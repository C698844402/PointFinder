<?php
/*
*
* Visual Composer PointFinder Static Grid Shortcode
*
*/

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

class PointFinderLocationListShortcode extends WPBakeryShortCode {
	use PointFinderOptionFunctions,
	  PointFinderCommonVCFunctions;

    function __construct() {
        add_action( 'vc_after_init', array( $this, 'pointfinder_single_pf_llist_widget_module_mapping' ) );
        add_shortcode( 'pf_llist_widget', array( $this, 'pointfinder_single_pf_llist_widget_module_html' ) );
    }

    

    public function pointfinder_single_pf_llist_widget_module_mapping() {

      if ( !defined( 'WPB_VC_VERSION' ) ) {
          return;
      }

      $PFVEX_GetTaxValues3 = $this->PFVEX_GetTaxValues('pointfinderlocations','setup3_pointposttype_pt5','Locations');

      /**
		*Start : Location List ----------------------------------------------------------------------------------------------------
		**/
			vc_map( array(
				"name" => esc_html__("PF Location List", 'pointfindercoreelements'),
				"base" => "pf_llist_widget",
				"icon" => "pfaicon-chat-empty",
				"category" => esc_html__("Point Finder", "pointfindercoreelements"),
				"description" => esc_html__("Location List Element", 'pointfindercoreelements'),
				"params" => array(
						array(
						  "type" => "pf_info_line_vc_field",
						  "heading" => esc_html__("If want to change main category colors please visit Locations > Category edit. You will find color options.", "pointfindercoreelements"),
						  "param_name" => "informationfield",
						),
						array(
							"type" => "pf_info_line_field",
							"param_name" => "pf_info_field5",
						 ),
						array(
						  "type" => "dropdown",
						  "heading" => esc_html__("Default Listing Columns", "pointfindercoreelements"),
						  "param_name" => "cols",
						  "value" => array('4 Columns'=>'4','3 Columns'=>'3','2 Columns'=>'2','1 Column'=>'1'),
						  "edit_field_class" => 'vc_col-sm-4 vc_column'
						),
						array(
						  "type" => "dropdown",
						  "heading" => esc_html__("Order By", "pointfindercoreelements"),
						  "param_name" => "orderby",
						  "value" => array(esc_html__("Title Order", "pointfindercoreelements")=>'name',esc_html__("ID Order", "pointfindercoreelements")=>'ID',esc_html__("Count Order", "pointfindercoreelements")=>'count'),
						  "edit_field_class" => 'vc_col-sm-4 vc_column'
						),
						array(
						  "type" => "dropdown",
						  "heading" => esc_html__("Order", "pointfindercoreelements"),
						  "param_name" => "order",
						  "value" => array('ASC'=>'ASC','DESC'=>'DESC'),
						  "edit_field_class" => 'vc_col-sm-4 vc_column'
						),
						array(
							"type" => "pf_info_line_field",
							"param_name" => "pf_info_field1",
						 ),
						array(
						  "type" => "pfa_select2",
						  "heading" => esc_html__("Excluding Locations", "pointfindercoreelements"),
						  "param_name" => "excludingcats",
						  "value" => $PFVEX_GetTaxValues3,
						  "description"=>esc_html__('These locations will be hidden. (optional)','pointfindercoreelements')
						),
						array(
							"type" => "pf_info_line_field",
							"param_name" => "pf_info_field2",
						 ),
						array(
							'type' => 'checkbox',
							'heading' => esc_html__( 'Hide Empty Locations for Main Locations', 'pointfindercoreelements' ),
							'param_name' => 'hideemptyformain',
							'description' => esc_html__( 'If "YES", empty locations will be hidden.', 'pointfindercoreelements' ),
							'value' => array( esc_html__( 'Yes, please', 'pointfindercoreelements' ) => 'yes' ),
							"edit_field_class" => 'vc_col-sm-6 vc_column'
						),

						array(
							'type' => 'checkbox',
							'heading' => esc_html__( 'Hide Empty Locations for Sub Locations', 'pointfindercoreelements' ),
							'param_name' => 'hideemptyforsub',
							'description' => esc_html__( 'If "YES", empty locations will be hidden.', 'pointfindercoreelements' ),
							'value' => array( esc_html__( 'Yes, please', 'pointfindercoreelements' ) => 'yes' ),
							"edit_field_class" => 'vc_col-sm-6 vc_column'
						),
						array(
							"type" => "pf_info_line_field",
							"param_name" => "pf_info_field3",
						 ),
						array(
							'type' => 'checkbox',
							'heading' => esc_html__( 'Show counts for Main Locations', 'pointfindercoreelements' ),
							'param_name' => 'showcountmain',
							'description' => esc_html__( 'If "YES", location count will be visible.', 'pointfindercoreelements' ),
							'value' => array( esc_html__( 'Yes, please', 'pointfindercoreelements' ) => 'yes' ),
							"edit_field_class" => 'vc_col-sm-6 vc_column'
						),
						array(
							'type' => 'checkbox',
							'heading' => esc_html__( 'Show counts for Sub Locations', 'pointfindercoreelements' ),
							'param_name' => 'showcountsub',
							'description' => esc_html__( 'If "YES", location count will be visible.', 'pointfindercoreelements' ),
							'value' => array( esc_html__( 'Yes, please', 'pointfindercoreelements' ) => 'yes' ),
							"edit_field_class" => 'vc_col-sm-6 vc_column'
						),
						array(
							"type" => "pf_info_line_field",
							"param_name" => "pf_info_field4",
						 ),
						array(
							"type" => "colorpicker",
							"heading" => esc_html__('Sub Cat. BG Color', 'pointfindercoreelements'),
							"param_name" => "subcatbgcolor",
							"description" => esc_html__("Leave empty for use default color. (Optional)", "pointfindercoreelements"),
							"edit_field_class" => 'vc_col-sm-4 vc_column'
						  ),
						array(
							"type" => "colorpicker",
							"heading" => esc_html__('Sub Cat. Text Color', 'pointfindercoreelements'),
							"param_name" => "subcattextcolor",
							"description" => esc_html__("Leave empty for use default color. (Optional)", "pointfindercoreelements"),
							"edit_field_class" => 'vc_col-sm-4 vc_column'
						  ),
						array(
							"type" => "colorpicker",
							"heading" => esc_html__('Sub Cat. Text Hover Color', 'pointfindercoreelements'),
							"param_name" => "subcattextcolor2",
							"description" => esc_html__("Leave empty for use default color. (Optional)", "pointfindercoreelements"),
							"edit_field_class" => 'vc_col-sm-4 vc_column'
						  ),
						array(
							"type" => "pf_info_line_field",
							"param_name" => "pf_info_field6",
						 ),

						array(
						  "type" => "dropdown",
						  "heading" => esc_html__("Sub Location Limit", "pointfindercoreelements"),
						  "param_name" => "subcatlimit",
						  "description"=>esc_html__('How many sub categories will be visible.','pointfindercoreelements'),
						  "value" => array('0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','25'=>'25','30'=>'30','40'=>'40','50'=>'50'),
						  "edit_field_class" => 'vc_col-sm-4 vc_column'
						),
						array(
							"type" => "checkbox",
							"heading" => esc_html__('View All Link', 'pointfindercoreelements'),
							"param_name" => "viewalllink",
							"description" => esc_html__("Do you want to see View All link?", "pointfindercoreelements"),
							"edit_field_class" => 'vc_col-sm-4 vc_column',
							'value' => array( esc_html__( 'Yes, please', 'pointfindercoreelements' ) => 'yes' )
						  ),
						array(
							"type" => "checkbox",
							"heading" => esc_html__("Title Uppercase", 'pointfindercoreelements'),
							"param_name" => "titleuppercase",
							"description" => esc_html__("Do you want to see uppercase titles?", "pointfindercoreelements"),
							"edit_field_class" => 'vc_col-sm-4 vc_column',
							'value' => array( esc_html__( 'Yes, please', 'pointfindercoreelements' ) => 'yes' )
						  ),
					)
				)
			);
		/**
		*End : Location List ----------------------------------------------------------------------------------------------------
		**/

    }


