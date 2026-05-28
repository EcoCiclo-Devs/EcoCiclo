<?php
require_once('../config/database.php');

$bancodedados = new db();
$link = $bancodedados->conecta_mysql();

$data = $_GET['data'] ?? '';
$ocupados = [];

if ($data) {
    $data_segura = mysqli_real_escape_string($link, $data);
    
    $sql = "SELECT hora_coleta FROM agendamentos 
        WHERE data_coleta = '$data_segura' 
        AND status != 'Excluido'";
    $resultado = mysqli_query($link, $sql);

    if ($resultado) {
        while ($row = mysqli_fetch_assoc($resultado)) {
            $ocupados[] = substr($row['hora_coleta'], 0, 5);
        }
    }
}

header('Content-Type: application/json');
echo json_encode($ocupados);
?>