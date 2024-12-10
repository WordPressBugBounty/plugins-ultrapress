<?php

/**
 *
 * @link              https://wordpress.org/plugins/ultrapress/
 * @since             1.0.0
 * @package           Ultrapress
 *
 * @wordpress-plugin
 * Plugin Name:       Ultrapress 
 * Plugin URI:        https://wordpress.org/plugins/ultrapress/
 * Description:       Build and connect features and plugins with visual scripting
 * Version:           0.0.22
 * Author:            meedawi
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ultrapress
 * Domain Path:       /languages
 *
 * Ultrapress is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Ultrapress is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.

 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'ULTRAPRESS_VERSION', '1.0.18' );
define( 'ULTRAPRESS_PREVIOUS_STABLE_VERSION', '1.0.0' );

define( 'ULTRAPRESS__FILE__', __FILE__ );
define( 'ULTRAPRESS_PLUGIN_BASE', plugin_basename( ULTRAPRESS__FILE__ ) );
define( 'ULTRAPRESS_PATH', plugin_dir_path( ULTRAPRESS__FILE__ ) );
define( 'ULTRAPRESS_URL', plugins_url( '/', ULTRAPRESS__FILE__ ) );

define( 'ULTRAPRESS_TEMPLATES_PATH', ULTRAPRESS_PATH . 'templates' );
define( 'ULTRAPRESS_MODULES_PATH', plugin_dir_path( ULTRAPRESS__FILE__ ) . '/modules' );
define( 'ULTRAPRESS_ASSETS_PATH', ULTRAPRESS_PATH . 'assets/' );
define( 'ULTRAPRESS_ASSETS_URL', ULTRAPRESS_URL . 'assets/' );

require_once ULTRAPRESS_PATH . 'includes/plugin.php';

/**
 * Load Ultrapress textdomain.
 *
 * Load gettext translate for Ultrapress text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function ultrapress_load_plugin_textdomain() {
	load_plugin_textdomain( 'ultrapress' , false,  dirname( plugin_basename(__FILE__) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'ultrapress_load_plugin_textdomain' );

/**
 * Enqueue scripts and styles with version control
 */
function ultrapress_enqueue_assets() {
    // Only load on plugin admin pages
    $screen = get_current_screen();
    if (!$screen || strpos($screen->id, 'ultrapress') === false) {
        return;
    }

    $version = ULTRAPRESS_VERSION;
    
    // Add timestamp to version for development
    if (defined('WP_DEBUG') && WP_DEBUG) {
        $version .= '.' . time();
    }

    // Get the correct path to plugin directory
    $plugin_url = plugin_dir_url(__FILE__);

    // Enqueue admin styles
    wp_enqueue_style(
        'ultrapress-admin',
        $plugin_url . 'css/ultrapress-admin.css',
        array(),
        $version
    );

    // Enqueue admin scripts
    wp_enqueue_script(
        'ultrapress-admin',
        $plugin_url . 'js/ultrapress-admin.js',
        array('jquery'),
        $version,
        true
    );

    // Add dynamic data to script
    wp_localize_script('ultrapress-admin', 'ultrapressData', array(
        'version' => $version,
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ultrapress_nonce'),
        'pluginUrl' => $plugin_url
    ));
}
add_action('admin_enqueue_scripts', 'ultrapress_enqueue_assets');

/**
 * Force reload of assets when version changes
 */
function ultrapress_maybe_force_reload_assets() {
    $stored_version = get_option('ultrapress_assets_version', '0');
    if (version_compare($stored_version, ULTRAPRESS_VERSION, '<')) {
        update_option('ultrapress_assets_version', ULTRAPRESS_VERSION);
        add_action('admin_notices', function() {
            echo '<div class="notice notice-info is-dismissible">';
            echo '<p>Ultrapress assets have been updated to version ' . ULTRAPRESS_VERSION . '</p>';
            echo '</div>';
        });
    }
}
add_action('admin_init', 'ultrapress_maybe_force_reload_assets');

register_activation_hook( __FILE__, function() 
	{
	$string = file_get_contents( plugin_dir_path( __FILE__ ) . "data/data.json");
	$json = json_decode($string, true);
	$circuits  = $json['circuits'];

	if (! is_array($circuits)) {
		$circuits  = array();
	} 

	$array_of_circuits  = get_option('ultrapress', array());
	if (! is_array($array_of_circuits)) {
		$array_of_circuits  = array();
	} 
	
	$array_of_circuits  = array_merge( $array_of_circuits,  $circuits);
	
	update_option('ultrapress', $array_of_circuits); 
		} 
	);