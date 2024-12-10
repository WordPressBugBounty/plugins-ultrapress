<?php
/**
 * ultra-add-page component for ultrapress
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
if ( ! class_exists( 'ultra_Register_Post_Type' ) ) {
class ultra_Register_Post_Type {
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
	public static $trigger = 'ultra/register_post_type';
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
	public static $name = 'ultra-register-post-type';
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
	public static $description = 'register new post type. intended to be used with init hook';
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
		'label' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 1,
			'description' => '(string) Name of the post type shown in the menu.',
			'name' => 'label',
		),
		'plural_label' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => '(string) plural name of the post type shown in the menu. Default: label + s',
			'name' => 'plural label',
		),
		'description' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => '(string) A short descriptive summary of what the post type is. Default: empty string',
			'name' => 'description',
		),
		'public' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => '(bool) Whether a post type is intended for use publicly either via the admin interface or by front-end users. Default 0',
			'name' => 'public',
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
			'description' => 'post type added successifully',
			'name' => 'success',
			'args' => array(),
		),
		'/fail' => array(
			'description' => 'post type not added',
			'name' => 'success',
			'args' => array(),
		),
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
		$icon_url = 'img/Other-Japanese-Post-icon.png';
		$success_icon_url =  'img/success-icon.png';
		$fail_icon_url =  'img/Button-Delete-icon.png';

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
			'description' => '', 
			'public' => 1, 
		);

		/**
 		* Parse incoming $args into an array and merge it with $defaults
 		*/ 
		$ultra_args['args'] = wp_parse_args( $ultra_args['args'], $defaults );
		$args =  $ultra_args['args'];

		// code here
		$public = $args['public'];
		if ( 1 == $public ) {
			$public = true;
		}

		if (!array_key_exists('label', $args)) {
			$args_fail = array();
			$ultra_args['args'] = $args_fail;

			Ultrapress_Class::do_action($ultra_args, '/fail');
			return false;
		} 
		$label = $args['label'];

		if (!array_key_exists('plural_label', $args)) {
			$args['plural_label'] = $args['label'] . 's';
		} 
		$plural_label = $args['plural_label'];
		
		$post_type_labels = array(
			'name' 					=> $plural_label, 
			'singular_name'			=> $label, 
			'add_new_item'			=> "Add New $label",
			'edit_item'				=> "Edit $label",
			'new_item'				=> "New $label",
			'view_item'				=> "View $label",
			'view_items'			=> "View $plural_label",
			'not_found'				=> "No $plural_label found",
			'not_found_in_trash'	=> "No $plural_label found in Thrash",
			'all_items'				=> "All $plural_label",
			'archives'				=> "$label Archives",
			'insert_into_item'		=> "Insert into $label",
			'uploaded_to_this_item'	=> "Uploaded to this $label",
			);		
		$capabilities = array(
			"read_post"					=> "read_$label",
			"read_private_posts" 		=> "read_private_$plural_label",
			"edit_post"					=> "edit_$label",
			"edit_posts"				=> "edit_$plural_label",
			"edit_others_posts"			=> "edit_others_$plural_label",
			"edit_published_posts"		=> "edit_published_$plural_label",
			"edit_private_posts"		=> "edit_private_$plural_label",
			"delete_post"				=> "delete_$label",
			"delete_posts"				=> "delete_$plural_label",
			"delete_others_posts"		=> "delete_others_$plural_label",
			"delete_published_posts"	=> "delete_published_$plural_label",
			"delete_private_posts"		=> "delete_private_$plural_label",
			"publish_posts"				=> "publish_$plural_label",
			"moderate_comments"			=> "moderate_{$label}_comments",
			); 

			$arr = array(
				'labels' => $post_type_labels,
				'description'  => $args['description'],
		        'public' => $public,
		        'label'  => $label,
		        'menu_position' => null,
		        'menu_icon' => 'dashicons-media-document',
		        'capabilities' => $capabilities,
		        'map_meta_cap' => true,
				'hierarchical' => false,
				'supports' => array( 'title', 'editor', 'author', 'excerpt', 'custom-fields', 'comments' ),
				'taxonomies' => array( 'category', 'post_tag' ),
				'has_archive' => true,
				'show_in_rest' => true,
				'rest_base'          =>  "$plural_label",
		    );

		$reg = register_post_type( $label, $arr );

		$admin = get_role( 'administrator' );
		$admin->add_cap( "read_$label" );
		$admin->add_cap( "read_private_$label" );
		$admin->add_cap( "edit_$label" );
		$admin->add_cap( "edit_$plural_label" );
		$admin->add_cap( "edit_others_$plural_label" );
		$admin->add_cap( "edit_published_$plural_label" );
		$admin->add_cap( "edit_private_$plural_label" );
		$admin->add_cap( "delete_$plural_label" );
		$admin->add_cap( "delete_$plural_label" );
		$admin->add_cap( "delete_others_$plural_label" );
		$admin->add_cap( "delete_published_$label" );
		$admin->add_cap( "delete_$label" );
		$admin->add_cap( "delete_private_$label" );
		$admin->add_cap( "publish_$plural_label" );
		$admin->add_cap( "moderate_{$label}_comments" );
		flush_rewrite_rules();

		if ( $reg ) {
			$args_success = array();
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


ultra_Register_Post_Type::instance();