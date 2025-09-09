<?php
/**
 * Plugin Name:       ultrapress
 * Plugin URI:        https://wordpress.org/plugins/ultrapress/
 * Description:       A simple and lightweight SEO plugin for WordPress to manage titles and meta descriptions.
 * Version:           2.0.1
 * Author:            meedawi
 * Author URI:        https://profiles.wordpress.org/meedawi/
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl.html
 * Text Domain:       ultrapress
 * Requires at least: 4.7
 * Tested up to:      6.8
 * Requires PHP:      5.6.0
 * Donate link:       https://paypal.me/ultrapress
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define constants
 */
define( 'ULTRAPRESS_VERSION', '2.0.0' );
define( 'ULTRAPRESS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * The core plugin classes.
 */
require ULTRAPRESS_PLUGIN_DIR . 'admin/class-ultrapress-admin.php';
require ULTRAPRESS_PLUGIN_DIR . 'public/class-ultrapress-public.php';

/**
 * Begins execution of the plugin.
 */
function run_ultrapress() {
    new Ultrapress_Admin();
    new Ultrapress_Public();
}

run_ultrapress();