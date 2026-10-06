---
applyTo: '**'
---

# Coding Preferences
- Mantener cambios mínimos y consistentes con shortcodes/JS vanilla del plugin.
- Para flujos CF7, renderizar el modal una sola vez con guard estático y poblar campos ocultos desde JS.

# Project Architecture
- El overlay global del wizard se inyecta desde dualgrid.php en wp_footer.
- includes/shortcodes.php concentra la mayor parte del HTML frontend de shortcodes.
- js/wizard.js controla el cuestionario; js/form.js controla el modal CF7 de tours.
- La referencia operativa del proyecto debe consultarse primero en docs/DUALGRID-ARCHITECTURE.md, docs/DUALGRID-TOUR-CREATION-GUIDE.md y docs/DUALGRID-PLUGIN-EXTENDING-GUIDE.md.
- La galería masonry del sitio pertenece a dualgrid_galeria (includes/shortcodes.php + css/galeria.css + js/galeria.js); la galería del modal de tours permanece separada en css/tour-modal.css + js/tour-modal-detail.js.
- dualgrid_galeria usa attachments + taxonomía dg_gallery_cat; el filtro y el lightbox viven en js/galeria.js y la presentación masonry tipo Pinterest vive en css/galeria.css.
- El admin custom de tours en includes/admin-form.php es la ruta operativa principal para crear/editar tours; el CPT nativo sigue existiendo pero está redirigido por includes/admin-menu.php.
- El campo `Slug del tour` del admin custom es la unión entre el tour real del CPT y el catálogo oficial del Tour Finder en includes/shortcodes.php.
- Las secciones largas del admin de tours usan wp_editor para permitir enlaces y formato básico en When to travel, Can Americans Join, Terms and Physical Demands.
- Si un tour no trae contenido propio en includes/excludes/FAQ/When to travel/Terms/Physical/Americans, el admin puede precargar sugerencias desde docs para que el editor las acepte, quite o amplíe.


## Dualgrid Architecture and Interconnections

## Executive overview

Dualgrid is a WordPress plugin that mixes four major layers:

1. content and admin operations for tours
2. frontend shortcodes and section components
3. modal and AJAX detail rendering
4. Tour Finder Wizard recommendation logic

The most important architectural fact is this:

- tour content is authored as CPT `tour`
- frontend list/detail rendering is mostly assembled in `includes/shortcodes.php`
- rich tour detail data is delivered through `includes/ajax-tour-details.php`
- recommendation logic lives in `js/wizard.js`
- the wizard keeps an official catalog seed, but can now bind to real CPT tours through the exact tour slug

## Bootstrap and plugin loading

Main entry file:

- `dualgrid.php`

Responsibilities of `dualgrid.php`:

- defines plugin constants
- loads all core modules from `includes/`
- enqueues all public CSS and JS
- localizes `geTourModal` for tour detail AJAX
- localizes `dgWizardData` for the Tour Finder Wizard
- injects promo popup markup in the footer
- injects the wizard overlay in the footer

Core modules loaded from `dualgrid.php`:

- `includes/cpt-offers.php`
- `includes/shortcodes.php`
- `includes/ajax-tour-details.php`
- `includes/cpt-tours.php`
- `includes/meta-boxes.php`
- `includes/template-loader.php`
- `includes/admin-menu.php`
- `includes/admin-list.php`
- `includes/admin-form.php`
- `includes/form-handler.php`

## Public asset map

Assets are globally enqueued from `dualgrid.php`, even when a given page may only use a subset.

### CSS files

- `css/global-typography.css`: shared typography tokens and base typography
- `css/tours.css`: general tour cards and list layouts
- `css/faqs.css`: FAQ shortcode styles
- `css/promo-popup.css`: promo popup
- `css/wizard.css`: Tour Finder Wizard UI
- `css/hero-slider.css`: homepage hero slider
- `css/wild-tours.css`: wild tour program cards
- `css/tour-detail.css`: single/archive tour surfaces outside the modal
- `css/galeria.css`: site gallery shortcode with masonry layout
- `css/blog.css`: blog archive and single shortcode UI
- `css/contact.css`: contact section shortcode
- `css/overview.css`: overview shortcode
- `css/mission.css`: mission shortcode
- `css/values.css`: values shortcode
- `css/team.css`: team shortcode
- `css/form.css`: CF7 and custom form visuals
- `css/tour-modal.css`: tour detail modal
- `css/testimonials.css`: testimonials carousel
- `css/admin-tours.css`: admin-only tours UI

