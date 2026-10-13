<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('dg_cw_get_default_settings')) {
    function dg_cw_get_default_settings()
    {
        return [
            'general' => [
                'brand_name' => 'CreativeWilds',
                'location_en' => 'South Florida',
                'location_es' => 'Sur de Florida',
            ],
            'hero' => [
                'primary_button_en' => 'Book a shoot',
                'primary_button_es' => 'Reservar sesi' . "\xC3\xB3" . 'n',
                'secondary_button_en' => 'See our work',
                'secondary_button_es' => 'Ver trabajos',
            ],
            'services' => [
                'eyebrow_en' => 'What we do',
                'eyebrow_es' => 'Lo que hacemos',
                'title_en' => 'Four ways we make a brand impossible to ignore',
                'title_es' => 'Cuatro formas de hacer que tu marca sea imposible de ignorar',
                'subtitle_en' => 'Every project starts from the same idea: visuals that feel like cinema and work like strategy.',
                'subtitle_es' => 'Cada proyecto parte de la misma idea: im' . "\xC3\xA1" . 'genes que se sienten como cine y funcionan como estrategia.',
                'cta_en' => 'Inquire about this service',
                'cta_es' => 'Consultar por este servicio',
                'items' => [
                    [
                        'slug' => 'real-estate-property-marketing',
                        'image_id' => 0,
                        'hero_image_id' => 0,
                        'title_en' => 'Real Estate & Property Marketing',
                        'title_es' => 'Bienes R' . "\xC3\xAD" . 'ces y Marketing de Propiedades',
                        'hero_lead_en' => 'Cinematic content that makes your properties',
                        'hero_accent_en' => 'sell faster',
                        'hero_lead_es' => 'Contenido que hace que tus propiedades',
                        'hero_accent_es' => 'vendan m' . "\xC3\xA1" . 's r' . "\xC3\xA1" . 'pido',
                        'description_en' => 'Premium photography, cinematic video and drone content for properties, developments and real estate professionals designed to showcase every space and attract qualified buyers.',
                        'description_es' => 'Fotograf' . "\xC3\xAD" . 'a premium, video cinematogr' . "\xC3\xA1" . 'fico y contenido con dron para propiedades, desarrollos y profesionales inmobiliarios, dise' . "\xC3\xB1" . 'ado para mostrar cada espacio y atraer compradores calificados.',
                    ],
                    [
                        'slug' => 'marine-luxury-experiences',
                        'image_id' => 0,
                        'hero_image_id' => 0,
                        'title_en' => 'Marine & Luxury Experiences',
                        'title_es' => 'Marina y Experiencias de Lujo',
                        'hero_lead_en' => 'Visuals that capture',
                        'hero_accent_en' => 'the luxury of the sea',
                        'hero_lead_es' => 'Visuales que capturan',
                        'hero_accent_es' => 'el lujo del mar',
                        'description_en' => 'Premium photography and cinematic productions for yachts, marinas and luxury experiences designed to capture elegance, lifestyle and unforgettable moments.',
                        'description_es' => 'Fotograf' . "\xC3\xAD" . 'a premium y producciones cinematogr' . "\xC3\xA1" . 'ficas para yates, marinas y experiencias de lujo, dise' . "\xC3\xB1" . 'adas para capturar elegancia, estilo de vida y momentos inolvidables.',
                    ],
                    [
                        'slug' => 'hospitality-lifestyle-brands',
                        'image_id' => 0,
                        'hero_image_id' => 0,
                        'title_en' => 'Hospitality & Lifestyle Brands',
                        'title_es' => 'Hospitalidad y Marcas de Estilo de Vida',
                        'hero_lead_en' => 'Content that turns visitors into',
                        'hero_accent_en' => 'guests who return',
                        'hero_lead_es' => 'Contenido que convierte visitantes en',
                        'hero_accent_es' => 'clientes que vuelven',
                        'description_en' => 'Cinematic visual content for hotels, restaurants, caf' . "\xC3\xA9" . 's and lifestyle brands designed to create memorable experiences and strengthen brand identity.',
                        'description_es' => 'Contenido visual cinematogr' . "\xC3\xA1" . 'fico para hoteles, restaurantes, cafeter' . "\xC3\xAD" . 'as y marcas de estilo de vida, dise' . "\xC3\xB1" . 'ado para crear experiencias memorables y fortalecer la identidad de marca.',
                    ],
                    [
                        'slug' => 'corporate-business-content',
                        'image_id' => 0,
                        'hero_image_id' => 0,
                        'title_en' => 'Corporate & Business Content',
                        'title_es' => 'Contenido Corporativo y de Negocios',
                        'hero_lead_en' => 'Professional content that makes your company',
                        'hero_accent_en' => 'look world-class',
                        'hero_lead_es' => 'Contenido profesional que hace que tu empresa',
                        'hero_accent_es' => 'se vea de primer nivel',
                        'description_en' => 'Professional visual content for companies, healthcare, corporate events and personal brands designed to build credibility, strengthen brand presence and connect with clients.',
                        'description_es' => 'Contenido visual profesional para empresas, salud, eventos corporativos y marcas personales, dise' . "\xC3\xB1" . 'ado para construir credibilidad, fortalecer la presencia de marca y conectar con tus clientes.',
                    ],
                ],
            ],
            
            'about' => [
                'eyebrow_en' => 'About CreativeWilds',
                'eyebrow_es' => 'Sobre CreativeWilds',
                'title_en' => 'From real estate and restaurants to yachts, hotels and corporate events',
                'title_es' => 'Desde bienes ra' . "\xC3\xAD" . 'ces y restaurantes hasta yates, hoteles y eventos corporativos',
                'body_en' => 'We help businesses stand out through cinematic photography, video production and strategic visual storytelling.',
                'body_es' => 'Ayudamos a las empresas a destacar con fotograf' . "\xC3\xAD" . 'a cinematogr' . "\xC3\xA1" . 'fica, producci' . "\xC3\xB3" . 'n de video y narrativa visual estrat' . "\xC3\xA9" . 'gica.',
                'chips_en' => [
                    'Aerial cinematography',
                    'Architecture & interiors',
                    'Brand films',
                    'Visual storytelling',
                ],
                'chips_es' => [
                    'Cinematograf' . "\xC3\xAD" . 'a a' . "\xC3\xA9" . 'rea',
                    'Arquitectura e interiores',
                    'Films de marca',
                    'Narrativa visual',
                ],
                'note_en' => 'Based in South Florida · Shooting on location across the region',
                'note_es' => 'Con base en el sur de Florida · Grabamos en locaci' . "\xC3\xB3" . 'n en toda la regi' . "\xC3\xB3" . 'n',
                'image_id' => 0,
            ],
            'gallery' => [
                'eyebrow_en' => 'Selected work',
                'eyebrow_es' => 'Trabajos seleccionados',
                'title_en' => 'Recent frames',
                'title_es' => 'Fotogramas recientes',
                'subtitle_en' => 'A glimpse of the visual language we shoot with. Full portfolio available on request.',
                'subtitle_es' => 'Una muestra del lenguaje visual con el que trabajamos. Portafolio completo disponible a solicitud.',
                'note_en' => 'Select any frame to view it full screen',
                'note_es' => 'Elige cualquier imagen para verla en pantalla completa',
                'items' => [
                    ['image_id' => 0, 'category_en' => 'Aerial & Drone', 'category_es' => 'A' . "\xC3\xA9" . 'reo y Dron'],
                    ['image_id' => 0, 'category_en' => 'Real Estate', 'category_es' => 'Bienes R' . "\xC3\xAD" . 'ces'],
                    ['image_id' => 0, 'category_en' => 'Interiors', 'category_es' => 'Interiores'],
                    ['image_id' => 0, 'category_en' => 'Yacht & Marine', 'category_es' => 'Yates y Marina'],
                    ['image_id' => 0, 'category_en' => 'Hospitality', 'category_es' => 'Hospitalidad'],
                    ['image_id' => 0, 'category_en' => 'Hospitality', 'category_es' => 'Hospitalidad'],
                ],
            ],
            'contact' => [
                'status_en' => 'Currently booking — South Florida',
                'status_es' => 'Agenda abierta — Sur de Florida',
                'heading_en' => 'Tell us what you want to shoot',
                'heading_es' => 'Cu' . "\xC3\xA9" . 'ntanos qu' . "\xC3\xA9" . ' quieres grabar',
                'subtitle_en' => 'Share a few details and we come back with ideas, availability and a quote.',
                'subtitle_es' => 'Comparte algunos detalles y te respondemos con ideas, disponibilidad y presupuesto.',
                'direct_title_en' => 'Or reach us directly',
                'direct_title_es' => 'O escr' . "\xC3\xAD" . 'benos directamente',
                'email' => 'hello@creativewilds.com',
                'phone' => '+1 (305) 555-0100',
                'whatsapp' => 'https://wa.me/13055550100',
                'recipient_email' => '',
                'image_id' => 0,
                'send_button_en' => 'Send inquiry',
                'send_button_es' => 'Enviar consulta',
                'sending_button_en' => 'Sending…',
                'sending_button_es' => 'Enviando…',
                'success_title_en' => 'Message received',
                'success_title_es' => 'Mensaje recibido',
                'success_message_en' => 'Thanks — we will get back to you within one business day.',
                'success_message_es' => 'Gracias — te contactaremos en un d' . "\xC3\xAD" . 'a h' . "\xC3\xA1" . 'bil.',
                'success_reset_en' => 'Send another inquiry',
                'success_reset_es' => 'Enviar otra consulta',
                'error_message_en' => 'Something went wrong sending your message. Please try the email or WhatsApp link instead.',
                'error_message_es' => 'Hubo un problema al enviar tu mensaje. Intenta escribirnos por email o WhatsApp.',
            ],
        ];
    }
}

