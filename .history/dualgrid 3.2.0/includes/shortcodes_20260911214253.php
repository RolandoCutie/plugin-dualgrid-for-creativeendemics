<?php
/* ============================================
   DUALGRID - SHORTCODE TOURS CARDS
   Agregar al final de includes/shortcodes.php
   ============================================ */

if (!defined('ABSPATH'))
    exit;

if (!function_exists('dg_get_site_logo_url')) {
    function dg_get_site_logo_url()
    {
        return 'https://palegreen-herring-696641.hostingersite.com/wp-content/uploads/2026/08/WE_EXCLUSIVE_Shell_Color_LOGO-SLOGAN-copy.svg';
    }
}

if (!function_exists('dg_format_price')) {
    function dg_format_price($price)
    {
        $price = is_string($price) ? trim($price) : $price;

        if ($price === '' || $price === null || $price === false) {
            return '';
        }

        if (preg_match('/[$€£¢]/u', (string) $price)) {
            return (string) $price;
        }

        $normalized = str_replace([' ', ','], '', (string) $price);

        if (is_numeric($normalized)) {
            $number = (float) $normalized;

            if (floor($number) === $number) {
                return '$' . number_format($number, 0, '.', ',');
            }

            return '$' . number_format($number, 2, '.', ',');
        }

        return '$' . (string) $price;
    }
}

if (!function_exists('dg_translate_tour_value')) {
    function dg_translate_tour_value($value, $context = '')
    {
        if (!is_string($value)) {
            return $value;
        }

        $value = trim($value);

        if ($value === '') {
            return '';
        }

        $exact = [
            'Fácil' => 'Easy',
            'Moderado' => 'Moderate',
            'Avanzado' => 'Advanced',
            'Fácil-Moderado' => 'Easy to Moderate',
            'Fácil–Moderado' => 'Easy to Moderate',
            'Moderado-Avanzado' => 'Moderate to Advanced',
            'Moderado–Avanzado' => 'Moderate to Advanced',
            'Easy-Moderate' => 'Easy to Moderate',
            'Easy–Moderate' => 'Easy to Moderate',
            'Moderate-Advanced' => 'Moderate to Advanced',
            'Moderate–Advanced' => 'Moderate to Advanced',
            'Inglés y español' => 'English and Spanish',
            'English & Spanish' => 'English and Spanish',
            'CEO y guía principal de observación de aves' => 'CEO and lead birding guide',
            'CEO & Lead Birding Guide' => 'CEO and lead birding guide',
            'Guía de observación de aves' => 'Birding guide',
            'Birding Guide' => 'Birding guide',
            'Guía de fotografía' => 'Photography guide',
            'Photography Guide' => 'Photography guide',
            'Guía principal de observación de aves' => 'Lead birding guide',
            'Lead Birding Guide' => 'Lead birding guide',
            'Tour para grupo pequeño (máx. 6)' => 'Small group tour (max. 6)',
            'Small group tour (max 6)' => 'Small group tour (max. 6)',
            'Vehículo privado 4x4 y traslado de montaña' => 'Private 4x4 vehicle and mountain transfer',
            'Private 4x4 vehicle and mountain transfer' => 'Private 4x4 vehicle and mountain transfer',
            'Eco-lodge y estación de investigación' => 'Eco-lodge and research station',
            'Eco-Lodge & Research Station' => 'Eco-lodge and research station',
            'Disponible' => 'Open',
            'Open' => 'Open',
        ];

        if (isset($exact[$value])) {
            $value = $exact[$value];
        }

        if ($context === 'group' && is_numeric($value)) {
            return 'Max. ' . $value;
        }

        $patterns = [
            '/\bEnero\b/iu' => 'January',
            '/\bFebrero\b/iu' => 'February',
            '/\bMarzo\b/iu' => 'March',
            '/\bAbril\b/iu' => 'April',
            '/\bMayo\b/iu' => 'May',
            '/\bJunio\b/iu' => 'June',
            '/\bJulio\b/iu' => 'July',
            '/\bAgosto\b/iu' => 'August',
            '/\bSeptiembre\b/iu' => 'September',
            '/\bOctubre\b/iu' => 'October',
            '/\bNoviembre\b/iu' => 'November',
            '/\bDiciembre\b/iu' => 'December',
            '/\bEne\b/iu' => 'Jan',
            '/\bAbr\b/iu' => 'Apr',
            '/\bAgo\b/iu' => 'Aug',
            '/\bDic\b/iu' => 'Dec',
            '/\bdías\b/iu' => 'days',
            '/\bdía\b/iu' => 'day',
            '/\bnoches\b/iu' => 'nights',
            '/\bnoche\b/iu' => 'night',
            '/\bInglés\b/iu' => 'English',
            '/\bEspañol\b/iu' => 'Spanish',
            '/\bpersonas\b/iu' => 'people',
            '/\bpersona\b/iu' => 'person',
            '/\bcupos disponibles\b/iu' => 'spots available',
            '/\bHasta\b/iu' => 'Up to',
            '/\bcaminatas diarias de\b/iu' => 'daily walks of',
            '/\bprivado\b/iu' => 'private',
            '/\bvehículo\b/iu' => 'vehicle',
            '/\btraslado de montaña\b/iu' => 'mountain transfer',
        ];

        foreach ($patterns as $pattern => $replacement) {
            $value = preg_replace($pattern, $replacement, $value);
        }

        $value = preg_replace('/^Máx\.?\s*/iu', 'Max. ', $value);
        $value = preg_replace('/\s*&\s*/', ' and ', $value);
        $value = preg_replace('/\s+y\s+/iu', ' and ', $value);

        return trim($value);
    }
}

if (!function_exists('dg_get_tour_meta_icon')) {
    function dg_get_tour_meta_icon($type)
    {
        switch ($type) {
            case 'duration':
                return '<svg class="we-program-meta__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>';
            case 'group':
                return '<svg class="we-program-meta__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>';
            case 'difficulty':
                return '<svg class="we-program-meta__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg>';
            case 'price':
                return '<svg class="we-program-meta__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="12" y1="2" x2="12" y2="22"></line><path d="M17 6H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>';
            case 'location':
                return '<svg class="we-program-meta__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
            case 'dates':
                return '<svg class="we-program-meta__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>';
            default:
                return '';
        }
    }
}

if (!function_exists('dg_clean_tour_finder_text')) {
    function dg_clean_tour_finder_text($value)
    {
        if (!is_scalar($value)) {
            return '';
        }

        $text = html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = wp_strip_all_tags($text);
        $text = preg_replace('/^\s*#+\s*/u', '', $text);
        $text = preg_replace('/\s+/u', ' ', $text);

        return trim((string) $text);
    }
}

if (!function_exists('dg_get_tour_finder_main_focus_options')) {
    function dg_get_tour_finder_main_focus_options()
    {
        return [
            1 => ['id' => 1, 'slug' => 'birding', 'label' => 'Birding'],
            2 => ['id' => 2, 'slug' => 'wildlife-photography', 'label' => 'Wildlife Photography'],
            3 => ['id' => 3, 'slug' => 'herping', 'label' => 'Herping'],
            4 => ['id' => 4, 'slug' => 'hiking', 'label' => 'Hiking'],
            5 => ['id' => 5, 'slug' => 'nature-culture', 'label' => 'Nature & Culture'],
            6 => ['id' => 6, 'slug' => 'snails-bugs', 'label' => 'Snails & Bugs'],
        ];
    }
}

if (!function_exists('dg_get_tour_finder_region_options')) {
    function dg_get_tour_finder_region_options()
    {
        return [
            1 => ['id' => 1, 'slug' => 'cuba', 'label' => 'Cuba'],
            2 => ['id' => 2, 'slug' => 'western-cuba', 'label' => 'Western Cuba'],
            3 => ['id' => 3, 'slug' => 'eastern-cuba', 'label' => 'Eastern Cuba'],
        ];
    }
}

if (!function_exists('dg_get_tour_finder_duration_options')) {
    function dg_get_tour_finder_duration_options()
    {
        return [
            1 => ['id' => 1, 'label' => 'Short (1-5 days)'],
            2 => ['id' => 2, 'label' => 'Mid-range (7-11 days)'],
            3 => ['id' => 3, 'label' => 'Long (12+ days)'],
        ];
    }
}

if (!function_exists('dg_get_tour_finder_status_options')) {
    function dg_get_tour_finder_status_options()
    {
        return [
            'available' => ['value' => 'available', 'label' => 'Available'],
            'spots' => ['value' => 'spots', 'label' => 'Spots'],
            'full' => ['value' => 'full', 'label' => 'Full'],
        ];
    }
}

if (!function_exists('dg_get_tour_finder_program_overrides')) {
    function dg_get_tour_finder_program_overrides()
    {
        return [
            'birding' => [
                'description' => 'Birding departures built around endemics, migration windows and high-value observation time.',
                'image' => 'https://palegreen-herring-696641.hostingersite.com/wp-content/uploads/2026/09/00-home-1-Birding-scaled.jpg',
                'link' => '/tours/birding-tours/',
            ],
            'wildlife-photography' => [
                'description' => 'Trips tailored around hides, light, behaviour, timing and field photography support.',
                'image' => 'https://palegreen-herring-696641.hostingersite.com/wp-content/uploads/2026/09/00-home-3-Wildlife-Photography-scaled.jpg',
                'link' => '/tours/wildlife-photography-tours/',
            ],
            'herping' => [
                'description' => 'Programs focused on reptiles, amphibians and nocturnal field sessions in the right habitats.',
                'image' => 'https://palegreen-herring-696641.hostingersite.com/wp-content/uploads/2026/09/00-home-4-Herping-tours-scaled.jpg',
                'link' => '/tours/herping-tours/',
            ],
            'hiking' => [
                'description' => 'Route-driven journeys for travelers who want trail time, elevation and active days outdoors.',
                'image' => 'https://palegreen-herring-696641.hostingersite.com/wp-content/uploads/2026/09/00-home-2-Hikes-Treks.jpeg',
                'link' => '/tours/hiking-tours/',
            ],
            'nature-culture' => [
                'description' => 'Balanced itineraries that combine landscapes, biodiversity, communities and local culture.',
                'image' => 'https://palegreen-herring-696641.hostingersite.com/wp-content/uploads/2026/09/00-home-5-Nature-Culture.jpeg',
                'link' => '/tours/nature-culture-tours/',
            ],
            'snails-bugs' => [
                'description' => 'Special interest programs for invertebrates, macro encounters and overlooked biodiversity.',
                'image' => 'https://palegreen-herring-696641.hostingersite.com/wp-content/uploads/2026/09/033-snail-east.jpeg',
                'link' => '/tours/snails-bugs-tours/',
            ],
        ];
    }
}

if (!function_exists('dg_get_tailor_made_cf7_id')) {
    function dg_get_tailor_made_cf7_id()
    {
        return '6f7a331';
    }
}

if (!function_exists('dg_get_booking_cf7_id')) {
    function dg_get_booking_cf7_id()
    {
        return '29aa099';
    }
}

if (!function_exists('dg_normalize_cf7_id')) {
    function dg_normalize_cf7_id($value)
    {
        if (!is_scalar($value)) {
            return '';
        }

        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }

        if (stripos($value, '[contact-form-7') !== false) {
            if (preg_match('/\bid\s*=\s*"([^"]+)"/i', $value, $matches)) {
                $value = trim($matches[1]);
            } elseif (preg_match('/\bid\s*=\s*\'([^\']+)\'/i', $value, $matches)) {
                $value = trim($matches[1]);
            }
        }

        return preg_replace('/[^A-Za-z0-9_-]/', '', $value);
    }
}

if (!function_exists('dg_get_tour_finder_tour_overrides')) {
    function dg_get_tour_finder_tour_overrides()
    {
        return [
            // Replace link/image/status for each official Tour Finder result here.
            'zapata-wetland-birding-tour' => ['link' => '#', 'image' => '', 'status' => 'available'],
            'birding-escape-in-cuba' => ['link' => '#', 'image' => '', 'status' => 'available'],
            '7-day-cuba-birding-essentials' => ['link' => '#', 'image' => '', 'status' => 'available'],
            'cuba-birding-tour-endemics-migration' => ['link' => '#', 'image' => '', 'status' => 'available'],
            'grand-birding-tour-cuban-endemics-habitats' => ['link' => '#', 'image' => '', 'status' => 'available'],
            'wild-cuba-reptiles-amphibians-tour' => ['link' => '#', 'image' => '', 'status' => 'available'],
            'herp-watching-in-eastern-cuba' => ['link' => '#', 'image' => '', 'status' => 'available'],
            'cuban-nature-adventure-birds-waterfalls' => ['link' => '#', 'image' => '', 'status' => 'available'],
            'eastern-cuba-discovery-natural-wonders' => ['link' => '#', 'image' => '', 'status' => 'available'],
            'cuba-wild-odyssey-tour' => ['link' => '#', 'image' => '', 'status' => 'available'],
            'bird-photography-expedition-cuban-endemics-caribbean-specialties-tour' => ['link' => '#', 'image' => '', 'status' => 'available'],
            'polymita-photography-expedition-cubas-painted-tree-snails' => ['link' => '#', 'image' => '', 'status' => 'available'],
            'cuba-land-snails-photography-workshop' => ['link' => '#', 'image' => '', 'status' => 'available'],
            'eastern-cuba-hiking-trails-heritage' => ['link' => '#', 'image' => '', 'status' => 'available'],
            'eastern-cuba-trek-rainforest-peaks' => ['link' => '#', 'image' => '', 'status' => 'available'],
            'western-cuba-hiking-nature-culture' => ['link' => '#', 'image' => '', 'status' => 'available'],
            'cuba-grand-trek-mountains-valleys' => ['link' => '#', 'image' => '', 'status' => 'available'],
        ];
    }
}

