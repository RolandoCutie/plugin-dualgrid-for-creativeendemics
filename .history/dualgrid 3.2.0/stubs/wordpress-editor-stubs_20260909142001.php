<?php
declare(strict_types=1);

/**
 * WordPress/CF7 editor stubs for static analysis only.
 *
 * This file is not loaded by plugin runtime; it only helps language servers
 * resolve global symbols in a non-WordPress environment.
 */

if (!defined('ABSPATH')) {
    define('ABSPATH', '/');
}

if (!class_exists('WP_Error')) {
    class WP_Error {
        public function __construct($code = '', $message = '', $data = null) {}
        public function get_error_message($code = '') { return ''; }
    }
}

if (!class_exists('WP_Query')) {
    class WP_Query {
        /** @var array<int, mixed> */
        public $posts = [];
        /** @var int */
        public $max_num_pages = 1;

        public function __construct($args = []) {}
        public function have_posts() { return false; }
        public function the_post() { return null; }
    }
}

if (!class_exists('WP_Term')) {
    class WP_Term {
        /** @var int */
        public $term_id = 0;
        /** @var string */
        public $slug = '';
        /** @var string */
        public $name = '';
        /** @var int */
        public $count = 0;
    }
}

if (!class_exists('WP_Post')) {
    class WP_Post {
        /** @var int */
        public $ID = 0;
        /** @var string */
        public $post_type = '';
        /** @var string */
        public $post_status = 'publish';
        /** @var string */
        public $post_title = '';
        /** @var string */
        public $post_content = '';
        /** @var string */
        public $post_excerpt = '';
    }
}

if (!class_exists('WP_Screen')) {
    class WP_Screen {
        /** @var string */
        public $id = 'upload';
    }
}

if (!class_exists('WP_REST_Request')) {
    class WP_REST_Request {
        public function get_param($key) { return null; }
    }
}

if (!class_exists('WP_REST_Response')) {
    class WP_REST_Response {
        public function __construct($data = null, int $status = 200, array $headers = []) {}
    }
}

if (!function_exists('add_action')) {
    function add_action(...$args) {
        return null;
    }
}

if (!function_exists('add_filter')) {
    function add_filter(...$args) {
        return null;
    }
}

if (!function_exists('add_menu_page')) {
    function add_menu_page(...$args) {
        return null;
    }
}

if (!function_exists('add_meta_box')) {
    function add_meta_box(...$args) {
        return null;
    }
}

if (!function_exists('add_query_arg')) {
    function add_query_arg(...$args) {
        return null;
    }
}

if (!function_exists('add_shortcode')) {
    function add_shortcode(...$args) {
        return null;
    }
}

if (!function_exists('add_submenu_page')) {
    function add_submenu_page(...$args) {
        return null;
    }
}

if (!function_exists('admin_url')) {
    function admin_url(...$args) {
        return '';
    }
}

if (!function_exists('apply_filters')) {
    function apply_filters(...$args) {
        return null;
    }
}

if (!function_exists('bloginfo')) {
    function bloginfo(...$args) {
        return '';
    }
}

if (!function_exists('check_admin_referer')) {
    function check_admin_referer(...$args) {
        return false;
    }
}

if (!function_exists('checked')) {
    function checked(...$args) {
        return null;
    }
}

if (!function_exists('current_user_can')) {
    function current_user_can(...$args) {
        return false;
    }
}

if (!function_exists('delete_post_meta')) {
    function delete_post_meta(...$args) {
        return false;
    }
}

if (!function_exists('delete_post_thumbnail')) {
    function delete_post_thumbnail(...$args) {
        return false;
    }
}

if (!function_exists('did_action')) {
    function did_action(...$args) {
        return false;
    }
}

if (!function_exists('do_shortcode')) {
    function do_shortcode(...$args) {
        return null;
    }
}

if (!function_exists('esc_attr')) {
    function esc_attr(...$args) {
        return '';
    }
}

if (!function_exists('esc_html')) {
    function esc_html(...$args) {
        return '';
    }
}

if (!function_exists('esc_js')) {
    function esc_js(...$args) {
        return '';
    }
}

if (!function_exists('esc_textarea')) {
    function esc_textarea(...$args) {
        return '';
    }
}