    public function pointfinder_single_pf_llist_widget_module_html( $atts ) {
	$output = $title = $number = $el_class = '';
	extract( shortcode_atts( array(
		'cols' => 4,
		'order' => 'ASC',
		'orderby' => 'name',
		'excludingcats' => array(),
		'hideemptyformain' => '',
		'hideemptyforsub' => '',
		'showcountmain' => false,
		'showcountsub' => false,
		'subcatbgcolor' => '#fafafa',
		'subcattextcolor' => '#494949',
		'subcattextcolor2' => '#000',
		'viewalllink' => '',
		'titleuppercase' => '',
		'subcatlimit' => 0,
	), $atts ) );
	

	switch ($cols) {
		case 4:
			$cols_output = 'col-lg-3 col-md-4 col-sm-6 col-xs-12';
			break;
		case 3:
			$cols_output = 'col-lg-4 col-md-4 col-sm-6 col-xs-12';
			break;
		case 2:
			$cols_output = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
			break;
		case 1:
			$cols_output = 'col-lg-12';
			break;
		
		default:
			$cols_output = 'col-lg-3 col-md-4 col-sm-6 col-xs-12';
			break;
	}

	if ($hideemptyformain == 'yes') {$hideemptyformain = true;}else{$hideemptyformain = false;}
	if ($hideemptyforsub == 'yes') {$hideemptyforsub = true;}else{$hideemptyforsub = false;}
	if ($showcountsub == 'yes') {$show_count_child = 1;}else{$show_count_child = 0;}
	if ($showcountmain == 'yes') {$show_count_main = 1;}else{$show_count_main = 0;}
	if ($viewalllink == 'yes') {$show_viewall_child = 1;}else{$show_viewall_child = 0;}

	if($subcatlimit != 0){$subcat_limit = $subcatlimit - 1;}else{$subcat_limit = 0;}
	$title_uppercase = $titleuppercase;


	/*Extra Styles*/
	$style_text_child = ' style="';
	$style_text_main = ' style="';

		$style_text_main .= 'font-weight:bold;';
		$style_text_child .= 'font-weight:normal;background-color:'.$subcatbgcolor.';color:'.$subcattextcolor.';';

		if ($title_uppercase == 1) {
			$style_text_main .= 'text-transform:uppercase;';
			$style_text_child .= 'text-transform:none;';
		}

	$style_text_child .= '"';
	$style_text_main .= '"';


	$taxonomies = array( 
	    'pointfinderlocations'
	);
	if (!empty($excludingcats)) {
		$excludingcats = pfstring2BasicArray($excludingcats);
	}
	$args = array(
	    'orderby'           => $orderby, 
	    'order'             => $order,
	    'hide_empty'        => $hideemptyformain, 
	    'exclude'           => array(), 
	    'exclude_tree'      => $excludingcats, 
	    'include'           => array(),
	    'number'            => '', 
	    'fields'            => 'all', 
	    'slug'              => '',
	    'parent'            => '',
	    'hierarchical'      => true, 
	    'child_of'          => 0, 
	    'get'               => '', 
	    'name__like'        => '',
	    'description__like' => '',
	    'pad_counts'        => true, 
	    'offset'            => '', 
	    'search'            => '', 
	    'cache_domain'      => 'core'
	); 

	$listing_terms = get_terms($taxonomies, $args);
	$listing_meta = get_option('pointfinderlocationsex_vars');

	$output = '<div class="vc_wp_posts wpb_content_element'.$el_class.'">';

	if ( ! empty( $listing_terms ) && ! is_wp_error( $listing_terms ) ) {
	    $count = count( $listing_terms );
	    $i = 0;
	    $term_list = '<ul class="pointfinder-terms-archive pf-row">';
		    foreach ( $listing_terms as $term ) {
		        if ($term->parent == 0) {

		        	/*get term specifications*/
		        	$style_text_main_custom = $iconimage_url = $this_term_icon = $this_term_catbg = $this_term_cattext = $this_term_cattext2 = $this_term_iconwidth = $hover_text = '';

		        	if (isset($listing_meta[$term->term_id])) {
		        		$this_term_icon = "";
		        		$this_term_iconwidth = "";
		        		$this_term_iconfont = (isset($listing_meta[$term->term_id]['pf_icon_of_listingfs']))? $listing_meta[$term->term_id]['pf_icon_of_listingfs']:'';
		        		$this_term_catbg = (isset($listing_meta[$term->term_id]['pf_catbg_of_listing']))? $listing_meta[$term->term_id]['pf_catbg_of_listing']:'#ededed';
		        		$this_term_catbg = (isset($listing_meta[$term->term_id]['pf_catbg_of_listing']))? $listing_meta[$term->term_id]['pf_catbg_of_listing']:'#ededed';
		        		$this_term_cattext = (isset($listing_meta[$term->term_id]['pf_cattext_of_listing']))? $listing_meta[$term->term_id]['pf_cattext_of_listing']:'#494949';
		        		$this_term_cattext2 = (isset($listing_meta[$term->term_id]['pf_cattext2_of_listing']))? $listing_meta[$term->term_id]['pf_cattext2_of_listing']:'#000';

		        		if (empty($this_term_iconwidth)) {
		        			$this_term_iconwidth = 20;
		        		}
		        		/*icon*/
		        		if (!empty($this_term_icon) && is_array($this_term_icon)) {
		        			$iconimage = wp_get_attachment_image_src($this_term_icon[0], 'full');
		        			$iconimage_url = '<span class="pf-main-term-icon"><img src="'.$iconimage[0].'" width="'.$this_term_iconwidth.'"></span>';
		        		}

		        		$style_text_main_custom .=' style="';
		        		$style_text_main_custom .= 'background-color:'.$this_term_catbg.';';
		        		$style_text_main_custom .= 'color:'.$this_term_cattext.';';

		        		$hover_text = ' data-hovercolor="'.$this_term_cattext2.'" data-standartc="'.$this_term_cattext.'"';
		        		
		        		$style_text_main_custom .='"';
		        	}



		        	$term_list .= '<li class="pf-grid-item '.$cols_output.' pf-main-term"'.$style_text_main.'>';
		        	if (!empty($this_term_iconfont)) {
		        		$term_list .= '<a href="' . get_term_link( $term ) . '" title="' . sprintf( __( 'View all posts under %s', 'pointfindercoreelements' ), $term->name ) . '"'.$style_text_main_custom.''.$hover_text.'><i class="'.$this_term_iconfont.'"></i> '. $iconimage_url . $term->name . ' ';
		        	}else{
		        		$term_list .= '<a href="' . get_term_link( $term ) . '" title="' . sprintf( __( 'View all posts under %s', 'pointfindercoreelements' ), $term->name ) . '"'.$style_text_main_custom.''.$hover_text.'>'. $iconimage_url . $term->name . ' ';
		        	}
		        	
		        	if ($show_count_main == 1) {
		        	$term_list .= '<span class="pull-right pf-main-term-number">('.$term->count.')</span>';
		        	}
		        	$term_list .= '</a>';
		        	
		        	/* Check term childs */

		        		$k = 0;
		        		$term_list_ex = '';
		        		if ($subcat_limit > 0) {
			        		$args_sub = array(
							    'orderby'           => $orderby, 
		   						'order'             => $order,
							    'hide_empty'        => $hideemptyforsub, 
							    'exclude'           => array(), 
							    'exclude_tree'      => array(), 
							    'include'           => array(),
							    'number'            => '', 
							    'fields'            => 'all', 
							    'slug'              => '',
							    'parent'            => $term->term_id,
							    'hierarchical'      => true, 
							    'child_of'          => 0, 
							    'get'               => '', 
							    'name__like'        => '',
							    'description__like' => '',
							    'pad_counts'        => true, 
							    'offset'            => '', 
							    'search'            => '', 
							    'cache_domain'      => 'core'
							); 
			        		$listing_terms_child = get_terms($taxonomies, $args_sub);
			        		foreach ($listing_terms_child as $term_child) {

			        			if($k <= $subcat_limit){
			        				$term_list_ex .= '<li class="pf-child-term"'.$style_text_child.'>';
			        				$term_list_ex .= '<a href="' . get_term_link( $term_child ) . '" title="' . sprintf( __( 'View all posts under %s', 'pointfindercoreelements' ), $term_child->name ) . '" data-hovercolor="'.$subcattextcolor2.'" data-standartc="'.$subcattextcolor.'"'.$style_text_child.'>' . $term_child->name . '</a>';
			        				if ($show_count_child == 1) {
			        					$term_list_ex .= '<span class="pull-right">('.$term_child->count.')</span>';
			        				}
			        				$term_list_ex .= '</li>';
			        				$k++;
			        			};
			        		}
		        		}
		        		if ($k > 0) {
		        			$term_list .= '<ul class="pf-child-term-main">';
		        			$term_list .= $term_list_ex;
		        			if ($show_viewall_child == 1) {
		        				$term_list .= '<li class="pf-child-term pf-child-term-viewall"'.$style_text_child.'><a href="' . get_term_link( $term ) . '" title="' . sprintf( __( 'View all posts under %s', 'pointfindercoreelements' ), $term->name ) . '" data-hovercolor="'.$subcattextcolor2.'" data-standartc="'.$subcattextcolor.'">' . esc_html__('View All','pointfindercoreelements') . '</a></li>';
		        			}
		        			$term_list .= '</ul>';
		        		}

		        	$term_list .= '</li>';
		        }
		    }
	    $term_list .= '</ul>';
	    $term_list .= '</div>';

	    $output .=  $term_list;
	}
	

	
	return $output;
}

}
new PointFinderLocationListShortcode();

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_pointfinder_single_pf_llist_widget extends WPBakeryShortCode {
    }
}