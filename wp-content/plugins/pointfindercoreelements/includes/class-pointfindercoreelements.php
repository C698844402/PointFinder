<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://themeforest.net/user/webbu
 * @since      1.0.0
 *
 * @package    Pointfindercoreelements
 * @subpackage Pointfindercoreelements/includes
 */


class Pointfindercoreelements {
	use PointFinderOptionFunctions;

	protected $loader;
	protected $plugin_name;
	protected $version;

	private $post_type_name;
	private $agent_post_type_name;

	public function __construct() {

		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		$this->plugin_name = 'pointfindercoreelements';

		$this->post_type_name = $this->PointFinderGetPostTypeName();
		$this->agent_post_type_name = $this->PointFinderGetAgentPostTypeName();

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_ajax_hooks();

		if(class_exists('SitePress')) {
		    define('ICL_DONT_LOAD_NAVIGATION_CSS', true);
		    define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
		    define('ICL_DONT_LOAD_LANGUAGES_JS', true);
	    }
	}

	private function PointFinderGetAgentPostTypeName(){
    	return $this->PFSAIssetControl("setup3_pointposttype_pt8","","agents");
  	}

	private function PointFinderGetPostTypeName(){
    	return $this->PFSAIssetControl("setup3_pointposttype_pt1","","pfitemfinder");
  	}

