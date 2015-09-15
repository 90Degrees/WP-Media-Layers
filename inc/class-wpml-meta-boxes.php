<?php
/**
 * WP Media Layers
 *
 * @package   WPML
 * @license   GPL-2.0+
 */

/**
 * Register metaboxes
 *
 * @since 0.1.0
 */
class WPML_Meta_Boxes {

	protected $registration_handler;
	public function __construct( $registration_handler ) {
		$this->registration_handler = $registration_handler;
	}

	public function init() {
		add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ),  10, 2 );
	}

	/**
	 * Register the metaboxes to be used for the team post type
	 *
	 * @since 0.1.0
	 */
	public function register_meta_boxes() {
		add_meta_box(
			'wpml_meta',
			__( 'Media Layers', 'wpml-media' ),
			array( $this, 'render_meta_boxes' ),
			$this->registration_handler->post_type,
			'normal',
			'high'
		);
	}

	/**
	* The HTML for the fields
	*
	* @since 0.1.0
	*/
	function render_meta_boxes( $post ) {

		wp_nonce_field( basename( __FILE__ ), 'wpml_meta' );
    $wpml_stored_meta = get_post_meta( $post->ID );
		$wpml_hotspots = !empty($wpml_stored_meta['wpml_meta_hotspots'][0]) ? unserialize($wpml_stored_meta['wpml_meta_hotspots'][0]) : array();

    ?>

		<div class="wpml-meta-container">

			<div class="wpml-meta-box container-fluid">
				<div class="row">
					<div class="col-sm-12">
						<h2><?php _e( 'Base Layer Image', 'wpml-media' )?></h2>
						<span class="description"><?php _e( 'This is the lowest of the layers in your media area', 'wpml-media' ); ?></span>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 col-md-9">
						<input type="text" name="wpml_meta_base_layer_image" id="wpml_meta_base_layer_image" value="<?php if ( isset ( $wpml_stored_meta['wpml_meta_base_layer_image'] ) ) { echo $wpml_stored_meta['wpml_meta_base_layer_image'][0]; } ?>" />
					</div>
					<div class="col-sm-6 col-md-3">
						<input type="button" id="wpml_meta_base_layer_image_button" class="btn btn-primary btn-sm" value="<?php _e( 'Choose/upload image', 'wpml-media' )?>" />
					</div>
				</div>
			</div>

			<div class="wpml-meta-box wpml-media-area-container container-fluid">
				<div class="row">
					<div class="col-sm-12">
						<h2><?php _e( 'Preview Area', 'wpml-media' )?></h2>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<?php if ( isset( $wpml_stored_meta['wpml_meta_base_layer_image']) && !empty ( $wpml_stored_meta['wpml_meta_base_layer_image'][0] ) ) { ?>

							<div class="wpml-media-area" id="#wpml_media_preview_area">

								<?php for($i=0; $i<count($wpml_hotspots); $i++) { ?>
									<div class="wpml-hotspot" data-hotspot-id="<?php echo $i; ?>" data-hotspot-x="<?php echo $wpml_hotspots[$i]['x']; ?>" data-hotspot-y="<?php echo $wpml_hotspots[$i]['y']; ?>">
										<?php echo $i + 1; ?>
									</div>
								<?php } ?>

								<img class="wpml-media-area-base" src="<?php echo $wpml_stored_meta['wpml_meta_base_layer_image'][0]; ?>" alt="<?php echo $post->post_title; ?>" />
							</div>

						<?php } else { ?>
							<span class="wpml-alert wpml-alert-warning"><?php _e( 'Save/Update to see media preview.', 'wpml-media' ); ?></span>
						<?php } ?>

					</div>
				</div>
			</div>

			<div class="wpml-meta-box wpml-hotspot-container container-fluid">
				<div class="row">
					<div class="col-sm-12">
						<h2><?php _e( 'Hotspots', 'wpml-media' )?></h2>
					</div>
				</div>

				<div class="wpml-hotspot-repeater">

					<?php if( is_array($wpml_hotspots) ) { ?>

							<?php
								for($i=0; $i<count($wpml_hotspots); $i++) {
									$hotspot_i = $i;
									$hotspot_label = $wpml_hotspots[$i]['label'];
							    $hotspot_popup_text = $wpml_hotspots[$i]['popup_text'];
							    $hotspot_x = $wpml_hotspots[$i]['x'];
							    $hotspot_y = $wpml_hotspots[$i]['y'];
									include plugin_dir_path(__DIR__) . 'partials/admin-hotspot-row.php';
								}
							?>

					<?php } else { ?>

						<div class="row">
							<div class="col-sm-12">
								No hotspots, add one by clicking "Add hotspot"
							</div>
						</div>

					<?php } ?>

				</div>

				<div class="row">
					<div class="col-sm-12">
						<span class="btn btn-primary btn-sm pull-right" id="wpml_add_hotspot">Add hotspot</span>
					</div>
				</div>

			</div>

		</div>

    <?php
	}

	/**
	 * Save metaboxes
	 *
	 * @since 0.1.0
	 */
	function save_meta_boxes( $post_id ) {
		global $post;

		// Verify nonce
		if ( !isset( $_POST['wpml_meta'] ) || !wp_verify_nonce( $_POST['wpml_meta'], basename(__FILE__) ) ) {
			return $post_id;
		}

		// Check Autosave
		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		// Don't save if only a revision
		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		// Check permissions
		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		// Checks for base layer image input and saves if needed
		if( isset( $_POST[ 'wpml_meta_base_layer_image' ] ) ) {
			update_post_meta( $post_id, 'wpml_meta_base_layer_image', esc_url_raw($_POST[ 'wpml_meta_base_layer_image' ]) );
		}

		// Loop through hotspots, serialize and save
		$hotspot_label_arr = isset($_POST['wpml_hotspot_label']) ? $_POST['wpml_hotspot_label'] : array();
    $hotspot_popup_text_arr = isset($_POST['wpml_hotspot_popup_text']) ? $_POST['wpml_hotspot_popup_text'] : array();
    $hotspot_x_arr = isset($_POST['wpml_hotspot_x']) ? $_POST['wpml_hotspot_x'] : array();
    $hotspot_y_arr = isset($_POST['wpml_hotspot_y']) ? $_POST['wpml_hotspot_y'] : array();
		$hotspots = array();

		for($i=0; $i<count($hotspot_label_arr); $i++) {
			$hotspots[$i]['label'] = !empty($hotspot_label_arr[$i]) ? esc_html($hotspot_label_arr[$i]) : "";
			$hotspots[$i]['popup_text'] = !empty($hotspot_popup_text_arr[$i]) ? esc_html($hotspot_popup_text_arr[$i]) : "";

			//filter all non-numeric and full-stop characters
			$hotspots[$i]['x'] = !empty($hotspot_x_arr[$i]) ? number_format ( preg_replace("/[^0-9.]/", "", trim($hotspot_x_arr[$i])), 2 ) : 0;
			$hotspots[$i]['y'] = !empty($hotspot_y_arr[$i]) ? number_format ( preg_replace("/[^0-9.]/", "", trim($hotspot_y_arr[$i])), 2 ) : 0;
		}
		update_post_meta( $post_id, 'wpml_meta_hotspots', $hotspots );

	}


}
