<?php
// === templates/archive-tour.php ===
// Página de categoría O archive general de tours

get_header();

$is_tax = is_tax('tour_category');
$term = $is_tax ? get_queried_object() : null;

$cat_name = $term ? $term->name : 'Tours';
$cat_slug = $term ? $term->slug : '';
$cat_desc = $term ? term_description($term->term_id, 'tour_category') : '';

$cat_subtitle   = $term ? get_term_meta($term->term_id, 'we_cat_subtitle', true) : '';
$cat_hero_img   = $term ? get_term_meta($term->term_id, 'we_cat_hero_img', true) : '';
$cat_duration   = $term ? get_term_meta($term->term_id, 'we_cat_duration', true) : '';
$cat_group      = $term ? get_term_meta($term->term_id, 'we_cat_group', true) : '';
$cat_difficulty = $term ? get_term_meta($term->term_id, 'we_cat_difficulty', true) : '';

if ($is_tax && !$cat_hero_img) {
    $first = new WP_Query([
        'post_type'      => 'tour',
        'posts_per_page' => 1,
        'tax_query'      => [['taxonomy' => 'tour_category', 'field' => 'slug', 'terms' => $cat_slug]],
    ]);
    if ($first->have_posts()) {
        $first->the_post();
        $cat_hero_img = get_the_post_thumbnail_url(get_the_ID(), 'large');
        wp_reset_postdata();
    }
}

$tour_count = $term ? $term->count : wp_count_posts('tour')->publish;

we_show_request_notice();
?>

<?php if ($is_tax && $term) : ?>
<section class="we-cat-hero" style="background-image: url('<?php echo esc_url($cat_hero_img); ?>');">
    <div class="we-cat-hero__overlay"></div>
    <div class="we-cat-hero__content">
        <a href="<?php echo esc_url(get_post_type_archive_link('tour')); ?>" class="we-cat-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Todas las Expediciones
        </a>
        <span class="we-cat-hero__label"><?php echo esc_html(strtoupper($cat_name)); ?></span>
        <h1 class="we-cat-hero__title"><?php echo esc_html($cat_name); ?></h1>
        <?php if ($cat_subtitle) : ?>
            <p class="we-cat-hero__subtitle"><?php echo esc_html($cat_subtitle); ?></p>
        <?php endif; ?>
    </div>
</section>

<section class="we-cat-overview">
    <div class="we-cat-overview__grid">
        <div class="we-cat-overview__text">
            <span class="we-section-label">Descripción</span>
            <h2 class="we-section-title">La experiencia de <?php echo esc_html(str_replace('Tours de ', '', $cat_name)); ?></h2>
            <div class="we-cat-overview__desc">
                <?php echo wp_kses_post($cat_desc); ?>
            </div>
        </div>
        <div class="we-cat-overview__sidebar">
            <div class="we-at-a-glance">
                <h4 class="we-at-a-glance__title">De un vistazo</h4>
                <?php if ($cat_duration) : ?>
                <div class="we-glance-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <div>
                        <span class="we-glance-label">Duración</span>
                        <span class="we-glance-value"><?php echo esc_html($cat_duration); ?></span>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($cat_group) : ?>
                <div class="we-glance-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <div>
                        <span class="we-glance-label">Grupo</span>
                        <span class="we-glance-value"><?php echo esc_html($cat_group); ?></span>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($cat_difficulty) : ?>
                <div class="we-glance-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
                    </svg>
                    <div>
                        <span class="we-glance-label">Dificultad</span>
                        <span class="we-glance-value"><?php echo esc_html($cat_difficulty); ?></span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="we-cat-programs">
    <div class="we-cat-programs__header">
        <span class="we-section-label">Programas</span>
        <h2 class="we-section-title"><?php echo $tour_count; ?> expediciones seleccionadas</h2>
    </div>

    <?php if (have_posts()) : ?>
    <div class="we-programs-list">
        <?php while (have_posts()) : the_post(); 
            $pid = get_the_ID();
            $price = dg_format_price(get_post_meta($pid, 'tour_price', true));
            $duration = dg_translate_tour_value(get_post_meta($pid, 'tour_duration', true) ?: '5-14 días', 'duration');
            $group = dg_translate_tour_value(get_post_meta($pid, 'tour_max_people', true) ?: 'Máx. 6', 'group');
            $difficulty = dg_translate_tour_value(get_post_meta($pid, 'tour_difficulty', true) ?: 'Fácil-Moderado', 'difficulty');
            $dates = dg_translate_tour_value(get_post_meta($pid, 'tour_dates', true), 'dates');
            $pdf = get_post_meta($pid, '_we_pdf_url', true);
            $image = get_the_post_thumbnail_url($pid, 'large') ?: '';
        ?>
        <article class="we-program-card">
            <div class="we-program-card__image">
                <?php if ($image) : ?>
                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy">
                <?php endif; ?>
            </div>
            <div class="we-program-card__content">
                <h3 class="we-program-card__title"><?php the_title(); ?></h3>
                <p class="we-program-card__desc"><?php echo wp_trim_words(get_the_excerpt(), 28); ?></p>

                <div class="we-program-meta">
                    <?php if ($duration) : ?>
                    <div class="we-program-meta__item">
                        <span class="we-program-meta__label"><?php echo dg_get_tour_meta_icon('duration'); ?>Duración</span>
                        <span class="we-program-meta__value"><?php echo esc_html($duration); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($group) : ?>
                    <div class="we-program-meta__item">
                        <span class="we-program-meta__label"><?php echo dg_get_tour_meta_icon('group'); ?>Grupo</span>
                        <span class="we-program-meta__value"><?php echo esc_html($group); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($difficulty) : ?>
                    <div class="we-program-meta__item">
                        <span class="we-program-meta__label"><?php echo dg_get_tour_meta_icon('difficulty'); ?>Dificultad</span>
                        <span class="we-program-meta__value"><?php echo esc_html($difficulty); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($price) : ?>
                    <div class="we-program-meta__item">
                        <span class="we-program-meta__label"><?php echo dg_get_tour_meta_icon('price'); ?>Precio</span>
                        <span class="we-program-meta__value"><?php echo esc_html($price); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($dates) : ?>
                    <div class="we-program-meta__item">
                        <span class="we-program-meta__label"><?php echo dg_get_tour_meta_icon('dates'); ?>Fechas</span>
                        <span class="we-program-meta__value"><?php echo esc_html($dates); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="we-program-card__actions">
                    <button type="button" class="we-btn-terracota" onclick="weOpenModal(<?php echo $pid; ?>, '<?php echo esc_js(get_the_title()); ?>')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
                        </svg>
                        Solicitar Información
                    </button>
                    <?php if ($pdf) : ?>
                    <a href="<?php echo esc_url($pdf); ?>" target="_blank" class="we-btn-green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Descargar PDF
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </article>
        <?php endwhile; ?>
    </div>
    <?php else : ?>
        <p style="text-align:center;padding:60px 20px;color:#888;">No hay programas disponibles en esta categoría.</p>
    <?php endif; ?>
</section>

<?php 
we_render_tour_modal();
get_footer(); 
?>