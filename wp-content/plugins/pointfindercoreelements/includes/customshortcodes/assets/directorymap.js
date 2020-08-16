(function($) {
	"use strict";
	$.PFDirectoryMap = {
	    init: function(handler){
	      $.pointfinderdirectorymap = $.pointfinderbuildmap(handler);
	    },
	    addMarkers: function(){
	    	
	    	var data = $.PFDirectoryMapData;

			$.pointfindernewmapclearlayers();
	    	
			if (data.found_posts > 0 ) {

				if ($('#pfdirectorymap').data("cluster")== 1) {
	    			var layerGroup = L.layerGroup();
	    		}else{
	    			var layerGroup = L.featureGroup();
	    		}

	    		$.pointfindermarkerel = [];
	    		var i = 0;

	    		var icontxt,iconanchor,marker;
	    		
	    		$.each(data.data, function(index, val) {

	    			if (typeof val.latLng != 'undefined') {
	    				if (val.icontype == 2){
	    					if (val.icon.includes("3-small")) {
    							iconanchor = [0, -11];
    						}else{
    							iconanchor = [0, -31];
    						}
    						icontxt = val.icon;
    					}else{
    						if (pficoncategories[""+val.icon+""].includes("3-small")) {
    							iconanchor = [0, -11];
    						}else{
    							iconanchor = [0, -31];
    						}
    						icontxt = pficoncategories[""+val.icon+""];
    					}
	    			 	marker = $.pointfindermarkerel[i] = $.pointfinderbuildmarker('pfdirectorymap',$.pointfinderdirectorymap,val.latLng[0],val.latLng[1],val.id,val.title,icontxt,iconanchor);
			      		layerGroup.addLayer(marker);
			      		i++;
			      	}
	    		});
	    		
	    		if ( i > 1 ) {
	    			$.pftogglewnotification('<a id="pfshowsearchresults" class="pfpointercursor pfshowsearchresults">'+i+' '+theme_pfdirectorymap.foundtext+'</a>',theme_pfdirectorymap.autoclosetimei,'pfnotfoundimagei');
	    			$.pointfinderbuildcluster('pfdirectorymap',$.pointfinderdirectorymap,layerGroup);
				}else if( i == 1){
					$.pftogglewnotification('<a id="pfshowsearchresults" class="pfpointercursor pfshowsearchresults">'+i+' '+theme_pfdirectorymap.foundtext+'</a>',theme_pfdirectorymap.autoclosetimei,'pfnotfoundimagei');
					marker.addTo($.pointfinderdirectorymap);
					$.pointfinderdirectorymap.flyTo(marker.getLatLng(),16,{animate:false});
				}else if( i == 0){
					$.pftogglewnotification(theme_pfdirectorymap.notfoundtext,theme_pfdirectorymap.autoclosetime,'pfnotfoundimage');
				}
				
			}
		},
		geolocateMarkers: function(latlng,customdistance){
		
			var data = $.PFDirectoryMapData;
			$.pointfindernewmapclearlayers();
	    	

			if (data.found_posts > 0 ) {
				
				if ($('#pfdirectorymap').data("cluster")== 1) {
	    			var layerGroup = L.layerGroup();
	    		}else{
	    			var layerGroup = L.featureGroup();
	    		}

	    		var i = 0;
	    		var contains = false;
	    		$.pointfindermarkerel = [];
	    		var marker,icontxt,iconanchor;

	    		if (isNaN(customdistance)) {
		 			customdistance = theme_map_functionspf.defmapdist;
		 		}
	    		customdistance = customdistance * 1000;
	    		
	    		var circleCheck = L.circle(latlng,{
		 			radius: customdistance
	            }).addTo($.pointfinderdirectorymap);

	    		var cicleCheckBounds = circleCheck.getBounds();
	    		circleCheck.remove();
	    		$.each(data.data, function(index, val) {
	    			
	    			if (typeof val.latLng != 'undefined') {
	    				
	    				
	    				if (cicleCheckBounds.contains(L.latLng(val.latLng[0],val.latLng[1]))) {
	    					contains = true;
	    				} else {
	    					contains = false;
	    				}
	    				
	    				
	    				if (contains){

	    					if (val.icontype == 2){
		    					if (val.icon.includes("3-small")) {
	    							iconanchor = [0, -11];
	    						}else{
	    							iconanchor = [0, -31];
	    						}
	    						icontxt = val.icon;
	    					}else{
	    						if (pficoncategories[""+val.icon+""].includes("3-small")) {
	    							iconanchor = [0, -11];
	    						}else{
	    							iconanchor = [0, -31];
	    						}
	    						icontxt = pficoncategories[""+val.icon+""];
	    					}

	    					if (val.icontype == 2){
	    						icontxt = val.icon;
	    					}else{
	    						icontxt = pficoncategories[""+val.icon+""];
	    					}
	    					$.pointfindermarkerel[i] = $.pointfinderbuildmarker('pfdirectorymap',$.pointfinderdirectorymap,val.latLng[0],val.latLng[1],val.id,val.title,icontxt,iconanchor);
			      			layerGroup.addLayer($.pointfindermarkerel[i]);
				      		i++;
			      		}
			      	}
	    		});
	    		$.pftogglewnotification('<a id="pfshowsearchresults" class="pfpointercursor pfshowsearchresults">'+i+' '+theme_pfdirectorymap.foundtext+'</a>',theme_pfdirectorymap.autoclosetimei,'pfnotfoundimagei');
	    		if ( i >= 1 ) {
	    			$.pointfinderbuildcluster('pfdirectorymap',$.pointfinderdirectorymap,layerGroup);
				}

			}else{
				$.pftogglewnotification(theme_pfdirectorymap.notfoundtext,theme_pfdirectorymap.autoclosetime,'pfnotfoundimage');
			}
		}

	  }

	$.pftogglewnotificationclearex = function(){
		if($('.pf-err-button').is(':visible')){
			$('.pf-err-button').hide('fast');
		}else{
			$('.pf-err-button').show({ effect: "fade",direction: "up" },0);
		}
	};

	$.PFLoadNewMarkers = function(ne,sw,ne2,sw2,saction,sdata){
		$.typeofsdata = typeof sdata;
		var kxdata = [{name:'pointfinderltypes',value:''+$('.pfsearchresults-container').data("lt")+''},{name:'pointfinderlocations',value:''+$('.pfsearchresults-container').data("lc")+''},{name:'pointfinderconditions',value:''+$('.pfsearchresults-container').data("co")+''},{name:'pointfinderitypes',value:''+$('.pfsearchresults-container').data("it")+''},{name:'pointfinderfeatures',value:''+$('.pfsearchresults-container').data("fe")+''}];
		$.ajax({
		  beforeSend: function(){$(".pfmaploading").fadeIn("slow");},
		  type: 'POST',
		  dataType: 'JSON',
		  url: theme_scriptspf.ajaxurl,
		  cache:false,
		  data: {
		  	'action': 'pfget_markers',
		  	'act': ($('.pfsearchresults-container').data("lt") != '')?'search':saction,
		  	'spl': $('.pfsearchresults-container').spl,
		  	'splo': $('.pfsearchresults-container').splo,
		  	'splob': $('.pfsearchresults-container').splob,
			'ne': ne,
			'sw': sw,
			'ne2': ne2,
			'sw2': sw2,
			'dt': sdata,
		  	'dtx':kxdata,
		  	'ppp':$('#pfdirectorymap').data("ppp"),
		  	'paged': $('#pfdirectorymap').data("paged"),
		  	'order': $('#pfdirectorymap').data("order"),
		  	'orderby': $('#pfdirectorymap').data("orderby"),
		  	'cl':theme_scriptspf.pfcurlang,
		  	'security': theme_scriptspf.pfget_markers
		  },
		  success:function(data){
		  	
		  	$("#pfdirectorymap").attr('data-found', data.found_posts);
		  	
			
			/*<?php if($setup15_mapnotifications_dontshow_i == 1){
				$s_fix_word = '$.typeofsdata != "undefined"';
				echo '$.pftogglewnotificationclearex();';
			}else{
				$s_fix_word = '1==1';
			}
			?>*/

			if(data.found_posts == 0){

				$.pftogglewnotificationclearex();
				$.pftogglewnotification(theme_pfdirectorymap.notfoundtext,theme_pfdirectorymap.autoclosetime,'pfnotfoundimage');
				$.pointfindernewmapclearlayers();
				$.pointfinderdirectorymap.setView(L.latLng(parseFloat($('#pfdirectorymap').data('lat')),parseFloat($('#pfdirectorymap').data('lng'))));

			}else if(data.found_posts > 0 ){
				$.PFDirectoryMapData = data;
				$.PFDirectoryMap.addMarkers();
				
				if ($('#pfdirectorymap').data("glstatus") == 1) {
		        	$.pointfinderlocationmark.start();
		        } 
				if ($('#pointfinder_google_search_coord').val() == '') {
					$.PFDirectoryMap.addMarkers();
				} else {
					if ($('#pointfinder_google_search_coord').length > 0 && $('#pointfinder_google_search_coord').val().length !== 0) {
				 		var mysplitp = $('#pointfinder_google_search_coord').val().split(',');
				 		var form_radius_val = $('#pointfinder_radius_search-view2').val();
					 	var form_radius_unit = $('#pointfinder_google_search_coord_unit').val();
					 	var form_radius_unit_name = 'mi';

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
				 		var latlng = L.latLng(parseFloat(mysplitp[0]),parseFloat(mysplitp[1]));
				 		$.PFDirectoryMap.geolocateMarkers(latlng,form_radius_val_ex);
				 		
			            
			            $.pointfindersplayergroup = L.marker(latlng,{
			            	icon: L.icon.pulse({iconSize:[16,16],fillColor:'#2A93EE',color:'#2A93EE'})
			            }).bindPopup(theme_scriptspf.locatefound).addTo($.pointfinderdirectorymap);
			        }
				}
				
				$.pftogglewnotification('<a id="pfshowsearchresults" class="pfpointercursor pfshowsearchresults">'+data.found_posts+' '+theme_pfdirectorymap.foundtext+'</a>',theme_pfdirectorymap.autoclosetimei,'pfnotfoundimagei');
			}

			var saction;
			var sdata;
			if(!$.isEmptyObject($.pfsearchformvars)){saction = $.pfsearchformvars.action;}else{saction = '';}
			if(!$.isEmptyObject($.pfsearchformvars)){sdata = $.pfsearchformvars.vars;}else{sdata = '';}
			
			if($('.pfsearchresults-container').data("autoopen") == 1){
				if (sdata != '') {
					$.pfgetpagelistdata({saction : saction,sdata : sdata,dtx :kxdata,ne : ne,sw : sw,ne2 : ne2,sw2 : sw2,});
					$.pftogglewnotificationclear();
					$.smoothScroll({scrollTarget: '.pfsearchresults-container',offset: -110});
				};
			}

			$('#pfshowsearchresults').on('click',function(){
				$.pfgetpagelistdata({saction : saction,sdata : sdata,dtx :kxdata,ne : ne,sw : sw,ne2 : ne2,sw2 : sw2});
				$.pftogglewnotificationclear();
				$.smoothScroll({scrollTarget: '.pfsearchresults-container',offset: -110});
			});

		  },
		  error: function(jqXHR,textStatus,errorThrown){
		  	console.log(errorThrown);
		  },
		  complete: function(){
			$(".pfmaploading").fadeOut("slow");
		  },
		});

	};


	

	/* Left locate button action. */
	$.pfgeolocatecontrolbutton = function(latlng){
		$.PFDirectoryMap.geolocateMarkers(latlng);
		$.pointfinderdirectorymap.stopLocate();
	}

	$.pfremovebyresults = function(){        	
		if($('.pfsearchgridview').length > 0){
			$('.pfsearchgridview').remove();
		};
		$('.pfsearchgridview').hide('fade',{ direction: "up" },300)
		
		$.pfscrolltotop();
	};


	$.fn.pointfindermapmenubuild = function(element){
		this.ov = {direction:''};
		this.te = element;
		this.tc = '.pf'+$(this.te).attr('data-pf-content')+'-content';
		this.ti1 = $(this.te).attr('data-pf-icon1');
		this.ti2 = $(this.te).attr('data-pf-icon2');
		this.sp = 300;
		this.st = 'data-pf-toggle';
		

		this.PFSTCheck = function(element){
			var status;
			function strcmp(str1, str2) {
			  return ((str1 == str2) ? 0 : ((str1 > str2) ? 1 : -1));
			}
			
			
			$(".pftogglemenulist > li").each(function(e) {
				
				var te = $(this);
				var tc = '.pf'+$(te).attr('data-pf-content')+'-content';
				var ti1 = $(te).attr('data-pf-icon1');
				var ti2 = $(te).attr('data-pf-icon2');
				
				if($(te).attr('data-pf-toggle') == 'active'){
					
					if(strcmp(te[0]['className'],element) != 0){
						$(tc).css('display','none');
						if($(te).find('i').hasClass(ti1)){
							$(te).find('i').switchClass(ti1,ti2)
						}else{
							$(te).find('i').switchClass(ti2,ti1);
						};
						$(te).attr('data-pf-toggle', 'passive');
						status = 1;
					}
					
				}				
				
			});
			if(status == 1){this.PFSTRun(10)}else{this.PFSTRun(0)}
			
		};
			
		this.PFSTRun = function(t){
				$(this.tc).fadeToggle(this.sp+t);
				if($(this.te).attr(this.st) == 'active'){
					this.PFCheck();
					$(this.te).attr(this.st, 'passive');
				}else{
					this.PFCheck();
					$(this.te).attr(this.st, 'active');
				}
			
	
		};
		
		this.PFCheck = function(){
			if($(this.te).find('i').hasClass(this.ti1)){
				$(this.te).find('i').switchClass(this.ti1,this.ti2)
			}else{
				$(this.te).find('i').switchClass(this.ti2,this.ti1);
			};
		};
		
		this.PFSTCheck(this.te.replace('.',''));
	};

	$(function(){
		
	 	if ( $('#pfdirectorymap').length > 0 ) {
			$.PFDirectoryMap.init('pfdirectorymap');
		}

		$('#pf-resetfilters-button').on('click', function(event) {
			event.preventDefault();
			document.getElementById("pointfinder-search-form").reset();
			$.pfremovebyresults();
			if ($.typeofsdata == 'undefined') {
				$.PFDirectoryMap.addMarkers();
			}else{
				if (typeof $.pointfindersplayergroup == 'object') {
					$('#pointfinder_google_search_coord').val("");
					document.getElementById("pointfinder-search-form").reset();
					$.PFLoadNewMarkers();
				}else{
					$.PFLoadNewMarkers();
				}
			}
		});

		$('#pf-search-button').on('click',function(){
			if ($('#pfdirectorymap').css('z-index') != '-1') {
				$(".pfsearchresults-container").css({
					"position": 'relative',
					"z-index": '999',
					"min-height": '110px',
					"background": '#fff'
				});
			}
			setTimeout(function(){ $.pointfinderdirectorymap.invalidateSize()}, 400);
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
			
			if(form.valid()){
				if ($('#pfsearch-draggable').hasClass('pfsearch-draggable-full') && !$('#pfsearch-draggable').hasClass('pfsearchdrhm')) {
					$( "#pfsearch-draggable" ).toggle( "slide",{direction:"up",mode:"hide"},function(){
				  		$('.pfsopenclose').fadeToggle("fast");
				  		$('.pfsopenclose2').fadeToggle("fast");
				  	});
				};
				
				if ($('#pfdirectorymap').css('z-index') == '-1') {
					$('#pfdirectorymap').css('z-index','1').css('position','').css('width','100%');
					$('#pfcontrol').css('z-index','3').show();
					$('.pfnot-err-button').css('z-index','4');
					$('.pfnotificationwindow').css('z-index','4');
					$(".pfnotificationwindow").show();
					$(".pfnot-err-button-menu").show();
					$('#pfdirectorymap').show();
					$("#wpf-map-container").css("min-height","");
					$("#wpf-map-container").css("z-index",0);
					$('.pf-ex-search-text').remove();
					$('#wpf-map-container').closest('.pf-fullwidth').prev('.upb_video-wrapper').remove();/*Video bg remove*/
					$('#wpf-map-container').closest('.pf-fullwidth').prev('.upb_row_bg').remove();/*Image bg remove*/
					
				};

				$.pfremovebyresults();


				 if ($('#pointfinder_google_search_coord').length > 0 && $('#pointfinder_google_search_coord').val().length !== 0) {
				 	
				 	
				 	var mysplitp = $('#pointfinder_google_search_coord').val().split(',');
				 
				 	
				 	var form_radius_val = $('#pointfinder_radius_search-view2').val();
				 	var form_radius_unit = $('#pointfinder_google_search_coord_unit').val();
				 	var form_radius_unit_name = 'mi';


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
				 	
				 	var coordinate_bounds = $.pointfindercalculatebounds(parseFloat(mysplitp[0]),parseFloat(mysplitp[1]),form_radius_val_ex,'km');


				 	$.PFLoadNewMarkers(coordinate_bounds[0],coordinate_bounds[1],coordinate_bounds[2],coordinate_bounds[3],$.pfsearchformvars.action,$.pfsearchformvars.vars);

					if (!$.pf_mobile_check()) {
						if($('#pfsearch-draggable').hasClass('pfshowmobile') == true){
							$('#pf-primary-search-button i').switchClass('pfadmicon-glyph-96', 'pfadmicon-glyph-627', 'fast',"easeInOutQuad");
							$('#pfsearch-draggable').removeClass('pfshowmobile');
							$('#pfsearch-draggable').hide("fade",{ direction: "up" }, "fast", function(){});	
						}
					};
				}else{
					$.PFLoadNewMarkers('','','','',$.pfsearchformvars.action,$.pfsearchformvars.vars);
				}

				if (!$.pf_tablet2_check()) {
					var direction = $('.psearchdraggable').attr("data-direction");
					if ($('.psearchdraggable').css(direction) == '0px') {
						$('#pf-primary-search-button').trigger('click');
					}
				}
			};
			return false;
		});	

		if($.pf_tablet2_check()){
			$( "#pfsearch-draggable" ).draggable({ 
				containment: "#wpf-map-container", 
				scroll: false, 
				handle: ".pftoggle-move",
				drag: function( event, ui ) {
					if ($('#pfdirectorymap').data('ttstatus') == 1) {
						$( ".pfsearch-header ul li" ).tooltip( "close" );
					}
				}
			});
			
			if ($('#pfdirectorymap').data('ttstatus') == 1) {
				$( '.pfsearch-header ul li' ).tooltip({
				  tooltipClass: "wpfui-tooltip",
				  position: {
					my: "left top",
					at: "left bottom+1",
					of: ".pfsearch-header",
					collision: 'flip'
				  },
				  show: {
					duration: "fast"
				  },
				  hide: {
					effect: "hide"
				  }
				});
				$('.pfsearch-header ul li').on('mouseleave',function(){
					$('.ui-helper-hidden-accessible').hide();
				});
			}
		}

		$(".pftoggle-search" ).attr('data-pf-toggle','active');	
		$(".pftoggle-itemlist" ).attr('data-pf-toggle','passive');	
		
		$('.pftoggle-search').on('click',function(){
			$(this).pointfindermapmenubuild('.pftoggle-search');
		});
		
		$('.pftoggle-itemlist').on('click',function(){
			$(this).pointfindermapmenubuild('.pftoggle-itemlist');
		});
		
		$('.pftoggle-user').on('click',function(){
			$(this).pointfindermapmenubuild('.pftoggle-user');
		});

	});

})(jQuery);