'use strict';
jQuery(document).ready(function($) {

  var wpml_hotspots = $(".wpml-media-area .wpml-hotspot");

  wpml_hotspots.each(function() {
    var hotspot = $(this);

    var hotspot_index = hotspot.find(".wpml-hotspot-index");
    var hotspot_close = hotspot.find(".wpml-hotspot-close");

    hotspot_index.on("click", function() {
      hotspot.siblings().removeClass("active");
      hotspot.addClass("active");
    });

    hotspot_close.on("click", function() {
      hotspot.removeClass("active");
    });

  });

});
