<?php

// === includes/ajax-tour-details.php ===
// Endpoint AJAX: we_get_tour_details
//
// Agrega este archivo a tu cpt-tours.php o functions.php


// ═══════════════════════════════════════════════════════════════════════════════
// 1. LOCALIZAR SCRIPT
//    Pasar URL AJAX y nonce al JS del modal
// ═══════════════════════════════════════════════════════════════════════════════

add_action('wp_enqueue_scripts', function () {

    if (!is_admin()) {

        wp_localize_script(
            'dualgrid-tour-modal-detail',
            'geTourModal',
            [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('we_tour_modal_nonce'),
            ]
        );
    }
});


// Si aún no registras el script del modal, hazlo así:
//
// wp_enqueue_script(
//     'tour-modal',
//     plugin_dir_url(__FILE__) . '../js/tour-modal.js',
//     [],
//     '1.0',
//     true
// );


// ═══════════════════════════════════════════════════════════════════════════════
// 2. PUNTO FINAL AJAX
//    Obtener detalles completos del tour
// ═══════════════════════════════════════════════════════════════════════════════

add_action(
    'wp_ajax_we_get_tour_details',
    'we_ajax_get_tour_details'
);

add_action(
    'wp_ajax_nopriv_we_get_tour_details',
    'we_ajax_get_tour_details'
);


