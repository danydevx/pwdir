# Migrations Skill - ProcessWire Listings

## Objetivo

Gestionar cambios estructurales en ProcessWire de forma versionada, reproducible e idempotente.

Toda modificación a la estructura del CMS debe vivir en una migración.

Esto incluye:

* Campos
* Templates
* Fieldgroups
* Páginas base
* Taxonomías
* Roles
* Permisos
* Settings
* API templates

---

# Stack

Usar:

* PHP
* ProcessWire API
* Selectors nativos
* `$modules`
* `$fields`
* `$templates`
* `$fieldgroups`
* `$pages`
* `$roles`
* `$permissions`

No usar:

* SQL manual
* Módulos externos innecesarios
* Cambios manuales desde el admin
* Scripts específicos para un nicho

---

# Filosofía

ProcessWire ya tiene sistema nativo de campos, templates y páginas.

Las migraciones permiten:

* Replicar estructura en otro servidor
* Versionar cambios con Git
* Desplegar sin pasos manuales
* Reconstruir el CMS desde cero
* Mantener consistencia entre local, staging y producción

Nunca decir:

```txt
Crea este campo manualmente en el admin de ProcessWire.
```

Siempre crear:

```txt
migración
script
instalador
```

---

# Regla Principal

El sistema es genérico.

No crear migraciones específicas para terrazas.

Correcto:

```txt
listing
listing_category
listing_feature
listing_location
listing_type
```

Incorrecto:

```txt
terrace
terrace_category
terrace_feature
terrace_fields
```

---

# Ubicación

Migraciones:

```txt
/site/migrations/
```

Runner principal:

```txt
/site/migrate.php
```

Ejemplo:

```txt
/site/migrations/
    001_create_base_fields.php
    002_create_taxonomy_fields.php
    003_create_listing_templates.php
    004_create_default_pages.php
    005_create_roles.php

/site/migrate.php
```

---

# Convenciones de Nombre

Formato:

```txt
{order}_{short_description}.php
```

Ejemplos:

```txt
001_create_base_fields.php
002_create_taxonomy_fields.php
003_create_listing_templates.php
004_create_default_pages.php
005_create_roles.php
006_add_seo_fields_to_listing.php
```

Reglas:

* Usar orden numérico.
* Usar nombres descriptivos.
* No usar nombres de nicho.
* No editar migraciones ya ejecutadas.
* Crear nueva migración para cada cambio estructural.

---

# Orden Sugerido de Migraciones

```txt
001_create_base_fields.php
002_create_taxonomy_fields.php
003_create_listing_templates.php
004_create_api_templates.php
005_create_default_pages.php
006_create_default_taxonomies.php
007_create_settings.php
008_create_roles.php
009_create_demo_listings.php
```

Notas:

* Campos antes que templates.
* Templates antes que páginas.
* Páginas raíz antes que Page Reference finales.
* Taxonomías antes que listings demo.
* API templates después de templates base.
* Demo data al final.

---

# Idempotencia

Toda migración debe poder ejecutarse múltiples veces sin errores.

Correcto:

```php
if (!$fields->get('fld_whatsapp')) {
    // crear campo
}
```

Lo mismo aplica para:

* Campos
* Templates
* Fieldgroups
* Páginas
* Roles
* Permisos
* Settings

Si ya existe, se omite o se actualiza de forma segura.

---

# Campos

Todos los campos personalizados usan prefijo:

```txt
fld_
```

Ejemplos:

```txt
fld_summary
fld_description
fld_phone
fld_whatsapp
fld_email
fld_website
fld_address
fld_latitude
fld_longitude
fld_capacity_min
fld_capacity_max
fld_price_from
fld_price_to
fld_featured
fld_verified
fld_status
fld_cover_image
fld_gallery
fld_meta_title
fld_meta_description
fld_og_image
```

No crear:

```txt
fld_name
```

ProcessWire ya tiene:

```txt
title
name
```

---

# Templates Base

Templates principales:

```txt
home
basic_page
listing
listing_category
listing_feature
listing_location
listing_type
settings
```

Templates API:

```txt
api_listings
api_listing
api_categories
api_features
api_locations
```

No usar guiones en nombres de templates.

Correcto:

```txt
listing_category
```

Incorrecto:

```txt
listing-category
```

---

# Estructura de Árbol Esperada

```txt
Home

├── Listings
├── Listing Categories
├── Listing Features
├── Listing Locations
├── Listing Types
└── Settings
```

Rutas sugeridas:

```txt
/listings/
/listing-categories/
/listing-features/
/listing-locations/
/listing-types/
/settings/
```

---

# Helper: Crear Campo Texto

```php
<?php namespace ProcessWire;

function createTextField($name, $label, $notes = '') {
    $fields = wire('fields');
    $modules = wire('modules');

    if ($fields->get($name)) {
        return $fields->get($name);
    }

    $field = new Field();
    $field->type = $modules->get('FieldtypeText');
    $field->name = $name;
    $field->label = $label;
    $field->notes = $notes;
    $field->save();

    return $field;
}
```

