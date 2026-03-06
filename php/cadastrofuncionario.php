<?php
    $nome = $_POST['nome'];
    $rg = $_POST['rg'];
    $cpf = $_POST['cpf'];  
    $numero_registro = $_POST['numero_registro'];
    $cargo = $_POST['cargo'];
    $senha = $_POST['senha'];
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    require_once __DIR__ . '/conexao.php';

    $bancodedados = new db();
    $link = $bancodedados->conecta_mysql();

    // Prepared statement seguro
    $stmt = mysqli_prepare($link, "INSERT INTO cadastrofuncionario (nome, rg, cpf, numero_registro, cargo, senha) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die('Prepare failed: ' . mysqli_error($link));
    }

    mysqli_stmt_bind_param($stmt, 'ssssss', $nome, $rg, $cpf, $numero_registro, $cargo, $senhaHash);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: ../cadastrofuncionario.html?sucesso=1');
        exit;
    } else {
        echo 'Erro ao cadastrar: ' . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
?>