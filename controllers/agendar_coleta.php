<?php
require_once('../config/database.php');

$bancodedados = new db();
$link = $bancodedados->conecta_mysql();

// 2. Recebe os dados enviados pelo formulário da coleta
$cep         = $_POST['cep'];
$endereco    = $_POST['endereco'];
$data_coleta = $_POST['data_coleta'];
$hora_coleta = $_POST['hora_coleta'];

$materiais = isset($_POST['material']) ? implode(", ", $_POST['material']) : "Não especificado";

// 3. Verifica se a conexão deu certo
if ($link) {
    $cep      = mysqli_real_escape_string($link, $cep);
    $endereco = mysqli_real_escape_string($link, $endereco);
    $materiais = mysqli_real_escape_string($link, $materiais);

    $sql = "INSERT INTO agendamentos (cep, endereco, data_coleta, hora_coleta, materiais) 
            VALUES ('$cep', '$endereco', '$data_coleta', '$hora_coleta', '$materiais')";

    if (mysqli_query($link, $sql)) {
    echo "<script>
            alert('Agendamento realizado com sucesso!');
            window.location.href = '../views/coleta_de_material.php'; 
          </script>";
    } else {
    echo "Erro ao salvar no banco: " . mysqli_error($link);
    }
} else {
    echo "Erro de conexão com o servidor MySQL.";
}
?>