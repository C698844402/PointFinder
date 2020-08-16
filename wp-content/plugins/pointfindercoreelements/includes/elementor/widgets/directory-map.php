<?php
namespace PointFinderElementorSYS\Widgets;

use PF_SF_Val;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use PointFinderElementorSYS\Helper;
use PointFinderCommonFunctions;
use PointFinderOptionFunctions;
use PointFinderWPMLFunctions;
use PointFinderCommonELFunctions;

if ( ! defined( 'ABSPATH' ) ) exit;

class PointFinder_Directory_Map extends Widget_Base {

	use PointFinderOptionFunctions;
	use PointFinderCommonFunctions;
	use PointFinderWPMLFunctions;
	use PointFinderCommonELFunctions;

	public function show_in_panel() { return true; }

	public function get_keywords() { return [ 'pointfinder', 'directory map', 'map' ]; }

	public function get_name() { return 'pointfinderdirectorymap'; }

	public function get_title() { return esc_html__( 'PF Directory Map', 'pointfindercoreelements' ); }

	public function get_icon() { return 'eicon-google-maps'; }

	public function get_categories() { return [ 'pointfinder_elements' ]; }


	public function get_script_depends() {
		//if ( $this->get_settings_for_display( 'layout_style' ) == 'carousel' ) {
		/*
		$depended_scripts = array();

		if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
	        $depended_scripts[] = 'theme-pfdirectorymap';
	        $depended_scripts[] = 'flaticons';

	        $stp5_mapty = \PointFinderElementorSYS\Plugin::PFSAIssetControl('stp5_mapty','',1);
			$wemap_geoctype = \PointFinderElementorSYS\Plugin::PFSAIssetControl('wemap_geoctype','','');

			if ($stp5_mapty == 1 || $wemap_geoctype == 'google') {
			 	$depended_scripts[] = 'theme-google-api';
			}

			if ($stp5_mapty == 4) {
				$depended_scripts[] = 'theme-yandex-map';
			}
	    }*/
	    return [];

	}

