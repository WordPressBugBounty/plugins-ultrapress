<?php
/**
 * AI Translation Component for Ultrapress
 *
 * This component takes a text and translates it using multiple AI providers
 * (like OpenAI or Claude). 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct file access
}

if ( ! class_exists( 'ai_Translate' ) ) :

class ai_Translate {

	/**
	 * The unique trigger (hook) of this component.
	 * When Ultrapress calls this hook, it will run the component logic.
	 */
	public static $trigger = 'ai/translate';

	/**
	 * The internal name of the component (folder name, typically).
	 */
	public static $name = 'ai-translate';

	/**
	 * Version of the component
	 */
	public static $version = '1.0.0';

	/**
	 * Brief description of the component
	 */
	public static $description = 'Translate text using AI providers (OpenAI or Claude).';

	/**
	 * The accepted input arguments (Inputs).
	 * Each argument is defined by a key => array of properties:
	 *  - primal (1/0): whether it's a primary (essential) argument
	 *  - required (1/0): must be provided or not
	 *  - type_of_variable (0 => string, 1 => int, 2 => array, etc.)
	 *  - description: short explanation
	 *  - name: label for UI
	 */
	public static $accepted_args = array(
		'text' => array(
			'primal' => 1,
			'required' => 1,
			'type_of_variable' => 0,
			'description' => 'The text to be translated.',
			'name' => 'Input Text',
		),
		'target_language' => array(
			'primal' => 0,
			'required' => 0,
			'type_of_variable' => 0,
			'description' => 'Language to translate into (e.g. en, ar, fr). Default is "en".',
			'name' => 'Target Language',
		),
		'provider' => array(
			'primal' => 0,
			'required' => 0,
			'type_of_variable' => 0,
			'description' => 'Which AI provider to use (e.g. "openai" or "claude").',
			'name' => 'AI Provider',
		),
		'api_key' => array(
			'primal' => 0,
			'required' => 0,
			'type_of_variable' => 0,
			'description' => 'Your API key for the chosen provider.',
			'name' => 'API Key',
		),
		'model' => array(
			'primal' => 0,
			'required' => 0,
			'type_of_variable' => 0,
			'description' => 'Model name (e.g. "gpt-3.5-turbo" for OpenAI).',
			'name' => 'Model',
		),
	);

	/**
	 * Additional input can be defined here if needed (like placeholders).
	 * We'll leave it empty for now.
	 */
	public static $additional_input = array();

	/**
	 * Define the outputs. Each output has:
	 * - a key like '/success' or '/fail'
	 * - description
	 * - args (the arguments it provides to the next component)
	 * - name (a label)
	 */
	public static $outputs = array(
		'/success' => array(
			'description' => 'Translation completed successfully',
			'name' => 'Translation Success',
			'args' => array(
				'translated_text' => array(
					'type_of_variable' => 0,
					'description'      => '(string) The translated text',
					'name'            => 'Translated Text',
				),
				'provider_used' => array(
					'type_of_variable' => 0,
					'description'      => '(string) The provider that handled the request',
					'name'            => 'Provider Used',
				),
			),
		),
		'/fail' => array(
			'description' => 'Failed to translate text',
			'name' => 'Translation Failed',
			'args' => array(
				'error_message' => array(
					'type_of_variable' => 0,
					'description'      => '(string) Reason of failure',
					'name'            => 'Error Message',
				),
				'provider_used' => array(
					'type_of_variable' => 0,
					'description'      => '(string) Provider chosen at the time of failure',
					'name'            => 'Provider Used',
				),
			),
		),
	);

	/**
	 * Internal property to store single instance
	 */
	private static $instance = null;

	/**
	 * Retrieve the singleton instance of this class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor: will register hooks to integrate with Ultrapress
	 */
	private function __construct() {
		// When Ultrapress is loaded, we register our component info
		add_action( 'ultrapress/loaded', array( $this, 'setup' ), 0 );

		// The main hook that triggers our run() method
		add_action( self::$trigger, array( $this, 'run' ), 10, 1 );
	}

	/**
	 * This method prepares the component info and tells Ultrapress about it.
	 */
	public function setup() {
		$info = $this->get_component_info();
		// This function (Ultrapress_Class::install_component) will register
		// our component into Ultrapress so it appears in the visual editor.
		Ultrapress_Class::install_component( $info );
	}

	/**
	 * Returns all the metadata about our component to Ultrapress
	 */
	public function get_component_info() {
		$path_of_component_dir = plugin_dir_path( __FILE__ );
		$url_of_component_dir  = plugin_dir_url( __FILE__ );

		// For icon_url, you can place an icon image in this folder and reference it
		$icon_url = $url_of_component_dir . 'comment-edit-icon.png'; 
		// Or use a placeholder if no icon is available

		return array(
			'trigger'               => self::$trigger,
			'name'                  => self::$name,
			'version'               => self::$version,
			'description'           => self::$description,
			'input'                 => self::$accepted_args,
			'additional_input'      => self::$additional_input,
			'outputs'               => self::$outputs,
			'icon_url'              => $icon_url,
			'path_of_component_dir' => $path_of_component_dir,
			'url_of_component_dir'  => $url_of_component_dir,
		);
	}

	/**
	 * The main logic of the component: called when the hook 'ai/translate' is fired.
	 * @param array $ultra_args The arguments from the previous component
	 */
	public function run( $ultra_args ) {
		// Define defaults
		$defaults = array(
			'text'            => '',
			'target_language' => 'en',
			'provider'        => 'openai', // or 'claude'
			'api_key'         => '',
			'model'           => 'gpt-3.5-turbo', // default for OpenAI
		);

		// Merge defaults with user-provided args
		$args = wp_parse_args( $ultra_args['args'], $defaults );

		// Extract them for convenience
		$text            = $args['text'];
		$target_language = $args['target_language'];
		$provider        = strtolower( $args['provider'] );
		$api_key         = $args['api_key'];
		$model           = $args['model'];

		// We will store final result or error
		$translated_text = '';
		$error_message   = '';

		try {
			// Quick checks
			if ( empty( $text ) ) {
				throw new \Exception( 'No input text provided' );
			}
			if ( empty( $api_key ) ) {
				throw new \Exception( 'No API key provided' );
			}

			// The logic to call the actual AI service:
			if ( $provider === 'openai' ) {
				// 1) Build request (Prompt) or ChatCompletion to openai
				// 2) Use something like wp_remote_post(...) or cURL
				// 3) Expect a JSON with { "status":"success", "translated_text":"..." }
				//    or manage the raw text and parse it.

				// PSEUDO code (this won't actually run without full details):
				$response = $this->call_openai_translation_api( $text, $target_language, $api_key, $model );
				// parse response
				if ( ! empty( $response['error'] ) ) {
					throw new \Exception( $response['error'] );
				}
				$translated_text = $response['translated_text'] ?? '';
				if ( empty( $translated_text ) ) {
					throw new \Exception( 'Empty translated text returned.' );
				}

			} elseif ( $provider === 'claude' ) {
				// Similar approach for Claude
				$response = $this->call_claude_translation_api( $text, $target_language, $api_key, $model );
				if ( ! empty( $response['error'] ) ) {
					throw new \Exception( $response['error'] );
				}
				$translated_text = $response['translated_text'] ?? '';
				if ( empty( $translated_text ) ) {
					throw new \Exception( 'No translation from Claude.' );
				}

			} else {
				throw new \Exception( 'Unsupported provider: ' . $provider );
			}

			// If we reach here => success
			$args_success = array(
				'translated_text' => $translated_text,
				'provider_used'   => $provider,
			);

			$ultra_args['args'] = $args_success;
			// Trigger success output
			Ultrapress_Class::do_action( $ultra_args, '/success' );

		} catch ( \Exception $ex ) {
			$error_message = $ex->getMessage();

			$args_fail = array(
				'error_message' => $error_message,
				'provider_used' => $provider,
			);

			$ultra_args['args'] = $args_fail;
			// Trigger fail output
			Ultrapress_Class::do_action( $ultra_args, '/fail' );
		}
	}

