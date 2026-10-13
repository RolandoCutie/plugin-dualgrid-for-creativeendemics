# Dualgrid CreativeWilds Architecture

## Overview

The active plugin runtime is now dedicated to a single landing experience for CreativeWilds.

The plugin provides:

1. editable landing content from a custom WordPress admin screen
2. five frontend shortcodes for the landing sections
3. a contact workflow that stores inquiries and emails the site owner
4. a shared visual system for typography, spacing, animation, and section behavior

## Bootstrap

Main entry file:

- [dualgrid.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/dualgrid.php)

Responsibilities:

- defines plugin constants
- loads the CreativeWilds modules
- keeps the runtime intentionally small

Loaded modules:

- [creativewilds-data.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-data.php)
- [creativewilds-contact.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-contact.php)
- [creativewilds-shortcodes.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-shortcodes.php)
- [creativewilds-admin.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-admin.php)

## Data model

Shared landing content is stored in the WordPress option:

- `dg_cw_settings`

That option contains:

- general brand data
- hero button labels
- services data
- about content
- gallery items
- contact content

Normalization and defaults live in:

- [creativewilds-data.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-data.php)

Important rules:

- EN and ES strings are stored in pairs
- services are the source for both the services grid and the hero slides
- gallery items are reorderable and image-driven
- all image references are Media Library attachment IDs

## Frontend shortcodes

Shortcodes live in:

- [creativewilds-shortcodes.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-shortcodes.php)

Registered shortcodes:

- `[cw_language_toggle]`
- `[cw_hero]`
- `[cw_services]`
- `[cw_about]`
- `[cw_gallery]`
- `[cw_contact]`

### Section relationships

- `[cw_language_toggle]` persists the EN/ES choice through a query arg + cookie so the landing can be switched from a header control
- `[cw_hero]` reads its rotating slides from the active service items but uses a dedicated hero image per item
- `[cw_services]` renders the editable service cards
- `[cw_about]` renders the editorial two-column story block
- `[cw_gallery]` renders the mosaic and its lightbox
- `[cw_contact]` renders direct contact data and the AJAX inquiry form

## Asset map

Typography:

- [global-typography.css](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/css/global-typography.css)

Landing styles:

- [creativewilds.css](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/css/creativewilds.css)
- frontend assets are loaded globally so header-level language toggles work even when the header only accepts raw HTML

Admin styles:

- [admin-creativewilds.css](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/css/admin-creativewilds.css)

Landing behavior:

- [creativewilds.js](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/js/creativewilds.js)

Admin behavior:

- [admin-creativewilds.js](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/js/admin-creativewilds.js)

## Admin architecture

Admin entry points live in:

- [creativewilds-admin.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-admin.php)

The admin provides:

- a dashboard page
- a content editor page with deep-linkable tabs
- service repeaters
- gallery repeaters
- Media Library image pickers

The admin is designed so a client can:

- add or remove services
- change EN and ES copy
- replace images without touching code
- update contact details and success/error messages

## Inquiry flow

Inquiry logic lives in:

- [creativewilds-contact.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-contact.php)

Flow:

1. the contact form submits through AJAX
2. nonce and required fields are validated
3. a `dg_cw_inquiry` post is created
4. post meta stores name, email, phone, service, language, and source URL
5. an email is sent to the configured recipient
6. the frontend shows success or error state

## Current extension guidance

When adding new landing features:

1. add defaults and normalization in [creativewilds-data.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-data.php)
2. expose editable fields in [creativewilds-admin.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-admin.php)
3. render the surface in [creativewilds-shortcodes.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-shortcodes.php)
4. add behavior in [creativewilds.js](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/js/creativewilds.js) only if interaction is required
5. keep styling in [creativewilds.css](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/css/creativewilds.css)
