<?php
/**
 * Migration: 004_create_terrace_fields
 * Description: Creates terrace-specific fields (has_pool, has_parking, etc.)
 * Depends: 001_create_listing_fields
 */

$terraceFields = [
    'fld_has_pool' => 'Has Pool (Alberca)',
    'fld_has_parking' => 'Has Parking (Estacionamiento)',
    'fld_has_kitchen' => 'Has Kitchen (Cocina)',
    'fld_has_garden' => 'Has Garden (Jardín)',
    'fld_has_security' => 'Has Security (Seguridad)',
    'fld_has_bathrooms' => 'Has Bathrooms (Baños)',
];

$fields = $GLOBALS['__mig_fields'];
$modules = $GLOBALS['__mig_modules'];

foreach ($terraceFields as $name => $label) {
    if (!$fields->get($name)) {
        $field = new \ProcessWire\Field();
        $field->name = $name;
        $field->label = $label;
        $field->type = $modules->get('FieldtypeCheckbox');
        $field->save();
    }
}

if (!$fields->get('fld_event_types')) {
    $field = new \ProcessWire\Field();
    $field->name = 'fld_event_types';
    $field->label = 'Event Types (Tipos de eventos)';
    $field->type = $modules->get('FieldtypeText');
    $field->save();
}

echo "Migration 004 completed: terrace-specific fields created\n";