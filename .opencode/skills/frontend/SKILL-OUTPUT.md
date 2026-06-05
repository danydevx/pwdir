
# FRONTEND.md — MVP Frontend con ProcessWire Direct Output

## Objetivo

Construir el frontend del MVP Listings usando ProcessWire con estrategia **Direct Output**.

Esto significa que cada template PHP imprime directamente su HTML.

No usar:

* Markup Regions
* Delayed Output
* Layout con `$content`
* `ob_start()`
* `_main.php`
* `_init.php`

ProcessWire debe renderizar todo el sitio con PHP templates simples, HTML, Bootstrap 5, LESS y BEM.

Referencia: ProcessWire Direct Output.

---

# Decisión actual

Usar:

```txt
Direct Output
```

Cada template contiene su estructura HTML completa o incluye parciales comunes.

Vue, Axios, Leaflet y API JSON quedan para el final.

---

# Stack de esta fase

Usar:

* ProcessWire
* PHP templates
* HTML semántico
* Bootstrap 5
* LESS
* BEM
* JavaScript nativo mínimo

No usar todavía:

* Vue
* Axios
* Leaflet
* Pinia
* Vue Router
* Nuxt
* Tailwind
* SPA
* Markup Regions
* Delayed Output

---

# Configuración importante

Como se usará Direct Output, revisar en:

```txt
/site/config.php
```

Si existen estas líneas:

```php
$config->useMarkupRegions = true;
$config->prependTemplateFile = '_init.php';
$config->appendTemplateFile = '_main.php';
```

Para Direct Output deben eliminarse o desactivarse:

```php
$config->useMarkupRegions = false;
$config->prependTemplateFile = '';
$config->appendTemplateFile = '';
```

No usar `_main.php`.

No usar `_init.php`.

---

# Filosofía

Correcto:

```txt
home.php imprime HTML directo
listings.php imprime HTML directo
listing.php imprime HTML directo
partials ayudan a no repetir header/footer
```

Incorrecto:

```txt
template llena $content
layout/main.php imprime $content
markup regions reemplazan regiones
_main.php controla todo
```

---

# Estructura de archivos

Usar esta estructura:

```txt
/site/templates/
    home.php
    basic_page.php
    listings.php
    listing.php

    partials/
        head.php
        header.php
        nav.php
        footer.php
        listing-card.php
        listing-filters.php
        pagination.php
        seo-vars.php

    assets/
        css/
            main.css

        js/
            main.js

        less/
            main.less
            variables.less
            mixins.less

            base/
                reset.less
                typography.less
                helpers.less

            layout/
                header.less
                footer.less
                sections.less

            components/
                buttons.less
                listing-card.less
                listing-filters.less
                listing-gallery.less
                badges.less

            pages/
                home.less
                listings.less
                listing-detail.less
```

No crear:

```txt
layouts/main.php
_init.php
_main.php
```

---

# Patrón de template Direct Output

Ejemplo base:

```php
<?php namespace ProcessWire; ?>
<!doctype html>
<html lang="es">
<head>
    <?php include $config->paths->templates . 'partials/head.php'; ?>
</head>
<body>

<?php include $config->paths->templates . 'partials/header.php'; ?>

<main class="site-main">
    <section class="page-section">
        <div class="container">
            <h1><?= $page->title ?></h1>
        </div>
    </section>
</main>

<?php include $config->paths->templates . 'partials/footer.php'; ?>

<script src="<?= $config->urls->templates ?>assets/js/main.js"></script>
</body>
</html>
```

---

# home.php

Debe imprimir HTML completo usando Direct Output.

Debe incluir:

```php
<?php include $config->paths->templates . 'partials/head.php'; ?>
<?php include $config->paths->templates . 'partials/header.php'; ?>
<?php include $config->paths->templates . 'partials/footer.php'; ?>
```

Debe mostrar:

* Hero principal
* Buscador simple hacia `/listings/`
* Categorías destacadas
* Listings destacados
* Beneficios del directorio
* CTA de WhatsApp o contacto

