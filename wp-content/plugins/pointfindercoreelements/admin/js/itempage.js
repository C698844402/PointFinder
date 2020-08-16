(function($) {
  "use strict";


  /* Map function STARTED */

    $.pf_submit_page_map = function(){
      
      var mapcontainer = $('#pfupload_map');
      var pf_istatus = mapcontainer.data('pf-istatus');
      var lat = mapcontainer.data('lat');
          var lng = mapcontainer.data('lng');
          var marker;

      if (!pf_istatus) {
        $.pointfinderuploadmapsys = $.pointfinderbuildmap('pfupload_map');
        
        $.pointfinderuploadmarker = marker = L.marker([parseFloat(lat),parseFloat(lng)])
            .on('click',function(e) {})
            .on('dragend',function(e) {

              $('.rwmb-map-coordinate').attr('value',marker.getLatLng().lat+','+marker.getLatLng().lng);
              

              if ($('#pfitempagestreetviewMap').length > 0 && theme_map_functionspf.st4_sp_medst == 1) {
                $('#pfitempagestreetviewMap').data('pfcoordinateslat',marker.getLatLng().lat);
                $('#pfitempagestreetviewMap').data('pfcoordinateslng',marker.getLatLng().lng);
              $.pfstmapregenerate(marker.getLatLng());
              }
              
            }).addTo($.pointfinderuploadmapsys);

            marker.dragging.enable();

        $('#pfupload_map').data("pf-istatus","true");
      }

    }

    $.pfstmapregenerate = function(latLng){
      $.pfstpano.setPosition(latLng);
    }
    $.pfstmapgenerate = function(){
      var current_heading = parseFloat($("#webbupointfinder_item_streetview-heading").val());
      var current_pitch = parseFloat($("#webbupointfinder_item_streetview-pitch").val());
      var current_zoom = parseInt($("#webbupointfinder_item_streetview-zoom").val());

      var pfitemcoordinatesLat = parseFloat($("#pfitempagestreetviewMap").data('pfcoordinateslat'));
      var pfitemcoordinatesLng = parseFloat($("#pfitempagestreetviewMap").data('pfcoordinateslng'));
      var pfitemzoom = parseInt($("#pfitempagestreetviewMap").data('pfzoom'));

      var pfitemcoordinates_output = L.latLng(pfitemcoordinatesLat,pfitemcoordinatesLng);

      if ($('#pfupload_map').data('lng') == 'undefied') {
        var defaultlocl = L.latLng(40.71275, -74.00597);
      }else{
        var defaultlocl = L.latLng($('#pfupload_map').data( 'lat'), $('#pfupload_map').data('lng'));
      }

      var curlatLng = (typeof pfitemcoordinatesLat != 'undefined')? pfitemcoordinates_output:defaultlocl;

      if (current_heading != 0 && current_pitch != 0) {
        var pfpanoramaOptions = {
              position: curlatLng,
              pov: {
                heading: current_heading,
                pitch: current_pitch
              },
              zoom: current_zoom
            };
            $.pfstpano = new google.maps.StreetViewPanorama(
                document.getElementById('pfitempagestreetviewMap'),
                pfpanoramaOptions);
            $.pfstpano.setVisible(true);
            setTimeout(function(){
              $.pfstpano.setPosition(curlatLng);
              $.pfstpano.setPov({heading: current_heading,pitch: current_pitch});
            },1000)
      }else{
        var pfpanoramaOptions = {
              position: curlatLng
            };
            $.pfstpano = new google.maps.StreetViewPanorama(
                document.getElementById('pfitempagestreetviewMap'),
                pfpanoramaOptions);
            $.pfstpano.setVisible(true);
      }

      $.pfstpano.addListener('pov_changed', function() {
              $("#webbupointfinder_item_streetview-heading").val($.pfstpano.getPov().heading);
              $("#webbupointfinder_item_streetview-pitch").val($.pfstpano.getPov().pitch)
              $("#webbupointfinder_item_streetview-zoom").val($.pfstpano.getPov().zoom)
            });

            $.pfstpano.addListener('position_changed', function() {
              $("#webbupointfinder_item_streetview-heading").val($.pfstpano.getPov().heading);
              $("#webbupointfinder_item_streetview-pitch").val($.pfstpano.getPov().pitch)
              $("#webbupointfinder_item_streetview-zoom").val($.pfstpano.getPov().zoom)
            });
    }

  /* Map Function END */


  $(function(){
      if (theme_map_functionspf.st4_sp_medst != 1 && $('#pfitempagestreetviewMap').length > 0) {
        $('#redux-pointfinderthemefmb_options-metabox-pf_item_streetview').remove();
      }

      $('.rwmb-map-coordinate-find').on('click', function() {

            var defaultloc = $('.rwmb-map-coordinate').attr('value') ? $('.rwmb-map-coordinate').attr('value').split( ',' ) : [0, 0];

            $.pointfinderuploadmarker.setLatLng(L.latLng(defaultloc[0],defaultloc[1]))
            $.pointfinderuploadmapsys.panTo(L.latLng(defaultloc[0],defaultloc[1]));

            if ($('#pfitempagestreetviewMap').length > 0 && theme_map_functionspf.st4_sp_medst == 1) {
              $('#pfitempagestreetviewMap').data('pfcoordinateslat',defaultloc[0]);
              $('#pfitempagestreetviewMap').data('pfcoordinateslng',defaultloc[1]);
              $.pfstmapregenerate(L.latLng(defaultloc[0],defaultloc[1]));
            }
            return false;
      });
      if ($('#pfitempagestreetviewMap').length > 0 && theme_map_functionspf.st4_sp_medst == 1) {
        $.pfstmapgenerate();
      }
      if ($('#pfupload_map').length > 0) {
        $.pf_submit_page_map();
      }


      $("#pf_search_geolocateme").on("click",function(){
        $(".pf-search-locatemebut").hide("fast");
        $(".pf-search-locatemebutloading").show("fast");
        $.pfgeolocation_findme('webbupointfinder_items_address',$('#pfupload_map').data('geoctype'));
      });


      if ($("#webbupointfinder_items_address").length > 0) {
       $('#webbupointfinder_items_address').parent().append('<div class="typeahead__container we-change-addr-input-upl"><div class="typeahead__field"><span class="typeahead__query">');
       $('#webbupointfinder_items_address').parent().prepend('</span></div></div>');
       $('#webbupointfinder_items_address').appendTo('.typeahead__container.we-change-addr-input-upl .typeahead__query');
       $('#pf_search_geolocateme').prependTo('.typeahead__container.we-change-addr-input-upl .typeahead__query');
       

       $.typeahead({
          input: "#webbupointfinder_items_address",
          minLength: 3,
          accent: true,
          dynamic:true,
          compression: false,
          cache: false,
          ttl: 86400000,
          hint: false,
          loadingAnimation: true,
          cancelButton: true,
          debug: true,
          searchOnFocus: false,
          delay: 1000,
          group: false,
          filter: false,
          maxItem: 10,
          maxItemPerGroup: 10,
          emptyTemplate: $('.rwmb-map-canvas').data('text1'),
          template: "{{address}}",
          templateValue: "{{address}}",
          selector: {
              cancelButton: "typeahead__cancel-button2"
          },
          source: {
              "found": {
                ajax: {
                  type: "GET",
                    url: theme_map_functionspf.ajaxurl,
                    dataType: "json",
                    path: "data.found",
                    data: {
                      action: "pfget_geocoding",
                      security: theme_map_functionspf.pfget_geocoding,
                      q: "{{query}}",
                      option: "geocode",
                      ctype: $('#pfupload_map').data('geoctype')
                    }
                }
              }
          },
          callback: {
            onLayoutBuiltAfter:function(){
            $(".we-change-addr-input-upl").find(".typeahead__list").css("width",$("#webbupointfinder_items_address").outerWidth());
            $(".we-change-addr-input-upl").find(".typeahead__result").css("width",$("#webbupointfinder_items_address").outerWidth());
            $(".we-change-addr-input-upl ul.typeahead__list").css("min-width",$(".we-change-addr-input-upl .typeahead__field").outerWidth());
            },
            onClickBefore: function(){
              
            $(".typeahead__container.we-change-addr-input-upl .typeahead__field input").css("padding-right","66px");
            },
          onClickAfter: function(node, a, item, event){
            event.preventDefault();
            
            $("#webbupointfinder_items_address").attr('value',item.address);

            if($('#pfupload_map').data('geoctype') == "google"){
                          
              var sessiontoken = item.lng;
              var place_id = item.lat
              $.ajax({
                  url: theme_scriptspf.ajaxurl,
                  type: "GET",
                  dataType: "JSON",
                  data: {action: "pfget_geocodingx",security: theme_scriptspf.pfget_geocoding,sessiontoken:sessiontoken,place_id:place_id},
                })
                .done(function(data) {

                  $('.rwmb-map-coordinate').attr('value',data.result.geometry.location.lat+','+data.result.geometry.location.lng);

                  $.pointfinderuploadmarker.setLatLng(L.latLng(data.result.geometry.location.lat, data.result.geometry.location.lng))
                  $.pointfinderuploadmapsys.panTo(L.latLng(data.result.geometry.location.lat, data.result.geometry.location.lng));

                  if ($('#pfitempagestreetviewMap').length > 0 && theme_map_functionspf.st4_sp_medst == 1) {
                    $('#pfitempagestreetviewMap').data('pfcoordinateslat',data.result.geometry.location.lat);
                    $('#pfitempagestreetviewMap').data('pfcoordinateslng',data.result.geometry.location.lng);
                    $.pfstmapregenerate(L.latLng(data.result.geometry.location.lat, data.result.geometry.location.lng));
                  }
                });
              

            }else{
              $('.rwmb-map-coordinate').attr('value',item.lat+','+item.lng);

              $.pointfinderuploadmarker.setLatLng(L.latLng(item.lat, item.lng))
              $.pointfinderuploadmapsys.panTo(L.latLng(item.lat, item.lng));

              if ($('#pfitempagestreetviewMap').length > 0 && theme_map_functionspf.st4_sp_medst == 1) {
                $('#pfitempagestreetviewMap').data('pfcoordinateslat',item.lat);
                $('#pfitempagestreetviewMap').data('pfcoordinateslng',item.lng);
                $.pfstmapregenerate(L.latLng(item.lat, item.lng));
              }
            }

            $(".typeahead__cancel-button2").css("visibility","visible");
          },
          onCancel: function(node,event){
            $(".typeahead__cancel-button2").css("visibility","hidden");
            $("#webbupointfinder_items_address").attr('value',"");
              }
          }
        });
      }

      if ($('#post_author_override').length > 0) {
        var container = $('.pflistingtype-selector-main-top');
        var pfurl = container.data('pfajaxurl');
        var pfnonce = container.data('pfnoncef');
        var pfplaceh = container.data('pfplaceh');

        $('#post_author_override').select2({
           placeholder: pfplaceh,
           minimumInputLength: 3,
           ajax: {
             type: 'POST',
             dataType: "json",
             url: pfurl,
             quietMillis: 250,
             data: function (term, page) {
                 return {
                     q: term,
                     action: 'pfget_authorchangesystem',
                     security: pfnonce
                 };
             },
             results: function (data) {
                 return {results: data};
             }
           },
           formatResult: formatValues,
           formatSelection: formatValues
        });

        function formatValues(data) {
            return data.nickname;
        }
      }

  });

})(jQuery);