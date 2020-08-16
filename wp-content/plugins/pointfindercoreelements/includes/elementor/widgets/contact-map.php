<?php
namespace PointFinderElementorSYS\Widgets;


use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use PointFinderElementorSYS\Helper;
use PointFinderCommonFunctions;
use PointFinderOptionFunctions;
use PointFinderWPMLFunctions;


if ( ! defined( 'ABSPATH' ) ) exit;

class PointFinder_Contact_Map extends Widget_Base {

	use PointFinderOptionFunctions;
	use PointFinderCommonFunctions;
	use PointFinderWPMLFunctions;


	public function show_in_panel() { return true; }

	public function get_keywords() { return [ 'pointfinder', 'contact map', 'map', 'contact' ]; }

	public function get_name() { return 'pointfindercontactmap'; }

	public function get_title() { return esc_html__( 'PF Contact Map', 'pointfindercoreelements' ); }

	public function get_icon() { return 'eicon-google-maps'; }

	public function get_categories() { return [ 'pointfinder_elements' ]; }


	public function get_script_depends() {
	    return [];
	}

	protected function _register_controls() {


		$this->start_controls_section(
			'contactmap_general',
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
			$repeater = new \Elementor\Repeater();
			$repeater->add_control(
				'cmap_icon',
				[
					'label' => __( 'Icon', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'fas fa-star',
						'library' => 'solid',
					],
				]
			);
			$repeater->add_control(
				'cmap_icolor',
				[
					'label' => esc_html__( 'Icon Color', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Scheme_Color::get_type(),
						'value' => \Elementor\Scheme_Color::COLOR_1,
					],
					'render_type' => 'ui'
				]
			);
			$repeater->add_control(
				'cmap_color',
				[
					'label' => esc_html__( 'Pointer Color', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Scheme_Color::get_type(),
						'value' => \Elementor\Scheme_Color::COLOR_1,
					],
					'render_type' => 'ui'
				]
			);
			$repeater->add_control(
				'cmap_ipos',
				[
					'label' => esc_html__( 'Pointer Size', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::CHOOSE,
					'options' => [
						'pfpsmall' => [
							'title' => esc_html__( 'Small', 'pointfindercoreelements' ),
							'icon' => 'fa fa-map-marker pfsmall',
						],
						'pfpnormal' => [
							'title' => esc_html__( 'Normal', 'pointfindercoreelements' ),
							'icon' => 'fa fa-map-marker pfnormal',
						],
						'pgplarge' => [
							'title' => esc_html__( 'Large', 'pointfindercoreelements' ),
							'icon' => 'fa fa-map-marker pflarge',
						],
					],
					'default' => 'pfpnormal',
					'toggle' => true,
					'render_type' => 'ui'
				]
			);

			$repeater->add_control(
				'cmap_lat',
				[
					'label' => esc_html__( 'Coordinate (Lat)', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => '40.73061',
					'render_type' => 'ui'
				]
			);
			$repeater->add_control(
				'cmap_lng',
				[
					'label' => esc_html__( 'Coordinate (Lng)', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => '-73.935242',
					'render_type' => 'ui'
				]
			);
			$repeater->add_control(
				'cmap_title',
				[
					'label' => esc_html__( 'Title', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'Default title', 'pointfindercoreelements' ),
					'placeholder' => esc_html__( 'Type your title here', 'pointfindercoreelements' ),
					'render_type' => 'ui',
				]
			);
			$repeater->add_control(
				'cmap_desc',
				[
					'label' => esc_html__( 'Title', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::WYSIWYG,
					'default' => esc_html__( 'Default title', 'pointfindercoreelements' ),
					'placeholder' => esc_html__( 'Type your title here', 'pointfindercoreelements' ),
					'render_type' => 'ui'
				]
			);
			
			$this->add_control(
				'cmap_points',
				[
					'label' => esc_html__( 'Map Points', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),
					'default' => [],
					'title_field' => '{{{ cmap_title }}}',
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
			'cmap_points' => isset($settings['cmap_points'])?$settings['cmap_points']:array()
		));
		


		if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
			echo '<div class="pf-sample-cmap" style="height:'.$setup5_mapsettings_height.'px;"><span>'.esc_html__( "Please click to preview mode to see contact map and components.", "pointfindercoreelements" ).'</span></div>';
		}else{
			$wpf_rndnum = rand(10,1000);
			if (empty($stp5_mapty)) {
				$stp5_mapty = $this->PFSAIssetControl('stp5_mapty','',1);
			}

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

			if (is_array($cmap_points)) {
				if ($cmap_points > 0) {
					$cmap_points = json_encode($cmap_points);
				}else{
					$cmap_points = '';
				}
			}else{
				$cmap_points = '';
			}

			
    		

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

			?>

	   
	    	<div id="pointfindercontactmap" class="wpf-map" style="height:<?php echo $setup5_mapsettings_height;?>px" 
	    		data-mapid="<?php echo $wpf_rndnum;?>" 
	    		data-lat="<?php echo $setup5_mapsettings_lat;?>" 
	    		data-lng="<?php echo $setup5_mapsettings_lng;?>" 
	    		data-zoom="<?php echo $setup5_mapsettings_zoom; ?>" 
	    		data-zoommx="18" 
	    		data-mtype="<?php echo $stp5_mapty;?>" 
	    		data-key="<?php echo $we_special_key;?>" 
	    		data-hereappid="<?php echo $wemap_here_appid;?>" 
				data-hereappcode="<?php echo $wemap_here_appcode;?>" 
				data-points='<?php echo $cmap_points;?>' 
				data-iconbg=""
	    		></div>
		  
			    
				
			<?php

		}
	
	}


}
