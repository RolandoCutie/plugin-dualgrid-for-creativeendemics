<?php

if (!defined('ABSPATH')) {
    exit;
}

function dg_get_team_section_settings()
{
    $defaults = [
        'label' => 'The team',
        'heading' => 'Meet your',
        'italic_word' => 'guides',
        'members' => [
            [
                'name' => 'José Alberto',
                'role' => 'CEO and lead birding guide',
                'img' => '',
            ],
            [
                'name' => 'Nils Navarro',
                'role' => 'Birding guide',
                'img' => '',
            ],
            [
                'name' => 'Diana DTen',
                'role' => 'Photography guide',
                'img' => '',
            ],
        ],
    ];

    $stored = get_option('dg_team_section_settings', []);
    if (!is_array($stored)) {
        return $defaults;
    }

    $members = [];
    if (!empty($stored['members']) && is_array($stored['members'])) {
        foreach ($stored['members'] as $member) {
            if (!is_array($member)) {
                continue;
            }

            $name = trim((string) ($member['name'] ?? ''));
            $role = trim((string) ($member['role'] ?? ''));
            $img = trim((string) ($member['img'] ?? ''));

            if ($name === '' && $role === '' && $img === '') {
                continue;
            }

            $members[] = [
                'name' => $name,
                'role' => $role,
                'img' => $img,
            ];
        }
    }

    return [
        'label' => !empty($stored['label']) ? (string) $stored['label'] : $defaults['label'],
        'heading' => !empty($stored['heading']) ? (string) $stored['heading'] : $defaults['heading'],
        'italic_word' => !empty($stored['italic_word']) ? (string) $stored['italic_word'] : $defaults['italic_word'],
        'members' => !empty($members) ? $members : $defaults['members'],
    ];
}

