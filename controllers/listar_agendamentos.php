<?php
require_once('../config/database.php');

$bancodedados = new db();
$link = $bancodedados->conecta_mysql();

$agendamentos = [];

if ($link) {
    // Busca os agendamentos da semana
    $sql = "SELECT id, data_coleta, hora_coleta, endereco, materiais, status FROM agendamentos ORDER BY data_coleta DESC";
    $resultado = mysqli_query($link, $sql);

    while ($row = mysqli_fetch_assoc($resultado)) {
        // Formata a data para o formato brasileiro
        $row['data_coleta'] = date('d/m/Y', strtotime($row['data_coleta']));
        // Pega apenas horas e minutos
        $row['hora_coleta'] = substr($row['hora_coleta'], 0, 5);
        $agendamentos[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($agendamentos);