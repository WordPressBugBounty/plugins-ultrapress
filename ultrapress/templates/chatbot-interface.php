<?php
/**
 * The main template for the chatbot interface.
 * This file is loaded in the footer of the site and contains the HTML structure
 * for the chatbot window and toggle button.
 *
 * @package UltraPress
 * @since 5.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Fetch all saved settings from the database at once.
$options = get_option('ultrapress_settings', []);

// --- Assign all customizable variables with safe default fallbacks ---

// Position & Spacing
$position = $options['chatbot_position'] ?? 'right';

// Theme
$theme = $options['theme_preset'] ?? 'light';

// Icon
$default_icon_url = ULTRAPRESS_PLUGIN_URL . 'assets/images/default-icon.svg';
$icon_url = !empty($options['chatbot_icon']) ? esc_url($options['chatbot_icon']) : $default_icon_url;

// Interface Text
$header_title = !empty($options['chatbot_header_title']) ? $options['chatbot_header_title'] : esc_html__('Chatbot', 'ultrapress');
$input_placeholder = !empty($options['chatbot_input_placeholder']) ? $options['chatbot_input_placeholder'] : esc_attr__('Type your message...', 'ultrapress');
$welcome_message = !empty($options['welcome_message']) ? $options['welcome_message'] : esc_html__('Hello! How can I assist you today?', 'ultrapress');

// RTL Support
$rtl_class = is_rtl() ? 'ultrapress-rtl' : '';

?>

<!-- Chatbot Toggle Button -->
<div class="ultrapress-toggle ultrapress-<?php echo esc_attr($position); ?> ultrapress-theme-<?php echo esc_attr($theme); ?> <?php echo $rtl_class; ?>">
    <img src="<?php echo $icon_url; ?>" alt="<?php esc_attr_e('Chat Toggle', 'ultrapress'); ?>" class="ultrapress-icon">
</div>

<!-- Main Chatbot Window -->
<div class="ultrapress-container ultrapress-<?php echo esc_attr($position); ?> ultrapress-theme-<?php echo esc_attr($theme); ?> ultrapress-hidden <?php echo $rtl_class; ?>">
    
    <!-- Header -->
    <div class="ultrapress-header">
        <h4><?php echo esc_html($header_title); ?></h4>
        <span class="ultrapress-minimize" role="button" aria-label="<?php esc_attr_e('Minimize Chat', 'ultrapress'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/>
            </svg>
        </span>
    </div>
    
    <!-- Messages Area -->
    <div class="ultrapress-messages">
        <div class="ultrapress-message ultrapress-bot-message">
            <?php echo wp_kses_post($welcome_message); ?>
        </div>
    </div>
    
    <!-- Input Form -->
    <div class="ultrapress-input-container">
        <form class="ultrapress-input-form">
            <textarea class="ultrapress-input" placeholder="<?php echo esc_attr($input_placeholder); ?>" aria-label="<?php esc_attr_e('Chat Input', 'ultrapress'); ?>" rows="1"></textarea>
            <button type="submit" class="ultrapress-submit" aria-label="<?php esc_attr_e('Send Message', 'ultrapress'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" role="img" aria-hidden="true" focusable="false">
                    <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" />
                </svg>
            </button>
        </form>
    </div>

</div>