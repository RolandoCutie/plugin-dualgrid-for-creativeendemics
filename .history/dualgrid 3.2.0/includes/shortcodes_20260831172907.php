<?php
/* ============================================
   DUALGRID - SHORTCODE TOURS CARDS
   Agregar al final de includes/shortcodes.php
   ============================================ */

if (!defined('ABSPATH'))
    exit;

if (!function_exists('dg_format_price')) {
    function dg_format_price($price)
    {
        $price = is_string($price) ? trim($price) : $price;

        if ($price === '' || $price === null || $price === false) {
            return '';
        }

        if (preg_match('/[$€£¢]/u', (string) $price)) {
            return (string) $price;
        }

        $normalized = str_replace([' ', ','], '', (string) $price);

        if (is_numeric($normalized)) {
            $number = (float) $normalized;

            if (floor($number) === $number) {
                return '$' . number_format($number, 0, '.', ',');
            }

            return '$' . number_format($number, 2, '.', ',');
        }

        return '$' . (string) $price;
    }
}

if (!function_exists('dg_translate_tour_value')) {
    function dg_translate_tour_value($value, $context = '')
    {
        if (!is_string($value)) {
            return $value;
        }

        $value = trim($value);

        if ($value === '') {
            return '';
        }

        $exact = [
            'Easy' => 'Fácil',
            'Moderate' => 'Moderado',
            'Advanced' => 'Avanzado',
            'Easy-Moderate' => 'Fácil-Moderado',
            'Easy–Moderate' => 'Fácil–Moderado',
            'Moderate-Advanced' => 'Moderado-Avanzado',
            'Moderate–Advanced' => 'Moderado–Avanzado',
            'English & Spanish' => 'Inglés y español',
            'CEO & Lead Birding Guide' => 'CEO y guía principal de observación de aves',
            'Birding Guide' => 'Guía de observación de aves',
            'Photography Guide' => 'Guía de fotografía',
            'Lead Birding Guide' => 'Guía principal de observación de aves',
            'Small group tour (max 6)' => 'Tour para grupo pequeño (máx. 6)',
            'Private 4x4 vehicle and mountain transfer' => 'Vehículo privado 4x4 y traslado de montaña',
            'Eco-Lodge & Research Station' => 'Eco-lodge y estación de investigación',
            'Open' => 'Disponible',
        ];

        if (isset($exact[$value])) {
            $value = $exact[$value];
        }

        if ($context === 'group' && is_numeric($value)) {
            return 'Máx. ' . $value;
        }

        $patterns = [
            '/\bJanuary\b/i' => 'Enero',
            '/\bFebruary\b/i' => 'Febrero',
            '/\bMarch\b/i' => 'Marzo',
            '/\bApril\b/i' => 'Abril',
            '/\bMay\b/i' => 'Mayo',
            '/\bJune\b/i' => 'Junio',
            '/\bJuly\b/i' => 'Julio',
            '/\bAugust\b/i' => 'Agosto',
            '/\bSeptember\b/i' => 'Septiembre',
            '/\bOctober\b/i' => 'Octubre',
            '/\bNovember\b/i' => 'Noviembre',
            '/\bDecember\b/i' => 'Diciembre',
            '/\bJan\b/i' => 'Ene',
            '/\bApr\b/i' => 'Abr',
            '/\bAug\b/i' => 'Ago',
            '/\bDec\b/i' => 'Dic',
            '/\bdays\b/i' => 'días',
            '/\bday\b/i' => 'día',
            '/\bnights\b/i' => 'noches',
            '/\bnight\b/i' => 'noche',
            '/\bEnglish\b/i' => 'Inglés',
            '/\bSpanish\b/i' => 'Español',
            '/\bpeople\b/i' => 'personas',
            '/\bperson\b/i' => 'persona',
            '/\bspots? left\b/i' => 'cupos disponibles',
            '/\bUp to\b/i' => 'Hasta',
            '/\bdaily walks of\b/i' => 'caminatas diarias de',
            '/\bprivate\b/i' => 'privado',
            '/\bvehicle\b/i' => 'vehículo',
            '/\bmountain transfer\b/i' => 'traslado de montaña',
        ];

        foreach ($patterns as $pattern => $replacement) {
            $value = preg_replace($pattern, $replacement, $value);
        }

        $value = preg_replace('/^Max\.?\s*/i', 'Máx. ', $value);
        $value = preg_replace('/\s*&\s*/', ' y ', $value);

        return trim($value);
    }
}

if (!function_exists('dg_get_tour_meta_icon')) {
    function dg_get_tour_meta_icon($type)
    {
        switch ($type) {
            case 'duration':
                return '<svg class="we-program-meta__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>';
            case 'group':
                return '<svg class="we-program-meta__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>';
            case 'difficulty':
                return '<svg class="we-program-meta__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg>';
            case 'price':
                return '<svg class="we-program-meta__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="12" y1="2" x2="12" y2="22"></line><path d="M17 6H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>';
            case 'dates':
                return '<svg class="we-program-meta__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>';
            default:
                return '';
        }
    }
}

/**
 * Shortcode: [tour_cards]
 *
 * Muestra una grid de 4 tarjetas de tours con imagen de fondo,
 * título, descripción, duración y capacidad máxima.
 */
add_shortcode('tour_cards', function ($atts) {
    $tours = [
        [
            'url' => '/tours/programas-de-avistamiento/',
            'image' => 'https://palegreen-herring-696641.hostingersite.com/wp-content/uploads/2026/07/d1a47e8e2_generated_7380d386.jpg',
            'title' => 'Tours de Observación de Aves',
            'desc' => 'Expediciones guiadas por expertos a los mejores destinos para avistamiento de aves del mundo...',
            'duration' => '5-14 días',
            'max' => 'Máx 6',
        ],
        [
            'url' => '/tours/tours-de-naturaleza/',
            'image' => 'https://palegreen-herring-696641.hostingersite.com/wp-content/uploads/2026/07/d1a47e8e2_generated_7380d386.jpg',
            'title' => 'Tours de Naturaleza',
            'desc' => 'Expediciones profundas en la selva tropical, caminatas a cascadas...',
            'duration' => '4-10 días',
            'max' => 'Máx 8',
        ],
        [
            'url' => '/tours/tours-de-senderismo/',
            'image' => 'https://palegreen-herring-696641.hostingersite.com/wp-content/uploads/2026/07/d1a47e8e2_generated_7380d386.jpg',
            'title' => 'Tours de Aventura',
            'desc' => 'Experiencias de alta adrenalina a través de paisajes volcánicos...',
            'duration' => '3-7 días',
            'max' => 'Máx 12',
        ],
        [
            'url' => '/tours/tours-culturales/',
            'image' => 'https://palegreen-herring-696641.hostingersite.com/wp-content/uploads/2026/07/d1a47e8e2_generated_7380d386.jpg',
            'title' => 'Tours Culturales',
            'desc' => 'Sumérgete en tradiciones indígenas y gastronomía local...',
            'duration' => '2-5 días',
            'max' => 'Máx 10',
        ],
    ];

    ob_start();
    ?>
    <div class="tour-section">
        <div class="tour-header">
            <div>
                <div class="tour-label">Expediciones Destacadas</div>
                <h2 class="tour-title">Cuatro formas de conocer<br>la <em>naturaleza</em></h2>
            </div>
            <a href="/tours" class="view-all">Ver todos los tours →</a>
        </div>
        <div class="tour-grid">
            <?php foreach ($tours as $tour): ?>
                <a href="<?php echo esc_url($tour['url']); ?>" class="tour-card" target="_self">
                    <div class="tour-card-bg" style="background-image: url('<?php echo esc_url($tour['image']); ?>');"></div>
                    <div class="tour-card-overlay"></div>
                    <div class="tour-card-meta-top">
                        <span></span>
                        <span></span>
                    </div>
                    <div class="tour-card-content">
                        <h3 class="tour-card-title"><?php echo esc_html($tour['title']); ?></h3>
                        <p class="tour-card-desc"><?php echo esc_html($tour['desc']); ?></p>
                        <div class="tour-card-info">
                            <span>⏱ <?php echo esc_html($tour['duration']); ?></span>
                            <span>👥 <?php echo esc_html($tour['max']); ?></span>
                        </div>
                        <span class="tour-card-link">Explorar Tours →</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
});

/* ============================================
   DUALGRID - SHORTCODES FAQ
   Agregar al final de includes/shortcodes.php
   ============================================ */

/**
 * Shortcode contenedor: [dualgrid_faqs]
 *
 * Atributos:
 * - label: Texto superior (default: "BUENO SABER")
 * - titulo: Parte regular del título (default: "Preguntas")
 * - titulo_italic: Parte en cursiva del título (default: "Frecuentes")
 * - subtitulo: Texto descriptivo debajo del título
 * - link_text: Texto del enlace (default: "Contáctanos.")
 * - link_url: URL del enlace (default: "#")
 *
 * Uso en Elementor:
 * [dualgrid_faqs]
 *   [dualgrid_faq pregunta="¿Pregunta 1?" respuesta="Respuesta 1"]
 *   [dualgrid_faq pregunta="¿Pregunta 2?" respuesta="Respuesta 2"]
 * [/dualgrid_faqs]
 */