	protected function _register_controls() {


		$this->start_controls_section(
			'directorymap_general',
			[
				'label' => esc_html__( 'General', 'pointfindercoreelements' ),
				'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);

			$stp5_mapty = $this->PFSAIssetControl('stp5_mapty','',1);
			$this->add_control(
				'stp5_mapty',
				[
					'label' => esc_html__("Map Type", "pointfindercoreelements"),
					'description' => esc_html__("Important: You must enter API key and codes to use map APIs", "pointfindercoreelements"),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => $stp5_mapty,
					'render_type' => 'ui',
					'options' => [
						1 => esc_html__('Google Maps', 'pointfindercoreelements'),
						2 => esc_html__('Open Street Maps', 'pointfindercoreelements'),
						3 => esc_html__('Mapbox', 'pointfindercoreelements'),
						4 => esc_html__('Yandex Maps', 'pointfindercoreelements'),
	                    5 => esc_html__('Here Maps', 'pointfindercoreelements'),
	                    6 => esc_html__('Bing Maps', 'pointfindercoreelements')
					]
				]
			);

			$this->add_control(
				'setup5_mapsettings_lat',
				[
					'label' => esc_html__( 'Default Coordinate (Lat)', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => '40.73061',
					'render_type' => 'ui'
				]
			);
			$this->add_control(
				'setup5_mapsettings_lng',
				[
					'label' => esc_html__( 'Default Coordinate (Lng)', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => '-73.935242',
					'render_type' => 'ui'
				]
			);

			$this->add_control(
				'setup5_mapsettings_height',
				[
					'label' => esc_html__( 'Desktop Map Height', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 10,
					'max' => 2000,
					'step' => 1,
					'default' => 550,
					'render_type' => 'theme'
				]
			);
			$this->add_control(
				'setup42_theight',
				[
					'label' => esc_html__( 'Tablet Map Height', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 10,
					'max' => 2000,
					'step' => 1,
					'default' => 400,
					'render_type' => 'theme'
				]
			);
			$this->add_control(
				'setup42_mheight',
				[
					'label' => esc_html__( 'Mobile Map Height', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 10,
					'max' => 2000,
					'step' => 1,
					'default' => 350,
					'render_type' => 'theme'
				]
			);
			$this->add_control(
				'setup5_mapsettings_zoom',
				[
					'label' => esc_html__( 'Desktop View Zoom', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 22,
					'step' => 1,
					'default' => 12,
					'render_type' => 'ui'
				]
			);
			$this->add_control(
				'setup5_mapsettings_zoom_mobile',
				[
					'label' => esc_html__( 'Mobile View Zoom', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 22,
					'step' => 1,
					'default' => 10,
					'render_type' => 'ui'
				]
			);
			$this->add_control(
				'hr',
				[
					'type' => \Elementor\Controls_Manager::DIVIDER,
				]
			);
			$this->add_control(
				'setup8_pointsettings_limit',
				[
					'label' => esc_html__( 'Limit Points', 'pointfindercoreelements' ),
					'description' => esc_html__( "After changing map point limit then you will see order/orderby filter options. The limit number must be higher than zero. If you set it empty, going to be unlimited.", "pointfindercoreelements" ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'render_type' => 'ui'
				]
			);

			$this->add_control(
				'setup8_pointsettings_orderby',
				[
					'label' => esc_html__("Limit Points: Order By", "pointfindercoreelements"),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'title',
					'render_type' => 'ui',
					'options' => [
						'title'  => esc_html__("Title", "pointfindercoreelements"),
						'id' => esc_html__("ID", "pointfindercoreelements"),
						'date' => esc_html__("Date", "pointfindercoreelements")
					],
					'conditions' => [
						'terms' => [
							[
								'name' => 'setup8_pointsettings_limit',
								'operator' => '!=',
								'value' => ''
							]
						]
					]
				]
			);
			$this->add_control(
				'setup8_pointsettings_order',
				[
					'label' => esc_html__( 'Limit Points: Order', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'ASC', 'pointfindercoreelements' ),
					'label_off' => esc_html__( 'DESC', 'pointfindercoreelements' ),
					'return_value' => 'ASC',
					'default' => 'DESC',
					'render_type' => 'ui',
					'separator' => 'before',
					'conditions' => [
						'terms' => [
							[
								'name' => 'setup8_pointsettings_limit',
								'operator' => '!=',
								'value' => ''
							]
						]
					]
				]
			);
			$this->add_control(
				'hr2',
				[
					'type' => \Elementor\Controls_Manager::DIVIDER,
				]
			);

			$this->add_control(
				'mapsearch_status',
				[
					'label' => esc_html__( 'Search Window', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'ON', 'pointfindercoreelements' ),
					'label_off' => esc_html__( 'OFF', 'pointfindercoreelements' ),
					'return_value' => '1',
					'default' => '1',
					'render_type' => 'theme',
					'separator' => 'none'
				]
			);

			$this->add_control(
				'horizontalmode',
				[
					'label' => esc_html__( 'Horizontal Search Window', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'ON', 'pointfindercoreelements' ),
					'label_off' => esc_html__( 'OFF', 'pointfindercoreelements' ),
					'return_value' => '1',
					'default' => '0',
					'render_type' => 'ui',
					'separator' => 'before',
					'conditions' => [
						'terms' => [
							[
								'name' => 'mapsearch_status',
								'operator' => '!=',
								'value' => ''
							]
						]
					]
				]
				
			);

			

			$this->add_control(
				'mapnot_status',
				[
					'label' => esc_html__( 'Notification Indicator', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'ON', 'pointfindercoreelements' ),
					'label_off' => esc_html__( 'OFF', 'pointfindercoreelements' ),
					'return_value' => '1',
					'default' => '1',
					'render_type' => 'ui',
					'separator' => 'none'
				]
			);
			
			

		$this->end_controls_section();




		$this->start_controls_section(
			'directorymap_filters',
			[
				'label' => esc_html__( 'Filters', 'pointfindercoreelements' ),
				'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);

			$setup3_pointposttype_pt4_check = $this->PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
	        $setup3_pointposttype_pt5_check = $this->PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
	        $setup3_pointposttype_pt6_check = $this->PFSAIssetControl('setup3_pointposttype_pt6_check','','1');
	        $setup3_pt14_check = $this->PFSAIssetControl('setup3_pt14_check','','0');

		 	$setup3_pointposttype_pt7 = $this->PFSAIssetControl('setup3_pointposttype_pt7','','Listing Types');
			$this->add_control(
				'listingtype',
				[
					'label' => $setup3_pointposttype_pt7,
					'label_block' => true,
					'type' => 'mxselect2',
					'multiple' => true,
					'options' => [],
					'default' => '',
					'render_type' => 'ui',
					'separator' => 'none'
				]
			);

			if($setup3_pointposttype_pt4_check == 1){
				$setup3_pointposttype_pt4 = $this->PFSAIssetControl('setup3_pointposttype_pt4','','Item Types');
				$this->add_control(
					'itemtype',
					[
						'label' => $setup3_pointposttype_pt4,
						'label_block' => true,
						'type' => 'mxselect2',
						'multiple' => true,
						'options' => [],
						'default' => '',
						'render_type' => 'ui',
						'separator' => 'none'
					]
				);
			}

			if($setup3_pointposttype_pt5_check == 1){
				$setup3_pointposttype_pt5 = $this->PFSAIssetControl('setup3_pointposttype_pt5','','Locations');
				$this->add_control(
					'locationtype',
					[
						'label' => $setup3_pointposttype_pt5,
						'label_block' => true,
						'type' => 'mxselect2',
						'multiple' => true,
						'options' => [],
						'default' => '',
						'render_type' => 'ui',
						'separator' => 'none'
					]
				);
			}

			if($setup3_pointposttype_pt6_check == 1){
				$setup3_pointposttype_pt6 = $this->PFSAIssetControl('setup3_pointposttype_pt6','','Features');
				$this->add_control(
					'features',
					[
						'label' => $setup3_pointposttype_pt6,
						'label_block' => true,
						'type' => 'mxselect2',
						'multiple' => true,
						'options' => [],
						'default' => '',
						'render_type' => 'ui',
						'separator' => 'none'
					]
				);
			}


			if($setup3_pt14_check == 1){
				$setup3_pt14 = $this->PFSAIssetControl('setup3_pt14','','Conditions');
				$this->add_control(
					'conditions',
					[
						'label' => $setup3_pt14,
						'label_block' => true,
						'type' => 'mxselect2',
						'multiple' => true,
						'options' => [],
						'default' => '',
						'render_type' => 'ui',
						'separator' => 'none'
					]
				);
			}


			$this->add_control(
				'pf_posts_in',
				[
					'label' => esc_html__( 'Include: Listing IDs', 'pointfindercoreelements' ),
					'label_block' => true,
					'description' => esc_html__( 'Fill this field with listing ID numbers separated by commas (,) to retrieve only that listings. Ex: 171,172,173 (Optional)', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => '',
					'render_type' => 'template',
					'separator' => 'none'
				]
			);

			$this->add_control(
				'pf_posts_not_in',
				[
					'label' => esc_html__( 'Exclude: Listing IDs', 'pointfindercoreelements' ),
					'label_block' => true,
					'description' => esc_html__( 'Fill this field with listing ID numbers separated by commas (,) to retrieve only that listings. Ex: 171,172,173 (Optional)', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => '',
					'render_type' => 'template',
					'separator' => 'none'
				]
			);

		$this->end_controls_section();

	}

	

	protected function render() {

		$settings = $this->get_settings_for_display();


		extract(array(
			'stp5_mapty' => isset($settings['stp5_mapty'])?$settings['stp5_mapty']:'',
			'setup5_mapsettings_height' => isset($settings['setup5_mapsettings_height'])?$settings['setup5_mapsettings_height']:'',
			'setup42_theight' => isset($settings['setup42_theight'])?$settings['setup42_theight']:'',
			'setup42_mheight' => isset($settings['setup42_mheight'])?$settings['setup42_mheight']:'',
			'setup5_mapsettings_lat' => isset($settings['setup5_mapsettings_lat'])?$settings['setup5_mapsettings_lat']:'',
			'setup5_mapsettings_lng' => isset($settings['setup5_mapsettings_lng'])?$settings['setup5_mapsettings_lng']:'',
			'setup5_mapsettings_zoom' => isset($settings['setup5_mapsettings_zoom'])?$settings['setup5_mapsettings_zoom']:'',
			'setup5_mapsettings_zoom_mobile' => isset($settings['setup5_mapsettings_zoom_mobile'])?$settings['setup5_mapsettings_zoom_mobile']:'',
			'setup8_pointsettings_limit' => isset($settings['setup8_pointsettings_limit'])?$settings['setup8_pointsettings_limit']:-1,
			'setup8_pointsettings_orderby' => isset($settings['setup8_pointsettings_orderby'])?$settings['setup8_pointsettings_orderby']:'title',
			'setup8_pointsettings_order' => isset($settings['setup8_pointsettings_order'])?$settings['setup8_pointsettings_order']:'DESC',
			'mapsearch_status' => isset($settings['mapsearch_status'])?$settings['mapsearch_status']:'',
			'mapnot_status' => isset($settings['mapnot_status'])?$settings['mapnot_status']:'',
			'listingtype' => isset($settings['listingtype'])?$settings['listingtype']:'',
			'itemtype' => isset($settings['itemtype'])?$settings['itemtype']:'',
			'conditions' => isset($settings['conditions'])?$settings['conditions']:'',
			'features' => isset($settings['features'])?$settings['features']:'',
			'locationtype' => isset($settings['locationtype'])?$settings['locationtype']:'',
			'horizontalmode' => isset($settings['horizontalmode'])?$settings['horizontalmode']:''
		));
		$paged = 1;

		if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
			echo '<div class="pf-sample-dmap" style="height:'.$setup5_mapsettings_height.'px;"><span>'.esc_html__( "Please click to preview mode to see map and components.", "pointfindercoreelements" ).'</span></div>';
		}else{

			if (empty($stp5_mapty)) {
				$stp5_mapty = $this->PFSAIssetControl('stp5_mapty','',1);
			}
			
			wp_enqueue_style( 'flaticons');
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


			$output = '';
			
		  		
		  	$device_check = $this->pointfinder_device_check('isDesktop');

	  		if ($horizontalmode != 0) {
	  			$horizontalmode_style = ' pfsearch-draggable-full pf-container';
	  			$horizontalmode_style2 = ' pf-row';
	  			$horizontalmode_style3 = ' class="col-lg-12 col-md-12 col-sm-12"';
	  			$hormode = 1;
	  		}else{
	  			$horizontalmode_style = $horizontalmode_style2 = $horizontalmode_style3 = '';
	  			$hormode = 0;
	  		};

	  		$tooltipstatus = $this->PFSAIssetControl('setup12_searchwindow_tooltips','','1');

	  		$drag_icon = "pfadmicon-glyph-151";
	  		$drag_status = "false";
	  		
	  		if (empty($setup8_pointsettings_limit)) {
	  			$setup8_pointsettings_limit = -1;
	  		}

	  		if ($mapsearch_status == 1) {

		  		$generalbradius = $this->PFSAIssetControl('generalbradius','','');
		  		$border_radius_level = $this->PFSAIssetControl('generalbradiuslevel','','0');
				
				$dragstatus = $mapinfostatus = $searchstyle = 'style="';
				$togglesectionstatus = '';
				for ($i=1; $i <= 3; $i++) {
					switch ($i) {
						case 1:
							if($this->PFSAIssetControl('setup12_searchwindow_buttonconfig'.$i,'','1') == 0){
								$dragstatus .= 'display: none;';
								$searchstyle .= 'display: block;margin-left:0;';
							}
							break;

						case 2:
							if($this->PFSAIssetControl('setup12_searchwindow_buttonconfig'.$i,'','1') == 0){
								$mapinfostatus .= 'display: none;';
							}
							break;
						
					}
				}


				if ($mapinfostatus == 'style="display: none;' && $dragstatus == 'style="display: none;') {
					$searchstyle .= 'display: none;';
				}
				if ($generalbradius == 1) {
					if ($mapinfostatus == 'style="' && $dragstatus == 'style="display: none;') {
						$searchstyle .= 'border-top-left-radius:'.$border_radius_level.'px!important;';
						$mapinfostatus .= 'border-top-right-radius:'.$border_radius_level.'px!important;border-top-right-radius:'.$border_radius_level.'px!important;';
					}elseif ($mapinfostatus == 'style="display: none;' && $dragstatus == 'style="display: none;') {
						$togglesectionstatus .= 'border-top-left-radius:'.$border_radius_level.'px!important;border-top-right-radius:'.$border_radius_level.'px!important;';
					}elseif ($mapinfostatus == 'style="display: none;' && $dragstatus == 'style="') {
						$dragstatus .= 'border-top-left-radius:'.$border_radius_level.'px!important;';
						$searchstyle .= 'border-top-right-radius:'.$border_radius_level.'px!important;';
					}elseif ($mapinfostatus == 'style="' && $dragstatus == 'style="'){
						$dragstatus .= 'border-top-left-radius:'.$border_radius_level.'px!important;';
						$mapinfostatus .= 'border-top-right-radius:'.$border_radius_level.'px!important;';
					}elseif ($mapinfostatus == 'style="' && $dragstatus == 'style="display: none;'){
						$searchstyle .= 'border-top-left-radius:'.$border_radius_level.'px!important;';
						$mapinfostatus .= 'border-top-right-radius:'.$border_radius_level.'px!important;';
					}

				}

				$dragstatus .= '"';
				$mapinfostatus .= '"';
				$searchstyle .= '"';



				$setup12_searchwindow_startpositions = $this->PFSAIssetControl('setup12_searchwindow_startpositions','','1');

				if($setup12_searchwindow_startpositions == 1){
					$pfdraggablestyle = 'left:15px;right:auto;';
				}else{
					if ($mapnot_status == 1) {
						$pfdraggablestyle = 'right:15px;top:67px;';
					}else{
						$pfdraggablestyle = 'right:15px;top:auto;';
					}

				}
				if ($horizontalmode == 1) {
					$pfdraggablestyle = 'left:0!important;right:0!important';
				}
				$stp28_mmenu_menulocation = esc_attr(PFSAIssetControl('stp28_mmenu_menulocation','','left'));

		  		ob_start();
		  		?>
			        <div class="pf-container psearchdraggable" data-direction="<?php echo sanitize_text_field( $stp28_mmenu_menulocation );?>"><div class="pf-row"><div class="col-lg-12">
					<div id="pfsearch-draggable" class="pfsearch-draggable-window<?php echo $horizontalmode_style;?> ui-widget-content" style="<?php echo $pfdraggablestyle;?>">
			          <?php if ($horizontalmode == 0) {?>
			          <div class="pfsearch-header">
			          	<ul class="pftogglemenulist clearfix">
			            	<li class="pftoggle-move" title="<?php echo esc_html__('Drag this window.', 'pointfindercoreelements');?>" <?php echo $dragstatus?>><i class="fas fa-arrows-alt"></i></li>
			                <li class="pftoggle-search" data-pf-icon1="fa-search-minus" data-pf-icon2="fa-search-plus" data-pf-content="search" title="<?php echo esc_html__('Search window.', 'pointfindercoreelements');?>" <?php echo $searchstyle?>><i class="fas fa-search-minus"></i></li>
			                <li class="pftoggle-itemlist" data-pf-icon1="fa-info-circle" data-pf-icon2="fa-times-circle" data-pf-content="itemlist" title="<?php echo esc_html__('Display map info.', 'pointfindercoreelements');?>" <?php echo $mapinfostatus?>><i class="fas fa-info-circle"></i></li>
			               
			            </ul>
			          </div>
			          <?php
			      		}
			      		if ($device_check) {
			      			$stp_mxheight = 'max-height:'.($setup5_mapsettings_height - 90).'px;';
			      		}else{
			      			$stp_mxheight = '';
			      		}
			          /**
			          *Start: Search Form
			          **/
			          ?>
				          <form id="pointfinder-search-form">
				          	<div class="pfsearch-content golden-forms pfdragcontent<?php echo $horizontalmode_style2;?>" style="<?php echo $stp_mxheight;?><?php echo $togglesectionstatus;?>">
					          	<div class="pfsearchformerrors">
					          		<ul></ul>
					           		<a class="button pfsearch-err-button"><i class="fas fa-times"></i> <?php echo esc_html__('CLOSE','pointfindercoreelements')?></a>
					          	</div>
					          	<?php
								$setup1s_slides = $this->PFSAIssetControl('setup1s_slides','','');

								if(is_array($setup1s_slides)){

									$PFListSF = new PF_SF_Val();
									foreach ($setup1s_slides as &$value) {

										$PFListSF->GetValue($value['title'],$value['url'],$value['select'],0,array(),$hormode);

									}

									/*Get Listing Type Item Slug*/
				                    $fltf = $this->pointfinder_find_requestedfields('pointfinderltypes');
				                    $features_field = $this->pointfinder_find_requestedfields('pointfinderfeatures');
				                    $itemtypes_field = $this->pointfinder_find_requestedfields('pointfinderitypes');
				                    $conditions_field = $this->pointfinder_find_requestedfields('pointfinderconditions');

				                    $stp_syncs_it = $this->PFSAIssetControl('stp_syncs_it','',1);
									$stp_syncs_co = $this->PFSAIssetControl('stp_syncs_co','',1);
									$setup4_sbf_c1 = $this->PFSAIssetControl('setup4_sbf_c1','',1);

									$second_request_process = false;
									$second_request_text = "{features:'',itemtypes:'',conditions:''};";
									$multiple_itemtypes = $multiple_features = $multiple_conditions =  '';

									if (!empty($features_field) || !empty($itemtypes_field) || !empty($conditions_field)) {
										$second_request_process = true;
										$second_request_text = '{';


										if (!empty($features_field) && $setup4_sbf_c1 == 0) {
											$second_request_text .= "features:'$features_field'";
											$multiple_features = $this->PFSFIssetControl('setupsearchfields_'.$features_field.'_multiple','','0');
										}
										if (!empty($itemtypes_field) && $stp_syncs_it == 0) {
											if (!empty($features_field) && $setup4_sbf_c1 == 0) {
												$second_request_text .= ",";
											}
											$second_request_text .= "itemtypes:'$itemtypes_field'";
											$multiple_itemtypes = $this->PFSFIssetControl('setupsearchfields_'.$itemtypes_field.'_multiple','','0');
										}
										if (!empty($conditions_field) && $stp_syncs_co == 0) {
											if ((!empty($features_field) && $setup4_sbf_c1 == 0) || (!empty($itemtypes_field) && $stp_syncs_it == 0)) {
												$second_request_text .= ",";
											}
											$second_request_text .= "conditions:'$conditions_field'";
											$multiple_conditions = $this->PFSFIssetControl('setupsearchfields_'.$conditions_field.'_multiple','','0');
										}

										if (!empty($multiple_itemtypes)) {
											if (!empty($second_request_text)) {
												$second_request_text .= ",";
											}
											$second_request_text .= "mit:'1'";
										}

										if (!empty($multiple_features)) {
											if (!empty($second_request_text)) {
												$second_request_text .= ",";
											}
											$second_request_text .= "mfe:'1'";
										}

										if (!empty($multiple_conditions)) {
											if (!empty($second_request_text)) {
												$second_request_text .= ",";
											}
											$second_request_text .= "mco:'1'";
										}


										$second_request_text .= '};';
									}


									echo $PFListSF->FieldOutput;
									echo '<div id="pfsearchsubvalues" '.$horizontalmode_style3.'></div>';
									if ($horizontalmode == 1) {
										echo '<div class="colhorsearch colhorseachbutton">';
									}
									echo '<a class="button pfsearch" id="pf-search-button">'.esc_html__('FILTER POINTS', 'pointfindercoreelements').'</a><a class="button pfreset" id="pf-resetfilters-button">'.esc_html__('RESET', 'pointfindercoreelements').'</a>';
									if ($horizontalmode == 1) {
										echo '</div>';
									}
									$script_output = '';
									$script_output .= '
									(function($) {
										"use strict";
										$.pffieldsids = '.$second_request_text.'
										$.pfsliderdefaults = {};$.pfsliderdefaults.fields = Array();
										$(function(){

										'.$PFListSF->ScriptOutput;
										$script_output .= 'var pfsearchformerrors = $(".pfsearchformerrors");
											$("#pointfinder-search-form").validate({
												  debug:false,
												  onfocus: false,
												  onfocusout: false,
												  onkeyup: false,
												  rules:{'.$PFListSF->VSORules.'},messages:{'.$PFListSF->VSOMessages.'},
												  ignore: ".select2-input, .select2-focusser, .pfignorevalidation",
												  validClass: "pfvalid",
												  errorClass: "pfnotvalid pfaddnotvalidicon",
												  errorElement: "li",
												  errorContainer: pfsearchformerrors,
												  errorLabelContainer: $("ul", pfsearchformerrors),
												  invalidHandler: function(event, validator) {
													var errors = validator.numberOfInvalids();
													if (errors) {
														pfsearchformerrors.show("slide",{direction : "up"},100);
														$(".pfsearch-err-button").on("click",function(){
															pfsearchformerrors.hide("slide",{direction : "up"},100);
															return false;
														});
													}else{
														pfsearchformerrors.hide("fade",300);
													}
												  }
											});';

											if ($horizontalmode == 1 && $this->PFSAIssetControl('as_hormode_close','','0') == 1) {
												$script_output .= '
													$( ".pfsearch-draggable-full" ).toggle( "slide",{direction:"up",mode:"hide"},function(){
														$(".pfsopenclose").fadeToggle("fast");
														$(".pfsopenclose2").fadeToggle("fast");
													});
												';
											}

											

				                        if ($fltf != 'none') {
				                        	$as_mobile_dropdowns = $this->PFSAIssetControl('as_mobile_dropdowns','','0');

											if ($as_mobile_dropdowns == 1) {
												$script_output .= '
												$(function(){
						                            $("#'.$fltf.'").change(function(e) {

						                              $.PFGetSubItems($("#'.$fltf.'").val(),"",0,'.$hormode.');
						                              ';
						                              if ($second_request_process) {
						                              	$script_output .= '$.PFRenewFeatures($("#'.$fltf.'").val(),"'.$second_request_text.'");';
						                              }
						                              $script_output .= '
						                            });
						                            $(document).one("ready",function(){
						                                if ($("#'.$fltf.'" ).val() !== 0) {
						                                   $.PFGetSubItems($("#'.$fltf.'" ).val(),"",0,'.$hormode.');
						                                   ';
							                               if ($second_request_process) {
							                              	 $script_output .= '$.PFRenewFeatures($("#'.$fltf.'").val(),"'.$second_request_text.'");';
							                               }
							                               $script_output .= '
						                                }
						                                setTimeout(function(){
						                                	$(".select2-container" ).attr("title","");
						                                	$("#'.$fltf.'" ).attr("title","");
						                                },300);
						                            });
					                            });
					                            ';
											}else{
												$script_output .= '
					                            $("#'.$fltf.'" ).change(function(e) {

					                              $.PFGetSubItems($("#'.$fltf.'" ).val(),"",0,'.$hormode.');
					                              ';
					                              if ($second_request_process) {
					                              	$script_output .= '$.PFRenewFeatures($("#'.$fltf.'" ).val(),"'.$second_request_text.'");';
					                              }
					                              $script_output .= '
					                            });
					                            $(document).one("ready",function(){

					                                if ($("#'.$fltf.'" ).val() !== 0) {
					                                   $.PFGetSubItems($("#'.$fltf.'" ).val(),"",0,'.$hormode.');
					                                   ';
						                              if ($second_request_process) {
						                              	$script_output .= '$.PFRenewFeatures($("#'.$fltf.'" ).val(),"'.$second_request_text.'");';
						                              }
						                              $script_output .= '
					                                }
					                                setTimeout(function(){
					                                	$(".select2-container" ).attr("title","");
					                                	$("#'.$fltf.'" ).attr("title","")
					                                },300);
					                            });

					                            ';
											}

				                        }
				                        $script_output .= '
										});'.$PFListSF->ScriptOutputDocReady;
									}
									$script_output .= '

									})(jQuery);
									';
									
									unset($PFListSF);
							  ?>
				            </div>
				          </form><!-- // pointfinder-search-form close-->
			          <?php
			          /**
			          *End: Search Form
			          **/
			          ?>
			          <div class="pfitemlist-content pfdragcontent">
			          <?php
			          global $pointfindertheme_option;
					  $setup12_searchwindow_mapinfotext = ($pointfindertheme_option['setup12_searchwindow_mapinfotext'])?wp_kses_post($pointfindertheme_option['setup12_searchwindow_mapinfotext']):'';
					  $setup12_searchwindow_mapinfotext = apply_filters( 'the_content', $setup12_searchwindow_mapinfotext );
					  echo $setup12_searchwindow_mapinfotext;
					  ?>

			          </div>
			  
			         <?php if ($horizontalmode == 1) {?>
			         <a class="pfsopenclose hidden-xs"><i class="fas fa-angle-up"></i></a>
					 <?php }?>
			        </div>
			        </div></div></div>
			       <?php if ($horizontalmode == 1) {?>
			        <a class="pfsopenclose2 hidden-xs"><i class="fas fa-search"></i> <?php echo esc_html__('SEARCH','pointfindercoreelements');?></a>

					<?php }?>
			    <!--  / Search Container -->
			    <?php
		  		$map_search_content = ob_get_contents();
		  		ob_end_clean();
		  		}


		  		

		  		

				if($mapsearch_status == 1 ){
					$output .= $map_search_content;
				}

				ob_start();
				?>

			
				<div id="wpf-map-container">

			    	<div class="pfmaploading pfloadingimg"></div>


			        <?php

						$setup7_geolocation_status = $this->PFSAIssetControl('setup7_geolocation_status','','0');


						if (empty($setup42_mheight)) {
							$setup42_mheight = $this->PFSAIssetControl('setup42_mheight','height','350');
						}
						
						$setup42_mheight = str_replace('px', '', $setup42_mheight);

						if (empty($setup42_theight)) {
							$setup42_theight = $this->PFSAIssetControl('setup42_theight','height','400');
						}
						
						$setup42_theight = str_replace('px', '', $setup42_theight);

						$we_special_key = $wemap_here_appid = $wemap_here_appcode = '';
							    	
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
						
						$setup7_geolocation_distance = $this->PFSAIssetControl('setup7_geolocation_distance','',10);
						$setup7_geolocation_distance_unit = $this->PFSAIssetControl('setup7_geolocation_distance_unit','',"km");
						$setup7_geolocation_hideinfo = $this->PFSAIssetControl('setup7_geolocation_hideinfo','',1);
						$setup6_clustersettings_status = $this->PFSAIssetControl('setup6_clustersettings_status','',1);
						$stp6_crad = $this->PFSAIssetControl('stp6_crad','',100);
						$setup10_infowindow_height = $this->PFSAIssetControl('setup10_infowindow_height','','136');
						$setup10_infowindow_width = $this->PFSAIssetControl('setup10_infowindow_width','','350');
						$s10_iw_w_m = $this->PFSAIssetControl('s10_iw_w_m','','184');
						$s10_iw_h_m = $this->PFSAIssetControl('s10_iw_h_m','','136');
					?>

			    	<div id="pfdirectorymap"
			    	data-mode="topmap" 
			    	data-height="<?php echo $setup5_mapsettings_height;?>" 
				    data-theight="<?php echo $setup42_theight;?>" 
				    data-mheight="<?php echo $setup42_mheight;?>" 
				    data-lat="<?php echo $setup5_mapsettings_lat;?>" 
		    		data-lng="<?php echo $setup5_mapsettings_lng;?>" 
		    		data-zoom="<?php echo $setup5_mapsettings_zoom;?>" 
		    		data-zoomm="<?php echo $setup5_mapsettings_zoom_mobile;?>" 
		    		data-zoommx="18" 
		    		data-mtype="<?php echo $stp5_mapty;?>" 
		    		data-key="<?php echo $we_special_key;?>" 
		    		data-hereappid="<?php echo $wemap_here_appid;?>" 
					data-hereappcode="<?php echo $wemap_here_appcode;?>" 
					data-spl="<?php echo $setup8_pointsettings_limit;?>" 
					data-splo="<?php echo $setup8_pointsettings_order;?>" 
					data-splob="<?php echo $setup8_pointsettings_orderby;?>" 
					data-ppp="<?php echo $setup8_pointsettings_limit;?>" 
					data-paged="<?php echo $paged;?>" 
					data-order="<?php echo $setup8_pointsettings_order;?>" 
					data-orderby="<?php echo $setup8_pointsettings_orderby;?>" 
					data-glstatus="<?php echo $setup7_geolocation_status;?>"
					data-gldistance="<?php echo $setup7_geolocation_distance;?>" 
					data-gldistanceunit="<?php echo $setup7_geolocation_distance_unit;?>" 
					data-gldistancepopup="<?php echo $setup7_geolocation_hideinfo;?>" 
					data-found=""  
					data-cluster="<?php echo $setup6_clustersettings_status;?>" 
					data-clusterrad="<?php echo $stp6_crad;?>" 
					data-iheight="<?php echo $setup10_infowindow_height;?>" 
					data-iwidth="<?php echo $setup10_infowindow_width;?>" 
					data-imheight="<?php echo $s10_iw_h_m;?>" 
					data-imwidth="<?php echo $s10_iw_w_m;?>" 
					data-ttstatus="<?php echo $tooltipstatus;?>" 
					></div>
			    	<?php if ($mapnot_status == 1) {?>
			        <div class="pfnotificationwindow">
			            <span class="pfnottext"></span>

			        </div>
			        <a class="pf-err-button pfnot-err-button" id="pfnot-err-button">
			           	<i class="fas fa-times"></i>
			        </a>
			        <a class="pf-err-button pfnot-err-button pfnot-err-button-menu" id="pfnot-err-button-menu">
			        	<i class="fas fa-info"></i>
			        </a>
			        <?php }?>
			    </div>

		    	<?php
		    	
				$serialized_sdata = $data_values = $fltf_get = '';

				$coordval = (isset($_GET['pointfinder_google_search_coord']))?esc_attr($_GET['pointfinder_google_search_coord']):'';
				$fltf = $this->pointfinder_find_requestedfields('pointfinderltypes');

				
				if(isset($_GET['serialized'])){
					$serialized_sdata = base64_encode(maybe_serialize($_GET));
				}
				$data_values .= ' data-sdata="'.$serialized_sdata.'"';	

				if(!empty($ne)){
					$data_values .= ' data-ne="'.$ne.'"';
					$data_values .= ' data-sw="'.$sw.'"';
					$data_values .= ' data-ne2="'.$ne2.'"';
					$data_values .= ' data-sw2="'.$sw2.'"';
				}
				$data_values .= ' data-lt="'.$this->PFEX_extract_type_ig($listingtype).'"';
				$data_values .= ' data-lc="'.$this->PFEX_extract_type_ig($locationtype).'"';
				$data_values .= ' data-co="'.$this->PFEX_extract_type_ig($conditions).'"';
				$data_values .= ' data-it="'.$this->PFEX_extract_type_ig($itemtype).'"';
				$data_values .= ' data-fe="'.$this->PFEX_extract_type_ig($features).'"';

				//$data_values .= ' data-csauto="'.$csauto.'"';
				$data_values .= ' data-fltf="'.$fltf.'"';
				$data_values .= ' data-coordval="'.$coordval.'"';

				if ($this->PFSAIssetControl('setup5_mapsettings_mapautoopen','','0') == 1) {
					$data_values .= ' data-autoopen="1"';
				}else{
					$data_values .= ' data-autoopen="0"';
				}

				if (isset($_GET[$fltf])) {
	        		$fltf_get = intval($_GET[$fltf]);
	        	}

				$data_values .= ' data-fltfget="'.$fltf_get.'"';
			?>

		    <div class="pfsearchresults-container" <?php echo $data_values;?>></div>


			<?php

	    	/*Point settings*/
			$setup10_infowindow_height = $this->PFSAIssetControl('setup10_infowindow_height','','136');
			$setup10_infowindow_width = $this->PFSAIssetControl('setup10_infowindow_width','','350');

			if($setup10_infowindow_height != 136){ $heightbetweenitems = $setup10_infowindow_height - 136;}else{$heightbetweenitems = 0;}
			if($setup10_infowindow_width != 350){
				$widthbetweenitems = (($setup10_infowindow_width - 350)/2);
			}else{
				$widthbetweenitems = 0;
			}
		
	    	$s10_iw_w_m = $this->PFSAIssetControl('s10_iw_w_m','','184');
			$s10_iw_h_m = $this->PFSAIssetControl('s10_iw_h_m','','136');
			if($s10_iw_h_m != 136){ $heightbetweenitems2 = $s10_iw_h_m - 136;}else{$heightbetweenitems2 = 0;}
			if($s10_iw_w_m != 184){ $widthbetweenitems2 = (($s10_iw_w_m - 184)/2);}else{$widthbetweenitems2 = -6;}

			if (empty($script_output)) {
				$script_output = '';
			}
			$script_output .= 'var pficoncategories = [];';
			$script_output .= $this->pf_get_default_cat_images();

			$output .= ob_get_contents();
	  		ob_end_clean();

			wp_add_inline_script('theme-pfdirectorymap',$script_output,'after');

			echo $output;
		}
	
	}

	private function pointfinder_get_category_points($params = array()){

		$defaults = array( 
	        'pf_get_term_detail_idm' => '',
	        'pf_get_term_detail_idm_parent' => '',
	        'listing_meta' => '',
	        'cpoint_type' => 0,
			'cpoint_icontype' => 1,
			'cpoint_iconsize' => 'middle',
			'cpoint_iconname' => '',
			'cpoint_bgcolor' => '#b00000',
			'dlang' => '',
			'clang' => '',
			'st8_npsys' => 0
	    );

		$params = array_merge($defaults, $params);

		$listing_meta = $params['listing_meta'];
	   
		$pf_get_term_detail_id = $pf_get_term_detail_idxx = $params['pf_get_term_detail_idm'];
		$pf_get_term_detail_idm_parent = $params['pf_get_term_detail_idm_parent'];
		
		$output_data = $pf_get_term_detail_id_output = '';

		if(class_exists('SitePress')) {
			$pf_get_term_detail_id = apply_filters( 'wpml_object_id',$params['pf_get_term_detail_idm'],'pointfinderltypes',true,$params['dlang']);
			$pf_get_term_detail_idm_parent = apply_filters( 'wpml_object_id',$params['pf_get_term_detail_idm_parent'],'pointfinderltypes',true,$params['dlang']);
			$pf_get_term_detail_idxx = apply_filters( 'wpml_object_id',$params['pf_get_term_detail_idm'],'pointfinderltypes',true,$params['clang']);
		}

		if ($params['st8_npsys'] == 1) {
			$run_parent_check = false;

			if(isset($listing_meta[$pf_get_term_detail_id])){
				$slisting_meta = $listing_meta[$pf_get_term_detail_id];
				$icon_type = (isset($slisting_meta['cpoint_type']))?$slisting_meta['cpoint_type']:0;
				if (empty($icon_type)) {
					$run_parent_check = true;
				}else{
					$run_parent_check = false;
					$pf_get_term_detail_id_output = $pf_get_term_detail_id;
				}
			}else{
				$slisting_meta = '';
				$run_parent_check = true;
			}

			/* If 2nd level */
			if ($run_parent_check && !empty($pf_get_term_detail_idm_parent)) {
				if(isset($listing_meta[$pf_get_term_detail_idm_parent])){
					$slisting_meta = $listing_meta[$pf_get_term_detail_idm_parent];
					$icon_type = (isset($slisting_meta['cpoint_type']))?$slisting_meta['cpoint_type']:0;
					if (empty($icon_type)) {
						$run_parent_check = true;
					}else{
						$run_parent_check = false;
						$pf_get_term_detail_id_output = $pf_get_term_detail_idm_parent;
					}

				}else{
					$slisting_meta = '';
					$run_parent_check = true;
				}
			}

		
			/* If 3rd level */
			if ($run_parent_check && !empty($pf_get_term_detail_idm_parent)) {
				$top_most_parent = $this->pf_get_term_top_most_parent($pf_get_term_detail_id,"pointfinderltypes");
				$top_most_parent = (isset($top_most_parent['parent']))?$top_most_parent['parent']:'';
				
				if(isset($listing_meta[$top_most_parent])){
					$slisting_meta = $listing_meta[$top_most_parent];
					$pf_get_term_detail_id_output = $top_most_parent;
				}else{
					$slisting_meta = '';
				}
				$run_parent_check = false;
			}

			
			
			if (!empty($slisting_meta)) {

				$icon_type = (isset($slisting_meta['cpoint_type']))?$slisting_meta['cpoint_type']:0;
				$icon_layout_type = (isset($slisting_meta['cpoint_icontype']))?$slisting_meta['cpoint_icontype']:1;
				$icon_size = (isset($slisting_meta['cpoint_iconsize']))?$slisting_meta['cpoint_iconsize']:'middle';
				$icon_bg_color = (isset($slisting_meta['cpoint_bgcolor']))?$slisting_meta['cpoint_bgcolor']:'#b00000';
				$icon_name = (isset($slisting_meta['cpoint_iconname']))?$slisting_meta['cpoint_iconname']:'';
				$icon_namefs = (isset($slisting_meta['cpoint_iconnamefs']))?$slisting_meta['cpoint_iconnamefs']:'';
				if ($icon_type == 2) {
					$arrow_text = ($icon_layout_type == 2)? '<div class=\'pf-pinarrow\' style=\'border-color: '.$icon_bg_color.' transparent transparent transparent;\'></div>': '';

					$output_data .= 'pficoncategories["pfcat'.$pf_get_term_detail_id.'"] =';
					$output_data .= ' "<div ';
					$output_data .= 'class=\'pfcat'.$pf_get_term_detail_id_output.'-mapicon pf-map-pin-'.$icon_layout_type.' pf-map-pin-'.$icon_layout_type.'-'.$icon_size.'\'';
					$output_data .= '>';
					if (!empty($icon_namefs)) {
	                  $output_data .= '<i class=\''.$icon_namefs.'\'></i></div>'.$arrow_text.'";';
	                }else{
	                  $output_data .= '<i class=\''.$icon_name.'\'></i></div>'.$arrow_text.'";';
	                }
				}else{
					$output_data .= 'pficoncategories["pfcat'.$pf_get_term_detail_id.'"] =';
					$output_data .= ' "<div ';
					$output_data .= 'class=\'pfcat'.$pf_get_term_detail_id_output.'-mapicon\'';
					$output_data .= '>';
					$output_data .= '</div>";'.PHP_EOL;
				}
			}else{

				/* Check parent term has settings */

				
				if ($params['cpoint_type'] == 0) {
					$arrow_text = ($params['cpoint_icontype'] == 2)? '<div class=\'pf-pinarrow\' style=\'border-color: '.$params['cpoint_bgcolor'].' transparent transparent transparent;\'></div>': '';

					$output_data .= 'pficoncategories["pfcat'.$pf_get_term_detail_id.'"] =';
					$output_data .= ' "<div ';
					$output_data .= 'class=\'pfcatdefault-mapicon pf-map-pin-'.$params['cpoint_icontype'].' pf-map-pin-'.$params['cpoint_icontype'].'-'.$params['cpoint_iconsize'].'\'';
					$output_data .= ' >';
					$output_data .= '<i class=\''.$params['cpoint_iconname'].'\' ></i></div>'.$arrow_text.'";'.PHP_EOL;
				}else{
					$output_data .= 'pficoncategories["pfcat'.$pf_get_term_detail_id.'"] =';
					$output_data .= ' "<div ';
					$output_data .= 'class=\'pfcatdefault-mapicon\'';
					$output_data .= '>';
					$output_data .= '</div>";'.PHP_EOL;
				}
			}
				
		}else{
			
			$icon_type = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_type','','0');

			$icon_bg_image = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_bgimage','','0');

			$icon_layout_type = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_icontype','','1');
			$icon_name = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_iconname','','');
			$icon_size = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_iconsize','','middle');
			$icon_bg_color = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_bgcolor','','#b00000');
			
			$arrow_text = ($icon_layout_type == 2)? '<div class=\'pf-pinarrow\' style=\'border-color: '.$icon_bg_color.' transparent transparent transparent;\'></div>': '';

			if ($icon_type == 0 && empty($icon_bg_image)) {

				$output_data .= 'pficoncategories["pfcat'.$pf_get_term_detail_id.'"] =';
				$output_data .= ' "<div ';
				$output_data .= 'class=\'pfcat'.$pf_get_term_detail_id.'-mapicon pf-map-pin-'.$icon_layout_type.' pf-map-pin-'.$icon_layout_type.'-'.$icon_size.'\'';
				$output_data .= ' >';
				$output_data .= '<i class=\''.$icon_name.'\' ></i></div>";'.PHP_EOL;
			
			}elseif ($icon_type != 0 && !empty($icon_bg_image)){

				$height_calculated = $icon_bg_image['height'];
				$width_calculated = $icon_bg_image['width'];

				$output_data .= 'pficoncategories["pfcat'.$pf_get_term_detail_id.'"] =';
				$output_data .= ' "<div ';
				$output_data .= 'class=\'pf-map-pin-x\' ';
				$output_data .= 'style=\'background-image:url('.$icon_bg_image['url'].');opacity:1; background-size:'.$width_calculated.'px '.$height_calculated.'px; width:'.$width_calculated.'px; height:'.$height_calculated.'px;\'';
				$output_data .= ' >';
				$output_data .= '</div>";'.PHP_EOL;
			
			}else{

				$output_data .= 'pficoncategories["pfcat'.$pf_get_term_detail_id.'"] =';
				$output_data .= ' "<div ';
				$output_data .= 'class=\'pfcat'.$pf_get_term_detail_id.'-mapicon pf-map-pin-'.$icon_layout_type.' pf-map-pin-'.$icon_layout_type.'-'.$icon_size.'\'';
				$output_data .= ' >';
				$output_data .= '<i class=\''.$icon_name.'\' ></i></div>'.$arrow_text.'";'.PHP_EOL;

			}
		}

		return $output_data;
	}


	private function pf_get_default_cat_images($pflang = ''){
							
		$wpflistdata = '';

		/**
		*Start: Default Point Variables
		**/
			if ($this->PFASSIssetControl('st8_npsys','',0) != 1) {
				$icon_layout_type = $this->PFPFIssetControl('pscp_pfdefaultcat_icontype','','1');
				$icon_name = $this->PFPFIssetControl('pscp_pfdefaultcat_iconname','','');
				$icon_size = $this->PFPFIssetControl('pscp_pfdefaultcat_iconsize','','middle');
				$icon_bg_color = $this->PFPFIssetControl('pscp_pfdefaultcat_bgcolor','','#b00000');

				$arrow_text = ($icon_layout_type == 2)? '<div class=\'pf-pinarrow\' style=\'border-color: '.$icon_bg_color.' transparent transparent transparent;\'></div>': '';

				$wpflistdata .= 'pficoncategories["pfcatdefault"] =';
				$wpflistdata .= ' "<div ';
				$wpflistdata .= 'class=\'pfcatdefault-mapicon pf-map-pin-'.$icon_layout_type.' pf-map-pin-'.$icon_layout_type.'-'.$icon_size.'\'';
				$wpflistdata .= ' >';
				$wpflistdata .= '<i class=\''.$icon_name.'\' ></i></div>'.$arrow_text.'";'.PHP_EOL;
			}else{
				$icon_layout_type = $this->PFASSIssetControl('cpoint_icontype','',1);
				$icon_name = $this->PFASSIssetControl('cpoint_iconname','','');
				$icon_namefs = $this->PFASSIssetControl('cpoint_iconnamefs','','');
				$icon_size = $this->PFASSIssetControl('cpoint_iconsize','','middle');
				$icon_bg_color = $this->PFASSIssetControl('cpoint_bgcolor','','#b00000');

				$arrow_text = ($icon_layout_type == 2)? '<div class=\'pf-pinarrow\' style=\'border-color: '.$icon_bg_color.' transparent transparent transparent;\'></div>': '';

				$wpflistdata .= 'pficoncategories["pfcatdefault"] =';
				$wpflistdata .= ' "<div ';
				$wpflistdata .= 'class=\'pfcatdefault-mapicon pf-map-pin-'.$icon_layout_type.' pf-map-pin-'.$icon_layout_type.'-'.$icon_size.'\'';
				$wpflistdata .= ' >';
				if (!empty($icon_namefs)) {
					$wpflistdata .= '<i class=\''.$icon_namefs.'\' ></i></div>'.$arrow_text.'";'.PHP_EOL;
				} else {
					$wpflistdata .= '<i class=\''.$icon_name.'\' ></i></div>'.$arrow_text.'";'.PHP_EOL;
				}
				
			}
		/**
		*End: Default Point Variables
		**/



		/**
		*Start: Cat Point Variables
		**/
			
			$pf_get_term_details = get_terms('pointfinderltypes',array('hide_empty'=>false)); 


			if(count($pf_get_term_details) > 0){
				$default_language = $current_language = $listing_meta = $cpoint_type = $cpoint_icontype = $cpoint_iconsize = $cpoint_iconname = $cpoint_bgcolor = '';
				
				if (class_exists('SitePress')) {
					$default_language = $this->PF_default_language();
					$current_language = $this->PF_current_language();
				}

				if ($this->PFASSIssetControl('st8_npsys','',0) == 1) {
					$listing_meta = get_option('pointfinderltypes_style_vars');
					$cpoint_type = $this->PFASSIssetControl('cpoint_type','',0);
					$cpoint_icontype = $this->PFASSIssetControl('cpoint_icontype','',1);
					$cpoint_iconsize = $this->PFASSIssetControl('cpoint_iconsize','','middle');
					$cpoint_iconname = $this->PFASSIssetControl('cpoint_iconname','','');
					$cpoint_bgcolor = $this->PFASSIssetControl('cpoint_bgcolor','','#b00000');
				}
				$st8_npsys = $this->PFASSIssetControl('st8_npsys','',0);

			    if ($st8_npsys == 1) {
			    	foreach ( $pf_get_term_details as $pf_get_term_detail ) {

					$wpflistdata .= $this->pointfinder_get_category_points(
						array(
							'pf_get_term_detail_idm' => $pf_get_term_detail->term_id,
							'pf_get_term_detail_idm_parent' => $pf_get_term_detail->parent,
					        'listing_meta' => $listing_meta,
					        'cpoint_type' => $cpoint_type,
							'cpoint_icontype' => $cpoint_icontype,
							'cpoint_iconsize' => $cpoint_iconsize,
							'cpoint_iconname' => $cpoint_iconname,
							'cpoint_bgcolor' => $cpoint_bgcolor,
							'dlang' => $default_language,
							'clang' => $current_language,
							'st8_npsys' => $st8_npsys
						));

					}
			    }else{
			    	foreach ( $pf_get_term_details as $pf_get_term_detail ) {
						if ($pf_get_term_detail->parent == 0) {
							
							$wpflistdata .= $this->pointfinder_get_category_points(
								array(
								'pf_get_term_detail_idm' => $pf_get_term_detail->term_id,
						        'listing_meta' => $listing_meta,
						        'cpoint_type' => $cpoint_type,
								'cpoint_icontype' => $cpoint_icontype,
								'cpoint_iconsize' => $cpoint_iconsize,
								'cpoint_iconname' => $cpoint_iconname,
								'cpoint_bgcolor' => $cpoint_bgcolor,
								'dlang' => $default_language,
								'clang' => $current_language,
								'st8_npsys' => $st8_npsys
								));

							$pf_get_term_details_sub = get_terms('pointfinderltypes',array('hide_empty'=>false,'parent'=>$pf_get_term_detail->term_id)); 

							foreach ($pf_get_term_details_sub as $pf_get_term_detail_sub) {
								$wpflistdata .= $this->pointfinder_get_category_points(
									array(
										'pf_get_term_detail_idm' => $pf_get_term_detail_sub->term_id,
								        'listing_meta' => $listing_meta,
								        'cpoint_type' => $cpoint_type,
										'cpoint_icontype' => $cpoint_icontype,
										'cpoint_iconsize' => $cpoint_iconsize,
										'cpoint_iconname' => $cpoint_iconname,
										'cpoint_bgcolor' => $cpoint_bgcolor,
										'dlang' => $default_language,
										'clang' => $current_language,
										'st8_npsys' => $st8_npsys
									));
							}

						}
						
					}
			    }
				
				/*
					Loop End from PF Custom Points
				*/

	
			}

		/**
		*End: Cat Point Variables
		**/

		return $wpflistdata;
		
	}

}
