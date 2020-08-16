<?php
namespace PointFinderElementorSYS\Widgets;


use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use PointFinderElementorSYS\Helper;


if ( ! defined( 'ABSPATH' ) ) exit;

class PointFinder_Text_Separator extends Widget_Base {



	public function show_in_panel() { return true; }

	public function get_keywords() { return [ 'pointfinder', 'divider', 'deparator', 'hr' ]; }

	public function get_name() { return 'pointfindertextseperator'; }

	public function get_title() { return esc_html__( 'PF Text Separator', 'pointfindercoreelements' ); }

	public function get_icon() { return 'eicon-divider-shape'; }

	public function get_categories() { return [ 'pointfinder_elements' ]; }


	public function get_script_depends() {
	    return [];
	}

	protected function _register_controls() {


		$this->start_controls_section(
			'seperator_general',
			[
				'label' => esc_html__( 'General', 'pointfindercoreelements' ),
				'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);
			$this->add_control(
				'title',
				[
					'label' => esc_html__( 'Title', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'Type your title here', 'pointfindercoreelements' ),
				]
			);
			$this->add_control(
				'title_align',
				[
					'label' => esc_html__( 'Alignment', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::CHOOSE,
					'options' => [
						'separator_align_left' => [
							'title' => esc_html__( 'Left', 'pointfindercoreelements' ),
							'icon' => 'fa fa-align-left',
						],
						'separator_align_center' => [
							'title' => esc_html__( 'Center', 'pointfindercoreelements' ),
							'icon' => 'fa fa-align-center',
						],
						'separator_align_right' => [
							'title' => esc_html__( 'Right', 'pointfindercoreelements' ),
							'icon' => 'fa fa-align-right',
						],
					],
					'default' => 'separator_align_left',
					'toggle' => true,
				]
			);
		$this->end_controls_section();

	}

	

	protected function render() {

		$settings = $this->get_settings_for_display();


		extract(array(
			'title' => isset($settings['title'])?$settings['title']:'',
			'title_align' => isset($settings['title_align'])?$settings['title_align']:'left'
		));
		$class = "pf_pageh_title";

        $class .= ($title_align!='') ? ' pf_'.$title_align : '';
        $output = '<div class="'.$class.'">';   
            if($title!=''){
                $output .= '<div class="pf_pageh_title_inner">'.$title.'</div>';
            }
        $output .= '</div>';
        echo $output;
	
	}


}
