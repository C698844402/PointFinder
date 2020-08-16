<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themeforest.net/user/webbu
 * @since      1.0.0
 *
 * @package    Pointfindercoreelements
 * @subpackage Pointfindercoreelements/public
 */

class Pointfindercoreelements_Public {
	use PointFinderOptionFunctions,
	PointFinderCommonFunctions,
	PointFinderCUFunctions,
	PointFinderWPMLFunctions,
	PointFinderMailSystem,
	PointFinderMegaMenu,
	PointFinderSocialLogins,
	PointFinderMailFunctions,
	PointFinderAuthorPGFunctions,
	PointFinderInvoiceFunction;

	private $plugin_name;
	private $version;
	private $post_type_name;



	public function __construct($plugin_name, $version, $post_type_name) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->post_type_name = $post_type_name;
	}

	public function pointfinder_get_admin_options_again_when_language_switched(){

		if (class_exists('SitePress')) {
			if ($this->PF_current_language() != $this->PF_default_language()) {

				global $pointfindertheme_option;
				$pointfindertheme_option = get_option('pointfindertheme_options');

				global $pfascontrol_options;
				$pfascontrol_options = get_option('pfascontrol_options');

				global $pfadvancedcontrol_options;
				$pfadvancedcontrol_options = get_option('pfadvancedcontrol_options');

				global $pfpgcontrol_options;
				$pfpgcontrol_options = get_option('pfpgcontrol_options');

				/*
				global $pfcustomfields_options;
				$pfcustomfields_options = get_option('pfcustomfields_options');
				*/

				global $pfsearchfields_options;
				$pfsearchfields_options = get_option('pfsearchfields_options');

				global $pointfindermail_option;
				$pointfindermail_option = get_option('pointfindermail_options');
			}
		}

	}
	
	public function enqueue_styles_scripts() {
		global $wp;
		global $post;
		global $post_type;

		$version = '2.0';

		wp_register_style( $this->plugin_name, PFCOREELEMENTSURLPUBLIC . 'css/pointfindercoreelements-public.css', array(), $version, 'all' );

		wp_register_script( $this->plugin_name, PFCOREELEMENTSURLPUBLIC . 'js/pointfindercoreelements-public.js', array( 'jquery' ), $version, false );

		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name );


		wp_register_script( 'theme-leafletjs', PFCOREELEMENTSURLPUBLIC . 'js/leaflet.js', array( 'jquery' ), '1.5.1', false );
		wp_register_style( 'theme-leafletcss', PFCOREELEMENTSURLPUBLIC . 'css/leaflet.css', array(), '1.5.1', 'all');

		wp_enqueue_style( 'font-awesome-free', PFCOREELEMENTSURLPUBLIC . 'css/all.min.css',array(), '5.12.0', 'all');

		/* All Styles Packaged */
		wp_register_style('flaticons', PFCOREELEMENTSURLPUBLIC . 'css/flaticon.css', array(), '1.0', 'all');
		
		
		$setup3_pointposttype_pt8 = $this->PFSAIssetControl("setup3_pointposttype_pt8","","agents");
   	 	$setup4_membersettings_dashboard = $this->PFSAIssetControl('setup4_membersettings_dashboard','','');
        $setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
		$pfmenu_perout = $this->PFPermalinkCheck();


		if (isset($wp->request)) {
			$current_url = home_url(add_query_arg(array(),$wp->request));
		}else{
			$current_url = get_permalink();
		}
		
		/* ReCaptcha Start - Controlled */
			$public_key = '';
			if (class_exists('Pointfinder_reCaptcha_System')) {
				$public_key = $this->PFSAIssetControl('repubk');
				if (!empty($public_key)) {
					wp_register_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js?render='.$public_key, array(
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
						'responsivemenu'
					), '3',true);
					wp_enqueue_script('google-recaptcha');
				}
			}
		/* ReCaptcha End - Controlled */
		
		$setup13_mapcontrols_position = $this->PFSAIssetControl('setup13_mapcontrols_position','','1');
		$setup6_clustersettings_status = $this->PFSAIssetControl('setup6_clustersettings_status','',1);
		$setup45_status = $this->PFSAIssetControl('setup45_status','',1);
		$gesturehandling = $this->PFSAIssetControl('gesturehandling','',1);
		if ($gesturehandling == 1) {
			$gesturehandling_status = 'true';
		}else{
			$gesturehandling_status = 'false';
		}
		
		if ($setup13_mapcontrols_position == 1) {
			$setup13_mapcontrols_position = 'topleft';
		}else{
			$setup13_mapcontrols_position = 'topright';
		}
		$as_mobile_dropdowns = $this->PFSAIssetControl('as_mobile_dropdowns','','0');
		wp_register_script('theme-scriptspf', PFCOREELEMENTSURLPUBLIC . 'js/theme-scripts.js', 
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
				'responsivemenu'
			), $version,true);
        wp_enqueue_script('theme-scriptspf');
        wp_localize_script( 'theme-scriptspf', 'theme_scriptspf', array(
			'ajaxurl' => PFCOREELEMENTSURLINC . 'pfajaxhandler.php',
			'homeurl' => esc_url(home_url("/")),
			'fullscreen' => esc_html__('Fullscreen', 'pointfindercoreelements'),
			'fullscreenoff' => esc_html__('Exit Fullscreen', 'pointfindercoreelements'),
			'locateme' => esc_html__('Locate me!', 'pointfindercoreelements'),
			'locatefound' => esc_html__('You are here!', 'pointfindercoreelements'),
			'pfget_usersystem' => wp_create_nonce('pfget_usersystem'),
			'pfget_modalsystem' => wp_create_nonce('pfget_modalsystem'),
			'pfget_usersystemhandler' => wp_create_nonce('pfget_usersystemhandler'),
			'pfget_modalsystemhandler' => wp_create_nonce('pfget_modalsystemhandler'),
			'pfget_favorites' => wp_create_nonce('pfget_favorites'),
			'pfget_searchitems' => wp_create_nonce('pfget_searchitems'),
			'pfget_reportitem' => wp_create_nonce('pfget_reportitem'),
			'pfget_claimitem' => wp_create_nonce('pfget_claimitem'),
			'pfget_flagreview' => wp_create_nonce('pfget_flagreview'),
			'pfget_grabtweets' => wp_create_nonce('pfget_grabtweets'),
			'pfget_autocomplete' => wp_create_nonce('pfget_autocomplete'),
			'pfget_listitems' => wp_create_nonce('pfget_listitems'),
			'pfget_markers' => wp_create_nonce('pfget_markers'),
			'pfget_taxpoint' => wp_create_nonce('pfget_taxpoint'),
			'pfget_geocoding' => wp_create_nonce('pfget_geocoding'),
			'pfget_infowindow' => wp_create_nonce('pfget_infowindow'),
			'pfget_phoneemail' => wp_create_nonce('pfget_phoneemail'),
			'pfget_itemcount' => wp_create_nonce('pfget_itemcount'),
			'recaptchapkey' => $this->PFSAIssetControl('repubk'),
			'pfnameerr' => esc_html__('Please write name','pointfindercoreelements'),
			'pfemailerr' => esc_html__('Please write email','pointfindercoreelements'),
			'pfemailerr2' => esc_html__('Please write correct email','pointfindercoreelements'),
			'pfmeserr' => esc_html__('Please write message','pointfindercoreelements'),
			'userlog' => (is_user_logged_in())? 1:0,
			'dashurl' => ''.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=newitem',
			'profileurl' => ''.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=profile',
			'pfselectboxtex' => esc_html__('Please Select','pointfindercoreelements'),
			'pfcurlang' => $this->PF_current_language(),
			'pfcurrentpage' => $current_url,
			'email_err_social' => esc_html__('Please add your email.','pointfindercoreelements'),
			'email_err_social2' => esc_html__('Please add valid email.','pointfindercoreelements'),
			'tnc_err2' => esc_html__('Please check terms and conditions checkbox.','pointfindercoreelements'),
			'pkeyre' => $public_key,
			'returnhome' => esc_html__("Return Home","pointfindercoreelements"),
			'lockunlock' => esc_html__("Lock Dragging","pointfindercoreelements"),
			'lockunlock2' => esc_html__("Unlock Dragging","pointfindercoreelements"),
			'getdirections' => esc_html__("Get Directions","pointfindercoreelements"),
			'bposition' => $setup13_mapcontrols_position,
			'clusterstatus' => $setup6_clustersettings_status,
			'ttstatus' => $setup45_status,
			'gesturehandling' => $gesturehandling_status,
			'mobiledropdowns' => $as_mobile_dropdowns
		));



   	 	wp_register_script('theme-scriptsheader', PFCOREELEMENTSURLPUBLIC . 'js/theme-scripts-header.js', array('jquery'), '1.0.0');
        wp_enqueue_script('theme-scriptsheader');



		/* Map Functions */
		wp_register_script('theme-map-functionspf', PFCOREELEMENTSURLPUBLIC . 'js/theme-map-functions.js', array('pftheme-customjs','theme-scriptspf','jquery.dropdown'), '2.0',true);
		wp_enqueue_script( 'theme-leafletjs' );
		wp_enqueue_style( 'theme-leafletcss');
		wp_enqueue_script('theme-map-functionspf');
		wp_localize_script( 'theme-map-functionspf', 'theme_map_functionspf', array(
			'ajaxurl' => PFCOREELEMENTSURLINC . 'pfajaxhandler.php',
			'template_directory' => PFCOREELEMENTSURL,
			'resizeword' => esc_html__('Resize','pointfindercoreelements'),
			'pfcurlang' => $this->PF_current_language(),
			'defmapdist' => $this->PFSAIssetControl('setup7_geolocation_distance','',10),
		));



		/* New Map System Start - Controlled */

			if (isset($post)) {

				$we_special_key = '';
				if (has_shortcode( $post->post_content, 'pf_itemgrid') ) {
					wp_enqueue_style( 'flaticons');
				}

				/* Contact Map Shorcode */
			    if (has_shortcode( $post->post_content, 'pf_contact_map')) {

					$stp5_mapty = $this->PFSAIssetControl('stp5_mapty','',1);
		    		$wemap_geoctype = $this->PFSAIssetControl('wemap_geoctype','','');
		    		

		    		if ($stp5_mapty == 1 || $wemap_geoctype == 'google') {
						$maplanguage = $this->PFSAIssetControl('setup5_mapsettings_maplanguage','','en');
						$we_special_key = $this->PFSAIssetControl('setup5_map_key','','');
						wp_enqueue_script('theme-google-api', "https://maps.googleapis.com/maps/api/js?key=$we_special_key&libraries=places&language='.$maplanguage",array('jquery','theme-leafletjs'));
					}

					if ($stp5_mapty == 4) {
						$wemap_langy = $this->PFSAIssetControl('wemap_langy','','');
						$we_special_key = $this->PFSAIssetControl('wemap_yandexmap_api_key','','');
						wp_enqueue_script('theme-yandex-map', "https://api-maps.yandex.ru/2.1/?lang=".$wemap_langy."&apikey=".$we_special_key,array('jquery','theme-leafletjs'));
					}

					wp_enqueue_script('theme-contactmapjs', PFCOREELEMENTSURLINC . 'customshortcodes/assets/contactmap.js', array('jquery','theme-leafletjs'), '1.0',true);
			    }

			    if (has_shortcode( $post->post_content, 'pf_itemgrid' ) || has_shortcode( $post->post_content, 'pf_itemgrid2')  || (is_author() || $setup3_pointposttype_pt8 == $post_type )) {
			    	wp_enqueue_script('theme-ajaxlist', PFCOREELEMENTSURLINC . 'customshortcodes/assets/ajaxlist.js', array('jquery','jquery.dropdown'), $version,true);
					wp_localize_script( 'theme-ajaxlist', 'themeajaxlist', array());
			    }

			    /* PF Map Shorcode */
			    if (has_shortcode( $post->post_content, 'pf_directory_map') || has_shortcode( $post->post_content, 'pf_searchw')) {
			    	wp_enqueue_style( 'flaticons');
			    	

					$stp5_mapty = $this->PFSAIssetControl('stp5_mapty','',1);
		    		$wemap_geoctype = $this->PFSAIssetControl('wemap_geoctype','','');
		    		

		    		if ($stp5_mapty == 1 || $wemap_geoctype == 'google') {
						$maplanguage = $this->PFSAIssetControl('setup5_mapsettings_maplanguage','','en');
						$we_special_key = $this->PFSAIssetControl('setup5_map_key','','');
						wp_enqueue_script('theme-google-api', "https://maps.googleapis.com/maps/api/js?key=$we_special_key&libraries=places&language='.$maplanguage",array('jquery','theme-leafletjs'));
					}

					if ($stp5_mapty == 4) {
						$wemap_langy = $this->PFSAIssetControl('wemap_langy','','');
						$we_special_key = $this->PFSAIssetControl('wemap_yandexmap_api_key','','');
						wp_enqueue_script('theme-yandex-map', "https://api-maps.yandex.ru/2.1/?lang=".$wemap_langy."&apikey=".$we_special_key,array('jquery','theme-leafletjs'));
					}

					$setup15_mapnotifications_dontshow_i = $this->PFSAIssetControl('setup15_mapnotifications_dontshow_i','','1');
					$setup15_mapnotifications_autoplay_e = $this->PFSAIssetControl('setup15_mapnotifications_autoplay_e','','1');
					if($setup15_mapnotifications_autoplay_e == 1){
					$setup15_mapnotifications_autoclosetime_e = $this->PFSAIssetControl('setup15_mapnotifications_autoclosetime_e','','5000');
					}else{$setup15_mapnotifications_autoclosetime_e = '120000';}
					$setup15_mapnotifications_autoplay_i = $this->PFSAIssetControl('setup15_mapnotifications_autoplay_i','','0');
					if($setup15_mapnotifications_autoplay_i == 1){
					$setup15_mapnotifications_autoclosetime_i = $this->PFSAIssetControl('setup15_mapnotifications_autoclosetime_i','','5000');
					}else{$setup15_mapnotifications_autoclosetime_i = '120000';}

					wp_enqueue_script('theme-pfdirectorymap', PFCOREELEMENTSURLINC . 'customshortcodes/assets/directorymap.js', array('jquery','theme-leafletjs','theme-map-functionspf'), '1.0',true);
					wp_localize_script( 'theme-pfdirectorymap', 'theme_pfdirectorymap', array(
						'notfoundtext' => esc_html__('We could not find any results.', 'pointfindercoreelements'),
						'foundtext' => esc_html__('LISTINGS FOUND! CLICK TO SHOW LIST', 'pointfindercoreelements'),
						'foundtexthalfmap' => esc_html__('LISTINGS FOUND!', 'pointfindercoreelements'),
						'autoclosetime' => $setup15_mapnotifications_autoclosetime_e,
						'autoclosetimei' => $setup15_mapnotifications_autoclosetime_i
					) );
			    }
			}
			
			if (is_tax() || is_search() || is_tag()) {

					wp_enqueue_style( 'flaticons');

					$stp5_mapty = $this->PFSAIssetControl('stp5_mapty','',1);
		    		$wemap_geoctype = $this->PFSAIssetControl('wemap_geoctype','','');

		    		if ($stp5_mapty == 1 || $wemap_geoctype == 'google') {
						$maplanguage = $this->PFSAIssetControl('setup5_mapsettings_maplanguage','','en');
						$we_special_key = $this->PFSAIssetControl('setup5_map_key','','');
						wp_enqueue_script('theme-google-api', "https://maps.googleapis.com/maps/api/js?key=$we_special_key&libraries=places&language='.$maplanguage",array('jquery','theme-leafletjs'));
					}

					if ($stp5_mapty == 4) {
						$wemap_langy = $this->PFSAIssetControl('wemap_langy','','');
						$we_special_key = $this->PFSAIssetControl('wemap_yandexmap_api_key','','');
						wp_enqueue_script('theme-yandex-map', "https://api-maps.yandex.ru/2.1/?lang=".$wemap_langy."&apikey=".$we_special_key,array('jquery','theme-leafletjs'));
					}


					wp_enqueue_script('theme-categorymapjs', PFCOREELEMENTSURLPUBLIC . 'js/categorymap.js', array('jquery','theme-leafletjs','theme-map-functionspf'), $version,true);
					wp_localize_script( 'theme-categorymapjs', 'themecatmap', array());
			
			}

			
			
			if (is_author() || $post_type == $setup3_pointposttype_pt8) {
				$wemap_geoctype = $this->PFSAIssetControl('wemap_geoctype','','');

	            wp_enqueue_script( 'theme-leafletjs' );
	            wp_enqueue_style( 'theme-leafletcss');

	            $we_special_key = $wemap_here_appid = $wemap_here_appcode = '';
	            $stp5_mapty = $this->PFSAIssetControl('stp5_mapty', '', 1);

	            switch ($stp5_mapty) {
	                case 3:
	                    $we_special_key = $this->PFSAIssetControl('stp5_mapboxpt','','');
	                    break;

	                case 5:
	                    $wemap_here_appid = $this->PFSAIssetControl('wemap_here_appid','','');
	                    $wemap_here_appcode = $this->PFSAIssetControl('wemap_here_appcode','','');
	                    break;

	                case 6:
	                    $we_special_key = $this->PFSAIssetControl('wemap_bingmap_api_key','','');
	                    break;
	            }

	            wp_enqueue_script('theme-ajaxlist', PFCOREELEMENTSURLINC . 'customshortcodes/assets/ajaxlist.js', array('jquery','jquery.dropdown'), $version,true);
				wp_localize_script( 'theme-ajaxlist', 'themeajaxlist', array());

	            if ($stp5_mapty == 1 || $wemap_geoctype == 'google' ) {
	                $maplanguage = $this->PFSAIssetControl('setup5_mapsettings_maplanguage','','en');
	                $we_special_key_google = $this->PFSAIssetControl('setup5_map_key','','');
	                wp_enqueue_script('theme-google-api', "https://maps.googleapis.com/maps/api/js?key=$we_special_key_google&libraries=places&language='.$maplanguage");
	            }

	            if ($stp5_mapty == 4) {
	                $we_special_key_yandex = $this->PFSAIssetControl('wemap_yandexmap_api_key','','');
	                $wemap_langy = $this->PFSAIssetControl('wemap_langy','','');
	                wp_enqueue_script('theme-yandex-map', "https://api-maps.yandex.ru/2.1/?lang=".$wemap_langy."&apikey=".$we_special_key_yandex);
	            }

	            $setup5_mapsettings_zoom = $this->PFSAIssetControl('setup42_searchpagemap_zoom','','12');
	            
	            wp_register_script($this->plugin_name.'singleauththememap', PFCOREELEMENTSURLPUBLIC . 'js/pointfindercoreelements-single-thememap.js', array( 'jquery','theme-map-functionspf' ), $this->version, true);
	            wp_enqueue_script($this->plugin_name.'singleauththememap');
	            wp_localize_script($this->plugin_name.'singleauththememap', 'pfthemesm', array(
	                'stp5_mapty' => $stp5_mapty,
	                'key' => $we_special_key,
	                'wemap_here_appid' => $wemap_here_appid,
	                'wemap_here_appcode' => $wemap_here_appcode,
	                'pfid' => '',
	                'zoom' => $setup5_mapsettings_zoom,
	                'mzoom' => 18
	            ));

	            wp_register_script($this->plugin_name.'singleauthscripts', PFCOREELEMENTSURLPUBLIC . 'js/pointfindercoreelements-single-public.js', array( 'jquery','theme-map-functionspf' ), $this->version, true);
	            wp_enqueue_script($this->plugin_name.'singleauthscripts');
			}

		/* New Map System End*/


		if (is_page($setup4_membersettings_dashboard) && !empty($setup4_membersettings_dashboard)) {

			$stp5_mapty = $this->PFSAIssetControl('stp5_mapty','',1);
			$stp5_osmsrv = $this->PFSAIssetControl('stp5_osmsrv','',"https://tile.openstreetmap.org");
			$stp5_mapboxpt = $this->PFSAIssetControl('stp5_mapboxpt','','');
			

			$as_mobile_dropdowns = $this->PFSAIssetControl('as_mobile_dropdowns','','0');
			wp_register_script('theme-scriptspfm', PFCOREELEMENTSURLPUBLIC . 'js/theme-scripts-dash.js',
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
					'theme-map-functionspf',
					'svginject'
				), '2.0',true);
	        wp_enqueue_script('theme-scriptspfm');
	        wp_localize_script( 'theme-scriptspfm', 'theme_scriptspfm', array(
				'delmsg' => esc_html__('Are you sure that you want to delete this? (This action cannot rollback.)','pointfindercoreelements'),
				'pfget_imagesystem' => wp_create_nonce('pfget_imagesystem'),
				'pfget_onoffsystem' => wp_create_nonce('pfget_onoffsystem'),
				'pfget_filesystem' => wp_create_nonce('pfget_filesystem'),
				'pfget_itemsystem' => wp_create_nonce('pfget_itemsystem'),
				'pfget_fieldsystem' => wp_create_nonce('pfget_fieldsystem'),
				'pfget_featuresystem' => wp_create_nonce('pfget_featuresystem'),
				'pfget_customtabsystem' => wp_create_nonce('pfget_customtabsystem'),
				'pfget_posttag' => wp_create_nonce('pfget_posttag'),
				'pfget_lprice' => wp_create_nonce('pfget_lprice'),
				'pfcurlang' => $this->PF_current_language(),
				'mobiledropdowns' => $as_mobile_dropdowns,
				'pfget_paymentsystem' => wp_create_nonce('pfget_paymentsystem'),
				'pfget_membershipsystem' => wp_create_nonce('pfget_membershipsystem'),
				'paypalredirect' => esc_html__('Redirecting to Paypal','pointfindercoreelements'),
				'generalredirect' => esc_html__('Redirecting','pointfindercoreelements'),
				'paypalredirect2' => esc_html__('Process Starting','pointfindercoreelements'),
				'paypalredirect3' => esc_html__('Finishing Process','pointfindercoreelements'),
				'paypalredirect4' => esc_html__('Done. Redirecting...','pointfindercoreelements'),
				'buttonwait' => esc_html__('Please wait...','pointfindercoreelements'),
				'buttonwaitex' => esc_html__('Submit Again','pointfindercoreelements'),
				'buttonwaitex2' => esc_html__('Submit Listing','pointfindercoreelements'),
				'dashurl' => ''.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=newitem',
				'dashurl2' => ''.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems',
				'dashtext1' => esc_html__("This is your cover photo and can not remove. Please change your cover photo first.","pointfindercoreelements" ),
				'dashtext2' => esc_html__("Are you sure want to delete this item? (This action can not be rollback.","pointfindercoreelements" ),
				'stp5_mapty' => $stp5_mapty,
				'stp5_osmsrv' => $stp5_osmsrv,
				'stp5_mapboxpt' => $stp5_mapboxpt
			));
		}
		/*
		* Start: Stripe Checkout JS - Controlled
		*/
			if(isset($_GET['ua'])){
				$ua_action = sanitize_text_field($_GET['ua']);

				if (in_array($ua_action, array('newitem','edititem','myitems','purchaseplan','renewplan','upgradeplan'))) {

					wp_register_script('moxieformforie', PFCOREELEMENTSURLPUBLIC . 'js/moxie.min.js', array('jquery'), '1.4.1',true);
					wp_enqueue_script('moxieformforie');
					
					$stp5_mapty = $this->PFSAIssetControl('stp5_mapty','',1);
		    		$wemap_geoctype = $this->PFSAIssetControl('wemap_geoctype','','');
		    		$st4_sp_medst = $this->PFSAIssetControl('st4_sp_medst','','0');

		    		if ($stp5_mapty == 1 || $wemap_geoctype == 'google' || $st4_sp_medst == 1) {
						$maplanguage = $this->PFSAIssetControl('setup5_mapsettings_maplanguage','','en');
						$we_special_key = $this->PFSAIssetControl('setup5_map_key','','');
						wp_enqueue_script('theme-google-api', "https://maps.googleapis.com/maps/api/js?key=$we_special_key&libraries=places&language='.$maplanguage",array('jquery','theme-leafletjs'));
					}

					if ($stp5_mapty == 4) {
						$wemap_langy = $this->PFSAIssetControl('wemap_langy','','');
						$we_special_key = $this->PFSAIssetControl('wemap_yandexmap_api_key','','');
						wp_enqueue_script('theme-yandex-map', "https://api-maps.yandex.ru/2.1/?lang=".$wemap_langy."&apikey=".$we_special_key,array('jquery','theme-leafletjs'));
					}

					$setup20_stripesettings_status = $this->PFSAIssetControl('setup20_stripesettings_status','','0');
					if ($setup20_stripesettings_status == 1) {
						wp_register_script('theme-stripeaddon', 'https://checkout.stripe.com/checkout.js', array('jquery'), '1.0.0',true);
	        			wp_enqueue_script('theme-stripeaddon');

	        			wp_register_script('theme-stripeaddon2', 'https://js.stripe.com/v2/', array('jquery'), '2.0.0',true);
	        			wp_enqueue_script('theme-stripeaddon2');
					}
					wp_enqueue_style('theme-dropzone', PFCOREELEMENTSURLPUBLIC . 'css/dropzone.min.css', array(), '1.0', 'all');
					wp_enqueue_script('theme-dropzone', PFCOREELEMENTSURLPUBLIC . 'js/dropzone.min.js', array('jquery','jquery-ui-core','jquery-ui-datepicker','jquery-ui-slider'), '4.0',true);
					
					wp_enqueue_style('jquery-ui-smoothnesspf2', PFCOREELEMENTSURLPUBLIC . "css/jquery-ui.structure.min.css", false, null);
					wp_enqueue_style('jquery-ui-smoothnesspf', PFCOREELEMENTSURLPUBLIC . "css/jquery-ui.theme.min.css", false, null);
					wp_enqueue_script('theme-timepicker', PFCOREELEMENTSURLPUBLIC . 'js/jquery-ui-timepicker-addon.js', array('jquery','jquery-ui-datepicker','jquery-ui-slider'), '4.0',true);
				}
			}
		/*
		* End: Stripe Checkout JS
		*/


		/* Twitter Widget Start - Controlled */
			if ( is_active_widget( false, '', 'pf_twitter_w', true ) != false ) {
				wp_register_script('pointfinder-twitterspf', PFCOREELEMENTSURLPUBLIC . 'js/twitterwebbu.min.js', array('jquery'), '1.0.0',true);
		        wp_enqueue_script('pointfinder-twitterspf');
		        wp_localize_script( 'pointfinder-twitterspf', 'pointfinder_twitterspf', array(
					'ajaxurl' => PFCOREELEMENTSURLINC . 'pfajaxhandler.php',
					'pfget_grabtweets' => wp_create_nonce('pfget_grabtweets'),
					'grabtweettext' => esc_html__('Please control secret keys!','pointfindercoreelements')
				));
			}
		/* Twitter Widget End - Controlled */


		/*
		* Start: Quick Setup Style Fix
		*/

			
			$uploads = wp_upload_dir();
			$pfcssstyle = get_option( 'pointfinder_cssstyle');
			$pfcssstyle = ($pfcssstyle)? $pfcssstyle : 'realestate';
			
			if ( file_exists( $uploads['basedir'] . '/pfstyles/pf-style-main.css' ) ) {
				wp_register_style('pf-main-compiler', $uploads['baseurl'] . '/pfstyles/pf-style-main.css', array('theme-style'), time(), 'all');
				wp_enqueue_style('pf-main-compiler');
			}else{
				wp_register_style('pf-opensn', 'https://fonts.googleapis.com/css?family=Open+Sans:400,600,700', array('theme-style'), '', 'all');
				wp_enqueue_style('pf-opensn');

				if($pfcssstyle == 'realestate'){
					wp_register_style('pf-main-compiler-local', PFCOREELEMENTSURLADMIN . 'quick-setup/pf-style-main.css', array('theme-style'), '', 'all');
					wp_enqueue_style('pf-main-compiler-local');				
				}elseif ($pfcssstyle == 'multidirectory') {
					wp_register_style('pf-main-compiler-local', PFCOREELEMENTSURLADMIN . 'quick-setup/multidirectory/pf-style-main.css', array('theme-style'), '', 'all');
					wp_enqueue_style('pf-main-compiler-local');
				}elseif ($pfcssstyle == 'cardealer') {
					wp_register_style('pf-main-compiler-local', PFCOREELEMENTSURLADMIN . 'quick-setup/cardealer/pf-style-main.css', array('theme-style'), '', 'all');
					wp_enqueue_style('pf-main-compiler-local');
				}
			}


			if ($this->PFASSIssetControl('st8_npsys','',0) == 1) {
				if ( file_exists( $uploads['basedir'] . '/pfstyles/pf-style-ncpt.css' ) ) {
					wp_register_style('pf-ncpt-compiler', $uploads['baseurl'] . '/pfstyles/pf-style-ncpt.css', array('theme-style'), time(), 'all');
					wp_enqueue_style('pf-ncpt-compiler');
				}
			}else{
				if ( file_exists( $uploads['basedir'] . '/pfstyles/pf-style-custompoints.css' ) ) {
					wp_register_style('pf-customp-compiler', $uploads['baseurl'] . '/pfstyles/pf-style-custompoints.css', array('theme-style'), time(), 'all');
					wp_enqueue_style('pf-customp-compiler');
				}else{
					if($pfcssstyle == 'realestate'){
						wp_register_style('pf-customp-compiler-local', PFCOREELEMENTSURLADMIN . 'quick-setup/pf-style-custompoints.css', array('theme-style'), '', 'all');
						wp_enqueue_style('pf-customp-compiler-local');
					}elseif ($pfcssstyle == 'multidirectory') {
						wp_register_style('pf-customp-compiler-local', PFCOREELEMENTSURLADMIN . 'quick-setup/multidirectory/pf-style-custompoints.css', array('theme-style'), '', 'all');
						wp_enqueue_style('pf-customp-compiler-local');
					}elseif ($pfcssstyle == 'cardealer') {
						wp_register_style('pf-customp-compiler-local', PFCOREELEMENTSURLADMIN . 'quick-setup/cardealer/pf-style-custompoints.css', array('theme-style'), '', 'all');
						wp_enqueue_style('pf-customp-compiler-local');
					}
				}
			}

			if ( file_exists( $uploads['basedir'] . '/pfstyles/pf-style-pbstyles.css' ) ) {
				wp_register_style('pf-pbstyles-compiler', $uploads['baseurl'] . '/pfstyles/pf-style-pbstyles.css', array('theme-style'), time(), 'all');
				wp_enqueue_style('pf-pbstyles-compiler');
			}else{
				wp_register_style('pf-pbstyles-compiler-local', PFCOREELEMENTSURLADMIN . 'quick-setup/pf-style-pbstyles.css', array('theme-style'), '', 'all');
				wp_enqueue_style('pf-pbstyles-compiler-local');
			}


			if ( file_exists( $uploads['basedir'] . '/pfstyles/pf-style-psizestyles.css' ) ) {
				wp_register_style('pf-psizestyles-compiler', $uploads['baseurl'] . '/pfstyles/pf-style-psizestyles.css', array('theme-style'), time(), 'all');
				wp_enqueue_style('pf-psizestyles-compiler');
			}else{
				wp_register_style('pf-psizestyles-compiler-local', PFCOREELEMENTSURLADMIN . 'quick-setup/pf-style-psizestyles.css', array('theme-style'), '', 'all');
				wp_enqueue_style('pf-psizestyles-compiler-local');
			}


			if ( file_exists( $uploads['basedir'] . '/pfstyles/pf-style-custom.css' ) ) {
				wp_register_style('pf-custom-compiler', $uploads['baseurl'] . '/pfstyles/pf-style-custom.css', array('theme-style'), time(), 'all');
				wp_enqueue_style('pf-custom-compiler');
			}

			if ( file_exists( $uploads['basedir'] . '/pfstyles/pf-style-search.css' ) ) {
				wp_register_style('pf-search-compiler', $uploads['baseurl'] . '/pfstyles/pf-style-search.css', array('theme-style'), time(), 'all');
				wp_enqueue_style('pf-search-compiler');
			}else{
				if ($pfcssstyle == 'cardealer') {
					wp_register_style('pf-customp-search-local', PFCOREELEMENTSURLADMIN . 'quick-setup/cardealer/pf-style-search.css', array('theme-style'), '', 'all');
					wp_enqueue_style('pf-customp-search-local');
				}
			}


			if ( file_exists( $uploads['basedir'] . '/pfstyles/pf-style-review.css' ) ) {
				wp_register_style('pf-review-compiler', $uploads['baseurl'] . '/pfstyles/pf-style-review.css', array('theme-style'), time(), 'all');
				wp_enqueue_style('pf-review-compiler');
			}else{
				if ($pfcssstyle == 'multidirectory') {
					wp_register_style('pf-main-review-local', PFCOREELEMENTSURLADMIN . 'quick-setup/multidirectory/pf-style-review.css', array('theme-style'), '', 'all');
					wp_enqueue_style('pf-main-review-local');
				}
			}

		/*
		* End: Quick Setup Style Fix
		*/ 



	}

	public function pointfinder_review_fix_pagination(){
	    if (is_singular($this->post_type_name)) {
	        remove_filter('template_redirect', 'redirect_canonical');
	        remove_action( 'template_redirect', 'redirect_canonical' );
	    }
	}

	public function pointfinder_tag_fix_pagination(){
	    if (is_tag()) {
	        remove_filter('template_redirect', 'redirect_canonical');
	        remove_action( 'template_redirect', 'redirect_canonical' );
	    }
	}

	public function pointfinder_removeDemoModeLink() {
	    if ( class_exists('ReduxFrameworkPlugin') ) {
	        remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2 );
	    }
	    if ( class_exists('ReduxFrameworkPlugin') ) {
	        remove_action('admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );
	    }
	}

	public function pointfinderh_style_remove($tag){
	    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
	}

	public function pf_enable_threaded_comments()
	{
	    if (!is_admin()) {
	        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
	            wp_enqueue_script('comment-reply');
	        }
	    }
	}

	public function pointfinder_remove_recent_comments_style()
	{
	    global $wp_widget_factory;
	    if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
	    	remove_action('wp_head', array(
		        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
		        'recent_comments_style'
   		 	));
	    }
	    
	}


	public function pointfindercoreelementsgravatar($avatar_defaults)
	{
	    $myavatar = PFCOREELEMENTSURL . 'images/empty_avatar.jpg';
	    $avatar_defaults[$myavatar] = "Custom Gravatar";
	    return $avatar_defaults;
	}

	public function pf_add_slug_to_body_class($classes)
	{
	    global $post;
	    if (is_home()) {
	        $key = array_search('blog', $classes);
	        if ($key > -1) {
	            unset($classes[$key]);
	        }
	    } elseif (is_page()) {
	        $classes[] = sanitize_html_class($post->post_name);
	    } elseif (is_singular()) {
	        $classes[] = sanitize_html_class($post->post_name);
	    }

	    if(is_tax() || is_tag() || is_category()){
	        $general_ct_page_layout = $this->PFSAIssetControl('general_ct_page_layout','','1');
	        if( $general_ct_page_layout == 3 ) {
	            $classes[] = 'pfhalfpagemapview';
	        }
	        if( $general_ct_page_layout == 2 || $general_ct_page_layout == 1) {
	            $classes[] = 'pftoppagemapview';
	        }

	        if( $general_ct_page_layout == 1) {
	            $classes[] = 'pftoppagemapviewdef';
	        }
	    }

	    if(is_search()){
	        $setup42_searchpagemap_headeritem = $this->PFSAIssetControl('setup42_searchpagemap_headeritem','','1');
	        if( $setup42_searchpagemap_headeritem == 2 ) {
	            $classes[] = 'pfhalfpagemapview';
	        }elseif( $setup42_searchpagemap_headeritem == 1 ){
	        	$classes[] = 'pftoppagemapview';
	        }else{
	        	$classes[] = 'pftoppagemapviewdef';
	        }

	    }

	    return $classes;
	}

	public function pointfinder_wp_nav_menu_args($args = '')
	{
	    $args['container'] = false;
	    return $args;
	}

	public function pf_remove_category_rel_from_category_list($thelist)
	{
	    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
	}

	public function pointfinder_remove_thumbnail_dimensions( $html )
	{
	    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
	    return $html;
	}

	public function pointfinderh_blank_view_article($more)
	{
	    global $post;
	    if($post->post_type == 'post'){
	        $output = '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . esc_html__('View Article', 'pointfindercoreelements') . '</a>';
	    	return $output;
	    }
	}

	public function pointfinder_modify_read_more_link() {
		return '...';
	}

	 /**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	public function pointfinder_wp_title( $title, $sep ) {
	    if ( is_feed() ) {
	        return $title;
	    }

	    global $page, $paged;

	    $title .= get_bloginfo( 'name', 'display' );

	    $site_description = get_bloginfo( 'description', 'display' );
	    if ( $site_description && ( is_home() || is_front_page() ) ) {
	        $title .= " $sep $site_description";
	    }

	    if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
	        $title .= " $sep " . sprintf( esc_html__( 'Page %s', 'pointfindercoreelements' ), max( $paged, $page ) );
	    }

	    return $title;
	}

	public function pointfinder_possibly_redirect(){
		$as_redirect_logins = $this->PFSAIssetControl('as_redirect_logins','','0');
		if ($as_redirect_logins == 0 ) {return;}
		global $pagenow;
		if( 'wp-login.php' == $pagenow ) {

			$setup4_membersettings_dashboard = $this->PFSAIssetControl('setup4_membersettings_dashboard','',esc_url(site_url("/")));
			$setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);

			$pfmenu_perout = $this->PFPermalinkCheck();

			$special_linkurl = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems';

			if (isset($_GET['action']) == 'rp') {

			} else {
			    if ( isset( $_POST['wp-submit'] ) ||
			      ( isset($_GET['action']) && $_GET['action']=='logout') ||
			      ( isset($_GET['checkemail']) && $_GET['checkemail']=='confirm') ||
			      ( isset($_GET['checkemail']) && $_GET['checkemail']=='registered') ) return;
			    else wp_redirect( $special_linkurl );
			    exit();
			}

		}
	}

	public function pointfinder_redirect_logged_in_user( $redirect_to = null ) {
        $user = wp_get_current_user();
        if ( user_can( $user, 'manage_options' ) ) {
            if ( $redirect_to ) {
                wp_safe_redirect( $redirect_to );
            } else {
                wp_redirect( admin_url() );
            }
        } else {
            $setup4_membersettings_dashboard = $this->PFSAIssetControl('setup4_membersettings_dashboard','',esc_url(site_url("/")));
            $setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);

            $homeurllink = esc_url(site_url("/"));
            $pfmenu_perout = $this->PFPermalinkCheck();
            $special_linkurl = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems';
            wp_redirect( $special_linkurl );
        }
    }

    /**
     * Redirects the user to the custom "Forgot your password?" page instead of
     * wp-login.php?action=lostpassword.
     */

    public function pointfinder_redirect_to_custom_lostpassword() {
        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            if ( is_user_logged_in() ) {
                $this->pointfinder_redirect_logged_in_user();
                exit;
            }
            $redirect_url = esc_url(home_url("/"));
            $redirect_url = add_query_arg( 'active', 'lp', $redirect_url );/*Lost Pass call*/
            wp_redirect( $redirect_url );
            exit;
        }
    }

     /**
     * Redirects the user to the custom "Forgot your password?" page instead of
     * wp-login.php?action=confirmaction.
     */

    public function pointfinder_redirect_to_custom_confirmaction() {
        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            $redirect_url = esc_url(home_url("/"));
            $request_id = (int) $_GET['request_id'];
			$key = sanitize_text_field( wp_unslash( $_GET['confirm_key'] ) );
            $redirect_url = add_query_arg( 
            	array(
            		'confirm_key' => $key,
            		'request_id' => $request_id,
            		'active' => 'confirmaction'
            	),
            	$redirect_url
            );
            wp_redirect( $redirect_url );
            exit;
        }
    }

    /**
     * Redirects to the custom password reset page, or the login page
     * if there are errors.
     */
    public function pointfinder_redirect_to_custom_password_reset() {
        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {

            $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
            $redirect_url = esc_url(home_url("/"));

            if ( ! $user || is_wp_error( $user ) ) {
                if ( $user && $user->get_error_code() === 'expired_key' ) {
                    $redirect_url = add_query_arg( 'active', 'lpex', $redirect_url );/*Expired Key*/
                    wp_redirect( $redirect_url );
                } else {
                    $redirect_url = add_query_arg( 'active', 'lpin', $redirect_url );/*Invalid Key*/
                    wp_redirect( $redirect_url );
                }
                exit;
            }

            $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
            $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );
            $redirect_url = add_query_arg( 'active', 'lpr', $redirect_url );/*Password Reset Page*/

            wp_redirect( $redirect_url );
            exit;
        }
    }

    public function pointfinder_lp_system_handler(){
        if(isset($_GET['active'])){
            $scontenttype = sanitize_text_field( $_GET['active'] );
        }else{
            $scontenttype = '';
        }


        switch ($scontenttype) {
            case 'lp':
                $scontenttext = 'lp';
                break;
            case 'lpex':
                $scontenttext = 'lpex';
                break;
            case 'lpin':
                $scontenttext = 'lpin';
                break;
            case 'lpr':
                $scontenttext = '<input type=\"hidden\" name=\"rp_key\" value=\"'.$_GET['key'].'\"/><input type=\"hidden\" name=\"rp_login\" value=\"'.$_GET['login'].'\"/>';
                break;
        }


        if(!empty($scontenttype) && !empty($scontenttext)){
            echo '<script type="text/javascript">(function($) {"use strict";$(function() {
            $.pfOpenLogin("open","'.$scontenttype.'","'.$scontenttext.'");
            });})(jQuery);</script>';
        }
    }

     public function pointfinder_accremove_system_handler(){
        if(isset($_GET['active'])){
            $scontenttype = sanitize_text_field( $_GET['active'] );
        }else{
            $scontenttype = '';
        }

        if ($scontenttype == 'confirmaction') {
        	$text = json_encode(array('confirm_key'=>$_GET['confirm_key'],'request_id' => $_GET['request_id']),JSON_PRETTY_PRINT);

        	echo '<script type="text/javascript">(function($) {"use strict";$(function() {
        		var confirmactiontext = '.$text.';
            $.pfOpenLogin("open","confirmaction",confirmactiontext);
            });})(jQuery);</script>';
        }

    }
    
	/**
	 * Title Filter
	 * Added with v1.7.2
	 */
	public function pointfinder_title_filter( $where, $wp_query )
	{


	    global $wpdb;


	    if ( $search_term = $wp_query->get( 'search_prod_title' ) ) {
	        $system_search_setup = $this->PFSAIssetControl('system_search_setup','','3');

	        $search_term_original = $wpdb->esc_like( $search_term );

	        if($search_term != ''){

	            switch ($system_search_setup) {
	                case '1':
	                    /*Or operator*/
	                    $search_term = explode(' ', $search_term_original);
	                    if (is_array($search_term)) {
	                        if (count($search_term) > 1) {
	                            $i = 1;
	                            $search_term_count = count($search_term);
	                            foreach ($search_term as $single_search_term) {
	                                if ($i == 1) {
	                                    if ($i == $search_term_count) {
	                                        $where .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\')';
	                                    }else{
	                                        $where .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }

	                                }else{
	                                    if ($i == $search_term_count) {
	                                        $where .= ' OR ' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\')';
	                                    }else{
	                                        $where .= ' OR ' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }
	                                }
	                                $i++;
	                            }
	                        }else{
	                            $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\'';
	                        }
	                    }else{
	                        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\'';
	                    }
	                    break;

	                case '2':
	                    /* and operator*/
	                    $search_term = explode(' ', $search_term_original);
	                    if (is_array($search_term)) {
	                        if (count($search_term) > 1) {
	                            $i = 1;
	                            $search_term_count = count($search_term);
	                            foreach ($search_term as $single_search_term) {
	                                if ($i == 1) {
	                                    if ($i == $search_term_count) {
	                                        $where .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\')';
	                                    }else{
	                                        $where .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }

	                                }else{
	                                    if ($i == $search_term_count) {
	                                        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\')';
	                                    }else{
	                                        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }
	                                }
	                                $i++;
	                            }
	                        }else{
	                            $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\'';
	                        }
	                    }else{
	                        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\'';
	                    }
	                    break;

	                case '3':
	                    /* exact word */
	                    $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\'';
	                    break;

	                case '4':
	                    /*Mixed words*/
	                    $search_term = explode(' ', $search_term_original);
	                    if (is_array($search_term)) {
	                        if (count($search_term) > 1) {
	                            $i = 1;
	                            $search_term_count = count($search_term);
	                            foreach ($search_term as $single_search_term) {
	                                if ($i == 1) {
	                                    if ($i == $search_term_count) {
	                                        $where .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\' AND '.$wpdb->posts.'.post_type = "'.$this->post_type_name.'")';
	                                    }else{
	                                        $where .= ' AND ((' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }

	                                }else{
	                                    if ($i == $search_term_count) {
	                                        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\' AND '.$wpdb->posts.'.post_type = "'.$this->post_type_name.'")';
	                                    }else{
	                                        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }
	                                }
	                                $i++;
	                            }
	                            $where .= ' OR (' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\' AND '.$wpdb->posts.'.post_type = "'.$this->post_type_name.'"))';

	                        }else{
	                            $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\'';
	                        }
	                    }else{
	                        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\'';
	                    }
	                    break;
	            }



	        }
	    }
	    return $where;
	}

	/**
	 * Description Filter
	 * Added with v1.7.2
	 */
	public function pointfinder_description_filter( $where, $wp_query )
	{
	    global $wpdb;
	    if ( $search_term = $wp_query->get( 'search_prod_desc' ) ) {

	        $system_search_setup = $this->PFSAIssetControl('system_search_setup','','3');

	        if($search_term != ''){

	            $search_term_original = $wpdb->esc_like( $search_term );
	            switch ($system_search_setup) {
	                case '1':
	                    /*Or operator*/
	                    $search_term = explode(' ', $search_term_original);
	                    if (is_array($search_term)) {
	                        if (count($search_term) > 1) {
	                            $i = 1;
	                            $search_term_count = count($search_term);
	                            foreach ($search_term as $single_search_term) {
	                                if ($i == 1) {
	                                    if ($i == $search_term_count) {
	                                        $where .= ' AND (' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\')';
	                                    }else{
	                                        $where .= ' AND (' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }

	                                }else{
	                                    if ($i == $search_term_count) {
	                                        $where .= ' OR ' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\')';
	                                    }else{
	                                        $where .= ' OR ' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }
	                                }
	                                $i++;
	                            }
	                        }else{
	                            $where .= ' AND ' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\'';
	                        }
	                    }else{
	                        $where .= ' AND ' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\'';
	                    }
	                    break;

	                case '2':
	                    /* and operator*/
	                    $search_term = explode(' ', $search_term_original);
	                    if (is_array($search_term)) {
	                        if (count($search_term) > 1) {
	                            $i = 1;
	                            $search_term_count = count($search_term);
	                            foreach ($search_term as $single_search_term) {
	                                if ($i == 1) {
	                                    if ($i == $search_term_count) {
	                                        $where .= ' AND (' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\')';
	                                    }else{
	                                        $where .= ' AND (' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }

	                                }else{
	                                    if ($i == $search_term_count) {
	                                        $where .= ' AND ' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\')';
	                                    }else{
	                                        $where .= ' AND ' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }
	                                }
	                                $i++;
	                            }
	                        }else{
	                            $where .= ' AND ' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\'';
	                        }
	                    }else{
	                        $where .= ' AND ' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\'';
	                    }
	                    break;

	                case '3':
	                    /* exact word */
	                    $where .= ' AND ' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\'';
	                    break;

	                case '4':
	                    /*Mixed words*/
	                    $search_term = explode(' ', $search_term_original);
	                    if (is_array($search_term)) {
	                        if (count($search_term) > 1) {
	                            $i = 1;
	                            $search_term_count = count($search_term);
	                            foreach ($search_term as $single_search_term) {
	                                if ($i == 1) {
	                                    if ($i == $search_term_count) {
	                                        $where .= ' AND (' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }else{
	                                        $where .= ' AND (' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }

	                                }else{
	                                    if ($i == $search_term_count) {
	                                        $where .= ' AND ' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }else{
	                                        $where .= ' AND ' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }
	                                }
	                                $i++;
	                            }
	                            $where .= ' OR ' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\')';

	                        }else{
	                            $where .= ' AND ' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\'';
	                        }
	                    }else{
	                        $where .= ' AND ' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\'';
	                    }
	                    break;
	            }


	        }
	    }
	    return $where;
	}



	/**
	 * Title & Desc Filter
	 * Added with v1.7.3.3
	 */
	public function pointfinder_title_desc_filter( $where, $wp_query )
	{


	    global $wpdb;


	    if ( $search_term = $wp_query->get( 'search_prod_desc_title' ) ) {
	        $system_search_setup = $this->PFSAIssetControl('system_search_setup','','3');

	        $search_term_original = $wpdb->esc_like( $search_term );
	        $where2 = '';

	        if($search_term != ''){

	            switch ($system_search_setup) {
	                case '4':
	                case '1':
	                    /*Or operator*/
	                    $search_term = explode(' ', $search_term_original);
	                    if (is_array($search_term)) {
	                        if (count($search_term) > 1) {
	                            $i = 1;
	                            $search_term_count = count($search_term);
	                            foreach ($search_term as $single_search_term) {
	                                if ($i == 1) {
	                                    if ($i == $search_term_count) {
	                                        $where .= ' AND (' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\')';
	                                    }else{
	                                        $where .= ' AND (' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }

	                                    if ($i == $search_term_count) {
	                                        $where2 .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\')';
	                                    }else{
	                                        $where2 .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }

	                                }else{
	                                    if ($i == $search_term_count) {
	                                        $where .= ' OR ' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\')';
	                                    }else{
	                                        $where .= ' OR ' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }

	                                    if ($i == $search_term_count) {
	                                        $where2 .= ' OR ' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\')';
	                                    }else{
	                                        $where2 .= ' OR ' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }
	                                }
	                                $i++;
	                            }

	                            $where .= $where2;

	                        }else{
	                            $where .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\' OR post_content LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\')';
	                        }
	                    }else{
	                        $where .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\' OR post_content LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\')';
	                    }
	                    break;

	                case '2':
	                    /* and operator*/
	                    $search_term = explode(' ', $search_term_original);
	                    if (is_array($search_term)) {
	                        if (count($search_term) > 1) {
	                            $i = 1;
	                            $search_term_count = count($search_term);
	                            foreach ($search_term as $single_search_term) {
	                                if ($i == 1) {
	                                    if ($i == $search_term_count) {
	                                        $where .= ' AND (' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\')';
	                                    }else{
	                                        $where .= ' AND (' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }

	                                    if ($i == $search_term_count) {
	                                        $where2 .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\')';
	                                    }else{
	                                        $where2 .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }

	                                }else{
	                                    if ($i == $search_term_count) {
	                                        $where .= ' AND ' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\')';
	                                    }else{
	                                        $where .= ' AND ' . $wpdb->posts . '.post_content LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }

	                                    if ($i == $search_term_count) {
	                                        $where2 .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\')';
	                                    }else{
	                                        $where2 .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $single_search_term ) . '%\'';
	                                    }
	                                }
	                                $i++;
	                            }

	                            $where .= $where2;

	                        }else{
	                            $where .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\' AND post_content LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\')';
	                        }
	                    }else{
	                        $where .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\' AND post_content LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\')';
	                    }

	                case '3':
	                    /* exact word */
	                    $where .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\' OR post_content LIKE \'%' . sanitize_text_field(  $search_term_original ) . '%\')';
	                    break;
	            }
	        }
	    }
	    return $where;
	}

	public function pointfinder_tags_page_fix( $query ) {


	        if( $query->is_tag() && $query->is_main_query() ){
	            $setup22_searchresults_defaultppptype = $this->PFSAIssetControl('setup22_searchresults_defaultppptype','',10);

	            $ppp = (isset($_GET['pfsearch-filter-number']))?$_GET['pfsearch-filter-number']:$setup22_searchresults_defaultppptype;
	            $ppp = intval($ppp);
	            $query->set( 'post_type', array( $this->post_type_name ) );
	            $query->set('posts_per_page', $ppp );
	            $query->query_vars['posts_per_page'] = $ppp;
	            $query->query['posts_per_page'] = $ppp;
	        }

	}

	public function pointfinder_halfpage_map_body_class( $classes ) {

	    $pffullwlayoutheader = $this->PFSAIssetControl('pffullwlayoutheader','','0');

	    if( is_search()) {
	        $setup42_searchpagemap_headeritem = $this->PFSAIssetControl('setup42_searchpagemap_headeritem','','1');
					if ( class_exists( 'WooCommerce' ) ) {
						if ($setup42_searchpagemap_headeritem == 2 && !is_woocommerce()) {
		            $classes[] = 'pfdisableshrink';
		        }
					}else{
						if ($setup42_searchpagemap_headeritem == 2) {
								$classes[] = 'pfdisableshrink';
						}
					}
	    }



			if ( class_exists( 'WooCommerce' ) ) {
					if ((is_archive() || is_category() || is_tag() || is_search()) && !is_woocommerce()) {
							$general_ct_page_layout = $this->PFSAIssetControl('general_ct_page_layout','','1');
							if ($general_ct_page_layout == 3) {
									$classes[] = 'pfdisableshrink';
							}
					}
	    } else {
					if (is_archive() || is_category() || is_tag() || is_search()) {
							$general_ct_page_layout = $this->PFSAIssetControl('general_ct_page_layout','','1');
							if ($general_ct_page_layout == 3) {
									$classes[] = 'pfdisableshrink';
							}
					}
	    }

	    if ($pffullwlayoutheader == 1 && (!is_archive() && !is_category() && !is_tag() && !is_search())) {
	        $classes[] = 'pffullwidthheader';
	    }
	    return $classes;
	}

	/**
	 * Tags Cloud Filter
	 * Added with v1.6.5
	 */
	public function pointfinder_tag_cloud_limit($args){
	    if ( isset($args['taxonomy']) && $args['taxonomy'] == 'post_tag' ){
	        $as_tags_cloud = $this->PFASSIssetControl('as_tags_cloud','',45);
	        $args['number'] = $as_tags_cloud;
	    }
	    return $args;
	}

	public function pfedit_my_widget_title($title = '', $instance = array(), $id_base = '') {

		if (!empty($id_base)) {
			if (empty($instance['title'])) {
				echo '<div class="pfwidgettitle pfemptytitle"><div class="widgetheader"></div></div><div class="pfwidgetinner pfemptytitle">';

			}else{
				echo '<div class="pfwidgettitle"><div class="widgetheader">'.$title.'</div></div><div class="pfwidgetinner">';
			}
		}else{
			if (!empty($title)) {
				echo '<div class="pfwidgettitle"><div class="widgetheader">'.$title.'</div></div><div class="pfwidgetinner">';
			}else{
				echo '<div class="pfwidgettitle pfemptytitle"><div class="widgetheader"></div></div><div class="pfwidgetinner pfemptytitle">';
			}

		}
	}

	public function PF_SAVE_FEATURED_MARKER_DATA( $post_id,$post,$update ) {

	    if ( $this->post_type_name == $post->post_type) {

		    if ($update) {
		    	$featured_status = get_post_meta( $post_id, 'webbupointfinder_item_featuredmarker', true );
		    	if (empty($featured_status)) {
		    		update_post_meta($post_id, 'webbupointfinder_item_featuredmarker', 0);
		    	}
		    }else{
		    	update_post_meta($post_id, 'webbupointfinder_item_featuredmarker', 0);

		    	if (isset($_POST['pfget_uploaditem'])) {
			    	if(isset($_POST['featureditembox'])){
			    		if ($_POST['featureditembox'] == "on") {
							update_post_meta($post_id, 'webbupointfinder_item_featuredmarker', 1);
			    		}
			    	}
			    }
		    }

	    }

	}

	public function pointfinder_form_class_attr( $class ) {
		$class .= ' golden-forms';
		return $class;
	}

	public function pointfinder_wpcf7_form_elements( $content ) {

		$rl_pfind = '/<p>/';
		$rl_preplace = '<p class="wpcf7-form-text">';
		$content = preg_replace( $rl_pfind, $rl_preplace, $content, 20 );

		return $content;
	}

	public function pf_default_comments_on( $data ) {
	    if( $data['post_type'] == $this->post_type_name ) {
	        $data['comment_status'] = "open";
	    }

	    return $data;
	}

	public function pointfinder_disable_admin_hook2() {
		$output="<style> .admin-bar #pfheadernav { margin-top:0!important } </style>";
		echo $output;
	}

	public function pointfinder_disable_admin_hook1() {
		$output="<style> .admin-bar #pfheadernav { margin-top:0!important } </style>";
		echo $output;
	}

	/*------------------------------------
	Fix for taxonomy paging
	------------------------------------*/
	public function pointfinder_alter_query_for_fix_default_taxorder($qry) {
	   if ( $qry->is_main_query() && is_tax(array('pointfinderltypes','pointfinderitypes','pointfinderlocations','pointfinderfeatures')) ) {
	     $setup42_authorpagedetails_defaultppptype = $this->PFSAIssetControl('setup22_searchresults_defaultppptype','','10');
	     $qry->set('post_type',$this->post_type_name);
	     $qry->set('posts_per_page',$setup42_authorpagedetails_defaultppptype);
	   }
	}

	/*------------------------------------*\
	Invoice Post Type Fix
	\*------------------------------------*/
	public function pf_invoices_mainfix(){
		global $post_type;
		if ($post_type == 'pointfinderinvoices') {
			echo '<style>html{height:100%!important}</style>';
		}
	}

	/*------------------------------------*\
	WP Editor Fix
	\*------------------------------------*/
	public function pf_newwp_editor_action($item_desc){
		add_editor_style();
		$ed_settings = array(
			'media_buttons' => false,
			'teeny' => true,
			'editor_class' => 'textarea mini',
			'textarea_name' => 'item_desc',
			'drag_drop_upload' => false,
			'dfw' => false,
			'tinymce' => true,
			'quicktags' => false
		);
		ob_start();
		wp_editor( $item_desc, 'item_desc', $ed_settings );
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	/*------------------------------------*\
	New Point Generator CSS System
	\*------------------------------------*/
	public function pointfinder_custom_pointStyles_newsys() {
		if ($this->PFASSIssetControl('st8_npsys','',0) != 1) {
			return;
		}

		$listing_meta = get_option('pointfinderltypes_style_vars');

		$csstext = "";
		if (is_array($listing_meta)) {	
			foreach ($listing_meta as $key => $value) {

				$cpoint_type = (isset($value['cpoint_type']))?$value['cpoint_type']:0;

				if (!empty($cpoint_type)) {
					$cpoint_bgimage = (isset($value['cpoint_bgimage'][0]))?$value['cpoint_bgimage'][0]:'';

					if (empty($cpoint_bgimage)) {
						$cpoint_bgcolor = (isset($value['cpoint_bgcolor']))?$value['cpoint_bgcolor']:'';
						$cpoint_bgcolorinner = (isset($value['cpoint_bgcolorinner']))?$value['cpoint_bgcolorinner']:'';
						$cpoint_iconcolor = (isset($value['cpoint_iconcolor']))?$value['cpoint_iconcolor']:'';

						$csstext .= ".pfcat$key-mapicon {background:$cpoint_bgcolor;}";
						$csstext .= ".pfcat$key-mapicon:after {background: $cpoint_bgcolorinner;}";
						$csstext .= ".pfcat$key-mapicon i {color: $cpoint_iconcolor;}";
					}else{
						$cpoint_bgimage_url = wp_get_attachment_image_src($cpoint_bgimage,'full');

						if (isset($cpoint_bgimage_url[1]) && isset($cpoint_bgimage_url[2])) {
							$height_calculated = $cpoint_bgimage_url[2];
							$width_calculated = $cpoint_bgimage_url[1];
						}else{
							$width_calculated = 100;
							$height_calculated = 100;
						}

						$csstext .= '.pfcat'.$key.'-mapicon{background-image:url('.$cpoint_bgimage_url[0].');opacity:1;background-size:'.$width_calculated.'px '.$height_calculated.'px; width:'.$width_calculated.'px; height:'.$height_calculated.'px;}';
					}
				}

			}
		}

		/*Default Icon*/
		$cpoint_type = $this->PFASSIssetControl('cpoint_type','',0);

		if ($cpoint_type == 0) {

			$cpoint_bgcolor = $this->PFASSIssetControl('cpoint_bgcolor','','#b00000');
			$cpoint_bgcolorinner = $this->PFASSIssetControl('cpoint_bgcolorinner','','#ffffff');
			$cpoint_iconcolor = $this->PFASSIssetControl('cpoint_iconcolor','','#b00000');

			$csstext .= ".pfcatdefault-mapicon {background:$cpoint_bgcolor;}";
			$csstext .= ".pfcatdefault-mapicon:after {background: $cpoint_bgcolorinner;}";
			$csstext .= ".pfcatdefault-mapicon i {color: $cpoint_iconcolor;}";

		}else{
			$cpoint_bgimage = $this->PFASSIssetControl('cpoint_bgimage','','');

		}

		
		wp_add_inline_style( 'pftheme-minified-package-css', $csstext );
		
	}


	public function pointfinder_admin_bar_operations(){

		$setup4_membersettings_hideadminbar = $this->PFSAIssetControl('setup4_membersettings_hideadminbar','','1');
		$general_hideadminbar = $this->PFSAIssetControl('general_hideadminbar','','1');

		if (  current_user_can( 'manage_options' ) && $general_hideadminbar == 0) {
		    show_admin_bar( false );
		    add_filter( 'show_admin_bar', '__return_false');
		    add_filter( 'wp_admin_bar_class', '__return_false');
		    add_action( 'wp_head', array($this,'pointfinder_disable_admin_hook1'));
		}

		if (  !current_user_can( 'manage_options' ) && $setup4_membersettings_hideadminbar == 0) {
		    show_admin_bar( false );
		    add_filter( 'show_admin_bar', '__return_false');
		    add_filter( 'wp_admin_bar_class', '__return_false');
		    add_action( 'wp_head', array($this,'pointfinder_disable_admin_hook2'));
		}
	}

	public function pointfinder_body_class_filter( $classes ) {
		if($this->PFSAIssetControl('setup4_membersettings_loginregister','','1') != 1){
			return $classes;
		}
		$setup4_membersettings_dashboard = $this->PFSAIssetControl('setup4_membersettings_dashboard','','');
	    if ( is_page($setup4_membersettings_dashboard) ) {
	    	$ua_action ='';
	    	if(isset($_GET['ua'])){ $ua_action = esc_attr($_GET['ua']);}
	    	if ($ua_action == 'newitem' || $ua_action == 'edititem') {
	    		$classes[] = 'pfdashboardpagenewedit';
	    	}else{
	    		$classes[] = 'pfdashboardpage';
	    	}
	    }
	    return $classes;
	}

	public function pointfinder_authorpage_filter_function($content, $current_author){
		if(!empty($current_author)){

			$authorpmap = $this->PFSAIssetControl('authorpmap','',1);

	        if ($authorpmap != 1) {
	            if(function_exists('PFGetDefaultPageHeader')){
					PFGetDefaultPageHeader(array('author_id'=>$current_author->ID));
				}
	        }else{
	            echo '<div></div>';
        	}

			

			$setup42_itempagedetails_sidebarpos_auth = $this->PFSAIssetControl('setup42_itempagedetails_sidebarpos_auth','','2');
			echo '<section role="main" class="pf-itempage-maindiv">';
				echo '<div class="pf-container clearfix">';
				echo '<div class="pf-row clearfix">';
	    		if ($setup42_itempagedetails_sidebarpos_auth == 2) {
					$this->PFGetAuthorPageCol1($current_author->ID);
	          		$this->PFGetAuthorPageCol2();
				} elseif ($setup42_itempagedetails_sidebarpos_auth == 1) {
					$this->PFGetAuthorPageCol2();
	          		$this->PFGetAuthorPageCol1($current_author->ID);
				}else{
					$this->PFGetAuthorPageCol1($current_author->ID);
				}
	    		echo '</div>';
	        	echo '</div>';
	        echo '</section>';
			                
		}else{

			PFPageNotFound(); 

		}
	}



	public function pointfinder_invoicepage_filter_function($content, $post_id, $current_user){
		/*Check invoice if belongs to this user.*/
		if (isset($current_user->ID)) {
			$current_user_id = $current_user->ID;

			if(class_exists('SitePress')) {
				$lang = $this->PF_current_language();
				$post_id = apply_filters( 'wpml_object_id', $post_id, 'pointfinderinvoices',false,''.$lang.'' );
			}
			
			global $wpdb;
			$post_author = $wpdb->get_var($wpdb->prepare("SELECT post_author FROM $wpdb->posts WHERE post_type = %s and ID = %d",'pointfinderinvoices',$post_id));
			$post_status = $wpdb->get_var($wpdb->prepare("SELECT post_status FROM $wpdb->posts WHERE post_type = %s and ID = %d",'pointfinderinvoices',$post_id));

			if (($post_author == $current_user_id)) {
				if ($post_status != 'pendingpayment') {
					echo $this->pointfinder_invoicesystem_template_html(array('invoiceid'=>$post_id,'userid'=>$current_user_id));
				}else{
					echo '<div style="margin-top:30px;margin-left:auto;margin-right:auto;width: 100%;text-align: center;font-family: Arial;">'.esc_html__("Sorry this invoice not ready yet. Please complete payment.","pointfindercoreelements").'</div>';
				}
			}elseif(current_user_can('activate_plugins')){
				if ($post_status != 'pendingpayment') {
					echo $this->pointfinder_invoicesystem_template_html(array('invoiceid'=>$post_id,'userid'=>$post_author));
				}else{
					echo '<div style="margin-top:30px;margin-left:auto;margin-right:auto;width: 100%;text-align: center;font-family: Arial;">'.esc_html__("Sorry this invoice not ready yet. Please complete payment.","pointfindercoreelements").'</div>';
				}
			}else{PFPageNotFound();}
		}else{PFPageNotFound();}
	}


	public function pointfinder_search_page_func(){
		/**
		*Start: Get search data & apply to query arguments.
		**/
			$pfgetdata = $_GET;

			$pfne = $pfne2 = $pfsw = $pfsw2 = $pfpointfinder_google_search_coord = $hidden_output = $search_output = '';

			$searchkeys = array('pfsearch-filter','pfsearch-filter-order','pfsearch-filter-number','pfsearch-filter-col');

			if(is_array($pfgetdata)){

				$pfformvars = array();
				$pfgetdata = $this->PFCleanArrayAttr('PFCleanFilters',$pfgetdata);

				foreach($pfgetdata as $key=>$value){

					if (is_array($value)) {if (empty($value[0])) {unset($value[0]);}}

					if(!empty($value)){
						if(isset($pfformvars[$key])){
							$pfformvars[$key] = $pfformvars[$key]. ',' .$value;
						}else{
							$pfformvars[$key] = $value;
						}
						if (!is_array($value)) {
							if(!in_array($key, $searchkeys)){$hidden_output .= '<input type="hidden" name="'.$key.'" value="'.$value.'"/>';}
						}
					}

					if ($key == 'ne') {$pfne = sanitize_text_field($value);}
					if ($key == 'ne2') {$pfne2 = sanitize_text_field($value);}
					if ($key == 'sw') {$pfsw = sanitize_text_field($value);}
					if ($key == 'sw2') {$pfsw2 = sanitize_text_field($value);}
					if ($key == 'pointfinder_google_search_coord') {$pfpointfinder_google_search_coord = sanitize_text_field($value);}

				}

				$hidden_output .= '<input type="hidden" name="s" value=""/>';


				$args = array( 'post_type' => $this->post_type_name, 'post_status' => 'publish');

				if(isset($_GET['pfsearch-filter']) && $_GET['pfsearch-filter']!=''){$pfg_orderbyx = esc_attr($_GET['pfsearch-filter']);}else{$pfg_orderbyx = '';}

				if(isset($_POST['pfg_order']) && $_POST['pfg_order']!=''){$pfg_orderx = esc_attr($_POST['pfg_order']);}else{$pfg_orderx = '';}
				if (empty($pfg_orderx)) {
					if(isset($_GET['pfsearch-filter-order'])){
						$pfg_orderx = sanitize_text_field($_GET['pfsearch-filter-order']);
					}else{
						$pfg_orderx = '';
					}
				}
				if(isset($_POST['pfg_number']) && $_POST['pfg_number']!=''){$pfg_numberx = esc_attr($_POST['pfg_number']);}else{$pfg_numberx = '';}

				$setup22_searchresults_defaultppptype = $this->PFSAIssetControl('setup22_searchresults_defaultppptype','','10');
				$setup22_searchresults_defaultsortbytype = $this->PFSAIssetControl('setup22_searchresults_defaultsortbytype','','ID');
				$setup22_searchresults_defaultsorttype = $this->PFSAIssetControl('setup22_searchresults_defaultsorttype','','ASC');

				/* Main Order Filters */
				$setup31_userpayments_featuredoffer = $this->PFSAIssetControl('setup31_userpayments_featuredoffer','','1');
				$setup22_featrand = $this->PFSAIssetControl('setup22_featrand','','0');
				

				if ($setup31_userpayments_featuredoffer == 1) {
					if ($setup22_featrand == 1) {
						$args['orderby']['query_featured']= 'rand';
					}else{
						$args['orderby']['query_featured']= 'DESC';
					}
					$args['meta_query']['query_featured'] = array('key' => 'webbupointfinder_item_featuredmarker','type'=>'NUMERIC');
					if (!empty($pfgetdata['manual_args'])) {
						$args['manual_args']['orderby']['query_featured']= 'DESC';
						$args['manual_args']['meta_query']['query_featured'] = array('key' => 'webbupointfinder_item_featuredmarker','type'=>'NUMERIC');
					}
				}

				if (!empty($pfg_numberx)) {
					$args['posts_per_page'] = $pfg_numberx;
				}else{
					$args['posts_per_page'] = $setup22_searchresults_defaultppptype;
				}

				$pagedpf = '';

				if(isset($_GET['page'])){
					$pagedpf = absint($_GET['page']);
				}
				
				if(!empty($pagedpf)){$args['paged'] = $pagedpf;}

				if($pfg_orderbyx != ''){
					if($pfg_orderbyx == 'date' || $pfg_orderbyx == 'title'){
						$args['orderby'][$pfg_orderbyx]= $pfg_orderx;
					}else{
						if ($pfg_orderbyx == 'reviewcount') {
							if (class_exists('Pointfinderspecialreview_Public')) {
						        $args['orderby']['query_review']= $pfg_orderx;
							    $args['meta_query']['query_review'] = array('key' => 'pfreviewx_totalperitem','type'=>'NUMERIC');    
						    }else{
						        $args['orderby']['query_review']= $pfg_orderx;
							    $args['meta_query']['query_review'] = array('key' => 'webbupointfinder_item_reviewcount','type'=>'NUMERIC');
						    }
						}else{
							if($this->PFIF_CheckFieldisNumeric_ld($pfg_orderbyx) == false){
								$args['orderby']['query_key']= $pfg_orderx;
								$args['meta_query']['query_key'] = array('key' => 'webbupointfinder_item_'.$pfg_orderbyx,'type'=>'CHAR');
							}else{
								$args['orderby']['query_key']= $pfg_orderx;
								$args['meta_query']['query_key'] = array('key' => 'webbupointfinder_item_'.$pfg_orderbyx,'type'=>'NUMERIC');
							}
						}
					}
				}else{

					if ($setup22_searchresults_defaultsortbytype == 'rand') {
		              	$args['orderby']['rand']= '';
		            }elseif($setup22_searchresults_defaultsortbytype == 'reviewcount'){
		              if (class_exists('Pointfinderspecialreview_Public')) {
		                $args['orderby']['query_review']= $pfg_orderx;
		                $args['meta_query']['query_review'] = array('key' => 'pfreviewx_totalperitem','type'=>'NUMERIC');
		              }else{
		                $args['orderby']['query_review']= $pfg_orderx;
		                $args['meta_query']['query_review'] = array('key' => 'webbupointfinder_item_reviewcount','type'=>'NUMERIC');
		              }
		            }else{
		              $args['orderby'][$setup22_searchresults_defaultsortbytype]= $setup22_searchresults_defaultsorttype;
		            }
				}

				/* Cleanup query */
				$args = apply_filters( 'pointfinder_cleanup_query_for_grid', $args );

				/* Added with v1.8.7 */
				$pf_query_builder = new PointfinderSearchQueryBuilder($args);
				$pf_query_builder->setQueryValues($pfformvars,'search',$searchkeys);
				$args = $pf_query_builder->getQuery();

			}

		/**
		*End: Get search data & apply to query arguments.
		**/
		$setup42_searchpagemap_headeritem = $this->PFSAIssetControl('setup42_searchpagemap_headeritem','','1');

		if ($setup42_searchpagemap_headeritem == 0) {
			if(function_exists('PFGetDefaultPageHeader')){PFGetDefaultPageHeader();}

			$setup_item_searchresults_sidebarpos = $this->PFSAIssetControl('setup_item_searchresults_sidebarpos','','2');
			$setup22_searchresults_background2 = $this->PFSAIssetControl('setup22_searchresults_background2','','#ffffff');
			$setup42_authorpagedetails_grid_layout_mode = $this->PFSAIssetControl('setup22_searchresults_grid_layout_mode','','1');
			$setup22_searchresults_defaultlistingtype = $this->PFSAIssetControl('setup22_searchresults_defaultlistingtype','','4');
			$setup42_authorpagedetails_defaultppptype = $this->PFSAIssetControl('setup22_searchresults_defaultppptype','','10');
			$setup42_authorpagedetails_grid_layout_mode = ($setup42_authorpagedetails_grid_layout_mode == 1) ? 'fitRows' : 'masonry' ;

			
		}

		$setup42_searchpagemap_height = $this->PFSAIssetControl('setup42_searchpagemap_height','height','550');
		$setup42_searchpagemap_height = str_replace('px', '', $setup42_searchpagemap_height);
		$setup42_mheight = $this->PFSAIssetControl('setup42_mheight','height','350');
		$setup42_mheight = str_replace('px', '', $setup42_mheight);
		$setup42_theight = $this->PFSAIssetControl('setup42_theight','height','400');
		$setup42_theight = str_replace('px', '', $setup42_theight);
		

		
		$setup42_searchpagemap_type = 'ROADMAP';
		$setup42_searchpagemap_style = '';
		$setup7_geolocation_status = 0;

		$setup42_searchpagemap_lat = $this->PFSAIssetControl('setup42_searchpagemap_lat','','');
		$setup42_searchpagemap_lng = $this->PFSAIssetControl('setup42_searchpagemap_lng','','');
		$setup42_searchpagemap_zoom = $this->PFSAIssetControl('setup42_searchpagemap_zoom','','12');
		$setup42_searchpagemap_mobile = $this->PFSAIssetControl('setup42_searchpagemap_mobile','','10');
		$setup7_geolocation_status = $this->PFSAIssetControl('setup7_geolocation_status','',0);
    	$setup7_geolocation_distance = $this->PFSAIssetControl('setup7_geolocation_distance','',10);
		$setup7_geolocation_distance_unit = $this->PFSAIssetControl('setup7_geolocation_distance_unit','',"km");
		$setup7_geolocation_hideinfo = $this->PFSAIssetControl('setup7_geolocation_hideinfo','',1);
		$setup6_clustersettings_status = $this->PFSAIssetControl('setup6_clustersettings_status','',1);
		$stp6_crad = $this->PFSAIssetControl('stp6_crad','',100);
		$setup10_infowindow_height = $this->PFSAIssetControl('setup10_infowindow_height','','136');
		$setup10_infowindow_width = $this->PFSAIssetControl('setup10_infowindow_width','','350');
		$s10_iw_w_m = $this->PFSAIssetControl('s10_iw_w_m','','184');
		$s10_iw_h_m = $this->PFSAIssetControl('s10_iw_h_m','','136');

		$we_special_key = $wemap_here_appid = $wemap_here_appcode = '';
		    
	    $stp5_mapty = $this->PFSAIssetControl('stp5_mapty','',1);

		switch ($stp5_mapty) {
			case 1:
				$we_special_key = $this->PFSAIssetControl('setup5_map_key','','');
				break;

			case 3:
				$we_special_key = $this->PFSAIssetControl('stp5_mapboxpt','','');
				break;

			case 5:
				$wemap_here_appid = $this->PFSAIssetControl('wemap_here_appid','','');
				$wemap_here_appcode = $this->PFSAIssetControl('wemap_here_appcode','','');
				break;

			case 6:
				$we_special_key = $this->PFSAIssetControl('wemap_bingmap_api_key','','');
				break;

			case 4:
				$we_special_key = $this->PFSAIssetControl('wemap_yandexmap_api_key','','');
				break;
		}
			
	    echo '<div id="pointfinder-category-map" 
	    data-mode="topmap" 
	    data-height="'.$setup42_searchpagemap_height.'" 
	    data-theight="'.$setup42_theight.'" 
	    data-mheight="'.$setup42_mheight.'" 
	    data-lat="'.$setup42_searchpagemap_lat.'" 
		data-lng="'.$setup42_searchpagemap_lng.'" 
		data-zoom="'.$setup42_searchpagemap_zoom.'" 
		data-zoomm="'.$setup42_searchpagemap_mobile.'" 
		data-zoommx="18" 
		data-mtype="'.$stp5_mapty.'" 
		data-key="'.$we_special_key.'" 
		data-hereappid="'.$wemap_here_appid.'" 
		data-hereappcode="'.$wemap_here_appcode.'" 
		data-glstatus="'.$setup7_geolocation_status.'"
		data-gldistance="'.$setup7_geolocation_distance.'" 
		data-gldistanceunit="'.$setup7_geolocation_distance_unit.'" 
		data-gldistancepopup="'.$setup7_geolocation_hideinfo.'" 
		data-found=""  
		data-cluster="'.$setup6_clustersettings_status.'" 
		data-clusterrad="'.$stp6_crad.'" 
		data-iheight="'.$setup10_infowindow_height.'" 
		data-iwidth="'.$setup10_infowindow_width.'" 
		data-imheight="'.$s10_iw_h_m.'" 
		data-imwidth="'.$s10_iw_w_m.'" 
		></div>';

		if(isset($_GET['pfsearch-filter']) && $_GET['pfsearch-filter']!=''){$pfg_orderby = esc_attr($_GET['pfsearch-filter']);}else{$pfg_orderby = $setup22_searchresults_defaultsortbytype;}
		if(isset($_GET['pfsearch-filter-order']) && $_GET['pfsearch-filter-order']!=''){$pfg_order = esc_attr($_GET['pfsearch-filter-order']);}else{$pfg_order = $setup22_searchresults_defaultsorttype;}
		if(isset($_GET['pfsearch-filter-number']) && $_GET['pfsearch-filter-number']!=''){$pfg_number = esc_attr($_GET['pfsearch-filter-number']);}else{$pfg_number = $setup22_searchresults_defaultppptype;}
		

        if($setup42_searchpagemap_headeritem != 2){
        	$stp42_fltrs = $this->PFSAIssetControl('stp42_fltrs','','1');
			if ($stp42_fltrs == 1) {
				$filters_text = 'true';
			}else{
				$filters_text = 'false';
			}

			echo do_shortcode('[pf_directory_half_map setup5_mapsettings_zoom="'.$setup42_searchpagemap_zoom.'" setup5_mapsettings_zoom_mobile="'.$setup42_searchpagemap_mobile.'" setup5_mapsettings_autofit="1" setup5_mapsettings_autofitsearch="1" setup5_mapsettings_type="'.$setup42_searchpagemap_type.'" mapsearch_status="1" mapnot_status="1" setup5_mapsettings_lat="'.$setup42_searchpagemap_lat.'" setup5_mapsettings_lng="'.$setup42_searchpagemap_lng.'" setup5_mapsettings_style="'.$setup42_searchpagemap_style.'" setup7_geolocation_status="0" listingtype="" itemtype="" conditions="" features ="" locationtype=""  termname="-" csauto="" neaddress="'.$pfpointfinder_google_search_coord.'" ne="'.$pfne.'" ne2="'.$pfne2.'" sw="'.$pfsw.'" sw2="'.$pfsw2.'" locofrequest="topmap"]');
		}else{
			/* Half Map */
			echo do_shortcode('[pf_directory_half_map setup5_mapsettings_zoom="'.$setup42_searchpagemap_zoom.'" setup5_mapsettings_zoom_mobile="'.$setup42_searchpagemap_mobile.'" setup5_mapsettings_autofit="1" setup5_mapsettings_autofitsearch="1" setup5_mapsettings_type="'.$setup42_searchpagemap_type.'" mapsearch_status="1" mapnot_status="1" setup5_mapsettings_lat="'.$setup42_searchpagemap_lat.'" setup5_mapsettings_lng="'.$setup42_searchpagemap_lng.'" setup5_mapsettings_style="'.$setup42_searchpagemap_style.'" setup7_geolocation_status="0" listingtype="" itemtype="" conditions="" features ="" locationtype=""  termname="-" csauto="" neaddress="'.$pfpointfinder_google_search_coord.'" ne="'.$pfne.'" ne2="'.$pfne2.'" sw="'.$pfsw.'" sw2="'.$pfsw2.'"]');
		}
	}

	private function pointfinder_check_tag($tag_id){
		$customquery = new WP_Query( array( "post_type" => $this->post_type_name, "tag_id" => $tag_id ) );
		wp_reset_postdata();
		if ($customquery->found_posts > 0){
			return true;
		}else{
			return false;
		}
	}

	public function pointfinder_category_page_func(){
		global $wp_query;

		$pf_category = 0;
		$get_termname = $get_term_nameforlink = $filter_text = $tag_id = '';
		$pf_mapheader_arr = array('pointfinderltypes'=>'','pointfinderitypes'=>'','pointfinderlocations'=>'','pointfinderfeatures'=>'','pointfinderconditions'=>'',);

		if (is_tag()) {

			global $post_type;
		
			$pf_category = 1;
			

			if(isset($wp_query->query_vars['tag_id'])){
				$tag_id = $wp_query->query_vars['tag_id'];
				

				$tag_status = $this->pointfinder_check_tag($tag_id);

				if ($tag_status) {	
					$term_slug = $wp_query->query_vars['tag'];
					$pf_category = 1;
					$get_termname = (isset($wp_query->queried_object->name))?$wp_query->queried_object->name:'-';
					$term_name = get_term_by('slug', $term_slug, 'post_tag','ARRAY_A');
					$get_term_nameforlink = '<a href="'.get_term_link( $wp_query->query_vars['tag_id'], 'post_tag' ).'" title="' . esc_attr( sprintf( esc_html__( "View all posts in %s","pointfinder" ), $get_termname) ) . '">'.$get_termname.'</a>';

					$filter_text = 'tag = "'.$tag_id.'"';
				}
			}
		}else{

		    
			if(isset($wp_query->query_vars['taxonomy'])){
				$taxonomy_name = $wp_query->query_vars['taxonomy'];
				if (in_array($taxonomy_name, array('pointfinderltypes','pointfinderitypes','pointfinderconditions','pointfinderlocations','pointfinderfeatures'))) {
					
					$term_slug = $wp_query->query_vars['term'];
					$pf_category = 1;
					$term_name = get_term_by('slug', $term_slug, $taxonomy_name,'ARRAY_A');
					
					$get_termname = $term_name['name'];
					$get_term_nameforlink = '<a href="'.get_term_link( $term_name['term_id'], $taxonomy_name ).'" title="' . esc_attr( sprintf( esc_html__( "View all posts in %s","pointfindercoreelements" ), $term_name['name']) ) . '">'.$term_name['name'].'</a>';

					if (!empty($term_name['parent'])) {
						$term_parent_name = get_term_by('id', $term_name['parent'], $taxonomy_name,'ARRAY_A');
						$get_termname = $term_parent_name['name'].' / '.$term_name['name'];
						$get_term_nameforlink = '<a href="'.get_term_link( $term_name['parent'], $taxonomy_name ).'" title="' . esc_attr( sprintf( esc_html__( "View all posts in %s","pointfindercoreelements" ), $term_parent_name['name']) ) . '">'.$term_parent_name['name'].'</a> / '.'<a href="'.get_term_link( $term_name['term_id'], $taxonomy_name ).'" title="' . esc_attr( sprintf( esc_html__( "View all posts in %s","pointfindercoreelements" ), $term_name['name']) ) . '">'.$term_name['name'].'</a>';
					}

					

					switch ($taxonomy_name) {
						case 'pointfinderltypes':
							$filter_text .= 'listingtype = "'.$term_name['term_id'].'"';
							$pf_mapheader_arr['pointfinderltypes'] = $term_name['term_id'];
							break;
						
						case 'pointfinderitypes':
							$filter_text .= 'itemtype = "'.$term_name['term_id'].'"';
							$pf_mapheader_arr['pointfinderitypes'] = $term_name['term_id'];
							break;

						case 'pointfinderlocations':
							$filter_text .= 'locationtype = "'.$term_name['term_id'].'"';
							$pf_mapheader_arr['pointfinderlocations'] = $term_name['term_id'];
							break;

						case 'pointfinderfeatures':
							$filter_text .= 'features = "'.$term_name['term_id'].'"';
							$pf_mapheader_arr['pointfinderfeatures'] = $term_name['term_id'];
							break;

						case 'pointfinderconditions':
							$filter_text .= 'conditions = "'.$term_name['term_id'].'"';
							$pf_mapheader_arr['pointfinderconditions'] = $term_name['term_id'];
							break;
					}

				}
			}
		}
		
			
		
		
		$setup22_searchresults_defaultppptype = $this->PFSAIssetControl('setup22_searchresults_defaultppptype','','10');
		$setup22_searchresults_defaultsorttype = $this->PFSAIssetControl('setup22_searchresults_defaultsorttype','','ASC');
		$setup22_searchresults_defaultsortbytype = $this->PFSAIssetControl('setup22_searchresults_defaultsortbytype','','ID');

		if ($pf_category == 0) {
			$setup_item_blogcatpage_sidebarpos = $this->PFASSIssetControl('setup_item_blogcatpage_sidebarpos','','2');
			if(function_exists('PFGetDefaultPageHeader')){PFGetDefaultPageHeader();}
			echo '<div class="pf-blogpage-spacing pfb-top"></div>';
			echo '<section role="main">';
				echo '<div class="pf-container">';
					echo '<div class="pf-row">';
						if ($setup_item_blogcatpage_sidebarpos == 3) {
			        		echo '<div class="col-lg-12">';

								get_template_part('loop');

							echo '</div>';
			        	}else{
			        	
				            if($setup_item_blogcatpage_sidebarpos == 1){
				                echo '<div class="col-lg-3 col-md-4">';
				                    if (is_active_sidebar( 'pointfinder-blogcatpages-area' )) {

				                    	get_sidebar('catblog' );
				                    } else {
				                    	get_sidebar();
				                    }
				                    
				                echo '</div>';
				            }
				              
				            echo '<div class="col-lg-9 col-md-8">'; 
				            
				            get_template_part('loop');

				            echo '</div>';
				            if($setup_item_blogcatpage_sidebarpos == 2){
				                echo '<div class="col-lg-3 col-md-4">';
				                    if (is_active_sidebar( 'pointfinder-blogcatpages-area' )) {
				                    	get_sidebar('catblog' );
				                    } else {
				                    	get_sidebar();
				                    }
				                echo '</div>';
				            }

			            }
					echo '</div>';
				echo '</div>';
			echo '</section>';
			echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';

		}else{
			$general_ct_page_layout = $this->PFSAIssetControl('general_ct_page_layout','','1');

	        if ($general_ct_page_layout == 1) {

	        	$pointfinderltypesas_vars = get_option('pointfinderltypesas_vars');
	       		$pf_cat_imagebg = (isset($pointfinderltypesas_vars[$term_name['term_id']]['pf_cat_imagebg']))? $pointfinderltypesas_vars[$term_name['term_id']]['pf_cat_imagebg']: 2;
		        
		        if ($pf_cat_imagebg == 1) {
		        	if(function_exists('PFGetDefaultCatPageHeader')){
		        		PFGetDefaultCatPageHeader(
		        			array(
		        				'taxname' => $get_termname,
		        				'taxnamebr' => $get_term_nameforlink,
		        				'taxinfo'=>$term_name['description'],
		        				'pf_cat_textcolor' => (isset($pointfinderltypesas_vars[$term_name['term_id']]['pf_cat_textcolor']))?$pointfinderltypesas_vars[$term_name['term_id']]['pf_cat_textcolor']:'',
		        				'pf_cat_backcolor' => (isset($pointfinderltypesas_vars[$term_name['term_id']]['pf_cat_backcolor']))?$pointfinderltypesas_vars[$term_name['term_id']]['pf_cat_backcolor']:'',
		        				'pf_cat_bgimg' => (isset($pointfinderltypesas_vars[$term_name['term_id']]['pf_cat_bgimg']))?$pointfinderltypesas_vars[$term_name['term_id']]['pf_cat_bgimg']:'',
		        				'pf_cat_bgrepeat' => (isset($pointfinderltypesas_vars[$term_name['term_id']]['pf_cat_bgrepeat']))?$pointfinderltypesas_vars[$term_name['term_id']]['pf_cat_bgrepeat']:'',
		        				'pf_cat_bgsize' => (isset($pointfinderltypesas_vars[$term_name['term_id']]['pf_cat_bgsize']))?$pointfinderltypesas_vars[$term_name['term_id']]['pf_cat_bgsize']:'',
		        				'pf_cat_bgpos' => (isset($pointfinderltypesas_vars[$term_name['term_id']]['pf_cat_bgpos']))?$pointfinderltypesas_vars[$term_name['term_id']]['pf_cat_bgpos']:'',
		        				'pf_cat_headerheight' => (isset($pointfinderltypesas_vars[$term_name['term_id']]['pf_cat_headerheight']))?$pointfinderltypesas_vars[$term_name['term_id']]['pf_cat_headerheight']:'',
		        			)
		        		);
		        	}
		        }elseif ($pf_cat_imagebg == 2) {

		        	if(function_exists('PFGetDefaultPageHeader')){
		        		PFGetDefaultPageHeader(
		        			array(
		        				'taxname' => $get_termname,
		        				'taxnamebr' => $get_term_nameforlink,
		        				'taxinfo'=>$term_name['description']
		        			)
		        		);
		        	}
		        }else{
		        	echo '<div class="pfnoheaderopt"></div>';
		        }
		    }

	    	$setup42_searchpagemap_lat = $this->PFSAIssetControl('setup42_searchpagemap_lat','','');
			$setup42_searchpagemap_lng = $this->PFSAIssetControl('setup42_searchpagemap_lng','','');
			$setup42_searchpagemap_zoom = $this->PFSAIssetControl('setup42_searchpagemap_zoom','','12');
			$setup42_searchpagemap_mobile = $this->PFSAIssetControl('setup42_searchpagemap_mobile','','10');

			if(isset($_GET['pfsearch-filter']) && $_GET['pfsearch-filter']!=''){$pfg_orderby = esc_attr($_GET['pfsearch-filter']);}else{$pfg_orderby = $setup22_searchresults_defaultsortbytype;}
			if(isset($_GET['pfsearch-filter-order']) && $_GET['pfsearch-filter-order']!=''){$pfg_order = esc_attr($_GET['pfsearch-filter-order']);}else{$pfg_order = $setup22_searchresults_defaultsorttype;}
			if(isset($_GET['pfsearch-filter-number']) && $_GET['pfsearch-filter-number']!=''){$pfg_number = esc_attr($_GET['pfsearch-filter-number']);}else{$pfg_number = "-1";}
		    	
			

			if($general_ct_page_layout == 1){
				echo do_shortcode('[pf_directory_half_map orderby="'.$setup22_searchresults_defaultsortbytype.'" sortby="'.$setup22_searchresults_defaultsorttype.'" setup5_mapsettings_zoom="'.$setup42_searchpagemap_zoom.'" setup5_mapsettings_zoom_mobile="'.$setup42_searchpagemap_mobile.'" setup5_mapsettings_autofit="1" setup5_mapsettings_autofitsearch="1" mapsearch_status="1" mapnot_status="1" setup5_mapsettings_lat="'.$setup42_searchpagemap_lat.'" setup5_mapsettings_lng="'.$setup42_searchpagemap_lng.'" setup7_geolocation_status="0" listingtype="'.$pf_mapheader_arr['pointfinderltypes'].'" itemtype="'.$pf_mapheader_arr['pointfinderitypes'].'" conditions="'.$pf_mapheader_arr['pointfinderconditions'].'" features ="'.$pf_mapheader_arr['pointfinderfeatures'].'" locationtype="'.$pf_mapheader_arr['pointfinderlocations'].'" tag="'.$tag_id.'"  termname="'.$get_termname.'" csauto="'.$term_name['term_id'].'" locofrequest="topmap"]');
			}elseif ($general_ct_page_layout == 3) {
				echo do_shortcode('[pf_directory_half_map orderby="'.$setup22_searchresults_defaultsortbytype.'" sortby="'.$setup22_searchresults_defaultsorttype.'" setup5_mapsettings_zoom="'.$setup42_searchpagemap_zoom.'" setup5_mapsettings_zoom_mobile="'.$setup42_searchpagemap_mobile.'" setup5_mapsettings_autofit="1" setup5_mapsettings_autofitsearch="1" mapsearch_status="1" mapnot_status="1" setup5_mapsettings_lat="'.$setup42_searchpagemap_lat.'" setup5_mapsettings_lng="'.$setup42_searchpagemap_lng.'" setup7_geolocation_status="0" listingtype="'.$pf_mapheader_arr['pointfinderltypes'].'" itemtype="'.$pf_mapheader_arr['pointfinderitypes'].'" conditions="'.$pf_mapheader_arr['pointfinderconditions'].'" features ="'.$pf_mapheader_arr['pointfinderfeatures'].'" locationtype="'.$pf_mapheader_arr['pointfinderlocations'].'" tag="'.$tag_id.'"  termname="'.$get_termname.'" csauto="'.$term_name['term_id'].'"]');
			}elseif($general_ct_page_layout == 2){
				echo do_shortcode('[pf_directory_half_map orderby="'.$setup22_searchresults_defaultsortbytype.'" sortby="'.$setup22_searchresults_defaultsorttype.'" setup5_mapsettings_zoom="'.$setup42_searchpagemap_zoom.'" setup5_mapsettings_zoom_mobile="'.$setup42_searchpagemap_mobile.'" setup5_mapsettings_autofit="1" setup5_mapsettings_autofitsearch="1" mapsearch_status="1" mapnot_status="1" setup5_mapsettings_lat="'.$setup42_searchpagemap_lat.'" setup5_mapsettings_lng="'.$setup42_searchpagemap_lng.'" setup7_geolocation_status="0" listingtype="'.$pf_mapheader_arr['pointfinderltypes'].'" itemtype="'.$pf_mapheader_arr['pointfinderitypes'].'" conditions="'.$pf_mapheader_arr['pointfinderconditions'].'" features ="'.$pf_mapheader_arr['pointfinderfeatures'].'" locationtype="'.$pf_mapheader_arr['pointfinderlocations'].'" tag="'.$tag_id.'"  termname="'.$get_termname.'" csauto="'.$term_name['term_id'].'" locofrequest="topmap"]');
			}

		}
	}

	public function pointfinder_user_request_action_email_subject($subject, $sitename, $email_data){
		return esc_html__("Account Data Removal Request Confirmation","pointfindercoreelements");
	}
	public function pointfinder_user_erasure_complete_email_subject($subject, $sitename, $email_data){
		return esc_html__("Account Data Removal Request Confirmation","pointfindercoreelements");
	}
	
	public function pointfinder_user_request_action_email_content($email_text, $email_data){
		$setup33_emailsettings_mailtype = $this->PFMSIssetControl('setup33_emailsettings_mailtype','','1');
	    if( $setup33_emailsettings_mailtype == 1 ) {
	        $email_text = $this->pointfinder_mailsystem_template_html(wpautop($email_text),esc_html__("Account Data Removal Request Confirmation","pointfindercoreelements"));
	    }
	    return $email_text;
	}

	public function pointfinder_user_confirmed_action_email_content($email_text, $email_data){

		$setup33_emailsettings_mailtype = $this->PFMSIssetControl('setup33_emailsettings_mailtype','','1');
	    if( $setup33_emailsettings_mailtype == 1 ) {
	        $email_text = $this->pointfinder_mailsystem_template_html(wpautop($email_text),esc_html__("Account Data Removal Request Completed","pointfindercoreelements"));
	    }

	    return $email_text;

	}


	public function pointfinder_gallery_post_action_func(){

		if ( has_shortcode( get_the_content(), 'gallery' ) ) {

	        $gallery = get_post_gallery(get_the_id(),false);
	        if (isset($gallery['ids'])) {
	            $gallery_ids = explode(',', $gallery['ids']);

	            if (is_array($gallery_ids)) {

	            $gridrandno_orj = PF_generate_random_string_ig();
	            echo '<div class="vc-image-carousel">';
	            echo '<ul id="'.$gridrandno_orj.'" class="pf-gallery-slider">';

	                foreach ($gallery_ids as $gallery_id) {

	                    $large_image_url = wp_get_attachment_image_src( $gallery_id, 'full' );
	                    echo '<li><img src="'.$large_image_url[0].'" /></li>';

	                }

	            echo '</ul>';
	            echo '</div>';
	       
	           	$script_output = 'jQuery(function() {
	                    jQuery("#'.$gridrandno_orj.'").owlCarousel({items : 1,navigation : true,paginationNumbers : false,pagination : false,autoPlay : false,slideSpeed:7000,mouseDrag:true,touchDrag:true,itemSpaceWidth: 10,autoHeight : false,responsive:true,transitionStyle: "fade", itemsScaleUp : false,navigationText:false,theme:"owl-theme",singleItem : true,itemsCustom : true,itemsDesktop : [1199,1],itemsDesktopSmall : [980,1],itemsTablet: [768,1],itemsTabletSmall: false,itemsMobile : [479,1]});
	                });
	            ';
	       		wp_add_inline_script( 'pftheme-customjs', $script_output );
	       
	            }
	        }

	    }

		remove_shortcode('gallery');
	    $output = do_shortcode(get_the_content('' . esc_html__('Read more', 'pointfindercoreelements') .''));
	    $output = preg_replace('/\[gallery(.*?)\]/', '', $output);
	    $output = apply_filters('convert_chars', $output);
	    $output = apply_filters('the_content', $output);
	    echo $output;
	}

	public function pointfinder_accept_language_filter_func(){
		return (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))?$_SERVER['HTTP_ACCEPT_LANGUAGE']:'';
	}


	public function pointfinder_geo_posts_join($join, $query) {
            
        if (!empty($query->get('pf_sw'))) {
            global $wpdb;

            $join .= " INNER JOIN ".$wpdb->postmeta." AS latitude ON ".$wpdb->posts.".ID = latitude.post_id ";
            $join .= " INNER JOIN ".$wpdb->postmeta." AS longitude ON ".$wpdb->posts.".ID = longitude.post_id ";
        }

        return $join;
    }


    public function pointfinder_geo_posts_where($where, $query) {
        if ( !empty($query->get('pf_sw')) ) {
            $where .= "AND (latitude.meta_key='webbupointfinder_items_location' AND SUBSTRING_INDEX(latitude.meta_value,',',1) BETWEEN ".$query->get('pf_sw')." AND ".$query->get('pf_ne').")";
             $where .= "AND (longitude.meta_key='webbupointfinder_items_location' AND SUBSTRING_INDEX(SUBSTRING_INDEX(longitude.meta_value,',',2),',',-1) BETWEEN ".$query->get('pf_sw2')." AND ".$query->get('pf_ne2').")";
        }
        return $where;
    }


    public function pointfinder_geojs_posts_join( $sql, $query ) {
       
        $geo_query = $query->get('geo_query');
        if( $geo_query ) {
        	global $wpdb;
            if( $sql ) {
                $sql .= ' ';
            }
            $sql .= "INNER JOIN " . $wpdb->prefix . "postmeta AS geo_query_lat ON ( " . $wpdb->prefix . "posts.ID = geo_query_lat.post_id ) ";
            $sql .= "INNER JOIN " . $wpdb->prefix . "postmeta AS geo_query_lng ON ( " . $wpdb->prefix . "posts.ID = geo_query_lng.post_id ) ";
        }
        return $sql;
    }

    public function pointfinder_geojs_posts_fields( $sql, $query ) {
        
        $geo_query = $query->get('geo_query');
        if( $geo_query ) {
        	global $wpdb;
            if( $sql ) {
                $sql .= ', ';
            }

            $sql .= $this->pointfinder_geojs_haversine_term( $geo_query ) . " AS geo_query_distance";
            
        }
        return $sql;
    }

    public function pointfinder_geojs_posts_where( $sql, $query ) {
        
        $geo_query = $query->get('geo_query');
        if( $geo_query ) {
        	global $wpdb;
            $lat_field = 'latitude';
            if( !empty( $geo_query['lat_field'] ) ) {
                $lat_field =  $geo_query['lat_field'];
            }
            $lng_field = 'longitude';
            if( !empty( $geo_query['lng_field'] ) ) {
                $lng_field =  $geo_query['lng_field'];
            }
            $distance = 20;
            if( isset( $geo_query['distance'] ) ) {
                $distance = $geo_query['distance'];
            }
            if( $sql ) {
                $sql .= " AND ";
            }
            $haversine = $this->pointfinder_geojs_haversine_term( $geo_query );
            $new_sql = "( geo_query_lat.meta_key = %s AND geo_query_lng.meta_key = %s AND " . $haversine . " <= %f )";
            $sql .= $wpdb->prepare( $new_sql, $lat_field, $lng_field, $distance );
        }
        return $sql;
    }

    public function pointfinder_geojs_posts_orderby( $sql, $query ) {
        
        $geo_query = $query->get('geo_query');
        if( $geo_query ) {
        	global $wpdb;
            $orderby = $query->get('orderby');
            $order   = $query->get('order');
            
            if( $orderby == 'distance' ) {
                if( !$order ) {
                    $order = 'ASC';
                }
                $sql = 'geo_query_distance ' . $order;
            }elseif(isset($orderby['distance'])){
                $order = 'ASC';
                if(isset($orderby['query_featuredor'])){
                    $sql = 'CAST('.$wpdb->postmeta.'.meta_value AS SIGNED) DESC, geo_query_distance ' . $order;
                }else{
                    $sql = 'geo_query_distance ' . $order;
                }
                
            }
            //CAST(wp_postmeta.meta_value AS SIGNED) DESC
        }
        return $sql;
    }

    private function pointfinder_geojs_haversine_term( $geo_query ) {
        global $wpdb;
        $units = "miles";
        if( !empty( $geo_query['units'] ) ) {
            $units = strtolower( $geo_query['units'] );
        }
        $radius = 3959;
        if( in_array( $units, array( 'km', 'kilometers' ) ) ) {
            $radius = 6371;
        }
        $lat_field = "geo_query_latval";
        $lng_field = "geo_query_lngval";
        $lat = 0;
        $lng = 0;
        if( isset( $geo_query['latitude'] ) ) {
            $lat = $geo_query['latitude' ];
        }
        if(  isset( $geo_query['longitude'] ) ) {
            $lng = $geo_query['longitude'];
        }
        $haversine  = "( " . $radius . " * ";
        $haversine .=     "acos( cos( radians(%f) ) * cos( radians( SUBSTRING_INDEX(geo_query_lat.meta_value,',',1) ) ) * ";
        $haversine .=     "cos( radians( SUBSTRING_INDEX(SUBSTRING_INDEX(geo_query_lng.meta_value,',',2),',',-1) ) - radians(%f) ) + ";
        $haversine .=     "sin( radians(%f) ) * sin( radians( SUBSTRING_INDEX(geo_query_lat.meta_value,',',1) ) ) ) ";
        $haversine .= ")";
        $haversine  = $wpdb->prepare( $haversine, array( $lat, $lng, $lat ) );
        return $haversine;
    }


    public function pointfinder_openhours_posts_join($join, $query) {
            
        if (!empty($query->get('ohourshow'))) {
            global $wpdb;
            $join .= " INNER JOIN ".$wpdb->postmeta." AS openinghours ON ".$wpdb->posts.".ID = openinghours.post_id ";
        }

        return $join;
    }


    public function pointfinder_openhours_posts_where($where, $query) {
        if ( !empty($query->get('ohourshow')) ) {

            $where .= "AND (openinghours.meta_key='webbupointfinder_items_o_o".date_i18n('N')."' AND SUBSTRING_INDEX(openinghours.meta_value,',',1) <= TIME('".date_i18n('G:i').":00') AND CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(openinghours.meta_value,'-',2),'-',-1) AS time) >= TIME('".date_i18n('G:i').":00') )";
        }
        return $where;
    }

    

	public function pointfinder_frontendpm_menu_filter($menu){
    	
    	foreach ($menu as $singlemenu_key => $singlemenu_value) {
    		$menu[$singlemenu_key]['action'] = $singlemenu_value['action'].'&ua=mymessages';
    	}

    	return $menu;
    }


    public function pointfinder_check_search_widget($params){
		if (isset($params[0]['id'])) {
			if ($params[0]['id'] == 'pointfinder-itemcatpage-area' || $params[0]['id'] == 'pointfinder-itemsearchres-area') {
			  if (strpos($params[0]['widget_id'],'pf_search_items_w') !== false) {
			    $params[0] = array();
			    $params[1] = array();
			  }
			}
		}
		return $params;
    }

    public function pointfinder_footer_system(){
    	if (!is_page_template('pf-empty-page.php' ) && !is_page_template('terms-conditions.php' )) {
    		
    		global $post;
    		
        /*$post_id = get_the_ID();

			// Get the page settings manager
			$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

			// Get the settings model for current post
			$page_settings_model = $page_settings_manager->get_model( $post_id );

			// Retrieve the color we added before
			$menu_item_color = $page_settings_model->get_settings( 'fullmenu' );

			var_dump($menu_item_color);
			var_dump($page_settings_manager);
			var_dump($page_settings_model);
			wp_die();*/
            ?>
           
            <div id="pf-membersystem-dialog"></div>
			<div id="iyzipay-checkout-form" class="popup"></div>
            <div class="pf-mobile-up-button">
	            <?php 
	            $setup4_membersettings_loginregister = $this->PFSAIssetControl('setup4_membersettings_loginregister','','0');
	            if ($setup4_membersettings_loginregister == 1) {
	            ?>
	            	<a title="<?php echo esc_html__('User Menu','pointfindercoreelements'); ?>" class="pf-up-but pf-up-but-umenu"><i class="fas fa-user"></i></a>
	            <?php 
	            }
	            ?>
	            <a title="<?php echo esc_html__('Menu','pointfindercoreelements'); ?>" class="pf-up-but pf-up-but-menu"><i class="fas fa-bars"></i></a>
	            <a title="<?php echo esc_html__('Back to Top','pointfindercoreelements'); ?>" class="pf-up-but pf-up-but-up"><i class="fas fa-angle-up"></i></a>
	            <a title="<?php echo esc_html__('Open','pointfindercoreelements'); ?>" class="pf-up-but pf-up-but-el"><i class="fas fa-ellipsis-h"></i></a>
            </div>
            <div class="pf-desktop-up-button">
                <a title="<?php echo esc_html__('Back to Top','pointfindercoreelements'); ?>" class="pf-up-but pf-up-but-up"><i class="fas fa-angle-up"></i></a>
            </div>
            <?php

            $processpage = true;

            if (is_search()) {
            	$setup42_searchpagemap_headeritem = $this->PFSAIssetControl('setup42_searchpagemap_headeritem','','1');
            	if ($setup42_searchpagemap_headeritem == 2) {
            		$processpage = false;
            	}
            }
            
            if ( class_exists( 'WooCommerce' ) ) {
                if ( (is_category() || is_tax() || is_archive()) && !is_woocommerce() ) {
                   $general_ct_page_layout = $this->PFSAIssetControl('general_ct_page_layout','','1');
                   if($general_ct_page_layout == 3){
                    $processpage = false;
                   }
                }
            } else {
                if (is_category() || is_tax() || is_archive()) {
                   $general_ct_page_layout = $this->PFSAIssetControl('general_ct_page_layout','','1');
                   if($general_ct_page_layout == 3){
                    $processpage = false;
                   }
                }
            }

            if ($processpage) {
	            /*
	            * Start: Footer Row option
	            */
	               
	                if (isset($post->ID)) {
	                    $webbupointfinder_gbf_status = get_post_meta( $post->ID, 'webbupointfinder_gbf_status', true );
	                    $pgfooterrow = $footerrow = $gbfooterrowstatus = $pgfooterrowstatus = false;
	                    $footer_text = $footer_ex_classes = $footer_ex_classes2 = '';
	                    $gbf_status = $this->PFASSIssetControl('gbf_status','',0);

	                    if ($gbf_status == 1 || !empty($webbupointfinder_gbf_status)) {

	                        $footer_row1 = $footer_row2 = $footer_row3 = $footer_row4 = '';

	                        if (!empty($webbupointfinder_gbf_status)) {

	                            $footer_cols = get_post_meta( $post->ID, 'webbupointfinder_gbf_cols', true );

	                            $footer_row[1] = get_post_meta( $post->ID, 'webbupointfinder_gbf_sidebar1', true );
	                            $footer_row[2] = get_post_meta( $post->ID, 'webbupointfinder_gbf_sidebar2', true );
	                            $footer_row[3] = get_post_meta( $post->ID, 'webbupointfinder_gbf_sidebar3', true );
	                            $footer_row[4] = get_post_meta( $post->ID, 'webbupointfinder_gbf_sidebar4', true );

	                            $gbfooterrowstatus = false;
	                            $pgfooterrowstatus = true;
	                            $pgfooterrow = true;

	                        }elseif (empty($webbupointfinder_gbf_status) && $gbf_status == 1) {

	                            $footer_cols = $this->PFASSIssetControl('gbf_cols','',4);

	                            $footer_row[1] = $this->PFASSIssetControl('gbf_sidebar1','','');
	                            $footer_row[2] = $this->PFASSIssetControl('gbf_sidebar2','','');
	                            $footer_row[3] = $this->PFASSIssetControl('gbf_sidebar3','','');
	                            $footer_row[4] = $this->PFASSIssetControl('gbf_sidebar4','','');

	                            $gbfooterrowstatus = true;
	                            $pgfooterrowstatus = false;
	                        }

	                        if (!$pgfooterrow ) {
	                            echo '<div class="wpf-footer-row-move">';
	                        }else{
	                            echo '<div class="wpf-footer-row-move wpf-footer-row-movepg">';
	                        }

	                        /* Start: Pointfinder Footer Row */
	                            if($pgfooterrow || $gbfooterrowstatus || $pgfooterrowstatus ){
	                                if ($footerrow) {
	                                    $footer_text = ' id="pf-footer-row"';
	                                    $footer_ex_classes = ' pointfinderexfooterclass';
	                                    $footer_ex_classes2 = ' pointfinderexfooterclassx';
	                                    $myoutput = '<script>
	                                    (function($) {
	                                    "use strict";
	                                    $(function(){
	                                        $(".pointfinderexfooterclass a").on("mouseenter mouseleave", function(){
	                                            $(this).css({ color: "'.$colorfortexth.'"});
	                                        }, function(){
	                                            $(this).css({ color: "'.$colorfortext.'"});
	                                        });
	                                        $(".pointfinderexfooterclass").css("color","'.$colorfortext.'!important");
	                                        $(".pointfinderexfooterclass a").css("color","'.$colorfortext.'!important");
	                                    });
	                                   
	                                    })(jQuery);
	                                    </script>';
	                                    $output .= $myoutput;
	                                }elseif ($pgfooterrowstatus) {
	                                    $footer_text = ' id="pf-footer-row"';
	                                    $footer_ex_classes = ' pointfinderexfooterclasspg';
	                                    $footer_ex_classes2 = ' pointfinderexfooterclassxpg';
	                                }elseif ($gbfooterrowstatus) {
	                                    $footer_text = ' id="pf-footer-row"';
	                                    $footer_ex_classes = ' pointfinderexfooterclassgb';
	                                    $footer_ex_classes2 = ' pointfinderexfooterclassxgb';
	                                }
	                            }
	                        /* End: Pointfinder Footer Row */

	                        	switch ($footer_cols) {
		                            case 4:$class_fn = 'wpb_column col-lg-3 col-md-3';break;
		                            case 3:$class_fn = 'wpb_column col-lg-4 col-md-4';break;
		                            case 2:$class_fn = 'wpb_column col-lg-6 col-md-6';break;
		                            case 1:$class_fn = 'wpb_column col-lg-12 col-md-12';break;
		                            default:$class_fn = 'wpb_column col-lg-3 col-md-3';break;
		                        }

		                        echo '<div class="vc_row wpb_row vc_row-fluid '.$footer_ex_classes2.'">';
		                        	echo '<div'.$footer_text.' class="pf-container'.$footer_ex_classes.'">';
		                        		echo '<div class="pf-row">';
				                       		for ($i = 1; $i <= $footer_cols; $i++) {
				                       			echo '<div class="'.$class_fn.'">
									                <div class="vc_column-inner">
									                    <div class="wpb_wrapper">
									                        <div class="wpb_widgetised_column wpb_content_element">
									                            <div class="wpb_wrapper">
																	';
																	if ( is_active_sidebar( $footer_row[$i] ) ){
																		dynamic_sidebar( $footer_row[$i] );
																	}
									                               echo '
									                            </div>
									                        </div>
									                    </div>
									                </div>
									            </div>';
				                       		}
				                        echo '</div>';
			                        echo '</div>';
		                        echo '</div>';
	                        echo '</div>';
	                    }else{
	                        echo '<div class="wpf-footer-row-move"></div>';
	                    }
	                }

	            /*
	            * End: Footer Row option
	            */
            }
            ?>
            <?php
            $setup_footerbar_status = $this->PFSAIssetControl('setup_footerbar_status','','1');
            if ($setup_footerbar_status == 1 && $processpage) {
            ?>
            	<footer class="wpf-footer">
	            <?php
	            $setup_footerbar_text_copy = $this->PFSAIssetControl('setup_footerbar_text_copy','','');
	            $setup_footerbar_width = $this->PFSAIssetControl('setup_footerbar_width','','0');


	            if ($setup_footerbar_width == 0) {
	              echo '<div class="pf-container"><div class="pf-row clearfix">';
	            }
	            ?>
	            <div class="wpf-footer-text col-lg-12">
	              <?php echo wp_kses_post($setup_footerbar_text_copy);?>

	            </div>
	            <?php
	            if ($this->PFSAIssetControl('setup_footerbar_text_copy_align','','left') == 'right') {
	               $footer_menu_text = ' pfleftside';
	            }else{
	               $footer_menu_text = ' pfrightside';
	            }
	            echo '<ul class="pf-footer-menu'.$footer_menu_text.'">';pointfinder_footer_navigation_menu();echo '</ul>';?>
	            <?php
	            if ($setup_footerbar_width == 0) {
	              echo '</div></div>';
	            }
	            ?>
	            </footer>
	            <?php
            }
        }
    }
}