---

# Helper: Crear Campo Textarea

```php
<?php namespace ProcessWire;

function createTextareaField($name, $label, $notes = '') {
    $fields = wire('fields');
    $modules = wire('modules');

    if ($fields->get($name)) {
        return $fields->get($name);
    }

    $field = new Field();
    $field->type = $modules->get('FieldtypeTextarea');
    $field->name = $name;
    $field->label = $label;
    $field->notes = $notes;
    $field->save();

    return $field;
}
```

---

# Textarea - Opciones Avanzadas

## Textarea Básico

```php
<?php namespace ProcessWire;

$field = new Field();
$field->type = $modules->get('FieldtypeTextarea');
$field->name = 'description';
$field->label = 'Descripción';

$fields->save($field);
```

## Configurar Número de Filas

```php
$field->rows = 8;
```

## Textarea con CKEditor

Requiere que CKEditor esté instalado.

```php
<?php namespace ProcessWire;

$field = new Field();
$field->type = $modules->get('FieldtypeTextarea');
$field->name = 'content';
$field->label = 'Contenido';

$field->inputfieldClass = 'InputfieldCKEditor';
$field->rows = 20;

$fields->save($field);
```

## Textarea Simple (sin editor)

```php
$field->inputfieldClass = 'InputfieldTextarea';
```

## Propiedades Útiles

```php
$field->rows = 10;
$field->required = 1;
$field->collapsed = Inputfield::collapsedNo;
$field->notes = 'Texto de ayuda';
$field->description = 'Descripción del campo';
```

## Patrón Recomendado para Migraciones

```php
<?php namespace ProcessWire;

$name = 'description';

if (!$fields->get($name)) {
    $field = new Field();
    $field->type = $modules->get('FieldtypeTextarea');
    $field->name = $name;
    $field->label = 'Descripción';
    $field->description = 'Descripción larga';
    $field->notes = 'Información adicional';
    $field->rows = 10;
    $field->required = 0;
    $field->inputfieldClass = 'InputfieldTextarea';

    $fields->save($field);
}
```

## Inspeccionar Configuración de un Campo Existente

```php
$field = $fields->get('body');

// Ver todas las propiedades
bd($field->getArray());

// o
foreach ($field->getArray() as $key => $value) {
    echo "$key => $value\n";
}
```

## Tipos de Inputfield para Textarea

```text
InputfieldTextarea      - Área de texto simple
InputfieldCKEditor      - Editor WYSIWYG
InputfieldTinyMCE       - Editor TinyMCE
InputfieldMarkup        - Para contenido renderizado
```

---

# Helper: Crear Campo Integer

```php
<?php namespace ProcessWire;

function createIntegerField($name, $label, $notes = '') {
    $fields = wire('fields');
    $modules = wire('modules');

    if ($fields->get($name)) {
        return $fields->get($name);
    }

    $field = new Field();
    $field->type = $modules->get('FieldtypeInteger');
    $field->name = $name;
    $field->label = $label;
    $field->notes = $notes;
    $field->save();

    return $field;
}
```

---

# Helper: Crear Campo Checkbox

```php
<?php namespace ProcessWire;

function createCheckboxField($name, $label, $notes = '') {
    $fields = wire('fields');
    $modules = wire('modules');

    if ($fields->get($name)) {
        return $fields->get($name);
    }

    $field = new Field();
    $field->type = $modules->get('FieldtypeCheckbox');
    $field->name = $name;
    $field->label = $label;
    $field->notes = $notes;
    $field->save();

    return $field;
}
```

---

# Helper: Crear Campo Imagen

```php
<?php namespace ProcessWire;

function createImageField($name, $label, $maxFiles = 0, $notes = '') {
    $fields = wire('fields');
    $modules = wire('modules');

    if ($fields->get($name)) {
        return $fields->get($name);
    }

    $field = new Field();
    $field->type = $modules->get('FieldtypeImage');
    $field->name = $name;
    $field->label = $label;
    $field->maxFiles = $maxFiles;
    $field->notes = $notes;
    $field->save();

    return $field;
}
```

---

# Helper: Crear Campo File

```php
<?php namespace ProcessWire;

function createFileField($name, $label, $extensions = 'pdf doc docx', $maxFiles = 10, $maxSize = 10485760, $notes = '') {
    $fields = wire('fields');
    $modules = wire('modules');

    if ($fields->get($name)) {
        return $fields->get($name);
    }

    $field = new Field();
    $field->type = $modules->get('FieldtypeFile');
    $field->name = $name;
    $field->label = $label;
    $field->extensions = $extensions;
    $field->maxFiles = $maxFiles;
    $field->maxSize = $maxSize;
    $field->notes = $notes;
    $field->save();

    return $field;
}
```

---

# Campo File - Opciones Avanzadas

## Campo File Básico

