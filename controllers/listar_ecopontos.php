<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../config/database.php';

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
    COALESCE(s.nivel_percentual, e.nivel_lixo, 0) AS nivel_lixo,
    DATE_FORMAT(s.atualizado_em, '%d/%m/%Y %H:%i:%s') AS atualizado_em
FROM ecopontos e
LEFT JOIN status_lixeiras s ON s.ecoponto_id = e.id
ORDER BY e.cidade, e.nome
";

$result = $conn->query($sql);

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
        'nivel_lixo' => (int)$row['nivel_lixo'],
        'atualizado_em' => $row['atualizado_em']
    ];
}

echo json_encode($ecopontos, JSON_UNESCAPED_UNICODE);

$conn->close();