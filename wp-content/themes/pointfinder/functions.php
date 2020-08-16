<?php

/**********************************************************************************************************************************
*
* PointFinder Functions
*
* Author: Webbu
*
***********************************************************************************************************************************/
update_option( 'pointfinder_registered', '1' );  
if ( ! function_exists( 'pointfinder_setup' ) ){
	function pointfinder_setup() {
    
    load_theme_textdomain( 'pointfinder',get_template_directory() . '/languages');

		if (!isset($content_width)){$content_width = 1170;}

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );
    add_theme_support( 'post-thumbnails');
    add_theme_support( 'bbpress' );
    add_theme_support( 'woocommerce' );
    add_theme_support( 'html5', array('search-form', 'comment-form', 'comment-list',) );
    add_theme_support( 'title-tag' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'editor-styles' );

		register_nav_menus(array(
			'pointfinder-main-menu' => esc_html__('Point Finder Main Menu', 'pointfinder'),
			'pointfinder-footer-menu' => esc_html__('Point Finder Footer Menu', 'pointfinder')
	    ));

		if (!class_exists("pointfinder")) {
		    global $pointfinderltypes_fevars;
		    $pointfinderltypes_fevars = get_option('pointfinderltypes_fevars');
		}
	}
};
add_action('after_setup_theme', 'pointfinder_setup');

require_once( get_template_directory().'/admin/core/navmenu-walker.php');
require_once( get_template_directory().'/admin/core/theme-functions.php' );
require_once( get_template_directory().'/admin/tgm/plugins.php');
require_once( get_template_directory().'/admin/core/scripts.php');