if (!function_exists('dg_get_tour_finder_official_catalog_seed')) {
    function dg_get_tour_finder_official_catalog_seed()
    {
        return [
            [
                'slug' => 'zapata-wetland-birding-tour',
                'title' => 'Zapata Wetland Birding Tour - Bee Hummingbird & Waterbirds',
                'description' => 'A short western Cuba birding departure focused on Zapata wetlands, hummingbirds and waterbirds.',
                'duration' => '3 Days',
                'durationBand' => 1,
                'mainFocus' => [
                    ['categoryId' => 1, 'priority' => 2],
                    ['categoryId' => 2, 'priority' => 3],
                ],
                'regions' => [1, 2],
                'programSlug' => 'birding',
            ],
            [
                'slug' => 'birding-escape-in-cuba',
                'title' => 'Birding Escape in Cuba - Endemics Highlights',
                'description' => 'A short western Cuba birding trip built around endemic highlights and efficient observation time.',
                'duration' => '5 Days',
                'durationBand' => 1,
                'mainFocus' => [
                    ['categoryId' => 1, 'priority' => 2],
                    ['categoryId' => 2, 'priority' => 3],
                ],
                'regions' => [1, 2],
                'programSlug' => 'birding',
            ],
            [
                'slug' => '7-day-cuba-birding-essentials',
                'title' => '7 Day Cuba Birding Essentials - Endemics Introduction Tour',
                'description' => 'A mid-range introduction to Cuba birding with endemic targets and photographic opportunities.',
                'duration' => '7 Days',
                'durationBand' => 2,
                'mainFocus' => [
                    ['categoryId' => 1, 'priority' => 2],
                    ['categoryId' => 2, 'priority' => 3],
                ],
                'regions' => [1, 2],
                'programSlug' => 'birding',
            ],
            [
                'slug' => 'cuba-birding-tour-endemics-migration',
                'title' => 'Cuba Birding Tour - Endemics & Migration',
                'description' => 'A strongly specialised western Cuba birding itinerary for endemics and migration periods.',
                'duration' => '10 Days',
                'durationBand' => 2,
                'mainFocus' => [
                    ['categoryId' => 1, 'priority' => 1],
                    ['categoryId' => 2, 'priority' => 3],
                ],
                'regions' => [1, 2],
                'programSlug' => 'birding',
            ],
            [
                'slug' => 'grand-birding-tour-cuban-endemics-habitats',
                'title' => 'Grand Birding Tour - Cuban Endemics & Habitats',
                'description' => 'A long-form Cuba birding route across habitats with a broader, slower pace.',
                'duration' => '14 Days',
                'durationBand' => 3,
                'mainFocus' => [
                    ['categoryId' => 1, 'priority' => 3],
                    ['categoryId' => 2, 'priority' => 3],
                ],
                'regions' => [1, 2],
                'programSlug' => 'birding',
            ],
            [
                'slug' => 'wild-cuba-reptiles-amphibians-tour',
                'title' => 'Wild Cuba: Reptiles & Amphibians Tour',
                'description' => 'A long western Cuba route focused on reptiles, amphibians and supporting wildlife photography.',
                'duration' => '12 Days',
                'durationBand' => 3,
                'mainFocus' => [
                    ['categoryId' => 2, 'priority' => 2],
                    ['categoryId' => 3, 'priority' => 1],
                ],
                'regions' => [1, 2],
                'programSlug' => 'herping',
            ],
            [
                'slug' => 'herp-watching-in-eastern-cuba',
                'title' => 'Herp-watching in Eastern Cuba',
                'description' => 'A long eastern Cuba tour with strong herping focus and complementary wildlife photography.',
                'duration' => '14 Days',
                'durationBand' => 3,
                'mainFocus' => [
                    ['categoryId' => 2, 'priority' => 2],
                    ['categoryId' => 3, 'priority' => 1],
                ],
                'regions' => [1, 3],
                'programSlug' => 'herping',
            ],
            [
                'slug' => 'cuban-nature-adventure-birds-waterfalls',
                'title' => 'Cuban Nature Adventure - Birds & Waterfalls',
                'description' => 'A mid-range western Cuba itinerary where Nature & Culture leads over birding and photography support.',
                'duration' => '10 Days',
                'durationBand' => 2,
                'mainFocus' => [
                    ['categoryId' => 1, 'priority' => 3],
                    ['categoryId' => 2, 'priority' => 3],
                    ['categoryId' => 5, 'priority' => 1],
                ],
                'regions' => [1, 2],
                'programSlug' => 'nature-culture',
            ],
            [
                'slug' => 'eastern-cuba-discovery-natural-wonders',
                'title' => 'Eastern Cuba Discovery - Natural Wonders',
                'description' => 'A long eastern Cuba departure where Nature & Culture is the top driver and birding remains supportive.',
                'duration' => '14 Days',
                'durationBand' => 3,
                'mainFocus' => [
                    ['categoryId' => 1, 'priority' => 3],
                    ['categoryId' => 2, 'priority' => 3],
                    ['categoryId' => 5, 'priority' => 1],
                ],
                'regions' => [1, 3],
                'programSlug' => 'nature-culture',
            ],
            [
                'slug' => 'cuba-wild-odyssey-tour',
                'title' => 'Cuba Wild Odyssey Tour',
                'description' => 'A broad multi-interest Cuba expedition that can support many searches without outranking specialised tours by default.',
                'duration' => '21 Days',
                'durationBand' => 3,
                'mainFocus' => [
                    ['categoryId' => 1, 'priority' => 3],
                    ['categoryId' => 2, 'priority' => 3],
                    ['categoryId' => 3, 'priority' => 3],
                    ['categoryId' => 4, 'priority' => 3],
                    ['categoryId' => 5, 'priority' => 3],
                    ['categoryId' => 6, 'priority' => 3],
                ],
                'regions' => [1, 2, 3],
                'programSlug' => 'nature-culture',
            ],
            [
                'slug' => 'bird-photography-expedition-cuban-endemics-caribbean-specialties-tour',
                'title' => 'Bird Photography Expedition - Cuban Endemics & Caribbean Specialties Tour',
                'description' => 'A mid-range western Cuba tour where wildlife photography is the lead focus and birding is secondary.',
                'duration' => '10 Days',
                'durationBand' => 2,
                'mainFocus' => [
                    ['categoryId' => 1, 'priority' => 3],
                    ['categoryId' => 2, 'priority' => 1],
                ],
                'regions' => [1, 2],
                'programSlug' => 'wildlife-photography',
            ],
            [
                'slug' => 'polymita-photography-expedition-cubas-painted-tree-snails',
                'title' => 'Polymita Photography Expedition - Cuba\'s Painted Tree Snails',
                'description' => 'A mid-range eastern Cuba tour strongly aligned with wildlife photography and snails and bugs.',
                'duration' => '10 Days',
                'durationBand' => 2,
                'mainFocus' => [
                    ['categoryId' => 2, 'priority' => 1],
                    ['categoryId' => 5, 'priority' => 3],
                    ['categoryId' => 6, 'priority' => 2],
                ],
                'regions' => [1, 3],
                'programSlug' => 'wildlife-photography',
            ],
            [
                'slug' => 'cuba-land-snails-photography-workshop',
                'title' => 'Cuba Land Snails - Photography Workshop',
                'description' => 'A mid-range western Cuba specialty route for land snails with a strong photography component.',
                'duration' => '8 Days',
                'durationBand' => 2,
                'mainFocus' => [
                    ['categoryId' => 2, 'priority' => 3],
                    ['categoryId' => 5, 'priority' => 3],
                    ['categoryId' => 6, 'priority' => 1],
                ],
                'regions' => [1, 2],
                'programSlug' => 'snails-bugs',
            ],
            [
                'slug' => 'eastern-cuba-hiking-trails-heritage',
                'title' => 'Eastern Cuba Hiking - Trails & Heritage',
                'description' => 'A mid-range eastern Cuba hiking route with cultural context and high hiking relevance.',
                'duration' => '10 Days',
                'durationBand' => 2,
                'mainFocus' => [
                    ['categoryId' => 4, 'priority' => 1],
                    ['categoryId' => 5, 'priority' => 3],
                ],
                'regions' => [1, 3],
                'programSlug' => 'hiking',
            ],
            [
                'slug' => 'eastern-cuba-trek-rainforest-peaks',
                'title' => 'Eastern Cuba Trek - Rainforest & Peaks',
                'description' => 'A long eastern Cuba trek focused specifically on hiking and landscape progression.',
                'duration' => '14 Days',
                'durationBand' => 3,
                'mainFocus' => [
                    ['categoryId' => 4, 'priority' => 3],
                ],
                'regions' => [1, 3],
                'programSlug' => 'hiking',
            ],
            [
                'slug' => 'western-cuba-hiking-nature-culture',
                'title' => 'Western Cuba Hiking - Nature & Culture',
                'description' => 'A mid-range western Cuba route that balances hiking with nature and cultural context.',
                'duration' => '10 Days',
                'durationBand' => 2,
                'mainFocus' => [
                    ['categoryId' => 4, 'priority' => 3],
                    ['categoryId' => 5, 'priority' => 3],
                ],
                'regions' => [1, 2],
                'programSlug' => 'hiking',
            ],
            [
                'slug' => 'cuba-grand-trek-mountains-valleys',
                'title' => 'Cuba Grand Trek - Mountains & Valleys',
                'description' => 'A long western Cuba hiking trip with stronger hiking weight than the broader mixed departures.',
                'duration' => '14 Days',
                'durationBand' => 3,
                'mainFocus' => [
                    ['categoryId' => 4, 'priority' => 2],
                ],
                'regions' => [1, 2],
                'programSlug' => 'hiking',
            ],
        ];
    }
}

if (!function_exists('dg_map_legacy_category_to_focus_id')) {
    function dg_map_legacy_category_to_focus_id($slug, $name = '')
    {
        $slug = sanitize_title((string) $slug);
        $name = sanitize_title((string) $name);

        if (in_array($slug, ['birding'], true) || in_array($name, ['birding'], true)) {
            return 1;
        }

        if (in_array($slug, ['wildlife', 'photography', 'wildlife-photography'], true) || in_array($name, ['wildlife', 'photography', 'wildlife-photography'], true)) {
            return 2;
        }

        if (in_array($slug, ['herping', 'herpetology', 'reptiles', 'amphibians'], true) || in_array($name, ['herping', 'herpetology', 'reptiles', 'amphibians'], true)) {
            return 3;
        }

        if (in_array($slug, ['hiking', 'senderismo'], true) || in_array($name, ['hiking', 'senderismo'], true)) {
            return 4;
        }

        if (in_array($slug, ['nature', 'culture', 'nature-culture', 'cultural'], true) || in_array($name, ['nature', 'culture', 'nature-culture', 'cultural'], true)) {
            return 5;
        }

        if (in_array($slug, ['snails-bugs', 'snails', 'bugs', 'insects'], true) || in_array($name, ['snails-bugs', 'snails', 'bugs', 'insects'], true)) {
            return 6;
        }

        return 0;
    }
}

if (!function_exists('dg_normalize_tour_main_focus')) {
    function dg_normalize_tour_main_focus($value, $legacy_terms = [])
    {
        $normalized = [];

        if (is_array($value)) {
            foreach ($value as $item) {
                if (!is_array($item)) {
                    continue;
                }

                if (array_key_exists('enabled', $item)) {
                    $enabled = $item['enabled'];
                    if ($enabled !== '1' && $enabled !== 1 && $enabled !== true && $enabled !== 'on') {
                        continue;
                    }
                }

                $category_id = isset($item['categoryId']) ? (int) $item['categoryId'] : 0;
                $priority = isset($item['priority']) ? (int) $item['priority'] : 0;

                if ($category_id < 1 || $category_id > 6 || $priority < 1 || $priority > 3) {
                    continue;
                }

                $normalized[$category_id] = [
                    'categoryId' => $category_id,
                    'priority' => $priority,
                ];
            }
        }

        if (empty($normalized) && is_array($legacy_terms)) {
            foreach ($legacy_terms as $term) {
                $focus_id = 0;

                if (is_object($term)) {
                    $focus_id = dg_map_legacy_category_to_focus_id($term->slug ?? '', $term->name ?? '');
                } elseif (is_string($term)) {
                    $focus_id = dg_map_legacy_category_to_focus_id($term, $term);
                }

                if ($focus_id > 0 && !isset($normalized[$focus_id])) {
                    $normalized[$focus_id] = [
                        'categoryId' => $focus_id,
                        'priority' => 1,
                    ];
                }
            }
        }

        $normalized = array_values($normalized);

        usort($normalized, function ($a, $b) {
            if ($a['priority'] === $b['priority']) {
                return $a['categoryId'] <=> $b['categoryId'];
            }

            return $a['priority'] <=> $b['priority'];
        });

        return $normalized;
    }
}

if (!function_exists('dg_normalize_tour_regions')) {
    function dg_normalize_tour_regions($value, $locations = [])
    {
        $normalized = [];

        if (is_array($value)) {
            foreach ($value as $region_id) {
                $region_id = (int) $region_id;
                if ($region_id >= 1 && $region_id <= 3) {
                    $normalized[$region_id] = $region_id;
                }
            }
        }

        if (empty($normalized) && is_array($locations)) {
            foreach ($locations as $location) {
                if (!is_array($location)) {
                    continue;
                }

                $haystack = strtolower(trim(($location['name'] ?? '') . ' ' . ($location['region'] ?? '') . ' ' . ($location['description'] ?? '')));

                if ($haystack === '') {
                    continue;
                }

                if (strpos($haystack, 'western cuba') !== false) {
                    $normalized[2] = 2;
                    $normalized[1] = 1;
                }

                if (strpos($haystack, 'eastern cuba') !== false) {
                    $normalized[3] = 3;
                    $normalized[1] = 1;
                }

                if (strpos($haystack, 'cuba') !== false) {
                    $normalized[1] = 1;
                }
            }
        }

        ksort($normalized);
        return array_values($normalized);
    }
}

if (!function_exists('dg_normalize_tour_duration_band')) {
    function dg_normalize_tour_duration_band($value, $duration_text = '')
    {
        $value = (int) $value;
        if ($value >= 1 && $value <= 3) {
            return $value;
        }

        $duration_text = is_string($duration_text) ? trim($duration_text) : '';
        if ($duration_text === '') {
            return 0;
        }

        if (preg_match('/(\d+)\s*(?:-|–|to)?\s*(\d+)?/u', $duration_text, $matches)) {
            $start = isset($matches[1]) ? (int) $matches[1] : 0;
            $end = isset($matches[2]) && $matches[2] !== '' ? (int) $matches[2] : $start;

            if ($end <= 5) {
                return 1;
            }

            if ($start >= 12 || $end >= 12) {
                return 3;
            }

            if ($start >= 6 || $end >= 7) {
                return 2;
            }
        }

        return 0;
    }
}

if (!function_exists('dg_normalize_tour_status')) {
    function dg_normalize_tour_status($value, $availability = [])
    {
        $allowed = dg_get_tour_finder_status_options();
        $value = sanitize_key((string) $value);

        if (isset($allowed[$value])) {
            return $value;
        }

        $candidates = [];
        if (is_array($availability)) {
            foreach ($availability as $item) {
                if (is_array($item) && !empty($item['status'])) {
                    $candidates[] = $item['status'];
                }
            }
        }

        foreach ($candidates as $candidate) {
            $normalized = strtolower(trim((string) $candidate));

            if ($normalized === '') {
                continue;
            }

            if (strpos($normalized, 'full') !== false || strpos($normalized, 'sold out') !== false) {
                return 'full';
            }

            if (strpos($normalized, 'spot') !== false || strpos($normalized, 'few') !== false || strpos($normalized, 'left') !== false) {
                return 'spots';
            }

            if (strpos($normalized, 'available') !== false || strpos($normalized, 'open') !== false || strpos($normalized, 'disponible') !== false) {
                return 'available';
            }
        }

        return '';
    }
}

if (!function_exists('dg_get_tour_finder_tour_record')) {
    function dg_get_tour_finder_tour_record($tour_id)
    {
        $focus_options = dg_get_tour_finder_main_focus_options();
        $region_options = dg_get_tour_finder_region_options();
        $duration_options = dg_get_tour_finder_duration_options();
        $status_options = dg_get_tour_finder_status_options();
        $legacy_terms = wp_get_post_terms($tour_id, 'tour_category');
        $main_focus = dg_normalize_tour_main_focus(get_post_meta($tour_id, 'tour_main_focus', true), is_wp_error($legacy_terms) ? [] : $legacy_terms);
        $regions = dg_normalize_tour_regions(get_post_meta($tour_id, 'tour_regions', true), get_post_meta($tour_id, 'tour_locations', true));
        $duration_band = dg_normalize_tour_duration_band(get_post_meta($tour_id, 'tour_duration_band', true), get_post_meta($tour_id, 'tour_duration', true));
        $status = dg_normalize_tour_status(get_post_meta($tour_id, 'tour_status', true), get_post_meta($tour_id, 'tour_availability', true)) ?: 'available';

        $primary_focus = !empty($main_focus) ? $main_focus[0] : ['categoryId' => 1, 'priority' => 3];
        $primary_focus_option = $focus_options[$primary_focus['categoryId']] ?? $focus_options[1];
        $tour_slug = get_post_field('post_name', $tour_id);
        $tour_overrides = dg_get_tour_finder_tour_overrides();
        $override = $tour_overrides[$tour_slug] ?? [];
        $program_overrides = dg_get_tour_finder_program_overrides();
        $program_override = $program_overrides[$primary_focus_option['slug']] ?? [];

        $image = get_the_post_thumbnail_url($tour_id, 'large')
            ?: (!empty($override['image']) ? $override['image'] : ($program_override['image'] ?? ''));

        $override_link = trim((string) ($override['link'] ?? ''));
        $link = ($override_link !== '' && $override_link !== '#')
            ? $override_link
            : get_permalink($tour_id);

        return [
            'id' => (int) $tour_id,
            'slug' => $tour_slug,
            'title' => dg_clean_tour_finder_text(get_the_title($tour_id)),
            'description' => dg_clean_tour_finder_text(wp_trim_words(get_the_excerpt($tour_id) ?: wp_strip_all_tags(get_post_field('post_content', $tour_id)), 22)),
            'image' => $image,
            'link' => $link,
            'mainFocus' => $main_focus,
            'regions' => $regions,
            'durationBand' => $duration_band,
            'status' => $status,
            'programSlug' => $primary_focus_option['slug'],
            'programName' => $primary_focus_option['label'],
            'primaryPriority' => (int) $primary_focus['priority'],
            'duration' => dg_translate_tour_value(get_post_meta($tour_id, 'tour_duration', true), 'duration') ?: ($duration_options[$duration_band]['label'] ?? ''),
            'difficulty' => dg_translate_tour_value(get_post_meta($tour_id, 'tour_difficulty', true), 'difficulty'),
            'groupSize' => dg_translate_tour_value(get_post_meta($tour_id, 'tour_max_people', true), 'group'),
            'statusLabel' => $status_options[$status]['label'] ?? 'Available',
            'regionLabels' => array_values(array_map(function ($region_id) use ($region_options) {
                return $region_options[$region_id]['label'] ?? '';
            }, $regions)),
        ];
    }
}

