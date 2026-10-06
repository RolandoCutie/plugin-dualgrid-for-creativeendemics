<?php
/**
 * Plugin Name: Dualgrid - Componentes Custom
 * Description: Shortcodes, estilos custom y popup promo dinámico con gestión de ofertas.
 * Version: 3.3.6
 * Author: RolandoCutie
 * Text Domain: dualgrid
 */

if (!defined('ABSPATH'))
    exit;

define('DUALGRID_VERSION', '3.2.6');
define('DUALGRID_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DUALGRID_PLUGIN_URL', plugin_dir_url(__FILE__));

// === 1. CARGAR CPT Y META BOXES ===
require_once DUALGRID_PLUGIN_DIR . 'includes/cpt-offers.php';

// === 2. CARGAR SHORTCODES ===
require_once DUALGRID_PLUGIN_DIR . 'includes/shortcodes.php';
require_once DUALGRID_PLUGIN_DIR . 'includes/ajax-tour-details.php';

require_once DUALGRID_PLUGIN_DIR . 'includes/cpt-tours.php';
require_once DUALGRID_PLUGIN_DIR . 'includes/meta-boxes.php';
require_once DUALGRID_PLUGIN_DIR . 'includes/template-loader.php';
require_once DUALGRID_PLUGIN_DIR . 'includes/admin-menu.php';
require_once DUALGRID_PLUGIN_DIR . 'includes/admin-list.php';
require_once DUALGRID_PLUGIN_DIR . 'includes/admin-form.php';
require_once DUALGRID_PLUGIN_DIR . 'includes/form-handler.php';

// === PAR CHE TEMPORAL: Suprimir notice de Action Scheduler ===
add_action('init', function () {
    if (!did_action('action_scheduler_init')) {
        add_filter('doing_it_wrong_trigger_error', '__return_false');
    }
}, 1);

