<?php
// === includes/cpt-tours.php ===
// CPT Tours + Taxonomía Categoría + Meta fields de categoría + Meta boxes del tour

// ──────────────────────────────────────────
// CPT: TOURS
// ──────────────────────────────────────────
add_action('init', function () {
    $labels = [
        'name'                  => 'Tours',
        'singular_name'         => 'Tour',
        'menu_name'             => 'Tours',
        'add_new'               => 'Añadir Nuevo',
        'add_new_item'          => 'Añadir Nuevo Tour',
        'edit_item'             => 'Editar Tour',
        'new_item'              => 'Nuevo Tour',
        'view_item'             => 'Ver Tour',
        'search_items'          => 'Buscar Tours',
        'not_found'             => 'No se encontraron tours',
        'not_found_in_trash'    => 'No se encontraron tours en la papelera',
    ];

    $args = [
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-palmtree',
        'supports'              => ['title', 'editor', 'thumbnail', 'excerpt'],
        'has_archive'           => false,
        'rewrite'               => ['slug' => 'expediciones', 'with_front' => false],
        'taxonomies'            => ['tour_category'],
        'show_in_rest'          => true,
    ];

    register_post_type('tour', $args);
});

// ──────────────────────────────────────────
// TAXONOMÍA: CATEGORÍA DE TOUR
// ──────────────────────────────────────────
add_action('init', function () {
    $labels = [
        'name'          => 'Categorías de Tour',
        'singular_name' => 'Categoría de Tour',
        'search_items'  => 'Buscar Categorías',
        'all_items'     => 'Todas las Categorías',
        'edit_item'     => 'Editar Categoría',
        'add_new_item'  => 'Añadir Nueva Categoría',
        'menu_name'     => 'Categorías',
    ];

    $args = [
        'labels'        => $labels,
        'hierarchical'  => true,
        'public'        => true,
        'rewrite'       => ['slug' => 'tour-category', 'with_front' => false],
        'show_in_rest'  => true,
    ];

    register_taxonomy('tour_category', ['tour'], $args);

    $cats = [
        'birding'  => ['name' => 'Tours de Avistamiento', 'desc' => 'Expediciones guiadas por expertos hacia los lugares más extraordinarios para avistamiento de aves.'],
        'nature'   => ['name' => 'Tours de Naturaleza', 'desc' => 'Expediciones profundas en la selva tropical, caminatas a cascadas y encuentros con fauna.'],
        'hiking'   => ['name' => 'Tours de Senderismo', 'desc' => 'Caminatas de varios días a través de paisajes dramáticos — picos volcánicos, crestas de bosque nuboso y valles ocultos.'],
        'culture'  => ['name' => 'Tours Culturales', 'desc' => 'Encuentros auténticos con comunidades indígenas, artesanías tradicionales y patrimonio vivo.'],
    ];

    foreach ($cats as $slug => $data) {
        if (!term_exists($slug, 'tour_category')) {
            wp_insert_term($data['name'], 'tour_category', [
                'slug'        => $slug,
                'description' => $data['desc'],
            ]);
        }
    }
});

// ──────────────────────────────────────────
// META FIELDS PARA CATEGORÍA
// ──────────────────────────────────────────
add_action('tour_category_add_form_fields', function () {
    ?>
    <div class="form-field">
        <label for="we_cat_subtitle">Subtítulo de la experiencia</label>
        <input type="text" name="we_cat_subtitle" id="we_cat_subtitle" value="" placeholder="Ej: Recorre las fronteras salvajes">
        <p>Aparece debajo del título en la página de categoría.</p>
    </div>
    <div class="form-field">
        <label for="we_cat_hero_img">Imagen de fondo (URL)</label>
        <input type="url" name="we_cat_hero_img" id="we_cat_hero_img" value="" placeholder="https://...">
        <p>Imagen del hero de la categoría. Si está vacía usa la primera imagen de un tour.</p>
    </div>
    <div class="form-field">
        <label for="we_cat_duration">Duración general</label>
        <input type="text" name="we_cat_duration" id="we_cat_duration" value="" placeholder="Ej: 3-12 días">
    </div>
    <div class="form-field">
        <label for="we_cat_group">Tamaño de grupo general</label>
        <input type="text" name="we_cat_group" id="we_cat_group" value="" placeholder="Ej: Máx. 8">
    </div>
    <div class="form-field">
        <label for="we_cat_difficulty">Dificultad general</label>
        <input type="text" name="we_cat_difficulty" id="we_cat_difficulty" value="" placeholder="Ej: Moderado-Avanzado">
    </div>
    <?php
});

add_action('tour_category_edit_form_fields', function ($term) {
    $subtitle   = get_term_meta($term->term_id, 'we_cat_subtitle', true);
    $hero_img   = get_term_meta($term->term_id, 'we_cat_hero_img', true);
    $duration   = get_term_meta($term->term_id, 'we_cat_duration', true);
    $group      = get_term_meta($term->term_id, 'we_cat_group', true);
    $difficulty = get_term_meta($term->term_id, 'we_cat_difficulty', true);
    ?>
    <tr class="form-field">
        <th><label for="we_cat_subtitle">Subtítulo de la experiencia</label></th>
        <td>
            <input type="text" name="we_cat_subtitle" id="we_cat_subtitle" value="<?php echo esc_attr($subtitle); ?>" class="regular-text">
            <p class="description">Aparece debajo del título en la página de categoría.</p>
        </td>
    </tr>
    <tr class="form-field">
        <th><label for="we_cat_hero_img">Imagen de fondo (URL)</label></th>
        <td>
            <input type="url" name="we_cat_hero_img" id="we_cat_hero_img" value="<?php echo esc_url($hero_img); ?>" class="regular-text">
            <p class="description">Si está vacía usa la primera imagen de un tour.</p>
        </td>
    </tr>
    <tr class="form-field">
        <th><label for="we_cat_duration">Duración general</label></th>
        <td><input type="text" name="we_cat_duration" id="we_cat_duration" value="<?php echo esc_attr($duration); ?>" class="regular-text"></td>
    </tr>
    <tr class="form-field">
        <th><label for="we_cat_group">Tamaño de grupo general</label></th>
        <td><input type="text" name="we_cat_group" id="we_cat_group" value="<?php echo esc_attr($group); ?>" class="regular-text"></td>
    </tr>
    <tr class="form-field">
        <th><label for="we_cat_difficulty">Dificultad general</label></th>
        <td><input type="text" name="we_cat_difficulty" id="we_cat_difficulty" value="<?php echo esc_attr($difficulty); ?>" class="regular-text"></td>
    </tr>
    <?php
});

