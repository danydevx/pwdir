
# AGENTS.md — MVP Directorio Inteligente de Listings

## Contexto del proyecto

Este proyecto es un MVP para validar un directorio inteligente de negocios locales, comenzando con terrazas para fiestas/eventos.

La idea principal es crear una aplicación web donde las personas puedan encontrar lugares fácilmente mediante filtros guiados:

- Fecha del evento
- Número de personas
- Zona o ubicación
- Radio de búsqueda en kilómetros
- Dentro o fuera de la ZMG
- Servicios disponibles, por ejemplo alberca, estacionamiento, cocina, baños, jardín, etc.
- Nivel de verificación
- Contacto directo por WhatsApp

Aunque el primer nicho será terrazas, la arquitectura debe ser genérica para poder reutilizarse después en otros nichos como mudanzas, fotógrafos, banquetes, DJs, salones, etc.

El concepto base NO debe estar amarrado a "terrazas". Debe manejarse como `listing`.

---

## Stack obligatorio

Usar:

- ProcessWire como CMS/backend
- PHP en templates de ProcessWire
- Vue 3 solo para partes interactivas del frontend
- Bootstrap 5
- LESS
- BEM para clases CSS propias
- Axios para consumir APIs internas
- Leaflet para mapas
- Git como control de versiones
- GitHub Actions para deploy por SSH

No usar en el MVP:

- Laravel
- Tailwind CSS
- Nuxt
- Vue Router
- Pinia
- SPA completa
- SSR
- Frameworks UI tipo Vuetify, Quasar, etc.

---

## Filosofía del MVP

Priorizar velocidad, claridad y validación comercial.

El objetivo del MVP es validar si los negocios pagan por aparecer, destacar o verificarse dentro del directorio.

No construir funcionalidades SaaS complejas todavía.

Evitar sobreingeniería.

Antes de crear una solución compleja, preferir una implementación simple, mantenible y fácil de desplegar en hosting compartido.

---

## Arquitectura general

ProcessWire debe manejar:

- Listings
- Categorías
- Servicios/features
- Municipios/zonas
- Verificación
- Fotos
- Datos de contacto
- Fichas SEO
- API JSON interna

Vue debe manejar:

- Wizard de búsqueda
- Filtros dinámicos
- Mapa con pines
- Cards de resultados
- Modal o vista rápida
- Consumo de API con Axios

El flujo esperado es:

```text
Usuario responde preguntas
↓
Vue manda filtros a ProcessWire
↓
ProcessWire busca listings
↓
ProcessWire devuelve JSON
↓
Vue muestra mapa + cards
↓
Usuario abre ficha o contacta por WhatsApp
```

---

## Regla clave de estructura

Usar nombres genéricos.

Correcto:

```text
listing
listing_category
listing_feature
listing_search
```

Evitar:

```text
terraza
terraza_category
terraza_feature
```

La categoría inicial puede ser `terraces`, pero el template base debe seguir siendo genérico.

---

## Convención de campos ProcessWire

Usar prefijo `fld_` para campos personalizados.

Ejemplos:

```text
fld_name
fld_slug
fld_excerpt
fld_description

fld_address
fld_city
fld_state
fld_country
fld_latitude
fld_longitude

fld_phone
fld_whatsapp
fld_email
fld_website

fld_facebook
fld_instagram
fld_tiktok
fld_youtube

fld_capacity_min
fld_capacity_max

fld_price_min
fld_price_max

fld_verified
fld_verification_status
fld_verified_at
fld_verification_notes

fld_featured
fld_plan
fld_status

fld_cover_image
fld_gallery

fld_category
fld_features
fld_tags
fld_region
fld_event_types
```

Campos específicos del nicho terrazas:

```text
fld_has_pool
fld_has_parking
fld_has_kitchen
fld_has_garden
fld_has_security
fld_has_bathrooms
```

Si después se agregan otros nichos, crear campos específicos sin romper la estructura genérica.

---

## Estructura sugerida de páginas

```text
Home
├── Listings
│   ├── Terraces
│   │   ├── Terraza Mirasol
│   │   ├── Terraza Los Pinos
│   │   └── Terraza Jardín Real
│   ├── Moving
│   └── Photographers
│
├── Categories (categories template)
│   ├── Amenities (taxonomy)
│   │   ├── Alberca
│   │   ├── Estacionamiento
│   │   ├── Jardín
│   │   └── Cocina
│   ├── Event Types (taxonomy)
│   │   ├── Bodas
│   │   ├── XV Años
│   │   ├── Cumpleaños
│   │   └── Corporativos
│   └── Services (taxonomy)
│       ├── Catering
│       └── Photography
│
├── Locations (locations template)
│   └── Mexico (country template)
│         └── Jalisco (state template)
│               ├── Guadalajara (region)
│               ├── Zapopan (region)
│               ├── Tonalá (region)
│               ├── Tlaquepaque (region)
│               └── Tlajomulco (region)
│
└── Settings
```

