---
applyTo: '**'
---

# Coding Preferences
- Keep WordPress plugin structure intact and prefer existing shortcode + CF7 patterns.
- Preserve client-side hidden form metadata for booking/inquiry flows.
- Match requested site branding using the existing Wildendemics palette and logo.
- Keep WordPress admin screens high-contrast and close to native WP styling; avoid heavy dark themes in the admin UI.

# Project Architecture
- Plugin uses custom post types, shortcodes, Ajax detail rendering, and CF7 form injection.
- Modal and form styling live mainly in CSS files under the theme/plugin CSS directory.
- Tour-specific defaults and reusable content are seeded in admin generation helpers.
- Repository root is a wrapper folder; the actual WordPress plugin lives under the nested `dualgrid/` directory.

# Solutions Repository
- Masonry gallery layout should use stable column masonry instead of fragile row-span calculations.
- Reused default included/excluded content should be pulled from docs and populated for new tours automatically.
- Modal forms should keep CF7 IDs and hidden fields while matching the provided branded layout.
- SFTP config for this repo should set `"context": "dualgrid"` so uploads map to the real plugin root instead of adding an extra `dualgrid/` segment.
- Admin content tabs can be deep-linked with `?tab=<panel>` and activated from dashboard quick actions.
- Front-end success panels that use the `hidden` attribute need an explicit `[hidden] { display: none !important; }` rule whenever their class also sets `display`.
- Front-end language switching for this plugin should persist `dg_cw_lang` in both the query string and a cookie so shortcode-rendered sections can follow header toggles without a full i18n plugin.
- Hero slide editing lives on the same service item data as the services grid and contact selector, so admin UI changes should keep those surfaces wired to the shared repeater.
- Service items now carry a dedicated `hero_image_id`; preserve it separately from `image_id` so hero slides and service cards can use different media.
- Admin media pickers in this plugin need binding guards (for example `data-dg-cw-image-bound`) because duplicate listeners can keep the WordPress media modal from closing cleanly.
