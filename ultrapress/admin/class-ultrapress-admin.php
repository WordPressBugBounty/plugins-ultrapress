<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Ultrapress
 * @subpackage Ultrapress/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ultrapress
 * @subpackage Ultrapress/admin
 * @author     meedawi <meedawi.med@gmail.com>
 */
class Ultrapress_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $ultrapress    The ID of this plugin.
	 */
	private $ultrapress;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	/**
	 * The hooks of plugin admin pages
	 *
	 * @since    1.0.0
	 * @access   private
	 */

	private $hook;
	private $hook_of_circuits_page;
	private $hook_of_components_page;
	private $hook_of_packages_page;
	private $hook_of_add_new_page;
	private $hook_of_dashboard_page;
	private $hook_of_documentation_page;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $visualpress       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $ultrapress = 'ultrapress', $version = '1.0.0') {
		$this->ultrapress = $ultrapress;
		$this->version = $version;

		add_action( 'admin_menu', [ $this, 'ultrapress_admin_menu' ], 0 );
		add_action( 'admin_enqueue_scripts' , [ $this, 'enqueue_scripts'] );
		add_action( 'admin_enqueue_scripts' , [ $this, 'enqueue_debug_assets'] );

		add_action( 'init', [ $this, 'plugins_loaded_action' ], 0 );

		// ajax callbacks
		add_action( 'wp_ajax_nopriv_run_circuit_ultrapress', array( $this, 'run_circuit_ultrapress' ) );
   		add_action( 'wp_ajax_run_circuit_ultrapress', array( $this, 'run_circuit_ultrapress' ) );

		add_action( 'wp_ajax_nopriv_fetch_ultrapress', array( $this, 'fetch_ultrapress' ) );
   		add_action( 'wp_ajax_fetch_ultrapress', array( $this, 'fetch_ultrapress' ) );

   		add_action( 'wp_ajax_nopriv_install_circuit_component', array( $this, 'install_circuit_component' ) );
   		add_action( 'wp_ajax_install_circuit_component', array( $this, 'install_circuit_component' ) );

		
   		add_action( 'wp_ajax_nopriv_save_ultrapress', array( $this, 'save_ultrapress' ) );
   		add_action( 'wp_ajax_save_ultrapress', array( $this, 'save_ultrapress' ) );

   		add_action( 'wp_ajax_nopriv_delete_composed_component_ultrapress', array( $this, 'delete_composed_component' ) );
   		add_action( 'wp_ajax_delete_composed_component_ultrapress', array( $this, 'delete_composed_component' ) );

   		add_action( 'wp_ajax_nopriv_deactivate_circuit_ultrapress', array( $this, 'deactivate_circuit' ) );
   		add_action( 'wp_ajax_deactivate_circuit_ultrapress', array( $this, 'deactivate_circuit' ) );

   		add_action( 'wp_ajax_nopriv_activate_circuit_ultrapress', array( $this, 'activate_circuit' ) );
   		add_action( 'wp_ajax_activate_circuit_ultrapress', array( $this, 'activate_circuit' ) );   		

   		add_action( 'wp_ajax_nopriv_activate_package_ultrapress', array( $this, 'activate_package' ) );
   		add_action( 'wp_ajax_activate_package_ultrapress', array( $this, 'activate_package' ) );

   		add_action( 'wp_ajax_nopriv_deactivate_package_ultrapress', array( $this, 'deactivate_package' ) );
   		add_action( 'wp_ajax_deactivate_package_ultrapress', array( $this, 'deactivate_package' ) );

   		add_action( 'wp_ajax_nopriv_delete_circuit_ultrapress', array( $this, 'delete_circuit_ultrapress' ) );
   		add_action( 'wp_ajax_delete_circuit_ultrapress', array( $this, 'delete_circuit_ultrapress' ) );

   		add_action( 'wp_ajax_nopriv_delete_package_ultrapress', array( $this, 'delete_package_ultrapress' ) );
   		add_action( 'wp_ajax_delete_package_ultrapress', array( $this, 'delete_package_ultrapress' ) );
   		
   		add_action( 'wp_ajax_nopriv_delete_component_ultrapress', array( $this, 'delete_component_ultrapress' ) );
   		add_action( 'wp_ajax_delete_component_ultrapress', array( $this, 'delete_component_ultrapress' ) );

   		add_action( 'wp_ajax_nopriv_create_plugin_ultrapress', array( $this, 'create_plugin_ultrapress' ) );
   		add_action( 'wp_ajax_create_plugin_ultrapress', array( $this, 'create_plugin_ultrapress' ) );

   		add_action( 'wp_ajax_nopriv_create_package_ultrapress', array( $this, 'create_package_ultrapress' ) );
   		add_action( 'wp_ajax_create_package_ultrapress', array( $this, 'create_package_ultrapress' ) );

   		add_action( 'wp_ajax_nopriv_clone_package_ultrapress', array( $this, 'clone_package_ultrapress' ) );
   		add_action( 'wp_ajax_clone_package_ultrapress', array( $this, 'clone_package_ultrapress' ) );

   		add_action( 'wp_ajax_nopriv_add_circuit_to_package_ultrapress', array( $this, 'add_circuit_to_package' ) );
   		add_action( 'wp_ajax_add_circuit_to_package_ultrapress', array( $this, 'add_circuit_to_package' ) );

   		add_action( 'wp_ajax_nopriv_export_composed_comp_ultrapress', array( $this, 'export_composed_comp_ultrapress' ) );
   		add_action( 'wp_ajax_export_composed_comp_ultrapress', array( $this, 'export_composed_comp_ultrapress' ) );

   		add_action( 'wp_ajax_nopriv_ultrapress_upload_package', array( $this, 'ultrapress_upload_package' ) );
   		add_action( 'wp_ajax_ultrapress_upload_package', array( $this, 'ultrapress_upload_package' ) );

   		add_action( 'wp_ajax_ultrapress_log_error', array( $this, 'handle_js_error_logging' ) );
	}
	/** 
	 * process activated circuits
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function plugins_loaded_action () {
		// acivated circuits
		Ultrapress_Class::instance()->run_activate();
	}
	/** 
	 * add menu pages
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function ultrapress_admin_menu () {
		$this->hook_of_dashboard_page = add_menu_page(
			__( 'Ultrapress', 'ultrapress' ),
			__( 'Ultrapress', 'ultrapress' ),
			'manage_options',
			'ultrapress-dashboard',
			array( $this, 'ultrapress_admin_dashboard_page' ),
			'dashicons-admin-generic'
		);

		$this->hook = add_submenu_page(
			'ultrapress-dashboard',
			__( 'Visual Editor', 'ultrapress' ),
			__( 'Visual Editor', 'ultrapress' ),
			'manage_options',
			'ultrapress',
			array( $this, 'render' )
		);

		$this->hook_of_documentation_page = add_submenu_page(
			'ultrapress-dashboard',
			__( 'Documentation', 'ultrapress' ),
			__( 'Documentation', 'ultrapress' ),
			'manage_options',
			'ultrapress-documentation',
			array( $this, 'ultrapress_admin_documentation_page' )
		);

		$this->hook_of_circuits_page = add_submenu_page( 'ultrapress-dashboard', __('Circuits', 'ultrapress'), __('Circuits', 'ultrapress'), 'manage_options', 'ultrapress/circuits.php', [ $this, 'ultrapress_admin_circuits_page' ] ); 

		$this->hook_of_components_page = add_submenu_page( 'ultrapress-dashboard', __('Components', 'ultrapress'), __('Components', 'ultrapress'), 'manage_options', 'ultrapress/components.php', [ $this, 'ultrapress_admin_components_page' ] ); 

		$this->hook_of_packages_page = add_submenu_page( 'ultrapress-dashboard', __('Packages', 'ultrapress'), __('Packages', 'ultrapress'), 'manage_options', 'ultrapress/packages.php', [ $this, 'ultrapress_admin_packages_page' ] ); 

		$this->hook_of_add_new_page = add_submenu_page( 'ultrapress-dashboard', __('Add New', 'ultrapress'), __('Add New', 'ultrapress'), 'manage_options', 'ultrapress/add.php', [ $this, 'ultrapress_admin_add_new_page' ] );
	}

	/** 
	 * render ultrapress menu page
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render () {
		include_once( plugin_dir_path( __FILE__ ) . 'partials/ultrapress-admin-display.php' );
	}

	/** 
	 * render circuits menu page
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function ultrapress_admin_circuits_page () {
		include_once( plugin_dir_path( __FILE__ ) . 'partials/ultrapress-admin-display-circuits.php' );
	}

	/** 
	 * render components menu page
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function ultrapress_admin_components_page () {
		include_once( plugin_dir_path( __FILE__ ) . 'partials/ultrapress-admin-display-components.php' );
	}

	/** 
	 * render packages menu page
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function ultrapress_admin_packages_page () {
		include_once( plugin_dir_path( __FILE__ ) . 'partials/ultrapress-admin-display-packages.php' );
	}

	public function ultrapress_admin_add_new_page () {
		include_once( plugin_dir_path( __FILE__ ) . 'partials/ultrapress-admin-display-add-new.php' );
	} 

	public function ultrapress_admin_dashboard_page () {
		include_once( plugin_dir_path( __FILE__ ) . 'partials/ultrapress-admin-display-dashboard.php' );
	} 

	public function ultrapress_admin_documentation_page () {
		include_once( plugin_dir_path( __FILE__ ) . 'partials/ultrapress-admin-documentation.php' );
	}
	/** 
	 * ajax callback: run circuit
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function run_circuit_ultrapress() {
		$response = array();
	    $trigger_of_circuit = sanitize_text_field( $_POST["trigger_of_circuit"] );	 
	    $args = $_POST["args"]; 	   
	    $args = array_map( 'sanitize_text_field', $args );

	    $array_of_circuits = get_option('ultrapress');
	        
	    // check the nonce
	    if ( check_ajax_referer( 'ultrapress_run', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }	    

	    if (!array_key_exists($trigger_of_circuit, $array_of_circuits)) {
	    	$response["response"] = 'F';
	    	$response["circuit_exist"] = false;
	    	$response["error_message"] = __('there is no circuit with this name', 'ultrapress');
	    	echo json_encode( $response );
			die();
	    }

	    $circuit = $array_of_circuits[ $trigger_of_circuit ];

	    do_action($trigger_of_circuit , $args);

    	$response["response"] = 'T';
    	$response["circuit_exist"] = true;
    	$response["ajaxable"] = true;
    	echo json_encode( $response );
		die();  
	}

	/** 
	 * ajax callback: fetch data
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function fetch_ultrapress() {	   
	    $response = array();
	    $response["components"] = Ultrapress_Class::$components;
	    $response["triggers"] = Ultrapress_Class::$triggers;
	    $response["packages"] = Ultrapress_Class::$packages;
	    $array_of_circuits  = get_option('ultrapress', array());
	    $response["circuits"] = $array_of_circuits;

	    // first circuit key
	    if ($array_of_circuits) {
	    	foreach ($array_of_circuits as $key => $value) {
	    		$response["key_of_first_circuit"] = $key;
	   	 		break;
	    	}
	    } else {
	    	$response["key_of_first_circuit"] = '';
	    }

	    echo json_encode( $response );
		die();
	}

	/** 
	/** 
	 * ajax callback: install circuits & components
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function install_circuit_component() {
		$response = array();
	    
	    $link = sanitize_url( $_POST["link"] );
	    /*
	    // check the nonce
	    if ( check_ajax_referer( 'nonce_install_circuit_component', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }	
	    */    

	    $this->copy_unzip_delete($link,
							     ULTRAPRESS_PATH . 'packages/tmp.zip',
							     ULTRAPRESS_PATH . 'packages/');

    	$response["response"] = 'T';
    	echo json_encode( $response );
		die();  
	}

	/** 
	 * get installed packages
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function get_installed_packages() {	    
		$array_of_packages = array();

	 	$dirs_of_packages = glob( ULTRAPRESS_PATH . 'packages/*', GLOB_ONLYDIR);
	 	foreach($dirs_of_packages as $dir_of_package)
		{
			$basename = basename($dir_of_package);
		    $array_of_packages[] = $basename;
		}

		return $array_of_packages;
	}

	/** 
	 * ajax callback: save data
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function save_ultrapress() {	
		$response = array();
	    // check the nonce
	    if ( check_ajax_referer( 'ultrapress_save', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }	

	    $array_of_circuits  = get_option( 'ultrapress', array() );
	    $circuit_key  = sanitize_text_field( $_POST["circuit_key"] );

	    // sanitize multidimensional array
	    $circuit = $this->recursive_sanitize_text_field( $_POST["circuit"] );

	    $array_of_circuits[ $circuit_key ] = $circuit;
	    Ultrapress_Class::$circuits[ $circuit_key ] = $circuit;

	    update_option('ultrapress', $array_of_circuits);

	    $res = 'not packaged';
	    if ( array_key_exists('package', $circuit) ) {
			$package_name = $circuit[ 'package' ];
			$res = $this->update_package($package_name);
		} 
		$response["res"] = $res;
	    $response["response"] = 'T';
	    echo json_encode( $response );
		die();
	}

	/** 
	 * ajax callback: export composed component
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function export_composed_comp_ultrapress() {
		$response = array();
	    // check the nonce
	    if ( check_ajax_referer( 'ultrapress_export_composed_component', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }

	    $circuit_key  = sanitize_text_field( $_POST["circuit_key"] );
	    $trigger  = sanitize_text_field( $_POST["trigger"] );
	    $name  = sanitize_text_field( $_POST["name"] );
	    $disc  = sanitize_text_field( $_POST["disc"] );

	    $array_of_circuits  = Ultrapress_Class::$circuits;
		if (!array_key_exists( $circuit_key, $array_of_circuits) ) {
			$response["response"] = 'F';
			$response["message"] = 'please save circuit before converted to a composed component';
			echo json_encode( $response );
			die();
		}

	    $is_saved = Ultrapress_Class::save_composed_component(
	    	$circuit_key,
	    	$trigger,
	    	$name,
	    	$disc
	    );

	    if ($is_saved) {
	    	$response["response"] = 'T';
	    } else {
	    	$response["response"] = 'F';
	    	$response["message"] = 'error: composed component not created';
	    }
	    
	    echo json_encode( $response );
		die();
	}
	/** 
	 * ajax callback: delete composed component
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function delete_composed_component() {
	    // check the nonce
	    if ( check_ajax_referer( 'ultrapress_delete_composed_component', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }

	    $key_of_comp = sanitize_text_field( $_POST["key_of_comp"]);

	    $composed_components = Ultrapress_Class::delete_composed_component( $key_of_comp );

	    $response["response"] = 'T';
	    echo json_encode( $response );
		die();
	}
	/** 
	 * ajax callback: deactivate circuit
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function deactivate_circuit() {
	    // check the nonce
	    if ( check_ajax_referer( 'ultrapress_deactivate_circuit', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }

	    $key_of_circ = sanitize_text_field( $_POST["circuit_key"]);

	    Ultrapress_Class::deactivate_circuit( $key_of_circ );

	    $response["response"] = 'T';
	    echo json_encode( $response );
		die();
	}
	/** 
	 * ajax callback: activate circuit
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function activate_circuit() {
	    // check the nonce
	    if ( check_ajax_referer( 'ultrapress_activate_circuit', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }

	    $key_of_circ = sanitize_text_field( $_POST["circuit_key"]);
	    $circuit_path =  sanitize_text_field( $_POST["circuit_path"] );

	    Ultrapress_Class::activate_circuit( $key_of_circ, $circuit_path);

	    $response["response"] = 'T';
	    echo json_encode( $response );
		die();
	}
	/** 
	 * ajax callback: activate package
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function activate_package() {
	    // check the nonce
	    if ( check_ajax_referer( 'ultrapress_activate_package', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }

	    $name_of_package = sanitize_text_field( $_POST["name_of_package"]);
	    $path_of_package =  sanitize_text_field( $_POST["path_of_package"] );

	    Ultrapress_Class::activate_package( $name_of_package, $path_of_package);

	    $response["response"] = 'T';
	    echo json_encode( $response );
		die();
	}
	/** 
	 * ajax callback: deactivate package
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function deactivate_package() {
	    // check the nonce
	    if ( check_ajax_referer( 'ultrapress_deactivate_package', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }

	    $name_of_package = sanitize_text_field( $_POST["name_of_package"]);

	    Ultrapress_Class::deactivate_package( $name_of_package );

	    $response["response"] = 'T';
	    echo json_encode( $response );
		die();
	}
	/** 
	 * ajax callback: delete component
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function delete_component_ultrapress() {
		$response = array();

	    // check the nonce
	    if ( check_ajax_referer( 'ultrapress_delete_component', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }

	    $components = Ultrapress_Class::$components;
	    $key_of_comp = sanitize_text_field( $_POST["key_of_comp"]);
	    $path_of_component_dir = $components[ $key_of_comp ][ 'path_of_component_dir' ];

	    $this->delete_dir_recursively($path_of_component_dir);

	    $response["response"] = 'T';
	    echo json_encode( $response );
		die();
	}
	/** 
	 * ajax callback: delete circuit
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function delete_circuit_ultrapress() {
		$response = array();

	    // check the nonce
	    if ( check_ajax_referer( 'ultrapress_delete_circuit', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }

	    $key_of_circ = sanitize_text_field( $_POST["circuit_key"]);
	    $circuit_path =  sanitize_text_field( $_POST["circuit_path"] );


	    $this->delete_dir_recursively($circuit_path);

	    $response["response"] = 'T';
	    echo json_encode( $response );
		die();
	}
	/** 
	 * ajax callback: delete package
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function delete_package_ultrapress() {
		$response = array();

	    // check the nonce
	    if ( check_ajax_referer( 'ultrapress_delete_package', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }

	    $name_of_package = sanitize_text_field( $_POST["name_of_package"]);
	    
	    $path_of_package =  sanitize_text_field( $_POST["path_of_package"] );
	    $path_of_package = WP_PLUGIN_DIR . $path_of_package;

	    $this->delete_dir_recursively($path_of_package);

	    $response["response"] = 'T';
	    echo json_encode( $response );
		die();
	}
	/** 
	 * ajax callback:  add circuit to package
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function add_circuit_to_package() {
		$response = array();
	    // check the nonce
	    if ( check_ajax_referer( 'ultrapress_add_circuit_to_package', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }

	    $array_of_circuits = get_option('ultrapress', array() );
	    $components = Ultrapress_Class::$components;

	    $circuit_key =  $_POST["circuit_key"];
	    $circuit_key =  sanitize_text_field( $circuit_key );
	    
	    $new_name_of_package_for_circuit =  $_POST["new_name_of_package_for_circuit"];
	    $new_name_of_package_for_circuit =  sanitize_text_field( $new_name_of_package_for_circuit );

	    $circuit = $array_of_circuits[ $circuit_key ];

	    $new_circuit_key = $circuit_key;
	    $old_package_name = '';

	    if ( array_key_exists('package', $circuit) ) {
			$old_package_name = $circuit[ 'package' ] . '/';

			if (substr($circuit_key, 0, strlen($old_package_name)) == $old_package_name) {
			    $new_circuit_key = substr($circuit_key, strlen($old_package_name));
			} 
		} 
		$new_circuit_key = $new_name_of_package_for_circuit . '/' . $new_circuit_key;
		$updated_circuit = array_merge(array(), $circuit);
		$updated_circuit[ 'package' ] = $new_name_of_package_for_circuit;
		$updated_circuit[ 'trigger_of_circuit' ] = $new_circuit_key;

		Ultrapress_Class::$circuits[ $new_circuit_key  ] = $updated_circuit;
		unset(Ultrapress_Class::$circuits[$circuit_key]);

		$array_of_circuits[ $new_circuit_key ] = $updated_circuit;
		unset($array_of_circuits[$circuit_key]);
	    update_option('ultrapress', $array_of_circuits);

	    $this->update_package($new_name_of_package_for_circuit);

	    if ($old_package_name) {
	    	$old_package_name = $circuit[ 'package' ];
	    	$this->update_package($old_package_name);
	    }



	    $response["response"] = 'T';	
	    $response["new_name_of_package_for_circuit"] = $new_name_of_package_for_circuit;
	    $response["old_package_name"] = $old_package_name;
	    echo json_encode( $response );
		die();
	}
	/** 
	 * ajax callback:  clone package
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function clone_package_ultrapress() {
		$response = array();
	    // check the nonce
	    if ( check_ajax_referer( 'ultrapress_clone_package', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }

	    $array_of_circuits = get_option('ultrapress', array() );
	    $components = Ultrapress_Class::$components;

	    $package_to_be_cloned =  $_POST["package_to_be_cloned"];
	    $package_to_be_cloned =  sanitize_text_field( $package_to_be_cloned );
	    
	    $name_of_package =  $_POST["name_of_package"];
	    $name_of_package =  sanitize_text_field( $name_of_package );

	    $desc_of_package =  $_POST["desc_of_package"];
	    $desc_of_package =  sanitize_text_field( $desc_of_package );

	    if (!array_key_exists($package_to_be_cloned, Ultrapress_Class::$packages)) {
	    	$response["response"] = 'F';
	    	$response["error_message"] = __('there is no package with this name', 'ultrapress');
	    	echo json_encode( $response );
			die();
	    }

	    $package = Ultrapress_Class::$packages[ $package_to_be_cloned ];
	    $path_of_cloned_package = WP_PLUGIN_DIR . $package['path'];

	    if (file_exists(! $path_of_cloned_package   . '/data.json' )) {
			$response["response"] = 'F';
	    	$response["error_message"] =  __('there is no package called: ', 'ultrapress') . $package_to_be_cloned;
	        echo json_encode( $response );
	        die();
			}

		$path_of_package = ULTRAPRESS_PATH . 'packages' . DIRECTORY_SEPARATOR . $name_of_package;

		$this->recurse_copy($path_of_cloned_package,  $path_of_package);

		

	    $json_path = file_get_contents( $path_of_package . '/data.json');
		$json_array = json_decode($json_path, true);

		$json_array['name'] = $name_of_package;
		$json_array['disc'] = $desc_of_package;

		$circuits_of_package  = $json_array['circuits'];
		$updated_circuits_of_package  = array();

		foreach ($circuits_of_package as $circuit_key => $circuit) {
	 		$new_circuit_key = $circuit_key;
			$cloned_circuit_for_package = array_merge(array(), $circuit);

			if ( array_key_exists('package', $cloned_circuit_for_package) ) {
				$old_package_name = $cloned_circuit_for_package[ 'package' ];

				if (substr($circuit_key, 0, strlen($old_package_name) + 1 ) == $old_package_name . '/') {
				    $new_circuit_key = substr($circuit_key, strlen($old_package_name));
				} 
			} 

			$new_circuit_key = $name_of_package . $new_circuit_key;

	 		$cloned_circuit_for_package[ 'package' ] = $name_of_package;
	 		$cloned_circuit_for_package[ 'trigger_of_circuit' ] = $new_circuit_key;

			$array_of_circuits[ $new_circuit_key ] = $cloned_circuit_for_package;
			$updated_circuits_of_package[ $new_circuit_key ] = $cloned_circuit_for_package;
	    }
	    update_option('ultrapress', $array_of_circuits);

	    $json_array['circuits'] = $updated_circuits_of_package;

		 $json  = json_encode( $json_array );
		 $file = fopen( ULTRAPRESS_PATH . 'packages' . DIRECTORY_SEPARATOR . $name_of_package . DIRECTORY_SEPARATOR .'data.json','w');
         fwrite($file, $json);
         fclose($file);

         Ultrapress_Class::$packages [$name_of_package] = array(
				'name' => $name_of_package,
				'disc' => $desc_of_package,
				'path' => substr($path_of_package, strlen(WP_PLUGIN_DIR)),
			);
		update_option('ultrapress_packages', Ultrapress_Class::$packages);

	    $response["response"] = 'T';	
	    echo json_encode( $json_array );
		die();
	}

	/** 
	 * ajax callback:  create package
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function create_package_ultrapress() {
	    // check the nonce
	    if ( check_ajax_referer( 'ultrapress_create_package', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }

	    $array_of_circuits = get_option('ultrapress', array() );
	    $components = Ultrapress_Class::$components;

	    $components_of_circuits = array();
	    
	    $name_of_package =  $_POST["name_of_package"];
	    $name_of_package =  sanitize_text_field( $name_of_package );

	    $desc_of_package =  $_POST["desc_of_package"];
	    $desc_of_package =  sanitize_text_field( $desc_of_package );

	    $json_array = array();
	    $json_array[ 'name' ] = $name_of_package;
	    $json_array[ 'desc' ] = $desc_of_package;
	    $json_array[ 'circuits' ] = array();

		$components_of_package = array();
	 	$composed_components_of_package = array();
	 	$circuits_of_composed_components_of_package = array();

	    $json_array[ 'composed_components' ] = array_merge(array(), $composed_components_of_package);
	    $json_array[ 'circuits_of_composed_components' ] = array_merge(array(), $circuits_of_composed_components_of_package);

	     $this->recurse_copy(ULTRAPRESS_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'package',  ULTRAPRESS_PATH . 'packages' . DIRECTORY_SEPARATOR . $name_of_package);



		 $json  = json_encode( $json_array );
		 $file = fopen( ULTRAPRESS_PATH . 'packages' . DIRECTORY_SEPARATOR . $name_of_package . DIRECTORY_SEPARATOR .'data.json','w');
         fwrite($file, $json);
         fclose($file);


         $path_of_package = ULTRAPRESS_PATH . 'packages' . DIRECTORY_SEPARATOR . $name_of_package;
         Ultrapress_Class::$packages [$name_of_package] = array(
				'name' => $name_of_package,
				'disc' => $desc_of_package,
				'path' => substr($path_of_package, strlen(WP_PLUGIN_DIR)),
			);
		update_option('ultrapress_packages', Ultrapress_Class::$packages);

	    $response["response"] = 'T'; 	
	    echo json_encode( $response );
		die();
	}

	/** 
	 * ajax callback:  create plugin
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function create_plugin_ultrapress() {
	    // check the nonce
	    if ( check_ajax_referer( 'ultrapress_create_plugin', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }

	    $name_of_package =  $_POST["name_of_package_of_plugin"];
	    $name_of_package =  sanitize_text_field( $name_of_package );

	    if (array_key_exists($name_of_package, Ultrapress_Class::$packages)) {
	    	$package = Ultrapress_Class::$packages[ $name_of_package ];
	    } else {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('package not found', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }

	    $path_of_package  = $package[ 'path' ];
	    $abs_path_of_package  = WP_PLUGIN_DIR . $path_of_package;
	     
		$this->recurse_copy(ULTRAPRESS_TEMPLATES_PATH . '/plugin',  ULTRAPRESS_PATH . 'export' . DIRECTORY_SEPARATOR . $name_of_package);
		$path_of_package = ULTRAPRESS_PATH . 'packages' . DIRECTORY_SEPARATOR . $name_of_package;

		$this->recurse_copy($abs_path_of_package,  ULTRAPRESS_PATH . 'export' . DIRECTORY_SEPARATOR . $name_of_package . DIRECTORY_SEPARATOR . 'ultrapress/packages'  . DIRECTORY_SEPARATOR . $name_of_package );

	    $response["response"] = 'T';	
	    echo json_encode( $response );
		die();
	}

	/** 
	 * copy directory
	 *
	 * @since 1.0.0
	 * @access public
	 * @param string  $src : source
 	 * @param string  $dst : distination 
	 */
	public function recurse_copy ( $src, $dst) {
		if ( $src == $dst	) {
			return true;
		}
	    $dir = opendir($src); 
	    @mkdir($dst); 
	    while(false !== ( $file = readdir($dir)) ) { 
	        if (( $file != '.' ) && ( $file != '..' )) { 
	            if ( is_dir($src . '/' . $file) ) { 
	                $this->recurse_copy($src . '/' . $file,$dst . '/' . $file); 
	            } 
	            else { 
	                copy($src . '/' . $file,$dst . '/' . $file); 
	            } 
	        } 
	    } 
	    closedir($dir); 
	}

	/**
	 * Recursive sanitation for an array 
	 * 
	 * @param $array
	 *
	 * @return mixed
	 */
	public function recursive_sanitize_text_field($array) {
	    foreach ( $array as $key => &$value ) {
	        if ( is_array( $value ) ) {
	            $value = $this->recursive_sanitize_text_field($value);
	        }
	        else {
	            $value = sanitize_text_field( $value );
	        }
	    }

	    return $array;
	}

	/**
	 * delete dir Recursively 
	 * 
	 * @param string: $dir
	 */
	public function delete_dir_recursively($dir) {
	    if (is_dir($dir)) { 
	     $objects = scandir($dir);
	     foreach ($objects as $object) { 
	       if ($object != "." && $object != "..") { 
	         if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
	           $this->delete_dir_recursively($dir. DIRECTORY_SEPARATOR .$object);
	         else
	           unlink($dir. DIRECTORY_SEPARATOR .$object); 
	       } 
	     }
	     rmdir($dir); 
	   }
	}
	/**
	 * update a package
	 * 
	 * @param string: $dir
	 */
	public function update_package($name_of_package) {
		if (! $name_of_package) {
			return false;
		}
	    
	    $array_of_circuits = get_option('ultrapress', array() );
	    $components = Ultrapress_Class::$components;

		$components_of_package = array();
	 	$composed_components_of_package = array();
	 	$circuits_of_composed_components_of_package = array();

	 	if (array_key_exists($name_of_package, Ultrapress_Class::$packages)) {
	    	$package = Ultrapress_Class::$packages[ $name_of_package ];
	    } else {
	    	return false;
	    }
	    $json_array = array();
	 	$json_array[ 'circuits' ] = array();
	 	$json_array[ 'name' ] = $package[ 'name' ];
	 	$json_array[ 'desc' ] = $package[ 'disc' ];

	 	$circuit_keys = array();
	 	foreach ($array_of_circuits as $circuit_key => $circ) {
	 		if ( array_key_exists('package', $circ) && ( $name_of_package == $circ[ 'package' ] ) ) {
				$circuit_keys[] = $circuit_key;
	 		}
	 	}

	    foreach ($circuit_keys as $k => $circuit_key) {
	    	$circuit = $array_of_circuits[ $circuit_key ];
		    $all_components_of_current_circuit =  Ultrapress_Class::get_components( $circuit_key );

		    $components_of_current_circuit = $all_components_of_current_circuit["components"];
	 	    $composed_components_of_current_circuit = $all_components_of_current_circuit["composed_components"];
	 	    $circuits_of_composed_components_of_current_circuit = $all_components_of_current_circuit["circuits_of_composed_components"];

	 	    $components_of_package = array_merge($components_of_package, $components_of_current_circuit);
	 		$composed_components_of_package = array_merge($composed_components_of_package, $composed_components_of_current_circuit);
	 		$circuits_of_composed_components_of_package = array_merge($circuits_of_composed_components_of_package, $circuits_of_composed_components_of_current_circuit);

			$json_array[ 'circuits' ][ $circuit_key ] = $circuit;
	    }

	    $json_array[ 'composed_components' ] = array_merge(array(), $composed_components_of_package);
	    $json_array[ 'circuits_of_composed_components' ] = array_merge(array(), $circuits_of_composed_components_of_package);

	    mkdir(ULTRAPRESS_PATH . 'packages' . DIRECTORY_SEPARATOR . $name_of_package  . DIRECTORY_SEPARATOR . 'temporary', 0755);

		foreach ($components_of_package as $key => $value) {
		 	$path_of_component_dir = $components[ $key ][ 'path_of_component_dir' ];
		 	$this->recurse_copy($path_of_component_dir,  ULTRAPRESS_PATH . 'packages' . DIRECTORY_SEPARATOR . $name_of_package  . DIRECTORY_SEPARATOR . 'temporary' . DIRECTORY_SEPARATOR . $components[ $key ][ 'name' ] );
		 }

		 $this->delete_dir_recursively(ULTRAPRESS_PATH . 'packages' . DIRECTORY_SEPARATOR . $name_of_package  . DIRECTORY_SEPARATOR . 'components');

		 rename(ULTRAPRESS_PATH . 'packages' . DIRECTORY_SEPARATOR . $name_of_package  . DIRECTORY_SEPARATOR . 'temporary', ULTRAPRESS_PATH . 'packages' . DIRECTORY_SEPARATOR . $name_of_package  . DIRECTORY_SEPARATOR . 'components');

		 $json  = json_encode( $json_array );
		 $file = fopen( ULTRAPRESS_PATH . 'packages' . DIRECTORY_SEPARATOR . $name_of_package . DIRECTORY_SEPARATOR .'data.json','w');
         fwrite($file, $json);
         fclose($file);

	    return true;
	}
	
	/**
	 * upload package
	 * 
	 * @param string: $dir
	 */
	public function ultrapress_upload_package() {
		$response = array();
	    // check the nonce
	    if ( check_ajax_referer( 'ultrapress_upload_package', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }

	    $filename = $_FILES['file']['name'];
	    $location = ULTRAPRESS_PATH . 'packages' . DIRECTORY_SEPARATOR . $filename;
		$uploadOk = 1;
		$imageFileType = pathinfo($location,PATHINFO_EXTENSION);

		/* Valid Extensions */
		$valid_extensions = array("zip",);
		/* Check file extension */
		if( !in_array(strtolower($imageFileType),$valid_extensions) ) {
		   $response["response"] = 'F';
	    	$response["error_message"] =  __('file should be .zip', 'ultrapress');
	        echo json_encode( $response );
	        die();
		}

	   /* Upload file */
	   if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
	   		$this->copy_unzip_delete('',
			     ULTRAPRESS_PATH . 'packages' . DIRECTORY_SEPARATOR . $filename,
			     ULTRAPRESS_PATH . 'packages/'
			 );
	        $response["response"] = 'T';	
		    echo json_encode( $response );
			die();
	   }else{
	        $response["response"] = 'F';
	    	$response["error_message"] =  __('Error: file not uploaded', 'ultrapress');
	        echo json_encode( $response );
	        die();
	   }

	}


	/**
	 * delete dir Recursively 
	 * 
	 * @param string: $dir
	 */
	public function copy_unzip_delete($source, $dist, $zip_to_dir) {
		if ($source) {
			 @copy($source, $dist);
		}
	   
		$zip = new ZipArchive;
		$res = $zip->open( $dist );
		if ($res === TRUE) {
		  $zip->extractTo( $zip_to_dir );
		  $zip->close();
		}

		unlink( $dist );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 *
 	 * @param string  $hook : hook of admin page. used to check if we are in a ultrapress page
	 */
	public function enqueue_scripts($hook) { 
		// check if we are in a ultrapress page
		if ( ($this->hook != $hook) && ($this->hook_of_circuits_page != $hook) &&
		 ($this->hook_of_components_page != $hook) && ($this->hook_of_add_new_page != $hook)  && ($this->hook_of_packages_page != $hook) && ($this->hook_of_dashboard_page != $hook) && ($this->hook_of_documentation_page != $hook) ) {  
			return;
		}

		// remove all admin notices & footer text
		add_filter( 'admin_footer_text', function ($content) {
		    return "";
		}, 11 );

		add_filter( 'admin_footer_text', '__return_empty_string', 11 ); 
		add_filter( 'update_footer', '__return_empty_string', 11 );

		remove_all_actions('admin_notices');
 		remove_all_actions('all_admin_notices');

		// Access the wp_scripts global to get the jquery-ui-core version used.
        global $wp_scripts;
        // Create a handle for the jquery-ui-core css.
        $handle = 'jquery-ui';
        // Register the stylesheet handle.
        wp_register_style( $handle, plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css', array(), '1.11.4' );
        // Enqueue the style.
        wp_enqueue_style( 'jquery-ui' );

		wp_enqueue_style(  'ultrapress-css', plugin_dir_url( __FILE__ ) . 'css/ultrapress-admin.css' ,  array(), filemtime(plugin_dir_path( __FILE__ ) . 'css/ultrapress-admin.css') );

		wp_enqueue_script( 'vue', plugin_dir_url( __FILE__ ) . 'js/vue.min.js' );

		wp_enqueue_style( 'bootstrap4', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), 'v4.4.1', 'all' );

		wp_enqueue_script( 'popper', plugin_dir_url( __FILE__ ) . 'js/popper.min.js', array('jquery'), 'v4.0.0', true );

		wp_enqueue_script( 'bootstrap-4-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array('jquery', 'popper'), 'v4.4.1', true );

		wp_enqueue_style( 'load-fa', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' , array(), 'v4.4.11');


		// enqueue jquery-ui components
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( 'jquery-ui-slider' );

		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-button' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-droppable' );
		wp_enqueue_script( 'jquery-ui-menu' );
		wp_enqueue_script( 'jquery-ui-position' );
		wp_enqueue_script( 'jquery-ui-progressbar' );
		wp_enqueue_script( 'jquery-ui-selectable' );
		wp_enqueue_script( 'jquery-ui-resizable' );
		wp_enqueue_script( 'jquery-ui-selectmenu' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-spinner' );
		wp_enqueue_script( 'jquery-ui-tooltip' );
		wp_enqueue_script( 'jquery-ui-tabs' );

	    wp_enqueue_script('ultrapress-js',  plugin_dir_url( __FILE__ ) . 'js/ultrapress-admin.js', array('vue', 'jquery'), filemtime( plugin_dir_path( __FILE__ ) . 'js/ultrapress-admin.js' ), true );

	    wp_localize_script( 'ultrapress-js', 'mysettings', array(
        	'ajaxurl'    => admin_url( 'admin-ajax.php' ),
    ) );

	    wp_enqueue_style('ultrapress-dashboard', plugin_dir_url(__FILE__) . 'css/ultrapress-dashboard.css', array(), ULTRAPRESS_VERSION);
	}

	public function enqueue_debug_assets($hook) {
		// check if we are in a ultrapress page
		if ( ($this->hook != $hook) && ($this->hook_of_circuits_page != $hook) &&
		 ($this->hook_of_components_page != $hook) && ($this->hook_of_add_new_page != $hook)  && ($this->hook_of_packages_page != $hook) && ($this->hook_of_dashboard_page != $hook) && ($this->hook_of_documentation_page != $hook) ) {  
			return;
		}

		wp_enqueue_script(
            'ultrapress-debug-js',
            plugin_dir_url( __FILE__ ) . 'js/ultrapress-debug.js',
            array('jquery'),
            ULTRAPRESS_VERSION,
            true
        );

		wp_localize_script( 'ultrapress-debug-js', 'ultrapress_debug', array(
        	'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce('ultrapress_log_error')
        ));
	}

	public function handle_js_error_logging() {
		$response = array();
	    // check the nonce
	    if ( check_ajax_referer( 'ultrapress_log_error', 'ultrapress_nonce', false ) == false ) {
	    	$response["response"] = 'F';
	    	$response["error_message"] =  __('check_ajax_referer problem', 'ultrapress');
	        echo json_encode( $response );
	        die();
	    }

	    $error_message = sanitize_text_field( $_POST["error_message"] );
	    $error_stack = sanitize_text_field( $_POST["error_stack"] );

	    $log_file = fopen( ULTRAPRESS_PATH . 'debug.log', 'a' );
	    fwrite( $log_file, $error_message . "\n" . $error_stack . "\n" );
	    fclose( $log_file );

	    $response["response"] = 'T';
	    echo json_encode( $response );
		die();
	}

}

new Ultrapress_Admin();