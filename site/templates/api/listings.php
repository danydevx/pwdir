<?php namespace ProcessWire;

use const ProcessWire\TPL_LISTING;
use const ProcessWire\FLD_STATUS;
use const ProcessWire\FLD_CATEGORY;
use const ProcessWire\FLD_REGION;
use const ProcessWire\FLD_FEATURES;
use const ProcessWire\FLD_COVER_IMAGE;
use const ProcessWire\FLD_LATITUDE;
use const ProcessWire\FLD_LONGITUDE;
use const ProcessWire\FLD_ADDRESS;
use const ProcessWire\FLD_CITY;
use const ProcessWire\FLD_STATE;
use const ProcessWire\FLD_PRICE_MIN;
use const ProcessWire\FLD_PRICE_MAX;
use const ProcessWire\FLD_CAPACITY_MIN;
use const ProcessWire\FLD_CAPACITY_MAX;
use const ProcessWire\FLD_EXCERPT;
use const ProcessWire\FLD_DESCRIPTION;
use const ProcessWire\FLD_NAME;
use const ProcessWire\FLD_VERIFIED;
use const ProcessWire\FLD_FEATURED;
use const ProcessWire\FLD_PLAN;
use const ProcessWire\FLD_WHATSAPP;
use const ProcessWire\FLD_PHONE;
use const ProcessWire\FLD_EMAIL;
use const ProcessWire\FLD_WEBSITE;
use const ProcessWire\FLD_FACEBOOK;
use const ProcessWire\FLD_INSTAGRAM;
use const ProcessWire\FLD_VERIFICATION_STATUS;
use const ProcessWire\STATUS_ACTIVE;
use const ProcessWire\PLAN_FREE;
use const ProcessWire\VERIFY_UNVERIFIED;

require_once __DIR__ . '/../_constants.php';

header('Content-Type: application/json');

$listings = $pages->find("template=" . TPL_LISTING . ", " . FLD_STATUS . "=" . STATUS_ACTIVE . ", sort=-created");

$category = $input->get('category');
$location = $input->get('location');
$region = $input->get('region');
$capacity = $input->get('capacity', 'int');
$priceMin = $input->get('price_min', 'int');
$priceMax = $input->get('price_max', 'int');
$featured = $input->get('featured');
$q = $input->get('q');

if ($category) {
    $listings = $listings->filter(FLD_CATEGORY . ".name={$category}");
}
if ($location) {
    $listings = $listings->filter(FLD_REGION . ".name={$location}");
}
if ($region) {
    $listings = $listings->filter(FLD_REGION . ".name={$region}");
}
if ($capacity) {
    $listings = $listings->filter(FLD_CAPACITY_MIN . "<={$capacity}, " . FLD_CAPACITY_MAX . ">={$capacity}");
}
if ($priceMin) {
    $listings = $listings->filter(FLD_PRICE_MIN . ">={$priceMin}");
}
if ($priceMax) {
    $listings = $listings->filter(FLD_PRICE_MAX . "<= {$priceMax}");
}
if ($featured) {
    $listings = $listings->filter(FLD_FEATURED . "=1");
}
if ($q) {
    $listings = $listings->filter("title|body*" . FLD_DESCRIPTION . "*" . FLD_NAME . "*={$q}");
}

$results = [];
foreach ($listings as $listing) {
    $coverImage = $listing->{FLD_COVER_IMAGE} ? $listing->{FLD_COVER_IMAGE}->first() : null;
    $categoryObj = $listing->{FLD_CATEGORY};
    $features = $listing->{FLD_FEATURES};
    $regionObj = $listing->{FLD_REGION};

    $featuresArray = [];
    if ($features && count($features) > 0) {
        foreach ($features as $feature) {
            $featuresArray[] = $feature->title;
        }
    }

    $categoryTitle = ($categoryObj && $categoryObj->count()) ? $categoryObj->title : null;
    $regionTitle = ($regionObj && $regionObj->id) ? $regionObj->title : null;

    $whatsapp = $listing->{FLD_WHATSAPP} ?: '523300000000';
    $waMessage = rawurlencode("Hola, vi tu lugar '" . $listing->title . "' en el directorio y quiero información.");
    $whatsappUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $whatsapp) . "?text={$waMessage}";

    $results[] = [
        'id' => $listing->id,
        'title' => $listing->title,
        'slug' => $listing->name,
        'url' => $listing->url,
        'excerpt' => $listing->{FLD_EXCERPT} ?: null,
        'address' => $listing->{FLD_ADDRESS} ?: null,
        'city' => $listing->{FLD_CITY} ?: null,
        'state' => $listing->{FLD_STATE} ?: null,
        'latitude' => $listing->{FLD_LATITUDE} ?: null,
        'longitude' => $listing->{FLD_LONGITUDE} ?: null,
        'capacity_min' => $listing->{FLD_CAPACITY_MIN} ?: null,
        'capacity_max' => $listing->{FLD_CAPACITY_MAX} ?: null,
        'price_min' => $listing->{FLD_PRICE_MIN} ?: null,
        'price_max' => $listing->{FLD_PRICE_MAX} ?: null,
        'verified' => (bool) $listing->{FLD_VERIFIED},
        'verification_status' => $listing->{FLD_VERIFICATION_STATUS} ?: VERIFY_UNVERIFIED,
        'featured' => (bool) $listing->{FLD_FEATURED},
        'plan' => $listing->{FLD_PLAN} ?: PLAN_FREE,
        'status' => $listing->{FLD_STATUS} ?: STATUS_ACTIVE,
        'cover_image' => $coverImage ? $coverImage->url : null,
        'category' => $categoryTitle,
        'features' => $featuresArray,
        'region' => $regionTitle,
        'whatsapp' => $listing->{FLD_WHATSAPP} ?: null,
        'whatsapp_url' => $whatsappUrl,
        'phone' => $listing->{FLD_PHONE} ?: null,
        'email' => $listing->{FLD_EMAIL} ?: null,
        'website' => $listing->{FLD_WEBSITE} ?: null,
        'facebook' => $listing->{FLD_FACEBOOK} ?: null,
        'instagram' => $listing->{FLD_INSTAGRAM} ?: null,
    ];
}

$response = [
    'success' => true,
    'count' => count($results),
    'listings' => $results
];

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);