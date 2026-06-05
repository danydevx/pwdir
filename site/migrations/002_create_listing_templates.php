<?php
/**
 * Migration: 002_create_listing_templates
 * Description: Creates listing, listing-category, basic-page templates
 * Depends: 001_create_listing_fields
 */

$listingFields = [
    'fld_name', 'fld_slug', 'fld_excerpt', 'fld_description',
    'fld_address', 'fld_city', 'fld_state', 'fld_country',
    'fld_latitude', 'fld_longitude',
    'fld_phone', 'fld_whatsapp', 'fld_email', 'fld_website',
    'fld_facebook', 'fld_instagram', 'fld_tiktok', 'fld_youtube',
    'fld_capacity_min', 'fld_capacity_max',
    'fld_price_min', 'fld_price_max',
    'fld_verified', 'fld_verification_status', 'fld_verified_at', 'fld_verification_notes',
    'fld_featured', 'fld_plan', 'fld_status',
    'fld_cover_image', 'fld_gallery',
    'fld_category', 'fld_features', 'fld_tags', 'fld_location',
    'fld_whatsapp_confirmed', 'fld_location_confirmed',
    'fld_socials_confirmed', 'fld_recent_photos_confirmed', 'fld_fraud_report_count'
];

$templatesData = [
    'listing' => ['label' => 'Listing', 'fields' => $listingFields],
    'listing-category' => ['label' => 'Listing Category', 'fields' => ['title', 'body']],
    'listing-feature' => ['label' => 'Listing Feature', 'fields' => ['title', 'body']],
    'location' => ['label' => 'Location', 'fields' => ['title', 'body']],
    'api' => ['label' => 'API', 'fields' => ['title', 'body']]
];

$fields = $GLOBALS['__mig_fields'];
$templates = $GLOBALS['__mig_templates'];
$fieldGroups = $GLOBALS['__mig_fieldgroups'];

foreach ($templatesData as $name => $config) {
    $template = $templates->get($name);

    if (!$template) {
        $fg = $fieldGroups->get($name);
        if (!$fg) {
            $fg = new \ProcessWire\Fieldgroup();
            $fg->name = $name;
            $fg->save();

            foreach ($config['fields'] as $fieldName) {
                $field = $fields->get($fieldName);
                if ($field) {
                    $fg->add($field);
                }
            }
            $fg->save();
        }

        $template = new \ProcessWire\Template();
        $template->name = $name;
        $template->label = $config['label'];
        $template->fieldgroup = $fg;
        $template->save();
    }
}

echo "Migration 002 completed: templates and fieldgroups created\n";