add_shortcode('dualgrid_faqs', function ($atts, $content = null) {
    $atts = shortcode_atts([
        'label' => 'BUENO SABER',
        'titulo' => 'Preguntas',
        'titulo_italic' => 'Frecuentes',
        'subtitulo' => 'Todo lo que necesitas saber sobre nuestras expediciones. ¿No encuentras lo que buscas?',
        'link_text' => 'Contáctanos.',
        'link_url' => '#',
    ], $atts, 'dualgrid_faqs');

    // Reiniciar contador global para cada sección FAQ
    $GLOBALS['dualgrid_faq_counter'] = 0;

    // Procesar shortcodes internos
    $items = do_shortcode($content);

    ob_start();
    ?>
    <section class="dg-faqs-section">
        <div class="dg-faqs-container">
            <div class="dg-faqs-header">
                <span class="dg-faqs-label"><?php echo esc_html($atts['label']); ?></span>
                <h2 class="dg-faqs-title">
                    <?php echo esc_html($atts['titulo']); ?> <em><?php echo esc_html($atts['titulo_italic']); ?></em>
                </h2>
                <p class="dg-faqs-subtitle">
                    <?php echo esc_html($atts['subtitulo']); ?>
                    <?php if ($atts['link_url']): ?>
                        <a href="<?php echo esc_url($atts['link_url']); ?>"><?php echo esc_html($atts['link_text']); ?></a>
                    <?php endif; ?>
                </p>
            </div>
            <div class="dg-faqs-list">
                <?php echo $items; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
});

/**
 * Shortcode item: [dualgrid_faq]
 *
 * Atributos:
 * - pregunta: Texto de la pregunta (obligatorio)
 * - respuesta: Texto/HTML de la respuesta (obligatorio)
 * - numero: Número personalizado (opcional, auto-generado si se omite)
 */
add_shortcode('dualgrid_faq', function ($atts) {
    $atts = shortcode_atts([
        'pregunta' => '',
        'respuesta' => '',
        'numero' => '',
    ], $atts, 'dualgrid_faq');

    // Generar número automático si no se proporciona
    if (empty($atts['numero'])) {
        if (!isset($GLOBALS['dualgrid_faq_counter'])) {
            $GLOBALS['dualgrid_faq_counter'] = 0;
        }
        $GLOBALS['dualgrid_faq_counter']++;
        $num = str_pad($GLOBALS['dualgrid_faq_counter'], 2, '0', STR_PAD_LEFT);
    } else {
        $num = esc_html($atts['numero']);
    }

    ob_start();
    ?>
    <div class="dg-faq-item">
        <button class="dg-faq-trigger" aria-expanded="false">
            <span class="dg-faq-number"><?php echo esc_html($num); ?></span>
            <span class="dg-faq-question"><?php echo esc_html($atts['pregunta']); ?></span>
            <span class="dg-faq-icon" aria-hidden="true">
                <span class="dg-icon-plus"></span>
                <span class="dg-icon-minus"></span>
            </span>
        </button>
        <div class="dg-faq-content">
            <div class="dg-faq-inner">
                <?php echo wp_kses_post($atts['respuesta']); ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
});






/* ============================================
   DUALGRID - SHORTCODE TESTIMONIALS CAROUSEL
   Agregar al final de includes/shortcodes.php
   ============================================ */

/**
 * Shortcode: [testimonials_carousel]
 *
 * Atributos:
 * - count: Cantidad de testimonials a mostrar (default: 10)
 * - speed: Velocidad de rotación en ms (default: 6000)
 *
 * Uso en Elementor:
 * [testimonials_carousel count="5" speed="5000"]
 */