Jerarquía de locations:
- `/locations/` - Página padre con template `locations`
- `/locations/{country}/` - País con template `country`
- `/locations/{country}/{state}/` - Estado con template `state`
- `/locations/{country}/{state}/{region}/` - Ciudad/Región con template `region`

Ver migrations/SKILL.md para `createLocationHierarchy()`.

Estructura de taxonomía:
- `/categories/` - Página padre con template `categories`
- `/categories/{taxonomy-name}/` - Taxonomía con template `{taxonomy-name}`
- `/categories/{taxonomy-name}/{term-slug}/` - Término con template `term`

Ver migrations/SKILL.md para `createTaxonomy()`.

---

## Templates esperados

```text
home
listing
listing-category
basic-page
api
term
locations
country
state
region
```

Descripción de templates de ubicación:

- `locations.php` - Lista países con todos los listings en mapa
- `country.php` - Lista estados con todos los listings del país en mapa
- `state.php` - Lista regiones con todos los listings del estado en mapa
- `region.php` - Lista listings de una región específica con mapa
- `term.php` - Lista listings por taxonomía (event-types, services, amenities)

La ficha individual de un listing debe ser un template normal de ProcessWire para favorecer SEO.

Ejemplo de URL:

```text
/listings/terraces/terraza-mirasol/
```

o, si se decide algo más comercial:

```text
/terrazas/terraza-mirasol/
```

La URL pública puede ser específica del nicho, pero la estructura interna debe mantenerse genérica.

---

## Frontend

El frontend debe usar Bootstrap 5 para layout y componentes base.

Usar LESS para estilos propios.

Usar BEM en componentes personalizados.

Ejemplo:

```html
<div class="listing-card">
    <div class="listing-card__image"></div>
    <div class="listing-card__body"></div>
    <div class="listing-card__title"></div>
    <div class="listing-card__meta"></div>
    <div class="listing-card__actions"></div>
</div>
```

Ejemplo LESS:

```less
.listing-card {
    background: #fff;
    border-radius: 1rem;
    overflow: hidden;

    &__image {
        position: relative;
    }

    &__body {
        padding: 1rem;
    }

    &__title {
        margin-bottom: .5rem;
    }

    &__actions {
        display: flex;
        gap: .5rem;
    }
}
```

---

## Estructura sugerida de assets

```text
/site/templates/
    home.php
    listing.php
    listing-category.php
    api/
        listings.php
        listing.php
        features.php
        locations.php

/site/templates/assets/
    js/
        app.js
        components/
            SearchWizard.vue
            SearchFilters.vue
            SearchMap.vue
            ListingCard.vue
            ListingResults.vue
            ListingQuickView.vue

    less/
        main.less
        variables.less
        mixins.less

        base/
        layout/
        components/
        pages/
```

---

## API interna

Crear endpoints simples en ProcessWire.

Endpoints sugeridos:

```text
/api/listings
/api/listings/search
/api/listings/{id}
/api/features
/api/locations
/api/categories
```

La API debe devolver JSON limpio y estable para Vue.

Ejemplo de respuesta de listing:

```json
{
  "id": 1234,
  "title": "Terraza Mirasol",
  "slug": "terraza-mirasol",
  "url": "/terrazas/terraza-mirasol/",
  "excerpt": "Terraza para eventos familiares en Zapopan.",
  "address": "Zapopan, Jalisco",
  "latitude": 20.000000,
  "longitude": -103.000000,
  "capacity_min": 30,
  "capacity_max": 120,
  "verified": true,
  "verification_status": "basic",
  "featured": false,
  "cover_image": "/site/assets/files/1234/cover.jpg",
  "features": ["Alberca", "Estacionamiento", "Jardín"],
  "whatsapp": "523300000000"
}
```

Ejemplo de respuesta de `/api/locations`:

```json
{
  "success": true,
  "count": 5,
  "data": [
    {
      "id": 1104,
      "title": "Guadalajara",
      "slug": "guadalajara",
      "url": "/locations/mexico/jalisco/guadalajara/",
      "state": "Jalisco",
      "country": "Mexico",
      "listing_count": 3
    }
  ]
}
```

---

