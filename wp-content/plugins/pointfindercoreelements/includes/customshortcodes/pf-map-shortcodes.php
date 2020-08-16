<?php
/*
*
* Visual Composer PointFinder Static Grid Shortcode
*
*/

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

if (!class_exists('PointFinderDirectoryMapShortcode')) {

	class PointFinderDirectoryMapShortcode extends WPBakeryShortCode {
	  use PointFinderOptionFunctions,
	  PointFinderCommonFunctions,
	  PointFinderWPMLFunctions,
	  PointFinderReviewFunctions,
	  PointFinderCommonVCFunctions;

	    public function __construct() {
	        add_action( 'vc_after_init', array( $this, 'pointfinder_single_pf_directory_map_module_mapping' ) );
	        add_shortcode( 'pf_directory_map', array( $this, 'pointfinder_single_pf_directory_map_module_html' ) );
	    }

	    

	    public function pointfinder_single_pf_directory_map_module_mapping() {

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

	        $PFVEX_GetTaxValues1 = $this->PFVEX_GetTaxValues('pointfinderltypes','setup3_pointposttype_pt7','Listing Types');
	        $PFVEX_GetTaxValues2 = $this->PFVEX_GetTaxValues('pointfinderitypes','setup3_pointposttype_pt4','Item Types');
	        $PFVEX_GetTaxValues3 = $this->PFVEX_GetTaxValues('pointfinderlocations','setup3_pointposttype_pt5','Locations');
	        $PFVEX_GetTaxValues4 = $this->PFVEX_GetTaxValues('pointfinderfeatures','setup3_pointposttype_pt6','Features');
	        $PFVEX_GetTaxValues5 = $this->PFVEX_GetTaxValues('pointfinderconditions','setup3_pt14','Conditions');
	      /**
			*Start : Directory Map ----------------------------------------------------------------------------------------------------
			**/
				$uploads = wp_upload_dir();
				$maplanguage = $this->PFSAIssetControl('setup5_mapsettings_maplanguage','','en');
				$setup5_map_key = $this->PFSAIssetControl('setup5_map_key','','');
				if (!empty($setup5_map_key)) {
					$setup5_map_key = '&key='.$setup5_map_key;
				}else{
					$setup5_map_key = '';
				}

				$PFVexFields_dmap = array();
				$PFVexFields_dmap['name'] = esc_html__("PF Directory Map", 'pointfindercoreelements');
				$PFVexFields_dmap['base'] = "pf_directory_map";
				$PFVexFields_dmap['controls'] = "full";
				$PFVexFields_dmap['icon'] = "pf_directory_map";
				$PFVexFields_dmap['category'] = "Point Finder";
				$PFVexFields_dmap['description'] = esc_html__("Directory Map", 'pointfindercoreelements');
				$PFVexFields_dmap['params'] = array();

				$PFVexFields_dmap['front_enqueue_js'] = array();
				$PFVexFields_dmap['front_enqueue_css'] = array();

				array_push($PFVexFields_dmap['params'],
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
					array_push($PFVexFields_dmap['params'],
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
					array_push($PFVexFields_dmap['params'],
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
					array_push($PFVexFields_dmap['params'],
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
					array_push($PFVexFields_dmap['params'],
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

				array_push($PFVexFields_dmap['params'],
						array(
							  "type" => "pf_info_line_field",
							  "param_name" => "pf_info_field10",
						  ),
						array(
							"type" => "textfield",
							"heading" => esc_html__("Default Latitude", "pointfindercoreelements"),
							"param_name" => "setup5_mapsettings_lat",
							"description" => sprintf(esc_html__('This coordinate for auto center on that point. %s Please click here for finding your coordinates %s', 'pointfindercoreelements'),'<a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">','</a>'),
						  	"edit_field_class" => 'vc_col-sm-6 vc_column'
						  ),
						array(
							"type" => "textfield",
							"heading" => esc_html__("Default Longitude", "pointfindercoreelements"),
							"param_name" => "setup5_mapsettings_lng",
							"description" => sprintf(esc_html__('This coordinate for auto center on that point. %s Please click here for finding your coordinates %s', 'pointfindercoreelements'),'<a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">','</a>'),
						  	"edit_field_class" => 'vc_col-sm-6 vc_column'
						  ),
						array(
							  "type" => "pf_info_line_field",
							  "param_name" => "pf_info_field7",
						  ),
						array(
							"type" => "pfa_numeric",
							"heading" => esc_html__("Map Height", "pointfindercoreelements"),
							"param_name" => "setup5_mapsettings_height",
							"value"	=> '550',
							"edit_field_class" => 'vc_col-sm-4 vc_column'
						  ),
						array(
							"type" => "pfa_numeric",
							"heading" => esc_html__("Map Height (Mobile)", "pointfindercoreelements"),
							"param_name" => "setup42_mheight",
							"value"	=> '350',
							"edit_field_class" => 'vc_col-sm-4 vc_column'
						  ),
						array(
							"type" => "pfa_numeric",
							"heading" => esc_html__("Map Height (Tablet)", "pointfindercoreelements"),
							"param_name" => "setup42_theight",
							"value"	=> '400',
							"edit_field_class" => 'vc_col-sm-4 vc_column'
						  ),
						array(
							  "type" => "pf_info_line_field",
							  "param_name" => "pf_info_field4532",
						  ),
						array(
							"type" => "pfa_numeric",
							"heading" => esc_html__("Desktop View Zoom", "pointfindercoreelements"),
							"param_name" => "setup5_mapsettings_zoom",
							"value"	=> '12',
							"edit_field_class" => 'vc_col-sm-6 vc_column'
						  ),
						array(
							"type" => "pfa_numeric",
							"heading" => esc_html__("Mobile View Zoom", "pointfindercoreelements"),
							"param_name" => "setup5_mapsettings_zoom_mobile",
							"value"	=> '10',
							"edit_field_class" => 'vc_col-sm-6 vc_column'
						  ),
						array(
							  "type" => "pf_info_line_field",
							  "param_name" => "pf_info_field6",
						  ),
						array(
							"type" => "pfa_numeric",
							"heading" => esc_html__("Limit Points", "pointfindercoreelements"),
							"param_name" => "setup8_pointsettings_limit",
							"value"	=> '',
							"description" => esc_html__('After changing map point limit then you will see order/orderby filter options. The limit number must be higher than zero. If you set it empty, going to be unlimited.', 'pointfindercoreelements')
						  ),
						array(
							  "type" => "dropdown",
							  "heading" => esc_html__("Limit Points: Order By", "pointfindercoreelements"),
							  "param_name" => "setup8_pointsettings_orderby",
							  "edit_field_class" => 'vc_col-sm-6 vc_column',
							  "dependency" => array('element' => 'setup8_pointsettings_limit','not_empty' => true),
							  "value" => array(esc_html__("Title", "pointfindercoreelements") => 'title',esc_html__("ID", "pointfindercoreelements") => 'id',esc_html__("Date", "pointfindercoreelements") => 'date'),
						  ),
						array(
							  "type" => "dropdown",
							  "heading" => esc_html__("Limit Points: Order", "pointfindercoreelements"),
							  "param_name" => "setup8_pointsettings_order",
							  "edit_field_class" => 'vc_col-sm-6 vc_column',
							  "dependency" => array('element' => 'setup8_pointsettings_limit','not_empty' => true),
							  "value" => array(esc_html__("DESC", "pointfindercoreelements") => 'DESC',esc_html__("ASC", "pointfindercoreelements") => 'ASC'),
						  ),
						array(
							  "type" => "pf_info_line_field",
							  "param_name" => "pf_info_field5",
						  ),
						array(
							  "type" => "dropdown",
							  "heading" => esc_html__("Background Mode", "pointfindercoreelements"),
							  "param_name" => "backgroundmode",
							  "value" => array(esc_html__("Disabled", "pointfindercoreelements") => '0',esc_html__("Enabled", "pointfindercoreelements") => '1'),
							  "description" => esc_html__('If this option is enabled; You can use video background or static image background.', 'pointfindercoreelements') ,
						  ),
						array(
				            "type" => "textarea_html",
				            "class" => "",
				            "heading" => __( "Background Mode Text", "pointfindercoreelements" ),
				            "param_name" => "content",
				            "value" => __( "<p>I am test text block. Click edit button to change this text.</p>", "pointfindercoreelements" ),
				            "description" => __( "Enter your content.", "pointfindercoreelements" ),
				            "dependency" => array('element' => 'backgroundmode','value' => '1'),
				         ),
						array(
							"type" => "pfa_numeric",
							"heading" => esc_html__('Left Margin for BG Mode Text (px)', 'pointfindercoreelements'),
							"param_name" => "box_leftmargin",
							"description" => esc_html__("Leave empty for use default size. (Optional)", "pointfindercoreelements"),
							"value"	=> 350,
							"dependency" => array('element' => 'backgroundmode','value' => '1'),
							"edit_field_class" => 'vc_col-sm-4 vc_column'
						  ),
						array(
							"type" => "pfa_numeric",
							"heading" => esc_html__('Top Margin for BG Mode Text (px)', 'pointfindercoreelements'),
							"param_name" => "box_topmargin",
							"description" => esc_html__("Leave empty for use default size. (Optional)", "pointfindercoreelements"),
							"value"	=> 240,
							"dependency" => array('element' => 'backgroundmode','value' => '1'),
							"edit_field_class" => 'vc_col-sm-4 vc_column'
						  ),
						array(
							"type" => "pfa_numeric",
							"heading" => esc_html__('Top Margin for BG Mode Text (Mobile) (px)', 'pointfindercoreelements'),
							"param_name" => "box_topmargin2",
							"description" => esc_html__("Leave empty for use default size. (Optional)", "pointfindercoreelements"),
							"value"	=> 110,
							"dependency" => array('element' => 'backgroundmode','value' => '1'),
							"edit_field_class" => 'vc_col-sm-4 vc_column'
						  ),
						array(
							  "type" => "dropdown",
							  "heading" => esc_html__("Horizontal Search Mode", "pointfindercoreelements"),
							  "param_name" => "horizontalmode",
							  "value" => array(esc_html__("Disabled", "pointfindercoreelements") => '0',esc_html__("Enabled", "pointfindercoreelements") => '1'),
							  "description" => esc_html__('If this option is enabled; Map search will be horizontal.', 'pointfindercoreelements') ,
						  ),
						
						array(
							  "type" => "pf_info_line_field",
							  "param_name" => "pf_info_field4",
						  ),
						
						array(
							  "type" => "pf_info_line_field",
							  "param_name" => "pf_info_field2",
						  ),
						array(
							  "type" => "dropdown",
							  "heading" => esc_html__("Map Search", "pointfindercoreelements"),
							  "param_name" => "mapsearch_status",
							  "value" => array(esc_html__("Disabled", "pointfindercoreelements") => '0',esc_html__("Enabled", "pointfindercoreelements") => '1'),
							  "edit_field_class" => 'vc_col-sm-6 vc_column',
							  "description" => esc_html__("You can show map search by using this option.", "pointfindercoreelements"),
						  ),

						array(
							  "type" => "dropdown",
							  "heading" => esc_html__("Map Notification Window", "pointfindercoreelements"),
							  "param_name" => "mapnot_status",
							  "value" => array(esc_html__("Disabled", "pointfindercoreelements") => '0',esc_html__("Enabled", "pointfindercoreelements") => '1'),
							  "edit_field_class" => 'vc_col-sm-6 vc_column',
							  "description" => esc_html__("You can show map notification window by using this option.", "pointfindercoreelements"),
						  ),
						array(
							  "type" => "pf_info_line_field",
							  "param_name" => "pf_info_field8",
						  )
						
					);
				vc_map($PFVexFields_dmap);
			/**
			*End : Directory Map ----------------------------------------------------------------------------------------------------
			**/

	    }


	    public function pointfinder_single_pf_directory_map_module_html( $atts,$content="" ) {
		  extract( shortcode_atts( array(
		    'setup5_mapsettings_height' => 550,
			'setup5_mapsettings_lat' => '0',
			'setup5_mapsettings_lng' => '0',
			'setup5_mapsettings_zoom' => 12,
			'setup5_mapsettings_zoom_mobile' => 10,
			'setup8_pointsettings_limit' => '',
			'setup8_pointsettings_orderby' => '',
			'setup8_pointsettings_order' => '',
			'setup7_geolocation_status' => '',
			'mapsearch_status' => '',
			'mapnot_status' => '',
			'listingtype' => '',
			'itemtype' => '',
			'conditions' => '',
			'features' => '',
			'locationtype' => '',
			'backgroundmode' => 0,
			'horizontalmode' => 0,
			'content_bg' => '',
			'box_topmargin' => 240,
			'box_topmargin2' => 110,
			'box_leftmargin' => 350,
			'ne' => '',
			'ne2' => '',
			'sw' => '',
			'sw2' => '',
			'neaddress' => '',
			'setup42_mheight' => 350,
			'setup42_theight' => 400,
			'ppp' => -1,
			'paged' => 1,
			'orderby' => '',
			'order' => ''
		  ), $atts) );


		  	ob_start();
	  		echo do_shortcode($content);
	  		$output_content = ob_get_contents();
	  		ob_end_clean();
		  		
		  	$device_check = $this->pointfinder_device_check('isDesktop');

	  		if ($horizontalmode != 0) {
	  			$horizontalmode_style = ' pfsearch-draggable-full pf-container';
	  			$horizontalmode_style2 = ' pf-row';
	  			$horizontalmode_style3 = ' class="col-lg-12 col-md-12 col-sm-12"';
	  			$hormode = 1;
	  		}else{$horizontalmode_style = $horizontalmode_style2 = $horizontalmode_style3 = '';$hormode = 0;};

	  		$tooltipstatus = $this->PFSAIssetControl('setup12_searchwindow_tooltips','','1');

	  		$drag_icon = "pfadmicon-glyph-151";
	  		$drag_status = "false";
	  		
	  		if (empty($setup8_pointsettings_limit)) {
	  			$setup8_pointsettings_limit = -1;
	  		}

	  		if ($mapsearch_status == 1) {

		  		$generalbradius = $this->PFSAIssetControl('generalbradius','','');
		  		$border_radius_level = $this->PFSAIssetControl('generalbradiuslevel','','0');
				
				$dragstatus = $mapinfostatus = $searchstyle = 'style="';
				$togglesectionstatus = '';
				for ($i=1; $i <= 3; $i++) {
					switch ($i) {
						case 1:
							if($this->PFSAIssetControl('setup12_searchwindow_buttonconfig'.$i,'','1') == 0){
								$dragstatus .= 'display: none;';
								$searchstyle .= 'display: block;margin-left:0;';
							}
							break;

						case 2:
							if($this->PFSAIssetControl('setup12_searchwindow_buttonconfig'.$i,'','1') == 0){
								$mapinfostatus .= 'display: none;';
							}
							break;
						
					}
				}


				if ($mapinfostatus == 'style="display: none;' && $dragstatus == 'style="display: none;') {
					$searchstyle .= 'display: none;';
				}
				if ($generalbradius == 1) {
					if ($mapinfostatus == 'style="' && $dragstatus == 'style="display: none;') {
						$searchstyle .= 'border-top-left-radius:'.$border_radius_level.'px!important;';
						$mapinfostatus .= 'border-top-right-radius:'.$border_radius_level.'px!important;border-top-right-radius:'.$border_radius_level.'px!important;';
					}elseif ($mapinfostatus == 'style="display: none;' && $dragstatus == 'style="display: none;') {
						$togglesectionstatus .= 'border-top-left-radius:'.$border_radius_level.'px!important;border-top-right-radius:'.$border_radius_level.'px!important;';
					}elseif ($mapinfostatus == 'style="display: none;' && $dragstatus == 'style="') {
						$dragstatus .= 'border-top-left-radius:'.$border_radius_level.'px!important;';
						$searchstyle .= 'border-top-right-radius:'.$border_radius_level.'px!important;';
					}elseif ($mapinfostatus == 'style="' && $dragstatus == 'style="'){
						$dragstatus .= 'border-top-left-radius:'.$border_radius_level.'px!important;';
						$mapinfostatus .= 'border-top-right-radius:'.$border_radius_level.'px!important;';
					}elseif ($mapinfostatus == 'style="' && $dragstatus == 'style="display: none;'){
						$searchstyle .= 'border-top-left-radius:'.$border_radius_level.'px!important;';
						$mapinfostatus .= 'border-top-right-radius:'.$border_radius_level.'px!important;';
					}

				}

				$dragstatus .= '"';
				$mapinfostatus .= '"';
				$searchstyle .= '"';



				$setup12_searchwindow_startpositions = $this->PFSAIssetControl('setup12_searchwindow_startpositions','','1');

				if($setup12_searchwindow_startpositions == 1){
					$pfdraggablestyle = 'left:15px;right:auto;';
				}else{
					if ($mapnot_status == 1) {
						$pfdraggablestyle = 'right:15px;top:67px;';
					}else{
						$pfdraggablestyle = 'right:15px;top:auto;';
					}

				}
				if ($horizontalmode == 1) {
					$pfdraggablestyle = 'left:0!important;right:0!important';
					$box_leftmargin=0;
				}
				$stp28_mmenu_menulocation = esc_attr(PFSAIssetControl('stp28_mmenu_menulocation','','left'));

		  		ob_start();
		  		?>
			        <div class="pf-container psearchdraggable" data-direction="<?php echo sanitize_text_field( $stp28_mmenu_menulocation );?>"><div class="pf-row"><div class="col-lg-12">

			        <?php if ($backgroundmode != 0) {?>
					<div class="pf-ex-search-text pfmobileexsearchtext" data-height="<?php echo $box_topmargin2;?>" style="position:absolute;top:<?php echo $box_topmargin;?>px;left:<?php echo $box_leftmargin;?>px;z-index:1"><?php echo $output_content;?></div>
			        <?php }?>
			        <div id="pfsearch-draggable" class="pfsearch-draggable-window<?php echo $horizontalmode_style;?> ui-widget-content" style="<?php echo $pfdraggablestyle;?>">
			          <?php if ($horizontalmode == 0) {?>
			          <div class="pfsearch-header">
			          	<ul class="pftogglemenulist clearfix">
			            	<li class="pftoggle-move" title="<?php echo esc_html__('Drag this window.', 'pointfindercoreelements');?>" <?php echo $dragstatus?>><i class="fas fa-arrows-alt"></i></li>
			                <li class="pftoggle-search" data-pf-icon1="fa-search-minus" data-pf-icon2="fa-search-plus" data-pf-content="search" title="<?php echo esc_html__('Search window.', 'pointfindercoreelements');?>" <?php echo $searchstyle?>><i class="fas fa-search-minus"></i></li>
			                <li class="pftoggle-itemlist" data-pf-icon1="fa-info-circle" data-pf-icon2="fa-times-circle" data-pf-content="itemlist" title="<?php echo esc_html__('Display map info.', 'pointfindercoreelements');?>" <?php echo $mapinfostatus?>><i class="fas fa-info-circle"></i></li>
			               
			            </ul>
			          </div>
			          <?php
			      		}

			      		if ($device_check) {
			      			$stp_mxheight = 'max-height:'.($setup5_mapsettings_height - 90).'px;';
			      		}else{
			      			$stp_mxheight = '';
			      		}
			          /**
			          *Start: Search Form
			          **/
			          ?>
				          <form id="pointfinder-search-form">
				          	<div class="pfsearch-content golden-forms pfdragcontent<?php echo $horizontalmode_style2;?>" style="<?php echo $stp_mxheight;?><?php echo $togglesectionstatus;?>">
					          	<div class="pfsearchformerrors">
					          		<ul></ul>
					           		<a class="button pfsearch-err-button"><i class="fas fa-times"></i> <?php echo esc_html__('CLOSE','pointfindercoreelements')?></a>
					          	</div>
					          	<?php
								$setup1s_slides = $this->PFSAIssetControl('setup1s_slides','','');

								if(is_array($setup1s_slides)){

									$PFListSF = new PF_SF_Val();
									foreach ($setup1s_slides as &$value) {

										$PFListSF->GetValue($value['title'],$value['url'],$value['select'],0,array(),$hormode);

									}

									/*Get Listing Type Item Slug*/
				                    $fltf = $this->pointfinder_find_requestedfields('pointfinderltypes');
				                    $features_field = $this->pointfinder_find_requestedfields('pointfinderfeatures');
				                    $itemtypes_field = $this->pointfinder_find_requestedfields('pointfinderitypes');
				                    $conditions_field = $this->pointfinder_find_requestedfields('pointfinderconditions');

				                    $stp_syncs_it = $this->PFSAIssetControl('stp_syncs_it','',1);
									$stp_syncs_co = $this->PFSAIssetControl('stp_syncs_co','',1);
									$setup4_sbf_c1 = $this->PFSAIssetControl('setup4_sbf_c1','',1);

									$second_request_process = false;
									$second_request_text = "{features:'',itemtypes:'',conditions:''};";
									$multiple_itemtypes = $multiple_features = $multiple_conditions =  '';

									if (!empty($features_field) || !empty($itemtypes_field) || !empty($conditions_field)) {
										$second_request_process = true;
										$second_request_text = '{';


										if (!empty($features_field) && $setup4_sbf_c1 == 0) {
											$second_request_text .= "features:'$features_field'";
											$multiple_features = $this->PFSFIssetControl('setupsearchfields_'.$features_field.'_multiple','','0');
										}
										if (!empty($itemtypes_field) && $stp_syncs_it == 0) {
											if (!empty($features_field) && $setup4_sbf_c1 == 0) {
												$second_request_text .= ",";
											}
											$second_request_text .= "itemtypes:'$itemtypes_field'";
											$multiple_itemtypes = $this->PFSFIssetControl('setupsearchfields_'.$itemtypes_field.'_multiple','','0');
										}
										if (!empty($conditions_field) && $stp_syncs_co == 0) {
											if ((!empty($features_field) && $setup4_sbf_c1 == 0) || (!empty($itemtypes_field) && $stp_syncs_it == 0)) {
												$second_request_text .= ",";
											}
											$second_request_text .= "conditions:'$conditions_field'";
											$multiple_conditions = $this->PFSFIssetControl('setupsearchfields_'.$conditions_field.'_multiple','','0');
										}

										if (!empty($multiple_itemtypes)) {
											if (!empty($second_request_text)) {
												$second_request_text .= ",";
											}
											$second_request_text .= "mit:'1'";
										}

										if (!empty($multiple_features)) {
											if (!empty($second_request_text)) {
												$second_request_text .= ",";
											}
											$second_request_text .= "mfe:'1'";
										}

										if (!empty($multiple_conditions)) {
											if (!empty($second_request_text)) {
												$second_request_text .= ",";
											}
											$second_request_text .= "mco:'1'";
										}


										$second_request_text .= '};';
									}


									echo $PFListSF->FieldOutput;
									echo '<div id="pfsearchsubvalues" '.$horizontalmode_style3.'></div>';
									if ($horizontalmode == 1) {
										echo '<div class="colhorsearch colhorseachbutton">';
									}
									echo '<a class="button pfsearch" id="pf-search-button">'.esc_html__('FILTER POINTS', 'pointfindercoreelements').'</a><a class="button pfreset" id="pf-resetfilters-button">'.esc_html__('RESET', 'pointfindercoreelements').'</a>';
									if ($horizontalmode == 1) {
										echo '</div>';
									}
									$script_output = '';
									$script_output .= '
									(function($) {
										"use strict";
										$.pffieldsids = '.$second_request_text.'
										$.pfsliderdefaults = {};$.pfsliderdefaults.fields = Array();
										$(function(){

										'.$PFListSF->ScriptOutput;
										$script_output .= 'var pfsearchformerrors = $(".pfsearchformerrors");
											$("#pointfinder-search-form").validate({
												  debug:false,
												  onfocus: false,
												  onfocusout: false,
												  onkeyup: false,
												  rules:{'.$PFListSF->VSORules.'},messages:{'.$PFListSF->VSOMessages.'},
												  ignore: ".select2-input, .select2-focusser, .pfignorevalidation",
												  validClass: "pfvalid",
												  errorClass: "pfnotvalid pfaddnotvalidicon",
												  errorElement: "li",
												  errorContainer: pfsearchformerrors,
												  errorLabelContainer: $("ul", pfsearchformerrors),
												  invalidHandler: function(event, validator) {
													var errors = validator.numberOfInvalids();
													if (errors) {
														pfsearchformerrors.show("slide",{direction : "up"},100);
														$(".pfsearch-err-button").on("click",function(){
															pfsearchformerrors.hide("slide",{direction : "up"},100);
															return false;
														});
													}else{
														pfsearchformerrors.hide("fade",300);
													}
												  }
											});';

											if ($horizontalmode == 1 && $this->PFSAIssetControl('as_hormode_close','','0') == 1) {
												$script_output .= '
													$( ".pfsearch-draggable-full" ).toggle( "slide",{direction:"up",mode:"hide"},function(){
														$(".pfsopenclose").fadeToggle("fast");
														$(".pfsopenclose2").fadeToggle("fast");
													});
												';
											}

											if ($backgroundmode != 0) {
				                        		$script_output .= 'if($.pf_mobile_check()){
				                        			$("#wpf-map-container").css("min-height",""+$("#pfdirectorymap").data("height")+"px");
					                        		$("#pfdirectorymap").css("z-index",-1).css("margin-top",-1).css("position","absolute").css("height",""+$("#pfdirectorymap").data("height")+"px").hide();
						                        	$(".pfnotificationwindow").css("z-index",-1);
						                        	$(".pf-err-button").css("z-index",-1);
						                        	$(".pfnotificationwindow").hide();
					                        		
				                        		}else{
													$("#wpf-map-container").css("min-height",""+$("#pfdirectorymap").data("mheight")+"px");
													$("#pfdirectorymap").css("z-index",-1).css("margin-top",-1).css("position","absolute").css("height",""+$("#pfdirectorymap").data("mheight")+"px").hide();
													$(".pfnotificationwindow").css("z-index",-1);
													$(".pf-err-button").css("z-index",-1);
													$(".pfnotificationwindow").hide();
				                        			$(".pf-ex-search-text").css("top",""+$(".pfmobileexsearchtext").data("height")+"px");
				                        			//$(".pf-ex-search-text").remove();/*text bg remove*/
				                        			//$("#wpf-map-container").closest(".pf-fullwidth").prev(".upb_video-wrapper").remove();/*Video bg remove*/
													//$("#wpf-map-container").closest(".pf-fullwidth").prev(".upb_row_bg").remove();/*Image bg remove*/
				                        		}';
				                        	}

				                        if ($fltf != 'none') {
				                        	$as_mobile_dropdowns = $this->PFSAIssetControl('as_mobile_dropdowns','','0');

											if ($as_mobile_dropdowns == 1) {
												$script_output .= '
												$(function(){
						                            $("#'.$fltf.'").change(function(e) {

						                              $.PFGetSubItems($("#'.$fltf.'").val(),"",0,'.$hormode.');
						                              ';
						                              if ($second_request_process) {
						                              	$script_output .= '$.PFRenewFeatures($("#'.$fltf.'").val(),"'.$second_request_text.'");';
						                              }
						                              $script_output .= '
						                            });
						                            $(document).one("ready",function(){
						                                if ($("#'.$fltf.'" ).val() !== 0) {
						                                   $.PFGetSubItems($("#'.$fltf.'" ).val(),"",0,'.$hormode.');
						                                   ';
							                               if ($second_request_process) {
							                              	 $script_output .= '$.PFRenewFeatures($("#'.$fltf.'").val(),"'.$second_request_text.'");';
							                               }
							                               $script_output .= '
						                                }
						                                setTimeout(function(){
						                                	$(".select2-container" ).attr("title","");
						                                	$("#'.$fltf.'" ).attr("title","");
						                                },300);
						                            });
					                            });
					                            ';
											}else{
												$script_output .= '
					                            $("#'.$fltf.'" ).change(function(e) {

					                              $.PFGetSubItems($("#'.$fltf.'" ).val(),"",0,'.$hormode.');
					                              ';
					                              if ($second_request_process) {
					                              	$script_output .= '$.PFRenewFeatures($("#'.$fltf.'" ).val(),"'.$second_request_text.'");';
					                              }
					                              $script_output .= '
					                            });
					                            $(document).one("ready",function(){

					                                if ($("#'.$fltf.'" ).val() !== 0) {
					                                   $.PFGetSubItems($("#'.$fltf.'" ).val(),"",0,'.$hormode.');
					                                   ';
						                              if ($second_request_process) {
						                              	$script_output .= '$.PFRenewFeatures($("#'.$fltf.'" ).val(),"'.$second_request_text.'");';
						                              }
						                              $script_output .= '
					                                }
					                                setTimeout(function(){
					                                	$(".select2-container" ).attr("title","");
					                                	$("#'.$fltf.'" ).attr("title","")
					                                },300);
					                            });

					                            ';
											}

				                        }
				                        $script_output .= '
										});'.$PFListSF->ScriptOutputDocReady;
									}
									$script_output .= '

									})(jQuery);
									';
									
									unset($PFListSF);
							  ?>
				            </div>
				          </form><!-- // pointfinder-search-form close-->
			          <?php
			          /**
			          *End: Search Form
			          **/
			          ?>
			          <div class="pfitemlist-content pfdragcontent">
			          <?php
			          global $pointfindertheme_option;
					  $setup12_searchwindow_mapinfotext = ($pointfindertheme_option['setup12_searchwindow_mapinfotext'])?wp_kses_post($pointfindertheme_option['setup12_searchwindow_mapinfotext']):'';
					  $setup12_searchwindow_mapinfotext = apply_filters( 'the_content', $setup12_searchwindow_mapinfotext );
					  echo $setup12_searchwindow_mapinfotext;
					  ?>

			          </div>
			  
			         <?php if ($horizontalmode == 1) {?>
			         <a class="pfsopenclose hidden-xs"><i class="fas fa-angle-up"></i></a>
					 <?php }?>
			        </div>
			        </div></div></div>
			       <?php if ($horizontalmode == 1) {?>
			        <a class="pfsopenclose2 hidden-xs"><i class="fas fa-search"></i> <?php echo esc_html__('SEARCH','pointfindercoreelements');?></a>

					<?php }?>
			    <!--  / Search Container -->
			    <?php
		  		$map_search_content = ob_get_contents();
		  		ob_end_clean();
		  		}


		  		

		  		ob_start();

				if($mapsearch_status == 1 ){
					echo $map_search_content;
				}
				?>

			
			<div id="wpf-map-container"<?php if ($backgroundmode != 0) {?>class="pfimagebgcontainer"<?php }?>>

		    	<div class="pfmaploading pfloadingimg"></div>


		        <?php

					if ($setup7_geolocation_status == '') {
						$setup7_geolocation_status = $this->PFSAIssetControl('setup7_geolocation_status','','0');
					}


					$stp5_mapty = $this->PFSAIssetControl('stp5_mapty','',1);
					$setup42_mheight = $this->PFSAIssetControl('setup42_mheight','height','350');
					$setup42_mheight = str_replace('px', '', $setup42_mheight);
					$setup42_theight = $this->PFSAIssetControl('setup42_theight','height','400');
					$setup42_theight = str_replace('px', '', $setup42_theight);

					$we_special_key = $wemap_here_appid = $wemap_here_appcode = '';
						    
					switch ($stp5_mapty) {
						case 1:
							$we_special_key = $this->PFSAIssetControl('setup5_map_key','','');
							break;

						case 3:
							$we_special_key = $this->PFSAIssetControl('stp5_mapboxpt','','');
							break;

						case 5:
							$wemap_here_appid = $this->PFSAIssetControl('wemap_here_appid','','');
							$wemap_here_appcode = $this->PFSAIssetControl('wemap_here_appcode','','');
							break;

						case 6:
							$we_special_key = $this->PFSAIssetControl('wemap_bingmap_api_key','','');
							break;

						case 4:
							$we_special_key = $this->PFSAIssetControl('wemap_yandexmap_api_key','','');
							break;
					}
					
					$setup7_geolocation_distance = $this->PFSAIssetControl('setup7_geolocation_distance','',10);
					$setup7_geolocation_distance_unit = $this->PFSAIssetControl('setup7_geolocation_distance_unit','',"km");
					$setup7_geolocation_hideinfo = $this->PFSAIssetControl('setup7_geolocation_hideinfo','',1);
					$setup6_clustersettings_status = $this->PFSAIssetControl('setup6_clustersettings_status','',1);
					$stp6_crad = $this->PFSAIssetControl('stp6_crad','',100);
					$setup10_infowindow_height = $this->PFSAIssetControl('setup10_infowindow_height','','136');
					$setup10_infowindow_width = $this->PFSAIssetControl('setup10_infowindow_width','','350');
					$s10_iw_w_m = $this->PFSAIssetControl('s10_iw_w_m','','184');
					$s10_iw_h_m = $this->PFSAIssetControl('s10_iw_h_m','','136');
				?>

		    	<div id="pfdirectorymap"
		    	data-mode="topmap" 
		    	data-height="<?php echo $setup5_mapsettings_height;?>" 
			    data-theight="<?php echo $setup42_theight;?>" 
			    data-mheight="<?php echo $setup42_mheight;?>" 
			    data-lat="<?php echo $setup5_mapsettings_lat;?>" 
	    		data-lng="<?php echo $setup5_mapsettings_lng;?>" 
	    		data-zoom="<?php echo $setup5_mapsettings_zoom;?>" 
	    		data-zoomm="<?php echo $setup5_mapsettings_zoom_mobile;?>" 
	    		data-zoommx="18" 
	    		data-mtype="<?php echo $stp5_mapty;?>" 
	    		data-key="<?php echo $we_special_key;?>" 
	    		data-hereappid="<?php echo $wemap_here_appid;?>" 
				data-hereappcode="<?php echo $wemap_here_appcode;?>" 
				data-spl="<?php echo $setup8_pointsettings_limit;?>" 
				data-splo="<?php echo $setup8_pointsettings_order;?>" 
				data-splob="<?php echo $setup8_pointsettings_orderby;?>" 
				data-ppp="<?php echo $ppp;?>" 
				data-paged="<?php echo $paged;?>" 
				data-order="<?php echo $order;?>" 
				data-orderby="<?php echo $orderby;?>" 
				data-glstatus="<?php echo $setup7_geolocation_status;?>"
				data-gldistance="<?php echo $setup7_geolocation_distance;?>" 
				data-gldistanceunit="<?php echo $setup7_geolocation_distance_unit;?>" 
				data-gldistancepopup="<?php echo $setup7_geolocation_hideinfo;?>" 
				data-found=""  
				data-cluster="<?php echo $setup6_clustersettings_status;?>" 
				data-clusterrad="<?php echo $stp6_crad;?>" 
				data-iheight="<?php echo $setup10_infowindow_height;?>" 
				data-iwidth="<?php echo $setup10_infowindow_width;?>" 
				data-imheight="<?php echo $s10_iw_h_m;?>" 
				data-imwidth="<?php echo $s10_iw_w_m;?>" 
				data-ttstatus="<?php echo $tooltipstatus;?>" 
				></div>
		    	<?php if ($mapnot_status == 1) {?>
		        <div class="pfnotificationwindow">
		            <span class="pfnottext"></span>

		        </div>
		        <a class="pf-err-button pfnot-err-button" id="pfnot-err-button">
		           	<i class="fas fa-times"></i>
		        </a>
		        <a class="pf-err-button pfnot-err-button pfnot-err-button-menu" id="pfnot-err-button-menu">
		        	<i class="fas fa-info"></i>
		        </a>
		        <?php }?>
		    </div>

		    <?php
				$serialized_sdata = $data_values = $fltf_get = '';

				$coordval = (isset($_GET['pointfinder_google_search_coord']))?esc_attr($_GET['pointfinder_google_search_coord']):'';
				$fltf = $this->pointfinder_find_requestedfields('pointfinderltypes');

				
				if(isset($_GET['serialized'])){
					$serialized_sdata = base64_encode(maybe_serialize($_GET));
				}
				$data_values .= ' data-sdata="'.$serialized_sdata.'"';	

				if(!empty($ne)){
					$data_values .= ' data-ne="'.$ne.'"';
					$data_values .= ' data-sw="'.$sw.'"';
					$data_values .= ' data-ne2="'.$ne2.'"';
					$data_values .= ' data-sw2="'.$sw2.'"';
				}
				$data_values .= ' data-lt="'.$this->PFEX_extract_type_ig($listingtype).'"';
				$data_values .= ' data-lc="'.$this->PFEX_extract_type_ig($locationtype).'"';
				$data_values .= ' data-co="'.$this->PFEX_extract_type_ig($conditions).'"';
				$data_values .= ' data-it="'.$this->PFEX_extract_type_ig($itemtype).'"';
				$data_values .= ' data-fe="'.$this->PFEX_extract_type_ig($features).'"';

				//$data_values .= ' data-csauto="'.$csauto.'"';
				$data_values .= ' data-fltf="'.$fltf.'"';
				$data_values .= ' data-coordval="'.$coordval.'"';

				if ($this->PFSAIssetControl('setup5_mapsettings_mapautoopen','','0') == 1) {
					$data_values .= ' data-autoopen="1"';
				}else{
					$data_values .= ' data-autoopen="0"';
				}

				if (isset($_GET[$fltf])) {
	        		$fltf_get = intval($_GET[$fltf]);
	        	}

				$data_values .= ' data-fltfget="'.$fltf_get.'"';
			?>

		    <div class="pfsearchresults-container" <?php echo $data_values;?>></div>


		<?php
			$output = ob_get_contents();
			ob_end_clean();

	    	/*Point settings*/
			$setup10_infowindow_height = $this->PFSAIssetControl('setup10_infowindow_height','','136');
			$setup10_infowindow_width = $this->PFSAIssetControl('setup10_infowindow_width','','350');

			if($setup10_infowindow_height != 136){ $heightbetweenitems = $setup10_infowindow_height - 136;}else{$heightbetweenitems = 0;}
			if($setup10_infowindow_width != 350){
				$widthbetweenitems = (($setup10_infowindow_width - 350)/2);
			}else{
				$widthbetweenitems = 0;
			}
		
	    	$s10_iw_w_m = $this->PFSAIssetControl('s10_iw_w_m','','184');
			$s10_iw_h_m = $this->PFSAIssetControl('s10_iw_h_m','','136');
			if($s10_iw_h_m != 136){ $heightbetweenitems2 = $s10_iw_h_m - 136;}else{$heightbetweenitems2 = 0;}
			if($s10_iw_w_m != 184){ $widthbetweenitems2 = (($s10_iw_w_m - 184)/2);}else{$widthbetweenitems2 = -6;}

			if (empty($script_output)) {
				$script_output = '';
			}
			$script_output .= 'var pficoncategories = [];';
			$script_output .= $this->pf_get_default_cat_images();

			wp_add_inline_script('theme-pfdirectorymap',$script_output,'after');
			return $output;
		}


		private function pf_get_default_cat_images($pflang = ''){
								
			$wpflistdata = '';

			/**
			*Start: Default Point Variables
			**/
				if ($this->PFASSIssetControl('st8_npsys','',0) != 1) {
					$icon_layout_type = $this->PFPFIssetControl('pscp_pfdefaultcat_icontype','','1');
					$icon_name = $this->PFPFIssetControl('pscp_pfdefaultcat_iconname','','');
					$icon_size = $this->PFPFIssetControl('pscp_pfdefaultcat_iconsize','','middle');
					$icon_bg_color = $this->PFPFIssetControl('pscp_pfdefaultcat_bgcolor','','#b00000');

					$arrow_text = ($icon_layout_type == 2)? '<div class=\'pf-pinarrow\' style=\'border-color: '.$icon_bg_color.' transparent transparent transparent;\'></div>': '';

					$wpflistdata .= 'pficoncategories["pfcatdefault"] =';
					$wpflistdata .= ' "<div ';
					$wpflistdata .= 'class=\'pfcatdefault-mapicon pf-map-pin-'.$icon_layout_type.' pf-map-pin-'.$icon_layout_type.'-'.$icon_size.'\'';
					$wpflistdata .= ' >';
					$wpflistdata .= '<i class=\''.$icon_name.'\' ></i></div>'.$arrow_text.'";'.PHP_EOL;
				}else{
					$icon_layout_type = $this->PFASSIssetControl('cpoint_icontype','',1);
					$icon_name = $this->PFASSIssetControl('cpoint_iconname','','');
					$icon_namefs = $this->PFASSIssetControl('cpoint_iconnamefs','','');
					$icon_size = $this->PFASSIssetControl('cpoint_iconsize','','middle');
					$icon_bg_color = $this->PFASSIssetControl('cpoint_bgcolor','','#b00000');

					$arrow_text = ($icon_layout_type == 2)? '<div class=\'pf-pinarrow\' style=\'border-color: '.$icon_bg_color.' transparent transparent transparent;\'></div>': '';

					$wpflistdata .= 'pficoncategories["pfcatdefault"] =';
					$wpflistdata .= ' "<div ';
					$wpflistdata .= 'class=\'pfcatdefault-mapicon pf-map-pin-'.$icon_layout_type.' pf-map-pin-'.$icon_layout_type.'-'.$icon_size.'\'';
					$wpflistdata .= ' >';
					if (!empty($icon_namefs)) {
						$wpflistdata .= '<i class=\''.$icon_namefs.'\' ></i></div>'.$arrow_text.'";'.PHP_EOL;
					} else {
						$wpflistdata .= '<i class=\''.$icon_name.'\' ></i></div>'.$arrow_text.'";'.PHP_EOL;
					}
					
				}
			/**
			*End: Default Point Variables
			**/



			/**
			*Start: Cat Point Variables
			**/
				
				$pf_get_term_details = get_terms('pointfinderltypes',array('hide_empty'=>false)); 


				if(count($pf_get_term_details) > 0){
					$default_language = $current_language = $listing_meta = $cpoint_type = $cpoint_icontype = $cpoint_iconsize = $cpoint_iconname = $cpoint_bgcolor = '';
					
					if (class_exists('SitePress')) {
						$default_language = $this->PF_default_language();
						$current_language = $this->PF_current_language();
					}

					if ($this->PFASSIssetControl('st8_npsys','',0) == 1) {
						$listing_meta = get_option('pointfinderltypes_style_vars');
						$cpoint_type = $this->PFASSIssetControl('cpoint_type','',0);
						$cpoint_icontype = $this->PFASSIssetControl('cpoint_icontype','',1);
						$cpoint_iconsize = $this->PFASSIssetControl('cpoint_iconsize','','middle');
						$cpoint_iconname = $this->PFASSIssetControl('cpoint_iconname','','');
						$cpoint_bgcolor = $this->PFASSIssetControl('cpoint_bgcolor','','#b00000');
					}
					$st8_npsys = $this->PFASSIssetControl('st8_npsys','',0);

				    if ($st8_npsys == 1) {
				    	foreach ( $pf_get_term_details as $pf_get_term_detail ) {

						$wpflistdata .= $this->pointfinder_get_category_points(
							array(
								'pf_get_term_detail_idm' => $pf_get_term_detail->term_id,
								'pf_get_term_detail_idm_parent' => $pf_get_term_detail->parent,
						        'listing_meta' => $listing_meta,
						        'cpoint_type' => $cpoint_type,
								'cpoint_icontype' => $cpoint_icontype,
								'cpoint_iconsize' => $cpoint_iconsize,
								'cpoint_iconname' => $cpoint_iconname,
								'cpoint_bgcolor' => $cpoint_bgcolor,
								'dlang' => $default_language,
								'clang' => $current_language,
								'st8_npsys' => $st8_npsys
							));

						}
				    }else{
				    	foreach ( $pf_get_term_details as $pf_get_term_detail ) {
							if ($pf_get_term_detail->parent == 0) {
								
								$wpflistdata .= $this->pointfinder_get_category_points(
									array(
									'pf_get_term_detail_idm' => $pf_get_term_detail->term_id,
							        'listing_meta' => $listing_meta,
							        'cpoint_type' => $cpoint_type,
									'cpoint_icontype' => $cpoint_icontype,
									'cpoint_iconsize' => $cpoint_iconsize,
									'cpoint_iconname' => $cpoint_iconname,
									'cpoint_bgcolor' => $cpoint_bgcolor,
									'dlang' => $default_language,
									'clang' => $current_language,
									'st8_npsys' => $st8_npsys
									));

								$pf_get_term_details_sub = get_terms('pointfinderltypes',array('hide_empty'=>false,'parent'=>$pf_get_term_detail->term_id)); 

								foreach ($pf_get_term_details_sub as $pf_get_term_detail_sub) {
									$wpflistdata .= $this->pointfinder_get_category_points(
										array(
											'pf_get_term_detail_idm' => $pf_get_term_detail_sub->term_id,
									        'listing_meta' => $listing_meta,
									        'cpoint_type' => $cpoint_type,
											'cpoint_icontype' => $cpoint_icontype,
											'cpoint_iconsize' => $cpoint_iconsize,
											'cpoint_iconname' => $cpoint_iconname,
											'cpoint_bgcolor' => $cpoint_bgcolor,
											'dlang' => $default_language,
											'clang' => $current_language,
											'st8_npsys' => $st8_npsys
										));
								}

							}
							
						}
				    }
					
					/*
						Loop End from PF Custom Points
					*/

		
				}

			/**
			*End: Cat Point Variables
			**/

			return $wpflistdata;
		}

		private function pointfinder_get_category_points($params = array()){

			$defaults = array( 
		        'pf_get_term_detail_idm' => '',
		        'pf_get_term_detail_idm_parent' => '',
		        'listing_meta' => '',
		        'cpoint_type' => 0,
				'cpoint_icontype' => 1,
				'cpoint_iconsize' => 'middle',
				'cpoint_iconname' => '',
				'cpoint_bgcolor' => '#b00000',
				'dlang' => '',
				'clang' => '',
				'st8_npsys' => 0
		    );

			$params = array_merge($defaults, $params);

			$listing_meta = $params['listing_meta'];
		   
			$pf_get_term_detail_id = $pf_get_term_detail_idxx = $params['pf_get_term_detail_idm'];
			$pf_get_term_detail_idm_parent = $params['pf_get_term_detail_idm_parent'];
			
			$output_data = $pf_get_term_detail_id_output = '';

			if(class_exists('SitePress')) {
				$pf_get_term_detail_id = apply_filters( 'wpml_object_id',$params['pf_get_term_detail_idm'],'pointfinderltypes',true,$params['dlang']);
				$pf_get_term_detail_idm_parent = apply_filters( 'wpml_object_id',$params['pf_get_term_detail_idm_parent'],'pointfinderltypes',true,$params['dlang']);
				$pf_get_term_detail_idxx = apply_filters( 'wpml_object_id',$params['pf_get_term_detail_idm'],'pointfinderltypes',true,$params['clang']);
			}

			if ($params['st8_npsys'] == 1) {
				$run_parent_check = false;

				if(isset($listing_meta[$pf_get_term_detail_id])){
					$slisting_meta = $listing_meta[$pf_get_term_detail_id];
					$icon_type = (isset($slisting_meta['cpoint_type']))?$slisting_meta['cpoint_type']:0;
					if (empty($icon_type)) {
						$run_parent_check = true;
					}else{
						$run_parent_check = false;
						$pf_get_term_detail_id_output = $pf_get_term_detail_id;
					}
				}else{
					$slisting_meta = '';
					$run_parent_check = true;
				}

				/* If 2nd level */
				if ($run_parent_check && !empty($pf_get_term_detail_idm_parent)) {
					if(isset($listing_meta[$pf_get_term_detail_idm_parent])){
						$slisting_meta = $listing_meta[$pf_get_term_detail_idm_parent];
						$icon_type = (isset($slisting_meta['cpoint_type']))?$slisting_meta['cpoint_type']:0;
						if (empty($icon_type)) {
							$run_parent_check = true;
						}else{
							$run_parent_check = false;
							$pf_get_term_detail_id_output = $pf_get_term_detail_idm_parent;
						}

					}else{
						$slisting_meta = '';
						$run_parent_check = true;
					}
				}

			
				/* If 3rd level */
				if ($run_parent_check && !empty($pf_get_term_detail_idm_parent)) {
					$top_most_parent = $this->pf_get_term_top_most_parent($pf_get_term_detail_id,"pointfinderltypes");
					$top_most_parent = (isset($top_most_parent['parent']))?$top_most_parent['parent']:'';
					
					if(isset($listing_meta[$top_most_parent])){
						$slisting_meta = $listing_meta[$top_most_parent];
						$pf_get_term_detail_id_output = $top_most_parent;
					}else{
						$slisting_meta = '';
					}
					$run_parent_check = false;
				}

				
				
				if (!empty($slisting_meta)) {

					$icon_type = (isset($slisting_meta['cpoint_type']))?$slisting_meta['cpoint_type']:0;
					$icon_layout_type = (isset($slisting_meta['cpoint_icontype']))?$slisting_meta['cpoint_icontype']:1;
					$icon_size = (isset($slisting_meta['cpoint_iconsize']))?$slisting_meta['cpoint_iconsize']:'middle';
					$icon_bg_color = (isset($slisting_meta['cpoint_bgcolor']))?$slisting_meta['cpoint_bgcolor']:'#b00000';
					$icon_name = (isset($slisting_meta['cpoint_iconname']))?$slisting_meta['cpoint_iconname']:'';
					$icon_namefs = (isset($slisting_meta['cpoint_iconnamefs']))?$slisting_meta['cpoint_iconnamefs']:'';
					if ($icon_type == 2) {
						$arrow_text = ($icon_layout_type == 2)? '<div class=\'pf-pinarrow\' style=\'border-color: '.$icon_bg_color.' transparent transparent transparent;\'></div>': '';

						$output_data .= 'pficoncategories["pfcat'.$pf_get_term_detail_id.'"] =';
						$output_data .= ' "<div ';
						$output_data .= 'class=\'pfcat'.$pf_get_term_detail_id_output.'-mapicon pf-map-pin-'.$icon_layout_type.' pf-map-pin-'.$icon_layout_type.'-'.$icon_size.'\'';
						$output_data .= '>';
						if (!empty($icon_namefs)) {
		                  $output_data .= '<i class=\''.$icon_namefs.'\'></i></div>'.$arrow_text.'";';
		                }else{
		                  $output_data .= '<i class=\''.$icon_name.'\'></i></div>'.$arrow_text.'";';
		                }
					}else{
						$output_data .= 'pficoncategories["pfcat'.$pf_get_term_detail_id.'"] =';
						$output_data .= ' "<div ';
						$output_data .= 'class=\'pfcat'.$pf_get_term_detail_id_output.'-mapicon\'';
						$output_data .= '>';
						$output_data .= '</div>";'.PHP_EOL;
					}
				}else{

					/* Check parent term has settings */

					
					if ($params['cpoint_type'] == 0) {
						$arrow_text = ($params['cpoint_icontype'] == 2)? '<div class=\'pf-pinarrow\' style=\'border-color: '.$params['cpoint_bgcolor'].' transparent transparent transparent;\'></div>': '';

						$output_data .= 'pficoncategories["pfcat'.$pf_get_term_detail_id.'"] =';
						$output_data .= ' "<div ';
						$output_data .= 'class=\'pfcatdefault-mapicon pf-map-pin-'.$params['cpoint_icontype'].' pf-map-pin-'.$params['cpoint_icontype'].'-'.$params['cpoint_iconsize'].'\'';
						$output_data .= ' >';
						$output_data .= '<i class=\''.$params['cpoint_iconname'].'\' ></i></div>'.$arrow_text.'";'.PHP_EOL;
					}else{
						$output_data .= 'pficoncategories["pfcat'.$pf_get_term_detail_id.'"] =';
						$output_data .= ' "<div ';
						$output_data .= 'class=\'pfcatdefault-mapicon\'';
						$output_data .= '>';
						$output_data .= '</div>";'.PHP_EOL;
					}
				}
					
			}else{
				
				$icon_type = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_type','','0');

				$icon_bg_image = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_bgimage','','0');

				$icon_layout_type = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_icontype','','1');
				$icon_name = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_iconname','','');
				$icon_size = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_iconsize','','middle');
				$icon_bg_color = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_bgcolor','','#b00000');
				
				$arrow_text = ($icon_layout_type == 2)? '<div class=\'pf-pinarrow\' style=\'border-color: '.$icon_bg_color.' transparent transparent transparent;\'></div>': '';

				if ($icon_type == 0 && empty($icon_bg_image)) {

					$output_data .= 'pficoncategories["pfcat'.$pf_get_term_detail_id.'"] =';
					$output_data .= ' "<div ';
					$output_data .= 'class=\'pfcat'.$pf_get_term_detail_id.'-mapicon pf-map-pin-'.$icon_layout_type.' pf-map-pin-'.$icon_layout_type.'-'.$icon_size.'\'';
					$output_data .= ' >';
					$output_data .= '<i class=\''.$icon_name.'\' ></i></div>";'.PHP_EOL;
				
				}elseif ($icon_type != 0 && !empty($icon_bg_image)){

					$height_calculated = $icon_bg_image['height'];
					$width_calculated = $icon_bg_image['width'];

					$output_data .= 'pficoncategories["pfcat'.$pf_get_term_detail_id.'"] =';
					$output_data .= ' "<div ';
					$output_data .= 'class=\'pf-map-pin-x\' ';
					$output_data .= 'style=\'background-image:url('.$icon_bg_image['url'].');opacity:1; background-size:'.$width_calculated.'px '.$height_calculated.'px; width:'.$width_calculated.'px; height:'.$height_calculated.'px;\'';
					$output_data .= ' >';
					$output_data .= '</div>";'.PHP_EOL;
				
				}else{

					$output_data .= 'pficoncategories["pfcat'.$pf_get_term_detail_id.'"] =';
					$output_data .= ' "<div ';
					$output_data .= 'class=\'pfcat'.$pf_get_term_detail_id.'-mapicon pf-map-pin-'.$icon_layout_type.' pf-map-pin-'.$icon_layout_type.'-'.$icon_size.'\'';
					$output_data .= ' >';
					$output_data .= '<i class=\''.$icon_name.'\' ></i></div>'.$arrow_text.'";'.PHP_EOL;

				}
			}

			return $output_data;
		}

	}
	new PointFinderDirectoryMapShortcode();	
}


if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_pointfinder_single_pf_directory_map extends WPBakeryShortCode {
    }
}