if (!function_exists('dg_cw_normalize_short_text')) {
    function dg_cw_normalize_short_text($value)
    {
        return sanitize_text_field((string) $value);
    }
}

if (!function_exists('dg_cw_normalize_long_text')) {
    function dg_cw_normalize_long_text($value)
    {
        return sanitize_textarea_field((string) $value);
    }
}

if (!function_exists('dg_cw_normalize_lines')) {
    function dg_cw_normalize_lines($value, array $fallback = [])
    {
        if (is_array($value)) {
            $lines = $value;
        } else {
            $lines = preg_split('/\r\n|\r|\n/', (string) $value);
        }

        $normalized = [];
        foreach ($lines as $line) {
            $line = dg_cw_normalize_short_text($line);
            if ($line !== '') {
                $normalized[] = $line;
            }
        }

        return !empty($normalized) ? array_values($normalized) : $fallback;
    }
}

if (!function_exists('dg_cw_normalize_services')) {
    function dg_cw_normalize_services($items, array $fallback)
    {
        $normalized = [];

        foreach ((array) $items as $index => $item) {
            if (!is_array($item)) {
                continue;
            }

            $defaults = $fallback[$index] ?? $fallback[0];
            $title_en = dg_cw_normalize_short_text($item['title_en'] ?? $defaults['title_en']);
            $title_es = dg_cw_normalize_short_text($item['title_es'] ?? $defaults['title_es']);
            $description_en = dg_cw_normalize_long_text($item['description_en'] ?? $defaults['description_en']);
            $description_es = dg_cw_normalize_long_text($item['description_es'] ?? $defaults['description_es']);

            if ($title_en === '' && $title_es === '' && $description_en === '' && $description_es === '') {
                continue;
            }

            $normalized[] = [
                'slug' => sanitize_title((string) ($item['slug'] ?? $title_en ?: $title_es ?: $defaults['slug'])),
                'image_id' => absint($item['image_id'] ?? $defaults['image_id']),
                'hero_image_id' => absint($item['hero_image_id'] ?? $item['image_id'] ?? ($defaults['hero_image_id'] ?? $defaults['image_id'])),
                'title_en' => $title_en ?: $defaults['title_en'],
                'title_es' => $title_es ?: $defaults['title_es'],
                'hero_lead_en' => dg_cw_normalize_short_text($item['hero_lead_en'] ?? $defaults['hero_lead_en']) ?: $defaults['hero_lead_en'],
                'hero_accent_en' => dg_cw_normalize_short_text($item['hero_accent_en'] ?? $defaults['hero_accent_en']) ?: $defaults['hero_accent_en'],
                'hero_lead_es' => dg_cw_normalize_short_text($item['hero_lead_es'] ?? $defaults['hero_lead_es']) ?: $defaults['hero_lead_es'],
                'hero_accent_es' => dg_cw_normalize_short_text($item['hero_accent_es'] ?? $defaults['hero_accent_es']) ?: $defaults['hero_accent_es'],
                'description_en' => $description_en ?: $defaults['description_en'],
                'description_es' => $description_es ?: $defaults['description_es'],
            ];
        }

        return !empty($normalized) ? array_values($normalized) : $fallback;
    }
}

