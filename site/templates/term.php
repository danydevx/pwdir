<?php namespace ProcessWire;

use const ProcessWire\TPL_LISTING;
use const ProcessWire\TPL_TERM;
use const ProcessWire\FLD_STATUS;
use const ProcessWire\FLD_EVENT_TYPES;
use const ProcessWire\FLD_SERVICES;
use const ProcessWire\FLD_AMENITIES;
use const ProcessWire\STATUS_ACTIVE;

require_once __DIR__ . '/_constants.php';
require_once __DIR__ . '/_helpers.php';

$taxonomyName = $page->parent->template->name;
$listings = $pages->find("template=" . TPL_LISTING . ", " . FLD_STATUS . "=" . STATUS_ACTIVE . ", sort=-created");

if ($taxonomyName === 'event-types') {
    $listings = $listings->filter(FLD_EVENT_TYPES . "={$page->id}");
} else if ($taxonomyName === 'services') {
    $listings = $listings->filter(FLD_SERVICES . "={$page->id}");
} else if ($taxonomyName === 'amenities') {
    $listings = $listings->filter(FLD_AMENITIES . "={$page->id}");
}

$taxonomy = $page->parent;
$categoryRoot = $taxonomy->parent;
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
                <li class="breadcrumb-item"><a href="<?= dir_categories()->url ?>">Categorías</a></li>
                <li class="breadcrumb-item"><a href="<?= $categoryRoot->url ?>"><?= $categoryRoot->title ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $page->title ?></li>
            </ol>
        </nav>

        <h1 class="mb-4"><?= $page->title ?></h1>

        <?php if ($listings->count()): ?>
            <div class="row g-4">
                <?php foreach ($listings as $listing): ?>
                    <?php include __DIR__ . '/partials/listing-card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                No hay listings con esta categoría todavía.
            </div>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>