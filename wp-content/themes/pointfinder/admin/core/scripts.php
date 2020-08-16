<?php
/**********************************************************************************************************************************
*
* Scripts & Styles
*
* Author: Webbu
*
***********************************************************************************************************************************/


/*------------------------------------*\
	Scripts & Styles
\*------------------------------------*/

if (!function_exists('pf_styleandscripts')) {
	function pf_styleandscripts()
	{


		if ( is_singular() ) {wp_enqueue_script( "comment-reply" );}

		/*------------------------------------*\
			Styles
		\*------------------------------------*/
		$version = '1.9.7';

		if (!class_exists('Pointfindercoreelements')) {
			wp_enqueue_style('theme-stylex', get_template_directory_uri() . '/css/theme.css', array(), $version, 'all');
		}
		wp_enqueue_script('touchSwipe', get_template_directory_uri() . '/js/jquery.touchSwipe.min.js', array('jquery'), $version,true);
		wp_enqueue_style( 'font-awesome-free', get_template_directory_uri() . '/css/all.min.css',array(), '5.12.0', 'all',true);
		wp_enqueue_script('svginject', get_template_directory_uri() . '/js/svginject.js', array('jquery'), $version,true);
		wp_enqueue_script('jquery.validate', get_template_directory_uri() . '/js/jquery.validate.min.js', array('jquery'), $version,true);
		wp_enqueue_script('jquery.magnific-popup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array('jquery'), $version,true);
		
		wp_enqueue_script('jquery.fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', array('jquery'), '1.1',true);
		wp_enqueue_script('jquery.smooth-scroll', get_template_directory_uri() . '/js/jquery.smooth-scroll.min.js', array('jquery'), '2.2.0',true);
		wp_enqueue_script('jquery.ui.touch-punch', get_template_directory_uri() . '/js/jquery.ui.touch-punch.min.js', array('jquery'), '0.2.3',true);
		wp_enqueue_script('isotope', get_template_directory_uri() . '/js/isotope.pkgd.min.js', array('jquery'), '3.0.6',true);
		wp_enqueue_script('select2pf', get_template_directory_uri() . '/js/select2.js', array('jquery'), '3.5.4',true);
		wp_enqueue_script('jquery.placeholder', get_template_directory_uri() . '/js/jquery.placeholder.min.js', array('jquery'), '0.2.4',true);
		wp_enqueue_script('jquery.typeahead', get_template_directory_uri() . '/js/jquery.typeahead.min.js', array('jquery'), '2.11.0',true);
		wp_enqueue_script('infinitescroll', get_template_directory_uri() . '/js/infinite-scroll.min.js', array('jquery'), '2.1.0',true);

		wp_enqueue_style( 'jquery.dropdown', get_template_directory_uri() . '/css/dropdown.css',array(), $version, 'all');
		wp_enqueue_script('jquery.dropdown', get_template_directory_uri() . '/js/jquery.dropdown.min.js', array('jquery'), '2.2.0',true);

		if(is_rtl()){

			wp_register_style('pftheme-minified-package-css', get_template_directory_uri() . '/css/framework.min.package.rtl.css', array(), '1.9','all');
			wp_enqueue_style(  'pointfinder-rtl',  get_template_directory_uri()."/rtl.css", array(), '1', 'screen' );

			wp_enqueue_script('owncarousel', get_template_directory_uri() . '/js/js.owncarousel.min.rtl.js', array('jquery'), '1.31',true);
			wp_enqueue_script('responsivemenu', get_template_directory_uri() . '/js/responsive-menu-rtl.js', array('jquery'), $version,true);

		}else{
			wp_register_style('pftheme-minified-package-css', get_template_directory_uri() . '/css/framework.min.package.css', array(), '1.9','all');

			wp_enqueue_script('owncarousel', get_template_directory_uri() . '/js/js.owncarousel.min.js', array('jquery'), '1.31',true);
			
			wp_enqueue_script('responsivemenu', get_template_directory_uri() . '/js/responsive-menu.js', array('jquery'), $version,true);
		}

		wp_enqueue_style('pftheme-minified-package-css');
		wp_enqueue_style( 'theme-style', get_stylesheet_uri(),array('pftheme-minified-package-css'),$version);
		wp_register_script('pftheme-customjs', get_template_directory_uri() . '/js/theme.js', 
			array(
				'jquery',
				'jquery-ui-core',
				'jquery-ui-draggable',
				'jquery-ui-tooltip',
				'jquery-effects-core',
				'jquery-ui-slider',
				'jquery-effects-fade',
				'jquery-effects-slide',
				'jquery-ui-dialog',
				'jquery-ui-autocomplete',
				'jquery.magnific-popup',
				'imagesloaded',
				'jquery.validate',
				'svginject',
				'jquery.fitvids',
				'jquery.smooth-scroll',
				'jquery.ui.touch-punch',
				'isotope',
				'select2pf',
				'jquery.placeholder',
				'jquery.typeahead',
				'owncarousel',
				'infinitescroll',
				'responsivemenu',
				'touchSwipe'
			), $version,true);
		wp_enqueue_script('pftheme-customjs');


		wp_enqueue_style( 'pointfinder-ie', get_template_directory_uri() . '/css/ie.css', array('theme-style'),$version );
		wp_style_add_data( 'pointfinder-ie', 'conditional', 'IE' );

	}
}

add_action('wp_enqueue_scripts', 'pf_styleandscripts');