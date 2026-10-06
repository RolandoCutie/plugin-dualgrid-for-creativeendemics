<?php
// === includes/admin-form.php ===
// Formulario completo para crear/editar tours (todos los campos del modal)

function we_render_tour_form()
{
    $request_post_id = 0;

    if (isset($_GET['post_id'])) {
        $request_post_id = (int) $_GET['post_id'];
    } elseif (isset($_POST['post_id'])) {
        $request_post_id = (int) $_POST['post_id'];
    }

    $is_edit = $request_post_id > 0;
    $post_id = $request_post_id;
    $tour = $is_edit ? get_post($post_id) : null;

    // ─────────────────────────────────────────────────────────────────────────────
    // Helper: obtener meta con default
    // ─────────────────────────────────────────────────────────────────────────────

    $we_get = function ($key, $default = '') use ($post_id) {
        $val = get_post_meta($post_id, $key, true);

        return ($val !== '' && $val !== false && $val !== null)
            ? $val
            : $default;
    };

    // ─────────────────────────────────────────────────────────────────────────────
    // Valores por defecto / actuales
    // ─────────────────────────────────────────────────────────────────────────────

    $defaults = [
        'title' => $tour ? $tour->post_title : '',
        'slug' => $tour ? (string) get_post_field('post_name', $post_id) : '',
        'excerpt' => $tour ? $tour->post_excerpt : '',
        'content' => $tour ? $tour->post_content : '',
        'status' => $tour ? $tour->post_status : 'publish',
        'featured_img' => $tour ? get_post_thumbnail_id($post_id) : 0,
    ];

    // Campos simples
    $simple = [
        'tour_short_description',
        'tour_coords',
        'tour_duration',
        'tour_max_people',
        'tour_price',
        'tour_price_secondary',
        'tour_price_secondary_label',
        'tour_difficulty',
        'tour_accommodation',
        'tour_dates',
        'tour_when_to_travel',
        'tour_next_date',
        'tour_type',
        'tour_language',
        'tour_city',
        'tour_pace',
        'tour_transport',
        'tour_map_custom_url',
        'tour_youtube_url',
        'tour_wildlife_label',
        'tour_organizer_name',
        'tour_organizer_role',
        'tour_organizer_logo',
        'tour_can_americans_join',
        'tour_terms_conditions',
        'tour_physical_demands',
        '_we_pdf_url',
        '_we_subtitle',
    ];

    foreach ($simple as $key) {
        $defaults[$key] = $we_get($key);
    }

    if ($defaults['tour_terms_conditions'] === '') {
        $defaults['tour_terms_conditions'] = $we_get('tour_policies');
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Repeaters simples
    // ─────────────────────────────────────────────────────────────────────────────

    $defaults['tour_gallery'] = $we_get('tour_gallery', []);

    if (!is_array($defaults['tour_gallery'])) {
        $defaults['tour_gallery'] = [];
    }

    $defaults['tour_about'] = $we_get('tour_about', []);

    if (!is_array($defaults['tour_about'])) {
        $defaults['tour_about'] = [''];
    }

    $defaults['tour_includes'] = $we_get('tour_includes', []);

    if (!is_array($defaults['tour_includes'])) {
        $defaults['tour_includes'] = [''];
    }

    $defaults['tour_excludes'] = $we_get('tour_excludes', []);

    if (!is_array($defaults['tour_excludes'])) {
        $defaults['tour_excludes'] = [''];
    }

    $defaults['tour_pricing'] = $we_get('tour_pricing', []);

    if (!is_array($defaults['tour_pricing'])) {
        $defaults['tour_pricing'] = [];
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Repeaters complejos
    // ─────────────────────────────────────────────────────────────────────────────

    $defaults['tour_wildlife'] = $we_get('tour_wildlife', []);

    if (!is_array($defaults['tour_wildlife'])) {
        $defaults['tour_wildlife'] = [];
    }

    $defaults['tour_guides'] = $we_get('tour_guides', []);

    if (!is_array($defaults['tour_guides'])) {
        $defaults['tour_guides'] = [];
    }

    $defaults['tour_itinerary'] = $we_get('tour_itinerary', []);

    if (!is_array($defaults['tour_itinerary'])) {
        $defaults['tour_itinerary'] = [];
    }

    $defaults['tour_availability'] = $we_get('tour_availability', []);

    if (!is_array($defaults['tour_availability'])) {
        $defaults['tour_availability'] = [];
    }

    $defaults['tour_faq'] = $we_get('tour_faq', []);

    if (!is_array($defaults['tour_faq'])) {
        $defaults['tour_faq'] = [];
    }

    $stored_main_focus = $we_get('tour_main_focus', []);
    $defaults['tour_main_focus'] = function_exists('dg_normalize_tour_main_focus') && is_array($stored_main_focus)
        ? dg_normalize_tour_main_focus($stored_main_focus, [])
        : [];

    $stored_regions = $we_get('tour_regions', []);
    $defaults['tour_regions'] = function_exists('dg_normalize_tour_regions') && is_array($stored_regions)
        ? dg_normalize_tour_regions($stored_regions, [])
        : [];

    $defaults['tour_duration_band'] = function_exists('dg_normalize_tour_duration_band')
        ? dg_normalize_tour_duration_band($we_get('tour_duration_band', 0), '')
        : 0;

    $stored_status = $we_get('tour_status', '');
    $defaults['tour_status'] = is_scalar($stored_status)
        ? sanitize_text_field((string) $stored_status)
        : '';

    // ─────────────────────────────────────────────────────────────────────────────
    // Categorías
    // ─────────────────────────────────────────────────────────────────────────────

    $selected_cats = $tour
        ? wp_get_post_terms($post_id, 'tour_category', ['fields' => 'ids'])
        : [];

    $all_cats = get_terms([
        'taxonomy' => 'tour_category',
        'hide_empty' => false,
    ]);

    $tour_finder_main_focus_options = function_exists('dg_get_tour_finder_main_focus_options')
        ? dg_get_tour_finder_main_focus_options()
        : [];

    $tour_finder_region_options = function_exists('dg_get_tour_finder_region_options')
        ? dg_get_tour_finder_region_options()
        : [];

    $tour_finder_duration_options = function_exists('dg_get_tour_finder_duration_options')
        ? dg_get_tour_finder_duration_options()
        : [];

    $tour_finder_status_options = function_exists('dg_get_tour_finder_status_options')
        ? dg_get_tour_finder_status_options()
        : [];

    $docs_dir = dirname(__DIR__) . '/docs';

    $read_doc_by_hints = function (array $hints) use ($docs_dir) {
        if (!is_dir($docs_dir)) {
            return '';
        }

        $files = glob($docs_dir . '/*.md');
        if (empty($files)) {
            return '';
        }

        foreach ($files as $file_path) {
            $base_name = basename($file_path);
            $matches_all = true;

            foreach ($hints as $hint) {
                if ($hint === '') {
                    continue;
                }

                if (stripos($base_name, $hint) === false) {
                    $matches_all = false;
                    break;
                }
            }

            if (!$matches_all) {
                continue;
            }

            $content = file_get_contents($file_path);
            return is_string($content) ? trim($content) : '';
        }

        return '';
    };

    $parse_includes_doc = function ($text) {
        $result = [
            'includes' => [],
            'excludes' => [],
        ];

        if (!is_string($text) || trim($text) === '') {
            return $result;
        }

        $lines = preg_split('/\R/u', $text) ?: [];
        $mode = '';

        foreach ($lines as $line) {
            $line = trim((string) $line);

            if ($line === '') {
                continue;
            }

            if (preg_match('/what.?s\s+included/i', $line)) {
                $mode = 'includes';
                continue;
            }

            if (preg_match('/what.?s\s+not\s+included/i', $line)) {
                $mode = 'excludes';
                continue;
            }

            if (preg_match('/physical\s+demands|accommodations|if\s+you.?re\s+american/i', $line)) {
                $mode = '';
            }

            if (!preg_match('/^[-*]\s+(.+)$/', $line, $matches)) {
                continue;
            }

            if ($mode !== 'includes' && $mode !== 'excludes') {
                continue;
            }

            $item = trim($matches[1]);
            if ($item !== '') {
                $result[$mode][] = $item;
            }
        }

        return $result;
    };

    $parse_faq_doc = function ($text) {
        $rows = [];

        if (!is_string($text) || trim($text) === '') {
            return $rows;
        }

        $lines = preg_split('/\R/u', $text) ?: [];

        foreach ($lines as $line) {
            $line = trim((string) $line);

            if ($line === '' || strpos($line, '?') === false) {
                continue;
            }

            $question_pos = strpos($line, '?');
            if ($question_pos === false) {
                continue;
            }

            $question = trim(substr($line, 0, $question_pos + 1));
            $answer = trim(substr($line, $question_pos + 1));

            if ($question === '' || $answer === '') {
                continue;
            }

            $rows[] = [
                'question' => $question,
                'answer' => $answer,
            ];
        }

        return $rows;
    };

    $extract_doc_section = function ($text, $start_pattern, array $end_patterns = []) {
        if (!is_string($text) || trim($text) === '') {
            return '';
        }

        $lines = preg_split('/\R/u', $text) ?: [];
        $collecting = false;
        $collected = [];

        foreach ($lines as $line) {
            $raw_line = rtrim((string) $line);
            $trimmed = trim($raw_line);

            if (!$collecting) {
                if ($trimmed !== '' && preg_match($start_pattern, $trimmed)) {
                    $collecting = true;
                }
                continue;
            }

            if (!empty($end_patterns)) {
                foreach ($end_patterns as $end_pattern) {
                    if ($trimmed !== '' && preg_match($end_pattern, $trimmed)) {
                        $collecting = false;
                        break 2;
                    }
                }
            }

            $collected[] = $raw_line;
        }

        return trim(implode("\n", $collected));
    };

    $default_when_to_travel_doc = $read_doc_by_hints(['When', 'Travel']);
    $default_rules_doc = $read_doc_by_hints(['Rules', 'Terms']);
    $default_includes_doc = $read_doc_by_hints(['What', 'Included']);
    $default_faq_doc = $read_doc_by_hints(['Frequently', 'Asked', 'Questions']);
    $default_include_sets = function_exists('dg_get_default_tour_include_sets')
        ? dg_get_default_tour_include_sets()
        : $parse_includes_doc($default_includes_doc);
    $default_faq_rows = $parse_faq_doc($default_faq_doc);
    $default_physical_doc = $extract_doc_section(
        $default_includes_doc,
        '/physical\s+demands/i',
        ['/accommodations/i', '/if\s+you.?re\s+american/i']
    );
    $default_americans_doc = $extract_doc_section(
        $default_includes_doc,
        '/if\s+you.?re\s+american/i'
    );

    $tour_finder_status_value = is_string($defaults['tour_status']) ? $defaults['tour_status'] : '';
    if (!isset($tour_finder_status_options[$tour_finder_status_value])) {
        $tour_finder_status_value = '';
    }
    $defaults['tour_status'] = $tour_finder_status_value;

    $normalize_repeater_values = function ($values, array $fallback = []) {
        if (!is_array($values)) {
            $values = [];
        }

        $clean = array_values(array_filter(array_map(function ($value) {
            return trim((string) $value);
        }, $values), function ($value) {
            return $value !== '';
        }));

        return $clean !== [] ? $clean : $fallback;
    };

    $defaults['tour_includes'] = $normalize_repeater_values(
        $defaults['tour_includes'],
        $default_include_sets['includes']
    );

    $defaults['tour_excludes'] = $normalize_repeater_values(
        $defaults['tour_excludes'],
        $default_include_sets['excludes']
    );

    if ($defaults['tour_when_to_travel'] === '' && $default_when_to_travel_doc !== '') {
        $defaults['tour_when_to_travel'] = $default_when_to_travel_doc;
    }

    if ($defaults['tour_terms_conditions'] === '' && $default_rules_doc !== '') {
        $defaults['tour_terms_conditions'] = $default_rules_doc;
    }

    if ($defaults['tour_physical_demands'] === '' && $default_physical_doc !== '') {
        $defaults['tour_physical_demands'] = $default_physical_doc;
    }

    if ($defaults['tour_can_americans_join'] === '' && $default_americans_doc !== '') {
        $defaults['tour_can_americans_join'] = $default_americans_doc;
    }

    if (empty($defaults['tour_faq']) && !empty($default_faq_rows)) {
        $defaults['tour_faq'] = $default_faq_rows;
    }

    // ═════════════════════════════════════════════════════════════════════════════
    // PROCESAR GUARDADO
    // ═════════════════════════════════════════════════════════════════════════════

    $message = '';

    if (isset($_GET['updated']) && $_GET['updated'] === '1') {
        $message = '
            <div class="notice notice-success">
                <p>
                    Tour guardado correctamente. Puedes seguir editandolo aqui o volver a la lista.
                    <a href="' . admin_url('admin.php?page=we-tours-admin') . '">
                        ← Volver a la lista
                    </a>
                </p>
            </div>
        ';
    }

    if (
        $_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['we_save_tour'])
    ) {
        check_admin_referer('we_save_tour_nonce');

        $posted_main_focus = function_exists('dg_normalize_tour_main_focus')
            ? dg_normalize_tour_main_focus((array) ($_POST['tour_main_focus'] ?? []), [])
            : [];

        $posted_regions = function_exists('dg_normalize_tour_regions')
            ? dg_normalize_tour_regions((array) ($_POST['tour_regions'] ?? []), [])
            : [];

        $posted_duration_band = function_exists('dg_normalize_tour_duration_band')
            ? dg_normalize_tour_duration_band($_POST['tour_duration_band'] ?? 0, $_POST['tour_duration'] ?? '')
            : 0;

        $posted_status = function_exists('dg_normalize_tour_status')
            ? dg_normalize_tour_status($_POST['tour_status'] ?? '', $_POST['tour_availability'] ?? [])
            : '';

        $validation_errors = [];

        if (!empty($validation_errors)) {
            $message = '<div class="notice notice-error"><p>' . implode('<br>', array_map('esc_html', $validation_errors)) . '</p></div>';
            $defaults['title'] = sanitize_text_field($_POST['we_title'] ?? $defaults['title']);
            $defaults['slug'] = sanitize_title($_POST['we_slug'] ?? $defaults['slug']);
            $defaults['excerpt'] = sanitize_textarea_field($_POST['we_excerpt'] ?? $defaults['excerpt']);
            $defaults['tour_youtube_url'] = esc_url_raw($_POST['tour_youtube_url'] ?? $defaults['tour_youtube_url']);
            $defaults['tour_when_to_travel'] = wp_kses_post($_POST['tour_when_to_travel'] ?? $defaults['tour_when_to_travel']);
            $defaults['tour_terms_conditions'] = wp_kses_post($_POST['tour_terms_conditions'] ?? $defaults['tour_terms_conditions']);
            $defaults['tour_physical_demands'] = wp_kses_post($_POST['tour_physical_demands'] ?? $defaults['tour_physical_demands']);
            $defaults['tour_can_americans_join'] = wp_kses_post($_POST['tour_can_americans_join'] ?? $defaults['tour_can_americans_join']);
            $defaults['tour_pricing'] = isset($_POST['tour_pricing']) && is_array($_POST['tour_pricing'])
                ? array_values($_POST['tour_pricing'])
                : $defaults['tour_pricing'];
            $defaults['tour_main_focus'] = $posted_main_focus;
            $defaults['tour_regions'] = $posted_regions;
            $defaults['tour_duration_band'] = $posted_duration_band;
            $defaults['tour_status'] = $posted_status;
            $selected_cats = !empty($_POST['we_categories']) ? array_map('intval', (array) $_POST['we_categories']) : [];
        } else {

            $post_data = [
                'post_title' => sanitize_text_field($_POST['we_title']),
                'post_excerpt' => sanitize_textarea_field($_POST['we_excerpt']),
                'post_content' => wp_kses_post($_POST['we_content']),
                'post_status' => sanitize_text_field($_POST['we_status']),
                'post_type' => 'tour',
            ];

            $submitted_slug = sanitize_title($_POST['we_slug'] ?? '');
            if ($submitted_slug !== '') {
                $post_data['post_name'] = $submitted_slug;
            }

            if ($is_edit) {
                $post_data['ID'] = $post_id;
                $new_id = wp_update_post($post_data, true);
            } else {
                $new_id = wp_insert_post($post_data, true);
            }

            if (is_wp_error($new_id)) {
                $message = '
                <div class="notice notice-error">
                    <p>
                        Error:
                        ' . esc_html($new_id->get_error_message()) . '
                    </p>
                </div>
            ';
            } else {
                $post_id = $new_id;

                // ─────────────────────────────────────────────────────────────────────
                // Campos simples de texto
                // ─────────────────────────────────────────────────────────────────────

                $text_fields = [
                    'tour_coords',
                    'tour_duration',
                    'tour_price',
                    'tour_price_secondary',
                    'tour_price_secondary_label',
                    'tour_difficulty',
                    'tour_accommodation',
                    'tour_dates',
                    'tour_next_date',
                    'tour_type',
                    'tour_language',
                    'tour_city',
                    'tour_transport',
                    'tour_wildlife_label',
                    'tour_organizer_name',
                    'tour_organizer_role',
                    '_we_subtitle',
                    '_we_pdf_url',
                ];

                foreach ($text_fields as $field) {
                    if (isset($_POST[$field])) {
                        update_post_meta(
                            $post_id,
                            $field,
                            sanitize_text_field($_POST[$field])
                        );
                    }
                }

                // ─────────────────────────────────────────────────────────────────────
                // Campos textarea
                // ─────────────────────────────────────────────────────────────────────

                $ta_fields = [
                    'tour_short_description',
                ];

                foreach ($ta_fields as $field) {
                    if (isset($_POST[$field])) {
                        update_post_meta(
                            $post_id,
                            $field,
                            sanitize_textarea_field($_POST[$field])
                        );
                    }
                }

                $rich_text_fields = [
                    'tour_when_to_travel',
                    'tour_terms_conditions',
                    'tour_physical_demands',
                    'tour_can_americans_join',
                ];

                foreach ($rich_text_fields as $field) {
                    if (isset($_POST[$field])) {
                        update_post_meta(
                            $post_id,
                            $field,
                            wp_kses_post($_POST[$field])
                        );
                    }
                }

                delete_post_meta($post_id, 'tour_policies');

                // ─────────────────────────────────────────────────────────────────────
                // Numéricos
                // ─────────────────────────────────────────────────────────────────────

                if (isset($_POST['tour_max_people'])) {
                    update_post_meta(
                        $post_id,
                        'tour_max_people',
                        intval($_POST['tour_max_people'])
                    );
                }

                // ─────────────────────────────────────────────────────────────────────
                // URL
                // ─────────────────────────────────────────────────────────────────────

                if (isset($_POST['tour_organizer_logo'])) {
                    update_post_meta(
                        $post_id,
                        'tour_organizer_logo',
                        esc_url_raw($_POST['tour_organizer_logo'])
                    );
                }

                if (isset($_POST['tour_map_custom_url'])) {
                    update_post_meta(
                        $post_id,
                        'tour_map_custom_url',
                        esc_url_raw($_POST['tour_map_custom_url'])
                    );
                }

                if (isset($_POST['tour_youtube_url'])) {
                    update_post_meta(
                        $post_id,
                        'tour_youtube_url',
                        esc_url_raw($_POST['tour_youtube_url'])
                    );
                }

                // ─────────────────────────────────────────────────────────────────────
                // Ritmo / transporte
                // ─────────────────────────────────────────────────────────────────────

                if (isset($_POST['tour_pace'])) {
                    update_post_meta(
                        $post_id,
                        'tour_pace',
                        sanitize_text_field($_POST['tour_pace'])
                    );
                }

                // ─────────────────────────────────────────────────────────────────────
                // Imagen destacada
                // ─────────────────────────────────────────────────────────────────────

                if (!empty($_POST['we_featured_img'])) {
                    set_post_thumbnail(
                        $post_id,
                        (int) $_POST['we_featured_img']
                    );
                } else {
                    delete_post_thumbnail($post_id);
                }

                // ─────────────────────────────────────────────────────────────────────
                // Categorías
                // ─────────────────────────────────────────────────────────────────────

                if (!empty($_POST['we_categories'])) {
                    $cat_ids = array_map(
                        'intval',
                        (array) $_POST['we_categories']
                    );

                    wp_set_post_terms(
                        $post_id,
                        $cat_ids,
                        'tour_category'
                    );
                } else {
                    wp_set_post_terms(
                        $post_id,
                        [],
                        'tour_category'
                    );
                }

                update_post_meta($post_id, 'tour_main_focus', $posted_main_focus);
                update_post_meta($post_id, 'tour_regions', $posted_regions);
                update_post_meta($post_id, 'tour_duration_band', $posted_duration_band);
                update_post_meta($post_id, 'tour_status', $posted_status);

                // ─────────────────────────────────────────────────────────────────────
                // Repeaters simples
                // ─────────────────────────────────────────────────────────────────────

                $simple_repeaters = [
                    'tour_gallery' => 'esc_url_raw',
                    'tour_about' => 'sanitize_textarea_field',
                    'tour_includes' => 'sanitize_text_field',
                    'tour_excludes' => 'sanitize_text_field',
                ];

                foreach ($simple_repeaters as $key => $sanitize_fn) {
                    $raw_values = isset($_POST[$key]) && is_array($_POST[$key])
                        ? $_POST[$key]
                        : [];

                    if ($key === 'tour_includes' && !$is_edit && empty($raw_values) && !empty($default_include_sets['includes'])) {
                        $raw_values = $default_include_sets['includes'];
                    }

                    if ($key === 'tour_excludes' && !$is_edit && empty($raw_values) && !empty($default_include_sets['excludes'])) {
                        $raw_values = $default_include_sets['excludes'];
                    }

                    if (!empty($raw_values)) {
                        $clean = array_map(
                            $sanitize_fn,
                            array_values(
                                array_filter(
                                    $raw_values,
                                    function ($value) {
                                        return $value !== '';
                                    }
                                )
                            )
                        );

                        update_post_meta(
                            $post_id,
                            $key,
                            $clean
                        );
                    } else {
                        delete_post_meta($post_id, $key);
                    }
                }

                // ─────────────────────────────────────────────────────────────────────
                // Repeaters complejos
                // ─────────────────────────────────────────────────────────────────────

                $complex = [
                    'tour_wildlife',
                    'tour_guides',
                    'tour_itinerary',
                    'tour_availability',
                    'tour_pricing',
                    'tour_faq',
                ];

                foreach ($complex as $key) {
                    if (
                        isset($_POST[$key]) &&
                        is_array($_POST[$key])
                    ) {
                        $clean = [];

                        foreach (array_values($_POST[$key]) as $item) {
                            if (!is_array($item)) {
                                continue;
                            }

                            $row = [];

                            foreach ($item as $k => $v) {
                                if (
                                    $k === 'image' ||
                                    $k === 'photo' ||
                                    $k === 'logo' ||
                                    $k === 'google_maps_url'
                                ) {
                                    $row[$k] = esc_url_raw($v);
                                } elseif ($k === 'is_target') {
                                    $row[$k] = ($v === '1') ? '1' : '';
                                } elseif ($k === 'answer') {
                                    $row[$k] = wp_kses_post($v);
                                } elseif ($key === 'tour_itinerary' && $k === 'description') {
                                    $row[$k] = wp_kses_post($v);
                                } elseif (
                                    $k === 'description' ||
                                    $k === 'note'
                                ) {
                                    $row[$k] = sanitize_textarea_field($v);
                                } else {
                                    $row[$k] = sanitize_text_field($v);
                                }
                            }

                            $clean[] = $row;
                        }

                        update_post_meta(
                            $post_id,
                            $key,
                            $clean
                        );
                    } else {
                        delete_post_meta($post_id, $key);
                    }
                }

                $redirect_url = add_query_arg([
                    'page' => 'we-tour-edit',
                    'post_id' => $post_id,
                    'updated' => 1,
                ], admin_url('admin.php'));

                if (!headers_sent()) {
                    wp_safe_redirect($redirect_url);
                    exit;
                }

                echo '<div class="wrap"><div class="notice notice-success"><p>Tour guardado correctamente. Redirigiendo al editor...</p><p><a class="button button-primary" href="' . esc_url($redirect_url) . '">Continuar</a> <a class="button" href="' . esc_url(admin_url('admin.php?page=we-tours-admin')) . '">Volver a tours</a></p></div></div>';
                echo '<script>window.location.replace(' . wp_json_encode($redirect_url) . ');</script>';
                exit;
            }
        }
    }

    $selected_focus_priorities = [];
    foreach ($defaults['tour_main_focus'] as $focus_item) {
        if (!is_array($focus_item) || empty($focus_item['categoryId'])) {
            continue;
        }
        $selected_focus_priorities[(int) $focus_item['categoryId']] = (int) ($focus_item['priority'] ?? 1);
    }

    $selected_regions_lookup = [];
    foreach ($defaults['tour_regions'] as $region_id) {
        $selected_regions_lookup[(int) $region_id] = true;
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Título y thumbnail
    // ─────────────────────────────────────────────────────────────────────────────

    $page_title = $is_edit
        ? 'Editar Tour'
        : 'Añadir Nuevo Tour';

    $official_catalog_seed = function_exists('dg_get_tour_finder_official_catalog_seed')
        ? dg_get_tour_finder_official_catalog_seed()
        : [];

    $current_tour_slug = $is_edit ? (string) get_post_field('post_name', $post_id) : '';
    $official_catalog_slugs = array_column($official_catalog_seed, 'slug');
    $current_tour_matches_catalog = $current_tour_slug !== '' && in_array($current_tour_slug, $official_catalog_slugs, true);

    $thumb_url = $defaults['featured_img']
        ? wp_get_attachment_image_url(
            $defaults['featured_img'],
            'medium'
        )
        : '';
    ?>

    <style>
        .we-admin-wrap {
            max-width: 1200px;
        }

        .we-admin-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .we-admin-header h1 {
            margin: 0;
        }

        .we-back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .we-admin-tabs {
            display: flex;
            border-bottom: 2px solid #c3c4c7;
            margin: 20px 0 0;
            background: #fff;
        }

        .we-admin-tab {
            padding: 12px 20px;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 14px;
            font-weight: 500;
            color: #646970;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: all 0.2s;
        }

        .we-admin-tab:hover {
            color: #2271b1;
        }

        .we-admin-tab.active {
            color: #2271b1;
            border-bottom-color: #2271b1;
            background: #f0f6fc;
        }

        .we-tab-panel {
            display: none;
            padding: 20px;
            background: #fff;
            border: 1px solid #c3c4c7;
            border-top: none;
        }

        .we-tab-panel.active {
            display: block;
        }

        .we-form-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 25px;
        }

        .we-form-main {
            min-width: 0;
        }

        .we-form-sidebar {
            position: sticky;
            top: 32px;
            height: fit-content;
        }

        .we-form-group {
            margin-bottom: 18px;
        }

        .we-form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 13px;
        }

        .we-form-group label small {
            font-weight: 400;
            color: #888;
        }

        .we-form-group input[type="text"],
        .we-form-group input[type="url"],
        .we-form-group input[type="number"],
        .we-form-group textarea,
        .we-form-group select {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #8c8f94;
            border-radius: 4px;
            font-size: 14px;
        }

        .we-form-group textarea {
            resize: vertical;
        }

        .we-input-large {
            font-size: 18px !important;
            padding: 8px 12px !important;
        }

        .we-sidebar-box {
            background: #fff;
            border: 1px solid #c3c4c7;
            padding: 16px;
            margin-bottom: 16px;
        }

        .we-sidebar-box h3 {
            margin: 0 0 12px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .we-cat-checkboxes {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .we-cat-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            cursor: pointer;
        }

        .we-image-uploader {
            border: 2px dashed #c3c4c7;
            padding: 20px;
            text-align: center;
            border-radius: 4px;
            background: #f6f7f7;
        }

        .we-image-preview {
            position: relative;
            display: inline-block;
        }

        .we-image-preview img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 4px;
            display: block;
        }

        .we-remove-img {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #d63638;
            color: #fff;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
        }

        .we-upload-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* Repeaters */

        .we-repeater {
            margin-bottom: 16px;
        }

        .we-repeater-items {
            margin-bottom: 12px;
        }

        .we-repeater-row {
            background: #f6f7f7;
            border: 1px solid #dcdcde;
            padding: 16px;
            border-radius: 4px;
            margin-bottom: 12px;
            position: relative;
        }

        .we-repeater-row .we-form-group {
            margin-bottom: 12px;
        }

        .we-repeater-row .we-form-group:last-child {
            margin-bottom: 0;
        }

        .we-repeater-remove {
            color: #d63638 !important;
            border-color: #d63638 !important;
        }

        .we-repeater-add {
            background: #2271b1 !important;
            color: #fff !important;
            border-color: #2271b1 !important;
        }

        .we-repeater-add:hover {
            background: #135e96 !important;
        }

        .we-two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .we-three-col {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }

        .we-section-title {
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid #dcdcde;
            color: #1d2327;
        }

        .we-hint {
            font-size: 12px;
            color: #888;
            margin-top: 4px;
            font-style: italic;
        }

        .we-tab-intro {
            margin: 0 0 18px;
            padding: 14px 16px;
            background: #f0f6fc;
            border: 1px solid #cfe1f5;
            border-radius: 6px;
            color: #1d2327;
            line-height: 1.6;
        }

        .we-tab-intro strong {
            color: #0a4b78;
        }

        .we-slug-guide {
            margin-top: 10px;
            padding: 12px 14px;
            background: #fff8e5;
            border: 1px solid #f1d58a;
            border-radius: 6px;
        }

        .we-slug-guide summary {
            cursor: pointer;
            font-weight: 600;
            color: #7a4b00;
        }

        .we-slug-guide ul {
            margin: 10px 0 0 18px;
        }

        .we-slug-guide li {
            margin: 4px 0;
            font-size: 12px;
            color: #4b4f58;
            line-height: 1.5;
        }

        .we-wildlife-row,
        .we-guide-row,
        .we-itinerary-row,
        .we-location-row,
        .we-avail-row,
        .we-faq-row {
            border-left: 3px solid #2271b1;
        }

        .we-include-row {
            border-left: 3px solid #00a32a;
        }

        .we-exclude-row {
            border-left: 3px solid #d63638;
        }

        .we-focus-grid,
        .we-radio-stack {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .we-focus-item {
            border: 1px solid #dcdcde;
            border-radius: 6px;
            padding: 10px 12px;
            background: #fff;
        }

        .we-priority-row {
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .we-priority-row span {
            font-size: 12px;
            color: #50575e;
        }

        .we-inline-radio {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border: 1px solid #dcdcde;
            border-radius: 6px;
            background: #fff;
            cursor: pointer;
        }
    </style>

    <div class="wrap we-admin-wrap">

        <div class="we-admin-header">
            <h1>
                <?php echo esc_html($page_title); ?>
            </h1>

            <a class="button we-back-link" href="<?php echo esc_url(admin_url('admin.php?page=we-tours-admin')); ?>">
                ← Volver a todos los tours
            </a>
        </div>

        <?php echo $message; ?>

        <form method="post" class="we-admin-form">

            <?php wp_nonce_field('we_save_tour_nonce'); ?>
            <input type="hidden" name="post_id" value="<?php echo esc_attr($post_id); ?>">

            <div class="we-tab-intro">
                <strong>Guía rápida para pasar un programa del documento al sitio:</strong><br>
                Título, extracto y datos clave alimentan cards y cabeceras. El bloque <strong>Overview</strong> del
                documento va en <strong>Acerca de</strong>. El bloque <strong>Highlights</strong> suele repartirse entre
                <strong>Fauna</strong>, <strong>Incluido/No incluido</strong> y <strong>Descripción corta</strong>. La tabla
                o resumen de <strong>precios</strong> va en <strong>Información básica</strong> y
                <strong>Disponibilidad</strong>. El <strong>itinerario día por día</strong> va en la pestaña Itinerario.
                <strong>Rules, FAQ y When to travel</strong> terminan en el cierre del modal.
            </div>

            <!-- ═══════════════════════════════════════════════════════════════════════
             PESTAÑAS
        ═══════════════════════════════════════════════════════════════════════ -->

            <div class="we-admin-tabs">

                <button type="button" class="we-admin-tab" data-tab="basic">
                    Información Básica
                </button>

                <button type="button" class="we-admin-tab" data-tab="gallery">
                    Galería
                </button>

                <button type="button" class="we-admin-tab" data-tab="about">
                    Acerca de
                </button>

                <button type="button" class="we-admin-tab" data-tab="wildlife">
                    Fauna
                </button>

                <button type="button" class="we-admin-tab" data-tab="organizer">
                    Organizador
                </button>

                <button type="button" class="we-admin-tab" data-tab="guides">
                    Guías
                </button>

                <button type="button" class="we-admin-tab" data-tab="itinerary">
                    Itinerario
                </button>

                <button type="button" class="we-admin-tab" data-tab="availability">
                    Disponibilidad
                </button>

                <button type="button" class="we-admin-tab" data-tab="includes">
                    Incluido/No
                </button>

                <button type="button" class="we-admin-tab" data-tab="policies">
                    Políticas/FAQ
                </button>

            </div>

            <!-- ═══════════════════════════════════════════════════════════════════════
             PANEL: INFORMACIÓN BÁSICA
        ═══════════════════════════════════════════════════════════════════════ -->

            <div class="we-tab-panel" id="tab-basic">

                <div class="we-form-layout">

                    <div class="we-form-main">

                        <!-- Título -->
                        <div class="we-form-group">
                            <label for="we_title">
                                Título del Tour *
                                <small>
                                    aparece en cards, modal y página individual
                                </small>
                            </label>

                            <input type="text" id="we_title" name="we_title"
                                value="<?php echo esc_attr($defaults['title']); ?>" required class="we-input-large"
                                placeholder="Ej: Cloud Forest Ridge Trek — Los Quetzales">
                        </div>

                        <div class="we-form-group">
                            <label for="we_slug">
                                Slug del tour
                                <small>
                                    clave para conectar este tour con el Tour Finder
                                </small>
                            </label>

                            <input type="text" id="we_slug" name="we_slug"
                                value="<?php echo esc_attr($defaults['slug']); ?>" list="we-official-tour-slugs"
                                placeholder="Ej: 7-day-cuba-birding-essentials">

                            <datalist id="we-official-tour-slugs">
                                <?php foreach ($official_catalog_seed as $catalog_tour): ?>
                                    <option value="<?php echo esc_attr($catalog_tour['slug']); ?>">
                                        <?php echo esc_html($catalog_tour['title']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </datalist>

                            <p class="we-hint">
                                Si este slug coincide exactamente con uno del catálogo oficial del wizard, el Tour Finder
                                usará la foto, el enlace y los datos reales de este tour creado desde el admin.
                            </p>

                            <details class="we-slug-guide">
                                <summary>Ver slugs oficiales del Tour Finder</summary>
                                <ul>
                                    <?php foreach ($official_catalog_seed as $catalog_tour): ?>
                                        <li>
                                            <strong><?php echo esc_html($catalog_tour['slug']); ?></strong>
                                            <?php if (!empty($catalog_tour['title'])): ?>
                                                — <?php echo esc_html($catalog_tour['title']); ?>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </details>
                        </div>

                        <!-- Descripción corta -->
                        <div class="we-form-group">
                            <label for="tour_short_description">
                                Descripción corta
                                <small>
                                    1-2 frases que aparecen en el header oscuro
                                </small>
                            </label>

                            <textarea id="tour_short_description" name="tour_short_description" rows="3"
                                placeholder="Breve descripción para el header del modal..."><?php echo esc_textarea($defaults['tour_short_description']); ?></textarea>
                        </div>

                        <!-- Extracto -->
                        <div class="we-form-group">
                            <label for="we_excerpt">
                                Extracto
                                <small>
                                    (aparece en la tarjeta del tour)
                                </small>
                            </label>

                            <textarea id="we_excerpt" name="we_excerpt" rows="3"
                                placeholder="Breve descripción que aparece en la lista de tours..."><?php echo esc_textarea($defaults['excerpt']); ?></textarea>
                        </div>

                        <!-- Contenido -->
                        <div class="we-form-group">
                            <label for="we_content">
                                Contenido / Itinerario completo
                                <small>
                                    se muestra en la página individual del tour, no en el modal
                                </small>
                            </label>

                            <?php
                            wp_editor(
                                $defaults['content'],
                                'we_content',
                                [
                                    'textarea_name' => 'we_content',
                                    'textarea_rows' => 12,
                                    'teeny' => true,
                                    'quicktags' => true,
                                ]
                            );
                            ?>
                        </div>

                        <h3 class="we-section-title">
                            Detalles del Tour
                        </h3>

                        <div class="we-two-col">

                            <div class="we-form-group">
                                <label for="tour_coords">
                                    Coordenadas
                                    <small>
                                        línea superior del header del modal
                                    </small>
                                </label>

                                <input type="text" id="tour_coords" name="tour_coords"
                                    value="<?php echo esc_attr($defaults['tour_coords']); ?>"
                                    placeholder="Ej: 09°35N 83°49W">
                            </div>

                            <div class="we-form-group">
                                <label for="tour_city">
                                    Ciudad base
                                    <small>
                                        cards del shortcode y atributos del modal
                                    </small>
                                </label>

                                <input type="text" id="tour_city" name="tour_city"
                                    value="<?php echo esc_attr($defaults['tour_city']); ?>" placeholder="Ej: Monteverde">
                            </div>

                            <div class="we-form-group">
                                <label for="tour_duration">
                                    Duración
                                    <small>
                                        cards, modal y página individual
                                    </small>
                                </label>

                                <input type="text" id="tour_duration" name="tour_duration"
                                    value="<?php echo esc_attr($defaults['tour_duration']); ?>"
                                    placeholder="Ej: 7 días / 6 noches">
                            </div>

                            <div class="we-form-group">
                                <label for="tour_max_people">
                                    Máximo de personas
                                    <small>
                                        resumen rápido en cards, modal y single
                                    </small>
                                </label>

                                <input type="number" id="tour_max_people" name="tour_max_people"
                                    value="<?php echo esc_attr($defaults['tour_max_people']); ?>" placeholder="Ej: 6">
                            </div>

                            <div class="we-form-group">
                                <label for="tour_price">
                                    Precio
                                    <small>
                                        precio principal del modal y resúmenes del tour
                                    </small>
                                </label>

                                <input type="text" id="tour_price" name="tour_price"
                                    value="<?php echo esc_attr($defaults['tour_price']); ?>" placeholder="Ej: $3,450">
                            </div>

                            <div class="we-form-group">
                                <label for="tour_price_secondary">
                                    Single supplement
                                    <small>
                                        línea adicional de precio en modal y single
                                    </small>
                                </label>

                                <input type="text" id="tour_price_secondary" name="tour_price_secondary"
                                    value="<?php echo esc_attr($defaults['tour_price_secondary']); ?>"
                                    placeholder="Ej: $3,950">
                            </div>

                            <div class="we-form-group">
                                <label for="tour_price_secondary_label">
                                    Etiqueta del single supplement
                                    <small>
                                        texto que acompaña el precio secundario
                                    </small>
                                </label>

                                <input type="text" id="tour_price_secondary_label" name="tour_price_secondary_label"
                                    value="<?php echo esc_attr($defaults['tour_price_secondary_label']); ?>"
                                    placeholder="Ej: Single supplement">
                            </div>

                            <div class="we-form-group">
                                <label for="tour_next_date">
                                    Próxima salida
                                    <small>
                                        atributo corto en el modal
                                    </small>
                                </label>

                                <input type="text" id="tour_next_date" name="tour_next_date"
                                    value="<?php echo esc_attr($defaults['tour_next_date']); ?>"
                                    placeholder="Ej: Jan 15, 2026">
                            </div>

                            <div class="we-form-group">
                                <label for="tour_type">
                                    Tipo de tour
                                    <small>
                                        atributo corto en el modal
                                    </small>
                                </label>

                                <input type="text" id="tour_type" name="tour_type"
                                    value="<?php echo esc_attr($defaults['tour_type']); ?>"
                                    placeholder="Ej: Tour para grupo pequeño (máx. 6)">
                            </div>

                            <div class="we-form-group">
                                <label for="tour_language">
                                    Idioma
                                    <small>
                                        atributo del modal y contexto para guías
                                    </small>
                                </label>

                                <input type="text" id="tour_language" name="tour_language"
                                    value="<?php echo esc_attr($defaults['tour_language']); ?>"
                                    placeholder="Ej: Inglés y español">
                            </div>

                            <div class="we-form-group">
                                <label for="tour_pace">
                                    Ritmo
                                    <small>
                                        bloque de transport/accommodation del modal
                                    </small>
                                </label>

                                <input type="text" id="tour_pace" name="tour_pace"
                                    value="<?php echo esc_attr($defaults['tour_pace']); ?>"
                                    placeholder="Ej: Moderado — caminatas diarias de 3–6 km">
                            </div>

                            <div class="we-form-group">
                                <label for="tour_transport">
                                    Transporte
                                    <small>
                                        sección Transport del modal
                                    </small>
                                </label>

                                <input type="text" id="tour_transport" name="tour_transport"
                                    value="<?php echo esc_attr($defaults['tour_transport']); ?>"
                                    placeholder="Ej: Vehículo privado 4x4 y traslado de montaña">
                            </div>

                            <div class="we-form-group">
                                <label for="tour_accommodation">
                                    Alojamiento
                                    <small>
                                        modal y página individual
                                    </small>
                                </label>

                                <input type="text" id="tour_accommodation" name="tour_accommodation"
                                    value="<?php echo esc_attr($defaults['tour_accommodation']); ?>"
                                    placeholder="Ej: Eco-lodge y estación de investigación">
                            </div>

                            <div class="we-form-group">
                                <label for="tour_dates">
                                    Fechas disponibles
                                    <small>
                                        cards y resumen general del tour
                                    </small>
                                </label>

                                <input type="text" id="tour_dates" name="tour_dates"
                                    value="<?php echo esc_attr($defaults['tour_dates']); ?>"
                                    placeholder="Ej: Jan 15–21 · Feb 12–18 · Mar 10–16">
                            </div>

                            <div class="we-form-group">
                                <label for="tour_map_custom_url">
                                    Link de Google Maps
                                    <small>
                                        mapa y botón de ruta dentro del modal
                                    </small>
                                </label>

                                <input type="url" id="tour_map_custom_url" name="tour_map_custom_url"
                                    value="<?php echo esc_url($defaults['tour_map_custom_url']); ?>"
                                    placeholder="https://maps.google.com/...">
                                <small class="we-hint">Para evitar mapas grises: usa el link de <strong>Insertar un
                                        mapa</strong>
                                    (URL que empieza con <code>https://www.google.com/maps/embed?pb=...</code>). Evita
                                    enlaces
                                    cortos de tipo <code>maps.app.goo.gl</code>.</small>
                                <div id="we-map-url-notice" class="we-hint" role="status" aria-live="polite"
                                    style="display:none; margin-top:8px; font-style:normal;"></div>
                            </div>

                            <div class="we-form-group">
                                <label for="tour_youtube_url">
                                    Video de YouTube
                                    <small>
                                        se mostrará al inicio del modal del tour
                                    </small>
                                </label>

                                <input type="url" id="tour_youtube_url" name="tour_youtube_url"
                                    value="<?php echo esc_url($defaults['tour_youtube_url']); ?>"
                                    placeholder="https://www.youtube.com/watch?v=... o https://youtu.be/...">
                                <small class="we-hint">Acepta enlaces normales de YouTube, Shorts, embed o youtu.be. En el
                                    modal se mostrará embebido y además saldrá el botón para verlo en YouTube.</small>
                            </div>

                        </div>

                        <!-- Dificultad -->
                        <div class="we-form-group">
                            <label for="tour_difficulty">
                                Dificultad
                                <small>
                                    cards, modal y página individual
                                </small>
                            </label>

                            <select id="tour_difficulty" name="tour_difficulty">
                                <option value="">
                                    Seleccionar...
                                </option>

                                <option value="Easy" <?php selected($defaults['tour_difficulty'], 'Easy'); ?>>
                                    Easy
                                </option>

                                <option value="Moderate" <?php selected($defaults['tour_difficulty'], 'Moderate'); ?>>
                                    Moderate
                                </option>

                                <option value="Advanced" <?php selected($defaults['tour_difficulty'], 'Advanced'); ?>>
                                    Advanced
                                </option>

                                <option value="Easy–Moderate" <?php selected($defaults['tour_difficulty'], 'Easy–Moderate'); ?>>
                                    Easy–Moderate
                                </option>

                                <option value="Moderate–Advanced" <?php selected($defaults['tour_difficulty'], 'Moderate–Advanced'); ?>>
                                    Moderate–Advanced
                                </option>
                            </select>
                        </div>

                    </div>

                    <!-- Sidebar -->
                    <div class="we-form-sidebar">

                        <!-- Publicar -->
                        <div class="we-sidebar-box">

                            <h3>
                                Publicar
                            </h3>

                            <div class="we-form-group">

                                <label for="we_status">
                                    Estado
                                </label>

                                <select id="we_status" name="we_status">
                                    <option value="publish" <?php selected($defaults['status'], 'publish'); ?>>
                                        Publicado
                                    </option>

                                    <option value="draft" <?php selected($defaults['status'], 'draft'); ?>>
                                        Borrador
                                    </option>
                                </select>

                            </div>

                            <button type="submit" name="we_save_tour" class="button button-primary button-hero"
                                style="width:100%">
                                <?php echo $is_edit ? '💾 Guardar Cambios' : '✨ Crear Tour'; ?>
                            </button>

                            <?php if ($is_edit): ?>

                                <a href="<?php echo esc_url(get_permalink($post_id)); ?>" target="_blank" class="button"
                                    style="width:100%;margin-top:8px">
                                    👁 Ver en sitio
                                </a>

                            <?php endif; ?>

                        </div>

                        <!-- Imagen principal -->
                        <div class="we-sidebar-box">

                            <h3>
                                Imagen Principal
                            </h3>

                            <div class="we-image-uploader">

                                <input type="hidden" id="we_featured_img" name="we_featured_img"
                                    value="<?php echo esc_attr($defaults['featured_img']); ?>">

                                <div id="we-image-preview" class="we-image-preview"
                                    style="<?php echo $thumb_url ? '' : 'display:none;'; ?>">

                                    <img src="<?php echo esc_url($thumb_url); ?>" alt="" id="we-preview-img">

                                    <button type="button" class="we-remove-img" onclick="weRemoveImage()">
                                        ×
                                    </button>

                                </div>

                                <button type="button" class="button we-upload-btn" id="we-upload-btn"
                                    onclick="weOpenMediaUploader()"
                                    style="<?php echo $thumb_url ? 'display:none;' : ''; ?>">
                                    📷 Subir imagen
                                </button>

                            </div>

                            <p class="we-hint">
                                Esta imagen se usa en cards, en la galería principal del modal y como hero de la página
                                individual.
                            </p>

                        </div>

                        <!-- Categorías -->
                        <div class="we-sidebar-box">

                            <h3>
                                Categorías
                            </h3>

                            <div class="we-cat-checkboxes">

                                <?php foreach ($all_cats as $cat): ?>

                                    <label class="we-cat-label">

                                        <input type="checkbox" name="we_categories[]"
                                            value="<?php echo esc_attr($cat->term_id); ?>" <?php checked(in_array($cat->term_id, $selected_cats)); ?>>

                                        <?php echo esc_html($cat->name); ?>

                                    </label>

                                <?php endforeach; ?>

                            </div>

                            <p class="we-hint">
                                Define en qué programa/listado aparece el tour y qué breadcrumb usa la página individual.
                            </p>

                        </div>

                        <div class="we-sidebar-box">

                            <h3>
                                Tour Finder Data
                            </h3>

                            <p class="we-hint">
                                Esta sección sí está conectada con el Tour Finder. El wizard recibe estos datos desde
                                <strong>dg_get_tour_finder_wizard_data()</strong> y la lógica de filtrado/ranking vive en el
                                archivo JS del wizard.
                                No afecta la card ni el modal directamente: solo cambia qué tours recomienda el Finder.
                            </p>

                            <p class="we-hint">
                                Importante: aquí ya no se marcan opciones por defecto. Solo se guarda lo que selecciones de
                                forma explícita.
                            </p>

                            <?php if ($current_tour_slug !== ''): ?>
                                <p class="we-hint">
                                    Slug actual del tour: <strong><?php echo esc_html($current_tour_slug); ?></strong><br>
                                    <?php if ($current_tour_matches_catalog): ?>
                                        Este slug coincide con el catálogo oficial del Tour Finder, así que el wizard puede usar la
                                        foto, título, enlace y metadatos reales de este tour.
                                    <?php else: ?>
                                        Este slug no coincide con el catálogo oficial del Tour Finder. El wizard seguirá usando su
                                        catálogo semilla salvo que este tour coincida con uno de esos slugs oficiales.
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>

                            <div class="we-form-group">
                                <label>
                                    Main Focus
                                </label>

                                <div class="we-focus-grid">
                                    <?php foreach ($tour_finder_main_focus_options as $focus_option): ?>
                                        <?php
                                        $focus_id = (int) $focus_option['id'];
                                        $is_focus_selected = isset($selected_focus_priorities[$focus_id]);
                                        $focus_priority = $selected_focus_priorities[$focus_id] ?? 1;
                                        ?>
                                        <div class="we-focus-item">
                                            <label class="we-cat-label">
                                                <input type="hidden"
                                                    name="tour_main_focus[<?php echo esc_attr($focus_id); ?>][enabled]"
                                                    value="0">
                                                <input type="checkbox"
                                                    name="tour_main_focus[<?php echo esc_attr($focus_id); ?>][enabled]"
                                                    value="1" <?php checked($is_focus_selected); ?>>
                                                <?php echo esc_html($focus_option['label']); ?>
                                            </label>

                                            <div class="we-priority-row">
                                                <span>Priority</span>
                                                <select name="tour_main_focus[<?php echo esc_attr($focus_id); ?>][priority]">
                                                    <option value="1" <?php selected($focus_priority, 1); ?>>Highest (***)
                                                    </option>
                                                    <option value="2" <?php selected($focus_priority, 2); ?>>Medium (**)
                                                    </option>
                                                    <option value="3" <?php selected($focus_priority, 3); ?>>Lower (*)</option>
                                                </select>
                                            </div>

                                            <input type="hidden"
                                                name="tour_main_focus[<?php echo esc_attr($focus_id); ?>][categoryId]"
                                                value="<?php echo esc_attr($focus_id); ?>">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="we-form-group">
                                <label>
                                    Region
                                </label>

                                <div class="we-cat-checkboxes">
                                    <?php foreach ($tour_finder_region_options as $region_option): ?>
                                        <label class="we-cat-label">
                                            <input type="checkbox" name="tour_regions[]"
                                                value="<?php echo esc_attr($region_option['id']); ?>" <?php checked(!empty($selected_regions_lookup[(int) $region_option['id']])); ?>>
                                            <?php echo esc_html($region_option['label']); ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="we-form-group">
                                <label>
                                    Duration
                                </label>

                                <div class="we-radio-stack">
                                    <?php foreach ($tour_finder_duration_options as $duration_option): ?>
                                        <label class="we-inline-radio">
                                            <input type="radio" name="tour_duration_band"
                                                value="<?php echo esc_attr($duration_option['id']); ?>" <?php checked((int) $defaults['tour_duration_band'], (int) $duration_option['id']); ?>>
                                            <span><?php echo esc_html($duration_option['label']); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="we-form-group">
                                <label>
                                    Availability Status
                                </label>

                                <div class="we-radio-stack">
                                    <?php foreach ($tour_finder_status_options as $status_option): ?>
                                        <label class="we-inline-radio">
                                            <input type="radio" name="tour_status"
                                                value="<?php echo esc_attr($status_option['value']); ?>" <?php checked($defaults['tour_status'], $status_option['value']); ?>>
                                            <span><?php echo esc_html($status_option['label']); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                        </div>

                        <!-- Extras -->
                        <div class="we-sidebar-box">

                            <h3>
                                Extras
                            </h3>

                            <div class="we-form-group">

                                <label for="_we_subtitle">
                                    Subtítulo
                                    <small>
                                        se usa en la página individual del tour
                                    </small>
                                </label>

                                <input type="text" id="_we_subtitle" name="_we_subtitle"
                                    value="<?php echo esc_attr($defaults['_we_subtitle']); ?>"
                                    placeholder="Ej: Recorre las fronteras salvajes">

                            </div>

                            <div class="we-form-group">

                                <label for="_we_pdf_url">
                                    PDF del Programa (URL)
                                    <small>
                                        activa botones de descarga en listados y página individual
                                    </small>
                                </label>

                                <input type="url" id="_we_pdf_url" name="_we_pdf_url"
                                    value="<?php echo esc_url($defaults['_we_pdf_url']); ?>" placeholder="https://...">

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ═══════════════════════════════════════════════════════════════════════
             PANEL: GALERÍA
        ═══════════════════════════════════════════════════════════════════════ -->

            <div class="we-tab-panel" id="tab-gallery">

                <h3 class="we-section-title">
                    Galería de Imágenes
                </h3>

                <p class="we-tab-intro">
                    Estas imágenes alimentan la galería del modal. La destacada sigue siendo la portada principal; aquí
                    agrega fotos de apoyo del tour.
                </p>

                <div class="we-repeater" data-repeater="gallery">

                    <div class="we-repeater-items">

                        <?php
                        $gallery = $defaults['tour_gallery'];

                        if (empty($gallery)) {
                            $gallery = [''];
                        }

                        foreach ($gallery as $i => $img):
                            ?>

                            <div class="we-repeater-row" data-index="<?php echo esc_attr($i); ?>">

                                <div class="we-form-group">

                                    <label>
                                        Imagen <?php echo $i + 1; ?>
                                    </label>

                                    <div class="we-image-uploader" data-field="tour_gallery[<?php echo $i; ?>]">

                                        <div class="we-image-preview"
                                            style="display:<?php echo !empty($img) ? 'block' : 'none'; ?>;">

                                            <?php if (!empty($img)): ?>

                                                <img src="<?php echo esc_url($img); ?>" alt="" style="max-width:200px">

                                            <?php endif; ?>

                                            <button type="button" class="we-remove-img" onclick="weRemoveRepeaterImage(this)">
                                                ×
                                            </button>

                                        </div>

                                        <button type="button" class="button we-upload-btn"
                                            onclick="weOpenRepeaterUploader(this)"
                                            style="display:<?php echo !empty($img) ? 'none' : 'inline-flex'; ?>">
                                            Seleccionar imagen
                                        </button>

                                        <input type="hidden" name="tour_gallery[<?php echo $i; ?>]"
                                            value="<?php echo esc_url($img); ?>">

                                    </div>

                                </div>

                                <button type="button" class="button we-repeater-remove"
                                    onclick="this.closest('.we-repeater-row').remove()">
                                    Eliminar
                                </button>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <button type="button" class="button we-repeater-add" onclick="weAddRepeaterSimple(this, 'gallery')">
                        + Añadir imagen
                    </button>

                </div>

                <template id="we-tpl-gallery">

                    <div class="we-repeater-row">

                        <div class="we-form-group">

                            <label>
                                Imagen
                            </label>

                            <div class="we-image-uploader" data-field="tour_gallery[__IDX__]">

                                <div class="we-image-preview" style="display:none">

                                    <img src="" alt="" style="max-width:200px">

                                    <button type="button" class="we-remove-img" onclick="weRemoveRepeaterImage(this)">
                                        ×
                                    </button>

                                </div>

                                <button type="button" class="button we-upload-btn" onclick="weOpenRepeaterUploader(this)">
                                    Seleccionar imagen
                                </button>

                                <input type="hidden" name="tour_gallery[__IDX__]" value="">

                            </div>

                        </div>

                        <button type="button" class="button we-repeater-remove"
                            onclick="this.closest('.we-repeater-row').remove()">
                            Eliminar
                        </button>

                    </div>

                </template>

            </div>

            <!-- ═══════════════════════════════════════════════════════════════════════
             PANEL: ABOUT
        ═══════════════════════════════════════════════════════════════════════ -->

            <div class="we-tab-panel" id="tab-about">

                <h3 class="we-section-title">
                    Sobre este tour
                </h3>

                <p class="we-tab-intro">
                    Usa esta sección para convertir el <strong>Overview</strong> del documento en 2 a 5 párrafos claros. Se
                    muestra en el modal, no en la card.
                </p>

                <div class="we-repeater" data-repeater="about">

                    <div class="we-repeater-items">

                        <?php foreach ($defaults['tour_about'] as $i => $text): ?>

                            <div class="we-repeater-row" data-index="<?php echo esc_attr($i); ?>">

                                <div class="we-form-group">

                                    <label>
                                        Párrafo <?php echo $i + 1; ?>
                                    </label>

                                    <textarea name="tour_about[<?php echo $i; ?>]" rows="4"
                                        placeholder="Escribe un párrafo..."><?php echo esc_textarea($text); ?></textarea>

                                </div>

                                <button type="button" class="button we-repeater-remove"
                                    onclick="this.closest('.we-repeater-row').remove()">
                                    Eliminar párrafo
                                </button>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <button type="button" class="button we-repeater-add" onclick="weAddRepeaterSimple(this, 'about')">
                        + Añadir párrafo
                    </button>

                </div>

                <template id="we-tpl-about">

                    <div class="we-repeater-row">

                        <div class="we-form-group">

                            <label>
                                Párrafo
                            </label>

                            <textarea name="tour_about[__IDX__]" rows="4" placeholder="Escribe un párrafo..."></textarea>

                        </div>

                        <button type="button" class="button we-repeater-remove"
                            onclick="this.closest('.we-repeater-row').remove()">
                            Eliminar párrafo
                        </button>

                    </div>

                </template>

            </div>

            <!-- ═══════════════════════════════════════════════════════════════════════
             PANEL: FAUNA
        ═══════════════════════════════════════════════════════════════════════ -->

            <div class="we-tab-panel" id="tab-wildlife">

                <h3 class="we-section-title">
                    Fauna / Experiencia
                </h3>

                <p class="we-tab-intro">
                    Este bloque funciona muy bien para especies objetivo, highlights biológicos o puntos fuertes del viaje.
                    Cada fila aparece como tarjeta visual en el modal.
                </p>

                <div class="we-form-group">

                    <label for="tour_wildlife_label">
                        Título de la sección
                    </label>

                    <input type="text" id="tour_wildlife_label" name="tour_wildlife_label"
                        value="<?php echo esc_attr($defaults['tour_wildlife_label']); ?>"
                        placeholder="Ej: Pájaros que puedas observar / Vida silvestre">

                </div>

                <div class="we-repeater" data-repeater="wildlife">

                    <div class="we-repeater-items">

                        <?php foreach ($defaults['tour_wildlife'] as $i => $item): ?>

                            <?php
                            $item = wp_parse_args(
                                $item,
                                [
                                    'name' => '',
                                    'scientific' => '',
                                    'image' => '',
                                    'is_target' => '',
                                ]
                            );
                            ?>

                            <div class="we-repeater-row we-wildlife-row" data-index="<?php echo esc_attr($i); ?>">

                                <div class="we-two-col">

                                    <div class="we-form-group">

                                        <label>
                                            Nombre común
                                        </label>

                                        <input type="text" name="tour_wildlife[<?php echo $i; ?>][name]"
                                            value="<?php echo esc_attr($item['name']); ?>"
                                            placeholder="Ej: Resplendent Quetzal">

                                    </div>

                                    <div class="we-form-group">

                                        <label>
                                            Nombre científico / Categoría
                                        </label>

                                        <input type="text" name="tour_wildlife[<?php echo $i; ?>][scientific]"
                                            value="<?php echo esc_attr($item['scientific']); ?>"
                                            placeholder="Ej: Pharomachrus mocinno">

                                    </div>

                                </div>

                                <div class="we-form-group">

                                    <label>
                                        Foto del animal
                                    </label>

                                    <div class="we-image-uploader" data-field="tour_wildlife[<?php echo $i; ?>][image]">

                                        <div class="we-image-preview"
                                            style="display:<?php echo !empty($item['image']) ? 'block' : 'none'; ?>;">

                                            <?php if (!empty($item['image'])): ?>

                                                <img src="<?php echo esc_url($item['image']); ?>" alt="" style="max-width:200px">

                                            <?php endif; ?>

                                            <button type="button" class="we-remove-img" onclick="weRemoveRepeaterImage(this)">
                                                ×
                                            </button>

                                        </div>

                                        <button type="button" class="button we-upload-btn"
                                            onclick="weOpenRepeaterUploader(this)"
                                            style="display:<?php echo !empty($item['image']) ? 'none' : 'inline-flex'; ?>">
                                            Seleccionar imagen
                                        </button>

                                        <input type="hidden" name="tour_wildlife[<?php echo $i; ?>][image]"
                                            value="<?php echo esc_url($item['image']); ?>">

                                    </div>

                                </div>

                                <div class="we-form-group">

                                    <label>

                                        <input type="checkbox" name="tour_wildlife[<?php echo $i; ?>][is_target]" value="1"
                                            <?php checked($item['is_target'], '1'); ?>>

                                        Es especie objetivo
                                        (muestra insignia Objetivo)

                                    </label>

                                </div>

                                <button type="button" class="button we-repeater-remove"
                                    onclick="this.closest('.we-repeater-row').remove()">
                                    Eliminar especie
                                </button>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <button type="button" class="button we-repeater-add" onclick="weAddRepeaterComplex(this, 'wildlife')">
                        + Añadir especie
                    </button>

                </div>

                <template id="we-tpl-wildlife">

                    <div class="we-repeater-row we-wildlife-row">

                        <div class="we-two-col">

                            <div class="we-form-group">

                                <label>
                                    Nombre común
                                </label>

                                <input type="text" name="tour_wildlife[__IDX__][name]"
                                    placeholder="Ej: Resplendent Quetzal">

                            </div>

                            <div class="we-form-group">

                                <label>
                                    Nombre científico / Categoría
                                </label>

                                <input type="text" name="tour_wildlife[__IDX__][scientific]"
                                    placeholder="Ej: Pharomachrus mocinno">

                            </div>

                        </div>

                        <div class="we-form-group">

                            <label>
                                Foto del animal
                            </label>

                            <div class="we-image-uploader" data-field="tour_wildlife[__IDX__][image]">

                                <div class="we-image-preview" style="display:none">

                                    <img src="" alt="" style="max-width:200px">

                                    <button type="button" class="we-remove-img" onclick="weRemoveRepeaterImage(this)">
                                        ×
                                    </button>

                                </div>

                                <button type="button" class="button we-upload-btn" onclick="weOpenRepeaterUploader(this)">
                                    Seleccionar imagen
                                </button>

                                <input type="hidden" name="tour_wildlife[__IDX__][image]" value="">

                            </div>

                        </div>

                        <div class="we-form-group">

                            <label>

                                <input type="checkbox" name="tour_wildlife[__IDX__][is_target]" value="1">

                                Es especie objetivo
                                (muestra insignia Objetivo)

                            </label>

                        </div>

                        <button type="button" class="button we-repeater-remove"
                            onclick="this.closest('.we-repeater-row').remove()">
                            Eliminar especie
                        </button>

                    </div>

                </template>

            </div>

            <!-- ═══════════════════════════════════════════════════════════════════════
             PANEL: ORGANIZADOR
        ═══════════════════════════════════════════════════════════════════════ -->

            <div class="we-tab-panel" id="tab-organizer">

                <h3 class="we-section-title">
                    Organizador
                </h3>

                <p class="we-tab-intro">
                    Se muestra en el bloque Organizer del modal. Si no lo llenas, esa sección simplemente se oculta.
                </p>

                <div class="we-two-col">

                    <div class="we-form-group">

                        <label for="tour_organizer_name">
                            Nombre del organizador
                        </label>

                        <input type="text" id="tour_organizer_name" name="tour_organizer_name"
                            value="<?php echo esc_attr($defaults['tour_organizer_name']); ?>"
                            placeholder="Ej: WILD ENDEMICS">

                    </div>

                    <div class="we-form-group">

                        <label for="tour_organizer_role">
                            Rol del organizador
                        </label>

                        <input type="text" id="tour_organizer_role" name="tour_organizer_role"
                            value="<?php echo esc_attr($defaults['tour_organizer_role']); ?>"
                            placeholder="Ej: Costa Rica-based expedition operator">

                    </div>

                </div>

                <div class="we-form-group">

                    <label>
                        Logo del organizador
                    </label>

                    <div class="we-image-uploader" data-field="tour_organizer_logo">

                        <div class="we-image-preview"
                            style="display:<?php echo !empty($defaults['tour_organizer_logo']) ? 'block' : 'none'; ?>;">

                            <?php if (!empty($defaults['tour_organizer_logo'])): ?>

                                <img src="<?php echo esc_url($defaults['tour_organizer_logo']); ?>" alt=""
                                    style="max-width:200px">

                            <?php endif; ?>

                            <button type="button" class="we-remove-img" onclick="weRemoveRepeaterImage(this)">
                                ×
                            </button>

                        </div>

                        <button type="button" class="button we-upload-btn" onclick="weOpenRepeaterUploader(this)"
                            style="display:<?php echo !empty($defaults['tour_organizer_logo']) ? 'none' : 'inline-flex'; ?>">
                            Seleccionar logo
                        </button>

                        <input type="hidden" name="tour_organizer_logo"
                            value="<?php echo esc_url($defaults['tour_organizer_logo']); ?>">

                    </div>

                </div>

            </div>

            <!-- ═══════════════════════════════════════════════════════════════════════
             PANEL: GUÍAS
        ═══════════════════════════════════════════════════════════════════════ -->

            <div class="we-tab-panel" id="tab-guides">

                <h3 class="we-section-title">
                    Guías
                </h3>

                <p class="we-tab-intro">
                    Cada guía aparece en el modal con nombre, idiomas, rol y foto. Úsalo cuando el tour tenga líderes o
                    especialistas destacados.
                </p>

                <div class="we-repeater" data-repeater="guides">

                    <div class="we-repeater-items">

                        <?php foreach ($defaults['tour_guides'] as $i => $guide): ?>

                            <?php
                            $guide = wp_parse_args(
                                $guide,
                                [
                                    'name' => '',
                                    'language' => '',
                                    'role' => '',
                                    'photo' => '',
                                ]
                            );
                            ?>

                            <div class="we-repeater-row we-guide-row" data-index="<?php echo esc_attr($i); ?>">

                                <div class="we-two-col">

                                    <div class="we-form-group">

                                        <label>
                                            Nombre
                                        </label>

                                        <input type="text" name="tour_guides[<?php echo $i; ?>][name]"
                                            value="<?php echo esc_attr($guide['name']); ?>" placeholder="Ej: Carlos Méndez">

                                    </div>

                                    <div class="we-form-group">

                                        <label>
                                            Idiomas
                                        </label>

                                        <input type="text" name="tour_guides[<?php echo $i; ?>][language]"
                                            value="<?php echo esc_attr($guide['language']); ?>"
                                            placeholder="Ej: English · Spanish">

                                    </div>

                                </div>

                                <div class="we-form-group">

                                    <label>
                                        Rol
                                    </label>

                                    <input type="text" name="tour_guides[<?php echo $i; ?>][role]"
                                        value="<?php echo esc_attr($guide['role']); ?>"
                                        placeholder="Ej: Guía principal de observación de aves">

                                </div>

                                <div class="we-form-group">

                                    <label>
                                        Foto del guía
                                    </label>

                                    <div class="we-image-uploader" data-field="tour_guides[<?php echo $i; ?>][photo]">

                                        <div class="we-image-preview"
                                            style="display:<?php echo !empty($guide['photo']) ? 'block' : 'none'; ?>;">

                                            <?php if (!empty($guide['photo'])): ?>

                                                <img src="<?php echo esc_url($guide['photo']); ?>" alt="" style="max-width:200px">

                                            <?php endif; ?>

                                            <button type="button" class="we-remove-img" onclick="weRemoveRepeaterImage(this)">
                                                ×
                                            </button>

                                        </div>

                                        <button type="button" class="button we-upload-btn"
                                            onclick="weOpenRepeaterUploader(this)"
                                            style="display:<?php echo !empty($guide['photo']) ? 'none' : 'inline-flex'; ?>">
                                            Seleccionar foto
                                        </button>

                                        <input type="hidden" name="tour_guides[<?php echo $i; ?>][photo]"
                                            value="<?php echo esc_url($guide['photo']); ?>">

                                    </div>

                                </div>

                                <button type="button" class="button we-repeater-remove"
                                    onclick="this.closest('.we-repeater-row').remove()">
                                    Eliminar guía
                                </button>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <button type="button" class="button we-repeater-add" onclick="weAddRepeaterComplex(this, 'guides')">
                        + Añadir guía
                    </button>

                </div>

                <template id="we-tpl-guides">

                    <div class="we-repeater-row we-guide-row">

                        <div class="we-two-col">

                            <div class="we-form-group">

                                <label>
                                    Nombre
                                </label>

                                <input type="text" name="tour_guides[__IDX__][name]" placeholder="Ej: Carlos Méndez">

                            </div>

                            <div class="we-form-group">

                                <label>
                                    Idiomas
                                </label>

                                <input type="text" name="tour_guides[__IDX__][language]"
                                    placeholder="Ej: English · Spanish">

                            </div>

                        </div>

                        <div class="we-form-group">

                            <label>
                                Rol
                            </label>

                            <input type="text" name="tour_guides[__IDX__][role]"
                                placeholder="Ej: Guía principal de observación de aves">

                        </div>

                        <div class="we-form-group">

                            <label>
                                Foto del guía
                            </label>

                            <div class="we-image-uploader" data-field="tour_guides[__IDX__][photo]">

                                <div class="we-image-preview" style="display:none">

                                    <img src="" alt="" style="max-width:200px">

                                    <button type="button" class="we-remove-img" onclick="weRemoveRepeaterImage(this)">
                                        ×
                                    </button>

                                </div>

                                <button type="button" class="button we-upload-btn" onclick="weOpenRepeaterUploader(this)">
                                    Seleccionar foto
                                </button>

                                <input type="hidden" name="tour_guides[__IDX__][photo]" value="">

                            </div>

                        </div>

                        <button type="button" class="button we-repeater-remove"
                            onclick="this.closest('.we-repeater-row').remove()">
                            Eliminar guía
                        </button>

                    </div>

                </template>

            </div>

            <!-- ═══════════════════════════════════════════════════════════════════════
             PANEL: ITINERARIO
        ═══════════════════════════════════════════════════════════════════════ -->

            <div class="we-tab-panel" id="tab-itinerary">

                <h3 class="we-section-title">
                    Itinerario
                </h3>

                <p class="we-tab-intro">
                    Pasa aquí el día por día del documento. Cada fila se muestra dentro del acordeón principal del modal.
                </p>

                <div class="we-repeater" data-repeater="itinerary">

                    <div class="we-repeater-items">

                        <?php foreach ($defaults['tour_itinerary'] as $i => $day): ?>

                            <?php
                            $day = wp_parse_args(
                                $day,
                                [
                                    'day' => '',
                                    'title' => '',
                                    'description' => '',
                                ]
                            );
                            ?>

                            <div class="we-repeater-row we-itinerary-row" data-index="<?php echo esc_attr($i); ?>">

                                <div class="we-two-col">

                                    <div class="we-form-group">

                                        <label>
                                            Etiqueta
                                        </label>

                                        <input type="text" name="tour_itinerary[<?php echo $i; ?>][day]"
                                            value="<?php echo esc_attr($day['day']); ?>" placeholder="Ej: Día 1">

                                    </div>

                                    <div class="we-form-group">

                                        <label>
                                            Título
                                        </label>

                                        <input type="text" name="tour_itinerary[<?php echo $i; ?>][title]"
                                            value="<?php echo esc_attr($day['title']); ?>" placeholder="Ej: Llegada a San José">

                                    </div>

                                </div>

                                <div class="we-form-group">

                                    <label>
                                        Descripción
                                    </label>

                                    <textarea id="tour_itinerary_description_<?php echo $i; ?>" class="we-itinerary-editor"
                                        name="tour_itinerary[<?php echo $i; ?>][description]" rows="6"
                                        placeholder="Describe las actividades del día..."><?php echo esc_textarea($day['description']); ?></textarea>

                                </div>

                                <button type="button" class="button we-repeater-remove" onclick="weRemoveRepeaterRow(this)">
                                    Eliminar día
                                </button>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <button type="button" class="button we-repeater-add" onclick="weAddRepeaterComplex(this, 'itinerary')">
                        + Añadir día
                    </button>

                </div>

                <template id="we-tpl-itinerary">

                    <div class="we-repeater-row we-itinerary-row">

                        <div class="we-two-col">

                            <div class="we-form-group">

                                <label>
                                    Etiqueta
                                </label>

                                <input type="text" name="tour_itinerary[__IDX__][day]" placeholder="Ej: Día 1">

                            </div>

                            <div class="we-form-group">

                                <label>
                                    Título
                                </label>

                                <input type="text" name="tour_itinerary[__IDX__][title]"
                                    placeholder="Ej: Llegada a San José">

                            </div>

                        </div>

                        <div class="we-form-group">

                            <label>
                                Descripción
                            </label>

                            <textarea id="tour_itinerary_description___IDX__" class="we-itinerary-editor"
                                name="tour_itinerary[__IDX__][description]" rows="6"
                                placeholder="Describe las actividades del día..."></textarea>

                        </div>

                        <button type="button" class="button we-repeater-remove" onclick="weRemoveRepeaterRow(this)">
                            Eliminar día
                        </button>

                    </div>

                </template>

            </div>

            <!-- ═══════════════════════════════════════════════════════════════════════
             PANEL: DISPONIBILIDAD
        ═══════════════════════════════════════════════════════════════════════ -->

            <div class="we-tab-panel" id="tab-availability">

                <h3 class="we-section-title">
                    Disponibilidad y Precios
                </h3>

                <p class="we-tab-intro">
                    Usa Availability para salidas concretas y el bloque de Pricing para desgloses como double occupancy,
                    single supplement, extensiones o add-ons.
                </p>

                <div class="we-repeater" data-repeater="availability">

                    <div class="we-repeater-items">

                        <?php foreach ($defaults['tour_availability'] as $i => $item): ?>

                            <?php
                            $item = wp_parse_args(
                                $item,
                                [
                                    'date' => '',
                                    'status' => '',
                                ]
                            );
                            ?>

                            <div class="we-repeater-row we-avail-row" data-index="<?php echo esc_attr($i); ?>">

                                <div class="we-two-col">

                                    <div class="we-form-group">

                                        <label>
                                            Fecha
                                        </label>

                                        <input type="text" name="tour_availability[<?php echo $i; ?>][date]"
                                            value="<?php echo esc_attr($item['date']); ?>" placeholder="Ej: Jan 15–21, 2026">

                                    </div>

                                    <div class="we-form-group">

                                        <label>
                                            Estado
                                        </label>

                                        <input type="text" name="tour_availability[<?php echo $i; ?>][status]"
                                            value="<?php echo esc_attr($item['status']); ?>"
                                            placeholder="Ej: Quedan 3 cupos / Disponible">

                                    </div>

                                </div>

                                <button type="button" class="button we-repeater-remove"
                                    onclick="this.closest('.we-repeater-row').remove()">
                                    Eliminar fecha
                                </button>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <button type="button" class="button we-repeater-add"
                        onclick="weAddRepeaterComplex(this, 'availability')">
                        + Añadir fecha
                    </button>

                </div>

                <template id="we-tpl-availability">

                    <div class="we-repeater-row we-avail-row">

                        <div class="we-two-col">

                            <div class="we-form-group">

                                <label>
                                    Fecha
                                </label>

                                <input type="text" name="tour_availability[__IDX__][date]"
                                    placeholder="Ej: Jan 15–21, 2026">

                            </div>

                            <div class="we-form-group">

                                <label>
                                    Estado
                                </label>

                                <input type="text" name="tour_availability[__IDX__][status]"
                                    placeholder="Ej: Quedan 3 cupos / Disponible">

                            </div>

                        </div>

                        <button type="button" class="button we-repeater-remove"
                            onclick="this.closest('.we-repeater-row').remove()">
                            Eliminar fecha
                        </button>

                    </div>

                </template>

                <hr style="margin:24px 0;border:none;border-top:1px solid #dcdcde;">

                <h4 style="margin:0 0 16px;">
                    Desglose de precios (se mostrara completo en el modal)
                </h4>

                <div class="we-repeater" data-repeater="pricing">

                    <div class="we-repeater-items">

                        <?php
                        $pricing_rows = $defaults['tour_pricing'];

                        if (empty($pricing_rows)) {
                            $pricing_rows = [];
                        }

                        foreach ($pricing_rows as $i => $row):
                            $row = wp_parse_args(
                                is_array($row) ? $row : [],
                                [
                                    'label' => '',
                                    'amount' => '',
                                    'note' => '',
                                ]
                            );
                            ?>

                            <div class="we-repeater-row we-avail-row" data-index="<?php echo esc_attr($i); ?>">

                                <div class="we-two-col">

                                    <div class="we-form-group">

                                        <label>
                                            Concepto
                                        </label>

                                        <input type="text" name="tour_pricing[<?php echo $i; ?>][label]"
                                            value="<?php echo esc_attr($row['label']); ?>"
                                            placeholder="Ej: Price per person (double occupancy)">

                                    </div>

                                    <div class="we-form-group">

                                        <label>
                                            Monto
                                        </label>

                                        <input type="text" name="tour_pricing[<?php echo $i; ?>][amount]"
                                            value="<?php echo esc_attr($row['amount']); ?>" placeholder="Ej: 2100 USD">

                                    </div>

                                </div>

                                <div class="we-form-group">

                                    <label>
                                        Nota (opcional)
                                    </label>

                                    <textarea name="tour_pricing[<?php echo $i; ?>][note]" rows="2"
                                        placeholder="Ej: Includes all meals and transportation."><?php echo esc_textarea($row['note']); ?></textarea>

                                </div>

                                <button type="button" class="button we-repeater-remove"
                                    onclick="this.closest('.we-repeater-row').remove()">
                                    Eliminar precio
                                </button>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <button type="button" class="button we-repeater-add" onclick="weAddRepeaterComplex(this, 'pricing')">
                        + Añadir precio
                    </button>

                </div>

                <template id="we-tpl-pricing">

                    <div class="we-repeater-row we-avail-row">

                        <div class="we-two-col">

                            <div class="we-form-group">

                                <label>
                                    Concepto
                                </label>

                                <input type="text" name="tour_pricing[__IDX__][label]"
                                    placeholder="Ej: Price per person (double occupancy)">

                            </div>

                            <div class="we-form-group">

                                <label>
                                    Monto
                                </label>

                                <input type="text" name="tour_pricing[__IDX__][amount]" placeholder="Ej: 2100 USD">

                            </div>

                        </div>

                        <div class="we-form-group">

                            <label>
                                Nota (opcional)
                            </label>

                            <textarea name="tour_pricing[__IDX__][note]" rows="2"
                                placeholder="Ej: Includes all meals and transportation."></textarea>

                        </div>

                        <button type="button" class="button we-repeater-remove"
                            onclick="this.closest('.we-repeater-row').remove()">
                            Eliminar precio
                        </button>

                    </div>

                </template>

            </div>

            <!-- ═══════════════════════════════════════════════════════════════════════
             PANEL: INCLUIDO / NO INCLUIDO
        ═══════════════════════════════════════════════════════════════════════ -->

            <div class="we-tab-panel" id="tab-includes">

                <h3 class="we-section-title">
                    Incluido / No Incluido
                </h3>

                <p class="we-tab-intro">
                    Estos ítems se muestran al final del modal en Essentials. Si dejas algo vacío, el sistema puede usar los
                    defaults globales desde docs. Cuando un tour todavía no tiene datos propios, aquí ya se cargan
                    sugerencias
                    globales para que el editor las acepte, quite o amplíe.
                </p>

                <div class="we-two-col">

                    <!-- Incluido -->
                    <div>

                        <h4 style="margin:0 0 16px;color:#00a32a">
                            ✓ Incluido
                        </h4>

                        <div class="we-repeater" data-repeater="includes">

                            <div class="we-repeater-items">

                                <?php foreach ($defaults['tour_includes'] as $i => $text): ?>

                                    <div class="we-repeater-row we-include-row" data-index="<?php echo esc_attr($i); ?>"
                                        style="display:flex;gap:8px;align-items:start;padding:10px 14px;">

                                        <div class="we-form-group" style="flex:1;margin:0">

                                            <input type="text" name="tour_includes[<?php echo $i; ?>]"
                                                value="<?php echo esc_attr($text); ?>"
                                                placeholder="Ej: All in-tour transport — private 4x4...">

                                        </div>

                                        <button type="button" class="button we-repeater-remove" style="margin-top:2px"
                                            onclick="this.closest('.we-repeater-row').remove()">
                                            ×
                                        </button>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                            <button type="button" class="button we-repeater-add"
                                onclick="weAddRepeaterSimple(this, 'includes')">
                                + Añadir item
                            </button>

                        </div>

                        <template id="we-tpl-includes">

                            <div class="we-repeater-row we-include-row"
                                style="display:flex;gap:8px;align-items:start;padding:10px 14px;">

                                <div class="we-form-group" style="flex:1;margin:0">

                                    <input type="text" name="tour_includes[__IDX__]"
                                        placeholder="Ej: All in-tour transport — private 4x4...">

                                </div>

                                <button type="button" class="button we-repeater-remove" style="margin-top:2px"
                                    onclick="this.closest('.we-repeater-row').remove()">
                                    ×
                                </button>

                            </div>

                        </template>

                    </div>

                    <!-- No incluido -->
                    <div>

                        <h4 style="margin:0 0 16px;color:#d63638">
                            ✕ No Incluido
                        </h4>

                        <div class="we-repeater" data-repeater="excludes">

                            <div class="we-repeater-items">

                                <?php foreach ($defaults['tour_excludes'] as $i => $text): ?>

                                    <div class="we-repeater-row we-exclude-row" data-index="<?php echo esc_attr($i); ?>"
                                        style="display:flex;gap:8px;align-items:start;padding:10px 14px;">

                                        <div class="we-form-group" style="flex:1;margin:0">

                                            <input type="text" name="tour_excludes[<?php echo $i; ?>]"
                                                value="<?php echo esc_attr($text); ?>"
                                                placeholder="Ej: Vuelos internacionales hacia y desde San José...">

                                        </div>

                                        <button type="button" class="button we-repeater-remove" style="margin-top:2px"
                                            onclick="this.closest('.we-repeater-row').remove()">
                                            ×
                                        </button>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                            <button type="button" class="button we-repeater-add"
                                onclick="weAddRepeaterSimple(this, 'excludes')">
                                + Añadir item
                            </button>

                        </div>

                        <template id="we-tpl-excludes">

                            <div class="we-repeater-row we-exclude-row"
                                style="display:flex;gap:8px;align-items:start;padding:10px 14px;">

                                <div class="we-form-group" style="flex:1;margin:0">

                                    <input type="text" name="tour_excludes[__IDX__]"
                                        placeholder="Ej: Vuelos internacionales hacia y desde San José...">

                                </div>

                                <button type="button" class="button we-repeater-remove" style="margin-top:2px"
                                    onclick="this.closest('.we-repeater-row').remove()">
                                    ×
                                </button>

                            </div>

                        </template>

                    </div>

                </div>

            </div>

            <!-- ═══════════════════════════════════════════════════════════════════════
             PANEL: POLÍTICAS Y FAQ
        ═══════════════════════════════════════════════════════════════════════ -->

            <div class="we-tab-panel" id="tab-policies">

                <h3 class="we-section-title">
                    Travel notes, Rules, Americans y FAQ
                </h3>

                <p class="we-tab-intro">
                    Completa aquí el cierre informativo del modal. Rules y FAQ aparecen en los acordeones finales; Physical
                    Demands mantiene su propia sección técnica.
                </p>

                <div class="we-form-group">

                    <label for="tour_when_to_travel">
                        When to travel
                        <small>
                            acordeón final Essentials del modal
                        </small>
                    </label>

                    <?php
                    wp_editor(
                        $defaults['tour_when_to_travel'],
                        'tour_when_to_travel_editor',
                        [
                            'textarea_name' => 'tour_when_to_travel',
                            'textarea_rows' => 8,
                            'teeny' => true,
                            'quicktags' => true,
                            'media_buttons' => false,
                        ]
                    );
                    ?>
                    <p class="we-hint">Admite mucho texto. Si quieres poner enlaces, puedes pegar HTML seguro como &lt;a
                        href=&quot;https://...&quot;&gt;texto&lt;/a&gt; o usar formato [texto](https://...).</p>

                </div>

                <div class="we-form-group">

                    <label for="tour_can_americans_join">
                        Can Americans Join?
                        <small>
                            acordeón final Essentials del modal
                        </small>
                    </label>

                    <?php
                    wp_editor(
                        $defaults['tour_can_americans_join'],
                        'tour_can_americans_join_editor',
                        [
                            'textarea_name' => 'tour_can_americans_join',
                            'textarea_rows' => 8,
                            'teeny' => true,
                            'quicktags' => true,
                            'media_buttons' => false,
                        ]
                    );
                    ?>
                    <p class="we-hint">Este bloque es útil para requisitos legales, licencias, preguntas de viaje y enlaces
                        externos.</p>

                </div>

                <div class="we-form-group">

                    <label for="tour_terms_conditions">
                        Terms and Conditions
                        <small>
                            acordeón final Rules / Terms del modal
                        </small>
                    </label>

                    <?php
                    wp_editor(
                        $defaults['tour_terms_conditions'],
                        'tour_terms_conditions_editor',
                        [
                            'textarea_name' => 'tour_terms_conditions',
                            'textarea_rows' => 8,
                            'teeny' => true,
                            'quicktags' => true,
                            'media_buttons' => false,
                        ]
                    );
                    ?>
                    <p class="we-hint">También admite enlaces con HTML o formato [texto](https://...).</p>

                </div>

                <div class="we-form-group">

                    <label for="tour_physical_demands">
                        Physical Demands
                        <small>
                            sección Physical Demands del modal
                        </small>
                    </label>

                    <?php
                    wp_editor(
                        $defaults['tour_physical_demands'],
                        'tour_physical_demands_editor',
                        [
                            'textarea_name' => 'tour_physical_demands',
                            'textarea_rows' => 8,
                            'teeny' => true,
                            'quicktags' => true,
                            'media_buttons' => false,
                        ]
                    );
                    ?>
                    <p class="we-hint">Úsalo para texto largo. Si necesitas enlazar a una página externa, puedes usar HTML o
                        [texto](https://...).</p>

                </div>

                <hr style="
                    margin:24px 0;
                    border:none;
                    border-top:1px solid #dcdcde;
                ">

                <h4 style="margin:0 0 16px">
                    Preguntas Frecuentes (FAQ)
                </h4>

                <div class="we-repeater" data-repeater="faq">

                    <div class="we-repeater-items">

                        <?php foreach ($defaults['tour_faq'] as $i => $item): ?>

                            <?php
                            $item = wp_parse_args(
                                $item,
                                [
                                    'question' => '',
                                    'answer' => '',
                                ]
                            );
                            ?>

                            <div class="we-repeater-row we-faq-row" data-index="<?php echo esc_attr($i); ?>">

                                <div class="we-form-group">

                                    <label>
                                        Pregunta
                                    </label>

                                    <input type="text" name="tour_faq[<?php echo $i; ?>][question]"
                                        value="<?php echo esc_attr($item['question']); ?>" placeholder="Ej: ¿Qué debo llevar?">

                                </div>

                                <div class="we-form-group">

                                    <label>
                                        Respuesta
                                        <small>
                                            puede incluir enlaces HTML o [texto](https://...)
                                        </small>
                                    </label>

                                    <textarea name="tour_faq[<?php echo $i; ?>][answer]" rows="4"
                                        placeholder="Escribe la respuesta..."><?php echo esc_textarea($item['answer']); ?></textarea>

                                </div>

                                <button type="button" class="button we-repeater-remove"
                                    onclick="this.closest('.we-repeater-row').remove()">
                                    Eliminar FAQ
                                </button>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <button type="button" class="button we-repeater-add" onclick="weAddRepeaterComplex(this, 'faq')">
                        + Añadir FAQ
                    </button>

                </div>

                <template id="we-tpl-faq">

                    <div class="we-repeater-row we-faq-row">

                        <div class="we-form-group">

                            <label>
                                Pregunta
                            </label>

                            <input type="text" name="tour_faq[__IDX__][question]" placeholder="Ej: ¿Qué debo llevar?">

                        </div>

                        <div class="we-form-group">

                            <label>
                                Respuesta
                            </label>

                            <textarea name="tour_faq[__IDX__][answer]" rows="4"
                                placeholder="Escribe la respuesta..."></textarea>

                        </div>

                        <button type="button" class="button we-repeater-remove"
                            onclick="this.closest('.we-repeater-row').remove()">
                            Eliminar FAQ
                        </button>

                    </div>

                </template>

            </div>

        </form>

    </div>

    <!-- ═══════════════════════════════════════════════════════════════════════════════
     SCRIPTS
═══════════════════════════════════════════════════════════════════════════════ -->

    <script>

        // Tabs

        document.querySelectorAll('.we-admin-tab')
            .forEach(function (tab) {

                tab.addEventListener('click', function () {

                    var target = this.dataset.tab;

                    document
                        .querySelectorAll('.we-admin-tab')
                        .forEach(function (t) {
                            t.classList.remove('active');
                        });

                    document
                        .querySelectorAll('.we-tab-panel')
                        .forEach(function (panel) {
                            panel.classList.remove('active');
                        });

                    this.classList.add('active');

                    document
                        .getElementById('tab-' + target)
                        .classList.add('active');

                });

            });

        // Activar primera tab
        var firstTab = document.querySelector('.we-admin-tab');

        if (firstTab) {
            firstTab.click();
        }


        // Media uploader: imagen destacada

        function weOpenMediaUploader() {

            var frame = wp.media({
                title: 'Seleccionar imagen del tour',

                button: {
                    text: 'Usar esta imagen'
                },

                multiple: false
            });

            frame.on('select', function () {

                var att = frame
                    .state()
                    .get('selection')
                    .first()
                    .toJSON();

                document.getElementById('we_featured_img').value = att.id;

                document.getElementById('we-preview-img').src = att.url;

                document.getElementById('we-image-preview').style.display = 'block';

                document.getElementById('we-upload-btn').style.display = 'none';

            });

            frame.open();
        }


        function weRemoveImage() {

            document.getElementById('we_featured_img').value = '';

            document.getElementById('we-image-preview').style.display = 'none';

            document.getElementById('we-upload-btn').style.display = 'inline-block';

        }


        // Media uploader: repeaters

        function weEnsureRepeaterPreviewImage(wrap) {

            var preview = wrap.querySelector('.we-image-preview');

            if (!preview) {
                return null;
            }

            var img = preview.querySelector('img');

            if (!img) {
                img = document.createElement('img');
                img.alt = '';
                img.style.maxWidth = '200px';
                preview.insertBefore(img, preview.firstChild);
            }

            return img;
        }


        function weOpenRepeaterUploader(btn) {

            var wrap = btn.closest('.we-image-uploader');

            var frame = wp.media({
                title: 'Seleccionar imagen',

                button: {
                    text: 'Usar esta imagen'
                },

                multiple: false
            });

            frame.on('select', function () {

                var att = frame
                    .state()
                    .get('selection')
                    .first()
                    .toJSON();

                var previewImg = weEnsureRepeaterPreviewImage(wrap);

                if (previewImg) {
                    previewImg.src = att.url;
                }

                wrap.querySelector('input[type=hidden]').value = att.url;

                wrap.querySelector('.we-image-preview').style.display = 'block';

                btn.style.display = 'none';

            });

            frame.open();
        }


        function weRemoveRepeaterImage(btn) {

            var wrap = btn.closest('.we-image-uploader');

            var previewImg = weEnsureRepeaterPreviewImage(wrap);

            if (previewImg) {
                previewImg.src = '';
            }

            wrap.querySelector('input[type=hidden]').value = '';

            wrap.querySelector('.we-image-preview').style.display = 'none';

            wrap.querySelector('.we-upload-btn').style.display = 'inline-flex';

        }


        // Repeaters simples

        function weGetItineraryEditorSettings() {

            return {
                tinymce: {
                    wpautop: true,
                    toolbar1: 'bold,italic,bullist,numlist,link,unlink,undo,redo'
                },
                quicktags: true,
                mediaButtons: false
            };
        }

        function weInitItineraryEditors(root) {

            if (!window.wp || !wp.editor || typeof wp.editor.initialize !== 'function') {
                return;
            }

            var scope = root || document;
            var textareas = scope.matches && scope.matches('.we-itinerary-editor')
                ? [scope]
                : scope.querySelectorAll('.we-itinerary-editor');

            Array.prototype.forEach.call(textareas, function (textarea) {
                if (!textarea || !textarea.id || textarea.closest('template')) {
                    return;
                }

                if (document.getElementById('wp-' + textarea.id + '-wrap')) {
                    return;
                }

                wp.editor.initialize(textarea.id, weGetItineraryEditorSettings());
            });
        }

        function weRemoveRepeaterRow(btn) {

            var row = btn ? btn.closest('.we-repeater-row') : null;

            if (!row) {
                return;
            }

            row.querySelectorAll('.we-itinerary-editor').forEach(function (textarea) {
                if (textarea.id && window.wp && wp.editor && typeof wp.editor.remove === 'function') {
                    wp.editor.remove(textarea.id);
                }
            });

            row.remove();
        }

        function weAddRepeaterSimple(btn, templateId) {

            var repeater = btn.closest('.we-repeater');

            var tpl = document
                .getElementById('we-tpl-' + templateId)
                .innerHTML;

            var idx = repeater
                .querySelectorAll('.we-repeater-row')
                .length;

            tpl = tpl.replace(
                /__IDX__/g,
                idx
            );

            var wrapper = document.createElement('div');

            wrapper.innerHTML = tpl;

            repeater
                .querySelector('.we-repeater-items')
                .appendChild(wrapper.firstElementChild);
        }


        // Repeaters complejos

        function weAddRepeaterComplex(btn, templateId) {

            var repeater = btn.closest('.we-repeater');

            var tpl = document
                .getElementById('we-tpl-' + templateId)
                .innerHTML;

            var idx = repeater
                .querySelectorAll('.we-repeater-row')
                .length;

            tpl = tpl.replace(
                /__IDX__/g,
                idx
            );

            var wrapper = document.createElement('div');

            wrapper.innerHTML = tpl;

            var newRow = wrapper.firstElementChild;

            repeater
                .querySelector('.we-repeater-items')
                .appendChild(newRow);

            // Re-index image uploaders dentro del nuevo row
            newRow
                .querySelectorAll('.we-image-uploader')
                .forEach(function (uploader) {

                    var base = uploader.dataset.field;

                    if (base) {

                        var newName = base.replace(
                            /__IDX__/g,
                            idx
                        );

                        uploader.dataset.field = newName;

                        uploader
                            .querySelector('input[type=hidden]')
                            .name = newName;
                    }

                });

            if (templateId === 'itinerary') {
                weInitItineraryEditors(newRow);
            }
        }

        // Validacion accesible del link de Google Maps

        function weAnalyzeGoogleMapUrl(value) {

            var text = (value || '').trim();

            if (!text) {
                return {
                    level: 'empty',
                    message: ''
                };
            }

            try {
                var parsed = new URL(text);
                var host = parsed.hostname.toLowerCase();
                var isGoogleHost = host.indexOf('google.') !== -1 || host === 'maps.app.goo.gl' || host === 'g.co' || host.slice(-7) === '.goo.gl';

                if (!isGoogleHost) {
                    return {
                        level: 'error',
                        message: 'Este enlace no parece de Google Maps. Usa un enlace de google.com/maps.'
                    };
                }

                if (host === 'maps.app.goo.gl' || host === 'g.co' || host.slice(-7) === '.goo.gl') {
                    return {
                        level: 'error',
                        message: 'Los enlaces cortos suelen bloquearse en iframe. Usa Compartir -> Insertar un mapa y pega la URL src (maps/embed?pb=...).'
                    };
                }

                if (parsed.pathname.indexOf('/maps/embed') !== -1 && parsed.searchParams.get('pb')) {
                    return {
                        level: 'success',
                        message: 'Enlace correcto para embeber: debería mostrar el mapa dentro del modal.'
                    };
                }

                if (parsed.pathname.indexOf('/maps/dir') !== -1) {
                    return {
                        level: 'warning',
                        message: 'Este enlace de ruta puede bloquearse en algunos navegadores. Si ves mapa gris, usa la URL de Insertar un mapa (maps/embed?pb=...).'
                    };
                }

                if (parsed.searchParams.get('output') === 'embed') {
                    return {
                        level: 'success',
                        message: 'Enlace con output=embed detectado.'
                    };
                }

                return {
                    level: 'warning',
                    message: 'Enlace válido, pero para mayor compatibilidad usa Compartir -> Insertar un mapa (maps/embed?pb=...).'
                };
            } catch (error) {
                return {
                    level: 'error',
                    message: 'Formato de URL no válido. Pega una URL completa de Google Maps.'
                };
            }
        }


        function weRenderGoogleMapNotice() {

            var mapInput = document.getElementById('tour_map_custom_url');
            var notice = document.getElementById('we-map-url-notice');

            if (!mapInput || !notice) {
                return;
            }

            var result = weAnalyzeGoogleMapUrl(mapInput.value);

            if (result.level === 'empty') {
                notice.textContent = '';
                notice.style.display = 'none';
                return;
            }

            notice.textContent = result.message;
            notice.style.display = 'block';

            if (result.level === 'success') {
                notice.style.color = '#0f5132';
                notice.style.background = '#d1e7dd';
                notice.style.border = '1px solid #badbcc';
            } else if (result.level === 'warning') {
                notice.style.color = '#664d03';
                notice.style.background = '#fff3cd';
                notice.style.border = '1px solid #ffecb5';
            } else {
                notice.style.color = '#842029';
                notice.style.background = '#f8d7da';
                notice.style.border = '1px solid #f5c2c7';
            }

            notice.style.borderRadius = '4px';
            notice.style.padding = '8px 10px';
        }


        function weInitGoogleMapUrlValidation() {

            var mapInput = document.getElementById('tour_map_custom_url');
            var adminForm = document.querySelector('form.we-admin-form');

            if (!mapInput || !adminForm) {
                return;
            }

            mapInput.addEventListener('input', weRenderGoogleMapNotice);
            mapInput.addEventListener('blur', weRenderGoogleMapNotice);
            weRenderGoogleMapNotice();

            adminForm.addEventListener('submit', function (event) {
                if (window.tinyMCE && typeof window.tinyMCE.triggerSave === 'function') {
                    window.tinyMCE.triggerSave();
                }

                var result = weAnalyzeGoogleMapUrl(mapInput.value);

                if (result.level === 'error') {
                    event.preventDefault();
                    weRenderGoogleMapNotice();
                    mapInput.focus();
                }
            });
        }

        weInitGoogleMapUrlValidation();
        weInitItineraryEditors(document);

    </script>

    <?php
}
