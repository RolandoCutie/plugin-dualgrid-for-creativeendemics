<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', function () {
    wp_register_style(
        'dualgrid-font-space-grotesk',
        'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&display=swap',
        [],
        null
    );
    wp_register_style(
        'dualgrid-font-inter',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap',
        [],
        null
    );
    wp_register_style(
        'dualgrid-font-jetbrains-mono',
        'https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&display=swap',
        [],
        null
    );
    wp_register_style(
        'dualgrid-global-typography',
        DUALGRID_PLUGIN_URL . 'css/global-typography.css',
        ['dualgrid-font-space-grotesk', 'dualgrid-font-inter', 'dualgrid-font-jetbrains-mono'],
        DUALGRID_VERSION
    );

    wp_register_style(
        'dualgrid-creativewilds',
        DUALGRID_PLUGIN_URL . 'css/creativewilds.css',
        ['dualgrid-global-typography'],
        DUALGRID_VERSION
    );
    wp_register_script(
        'dualgrid-creativewilds',
        DUALGRID_PLUGIN_URL . 'js/creativewilds.js',
        [],
        DUALGRID_VERSION,
        true
    );
});

if (!function_exists('dg_cw_localize_frontend_assets')) {
    function dg_cw_localize_frontend_assets()
    {
        static $localized = false;

        if ($localized) {
            return;
        }

        wp_add_inline_script(
            'dualgrid-creativewilds',
            'window.dgCreativeWilds=' . wp_json_encode([
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('dg_cw_contact_submit'),
                'currentLang' => dg_cw_resolve_frontend_lang(),
                'langQueryVar' => dg_cw_get_lang_query_var(),
                'langCookieName' => dg_cw_get_lang_cookie_name(),
            ]) . ';',
            'before'
        );

        $localized = true;
    }
}

if (!function_exists('dg_cw_enqueue_assets')) {
    function dg_cw_enqueue_assets()
    {
        wp_enqueue_style('dualgrid-creativewilds');
        wp_enqueue_script('dualgrid-creativewilds');
        dg_cw_localize_frontend_assets();
    }
}

add_action('wp_enqueue_scripts', function () {
    dg_cw_enqueue_assets();
}, 20);

if (!function_exists('dg_cw_render_media')) {
    function dg_cw_render_media($image_id, $size, $class, $alt = '')
    {
        $classes = trim($class . ' dg-cw-media');
        if ($image_id) {
            return wp_get_attachment_image(
                $image_id,
                $size,
                false,
                [
                    'class' => $classes,
                    'alt' => $alt,
                    'loading' => 'lazy',
                ]
            );
        }

        return '<span class="' . esc_attr($classes . ' dg-cw-media--placeholder') . '" aria-hidden="true"></span>';
    }
}

if (!function_exists('dg_cw_render_section_header')) {
    function dg_cw_render_section_header($eyebrow, $title, $subtitle)
    {
        ob_start();
        ?>
        <div class="dg-cw-section-head dg-cw-reveal">
            <div class="dg-cw-section-head__lead">
                <p class="dg-cw-eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <h2><?php echo esc_html($title); ?></h2>
            </div>
            <p class="dg-cw-section-head__copy"><?php echo esc_html($subtitle); ?></p>
        </div>
        <?php
        return ob_get_clean();
    }
}

if (!function_exists('dg_cw_get_gallery_span_style')) {
    function dg_cw_get_gallery_span_style($index)
    {
        $patterns = [
            [2, 2],
            [1, 1],
            [1, 1],
            [2, 1],
            [2, 1],
            [2, 1],
        ];
        $pattern = $patterns[$index % count($patterns)];

        return sprintf('--dg-cw-col-span:%d;--dg-cw-row-span:%d;', $pattern[0], $pattern[1]);
    }
}

