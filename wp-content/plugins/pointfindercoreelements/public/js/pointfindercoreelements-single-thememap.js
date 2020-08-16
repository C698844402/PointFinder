(function($) {
  "use strict";
  var PFSinglePageMap = {
      init: function(container,markertype){
        if ( $('#'+container).length > 0 ) {

          $.pointfindersinglepagemap = $.pointfinderbuildmap(container,markertype);
         
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

        $.pointfindersinglepagemap.addLayer(marker);
      },
      addMarkerx: function(we_lat,we_lng){

        var marker = L.marker(
          L.latLng(we_lat,we_lng),
          {
            riseOnHover: true,
            bubblingMouseEvents: true
          }
        );

        $.pointfindersinglepagemap.addLayer(marker);
      },
      initStView: function(){
        $('#pf-itempage-header-streetview').css('height',''+pfthemesm.street_view_height+'px');

        var defaultloc = pfthemesm.pfstviewcor.split( ',' );
        var pfpanoramaOptions = {
          position: new google.maps.LatLng(defaultloc[0], defaultloc[1]),
          pov: {
            heading: parseFloat(pfthemesm.pfstview_heading),
            pitch: parseFloat(pfthemesm.pfstview_pitch)
          },
          zoom: parseInt(pfthemesm.pfstview_zoom)
        };
        var pfstpano = new google.maps.StreetViewPanorama(
            document.getElementById('pf-itempage-header-streetview'),
            pfpanoramaOptions);
        pfstpano.setVisible(true);
      }

    }

    $(function(){

      if (pfthemesm.pfstviewcor != "0,0") {

        if ($('.ui-tabs > label').attr('id') != 'pfidplocation' && $('.pf-itempage-firsttab .ui-tab1 > .pf-itempage-maparea').length == 0) {
          $('body').one('click', '#pfidplocation', function(){
            setTimeout(function(){PFSinglePageMap.init('pf-itempage-header-map',1);},500);
          });
        } else {
          PFSinglePageMap.init('pf-itempage-header-map',1);
        }

        if (pfthemesm.streetview_status) {
          if ($('.ui-tabs > label').attr('id') != 'pfidpstreetview' && $('.pf-itempage-firsttab .ui-tab1 > .pf-itempage-stmaparea').length == 0) {
            $('body').one('click', '#pfidpstreetview', function(){
              PFSinglePageMap.initStView();
            });
          } else {
            PFSinglePageMap.initStView();
          }
        }

        if ($('#item-map-page').length > 0) {
          PFSinglePageMap.init('item-map-page',2);
        }
      }

      
      
    });

})(jQuery);