# Crear un tour nuevo y cómo influye en el Tour Finder

Este documento explica dos cosas:

1. Cómo crear un tour nuevo dentro del sistema.
2. Cómo ese tour afecta al Tour Finder y cuándo puede aparecer por encima de otros resultados.

## 1. Dónde se crea un tour

Los tours se crean desde el menú de WordPress:

- Tours
- Añadir Nuevo

Ese formulario personalizado es el centro real de edición. Ahí defines:

- Título
- Slug
- Imagen destacada
- Descripción corta
- Extracto
- Contenido
- Datos del modal
- Itinerario
- Disponibilidad
- Tour Finder Data

## 2. Campos importantes para que el tour se vea bien

Para que el tour salga correctamente en el sitio, conviene completar como mínimo:

- Título del tour
- Slug del tour
- Imagen destacada
- Extracto
- Duración
- Dificultad
- Tamaño de grupo
- Ciudad o Location
- Fechas
- Categoría del tour

La imagen destacada es especialmente importante porque:

- alimenta cards del sitio
- alimenta el modal del tour
- ahora también alimenta la imagen del resultado en el Tour Finder cuando el tour existe realmente en WordPress

## 3. Qué controla la categoría del tour

La categoría del tour sirve para agrupar el contenido visible del sitio. Por ejemplo:

- Birding
- Wildlife Photography
- Herping
- Hiking
- Nature & Culture
- Snails & Bugs

Esto afecta:

- la navegación del sitio
- los listados por programa
- el breadcrumb del modal

Pero para el Tour Finder no basta solo con la categoría. El Finder usa además una configuración específica llamada Tour Finder Data.

## 4. Qué parte del formulario influye directamente en el Tour Finder

Dentro del editor del tour hay un bloque llamado Tour Finder Data.

Ahí se definen cuatro cosas clave:

- Main Focus
- Region
- Duration
- Availability Status

### Main Focus

Aquí marcas qué tipo de experiencia representa el tour y con qué prioridad.

Ejemplo:

- Birding con prioridad Highest
- Wildlife Photography con prioridad Medium

Eso significa que si una persona usa el Finder buscando Birding, este tour puede competir muy fuerte. Si además el tour tiene Birding como prioridad alta, tendrá más puntuación que otro tour donde Birding sea secundario.

### Region

Define la región con la que debe coincidir el tour cuando el usuario filtra en el Finder.

Opciones actuales:

- Cuba
- Western Cuba
- Eastern Cuba

Si el usuario elige Eastern Cuba, un tour marcado con esa región tendrá ventaja sobre uno que no la tenga.

### Duration

Define la banda de duración que usa el Finder, no solo el texto visible.

Opciones actuales:

- Short (1-5 days)
- Mid-range (7-11 days)
- Long (12+ days)

Si el usuario elige un viaje largo, los tours marcados como Long suman más puntos.

### Availability Status

Define el estado comercial del resultado:

- Available
- Spots
- Full

Los tours Available suelen tener más peso que Full en el ranking.

## 5. Cómo decide el Tour Finder qué tour mostrar primero

El Tour Finder calcula una puntuación interna usando principalmente:

- coincidencia de Main Focus
- prioridad del Main Focus
- coincidencia de Region
- coincidencia de Duration
- estado de disponibilidad

En términos prácticos:

- un tour que coincide exactamente con lo que el usuario pidió sube
- un tour donde ese enfoque es prioridad alta sube más
- un tour con región y duración correctas sube todavía más
- un tour Full pierde fuerza frente a uno Available

Por eso sí, un tour nuevo puede quedar por encima de otros ya existentes si está mejor configurado y coincide mejor con las respuestas del usuario.

## 6. Qué pasa mañana si crean un tour nuevo

Ahora el sistema ya permite que un tour nuevo publicado entre al Tour Finder aunque no forme parte del catálogo semilla original.

Eso ocurre así:

1. Se crea y publica el tour en WordPress.
2. Se le asigna una imagen destacada.
3. Se completan los campos de Tour Finder Data.
4. El sistema lo incorpora al catálogo del Finder.
5. Si sus coincidencias son mejores que las de otros tours, puede aparecer primero.

En otras palabras: ya no depende solo de estar escrito a mano en código.

## 7. Qué sigue dependiendo del slug

El slug sigue siendo importante por dos motivos:

1. Si coincide con un tour del catálogo oficial antiguo, el sistema puede reemplazar el dato semilla por el tour real.
2. Facilita que el equipo identifique exactamente cada resultado dentro del panel Tour Finder.

Recomendación:

- usar slugs limpios, estables y descriptivos
- no cambiarlos sin necesidad una vez que el tour esté publicado

## 8. Dónde se cambian links y estados del Tour Finder

Ahora existe una pantalla de administración nueva:

- Tours
- Tour Finder

Desde ahí se pueden cambiar:

- URL de cada programa
- imagen de cada programa
- descripción de cada programa
- URL final de cada tour en el Finder
- estado del tour en el Finder

Importante:

- la imagen del resultado del tour ya no se gestiona manualmente en ese panel
- la imagen del resultado sale del tour real creado en WordPress
- si el tour no tiene imagen destacada, el sistema puede caer al fallback del programa

## 9. Diferencia entre datos del programa y datos del tour

Hay dos niveles distintos:

### Programa

Ejemplos:

- Birding
- Herping
- Hiking

En el panel Tour Finder puedes editar para cada programa:

- link
- imagen
- descripción

### Tour

Ejemplos:

- Zapata Wetland Birding Tour
- Herp-watching in Eastern Cuba

En el panel Tour Finder puedes editar para cada tour:

- link del resultado
- estado

Pero el peso real del ranking se edita dentro del tour, en el bloque Tour Finder Data.

## 10. Flujo recomendado para crear un tour nuevo que funcione bien en el Finder

Usa este orden:

1. Crear el tour en Tours > Añadir Nuevo.
2. Completar título, slug, extracto e imagen destacada.
3. Asignar la categoría correcta.
4. Completar Duration, Group, Difficulty, Location y Dates.
5. Completar Tour Finder Data con Main Focus, Region, Duration y Status.
6. Publicar el tour.
7. Entrar a Tours > Tour Finder y revisar la URL y el estado del resultado.
8. Probar el Finder en frontend con combinaciones que deberían hacer aparecer ese tour.

## 11. Cómo lograr que un tour nuevo salga por encima de otros

Si quieres que un tour nuevo gane relevancia en una búsqueda, normalmente debes:

- marcar el Main Focus correcto
- darle prioridad Highest cuando realmente sea el foco principal
- asignarle la región correcta
- elegir la banda de duración correcta
- mantener status en Available si aplica

No conviene inflar prioridades de forma artificial, porque eso degrada la lógica del Finder y puede hacer que recomiende tours poco precisos.

## 12. Checklist rápido antes de publicar

- ¿Tiene imagen destacada?
- ¿Tiene categoría correcta?
- ¿Tiene Main Focus configurado?
- ¿La prioridad del Main Focus es la correcta?
- ¿Tiene Region configurada?
- ¿Tiene Duration Band configurada?
- ¿Tiene Status configurado?
- ¿Tiene slug limpio y estable?
- ¿La URL final del resultado se revisó en Tours > Tour Finder?

## 13. Resumen corto

Crear un tour nuevo ahora sí impacta al Tour Finder de forma directa.

Si el tour:

- está publicado
- tiene imagen destacada
- tiene bien configurado Tour Finder Data

entonces puede entrar al Finder y competir con los demás resultados. Si coincide mejor con lo que busca el usuario, puede aparecer por encima de otros tours más antiguos.