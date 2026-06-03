<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$pin = $input['pin'] ?? '';
$id = $input['id'] ?? '';

if ($pin !== '8023') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid PIN']);
    exit;
}

if (empty($id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID required']);
    exit;
}

$dataFile = __DIR__ . '/data.json';

if (!file_exists($dataFile)) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'No data found']);
    exit;
}

$content = file_get_contents($dataFile);
$data = json_decode($content, true) ?? [];

$newData = array_values(array_filter($data, function($entry) use ($id) {
    return $entry['id'] !== $id;
}));

if (count($newData) === count($data)) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Entry not found']);
    exit;
}

file_put_contents($dataFile, json_encode($newData, JSON_PRETTY_PRINT));

echo json_encode(['success' => true, 'message' => 'Entry deleted']);
