<?php
namespace PointFinderElementorSYS\Widgets;


use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use PointFinderElementorSYS\Helper;
use PointFinderOptionFunctions;

if ( ! defined( 'ABSPATH' ) ) exit;

class PointFinder_Logo_Carousel extends Widget_Base {

	use PointFinderOptionFunctions;

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		if(is_rtl()){
			wp_enqueue_script('owncarousel', 
			PFCOREELEMENTSURL . 'includes/elementor/assets/js/js.owncarousel.min.rtl.js', array('jquery'), '1.31',true);
		}else{
			wp_enqueue_script('owncarousel', 
			PFCOREELEMENTSURL . 'includes/elementor/assets/js/js.owncarousel.min.js', array('jquery'), '1.31',true);
		}

		wp_enqueue_script('pointfinder-elementor-logo-carousel', PFCOREELEMENTSURL . 'includes/elementor/assets/js/logocarousel.js', ['owncarousel'],'1.9.2',true);
    }

	public function show_in_panel() { return true; }

	public function get_keywords() { return [ 'pointfinder', 'logo carousel', 'carousel', 'image carousel' ]; }

	public function get_name() { return 'pointfinderlogocarousel'; }

	public function get_title() { return esc_html__( 'PF Logo Carousel', 'pointfindercoreelements' ); }

	public function get_icon() { return 'eicon-site-logo'; }

	public function get_categories() { return [ 'pointfinder_elements' ]; }


	public function get_script_depends() {
	    return ['owncarousel','pointfinder-elementor-logo-carousel'];
	}

	public function get_style_depends() {
      return [];
    }

	protected function _register_controls() {


		$this->start_controls_section(
			'logocarousel_general',
			[
				'label' => esc_html__( 'General', 'pointfindercoreelements' ),
				'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);
			
			$repeater = new \Elementor\Repeater();


			$repeater->add_control(
				'title',
				[
					'label' => esc_html__( 'Title', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'Type your title here', 'pointfindercoreelements' ),
					'render_type' => 'ui'
				]
			);
			$repeater->add_control(
				'onclick',
				[
					'label' => esc_html__( 'Action', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'link_no',
					'options' => [
						'custom_link'  => esc_html__( 'Open Custom Link', 'pointfindercoreelements' ),
						'link_image' => esc_html__( 'Modal Window(Image)', 'pointfindercoreelements' ),
						'link_no' => esc_html__( 'Do Nothing', 'pointfindercoreelements' )
					],
					'render_type' => 'ui'
				]
			);
			$repeater->add_control(
				'website_link',
				[
					'label' => __( 'Link', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::URL,
					'placeholder' => __( 'https://your-link.com', 'pointfindercoreelements' ),
					'show_external' => true,
					'default' => [
						'url' => '',
						'is_external' => true,
						'nofollow' => true,
					],
					'render_type' => 'ui',
					'condition' => [ 'onclick' => 'custom_link' ]
				]
			);

			$repeater->add_control(
				'image',
				[
					'label' => __( 'Choose Image', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::MEDIA,
					'default' => [
						'url' => \Elementor\Utils::get_placeholder_image_src(),
					],
					'render_type' => 'ui'
				]
			);

			$this->add_control(
				'list',
				[
					'label' => esc_html__( 'Logos', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),
					'default' => [],
					'title_field' => '{{{ title }}}',
					'render_type' => 'ui'
				]
			);

			$this->add_control(
				'logoamount',
				[
					'label' => esc_html__( 'Visible Logo Amount', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 2,
					'max' => 5,
					'step' => 1,
					'default' => 4,
				]
			);

			$this->add_control(
				'autoplay',
				[
					'label' => esc_html__( 'Slider Autoplay', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'ON', 'pointfindercoreelements' ),
					'label_off' => esc_html__( 'OFF', 'pointfindercoreelements' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);
			$this->add_control(
				'speed',
				[
					'label' => esc_html__( 'Slider Speed (second)', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 15,
					'step' => 1,
					'default' => 5,
					'condition' => [ 'autoplay' => 'yes' ]
				]
			);
			$this->add_control(
				'pagination',
				[
					'label' => esc_html__( 'Pagination Control', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'ON', 'pointfindercoreelements' ),
					'label_off' => esc_html__( 'OFF', 'pointfindercoreelements' ),
					'return_value' => 'yes',
					'default' => ''
				]
			);
			$this->add_control(
				'npagination',
				[
					'label' => esc_html__( 'Numeric Pagination', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'ON', 'pointfindercoreelements' ),
					'label_off' => esc_html__( 'OFF', 'pointfindercoreelements' ),
					'return_value' => 'yes',
					'default' => '',
					'condition' => [ 'pagination' => 'yes' ]
				]
			);
			$this->add_control(
				'prevnext',
				[
					'label' => esc_html__( 'Prev/Next Buttons', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'ON', 'pointfindercoreelements' ),
					'label_off' => esc_html__( 'OFF', 'pointfindercoreelements' ),
					'return_value' => 'yes',
					'default' => 'yes'
				]
			);
			$this->add_control(
				'borders',
				[
					'label' => esc_html__( 'Logo Borders', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'ON', 'pointfindercoreelements' ),
					'label_off' => esc_html__( 'OFF', 'pointfindercoreelements' ),
					'return_value' => 'yes',
					'default' => 'yes'
				]
			);
			$this->add_control(
				'autocrop',
				[
					'label' => esc_html__( 'Auto Image Crop', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'ON', 'pointfindercoreelements' ),
					'label_off' => esc_html__( 'OFF', 'pointfindercoreelements' ),
					'return_value' => 'yes',
					'default' => 'yes'
				]
			);
			$this->add_control(
				'customsize',
				[
					'label' => esc_html__("Custom Size (Optional)", "pointfindercoreelements"),
					'type' => \Elementor\Controls_Manager::TEXT,
					"description" => esc_html__("Ex: 300x200  | Custom size value (Optional). Please leave blank for auto resize. (in px)", "pointfindercoreelements")
				]
			);
			
		$this->end_controls_section();

	}

	

	protected function render() {

		$settings = $this->get_settings_for_display();

		extract(
			array(
				'list' => isset($settings['list'])?$settings['list']:array(),
				'logoamount' => isset($settings['logoamount'])?$settings['logoamount']:4,
				'autoplay' => isset($settings['autoplay'])?$settings['autoplay']:'yes',
				'speed' => isset($settings['speed'])?$settings['speed']:5,
				'pagination' => isset($settings['pagination'])?$settings['pagination']:'',
				'npagination' => isset($settings['npagination'])?$settings['npagination']:'',
				'prevnext' => isset($settings['prevnext'])?$settings['prevnext']:'yes',
				'borders' => isset($settings['borders'])?$settings['borders']:'yes',
				'autocrop' => isset($settings['autocrop'])?$settings['autocrop']:'yes',
				'customsize' => isset($settings['customsize'])?$settings['customsize']:''
			)
		);


		if (!empty($list)){
			$pfstyletext = '';

			if(!empty($customsize)){
				$customsize = explode('x',$customsize);
				if(is_array($customsize)){
					$featured_image_width = $customsize[0]*$pf_retnumber;
					$featured_image_height = $customsize[1]*$pf_retnumber;
				}
			}else{

				$general_retinasupport = $this->PFSAIssetControl('general_retinasupport','','0');

				if($general_retinasupport == 1){$pf_retnumber = 2;}else{$pf_retnumber = 1;}

				$setupsizelimitconf_general_gridsize2_width = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize2','width',555);
				$setupsizelimitconf_general_gridsize2_height = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize2','height',416);

				$setupsizelimitconf_general_gridsize3_width = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize3','width',360);
				$setupsizelimitconf_general_gridsize3_height = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize3','height',270);

				$setupsizelimitconf_general_gridsize4_width = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize4','width',263);
				$setupsizelimitconf_general_gridsize4_height = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize4','height',197);


				switch($logoamount){
					case 2:
						$featured_image_width = $setupsizelimitconf_general_gridsize2_width*$pf_retnumber;
						$featured_image_height = $setupsizelimitconf_general_gridsize2_height*$pf_retnumber;
						break;
					case 3:
						$featured_image_width = $setupsizelimitconf_general_gridsize3_width*$pf_retnumber;
						$featured_image_height = $setupsizelimitconf_general_gridsize3_height*$pf_retnumber;
						break;
					case 4:
						$featured_image_width = $setupsizelimitconf_general_gridsize4_width*$pf_retnumber;
						$featured_image_height = $setupsizelimitconf_general_gridsize4_height*$pf_retnumber;
						break;
					case 5:
						$featured_image_width = $setupsizelimitconf_general_gridsize4_width*$pf_retnumber;
						$featured_image_height = $setupsizelimitconf_general_gridsize4_height*$pf_retnumber;
						break;
					default:
						$featured_image_width = $setupsizelimitconf_general_gridsize4_width*$pf_retnumber;
						$featured_image_height = $setupsizelimitconf_general_gridsize4_height*$pf_retnumber;
					break;
				}

			}


		

			$myrandno = rand(1, 2147483647);
			$myrandno = md5($myrandno);
			$carousel_id = 'vc-images-carousel-'.$myrandno;
			
			if($logoamount > 1){$pfstyletext = 'pf-vcimage-carousel';}

			echo '
			<div class="wpb_images_carousel wpb_content_element">
			    <div class="wpb_wrapper">';
				
				echo  '
			        <div id="'.$carousel_id.'"  class="vc-slide vc-image-carousel pf-client-carousel vc-image-carousel-'.$logoamount.'">
			            <!-- Wrapper for slides -->
			            <div class="vc-carousel-inner">
			            	
			                <div class="vc-carousel-slideline"><div class="vc-carousel-slideline-inner pointfinderlogocarousel" 
			                data-prevnext="'.$prevnext.'" 
			                data-npagination="'.$npagination.'" 
			                data-pagination="'.$pagination.'" 
			                data-autoplay="'.$autoplay.'" 
			                data-speed="'.$speed.'" 
			                data-logoamount="'.$logoamount.'" 
			                >';

			                $i = -1;

			               	foreach ($list as $singlelist) {
			               		$i++;

			               		if (isset($singlelist['image']['url'])) {
			                        $post_thumbnail = $singlelist['image']['url'];
			                    }else {
			                        $thumbnail = PFCOREELEMENTSURLPUBLIC.'images/noimg.png';
			        				$p_img_large = PFCOREELEMENTSURLPUBLIC.'images/noimg.png';
			                    }

			                    if (!empty($post_thumbnail)) {
			                    	if($featured_image_width > 0 ){
								   
									   if($autocrop === 'yes'){
				                   	   	   $thumbnail = pointfinder_aq_resize($post_thumbnail,$featured_image_width,false);
										   if($thumbnail === false) {
												if($general_retinasupport == 1){
													$thumbnail = pointfinder_aq_resize($post_thumbnail,$featured_image_width/2,false);
													if($thumbnail === false) {
														$thumbnail = $post_thumbnail;
													}
												}else{
													$thumbnail = $post_thumbnail;
												}
												
											}
									   }else{
									   	   $thumbnail = pointfinder_aq_resize($post_thumbnail,$featured_image_width,$featured_image_height,true);
										   if($thumbnail === false) {
												if($general_retinasupport == 1){
													$thumbnail = pointfinder_aq_resize($post_thumbnail,$featured_image_width/2,$featured_image_height/2,false);
													if($thumbnail === false) {
														$thumbnail = $post_thumbnail;
													}
												}else{
													$thumbnail = $post_thumbnail;
												}
												
											}
									   
									   }

								   }else{
									   $thumbnail = $post_thumbnail;
								   }

								   if ($thumbnail == false) {
								     $thumbnail = pointfinder_aq_resize($post_thumbnail,$featured_image_width,$featured_image_height,true,true,true);
								   }


			   				   		$p_img_large = $post_thumbnail;
			                    }
			               

			               	echo ' <div class="vc-item"><div class="vc-inner"';?> <?php if($borders == 'yes'){echo  ' style="border:1px solid rgba(60,60,60,0.07)"';}
		                   	echo  '>';
			                     if ($singlelist['onclick'] == 'link_image'){
			                        $p_img_large = $post_thumbnail;
			                        echo  '<a class="pf-mfp-image wbp_vc_gallery_pfwrapper '.$pfstyletext.'" href="'.$p_img_large.'"><div class="pf-pad-area"><img data-no-lazy="1" src="'.$thumbnail.'" alt=""><div class="PStyleHe"></div></div></a>';
									}elseif($singlelist['onclick'] == 'custom_link'){ 
			                        echo  '<a href="'.$singlelist['website_link']['url'].'"'.(!empty($singlelist['website_link']['target']) ? ' target="_blank"' : '').' '.(!empty($singlelist['website_link']['nofollow']) ? ' rel="nofollow"' : '').' class="wbp_vc_gallery_pfwrapper '.$pfstyletext.'">
			                            <img data-no-lazy="1" src="'.$thumbnail.'" alt="">
			                        <div class="PStyleHe2"></div></a>';
			                    }else{ 
			                        echo  '<img data-no-lazy="1" src="'.$thumbnail.'" alt="" class="'.$pfstyletext.'">';
			                    }; 
			                   echo ' </div></div>';
			                   };
			                    echo '
			                </div>
			                
			                </div>
			            </div>
			        </div>
			    </div>
			</div>
			';
			
			
		}
	}


}