add_shortcode('testimonials_carousel', function ($atts) {
    $atts = shortcode_atts(['count' => 10, 'speed' => 6000], $atts, 'testimonials_carousel');

    $query = new WP_Query([
        'post_type' => 'testimonial',
        'posts_per_page' => intval($atts['count']),
        'post_status' => 'publish',
        'meta_query' => [
            ['key' => '_tp_estado', 'value' => 'aprobado', 'compare' => '=']
        ]
    ]);

    if (!$query->have_posts()) {
        return '<p style="text-align:center;">No hay testimonials aprobados aún.</p>';
    }

    $items = [];
    while ($query->have_posts()) {
        $query->the_post();
        $img = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: 'https://via.placeholder.com/128';
        $items[] = [
            'quote' => get_the_content(),
            'name' => get_the_title(),
            'location' => get_post_meta(get_the_ID(), '_tp_ubicacion', true) ?: '',
            'stars' => intval(get_post_meta(get_the_ID(), '_tp_valoracion', true) ?: 5),
            'avatar' => $img,
        ];
    }
    wp_reset_postdata();

    $json = json_encode($items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $speed = intval($atts['speed']);

    ob_start();
    ?>
    <div id="tp-carousel" class="tp-carousel" data-items='<?php echo htmlspecialchars($json, ENT_QUOTES, 'UTF-8'); ?>'
        data-speed="<?php echo $speed; ?>">
        <div class="tp-inner">
            <div class="tp-label">Voces del campo</div>
            <h2 class="tp-title">Historias que <em>perduran</em></h2>

            <!-- Wrapper para animaciones -->
            <div class="tp-slide-wrap tp-active" id="tp-slide-wrap">
                <div class="tp-quote-icon">"</div>
                <div class="tp-quote" id="tp-quote"></div>
                <img class="tp-avatar" id="tp-avatar" src="" alt="">
                <div class="tp-name" id="tp-name"></div>
                <div class="tp-location" id="tp-location"></div>
                <div class="tp-stars" id="tp-stars"></div>
            </div>

            <div class="tp-dots" id="tp-dots"></div>
        </div>
        <button class="tp-nav tp-prev" onclick="tpPrev()">‹</button>
        <button class="tp-nav tp-next" onclick="tpNext()">›</button>
    </div>
    <?php
    return ob_get_clean();
});

/**
 * Shortcode: [tour_wizard]
 *
 * Solo renderiza el BOTÓN. El overlay ya existe globalmente.
 * Uso en la home: [tour_wizard]
 * Uso con texto personalizado: [tour_wizard texto="Descubre tu tour →"]
 */
add_shortcode('tour_wizard', function ($atts) {
    $atts = shortcode_atts([
        'texto' => 'Encuentra tu expedición ideal →',
        'clase' => 'dg-wizard-btn',
    ], $atts, 'tour_wizard');

    return '<button class="' . esc_attr($atts['clase']) . ' dg-wizard-trigger">' . esc_html($atts['texto']) . '</button>';
});





/* ============================================
   DUALGRID - SHORTCODE HERO SLIDER
   Agregar al final de includes/shortcodes.php
   ============================================ */

/**
 * Shortcode: [hero_slider]
 *
 * Muestra un hero slider fullscreen con Ken Burns y autoplay.
 *
 * Atributos:
 * - slide_1_image, slide_2_image, slide_3_image, slide_4_image: URLs de imágenes
 * - slide_1_title, slide_2_title, etc.: Títulos
 * - slide_1_subtitle, etc.: Subtítulos
 * - slide_1_desc, etc.: Descripciones
 * - slide_1_link, etc.: URLs del botón primario
 * - btn_primary_text: Texto botón primario (default: "Explorar Tours →")
 * - btn_secondary_text: Texto botón secundario (default: "Personalizar mi Viaje")
 * - btn_secondary_link: URL botón secundario (default: "#")
 * - delay: Milisegundos entre slides (default: 6000)
 *
 * Uso en Elementor:
 * [hero_slider
 *   slide_1_image="URL" slide_1_title="Tours de Aves" slide_1_subtitle="Subtítulo" slide_1_desc="Descripción..." slide_1_link="/tours/birding"
 *   slide_2_image="URL" slide_2_title="Tours de Naturaleza" ...
 * ]
 */
add_shortcode('hero_slider', function ($atts) {
    $atts = shortcode_atts([
        'slide_1_image' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1920',
        'slide_1_title' => 'Tours de Observación de Aves',
        'slide_1_subtitle' => 'Donde el cielo se pinta de plumas',
        'slide_1_desc' => 'Expediciones guiadas por expertos a los mejores destinos para avistamiento de aves del mundo...',
        'slide_1_link' => '/tours/programas-de-avistamiento/',

        'slide_2_image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920',
        'slide_2_title' => 'Tours de Naturaleza',
        'slide_2_subtitle' => 'Adéntrate en el pulso de la selva',
        'slide_2_desc' => 'Expediciones profundas en la selva tropical, caminatas a cascadas...',
        'slide_2_link' => '/tours/tours-de-naturaleza/',

        'slide_3_image' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1920',
        'slide_3_title' => 'Tours de Aventura',
        'slide_3_subtitle' => 'Desafía la tierra donde arde la tierra',
        'slide_3_desc' => 'Experiencias de alta adrenalina a través de paisajes volcánicos...',
        'slide_3_link' => '/tours/tours-de-senderismo/',

        'slide_4_image' => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=1920',
        'slide_4_title' => 'Tours Culturales',
        'slide_4_subtitle' => 'Siente el latido de raíces milenarias',
        'slide_4_desc' => 'Sumérgete en tradiciones indígenas y gastronomía local...',
        'slide_4_link' => '/tours/tours-culturales/',

        'btn_primary_text' => 'Explorar Tours →',
        'btn_secondary_text' => 'Personaliza tu viaje',
        'btn_secondary_link' => '#',
        'delay' => 6000,
    ], $atts, 'hero_slider');

    $slides = [];
    for ($i = 1; $i <= 4; $i++) {
        $img = $atts["slide_{$i}_image"];
        $title = $atts["slide_{$i}_title"];
        if (empty($img) || empty($title))
            continue;
        $slides[] = [
            'image' => $img,
            'title' => $title,
            'subtitle' => $atts["slide_{$i}_subtitle"],
            'desc' => $atts["slide_{$i}_desc"],
            'link' => $atts["slide_{$i}_link"],
        ];
    }

    if (empty($slides))
        return '';

    $delay = intval($atts['delay']);

    ob_start();
    ?>
    <div class="dg-hero-slider" id="dgHeroSlider" data-delay="<?php echo esc_attr($delay); ?>">
        <?php foreach ($slides as $index => $slide): ?>
            <div class="dg-hero-slide <?php echo $index === 0 ? 'dg-active' : ''; ?>" data-index="<?php echo $index; ?>">
                <div class="dg-hero-bg" style="background-image: url('<?php echo esc_url($slide['image']); ?>');"></div>
                <div class="dg-hero-overlay"></div>
                <div class="dg-hero-content">
                    <h1 class="dg-hero-title"><?php echo esc_html($slide['title']); ?></h1>
                    <p class="dg-hero-subtitle"><?php echo esc_html($slide['subtitle']); ?></p>
                    <p class="dg-hero-desc"><?php echo esc_html($slide['desc']); ?></p>
                    <div class="dg-hero-buttons">
                        <a href="<?php echo esc_url($slide['link']); ?>"
                            class="dg-hero-btn dg-hero-btn-primary"><?php echo esc_html($atts['btn_primary_text']); ?></a>
                        <a href="<?php echo esc_url($atts['btn_secondary_link']); ?>"
                            class="dg-hero-btn dg-hero-btn-outline dg-wizard-trigger"><?php echo esc_html($atts['btn_secondary_text']); ?></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
});





/**
 * Shortcode: [wild_tour]
 * Cada tour individual. El layout se alterna automáticamente.
 *
 * Atributos:
 *   numero        -> "01", "02", etc. (aparece en el label: "01 · TIPO DE EXPEDICIÓN")
 *   imagen        -> URL de la imagen
 *   label         -> "TIPO DE EXPEDICIÓN"
 *   titulo        -> Título principal
 *   subtitulo     -> Texto en cursiva verde
 *   descripcion   -> Párrafo descriptivo
 *   duracion      -> ej: "5-14 días"
 *   grupo         -> ej: "Máx. 6"
 *   dificultad    -> ej: "Fácil-Moderado"
 *   link          -> URL del botón
 *   btn_texto     -> Texto del botón (default: "Ver Programas")
 */
add_shortcode('wild_tour', function ($atts) {
    $atts = shortcode_atts([
        'numero' => '01',
        'imagen' => '',
        'label' => 'TIPO DE EXPEDICIÓN',
        'titulo' => 'Título del Tour',
        'subtitulo' => 'Subtítulo aquí',
        'descripcion' => 'Descripción del tour.',
        'duracion' => '5-14 días',
        'grupo' => 'Máx. 6',
        'dificultad' => 'Fácil-Moderado',
        'link' => '#',
        'btn_texto' => 'Ver Programas',
    ], $atts, 'wild_tour');

    // Dirección de animación según número par/impar
    $direccion = ((int) $atts['numero'] % 2 === 0) ? 'from-right' : 'from-left';

    ob_start();
    ?>
    <div class="wild-tour-card <?php echo esc_attr($direccion); ?>">
        <div class="wild-tour__image-wrap">
            <img src="<?php echo esc_url($atts['imagen']); ?>" alt="<?php echo esc_attr($atts['titulo']); ?>"
                loading="lazy">
        </div>
        <div class="wild-tour__content">
            <span class="wild-tour__type-label"><?php echo esc_html($atts['numero']); ?> ·
                <?php echo esc_html($atts['label']); ?></span>
            <h3 class="wild-tour__title"><?php echo esc_html($atts['titulo']); ?></h3>
            <p class="wild-tour__subtitle"><?php echo esc_html($atts['subtitulo']); ?></p>
            <p class="wild-tour__desc"><?php echo esc_html($atts['descripcion']); ?></p>

            <div class="wild-tour__meta">
                <div class="wild-tour__meta-item">
                    <svg class="wild-tour__meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    <span class="wild-tour__meta-label">Duración</span>
                    <span class="wild-tour__meta-value"><?php echo esc_html($atts['duracion']); ?></span>
                </div>
                <div class="wild-tour__meta-item">
                    <svg class="wild-tour__meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    <span class="wild-tour__meta-label">Tamaño del Grupo</span>
                    <span class="wild-tour__meta-value"><?php echo esc_html($atts['grupo']); ?></span>
                </div>
                <div class="wild-tour__meta-item">
                    <svg class="wild-tour__meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M12 2L2 7l10 5 10-5-10-5z" />
                        <path d="M2 17l10 5 10-5" />
                        <path d="M2 12l10 5 10-5" />
                    </svg>
                    <span class="wild-tour__meta-label">Dificultad</span>
                    <span class="wild-tour__meta-value"><?php echo esc_html($atts['dificultad']); ?></span>
                </div>
            </div>

            <a href="<?php echo esc_url($atts['link']); ?>" class="wild-tour__btn">
                <?php echo esc_html($atts['btn_texto']); ?>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
    <?php
    return ob_get_clean();
});

/**
 * Shortcode: [wild_tours_container]
 * Envuelve los [wild_tour] para que el JS funcione correctamente.
 */
add_shortcode('wild_tours_container', function ($atts, $content = null) {
    return '<div class="wild-tours">' . do_shortcode($content) . '</div>';
});



/**
 * Shortcode: [we_tour_modal_form]
 * Renderiza el modal con formulario CF7 de solicitud de información.
 *
 * Uso: [we_tour_modal_form cf7_id="123"]
 */

add_shortcode('we_tour_modal_form', function ($atts) {
    $atts = shortcode_atts([
        'cf7_id' => 2112133, // ← tu ID de CF7
    ], $atts, 'we_tour_modal_form');

    ob_start();
    dg_render_tour_request_modal(intval($atts['cf7_id']));
    return ob_get_clean();
});

function dg_render_tour_request_modal($cf7_id = 2112133)
{
    static $rendered = false;

    if ($rendered) {
        return;
    }

    $rendered = true;

    if (function_exists('wpcf7_enqueue_scripts')) {
        wpcf7_enqueue_scripts();
        wpcf7_enqueue_styles();
    }

    ?>
    <div id="we-tour-modal" class="we-modal" style="display:none;" aria-hidden="true">
        <div class="we-modal__overlay" onclick="weCloseModal()"></div>
        <div class="we-modal__content">
            <button type="button" class="we-modal__close" onclick="weCloseModal()" aria-label="Cerrar">×</button>
            <h3 class="we-modal__title">Solicitar Información</h3>
            <p class="we-modal__subtitle">Completa el formulario y te enviaremos los detalles de este programa.</p>

            <?php echo do_shortcode('[contact-form-7 id="' . intval($cf7_id) . '" html_class="we-cf7-form we-cf7-tour-form" html_id="we-cf7-tour-form"]'); ?>
        </div>
    </div>
    <?php
}

/**
 * Shortcode: [we_tours categoria="slug" titulo="Opcional" subtitulo="Opcional"]
 *
 * Ejemplos:
 * [we_tours categoria="birding" titulo="Tours de Avistamiento"]
 * [we_tours categoria="nature" titulo="Tours de Naturaleza"]
 * [we_tours categoria="hiking" titulo="Tours de Senderismo"]
 * [we_tours categoria="culture" titulo="Tours Culturales"]
 */







add_shortcode('we_tours', function ($atts) {
    $atts = shortcode_atts([
        'categoria' => '',
        'titulo' => '',
        'subtitulo' => 'expediciones seleccionadas',
        'cf7_id' => 2112133,
    ], $atts, 'we_tours');

    if (empty($atts['categoria'])) {
        return '<p style="color:#c62828; text-align:center; padding:20px;">Error: Debes especificar una categoría. Ej: [we_tours categoria="birding"]</p>';
    }

    $term = get_term_by('slug', $atts['categoria'], 'tour_category');
    if (!$term) {
        return '<p style="color:#c62828; text-align:center; padding:20px;">Error: La categoría "' . esc_html($atts['categoria']) . '" no existe.</p>';
    }

    $args = [
        'post_type' => 'tour',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
        'tax_query' => [
            [
                'taxonomy' => 'tour_category',
                'field' => 'slug',
                'terms' => $atts['categoria'],
            ]
        ],
    ];

    $tours = new WP_Query($args);

    if (!$tours->have_posts()) {
        return '<p style="text-align:center; padding:60px 20px; color:#888;">No hay programas disponibles en esta categoría.</p>';
    }

    $titulo = !empty($atts['titulo']) ? $atts['titulo'] : $term->count . ' ' . $atts['subtitulo'];

    ob_start();
    ?>
    <section class="we-cat-programs" style="padding-top:0; padding-bottom:0;">
        <div class="we-cat-programs__header">
            <span class="we-section-label">Programas</span>
            <h2 class="we-section-title"><?php echo esc_html($titulo); ?></h2>
        </div>

        <div class="we-programs-list">
            <?php while ($tours->have_posts()):
                $tours->the_post();
                $pid = get_the_ID();
                // ===== META KEYS CORREGIDOS (igual que el admin) =====
                $price = get_post_meta($pid, 'tour_price', true);
                $duration = get_post_meta($pid, 'tour_duration', true) ?: '5-14 días';
                $group = get_post_meta($pid, 'tour_max_people', true) ?: 'Máx. 6';
                $difficulty = get_post_meta($pid, 'tour_difficulty', true) ?: 'Fácil-Moderado';
                $dates = get_post_meta($pid, 'tour_dates', true);
                $pdf = get_post_meta($pid, '_we_pdf_url', true);
                $image = get_the_post_thumbnail_url($pid, 'large') ?: '';
                $duration_display = dg_translate_tour_value($duration, 'duration');
                $group_display = dg_translate_tour_value($group, 'group');
                $difficulty_display = dg_translate_tour_value($difficulty, 'difficulty');
                $price_display = dg_format_price($price);
                $dates_display = dg_translate_tour_value($dates, 'dates');
                ?>
                <article class="we-program-card">
                    <div class="we-program-card__image">
                        <?php if ($image): ?>
                            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy">
                        <?php endif; ?>
                    </div>
                    <div class="we-program-card__content">
                        <h3 class="we-program-card__title"><?php the_title(); ?></h3>
                        <p class="we-program-card__desc"><?php echo wp_trim_words(get_the_excerpt(), 28); ?></p>

                        <div class="we-program-meta">
                            <?php if ($duration_display): ?>
                                <div class="we-program-meta__item">
                                    <span
                                        class="we-program-meta__label"><?php echo dg_get_tour_meta_icon('duration'); ?>Duración</span>
                                    <span class="we-program-meta__value"><?php echo esc_html($duration_display); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($group_display): ?>
                                <div class="we-program-meta__item">
                                    <span class="we-program-meta__label"><?php echo dg_get_tour_meta_icon('group'); ?>Grupo</span>
                                    <span class="we-program-meta__value"><?php echo esc_html($group_display); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($difficulty_display): ?>
                                <div class="we-program-meta__item">
                                    <span
                                        class="we-program-meta__label"><?php echo dg_get_tour_meta_icon('difficulty'); ?>Dificultad</span>
                                    <span class="we-program-meta__value"><?php echo esc_html($difficulty_display); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($price_display): ?>
                                <div class="we-program-meta__item">
                                    <span class="we-program-meta__label"><?php echo dg_get_tour_meta_icon('price'); ?>Precio</span>
                                    <span class="we-program-meta__value"><?php echo esc_html($price_display); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($dates_display): ?>
                                <div class="we-program-meta__item">
                                    <span class="we-program-meta__label"><?php echo dg_get_tour_meta_icon('dates'); ?>Fechas</span>
                                    <span class="we-program-meta__value"><?php echo esc_html($dates_display); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- ===== BOTONES CORREGIDOS ===== -->
                        <div class="we-program-card__actions we-program-card__actions--three">

                            <!-- Botón 1: Tour Details (verde, ancho completo) -->
                            <button type="button" class="we-btn-green we-btn-details" data-tour-id="<?php echo $pid; ?>">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16"
                                    height="16">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 16v-4M12 8h.01" />
                                </svg>
                                Ver detalles
                            </button>

                            <!-- Fila inferior: 2 botones -->
                            <div class="we-program-card__actions-row">
                                <button type="button" class="we-btn-terracota"
                                    onclick="weOpenModal(<?php echo $pid; ?>, '<?php echo esc_js(get_the_title()); ?>')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16"
                                        height="16">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                        <polyline points="22,6 12,13 2,6" />
                                    </svg>
                                    Solicitar información
                                </button>

                                <?php if ($pdf): ?>
                                    <a href="<?php echo esc_url($pdf); ?>" target="_blank" class="we-btn-outline">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16"
                                            height="16">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                            <polyline points="7 10 12 15 17 10" />
                                            <line x1="12" y1="15" x2="12" y2="3" />
                                        </svg>
                                        Descargar PDF
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>
    </section>
    <?php
    dg_render_tour_modal();
    dg_render_tour_request_modal(intval($atts['cf7_id']));
    return ob_get_clean();
});

/**
 * Renderiza el contenedor vacío del modal (se rellena vía JS)
 */
function dg_render_tour_modal()
{
    static $rendered = false;
    if ($rendered)
        return;
    $rendered = true;
    ?>
    <!-- Tour Details Modal -->
    <div id="tour-details-modal" class="tour-details-modal" style="display:none;">
        <div class="tour-modal-overlay">
            <button class="tour-modal-close-btn" id="tour-modal-close" aria-label="Cerrar">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>

            <!-- Encabezado oscuro -->
            <header class="tour-modal-header">
                <div class="tour-modal-header__inner">
                    <nav class="tour-breadcrumbs">
                        <a href="<?php echo esc_url(home_url('/')); ?>">Inicio</a>
                        <span>/</span>
                        <a href="<?php echo esc_url(home_url('/tours')); ?>">Tours</a>
                        <span>/</span>
                        <a id="tour-breadcrumb-name" href="<?php echo esc_url(home_url('/tours')); ?>">Tour</a>
                    </nav>
                    <div class="tour-header-grid">
                        <div class="tour-header-left">
                            <div class="tour-header-meta-line">
                                <span class="tour-sage-line"></span>
                                <span id="tour-header-coords" class="tour-mono-label"></span>
                                <span class="tour-expedition-label">Expedición</span>
                            </div>
                            <h1 id="tour-modal-title" class="tour-modal-title"></h1>
                            <p id="tour-modal-short-desc" class="tour-modal-short-desc"></p>
                            <div id="tour-attributes-list" class="tour-attributes-list"></div>
                            <div class="tour-header-actions">
                                <button class="tour-btn-inquiry" id="tour-btn-inquiry-header">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path
                                            d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                        <polyline points="22,6 12,13 2,6" />
                                    </svg>
                                    Enviar consulta
                                </button>
                            </div>
                        </div>
                        <div class="tour-header-right">
                            <div class="tour-gallery-main">
                                <img id="gallery-main-img" src="" alt="">
                                <span id="gallery-counter" class="gallery-counter">1 / 4</span>
                                <button id="gallery-prev" class="gallery-nav gallery-prev">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polyline points="15 18 9 12 15 6" />
                                    </svg>
                                </button>
                                <button id="gallery-next" class="gallery-nav gallery-next">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polyline points="9 18 15 12 9 6" />
                                    </svg>
                                </button>
                            </div>
                            <span class="tour-gallery-label">GALERÍA</span>
                            <div id="gallery-thumbnails" class="gallery-thumbnails"></div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Sticky Nav -->
            <nav class="tour-jump-nav" id="tour-jump-nav">
                <div class="tour-jump-nav__inner">
                    <button class="tour-jump-to-pill" data-section="gallery"><svg width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <path d="M21 15l-5-5L5 21" />
                        </svg>Galería</button>
                    <button class="tour-jump-to-pill" data-section="about"><svg width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="16" x2="12" y2="12" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                        </svg>Acerca</button>
                    <button class="tour-jump-to-pill" data-section="wildlife"><svg width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>Fauna</button>
                    <button class="tour-jump-to-pill" data-section="organizer"><svg width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>Organizador</button>
                    <button class="tour-jump-to-pill" data-section="itinerary"><svg width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="8" y1="6" x2="21" y2="6" />
                            <line x1="8" y1="12" x2="21" y2="12" />
                            <line x1="8" y1="18" x2="21" y2="18" />
                            <line x1="3" y1="6" x2="3.01" y2="6" />
                            <line x1="3" y1="12" x2="3.01" y2="12" />
                            <line x1="3" y1="18" x2="3.01" y2="18" />
                        </svg>Itinerario</button>
                    <button class="tour-jump-to-pill" data-section="locations"><svg width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>Ubicaciones</button>
                    <button class="tour-jump-to-pill" data-section="availability"><svg width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>Disponibilidad</button>
                    <button class="tour-jump-to-pill" data-section="includes"><svg width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>Incluye</button>
                    <button class="tour-jump-to-pill" data-section="transport"><svg width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="1" y="3" width="15" height="13" />
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                            <circle cx="5.5" cy="18.5" r="2.5" />
                            <circle cx="18.5" cy="18.5" r="2.5" />
                        </svg>Transporte</button>
                    <button class="tour-jump-to-pill" data-section="policies"><svg width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>Políticas</button>
                    <button class="tour-jump-to-pill" data-section="inquiry"><svg width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>Consulta</button>
                </div>
            </nav>

            <!-- Contenido -->
            <main class="tour-modal-body">
                <div class="tour-modal-body__inner">

                    <!-- Galería -->
                    <section class="tour-section-card" id="section-gallery">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <path d="M21 15l-5-5L5 21" />
                            </svg>Galería</h2>
                        <div id="tour-gallery-grid" class="tour-gallery-grid"></div>
                    </section>

                    <!-- Acerca de este tour -->
                    <section class="tour-section-card" id="section-about">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>Acerca de esta expedición</h2>
                        <div id="tour-about-content"></div>
                    </section>

                    <!-- Fauna de la expedición -->
                    <section class="tour-section-card" id="section-wildlife">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg><span id="tour-wildlife-label-text">Fauna que puedes observar</span></h2>
                        <div id="tour-wildlife-grid" class="tour-wildlife-grid"></div>
                    </section>

                    <!-- Organizador y Guías -->
                    <section class="tour-section-card" id="section-organizer">
                        <div class="tour-two-col">
                            <div class="tour-org-card">
                                <h3 class="tour-subsection-title">Organizador</h3>
                                <div id="tour-organizer-content" class="tour-organizer-content"></div>
                            </div>
                            <div class="tour-guides-card">
                                <h3 class="tour-subsection-title">Guías</h3>
                                <div id="tour-guides-content" class="tour-guides-content"></div>
                            </div>
                        </div>
                    </section>

                    <!-- Itinerario -->
                    <section class="tour-section-card" id="section-itinerary">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="8" y1="6" x2="21" y2="6" />
                                <line x1="8" y1="12" x2="21" y2="12" />
                                <line x1="8" y1="18" x2="21" y2="18" />
                                <line x1="3" y1="6" x2="3.01" y2="6" />
                                <line x1="3" y1="12" x2="3.01" y2="12" />
                                <line x1="3" y1="18" x2="3.01" y2="18" />
                            </svg>Itinerario</h2>
                        <button class="tour-itinerary-toggle" id="itinerary-toggle">
                            <span id="itinerary-toggle-text"></span>
                            <svg class="toggle-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </button>
                        <div id="itinerary-content" class="tour-itinerary-content"></div>
                    </section>

                    <!-- Ubicaciones y Mapa -->
                    <section class="tour-section-card" id="section-locations">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>Ubicaciones y mapa</h2>
                        <div id="tour-locations-list" class="tour-locations-list"></div>
                        <div id="tour-map" class="tour-map"></div>
                    </section>

                    <!-- Disponibilidad y Precios -->
                    <section class="tour-section-card" id="section-availability">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>Disponibilidad y precio</h2>
                        <div id="tour-availability-list" class="tour-availability-list"></div>
                        <div class="tour-price-box">
                            <div>
                                <span class="tour-price-label">PRECIO DESDE</span>
                                <span id="tour-price-value" class="tour-price-value"></span>
                            </div>
                            <button type="button" class="tour-btn-inquiry-sm" id="tour-btn-inquiry-price">Enviar
                                consulta</button>
                        </div>
                    </section>

                    <!-- Incluido y No Incluido -->
                    <section class="tour-section-card" id="section-includes">
                        <div class="tour-two-col">
                            <div>
                                <h3 class="tour-subsection-title"><svg width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="#2E6A4F" stroke-width="2">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                        <polyline points="22 4 12 14.01 9 11.01" />
                                    </svg> Incluye</h3>
                                <div id="tour-includes-list" class="tour-check-list"></div>
                            </div>
                            <div>
                                <h3 class="tour-subsection-title"><svg width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="#E56F51" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="15" y1="9" x2="9" y2="15" />
                                        <line x1="9" y1="9" x2="15" y2="15" />
                                    </svg> No incluye</h3>
                                <div id="tour-excludes-list" class="tour-check-list tour-check-list--exclude"></div>
                            </div>
                        </div>
                    </section>

                    <!-- Transporte y Alojamiento -->
                    <section class="tour-section-card" id="section-transport">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="1" y="3" width="15" height="13" />
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                <circle cx="5.5" cy="18.5" r="2.5" />
                                <circle cx="18.5" cy="18.5" r="2.5" />
                            </svg>Transporte y alojamiento</h2>
                        <div class="tour-transport-grid">
                            <div class="tour-transport-block">
                                <span class="tour-transport-label">TRANSPORTE</span>
                                <p id="tour-transport-value"></p>
                            </div>
                            <div class="tour-transport-block">
                                <span class="tour-transport-label">ALOJAMIENTO</span>
                                <p id="tour-accommodation-value"></p>
                            </div>
                            <div class="tour-transport-block">
                                <span class="tour-transport-label">RITMO</span>
                                <p id="tour-pace-value"></p>
                            </div>
                        </div>
                    </section>

                    <!-- Políticas y FAQ -->
                    <section class="tour-section-card" id="section-policies">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                <line x1="12" y1="17" x2="12.01" y2="17" />
                            </svg>Políticas y preguntas frecuentes</h2>
                        <div class="tour-policies-box">
                            <span class="tour-policies-label">RESERVA Y CANCELACIÓN</span>
                            <div id="tour-policies-text"></div>
                        </div>
                        <div class="tour-faq-divider" id="tour-faq-divider"></div>
                        <h3 class="tour-subsection-title tour-faq-heading" id="tour-faq-heading">Preguntas frecuentes</h3>
                        <div id="tour-faq-list" class="tour-faq-list"></div>
                    </section>

                    <!-- Llamada a Consulta -->
                    <section class="tour-inquiry-cta" id="section-inquiry">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#63B78B" stroke-width="1.5">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        <h2>¿Listo para reservar esta <em>expedición</em>?</h2>
                        <p>Envíanos una consulta y nuestro equipo te responderá en menos de 24 horas con disponibilidad y
                            próximos pasos.</p>
                        <div class="tour-inquiry-buttons">
                            <button type="button" class="tour-btn-inquiry-lg" id="tour-btn-inquiry-cta">Enviar
                                consulta</button>
                            <a href="<?php echo esc_url(home_url('/tours')); ?>" class="tour-btn-outline">Ver todos los
                                tours</a>
                        </div>
                    </section>
                    <div id="tour-image-lightbox" class="tour-image-lightbox" aria-hidden="true">
                        <button type="button" class="tour-image-lightbox__close" id="tour-image-lightbox-close"
                            aria-label="Cerrar imagen">×</button>
                        <div class="tour-image-lightbox__inner">
                            <img id="tour-image-lightbox-img" src="" alt="">
                            <p id="tour-image-lightbox-caption" class="tour-image-lightbox__caption"></p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php
}
;




/**
 * Shortcode: [dualgrid_galeria]
 * Muestra TODAS las categorías creadas como filtros, aunque estén vacías.
 */
add_shortcode('dualgrid_galeria', function ($atts) {
    $atts = shortcode_atts([
        'categoria' => 'all',
    ], $atts, 'dualgrid_galeria');

    // Obtener TODAS las categorías (incluso sin fotos) para los filtros
    $terms = get_terms([
        'taxonomy' => 'dg_gallery_cat',
        'hide_empty' => false, // ← CAMBIO CLAVE: muestra categorías vacías también
    ]);

    if (is_wp_error($terms)) {
        $terms = [];
    }

    // Query de imágenes
    $args = [
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    ];

    // Si se pasa categoría por shortcode, filtrar
    if (!empty($atts['categoria']) && $atts['categoria'] !== 'all') {
        $args['tax_query'] = [
            [
                'taxonomy' => 'dg_gallery_cat',
                'field' => 'slug',
                'terms' => array_map('trim', explode(',', $atts['categoria'])),
            ]
        ];
    }

    $query = new WP_Query($args);

    ob_start();
    $gallery_id = uniqid('dg-gallery-');
    ?>
    <div class="dg-gallery-wrapper" id="<?php echo esc_attr($gallery_id); ?>">

        <!-- Filtros: siempre muestra todas las categorías creadas -->
        <div class="dg-gallery-filters">
            <button class="dg-filter-btn active" data-filter="all">All</button>
            <?php foreach ($terms as $term): ?>
                <button class="dg-filter-btn" data-filter="<?php echo esc_attr($term->slug); ?>">
                    <?php echo esc_html($term->name); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Grid Masonry -->
        <div class="dg-gallery-grid">
            <?php
            $index = 0;
            while ($query->have_posts()):
                $query->the_post();
                $post_id = get_the_ID();
                $img_url = wp_get_attachment_url($post_id);
                $img_full = wp_get_attachment_image_src($post_id, 'large');
                $img_full = $img_full ? $img_full[0] : $img_url;
                $title = get_the_title($post_id);
                $location = get_post_meta($post_id, '_dg_location', true);

                $item_terms = get_the_terms($post_id, 'dg_gallery_cat');
                $cat_slugs = [];
                $cat_names = [];
                if (!empty($item_terms) && !is_wp_error($item_terms)) {
                    foreach ($item_terms as $t) {
                        $cat_slugs[] = $t->slug;
                        $cat_names[] = $t->name;
                    }
                }

                $cats = implode(' ', $cat_slugs);
                $cat_label = !empty($cat_names) ? implode(' · ', $cat_names) : '';
                $subtitle = trim(($location ? $location . ' · ' : '') . $cat_label, ' ·');
                ?>
                <div class="dg-gallery-item" data-categories="<?php echo esc_attr($cats); ?>"
                    data-index="<?php echo (int) $index; ?>" data-full="<?php echo esc_url($img_full); ?>"
                    data-title="<?php echo esc_attr($title); ?>" data-subtitle="<?php echo esc_attr($subtitle); ?>">
                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
                    <div class="dg-item-overlay">
                        <div class="dg-item-info">
                            <h3><?php echo esc_html($title); ?></h3>
                            <?php if ($subtitle): ?>
                                <span><?php echo esc_html($subtitle); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
                $index++;
            endwhile;
            wp_reset_postdata();
            ?>
        </div>

        <!-- Lightbox -->
        <div class="dg-lightbox" aria-hidden="true">
            <button class="dg-lightbox-close" aria-label="Cerrar">&times;</button>
            <button class="dg-lightbox-prev" aria-label="Anterior">&#8249;</button>
            <button class="dg-lightbox-next" aria-label="Siguiente">&#8250;</button>

            <div class="dg-lightbox-content">
                <img src="" alt="" class="dg-lightbox-img">
                <div class="dg-lightbox-caption">
                    <h3 class="dg-lightbox-title"></h3>
                    <p class="dg-lightbox-subtitle"></p>
                </div>
            </div>
        </div>

    </div>
    <?php
    return ob_get_clean();
});



/* ============================================================
   DUALGRID BLOG — Shortcode + CSS + JS (v4.1 — Fix enlaces)
   Usa IDs explícitos para evitar conflictos con Elementor/WP
   ============================================================ */

/**
 * Shortcode: [dualgrid_blog]
 * Grid con post destacado horizontal + filas de 3 y 2 columnas
 */
add_shortcode('dualgrid_blog', function ($atts) {
    $atts = shortcode_atts([
        'posts_per_page' => 9,
    ], $atts, 'dualgrid_blog');

    // Obtener categorías con posts
    $categories = get_categories([
        'orderby' => 'name',
        'order' => 'ASC',
        'number' => 10,
    ]);

    // Query de posts
    $paged = max(1, get_query_var('paged'));
    $args = [
        'post_type' => 'post',
        'posts_per_page' => intval($atts['posts_per_page']),
        'paged' => $paged,
        'post_status' => 'publish',
    ];

    $query = new WP_Query($args);
    $posts = $query->posts;

    ob_start();
    ?>
    <section class="dg-blog-section">
        <div class="dg-blog-container">

            <!-- Header: Buscador + Filtros -->
            <div class="dg-blog-header">
                <div class="dg-blog-search">
                    <input type="text" id="dg-blog-search" placeholder="Buscar artículos..." autocomplete="off">
                </div>
                <div class="dg-blog-filters">
                    <button class="dg-filter-btn active" data-filter="all">Todos</button>
                    <?php foreach ($categories as $cat): ?>
                        <button class="dg-filter-btn" data-filter="<?php echo esc_attr($cat->slug); ?>">
                            <?php echo esc_html($cat->name); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if ($query->have_posts()): ?>

                <?php
                // === POST DESTACADO (primero) ===
                $featured = array_shift($posts);
                setup_postdata($featured);
                $f_cats = get_the_category($featured->ID);
                $f_cat_slug = !empty($f_cats) ? $f_cats[0]->slug : '';
                $f_cat_name = !empty($f_cats) ? $f_cats[0]->name : 'Artículo';
                $f_thumb = get_the_post_thumbnail_url($featured->ID, 'large');
                if (!$f_thumb)
                    $f_thumb = 'https://via.placeholder.com/800x600/18382E/FFFFFF?text=DualGrid';
                $content = get_post_field('post_content', $featured->ID);
                $word_count = str_word_count(strip_tags($content));
                $read_time = max(1, ceil($word_count / 200));
                ?>
                <a href="<?php echo esc_url(get_permalink($featured->ID)); ?>" class="dg-featured-card"
                    data-category="<?php echo esc_attr($f_cat_slug); ?>"
                    data-title="<?php echo esc_attr(strtolower(get_the_title($featured->ID))); ?>">
                    <div class="dg-featured-img">
                        <img src="<?php echo esc_url($f_thumb); ?>" alt="<?php echo esc_attr(get_the_title($featured->ID)); ?>">
                        <span class="dg-img-badge"><?php echo esc_html($f_cat_name); ?></span>
                    </div>
                    <div class="dg-featured-body">
                        <div class="dg-featured-meta-top">
                            <span class="dg-featured-badge"><?php echo esc_html($f_cat_name); ?></span>
                            <span class="dg-featured-readtime"><?php echo $read_time; ?> min de lectura</span>
                        </div>
                        <h2 class="dg-featured-title"><?php echo esc_html(get_the_title($featured->ID)); ?></h2>
                        <p class="dg-featured-excerpt"><?php echo wp_trim_words(get_the_excerpt($featured->ID), 28, '...'); ?>
                        </p>
                        <div class="dg-featured-author-row">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            <span><?php echo esc_html(get_the_author_meta('display_name', $featured->post_author)); ?></span>
                            <span style="opacity:0.4">|</span>
                            <span><?php echo get_the_date('M j, Y', $featured->ID); ?></span>
                        </div>
                        <span class="dg-featured-read">Leer artículo →</span>
                    </div>
                </a>

                <?php
                // === GRID 3 COLUMNAS (posts 2-4) ===
                $row3 = array_splice($posts, 0, 3);
                if (!empty($row3)):
                    ?>
                    <div class="dg-blog-grid" id="dg-blog-grid">
                        <?php foreach ($row3 as $post):
                            setup_postdata($post); ?>
                            <?php
                            $post_cats = get_the_category($post->ID);
                            $cat_slug = !empty($post_cats) ? $post_cats[0]->slug : '';
                            $cat_name = !empty($post_cats) ? $post_cats[0]->name : 'Artículo';
                            $thumb = get_the_post_thumbnail_url($post->ID, 'medium_large');
                            if (!$thumb)
                                $thumb = 'https://via.placeholder.com/800x600/18382E/FFFFFF?text=DualGrid';
                            $content_p = get_post_field('post_content', $post->ID);
                            $wc = str_word_count(strip_tags($content_p));
                            $rt = max(1, ceil($wc / 200));
                            ?>
                            <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="dg-card"
                                data-category="<?php echo esc_attr($cat_slug); ?>"
                                data-title="<?php echo esc_attr(strtolower(get_the_title($post->ID))); ?>">
                                <div class="dg-card-img">
                                    <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title($post->ID)); ?>">
                                    <span class="dg-img-badge"><?php echo esc_html($cat_name); ?></span>
                                </div>
                                <div class="dg-card-body">
                                    <div class="dg-card-meta-top">
                                        <span class="dg-card-badge"><?php echo esc_html($cat_name); ?></span>
                                        <span class="dg-card-readtime"><?php echo $rt; ?> min de lectura</span>
                                    </div>
                                    <h3 class="dg-card-title"><?php echo esc_html(get_the_title($post->ID)); ?></h3>
                                    <p class="dg-card-excerpt"><?php echo wp_trim_words(get_the_excerpt($post->ID), 18, '...'); ?></p>
                                    <div class="dg-card-meta-bottom">
                                        <span
                                            class="author"><?php echo esc_html(get_the_author_meta('display_name', $post->post_author)); ?></span>
                                        <span class="sep"></span>
                                        <span class="date"><?php echo get_the_date('M j, Y', $post->ID); ?></span>
                                    </div>
                                    <span class="dg-card-read">Leer artículo →</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php
                // === GRID 2 COLUMNAS (posts 5-6) ===
                $row2 = array_splice($posts, 0, 2);
                if (!empty($row2)):
                    ?>
                    <div class="dg-blog-grid-two" id="dg-blog-grid-two">
                        <?php foreach ($row2 as $post):
                            setup_postdata($post); ?>
                            <?php
                            $post_cats = get_the_category($post->ID);
                            $cat_slug = !empty($post_cats) ? $post_cats[0]->slug : '';
                            $cat_name = !empty($post_cats) ? $post_cats[0]->name : 'Artículo';
                            $thumb = get_the_post_thumbnail_url($post->ID, 'medium_large');
                            if (!$thumb)
                                $thumb = 'https://via.placeholder.com/800x600/18382E/FFFFFF?text=DualGrid';
                            $content_p = get_post_field('post_content', $post->ID);
                            $wc = str_word_count(strip_tags($content_p));
                            $rt = max(1, ceil($wc / 200));
                            ?>
                            <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="dg-card"
                                data-category="<?php echo esc_attr($cat_slug); ?>"
                                data-title="<?php echo esc_attr(strtolower(get_the_title($post->ID))); ?>">
                                <div class="dg-card-img">
                                    <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title($post->ID)); ?>">
                                    <span class="dg-img-badge"><?php echo esc_html($cat_name); ?></span>
                                </div>
                                <div class="dg-card-body">
                                    <div class="dg-card-meta-top">
                                        <span class="dg-card-badge"><?php echo esc_html($cat_name); ?></span>
                                        <span class="dg-card-readtime"><?php echo $rt; ?> min de lectura</span>
                                    </div>
                                    <h3 class="dg-card-title"><?php echo esc_html(get_the_title($post->ID)); ?></h3>
                                    <p class="dg-card-excerpt"><?php echo wp_trim_words(get_the_excerpt($post->ID), 18, '...'); ?></p>
                                    <div class="dg-card-meta-bottom">
                                        <span
                                            class="author"><?php echo esc_html(get_the_author_meta('display_name', $post->post_author)); ?></span>
                                        <span class="sep"></span>
                                        <span class="date"><?php echo get_the_date('M j, Y', $post->ID); ?></span>
                                    </div>
                                    <span class="dg-card-read">Leer artículo →</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php
                // === RESTO DE POSTS (si hay más) ===
                if (!empty($posts)):
                    $remaining_chunks = array_chunk($posts, 3);
                    foreach ($remaining_chunks as $chunk):
                        ?>
                        <div class="dg-blog-grid" style="margin-top:32px;">
                            <?php foreach ($chunk as $post):
                                setup_postdata($post); ?>
                                <?php
                                $post_cats = get_the_category($post->ID);
                                $cat_slug = !empty($post_cats) ? $post_cats[0]->slug : '';
                                $cat_name = !empty($post_cats) ? $post_cats[0]->name : 'Artículo';
                                $thumb = get_the_post_thumbnail_url($post->ID, 'medium_large');
                                if (!$thumb)
                                    $thumb = 'https://via.placeholder.com/800x600/18382E/FFFFFF?text=DualGrid';
                                $content_p = get_post_field('post_content', $post->ID);
                                $wc = str_word_count(strip_tags($content_p));
                                $rt = max(1, ceil($wc / 200));
                                ?>
                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="dg-card"
                                    data-category="<?php echo esc_attr($cat_slug); ?>"
                                    data-title="<?php echo esc_attr(strtolower(get_the_title($post->ID))); ?>">
                                    <div class="dg-card-img">
                                        <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title($post->ID)); ?>">
                                        <span class="dg-img-badge"><?php echo esc_html($cat_name); ?></span>
                                    </div>
                                    <div class="dg-card-body">
                                        <div class="dg-card-meta-top">
                                            <span class="dg-card-badge"><?php echo esc_html($cat_name); ?></span>
                                            <span class="dg-card-readtime"><?php echo $rt; ?> min de lectura</span>
                                        </div>
                                        <h3 class="dg-card-title"><?php echo esc_html(get_the_title($post->ID)); ?></h3>
                                        <p class="dg-card-excerpt"><?php echo wp_trim_words(get_the_excerpt($post->ID), 18, '...'); ?></p>
                                        <div class="dg-card-meta-bottom">
                                            <span
                                                class="author"><?php echo esc_html(get_the_author_meta('display_name', $post->post_author)); ?></span>
                                            <span class="sep"></span>
                                            <span class="date"><?php echo get_the_date('M j, Y', $post->ID); ?></span>
                                        </div>
                                        <span class="dg-card-read">Leer artículo →</span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <?php
                    endforeach;
                endif;
                ?>

            <?php else: ?>
                <div class="dg-blog-empty visible">No se encontraron artículos.</div>
            <?php endif; ?>

            <div class="dg-blog-empty" id="dg-blog-empty">No se encontraron artículos.</div>

            <?php if ($query->max_num_pages > 1): ?>
                <div class="dg-pagination" style="text-align:center; margin-top:48px;">
                    <?php
                    echo paginate_links([
                        'total' => $query->max_num_pages,
                        'current' => $paged,
                        'prev_text' => '← Anterior',
                        'next_text' => 'Siguiente →',
                    ]);
                    ?>
                </div>
            <?php endif; ?>

        </div>
    </section>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
});

