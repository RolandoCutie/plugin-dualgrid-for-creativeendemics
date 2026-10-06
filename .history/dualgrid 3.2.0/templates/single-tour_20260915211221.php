<?php
// === templates/single-tour.php ===
// Single tour page

get_header();

$pid = get_the_ID();
$subtitle = '';
$pdf = '';
$subtitle = get_post_meta($pid, '_we_subtitle', true);
$price = dg_format_price(get_post_meta($pid, 'tour_price', true));
$price_secondary = dg_format_price(get_post_meta($pid, 'tour_price_secondary', true));
$price_secondary_label = get_post_meta($pid, 'tour_price_secondary_label', true) ?: 'Single supplement';
$duration = dg_translate_tour_value(get_post_meta($pid, 'tour_duration', true) ?: '5-14 days', 'duration');
$group = dg_translate_tour_value(get_post_meta($pid, 'tour_max_people', true) ?: 'Max. 6', 'group');
$difficulty = dg_translate_tour_value(get_post_meta($pid, 'tour_difficulty', true) ?: 'Easy to Moderate', 'difficulty');
$accommodation = dg_translate_tour_value(get_post_meta($pid, 'tour_accommodation', true), 'accommodation');
$dates = dg_translate_tour_value(get_post_meta($pid, 'tour_dates', true), 'dates');
$pdf = get_post_meta($pid, '_we_pdf_url', true);
$image = get_the_post_thumbnail_url($pid, 'large') ?: '';

$cat_obj = get_the_terms($pid, 'tour_category');
$has_cat = ($cat_obj && !is_wp_error($cat_obj) && !empty($cat_obj));
$cat_name = $has_cat ? $cat_obj[0]->name : 'Tour';
$cat_link = $has_cat ? get_term_link($cat_obj[0]) : '#';
?>

<!-- HERO -->
<section class="we-cat-hero" style="background-image: url('<?php echo esc_url($image); ?>');">
    <div class="we-cat-hero__overlay"></div>
    <div class="we-cat-hero__content">
        <a href="<?php echo esc_url($cat_link); ?>" class="we-cat-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
            <?php echo esc_html($cat_name); ?>
        </a>
        <span class="we-cat-hero__label">Program</span>
        <h1 class="we-cat-hero__title"><?php the_title(); ?></h1>
        <?php if ($subtitle): ?>
            <p class="we-cat-hero__subtitle"><?php echo esc_html($subtitle); ?></p>
        <?php endif; ?>
    </div>
</section>

