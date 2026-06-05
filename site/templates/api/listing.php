<?php namespace ProcessWire;

$id = $input->get('id');

if (!$id) {
    echo json_encode(['success' => false, 'error' => 'ID required'], JSON_UNESCAPED_UNICODE);
    exit;
}

$listing = $pages->get("id=$id, template=listing");

if (!$listing->id) {
    echo json_encode(['success' => false, 'error' => 'Listing not found'], JSON_UNESCAPED_UNICODE);
    exit;
}

$coverImage = $listing->fld_cover_image ? $listing->fld_cover_image->first() : null;
$gallery = $listing->fld_gallery;
$category = $listing->fld_category;
$features = $listing->fld_features;
$location = $listing->fld_location;

$featuresArray = [];
foreach ($features as $feature) {
    $featuresArray[] = $feature->title;
}

$galleryArray = [];
foreach ($gallery as $img) {
    $galleryArray[] = [
        'url' => $img->url,
        'width' => $img->width,
        'height' => $img->height,
        'description' => $img->description
    ];
}

echo json_encode([
    'success' => true,
    'data' => [
        'id' => $listing->id,
        'title' => $listing->title,
        'slug' => $listing->name,
        'url' => $listing->url,
        'excerpt' => $listing->fld_excerpt ?: null,
        'description' => $listing->fld_description ?: null,
        'address' => $listing->fld_address ?: null,
        'city' => $listing->fld_city ?: null,
        'state' => $listing->fld_state ?: null,
        'country' => $listing->fld_country ?: null,
        'latitude' => $listing->fld_latitude ?: null,
        'longitude' => $listing->fld_longitude ?: null,
        'capacity_min' => $listing->fld_capacity_min ?: null,
        'capacity_max' => $listing->fld_capacity_max ?: null,
        'price_min' => $listing->fld_price_min ?: null,
        'price_max' => $listing->fld_price_max ?: null,
        'verified' => (bool) $listing->fld_verified,
        'verification_status' => $listing->fld_verification_status ?: 'unverified',
        'verified_at' => $listing->fld_verified_at ?: null,
        'featured' => (bool) $listing->fld_featured,
        'plan' => $listing->fld_plan ?: 'free',
        'status' => $listing->fld_status ?: 'active',
        'cover_image' => $coverImage ? $coverImage->url : null,
        'gallery' => $galleryArray,
        'category' => $category->count() ? $category->title : null,
        'category_slug' => $category->count() ? $category->name : null,
        'features' => $featuresArray,
        'location' => $location->count() ? $location->title : null,
        'location_slug' => $location->count() ? $location->name : null,
        'whatsapp' => $listing->fld_whatsapp ?: null,
        'phone' => $listing->fld_phone ?: null,
        'email' => $listing->fld_email ?: null,
        'website' => $listing->fld_website ?: null,
        'facebook' => $listing->fld_facebook ?: null,
        'instagram' => $listing->fld_instagram ?: null,
        'tiktok' => $listing->fld_tiktok ?: null,
        'youtube' => $listing->fld_youtube ?: null,
        'created' => $listing->created->unix(),
        'modified' => $listing->modified->unix()
    ]
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);