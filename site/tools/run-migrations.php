<?php

$rootDir = dirname(dirname(__DIR__));
require_once $rootDir . '/index.php';

while (ob_get_level()) ob_end_clean();

$config = ProcessWire\ProcessWire::buildConfig($rootDir);
$wire = new \ProcessWire\ProcessWire($config);
$config->internal = true;

$GLOBALS['__mig_fields'] = $wire->fields;
$GLOBALS['__mig_templates'] = $wire->templates;
$GLOBALS['__mig_fieldgroups'] = $wire->fieldgroups;
$GLOBALS['__mig_pages'] = $wire->pages;
$GLOBALS['__mig_modules'] = $wire->modules;
$GLOBALS['__mig_files'] = $wire->files;

echo "Running migrations...\n";

$migrationDir = __DIR__ . '/../migrations/';
$filesList = @scandir($migrationDir) ?: [];
$migrations = array_filter($filesList, function($f) { return preg_match('/^00[0-9]+[a-z]?_.*\.php$/', $f); });
sort($migrations);

foreach ($migrations as $fileName) {
    echo "Running: {$fileName}\n";

    $code = file_get_contents($migrationDir . $fileName);
    $code = preg_replace('/^<\?php\s+namespace\s+ProcessWire\s*;/m', '<?php', $code);
    $code = preg_replace('/^<\?php\s+namespace\s+ProcessWire$/m', '<?php', $code);

    $wrappedCode = '<?php
global $fields, $templates, $fieldGroups, $pages, $modules, $files;
$fields = $GLOBALS["__mig_fields"];
$templates = $GLOBALS["__mig_templates"];
$fieldGroups = $GLOBALS["__mig_fieldgroups"];
$pages = $GLOBALS["__mig_pages"];
$modules = $GLOBALS["__mig_modules"];
$files = $GLOBALS["__mig_files"];
?>' . $code;

    $tempFile = '/tmp/migration_' . uniqid() . '.php';
    file_put_contents($tempFile, $wrappedCode);

    include($tempFile);
    unlink($tempFile);

    echo "  Done\n";
}

echo "\nAll migrations complete.\n";