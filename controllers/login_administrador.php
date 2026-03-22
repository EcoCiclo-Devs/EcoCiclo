<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('../config/database.php'); // permanece intacto

$numero_registro = trim($_POST['numero_registro'] ?? '');
$senha = $_POST['senha'] ?? '';

$msg = '';
$msg_class = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($numero_registro === '' || $senha === '') {
        $msg = 'Número de registro e senha são obrigatórios.';
        $msg_class = 'erro';
    } else {
        try {
            $bancodedados = new db();
            $link = $bancodedados->conecta_mysql();

            $sql = "SELECT id, nome, senha FROM cadastrofuncionario WHERE numero_registro = ?";
            $stmt = $link->prepare($sql);
            $stmt->bind_param('s', $numero_registro);
            $stmt->execute();
            $result = $stmt->get_result();

            if (mysqli_stmt_execute($stmt)) {
                header('Location: ../cadastrofuncionario.html?sucesso=1');
                exit;
            } else {
                echo 'Erro ao cadastrar: ' . mysqli_stmt_error($stmt);
            }

            if ($result->num_rows === 0) {
                $msg = 'Número de registro ou senha incorretos.';
                $msg_class = 'erro';
            } else {
                $funcionario = $result->fetch_assoc();
                // Alternativa temporária: senha em texto simples
                if ($senha === $funcionario['senha']) {
                    $_SESSION['funcionario_id'] = $funcionario['id'];
                    $_SESSION['funcionario_nome'] = $funcionario['nome'];
                    // sinaliza sucesso para mostrar modal e redirecionar em client-side
                    $loginSuccess = true;
                    $redirectAfter = 'adminDashboard.php';
                } else {
                    $msg = 'Número de registro ou senha incorretos.';
                    $msg_class = 'erro';
                }
            }

            $stmt->close();
            $link->close();
        } catch (Exception $e) {
            $msg = 'Erro ao conectar ao banco de dados: ' . $e->getMessage();
            $msg_class = 'erro';
        }
    }
}
?>