/**
 * Shortcode: [dualgrid_single]
 * Hero ancho completo + breadcrumbs + contenido + share + related
 * Breadcrumbs: Home > Journal > Título (sin categoría intermedia)
 */
add_shortcode('dualgrid_single', function ($atts) {
    if (!is_singular('post')) {
        return '<p style="text-align:center; padding:40px;">Este shortcode solo funciona en entradas individuales.</p>';
    }

    $post_id = get_the_ID();
    $categories = get_the_category($post_id);
    $cat_name = !empty($categories) ? $categories[0]->name : 'Blog';
    $thumb_url = has_post_thumbnail() ? get_the_post_thumbnail_url($post_id, 'full') : '';

    // Breadcrumbs: Home > Journal > Título actual
    $blog_page = get_permalink(get_option('page_for_posts')) ?: home_url('/blog');
    // Breadcrumbs: Home > Blog > Título actual
    $breadcrumbs = [
        ['label' => 'Inicio', 'url' => home_url('/')],
        ['label' => 'Blog', 'url' => home_url('/blog')],
    ];
    $breadcrumbs[] = ['label' => get_the_title(), 'url' => '', 'current' => true];

    // Related posts
    $related = [];
    if (!empty($categories)) {
        $related_query = new WP_Query([
            'post_type' => 'post',
            'posts_per_page' => 3,
            'post__not_in' => [$post_id],
            'category__in' => wp_list_pluck($categories, 'term_id'),
            'orderby' => 'rand',
        ]);
        $related = $related_query->posts;
        wp_reset_postdata();
    }

    $share_url = urlencode(get_permalink());
    $share_title = urlencode(get_the_title());

    ob_start();
    ?>

    <!-- HERO ANCHO COMPLETO -->
    <section class="dg-single-hero">
        <?php if ($thumb_url): ?>
            <div class="dg-single-hero-bg" style="background-image: url('<?php echo esc_url($thumb_url); ?>');"></div>
        <?php else: ?>
            <div class="dg-single-hero-bg" style="background-color: #18382E;"></div>
        <?php endif; ?>
        <div class="dg-single-hero-content">
            <span class="dg-single-category"><?php echo esc_html($cat_name); ?></span>
            <h1 class="dg-single-title"><?php the_title(); ?></h1>
            <div class="dg-single-meta">
                <span><?php the_author(); ?></span>
                <span class="sep"></span>
                <span><?php echo get_the_date('M j, Y'); ?></span>
            </div>
        </div>
    </section>

    <!-- BREADCRUMBS -->
    <nav class="dg-breadcrumbs" aria-label="Breadcrumb">
        <div class="dg-breadcrumbs-inner">
            <?php foreach ($breadcrumbs as $i => $crumb): ?>
                <?php if (!$crumb['current']): ?>
                    <a href="<?php echo esc_url($crumb['url']); ?>"><?php echo esc_html($crumb['label']); ?></a>
                    <span class="dg-bc-sep">›</span>
                <?php else: ?>
                    <span class="dg-bc-current"><?php echo esc_html($crumb['label']); ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </nav>

    <!-- CONTENIDO -->
    <article class="dg-single-content">
        <?php the_content(); ?>
    </article>

    <!-- SHARE -->
    <div class="dg-single-share">
        <p class="dg-share-label">Comparte este artículo</p>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr($share_url); ?>&quote=<?php echo esc_attr($share_title); ?>"
            target="_blank" rel="noopener noreferrer" class="dg-btn-facebook">
            <svg viewBox="0 0 24 24">
                <path
                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
            </svg>
            Compartir en Facebook
        </a>
    </div>

    <!-- RELATED POSTS -->
    <?php if (!empty($related)): ?>
        <section class="dg-related-section">
            <div class="dg-related-header">
                <h3>Más del blog</h3>
                <a href="<?php echo esc_url(home_url('/blog')); ?>">Ver todo →</a>
            </div>
            <div class="dg-related-grid">
                <?php foreach ($related as $rel_post):
                    setup_postdata($GLOBALS['post'] = $rel_post); ?>
                    <?php
                    $rel_cats = get_the_category($rel_post->ID);
                    $rel_cat = !empty($rel_cats) ? $rel_cats[0]->name : 'Artículo';
                    ?>
                    <a href="<?php the_permalink(); ?>" class="dg-card">
                        <div class="dg-card-img">
                            <?php if (has_post_thumbnail($rel_post->ID)): ?>
                                <?php echo get_the_post_thumbnail($rel_post->ID, 'medium_large', ['alt' => get_the_title($rel_post->ID)]); ?>
                            <?php else: ?>
                                <img src="https://via.placeholder.com/800x600/18382E/FFFFFF?text=DualGrid"
                                    alt="<?php echo esc_attr(get_the_title($rel_post->ID)); ?>">
                            <?php endif; ?>
                            <span class="dg-card-badge"><?php echo esc_html($rel_cat); ?></span>
                        </div>
                        <div class="dg-card-body">
                            <h2 class="dg-card-title"><?php echo esc_html(get_the_title($rel_post->ID)); ?></h2>
                            <p class="dg-card-excerpt"><?php echo wp_trim_words(get_the_excerpt($rel_post->ID), 14, '...'); ?></p>
                            <div class="dg-card-meta">
                                <span class="author"><?php the_author(); ?></span>
                                <span class="sep"></span>
                                <span class="date"><?php echo get_the_date('M j, Y', $rel_post->ID); ?></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach;
                wp_reset_postdata(); ?>
            </div>
        </section>
    <?php endif; ?>

    <?php
    return ob_get_clean();
});



