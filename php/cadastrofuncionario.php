<?php
    $nome = $_POST['nome'];
    $rg = $_POST['rg'];
    $cpf = $_POST['cpf'];  
    $numero_registro = $_POST['numero_registro'];
    $cargo = $_POST['cargo'];
    $senha = $_POST['senha'];
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    require_once('conexao.php');

    $bancodedados = new db();

    $link = $bancodedados->conecta_mysql();

    $sql = "insert into cadastrofuncionario(nome, rg, cpf, numero_registro, cargo, senha) values('$nome', '$rg', '$cpf', '$numero_registro', '$cargo', '$senha')";

    mysqli_query($link, $sql);
?>