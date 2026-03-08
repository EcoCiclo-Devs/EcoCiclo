<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('../model/conexao.php');

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

$msg = '';
$msg_class = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($email === '' || $senha === '') {
        $msg = 'E-mail e senha são obrigatórios.';
        $msg_class = 'erro';
    } else {
        try {
            $bancodedados = new db();
            $link = $bancodedados->conecta_mysql();

            $sql = "SELECT id, nome, senha FROM cadastrocidadao WHERE email = ?";
            $stmt = $link->prepare($sql);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                $msg = 'E-mail ou senha incorretos.';
                $msg_class = 'erro';
            } else {
                $cidadao = $result->fetch_assoc();

                if ($senha === $cidadao['senha']) {

                    // Seta sessão
                    $_SESSION['cidadao_id'] = $cidadao['id'];
                    $_SESSION['cidadao_nome'] = $cidadao['nome'];

                    // sinaliza sucesso para mostrar modal após renderizar a página
                    $loginSuccess = true;
                    $redirectAfter = 'index.html'; // redireciona para a página inicial do cidadão
                } else {
                    $msg = 'E-mail ou senha incorretos.';
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