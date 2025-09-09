<?php
/**
 * The public-facing functionality of the plugin.
 */
class Ultrapress_Public {

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        add_filter( 'pre_get_document_title', array( $this, 'filter_document_title' ), 20 );
        add_action( 'wp_head', array( $this, 'add_meta_description' ) );
    }

    /**
     * Filters the title of the page if a custom SEO title is set.
     * @param string $title The original title.
     * @return string The modified title.
     */
    public function filter_document_title( $title ) {
        if ( is_singular() ) {
            $post_id = get_queried_object_id();
            if ( $post_id ) {
                $seo_title = get_post_meta( $post_id, '_ultrapress_seo_title', true );
                if ( ! empty( $seo_title ) ) {
                    return esc_html( $seo_title );
                }
            }
        }
        return $title;
    }

    /**
     * Adds the meta description to the document head. [1]
     */
    public function add_meta_description() {
        if ( is_singular() ) {
            $post_id = get_queried_object_id();
            if ( $post_id ) {
                $meta_desc = get_post_meta( $post_id, '_ultrapress_meta_desc', true );
                if ( ! empty( $meta_desc ) ) {
                    echo '<meta name="description" content="' . esc_attr( $meta_desc ) . '" />' . "\n";
                }
            }
        }
    }
}