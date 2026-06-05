<?php
/**
 * Bootstrap existing migrations
 *
 * Run this via: php bootstrap.php
 * Only run this once to sync the new tracking system with existing migrations!
 */

require(__DIR__ . '/../../index.php');

$wire = $GLOBALS['wire'];
$runner = $wire->modules->get('MigrationRunner');

echo "Bootstrapping existing migrations...\n\n";

$existingMigrations = [
    '001_create_listing_fields',
    '001b_create_select_options',
    '002_create_listing_templates',
    '003_create_default_pages',
    '004_create_terrace_fields',
    '005_create_sample_listings',
    '006_fix_missing_fields',
    '007_create_more_listings',
    '008_create_event_types',
    '009_create_event_types_taxonomy',
    '010_create_location_hierarchy',
    '011_create_region_field',
];

$bootstrapped = 0;
foreach ($existingMigrations as $name) {
    if ($runner->isExecuted($name)) {
        echo "  Already tracked: {$name}\n";
        continue;
    }

    $file = __DIR__ . '/' . $name . '.php';
    if (file_exists($file)) {
        $checksum = $runner->calculateChecksum($file);
        $runner->markExecuted($name, $checksum, true);
        echo "  Bootstrapped: {$name}\n";
        $bootstrapped++;
    } else {
        echo "  Not found: {$name}\n";
    }
}

echo "\nBootstrapped {$bootstrapped} migrations.\n";
echo "Run 'php index.php --status' to verify.\n";