function dg_render_team_admin_page()
{
    if (!current_user_can('edit_others_posts')) {
        wp_die(esc_html__('You do not have permission to access this page.', 'dualgrid'));
    }

    $settings = dg_get_team_section_settings();
    $saved_notice = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dg_save_team_section'])) {
        check_admin_referer('dg_save_team_section');

        $raw_members = isset($_POST['dg_team_members']) && is_array($_POST['dg_team_members'])
            ? wp_unslash($_POST['dg_team_members'])
            : [];
        $members = [];

        foreach ($raw_members as $member) {
            if (!is_array($member)) {
                continue;
            }

            $clean_member = [
                'name' => sanitize_text_field((string) ($member['name'] ?? '')),
                'role' => sanitize_text_field((string) ($member['role'] ?? '')),
                'img' => esc_url_raw((string) ($member['img'] ?? '')),
            ];

            if ($clean_member['name'] === '' && $clean_member['role'] === '' && $clean_member['img'] === '') {
                continue;
            }

            $members[] = $clean_member;
        }

        $settings = [
            'label' => sanitize_text_field((string) ($_POST['dg_team_label'] ?? '')),
            'heading' => sanitize_text_field((string) ($_POST['dg_team_heading'] ?? '')),
            'italic_word' => sanitize_text_field((string) ($_POST['dg_team_italic_word'] ?? '')),
            'members' => !empty($members) ? $members : $settings['members'],
        ];

        update_option('dg_team_section_settings', $settings, false);
        $saved_notice = true;
    }
    ?>
    <div class="wrap we-admin-wrap">
        <div class="we-admin-header">
            <h1>Nuestro Equipo</h1>
            <a class="button we-back-link" href="<?php echo esc_url(admin_url('admin.php?page=dualgrid-admin')); ?>">← Volver a DualGrid</a>
        </div>

        <?php if ($saved_notice): ?>
            <div class="notice notice-success"><p>La sección de equipo se guardó correctamente.</p></div>
        <?php endif; ?>

        <form method="post" class="we-admin-form">
            <?php wp_nonce_field('dg_save_team_section'); ?>

            <div class="we-sidebar-box" style="max-width:none; margin-bottom:24px;">
                <h3>Encabezado de la sección</h3>
                <div class="we-three-col">
                    <div class="we-form-group">
                        <label for="dg_team_label">Label</label>
                        <input type="text" id="dg_team_label" name="dg_team_label" value="<?php echo esc_attr($settings['label']); ?>">
                    </div>
                    <div class="we-form-group">
                        <label for="dg_team_heading">Título</label>
                        <input type="text" id="dg_team_heading" name="dg_team_heading" value="<?php echo esc_attr($settings['heading']); ?>">
                    </div>
                    <div class="we-form-group">
                        <label for="dg_team_italic_word">Palabra en cursiva</label>
                        <input type="text" id="dg_team_italic_word" name="dg_team_italic_word" value="<?php echo esc_attr($settings['italic_word']); ?>">
                    </div>
                </div>
            </div>

            <div class="we-sidebar-box" style="max-width:none;">
                <h3>Guías y miembros</h3>
                <p class="we-hint">Agrega, quita u ordena los miembros que quieras mostrar en la sección del equipo.</p>

                <div class="we-repeater" id="dg-team-repeater">
                    <div class="we-repeater-items">
                        <?php foreach ($settings['members'] as $index => $member): ?>
                            <div class="we-repeater-row" data-index="<?php echo esc_attr($index); ?>">
                                <div class="we-three-col">
                                    <div class="we-form-group">
                                        <label>Nombre</label>
                                        <input type="text" name="dg_team_members[<?php echo esc_attr($index); ?>][name]" value="<?php echo esc_attr($member['name'] ?? ''); ?>">
                                    </div>
                                    <div class="we-form-group">
                                        <label>Rol</label>
                                        <input type="text" name="dg_team_members[<?php echo esc_attr($index); ?>][role]" value="<?php echo esc_attr($member['role'] ?? ''); ?>">
                                    </div>
                                    <div class="we-form-group">
                                        <label>Foto</label>
                                        <input type="url" class="dg-team-image-input" name="dg_team_members[<?php echo esc_attr($index); ?>][img]" value="<?php echo esc_url($member['img'] ?? ''); ?>" placeholder="https://...">
                                    </div>
                                </div>
                                <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                                    <button type="button" class="button dg-team-upload-btn">Seleccionar imagen</button>
                                    <button type="button" class="button we-repeater-remove" onclick="this.closest('.we-repeater-row').remove()">Eliminar guía</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="button we-repeater-add" id="dg-team-add-member">+ Añadir guía</button>
                </div>
            </div>

            <p style="margin-top:24px;">
                <button type="submit" name="dg_save_team_section" class="button button-primary">Guardar sección de equipo</button>
            </p>
        </form>
    </div>

    <template id="dg-team-member-template">
        <div class="we-repeater-row">
            <div class="we-three-col">
                <div class="we-form-group">
                    <label>Nombre</label>
                    <input type="text" name="dg_team_members[__IDX__][name]" value="">
                </div>
                <div class="we-form-group">
                    <label>Rol</label>
                    <input type="text" name="dg_team_members[__IDX__][role]" value="">
                </div>
                <div class="we-form-group">
                    <label>Foto</label>
                    <input type="url" class="dg-team-image-input" name="dg_team_members[__IDX__][img]" value="" placeholder="https://...">
                </div>
            </div>
            <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                <button type="button" class="button dg-team-upload-btn">Seleccionar imagen</button>
                <button type="button" class="button we-repeater-remove" onclick="this.closest('.we-repeater-row').remove()">Eliminar guía</button>
            </div>
        </div>
    </template>

    <script>
        (function() {
            var addButton = document.getElementById('dg-team-add-member');
            var repeater = document.getElementById('dg-team-repeater');
            var template = document.getElementById('dg-team-member-template');

            if (addButton && repeater && template) {
                addButton.addEventListener('click', function() {
                    var wrapper = document.createElement('div');
                    var idx = repeater.querySelectorAll('.we-repeater-row').length;
                    wrapper.innerHTML = template.innerHTML.replace(/__IDX__/g, idx);
                    repeater.querySelector('.we-repeater-items').appendChild(wrapper.firstElementChild);
                });
            }

            document.addEventListener('click', function(event) {
                var button = event.target.closest('.dg-team-upload-btn');
                if (!button || !window.wp || !wp.media) {
                    return;
                }

                event.preventDefault();
                var input = button.closest('.we-repeater-row').querySelector('.dg-team-image-input');
                var frame = wp.media({
                    title: 'Seleccionar imagen',
                    button: { text: 'Usar esta imagen' },
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    if (input) {
                        input.value = attachment.url || '';
                    }
                });

                frame.open();
            });
        })();
    </script>
    <?php
}