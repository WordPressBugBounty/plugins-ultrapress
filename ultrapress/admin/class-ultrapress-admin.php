<?php
/**
 * The admin-specific functionality of the plugin.
 */
class Ultrapress_Admin {

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_seo_meta_box' ) );
        add_action( 'save_post', array( $this, 'save_seo_meta_box_data' ), 10, 2 );
    }

    /**
     * Adds the meta box container to post and page edit screens.
     */
    public function add_seo_meta_box() {
        add_meta_box(
            'ultrapress_seo_meta_box',
            'ultrapress SEO Settings',
            array( $this, 'render_seo_meta_box' ),
            array( 'post', 'page' ), // Apply to posts and pages
            'normal',
            'high'
        );
    }

    /**
     * Renders the HTML for the meta box.
     * @param WP_Post $post The post object.
     */
    public function render_seo_meta_box( $post ) {
        // Add a nonce field for security.
        wp_nonce_field( 'ultrapress_seo_meta_box', 'ultrapress_seo_meta_box_nonce' );

        // Get existing meta values.
        $seo_title = get_post_meta( $post->ID, '_ultrapress_seo_title', true );
        $meta_desc = get_post_meta( $post->ID, '_ultrapress_meta_desc', true );

        // Render the fields.
        echo '<p>';
        echo '<label for="ultrapress_seo_title"><strong>SEO Title</strong></label><br />';
        echo '<input type="text" id="ultrapress_seo_title" name="ultrapress_seo_title" value="' . esc_attr( $seo_title ) . '" style="width: 100%;" />';
        echo '<br /><small>The title for search engine results.</small>';
        echo '</p>';

        echo '<p>';
        echo '<label for="ultrapress_meta_desc"><strong>Meta Description</strong></label><br />';
        echo '<textarea id="ultrapress_meta_desc" name="ultrapress_meta_desc" rows="4" style="width: 100%;">' . esc_textarea( $meta_desc ) . '</textarea>';
        echo '<br /><small>The short description for search engine results.</small>';
        echo '</p>';
    }

    /**
     * Saves the meta data when the post is saved.
     *
     * @param int     $post_id The ID of the post being saved.
     * @param WP_Post $post    The post object.
     */
    public function save_seo_meta_box_data( $post_id, $post ) {
        // 1. Verify the nonce before proceeding.
        $nonce = isset( $_POST['ultrapress_seo_meta_box_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ultrapress_seo_meta_box_nonce'] ) ) : '';
        if ( ! wp_verify_nonce( $nonce, 'ultrapress_seo_meta_box' ) ) {
            return;
        }

        // 2. Ignore autosaves and revisions to prevent unintended updates.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        // 3. Check the user's permissions. This is the key security check.
        // We check if the user has the permission to edit this specific post or page.
        $post_type = get_post_type_object( $post->post_type );
        if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
             return;
        }

        // 4. Sanitize and save the SEO title.
        if ( isset( $_POST['ultrapress_seo_title'] ) ) {
            $seo_title = sanitize_text_field( wp_unslash( $_POST['ultrapress_seo_title'] ) );
            update_post_meta( $post_id, '_ultrapress_seo_title', $seo_title );
        }

        // 5. Sanitize and save the meta description.
        if ( isset( $_POST['ultrapress_meta_desc'] ) ) {
            $meta_desc = sanitize_textarea_field( wp_unslash( $_POST['ultrapress_meta_desc'] ) );
            update_post_meta( $post_id, '_ultrapress_meta_desc', $meta_desc );
        }
    }
}