if (!function_exists('dg_cw_render_contact_icon')) {
    function dg_cw_render_contact_icon($type)
    {
        $icons = [
            'email' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18v12H3z" fill="none" stroke="currentColor" stroke-width="1.5"/><path d="M4 7l8 6 8-6" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>',
            'phone' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.8 3.5h3.2l1.2 4.4-1.9 1.8c1.2 2.1 2.9 3.8 5 5l1.8-1.9 4.4 1.2v3.2c0 1-.8 1.8-1.8 1.8C10 19 5 14 5 7.1c0-1 .8-1.8 1.8-1.8z" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>',
            'whatsapp' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 4a8 8 0 0 0-6.9 12l-1.1 4 4.1-1A8 8 0 1 0 12 4z" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M9.3 9.1c.2-.5.4-.5.7-.5h.6c.2 0 .4 0 .5.4l.6 1.5c.1.2.1.4 0 .6l-.5.6c-.1.1-.1.3 0 .4.3.6 1 1.5 2.2 2 .2.1.3.1.4 0l.7-.8c.1-.2.4-.2.5-.1l1.4.7c.3.1.4.3.3.5l-.2.8c-.1.4-.4.6-.8.7-.6.1-1.4 0-2.6-.7-2-1.2-3.3-3.1-3.8-4.3-.2-.5-.2-1 0-1.4l.5-.9z" fill="currentColor"/></svg>',
        ];

        return $icons[$type] ?? $icons['email'];
    }
}

if (!function_exists('dg_cw_get_client_logo_url')) {
    function dg_cw_get_client_logo_url()
    {
        $logo_path = DUALGRID_PLUGIN_DIR . 'assets/logo.svg';

        if (!file_exists($logo_path) || !is_readable($logo_path)) {
            return '';
        }

        return DUALGRID_PLUGIN_URL . 'assets/logo.svg';
    }
}


add_shortcode('cw_language_toggle', function ($atts = []) {
    dg_cw_enqueue_assets();

    $atts = shortcode_atts([
        'en_label' => 'EN',
        'es_label' => 'ES',
        'title' => __('Cambiar idioma', 'dualgrid'),
    ], $atts, 'cw_language_toggle');

    $current_lang = dg_cw_resolve_frontend_lang();

    ob_start();
    ?>
    <div class="dg-cw-language-toggle" data-dg-cw-language-toggle role="group" aria-label="<?php echo esc_attr($atts['title']); ?>">
        <button
            type="button"
            class="dg-cw-language-toggle__button<?php echo $current_lang === 'en' ? ' is-active' : ''; ?>"
            data-dg-cw-lang-switch="en"
            aria-pressed="<?php echo $current_lang === 'en' ? 'true' : 'false'; ?>">
            <?php echo esc_html($atts['en_label']); ?>
        </button>
        <button
            type="button"
            class="dg-cw-language-toggle__button<?php echo $current_lang === 'es' ? ' is-active' : ''; ?>"
            data-dg-cw-lang-switch="es"
            aria-pressed="<?php echo $current_lang === 'es' ? 'true' : 'false'; ?>">
            <?php echo esc_html($atts['es_label']); ?>
        </button>
    </div>
    <?php
    return ob_get_clean();
});

