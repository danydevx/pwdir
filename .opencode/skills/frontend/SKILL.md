# Frontend Skill - ProcessWire Listings

## Objetivo

Construir interfaces rápidas, mantenibles, SEO-friendly y fáciles de desplegar para el MVP de Listings.

El frontend debe priorizar:

* Velocidad de desarrollo
* Simplicidad
* SEO
* Mantenibilidad
* Reutilización
* Bajo acoplamiento
* Progresive enhancement

Evitar sobreingeniería.

---

# Stack del MVP Clásico

Primera etapa:

* ProcessWire Templates PHP
* Bootstrap 5
* LESS
* BEM
* JavaScript nativo cuando sea necesario

No usar todavía:

* Vue
* Axios
* Leaflet
* Pinia
* Vue Router
* SPA completa

---

# Stack de Etapa Interactiva

Después de terminar el MVP clásico se podrá usar:

* Vue 3
* Axios
* Leaflet

Solamente para partes interactivas:

* Buscador avanzado
* Filtros dinámicos
* Mapa
* Resultados actualizados sin recargar
* Search Wizard

---

# No Usar

* Tailwind
* Nuxt
* Pinia
* Vue Router
* Vuetify
* Quasar
* Element Plus
* BootstrapVue
* Frameworks UI complejos
* SPA completa

---

# Filosofía

ProcessWire sigue siendo dueño del sitio.

Correcto:

```text
ProcessWire
 ├─ SEO
 ├─ Routing
 ├─ Templates PHP
 ├─ Listings
 ├─ Página detalle
 └─ API JSON

JavaScript / Vue
 ├─ Buscador
 ├─ Filtros
 ├─ Mapa
 └─ Resultados dinámicos
```

Incorrecto:

```text
Vue SPA completa
+
ProcessWire únicamente como API
```

---

# Regla Principal

Antes de Vue debe existir una versión funcional en PHP clásico.

Debe funcionar:

```text
/listings/
/listings/nombre-del-listing/
```

con:

* HTML renderizado por ProcessWire
* CSS funcional
* Filtros GET básicos
* Cards visibles
* Detalle individual
* SEO básico
* API JSON lista para usarse después

---

# Convención de Nombres

Todo debe ser genérico.

Correcto:

```text
listing-card
listing-filters
listing-detail
listing-map
listing-results
listing-gallery
```

Evitar:

```text
terrace-card
terrace-map
terrace-filters
```

La categoría puede ser terrazas.

Los componentes no.

---

# Estructura de Templates PHP

Usar:

```text
/site/templates/
```

Archivos principales:

```text
home.php
basic_page.php
listings.php
listing.php
```

Parciales:

```text
/site/templates/partials/
    header.php
    footer.php
    nav.php
    listing-card.php
    listing-filters.php
```

Layouts:

```text
/site/templates/layouts/
    main.php
```

Assets:

```text
/site/templates/assets/
    css/
    js/
    less/
    img/
```

---

# Estructura LESS

```text
/site/templates/assets/less/
    main.less
    variables.less
    mixins.less

    base/
    layout/
    components/
    pages/
```

Compilar a:

```text
/site/templates/assets/css/main.css
```

---

# Bootstrap

Bootstrap 5 es el sistema principal de layout.

Usar:

```text
container
container-fluid
row
col
card
btn
modal
offcanvas
dropdown
badge
pagination
form-control
form-select
```

No reinventar componentes que Bootstrap ya tiene.

---

# BEM

Usar BEM para componentes propios.

Correcto:

```html
<div class="listing-card">
    <div class="listing-card__image"></div>
    <div class="listing-card__body"></div>
    <div class="listing-card__actions"></div>
</div>
```

LESS:

```less
.listing-card {
    &__image {}

    &__body {}

    &__actions {}
}
```

Evitar:

```html
<div class="card2">
<div class="card-title2">
```

---

# LESS

Todos los estilos personalizados deben estar en LESS.

Evitar CSS inline.

Incorrecto:

```html
<div style="margin-top:20px">
```

Correcto:

```html
<div class="listing-card">
```

---

# Variables LESS

Centralizar valores.

Ejemplo:

```less
@primary: #0d6efd;
@success: #198754;
@danger: #dc3545;

@border-radius: 12px;
@card-radius: 18px;
```

No repetir valores mágicos.

---

# Página de Listado

Ruta:

```text
/listings/
```

Archivo:

```text
/site/templates/listings.php
```

Debe incluir:

* Header
* Hero simple
* Filtros GET básicos
* Grid de cards
* Paginación simple
* Footer

Cada card debe mostrar:

* Imagen
* Título
* Resumen corto
* Ubicación
* Capacidad
* Precio desde
* Categorías
* Features principales
* Badge verificado
* Badge destacado
* Botón WhatsApp
* Botón Ver detalle

---

# Filtros GET

Antes de Vue usar filtros tradicionales por query string.

Ejemplo:

```text
/listings/?q=jardin&category=terrace&location=zapopan&capacity=100&price_max=8000
```

Filtros iniciales:

```text
q
category
location
capacity
price_max
featured
```

Los filtros deben funcionar sin JavaScript.

---

# Página Detalle

Ruta:

```text
/listings/nombre-del-listing/
```

