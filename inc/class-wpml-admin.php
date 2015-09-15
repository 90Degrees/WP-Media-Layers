<?php
/**
 * WP Media Layers
 *
 * @package   WPML
 * @license   GPL-2.0+
 */

/**
 * Handle admin and plugin settings
 *
 * @package WPML
 */
class WPML_Admin {

	protected $registration_handler;
	public function __construct( $registration_handler ) {
		$this->registration_handler = $registration_handler;
	}

	public function init() {
		// Add shortcode to column view
		add_filter( 'manage_edit-' . $this->registration_handler->post_type . '_columns', array( $this, 'add_shortcode_column'), 10, 1 );
		add_action( 'manage_' . $this->registration_handler->post_type . '_posts_custom_column', array( $this, 'display_shortcode' ), 10, 1 );

		// Add admin scripts and styles
		add_action( 'admin_print_scripts-post-new.php', array($this, 'admin_scripts_and_styles'), 11 );
		add_action( 'admin_print_scripts-post.php', array($this, 'admin_scripts_and_styles'), 11 );
	}

	/**
	 * Adds the meta box scripts and styles
	 */
	public function admin_scripts_and_styles(){
    global $post_type;
    if( $post_type == $this->registration_handler->post_type ) {

			// Load vendor styles and scripts
			wp_enqueue_style( 'bootstrap-styles', plugin_dir_url(__DIR__) . 'vendor/bootstrap/css/bootstrap.min.css' );
			wp_enqueue_script( 'bootstrap-script', plugin_dir_url(__DIR__) . 'vendor/bootstrap/js/bootstrap.min.js', array( 'jquery' ) );

			// Load meta box styles and scripts
      wp_enqueue_style( 'wpml_meta_box_styles', plugin_dir_url(__DIR__) . 'css/admin-meta-boxes.css' );
			wp_enqueue_media(); // WP image management JS
			wp_register_script( // Meta box scripts and to pass our javascript some variables we use wp_localize_script()
				'wpml_meta_box_scripts',
				plugin_dir_url(__DIR__) . 'js/admin-meta-boxes.js',
				array(
					'jquery',
					'jquery-ui-core',
					'jquery-ui-draggable'
				)
			);
			wp_localize_script( 'wpml_meta_box_scripts', 'wpml_meta_box_js_vars',
				array(
					'title' 		=> __( 'Choose or Upload an Image', 'wpml-media' ),
					'button' 		=> __( 'Use this image', 'wpml-media' ),
					'hotspot_row_partial' => plugin_dir_url(__DIR__) . 'partials/admin-hotspot-row.php'
				)
			);
			wp_enqueue_script( 'wpml_meta_box_scripts' );

    }
	}

	/**
	 * Add columns to post type list screen.
	 *
	 * @link http://wptheming.com/2010/07/column-edit-pages/
	 * @param array $columns Existing columns.
	 * @return array Amended columns.
	 */
	public function add_shortcode_column( $columns ) {
		$column_shortcode = array( 'shortcode' => __( 'Shortcode', 'wpml-media' ) );
		return array_slice( $columns, 0, 2, true ) + $column_shortcode + array_slice( $columns, 1, null, true );
	}

	/**
	 * Custom column callback
	 *
	 * @global stdClass $post Post object.
	 * @param string $column Column ID.
	 */
	public function display_shortcode( $column ) {
		// global $post;
		switch ( $column ) {
			case 'shortcode':
				echo '<code>[WPML id="' . get_the_ID(). '"]</code>';
				break;
		}
	}




}
