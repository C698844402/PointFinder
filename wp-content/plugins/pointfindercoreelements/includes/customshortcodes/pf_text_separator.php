<?php
/*
*
* Visual Composer PointFinder Text Seperator Shortcode
*
*/

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

class PointFinderTextSPShortcode extends WPBakeryShortCode {


    function __construct() {
        add_action( 'vc_after_init', array( $this, 'pointfinder_single_pftext_seperator_module_mapping' ) );
        add_shortcode( 'pftext_separator', array( $this, 'pointfinder_single_pftext_seperator_module_html' ) );
    }

    

    public function pointfinder_single_pftext_seperator_module_mapping() {

        /**
        *Start : PF Text Separator ----------------------------------------------------------------------------------------------------
        **/
            vc_map( array(
                'name' => esc_html__( 'PF Text Separator', 'pointfindercoreelements' ),
                'base' => 'pftext_separator',
                'icon' => 'icon-wpb-ui-separator-label',
                'category' => esc_html__( 'Point Finder', 'pointfindercoreelements' ),
                'description' => esc_html__( 'Horizontal separator line with heading', 'pointfindercoreelements' ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__( 'Title', 'pointfindercoreelements' ),
                        'param_name' => 'title',
                        'holder' => 'div',
                        'value' => esc_html__( 'Title', 'pointfindercoreelements' ),
                        'description' => esc_html__( 'Separator title.(Required)', 'pointfindercoreelements' )
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__( 'Title position', 'pointfindercoreelements' ),
                        'param_name' => 'title_align',
                        'value' => array(
                            esc_html__( 'Align left', 'pointfindercoreelements' ) => 'separator_align_left',
                            esc_html__( 'Align center', 'pointfindercoreelements' ) => 'separator_align_center',
                            esc_html__( 'Align right', 'pointfindercoreelements' ) => "separator_align_right"
                        ),
                        'description' => esc_html__( 'Select title location.', 'pointfindercoreelements' )
                    )
                )
            ) );

        /**
        *End : PF Text Separator ----------------------------------------------------------------------------------------------------
        **/

      

    }


    public function pointfinder_single_pftext_seperator_module_html( $atts ) {

      extract(shortcode_atts(array(
            'title' => '',
            'title_align' => '',
        ), $atts));
        $class = "pf_pageh_title";

        $class .= ($title_align!='') ? ' pf_'.$title_align : '';
        $output = '<div class="'.$class.'">';   
            if($title!=''){
                $output .= '<div class="pf_pageh_title_inner">'.$title.'</div>';
            }
        $output .= '</div>';
        return $output;

    }

}
new PointFinderTextSPShortcode();

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_pointfinder_single_pftext_seperator extends WPBakeryShortCode {
    }
}
