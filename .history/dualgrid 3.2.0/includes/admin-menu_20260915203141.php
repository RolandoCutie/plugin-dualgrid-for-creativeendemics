<?php
// === includes/admin-menu.php ===
// Interfaz de administración custom para Tours

add_action('admin_menu', function () {
    add_menu_page(
        'DualGrid',
        'DualGrid',
        'edit_others_posts',  // ← Admin + Editor
        'dualgrid-admin',
        'dg_render_admin_dashboard',
        'dashicons-screenoptions',
        5
    );

    add_submenu_page(
        'dualgrid-admin',
        'DualGrid',
        'Dashboard',
        'edit_others_posts',
        'dualgrid-admin',
        'dg_render_admin_dashboard'
    );

    // Submenú: Lista
    add_submenu_page(
        'dualgrid-admin',
        'Todos los Tours',
        'Tours',
        'edit_others_posts',  // ← Admin + Editor
        'we-tours-admin',
        'we_render_tours_list'
    );

    // Submenú: Añadir Nuevo
    add_submenu_page(
        'dualgrid-admin',
        'Añadir Nuevo Tour',
        'Añadir Tour',
        'edit_others_posts',  // ← Admin + Editor
        'we-tour-add',
        'we_render_tour_form'
    );

    // Submenú: Editar (oculto del menú)
    add_submenu_page(
        null,
        'Editar Tour',
        'Editar Tour',
        'edit_others_posts',  // ← Admin + Editor
        'we-tour-edit',
        'we_render_tour_form'
    );

    // Submenú: Categorías (redirige a taxonomía nativa)
    add_submenu_page(
        'dualgrid-admin',
        'Categorías',
        'Categorías Tours',
        'edit_others_posts',  // ← Admin + Editor
        'edit-tags.php?taxonomy=tour_category&post_type=tour'
    );

    add_submenu_page(
        'dualgrid-admin',
        'Offers Promo',
        'Offers Promo',
        'edit_others_posts',
        'edit.php?post_type=dg_offer'
    );

    add_submenu_page(
        'dualgrid-admin',
        'Testimonials',
        'Testimonials',
        'edit_others_posts',
        'edit.php?post_type=testimonial'
    );

    add_submenu_page(
        'dualgrid-admin',
        'Generar Links Testimonials',
        'Links Testimonials',
        'manage_options',
        'edit.php?post_type=testimonial&page=tp-generar-links'
    );

    add_submenu_page(
        'dualgrid-admin',
        'Nuestro Equipo',
        'Nuestro Equipo',
        'edit_others_posts',
        'dualgrid-team',
        'dg_render_team_admin_page'
    );
});

// ──────────────────────────────────────────
// REDIRIGIR "Añadir Nuevo" Y "Editar" DEL CPT NATIVO A NUESTRA INTERFAZ
// ──────────────────────────────────────────
add_action('admin_init', function () {
    global $pagenow;
    if ($pagenow === 'post-new.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'tour') {
        wp_redirect(admin_url('admin.php?page=we-tour-add'));
        exit;
    }
    if ($pagenow === 'post.php' && isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] === 'edit') {
        $post = get_post((int) $_GET['post']);
        if ($post && $post->post_type === 'tour') {
            wp_redirect(admin_url('admin.php?page=we-tour-edit&post_id=' . $post->ID));
            exit;
        }
    }
});

// Ocultar el menú nativo del CPT
add_action('admin_menu', function () {
    remove_menu_page('edit.php?post_type=tour');
    remove_menu_page('edit.php?post_type=dg_offer');
    remove_menu_page('edit.php?post_type=testimonial');
}, 999);