<?php
/**
 * Migration: 008_create_event_types
 * Description: Creates event types taxonomy under listings
 * Depends: 003_create_default_pages
 */

$parentListings = $pages->get('/listings/');

$eventTypes = [
    ['name' => 'birthday', 'title' => 'Cumpleaños'],
    ['name' => 'wedding', 'title' => 'Boda'],
    ['name' => 'corporate', 'title' => 'Evento corporativo'],
    ['name' => 'quinceanera', 'title' => 'Quinceañera'],
    ['name' => 'baptism', 'title' => 'Bautizo'],
    ['name' => 'graduation', 'title' => 'Graduación'],
    ['name' => 'other', 'title' => 'Otro'],
];

foreach ($eventTypes as $type) {
    $existing = $pages->find("parent=$parentListings, name={$type['name']}")->first();
    if (!$existing->id) {
        $page = new \ProcessWire\Page();
        $page->template = $templates->get('listing-category');
        $page->parent = $parentListings;
        $page->title = $type['title'];
        $page->name = $type['name'];
        $page->save();
        echo "Created event type: {$type['title']}\n";
    } else {
        echo "Event type already exists: {$type['title']}\n";
    }
}

echo "Migration 008 completed: event type categories created\n";