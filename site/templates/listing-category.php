<?php namespace ProcessWire;

use const ProcessWire\TPL_LISTING;
use const ProcessWire\FLD_STATUS;
use const ProcessWire\STATUS_ACTIVE;

require_once __DIR__ . '/_constants.php';

$categories = $pages->find("template=listing-category, sort=title");

$q = $input->get('q');
$categoryFilter = $input->get('category');
$capacityFilter = $input->get('capacity');

$limit = 3;
$pageNum = $input->get('page', 'int') ?: 1;
$start = ($pageNum - 1) * $limit;

$selector = "template=" . TPL_LISTING . ", " . FLD_STATUS . "=" . STATUS_ACTIVE . ", sort=-created";

if ($categoryFilter) {
    $selector .= ", fld_category.name={$categoryFilter}";
}

$total = $pages->count($selector);

if ($q) {
    $listings = $pages->find($selector);
    $listings = $listings->filter("title*fld_name*fld_excerpt*fld_description*={$q}");
    $total = $listings->count();
    $listings = $listings->slice($start, $limit);
} elseif ($capacityFilter) {
    $listings = $pages->find($selector);
    $listings = $listings->filter("fld_capacity_min<={$capacityFilter}, fld_capacity_max>={$capacityFilter}");
    $total = $listings->count();
    $listings = $listings->slice($start, $limit);
} else {
    $listings = $pages->find($selector . ", start={$start}, limit={$limit}");
}

$listingsData = [];
foreach ($listings as $listing) {
    $lat = $listing->fld_latitude;
    $lng = $listing->fld_longitude;
    if ($lat && $lng) {
        $listingsData[] = [
            'id' => $listing->id,
            'title' => $listing->title,
            'url' => $listing->url,
            'lat' => (float) $lat,
            'lng' => (float) $lng,
            'excerpt' => $listing->fld_excerpt ?: '',
            'price' => $listing->fld_price_min ?: null,
        ];
    }
}
$listingsJson = json_encode($listingsData);

$pager = '';
$totalPages = $total > 0 ? ceil($total / $limit) : 1;

if ($totalPages > 1) {
    $paginationBase = '/listings/?';
    if ($q) $paginationBase .= 'q=' . urlencode($q) . '&';
    if ($categoryFilter) $paginationBase .= 'category=' . urlencode($categoryFilter) . '&';

    $pager .= '<nav aria-label="Pagination"><ul class="pagination justify-content-center">';

    if ($pageNum > 1) {
        $prevPage = $pageNum - 1;
        $pager .= '<li class="page-item"><a class="page-link" href="' . $paginationBase . 'page=' . $prevPage . '">Anterior</a></li>';
    } else {
        $pager .= '<li class="page-item disabled"><span class="page-link">Anterior</span></li>';
    }

    for ($p = 1; $p <= $totalPages; $p++) {
        if ($p == $pageNum) {
            $pager .= '<li class="page-item active"><span class="page-link">' . $p . '</span></li>';
        } else {
            $pager .= '<li class="page-item"><a class="page-link" href="' . $paginationBase . 'page=' . $p . '">' . $p . '</a></li>';
        }
    }

    if ($pageNum < $totalPages) {
        $nextPage = $pageNum + 1;
        $pager .= '<li class="page-item"><a class="page-link" href="' . $paginationBase . 'page=' . $nextPage . '">Siguiente</a></li>';
    } else {
        $pager .= '<li class="page-item disabled"><span class="page-link">Siguiente</span></li>';
    }

    $pager .= '</ul></nav>';
}
?>
<!doctype html>
<html lang="es">
<head>
    <?php include __DIR__ . '/partials/head.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="site-main">
        <section class="listing-hero py-5 bg-light">
            <div class="container">
                <div class="text-center mb-4">
                    <h1 class="mb-3">Encuentra tu lugar perfecto</h1>
                    <p class="lead text-muted">Terrazas, salones y espacios para eventos</p>
                </div>

                <div id="sw-container"></div>
            </div>
        </section>

        <section class="listing-results py-4">
            <div class="container">
                <?php if ($listings->count()): ?>
                <div class="mb-4">
                    <div id="listings-map" style="height: 350px; border-radius: 8px; overflow: hidden; border: 1px solid #dee2e6;"></div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <p class="text-muted mb-0">Mostrando <?php echo $listings->count(); ?> de <?php echo $total; ?> lugares (página <?php echo $pageNum; ?> de <?php echo $totalPages; ?>)</p>
                    </div>
                </div>
                <?php if ($pager): ?>
                <div class="mb-3">
                    <?php echo $pager; ?>
                </div>
                <?php endif; ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($listings as $listing): ?>
                    <div class="col">
                        <?php
                        $savePage = $page;
                        $page = $listing;
                        include __DIR__ . '/partials/listing-card.php';
                        $page = $savePage;
                        ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="alert alert-info">
                    No se encontraron lugares con esos filtros. Intenta con otros criterios.
                    <a href="/listings/" class="btn btn-outline-primary btn-sm ms-3">Ver todos</a>
                </div>
                <?php endif; ?>
                <?php if ($pager): ?>
                <div class="mt-3">
                    <?php echo $pager; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var mapListings = <?php echo $listingsJson; ?>;
        if (mapListings.length === 0) return;

        var map = L.map('listings-map').setView([20.68, -103.35], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        mapListings.forEach(function(item) {
            var priceStr = item.price ? 'Desde $' + item.price.toLocaleString() : '';
            var marker = L.marker([item.lat, item.lng]).addTo(map);
            marker.bindPopup('<a href="' + item.url + '"><strong>' + item.title + '</strong></a>' +
                (item.excerpt ? '<br><small>' + item.excerpt.substring(0, 80) + '...</small>' : '') +
                (priceStr ? '<br><strong>' + priceStr + '</strong>' : ''));
        });

        var group = new L.featureGroup(mapListings.map(function(item) {
            return L.marker([item.lat, item.lng]);
        }));
        if (mapListings.length > 1) {
            map.fitBounds(group.getBounds().pad(0.1));
        } else if (mapListings.length === 1) {
            map.setView([mapListings[0].lat, mapListings[0].lng], 14);
        }
    });
    </script>
    <script src="<?php echo $config->urls->templates; ?>assets/js/components/SearchWizard.js"></script>
</body>
</html>