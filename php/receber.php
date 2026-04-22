<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método não permitido. Use POST.'
    ]);
    exit;
}

$ecopontoId   = filter_input(INPUT_POST, 'ecoponto_id', FILTER_VALIDATE_INT);
$distancia    = filter_input(INPUT_POST, 'distancia', FILTER_VALIDATE_FLOAT);
$porcentagem  = filter_input(INPUT_POST, 'porcentagem', FILTER_VALIDATE_INT);
$sinalValido  = filter_input(INPUT_POST, 'sinal_valido', FILTER_VALIDATE_INT);

if ($ecopontoId === false || $ecopontoId === null || $ecopontoId <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'ecoponto_id inválido.'
    ]);
    exit;
}

if ($distancia === false || $distancia === null || $distancia < 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'distancia inválida.'
    ]);
    exit;
}

if ($porcentagem === false || $porcentagem === null || $porcentagem < 0 || $porcentagem > 100) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'porcentagem inválida.'
    ]);
    exit;
}

if ($sinalValido === false || $sinalValido === null) {
    $sinalValido = 1;
}

try {
    $pdo = Database::getInstance()->getConnection();

    $pdo->beginTransaction();

    $sqlUpsert = "
        INSERT INTO status_lixeiras (
            ecoponto_id,
            distancia_cm,
            nivel_percentual,
            sinal_valido,
            atualizado_em
        ) VALUES (
            :ecoponto_id,
            :distancia_cm,
            :nivel_percentual,
            :sinal_valido,
            NOW()
        )
        ON DUPLICATE KEY UPDATE
            distancia_cm = VALUES(distancia_cm),
            nivel_percentual = VALUES(nivel_percentual),
            sinal_valido = VALUES(sinal_valido),
            atualizado_em = NOW()
    ";

    $stmt = $pdo->prepare($sqlUpsert);
    $stmt->execute([
        ':ecoponto_id' => $ecopontoId,
        ':distancia_cm' => $distancia,
        ':nivel_percentual' => $porcentagem,
        ':sinal_valido' => $sinalValido
    ]);

    $sqlHistorico = "
        INSERT INTO historico_lixeiras (
            ecoponto_id,
            distancia_cm,
            nivel_percentual,
            sinal_valido,
            registrado_em
        ) VALUES (
            :ecoponto_id,
            :distancia_cm,
            :nivel_percentual,
            :sinal_valido,
            NOW()
        )
    ";

    $stmtHist = $pdo->prepare($sqlHistorico);
    $stmtHist->execute([
        ':ecoponto_id' => $ecopontoId,
        ':distancia_cm' => $distancia,
        ':nivel_percentual' => $porcentagem,
        ':sinal_valido' => $sinalValido
    ]);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Status da lixeira atualizado com sucesso.'
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno ao salvar dados.',
        'error' => $e->getMessage()
    ]);
}