<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function () {
    register_post_type('dg_cw_inquiry', [
        'labels' => [
            'name' => __('Consultas', 'dualgrid'),
            'singular_name' => __('Consulta', 'dualgrid'),
            'menu_name' => __('Consultas', 'dualgrid'),
            'all_items' => __('Todas las consultas', 'dualgrid'),
            'view_item' => __('Ver consulta', 'dualgrid'),
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'dualgrid-creativewilds',
        'supports' => ['title', 'editor'],
        'menu_position' => null,
        'capability_type' => 'post',
        'map_meta_cap' => true,
    ]);
});

add_filter('manage_dg_cw_inquiry_posts_columns', function ($columns) {
    return [
        'cb' => $columns['cb'] ?? '<input type="checkbox" />',
        'title' => __('Consulta', 'dualgrid'),
        'service' => __('Servicio', 'dualgrid'),
        'email' => __('Email', 'dualgrid'),
        'language' => __('Idioma', 'dualgrid'),
        'mail_status' => __('Correo', 'dualgrid'),
        'date' => $columns['date'] ?? __('Date', 'dualgrid'),
    ];
});

add_action('manage_dg_cw_inquiry_posts_custom_column', function ($column, $post_id) {
    switch ($column) {
        case 'service':
            echo esc_html((string) get_post_meta($post_id, 'service_label', true));
            break;
        case 'email':
            echo esc_html((string) get_post_meta($post_id, 'email', true));
            break;
        case 'language':
            echo esc_html(strtoupper((string) get_post_meta($post_id, 'language', true)));
            break;
        case 'mail_status':
            echo esc_html((string) get_post_meta($post_id, 'mail_status', true));
            break;
    }
}, 10, 2);

add_action('wp_ajax_dg_cw_submit_contact', 'dg_cw_handle_contact_submission');
add_action('wp_ajax_nopriv_dg_cw_submit_contact', 'dg_cw_handle_contact_submission');

if (!function_exists('dg_cw_handle_contact_submission')) {
    function dg_cw_handle_contact_submission()
    {
        if (!check_ajax_referer('dg_cw_contact_submit', 'nonce', false)) {
            wp_send_json_error(['message' => __('Security validation failed.', 'dualgrid')], 403);
        }

        $settings = dg_cw_get_settings();
        $lang = dg_cw_resolve_lang($_POST['lang'] ?? '');
        $services = dg_cw_get_active_services($settings);

        $name = sanitize_text_field(wp_unslash((string) ($_POST['name'] ?? '')));
        $email = sanitize_email(wp_unslash((string) ($_POST['email'] ?? '')));
        $phone = sanitize_text_field(wp_unslash((string) ($_POST['phone'] ?? '')));
        $service_slug = sanitize_title(wp_unslash((string) ($_POST['service'] ?? '')));
        $message = sanitize_textarea_field(wp_unslash((string) ($_POST['message'] ?? '')));
        $page_url = esc_url_raw((string) ($_POST['page_url'] ?? ''));

        if ($name === '' || !is_email($email) || $message === '') {
            wp_send_json_error(['message' => dg_cw_get_translated_value($settings['contact'], 'error_message', $lang)], 422);
        }

        $service_label = '';
        foreach ($services as $service) {
            if (($service['slug'] ?? '') === $service_slug) {
                $service_label = dg_cw_get_translated_value($service, 'title', $lang);
                break;
            }
        }

        if ($service_slug !== '' && $service_slug !== 'something-else' && $service_label === '') {
            wp_send_json_error(['message' => dg_cw_get_translated_value($settings['contact'], 'error_message', $lang)], 422);
        }

        if ($service_label === '' && $service_slug === 'something-else') {
            $service_label = $lang === 'es' ? 'Algo m' . "\xC3\xA1" . 's' : 'Something else';
        }

        $post_id = wp_insert_post([
            'post_type' => 'dg_cw_inquiry',
            'post_status' => 'publish',
            'post_title' => sprintf('%s — %s', $name, current_time('mysql')),
            'post_content' => $message,
        ], true);

        if (is_wp_error($post_id) || !$post_id) {
            wp_send_json_error(['message' => dg_cw_get_translated_value($settings['contact'], 'error_message', $lang)], 500);
        }

        update_post_meta($post_id, 'name', $name);
        update_post_meta($post_id, 'email', $email);
        update_post_meta($post_id, 'phone', $phone);
        update_post_meta($post_id, 'service_slug', $service_slug);
        update_post_meta($post_id, 'service_label', $service_label);
        update_post_meta($post_id, 'language', $lang);
        update_post_meta($post_id, 'page_url', $page_url);

        $recipient = $settings['contact']['recipient_email'] ?: sanitize_email(get_option('admin_email'));
        $subject = sprintf(
            '[%s] %s',
            $settings['general']['brand_name'],
            $service_label !== '' ? $service_label : ($lang === 'es' ? 'Nueva consulta' : 'New inquiry')
        );

        $message_lines = [
            ($lang === 'es' ? 'Nombre' : 'Name') . ': ' . $name,
            'Email: ' . $email,
            ($lang === 'es' ? 'Tel' . "\xC3\xA9" . 'fono' : 'Phone') . ': ' . ($phone !== '' ? $phone : '-'),
            ($lang === 'es' ? 'Servicio' : 'Service') . ': ' . ($service_label !== '' ? $service_label : '-'),
            ($lang === 'es' ? 'Idioma' : 'Language') . ': ' . strtoupper($lang),
            'URL: ' . ($page_url !== '' ? $page_url : '-'),
            '',
            ($lang === 'es' ? 'Mensaje' : 'Message') . ':',
            $message,
        ];

        $headers = [
            'Content-Type: text/plain; charset=UTF-8',
            sprintf('Reply-To: %s <%s>', $name, $email),
        ];

        $mail_sent = wp_mail($recipient, $subject, implode("\n", $message_lines), $headers);
        update_post_meta($post_id, 'mail_status', $mail_sent ? 'sent' : 'failed');

        if (!$mail_sent) {
            wp_send_json_error(['message' => dg_cw_get_translated_value($settings['contact'], 'error_message', $lang)], 500);
        }

        wp_send_json_success([
            'title' => dg_cw_get_translated_value($settings['contact'], 'success_title', $lang),
            'message' => dg_cw_get_translated_value($settings['contact'], 'success_message', $lang),
            'resetLabel' => dg_cw_get_translated_value($settings['contact'], 'success_reset', $lang),
        ]);
    }
}