/**
 * Shortcode: [we_contact_section]
 * Sección de contacto con formulario CF7.
 */
add_shortcode('we_contact_section', function ($atts) {
    $atts = shortcode_atts([
        'email' => get_option('admin_email'),
        'phone' => '+506 8888 8888',
        'location' => 'San José, Costa Rica',
        'response' => 'Respondemos a todas las consultas en menos de 24 horas. Para asuntos urgentes, llámanos directamente.',
        'heading' => 'Inicia la <em>conversación</em>',
        'description' => 'Cuéntanos sobre tu expedición soñada — a dónde quieres ir, qué quieres ver y cómo quieres sentirte. Crearemos algo extraordinario.',
        'cf7_id' => '4cbeadb',
    ], $atts, 'we_contact_section');

    // DEBUG: Verificar que CF7 está activo
    if (!function_exists('wpcf7_enqueue_scripts')) {
        return '<p style="color:red; padding:20px; background:#fee; border:1px solid red;"><strong>ERROR:</strong> Contact Form 7 no está activo o no se ha cargado.</p>';
    }

    // DEBUG: Verificar que el ID no está vacío
    $cf7_id = trim($atts['cf7_id']);
    if (empty($cf7_id)) {
        return '<p style="color:red; padding:20px; background:#fee; border:1px solid red;"><strong>ERROR:</strong> No se especificó el ID del formulario CF7.</p>';
    }

    // Encolar scripts de CF7
    wpcf7_enqueue_scripts();
    wpcf7_enqueue_styles();

    // Construir el shortcode exacto de CF7
    $cf7_shortcode = '[contact-form-7 id="' . $cf7_id . '"]';

    // DEBUG (quita esto después de probar): muestra el ID que está usando
    // $debug_info = '<!-- CF7 Debug: ID=' . $cf7_id . ' | Shortcode=' . $cf7_shortcode . ' -->';

    ob_start();
    ?>
    <section class="we-contact-section">
        <div class="we-contact-container">

            <div class="we-contact-info">
                <div class="we-contact-heading">
                    <h2><?php echo wp_kses_post($atts['heading']); ?></h2>
                    <p><?php echo esc_html($atts['description']); ?></p>
                </div>

                <div class="we-contact-details">
                    <div class="we-contact-item">
                        <div class="we-contact-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                        </div>
                        <div class="we-contact-text">
                            <span class="we-contact-label">Correo</span>
                            <a href="mailto:<?php echo esc_attr($atts['email']); ?>"
                                class="we-contact-value"><?php echo esc_html($atts['email']); ?></a>
                        </div>
                    </div>

                    <div class="we-contact-item">
                        <div class="we-contact-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                            </svg>
                        </div>
                        <div class="we-contact-text">
                            <span class="we-contact-label">Teléfono</span>
                            <a href="tel:<?php echo esc_attr(preg_replace('/\D/', '', $atts['phone'])); ?>"
                                class="we-contact-value"><?php echo esc_html($atts['phone']); ?></a>
                        </div>
                    </div>

                    <div class="we-contact-item">
                        <div class="we-contact-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                        </div>
                        <div class="we-contact-text">
                            <span class="we-contact-label">Base</span>
                            <span class="we-contact-value"><?php echo esc_html($atts['location']); ?></span>
                        </div>
                    </div>
                </div>

                <div class="we-contact-notice">
                    <span class="we-contact-notice-label">Tiempo de respuesta</span>
                    <p><?php echo esc_html($atts['response']); ?></p>
                </div>
            </div>

            <!-- Formulario CF7 -->
            <div class="we-contact-form-wrap">
                <?php
                $form_html = do_shortcode($cf7_shortcode);

                // Si CF7 devuelve vacío o el mensaje de "no encontrado", mostrar error
                if (empty($form_html) || strpos($form_html, 'No se ha encontrado') !== false || strpos($form_html, 'not found') !== false) {
                    echo '<p style="color:red; padding:20px; background:#fee; border:1px solid red;"><strong>ERROR CF7:</strong> No se pudo cargar el formulario con ID <code>' . esc_html($cf7_id) . '</code>.<br>Verifica que el ID sea correcto en Contact > Formularios de contacto.</p>';
                } else {
                    echo $form_html;
                }
                ?>
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
});



