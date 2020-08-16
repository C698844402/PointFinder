(function($) {
	"use strict";

	$.pftogglewnotification = function(message,timeout){
		$.pftogglestatus = true;
		setTimeout(function(){
			if($.pftogglestatus == true){
				$.pftogglewnotificationclear();
			}
		},timeout);
		$('#pfnot-err-button-menu').hide({ effect: "fade",direction: "up" },0);
		$('#pfnot-err-button').show({ effect: "fade",direction: "up" },0);
		$('.pfnotificationwindow .pfnottext').html(message);
		$( ".pfnotificationwindow" ).show({ effect: "fade",direction: "up" },0);
		
	};

	$.pftogglewnotificationclear = function(){
		if($('.pfnotificationwindow').is(':visible')){
			$('.pfnotificationwindow').hide({ effect: "fade",direction: "up" },0);
			$('#pfnot-err-button').hide();
			$('#pfnot-err-button-menu').show({ effect: "fade",direction: "up" },1000,function(){
				if($.pf_tablet_check()){$(this).addClass('animated flash')};
			});
			$.pftogglestatus = false;
		}else{
			$('.pfnotificationwindow').show({ effect: "fade",direction: "up" },0);
			$('#pfnot-err-button').show({ effect: "fade",direction: "up" },0);
			$('#pfnot-err-button-menu').hide({ effect: "fade",direction: "up" },0);
			$.pftogglestatus = true;
		}
		
	};

	$.pointfinderdistanceclickact = function(e){
		e.stopPropagation();
		if ($('#pf_search_geodistance i').hasClass('pfadmicon-glyph-72')) {
			$('#pf_search_geodistance i').switchClass('pfadmicon-glyph-72','pfadmicon-glyph-96');
			$('#pointfinder_radius_search_main').fadeIn('fast');
		}else{
			$('#pointfinder_radius_search-view').attr("value",$('#pointfinder_radius_search').attr("value"));
			$('#pf_search_geodistance i').switchClass('pfadmicon-glyph-96','pfadmicon-glyph-72');
			$('#pointfinder_radius_search_main').fadeOut('fast');
		}
	}

	$.pointfinderdistanceclickactex = function(e){
		e.stopPropagation();
		if ($('#pf_search_geodistance i').hasClass('pfadmicon-glyph-72')) {
			$('#pf_search_geodistance i').switchClass('pfadmicon-glyph-72','pfadmicon-glyph-96');
			$('#pointfinder_radius_search_mainerror').fadeIn('fast');
		}else{
			$('#pf_search_geodistance i').switchClass('pfadmicon-glyph-96','pfadmicon-glyph-72');
			$('#pointfinder_radius_search_mainerror').fadeOut('fast');
		}
	}

	$('body').on('click','#pf_search_geodistance',function(e){
		if ($('#pointfinder_google_search_coord').attr("value") != "") {
			$.pointfinderdistanceclickact(e);
		}else{
			$.pointfinderdistanceclickactex(e);
		}

		var form_radius_val = $('#pointfinder_radius_search-view2').attr("value");
		$('#pointfinder_radius_search-view').attr("value",form_radius_val);
	});

	$('#pf-reset-button-manual').on('click', function(event) {
		event.preventDefault();
		document.getElementById("pointfinder-search-form-manual").reset();
		$('#pointfinder-search-form-manual').find(':input').not(':button, :submit, :reset, :hidden, :checkbox, :radio').val('')
		if (typeof $('#pointfinder_google_search_coord').attr("value") != 'undefined' || $('#pointfinder_google_search_coord').attr("value") != '') {
			$('#pointfinder_google_search_coord').attr("value",'');
			$('input[name=ne]').attr("value",'');$('input[name=ne2]').attr("value",'');$('input[name=sw]').attr("value",'');$('input[name=sw2]').attr("value",'');
		}

		document.getElementById("pointfinder-search-form-manual").submit();
	});


	$.pointfindernewmapclearlayers = function(){
		if ($.pointfindermarkers != '' && !$('body').hasClass('pftoppagemapviewdef')) {
    		$.pointfindermarkers.clearLayers();
    	}
    	if (typeof $.pointfindersplayergroup == 'object') {
			$.pointfindersplayergroup.remove();
		}
	}

	$.pointfinderbuildcluster = function(handler,mapelement,layerGroup){
		if (!$('body').hasClass('pftoppagemapviewdef')) {
			if ($('#'+handler+'').data("cluster")== 1) {
	    		$.pointfindermarkers = L.markerClusterGroup({
					showCoverageOnHover: false,
					zoomToBoundsOnClick: true,
					spiderfyOnMaxZoom: true,
					disableClusteringAtZoom: 18,
					maxClusterRadius:$('#'+handler+'').data("clusterrad"),
					animate:true,
					spiderfyDistanceMultiplier: 2,
					spiderLegPolylineOptions: { weight: 1.5, color: '#222', opacity: 0.5 },
					removeOutsideVisibleBounds: true
				});
				$.pointfindermarkers.addLayer(layerGroup);
				$.pointfindermarkers.id = 'categorymapmarkers';
				mapelement.addLayer($.pointfindermarkers);
				mapelement.setMinZoom(2.4);
				mapelement.fitBounds($.pointfindermarkers.getBounds(),{padding: [40,40],maxZoom:14});
			}else{
				$.pointfindermarkers = L.featureGroup(layerGroup.getLayers());
				$.pointfindermarkers.id = 'categorymapmarkers';
				mapelement.addLayer($.pointfindermarkers);
				mapelement.fitBounds($.pointfindermarkers.getBounds(),{padding: [40,40],maxZoom:14});
			}
		}
	}


	$.pointfinderbuildmap = function(handler,markertype){

	      	$.pointfindermarkers = ''; $.pointfindersplayergroup = '';

			var $mapobject = $('#'+handler+'');
	        
	        if (handler != 'pf-itempage-header-map' && handler != 'item-map-page' && handler != 'pfquickviewmap') {

	        	var pagemode = $mapobject.data('mode');

		        if (pagemode == "topmap") {
			        if (!$.pf_mobile_check()) {/* Mobile */
			        	$mapobject.css('height',''+$mapobject.data('mheight')+'px');
			        } else if(!$.pf_tablet_check()){/* Tablet */
			        	$mapobject.css('height',''+$mapobject.data('theight')+'px');
			        }else{/* Desktop */
			        	$mapobject.css('height',''+$mapobject.data('height')+'px');
			        }
			    }else if(pagemode == "halfmap"){
			    	if (!$.pf_mobile_check()) {/* Mobile */
			        	$mapobject.css('height',''+$mapobject.data('mheight')+'px');
			        } else if(!$.pf_tablet_check()){/* Tablet */
			        	$mapobject.css('height',''+$mapobject.data('theight')+'px');
			        }else{/* Desktop */
			        	$mapobject.css('height',$('#pfhalfmapmapcontainer').height());
			        }
			    }

			    /* Get Map type etc.. */
		        var we_map_type = $mapobject.data('mtype');
		        var we_special_key = $mapobject.data('key');

		        var we_lat = $mapobject.data('lat');
		        var we_lng = $mapobject.data('lng');
		        var we_zoom = $mapobject.data('zoom');

		    }else if(handler == 'pfquickviewmap'){

		    	$mapobject.css('height','570px');
		    	var we_lat = parseFloat($mapobject.data("welat"));
	          	var we_lng = parseFloat($mapobject.data("welng"));

          		var we_map_type = $mapobject.data("mtype");
	          	var we_special_key = $mapobject.data("key");

	          	var we_zoom = $mapobject.data('zoom');

	         	if (we_zoom == '') {we_zoom = 12;}

		    }else{
		    	
		    	 if (markertype == 1) {
		            $mapobject.css('height',''+pfthemesm.location_view_height+'px');
		          } else {
		            $mapobject.css('height','400px');
		          }
		          var defaultloc = pfthemesm.pfstviewcor.split( ',' );

		          var we_lat = parseFloat(defaultloc[0]);
		          var we_lng = parseFloat(defaultloc[1]);

		          /* Get Map type etc.. */
		          var we_map_type = pfthemesm.stp5_mapty;
		          var we_special_key = pfthemesm.key;


		          var we_zoom = pfthemesm.zoom;

		          if (typeof we_zoom == 'undefined' || we_zoom == '') {we_zoom = 12;}
		    }

		   var we_maxzoom = 18;

		    if (handler == 'pointfinder-category-map' || handler == 'wpf-map') {
		    	if(!$.pf_mobile_check()){
	       	  		var we_zoom = $mapobject.data('zoomm');
		       	}else{
		       		var we_zoom = $mapobject.data('zoom');
		       	}
		    } else if (handler != 'pf-itempage-header-map' && handler != 'item-map-page') {
		    	var we_zoom = $mapobject.data('zoom');
		    }

	     	if (we_zoom == '') {we_zoom = 14;}


	     	if (handler == 'pfdirectorymap') {
	     		$.pfmovemaplatlng = [];
		        if (!$.pf_mobile_check()) {/* Mobile */
		        	$mapobject.css('height',''+$mapobject.data('mheight')+'px');
		        } else if(!$.pf_tablet_check()){/* Tablet */
		        	$mapobject.css('height',''+$mapobject.data('theight')+'px');
		        }else{/* Desktop */
		        	$mapobject.css('height',''+$mapobject.data('height')+'px');
		        }

	        
		        var containerparent = $("#wpf-map-container").parent().parent().parent().parent().parent();

				if (containerparent.hasClass("vc_row-o-full-height")) {

					var calc_height = containerparent.height();


					if (!$(".wpf-container").hasClass("pftransparenthead") && $.pf_tablet_check()) {
						calc_height = (calc_height - $("#pfheadernav").height());
					}

					if (!$.pf_tablet_check()) {
						calc_height = (calc_height - $("#pfheadernav").height());
					}

					$mapobject.attr('data-height',calc_height);
					$mapobject.css('height',''+calc_height+'px');

				}

				
				if ($(".wpf-container").hasClass("pftransparenthead") && !$.pf_tablet2_check()) {
					$(".pfnotificationwindow").css("top",'50px');
					$(".pf-err-button").css("top",'50px');
				}else if($(".wpf-container").hasClass("pftransparenthead") && $.pf_tablet2_check()){
					$(".pfsearch-draggable-window").css("margin-top",($("#pfheadernav").height() + 20));
					$(".pfnotificationwindow").css("top",($("#pfheadernav").height() + 30));
					$(".pf-err-button").css("top",($("#pfheadernav").height() + 30));
				}else{
					$(".pfsearch-draggable-window").css("margin-top","50px");
					$(".pfnotificationwindow").css("top",'50px');
					$(".pf-err-button").css("top",'50px');
				}
				
				

				if ($('#pfsearch-draggable').parents('.pf-fullwidth').length == 0) {
					if ($.pf_mobile_check()) {
						$('#pfsearch-draggable').css('margin-right','50px').css('margin-left','50px');
					};
				};


		        $mapobject.css('z-index', 1);
	    	}

	    	var gesturestatus = false;

	    	if (theme_scriptspf.gesturehandling == 'true') {
	    		gesturestatus = true;
	    	}
	    
	     	if (we_map_type == '1') {


	     		/* Create Map */
	            var mapelement = L.map(''+handler+'',{
	                center: [we_lat, we_lng],
	                zoom: we_zoom,
	                maxZoom: parseInt(we_maxzoom),
	                gestureHandling: gesturestatus
	            });

	        	/* Google */
	            var roadMutant = L.gridLayer.googleMutant({maxZoom: 24,type:'roadmap'});
	            var satMutant = L.gridLayer.googleMutant({maxZoom: 24,type:'satellite'});
	            var terrainMutant = L.gridLayer.googleMutant({maxZoom: 24,type:'terrain'});
	            var hybridMutant = L.gridLayer.googleMutant({maxZoom: 24,type:'hybrid'});

	           
	            var trafficMutant = L.gridLayer.googleMutant({maxZoom: 24,type:'roadmap'});
	            trafficMutant.addGoogleLayer('TrafficLayer');


	            L.control.layers({
	                Roadmap: roadMutant,
	                Aerial: satMutant,
	                Terrain: terrainMutant,
	                Hybrid: hybridMutant,
	                Traffic: trafficMutant
	                }, {}, {
	                collapsed: true
	            }).addTo(mapelement);


	            var grid = L.gridLayer({
	                attribution: 'Grid Layer',
	            });

	            grid.createTile = function (coords) {
	                var tile = L.DomUtil.create('div', 'tile-coords');
	                tile.innerHTML = [coords.x, coords.y, coords.z].join(', ');

	                return tile;
	            };

	            mapelement.addLayer(grid);
	            mapelement.addLayer(roadMutant);

	        } else if(we_map_type == '3'){
	        	/* Mapbox */
	            var mbAttr = 'Map data &copy; <a href="https://www.openstreetmap.org/">OSM</a> | ' +
	            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
	            mbUrl = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token='+we_special_key;

	            var grayscale = L.tileLayer(mbUrl, {id: 'mapbox.light', attribution: mbAttr});
	            var streets   = L.tileLayer(mbUrl, {id: 'mapbox.streets', attribution: mbAttr});
	            var satellite   = L.tileLayer(mbUrl, {id: 'mapbox.satellite', attribution: mbAttr});

	            var baseMaps = {
	                "Grayscale": grayscale,
	                "Streets": streets,
	                "Satellite": satellite
	            };

	            /* Create Map */
	            var mapelement = L.map(''+handler+'',{
	            	zoomControl: false,
	                center: [we_lat, we_lng],
	                zoom: we_zoom,
	                maxZoom: parseInt(we_maxzoom),
	                layers:[streets],
	                gestureHandling: gesturestatus
	            });

	            L.control.layers(baseMaps).addTo(mapelement);

	        } else if(we_map_type == '2'){
	        	/* OSM */
	            var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	                maxZoom: parseInt(we_maxzoom),
	                attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
	            });

	            /* Create Map */
	            var mapelement = L.map(''+handler+'',{
	            	zoomControl: false,
	                center: [we_lat, we_lng],
	                zoom: we_zoom,
	                layers:[tiles],
	                gestureHandling: gesturestatus
	            });

	        } else if(we_map_type == '4'){
	        	/* Yandex */
	            var standard = new L.Yandex('map',{traffic: true});
	            var satellite = new L.Yandex('satellite',{traffic: true});
	            var hybrid = new L.Yandex('hybrid',{traffic: true});

	            var baseMaps = {
	                "Standard": standard,
	                "Satellite": satellite,
	                "Hybrid": hybrid
	            };


	            var mapelement = L.map(''+handler+'',{
	            	zoomControl: false,
	                center: [we_lat, we_lng],
	                zoom: we_zoom,
	                layers:[standard],
	                maxZoom: parseInt(we_maxzoom),
	                gestureHandling: gesturestatus
	            });

	            L.control.layers(baseMaps).addTo(mapelement);

	        } else if(we_map_type == '6'){
	        	/* Bing */

	            var Aerial = new L.BingLayer(""+we_special_key+"", {type: "Aerial"});
	            var AerialWithLabels   =  new L.BingLayer(""+we_special_key+"", {type: "AerialWithLabels"});
	            var Road   = new L.BingLayer(""+we_special_key+"", {type: "Road"});

	            var baseMaps = {
	                "Aerial": Aerial,
	                "AerialWithLabels": AerialWithLabels,
	                "Road": Road
	            };

	            var mapelement = L.map(''+handler+'',{
	            	zoomControl: false,
	                center: [we_lat, we_lng],
	                zoom: we_zoom,
	                maxZoom: parseInt(we_maxzoom),
	                layers:[Road],
	                gestureHandling: gesturestatus
	            });

	            L.control.layers(baseMaps).addTo(mapelement);

	        } else if(we_map_type == '5'){
	        	/* here */
	        	if (handler != 'pf-itempage-header-map' && handler != 'item-map-page') {
	        		var we_here_appid = $mapobject.data('hereappid');
	            	var we_here_app_code = $mapobject.data('hereappcode');
	        	} else {
	        		var we_here_appid = pfthemesm.wemap_here_appid;
              		var we_here_app_code = pfthemesm.wemap_here_appcode;
	        	}
	            
	        	var attiribution = '&copy; <a href="https://www.here.com/">HERE Maps</a> contributors';

	            var normal = L.tileLayer("https://2.base.maps.ls.hereapi.com/maptile/2.1/maptile/newest/normal.day/{z}/{x}/{y}/256/png8?apiKey="+we_here_app_code, {styleId: 997,attribution: attiribution});
	            var normalg = L.tileLayer("https://2.base.maps.ls.hereapi.com/maptile/2.1/maptile/newest/normal.day.grey/{z}/{x}/{y}/256/png8?apiKey="+we_here_app_code, {styleId: 998,attribution: attiribution});
	            var terrain = L.tileLayer("https://1.aerial.maps.ls.hereapi.com/maptile/2.1/maptile/newest/terrain.day/{z}/{x}/{y}/256/png8?apiKey="+we_here_app_code, {styleId: 990,attribution: attiribution});
	            var satellite = L.tileLayer("https://2.aerial.maps.ls.hereapi.com/maptile/2.1/maptile/newest/satellite.day/{z}/{x}/{y}/256/png8?apiKey="+we_here_app_code, {styleId: 999,attribution: attiribution});
	            var hybrid = L.tileLayer("https://2.aerial.maps.ls.hereapi.com/maptile/2.1/maptile/newest/hybrid.day/{z}/{x}/{y}/256/png8?apiKey="+we_here_app_code, {styleId: 996,attribution: attiribution});


	            var baseMaps = {
	                "Roadmap": normal,
	                "Roadmap Grey": normalg,
	                "Terrain": terrain,
	                "Satellite": satellite,
	                "Hybrid": hybrid
	            };

	            var mapelement = L.map(''+handler+'',{
	            	zoomControl: false,
	                center: [we_lat, we_lng],
	                zoom: we_zoom,
	                layers:[normalg],
	                maxZoom: parseInt(we_maxzoom),
	                gestureHandling: gesturestatus
	            });

	            L.control.layers(baseMaps).addTo(mapelement);

	        }

	        if (handler != 'pfupload_map' && handler != 'pfquickviewmap') { 
	        	mapelement.dragging.enable();
	        	mapelement.scrollWheelZoom.enable();
	        }
	       
	        L.control.zoom({
			     position:theme_scriptspf.bposition
			}).addTo(mapelement);

	        mapelement.addControl(new L.Control.Fullscreen({
	        	position: theme_scriptspf.bposition,
	        	icon: 'fas fa-expand',
            	iconex: 'fas fa-compress',
	            title: {
	                'false': theme_scriptspf.fullscreen,
	                'true': theme_scriptspf.fullscreenoff
	            }
	        }));

	        if (handler != 'pfupload_map') {
		        if (handler != 'pf-itempage-header-map' && handler != 'item-map-page' && handler != 'pfquickviewmap') {
		        	if (handler == 'pointfindercontactmap') {
			        	L.control.locate({
							position: theme_scriptspf.bposition,
							strings: {
								title: theme_scriptspf.locateme,
								popup: theme_scriptspf.locatefound
							},
							icon: "fas fa-map-marker-alt",
							iconLoading: 'fas fa-spinner fa-spin',
							drawCircle: false,
							showPopup: true,
							locateOptions: {
							maxZoom: 16
							}
				        }).addTo(mapelement);
			        } else {

			        	$.pointfinderlocationmark = new L.Control.PFDMapLocate({
							position: theme_scriptspf.bposition,
							setView: 'once',
							strings: {
								title: theme_scriptspf.locateme,
								metersUnit: "km",
				                feetUnit: "mile",
				                popup: theme_scriptspf.locatefound
							},
							metric: ($mapobject.data("gldistanceunit")=="km")?true:false,
							icon: "fas fa-map-marker-alt",
							iconLoading: 'fas fa-spinner fa-spin',
							drawCircle: false,
							showPopup: true,
							returnToPrevBounds: false,
							keepCurrentZoomLevel: false,
							locateOptions: {
								maxZoom: 12,
								enableHighAccuracy: true
							},
				            followCircleStyle: {
				            	radius: ($mapobject.data("gldistance") * 1000)
				            }
				        }).addTo(mapelement);
			        }
		        } else {
		        	L.control.locate({
			              position: theme_scriptspf.bposition,
			              strings: {
			               title: theme_scriptspf.locateme
			              },
			              icon: "fas fa-map-marker-alt",
			              iconLoading: 'fas fa-spinner fa-spin',
			              drawCircle: false,
			              showPopup: false,
			              locateOptions: {
			                maxZoom: 16
			              }
			          }).addTo(mapelement);
		        }
	        }
	        
	        if (gesturestatus != true) {
		        L.easyButton({
		        	position: theme_scriptspf.bposition,
					states:[
						{
							stateName: 'lockbutton',
							icon: 'fas fa-lock-open',
							title: theme_scriptspf.lockunlock,
							onClick: function(btn, map){
								map.dragging.disable();
								map.scrollWheelZoom.disable();
								btn.state('unlockbutton');
							}
						},
						{
							stateName: 'unlockbutton',
							icon: 'fas fa-lock',
							title: theme_scriptspf.lockunlock2,
							onClick: function(btn, map){
								map.dragging.enable();
								map.scrollWheelZoom.enable();
								btn.state('lockbutton');
							}
						}
					]
		        }).addTo(mapelement);
	    	}

	    	if (handler != 'pfupload_map') {
	         	L.easyButton({
		        	position: theme_scriptspf.bposition,
					states:[
						{
							stateName: 'homebutton',
							icon: 'fas fa-home',
							title: theme_scriptspf.returnhome,
							onClick: function(btn, map){
								if (handler != 'pf-itempage-header-map' && handler != 'item-map-page') {
									if (handler == 'pfdirectorymap') {
										$("html, body").animate({ scrollTop: 0 }, "slow");
										if ($.typeofsdata == 'undefined') {
											$.PFDirectoryMap.addMarkers();
										}else{
											if (typeof $.pointfindersplayergroup == 'object') {
												$('#pointfinder_google_search_coord').attr("value","");
												document.getElementById("pointfinder-search-form").reset();
												$.PFLoadNewMarkers();
											}else{
												$.PFLoadNewMarkers();
											}
										}
									}else if(handler == 'pointfindercontactmap'){
										mapelement.setView([we_lat,we_lng],we_zoom);
									}else if(handler == 'pointfinder-category-map'){
										$.pointfindernewmapsys.fitBounds($.pointfindermarkers.getBounds(),{padding: [100,100]});
									} else {
										$.pfloadlistings();
										$.pointfinderlocationmark.stop();
									}
								}else{
									mapelement.setView([we_lat,we_lng],we_zoom);
								}
							}
						}
					]
		        }).addTo(mapelement);
	         }

         	if (handler == 'pf-itempage-header-map' || handler == 'item-map-page' || handler == 'pfquickviewmap') {
				L.easyButton({
					position: theme_scriptspf.bposition,
					states:[
						{
							stateName: 'getdirection',
							icon: 'fas fa-route',
							title: theme_scriptspf.getdirections,
							onClick: function(){window.open("https://maps.google.com?saddr=Current+Location&daddr="+we_lat+","+we_lng+"");}
						}
					]
				}).addTo(mapelement);
         	}

	    	if (handler == 'pfdirectorymap') {
	    		$.PFLoadNewMarkers();
	    	}

	    	if($.pf_tablet2_check() && theme_scriptspf.ttstatus == 1 && handler != 'pfupload_map'){
	  			$('.leaflet-left .leaflet-control a').tooltip(
	  				{
					  position: { 
					  	my: 'left+5',
					  	at: 'right+2 center-3',
					  	collision: "none",
					  	using: function( position, feedback ) {
							$( this ).css( position );
							$( this.firstChild )
							.addClass( "pointfinderarrow_box" )
							.addClass( "wpfquick-tooltip" )
							.addClass( feedback.vertical )
							.addClass( feedback.horizontal );
				        }
					  },
					  show: {effect: "blind", duration: 800},
					  hide: {effect: "blind", duration: "fast"}
					}
	  			);
	  			$('.leaflet-left .leaflet-control button').tooltip(
	  				{
					  position: { 
					  	my: 'left+5',
					  	at: 'right+2 center-3',
					  	collision: "none",
					  	using: function( position, feedback ) {
							$( this ).css( position );
							$( this.firstChild )
							.addClass( "pointfinderarrow_box" )
							.addClass( "wpfquick-tooltip" )
							.addClass( feedback.vertical )
							.addClass( feedback.horizontal );
				        }
					  },
					  show: {effect: "blind", duration: 800},
					  hide: {effect: "blind", duration: "fast"}
					}
	  			);
	  		}


	    	return mapelement;

	}

	$.pfgeolocation_findme = function(fieldval,we_geoc_type){

		if ($('#pf_search_geodistance').length > 0) {
			$('body').off('click','#pf_search_geodistance');
		}

		if ( $('#pfdirectorymap').length > 0 ) {
			$.pointfinderdirectorymap.off('locationfound');
			$.pointfinderdirectorymap.on('locationfound',function(e){
	        	
		        $('#pointfinder_google_search_coord').attr("value",e.latlng.lat+','+e.latlng.lng);
	        	
				$.pointfinderdirectorymap.stopLocate();

				$.ajax({
			    	url: theme_scriptspf.ajaxurl,
			    	type: 'GET',
			    	dataType: 'json',
			    	data: {
			    		action: "pfget_geocoding",
		              	security: theme_scriptspf.pfget_geocoding,
		              	lat: ""+e.latlng.lat+"",
		              	lng: ""+e.latlng.lng+"",
		              	option: 'reverse',
		              	ctype: ""+we_geoc_type+""
			    	},
			    }).success(function(data, textStatus, jqXHR) {
			    	if (typeof data != 'undefined' || data != '') {
			    		$("#"+fieldval+"").attr("value",data);
			    	}
			    }).complete(function(){
			    	if ($('#pf_search_geodistance').length > 0) {
						$('body').on('click','#pf_search_geodistance',function(e){$.pointfinderdistanceclickact(e);});
					}
			    	$('.pf-search-locatemebut').show('fast');
					$('.pf-search-locatemebutloading').hide('fast');
			    });
	        });

			var Location = $.pointfinderdirectorymap.locate({
	            watch: false,
	            setView: false,
	            enableHighAccuracy: true,
	            custom:'geobutton'
	        }).on('locationerror', function(e){
	            console.log(e);
	            alert(e.message);
	        });

		}else if($('#pfwidgetmap').length > 0){
			
			if(typeof $.pointfindernewmapsysw == 'undefined'){
		  		$.pointfindernewmapsysw = $.pointfinderbuildmap('pfwidgetmap');
		  	}

	  		try {
			    $.pointfindernewmapsysw.off('locationfound');
			}catch(err) {
			  console.log(err.message);
			}

	  		try{
			    $.pointfindernewmapsysw.locate({
		            watch: false,
		            setView: false,
		            enableHighAccuracy: true,
		            custom:'geobutton'
		        }).on('locationfound',function(e){

			        $('#pointfinder_google_search_coord').attr("value",e.latlng.lat+','+e.latlng.lng);
		        	$.pointfindersetbounds(e.latlng.lat,e.latlng.lng);
					$.pointfindernewmapsysw.stopLocate();

					$.ajax({
				    	url: theme_scriptspf.ajaxurl,
				    	type: 'GET',
				    	dataType: 'json',
				    	data: {
				    		action: "pfget_geocoding",
			              	security: theme_scriptspf.pfget_geocoding,
			              	lat: ""+e.latlng.lat+"",
			              	lng: ""+e.latlng.lng+"",
			              	option: 'reverse',
			              	ctype: ""+we_geoc_type+""
				    	},
				    }).success(function(data, textStatus, jqXHR) {
				    	if (typeof data != 'undefined' || data != '') {
				    		$("#"+fieldval+"").attr("value",data);
				    	}
				    }).complete(function(){
				    	if ($('#pf_search_geodistance').length > 0) {
							$('body').on('click','#pf_search_geodistance',function(e){$.pointfinderdistanceclickact(e);});
						}
				    	$('.pf-search-locatemebut').show('fast');
						$('.pf-search-locatemebutloading').hide('fast');
				    });
		        }).on('locationerror', function(e){
		            console.log(e);
		            alert(e.message);
		        });
			}catch(err) {
			  console.log(err.message);
			}
		 
			
			

		}else if($('#wpf-map').length > 0){
			if(typeof $.pointfindernewmapsys == 'undefined'){
		  		$.pointfindernewmapsys = $.pointfinderbuildmap('wpf-map');
		  	}

	  		try {
			    $.pointfindernewmapsys.off('locationfound');
			}catch(err) {
			  console.log(err.message);
			}
			try{
				$.pointfindernewmapsys.locate({
		            watch: false,
		            setView: false,
		            enableHighAccuracy: true,
		            custom:'geobutton'
		        }).on('locationfound',function(e){
		        	
			        $('#pointfinder_google_search_coord').attr("value",e.latlng.lat+','+e.latlng.lng);
		        	$.pointfindersetbounds(e.latlng.lat,e.latlng.lng);
					$.pointfindernewmapsys.stopLocate();

					$.ajax({
				    	url: theme_scriptspf.ajaxurl,
				    	type: 'GET',
				    	dataType: 'json',
				    	data: {
				    		action: "pfget_geocoding",
			              	security: theme_scriptspf.pfget_geocoding,
			              	lat: ""+e.latlng.lat+"",
			              	lng: ""+e.latlng.lng+"",
			              	option: 'reverse',
			              	ctype: ""+we_geoc_type+""
				    	},
				    }).success(function(data, textStatus, jqXHR) {
				    	if (typeof data != 'undefined' || data != '') {
				    		$("#"+fieldval+"").attr("value",data);
				    	}
				    }).complete(function(){
				    	if ($('#pf_search_geodistance').length > 0) {
							$('body').on('click','#pf_search_geodistance',function(e){$.pointfinderdistanceclickact(e);});
						}
				    	$('.pf-search-locatemebut').show('fast');
						$('.pf-search-locatemebutloading').hide('fast');
				    });
		        }).on('locationerror', function(e){
		            console.log(e);
		            alert(e.message);
		        });
			}catch(err) {
			  console.log(err.message);
			}

		}else if($('#pfupload_map').length > 0){
			$.pointfinderuploadmapsys.off('locationfound');
			$.pointfinderuploadmapsys.on('locationfound',function(e){
	        	

	        	if ($('.rwmb-map-coordinate').length > 0) {
	        		$('.rwmb-map-coordinate').attr('value',e.latlng.lat+','+e.latlng.lng);
	        	}else{
	        		$('#pfupload_lat_coordinate').attr('value',e.latlng.lat);
	    			$('#pfupload_lng_coordinate').attr('value',e.latlng.lng);
	        	}
	    		$.pointfinderuploadmarker.setLatLng(L.latLng(e.latlng.lat, e.latlng.lng))
	    		$.pointfinderuploadmapsys.panTo(L.latLng(e.latlng.lat, e.latlng.lng));

	    		if ($('#pfitempagestreetviewMap').length > 0) {
	    			$('#pfitempagestreetviewMap').data('pfcoordinateslat',e.latlng.lat);
					$('#pfitempagestreetviewMap').data('pfcoordinateslng',e.latlng.lng);
					$.pfstmapregenerate(L.latLng(e.latlng.lat, e.latlng.lng));
	    		}

		        $('#pointfinder_google_search_coord').attr('value',e.latlng.lat+','+e.latlng.lng);
	        	$.pointfindersetbounds(e.latlng.lat,e.latlng.lng);
				$.pointfinderuploadmapsys.stopLocate();

				$.ajax({
			    	url: theme_scriptspf.ajaxurl,
			    	type: 'GET',
			    	dataType: 'json',
			    	data: {
			    		action: "pfget_geocoding",
		              	security: theme_scriptspf.pfget_geocoding,
		              	lat: ""+e.latlng.lat+"",
		              	lng: ""+e.latlng.lng+"",
		              	option: 'reverse',
		              	ctype: ""+we_geoc_type+""
			    	},
			    }).success(function(data, textStatus, jqXHR) {
			    	if (typeof data != 'undefined' || data != '') {
			    		$("#"+fieldval+"").attr('value',data);
			    	}
			    }).complete(function(){
			    	if ($('#pf_search_geodistance').length > 0) {
						$('body').on('click','#pf_search_geodistance',function(e){$.pointfinderdistanceclickact(e);});
					}
			    	$(".pf-search-locatemebut").show("fast");
				  	$(".pf-search-locatemebutloading").hide("fast");
			    });
	        });

			var Location = $.pointfinderuploadmapsys.locate({
	            watch: false,
	            setView: false,
	            enableHighAccuracy: true,
	            custom:'geobutton'
	        }).on('locationerror', function(e){
	            console.log(e);
	            alert(e.message);
	        });
		}
        
	};

	$.pointfinderbuildmarker = function(handler,mapobj,lat,lng,id,title,icon,iconanchor){
		var marker = L.marker(
	        L.latLng(parseFloat(lat),parseFloat(lng)),
	        {
	          id: id,
	          icon: L.divIcon({html: ""+icon+"",popupAnchor: iconanchor}),
	          title: title,
	          alt: title,
	          riseOnHover: true,
	          bubblingMouseEvents: true
	        }
      	).bindPopup("",{
      		autoPanPadding:[30, 30],
	        maxWidth:(!$.pf_mobile_check())?$('#'+handler+'').data('imwidth'):$('#'+handler+'').data('iwidth'),
	        minWidth:(!$.pf_mobile_check())?$('#'+handler+'').data('imwidth'):$('#'+handler+'').data('iwidth'),
	        maxHeight:(!$.pf_mobile_check())?$('#'+handler+'').data('imheight'):$('#'+handler+'').data('iheight'),
	        autoPan:true,
	        keepInView:false,
	        closeButton:true,
	        autoClose:true,
	        closeOnEscapeKey:true,
	        closeOnClick:true,
	        className:'wpfinfowindow'
      	}).on('click',function(e) {

      		mapobj.setView(this.getLatLng());
      		
      	}).on('popupopen', function(e) {
      		var popup = e.popup;
      		popup.setContent("<div class='pfinfoloading pfloadingimg'></div>");
      		$.ajax({
	            type: 'POST',
	            dataType: 'html',
	            cache:true,
	            url: theme_scriptspf.ajaxurl,
	            data: { 
	                'action': 'pfget_infowindow',
	                'id': this.options.id,
	                'cl': theme_scriptspf.pfcurlang,
	                'single':0,
	                'security': theme_scriptspf.pfget_infowindow
	            },
	            success:function(data){
	            	$('.pfinfoloading').fadeOut('fast');
	            	popup.setContent(data);
	            },
	            error: function (request, status, error) {
	                popup.setContent('Error:'+request.responseText);
	            },
	            complete: function(){
	            	$('.pfinfoloading').fadeOut('slow');
	            }
            });
      	});

      	return marker;
	}

	$.pointfindercalculatebounds = function(lat, lng, distance, unit){

		function rad2deg (angle) {
		  return angle * 57.29577951308232
		}

		function deg2rad(degrees){
		    return degrees *  Math.PI / 180
		}

		if (unit == 'km') { var radius = 6371.009;} else {var radius = 3958.761;}

		var maxlat = lat + rad2deg( distance / radius );
		var minLat = lat - rad2deg( distance / radius );
		var maxLng = lng + rad2deg( distance / radius) / Math.cos( deg2rad( lat ) );
		var minLng = lng - rad2deg( distance / radius) / Math.cos( deg2rad( lat ) );

		return [maxlat,minLat,maxLng,minLng];
	}

	$.pfcalculatedistance = function(lat1, lon1, lat2, lon2, unit) {
		if ((lat1 == lat2) && (lon1 == lon2)) {
			return 0;
		}else {
			var radlat1 = Math.PI * lat1/180;
			var radlat2 = Math.PI * lat2/180;
			var theta = lon1-lon2;
			var radtheta = Math.PI * theta/180;
			var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
			if (dist > 1) {
				dist = 1;
			}
			dist = Math.acos(dist);
			dist = dist * 180/Math.PI;
			dist = dist * 60 * 1.1515;
			if (unit=="K") { dist = dist * 1.609344 }
			if (unit=="N") { dist = dist * 0.8684 }
			return dist;
		}
	}

	$.pointfindersetbounds = function(lat,lng){
		var form_radius_val = $('#pointfinder_radius_search-view2').attr("value");
	 	var form_radius_unit = $('#pointfinder_google_search_coord_unit').attr("value");
		var form_radius_unit_name = 'mi';
		
		if (form_radius_unit != 'Mile') {
	 		form_radius_val = parseInt(form_radius_val);
	 		if (isNaN(form_radius_val)) {
	 			form_radius_val = theme_map_functionspf.defmapdist;
	 		}
	 		var form_radius_val_ex = (parseInt(form_radius_val)*1000);
	 		form_radius_unit_name='km';
	 	} else{
	 		form_radius_val = parseInt(form_radius_val);
	 		if (isNaN(form_radius_val)) {
	 			form_radius_val = theme_map_functionspf.defmapdist;
	 		}
	 		var form_radius_val_ex = ((parseInt(form_radius_val)*1000)*1.60934);
	 		form_radius_unit_name='mi';
	 	};


		var newbounds = $.pointfindercalculatebounds(lat,lng,form_radius_val,form_radius_unit_name);
		$('#pfw-ne').attr("value",newbounds[0]);
		$('#pfw-ne2').attr("value",newbounds[2]);
		$('#pfw-sw').attr("value",newbounds[1]);
		$('#pfw-sw2').attr("value",newbounds[3]);
	}

	$.pointfindergetbounds = function(lat,lng){
		var form_radius_val = $('#pointfinder_radius_search-view2').attr("value");
	 	var form_radius_unit = $('#pointfinder_google_search_coord_unit').attr("value");
		var form_radius_unit_name = 'mi';
		
		if (form_radius_unit != 'Mile') {
	 		form_radius_val = parseInt(form_radius_val);
	 		if (isNaN(form_radius_val)) {
	 			form_radius_val = theme_map_functionspf.defmapdist;
	 		}
	 		var form_radius_val_ex = (parseInt(form_radius_val)*1000);
	 		form_radius_unit_name='km';
	 	} else{
	 		form_radius_val = parseInt(form_radius_val);
	 		if (isNaN(form_radius_val)) {
	 			form_radius_val = theme_map_functionspf.defmapdist;
	 		}
	 		var form_radius_val_ex = ((parseInt(form_radius_val)*1000)*1.60934);
	 		form_radius_unit_name='mi';
	 	};


		var newbounds = $.pointfindercalculatebounds(lat,lng,form_radius_val,form_radius_unit_name);
		
		return newbounds;
	}

	$.pointfindersetboundsex = function(lat,lng){
		var form_radius_val = $('#pointfinder_radius_search-view2').attr("value");
	 	var form_radius_unit = $('#pointfinder_google_search_coord_unit').attr("value");
		var form_radius_unit_name = 'mi';
		
		if (form_radius_unit != 'Mile') {
	 		form_radius_val = parseInt(form_radius_val);
	 		if (isNaN(form_radius_val)) {
	 			form_radius_val = theme_map_functionspf.defmapdist;
	 		}
	 		var form_radius_val_ex = (parseInt(form_radius_val)*1000);
	 		form_radius_unit_name='km';
	 	} else{
	 		form_radius_val = parseInt(form_radius_val);
	 		if (isNaN(form_radius_val)) {
	 			form_radius_val = theme_map_functionspf.defmapdist;
	 		}
	 		var form_radius_val_ex = ((parseInt(form_radius_val)*1000)*1.60934);
	 		form_radius_unit_name='mi';
	 	};


		var newbounds = $.pointfindercalculatebounds(lat,lng,form_radius_val,form_radius_unit_name);
		$('.pfsearchresults-container').attr("data-ne",newbounds[0]);
		$('.pfsearchresults-container').attr("data-ne2",newbounds[2]);
		$('.pfsearchresults-container').attr("data-sw",newbounds[1]);
		$('.pfsearchresults-container').attr("data-sw2",newbounds[3]);
	}

	function pfgetUrlVars() {
	    var vars = {};
	    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	        vars[key] = value;
	    });
	    return vars;
	}

	$.pfgetUrlParam = function(parameter, defaultvalue){
	    var urlparameter = defaultvalue;
	    if(window.location.href.indexOf(parameter) > -1){
	        urlparameter = pfgetUrlVars()[parameter];
	        }
	    return urlparameter;
	}

	$(function(){
    	window.addEventListener('popstate', function(e){
			console.log(e);
			if (e.state != null) {
				$.pfremovebyresults();
				$.pfgetpagelistdata({
					saction : e.state.saction,
					sdata : e.state.sdata,
					dtx : e.state.dtx,
					ne : e.state.ne,
					sw : e.state.sw,
					ne2 : e.state.ne2,
					sw2 : e.state.sw2,
					grid : e.state.grid,
					pfg_orderby : e.state.pfg_orderby,
					pfg_number : e.state.pfg_number,
					page : e.state.page,
					from : e.state.from,
					ohours: e.state.ohours,
					popstate: true
				});
			}
		});
    })

	$.pfdefinestate = function( options ){
		var settings = $.extend({
            saction : '',
            sdata : '',
            dtx : '',
            show : 1,
            ne : '',
            sw : '',
            ne2 : '',
            sw2 : '',
            grid : '',
            cl:theme_scriptspf.pfcurlang,
            pfg_orderby : '',
            pfg_order : '',
            pfg_number : '',
            page : '',
            pfcontainerdiv : '.pfsearchresults',
            pfcontainershow : '.pfsearchgridview',
            from: '',
            ohours: '',
            state_uri: '',
            state_base: ''
        }, options );

		var state_uri_output = ' ';

		//console.log('-------------------------------------------');

		//console.log('State BASE Area:');
		if (typeof settings.state_base != 'undefined') {
			//console.log(settings.state_base);
		}else{
			//console.log('UNDEFINED');
		}
		//console.log('State URL Area:');
		if (typeof settings.state_uri != 'undefined') {
			//console.log(settings.state_uri);
		}else{
			//console.log('UNDEFINED');
		}
		//console.log('History Area:');
		//console.log(history);

		//console.log('-------------------------------------------');

		if (typeof settings.state_uri != 'undefined') {
			if (settings.state_uri != '') {
			state_uri_output = '?'+$.param(settings.state_uri);
			}
		}

		history.pushState({
			saction : settings.saction,
			sdata : settings.sdata,
			dtx : settings.dtx,
			ne : settings.ne,
			sw : settings.sw,
			ne2 : settings.ne2,
			sw2 : settings.sw2,
			grid : settings.pfg_grid,
			pfg_orderby : settings.pfg_orderby,
			pfg_number : settings.pfg_number,
			page : settings.page,
			from : settings.from,
			ohours: ''+settings.ohours+''
		}, '', state_uri_output);

	}

	
	$.pfgetpagelistdata = function( options ) {

		if (options != null) {
			//console.log('Function Start Area:');
			//console.log(options);
			//console.log(typeof options.from != 'undefined');
			if (options.popstate != true && typeof options.from != 'undefined') {

				//console.log('Type of grid:');
				//console.log(typeof options.grid);
				if(typeof options.ohours != 'undefined'){
					var opt_ohours = ''+options.ohours+''
				}else{
					var opt_ohours = ''
				}

				if(typeof options.grid == 'undefined' && ((options.from == 'halfmapx' || options.from == 'halfmap') || ( options.from == 'topmap' || options.from == 'topmapx'))){

					if (options.from == 'halfmapx') {
						options.from = 'halfmap';
					}

					if (options.from == 'topmapx') {
						options.from = 'topmap';
					}

					$.pfdefinestate({
						saction : options.saction,
						sdata : options.sdata,
						dtx : options.dtx,
						ne : options.ne,
						sw : options.sw,
						ne2 : options.ne2,
						sw2 : options.sw2,
						from: options.from,
						ohours: opt_ohours,
						page: 1,
						state_base: 'Start Area 1'
					});
				}

				if (typeof options.grid == 'undefined' && (options.from == 'halfmapx' || options.from == 'topmapx')) {
					/* From minisearch */
					var state_uri;
					//console.log('Type of saction:');
					//console.log(options.saction);
					//console.log('Type of sdata:');
					//console.log(typeof options.sdata[0]);
					if (typeof options.sdata[0] != 'undefined') {
						state_uri = {};
						$.each(options.sdata, function(index, val) {
							 state_uri[val.name] = val.value;
						});
					}else{
						state_uri = options.sdata;
					}

					if (options.from == 'halfmapx') {
						options.from = 'halfmap';
					}

					if (options.from == 'topmapx') {
						options.from = 'topmap';
					}
					
					$.pfdefinestate({
						saction : options.saction,
						sdata : options.sdata,
						dtx : options.dtx,
						ne : options.ne,
						sw : options.sw,
						ne2 : options.ne2,
						sw2 : options.sw2,
						from: options.from,
						ohours: opt_ohours,
						state_uri: state_uri,
						state_base: 'Start Area 2'
					});
				}
				
			}
		}

        var settings = $.extend({
            saction : '',
            sdata : '',
            dtx : '',
            show : 1,
            ne : '',
            sw : '',
            ne2 : '',
            sw2 : '',
            grid : '',
            cl:theme_scriptspf.pfcurlang,
            pfg_orderby : '',
            pfg_order : '',
            pfg_number : '',
            page : '',
            pfcontainerdiv : '.pfsearchresults',
            pfcontainershow : '.pfsearchgridview',
            from: '',
            ohours: ''
        }, options );

        
		var pfscrolltoresults = function(){
			$.smoothScroll({
				scrollTarget: '.pfsearchgridview',
				offset: -75
			});
		};
		

		var pfgridloadingtoggle = function(status){
			if(status == 'hide'){
				if($('.pfsearchgridview .pfsearchresults-loading').length>0){
					$('.pfsearchgridview').remove();
					$('.pfsearchgridview').hide('fade',{ direction: "up" },300)
				};
			}else{
				$('.pfsearchresults-container').html('<div class= "pfsearchresults pfsearchgridview"><div class="pfsearchresults-loading"><div class="pfsresloading pfloadingimg"></div></div></div>');
				$('.pfsearchgridview').show('fade',{ direction: "up" },300)
			}
		}

		
		var pfgridloadingtoggle2 = function(status){
			if(status == 'hide'){
				if($('.pfsearchresults-loading').length>0){
					$('.pfsearchresults-loading').remove();
				};
			}else{
				$('.pfsearchresults-container').append('<div class="pfsearchresults-loading"><div class="pfsresloading pfloadingimg"></div></div>');
			}
		}

		if($('.pfsearchgridview').length <= 0){
			
			if (!$.pf_mobile_check() && (settings.grid == 'grid1' || $.pfgetUrlParam('grid','') == 'grid1')) {
				settings.grid = 'grid2';
			}else if (!$.pf_tablet_check() && (settings.grid == 'grid1' || $.pfgetUrlParam('grid','') == 'grid1')) {
				settings.grid = 'grid2';
			}else if (!$.pf_tablet2_check() && (settings.grid == 'grid1' || $.pfgetUrlParam('grid','') == 'grid1')) {
				settings.grid = 'grid2';
			}

			$.ajax({
				beforeSend:function(){
					pfgridloadingtoggle('show');
				},
				type: 'POST',
				cache:false,
				dataType: 'html',
				url: theme_scriptspf.ajaxurl,
				data: { 
					'action': 'pfget_listitems',
					'act': settings.saction,
					'dt': settings.sdata,
					'dtx': settings.dtx,
					'ne': settings.ne,
					'sw': settings.sw,
					'ne2': settings.ne2,
					'sw2': settings.sw2,
					'cl': settings.cl,
					'grid': settings.grid,
					'pfg_orderby': settings.pfg_orderby,
					'pfg_order': settings.pfg_order,
					'pfg_number': settings.pfg_number,
					'pfcontainerdiv': '.pfsearchresults',
					'pfcontainershow': '.pfsearchgridview',
					'page': settings.page,
					'from': settings.from,
					'security': theme_scriptspf.pfget_listitems,
					'pflat':''+$.pointfinder_pflatp+'',
                    'pflng':''+$.pointfinder_pflngp+'',
                    'ohours': settings.ohours
				},
				success:function(data){
					pfgridloadingtoggle('hide');

					if($.isEmptyObject($.pfsortformvars)){
						$.pfsortformvars = {};
					};
					
					if(settings.page == '' || settings.page == null || settings.page <= 0){$.pfsortformvars.page = 1;}
					if(!$.isEmptyObject($.pfsearchformvars)){
						$.pfsortformvars.saction = $.pfsearchformvars.action;
						$.pfsortformvars.sdata = $.pfsearchformvars.vars;
					}else{
						$.pfsortformvars.saction = settings.saction;
						$.pfsortformvars.sdata = settings.sdata;
					};
					
			
					$('.pfsearchresults-container').append(data);


					if ((settings.from == 'halfmap' || settings.from == 'topmap') &&  $('.pf-results-for').length > 0  && settings.sdata != '') {

						if (typeof $('.pf-page-container').attr("data-oldtext") == 'undefined' ) {
							$('.pf-page-container').attr("data-oldtext",$('.pf-results-for').text());
						}
						
						$('.pf-results-for').text($('.pfsearchresults').data("taxonomyname"));

						if ($('.pfsearchresults').data("foundposts") != "0") {
							$('.pffoundpp').text($('.pfsearchresults').data("foundposts")+' '+$('.pfsearchresults').data("fpt"));
						}else{
							$('.pffoundpp').text(" ");
						}

					}else if((settings.from == 'halfmap' || settings.from == 'topmap') &&  $('.pf-results-for').length > 0  && settings.sdata == ''){
						
						if ($('.pf-page-container').attr("data-oldtext") != '' && typeof $('.pf-page-container').attr("data-oldtext") != 'undefined' ) {
							
							$('.pf-results-for').text($('.pf-page-container').attr("data-oldtext"));
							$('.pffoundpp').text($('.pfsearchresults').data("foundposts")+' '+$('.pfsearchresults').data("fpt"));

						}else{

							$('.pffoundpp').text($('.pfsearchresults').data("foundposts")+' '+$('.pfsearchresults').data("fpt"));

						}

					}

					if(settings.show){
						$('.pfsearchgridview').show('fade',{ direction: "up" },300)
						//if(settings.from != 'halfmap' || settings.from != 'topmap'){pfscrolltoresults();}
					}

					if ($('body').hasClass('pfhalfpagemapview') || $('body').hasClass('pftoppagemapview')) {
						$.PFNewMapSys.addMarkers();
					}
					
					
					$('.pfsearchresults-filters .pfgridlist6').on('click',function(){
						$.pfremovebyresults();
					});

					
					var layout_modes = {fitrows: "fitRows",masonry: "masonry"};
					var pfajaxlistdmip = function(){
						$('.pfsearchresults-content').each(function(){
				            var $container = $(this);
				            var $thumbs = $container.find(".pfitemlists-content-elements");
				            var layout_mode = $thumbs.attr("data-layout-mode");
				            $thumbs.isotope({
				                itemSelector : ".isotope-item",
				                layoutMode : (layout_modes[layout_mode]==undefined ? "fitRows" : layout_modes[layout_mode])
				            });
				           
				        });
					};

					setTimeout(function() {pfajaxlistdmip();}, 700);
					setTimeout(function() {pfajaxlistdmip();}, 1500);
					setTimeout(function() {pfajaxlistdmip();}, 2000);
					setTimeout(function() {pfajaxlistdmip();}, 2500);
					setTimeout(function() {pfajaxlistdmip();}, 5000);

					function pf_search_elements(gridnum,ohours){
							
						$.pfsortformvars.pfg_orderby = $('.pfsearchgridview').find('.pfsearch-filter').attr("value");
						$.pfsortformvars.pfg_number = $('.pfsearchgridview').find('.pfsearch-filter-number').attr("value");
						$.pfsortformvars.from = $('.pfsearchgridview').find('.pfsearch-filter-from').attr("value");

						if (gridnum != null) {
							$.pfsortformvars.pfg_grid = gridnum;
						}else{
							$.pfsortformvars.pfg_grid = $('.pfsearchresults-filters-right .pfgridlistit.pfselectedval').attr('data-pf-grid');
						}
						
						$.pfremovebyresults();
						
						$.pfgetpagelistdata({
							saction : $.pfsortformvars.saction,
							sdata : $.pfsortformvars.sdata,
							dtx : settings.dtx,
							ne : settings.ne,
							sw : settings.sw,
							ne2 : settings.ne2,
							sw2 : settings.sw2,
							grid : $.pfsortformvars.pfg_grid,
							pfg_orderby : $.pfsortformvars.pfg_orderby,
							pfg_number : $.pfsortformvars.pfg_number,
							page : $.pfsortformvars.page,
							from : $.pfsortformvars.from,
							ohours: ''+ohours+''
						});

						var state_uri = $.pfsortformvars.sdata;

						if (state_uri == '') {
							state_uri = {};
						}

						if (typeof $.pfsortformvars.sdata[0] != 'undefined') {
							state_uri = {};
							$.each($.pfsortformvars.sdata, function(index, val) {
								 state_uri[val.name] = val.value;
							});
						}

						if (settings.ne != '') {state_uri.ne = settings.ne};
						if (settings.sw != '') {state_uri.sw = settings.sw};
						if (settings.ne2 != '') {state_uri.ne2 = settings.ne2};
						if (settings.sw2 != '') {state_uri.sw2 = settings.sw2};
						if ($.pfsortformvars.pfg_grid != '') {state_uri.grid = $.pfsortformvars.pfg_grid};
						if ($.pfsortformvars.pfg_orderby != '') {state_uri.pfg_orderby = $.pfsortformvars.pfg_orderby};
						if ($.pfsortformvars.pfg_number != '') {state_uri.pfg_number = $.pfsortformvars.pfg_number};
						if ($.pfsortformvars.page != '') {state_uri.page = $.pfsortformvars.page};
						if ($.pfsortformvars.from != '') {state_uri.from = $.pfsortformvars.from};
						if (ohours != '') {state_uri.ohours = ''+ohours+'';}

					
						if($.pfsortformvars.from != ''){
							$.pfdefinestate({
								saction : $.pfsortformvars.saction,
								sdata : $.pfsortformvars.sdata,
								dtx : settings.dtx,
								ne : settings.ne,
								sw : settings.sw,
								ne2 : settings.ne2,
								sw2 : settings.sw2,
								grid : $.pfsortformvars.pfg_grid,
								pfg_orderby : $.pfsortformvars.pfg_orderby,
								pfg_number : $.pfsortformvars.pfg_number,
								page : $.pfsortformvars.page,
								from : $.pfsortformvars.from,
								ohours: ''+ohours+'',
								state_uri: state_uri,
								state_base: 'Middle Area'
							});
						}
					}

					

					function pointfinder_location_success(pos){
						$.pointfinder_pflatp = pos.coords.latitude;
						$.pointfinder_pflngp = pos.coords.longitude;
						pfgridloadingtoggle2('hide');
						pf_search_elements();
					}

					function pointfinder_location_error(data){
						console.log('error');
						console.log(data);
						pfgridloadingtoggle2('hide');
					}

					function pointfinder_selectElement(id, valueToSelect) {    
					    var element = document.getElementById(id);
					    element.value = valueToSelect;
					}
					
					
					$('.pfajax_paginate a').on('click',function(e){
						e.preventDefault();

						if($(this).hasClass('prev')){
							$.pfsortformvars.page--;
						}else if($(this).hasClass('next')){
							$.pfsortformvars.page++;
						}else{
							$.pfsortformvars.page = $(this).text();
						}
						
						pf_search_elements();
					});
										
					$('.pfsearchresults-filters-right .pfgridlistit').on('click',function(e){
						e.preventDefault();
						pf_search_elements($(this).attr('data-pf-grid'));
					});

					$('.openedonlybutton').on('click',function(e){
						e.preventDefault();
						var icon = $(this).find('i');
						var ohstatus;
						if (icon.hasClass('far')) {
							$(this).attr('data-status','passive');
							ohstatus = 'active';
							icon.switchClass('far','fas');
							$(this).attr('title', $(this).attr('data-passive'));
						}else{
							$(this).attr('data-status','active');
							ohstatus = 'passive';
							icon.switchClass('fas','far');
							$(this).attr('title', $(this).attr('data-active'));
						}

						pf_search_elements(null,ohstatus);

					});

					$('.pf-resetfilters-button-txt').one('click',function(event) {
						event.preventDefault();
						if ($('body').hasClass('pfhalfpagemapview')) {
							$('#pf-resetfilters-button').trigger('click');
						}else{
							$.pfremovebyresults();

							$.pfloadlistings();
						}
					});

					if ($('.openedonlybutton').length > 0) {
						$('.openedonlybutton').tooltip(
			  				{
							  position: { 
							  	my: 'center-3',
							  	at: 'top center-35',
							  	collision: "none",
							  	using: function( position, feedback ) {
									$( this ).css( position );
									$( this.firstChild )
									.addClass( "pointfinderarrow_box" )
									.addClass( "wpfquick-tooltip" );

									if (feedback.important == 'horizontal') {
										$( this.firstChild )
										.addClass( feedback.vertical );
									} else {
										$( this.firstChild )
										.addClass( feedback.horizontal );
									}
						        }
							  },
							  show: {effect: "blind", duration: 800},
							  hide: {effect: "blind"}
							}
			  			);
		  			}
					
					if (settings.from == 'halfmap' || settings.from == 'topmap') {
						if($.pf_tablet2_check() && theme_scriptspf.ttstatus == 1){
				  			$('.pfquicklinks a').tooltip(
				  				{
								  position: { 
								  	my: 'center-3',
								  	at: 'top center-35',
								  	collision: "none",
								  	using: function( position, feedback ) {
										$( this ).css( position );
										$( this.firstChild )
										.addClass( "pointfinderarrow_box" )
										.addClass( "wpfquick-tooltip" );

										if (feedback.important == 'horizontal') {
											$( this.firstChild )
											.addClass( feedback.vertical );
										} else {
											$( this.firstChild )
											.addClass( feedback.horizontal );
										}
							        }
								  },
								  show: {effect: "blind", duration: 800},
								  hide: {effect: "blind"}
								}
				  			);
				  		}
						if ($('#pointfinder_google_search_coord').length > 0 && $('#pointfinder_google_search_coord').attr("value").length !== 0 ) {
							var mysplitp = $('#pointfinder_google_search_coord').attr("value").split(',');
					 		var form_radius_val = $('#pointfinder_radius_search-view2').attr("value");
						 	var form_radius_unit = $('#pointfinder_google_search_coord_unit').attr("value");
						 	var form_radius_unit_name = 'mi';
						 	if (typeof form_radius_val == 'undefined') {
						 		form_radius_val = $('#pfdirectorymap').data("gldistance");
						 	}

						 	if (form_radius_unit != 'Mile') {
						 		form_radius_val = parseInt(form_radius_val);
						 		if (isNaN(form_radius_val)) {
						 			form_radius_val = theme_map_functionspf.defmapdist;
						 		}
						 		var form_radius_val_ex = (parseInt(form_radius_val));
						 		form_radius_unit_name='km';
						 	} else{
						 		form_radius_val = parseInt(form_radius_val);
						 		if (isNaN(form_radius_val)) {
						 			form_radius_val = theme_map_functionspf.defmapdist;
						 		}
						 		var form_radius_val_ex = ((parseInt(form_radius_val))*1.60934);
						 		form_radius_unit_name='mi';
						 	}
						 	
				            $.pointfindersplayergroup = L.marker(L.latLng(parseFloat(mysplitp[0]),parseFloat(mysplitp[1])),{
				            	icon: L.icon.pulse({iconSize:[16,16],fillColor:'#2A93EE',color:'#2A93EE'})
				            }).bindPopup(theme_scriptspf.locatefound).addTo($.pointfindernewmapsys);

				            if ($('.pfsearchresults.pflistgridview').attr("data-foundposts") == 0) {
				            	var boundsx = $.pointfindergetbounds(parseFloat(mysplitp[0]),parseFloat(mysplitp[1]));
				            	$.pointfindernewmapsys.fitBounds([[boundsx[0], boundsx[2]],[boundsx[1], boundsx[3]]],{padding: [100,100]});
				            }else{
				            	$.pointfindernewmapsys.fitBounds($.pointfindernewmapsys.getBounds(),{padding: [100,100]});
				            }
				            
				        }
					}


					$(function(){

						if($.pf_tablet2_check()){
							if ($('.pficonltype.pficonloc').length > 0) {
								$('.pficonltype.pficonloc').tooltip(
					  				{
									  position: { 
									  	my: 'center-3',
									  	at: 'top center-35',
									  	collision: "none",
									  	using: function( position, feedback ) {
											$( this ).css( position );
											$( this.firstChild )
											.addClass( "pointfinderarrow_box" )
											.addClass( "wpfquick-tooltip" );

											if (feedback.important == 'horizontal') {
												$( this.firstChild )
												.addClass( feedback.vertical );
											} else {
												$( this.firstChild )
												.addClass( feedback.horizontal );
											}
								        }
									  },
									  show: {effect: "blind", duration: 800},
									  hide: {effect: "blind"}
									}
					  			);
							}

							if ($('.pf3col .pflticon').length > 0 || $('.pf4col .pflticon').length > 0) {

								$('.pflticon').tooltip(
					  				{
									  position: { 
									  	my: 'center-3',
									  	at: 'top center-35',
									  	collision: "none",
									  	using: function( position, feedback ) {
											$( this ).css( position );
											$( this.firstChild )
											.addClass( "pointfinderarrow_box" )
											.addClass( "wpfquick-tooltip" );

											if (feedback.important == 'horizontal') {
												$( this.firstChild )
												.addClass( feedback.vertical );
											} else {
												$( this.firstChild )
												.addClass( feedback.horizontal );
											}
								        }
									  },
									  show: {effect: "blind", duration: 800},
									  hide: {effect: "blind"}
									}
					  			);
							}
						}

						if ($('#pfsearch-filter').length > 0) {
							
							if ($('#pfsearch-filter').val() == '') {
								pointfinder_selectElement('pfsearch-filter',settings.pfg_orderby);
							}

							$('#pfsearch-filter').dropdown({
								autoResize: 0,
								keyboard:true,
								nested:true,
								selectParents:false
							});
						
							$('#pfsearch-filter').on( 'dropdown.select', function( e, item, previous, dropdown ) {
								if (item.value == 'nearby' || item.value == 'distance') {
									pfgridloadingtoggle2('show');
						      		navigator.geolocation.getCurrentPosition(pointfinder_location_success, pointfinder_location_error,{enableHighAccuracy:true, timeout: 5000, maximumAge: 0});
									pointfinder_selectElement('pfsearch-filter',item.value);
								}else{
									pointfinder_selectElement('pfsearch-filter',item.value);
									pf_search_elements();
								}
							});
						}
						if ($('#pfsearch-filter-number').length > 0) {
							$('#pfsearch-filter-number').dropdown({
								autoResize: 0,
								keyboard:true,
								nested:false,
								selectParents:true
							});

							$('#pfsearch-filter-number').on( 'dropdown.select', function( e, item, previous, dropdown ) {
								pointfinder_selectElement('pfsearch-filter-number',item.value);
								pf_search_elements();
							});
						}

					});
				},
				error: function (request, status, error) {
					pfgridloadingtoggle('hide');
					$('.pfsearchresults-container').append('<div class= "pfsearchresults"><div class="pfsearchresults-loading" style="text-align:center"><strong>An error occured!</strong></div></div>');
				},
				complete: function(){
					
				},
			});
		}else{
			$('.pfsearchgridview').show('fade',{ direction: "up" },300)
			//if (settings.from != 'halfmap' || settings.from != 'topmap') {pfscrolltoresults();}
		}
    };

    
		
})(jQuery);