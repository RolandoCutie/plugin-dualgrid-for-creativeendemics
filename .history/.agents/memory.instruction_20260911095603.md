---
applyTo: '**'
---

# Coding Preferences
- Keep WordPress plugin structure intact and prefer existing shortcode + CF7 patterns.
- Preserve client-side hidden form metadata for booking/inquiry flows.
- Match requested site branding using the existing Wildendemics palette and logo.

# Project Architecture
- Plugin uses custom post types, shortcodes, Ajax detail rendering, and CF7 form injection.
- Modal and form styling live mainly in CSS files under the theme/plugin CSS directory.
- Tour-specific defaults and reusable content are seeded in admin generation helpers.

# Solutions Repository
- Masonry gallery layout should use stable column masonry instead of fragile row-span calculations.
- Reused default included/excluded content should be pulled from docs and populated for new tours automatically.
- Modal forms should keep CF7 IDs and hidden fields while matching the provided branded layout.