/**
 * Shortcode: [we_overview_section]
 * Sección Overview con sidebar "De un vistazo" + animaciones.
 */
add_shortcode('we_overview_section', function ($atts) {
    $atts = shortcode_atts([
        'label' => 'Vista general',
        'title' => 'La experiencia de',
        'title_italic' => 'avistamiento',
        'description' => 'Expediciones guiadas por expertos hacia los lugares más extraordinarios para avistar aves, desde quetzales en bosques nublados hasta guacamayos en collpas amazónicas. Nuestros programas son liderados por especialistas certificados que conocen cada sendero, cada canto y cada rincón escondido del territorio. Con grupos reducidos e itinerarios cuidadosamente diseñados, cada expedición equilibra la aventura con la comodidad, la educación con el asombro.',
        'sidebar_title' => 'De un vistazo',
        'duration' => '5–14 días',
        'group_size' => 'Máx. 6',
        'difficulty' => 'Fácil–Moderado',
    ], $atts, 'we_overview_section');

    ob_start();
    ?>
    <section class="we-overview-section">
        <div class="we-overview-container">

            <!-- Columna izquierda -->
            <div class="we-overview-main">
                <span class="we-overview-label we-anim-line">
                    <?php echo esc_html($atts['label']); ?>
                </span>
                <h2 class="we-overview-title we-anim we-anim-fade-up we-anim-d1">
                    <?php echo esc_html($atts['title']); ?> <em><?php echo esc_html($atts['title_italic']); ?></em>
                </h2>
                <p class="we-overview-text we-anim we-anim-fade-up we-anim-d2">
                    <?php echo esc_html($atts['description']); ?>
                </p>
            </div>

            <!-- Columna derecha -->
            <div class="we-overview-sidebar we-anim we-anim-fade-right we-anim-d2">
                <span class="we-sidebar-label we-anim we-anim-fade-up we-anim-d3">
                    <?php echo esc_html($atts['sidebar_title']); ?>
                </span>

                <div class="we-glance-list">

                    <!-- Duración -->
                    <div class="we-glance-item we-anim we-anim-fade-up we-anim-d3">
                        <div class="we-glance-icon">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </div>
                        <div class="we-glance-content">
                            <span class="we-glance-key">Duración</span>
                            <span class="we-glance-value"><?php echo esc_html($atts['duration']); ?></span>
                        </div>
                    </div>

                    <!-- Grupo -->
                    <div class="we-glance-item we-anim we-anim-fade-up we-anim-d4">
                        <div class="we-glance-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                        </div>
                        <div class="we-glance-content">
                            <span class="we-glance-key">Tamaño del grupo</span>
                            <span class="we-glance-value"><?php echo esc_html($atts['group_size']); ?></span>
                        </div>
                    </div>

                    <!-- Dificultad -->
                    <div class="we-glance-item we-anim we-anim-fade-up we-anim-d5">
                        <div class="we-glance-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5z" />
                                <path d="M2 17l10 5 10-5" />
                                <path d="M2 12l10 5 10-5" />
                            </svg>
                        </div>
                        <div class="we-glance-content">
                            <span class="we-glance-key">Dificultad</span>
                            <span class="we-glance-value"><?php echo esc_html($atts['difficulty']); ?></span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
});


