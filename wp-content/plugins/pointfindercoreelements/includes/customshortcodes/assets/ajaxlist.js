(function($) {
	"use strict";
	
	$(function(){
		if ($('.pflistgridajaxview').length > 0) {
			
			$.each($('.pflistgridajaxview'), function(index, val) {
			 	var gridrandnoorj = $(this).attr('data-gridorj');
				var gridrandno = $(this).attr('data-grid');

				$.fn.pointfinderajaxpagelist({
				  sgdt:{
				  	'sortby' : $(this).attr('data-sortby'),
					'orderby' : $(this).attr('data-orderby'),
					'items' : $(this).attr('data-items'),
					'cols' : $(this).attr('data-cols'),
					'posts_in' : $(this).attr('data-posts_in'),
					'filters' : $(this).attr('data-filters'),
					'itemboxbg' : $(this).attr('data-itemboxbg'),
					'grid_layout_mode' : $(this).attr('data-grid_layout_mode'),
					'listingtype' : $(this).attr('data-listingtype_x'),
					'itemtype' : $(this).attr('data-itemtype_x'),
					'conditions' : $(this).attr('data-conditions_x'),
					'locationtype' : $(this).attr('data-locationtype_x'),
					'features' : $(this).attr('data-features_x'),
					'featureditems' : $(this).attr('data-featureditems'),
					'featureditemshide' : $(this).attr('data-featureditemshide'),
					'authormode' : $(this).attr('data-authormode'),
					'agentmode' : $(this).attr('data-agentmode'),
					'author' : $(this).attr('data-author'),
					'related' : $(this).attr('data-related'),
					'relatedcpi' : $(this).attr('data-relatedcpi'),
					'tag' : $(this).attr('data-tag'),
					'pfrandomize' : $(this).attr('data-pfrandomize')
				  },
				  show : 1,
				  grid : '',
				  pfg_orderby : '',
				  pfg_order : '',
				  pfg_number : '',
				  pfsearch_filter_ltype : '',
				  pfsearch_filter_itype : '',
				  pfsearch_filter_location : '',
				  page : '',
				  cl:theme_scriptspf.pfcurlang,
				  pfcontainerdiv : 'pflistgridview'+gridrandnoorj+'',
				  pfcontainershow : 'pflistgridviewshow'+gridrandnoorj+'',
				  pfex : 'alist',
				  gridrandnoorj: gridrandnoorj
				});
			});
		}
			
	});

	

	$.fn.pointfinderajaxpagelist = function( options ) {

		var settings = $.extend({
		  sgdt: '',
		  show : 1,
		  grid : '',
		  pfg_orderby : '',
		  pfg_order : '',
		  pfg_number : '',
		  pfsearch_filter_ltype : '',
		  pfsearch_filter_itype : '',
		  pfsearch_filter_location : '',
		  page : '',
		  cl:theme_scriptspf.pfcurlang,
		  pfcontainerdiv : '',
		  pfcontainershow : '',
		  pfex : 'alist',
		  gridrandnoorj: ''
		}, options );

		var gridrandnoorj = settings.gridrandnoorj;

		var pfremovebyresults = function(){
			if($('.pflistgridviewshow'+gridrandnoorj+'').length>0){
			  $('.pflistgridviewshow'+gridrandnoorj+'').remove();
			};


			$(".pflistgridviewshow"+gridrandnoorj+"").hide("fade",{direction: "up" },300)

		};

		var pfscrolltoresults = function(){
			$.smoothScroll({
			  scrollTarget: ".pflistgridviewshow"+gridrandnoorj+"",
			  offset: -110
			});
		};

		var pfgridloadingtoggle = function(status){

			if(status == "hide"){
			  if($(".pflistgridviewshow"+gridrandnoorj+" .pfsearchresults-loading").length>0){
			    $(".pflistgridviewshow"+gridrandnoorj+"").remove();
			    $(".pflistgridviewshow"+gridrandnoorj+"").hide("fade",{direction: "up" },300)
			  };
			}else{
			  $(".pflistgridview"+gridrandnoorj+"-container").append("<div class= 'pfsearchresults pflistgridviewshow"+gridrandnoorj+" pfsearchgridview'><div class='pfsearchresults-loading'><div class='pfsresloading pfloadingimg'></div></div></div>");
			  $(".pflistgridviewshow"+gridrandnoorj+"").show("fade",{direction: "up" },300)
			}
		}

		var pfmakeitperfect = function() {
		
			var layout_modes = {fitrows: 'fitRows',masonry: 'masonry'}
			
			$.each($('.pflistcommonview-content'),function(){
		        var $container = $(this);
		        var $thumbs = $container.find('.pfitemlists-content-elements:not(.owl-carousel)');
		        var layout_mode = $thumbs.attr('data-layout-mode');
		       
		        if ($('.pflistgridview'+gridrandnoorj+'').attr('data-rtl') > 0) {
		        	$thumbs.isotope({
			            itemSelector : '.isotope-item',
			            transformsEnabled: false,
			            isOriginLeft: false,
			            layoutMode : (layout_modes[layout_mode]==undefined ? 'fitRows' : layout_modes[layout_mode])
			        });
		        }else{
		           
		        	$thumbs.isotope({
			            itemSelector : '.isotope-item',
			            layoutMode : (layout_modes[layout_mode]==undefined ? 'fitRows' : layout_modes[layout_mode])
			        });
		        }
		    });
		};

		var pfscrolltotop = function(){$.smoothScroll();};

		function pointfinder_location_success(pos){

			$.pointfinder_pflatp = pos.coords.latitude;
			$.pointfinder_pflngp = pos.coords.longitude;

			pf_grid_elements();
		}

		function pointfinder_location_error(data){
			console.log('error');console.log(data);
		}

		function pointfinder_selectElement(id, valueToSelect) {    
		    var element = document.getElementById(id);
		    element.value = valueToSelect;
		}

		function pf_grid_elements(gridnum){

			if($.isEmptyObject(pfsortformvars)){
              var pfsortformvars = {};
            };

			pfsortformvars.pg = $('.pflistgridview'+gridrandnoorj+'-container').attr('data-page');
			
			if (gridnum != null) {
				pfsortformvars.pfg_grid = gridnum;
			}else{
				pfsortformvars.pfg_grid = $('.pflistgridviewshow'+gridrandnoorj+' .pfgridlistit.pfselectedval').attr('data-pf-grid');
			}

			pfsortformvars.pfg_orderby = $('.pflistgridviewshow'+gridrandnoorj+'').find('.pfsearch-filter').val();
	        pfsortformvars.pfg_number = $('.pflistgridviewshow'+gridrandnoorj+'').find('.pfsearch-filter-number').val();

	        pfsortformvars.pfsearch_filter_ltype = $('.pflistgridviewshow'+gridrandnoorj+'').find('.pfsearch-filter-ltype').val();
	        pfsortformvars.pfsearch_filter_itype = $('.pflistgridviewshow'+gridrandnoorj+'').find('.pfsearch-filter-itype').val();
	        pfsortformvars.pfsearch_filter_location = $('.pflistgridviewshow'+gridrandnoorj+'').find('.pfsearch-filter-location').val();

	        if($.isEmptyObject(pfsortformvars.pfg_grid)){ pfsortformvars.pfg_grid = ''; }

	        if(!$.isEmptyObject(pfsortformvars)){

	            pfremovebyresults();
	           
	            $.fn.pointfinderajaxpagelist({
	              sgdt: settings.sgdt,
	              grid : pfsortformvars.pfg_grid,
	              pfg_orderby : pfsortformvars.pfg_orderby,
	              pfg_number : pfsortformvars.pfg_number,
	              pfsearch_filter_ltype : pfsortformvars.pfsearch_filter_ltype,
	              pfsearch_filter_itype : pfsortformvars.pfsearch_filter_itype,
	              pfsearch_filter_location : pfsortformvars.pfsearch_filter_location,
	              page : pfsortformvars.pg,
	              pfcontainerdiv : pfsortformvars.pfg_griddiv,
	              pfcontainershow : pfsortformvars.pfg_gridshow,
	              gridrandnoorj: gridrandnoorj
	            });
	      	};
		}


				/* If not carousel or listing slider*/
		if($('.pflistgridviewshow'+gridrandnoorj+'').length <= 0){
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
                'gdt': settings.sgdt,
                'grid': settings.grid,
                'pfg_orderby': settings.pfg_orderby,
                'pfg_order': settings.pfg_order,
                'pfg_number': settings.pfg_number,
                'pfsearch_filter_ltype' : settings.pfsearch_filter_ltype,
                'pfsearch_filter_itype' : settings.pfsearch_filter_itype,
                'pfsearch_filter_location' : settings.pfsearch_filter_location,
                'page': settings.page,
                'pfcontainerdiv': 'pflistgridview'+gridrandnoorj+'',
                'pfcontainershow': 'pflistgridviewshow'+gridrandnoorj+'',
                'security': $('.pflistgridajaxview').attr('data-nonce'),
                'pfex' : 'alist',
                'cl':theme_scriptspf.pfcurlang,
                'pflat':$.pointfinder_pflatp,
                'pflng':$.pointfinder_pflngp
              },
              success:function(data){
                
                pfgridloadingtoggle('hide');
                
                setTimeout(function() {pfmakeitperfect();}, 300);
                setTimeout(function() {pfmakeitperfect();}, 500);
                setTimeout(function() {pfmakeitperfect();}, 700);
                setTimeout(function() {pfmakeitperfect();}, 3000);
                setTimeout(function() {pfmakeitperfect();}, 5000);


                
                $('.pflistgridview'+gridrandnoorj+'-container').append(data);

                $('.pflistgridviewshow'+gridrandnoorj+'').show('fade',{direction: 'up' },300)


                $('.pflistgridview'+gridrandnoorj+'-filters .pfgridlist6').on('click',function(e){
            		e.preventDefault();
                	e.stopPropagation();
                  	pfremovebyresults();
                });

                $('.pflistgridview'+gridrandnoorj+'-container .pfajax_paginate a').on('click',function(e){
                	e.preventDefault();
                	e.stopPropagation();

                	var pg = $('.pflistgridview'+gridrandnoorj+'-container').attr('data-page');

                	if (pg == '') {pg = 1;}

                	if($(this).hasClass('prev')){
						pg--;
					}else if($(this).hasClass('next')){
						pg++;
					}else{
						pg = $(this).text();
					}
				
					$('.pflistgridview'+gridrandnoorj+'-container').attr('data-page',pg);
                    pf_grid_elements();
                });


                $('.pflistgridview'+gridrandnoorj+'-filters-right .pfgridlistit').on('click',function(e){
                	e.preventDefault();
                	e.stopPropagation();
                	pf_grid_elements($(this).attr('data-pf-grid'));
                });



                $(function(){
                	
                	if ($('.openedonlybutton').length > 0) {
						$('.openedonlybutton').tooltip(
			  				{
							  position: { 
							  	my: 'center-9',
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

                	if($.pf_tablet2_check()){
						if ($('.pficonltype.pficonloc').length > 0) {
							$('.pficonltype.pficonloc').tooltip(
				  				{
								  position: { 
								  	my: 'center-9',
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
								  	my: 'center-11',
								  	at: 'top center-35',
								  	collision: "none",
								  	using: function( position, feedback ) {
										$( this ).css( position );
										$( this.firstChild )
										.addClass( "pointfinderarrow_box" )
										.addClass( "wpfquick-tooltip" )
										.addClass( "bottom" );

							        }
								  },
								  show: {effect: "blind", duration: 800},
								  hide: {effect: "blind", duration: 800}
								}
				  			);
						}

						if ($('.pfquicklinks a').length > 0) {
							$('.pfquicklinks a').tooltip(
				  				{
								  position: { 
								  	my: 'center-11',
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
						pointfinder_selectElement('pfsearch-filter',settings.pfg_orderby);
						$('#pfsearch-filter').dropdown({
						  autoResize: 0,
						  keyboard:true,
						  nested:true,
						  selectParents:false
						});
						$('#pfsearch-filter').on( 'dropdown.select', function( e, item, previous, dropdown ) {
						  if (item.value == 'nearby' || item.value == 'distance') {
						    navigator.geolocation.getCurrentPosition(pointfinder_location_success, pointfinder_location_error,{enableHighAccuracy:true, timeout: 5000, maximumAge: 0});
						    pointfinder_selectElement('pfsearch-filter',item.value);
						  }else{
						    pointfinder_selectElement('pfsearch-filter',item.value);
						    pf_grid_elements();
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
						  pf_grid_elements();
						});
					}

                });


              },
              error: function (request, status, error) {
                pfgridloadingtoggle('hide')
                $('.pflistgridview'+gridrandnoorj+'-container').append('<div class= "pflistgridview'+gridrandnoorj+'"><div class="pfsearchresults-loading" style="text-align:center"><strong>An error occured!</strong></div></div>');
              },
              complete: function(){

              },
            });

		}else{
        	$(settings.pfcontainershow).show('fade',{direction: 'up' },300);
      	}


	};	


})(jQuery);