<?php
require_once '../config/database.php';

$bancodedados = new db();
$conn = $bancodedados->conecta_mysql();

$token = trim($_POST['device_token'] ?? '');
$distancia = isset($_POST['distancia']) ? (float)$_POST['distancia'] : null;
$porcentagem = isset($_POST['porcentagem']) ? (int)$_POST['porcentagem'] : null;
$sinal_valido = isset($_POST['sinal_valido']) ? (int)$_POST['sinal_valido'] : 1;

if ($token === '' || $distancia === null || $porcentagem === null) {
    die("Dados incompletos.");
}

// Procura a ESP32 e o ecoponto vinculado a ela
$sql = "
SELECT e.id AS ecoponto_id, d.id AS dispositivo_id
FROM esp32_dispositivos d
INNER JOIN ecopontos e ON e.dispositivo_id = d.id
WHERE d.device_token = ?
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("ESP32 não vinculada a nenhum ecoponto.");
}

$dados = $result->fetch_assoc();

$ecoponto_id = (int)$dados['ecoponto_id'];
$dispositivo_id = (int)$dados['dispositivo_id'];

// Atualiza ou insere o status da lixeira
$sql = "
INSERT INTO status_lixeiras (
    ecoponto_id,
    dispositivo_id,
    distancia_cm,
    nivel_percentual,
    atualizado_em
)
VALUES (?, ?, ?, ?, NOW())
ON DUPLICATE KEY UPDATE
    dispositivo_id = VALUES(dispositivo_id),
    distancia_cm = VALUES(distancia_cm),
    nivel_percentual = VALUES(nivel_percentual),
    atualizado_em = NOW()
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iidi", $ecoponto_id, $dispositivo_id, $distancia, $porcentagem);

if ($stmt->execute()) {
    echo "OK";
} else {
    echo "Erro ao atualizar status.";
}

$stmt->close();
$conn->close();