Archivo:

```text
/site/templates/listing.php
```

Debe mostrar:

* Header
* Imagen principal
* Galería
* Título
* Resumen
* Descripción
* Precio
* Capacidad
* Dirección
* Ubicación
* Categorías
* Features
* Botón WhatsApp
* Botón email
* Footer

Esta página debe ser totalmente renderizada por ProcessWire.

---

# WhatsApp

Todas las cards y fichas deben tener acceso rápido.

Formato:

```text
https://wa.me/
```

Mensaje sugerido:

```text
Hola, quiero información sobre {listing_title}
```

No hardcodear nombres específicos de nicho.

---

# Badges

Estados mínimos:

```text
Verificado
Destacado
Premium
```

Bootstrap:

```html
<span class="badge bg-success">Verificado</span>
<span class="badge bg-warning text-dark">Destacado</span>
<span class="badge bg-primary">Premium</span>
```

---

# Accesibilidad

Usar siempre cuando aplique:

```text
alt
aria-label
label
for
button type
```

Las imágenes deben tener `alt`.

Los inputs deben tener `label`.

Los botones de icono deben tener `aria-label`.

---

# SEO

Vue no debe afectar SEO.

Las fichas individuales son responsabilidad de ProcessWire.

Correcto:

```text
/listings/example-listing/
```

renderizado por ProcessWire.

Cada página debe tener:

* title
* meta description
* canonical
* Open Graph básico

---

# JavaScript Nativo

En MVP clásico usar JavaScript nativo solo para detalles pequeños:

* abrir/cerrar UI simple
* mejorar UX de filtros
* previews pequeños
* interacción no crítica

El sitio debe seguir funcionando sin JavaScript.

---

# API

La API se prepara antes de Vue, pero el frontend clásico no debe depender de ella para renderizar contenido principal.

Correcto:

```text
ProcessWire renderiza HTML
API disponible para Vue futuro
```

Incorrecto:

```text
Página vacía
Vue consume API
Renderiza todo
```

---

# Vue 3

Vue entra después del MVP clásico.

Estructura sugerida:

```text
/site/templates/assets/js/
    app.js

    components/
    listings/
        ListingCard.vue
        ListingFilters.vue
        ListingMap.vue
        ListingResults.vue
        ListingQuickView.vue
        SearchWizard.vue

    composables/
    services/
        api.js

    utils/
```

---

# Componentes Vue

Los componentes deben ser pequeños.

Correcto:

```text
ListingCard
```

Muestra una card.

```text
ListingMap
```

Muestra el mapa.

```text
ListingFilters
```

Muestra filtros.

Incorrecto:

```text
ListingsPage.vue
```

con miles de líneas y toda la lógica mezclada.

---

# Props Vue

Siempre preferir props.

Correcto:

```javascript
defineProps({
    listing: Object
})
```

Evitar:

```javascript
window.listing
```

salvo para configuración inicial mínima.

---

# Estado Vue

Para el MVP interactivo usar:

```javascript
ref()
reactive()
computed()
```

No usar:

```text
Pinia
Vuex
```

hasta que exista una necesidad real.

---

# Axios

Centralizar llamadas.

Crear:

```text
/site/templates/assets/js/services/api.js
```

Ejemplo:

```javascript
import axios from 'axios'

export default axios.create({
    baseURL: '/api'
})
```

Correcto:

```javascript
api.get('/listings')
```

Evitar repetir:

```javascript
axios.get(...)
axios.get(...)
axios.get(...)
```

en todos los componentes.

---

# Leaflet

Usar Leaflet para mapas en la etapa interactiva.

No usar Google Maps en el MVP.

Razones:

* Gratuito
* Sin API Keys
* Fácil despliegue
* Suficiente para búsqueda local

---

# Search Wizard

El wizard debe ser paso a paso.

Ejemplo:

```text
Paso 1
¿Qué estás buscando?

Paso 2
¿Cuántas personas o qué capacidad necesitas?

Paso 3
¿Dónde buscas?

Paso 4
¿Cuál es tu presupuesto?

Paso 5
¿Qué características necesitas?
```

Cada paso debe ser independiente.

No debe romper el funcionamiento clásico del listado.

---

# Performance

Evitar:

```text
Dependencias grandes
Frameworks adicionales
Plugins innecesarios
Renderizado completo desde JS
```

Preferir:

```text
HTML renderizado por ProcessWire
Bootstrap
LESS
JavaScript simple
Vue por islas interactivas
```

---

# Regla de Dependencias

Antes de instalar una nueva librería preguntar:

```text
¿Bootstrap, Vue o JavaScript nativo ya resuelven esto?
```

Si la respuesta es sí:

No instalar dependencia nueva.

---

# Criterio de Éxito

El frontend es correcto si:

* Funciona sin Vue.
* Funciona sin JavaScript para lo esencial.
* Es rápido.
* Es mantenible.
* Es fácil de desplegar.
* Tiene pocas dependencias.
* Tiene buen SEO.
* Las cards cargan desde ProcessWire.
* Los filtros GET funcionan.
* El detalle individual es indexable.
* El usuario puede contactar por WhatsApp en pocos clics.
* Puede evolucionar a Vue sin rehacer la base.