if (!function_exists('dg_get_tour_finder_catalog')) {
    function dg_get_tour_finder_catalog()
    {
        static $catalog = null;

        if ($catalog !== null) {
            return $catalog;
        }

        $catalog = [];
        $focus_options = dg_get_tour_finder_main_focus_options();
        $program_overrides = dg_get_tour_finder_program_overrides();
        $tour_overrides = dg_get_tour_finder_tour_overrides();
        $region_options = dg_get_tour_finder_region_options();
        $status_options = dg_get_tour_finder_status_options();
        $real_tours_by_slug = [];

        $published_tour_ids = get_posts([
            'post_type' => 'tour',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ]);

        foreach ($published_tour_ids as $published_tour_id) {
            $record = dg_get_tour_finder_tour_record((int) $published_tour_id);

            if (!is_array($record) || empty($record['slug'])) {
                continue;
            }

            $real_tours_by_slug[(string) $record['slug']] = $record;
        }

        foreach (dg_get_tour_finder_official_catalog_seed() as $index => $tour) {
            $seed_slug = (string) ($tour['slug'] ?? '');
            $override = $tour_overrides[$tour['slug']] ?? [];
            $program_override = $program_overrides[$tour['programSlug']] ?? [];
            $matched_tour = $real_tours_by_slug[$seed_slug] ?? null;
            $resolved_main_focus = !empty($matched_tour['mainFocus']) ? $matched_tour['mainFocus'] : ($tour['mainFocus'] ?? []);
            $primary_focus = $resolved_main_focus[0] ?? ['categoryId' => 1, 'priority' => 3];
            $resolved_program_slug = !empty($matched_tour['programSlug']) ? $matched_tour['programSlug'] : $tour['programSlug'];
            $program_option = null;

            foreach ($focus_options as $focus_option) {
                if (($focus_option['slug'] ?? '') === $resolved_program_slug) {
                    $program_option = $focus_option;
                    break;
                }
            }

            if ($program_option === null) {
                $program_option = $focus_options[$primary_focus['categoryId']] ?? ($focus_options[1] ?? ['label' => 'Birding']);
            }

            $program_name = $program_option['label'] ?? 'Birding';
            $status = !empty($matched_tour['status'])
                ? $matched_tour['status']
                : (dg_normalize_tour_status($override['status'] ?? ($tour['status'] ?? 'available'), []) ?: 'available');
            $resolved_regions = !empty($matched_tour['regions']) ? $matched_tour['regions'] : ($tour['regions'] ?? []);
            $resolved_duration_band = !empty($matched_tour['durationBand']) ? (int) $matched_tour['durationBand'] : (int) $tour['durationBand'];
            $resolved_duration = !empty($matched_tour['duration']) ? $matched_tour['duration'] : $tour['duration'];
            $resolved_image = !empty($matched_tour['image'])
                ? $matched_tour['image']
                : (!empty($override['image']) ? $override['image'] : ($program_override['image'] ?? ''));
            $override_link = trim((string) ($override['link'] ?? ''));
            $resolved_link = !empty($matched_tour['link'])
                ? $matched_tour['link']
                : (($override_link !== '' && $override_link !== '#') ? $override_link : ($program_override['link'] ?? '#'));

            $catalog[] = [
                'id' => !empty($matched_tour['id']) ? (int) $matched_tour['id'] : ($index + 1),
                'slug' => $seed_slug,
                'title' => dg_clean_tour_finder_text(!empty($matched_tour['title']) ? $matched_tour['title'] : $tour['title']),
                'description' => dg_clean_tour_finder_text(!empty($matched_tour['description']) ? $matched_tour['description'] : $tour['description']),
                'image' => $resolved_image,
                'link' => $resolved_link,
                'mainFocus' => $resolved_main_focus,
                'regions' => $resolved_regions,
                'durationBand' => $resolved_duration_band,
                'duration' => $resolved_duration,
                'status' => $status,
                'statusLabel' => $status_options[$status]['label'] ?? 'Available',
                'programSlug' => $resolved_program_slug,
                'programName' => !empty($matched_tour['programName']) ? $matched_tour['programName'] : $program_name,
                'primaryPriority' => !empty($matched_tour['primaryPriority']) ? (int) $matched_tour['primaryPriority'] : (int) $primary_focus['priority'],
                'difficulty' => !empty($matched_tour['difficulty']) ? $matched_tour['difficulty'] : ($tour['difficulty'] ?? ''),
                'groupSize' => !empty($matched_tour['groupSize']) ? $matched_tour['groupSize'] : ($tour['groupSize'] ?? ''),
                'catalogOrder' => $index + 1,
                'regionLabels' => !empty($matched_tour['regionLabels']) ? $matched_tour['regionLabels'] : array_values(array_map(function ($region_id) use ($region_options) {
                    return $region_options[$region_id]['label'] ?? '';
                }, $resolved_regions)),
            ];
        }

        return $catalog;
    }
}

if (!function_exists('dg_get_tour_finder_programs')) {
    function dg_get_tour_finder_programs()
    {
        static $programs = null;

        if ($programs !== null) {
            return $programs;
        }

        $programs = [];
        $focus_options = dg_get_tour_finder_main_focus_options();
        $program_overrides = dg_get_tour_finder_program_overrides();

        foreach ($focus_options as $option) {
            $override = $program_overrides[$option['slug']] ?? [];

            $programs[$option['slug']] = [
                'id' => $option['id'],
                'slug' => $option['slug'],
                'name' => $option['label'],
                'description' => $override['description'] ?? '',
                'image' => $override['image'] ?? '',
                'link' => $override['link'] ?? '#',
                'tours' => [],
            ];
        }

        foreach (dg_get_tour_finder_catalog() as $tour) {
            $program_slug = $tour['programSlug'];
            if (!isset($programs[$program_slug])) {
                continue;
            }

            $programs[$program_slug]['tours'][] = $tour;
        }

        foreach ($programs as &$program) {
            usort($program['tours'], function ($a, $b) {
                if ($a['primaryPriority'] === $b['primaryPriority']) {
                    return strcmp($a['title'], $b['title']);
                }

                return $a['primaryPriority'] <=> $b['primaryPriority'];
            });
        }
        unset($program);

        return apply_filters('dg_tour_finder_programs', $programs);
    }
}

if (!function_exists('dg_get_tour_finder_wizard_data')) {
    function dg_get_tour_finder_wizard_data()
    {
        return [
            'programs' => dg_get_tour_finder_programs(),
            'tours' => dg_get_tour_finder_catalog(),
            'mainFocusOptions' => array_values(dg_get_tour_finder_main_focus_options()),
            'regionOptions' => array_values(dg_get_tour_finder_region_options()),
            'durationOptions' => array_values(dg_get_tour_finder_duration_options()),
            'statusOptions' => array_values(dg_get_tour_finder_status_options()),
        ];
    }
}

/**
 * Shortcode: [tour_cards]
 *
 * Muestra una grid de 4 tarjetas de tours con imagen de fondo,
 * título, descripción, duración y capacidad máxima.
 */
add_shortcode('tour_cards', function ($atts) {
    $program_overrides = dg_get_tour_finder_program_overrides();
    $tours = [
        [
            'url' => $program_overrides['birding']['link'] ?? '#',
            'image' => ''??$program_overrides['birding']['image'] ?? '',
            'title' => 'Birding',
            'desc' => 'Expert-led departures to standout birding sites and observation windows.',
            'duration' => 'Short to Long',
            'max' => 'Priority program',
        ],
        [
            'url' => $program_overrides['wildlife-photography']['link'] ?? '#',
            'image' => '' ??$program_overrides['wildlife-photography']['image'] ?? '',
            'title' => 'Wildlife Photography',
            'desc' => 'Trips designed around hides, timing, behaviour and field image-making.',
            'duration' => 'Short to Long',
            'max' => 'Priority program',
        ],
        [
            'url' => $program_overrides['herping']['link'] ?? '#',
            'image' => 'https://palegreen-herring-696641.hostingersite.com/wp-content/uploads/2026/09/04-tours-herping.jpeg' ??$program_overrides['herping']['image'] ?? '',
            'title' => 'Herping',
            'desc' => 'Night walks and habitat-focused trips for reptiles and amphibians.',
            'duration' => 'Short to Long',
            'max' => 'Priority program',
        ],
        [
            'url' => $program_overrides['hiking']['link'] ?? '#',
            'image' => 'https://palegreen-herring-696641.hostingersite.com/wp-content/uploads/2026/09/02-tour-hiking-icon-scaled.jpg' ??$program_overrides['hiking']['image'] ?? '',
            'title' => 'Hiking',
            'desc' => 'Route-focused adventures across dramatic trails and active landscapes.',
            'duration' => 'Short to Long',
            'max' => 'Priority program',
        ],
        [
            'url' => $program_overrides['nature-culture']['link'] ?? '#',
            'image' => 'https://palegreen-herring-696641.hostingersite.com/wp-content/uploads/2026/09/05-tour-Nature-Culture-icon-scaled.jpg' ?? $program_overrides['nature-culture']['image'] ?? '',
            'title' => 'Nature & Culture',
            'desc' => 'Balanced itineraries that combine landscapes, biodiversity and local life.',
            'duration' => 'Short to Long',
            'max' => 'Priority program',
        ],
        [
            'url' => $program_overrides['snails-bugs']['link'] ?? '#',
            'image' => '' ?? $program_overrides['snails-bugs']['image'] ?? '',
            'title' => 'Snails & Bugs',
            'desc' => 'Special interest departures built around invertebrates and macro encounters.',
            'duration' => 'Short to Long',
            'max' => 'Priority program',
        ],
    ];

    ob_start();
    ?>
    <div class="tour-section">
        <div class="tour-header">
            <div>
                <div class="tour-label">Featured Expeditions</div>
                <h2 class="tour-title">Six ways to explore<br>the <em>wild</em></h2>
            </div>
        </div>
        <div class="tour-grid">
            <?php foreach ($tours as $tour): ?>
                <?php if (!empty($tour['url'])): ?>
                    <a href="<?php echo esc_url($tour['url']); ?>" class="tour-card" target="_self">
                    <?php else: ?>
                        <div class="tour-card tour-card--no-link">
                        <?php endif; ?>
                        <div class="tour-card-bg" style="background-image: url('<?php echo esc_url($tour['image']); ?>');">
                        </div>
                        <div class="tour-card-overlay"></div>
                        <div class="tour-card-meta-top">
                            <span></span>
                            <span></span>
                        </div>
                        <div class="tour-card-content">
                            <h3 class="tour-card-title"><?php echo esc_html($tour['title']); ?></h3>
                            <p class="tour-card-desc"><?php echo esc_html($tour['desc']); ?></p>
                            <div class="tour-card-info">
                                <span>⏱ <?php echo esc_html($tour['duration']); ?></span>
                                <span>👥 <?php echo esc_html($tour['max']); ?></span>
                            </div>
                            <span class="tour-card-link">Explore Tours →</span>
                        </div>
                        <?php if (!empty($tour['url'])): ?>
                    </a>
                <?php else: ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    </div>
    <?php
    return ob_get_clean();
});

/* ============================================
   DUALGRID - SHORTCODES FAQ
   Agregar al final de includes/shortcodes.php
   ============================================ */

/**
 * Shortcode contenedor: [dualgrid_faqs]
 *
 * Atributos:
 * - label: Texto superior (default: "BUENO SABER")
 * - titulo: Parte regular del título (default: "Preguntas")
 * - titulo_italic: Parte en cursiva del título (default: "Frecuentes")
 * - subtitulo: Texto descriptivo debajo del título
 * - link_text: Texto del enlace (default: "Contáctanos.")
 * - link_url: URL del enlace (default: "#")
 *
 * Uso en Elementor:
 * [dualgrid_faqs]
 *   [dualgrid_faq pregunta="¿Pregunta 1?" respuesta="Respuesta 1"]
 *   [dualgrid_faq pregunta="¿Pregunta 2?" respuesta="Respuesta 2"]
 * [/dualgrid_faqs]
 */
add_shortcode('dualgrid_faqs', function ($atts, $content = null) {
    $atts = shortcode_atts([
        'label' => 'GOOD TO KNOW',
        'titulo' => 'Frequently',
        'titulo_italic' => 'Asked Questions',
        'subtitulo' => 'Everything you need to know about our expeditions. Still need help?',
        'link_text' => 'Contact us.',
        'link_url' => '#',
    ], $atts, 'dualgrid_faqs');

    // Reiniciar contador global para cada sección FAQ
    $GLOBALS['dualgrid_faq_counter'] = 0;

    // Procesar shortcodes internos
    $items = do_shortcode($content);

    ob_start();
    ?>
    <section class="dg-faqs-section">
        <div class="dg-faqs-container">
            <div class="dg-faqs-header">
                <span class="dg-faqs-label"><?php echo esc_html($atts['label']); ?></span>
                <h2 class="dg-faqs-title">
                    <?php echo esc_html($atts['titulo']); ?> <em><?php echo esc_html($atts['titulo_italic']); ?></em>
                </h2>
                <p class="dg-faqs-subtitle">
                    <?php echo esc_html($atts['subtitulo']); ?>
                    <?php if ($atts['link_url']): ?>
                        <a href="<?php echo esc_url($atts['link_url']); ?>"><?php echo esc_html($atts['link_text']); ?></a>
                    <?php endif; ?>
                </p>
            </div>
            <div class="dg-faqs-list">
                <?php echo $items; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
});

/**
 * Shortcode item: [dualgrid_faq]
 *
 * Atributos:
 * - pregunta: Texto de la pregunta (obligatorio)
 * - respuesta: Texto/HTML de la respuesta (obligatorio)
 * - numero: Número personalizado (opcional, auto-generado si se omite)
 */
