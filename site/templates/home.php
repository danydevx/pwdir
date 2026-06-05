<?php namespace ProcessWire;

$featuredListings = $pages->find("template=listing, fld_featured=1, fld_status=active, limit=6");
$categories = $pages->find("template=listing-category, sort=title");
?>
<!doctype html>
<html lang="es">
<head>
    <?php include __DIR__ . '/partials/head.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="site-main">
        <section class="hero bg-light py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="display-4 fw-bold">Encuentra tu lugar perfecto</h1>
                        <p class="lead text-muted mb-4">Terrazas, salones y espacios para eventos en Guadalajara y alrededores</p>
                        <form action="/listings/" method="get" class="d-flex gap-2">
                            <input type="text" name="q" class="form-control form-control-lg" placeholder="Buscar lugar...">
                            <button type="submit" class="btn btn-primary btn-lg">Buscar</button>
                        </form>
                    </div>
                    <div class="col-lg-6">
                        <div class="hero-stats bg-white rounded-4 shadow p-4">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="h2 fw-bold text-primary">6</div>
                                    <div class="text-muted">Lugares</div>
                                </div>
                                <div class="col-4">
                                    <div class="h2 fw-bold text-primary"><?php echo $categories->count(); ?></div>
                                    <div class="text-muted">Categorías</div>
                                </div>
                                <div class="col-4">
                                    <div class="h2 fw-bold text-success">3</div>
                                    <div class="text-muted">Verificados</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php if ($categories->count()): ?>
        <section class="categories py-5">
            <div class="container">
                <h2 class="mb-4">Categorías</h2>
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                    <?php foreach ($categories as $cat): ?>
                    <div class="col">
                        <a href="/listings/?category=<?php echo $cat->name; ?>" class="text-decoration-none">
                            <div class="card h-100 text-center">
                                <div class="card-body">
                                    <div class="h4 mb-2">📍</div>
                                    <h3 class="h6"><?php echo $cat->title; ?></h3>
                                    <p class="text-muted small mb-0"><?php echo $cat->summary ?: 'Ver opciones'; ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($featuredListings->count()): ?>
        <section class="featured py-5 bg-light">
            <div class="container">
                <h2 class="mb-4">Lugares Destacados</h2>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($featuredListings as $listing): ?>
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
                <div class="text-center mt-4">
                    <a href="/listings/" class="btn btn-outline-primary">Ver todos los lugares</a>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <section class="cta py-5 bg-primary text-white">
            <div class="container text-center">
                <h2 class="mb-3">¿Tienes un lugar para eventos?</h2>
                <p class="lead mb-4">Agrega tu negocio al directorio y reacha más clientes</p>
                <a href="https://wa.me/523300000000?text=Hola%2C%20quiero%20agregar%20mi%20lugar%20al%20directorio" class="btn btn-light btn-lg">Contactar por WhatsApp</a>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>