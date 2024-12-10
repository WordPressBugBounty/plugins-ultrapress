<?php

/**
 * Debug utility class for Ultrapress
 *
 * @since      1.0.0
 * @package    Ultrapress
 * @subpackage Ultrapress/includes
 */

if (!defined('ABSPATH')) {
    exit;
}

class Ultrapress_Debug {
    /**
     * Whether debug mode is enabled
     *
     * @var bool
     */
    private static $debug_mode = false;

    /**
     * Initialize the debug system
     */
    public static function init() {
        self::$debug_mode = defined('WP_DEBUG') && WP_DEBUG;
    }

    /**
     * Log a debug message
     *
     * @param string $message The message to log
     * @param string $type The type of message (debug, info, warning, error)
     * @param array $context Additional context data
     */
    public static function log($message, $type = 'debug', $context = array()) {
        if (!self::$debug_mode) {
            return;
        }

        $log_entry = array(
            'timestamp' => current_time('mysql'),
            'type' => $type,
            'message' => $message,
            'context' => $context
        );

        // Log to WordPress debug.log
        error_log(sprintf(
            '[Ultrapress] [%s] %s | Context: %s',
            strtoupper($type),
            $message,
            json_encode($context)
        ));

        // Store in transient for admin display
        $logs = get_transient('ultrapress_debug_logs') ?: array();
        array_unshift($logs, $log_entry);
        $logs = array_slice($logs, 0, 100); // Keep only last 100 logs
        set_transient('ultrapress_debug_logs', $logs, HOUR_IN_SECONDS);
    }

    /**
     * Get all logged messages
     *
     * @return array
     */
    public static function get_logs() {
        return get_transient('ultrapress_debug_logs') ?: array();
    }

    /**
     * Clear all logged messages
     */
    public static function clear_logs() {
        delete_transient('ultrapress_debug_logs');
    }

    /**
     * Add admin notice
     *
     * @param string $message The notice message
     * @param string $type The notice type (error, warning, success, info)
     */
    public static function add_admin_notice($message, $type = 'info') {
        add_action('admin_notices', function() use ($message, $type) {
            printf(
                '<div class="notice notice-%s is-dismissible"><p>%s</p></div>',
                esc_attr($type),
                esc_html($message)
            );
        });
    }
}

// Initialize debug system
Ultrapress_Debug::init();
