<?php
require_once '../config/database.php';

$bancodedados = new db();
$conn = $bancodedados->conecta_mysql();

$nome = trim($_POST['nome'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$logradouro = trim($_POST['logradouro'] ?? '');
$numero = trim($_POST['numero'] ?? '');
$complemento = trim($_POST['complemento'] ?? '');
$bairro = trim($_POST['bairro'] ?? '');
$uf = trim($_POST['uf'] ?? '');
$cep = trim($_POST['cep'] ?? '');
$tipo = trim($_POST['tipo_residuo'] ?? '');
$dispositivo_id = (int)($_POST['dispositivo_id'] ?? 0);

if (
    $nome === '' || $cidade === '' || $logradouro === '' ||
    $numero === '' || $uf === '' || $cep === '' ||
    $tipo === '' || $dispositivo_id <= 0
) {
    die("Preencha todos os campos obrigatórios.");
}

function buscarCoordenadas($endereco) {
    $url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($endereco) . "&format=jsonv2&limit=1&countrycodes=br&addressdetails=1";

    $options = [
        "http" => [
            "header" => "User-Agent: EcoCiclo/1.0\r\n"
        ]
    ];

    $context = stream_context_create($options);
    $resposta = @file_get_contents($url, false, $context);

    if ($resposta === false) {
        return null;
    }

    $dados = json_decode($resposta, true);

    if (!$dados || !isset($dados[0]['lat']) || !isset($dados[0]['lon'])) {
        return null;
    }

    return [
        'lat' => (float)$dados[0]['lat'],
        'lon' => (float)$dados[0]['lon']
    ];
}

// Tentativa 1: endereço completo
$busca1 = "$logradouro $numero";

if ($bairro !== '') {
    $busca1 .= ", $bairro";
}

$busca1 .= ", $cidade, $uf, Brasil";

$coords = buscarCoordenadas($busca1);

// Tentativa 2: sem número
if ($coords === null) {
    $busca2 = "$logradouro";
    if ($bairro !== '') {
        $busca2 .= ", $bairro";
    }
    $busca2 .= ", $cidade, $uf, Brasil";

    $coords = buscarCoordenadas($busca2);
}

// Tentativa 3: só cidade/UF
if ($coords === null) {
    $busca3 = "$cidade, $uf, Brasil";
    $coords = buscarCoordenadas($busca3);
}

if ($coords === null) {
    die("Erro ao localizar endereço no mapa.");
}

$lat = $coords['lat'];
$lon = $coords['lon'];

$enderecoCompleto = "$logradouro, $numero";
if ($complemento !== '') {
    $enderecoCompleto .= " - $complemento";
}
if ($bairro !== '') {
    $enderecoCompleto .= " - $bairro";
}
$enderecoCompleto .= " - $cidade/$uf - CEP: $cep";

$sql = "INSERT INTO ecopontos
(nome, cidade, endereco, latitude, longitude, tipo_residuo, nivel_lixo, dispositivo_id)
VALUES (?, ?, ?, ?, ?, ?, 0, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssddsi", $nome, $cidade, $enderecoCompleto, $lat, $lon, $tipo, $dispositivo_id);

if ($stmt->execute()) {
    header("Location: ../views/ecopontos.php?sucesso=1");
    exit;
} else {
    die("Erro ao cadastrar ecoponto.");
}