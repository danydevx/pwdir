<?php
/**
 * Migration: 001_create_listing_fields
 * Description: Creates base listing fields (name, description, contact, location, etc.)
 * Depends:
 */

$fieldDefinitions = [
    'fld_name' => ['type' => 'FieldtypeText', 'label' => 'Name'],
    'fld_slug' => ['type' => 'FieldtypeText', 'label' => 'Slug'],
    'fld_excerpt' => ['type' => 'FieldtypeTextarea', 'label' => 'Excerpt'],
    'fld_description' => ['type' => 'FieldtypeTextarea', 'label' => 'Description'],
    'fld_address' => ['type' => 'FieldtypeText', 'label' => 'Address'],
    'fld_city' => ['type' => 'FieldtypeText', 'label' => 'City'],
    'fld_state' => ['type' => 'FieldtypeText', 'label' => 'State'],
    'fld_country' => ['type' => 'FieldtypeText', 'label' => 'Country'],
    'fld_latitude' => ['type' => 'FieldtypeFloat', 'label' => 'Latitude'],
    'fld_longitude' => ['type' => 'FieldtypeFloat', 'label' => 'Longitude'],
    'fld_phone' => ['type' => 'FieldtypeText', 'label' => 'Phone'],
    'fld_whatsapp' => ['type' => 'FieldtypeText', 'label' => 'WhatsApp'],
    'fld_email' => ['type' => 'FieldtypeEmail', 'label' => 'Email'],
    'fld_website' => ['type' => 'FieldtypeURL', 'label' => 'Website'],
    'fld_facebook' => ['type' => 'FieldtypeURL', 'label' => 'Facebook'],
    'fld_instagram' => ['type' => 'FieldtypeURL', 'label' => 'Instagram'],
    'fld_tiktok' => ['type' => 'FieldtypeURL', 'label' => 'TikTok'],
    'fld_youtube' => ['type' => 'FieldtypeURL', 'label' => 'YouTube'],
    'fld_capacity_min' => ['type' => 'FieldtypeInteger', 'label' => 'Capacity Min'],
    'fld_capacity_max' => ['type' => 'FieldtypeInteger', 'label' => 'Capacity Max'],
    'fld_price_min' => ['type' => 'FieldtypeInteger', 'label' => 'Price Min'],
    'fld_price_max' => ['type' => 'FieldtypeInteger', 'label' => 'Price Max'],
    'fld_verified' => ['type' => 'FieldtypeCheckbox', 'label' => 'Verified'],
    'fld_verified_at' => ['type' => 'FieldtypeDatetime', 'label' => 'Verified At'],
    'fld_verification_notes' => ['type' => 'FieldtypeTextarea', 'label' => 'Verification Notes'],
    'fld_featured' => ['type' => 'FieldtypeCheckbox', 'label' => 'Featured'],
    'fld_cover_image' => ['type' => 'FieldtypeImage', 'label' => 'Cover Image'],
    'fld_gallery' => ['type' => 'FieldtypeImage', 'label' => 'Gallery'],
    'fld_category' => ['type' => 'FieldtypePage', 'label' => 'Category', 'pageSingle' => true],
    'fld_features' => ['type' => 'FieldtypePage', 'label' => 'Features', 'pageArray' => true],
    'fld_tags' => ['type' => 'FieldtypePage', 'label' => 'Tags', 'pageArray' => true],
    'fld_location' => ['type' => 'FieldtypePage', 'label' => 'Location', 'pageSingle' => true],
    'fld_whatsapp_confirmed' => ['type' => 'FieldtypeCheckbox', 'label' => 'WhatsApp Confirmed'],
    'fld_location_confirmed' => ['type' => 'FieldtypeCheckbox', 'label' => 'Location Confirmed'],
    'fld_socials_confirmed' => ['type' => 'FieldtypeCheckbox', 'label' => 'Socials Confirmed'],
    'fld_recent_photos_confirmed' => ['type' => 'FieldtypeCheckbox', 'label' => 'Recent Photos Confirmed'],
    'fld_fraud_report_count' => ['type' => 'FieldtypeInteger', 'label' => 'Fraud Report Count'],
];

$fields = $GLOBALS['__mig_fields'];
$modules = $GLOBALS['__mig_modules'];

foreach ($fieldDefinitions as $name => $config) {
    if (!$fields->get($name)) {
        $field = new \ProcessWire\Field();
        $field->name = $name;
        $field->label = $config['label'];
        $field->type = $modules->get($config['type']);

        if ($config['type'] === 'FieldtypePage') {
            if (isset($config['pageArray'])) {
                $field->derefAsPage = 1;
            } elseif (isset($config['pageSingle'])) {
                $field->derefAsPage = 0;
            }
        }

        $field->save();
    }
}

echo "Migration 001 completed: listing fields created\n";