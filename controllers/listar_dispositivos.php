<?php
require_once '../config/database.php';

$bancodedados = new db();
$conn = $bancodedados->conecta_mysql();

$sql = "SELECT id, nome_dispositivo FROM esp32_dispositivos WHERE ativo = 1 ORDER BY nome_dispositivo";
$result = $conn->query($sql);

$dados = [];

while ($row = $result->fetch_assoc()) {
    $dados[] = $row;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($dados, JSON_UNESCAPED_UNICODE);

$conn->close();