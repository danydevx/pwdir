<?php
/**
 * Migration: 006_fix_missing_fields
 * Description: Creates missing fields (status, plan, verification_status)
 * Depends: 001_create_listing_fields
 */

$fields = $GLOBALS['__mig_fields'];
$modules = $GLOBALS['__mig_modules'];

$missingFields = [
    'fld_status' => ['type' => 'FieldtypeText', 'label' => 'Status'],
    'fld_plan' => ['type' => 'FieldtypeText', 'label' => 'Plan'],
    'fld_verification_status' => ['type' => 'FieldtypeText', 'label' => 'Verification Status'],
];

foreach ($missingFields as $name => $config) {
    if (!$fields->get($name)) {
        $field = new \ProcessWire\Field();
        $field->name = $name;
        $field->label = $config['label'];
        $field->type = $modules->get($config['type']);
        $field->save();
        echo "Created field: {$name}\n";
    } else {
        echo "Field already exists: {$name}\n";
    }
}

$templates = $GLOBALS['__mig_templates'];
$fieldGroups = $GLOBALS['__mig_fieldgroups'];

$fieldsToAddToListing = ['fld_status', 'fld_plan', 'fld_verification_status'];
$fg = $fieldGroups->get('listing');

if ($fg) {
    foreach ($fieldsToAddToListing as $fname) {
        $f = $fields->get($fname);
        if ($f && !$fg->has($f)) {
            $fg->add($f);
            echo "Added {$fname} to fieldgroup\n";
        }
    }
    $fg->save();
    echo "Fieldgroup updated, count: " . $fg->count() . "\n";
}

echo "\nMigration 006 completed: fixed missing fields\n";