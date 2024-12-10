<?php
/**
 * ultra-node component for ultrapress
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
if ( ! class_exists( 'ultra_If' ) ) {
class ultra_If {
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
	public static $trigger = 'ultra/if';
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
	public static $name = 'ultra-if';
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
	public static $description = 'check a condition, if true trigger <true> output, if not trigger <false> output.';

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
		'operator' => array(
			'primal' => 1,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => '(string) name of the operator. and: logical and or: logical OR | ! |  only work if <from> is set. defaut is: Wordpress notification',   // description of arg
			'select' => 1, // change it to '1' if you want the user to choose from limited choices 
			'select_values' => array(
				'AND' => '(first operand) AND (second operand)',
				'OR' => '(first operand) OR (second operand)',
				'negation' => 'negation of the logical value of first operand',
				'equals' => 'first operand equals second operand',
				'bigger than' => 'first operand bigger than second operand',
				'bigger than or equals' => 'first operand bigger or equals  second operand',
				'smaller than' => 'first operand smaller than second operand',
				'smaller than or equals' => 'first operand smaller or equals  second operand',
				'different' => 'first operand different from second operand',
				'key_in_array' => 'first operand is a key in second operand (array)',
				'value_in_array' => 'first operand is a value in second operand (array)',
				'isTrue' => 'the logical value of first operand is true',
			), // if 'select' == 1, this assosiative array will contain the values that the user should choose from.
		),
		'first_operand' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 1,
			'description' => 'the first operand of the operator',   // description of arg
		),
		'second_operand' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => 'the second operand of the operator (required if operator need 2 operands).',   // description of arg
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
		'/true' => array(
			'description' => 'the result is TRUE',
			'name' => 'true',
			'args' => array(),
		),
		'/false' => array(
			'description' => 'the result is FALSE',
			'name' => 'false',
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
		$icon_url = 'img/if.png';
		$out1_icon_url = 'img/Ok-icon.png';
	    $out2_icon_url = 'img/Check-icon.png';
	    self::$outputs[ '/true' ][ 'output_icon_url' ] = $out1_icon_url;
	    self::$outputs[ '/false' ][ 'output_icon_url' ] = $out2_icon_url;

		$info = array(
			'trigger' => self::$trigger,
			'name' => self::$name,
			'version' => self::$version,
			'description' => self::$description,
			'multiple_connection' => 0,
			'auto_fill_of_args' => 1, 
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
		$defaults = array();

		/**
 		* Parse incoming $args into an array and merge it with $defaults
 		*/ 
		$ultra_args['args'] = wp_parse_args( $ultra_args['args'], $defaults );
		$args =  $ultra_args['args'];

		if (array_key_exists('operator', $args)) {
			$operator =  $args['operator'];
		} else {
			$operator =  '';
		}

		if (array_key_exists('first_operand', $args)) {
			$first_operand =  $args['first_operand'];
		} else {
			$first_operand =  false;
		}

		if (array_key_exists('second_operand', $args)) {
			$second_operand =  $args['second_operand'];
		} else {
			$second_operand =  false;
		}
		update_option("oper", "noooooo");
		$res = false;

		switch ($operator) {
				    case "AND":
				    update_option("oper", "AND");
				  		$res =  $first_operand && $second_operand;
				        break;
				    case "OR":
					    $res = $first_operand || $second_operand;
				        break;
				    case "negation": 
				    	 $res = (! $first_operand);
				        break;
				    case "equals":
					    $res = ($first_operand == $second_operand);
				        break;
				    case "bigger than":
					    $res = ($first_operand > $second_operand);
				        break;
				    case "bigger than or equals":
					    $res = ($first_operand >= $second_operand);
				        break;
				    case "smaller than":
				    	update_option("oper", "<");
					    $res = ($first_operand < $second_operand);
				        break;
				    case "smaller than or equals":
					    $res = ($first_operand <= $second_operand);
				        break;
				    case "different":
					    $res = ($first_operand != $second_operand);
				        break;
				    case "key_in_array":
					    $res = array_key_exists( $first_operand, $second_operand );
				        break;
				    case "value_in_array":
					    $res = in_array( $first_operand, $second_operand );
				        break;
				    case "isTrue":
					    $res = $first_operand;
				        break;
				}

		update_option("dgdgdf", array($operator,$first_operand, $second_operand,  $res));

		if ($res) {
			// trigger first_output_example
			Ultrapress_Class::do_action($ultra_args, '/true');
		} else {
			Ultrapress_Class::do_action($ultra_args, '/false');
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


ultra_If::instance();