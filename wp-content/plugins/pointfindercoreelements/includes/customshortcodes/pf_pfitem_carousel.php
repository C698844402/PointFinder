<?php
/*
*
* Visual Composer PointFinder Static Grid Shortcode
*
*/

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

class PointFinderListingCarouselShortcode extends WPBakeryShortCode {
	use PointFinderOptionFunctions,
	  PointFinderCommonFunctions,
	  PointFinderWPMLFunctions,
	  PointFinderReviewFunctions,
	  PointFinderCommonVCFunctions,
	  PointFinderGridSpecificFunctions;

    function __construct() {
        add_action( 'vc_after_init', array( $this, 'pointfinder_single_pf_pfitemcarousel_module_mapping' ) );
        add_shortcode( 'pf_pfitemcarousel', array( $this, 'pointfinder_single_pf_pfitemcarousel_module_html' ) );
    }

    

    public function pointfinder_single_pf_pfitemcarousel_module_mapping() {

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
		*Start : Item Carousel Static ----------------------------------------------------------------------------------------------------
		**/
			$PFVEXFields_ItemCarousel = array();
			$PFVEXFields_ItemCarousel['name'] = esc_html__("PF Listing Carousel", 'pointfindercoreelements');
			$PFVEXFields_ItemCarousel['base'] = "pf_pfitemcarousel";
			$PFVEXFields_ItemCarousel['controls'] = "full";
			$PFVEXFields_ItemCarousel['icon'] = "pfaicon-th";
			$PFVEXFields_ItemCarousel['category'] = "Point Finder";
			$PFVEXFields_ItemCarousel['description'] = esc_html__("Point Finder item carousel", 'pointfindercoreelements');
			$PFVEXFields_ItemCarousel['params'] = "";
			$PFVEXFields_ItemCarousel['params'] = array(
				array(
				  "type" => "pfa_select2",
				  "heading" => $setup3_pointposttype_pt7,
				  "param_name" => "listingtype",
				  "value" => $PFVEX_GetTaxValues1,
				  "description"=>esc_html__('Leave empty for select all.','pointfindercoreelements'),
				  "admin_label" => true
				),

			  );


			if($setup3_pointposttype_pt4_check == 1){
				array_push($PFVEXFields_ItemCarousel['params'],
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
				array_push($PFVEXFields_ItemCarousel['params'],
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
				array_push($PFVEXFields_ItemCarousel['params'],
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

			if($setup3_pt14_check == 1){
				array_push($PFVEXFields_ItemCarousel['params'],
					array(
					  "type" => "pfa_select2",
					  "heading" => $setup3_pt14,
					  "param_name" => "conditions",
					  "value" => $PFVEX_GetTaxValues5,
					  "description"=>esc_html__('Leave empty for select all.','pointfindercoreelements'),
					  "admin_label" => true
					)
				);
			}

			array_push($PFVEXFields_ItemCarousel['params'],
				//If possible add custom fields filter on here.
				array(
					"type" => "dropdown",
					"heading" => esc_html__("Order by", "pointfindercoreelements"),
					"param_name" => "orderby",
					"value" => array(esc_html__("Title", "pointfindercoreelements")=>'title',esc_html__("Date", "pointfindercoreelements")=>'date'),
					"description" => esc_html__("Please select an order by filter.", "pointfindercoreelements"),
					"edit_field_class" => 'vc_col-sm-6'
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
			        "heading" => esc_html__("Item IDs", "pointfindercoreelements"),
			        "param_name" => "posts_in",
			        "description" => esc_html__('Fill this field with items ID numbers separated by commas (,), to retrieve only them. Ex: 171,172,173 (Optional) This option will show only selected items.', "pointfindercoreelements")
			     ),

				array(
					  "type" => "dropdown",
					  "heading" => esc_html__("Default Listing Columns", "pointfindercoreelements"),
					  "param_name" => "cols",
					  "value" => array('4 Columns'=>'4','2 Columns'=>'2','3 Columns'=>'3'),
					  "description" => esc_html__("Please choose default column number for this grid.", "pointfindercoreelements"),
				),
				array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("Only show featured items", "pointfindercoreelements"),
					  "param_name" => "featureditems",
					  "description" => esc_html__("Enables featured items and hide another items on query.", "pointfindercoreelements"),
					  "value" => array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
					  "edit_field_class" => 'vc_col-sm-6 vc_column'
					),
				array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("HIDE featured items", "pointfindercoreelements"),
					  "param_name" => "featureditemshide",
					  "description" => esc_html__("Disable featured items and show another items on query. Do not use with Only Show Featured Items", "pointfindercoreelements"),
					  "value" => array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
					  "edit_field_class" => 'vc_col-sm-6 vc_column'
					),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Item Limit', 'pointfindercoreelements' ),
					'param_name' => 'itemlimit',
					'value' => '20',
					'description' => esc_html__( 'You can limit items.', 'pointfindercoreelements' ),
					"edit_field_class" => 'vc_col-sm-6 vc_column'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Slider speed', 'pointfindercoreelements' ),
					'param_name' => 'speed',
					'value' => '500',
					'description' => esc_html__( 'Duration of animation between slides (in ms)', 'pointfindercoreelements' ),
					"edit_field_class" => 'vc_col-sm-6 vc_column'
				),
				array(
					  "type" => "colorpicker",
					  "heading" => esc_html__("Item Box Area Background", 'pointfindercoreelements'),
					  "param_name" => "itemboxbg",
					  "value" => $setup22_searchresults_background2,
					  "description" => esc_html__("Item box area background color of the grid listing area. Optional", 'pointfindercoreelements')
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Slider autoplay', 'pointfindercoreelements' ),
					'param_name' => 'autoplay',
					'description' => esc_html__( 'Enables autoplay mode.', 'pointfindercoreelements' ),
					'value' => array( esc_html__( 'Yes, please', 'pointfindercoreelements' ) => 'yes' ),
					"edit_field_class" => 'vc_col-sm-6 vc_column'
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'HIDE pagination control', 'pointfindercoreelements' ),
					'param_name' => 'hide_pagination_control',
					'description' => esc_html__( 'If YES pagination control will be removed.', 'pointfindercoreelements' ),
					'value' => array( esc_html__( 'Yes, please', 'pointfindercoreelements' ) => 'yes' ),
					"edit_field_class" => 'vc_col-sm-6 vc_column'
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'HIDE prev/next buttons', 'pointfindercoreelements' ),
					'param_name' => 'hide_prev_next_buttons',
					'description' => esc_html__( 'If "YES" prev/next control will be removed.', 'pointfindercoreelements' ),
					'value' => array( esc_html__( 'Yes, please', 'pointfindercoreelements' ) => 'yes' ),
					"edit_field_class" => 'vc_col-sm-6 vc_column'
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Disable Item Padding', 'pointfindercoreelements' ),
					'param_name' => 'zeropadding',
					'description' => esc_html__( 'This will disable padding between items.', 'pointfindercoreelements' ),
					'value' => array( esc_html__( 'Yes, please', 'pointfindercoreelements' ) => 'yes' ),
					"edit_field_class" => 'vc_col-sm-6 vc_column'
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Randomize Results', 'pointfindercoreelements' ),
					'param_name' => 'pfrandomize',
					'description' => esc_html__( 'This feature will enable randomize support. If this enabled, sort by option will not work.', 'pointfindercoreelements' ),
					'value' => array( esc_html__( 'Yes, please', 'pointfindercoreelements' ) => 'yes' ),
				)

			);
			vc_map($PFVEXFields_ItemCarousel);
		/**
		*End : Item Carousel Static
		**/

    }


    public function pointfinder_single_pf_pfitemcarousel_module_html( $atts ) {

      $output = $title =  $onclick = $custom_links = $img_size = $custom_links_target = $images = '';
		$autoplay = $autocrop = $customsize = $hide_pagination_control =  $speed = $zeropadding ='';
		extract(shortcode_atts(array(
		    'autoplay' => '',
		    'hide_pagination_control' => '',
		    'hide_prev_next_buttons' => '',
		    'speed' => '500',
		    'zeropadding' => '',
		    'listingtype' => '',
			'itemtype' => '',
			'conditions' => '',
			'locationtype' => '',
			'posts_in' => '',
			'sortby' => 'ASC',
			'orderby' => 'title',
			'cols' => 4,
			'features'=>array(),
			'itemboxbg' => '',
			'featureditems'=>'',
			'featureditemshide' => '',
			'itemlimit' => 20,
			'related' => 0,
			'pfrandomize' => ''
		), $atts));

		$gal_images = '';
		$link_start = '';
		$link_end = '';
		$el_start = '';
		$el_end = '';
		$slides_wrap_start = '';
		$slides_wrap_end = '';
		$pretty_rand = $onclick == 'link_image' ? rand() : '';


		$template_directory_uri = get_template_directory_uri();
		$general_crop2 = $this->PFSizeSIssetControl('general_crop2','',1);

		$general_retinasupport = $this->PFSAIssetControl('general_retinasupport','','0');
		if($general_retinasupport == 1){$pf_retnumber = 2;}else{$pf_retnumber = 1;}

		$myrandno = rand(1, 2147483647);
		$myrandno = md5($myrandno);
		$carousel_id = 'vc-images-carousel-'.$myrandno;

		$setup3_pointposttype_pt4_check = $this->PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
		$setup3_pointposttype_pt5_check = $this->PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
		$setup3_pointposttype_pt6_check = $this->PFSAIssetControl('setup3_pointposttype_pt6_check','','1');
		$setup3_pt14_check = $this->PFSAIssetControl('setup3_pt14_check','',0);

		$gridrandno_orj = PF_generate_random_string_ig();
		$gridrandno = 'pf_'.$gridrandno_orj;

		$listingtype_x = $this->PFEX_extract_type_ig($listingtype);
		$itemtype_x = ($setup3_pointposttype_pt4_check == 1) ? $this->PFEX_extract_type_ig($itemtype) : '' ;
		$conditions_x = ($setup3_pt14_check == 1) ? $this->PFEX_extract_type_ig($conditions) : '' ;
		$locationtype_x = ($setup3_pointposttype_pt5_check == 1) ? $this->PFEX_extract_type_ig($locationtype) : '' ;
		$features_x = ($setup3_pointposttype_pt6_check == 1) ? $this->PFEX_extract_type_ig($features) : '' ;

		$wpflistdata = "<div class='pflistgridview".$gridrandno_orj."-container pflistgridviewgr-container'>";

		/* Get admin values */
		$setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');


		//Container & show check
		$pfcontainerdiv = 'pflistgridview'.$gridrandno_orj.'';
		$pfcontainershow = 'pflistgridviewshow'.$gridrandno_orj.'';


		//Defaults
		$pfgrid = '';
		$pfitemboxbg = '';		

		$pfgetdata = array();
		$pfgetdata['sortby'] = $sortby;
		$pfgetdata['orderby'] = $orderby;
		$pfgetdata['posts_in'] = $posts_in;

		$pfgetdata['cols'] = $cols;
		$pfgetdata['itemboxbg'] = $itemboxbg;
		$pfgetdata['listingtype'] = $listingtype_x;
		$pfgetdata['itemtype'] = $itemtype_x;
		$pfgetdata['conditions'] = $conditions_x;
		$pfgetdata['locationtype'] = $locationtype_x;
		$pfgetdata['features'] = $features_x;	
		$pfgetdata['featureditems'] = $featureditems;
		$pfgetdata['featureditemshide'] = $featureditemshide;

		if($pfgetdata['cols'] != ''){$pfgrid = 'grid'.$pfgetdata['cols'];}


		$args = array( 'post_type' => $setup3_pointposttype_pt1, 'post_status' => 'publish');
		$args['posts_per_page'] = $itemlimit;

		if($pfgetdata['posts_in']!=''){
			$args['post__in'] = pfstring2BasicArray($pfgetdata['posts_in']);
		}

		if(isset($args['meta_query']) == false || isset($args['meta_query']) == NULL){
			$args['meta_query'] = array();
		}	

		if(isset($args['tax_query']) == false || isset($args['tax_query']) == NULL){
			$args['tax_query'] = array();
		}


		if ($related == 1) {
			$the_current_post_id = get_the_id();
			if(!empty($the_current_post_id)){$args['post__not_in'] = array($the_current_post_id);}
			$agent_id = redux_post_meta("pointfinderthemefmb_options", $the_current_post_id, "webbupointfinder_item_agents");

			$re_li_4 = $this->PFSAIssetControl('re_li_4','','0');
			
			//Agent Filter for Related Listings
			if(!empty($agent_id) && $re_li_4 == 1){
				$args['meta_query'][] = array(
					'key' => 'webbupointfinder_item_agents',
					'value' => $agent_id,
					'compare' => '=',
					'type' => 'NUMERIC'
				);
			}
		}


		$review_system_statuscheck = $this->PFREVSIssetControl('setup11_reviewsystem_check','','0');

		if(is_array($pfgetdata)){

			// listing type
			if($pfgetdata['listingtype'] != ''){
				$pfvalue_arr_lt = PFGetArrayValues_ld($pfgetdata['listingtype']);
				$fieldtaxname_lt = 'pointfinderltypes';
				$args['tax_query'][]=array(
					'taxonomy' => $fieldtaxname_lt,
					'field' => 'id',
					'terms' => $pfvalue_arr_lt,
					'operator' => 'IN'
				);
			}


			if($setup3_pointposttype_pt5_check == 1){
				// location type
				if(!empty($pfgetdata['locationtype'])){
					$pfvalue_arr_loc = PFGetArrayValues_ld($pfgetdata['locationtype']);
					$fieldtaxname_loc = 'pointfinderlocations';
					$args['tax_query'][]=array(
						'taxonomy' => $fieldtaxname_loc,
						'field' => 'id',
						'terms' => $pfvalue_arr_loc,
						'operator' => 'IN'
					);
				}
			}

			if($setup3_pointposttype_pt4_check == 1){
				// item type
				if($pfgetdata['itemtype'] != ''){
					$pfvalue_arr_it = PFGetArrayValues_ld($pfgetdata['itemtype']);
					$fieldtaxname_it = 'pointfinderitypes';
					$args['tax_query'][]=array(
						'taxonomy' => $fieldtaxname_it,
						'field' => 'id',
						'terms' => $pfvalue_arr_it,
						'operator' => 'IN'
					);
				}
			}

			if($setup3_pointposttype_pt6_check == 1){
				// features type
				if($pfgetdata['features'] != ''){
					$pfvalue_arr_fe = PFGetArrayValues_ld($pfgetdata['features']);
					$fieldtaxname_fe = 'pointfinderfeatures';
					$args['tax_query'][]=array(
						'taxonomy' => $fieldtaxname_fe,
						'field' => 'id',
						'terms' => $pfvalue_arr_fe,
						'operator' => 'IN'
					);
				}
			}

			/* Condition */
			if($setup3_pt14_check == 1){
				if($pfgetdata['conditions'] != ''){
					$pfvalue_arr_it = PFGetArrayValues_ld($pfgetdata['conditions']);
					$fieldtaxname_it = 'pointfinderconditions';
					$args['tax_query'][] = array(
						'taxonomy' => $fieldtaxname_it,
						'field' => 'id',
						'terms' => $pfvalue_arr_it,
						'operator' => 'IN'
					);
				}
			}


			if ($zeropadding !== "yes") {
				$itemspacebetween = 17;
				$pfitemboxbg = ' style="background-color:'.$pfgetdata['itemboxbg'].';"';
			}else{
				$itemspacebetween = 0;
				$pfitemboxbg = ' style="background-color:'.$pfgetdata['itemboxbg'].'; margin:0!important"';
			}

			
			$meta_key_featured = 'webbupointfinder_item_featuredmarker';
			

			if($pfgetdata['orderby'] == 'date' || $pfgetdata['orderby'] == 'title'){
				$args['orderby'] = array('meta_value_num' => 'DESC' , $pfgetdata['orderby'] => $pfgetdata['sortby']);
				if ($pfrandomize == 'yes') {
					unset($args['orderby'][$pfgetdata['orderby']]);
					$args['orderby']['rand']='';
				}
				$args['meta_key'] = $meta_key_featured;
			}

			

			//Featured items filter
			if($pfgetdata['featureditems'] == 'yes'){
				if(isset($args['meta_key'])){unset($args['meta_key']);}
				if(isset($args['orderby']['meta_value_num'])){unset($args['orderby']['meta_value_num']);}
				$args['meta_query'][] = array( 
					'key' => 'webbupointfinder_item_featuredmarker',
					'value' => 1,
					'compare' => '=',
					'type' => 'NUMERIC'
				);
			}

			//Featured items filter
			if($pfgetdata['featureditemshide'] == 'yes'){
				if(isset($args['meta_key'])){unset($args['meta_key']);}
				if(isset($args['orderby']['meta_value_num'])){unset($args['orderby']['meta_value_num']);}
				$args['orderby'] = array($pfgetdata['orderby'] => $pfgetdata['sortby']);
				if ($pfrandomize == 'yes') {
					if(isset($args['orderby'][$pfgetdata['orderby']])){unset($args['orderby'][$pfgetdata['orderby']]);}
					$args['orderby']['rand']='';
				}
				$args['meta_query'][] = array(
					'key' => 'webbupointfinder_item_featuredmarker',
					'value' => 0,
					'compare' => '=',
					'type' => 'NUMERIC'
				);
			}	
		}



		$general_retinasupport = $this->PFSAIssetControl('general_retinasupport','','0');	

		$setupsizelimitconf_general_gridsize1_width = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize1','width',440);
		$setupsizelimitconf_general_gridsize1_height = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize1','height',330);


		switch($pfgrid){

			case 'grid1':
				$pf_grid_size = 1;
				$pfgrid_output = 'pf1col';
				$pfgridcol_output = '';
				break;
			case 'grid2':
				$pf_grid_size = 2;
				$pfgrid_output = 'pf2col';
				$pfgridcol_output = '';
				break;
			case 'grid3':
				$pf_grid_size = 3;
				$pfgrid_output = 'pf3col';
				$pfgridcol_output = '';
				break;
			case 'grid4':
				$pf_grid_size = 4;
				$pfgrid_output = 'pf4col';
				$pfgridcol_output = '';
				break;
			default:
				$pf_grid_size = 4;
				$pfgrid_output = 'pf4col';
				$pfgridcol_output = '';
				break;
		}


		$loop = new WP_Query( $args );
		$foundedposts = $loop->found_posts;
		/*
		print_r($loop->query).PHP_EOL;
		echo $loop->request.PHP_EOL;
		echo $loop->post_count ;
		*/
		$post_ids = wp_list_pluck($loop->posts,'ID');
		if ($setup3_pt14_check == 1) {
			$post_contidions = wp_get_object_terms($post_ids, 'pointfinderconditions', array("fields" => "all_with_object_id"));
		}
		$post_listingtypes = wp_get_object_terms($post_ids, 'pointfinderltypes', array("fields" => "all_with_object_id"));

		//Create html codes
		$pflang = $this->PF_current_language();

		$wpflistdata .= '
		    <div class="pfsearchresults '.$pfcontainershow.' pflistgridview pflistgridview-static">';

		        $wpflistdata .=
		        '<div class="'.$pfcontainerdiv.'-content pflistcommonview-content" style="padding:0"  id="'.$carousel_id.'">';//List Content begin
		        
		        
		            $wpflistdata .='
		                <div class="pfitemlists-content-elements '.$pfgrid_output.'" id="'.$myrandno.'">';


					$wpflistdata_output = '';
					
					/* Variables */

						$setup22_searchresults_animation_image  = $this->PFSAIssetControl('setup22_searchresults_animation_image','','WhiteSquare');
						$setup22_searchresults_hover_image  = $this->PFSAIssetControl('setup22_searchresults_hover_image','','0');
						$setup22_searchresults_hover_video  = $this->PFSAIssetControl('setup22_searchresults_hover_video','','0');
						$setup22_searchresults_hide_address  = $this->PFSAIssetControl('setup22_searchresults_hide_address','','0');
						
						$pfbuttonstyletext = 'pfHoverButtonStyle ';
						
						switch($setup22_searchresults_animation_image){
							case 'WhiteRounded':
								$pfbuttonstyletext .= 'pfHoverButtonWhite pfHoverButtonRounded';
								break;
							case 'BlackRounded':
								$pfbuttonstyletext .= 'pfHoverButtonBlack pfHoverButtonRounded';
								break;
							case 'WhiteSquare':
								$pfbuttonstyletext .= 'pfHoverButtonWhite pfHoverButtonSquare';
								break;
							case 'BlackSquare':
								$pfbuttonstyletext .= 'pfHoverButtonBlack pfHoverButtonSquare';
								break;
							
						} 
						$st22srloc = $this->PFSAIssetControl('st22srloc','',0);
						$stp22_qwlink = $this->PFSAIssetControl('stp22_qwlink','','1');
						$st8_npsys = $this->PFASSIssetControl('st8_npsys','',0);
						$showmapfeature = $this->PFSAIssetControl('setup22_searchresults_showmapfeature','','1');
						$user_loggedin_check = is_user_logged_in();
						$pointfinderlocationsex_vars = get_option('pointfinderlocationsex_vars');
						if ($st8_npsys == 1) {
							$listing_pstyle_meta = get_option('pointfinderltypes_style_vars');
						}else{
							$listing_pstyle_meta = '';
						}
						
						$pfboptx1 = $this->PFSAIssetControl('setup22_searchresults_hide_excerpt','1','0');
						$pfboptx2 = $this->PFSAIssetControl('setup22_searchresults_hide_excerpt','2','0');
						$pfboptx3 = $this->PFSAIssetControl('setup22_searchresults_hide_excerpt','3','0');
						$pfboptx4 = $this->PFSAIssetControl('setup22_searchresults_hide_excerpt','4','0');
						
						if($pfboptx1 != 1){$pfboptx1_text = 'style="display:none"';}else{$pfboptx1_text = '';}
						if($pfboptx2 != 1){$pfboptx2_text = 'style="display:none"';}else{$pfboptx2_text = '';}
						if($pfboptx3 != 1){$pfboptx3_text = 'style="display:none"';}else{$pfboptx3_text = '';}
						if($pfboptx4 != 1){$pfboptx4_text = 'style="display:none"';}else{$pfboptx4_text = '';}
						
						switch($pfgrid_output){case 'pf1col':$pfboptx_text = $pfboptx1_text;break;case 'pf2col':$pfboptx_text = $pfboptx2_text;break;case 'pf3col':$pfboptx_text = $pfboptx3_text;break;case 'pf4col':$pfboptx_text = $pfboptx4_text;break;}		
						
						if (is_user_logged_in()) {
							$user_favorites_arr = get_user_meta( get_current_user_id(), 'user_favorites', true );
							if (!empty($user_favorites_arr)) {
								$user_favorites_arr = json_decode($user_favorites_arr,true);
							}else{
								$user_favorites_arr = array();
							}
						}						
						
						$setup16_featureditemribbon_hide = $this->PFSAIssetControl('setup16_featureditemribbon_hide','','1');
						$setup4_membersettings_favorites = $this->PFSAIssetControl('setup4_membersettings_favorites','','1');
						$setup22_searchresults_hide_re = $this->PFREVSIssetControl('setup22_searchresults_hide_re','','1');
						
						$setup16_reviewstars_nrtext = $this->PFREVSIssetControl('setup16_reviewstars_nrtext','','0');
						$setup22_searchresults_hide_lt  = $this->PFSAIssetControl('setup22_searchresults_hide_lt','','0');

						$st22srlinknw = $this->PFSAIssetControl('st22srlinknw','','0');
						$targetforitem = '';
						if ($st22srlinknw == 1) {
							$targetforitem = ' target="_blank"';
						}

					if($loop->post_count > 0){
				
						while ( $loop->have_posts() ) : $loop->the_post();
						
						$post_id = get_the_id();

						
						$ItemDetailArr = array();
						
						if ($pflang) {
							$pfitemid = $this->PFLangCategoryID_ld($post_id,$pflang,$setup3_pointposttype_pt1);
						}else{
							$pfitemid = $post_id;
						}

						
						$featured_image_stored = $this->pointfinder_featured_image_getresized($pfitemid,$template_directory_uri,$general_crop2,$general_retinasupport,$setupsizelimitconf_general_gridsize1_width,$setupsizelimitconf_general_gridsize1_height);

						$ItemDetailArr['featured_image_org'] = $featured_image_stored['featured_image_org'];
						$ItemDetailArr['featured_image'] = $featured_image_stored['featured_image'];
						$ItemDetailArr['if_title'] = get_the_title($pfitemid);
						$ItemDetailArr['if_excerpt'] = get_the_excerpt();
						$ItemDetailArr['if_link'] = get_permalink($pfitemid);;
						$ItemDetailArr['if_address'] = esc_html(get_post_meta( $pfitemid, 'webbupointfinder_items_address', true ));
						$ItemDetailArr['featured_video'] =  esc_url(get_post_meta( $pfitemid, 'webbupointfinder_item_video', true ));

						$data_values = $pfstviewcor = '';

	                      
                         $data_values .= ' data-pid="'.$pfitemid.'"';
                         $pfstviewcor = get_post_meta($pfitemid, 'webbupointfinder_items_location', true);

                         if (!empty($pfstviewcor)) {
                           $pfstviewcor = explode(',', $pfstviewcor);

                           if (count($pfstviewcor) >= 2) {
                            if (!empty($pfstviewcor[0]) && !empty($pfstviewcor[1])) {
                              $ItemDetailArr['lat'] = $pfstviewcor[0];
                              $data_values .= ' data-lat="'.$pfstviewcor[0].'"';
                              $ItemDetailArr['lng'] = $pfstviewcor[1];
                              $data_values .= ' data-lng="'.$pfstviewcor[1].'"';
                            }else{
                              $pfstviewcor = '';
                            }
                            
                           }
                         }
                         
                         $pfitemicon = $this->pf_get_markerimage($pfitemid,1,1,$st8_npsys);
                         
                         if($this->PFControlEmptyArr($pfitemicon)){
                  
                            if ($pfitemicon['is_cat'] == 1) {
                              $listing_icon = $this->pf_get_default_cat_images($pflang,$pfitemicon['cat'],$st8_npsys);

                              $data_values .= ' data-icon="'.$listing_icon.'"';
                            }

                            if ($pfitemicon['is_image'] == 1) {
                              $data_values .= ' data-icon="'.$pfitemicon['content'].'"';
                            }
                            
                          }

                         $data_values .= ' data-title="'.$ItemDetailArr['if_title'].'"';
	                      


						$post_listing_typeval = '';
						foreach ($post_listingtypes as $post_listingtype) {
							if ($pfitemid == $post_listingtype->object_id) {
								$post_listing_typeval = $post_listingtype->term_id;
							}
						}

						$output_data = $this->PFIF_DetailText_ld($pfitemid,$setup22_searchresults_hide_lt,$post_listing_typeval,$listing_pstyle_meta,'topmap');
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
						
						$fav_check = 'false';
						/*li*/$wpflistdata_output .= '
							<div class="'.$pfgridcol_output.' wpfitemlistdata">
								<div class="pflist-item"'.$pfitemboxbg.$data_values.'>
								<div class="pflist-item-inner">
									<div class="pflist-imagecontainer pflist-subitem">
									';
									
									if($setup22_searchresults_hover_image == 1){
										$wpflistdata_output .= "<a href='".$ItemDetailArr['if_link']."'".$targetforitem.">";
										if ($general_crop2 == 3) {
											$wpflistdata_output .= "<div class='pfuorgcontainer'><img src='".$ItemDetailArr['featured_image'] ."' alt='' /></div>";
										}else{
											$wpflistdata_output .= "<img src='".$ItemDetailArr['featured_image'] ."' alt='' />";
										}
										$wpflistdata_output .= "</a>";
										
									}else{
										if ($general_crop2 == 3) {
											$wpflistdata_output .= "<div class='pfuorgcontainer'><img src='".$ItemDetailArr['featured_image'] ."' alt='' /></div>";
										}else{
											$wpflistdata_output .= "<img src='".$ItemDetailArr['featured_image'] ."' alt='' />";
										}
										$wpflistdata_output .= "</a>";
										

										$wpflistdata_output .= '
										<div class="pfImageOverlayH hidden-xs"></div>
										';
										if($setup22_searchresults_hover_video != 1 && !empty($ItemDetailArr['featured_video'])){	
										$wpflistdata_output .= '
										<div class="pfButtons pfStyleV pfStyleVAni hidden-xs">';
										}else{
										$wpflistdata_output .= '
										<div class="pfButtons pfStyleV2 pfStyleVAni hidden-xs">';
										}
											$wpflistdata_output .= '
											<span class="'.$pfbuttonstyletext.' clearfix">
												<a class="pficon-imageclick" data-pf-link="'.$ItemDetailArr['featured_image_org'].'" data-pf-type="image" style="cursor:pointer">
													<i class="pfadmicon-glyph-684"></i>
												</a>
											</span>';
											if($setup22_searchresults_hover_video != 1 && !empty($ItemDetailArr['featured_video'])){	
											$wpflistdata_output .= '
											<span class="'.$pfbuttonstyletext.'">
												<a class="pficon-videoclick" data-pf-link="'.$ItemDetailArr['featured_video'].'" data-pf-type="iframe" style="cursor:pointer">
													<i class="pfadmicon-glyph-573"></i>
												</a>
											</span>';
											}
											$wpflistdata_output .= '
											<span class="'.$pfbuttonstyletext.'">
												<a href="'.$ItemDetailArr['if_link'].'">
													<i class="pfadmicon-glyph-794"></i>
												</a>
											</span>
										</div>';
									}

									if ($setup16_featureditemribbon_hide != 0) {
										$featured_check_x = get_post_meta( $pfitemid, 'webbupointfinder_item_featuredmarker', true );

		                        		if (!empty($featured_check_x)) {
		                        			$wpflistdata_output .= '<div class="pfribbon-wrapper-featured"><div class="pfribbon-featured">'.esc_html__('FEATURED','pointfindercoreelements').'</div></div>';
		                        		}
			                        }

			                        
			                        /* Start: Conditions */

				                        if ($setup3_pt14_check == 1 && !empty($post_contidions)) {
		                        			
		                        			foreach ($post_contidions as $post_condition) {
		                        				if ($post_condition->object_id == $pfitemid) {
		                        					$condition_term_id = $post_condition->term_id;
		                        					$condition_name = $post_condition->name;
		                        				
											
													if (isset($post_condition->term_id)) {																
				                        				$contidion_colors = $this->pf_get_condition_color($post_condition->term_id);

				                        				$condition_c = (isset($contidion_colors['cl']))? $contidion_colors['cl']:'#494949';
				                        				$condition_b = (isset($contidion_colors['bg']))? $contidion_colors['bg']:'#f7f7f7';

				                        				$wpflistdata_output .= '<div class="pfconditions-tag" style="color:'.$condition_c.';background-color:'.$condition_b.'">';
					                        			$wpflistdata_output .= '<a href="' . esc_url( get_term_link( $post_condition->term_id, 'pointfinderconditions' ) ) . '" style="color:'.$condition_c.';">'.$post_condition->name.'</a>';
					                        			$wpflistdata_output .= '</div>';
				                        			}
				                        		}
				                        	}
											

				                        }
					                /* End: Conditions */

									
									if ($output_data_priceval != '' || $output_data_ltypes != '') {
										$wpflistdata_output .= '<div class="pflisting-itemband">';
									
										$wpflistdata_output .= '<div class="pflist-pricecontainer">';
										/* Start: Review Stars */
				                        if ($review_system_statuscheck == 1) {
				                        	if ($setup22_searchresults_hide_re == 0) {

				                        		$reviews = $this->pfcalculate_total_review($pfitemid);

				                        		if (!empty($reviews['totalresult'])) {
				                        			$wpflistdata_output .= '<div class="pflist-reviewstars">';
				                        			$rev_total_res = round($reviews['totalresult']);
				                        			$wpflistdata_output .= '<div class="revpoint">';
				                        			$wpflistdata_output .= (strlen($reviews['totalresult']) > 1)?$reviews['totalresult']:$reviews['totalresult'].'.0';
				                        			$wpflistdata_output .= '</div>';
				                        			$wpflistdata_output .= '<div class="pfrevstars-wrapper-review">';
				                        			$wpflistdata_output .= ' <div class="pfrevstars-review">';
				                        				for ($ri=0; $ri < $rev_total_res; $ri++) { 
				                        					$wpflistdata_output .= '<i class="fas fa-star"></i> ';
				                        				}
				                        				for ($ki=0; $ki < (5-$rev_total_res); $ki++) { 
				                        					$wpflistdata_output .= '<i class="far fa-star"></i> ';
				                        				}

				                        			$wpflistdata_output .= '</div></div>';
				                        			$wpflistdata_output .= '</div>';
				                        		}else{
				                        			if($setup16_reviewstars_nrtext == 0){
				                        				$wpflistdata_output .= '<div class="pflist-reviewstars">';
					                        			$wpflistdata_output .= '<div class="pfrevstars-wrapper-review">';
					                        			$wpflistdata_output .= '<div class="pfrevstars-review pfrevstars-reviewbl"><i class="far fa-star"></i> <i class="far fa-star"></i> <i class="far fa-star"></i> <i class="far fa-star"></i> <i class="far fa-star"></i> </div></div>';
		                        						$wpflistdata_output .= '</div>';
				                        			}
				                        		}
				                        	}
				                        }
					               		/* End: Review Stars */
										if ($output_data_priceval != '') {
											$wpflistdata_output .= $output_data_priceval;
										}else{
											$wpflistdata_output .= '<div class="pflistingitem-subelement pf-price" style="visibility: hidden;"><i class="pfadmicon-glyph-553"></i></div>';
										}
										
										$wpflistdata_output .= '</div>';
								
										$wpflistdata_output .= '</div>';
									}

									if($pfgrid_output == 'pf1col'){
										$wpflistdata_output .= '</div><div class="pfrightcontent">';
									}else{
										$wpflistdata_output .='
										
									</div>
									';
									}

									
									
									$title_text = $ItemDetailArr['if_title'];
									$address_text = $ItemDetailArr['if_address'];
									$excerpt_text = $ItemDetailArr['if_excerpt'];
									
									$wpflistdata_output .= '
									<div class="pflist-detailcontainer pflist-subitem clearfix">
										<ul class="pflist-itemdetails">
											<li class="pflist-itemtitle pflineclamp-title"><a href="'.$ItemDetailArr['if_link'].'">'.$title_text.'</a></li>
											';

											if($setup22_searchresults_hide_address == 0){
												if (!empty($address_text)) {
													$wpflistdata_output .= '<li class="pflist-address pflineclamp-address"><i class="fas fa-map-marker-alt"></i> '.$address_text.'</li>';
												}else{
													$wpflistdata_output .= '<li class="pflist-address pflineclamp-address"></li>';
												}
											
											}
											$wpflistdata_output .= '
										</ul>
									</div>
									';
									if($pfboptx_text != 'style="display:none"' && $pfgrid != 'grid1'){
									$wpflistdata_output .= '
										<div class="pflist-excerpt pflist-subitem pflineclamp-excerpt" '.$pfboptx_text.'>'.$excerpt_text.'</div>
									';
									}
									if ((!empty($output_data_content) || !empty($output_data_priceval)) && $pfgrid != 'grid1') {
										$wpflistdata_output .= '<div class="pflist-subdetailcontainer pflist-subitem"><div class="pflist-customfields">'.$output_data_content.'</div></div>';
									}

									/* Show on map text for search results and search page */
										$wpflistdata_output .= '<div class="pflist-subdetailcontainer pflist-subitem pfshowmapmain clearfix">';
											if ($st22srloc == 1) {
												$location_val = $this->GetPFTermInfoX( $pfitemid, 'pointfinderlocations','',$pointfinderlocationsex_vars,'topmap');
												if (!empty($location_val)) {
													$wpflistdata_output .= $location_val;
												}
											}

											if (!empty($output_data_ltypes)) {
												$wpflistdata_output .= $output_data_ltypes;
											}
											$wpflistdata_output .= '<div class="pfquicklinks">';
												if ($stp22_qwlink == 1) {
												$wpflistdata_output .= '<a data-pfitemid="'.$pfitemid.'" class="pfquickview" title="'.esc_html__('Quick Preview','pointfindercoreelements').'">
													<i class="fas fa-search"></i>
												</a>';
												}
												

												/* Start: Favorites */
												if($setup4_membersettings_favorites == 1){
													$favoriteicon = 'fas fa-heart';
													$favtitle_text = esc_html__('Add to Favorites','pointfindercoreelements');
													if ($user_loggedin_check && count($user_favorites_arr)>0) {
														
														if (in_array($pfitemid, $user_favorites_arr)) {
															$fav_check = 'true';
															$favtitle_text = esc_html__('Remove from Favorites','pointfindercoreelements');
															$favoriteicon = 'fas fa-heart';
														}
													}

													$wpflistdata_output .= '
													<a class="pf-favorites-link" data-pf-num="'.$pfitemid.'" data-pf-active="'.$fav_check.'" data-pf-item="true" title="'.$favtitle_text.'">
														<i class="'.$favoriteicon.'"></i></a>
													';
						                        }
					                    		/* End: Favorites */
					                    	$wpflistdata_output .= '</div>';
										$wpflistdata_output .= '</div>';
									
								/* End: Detail Texts */
									
									$wpflistdata_output .= '
									</div>
								</div>
								
							</div>
						';/*li*/
							
						
							
							
						endwhile;
						
						$wpflistdata .= $wpflistdata_output;               
			            $wpflistdata .= '</div>';/*ul*/
					}
		           

					wp_reset_postdata();

					$wpflistdata .= '</div>';//List Content End
					$wpflistdata .= "</div></div> ";//Form End . List Data End
			
					if ($foundedposts > 0) {
						$content_script = '
						(function($) {
							"use strict"
							$(function() {
								$("#'.$myrandno.'").owlCarousel({
									items : '.$pf_grid_size .',';
									 if($hide_prev_next_buttons !== "yes"){ $content_script .= 'navigation : true,';}else{$content_script .= "navigation : false,";}
									 if($hide_pagination_control !== "yes"){ $content_script .= 'pagination : true,';}else{$content_script .= "pagination : false,";}
									 if($autoplay == "yes"){ $content_script .= 'autoPlay : true,stopOnHover : true,';}else{$content_script .= 'autoPlay : false,';}
									 $content_script .='
									slideSpeed:'.$speed.',
									mouseDrag:true,
									touchDrag:true,
									itemSpaceWidth: '.$itemspacebetween.',';
									if($autoplay === "yes"){ $content_script .= 'itemBorderWidth : 0,';}else{$content_script .= 'itemBorderWidth : '.$pf_grid_size.',';}
									$content_script .='
									autoHeight : true,
									responsive:true,
									itemsScaleUp : false,
									navigationText:false,
									theme:"owl-theme",
									singleItem : false,
									itemsDesktop : [1199,'.$pf_grid_size .'],
									itemsDesktopSmall : [980,'.$pf_grid_size .'],
									itemsTablet: [768, 2],
									itemsTabletSmall: false,
									itemsMobile : [479,1],
								});
							});

						
						})(jQuery);';
						wp_add_inline_script('pftheme-customjs',$content_script,'after');
					}
					


			if ($foundedposts > 0) {
				return $wpflistdata;
			}else{
				return '';
			}

    }

}
new PointFinderListingCarouselShortcode();

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_pointfinder_single_pf_pfitemcarousel extends WPBakeryShortCode {
    }
}