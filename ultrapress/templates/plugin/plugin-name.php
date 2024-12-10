<?php

/**
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           ultrapress
 *
 * @wordpress-plugin
 * Plugin Name:       plugin-name
 * Plugin URI:        http://example.com/
 * Description:       Description
 * Version:           1.0.0
 * Author:            author
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ultrapress
 * Domain Path:       /languages
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( !function_exists('is_plugin_active') ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
update_option('install_state', 'already');
if ( ! ( is_plugin_active( 'ultrapress/ultrapress.php') || has_action('ultrapress/loaded')  ) ) {
	update_option('install_state', glob( plugin_dir_path( __FILE__ ) . "ultrapress/packages/*", GLOB_ONLYDIR));
	require_once plugin_dir_path( __FILE__ ) . 'ultrapress/ultrapress.php';
}

register_activation_hook( __FILE__, function() 
	{
	$dirs = glob( plugin_dir_path( __FILE__ ) . "ultrapress/packages/*", GLOB_ONLYDIR);
	foreach ($dirs as $dir) {
		$name_of_package = basename($dir);
		$path_of_package = substr($dir, strlen(WP_PLUGIN_DIR));
		Ultrapress_Class::activate_package( $name_of_package, $path_of_package);
			}	
	} 
);


register_deactivation_hook( __FILE__, function() 
	{
		$dirs = glob( plugin_dir_path( __FILE__ ) . "ultrapress/packages/*", GLOB_ONLYDIR);
		foreach ($dirs as $dir) {
			$name_of_package = basename($dir);
			Ultrapress_Class::deactivate_package( $name_of_package );
		}
	} 
);


add_action( 'init', function() {
 	Ultrapress_Class::instance()->run_activate();	   
		},
		10,1 );