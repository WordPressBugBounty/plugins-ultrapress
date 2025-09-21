<?php
/**
 * The main plugin class.
 *
 * @package UltraPress
 * @since 5.1.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

final class UltraPress_Main {

    private static $instance = null;
    private $options = [];

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->options = get_option('ultrapress_settings', []);
        $this->includes();
        $this->init_hooks();
    }

    private function includes() {
        require_once ULTRAPRESS_PLUGIN_DIR . 'includes/class-ultrapress-settings.php';
        require_once ULTRAPRESS_PLUGIN_DIR . 'includes/class-ultrapress-api-handler.php';
        require_once ULTRAPRESS_PLUGIN_DIR . 'includes/class-ultrapress-seo.php';
        require_once ULTRAPRESS_PLUGIN_DIR . 'includes/installation.php';
    }

    private function init_hooks() {
        add_action('init', array($this, 'load_textdomain'));
        UltraPress_Settings::instance();
        UltraPress_SEO::instance();
        
        add_action('wp_enqueue_scripts', array($this, 'enqueue_front_end_assets'));
        add_action('wp_footer', array($this, 'add_chatbot_to_footer'));
        
        add_action('wp_ajax_ultrapress_send_message', array('UltraPress_API_Handler', 'handle_chatbot_request'));
        add_action('wp_ajax_nopriv_ultrapress_send_message', array('UltraPress_API_Handler', 'handle_chatbot_request'));
        
        add_filter('plugin_action_links_' . plugin_basename(ULTRAPRESS_PLUGIN_FILE), array($this, 'add_settings_link'));
        register_activation_hook(ULTRAPRESS_PLUGIN_FILE, 'ultrapress_create_table');
    }

    public function load_textdomain() { /* Unchanged */ }
    public function add_settings_link($links) { /* Unchanged */ }
    
    public function enqueue_front_end_assets() {
        if (empty($this->options['enable_chatbot_module'])) return;

        wp_enqueue_style('ultrapress-style', ULTRAPRESS_PLUGIN_URL . 'assets/css/style.css', array(), ULTRAPRESS_VERSION);
        wp_enqueue_style('ultrapress-themes', ULTRAPRESS_PLUGIN_URL . 'assets/css/themes.css', array(), ULTRAPRESS_VERSION);
    
        wp_enqueue_script('ultrapress-marked', ULTRAPRESS_PLUGIN_URL . 'assets/js/lib/marked.min.js', array(), '13.0.0', true);
        wp_enqueue_script('ultrapress-chatbot', ULTRAPRESS_PLUGIN_URL . 'assets/js/chatbot.js', array('jquery', 'ultrapress-marked'), ULTRAPRESS_VERSION, true);
    
        wp_localize_script('ultrapress-chatbot', 'ultrapressData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ultrapress-nonce'),
            'welcomeMessage' => !empty($this->options['welcome_message']) ? wp_kses_post($this->options['welcome_message']) : esc_html__('Hello! How can I assist you today?', 'ultrapress'),
            'errorMessages' => [ 'general' => esc_html__('Sorry, an error occurred.', 'ultrapress'), 'connection' => esc_html__('Sorry, a connection error occurred.', 'ultrapress') ]
        ]);

        $this->add_inline_styles($this->options);
    }
    
    /**
     * Injects dynamic styles into the page header.
     * This is the corrected version that includes spacing variables.
     */
    private function add_inline_styles($settings) {
        // Get all dynamic values with safe defaults
        $bottom_spacing = $settings['chatbot_spacing_bottom'] ?? 20;
        $side_spacing = $settings['chatbot_spacing_side'] ?? 20;
        $font_family = $settings['font_family'] ?? 'system-ui, sans-serif';
        $font_size = $settings['font_size'] ?? 14;
        $window_width = $settings['window_width'] ?? 350;
        $window_height = $settings['window_height'] ?? 500;
        $primary_color = $settings['primary_color'] ?? '#007bff';
        $secondary_color = $settings['secondary_color'] ?? '#f8f9fa';
        $text_color = $settings['text_color'] ?? '#212529';

        $css = "
        :root {
            --ultrapress-font-family: '" . esc_attr($font_family) . "';
            --ultrapress-font-size: " . absint($font_size) . "px;
            --ultrapress-window-width: " . absint($window_width) . "px;
            --ultrapress-window-height: " . absint($window_height) . "px;
            --ultrapress-bottom-spacing: " . absint($bottom_spacing) . "px;
            --ultrapress-side-spacing: " . absint($side_spacing) . "px;
        }
        .ultrapress-theme-custom {
            --chatbot-primary: " . esc_attr($primary_color) . ";
            --chatbot-secondary: " . esc_attr($secondary_color) . ";
            --chatbot-text: " . esc_attr($text_color) . ";
        }
        ";
        wp_add_inline_style('ultrapress-style', $css);
    }

    public function add_chatbot_to_footer() {
        if (empty($this->options['enable_chatbot_module'])) return;
        include ULTRAPRESS_PLUGIN_DIR . 'templates/chatbot-interface.php';
    }
}