<?php
/**
 * Migration: 003_create_default_pages
 * Description: Creates default pages (listings, categories, etc.)
 * Depends: 002_create_listing_templates
 */

$parentListings = $pages->get('/listings/');
if (!$parentListings->id) {
    $parentListings = new \ProcessWire\Page();
    $parentListings->template = $templates->get('listing-category');
    $parentListings->parent = $pages->get('/');
    $parentListings->title = 'Listings';
    $parentListings->name = 'listings';
    $parentListings->save();
}

$categories = [
    ['name' => 'terraces', 'title' => 'Terrazas'],
    ['name' => 'salons', 'title' => 'Salones'],
    ['name' => 'gardens', 'title' => 'Jardines'],
];

foreach ($categories as $cat) {
    $existing = $pages->find("parent=$parentListings, name={$cat['name']}")->first();
    if (!$existing->id) {
        $page = new \ProcessWire\Page();
        $page->template = $templates->get('listing-category');
        $page->parent = $parentListings;
        $page->title = $cat['title'];
        $page->name = $cat['name'];
        $page->save();
    }
}

$parentFeatures = $pages->get('/listing-features/');
if (!$parentFeatures->id) {
    $parentFeatures = new \ProcessWire\Page();
    $parentFeatures->template = $templates->get('basic-page');
    $parentFeatures->parent = $pages->get('/');
    $parentFeatures->title = 'Listing Features';
    $parentFeatures->name = 'listing-features';
    $parentFeatures->save();
}

$features = [
    ['name' => 'pool', 'title' => 'Alberca'],
    ['name' => 'parking', 'title' => 'Estacionamiento'],
    ['name' => 'kitchen', 'title' => 'Cocina'],
    ['name' => 'garden', 'title' => 'Jardín'],
    ['name' => 'bathrooms', 'title' => 'Baños'],
    ['name' => 'security', 'title' => 'Seguridad'],
];

foreach ($features as $feat) {
    $existing = $pages->find("parent=$parentFeatures, name={$feat['name']}")->first();
    if (!$existing->id) {
        $page = new \ProcessWire\Page();
        $page->template = $templates->get('listing-feature');
        $page->parent = $parentFeatures;
        $page->title = $feat['title'];
        $page->name = $feat['name'];
        $page->save();
    }
}

$parentLocations = $pages->get('/locations/');
if (!$parentLocations->id) {
    $parentLocations = new \ProcessWire\Page();
    $parentLocations->template = $templates->get('basic-page');
    $parentLocations->parent = $pages->get('/');
    $parentLocations->title = 'Locations';
    $parentLocations->name = 'locations';
    $parentLocations->save();
}

$locations = [
    ['name' => 'guadalajara', 'title' => 'Guadalajara'],
    ['name' => 'zapopan', 'title' => 'Zapopan'],
    ['name' => 'tlaquepaque', 'title' => 'Tlaquepaque'],
    ['name' => 'tonala', 'title' => 'Tonalá'],
    ['name' => 'tepatitlan', 'title' => 'Tepatitlán'],
    ['name' => 'lagos-de-moreno', 'title' => 'Lagos de Moreno'],
];

foreach ($locations as $loc) {
    $existing = $pages->find("parent=$parentLocations, name={$loc['name']}")->first();
    if (!$existing->id) {
        $page = new \ProcessWire\Page();
        $page->template = $templates->get('location');
        $page->parent = $parentLocations;
        $page->title = $loc['title'];
        $page->name = $loc['name'];
        $page->save();
    }
}

$parentApi = $pages->get('/api/');
if (!$parentApi->id) {
    $parentApi = new \ProcessWire\Page();
    $parentApi->template = $templates->get('api');
    $parentApi->parent = $pages->get('/');
    $parentApi->title = 'API';
    $parentApi->name = 'api';
    $parentApi->save();
}

echo "Migration 003 completed: base pages created\n";