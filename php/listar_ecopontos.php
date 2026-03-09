<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/conexao.php';

$bancodedados = new db();
$link = $bancodedados->conecta_mysql();

$sql = "SELECT id, nome, cidade, endereco, latitude, longitude, tipo_residuo, nivel_lixo
        FROM ecopontos
        ORDER BY cidade, nome";

$result = mysqli_query($link, $sql);

$ecopontos = [];

while ($row = mysqli_fetch_assoc($result)) {
    $ecopontos[] = [
        'id' => (int)$row['id'],
        'nome' => $row['nome'],
        'cidade' => $row['cidade'],
        'endereco' => $row['endereco'],
        'latitude' => (float)$row['latitude'],
        'longitude' => (float)$row['longitude'],
        'tipo_residuo' => $row['tipo_residuo'],
        'nivel_lixo' => (int)$row['nivel_lixo']
    ];
}

echo json_encode($ecopontos, JSON_UNESCAPED_UNICODE);

mysqli_close($link);
?>