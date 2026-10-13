<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('dg_cw_get_admin_menu_icon')) {
    function dg_cw_get_admin_menu_icon()
    {
        static $icon = null;

        if ($icon !== null) {
            return $icon;
        }

        $icon_path = DUALGRID_PLUGIN_DIR . 'assets/dualgrid.svg';
        if (!file_exists($icon_path) || !is_readable($icon_path)) {
            $icon = 'dashicons-format-gallery';
            return $icon;
        }

        $svg = file_get_contents($icon_path);
        if ($svg === false || trim($svg) === '') {
            $icon = 'dashicons-format-gallery';
            return $icon;
        }

        $svg = preg_replace('/<\?xml.*?\?>/i', '', $svg);
        $svg = preg_replace('/<!--.*?-->/s', '', $svg);
        $svg = trim((string) $svg);

        $icon = 'data:image/svg+xml;base64,' . base64_encode($svg);
        return $icon;
    }
}

add_action('admin_menu', function () {
    add_menu_page(
        'DualGrid',
        'DualGrid',
        'edit_others_posts',
        'dualgrid-creativewilds',
        'dg_cw_render_admin_dashboard',
        dg_cw_get_admin_menu_icon(),
        58
    );
    

    add_submenu_page(
        'dualgrid-creativewilds',
        'DualGrid',
        'Dashboard',
        'edit_others_posts',
        'dualgrid-creativewilds',
        'dg_cw_render_admin_dashboard'
    );

    add_submenu_page(
        'dualgrid-creativewilds',
        'Contenido DualGrid',
        'Contenido',
        'edit_others_posts',
        'dualgrid-creativewilds-content',
        'dg_cw_render_admin_content_page'
    );
});

add_action('admin_head', function () {
    ?>
    <style>
        #toplevel_page_dualgrid-creativewilds .wp-menu-image img {
            width: 20px;
            height: 20px;
            padding: 7px 0 0;
            opacity: 0.92;
        }

        #toplevel_page_dualgrid-creativewilds:hover .wp-menu-image img,
        #toplevel_page_dualgrid-creativewilds.current .wp-menu-image img,
        #toplevel_page_dualgrid-creativewilds.wp-has-current-submenu .wp-menu-image img {
            opacity: 1;
        }
    </style>
    <?php
});

add_action('admin_enqueue_scripts', function ($hook) {
    if (strpos((string) $hook, 'dualgrid-creativewilds') === false) {
        return;
    }


    wp_enqueue_media();
    wp_enqueue_style(
        'dualgrid-admin-creativewilds',
        DUALGRID_PLUGIN_URL . 'css/admin-creativewilds.css',
        [],
        DUALGRID_VERSION
    );
    wp_enqueue_script(
        'dualgrid-admin-creativewilds',
        DUALGRID_PLUGIN_URL . 'js/admin-creativewilds.js',
        [],
        DUALGRID_VERSION,
        true
    );
});

if (!function_exists('dg_cw_assert_admin_access')) {
    function dg_cw_assert_admin_access()
    {
        if (!current_user_can('edit_others_posts')) {
            wp_die(esc_html__('You do not have permission to access this page.', 'dualgrid'));
        }
    }
}

