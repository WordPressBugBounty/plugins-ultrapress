<?php
/**
 * Handles the creation of the admin menu and all settings pages for UltraPress.
 *
 * @package UltraPress
 * @since 5.2.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class UltraPress_Settings {

    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    public function enqueue_admin_assets($hook) {
        // Only load assets on our plugin's pages
        if (strpos($hook, 'ultrapress') === false) {
            return;
        }
        
        // This script handles the API/Model toggle and Media Uploader
        wp_enqueue_script('ultrapress-admin-settings', ULTRAPRESS_PLUGIN_URL . 'assets/js/admin-settings.js', array('jquery', 'wp-color-picker'), ULTRAPRESS_VERSION, true);
        wp_enqueue_style('wp-color-picker');
        
        // Localize script with data for the media uploader, but only on the chatbot page
        if ($hook === 'ultrapress_page_ultrapress-chatbot' || $hook === 'ultrapress_toplevel_page_ultrapress-chatbot') { // Hook can vary
            wp_enqueue_media();
            wp_localize_script('ultrapress-admin-settings', 'ultrapressAdminData', [
                'uploaderTitle'  => esc_html__('Select or Upload a ChatBot Icon', 'ultrapress'),
                'uploaderButton' => esc_html__('Use this icon', 'ultrapress'),
                'defaultIconUrl' => ULTRAPRESS_PLUGIN_URL . 'assets/images/default-icon.svg',
            ]);
        }
    }

    public function add_admin_menu() {
        add_menu_page('UltraPress', 'UltraPress', 'manage_options', 'ultrapress-ai-brain', array($this, 'render_ai_brain_page'), 'dashicons-superhero-alt', 25);
        add_submenu_page('ultrapress-ai-brain', esc_html__('AI Brain Settings', 'ultrapress'), esc_html__('AI Brain', 'ultrapress'), 'manage_options', 'ultrapress-ai-brain', array($this, 'render_ai_brain_page'));
        add_submenu_page('ultrapress-ai-brain', esc_html__('Chatbot Settings', 'ultrapress'), esc_html__('Chatbot', 'ultrapress'), 'manage_options', 'ultrapress-chatbot', array($this, 'render_chatbot_page'));
        add_submenu_page('ultrapress-ai-brain', esc_html__('SEO Settings', 'ultrapress'), esc_html__('SEO', 'ultrapress'), 'manage_options', 'ultrapress-seo', array($this, 'render_seo_page'));
    }

    private function render_page_wrapper($page_slug, $page_title) {
        echo '<div class="wrap"><h1>' . esc_html($page_title) . '</h1><form method="post" action="options.php">';
        settings_fields('ultrapress_settings');
        do_settings_sections($page_slug);
        submit_button(esc_html__('Save Settings', 'ultrapress'));
        echo '</form></div>';
    }
    
    public function render_ai_brain_page() { $this->render_page_wrapper('ultrapress-ai-brain', esc_html__('AI Brain Settings', 'ultrapress')); }
    public function render_chatbot_page() { $this->render_page_wrapper('ultrapress-chatbot', esc_html__('Chatbot Settings', 'ultrapress')); }
    public function render_seo_page() { $this->render_page_wrapper('ultrapress-seo', esc_html__('SEO Settings', 'ultrapress')); }

    public function register_settings() {
        register_setting('ultrapress_settings', 'ultrapress_settings', array($this, 'sanitize_settings'));

        // --- AI BRAIN PAGE ---
        add_settings_section('ultrapress_api_section', esc_html__('API Configuration', 'ultrapress'), null, 'ultrapress-ai-brain');
        add_settings_field('api_provider', esc_html__('AI Provider', 'ultrapress'), array($this, 'field_api_provider'), 'ultrapress-ai-brain', 'ultrapress_api_section');
        $providers = $this->get_available_models();
        foreach ($providers as $provider => $models) {
            add_settings_field("{$provider}_api_key", ucfirst($provider) . ' API Key', array($this, 'field_api_key'), 'ultrapress-ai-brain', 'ultrapress_api_section', ['provider' => $provider]);
            add_settings_field("{$provider}_model", ucfirst($provider) . ' Model', array($this, 'field_model_select'), 'ultrapress-ai-brain', 'ultrapress_api_section', ['provider' => $provider, 'models' => $models]);
        }
        add_settings_field('max_tokens', esc_html__('Max Tokens', 'ultrapress'), array($this, 'field_number'), 'ultrapress-ai-brain', 'ultrapress_api_section', ['key' => 'max_tokens', 'default' => 400]);

        // --- CHATBOT PAGE ---
        add_settings_section('ultrapress_chatbot_general_section', esc_html__('General Settings', 'ultrapress'), null, 'ultrapress-chatbot');
        add_settings_field('enable_chatbot_module', esc_html__('Enable Chatbot', 'ultrapress'), array($this, 'field_checkbox'), 'ultrapress-chatbot', 'ultrapress_chatbot_general_section', ['key' => 'enable_chatbot_module', 'label' => 'Activate the AI chatbot on the front-end of your website.']);
        
        add_settings_section('ultrapress_chatbot_behavior_section', esc_html__('Behavior & Content', 'ultrapress'), null, 'ultrapress-chatbot');
        add_settings_field('chatbot_system_prompt', esc_html__('System Prompt / Instructions', 'ultrapress'), array($this, 'field_textarea'), 'ultrapress-chatbot', 'ultrapress_chatbot_behavior_section', ['key' => 'chatbot_system_prompt', 'rows' => 10, 'description' => 'This is the knowledge base for the AI. Provide all company information, product details, and rules here.']);
        add_settings_field('chatbot_contact_info', esc_html__('Contact Info Fallback', 'ultrapress'), array($this, 'field_text'), 'ultrapress-chatbot', 'ultrapress_chatbot_behavior_section', ['key' => 'chatbot_contact_info', 'description' => 'If the AI cannot answer, it will provide this contact info (e.g., an email or phone number).']);
        add_settings_field('chatbot_persona', esc_html__('Bot Persona', 'ultrapress'), array($this, 'field_persona_select'), 'ultrapress-chatbot', 'ultrapress_chatbot_behavior_section');
        add_settings_field('chatbot_header_title', esc_html__('Header Title', 'ultrapress'), array($this, 'field_text'), 'ultrapress-chatbot', 'ultrapress_chatbot_behavior_section', ['key' => 'chatbot_header_title', 'default' => 'Chatbot']);
        add_settings_field('chatbot_input_placeholder', esc_html__('Input Placeholder', 'ultrapress'), array($this, 'field_text'), 'ultrapress-chatbot', 'ultrapress_chatbot_behavior_section', ['key' => 'chatbot_input_placeholder', 'default' => 'Type your message...']);
        add_settings_field('welcome_message', esc_html__('Welcome Message', 'ultrapress'), array($this, 'field_textarea'), 'ultrapress-chatbot', 'ultrapress_chatbot_behavior_section', ['key' => 'welcome_message', 'default' => 'Hello! How can I assist you today?']);
        
        add_settings_section('ultrapress_chatbot_customization_section', esc_html__('Visual Customization', 'ultrapress'), null, 'ultrapress-chatbot');
        add_settings_field('chatbot_icon', esc_html__('ChatBot Icon', 'ultrapress'), array($this, 'field_icon_uploader'), 'ultrapress-chatbot', 'ultrapress_chatbot_customization_section');
        add_settings_field('chatbot_position', esc_html__('Chatbot Position', 'ultrapress'), array($this, 'field_select'), 'ultrapress-chatbot', 'ultrapress_chatbot_customization_section', ['key' => 'chatbot_position', 'options' => ['right' => 'Right', 'left' => 'Left']]);
        add_settings_field('chatbot_spacing_side', esc_html__('Side Spacing (px)', 'ultrapress'), array($this, 'field_number'), 'ultrapress-chatbot', 'ultrapress_chatbot_customization_section', ['key' => 'chatbot_spacing_side', 'default' => 20]);
        add_settings_field('chatbot_spacing_bottom', esc_html__('Bottom Spacing (px)', 'ultrapress'), array($this, 'field_number'), 'ultrapress-chatbot', 'ultrapress_chatbot_customization_section', ['key' => 'chatbot_spacing_bottom', 'default' => 20]);
        add_settings_field('theme_preset', esc_html__('Theme Preset', 'ultrapress'), array($this, 'field_theme_preset'), 'ultrapress-chatbot', 'ultrapress_chatbot_customization_section');
        add_settings_field('primary_color', esc_html__('Primary Color', 'ultrapress'), array($this, 'field_color_picker'), 'ultrapress-chatbot', 'ultrapress_chatbot_customization_section', ['key' => 'primary_color', 'default' => '#007bff']);
        add_settings_field('secondary_color', esc_html__('Secondary Color', 'ultrapress'), array($this, 'field_color_picker'), 'ultrapress-chatbot', 'ultrapress_chatbot_customization_section', ['key' => 'secondary_color', 'default' => '#f8f9fa']);
        add_settings_field('text_color', esc_html__('Text Color', 'ultrapress'), array($this, 'field_color_picker'), 'ultrapress-chatbot', 'ultrapress_chatbot_customization_section', ['key' => 'text_color', 'default' => '#212529']);
        add_settings_field('font_family', esc_html__('Font Family', 'ultrapress'), array($this, 'field_font_select'), 'ultrapress-chatbot', 'ultrapress_chatbot_customization_section');
        add_settings_field('font_size', esc_html__('Font Size (px)', 'ultrapress'), array($this, 'field_number'), 'ultrapress-chatbot', 'ultrapress_chatbot_customization_section', ['key' => 'font_size', 'default' => 14]);
        
        // --- SEO PAGE ---
        add_settings_section('ultrapress_seo_general_section', esc_html__('General SEO Settings', 'ultrapress'), array($this, 'render_seo_general_description'), 'ultrapress-seo');
        add_settings_field('enable_seo_module', esc_html__('Enable SEO Module', 'ultrapress'), array($this, 'field_checkbox'), 'ultrapress-seo', 'ultrapress_seo_general_section', ['key' => 'enable_seo_module', 'label' => 'Activate the AI-powered SEO meta box in the post editor.']);
    }

    public function render_seo_general_description() { echo '<p>' . esc_html__('When enabled, a meta box will be added to your post editor. If you leave the SEO title and description fields empty, the AI will automatically generate them for you upon publishing.', 'ultrapress') . '</p>'; }

    public function field_api_provider() { $options = get_option('ultrapress_settings', []); $value = $options['api_provider'] ?? 'openai'; $providers = ['openai' => 'OpenAI', 'deepseek' => 'DeepSeek', 'gemini' => 'Google Gemini']; echo "<select id='ultrapress_api_provider_select' name='ultrapress_settings[api_provider]'>"; foreach ($providers as $key => $label) { printf('<option value="%s" %s>%s</option>', esc_attr($key), selected($value, $key, false), esc_html($label)); } echo "</select>"; }
    public function field_api_key($args) { $provider = $args['provider']; $options = get_option('ultrapress_settings', []); $key = "{$provider}_api_key"; $value = $options[$key] ?? ''; echo "<div class='provider-setting provider-{$provider}'><input type='password' name='ultrapress_settings[{$key}]' value='" . esc_attr($value) . "' class='regular-text' placeholder='••••••••••••••••••••'></div>"; }
    public function field_model_select($args) { $provider = $args['provider']; $models = $args['models']; $options = get_option('ultrapress_settings', []); $key = "{$provider}_model"; $value = $options[$key] ?? ''; echo "<div class='provider-setting provider-{$provider}'><select name='ultrapress_settings[{$key}]' style='min-width: 300px;'>"; foreach ($models as $model_key => $model_label) { printf('<option value="%s" %s>%s</option>', esc_attr($model_key), selected($value, $model_key, false), esc_html($model_label)); } echo "</select></div>"; }
    public function field_number($args) { $options = get_option('ultrapress_settings', []); $key = $args['key']; $value = $options[$key] ?? ($args['default'] ?? ''); printf('<input type="number" name="ultrapress_settings[%s]" value="%s" class="small-text">', esc_attr($key), esc_attr($value)); }
    public function field_checkbox($args) { $options = get_option('ultrapress_settings', []); $key = $args['key']; $value = $options[$key] ?? 0; printf('<label><input type="checkbox" name="ultrapress_settings[%s]" value="1" %s> %s</label>', esc_attr($key), checked(1, $value, false), esc_html($args['label'] ?? '')); }
    public function field_text($args) { $options = get_option('ultrapress_settings', []); $key = $args['key']; $value = $options[$key] ?? ($args['default'] ?? ''); printf('<input type="text" name="ultrapress_settings[%s]" value="%s" class="regular-text">', esc_attr($key), esc_attr($value)); }
    public function field_textarea($args) { $options = get_option('ultrapress_settings', []); $key = $args['key']; $value = $options[$key] ?? ($args['default'] ?? ''); $rows = $args['rows'] ?? 5; $desc = $args['description'] ?? ''; printf('<textarea name="ultrapress_settings[%s]" rows="%d" class="large-text">%s</textarea>', esc_attr($key), esc_attr($rows), esc_textarea($value)); if ($desc) { echo '<p class="description">' . esc_html($desc) . '</p>'; } }
    public function field_select($args) { $options = get_option('ultrapress_settings', []); $key = $args['key']; $value = $options[$key] ?? ($args['default'] ?? ''); echo "<select name='ultrapress_settings[{$key}]'>"; foreach ($args['options'] as $opt_key => $opt_val) { printf('<option value="%s" %s>%s</option>', esc_attr($opt_key), selected($value, $opt_key, false), esc_html($opt_val)); } echo "</select>"; }
    public function field_persona_select() { $options = get_option('ultrapress_settings', []); $value = $options['chatbot_persona'] ?? 'professional'; $personas = $this->get_available_personas(); echo "<select name='ultrapress_settings[chatbot_persona]'>"; foreach ($personas as $key => $label) { printf('<option value="%s" %s>%s</option>', esc_attr($key), selected($value, $key, false), esc_html($label)); } echo "</select>"; }
    public function field_icon_uploader() { $options = get_option('ultrapress_settings', []); $icon_url = $options['chatbot_icon'] ?? ''; $preview_url = !empty($icon_url) ? esc_url($icon_url) : ULTRAPRESS_PLUGIN_URL . 'assets/images/default-icon.svg'; echo '<div class="ultrapress-icon-preview-wrapper" style="margin-bottom: 10px;"><img src="' . $preview_url . '" alt="Icon Preview" style="max-width: 64px; height: auto; border: 1px solid #ddd; padding: 5px;"></div>'; echo '<input type="hidden" id="ultrapress-chatbot-icon-url" name="ultrapress_settings[chatbot_icon]" value="' . esc_attr($icon_url) . '">'; echo '<button type="button" class="button" id="ultrapress-upload-icon-btn">' . esc_html__('Upload Icon', 'ultrapress') . '</button> '; echo '<button type="button" class="button button-secondary" id="ultrapress-reset-icon-btn">' . esc_html__('Reset to Default', 'ultrapress') . '</button>'; }
    public function field_theme_preset() { $options = get_option('ultrapress_settings', []); $value = $options['theme_preset'] ?? 'light'; $themes = ['light' => __('Light', 'ultrapress'), 'dark' => __('Dark', 'ultrapress'), 'professional' => __('Professional', 'ultrapress'), 'friendly' => __('Friendly', 'ultrapress'), 'custom' => __('Custom Colors', 'ultrapress')]; echo "<select name='ultrapress_settings[theme_preset]'>"; foreach ($themes as $key => $label) { printf('<option value="%s" %s>%s</option>', esc_attr($key), selected($value, $key, false), esc_html($label)); } echo "</select><p class='description'>" . esc_html__('Select "Custom Colors" to use the color pickers below.', 'ultrapress') . "</p>"; }
    public function field_color_picker($args) { $options = get_option('ultrapress_settings', []); $key = $args['key']; $value = $options[$key] ?? $args['default']; printf('<input type="text" name="ultrapress_settings[%s]" value="%s" class="ultrapress-color-picker">', esc_attr($key), esc_attr($value)); }
    public function field_font_select() { $options = get_option('ultrapress_settings', []); $value = $options['font_family'] ?? 'system-ui, sans-serif'; $fonts = ['system-ui, sans-serif' => 'System Default', 'Arial, sans-serif' => 'Arial', 'Verdana, sans-serif' => 'Verdana', 'Georgia, serif' => 'Georgia', 'Times New Roman, serif' => 'Times New Roman']; echo "<select name='ultrapress_settings[font_family]'>"; foreach ($fonts as $font_val => $font_label) { printf('<option value="%s" %s>%s</option>', esc_attr($font_val), selected($value, $font_val, false), esc_html($font_label)); } echo "</select>"; }
    
    // In class-ultrapress-settings.php

public function sanitize_settings($input) {
    // Start with a fresh array or load existing options to merge.
    // Loading existing options is safer to not lose settings from other pages.
    $output = get_option('ultrapress_settings', []);

    // Loop through all submitted input and sanitize it based on its key.
    foreach ($input as $key => $value) {
        switch ($key) {
            // --- API Keys & Text Fields ---
            case 'openai_api_key':
            case 'deepseek_api_key':
            case 'gemini_api_key':
            case 'chatbot_contact_info':
            case 'chatbot_header_title':
            case 'chatbot_input_placeholder':
            case 'font_family': // Font family is a string
                $output[$key] = sanitize_text_field(trim($value));
                break;
            
            // --- Models (No strict validation, just sanitize) ---
            case 'openai_model':
            case 'deepseek_model':
            case 'gemini_model':
                $output[$key] = sanitize_text_field(trim($value));
                break;

            // --- Textareas ---
            case 'chatbot_system_prompt':
                $output[$key] = sanitize_textarea_field(trim($value));
                break;
            case 'welcome_message':
                $output[$key] = wp_kses_post($value);
                break;

            // --- Selects (Key-based validation) ---
            case 'api_provider':
                if (array_key_exists($value, $this->get_available_models())) {
                    $output[$key] = sanitize_key($value);
                }
                break;
            case 'chatbot_persona':
                if (array_key_exists($value, $this->get_available_personas())) {
                    $output[$key] = sanitize_key($value);
                }
                break;
            case 'chatbot_position':
                if (in_array($value, ['left', 'right'])) {
                    $output[$key] = sanitize_key($value);
                }
                break;
            case 'theme_preset':
                if (in_array($value, ['light', 'dark', 'professional', 'friendly', 'custom'])) {
                    $output[$key] = sanitize_key($value);
                }
                break;
            
            // --- Numeric Fields ---
            case 'max_tokens':
            case 'chatbot_spacing_bottom':
            case 'chatbot_spacing_side':
            case 'window_width':
            case 'window_height':
            case 'font_size':
                $output[$key] = absint($value);
                break;

            // --- Color Pickers ---
            case 'primary_color':
            case 'secondary_color':
            case 'text_color':
                $output[$key] = sanitize_hex_color($value);
                break;

            // --- URL Fields ---
            case 'chatbot_icon':
                $output[$key] = esc_url_raw($value);
                break;

            // --- Checkboxes ---
            case 'enable_chatbot_module':
            case 'enable_seo_module':
                $output[$key] = ($value == '1') ? 1 : 0;
                break;
            
            // Default for any other fields that might be added
            default:
                $output[$key] = sanitize_text_field($value);
                break;
        }
    }

    return $output;
}

    private function get_available_models() { return [ 'openai' => [ 'gpt-4o' => 'GPT-4o (Latest & Best All-Round)', 'gpt-4-turbo' => 'GPT-4 Turbo (Large Context)', 'gpt-3.5-turbo' => 'GPT-3.5 Turbo (Fast & Cost-Effective)', 'gpt-5' => 'GPT-5 (Advanced Reasoning & Coding)', 'gpt-5-mini' => 'GPT-5 Mini (Fast & Capable)', 'gpt-5-nano' => 'GPT-5 Nano (Summaries & Classification)', 'gpt-4o-mini' => 'GPT-4o Mini (Cost-Effective Omni)', 'gpt-4.1' => 'GPT-4.1 (Complex Tasks)', 'gpt-4.1-mini' => 'GPT-4.1 Mini (Balanced Performance)', 'gpt-4.1-nano' => 'GPT-4.1 Nano (Speed Optimized)', 'o3' => 'DeepThought (o3) (Advanced Logic)', 'o3-pro' => 'DeepThought Pro (o3-pro) (Research & Logic)'], 'gemini' => [ 'gemini-2.5-pro' => 'Gemini 2.5 Pro (Advanced Reasoning)', 'gemini-2.5-flash' => 'Gemini 2.5 Flash (Fast & Cost-Effective)', 'gemini-2.5-flash-lite' => 'Gemini 2.5 Flash-Lite (High Throughput & Lowest Cost)', 'gemini-1.5-pro-latest' => 'Gemini 1.5 Pro (Legacy - Large Context)'], 'deepseek' => [ 'deepseek-chat' => 'DeepSeek Chat (General Purpose)', 'deepseek-coder' => 'DeepSeek Coder (Optimized for Code)']]; }
    private function get_available_personas() { return [ 'professional' => __('Professional & Formal', 'ultrapress'), 'friendly' => __('Friendly & Conversational', 'ultrapress'), 'enthusiastic_marketer' => __('Enthusiastic & Persuasive (Marketing)', 'ultrapress'), 'technical_support' => __('Technical & Precise (Support)', 'ultrapress'), 'playful' => __('Playful & Creative', 'ultrapress'), 'concise' => __('Concise & Direct (To-the-point)', 'ultrapress')]; }
}


