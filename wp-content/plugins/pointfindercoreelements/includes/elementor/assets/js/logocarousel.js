( function( $ ) {


  $( window ).on( 'elementor/frontend/init', function() {

    
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


    var total_out = footer_row_height + footer_height + wpfcontainermargin;
    
    if ($('.wpf-container').length > 0) {$('.wpf-container').css('min-height','calc(100vh - '+total_out+'px)');}

 

    elementorFrontend.hooks.addAction( 'frontend/element_ready/pointfinderlogocarousel.default', function(index){
      
      var carouselel = $( index ).find('.pointfinderlogocarousel');
     
      carouselel.owlCarousel({
            items : carouselel.data('logoamount'),
            navigation : (carouselel.data('prevnext') == 'yes')?true:false,
            paginationNumbers : (carouselel.data('npagination') == 'yes')?true:false,
            pagination : (carouselel.data('pagination') == 'yes')?true:false,
            autoPlay : (carouselel.data('autoplay') == 'yes')?true:false,
            stopOnHover : true,
            slideSpeed: (carouselel.data('speed')*1000),
            mouseDrag:true,
            touchDrag:true,
            itemSpaceWidth: 10,
            itemBorderWidth : (carouselel.data('autoplay') == 'yes')?0:carouselel.data('logoamount'),
            autoHeight : false,
            responsive:true,
            itemsScaleUp : false,
            navigationText:false,
            theme:"owl-theme",
            singleItem : false,
          });

          setTimeout(function(){
            carouselel.find(".vc-inner img").css("opacity","1").css("width","100%");
          },150);
      });
	});
  
} )( jQuery );