add_action('created_tour_category', 'we_save_cat_meta');
add_action('edited_tour_category', 'we_save_cat_meta');

function we_save_cat_meta($term_id) {
    $fields = ['subtitle', 'hero_img', 'duration', 'group', 'difficulty'];
    foreach ($fields as $field) {
        if (isset($_POST['we_cat_' . $field])) {
            $value = ($field === 'hero_img') ? esc_url_raw($_POST['we_cat_' . $field]) : sanitize_text_field($_POST['we_cat_' . $field]);
            update_term_meta($term_id, 'we_cat_' . $field, $value);
        }
    }
}

// ═══════════════════════════════════════════════════════════════════════════════
// META BOXES PARA TOURS (33 campos según especificación del modal)
// ═══════════════════════════════════════════════════════════════════════════════

add_action('add_meta_boxes', function () {
    add_meta_box('we_tour_basic', 'Información Básica del Tour', 'we_mb_basic', 'tour', 'normal', 'high');
    add_meta_box('we_tour_pdf', 'PDF del Programa', 'we_mb_pdf', 'tour', 'side', 'default');
    add_meta_box('we_tour_gallery', 'Galería de Imágenes', 'we_mb_gallery', 'tour', 'normal', 'high');
    add_meta_box('we_tour_about', 'Sobre este tour', 'we_mb_about', 'tour', 'normal', 'high');
    add_meta_box('we_tour_wildlife', 'Fauna / Experiencia', 'we_mb_wildlife', 'tour', 'normal', 'high');
    add_meta_box('we_tour_organizer', 'Organizador', 'we_mb_organizer', 'tour', 'side', 'default');
    add_meta_box('we_tour_guides', 'Guías', 'we_mb_guides', 'tour', 'normal', 'high');
    add_meta_box('we_tour_itinerary', 'Itinerario', 'we_mb_itinerary', 'tour', 'normal', 'high');
    add_meta_box('we_tour_locations', 'Ubicaciones y Mapa', 'we_mb_locations', 'tour', 'normal', 'high');
    add_meta_box('we_tour_availability', 'Disponibilidad y Precios', 'we_mb_availability', 'tour', 'normal', 'high');
    add_meta_box('we_tour_includes', 'Incluido / No Incluido', 'we_mb_includes', 'tour', 'normal', 'high');
    add_meta_box('we_tour_transport', 'Transporte y Alojamiento', 'we_mb_transport', 'tour', 'side', 'default');
    add_meta_box('we_tour_policies', 'Políticas y FAQ', 'we_mb_policies', 'tour', 'normal', 'high');
});

// ─── Helper: input text ───
function we_field_text($id, $label, $value, $placeholder = '', $type = 'text') {
    ?>
    <div class="we-form-group">
        <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label>
        <input type="<?php echo esc_attr($type); ?>" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($value); ?>" placeholder="<?php echo esc_attr($placeholder); ?>">
    </div>
    <?php
}

// ─── Helper: textarea ───
function we_field_textarea($id, $label, $value, $placeholder = '', $rows = 4) {
    ?>
    <div class="we-form-group">
        <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label>
        <textarea id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($id); ?>" rows="<?php echo intval($rows); ?>" placeholder="<?php echo esc_attr($placeholder); ?>"><?php echo esc_textarea($value); ?></textarea>
    </div>
    <?php
}

// ─── Helper: select ───
function we_field_select($id, $label, $value, $options, $placeholder = '') {
    ?>
    <div class="we-form-group">
        <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label>
        <select id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($id); ?>">
            <?php if ($placeholder) : ?><option value=""><?php echo esc_html($placeholder); ?></option><?php endif; ?>
            <?php foreach ($options as $opt_value => $opt_label) : ?>
                <?php
                $option_value = is_string($opt_value) ? $opt_value : $opt_label;
                $option_label = is_string($opt_value) ? $opt_label : $opt_label;
                ?>
                <option value="<?php echo esc_attr($option_value); ?>" <?php selected($value, $option_value); ?>><?php echo esc_html($option_label); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php
}

// ─── Helper: image uploader ───
function we_field_image($id, $label, $value) {
    $has_img = !empty($value);
    ?>
    <div class="we-form-group">
        <label><?php echo esc_html($label); ?></label>
        <div class="we-image-uploader" data-field="<?php echo esc_attr($id); ?>">
            <div class="we-image-preview" style="display:<?php echo $has_img ? 'block' : 'none'; ?>;">
                <?php if ($has_img) : ?><img src="<?php echo esc_url($value); ?>" alt="" style="max-width:200px;"><?php endif; ?>
                <button type="button" class="we-remove-img">&times;</button>
            </div>
            <button type="button" class="button we-upload-btn" style="display:<?php echo $has_img ? 'none' : 'inline-flex'; ?>;">Seleccionar imagen</button>
            <input type="hidden" name="<?php echo esc_attr($id); ?>" value="<?php echo esc_url($value); ?>">
        </div>
    </div>
    <?php
}

// Helper: nonce para todos los meta boxes
function we_tour_nonce_field() {
    wp_nonce_field('we_tour_meta', 'we_tour_meta_nonce');
}

