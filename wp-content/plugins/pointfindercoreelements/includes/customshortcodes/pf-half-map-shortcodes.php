<?php
/*
*
* Visual Composer PointFinder Text Seperator Shortcode
*
*/

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

class PointFinderHalfMapShortcode extends WPBakeryShortCode {
	 use PointFinderOptionFunctions,
	  PointFinderCommonFunctions,
	  PointFinderWPMLFunctions,
	  PointFinderReviewFunctions,
	  PointFinderCommonVCFunctions;

    function __construct() {
        //add_action( 'vc_after_init', array( $this, 'pointfinder_single_pfhalfmap_module_mapping' ) );
        add_shortcode( 'pf_directory_half_map', array( $this, 'pointfinder_single_pfhalfmap_module_html' ),10,2 );
    }

    

    public function pointfinder_single_pfhalfmap_module_mapping() {}


    public function pointfinder_single_pfhalfmap_module_html( $atts,$content ) {

		extract( shortcode_atts( array(
			'setup5_mapsettings_height' => 550,
			'setup5_mapsettings_lat' => '0',
			'setup5_mapsettings_lng' => '0',
			'setup5_mapsettings_zoom' => 12,
			'setup5_mapsettings_zoom_mobile' => 10,
			'setup8_pointsettings_limit' => '',
			'setup8_pointsettings_orderby' => '',
			'setup8_pointsettings_order' => '',
			'setup5_mapsettings_autofit' => 0,
			'setup5_mapsettings_autofitsearch' => 0,
			'setup5_mapsettings_type' => 'ROADMAP',
			'setup5_mapsettings_business'=> 0,
			'setup5_mapsettings_streetviewcontrol' => 0,
			'setup5_mapsettings_style' => '',
			'setup7_geolocation_status' => '',
			'mapsearch_status' => 1,
			'mapnot_status' => 1,
			'listingtype' => '',
			'itemtype' => '',
			'conditions' => '',
			'features' => '',
			'locationtype' => '',
			'tag' => '',
			'backgroundmode' => 0,
			'horizontalmode' => 1,
			'content_bg' => '',
			'box_topmargin' => 100,
			'box_leftmargin' => 350,
			'ne' => '',
			'ne2' => '',
			'sw' => '',
			'sw2' => '',
			'neaddress' => '',
			'setup42_mheight' => 350,
			'setup42_theight' => 400,
			'termname' => '',
			'csauto' => '',
			'locofrequest' => 'halfmap'
		), $atts) );

		$hormode = 1;
		$device_check = $this->pointfinder_device_check('isDesktop');

		$general_ct_page_layout = $this->PFSAIssetControl('general_ct_page_layout','','1');
		$general_ct_page_filters = $this->PFSAIssetControl('general_ct_page_filters','','1');
		$setup42_searchpagemap_headeritem = $this->PFSAIssetControl('setup42_searchpagemap_headeritem','','1');

		if (is_search()) {
			$mapsearch_status = 1;
			if ($setup42_searchpagemap_headeritem == 1 || $setup42_searchpagemap_headeritem == 0) {

				$horizontalmode = $hormode = 0;
			}
		}else{
			if ($general_ct_page_layout == '1' || $general_ct_page_layout == '2') {
				if ($general_ct_page_filters != 1 ) {
					$mapsearch_status = 0;
				}else{
					$mapsearch_status = 1;
				}
				$horizontalmode = $hormode = 0;
			}else{
				if ($general_ct_page_filters != 1 ) {
					$mapsearch_status = 0;
				}else{
					$mapsearch_status = 1;
				}
			}
		}

		
		if($mapsearch_status == 1){
		$stp28_mmenu_menulocation = esc_attr(PFSAIssetControl('stp28_mmenu_menulocation','','left'));
			
		ob_start();

		?>
			<div class="psearchdraggable pfhalfmapdraggable <?php echo $locofrequest;?>" data-direction="<?php echo sanitize_text_field( $stp28_mmenu_menulocation );?>">
	        <div id="pfsearch-draggable" class="pfsearch-draggable-window pfsearch-draggable-full pfsearchdrhm ui-widget-content">
	          <?php
	          /**
	          *Start: Search Form style="max-height:<?php echo $setup5_mapsettings_height - 90;?>px;"
	          **/
	          ?>
		          <form id="pointfinder-search-form">
		          	<div class="pfsearch-content golden-forms pfdragcontent" >
			          	<div class="pfsearchformerrors">
			          		<ul></ul>
			           		<a class="button pfsearch-err-button"><i class="fas fa-times"></i> <?php echo esc_html__('CLOSE','pointfindercoreelements')?></a>
			          	</div>
			          	<input type="hidden" name="wherefr" value="halfmapsearch" id="pfwherefr">
			          	<?php if (is_search()): ?>
			          		<input type="hidden" name="s" value="" id="s">
			          		<input type="hidden" name="serialized" value="1" id="serialized">
			          		<input type="hidden" name="action" value="pfs" id="action">
			          	<?php endif ?>
			          	<?php
						$setup1s_slides = $this->PFSAIssetControl('setup1s_slides','','');

						if(is_array($setup1s_slides)){

							if (is_search()) {
								/**
			                    *Start: Get search data & apply to query arguments.
			                    **/

			                        $pfgetdata = $_GET;

			                        if(is_array($pfgetdata)){

			                            $pfformvars2 = array();

			                            foreach ($pfgetdata as $key => $value) {
			                                if (!empty($value) && $value != 'pfs') {
			                                    $pfformvars2[$key] = $value;
			                                }
			                            }

			                            $pfformvars2 = $this->PFCleanArrayAttr('PFCleanFilters',$pfformvars2);

			                        }

			                    /**
			                    *End: Get search data & apply to query arguments.
			                    **/
							}else{
								$pfformvars2 = array();
							}
							$PFListSF = new PF_SF_Val();
							foreach ($setup1s_slides as &$value) {

								$PFListSF->GetValue($value['title'],$value['url'],$value['select'],0,$pfformvars2,$hormode);

							}

							/*Get Listing Type Item Slug*/
		                    $fltf = $this->pointfinder_find_requestedfields('pointfinderltypes');
		                    $features_field = $this->pointfinder_find_requestedfields('pointfinderfeatures');
		                    $itemtypes_field = $this->pointfinder_find_requestedfields('pointfinderitypes');
		                    $conditions_field = $this->pointfinder_find_requestedfields('pointfinderconditions');
		                    $location_field = $this->pointfinder_find_requestedfields('pointfinderlocations');

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
							echo '<div id="pfsearchsubvalues"></div>';
							if ($horizontalmode == 1) {
								echo '<div class="colhorsearch">';
							}
							echo '<a class="button pfsearch pfhfmap-src" id="pf-search-button-halfmap"><i class="fas fa-search"></i> '.esc_html__('FILTER RESULTS', 'pointfindercoreelements').'</a>';
				
							echo '<a class="button pfreset" id="pf-resetfilters-button">'.esc_html__('RESET', 'pointfindercoreelements').'</a>';
							if ($horizontalmode == 1) {
								echo '</div>';
							}

							$scriptdata = '';
							$scriptdata .=  '
							(function($) {
								"use strict";
								$.pffieldsids = '.$second_request_text.'
								$.pfsliderdefaults = {};$.pfsliderdefaults.fields = Array();
								$(function(){';
								$scriptdata .= $PFListSF->ScriptOutput;
								$scriptdata .= 'var pfsearchformerrors = $(".pfsearchformerrors");
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

		                        if ($fltf != 'none') {
		                        	$as_mobile_dropdowns = $this->PFSAIssetControl('as_mobile_dropdowns','','0');

		                        	$scriptdata .= '
										function pfltypechange(){
											if ($("#'.$fltf.'" ).val() !== 0) {
			                                   $.PFGetSubItems($("#'.$fltf.'" ).val(),"",0,'.$hormode.');
			                                   ';
				                               if ($second_request_process) {
				                              	 $scriptdata .= '$.PFRenewFeatures($("#'.$fltf.'").val(),"'.$second_request_text.'");';
				                               }
				                               $scriptdata .= '
			                                }
			                                setTimeout(function(){
			                                	$(".select2-container" ).attr("title","");
			                                	$("#'.$fltf.'" ).attr("title","");
			                                },300);
										}
		                        	';
									if ($as_mobile_dropdowns == 1) {
										$scriptdata .= '
										
										$(function(){
				                            $("body").on("change","#'.$fltf.'",function(e) {

				                              $.PFGetSubItems($("#'.$fltf.'").val(),"",0,'.$hormode.');
				                              ';
				                              if ($second_request_process) {
				                              	$scriptdata .= '$.PFRenewFeatures($("#'.$fltf.'").val(),"'.$second_request_text.'");';
				                              }
				                              $scriptdata .= '
				                            });

				                            $("#'.$fltf.'").on("select2-removed", function (e) {
											    setTimeout(function(){$("#'.$fltf.'").trigger("change");},300);
											});

				                            $(document).one("ready",function(){
				                                pfltypechange();
				                            });
			                            });
			                            ';
									}else{
										$scriptdata .= '
			                            $("body").on("change","#'.$fltf.'",function(e) {
											
			                              $.PFGetSubItems($("#'.$fltf.'" ).val(),"",0,'.$hormode.');
			                              ';
			                              if ($second_request_process) {
			                              	$scriptdata .= '$.PFRenewFeatures($("#'.$fltf.'" ).val(),"'.$second_request_text.'");';
			                              }
			                              $scriptdata .= '
			                            });
			                            $("#'.$fltf.'").on("select2-removed", function (e) {
										    setTimeout(function(){$("#'.$fltf.'").trigger("change");},300);
										});
			                            $(document).one("ready",function(){
			                                pfltypechange();
			                            });

			                            ';
									}

		                        }
		                        $scriptdata .= '$.pfmapafterworks();';
		                    	$scriptdata .= '});';
								$scriptdata .= $PFListSF->ScriptOutputDocReady;
							}
							$scriptdata .= '

							})(jQuery);
							';

							wp_add_inline_script('theme-categorymapjs',$scriptdata,'after');
							unset($PFListSF);
					  ?>
		            </div>
		          </form><!-- // pointfinder-search-form close-->
	          <?php
	          /**
	          *End: Search Form
	          **/
	          ?>


	        </div>
	        </div>
	   		 <!--  / Search Container -->
		<?php
		$map_search_content = ob_get_contents();
		ob_end_clean();
		}


		
			
		
		if (is_search()) {
			$stpmappos = $this->PFSAIssetControl('stpmappos_src','','2');
		}else{
			$stpmappos = $this->PFSAIssetControl('stpmappos_cat','','2');
		}

		$stp5_mapty = $this->PFSAIssetControl('stp5_mapty','',1);
		if (is_search()) {
			$setup42_mheight = $this->PFSAIssetControl('setup42_mheight','height','350');
			$setup42_mheight = str_replace('px', '', $setup42_mheight);
			$setup42_theight = $this->PFSAIssetControl('setup42_theight','height','400');
			$setup42_theight = str_replace('px', '', $setup42_theight);
		}else{
			$setup42_mheight = $this->PFSAIssetControl('setup56_mheight','height','350');
			$setup42_mheight = str_replace('px', '', $setup42_mheight);
			$setup42_theight = $this->PFSAIssetControl('setup56_theight','height','400');
			$setup42_theight = str_replace('px', '', $setup42_theight);
		}

		$we_special_key = $wemap_here_appid = $wemap_here_appcode = '';


		
		$setup7_geolocation_distance = $this->PFSAIssetControl('setup7_geolocation_distance','',10);
		$setup7_geolocation_distance_unit = $this->PFSAIssetControl('setup7_geolocation_distance_unit','',"km");
		$setup7_geolocation_hideinfo = $this->PFSAIssetControl('setup7_geolocation_hideinfo','',1);
		$setup6_clustersettings_status = $this->PFSAIssetControl('setup6_clustersettings_status','',1);
		$stp6_crad = $this->PFSAIssetControl('stp6_crad','',100);
		$setup10_infowindow_height = $this->PFSAIssetControl('setup10_infowindow_height','','136');
		$setup10_infowindow_width = $this->PFSAIssetControl('setup10_infowindow_width','','350');
		$s10_iw_w_m = $this->PFSAIssetControl('s10_iw_w_m','','184');
		$s10_iw_h_m = $this->PFSAIssetControl('s10_iw_h_m','','136');

		ob_start();

		echo '<section role="main" class="clearfix">';
			    
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
		
		 
		$css_classes = 'pf-'.$locofrequest.'-list-container';

		if ($locofrequest == 'halfmap') {
			if ($stpmappos == '2') {
				$css_classes .= ' pull-right';
			}else{
				$css_classes .= ' pull-left';
				$css_classes .= ' pfplctr';
			}
		}

		?>

		<div id="pf<?php echo $locofrequest;?>mapcontainer" class="<?php echo $css_classes;?>">
			<div id="wpf-map-container">

		    	<div class="pfmaploading pfloadingimg"></div>
				
		    	<div id="wpf-map"
		    	data-mode="<?php echo $locofrequest;?>" 
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
				></div>

		    	
		    </div>
		</div>
		

		<?php 

		$css_classes = 'pf-'.$locofrequest.'-list-container';

		if ($locofrequest == 'halfmap') {
			if ($stpmappos == '2') {
				$css_classes .= ' pull-left';
			}else{
				$css_classes .= ' pull-right';
			}
			
		}else{
			$css_classes .= ' pf-row';
		}
		
		if ($locofrequest != 'halfmap' && !is_search()) {
			$setup_item_catpage_sidebarpos = $this->PFSAIssetControl('setup_item_catpage_sidebarpos','','2');
			
	        echo '<div class="pf-page-spacing"></div>';
	        echo '<div class="pf-container"><div class="pf-row clearfix">';
	        if ($setup_item_catpage_sidebarpos == '3') {
	        	echo '<div class="col-lg-12">';
	        	if ($general_ct_page_layout == '1' || $general_ct_page_layout == '2') {
					if($mapsearch_status == 1 && $device_check == true){	
						echo $map_search_content;
					}
				}
	        }else{

		        if($setup_item_catpage_sidebarpos == '1'){
	                echo '<div class="col-lg-3 col-md-4">';
		                if ($general_ct_page_layout == '1' || $general_ct_page_layout == '2') {
							if($mapsearch_status == 1 && $device_check == true){	
								echo $map_search_content;
							}
						}
	                    get_sidebar('itemcats' ); 
	                echo '</div>';

	                echo '<div class="col-lg-9 col-md-8">';
	            }elseif ($setup_item_catpage_sidebarpos == '2') {
	            	echo '<div class="col-lg-9 col-md-8">';
	            }

	        }
		}elseif ($locofrequest != 'halfmap' && is_search()) {
			$setup_item_searchresults_sidebarpos = $this->PFSAIssetControl('setup_item_searchresults_sidebarpos','','2');
	        echo '<div class="pf-page-spacing"></div>';
	        echo '<div class="pf-container"><div class="pf-row clearfix">';
	        if ($setup_item_searchresults_sidebarpos == '3') {
	        	echo '<div class="col-lg-12">';
	        	if ($setup42_searchpagemap_headeritem == 1 || $setup42_searchpagemap_headeritem == 0) {
					if($mapsearch_status == 1 && $device_check == true){	
						echo $map_search_content;
					}
				}
	        }else{

		        if($setup_item_searchresults_sidebarpos == '1'){
	                echo '<div class="col-lg-3 col-md-4">';
		                if ($setup42_searchpagemap_headeritem == 1 || $setup42_searchpagemap_headeritem == 0) {
							if($mapsearch_status == 1 && $device_check == true){	
								echo $map_search_content;
							}
						}
	                    get_sidebar('itemsearchres' ); 
	                echo '</div>';

	                echo '<div class="col-lg-9 col-md-8">';
	            }elseif ($setup_item_searchresults_sidebarpos == '2') {
	            	echo '<div class="col-lg-9 col-md-8">';
	            }

	        }
		}

		if($mapsearch_status == 1 && $device_check == false){	
			echo '<div class="pfmobilecheck">';
			echo $map_search_content;
			echo '</div>';
		}
		
		


		?>
		<div class="<?php echo $css_classes;?>">

			<?php
			$tax_keyword = '';
			if (is_search()) {

				echo '<div class="pf_pageh_title pf_separator_align_left">';   
		        echo '<div class="pf_pageh_title_inner clearfix"><span class="pf-results-for">'.esc_html__( "Search Results", "pointfindercoreelements" ).'</span> <span class="pffoundpp"></span></div>';
				echo '</div>';

			}else{
				global $wp_query;
				if (isset($wp_query->query_vars['taxonomy'])) {
					switch ($wp_query->query_vars['taxonomy']) {
						case 'pointfinderltypes':
							$setup3_pointposttype_pt7s = $this->PFSAIssetControl('setup3_pointposttype_pt7s','','Listing Type');
							$tax_keyword = $setup3_pointposttype_pt7s.': ';
							break;
						
						case 'pointfinderitypes':
							$setup3_pointposttype_pt4s = $this->PFSAIssetControl('setup3_pointposttype_pt4s','','Item Type');
							$tax_keyword = $setup3_pointposttype_pt4s.': ';
							break;

						case 'pointfinderconditions':
						 	$setup3_pt14s = $this->PFSAIssetControl('setup3_pt14s','','Condition');
							$tax_keyword = $setup3_pt14s.': ';
							break;

						case 'pointfinderlocations':
							$setup3_pointposttype_pt5s = $this->PFSAIssetControl('setup3_pointposttype_pt5s','','Location');
							$tax_keyword = $setup3_pointposttype_pt5s.': ';
							break;

						case 'pointfinderfeatures':
							$setup3_pointposttype_pt6s = $this->PFSAIssetControl('setup3_pointposttype_pt6s','','Feature');
							$tax_keyword = $setup3_pointposttype_pt6s.': ';
							break;
					}
					
				}elseif(is_tag()){
					$tax_keyword = esc_html__( "Tag","pointfindercoreelements" ).': ';
				}
				echo '<div class="pf_pageh_title pf_separator_align_left">';   
		        echo '<div class="pf_pageh_title_inner clearfix"><span class="pf-results-for">'.$tax_keyword.' '.$termname.'</span> <span class="pffoundpp"></span></div>';
				echo '</div>';

			}
			

			if($mapsearch_status == 1 && $device_check != false){
				if ($general_ct_page_layout != '1' && $general_ct_page_layout != '2' && !is_search()) {
					echo $map_search_content;
				}elseif($setup42_searchpagemap_headeritem != 0 && $setup42_searchpagemap_headeritem != 1 && is_search()){
					echo $map_search_content;
				}
			}else{
				$fltf = $this->pointfinder_find_requestedfields('pointfinderltypes');
                $features_field = $this->pointfinder_find_requestedfields('pointfinderfeatures');
                $itemtypes_field = $this->pointfinder_find_requestedfields('pointfinderitypes');
                $conditions_field = $this->pointfinder_find_requestedfields('pointfinderconditions');
                $location_field = $this->pointfinder_find_requestedfields('pointfinderlocations');
			}?>
			<?php
			if ($general_ct_page_layout != '1' && $general_ct_page_layout != '2' && !is_search()) {
				if($mapsearch_status ==1 && $device_check != false){
					echo '<hr/>';
				}
			}
			?>
			<div class="pf-page-container">
				<?php
					$serialized_sdata = $data_values = $fltf_get = '';

					$coordval = (isset($_GET['pointfinder_google_search_coord']))?esc_attr($_GET['pointfinder_google_search_coord']):'';
					
					if(isset($_GET['serialized']) || isset($_GET['wherefr'])){
						$serialized_sdata = json_encode($_GET, JSON_HEX_QUOT|JSON_HEX_APOS);
					}

					$data_values .= " data-sdata='".$serialized_sdata."'";	

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
					$data_values .= ' data-tag="'.$tag.'"';
					$data_values .= ' data-csauto="'.$csauto.'"';
					$data_values .= ' data-fltf="'.$fltf.'"';
					$data_values .= ' data-floct="'.$location_field.'"';
					$data_values .= ' data-coordval="'.$coordval.'"';

					if (isset($_GET[$fltf])) {
                		$fltf_get = intval($_GET[$fltf]);
                	}

					$data_values .= ' data-fltfget="'.$fltf_get.'"';
				?>
				<div class="pfsearchresults-container" <?php echo $data_values;?>></div>
			</div>
		</div>
		<?php
		if ($locofrequest != 'halfmap' && !is_search()) {

	        
	        echo '</div>';//column
	        if($setup_item_catpage_sidebarpos == '2'){
                echo '<div class="col-lg-3 col-md-4">';
	                if ($general_ct_page_layout == '1' || $general_ct_page_layout == '2') {
						if($mapsearch_status == 1 && $device_check == true){	
							echo $map_search_content;
						}
					}
                    get_sidebar('itemcats' );
                echo '</div>';
            }
	        echo '</div></div>';
		}elseif ($locofrequest != 'halfmap' && is_search()){
			 echo '</div>';//column
	        if($setup_item_searchresults_sidebarpos == '2'){
                echo '<div class="col-lg-3 col-md-4">';
	                if ($setup42_searchpagemap_headeritem == 1 || $setup42_searchpagemap_headeritem == 0) {
						if($mapsearch_status == 1 && $device_check == true){	
							echo $map_search_content;
						}
					}
                    get_sidebar('itemcats' );
                echo '</div>';
            }
	        echo '</div></div>';
		}
		?>
		<?php
		echo '</section>';

		$output = ob_get_contents();
		ob_end_clean();
		return $output;

    }

}
new PointFinderHalfMapShortcode();

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_pointfinder_single_pfhalfmap extends WPBakeryShortCode {
    }
}