// === 3. ENCOLAR CSS Y JS ===
add_action('wp_enqueue_scripts', function () {
    // Jost (Google Fonts) - pesos 400, 500, 700, 900 + italic
    wp_enqueue_style(
        'dualgrid-font-jost',
        'https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,400;0,500;0,700;0,900;1,400;1,900&display=swap',
        [],
        null
    );

    // Fraunces (Google Fonts) - pesos 300, 400, 500, 600 + italic
    wp_enqueue_style(
        'dualgrid-font-fraunces',
        'https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,400;1,9..144,500&display=swap',
        [],
        null
    );

    // Satoshi (Fontshare CDN)
    wp_enqueue_style(
        'dualgrid-font-satoshi',
        'https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700&display=swap',
        [],
        null
    );

    wp_enqueue_style('dualgrid-global-typography', DUALGRID_PLUGIN_URL . 'css/global-typography.css', ['dualgrid-font-jost', 'dualgrid-font-fraunces', 'dualgrid-font-satoshi'], DUALGRID_VERSION);
    wp_enqueue_style('dualgrid-tours', DUALGRID_PLUGIN_URL . 'css/tours.css', [], DUALGRID_VERSION);
    wp_enqueue_style('dualgrid-faqs', DUALGRID_PLUGIN_URL . 'css/faqs.css', [], DUALGRID_VERSION);
    wp_enqueue_style('dualgrid-promo', DUALGRID_PLUGIN_URL . 'css/promo-popup.css', [], DUALGRID_VERSION);
    wp_enqueue_style('dualgrid-wizard', DUALGRID_PLUGIN_URL . 'css/wizard.css', [], DUALGRID_VERSION);
    wp_enqueue_style('dualgrid-hero-slider', DUALGRID_PLUGIN_URL . 'css/hero-slider.css', [], DUALGRID_VERSION);
    wp_enqueue_style('dualgrid-wild-tours', DUALGRID_PLUGIN_URL . 'css/wild-tours.css', [], DUALGRID_VERSION);
    wp_enqueue_style('dualgrid-tour-detail', DUALGRID_PLUGIN_URL . 'css/tour-detail.css', [], DUALGRID_VERSION);
    wp_enqueue_style('dualgrid-galeria', DUALGRID_PLUGIN_URL . 'css/galeria.css', [], DUALGRID_VERSION);
    wp_enqueue_style('dualgrid-blog', DUALGRID_PLUGIN_URL . 'css/blog.css', [], DUALGRID_VERSION);
    wp_enqueue_style('dualgrid-contact', DUALGRID_PLUGIN_URL . 'css/contact.css', [], DUALGRID_VERSION);
    wp_enqueue_style('dualgrid-overview', DUALGRID_PLUGIN_URL . 'css/overview.css', [], DUALGRID_VERSION);
    wp_enqueue_style('dualgrid-mission', DUALGRID_PLUGIN_URL . 'css/mission.css', [], DUALGRID_VERSION);
    wp_enqueue_style('dualgrid-values', DUALGRID_PLUGIN_URL . 'css/values.css', [], DUALGRID_VERSION);
    wp_enqueue_style('dualgrid-team', DUALGRID_PLUGIN_URL . 'css/team.css', [], DUALGRID_VERSION);
    wp_enqueue_style('dualgrid-form', DUALGRID_PLUGIN_URL . 'css/form.css', [], DUALGRID_VERSION);
    wp_enqueue_style('dualgrid-tour-modal', DUALGRID_PLUGIN_URL . 'css/tour-modal.css', [], DUALGRID_VERSION);
    wp_enqueue_style('dualgrid-testimonials', DUALGRID_PLUGIN_URL . 'css/testimonials.css', [], DUALGRID_VERSION);
    wp_enqueue_script('dualgrid-languaje', DUALGRID_PLUGIN_URL . 'js/languaje.js', [], DUALGRID_VERSION, true);
    wp_enqueue_script('dualgrid-promo', DUALGRID_PLUGIN_URL . 'js/promo-popup.js', [], DUALGRID_VERSION, true);
    wp_enqueue_script('dualgrid-wizard', DUALGRID_PLUGIN_URL . 'js/wizard.js', [], DUALGRID_VERSION, true);
    wp_enqueue_script('dualgrid-faqs', DUALGRID_PLUGIN_URL . 'js/faqs.js', [], DUALGRID_VERSION, true);
    wp_enqueue_script('dualgrid-hero-slider', DUALGRID_PLUGIN_URL . 'js/hero-slider.js', [], DUALGRID_VERSION, true);
    wp_enqueue_script('dualgrid-wild-tours', DUALGRID_PLUGIN_URL . 'js/wild-tours.js', [], DUALGRID_VERSION, true);
    wp_enqueue_script('dualgrid-tour-modal', DUALGRID_PLUGIN_URL . 'js/tour-modal.js', [], DUALGRID_VERSION, true);
    wp_enqueue_script('dualgrid-galeria', DUALGRID_PLUGIN_URL . 'js/galeria.js', [], DUALGRID_VERSION, true);
    wp_enqueue_script('dualgrid-animations', DUALGRID_PLUGIN_URL . 'js/animations.js', [], DUALGRID_VERSION, true);
    wp_enqueue_script('dualgrid-blog', DUALGRID_PLUGIN_URL . 'js/blog.js', [], DUALGRID_VERSION, true);
    wp_enqueue_script('dualgrid-overview', DUALGRID_PLUGIN_URL . 'js/overview.js', [], DUALGRID_VERSION, true);
    wp_enqueue_script('dualgrid-form', DUALGRID_PLUGIN_URL . 'js/form.js', [], DUALGRID_VERSION, true);
    wp_enqueue_script('dualgrid-testimonials', DUALGRID_PLUGIN_URL . 'js/testimonials.js', [], DUALGRID_VERSION, true);
    wp_enqueue_script('dualgrid-tour-modal-detail', DUALGRID_PLUGIN_URL . 'js/tour-modal-detail.js', [], DUALGRID_VERSION, true);
    // Leaflet CSS (solo si hay un shortcode de tours en la página)
    wp_enqueue_style(
        'leaflet-css',
        'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
        [],
        '1.9.4'
    );

    // Leaflet JS
    wp_enqueue_script(
        'leaflet-js',
        'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
        [],
        '1.9.4',
        true
    );

    wp_localize_script('dualgrid-tour-modal-detail', 'geTourModal', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('we_tour_modal_nonce'),
    ]);

    if (function_exists('dg_get_tour_finder_wizard_data')) {
        wp_localize_script('dualgrid-wizard', 'dgWizardData', dg_get_tour_finder_wizard_data());
    }

});

add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'toplevel_page_we-tours-admin' || $hook === 'tours_page_we-tour-add' || $hook === 'admin_page_we-tour-edit') {
        wp_enqueue_media();
        wp_enqueue_style(
            'dualgrid-admin-tours',
            DUALGRID_PLUGIN_URL . 'css/admin-tours.css',
            [],
            DUALGRID_VERSION
        );
    }
});

// ─── Admin CSS ───
add_action('admin_enqueue_scripts', function ($hook) {
    global $post_type;
    if (($hook === 'post.php' || $hook === 'post-new.php') && $post_type === 'tour') {
        wp_enqueue_style(
            'mi-agencia-admin-tours',
            DUALGRID_PLUGIN_URL . 'css/admin-tours.css',
            [],
            DUALGRID_VERSION
        );
    }
});

