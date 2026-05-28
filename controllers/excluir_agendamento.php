<?php
require_once('../config/database.php');

$bancodedados = new db();
$link = $bancodedados->conecta_mysql();

$id = $_GET['id'] ?? '';

if ($id && $link) {
    $id = mysqli_real_escape_string($link, $id);
    
    // Altera o status para 'Excluido' em vez de deletar fisicamente
    $sql = "UPDATE agendamentos SET status = 'Excluido' WHERE id = '$id'";

    if (mysqli_query($link, $sql)) {
        echo json_encode(["sucesso" => true]);
    } else {
        echo json_encode(["sucesso" => false, "erro" => mysqli_error($link)]);
    }
} else {
    echo json_encode(["sucesso" => false, "erro" => "ID ou conexão inválida"]);
}
exit;
?>