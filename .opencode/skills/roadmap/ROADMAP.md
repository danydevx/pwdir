ROADMAP MVP LISTINGS - PROCESSWIRE DESDE CERO

CARPETA ACTUAL:
Usar site/

NO usar site-dir/

OBJETIVO:
Crear un directorio genérico de listings en ProcessWire.

Primer nicho:
Terrazas para eventos.

Pero la arquitectura debe servir después para:
- Terrazas
- Salones
- Fotógrafos
- DJs
- Banquetes
- Mudanzas
- Servicios locales

CONCEPTO BASE:
Todo gira alrededor de listing.

NO crear arquitectura amarrada a terrazas.

==================================================
FASE 1 - VALIDAR INSTALACIÓN
==================================================

Objetivo:
Confirmar que ProcessWire funciona limpio.

Revisar:

/wire
/site
/index.php
/.htaccess

Confirmar:

- El frontend carga.
- El admin carga en /processwire/.
- site/config.php existe.
- site/assets/ es escribible.
- site/modules/ es escribible en desarrollo.
- site/templates/ existe.
- No existe dependencia a site-dir/.

No crear campos todavía.
No crear templates todavía.
No tocar Vue.

Detenerse al terminar esta fase.

==================================================
FASE 2 - GIT Y LIMPIEZA BASE
==================================================

Objetivo:
Dejar el proyecto versionado antes de construir.

Crear o revisar .gitignore para ProcessWire.

Ignorar:

site/assets/cache/
site/assets/logs/
site/assets/sessions/
site/assets/backups/
site/config.php
node_modules/
vendor/
.env

Comandos:

git status
git add .
git commit -m "Initial clean ProcessWire setup"

Detenerse al terminar esta fase.

==================================================
FASE 3 - ESTRUCTURA DEL TEMA
==================================================

Objetivo:
Crear la estructura base de frontend clásico.

Crear:

site/templates/layouts/
site/templates/partials/
site/templates/assets/css/
site/templates/assets/js/
site/templates/assets/less/
site/templates/assets/img/

Crear archivo principal:

site/templates/assets/less/main.less
site/templates/assets/css/main.css
site/templates/assets/js/main.js

Crear parciales:

site/templates/partials/header.php
site/templates/partials/footer.php

No usar Vue.
No usar Tailwind.
Usar Bootstrap 5.

Detenerse al terminar esta fase.

==================================================
FASE 4 - SISTEMA SIMPLE DE MIGRACIONES
==================================================

Objetivo:
Crear migraciones PHP simples para ProcessWire.

Crear:

site/migrations/
site/migrate.php

Reglas:

- Las migraciones deben poder ejecutarse varias veces.
- Si un campo existe, saltarlo.
- Si un template existe, saltarlo.
- Si una página existe, saltarla.
- No borrar datos automáticamente.
- No hacer cambios destructivos.

Archivos esperados:

site/migrations/001_create_base_fields.php
site/migrations/002_create_listing_templates.php
site/migrations/003_create_demo_listings.php

Detenerse al terminar esta fase.

==================================================
FASE 5 - CAMPOS BASE DE LISTING
==================================================

Objetivo:
Crear campos genéricos para el modelo listing.

Todos los campos deben usar prefijo:

listing_

Campos básicos:

listing_summary
listing_description
listing_image
listing_gallery
listing_price_from
listing_price_to
listing_capacity_min
listing_capacity_max
listing_address
listing_city
listing_state
listing_country
listing_lat
listing_lng
listing_phone
listing_whatsapp
listing_email
listing_website
listing_status
listing_featured

Campos SEO:

listing_meta_title
listing_meta_description
listing_og_image

Importante:

No crear campos como:
terraza_price
terraza_capacity
salon_address

Siempre usar:
listing_price_from
listing_capacity_max
listing_address

Detenerse al terminar esta fase.

==================================================
FASE 6 - CATEGORÍAS Y CARACTERÍSTICAS
==================================================

Objetivo:
Agregar estructura reutilizable para clasificar listings.

Crear templates:

listing_category
listing_feature

Crear páginas padre:

/listing-categories/
/listing-features/

Ejemplos de categorías:

- Terraza
- Salón
- Jardín
- Quinta

Ejemplos de características:

- Alberca
- Estacionamiento
- Cocina
- Área techada
- Jardín
- Baños
- Música permitida

Agregar a listing campos tipo Page Reference:

listing_categories
listing_features

Importante:
Esto permite reutilizar el sistema para otros nichos.

Detenerse al terminar esta fase.

==================================================
FASE 7 - TEMPLATE LISTING Y PÁGINA PADRE
==================================================

Objetivo:
Crear el contenido principal del directorio.

Crear template:

listing

Crear template padre:

listings

Crear página:

/listings/

Los listings deben vivir en:

/listings/nombre-del-listing/

El template listing debe tener:

- title
- listing_summary
- listing_description
- listing_image
- listing_gallery
- listing_price_from
- listing_price_to
- listing_capacity_min
- listing_capacity_max
- listing_address
- listing_city
- listing_state
- listing_country
- listing_lat
- listing_lng
- listing_phone
- listing_whatsapp
- listing_email
- listing_website
- listing_status
- listing_featured
- listing_categories
- listing_features
- campos SEO

