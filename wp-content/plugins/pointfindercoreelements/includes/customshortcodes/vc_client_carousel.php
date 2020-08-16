<?php
/*
*
* Visual Composer PointFinder Client Carousel Shortcode
*
*/

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

class PointFinderClientCarouselShortcode extends WPBakeryShortCode {
	use PointFinderOptionFunctions;

    function __construct() {
        add_action( 'vc_after_init', array( $this, 'pointfinder_single_pf_clientcarousel_module_mapping' ) );
        add_shortcode( 'pf_clientcarousel', array( $this, 'pointfinder_single_pf_clientcarousel_module_html' ) );
    }

    

    public function pointfinder_single_pf_clientcarousel_module_mapping() {

		if ( !defined( 'WPB_VC_VERSION' ) ) {
		  return;
		}

		/**
		*Start : Client Carousel ----------------------------------------------------------------------------------------------------
		**/
			vc_map( array(
			"name" => esc_html__("PF Client Carousel", 'pointfindercoreelements'),
			"base" => "pf_clientcarousel",
			"icon" => "pfaicon-users",
			"category" => esc_html__("Point Finder", "pointfindercoreelements"),
			"description" => esc_html__('Client carousel', 'pointfindercoreelements'),
			"params" => array(
					array(
					  "type" => "textfield",
					  "heading" => esc_html__("Widget title", "pointfindercoreelements"),
					  "param_name" => "title",
					  "description" => esc_html__("Enter text which will be used as widget title. Leave blank if no title is needed.", "pointfindercoreelements")
					),
					array(
					  "type" => "attach_images",
					  "heading" => esc_html__("Images", "pointfindercoreelements"),
					  "param_name" => "images",
					  "value" => "",
					  "description" => esc_html__("Select images from media library.", "pointfindercoreelements")
					),
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Items", "pointfindercoreelements"),
						"param_name" => "img_size",
						"value" => array(
							esc_html__("4 Item - Default", "pointfindercoreelements") => "grid4",
							esc_html__("5 Item", "pointfindercoreelements") => "grid5",
							esc_html__("3 Item", "pointfindercoreelements") => "grid3",esc_html__("2 Item", "pointfindercoreelements") => "grid2"
						),
						"description" => esc_html__("How many item want to see in viewport? (On mobile and tablet it will resize auto.)", "pointfindercoreelements"),
					"edit_field_class" => 'vc_col-sm-6 vc_column'
					),
					array(
					  "type" => "textfield",
					  "heading" => esc_html__("Slider speed", "pointfindercoreelements"),
					  "param_name" => "speed",
					  "value" => "5000",
					  "description" => esc_html__("Duration of animation between slides (in ms)", "pointfindercoreelements"),
					"edit_field_class" => 'vc_col-sm-6 vc_column'
					),
					array(
					  "type" => "dropdown",
					  "heading" => esc_html__("On click", "pointfindercoreelements"),
					  "param_name" => "onclick",
					  "value" => array(
						   esc_html__("Open prettyPhoto", "pointfindercoreelements") => "link_image",
						   esc_html__("Do nothing", "pointfindercoreelements") => "link_no",
						   esc_html__("Open custom link", "pointfindercoreelements") => "custom_link"
					   ),
					  "description" => esc_html__("What to do when slide is clicked?", "pointfindercoreelements")
					),
					array(
					  "type" => "exploded_textarea",
					  "heading" => esc_html__("Custom links", "pointfindercoreelements"),
					  "param_name" => "custom_links",
					  "description" => esc_html__('Enter links for each slide here. Divide links with linebreaks (Enter).', 'pointfindercoreelements'),
					  "dependency" => array('element' => "onclick", 'value' => array('custom_link'))
					),

					array(
					  "type" => "dropdown",
					  "heading" => esc_html__("Custom link target", "pointfindercoreelements"),
					  "param_name" => "custom_links_target",
					  "description" => esc_html__('Select where to open  custom links.', 'pointfindercoreelements'),
					  "dependency" => array('element' => "onclick", 'value' => array('custom_link')),
					  'value' => array(esc_html__("Same window", "pointfindercoreelements") => "_self", esc_html__("New window", "pointfindercoreelements") => "_blank")
					),

					array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("Slider autoplay", "pointfindercoreelements"),
					  "param_name" => "autoplay",
					  "description" => esc_html__("Enables autoplay mode.", "pointfindercoreelements"),
					  "value" => array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
					"edit_field_class" => 'vc_col-sm-6 vc_column'
					),
					array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("Hide pagination control", "pointfindercoreelements"),
					  "param_name" => "hide_pagination_control",
					  "description" => esc_html__("If YES pagination control will be removed.", "pointfindercoreelements"),
					  "value" => array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
					"edit_field_class" => 'vc_col-sm-6 vc_column'
					),
					array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("Hide borders", "pointfindercoreelements"),
					  "param_name" => "hide_borders",
					  "description" => esc_html__("If YES borders control will be removed.", "pointfindercoreelements"),
					  "value" => array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
					"edit_field_class" => 'vc_col-sm-6 vc_column'
					),
					array(
					"type" => 'checkbox',
					"heading" => esc_html__("Disable Auto Crop", "pointfindercoreelements"),
					"param_name" => "autocrop",
					"description" => esc_html__("Disables auto crop on image.(Not recommended.)", "pointfindercoreelements"),
					"value" => array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
					"edit_field_class" => 'vc_col-sm-6 vc_column'
					),
					array(
					"type" => "textfield",
					"heading" => esc_html__("Custom Size (Optional)", "pointfindercoreelements"),
					"param_name" => "customsize",
					"value" => "",
					"description" => esc_html__("Ex: 300x200  | Custom size value (Optional). Please leave blank for auto resize. (in px)", "pointfindercoreelements")
					),
			)
			) );
		/**
		*End : Client Carousel ----------------------------------------------------------------------------------------------------
		**/

    }


    public function pointfinder_single_pf_clientcarousel_module_html( $atts ) {

        $output = $title =  $onclick = $custom_links = $img_size = $custom_links_target = $images = '';
		$autoplay = $autocrop = $customsize = $hide_pagination_control =  $speed ='';
		extract(shortcode_atts(array(
		    'title' => '',
		    'onclick' => 'link_image',
		    'custom_links' => '',
		    'custom_links_target' => '',
		    'img_size' => 'grid4',//Change
		    'images' => '',
			'hide_borders' => '',
			'autocrop' => '',
			'customsize' => '',
		    'autoplay' => '',
		    'hide_pagination_control' => '',
		    'hide_prev_next_buttons' => '',
		    'numbered_pagination' => '',
		    'speed' => '5000'
		), $atts));

		$gal_images = '';
		$link_start = '';
		$link_end = '';
		$el_start = '';
		$el_end = '';
		$slides_wrap_start = '';
		$slides_wrap_end = '';
		$pretty_rand = $onclick == 'link_image' ? rand() : '';


		$general_retinasupport = $this->PFSAIssetControl('general_retinasupport','','0');
		if($general_retinasupport == 1){$pf_retnumber = 2;}else{$pf_retnumber = 1;}

		$setupsizelimitconf_general_gridsize2_width = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize2','width',555);
		$setupsizelimitconf_general_gridsize2_height = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize2','height',416);

		$setupsizelimitconf_general_gridsize3_width = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize3','width',360);
		$setupsizelimitconf_general_gridsize3_height = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize3','height',270);

		$setupsizelimitconf_general_gridsize4_width = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize4','width',263);
		$setupsizelimitconf_general_gridsize4_height = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize4','height',197);



		switch($img_size){
			case 'grid2':
				$pf_grid_size = 2;
				$featured_image_width = $setupsizelimitconf_general_gridsize2_width*$pf_retnumber;
				$featured_image_height = $setupsizelimitconf_general_gridsize2_height*$pf_retnumber;
				break;
			case 'grid3':
				$pf_grid_size = 3;
				$featured_image_width = $setupsizelimitconf_general_gridsize3_width*$pf_retnumber;
				$featured_image_height = $setupsizelimitconf_general_gridsize3_height*$pf_retnumber;
				break;
			case 'grid4':
				$pf_grid_size = 4;
				$featured_image_width = $setupsizelimitconf_general_gridsize4_width*$pf_retnumber;
				$featured_image_height = $setupsizelimitconf_general_gridsize4_height*$pf_retnumber;
				break;
			case 'grid5':
				$pf_grid_size = 5;
				$featured_image_width = $setupsizelimitconf_general_gridsize4_width*$pf_retnumber;
				$featured_image_height = $setupsizelimitconf_general_gridsize4_height*$pf_retnumber;
				break;
			default:
				$pf_grid_size = 4;
				$featured_image_width = $setupsizelimitconf_general_gridsize4_width*$pf_retnumber;
				$featured_image_height = $setupsizelimitconf_general_gridsize4_height*$pf_retnumber;
			break;
		}

		if($customsize!=''){
			$customsize = explode('x',$customsize);
			if(is_array($customsize)){
				$featured_image_width = $customsize[0]*$pf_retnumber;
				$featured_image_height = $customsize[1]*$pf_retnumber;
			}
		}
		$myrandno = rand(1, 2147483647);
		$myrandno = md5($myrandno);

		if($pf_grid_size > 1){ $pfstyletext = 'pf-vcimage-carousel'; }else{$pfstyletext = '';}
		if ( $images == '' ) $images = '-1,-2,-3';

		if ( $onclick == 'custom_link' ) { $custom_links = explode( ',', $custom_links); }

		$images = explode( ',', $images);
		$i = -1;
		$carousel_id = 'vc-images-carousel-'.$myrandno;
		$output_text = '
		<div class="wpb_images_carousel wpb_content_element">
		    <div class="wpb_wrapper">';
			$output_text .=  wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_gallery_heading')) ;
			$output_text .= '
		        <div id="'.$carousel_id.'"  class="vc-slide vc-image-carousel pf-client-carousel vc-image-carousel-'.$pf_grid_size.'">
		            <!-- Wrapper for slides -->
		            <div class="vc-carousel-inner">
		            	
		                <div class="vc-carousel-slideline"><div class="vc-carousel-slideline-inner" id="'.$myrandno.'">';?>
		                    <?php foreach($images as $attach_id): ?>
		                    <?php
		                    $i++;
		                    if ($attach_id > 0) {
		                        $post_thumbnail = wp_get_attachment_image_src( $attach_id, 'full' );
		                    }else {
		                        $thumbnail = PFCOREELEMENTSURLPUBLIC.'images/noimg.png';
		        				$p_img_large = PFCOREELEMENTSURLPUBLIC.'images/noimg.png';
		                    }
		                    if (isset($post_thumbnail[0])) {
		                    	
		                  
							   if($featured_image_width > 0 ){
								   
								   if($autocrop === 'yes'){
			                   	   	   $thumbnail = pointfinder_aq_resize($post_thumbnail[0],$featured_image_width,false);
									   if($thumbnail === false) {
											if($general_retinasupport == 1){
												$thumbnail = pointfinder_aq_resize($post_thumbnail[0],$featured_image_width/2,false);
												if($thumbnail === false) {
													$thumbnail = $post_thumbnail[0];
												}
											}else{
												$thumbnail = $post_thumbnail[0];
											}
											
										}
								   }else{
								   	   $thumbnail = pointfinder_aq_resize($post_thumbnail[0],$featured_image_width,$featured_image_height,true);
									   if($thumbnail === false) {
											if($general_retinasupport == 1){
												$thumbnail = pointfinder_aq_resize($post_thumbnail[0],$featured_image_width/2,$featured_image_height/2,false);
												if($thumbnail === false) {
													$thumbnail = $post_thumbnail[0];
												}
											}else{
												$thumbnail = $post_thumbnail[0];
											}
											
										}
								   
								   }

							   }else{
								   $thumbnail = $post_thumbnail[0];
							   }

							   if ($thumbnail == false) {
							     $thumbnail = pointfinder_aq_resize($post_thumbnail[0],$featured_image_width,$featured_image_height,true,true,true);
							   }


		   				   		$p_img_large = $post_thumbnail[0];
	   				   		}
		                   
		                   $output_text .=' <div class="vc-item"><div class="vc-inner"';?> <?php if($hide_borders !== 'yes'){$output_text .= ' style="border:1px solid rgba(60,60,60,0.07)"';}
		                   $output_text .= '>';
		                     if ($onclick == 'link_image'){
		                        $p_img_large = $post_thumbnail[0];
		                        $output_text .= '<a class="pf-mfp-image wbp_vc_gallery_pfwrapper '.$pfstyletext.'" href="'.$p_img_large.'">
		                        <div class="pf-pad-area">
		                            <img data-no-lazy="1" src="'.$thumbnail.'" alt="">
		                        <div class="PStyleHe"></div></div></a>';
								}elseif($onclick == 'custom_link' && isset( $custom_links[$i] ) && $custom_links[$i] != ''){ 
		                        $output_text .= '<a href="'.$custom_links[$i].'"'.(!empty($custom_links_target) ? ' target="'.$custom_links_target.'"' : '').' class="wbp_vc_gallery_pfwrapper '.$pfstyletext.'">
		                            <img data-no-lazy="1" src="'.$thumbnail.'" alt="">
		                        <div class="PStyleHe2"></div></a>';
		                    }else{ 
		                        $output_text .= '<img data-no-lazy="1" src="'.$thumbnail.'" alt="" class="'.$pfstyletext.'">';
		                    }; 
		                   $output_text .=' </div></div>';
		                   endforeach;
		                    $output_text .='
		                </div>
		                
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
		';
		
		$content_script = '(function($) {
			"use strict";
			$(function() {
				$("#'.$myrandno.'").owlCarousel({
						items : '.$pf_grid_size .',';
						 if($hide_prev_next_buttons !== "yes"){ $content_script .= 'navigation : true,';}else{$content_script .= "navigation : false,";}
						 if($numbered_pagination === "yes"){ $content_script .= 'paginationNumbers : true,';}else{$content_script .= "paginationNumbers : false,";}
						 if($hide_pagination_control !== "yes"){ $content_script .= 'pagination : true,';}else{$content_script .= "pagination : false,";}
						 if($autoplay == "yes"){ $content_script .= 'autoPlay : true,stopOnHover : true,';}else{$content_script .= 'autoPlay : false,';}
						 $content_script .='
						slideSpeed:'.$speed.',
						mouseDrag:true,
						touchDrag:true,
						itemSpaceWidth: 10,';
						if($autoplay === "yes"){ $content_script .= 'itemBorderWidth : 0,';}else{$content_script .= 'itemBorderWidth : '.$pf_grid_size.',';}
						$content_script .='
						autoHeight : false,
						responsive:true,
						itemsScaleUp : false,
						navigationText:false,
						theme:"owl-theme",
						singleItem : false,
					});

					$(window).on("load",function(){
					    setTimeout(function(){
							$(".vc-image-carousel .vc-carousel-slideline-inner .vc-inner img").css("opacity","1").css("width","100%");
					    },150);
					});
			});

		})(jQuery);';
		wp_add_inline_script('pftheme-customjs',$content_script,'after');
		return $output_text;
    }

}
new PointFinderClientCarouselShortcode();

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_pointfinder_single_pf_clientcarousel extends WPBakeryShortCode {
    }
}