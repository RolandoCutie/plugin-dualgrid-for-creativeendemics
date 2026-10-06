<?php

if (!defined('ABSPATH')) {
    exit;
}

function dg_render_admin_dashboard()
{
    $cards = [
        [
            'title' => 'Tours',
            'desc' => 'Crear, editar y organizar todos los tours del sitio.',
            'url' => admin_url('admin.php?page=we-tours-admin'),
            'icon' => 'dashicons-palmtree',
        ],
        [
            'title' => 'Tour Finder',
            'desc' => 'Ajustar links, estados y configuración pública del Finder.',
            'url' => admin_url('admin.php?page=we-tour-finder'),
            'icon' => 'dashicons-search',
        ],
        [
            'title' => 'Offers Promo',
            'desc' => 'Administrar el popup promocional y sus ofertas activas.',
            'url' => admin_url('edit.php?post_type=dg_offer'),
            'icon' => 'dashicons-tag',
        ],
        [
            'title' => 'Testimonials',
            'desc' => 'Revisar testimonios, aprobarlos y mantener su contenido.',
            'url' => admin_url('edit.php?post_type=testimonial'),
            'icon' => 'dashicons-format-quote',
        ],
        [
            'title' => 'Nuestro Equipo',
            'desc' => 'Editar la sección global de guías y miembros del equipo.',
            'url' => admin_url('admin.php?page=dualgrid-team'),
            'icon' => 'dashicons-groups',
        ],
        [
            'title' => 'Tour Categories',
            'desc' => 'Gestionar categorías de tours y sus textos base.',
            'url' => admin_url('edit-tags.php?taxonomy=tour_category&post_type=tour'),
            'icon' => 'dashicons-category',
        ],
    ];
    ?>
    <div class="wrap dg-admin-dashboard">
        <h1>DualGrid</h1>
        <p class="dg-admin-dashboard__lead">Todo el contenido administrable del plugin queda agrupado aquí en un solo lugar.
        </p>

        <div class="dg-admin-dashboard__grid">
            <?php foreach ($cards as $card): ?>
                <a class="dg-admin-card" href="<?php echo esc_url($card['url']); ?>">
                    <span class="dashicons <?php echo esc_attr($card['icon']); ?> dg-admin-card__icon"
                        aria-hidden="true"></span>
                    <h2><?php echo esc_html($card['title']); ?></h2>
                    <p><?php echo esc_html($card['desc']); ?></p>
                    <span class="dg-admin-card__cta">Abrir</span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <style>
        .dg-admin-dashboard__lead {
            max-width: 820px;
            color: #50575e;
            font-size: 15px;
            line-height: 1.6;
            margin: 10px 0 26px;
        }

        .dg-admin-dashboard__grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            max-width: 1200px;
        }

        .dg-admin-card {
            display: flex;
            flex-direction: column;
            gap: 12px;
            min-height: 210px;
            padding: 24px;
            border-radius: 16px;
            border: 1px solid #dcdcde;
            background: linear-gradient(180deg, #ffffff 0%, #f6f7f7 100%);
            text-decoration: none;
            color: #1d2327;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }

        .dg-admin-card:hover {
            transform: translateY(-2px);
            border-color: #2271b1;
            box-shadow: 0 14px 32px rgba(34, 113, 177, 0.12);
        }

        .dg-admin-card__icon {
            font-size: 28px;
            width: 28px;
            height: 28px;
            color: #2271b1;
        }

        .dg-admin-card h2 {
            margin: 0;
            font-size: 20px;
            line-height: 1.2;
        }

        .dg-admin-card p {
            margin: 0;
            color: #50575e;
            line-height: 1.6;
            flex: 1;
        }

        .dg-admin-card__cta {
            font-weight: 600;
            color: #2271b1;
        }
    </style>
    <?php
}
