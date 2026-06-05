<?php namespace ProcessWire;
?>
<!doctype html>
<html lang="es">
<head>
    <?php include __DIR__ . '/partials/head.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="site-main">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                    <?php if ($page->parents->count()): ?>
                    <?php foreach ($page->parents as $parent): ?>
                    <li class="breadcrumb-item"><a href="<?php echo $parent->url; ?>"><?php echo $parent->title; ?></a></li>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $page->title; ?></li>
                </ol>
            </div>
        </nav>

        <section class="basic-page__content py-4">
            <div class="container">
                <h1 class="mb-4"><?php echo $page->title; ?></h1>
                <?php if ($page->body): ?>
                <div class="basic-page__body">
                    <?php echo $page->body; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>