<?php
require_once '../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$bancodedados = new db();
$conn = $bancodedados->conecta_mysql();

$token = trim($_POST['device_token'] ?? $_GET['device_token'] ?? '');

$distancia = isset($_POST['distancia'])
    ? (float)$_POST['distancia']
    : (isset($_GET['distancia']) ? (float)$_GET['distancia'] : null);

$porcentagem = isset($_POST['porcentagem'])
    ? (int)$_POST['porcentagem']
    : (isset($_GET['porcentagem']) ? (int)$_GET['porcentagem'] : null);

if ($token === '' || $distancia === null || $porcentagem === null) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Dados incompletos.'
    ]);
    exit;
}

if ($distancia < 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Distância inválida.'
    ]);
    exit;
}

if ($porcentagem < 0 || $porcentagem > 100) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Porcentagem inválida.'
    ]);
    exit;
}

// Procura a ESP32 e o ecoponto vinculado
$sqlBusca = "
SELECT 
    e.id AS ecoponto_id,
    d.id AS dispositivo_id
FROM esp32_dispositivos d
INNER JOIN ecopontos e ON e.dispositivo_id = d.id
WHERE d.device_token = ?
LIMIT 1
";

$stmtBusca = $conn->prepare($sqlBusca);

if (!$stmtBusca) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao preparar busca do dispositivo.',
        'error' => $conn->error
    ]);
    exit;
}

$stmtBusca->bind_param("s", $token);
$stmtBusca->execute();
$resultBusca = $stmtBusca->get_result();

if ($resultBusca->num_rows === 0) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'ESP32 não vinculada a nenhum ecoponto.'
    ]);
    exit;
}

$dados = $resultBusca->fetch_assoc();

$ecoponto_id = (int)$dados['ecoponto_id'];
$dispositivo_id = (int)$dados['dispositivo_id'];

$stmtBusca->close();

// Atualiza ou insere o status atual da lixeira
$sqlStatus = "
INSERT INTO status_lixeiras (
    ecoponto_id,
    dispositivo_id,
    distancia_cm,
    nivel_percentual,
    atualizado_em
)
VALUES (?, ?, ?, ?, NOW())
ON DUPLICATE KEY UPDATE
    dispositivo_id = VALUES(dispositivo_id),
    distancia_cm = VALUES(distancia_cm),
    nivel_percentual = VALUES(nivel_percentual),
    atualizado_em = NOW()
";

$stmtStatus = $conn->prepare($sqlStatus);

if (!$stmtStatus) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao preparar atualização do status.',
        'error' => $conn->error
    ]);
    exit;
}

$stmtStatus->bind_param(
    "iidi",
    $ecoponto_id,
    $dispositivo_id,
    $distancia,
    $porcentagem
);

if (!$stmtStatus->execute()) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao atualizar status da lixeira.',
        'error' => $stmtStatus->error
    ]);
    exit;
}

$stmtStatus->close();

// Atualiza a tabela ecopontos, que é usada pelo mapa/listagem
$sqlEcoponto = "
UPDATE ecopontos
SET 
    nivel_lixo = ?,
    atualizado_em = NOW()
WHERE id = ?
";

$stmtEcoponto = $conn->prepare($sqlEcoponto);

if (!$stmtEcoponto) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao preparar atualização do ecoponto.',
        'error' => $conn->error
    ]);
    exit;
}

$stmtEcoponto->bind_param("ii", $porcentagem, $ecoponto_id);

if (!$stmtEcoponto->execute()) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao atualizar ecoponto.',
        'error' => $stmtEcoponto->error
    ]);
    exit;
}

$stmtEcoponto->close();

echo json_encode([
    'success' => true,
    'message' => 'Status da lixeira atualizado com sucesso.',
    'ecoponto_id' => $ecoponto_id,
    'dispositivo_id' => $dispositivo_id,
    'distancia' => $distancia,
    'porcentagem' => $porcentagem
]);

$conn->close();