// ─── 1. INFORMACIÓN BÁSICA ───
function we_mb_basic($post) {
    wp_nonce_field('we_tour_meta', 'we_tour_meta_nonce');
    $fields = [
        'tour_short_description' => get_post_meta($post->ID, 'tour_short_description', true),
        'tour_coords'            => get_post_meta($post->ID, 'tour_coords', true),
        'tour_coords_lat'        => get_post_meta($post->ID, 'tour_coords_lat', true),
        'tour_coords_lng'        => get_post_meta($post->ID, 'tour_coords_lng', true),
        'tour_duration'          => get_post_meta($post->ID, 'tour_duration', true),
        'tour_max_people'        => get_post_meta($post->ID, 'tour_max_people', true),
        'tour_price'             => get_post_meta($post->ID, 'tour_price', true),
        'tour_difficulty'        => get_post_meta($post->ID, 'tour_difficulty', true),
        'tour_accommodation'     => get_post_meta($post->ID, 'tour_accommodation', true),
        'tour_dates'             => get_post_meta($post->ID, 'tour_dates', true),
        'tour_next_date'         => get_post_meta($post->ID, 'tour_next_date', true),
        'tour_type'              => get_post_meta($post->ID, 'tour_type', true),
        'tour_language'          => get_post_meta($post->ID, 'tour_language', true),
        'tour_city'              => get_post_meta($post->ID, 'tour_city', true),
        'tour_pace'              => get_post_meta($post->ID, 'tour_pace', true),
        'tour_transport'         => get_post_meta($post->ID, 'tour_transport', true),
    ];
    ?>
    <div class="we-form-layout">
        <div class="we-form-main">
            <?php we_field_textarea('tour_short_description', 'Descripción corta (header del modal)', $fields['tour_short_description'], '1-2 frases que aparecen en el header del modal', 3); ?>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <?php
                we_field_text('tour_coords', 'Coordenadas', $fields['tour_coords'], "Ej: 09°35'N 83°49'W");
                we_field_text('tour_duration', 'Duración', $fields['tour_duration'], 'Ej: 7 días / 6 noches');
                we_field_text('tour_max_people', 'Máximo de personas', $fields['tour_max_people'], 'Ej: 6', 'number');
                we_field_text('tour_price', 'Precio', $fields['tour_price'], 'Ej: $3,450');
                we_field_select('tour_difficulty', 'Dificultad', $fields['tour_difficulty'], ['Easy' => 'Fácil', 'Moderate' => 'Moderado', 'Advanced' => 'Avanzado', 'Easy–Moderate' => 'Fácil–Moderado', 'Moderate–Advanced' => 'Moderado–Avanzado'], 'Selecciona...');
                we_field_text('tour_accommodation', 'Alojamiento', $fields['tour_accommodation'], 'Ej: Eco-lodge y estación de investigación');
                we_field_text('tour_dates', 'Fechas disponibles', $fields['tour_dates'], 'Ej: Ene 15–21 · Feb 12–18 · Mar 10–16');
                we_field_text('tour_next_date', 'Próxima salida', $fields['tour_next_date'], 'Ej: 15 Ene, 2026');
                we_field_text('tour_type', 'Tipo de tour', $fields['tour_type'], 'Ej: Tour para grupo pequeño (máx. 6)');
                we_field_text('tour_language', 'Idioma', $fields['tour_language'], 'Ej: Inglés y español');
                we_field_text('tour_city', 'Ciudad base', $fields['tour_city'], 'Ej: Monteverde');
                we_field_text('tour_pace', 'Ritmo', $fields['tour_pace'], 'Ej: Moderado — caminatas diarias de 3–6 km');
                we_field_text('tour_transport', 'Transporte', $fields['tour_transport'], 'Ej: Vehículo privado 4x4 y traslado de montaña');
                ?>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:20px;">
                <?php
                we_field_text('tour_coords_lat', 'Latitud (para mapa)', $fields['tour_coords_lat'], 'Ej: 9.58');
                we_field_text('tour_coords_lng', 'Longitud (para mapa)', $fields['tour_coords_lng'], 'Ej: -83.81');
                ?>
            </div>
        </div>
        <div class="we-form-sidebar">
            <div class="we-sidebar-box">
                <h3>Imagen Principal</h3>
                <p style="font-size:12px;color:#888;margin-bottom:12px;">Usa la imagen destacada de WordPress.</p>
            </div>
            <div class="we-sidebar-box">
                <h3>Campos clave</h3>
                <p style="font-size:12px;color:#888;line-height:1.6;">
                    • <strong>Descripción corta:</strong> Aparece en el header oscuro del modal.<br>
                    • <strong>Coordenadas:</strong> Se muestran como metadata estilo expedición.<br>
                    • <strong>Lat/Lng:</strong> Centran el mapa Leaflet.
                </p>
            </div>
        </div>
    </div>
    <?php
}

// ─── 2. GALERÍA ───
function we_mb_gallery($post) {
    we_tour_nonce_field();
    $gallery = get_post_meta($post->ID, 'tour_gallery', true);
    if (!is_array($gallery)) $gallery = [];
    ?>
    <div class="we-repeater" data-repeater="gallery">
        <div class="we-repeater-items">
            <?php foreach ($gallery as $i => $img) : ?>
            <div class="we-repeater-row" data-index="<?php echo $i; ?>">
                <div class="we-form-group">
                    <label>Imagen <?php echo $i + 1; ?></label>
                    <div class="we-image-uploader" data-field="tour_gallery[<?php echo $i; ?>]">
                        <div class="we-image-preview" style="display:<?php echo !empty($img) ? 'block' : 'none'; ?>;">
                            <?php if (!empty($img)) : ?><img src="<?php echo esc_url($img); ?>" alt="" style="max-width:200px;"><?php endif; ?>
                            <button type="button" class="we-remove-img">&times;</button>
                        </div>
                        <button type="button" class="button we-upload-btn" style="display:<?php echo !empty($img) ? 'none' : 'inline-flex'; ?>;">Seleccionar imagen</button>
                        <input type="hidden" name="tour_gallery[<?php echo $i; ?>]" value="<?php echo esc_url($img); ?>">
                    </div>
                </div>
                <button type="button" class="button we-repeater-remove">Eliminar</button>
            </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button we-repeater-add" data-template="gallery">+ Añadir imagen</button>
    </div>
    <template id="we-tpl-gallery">
        <div class="we-repeater-row">
            <div class="we-form-group">
                <label>Imagen</label>
                <div class="we-image-uploader" data-field="tour_gallery[__IDX__]">
                    <div class="we-image-preview" style="display:none;">
                        <img src="" alt="" style="max-width:200px;">
                        <button type="button" class="we-remove-img">&times;</button>
                    </div>
                    <button type="button" class="button we-upload-btn">Seleccionar imagen</button>
                    <input type="hidden" name="tour_gallery[__IDX__]" value="">
                </div>
            </div>
            <button type="button" class="button we-repeater-remove">Eliminar</button>
        </div>
    </template>
    <?php
}