add_shortcode('cw_hero', function ($atts = []) {
    dg_cw_enqueue_assets();

    $atts = shortcode_atts([
        'lang' => '',
        'id' => 'hero',
        'contact_anchor' => 'contact',
        'work_anchor' => 'work',
        'interval' => 6000,
    ], $atts, 'cw_hero');

    $lang = dg_cw_resolve_frontend_lang($atts['lang']);
    $settings = dg_cw_get_settings();
    $services = dg_cw_get_active_services($settings);
    $contact_href = '#' . sanitize_title_with_dashes((string) $atts['contact_anchor']);
    $work_href = '#' . sanitize_title_with_dashes((string) $atts['work_anchor']);
    $primary_label = dg_cw_get_translated_value($settings['hero'], 'primary_button', $lang);
    $secondary_label = dg_cw_get_translated_value($settings['hero'], 'secondary_button', $lang);

    ob_start();
    ?>
    <section id="<?php echo esc_attr(sanitize_title_with_dashes((string) $atts['id'])); ?>"
        class="dg-cw-section dg-cw-hero"
        data-dg-cw-hero
        data-interval="<?php echo esc_attr((int) $atts['interval']); ?>">
        <?php foreach ($services as $index => $service): ?>
            <?php
            $title = trim(
                dg_cw_get_translated_value($service, 'hero_lead', $lang) . ' ' .
                dg_cw_get_translated_value($service, 'hero_accent', $lang)
            );
            ?>
            <article class="dg-cw-hero-slide<?php echo $index === 0 ? ' is-active' : ''; ?>" data-slide-index="<?php echo esc_attr($index); ?>">
                <div class="dg-cw-hero-media-wrap">
                    <?php echo dg_cw_render_media($service['hero_image_id'], 'full', 'dg-cw-hero-media', $title); ?>
                    <span class="dg-cw-hero-overlay dg-cw-hero-overlay--vertical" aria-hidden="true"></span>
                    <span class="dg-cw-hero-overlay dg-cw-hero-overlay--horizontal" aria-hidden="true"></span>
                </div>
                <div class="dg-cw-shell">
                    <div class="dg-cw-hero-copy">
                        <h1>
                            <?php echo esc_html(dg_cw_get_translated_value($service, 'hero_lead', $lang)); ?>
                            <span><?php echo esc_html(dg_cw_get_translated_value($service, 'hero_accent', $lang)); ?></span>
                        </h1>
                        <p><?php echo esc_html(dg_cw_get_translated_value($service, 'description', $lang)); ?></p>
                        <div class="dg-cw-hero-actions">
                            <a class="dg-cw-button dg-cw-button--primary" href="<?php echo esc_url($contact_href); ?>">
                                <?php echo esc_html($primary_label); ?>
                            </a>
                            <a class="dg-cw-button dg-cw-button--secondary" href="<?php echo esc_url($work_href); ?>">
                                <?php echo esc_html($secondary_label); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
    <?php
    return ob_get_clean();
});

add_shortcode('cw_services', function ($atts = []) {
    dg_cw_enqueue_assets();

    $atts = shortcode_atts([
        'lang' => '',
        'id' => 'services',
        'contact_anchor' => 'contact',
    ], $atts, 'cw_services');

    $lang = dg_cw_resolve_frontend_lang($atts['lang']);
    $settings = dg_cw_get_settings();
    $section = $settings['services'];
    $services = dg_cw_get_active_services($settings);
    $contact_id = sanitize_title_with_dashes((string) $atts['contact_anchor']);

    ob_start();
    ?>
    <section id="<?php echo esc_attr(sanitize_title_with_dashes((string) $atts['id'])); ?>" class="dg-cw-section dg-cw-services">
        <div class="dg-cw-shell">
            <?php
            echo dg_cw_render_section_header(
                dg_cw_get_translated_value($section, 'eyebrow', $lang),
                dg_cw_get_translated_value($section, 'title', $lang),
                dg_cw_get_translated_value($section, 'subtitle', $lang)
            );
            ?>
            <div class="dg-cw-services-grid">
                <?php foreach ($services as $index => $service): ?>
                    <article class="dg-cw-service-card dg-cw-reveal" style="--dg-cw-delay: <?php echo esc_attr(number_format(($index % 2) * 0.1, 2)); ?>s;">
                        <div class="dg-cw-service-card__media">
                            <?php echo dg_cw_render_media($service['image_id'], 'large', 'dg-cw-service-card__image', dg_cw_get_translated_value($service, 'title', $lang)); ?>
                            <span class="dg-cw-service-card__overlay" aria-hidden="true"></span>
                        </div>
                        <div class="dg-cw-service-card__content">
                            <p class="dg-cw-index"><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></p>
                            <h3><?php echo esc_html(dg_cw_get_translated_value($service, 'title', $lang)); ?></h3>
                            <p><?php echo esc_html(dg_cw_get_translated_value($service, 'description', $lang)); ?></p>
                            <a class="dg-cw-inline-link"
                                href="<?php echo esc_url('#' . $contact_id); ?>"
                                data-dg-cw-service="<?php echo esc_attr($service['slug']); ?>"
                                data-dg-cw-target="<?php echo esc_attr($contact_id); ?>">
                                <?php echo esc_html(dg_cw_get_translated_value($section, 'cta', $lang)); ?>
                                <span aria-hidden="true">&#8599;</span>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
});

