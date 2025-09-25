<?php
/**
 * Plugin Name:       UltraPress - AI Assistant, Chatbot & SEO
 * Plugin URI:        https://wordpress.org/plugins/ultrapress
 * Description:       The AI Brain for your WordPress site. Engage visitors with a smart chatbot and enhance your SEO with AI-powered tools.
 * Version:           3.0.1
 * Author:            meedawi
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ultrapress
 * Domain Path:       /languages
 * Requires at least: 5.8
 * Requires PHP:      7.4
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define core plugin constants for easy access.
 */
define('ULTRAPRESS_VERSION', '3.0.1');
define('ULTRAPRESS_PLUGIN_FILE', __FILE__);
define('ULTRAPRESS_PLUGIN_DIR', plugin_dir_path(ULTRAPRESS_PLUGIN_FILE));
define('ULTRAPRESS_PLUGIN_URL', plugin_dir_url(ULTRAPRESS_PLUGIN_FILE));

/**
 * The main plugin class does not exist, load it.
 * This is the engine of the plugin.
 */
if (!class_exists('UltraPress_Main')) {
    require_once ULTRAPRESS_PLUGIN_DIR . 'includes/class-ultrapress-main.php';
}

/**
 * Begins execution of the plugin.
 *
 * @since    3.0.0
 */
function ultrapress_run() {
    return UltraPress_Main::instance();
}

// Let's get this party started!
ultrapress_run();