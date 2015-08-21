<?php
/*
Plugin Name: WP Media Layers
Plugin URI: https://github.com/90Degrees/wp-media-layers
Description:
Author: Dominic Fallows @ 90 Degrees
Version: 0.1
Author URI: https://github.com/dominicfallows
*/


/**
 * Adds the meta box stylesheet when appropriate
 */
function wpml_admin_styles(){
    global $typenow;
    if( $typenow == 'post' ) {
        wp_enqueue_style( 'wpml_meta_box_styles', plugin_dir_url( __FILE__ ) . 'styles.css' );
    }
}
add_action( 'admin_print_styles', 'wpml_admin_styles' );

/**
 * Adds a meta box to the post editing screen
 */
function wpml_custom_meta() {
    add_meta_box( 'wpml_meta', __( 'Meta Box Title', 'wpml-textdomain' ), 'wpml_meta_callback', 'post' );
}
add_action( 'add_meta_boxes', 'wpml_custom_meta' );


/**
 * Outputs the content of the meta box
 */
function wpml_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'wpml_nonce' );
    $wpml_stored_meta = get_post_meta( $post->ID );
    ?>

    <p>
        <label for="meta-text" class="wpml-row-title"><?php _e( 'Example Text Input', 'wpml-textdomain' )?></label>
        <input type="text" name="meta-text" id="meta-text" value="<?php if ( isset ( $wpml_stored_meta['meta-text'] ) ) echo $wpml_stored_meta['meta-text'][0]; ?>" />
    </p>

    <?php
}





/**
 * Saves the custom meta input
 */
function wpml_meta_save( $post_id ) {

    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'wpml_nonce' ] ) && wp_verify_nonce( $_POST[ 'wpml_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'meta-text' ] ) ) {
        update_post_meta( $post_id, 'meta-text', sanitize_text_field( $_POST[ 'meta-text' ] ) );
    }

}
add_action( 'save_post', 'wpml_meta_save' );



// Retrieves the stored value from the database
    $meta_value = get_post_meta( get_the_ID(), 'meta-text', true );

    // Checks and displays the retrieved value
    if( !empty( $meta_value ) ) {
        echo $meta_value;
    }
