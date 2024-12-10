<?php
/**
 * circuit for ultrapress
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// load components of circuit
$dirs = glob( plugin_dir_path( __FILE__ ) . 'components\*', GLOB_ONLYDIR);
 
foreach($dirs as $dir)
	{
	    $comp = $dir . '/' . basename($dir) . '.php';
		require_once $comp;
	}

$string = file_get_contents( plugin_dir_path( __FILE__ ) . "data.json");
$json = json_decode($string, true);

// load circuits
$array_of_circuits  = get_option('ultrapress', array());

foreach ($json as $key_of_circ => $circ) {
	$array_of_circuits[ $key_of_circ ]  = $circ;
	
}

update_option('ultrapress', $array_of_circuits);