<?php
/**
 * Migration: 011_create_region_field
 * Description: Creates fld_region Page Reference field pointing to region template
 * Depends: 010_create_location_hierarchy
 */

$fields = wire('fields');
$fieldgroups = wire('fieldgroups');
$templates = wire('templates');
$pages = wire('pages');

// fld_region: Page Reference to region template
$fldRegion = $fields->get('fld_region');
if (!$fldRegion) {
    $fldRegion = new Field();
    $fldRegion->name = 'fld_region';
    $fldRegion->label = 'Region';
    $fldRegion->type = 'FieldtypePage';
    $fldRegion->inputfield = 'InputfieldSelect';
    $fldRegion->parent_id = $pages->get('/locations/mexico/jalisco/')->id;
    $fldRegion->template_id = $templates->get('region')->id;
    $fldRegion->derefAsPage = 1;
    $fldRegion->labelFieldName = 'title';
    $fldRegion->save();
    echo "Created fld_region field\n";
}

// Add to listing template
$listingTemplate = $templates->get('listing');
if ($listingTemplate && !$listingTemplate->fieldgroup->hasField('fld_region')) {
    $listingTemplate->fieldgroup->add($fldRegion);
    $listingTemplate->fieldgroup->save();
    echo "Added fld_region to listing template\n";
}

echo "Done!\n";