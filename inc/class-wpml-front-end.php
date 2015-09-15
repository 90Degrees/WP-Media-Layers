<?php
/**
 * WP Media Layers
 *
 * @package   WPML
 * @license   GPL-2.0+
 */

 /**
  * Handle front-end.
  *
  * @package WPML
  */
class WPML_Front_End {

  public function init() {
    // Add the shortcode
		add_shortcode( 'WPML', array( $this, 'shortcode_handler' ) );

    //Enqueue the front-end styles and scripts
    add_action( 'wp_enqueue_scripts', array( $this, 'front_end_scripts_and_styles' ) );
	}

  /**
	 * Adds the scripts and styles to the front-end
	 */
	public function front_end_scripts_and_styles(){

    if(!is_admin()) {

			// Load meta box styles and scripts
      wp_enqueue_style( 'wpml_styles', plugin_dir_url(__DIR__) . 'css/public.css' );
			wp_enqueue_script( 'wpml_scripts', plugin_dir_url(__DIR__) . 'js/public.js', array( 'jquery' ), '', true );

    }
	}

  /**
	 * Register shortcode
   */
	public function shortcode_handler( $atts, $content = null ) {

    global $post;

    $a = shortcode_atts( array(
        'id' => '',
    ), $atts );


    $wpml_object = get_post($a['id']);

    if(empty($wpml_object)) {
      return false;
    }

    $wpml_stored_meta = get_post_meta( $wpml_object->ID );
		$wpml_hotspots = !empty($wpml_stored_meta['wpml_meta_hotspots'][0]) ? unserialize($wpml_stored_meta['wpml_meta_hotspots'][0]) : array();

    ob_start();
  	?><div class="wpml-media-area" id="#wpml_media_area_<?php echo $a['id']; ?>">

      <?php for($i=0; $i<count($wpml_hotspots); $i++) { ?>
        <div class="wpml-hotspot"
          data-hotspot-id="<?php echo $i; ?>"
          style="left:<?php echo $wpml_hotspots[$i]['x']; ?>%; top:<?php echo $wpml_hotspots[$i]['y']; ?>%;"
          >
          <div class="wpml-hotspot-index">
            <?php echo $i + 1; ?>
          </div>
          <div class="wpml-hotspot-popup">
            <span class="wpml-hotspot-label">
              <?php echo $wpml_hotspots[$i]['label']; ?>
            </span>
            <span class="wpml-hotspot-popup-text">
              <?php echo $wpml_hotspots[$i]['popup_text']; ?>
            </span>
            <span class="wpml-hotspot-close">&times;</span>
          </div>
        </div>
      <?php } ?>

      <img class="wpml-media-area-base" src="<?php echo $wpml_stored_meta['wpml_meta_base_layer_image'][0]; ?>" alt="<?php echo $wpml_object->post_title; ?>" />

    </div><?php
  	return ob_get_clean();
  }

}
