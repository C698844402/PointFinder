(function($) {
  "use strict";
  var PFSinglePageMap = {
      init: function(container,markertype){
        if ( $('#'+container).length > 0 ) {

          $.pointfindersingleauthormap = $.pointfinderbuildmap(container,markertype);
         
          var defaultloc = pfthemesm.pfstviewcor.split( ',' );

          var we_lat = parseFloat(defaultloc[0]);
          var we_lng = parseFloat(defaultloc[1]);

          if (markertype == 1) {
            this.addMarker(we_lat,we_lng);
          } else {
            this.addMarkerx(we_lat,we_lng);
          }
        }
      },
      addMarker: function(we_lat,we_lng){

        var marker = L.marker(
          L.latLng(we_lat,we_lng),
          {
            riseOnHover: true,
            bubblingMouseEvents: true
          }
        );

        $.pointfindersingleauthormap.addLayer(marker);
      },
      addMarkerx: function(we_lat,we_lng){

        var marker = L.marker(
          L.latLng(we_lat,we_lng),
          {
            riseOnHover: true,
            bubblingMouseEvents: true
          }
        );

        $.pointfindersingleauthormap.addLayer(marker);
      }

    }

    $(function(){

      if ($('#pf-authorpage-header-map').length > 0) {
        PFSinglePageMap.init('pf-authorpage-header-map',1);
      }

    });

})(jQuery);