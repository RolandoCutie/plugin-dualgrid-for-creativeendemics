<?php
// === includes/form-handler.php ===
// Procesa el formulario del modal y provee funciones helper

add_action('init', function () {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['we_modal_submit']))
        return;

    if (!wp_verify_nonce($_POST['we_modal_nonce'] ?? '', 'we_modal_form')) {
        wp_die('Verificación de seguridad fallida. Intenta de nuevo.');
    }

    $tour_id = intval($_POST['we_modal_tour_id'] ?? 0);
    $tour_name = sanitize_text_field($_POST['we_modal_tour_name'] ?? '');
    $nombre = sanitize_text_field($_POST['we_nombre'] ?? '');
    $email = sanitize_email($_POST['we_email'] ?? '');
    $telefono = sanitize_text_field($_POST['we_telefono'] ?? '');
    $fecha = sanitize_text_field($_POST['we_fecha'] ?? '');
    $personas = sanitize_text_field($_POST['we_personas'] ?? '');
    $notas = sanitize_textarea_field($_POST['we_notas'] ?? '');
    $redirect = !empty($_POST['we_redirect']) ? esc_url_raw($_POST['we_redirect']) : home_url();

    $to = get_option('admin_email');
    $subject = 'Solicitud de información - ' . ($tour_name ?: 'Tour');
    $body = "Nueva solicitud de información\n\n";
    $body .= "Tour: " . ($tour_name ?: 'N/A') . " (ID: $tour_id)\n";
    $body .= "Nombre: $nombre\n";
    $body .= "Email: $email\n";
    $body .= "Teléfono: $telefono\n";
    $body .= "Fecha preferida: $fecha\n";
    $body .= "Personas: $personas\n";
    $body .= "Notas: $notas\n";
    $headers = ['Content-Type: text/plain; charset=UTF-8', "Reply-To: $email"];

    $sent = wp_mail($to, $subject, $body, $headers);

    $redirect = add_query_arg('we_request', $sent ? 'success' : 'error', $redirect);
    wp_safe_redirect($redirect);
    exit;
});

function we_show_request_notice()
{
    if (!isset($_GET['we_request']))
        return;
    $is_success = $_GET['we_request'] === 'success';
    $class = $is_success ? 'we-toast-success' : 'we-toast-error';
    $msg = $is_success ? 'Thank you. We received your request and will contact you soon.' : 'There was an error sending your request. Please try again.';
    echo '<div class="we-toast ' . esc_attr($class) . '" id="we-toast-msg">';
    echo esc_html($msg);
    echo '<button type="button" onclick="this.parentElement.remove()">×</button>';
    echo '</div>';
    echo '<script>setTimeout(function(){ var t=document.getElementById("we-toast-msg"); if(t) t.remove(); }, 6000);</script>';
}


// === includes/form-handler.php ===
// Renderiza el modal con formulario CF7 de solicitud de información

function we_render_tour_modal()
{
    $cf7_id = '2112133'; // ← Tu ID de CF7 de tours

    if (!function_exists('wpcf7_enqueue_scripts')) {
        echo '<p style="color:red; padding:20px;"><strong>ERROR:</strong> Contact Form 7 is not active.</p>';
        return;
    }

    wpcf7_enqueue_scripts();
    wpcf7_enqueue_styles();

    $cf7_shortcode = '[contact-form-7 id="' . $cf7_id . '" title="Tours"]';
    ?>
    <div id="we-tour-modal" class="we-modal" style="display:none;" aria-hidden="true">
        <div class="we-modal__overlay" onclick="weCloseModal()"></div>
        <div class="we-modal__content">
            <button type="button" class="we-modal__close" onclick="weCloseModal()" aria-label="Close">×</button>
            <h3 class="we-modal__title">Request Information</h3>
            <p class="we-modal__subtitle">Complete the form and we will send you the details for this program.</p>

            <?php
            $form_html = do_shortcode($cf7_shortcode);
            if (empty($form_html) || strpos($form_html, 'No se ha encontrado') !== false) {
                echo '<p style="color:red; padding:10px;"><strong>ERROR:</strong> CF7 form not found. ID used: <code>' . esc_html($cf7_id) . '</code></p>';
            } else {
                echo $form_html;
            }
            ?>
        </div>
    </div>
    <?php
}