/**
 * Example helper function to call OpenAI for translation.
 * 
 * In a real scenario, you should refine the prompt, handle large texts,
 * and ensure you have proper error checks around JSON parsing, etc.
 *
 * @param string $text            The text to translate
 * @param string $target_language The language code to translate into (e.g. 'en', 'ar')
 * @param string $api_key         OpenAI API key
 * @param string $model           e.g. 'gpt-3.5-turbo'
 *
 * @return array  An associative array either containing 'translated_text' or 'error'
 */
private function call_openai_translation_api( $text, $target_language, $api_key, $model ) {

    // 1) حدد عنوان واجهة OpenAI (ChatCompletion)
    $api_url = 'https://api.openai.com/v1/chat/completions';

    // 2) صياغة تلقينة (Prompt) تلزم النموذج بإرجاع JSON واضح
    //    هنا نستخدم "System Message" لتوجيه النموذج
    $system_content = sprintf(
        'You are a translator. Please translate the user text into %s. 
         Return your result strictly in JSON format as follows:
         {"status":"success","translated_text":"...the translation..."} 
         If any error, then: {"status":"error","error_message":"reason"}',
        $target_language
    );

    // 3) نبني الرسائل التي سيتلقاها نموذج Chat
    //    الرسالة الأولى system: تحوي تعليمات. الرسالة الثانية user: تحوي النص
    $messages = array(
        array(
            'role'    => 'system',
            'content' => $system_content,
        ),
        array(
            'role'    => 'user',
            'content' => $text,
        ),
    );

    // 4) نجهز بيانات الطلب (Body)
    //    - 'model' يشير إلى اسم النموذج (مثل gpt-3.5-turbo)
    //    - 'messages' قائمة الرسائل
    //    - بإمكانك إضافة خيارات مثل temperature أو max_tokens الخ...
    $request_body = array(
        'model'    => $model,
        'messages' => $messages,
        'temperature' => 0.0, // set as you wish
    );

    // 5) تحضير المعاملات لمرسل wp_remote_post
    $request_args = array(
        'headers' => array(
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $api_key,
        ),
        'body'    => json_encode( $request_body ),
        'timeout' => 60, // ثواني كمهلة (يمكن رفعها عند الحاجة)
    );

    // 6) إرسال الطلب
    $response = wp_remote_post( $api_url, $request_args );

    // 7) التحقق من وجود خطأ على مستوى wp_remote_post
    if ( is_wp_error( $response ) ) {
        return array(
            'error' => 'Request error: ' . $response->get_error_message(),
        );
    }

    $status_code = wp_remote_retrieve_response_code( $response );
    $body        = wp_remote_retrieve_body( $response );

    if ( 200 !== $status_code ) {
        // ربما تودّ إرجاع مزيد من التفاصيل
        return array(
            'error' => 'OpenAI API returned status code ' . $status_code . ' - body: ' . $body,
        );
    }

    // 8) تحليل الـ JSON من استجابة الـ ChatCompletion
    //    عادة الاستجابة شكلها: { "id": "...", "object": "chat.completion", ... "choices":[{"message":{"role":"assistant","content":"..."}}], ... }
    $decoded = json_decode( $body, true );

    if ( ! isset( $decoded['choices'][0]['message']['content'] ) ) {
        return array(
            'error' => 'Invalid response from OpenAI (no content in choices)',
        );
    }

    // 9) المخرجات تأتي في content. نفترض أنها JSON من تعليماتنا بالخطوة 2
    $assistant_content = trim( $decoded['choices'][0]['message']['content'] );

    // 10) نحاول تحليل هذا الـ JSON الذي افترضنا أن الذكاء الاصطناعي سيرسله
    $parsed_ai_output = json_decode( $assistant_content, true );

    if ( json_last_error() !== JSON_ERROR_NONE || ! is_array($parsed_ai_output) ) {
        return array(
            'error' => 'Could not parse AI JSON output: ' . $assistant_content,
        );
    }

    // 11) نفرّق بين success / error كما طلبنا من الذكاء الاصطناعي
    if ( isset($parsed_ai_output['status']) && $parsed_ai_output['status'] === 'success' ) {
        $translated_text = $parsed_ai_output['translated_text'] ?? '';
        if ( empty($translated_text) ) {
            return array(
                'error' => 'Translated text is empty!',
            );
        }

        // نجاح
        return array(
            'translated_text' => $translated_text,
        );

    } elseif ( isset($parsed_ai_output['status']) && $parsed_ai_output['status'] === 'error' ) {
        $error_msg = $parsed_ai_output['error_message'] ?? 'Unknown AI error';
        return array(
            'error' => $error_msg,
        );

    } else {
        // حالة غير متوقعة
        return array(
            'error' => 'Unexpected AI output format: ' . print_r($parsed_ai_output, true),
        );
    }
}


	/**
	 * Example helper function for Claude
	 */
	private function call_claude_translation_api( $text, $target_language, $api_key, $model ) {
		// Similar approach
		return array(
			'translated_text' => 'نص مترجم بواسطة Claude (مثال)',
		);
	}

} // end class

endif; // class exists

// Initialize once
ai_Translate::instance();