```php
<?php namespace ProcessWire;

$field = new Field();
$field->type = $modules->get('FieldtypeFile');
$field->name = 'documentos';
$field->label = 'Documentos';

$fields->save($field);
```

## Limitar Cantidad de Archivos

```php
$field->maxFiles = 1;   // Solo un archivo
$field->maxFiles = 10;   // Múltiples archivos
```

## Extensiones Permitidas

```php
$field->extensions = 'pdf doc docx xls xlsx zip';
```

## Tamaño Máximo

En bytes (10 MB):

```php
$field->maxSize = 10485760;
```

## Campo Requerido

```php
$field->required = 1;
```

## Descripción y Notas

```php
$field->description = 'Adjunta documentos PDF';
$field->notes = 'Tamaño máximo 10 MB';
```

## Patrón Recomendado para Migraciones

```php
<?php namespace ProcessWire;

$name = 'documents';

if (!$fields->get($name)) {
    $field = new Field();
    $field->type = $modules->get('FieldtypeFile');
    $field->name = $name;
    $field->label = 'Documentos';
    $field->description = 'Archivos adjuntos';
    $field->notes = 'PDF y Word';
    $field->extensions = 'pdf doc docx';
    $field->maxFiles = 10;
    $field->maxSize = 10485760;

    $fields->save($field);
}
```

## Valores Recomendados

### Un solo PDF
```php
$field->extensions = 'pdf';
$field->maxFiles = 1;
$field->maxSize = 10485760;
```

### Documentos de Oficina
```php
$field->extensions = 'pdf doc docx xls xlsx ppt pptx';
$field->maxFiles = 20;
$field->maxSize = 20971520;
```

## Obtener Archivos

```php
foreach ($page->documents as $file) {
    echo $file->url;
    echo $file->basename;
    echo $file->filesize;
}

// Primer archivo
$file = $page->documents->first();
```

## Agregar Archivo por API

```php
$page = $pages->get('/mi-pagina/');
$page->documents->add('/ruta/archivo.pdf');
$page->save('documents');
```

---

# Helper: Crear Page Reference

```php
<?php namespace ProcessWire;

function createPageReferenceField($name, $label, $parentPath, $multiple = true, $notes = '') {
    $fields = wire('fields');
    $modules = wire('modules');
    $pages = wire('pages');

    if ($fields->get($name)) {
        return $fields->get($name);
    }

    $parent = $pages->get($parentPath);

    $field = new Field();
    $field->type = $modules->get('FieldtypePage');
    $field->name = $name;
    $field->label = $label;
    $field->notes = $notes;

    if ($parent && $parent->id) {
        $field->parent_id = $parent->id;
    }

    $field->derefAsPage = $multiple
        ? FieldtypePage::derefAsPageArray
        : FieldtypePage::derefAsPageOrFalse;

    $field->save();

    return $field;
}
```

---

# Helper: Crear Template

```php
<?php namespace ProcessWire;

function createTemplateWithFields($templateName, array $fieldNames) {
    $templates = wire('templates');
    $fields = wire('fields');

    if ($templates->get($templateName)) {
        return $templates->get($templateName);
    }

    $fieldgroup = new Fieldgroup();
    $fieldgroup->name = $templateName;

    foreach ($fieldNames as $fieldName) {
        $field = $fields->get($fieldName);

        if ($field && $field->id) {
            $fieldgroup->add($field);
        }
    }

    $fieldgroup->save();

    $template = new Template();
    $template->name = $templateName;
    $template->fieldgroup = $fieldgroup;
    $template->noSave = false;
    $template->save();

    return $template;
}
```

---

# Helper: Crear Taxonomía (Template taxonomy + term + campo en listing)

Para crear una nueva taxonomía:

1. Asegurar que exista página "Categories" (template: listing-category) bajo Home (/)
2. Crear fieldgroup y template para taxonomy (nombre = $taxonomyName)
3. Crear fieldgroup y template para term
4. Crear campo Page Reference en listing con tipo FieldtypePage
   - Configurar inputfield: InputfieldSelect (valor único) o InputfieldAsmSelect (múltiples)
   - Configurar derefAsPage: 1 (único) o 0 (múltiples)
   - Configurar parent_id para restringir a los términos de esta taxonomía
   - Configurar template_id al template 'term'
5. Crear página de taxonomía como hija de `/categories/`
6. Agregar campo al template listing
7. Crear términos como hijos de la taxonomía

