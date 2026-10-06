<?php
// === includes/admin-list.php ===
// Pantalla de lista de tours con buscador y acciones

function we_render_tours_list()
{
    // Procesar eliminación
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['post_id']) && isset($_GET['_wpnonce'])) {
        if (wp_verify_nonce($_GET['_wpnonce'], 'delete_tour_' . $_GET['post_id'])) {
            wp_delete_post((int) $_GET['post_id'], true);
            echo '<div class="notice notice-success"><p>Tour eliminado correctamente.</p></div>';
        }
    }

    // Búsqueda
    $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    $cat_filter = isset($_GET['cat']) ? sanitize_text_field($_GET['cat']) : '';

    $args = [
        'post_type' => 'tour',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    ];

    if ($search) {
        $args['s'] = $search;
    }

    if ($cat_filter) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'tour_category',
                'field' => 'slug',
                'terms' => $cat_filter,
            ]
        ];
    }

    $tours = get_posts($args);
    $categories = get_terms(['taxonomy' => 'tour_category', 'hide_empty' => false]);
    ?>
    <div class="wrap we-admin-wrap">
        <h1 class="wp-heading-inline">Todos los Tours</h1>
        <a href="<?php echo admin_url('admin.php?page=we-tour-add'); ?>" class="page-title-action">Añadir Nuevo</a>
        <hr class="wp-header-end">

        <!-- Filtros -->
        <form method="get" class="we-admin-filters">
            <input type="hidden" name="page" value="we-tours-admin">
            <input type="text" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Buscar tours..."
                class="we-admin-search">
            <select name="cat">
                <option value="">Todas las categorías</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo esc_attr($cat->slug); ?>" <?php selected($cat_filter, $cat->slug); ?>>
                        <?php echo esc_html($cat->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="button">Filtrar</button>
            <?php if ($search || $cat_filter): ?>
                <a href="<?php echo admin_url('admin.php?page=we-tours-admin'); ?>" class="button">Limpiar</a>
            <?php endif; ?>
        </form>

        <!-- Tabla -->
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width:60px">Imagen</th>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Duración</th>
                    <th>Estado</th>
                    <th style="width:120px">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tours)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center;padding:40px;color:#888;">
                            No hay tours. <a href="<?php echo admin_url('admin.php?page=we-tour-add'); ?>">Crea el primero</a>.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tours as $tour):
                        $pid = $tour->ID;
                        $price = get_post_meta($pid, 'tour_price', true);
                        $duration = get_post_meta($pid, 'tour_duration', true);
                        $cats = get_the_terms($pid, 'tour_category');
                        $cat_names = ($cats && !is_wp_error($cats)) ? implode(', ', wp_list_pluck($cats, 'name')) : '—';
                        $thumb = get_the_post_thumbnail_url($pid, 'thumbnail');
                        $status = $tour->post_status === 'publish' ? '<span class="we-status we-status-published">Publicado</span>' : '<span class="we-status we-status-draft">Borrador</span>';
                        ?>
                        <tr>
                            <td>
                                <?php if ($thumb): ?>
                                    <img src="<?php echo esc_url($thumb); ?>" alt=""
                                        style="width:50px;height:50px;object-fit:cover;border-radius:3px;">
                                <?php else: ?>
                                    <div
                                        style="width:50px;height:50px;background:#e8e8e8;border-radius:3px;display:flex;align-items:center;justify-content:center;color:#aaa;font-size:10px;">
                                        Sin img</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo esc_html($tour->post_title); ?></strong>
                                <div style="font-size:12px;color:#888;margin-top:2px;">
                                    <a href="<?php echo get_permalink($pid); ?>" target="_blank">Ver en sitio ↗</a>
                                </div>
                            </td>
                            <td><?php echo esc_html($cat_names); ?></td>
                            <td><?php echo esc_html($price ?: '—'); ?></td>
                            <td><?php echo esc_html($duration ?: '—'); ?></td>
                            <td><?php echo $status; ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=we-tour-edit&post_id=' . $pid); ?>"
                                    class="button button-small">Editar</a>
                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=we-tours-admin&action=delete&post_id=' . $pid), 'delete_tour_' . $pid); ?>"
                                    class="button button-small" style="color:#c62828;"
                                    onclick="return confirm('¿Eliminar este tour? Esta acción no se puede deshacer.');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