// ============================================
// 3. (OPCIONAL) Plan B: CSS inline con prioridad 999
// Si Blocksy/Elementor siguen ganando, descomenta esto:
/*
add_action('wp_head', function() {
    echo '<style>';
    echo 'h1,h2,h3,h4,h5,h6,.entry-title,.elementor-heading-title{font-family:"Jost","Fraunces",serif !important;font-weight:900 !important;}';
    echo 'h1 em,h2 em,h3 em,h4 em,h5 em,h6 em,.entry-title em,.elementor-heading-title em{font-family:"Jost","Fraunces",serif !important;font-weight:900 !important;font-style:italic !important;}';
    echo 'body,p,li{font-family:"Fraunces","Georgia",serif !important;}';
    echo 'button,.button,.elementor-button,.btn{font-family:"Satoshi","Helvetica Neue",sans-serif !important;}';
    echo '</style>';
}, 999);
*/

// === 4. INYECTAR POPUP PROMO DINÁMICO EN EL FOOTER ===
add_action('wp_footer', function () {
    if (is_admin())
        return;

    // Buscar TODAS las ofertas activas
    $offers_query = get_posts([
        'post_type' => 'dg_offer',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_key' => '_dg_active',
        'meta_value' => 'yes',
    ]);

    if (empty($offers_query))
        return;

    $offers = [];
    foreach ($offers_query as $offer) {
        $offer_id = $offer->ID;
        $img = get_the_post_thumbnail_url($offer_id, 'large');
        $offers[] = [
            'title' => get_the_title($offer_id),
            'image' => $img ?: '',
            'discount' => get_post_meta($offer_id, '_dg_discount', true) ?: '10% OFF',
            'desc' => get_post_meta($offer_id, '_dg_description', true) ?: $offer->post_excerpt,
            'price_new' => get_post_meta($offer_id, '_dg_price_new', true) ?: '$3,105',
            'price_old' => get_post_meta($offer_id, '_dg_price_old', true) ?: '$3,450',
            'button_url' => get_post_meta($offer_id, '_dg_button_url', true) ?: '/tours',
            'button_text' => get_post_meta($offer_id, '_dg_button_text', true) ?: 'View Expedition',
            'spots' => get_post_meta($offer_id, '_dg_spots_text', true) ?: '✦ LIMITED SPOTS',
        ];
    }

    $first = $offers[0];
    $offers_json = json_encode($offers, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    $total_offers = count($offers);
    $has_multiple = $total_offers > 1;
    ?>
    <div id="dg-promo-popup" data-state="collapsed" data-offers='<?php echo $offers_json; ?>'>
        <div class="dg-promo-open" id="dg-promo-open">
            <?php if ($has_multiple): ?>
                <!-- Flechas de navegación -->
                <button class="dg-promo-nav dg-promo-nav-prev" id="dg-promo-prev" aria-label="Previous">‹</button>
                <button class="dg-promo-nav dg-promo-nav-next" id="dg-promo-next" aria-label="Next">›</button>
                <div class="dg-promo-counter" id="dg-promo-counter">1 / <?php echo $total_offers; ?></div>
            <?php endif; ?>

            <div class="dg-promo-image-wrap">
                <div class="dg-promo-image" id="dg-promo-image"
                    style="background-image: url('<?php echo esc_url($first['image']); ?>');"></div>
                <div class="dg-promo-badge" id="dg-promo-badge"><?php echo esc_html($first['discount']); ?></div>
                <button class="dg-promo-close-x" id="dg-promo-close-x" aria-label="Close">×</button>
                <div class="dg-promo-spots" id="dg-promo-spots"><?php echo esc_html($first['spots']); ?></div>
            </div>
            <div class="dg-promo-body">
                <h3 class="dg-promo-title" id="dg-promo-title"><?php echo esc_html($first['title']); ?></h3>
                <p class="dg-promo-desc" id="dg-promo-desc"><?php echo esc_html($first['desc']); ?></p>
                <div class="dg-promo-price">
                    <span class="dg-promo-price-new"
                        id="dg-promo-price-new"><?php echo esc_html($first['price_new']); ?></span>
                    <span class="dg-promo-price-old"
                        id="dg-promo-price-old"><?php echo esc_html($first['price_old']); ?></span>
                </div>
                <a href="<?php echo esc_url($first['button_url']); ?>" class="dg-promo-btn" id="dg-promo-btn">
                    <span id="dg-promo-btn-text"><?php echo esc_html($first['button_text']); ?></span>
                    <span class="dg-promo-btn-arrow">→</span>
                </a>
            </div>
            <div class="dg-promo-progress">
                <div class="dg-promo-progress-bar" id="dg-promo-progress-bar"></div>
            </div>
        </div>
        <div class="dg-promo-collapsed" id="dg-promo-collapsed">
            <div class="dg-promo-bar"></div>
            <div class="dg-promo-collapsed-text">
                <div class="dg-promo-collapsed-label">Special</div>
                <div class="dg-promo-collapsed-offer" id="dg-promo-collapsed-offer">
                    <?php echo esc_html($first['discount']); ?>
                </div>
            </div>
            <span class="dg-promo-collapsed-arrow">‹</span>
        </div>
    </div>
    <?php
});

/* ============================================
   AGREGAR TODO ESTO EN dualgrid.php
   ============================================ */

// ============================================
// TESTIMONIALS - CPT
// ============================================
add_action('init', function () {
    register_post_type('testimonial', [
        'labels' => [
            'name' => 'Testimonials',
            'singular_name' => 'Testimonial',
            'add_new' => 'Añadir nuevo',
            'edit_item' => 'Editar testimonio',
            'all_items' => 'Todos los testimonials',
        ],
        'public' => true,
        'has_archive' => false,
        'supports' => ['title', 'editor', 'thumbnail'],
        'menu_icon' => 'dashicons-format-quote',
        'rewrite' => ['slug' => 'testimonial'],
    ]);
});

// ============================================
// TESTIMONIALS - META BOXES
// ============================================
add_action('add_meta_boxes', function () {
    add_meta_box('tp_meta', 'Datos del Cliente', 'dg_tp_render_meta', 'testimonial', 'normal', 'high');
});

function dg_tp_render_meta($post)
{
    wp_nonce_field('dg_tp_save_meta', 'dg_tp_nonce');
    $ubicacion = get_post_meta($post->ID, '_tp_ubicacion', true);
    $valoracion = get_post_meta($post->ID, '_tp_valoracion', true);
    $token = get_post_meta($post->ID, '_tp_token', true);
    $estado = get_post_meta($post->ID, '_tp_estado', true) ?: 'pendiente';
    ?>
    <p>
        <label><strong>Ubicación:</strong></label><br>
        <input type="text" name="tp_ubicacion" value="<?php echo esc_attr($ubicacion); ?>" style="width:100%;">
    </p>
    <p>
        <label><strong>Valoración (1-5):</strong></label><br>
        <input type="number" name="tp_valoracion" min="1" max="5" value="<?php echo esc_attr($valoracion ?: 5); ?>"
            style="width:100px;">
    </p>
    <p>
        <label><strong>Token de invitación:</strong></label><br>
        <input type="text" name="tp_token" value="<?php echo esc_attr($token); ?>" style="width:100%;" readonly>
        <small>Token único que se envía al cliente para que pueda acceder al formulario.</small>
    </p>
    <p>
        <label><strong>Estado de moderación:</strong></label><br>
        <select name="tp_estado">
            <option value="pendiente" <?php selected($estado, 'pendiente'); ?>>🟡 Pendiente</option>
            <option value="aprobado" <?php selected($estado, 'aprobado'); ?>>🟢 Aprobado</option>
            <option value="rechazado" <?php selected($estado, 'rechazado'); ?>>🔴 Rechazado</option>
        </select>
    </p>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['dg_tp_nonce']) || !wp_verify_nonce($_POST['dg_tp_nonce'], 'dg_tp_save_meta'))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!current_user_can('edit_post', $post_id))
        return;

    if (isset($_POST['tp_ubicacion']))
        update_post_meta($post_id, '_tp_ubicacion', sanitize_text_field($_POST['tp_ubicacion']));
    if (isset($_POST['tp_valoracion']))
        update_post_meta($post_id, '_tp_valoracion', intval($_POST['tp_valoracion']));
    if (isset($_POST['tp_estado']))
        update_post_meta($post_id, '_tp_estado', sanitize_text_field($_POST['tp_estado']));
});