/**
 * Shortcode: [mission_section]
 *
 * Ejemplo:
 * [mission_section label="Nuestra misión" heading="Inspirar amor por lo salvaje" italic_part="protegerlo" dash_text="y"]
 */
add_shortcode('mission_section', function ($atts) {
    $atts = shortcode_atts([
        'label' => 'Nuestra misión',
        'heading' => 'Inspirar amor por lo salvaje',
        'italic_part' => 'protegerlo',
        'dash_text' => 'y',
        'paragraph_1' => 'Historia de CreativeWidl.',
    ], $atts, 'mission_section');

    ob_start();
    ?>
    <section class="mission-section dg-animate-on-scroll">
        <div class="mission-container">
            <div class="mission-col-left">
                <span class="mission-label"><?php echo esc_html($atts['label']); ?></span>
                <h2 class="mission-heading">
                    <?php echo esc_html($atts['heading']); ?><br>
                    <span class="dash"><?php echo esc_html($atts['dash_text']); ?></span>
                    <em><?php echo esc_html($atts['italic_part']); ?></em>
                </h2>
            </div>
            <div class="mission-col-right mission-body">
                <p><?php echo esc_html($atts['paragraph_1']); ?></p>
                <p><?php echo esc_html($atts['paragraph_2']); ?></p>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
});

