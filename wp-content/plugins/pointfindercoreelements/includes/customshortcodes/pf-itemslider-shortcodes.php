<?php
/*
*
* Visual Composer PointFinder Static Grid Shortcode
*
*/

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

class PointFinderListingSliderShortcode extends WPBakeryShortCode {
	use PointFinderOptionFunctions,
	  PointFinderCommonFunctions,
	  PointFinderWPMLFunctions,
	  PointFinderReviewFunctions,
	  PointFinderCommonVCFunctions;

    function __construct() {
        add_action( 'vc_after_init', array( $this, 'pointfinder_single_pf_itemslider_module_mapping' ) );
        add_shortcode( 'pf_itemslider', array( $this, 'pointfinder_single_pf_itemslider_module_html' ) );
    }

    

    public function pointfinder_single_pf_itemslider_module_mapping() {

      if ( !defined( 'WPB_VC_VERSION' ) ) {
          return;
      }

      $setup3_pointposttype_pt7 = $this->PFSAIssetControl('setup3_pointposttype_pt7','','Listing Types');
        $setup3_pointposttype_pt6 = $this->PFSAIssetControl('setup3_pointposttype_pt6','','Features');
        $setup3_pointposttype_pt5 = $this->PFSAIssetControl('setup3_pointposttype_pt5','','Locations');
        $setup3_pointposttype_pt4 = $this->PFSAIssetControl('setup3_pointposttype_pt4','','Item Types');
        $setup3_pt14 = $this->PFSAIssetControl('setup3_pt14','','Conditions');
        $setup3_pt14_check = $this->PFSAIssetControl('setup3_pt14_check','','0');

        //Check taxonomies
        $setup3_pointposttype_pt4_check = $this->PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
        $setup3_pointposttype_pt5_check = $this->PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
        $setup3_pointposttype_pt6_check = $this->PFSAIssetControl('setup3_pointposttype_pt6_check','','1');
        $setup3_pointposttype_pt6_status = $this->PFSAIssetControl('setup3_pointposttype_pt6_status','','1');

        //Default grid settings from admin
        $setup22_searchresults_background = $this->PFSAIssetControl('setup22_searchresults_background','','#ffffff');
        $setup22_searchresults_headerbackground = $this->PFSAIssetControl('setup22_searchresults_headerbackground','','#fafafa');
        $setup22_searchresults_background2 = $this->PFSAIssetControl('setup22_searchresults_background2','','#fafafa');


        $PFVEX_GetTaxValues1 = $this->PFVEX_GetTaxValues('pointfinderltypes','setup3_pointposttype_pt7','Listing Types');
        $PFVEX_GetTaxValues2 = $this->PFVEX_GetTaxValues('pointfinderitypes','setup3_pointposttype_pt4','Item Types');
        $PFVEX_GetTaxValues3 = $this->PFVEX_GetTaxValues('pointfinderlocations','setup3_pointposttype_pt5','Locations');
        $PFVEX_GetTaxValues4 = $this->PFVEX_GetTaxValues('pointfinderfeatures','setup3_pointposttype_pt6','Features');
        $PFVEX_GetTaxValues5 = $this->PFVEX_GetTaxValues('pointfinderconditions','setup3_pt14','Conditions');

      /**
		*Start : Item Slider ----------------------------------------------------------------------------------------------------
		**/
			$PFVEXFields_Item_Slider = array();
			$PFVEXFields_Item_Slider['name'] = esc_html__("PF Listing Slider", 'pointfindercoreelements');
			$PFVEXFields_Item_Slider['base'] = "pf_itemslider";
			$PFVEXFields_Item_Slider['controls'] = "full";
			$PFVEXFields_Item_Slider['icon'] = "pfaicon-doc-landscape";
			$PFVEXFields_Item_Slider['category'] = "Point Finder";
			$PFVEXFields_Item_Slider['description'] = esc_html__("Item slider", 'pointfindercoreelements');
			$PFVEXFields_Item_Slider['params'] = "";
			$PFVEXFields_Item_Slider['params'] = array();

			array_push($PFVEXFields_Item_Slider['params'],

				array(
			        "type" => "textfield",
			        "heading" => esc_html__("Item IDs", "pointfindercoreelements"),
			        "param_name" => "posts_in",
			        "description" => esc_html__('Fill this field with items ID numbers separated by commas (,), to retrieve only them. Ex: 171,172,173 (Optional)', "pointfindercoreelements")
			     ),
				array(
				  "type" => "pfa_select2",
				  "heading" => $setup3_pointposttype_pt7,
				  "param_name" => "listingtype",
				  "value" => $PFVEX_GetTaxValues1,
				  "description"=>esc_html__('Leave empty for select all.','pointfindercoreelements'),
				  "admin_label" => true
				)
			);

			if($setup3_pointposttype_pt4_check == 1){
				array_push($PFVEXFields_Item_Slider['params'],
					array(
					  "type" => "pfa_select2",
					  "heading" => $setup3_pointposttype_pt4,
					  "param_name" => "itemtype",
					  "value" => $PFVEX_GetTaxValues2,
					  "description"=>esc_html__('Leave empty for select all.','pointfindercoreelements'),
					  "admin_label" => true
					)
				);
			}

			if($setup3_pointposttype_pt5_check == 1){
				array_push($PFVEXFields_Item_Slider['params'],
					array(
					  "type" => "pfa_select2",
					  "heading" => $setup3_pointposttype_pt5,
					  "param_name" => "locationtype",
					  "value" => $PFVEX_GetTaxValues3,
					  "description"=>esc_html__('Leave empty for select all.','pointfindercoreelements'),
					  "admin_label" => true
					)
				);
			}

			if($setup3_pointposttype_pt6_check == 1){
				array_push($PFVEXFields_Item_Slider['params'],
					array(
					  "type" => "pfa_select2",
					  "heading" => $setup3_pointposttype_pt6,
					  "param_name" => "features",
					  "value" => $PFVEX_GetTaxValues4,
					  "description"=>esc_html__('Leave empty for select all.','pointfindercoreelements'),
					  "admin_label" => true
					)
				);
			}

			array_push($PFVEXFields_Item_Slider['params'],

				array(
					"type" => "dropdown",
					"heading" => esc_html__("Order by", "pointfindercoreelements"),
					"param_name" => "orderby",
					"value" => array(esc_html__("Title", "pointfindercoreelements")=>'title',esc_html__("Date", "pointfindercoreelements")=>'date'),
					"description" => esc_html__("Please select an order by filter.", "pointfindercoreelements"),
					"edit_field_class" => 'vc_col-sm-6 vc_column'

				),
				array(
					"type" => "dropdown",
					"heading" => esc_html__("Order", "pointfindercoreelements"),
					"param_name" => "sortby",
					"value" => array(esc_html__("ASC", "pointfindercoreelements")=>'ASC',esc_html__("DESC", "pointfindercoreelements")=>'DESC'),
					"description" => esc_html__("Please select an order filter.", "pointfindercoreelements"),
					"edit_field_class" => 'vc_col-sm-6 vc_column'

				),
				array(
					"type" => "textfield",
					"heading" => esc_html__("Slides count", "pointfindercoreelements"),
					"param_name" => "count",
					"value" => "5",
					"description" => esc_html__('How many slides to show? Enter number or word "All".', "pointfindercoreelements"),
					"edit_field_class" => 'vc_col-sm-4 vc_column'
				),
				array(
					  "type" => "textfield",
					  "heading" => esc_html__("Slider speed", "pointfindercoreelements"),
					  "param_name" => "interval",
					  "value" => "5000",
					  "description" => esc_html__("Duration of animation between slides (in ms)", "pointfindercoreelements"),
					  "edit_field_class" => 'vc_col-sm-4 vc_column'
				 ),

				array(
				  "type" => "dropdown",
				  "heading" => esc_html__("Slider Effect", "pointfindercoreelements"),
				  "param_name" => "mode",
				  "value" => array(esc_html__("Fade", "pointfindercoreelements") => 'fade', esc_html__("Fade Up", "pointfindercoreelements") => 'fadeUp',esc_html__("Back Slide", "pointfindercoreelements") => 'backSlide', esc_html__("Go Down", "pointfindercoreelements") => 'goDown'),
				  "description" => esc_html__("If slider enabled (1 Column) You can select a transition effect for it.", "pointfindercoreelements"),
				  "edit_field_class" => 'vc_col-sm-4 vc_column'
				),
				array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("Slider autoplay", "pointfindercoreelements"),
					  "param_name" => "autoplay",
					  "description" => esc_html__("Enables autoplay mode.", "pointfindercoreelements"),
					  "value" => array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
					  "edit_field_class" => 'vc_col-sm-4 vc_column'
					),
				array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("Only show featured items", "pointfindercoreelements"),
					  "param_name" => "featureditems",
					  "description" => esc_html__("Enables featured items and hide another items on query.", "pointfindercoreelements"),
					  "value" => array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
					  "edit_field_class" => 'vc_col-sm-4 vc_column'
					),
				array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("Hide Description Box", "pointfindercoreelements"),
					  "param_name" => "descbox",
					  "description" => esc_html__("If want to hide description box please check.", "pointfindercoreelements"),
					  "value" => array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
					  "edit_field_class" => 'vc_col-sm-4 vc_column'
					)

			);
			vc_map($PFVEXFields_Item_Slider);
		/**
		*End : Item Slider ----------------------------------------------------------------------------------------------------
		**/

    }


    public function pointfinder_single_pf_itemslider_module_html( $atts ) {
	  extract( shortcode_atts( array(
	    'listingtype' => '',
		'itemtype' => '',
		'locationtype' => '',
		'sortby' => 'ASC',
		'orderby' => 'title',
		'count' => 12,
		'posts_in' => '',
		'features'=> '',
		'mode' => 'fadeUp',
		'interval' => 5000,
		'featureditems' =>'',
		'autoplay' =>'',
		'descbox'  => ''
	  ), $atts ) );


		$setup3_pointposttype_pt4_check = $this->PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
		$setup3_pointposttype_pt5_check = $this->PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
		$setup3_pointposttype_pt6_check = $this->PFSAIssetControl('setup3_pointposttype_pt6_check','','1');

		$gridrandno_orj = PF_generate_random_string_ig();
		$gridrandno = 'pf_'.$gridrandno_orj;

		$listingtype_x = $this->PFEX_extract_type_ig($listingtype);
		$itemtype_x = ($setup3_pointposttype_pt4_check == 1) ? $this->PFEX_extract_type_ig($itemtype) : '' ;
		$locationtype_x = ($setup3_pointposttype_pt5_check == 1) ? $this->PFEX_extract_type_ig($locationtype) : '' ;
		$features_x = ($setup3_pointposttype_pt6_check == 1) ? $this->PFEX_extract_type_ig($features) : '' ;


			/* Get admin values */
			$setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');


			//Container & show check
			$pfcontainerdiv = 'pflistgridview'.$gridrandno_orj.'';
			$pfcontainershow = 'pflistgridviewshow'.$gridrandno_orj.'';


			//Defaults
			$pfgetdata = array();
			$pfgetdata['sortby'] = $sortby;
			$pfgetdata['orderby'] = $orderby;
			$pfgetdata['count'] = $count;
			$pfgetdata['posts_in'] = $posts_in;
			$pfgetdata['mode'] = $mode;
			$pfgetdata['interval'] = $interval;
			$pfgetdata['listingtype'] = $listingtype_x;
			$pfgetdata['itemtype'] = $itemtype_x;
			$pfgetdata['locationtype'] = $locationtype_x;
			$pfgetdata['features'] = $features_x;
			$pfgetdata['featureditems'] = $featureditems;

			if($pfgetdata['count'] == 'All' || $pfgetdata['count'] == 'all' || $pfgetdata['count'] == 'ALL'){$pfgetdata['count'] = -1;}

			$args = array( 'post_type' => $setup3_pointposttype_pt1, 'post_status' => 'publish');
			if($pfgetdata['posts_in']!=''){
				$args['post__in'] = pfstring2BasicArray($pfgetdata['posts_in']);

			}

			$setup3_pointposttype_pt4_check = $this->PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
			$setup3_pointposttype_pt5_check = $this->PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
			$setup3_pointposttype_pt6_check = $this->PFSAIssetControl('setup3_pointposttype_pt6_check','','1');



			if(is_array($pfgetdata)){

				$args['tax_query'] = array();

				// listing type
				if($pfgetdata['listingtype'] != ''){
					$pfvalue_arr_lt = PFGetArrayValues_ld($pfgetdata['listingtype']);

					$fieldtaxname_lt = 'pointfinderltypes';

					if(count($args['tax_query']) > 0){
						$args['tax_query'][(count($args['tax_query'])-1)]=
						array(
								'taxonomy' => $fieldtaxname_lt,
								'field' => 'id',
								'terms' => $pfvalue_arr_lt,
								'operator' => 'IN'
						);
					}else{
						$args['tax_query']=
						array(
							'relation' => 'AND',
							array(
								'taxonomy' => $fieldtaxname_lt,
								'field' => 'id',
								'terms' => $pfvalue_arr_lt,
								'operator' => 'IN'
							)
						);
					}
				}

				if($setup3_pointposttype_pt4_check == 1){
					// location type
					if($pfgetdata['locationtype'] != ''){
						$pfvalue_arr_loc = PFGetArrayValues_ld($pfgetdata['locationtype']);

						$fieldtaxname_loc = 'pointfinderlocations';

						if(count($args['tax_query']) > 0){
							$args['tax_query'][(count($args['tax_query'])-1)]=
							array(
									'taxonomy' => $fieldtaxname_loc,
									'field' => 'id',
									'terms' => $pfvalue_arr_loc,
									'operator' => 'IN'
							);
						}else{
							$args['tax_query']=
							array(
								'relation' => 'AND',
								array(
									'taxonomy' => $fieldtaxname_loc,
									'field' => 'id',
									'terms' => $pfvalue_arr_loc,
									'operator' => 'IN'
								)
							);
						}
					}
				}

				if($setup3_pointposttype_pt5_check == 1){
					// item type
					if($pfgetdata['itemtype'] != ''){
					$pfvalue_arr_it = PFGetArrayValues_ld($pfgetdata['itemtype']);

					$fieldtaxname_it = 'pointfinderitypes';

					if(count($args['tax_query']) > 0){
						$args['tax_query'][(count($args['tax_query'])-1)]=
						array(
								'taxonomy' => $fieldtaxname_it,
								'field' => 'id',
								'terms' => $pfvalue_arr_it,
								'operator' => 'IN'
						);
					}else{
						$args['tax_query']=
						array(
							'relation' => 'AND',
							array(
								'taxonomy' => $fieldtaxname_it,
								'field' => 'id',
								'terms' => $pfvalue_arr_it,
								'operator' => 'IN'
							)
						);
					}
					}
				}

				if($setup3_pointposttype_pt6_check == 1){
					// features type
					if($pfgetdata['features'] != ''){
					$pfvalue_arr_fe = PFGetArrayValues_ld($pfgetdata['features']);

					$fieldtaxname_fe = 'pointfinderfeatures';

					if(count($args['tax_query']) > 0){
						$args['tax_query'][(count($args['tax_query'])-1)]=
						array(
								'taxonomy' => $fieldtaxname_fe,
								'field' => 'id',
								'terms' => $pfvalue_arr_fe,
								'operator' => 'IN'
						);
					}else{
						$args['tax_query']=
						array(
							'relation' => 'AND',
							array(
								'taxonomy' => $fieldtaxname_fe,
								'field' => 'id',
								'terms' => $pfvalue_arr_fe,
								'operator' => 'IN'
							)
						);
					}
					}
				}

				//Changed values by user
				$args['orderby'] = $pfgetdata['orderby'];
				$args['order'] = $pfgetdata['sortby'];
				$args['posts_per_page'] = $pfgetdata['count'];

				//Featured items filter
				if($pfgetdata['featureditems'] == 'yes'){

					$args['meta_query'] = array();

					if(count($args['meta_query']) > 0){
						$args['meta_query'][(count($args['meta_query'])-1)] = array(
							'key' => 'webbupointfinder_item_featuredmarker',
							'value' => 1,
							'compare' => '=',
							'type' => 'NUMERIC'
							);

					}else{
							$args['meta_query'] = array(
								'relation' => 'AND',
								array(
								'key' => 'webbupointfinder_item_featuredmarker',
								'value' => 1,
								'compare' => '=',
								'type' => 'NUMERIC'
							)
						);

					}
				}

			}



			//Create html codes
			$wpflistdata = "<div class='pfitemslider".$gridrandno_orj."is-container'>";
			$wpflistdata_output = '<ul id="'.$gridrandno_orj.'" class="pf-item-slider">';


			$setup22_searchresults_hide_lt  = $this->PFSAIssetControl('setup22_searchresults_hide_lt','','0');

			$loop = new WP_Query( $args );
				
				if($loop->post_count > 0){

					while ( $loop->have_posts() ) : $loop->the_post();

					$post_id = get_the_id();


							$ItemDetailArr = array();
							if (class_exists('SitePress')) {
								$pflang = $this->PF_current_language();
								if ($pflang) {
									$pfitemid = $this->PFLangCategoryID_ld($post_id,$pflang,$setup3_pointposttype_pt1);
								}else{
									$pfitemid = $post_id;
								}
							}else{
								$pfitemid = $post_id;
							}
							


							$featured_image = '';
							$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $pfitemid ), 'full' );
							$ItemDetailArr['featured_image_org'] = $featured_image[0];
							$ItemDetailArr['featured_image'] = get_post_meta($pfitemid,'webbupointfinder_item_sliderimage',true);




							if(is_array($ItemDetailArr['featured_image'])){
								if(count($ItemDetailArr['featured_image'])>0 && !empty($ItemDetailArr['featured_image']['url'])){
									$ItemDetailArr['featured_image'] = $ItemDetailArr['featured_image']['url'];
								}else{
									$ItemDetailArr['featured_image'] = $ItemDetailArr['featured_image_org'];
								}
							}else{
								$ItemDetailArr['featured_image'] = $ItemDetailArr['featured_image_org'];
							}
							//Title
							$ItemDetailArr['if_title'] = get_the_title($pfitemid);
							//Exceprty
							$ItemDetailArr['if_excerpt'] = get_the_excerpt();
							//Permalink
							$ItemDetailArr['if_link'] = get_permalink($pfitemid);
							//Address
							$ItemDetailArr['if_address'] = esc_html(get_post_meta( $pfitemid, 'webbupointfinder_items_address', true ));

							$output_data = $this->PFIF_DetailText_ld($pfitemid,$setup22_searchresults_hide_lt);
							if (is_array($output_data)) {
								if (!empty($output_data['ltypes'])) {
									$output_data_ltypes = $output_data['ltypes'];
								} else {
									$output_data_ltypes = '';
								}
								if (!empty($output_data['content'])) {
									$output_data_content = $output_data['content'];
								} else {
									$output_data_content = '';
								}
								if (!empty($output_data['priceval'])) {
									$output_data_priceval = $output_data['priceval'];
								} else {
									$output_data_priceval = '';
								}
							} else {
								$output_data_priceval = '';
								$output_data_content = '';
								$output_data_ltypes = '';
							}


							$wpflistdata_output .= '<li class="pf-item-slider-items">
							<img src="'.$ItemDetailArr['featured_image'].'" alt="" data-no-lazy="1">';
							if($descbox != 'yes'){
							$wpflistdata_output .='
							<div class="pf-item-slider-description-container">
							<div class="pf-item-slider-description">
								<div class="pf-item-slider-title"><a href="'.$ItemDetailArr['if_link'].'">'.$ItemDetailArr['if_title'].'</a></div>
								<div class="pf-item-slider-address"><a href="'.$ItemDetailArr['if_link'].'">'.$ItemDetailArr['if_address'].'</a></div>
								<div class="pf-item-slider-excerpt"><p>'.wp_trim_words( $ItemDetailArr['if_excerpt'], 23, ' ...' ).'</p></div>
							</div>
							<div class="pf-item-slider-ex-container">';
							if(!empty($output_data_priceval)){
								$wpflistdata_output .='<div class="pf-item-slider-price clearfix">'.$output_data_priceval.'</div>';
							}
							$wpflistdata_output .='<div class="pf-item-slider-golink clearfix"><a href="'.get_the_permalink().'">'.esc_html__('Details','pointfindercoreelements').'</a></div>';


							$wpflistdata_output .='
							</div>';
							}
							$wpflistdata_output .='
							</li>';


					endwhile;
					wp_reset_postdata();
				}
				$wpflistdata_output .= '</ul>';

	            $wpflistdata .= $wpflistdata_output;
				$wpflistdata .= "</div> ";

				$content_script =
				'(function($) {
				"use strict"
					$(function() {';
						if($descbox !== 'yes'){
						$content_script .='
						$(window).on("load resize orientationchange", function(){
							if($("#'.$gridrandno_orj.'").width() < 640){

								$("#'.$gridrandno_orj.'").addClass("pfmobile");
							}else{

								$("#'.$gridrandno_orj.'").removeClass("pfmobile");
							}
						});
						';

						}
							$content_script .='
							$("#'.$gridrandno_orj.'").owlCarousel({
							items : 1,
							navigation : true,
							paginationNumbers : false,
							pagination : false,';
							if($autoplay == 'yes'){ $content_script .=  'autoPlay : true,stopOnHover : true,';}else{$content_script .=  'autoPlay : false,';}
							$content_script .= '
							slideSpeed:'.$interval.',
							mouseDrag:false,
							touchDrag:true,
							itemSpaceWidth: 0,
							autoHeight : false,
							responsive:true,
							transitionStyle: "'.$mode.'",
							itemsScaleUp : false,
							navigationText:false,
							theme:"owl-theme",
							singleItem : true,
						});
				});

				})(jQuery);';
				wp_add_inline_script('pftheme-customjs',$content_script,'after');
		return $wpflistdata;
	}

}
new PointFinderListingSliderShortcode();

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_pointfinder_single_pf_itemslider extends WPBakeryShortCode {
    }
}