<?php
/**
 * WP Media Layers
 *
 * @package   WPML
 * @license   GPL-2.0+
 */

 /**
  * Register post types and taxonomies.
  *
  * @package WPML
  */
class WPML_CPT_Registrations {

  public $post_type = 'wpml-media';
	public $taxonomies = array();

  public function init() {
    // Add the post types and taxonomies
		add_action( 'init', array( $this, 'register' ) );
	}

  /**
	 * Initiate registrations of post type and taxonomies.
	 *
	 * @uses WP_Media_Layers_Registrations::register_post_types()
	 * @uses WP_Media_Layers_Registrations::register_taxonomies()
	 */
  public function register() {
    $this->register_post_types();
		$this->register_taxonomies();
	}

  /**
	 * Register the custom post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	protected function register_post_types() {

  	$labels = array(
  		'name' => 'Layered Media',
  		'singular_name' => 'Layered Media',
  	);

    $labels = array(
    	'name'               => __( 'Layered Media', 'wpml-media' ),
    	'singular_name'      => __( 'Layered Media', 'wpml-media' ),
    	'add_new'            => __( 'Add Media', 'wpml-media' ),
    	'add_new_item'       => __( 'Add Media', 'wpml-media' ),
    	'edit_item'          => __( 'Edit Media', 'wpml-media' ),
    	'new_item'           => __( 'New Layered Media', 'wpml-media' ),
    	'view_item'          => __( 'View Layered Media', 'wpml-media' ),
    	'search_items'       => __( 'Search Layered Media', 'wpml-media' ),
    	'not_found'          => __( 'No Layered Media found', 'wpml-media' ),
    	'not_found_in_trash' => __( 'No Layered Media in the trash', 'wpml-media' ),
  	);
  	$supports = array(
  		'title'
  	);

    $args = array(
  		'labels'          => $labels,
  		'supports'        => $supports,
  		'public'          => true,
      'show_ui'         => true,
  		'has_archive'     => false,
  		'show_in_menu'    => true,
  		'exclude_from_search' => true,
      'query_var'       => false,
  		'capability_type' => 'post',
  		'rewrite'         => array( 'slug' => $this->post_type, ), // Permalinks format
  		'menu_position'   => 10,
  		'menu_icon'       => 'dashicons-admin-media',
  	);
  	$args = apply_filters( 'team_post_type_args', $args );
  	register_post_type( $this->post_type, $args );
  }

  /**
	 * Register a taxonomies
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	protected function register_taxonomies() {

	}


}