```php
<?php namespace ProcessWire;

function createTaxonomy($taxonomyName, $taxonomyLabel, array $terms = [], $multiple = false) {
    $templates = wire('templates');
    $fieldgroups = wire('fieldgroups');
    $fields = wire('fields');
    $pages = wire('pages');
    $modules = wire('modules');

    // 1. Crear/verificar página "categories" bajo Home (/) con template 'categories'
    $homePage = $pages->get('/');
    $categoriesPage = $pages->find("parent={$homePage}, name=categories")->first();

    if (!$categoriesPage || !$categoriesPage->id) {
        $categoriesTemplate = $templates->get('categories');
        $categoriesPage = new Page();
        $categoriesPage->template = $categoriesTemplate;
        $categoriesPage->parent = $homePage;
        $categoriesPage->title = 'Categories';
        $categoriesPage->name = 'categories';
        $categoriesPage->save();
    }

    // 2. Crear fieldgroup y template para taxonomy
    $taxonomyFg = $fieldgroups->get($taxonomyName);
    if (!$taxonomyFg) {
        $taxonomyFg = new Fieldgroup();
        $taxonomyFg->name = $taxonomyName;
        $taxonomyFg->save();
    }

    $taxonomyTemplate = $templates->get($taxonomyName);
    if (!$taxonomyTemplate) {
        $taxonomyTemplate = new Template();
        $taxonomyTemplate->name = $taxonomyName;
        $taxonomyTemplate->label = $taxonomyLabel;
        $taxonomyTemplate->fieldgroup = $taxonomyFg;
        $taxonomyTemplate->save();
    }

    // 3. Crear fieldgroup y template para term
    $termFg = $fieldgroups->get('term');
    if (!$termFg) {
        $termFg = new Fieldgroup();
        $termFg->name = 'term';
        $termFg->save();
    }

    $termTemplate = $templates->get('term');
    if (!$termTemplate) {
        $termTemplate = new Template();
        $termTemplate->name = 'term';
        $termTemplate->label = 'Term';
        $termTemplate->fieldgroup = $termFg;
        $termTemplate->save();
    }

    // 4. Crear página de taxonomía bajo /categories/
    $taxonomyPage = $pages->find("parent={$categoriesPage}, name={$taxonomyName}")->first();
    if (!$taxonomyPage || !$taxonomyPage->id) {
        $taxonomyPage = new Page();
        $taxonomyPage->template = $taxonomyTemplate;
        $taxonomyPage->parent = $categoriesPage;
        $taxonomyPage->title = $taxonomyLabel;
        $taxonomyPage->name = $taxonomyName;
        $taxonomyPage->save();
    }

    // 5. Crear campo Page Reference en listing
    $fieldName = 'fld_' . $taxonomyName;
    $field = $fields->get($fieldName);
    if (!$field) {
        $field = new Field();
        $field->name = $fieldName;
        $field->type = $modules->get('FieldtypePage');
        $field->label = $taxonomyLabel;

        // Configurar input type según sea múltiple o no
        if ($multiple) {
            // Múltiples valores: InputfieldAsmSelect
            $field->inputfield = 'InputfieldAsmSelect';
            $field->derefAsPage = 0;
        } else {
            // Valor único: InputfieldSelect
            $field->inputfield = 'InputfieldSelect';
            $field->derefAsPage = 1;
        }

        // Restringir a páginas hijos de la taxonomía
        $field->parent_id = $taxonomyPage->id;
        // Restringir al template 'term'
        $field->template_id = $termTemplate->id;

        $field->save();
    }

    // 6. Agregar campo al template listing si no está
    $listingTemplate = $templates->get('listing');
    if ($listingTemplate && $listingTemplate->fieldgroup) {
        if (!$listingTemplate->fieldgroup->hasField($field)) {
            $listingTemplate->fieldgroup->add($field);
            $listingTemplate->fieldgroup->save();
        }
    }

    // 7. Crear términos
    foreach ($terms as $term) {
        $existing = $pages->find("parent={$taxonomyPage}, name={$term['name']}")->first();
        if (!$existing || !$existing->id) {
            $page = new Page();
            $page->template = $termTemplate;
            $page->parent = $taxonomyPage;
            $page->title = $term['title'];
            $page->name = $term['name'];
            $page->save();
        }
    }

    return $taxonomyPage;
}
```

Ejemplo de uso:

```php
// Crear taxonomía "Tipos de Eventos" - valor único (Select)
createTaxonomy('event-types', 'Tipos de Eventos', [
    ['name' => 'bodas', 'title' => 'Bodas'],
    ['name' => 'xv-anos', 'title' => 'XV Años'],
    ['name' => 'cumpleanos', 'title' => 'Cumpleaños'],
    ['name' => 'corporativos', 'title' => 'Evento Corporativo'],
    ['name' => 'graduaciones', 'title' => 'Graduación'],
    ['name' => 'bautizos', 'title' => 'Bautizo'],
    ['name' => 'otros', 'title' => 'Otros'],
]);

// Crear taxonomía "Services" - múltiples valores (Checkbox)
createTaxonomy('services', 'Services', [
    ['name' => 'catering', 'title' => 'Catering'],
    ['name' => 'photography', 'title' => 'Photography'],
    ['name' => 'music', 'title' => 'Music/DJ'],
], true); // true = múltiples valores

// Crear taxonomía "Amenities" - múltiples valores (Checkbox)
createTaxonomy('amenities', 'Amenities', [
    ['name' => 'pool', 'title' => 'Alberca'],
    ['name' => 'parking', 'title' => 'Estacionamiento'],
    ['name' => 'wifi', 'title' => 'WiFi'],
    ['name' => 'garden', 'title' => 'Jardín'],
], true);
```