if (!function_exists('esc_url')) {
    function esc_url(...$args) {
        return '';
    }
}

if (!function_exists('esc_url_raw')) {
    function esc_url_raw(...$args) {
        return '';
    }
}

if (!function_exists('get_categories')) {
    function get_categories(...$args) {
        return [new WP_Term()];
    }
}

if (!function_exists('get_current_screen')) {
    function get_current_screen(...$args) {
        return new WP_Screen();
    }
}

if (!function_exists('get_footer')) {
    function get_footer(...$args) {
        return null;
    }
}

if (!function_exists('get_header')) {
    function get_header(...$args) {
        return null;
    }
}

if (!function_exists('get_option')) {
    function get_option(...$args) {
        return null;
    }
}

if (!function_exists('get_permalink')) {
    function get_permalink(...$args) {
        return '';
    }
}

if (!function_exists('get_post')) {
    function get_post(...$args) {
        return new WP_Post();
    }
}

if (!function_exists('get_post_field')) {
    function get_post_field(...$args) {
        return '';
    }
}

if (!function_exists('get_post_meta')) {
    function get_post_meta(...$args) {
        return null;
    }
}

if (!function_exists('get_post_thumbnail_id')) {
    function get_post_thumbnail_id(...$args) {
        return 0;
    }
}

if (!function_exists('get_post_type_archive_link')) {
    function get_post_type_archive_link(...$args) {
        return '';
    }
}

if (!function_exists('get_posts')) {
    function get_posts(...$args) {
        return [new WP_Post()];
    }
}

if (!function_exists('get_queried_object')) {
    function get_queried_object(...$args) {
        return null;
    }
}

if (!function_exists('get_query_var')) {
    function get_query_var(...$args) {
        return null;
    }
}

if (!function_exists('get_rest_url')) {
    function get_rest_url(...$args) {
        return '';
    }
}

if (!function_exists('get_term_by')) {
    function get_term_by(...$args) {
        return new WP_Term();
    }
}

if (!function_exists('get_term_link')) {
    function get_term_link(...$args) {
        return null;
    }
}

if (!function_exists('get_term_meta')) {
    function get_term_meta(...$args) {
        return null;
    }
}

if (!function_exists('get_terms')) {
    function get_terms(...$args) {
        return [new WP_Term()];
    }
}

if (!function_exists('get_the_author_meta')) {
    function get_the_author_meta(...$args) {
        return null;
    }
}

if (!function_exists('get_the_category')) {
    function get_the_category(...$args) {
        return [new WP_Term()];
    }
}

if (!function_exists('get_the_content')) {
    function get_the_content(...$args) {
        return null;
    }
}

if (!function_exists('get_the_date')) {
    function get_the_date(...$args) {
        return null;
    }
}

if (!function_exists('get_the_excerpt')) {
    function get_the_excerpt(...$args) {
        return null;
    }
}

if (!function_exists('get_the_ID')) {
    function get_the_ID(...$args) {
        return 0;
    }
}

if (!function_exists('get_the_post_thumbnail')) {
    function get_the_post_thumbnail(...$args) {
        return '';
    }
}

if (!function_exists('get_the_post_thumbnail_url')) {
    function get_the_post_thumbnail_url(...$args) {
        return '';
    }
}

if (!function_exists('get_the_terms')) {
    function get_the_terms(...$args) {
        return [new WP_Term()];
    }
}

if (!function_exists('get_the_title')) {
    function get_the_title(...$args) {
        return null;
    }
}

if (!function_exists('has_post_thumbnail')) {
    function has_post_thumbnail(...$args) {
        return false;
    }
}

if (!function_exists('have_posts')) {
    function have_posts(...$args) {
        return false;
    }
}

if (!function_exists('home_url')) {
    function home_url(...$args) {
        return '';
    }
}

if (!function_exists('is_admin')) {
    function is_admin(...$args) {
        return false;
    }
}

if (!function_exists('is_feed')) {
    function is_feed(...$args) {
        return false;
    }
}

if (!function_exists('is_main_query')) {
    function is_main_query(...$args) {
        return false;
    }
}

if (!function_exists('is_page')) {
    function is_page(...$args) {
        return false;
    }
}

if (!function_exists('is_post_type_archive')) {
    function is_post_type_archive(...$args) {
        return false;
    }
}

