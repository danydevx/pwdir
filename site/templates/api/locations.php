<?php namespace ProcessWire;

use const ProcessWire\TPL_REGION;
use const ProcessWire\TPL_LISTING;
use const ProcessWire\FLD_REGION;
use const ProcessWire\FLD_STATUS;
use const ProcessWire\STATUS_ACTIVE;

require_once __DIR__ . '/../_constants.php';

$regions = $pages->find("template=" . TPL_REGION . ", sort=title");

$results = [];
foreach ($regions as $region) {
    $listingCount = $pages->count("template=" . TPL_LISTING . ", " . FLD_REGION . "=$region, " . FLD_STATUS . "=" . STATUS_ACTIVE);

    $state = $region->parent;
    $country = $state->parent;

    $results[] = [
        'id' => $region->id,
        'title' => $region->title,
        'slug' => $region->name,
        'url' => $region->url,
        'state' => $state->title,
        'country' => $country->title,
        'listing_count' => $listingCount
    ];
}

echo json_encode([
    'success' => true,
    'count' => count($results),
    'data' => $results
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);