add_shortcode('dualgrid_faq', function ($atts) {
    $atts = shortcode_atts([
        'pregunta' => '',
        'respuesta' => '',
        'numero' => '',
    ], $atts, 'dualgrid_faq');

    // Generar número automático si no se proporciona
    if (empty($atts['numero'])) {
        if (!isset($GLOBALS['dualgrid_faq_counter'])) {
            $GLOBALS['dualgrid_faq_counter'] = 0;
        }
        $GLOBALS['dualgrid_faq_counter']++;
        $num = str_pad($GLOBALS['dualgrid_faq_counter'], 2, '0', STR_PAD_LEFT);
    } else {
        $num = esc_html($atts['numero']);
    }

    ob_start();
    ?>
    <div class="dg-faq-item">
        <button class="dg-faq-trigger" aria-expanded="false">
            <span class="dg-faq-number"><?php echo esc_html($num); ?></span>
            <span class="dg-faq-question"><?php echo esc_html($atts['pregunta']); ?></span>
            <span class="dg-faq-icon" aria-hidden="true">
                <span class="dg-icon-plus"></span>
                <span class="dg-icon-minus"></span>
            </span>
        </button>
        <div class="dg-faq-content">
            <div class="dg-faq-inner">
                <?php echo wp_kses_post($atts['respuesta']); ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
});






/* ============================================
   DUALGRID - SHORTCODE TESTIMONIALS CAROUSEL
   Agregar al final de includes/shortcodes.php
   ============================================ */

/**
 * Shortcode: [testimonials_carousel]
 *
 * Atributos:
 * - count: Cantidad de testimonials a mostrar (default: 10)
 * - speed: Velocidad de rotación en ms (default: 6000)
 *
 * Uso en Elementor:
 * [testimonials_carousel count="5" speed="5000"]
 */
