<?php

session_start();

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$cep = $_POST['cep'];
$rua = $_POST['rua'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];  
$email = $_POST['email'];
$senha = $_POST['password'];

require_once __DIR__ . '/../config/database.php';

$bancodedados = new db();
$link = $bancodedados->conecta_mysql();

$stmt = mysqli_prepare($link, "INSERT INTO cadastrocidadao (nome, cpf, cep, rua, bairro, cidade, estado, email, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

mysqli_stmt_bind_param($stmt, 'sssssssss', $nome, $cpf, $cep, $rua, $bairro, $cidade, $estado, $email, $senha);

if (mysqli_stmt_execute($stmt)) {

    $id = mysqli_insert_id($link);

    $_SESSION['cidadao_id'] = $id;
    $_SESSION['cidadao_nome'] = $nome;

    header('Location: ../login.php?cadastro=sucesso');
    exit;

} else {
    echo 'Erro ao cadastrar: ' . mysqli_stmt_error($stmt);
}