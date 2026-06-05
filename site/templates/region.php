<?php namespace ProcessWire;

use const ProcessWire\TPL_LISTING;
use const ProcessWire\FLD_STATUS;
use const ProcessWire\FLD_REGION;
use const ProcessWire\FLD_LATITUDE;
use const ProcessWire\FLD_LONGITUDE;
use const ProcessWire\FLD_EXCERPT;
use const ProcessWire\FLD_PRICE_MIN;
use const ProcessWire\STATUS_ACTIVE;

require_once __DIR__ . '/_constants.php';
require_once __DIR__ . '/_helpers.php';

$listings = $pages->find("template=" . TPL_LISTING . ", " . FLD_REGION . "={$page}, " . FLD_STATUS . "=" . STATUS_ACTIVE . ", sort=-created");

$listingsData = [];
foreach ($listings as $listing) {
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

$region = $page;
$state = $page->parent;
$country = $state->parent;
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
                <li class="breadcrumb-item"><a href="<?= $country->url ?>"><?= $country->title ?></a></li>
                <li class="breadcrumb-item"><a href="<?= $state->url ?>"><?= $state->title ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $page->title ?></li>
            </ol>
        </nav>

        <div class="row mb-4">
            <div class="col">
                <h1 class="mb-1"><?= $page->title ?></h1>
                <p class="text-muted mb-0"><?= $state->title ?>, <?= $country->title ?></p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <?php if ($listings->count()): ?>
                    <div class="row g-4">
                        <?php foreach ($listings as $listing): ?>
                            <?php include __DIR__ . '/partials/listing-card.php'; ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        No hay listings en esta región todavía.
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
            map.setView([20.6597, -103.3496], 11);
        }
    </script>
</body>
</html>