if (!function_exists('is_singular')) {
    function is_singular(...$args) {
        return false;
    }
}

if (!function_exists('is_tax')) {
    function is_tax(...$args) {
        return false;
    }
}

if (!function_exists('is_wp_error')) {
    function is_wp_error(...$args) {
        return false;
    }
}

if (!function_exists('language_attributes')) {
    function language_attributes(...$args) {
        return '';
    }
}

if (!function_exists('paginate_links')) {
    function paginate_links(...$args) {
        return '';
    }
}

if (!function_exists('plugin_dir_path')) {
    function plugin_dir_path(...$args) {
        return '';
    }
}

if (!function_exists('plugin_dir_url')) {
    function plugin_dir_url(...$args) {
        return '';
    }
}

if (!function_exists('register_post_type')) {
    function register_post_type(...$args) {
        return null;
    }
}

if (!function_exists('register_rest_route')) {
    function register_rest_route(...$args) {
        return null;
    }
}

if (!function_exists('register_taxonomy')) {
    function register_taxonomy(...$args) {
        return null;
    }
}

if (!function_exists('remove_menu_page')) {
    function remove_menu_page(...$args) {
        return null;
    }
}

if (!function_exists('sanitize_email')) {
    function sanitize_email(...$args) {
        return '';
    }
}

if (!function_exists('sanitize_file_name')) {
    function sanitize_file_name(...$args) {
        return '';
    }
}

if (!function_exists('sanitize_key')) {
    function sanitize_key(...$args) {
        return '';
    }
}

if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field(...$args) {
        return '';
    }
}

if (!function_exists('sanitize_textarea_field')) {
    function sanitize_textarea_field(...$args) {
        return '';
    }
}

if (!function_exists('sanitize_title')) {
    function sanitize_title(...$args) {
        return '';
    }
}

if (!function_exists('selected')) {
    function selected(...$args) {
        return null;
    }
}

if (!function_exists('set_post_thumbnail')) {
    function set_post_thumbnail(...$args) {
        return false;
    }
}

if (!function_exists('setup_postdata')) {
    function setup_postdata(...$args) {
        return false;
    }
}

if (!function_exists('shortcode_atts')) {
    function shortcode_atts(...$args) {
        return [];
    }
}

if (!function_exists('the_author')) {
    function the_author(...$args) {
        return null;
    }
}

if (!function_exists('the_content')) {
    function the_content(...$args) {
        return null;
    }
}

if (!function_exists('the_permalink')) {
    function the_permalink(...$args) {
        return null;
    }
}

if (!function_exists('the_post')) {
    function the_post(...$args) {
        return null;
    }
}

if (!function_exists('the_post_thumbnail')) {
    function the_post_thumbnail(...$args) {
        return null;
    }
}

if (!function_exists('the_title')) {
    function the_title(...$args) {
        return null;
    }
}

if (!function_exists('update_post_meta')) {
    function update_post_meta(...$args) {
        return null;
    }
}

if (!function_exists('wp_add_inline_script')) {
    function wp_add_inline_script(...$args) {
        return null;
    }
}

if (!function_exists('wp_check_filetype')) {
    function wp_check_filetype(...$args) {
        return ['ext' => '', 'type' => ''];
    }
}

if (!function_exists('wp_count_posts')) {
    function wp_count_posts(...$args) {
        return null;
    }
}

if (!function_exists('wp_create_nonce')) {
    function wp_create_nonce(...$args) {
        return null;
    }
}

if (!function_exists('wp_delete_post')) {
    function wp_delete_post(...$args) {
        return 0;
    }
}

if (!function_exists('wp_die')) {
    function wp_die(...$args) {
        return null;
    }
}

if (!function_exists('wp_editor')) {
    function wp_editor(...$args) {
        return null;
    }
}

if (!function_exists('wp_enqueue_media')) {
    function wp_enqueue_media(...$args) {
        return null;
    }
}

if (!function_exists('wp_enqueue_script')) {
    function wp_enqueue_script(...$args) {
        return null;
    }
}

if (!function_exists('wp_enqueue_style')) {
    function wp_enqueue_style(...$args) {
        return null;
    }
}

if (!function_exists('wp_footer')) {
    function wp_footer(...$args) {
        return null;
    }
}