	private function load_dependencies() {

		require_once PFCOREELEMENTSDIR . 'includes/traits/wpml.php';
		require_once PFCOREELEMENTSDIR . 'includes/traits/functions-common.php';
		require_once PFCOREELEMENTSDIR . 'includes/traits/functions-gridspecific.php';
		require_once PFCOREELEMENTSDIR . 'includes/traits/functions-common-user.php';

		require_once PFCOREELEMENTSDIR . 'admin/fields/pfsetcustomfields.php';
		require_once PFCOREELEMENTSDIR . 'admin/fields/pfsetcustompoints.php';
		require_once PFCOREELEMENTSDIR . 'admin/fields/pfsetsearchfields.php';

		require_once PFCOREELEMENTSDIR . 'admin/fields/pfgetcustomfields.php';
		require_once PFCOREELEMENTSDIR . 'admin/fields/pfgetsearchfields.php';
		require_once PFCOREELEMENTSDIR . 'admin/fields/pfgetsubsearchfields.php';

		require_once PFCOREELEMENTSDIR . 'admin/options/reduxmetabox.php';

		require_once PFCOREELEMENTSDIR . 'includes/traits/posttypename.php';
		
		require_once PFCOREELEMENTSDIR . 'includes/traits/functions-review.php';
		require_once PFCOREELEMENTSDIR . 'includes/traits/dashboard-functions.php';
		require_once PFCOREELEMENTSDIR . 'includes/traits/agent-functions.php';
		require_once PFCOREELEMENTSDIR . 'includes/traits/mail-system.php';

		require_once( PFCOREELEMENTSDIR . 'admin/options/loader.php');

		
		if (class_exists("Redux")) {

			$pointfinder_v2_update3 = get_option( 'pointfinder_v2_update_3');

			require_once( PFCOREELEMENTSDIR . 'admin/options/PointfinderOptions.config.php' );
			require_once( PFCOREELEMENTSDIR . 'admin/options/PointfinderOptionsFMB.config.php' );
			require_once( PFCOREELEMENTSDIR . 'admin/options/PFASControl.config.php' );
			if ($pointfinder_v2_update3 != 1) {
				require_once( PFCOREELEMENTSDIR . 'admin/options/PFPBControl.config.php' );
			}
			require_once( PFCOREELEMENTSDIR . 'admin/options/CustomFields.config.php' );
			require_once( PFCOREELEMENTSDIR . 'admin/options/SearchFields.config.php' );
			require_once( PFCOREELEMENTSDIR . 'admin/options/MailSystem.config.php' );
			require_once( PFCOREELEMENTSDIR . 'admin/options/SidebarGenerator.config.php' );

			
			if ($pointfinder_v2_update3 != 1) {
				require_once( PFCOREELEMENTSDIR . 'admin/options/TwitterWidget.config.backup.php' );
			}
			
			
			require_once( PFCOREELEMENTSDIR . 'admin/options/ReviewSystem.config.php' );
			require_once( PFCOREELEMENTSDIR . 'admin/options/PFSizeControl.config.php' );
			require_once( PFCOREELEMENTSDIR . 'admin/options/PFPGControl.config.php' );

		}
		

		

		/* Backend Filters */
		require_once PFCOREELEMENTSDIR . 'admin/traits/admin-dashboard.php';
		require_once PFCOREELEMENTSDIR . 'admin/traits/listing-column-filters.php';
		require_once PFCOREELEMENTSDIR . 'admin/traits/listing-review-metabox.php';
		require_once PFCOREELEMENTSDIR . 'admin/traits/membership-packages.php';
		require_once PFCOREELEMENTSDIR . 'admin/traits/ppp-packages.php';
		require_once PFCOREELEMENTSDIR . 'admin/traits/order-metabox.php';
		require_once PFCOREELEMENTSDIR . 'admin/traits/morder-metabox.php';
		require_once PFCOREELEMENTSDIR . 'admin/traits/functions-statuschange.php';
		require_once PFCOREELEMENTSDIR . 'admin/traits/listing-metabox.php';
		require_once PFCOREELEMENTSDIR . 'admin/traits/invoices-metabox.php';
		require_once PFCOREELEMENTSDIR . 'admin/traits/schedule-functions.php';
		require_once PFCOREELEMENTSDIR . 'admin/traits/user-profile.php';
		require_once PFCOREELEMENTSDIR . 'admin/traits/admin-new-version.php';
		require_once PFCOREELEMENTSDIR . 'admin/traits/listing-type-connection.php';
		require_once PFCOREELEMENTSDIR . 'admin/traits/wpml-string-generator.php';
		

		/* Frontend Filters*/
		require_once PFCOREELEMENTSDIR . 'public/traits/listing-contents.php';
		require_once PFCOREELEMENTSDIR . 'public/traits/page-not-found.php';
		require_once PFCOREELEMENTSDIR . 'public/traits/listing-breadcrumbs.php';
		require_once PFCOREELEMENTSDIR . 'public/traits/listing-sharebar.php';
		require_once PFCOREELEMENTSDIR . 'public/traits/listing-review.php';
		require_once PFCOREELEMENTSDIR . 'public/traits/listing-comments.php';
		require_once PFCOREELEMENTSDIR . 'public/traits/listing-columns.php';
		require_once PFCOREELEMENTSDIR . 'public/traits/megamenu.php';
		require_once PFCOREELEMENTSDIR . 'public/traits/general-css.php';
		require_once PFCOREELEMENTSDIR . 'public/traits/ipnlistener.php';
		require_once PFCOREELEMENTSDIR . 'public/traits/social-login.php';
		require_once PFCOREELEMENTSDIR . 'public/traits/mail-functions.php';
		require_once PFCOREELEMENTSDIR . 'public/traits/authorpage-functions.php';
		require_once PFCOREELEMENTSDIR . 'public/traits/invoice-function.php';
		require_once PFCOREELEMENTSDIR . 'public/traits/dashboard-frontend-functions.php';
		require_once PFCOREELEMENTSDIR . 'public/traits/dashboard-frontend-header.php';
		require_once PFCOREELEMENTSDIR . 'public/traits/dashboard-frontend-footer.php';


		require_once PFCOREELEMENTSDIR . 'includes/Mobile_Detect.php';
		require_once PFCOREELEMENTSDIR . 'includes/meta-box/meta-box.php';
		require_once PFCOREELEMENTSDIR . 'includes/aq_resizer.php';
		require_once PFCOREELEMENTSDIR . 'includes/pfwidgets.php';
		require_once PFCOREELEMENTSDIR . 'includes/navmenu-walker.php';
		require_once PFCOREELEMENTSDIR . 'includes/megamenu.php';
		require_once PFCOREELEMENTSDIR . 'includes/query-builder.php';
		require_once PFCOREELEMENTSDIR . 'includes/taxonomymeta/taxonomy-meta.php';
		
		
		/* Payment System Fallbacks */
		$pags_status = $this->PFPGIssetControl("pags_status","",0);
		$ideal_status = $this->PFPGIssetControl("ideal_status","",0);
		$iyzico_status = $this->PFPGIssetControl("iyzico_status","",0);

		$setup20_stripesettings_status = $this->PFSAIssetControl("setup20_stripesettings_status","",0);

		if ($iyzico_status == 1) {
			require_once PFCOREELEMENTSDIR . 'includes/IyzipayBootstrap.php';
		}
		if ($pags_status == 1) {
			require_once PFCOREELEMENTSDIR . 'includes/PagSeguroLibrary/PagSeguroLibrary.php';
		}
		if ($setup20_stripesettings_status == 1) {
			require_once PFCOREELEMENTSDIR . 'includes/stripe/init.php';
		}
		if ($ideal_status == 1) {
			require_once PFCOREELEMENTSDIR . 'includes/Mollie/API/Autoloader.php';
		}
		$paypal_status = $this->PFSAIssetControl("setup4_membersettings_frontend","",0);
		if($paypal_status == 1){
			require_once PFCOREELEMENTSDIR . 'includes/paypall-class.php';
		}


		/* Social Media Includes */
		$twitter_login_check = $this->PFASSIssetControl("setup4_membersettings_twitterlogin","",0);
		if ($twitter_login_check == 1) {
			require_once PFCOREELEMENTSDIR . 'includes/Twitter/twitteroauth.php';
		}
		$facebook_login_status = $this->PFASSIssetControl("setup4_membersettings_facebooklogin","",0);
		if ($facebook_login_status == 1) {
			require_once PFCOREELEMENTSDIR . 'includes/Facebook/autoload.php';
		}

		$recaptcha_status = $this->PFSAIssetControl("recaptchast","",0);

		if ($recaptcha_status == 1) {
			require_once( PFCOREELEMENTSDIR .'includes/recaptchalib.php');
		}

		if (function_exists('vc_set_shortcodes_templates_dir')) {
			$dir = PFCOREELEMENTSDIR . 'includes/vc_templates/';
			vc_set_shortcodes_templates_dir($dir);
			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/custom-params.php';

			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/traits/grid-common-functions.php';

			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/pf-list-agents.php';
			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/pf_text_separator.php';
			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/pf_pfitem_carousel.php';
			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/pf-grid-shortcodes.php';
			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/pf-map-shortcodes.php';
			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/pf-half-map-shortcodes.php';
			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/pf-cmap-shortcodes.php';
			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/pf-cform-shortcodes.php';
			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/pf-directorylist.php';
			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/pf-locationlist.php';
			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/pf-itemslider-shortcodes.php';
			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/pf-search.php';
			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/vc_client_carousel.php';
			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/vc_testimonials.php';
			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/vc_pfinfobox.php';
			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/pf-tiled-listing-type.php';
			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/pf-tiled-locations.php';
			require_once PFCOREELEMENTSDIR . 'includes/customshortcodes/pf-icon.php';
		}


		/* REST API */
		//require_once WEMDLXPLUGIN_PATH . 'includes/rest-api/pflistingtype_customlist.php';
		//require_once WEMDLXPLUGIN_PATH . 'includes/rest-api/mxcategory_customlist.php';
		//require_once WEMDLXPLUGIN_PATH . 'includes/rest-api/mxreta_customlist.php';
		//require_once PFCOREELEMENTSDIR . 'includes/elementor/grid-common-functions.php';
		//require_once PFCOREELEMENTSDIR . 'includes/elementor/pointfinder-elementor.php';

		/*
			add_action( 'elementor/frontend/widget/before_render', function ( \Elementor\Element_Base $element ) {
 
			  $widget_name = $element->get_name();
			  
			  if (strpos($widget_name,'wp-widget-') == 0) {
			    $element->add_render_attribute( '_wrapper', ['class' => 'widget_'.str_replace('wp-widget-','',$widget_name)]);
			  }
			});
		*/


		
		require_once PFCOREELEMENTSDIR . 'includes/class-pointfindercoreelements-loader.php';
		require_once PFCOREELEMENTSDIR . 'includes/class-pointfindercoreelements-i18n.php';
		require_once PFCOREELEMENTSDIR . 'admin/class-pointfindercoreelements-admin.php';
		require_once PFCOREELEMENTSDIR . 'public/class-pointfindercoreelements-public.php';


		require_once PFCOREELEMENTSDIR . 'ajax/class-pointfindercoreelements-ajax.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/itemcountsys.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/grab-tweets.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/listing-type-limits.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/backend-nagsystem.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/user-system.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/user-system-handler.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/onoff-system.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/taxpoint.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/searchlistings.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/review-flag.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/listing-report.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/posttag.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/poidata.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/payment-system.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/owner-change.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/modalsys-handler.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/modalsys.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/membershipsys.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/membershipsys-payment.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/listing-types.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/listing-paymentsys.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/listdata.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/itemsys.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/infowindow.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/image-upload.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/imagesys.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/user-change.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/file-upload.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/filesys.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/featuresys.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/features-filter.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/favoritesys.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/claimsys.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/autocomplete.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/autocompletesa.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/quickinstall.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/currencychange.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/accremovesys.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/pfgeocoding.php';
		require_once PFCOREELEMENTSDIR . 'ajax/traits/pfgeocodingx.php';

		require_once PFCOREELEMENTSDIR . 'public/class-pointfindercoreelements-listing-details.php';
		require_once PFCOREELEMENTSDIR . 'public/class-pointfindercoreelements-agent-details.php';
		require_once PFCOREELEMENTSDIR . 'public/class-pointfindercoreelements-dashboard-page.php';
		require_once PFCOREELEMENTSDIR . 'public/class-pointfindercoreelements-dashboard-frontend.php';

		$this->loader = new Pointfindercoreelements_Loader();
		
	}