Detenerse al terminar esta fase.

==================================================
FASE 8 - DATOS DEMO
==================================================

Objetivo:
Crear contenido de prueba.

Crear mínimo 3 listings:

1. Terraza Jardín Norte
2. Terraza Vista Luna
3. Salón Aurora

Cada demo debe tener:

- título
- resumen
- descripción
- imagen si es posible
- precio desde
- precio hasta
- capacidad mínima
- capacidad máxima
- dirección
- ciudad
- estado
- país
- WhatsApp
- status activo
- destacado sí/no
- categoría
- características

Detenerse al terminar esta fase.

==================================================
FASE 9 - FRONTEND LISTINGS SIN VUE
==================================================

Objetivo:
Crear listado público en PHP clásico.

Archivo:

site/templates/listings.php

Debe mostrar:

- Header
- Hero simple
- Filtros básicos por GET
- Grid de cards
- Paginación simple
- Footer

Cada card debe mostrar:

- imagen
- título
- resumen
- ciudad/estado
- capacidad máxima
- precio desde
- categorías
- botón ver detalle

Filtros iniciales:

q
category
city
capacity
price_max
featured

No usar Vue.
No usar Axios.

Detenerse al terminar esta fase.

==================================================
FASE 10 - FRONTEND DETALLE SIN VUE
==================================================

Objetivo:
Crear vista individual de listing.

Archivo:

site/templates/listing.php

Debe mostrar:

- Header
- Imagen principal
- Galería
- Título
- Resumen
- Descripción
- Precio
- Capacidad
- Dirección
- Ciudad/Estado
- Categorías
- Características
- Botón WhatsApp
- Botón email
- Footer

No usar Vue.

Detenerse al terminar esta fase.

==================================================
FASE 11 - ESTILOS BASE
==================================================

Objetivo:
Ordenar estilos con Bootstrap 5 + LESS + BEM.

Crear estructura:

site/templates/assets/less/
  main.less
  base/
  components/
  pages/

Componentes mínimos:

listing-card
listing-filters
listing-detail
listing-gallery
listing-badge
listing-hero

Compilar a:

site/templates/assets/css/main.css

No usar Tailwind.

Detenerse al terminar esta fase.

==================================================
FASE 12 - API JSON LISTINGS
==================================================

Objetivo:
Crear API interna para consultar listings.

Crear ruta:

/api/listings/

Puede hacerse con template ProcessWire.

Debe devolver:

{
  "data": [],
  "meta": {
    "total": 0,
    "page": 1,
    "limit": 12
  }
}

Cada item debe incluir:

id
title
url
summary
image
price_from
price_to
capacity_min
capacity_max
city
state
country
lat
lng
categories
features
featured

Filtros soportados:

q
category
city
capacity
price_max
featured
page
limit

No usar Vue todavía.
No usar Axios todavía.

Detenerse al terminar esta fase.

==================================================
FASE 13 - API JSON DETALLE
==================================================

Objetivo:
Crear endpoint individual.

Ruta sugerida:

/api/listings/{id}/

o:

/api/listing/?id=123

Debe devolver toda la información de un listing.

Incluir:

- datos principales
- galería
- categorías
- características
- ubicación
- contacto
- SEO si aplica

Detenerse al terminar esta fase.

==================================================
FASE 14 - SEO BÁSICO
==================================================

Objetivo:
Preparar el sitio para indexación.

Implementar:

- meta title
- meta description
- Open Graph title
- Open Graph description
- Open Graph image
- canonical
- robots.txt
- sitemap básico

En listings individuales usar campos:

listing_meta_title
listing_meta_description
listing_og_image

Detenerse al terminar esta fase.

==================================================
FASE 15 - PRUEBAS ANTES DE VUE
==================================================

Objetivo:
Confirmar que el MVP clásico funciona.

Checklist:

- Frontend carga.
- Admin carga.
- Se pueden crear listings desde admin.
- Existe /listings/.
- Se ven cards.
- Existe detalle individual.
- Filtros GET funcionan.
- API listado funciona.
- API detalle funciona.
- SEO básico funciona.
- No hay errores PHP visibles.
- No hay referencias a site-dir/.
- Git está limpio.

Comandos:

git status

Si todo está bien:

git add .
git commit -m "Add classic listings MVP"

Detenerse al terminar esta fase.

==================================================
FASE 16 - PREPARAR ENTRADA A VUE
==================================================

Objetivo:
Solamente planear Vue.

No implementar todavía.

Vue se usará después para:

- filtros dinámicos
- búsqueda con Axios
- mapa con Leaflet
- cards actualizadas sin recargar
- experiencia tipo buscador inteligente

Antes de Vue deben existir:

- HTML funcional
- CSS funcional
- API JSON funcional
- datos demo
- filtros PHP funcionales
- Git limpio

==================================================
INSTRUCCIÓN FINAL PARA EL AGENTE
==================================================

Ejecuta únicamente la FASE 1.

No avances a la FASE 2 hasta que yo confirme.

Usaremos site/ porque la instalación fue hecha desde cero.

No usar site-dir/.

No usar Vue todavía.

No instalar Laravel.

No instalar Tailwind.

No convertir esto en SPA.

Construir primero el MVP clásico en ProcessWire.