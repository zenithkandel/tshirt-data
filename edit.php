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
$name = trim($input['name'] ?? '');
$rollNumber = trim($input['rollNumber'] ?? '');
$house = trim($input['house'] ?? '');
$size = trim($input['size'] ?? '');

if ($pin !== '8023') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid PIN']);
    exit;
}

if (empty($id) || empty($name) || empty($rollNumber) || empty($house) || empty($size)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
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
        foreach ($data as $check) {
            if ($check['id'] !== $id && strtolower($check['rollNumber']) === strtolower($rollNumber)) {
                http_response_code(409);
                echo json_encode(['success' => false, 'message' => 'This roll number is already used by another entry']);
                exit;
            }
        }
        $entry['name'] = $name;
        $entry['rollNumber'] = $rollNumber;
        $entry['house'] = $house;
        $entry['size'] = $size;
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

echo json_encode(['success' => true, 'message' => 'Entry updated successfully']);
