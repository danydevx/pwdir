# ProcessWire Skill - Listings Platform

## Objetivo

Utilizar ProcessWire como CMS principal del proyecto Listings.

ProcessWire es responsable de:

* Gestión de contenido
* Estructura de datos
* Templates
* Routing
* API interna
* SEO
* Administración
* Importaciones

Vue NO reemplaza a ProcessWire.

Vue será una capa de experiencia de usuario encima de ProcessWire.

---

# Filosofía

ProcessWire es el centro del sistema.

Correcto:

```text
ProcessWire
 ├─ Routing
 ├─ Templates
 ├─ SEO
 ├─ Listings
 ├─ Categorías
 ├─ Features
 ├─ Locations
 ├─ Importaciones
 └─ API JSON

Vue
 ├─ Buscador
 ├─ Filtros
 ├─ Mapa
 └─ Resultados
```

Incorrecto:

```text
Vue SPA
+
ProcessWire solo como API
```

---

# Regla principal

Todo debe ser genérico.

No construir un CMS para terrazas.

Construir un CMS para listings.

Correcto:

```text
listing
listing_category
listing_feature
listing_location
listing_type
```

Evitar:

```text
terrace
terrace_category
terrace_feature
```

---

# Arquitectura Base

El sistema debe poder reutilizarse para:

* Terrazas
* Salones
* Jardines
* DJs
* Fotógrafos
* Banquetes
* Mudanzas
* Servicios locales

La arquitectura nunca debe depender de un nicho específico.

---

# Templates

Templates principales:

```text
home
basic_page

listing
listing_category
listing_feature
listing_location
listing_type

api_listings
api_listing
api_categories
api_features
api_locations
```

Mantener pocos templates.

Evitar duplicación.

---

# Campos

Todos los campos personalizados deben usar prefijo:

```text
fld_
```

Ejemplos:

```text
fld_summary
fld_description

fld_phone
fld_whatsapp
fld_email
fld_website

fld_address

fld_latitude
fld_longitude

fld_featured
fld_verified
fld_status

fld_cover_image
fld_gallery

fld_meta_title
fld_meta_description
fld_og_image
```

Importante:

ProcessWire ya incluye:

```text
title
name
```

No crear:

```text
fld_name
```

sin necesidad real.

---

# Listing Types

Los tipos de listing son páginas.

Ejemplo:

```text
Listing Types

├── Venue
├── Service
└── Product
```

Permite clasificar:

```text
Terraza      → Venue
Salón        → Venue
DJ           → Service
Fotógrafo    → Service
Banquete     → Service
```

---

# Categorías

Las categorías son páginas.

Ejemplo:

```text
Listing Categories

├── Terrace
├── Event Venue
├── Photographer
├── DJ
├── Moving
```

No usar listas hardcodeadas.

---

# Features

Las características son páginas.

Ejemplo:

```text
Listing Features

├── Pool
├── Parking
├── Garden
├── Kitchen
├── Security
├── WiFi
```

Relacionar mediante Page Reference.

---

# Locations

Las ubicaciones son páginas.

Ejemplo:

```text
Locations

├── Guadalajara
├── Zapopan
├── Tlaquepaque
├── Tonalá
└── Puerto Vallarta
```

Evitar guardar ciudades repetidas como texto libre.

Usar referencias.

---

# Estructura del Árbol

```text
Home

├── Listings
│
├── Listing Categories
│
├── Listing Features
│
├── Listing Locations
│
├── Listing Types
│
└── Settings
```

---

# Settings

Configuración global del sitio.

Ejemplos:

```text
Logo
WhatsApp principal
Email principal
Teléfono principal
Google Maps API Key
Facebook
Instagram
TikTok
```

---

# Templates PHP

Mantener templates limpios.

Incorrecto:

```php
HTML
+
consultas
+
JSON
+
lógica
+
SEO
```

todo mezclado.

Correcto:

```text
Template
↓
Service
↓
Repository
↓
Render
```

---

# Servicios

Mover lógica a clases reutilizables.