---

# listings.php

Debe imprimir HTML completo usando Direct Output.

Debe mostrar:

* Header
* Hero pequeño
* Filtros GET
* Total de resultados
* Grid de cards
* Paginación
* Mensaje si no hay resultados
* Footer

Debe usar partials:

```txt
partials/listing-filters.php
partials/listing-card.php
partials/pagination.php
```

---

# listing.php

Debe imprimir HTML completo usando Direct Output.

Debe mostrar:

* Header
* Imagen principal
* Título
* Resumen
* Descripción
* Galería
* Precio
* Capacidad
* Ubicación
* Categorías
* Features
* Botón WhatsApp
* Botón email
* Información de confianza/verificación
* Listings relacionados si aplica
* Footer

---

# basic_page.php

Debe imprimir HTML completo usando Direct Output.

Usar para:

* Acerca de
* Contacto
* Aviso de privacidad
* Términos

---

# Partials

Los partials NO deben imprimir documentos HTML completos.

## head.php

Debe incluir solo contenido del `<head>`:

* charset
* viewport
* title
* meta description
* canonical
* Open Graph básico
* Bootstrap CSS
* main.css

## header.php

Debe incluir:

* `<header>`
* logo
* navegación principal
* botón CTA
* menú responsive Bootstrap si se usa

## footer.php

Debe incluir:

* `<footer>`
* enlaces principales
* contacto
* redes sociales si existen
* copyright

## listing-card.php

Debe recibir:

```php
$listing
```

y renderizar una card reutilizable.

## listing-filters.php

Debe renderizar filtros GET reutilizables.

## pagination.php

Debe renderizar paginación simple preservando query strings.

---

# SEO en Direct Output

Como no habrá `_main.php`, cada template debe incluir:

```php
<?php include $config->paths->templates . 'partials/head.php'; ?>
```

El partial `head.php` debe calcular valores con fallback:

```php
$metaTitle = $page->fld_meta_title ?: $page->title;
$metaDescription = $page->fld_meta_description ?: $page->fld_summary;
```

Cada página debe tener:

* `<title>`
* `<meta name="description">`
* canonical
* Open Graph title
* Open Graph description
* Open Graph image cuando exista

---

# Filtros GET

Los filtros deben funcionar con query string.

Ejemplo:

```txt
/listings/?q=jardin&category=terrace&location=zapopan&capacity=100&price_max=8000&featured=1
```

Filtros iniciales:

```txt
q
category
location
capacity
price_max
featured
```

Reglas:

* Sanitizar todo input con `$sanitizer`.
* No usar `$_GET` directo.
* Preservar filtros al paginar.
* Mostrar mensaje si no hay resultados.
* No romper si el usuario manda valores vacíos.

---

# Selector base de listings

Usar selectors de ProcessWire.

Ejemplo:

```php
$selector = [
    'template=listing',
    'status<2048',
    'fld_status=1',
    'limit=12',
    'sort=-fld_featured, title',
];
```

Agregar filtros de forma progresiva.

---

# Búsqueda por texto

Buscar en:

```txt
title
fld_summary
fld_description
fld_address
```

Ejemplo:

```php
if ($q) {
    $selector[] = "title|fld_summary|fld_description|fld_address%={$q}";
}
```

---

# Cards de listing

Cada card debe mostrar solo lo esencial:

* Imagen
* Título
* Resumen corto
* Ubicación
* Capacidad máxima
* Precio desde
* Badges
* Botón WhatsApp
* Botón Ver detalle

No sobrecargar la card.

---

# WhatsApp

Todas las cards y fichas deben tener botón de WhatsApp.

Formato:

```txt
https://wa.me/523300000000?text=Hola%2C%20quiero%20informaci%C3%B3n%20sobre%20...
```

El número debe venir de:

```txt
fld_whatsapp
```

El mensaje debe incluir el título del listing.

---

# Imágenes

Usar campos nativos de ProcessWire:

```txt
fld_cover_image
fld_gallery
```

