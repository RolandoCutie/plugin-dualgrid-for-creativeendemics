# Dualgrid Architecture and Interconnections

## Resumen

Dualgrid es un plugin WordPress que combina:

- shortcodes frontend
- CPT de tours y ofertas
- admin custom para tours
- modal AJAX de detalles
- Tour Finder Wizard con ranking en JS
- templates propios para archivo y single de tours

## Punto de entrada principal

Archivo:

- `dualgrid.php`

Responsabilidades:

- define constantes del plugin
- carga archivos de `includes/`
- encola CSS y JS
- localiza datos del modal y del wizard
- inyecta popup promo y overlay del wizard

## Módulos cargados desde `dualgrid.php`

- `includes/cpt-offers.php`: CPT de ofertas
- `includes/shortcodes.php`: shortcodes frontend y lógica del Tour Finder
- `includes/ajax-tour-details.php`: endpoint AJAX del modal de tour
- `includes/cpt-tours.php`: CPT de tours y metaboxes legacy
- `includes/meta-boxes.php`: soporte legacy adicional
- `includes/template-loader.php`: fuerza templates custom del plugin
- `includes/admin-menu.php`: menú custom de Tours
- `includes/admin-list.php`: lista admin de tours
- `includes/admin-form.php`: formulario custom de crear/editar tours
- `includes/form-handler.php`: helpers de formularios y notice legacy

## Flujo de tours

### 1. Creación y edición

Pantallas:

- `includes/admin-menu.php`
- `includes/admin-list.php`
- `includes/admin-form.php`

El usuario crea un post type `tour` y guarda metadatos como:

- precios
- duración
- descripción
- galería
- itinerary
- includes / excludes
- FAQ
- Tour Finder Data

### 2. Listados frontend

Shortcode principal:

- `we_tours`

Archivo:

- `includes/shortcodes.php`

Este shortcode:

- consulta posts de tipo `tour`
- filtra por taxonomía `tour_category`
- muestra cards
- inyecta el modal de detalle y modales CF7 relacionados

### 3. Modal de detalle del tour

Piezas:

- HTML base: `includes/shortcodes.php`
- datos: `includes/ajax-tour-details.php`
- render JS: `js/tour-modal-detail.js`
- estilos: `css/tour-modal.css`

Flujo:

1. el usuario hace click en `View details`
2. JS llama a `we_get_tour_details`
3. PHP arma un payload completo del tour
4. JS rellena secciones del modal

El modal ya soporta:

- header con atributos
- galería
- about
- wildlife
- organizer / guides
- itinerary
- locations + Google Maps
- availability + price breakdown
- transport / accommodation / pace
- essentials finales:
  - what is included
  - when to travel
  - can americans join
  - rules / terms
  - FAQ

### 4. Página individual del tour

Template:

- `templates/single-tour.php`

Ese template muestra:

- hero
- subtítulo
- meta rápidos
- contenido principal
- botón PDF
- formulario de información lateral
- relacionados

### 5. Archivo y taxonomía de tours

Template:

- `templates/archive-tour.php`

Soporta:

- hero de categoría
- overview de categoría
- cards de tours

## Tour Finder Wizard

Archivos clave:

- datos y helpers: `includes/shortcodes.php`
- localización JS: `dualgrid.php`
- lógica de ranking: `js/wizard.js`

### Dataset del wizard

Se expone con:

- `dg_get_tour_finder_wizard_data()`

Incluye:

- programas
- tours
- opciones de main focus
- opciones de region
- opciones de duración
- opciones de status

### Ranking del wizard

La lógica vive en `js/wizard.js`.

Usa:

- `mainFocus`
- `regions`
- `duration`
- `status`
- pesos por prioridad

### Catálogo oficial

El Finder mantiene un catálogo semilla oficial en:

- `dg_get_tour_finder_official_catalog_seed()`

Pero ahora puede reemplazar esa información con un tour real del CPT cuando:

