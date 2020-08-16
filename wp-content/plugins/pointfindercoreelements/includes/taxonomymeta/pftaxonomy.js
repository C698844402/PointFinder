(function( $ ) {
    "use strict";
 
    $(function() {
            $('body').on('click', '.pointfinder-tax-header > span', function(event) {

                if ($(this).hasClass('dashicons-arrow-up-alt2')) {
                    $(this).parent('.pointfinder-tax-header').next('.pointfinder-tax-header-body').hide();
                    $(this).removeClass('dashicons-arrow-up-alt2').addClass('dashicons-arrow-down-alt2');
                }else{
                    $(this).parent('.pointfinder-tax-header').next('.pointfinder-tax-header-body').show();
                    $(this).removeClass('dashicons-arrow-down-alt2').addClass('dashicons-arrow-up-alt2');
                }
                
                
            });
        }
    );
})( jQuery );