// Generar token automático al crear un testimonio vacío (desde admin)
add_action('wp_insert_post', function ($post_id, $post) {
    if ($post->post_type !== 'testimonial')
        return;
    if (!empty(get_post_meta($post_id, '_tp_token', true)))
        return;
    $token = wp_generate_password(20, false);
    update_post_meta($post_id, '_tp_token', $token);
    update_post_meta($post_id, '_tp_estado', 'pendiente');
}, 10, 2);

// ============================================
// TESTIMONIALS - ADMIN PAGE: GENERAR LINKS + GESTIÓN
// ============================================
add_action('admin_menu', function () {
    add_submenu_page(
        'edit.php?post_type=testimonial',
        'Generar Links',
        '🔗 Generar Links',
        'manage_options',
        'tp-generar-links',
        'dg_tp_admin_links_page'
    );
});

function dg_tp_admin_links_page()
{
    // Procesar acciones (aprobbar, rechazar, eliminar)
    if (isset($_GET['tp_action'], $_GET['post_id']) && check_admin_referer('tp_admin_action')) {
        $pid = intval($_GET['post_id']);
        $action = sanitize_text_field($_GET['tp_action']);
        switch ($action) {
            case 'aprobbar':
                update_post_meta($pid, '_tp_estado', 'aprobado');
                wp_update_post(['ID' => $pid, 'post_status' => 'publish']);
                echo '<div class="notice notice-success"><p>✅ Testimonial aprobado y publicado.</p></div>';
                break;
            case 'rechazar':
                update_post_meta($pid, '_tp_estado', 'rechazado');
                wp_update_post(['ID' => $pid, 'post_status' => 'draft']);
                echo '<div class="notice notice-warning"><p>🔴 Testimonial rechazado.</p></div>';
                break;
            case 'eliminar':
                wp_delete_post($pid, true);
                echo '<div class="notice notice-success"><p>🗑️ Testimonial eliminado permanentemente.</p></div>';
                break;
        }
    }

    $token = '';
    $link = '';
    $msg = '';

    // Crear nuevo testimonio placeholder y generar link
    if (isset($_POST['tp_crear_link']) && check_admin_referer('tp_crear_link_nonce')) {
        $nombre = sanitize_text_field($_POST['tp_cliente_nombre'] ?: 'Cliente');
        $post_id = wp_insert_post([
            'post_type' => 'testimonial',
            'post_title' => $nombre,
            'post_status' => 'draft',
            'post_content' => '',
        ]);
        if ($post_id) {
            $token = get_post_meta($post_id, '_tp_token', true);
            if (empty($token)) {
                $token = wp_generate_password(20, false);
                update_post_meta($post_id, '_tp_token', $token);
                update_post_meta($post_id, '_tp_estado', 'pendiente');
            }
            update_post_meta($post_id, '_tp_ubicacion', sanitize_text_field($_POST['tp_cliente_ubicacion'] ?: ''));
            $link = home_url("/enviar-testimonio/?token=$token");
            $msg = '✅ Link generado correctamente. Envíalo al cliente.';
        }
    }
    ?>
    <div class="wrap">
        <h1>🔗 Generar Link de Invitación</h1>
        <?php if ($msg)
            echo '<div class="notice notice-success"><p>' . esc_html($msg) . '</p></div>'; ?>

        <div style="background:#fff;padding:20px;border:1px solid #c3c4c7;max-width:600px;">
            <form method="post">
                <?php wp_nonce_field('tp_crear_link_nonce'); ?>
                <p>
                    <label><strong>Nombre del cliente:</strong></label><br>
                    <input type="text" name="tp_cliente_nombre" style="width:100%;" placeholder="Ej: Elena Marchetti"
                        required>
                </p>
                <p>
                    <label><strong>Ubicación (País/Ciudad):</strong></label><br>
                    <input type="text" name="tp_cliente_ubicacion" style="width:100%;" placeholder="Ej: Italy">
                </p>
                <p>
                    <button type="submit" name="tp_crear_link" class="button button-primary">Generar link de
                        invitación</button>
                </p>
            </form>

            <?php if ($link): ?>
                <hr>
                <h3>Link generado:</h3>
                <div style="background:#f0f0f1;padding:12px;border-radius:4px;word-break:break-all;font-family:monospace;">
                    <?php echo esc_url($link); ?>
                </div>
                <p>
                    <button class="button"
                        onclick="navigator.clipboard.writeText('<?php echo esc_js($link); ?>');this.textContent='¡Copiado!';">Copiar
                        al portapapeles</button>
                </p>
            <?php endif; ?>
        </div>

        <h2 style="margin-top:40px;">📋 Gestión de Testimonials</h2>
        <p>Desde aquí puedes <strong>aprobar, rechazar, editar o eliminar</strong> cualquier testimonio.</p>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Ubicación</th>
                    <th>Valoración</th>
                    <th>Estado</th>
                    <th>Foto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $todos = new WP_Query([
                    'post_type' => 'testimonial',
                    'posts_per_page' => -1,
                    'post_status' => 'any',
                ]);
                if ($todos->have_posts()):
                    while ($todos->have_posts()):
                        $todos->the_post();
                        $pid = get_the_ID();
                        $estado = get_post_meta($pid, '_tp_estado', true) ?: 'pendiente';
                        $ubi = get_post_meta($pid, '_tp_ubicacion', true);
                        $val = get_post_meta($pid, '_tp_valoracion', true) ?: '-';
                        $has_img = has_post_thumbnail($pid) ? '✅ Sí' : '❌ No';
                        $edit_link = admin_url("post.php?post=$pid&action=edit");
                        $nonce = wp_create_nonce('tp_admin_action');
                        ?>
                        <tr>
                            <td><?php the_title(); ?></td>
                            <td><?php echo esc_html($ubi); ?></td>
                            <td><?php echo esc_html($val); ?>/5</td>
                            <td>
                                <?php if ($estado === 'aprobado'): ?>
                                    <span style="color:green;font-weight:600;">🟢 Aprobado</span>
                                <?php elseif ($estado === 'rechazado'): ?>
                                    <span style="color:#d63638;font-weight:600;">🔴 Rechazado</span>
                                <?php else: ?>
                                    <span style="color:#dba617;font-weight:600;">🟡 Pendiente</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $has_img; ?></td>
                            <td>
                                <a href="<?php echo esc_url($edit_link); ?>" class="button button-small">✏️ Editar</a>
                                <a href="<?php echo esc_url(admin_url("edit.php?post_type=testimonial&page=tp-generar-links&tp_action=aprobar&post_id=$pid&_wpnonce=$nonce")); ?>"
                                    class="button button-small" style="color:green;">✅ Aprobar</a>
                                <a href="<?php echo esc_url(admin_url("edit.php?post_type=testimonial&page=tp-generar-links&tp_action=rechazar&post_id=$pid&_wpnonce=$nonce")); ?>"
                                    class="button button-small" style="color:#dba617;">🛑 Rechazar</a>
                                <a href="<?php echo esc_url(admin_url("edit.php?post_type=testimonial&page=tp-generar-links&tp_action=eliminar&post_id=$pid&_wpnonce=$nonce")); ?>"
                                    class="button button-small" style="color:#d63638;"
                                    onclick="return confirm('¿Seguro que quieres eliminar este testimonio? No se puede deshacer.')">🗑️
                                    Eliminar</a>
                            </td>
                        </tr>
                        <?php
                    endwhile;
                else:
                    echo '<tr><td colspan="6" style="text-align:center;">No hay testimonials creados aún.</td></tr>';
                endif;
                wp_reset_postdata();
                ?>
            </tbody>
        </table>
    </div>
    <?php
}

