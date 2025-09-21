<?php
/**
 * Handles all SEO-related functionality, including the meta box in the post editor
 * and the AI generation for meta titles and descriptions.
 *
 * @package UltraPress
 * @since 5.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class UltraPress_SEO {

    /**
     * The single instance of the class.
     * @var UltraPress_SEO
     */
    private static $instance = null;

    /**
     * Main UltraPress_SEO Instance.
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {
        $options = get_option('ultrapress_settings', []);
        
        // Only load the SEO features if the module is enabled in settings.
        if (!empty($options['enable_seo_module'])) {
            add_action('add_meta_boxes', array($this, 'add_meta_box'));
            add_action('save_post', array($this, 'save_meta_data'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_editor_assets'));
            
            // Register AJAX action for AI generation
            add_action('wp_ajax_ultrapress_generate_seo_meta', array('UltraPress_API_Handler', 'handle_seo_generation_request'));
        }
    }

    /**
     * Enqueue scripts and styles specifically for the post editor screen.
     */
    public function enqueue_editor_assets($hook) {
        // Only load on post and page editing screens
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }

        wp_enqueue_script(
            'ultrapress-meta-box',
            ULTRAPRESS_PLUGIN_URL . 'assets/js/admin-meta-box.js',
            array('jquery'),
            ULTRAPRESS_VERSION,
            true
        );

        // Pass data to the script
        wp_localize_script('ultrapress-meta-box', 'ultrapressSeoData', array(
            'nonce' => wp_create_nonce('ultrapress-seo-nonce'),
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'generatingText' => esc_html__('Generating with AI...', 'ultrapress'),
        ));
    }

    /**
     * Adds the SEO meta box to posts and pages.
     */
    public function add_meta_box() {
        // Defines which post types will get the meta box
        $post_types = apply_filters('ultrapress_seo_post_types', ['post', 'page']);
        
        add_meta_box(
            'ultrapress_seo_meta_box',                   // Unique ID
            'UltraPress - AI SEO',                       // Box title
            array($this, 'render_meta_box_content'),     // Content callback
            $post_types,                                 // Post types
            'normal',                                    // Context
            'high'                                       // Priority
        );
    }

    /**
     * Renders the HTML content of the SEO meta box.
     */
    public function render_meta_box_content($post) {
        // This includes the actual form fields and logic for the meta box.
        include ULTRAPRESS_PLUGIN_DIR . 'templates/seo-meta-box.php';
    }

    /**
     * Saves the custom meta data when a post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
        public function save_meta_data($post_id) {
        // --- Security checks (nonce, autosave, permissions) remain the same ---
        if (!isset($_POST['ultrapress_seo_nonce']) || !wp_verify_nonce($_POST['ultrapress_seo_nonce'], 'ultrapress_save_seo_meta') || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) {
            return;
        }

        // Get submitted values
        $seo_title = sanitize_text_field($_POST['_ultrapress_seo_title'] ?? '');
        $meta_description = sanitize_textarea_field($_POST['_ultrapress_meta_description'] ?? '');
        $focus_keyword = sanitize_text_field($_POST['_ultrapress_focus_keyword'] ?? '');

        // --- NEW: AI Auto-generation Logic ---
        // Check if API is configured and if the fields are empty
        $settings = get_option('ultrapress_settings', []);
        $is_api_configured = !empty($settings[$settings['api_provider'] . '_api_key']);

        if ($is_api_configured && (empty($seo_title) || empty($meta_description))) {
            $post = get_post($post_id);
            $post_title = $post->post_title;
            $post_content = wp_strip_all_tags($post->post_content);
            $site_title = get_bloginfo('name');
            
            // We call the API directly from the backend
            $system_prompt = UltraPress_API_Handler::get_seo_system_prompt($post_title, $post_content, $focus_keyword, $site_title);
            $response = UltraPress_API_Handler::send_request($system_prompt, []);

            if (!is_wp_error($response)) {
                $decoded = json_decode($response, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($decoded['title'])) {
                    // If AI generation was successful, use its values
                    $seo_title = empty($seo_title) ? sanitize_text_field($decoded['title']) : $seo_title;
                    $meta_description = empty($meta_description) ? sanitize_textarea_field($decoded['description']) : $meta_description;
                }
            }
        }
        
        // --- Save the final values ---
        update_post_meta($post_id, '_ultrapress_seo_title', $seo_title);
        update_post_meta($post_id, '_ultrapress_meta_description', $meta_description);
        update_post_meta($post_id, '_ultrapress_focus_keyword', $focus_keyword);
    }

}