if (!function_exists('wp_generate_attachment_metadata')) {
    function wp_generate_attachment_metadata(...$args) {
        return null;
    }
}

if (!function_exists('wp_generate_password')) {
    function wp_generate_password(...$args) {
        return '';
    }
}

if (!function_exists('wp_get_attachment_image_src')) {
    function wp_get_attachment_image_src(...$args) {
        return [];
    }
}

if (!function_exists('wp_get_attachment_image_url')) {
    function wp_get_attachment_image_url(...$args) {
        return '';
    }
}

if (!function_exists('wp_get_attachment_url')) {
    function wp_get_attachment_url(...$args) {
        return '';
    }
}

if (!function_exists('wp_get_object_terms')) {
    function wp_get_object_terms(...$args) {
        return [new WP_Term()];
    }
}

if (!function_exists('wp_get_post_terms')) {
    function wp_get_post_terms(...$args) {
        return [new WP_Term()];
    }
}

if (!function_exists('wp_head')) {
    function wp_head(...$args) {
        return null;
    }
}

if (!function_exists('wp_insert_attachment')) {
    function wp_insert_attachment(...$args) {
        return 0;
    }
}

if (!function_exists('wp_insert_post')) {
    function wp_insert_post(...$args) {
        return 0;
    }
}

if (!function_exists('wp_insert_term')) {
    function wp_insert_term(...$args) {
        return 0;
    }
}

if (!function_exists('wp_json_encode')) {
    function wp_json_encode(...$args) {
        return '';
    }
}

if (!function_exists('wp_kses_post')) {
    function wp_kses_post(...$args) {
        return null;
    }
}

if (!function_exists('wp_list_pluck')) {
    function wp_list_pluck(...$args) {
        return [];
    }
}

if (!function_exists('wp_localize_script')) {
    function wp_localize_script(...$args) {
        return null;
    }
}

if (!function_exists('wp_mail')) {
    function wp_mail(...$args) {
        return false;
    }
}

if (!function_exists('wp_nonce_field')) {
    function wp_nonce_field(...$args) {
        return null;
    }
}

if (!function_exists('wp_nonce_url')) {
    function wp_nonce_url(...$args) {
        return '';
    }
}

if (!function_exists('wp_parse_args')) {
    function wp_parse_args(...$args) {
        return [];
    }
}

if (!function_exists('wp_redirect')) {
    function wp_redirect(...$args) {
        return null;
    }
}

if (!function_exists('wp_reset_postdata')) {
    function wp_reset_postdata(...$args) {
        return null;
    }
}

if (!function_exists('wp_safe_redirect')) {
    function wp_safe_redirect(...$args) {
        return null;
    }
}

if (!function_exists('wp_send_json_error')) {
    function wp_send_json_error(...$args) {
        return null;
    }
}

if (!function_exists('wp_send_json_success')) {
    function wp_send_json_success(...$args) {
        return null;
    }
}

if (!function_exists('wp_set_object_terms')) {
    function wp_set_object_terms(...$args) {
        return null;
    }
}

if (!function_exists('wp_set_post_terms')) {
    function wp_set_post_terms(...$args) {
        return null;
    }
}

if (!function_exists('wp_strip_all_tags')) {
    function wp_strip_all_tags(...$args) {
        return '';
    }
}

if (!function_exists('wp_trim_words')) {
    function wp_trim_words(...$args) {
        return '';
    }
}

if (!function_exists('wp_update_attachment_metadata')) {
    function wp_update_attachment_metadata(...$args) {
        return null;
    }
}

if (!function_exists('wp_update_post')) {
    function wp_update_post(...$args) {
        return 0;
    }
}

if (!function_exists('wp_upload_bits')) {
    function wp_upload_bits(...$args) {
        return ['file' => '', 'url' => '', 'error' => false];
    }
}

if (!function_exists('wp_verify_nonce')) {
    function wp_verify_nonce(...$args) {
        return false;
    }
}

if (!function_exists('wpcf7_enqueue_scripts')) {
    function wpcf7_enqueue_scripts(...$args) {
        return null;
    }
}

if (!function_exists('wpcf7_enqueue_styles')) {
    function wpcf7_enqueue_styles(...$args) {
        return null;
    }
}

