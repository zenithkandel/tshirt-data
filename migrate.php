<?php
header('Content-Type: application/json');

$dataFile = __DIR__ . '/data.json';

if (!file_exists($dataFile)) {
    echo json_encode(['success' => false, 'message' => 'No data.json found']);
    exit;
}

$content = file_get_contents($dataFile);
$data = json_decode($content, true) ?? [];

$updated = 0;
foreach ($data as &$entry) {
    if (!isset($entry['distributed'])) {
        $entry['distributed'] = false;
        $updated++;
    }
}
unset($entry);

file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));

echo json_encode(['success' => true, 'updated' => $updated, 'total' => count($data)]);