Parámetros:
- `$taxonomyName`: nombre del template y del campo (ej: 'event-types')
- `$taxonomyLabel`: label para el campo y la página (ej: 'Tipos de Eventos')
- `$terms`: array de términos a crear
- `$multiple` (default: false): si es true, usa InputfieldAsmSelect (múltiples valores), si es false usa InputfieldSelect (valor único)

Input types comunes para campos Page Reference:

```php
// Valor único - Select
$field->inputfield = 'InputfieldSelect';
$field->derefAsPage = 1;

// Valor único - Radio buttons
$field->inputfield = 'InputfieldRadios';
$field->derefAsPage = 1;

// Valor único - Page list selector
$field->inputfield = 'InputfieldPageListSelect';
$field->derefAsPage = 1;

// Múltiples valores - AsmSelect (típico para múltiples)
$field->inputfield = 'InputfieldAsmSelect';
$field->derefAsPage = 0;

// Múltiples valores - Checkboxes
$field->inputfield = 'InputfieldCheckboxes';
$field->derefAsPage = 0;

// Autocomplete
$field->inputfield = 'InputfieldPageAutocomplete';
$field->derefAsPage = 0;
```

Propiedades importantes:
- `inputfield`: tipo de input (InputfieldSelect, InputfieldAsmSelect, etc.)
- `parent_id`: ID de la página padre de donde se seleccionan las páginas
- `template_id`: ID del template que deben tener las páginas seleccionables
- `derefAsPage`: 1 para una página, 0 para múltiples páginas

Estructura resultante:

```text
Home
├── Listings
├── Categories (categories template)
│   ├── Amenities (amenities taxonomy)
│   │   ├── Alberca (term)
│   │   ├── Estacionamiento (term)
│   │   └── ...
│   ├── Event Types (event-types taxonomy)
│   │   ├── Bodas (term)
│   │   ├── XV Años (term)
│   │   └── ...
│   └── Services (services taxonomy)
│       ├── Musica (term)
│       └── Luz (term)
├── Locations
└── Settings
```

---

# Helper: Crear Jerarquía de Locations (País → Estado → Región)

Para crear una estructura geográfica jerárquica:

1. Crear templates: `locations`, `country`, `state`, `region`
2. Crear página `/locations/` con template `locations`
3. Crear países bajo `/locations/`
4. Crear estados bajo cada país
5. Crear regiones/ciudades bajo cada estado

```php
<?php namespace ProcessWire;

function createLocationHierarchy($countryName, $countryLabel, array $states = []) {
    $templates = wire('templates');
    $fieldgroups = wire('fieldgroups');
    $fields = wire('fields');
    $pages = wire('pages');

    $titleField = $fields->get('title');

    // Crear templates
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

    // Crear página /locations/
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
    }

    // Crear país
    $countryPage = $pages->find("parent={$locationsPage}, name={$countryName}")->first();
    if (!$countryPage || !$countryPage->id) {
        $countryPage = new Page();
        $countryPage->template = $countryTemplate;
        $countryPage->parent = $locationsPage;
        $countryPage->title = $countryLabel;
        $countryPage->name = $countryName;
        $countryPage->save();
    }

    // Crear estados y regiones
    foreach ($states as $state) {
        $statePage = $pages->find("parent={$countryPage}, name={$state['name']}")->first();
        if (!$statePage || !$statePage->id) {
            $statePage = new Page();
            $statePage->template = $stateTemplate;
            $statePage->parent = $countryPage;
            $statePage->title = $state['label'];
            $statePage->name = $state['name'];
            $statePage->save();
        }

        // Crear regiones bajo el estado
        if (isset($state['regions'])) {
            foreach ($state['regions'] as $region) {
                $existing = $pages->find("parent={$statePage}, name={$region['name']}")->first();
                if (!$existing || !$existing->id) {
                    $regionPage = new Page();
                    $regionPage->template = $regionTemplate;
                    $regionPage->parent = $statePage;
                    $regionPage->title = $region['label'];
                    $regionPage->name = $region['name'];
                    $regionPage->save();
                }
            }
        }
    }

    return $countryPage;
}
```

Ejemplo de uso:

```php
// Crear estructura para México
createLocationHierarchy('mexico', 'Mexico', [
    [
        'name' => 'jalisco',
        'label' => 'Jalisco',
        'regions' => [
            ['name' => 'guadalajara', 'label' => 'Guadalajara'],
            ['name' => 'zapopan', 'label' => 'Zapopan'],
            ['name' => 'tonala', 'label' => 'Tonalá'],
            ['name' => 'tlaquepaque', 'label' => 'Tlaquepaque'],
            ['name' => 'tlajomulco', 'label' => 'Tlajomulco de Zúñiga'],
        ]
    ],
    [
        'name' => 'colima',
        'label' => 'Colima',
        'regions' => [
            ['name' => 'colima', 'label' => 'Colima'],
            ['name' => 'manzanillo', 'label' => 'Manzanillo'],
        ]
    ],
]);
```

