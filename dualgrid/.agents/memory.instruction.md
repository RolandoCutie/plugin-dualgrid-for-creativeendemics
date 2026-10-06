---
applyTo: '**'
---

# Coding Preferences
- Mantener cambios mínimos y consistentes con shortcodes y JS vanilla.
- Centralizar defaults, sanitización y lecturas compartidas antes de tocar el render.
- Si el cliente debe editar algo, exponerlo en el admin con Media Library o campos directos, no como hardcode.

# Project Architecture
- El runtime activo del plugin ahora vive en [dualgrid.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/dualgrid.php) y solo carga los módulos CreativeWilds.
- Los datos compartidos viven en la opción `dg_cw_settings` y se normalizan en [creativewilds-data.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-data.php).
- Los shortcodes activos son `cw_hero`, `cw_services`, `cw_about`, `cw_gallery` y `cw_contact`, todos definidos en [creativewilds-shortcodes.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-shortcodes.php).
- El editor de contenido del cliente y los repetidores de servicios/galería viven en [creativewilds-admin.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-admin.php) y [admin-creativewilds.js](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/js/admin-creativewilds.js).
- Las consultas del formulario se guardan en el CPT `dg_cw_inquiry` y se procesan en [creativewilds-contact.php](/Users/rolandocutie/Documents/Proyectos/Wild Endemics Web Page/plugin dualgrid/dualgrid/includes/creativewilds-contact.php).

# Solutions Repository
- Si se reemplaza una arquitectura grande por una landing específica, sacar primero los módulos viejos del bootstrap y luego borrar los archivos legacy evita referencias rotas.
- Cuando una misma fuente de datos alimenta varias secciones, usar una única opción normalizada evita duplicación entre hero, servicios y formulario.
- Para repetidores con imágenes en el admin, reutilizar templates HTML + `wp.media` mantiene el flujo simple para el cliente.
