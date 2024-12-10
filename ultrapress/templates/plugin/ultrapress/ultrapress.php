<?php

/**
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           ultrapress
 *
 * @wordpress-plugin
 * Plugin Name:       Ultrapress 
 * Plugin URI:        http://example.com
 * Description:       Build and connect features and plugins with visual scripting
 * Version:           1.0.0
 * Author:            meedawi
 * Author URI:        http://example.com/
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
 
define( 'ULTRAPRESS_PATH_SLAVE', plugin_dir_path( __FILE__ ) );

require_once ULTRAPRESS_PATH_SLAVE . 'includes/plugin.php';

function ultrapress_slave_version() {
	return '1.0.0';
}