add_shortcode('testimonials_carousel', function ($atts) {
    $atts = shortcode_atts(['count' => 10, 'speed' => 6000], $atts, 'testimonials_carousel');

    $query = new WP_Query([
        'post_type' => 'testimonial',
        'posts_per_page' => intval($atts['count']),
        'post_status' => 'publish',
        'meta_query' => [
            ['key' => '_tp_estado', 'value' => 'aprobado', 'compare' => '=']
        ]
    ]);

    if (!$query->have_posts()) {
        return '<p style="text-align:center;">There are no approved testimonials yet.</p>';
    }

    $items = [];
    while ($query->have_posts()) {
        $query->the_post();
        $img = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: 'https://via.placeholder.com/128';
        $items[] = [
            'quote' => get_the_content(),
            'name' => get_the_title(),
            'location' => get_post_meta(get_the_ID(), '_tp_ubicacion', true) ?: '',
            'stars' => intval(get_post_meta(get_the_ID(), '_tp_valoracion', true) ?: 5),
            'avatar' => $img,
        ];
    }
    wp_reset_postdata();

    $json = json_encode($items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $speed = intval($atts['speed']);

    ob_start();
    ?>
    <div id="tp-carousel" class="tp-carousel" data-items='<?php echo htmlspecialchars($json, ENT_QUOTES, 'UTF-8'); ?>'
        data-speed="<?php echo $speed; ?>">
        <div class="tp-inner">
            <div class="tp-label">Voices from the field</div>
            <h2 class="tp-title">Stories that <em>stay with you</em></h2>

            <!-- Wrapper para animaciones -->
            <div class="tp-slide-wrap tp-active" id="tp-slide-wrap">
                <div class="tp-quote-icon">"</div>
                <div class="tp-quote" id="tp-quote"></div>
                <img class="tp-avatar" id="tp-avatar" src="" alt="">
                <div class="tp-name" id="tp-name"></div>
                <div class="tp-location" id="tp-location"></div>
                <div class="tp-stars" id="tp-stars"></div>
            </div>

            <div class="tp-dots" id="tp-dots"></div>
        </div>
        <button class="tp-nav tp-prev" onclick="tpPrev()">‹</button>
        <button class="tp-nav tp-next" onclick="tpNext()">›</button>
    </div>
    <?php
    return ob_get_clean();
});

/**
 * Shortcode: [tour_wizard]
 *
 * Solo renderiza el BOTÓN. El overlay ya existe globalmente.
 * Uso en la home: [tour_wizard]
 * Uso con texto personalizado: [tour_wizard texto="Descubre tu tour →"]
 */
add_shortcode('tour_wizard', function ($atts) {
    $atts = shortcode_atts([
        'texto' => 'Find your ideal expedition →',
        'clase' => 'dg-wizard-btn',
    ], $atts, 'tour_wizard');

    return '<button class="' . esc_attr($atts['clase']) . ' dg-wizard-trigger">' . esc_html($atts['texto']) . '</button>';
});





/* ============================================
   DUALGRID - SHORTCODE HERO SLIDER
   Agregar al final de includes/shortcodes.php
   ============================================ */

/**
 * Shortcode: [hero_slider]
 *
 * Muestra un hero slider fullscreen con Ken Burns y autoplay.
 *
 * Atributos:
 * - slide_1_image, slide_2_image, slide_3_image, slide_4_image, slide_5_image: URLs de imágenes
 * - slide_1_title, slide_2_title, etc.: Títulos
 * - slide_1_subtitle, etc.: Subtítulos
 * - slide_1_desc, etc.: Descripciones
 * - slide_1_link, etc.: URLs del botón primario
 * - btn_primary_text: Texto botón primario (default: "Explorar Tours →")
 * - delay: Milisegundos entre slides (default: 6000)
 *
 * Uso en Elementor:
 * [hero_slider
 *   slide_1_image="URL" slide_1_title="Tours de Aves" slide_1_subtitle="Subtítulo" slide_1_desc="Descripción..." slide_1_link="/tours/birding"
 *   slide_2_image="URL" slide_2_title="Tours de Naturaleza" ...
 * ]
 */
add_shortcode('hero_slider', function ($atts) {
    $program_overrides = dg_get_tour_finder_program_overrides();

    $atts = shortcode_atts([
        // Replace these slide image/link defaults here if you want hero-specific routes or images.
        'slide_1_image' => $program_overrides['birding']['image'] ?? '',
        'slide_1_title' => 'Birding Programs',
        'slide_1_subtitle' => 'Where observation drives the journey',
        'slide_1_desc' => 'Expert-led departures built around endemics, migration windows and focused birding time.',
        'slide_1_link' => $program_overrides['birding']['link'] ?? '#',

        'slide_2_image' => $program_overrides['wildlife-photography']['image'] ?? '',
        'slide_2_title' => 'Wildlife Photography',
        'slide_2_subtitle' => 'Light, timing and behaviour in the field',
        'slide_2_desc' => 'Programs tailored for hides, strategic timing and strong photographic opportunities.',
        'slide_2_link' => $program_overrides['wildlife-photography']['link'] ?? '#',

        'slide_3_image' => $program_overrides['herping']['image'] ?? '',
        'slide_3_title' => 'Herping',
        'slide_3_subtitle' => 'After-dark encounters in the right habitats',
        'slide_3_desc' => 'Trips focused on amphibians, reptiles and expert habitat reading by day and night.',
        'slide_3_link' => $program_overrides['herping']['link'] ?? '#',

        'slide_4_image' => $program_overrides['hiking']['image'] ?? '',
        'slide_4_title' => 'Hiking',
        'slide_4_subtitle' => 'Routes shaped by landscape and effort',
        'slide_4_desc' => 'Active departures across ridgelines, trails and terrain that reward movement.',
        'slide_4_link' => $program_overrides['hiking']['link'] ?? '#',

        'slide_5_image' => $program_overrides['nature-culture']['image'] ?? '',
        'slide_5_title' => 'Nature & Culture',
        'slide_5_subtitle' => 'Wild places with living local context',
        'slide_5_desc' => 'Balanced journeys that combine biodiversity, scenery, communities and culture.',
        'slide_5_link' => $program_overrides['nature-culture']['link'] ?? '#',

        'slide_6_image' => $program_overrides['snails-bugs']['image'] ?? '',
        'slide_6_title' => 'Snails & Bugs',
        'slide_6_subtitle' => 'Macro worlds hidden in plain sight',
        'slide_6_desc' => 'Special interest departures built for invertebrates, textures and overlooked biodiversity.',
        'slide_6_link' => $program_overrides['snails-bugs']['link'] ?? '#',

        'slide_7_image' => 'https://palegreen-herring-696641.hostingersite.com/wp-content/uploads/2026/09/00-home-6-Tailor-made.jpeg',
        'slide_7_title' => 'Tailor-Made Journeys',
        'slide_7_subtitle' => 'Build the exact trip you have in mind',
        'slide_7_desc' => 'If your priorities cross several programs, we can shape a route around your pace, dates and goals.',
        'slide_7_link' => '#',

        'slide_8_image' => 'https://palegreen-herring-696641.hostingersite.com/wp-content/uploads/2026/09/00-home-7-Conservation.jpeg',
        'slide_8_title' => 'Conservation',
        'slide_8_subtitle' => 'Build the exact trip you have in mind',
        'slide_8_desc' => 'If your priorities cross several programs, we can shape a route around your pace, dates and goals.',
        'slide_8_link' => '#',

        'btn_primary_text' => 'Explore Tours →',
        'delay' => 6000,
    ], $atts, 'hero_slider');

    $slides = [];
    for ($i = 1; $i <= 8; $i++) {
        $img = $atts["slide_{$i}_image"];
        $title = $atts["slide_{$i}_title"];
        if (empty($img) || empty($title))
            continue;
        $slides[] = [
            'image' => $img,
            'title' => $title,
            'subtitle' => $atts["slide_{$i}_subtitle"],
            'desc' => $atts["slide_{$i}_desc"],
            'link' => $atts["slide_{$i}_link"],
        ];
    }

    if (empty($slides))
        return '';

    $delay = intval($atts['delay']);

    ob_start();
    ?>
    <div class="dg-hero-slider" id="dgHeroSlider" data-delay="<?php echo esc_attr($delay); ?>">
        <?php foreach ($slides as $index => $slide): ?>
            <div class="dg-hero-slide <?php echo $index === 0 ? 'dg-active' : ''; ?>" data-index="<?php echo $index; ?>">
                <div class="dg-hero-bg" style="background-image: url('<?php echo esc_url($slide['image']); ?>');"></div>
                <div class="dg-hero-overlay"></div>
                <div class="dg-hero-content">
                    <h1 class="dg-hero-title"><?php echo esc_html($slide['title']); ?></h1>
                    <p class="dg-hero-subtitle"><?php echo esc_html($slide['subtitle']); ?></p>
                    <p class="dg-hero-desc"><?php echo esc_html($slide['desc']); ?></p>
                    <div class="dg-hero-buttons">
                        <a href="<?php echo esc_url($slide['link']); ?>"
                            class="dg-hero-btn dg-hero-btn-primary"><?php echo esc_html($atts['btn_primary_text']); ?></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
});





/**
 * Shortcode: [wild_tour]
 * Cada tour individual. El layout se alterna automáticamente.
 *
 * Atributos:
 *   numero        -> "01", "02", etc. (aparece en el label: "01 · TIPO DE EXPEDICIÓN")
 *   imagen        -> URL de la imagen
 *   label         -> "TIPO DE EXPEDICIÓN"
 *   titulo        -> Título principal
 *   subtitulo     -> Texto en cursiva verde
 *   descripcion   -> Párrafo descriptivo
 *   duracion      -> ej: "5-14 días"
 *   grupo         -> ej: "Máx. 6"
 *   dificultad    -> ej: "Fácil-Moderado"
 *   link          -> URL del botón
 *   btn_texto     -> Texto del botón (default: "Ver Programas")
 */
add_shortcode('wild_tour', function ($atts) {
    $atts = shortcode_atts([
        'numero' => '01',
        'imagen' => '',
        'label' => 'EXPEDITION TYPE',
        'titulo' => 'Tour Title',
        'subtitulo' => 'Subtitle goes here',
        'descripcion' => 'Tour description.',
        'duracion' => '5-14 days',
        'grupo' => 'Max. 6',
        'dificultad' => 'Easy to Moderate',
        'link' => '#',
        'btn_texto' => 'View Programs',
    ], $atts, 'wild_tour');

    // Dirección de animación según número par/impar
    $direccion = ((int) $atts['numero'] % 2 === 0) ? 'from-right' : 'from-left';

    ob_start();
    ?>
    <div class="wild-tour-card <?php echo esc_attr($direccion); ?>">
        <div class="wild-tour__image-wrap">
            <img src="<?php echo esc_url($atts['imagen']); ?>" alt="<?php echo esc_attr($atts['titulo']); ?>"
                loading="lazy">
        </div>
        <div class="wild-tour__content">
            <span class="wild-tour__type-label"><?php echo esc_html($atts['numero']); ?> ·
                <?php echo esc_html($atts['label']); ?></span>
            <h3 class="wild-tour__title"><?php echo esc_html($atts['titulo']); ?></h3>
            <p class="wild-tour__subtitle"><?php echo esc_html($atts['subtitulo']); ?></p>
            <p class="wild-tour__desc"><?php echo esc_html($atts['descripcion']); ?></p>

            <div class="wild-tour__meta">
                <div class="wild-tour__meta-item">
                    <svg class="wild-tour__meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    <span class="wild-tour__meta-label">Duration</span>
                    <span class="wild-tour__meta-value"><?php echo esc_html($atts['duracion']); ?></span>
                </div>
                <div class="wild-tour__meta-item">
                    <svg class="wild-tour__meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    <span class="wild-tour__meta-label">Group Size</span>
                    <span class="wild-tour__meta-value"><?php echo esc_html($atts['grupo']); ?></span>
                </div>
                <div class="wild-tour__meta-item">
                    <svg class="wild-tour__meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M12 2L2 7l10 5 10-5-10-5z" />
                        <path d="M2 17l10 5 10-5" />
                        <path d="M2 12l10 5 10-5" />
                    </svg>
                    <span class="wild-tour__meta-label">Difficulty</span>
                    <span class="wild-tour__meta-value"><?php echo esc_html($atts['dificultad']); ?></span>
                </div>
            </div>

            <a href="<?php echo esc_url($atts['link']); ?>" class="wild-tour__btn">
                <?php echo esc_html($atts['btn_texto']); ?>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
    <?php
    return ob_get_clean();
});

/**
 * Shortcode: [wild_tours_container]
 * Envuelve los [wild_tour] para que el JS funcione correctamente.
 */
add_shortcode('wild_tours_container', function ($atts, $content = null) {
    $program_overrides = dg_get_tour_finder_program_overrides();
    $cards = [
        [
            'url' => $program_overrides['birding']['link'] ?? '#',
            'image' => $program_overrides['birding']['image'] ?? '',
            'title' => 'Birding',
            'desc' => 'Expert-led departures to standout birding sites and observation windows.',
        ],
        [
            'url' => $program_overrides['wildlife-photography']['link'] ?? '#',
            'image' => $program_overrides['wildlife-photography']['image'] ?? '',
            'title' => 'Wildlife Photography',
            'desc' => 'Trips designed around hides, timing, behaviour and field image-making.',
        ],
        [
            'url' => $program_overrides['herping']['link'] ?? '#',
            'image' => $program_overrides['herping']['image'] ?? '',
            'title' => 'Herping',
            'desc' => 'Night walks and habitat-focused trips for reptiles and amphibians.',
        ],
        [
            'url' => $program_overrides['hiking']['link'] ?? '#',
            'image' => $program_overrides['hiking']['image'] ?? '',
            'title' => 'Hiking',
            'desc' => 'Route-focused adventures across dramatic trails and active landscapes.',
        ],
        [
            'url' => $program_overrides['nature-culture']['link'] ?? '#',
            'image' => $program_overrides['nature-culture']['image'] ?? '',
            'title' => 'Nature & Culture',
            'desc' => 'Balanced itineraries that combine landscapes, biodiversity and local life.',
        ],
        [
            'url' => $program_overrides['snails-bugs']['link'] ?? '#',
            'image' => $program_overrides['snails-bugs']['image'] ?? '',
            'title' => 'Snails & Bugs',
            'desc' => 'Special interest departures built around invertebrates and macro encounters.',
        ],
    ];

    ob_start();
    ?>
    <section class="we-cat-programs" style="padding-top:0; padding-bottom:0;">
        <div class="we-cat-programs__header">
            <span class="we-section-label">Tours</span>
            <h2 class="we-section-title">Six ways to explore</h2>
        </div>
        <div class="we-programs-list">
            <?php foreach ($cards as $card): ?>
                <?php if (!empty($card['url'])): ?>
                    <a href="<?php echo esc_url($card['url']); ?>" class="we-program-card we-program-card--link">
                    <?php else: ?>
                        <article class="we-program-card we-program-card--no-link">
                        <?php endif; ?>
                        <div class="we-program-card__image">
                            <?php if (!empty($card['image'])): ?>
                                <img src="<?php echo esc_url($card['image']); ?>" alt="<?php echo esc_attr($card['title']); ?>"
                                    loading="lazy">
                            <?php endif; ?>
                        </div>
                        <div class="we-program-card__content">
                            <h3 class="we-program-card__title"><?php echo esc_html($card['title']); ?></h3>
                            <p class="we-program-card__desc"><?php echo esc_html($card['desc']); ?></p>
                            <div class="we-program-card__actions">
                                <?php if (!empty($card['url'])): ?>
                                    <span class="we-btn-green">Explore tours</span>
                                <?php else: ?>
                                    <span class="we-btn-green we-btn-green--static">Explore tours</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (!empty($card['url'])): ?>
                    </a>
                <?php else: ?>
                    </article>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
});



/**
 * Shortcode: [we_tour_modal_form]
 * Renderiza el modal con formulario CF7 de solicitud de información.
 *
 * Uso: [we_tour_modal_form cf7_id="123"]
 */

add_shortcode('we_tour_modal_form', function ($atts) {
    $atts = shortcode_atts([
        'cf7_id' => 2112133, // ← tu ID de CF7
    ], $atts, 'we_tour_modal_form');

    ob_start();
    dg_render_tour_request_modal($atts['cf7_id']);
    return ob_get_clean();
});

function dg_render_tour_request_modal($cf7_id = 2112133)
{
    static $rendered = false;

    if ($rendered) {
        return;
    }

    $rendered = true;
    $cf7_id = dg_normalize_cf7_id($cf7_id);

    if ($cf7_id === '') {
        $cf7_id = '2112133';
    }

    if (function_exists('wpcf7_enqueue_scripts')) {
        wpcf7_enqueue_scripts();
        wpcf7_enqueue_styles();
    }

    ?>
    <div id="we-tour-modal" class="we-modal" style="display:none;" aria-hidden="true">
        <div class="we-modal__overlay" onclick="weCloseModal()"></div>
        <div class="we-modal__content">
            <button type="button" class="we-modal__close" onclick="weCloseModal()" aria-label="Close">×</button>
            <div class="we-modal__header">
                <img src="<?php echo esc_url(dg_get_site_logo_url()); ?>" alt="Nils Birding & Conservation"
                    class="we-modal__logo">
                <span class="we-modal__badge">Request Information</span>
            </div>
            <div class="we-modal__body">
                <h3 class="we-modal__title">Request Information</h3>
                <p class="we-modal__subtitle">Complete the form and we will send you the details for this program.</p>
                <div class="we-modal__form-wrap">
                    <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($cf7_id) . '" html_class="we-cf7-form we-cf7-tour-form" html_id="we-cf7-tour-form"]'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

add_shortcode('tour_booking_form', function ($atts) {
    $atts = shortcode_atts([
        'cf7_id' => dg_get_booking_cf7_id(),
    ], $atts, 'tour_booking_form');

    ob_start();
    dg_render_tour_booking_modal($atts['cf7_id']);
    return ob_get_clean();
});

function dg_render_tour_booking_modal($cf7_id = '')
{
    static $rendered = false;

    if ($rendered) {
        return;
    }

    $rendered = true;
    $cf7_id = dg_normalize_cf7_id($cf7_id);

    if ($cf7_id === '') {
        $cf7_id = dg_normalize_cf7_id(dg_get_booking_cf7_id());
    }

    if (function_exists('wpcf7_enqueue_scripts')) {
        wpcf7_enqueue_scripts();
        wpcf7_enqueue_styles();
    }

    ?>
    <div id="dg-tour-booking-modal" class="we-modal dg-tour-booking-modal" style="display:none;" aria-hidden="true">
        <div class="we-modal__content">
            <button type="button" class="we-modal__close" onclick="dgCloseBookingModal()" aria-label="Close">×</button>
            <div class="we-modal__header">
                <img src="<?php echo esc_url(dg_get_site_logo_url()); ?>" alt="Nils Birding & Conservation"
                    class="we-modal__logo">
                <span class="we-modal__badge">Booking form</span>
            </div>
            <div class="we-modal__body">
                <h3 class="we-modal__title" id="dg-booking-modal-title">Book this tour</h3>
                <p class="we-modal__subtitle">Complete this form and we will confirm availability and payment steps.</p>

                <?php if (empty($cf7_id)): ?>
                    <p
                        style="color:#c62828; padding:14px 16px; background:#fff3f1; border:1px solid #f0c5bb; border-radius:12px;">
                        Add your Contact Form 7 ID in the shortcode, for example: [tour_booking_form cf7_id="123"]
                    </p>
                <?php else: ?>
                    <div class="we-modal__form-wrap">
                        <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($cf7_id) . '" html_class="we-cf7-form we-cf7-tour-booking-form" html_id="dg-cf7-tour-booking-form"]'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}

add_shortcode('tailor_made_form', function ($atts) {
    $atts = shortcode_atts([
        'cf7_id' => dg_get_tailor_made_cf7_id(),
        'button_text' => '',
        'button_class' => 'dg-tailor-made-trigger',
    ], $atts, 'tailor_made_form');

    ob_start();

    if ($atts['button_text'] !== '') {
        echo '<button class="' . esc_attr(trim($atts['button_class'] . ' dg-tailor-made-trigger')) . '">' . esc_html($atts['button_text']) . '</button>';
    }

    dg_render_tailor_made_modal($atts['cf7_id']);
    return ob_get_clean();
});

function dg_render_tailor_made_modal($cf7_id = '')
{
    static $rendered = false;

    if ($rendered) {
        return;
    }

    $rendered = true;

    $cf7_id = dg_normalize_cf7_id($cf7_id);

    if ($cf7_id === '') {
        $cf7_id = dg_normalize_cf7_id(dg_get_tailor_made_cf7_id());
    }

    if (function_exists('wpcf7_enqueue_scripts')) {
        wpcf7_enqueue_scripts();
        wpcf7_enqueue_styles();
    }

    ?>
    <div id="dg-tailor-made-modal" class="we-modal dg-tailor-made-modal" style="display:none;" aria-hidden="true">
        <div class="we-modal__overlay" onclick="dgCloseTailorMadeModal()"></div>
        <div class="we-modal__content">
            <button type="button" class="we-modal__close" onclick="dgCloseTailorMadeModal()" aria-label="Close">×</button>
            <div class="we-modal__header">
                <img src="<?php echo esc_url(dg_get_site_logo_url()); ?>" alt="Nils Birding & Conservation"
                    class="we-modal__logo">
                <span class="we-modal__badge">Tailor-made request</span>
            </div>
            <div class="we-modal__body">
                <h3 class="we-modal__title">Tailor-Made Journey</h3>
                <p class="we-modal__subtitle">Share your dates, priorities and travel style and we will build a custom
                    proposal
                    for you.</p>

                <?php if (empty($cf7_id)): ?>
                    <p
                        style="color:#c62828; padding:14px 16px; background:#fff3f1; border:1px solid #f0c5bb; border-radius:12px;">
                        Add your Contact Form 7 ID in the shortcode, for example: [tailor_made_form cf7_id="123"]
                    </p>
                <?php else: ?>
                    <div class="we-modal__form-wrap">
                        <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($cf7_id) . '" html_class="we-cf7-form we-cf7-tailor-made-form" html_id="dg-cf7-tailor-made-form"]'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Shortcode: [we_tours categoria="slug" titulo="Opcional" subtitulo="Opcional"]
 *
 * Ejemplos:
 * [we_tours categoria="birding" titulo="Birding"]
 * [we_tours categoria="wildlife-photography" titulo="Wildlife Photography"]
 * [we_tours categoria="herping" titulo="Herping"]
 * [we_tours categoria="hiking" titulo="Hiking"]
 * [we_tours categoria="nature-culture" titulo="Nature & Culture"]
 * [we_tours categoria="snails-bugs" titulo="Snails & Bugs"]
 */







add_shortcode('we_tours', function ($atts) {
    $atts = shortcode_atts([
        'categoria' => '',
        'titulo' => '',
        'subtitulo' => 'selected departures',
        'cf7_id' => 2112133,
        'booking_cf7_id' => dg_get_booking_cf7_id(),
    ], $atts, 'we_tours');

    if (empty($atts['categoria'])) {
        return '<p style="color:#c62828; text-align:center; padding:20px;">Error: You must set a category. Example: [we_tours categoria="birding"]</p>';
    }

    $term = get_term_by('slug', $atts['categoria'], 'tour_category');
    if (!$term) {
        return '<p style="color:#c62828; text-align:center; padding:20px;">Error: The category "' . esc_html($atts['categoria']) . '" does not exist.</p>';
    }

    $args = [
        'post_type' => 'tour',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
        'tax_query' => [
            [
                'taxonomy' => 'tour_category',
                'field' => 'slug',
                'terms' => $atts['categoria'],
            ]
        ],
    ];

    $tours = new WP_Query($args);

    if (!$tours->have_posts()) {
        return '<p style="text-align:center; padding:60px 20px; color:#888;">There are no available programs in this category yet.</p>';
    }

    $titulo = !empty($atts['titulo']) ? $atts['titulo'] : $term->count . ' ' . $atts['subtitulo'];

    ob_start();
    ?>
    <section class="we-cat-programs" style="padding-top:0; padding-bottom:0;">
        <div class="we-cat-programs__header">
            <span class="we-section-label">Programs</span>
            <h2 class="we-section-title"><?php echo esc_html($titulo); ?></h2>
        </div>

        <div class="we-programs-list">
            <?php $pdf = '';
            while ($tours->have_posts()):
                $tours->the_post();
                $pid = get_the_ID();
                // ===== META KEYS CORREGIDOS (igual que el admin) =====
                $duration = get_post_meta($pid, 'tour_duration', true) ?: '5-14 days';
                $group = get_post_meta($pid, 'tour_max_people', true) ?: 'Max. 6';
                $difficulty = get_post_meta($pid, 'tour_difficulty', true) ?: 'Easy to Moderate';
                $city = get_post_meta($pid, 'tour_city', true);
                $dates = get_post_meta($pid, 'tour_dates', true);
                $pdf = get_post_meta($pid, '_we_pdf_url', true);
                $image = get_the_post_thumbnail_url($pid, 'large') ?: '';
                $duration_display = dg_translate_tour_value($duration, 'duration');
                $group_display = dg_translate_tour_value($group, 'group');
                $difficulty_display = dg_translate_tour_value($difficulty, 'difficulty');
                $city_display = dg_translate_tour_value($city, 'city');
                $dates_display = dg_translate_tour_value($dates, 'dates');
                ?>
                <article class="we-program-card">
                    <div class="we-program-card__image">
                        <?php if ($image): ?>
                            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy">
                        <?php endif; ?>
                    </div>
                    <div class="we-program-card__content">
                        <h3 class="we-program-card__title"><?php the_title(); ?></h3>
                        <p class="we-program-card__desc"><?php echo wp_trim_words(get_the_excerpt(), 28); ?></p>

                        <div class="we-program-meta">
                            <?php if ($duration_display): ?>
                                <div class="we-program-meta__item">
                                    <span
                                        class="we-program-meta__label"><?php echo dg_get_tour_meta_icon('duration'); ?>Duration</span>
                                    <span class="we-program-meta__value"><?php echo esc_html($duration_display); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($group_display): ?>
                                <div class="we-program-meta__item">
                                    <span class="we-program-meta__label"><?php echo dg_get_tour_meta_icon('group'); ?>Group</span>
                                    <span class="we-program-meta__value"><?php echo esc_html($group_display); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($difficulty_display): ?>
                                <div class="we-program-meta__item">
                                    <span
                                        class="we-program-meta__label"><?php echo dg_get_tour_meta_icon('difficulty'); ?>Difficulty</span>
                                    <span class="we-program-meta__value"><?php echo esc_html($difficulty_display); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($city_display): ?>
                                <div class="we-program-meta__item">
                                    <span
                                        class="we-program-meta__label"><?php echo dg_get_tour_meta_icon('location'); ?>Location</span>
                                    <span class="we-program-meta__value"><?php echo esc_html($city_display); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($dates_display): ?>
                                <div class="we-program-meta__item">
                                    <span class="we-program-meta__label"><?php echo dg_get_tour_meta_icon('dates'); ?>Dates</span>
                                    <span class="we-program-meta__value"><?php echo esc_html($dates_display); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- ===== BOTONES CORREGIDOS ===== -->
                        <div class="we-program-card__actions we-program-card__actions--three">

                            <!-- Botón 1: Tour Details (verde, ancho completo) -->
                            <button type="button" class="we-btn-green we-btn-details" data-tour-id="<?php echo $pid; ?>">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16"
                                    height="16">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 16v-4M12 8h.01" />
                                </svg>
                                View details
                            </button>

                            <!-- Fila inferior: 2 botones -->
                            <div class="we-program-card__actions-row">
                                <button type="button" class="we-btn-terracota"
                                    onclick="weOpenModal(<?php echo $pid; ?>, '<?php echo esc_js(get_the_title()); ?>')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16"
                                        height="16">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                        <polyline points="22,6 12,13 2,6" />
                                    </svg>
                                    Request information
                                </button>

                                <?php if ($pdf): ?>
                                    <a href="<?php echo esc_url($pdf ?? ''); ?>" target="_blank" class="we-btn-outline">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16"
                                            height="16">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                            <polyline points="7 10 12 15 17 10" />
                                            <line x1="12" y1="15" x2="12" y2="3" />
                                        </svg>
                                        Download PDF
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>
    </section>
    <?php
    dg_render_tour_modal();
    dg_render_tour_request_modal($atts['cf7_id']);
    dg_render_tour_booking_modal($atts['booking_cf7_id']);
    return ob_get_clean();
});

/**
 * Renderiza el contenedor vacío del modal (se rellena vía JS)
 */
function dg_render_tour_modal()
{
    static $rendered = false;
    if ($rendered)
        return;
    $rendered = true;
    ?>
    <!-- Tour Details Modal -->
    <div id="tour-details-modal" class="tour-details-modal" style="display:none;">
        <div class="tour-modal-overlay">
            <button class="tour-modal-close-btn" id="tour-modal-close" aria-label="Close">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>

            <!-- Encabezado oscuro -->
            <header class="tour-modal-header">
                <div class="tour-modal-header__inner">
                    <nav class="tour-breadcrumbs">
                        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                        <span>/</span>
                        <a href="<?php echo esc_url(home_url('/tours')); ?>">Tours</a>
                        <span>/</span>
                        <a id="tour-breadcrumb-name" href="<?php echo esc_url(home_url('/tours')); ?>">Tour</a>
                    </nav>
                    <div class="tour-header-grid">
                        <div class="tour-header-left">
                            <div class="tour-header-meta-line">
                                <span class="tour-sage-line"></span>
                                <span id="tour-header-coords" class="tour-mono-label"></span>
                                <span class="tour-expedition-label">Expedition</span>
                            </div>
                            <h1 id="tour-modal-title" class="tour-modal-title"></h1>
                            <p id="tour-modal-short-desc" class="tour-modal-short-desc"></p>
                            <div id="tour-attributes-list" class="tour-attributes-list"></div>
                            <div class="tour-header-actions">
                                <button class="tour-btn-inquiry" id="tour-btn-inquiry-header">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path
                                            d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                        <polyline points="22,6 12,13 2,6" />
                                    </svg>
                                    Send inquiry
                                </button>
                                <button class="tour-btn-booking" id="tour-btn-booking-header">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="7 10 12 15 17 10" />
                                        <line x1="12" y1="15" x2="12" y2="3" />
                                    </svg>
                                    Book this tour
                                </button>
                            </div>
                        </div>
                        <div class="tour-header-right">
                            <div class="tour-gallery-main">
                                <img id="gallery-main-img" src="" alt="">
                                <span id="gallery-counter" class="gallery-counter">1 / 4</span>
                                <button id="gallery-prev" class="gallery-nav gallery-prev">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polyline points="15 18 9 12 15 6" />
                                    </svg>
                                </button>
                                <button id="gallery-next" class="gallery-nav gallery-next">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polyline points="9 18 15 12 9 6" />
                                    </svg>
                                </button>
                            </div>
                            <span class="tour-gallery-label">GALLERY</span>
                            <div id="gallery-thumbnails" class="gallery-thumbnails"></div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Sticky Nav -->
            <nav class="tour-jump-nav" id="tour-jump-nav">
                <div class="tour-jump-nav__inner">
                    <button class="tour-jump-to-pill" data-section="gallery"><svg width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <path d="M21 15l-5-5L5 21" />
                        </svg>Gallery</button>
                    <button class="tour-jump-to-pill" data-section="about"><svg width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="16" x2="12" y2="12" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                        </svg>About</button>
                    <button class="tour-jump-to-pill" data-section="wildlife"><svg width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>Wildlife</button>
                    <button class="tour-jump-to-pill" data-section="organizer"><svg width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>Organizer</button>
                    <button class="tour-jump-to-pill" data-section="itinerary"><svg width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="8" y1="6" x2="21" y2="6" />
                            <line x1="8" y1="12" x2="21" y2="12" />
                            <line x1="8" y1="18" x2="21" y2="18" />
                            <line x1="3" y1="6" x2="3.01" y2="6" />
                            <line x1="3" y1="12" x2="3.01" y2="12" />
                            <line x1="3" y1="18" x2="3.01" y2="18" />
                        </svg>Itinerary</button>
                    <button class="tour-jump-to-pill" data-section="locations"><svg width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>Locations</button>
                    <button class="tour-jump-to-pill" data-section="availability"><svg width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>Availability</button>
                    <button class="tour-jump-to-pill" data-section="includes"><svg width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>Included</button>
                    <button class="tour-jump-to-pill" data-section="transport"><svg width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="1" y="3" width="15" height="13" />
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                            <circle cx="5.5" cy="18.5" r="2.5" />
                            <circle cx="18.5" cy="18.5" r="2.5" />
                        </svg>Transport</button>
                    <button class="tour-jump-to-pill" data-section="faq"><svg width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>FAQ</button>
                    <button class="tour-jump-to-pill" data-section="terms"><svg width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                        </svg>Terms</button>
                    <button class="tour-jump-to-pill" data-section="physical"><svg width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 2v8h8" />
                            <path d="M7 21a5 5 0 0 1 5-5h3a5 5 0 0 1 5 5" />
                            <circle cx="8" cy="8" r="3" />
                        </svg>Physical</button>
                    <button class="tour-jump-to-pill" data-section="essentials"><svg width="14" height="14"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3 7 7 3-7 3-3 7-3-7-7-3 7-3 3-7z" />
                        </svg>Essentials</button>
                    <button class="tour-jump-to-pill" data-section="inquiry"><svg width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>Inquiry</button>
                </div>
            </nav>

            <!-- Contenido -->
            <main class="tour-modal-body">
                <div class="tour-modal-body__inner">

                    <!-- Galería -->
                    <section class="tour-section-card" id="section-gallery">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <path d="M21 15l-5-5L5 21" />
                            </svg>Gallery</h2>
                        <div id="tour-gallery-grid" class="tour-gallery-grid"></div>
                    </section>

                    <!-- Acerca de este tour -->
                    <section class="tour-section-card" id="section-about">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>About this expedition</h2>
                        <div id="tour-about-content"></div>
                    </section>

                    <!-- Fauna de la expedición -->
                    <section class="tour-section-card" id="section-wildlife">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg><span id="tour-wildlife-label-text">Wildlife you can spot</span></h2>
                        <div id="tour-wildlife-grid" class="tour-wildlife-grid"></div>
                    </section>

                    <!-- Organizador y Guías -->
                    <section class="tour-section-card" id="section-organizer">
                        <div class="tour-two-col">
                            <div class="tour-org-card">
                                <h3 class="tour-subsection-title">Organizer</h3>
                                <div id="tour-organizer-content" class="tour-organizer-content"></div>
                            </div>
                            <div class="tour-guides-card">
                                <h3 class="tour-subsection-title">Guides</h3>
                                <div id="tour-guides-content" class="tour-guides-content"></div>
                            </div>
                        </div>
                    </section>

                    <!-- Itinerario -->
                    <section class="tour-section-card" id="section-itinerary">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="8" y1="6" x2="21" y2="6" />
                                <line x1="8" y1="12" x2="21" y2="12" />
                                <line x1="8" y1="18" x2="21" y2="18" />
                                <line x1="3" y1="6" x2="3.01" y2="6" />
                                <line x1="3" y1="12" x2="3.01" y2="12" />
                                <line x1="3" y1="18" x2="3.01" y2="18" />
                            </svg>Itinerary</h2>
                        <button class="tour-itinerary-toggle" id="itinerary-toggle">
                            <span id="itinerary-toggle-text"></span>
                            <svg class="toggle-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </button>
                        <div id="itinerary-content" class="tour-itinerary-content"></div>
                    </section>

                    <!-- Ubicaciones y Mapa -->
                    <section class="tour-section-card" id="section-locations">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>Locations and map</h2>
                        <div id="tour-locations-list" class="tour-locations-list"></div>
                        <div id="tour-map" class="tour-map">
                            <iframe id="tour-map-iframe" class="tour-map-iframe" src="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade" allowfullscreen
                                aria-label="Tour route map"></iframe>
                            <div id="tour-map-fallback" class="tour-map-fallback" aria-hidden="true"></div>
                        </div>
                        <a id="tour-map-custom-link" class="tour-map-custom-link" href="#" target="_blank"
                            rel="noopener noreferrer">Open this route in Google Maps</a>
                    </section>

                    <!-- Disponibilidad y Precios -->
                    <section class="tour-section-card" id="section-availability">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>Availability and price</h2>
                        <div id="tour-availability-list" class="tour-availability-list"></div>
                        <div class="tour-price-box">
                            <div class="tour-price-main">
                                <span class="tour-price-label">PRICE FROM</span>
                                <span id="tour-price-value" class="tour-price-value"></span>
                                <span id="tour-price-secondary" class="tour-price-secondary"></span>
                                <div id="tour-price-breakdown" class="tour-price-breakdown"></div>
                            </div>
                            <div class="tour-price-box-actions">
                                <button type="button" class="tour-btn-inquiry-sm" id="tour-btn-inquiry-price">Send
                                    inquiry</button>
                                <button type="button" class="tour-btn-booking-sm" id="tour-btn-booking-price">Book this
                                    tour</button>
                            </div>
                        </div>
                    </section>

                    <!-- Incluido y No Incluido -->
                    <section class="tour-section-card" id="section-includes">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>Inclusions</h2>
                        <div class="tour-two-col">
                            <div class="tour-collapsible-box" id="tour-includes-box">
                                <button class="tour-collapsible-toggle" type="button">
                                    <span>Included</span>
                                    <svg class="toggle-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9" />
                                    </svg>
                                </button>
                                <div class="tour-collapsible-content">
                                    <div id="tour-includes-list" class="tour-check-list"></div>
                                </div>
                            </div>
                            <div class="tour-collapsible-box" id="tour-excludes-box">
                                <button class="tour-collapsible-toggle" type="button">
                                    <span>Not included</span>
                                    <svg class="toggle-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9" />
                                    </svg>
                                </button>
                                <div class="tour-collapsible-content">
                                    <div id="tour-excludes-list" class="tour-check-list tour-check-list--exclude"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Transporte y Alojamiento -->
                    <section class="tour-section-card" id="section-transport">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="1" y="3" width="15" height="13" />
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                <circle cx="5.5" cy="18.5" r="2.5" />
                                <circle cx="18.5" cy="18.5" r="2.5" />
                            </svg>Transport and accommodation</h2>
                        <div class="tour-transport-grid">
                            <div class="tour-transport-block">
                                <span class="tour-transport-label">TRANSPORT</span>
                                <p id="tour-transport-value"></p>
                            </div>
                            <div class="tour-transport-block">
                                <span class="tour-transport-label">ACCOMMODATION</span>
                                <p id="tour-accommodation-value"></p>
                            </div>
                            <div class="tour-transport-block">
                                <span class="tour-transport-label">PACE</span>
                                <p id="tour-pace-value"></p>
                            </div>
                        </div>
                    </section>

                    <!-- FAQ -->
                    <section class="tour-section-card" id="section-faq">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                <line x1="12" y1="17" x2="12.01" y2="17" />
                            </svg>Frequently asked questions</h2>
                        <div class="tour-collapsible-box">
                            <button class="tour-collapsible-toggle" type="button">
                                <span>Show FAQ</span>
                                <svg class="toggle-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </button>
                            <div class="tour-collapsible-content">
                                <div id="tour-faq-list" class="tour-faq-list"></div>
                            </div>
                        </div>
                    </section>

                    <section class="tour-section-card" id="section-terms">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                                <line x1="16" y1="13" x2="8" y2="13" />
                            </svg>Terms and conditions</h2>
                        <div class="tour-collapsible-box">
                            <button class="tour-collapsible-toggle" type="button">
                                <span>Show terms</span>
                                <svg class="toggle-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </button>
                            <div class="tour-collapsible-content">
                                <div class="tour-policies-box">
                                    <div id="tour-terms-text"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="tour-section-card" id="section-physical">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M13 2v8h8" />
                                <path d="M7 21a5 5 0 0 1 5-5h3a5 5 0 0 1 5 5" />
                                <circle cx="8" cy="8" r="3" />
                            </svg>Physical demands</h2>
                        <div class="tour-collapsible-box">
                            <button class="tour-collapsible-toggle" type="button">
                                <span>Show physical demands</span>
                                <svg class="toggle-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </button>
                            <div class="tour-collapsible-content">
                                <div class="tour-policies-box">
                                    <div id="tour-physical-demands-text"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="tour-section-card" id="section-essentials">
                        <h2 class="tour-section-title"><svg class="tour-section-title-icon" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2l3 7 7 3-7 3-3 7-3-7-7-3 7-3 3-7z" />
                            </svg>Trip essentials</h2>

                        <div class="tour-essentials-stack">
                            <div class="tour-collapsible-box" id="tour-final-includes-box">
                                <button class="tour-collapsible-toggle" type="button">
                                    <span>What is included</span>
                                    <svg class="toggle-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9" />
                                    </svg>
                                </button>
                                <div class="tour-collapsible-content">
                                    <div id="tour-final-includes-content" class="tour-final-includes-content"></div>
                                </div>
                            </div>

                            <div class="tour-collapsible-box" id="tour-final-when-box">
                                <button class="tour-collapsible-toggle" type="button">
                                    <span>When to travel</span>
                                    <svg class="toggle-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9" />
                                    </svg>
                                </button>
                                <div class="tour-collapsible-content">
                                    <div class="tour-policies-box">
                                        <div id="tour-final-when-text"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="tour-collapsible-box" id="tour-final-americans-box">
                                <button class="tour-collapsible-toggle" type="button">
                                    <span>Can Americans Join?</span>
                                    <svg class="toggle-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9" />
                                    </svg>
                                </button>
                                <div class="tour-collapsible-content">
                                    <div class="tour-policies-box">
                                        <div id="tour-final-americans-text"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="tour-collapsible-box" id="tour-final-rules-box">
                                <button class="tour-collapsible-toggle" type="button">
                                    <span>Rules, terms and conditions</span>
                                    <svg class="toggle-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9" />
                                    </svg>
                                </button>
                                <div class="tour-collapsible-content">
                                    <div class="tour-policies-box">
                                        <div id="tour-final-rules-text"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="tour-collapsible-box" id="tour-final-faq-box">
                                <button class="tour-collapsible-toggle" type="button">
                                    <span>Frequently asked questions</span>
                                    <svg class="toggle-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9" />
                                    </svg>
                                </button>
                                <div class="tour-collapsible-content">
                                    <div id="tour-final-faq-list" class="tour-faq-list"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Llamada a Consulta -->
                    <section class="tour-inquiry-cta" id="section-inquiry">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#63B78B" stroke-width="1.5">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        <h2>Ready to book this <em>expedition</em>?</h2>
                        <p>Send us an inquiry and our team will reply within 24 hours with availability and next steps.</p>
                        <div class="tour-inquiry-buttons">
                            <button type="button" class="tour-btn-inquiry-lg" id="tour-btn-inquiry-cta">Send
                                inquiry</button>
                            <button type="button" class="tour-btn-booking-lg" id="tour-btn-booking-cta">Book this
                                tour</button>
                            <a href="<?php echo esc_url(home_url('/tours')); ?>" class="tour-btn-outline">View all
                                tours</a>
                        </div>
                    </section>
                    <div id="tour-image-lightbox" class="tour-image-lightbox" aria-hidden="true">
                        <button type="button" class="tour-image-lightbox__close" id="tour-image-lightbox-close"
                            aria-label="Close image">×</button>
                        <div class="tour-image-lightbox__inner">
                            <img id="tour-image-lightbox-img" src="" alt="">
                            <p id="tour-image-lightbox-caption" class="tour-image-lightbox__caption"></p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php
}
;




/**
 * Shortcode: [dualgrid_galeria]
 * Muestra TODAS las categorías creadas como filtros, aunque estén vacías.
 */
add_shortcode('dualgrid_galeria', function ($atts) {
    $atts = shortcode_atts([
        'categoria' => 'all',
    ], $atts, 'dualgrid_galeria');

    // Obtener TODAS las categorías (incluso sin fotos) para los filtros
    $terms = get_terms([
        'taxonomy' => 'dg_gallery_cat',
        'hide_empty' => false, // ← CAMBIO CLAVE: muestra categorías vacías también
    ]);

    if (is_wp_error($terms)) {
        $terms = [];
    }

    // Query de imágenes
    $args = [
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    ];

    // Si se pasa categoría por shortcode, filtrar
    if (!empty($atts['categoria']) && $atts['categoria'] !== 'all') {
        $args['tax_query'] = [
            [
                'taxonomy' => 'dg_gallery_cat',
                'field' => 'slug',
                'terms' => array_map('trim', explode(',', $atts['categoria'])),
            ]
        ];
    }

    $query = new WP_Query($args);

    ob_start();
    $gallery_id = uniqid('dg-gallery-');
    ?>
    <div class="dg-gallery-wrapper" id="<?php echo esc_attr($gallery_id); ?>">

        <!-- Filtros: siempre muestra todas las categorías creadas -->
        <div class="dg-gallery-filters">
            <button class="dg-filter-btn active" data-filter="all">All</button>
            <?php foreach ($terms as $term): ?>
                <button class="dg-filter-btn" data-filter="<?php echo esc_attr($term->slug); ?>">
                    <?php echo esc_html($term->name); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Grid Masonry -->
        <div class="dg-gallery-grid">
            <?php
            $index = 0;
            while ($query->have_posts()):
                $query->the_post();
                $post_id = get_the_ID();
                $img_url = wp_get_attachment_url($post_id);
                $img_full = wp_get_attachment_image_src($post_id, 'large');
                $img_full = $img_full ? $img_full[0] : $img_url;
                $title = get_the_title($post_id);
                $location_meta = (string) get_post_meta($post_id, '_dg_location', true);

                $item_terms = get_the_terms($post_id, 'dg_gallery_cat');
                $cat_slugs = [];
                $cat_names = [];
                if (!empty($item_terms) && !is_wp_error($item_terms)) {
                    foreach ($item_terms as $t) {
                        $cat_slugs[] = $t->slug;
                        $cat_names[] = $t->name;
                    }
                }

                $cats = implode(' ', $cat_slugs);
                $cat_label = !empty($cat_names) ? implode(' · ', $cat_names) : '';
                $subtitle = trim(($location_meta !== '' ? $location_meta . ' · ' : '') . $cat_label, ' ·');
                ?>
                <div class="dg-gallery-item" data-categories="<?php echo esc_attr($cats); ?>"
                    data-index="<?php echo (int) $index; ?>" data-full="<?php echo esc_url($img_full); ?>"
                    data-title="<?php echo esc_attr($title); ?>" data-subtitle="<?php echo esc_attr($subtitle); ?>">
                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
                    <div class="dg-item-overlay">
                        <div class="dg-item-info">
                            <h3><?php echo esc_html($title); ?></h3>
                            <?php if ($subtitle): ?>
                                <span><?php echo esc_html($subtitle); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
                $index++;
            endwhile;
            wp_reset_postdata();
            ?>
        </div>

        <!-- Lightbox -->
        <div class="dg-lightbox" aria-hidden="true">
            <button class="dg-lightbox-close" aria-label="Close">&times;</button>
            <button class="dg-lightbox-prev" aria-label="Previous">&#8249;</button>
            <button class="dg-lightbox-next" aria-label="Next">&#8250;</button>

            <div class="dg-lightbox-content">
                <img src="" alt="" class="dg-lightbox-img">
                <div class="dg-lightbox-caption">
                    <h3 class="dg-lightbox-title"></h3>
                    <p class="dg-lightbox-subtitle"></p>
                </div>
            </div>
        </div>

    </div>
    <?php
    return ob_get_clean();
});



/* ============================================================
   DUALGRID BLOG — Shortcode + CSS + JS (v4.1 — Fix enlaces)
   Usa IDs explícitos para evitar conflictos con Elementor/WP
   ============================================================ */

/**
 * Shortcode: [dualgrid_blog]
 * Grid con post destacado horizontal + filas de 3 y 2 columnas
 */
add_shortcode('dualgrid_blog', function ($atts) {
    $atts = shortcode_atts([
        'posts_per_page' => 9,
    ], $atts, 'dualgrid_blog');

    // Obtener categorías con posts
    $categories = get_categories([
        'orderby' => 'name',
        'order' => 'ASC',
        'number' => 10,
    ]);

    // Query de posts
    $paged = max(1, get_query_var('paged'));
    $args = [
        'post_type' => 'post',
        'posts_per_page' => intval($atts['posts_per_page']),
        'paged' => $paged,
        'post_status' => 'publish',
    ];

    $query = new WP_Query($args);
    $posts = $query->posts;

    ob_start();
    ?>
    <section class="dg-blog-section">
        <div class="dg-blog-container">

            <!-- Header: Buscador + Filtros -->
            <div class="dg-blog-header">
                <div class="dg-blog-search">
                    <input type="text" id="dg-blog-search" placeholder="Search articles..." autocomplete="off">
                </div>
                <div class="dg-blog-filters">
                    <button class="dg-filter-btn active" data-filter="all">All</button>
                    <?php foreach ($categories as $cat): ?>
                        <button class="dg-filter-btn" data-filter="<?php echo esc_attr($cat->slug); ?>">
                            <?php echo esc_html($cat->name); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if ($query->have_posts()): ?>

                <?php
                // === POST DESTACADO (primero) ===
                $featured = array_shift($posts);
                setup_postdata($featured);
                $f_cats = get_the_category($featured->ID);
                $f_cat_slug = !empty($f_cats) ? $f_cats[0]->slug : '';
                $f_cat_name = !empty($f_cats) ? $f_cats[0]->name : 'Article';
                $f_thumb = get_the_post_thumbnail_url($featured->ID, 'large');
                if (!$f_thumb)
                    $f_thumb = 'https://via.placeholder.com/800x600/18382E/FFFFFF?text=DualGrid';
                $content = get_post_field('post_content', $featured->ID);
                $word_count = str_word_count(strip_tags($content));
                $read_time = max(1, ceil($word_count / 200));
                ?>
                <a href="<?php echo esc_url(get_permalink($featured->ID)); ?>" class="dg-featured-card"
                    data-category="<?php echo esc_attr($f_cat_slug); ?>"
                    data-title="<?php echo esc_attr(strtolower(get_the_title($featured->ID))); ?>">
                    <div class="dg-featured-img">
                        <img src="<?php echo esc_url($f_thumb); ?>" alt="<?php echo esc_attr(get_the_title($featured->ID)); ?>">
                        <span class="dg-img-badge"><?php echo esc_html($f_cat_name); ?></span>
                    </div>
                    <div class="dg-featured-body">
                        <div class="dg-featured-meta-top">
                            <span class="dg-featured-badge"><?php echo esc_html($f_cat_name); ?></span>
                            <span class="dg-featured-readtime"><?php echo $read_time; ?> min read</span>
                        </div>
                        <h2 class="dg-featured-title"><?php echo esc_html(get_the_title($featured->ID)); ?></h2>
                        <p class="dg-featured-excerpt"><?php echo wp_trim_words(get_the_excerpt($featured->ID), 28, '...'); ?>
                        </p>
                        <div class="dg-featured-author-row">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            <span><?php echo esc_html(get_the_author_meta('display_name', $featured->post_author)); ?></span>
                            <span style="opacity:0.4">|</span>
                            <span><?php echo get_the_date('M j, Y', $featured->ID); ?></span>
                        </div>
                        <span class="dg-featured-read">Read article →</span>
                    </div>
                </a>

                <?php
                // === GRID 3 COLUMNAS (posts 2-4) ===
                $row3 = array_splice($posts, 0, 3);
                if (!empty($row3)):
                    ?>
                    <div class="dg-blog-grid" id="dg-blog-grid">
                        <?php foreach ($row3 as $post):
                            setup_postdata($post); ?>
                            <?php
                            $post_cats = get_the_category($post->ID);
                            $cat_slug = !empty($post_cats) ? $post_cats[0]->slug : '';
                            $cat_name = !empty($post_cats) ? $post_cats[0]->name : 'Article';
                            $thumb = get_the_post_thumbnail_url($post->ID, 'medium_large');
                            if (!$thumb)
                                $thumb = 'https://via.placeholder.com/800x600/18382E/FFFFFF?text=DualGrid';
                            $content_p = get_post_field('post_content', $post->ID);
                            $wc = str_word_count(strip_tags($content_p));
                            $rt = max(1, ceil($wc / 200));
                            ?>
                            <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="dg-card"
                                data-category="<?php echo esc_attr($cat_slug); ?>"
                                data-title="<?php echo esc_attr(strtolower(get_the_title($post->ID))); ?>">
                                <div class="dg-card-img">
                                    <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title($post->ID)); ?>">
                                    <span class="dg-img-badge"><?php echo esc_html($cat_name); ?></span>
                                </div>
                                <div class="dg-card-body">
                                    <div class="dg-card-meta-top">
                                        <span class="dg-card-badge"><?php echo esc_html($cat_name); ?></span>
                                        <span class="dg-card-readtime"><?php echo $rt; ?> min read</span>
                                    </div>
                                    <h3 class="dg-card-title"><?php echo esc_html(get_the_title($post->ID)); ?></h3>
                                    <p class="dg-card-excerpt"><?php echo wp_trim_words(get_the_excerpt($post->ID), 18, '...'); ?></p>
                                    <div class="dg-card-meta-bottom">
                                        <span
                                            class="author"><?php echo esc_html(get_the_author_meta('display_name', $post->post_author)); ?></span>
                                        <span class="sep"></span>
                                        <span class="date"><?php echo get_the_date('M j, Y', $post->ID); ?></span>
                                    </div>
                                    <span class="dg-card-read">Read article →</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php
                // === GRID 2 COLUMNAS (posts 5-6) ===
                $row2 = array_splice($posts, 0, 2);
                if (!empty($row2)):
                    ?>
                    <div class="dg-blog-grid-two" id="dg-blog-grid-two">
                        <?php foreach ($row2 as $post):
                            setup_postdata($post); ?>
                            <?php
                            $post_cats = get_the_category($post->ID);
                            $cat_slug = !empty($post_cats) ? $post_cats[0]->slug : '';
                            $cat_name = !empty($post_cats) ? $post_cats[0]->name : 'Article';
                            $thumb = get_the_post_thumbnail_url($post->ID, 'medium_large');
                            if (!$thumb)
                                $thumb = 'https://via.placeholder.com/800x600/18382E/FFFFFF?text=DualGrid';
                            $content_p = get_post_field('post_content', $post->ID);
                            $wc = str_word_count(strip_tags($content_p));
                            $rt = max(1, ceil($wc / 200));
                            ?>
                            <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="dg-card"
                                data-category="<?php echo esc_attr($cat_slug); ?>"
                                data-title="<?php echo esc_attr(strtolower(get_the_title($post->ID))); ?>">
                                <div class="dg-card-img">
                                    <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title($post->ID)); ?>">
                                    <span class="dg-img-badge"><?php echo esc_html($cat_name); ?></span>
                                </div>
                                <div class="dg-card-body">
                                    <div class="dg-card-meta-top">
                                        <span class="dg-card-badge"><?php echo esc_html($cat_name); ?></span>
                                        <span class="dg-card-readtime"><?php echo $rt; ?> min read</span>
                                    </div>
                                    <h3 class="dg-card-title"><?php echo esc_html(get_the_title($post->ID)); ?></h3>
                                    <p class="dg-card-excerpt"><?php echo wp_trim_words(get_the_excerpt($post->ID), 18, '...'); ?></p>
                                    <div class="dg-card-meta-bottom">
                                        <span
                                            class="author"><?php echo esc_html(get_the_author_meta('display_name', $post->post_author)); ?></span>
                                        <span class="sep"></span>
                                        <span class="date"><?php echo get_the_date('M j, Y', $post->ID); ?></span>
                                    </div>
                                    <span class="dg-card-read">Read article →</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php
                // === RESTO DE POSTS (si hay más) ===
                if (!empty($posts)):
                    $remaining_chunks = array_chunk($posts, 3);
                    foreach ($remaining_chunks as $chunk):
                        ?>
                        <div class="dg-blog-grid" style="margin-top:32px;">
                            <?php foreach ($chunk as $post):
                                setup_postdata($post); ?>
                                <?php
                                $post_cats = get_the_category($post->ID);
                                $cat_slug = !empty($post_cats) ? $post_cats[0]->slug : '';
                                $cat_name = !empty($post_cats) ? $post_cats[0]->name : 'Article';
                                $thumb = get_the_post_thumbnail_url($post->ID, 'medium_large');
                                if (!$thumb)
                                    $thumb = 'https://via.placeholder.com/800x600/18382E/FFFFFF?text=DualGrid';
                                $content_p = get_post_field('post_content', $post->ID);
                                $wc = str_word_count(strip_tags($content_p));
                                $rt = max(1, ceil($wc / 200));
                                ?>
                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="dg-card"
                                    data-category="<?php echo esc_attr($cat_slug); ?>"
                                    data-title="<?php echo esc_attr(strtolower(get_the_title($post->ID))); ?>">
                                    <div class="dg-card-img">
                                        <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title($post->ID)); ?>">
                                        <span class="dg-img-badge"><?php echo esc_html($cat_name); ?></span>
                                    </div>
                                    <div class="dg-card-body">
                                        <div class="dg-card-meta-top">
                                            <span class="dg-card-badge"><?php echo esc_html($cat_name); ?></span>
                                            <span class="dg-card-readtime"><?php echo $rt; ?> min read</span>
                                        </div>
                                        <h3 class="dg-card-title"><?php echo esc_html(get_the_title($post->ID)); ?></h3>
                                        <p class="dg-card-excerpt"><?php echo wp_trim_words(get_the_excerpt($post->ID), 18, '...'); ?></p>
                                        <div class="dg-card-meta-bottom">
                                            <span
                                                class="author"><?php echo esc_html(get_the_author_meta('display_name', $post->post_author)); ?></span>
                                            <span class="sep"></span>
                                            <span class="date"><?php echo get_the_date('M j, Y', $post->ID); ?></span>
                                        </div>
                                        <span class="dg-card-read">Read article →</span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <?php
                    endforeach;
                endif;
                ?>

            <?php else: ?>
                <div class="dg-blog-empty visible">No articles were found.</div>
            <?php endif; ?>

            <div class="dg-blog-empty" id="dg-blog-empty">No articles were found.</div>

            <?php if ($query->max_num_pages > 1): ?>
                <div class="dg-pagination" style="text-align:center; margin-top:48px;">
                    <?php
                    echo paginate_links([
                        'total' => $query->max_num_pages,
                        'current' => $paged,
                        'prev_text' => '← Previous',
                        'next_text' => 'Next →',
                    ]);
                    ?>
                </div>
            <?php endif; ?>

        </div>
    </section>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
});

