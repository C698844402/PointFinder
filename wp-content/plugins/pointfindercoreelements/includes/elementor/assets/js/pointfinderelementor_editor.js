( function( $ ) {


    /* Split Map Functions */
    elementor.hooks.addAction( 'panel/open_editor/widget/pointfinderlogocarousel', function( panel, model, view ) {
        //$('.elementor-panel-navigation-tab.elementor-tab-control-advanced').hide();
        console.log('herey')
    } );

   

   
    $(function(){
    	$(window).on('load', function(event) {
	    	setTimeout(function(){
	    		if ($('#elementor-panel-category-pro-elements .elementor-panel-category-items .elementor-element i').hasClass('eicon-lock')) {
		    		$('#elementor-panel-category-pro-elements').hide();
		    	}
	    	},500)
    	});
    })

} )( jQuery );