### JS files

- `js/languaje.js`: language toggle injection in menu + sticky header state helper
- `js/promo-popup.js`: promo popup lifecycle and rotation
- `js/wizard.js`: Tour Finder Wizard UI and ranking logic
- `js/faqs.js`: FAQ accordion behavior
- `js/hero-slider.js`: hero slider autoplay
- `js/wild-tours.js`: wild tour card reveal animations
- `js/tour-modal.js`: legacy global bridge for modal map compatibility
- `js/galeria.js`: site gallery filtering, reveal animation and lightbox
- `js/animations.js`: global scroll-based reveal orchestration for multiple components
- `js/blog.js`: blog filters, search and animations
- `js/overview.js`: overview reveal animation
- `js/form.js`: CF7 modal open/close + hidden field hydration
- `js/testimonials.js`: testimonial rotation and animated content refresh
- `js/tour-modal-detail.js`: AJAX tour detail modal rendering and interactions
- `js/tours.js`: currently placeholder / effectively unused

## Tour content architecture

### CPT and taxonomy

Main definitions live in:

- `includes/cpt-tours.php`

This file defines:

- post type `tour`
- taxonomy `tour_category`
- default category seed terms for official programs
- legacy metaboxes for the native WP edit screen

Important note:

- the native CPT screen still exists technically
- but `includes/admin-menu.php` redirects creation and edit flows to the custom admin UI in `includes/admin-form.php`

### Custom admin flow

Files:

- `includes/admin-menu.php`
- `includes/admin-list.php`
- `includes/admin-form.php`

Responsibilities:

- `admin-menu.php`: registers custom Tours menu and redirects native edit/new screens
- `admin-list.php`: custom tours table with filters and actions
- `admin-form.php`: primary authoring interface for tour content

The custom admin form is now the operational source of truth for tour creation.

### Tour authoring outputs

The custom tour form populates data used by these surfaces:

- `we_tours` tour cards
- tour detail modal
- single tour page
- Tour Finder Wizard ranking dataset

## Tour frontend surfaces

### Tour lists by category

Primary shortcode:

- `we_tours`

Code location:

- `includes/shortcodes.php`

Related files:

- CSS: `css/tours.css`, `css/tour-detail.css`, `css/tour-modal.css`
- JS: `js/form.js`, `js/tour-modal-detail.js`, `js/animations.js`

What it does:

- queries posts of type `tour`
- filters by taxonomy `tour_category`
- renders cards with title, excerpt, image and key metadata
- injects the detail modal container
- injects request and booking CF7 modals

### Single tour page

Template:

- `templates/single-tour.php`

Loader:

- `includes/template-loader.php`

Dependencies:

- meta values stored from `includes/admin-form.php`
- `css/tour-detail.css`
- optional modal/form helpers from `js/form.js`

What it shows:

- hero image
- title and subtitle
- quick meta bar
- main content body from WP editor
- optional PDF CTA
- inquiry form area
- related tours

### Tour archive and taxonomy pages

Template:

- `templates/archive-tour.php`

Loader:

- `includes/template-loader.php`

Dependencies:

- category term meta stored in `includes/cpt-tours.php`
- tour cards from CPT data
- `css/tour-detail.css`

## Tour detail modal architecture

### Rendering chain

1. HTML shell is printed by `dg_render_tour_modal()` in `includes/shortcodes.php`
2. card button `.we-btn-details` is clicked
3. `js/tour-modal-detail.js` sends AJAX request to `we_get_tour_details`
4. `includes/ajax-tour-details.php` builds the response payload
5. `js/tour-modal-detail.js` fills the modal sections, resets accordions and opens the overlay

### Files involved

- HTML shell: `includes/shortcodes.php`
- AJAX payload: `includes/ajax-tour-details.php`
- render logic: `js/tour-modal-detail.js`
- styles: `css/tour-modal.css`
- form bridges for inquiry/booking: `js/form.js`

### Modal sections and their sources

- Header: title, short description, coords, quick attributes
- Gallery: featured image + `tour_gallery`
- About: `tour_about`
- Wildlife: `tour_wildlife` + `tour_wildlife_label`
- Organizer: `tour_organizer_*`
- Guides: `tour_guides`
- Itinerary: `tour_itinerary`
- Locations: `tour_locations` + `tour_map_custom_url`
- Availability: `tour_availability`
- Pricing: `tour_price`, `tour_price_secondary`, `tour_pricing`
- Transport: `tour_transport`, `tour_accommodation`, `tour_pace`
- Physical demands: `tour_physical_demands`
- Essentials final stack:
  - `tour_includes`
  - `tour_excludes`
  - `tour_when_to_travel`
  - `tour_can_americans_join`
  - `tour_terms_conditions`
  - `tour_faq`