Reglas:

* Si no hay imagen, usar placeholder local.
* Siempre incluir `alt`.
* No romper si el campo está vacío.
* Usar tamaños generados por ProcessWire.

Ejemplo:

```php
$image = $listing->fld_cover_image->count()
    ? $listing->fld_cover_image->first()->size(800, 500)
    : null;
```

---

# LESS

No escribir CSS inline.

Correcto:

```html
<div class="listing-card">
```

Incorrecto:

```html
<div style="margin-top: 20px;">
```

---

# BEM

Usar BEM para componentes propios.

Ejemplo:

```less
.listing-card {
    &__image {}
    &__body {}
    &__title {}
    &__meta {}
    &__actions {}
}
```

---

# Bootstrap

Usar Bootstrap para:

```txt
container
row
col
card
btn
badge
form-control
form-select
navbar
dropdown
modal
pagination
```

No reinventar lo que Bootstrap ya resuelve.

---

# JavaScript

Usar JavaScript nativo solo para mejoras pequeñas.

No usar JS para renderizar contenido principal.

El sitio debe seguir funcionando sin JavaScript para lo esencial.

---

# No hacer todavía

No implementar todavía:

* Vue
* Axios
* Leaflet
* API JSON como base del render
* Search Wizard
* Mapa interactivo
* Resultados dinámicos sin recarga
* Favoritos
* Reviews
* Panel de negocios
* Login para dueños

---

# Orden recomendado para el agente

## Fase Frontend 1 — Direct Output base

Crear o revisar:

```txt
home.php
basic_page.php
listings.php
listing.php
partials/head.php
partials/header.php
partials/footer.php
assets/less/main.less
assets/css/main.css
assets/js/main.js
```

Eliminar del plan:

```txt
layouts/main.php
_init.php
_main.php
ob_start()
$content
```

Detenerse al terminar.

---

## Fase Frontend 2 — Home

Crear o mejorar `home.php` con:

* HTML completo
* Hero
* Buscador simple hacia `/listings/`
* Categorías destacadas
* Listings destacados
* CTA contacto

Detenerse al terminar.

---

## Fase Frontend 3 — Listado

Crear o mejorar:

```txt
listings.php
partials/listing-card.php
partials/listing-filters.php
partials/pagination.php
```

Con:

* Filtros GET
* Cards
* Paginación
* Mensaje sin resultados

Detenerse al terminar.

---

## Fase Frontend 4 — Detalle

Crear o mejorar:

```txt
listing.php
partials/listing-gallery.php
```

Con:

* Imagen principal
* Galería
* Información completa
* WhatsApp
* Features
* SEO básico

Detenerse al terminar.

---

## Fase Frontend 5 — LESS

Crear componentes LESS:

```txt
components/listing-card.less
components/listing-filters.less
components/listing-gallery.less
components/badges.less
pages/home.less
pages/listings.less
pages/listing-detail.less
```

Compilar a:

```txt
assets/css/main.css
```

Detenerse al terminar.

---

## Fase Frontend 6 — Ajustes UX

Mejorar:

* Responsive
* Estados vacíos
* Botones
* Badges
* Espaciados
* Accesibilidad
* Performance de imágenes

Detenerse al terminar.

---

## Fase Frontend 7 — SEO básico

Crear o mejorar:

```txt
partials/head.php
```

Con:

* title
* meta description
* canonical
* Open Graph

Detenerse al terminar.

---

# Instrucción para el agente

Ejecutar una fase a la vez.

No avanzar a la siguiente fase sin confirmación.

Usar Direct Output.

No usar Markup Regions.

No usar Delayed Output.

No usar `_main.php`.

No usar `_init.php`.

No usar `ob_start()`.

No usar `$content`.

No implementar Vue.

No implementar API JSON como base del render.

No instalar dependencias nuevas.

No cambiar `site/`.

No usar `site-dir/`.

No cambiar nombres genéricos de `listing`.

Si falta estructura de campos o templates, crear migración.
