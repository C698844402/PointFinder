(function($) {
	"use strict";
	var PFContactMap = {
	    init: function(){
	      if ( $('#pointfindercontactmap').length > 0 ) {
	      	$.pointfindercontactmap = $.pointfinderbuildmap('pointfindercontactmap');
	        this.addMarkers();
	      }
	    },
	    addMarkers: function(){
			var points = $('#pointfindercontactmap').data('points');
			if (points.length > 0) {
			  var layerGroup = L.layerGroup();
			  var marker, markers, $point;
			  
				function urldecode(str) {
				  return decodeURIComponent((str + '')
				    .replace(/%(?![\da-f]{2})/gi, function() {
				      return '%25';
				    })
				    .replace(/\+/g, '%20'));
				}
			  var iconbg = $('#pointfindercontactmap').data('iconbg');
		
			  $.each(points, function(index, val) {

			  	  if (typeof val.cmap_color != 'undefined') {
					if (typeof val.icon.value != 'undefined') {
						var icon = val.icon.value;
					}else{
						var icon = '';
					}
			  	  	marker = L.marker(L.latLng(parseFloat(val.cmap_lat),parseFloat(val.cmap_lng)),
				        {
				          icon: L.divIcon({html: "<div class='pfcatdefault-mapicon "+val.cmap_ipos+" pf-map-pin-1 pf-map-pin-1-small' style='background-color:"+val.cmap_color+";opacity:1;' ><i class='"+icon+"' style='color:"+val.cmap_icolor+";' ></i></div>",popupAnchor: [0, -31]}),
				          title: urldecode(val.cmap_title),
				          alt: urldecode(val.cmap_title),
				          riseOnHover: true,
				          bubblingMouseEvents: true
				        }
				      );

				      marker.bindPopup("<span class='wpftitle'><a>"+urldecode(val.cmap_title)+"</a></span><span class='wpfaddress'>"+urldecode(val.cmap_desc)+"</span>",{
				        maxWidth:250,
				        minWidth:50,
				        maxHeight:400,
				        autoPan:true,
				        keepInView:false,
				        closeButton:true,
				        autoClose:true,
				        closeOnEscapeKey:true,
				        closeOnClick:true,
				        className:'pointfindercontactmappoint'
				      });
			  	  }else{
			  	  	marker = L.marker( L.latLng(parseFloat(val.lat),parseFloat(val.lng)),
				        {
				          icon: L.divIcon({html: "<div class='pfcatdefault-mapicon pf-map-pin-1 pf-map-pin-1-small' style='background-color:"+iconbg+";opacity:1;' ><i class='pfadmicon-glyph-869' style='color:"+iconbg+";' ></i></div>",popupAnchor: [0, -31]}),
				          title: urldecode(val.data.title),
				          alt: urldecode(val.data.title),
				          riseOnHover: true,
				          bubblingMouseEvents: true
				        }
				      );

				      marker.bindPopup("<span class='wpftitle'><a>"+urldecode(val.data.title)+"</a></span><span class='wpfaddress'>"+urldecode(val.data.desc)+"</span>",{
				        maxWidth:250,
				        minWidth:50,
				        maxHeight:400,
				        autoPan:true,
				        keepInView:false,
				        closeButton:true,
				        autoClose:true,
				        closeOnEscapeKey:true,
				        closeOnClick:true,
				        className:'pointfindercontactmappoint'
				      });
			  	  }
			      
			      layerGroup.addLayer(marker);
			          
			  });
			  
			  markers = L.markerClusterGroup({
			    showCoverageOnHover: false,
			    spiderfyDistanceMultiplier: 2,
			    spiderLegPolylineOptions: { weight: 1 }
			  });

			  markers.addLayer(layerGroup);
			  $.pointfindercontactmap.addLayer(markers);
			  $.pointfindercontactmap.fitBounds(markers.getBounds(),{padding: [40,40],maxZoom:14});
			  
			}
		}

	  }


	  $(function(){
	  	PFContactMap.init();
	  });

})(jQuery);