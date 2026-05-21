<?php
require_once '../config/database.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$bancodedados = new db();
$conn = $bancodedados->conecta_mysql();

$sql = "
SELECT 
    e.id,
    e.nome,
    e.cidade,
    e.endereco,
    e.latitude,
    e.longitude,
    e.tipo_residuo,
    e.dispositivo_id,
    COALESCE(s.nivel_percentual, e.nivel_lixo, 0) AS nivel_lixo,
    COALESCE(s.atualizado_em, e.atualizado_em) AS atualizado_em
FROM ecopontos e
LEFT JOIN status_lixeiras s ON s.ecoponto_id = e.id
ORDER BY e.nome ASC
";

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode([
        'erro' => 'Erro ao listar ecopontos.',
        'detalhe' => $conn->error
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$ecopontos = [];

while ($row = $result->fetch_assoc()) {
    $ecopontos[] = [
        'id' => (int)$row['id'],
        'nome' => $row['nome'],
        'cidade' => $row['cidade'],
        'endereco' => $row['endereco'],
        'latitude' => (float)$row['latitude'],
        'longitude' => (float)$row['longitude'],
        'tipo_residuo' => $row['tipo_residuo'],
        'dispositivo_id' => $row['dispositivo_id'] !== null ? (int)$row['dispositivo_id'] : null,
        'nivel_lixo' => (int)$row['nivel_lixo'],
        'atualizado_em' => $row['atualizado_em']
    ];
}

echo json_encode($ecopontos, JSON_UNESCAPED_UNICODE);

$conn->close();