/**
 * Shortcode: [values_section]
 *
 * Ejemplo:
 * [values_section label="Lo que nos define" heading="Nuestros" italic_word="valores"]
 */
add_shortcode('values_section', function ($atts) {
    $atts = shortcode_atts([
        'label' => 'Lo que nos define',
        'heading' => 'Nuestros',
        'italic_word' => 'valores',
    ], $atts, 'values_section');

    $values = [
        [
            'icon' => 'heart',
            'title' => 'Autenticidad',
            'desc' => 'Experiencias reales, personas reales y naturaleza real. Sin encuentros montados.',
        ],
        [
            'icon' => 'compass',
            'title' => 'Experiencia',
            'desc' => 'Guías certificados con décadas de experiencia en campo y profundo conocimiento local.',
        ],
        [
            'icon' => 'globe',
            'title' => 'Responsabilidad',
            'desc' => 'Operaciones carbono neutro y un compromiso genuino con la conservación.',
        ],
        [
            'icon' => 'users',
            'title' => 'Cercanía',
            'desc' => 'Grupos pequeños que protegen la experiencia y el entorno.',
        ],
    ];

    ob_start();
    ?>
    <section class="values-section">
        <div class="values-container">
            <div class="values-header dg-animate-on-scroll">
                <div class="values-label">
                    <span class="values-label-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <circle cx="12" cy="12" r="6" />
                            <circle cx="12" cy="12" r="2" />
                        </svg>
                    </span>
                    <?php echo esc_html($atts['label']); ?>
                </div>
                <h2 class="values-heading"><?php echo esc_html($atts['heading']); ?>
                    <em><?php echo esc_html($atts['italic_word']); ?></em>
                </h2>
            </div>
            <div class="values-grid">
                <?php foreach ($values as $value): ?>
                    <div class="values-card dg-animate-on-scroll">
                        <div class="values-icon-wrap">
                            <?php if ($value['icon'] === 'heart'): ?>
                                <svg viewBox="0 0 24 24">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                </svg>
                            <?php elseif ($value['icon'] === 'compass'): ?>
                                <svg viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76" />
                                </svg>
                            <?php elseif ($value['icon'] === 'globe'): ?>
                                <svg viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="2" y1="12" x2="22" y2="12" />
                                    <path
                                        d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                </svg>
                            <?php elseif ($value['icon'] === 'users'): ?>
                                <svg viewBox="0 0 24 24">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            <?php endif; ?>
                        </div>
                        <h3 class="values-card-title"><?php echo esc_html($value['title']); ?></h3>
                        <p class="values-card-desc"><?php echo esc_html($value['desc']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
});

/**
 * Shortcode: [team_section]
 *
 * Ejemplo:
 * [team_section label="El equipo" heading="Conoce a tus" italic_word="guías"]
 *
 * Los miembros se pasan como pares: name1, role1, img1, name2, role2, img2...
 */
add_shortcode('team_section', function ($atts) {
    $atts = shortcode_atts([
        'label' => 'El equipo',
        'heading' => 'Conoce a tus',
        'italic_word' => 'guías',
        'name1' => 'José Alberto',
        'role1' => 'CEO y guía principal de observación de aves',
        'img1' => '',
        'name2' => 'Nils Navarro',
        'role2' => 'Guía de observación de aves',
        'img2' => '',
        'name3' => 'Diana DTen',
        'role3' => 'Guía de fotografía',
        'img3' => '',
    ], $atts, 'team_section');

    $members = [];
    for ($i = 1; $i <= 3; $i++) {
        $members[] = [
            'name' => $atts["name{$i}"],
            'role' => $atts["role{$i}"],
            'img' => $atts["img{$i}"],
        ];
    }

    ob_start();
    ?>
    <section class="team-section">
        <div class="team-container">
            <div class="team-header dg-animate-on-scroll">
                <span class="team-label"><?php echo esc_html($atts['label']); ?></span>
                <h2 class="team-heading"><?php echo esc_html($atts['heading']); ?>
                    <em><?php echo esc_html($atts['italic_word']); ?></em>
                </h2>
            </div>
            <div class="team-grid">
                <?php foreach ($members as $member): ?>
                    <div class="team-member dg-animate-on-scroll">
                        <div class="team-image-wrap">
                            <?php if (!empty($member['img'])): ?>
                                <img src="<?php echo esc_url($member['img']); ?>" alt="<?php echo esc_attr($member['name']); ?>"
                                    loading="lazy">
                            <?php else: ?>
                                <div style="width:100%;height:100%;background:linear-gradient(135deg,#D4CFC7,#B8B0A5);"></div>
                            <?php endif; ?>
                        </div>
                        <h3 class="team-name"><?php echo esc_html($member['name']); ?></h3>
                        <p class="team-role"><?php echo esc_html($member['role']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
});