// ─── 3. ABOUT ───
function we_mb_about($post) {
    we_tour_nonce_field();
    $about = get_post_meta($post->ID, 'tour_about', true);
    if (!is_array($about)) $about = [''];
    ?>
    <div class="we-repeater" data-repeater="about">
        <div class="we-repeater-items">
            <?php foreach ($about as $i => $text) : ?>
            <div class="we-repeater-row" data-index="<?php echo $i; ?>">
                <?php we_field_textarea("tour_about[$i]", 'Párrafo ' . ($i + 1), $text, 'Escribe un párrafo...', 4); ?>
                <button type="button" class="button we-repeater-remove">Eliminar párrafo</button>
            </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button we-repeater-add" data-template="about">+ Añadir párrafo</button>
    </div>
    <template id="we-tpl-about">
        <div class="we-repeater-row">
            <div class="we-form-group">
                <label>Párrafo</label>
                <textarea name="tour_about[__IDX__]" rows="4" placeholder="Escribe un párrafo..."></textarea>
            </div>
            <button type="button" class="button we-repeater-remove">Eliminar párrafo</button>
        </div>
    </template>
    <?php
}

// ─── 4. FAUNA / EXPERIENCIA ───
function we_mb_wildlife($post) {
    we_tour_nonce_field();
    $label = get_post_meta($post->ID, 'tour_wildlife_label', true);
    $wildlife = get_post_meta($post->ID, 'tour_wildlife', true);
    if (!is_array($wildlife)) $wildlife = [];
    ?>
    <div style="margin-bottom:20px;">
        <?php we_field_text('tour_wildlife_label', 'Título de la sección', $label, 'Ej: Birds you can spot / Wildlife you can spot / What youll experience'); ?>
    </div>
    <div class="we-repeater" data-repeater="wildlife">
        <div class="we-repeater-items">
            <?php foreach ($wildlife as $i => $item) : 
                $item = wp_parse_args($item, ['name' => '', 'scientific' => '', 'image' => '', 'is_target' => '']);
            ?>
            <div class="we-repeater-row" data-index="<?php echo $i; ?>" style="border:1px solid #e8e8e8;padding:16px;border-radius:4px;margin-bottom:12px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <?php
                    we_field_text("tour_wildlife[$i][name]", 'Nombre común', $item['name'], 'Ej: Resplendent Quetzal');
                    we_field_text("tour_wildlife[$i][scientific]", 'Nombre científico / Categoría', $item['scientific'], 'Ej: Pharomachrus mocinno');
                    ?>
                </div>
                <?php we_field_image("tour_wildlife[$i][image]", 'Foto del animal', $item['image']); ?>
                <div class="we-form-group">
                    <label>
                        <input type="checkbox" name="tour_wildlife[<?php echo $i; ?>][is_target]" value="1" <?php checked($item['is_target'], '1'); ?>>
                        Es especie objetivo (muestra insignia "Objetivo")
                    </label>
                </div>
                <button type="button" class="button we-repeater-remove">Eliminar</button>
            </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button we-repeater-add" data-template="wildlife">+ Añadir especie</button>
    </div>
    <template id="we-tpl-wildlife">
        <div class="we-repeater-row" style="border:1px solid #e8e8e8;padding:16px;border-radius:4px;margin-bottom:12px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="we-form-group">
                    <label>Nombre común</label>
                    <input type="text" name="tour_wildlife[__IDX__][name]" placeholder="Ej: Resplendent Quetzal">
                </div>
                <div class="we-form-group">
                    <label>Nombre científico / Categoría</label>
                    <input type="text" name="tour_wildlife[__IDX__][scientific]" placeholder="Ej: Pharomachrus mocinno">
                </div>
            </div>
            <div class="we-form-group">
                <label>Foto del animal</label>
                <div class="we-image-uploader" data-field="tour_wildlife[__IDX__][image]">
                    <div class="we-image-preview" style="display:none;">
                        <img src="" alt="" style="max-width:200px;">
                        <button type="button" class="we-remove-img">&times;</button>
                    </div>
                    <button type="button" class="button we-upload-btn">Seleccionar imagen</button>
                    <input type="hidden" name="tour_wildlife[__IDX__][image]" value="">
                </div>
            </div>
            <div class="we-form-group">
                <label>
                    <input type="checkbox" name="tour_wildlife[__IDX__][is_target]" value="1">
                    Es especie objetivo (muestra insignia "Objetivo")
                </label>
            </div>
            <button type="button" class="button we-repeater-remove">Eliminar</button>
        </div>
    </template>
    <?php
}

// ─── 5. ORGANIZADOR ───
function we_mb_organizer($post) {
    we_tour_nonce_field();
    $name = get_post_meta($post->ID, 'tour_organizer_name', true);
    $role = get_post_meta($post->ID, 'tour_organizer_role', true);
    $logo = get_post_meta($post->ID, 'tour_organizer_logo', true);
    ?>
    <?php we_field_text('tour_organizer_name', 'Nombre del organizador', $name, 'Ej: WILD ENDEMICS'); ?>
    <?php we_field_text('tour_organizer_role', 'Rol del organizador', $role, 'Ej: Operador de expedición en Costa Rica'); ?>
    <?php we_field_image('tour_organizer_logo', 'Logo del organizador', $logo); ?>
    <?php
}

