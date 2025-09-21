<?php
if (!defined('ABSPATH')) exit;

wp_nonce_field('ultrapress_save_seo_meta', 'ultrapress_seo_nonce');

$settings = get_option('ultrapress_settings', []);

// --- Get saved meta values (no more auto-population) ---
$seo_title = get_post_meta($post->ID, '_ultrapress_seo_title', true);
$meta_description = get_post_meta($post->ID, '_ultrapress_meta_description', true);
$focus_keyword = get_post_meta($post->ID, '_ultrapress_focus_keyword', true);

$is_api_configured = !empty($settings[$settings['api_provider'] . '_api_key']);
?>

<style>
    /* Basic styling for the meta box for better readability */
    .ultrapress-meta-field { margin-bottom: 20px; }
    .ultrapress-meta-field label { display: block; font-weight: 600; margin-bottom: 5px; }
    .ultrapress-meta-field input, .ultrapress-meta-field textarea { width: 100%; }
    .ultrapress-meta-field .description { font-style: italic; color: #666; }
    #ultrapress-generate-seo-btn { margin-top: 5px; }
    .ultrapress-char-counter { text-align: right; font-size: 12px; color: #666; margin-top: 2px; }
</style>

<div class="ultrapress-meta-box-wrapper">
    <!-- Field 1: Focus Keyword -->
    <div class="ultrapress-meta-field">
        <label for="_ultrapress_focus_keyword"><?php esc_html_e('Focus Keyword (Optional)', 'ultrapress'); ?></label>
        <input type="text" id="_ultrapress_focus_keyword" name="_ultrapress_focus_keyword" value="<?php echo esc_attr($focus_keyword); ?>" autocomplete="off">
        <p class="description"><?php esc_html_e('Provide a keyword to focus the AI generation. If left empty, the AI will suggest one.', 'ultrapress'); ?></p>
    </div>

    <!-- Field 2: SEO Title -->
    <div class="ultrapress-meta-field">
        <label for="_ultrapress_seo_title"><?php esc_html_e('SEO Title', 'ultrapress'); ?></label>
        <input type="text" id="_ultrapress_seo_title" name="_ultrapress_seo_title" value="<?php echo esc_attr($seo_title); ?>" autocomplete="off">
        <div class="ultrapress-char-counter" data-target="_ultrapress_seo_title" data-limit="60"><span>0</span> / 60</div>
    </div>

    <!-- Field 3: Meta Description -->
    <div class="ultrapress-meta-field">
        <label for="_ultrapress_meta_description"><?php esc_html_e('Meta Description', 'ultrapress'); ?></label>
        <textarea id="_ultrapress_meta_description" name="_ultrapress_meta_description" rows="4"><?php echo esc_textarea($meta_description); ?></textarea>
        <div class="ultrapress-char-counter" data-target="_ultrapress_meta_description" data-limit="160"><span>0</span> / 160</div>
    </div>

    <!-- AI Generation Button or Notification -->
    <div class="ultrapress-meta-field">
        <?php if ($is_api_configured) : ?>
            <button type="button" id="ultrapress-generate-seo-btn" class="button button-primary">
                <span class="dashicons dashicons-superhero-alt" style="vertical-align: text-bottom; margin-right: 5px;"></span>
                <?php esc_html_e('Generate with AI', 'ultrapress'); ?>
            </button>
            <span class="spinner" style="float: none; vertical-align: middle; margin-left: 5px;"></span>
        <?php else : ?>
            <div class="notice notice-warning inline">
                <p>
                    <?php
                    printf(
                        wp_kses(
                            __('💡 To generate with AI, please <a href="%s" target="_blank">add your API key</a> in the AI Brain settings.', 'ultrapress'),
                            ['a' => ['href' => [], 'target' => []], 'strong' => []]
                        ),
                        esc_url(admin_url('admin.php?page=ultrapress-ai-brain'))
                    );
                    ?>
                </p>
            </div>
        <?php endif; ?>
    </div>

</div>