function we_ajax_get_tour_details()
{
    // ═══════════════════════════════════════════════════════════════════════════
    // VERIFICAR TOKEN DE SEGURIDAD
    // ═══════════════════════════════════════════════════════════════════════════

    if (
        !wp_verify_nonce(
            $_POST['nonce'] ?? '',
            'we_tour_modal_nonce'
        )
    ) {
        wp_send_json_error('Nonce inválido');
    }


    // ═══════════════════════════════════════════════════════════════════════════
    // OBTENER IDENTIFICADOR DEL TOUR
    // ═══════════════════════════════════════════════════════════════════════════

    $tour_id = intval($_POST['tour_id'] ?? 0);

    if (!$tour_id) {
        wp_send_json_error('ID de tour no válido');
    }


    // ═══════════════════════════════════════════════════════════════════════════
    // VALIDAR EXISTENCIA Y TIPO DE TOUR
    // ═══════════════════════════════════════════════════════════════════════════

    $post = get_post($tour_id);

    if (
        !$post ||
        $post->post_type !== 'tour'
    ) {
        wp_send_json_error('Tour no encontrado');
    }


    // ═══════════════════════════════════════════════════════════════════════════
    // FUNCIONES AUXILIARES DE FORMATEO
    // ═══════════════════════════════════════════════════════════════════════════

    /**
     * Obtener un campo meta del tour.
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    $tmeta = function ($key, $default = '') use ($tour_id) {
        $val = get_post_meta(
            $tour_id,
            $key,
            true
        );

        return (
            $val !== '' &&
            $val !== false &&
            $val !== null
        )
            ? $val
            : $default;
    };


    /**
     * Obtener un campo meta que debe ser un array.
     *
     * @param string $key
     * @return array
     */
    $tmeta_arr = function ($key) use ($tour_id) {
        $val = get_post_meta(
            $tour_id,
            $key,
            true
        );

        return is_array($val)
            ? $val
            : [];
    };

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

            if (preg_match('/physical\s+demands/i', $line)) {
                break;
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

    $default_when_to_travel = $read_doc_by_hints(['When', 'Travel']);
    $default_rules = $read_doc_by_hints(['Rules', 'Terms']);
    $default_includes_doc = $read_doc_by_hints(['What', 'Included']);
    $default_faq_doc = $read_doc_by_hints(['Frequently', 'Asked', 'Questions']);

    $default_include_sets = $parse_includes_doc($default_includes_doc);
    $default_faq = $parse_faq_doc($default_faq_doc);


    // ═══════════════════════════════════════════════════════════════════════════
    // CONSTRUIR RESPUESTA
    //
    // La estructura debe mantenerse exactamente como la espera
    // tour-modal.js
    // ═══════════════════════════════════════════════════════════════════════════

    $data = [

        // ───────────────────────────────────────────────────────────────────────
        // HEADER
        // ───────────────────────────────────────────────────────────────────────

        'name' => $post->post_title,

        'permalink' => get_permalink($tour_id),

        'coords' => $tmeta(
            'tour_coords'
        ),

        'short_description' => $tmeta(
            'tour_short_description'
        ),


        // ───────────────────────────────────────────────────────────────────────
        // ATRIBUTOS DEL TOUR
        // ───────────────────────────────────────────────────────────────────────

        'city' => $tmeta(
            'tour_city'
        ),

        'duration' => $tmeta(
            'tour_duration'
        ),

        'max_people' => $tmeta(
            'tour_max_people'
        ),

        'difficulty' => $tmeta(
            'tour_difficulty'
        ),

        'price' => $tmeta(
            'tour_price'
        ),

        'price_secondary' => $tmeta(
            'tour_price_secondary'
        ),

        'price_secondary_label' => $tmeta(
            'tour_price_secondary_label',
            'Single supplement'
        ),

        'next_date' => $tmeta(
            'tour_next_date'
        ),

        'tour_type' => $tmeta(
            'tour_type'
        ),

        'pace' => $tmeta(
            'tour_pace'
        ),

        'language' => $tmeta(
            'tour_language'
        ),

        'transport' => $tmeta(
            'tour_transport'
        ),

        'accommodation' => $tmeta(
            'tour_accommodation'
        ),

        'dates' => $tmeta(
            'tour_dates'
        ),

        'when_to_travel' => $tmeta(
            'tour_when_to_travel'
        ),

        'can_americans_join' => $tmeta(
            'tour_can_americans_join'
        ),

        'pricing' => $tmeta_arr(
            'tour_pricing'
        ),

        'map_custom_url' => $tmeta(
            'tour_map_custom_url'
        ),


        // ───────────────────────────────────────────────────────────────────────
        // GALERÍA
        // ───────────────────────────────────────────────────────────────────────

        'main_image' => get_the_post_thumbnail_url(
            $tour_id,
            'large'
        ) ?: '',

        'gallery' => $tmeta_arr(
            'tour_gallery'
        ),


        // ───────────────────────────────────────────────────────────────────────
        // ABOUT
        // ───────────────────────────────────────────────────────────────────────

        'about' => $tmeta_arr(
            'tour_about'
        ),


        // ───────────────────────────────────────────────────────────────────────
        // FAUNA / WILDLIFE
        // ───────────────────────────────────────────────────────────────────────

        'wildlife_label' => $tmeta(
            'tour_wildlife_label',
            'Wildlife you can spot'
        ),

        'wildlife' => $tmeta_arr(
            'tour_wildlife'
        ),


        // ───────────────────────────────────────────────────────────────────────
        // ORGANIZADOR
        // ───────────────────────────────────────────────────────────────────────

        'organizer' => [

            'name' => $tmeta(
                'tour_organizer_name'
            ),

            'role' => $tmeta(
                'tour_organizer_role'
            ),

            'logo' => $tmeta(
                'tour_organizer_logo'
            ),
        ],


        // ───────────────────────────────────────────────────────────────────────
        // GUÍAS
        // ───────────────────────────────────────────────────────────────────────

        'guides' => $tmeta_arr(
            'tour_guides'
        ),


        // ───────────────────────────────────────────────────────────────────────
        // ITINERARIO
        // ───────────────────────────────────────────────────────────────────────

        'itinerary' => $tmeta_arr(
            'tour_itinerary'
        ),


        // ───────────────────────────────────────────────────────────────────────
        // UBICACIONES
        //
        // Se utilizan tanto para la lista como para el mapa.
        // ───────────────────────────────────────────────────────────────────────

        'locations' => $tmeta_arr(
            'tour_locations'
        ),


        // ───────────────────────────────────────────────────────────────────────
        // DISPONIBILIDAD
        // ───────────────────────────────────────────────────────────────────────

        'availability' => $tmeta_arr(
            'tour_availability'
        ),


        // ───────────────────────────────────────────────────────────────────────
        // INCLUIDO / NO INCLUIDO
        // ───────────────────────────────────────────────────────────────────────

        'includes' => $tmeta_arr(
            'tour_includes'
        ),

        'excludes' => $tmeta_arr(
            'tour_excludes'
        ),


        // ───────────────────────────────────────────────────────────────────────
        // POLÍTICAS Y FAQ
        // ───────────────────────────────────────────────────────────────────────

        'policies' => $tmeta(
            'tour_policies'
        ),

        'terms_conditions' => $tmeta(
            'tour_terms_conditions'
        ),

        'physical_demands' => $tmeta(
            'tour_physical_demands'
        ),

        'faq' => $tmeta_arr(
            'tour_faq'
        ),

        'default_when_to_travel' => $default_when_to_travel,

        'default_rules' => $default_rules,

        'default_includes' => $default_include_sets['includes'],

        'default_excludes' => $default_include_sets['excludes'],

        'default_faq' => $default_faq,


        // ───────────────────────────────────────────────────────────────────────
        // COORDENADAS PARA EL MAPA
        // ───────────────────────────────────────────────────────────────────────

        'coords_lat' => $tmeta(
            'tour_coords_lat',
            '9.58'
        ),

        'coords_lng' => $tmeta(
            'tour_coords_lng',
            '-83.81'
        ),
    ];

    $data['duration'] = dg_translate_tour_value($data['duration'], 'duration');
    $data['max_people'] = dg_translate_tour_value($data['max_people'], 'group');
    $data['difficulty'] = dg_translate_tour_value($data['difficulty'], 'difficulty');
    $data['price'] = dg_format_price($data['price']);
    $data['price_secondary'] = dg_format_price($data['price_secondary']);
    $data['next_date'] = dg_translate_tour_value($data['next_date'], 'date');
    $data['tour_type'] = dg_translate_tour_value($data['tour_type'], 'tour_type');
    $data['pace'] = dg_translate_tour_value($data['pace'], 'pace');
    $data['language'] = dg_translate_tour_value($data['language'], 'language');
    $data['city'] = dg_translate_tour_value($data['city'], 'city');
    $data['accommodation'] = dg_translate_tour_value($data['accommodation'], 'accommodation');
    $data['dates'] = dg_translate_tour_value($data['dates'], 'dates');

    if (empty($data['terms_conditions']) && !empty($data['policies'])) {
        $data['terms_conditions'] = $data['policies'];
    }

    foreach ($data['guides'] as &$guide) {
        if (!is_array($guide)) {
            continue;
        }

        if (!empty($guide['role'])) {
            $guide['role'] = dg_translate_tour_value($guide['role'], 'guide_role');
        }

        if (!empty($guide['language'])) {
            $guide['language'] = dg_translate_tour_value($guide['language'], 'language');
        }
    }
    unset($guide);

    foreach ($data['availability'] as &$availability) {
        if (!is_array($availability)) {
            continue;
        }

        if (!empty($availability['date'])) {
            $availability['date'] = dg_translate_tour_value($availability['date'], 'date');
        }

        if (!empty($availability['status'])) {
            $availability['status'] = dg_translate_tour_value($availability['status'], 'status');
        }
    }
    unset($availability);

    $normalized_pricing = [];
    foreach ($data['pricing'] as $pricing_row) {
        if (!is_array($pricing_row)) {
            continue;
        }

        $label = trim((string) ($pricing_row['label'] ?? ''));
        $amount_raw = trim((string) ($pricing_row['amount'] ?? ''));
        $note = trim((string) ($pricing_row['note'] ?? ''));

        $amount = $amount_raw !== '' ? dg_format_price($amount_raw) : '';

        if ($label === '' && $amount === '' && $note === '') {
            continue;
        }

        $normalized_pricing[] = [
            'label' => $label,
            'amount' => $amount,
            'note' => $note,
        ];
    }
    $data['pricing'] = $normalized_pricing;


    // ═══════════════════════════════════════════════════════════════════════════
    // ASEGURAR QUE LA GALERÍA TENGA LA IMAGEN PRINCIPAL
    // ═══════════════════════════════════════════════════════════════════════════

    if (
        empty($data['gallery']) &&
        !empty($data['main_image'])
    ) {
        $data['gallery'] = [
            $data['main_image']
        ];
    }


    // ═══════════════════════════════════════════════════════════════════════════
    // ASEGURAR IMAGEN PARA ELEMENTOS DE WILDLIFE
    //
    // Si una especie no tiene imagen propia, utiliza la imagen principal
    // del tour como fallback.
    // ═══════════════════════════════════════════════════════════════════════════

    foreach ($data['wildlife'] as &$w) {

        if (
            empty($w['image']) &&
            !empty($data['main_image'])
        ) {
            $w['image'] = $data['main_image'];
        }
    }

    unset($w);


    // ═══════════════════════════════════════════════════════════════════════════
    // RESPUESTA JSON
    // ═══════════════════════════════════════════════════════════════════════════

    wp_send_json_success(
        $data
    );
}