if (!function_exists('dg_cw_admin_image_field')) {
    function dg_cw_admin_image_field($name, $image_id, $label)
    {
        $preview = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : '';
        ?>
        <div class="dg-cw-image-field" data-dg-cw-image-field>
            <input type="hidden" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr((int) $image_id); ?>" data-dg-cw-image-input>
            <div class="dg-cw-image-field__preview<?php echo $preview ? '' : ' is-empty'; ?>" data-dg-cw-image-preview>
                <?php if ($preview): ?>
                    <img src="<?php echo esc_url($preview); ?>" alt="<?php echo esc_attr($label); ?>">
                <?php else: ?>
                    <span><?php echo esc_html($label); ?></span>
                <?php endif; ?>
            </div>
            <div class="dg-cw-image-field__actions">
                <button type="button" class="button button-secondary" data-dg-cw-media-open><?php esc_html_e('Seleccionar imagen', 'dualgrid'); ?></button>
                <button type="button" class="button" data-dg-cw-media-clear><?php esc_html_e('Quitar', 'dualgrid'); ?></button>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('dg_cw_render_service_row')) {
    function dg_cw_render_service_row(array $item, $index)
    {
        ?>
        <div class="dg-cw-repeater-row" data-dg-cw-row>
            <div class="dg-cw-repeater-row__header">
                <strong data-dg-cw-row-title><?php echo esc_html($item['title_en'] ?: 'Service'); ?></strong>
                <div class="dg-cw-row-actions">
                    <button type="button" class="button" data-dg-cw-move-up><?php esc_html_e('Subir', 'dualgrid'); ?></button>
                    <button type="button" class="button" data-dg-cw-move-down><?php esc_html_e('Bajar', 'dualgrid'); ?></button>
                    <button type="button" class="button button-link-delete" data-dg-cw-remove-row><?php esc_html_e('Eliminar', 'dualgrid'); ?></button>
                </div>
            </div>
            <div class="dg-cw-admin-grid dg-cw-admin-grid--2">
                <div class="dg-cw-admin-field dg-cw-admin-field--full">
                    <label><?php esc_html_e('Imagen principal', 'dualgrid'); ?></label>
                    <?php dg_cw_admin_image_field("dg_cw[services][items][$index][image_id]", (int) $item['image_id'], 'Service image'); ?>
                </div>
                <div class="dg-cw-admin-field">
                    <label><?php esc_html_e('Titulo tarjeta (EN)', 'dualgrid'); ?></label>
                    <input type="text" name="dg_cw[services][items][<?php echo esc_attr($index); ?>][title_en]" value="<?php echo esc_attr($item['title_en']); ?>" data-dg-cw-title-source>
                </div>
                <div class="dg-cw-admin-field">
                    <label><?php esc_html_e('Titulo tarjeta (ES)', 'dualgrid'); ?></label>
                    <input type="text" name="dg_cw[services][items][<?php echo esc_attr($index); ?>][title_es]" value="<?php echo esc_attr($item['title_es']); ?>">
                </div>
                <div class="dg-cw-admin-field">
                    <label><?php esc_html_e('Hero lead (EN)', 'dualgrid'); ?></label>
                    <input type="text" name="dg_cw[services][items][<?php echo esc_attr($index); ?>][hero_lead_en]" value="<?php echo esc_attr($item['hero_lead_en']); ?>">
                </div>
                <div class="dg-cw-admin-field">
                    <label><?php esc_html_e('Hero accent (EN)', 'dualgrid'); ?></label>
                    <input type="text" name="dg_cw[services][items][<?php echo esc_attr($index); ?>][hero_accent_en]" value="<?php echo esc_attr($item['hero_accent_en']); ?>">
                </div>
                <div class="dg-cw-admin-field">
                    <label><?php esc_html_e('Hero lead (ES)', 'dualgrid'); ?></label>
                    <input type="text" name="dg_cw[services][items][<?php echo esc_attr($index); ?>][hero_lead_es]" value="<?php echo esc_attr($item['hero_lead_es']); ?>">
                </div>
                <div class="dg-cw-admin-field">
                    <label><?php esc_html_e('Hero accent (ES)', 'dualgrid'); ?></label>
                    <input type="text" name="dg_cw[services][items][<?php echo esc_attr($index); ?>][hero_accent_es]" value="<?php echo esc_attr($item['hero_accent_es']); ?>">
                </div>
                <div class="dg-cw-admin-field">
                    <label><?php esc_html_e('Descripcion (EN)', 'dualgrid'); ?></label>
                    <textarea name="dg_cw[services][items][<?php echo esc_attr($index); ?>][description_en]" rows="4"><?php echo esc_textarea($item['description_en']); ?></textarea>
                </div>
                <div class="dg-cw-admin-field">
                    <label><?php esc_html_e('Descripcion (ES)', 'dualgrid'); ?></label>
                    <textarea name="dg_cw[services][items][<?php echo esc_attr($index); ?>][description_es]" rows="4"><?php echo esc_textarea($item['description_es']); ?></textarea>
                </div>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('dg_cw_render_gallery_row')) {
    function dg_cw_render_gallery_row(array $item, $index)
    {
        ?>
        <div class="dg-cw-repeater-row" data-dg-cw-row>
            <div class="dg-cw-repeater-row__header">
                <strong data-dg-cw-row-title><?php echo esc_html($item['category_en'] ?: 'Gallery item'); ?></strong>
                <div class="dg-cw-row-actions">
                    <button type="button" class="button" data-dg-cw-move-up><?php esc_html_e('Subir', 'dualgrid'); ?></button>
                    <button type="button" class="button" data-dg-cw-move-down><?php esc_html_e('Bajar', 'dualgrid'); ?></button>
                    <button type="button" class="button button-link-delete" data-dg-cw-remove-row><?php esc_html_e('Eliminar', 'dualgrid'); ?></button>
                </div>
            </div>
            <div class="dg-cw-admin-grid dg-cw-admin-grid--2">
                <div class="dg-cw-admin-field dg-cw-admin-field--full">
                    <label><?php esc_html_e('Imagen', 'dualgrid'); ?></label>
                    <?php dg_cw_admin_image_field("dg_cw[gallery][items][$index][image_id]", (int) $item['image_id'], 'Gallery image'); ?>
                </div>
                <div class="dg-cw-admin-field">
                    <label><?php esc_html_e('Categoria (EN)', 'dualgrid'); ?></label>
                    <input type="text" name="dg_cw[gallery][items][<?php echo esc_attr($index); ?>][category_en]" value="<?php echo esc_attr($item['category_en']); ?>" data-dg-cw-title-source>
                </div>
                <div class="dg-cw-admin-field">
                    <label><?php esc_html_e('Categoria (ES)', 'dualgrid'); ?></label>
                    <input type="text" name="dg_cw[gallery][items][<?php echo esc_attr($index); ?>][category_es]" value="<?php echo esc_attr($item['category_es']); ?>">
                </div>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('dg_cw_render_admin_dashboard')) {
    function dg_cw_render_admin_dashboard()
    {
        dg_cw_assert_admin_access();

        $settings = dg_cw_get_settings();
        $services_count = count(dg_cw_get_active_services($settings));
        $gallery_count = count($settings['gallery']['items']);
        $inbox_email = $settings['contact']['recipient_email'] ?: sanitize_email(get_option('admin_email'));
        $content_page = admin_url('admin.php?page=dualgrid-creativewilds-content');
        $dashboard_actions = [
            [
                'eyebrow' => __('Edicion rapida', 'dualgrid'),
                'title' => __('Branding y hero', 'dualgrid'),
                'description' => __('Ajusta la marca, copys base y los textos que alimentan la portada.', 'dualgrid'),
                'href' => add_query_arg('tab', 'general', $content_page),
            ],
            [
                'eyebrow' => __('Edicion rapida', 'dualgrid'),
                'title' => __('Servicios', 'dualgrid'),
                'description' => __('Agrega, ordena o quita servicios y actualiza sus imagenes destacadas.', 'dualgrid'),
                'href' => add_query_arg('tab', 'services', $content_page),
            ],
            [
                'eyebrow' => __('Edicion rapida', 'dualgrid'),
                'title' => __('Galeria', 'dualgrid'),
                'description' => __('Administra las fotos del grid y sus categorias en ingles y espanol.', 'dualgrid'),
                'href' => add_query_arg('tab', 'gallery', $content_page),
            ],
            [
                'eyebrow' => __('Edicion rapida', 'dualgrid'),
                'title' => __('About', 'dualgrid'),
                'description' => __('Cambia el storytelling, los bloques de apoyo y el contenido editorial.', 'dualgrid'),
                'href' => add_query_arg('tab', 'about', $content_page),
            ],
            [
                'eyebrow' => __('Edicion rapida', 'dualgrid'),
                'title' => __('Contacto', 'dualgrid'),
                'description' => __('Edita correo receptor, llamadas a la accion y mensajes del formulario.', 'dualgrid'),
                'href' => add_query_arg('tab', 'contact', $content_page),
            ],
            [
                'eyebrow' => __('Gestion', 'dualgrid'),
                'title' => __('Consultas recibidas', 'dualgrid'),
                'description' => __('Abre el inbox guardado por el formulario y revisa cada lead enviado.', 'dualgrid'),
                'href' => admin_url('edit.php?post_type=dg_cw_inquiry'),
            ],
            [
                'eyebrow' => __('Recursos', 'dualgrid'),
                'title' => __('Libreria multimedia', 'dualgrid'),
                'description' => __('Sube el material visual y reutilizalo desde los bloques editables.', 'dualgrid'),
                'href' => admin_url('upload.php'),
            ],
            [
                'eyebrow' => __('Revision final', 'dualgrid'),
                'title' => __('Ver sitio', 'dualgrid'),
                'description' => __('Previsualiza la landing publicada para validar contenido, estilos y animaciones.', 'dualgrid'),
                'href' => home_url('/'),
                'external' => true,
            ],
        ];
        ?>
        <div class="wrap dg-cw-admin-page">
            <div class="dg-cw-admin-hero">
                <div class="dg-cw-admin-brand">
                    <span class="dg-cw-admin-brand__mark">
                        <img src="<?php echo esc_url(DUALGRID_PLUGIN_URL . 'assets/dualgrid.svg'); ?>" alt="<?php esc_attr_e('Logo DualGrid', 'dualgrid'); ?>">
                    </span>
                    <div class="dg-cw-admin-brand__copy">
                        <span class="dg-cw-admin-kicker"><?php esc_html_e('Dashboard del plugin', 'dualgrid'); ?></span>
                        <h1>DualGrid</h1>
                        <p><?php esc_html_e('El plugin ahora queda enfocado en la nueva landing: hero, servicios, about, galeria y contacto.', 'dualgrid'); ?></p>
                    </div>
                </div>
                <div class="dg-cw-admin-hero__actions">
                    <a class="button button-primary button-large" href="<?php echo esc_url($content_page); ?>">
                        <?php esc_html_e('Editar contenido', 'dualgrid'); ?>
                    </a>
                    <a class="button button-secondary" href="<?php echo esc_url(admin_url('edit.php?post_type=dg_cw_inquiry')); ?>">
                        <?php esc_html_e('Ver consultas', 'dualgrid'); ?>
                    </a>
                    <a class="button" href="<?php echo esc_url(home_url('/')); ?>" target="_blank" rel="noreferrer noopener">
                        <?php esc_html_e('Abrir sitio', 'dualgrid'); ?>
                    </a>
                </div>
            </div>

            <div class="dg-cw-admin-stats">
                <div class="dg-cw-admin-stat">
                    <span class="dg-cw-admin-kicker"><?php esc_html_e('Marca activa', 'dualgrid'); ?></span>
                    <strong><?php echo esc_html($settings['general']['brand_name']); ?></strong>
                </div>
                <div class="dg-cw-admin-stat">
                    <span class="dg-cw-admin-kicker"><?php esc_html_e('Servicios activos', 'dualgrid'); ?></span>
                    <strong><?php echo esc_html((string) $services_count); ?></strong>
                </div>
                <div class="dg-cw-admin-stat">
                    <span class="dg-cw-admin-kicker"><?php esc_html_e('Items de galeria', 'dualgrid'); ?></span>
                    <strong><?php echo esc_html((string) $gallery_count); ?></strong>
                </div>
                <div class="dg-cw-admin-stat">
                    <span class="dg-cw-admin-kicker"><?php esc_html_e('Inbox', 'dualgrid'); ?></span>
                    <strong><?php echo esc_html($inbox_email); ?></strong>
                </div>
            </div>

            <div class="dg-cw-admin-actions">
                <?php foreach ($dashboard_actions as $action): ?>
                    <a
                        class="dg-cw-admin-action"
                        href="<?php echo esc_url($action['href']); ?>"
                        <?php if (!empty($action['external'])): ?>
                            target="_blank" rel="noreferrer noopener"
                        <?php endif; ?>>
                        <span class="dg-cw-admin-action__eyebrow"><?php echo esc_html($action['eyebrow']); ?></span>
                        <strong class="dg-cw-admin-action__title"><?php echo esc_html($action['title']); ?></strong>
                        <span class="dg-cw-admin-action__description"><?php echo esc_html($action['description']); ?></span>
                        <span class="dg-cw-admin-action__cta">
                            <?php esc_html_e('Abrir', 'dualgrid'); ?>
                            <span aria-hidden="true">&rarr;</span>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="dg-cw-admin-cards">
                <div class="dg-cw-admin-card">
                    <h2><?php esc_html_e('Shortcodes disponibles', 'dualgrid'); ?></h2>
                    <ul class="dg-cw-code-list">
                        <li><code>[cw_hero lang="en"]</code></li>
                        <li><code>[cw_services lang="en"]</code></li>
                        <li><code>[cw_about lang="en"]</code></li>
                        <li><code>[cw_gallery lang="en"]</code></li>
                        <li><code>[cw_contact lang="en"]</code></li>
                    </ul>
                    <p><?php esc_html_e('Puedes usar lang="es" en las paginas en espanol. El hero reutiliza automaticamente los servicios activos.', 'dualgrid'); ?></p>
                </div>
                <div class="dg-cw-admin-card">
                    <h2><?php esc_html_e('Orden recomendado', 'dualgrid'); ?></h2>
                    <ol>
                        <li><code>[cw_hero]</code></li>
                        <li><code>[cw_services]</code></li>
                        <li><code>[cw_about]</code></li>
                        <li><code>[cw_gallery]</code></li>
                        <li><code>[cw_contact]</code></li>
                    </ol>
                    <p><?php esc_html_e('Las anclas por defecto son #hero, #services, #about, #work y #contact.', 'dualgrid'); ?></p>
                </div>
                <div class="dg-cw-admin-card">
                    <h2><?php esc_html_e('Flujo de edicion sugerido', 'dualgrid'); ?></h2>
                    <p><?php esc_html_e('Empieza por General para branding y hero, sigue con Servicios y Galeria, luego ajusta About y termina en Contacto para el inbox y los mensajes de exito/error.', 'dualgrid'); ?></p>
                    <p><?php esc_html_e('Las consultas enviadas desde el formulario quedan guardadas dentro del submenu Consultas.', 'dualgrid'); ?></p>
                </div>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('dg_cw_render_admin_content_page')) {
    function dg_cw_render_admin_content_page()
    {
        dg_cw_assert_admin_access();

        $settings = dg_cw_get_settings();
        $saved_notice = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dg_cw_save_content'])) {
            check_admin_referer('dg_cw_save_content');

            $raw_settings = isset($_POST['dg_cw']) && is_array($_POST['dg_cw']) ? wp_unslash($_POST['dg_cw']) : [];
            $settings = dg_cw_normalize_settings($raw_settings);
            update_option('dg_cw_settings', $settings, false);
            $saved_notice = true;
        }

        ?>
        <div class="wrap dg-cw-admin-page">
            <div class="dg-cw-admin-hero">
                <div>
                    <h1><?php esc_html_e('Contenido CreativeWilds', 'dualgrid'); ?></h1>
                    <p><?php esc_html_e('Hero, servicios, seccion about, galeria y formulario de contacto comparten este contenido.', 'dualgrid'); ?></p>
                </div>
                <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=dualgrid-creativewilds')); ?>">
                    <?php esc_html_e('Volver al dashboard', 'dualgrid'); ?>
                </a>
            </div>

            <?php if ($saved_notice): ?>
                <div class="notice notice-success"><p><?php esc_html_e('Se guardaron los cambios de CreativeWilds.', 'dualgrid'); ?></p></div>
            <?php endif; ?>

            <form method="post">
                <?php wp_nonce_field('dg_cw_save_content'); ?>

                <div class="dg-cw-admin-tabs" data-dg-cw-tabs>
                    <button type="button" class="button is-active" data-dg-cw-tab-trigger="general"><?php esc_html_e('General', 'dualgrid'); ?></button>
                    <button type="button" class="button" data-dg-cw-tab-trigger="services"><?php esc_html_e('Servicios', 'dualgrid'); ?></button>
                    <button type="button" class="button" data-dg-cw-tab-trigger="gallery"><?php esc_html_e('Galeria', 'dualgrid'); ?></button>
                    <button type="button" class="button" data-dg-cw-tab-trigger="about"><?php esc_html_e('About', 'dualgrid'); ?></button>
                    <button type="button" class="button" data-dg-cw-tab-trigger="contact"><?php esc_html_e('Contacto', 'dualgrid'); ?></button>
                </div>

                <div class="dg-cw-tab-panel is-active" data-dg-cw-tab-panel="general">
                    <div class="dg-cw-admin-card">
                        <h2><?php esc_html_e('Ajustes globales', 'dualgrid'); ?></h2>
                        <div class="dg-cw-admin-grid dg-cw-admin-grid--2">
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Nombre de marca', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[general][brand_name]" value="<?php echo esc_attr($settings['general']['brand_name']); ?>">
                            </label>
                            <div class="dg-cw-admin-grid dg-cw-admin-grid--2 dg-cw-admin-field">
                                <label>
                                    <span><?php esc_html_e('Ubicacion (EN)', 'dualgrid'); ?></span>
                                    <input type="text" name="dg_cw[general][location_en]" value="<?php echo esc_attr($settings['general']['location_en']); ?>">
                                </label>
                                <label>
                                    <span><?php esc_html_e('Ubicacion (ES)', 'dualgrid'); ?></span>
                                    <input type="text" name="dg_cw[general][location_es]" value="<?php echo esc_attr($settings['general']['location_es']); ?>">
                                </label>
                            </div>
                            <div class="dg-cw-admin-grid dg-cw-admin-grid--2 dg-cw-admin-field">
                                <label>
                                    <span><?php esc_html_e('Boton hero principal (EN)', 'dualgrid'); ?></span>
                                    <input type="text" name="dg_cw[hero][primary_button_en]" value="<?php echo esc_attr($settings['hero']['primary_button_en']); ?>">
                                </label>
                                <label>
                                    <span><?php esc_html_e('Boton hero principal (ES)', 'dualgrid'); ?></span>
                                    <input type="text" name="dg_cw[hero][primary_button_es]" value="<?php echo esc_attr($settings['hero']['primary_button_es']); ?>">
                                </label>
                            </div>
                            <div class="dg-cw-admin-grid dg-cw-admin-grid--2 dg-cw-admin-field">
                                <label>
                                    <span><?php esc_html_e('Boton hero secundario (EN)', 'dualgrid'); ?></span>
                                    <input type="text" name="dg_cw[hero][secondary_button_en]" value="<?php echo esc_attr($settings['hero']['secondary_button_en']); ?>">
                                </label>
                                <label>
                                    <span><?php esc_html_e('Boton hero secundario (ES)', 'dualgrid'); ?></span>
                                    <input type="text" name="dg_cw[hero][secondary_button_es]" value="<?php echo esc_attr($settings['hero']['secondary_button_es']); ?>">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dg-cw-tab-panel" data-dg-cw-tab-panel="services">
                    <div class="dg-cw-admin-card">
                        <div class="dg-cw-section-title">
                            <div>
                                <h2><?php esc_html_e('Servicios', 'dualgrid'); ?></h2>
                                <p><?php esc_html_e('Los servicios alimentan la cuadricula, el hero y el selector del formulario.', 'dualgrid'); ?></p>
                            </div>
                            <button type="button" class="button button-primary" data-dg-cw-add-row="services"><?php esc_html_e('Agregar servicio', 'dualgrid'); ?></button>
                        </div>
                        <div class="dg-cw-admin-grid dg-cw-admin-grid--2">
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Eyebrow (EN)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[services][eyebrow_en]" value="<?php echo esc_attr($settings['services']['eyebrow_en']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Eyebrow (ES)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[services][eyebrow_es]" value="<?php echo esc_attr($settings['services']['eyebrow_es']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Titulo (EN)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[services][title_en]" value="<?php echo esc_attr($settings['services']['title_en']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Titulo (ES)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[services][title_es]" value="<?php echo esc_attr($settings['services']['title_es']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Subtitulo (EN)', 'dualgrid'); ?></span>
                                <textarea name="dg_cw[services][subtitle_en]" rows="3"><?php echo esc_textarea($settings['services']['subtitle_en']); ?></textarea>
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Subtitulo (ES)', 'dualgrid'); ?></span>
                                <textarea name="dg_cw[services][subtitle_es]" rows="3"><?php echo esc_textarea($settings['services']['subtitle_es']); ?></textarea>
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('CTA (EN)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[services][cta_en]" value="<?php echo esc_attr($settings['services']['cta_en']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('CTA (ES)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[services][cta_es]" value="<?php echo esc_attr($settings['services']['cta_es']); ?>">
                            </label>
                        </div>

                        <div class="dg-cw-repeater" data-dg-cw-repeater="services">
                            <?php foreach ($settings['services']['items'] as $index => $item): ?>
                                <?php dg_cw_render_service_row($item, $index); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="dg-cw-tab-panel" data-dg-cw-tab-panel="gallery">
                    <div class="dg-cw-admin-card">
                        <div class="dg-cw-section-title">
                            <div>
                                <h2><?php esc_html_e('Galeria', 'dualgrid'); ?></h2>
                                <p><?php esc_html_e('Agrega, reordena o quita imagenes del mosaico editorial.', 'dualgrid'); ?></p>
                            </div>
                            <button type="button" class="button button-primary" data-dg-cw-add-row="gallery"><?php esc_html_e('Agregar imagen', 'dualgrid'); ?></button>
                        </div>
                        <div class="dg-cw-admin-grid dg-cw-admin-grid--2">
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Eyebrow (EN)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[gallery][eyebrow_en]" value="<?php echo esc_attr($settings['gallery']['eyebrow_en']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Eyebrow (ES)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[gallery][eyebrow_es]" value="<?php echo esc_attr($settings['gallery']['eyebrow_es']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Titulo (EN)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[gallery][title_en]" value="<?php echo esc_attr($settings['gallery']['title_en']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Titulo (ES)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[gallery][title_es]" value="<?php echo esc_attr($settings['gallery']['title_es']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Subtitulo (EN)', 'dualgrid'); ?></span>
                                <textarea name="dg_cw[gallery][subtitle_en]" rows="3"><?php echo esc_textarea($settings['gallery']['subtitle_en']); ?></textarea>
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Subtitulo (ES)', 'dualgrid'); ?></span>
                                <textarea name="dg_cw[gallery][subtitle_es]" rows="3"><?php echo esc_textarea($settings['gallery']['subtitle_es']); ?></textarea>
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Nota (EN)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[gallery][note_en]" value="<?php echo esc_attr($settings['gallery']['note_en']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Nota (ES)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[gallery][note_es]" value="<?php echo esc_attr($settings['gallery']['note_es']); ?>">
                            </label>
                        </div>

                        <div class="dg-cw-repeater" data-dg-cw-repeater="gallery">
                            <?php foreach ($settings['gallery']['items'] as $index => $item): ?>
                                <?php dg_cw_render_gallery_row($item, $index); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="dg-cw-tab-panel" data-dg-cw-tab-panel="about">
                    <div class="dg-cw-admin-card">
                        <h2><?php esc_html_e('Seccion About', 'dualgrid'); ?></h2>
                        <div class="dg-cw-admin-grid dg-cw-admin-grid--2">
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Eyebrow (EN)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[about][eyebrow_en]" value="<?php echo esc_attr($settings['about']['eyebrow_en']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Eyebrow (ES)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[about][eyebrow_es]" value="<?php echo esc_attr($settings['about']['eyebrow_es']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Titulo (EN)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[about][title_en]" value="<?php echo esc_attr($settings['about']['title_en']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Titulo (ES)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[about][title_es]" value="<?php echo esc_attr($settings['about']['title_es']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Texto (EN)', 'dualgrid'); ?></span>
                                <textarea name="dg_cw[about][body_en]" rows="4"><?php echo esc_textarea($settings['about']['body_en']); ?></textarea>
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Texto (ES)', 'dualgrid'); ?></span>
                                <textarea name="dg_cw[about][body_es]" rows="4"><?php echo esc_textarea($settings['about']['body_es']); ?></textarea>
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Tags (EN, una por linea)', 'dualgrid'); ?></span>
                                <textarea name="dg_cw[about][chips_en]" rows="4"><?php echo esc_textarea(implode("\n", $settings['about']['chips_en'])); ?></textarea>
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Tags (ES, una por linea)', 'dualgrid'); ?></span>
                                <textarea name="dg_cw[about][chips_es]" rows="4"><?php echo esc_textarea(implode("\n", $settings['about']['chips_es'])); ?></textarea>
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Nota (EN)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[about][note_en]" value="<?php echo esc_attr($settings['about']['note_en']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Nota (ES)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[about][note_es]" value="<?php echo esc_attr($settings['about']['note_es']); ?>">
                            </label>
                            <div class="dg-cw-admin-field dg-cw-admin-field--full">
                                <label><?php esc_html_e('Imagen vertical', 'dualgrid'); ?></label>
                                <?php dg_cw_admin_image_field('dg_cw[about][image_id]', (int) $settings['about']['image_id'], 'About image'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dg-cw-tab-panel" data-dg-cw-tab-panel="contact">
                    <div class="dg-cw-admin-card">
                        <h2><?php esc_html_e('Seccion de contacto', 'dualgrid'); ?></h2>
                        <div class="dg-cw-admin-grid dg-cw-admin-grid--2">
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Estado (EN)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[contact][status_en]" value="<?php echo esc_attr($settings['contact']['status_en']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Estado (ES)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[contact][status_es]" value="<?php echo esc_attr($settings['contact']['status_es']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Titulo (EN)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[contact][heading_en]" value="<?php echo esc_attr($settings['contact']['heading_en']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Titulo (ES)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[contact][heading_es]" value="<?php echo esc_attr($settings['contact']['heading_es']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Subtitulo (EN)', 'dualgrid'); ?></span>
                                <textarea name="dg_cw[contact][subtitle_en]" rows="3"><?php echo esc_textarea($settings['contact']['subtitle_en']); ?></textarea>
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Subtitulo (ES)', 'dualgrid'); ?></span>
                                <textarea name="dg_cw[contact][subtitle_es]" rows="3"><?php echo esc_textarea($settings['contact']['subtitle_es']); ?></textarea>
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Texto acceso directo (EN)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[contact][direct_title_en]" value="<?php echo esc_attr($settings['contact']['direct_title_en']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Texto acceso directo (ES)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[contact][direct_title_es]" value="<?php echo esc_attr($settings['contact']['direct_title_es']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span>Email</span>
                                <input type="email" name="dg_cw[contact][email]" value="<?php echo esc_attr($settings['contact']['email']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Telefono', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[contact][phone]" value="<?php echo esc_attr($settings['contact']['phone']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span>WhatsApp URL</span>
                                <input type="url" name="dg_cw[contact][whatsapp]" value="<?php echo esc_attr($settings['contact']['whatsapp']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Email receptor del formulario', 'dualgrid'); ?></span>
                                <input type="email" name="dg_cw[contact][recipient_email]" value="<?php echo esc_attr($settings['contact']['recipient_email']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Boton enviar (EN)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[contact][send_button_en]" value="<?php echo esc_attr($settings['contact']['send_button_en']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Boton enviar (ES)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[contact][send_button_es]" value="<?php echo esc_attr($settings['contact']['send_button_es']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Texto enviando (EN)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[contact][sending_button_en]" value="<?php echo esc_attr($settings['contact']['sending_button_en']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Texto enviando (ES)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[contact][sending_button_es]" value="<?php echo esc_attr($settings['contact']['sending_button_es']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Titulo exito (EN)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[contact][success_title_en]" value="<?php echo esc_attr($settings['contact']['success_title_en']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Titulo exito (ES)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[contact][success_title_es]" value="<?php echo esc_attr($settings['contact']['success_title_es']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Mensaje exito (EN)', 'dualgrid'); ?></span>
                                <textarea name="dg_cw[contact][success_message_en]" rows="3"><?php echo esc_textarea($settings['contact']['success_message_en']); ?></textarea>
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Mensaje exito (ES)', 'dualgrid'); ?></span>
                                <textarea name="dg_cw[contact][success_message_es]" rows="3"><?php echo esc_textarea($settings['contact']['success_message_es']); ?></textarea>
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Texto reinicio (EN)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[contact][success_reset_en]" value="<?php echo esc_attr($settings['contact']['success_reset_en']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Texto reinicio (ES)', 'dualgrid'); ?></span>
                                <input type="text" name="dg_cw[contact][success_reset_es]" value="<?php echo esc_attr($settings['contact']['success_reset_es']); ?>">
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Mensaje error (EN)', 'dualgrid'); ?></span>
                                <textarea name="dg_cw[contact][error_message_en]" rows="3"><?php echo esc_textarea($settings['contact']['error_message_en']); ?></textarea>
                            </label>
                            <label class="dg-cw-admin-field">
                                <span><?php esc_html_e('Mensaje error (ES)', 'dualgrid'); ?></span>
                                <textarea name="dg_cw[contact][error_message_es]" rows="3"><?php echo esc_textarea($settings['contact']['error_message_es']); ?></textarea>
                            </label>
                            <div class="dg-cw-admin-field dg-cw-admin-field--full">
                                <label><?php esc_html_e('Imagen de fondo', 'dualgrid'); ?></label>
                                <?php dg_cw_admin_image_field('dg_cw[contact][image_id]', (int) $settings['contact']['image_id'], 'Contact image'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="submit">
                    <button type="submit" name="dg_cw_save_content" class="button button-primary button-large">
                        <?php esc_html_e('Guardar contenido', 'dualgrid'); ?>
                    </button>
                </p>
            </form>

            <template id="dg-cw-service-template">
                <?php
                dg_cw_render_service_row([
                    'image_id' => 0,
                    'title_en' => '',
                    'title_es' => '',
                    'hero_lead_en' => '',
                    'hero_accent_en' => '',
                    'hero_lead_es' => '',
                    'hero_accent_es' => '',
                    'description_en' => '',
                    'description_es' => '',
                ], '__INDEX__');
                ?>
            </template>

            <template id="dg-cw-gallery-template">
                <?php
                dg_cw_render_gallery_row([
                    'image_id' => 0,
                    'category_en' => '',
                    'category_es' => '',
                ], '__INDEX__');
                ?>
            </template>
        </div>
        <?php
    }
}
