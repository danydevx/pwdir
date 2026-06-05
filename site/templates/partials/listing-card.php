<?php namespace ProcessWire;

$listing = $page;
$listingImages = $listing->fld_cover_image;
$coverImage = count($listingImages) > 0 ? $listingImages->first() : null;
$category = $listing->fld_category;
$location = $listing->fld_location;

$whatsappNumber = preg_replace('/[^0-9]/', '', $listing->fld_whatsapp ?: '');
$whatsappMsg = rawurlencode("Hola, vi tu lugar en el directorio y quiero información para un evento.");
$whatsappUrl = $whatsappNumber ? "https://wa.me/{$whatsappNumber}?text={$whatsappMsg}" : '#';
?>

<article class="listing-card">
    <?php if ($listing->fld_featured): ?>
    <div class="listing-card__badge listing-card__badge--featured">Destacado</div>
    <?php endif; ?>

    <?php if ($coverImage): ?>
    <div class="listing-card__image">
        <a href="<?php echo $listing->url; ?>">
            <img src="<?php echo $coverImage->size(400, 300)->url; ?>" alt="<?php echo $listing->title; ?>" loading="lazy">
        </a>
    </div>
    <?php else: ?>
    <div class="listing-card__image listing-card__image--placeholder">
        <a href="<?php echo $listing->url; ?>">
            <span class="placeholder-icon">📍</span>
        </a>
    </div>
    <?php endif; ?>

    <div class="listing-card__body">
        <?php if ($category && $category->id): ?>
        <span class="listing-card__category"><?php echo $category->title; ?></span>
        <?php endif; ?>

        <h3 class="listing-card__title">
            <a href="<?php echo $listing->url; ?>"><?php echo $listing->title; ?></a>
        </h3>

        <?php if ($listing->fld_excerpt): ?>
        <p class="listing-card__excerpt"><?php echo $listing->fld_excerpt; ?></p>
        <?php endif; ?>

        <div class="listing-card__meta">
            <?php if ($location && $location->id): ?>
            <span class="listing-card__location">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                </svg>
                <?php echo $location->title; ?>
            </span>
            <?php endif; ?>

            <?php if ($listing->fld_capacity_min || $listing->fld_capacity_max): ?>
            <span class="listing-card__capacity">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 3zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                </svg>
                <?php echo $listing->fld_capacity_min ?: '?'; ?> - <?php echo $listing->fld_capacity_max ?: '?'; ?>
            </span>
            <?php endif; ?>
        </div>

        <?php if ($listing->fld_price_min): ?>
        <div class="listing-card__price">
            Desde $<?php echo number_format($listing->fld_price_min); ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="listing-card__actions">
        <?php if ($listing->fld_verified): ?>
        <span class="listing-card__verified" title="Verificado">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
            </svg>
            Verificado
        </span>
        <?php endif; ?>

        <a href="<?php echo $listing->url; ?>" class="btn btn-sm btn-outline-primary">Ver detalle</a>

        <?php if ($whatsappNumber): ?>
        <a href="<?php echo $whatsappUrl; ?>" class="btn btn-sm btn-success" target="_blank" rel="noopener" aria-label="Contactar por WhatsApp">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.893 7.893 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433h-.381a6.57 6.57 0 0 1-3.131.916 6.58 6.58 0 0 1-6.045-4.116 6.583 6.583 0 0 1 6.185-10.518c2.542-.082 4.892.53 6.67 1.908a6.58 6.58 0 0 1 1.908 6.67z"/>
            </svg>
        </a>
        <?php endif; ?>
    </div>
</article>