if (!function_exists('pointfinder_v2_updatecheck')) {
  function pointfinder_v2_updatecheck(){

    $pointfinder_v2_update3 = get_option( 'pointfinder_v2_update_3');

     /* Move Twitter & PNButton Config Panel*/
    if ($pointfinder_v2_update3 != 1 && class_exists('ReduxFramework') && isset($pfadditionalsettingsconfig)) {
        global $pfadditionalsettingsconfig;
        global $pftwitterwidget_options;
        global $pfascontrol_options;
        global $pointfinder_main_options_fw;

        if (isset($pfascontrol_options['as_redirect_logins'])) {
          $pointfinder_main_options_fw->ReduxFramework->set('as_redirect_logins', $pfascontrol_options['as_redirect_logins']);
        }

        if (isset($pfascontrol_options['as_autologin'])) {
          $pointfinder_main_options_fw->ReduxFramework->set('as_autologin', $pfascontrol_options['as_autologin']);
        }

        if (isset($pfascontrol_options['as_hormode_close'])) {
          $pointfinder_main_options_fw->ReduxFramework->set('as_hormode_close', $pfascontrol_options['as_hormode_close']);
        }

        if (isset($pfascontrol_options['as_mobile_zoom'])) {
          $pointfinder_main_options_fw->ReduxFramework->set('as_mobile_zoom', $pfascontrol_options['as_mobile_zoom']);
        }

        if (isset($pftwitterwidget_options['setuptwitterwidget_conkey'])) {
          if (!empty($pftwitterwidget_options['setuptwitterwidget_conkey'])) {
            $pfadditionalsettingsconfig->ReduxFramework->set('setuptwitterwidget_conkey', $pftwitterwidget_options['setuptwitterwidget_conkey']);
            $pfadditionalsettingsconfig->ReduxFramework->set('setuptwitterwidget_consecret', $pftwitterwidget_options['setuptwitterwidget_consecret']);
            $pfadditionalsettingsconfig->ReduxFramework->set('setuptwitterwidget_acckey', $pftwitterwidget_options['setuptwitterwidget_acckey']);
            $pfadditionalsettingsconfig->ReduxFramework->set('setuptwitterwidget_accsecret', $pftwitterwidget_options['setuptwitterwidget_accsecret']);
          }
        }

        if (isset($pfascontrol_options['setup_item_catpage_sidebarpos'])) {
          global $pointfinder_main_options_fw;
          $pointfinder_main_options_fw->ReduxFramework->set('setup_item_catpage_sidebarpos', $pfascontrol_options['setup_item_catpage_sidebarpos']);
          $pointfinder_main_options_fw->ReduxFramework->set('setup_item_searchresults_sidebarpos', $pfascontrol_options['setup_item_searchresults_sidebarpos']);
        }
    }

    if ($pointfinder_v2_update3 != 1 && class_exists('ReduxFramework') && isset($pfadditionalsettingsconfig) ) {
        global $pfpbcontrol_options;
        if (isset($pfpbcontrol_options['setup21_iconboxsettings_title_typo'])) {
          $pfadditionalsettingsconfig->ReduxFramework->set('setup21_iconboxsettings_title_typo', $pfpbcontrol_options['setup21_iconboxsettings_title_typo']);
          $pfadditionalsettingsconfig->ReduxFramework->set('setup21_iconboxsettings_typ1_typo', $pfpbcontrol_options['setup21_iconboxsettings_typ1_typo']);
          $pfadditionalsettingsconfig->ReduxFramework->set('setup21_widgetsettings_3_slider_capt', $pfpbcontrol_options['setup21_widgetsettings_3_slider_capt']);
          $pfadditionalsettingsconfig->ReduxFramework->set('setup21_widgetsettings_3_title_color', $pfpbcontrol_options['setup21_widgetsettings_3_title_color']);
          $pfadditionalsettingsconfig->ReduxFramework->set('setup21_widgetsettings_3_title_typo', $pfpbcontrol_options['setup21_widgetsettings_3_title_typo']);
          $pfadditionalsettingsconfig->ReduxFramework->set('setup21_widgetsettings_3_address_color', $pfpbcontrol_options['setup21_widgetsettings_3_address_color']);
          $pfadditionalsettingsconfig->ReduxFramework->set('setup21_widgetsettings_3_address_typo', $pfpbcontrol_options['setup21_widgetsettings_3_address_typo']);
          $pfadditionalsettingsconfig->ReduxFramework->set('setup21_widgetsettings_3_typ1_typo', $pfpbcontrol_options['setup21_widgetsettings_3_typ1_typo']);
          $pfadditionalsettingsconfig->ReduxFramework->set('general_postitembutton_status', $pfpbcontrol_options['general_postitembutton_status']);
          $pfadditionalsettingsconfig->ReduxFramework->set('general_postitembutton_linkcolor', $pfpbcontrol_options['general_postitembutton_linkcolor']);
          $pfadditionalsettingsconfig->ReduxFramework->set('general_postitembutton_linkcolor_typo', $pfpbcontrol_options['general_postitembutton_linkcolor_typo']);
          $pfadditionalsettingsconfig->ReduxFramework->set('general_postitembutton_bgcolor', $pfpbcontrol_options['general_postitembutton_bgcolor']);
          $pfadditionalsettingsconfig->ReduxFramework->set('general_postitembutton_border', $pfpbcontrol_options['general_postitembutton_border']);
          $pfadditionalsettingsconfig->ReduxFramework->set('general_postitembutton_borderr', $pfpbcontrol_options['general_postitembutton_borderr']);
          $pfadditionalsettingsconfig->ReduxFramework->set('general_postitembutton_iconstatus', $pfpbcontrol_options['general_postitembutton_iconstatus']);
          $pfadditionalsettingsconfig->ReduxFramework->set('pnewiconsize', $pfpbcontrol_options['pnewiconsize']);
          $pfadditionalsettingsconfig->ReduxFramework->set('general_postitembutton_innerpadding', $pfpbcontrol_options['general_postitembutton_innerpadding']);
          $pfadditionalsettingsconfig->ReduxFramework->set('general_postitembutton_buttontext', $pfpbcontrol_options['general_postitembutton_buttontext']);
          $pfadditionalsettingsconfig->ReduxFramework->set('general_postitembutton_button_mtop', $pfpbcontrol_options['general_postitembutton_button_mtop']);
        }
    }

     update_option( 'pointfinder_v2_update_3', 1 );

  }
}
add_action( 'init', 'pointfinder_v2_updatecheck');


