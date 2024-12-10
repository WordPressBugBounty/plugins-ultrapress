<?php
/**
 * Ultrapress plugin.
 *
 * The main plugin handler class is responsible for initializing Ultrapress. The
 * class registers  all the components required to run the plugin.
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if (! class_exists( 'Ultrapress_Class' )) {

class Ultrapress_Class {
	/**
	 * Instance.
	 *
	 * Holds the plugin instance.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var instance
	 */
	private  static $instance = null;
	/**
	 * circuits
	 *
	 * Holds the circuits
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var circuits
	 */
	public static $circuits = array();
	/**
	 * components
	 *
	 * Holds the components
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var components
	 */
	public static $components = array();
	/**
	 * composed_components
	 *
	 * Holds the composed components
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var composed_components
	 */
	public static $composed_components = array();
	/**
	 * circuits_of_composed_components
	 *
	 * Holds the circuits of composed components
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var circuits_of_composed_components
	 */
	public static $circuits_of_composed_components = array();
	/**
	 * used_components
	 *
	 * used components in get_components function
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var circuits_of_composed_components
	 */
	public static $used_components = array();
	/**
	 * outputs_of_composed_component
	 *
	 * used in save_composed_component function
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var outputs_of_composed_component
	 */
	public static $outputs_of_composed_component = array();
	/**
	 * nodes_outputs_of_composed_component
	 *
	 * used in save_composed_component function
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var nodes_outputs_of_composed_component
	 */
	public static $nodes_outputs_of_composed_component = array();
	/**
	 * triggers_history
	 *
	 * used in add_triggers function
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var triggers_history
	 */
	public static $triggers_history = array();
	/**
	 * paths
	 *
	 * Holds the paths
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var paths
	 */
	public static $paths = array();
	/**
	 * packages
	 *
	 * Holds  packages
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var packages
	 */
	public static $packages = array();
	/**
	 * Holds names of templates
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var templates_name
	 */
	public static $templates_name = array();
	/**
	 * Holds paths of templates
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var templates_paths
	 */
	public static $templates_paths = array();
	/**
	 * triggers
	 *
	 * Holds the triggers
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var triggers
	 */
	public static $triggers = array();

	/**
	 * Clone.
	 *
	 * Disable class cloning and throw an error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __clone() {}
	/**
	 * Wakeup.
	 *
	 * Disable unserializing of the class.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __wakeup() {}
	public static function process_outputs( $output ) {
		$output [ 'connection' ] = '';
		$output [ 'idOfConnection' ] = '';
		$output [ 'args_options' ] = array();

		return $output ;

	}
	 /** 
	 * install component
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array    $args   info of component
 	 * @param string  $caller the history of execution
	 */
	public static function install_component($args) {
				$trigger = $args[ 'trigger' ] ;
		$name = $args[ 'name' ];
		$version = $args[ 'version' ];
		$description = $args[ 'description' ];
		if (array_key_exists('multiple_connection', $args)) {
			$multiple_connection = $args[ 'multiple_connection' ];
		} else {
			$multiple_connection = '1';	
		}
		
		if (array_key_exists('auto_fill_of_args', $args)) {
			$auto_fill_of_args = $args[ 'auto_fill_of_args' ];
		} else {
			$auto_fill_of_args = '0';	
		}

		$input = $args[ 'input' ];
		$additional_input = $args[ 'additional_input' ];
		$outputs = $args[ 'outputs' ];

		$icon_url = $args[ 'icon_url' ];
		$path_of_component_dir = $args[ 'path_of_component_dir' ];
		
		$export = array();

		// add some data to $outputs (internal use)
		foreach ($outputs as $key => $output) {
			$output = self::process_outputs($output);
		}
		

		self::$components[ $trigger ] =  array(
				'trigger_of_component' => $trigger,
				'name' => $name,
				'version' => $version,
				'path' => '',
				'composed' => 0,
				'x' => 200,
 			 	'y' => 260,
 			 	'multiple_connection' =>  $multiple_connection,
 			 	'auto_fill_of_args' =>  $auto_fill_of_args,
 			 	'icon_url' => $icon_url,
 			 	'path_of_component_dir' => $path_of_component_dir,
 			 	'disc' =>  $description,
 			 	'outputConnectedToThisCirc' => '',
 			 	'input' => $input,
 			 	'additional_input' => $additional_input,
 			 	'export' => $export,
				'outputs' => $outputs,
			);

	}

	/** 
	 * save new composed component
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string  $circuit_key: key of circuit converted to composed component
 	 * @param string  $trigger    : trigger of composed component  
 	 * @param string  $name       : name of composed component 
 	 * @param string  $disc       : disc of composed component 
 	 *
 	 * @return bool   true if composed component saved 
	 */
	public static function save_composed_component($circuit_key, $trigger, $name, $disc ) {
		$array_of_circuits  = self::$circuits;
		if (!array_key_exists( $circuit_key, $array_of_circuits) ) {
			return false;
		}
		$circuit = $array_of_circuits[ $circuit_key ]; 
		$first_trigger = $circuit ['first_trigger'];
		$trigger_to_be_replaced = $circuit ['trigger_of_circuit'] . '|' . $first_trigger;

		$id_of_first_component = $circuit ['id_of_first_component'];
		$input = $circuit['arch'] [ $id_of_first_component ] ['input'];
		$additional_input = $circuit['arch'] [ $id_of_first_component ] ['additional_input'];
		$trigger_of_circuit = $circuit['trigger_of_circuit'];

		// calculate output of circuit
		self::$outputs_of_composed_component = array();
		self::$nodes_outputs_of_composed_component = array();
		$arch_of_circ = $circuit['arch'];

		self::recurrent_outputs_of_composed_component($circuit, $id_of_first_component, '');


		$composed_component =  array(
			'trigger_of_component' => $trigger,
			'name' => $name,
			'version' => '',
			'composed' => 1,	
			'path' => '',
			'x' => 200,
		 	'y' => 200,
		 	'icon_url' => plugin_dir_url( __FILE__ ) . 'img/images.jpg',
		 	'path_of_component_dir' => '',
		 	'disc' => $disc,
		 	'outputConnectedToThisCirc' => '',
		 	'input' => array_merge(array(), $input),
		 	'additional_input' => array_merge(array(), $additional_input),
		 	'export' => array(),
			'outputs' => self::$outputs_of_composed_component,
			);

		self::$components[ $trigger ] = $composed_component;
		self::$composed_components[ $trigger ] = $composed_component;
		self::$circuits_of_composed_components [ $trigger ] = array_merge(array(), $circuit);

		update_option('ultrapress_circuits_of_composed_components', self::$circuits_of_composed_components);
		return update_option('ultrapress_composed_components', self::$composed_components);	


	}
	/** 
	 * calculate outputs of composed component
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array  $current_circuit : hold current circuit
 	 * @param string  $key_of_component : key of  component  
 	 * @param string  $path       : path of execution
	 */
	public static function recurrent_outputs_of_composed_component($current_circuit, $key_of_component, $path) {
		$arch_of_circ = $current_circuit['arch'];
		$component  = $arch_of_circ[$key_of_component];
		$new_path = $path . '|' . $component['trigger_of_component'];

		foreach ($component['outputs'] as $key_output => $output) {
			if (array_key_exists('blocked', $output) && $output['blocked']) {
				continue;
			}
			$connection = $output['connection'];
			$key_of_next_component = $output['idOfConnection'];
			$path_of_next_component = $new_path  . $key_output;
			
			if (! $connection) {	
				if ($component['trigger_of_component'] == 'ultra/ultra_single_node') {
					if (! array_key_exists($key_of_component, self::$nodes_outputs_of_composed_component)) {
						self::$nodes_outputs_of_composed_component[$key_of_component] = $path_of_next_component;
						self::$outputs_of_composed_component[$path_of_next_component] = array_merge(array(), $output);
						self::$outputs_of_composed_component[$path_of_next_component]['additional_paths'] = array();
					} else {
						$out = self::$nodes_outputs_of_composed_component[$key_of_component];
						self::$outputs_of_composed_component[$out]['additional_paths'][] = $path_of_next_component;
					}								

				} else {
					self::$outputs_of_composed_component[$path_of_next_component] = array_merge(array(), $output);
				}

			} else {
				self::recurrent_outputs_of_composed_component($current_circuit, $key_of_next_component, $path_of_next_component);

			}
		}
	}

	/** 
	 * construct arguments of hooks
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array   $args_options   : hold options to construct args
 	 * @param array   $args_from_hook : hold args from hook
 	 * @param string  $id_of_component       : id of component
 	 * @param string  $key_of_circuit        : key of circuit
 	 *
 	 * @return array  array contain constructes args
	 */
	public static function construct_args($args_options, $args_from_hook, $id_of_component, $key_of_circuit= false , $composed_circuit= false, $key_of_composed_circuit = false ) {

		$input_args = $args_from_hook;
		$input = $input_args['args'];
		$res_args = array();
		if ($key_of_circuit) {
			// if component inside circuit
			if ($composed_circuit) {
				$next_component = self::$circuits_of_composed_components[$key_of_composed_circuit]['arch'][$id_of_component];
			} else {
				$next_component = self::$circuits[$key_of_circuit]['arch'][$id_of_component];
			}
		}  else {
			// if solo component
			$next_component = self::$components[$id_of_component];
		}
		
		
		$input_of_next_component = $next_component['input'];
		$next_component_trigger =$next_component['trigger_of_component'];

		// primal args
		if (! $input_of_next_component) {
			return $args_from_hook;
		}

		foreach ($input_of_next_component as $key => $value) {		

			if ($value['primal']  ) {
				$type_of_map = $args_options[$key]['type_of_map'];
				if ( ('' === $args_options[$key]['map']) && ( 'map' ===  $type_of_map) ) {
					continue;
				}

				switch ($type_of_map) {
				    case "map":
				    	$key_of_arg = $args_options[$key]['map']; 
				    	$first_character = substr($key_of_arg, 0, 1);
				    	$last_character = substr($key_of_arg, -1);  
				    	if ( '[' == $first_character ) {
				    		$key_of_arg = substr($key_of_arg, 1);
				    	}
				    	if ( ']' == $last_character ) {
				    		$key_of_arg = substr($key_of_arg, 0, -1);
				    	}

				  		$res_args[$key] =  $input[ $key_of_arg ];
				        break;
				    case "literal":
					    $res_args[$key] = self::construct_literal($args_options[$key]['map'], $input);
				        break;
				    case "function": 
				    	$res_args[$key] = call_user_func($args_options[$key]['map'], $input);
				        break;
				    case "eval":
					    $res_args[$key] = self::construct_eval($args_options[$key]['map'], $input);
				        break;
				    case "select":
				  		$res_args[$key] =  $args_options[$key]['map'];
				        break;
				}		
			}
		}

		// for additional arguments
		if (has_filter( "{$next_component_trigger}/filter_args" )) {
			$res_args = apply_filters( "{$next_component_trigger}/filter_args", $res_args );
		}
		
		$input = wp_parse_args( $res_args, $input );

		// non primal args
		foreach ($input_of_next_component as $key => $value) {
			if (! $value['primal']  ) {
				$type_of_map = $args_options[$key]['type_of_map'];
				if ( ('' === $args_options[$key]['map']) && ( 'map' ===  $type_of_map) ) {
					continue;
				}

				switch ($type_of_map) {
				    case "map":
				    	$key_of_arg = $args_options[$key]['map']; 
				    	$first_character = substr(key_of_arg, 0, 1);
				    	$last_character = substr(key_of_arg, -1);  
				    	if ( '[' == $first_character ) {
				    		$key_of_arg = substr(key_of_arg, 1);
				    	}
				    	if ( ']' == $last_character ) {
				    		$key_of_arg = substr(key_of_arg, 0, -1);
				    	}

				  		$res_args[$key] =  $input[ $key_of_arg ];
				        break;
				    case "literal":
					    $res_args[$key] = self::construct_literal($args_options[$key]['map'], $input);
				        break;
				    case "function": 
				    	$res_args[$key] = call_user_func($args_options[$key]['map'], $input);
				        break;
				    case "eval":
					    $res_args[$key] = self::construct_eval($args_options[$key]['map'], $input);
				        break;
				    case "select":
				  		$res_args[$key] =  $args_options[$key]['map'];
				        break;
				}	
			}
		}

		$input_args['args'] = $res_args;

		return $input_args;

	}
	/** 
	 * helper function - replace variables inside strings
	 *
	 * @since 1.0.0
	 * @access public
	 *
 	 * @param string  $string       : string to be processed
 	 * @param array   $input        : array of args
 	 *
 	 * @return string  processed string
	 */
	public static function construct_literal($string, $input) {

		$tmp_string = $string;

		foreach ($input as $key => $value) {
			$replaceKey = '[' . $key . ']';
			$tmp_string = str_replace($replaceKey, $value, $tmp_string);
		}

		return $tmp_string;
	}
	/** 
	 * helper function - eval argument
	 *
	 * @since 1.0.0
	 * @access public
	 *
 	 * @param string  $string       : string to be evaluated
 	 * @param array   $input        : array of args
 	 *
 	 * @return string  evaluated string
	 */
	public static function construct_eval($string, $input) {

		$tmp_string = $string;

		foreach ($input as $key => $value) {
			$replaceKey = '[' . $key . ']';
			$tmp_string = str_replace($replaceKey, ' $input["' . $key . '"] ' , $tmp_string);
		}
		$tmp_string = str_replace('||','"', $tmp_string);

		$res = '';

		$res = @eval('return ' . $tmp_string . ';');
		if (! $res){
			$error_message = 'Caught exception: ' . error_get_last() . '<br> expression of eval: ' . 'return ' . $tmp_string . ';'; 
		    update_option('construct_eval_debug' , $error_message);
		}
		
		return $res;	
	}
	/** 
	 * activate package
	 *
	 * @since 1.0.0
	 * @access public
	 *
 	 * @param string  $name_of_package  : name of package  	 
 	 * @param string  $path_of_package  : path of package directory
	 */
	public static function activate_package($name_of_package, $path_of_package) {
		$abs_path_of_package = WP_PLUGIN_DIR . $path_of_package;
		$json_path = file_get_contents( $abs_path_of_package . '/data.json');
		$json = json_decode($json_path, true);

		$desc_of_package  = $json['desc'];
		$circuits_of_package  = $json['circuits'];
		$composed_components_of_package  = $json['composed_components'];
		$circuits_of_composed_components_of_package  = $json['circuits_of_composed_components'];

		foreach ($circuits_of_package as $key_of_circ => $circ) {
				self::activate_circuit($key_of_circ, $circ);
		}

		foreach ($composed_components_of_package as $key_of_cc => $cc) {
			self::$composed_components[$key_of_cc] = $cc;
			self::$circuits_of_composed_components[$key_of_cc] = $circuits_of_composed_components_of_package[$key_of_cc];
		}

		update_option('ultrapress_composed_components', self::$composed_components);
		update_option('ultrapress_circuits_of_composed_components', self::$circuits_of_composed_components);

		self::$packages [$name_of_package] = array(
				'name' => $name_of_package,
				'desc' => $desc_of_package,
				'path' => $path_of_package,
			);
		update_option('ultrapress_packages', self::$packages);
	}
	/** 
	 * deactivate package
	 *
	 * @since 1.0.0
	 * @access public
	 *
 	 * @param string  $name_of_package  : key of package 
	 */

	public static function deactivate_package($name_of_package) {

		foreach (self::$circuits as $key => $circ) {
			if ( array_key_exists('package', $circ) && ($name_of_package == $circ[ 'package' ]) ) {
				self::deactivate_circuit($key);
			}
		}

		unset(self::$packages[$name_of_package]);
		update_option('ultrapress_packages', self::$packages);
	}
	/** 
	 * deactivate circuit
	 *
	 * @since 1.0.0
	 * @access public
	 *
 	 * @param string  $key_of_circ  : key of circuit 
	 */

	public static function deactivate_circuit($key_of_circ) {
		self::run_deactivate($key_of_circ);

		unset(self::$circuits[$key_of_circ]);
		update_option('ultrapress', self::$circuits);
	}
	/** 
	 * run deactivate component of circuit
	 *
	 * @since 1.0.0
	 * @access public
	 *
 	 * @param string  $key_of_circ  : key of circuit 
	 */
	public static  function run_deactivate($key_of_circ) {
		$array_of_circuits  = self::$circuits;
		$current_circuit    = $array_of_circuits[$key_of_circ];
		if (!array_key_exists( 'deactivate', $current_circuit) ) {
			return 0;
		}
		$deactivates_comp      = $current_circuit['deactivate'];

		foreach ($deactivates_comp as $key => $value) {
			$component    = self::$components[ $key ];

			$trigger_of_component    = $key;
			$args_options            = $value;
			$is_composed      = $component['composed'];

			if ($is_composed) {
				$circuit = self::$circuits_of_composed_components [ $trigger_of_component ];
				$first_trigger = $circuit['first_trigger'];
				$str = '|' . $first_trigger;
				$is_inside_composed_circuit = 1;
				$key_of_composed_circuit = $trigger_of_component;
			} else {
				$str = '';
				$is_inside_composed_circuit = 0;
			}
			
			$args_from_hook = array(
				'args' => array(),
				'__ultra_internal' => array(
					'__ultra_path_of_exec' => $trigger_of_component . $str  ,
					'__exported' => array()  ,
				),		
			);

			$args_for_hook = self::construct_args($args_options, $args_from_hook, $trigger_of_component);
   		    do_action($trigger_of_component, $args_for_hook);  
		}

	}
	/** 
	 * activate circuit
	 *
	 * @since 1.0.0
	 * @access public
	 *
 	 * @param string  $key_of_circ  : key of circuit  	 
 	 * @param string  $circuit_path  : path of circuit directory
	 */
	public static function activate_circuit($key_of_circ, $circ) {
		self::$circuits[$key_of_circ] = $circ;

		update_option('ultrapress', self::$circuits);

		$activate_circuits  =  get_option('ultrapress_activate_circuits', array());
		$activate_circuits[] = $key_of_circ;

		update_option('ultrapress_activate_circuits', $activate_circuits);
	}
	/** 
	 * run activate components of circuit
	 *
	 * @since 1.0.0
	 * @access public
	 */	
	public  function run_activate() {
		$activate_circuits  =  get_option('ultrapress_activate_circuits', array());

		update_option('ultrapress_activate_circuits', array());

		foreach ($activate_circuits as $key => $key_of_circ) {
			$array_of_circuits  = self::$circuits;

			if ( !array_key_exists( $key_of_circ, $array_of_circuits) ) {
				continue;
			}

			$current_circuit    = $array_of_circuits[$key_of_circ];

			if ( !array_key_exists( 'activate', $current_circuit) ) {
				continue;
			}	

			$activates_comp      = $current_circuit['activate'];

			foreach ($activates_comp as $key => $value) {
				$component    = self::$components[ $key ];

				$trigger_of_component    = $key;
				$args_options            = $value;
				$is_composed      = $component['composed'];

				if ($is_composed) {
					$circuit = self::$circuits_of_composed_components[$trigger_of_component];
					$first_trigger = $circuit['first_trigger'];
					$str = '|' . $first_trigger;
					$is_inside_composed_circuit = 1;
					$key_of_composed_circuit = $trigger_of_component;
				} else {
					$str = '';
					$is_inside_composed_circuit = 0;
				}
				
				$args_from_hook = array(
					'args' => array(),
					'__ultra_internal' => array(
						'__ultra_path_of_exec' => $trigger_of_component . $str  ,
						'__exported' => array()  ,
					),		
				);

				$args_for_hook = self::construct_args($args_options, $args_from_hook, $trigger_of_component);
	   		    do_action($trigger_of_component, $args_for_hook); 
			}


		}
		update_option('ultrapress_activate_circuits', array());
	}
	/** 
	 * delete composed component 
	 *
	 * @since 1.0.0
	 * @access public
	 *
 	 * @param string  $key_of_comp  : key of composed component
 	 *
	 */
	public static function delete_composed_component($key_of_comp) {
		unset(self::$components[$key_of_comp]);
		unset(self::$composed_components[$key_of_comp]);
		unset(self::$circuits_of_composed_components[$key_of_comp]);
		update_option('ultrapress_composed_components', self::$composed_components);
		update_option('ultrapress_circuits_of_composed_components', self::$circuits_of_composed_components);
	}
	/** 
	 * add triggers of circuit
	 *
	 * @since 1.0.0
	 * @access public
	 *
 	 * @param string  $key_of_circuit  : key of circuit
 	 *
	 */
	public static function add_triggers($key_of_circuit) {
		$array_of_circuits  = self::$circuits;
		$current_circuit    = $array_of_circuits[$key_of_circuit];
		$trigger_of_circuit = $current_circuit['trigger_of_circuit'];
		$first_trigger      = $current_circuit['first_trigger'];
		
		$id_of_first_component = $current_circuit['id_of_first_component'];	
		if (array_key_exists('triggers', $current_circuit)) {
			$triggers 			= $current_circuit['triggers'];
		} else {
			$triggers 			= array();
			$array_of_circuits[$key_of_circuit]['triggers'] = $triggers ;
			$current_circuit ['triggers'] = $triggers ;
		}

		foreach ($triggers  as $trigger => $trigger_info) {

			add_action( $trigger, function($arguments = null)  use ( $trigger_info, $trigger, $first_trigger,$id_of_first_component, $trigger_of_circuit ) {

				if (! array_key_exists( $trigger, self::$triggers_history)) {
					self::$triggers_history[$trigger] = array();
				}
				
				$triggers_history = self::$triggers_history;

				if (! array_key_exists( $trigger_of_circuit, $triggers_history[$trigger] ) ) {
					self::$triggers_history[$trigger][$trigger_of_circuit] = 1;
				} else  {
					return 0;
				}

				$backtrace = debug_backtrace();

			    $action_arguments = $backtrace[3]['args'];
			    // The first element of this is going to be the $name of hook ie: $trigger
			    array_shift($action_arguments);
			    // Leaving you with the rest of the parameters available to that action
			    $array_of_args_from_hook = self::$triggers[ $trigger ]['args'];
			    // args of action $trigger
			    $args_from_action = array();
			    foreach ($array_of_args_from_hook as $arg => $arg_info) {
			    	$offset = $arg_info[ 'offset' ];
			    	$args_from_action[ $arg ] = $action_arguments[ $offset ];
			    }

			    if (has_filter( 'ultra_filter_'  . $trigger )) {
					$args_from_action = apply_filters( 'ultra_filter_'  . $trigger, $args_from_action );
				}

			    $args_from_hook = array(
					'args' => $args_from_action,
					'__ultra_internal' => array(
						'__ultra_path_of_exec' => $trigger_of_circuit . '|' . $first_trigger,
					),		
				);

			    $is_composed = self::$components[ $first_trigger ]['composed'];
			    $args_options = $trigger_info ['args_options'];
				$args_for_hook = self::construct_args($args_options, $args_from_hook, $id_of_first_component, $trigger_of_circuit);

				$args_for_hook['__ultra_internal']['__exported'] = array();

				if ($is_composed) {
					$trigger_of_component = self::$circuits_of_composed_components [$first_trigger]['first_trigger'];
	
				 	$args_for_hook['__ultra_internal']['__ultra_path_of_exec'] = $trigger_of_circuit . '|' . $first_trigger . '|' . $trigger_of_component;	
				} 
				
	   		    do_action($first_trigger, $args_for_hook); 		   
		},
		10,1 );
		}		
	}
	/** 
	 * initialise first trigger of circuit
	 *
	 * @since 1.0.0
	 * @access public
	 *
 	 * @param string  $key_of_circuit  : key of circuit
 	 *
	 */
	public static function first_trigger($key_of_circuit) {
		$current_circuit    = self::$circuits[$key_of_circuit];
		$trigger_of_circuit = $current_circuit['trigger_of_circuit'];
		$first_trigger      = $current_circuit['first_trigger'];

		if (!array_key_exists( $first_trigger, self::$components)) {
			return;
		}
		
		$component  = self::$components[$first_trigger];
		$composed = $component['composed'];
		if ($composed) {
			$trigger_of_component = self::$circuits_of_composed_components [$first_trigger]['first_trigger'];
		} else {
			$trigger_of_component = '';
		}

		add_action( $trigger_of_circuit, function($args_from_hook) use ( $first_trigger, $trigger_of_circuit , $composed, $trigger_of_component) {
			if ($composed) {
				$path = $trigger_of_circuit . '|' . $first_trigger . '|' . $trigger_of_component;
			} else {
				$path = $trigger_of_circuit . '|' . $first_trigger;
			}
			$args = array(
			'args' => $args_from_hook,
			'__ultra_internal' => array(
				'__ultra_path_of_exec' => $path,
				'__exported' => array(),
					),		
			);

 		    do_action($first_trigger, $args);
 		   
		},
		10,1 );
	}
	/** 
	 * run composed component
	 *
	 * @since 1.0.0
	 * @access public
	 *
 	 * @param string  $trigger_of_composed_component : trigger of composed component
 	 * @param string   $first_trigger        : first_trigger of composed component
 	 *
	 */
	public static function run_composed_component($trigger_of_composed_component, $first_trigger) {

		add_action( $trigger_of_composed_component, function($args_from_hook) use ( $first_trigger) {
 		    do_action($first_trigger, $args_from_hook);	   
		},
		10,1 );
	}
	/** 
	 * do_action called from component
	 *
	 * @since 1.0.0
	 * @access public
	 *
 	 * @param array    $args : args to be passed to next hook
 	 * @param string   $output  : output of component triggered
 	 *
	 */
	public static function do_action($args, $output) {
		$paths = self::$paths;
		$path = $args['__ultra_internal']['__ultra_path_of_exec'] . $output;
		$args['__ultra_internal']['__ultra_path_of_exec'] = $path;

		$args['args'] = array_merge( $args['__ultra_internal']['__exported'], $args['args']);

		if (array_key_exists( $path, $paths) )  {
			$info = $paths[ $path ];
			self::run_component( $path, $args );		
		} 
	}

	/** 
	 * add connection (trigger/hook) to self:$paths
	 *
	 * @since 1.0.0
	 * @access public
	 *
 	 * @param string   $key_of_comp : key of component
 	 * @param string   $key_of_output  : key of component output
 	 * @param string   $connection  : trigger of component connected to current output
 	 * @param string   $key_of_next_component  : id  of component connected to current output
 	 * @param array    $args_options  : options to construct args
 	 * @param array    $current_circuit  : processed circuit	 
 	 * @param string   $path  : path of execution 	 
 	 *
	 */
	public static function add_connection($key_of_comp, $key_of_output, $connection, $key_of_next_component, $args_options, $current_circuit, $path, $is_inside_composed_circuit = 0, $key_of_composed_circuit = 0) {
					
		$output_executed = $current_circuit['arch'][$key_of_comp]['outputs'][$key_of_output];

		$next_component_trigger = $output_executed['connection'];
		self::$paths[ $path ] = array(
			'component_trigger' => $next_component_trigger,
			'key_of_circuit' => $current_circuit['trigger_of_circuit'],
			'id_of_component' => $key_of_next_component,
			'args_options' => $args_options,
			'is_inside_composed_circuit' => $is_inside_composed_circuit,
			'key_of_composed_circuit' => $key_of_composed_circuit,
		);

	}
	/** 
	 * run component - execute the hook associated with it
	 *
	 * @since 1.0.0
	 * @access public
	 *
 	 * @param string   $path  : path of execution 	 
 	 * @param array    $args_from_hook  : args sent from hook 
 	 *
	 */
	public static function run_component($path, $args_from_hook) {
		$info = self::$paths[ $path ];
		$component_trigger = $info['component_trigger'];
		$key_of_circuit = $info['key_of_circuit'];
		$id_of_component = $info['id_of_component'];
		$args_options = $info['args_options']; 
		$is_inside_composed_circuit = $info['is_inside_composed_circuit'];
		$key_of_composed_circuit = $info['key_of_composed_circuit'];

		if ($is_inside_composed_circuit) {
			$component = self::$circuits_of_composed_components [$key_of_composed_circuit]['arch'][$id_of_component];
		} else {
			$component = self::$circuits[$key_of_circuit]['arch'][$id_of_component];
		}
		if (array_key_exists('auto_fill_of_args' , $component)) {
			$auto_fill_of_args = $component['auto_fill_of_args'];
		} else {
			$auto_fill_of_args = 0;
		}
		
		


		if ($auto_fill_of_args == 1) {
			// node component
			$args_for_hook = self::construct_args($args_options, $args_from_hook, $id_of_component, $key_of_circuit, $is_inside_composed_circuit, $key_of_composed_circuit);
			$args_for_hook['args']  = array_merge($args_from_hook['args'], array() );
		} else {
			$args_for_hook = self::construct_args($args_options, $args_from_hook, $id_of_component, $key_of_circuit,$is_inside_composed_circuit, $key_of_composed_circuit);
		}
		
		$composed = $component['composed'];

		if ( $composed ) {
			$circuit_of_composed_component = self::$circuits_of_composed_components[$component_trigger];
			$first_trigger = $circuit_of_composed_component['first_trigger'];	
			$trig = $component_trigger . '|' . $first_trigger;
		}  else {
			$trig = $component_trigger;
		}
		
		$args_for_hook['__ultra_internal']['__ultra_path_of_exec'] = $args_for_hook['__ultra_internal']['__ultra_path_of_exec'] . '|' . $trig;
		$args_for_hook['__ultra_internal']['__exported'] = array();
		if (array_key_exists('exported' , $component) ) {
			foreach ($component['exported'] as $key => $value) {
			$args_for_hook['__ultra_internal']['__exported']['_exp_' . $key] = $args_from_hook['args'][$key]; 
		}
		}

	    do_action($component_trigger, $args_for_hook); 		   
	}
	/** 
	 * return all components used inside the circuit
	 *
	 * @since 1.0.0
	 * @access public
	 *
 	 * @param string   $key_of_circuit  : key of circuit	 
 	 *
 	 * @return array  'components' =>  all (non composed) components used inside the circuit
					  'composed_components' => all composed components used inside the circuit
					  'circuits_of_composed_components' => circuits of composed components used inside the circuit
	 */
	public static function get_components($key_of_circuit, $is_composed = false) {
		self::$used_components = array(
			'components' => array() ,
			'composed_components' => array(),
			'circuits_of_composed_components' => array(),
		);

		$array_of_circuits = self::$circuits;

		if (! $is_composed	) {
			$circuit = $array_of_circuits[$key_of_circuit];
			self::recurrent_get_components($circuit);

			if (array_key_exists('activate' , $circuit)) {
				$activates_comp =  $circuit['activate'];
			} else {
				$activates_comp = array();	
			}

			if (array_key_exists('deactivate' , $circuit)) {
				$deactivates_comp =  $circuit['deactivate'];
			} else {
				$deactivates_comp = array();	
			}
			
			$activate_deactivates_comp =	array_merge($activates_comp, $deactivates_comp);
			foreach ($activate_deactivates_comp as $key => $value) {	
				$comp    = self::$components[ $key ];
				$composed = $comp['composed'];
				$trigger_of_component = $key;
				
				if ( $composed ) {
					self::$used_components['composed_components'][ $trigger_of_component ] = self::$components[ $trigger_of_component ];
					self::$used_components['circuits_of_composed_components'][ $trigger_of_component ] = self::$circuits_of_composed_components[ $trigger_of_component ];

					self::recurrent_get_components(self::$circuits_of_composed_components[ $trigger_of_component ]);	
				} else {
					self::$used_components['components'][ $trigger_of_component ] = self::$components[ $trigger_of_component ];
					//self::$used_components['circuits_of_composed_components'][ $trigger_of_component ] = self::$circuits_of_composed_components[ $trigger_of_component ];
				}
			}
		} else {
			$circuit = self::$circuits_of_composed_components[ $key_of_circuit ];
			self::recurrent_get_components($circuit);
		}		
		
		$array_of_comps = array_merge( array(), self::$used_components );

		self::$used_components = array(
			'components' => array() ,
			'composed_components' => array(),
			'circuits_of_composed_components' => array(),
		);
		return $array_of_comps;
	}
	/** 
	 * helper functions
	 *
	 * @since 1.0.0
	 * @access public
	 *
 	 * @param array   $current_circuit  : processed circuit	 
 	 *
	 */
	public static function recurrent_get_components($current_circuit) {
		$arch_of_circ = $current_circuit['arch'];
		foreach ($arch_of_circ as $key => $comp) {
		
			$composed = $comp['composed'];

			$trigger_of_component = $comp['trigger_of_component'];
			if ( $composed ) {
				if ( ! array_key_exists($trigger_of_component , self::$used_components) ) {
					self::$used_components['composed_components'][ $trigger_of_component ] = self::$components[ $trigger_of_component ];
					self::$used_components['circuits_of_composed_components'][ $trigger_of_component ] = self::$circuits_of_composed_components[ $trigger_of_component ];
				}

			self::recurrent_get_components( self::$circuits_of_composed_components[ $trigger_of_component ]);	
			} else {
				if ( ! array_key_exists($trigger_of_component , self::$used_components) ) {
					self::$used_components['components'][ $trigger_of_component ] = self::$components[ $trigger_of_component ];
				}
			}
		}
	}		
	/** 
	 * process circuits
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public static function process() {
		$array_of_circuits = self::$circuits;
		update_option('llllllll', 1333);

		foreach ($array_of_circuits as $key_of_circuit => $circ) {
			self::first_trigger($key_of_circuit);
			self::add_triggers($key_of_circuit);
			$arch_of_circ = $circ['arch'];
			$trigger_of_circuit = $circ['trigger_of_circuit'];
			$id_of_first_component = $circ['id_of_first_component'];

			self::recurrent_process($circ, $id_of_first_component, $trigger_of_circuit);

		}
		update_option('sssss', self::$nodes_outputs_of_composed_component);

	}
	/** 
	 * helper function - process $current_circuit
	 *
	 * @since 1.0.0
	 * @access public
	 *
 	 * @param array   $current_circuit    : processed circuit	 
 	 * @param string   $key_of_component  : key of current processed component	 
 	 * @param string   $path  : path of current processed component
	 */
	public static function recurrent_process($current_circuit, $key_of_component, $path, $is_inside_composed_circuit = 0, $key_of_composed_circuit = 0 ) {	
		if (! array_key_exists( 'arch', $current_circuit)) {
			return false;
		} 
		$arch_of_circ = $current_circuit['arch'];
		
		if (! array_key_exists($key_of_component, $arch_of_circ)) {
			return 2;
		} 
		$component  = $arch_of_circ[$key_of_component];
		$trigger_of_component = $component['trigger_of_component']; 
		$new_path = $path . '|' . $trigger_of_component; 		
		$composed = $component['composed'];

		if ( $composed ) {
			$circuit_of_composed_component = self::$circuits_of_composed_components[$trigger_of_component];
			$id_of_first_component = $circuit_of_composed_component['id_of_first_component'];
			$first_trigger = $circuit_of_composed_component['first_trigger'];
			self::recurrent_process(
				$circuit_of_composed_component,
			    $id_of_first_component,
			    $new_path,
			    1,
			    $trigger_of_component
			);	
		}

		foreach ($component['outputs'] as $key_output => $output) {
			if (array_key_exists('blocked', $output) && $output['blocked']) {
				continue;
			}
			self::$nodes_outputs_of_composed_component[$key_output] = $output;
			if (! array_key_exists( 'connection', $output)) {
				$output['connection'] = '';
				$output['idOfConnection'] = '';
			}
			$connection = $output['connection'];
			$key_of_next_component = $output['idOfConnection'];
			if (! array_key_exists($key_of_next_component, $arch_of_circ)) {
				return 1;
			}
			$path_of_next_component = $new_path  . $key_output;
			if ($connection) {
				if (! array_key_exists( 'args_options', $output)) {
					$output['args_options'] = array();
				}
				$args_options = $output['args_options'];
				self::add_connection($key_of_component, $key_output, $connection, $key_of_next_component, $args_options, $current_circuit, $new_path . $key_output, $is_inside_composed_circuit, $key_of_composed_circuit);
				self::recurrent_process($current_circuit, $key_of_next_component, $path_of_next_component,$is_inside_composed_circuit, $key_of_composed_circuit);
				
				if (array_key_exists('additional_paths', $output) ) {
					update_option('llllllll', 4444);
					$additional_paths = $output['additional_paths'];
				}
			}
		}
	}
	/** 
	 * process composed components
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public static function process_composed_components() {
		$composed_components = self::$composed_components;

		foreach ($composed_components as $key_composed_component => $composed_component) {
				$trigger_of_composed_component = $composed_component['trigger_of_component'];
				$circuit = self::$circuits_of_composed_components[$trigger_of_composed_component];
				$first_trigger = $circuit['first_trigger'];
				$id_of_first_component = $circuit['id_of_first_component'];

				self::run_composed_component($trigger_of_composed_component, $first_trigger);

				self::recurrent_process(
					$circuit,
				    $id_of_first_component,
				    $trigger_of_composed_component,
				    1,
				     $trigger_of_composed_component
				);				
		}
	}
	/**
	 * Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			/**
			 * Ultrapress loaded.
			 *
			 * Fires when Ultrapress was fully loaded and instantiated.
			 *
			 * @since 1.0.0
			 */
			do_action( 'ultrapress/loaded' );
			do_action( 'ultrapress/process' );

		}
		return self::$instance;
	}
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * load activated packages
		 */

		// array containing directories of activated circuits
		$packages = self::$packages;
 
		foreach($packages as $pack)
		{
			/**
			 * load functions.php of activated package
			 */
			$dir = WP_PLUGIN_DIR . $pack[ 'path' ];

			if (file_exists( $dir   . '/functions.php' )) {
				require_once  $dir   . '/functions.php';
			}

			/**
		 	* load components of activated package
		 	*/
			$dirs_of_components = glob( $dir   . '/components' .  '/*', GLOB_ONLYDIR);
 
			foreach($dirs_of_components as $dir_of_components)
			{
			    $comp = $dir_of_components . '/setup.php';
			    if ( file_exists( $comp ) ) {
			    	require_once $comp;
			    }
				
			}


			/**
		 	* load templates of activated packages
		 	*/
			$array_of_dirs_of_templates = glob( $dir   . '/templates' .  '/*', GLOB_ONLYDIR);

			foreach ($array_of_dirs_of_templates as $dir_of_template) {
				$template = $dir_of_template . '/index.php';
				if (file_exists( $dir_of_template   . '/functions.php' )) {
					require_once  $dir_of_template   . '/functions.php';
				}

			    if ( file_exists( $template ) ) {
			    	$basename = basename($dir_of_template);

			    	self::$templates_name[$basename] = $basename;
			    	self::$templates_paths[$basename] = $dir_of_template;
			    }
			}
		}

		// load add-custom-page-templates.php script
		require_once  plugin_dir_path( __FILE__ ) . 'add-custom-page-templates.php';
		new PageTemplater_Class(self::$templates_name, self::$templates_paths);
	}
	/** 
	 * load circuits & composed components & components
	 *
	 * @since    1.0.0
	 * @access   private
	 */	
	private function load() {
		self::$composed_components  =  get_option('ultrapress_composed_components', array());
		self::$circuits_of_composed_components  =  get_option('ultrapress_circuits_of_composed_components', array());
		self::$components  = array_merge(self::$components, self::$composed_components) ;
		self::$circuits = get_option('ultrapress', array());
		self::$packages = get_option('ultrapress_packages', array());
		do_action( 'ultrapress/load_circuits' );
	}
	/** 
	 * addtrigger - called from component
	 *
	 * @since    1.0.0
	 * @access   public
	 * @static
	 *
	 * @param array   $args 
	 */
	public static function add_trigger( $args) {
		self::$triggers =array_merge( self::$triggers, $args );

	}
	/** 
	 * load triggers
	 *
	 * @since    1.0.0
	 * @access private
	 */
	private function load_triggers() {
		/** 
		 * defaults triggers/hooks: used to fire circuits
		 */
		self::$triggers[ 'comment_post' ] =  array(
			'disc' => 'triggered when adding comment to post',
			'args' => array(
				'comment_id' => array(
								'offset' => 0,
								'type_of_variable' => false,
								'disc' => 'comment_post saved',
							),
			),
			'additional_args' => array(
				'post_id' => array(
								'offset' => 0,
								'type_of_variable' => false,
								'disc' => 'post ID',
				),
				'post_author_id' => array(
								'offset' => 0,
								'type_of_variable' => false,
								'disc' => 'author post ID',
				),
				'comment_author_id' => array(
								'offset' => 0,
								'type_of_variable' => false,
								'disc' => 'author comment ID',
				),
			),

			);

		// additional arguments to be passed with the arguments of trigger/hook
		add_filter( 'ultra_filter_comment_post', function ($args) {
			$comment_id = $args[ 'comment_id' ];
			$comment = get_comment( $comment_id );

			$post_id = $comment->comment_post_ID;
			$comment_author_id = $comment->user_id;
			$post_author_id = get_post_field( 'post_author', $post_id );

			$args[ 'comment_author_id' ] = $comment_author_id;
			$args[ 'post_author_id' ] = $post_author_id;
			$args[ 'post_id' ] = $post_id;

		    return $args;
		}, 11 );


		self::$triggers[ 'wp_insert_post' ] =  array(
			'disc' => 'triggered when inserting post',
			'args' => array(
				'post_id' => array(
								'offset' => 0,
								'type_of_variable' => false,
								'disc' => 'post_id saved',
							),
			),

			);

		self::$triggers[ 'init' ] =  array(
			'disc' => 'Fires after WordPress has finished loading but before any headers are sent.',
			'args' => array(),
			);
		
	}


	/**
	 * Init.
	 *
	 * Initialize Ultrapress Plugin. Register Ultrapress support for all the
	 * supported post types and initialize Ultrapress components.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {

		/**
		 * Ultrapress init.
		 *
		 * Fires on Ultrapress init, after Ultrapress has finished loading but
		 * before any headers are sent.
		 *
		 * @since 1.0.0
		 */
		do_action( 'ultrapress/init' );	
	}
	/**
	 * Ajax callback
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function ajax_ultrapress() {	 
	    $file_data = isset( $_FILES ) ? $_FILES : array();

	    $args = $_POST["args"]; 
	    //$args = array_map( 'sanitize_text_field', $args ); 
	    $args = array_merge( $args, $file_data );
	    
   
	    $trigger_of_circuit = sanitize_text_field ( $_POST["trigger_of_circuit"] );	

	    // check the nonce
	    if ( check_ajax_referer( 'ultrapress_', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }

	    $response = array();
	    
	    $array_of_circuits = self::$circuits;

	    if (!array_key_exists($trigger_of_circuit, $array_of_circuits)) {
	    	$response["response"] = 'F';
	    	$response["circuit_exist"] = false;
	    	$response["error_message"] = __('there is no circuit with this name', 'ultrapress');
	    	echo json_encode( $response );
			die();
	    }

	    $circuit = $array_of_circuits[ $trigger_of_circuit ];
	    $ajaxable = $circuit[ 'ajaxable' ];

	    if ( $ajaxable) {
	    	do_action($trigger_of_circuit , $args);

	    	$response["response"] = 'T';
	    	$response["circuit_exist"] = true;
	    	$response["ajaxable"] = true;
	    	echo json_encode( $response );
			die();
	    } else {
	    	$response["response"] = 'F';
	    	$response["ajaxable"] = false;
	    	$response["error_message"] = __('the circuit is not ajaxable', 'ultrapress');
	    	echo json_encode( $response );
	    	die();
	    }
	    
	}
	/**
	 * Plugin constructor.
	 *
	 * Initializing Ultrapress plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function __construct() {
		$this->load_triggers();
		$this->load();
		$this->load_dependencies();

		add_action( 'init', [ $this, 'init' ], 0 );
		add_action( 'wp_ajax_nopriv_ultrapress', array( $this, 'ajax_ultrapress' ) );
   		add_action( 'wp_ajax_ultrapress', array( $this, 'ajax_ultrapress' ) );
		
	}
}

Ultrapress_Class::instance(); 
Ultrapress_Class::process_composed_components();
Ultrapress_Class::process(); 

function ultrapress_plugins_loaded_action () {
	Ultrapress_Class::instance()->run_activate();

}
add_action( 'init', 'ultrapress_plugins_loaded_action', 0 );

}  // end if