add_shortcode('cw_about', function ($atts = []) {
    dg_cw_enqueue_assets();

    $atts = shortcode_atts([
        'lang' => '',
        'id' => 'about-us',
    ], $atts, 'cw_about');

    $lang = dg_cw_resolve_frontend_lang($atts['lang']);
    $settings = dg_cw_get_settings();
    $section = $settings['about'];
    $chips = $lang === 'es' ? ($section['chips_es'] ?? []) : ($section['chips_en'] ?? []);
    $about_id = sanitize_title_with_dashes((string) $atts['id']);

    ob_start();
    ?>
    <section id="<?php echo esc_attr($about_id); ?>" class="dg-cw-section dg-cw-about">
        <?php if ($about_id !== 'about'): ?>
            <span id="about" class="dg-cw-anchor" aria-hidden="true"></span>
        <?php endif; ?>
        <div class="dg-cw-shell dg-cw-about-grid">
            <div class="dg-cw-about-copy dg-cw-reveal">
                <p class="dg-cw-eyebrow"><?php echo esc_html(dg_cw_get_translated_value($section, 'eyebrow', $lang)); ?></p>
                <h2><?php echo esc_html(dg_cw_get_translated_value($section, 'title', $lang)); ?></h2>
                <p class="dg-cw-about-copy__body"><?php echo esc_html(dg_cw_get_translated_value($section, 'body', $lang)); ?></p>
                <div class="dg-cw-chip-list">
                    <?php foreach ($chips as $chip): ?>
                        <span class="dg-cw-chip"><?php echo esc_html($chip); ?></span>
                    <?php endforeach; ?>
                </div>
                <p class="dg-cw-meta-line"><?php echo esc_html(dg_cw_get_translated_value($section, 'note', $lang)); ?></p>
            </div>
            <div class="dg-cw-about-media dg-cw-reveal dg-cw-reveal--scale">
                <?php echo dg_cw_render_media($section['image_id'], 'large', 'dg-cw-about-image', dg_cw_get_translated_value($section, 'title', $lang)); ?>
                <span class="dg-cw-about-gradient" aria-hidden="true"></span>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
});

