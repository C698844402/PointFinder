( function( $ ) {
    
	/**
 	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */ 


  $( window ).on( 'elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction( 'frontend/element_ready/pointfinderlogocarousel.default', function(){
      
      console.log('herexxxxx');
    });
	} );

	// Make sure you run this code under Elementor.
 /*
	$( window ).on( 'elementor/frontend/init', function() {

    elementorFrontend.hooks.addAction( 'init', function() {
      console.log('Elementor Init');
    } );

    elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $scope ) {

       console.log('Elementor global ready');
    } );

    elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', function( $scope ) {
      console.log('Elementor widget ready');
    } );

    elementorFrontend.hooks.addAction( 'frontend/element_ready/modulexsplitmap.default', function( $scope ) {
     console.log('Elementor modulexsplitmap ready');
    } );


	} );
  */

} )( jQuery );