Estructura resultante:

```text
/locations/ (locations template)
  └── /locations/mexico/ (country template)
        ├── /locations/mexico/jalisco/ (state template)
        │     ├── guadalajara (region)
        │     ├── zapopan (region)
        │     ├── tonala (region)
        │     └── tlaquepaque (region)
        └── /locations/mexico/colima/ (state template)
              ├── colima (region)
              └── Manzanillo (region)
```

---

# Helper: Agregar Campo a Template

```php
<?php namespace ProcessWire;

function addFieldToTemplate($templateName, $fieldName) {
    $templates = wire('templates');
    $fields = wire('fields');

    $template = $templates->get($templateName);
    $field = $fields->get($fieldName);

    if (!$template || !$template->id || !$field || !$field->id) {
        return false;
    }

    if (!$template->fieldgroup->hasField($field)) {
        $template->fieldgroup->add($field);
        $template->fieldgroup->save();
    }

    return true;
}
```

---

# Helper: Crear Página

```php
<?php namespace ProcessWire;

function createPageIfMissing($templateName, $parentPath, $name, $title) {
    $pages = wire('pages');

    $parent = $pages->get($parentPath);

    if (!$parent || !$parent->id) {
        return null;
    }

    $existing = $pages->get("parent={$parent->id}, name=$name");

    if ($existing && $existing->id) {
        return $existing;
    }

    $page = new Page();
    $page->template = $templateName;
    $page->parent = $parent;
    $page->name = $name;
    $page->title = $title;
    $page->save();

    return $page;
}
```

---

# Helper: Crear Rol

```php
<?php namespace ProcessWire;

function createRoleIfMissing($roleName, $label = '') {
    $roles = wire('roles');

    if ($roles->get($roleName)) {
        return $roles->get($roleName);
    }

    $role = new Role();
    $role->name = $roleName;

    if ($label) {
        $role->label = $label;
    }

    $role->save();

    return $role;
}
```

---

# Helper: Agregar Permiso a Rol

```php
<?php namespace ProcessWire;

function addPermissionToRole($roleName, $permissionName) {
    $roles = wire('roles');
    $permissions = wire('permissions');

    $role = $roles->get($roleName);
    $permission = $permissions->get($permissionName);

    if (!$role || !$role->id || !$permission || !$permission->id) {
        return false;
    }

    if (!$role->hasPermission($permission)) {
        $role->addPermission($permission);
        $role->save();
    }

    return true;
}
```

---

# Ejemplo: Crear Campos Base

```php
<?php namespace ProcessWire;

require_once __DIR__ . '/helpers.php';

createTextField('fld_summary', 'Resumen');
createTextareaField('fld_description', 'Descripción');

createTextField('fld_phone', 'Teléfono');
createTextField('fld_whatsapp', 'WhatsApp');
createTextField('fld_email', 'Email');
createTextField('fld_website', 'Website');

createTextField('fld_address', 'Dirección');
createTextField('fld_latitude', 'Latitud');
createTextField('fld_longitude', 'Longitud');

createIntegerField('fld_capacity_min', 'Capacidad mínima');
createIntegerField('fld_capacity_max', 'Capacidad máxima');
createIntegerField('fld_price_from', 'Precio desde');
createIntegerField('fld_price_to', 'Precio hasta');

createCheckboxField('fld_featured', 'Destacado');
createCheckboxField('fld_verified', 'Verificado');
createCheckboxField('fld_status', 'Activo');

createImageField('fld_cover_image', 'Imagen de portada', 1);
createImageField('fld_gallery', 'Galería', 0);

createTextField('fld_meta_title', 'Meta title');
createTextareaField('fld_meta_description', 'Meta description');
createImageField('fld_og_image', 'Open Graph image', 1);
```

---

# Ejemplo: Crear Campos Taxonómicos

```php
<?php namespace ProcessWire;

require_once __DIR__ . '/helpers.php';

createPageReferenceField(
    'fld_listing_type',
    'Tipo de listing',
    '/listing-types/',
    false
);

createPageReferenceField(
    'fld_categories',
    'Categorías',
    '/listing-categories/',
    true
);

createPageReferenceField(
    'fld_features',
    'Características',
    '/listing-features/',
    true
);

createPageReferenceField(
    'fld_location',
    'Ubicación',
    '/listing-locations/',
    false
);
```

---

# Ejemplo: Crear Template Listing

```php
<?php namespace ProcessWire;

require_once __DIR__ . '/helpers.php';

createTemplateWithFields('listing', [
    'title',
    'fld_summary',
    'fld_description',

    'fld_listing_type',
    'fld_categories',
    'fld_features',
    'fld_location',

    'fld_phone',
    'fld_whatsapp',
    'fld_email',
    'fld_website',

    'fld_address',
    'fld_latitude',
    'fld_longitude',

    'fld_capacity_min',
    'fld_capacity_max',
    'fld_price_from',
    'fld_price_to',

    'fld_featured',
    'fld_verified',
    'fld_status',

    'fld_cover_image',
    'fld_gallery',

    'fld_meta_title',
    'fld_meta_description',
    'fld_og_image',
]);
```

