<?php
require_once('../config/database.php');

$bancodedados = new db();
$link = $bancodedados->conecta_mysql();

$id = $_GET['id'] ?? '';

if ($id && $link) {
    $id = mysqli_real_escape_string($link, $id);
    
    // Agora mudamos o status para 'Concluído' em vez de apagar
    $sql = "UPDATE agendamentos SET status = 'Concluído' WHERE id = '$id'";

    if (mysqli_query($link, $sql)) {
        echo json_encode(["sucesso" => true]);
    } else {
        echo json_encode(["sucesso" => false, "erro" => mysqli_error($link)]);
    }
} else {
    echo json_encode(["sucesso" => false, "erro" => "ID ou conexão inválida"]);
}
exit;