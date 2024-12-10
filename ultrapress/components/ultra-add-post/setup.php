<?php
/**
 * ultra-add-post component for ultrapress
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
if ( ! class_exists( 'ultra_Add_Post' ) ) {
class ultra_Add_Post {
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
	public static $trigger = 'ultra/add_post';
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
	public static $name = 'ultra-add-post';
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
	public static $description = 'add new post';
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
		'post_content' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => '(mixed) The post content. Default empty.',
		),
		'post_author' => array(
			'primal' => 1,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => '(int) The ID of the user who added the post. Default is the current user ID.',
		),
		'post_title' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 1,
			'description' => '(string) The post title. Default empty.',
		),
		'post_status' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => '(string) The post status. Default "publish".',
		),
		'post_type' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => '(string) The post type. Default "post".',
		),
		'comment_status' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => "(string) Whether the post can accept comments. Accepts 'open' or 'closed'. Default is the value of 'default_comment_status' option.",
		),
		'post_name' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => "(string) The post name. Default is the sanitized post title when creating a new post.",
		),
		'post_category' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => "(array) Array of category IDs. Defaults to value of the 'default_category' option.",
		),
		'tags_input' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => "(array) Array of tag names, slugs, or IDs. Default empty.",
		),
		'meta_input' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => "(array) Array of post meta values keyed by their post meta key. Default empty.",
		),
		'featured_image' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => "(file) featured image of post",
		),
		'multiple_attachments' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => "(boolean) multiple files. Default 0.",
		),
		'attachments' => array(
			'primal' => 0,
			'type_of_variable' => 0,
			'required' => 0,
			'description' => "(array) attachments of post. Default empty.",
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
			'description' => 'post added successufilly',
			'name' => 'success',
			'args' => array(
				'post_id' => array(
					'type_of_variable' => '',
					'description' => 'ID of created post',
					'name' => 'ID of post',
				),
				'post_author' => array(
					'type_of_variable' => '',
					'description' => 'ID of the user who added the post',
					'name' => 'author ID',
				),
			),
		),
		'/fail' => array(
			'description' => 'error: post not added',
			'args' => array(
				'error' => array(
					'type_of_variable' => '',
					'description' => 'Error message',
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
		$success_icon_url = 'img/success.png';
	    $fail_icon_url =  'img/report.png';
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
	      'post_author'   => get_current_user_id(),
	      'post_content'  => '',
	      'post_status'  => 'publish',
	      'post_type'  => 'post',
	      'comment_status'  => 'open',
	      'attachments_name'  => '', 
	      'featured_image'  => 0, 
	      'multiple_attachments'  => 0,  
	      'attachments'  => array(), 
			);
		

		/**
 		* Parse incoming $args into an array and merge it with $defaults
 		*/ 
		$ultra_args['args'] = wp_parse_args( $ultra_args['args'], $defaults );
		$args =  $ultra_args['args'];

		// your code
		$my_post = array(
	      'post_title'    => wp_strip_all_tags( $args['post_title'] ),
	      'post_author'   => $args['post_author'],
	      'post_content'  => $args['post_content'],
	      'post_status'   => $args['post_status'],
	      'post_type'     => $args['post_type'],
	      'comment_status'     => $args['comment_status'], 
	    );

	    if ( array_key_exists('post_name', $args) ) {
	    	$my_post['post_name'] = $args['post_name'];
	    }
	    if ( array_key_exists('post_category', $args) ) {
	    	$my_post['post_category'] = $args['post_category'];
	    }
	    if ( array_key_exists('tags_input', $args) ) {
	    	$my_post['tags_input'] = $args['tags_input'];
	    }
	    if ( array_key_exists('meta_input', $args) ) {
	    	$my_post['meta_input'] = $args['meta_input'];
	    }
	    update_option('ultra_debug', $args);
	    $post_id = wp_insert_post( $my_post );

	    if( !is_wp_error($post_id) ) {	    	
	    	$featured_image = $args['featured_image'];
	    	update_option('ultra_debug2', array($featured_image, $post_id));
	    	if ($featured_image) {	    			
	    		$res = self::instance()->save_single_file( $featured_image, $post_id, 'featured_image');
	    	}

	    	$attachments = $args['attachments'];
	    	update_option('ultra_debug2', array($attachments, $post_id));
	    	if ($attachments) {
	    		$multiple_attachments = 0;

	    		if (! $multiple_attachments) {
	    			
	    			$res = self::instance()->save_single_file( $attachments, $post_id, 'post_attachments');
	    		} else {
	    			$res = self::instance()->save_files( $attachments, $post_id);
	    		}  

	    	} else {
	    		$res = false;
	    	}
	    	
	    	$args_success = array(
				'post_id' => $post_id,
				'post_author' => $args['post_author'],
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
	 * run the component
	 *
	 *
	 * Fired by self::$trigger hook
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array    $files   array of files
	 * @param int    $post_id   ID of the created post
	 */
	public function save_files($files, $post_id) {
		// upload
	$fileErrors = array(
			0 => "There is no error, the file uploaded with success",
			1 => "The uploaded file exceeds the upload_max_files in server settings",
			2 => "The uploaded file exceeds the MAX_FILE_SIZE from html form",
			3 => "The uploaded file uploaded only partially",
			4 => "No file was uploaded",
			6 => "Missing a temporary folder",
			7 => "Failed to write file to disk",
			8 => "A PHP extension stoped file to upload" );

	 $temp_name = $files["tmp_name"];
	 $upload_dir = wp_upload_dir();
	 $upload_path = $upload_dir["basedir"] . "/mp/";
	 $upload_url = $upload_dir["baseurl"] . "/mp/";
	 if(!file_exists($upload_path)){
				mkdir($upload_path);
	}

	 $targetPath = $upload_path;
	 $fileName = $files["name"];

	$fileNameChanged = str_replace(" ", "_", $fileName);

	$post_attachments = array();

	for($i=0; $i<count($files['name']); $i++){
		$upload_path2 = $upload_path;
		$upload_url2 = $upload_url;
		$fileName = $files["name"][$i];
	    $ext = explode('.', basename( $fileName ));
	    $attachment_name = md5(uniqid());
	    $upload_path2 = $upload_path2 . $attachment_name . "." . $ext[count($ext)-1];
	    $upload_url2 = $upload_url2 . $attachment_name . "." . $ext[count($ext)-1];
	    $file_size = $data[$attachments_name]["size"][$i];
	    $mb = 10 * 1024 * 1024;
	    $fileError = $data[$attachments_name]["error"][$i];

		if($fileError > 0){
			$response["response"] = "ERROR";
			$response["error"] = $fileErrors[ $fileError ];
			//update_comment_meta($comment_receiver_id, 'comment_attachments', $comment_attachments);
			//update_comment_meta($comment_id, 'comment_attachments', $comment_attachments);
			//meed_delete_comment($comment_id);
			//meed_delete_comment($comment_receiver_id);
			return false;
		} else {
					if($file_size <= $mb){
						// حجم الملف مناسب
				            	if(move_uploaded_file($files['tmp_name'][$i], $upload_path2)){
				            		$response["response"] = "SUCCESS";
				            		$attachment = array(
				            			"url" =>  $attachment_name . "." . $ext[count($ext)-1],
				            			"title" => $files["name"][$i],
				            		);
				            		$post_attachments[] =  $attachment;
				            		$response["url"] =  $upload_url2;
				            		update_post_meta($post_id, 'post_attachments', $post_attachments);

				            	} else {
												// وقعت مشكلة في التحميل
												$response["response"] = "ERROR";
												$response["error"]= "وقعت مشكلة في التحميل، المرجو إعادة المحاولة";
												//update_comment_meta($comment_id, 'comment_attachments', $comment_attachments);
												//update_comment_meta($comment_receiver_id, 'comment_attachments', $comment_attachments);
												//meed_delete_comment($comment_id);
												//meed_delete_comment($comment_receiver_id);
												return false;
				            	}

			           		 } else {
											 // حجم الملف كبير للغاية
	 										$response["response"] = "ERROR";
	 										$response["error"]= "هيوا على السلامة";
											//update_comment_meta($comment_id, 'comment_attachments', $comment_attachments);
											//update_comment_meta($comment_receiver_id, 'comment_attachments', $comment_attachments);
											//meed_delete_comment($comment_id);
											//meed_delete_comment($comment_receiver_id);
											return false;
			            	}
		            	}


	/*
	     if(move_uploaded_file($_FILES['file']['tmp_name'][$i], $upload_path)) {
	        $response['coco']  = "mlih";
	    } else{
	        $response['coco']  = "ralat";
	    }
	} */
	}


	
	return true;

	}

	public function save_single_file($files, $post_id, $option_name ) {
	 $temp_name = $files["tmp_name"];
	 $upload_dir = wp_upload_dir();
	 $upload_path = $upload_dir["basedir"] . "/mp/";
	 $upload_url = $upload_dir["baseurl"] . "/mp/";

	 if(!file_exists($upload_path)){
		mkdir($upload_path);
	}

	$targetPath = $upload_path;
	$fileName = $files["name"];

	$fileNameChanged = str_replace(" ", "_", $fileName);

	$post_attachments = array();

	$upload_path2 = $upload_path;
	$upload_url2 = $upload_url;
	$fileName = $files["name"];
    $ext = explode('.', basename( $fileName ));
    $attachment_name = md5(uniqid());
    $upload_path2 = $upload_path2 . $attachment_name . "." . $ext[count($ext)-1];
    $upload_url2 = $upload_url2 . $attachment_name . "." . $ext[count($ext)-1];
    $file_size = $data[$attachments_name]["size"];
    $mb = 10 * 1024 * 1024;
    $fileError = $data[$attachments_name]["error"];

	if($fileError > 0){
		return false;
	} else {
		if($file_size <= $mb){
        	if(move_uploaded_file($files['tmp_name'], $upload_path2)){
        		$attachment = array(
        			"url" =>  $attachment_name . "." . $ext[count($ext)-1],
        			"title" => $files["name"][$i],
        		);
        		$post_attachments[] =  $attachment;

        	} else {
				return false;
        	}
   		 } else {
			return false;
    	}
	}

	update_post_meta($post_id, $option_name , $post_attachments);
	return true;
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

	final public static function get_title() {
		return __( 'Ultrapress', 'ultrapress' );
	}
}
}

ultra_Add_Post::instance();