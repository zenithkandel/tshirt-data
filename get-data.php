<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$pin = $_GET['pin'] ?? '';

if ($pin !== '8023') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid PIN']);
    exit;
}

$dataFile = __DIR__ . '/data.json';

if (!file_exists($dataFile)) {
    echo json_encode(['success' => true, 'data' => []]);
    exit;
}

$content = file_get_contents($dataFile);
$data = json_decode($content, true) ?? [];

$houseCounts = [];
$sizeCounts = [];

foreach ($data as $entry) {
    $h = $entry['house'];
    $s = $entry['size'];
    $houseCounts[$h] = ($houseCounts[$h] ?? 0) + 1;
    $sizeCounts[$s] = ($sizeCounts[$s] ?? 0) + 1;
}

arsort($houseCounts);
arsort($sizeCounts);

echo json_encode([
    'success' => true,
    'data' => $data,
    'analytics' => [
        'total' => count($data),
        'byHouse' => $houseCounts,
        'bySize' => $sizeCounts
    ]
]);
