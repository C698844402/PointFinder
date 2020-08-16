<?php
/*
*
* Visual Composer PointFinder Info Box Shortcode
*
*/

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

class PointFinderInfoBoxShortcode extends WPBakeryShortCode {
	use PointFinderCommonVCFunctions,
	PointFinderCommonFunctions;

    function __construct() {
        add_action( 'vc_after_init', array( $this, 'pointfinder_single_pf_infobox_module_mapping' ) );
        add_shortcode( 'pf_infobox', array( $this, 'pointfinder_single_pf_infobox_module_html' ) );
    }

    

    public function pointfinder_single_pf_infobox_module_mapping() {

      if ( !defined( 'WPB_VC_VERSION' ) ) {
          return;
      }

      $add_css_animation = $this->pf_vc_add_css_animation();
      /**
		*Start : Info Box ----------------------------------------------------------------------------------------------------
		**/
			vc_map( array(
			"name" => esc_html__("PF Info Box", 'pointfindercoreelements'),
			"base" => "pf_infobox",
			"icon" => "pfaicon-archive-2",
			"category" => esc_html__("Point Finder", "pointfindercoreelements"),
			"description" => esc_html__('Info Boxes', 'pointfindercoreelements'),
			"params" => array(
					 array(
						"type" => "dropdown",
						"heading" => esc_html__("Infobox Style", "pointfindercoreelements"),
						"param_name" => "iconbox_style",
						"value" => array(esc_html__("Simple Infobox", "pointfindercoreelements") => "type1", esc_html__("Boxed Simple Infobox", "pointfindercoreelements") => "type2",esc_html__("Icon at Top & Boxed Title + Text", "pointfindercoreelements") => "type3",esc_html__("Simple Infobox & Icon at Right", "pointfindercoreelements") => "type4",esc_html__("Simple Infobox & Icon at Left", "pointfindercoreelements") => "type5",),
					  ),

					  array(
						"type" => "dropdown",
						"heading" => esc_html__("Icon Type", "pointfindercoreelements"),
						"param_name" => "icon_type",
						"value" => array(esc_html__("Predefined Font Icon", "pointfindercoreelements") => "font", esc_html__("No Icon", "pointfindercoreelements") => "no_icon"),
						"description" => esc_html__("Please select an icon type.", "pointfindercoreelements"),
						"dependency" => array('element' => "iconbox_style", 'value' => array('type2'))
					  ),
					  array(
						"type" => "dropdown",
						"heading" => esc_html__("Icon Style", "pointfindercoreelements"),
						"param_name" => "icon_style_outside",
						"value" => array(esc_html__("No border & No Background", "pointfindercoreelements") => "", esc_html__("Rounded Border & Background", "pointfindercoreelements") => "rounded", esc_html__("Rounded Square Border & Background", "pointfindercoreelements") => "rectangle", esc_html__("Square Border & Background", "pointfindercoreelements") => "square"),
						"description" => esc_html__("Please select an icon style for outside area of icon.", "pointfindercoreelements")
					  ),
					  array(
						"type" => "pfa_numeric",
						"heading" => esc_html__("Info Box Border Radius", "pointfindercoreelements"),
						"param_name" => "box_border_radius",
						"description" => esc_html__("Please write a border radius value (px) (Optional) (Numeric only)", "pointfindercoreelements"),
						"value"	=> '0',
					  ),
					  $add_css_animation,

					  array(
						"type" => "pfa_select1",
						"heading" => esc_html__("Please Select an Icon", "pointfindercoreelements"),
						"param_name" => "iconbox_icon_name",
					  ),
					  array(
						"type" => "colorpicker",
						"heading" => esc_html__('Icon Color', 'pointfindercoreelements'),
						"param_name" => "box_icon_color",
					    "edit_field_class" => 'vc_col-sm-3 vc_column'
					  ),
					  array(
						"type" => "colorpicker",
						"heading" => esc_html__('Icon Background', 'pointfindercoreelements'),
						"param_name" => "box_icon_bg_color",
						"edit_field_class" => 'vc_col-sm-3 vc_column'
					  ),
					  array(
						"type" => "colorpicker",
						"heading" => esc_html__('Icon Border Color', 'pointfindercoreelements'),
						"param_name" => "box_icon_border_color",
						"edit_field_class" => 'vc_col-sm-3 vc_column'
					  ),
					  array(
						"type" => "pfa_numeric",
						"heading" => esc_html__("Icon Size", "pointfindercoreelements"),
						"param_name" => "box_icon_size",
						"value"	=> '16',
						"edit_field_class" => 'vc_col-sm-3 vc_column'
					  ),

					  array(
						"type" => "textfield",
						"heading" => esc_html__("Infobox Title", "pointfindercoreelements"),
						"param_name" => "box_title",
						"admin_label" => true
					  ),
					  array(
						"type" => "colorpicker",
						"heading" => esc_html__('Title Color', 'pointfindercoreelements'),
						"param_name" => "box_title_color",
						"description" => esc_html__("Leave empty for use default color. (Optional)", "pointfindercoreelements"),
						"edit_field_class" => 'vc_col-sm-4 vc_column'
					  ),
					  array(
						"type" => "colorpicker",
						"heading" => esc_html__('Title Hover Color', 'pointfindercoreelements'),
						"param_name" => "box_title_hover_color",
						"description" => esc_html__("Leave empty for use default color. (Optional)", "pointfindercoreelements"),
						"edit_field_class" => 'vc_col-sm-4 vc_column'
					  ),
					  array(
						"type" => "pfa_numeric",
						"heading" => esc_html__('Title Text Size', 'pointfindercoreelements'),
						"param_name" => "box_title_textsize",
						"description" => esc_html__("Leave empty for use default size. (Optional)", "pointfindercoreelements"),
						"edit_field_class" => 'vc_col-sm-4 vc_column',
						"value"	=> 16
					  ),
					  array(
						"type" => "textarea_html",
						"class" => "box_content",
						"heading" => esc_html__("Infobox Content", "pointfindercoreelements"),
						"param_name" => "content",
						"value" => '<p>'.esc_html__("Box content goes here, click edit button to change this text.", "pointfindercoreelements").'</p>',
						"description" => esc_html__("Icon box content.", "pointfindercoreelements")
					  ),
					  array(
						"type" => "pfa_numeric",
						"heading" => esc_html__('Content Text Size', 'pointfindercoreelements'),
						"param_name" => "box_content_textsize",
						"description" => esc_html__("Leave empty for use default size. (Optional)", "pointfindercoreelements"),
						"value"	=> 13,
						"edit_field_class" => 'vc_col-sm-4 vc_column'
					  ),
					  array(
						"type" => "colorpicker",
						"heading" => esc_html__('Content Background', 'pointfindercoreelements'),
						"param_name" => "box_content_bg_color",
						"description" => esc_html__("Leave empty for use default color. (Optional)", "pointfindercoreelements"),
						"edit_field_class" => 'vc_col-sm-4 vc_column'
					  ),
					  array(
						"type" => "colorpicker",
						"heading" => esc_html__('Content Border Color', 'pointfindercoreelements'),
						"param_name" => "box_content_border_color",
						"description" => esc_html__("Leave empty for use default color. (Optional)", "pointfindercoreelements"),
						"edit_field_class" => 'vc_col-sm-4 vc_column'
					  ),

					  array(
						"type" => "dropdown",
						"heading" => esc_html__("Title & Content Text Align", "pointfindercoreelements"),
						"param_name" => "icon_box_align",
						"value" => array(esc_html__("Left", "pointfindercoreelements") => "left", esc_html__("Center", "pointfindercoreelements") => "center", esc_html__("Right", "pointfindercoreelements") => "right"),
						"dependency" => array('element' => "iconbox_style", 'value' => array('type2','type3','type4','type5')),

					  ),
					  array(
						"type" => "dropdown",
						"heading" => esc_html__("Infobox Background Transparency", "pointfindercoreelements"),
						"param_name" => "box_bg_opacity",
						"value" => array(
						 esc_html__("No Transparency", "pointfindercoreelements") => "1",
						 esc_html__("%100", "pointfindercoreelements") => "0",
						 esc_html__("%90", "pointfindercoreelements") => "0.1",
						 esc_html__("%80", "pointfindercoreelements") => "0.2",
						 esc_html__("%70", "pointfindercoreelements") => "0.3",
						 esc_html__("%60", "pointfindercoreelements") => "0.4",
						 esc_html__("%50", "pointfindercoreelements") => "0.5",
						 esc_html__("%40", "pointfindercoreelements") => "0.6",
						 esc_html__("%30", "pointfindercoreelements") => "0.7",
						 esc_html__("%20", "pointfindercoreelements") => "0.8",
						 esc_html__("%10", "pointfindercoreelements") => "0.9",

						 ),
						"description" => esc_html__("Please select a transparency value if want to use for background. (Optional) %100 = transparent bg", "pointfindercoreelements"),
						"dependency" => array('element' => "iconbox_style", 'value' => array('type2','type3','type4','type5')),

					  ),
					  array(
						"type" => "dropdown",
						"heading" => esc_html__("On click", "pointfindercoreelements"),
						"param_name" => "onclick",
						"value" => array(esc_html__("Do nothing", "pointfindercoreelements") => "link_no", esc_html__("Open custom link", "pointfindercoreelements") => "custom_link"),
						"description" => esc_html__("Define action for onclick event if needed.", "pointfindercoreelements")
					  ),
					  array(
						"type" => "vc_link",
						"heading" => esc_html__("URL (Link)", "pointfindercoreelements"),
						"param_name" => "link",
						"description" => esc_html__("Infobox link url.", "pointfindercoreelements"),
						"dependency" => Array('element' => "onclick", 'value' => array('custom_link'))
					  ),
					  array(
						"type" => "dropdown",
						"heading" => esc_html__("Read More: Add to Box", "pointfindercoreelements"),
						"param_name" => "readmore",
						"value" => array(esc_html__("No Text", "pointfindercoreelements") => "text_no", esc_html__("Yes, I want to use it.", "pointfindercoreelements") => "text_link"),
						"description" => esc_html__("Are you want to put a read more text below the box?", "pointfindercoreelements")
					  ),
					  array(
						"type" => "textfield",
						"heading" => esc_html__("Read More: Text", "pointfindercoreelements"),
						"param_name" => "readmore_text",
						"value" => 'Read more',
						"description" => esc_html__("Please enter read more text", "pointfindercoreelements"),
						"dependency" => array('element' => "readmore", 'value' => array('text_link'))
					  ),

				)
				)
			);
		/**
		*End : Info Box ----------------------------------------------------------------------------------------------------
		**/

    }