## Búsqueda por distancia

Para el MVP se puede calcular distancia con fórmula Haversine en PHP o filtrar inicialmente con aproximación por latitud/longitud.

Filtros mínimos:

- Categoría
- Capacidad
- Features
- Municipio/zona
- Verificación
- Radio en km desde un punto lat/lng
- Texto libre por nombre o descripción

No complicar con motores externos de búsqueda al inicio.

No usar Elasticsearch, Meilisearch ni Algolia en el MVP.

---

## Configuración centralizada

Toda la configuración de templates, campos y páginas está centralizada en:

```text
/site/templates/_constants.php  # Constantes
/site/templates/_helpers.php    # Funciones helper
```

### Constantes disponibles

**Templates:**
```php
TPL_LISTING       // 'listing'
TPL_LOCATIONS     // 'locations'
TPL_COUNTRY       // 'country'
TPL_STATE         // 'state'
TPL_REGION        // 'region'
TPL_TERM          // 'term'
TPL_CATEGORIES    // 'categories'
```

**Campos:**
```php
FLD_REGION              // 'fld_region'
FLD_CATEGORY            // 'fld_category'
FLD_FEATURES            // 'fld_features'
FLD_EVENT_TYPES         // 'fld_event_types'
FLD_SERVICES            // 'fld_services'
FLD_AMENITIES           // 'fld_amenities'
FLD_STATUS              // 'fld_status'
// ... y más (ver archivo _constants.php)
```

**Páginas:**
```php
PAGE_LOCATIONS    // '/locations/'
PAGE_CATEGORIES   // '/categories/'
```

**Status y planes:**
```php
STATUS_ACTIVE / STATUS_INACTIVE / STATUS_PENDING
PLAN_FREE / PLAN_BASIC / PLAN_PREMIUM
VERIFY_UNVERIFIED / VERIFY_BASIC / VERIFY_DOCUMENTS / VERIFY_ONSITE / VERIFY_REPORTED
```

### Helpers disponibles

```php
dir_locations()           // Retorna página /locations/
dir_categories()          // Retorna página /categories/
dir_region('guadalajara')  // Retorna página de región por nombre
dir_state('jalisco')       // Retorna página de estado por nombre
dir_country('mexico')      // Retorna página de país por nombre
dir_taxonomy('event-types') // Retorna página de taxonomía por nombre
dir_all_regions()         // Array [name => Page] de todas las regiones
dir_all_states()          // Array [name => Page] de todos los estados
dir_all_countries()       // Array [name => Page] de todos los países
dir_template_exists('listing')  // Boolean
dir_field_exists('fld_region')  // Boolean
```

### Uso en templates

```php
<?php namespace ProcessWire;

use const ProcessWire\TPL_LISTING;
use const ProcessWire\FLD_STATUS;
use const ProcessWire\STATUS_ACTIVE;

require_once __DIR__ . '/_constants.php';
require_once __DIR__ . '/_helpers.php';

$listings = $pages->find("template=" . TPL_LISTING . ", " . FLD_STATUS . "=" . STATUS_ACTIVE);
```

Si se elimina o recrea un template, campo o página, solo modificar `_constants.php`.

---

## Verificación

La verificación es un diferenciador importante contra fraudes.

Usar estados simples:

```text
unverified
basic
documents
onsite
reported
```

Mostrar badges claros en frontend:

```text
Sin verificar
WhatsApp y ubicación confirmados
Verificada con documentación
Verificada presencialmente
Reportada
```

Campos sugeridos:

```text
fld_verified
fld_verification_status
fld_verified_at
fld_verified_by
fld_verification_notes
fld_whatsapp_confirmed
fld_location_confirmed
fld_socials_confirmed
fld_recent_photos_confirmed
fld_fraud_report_count
```

Para el MVP, la verificación mínima puede ser:

```text
WhatsApp confirmado + ubicación confirmada
```

---

## Contacto por WhatsApp

Cada card y ficha debe tener botón de WhatsApp.

El enlace debe incluir mensaje prellenado.

Ejemplo:

```text
Hola, vi tu terraza en [Nombre del directorio] y quiero información para un evento.
```

Registrar leads puede dejarse para una segunda etapa, pero el código debe quedar preparado para agregarlo.

---

## Migraciones en ProcessWire

No hacer cambios estructurales manuales sin registrarlos en código.

Todo cambio estructural debe tener migración PHP versionada.

Crear carpeta:

```text
/site/migrations/
```

Ejemplo:

```text
/site/migrations/
    001_create_listing_fields.php
    002_create_listing_templates.php
    003_create_listing_taxonomies.php
    004_create_default_pages.php
```

Crear runner:

```text
/site/tools/run-migrations.php
```

Las migraciones deben ser idempotentes.

Eso significa que pueden ejecutarse varias veces sin romper nada.

Ejemplo:

```php
<?php namespace ProcessWire;

if (!$fields->get('fld_whatsapp')) {
    $field = new Field();
    $field->type = $modules->get('FieldtypeText');
    $field->name = 'fld_whatsapp';
    $field->label = 'WhatsApp';
    $field->save();
}
```

El agente NO debe decir:

```text
Crea este campo desde el admin de ProcessWire.
```

Debe crear o modificar una migración.

---

## Regla obligatoria para el agente

Cuando se necesite crear:

- Campos
- Templates
- Fieldgroups
- Roles
- Permisos
- Páginas base
- Configuración estructural

Entonces crear una migración.

No depender de pasos manuales del admin.

---

## Deploy

El proyecto debe poder desplegarse por SSH usando GitHub Actions.

El deploy debe:

1. Instalar/build frontend si aplica.
2. Subir archivos al servidor.
3. No subir archivos sensibles.
4. No borrar `/site/assets/files/`.
5. Ejecutar migraciones.
6. Limpiar cache si aplica.

No versionar:

```text
/site/assets/cache/
/site/assets/files/
/site/config.php
/node_modules/
/vendor/
.env
```

Versionar:

```text
/site/templates/
/site/modules/ propios
/site/migrations/
/site/tools/
/package.json
/vite.config.js si aplica
/AGENTS.md
```

---

## Git

Usar ramas:

```text
main        producción
develop     desarrollo/staging
feature/*   cambios puntuales
```

Flujo:

```text
feature/* → develop → main
```

No trabajar directo en `main`.

Todo cambio importante debe tener commit claro.

Ejemplos:

```text
feat: add listing search api
feat: create listing fields migration
fix: prevent empty coordinates in map
refactor: rename terrace components to listing components
```

---

## Reglas para el agente IA

1. Mantener el proyecto simple.
2. No introducir dependencias innecesarias.
3. No cambiar el stack sin autorización.
4. No usar Tailwind.
5. No usar Laravel.
6. No convertir el frontend en SPA completa.
7. No agregar Pinia ni Vue Router en el MVP.
8. No crear cambios estructurales manuales sin migración.
9. Mantener nombres genéricos basados en `listing`.
10. Usar `fld_` para campos personalizados de ProcessWire.
11. Usar Bootstrap 5 para layout y componentes comunes.
12. Usar LESS + BEM para CSS propio.
13. Usar Leaflet para mapas.
14. Usar Axios para llamadas a la API.
15. Priorizar SEO en fichas individuales.
16. Priorizar contacto por WhatsApp.
17. Priorizar verificación como elemento de confianza.
18. Mantener código fácil de copiar, revisar y desplegar.
19. Documentar decisiones importantes.
20. No sobreingenierizar.

---

## Prioridades del MVP

Orden sugerido:

1. Estructura ProcessWire para listings.
2. Migraciones base.
3. Importación inicial de listings.
4. Ficha individual SEO.
5. API JSON de búsqueda.
6. Vue Search Wizard.
7. Cards de resultados.
8. Mapa con Leaflet.
9. Filtros por radio/capacidad/features.
10. Botón WhatsApp.
11. Badges de verificación.
12. Plan destacado/premium.
13. Deploy por GitHub Actions.

---

## Funcionalidades fuera del MVP

No construir todavía:

- Panel multiusuario para dueños de negocios
- Suscripciones automáticas
- Stripe
- Facturación
- CRM completo
- Chat IA
- Comparador avanzado
- Favoritos
- Reseñas públicas
- App móvil
- Notificaciones
- API pública para terceros

Estas funcionalidades pueden considerarse después de validar con clientes pagados.

---

## Criterio de éxito del MVP

El MVP se considera validado si consigue aproximadamente:

```text
10 a 20 listings pagados
```

Especialmente si los negocios pagan por:

- Aparecer destacados
- Tener ficha verificada
- Tener más visibilidad
- Recibir prospectos por WhatsApp

Después de eso se puede evaluar una versión más robusta en Laravel.

---

## Nota estratégica

El activo principal del proyecto no es el framework.

El activo principal es:

- Base de datos curada
- Listings verificados
- SEO local
- Relación con negocios
- Generación de prospectos
- Confianza contra fraude

Construir pensando en reutilización, pero validar primero con el nicho de terrazas.
