<?php
/*
*
* Visual Composer PointFinder Testimonials Shortcode
*
*/

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

class PointFinderTestimonialsCode extends WPBakeryShortCode {
	use PointFinderOptionFunctions;

    function __construct() {
        add_action( 'vc_after_init', array( $this, 'pointfinder_single_pf_testimonials_module_mapping' ) );
        add_shortcode( 'pf_testimonials', array( $this, 'pointfinder_single_pf_testimonials_module_html' ) );
    }

    

    public function pointfinder_single_pf_testimonials_module_mapping() {

		if ( !defined( 'WPB_VC_VERSION' ) ) {
		  return;
		}


	    /**
		*Start : Testimonials ----------------------------------------------------------------------------------------------------
		**/
			vc_map( array(
			"name" => esc_html__("PF Testimonials", 'pointfindercoreelements'),
			"base" => "pf_testimonials",
			"icon" => "pfaicon-chat-empty",
			"category" => esc_html__("Point Finder", "pointfindercoreelements"),
			"description" => esc_html__('Testimonial shortcut', 'pointfindercoreelements'),
			"params" => array(
					  array(
						"type" => "textfield",
						"heading" => esc_html__("Widget title", "pointfindercoreelements"),
						"param_name" => "title",
						"description" => esc_html__("Enter text which will be used as widget title. Leave blank if no title is needed.", "pointfindercoreelements"),
						"admin_label" => true
					  ),
					  array(
						"type" => "textfield",
						"heading" => esc_html__("Slides count", "pointfindercoreelements"),
						"param_name" => "count",
						"description" => esc_html__('How many slides to show? Enter number or word "All" or Enter "1" for disable slider and show only one item.', "pointfindercoreelements"),
						"edit_field_class" => 'vc_col-sm-6 vc_column'
					  ),
					  array(
						  "type" => "textfield",
						  "heading" => esc_html__("Slider speed", "pointfindercoreelements"),
						  "param_name" => "interval",
						  "value" => "5000",
						  "description" => esc_html__("Duration of animation between slides (in ms)", "pointfindercoreelements"),
						  "edit_field_class" => 'vc_col-sm-6 vc_column'
					  ),
					  array(
						  "type" => "dropdown",
						  "heading" => esc_html__("Slider Effect", "pointfindercoreelements"),
						  "param_name" => "mode",
						  "value" => array(esc_html__("Fade", "pointfindercoreelements") => 'fade', esc_html__("Back Slide", "pointfindercoreelements") => 'backSlide'),
					  ),
					  array(
						"type" => "textfield",
						"heading" => esc_html__("Testimonial IDs", "pointfindercoreelements"),
						"param_name" => "posts_in",
						"description" => esc_html__('Fill this field with testimonial item IDs separated by commas (,), to retrieve only them. Use this in conjunction with "PF Testimonials" field.', "pointfindercoreelements")
					  ),
					  array(
						"type" => "dropdown",
						"heading" => esc_html__("Order by", "pointfindercoreelements"),
						"param_name" => "orderby",
						"value" => array( "", esc_html__("Date", "pointfindercoreelements") => "date", esc_html__("ID", "pointfindercoreelements") => "ID", esc_html__("Author", "pointfindercoreelements") => "author", esc_html__("Title", "pointfindercoreelements") => "title", esc_html__("Modified", "pointfindercoreelements") => "modified", esc_html__("Random", "pointfindercoreelements") => "rand", esc_html__("Comment count", "pointfindercoreelements") => "comment_count", esc_html__("Menu order", "pointfindercoreelements") => "menu_order" ),
						"description" => sprintf(esc_html__('Select how to sort retrieved posts. More at %s.', 'pointfindercoreelements'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>'),
						"edit_field_class" => 'vc_col-sm-6 vc_column'
					  ),
					  array(
						"type" => "dropdown",
						"heading" => esc_html__("Order", "pointfindercoreelements"),
						"param_name" => "order",
						"value" => array( esc_html__("Descending", "pointfindercoreelements") => "DESC", esc_html__("Ascending", "pointfindercoreelements") => "ASC" ),
						"description" => sprintf(esc_html__('Designates the ascending or descending order. More at %s.', 'pointfindercoreelements'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>'),
						"edit_field_class" => 'vc_col-sm-6 vc_column'
					  ),
				)
				)
			);
		/**
		*End : Testimonials ----------------------------------------------------------------------------------------------------
		**/


    }


    public function pointfinder_single_pf_testimonials_module_html( $atts ) {

        $output = $title = $count = $interval = $posts_in = '';
		$orderby = $order = $mode = $el_class = '';
		extract(shortcode_atts(array(
		    'title' => '',
		    'count' => 3,
		    'interval' => 5000,
		    'slides_title' => '',
		    'posts_in' => '',
		    'orderby' => NULL,
		    'order' => 'DESC',
			'mode' => 'backSlide',
		    'autoplay' => '',
		    'hide_pagination_control' => '',
		    'hide_prev_next_buttons' => '',
		), $atts));

		$gal_images = '';
		$link_start = '';
		$link_end = '';
		$el_start = '';
		$el_end = '';
		$slides_wrap_start = '';
		$slides_wrap_end = '';
		$setup3_pointposttype_pt11 = $this->PFSAIssetControl('setup3_pointposttype_pt11','','testimonials');
			$myrandno = rand(1, 2147483647);
			$myrandno = md5($myrandno);
						
		    $el_start = '<div class="pfslides-item">';
		    $el_end = '</div>';
		    $slides_wrap_start = '<div class="pfslides" id="'.$myrandno.'">';
		    $slides_wrap_end = '</div>';

		$query_args = array();

		//exclude current post/page from query
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
		    
			// Content start.
			$description = '<div class="pf-testslider-content">';
			$description .= $content;
			$description .= '<div class="pf-test-arrow"> </div>';
			$description .= '<div class="pf-test-icon"></div><div class="pf-test-name">'.$post_title.'</div>';
			$description .= '</div>';
			// Content end

		    $teasers .= $el_start  . $description . $el_end;
		} // endwhile loop
		wp_reset_postdata();

		if ( $teasers ) { $teasers = $slides_wrap_start. $teasers . $slides_wrap_end; }
		else { $teasers = esc_html__("Nothing found." , "pointfindercoreelements"); }

		$css_class = 'pf_testimonials ';

		$output .= "\n\t".'<div class="'.$css_class.'">';
		$output .= "\n\t\t".'<div class="wpb_wrapper">';
		$output .=  wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_posts_slider_heading'));
		$output .= '<div class="pf_testimonials_sliderWrapper">'.$teasers.'';
		$output .= '</div>';
		$output .= "\n\t\t".'</div> ';
		$output .= "\n\t".'</div> ';
		if($count > 1){
		$output .= '
		<script type="text/javascript">
		(function($) {
			"use strict";
			$(function() {
				$("#'.$myrandno.'").owlCarousel({
						items : 1,
						navigation : false,
						pagination : false,
						autoPlay : true,stopOnHover : true,
						slideSpeed:'.$interval.',
						paginationNumbers : false,
						mouseDrag:false,
						touchDrag:false,
						autoHeight : false,
						responsive:true,
						transitionStyle: "'.$mode.'", 
						itemsScaleUp : false,
						navigationText:false,
						theme:"owl-theme"
						,singleItem : true,
						itemsCustom : true,
						itemsDesktop : [1199,1],
						itemsDesktopSmall : [980,1],
						itemsTablet: [768,1],
						itemsTabletSmall: false,
						itemsMobile : [479,1],
					});
			});

		})(jQuery);
		</script>';
		}
		return $output;

    }

}
new PointFinderTestimonialsCode();

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_pointfinder_single_pf_testimonials extends WPBakeryShortCode {
    }
}
