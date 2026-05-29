<?php
require_once '../config/database.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$bancodedados = new db();
$conn = $bancodedados->conecta_mysql();

$dataInicio = $_GET['data_inicio'] ?? '';
$dataFim = $_GET['data_fim'] ?? '';

$where = [];
$params = [];
$types = '';

if ($dataInicio !== '') {
    $where[] = "DATE(h.registrado_em) >= ?";
    $params[] = $dataInicio;
    $types .= 's';
}

if ($dataFim !== '') {
    $where[] = "DATE(h.registrado_em) <= ?";
    $params[] = $dataFim;
    $types .= 's';
}

$sql = "
SELECT 
    h.id,
    h.ecoponto_id,
    h.dispositivo_id,
    e.nome AS ecoponto,
    e.cidade,
    h.distancia_cm,
    h.nivel_percentual,
    h.registrado_em
FROM historico_lixeiras h
INNER JOIN ecopontos e ON e.id = h.ecoponto_id
";

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY h.registrado_em DESC LIMIT 500";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao preparar relatório.',
        'error' => $conn->error
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$dados = [];

while ($row = $result->fetch_assoc()) {
    $dados[] = [
        'id' => (int)$row['id'],
        'ecoponto_id' => (int)$row['ecoponto_id'],
        'dispositivo_id' => $row['dispositivo_id'] !== null ? (int)$row['dispositivo_id'] : null,
        'ecoponto' => $row['ecoponto'],
        'cidade' => $row['cidade'],
        'distancia_cm' => (float)$row['distancia_cm'],
        'nivel_percentual' => (int)$row['nivel_percentual'],
        'registrado_em' => $row['registrado_em']
    ];
}

echo json_encode([
    'success' => true,
    'dados' => $dados
], JSON_UNESCAPED_UNICODE);

$stmt->close();
$conn->close();