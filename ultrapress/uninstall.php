<?php
/**
 * UltraPress Uninstall
 *
 * This file is triggered when the user deletes the UltraPress plugin.
 * It is responsible for cleaning up all plugin-specific data from the database
 * to ensure a clean removal.
 *
 * @package UltraPress
 * @since 5.0.0
 */

// If uninstall.php is not called by WordPress, die.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// --- 1. Delete Plugin Options ---

// The primary option where all settings are stored.
$option_name = 'ultrapress_settings';
delete_option($option_name);

// For multisite installations, delete the option from all sites.
if (is_multisite()) {
    global $wpdb;
    $blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);
    if ($blogs) {
        foreach ($blogs as $blog) {
            switch_to_blog($blog['blog_id']);
            delete_option($option_name);
            restore_current_blog();
        }
    }
}


// --- 2. Delete Custom Database Tables ---

global $wpdb;
$conversations_table = $wpdb->prefix . 'ultrapress_conversations';
$wpdb->query("DROP TABLE IF EXISTS {$conversations_table}");


// --- 3. Delete Post Meta (Optional but good practice) ---

// If you have many users, this query can be slow. It's good practice
// for a complete cleanup.
$wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE '_ultrapress_%'");