if (!function_exists('dg_cw_normalize_gallery_items')) {
    function dg_cw_normalize_gallery_items($items, array $fallback)
    {
        $normalized = [];

        foreach ((array) $items as $index => $item) {
            if (!is_array($item)) {
                continue;
            }

            $defaults = $fallback[$index] ?? $fallback[0];
            $category_en = dg_cw_normalize_short_text($item['category_en'] ?? $defaults['category_en']);
            $category_es = dg_cw_normalize_short_text($item['category_es'] ?? $defaults['category_es']);

            if ($category_en === '' && $category_es === '' && absint($item['image_id'] ?? 0) === 0) {
                continue;
            }

            $normalized[] = [
                'image_id' => absint($item['image_id'] ?? $defaults['image_id']),
                'category_en' => $category_en ?: $defaults['category_en'],
                'category_es' => $category_es ?: $defaults['category_es'],
            ];
        }

        return !empty($normalized) ? array_values($normalized) : $fallback;
    }
}

if (!function_exists('dg_cw_normalize_settings')) {
    function dg_cw_normalize_settings($settings)
    {
        $defaults = dg_cw_get_default_settings();
        $settings = is_array($settings) ? $settings : [];

        $general = is_array($settings['general'] ?? null) ? $settings['general'] : [];
        $hero = is_array($settings['hero'] ?? null) ? $settings['hero'] : [];
        $services = is_array($settings['services'] ?? null) ? $settings['services'] : [];
        $about = is_array($settings['about'] ?? null) ? $settings['about'] : [];
        $gallery = is_array($settings['gallery'] ?? null) ? $settings['gallery'] : [];
        $contact = is_array($settings['contact'] ?? null) ? $settings['contact'] : [];

        $recipient_email = sanitize_email($contact['recipient_email'] ?? '');
        if ($recipient_email === '') {
            $recipient_email = sanitize_email(get_option('admin_email'));
        }

        return [
            'general' => [
                'brand_name' => dg_cw_normalize_short_text($general['brand_name'] ?? $defaults['general']['brand_name']) ?: $defaults['general']['brand_name'],
                'location_en' => dg_cw_normalize_short_text($general['location_en'] ?? $defaults['general']['location_en']) ?: $defaults['general']['location_en'],
                'location_es' => dg_cw_normalize_short_text($general['location_es'] ?? $defaults['general']['location_es']) ?: $defaults['general']['location_es'],
            ],
            'hero' => [
                'primary_button_en' => dg_cw_normalize_short_text($hero['primary_button_en'] ?? $defaults['hero']['primary_button_en']) ?: $defaults['hero']['primary_button_en'],
                'primary_button_es' => dg_cw_normalize_short_text($hero['primary_button_es'] ?? $defaults['hero']['primary_button_es']) ?: $defaults['hero']['primary_button_es'],
                'secondary_button_en' => dg_cw_normalize_short_text($hero['secondary_button_en'] ?? $defaults['hero']['secondary_button_en']) ?: $defaults['hero']['secondary_button_en'],
                'secondary_button_es' => dg_cw_normalize_short_text($hero['secondary_button_es'] ?? $defaults['hero']['secondary_button_es']) ?: $defaults['hero']['secondary_button_es'],
            ],
            'services' => [
                'eyebrow_en' => dg_cw_normalize_short_text($services['eyebrow_en'] ?? $defaults['services']['eyebrow_en']) ?: $defaults['services']['eyebrow_en'],
                'eyebrow_es' => dg_cw_normalize_short_text($services['eyebrow_es'] ?? $defaults['services']['eyebrow_es']) ?: $defaults['services']['eyebrow_es'],
                'title_en' => dg_cw_normalize_short_text($services['title_en'] ?? $defaults['services']['title_en']) ?: $defaults['services']['title_en'],
                'title_es' => dg_cw_normalize_short_text($services['title_es'] ?? $defaults['services']['title_es']) ?: $defaults['services']['title_es'],
                'subtitle_en' => dg_cw_normalize_long_text($services['subtitle_en'] ?? $defaults['services']['subtitle_en']) ?: $defaults['services']['subtitle_en'],
                'subtitle_es' => dg_cw_normalize_long_text($services['subtitle_es'] ?? $defaults['services']['subtitle_es']) ?: $defaults['services']['subtitle_es'],
                'cta_en' => dg_cw_normalize_short_text($services['cta_en'] ?? $defaults['services']['cta_en']) ?: $defaults['services']['cta_en'],
                'cta_es' => dg_cw_normalize_short_text($services['cta_es'] ?? $defaults['services']['cta_es']) ?: $defaults['services']['cta_es'],
                'items' => dg_cw_normalize_services($services['items'] ?? [], $defaults['services']['items']),
            ],
            'about' => [
                'eyebrow_en' => dg_cw_normalize_short_text($about['eyebrow_en'] ?? $defaults['about']['eyebrow_en']) ?: $defaults['about']['eyebrow_en'],
                'eyebrow_es' => dg_cw_normalize_short_text($about['eyebrow_es'] ?? $defaults['about']['eyebrow_es']) ?: $defaults['about']['eyebrow_es'],
                'title_en' => dg_cw_normalize_short_text($about['title_en'] ?? $defaults['about']['title_en']) ?: $defaults['about']['title_en'],
                'title_es' => dg_cw_normalize_short_text($about['title_es'] ?? $defaults['about']['title_es']) ?: $defaults['about']['title_es'],
                'body_en' => dg_cw_normalize_long_text($about['body_en'] ?? $defaults['about']['body_en']) ?: $defaults['about']['body_en'],
                'body_es' => dg_cw_normalize_long_text($about['body_es'] ?? $defaults['about']['body_es']) ?: $defaults['about']['body_es'],
                'chips_en' => dg_cw_normalize_lines($about['chips_en'] ?? [], $defaults['about']['chips_en']),
                'chips_es' => dg_cw_normalize_lines($about['chips_es'] ?? [], $defaults['about']['chips_es']),
                'note_en' => dg_cw_normalize_short_text($about['note_en'] ?? $defaults['about']['note_en']) ?: $defaults['about']['note_en'],
                'note_es' => dg_cw_normalize_short_text($about['note_es'] ?? $defaults['about']['note_es']) ?: $defaults['about']['note_es'],
                'image_id' => absint($about['image_id'] ?? $defaults['about']['image_id']),
            ],
            'gallery' => [
                'eyebrow_en' => dg_cw_normalize_short_text($gallery['eyebrow_en'] ?? $defaults['gallery']['eyebrow_en']) ?: $defaults['gallery']['eyebrow_en'],
                'eyebrow_es' => dg_cw_normalize_short_text($gallery['eyebrow_es'] ?? $defaults['gallery']['eyebrow_es']) ?: $defaults['gallery']['eyebrow_es'],
                'title_en' => dg_cw_normalize_short_text($gallery['title_en'] ?? $defaults['gallery']['title_en']) ?: $defaults['gallery']['title_en'],
                'title_es' => dg_cw_normalize_short_text($gallery['title_es'] ?? $defaults['gallery']['title_es']) ?: $defaults['gallery']['title_es'],
                'subtitle_en' => dg_cw_normalize_long_text($gallery['subtitle_en'] ?? $defaults['gallery']['subtitle_en']) ?: $defaults['gallery']['subtitle_en'],
                'subtitle_es' => dg_cw_normalize_long_text($gallery['subtitle_es'] ?? $defaults['gallery']['subtitle_es']) ?: $defaults['gallery']['subtitle_es'],
                'note_en' => dg_cw_normalize_short_text($gallery['note_en'] ?? $defaults['gallery']['note_en']) ?: $defaults['gallery']['note_en'],
                'note_es' => dg_cw_normalize_short_text($gallery['note_es'] ?? $defaults['gallery']['note_es']) ?: $defaults['gallery']['note_es'],
                'items' => dg_cw_normalize_gallery_items($gallery['items'] ?? [], $defaults['gallery']['items']),
            ],
            'contact' => [
                'status_en' => dg_cw_normalize_short_text($contact['status_en'] ?? $defaults['contact']['status_en']) ?: $defaults['contact']['status_en'],
                'status_es' => dg_cw_normalize_short_text($contact['status_es'] ?? $defaults['contact']['status_es']) ?: $defaults['contact']['status_es'],
                'heading_en' => dg_cw_normalize_short_text($contact['heading_en'] ?? $defaults['contact']['heading_en']) ?: $defaults['contact']['heading_en'],
                'heading_es' => dg_cw_normalize_short_text($contact['heading_es'] ?? $defaults['contact']['heading_es']) ?: $defaults['contact']['heading_es'],
                'subtitle_en' => dg_cw_normalize_long_text($contact['subtitle_en'] ?? $defaults['contact']['subtitle_en']) ?: $defaults['contact']['subtitle_en'],
                'subtitle_es' => dg_cw_normalize_long_text($contact['subtitle_es'] ?? $defaults['contact']['subtitle_es']) ?: $defaults['contact']['subtitle_es'],
                'direct_title_en' => dg_cw_normalize_short_text($contact['direct_title_en'] ?? $defaults['contact']['direct_title_en']) ?: $defaults['contact']['direct_title_en'],
                'direct_title_es' => dg_cw_normalize_short_text($contact['direct_title_es'] ?? $defaults['contact']['direct_title_es']) ?: $defaults['contact']['direct_title_es'],
                'email' => sanitize_email($contact['email'] ?? $defaults['contact']['email']) ?: $defaults['contact']['email'],
                'phone' => dg_cw_normalize_short_text($contact['phone'] ?? $defaults['contact']['phone']) ?: $defaults['contact']['phone'],
                'whatsapp' => esc_url_raw((string) ($contact['whatsapp'] ?? $defaults['contact']['whatsapp'])) ?: $defaults['contact']['whatsapp'],
                'recipient_email' => $recipient_email,
                'image_id' => absint($contact['image_id'] ?? $defaults['contact']['image_id']),
                'send_button_en' => dg_cw_normalize_short_text($contact['send_button_en'] ?? $defaults['contact']['send_button_en']) ?: $defaults['contact']['send_button_en'],
                'send_button_es' => dg_cw_normalize_short_text($contact['send_button_es'] ?? $defaults['contact']['send_button_es']) ?: $defaults['contact']['send_button_es'],
                'sending_button_en' => dg_cw_normalize_short_text($contact['sending_button_en'] ?? $defaults['contact']['sending_button_en']) ?: $defaults['contact']['sending_button_en'],
                'sending_button_es' => dg_cw_normalize_short_text($contact['sending_button_es'] ?? $defaults['contact']['sending_button_es']) ?: $defaults['contact']['sending_button_es'],
                'success_title_en' => dg_cw_normalize_short_text($contact['success_title_en'] ?? $defaults['contact']['success_title_en']) ?: $defaults['contact']['success_title_en'],
                'success_title_es' => dg_cw_normalize_short_text($contact['success_title_es'] ?? $defaults['contact']['success_title_es']) ?: $defaults['contact']['success_title_es'],
                'success_message_en' => dg_cw_normalize_long_text($contact['success_message_en'] ?? $defaults['contact']['success_message_en']) ?: $defaults['contact']['success_message_en'],
                'success_message_es' => dg_cw_normalize_long_text($contact['success_message_es'] ?? $defaults['contact']['success_message_es']) ?: $defaults['contact']['success_message_es'],
                'success_reset_en' => dg_cw_normalize_short_text($contact['success_reset_en'] ?? $defaults['contact']['success_reset_en']) ?: $defaults['contact']['success_reset_en'],
                'success_reset_es' => dg_cw_normalize_short_text($contact['success_reset_es'] ?? $defaults['contact']['success_reset_es']) ?: $defaults['contact']['success_reset_es'],
                'error_message_en' => dg_cw_normalize_long_text($contact['error_message_en'] ?? $defaults['contact']['error_message_en']) ?: $defaults['contact']['error_message_en'],
                'error_message_es' => dg_cw_normalize_long_text($contact['error_message_es'] ?? $defaults['contact']['error_message_es']) ?: $defaults['contact']['error_message_es'],
            ],
        ];
    }
}

