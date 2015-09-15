<?php
  if ( !defined('ABSPATH') ) {

    /* Get document root */
    $current_script = getenv("SCRIPT_NAME");
    $current_path = str_replace( "/" . basename(__FILE__), "", $current_script );
    $absolute_path = str_replace("\\","/",__DIR__); // a fix for Windows slashes
    $doc_root = substr($absolute_path, 0, strpos($absolute_path,$current_path));

    /* Set up WordPress environment */
    require_once $doc_root . '/wp-load.php';
  }
  $hotspot_i = isset($hotspot_i) ? $hotspot_i : null;

  $hotpost_id_tag_pre = isset($hotspot_i) ? "wpml_hotspot_{$hotspot_i}_" : null;
  $hotpost_label_id_tag = isset($hotpost_id_tag_pre) ? "{$hotpost_id_tag_pre}label" : null;
  $hotpost_popup_text_id_tag = isset($hotpost_id_tag_pre) ? "{$hotpost_id_tag_pre}popup_text" : null;
  $hotpost_hotspot_x_id_tag = isset($hotpost_id_tag_pre) ? "{$hotpost_id_tag_pre}hotspot_x" : null;
  $hotpost_hotspot_y_id_tag = isset($hotpost_id_tag_pre) ? "{$hotpost_id_tag_pre}hotspot_y" : null;

  $hotspot_label = isset($hotspot_label) ? $hotspot_label : null;
  $hotspot_popup_text = isset($hotspot_popup_text) ? $hotspot_popup_text : null;
  $hotspot_x = isset($hotspot_x) ? $hotspot_x : 0;
  $hotspot_y = isset($hotspot_y) ? $hotspot_y : 0;
?>
<div class="row wpml-hotspot">
  <div class="col-sm-12">
    <div class="table-responsive">
      <table class="table">
        <tr>
          <td rowspan="3" class="wpml-hotspot-id">
            <?php echo $hotspot_i + 1; ?>
          </td>
          <td class="wpml-label-cell">
            <label for="<?php echo $hotpost_label_id_tag; ?>">
              <?php _e( 'Hotspot Label', 'wpml-media' )?>
            </label>
          </td>
          <td colspan="2">
            <input type="text" id="<?php echo $hotpost_label_id_tag; ?>" name="wpml_hotspot_label[<?php echo $hotspot_i; ?>]" class="wpml_hotspot_label" value="<?php echo $hotspot_label; ?>" />
          </td>
          <td rowspan="3" class="wpml-hotspot-content">
            <label for="<?php echo $hotpost_popup_text_id_tag; ?>">
              <?php _e( 'Hotspot Popup Text', 'wpml-media' )?>
            </label>
            <textarea id="<?php echo $hotpost_popup_text_id_tag; ?>" name="wpml_hotspot_popup_text[<?php echo $hotspot_i; ?>]" class="wpml_hotspot_popup_text" class="text-area" rows="3"><?php echo $hotspot_popup_text; ?></textarea>
          </td>
          <td rowspan="3" class="wpml-hotspot-remove">
            <i class="glyphicon glyphicon-remove wpml_remove_hotspot"></i>
          </td>
        </tr>
        <tr>
          <td class="wpml-label-cell">
            <label>
              <?php _e( 'Coordinates', 'wpml-media' )?> <br />
            </label>
          </td>
          <td>
            <label for="<?php echo $hotpost_hotspot_x_id_tag; ?>">
              <?php _e( 'X-axis (%)', 'wpml-media' )?>
            </label>
            <input type="text" id="<?php echo $hotpost_hotspot_x_id_tag; ?>" name="wpml_hotspot_x[<?php echo $hotspot_i; ?>]" class="wpml_hotspot_x"  value="<?php echo $hotspot_x; ?>" />
          </td>
          <td>
            <label for="<?php echo $hotpost_hotspot_y_id_tag; ?>">
              <?php _e( 'Y-axis (%)', 'wpml-media' )?>
            </label>
            <input type="text" id="<?php echo $hotpost_hotspot_y_id_tag; ?>" name="wpml_hotspot_y[<?php echo $hotspot_i; ?>]" class="wpml_hotspot_y" value="<?php echo $hotspot_y; ?>" />
          </td>
        </tr>
        <tr>
          <td colspan="3" class="wpml-coord-desc">
            <small><?php _e( 'Coordinates from top-left, for example top-left = 0% X,0% Y', 'wpml-media' )?></small>
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>
