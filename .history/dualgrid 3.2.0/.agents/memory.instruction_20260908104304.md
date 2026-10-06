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

# Solutions Repository
- El shortcode tour_wizard estaba duplicado entre dualgrid.php e includes/shortcodes.php; mantener una sola fuente de verdad para evitar overrides silenciosos.
