(function($) {
	"use strict";
	$.PFNewMapSys = {
	    init: function(handler){
	    	$.pointfindernewmapsys = $.pointfinderbuildmap(handler);
	    },
	    addMarkers: function(){
	    	if (!$('body').hasClass('pftoppagemapviewdef')) {
		    	$.pointfindernewmapclearlayers();

		    	if ($('.pfitemlists-content-elements').length > 0 && $('.pfsearchresults').data('foundposts') > 0) {
		    		
		    		var layerGroup = L.layerGroup();
		    		$.pointfindermarkerel = [];
		    		var i = 0;
		    		var marker,iconanchor;
		    		if ($('#wpf-map').length > 0) {
	    				var mapname = 'wpf-map';
	    			} else {
	    				var mapname = 'pointfinder-category-map';
	    			}
		    		$.each($('.pfitemlists-content-elements .pflist-item'), function(index, val) {

						if (val.dataset.icon.includes("3-small")) {
							iconanchor = [0, -11];
						}else{
							iconanchor = [0, -31];
						}

		    			if ((val.dataset.lat != undefined) && (val.dataset.lng != undefined)) {
		    				marker = $.pointfindermarkerel[index] = $.pointfinderbuildmarker(mapname,$.pointfindernewmapsys,val.dataset.lat,val.dataset.lng,val.dataset.pid,val.dataset.title,val.dataset.icon,iconanchor);
				      		layerGroup.addLayer(marker);
				      		i++;
				      	}else{
				      		$(this).find('.pfshowmaplink').hide();
				      	}
		    		});
		    		
		    		if ( i > 1 ) {
		    			$.pointfinderbuildcluster(mapname,$.pointfindernewmapsys,layerGroup);
					}else if( i == 1){
						marker.addTo($.pointfindernewmapsys);
						$.pointfindernewmapsys.flyTo(marker.getLatLng(),16,{animate:false});
					}else if( i == 0){
						if (!$('body').hasClass('pfhalfpagemapview') || !$('body').hasClass('pftoppagemapview')) {
							$.pointfindernewmapsys.remove();
							$('#'+mapname+'').remove();
						}
					}
		    	}
		    }	

		},
		geolocateMarkers: function(latlng){
	    	$.pointfindernewmapclearlayers();

	    	if ($('.pfitemlists-content-elements').length > 0 ) {
	    		
	    		var layerGroup = L.layerGroup();

	    		var i = 0;
	    		var marker;
	    		var contains = false;
	    		if ($('#wpf-map').length > 0) {
    				var mapname = 'wpf-map';
    			} else {
    				var mapname = 'pointfinder-category-map';
    			}
    			var customdistance = $('#'+mapname+'').data("gldistance") * 1000;

    			var circleCheck = L.circle(latlng,{
		 			radius: customdistance
	            }).addTo($.pointfindernewmapsys);

	    		var cicleCheckBounds = circleCheck.getBounds();
	    		circleCheck.remove();

	    		$.each($('.pfitemlists-content-elements .pflist-item'), function(index, val) {
	    			
	    			if ((val.dataset.lat != undefined) && (val.dataset.lng != undefined)) {

	    				if (cicleCheckBounds.contains(L.latLng(parseFloat(val.dataset.lat),parseFloat(val.dataset.lng)))) {
	    					contains = true;
	    				} else {
	    					contains = false;
	    				}
	    				if (contains) {
				      		layerGroup.addLayer($.pointfindermarkerel[index]);
				      		i++;
			      		}
			      	}else{
			      		$(this).find('.pfshowmaplink').hide();
			      	}
	    		});
	    		
	    		if ( i >= 1 ) {
	    			$.pointfinderbuildcluster(mapname,$.pointfindernewmapsys,layerGroup);
				}else if( i == 0){
					
					if (!$('body').hasClass('pfhalfpagemapview') || !$('body').hasClass('pftoppagemapview')) {
						$.pointfindernewmapsys.remove();
						$(mapname).remove();
					}
				}
	    	}

		}

 	}


 	$.pfgeolocatecontrolbutton = function(latlng){
		$.PFNewMapSys.geolocateMarkers(latlng);
		$.pointfindernewmapsys.stopLocate();
	}

 	$.pfloadlistings = function(ne,sw,ne2,sw2,saction,sdata,wherex,fromx){

 		if (typeof fromx == 'undefined') {
 			fromx ='halfmap';
 		}

		var kxdata = [{name:'post_tags',value:''+$('.pfsearchresults-container').data("tag")+''},{name:'pointfinderltypes',value:''+$('.pfsearchresults-container').data("lt")+''},{name:'pointfinderlocations',value:''+$('.pfsearchresults-container').data("lc")+''},{name:'pointfinderconditions',value:''+$('.pfsearchresults-container').data("co")+''},{name:'pointfinderitypes',value:''+$('.pfsearchresults-container').data("it")+''},{name:'pointfinderfeatures',value:''+$('.pfsearchresults-container').data("fe")+''}];

		if ($('.pfsearchresults-container').data("sdata") != '') {
			if(sdata == '' || typeof sdata == 'undefined' || sdata == null ){
				sdata = $('.pfsearchresults-container').data("sdata");
				if (typeof saction == 'undefined') {
					if (fromx=='halfmap') {
						fromx = 'halfmapx';
					}
					if (fromx=='topmap') {
						fromx = 'topmapx';
					}
				}
				saction = 'search';
			}

	  		if ($('.pfsearchresults-container').attr("data-ne")) {
	  			ne = $('.pfsearchresults-container').attr("data-ne");
	  			sw = $('.pfsearchresults-container').attr("data-sw");
	  			ne2 = $('.pfsearchresults-container').attr("data-ne2");
	  			sw2 = $('.pfsearchresults-container').attr("data-sw2");
	  		}
	  	}


	  	if (typeof sdata != 'undefined') {

			if (typeof sdata[0] != 'undefined') {
				
				if (sdata[0].name == 'wherefr' && sdata[0].value == 'halfmapsearch') {
					if (fromx=='halfmap') {
						fromx = 'halfmapx';
					}
					if (fromx=='topmap') {
						fromx = 'topmapx';
					}
					saction = 'search';
				}
			}
			
	  	}

	  	if(typeof wherex != "undefined"){ne=wherex[0];sw=wherex[1];ne2=wherex[2];sw2=wherex[3];}
	  	
	  	$.pfgetpagelistdata({saction : saction,sdata : sdata,dtx :kxdata,ne : ne,sw : sw,ne2 : ne2,sw2 : sw2,from : fromx});

	  	setTimeout(function(){$.PFNewMapSys.addMarkers();},10);
	};

	$.pfmapafterworks = function(){
		if ($('#pointfinder_google_search_coord').length > 0 && $('#pointfinder_google_search_coord').val().length == 0){
			$('#pointfinder_google_search_coord').val($('.pfsearchresults-container').data("coordval"));
		}

		var fltf = $('.pfsearchresults-container').data("fltf");
		var csauto = $('.pfsearchresults-container').data("csauto");
		var fltf_get = $('.pfsearchresults-container').data("fltfget");


		if (csauto != '' && fltf.length > 0) {
			if (!$.pf_tablet_check()){
				if ($("#"+fltf+"" )) {
					if (theme_scriptspf.mobiledropdowns != '1') {
						$("#"+fltf+"" ).select2("val",csauto);
					}else{
						$("#"+fltf+" option[value=\""+csauto+"\"]").attr("selected","selected");
					}
				}
			}else{
				if ($("#"+fltf+"" )) {
					$("#"+fltf+"" ).select2("val",csauto);
				}
			}
		} else {
			if (fltf_get.length > 0) {
				if (!$.pf_tablet_check()){
					if ($("#"+fltf+"" )) {					
						if (theme_scriptspf.mobiledropdowns != '1') {
							$("#"+fltf+"" ).select2("val",""+fltf_get+"");
						}else{
							$("#"+fltf+" option[value=\""+fltf_get+"\"]").attr("selected","selected");
						}
					}
				}else{
					if ($("#"+fltf+"" )) {
						$("#"+fltf+"" ).select2("val",""+fltf_get+"");
					}
				}
			}
		}

		var calc_height = $("#pfhalfmapmapcontainer").height();

		if (!$(".wpf-container").hasClass("pftransparenthead") && $.pf_tablet_check()) {
			calc_height = (calc_height - $("#pfheadernav").height());
		}

		if (!$.pf_tablet_check()) {
			calc_height = (calc_height - $("#pfheadernav").height());
		}
	}

	$.pfremovebyresults = function(){        	
		if($('.pfsearchgridview').length > 0){
			$('.pfsearchgridview').remove();
		};
		$('.pfsearchgridview').hide('fade',{ direction: "up" },300)
		
		$.pfscrolltotop();/* in theme-scripts.js */
	};

  	$(function(){

  		if ($('body').hasClass('pfhalfpagemapview')) {
  			
			$.pfloadlistings();
			if ($('#wpf-map').length > 0) {
				$.PFNewMapSys.init('wpf-map');
			}

			if ($('body').hasClass('tax-pointfinderlocations')) {
				var floct = $('.pfsearchresults-container').data("floct");
				var floctdata = $('.pfsearchresults-container').data("lc");
				var csauto = $('.pfsearchresults-container').data("csauto");

				if (csauto != '' && floct.length > 0 && floctdata != '' && $("#"+floct+"").length > 0) {
					if (!$.pf_tablet_check()){
						$("#"+floct+" option[value=\""+csauto+"\"]").attr("selected","selected");
					}else{
						setTimeout(function(){$("#"+floct+"" ).select2("val",csauto);},500);
					}
				}
			}
		}else if ($('body').hasClass('pftoppagemapview')) {
  			
			$.pfloadlistings('','','','','','','','topmap');
			if ($('#wpf-map').length > 0) {
				$.PFNewMapSys.init('wpf-map');
			}

			if ($('body').hasClass('tax-pointfinderlocations')) {
				var floct = $('.pfsearchresults-container').data("floct");
				var floctdata = $('.pfsearchresults-container').data("lc");
				var csauto = $('.pfsearchresults-container').data("csauto");

				if (csauto != '' && floct.length > 0 && floctdata != '' && $("#"+floct+"").length > 0) {
					
					if (!$.pf_tablet_check()){
						$("#"+floct+" option[value=\""+csauto+"\"]").attr("selected","selected");
					}else{
						setTimeout(function(){$("#"+floct+"" ).select2("val",csauto);},500);
					}
				}
			}
		}else if ($('body').hasClass('pftoppagemapviewdef')) {
  			
			$.pfloadlistings('','','','','','','','topmap');
			

			if ($('body').hasClass('tax-pointfinderlocations')) {
				var floct = $('.pfsearchresults-container').data("floct");
				var floctdata = $('.pfsearchresults-container').data("lc");
				var csauto = $('.pfsearchresults-container').data("csauto");

				if (csauto != '' && floct.length > 0 && floctdata != '' && $("#"+floct+"").length > 0) {
					
					if (!$.pf_tablet_check()){
						$("#"+floct+" option[value=\""+csauto+"\"]").attr("selected","selected");
					}else{
						setTimeout(function(){$("#"+floct+"" ).select2("val",csauto);},500);
					}
				}
			}
		}else if ( $('#pfwidgetmap').length > 0 && $('#pointfinder-category-map').length == 0 ) {

			$.PFNewMapSys.init('pfwidgetmap');console.log('Widget Map Loaded');

		}else if($('#pointfinder-category-map').length > 0) {
			$.PFNewMapSys.init('pointfinder-category-map');
			setTimeout(function(){$.PFNewMapSys.addMarkers();},100);
		}

		$('body').on('click touchstart', '.pfshowmaplink', function(e){

			var marker = $.pointfindernewmapsys.getMarkerById($(this).data('pfitemid'),"categorymapmarkers");
			if(marker != null){
				$.pointfindernewmapsys.flyTo(marker.getLatLng(),14,{animate:false});
				marker.openPopup();
				if (!$('body').hasClass('pfhalfpagemapview')) {
					$(window).scrollTop(0);
				}
			}
			
			return false;
		});

		$('#pf-resetfilters-button').on('click', function(event) {
			event.preventDefault();
			$.pfremovebyresults();
			
			var fltf = $('.pfsearchresults-container').data("fltf");
			var floct = $('.pfsearchresults-container').data("floct");
			var fltfdata = $('.pfsearchresults-container').data("lt");
			var floctdata = $('.pfsearchresults-container').data("lc");
			
			var csauto = $('.pfsearchresults-container').data("csauto");
			
			$('#pfsearchsubvalues').html('');
			$('#pointfinder-search-form').find(':input').not(':button, :submit, :reset, :hidden, :checkbox, :radio').val('')
			if (typeof $('#pointfinder_google_search_coord').attr("value") != 'undefined' || $('#pointfinder_google_search_coord').attr("value") != '') {
				$('#pointfinder_google_search_coord').attr("value",'');
				$('input[name=ne]').attr("value",'');$('input[name=ne2]').attr("value",'');$('input[name=sw]').attr("value",'');$('input[name=sw2]').attr("value",'');
			}

			/* If location */
			if (csauto != '' && floct.length > 0 && floctdata != '' && $("#"+floct+"").length > 0) {
				if (!$.pf_tablet_check()){
					if (theme_scriptspf.mobiledropdowns != '1') {
						setTimeout(function(){$("#"+floct+"" ).select2("val",csauto);},500);
					}else{
						$("#"+floct+" option[value=\""+csauto+"\"]").attr("selected","selected");
					}
				}else{
					setTimeout(function(){$("#"+floct+"" ).select2("val",csauto);},500);
				}
			}


			/* If listing type */
			if (csauto != '' && fltf.length > 0 && fltfdata != '') {
				if (!$.pf_tablet_check()){
					if (theme_scriptspf.mobiledropdowns != '1') {
						setTimeout(function(){$("#"+fltf+"" ).select2("val",csauto).trigger('change');},500);
					}else{
						$("#"+fltf+" option[value=\""+csauto+"\"]").attr("selected","selected");
					}
				}else{
					setTimeout(function(){$("#"+fltf+"" ).select2("val",csauto).trigger('change');},500);
				}
			}

         
			var kxdata = [{name:'post_tags',value:''+$('.pfsearchresults-container').data("tag")+''},{name:'pointfinderltypes',value:''+$('.pfsearchresults-container').data("lt")+''},{name:'pointfinderlocations',value:''+$('.pfsearchresults-container').data("lc")+''},{name:'pointfinderconditions',value:''+$('.pfsearchresults-container').data("co")+''},{name:'pointfinderitypes',value:''+$('.pfsearchresults-container').data("it")+''},{name:'pointfinderfeatures',value:''+$('.pfsearchresults-container').data("fe")+''}];
			
			if ($('.pfsearchresults-container').length > 0) {
				if ($('.pfsearchresults-container').data('sdata') != '') {
					$('.pfsearchresults-container').data('sdata','');
				}
			}

			

			if ($('body').hasClass('pftoppagemapview') || $('body').hasClass('pftoppagemapviewdef')) {

				window.history.pushState(null, '', '?s=&serialized=1&action=pfs');
				$.pfgetpagelistdata({saction : '',sdata : '',dtx :kxdata,ne : '',sw : '',ne2 : '',sw2 : '',from : 'topmap'});
			}else{
				window.history.pushState(null, null, window.location.pathname);
				$.pfgetpagelistdata({saction : '',sdata : '',dtx :kxdata,ne : '',sw : '',ne2 : '',sw2 : '',from : 'halfmap'});
			}
	  		
	  		setTimeout(function(){$.PFNewMapSys.addMarkers();},10);
			
		});


		$('#pf-search-button-halfmap').on('click',function(){

			$.pfremovebyresults();
			
			var form = $('#pointfinder-search-form');
			form.validate();
			form.find("div:hidden[id$='_main']").each(function(){ 
				$(this).find('input[type=hidden]').not("#pointfinder_radius_search-view2").not("#pointfinder_radius_search-view").val(""); 
				$(this).find('input[type=text]').val($.pfsliderdefaults.fields[$(this).attr('id')]);
				$(this).find('.slider-wrapper .ui-slider-range').not("#pointfinder_radius_search .ui-slider-range").css('width','0%');
				$(this).find('.slider-wrapper a:nth-child(2)').css('left','0%');
				$(this).find('.slider-wrapper a:nth-child(3)').css('left','100%');
			});
			$.pfsearchformvars = {};
			$.pfsearchformvars.action = 'search';
			$.pfsearchformvars.vars = form.serializeArray();

			
			if ($('#pointfinder_google_search_coord').length > 0 && $('#pointfinder_google_search_coord').val().length !== 0) {
				 	
				 	if (typeof $('.pfsearchresults-container').attr("data-sw2") == 'undefined') {

					 	var mysplitp = $('#pointfinder_google_search_coord').val().split(',');
					 
					 	
					 	var form_radius_val = $('#pointfinder_radius_search-view2').val();
					 	var form_radius_unit = $('#pointfinder_google_search_coord_unit').val();
					 	var form_radius_unit_name = 'mi';
					 	if (typeof form_radius_val == 'undefined') {
					 		form_radius_val = $('#wpf-map').data("gldistance");
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
					 	
					 	var coordinate_bounds = $.pointfindercalculatebounds(parseFloat(mysplitp[0]),parseFloat(mysplitp[1]),form_radius_val_ex,form_radius_unit_name);
					 	
					 	$.pfloadlistings(coordinate_bounds[0],coordinate_bounds[1],coordinate_bounds[2],coordinate_bounds[3],$.pfsearchformvars.action,$.pfsearchformvars.vars);
					 
				 	}else{
				 		$.pfloadlistings($('.pfsearchresults-container').attr("data-ne"),$('.pfsearchresults-container').attr("data-ne2"),$('.pfsearchresults-container').attr("data-sw"),$('.pfsearchresults-container').attr("data-sw2"),$.pfsearchformvars.action,$.pfsearchformvars.vars);
				 	}


					if (!$.pf_mobile_check()) {
						if($('#pfsearch-draggable').hasClass('pfshowmobile') == true){
							$('#pf-primary-search-button i').switchClass('pfadmicon-glyph-96', 'pfadmicon-glyph-627', 'fast',"easeInOutQuad");
							$('#pfsearch-draggable').removeClass('pfshowmobile');
							$('#pfsearch-draggable').hide("fade",{ direction: "up" }, "fast", function(){});	
						}
					};
			}else{
				$.pfloadlistings('','','','',$.pfsearchformvars.action,$.pfsearchformvars.vars);
			}

			if (!$.pf_tablet2_check()) {
				var direction = $('.psearchdraggable').attr("data-direction");
				if ($('.psearchdraggable').css(direction) == '0px') {
					$('#pf-primary-search-button').trigger('click');
				}
			}

			return false;
		});	

  	});

})(jQuery);