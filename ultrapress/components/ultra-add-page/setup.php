<?php
/**
 * add-page component for ultrapress
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
if ( ! class_exists( 'ultra_Add_Page' ) ) {
class ultra_Add_Page {
	/**
	 * Instance.
	 *
	 * Holds the plugin instance.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var instance
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
	public static $trigger = 'ultra/add_page';
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
	public static $name = 'ultra-add-page';
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
	 * the description of circuit
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var description
	 */
	public static $description = 'add new page';
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
		'page_title' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 1,
			'description' => '(string) The page title. Default empty.',
		),
		'page_author' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => '(int) The ID of the user who added the page. Default is the current user ID.',
		),
		'page_template' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => 'template of page. Default is "page"',
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
	public static $additional_input = array();
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
			'description' => 'page added successifully',
			'name' => 'success',
			'args' => array(
				'page_id' => array(
					'type_of_variable' => '',
					'description' => 'ID of created page',
					'name' => 'id of page',
				),
				'page_author' => array(
					'type_of_variable' => '',
					'description' => 'ID of the user who added the page',
					'name' => 'id of user',
				),
			),
		),
		'/fail' => array(
			'description' => 'error: page not added',
			'args' => array(
				'error' => array(
					'type_of_variable' => '',
					'description' => '(string) error',
					'name' => 'error',
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
		$path_of_component_dir = plugin_dir_path( __FILE__ );
		$url_of_component_dir = plugin_dir_url( __FILE__ );
		$icon_url =  'img/add_page.png';
		$success_icon_url =  'img/success.png';
	    $fail_icon_url ='img/report.png';
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
			'path_of_component_dir' => $path_of_component_dir, 
			'url_of_component_dir' => $url_of_component_dir,
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
		return $args;
	}
	/**
	 * run the component
	 *
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
	      'page_author'   => get_current_user_id(),
			);

		/**
 		* Parse incoming $args into an array and merge it with $defaults
 		*/ 
		$ultra_args['args'] = wp_parse_args( $ultra_args['args'], $defaults );
		$args =  $ultra_args['args'];
		
		$my_post = array(
	      'post_title'    => wp_strip_all_tags( $args['page_title'] ),
	      'post_author'   => $args['page_author'],
	      'post_content'  => '',
	      'post_status'   => 'publish',
	      'post_type'     => 'page',
	    );

	    if (array_key_exists('page_template', $args) && $args['page_template']) {
	    	$my_post['page_template'] = $args['page_template'];
	    }
	   
	    $post_id = wp_insert_post( $my_post );	    

	    if(!is_wp_error($post_id)){
	    	$args_success = array(
				'page_id' => $post_id,
				'page_author' => $args['page_author'],
		);

		$ultra_args['args'] = $args_success;
		Ultrapress_Class::do_action($ultra_args, '/success');
		} else{
		  //there was an error in the post insertion, 
		  $args_fail = array(
				'error' => $post_id->get_error_message(),
		);
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


ultra_Add_Page::instance();
