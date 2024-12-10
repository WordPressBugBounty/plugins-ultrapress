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


if ( ! class_exists( 'ultra_Send_Email' ) ) {
class ultra_Send_Email {
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
	public static $trigger = 'ultra/send_email'; 
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
	public static $name = 'ultra-send-email'; 
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
	public static $description = 'send email'; 
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
		'sender_name' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => '(string) name of the email author. only work if <from> is set. defaut is: Wordpress',   // description of arg
		),
		'from' => array(
			'primal' => 1,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => '(string) email addresses OR ID of user who want to send the email. default is: WordPress default sender',   // description of arg
		),
		'to' => array(
			'primal' => 1,
			'type_of_variable' => 0,
			'required' => 1,
			'description' => '(string) email addresses OR ID of user to send the email to.',   // description of arg
		),
		'subject' => array(
			'primal' => 1,
			'type_of_variable' => 0,
			'required' => 1,
			'description' => '(string) Email subject',   // description of arg
		),
		'message' => array(
				'primal' => 1,
				'type_of_variable' => 0,
				'required' => 1,
				'description' => '(string) Message contents',   // description of arg
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
	public static $additional_input = array( );
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
			'description' => 'email sent successfully', //  description  of output
			'name' => 'success', //  name  of output
			'args' => array(),
		),
		'/fail' => array(
			'description' => 'ERROR: email not sent',
			'name' => 'fail', //  name  of output
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
		// put the url of component icon here
		$icon_url =  'img/Email-icon.png';

		// put the url of outputs icon here
		$success =  'img/success-icon.png';
	    $fail = 'img/fail-icon.png';

	    self::$outputs[ '/success' ][ 'output_icon_url' ] = $success;
	    self::$outputs[ '/fail' ][ 'output_icon_url' ] = $fail;

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

		if (is_numeric( $args['to'] )) {
			$user = get_user_by('id', $args['to']);
			if ( ! empty( $user ) ) {
				$to = $user->user_email;
			} else {
				$args_fail = array();
				$ultra_args['args'] = $args_fail;

				// trigger second_output_example
				Ultrapress_Class::do_action($ultra_args, '/fail');
				return false;
			}
			
		} else {
			$to      = sanitize_email($args['to']);
		}

		$from = '';	
		if (array_key_exists('from', $args)) {
			if (is_numeric( $args['from'] )) {
				$user = get_user_by('id', $args['from']);
				if ($user) {
					$from = $user->user_email;
				}	
			} else {
				$from = sanitize_email($args['from']);
			}			
		}

		if (array_key_exists('sender_name', $args)) {
			$sender_name = sanitize_text_field($args['sender_name']);
		} else {
			$sender_name =  'Wordpress';
		}
		
		$headers = array('Content-Type: text/html; charset=UTF-8');
		if ($from) {
			$headers[] = "From: $sender_name <$from>";
		} 
		
		$subject = sanitize_text_field($args['subject']);
		$message = sanitize_text_field($args['message']);
		
		$condition = wp_mail( $to, $subject, $message, $headers );

		if ( $condition ) {		
			$args_success = array(
			);

			$ultra_args['args'] = $args_success;

			// trigger first_output_example
			Ultrapress_Class::do_action($ultra_args, '/success');
		} else {	
			// ex: if error
			$args_fail = array();

			$ultra_args['args'] = $args_fail;

			// trigger second_output_example
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


ultra_Send_Email::instance();