// ─── 6. GUÍAS ───
function we_mb_guides($post) {
    we_tour_nonce_field();
    $guides = get_post_meta($post->ID, 'tour_guides', true);
    if (!is_array($guides)) $guides = [];
    ?>
    <div class="we-repeater" data-repeater="guides">
        <div class="we-repeater-items">
            <?php foreach ($guides as $i => $guide) : 
                $guide = wp_parse_args($guide, ['name' => '', 'language' => '', 'role' => '', 'photo' => '']);
            ?>
            <div class="we-repeater-row" data-index="<?php echo $i; ?>" style="border:1px solid #e8e8e8;padding:16px;border-radius:4px;margin-bottom:12px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <?php
                    we_field_text("tour_guides[$i][name]", 'Nombre', $guide['name'], 'Ej: Carlos Méndez');
                    we_field_text("tour_guides[$i][language]", 'Idiomas', $guide['language'], 'Ej: Inglés · Español');
                    we_field_text("tour_guides[$i][role]", 'Rol', $guide['role'], 'Ej: Guía principal de observación de aves');
                    ?>
                </div>
                <?php we_field_image("tour_guides[$i][photo]", 'Foto del guía', $guide['photo']); ?>
                <button type="button" class="button we-repeater-remove">Eliminar guía</button>
            </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button we-repeater-add" data-template="guides">+ Añadir guía</button>
    </div>
    <template id="we-tpl-guides">
        <div class="we-repeater-row" style="border:1px solid #e8e8e8;padding:16px;border-radius:4px;margin-bottom:12px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="we-form-group"><label>Nombre</label><input type="text" name="tour_guides[__IDX__][name]" placeholder="Ej: Carlos Méndez"></div>
                <div class="we-form-group"><label>Idiomas</label><input type="text" name="tour_guides[__IDX__][language]" placeholder="Ej: Inglés · Español"></div>
                <div class="we-form-group"><label>Rol</label><input type="text" name="tour_guides[__IDX__][role]" placeholder="Ej: Guía principal de observación de aves"></div>
            </div>
            <div class="we-form-group">
                <label>Foto del guía</label>
                <div class="we-image-uploader" data-field="tour_guides[__IDX__][photo]">
                    <div class="we-image-preview" style="display:none;">
                        <img src="" alt="" style="max-width:200px;">
                        <button type="button" class="we-remove-img">&times;</button>
                    </div>
                    <button type="button" class="button we-upload-btn">Seleccionar imagen</button>
                    <input type="hidden" name="tour_guides[__IDX__][photo]" value="">
                </div>
            </div>
            <button type="button" class="button we-repeater-remove">Eliminar guía</button>
        </div>
    </template>
    <?php
}

// ─── 7. ITINERARIO ───
function we_mb_itinerary($post) {
    we_tour_nonce_field();
    $itinerary = get_post_meta($post->ID, 'tour_itinerary', true);
    if (!is_array($itinerary)) $itinerary = [];
    ?>
    <div class="we-repeater" data-repeater="itinerary">
        <div class="we-repeater-items">
            <?php foreach ($itinerary as $i => $day) : 
                $day = wp_parse_args($day, ['day' => '', 'title' => '', 'description' => '']);
            ?>
            <div class="we-repeater-row" data-index="<?php echo $i; ?>" style="border:1px solid #e8e8e8;padding:16px;border-radius:4px;margin-bottom:12px;">
                <div style="display:grid;grid-template-columns:120px 1fr;gap:16px;">
                    <?php
                    we_field_text("tour_itinerary[$i][day]", 'Etiqueta', $day['day'], 'Ej: Día 1');
                    we_field_text("tour_itinerary[$i][title]", 'Título', $day['title'], 'Ej: Llegada a San José');
                    ?>
                </div>
                <?php we_field_textarea("tour_itinerary[$i][description]", 'Descripción', $day['description'], 'Describe las actividades del día...', 4); ?>
                <button type="button" class="button we-repeater-remove">Eliminar día</button>
            </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button we-repeater-add" data-template="itinerary">+ Añadir día</button>
    </div>
    <template id="we-tpl-itinerary">
        <div class="we-repeater-row" style="border:1px solid #e8e8e8;padding:16px;border-radius:4px;margin-bottom:12px;">
            <div style="display:grid;grid-template-columns:120px 1fr;gap:16px;">
                <div class="we-form-group"><label>Etiqueta</label><input type="text" name="tour_itinerary[__IDX__][day]" placeholder="Ej: Día 1"></div>
                <div class="we-form-group"><label>Título</label><input type="text" name="tour_itinerary[__IDX__][title]" placeholder="Ej: Llegada a San José"></div>
            </div>
            <div class="we-form-group">
                <label>Descripción</label>
                <textarea name="tour_itinerary[__IDX__][description]" rows="4" placeholder="Describe las actividades del día..."></textarea>
            </div>
            <button type="button" class="button we-repeater-remove">Eliminar día</button>
        </div>
    </template>
    <?php
}