// ============================================
// TESTIMONIALS - ENDPOINT REST
// ============================================
add_action('rest_api_init', function () {
    register_rest_route('testimonials/v1', '/enviar', [
        'methods' => 'POST',
        'callback' => 'dg_tp_procesar_envio',
        'permission_callback' => '__return_true'
    ]);
});

function dg_tp_procesar_envio(WP_REST_Request $req)
{
    $token = sanitize_text_field($req->get_param('token') ?: '');
    if (!$token)
        return new WP_Error('no_token', 'Token is required.', ['status' => 403]);

    $query = new WP_Query([
        'post_type' => 'testimonial',
        'posts_per_page' => 1,
        'post_status' => 'any',
        'meta_query' => [
            ['key' => '_tp_token', 'value' => $token, 'compare' => '=']
        ]
    ]);

    if (!$query->have_posts()) {
        return new WP_Error('token_invalido', 'Invalid or expired token.', ['status' => 403]);
    }

    $post_id = $query->posts[0]->ID;

    wp_update_post([
        'ID' => $post_id,
        'post_title' => sanitize_text_field($req->get_param('nombre')),
        'post_content' => sanitize_textarea_field($req->get_param('testimonio')),
        'post_status' => 'draft',
    ]);

    update_post_meta($post_id, '_tp_ubicacion', sanitize_text_field($req->get_param('ubicacion')));
    update_post_meta($post_id, '_tp_valoracion', intval($req->get_param('valoracion')));

    if (!empty($_FILES['foto'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $upload = wp_upload_bits($_FILES['foto']['name'], null, file_get_contents($_FILES['foto']['tmp_name']));
        if (empty($upload['error'])) {
            $filetype = wp_check_filetype($upload['file']);
            $attachment = [
                'post_mime_type' => $filetype['type'],
                'post_title' => sanitize_file_name($_FILES['foto']['name']),
                'post_content' => '',
                'post_status' => 'inherit'
            ];
            $attach_id = wp_insert_attachment($attachment, $upload['file'], $post_id);
            $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
            wp_update_attachment_metadata($attach_id, $attach_data);
            set_post_thumbnail($post_id, $attach_id);
        }
    }

    return new WP_REST_Response([
        'success' => true,
        'message' => 'Thank you. Your testimonial is pending approval.'
    ], 200);
}

// ============================================
// TESTIMONIALS - TEMPLATE: FORMULARIO FRONTEND
// ============================================
add_filter('template_include', function ($template) {
    if (is_feed()) {
        return $template;
    }
    if (is_page('enviar-testimonio') || get_query_var('pagename') === 'enviar-testimonio') {
        $custom = DUALGRID_PLUGIN_DIR . 'templates/form-testimonial.php';
        if (file_exists($custom))
            return $custom;
    }
    return $template;
});

/* ============================================================
   1. TAXONOMÍA: Categorías de la Galería DualGrid
   ============================================================ */
add_action('init', function () {
    register_taxonomy('dg_gallery_cat', 'attachment', [
        'labels' => [
            'name' => 'Categorías Galería',
            'singular_name' => 'Categoría Galería',
            'search_items' => 'Buscar Categorías',
            'all_items' => 'Todas las Categorías',
            'edit_item' => 'Editar Categoría',
            'add_new_item' => 'Agregar Nueva Categoría',
            'menu_name' => 'Cat. Galería',
        ],
        'hierarchical' => true,
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => false,
    ]);
});

/* ============================================================
   2. CAMPO CATEGORÍA EN EL MODAL DE EDICIÓN (Attachment Details)
   ============================================================ */
add_filter('attachment_fields_to_edit', function ($form_fields, $post) {
    $terms = get_terms([
        'taxonomy' => 'dg_gallery_cat',
        'hide_empty' => false,
    ]);

    $assigned = wp_get_object_terms($post->ID, 'dg_gallery_cat');
    $assigned_id = 0;
    if (!empty($assigned) && !is_wp_error($assigned)) {
        $assigned_id = (int) $assigned[0]->term_id;
    }

    $html = '<select name="attachments[' . $post->ID . '][dg_gallery_cat_id]" style="width:100%;">';
    $html .= '<option value="">— Sin categoría —</option>';
    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $sel = selected($assigned_id, (int) $term->term_id, false);
            $html .= '<option value="' . esc_attr($term->term_id) . '"' . $sel . '>' . esc_html($term->name) . '</option>';
        }
    }
    $html .= '</select>';

    $form_fields['dg_gallery_cat'] = [
        'label' => 'Categoría Galería',
        'input' => 'html',
        'html' => $html,
        'helps' => 'Selecciona la categoría para mostrarla en [dualgrid_galeria]',
    ];

    $form_fields['dg_location'] = [
        'label' => 'Ubicación',
        'input' => 'text',
        'value' => get_post_meta($post->ID, '_dg_location', true),
        'helps' => 'Ej: Tambopata, Perú',
    ];

    return $form_fields;
}, 10, 2);