	private function set_locale() {

		$plugin_i18n = new Pointfindercoreelements_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function define_admin_hooks() {

		$plugin_admin = new Pointfindercoreelements_Admin($this->get_plugin_name(), $this->get_version(), $this->post_type_name, $this->agent_post_type_name);


		$this->loader->add_action( 'init', $plugin_admin, 'pointfinder_custompoints_filter',10);
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'create_post_type_pointfinder',0 );
		$this->loader->add_action( 'init', $plugin_admin, 'pointfinder_membership_pack_function');
		$this->loader->add_action( 'init', $plugin_admin, 'pointfinder_ppp_pack_function');
		$this->loader->add_action( 'init', $plugin_admin, 'pf_custom_post_status');
		$this->loader->add_action( 'admin_init', $plugin_admin, 'pointfinder2_TAX_register_taxonomy_meta_boxes');
		$this->loader->add_filter( 'use_block_editor_for_post_type', $plugin_admin, 'pointfinder_post_type_filter',10,2);

		$this->loader->add_action( 'redux/construct', $plugin_admin, 'redux_disable_dev_mode_plugin',10,1);
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'pointfinder_remove_redux_menu',12);
		$this->loader->add_action( 'vc_before_init', $plugin_admin, 'pointfinder_new_vcSetAsTheme');
		$this->loader->add_action( 'pointfinder_run_onlyoncefunction', $plugin_admin, 'pointfinder_ultimate_and_vc_options');
		$this->loader->add_action( 'admin_head', $plugin_admin, 'pointfinder_ultimate_fix');
		$this->loader->add_filter( 'wp_redirect', $plugin_admin, 'pointfinder_remove_redux_redirection',10,2 );
		

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'pf_register_wpml_string_output',20);


		$this->loader->add_action( 'wp_dashboard_setup', $plugin_admin, 'pointfinder_add_dashboard_widgets');

		//$this->loader->add_action( 'admin_notices', $plugin_admin, 'pointfinder_new_version_notice');
		$this->loader->add_filter( 'rest_prepare_taxonomy', $plugin_admin, 'pointfinder_rest_prepare_function',10,2);

		$this->loader->add_filter( 'wp_privacy_personal_data_erasers', $plugin_admin, 'pointfinder_register_erasers',10,1);

		$this->loader->add_action( 'admin_head-edit-tags.php', $plugin_admin, 'pfconditions_remove_parent_category');

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'pointfinder_remove_submenu_cpts' );
		$this->loader->add_action( 'admin_head', $plugin_admin, 'pointfinder_remove_unwanted_cpts' );
		$this->loader->add_filter( 'page_row_actions', $plugin_admin, 'pointfinder_remove_unwanted_pra',10,2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'pointfinder_unwanted_remove_meta_box',10,1 );
		$this->loader->add_action( 'admin_head-edit.php', $plugin_admin, 'pointfinder_admin_head_custompost_listing' );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'pointfinder_orders_add_meta_box_ex',10,1 );
		$this->loader->add_filter( 'rwmb_meta_boxes', $plugin_admin, 'pointfinder_metaboxio_metaboxes',10,1 );
		$this->loader->add_action( 'save_post', $plugin_admin, 'pointfinder_item_save_meta_box_data',10,1 );


		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'pf_add_additional_review_metabox',10,2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'pointfinder_reviews_add_meta_box',10,2 );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'pointfinder_morders_add_meta_box',10,1 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'pointfinder_orders_add_meta_box',10,1 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'pointfinder_activate_morder',0,1 );

		$this->loader->add_action( 'before_delete_post', $plugin_admin, 'pointfinder_before_delete_post',10,1 );
		$this->loader->add_action( 'transition_post_status', $plugin_admin, 'pointfinder_all_item_status_changes',10,3 );

		$this->loader->add_action( 'post_updated', $plugin_admin, 'pointfinder_correctowneroforder',10,3 );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'pointfinderex_author_metabox_remove');
		$this->loader->add_action( 'post_submitbox_misc_actions', $plugin_admin, 'pointfinderex_author_metabox_move');

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'pf_reviewer_message_metabox',10,1 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'pointfinder_add_altered_submit_box',101, 2);
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'pointfinder_minvoices_add_meta_box',10, 1);


		$this->loader->add_action( 'user_contactmethods', $plugin_admin, 'pf_modify_contact_methods',10, 1);
		$this->loader->add_action( 'show_user_profile', $plugin_admin, 'pf_custom_user_profile_fields',10, 1);
		$this->loader->add_action( 'edit_user_profile', $plugin_admin, 'pf_custom_user_profile_fields',10, 1);
		$this->loader->add_action( 'edit_user_profile_update', $plugin_admin, 'pf_update_extra_profile_fields',10, 1);
		$this->loader->add_action( 'personal_options_update', $plugin_admin, 'pf_update_extra_profile_fields',10, 1);

		$this->loader->add_action( 'manage_pfmembershippacks_posts_custom_column', $plugin_admin, 'pointfinder_listingpacks_manage_columns',10,2);
		$this->loader->add_action( 'manage_pfmembershippacks_posts_columns', $plugin_admin, 'pointfinder_membershippacks_edit_columns',10,1);


		$this->loader->add_action( 'manage_pflistingpacks_posts_custom_column', $plugin_admin, 'pointfinder_membershippacks_manage_columns',10,2);
		$this->loader->add_action( 'manage_pflistingpacks_posts_columns', $plugin_admin, 'pointfinder_listingpacks_edit_columns',10,1);

		$this->loader->add_action( 'widgets_init', $plugin_admin, 'pointfinder_widgets_initialization' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'pointfinder_register_my_page',7 );
		$this->loader->add_action( 'pointfinder_cleanup_query_for_grid', $plugin_admin, 'pointfinder_query_cleanup_filter',10,1);

		/**
		*Start: Invoices Filters
		**/
			$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'pf_invoices_item_filter' );
			$this->loader->add_action( 'parse_query', $plugin_admin, 'pf_invoices_item_filter_query',10,1 );
			$this->loader->add_filter( 'manage_edit-pointfinderinvoices_columns', $plugin_admin, 'pointfinder_edit_invoices_columns',10,1 );
			$this->loader->add_action( 'manage_pointfinderinvoices_posts_custom_column', $plugin_admin, 'pointfinder_manage_invoices_columns',10,2 );
		/**
		*End: Invoices Filters
		**/

		/**
		*Start: Reviews Item Filter
		**/
			$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'pf_reviews_item_filter' );
			$this->loader->add_filter( 'parse_query', $plugin_admin, 'pf_reviews_item_filter_query',10,1 );
			$this->loader->add_action( 'admin_head', $plugin_admin, 'pf_clear_flagged_review' );
			$this->loader->add_filter( 'manage_edit-pointfinderreviews_columns', $plugin_admin, 'pointfinder_edit_reviews_columns',10,1 );
			$this->loader->add_filter( 'manage_edit-pointfinderreviews_sortable_columns', $plugin_admin, 'pointfinder_reviews_sortable_columns',10,1 );
			$this->loader->add_action( 'manage_pointfinderreviews_posts_custom_column', $plugin_admin, 'pointfinder_manage_reviews_columns',10,2 );
		/**
		*End: Reviews Item Filter
		**/

		/**
		*Start: Order List Filters
		**/
			$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'pf_orders_item_filter' );
			$this->loader->add_filter( 'parse_query', $plugin_admin, 'pf_orders_item_filter_query',10,1 );
			$this->loader->add_filter( 'manage_edit-pointfinderorders_columns', $plugin_admin, 'pointfinder_edit_orders_columns',10,1 );
			$this->loader->add_action( 'manage_pointfinderorders_posts_custom_column', $plugin_admin, 'pointfinder_manage_orders_columns',10,2 );
		/**
		*End: Order List Filters
		**/

		/**
		*Start: Order Membership List Filters
		**/
			$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'pf_morders_item_filter' );
			$this->loader->add_filter( 'parse_query', $plugin_admin, 'pf_morders_item_filter_query',10,1 );
			$this->loader->add_filter( 'manage_edit-pointfindermorders_columns', $plugin_admin, 'pointfinder_edit_morders_columns',10,1 );
			$this->loader->add_action( 'manage_pointfindermorders_posts_custom_column', $plugin_admin, 'pointfinder_manage_morders_columns',10,2 );
			/**
		*End: Order Membership List Filters
		**/


		/**
		*Start: PF Items Item Filter
		**/
			$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'pointfinder_items_item_filter' );
			$this->loader->add_filter( 'parse_query', $plugin_admin, 'pointfinder_items_item_filter_query',10,1 );
			$this->loader->add_filter( 'manage_edit-'.$this->post_type_name.'_columns', $plugin_admin, 'pointfinder_items_edit_columns',10,1 );
			$this->loader->add_filter( 'manage_edit-'.$this->post_type_name.'_sortable_columns', $plugin_admin, 'pointfinder_items_sortable_columns',10,1 );
			$this->loader->add_action( 'manage_'.$this->post_type_name.'_posts_custom_column', $plugin_admin, 'pointfinder_items_manage_columns',10,2 );
		/**
		*End: PF Items Item Filter
		**/

		
		

		/**
		*Start: Schedule System
		**/

			$this->loader->add_action( 'after_switch_theme', $plugin_admin, 'pointfinder_activation_twicedaily' );
			$this->loader->add_action( 'after_switch_theme', $plugin_admin, 'pointfinder_activation_daily' );
			$this->loader->add_action( 'after_switch_theme', $plugin_admin, 'pointfinder_activation_hourly2' );

			$this->loader->add_action( 'switch_theme', $plugin_admin, 'pointfinder_deactivation_daily' );
			$this->loader->add_action( 'switch_theme', $plugin_admin, 'pointfinder_deactivation_hourly' );
			$this->loader->add_action( 'switch_theme', $plugin_admin, 'pointfinder_deactivation_hourly2' );

			$this->loader->add_action( 'pointfinder_schedule_hooks_hourly', $plugin_admin, 'pointfinder_check_expires_member' );
			$this->loader->add_action( 'pointfinder_schedule_hooks_daily', $plugin_admin, 'pointfinder_check_expiring_member' );
			
			$this->loader->add_action( 'pointfinder_schedule_hooks_hourly', $plugin_admin, 'pointfinder_clean_pending_orders' );
			$this->loader->add_action( 'pointfinder_schedule_hooks_hourly', $plugin_admin, 'pointfinder_check_expires' );
			$this->loader->add_action( 'pointfinder_schedule_hooks_hourly', $plugin_admin, 'pointfinder_check_expires_featured' );
			$this->loader->add_action( 'pointfinder_schedule_hooks_daily', $plugin_admin, 'pointfinder_check_expiring' );


			$this->loader->add_action( 'pointfinder_schedule_hooks_daily', $plugin_admin, 'pointfinder_clear_unusedimages' );



			$st9_currency_status = $this->PFASSIssetControl('st9_currency_status','',0);
			if (!empty($st9_currency_status)) {

				//$this->loader->add_action( 'init', $plugin_admin, 'pointfinder_currency_system_process' );

				$st9_currency_when = $this->PFASSIssetControl('st9_currency_when','','twicedaily');

				switch ($st9_currency_when) {
					case 'hourly':
						$this->loader->add_action( 'pointfinder_schedule_hooks_hourly2', $plugin_admin, 'pointfinder_currency_schedule' );
						break;

					case 'twicedaily':
						$this->loader->add_action( 'pointfinder_schedule_hooks_hourly', $plugin_admin, 'pointfinder_currency_schedule' );
						break;

					case 'daily':
						$this->loader->add_action( 'pointfinder_schedule_hooks_daily', $plugin_admin, 'pointfinder_currency_schedule' );
						break;
				}
			}
		/**
		*End: Schedule System
		**/	


		/**
		*Start: Listing Type Connections
		**/
			$this->loader->add_action( 'created_pointfinderfeatures', $plugin_admin, 'pointfinder_category_form_custom_field_save',10, 2 );
			$this->loader->add_action( 'edited_pointfinderfeatures', $plugin_admin, 'pointfinder_category_form_custom_field_save',10, 2 );
			$this->loader->add_action( 'created_pointfinderitypes', $plugin_admin, 'pointfinder_category_form_custom_field_save',10, 2 );
			$this->loader->add_action( 'edited_pointfinderitypes', $plugin_admin, 'pointfinder_category_form_custom_field_save',10, 2 );
			$this->loader->add_action( 'created_pointfinderconditions', $plugin_admin, 'pointfinder_category_form_custom_field_save',10, 2 );
			$this->loader->add_action( 'edited_pointfinderconditions', $plugin_admin, 'pointfinder_category_form_custom_field_save',10, 2 );

			$this->loader->add_action( 'pointfinderfeatures_add_form_fields', $plugin_admin, 'pointfinder_category_form_custom_field_add',10, 1 );
			$this->loader->add_action( 'pointfinderitypes_add_form_fields', $plugin_admin, 'pointfinder_category_form_custom_field_add',10, 1 );
			$this->loader->add_action( 'pointfinderconditions_add_form_fields', $plugin_admin, 'pointfinder_category_form_custom_field_add',10, 1 );

			$this->loader->add_action( 'pointfinderfeatures_edit_form_fields', $plugin_admin, 'pointfinder_category_form_custom_field_edit',10, 2 );
			$this->loader->add_action( 'pointfinderitypes_edit_form_fields', $plugin_admin, 'pointfinder_category_form_custom_field_edit',10, 2 );
			$this->loader->add_action( 'pointfinderconditions_edit_form_fields', $plugin_admin, 'pointfinder_category_form_custom_field_edit',10, 2 );
		/**
		*End: Listing Type Connections
		**/	


		if (function_exists('vc_set_shortcodes_templates_dir')) {
			$plugin_vc = new PointFinderVCNewParams();
			$this->loader->add_action( 'admin_head', $plugin_vc, 'pf_remove_vcmeta_boxes');
			$this->loader->add_filter( 'vc_shortcodes_css_class', $plugin_vc, 'pfcustom_css_classes_for_vc_row_and_vc_column',10,2);
			$this->loader->add_action( 'vc_before_init', $plugin_vc, 'pf_vc_remove_all_pointers');
			$this->loader->add_action( 'vc_before_init', $plugin_vc, 'visualcomposer_single_page_elements');
			$this->loader->add_action( 'vc_before_init', $plugin_vc, 'additional_init');
		}



		
		
		if (!has_filter( 'pf_publish_bulk_action' )) {
			$this->loader->add_filter( 'bulk_actions-edit-listing', $plugin_admin, 'pf_publish_bulk_action',10,1 );
			$this->loader->add_filter( 'handle_bulk_actions-edit-listing', $plugin_admin, 'pf_publish_bulk_action_handler',10,3 );
			$this->loader->add_filter( 'handle_bulk_actions-edit-listing', $plugin_admin, 'pf_reject_bulk_action_handler',10,3 );
			$this->loader->add_action( 'admin_notices', $plugin_admin, 'pf_publish_bulk_action_admin_notice',10);
			$this->loader->add_action( 'admin_notices', $plugin_admin, 'pf_reject_bulk_action_admin_notice',10);
		}



		/* Quick Installation */
		$this->loader->add_filter( 'pt-ocdi/disable_pt_branding', $plugin_admin, 'pointfinder_disable_ptbranding' );
		$this->loader->add_filter( 'pt-ocdi/enable_grid_layout_import_popup_confirmation', $plugin_admin, 'pointfinder_disable_popupconfirmationocdi' );
		$this->loader->add_action( 'pt-ocdi/after_import', $plugin_admin, 'pointfinder_ocdi_after_import', 10, 1);

		$this->loader->add_filter( 'pt-ocdi/regenerate_thumbnails_in_content_import', $plugin_admin, 'pointfinder_disable_regeneratethumbs');
		$this->loader->add_filter( 'pt-ocdi/import_files', $plugin_admin, 'pointfinder_ocdi_import_files');

	}

	private function define_public_hooks() {
		$PointFinderIPNListener = new PointFinderIPNListener();
		$this->loader->add_action( 'wp', $PointFinderIPNListener, 'pointfinder_ipn_requests_handle' );

		$plugin_public = new Pointfindercoreelements_Public($this->get_plugin_name(), $this->get_version(), $this->post_type_name);


		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles_scripts' );

		$this->loader->add_filter( 'dynamic_sidebar_params', $plugin_public, 'pointfinder_check_search_widget',10,1);

		$this->loader->add_action( 'init', $plugin_public, 'pointfinder_get_admin_options_again_when_language_switched',0);
		$this->loader->add_action( 'wp_footer', $plugin_public, 'pointfinder_footer_system' );
		$this->loader->add_filter( 'posts_where', $plugin_public, 'pointfinder_geo_posts_where',10,2);
		$this->loader->add_filter( 'posts_join', $plugin_public, 'pointfinder_geo_posts_join',10,2);

		$this->loader->add_filter( 'posts_fields', $plugin_public, 'pointfinder_geojs_posts_fields',10,2);
		$this->loader->add_filter( 'posts_join', $plugin_public, 'pointfinder_geojs_posts_join',10,2);
		$this->loader->add_filter( 'posts_where', $plugin_public, 'pointfinder_geojs_posts_where',10,2);
		$this->loader->add_filter( 'posts_orderby', $plugin_public, 'pointfinder_geojs_posts_orderby',10,2);

		$this->loader->add_filter( 'posts_where', $plugin_public, 'pointfinder_openhours_posts_where',10,2);
		$this->loader->add_filter( 'posts_join', $plugin_public, 'pointfinder_openhours_posts_join',10,2);

		$this->loader->add_filter( 'pointfinder_accept_language_filter', $plugin_public, 'pointfinder_accept_language_filter_func' );
		$this->loader->add_action( 'pointfinder_gallery_post_action', $plugin_public, 'pointfinder_gallery_post_action_func' );
		$this->loader->add_action( 'template_redirect', $plugin_public, 'pointfinder_review_fix_pagination' );
		$this->loader->add_action( 'template_redirect', $plugin_public, 'pointfinder_tag_fix_pagination' );
		$this->loader->add_action( 'init', $plugin_public, 'pointfinder_removeDemoModeLink' );

		$PointFinderDynamicCSS = new PointFinderDynamicCSS();
		$this->loader->add_action( 'wp_enqueue_scripts', $PointFinderDynamicCSS, 'pointfinder_dynamic_css',999);
		$this->loader->add_filter( 'style_loader_tag', $plugin_public, 'pointfinderh_style_remove' );
		$this->loader->add_action( 'wp_update_nav_menu_item', $plugin_public, 'pointfinder_custom_nav_update',10,3 );
		$this->loader->add_filter( 'wp_setup_nav_menu_item', $plugin_public, 'pointfinder_custom_nav_item',10, 1 );
		$this->loader->add_filter( 'wp_edit_nav_menu_walker', $plugin_public, 'pointfinder_custom_nav_edit_walker',10,2 );
		$this->loader->add_action( 'get_header', $plugin_public, 'pf_enable_threaded_comments' );
		$this->loader->add_action( 'widgets_init', $plugin_public, 'pointfinder_remove_recent_comments_style' );
		$this->loader->add_filter( 'avatar_defaults', $plugin_public, 'pointfindercoreelementsgravatar' );
		$this->loader->add_filter( 'body_class', $plugin_public, 'pf_add_slug_to_body_class', 10, 1);
		$this->loader->add_filter( 'wp_nav_menu_args', $plugin_public, 'pointfinder_wp_nav_menu_args',10,1);
		$this->loader->add_filter( 'the_category', $plugin_public, 'pf_remove_category_rel_from_category_list',10,1);
		$this->loader->add_filter( 'post_thumbnail_html', $plugin_public, 'pointfinder_remove_thumbnail_dimensions',10,1);
		$this->loader->add_filter( 'image_send_to_editor', $plugin_public, 'pointfinder_remove_thumbnail_dimensions',10,1);

		$this->loader->add_filter( 'excerpt_more', $plugin_public, 'pointfinderh_blank_view_article',10,1);
		$this->loader->add_filter( 'the_content_more_link', $plugin_public, 'pointfinder_modify_read_more_link');
		$this->loader->add_filter( 'wp_title', $plugin_public, 'pointfinder_wp_title',10,2);

		$this->loader->add_action( 'init', $plugin_public, 'pointfinder_possibly_redirect' );

		$this->loader->add_action( 'login_form_rp', $plugin_public, 'pointfinder_redirect_to_custom_password_reset' );
		$this->loader->add_action( 'login_form_resetpass', $plugin_public, 'pointfinder_redirect_to_custom_password_reset' );
		$this->loader->add_action( 'login_form_lostpassword', $plugin_public, 'pointfinder_redirect_to_custom_lostpassword' );

		$this->loader->add_action( 'login_form_confirmaction', $plugin_public, 'pointfinder_redirect_to_custom_confirmaction' );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'pointfinder_lp_system_handler' );
		
		$this->loader->add_action( 'wp_footer', $plugin_public, 'pointfinder_accremove_system_handler' );

		$this->loader->add_filter( 'posts_where', $plugin_public, 'pointfinder_title_filter',10,2);
		$this->loader->add_filter( 'posts_where', $plugin_public, 'pointfinder_description_filter',10,2);
		$this->loader->add_filter( 'posts_where', $plugin_public, 'pointfinder_title_desc_filter',10,2);

		$this->loader->add_filter( 'body_class', $plugin_public, 'pointfinder_halfpage_map_body_class',10,2);
		$this->loader->add_filter( 'widget_tag_cloud_args', $plugin_public, 'pointfinder_tag_cloud_limit',10,1);

		$this->loader->add_action( 'pre_get_posts', $plugin_public, 'pointfinder_tags_page_fix',10,1 );

		$this->loader->add_filter( 'widget_title', $plugin_public, 'pfedit_my_widget_title',10,3);
		$this->loader->add_action( 'wp_insert_post', $plugin_public, 'PF_SAVE_FEATURED_MARKER_DATA',0,3);

		$this->loader->add_filter( 'wpcf7_form_class_attr', $plugin_public, 'pointfinder_form_class_attr',10,1);
		$this->loader->add_filter( 'wpcf7_form_elements', $plugin_public, 'pointfinder_wpcf7_form_elements',10,1);

		$this->loader->add_filter( 'wp_insert_post_data', $plugin_public, 'pf_default_comments_on',10,1);

		add_filter('widget_text', 'do_shortcode');
		add_filter('widget_text', 'shortcode_unautop');
		add_filter('the_excerpt', 'shortcode_unautop');
		add_filter('the_excerpt', 'do_shortcode');

		add_post_type_support( $this->post_type_name, 'comments' );
		add_post_type_support( $this->post_type_name, 'author' );

		$this->loader->add_action( 'init', $plugin_public, 'pointfinder_admin_bar_operations');
		$this->loader->add_action( 'wp_footer', $plugin_public, 'PF_SocialErrorHandler',400);
		$this->loader->add_action( 'wp_footer', $plugin_public, 'PF_SocialModalHandler',400);
		$this->loader->add_action( 'init', $plugin_public, 'PointFinder_Social_Facebook_Login');

		$this->loader->add_filter( 'body_class', $plugin_public, 'pointfinder_body_class_filter',10,1);
		$this->loader->add_filter( 'wp_mail_from_name', $plugin_public, 'pointfinder_mail_wp_mail_from_name',10,1);
		$this->loader->add_filter( 'wp_mail_from', $plugin_public, 'pointfinder_mail_wp_mail_from',10,1);
		$this->loader->add_filter( 'wp_mail_content_type', $plugin_public, 'pointfinder_mail_content_type',10,1);
		$this->loader->add_filter( 'pointfinder_authorpage_filter', $plugin_public, 'pointfinder_authorpage_filter_function',10,2);
		$this->loader->add_filter( 'pointfinder_invoicepage_filter', $plugin_public, 'pointfinder_invoicepage_filter_function',10,3);
		

		$this->loader->add_action( 'pre_get_posts', $plugin_public, 'pointfinder_alter_query_for_fix_default_taxorder',10,1);
		$this->loader->add_action( 'wp_head', $plugin_public, 'pf_invoices_mainfix');
		$this->loader->add_action( 'pf_desc_editor_hook', $plugin_public, 'pf_newwp_editor_action',10,1);

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'pointfinder_custom_pointStyles_newsys',11);//edited_pointfinderltypes


		/* Single Listing Page */
		$plugin_listing_details = new Pointfindercoreelements_ListingDetails($this->get_plugin_name(), $this->get_version(), $this->post_type_name);
		$this->loader->add_filter( 'pointfinder_lpost_type_check', $plugin_listing_details, 'pointfinder_listing_post_type_filter', 10, 2);
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_listing_details, 'enqueue_scripts' );
		$this->loader->add_action( 'pointfinder_single_page_elements', $plugin_listing_details, 'pointfinder_single_listing_output' );


		/* Single Agent Page */
		$plugin_agent_details = new Pointfindercoreelements_AgentDetails($this->get_plugin_name(), $this->get_version(), $this->agent_post_type_name);
		$this->loader->add_filter( 'pointfinder_apost_type_check', $plugin_agent_details, 'pointfinder_agent_post_type_filter', 10, 2);
		$this->loader->add_action( 'pointfinder_single_page_elements', $plugin_agent_details, 'pointfinder_single_agent_output' );


		$this->loader->add_action( 'pointfinder_search_page_hook', $plugin_public, 'pointfinder_search_page_func' );
		$this->loader->add_action( 'pointfinder_category_page_hook', $plugin_public, 'pointfinder_category_page_func' );
		
		
		$plugin_dashboardpage = new PointfinderDashboardPageClass();
		$this->loader->add_action( 'pointfinder_dashboardpage_hook', $plugin_dashboardpage, 'pointfinder_dashpage_maindash');


		$this->loader->add_filter( 'user_confirmed_action_email_content', $plugin_public, 'pointfinder_user_confirmed_action_email_content', 10, 2);
		$this->loader->add_filter( 'user_request_action_email_content', $plugin_public, 'pointfinder_user_request_action_email_content', 10, 2);
		$this->loader->add_filter( 'user_request_action_email_subject', $plugin_public, 'pointfinder_user_request_action_email_subject', 10, 3);
		$this->loader->add_filter( 'user_erasure_complete_email_subject', $plugin_public, 'pointfinder_user_erasure_complete_email_subject', 10, 3);

		
		$this->loader->add_filter( 'fep_menu_buttons', $plugin_public, 'pointfinder_frontendpm_menu_filter', 11, 1);
	}



	private function define_ajax_hooks() {
		$plugin_ajax = new Pointfindercoreelements_AJAX();

		/* AJAX - Quick Setup */
		$PointfinderQuickInstall = new PointfinderQuickInstall();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_quicksetupprocess', $PointfinderQuickInstall, 'pf_ajax_quicksetupprocess' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_quicksetupprocess', $PointfinderQuickInstall, 'pf_ajax_quicksetupprocess' );

		
		/* AJAX - Grab Tweets */
		$PointFinderGrabTweets = new PointFinderGrabTweets();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_grabtweets', $PointFinderGrabTweets, 'pf_ajax_grabtweets' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_grabtweets', $PointFinderGrabTweets, 'pf_ajax_grabtweets' );

		
		/* AJAX - Listing Type Limits */
		$PointFinderListingTypeLimits = new PointFinderListingTypeLimits();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_listingtypelimits', $PointFinderListingTypeLimits, 'pf_ajax_listingtypelimits' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_listingtypelimits', $PointFinderListingTypeLimits, 'pf_ajax_listingtypelimits' );

		/* AJAX - Disable admin notices */
		$PointFinderNagSystem = new PointFinderNagSystem();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_nagsystem', $PointFinderNagSystem, 'pf_ajax_nagsystem' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_nagsystem', $PointFinderNagSystem, 'pf_ajax_nagsystem' );

		/* AJAX - User System Handler */
		$PointFinderUserSystemHandler = new PointFinderUserSystemHandler();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_usersystemhandler', $PointFinderUserSystemHandler, 'pf_ajax_usersystemhandler' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_usersystemhandler', $PointFinderUserSystemHandler, 'pf_ajax_usersystemhandler' );

		/* AJAX - User System  */
		$PointFinderUserSystem = new PointFinderUserSystem();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_usersystem', $PointFinderUserSystem, 'pf_ajax_usersystem' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_usersystem', $PointFinderUserSystem, 'pf_ajax_usersystem' );

		/* AJAX - On/Off System */
		$PointFinderOnOffSystem = new PointFinderOnOffSystem();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_onoffsystem', $PointFinderOnOffSystem, 'pf_ajax_onoffsystem' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_onoffsystem', $PointFinderOnOffSystem, 'pf_ajax_onoffsystem' );

		/* AJAX - Taxpoint */
		$PointFinderTaxPoint = new PointFinderTaxPoint();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_taxpoint', $PointFinderTaxPoint, 'pf_ajax_taxpoint' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_taxpoint', $PointFinderTaxPoint, 'pf_ajax_taxpoint' );

		/* AJAX - Search Listings */
		$PointFinderSearchListings = new PointFinderSearchListings();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_searchitems', $PointFinderSearchListings, 'pf_ajax_searchitems' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_searchitems', $PointFinderSearchListings, 'pf_ajax_searchitems' );

		/* AJAX - Review Flag System */
		$PointFinderReviewFlag = new PointFinderReviewFlag();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_flagreview', $PointFinderReviewFlag, 'pf_ajax_flagreview' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_flagreview', $PointFinderReviewFlag, 'pf_ajax_flagreview' );

		/* AJAX - Listing Report */
		$PointFinderListingReport = new PointFinderListingReport();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_reportitem', $PointFinderListingReport, 'pf_ajax_reportitem' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_reportitem', $PointFinderListingReport, 'pf_ajax_reportitem' );

		/* AJAX - Post Tag */
		$PointFinderAjaxPostTag = new PointFinderAjaxPostTag();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_posttag', $PointFinderAjaxPostTag, 'pf_ajax_posttag' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_posttag', $PointFinderAjaxPostTag, 'pf_ajax_posttag' );

		/* AJAX - Get Markers */
		$PointFinderPoiData = new PointFinderPoiData();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_markers', $PointFinderPoiData, 'pf_ajax_markers' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_markers', $PointFinderPoiData, 'pf_ajax_markers' );

		/* AJAX - Payment System */
		$PointFinderPaymentSystem = new PointFinderPaymentSystem();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_paymentsystem', $PointFinderPaymentSystem, 'pf_ajax_paymentsystem' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_paymentsystem', $PointFinderPaymentSystem, 'pf_ajax_paymentsystem' );

		/* AJAX - Owner Change */
		$PointFinderOwnerChange = new PointFinderOwnerChange();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_createorder', $PointFinderOwnerChange, 'pf_ajax_createorder' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_createorder', $PointFinderOwnerChange, 'pf_ajax_createorder' );

		/* AJAX - Modal System */
		$PointFinderModalSYS = new PointFinderModalSYS();
		$PointFinderModalSYSHandler = new PointFinderModalSYSHandler();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_modalsystemhandler', $PointFinderModalSYSHandler, 'pf_ajax_modalsystemhandler' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_modalsystemhandler', $PointFinderModalSYSHandler, 'pf_ajax_modalsystemhandler' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_modalsystem', $PointFinderModalSYS, 'pf_ajax_modalsystem' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_modalsystem', $PointFinderModalSYS, 'pf_ajax_modalsystem' );

		/* AJAX - Membership System */
		$PointFinderMembershipSYS = new PointFinderMembershipSYS();
		$PointFinderMembershipSYSPayment = new PointFinderMembershipSYSPayment();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_membershipsystem', $PointFinderMembershipSYS, 'pf_ajax_membershipsystem' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_membershipsystem', $PointFinderMembershipSYS, 'pf_ajax_membershipsystem' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_membershippaymentsystem', $PointFinderMembershipSYSPayment, 'pf_ajax_membershippaymentsystem' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_membershippaymentsystem', $PointFinderMembershipSYSPayment, 'pf_ajax_membershippaymentsystem' );

		/* AJAX - Listing Types */
		$PointFinderAjaxListingTypes = new PointFinderAjaxListingTypes();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_listingtype', $PointFinderAjaxListingTypes, 'pf_ajax_listingtype' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_listingtype', $PointFinderAjaxListingTypes, 'pf_ajax_listingtype' );

		/* AJAX - Listing Payments */
		$PointFinderListingPaymentSYS = new PointFinderListingPaymentSYS();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_listingpaymentsystem', $PointFinderListingPaymentSYS, 'pf_ajax_listingpaymentsystem' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_listingpaymentsystem', $PointFinderListingPaymentSYS, 'pf_ajax_listingpaymentsystem' );

		/* AJAX - Listing Data */
		$PointFinderListData = new PointFinderListData();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_listitems', $PointFinderListData, 'pf_ajax_list_items' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_listitems', $PointFinderListData, 'pf_ajax_list_items' );

		/* AJAX - Item SYS */
		$PointFinderItemSYS = new PointFinderItemSYS();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_itemsystem', $PointFinderItemSYS, 'pf_ajax_itemsystem' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_itemsystem', $PointFinderItemSYS, 'pf_ajax_itemsystem' );

		/* AJAX - Info Window */
		$PointFinderInfoWindow = new PointFinderInfoWindow();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_infowindow', $PointFinderInfoWindow, 'pf_ajax_infowindow' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_infowindow', $PointFinderInfoWindow, 'pf_ajax_infowindow' );

		/* AJAX - Image Upload */
		$PointFinderIMGUpload = new PointFinderIMGUpload();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_imageupload', $PointFinderIMGUpload, 'pf_ajax_imageupload' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_imageupload', $PointFinderIMGUpload, 'pf_ajax_imageupload' );

		/* AJAX - Image SYS */
		$PointFinderImageSYS = new PointFinderImageSYS();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_imagesystem', $PointFinderImageSYS, 'pf_ajax_imagesystem' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_imagesystem', $PointFinderImageSYS, 'pf_ajax_imagesystem' );

		/* AJAX - Get user info */
		$PointFinderGETUserChange = new PointFinderGETUserChange();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_authorchangesystem', $PointFinderGETUserChange, 'pf_ajax_authorchangesystem' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_authorchangesystem', $PointFinderGETUserChange, 'pf_ajax_authorchangesystem' );

		/* AJAX - File Upload */
		$PointFinderAJAXFileUpload = new PointFinderAJAXFileUpload();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_fileupload', $PointFinderAJAXFileUpload, 'pf_ajax_fileupload' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_fileupload', $PointFinderAJAXFileUpload, 'pf_ajax_fileupload' );

		/* AJAX - File SYS */
		$PointFinderAJAXFileSYS = new PointFinderAJAXFileSYS();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_filesystem', $PointFinderAJAXFileSYS, 'pf_ajax_filesystem' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_filesystem', $PointFinderAJAXFileSYS, 'pf_ajax_filesystem' );

		/* AJAX - Feature SYS */
		$PointFinderFeatureSYS = new PointFinderFeatureSYS();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_featuresystem', $PointFinderFeatureSYS, 'pf_ajax_featuresystem' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_featuresystem', $PointFinderFeatureSYS, 'pf_ajax_featuresystem' );

		/* AJAX - Feature Filter SYS */
		$PointFinderFeaturesFilter = new PointFinderFeaturesFilter();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_featuresfilter', $PointFinderFeaturesFilter, 'pf_ajax_featuresfilter' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_featuresfilter', $PointFinderFeaturesFilter, 'pf_ajax_featuresfilter' );

		/* AJAX - Favorite SYS */
		$PointFinderFavoriteSYS = new PointFinderFavoriteSYS();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_favorites', $PointFinderFavoriteSYS, 'pf_ajax_favorites' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_favorites', $PointFinderFavoriteSYS, 'pf_ajax_favorites' );

		/* AJAX - Claim SYS */
		$PointFinderClaimSYS = new PointFinderClaimSYS();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_claimitem', $PointFinderClaimSYS, 'pf_ajax_claimitem' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_claimitem', $PointFinderClaimSYS, 'pf_ajax_claimitem' );

		/* AJAX - Auto Complete */
		$PointFinderAutoCMPLT = new PointFinderAutoCMPLT();
		$PointFinderAutoCMPLTSA = new PointFinderAutoCMPLTSA();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_autocomplete', $PointFinderAutoCMPLT, 'pf_ajax_autocomplete' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_autocomplete', $PointFinderAutoCMPLT, 'pf_ajax_autocomplete' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_autocomplete_sa', $PointFinderAutoCMPLTSA, 'pf_ajax_autocomplete_sa' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_autocomplete_sa', $PointFinderAutoCMPLTSA, 'pf_ajax_autocomplete_sa' );


		$PointFinderCurrencySYSHandler = new PointFinderCurrencySYSHandler();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_currencychange', $PointFinderCurrencySYSHandler, 'pf_ajax_currencychange' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_currencychange', $PointFinderCurrencySYSHandler, 'pf_ajax_currencychange' );


		$PointFinderAccSYS = new PointFinderAccSYS();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_accountremoval', $PointFinderAccSYS, 'pf_ajax_accountremoval' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_accountremoval', $PointFinderAccSYS, 'pf_ajax_accountremoval' );


		$PointFinderGeocoding = new PointFinderGeocoding();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_geocoding', $PointFinderGeocoding, 'pf_ajax_geocoding' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_geocoding', $PointFinderGeocoding, 'pf_ajax_geocoding' );

		$PointFinderGeocodingX = new PointFinderGeocodingX();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_geocodingx', $PointFinderGeocodingX, 'pf_ajax_geocodingx' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_geocodingx', $PointFinderGeocodingX, 'pf_ajax_geocodingx' );

		$PointFinderItemCountSYS = new PointFinderItemCountSYS();
		$this->loader->add_action( 'PF_AJAX_HANDLER_pfget_itemcount', $PointFinderItemCountSYS, 'pf_ajax_itemcount' );
		$this->loader->add_action( 'PF_AJAX_HANDLER_nopriv_pfget_itemcount', $PointFinderItemCountSYS, 'pf_ajax_itemcount' );

	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}


	public function get_version() {
		return $this->version;
	}

	public function run() {
		$this->loader->run();
	}

	public function get_loader() {
		return $this->loader;
	}

}