---

# Ejemplo: Crear Templates Taxonómicos

```php
<?php namespace ProcessWire;

require_once __DIR__ . '/helpers.php';

createTemplateWithFields('listing_category', [
    'title',
    'fld_summary',
    'fld_description',
    'fld_cover_image',
    'fld_meta_title',
    'fld_meta_description',
    'fld_og_image',
]);

createTemplateWithFields('listing_feature', [
    'title',
    'fld_summary',
    'fld_description',
    'fld_cover_image',
]);

createTemplateWithFields('listing_location', [
    'title',
    'fld_summary',
    'fld_description',
    'fld_latitude',
    'fld_longitude',
    'fld_meta_title',
    'fld_meta_description',
    'fld_og_image',
]);

createTemplateWithFields('listing_type', [
    'title',
    'fld_summary',
    'fld_description',
]);
```

---

# Ejemplo: Crear Páginas Base

```php
<?php namespace ProcessWire;

require_once __DIR__ . '/helpers.php';

createPageIfMissing('basic_page', '/', 'listings', 'Listings');
createPageIfMissing('basic_page', '/', 'listing-categories', 'Listing Categories');
createPageIfMissing('basic_page', '/', 'listing-features', 'Listing Features');
createPageIfMissing('basic_page', '/', 'listing-locations', 'Listing Locations');
createPageIfMissing('basic_page', '/', 'listing-types', 'Listing Types');
createPageIfMissing('settings', '/', 'settings', 'Settings');
```

---

# Ejemplo: Crear Taxonomías Default

```php
<?php namespace ProcessWire;

require_once __DIR__ . '/helpers.php';

createPageIfMissing('listing_type', '/listing-types/', 'venue', 'Venue');
createPageIfMissing('listing_type', '/listing-types/', 'service', 'Service');
createPageIfMissing('listing_type', '/listing-types/', 'product', 'Product');

createPageIfMissing('listing_category', '/listing-categories/', 'terrace', 'Terrace');
createPageIfMissing('listing_category', '/listing-categories/', 'event-venue', 'Event Venue');
createPageIfMissing('listing_category', '/listing-categories/', 'photographer', 'Photographer');
createPageIfMissing('listing_category', '/listing-categories/', 'dj', 'DJ');
createPageIfMissing('listing_category', '/listing-categories/', 'moving', 'Moving');

createPageIfMissing('listing_feature', '/listing-features/', 'pool', 'Pool');
createPageIfMissing('listing_feature', '/listing-features/', 'parking', 'Parking');
createPageIfMissing('listing_feature', '/listing-features/', 'garden', 'Garden');
createPageIfMissing('listing_feature', '/listing-features/', 'kitchen', 'Kitchen');
createPageIfMissing('listing_feature', '/listing-features/', 'security', 'Security');
createPageIfMissing('listing_feature', '/listing-features/', 'wifi', 'WiFi');

createPageIfMissing('listing_location', '/listing-locations/', 'guadalajara', 'Guadalajara');
createPageIfMissing('listing_location', '/listing-locations/', 'zapopan', 'Zapopan');
createPageIfMissing('listing_location', '/listing-locations/', 'tlaquepaque', 'Tlaquepaque');
createPageIfMissing('listing_location', '/listing-locations/', 'tonala', 'Tonalá');
```

---

# Runner de Migraciones

Crear en:

```txt
/site/migrate.php
```

Contenido:

```php
<?php namespace ProcessWire;

require_once __DIR__ . '/../index.php';

echo "==============================\n";
echo " ProcessWire Migrations\n";
echo "==============================\n\n";

$migrationsPath = __DIR__ . '/migrations/';
$migrationsLog = __DIR__ . '/migrations.log';

if (!is_dir($migrationsPath)) {
    echo "No migrations directory found.\n";
    exit;
}

$executed = file_exists($migrationsLog)
    ? json_decode(file_get_contents($migrationsLog), true)
    : [];

if (!is_array($executed)) {
    $executed = [];
}

$files = glob($migrationsPath . '*.php');
sort($files);

foreach ($files as $file) {
    $filename = basename($file);

    if (isset($executed[$filename])) {
        echo "SKIP: {$filename}\n";
        continue;
    }

    echo "Running: {$filename}\n";

    try {
        require $file;

        $executed[$filename] = date('Y-m-d H:i:s');
        file_put_contents(
            $migrationsLog,
            json_encode($executed, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        echo "OK: {$filename}\n\n";
    } catch (\Throwable $e) {
        echo "ERROR: {$filename}\n";
        echo $e->getMessage() . "\n";
        echo $e->getFile() . ':' . $e->getLine() . "\n";
        exit(1);
    }
}

echo "\nFinished.\n";
```

