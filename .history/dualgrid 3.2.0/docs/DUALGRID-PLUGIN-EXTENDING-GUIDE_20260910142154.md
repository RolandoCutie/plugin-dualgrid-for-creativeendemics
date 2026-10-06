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