// ─── 8. UBICACIONES ───
function we_mb_locations($post) {
    we_tour_nonce_field();
    $locations = get_post_meta($post->ID, 'tour_locations', true);
    if (!is_array($locations)) $locations = [];
    ?>
    <div class="we-repeater" data-repeater="locations">
        <div class="we-repeater-items">
            <?php foreach ($locations as $i => $loc) : 
                $loc = wp_parse_args($loc, ['name' => '', 'region' => '', 'description' => '', 'lat' => '', 'lng' => '']);
            ?>
            <div class="we-repeater-row" data-index="<?php echo $i; ?>" style="border:1px solid #e8e8e8;padding:16px;border-radius:4px;margin-bottom:12px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <?php
                    we_field_text("tour_locations[$i][name]", 'Nombre', $loc['name'], 'Ej: Reserva del Bosque Nuboso de Monteverde');
                    we_field_text("tour_locations[$i][region]", 'Región', $loc['region'], 'Ej: Puntarenas, Costa Rica');
                    we_field_text("tour_locations[$i][lat]", 'Latitud', $loc['lat'], 'Ej: 9.58');
                    we_field_text("tour_locations[$i][lng]", 'Longitud', $loc['lng'], 'Ej: -83.81');
                    ?>
                </div>
                <?php we_field_textarea("tour_locations[$i][description]", 'Descripción corta', $loc['description'], 'Breve descripción de la ubicación...', 3); ?>
                <button type="button" class="button we-repeater-remove">Eliminar ubicación</button>
            </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button we-repeater-add" data-template="locations">+ Añadir ubicación</button>
    </div>
    <template id="we-tpl-locations">
        <div class="we-repeater-row" style="border:1px solid #e8e8e8;padding:16px;border-radius:4px;margin-bottom:12px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="we-form-group"><label>Nombre</label><input type="text" name="tour_locations[__IDX__][name]" placeholder="Ej: Reserva del Bosque Nuboso de Monteverde"></div>
                <div class="we-form-group"><label>Región</label><input type="text" name="tour_locations[__IDX__][region]" placeholder="Ej: Puntarenas, Costa Rica"></div>
                <div class="we-form-group"><label>Latitud</label><input type="text" name="tour_locations[__IDX__][lat]" placeholder="Ej: 9.58"></div>
                <div class="we-form-group"><label>Longitud</label><input type="text" name="tour_locations[__IDX__][lng]" placeholder="Ej: -83.81"></div>
            </div>
            <div class="we-form-group">
                <label>Descripción corta</label>
                <textarea name="tour_locations[__IDX__][description]" rows="3" placeholder="Breve descripción de la ubicación..."></textarea>
            </div>
            <button type="button" class="button we-repeater-remove">Eliminar ubicación</button>
        </div>
    </template>
    <?php
}

// ─── 9. DISPONIBILIDAD ───
function we_mb_availability($post) {
    we_tour_nonce_field();
    $availability = get_post_meta($post->ID, 'tour_availability', true);
    if (!is_array($availability)) $availability = [];
    ?>
    <div class="we-repeater" data-repeater="availability">
        <div class="we-repeater-items">
            <?php foreach ($availability as $i => $item) : 
                $item = wp_parse_args($item, ['date' => '', 'status' => '']);
            ?>
            <div class="we-repeater-row" data-index="<?php echo $i; ?>" style="display:grid;grid-template-columns:1fr 1fr auto;gap:16px;align-items:end;border:1px solid #e8e8e8;padding:16px;border-radius:4px;margin-bottom:12px;">
                <?php
                we_field_text("tour_availability[$i][date]", 'Fecha', $item['date'], 'Ej: 15–21 Ene, 2026');
                we_field_text("tour_availability[$i][status]", 'Estado', $item['status'], 'Ej: Quedan 3 cupos / Disponible');
                ?>
                <button type="button" class="button we-repeater-remove">Eliminar</button>
            </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button we-repeater-add" data-template="availability">+ Añadir fecha</button>
    </div>
    <template id="we-tpl-availability">
        <div class="we-repeater-row" style="display:grid;grid-template-columns:1fr 1fr auto;gap:16px;align-items:end;border:1px solid #e8e8e8;padding:16px;border-radius:4px;margin-bottom:12px;">
            <div class="we-form-group"><label>Fecha</label><input type="text" name="tour_availability[__IDX__][date]" placeholder="Ej: 15–21 Ene, 2026"></div>
            <div class="we-form-group"><label>Estado</label><input type="text" name="tour_availability[__IDX__][status]" placeholder="Ej: Quedan 3 cupos / Disponible"></div>
            <button type="button" class="button we-repeater-remove">Eliminar</button>
        </div>
    </template>
    <?php
}

// ─── 10. INCLUIDO / NO INCLUIDO ───
function we_mb_includes($post) {
    we_tour_nonce_field();
    $includes = get_post_meta($post->ID, 'tour_includes', true);
    $excludes = get_post_meta($post->ID, 'tour_excludes', true);
    if (!is_array($includes)) $includes = [''];
    if (!is_array($excludes)) $excludes = [''];
    ?>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:30px;">
        <div>
            <h4 style="margin:0 0 16px;color:#2D6A4F;">✓ Incluido</h4>
            <div class="we-repeater" data-repeater="includes">
                <div class="we-repeater-items">
                    <?php foreach ($includes as $i => $text) : ?>
                    <div class="we-repeater-row" data-index="<?php echo $i; ?>" style="display:flex;gap:8px;align-items:start;margin-bottom:8px;">
                        <div class="we-form-group" style="flex:1;margin:0;">
                            <input type="text" name="tour_includes[<?php echo $i; ?>]" value="<?php echo esc_attr($text); ?>" placeholder="Ej: Todo el transporte del tour — 4x4 privado...">
                        </div>
                        <button type="button" class="button we-repeater-remove" style="margin-top:2px;">&times;</button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button we-repeater-add" data-template="includes">+ Añadir item</button>
            </div>
            <template id="we-tpl-includes">
                <div class="we-repeater-row" style="display:flex;gap:8px;align-items:start;margin-bottom:8px;">
                    <div class="we-form-group" style="flex:1;margin:0;">
                        <input type="text" name="tour_includes[__IDX__]" placeholder="Ej: Todo el transporte del tour — 4x4 privado...">
                    </div>
                    <button type="button" class="button we-repeater-remove" style="margin-top:2px;">&times;</button>
                </div>
            </template>
        </div>
        <div>
            <h4 style="margin:0 0 16px;color:#E76F51;">✕ No Incluido</h4>
            <div class="we-repeater" data-repeater="excludes">
                <div class="we-repeater-items">
                    <?php foreach ($excludes as $i => $text) : ?>
                    <div class="we-repeater-row" data-index="<?php echo $i; ?>" style="display:flex;gap:8px;align-items:start;margin-bottom:8px;">
                        <div class="we-form-group" style="flex:1;margin:0;">
                            <input type="text" name="tour_excludes[<?php echo $i; ?>]" value="<?php echo esc_attr($text); ?>" placeholder="Ej: Vuelos internacionales hacia y desde San José...">
                        </div>
                        <button type="button" class="button we-repeater-remove" style="margin-top:2px;">&times;</button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button we-repeater-add" data-template="excludes">+ Añadir item</button>
            </div>
            <template id="we-tpl-excludes">
                <div class="we-repeater-row" style="display:flex;gap:8px;align-items:start;margin-bottom:8px;">
                    <div class="we-form-group" style="flex:1;margin:0;">
                        <input type="text" name="tour_excludes[__IDX__]" placeholder="Ej: Vuelos internacionales hacia y desde San José...">
                    </div>
                    <button type="button" class="button we-repeater-remove" style="margin-top:2px;">&times;</button>
                </div>
            </template>
        </div>
    </div>
    <?php
}

