<?php
/**
 * Handles all communication with external AI APIs for both the chatbot and SEO features.
 *
 * @package UltraPress
 * @since 5.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class UltraPress_API_Handler {

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
    
    // **NEW**: Get the site title to pass to the AI
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
        $max_tokens = absint($options['max_tokens'] ?? 400);

        if (empty($api_key)) return new WP_Error('api_key_missing', sprintf(esc_html__('API Key for %s is not configured.', 'ultrapress'), ucfirst($provider)));
        if (empty($model)) return new WP_Error('model_missing', sprintf(esc_html__('Model for %s is not configured.', 'ultrapress'), ucfirst($provider)));

        list($api_url, $body, $headers) = self::prepare_request_data($provider, $api_key, $model, $system_prompt, $history, $max_tokens);
        
        $response = wp_remote_post($api_url, ['headers' => $headers, 'body' => json_encode($body), 'timeout' => 45]);

        if (is_wp_error($response)) return new WP_Error('http_error', $response->get_error_message());

        return self::parse_response($provider, $response);
    }
    
    private static function prepare_request_data($provider, $api_key, $model, $system_prompt, $history, $max_tokens) {
        // Advanced Context Management (Bookend Strategy)
        $messages_to_keep_at_start = 2;
        $messages_to_keep_at_end = 8;
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

        switch ($provider) {
            case 'openai': return ['https://api.openai.com/v1/chat/completions', $body, $headers];
            case 'deepseek': return ['https://api.deepseek.com/chat/completions', $body, $headers];
            case 'gemini':
                unset($headers['Authorization']);
                $api_url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$api_key}";
                $gemini_body = self::format_for_gemini($system_prompt, $history);
                return [$api_url, $gemini_body, ['Content-Type' => 'application/json']];
        }
        return ['', [], []];
    }

    private static function parse_response($provider, $response) {
        $http_code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);

        if ($http_code !== 200) {
            $error_message = $body['error']['message'] ?? ($body['message'] ?? esc_html__('Unknown API error.', 'ultrapress'));
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
        $site_info = $options['chatbot_system_prompt'] ?? '';
        $contact_info = $options['chatbot_contact_info'] ?? '';
        $persona = $options['chatbot_persona'] ?? 'professional';
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
    $prompt = "You are an expert SEO copywriter. Your task is to generate an SEO-optimized meta title and description.\n";
    $prompt .= "RULES:\n";
    $prompt .= "1. The meta title must be compelling and under 60 characters total.\n";
    
    // **NEW**: Updated rule for the title
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
                $sanitized[] = [
                    'role'    => sanitize_key($item['role']),
                    'content' => sanitize_textarea_field($item['content']),
                ];
            }
        }
        return $sanitized;
    }

    private static function format_for_gemini($system_prompt, $history) {
        $contents = [];
        $first_user_message = true;
        foreach ($history as $item) {
            $role = ($item['role'] === 'assistant' || $item['role'] === 'bot') ? 'model' : 'user';
            $content = $item['content'];
            if ($role === 'user' && $first_user_message) {
                $content = $system_prompt . "\n\n---\n\n" . $content;
                $first_user_message = false;
            }
            $contents[] = ['role' => $role, 'parts' => [['text' => $content]]];
        }
        return ['contents' => $contents];
    }
}