### Global docs fallback system

The modal uses fallback content from `docs/` when a tour does not provide some final informational sections.

Source file:

- `includes/ajax-tour-details.php`

Current fallback docs used:

- `docs/When to Travel.md`
- `docs/Rules, Terms & Conditions.md`
- `docs/What’s Included.md`
- `docs/❓ Frequently Asked Questions.md`

This fallback currently applies to:

- When to travel
- Rules / terms
- Includes / excludes
- FAQ

## Tour Finder Wizard architecture

### Files involved

- dataset builder: `includes/shortcodes.php`
- data localization: `dualgrid.php`
- UI and ranking logic: `js/wizard.js`
- styles: `css/wizard.css`

### Core data builders

Defined in `includes/shortcodes.php`:

- `dg_get_tour_finder_main_focus_options()`
- `dg_get_tour_finder_region_options()`
- `dg_get_tour_finder_duration_options()`
- `dg_get_tour_finder_status_options()`
- `dg_get_tour_finder_program_overrides()`
- `dg_get_tour_finder_tour_overrides()`
- `dg_get_tour_finder_official_catalog_seed()`
- `dg_get_tour_finder_tour_record()`
- `dg_get_tour_finder_catalog()`
- `dg_get_tour_finder_programs()`
- `dg_get_tour_finder_wizard_data()`

### How the wizard really works

The wizard is not purely dynamic.

It combines two layers:

1. an official seed catalog hardcoded in PHP
2. optional replacement from real CPT tours when the slug matches exactly

That means:

- the ranking logic remains stable and curated
- the visual/output layer can still use your real tour content

### Exact rule for real tour takeover

A real CPT tour replaces the seed record when:

- its post slug equals one of the official slugs from `dg_get_tour_finder_official_catalog_seed()`

When that happens, the wizard can use the real tour’s:

- featured image
- permalink
- title
- description
- ranking metadata

### Wizard ranking inputs

The wizard consumes these fields from the admin form:

- `tour_main_focus`
- `tour_regions`
- `tour_duration_band`
- `tour_status`

These values do not directly change modal rendering.
They only change how the wizard scores and sorts tours.

### Why `Tour Finder Data` exists in the admin

That admin block exists because the wizard needs structured metadata that cannot be inferred reliably from card copy or long descriptions.

Meaning of each field:

- Main Focus: what type of expedition the tour primarily serves
- Region: Cuba / Western Cuba / Eastern Cuba
- Duration: short / mid-range / long classification
- Availability Status: light ranking signal shown to the wizard

## Shortcode to asset interconnection map

### `tour_cards`

- PHP: `includes/shortcodes.php`
- CSS: `css/tours.css`
- JS: `js/animations.js`
- Data source: `dg_get_tour_finder_program_overrides()`

### `dualgrid_faqs` and `dualgrid_faq`

- PHP: `includes/shortcodes.php`
- CSS: `css/faqs.css`
- JS: `js/faqs.js`, `js/animations.js`
- Data source: shortcode attributes and nested content

### `testimonials_carousel`

- PHP: `includes/shortcodes.php`
- CSS: `css/testimonials.css`
- JS: `js/testimonials.js`, `js/animations.js`
- Data source: CPT `testimonial`

### `tour_wizard`

- PHP: `includes/shortcodes.php`
- CSS: `css/wizard.css`
- JS: `js/wizard.js`
- Data source: `dg_get_tour_finder_wizard_data()` localized in `dualgrid.php`

### `hero_slider`

- PHP: `includes/shortcodes.php`
- CSS: `css/hero-slider.css`
- JS: `js/hero-slider.js`
- Data source: shortcode attributes + `dg_get_tour_finder_program_overrides()` defaults

### `wild_tour`

- PHP: `includes/shortcodes.php`
- CSS: `css/wild-tours.css`
- JS: `js/wild-tours.js`
- Data source: shortcode attributes

### `wild_tours_container`

- PHP: `includes/shortcodes.php`
- CSS: `css/wild-tours.css`, `css/tours.css`
- JS: `js/animations.js`
- Data source: `dg_get_tour_finder_program_overrides()`