/* ============================================================
   3. GUARDAR CATEGORÍA Y UBICACIÓN (desde el modal)
   ============================================================ */
add_filter('attachment_fields_to_save', function ($post, $attachment) {
    if (isset($attachment['dg_gallery_cat_id'])) {
        $cat_id = intval($attachment['dg_gallery_cat_id']);
        if ($cat_id > 0) {
            wp_set_object_terms($post['ID'], [$cat_id], 'dg_gallery_cat');
        } else {
            wp_set_object_terms($post['ID'], [], 'dg_gallery_cat');
        }
    }

    if (isset($attachment['dg_location'])) {
        update_post_meta($post['ID'], '_dg_location', sanitize_text_field($attachment['dg_location']));
    }

    return $post;
}, 10, 2);

/* ============================================================
   4. METABOX EN LA PÁGINA DE EDICIÓN INDIVIDUAL DEL ATTACHMENT
   ============================================================ */
add_action('add_meta_boxes', function () {
    add_meta_box(
        'dg_gallery_meta',
        'DualGrid — Categoría de Galería',
        function ($post) {
            wp_nonce_field('dg_save_meta', 'dg_meta_nonce');

            $terms = get_terms([
                'taxonomy' => 'dg_gallery_cat',
                'hide_empty' => false,
            ]);

            $assigned = wp_get_object_terms($post->ID, 'dg_gallery_cat');
            $assigned_id = 0;
            if (!empty($assigned) && !is_wp_error($assigned)) {
                $assigned_id = (int) $assigned[0]->term_id;
            }

            echo '<p><label for="dg_gallery_cat_id"><strong>Categoría:</strong></label></p>';
            if (empty($terms) || is_wp_error($terms)) {
                echo '<p style="color:#d63638;">Crea categorías primero en <strong>Medios &rarr; Cat. Galería</strong>.</p>';
            } else {
                echo '<select name="dg_gallery_cat_id" id="dg_gallery_cat_id" style="width:100%;">';
                echo '<option value="">— Sin categoría —</option>';
                foreach ($terms as $term) {
                    echo '<option value="' . esc_attr($term->term_id) . '"' . selected($assigned_id, (int) $term->term_id, false) . '>' . esc_html($term->name) . '</option>';
                }
                echo '</select>';
            }

            $location = get_post_meta($post->ID, '_dg_location', true);
            echo '<p style="margin-top:12px;"><label for="dg_location"><strong>Ubicación:</strong></label></p>';
            echo '<input type="text" name="dg_location" id="dg_location" value="' . esc_attr($location) . '" style="width:100%;" placeholder="Ej: Tambopata, Perú">';
        },
        'attachment',
        'side',
        'high'
    );
});

