<?php
/**
 * Provide a admin area view for the plugin's dashboard
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Ultrapress
 * @subpackage Ultrapress/admin/partials
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap ultrapress-admin">
    <div class="ultrapress-dashboard-content">
        <div class="ultrapress-welcome-section">
            <h2><?php echo esc_html__('Welcome to Ultrapress', 'ultrapress'); ?></h2>
            <div class="ultrapress-welcome-content">
                <p><?php echo esc_html__('Ultrapress is a powerful suite of integrated tools for WordPress developers and users. Build plugins, features, and circuits without writing a single line of code.', 'ultrapress'); ?></p>
                <p><?php echo esc_html__('Think of Ultrapress as Elementor for plugins - it lets you create complex functionalities using a visual scripting system, making plugin development accessible to everyone.', 'ultrapress'); ?></p>
            </div>
        </div>

        <div class="ultrapress-features-grid">
            <div class="ultrapress-feature-card">
                <h3><?php echo esc_html__('Visual Circuit Builder', 'ultrapress'); ?></h3>
                <p><?php echo esc_html__('Create plugins by connecting components visually, just like building with blocks. No coding required!', 'ultrapress'); ?></p>
            </div>
            
            <div class="ultrapress-feature-card">
                <h3><?php echo esc_html__('Component System', 'ultrapress'); ?></h3>
                <p><?php echo esc_html__('Use pre-built components for common WordPress tasks like adding posts, comments, or custom features.', 'ultrapress'); ?></p>
            </div>
            
            <div class="ultrapress-feature-card">
                <h3><?php echo esc_html__('Plugin Integration', 'ultrapress'); ?></h3>
                <p><?php echo esc_html__('Easily combine functionalities from different plugins into a single workflow without complex coding.', 'ultrapress'); ?></p>
            </div>
        </div>

        <div class="ultrapress-getting-started">
            <h3><?php echo esc_html__('Getting Started', 'ultrapress'); ?></h3>
            <ul class="ultrapress-steps">
                <li><?php echo esc_html__('Create a new circuit from the Circuits page', 'ultrapress'); ?></li>
                <li><?php echo esc_html__('Add components and connect them to create your desired functionality', 'ultrapress'); ?></li>
                <li><?php echo esc_html__('Test your circuit using the built-in testing tools', 'ultrapress'); ?></li>
                <li><?php echo esc_html__('Export your circuit as a standalone plugin if needed', 'ultrapress'); ?></li>
            </ul>
        </div>

        <div class="ultrapress-quick-links">
            <h3><?php echo esc_html__('Quick Links', 'ultrapress'); ?></h3>
            <a href="<?php echo esc_url(admin_url('admin.php?page=ultrapress')); ?>" class="button button-primary">
                <?php echo esc_html__('Manage Circuits', 'ultrapress'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=ultrapress/components.php')); ?>" class="button button-primary">
                <?php echo esc_html__('Manage Components', 'ultrapress'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=ultrapress/packages.php')); ?>" class="button button-primary">
                <?php echo esc_html__('Manage Packages', 'ultrapress'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=ultrapress/add.php')); ?>" class="button button-primary">
                <?php echo esc_html__('Create New', 'ultrapress'); ?>
            </a>
        </div>
    </div>
</div>