// ─── 11. TRANSPORTE Y ALOJAMIENTO ───
function we_mb_transport($post) {
    we_tour_nonce_field();
    $transport = get_post_meta($post->ID, 'tour_transport', true);
    $accommodation = get_post_meta($post->ID, 'tour_accommodation', true);
    $pace = get_post_meta($post->ID, 'tour_pace', true);
    ?>
    <?php we_field_text('tour_transport_side', 'Transporte', $transport, 'Ej: Vehículo privado 4x4 y traslados'); ?>
    <?php we_field_text('tour_accommodation_side', 'Alojamiento', $accommodation, 'Ej: Eco-lodge y estación de investigación'); ?>
    <?php we_field_text('tour_pace_side', 'Ritmo', $pace, 'Ej: Moderado — caminatas diarias de 3–6 km'); ?>
    <p style="font-size:11px;color:#888;margin-top:8px;">Nota: Estos campos también se muestran en Información Básica. Se sincronizan al guardar.</p>
    <?php
}

// ─── 13. PDF DEL PROGRAMA ───
function we_mb_pdf($post) {
    we_tour_nonce_field();
    $pdf = get_post_meta($post->ID, '_we_pdf_url', true);
    ?>
    <div class="we-form-group">
        <label for="_we_pdf_url">URL del PDF del programa</label>
        <input type="url" id="_we_pdf_url" name="_we_pdf_url" value="<?php echo esc_url($pdf); ?>" placeholder="https://tusitio.com/pdf/programa.pdf" style="width:100%;">
        <p style="font-size:11px;color:#888;margin-top:6px;">Pega aquí la URL del PDF. Se muestra como botón "Descargar PDF" en la tarjeta.</p>
        <?php if (!empty($pdf)) : ?>
        <p style="font-size:12px;margin-top:8px;">
            <a href="<?php echo esc_url($pdf); ?>" target="_blank" style="color:#2E6A4F;">📄 Ver PDF actual</a>
        </p>
        <?php endif; ?>
    </div>
    <?php
}

// ─── 12. POLÍTICAS Y FAQ ───
function we_mb_policies($post) {
    we_tour_nonce_field();
    $policies = get_post_meta($post->ID, 'tour_policies', true);
    $faq = get_post_meta($post->ID, 'tour_faq', true);
    if (!is_array($faq)) $faq = [];
    ?>
    <?php we_field_textarea('tour_policies', 'Políticas de reserva y cancelación', $policies, 'Escribe las políticas...', 6); ?>
    <hr style="margin:24px 0;border:none;border-top:1px solid #e8e8e8;">
    <h4 style="margin:0 0 16px;">Preguntas Frecuentes (FAQ)</h4>
    <div class="we-repeater" data-repeater="faq">
        <div class="we-repeater-items">
            <?php foreach ($faq as $i => $item) : 
                $item = wp_parse_args($item, ['question' => '', 'answer' => '']);
            ?>
            <div class="we-repeater-row" data-index="<?php echo $i; ?>" style="border:1px solid #e8e8e8;padding:16px;border-radius:4px;margin-bottom:12px;">
                <?php we_field_text("tour_faq[$i][question]", 'Pregunta', $item['question'], 'Ej: ¿Qué debo llevar?'); ?>
                <?php we_field_textarea("tour_faq[$i][answer]", 'Respuesta', $item['answer'], 'Escribe la respuesta...', 4); ?>
                <button type="button" class="button we-repeater-remove">Eliminar FAQ</button>
            </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button we-repeater-add" data-template="faq">+ Añadir FAQ</button>
    </div>
    <template id="we-tpl-faq">
        <div class="we-repeater-row" style="border:1px solid #e8e8e8;padding:16px;border-radius:4px;margin-bottom:12px;">
            <div class="we-form-group"><label>Pregunta</label><input type="text" name="tour_faq[__IDX__][question]" placeholder="Ej: ¿Qué debo llevar?"></div>
            <div class="we-form-group"><label>Respuesta</label><textarea name="tour_faq[__IDX__][answer]" rows="4" placeholder="Escribe la respuesta..."></textarea></div>
            <button type="button" class="button we-repeater-remove">Eliminar FAQ</button>
        </div>
    </template>
    <?php
}