add_action('edit_attachment', function ($post_id) {
    if (!isset($_POST['dg_meta_nonce']) || !wp_verify_nonce($_POST['dg_meta_nonce'], 'dg_save_meta')) {
        return;
    }

    if (isset($_POST['dg_gallery_cat_id'])) {
        $cat_id = intval($_POST['dg_gallery_cat_id']);
        if ($cat_id > 0) {
            wp_set_object_terms($post_id, [$cat_id], 'dg_gallery_cat');
        } else {
            wp_set_object_terms($post_id, [], 'dg_gallery_cat');
        }
    }

    if (isset($_POST['dg_location'])) {
        update_post_meta($post_id, '_dg_location', sanitize_text_field($_POST['dg_location']));
    }
});

/* ============================================================
   5. COLUMNA EN LA VISTA DE LISTA DE MEDIOS
   ============================================================ */
add_filter('manage_media_columns', function ($columns) {
    $new_columns = [];
    foreach ($columns as $key => $label) {
        $new_columns[$key] = $label;
        if ($key === 'title') {
            $new_columns['dg_gallery_cat'] = 'Cat. Galería';
        }
    }
    return $new_columns;
});

add_action('manage_media_custom_column', function ($column_name, $post_id) {
    if ($column_name !== 'dg_gallery_cat')
        return;

    $terms = get_the_terms($post_id, 'dg_gallery_cat');
    if (!empty($terms) && !is_wp_error($terms)) {
        $names = wp_list_pluck($terms, 'name');
        echo '<span style="color:#1B4332;font-weight:600;">' . esc_html(implode(', ', $names)) . '</span>';
    } else {
        echo '<span style="color:#999;">—</span>';
    }
}, 10, 2);

