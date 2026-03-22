<?php
    require_once('../config/database.php');

    $bancodedados = new db();

    $link = $bancodedados->conecta_mysql();

    $sql = "insert into cadastro(nome, cep, estado, cidade, cpf, email, senha) values('$nome', $cep, $estado, $cidade, $cpf, $email, '$senhaHash')";
    $sql = "insert into cadastro(nome, rg, cpf, cargo, numero_registro, email, senha) values('$nome', $rg, $cpf, '$cargo', $numero_registro, $email, '$senhaHash')";
?>