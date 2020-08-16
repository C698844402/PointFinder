(function( $ ) {
	'use strict';


	$(function() {

		if ($('#pfsearch-filter').length > 0) {
			$('#pfsearch-filter').dropdown();
		}

	  	if ($("#pf-itempage-video").length > 0) {
	  		$("#pf-itempage-video").fitVids();
	  	}

	  	if ($(".pftabcontainer").length > 0 ) {
		  	$.ajax({
		  		url: theme_scriptspf.ajaxurl,
		  		type: 'POST',
		  		dataType: 'json',
		  		data: {l: $(".pftabcontainer").attr('data-lid'),security:theme_scriptspf.pfget_itemcount,action:'pfget_itemcount'},
		  	})
		  	.done(function(data) {});
	  	}
	  	
	  	function syncPosition(el){
			var current = this.currentItem;
			$("#pfitemdetail-slider-sub")
			.find(".owl-item")
			.removeClass("synced")
			.eq(current)
			.addClass("synced")
			if($("#pfitemdetail-slider-sub").data("owlCarousel") !== undefined){
				center(current);
			}
		}

		function center(number){
			var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
			var num = number;
			var found = false;
			for(var i in sync2visible){
				if(num === sync2visible[i]){
					var found = true;
				}
			}

			if(found === false){
				if(num>sync2visible[sync2visible.length-1]){
					sync2.trigger("owl.goTo", num - sync2visible.length+2)
				}else{
					if(num - 1 === -1){
					num = 0;
					}
					sync2.trigger("owl.goTo", num);
				}
			} else if(num === sync2visible[sync2visible.length-1]){
				sync2.trigger("owl.goTo", sync2visible[1])
			} else if(num === sync2visible[0]){
				sync2.trigger("owl.goTo", num-1)
			}
      	}

  		if ($("#pfitemdetail-slider").length > 0 ) {

	      	$("#pfitemdetail-slider").magnificPopup({
	            delegate: "a",
	            type: "image",
	            gallery:{
	            enabled:true,
	            navigateByImgClick: true,
	            preload: [0,2],
	            arrowMarkup: "<button title=\"%title%\" type=\"button\" class=\"mfp-arrow mfp-arrow-%dir%\"></button>",
	            tPrev: ""+$("#pfitemdetail-slider").data("mes1")+"",
	            tNext: ""+$("#pfitemdetail-slider").data("mes2")+"",
	            tCounter: "<span class=\"mfp-counter\">%curr% / %total%</span>"
	            }
	 	 	});

			var sync1 = $("#pfitemdetail-slider");
			var sync2 = $("#pfitemdetail-slider-sub");
			var autoplay_status = $("#pfitemdetail-slider").data("autoplay");
			var autoheight_status = $("#pfitemdetail-slider").data("autoheight");

			sync1.owlCarousel({
				singleItem : true,
				autoPlay:(autoplay_status)?true:false,
				slideSpeed: $("#pfitemdetail-slider").data("timer"),
				stopOnHover : true,
				transitionStyle: ""+$("#pfitemdetail-slider").data("tstyle")+"",
				navigation: true,
				responsive:true,
				pagination:false,
				autoHeight:(autoheight_status)?true:false,
				itemsScaleUp : false,
				navigationText:false,
				theme:"owl-theme",
				afterAction : syncPosition,
				responsiveRefreshRate : 200,
			});

			sync2.owlCarousel({
				pagination:false,
				autoHeight : false,
				responsiveRefreshRate : 100,
				navigation: true,
				responsive:true,
				itemsScaleUp : false,
				navigationText:false,
				theme:"owl-theme",
				itemSpaceWidth: 10,
				singleItem : false,
				items:7,
				itemsDesktop:[1200,5],
				itemsDesktopSmall: [979,4],
				itemsTablet: [768,6],
				itemsTabletSmall: [638,5],
				itemsMobile: [479,3],
				afterInit : function(el){
					el.find(".owl-item").eq(0).addClass("synced");
				}
			});

			

			$("#pfitemdetail-slider-sub").on("click", ".owl-item", function(e){
				e.preventDefault();
				var number = $(this).data("owlItem");
				sync1.trigger("owl.goTo",number);
			});

			
  		}

  	});

})( jQuery );
