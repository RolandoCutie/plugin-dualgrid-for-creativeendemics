<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', function () {
    add_submenu_page(
        'we-tours-admin',
        'Tour Finder',
        'Tour Finder',
        'edit_others_posts',
        'we-tour-finder',
        'dg_render_tour_finder_admin_page'
    );
});

function dg_render_tour_finder_admin_page()
{
    if (!current_user_can('edit_others_posts')) {
        wp_die(esc_html__('You do not have permission to access this page.', 'dualgrid'));
    }

    $focus_options = function_exists('dg_get_tour_finder_main_focus_options')
        ? dg_get_tour_finder_main_focus_options()
        : [];
    $status_options = function_exists('dg_get_tour_finder_status_options')
        ? dg_get_tour_finder_status_options()
        : [];
    $saved_notice = false;

    $published_tours = get_posts([
        'post_type' => 'tour',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'ASC',
    ]);

    $published_lookup = [];
    foreach ($published_tours as $tour_post) {
        $slug = (string) get_post_field('post_name', $tour_post->ID);
        if ($slug === '') {
            continue;
        }

        $tour_record = function_exists('dg_get_tour_finder_tour_record')
            ? dg_get_tour_finder_tour_record((int) $tour_post->ID)
            : [];

        $published_lookup[$slug] = [
            'id' => (int) $tour_post->ID,
            'title' => get_the_title($tour_post->ID),
            'programName' => is_array($tour_record) ? (string) ($tour_record['programName'] ?? '') : '',
            'link' => is_array($tour_record) ? (string) ($tour_record['link'] ?? '') : '',
            'status' => is_array($tour_record) ? (string) ($tour_record['status'] ?? 'available') : 'available',
            'editLink' => admin_url('admin.php?page=we-tour-edit&post_id=' . (int) $tour_post->ID),
        ];
    }

    $tour_rows = [];
    if (function_exists('dg_get_tour_finder_official_catalog_seed')) {
        foreach (dg_get_tour_finder_official_catalog_seed() as $tour) {
            $slug = (string) ($tour['slug'] ?? '');
            if ($slug === '') {
                continue;
            }

            $published = $published_lookup[$slug] ?? [];
            $tour_rows[$slug] = [
                'slug' => $slug,
                'title' => !empty($published['title']) ? $published['title'] : (string) ($tour['title'] ?? $slug),
                'programName' => !empty($published['programName']) ? $published['programName'] : (string) ($tour['programSlug'] ?? ''),
                'source' => !empty($published) ? 'Official catalog + published tour' : 'Official catalog only',
                'editLink' => $published['editLink'] ?? '',
            ];
        }
    }

    foreach ($published_lookup as $slug => $published) {
        if (isset($tour_rows[$slug])) {
            continue;
        }

        $tour_rows[$slug] = [
            'slug' => $slug,
            'title' => (string) ($published['title'] ?? $slug),
            'programName' => (string) ($published['programName'] ?? ''),
            'source' => 'Published tour',
            'editLink' => (string) ($published['editLink'] ?? ''),
        ];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dg_save_tour_finder'])) {
        check_admin_referer('dg_save_tour_finder');

        $raw_programs = isset($_POST['dg_program_overrides']) && is_array($_POST['dg_program_overrides'])
            ? wp_unslash($_POST['dg_program_overrides'])
            : [];
        $clean_programs = [];

        foreach ($focus_options as $option) {
            $slug = (string) ($option['slug'] ?? '');
            if ($slug === '') {
                continue;
            }

            $values = isset($raw_programs[$slug]) && is_array($raw_programs[$slug]) ? $raw_programs[$slug] : [];

            $clean_programs[$slug] = [
                'description' => sanitize_textarea_field((string) ($values['description'] ?? '')),
                'image' => esc_url_raw((string) ($values['image'] ?? '')),
                'link' => esc_url_raw((string) ($values['link'] ?? '')),
            ];
        }

        $raw_tours = isset($_POST['dg_tour_overrides']) && is_array($_POST['dg_tour_overrides'])
            ? wp_unslash($_POST['dg_tour_overrides'])
            : [];
        $clean_tours = [];

        foreach ($tour_rows as $slug => $row) {
            $values = isset($raw_tours[$slug]) && is_array($raw_tours[$slug]) ? $raw_tours[$slug] : [];
            $status = sanitize_key((string) ($values['status'] ?? 'available'));

            if (!isset($status_options[$status])) {
                $status = 'available';
            }

            $clean_tours[$slug] = [
                'link' => esc_url_raw((string) ($values['link'] ?? '')),
                'status' => $status,
            ];
        }

        update_option('dg_tour_finder_program_overrides', $clean_programs, false);
        update_option('dg_tour_finder_tour_overrides', $clean_tours, false);
        $saved_notice = true;
    }

    $program_values = function_exists('dg_get_tour_finder_program_overrides')
        ? dg_get_tour_finder_program_overrides()
        : [];
    $tour_values = function_exists('dg_get_tour_finder_tour_overrides')
        ? dg_get_tour_finder_tour_overrides()
        : [];
    ?>
    <div class="wrap we-admin-wrap">
        <div class="we-admin-header">
            <h1>Tour Finder</h1>
            <a class="button we-back-link" href="<?php echo esc_url(admin_url('admin.php?page=we-tours-admin')); ?>">←
                Volver a tours</a>
        </div>

        <?php if ($saved_notice): ?>
            <div class="notice notice-success">
                <p>La configuración del Tour Finder se guardó correctamente.</p>
            </div>
        <?php endif; ?>

        <form method="post">
            <?php wp_nonce_field('dg_save_tour_finder'); ?>

            <div class="we-tab-intro">
                <strong>Cómo funciona ahora:</strong><br>
                Los tours publicados nuevos ya pueden entrar al Finder aunque no estén en el catálogo semilla, siempre que
                en su edición tengas bien definido <strong>Main Focus</strong>, <strong>Region</strong>,
                <strong>Duration</strong> y <strong>Status</strong>. Esta pantalla controla links y estados
                visibles del catálogo; la prioridad de coincidencia se sigue editando dentro de cada tour y la imagen del
                resultado sale del tour publicado.
            </div>

            <div class="we-sidebar-box" style="margin-bottom:24px; max-width:none;">
                <h3>Programas del Finder</h3>
                <p class="we-hint">Aquí puedes cambiar la descripción, imagen y URL pública de cada programa sin tocar
                    código.</p>
                <div style="display:grid; gap:18px;">
                    <?php foreach ($focus_options as $option): ?>
                        <?php
                        $slug = (string) ($option['slug'] ?? '');
                        $values = $program_values[$slug] ?? ['description' => '', 'image' => '', 'link' => ''];
                        ?>
                        <div class="we-repeater-row" style="margin-bottom:0;">
                            <h3 style="margin:0 0 14px 0;"><?php echo esc_html($option['label']); ?></h3>
                            <div class="we-two-col">
                                <div class="we-form-group">
                                    <label for="dg-program-link-<?php echo esc_attr($slug); ?>">URL del programa</label>
                                    <input id="dg-program-link-<?php echo esc_attr($slug); ?>" type="url"
                                        name="dg_program_overrides[<?php echo esc_attr($slug); ?>][link]"
                                        value="<?php echo esc_url($values['link'] ?? ''); ?>"
                                        placeholder="https://... o /tours/...">
                                </div>
                                <div class="we-form-group">
                                    <label for="dg-program-image-<?php echo esc_attr($slug); ?>">Imagen del programa</label>
                                    <input id="dg-program-image-<?php echo esc_attr($slug); ?>" type="url"
                                        name="dg_program_overrides[<?php echo esc_attr($slug); ?>][image]"
                                        value="<?php echo esc_url($values['image'] ?? ''); ?>" placeholder="https://...">
                                </div>
                            </div>
                            <div class="we-form-group" style="margin-bottom:0;">
                                <label for="dg-program-desc-<?php echo esc_attr($slug); ?>">Descripción</label>
                                <textarea id="dg-program-desc-<?php echo esc_attr($slug); ?>"
                                    name="dg_program_overrides[<?php echo esc_attr($slug); ?>][description]" rows="3"
                                    placeholder="Texto corto del programa"><?php echo esc_textarea($values['description'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="we-sidebar-box" style="max-width:none;">
                <h3>Tours del Finder</h3>
                <p class="we-hint">Aquí controlas la URL final y el estado de cada resultado. Si un tour es real
                    y publicado, también verás el acceso directo a su editor.</p>
                <div style="display:grid; gap:16px;">
                    <?php foreach ($tour_rows as $slug => $row): ?>
                        <?php
                        $values = $tour_values[$slug] ?? ['link' => '', 'image' => '', 'status' => 'available'];
                        ?>
                        <div class="we-repeater-row" style="margin-bottom:0;">
                            <div
                                style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; flex-wrap:wrap; margin-bottom:12px;">
                                <div>
                                    <h3 style="margin:0 0 4px 0;"><?php echo esc_html($row['title']); ?></h3>
                                    <p class="we-hint" style="margin:0; font-style:normal;">Slug:
                                        <strong><?php echo esc_html($slug); ?></strong><br>Programa:
                                        <?php echo esc_html($row['programName'] !== '' ? $row['programName'] : 'Sin programa detectado'); ?><br>Origen:
                                        <?php echo esc_html($row['source']); ?>
                                    </p>
                                </div>
                                <?php if (!empty($row['editLink'])): ?>
                                    <a class="button" href="<?php echo esc_url($row['editLink']); ?>">Editar tour</a>
                                <?php endif; ?>
                            </div>
                            <div class="we-two-col">
                                <div class="we-form-group">
                                    <label for="dg-tour-link-<?php echo esc_attr($slug); ?>">URL del resultado</label>
                                    <input id="dg-tour-link-<?php echo esc_attr($slug); ?>" type="url"
                                        name="dg_tour_overrides[<?php echo esc_attr($slug); ?>][link]"
                                        value="<?php echo esc_url($values['link'] ?? ''); ?>"
                                        placeholder="https://... o /expediciones/...">
                                </div>
                            </div>
                            <div class="we-form-group" style="margin-bottom:0; max-width:260px;">
                                <label for="dg-tour-status-<?php echo esc_attr($slug); ?>">Estado</label>
                                <select id="dg-tour-status-<?php echo esc_attr($slug); ?>"
                                    name="dg_tour_overrides[<?php echo esc_attr($slug); ?>][status]">
                                    <?php foreach ($status_options as $status_key => $status_option): ?>
                                        <option value="<?php echo esc_attr($status_key); ?>" <?php selected($values['status'] ?? 'available', $status_key); ?>><?php echo esc_html($status_option['label']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <p style="margin-top:24px;">
                <button type="submit" name="dg_save_tour_finder" class="button button-primary">Guardar configuración del
                    Tour Finder</button>
            </p>
        </form>
    </div>
    <?php
}
