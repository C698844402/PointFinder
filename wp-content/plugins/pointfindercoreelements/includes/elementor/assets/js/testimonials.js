( function( $ ) {

  $( window ).on( 'elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction( 'frontend/element_ready/pointfindertestimonials.default', function(index){
      
      var carouselel = $( index ).find('.pointfindertestimonials');

      carouselel.owlCarousel({
          items : 1,
          navigation : false,
          pagination : false,
          autoPlay : true,
          stopOnHover : true,
          slideSpeed:(carouselel.data('speed')*1000),
          paginationNumbers : false,
          mouseDrag:false,
          touchDrag:false,
          autoHeight : false,
          responsive:true,
          transitionStyle: ""+carouselel.data('mode')+"", 
          itemsScaleUp : false,
          navigationText:false,
          theme:"owl-theme",
          singleItem : true,
          itemsCustom : true,
          itemsDesktop : [1199,1],
          itemsDesktopSmall : [980,1],
          itemsTablet: [768,1],
          itemsTabletSmall: false,
          itemsMobile : [479,1],
      });
    });
	});
  
} )( jQuery );