/**
 * Shortcode: [dualgrid_single]
 * Hero ancho completo + breadcrumbs + contenido + share + related
 * Breadcrumbs: Home > Journal > Título (sin categoría intermedia)
 */
add_shortcode('dualgrid_single', function ($atts) {
    if (!is_singular('post')) {
        return '<p style="text-align:center; padding:40px;">This shortcode only works on single blog posts.</p>';
    }

    $post_id = get_the_ID();
    $categories = get_the_category($post_id);
    $cat_name = !empty($categories) ? $categories[0]->name : 'Blog';
    $thumb_url = has_post_thumbnail() ? get_the_post_thumbnail_url($post_id, 'full') : '';

    // Breadcrumbs: Home > Journal > Título actual
    $blog_page = get_permalink(get_option('page_for_posts')) ?: home_url('/blog');
    // Breadcrumbs: Home > Blog > Título actual
    $breadcrumbs = [
        ['label' => 'Home', 'url' => home_url('/')],
        ['label' => 'Blog', 'url' => home_url('/blog')],
    ];
    $breadcrumbs[] = ['label' => get_the_title(), 'url' => '', 'current' => true];

    // Related posts
    $related = [];
    if (!empty($categories)) {
        $related_query = new WP_Query([
            'post_type' => 'post',
            'posts_per_page' => 3,
            'post__not_in' => [$post_id],
            'category__in' => wp_list_pluck($categories, 'term_id'),
            'orderby' => 'rand',
        ]);
        $related = $related_query->posts;
        wp_reset_postdata();
    }

    $share_url = urlencode(get_permalink());
    $share_title = urlencode(get_the_title());

    ob_start();
    ?>

    <!-- HERO ANCHO COMPLETO -->
    <section class="dg-single-hero">
        <?php if ($thumb_url): ?>
            <div class="dg-single-hero-bg" style="background-image: url('<?php echo esc_url($thumb_url); ?>');"></div>
        <?php else: ?>
            <div class="dg-single-hero-bg" style="background-color: #18382E;"></div>
        <?php endif; ?>
        <div class="dg-single-hero-content">
            <span class="dg-single-category"><?php echo esc_html($cat_name); ?></span>
            <h1 class="dg-single-title"><?php the_title(); ?></h1>
            <div class="dg-single-meta">
                <span><?php the_author(); ?></span>
                <span class="sep"></span>
                <span><?php echo get_the_date('M j, Y'); ?></span>
            </div>
        </div>
    </section>

    <!-- BREADCRUMBS -->
    <nav class="dg-breadcrumbs" aria-label="Breadcrumb">
        <div class="dg-breadcrumbs-inner">
            <?php foreach ($breadcrumbs as $i => $crumb): ?>
                <?php if (!$crumb['current']): ?>
                    <a href="<?php echo esc_url($crumb['url']); ?>"><?php echo esc_html($crumb['label']); ?></a>
                    <span class="dg-bc-sep">›</span>
                <?php else: ?>
                    <span class="dg-bc-current"><?php echo esc_html($crumb['label']); ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </nav>

    <!-- CONTENIDO -->
    <article class="dg-single-content">
        <?php the_content(); ?>
    </article>

    <!-- SHARE -->
    <div class="dg-single-share">
        <p class="dg-share-label">Share this article</p>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr($share_url); ?>&quote=<?php echo esc_attr($share_title); ?>"
            target="_blank" rel="noopener noreferrer" class="dg-btn-facebook">
            <svg viewBox="0 0 24 24">
                <path
                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
            </svg>
            Share on Facebook
        </a>
    </div>

    <!-- RELATED POSTS -->
    <?php if (!empty($related)): ?>
        <section class="dg-related-section">
            <div class="dg-related-header">
                <h3>More from the blog</h3>
                <a href="<?php echo esc_url(home_url('/blog')); ?>">View all →</a>
            </div>
            <div class="dg-related-grid">
                <?php foreach ($related as $rel_post):
                    setup_postdata($GLOBALS['post'] = $rel_post); ?>
                    <?php
                    $rel_cats = get_the_category($rel_post->ID);
                    $rel_cat = !empty($rel_cats) ? $rel_cats[0]->name : 'Article';
                    ?>
                    <a href="<?php the_permalink(); ?>" class="dg-card">
                        <div class="dg-card-img">
                            <?php if (has_post_thumbnail($rel_post->ID)): ?>
                                <?php echo get_the_post_thumbnail($rel_post->ID, 'medium_large', ['alt' => get_the_title($rel_post->ID)]); ?>
                            <?php else: ?>
                                <img src="https://via.placeholder.com/800x600/18382E/FFFFFF?text=DualGrid"
                                    alt="<?php echo esc_attr(get_the_title($rel_post->ID)); ?>">
                            <?php endif; ?>
                            <span class="dg-card-badge"><?php echo esc_html($rel_cat); ?></span>
                        </div>
                        <div class="dg-card-body">
                            <h2 class="dg-card-title"><?php echo esc_html(get_the_title($rel_post->ID)); ?></h2>
                            <p class="dg-card-excerpt"><?php echo wp_trim_words(get_the_excerpt($rel_post->ID), 14, '...'); ?></p>
                            <div class="dg-card-meta">
                                <span class="author"><?php the_author(); ?></span>
                                <span class="sep"></span>
                                <span class="date"><?php echo get_the_date('M j, Y', $rel_post->ID); ?></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach;
                wp_reset_postdata(); ?>
            </div>
        </section>
    <?php endif; ?>

    <?php
    return ob_get_clean();
});



