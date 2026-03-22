<?php
session_start();

if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    die('Acesso negado.');
}

require_once __DIR__ . '/../config/database.php';

$nome = trim($_POST['nome'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$tipo_residuo = trim($_POST['tipo_residuo'] ?? '');
$nivel_lixo = (int)($_POST['nivel_lixo'] ?? 0);
$latitude = trim($_POST['latitude'] ?? '');
$longitude = trim($_POST['longitude'] ?? '');

if ($nome === '' || $cidade === '' || $endereco === '' || $latitude === '' || $longitude === '') {
    die('Preencha todos os campos obrigatórios.');
}

$bancodedados = new db();
$link = $bancodedados->conecta_mysql();

$sql = "INSERT INTO ecopontos (nome, cidade, endereco, latitude, longitude, tipo_residuo, nivel_lixo)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($link, $sql);

if (!$stmt) {
    die('Erro no prepare: ' . mysqli_error($link));
}

mysqli_stmt_bind_param($stmt, 'sssddsi', $nome, $cidade, $endereco, $latitude, $longitude, $tipo_residuo, $nivel_lixo);

if (mysqli_stmt_execute($stmt)) {
    header('Location: ../html/ecopontos.php?sucesso=1');
    exit;
} else {
    echo 'Erro ao cadastrar ecoponto: ' . mysqli_stmt_error($stmt);
}

mysqli_stmt_close($stmt);
mysqli_close($link);
?>