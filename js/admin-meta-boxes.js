'use strict';
jQuery(document).ready(function($) {

  /*
   * Attach the WP image uploader to the input field
   */
  // Instantiates the variable that holds the media library frame.
  var meta_image_frame;

  // Runs when the image button is clicked.
  $('#wpml_meta_base_layer_image_button').on('click', function(e) {

    e.preventDefault();

    // If the frame already exists, re-open it.
    if (meta_image_frame) {
      meta_image_frame.open();
      return;
    }

    // Sets up the media library frame, using variables from the admin PHP using wp_localize_script()
    meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
      title: wpml_meta_box_js_vars.title,
      button: { text: wpml_meta_box_js_vars.button },
      library: { type: 'image' }
    });

    // Runs when an image is selected.
    meta_image_frame.on('select', function() {
      // Grabs the attachment selection and creates a JSON representation of the model.
      var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
      // Sends the attachment URL to our custom image input field.
      $('#wpml_meta_base_layer_image').val(media_attachment.url);
    });

    // Opens the media library frame.
    meta_image_frame.open();
  });

  var wpml_meta_container = $('.wpml-meta-container');

  /*
   *  Handle hotspots
   */
  var wpml_media_area_container = $('.wpml-media-area-container .wpml-media-area');
  var wpml_media_wrapper_offset = wpml_media_area_container.offset();
  var wpml_media_wrapper_width = wpml_media_area_container.outerWidth();
  var wpml_media_wrapper_height = wpml_media_area_container.outerHeight();

  var wpml_hotspot_repeater = wpml_meta_container.find('.wpml-hotspot-repeater');
  var wpml_hotspots = wpml_hotspot_repeater.find(".wpml-hotspot");

  /* Handle hotspots in media area */
  var wpml_hotspot_handler = function() {
    wpml_media_area_container.find('.wpml-hotspot').each(function() {
      var hotspot = $(this);

      // Initial position
      hotspot.css({
        top: hotspot.data("hotspot-y") + "%",
        left: hotspot.data("hotspot-x") + "%"
      });

      // Make draggable
      hotspot.draggable({
        containment: "parent",
        stop:function(event,ui) {
          var pos = wpml_hotspot_position(ui.helper.offset());
          $(wpml_hotspots[hotspot.data("hotspot-id")]).find(".wpml_hotspot_x").val(pos.x_perc);
          $(wpml_hotspots[hotspot.data("hotspot-id")]).find(".wpml_hotspot_y").val(pos.y_perc);
        }
      });

    });
  }
  wpml_hotspot_handler();

  var wpml_hotspot_position = function(pos) {
    var x_px = pos.left - wpml_media_wrapper_offset.left;
    var y_px = pos.top - wpml_media_wrapper_offset.top;
    return {
      x_px: x_px,
      y_px: y_px,
      x_perc: (100 - ( ( ( wpml_media_wrapper_width - x_px ) / wpml_media_wrapper_width ) * 100 )).toFixed(2),
      y_perc: (100 - ( ( ( wpml_media_wrapper_height - y_px ) / wpml_media_wrapper_height ) * 100 )).toFixed(2)
    }
  }

  /* Handle add new hotspot */
  wpml_meta_container.find('#wpml_add_hotspot').on("click", function(e) {
    e.preventDefault();
    $.get(wpml_meta_box_js_vars.hotspot_row_partial, function( data ) {

      if(wpml_hotspots.length > 0) {
        wpml_hotspot_repeater.append( data );
      } else {
        wpml_hotspot_repeater.html( data );
      }

      wpml_hotspots = wpml_hotspot_repeater.find(".wpml-hotspot");
      wpml_hotspot_index_handler( wpml_hotspots );
      wpml_hotspot_remove_handler( wpml_hotspots )
    });
  });

  /* Handle hotspot indexes */
  var wpml_hotspot_index_handler = function(hotspots) {

    /* clear media area hotspots */
    wpml_media_area_container.find('.wpml-hotspot').remove();

    /* reindex repeater hotspots, and add media hotspots */
    var hotspot_i = 0;
    hotspots.each(function() {
      var hotspot = $(this);
      hotspot.find(".wpml-hotspot-id").html(hotspot_i + 1);
      hotspot.find(".wpml_hotspot_label").attr("name", "wpml_hotspot_label[" + hotspot_i + "]");
      hotspot.find(".wpml_hotspot_popup_text").attr("name", "wpml_hotspot_popup_text[" + hotspot_i + "]");
      hotspot.find(".wpml_hotspot_x").attr("name", "wpml_hotspot_x[" + hotspot_i + "]");
      hotspot.find(".wpml_hotspot_y").attr("name", "wpml_hotspot_y[" + hotspot_i + "]");

      wpml_media_area_container.prepend('<div class="wpml-hotspot" data-hotspot-id="' + hotspot_i + '" data-hotspot-x="' + hotspot.find(".wpml_hotspot_x").val() + '" data-hotspot-y="' + hotspot.find(".wpml_hotspot_y").val() + '">' + parseInt(hotspot_i + 1) + '</div>');

      hotspot_i++;
    });

    wpml_hotspot_handler();
  }

  /* Handle remove hotspot */
  var wpml_hotspot_remove_handler = function(hotspots) {
    hotspots.each(function() {
      var hotspot = $(this);
      hotspot.find('.wpml_remove_hotspot').on("click", function() {
        hotspot.remove();
        wpml_hotspot_index_handler( wpml_hotspot_repeater.find(".wpml-hotspot") );
      });
    });
  }
  wpml_hotspot_remove_handler(wpml_hotspots);


});