/* ============================================================
   6. FILTRO POR CATEGORÍA EN LA BIBLIOTECA DE MEDIOS
   ============================================================ */
add_action('restrict_manage_posts', function () {
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'upload')
        return;

    $terms = get_terms([
        'taxonomy' => 'dg_gallery_cat',
        'hide_empty' => false,
    ]);

    if (empty($terms) || is_wp_error($terms))
        return;

    $selected = isset($_GET['dg_gallery_cat']) ? sanitize_text_field($_GET['dg_gallery_cat']) : '';

    echo '<select name="dg_gallery_cat" style="float:none;margin-right:6px;">';
    echo '<option value="">Todas las categorías</option>';
    foreach ($terms as $term) {
        printf(
            '<option value="%s"%s>%s</option>',
            esc_attr($term->slug),
            selected($selected, $term->slug, false),
            esc_html($term->name)
        );
    }
    echo '</select>';
});

add_action('pre_get_posts', function ($query) {
    if (!is_admin() || !$query->is_main_query())
        return;
    if ($query->get('post_type') !== 'attachment')
        return;

    $cat = isset($_GET['dg_gallery_cat']) ? sanitize_text_field($_GET['dg_gallery_cat']) : '';
    if (empty($cat))
        return;

    $tax_query = $query->get('tax_query') ?: [];
    $tax_query[] = [
        'taxonomy' => 'dg_gallery_cat',
        'field' => 'slug',
        'terms' => $cat,
    ];
    $query->set('tax_query', $tax_query);
});

/**
 * Tour Wizard — Overlay global (UNO solo por página)
 * Se inyecta automáticamente al final del <body> en TODAS las páginas.
 */
add_action('wp_footer', function () {
    static $wizard_overlay_done = false;
    if ($wizard_overlay_done || is_admin())
        return;
    $wizard_overlay_done = true;
    ?>
    <div id="dgWizardOverlay" class="dg-wizard-overlay">
        <div class="dg-progress-bar-container">
            <div class="dg-progress-bar-fill"></div>
        </div>
        <div class="dg-step-indicator">Tour Finder · 01 / 03</div>
        <button class="dg-wizard-close" aria-label="Cerrar" onclick="dgCloseWizard()">✕</button>
        <div id="dgWizardContent" class="dg-wizard-content"></div>
    </div>
    <?php
}, 999);

add_action('wp_footer', function() {
    if (is_admin() || !function_exists('dg_render_tailor_made_modal')) {
        return;
    }

    dg_render_tailor_made_modal(function_exists('dg_get_tailor_made_cf7_id') ? dg_get_tailor_made_cf7_id() : '6f7a331');
}, 1000);
