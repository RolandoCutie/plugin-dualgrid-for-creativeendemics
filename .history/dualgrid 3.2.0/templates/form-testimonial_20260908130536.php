<?php
/**
 * Template: Formulario de Testimonio
 * Ubicación: dualgrid/templates/form-testimonial.php
 */

$token = sanitize_text_field($_GET['token'] ?? '');
if (!$token) {
    wp_die('Unauthorized access. An invitation token is required.');
}

// Verificar token
$query = new WP_Query([
    'post_type' => 'testimonial',
    'posts_per_page' => 1,
    'post_status' => 'any',
    'meta_query' => [
        ['key' => '_tp_token', 'value' => $token, 'compare' => '=']
    ]
]);

if (!$query->have_posts()) {
    wp_die('Invalid or expired token. Please contact the site administrator.');
}

$post = $query->posts[0];
$ya_enviado = !empty($post->post_content) && $post->post_status !== 'draft';
wp_reset_postdata();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share your experience</title>
    <?php wp_head(); ?>
    <style>
        body {
            font-family: 'Fraunces', 'Georgia', serif;
            background: #1a3b2e;
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .tp-form-box {
            background: #0f2a1f;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 40px;
            max-width: 480px;
            width: 100%;
        }

        .tp-form-box h1 {
            font-family: 'Jost', 'Fraunces', 'Georgia', serif;
            font-size: 24px;
            margin: 0 0 8px;
            color: #fff;
            font-weight: 900;
        }

        .tp-form-box p {
            font-family: 'Fraunces', 'Georgia', serif;
            color: #8fbfa3;
            margin: 0 0 24px;
            font-size: 14px;
            line-height: 1.5;
        }

        .tp-field {
            margin-bottom: 16px;
        }

        .tp-field label {
            font-family: 'Satoshi', 'Helvetica Neue', sans-serif;
            display: block;
            font-size: 13px;
            color: #8fbfa3;
            margin-bottom: 6px;
            font-weight: 500;
        }

        .tp-field input,
        .tp-field textarea {
            font-family: 'Fraunces', 'Georgia', serif;
            width: 100%;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 10px 14px;
            color: #fff;
            font-size: 15px;
            box-sizing: border-box;
        }

        .tp-field input:focus,
        .tp-field textarea:focus {
            outline: none;
            border-color: #7dd3a8;
        }

        .tp-field textarea {
            resize: vertical;
            min-height: 100px;
        }

        .tp-field input[type="file"] {
            padding: 8px;
            color: #8fbfa3;
        }

        .tp-stars-select {
            display: flex;
            gap: 8px;
            font-size: 28px;
            cursor: pointer;
        }

        .tp-stars-select span {
            color: rgba(255, 255, 255, 0.2);
            transition: color 0.2s;
        }

        .tp-stars-select span.active {
            color: #e8b86d;
        }

        .tp-submit {
            font-family: 'Satoshi', 'Helvetica Neue', sans-serif;
            width: 100%;
            background: #7dd3a8;
            color: #1a3b2e;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .tp-submit:hover {
            background: #6bc497;
        }

        .tp-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .tp-success {
            text-align: center;
            padding: 20px 0;
        }

        .tp-success h2 {
            font-family: 'Jost', 'Fraunces', 'Georgia', serif;
            color: #7dd3a8;
            margin-bottom: 8px;
            font-weight: 900;
        }
    </style>
</head>

<body>

    <?php if ($ya_enviado): ?>
        <div class="tp-form-box tp-success">
            <h2>✅ Thank you!</h2>
            <p>Your testimonial has already been received and is pending approval.</p>
        </div>
    <?php else: ?>
        <div class="tp-form-box">
            <h1>Share your experience</h1>
            <p>Your feedback helps us keep creating unforgettable journeys. Everything you share will be moderated before
                publication.</p>

            <form id="tp-form" enctype="multipart/form-data">
                <input type="hidden" name="token" value="<?php echo esc_attr($token); ?>">

                <div class="tp-field">
                    <label>Full name</label>
                    <input type="text" name="nombre" required placeholder="Example: Elena Marchetti">
                </div>

                <div class="tp-field">
                    <label>Country / City</label>
                    <input type="text" name="ubicacion" required placeholder="Example: Italy">
                </div>

                <div class="tp-field">
                    <label>Your experience</label>
                    <textarea name="testimonio" required placeholder="Tell us about your trip..."></textarea>
                </div>

                <div class="tp-field">
                    <label>Your photo (optional but recommended)</label>
                    <input type="file" name="foto" accept="image/*">
                </div>

                <div class="tp-field">
                    <label>Rating</label>
                    <div class="tp-stars-select" id="star-rating">
                        <span data-val="1">★</span>
                        <span data-val="2">★</span>
                        <span data-val="3">★</span>
                        <span data-val="4">★</span>
                        <span data-val="5">★</span>
                    </div>
                    <input type="hidden" name="valoracion" id="rating-input" value="5">
                </div>

                <button type="submit" class="tp-submit">Send testimonial</button>
            </form>

            <div id="tp-msg" style="margin-top:16px; text-align:center; font-size:14px;"></div>
        </div>
    <?php endif; ?>

    <script>
        // Star rating interactivo
        const stars = document.querySelectorAll('#star-rating span');
        const ratingInput = document.getElementById('rating-input');
        stars.forEach((s, i) => {
            s.addEventListener('click', () => {
                const val = parseInt(s.dataset.val);
                ratingInput.value = val;
                stars.forEach((st, idx) => st.classList.toggle('active', idx < val));
            });
            if (i < 5) s.classList.add('active'); // default 5
        });

        // Envío AJAX
        document.getElementById('tp-form')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('.tp-submit');
            btn.disabled = true;
            btn.textContent = 'Sending...';

            const formData = new FormData(e.target);
            try {
                const res = await fetch('<?php echo esc_url_raw(get_rest_url(null, 'testimonials/v1/enviar')); ?>', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    e.target.innerHTML = '<div style="text-align:center;padding:20px 0;"><h2 style="color:#7dd3a8;margin-bottom:8px;">✅ Thank you!</h2><p style="color:#8fbfa3;">' + data.message + '</p></div>';
                } else {
                    throw new Error(data.message || 'Error sending testimonial');
                }
            } catch (err) {
                document.getElementById('tp-msg').textContent = '❌ ' + err.message;
                document.getElementById('tp-msg').style.color = '#e07a5f';
                btn.disabled = false;
                btn.textContent = 'Send testimonial';
            }
        });
    </script>
    <?php wp_footer(); ?>
</body>

</html>
