<?php
/**
 * Handles all communication with external AI APIs for both the chatbot and SEO features.
 *
 * @package UltraPress
 * @since 5.2.1
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class UltraPress_API_Handler {

    // قائمة النماذج الصحيحة لـ Gemini
    private static $valid_gemini_models = [
        'gemini-pro',
        'gemini-1.5-pro', 
        'gemini-1.5-pro-latest',
        'gemini-1.5-flash',
        'gemini-1.5-flash-latest'
    ];

    public static function handle_chatbot_request() {
        check_ajax_referer('ultrapress-nonce', 'nonce');
        $history = isset($_POST['history']) && is_string($_POST['history']) ? json_decode(stripslashes($_POST['history']), true) : [];
        if (empty($history) || !is_array($history)) {
            wp_send_json_error(['message' => esc_html__('Invalid conversation history.', 'ultrapress')]);
            return;
        }
        $sanitized_history = self::sanitize_history($history);
        $last_user_message = end($sanitized_history);
        if ('user' !== $last_user_message['role']) {
             wp_send_json_error(['message' => esc_html__('Invalid request format.', 'ultrapress')]);
            return;
        }
        $system_prompt = self::get_chatbot_system_prompt(get_option('ultrapress_settings', []));
        $response = self::send_request($system_prompt, $sanitized_history);
        if (is_wp_error($response)) {
            wp_send_json_error(['message' => $response->get_error_message()]);
        } else {
            // Future: self::save_conversation_turn($last_user_message['content'], $response);
            wp_send_json_success($response);
        }
    }

    public static function handle_seo_generation_request() {
        check_ajax_referer('ultrapress-seo-nonce', 'nonce');
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => esc_html__('Permission denied.', 'ultrapress')]);
            return;
        }
        $post_title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
        $post_content = isset($_POST['content']) ? wp_strip_all_tags(stripslashes($_POST['content'])) : '';
        $focus_keyword = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';
        $site_title = get_bloginfo('name');
        $system_prompt = self::get_seo_system_prompt($post_title, $post_content, $focus_keyword, $site_title);
        $response = self::send_request($system_prompt, []);
        if (is_wp_error($response)) {
            wp_send_json_error(['message' => $response->get_error_message()]);
        } else {
            $decoded_response = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($decoded_response['title'])) {
                wp_send_json_success($decoded_response);
            } else {
                wp_send_json_error(['message' => esc_html__('The AI returned an invalid format. Please try again.', 'ultrapress'), 'raw' => $response]);
            }
        }
    }

    private static function send_request($system_prompt, $history) {
        $options = get_option('ultrapress_settings', []);
        $provider = $options['api_provider'] ?? 'openai';
        $api_key = self::get_api_key($provider, $options);
        $model = $options[$provider . '_model'] ?? '';
        $max_tokens = absint($options['max_tokens'] ?? 800);

        if (empty($api_key)) return new WP_Error('api_key_missing', sprintf(esc_html__('API Key for %s is not configured.', 'ultrapress'), ucfirst($provider)));
        if (empty($model)) return new WP_Error('model_missing', sprintf(esc_html__('Model for %s is not configured.', 'ultrapress'), ucfirst($provider)));

        // تصحيح اسم نموذج Gemini إذا كان غير صحيح
        if ($provider === 'gemini') {
            $model = self::validate_gemini_model($model);
        }

        list($api_url, $body, $headers) = self::prepare_request_data($provider, $api_key, $model, $system_prompt, $history, $max_tokens);
        
        $response = wp_remote_post($api_url, ['headers' => $headers, 'body' => json_encode($body), 'timeout' => 60]);

        if (is_wp_error($response)) return new WP_Error('http_error', $response->get_error_message());

        return self::parse_response($provider, $response);
    }
    
    /**
     * التحقق من صحة نموذج Gemini وتصحيحه إذا لزم الأمر
     */
    private static function validate_gemini_model($model) {
        // إذا كان النموذج صحيحاً، أعده كما هو
        if (in_array($model, self::$valid_gemini_models)) {
            return $model;
        }
        
        // محاولة تصحيح الأسماء الشائعة الخاطئة
        $model_lower = strtolower($model);
        
        // تصحيح الأخطاء الشائعة
        if (strpos($model_lower, 'gemini-25') !== false || 
            strpos($model_lower, 'gemini-2.5') !== false) {
            return 'gemini-1.5-pro'; // استخدم أحدث نموذج متاح
        }
        
        if (strpos($model_lower, 'gemini') !== false && strpos($model_lower, 'flash') !== false) {
            return 'gemini-1.5-flash';
        }
        
        if (strpos($model_lower, 'gemini') !== false && strpos($model_lower, 'pro') !== false) {
            return 'gemini-1.5-pro';
        }
        
        // إذا لم نتمكن من تحديد النموذج، استخدم النموذج الافتراضي
        return 'gemini-1.5-pro';
    }
    
    private static function prepare_request_data($provider, $api_key, $model, $system_prompt, $history, $max_tokens) {
        if ($provider === 'gemini') {
            $api_url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$api_key}";
            $full_prompt = self::build_single_prompt_for_gemini($system_prompt, $history);
            $gemini_body = [
                'contents' => [['parts' => [['text' => $full_prompt]]]],
                'generationConfig' => ['temperature' => 0.3, 'maxOutputTokens' => $max_tokens]
            ];
            return [$api_url, $gemini_body, ['Content-Type' => 'application/json']];
        }

        // --- Logic for OpenAI and DeepSeek (remains the same) ---
        $messages_to_keep_at_start = 2; $messages_to_keep_at_end = 8;
        $max_history_items = $messages_to_keep_at_start + $messages_to_keep_at_end;
        if (count($history) > $max_history_items) {
            $start_slice = array_slice($history, 0, $messages_to_keep_at_start);
            $end_slice = array_slice($history, -$messages_to_keep_at_end);
            $summary_message = [['role' => 'system', 'content' => '[... Earlier conversation omitted ...]']];
            $history = array_merge($start_slice, $summary_message, $end_slice);
        }
        
        $headers = ['Content-Type'  => 'application/json', 'Authorization' => 'Bearer ' . $api_key];
        $messages = array_merge([['role' => 'system', 'content' => $system_prompt]], $history);
        $body = ['model' => $model, 'messages' => $messages, 'max_tokens' => $max_tokens, 'temperature' => 0.2];
        $api_url = ($provider === 'deepseek') ? 'https://api.deepseek.com/chat/completions' : 'https://api.openai.com/v1/chat/completions';
        
        return [$api_url, $body, $headers];
    }
    
    private static function build_single_prompt_for_gemini($system_prompt, $history) {
        $full_prompt = $system_prompt . "\n\n--- CONVERSATION HISTORY ---\n\n";
        foreach ($history as $item) {
            $role = ($item['role'] === 'user') ? 'User' : 'Assistant';
            $full_prompt .= "{$role}: {$item['content']}\n";
        }
        $full_prompt .= "\nAssistant:";
        return $full_prompt;
    }

    private static function parse_response($provider, $response) {
        $http_code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);
        if ($http_code !== 200) {
            $error_message = $body['error']['message'] ?? ($body['message'] ?? esc_html__('Unknown API error.', 'ultrapress'));
            
            // إضافة رسالة مساعدة إضافية لأخطاء Gemini
            if ($provider === 'gemini' && strpos($error_message, 'is not found') !== false) {
                $error_message .= sprintf(
                    esc_html__(' Valid Gemini models are: %s', 'ultrapress'),
                    implode(', ', self::$valid_gemini_models)
                );
            }
            
            return new WP_Error('api_error', "API Error ($http_code): " . esc_html($error_message));
        }
        $content = '';
        if ($provider === 'gemini') {
            $content = $body['candidates'][0]['content']['parts'][0]['text'] ?? '';
        } else {
            $content = $body['choices'][0]['message']['content'] ?? '';
        }
        if (!empty($content)) return trim($content);
        return new WP_Error('response_parse_error', esc_html__('Could not parse a valid response from the API.', 'ultrapress'));
    }
    
    private static function get_api_key($provider, $options) {
        $constant_name = 'ULTRAPRESS_' . strtoupper($provider) . '_API_KEY';
        if (defined($constant_name) && !empty(constant($constant_name))) return constant($constant_name);
        return $options[$provider . '_api_key'] ?? '';
    }

    private static function get_chatbot_system_prompt($options) {
        $site_info = $options['chatbot_system_prompt'] ?? ''; $contact_info = $options['chatbot_contact_info'] ?? ''; $persona = $options['chatbot_persona'] ?? 'professional';
        $persona_instruction = '';
        switch ($persona) {
            case 'friendly': $persona_instruction = "Adopt a very friendly, welcoming, and conversational tone. Use emojis where appropriate."; break;
            case 'enthusiastic_marketer': $persona_instruction = "Adopt an enthusiastic and persuasive tone. Highlight benefits and encourage action."; break;
            case 'technical_support': $persona_instruction = "Adopt a precise, technical, and methodical tone. Ask clarifying questions and provide clear steps."; break;
            case 'playful': $persona_instruction = "Adopt a playful, witty, and creative tone. You can use light humor but remain helpful."; break;
            case 'concise': $persona_instruction = "Adopt a concise and direct tone. Get straight to the point without extra conversational fluff."; break;
            default: $persona_instruction = "Adopt a professional, formal, and polite tone. Avoid slang or overly casual language."; break;
        }
        $system_prompt = $persona_instruction;
        $system_prompt .= "\n\n--- INITIAL INTERACTION RULE ---\n";
        $system_prompt .= "After your welcome message, if the user asks their first question, your very first task is to politely ask for their name, for example: 'I can certainly help with that. First, may I know your name?'. Once they provide a name, use it in subsequent responses to personalize the conversation.";
        $system_prompt .= "\n\n--- CORE RULES ---\n";
        $system_prompt .= "1. Your knowledge is strictly limited to the information provided in the 'COMPANY KNOWLEDGE BASE'.\n";
        $system_prompt .= "2. **CRITICAL RULE: If a user's question cannot be answered from the knowledge base, you MUST NOT invent an answer.**\n";
        if (!empty($contact_info)) {
            $system_prompt .= "3. If you do not have the information, you MUST respond with a phrase like 'I do not have that specific information. For more details, please contact us at: " . $contact_info . "'";
        } else {
            $system_prompt .= "3. If you do not have the information, you MUST respond with a phrase like 'I'm sorry, but I do not have access to that specific information.'";
        }
        $system_prompt .= "\n4. Always format your responses using Markdown (e.g., **bold**, lists with -).\n";
        $system_prompt .= "\n--- COMPANY KNOWLEDGE BASE ---\n";
        $system_prompt .= !empty($site_info) ? $site_info : "No company information has been provided.";
        return $system_prompt;
    }

    private static function get_seo_system_prompt($title, $content, $keyword, $site_title) {
        $prompt = "You are an expert SEO copywriter. Your task is to generate an SEO-optimized meta title and description.\nRULES:\n";
        $prompt .= "1. The meta title must be compelling and under 60 characters total.\n";
        $prompt .= "2. The generated title must end with a separator and the site title. Example format: 'Generated Title - {$site_title}'. You may need to shorten the generated part to fit the character limit.\n";
        $prompt .= "3. The meta description must be enticing, encourage clicks, and be under 160 characters.\n";
        if (!empty($keyword)) {
            $prompt .= "4. You MUST naturally incorporate the Focus Keyword '{$keyword}' into both the title and the description.\n";
            $prompt .= "5. Your response MUST be ONLY a valid JSON object in the format: {\"title\": \"...\", \"description\": \"...\"}\n";
        } else {
            $prompt .= "4. First, identify the single most relevant 'Focus Keyword' for the article.\n";
            $prompt .= "5. Your response MUST be ONLY a valid JSON object in the format: {\"title\": \"...\", \"description\": \"...\", \"suggested_keyword\": \"...\"}\n";
        }
        $prompt .= "\n--- ARTICLE DETAILS ---\n";
        $prompt .= "Site Title: {$site_title}\n";
        $prompt .= "Article Title: {$title}\n";
        $prompt .= "Content Snippet: " . substr($content, 0, 1500) . "\n";
        return $prompt;
    }

    private static function sanitize_history($history) {
        $sanitized = [];
        foreach ($history as $item) {
            if (!empty($item['role']) && isset($item['content'])) {
                $sanitized[] = ['role' => sanitize_key($item['role']), 'content' => sanitize_textarea_field($item['content'])];
            }
        }
        return $sanitized;
    }

    private static function format_for_gemini($system_prompt, $history) { /* DEPRECATED - This function is no longer needed with the single-prompt approach */ return []; }
}