<!-- DETALLE -->
<section class="we-single-wrap">
    <div class="we-single-grid">
        <div class="we-single-main">
            <!-- Meta bar -->
            <div class="we-single-meta-bar">
                <div class="we-single-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    <div>
                        <span class="we-single-meta-label">Duration</span>
                        <span class="we-single-meta-value"><?php echo esc_html($duration); ?></span>
                    </div>
                </div>
                <div class="we-single-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    <div>
                        <span class="we-single-meta-label">Group</span>
                        <span class="we-single-meta-value"><?php echo esc_html($group); ?></span>
                    </div>
                </div>
                <div class="we-single-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 2L2 7l10 5 10-5-10-5z" />
                        <path d="M2 17l10 5 10-5" />
                        <path d="M2 12l10 5 10-5" />
                    </svg>
                    <div>
                        <span class="we-single-meta-label">Difficulty</span>
                        <span class="we-single-meta-value"><?php echo esc_html($difficulty); ?></span>
                    </div>
                </div>
                <?php if ($price): ?>
                    <div class="we-single-meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <line x1="12" y1="1" x2="12" y2="23" />
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg>
                        <div>
                            <span class="we-single-meta-label">Price</span>
                            <span class="we-single-meta-value"><?php echo esc_html($price); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($price_secondary): ?>
                    <div class="we-single-meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <line x1="12" y1="1" x2="12" y2="23" />
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg>
                        <div>
                            <span class="we-single-meta-label"><?php echo esc_html($price_secondary_label); ?></span>
                            <span class="we-single-meta-value"><?php echo esc_html($price_secondary); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Contenido -->
            <div class="we-single-content">
                <?php the_content(); ?>
            </div>

            <?php if ($pdf): ?>
                <div class="we-single-pdf">
                    <a href="<?php echo esc_url($pdf); ?>" target="_blank" class="we-single-pdf__link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                            <polyline points="10 9 9 9 8 9" />
                        </svg>
                        <span>Download full itinerary (PDF)</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar formulario -->
        <div class="we-single-sidebar">
            <div class="we-request-form">
                <h3 class="we-form-title">Request Information</h3>
                <p class="we-form-subtitle">Complete the form and we will send you the details for this program.</p>

                <?php
                $mensaje = '';
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['we_request_submit'])) {
                    $nombre = sanitize_text_field($_POST['we_nombre'] ?? '');
                    $email = sanitize_email($_POST['we_email'] ?? '');
                    $telefono = sanitize_text_field($_POST['we_telefono'] ?? '');
                    $fecha = sanitize_text_field($_POST['we_fecha'] ?? '');
                    $personas = sanitize_text_field($_POST['we_personas'] ?? '');
                    $notas = sanitize_textarea_field($_POST['we_notas'] ?? '');
                    $tour_sel = get_the_title($pid);

                    $to = get_option('admin_email');
                    $subject = 'Information request - ' . $tour_sel;
                    $body = "New information request\n\n";
                    $body .= "Tour: $tour_sel\n";
                    $body .= "Name: $nombre\n";
                    $body .= "Email: $email\n";
                    $body .= "Phone: $telefono\n";
                    $body .= "Preferred date: $fecha\n";
                    $body .= "Travelers: $personas\n";
                    $body .= "Notes: $notas\n";
                    $headers = ['Content-Type: text/plain; charset=UTF-8', "Reply-To: $email"];

                    if (wp_mail($to, $subject, $body, $headers)) {
                        $mensaje = '<div class="we-form-success">Thank you. We received your request and will contact you soon.</div>';
                    } else {
                        $mensaje = '<div class="we-form-error">There was an error sending your request. Please try again.</div>';
                    }
                }
                echo $mensaje;
                ?>

                <form method="post" action="">
                    <div class="we-form-grid">
                        <div class="we-form-field">
                            <label for="we_nombre">Full name *</label>
                            <input type="text" id="we_nombre" name="we_nombre" required>
                        </div>
                        <div class="we-form-field">
                            <label for="we_email">Email address *</label>
                            <input type="email" id="we_email" name="we_email" required>
                        </div>
                        <div class="we-form-field">
                            <label for="we_telefono">Phone</label>
                            <input type="tel" id="we_telefono" name="we_telefono">
                        </div>
                        <div class="we-form-field">
                            <label for="we_fecha">Preferred date</label>
                            <input type="date" id="we_fecha" name="we_fecha">
                        </div>
                        <div class="we-form-field">
                            <label for="we_personas">Number of travelers</label>
                            <input type="number" id="we_personas" name="we_personas" min="1" max="20">
                        </div>
                    </div>
                    <div class="we-form-field we-form-full">
                        <label for="we_notas">Questions or additional notes</label>
                        <textarea id="we_notas" name="we_notas" rows="4"></textarea>
                    </div>
                    <button type="submit" name="we_request_submit" class="we-form-btn">
                        Send Request
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- RELACIONADOS -->
<?php if ($has_cat):
    $related = new WP_Query([
        'post_type' => 'tour',
        'posts_per_page' => 3,
        'post__not_in' => [$pid],
        'tax_query' => [
            [
                'taxonomy' => 'tour_category',
                'field' => 'slug',
                'terms' => $cat_obj[0]->slug,
            ]
        ],
    ]);
    if ($related->have_posts()):
        ?>
        <section class="we-related">
            <div class="we-related__header">
                <span class="we-section-label">You may also like</span>
                <h2 class="we-section-title">More <?php echo esc_html($cat_name); ?></h2>
            </div>
            <div class="we-related__grid">
                <?php while ($related->have_posts()):
                    $related->the_post(); ?>
                    <a href="<?php the_permalink(); ?>" class="we-related-card">
                        <div class="we-related-card__img">
                            <?php if (has_post_thumbnail())
                                the_post_thumbnail('medium'); ?>
                        </div>
                        <div class="we-related-card__body">
                            <h4><?php the_title(); ?></h4>
                            <span>View program →</span>
                        </div>
                    </a>
                <?php endwhile;
                wp_reset_postdata(); ?>
            </div>
        </section>
    <?php endif; endif; ?>

<?php get_footer(); ?>