Ejecutar:

```bash
php site/migrate.php
```

---

# Registro de Ejecuciones

El runner guarda:

```txt
/site/migrations.log
```

Ejemplo:

```json
{
  "001_create_base_fields.php": "2026-06-04 10:30:00",
  "002_create_taxonomy_fields.php": "2026-06-04 10:30:01"
}
```

Si una migración ya está en el log, no se vuelve a ejecutar.

---

# Sistema de Migraciones (Nuevo)

El sistema actual usa un módulo `MigrationRunner` que almacena el estado en una tabla de base de datos.

## Estructura

```txt
/site/modules/
    MigrationRunner/
        MigrationRunner.module

/site/migrations/
    index.php          # Runner CLI
    bootstrap.php       # Para sync de migraciones existentes
    _template.php       # Template para nuevas migraciones
    001_xxx.php
    002_yyy.php
    ...
```

## Comandos

```bash
# Ver estado de migraciones
php site/migrations/index.php --status

# Ejecutar todas las pendientes
php site/migrations/index.php

# Ejecutar una específica
php site/migrations/index.php 010_name

# Forzar continuación en caso de errores
php site/migrations/index.php --force
```

## Formato de migración

```php
<?php
/**
 * Migration: 010_example
 * Description: Creates example structure
 * Depends: 009_previous_migration
 */

$fields = $GLOBALS['__mig_fields'];
$modules = $GLOBALS['__mig_modules'];
$pages = $GLOBALS['__mig_pages'];
$templates = $GLOBALS['__mig_templates'];
$fieldgroups = $GLOBALS['__mig_fieldgroups'];
$fieldtypes = $GLOBALS['__mig_fieldtypes'];

// Tu código aquí
// Importante: verificar si existe antes de crear

return true;
```

## Variables disponibles

```php
$GLOBALS['__mig_fields']      // Wire('fields')
$GLOBALS['__mig_modules']     // Wire('modules')
$GLOBALS['__mig_pages']       // Wire('pages')
$GLOBALS['__mig_templates']   // Wire('templates')
$GLOBALS['__mig_fieldgroups'] // Wire('fieldgroups')
$GLOBALS['__mig_fieldtypes']  // Wire('fieldtypes')
```

## Tabla de tracking

El módulo crea automáticamente la tabla `migrations` en la BD.

## Git Ignore

No versionar:

```txt
/site/assets/cache/
/site/assets/logs/
/site/assets/sessions/
/site/assets/backups/
/site/config.php
```

Sí versionar:

```txt
/site/modules/MigrationRunner/
/site/migrations/
```

---

# Regenerar o Cambiar Migración

No editar una migración ya ejecutada.

Incorrecto:

```txt
Editar:
001_create_base_fields.php
```

Correcto:

```txt
Crear:
010_add_seo_fields_to_listing.php
011_add_rating_field_to_listing.php
012_update_listing_template_fields.php
```

Las migraciones son un historial de cambios.

---

# Rollback

ProcessWire no tiene rollback automático en este sistema.

Para revertir:

1. Crear nueva migración que quite o modifique el elemento.
2. Ejecutarla normalmente.
3. Confirmar que el estado actual del CMS coincide con Git.

No borrar datos automáticamente salvo que sea explícitamente necesario.

---

# Buenas Prácticas

* Una migración por concepto.
* Nombres descriptivos.
* Código idempotente.
* Evitar SQL manual.
* Evitar módulos externos.
* Probar en local antes de subir.
* No mezclar demasiadas responsabilidades en un archivo.
* No usar nombres específicos de nicho.
* Usar `title` nativo de ProcessWire.
* Usar selectors nativos.
* Mantener todo reproducible desde Git.
* Siempre retornar `true` al final para marcar como exitosa.

---

# Errores Comunes

## Campo ya existe

Incorrecto:

```php
$field = new Field();
$field->name = 'fld_whatsapp';
$field->save();
```

Correcto:

```php
if (!$fields->get('fld_whatsapp')) {
    $field = new Field();
    $field->name = 'fld_whatsapp';
    $field->save();
}
```

## Template ya existe

Correcto:

```php
if (!$templates->get('listing')) {
    // crear template
}
```

## Página ya existe

Correcto:

```php
$existing = $pages->get("parent=/listings/, name=terraza-demo");

if (!$existing->id) {
    // crear página
}
```

## Referencia a página inexistente

Antes de crear un Page Reference, asegurar que la página raíz existe:

```txt
/listing-categories/
/listing-features/
/listing-locations/
/listing-types/
```

---

# Criterio de Éxito

La migración está bien si:

* Puede ejecutarse en un ProcessWire limpio.
* Puede ejecutarse varias veces sin duplicar nada.
* No falla si algo ya existe.
* Refleja exactamente la estructura del CMS.
* Se despliega con Git.
* No depende del admin.
* No depende de configuraciones manuales.
* Es fácil de entender para otro desarrollador.
