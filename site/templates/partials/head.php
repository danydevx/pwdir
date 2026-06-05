<?php
$metaTitle = $page->fld_meta_title ?: $page->title;
$metaDesc = $page->get('fld_meta_description|fld_summary|fld_excerpt|description') ?: 'Directorio inteligente de lugares para eventos';
$canonical = $page->httpUrl;
$ogCoverImages = $page->get('fld_cover_image');
$ogImage = '';
if ($ogCoverImages) {
    if (is_object($ogCoverImages) && method_exists($ogCoverImages, 'first')) {
        $firstImg = $ogCoverImages->first();
        $ogImage = $firstImg ? $firstImg->url : '';
    } elseif (isset($ogCoverImages->url)) {
        $ogImage = $ogCoverImages->url;
    }
}
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $sanitizer->entities($metaTitle); ?></title>
<meta name="description" content="<?php echo $sanitizer->entities($metaDesc); ?>">
<link rel="canonical" href="<?php echo $canonical; ?>">
<meta property="og:title" content="<?php echo $sanitizer->entities($metaTitle); ?>">
<meta property="og:description" content="<?php echo $sanitizer->entities($metaDesc); ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo $canonical; ?>">
<?php if ($ogImage): ?>
<meta property="og:image" content="<?php echo $ogImage; ?>">
<?php endif; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/css/glightbox.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $config->urls->templates; ?>assets/css/main.css" />