# Dualgrid CreativeWilds Extending Guide

## Purpose

This plugin is now a focused landing builder for CreativeWilds. Extend it by following the new data flow instead of reintroducing the previous large plugin structure.

## Core files

Start from these files:

- [dualgrid.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/dualgrid.php)
- [creativewilds-data.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-data.php)
- [creativewilds-shortcodes.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-shortcodes.php)
- [creativewilds-admin.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-admin.php)
- [creativewilds-contact.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-contact.php)
- [creativewilds.css](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/css/creativewilds.css)
- [creativewilds.js](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/js/creativewilds.js)

## Golden rules

1. Keep the plugin centered on the CreativeWilds landing.
2. Reuse the shared option `dg_cw_settings`.
3. Store images as attachment IDs, not raw URLs.
4. Add EN and ES values together.
5. Keep interaction in vanilla JS and markup in PHP.
6. Do not restore old runtime modules unless there is a real product decision to do so.

## Adding a new editable field

If a new field should be managed by the client:

1. add its default in [creativewilds-data.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-data.php)
2. normalize and sanitize it in the same file
3. expose the field in [creativewilds-admin.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-admin.php)
4. render it in the appropriate shortcode inside [creativewilds-shortcodes.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-shortcodes.php)
5. style it in [creativewilds.css](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/css/creativewilds.css) if needed

## Adding a new shortcode section

Recommended flow:

1. define the data shape in [creativewilds-data.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-data.php)
2. add admin inputs in [creativewilds-admin.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-admin.php) if the section is editable
3. register the shortcode in [creativewilds-shortcodes.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-shortcodes.php)
4. enqueue shared assets through the existing `dg_cw_enqueue_assets()` path
5. add CSS in [creativewilds.css](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/css/creativewilds.css)
6. add JS in [creativewilds.js](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/js/creativewilds.js) only when necessary

## Language switching

- use `[cw_language_toggle]` anywhere you need the EN / ES switcher, including a header template
- leave section shortcodes without a fixed `lang` attribute when you want them to follow the switcher
- custom buttons can also use the `data-dg-cw-lang-switch=\"en\"` or `data-dg-cw-lang-switch=\"es\"` attribute
- the selected language is persisted with the `dg_cw_lang` query arg and cookie

## Repeater fields

Services and gallery items already use repeaters.

Follow the existing pattern:

- render row markup in PHP
- provide a `<template>`
- clone rows in [admin-creativewilds.js](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/js/admin-creativewilds.js)
- use Media Library pickers for images

## Contact workflow changes

If you change the inquiry form:

1. update the form markup in [creativewilds-shortcodes.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-shortcodes.php)
2. update AJAX handling in [creativewilds-contact.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-contact.php)
3. keep success and error states aligned with [creativewilds.js](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/js/creativewilds.js)
4. preserve CPT logging in `dg_cw_inquiry`

## Validation checklist

After each change:

1. confirm the shortcode still renders
2. confirm the admin field still saves
3. confirm no deleted legacy files are referenced again
4. run syntax validation if PHP or Node is available
5. run `git diff --check`
