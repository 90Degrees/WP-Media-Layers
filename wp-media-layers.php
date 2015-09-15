<?php
/**
 * WP Media Layers
 *
 * @package   WPML
 * @license   GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:   WP Media Layers
 * Plugin URI:    https://github.com/90Degrees/wp-media-layers
 * Description:
 * Version:       0.1.0
 * Author:        Dominic Fallows
 * Author URI:    https://github.com/dominicfallows
 * Text Domain:   wpml-media
 * License:       GPL-2.0+
 * License URI:   http://www.gnu.org/licenses/gpl-2.0.txt
 */
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Required files
require plugin_dir_path( __FILE__ ) . 'inc/class-wpml.php';
require plugin_dir_path( __FILE__ ) . 'inc/class-wpml-custom-post-types.php';
require plugin_dir_path( __FILE__ ) . 'inc/class-wpml-meta-boxes.php';
require plugin_dir_path( __FILE__ ) . 'inc/class-wpml-front-end.php';

// Instantiate registration class, so we can add it as a dependency to main plugin class.
$wpml_registrations = new WPML_CPT_Registrations;

// Instantiate main plugin file, so activation callback does not need to be static.
$post_type = new WPML( $wpml_registrations );

// Register callback that is fired when the plugin is activated.
register_activation_hook( __FILE__, array( $post_type, 'activate' ) );

// Initialize registrations for post-activation requests.
$wpml_registrations->init();

// Initialize metaboxes
$post_type_metaboxes = new WPML_Meta_Boxes( $wpml_registrations );
$post_type_metaboxes->init();

// Initialize front-end handler
$front_end = new WPML_Front_End();
$front_end->init();

/**
 * Handle admin and plugin settings
 */
if ( is_admin() ) {
	require plugin_dir_path( __FILE__ ) . 'inc/class-wpml-admin.php';
	$wpml_admin = new WPML_Admin( $wpml_registrations );
	$wpml_admin->init();
}
