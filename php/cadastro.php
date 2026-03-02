<?php
    $nome = $_POST['nome'];
    $cep = $_POST['cep'];
    $estado = $_POST['estado'];  
    $cidade = $_POST['cidade'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    echo $nome . ' ' . $cep . ' ' . $estado . ' ' . $estado . ' ' . $cidade . ' ' . $cpf . ' ' . $email . ' ' . $senha;

    require_once __DIR__ . '/conexao.php';

    $bancodedados = new db();
    $link = $bancodedados->conecta_mysql();

    // Prepared statement seguro
    $stmt = mysqli_prepare($link, "INSERT INTO cadastrocidadao (nome, cep, estado, cidade, cpf, email, senha) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die('Prepare failed: ' . mysqli_error($link));
    }

    mysqli_stmt_bind_param($stmt, 'sssssss', $nome, $cep, $estado, $cidade, $cpf, $email, $senha);

    if (mysqli_stmt_execute($stmt)) {
        echo 'Cadastro realizado com sucesso';
    } else {
        echo 'Erro ao cadastrar: ' . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
?>