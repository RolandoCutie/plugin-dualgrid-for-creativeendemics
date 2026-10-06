# Dualgrid Tour Creation Guide

## Objetivo

Esta guía explica cómo crear un tour desde el admin personalizado y cómo hacer que ese tour se conecte correctamente con:

- las cards de tours
- el modal de detalles
- la página individual del tour
- el Tour Finder Wizard

## Flujo real del sistema

1. Creas o editas un tour desde el admin personalizado:
   - Menú: Tours
   - Pantallas: lista, crear, editar

2. Ese formulario guarda un post type `tour` con metadatos personalizados.

3. Esos datos luego alimentan distintas salidas:
   - Cards de tours: shortcode `we_tours`
   - Modal de detalle: HTML en `includes/shortcodes.php` + datos AJAX en `includes/ajax-tour-details.php` + render en `js/tour-modal-detail.js`
   - Página individual: `templates/single-tour.php`
   - Tour Finder Wizard: dataset localizado desde `dg_get_tour_finder_wizard_data()` y lógica en `js/wizard.js`

## Lo más importante para el Tour Finder

El wizard no recomienda cualquier tour al azar.

Tiene un catálogo oficial semilla definido en:

- `includes/shortcodes.php`
- función: `dg_get_tour_finder_official_catalog_seed()`

Ahora el sistema ya puede reemplazar los datos del catálogo semilla por los datos del tour real que tú creaste, pero solo si el `slug` del tour coincide exactamente con uno de los slugs oficiales.

## Cómo conectar un tour real con el wizard

Cuando crees un tour desde el admin, revisa el campo:

- `Slug del tour`

Ese slug debe coincidir exactamente con uno de estos slugs oficiales:

| Programa | Slug oficial |
|---|---|
| Birding | `zapata-wetland-birding-tour` |
| Birding | `birding-escape-in-cuba` |
| Birding | `7-day-cuba-birding-essentials` |
| Birding | `cuba-birding-tour-endemics-migration` |
| Birding | `grand-birding-tour-cuban-endemics-habitats` |
| Herping | `wild-cuba-reptiles-amphibians-tour` |
| Herping | `herp-watching-in-eastern-cuba` |
| Nature & Culture | `cuban-nature-adventure-birds-waterfalls` |
| Nature & Culture | `eastern-cuba-discovery-natural-wonders` |
| Nature & Culture | `cuba-wild-odyssey-tour` |
| Wildlife Photography | `bird-photography-expedition-cuban-endemics-caribbean-specialties-tour` |
| Wildlife Photography | `polymita-photography-expedition-cubas-painted-tree-snails` |
| Snails & Bugs | `cuba-land-snails-photography-workshop` |
| Hiking | `eastern-cuba-hiking-trails-heritage` |
| Hiking | `eastern-cuba-trek-rainforest-peaks` |
| Hiking | `western-cuba-hiking-nature-culture` |
| Hiking | `cuba-grand-trek-mountains-valleys` |

## Qué pasa si el slug coincide

Si coincide:

- el wizard usa la foto real del tour
- usa el enlace real del tour
- usa el título real del tour
- usa sus metadatos reales de ranking

## Qué pasa si el slug no coincide

Si no coincide:

- el wizard sigue usando la entrada semilla del catálogo oficial
- puede mostrar imagen genérica del programa
- tu tour existe en el sitio, pero no reemplaza ese resultado oficial del Finder

## Qué llena cada bloque del formulario

### Información Básica

- `Título del Tour`: cards, modal y página individual
- `Slug del tour`: conexión exacta con el Tour Finder
- `Descripción corta`: header del modal
- `Extracto`: descripción de card
- `Contenido / Itinerario completo`: página individual del tour
- `Coordenadas`: línea superior del modal
- `Ciudad base`: cards y atributos del modal
- `Duración`: cards, modal y single
- `Máximo de personas`: cards, modal y single
- `Precio`: resumen principal del tour
- `Single supplement`: línea secundaria de precio
- `Etiqueta del single supplement`: nombre de esa línea
- `Próxima salida`: atributo del modal
- `Tipo de tour`: atributo del modal
- `Idioma`: atributo del modal
- `Ritmo`: sección de transport/accommodation del modal
- `Transporte`: sección transport del modal
- `Alojamiento`: modal y página individual
- `Fechas disponibles`: cards y resumen general
- `Link de Google Maps`: mapa del modal
- `Dificultad`: cards, modal y single