if (!function_exists('pf_theme_render_title')) {
  function pf_theme_render_title() {
    if ( ! function_exists( '_wp_render_title_tag' ) ) {
      ?>
      <title><?php wp_title( '|', true, 'right' ); ?></title>
      <?php
    }

    $general_responsive = PFSAIssetControl('general_responsive','','1');
    if($general_responsive == 1){
      $as_mobile_zoom = PFSAIssetControl('as_mobile_zoom','','0');
      $gesturehandling = PFSAIssetControl('gesturehandling','',1);
      if ($as_mobile_zoom == '1' && $gesturehandling != 1) {
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">';
      } else {
        echo '<meta name="viewport" content="width=device-width, maximum-scale=1.0">';
      }
    }
  }
}
add_action( 'wp_head', 'pf_theme_render_title');



if (!function_exists('pointfinder_init_frontendeditorfix')) {
    function pointfinder_init_frontendeditorfix(){

    if (isset($_GET['vc_action'])) {
      $vc_action = sanitize_text_field( $_GET['vc_action'] );
      if ($vc_action == 'vc_inline') {
        add_action('admin_enqueue_scripts','pf_styleandscripts');
      }
    }
  }
}
add_action( 'init', 'pointfinder_init_frontendeditorfix');

do_action("pointfinder_run_onlyoncefunction");