if (!function_exists('dg_cw_get_settings')) {
    function dg_cw_get_settings()
    {
        static $settings = null;

        if ($settings !== null) {
            return $settings;
        }

        $stored = get_option('dg_cw_settings', []);
        $settings = dg_cw_normalize_settings($stored);

        return $settings;
    }
}

if (!function_exists('dg_cw_get_lang_query_var')) {
    function dg_cw_get_lang_query_var()
    {
        return 'dg_cw_lang';
    }
}

if (!function_exists('dg_cw_get_lang_cookie_name')) {
    function dg_cw_get_lang_cookie_name()
    {
        return 'dg_cw_lang';
    }
}

if (!function_exists('dg_cw_normalize_lang_code')) {
    function dg_cw_normalize_lang_code($lang = '')
    {
        $lang = strtolower(substr((string) $lang, 0, 2));

        return in_array($lang, ['en', 'es'], true) ? $lang : '';
    }
}

if (!function_exists('dg_cw_resolve_lang')) {
    function dg_cw_resolve_lang($lang = '')
    {
        $lang = dg_cw_normalize_lang_code($lang);
        if ($lang !== '') {
            return $lang;
        }

        $query_var = dg_cw_get_lang_query_var();
        $request_lang = dg_cw_normalize_lang_code(wp_unslash($_GET[$query_var] ?? ''));
        if ($request_lang !== '') {
            return $request_lang;
        }

        $cookie_name = dg_cw_get_lang_cookie_name();
        $cookie_lang = dg_cw_normalize_lang_code(wp_unslash($_COOKIE[$cookie_name] ?? ''));
        if ($cookie_lang !== '') {
            return $cookie_lang;
        }

        $locale = function_exists('determine_locale') ? determine_locale() : get_locale();

        return stripos((string) $locale, 'es_') === 0 ? 'es' : 'en';
    }
}

