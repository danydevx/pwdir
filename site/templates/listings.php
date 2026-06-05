<?php namespace ProcessWire;

use const ProcessWire\TPL_LISTING;
use const ProcessWire\FLD_STATUS;
use const ProcessWire\STATUS_ACTIVE;

require_once __DIR__ . '/_constants.php';

$categories = $pages->find("template=listing-category, sort=title");

$q = $input->get('q');
$categoryFilter = $input->get('category');

// Pagination
$limit = 3;
$pageNum = $input->get('page', 'int') ?: 1;
$start = ($pageNum - 1) * $limit;

$selector = "template=" . TPL_LISTING . ", " . FLD_STATUS . "=" . STATUS_ACTIVE . ", sort=-created";

if ($categoryFilter) {
    $selector .= ", fld_category.name={$categoryFilter}";
}

// Get total count first
$total = $pages->count($selector);

if ($q) {
    $listings = $pages->find($selector);
    $listings = $listings->filter("title*fld_name*fld_excerpt*fld_description*={$q}");
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

// Pagination vars
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
                <h1 class="mb-3">Encuentra tu lugar perfecto</h1>
                <p class="lead text-muted">Terrazas, salones y espacios para eventos</p>

                <form method="get" action="/listings/" class="listing-filters row g-3 mt-3">
                    <div class="col-md-3">
                        <label for="q" class="form-label">Buscar</label>
                        <input type="text" class="form-control" id="q" name="q" value="<?php echo $sanitizer->entities($q); ?>" placeholder="Nombre del lugar...">
                    </div>
                    <div class="col-md-2">
                        <label for="category" class="form-label">Categoría</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">Todas</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat->name; ?>" <?php if ($categoryFilter === $cat->name) echo 'selected'; ?>><?php echo $cat->title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="location" class="form-label">Ubicación</label>
                        <select class="form-select" id="location" name="location">
                            <option value="">Todas</option>
                            <?php foreach ($locations as $loc): ?>
                            <option value="<?php echo $loc->name; ?>"><?php echo $loc->title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="capacity" class="form-label">Personas</label>
                        <input type="number" class="form-control" id="capacity" name="capacity" value="" placeholder="Ej: 100">
                    </div>
                    <div class="col-md-2">
                        <label for="price_max" class="form-label">Precio máximo</label>
                        <input type="number" class="form-control" id="price_max" name="price_max" value="" placeholder="Ej: 5000">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Buscar</button>
                    </div>
                </form>
            </div>
        </section>

        <section class="listing-results py-4">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <p class="text-muted">Mostrando <?php echo $listings->count(); ?> de <?php echo $total; ?> lugares (página <?php echo $pageNum; ?> de <?php echo $totalPages; ?>)</p>
                    </div>
                </div>

                <?php if ($total): ?>
                <div class="mb-3">
                    <?php echo $pager; ?>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div id="listings-map" style="height: 400px; border-radius: 8px; overflow: hidden;"></div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row row-cols-1 g-4" style="max-height: 400px; overflow-y: auto;">
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
                    </div>
                </div>
                <div class="mt-3">
                    <?php echo $pager; ?>
                </div>
                <?php else: ?>
                <div class="alert alert-info">
                    No se encontraron lugares con esos filtros. Intenta con otros criterios.
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var listings = <?php echo $listingsJson; ?>;
        if (listings.length === 0) return;

        var map = L.map('listings-map').setView([20.68, -103.35], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        listings.forEach(function(item) {
            var priceStr = item.price ? 'Desde $' + item.price.toLocaleString() : '';
            var marker = L.marker([item.lat, item.lng]).addTo(map);
            marker.bindPopup('<a href="' + item.url + '"><strong>' + item.title + '</strong></a>' +
                (item.excerpt ? '<br><small>' + item.excerpt.substring(0, 80) + '...</small>' : '') +
                (priceStr ? '<br><strong>' + priceStr + '</strong>' : ''));
        });

        if (listings.length === 1) {
            map.setView([listings[0].lat, listings[0].lng], 14);
        } else {
            var group = new L.featureGroup(listings.map(function(item) {
                return L.marker([item.lat, item.lng]);
            }));
            map.fitBounds(group.getBounds().pad(0.1));
        }
    });
    </script>
</body>
</html>