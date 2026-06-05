<?php namespace ProcessWire;

$endpoint = $input->get('endpoint') ?: 'listings';
$method = strtolower($input->requestMethod());

$allowed = ['listings', 'listing', 'features', 'locations'];
if (!in_array($endpoint, $allowed)) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Endpoint not found', 'available' => $allowed]);
    return;
}

$apiFile = __DIR__ . '/api/' . $endpoint . '.php';
if (file_exists($apiFile)) {
    include($apiFile);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'File not found for endpoint: ' . $endpoint]);
}