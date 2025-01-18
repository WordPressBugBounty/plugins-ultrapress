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

<div style="margin: 20px; padding: 20px; border: 3px solid #2271b1; background: #e6f7f7; border-radius: 8px; animation: pulseBox 3s infinite;">
  <h2 style="color: #2271b1; font-size: 1.8rem; margin-bottom: 10px;">
    AI Agents Are Coming Soon to Ultrapress!
  </h2>
  <p style="font-size: 1.1rem; color: #444; line-height: 1.6;">
    We’re gearing up for a revolutionary update that brings advanced AI to WordPress. 
    <strong>Generate dynamic content, translate posts instantly, summarize pages, and more</strong>—all 
    from your dashboard with no coding required. 
  </p>
  <p style="font-size: 1.1rem; color: #444; font-weight: bold;">
    Keep Ultrapress active and stay tuned. The new era of AI-powered WordPress websites is closer than you think!
  </p>
</div>

<!-- Example simple keyframes for subtle animation, you can put this in a <style> block or CSS file -->
<style>
@keyframes pulseBox {
  0% { transform: scale(1); box-shadow: 0 0 10px rgba(0,0,0,0.1); }
  50% { transform: scale(1.02); box-shadow: 0 0 20px rgba(34,113,177,0.3); }
  100% { transform: scale(1); box-shadow: 0 0 10px rgba(0,0,0,0.1); }
}
</style>


<div class="wrap ultrapress-admin">
    <div class="ultrapress-dashboard-content">
        <div class="ultrapress-welcome-section">
            <h2><?php echo esc_html__('Welcome to Ultrapress', 'ultrapress'); ?></h2>
            <div class="ultrapress-welcome-content">
                <div class="ultrapress-video-container">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/mQlrCUliSTM?si=6711Gb5TejIwa6sa" title="UltraPress Tutorial" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
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