### Imagen Principal

Se usa en:

- la card del tour
- la galería principal del modal
- el hero de la página individual
- el wizard, si el slug conecta con el catálogo oficial

### Categorías

Determinan:

- en qué listados aparece el tour
- la taxonomía usada por `we_tours`
- el breadcrumb y relación de la página individual

### Tour Finder Data

Esta sección sí se usa.

Sirve para el filtrado y ranking del wizard.

- `Main Focus`: interés principal del tour
- `Region`: región cubierta por el tour
- `Duration`: banda corta/media/larga para el wizard
- `Availability Status`: estado corto del ranking

Importante:

- ya no se marca todo automáticamente
- ahora solo se guarda lo que el editor selecciona
- si dejas esto vacío, el wizard no tendrá señales explícitas de ranking para ese tour real

### Galería

Estas imágenes alimentan la galería del modal.

### Sobre este tour

Aquí conviene pasar el bloque `OVERVIEW` del documento fuente.

### Fauna / Experiencia

Úsalo para highlights biológicos, especies objetivo y elementos visuales.

### Organizador y Guías

Se muestran en el modal si tienen contenido.

### Itinerario

Usa aquí el día por día del documento.

### Disponibilidad y precios

- `Availability`: salidas concretas
- `Pricing`: desglose completo de precios del modal

### Incluido / No incluido

Se muestra al final del modal.

Si lo dejas vacío, el sistema puede usar defaults globales desde la carpeta `docs`.

### Travel notes, Rules, Americans y FAQ

Aquí van los textos largos finales del modal:

- `When to travel`
- `Can Americans Join?`
- `Terms and Conditions`
- `Physical Demands`
- `FAQ`

Estas áreas aceptan texto largo y también enlaces.

Formatos permitidos:

1. HTML seguro:

```html
<a href="https://example.com">Read more</a>
```

2. Formato tipo Markdown:

```text
[Read more](https://example.com)
```

## Cómo crear bien un tour nuevo

1. Crea el post desde el admin custom `Tours`.
2. Define un título claro.
3. Si el tour debe salir en el wizard oficial, pon un slug exacto del catálogo oficial.
4. Sube imagen principal.
5. Añade extracto para la card.
6. Añade descripción corta para el header del modal.
7. Completa duración, grupo, dificultad, ciudad, fechas y precio.
8. Completa `Tour Finder Data` para que el ranking del wizard tenga lógica real.
9. Pasa el `OVERVIEW` del documento a `Sobre este tour`.
10. Pasa el `ITINERARY` a la pestaña Itinerario.
11. Pasa precios adicionales a `Desglose de precios`.
12. Pasa `When to travel`, `Can Americans Join?`, `Rules` y `FAQ` al bloque final.
13. Guarda y revisa:
   - card
   - modal
   - página individual
   - resultado del wizard

## Cómo saber si el wizard ya está usando tu tour real

Revisa estas señales:

1. El slug del tour coincide con uno oficial.
2. El resultado del wizard muestra la foto real del tour y no la imagen genérica del programa.
3. El enlace del resultado lleva a la página real del tour.

## Regla práctica

Si el tour forma parte del catálogo oficial del Finder:

- crea el tour desde el admin
- usa el slug oficial exacto
- rellena `Tour Finder Data`

Si el tour no forma parte del catálogo oficial:

- igual puedes crearlo y mostrarlo en cards/modal/single
- pero el wizard no lo tratará como uno de sus resultados oficiales hasta que se agregue al catálogo semilla o se cambie la estrategia de catálogo