### `we_tour_modal_form`

- PHP: `includes/shortcodes.php`
- CSS: `css/form.css`
- JS: `js/form.js`
- Dependency: Contact Form 7

### `tour_booking_form`

- PHP: `includes/shortcodes.php`
- CSS: `css/form.css`
- JS: `js/form.js`
- Dependency: Contact Form 7

### `tailor_made_form`

- PHP: `includes/shortcodes.php`
- CSS: `css/form.css`
- JS: `js/form.js`
- Dependency: Contact Form 7

### `we_tours`

- PHP: `includes/shortcodes.php`
- CSS: `css/tours.css`, `css/tour-modal.css`, `css/form.css`
- JS: `js/tour-modal-detail.js`, `js/form.js`, `js/animations.js`
- Data source: CPT `tour` + taxonomy `tour_category`

### `dualgrid_galeria`

- PHP: `includes/shortcodes.php`
- CSS: `css/galeria.css`
- JS: `js/galeria.js`, `js/animations.js`
- Data source: WP attachments + taxonomy `dg_gallery_cat`

### `dualgrid_blog`

- PHP: `includes/shortcodes.php`
- CSS: `css/blog.css`
- JS: `js/blog.js`
- Data source: posts and categories

### `dualgrid_single`

- PHP: `includes/shortcodes.php`
- CSS: `css/blog.css`
- JS: `js/blog.js`
- Data source: current post context

### `we_contact_section`

- PHP: `includes/shortcodes.php`
- CSS: `css/contact.css`, `css/form.css`
- JS: no dedicated logic beyond CF7 behavior
- Dependency: Contact Form 7

### `we_overview_section`

- PHP: `includes/shortcodes.php`
- CSS: `css/overview.css`
- JS: `js/overview.js`
- Data source: shortcode attributes

### `mission_section`

- PHP: `includes/shortcodes.php`
- CSS: `css/mission.css`
- JS: `js/animations.js`
- Data source: shortcode attributes

### `values_section`

- PHP: `includes/shortcodes.php`
- CSS: `css/values.css`
- JS: `js/animations.js`
- Data source: internal array + shortcode heading attributes

### `team_section`

- PHP: `includes/shortcodes.php`
- CSS: `css/team.css`
- JS: `js/animations.js`
- Data source: shortcode attributes

## Cross-cutting modules

### `js/form.js`

This file is a bridge layer between tour-related UI and Contact Form 7.

It is responsible for:

- opening inquiry modal
- opening booking modal
- opening tailor-made modal
- injecting hidden metadata into forms
- keeping page URL and tour ID context

### `js/animations.js`

This file is a shared reveal system.

It is used by:

- FAQ blocks
- testimonials
- tour cards
- gallery items
- mission
- values
- team

### `js/languaje.js`

This is not shortcode-specific.

It patches the language menu and tracks header sticky state.

### `js/tours.js`

Currently it is effectively empty and does not control a live frontend feature.

## Legacy versus current authoring path

There are two historical paths in the codebase for tours:

1. native metabox-based authoring in `includes/cpt-tours.php` and `includes/meta-boxes.php`
2. custom admin workflow in `includes/admin-form.php`

Current operational path:

- custom admin workflow

Legacy path still matters because:

- some helper code and fallbacks still read old meta conventions
- some documentation or code comments may still reference metabox terminology

## Default docs folder as editorial support

The `docs/` folder is not just reference material.

It is also a fallback content source for tour detail essentials.

That means editorial docs influence runtime behavior in the modal.

## Debugging checklist by symptom

### If a tour card is missing content

Check:

- `includes/admin-form.php`
- `includes/shortcodes.php`
- category assignment in `tour_category`

### If the modal opens but is empty

Check:

- AJAX route in `includes/ajax-tour-details.php`
- localized object `geTourModal` in `dualgrid.php`
- render logic in `js/tour-modal-detail.js`

### If the wizard shows the program image instead of the real tour image

Check:

- the tour slug in the admin form
- whether that slug exists in the official seed catalog
- whether the tour has a featured image

### If Tour Finder Data seems wrong in admin

Check:

- `includes/admin-form.php`
- especially hidden `enabled=0` inputs for `tour_main_focus`

### If gallery layout is wrong

Check:

- site gallery: `css/galeria.css`
- modal gallery: `css/tour-modal.css`

These are intentionally separate now.

## Documentation set inside `docs/`

Key project guides:

- `docs/DUALGRID-TOUR-CREATION-GUIDE.md`
- `docs/DUALGRID-ARCHITECTURE.md`
- `docs/DUALGRID-PLUGIN-EXTENDING-GUIDE.md`

These should be treated as the operational memory of the plugin.

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


# Solutions Repository
- El shortcode tour_wizard estaba duplicado entre dualgrid.php e includes/shortcodes.php; mantener una sola fuente de verdad para evitar overrides silenciosos.
- El modal de detalle debe mostrar FAQ/When to travel/Rules/What is included al final dentro de Essentials usando fallback desde docs cuando falte meta por tour.
- El pricing del modal se construye con tour_price + repeater tour_pricing; si price_secondary no viene en repeater, se agrega como fila auxiliar para no perder datos.
- El campo Slug del tour en el admin custom es la llave de unión entre los tours reales del CPT y el catálogo oficial del Tour Finder; si el slug coincide con uno oficial, el wizard usa foto, link y metadatos reales del tour.
- Para el wizard, limpiar títulos y descripciones con html_entity_decode y quitar prefijos tipo `##` evita que aparezcan entidades como `&#8211;` o headings importados desde markdown.
- Si se quiere masonry con reveal por filas en dualgrid_galeria, usar CSS grid + cálculo de `grid-row-end` en js/galeria.js; `column-count` ordena visualmente por columnas y no sirve para ese efecto.
- En la galería del sitio, el lightbox debe vaciar la imagen anterior y precargar la nueva antes de mostrarla para evitar el retraso visual entre selecciones.
- En la galería del sitio, el lightbox no debe dejar visible la imagen anterior mientras carga la siguiente; js/galeria.js debe limpiar `src` y precargar la nueva imagen antes de mostrarla.
- El script inline del admin form es frágil frente a texto corrupto o palabras partidas dentro del bloque `<script>`; cuando las pestañas dejan de abrir, revisar primero `document.querySelectorAll('.we-admin-tab')` en includes/admin-form.php.



# Dualgrid Plugin Extending Guide

## Purpose

This guide explains how to add new shortcodes, new CSS/JS modules, new tour fields, and new frontend features to the plugin without breaking the existing architecture.

## Golden rules

1. Keep new features aligned with the current file split.
2. Reuse existing patterns before introducing a second way to solve the same problem.
3. If the feature is tour-related, decide first whether it belongs to:
   - card
   - modal
   - single page
   - wizard ranking
   - admin authoring
4. If a new feature needs data, trace the full chain before coding:
   - admin input
   - post meta storage
   - PHP read path
   - JS render path
   - CSS styling

## How to add a new shortcode

Shortcodes live in:

- `includes/shortcodes.php`

### Recommended process

1. Define the shortcode in `includes/shortcodes.php`.
2. Decide if it needs dedicated CSS.
3. Decide if it needs dedicated JS.
4. If yes, add those files under `css/` and `js/`.
5. Enqueue them from `dualgrid.php`.
6. If the shortcode needs server data, keep data assembly in PHP and render in JS only when interaction is needed.

### Minimal shortcode pattern

```php
add_shortcode('my_shortcode', function ($atts, $content = null) {
    $atts = shortcode_atts([
        'title' => 'Default title',
    ], $atts, 'my_shortcode');

    ob_start();
    ?>
    <section class="my-shortcode">
        <h2><?php echo esc_html($atts['title']); ?></h2>
        <?php if ($content !== null): ?>
            <div class="my-shortcode__content"><?php echo do_shortcode($content); ?></div>
        <?php endif; ?>
    </section>
    <?php
    return ob_get_clean();
});
```

## How to add CSS for a new shortcode

1. Create `css/my-shortcode.css`.
2. Enqueue it in `dualgrid.php` with the existing version constant.
3. Prefer unique class prefixes to avoid collisions.

Example:

```php
wp_enqueue_style('dualgrid-my-shortcode', DUALGRID_PLUGIN_URL . 'css/my-shortcode.css', [], DUALGRID_VERSION);
```

## How to add JS for a new shortcode

1. Create `js/my-shortcode.js`.
2. Keep it wrapped in an IIFE.
3. Guard against missing DOM nodes.
4. Enqueue it in `dualgrid.php`.

Example:

```js
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var el = document.querySelector('.my-shortcode');
        if (!el) {
            return;
        }

        // behavior here
    });
})();
```

## How to add a new field to tours

