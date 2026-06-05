<?php
/**
 * Directorio Helpers
 *
 * Funciones helper para acceder a páginas y configuración.
 */

namespace ProcessWire;

defined('PROCESSWIRE') || die;

/**
 * Get locations page
 */
function dir_locations() {
    return wire('pages')->get(PAGE_LOCATIONS);
}

/**
 * Get categories page
 */
function dir_categories() {
    return wire('pages')->get(PAGE_CATEGORIES);
}

/**
 * Get region by name
 */
function dir_region(string $name): Page|null {
    return wire('pages')->find("template=" . TPL_REGION . ", name={$name}")->first();
}

/**
 * Get state by name
 */
function dir_state(string $name): Page|null {
    return wire('pages')->find("template=" . TPL_STATE . ", name={$name}")->first();
}

/**
 * Get country by name
 */
function dir_country(string $name): Page|null {
    return wire('pages')->find("template=" . TPL_COUNTRY . ", name={$name}")->first();
}

/**
 * Get taxonomy term by slug
 */
function dir_taxonomy_term(string $taxonomy, string $slug): Page|null {
    $taxonomyPage = wire('pages')->find("template={$taxonomy}, name={$slug}")->first();
    if ($taxonomyPage && $taxonomyPage->id) {
        return $taxonomyPage;
    }
    return wire('pages')->find("template=" . TPL_TERM . ", name={$slug}, parent.parent.name={$taxonomy}")->first();
}

/**
 * Get all regions as associative array [name => Page]
 */
function dir_all_regions(): array {
    $regions = wire('pages')->find("template=" . TPL_REGION . ", sort=title");
    $result = [];
    foreach ($regions as $r) {
        $result[$r->name] = $r;
    }
    return $result;
}

/**
 * Get all states as associative array [name => Page]
 */
function dir_all_states(): array {
    $states = wire('pages')->find("template=" . TPL_STATE . ", sort=title");
    $result = [];
    foreach ($states as $s) {
        $result[$s->name] = $s;
    }
    return $result;
}

/**
 * Get all countries as associative array [name => Page]
 */
function dir_all_countries(): array {
    $countries = wire('pages')->find("template=" . TPL_COUNTRY . ", sort=title");
    $result = [];
    foreach ($countries as $c) {
        $result[$c->name] = $c;
    }
    return $result;
}

/**
 * Get taxonomy by name (parent page under /categories/)
 */
function dir_taxonomy(string $name): Page|null {
    return wire('pages')->find("template=" . TPL_CATEGORIES . ", name={$name}")->first();
}

/**
 * Check if template exists
 */
function dir_template_exists(string $name): bool {
    $tpl = wire('templates')->get($name);
    return $tpl !== null;
}

/**
 * Check if field exists
 */
function dir_field_exists(string $name): bool {
    $field = wire('fields')->get($name);
    return $field !== null && $field->id;
}
