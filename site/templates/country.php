<?php namespace ProcessWire;

use const ProcessWire\TPL_LISTING;
use const ProcessWire\TPL_STATE;
use const ProcessWire\FLD_STATUS;
use const ProcessWire\FLD_REGION;
use const ProcessWire\FLD_LATITUDE;
use const ProcessWire\FLD_LONGITUDE;
use const ProcessWire\FLD_EXCERPT;
use const ProcessWire\FLD_PRICE_MIN;
use const ProcessWire\STATUS_ACTIVE;

require_once __DIR__ . '/_constants.php';
require_once __DIR__ . '/_helpers.php';

$country = $page;
$states = $pages->find("template=" . TPL_STATE . ", parent={$country}, sort=title");

$stateIds = [];
foreach ($states as $state) {
    $stateIds[] = $state->id;
}

$allListings = $pages->find("template=" . TPL_LISTING . ", " . FLD_STATUS . "=" . STATUS_ACTIVE . ", sort=-created");
$filteredListings = new PageArray();
foreach ($allListings as $listing) {
    $region = $listing->{FLD_REGION};
    if ($region && $region->id) {
        $state = $region->parent;
        if ($state && $state->id && in_array($state->id, $stateIds)) {
            $filteredListings->add($listing);
        }
    }
}

$listingsData = [];
foreach ($filteredListings as $listing) {
    $lat = $listing->{FLD_LATITUDE};
    $lng = $listing->{FLD_LONGITUDE};
    if ($lat && $lng) {
        $listingsData[] = [
            'id' => $listing->id,
            'title' => $listing->title,
            'url' => $listing->url,
            'lat' => (float) $lat,
            'lng' => (float) $lng,
            'excerpt' => $listing->{FLD_EXCERPT} ?: '',
            'price' => $listing->{FLD_PRICE_MIN} ?: null,
        ];
    }
}
$listingsJson = json_encode($listingsData);
?>
<!doctype html>
<html lang="es">
<head>
    <?php include __DIR__ . '/partials/head.php'; ?>
    <title><?= $page->title ?> - Directorio</title>
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item"><a href="<?= dir_locations()->url ?>">Locations</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $page->title ?></li>
            </ol>
        </nav>

        <div class="row mb-4">
            <div class="col">
                <h1 class="mb-1"><?= $page->title ?></h1>
            </div>
        </div>

        <?php if ($states->count()): ?>
            <div class="row mb-4">
                <div class="col">
                    <h5>Estados</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($states as $state): ?>
                            <a href="<?= $state->url ?>" class="badge bg-primary text-decoration-none"><?= $state->title ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <?php if ($filteredListings->count()): ?>
                    <div class="row g-4">
                        <?php foreach ($filteredListings as $listing): ?>
                            <?php include __DIR__ . '/partials/listing-card.php'; ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        No hay listings en este país todavía.
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-4">
                <div id="map" style="height: 400px;"></div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const listings = <?= $listingsJson ?>;
        const map = L.map('map');

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        if (listings.length > 0) {
            const bounds = L.latLngBounds(listings.map(l => [l.lat, l.lng]));
            map.fitBounds(bounds, { padding: [50, 50] });

            listings.forEach(l => {
                L.marker([l.lat, l.lng])
                    .bindPopup(`<a href="${l.url}">${l.title}</a>`)
                    .addTo(map);
            });
        } else {
            map.setView([20.6597, -103.3496], 6);
        }
    </script>
</body>
</html>