/**
 * Shortcode: [we_contact_section]
 * Sección de contacto con formulario CF7.
 */
add_shortcode('we_contact_section', function ($atts) {
    $atts = shortcode_atts([
        'email' => get_option('admin_email'),
        'phone' => '+506 8888 8888',
        'location' => 'San Jose, Costa Rica',
        'response' => 'We answer every inquiry within 24 hours. For urgent matters, call us directly.',
        'heading' => 'Start the <em>conversation</em>',
        'description' => 'Tell us about your dream expedition: where you want to go, what you want to see and how you want to travel.',
        'cf7_id' => '4cbeadb',
    ], $atts, 'we_contact_section');

    // DEBUG: Verificar que CF7 está activo
    if (!function_exists('wpcf7_enqueue_scripts')) {
        return '<p style="color:red; padding:20px; background:#fee; border:1px solid red;"><strong>ERROR:</strong> Contact Form 7 is not active or has not been loaded.</p>';
    }

    // DEBUG: Verificar que el ID no está vacío
    $cf7_id = trim($atts['cf7_id']);
    if (empty($cf7_id)) {
        return '<p style="color:red; padding:20px; background:#fee; border:1px solid red;"><strong>ERROR:</strong> No CF7 form ID was provided.</p>';
    }

    // Encolar scripts de CF7
    wpcf7_enqueue_scripts();
    wpcf7_enqueue_styles();

    // Construir el shortcode exacto de CF7
    $cf7_shortcode = '[contact-form-7 id="' . $cf7_id . '"]';

    // DEBUG (quita esto después de probar): muestra el ID que está usando
    // $debug_info = '<!-- CF7 Debug: ID=' . $cf7_id . ' | Shortcode=' . $cf7_shortcode . ' -->';

    ob_start();
    ?>
    <section class="we-contact-section">
        <div class="we-contact-container">

            <div class="we-contact-info">
                <div class="we-contact-heading">
                    <h2><?php echo wp_kses_post($atts['heading']); ?></h2>
                    <p><?php echo esc_html($atts['description']); ?></p>
                </div>

                <div class="we-contact-details">
                    <div class="we-contact-item">
                        <div class="we-contact-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                        </div>
                        <div class="we-contact-text">
                            <span class="we-contact-label">Email</span>
                            <a href="mailto:<?php echo esc_attr($atts['email']); ?>"
                                class="we-contact-value"><?php echo esc_html($atts['email']); ?></a>
                        </div>
                    </div>

                    <div class="we-contact-item">
                        <div class="we-contact-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                            </svg>
                        </div>
                        <div class="we-contact-text">
                            <span class="we-contact-label">Phone</span>
                            <a href="tel:<?php echo esc_attr(preg_replace('/\D/', '', $atts['phone'])); ?>"
                                class="we-contact-value"><?php echo esc_html($atts['phone']); ?></a>
                        </div>
                    </div>

                    <div class="we-contact-item">
                        <div class="we-contact-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                        </div>
                        <div class="we-contact-text">
                            <span class="we-contact-label">Base</span>
                            <span class="we-contact-value"><?php echo esc_html($atts['location']); ?></span>
                        </div>
                    </div>
                </div>

                <div class="we-contact-notice">
                    <span class="we-contact-notice-label">Response time</span>
                    <p><?php echo esc_html($atts['response']); ?></p>
                </div>
            </div>

            <!-- Formulario CF7 -->
            <div class="we-contact-form-wrap">
                <?php
                $form_html = do_shortcode($cf7_shortcode);

                // Si CF7 devuelve vacío o el mensaje de "no encontrado", mostrar error
                if (empty($form_html) || strpos($form_html, 'No se ha encontrado') !== false || strpos($form_html, 'not found') !== false) {
                    echo '<p style="color:red; padding:20px; background:#fee; border:1px solid red;"><strong>ERROR CF7:</strong> The form with ID <code>' . esc_html($cf7_id) . '</code> could not be loaded.<br>Check that the ID is correct in Contact > Contact Forms.</p>';
                } else {
                    echo $form_html;
                }
                ?>
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
});