    public function pointfinder_single_pf_infobox_module_html( $atts,$content ) {
		extract(shortcode_atts(array(
			'onclick'	=> 'link_no',
			'link'	=> '',
			'box_bg_opacity'	=> '0',
			'box_border_radius'	=> '0',
			'box_content_border_color'	=> '#efefef',
			'box_content_bg_color'	=> '#fafafa',
			'box_content_textsize'	=> '13',
			'box_title_color'	=> '#444',
			'box_title_hover_color'	=> '#000',
			'box_title'	=> '',
			'box_title_textsize'	=> '16',
			'box_icon_size'	=> '28',
			'box_icon_border_color'	=> '',
			'box_icon_bg_color'	=> '#ccc',
			'box_icon_color'	=> '#000',
			'box_image1'	=> '',
			'iconbox_style'	=> 'type1',
			'iconbox_icon_name'	=> '',
			'css_animation' => '',
			'icon_type'	=> 'font',
			'icon_style_outside'	=> 'none',
			//'box_icon_image_size'	=> '',
			'readmore' => 'text_no',
			'readmore_text' => 'Read more',
			'icon_box_align' => 'left'
		), $atts));
		
		//pointfinderhex2rgb($hex,$opacity) box_content_textsize
		$align_icon_size = ($box_icon_size + (2 + 30));
		if($icon_style_outside != 'none'){$align_icon_size2 = ($box_icon_size + (2 +20));$margin_right_number=22;}else{$align_icon_size2 = ($box_icon_size + (2 +10));$margin_right_number=12;}
		$box_style_text = ' ';
		$icon_style_text = $icon_style_text2 = ' ';
		$box_title_style_text = ' ';
		$box_content_style_text = ' ';
		$topmargin_style_text = ' ';
		$box_readmore_style_text = ' ';
		$cssanimation_text = '';
		
		$link = ($link=='||') ? '' : $link;
		$link = vc_build_link($link);
		$a_href = $link['url'];
		$a_title = $link['title'];
		$a_target = $link['target'];
		
		if($iconbox_icon_name != ''){
			$icon_style_text .= 'style="';
			$icon_style_text2 .= 'style="';

			if($box_icon_color != ''){ $icon_style_text .= 'color:'.$box_icon_color.';';}
			
			if($icon_style_outside != 'none'){
				if($box_icon_bg_color != ''){ $icon_style_text .= 'background-color:'.$box_icon_bg_color.';';}
				if($box_icon_border_color != ''){ $icon_style_text .= 'border:1px solid '.$box_icon_border_color.';';}
				
			}
			
			if($box_icon_size != ''){ 
				$icon_style_text .= 'font-size:'.$box_icon_size.'px;';
			}

			if($box_icon_size != '' && ($iconbox_style == 'type5' || $iconbox_style == 'type4' || $iconbox_style == 'type3') && $icon_style_outside != 'none'){ 
				$icon_style_text .= 'line-height:'.($box_icon_size + ($box_icon_size/2)).'px;width:'.($box_icon_size*2).'px;height:'.($box_icon_size*2).'px;';
				$icon_style_text2 .= 'line-height:'.($box_icon_size + ($box_icon_size/2)).'px;';
			}

			if($box_icon_size != '' && ($iconbox_style == 'type1' || $iconbox_style == 'type2') && $icon_style_outside != 'none'){ 
				$icon_style_text .= 'line-height:'.($box_icon_size + ($box_icon_size/2)).'px;width:'.($box_icon_size*2).'px;height:'.($box_icon_size*2).'px;';
				$icon_style_text2 .= 'line-height:'.($box_icon_size + ($box_icon_size/2)).'px;';
			}
			if($box_icon_size != '' &&  ($iconbox_style == 'type1' || $iconbox_style == 'type2') && $icon_style_outside == 'none'){ 
				$icon_style_text .= 'margin-left:0;';
			}
			
			if(($iconbox_style == 'type5' || $iconbox_style == 'type4' || $iconbox_style == 'type3') && $icon_style_outside == 'none'){
				$icon_style_text .= 'margin-top: -2px;';
			}
			if(($iconbox_style == 'type3') && $icon_box_align == 'center' && $icon_style_outside == 'none'){
				$icon_style_text .= 'margin-top: 20px;';
			}
			
			/*Special actions*/
			if($iconbox_style == 'type3'){
				if($icon_style_outside != 'none'){
					$icon_style_text .= 'margin-left:-'.($align_icon_size/2).'px;top:-'.($align_icon_size/2).'px;';
				}else{
					$icon_style_text .= 'margin-left:-'.(($align_icon_size/2)+5).'px;top:2px;';
				}
			}
			
			$icon_style_text .= '"';
			$icon_style_text2 .= '"';
		}
		
		$box_style_text .= 'style="';
		if($iconbox_style != '' && $iconbox_style != 'type1' && $iconbox_style != 'type4' && $iconbox_style != 'type5'){
			
			$box_style_text .= 'background:'.$this->pointfinderhex2rgb($box_content_bg_color,$box_bg_opacity).';';
			$box_style_text .= 'border:1px solid '.$this->pointfinderhex2rgb($box_content_border_color,$box_bg_opacity).';';
			/*Special actions*/
			if($iconbox_style == 'type3' && $icon_style_outside != 'none' && $icon_type == 'font'){
				$box_style_text .= 'margin-top:'.(($align_icon_size/2)+18).'px;';
			}elseif($iconbox_style == 'type3' && $icon_style_outside == 'none'){
				$box_style_text .= 'margin-top:18px;';
			}elseif($iconbox_style == 'type3' && $icon_style_outside != 'none' && $icon_type != 'font'){
				$box_style_text .= 'margin-top:18px;';
			}
			
			if($box_border_radius != '0'){
				$box_style_text .= 'border-radius:'.$box_border_radius.'px; -webkit-border-radius:'.$box_border_radius.'px; -moz-border-radius:'.$box_border_radius.'px; -o-border-radius:'.$box_border_radius.'px; -ms-border-radius:'.$box_border_radius.'px;';
			}
			
		}
		if($iconbox_style != 'type3' ){
			$box_style_text .= 'margin-top:18px;';
			if($iconbox_style == 'type5' || $iconbox_style == 'type4' ){$box_style_text .= 'margin-bottom:15px;';}
		}
		$box_style_text .= '"';

		$output = '';
		
		if ( $css_animation != '' ) {
			wp_enqueue_script( 'waypoints' );
			$cssanimation_text = ' wpb_animate_when_almost_visible wpb_'.$css_animation;
		}
		
		$output .= '<div class="pf-iconbox-wrapper pfib-'.$iconbox_style.$cssanimation_text.'"'.$box_style_text.'>';
		
		if($iconbox_icon_name != '' && $icon_type == 'font'){//If icon exist
			$output .= '<div class="pficonboxcover pf-iconbox-'. $icon_style_outside .'-cover"'.$icon_style_text.'>';
			$output .= '<i class="'.$iconbox_icon_name .'"'.$icon_style_text2.'></i>';
			$output .= '</div>';
		}
		
		if($iconbox_style == 'type3'){//Margin for type3 
			$topmargin_style_text .= 'style="';
			if($icon_type == 'font'){
				
				if ($icon_box_align == 'center' && $icon_style_outside == 'none') {
					$topmargin_style_text .= 'margin-top:'.($box_icon_size+3).'px';
				}else{
					$topmargin_style_text .= 'margin-top:'.($box_icon_size-17).'px';
				}
			}
			$topmargin_style_text .= '"';
			$output .= '<div class="pf-iconbox-topmargin"'.$topmargin_style_text.'></div>';
		}
		
		if($box_title != ''){//if title exist
			$box_title_style_text .= 'style="';
			if($icon_type != 'font' && $iconbox_style == 'type2'){$box_title_style_text .= 'display:block;';}
			if($iconbox_style != 'type4'){$box_title_style_text .= 'text-align:'.$icon_box_align.';';};
			if($box_title_color != ''){$box_title_style_text .= 'color:'.$box_title_color.';';};
			if($box_title_textsize != ''){$box_title_style_text .= 'font-size:'.$box_title_textsize.'px;';};
			if($iconbox_style == 'type1' || $iconbox_style == 'type2'){
				$box_title_style_text .= 'margin-left:2px;';
			}
			if($iconbox_style == 'type5'){$box_title_style_text .= 'margin-left:'.($align_icon_size2 + $margin_right_number).'px;';}
			if($iconbox_style == 'type4'){$box_title_style_text .= 'margin-right:'.($align_icon_size2 + $margin_right_number).'px;';}
			$box_title_style_text .= '"';
			
			if($onclick != 'link_no'){
				//wp_die(print_r($link));
				$output .= '<a href="'.$link['url'].'" title="'.$link['title'].'" target="'.$link['target'].'" onMouseOver="this.style.color=\''.$box_title_hover_color.'\'" onMouseOut="this.style.color=\''.$box_title_color.'\'"';
			}else{
				$output .= '<div';
			}
			
			$output .= ' class="pf-iconbox-title"'.$box_title_style_text.' >'.$box_title.'';
			
			if($onclick != 'link_no'){
				$output .= '</a>';
			}else{
				$output .= '</div>';
			}
		}
		
		if($content != ''){//If content exist
			$box_content_style_text .= 'style="';
			if($iconbox_style != 'type4'){$box_content_style_text .= 'text-align:'.$icon_box_align.';';};
			if($box_content_textsize != ''){$box_content_style_text .= 'font-size:'.$box_content_textsize.'px;';}
			
			if($iconbox_style == 'type5'){$box_content_style_text .= 'margin-left:'.($align_icon_size2 + $margin_right_number).'px;';}
			if($iconbox_style == 'type4'){$box_content_style_text .= 'margin-right:'.($align_icon_size2 + $margin_right_number).'px;';}
			
			$box_content_style_text .= '"';
			
			$output .= '<div class="pf-iconbox-text"'.$box_content_style_text.'>'.wpb_js_remove_wpautop($content, true).'';
			
			if($readmore != 'text_no'){
			
			$box_readmore_style_text .= 'style="';
			if($iconbox_style != 'type4'){$box_readmore_style_text .= 'text-align:'.$icon_box_align.';';};
			if($box_content_textsize != ''){$box_readmore_style_text .= 'font-size:'.$box_content_textsize.'px;';}
			if($box_title_color != ''){$box_readmore_style_text .= 'color:'.$box_title_color.';';};
			$box_readmore_style_text .= '"';
			
			if($onclick != 'link_no'){
					$output .= '<a href="'.$link['url'].'" title="'.$link['title'].'" target="'.$link['target'].'" onMouseOver="this.style.color=\''.$box_title_hover_color.'\'" onMouseOut="this.style.color=\''.$box_title_color.'\'"';
				}else{
					$output .= '<div';
				}
				$output .= ' class="pf-iconbox-readmore"'.$box_readmore_style_text.' >'.$readmore_text.'';
				if($onclick != 'link_no'){
					$output .= '</a>';
				}else{
					$output .= '</div>';
				}
			}

			
			$output .= '</div>';
		}
		
		
		$output .= '</div>';
		
		
	return $output;
}

}
new PointFinderInfoBoxShortcode();

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_pointfinder_single_pf_infobox extends WPBakeryShortCode {
    }
}