If a new feature belongs to the tour content model, update all affected layers.

### Required touchpoints

1. `includes/admin-form.php`
   - add field to defaults
   - add UI field
   - add save logic

2. `includes/ajax-tour-details.php`
   - add field to the AJAX payload if the modal needs it

3. `templates/single-tour.php`
   - add rendering if the single page needs it

4. `includes/shortcodes.php`
   - add rendering if a card or list surface needs it

5. `js/tour-modal-detail.js`
   - add render logic if the modal is dynamic

6. `css/tour-modal.css` or another stylesheet
   - add presentation

### Example: new long-text field for the modal

If adding `tour_example_notes`:

1. Add to `$simple` or appropriate defaults in `admin-form.php`
2. Add save logic using `wp_kses_post` if links are allowed
3. Add to AJAX payload in `ajax-tour-details.php`
4. Add container markup in `dg_render_tour_modal()`
5. Render it in `js/tour-modal-detail.js`
6. Style it in `css/tour-modal.css`

## When to use `sanitize_text_field`, `sanitize_textarea_field`, or `wp_kses_post`

- `sanitize_text_field`: short plain text
- `sanitize_textarea_field`: plain multiline text without HTML
- `wp_kses_post`: long text that may include safe HTML links or formatting

If a field should allow links, prefer `wp_kses_post` and render it carefully.

## How to extend the tour modal

The modal uses a three-part chain:

1. structure in `includes/shortcodes.php`
2. data in `includes/ajax-tour-details.php`
3. rendering in `js/tour-modal-detail.js`

If one part is missing, the feature is incomplete.

### Modal extension checklist

1. Add HTML placeholder in `dg_render_tour_modal()`
2. Add field to AJAX payload
3. Read payload in `renderTourModal()`
4. Toggle visibility with `setSectionVisibility()` or `setElementVisibility()`
5. Add matching CSS

## How to extend the Tour Finder Wizard

The wizard uses curated logic. Do not treat it like a generic search.

### Files involved

- `includes/shortcodes.php`
- `js/wizard.js`
- `css/wizard.css`
- localized data from `dualgrid.php`

### If you want to add a new official wizard tour

1. Add it to `dg_get_tour_finder_official_catalog_seed()`
2. Add any override to `dg_get_tour_finder_tour_overrides()` if needed
3. Create the real CPT tour from admin
4. Give that real tour the exact same slug
5. Fill in `Tour Finder Data`

### If you want the wizard to consider a new ranking signal

1. Add the structured value in PHP data builders
2. Localize it through `dg_get_tour_finder_wizard_data()`
3. Update `js/wizard.js` scoring logic
4. Update any admin field that authors that signal
5. Update the docs

## How to add a new site gallery behavior

The site gallery is separate from the tour modal gallery.

Files:

- shortcode markup: `includes/shortcodes.php` under `dualgrid_galeria`
- styles: `css/galeria.css`
- behavior: `js/galeria.js`

Do not apply site gallery layout changes in `css/tour-modal.css`.

## How to add a new CF7-powered modal

Pattern to reuse:

- markup in `includes/shortcodes.php`
- open/close and hidden field hydration in `js/form.js`

Recommended flow:

1. create render function similar to `dg_render_tour_request_modal()`
2. create shortcode wrapper if needed
3. ensure unique modal ID
4. add opener function in `js/form.js`
5. add hidden field population logic if context matters

## How to debug a new feature safely

### If it is PHP-rendered

Check:

- is the shortcode registered?
- is the markup printed?
- is the field saved in post meta?

### If it is JS-driven

Check:

- is the script enqueued in `dualgrid.php`?
- is the DOM element present?
- is the data localized or present in data attributes?

### If it is admin-driven

Check:

- defaults loaded correctly
- save logic handles the field
- validation does not wipe the field
- the form script is still valid

## Documentation discipline

Whenever adding a new feature, update at least one of these:

- `docs/DUALGRID-ARCHITECTURE.md`
- `docs/DUALGRID-TOUR-CREATION-GUIDE.md`
- `docs/DUALGRID-PLUGIN-EXTENDING-GUIDE.md`

## Recommended workflow for future changes

1. Identify which surface changes: admin, shortcode, modal, template, wizard, gallery, or form.
2. Read the owning file first.
3. Trace the data path end-to-end.
4. Make the smallest change that closes the full loop.
5. Validate with editor errors and, when possible, JS syntax checks.
6. Record the architecture decision in docs if it changes behavior.
