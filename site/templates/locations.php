<?php namespace ProcessWire;

use const ProcessWire\TPL_LISTING;
use const ProcessWire\TPL_COUNTRY;
use const ProcessWire\FLD_STATUS;
use const ProcessWire\FLD_LATITUDE;
use const ProcessWire\FLD_LONGITUDE;
use const ProcessWire\FLD_EXCERPT;
use const ProcessWire\FLD_PRICE_MIN;
use const ProcessWire\STATUS_ACTIVE;

require_once __DIR__ . '/_constants.php';
require_once __DIR__ . '/_helpers.php';

$countries = $pages->find("template=" . TPL_COUNTRY . ", sort=title");

$allListings = $pages->find("template=" . TPL_LISTING . ", " . FLD_STATUS . "=" . STATUS_ACTIVE . ", sort=-created");

$listingsData = [];
foreach ($allListings as $listing) {
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
    <title>Locations - Directorio</title>
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="container py-5">
        <div class="row mb-4">
            <div class="col">
                <h1 class="mb-1">Locations</h1>
            </div>
        </div>

        <?php if ($countries->count()): ?>
            <div class="row mb-4">
                <div class="col">
                    <h5>Países</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($countries as $country): ?>
                            <a href="<?= $country->url ?>" class="badge bg-primary text-decoration-none"><?= $country->title ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <?php if ($allListings->count()): ?>
                    <div class="row g-4">
                        <?php foreach ($allListings as $listing): ?>
                            <?php include __DIR__ . '/partials/listing-card.php'; ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        No hay listings todavía.
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