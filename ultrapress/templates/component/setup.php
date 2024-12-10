<?php
/**
 * X component for ultrapress
 *
 * this component add new page
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * this class define functionality of the component.
 *
 * @package    Ultrapress
 * @subpackage Ultrapress/components
 * @author     meedawi <meedawi.med@gmail.com>
 */
if ( ! class_exists( 'Class_Name' ) ) {
class Class_Name {
	/**
	 * Instance.
	 *
	 * Holds the plugin instance.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var Plugin
	 */
	private  static $instance = null;
	/**
	 * trigger.
	 *
	 * trigger of component. it will be used to trigger component
	 *
	 * use namespace, example: 'namespace/something'
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var trigger
	 */
	public static $trigger = ''; 
	/**
	 * name.
	 *
	 * name of component.
	 *
	 * use namespace, example: 'namespace-something'
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var name
	 */ 
	public static $name = ''; 
	/**
	 * version of component
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var version
	 */
	public static $version = '1.0.0'; 
	/**
	 * the description of component
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var description
	 */
	public static $description = ''; 
	/**
	 * Holds the component input arguments.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var accepted_args
	 */
	public static $accepted_args = array(
		'arg_example' => array(
			'primal' => 1,
			'type_of_variable' => 0,
			'required' => 1,
			'description' => '',   // description of arg
			'select' => 0, // change it to '1' if you want the user to choose from limited choices 
			'select_values' => array(
				'first_choice' => 'description of first choice',
				'second_choice' => 'description of second choice',
			), // if 'select' == 1, this assosiative array will contain the values that the user should choose from.
		),
	);
	/**
	 * Holds the component additional input arguments.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var additional_input
	 */
	public static $additional_input = array( 
		'additional_arg_example' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 1,
			'description' => '',
			'name' => '',
		),
	);
	/**
	 * Holds the component outputs
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var outputs
	 */
	public static $outputs = array(
		'/first_output_example' => array(
			'description' => '', //  description  of output
			'name' => '', //  name  of output
			'args' => array(   // args of outputs  of component
				'example_arg' => array(
					'type_of_variable' => '',
					'description' => '',
					'name' => '',
				),
			),
		),
		'/second_output_example' => array(
			'description' => '',
			'name' => '', //  name  of output
			'args' => array(
				'example_arg' => array(
					'type_of_variable' => '',
					'description' => '',
					'name' => '',
				),
			),
		)

	);

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
		}
		return self::$instance;
	}
	/**
	 * get_component_info.
	 *
	 * get the info of components.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_component_info() {
		// put the url of component icon here
		$icon_url =  'img/...';

		// put the url of outputs icon here
		$first_output_example =  'img/...';
	    $first_output_example = 'img/...';

	    self::$outputs[ '/first_output_example' ][ 'output_icon_url' ] = $first_output_example;
	    self::$outputs[ '/first_output_example' ][ 'output_icon_url' ] = $first_output_example;

		$info = array(
			'trigger' => self::$trigger,
			'name' => self::$name,
			'version' => self::$version,
			'description' => self::$description,
			'multiple_connection' => 1, // change to 0 if you want the component to be connectable to only one output
			'auto_fill_of_args' => 0, // change to 1 if you want ultrapress to pass the args of calling compoent to this component automatically
			'input' => self::$accepted_args,
			'additional_input' => self::$additional_input,
			'outputs' => self::$outputs,
			'icon_url' => $icon_url, 
			'path_of_component_dir' =>  plugin_dir_path( __FILE__ ), 
			'url_of_component_dir' => plugin_dir_url( __FILE__ ),
		);
		return $info;
	}
	/**
	 * get_triggers.
	 *
	 * triggers to be added.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_triggers() {
		return array();
	}
	/**
	 * setup.
	 *
	 * setup the component.
	 * Fires on Ultrapress loaded, after Ultrapress has finished loading but
	 * before any headers are sent.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function setup() {
		$info = self::instance()->get_component_info();
		$trig = self::instance()->get_triggers();
		Ultrapress_Class::add_trigger($trig);
		Ultrapress_Class::install_component($info);
	}
	/**
	 * filter_args.
	 *
	 * filter hook to add additional arguments.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array  $args primal args
	 *
	 * @return array
	 */
	public function filter_args($args) {
		// only primal args
		
		return $args;
	}
	/**
	 * run the component
	 *
	 * Fired by self::$trigger hook
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array    $ultra_args   arguments
	 */
	public function run($ultra_args) {
		/**
 		* Define the array of defaults
 		*/ 
		$defaults = array();

		/**
 		* Parse incoming $args into an array and merge it with $defaults
 		*/ 
		$ultra_args['args'] = wp_parse_args( $ultra_args['args'], $defaults );
		$args =  $ultra_args['args'];

		// do the work here
		$condition = true; // some condition
		if ( $condition ) {		
			// ex: if success
			$args_success = array(
				// 'example_arg' => , 
			);

			$ultra_args['args'] = $args_success;

			// trigger first_output_example
			Ultrapress_Class::do_action($ultra_args, '/first_output_example');
		} else {		
			// ex: if error
			$args_fail = array(
				// 'example_arg' => , 
			);

			$ultra_args['args'] = $args_fail;

			// trigger second_output_example
			Ultrapress_Class::do_action($ultra_args, '/second_output_example');
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
		add_action( 'ultrapress/loaded', [ $this, 'setup' ], 0 );
		add_action( self::$trigger, [ $this, 'run' ], 10,1);
		add_filter( self::$trigger . '/filter_args', [ $this, 'filter_args' ], 10,1 );
	}
}
}


class_Name::instance();