- el slug del post `tour` coincide exactamente con uno de los slugs oficiales

### Qué significa eso para operación

- no hace falta tocar JS para cada tour nuevo oficial
- sí hace falta que el slug coincida
- sí hace falta rellenar `Tour Finder Data`

## Shortcodes activos

Definidos en `includes/shortcodes.php`:

- `tour_cards`: grid de programas destacados
- `dualgrid_faqs`: contenedor FAQ
- `dualgrid_faq`: item FAQ
- `testimonials_carousel`: carrusel de testimonios
- `tour_wizard`: botón del wizard
- `hero_slider`: hero principal
- `wild_tour`: tarjeta de programa individual
- `wild_tours_container`: contenedor de programas
- `we_tour_modal_form`: modal de inquiry CF7
- `tour_booking_form`: modal de booking CF7
- `tailor_made_form`: modal tailor-made CF7
- `we_tours`: listado de tours por categoría
- `dualgrid_galeria`: galería del sitio con filtros y lightbox
- `dualgrid_blog`: listado de blog
- `dualgrid_single`: single de blog vía shortcode
- `we_contact_section`: sección de contacto con CF7
- `we_overview_section`: sección overview visual
- `mission_section`: bloque de misión
- `values_section`: bloque de valores
- `team_section`: bloque de equipo

## Galerías

### Galería del modal

Archivos:

- HTML en `includes/shortcodes.php`
- JS en `js/tour-modal-detail.js`
- CSS en `css/tour-modal.css`

Usa una grilla visual fija.

### Galería del sitio

Shortcode:

- `dualgrid_galeria`

Archivos:

- `includes/shortcodes.php`
- `css/galeria.css`
- `js/galeria.js`

Usa filtros por categoría, lightbox y ahora layout masonry tipo Pinterest.

## Formularios

### Inquiry modal y Booking modal

Se imprimen desde `includes/shortcodes.php`.

Dependen de Contact Form 7.

### Tailor-made modal

También depende de Contact Form 7 y comparte infraestructura visual.

## Defaults globales desde `docs/`

El modal puede tomar contenido global desde la carpeta `docs/` cuando un tour no tiene datos específicos.

Actualmente se usan como fallback:

- `When to Travel.md`
- `Rules, Terms & Conditions.md`
- `What’s Included.md`
- `❓ Frequently Asked Questions.md`

Eso se resuelve en:

- `includes/ajax-tour-details.php`

## Reglas operativas importantes

1. El tour real debe tener imagen destacada para que el wizard y las cards se vean bien.
2. Si el tour debe aparecer correctamente en el wizard oficial, debe usar un slug oficial exacto.
3. `Tour Finder Data` sí se usa y sí afecta el ranking.
4. Las categorías de `tour_category` controlan listados y breadcrumbs, pero no sustituyen el ranking del wizard.
5. Los textos finales largos del modal aceptan enlaces.

## Archivos clave que conviene revisar primero cuando algo falle

### Si falla el admin de tours

- `includes/admin-form.php`
- `includes/admin-menu.php`
- `includes/admin-list.php`

### Si falla el modal de detalles

- `includes/shortcodes.php`
- `includes/ajax-tour-details.php`
- `js/tour-modal-detail.js`
- `css/tour-modal.css`

### Si falla el Tour Finder

- `dualgrid.php`
- `includes/shortcodes.php`
- `js/wizard.js`

### Si falla la galería del sitio

- `includes/shortcodes.php`
- `css/galeria.css`
- `js/galeria.js`

## Recordatorio práctico

Cuando trabajes con tours, la interconexión real es esta:

`admin-form.php` → guarda post `tour` + meta

`we_tours` / `single-tour.php` / `ajax-tour-details.php` → leen esos datos

`tour-modal-detail.js` / `wizard.js` → convierten esos datos en experiencia frontend

El Tour Finder no deja de tener lógica propia, pero ahora puede usar tus tours reales como fuente visual y semántica si respetas el slug oficial.