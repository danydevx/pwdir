<?php
/**
 * Migration: 009_create_event_types_taxonomy
 * Description: Creates event-types taxonomy with terms under /categories/
 * Depends: 002_create_listing_templates
 */

echo "Migration 009: Creating event-types taxonomy system\n\n";

$fieldGroup = $fieldgroups->get('listing');

$fldEventTypes = $fields->get('fld_event_types');
if (!$fldEventTypes->id) {
    $fldEventTypes = new Field();
    $fldEventTypes->name = 'fld_event_types';
    $fldEventTypes->type = $fieldtypes->get('FieldtypePage');
    $fldEventTypes->label = 'Tipos de Eventos';
    $fldEventTypes->description = 'Taxonomía para clasificar lugares por tipo de evento';
    $fldEventTypes->derefAsPage = false;
    $fldEventTypes->save();

    $fieldGroup->add($fldEventTypes);
    $fieldGroup->save();

    echo "Created field: fld_event_types\n";
} else {
    echo "Field already exists: fld_event_types\n";
}

$termTemplate = $templates->get('term');
if (!$termTemplate->id) {
    $termTemplate = new Template();
    $termTemplate->name = 'term';
    $termTemplate->label = 'Term';
    $termTemplate->noSave = false;
    $termTemplate->save();
    echo "Created template: term\n";
} else {
    echo "Template already exists: term\n";
}

$taxonomyTemplate = $templates->get('event-types');
if (!$taxonomyTemplate->id) {
    $taxonomyTemplate = new Template();
    $taxonomyTemplate->name = 'event-types';
    $taxonomyTemplate->label = 'Taxonomía: Tipos de Eventos';
    $taxonomyTemplate->noSave = false;
    $taxonomyTemplate->save();
    echo "Created template: event-types\n";
} else {
    echo "Template already exists: event-types\n";
}

$parentListings = $pages->get('/listings/');

$taxonomyPage = $pages->find("parent=$parentListings, name=event-types")->first();
if (!$taxonomyPage->id) {
    $taxonomyPage = new Page();
    $taxonomyPage->template = $taxonomyTemplate;
    $taxonomyPage->parent = $parentListings;
    $taxonomyPage->title = 'Tipos de Eventos';
    $taxonomyPage->name = 'event-types';
    $taxonomyPage->save();
    echo "Created taxonomy page: Tipos de Eventos\n";
} else {
    echo "Taxonomy page already exists: Tipos de Eventos\n";
}

$terms = [
    ['name' => 'bodas', 'title' => 'Bodas'],
    ['name' => 'xv-anos', 'title' => 'XV Años'],
    ['name' => 'cumpleanos', 'title' => 'Cumpleaños'],
    ['name' => 'corporativos', 'title' => 'Evento Corporativo'],
    ['name' => 'graduaciones', 'title' => 'Graduación'],
    ['name' => 'bautizos', 'title' => 'Bautizo'],
    ['name' => 'otros', 'title' => 'Otros'],
];

foreach ($terms as $term) {
    $existing = $pages->find("parent=$taxonomyPage, name={$term['name']}")->first();
    if (!$existing->id) {
        $page = new Page();
        $page->template = $termTemplate;
        $page->parent = $taxonomyPage;
        $page->title = $term['title'];
        $page->name = $term['name'];
        $page->save();
        echo "Created term: {$term['title']}\n";
    } else {
        echo "Term already exists: {$term['title']}\n";
    }
}

echo "\nMigration 009 completed: event-types taxonomy created\n";