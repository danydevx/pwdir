<?php
/**
 * Migration: 999_example_migration
 * Description: Example migration template
 * Depends: 010_create_location_hierarchy
 */

$fields = $GLOBALS['__mig_fields'];
$modules = $GLOBALS['__mig_modules'];
$pages = $GLOBALS['__mig_pages'];
$templates = $GLOBALS['__mig_templates'];
$fieldgroups = $GLOBALS['__mig_fieldgroups'];
$fieldtypes = $GLOBALS['__mig_fieldtypes'];

// Your migration code here
// All migrations should be idempotent - check if things exist before creating

// Example: Create a field
// $field = $fields->get('my_new_field');
// if (!$field || !$field->id) {
//     $field = new \ProcessWire\Field();
//     $field->name = 'my_new_field';
//     $field->label = 'My New Field';
//     $field->type = $fieldtypes->get('FieldtypeText');
//     $field->save();
//     echo "Created field: my_new_field\n";
// } else {
//     echo "Field already exists: my_new_field\n";
// }

// Return true on success (required for runner to mark as executed)
return true;
