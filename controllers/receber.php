<?php
require_once '../config/database.php';

header('Content-Type: text/plain; charset=utf-8');

$bancodedados = new db();
$conn = $bancodedados->conecta_mysql();

$token = trim($_POST['device_token'] ?? $_GET['device_token'] ?? '');

$distancia = isset($_POST['distancia'])
    ? (float)$_POST['distancia']
    : (isset($_GET['distancia']) ? (float)$_GET['distancia'] : null);

$porcentagem = isset($_POST['porcentagem'])
    ? (int)$_POST['porcentagem']
    : (isset($_GET['porcentagem']) ? (int)$_GET['porcentagem'] : null);

$sinal_valido = isset($_POST['sinal_valido'])
    ? (int)$_POST['sinal_valido']
    : (isset($_GET['sinal_valido']) ? (int)$_GET['sinal_valido'] : 1);

if ($token === '' || $distancia === null || $porcentagem === null) {
    http_response_code(400);
    die("Dados incompletos.");
}

if ($porcentagem < 0 || $porcentagem > 100) {
    http_response_code(400);
    die("Porcentagem inválida.");
}

$sql = "
SELECT 
    e.id AS ecoponto_id, 
    d.id AS dispositivo_id
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
    http_response_code(404);
    die("ESP32 não vinculada a nenhum ecoponto.");
}

$dados = $result->fetch_assoc();

$ecoponto_id = (int)$dados['ecoponto_id'];
$dispositivo_id = (int)$dados['dispositivo_id'];

$stmt->close();

$sqlStatus = "
INSERT INTO status_lixeiras (
    ecoponto_id,
    dispositivo_id,
    distancia_cm,
    nivel_percentual,
    sinal_valido,
    atualizado_em
)
VALUES (?, ?, ?, ?, ?, NOW())
ON DUPLICATE KEY UPDATE
    dispositivo_id = VALUES(dispositivo_id),
    distancia_cm = VALUES(distancia_cm),
    nivel_percentual = VALUES(nivel_percentual),
    sinal_valido = VALUES(sinal_valido),
    atualizado_em = NOW()
";

$stmtStatus = $conn->prepare($sqlStatus);
$stmtStatus->bind_param(
    "iidii",
    $ecoponto_id,
    $dispositivo_id,
    $distancia,
    $porcentagem,
    $sinal_valido
);

if (!$stmtStatus->execute()) {
    http_response_code(500);
    die("Erro ao atualizar status: " . $conn->error);
}

$stmtStatus->close();

$sqlEcoponto = "
UPDATE ecopontos
SET 
    nivel_lixo = ?,
    atualizado_em = NOW()
WHERE id = ?
";

$stmtEcoponto = $conn->prepare($sqlEcoponto);
$stmtEcoponto->bind_param("ii", $porcentagem, $ecoponto_id);

if (!$stmtEcoponto->execute()) {
    http_response_code(500);
    die("Erro ao atualizar ecoponto: " . $conn->error);
}

$stmtEcoponto->close();

echo "OK - Nivel atualizado para {$porcentagem}%";

$conn->close();