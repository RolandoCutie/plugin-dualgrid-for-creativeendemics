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

# Solutions Repository
- El shortcode tour_wizard estaba duplicado entre dualgrid.php e includes/shortcodes.php; mantener una sola fuente de verdad para evitar overrides silenciosos.
- El modal de detalle debe mostrar FAQ/When to travel/Rules/What is included al final dentro de Essentials usando fallback desde docs cuando falte meta por tour.
- El pricing del modal se construye con tour_price + repeater tour_pricing; si price_secondary no viene en repeater, se agrega como fila auxiliar para no perder datos.
- El campo Slug del tour en el admin custom es la llave de unión entre los tours reales del CPT y el catálogo oficial del Tour Finder; si el slug coincide con uno oficial, el wizard usa foto, link y metadatos reales del tour.
