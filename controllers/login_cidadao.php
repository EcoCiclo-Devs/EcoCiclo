<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('../config/database.php');

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? $_POST['password'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($email === '' || $senha === '') {
        header("Location: ../html/login_cidadao.html?erro=campos");
        exit;
    }

    try {
        $bancodedados = new db();
        $link = $bancodedados->conecta_mysql();

        $sql = "SELECT id, nome, email, senha, is_admin 
                FROM cadastrocidadao 
                WHERE email = ?";

        $stmt = $link->prepare($sql);

        if (!$stmt) {
            die("Erro na preparação da consulta: " . $link->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if (mysqli_stmt_execute($stmt)) {
                header('Location: ../cadastrofuncionario.html?sucesso=1');
                exit;
        } else {
                echo 'Erro ao cadastrar: ' . mysqli_stmt_error($stmt);
        }

        if ($result->num_rows === 1) {
            $cidadao = $result->fetch_assoc();

            // Login sem hash, como você pediu
            if ($senha === $cidadao['senha']) {

                $_SESSION['cidadao_id'] = $cidadao['id'];
                $_SESSION['cidadao_nome'] = $cidadao['nome'];
                $_SESSION['cidadao_email'] = $cidadao['email'];
                $_SESSION['is_admin'] = ((int)$cidadao['is_admin'] === 1);

                header("Location: ../index.php?login=sucesso");
                exit;

            } else {
                header("Location: ../html/login_cidadao.html?erro=senha");
                exit;
            }

        } else {
            header("Location: ../html/login_cidadao.html?erro=email");
            exit;
        }

        $stmt->close();
        $link->close();

    } catch (Exception $e) {
        echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
    }

} else {
    header("Location: ../html/login_cidadao.html");
    exit;
}
?>