// ═══════════════════════════════════════════════════════════════════════════════
// GUARDAR META BOXES  (CORREGIDO - sin bugs de sintaxis)
// ═══════════════════════════════════════════════════════════════════════════════
add_action('save_post_tour', function ($post_id) {
    // Verificar nonce
    if (!isset($_POST['we_tour_meta_nonce']) || !wp_verify_nonce($_POST['we_tour_meta_nonce'], 'we_tour_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // -------------------------------------------------------------------------
    // Campos simples de texto (sanitize_text_field)
    // -------------------------------------------------------------------------
    $text_fields = [
        'tour_coords', 'tour_coords_lat', 'tour_coords_lng',
        'tour_duration', 'tour_price', 'tour_difficulty',
        'tour_accommodation', 'tour_dates', 'tour_next_date', 'tour_type',
        'tour_language', 'tour_city', 'tour_transport',
        'tour_wildlife_label', 'tour_organizer_name', 'tour_organizer_role',
    ];
    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }

    // -------------------------------------------------------------------------
    // Campos textarea (sanitize_textarea_field)
    // -------------------------------------------------------------------------
    $textarea_fields = ['tour_short_description', 'tour_policies'];
    foreach ($textarea_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_textarea_field($_POST[$field]));
        }
    }

    // -------------------------------------------------------------------------
    // Campo numerico
    // -------------------------------------------------------------------------
    if (isset($_POST['tour_max_people'])) {
        update_post_meta($post_id, 'tour_max_people', intval($_POST['tour_max_people']));
    }

    // -------------------------------------------------------------------------
    // Campo URL (logo del organizador)
    // -------------------------------------------------------------------------
    if (isset($_POST['tour_organizer_logo'])) {
        update_post_meta($post_id, 'tour_organizer_logo', esc_url_raw($_POST['tour_organizer_logo']));
    }
    
    // Campo PDF del programa
if (isset($_POST['_we_pdf_url'])) {
    update_post_meta($post_id, '_we_pdf_url', esc_url_raw($_POST['_we_pdf_url']));
}

    // -------------------------------------------------------------------------
    // Sincronizar transport/accommodation/pace del sidebar
    // -------------------------------------------------------------------------
    if (isset($_POST['tour_transport_side'])) {
        update_post_meta($post_id, 'tour_transport', sanitize_text_field($_POST['tour_transport_side']));
    }
    if (isset($_POST['tour_accommodation_side'])) {
        update_post_meta($post_id, 'tour_accommodation', sanitize_text_field($_POST['tour_accommodation_side']));
    }
    if (isset($_POST['tour_pace_side'])) {
        update_post_meta($post_id, 'tour_pace', sanitize_text_field($_POST['tour_pace_side']));
    }

    // -------------------------------------------------------------------------
    // Repeaters simples (arrays de strings)
    // -------------------------------------------------------------------------
    $simple_repeaters = [
        'tour_gallery'  => 'esc_url_raw',
        'tour_about'    => 'sanitize_textarea_field',
        'tour_includes' => 'sanitize_text_field',
        'tour_excludes' => 'sanitize_text_field',
    ];
    foreach ($simple_repeaters as $key => $sanitize_fn) {
        if (isset($_POST[$key]) && is_array($_POST[$key])) {
            $clean = array_map($sanitize_fn, array_values(array_filter($_POST[$key], function ($v) {
                return $v !== '';
            })));
            update_post_meta($post_id, $key, $clean);
        } else {
            delete_post_meta($post_id, $key);
        }
    }

    // -------------------------------------------------------------------------
    // Repeaters complejos (arrays asociativos)
    // -------------------------------------------------------------------------
    $complex_repeaters = ['tour_wildlife', 'tour_guides', 'tour_itinerary', 'tour_locations', 'tour_availability', 'tour_faq'];
    foreach ($complex_repeaters as $key) {
        if (isset($_POST[$key]) && is_array($_POST[$key])) {
            $clean = [];
            foreach (array_values($_POST[$key]) as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $row = [];
                foreach ($item as $k => $v) {
                    if ($k === 'image' || $k === 'photo' || $k === 'logo') {
                        $row[$k] = esc_url_raw($v);
                    } elseif ($k === 'is_target') {
                        $row[$k] = ($v === '1') ? '1' : '';
                    } elseif ($k === 'description' || $k === 'answer') {
                        $row[$k] = sanitize_textarea_field($v);
                    } else {
                        $row[$k] = sanitize_text_field($v);
                    }
                }
                $clean[] = $row;
            }
            update_post_meta($post_id, $key, $clean);
        } else {
            delete_post_meta($post_id, $key);
        }
    }
});

// ─── Admin JS para repeaters e image upload ───
add_action('admin_enqueue_scripts', function ($hook) {
    global $post_type;
    if (($hook === 'post.php' || $hook === 'post-new.php') && $post_type === 'tour') {
        wp_enqueue_media();
        wp_enqueue_style('we-admin-tours-css', plugin_dir_url(__FILE__) . '../css/admin-tours.css', [], '1.0');
        wp_add_inline_script('jquery', '
        jQuery(function($) {
            // Image uploader
            $(document).on("click", ".we-upload-btn", function(e) {
                e.preventDefault();
                var $wrap = $(this).closest(".we-image-uploader");
            var frame = wp.media({ 
    title: "Seleccionar imagen", 
    button: { text: "Usar esta imagen" }, 
    multiple: false 
});
                frame.on("select", function() {
                    var attachment = frame.state().get("selection").first().toJSON();
                    $wrap.find("img").attr("src", attachment.url);
                    $wrap.find("input[type=hidden]").val(attachment.url);
                    $wrap.find(".we-image-preview").show();
                    $wrap.find(".we-upload-btn").hide();
                });
                frame.open();
            });
            $(document).on("click", ".we-remove-img", function(e) {
                e.preventDefault();
                var $wrap = $(this).closest(".we-image-uploader");
                $wrap.find("img").attr("src", "");
                $wrap.find("input[type=hidden]").val("");
                $wrap.find(".we-image-preview").hide();
                $wrap.find(".we-upload-btn").show();
            });

            // Repeaters
            $(document).on("click", ".we-repeater-add", function() {
                var $repeater = $(this).closest(".we-repeater");
                var templateId = "we-tpl-" + $(this).data("template");
                var $tpl = $("#" + templateId).html();
                var idx = $repeater.find(".we-repeater-row").length;
                $tpl = $tpl.replace(/__IDX__/g, idx);
                $repeater.find(".we-repeater-items").append($tpl);
                // Re-index image uploaders
                $repeater.find(".we-repeater-row").each(function(i) {
                    $(this).find(".we-image-uploader").each(function() {
                        var base = $(this).data("field");
                        if (base) {
                            var newName = base.replace(/__IDX__/g, i);
                            $(this).attr("data-field", newName);
                            $(this).find("input[type=hidden]").attr("name", newName);
                        }
                    });
                });
            });
            $(document).on("click", ".we-repeater-remove", function() {
                $(this).closest(".we-repeater-row").remove();
            });
        });
        ');
    }
});