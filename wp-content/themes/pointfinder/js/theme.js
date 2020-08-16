(function($) {
  "use strict";
  	$.pfscrolltotop = function(){$.smoothScroll();};
	$.pfmessagehide = function(){
		/*
		setTimeout(function() {
			$('#pfuaprofileform-notify').hide("slide",{direction : "up"},100);
		}, 5000);
		*/
	};
	
  	$.pf_mobile_check = function(){
		if (window.screen.width > 568) {return true;} else{return false;};
	}

	$.pf_tablet_check = function(){
		if (window.screen.width > 992 ) {return true;} else{return false;};
	}
	$.pf_tablet2_check = function(){
		if (window.screen.width > 1024 ) {return true;} else{return false;};
	}
	$.pf_tablet3_check = function(){
		if (window.screen.width > 1024 && window.screen.orientation.angle == 0) {return true;} else{return false;};
	}

	$(function(){

		$(window).on('load', function(event) {
			var footer_row_height = 0;
			var footer_height = 0;
			var header_height = 0;
			var adminbarheight = 0;
			var wpfcontainermargin = 0;

			if ($('.wpf-container').length > 0) {
		      wpfcontainermargin = $('.wpf-container').css('margin-top');
		      wpfcontainermargin = parseInt(wpfcontainermargin.replace("px",""));
		    }

			if ($('.wpf-footer-row-move').length>0) {
				footer_row_height = $('.wpf-footer-row-move').outerHeight();
			}

			if ($('.wpf-footer').length>0) {
				footer_height = $('.wpf-footer').outerHeight();
			}

			if ($('.wpf-header').length>0) {
				header_height = $('.wpf-header').outerHeight();
			}

			if ($('#wpadminbar').length>0) {
				adminbarheight = $('#wpadminbar').outerHeight();
			}

			var total_out = footer_row_height + footer_height + wpfcontainermargin + adminbarheight;
			
			if ($('.wpf-container').length > 0) {$('.wpf-container').css('min-height','calc(100vh - '+total_out+'px)');}

		});

		if($.pf_tablet2_check()){
			if ($('.pficonltype.pficon').length > 0) {
				$('.pficonltype.pficon').tooltip(
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
					  hide: {effect: "blind"}
					}
	  			);
			}
		}

		if ($('.pfwidgetinner .golden-forms .select').length > 0) {
			$('.pfwidgetinner .golden-forms .select:has(.pfselect2container)').css('border','none');
		}

		if ($(".post-content").length > 0) {
			$(".post-content").fitVids();
		}

		if ($('.wp-block-embed__wrapper').length > 0) {
			$('.wp-block-embed__wrapper').fitVids();
		}

		function getCurrentScroll() {
			return window.pageYOffset || document.documentElement.scrollTop;
		}

		$.reCAPTCHA_execute = function(formname){

			function replaceAll(str, find, replace) {
			    return str.replace(new RegExp(find, 'g'), replace);
			}

			if (typeof grecaptcha != "undefined"){

				grecaptcha.execute(theme_scriptspf.pkeyre, {
					action: ""+replaceAll(formname,"-","")+""
				}).then(function(token) {
				  $("#"+formname+" #grecaptcharesponse").remove();
				  $("<input>").attr({type: "hidden",name: "g-recaptcha-response",id: "grecaptcharesponse",value: token}).prependTo("#"+formname+"");
				});
			}
		};

    	$('.pf-mfp-image').magnificPopup({type:'image'});
    	
		$('body').on('click touchstart', '.menu-item-has-children + a', function(event) {
			
			if (!$.pf_mobile_check()) {return false;}
			
		});

		if ($('#respond.comment-respond').length > 0) {$('#respond.comment-respond').addClass('golden-forms')};

		
		$('.pf-mobile-up-button .pf-up-but-el').on('click',function(e){
			e.preventDefault();
			if ($('.pf-up-but-el i').hasClass('fa-ellipsis-h')) {
				$('.pf-up-but-el i').fadeOut(10, function() { 
		           $('.pf-up-but-el i').removeClass('fa-ellipsis-h');
		        });
		        $('.pf-up-but-el i').fadeIn(200, function() { 
		           $('.pf-up-but-el i').addClass('fa-ellipsis-v');
		        });
			}else{
				$('.pf-up-but-el i').fadeOut(10, function() { 
		           $('.pf-up-but-el i').removeClass('fa-ellipsis-v');
		        });
		        $('.pf-up-but-el i').fadeIn(200, function() { 
		           $('.pf-up-but-el i').addClass('fa-ellipsis-h');
		        });
			}
			
			$('.pf-mobile-up-button .pf-up-but-up').fadeToggle('800');
			$('.pf-mobile-up-button .pf-up-but-menu').fadeToggle('1000');
			if($('.pf-mobile-up-button .pf-up-but-umenu').length > 0){$('.pf-mobile-up-button .pf-up-but-umenu').fadeToggle('1200');}
		});


		$('.pf-mobile-up-button .pf-up-but-up').on('click',function(e){
			e.preventDefault();
			$('.pf-up-but-el').trigger('click');
			$('html, body').animate({scrollTop : 0},800,function(){});
		});

		$('.pf-desktop-up-button .pf-up-but-up').on('click',function(e){
			e.preventDefault();
			$('html, body').animate({scrollTop : 0},800,function(){});
		});

		$('.pf-up-but-menu').on('click',function(e){
			e.preventDefault();
			$('#pf-primary-nav-button').trigger("click");
		});

		$('.pf-up-but-umenu').on('click',function(e){
			e.preventDefault();
			$('#pf-topprimary-nav-button').trigger("click");
		});

/***************************************************************************************************************
*
*
* RESPONSIVE MENU FUNCTIONS & MOBILE MENU FUNCTIONS
*
*
***************************************************************************************************************/

		$('.pfmenucontaineroverflow').on('click',function(){
			var target = $('.pfmobilemenucontainer').attr("data-menuid");
			$('#'+target).trigger('click');
		});

		$('#pf-topprimary-navmobi a').on('click', function(event) {
			var target = $('.pfmobilemenucontainer').attr("data-menuid");
			$('#'+target).trigger('click');
		});

		
		$.each($('.mobilenavbutton'), function(index, val) {
		 	
			var target = $(this).attr('id');

		 	$('#'+target).on('click',function(e){
		 		e.preventDefault();
				$(this).toggleClass('pfopened');

				var menuid = $(this).attr("data-menu");
				

				setTimeout(function(){
					$('.pfmenucontaineroverflow').toggleClass('pfactiveoverflow');
				},0);

				if (menuid != 'pfsearch-draggable') {
					var direction = $('#'+menuid).attr("data-direction");
					if($('#'+menuid).css(direction) == '-290px'){
						$('#'+menuid).css('display','block');
						$('#'+menuid).css(direction,'0');
						$('.pf-menu-container').css(direction,'0');
						$('.pfmobilemenucontainer').attr("data-menuid",target);
					}else{
						$('#'+menuid).css('display','none');
						$('#'+menuid).css(direction,'-290px');
						$('.pf-menu-container').css(direction,'-290px');
						$('.pfmobilemenucontainer').removeAttr("data-menuid");
					}
				}else{
					var direction = $('.psearchdraggable').attr("data-direction");

					if ($('.psearchdraggable').css(direction) == '-290px') {
						$('.psearchdraggable.mobilesearch').css(direction,'0');
						$('.pfmobilemenucontainer').attr("data-menuid",target);
					}else{
						$('.psearchdraggable.mobilesearch').css(direction,'-290px');
						$('.pfmobilemenucontainer').removeAttr("data-menuid");
					}
				}

			});

			
			if ($('body').hasClass('rtl')) {
				$('.pfmobilemenucontainer .pf-menu-container .pf-logo-container.pfmobilemenulogo').css('margin-right','30px');
			}else{
				$('.pfmobilemenucontainer .pf-menu-container .pf-logo-container.pfmobilemenulogo').css('margin-left','30px');
			}
			

		});

		if ($.pf_tablet2_check()) {
			$('#pf-primary-nav').pfresponsivenav();
			$('#pf-topprimary-nav').pfresponsivenav({mleft:0});
			
		}else{
			$('#pf-primary-navmobile').pfresponsivenav();
			$('#pf-topprimary-nav').pfresponsivenav({mleft:0});
		}
		


		//Scroll action
		var shrinkHeader = 180;
		$(window).scroll(function() {

			if ($(this).scrollTop() > 100 && !$('body').hasClass('pfdisableshrink')) {

				$('.pf-desktop-up-button').addClass('pfvisible');
				$('.pf-mobile-up-button').addClass('pfvisible');

				$('.pf-mobile-up-button .pf-up-but-el').fadeIn();
				
			} else {
				$('.pf-desktop-up-button').removeClass('pfvisible');
				$('.pf-mobile-up-button').removeClass('pfvisible');


				$('.pf-mobile-up-button .pf-up-but-up').fadeOut();
				$('.pf-mobile-up-button .pf-up-but-menu').fadeOut();

				
				if($('.pf-mobile-up-button .pf-up-but-umenu').length > 0){$('.pf-mobile-up-button .pf-up-but-umenu').fadeOut();}
				$('.pf-mobile-up-button .pf-up-but-el').fadeOut();
				$('.pf-mobile-up-button .pf-up-but-el i').removeClass('fa-ellipsis-v').addClass('fa-ellipsis-h');
			}

			var scroll = getCurrentScroll();
			if ( scroll >= shrinkHeader && $.pf_tablet_check() && !$('body').hasClass('pfdisableshrink') ) {
				$('.wpf-header').addClass('pfshrink');
			} else {
				$('.wpf-header').removeClass('pfshrink');
			}
		});


		if ($('.pfmobilemenucontainer').attr('data-direction') == 'left') {
			$(".pfmenucontaineroverflow").swipe( {
				swipeLeft:function(event, direction, distance, duration, fingerCount, fingerData, currentDirection) {						
					event.stopPropagation();
					event.preventDefault();
					if ($('#pf-primary-navmobile').css("left") != '-290px') {
						$('#pf-primary-nav-button').trigger("click");
					}
					if ($('#pf-topprimary-navmobi').css("left") != '-290px') {
						$('#pf-topprimary-nav-button').trigger("click");
					}
					if ($('.psearchdraggable').css("left") != '-290px') {
						$('#pf-primary-search-button').trigger("click");
					}
				},
				allowPageScroll: "auto",
				threshold: 50,
				maxTimeThreshold: 5000,
				preventDefaultEvents: true
			});
		}else{
			$(".pfmenucontaineroverflow").swipe( {
				swipeRight:function(event, direction, distance, duration, fingerCount, fingerData, currentDirection) {						
					event.stopPropagation();
					event.preventDefault();
					if ($('#pf-primary-navmobile').css("right") != '-290px') {
						$('#pf-primary-nav-button').trigger("click");
					}
					if ($('#pf-topprimary-navmobi').css("right") != '-290px') {
						$('#pf-topprimary-nav-button').trigger("click");
					}
					if ($('.psearchdraggable').css("right") != '-290px') {
						$('#pf-primary-search-button').trigger("click");
					}
				},
				allowPageScroll: "auto",
				threshold: 50,
				maxTimeThreshold: 5000,
				preventDefaultEvents: true
			});
		}




		$(window).on('load resize orientationchange', function(){


			if ($('#pfsearch-draggable').length > 0 && !$.pf_tablet2_check()) {
				$('#pf-primary-search-button').show('fast');
				if (!$('.wpf-container .psearchdraggable').hasClass('mobilesearch')) {
					$('.wpf-container .psearchdraggable').addClass('mobilesearch');
				}

			}else if ($('#pfsearch-draggable').length > 0 && $.pf_tablet2_check()) {
				$('#pf-primary-search-button').hide('fast');

				if ($('.wpf-container .psearchdraggable').hasClass('mobilesearch')) {
					$('.wpf-container .psearchdraggable').removeClass('mobilesearch');
				}
			};
			if ($.pf_tablet2_check() && $("#pfsearch-draggable").is(":hidden")) {
				if (!$('#pfsearch-draggable').hasClass('pfsearch-draggable-full')) {
					$('#pfsearch-draggable').css('display','block');

					if ($('#pfsearch-draggable').hasClass('pfshowmobile')) {
						$('#pfsearch-draggable').removeClass('pfshowmobile');
					};
				};
			};
		
			if($('#pf-primary-nav').attr('style') == 'display: none;' && $.pf_tablet_check()){
			   $('#pf-primary-nav').removeAttr('style');
			}

			if ($('.psearchdraggable.pfhalfmapdraggable.topmap.mobilesearch').length > 0 && !$.pf_tablet3_check()) {
				if ($('.sidebar-widget').length > 0) {
					var elem = document.querySelector('.psearchdraggable.pfhalfmapdraggable.topmap');
					var elem2 = document.querySelector('.sidebar-widget');
					var clone = elem.cloneNode(true);
					elem.remove();
					elem2.before(elem);
					
				}
				if (!$('.wpf-container .psearchdraggable').hasClass('mobilesearcht')) {
					$('.wpf-container .psearchdraggable').addClass('mobilesearcht');
				}

			}


		});


		setTimeout(function(){
			if ($('body').hasClass('pfdisableshrink')) {$('html').css('background-color','#ffffff')}
		},100);
	});



})(jQuery);