<?php
namespace PointFinderElementorSYS\Widgets;


use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use PointFinderElementorSYS\Helper;
use PointFinderOptionFunctions;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) exit;

class PointFinder_Testimonials extends Widget_Base {

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

		wp_enqueue_script('pointfinder-elementor-testimonials', PFCOREELEMENTSURL . 'includes/elementor/assets/js/testimonials.js', ['owncarousel'],'1.9.2',true);
    }

	public function show_in_panel() { return true; }

	public function get_keywords() { return [ 'pointfinder', 'testimonial carousel', 'testimonial' ]; }

	public function get_name() { return 'pointfindertestimonials'; }

	public function get_title() { return esc_html__( 'PF Testimonials', 'pointfindercoreelements' ); }

	public function get_icon() { return 'eicon-testimonial'; }

	public function get_categories() { return [ 'pointfinder_elements' ]; }


	public function get_script_depends() {
	    return ['owncarousel','pointfinder-elementor-testimonials'];
	}

	public function get_style_depends() {
      return [];
    }

	protected function _register_controls() {


		$this->start_controls_section(
			'testimonials_general',
			[
				'label' => esc_html__( 'General', 'pointfindercoreelements' ),
				'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);
			
			$this->add_control(
				'posts_in',
				[
					'label' => esc_html__("Testimonial IDs", "pointfindercoreelements"),
					"description" => esc_html__('Fill this field with testimonial item IDs separated by commas (,), to retrieve only them. Use this in conjunction with "PF Testimonials" field.', "pointfindercoreelements"),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => ''
				]
			);
			$this->add_control(
				'count',
				[
					'label' => esc_html__( 'Slides Count', 'pointfindercoreelements' ),
					"description" => esc_html__('How many slides wamt to show? Enter number or word "All" or Enter "1" for disable slider and show only one item.', "pointfindercoreelements"),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => ''
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
				'mode',
				[
					'label' => esc_html__( 'Slider Effect', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'fade',
					'options' => [
						'fade'  => esc_html__("Fade", "pointfindercoreelements"),
						'backSlide' => esc_html__("Back Slide", "pointfindercoreelements")
					],
				]
			);
			$this->add_control(
				'orderby',
				[
					'label' => esc_html__( 'Order by', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'date',
					'options' => [
						'date' => esc_html__("Date", "pointfindercoreelements"),
						'ID' => esc_html__("ID", "pointfindercoreelements"),
						'author' => esc_html__("Author", "pointfindercoreelements"),
						'title' => esc_html__("Title", "pointfindercoreelements"),
						'modified' => esc_html__("Modified", "pointfindercoreelements"),
						'rand' => esc_html__("Random", "pointfindercoreelements"),
						'comment_count' => esc_html__("Comment count", "pointfindercoreelements"),
						'menu_order' => esc_html__("Menu order", "pointfindercoreelements")
					],
				]
			);

			$this->add_control(
				'order',
				[
					'label' => esc_html__( 'Order', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'DESC',
					'options' => [
						'ASC'  => esc_html__("Ascending", "pointfindercoreelements"),
						'DESC' => esc_html__("Descending", "pointfindercoreelements")
					],
				]
			);
			
		$this->end_controls_section();

	}

	

	protected function render() {

		$settings = $this->get_settings_for_display();

		extract(
			array(
				'posts_in' => isset($settings['posts_in'])?$settings['posts_in']:'',
				'count' => isset($settings['count'])?$settings['count']:'',
				'autoplay' => isset($settings['autoplay'])?$settings['autoplay']:'yes',
				'speed' => isset($settings['speed'])?$settings['speed']:5,
				'mode' => isset($settings['mode'])?$settings['mode']:'',
				'orderby' => isset($settings['orderby'])?$settings['orderby']:'date',
				'order' => isset($settings['order'])?$settings['order']:'DESC'
			)
		);

		$setup3_pointposttype_pt11 = $this->PFSAIssetControl('setup3_pointposttype_pt11','','testimonials');

		$el_start = '<div class="pfslides-item">';
	    $el_end = '</div>';
	    $slides_wrap_start = '<div class="pfslides pointfindertestimonials" data-mode="'.$mode.'" data-speed="'.$speed.'">';
	    $slides_wrap_end = '</div>';

	    $query_args = array();

		if ( $posts_in == '' ) {
		    global $post;
		    $query_args['post__not_in'] = array($post->ID);
		}
		else if ( $posts_in != '' ) {
		    $query_args['post__in'] = explode(",", $posts_in);
		}

		// Post teasers count
		if ( $count != '' && !is_numeric($count) ) $count = -1;
		if ( $count != '' && is_numeric($count) ) $query_args['posts_per_page'] = $count;

		// Post type
		$query_args['post_type'] = $setup3_pointposttype_pt11;



		// Order posts
		if ( $orderby != NULL ) {
		    $query_args['orderby'] = $orderby;
		}
		$query_args['order'] = $order;

		// Run query
		$my_query = new WP_Query($query_args);

		$pretty_rel_random = 'rel-'.rand();
		$teasers = '';
		$i = -1;

		while ( $my_query->have_posts() ) {
		    $i++;
		    $my_query->the_post();
		    $post_title = the_title("", "", false);
		    $post_id = $my_query->post->ID;
		    $content = apply_filters('the_content', get_the_content());
		    
		    $description = '';
		    
			$description = '<div class="pf-testslider-content">';
			$description .= $content;
			$description .= '<div class="pf-test-arrow"> </div>';
			$description .= '<div class="pf-test-icon"></div><div class="pf-test-name">'.$post_title.'</div>';
			$description .= '</div>';

		    $teasers .= $el_start  . $description . $el_end;
		} // endwhile loop
		wp_reset_postdata();

		if ( $teasers ) { $teasers = $slides_wrap_start. $teasers . $slides_wrap_end; }
		else { $teasers = esc_html__("Nothing found." , "pointfindercoreelements"); }

		$css_class = 'pf_testimonials ';

		echo  "\n\t".'<div class="'.$css_class.'">';
		echo  "\n\t\t".'<div class="wpb_wrapper">';
		echo  '<div class="pf_testimonials_sliderWrapper">'.$teasers.'';
		echo  '</div>';
		echo  "\n\t\t".'</div> ';
		echo  "\n\t".'</div> ';
	}


}