if (!function_exists('dg_cw_resolve_frontend_lang')) {
    function dg_cw_resolve_frontend_lang($fallback_lang = '')
    {
        $query_var = dg_cw_get_lang_query_var();
        $request_lang = dg_cw_normalize_lang_code(wp_unslash($_GET[$query_var] ?? ''));
        if ($request_lang !== '') {
            return $request_lang;
        }

        $cookie_name = dg_cw_get_lang_cookie_name();
        $cookie_lang = dg_cw_normalize_lang_code(wp_unslash($_COOKIE[$cookie_name] ?? ''));
        if ($cookie_lang !== '') {
            return $cookie_lang;
        }

        return dg_cw_resolve_lang($fallback_lang);
    }
}

if (!function_exists('dg_cw_get_translated_value')) {
    function dg_cw_get_translated_value(array $values, $key, $lang, $fallback = '')
    {
        $lang = dg_cw_resolve_lang($lang);
        $localized_key = $key . '_' . $lang;
        if (isset($values[$localized_key]) && $values[$localized_key] !== '') {
            return $values[$localized_key];
        }

        $fallback_key = $key . '_en';
        if (isset($values[$fallback_key]) && $values[$fallback_key] !== '') {
            return $values[$fallback_key];
        }

        return $fallback;
    }
}

if (!function_exists('dg_cw_get_active_services')) {
    function dg_cw_get_active_services(array $settings = [])
    {
        if (empty($settings)) {
            $settings = dg_cw_get_settings();
        }

        return array_values(array_filter(
            $settings['services']['items'] ?? [],
            static function ($item) {
                return !empty($item['title_en']) || !empty($item['title_es']);
            }
        ));
    }
}