add_action( 'widgets_init', 'pointfinder_widgets_init' );
if (!function_exists('pointfinder_widgets_init')) {
  function pointfinder_widgets_init() {

      register_sidebar(array(
          'name' => esc_html__('PF Default Widget Area', 'pointfinder'),
          'description' => esc_html__('PF  Default Widget Area', 'pointfinder'),
          'id' => 'pointfinder-widget-area',
          'before_widget' => '<div id="%1$s" class="%2$s">',
          'after_widget' => '</div></div>',
          'before_title' => '',
          'after_title' => ''
      ));

      register_sidebar(array(
          'name' => esc_html__('PF Item Page Widget', 'pointfinder'),
          'description' => esc_html__('Widget area for item detail page.', 'pointfinder'),
          'id' => 'pointfinder-itempage-area',
          'before_widget' => '<div id="%1$s" class="%2$s">',
          'after_widget' => '</div></div>',
          'before_title' => '',
          'after_title' => ''
      ));

      register_sidebar(array(
          'name' => esc_html__('PF Author Page Widget', 'pointfinder'),
          'description' => esc_html__('Widget area for author detail page.', 'pointfinder'),
          'id' => 'pointfinder-authorpage-area',
          'before_widget' => '<div id="%1$s" class="%2$s">',
          'after_widget' => '</div></div>',
          'before_title' => '',
          'after_title' => ''
      ));

      if (function_exists('is_bbpress')) {
        register_sidebar(array(
            'name' => esc_html__('PF bbPress Sidebar', 'pointfinder'),
            'description' => esc_html__('Widget area for inner bbPress pages.', 'pointfinder'),
            'id' => 'pointfinder-bbpress-area',
            'before_widget' => '<div id="%1$s" class="%2$s">',
            'after_widget' => '</div></div>',
            'before_title' => '',
            'after_title' => ''
        ));
      }

      if (function_exists('is_woocommerce')) {
        register_sidebar(array(
            'name' => esc_html__('PF WooCommerce Sidebar', 'pointfinder'),
            'description' => esc_html__('Widget area for inner WooCommerce pages.', 'pointfinder'),
            'id' => 'pointfinder-woocom-area',
            'before_widget' => '<div id="%1$s" class="%2$s">',
            'after_widget' => '</div></div>',
            'before_title' => '',
            'after_title' => ''
        ));
      }

      if (function_exists('dsidxpress_InitWidgets')) {
        register_sidebar(array(
            'name' => esc_html__('PF dsIdxpress Sidebar', 'pointfinder'),
            'description' => esc_html__('Widget area for inner dsIdxpress pages.', 'pointfinder'),
            'id' => 'pointfinder-dsidxpress-area',
            'before_widget' => '<div id="%1$s" class="%2$s">',
            'after_widget' => '</div></div>',
            'before_title' => '',
            'after_title' => ''
        ));
      }
      register_sidebar(array(
          'name' => esc_html__('PF Category Sidebar', 'pointfinder'),
          'description' => esc_html__('Widget area for Item Category Page.', 'pointfinder'),
          'id' => 'pointfinder-itemcatpage-area',
          'before_widget' => '<div id="%1$s" class="%2$s">',
          'after_widget' => '</div></div>',
          'before_title' => '',
          'after_title' => ''
      ));


      register_sidebar(array(
          'name' => esc_html__('PF Search Results Sidebar', 'pointfinder'),
          'description' => esc_html__('Widget area for Item Search Results Page.', 'pointfinder'),
          'id' => 'pointfinder-itemsearchres-area',
          'before_widget' => '<div id="%1$s" class="%2$s">',
          'after_widget' => '</div></div>',
          'before_title' => '',
          'after_title' => ''
      ));


      register_sidebar(array(
          'name' => esc_html__('PF Blog Sidebar', 'pointfinder'),
          'description' => esc_html__('Widget area for single blog page.', 'pointfinder'),
          'id' => 'pointfinder-blogpages-area',
          'before_widget' => '<div id="%1$s" class="%2$s">',
          'after_widget' => '</div></div>',
          'before_title' => '',
      'after_title' => ''
      ));


      register_sidebar(array(
          'name' => esc_html__('PF Blog Category Sidebar', 'pointfinder'),
          'description' => esc_html__('Widget area for blog category page.', 'pointfinder'),
          'id' => 'pointfinder-blogcatpages-area',
          'before_widget' => '<div id="%1$s" class="%2$s">',
          'after_widget' => '</div></div>',
          'before_title' => '',
      'after_title' => ''
      ));

      register_sidebar(array(
          'name' => esc_html__('PF Blog Search Results Sidebar', 'pointfinder'),
          'description' => esc_html__('Widget area for blog search results page.', 'pointfinder'),
          'id' => 'pointfinder-blogspages-area',
          'before_widget' => '<div id="%1$s" class="%2$s">',
          'after_widget' => '</div></div>',
          'before_title' => '',
      'after_title' => ''
      ));

      if (class_exists('Redux')) {
          global $pfsidebargenerator_options;
          $setup25_sidebargenerator_sidebars = (isset($pfsidebargenerator_options['setup25_sidebargenerator_sidebars']))?$pfsidebargenerator_options['setup25_sidebargenerator_sidebars']:'';

          if(PFControlEmptyArr($setup25_sidebargenerator_sidebars)){
              if(count($setup25_sidebargenerator_sidebars) > 0){
                  foreach($setup25_sidebargenerator_sidebars as $itemvalue){
                      if (function_exists('register_sidebar') && !empty($itemvalue['title'])){

                        register_sidebar(array(
                        'name' => sanitize_title( $itemvalue['title'] ),
                        'id' => sanitize_text_field($itemvalue['url']),
                        'before_widget' => '<div id="%1$s" class="%2$s">',
                        'after_widget' => '</div></div>',
                        'before_title' => '',
                        'after_title' => ''
                        ));
                      }
                  }
              }
          }
      }
  }
}


if (!function_exists('pointfinder_block_editor_styles')) {
  function pointfinder_block_editor_styles() {
      wp_enqueue_style( 'site-block-editor-styles', get_theme_file_uri( '/style-editor.css' ), false, '1.0', 'all' );
  } 
}
add_action( 'enqueue_block_editor_assets', 'pointfinder_block_editor_styles',99 );