Ubicación:

```text
/site/classes/
```

Ejemplos:

```text
ListingRepository.php
ListingSearch.php
ListingApi.php

CategoryRepository.php
FeatureRepository.php
LocationRepository.php

SeoService.php
```

---

# Search Filters

Preparar filtros reutilizables.

Ubicación:

```text
/site/classes/Search/
```

Ejemplo:

```text
ListingSearchFilters.php
```

Propiedades:

```php
$filters->query;
$filters->category;
$filters->location;
$filters->capacity;
$filters->priceMax;
$filters->featured;
```

Esto facilitará futuras integraciones con Vue o IA.

---

# Selectors

Usar selectors nativos de ProcessWire.

Correcto:

```php
$pages->find("
template=listing,
status<2048,
fld_verified=1
");
```

Evitar SQL manual.

Evitar consultas directas a la base de datos.

---

# API JSON

Las APIs deben vivir dentro de templates.

Ejemplos:

```text
api_listings
api_listing
api_categories
api_features
api_locations
```

---

# Formato API

Respuesta exitosa:

```json
{
  "success": true,
  "data": [],
  "meta": {
    "total": 0,
    "page": 1,
    "pages": 1
  }
}
```

Respuesta de error:

```json
{
  "success": false,
  "message": "Error"
}
```

Mantener formato consistente.

---

# SEO

Las fichas individuales son prioridad.

Ejemplo:

```text
/listings/terraza-mirasol/
/listings/terraza-los-pinos/
```

Cada listing debe incluir:

* Title
* Meta Description
* Open Graph Title
* Open Graph Description
* Open Graph Image
* URL amigable
* Imagen principal

---

# Imágenes

Usar campos nativos de ProcessWire.

Correcto:

```text
fld_cover_image
fld_gallery
```

Evitar módulos innecesarios.

---

# Archivos

Todos los archivos deben almacenarse mediante ProcessWire.

Ubicación:

```text
/site/assets/files/
```

No guardar archivos fuera del sistema de archivos de ProcessWire.

---

# Roles

Para el MVP:

```text
superuser
editor
```

No crear sistemas complejos de permisos todavía.

---

# Módulos

Antes de instalar un módulo:

Preguntar:

```text
¿ProcessWire ya resuelve esto?
```

Si la respuesta es sí:

No instalar módulo.

---

# Hooks

Usar hooks únicamente cuando exista una necesidad real.

Evitar hooks para lógica simple.

---

# Configuración

Variables globales cuando tenga sentido:

```php
$config->listingCategoryRoot;
$config->listingFeatureRoot;
$config->listingLocationRoot;
$config->listingTypeRoot;
```

---

# Importaciones

La carga masiva de listings debe ser reutilizable.

Formatos soportados:

```text
CSV
JSON
```

La importación debe funcionar para cualquier nicho.

No crear importadores específicos para terrazas.

---

# Migraciones

Nunca depender de configuración manual.

Nunca decir:

```text
Crea este campo manualmente en ProcessWire.
```

Siempre crear:

* migración
* script
* instalador

Todo debe ser reproducible desde Git.

---

# MVP Prioridades

Orden correcto:

```text
1. Templates base
2. Campos base
3. Listing Types
4. Categorías
5. Features
6. Locations
7. Listings
8. API
9. SEO
10. Importador
```

---

# Reglas de Desarrollo

* Usar site/
* No usar site-dir/
* No usar Laravel
* No usar Tailwind
* No usar SPA completa
* No usar SQL manual
* No duplicar templates
* No crear arquitectura específica para terrazas
* Mantener ProcessWire como núcleo del sistema

---

# Criterio de Éxito

La implementación es correcta si:

* Puede desplegarse en otro servidor.
* Puede reconstruirse desde Git.
* No depende de configuraciones manuales.
* Soporta múltiples nichos.
* Tiene buen SEO.
* Tiene API reutilizable.
* Es fácil de mantener.
* Puede evolucionar a Vue sin reestructurar ProcessWire.
