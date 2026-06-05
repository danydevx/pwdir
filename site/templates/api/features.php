<?php namespace ProcessWire;

$features = $pages->find("template=listing-feature, sort=title");

$results = [];
foreach ($features as $feature) {
    $results[] = [
        'id' => $feature->id,
        'title' => $feature->title,
        'slug' => $feature->name,
        'url' => $feature->url,
        'body' => $feature->body ?: null
    ];
}

echo json_encode([
    'success' => true,
    'count' => count($results),
    'data' => $results
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);