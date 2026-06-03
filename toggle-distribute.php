<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
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

$found = false;
foreach ($data as &$entry) {
    if ($entry['id'] === $id) {
        $entry['distributed'] = !($entry['distributed'] ?? false);
        $found = true;
        break;
    }
}
unset($entry);

if (!$found) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Entry not found']);
    exit;
}

if (file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT)) === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to save data']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Distribution status toggled']);