add_shortcode('cw_gallery', function ($atts = []) {
    dg_cw_enqueue_assets();

    $atts = shortcode_atts([
        'lang' => '',
        'id' => 'work',
    ], $atts, 'cw_gallery');

    $lang = dg_cw_resolve_frontend_lang($atts['lang']);
    $settings = dg_cw_get_settings();
    $section = $settings['gallery'];
    $brand = $settings['general']['brand_name'];
    $location = dg_cw_get_translated_value($settings['general'], 'location', $lang);
    $gallery_id = sanitize_title_with_dashes((string) $atts['id']);

    ob_start();
    ?>
    <section id="<?php echo esc_attr($gallery_id); ?>" class="dg-cw-section dg-cw-gallery">
        <div class="dg-cw-shell">
            <?php
            echo dg_cw_render_section_header(
                dg_cw_get_translated_value($section, 'eyebrow', $lang),
                dg_cw_get_translated_value($section, 'title', $lang),
                dg_cw_get_translated_value($section, 'subtitle', $lang)
            );
            ?>
            <div class="dg-cw-gallery-grid">
                <?php foreach (($section['items'] ?? []) as $index => $item): ?>
                    <?php
                    $category = dg_cw_get_translated_value($item, 'category', $lang);
                    $full_url = $item['image_id'] ? wp_get_attachment_image_url($item['image_id'], 'full') : '';
                    $thumb_url = $item['image_id'] ? wp_get_attachment_image_url($item['image_id'], 'large') : '';
                    $is_disabled = $full_url === '';
                    ?>
                    <button
                        type="button"
                        class="dg-cw-gallery-item dg-cw-reveal"
                        style="<?php echo esc_attr(dg_cw_get_gallery_span_style($index)); ?>--dg-cw-delay: <?php echo esc_attr(number_format(($index % 4) * 0.08, 2)); ?>s;"
                        data-dg-cw-gallery-item
                        data-image-full="<?php echo esc_url($full_url); ?>"
                        data-image-thumb="<?php echo esc_url($thumb_url); ?>"
                        data-category="<?php echo esc_attr($category); ?>"
                        data-location="<?php echo esc_attr($location); ?>"
                        data-brand="<?php echo esc_attr($brand); ?>"
                        <?php disabled($is_disabled); ?>>
                        <?php echo dg_cw_render_media($item['image_id'], 'large', 'dg-cw-gallery-item__image', $category); ?>
                        <span class="dg-cw-gallery-item__overlay" aria-hidden="true"></span>
                        <span class="dg-cw-gallery-item__footer">
                            <span class="dg-cw-gallery-item__category"><?php echo esc_html($category); ?></span>
                            <span class="dg-cw-gallery-item__expand" aria-hidden="true">&#10548;</span>
                        </span>
                    </button>
                <?php endforeach; ?>
            </div>
            <p class="dg-cw-gallery-note"><?php echo esc_html(dg_cw_get_translated_value($section, 'note', $lang)); ?></p>
        </div>

        <div class="dg-cw-lightbox" data-dg-cw-lightbox hidden>
            <div class="dg-cw-lightbox__inner" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr(dg_cw_get_translated_value($section, 'title', $lang)); ?>">
                <div class="dg-cw-lightbox__top">
                    <p class="dg-cw-eyebrow" data-dg-cw-lightbox-counter>01 / 01</p>
                    <button type="button" class="dg-cw-lightbox__close" data-dg-cw-lightbox-close aria-label="<?php echo esc_attr($lang === 'es' ? 'Cerrar galeria' : 'Close gallery'); ?>">&times;</button>
                </div>
                <div class="dg-cw-lightbox__stage">
                    <button type="button" class="dg-cw-lightbox__nav dg-cw-lightbox__nav--prev" data-dg-cw-lightbox-prev aria-label="<?php echo esc_attr($lang === 'es' ? 'Imagen anterior' : 'Previous image'); ?>">&larr;</button>
                    <div class="dg-cw-lightbox__frame">
                        <img src="" alt="" data-dg-cw-lightbox-image />
                    </div>
                    <button type="button" class="dg-cw-lightbox__nav dg-cw-lightbox__nav--next" data-dg-cw-lightbox-next aria-label="<?php echo esc_attr($lang === 'es' ? 'Siguiente imagen' : 'Next image'); ?>">&rarr;</button>
                </div>
                <div class="dg-cw-lightbox__bottom">
                    <div>
                        <p class="dg-cw-lightbox__category" data-dg-cw-lightbox-category></p>
                        <p class="dg-cw-eyebrow" data-dg-cw-lightbox-location></p>
                    </div>
                    <p class="dg-cw-eyebrow" data-dg-cw-lightbox-brand><?php echo esc_html($brand); ?></p>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
});

