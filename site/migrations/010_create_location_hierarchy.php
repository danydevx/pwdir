<?php
/**
 * Migration: 010_create_location_hierarchy
 * Description: Creates location hierarchy (locations, country, state, region templates and pages)
 * Depends:
 */

$templates = wire('templates');
$fieldgroups = wire('fieldgroups');
$fields = wire('fields');
$pages = wire('pages');

$titleField = $fields->get('title');

// Create templates
$countryFg = $fieldgroups->get('country');
if (!$countryFg) {
    $countryFg = new Fieldgroup();
    $countryFg->name = 'country';
    $countryFg->save();
}
$countryTemplate = $templates->get('country');
if (!$countryTemplate) {
    $countryTemplate = new Template();
    $countryTemplate->name = 'country';
    $countryTemplate->label = 'Country';
    $countryTemplate->fieldgroup = $countryFg;
    $countryTemplate->save();
}
if (!$countryFg->hasField('title')) {
    $countryFg->add($titleField);
    $countryFg->save();
}

$stateFg = $fieldgroups->get('state');
if (!$stateFg) {
    $stateFg = new Fieldgroup();
    $stateFg->name = 'state';
    $stateFg->save();
}
$stateTemplate = $templates->get('state');
if (!$stateTemplate) {
    $stateTemplate = new Template();
    $stateTemplate->name = 'state';
    $stateTemplate->label = 'State';
    $stateTemplate->fieldgroup = $stateFg;
    $stateTemplate->save();
}
if (!$stateFg->hasField('title')) {
    $stateFg->add($titleField);
    $stateFg->save();
}

$regionFg = $fieldgroups->get('region');
if (!$regionFg) {
    $regionFg = new Fieldgroup();
    $regionFg->name = 'region';
    $regionFg->save();
}
$regionTemplate = $templates->get('region');
if (!$regionTemplate) {
    $regionTemplate = new Template();
    $regionTemplate->name = 'region';
    $regionTemplate->label = 'Region';
    $regionTemplate->fieldgroup = $regionFg;
    $regionTemplate->save();
}
if (!$regionFg->hasField('title')) {
    $regionFg->add($titleField);
    $regionFg->save();
}

// Create /locations/ page
$homePage = $pages->get('/');
$locationsTemplate = $templates->get('locations');
if (!$locationsTemplate) {
    $locationsTemplate = new Template();
    $locationsTemplate->name = 'locations';
    $locationsTemplate->label = 'Locations';
    $locationsFg = new Fieldgroup();
    $locationsFg->name = 'locations';
    $locationsFg->save();
    $locationsTemplate->fieldgroup = $locationsFg;
    $locationsTemplate->save();
}

$locationsPage = $pages->find("parent={$homePage}, name=locations")->first();
if (!$locationsPage || !$locationsPage->id) {
    $locationsPage = new Page();
    $locationsPage->template = $locationsTemplate;
    $locationsPage->parent = $homePage;
    $locationsPage->title = 'Locations';
    $locationsPage->name = 'locations';
    $locationsPage->save();
    echo "Created /locations/\n";
}

// Create Mexico
$mexicoPage = $pages->find("parent={$locationsPage}, name=mexico")->first();
if (!$mexicoPage || !$mexicoPage->id) {
    $mexicoPage = new Page();
    $mexicoPage->template = $countryTemplate;
    $mexicoPage->parent = $locationsPage;
    $mexicoPage->title = 'Mexico';
    $mexicoPage->name = 'mexico';
    $mexicoPage->save();
    echo "Created /locations/mexico/\n";
}

// Create Jalisco state
$jaliscoPage = $pages->find("parent={$mexicoPage}, name=jalisco")->first();
if (!$jaliscoPage || !$jaliscoPage->id) {
    $jaliscoPage = new Page();
    $jaliscoPage->template = $stateTemplate;
    $jaliscoPage->parent = $mexicoPage;
    $jaliscoPage->title = 'Jalisco';
    $jaliscoPage->name = 'jalisco';
    $jaliscoPage->save();
    echo "Created /locations/mexico/jalisco/\n";
}

// Create regions
$regions = [
    ['name' => 'guadalajara', 'title' => 'Guadalajara'],
    ['name' => 'zapopan', 'title' => 'Zapopan'],
    ['name' => 'tonala', 'title' => 'Tonalá'],
    ['name' => 'tlajomulco', 'title' => 'Tlajomulco de Zúñiga'],
    ['name' => 'tlaquepaque', 'title' => 'Tlaquepaque'],
];

foreach ($regions as $r) {
    $existing = $pages->find("parent={$jaliscoPage}, name={$r['name']}")->first();
    if (!$existing || !$existing->id) {
        $page = new Page();
        $page->template = $regionTemplate;
        $page->parent = $jaliscoPage;
        $page->title = $r['title'];
        $page->name = $r['name'];
        $page->save();
        echo "Created /locations/mexico/jalisco/{$r['name']}/\n";
    }
}

echo "\nDone!\n";
echo "Structure:\n";
echo "/locations/ (locations)\n";
echo "  └── /locations/mexico/ (country)\n";
echo "        └── /locations/mexico/jalisco/ (state)\n";
echo "              ├── guadalajara, zapopan, tonala, tlajomulco, tlaquepaque (region)\n";