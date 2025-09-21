<?php
/**
 * Handles plugin activation tasks, primarily creating the database table for conversations.
 *
 * @package UltraPress
 * @since 5.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Creates the custom database table for storing chatbot conversations upon plugin activation.
 *
 * This function is hooked to 'register_activation_hook' in the main plugin file.
 */
function ultrapress_create_table() {
    global $wpdb;

    // Use the new, branded table name.
    $table_name = $wpdb->prefix . 'ultrapress_conversations';
    
    // Get the character set and collation for the database.
    $charset_collate = $wpdb->get_charset_collate();

    // The SQL query to create the table.
    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        user_message TEXT NOT NULL,
        bot_response TEXT NOT NULL,
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    // We need to include this file to use dbDelta().
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    // dbDelta() is the recommended WordPress function to execute table creation/updates.
    // It checks if the table exists and what the current structure is before making changes.
    dbDelta($sql);
}