add_shortcode('cw_contact', function ($atts = []) {
    dg_cw_enqueue_assets();

    $atts = shortcode_atts([
        'lang' => '',
        'id' => 'contact',
    ], $atts, 'cw_contact');

    $lang = dg_cw_resolve_frontend_lang($atts['lang']);
    $settings = dg_cw_get_settings();
    $section = $settings['contact'];
    $services = dg_cw_get_active_services($settings);
    $background = $section['image_id'] ? wp_get_attachment_image_url($section['image_id'], 'full') : '';
    $client_logo_url = dg_cw_get_client_logo_url();
    $labels = [
        'name' => $lang === 'es' ? 'Nombre' : 'Name',
        'email' => 'Email',
        'phone' => $lang === 'es' ? 'Tel' . "\xC3\xA9" . 'fono' : 'Phone',
        'service' => $lang === 'es' ? '¿Qu' . "\xC3\xA9" . ' necesitas?' : 'What do you need?',
        'details' => $lang === 'es' ? 'Detalles del proyecto' : 'Project details',
        'email_placeholder' => 'you@company.com',
        'name_placeholder' => $lang === 'es' ? 'Tu nombre' : 'Your name',
        'phone_placeholder' => $lang === 'es' ? 'Opcional' : 'Optional',
        'service_placeholder' => $lang === 'es' ? 'Selecciona un servicio' : 'Select a service',
        'details_placeholder' => $lang === 'es' ? 'Ubicaci' . "\xC3\xB3" . 'n, fechas, qu' . "\xC3\xA9" . ' quieres capturar…' : 'Location, dates, what you want to capture…',
        'something_else' => $lang === 'es' ? 'Algo m' . "\xC3\xA1" . 's' : 'Something else',
        'email_row' => 'Email',
        'phone_row' => $lang === 'es' ? 'Tel' . "\xC3\xA9" . 'fono' : 'Phone',
        'whatsapp_row' => 'WhatsApp',
    ];

    ob_start();
    ?>
    <section id="<?php echo esc_attr(sanitize_title_with_dashes((string) $atts['id'])); ?>" class="dg-cw-section dg-cw-contact">
        <div class="dg-cw-contact-bg" style="<?php echo $background ? 'background-image:url(' . esc_url($background) . ');' : ''; ?>"></div>
        <div class="dg-cw-shell dg-cw-contact-grid">
            <div class="dg-cw-contact-copy dg-cw-reveal">
                <div class="dg-cw-status">
                    <span class="dg-cw-status__dot"></span>
                    <span class="dg-cw-eyebrow"><?php echo esc_html(dg_cw_get_translated_value($section, 'status', $lang)); ?></span>
                </div>
                <h2><?php echo esc_html(dg_cw_get_translated_value($section, 'heading', $lang)); ?></h2>
                <p class="dg-cw-contact-copy__subtitle"><?php echo esc_html(dg_cw_get_translated_value($section, 'subtitle', $lang)); ?></p>

                <div class="dg-cw-contact-links">
                    <p class="dg-cw-eyebrow"><?php echo esc_html(dg_cw_get_translated_value($section, 'direct_title', $lang)); ?></p>
                    <a href="mailto:<?php echo esc_attr($section['email']); ?>" class="dg-cw-contact-link">
                        <span class="dg-cw-contact-link__icon"><?php echo dg_cw_render_contact_icon('email'); ?></span>
                        <span class="dg-cw-contact-link__meta">
                            <span class="dg-cw-eyebrow"><?php echo esc_html($labels['email_row']); ?></span>
                            <span><?php echo esc_html($section['email']); ?></span>
                        </span>
                        <span aria-hidden="true">&#8599;</span>
                    </a>
                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $section['phone'])); ?>" class="dg-cw-contact-link">
                        <span class="dg-cw-contact-link__icon"><?php echo dg_cw_render_contact_icon('phone'); ?></span>
                        <span class="dg-cw-contact-link__meta">
                            <span class="dg-cw-eyebrow"><?php echo esc_html($labels['phone_row']); ?></span>
                            <span><?php echo esc_html($section['phone']); ?></span>
                        </span>
                        <span aria-hidden="true">&#8599;</span>
                    </a>
                    <a href="<?php echo esc_url($section['whatsapp']); ?>" class="dg-cw-contact-link" target="_blank" rel="noreferrer noopener">
                        <span class="dg-cw-contact-link__icon"><?php echo dg_cw_render_contact_icon('whatsapp'); ?></span>
                        <span class="dg-cw-contact-link__meta">
                            <span class="dg-cw-eyebrow"><?php echo esc_html($labels['whatsapp_row']); ?></span>
                            <span><?php echo esc_html($section['phone']); ?></span>
                        </span>
                        <span aria-hidden="true">&#8599;</span>
                    </a>
                </div>
            </div>

            <div class="dg-cw-contact-form-wrap dg-cw-reveal" style="--dg-cw-delay:0.1s;">
                <?php if ($client_logo_url !== ''): ?>
                    <div class="dg-cw-contact-form__badge" aria-hidden="true">
                        <span class="dg-cw-contact-form__badge-shadow"></span>
                        <img src="<?php echo esc_url($client_logo_url); ?>" alt="<?php echo esc_attr($settings['general']['brand_name']); ?>">
                    </div>
                <?php endif; ?>
                <form class="dg-cw-contact-form"
                    data-dg-cw-contact-form
                    data-success-title="<?php echo esc_attr(dg_cw_get_translated_value($section, 'success_title', $lang)); ?>"
                    data-success-message="<?php echo esc_attr(dg_cw_get_translated_value($section, 'success_message', $lang)); ?>"
                    data-success-reset="<?php echo esc_attr(dg_cw_get_translated_value($section, 'success_reset', $lang)); ?>"
                    data-error-message="<?php echo esc_attr(dg_cw_get_translated_value($section, 'error_message', $lang)); ?>"
                    data-send-label="<?php echo esc_attr(dg_cw_get_translated_value($section, 'send_button', $lang)); ?>"
                    data-sending-label="<?php echo esc_attr(dg_cw_get_translated_value($section, 'sending_button', $lang)); ?>">
                    <input type="hidden" name="lang" value="<?php echo esc_attr($lang); ?>">
                    <input type="hidden" name="page_url" value="<?php echo esc_url(get_permalink() ?: home_url('/')); ?>">
                    <div class="dg-cw-contact-fields">
                        <label class="dg-cw-field">
                            <span class="dg-cw-eyebrow"><?php echo esc_html($labels['name']); ?></span>
                            <input type="text" name="name" required placeholder="<?php echo esc_attr($labels['name_placeholder']); ?>">
                        </label>
                        <label class="dg-cw-field">
                            <span class="dg-cw-eyebrow"><?php echo esc_html($labels['email']); ?></span>
                            <input type="email" name="email" required placeholder="<?php echo esc_attr($labels['email_placeholder']); ?>">
                        </label>
                        <label class="dg-cw-field">
                            <span class="dg-cw-eyebrow"><?php echo esc_html($labels['phone']); ?></span>
                            <input type="tel" name="phone" placeholder="<?php echo esc_attr($labels['phone_placeholder']); ?>">
                        </label>
                        <label class="dg-cw-field">
                            <span class="dg-cw-eyebrow"><?php echo esc_html($labels['service']); ?></span>
                            <select name="service" data-dg-cw-service-select>
                                <option value=""><?php echo esc_html($labels['service_placeholder']); ?></option>
                                <?php foreach ($services as $service): ?>
                                    <option value="<?php echo esc_attr($service['slug']); ?>">
                                        <?php echo esc_html(dg_cw_get_translated_value($service, 'title', $lang)); ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="something-else"><?php echo esc_html($labels['something_else']); ?></option>
                            </select>
                        </label>
                    </div>
                    <label class="dg-cw-field dg-cw-field--full">
                        <span class="dg-cw-eyebrow"><?php echo esc_html($labels['details']); ?></span>
                        <textarea name="message" rows="4" required placeholder="<?php echo esc_attr($labels['details_placeholder']); ?>"></textarea>
                    </label>

                    <div class="dg-cw-contact-form__footer">
                        <button type="submit" class="dg-cw-button dg-cw-button--primary">
                            <span data-dg-cw-submit-label><?php echo esc_html(dg_cw_get_translated_value($section, 'send_button', $lang)); ?></span>
                            <span class="dg-cw-spinner" data-dg-cw-spinner aria-hidden="true"></span>
                        </button>
                        <p class="dg-cw-contact-error" data-dg-cw-contact-error hidden></p>
                    </div>
                </form>

                <div class="dg-cw-contact-success" data-dg-cw-contact-success hidden>
                    <span class="dg-cw-contact-success__badge">&#10003;</span>
                    <h3 data-dg-cw-success-title><?php echo esc_html(dg_cw_get_translated_value($section, 'success_title', $lang)); ?></h3>
                    <p data-dg-cw-success-message><?php echo esc_html(dg_cw_get_translated_value($section, 'success_message', $lang)); ?></p>
                    <button type="button" class="dg-cw-inline-link dg-cw-inline-link--button" data-dg-cw-contact-reset>
                        <?php echo esc_html(dg_cw_get_translated_value($section, 'success_reset', $lang)); ?>
                    </button>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
});
