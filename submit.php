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

if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$name = trim($input['name'] ?? '');
$rollNumber = trim($input['rollNumber'] ?? '');
$house = trim($input['house'] ?? '');
$size = trim($input['size'] ?? '');

if (empty($name) || empty($rollNumber) || empty($house) || empty($size)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

$dataFile = __DIR__ . '/data.json';

$data = [];
if (file_exists($dataFile)) {
    $content = file_get_contents($dataFile);
    $data = json_decode($content, true) ?? [];
}

foreach ($data as $entry) {
    if (strtolower($entry['rollNumber']) === strtolower($rollNumber)) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'This roll number has already submitted']);
        exit;
    }
}

$newEntry = [
    'id' => uniqid('sub_', true),
    'name' => $name,
    'rollNumber' => $rollNumber,
    'house' => $house,
    'size' => $size,
    'distributed' => false,
    'submittedAt' => date('c')
];

$data[] = $newEntry;

if (file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT)) === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to save data']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Submission recorded successfully']);