/**
 * Shortcode: [we_overview_section]
 * Sección Overview con sidebar "De un vistazo" + animaciones.
 */
add_shortcode('we_overview_section', function ($atts) {
    $atts = shortcode_atts([
        'label' => 'Overview',
        'title' => 'The',
        'title_italic' => 'experience',
        'description' => 'Expert-led expeditions to remarkable wildlife destinations, shaped around strong guiding, small groups and carefully paced itineraries.',
        'sidebar_title' => 'At a glance',
        'duration' => '5-14 days',
        'group_size' => 'Max. 6',
        'difficulty' => 'Easy to Moderate',
    ], $atts, 'we_overview_section');

    ob_start();
    ?>
    <section class="we-overview-section">
        <div class="we-overview-container">

            <!-- Columna izquierda -->
            <div class="we-overview-main">
                <span class="we-overview-label we-anim-line">
                    <?php echo esc_html($atts['label']); ?>
                </span>
                <h2 class="we-overview-title we-anim we-anim-fade-up we-anim-d1">
                    <?php echo esc_html($atts['title']); ?> <em><?php echo esc_html($atts['title_italic']); ?></em>
                </h2>
                <p class="we-overview-text we-anim we-anim-fade-up we-anim-d2">
                    <?php echo esc_html($atts['description']); ?>
                </p>
            </div>

            <!-- Columna derecha -->
            <div class="we-overview-sidebar we-anim we-anim-fade-right we-anim-d2">
                <span class="we-sidebar-label we-anim we-anim-fade-up we-anim-d3">
                    <?php echo esc_html($atts['sidebar_title']); ?>
                </span>

                <div class="we-glance-list">

                    <!-- Duración -->
                    <div class="we-glance-item we-anim we-anim-fade-up we-anim-d3">
                        <div class="we-glance-icon">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </div>
                        <div class="we-glance-content">
                            <span class="we-glance-key">Duration</span>
                            <span class="we-glance-value"><?php echo esc_html($atts['duration']); ?></span>
                        </div>
                    </div>

                    <!-- Grupo -->
                    <div class="we-glance-item we-anim we-anim-fade-up we-anim-d4">
                        <div class="we-glance-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                        </div>
                        <div class="we-glance-content">
                            <span class="we-glance-key">Group size</span>
                            <span class="we-glance-value"><?php echo esc_html($atts['group_size']); ?></span>
                        </div>
                    </div>

                    <!-- Dificultad -->
                    <div class="we-glance-item we-anim we-anim-fade-up we-anim-d5">
                        <div class="we-glance-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5z" />
                                <path d="M2 17l10 5 10-5" />
                                <path d="M2 12l10 5 10-5" />
                            </svg>
                        </div>
                        <div class="we-glance-content">
                            <span class="we-glance-key">Difficulty</span>
                            <span class="we-glance-value"><?php echo esc_html($atts['difficulty']); ?></span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
    <?php
    return ob_get_clean();
});


/**
 * Shortcode: [mission_section]
 *
 * Ejemplo:
 * [mission_section label="Nuestra misión" heading="Inspirar amor por lo salvaje" italic_part="protegerlo" dash_text="y"]
 */
add_shortcode('mission_section', function ($atts) {
    $atts = shortcode_atts([
        'label' => 'Our mission',
        'heading' => 'Inspire love for the wild',
        'italic_part' => 'and protect it',
        'dash_text' => '&',
        'paragraph_1' => 'WildEndemics was built to connect travelers with meaningful nature experiences.',
        'paragraph_2' => 'Each journey is designed to support conservation, local knowledge and a deeper relationship with place.',
    ], $atts, 'mission_section');

    ob_start();
    ?>
    <section class="mission-section dg-animate-on-scroll">
        <div class="mission-container">
            <div class="mission-col-left">
                <span class="mission-label"><?php echo esc_html($atts['label']); ?></span>
                <h2 class="mission-heading">
                    <?php echo esc_html($atts['heading']); ?><br>
                    <span class="dash"><?php echo esc_html($atts['dash_text']); ?></span>
                    <em><?php echo esc_html($atts['italic_part']); ?></em>
                </h2>
            </div>
            <div class="mission-col-right mission-body">
                <p><?php echo esc_html($atts['paragraph_1']); ?></p>
                <p><?php echo esc_html($atts['paragraph_2']); ?></p>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
});

/**
 * Shortcode: [values_section]
 *
 * Ejemplo:
 * [values_section label="Lo que nos define" heading="Nuestros" italic_word="valores"]
 */
add_shortcode('values_section', function ($atts) {
    $atts = shortcode_atts([
        'label' => 'What defines us',
        'heading' => 'Our',
        'italic_word' => 'values',
    ], $atts, 'values_section');

    $values = [
        [
            'icon' => 'heart',
            'title' => 'Authenticity',
            'desc' => 'Real experiences, real people and real nature without staged encounters.',
        ],
        [
            'icon' => 'compass',
            'title' => 'Experience',
            'desc' => 'Certified guides with years of field experience and deep local knowledge.',
        ],
        [
            'icon' => 'globe',
            'title' => 'Responsibility',
            'desc' => 'Low-impact operations and a genuine commitment to conservation.',
        ],
        [
            'icon' => 'users',
            'title' => 'Closeness',
            'desc' => 'Small groups that protect both the experience and the places we visit.',
        ],
    ];

    ob_start();
    ?>
    <section class="values-section">
        <div class="values-container">
            <div class="values-header dg-animate-on-scroll">
                <div class="values-label">
                    <span class="values-label-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <circle cx="12" cy="12" r="6" />
                            <circle cx="12" cy="12" r="2" />
                        </svg>
                    </span>
                    <?php echo esc_html($atts['label']); ?>
                </div>
                <h2 class="values-heading"><?php echo esc_html($atts['heading']); ?>
                    <em><?php echo esc_html($atts['italic_word']); ?></em>
                </h2>
            </div>
            <div class="values-grid">
                <?php foreach ($values as $value): ?>
                    <div class="values-card dg-animate-on-scroll">
                        <div class="values-icon-wrap">
                            <?php if ($value['icon'] === 'heart'): ?>
                                <svg viewBox="0 0 24 24">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                </svg>
                            <?php elseif ($value['icon'] === 'compass'): ?>
                                <svg viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76" />
                                </svg>
                            <?php elseif ($value['icon'] === 'globe'): ?>
                                <svg viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="2" y1="12" x2="22" y2="12" />
                                    <path
                                        d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                </svg>
                            <?php elseif ($value['icon'] === 'users'): ?>
                                <svg viewBox="0 0 24 24">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            <?php endif; ?>
                        </div>
                        <h3 class="values-card-title"><?php echo esc_html($value['title']); ?></h3>
                        <p class="values-card-desc"><?php echo esc_html($value['desc']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
});

/**
 * Shortcode: [team_section]
 *
 * Ejemplo:
 * [team_section label="El equipo" heading="Conoce a tus" italic_word="guías"]
 *
 * Los miembros se pasan como pares: name1, role1, img1, name2, role2, img2...
 */
add_shortcode('team_section', function ($atts) {
    $atts = shortcode_atts([
        'label' => 'The team',
        'heading' => 'Meet your',
        'italic_word' => 'guides',
        'name1' => 'José Alberto',
        'role1' => 'CEO and lead birding guide',
        'img1' => '',
        'name2' => 'Nils Navarro',
        'role2' => 'Birding guide',
        'img2' => '',
        'name3' => 'Diana DTen',
        'role3' => 'Photography guide',
        'img3' => '',
    ], $atts, 'team_section');

    $members = [];
    for ($i = 1; $i <= 3; $i++) {
        $members[] = [
            'name' => $atts["name{$i}"],
            'role' => $atts["role{$i}"],
            'img' => $atts["img{$i}"],
        ];
    }

    ob_start();
    ?>
    <section class="team-section">
        <div class="team-container">
            <div class="team-header dg-animate-on-scroll">
                <span class="team-label"><?php echo esc_html($atts['label']); ?></span>
                <h2 class="team-heading"><?php echo esc_html($atts['heading']); ?>
                    <em><?php echo esc_html($atts['italic_word']); ?></em>
                </h2>
            </div>
            <div class="team-grid">
                <?php foreach ($members as $member): ?>
                    <div class="team-member dg-animate-on-scroll">
                        <div class="team-image-wrap">
                            <?php if (!empty($member['img'])): ?>
                                <img src="<?php echo esc_url($member['img']); ?>" alt="<?php echo esc_attr($member['name']); ?>"
                                    loading="lazy">
                            <?php else: ?>
                                <div style="width:100%;height:100%;background:linear-gradient(135deg,#D4CFC7,#B8B0A5);"></div>
                            <?php endif; ?>
                        </div>
                        <h3 class="team-name"><?php echo esc_html($member['name']); ?></h3>
                        <p class="team-role"><?php echo esc_html($member['role']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
});
