<?php
/**
 * Migration Runner
 *
 * Executes pending migrations in order.
 *
 * Usage:
 *   php index.php              - Run all pending migrations
 *   php index.php --status     - Show migration status
 *   php index.php --force      - Continue on errors
 *   php index.php 010_name     - Run specific migration
 */

require(__DIR__ . '/../../index.php');

$wire = $GLOBALS['wire'];
$runner = $wire->modules->get('MigrationRunner');
$migrationsPath = __DIR__;

function println($msg, $type = 'info') {
    $prefix = '';
    $color = '';
    $reset = "\033[0m";

    switch ($type) {
        case 'success':
            $color = "\033[32m";
            $prefix = '✓ ';
            break;
        case 'error':
            $color = "\033[31m";
            $prefix = '✗ ';
            break;
        case 'warning':
            $color = "\033[33m";
            $prefix = '○ ';
            break;
        case 'info':
        default:
            $color = "\033[36m";
            $prefix = '  ';
            break;
    }

    echo "{$color}{$prefix}{$msg}{$reset}\n";
}

function getMigrationName($filename) {
    return basename($filename, '.php');
}

// Set up global variables for migrations
$GLOBALS['__mig_fields'] = $wire->fields;
$GLOBALS['__mig_modules'] = $wire->modules;
$GLOBALS['__mig_pages'] = $wire->pages;
$GLOBALS['__mig_templates'] = $wire->templates;
$GLOBALS['__mig_fieldgroups'] = $wire->fieldgroups;
$GLOBALS['__mig_fieldtypes'] = $wire->fieldtypes;

// Also set up a global wire() function for migrations that use it
if (!function_exists('wire')) {
    $GLOBALS['__wire_instance'] = $wire;
    eval('function wire($name = null) { $w = $GLOBALS["__wire_instance"]; return $name ? $w->$name : $w; }');
}

// Handle --status flag
if (in_array('--status', $argv)) {
    println("=== Migration Status ===\n", 'info');

    $executed = $runner->getExecutedMigrations();
    $pending = $runner->getPendingMigrations($migrationsPath);

    if (empty($executed) && empty($pending)) {
        println("No migrations found.", 'warning');
        exit;
    }

    if (!empty($executed)) {
        println("Executed migrations:", 'info');
        foreach ($executed as $m) {
            $status = $m['success'] ? '✓' : '✗';
            $error = $m['error_message'] ? " ({$m['error_message']})" : '';
            println("  {$status} {$m['name']} - {$m['executed_at']}{$error}", $m['success'] ? 'success' : 'error');
        }
    }

    if (!empty($pending)) {
        println("\nPending migrations:", 'warning');
        foreach ($pending as $m) {
            println("  ○ {$m['name']}", 'warning');
        }
    }

    println("\nTotal: " . count($executed) . " executed, " . count($pending) . " pending");
    exit;
}

// Handle specific migration
$singleMigration = null;
foreach ($argv as $arg) {
    if (preg_match('/^\d{3}_.+$/', $arg)) {
        $singleMigration = $arg;
        break;
    }
}

// Run migrations
println("=== Migration Runner ===\n", 'info');

if ($singleMigration) {
    $file = $migrationsPath . '/' . $singleMigration . '.php';
    if (!file_exists($file)) {
        println("Migration not found: {$singleMigration}", 'error');
        exit(1);
    }
    $pending = [['name' => $singleMigration, 'file' => $file]];
    println("Running specific migration: {$singleMigration}\n", 'info');
} else {
    $pending = $runner->getPendingMigrations($migrationsPath);
    println("Found " . count($pending) . " pending migrations\n", 'info');
}

if (empty($pending)) {
    println("Nothing to migrate.", 'success');
    exit;
}

$executedCount = 0;
$failedCount = 0;

foreach ($pending as $m) {
    $name = $m['name'];
    $file = $m['file'];
    $info = $runner->getMigrationInfo($file);

    println("----------------------------------------");
    println("Migration: {$name}", 'info');
    if ($info['description']) {
        println("Description: {$info['description']}", 'info');
    }
    if ($info['depends']) {
        println("Depends: " . implode(', ', $info['depends']));
    }
    println("", 'info');

    // Check dependencies
    $depsOk = true;
    foreach ($info['depends'] as $dep) {
        if (!$runner->isExecuted($dep)) {
            println("SKIP: Dependency not met ({$dep})", 'error');
            $depsOk = false;
            break;
        }
    }

    if (!$depsOk) {
        $failedCount++;
        continue;
    }

    // Check if already executed successfully
    if ($runner->isExecuted($name)) {
        println("SKIP: Already executed", 'warning');
        continue;
    }

    // Calculate checksum
    $checksum = $runner->calculateChecksum($file);

    // Execute migration
    ob_start();
    $startTime = microtime(true);

    try {
        // Re-set globals in case they changed
        $GLOBALS['__mig_fields'] = wire('fields');
        $GLOBALS['__mig_modules'] = wire('modules');
        $GLOBALS['__mig_pages'] = wire('pages');
        $GLOBALS['__mig_templates'] = wire('templates');
        $GLOBALS['__mig_fieldgroups'] = wire('fieldgroups');
        $GLOBALS['__mig_fieldtypes'] = wire('fieldtypes');

        $success = include($file);
        $executionTime = round(microtime(true) - $startTime, 3);

        // Check if migration returned false (failure)
        if ($success === false) {
            throw new \Exception('Migration returned false');
        }

        $runner->markExecuted($name, $checksum, true);
        println("SUCCESS ({$executionTime}s)", 'success');
        $executedCount++;

    } catch (\Throwable $e) {
        $errorMessage = $e->getMessage();
        $runner->markExecuted($name, $checksum, false, $errorMessage);
        println("FAILED: {$errorMessage}", 'error');
        $failedCount++;

        // Stop on first failure unless --force
        if (!in_array('--force', $argv)) {
            println("\nStopped on first failure. Run with --force to continue anyway.", 'warning');
            break;
        }
    }

    $output = ob_get_clean();
    if ($output && trim($output)) {
        echo "  Output: " . trim($output) . "\n";
    }
}

println("\n----------------------------------------");
println("Migrations completed:", 'info');
println("  Executed: {$executedCount}", 'success');
println("  Failed: {$failedCount}", $failedCount > 0 ? 'error' : 'success');

exit($failedCount > 0 ? 1 : 0);
