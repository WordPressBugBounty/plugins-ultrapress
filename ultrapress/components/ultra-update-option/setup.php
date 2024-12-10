<?php
/**
 * ultra-update-option component for ultrapress
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
if ( ! class_exists( 'ultra_Update_Option' ) ) {
class ultra_Update_Option {
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
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var trigger
	 */
	public static $trigger = 'ultra/update_option';
	/**
	 * name.
	 *
	 * name of component.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var name
	 */
	public static $name = 'ultra-update-option';
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
	public static $description = 'update option';

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
		'option' => array(
			'primal' => 1,
			'type_of_variable' => 0,
			'required' => 1,
			'description' => '(string) (Required) Option name. Expected to not be SQL-escaped.',
			'name' => 'option',
		),
		'value' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 1,
			'description' => '(mixed) (Required) Option value. Must be serializable if non-scalar. Expected to not be SQL-escaped.',
			'name' => 'option',
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
						'old_value' => array(
							'type_of_variable' => 0,
							'description' => 'old value of Option if exist, or ""',
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
		'/success' => array(
			'description' => 'option updated successifully',
			'name' => 'success',
			'args' => array(
				'value' => array(
					'type_of_variable' => '',
					'description' => 'new value',
					'name' => 'value',
				),
			),
		),
		'/fail' => array(
			'description' => 'option not updated',
			'args' => array(),
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
		$path_of_component_dir = plugin_dir_path( __FILE__ );
		$url_of_component_dir = plugin_dir_url( __FILE__ );
		$icon_url = 'img/pencil-icon.png';
		$success_icon_url = 'img/success-icon.png';
	    $fail_icon_url = 'img/Button-Delete-icon.png';
	    self::$outputs[ '/success' ][ 'output_icon_url' ] = $success_icon_url;
	    self::$outputs[ '/fail' ][ 'output_icon_url' ] = $fail_icon_url;

		$info = array(
			'trigger' => self::$trigger,
			'name' => self::$name,
			'version' => self::$version,
			'description' => self::$description,
			'input' => self::$accepted_args,
			'additional_input' => self::$additional_input,
			'outputs' => self::$outputs,
			'icon_url' => $icon_url, 
			'url_of_component_dir' => $url_of_component_dir,
			'path_of_component_dir' => $path_of_component_dir, 
		);
		return $info;

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
		$args[ 'old_value' ] = get_option( $args[ 'option' ] , '');
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
		$defaults = array(
			'meta' => 'coco',
		);

		/**
 		* Parse incoming $args into an array and merge it with $defaults
 		*/ 
		$ultra_args['args'] = wp_parse_args( $ultra_args['args'], $defaults );
		$args =  $ultra_args['args'];

		// code here
		$old_val = get_option($args['option'], 'ASDGEYJNKGHDRTEYRTSFSDGFGJGKJYUIUYLHJJFDGFSGFJGJFHD');

		$res = update_option( $args['option'], $args['value']);

	if ( $res or ($old_val == $args['value']) ) {		
		$args_success = array(
				'value' => $args['value'],
		);

		$ultra_args['args'] = $args_success;
		Ultrapress_Class::do_action($ultra_args, '/success');
	} else {		
		$args_fail = array();

		$ultra_args['args'] = $args_fail;
		Ultrapress_Class::do_action($ultra_args, '/fail');
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


ultra_Update_Option::instance();