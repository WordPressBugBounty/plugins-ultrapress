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
if ( ! class_exists( 'ultra_Add_Comment' ) ) {
class ultra_Add_Comment {
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
	public static $trigger = 'ultra/add_comment';
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
	public static $name = 'ultra-add-comment';
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
	public static $description = 'add comment to post';
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
		'user_id' => array(
			'primal' => 1,
			'type_of_variable' => 0,
			'required' => 1,
			'description' => '(int) ID of the user who submitted the comment.',
			'name' => 'comment author',
		),
		'comment_post_ID' => array(
			'primal' => 1,
			'type_of_variable' => 0,
			'required' => 1,
			'description' => '(int) ID of the post that relates to the comment.',
			'name' => 'ID of the post',
		),
		'comment_content' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 1,
			'description' => '(string) The content of the comment.',
			'name' => 'content of comment',
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
			'description' => 'comment added successifully',
			'name' => 'success',
			'args' => array(
				'comment_id' => array(
					'type_of_variable' => '',
					'description' => '(int) The new comments ID',
					'name' => 'id of comment',
				),
				'user_id' => array(
					'type_of_variable' => '',
					'description' => '(int) ID of the user who submitted the comment',
					'name' => 'user ID',
				),
				'comment_post_ID' => array(
					'type_of_variable' => '',
					'description' => '(int) ID of the post that relates to the comment',
					'name' => 'comment post ID',
				),
			),
		),
		'/fail' => array(
			'description' => 'ERROR: comment not added',
			'args' => array(
				'user_id' => array(
					'type_of_variable' => '',
					'description' => '(int) ID of the user who try to submit the comment',
					'name' => 'user ID',
				),
				'comment_post_ID' => array(
					'type_of_variable' => '',
					'description' => '(int) ID of the post',
					'name' => 'post ID',
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
		$icon_url = 'img/comment-edit-icon.png';

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
			'path_of_component_dir' =>  plugin_dir_path( __FILE__ ), 
			'url_of_component_dir' => plugin_dir_url( __FILE__ ),
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
		$defaults = array();

		/**
 		* Parse incoming $args into an array and merge it with $defaults
 		*/ 
		$ultra_args['args'] = wp_parse_args( $ultra_args['args'], $defaults );
		$args =  $ultra_args['args'];

		$user_data = get_userdata($args['user_id']);

		$comment_data = array(
		    'comment_post_ID' => $args['comment_post_ID'],
		    'comment_author' => esc_html ($user_data->user_nicename),
		    'comment_author_email' => $user_data->user_email,
		    'comment_content' => $args['comment_content'],
		    'user_id' => intval($args['user_id']),
		    'comment_approved' => 1,
		);

		$comment_id = wp_insert_comment($comment_data);

		if ( empty( $comment_id->errors ) ) {		
			$args_success = array(
				'comment_id' => $comment_id,
				'user_id' => $args['user_id'],
				'comment_post_ID' => $args['comment_post_ID'],
			);

			$ultra_args['args'] = $args_success;
			Ultrapress_Class::do_action($ultra_args, '/success');
		} else {		
			$args_fail = array(
				'user_id' => $args['user_id'],
				'comment_post_ID' => $args['comment_post_ID'],
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

ultra_Add_Comment::instance();