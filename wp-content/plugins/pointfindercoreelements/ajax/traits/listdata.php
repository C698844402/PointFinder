<?php 
if (class_exists('PointFinderListData')) {
	return;
}

class PointFinderListData extends Pointfindercoreelements_AJAX
{
	use PointFinderGridSpecificFunctions;

    public function __construct(){}
	
	private function pointfinder_featured_image_getresized_special($pfitemid,$template_directory_uri,$general_crop2,$general_retinasupport,$setupsizelimitconf_general_gridsize1_width,$setupsizelimitconf_general_gridsize1_height){
		$noimg_url = PFCOREELEMENTSURLPUBLIC.'images/noimg.png';
		$featured_image = '';
		$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $pfitemid ), 'full' );

		$featured_image_original = $featured_image[0];

		if($general_retinasupport == 1){$pf_retnumber = 2;}else{$pf_retnumber = 1;}
		$featured_image_width = $setupsizelimitconf_general_gridsize1_width*$pf_retnumber;
		$featured_image_height = $setupsizelimitconf_general_gridsize1_height*$pf_retnumber;

		if(!empty($featured_image[0])){

			switch ($general_crop2) {
				case 1:
					$featured_image_output = pointfinder_aq_resize($featured_image[0],$featured_image_width,$featured_image_height,true,true,true);
					break;
				case 2:

					$featured_image_output = pointfinder_aq_resize($featured_image[0],$featured_image_width,$featured_image_height,true);

					if($featured_image_output === false) {
						if($general_retinasupport == 1){
							$featured_image_output = pointfinder_aq_resize($featured_image[0],$featured_image_width/2,$featured_image_height/2,true);
							if($featured_image_output === false) {
								$featured_image_output = $featured_image_original;
								if($featured_image_output == '') {
									$featured_image_output = $noimg_url;
								}
							}
						}else{
							$featured_image_output = pointfinder_aq_resize($featured_image[0],$featured_image_width/2,$featured_image_height/2,true);
							if ($featured_image_output === false) {
								$featured_image_output = pointfinder_aq_resize($featured_image[0],$featured_image_width/4,$featured_image_height/4,true);
								if ($featured_image_output === false) {
									$featured_image_output = $featured_image_original;
									if($featured_image_output == '') {
										$featured_image_output = $noimg_url;
									}
								}
							}

							$featured_image_output = $featured_image_original;
							if($featured_image_output == '') {
								$featured_image_output = $noimg_url;
							}
						}

					}
					break;

				case 3:
					$featured_image_output = $featured_image_original;
					break;
			}

		}else{
			$featured_image_output = $noimg_url;
		}

		return array('featured_image' => $featured_image_output,'featured_image_org' => $featured_image_original);

	}

    public function pf_ajax_list_items(){
		check_ajax_referer( 'pfget_listitems', 'security' );
		header('Content-Type: text/html; charset=UTF-8;');

			/* Defaults */
			$wpflistdata = $pfg_orderby_orj = $wpflistdata_output = $pfaction = $pfgrid = $pfg_ltype = $pfg_itype = $pfg_lotype = $pfheaderfilters = $pfitemboxbg = $pfcontainershow = $pfcontainerdiv = $pfgrid = $pflang = $pfgetdata =  $pf_from = $pflatp = $pflngp = $pfg_orderby = $pfg_orderby_original = $pfg_order = $pfg_number = $pfg_paged = $ohours = $taxonomy_name = $found_keyword = '';
			$pfg_authormode = $pfg_agentmode = $setup22_searchresults_status_sortby = $setup22_searchresults_status_ascdesc = $setup22_searchresults_status_number = $setup22_searchresults_status_2col = $setup22_searchresults_status_3col = $setup22_searchresults_status_4col = $setup22_searchresults_status_2colh = 0;
			$user_loggedin_check = is_user_logged_in();
			
			$debug = 0;
			

			/* Get admin values */
			$setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
			
			/* Define Coordinates */
				if(isset($_POST['ne']) && $_POST['ne']!=''){$ne = esc_attr($_POST['ne']);}else{$ne = 360;}
				if(isset($_POST['ne2']) && $_POST['ne2']!=''){$ne2 = esc_attr($_POST['ne2']);}else{$ne2 = 360;}
				if(isset($_POST['sw']) && $_POST['sw']!=''){$sw = esc_attr($_POST['sw']);}else{$sw = -360;}
				if(isset($_POST['sw2']) && $_POST['sw2']!=''){$sw2 = esc_attr($_POST['sw2']);}else{$sw2 = -360;}

			/* Distance Detection */
				if(isset($_POST['pflat']) && $_POST['pflat']!=''){
					$pflatp = floatval($_POST['pflat']);
				}
				if(isset($_POST['pflng']) && $_POST['pflng']!=''){
					$pflngp = floatval($_POST['pflng']);
				}


			/* Half Page Map Detection */
				if(isset($_POST['from']) && $_POST['from']!=''){
					$pf_from = sanitize_text_field($_POST['from']);
				}
				if (empty($pf_from)) {
					if(isset($_POST['pfsearchfilterfrom']) && $_POST['pfsearchfilterfrom']!=''){
						$pf_from = sanitize_text_field($_POST['pfsearchfilterfrom']);
					}
				}


			/* Opening Hours */
				if(isset($_POST['ohours']) && $_POST['ohours']!=''){
					$ohours = sanitize_text_field($_POST['ohours']);
				}



			/* WPML - Current language fix */
				if(isset($_POST['cl']) && $_POST['cl']!=''){
					$pflang = esc_attr($_POST['cl']);
					if(class_exists('SitePress')) {
						if (!empty($pflang)) {
							do_action( 'wpml_switch_language', $pflang );
						}
					}
				}


			/* Get Grid Layout Mode */
				$setup22_searchresults_grid_layout_mode = $this->PFSAIssetControl('setup22_searchresults_grid_layout_mode','','1');
				$grid_layout_mode = ($setup22_searchresults_grid_layout_mode == 1) ? 'fitRows' : 'masonry' ;

			/* Search form check */
				if(isset($_POST['act']) && $_POST['act']!=''){
					$pfaction = esc_attr($_POST['act']);
				}

			/* Grid random number (id) */
				if(isset($_POST['gdt']) && $_POST['gdt']!=''){
					$variables_gdt = $_POST['gdt'];
					$pfaction = 'grid';
					$pfgetdata = $variables_gdt;
				}
				
			if(isset($_POST['dt']) && $_POST['dt']!=''){
				$pfgetdata = $_POST['dt'];
			}

			/* Get default Grid settings from admin */
				$setup22_searchresults_defaultppptype = $this->PFSAIssetControl('setup22_searchresults_defaultppptype','','10');
				$setup22_searchresults_defaultsortbytype = $this->PFSAIssetControl('setup22_searchresults_defaultsortbytype','','date');
				$setup22_searchresults_defaultsorttype = 'DESC';
				$setup22_searchresults_defaultlistingtype = $this->PFSAIssetControl('setup22_searchresults_defaultlistingtype','','4');
				$review_system_statuscheck = $this->PFREVSIssetControl('setup11_reviewsystem_check','','0');
				$setup16_reviewstars_revtextbefore = $this->PFREVSIssetControl('setup16_reviewstars_revtextbefore','','');
				$st22srlinknw = $this->PFSAIssetControl('st22srlinknw','','0');
				$setup3_pt14_check = $this->PFSAIssetControl('setup3_pt14_check','',0);
				$setup22_searchresults_hide_lt  = $this->PFSAIssetControl('setup22_searchresults_hide_lt','','0');

				$targetforitem = '';
				if ($st22srlinknw == 1) {
					$targetforitem = ' target="_blank"';
				}


				$template_directory_uri = get_template_directory_uri();
				$general_crop2 = $this->PFSizeSIssetControl('general_crop2','',1);

			/* Post Type status check */
				$setup3_pointposttype_pt4_check = $this->PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
				$setup3_pointposttype_pt5_check = $this->PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
				$setup3_pointposttype_pt6_check = $this->PFSAIssetControl('setup3_pointposttype_pt6_check','','1');

			/* Container & show check */
				if(isset($_POST['pfcontainerdiv']) && $_POST['pfcontainerdiv']!=''){
					$pfcontainerdiv = str_replace('.', '', esc_attr($_POST['pfcontainerdiv']));
				}
				if(isset($_POST['pfcontainershow']) && $_POST['pfcontainershow']!=''){
					$pfcontainershow = str_replace('.', '', esc_attr($_POST['pfcontainershow']));
					if (isset($_POST['pfex']) && !empty($_POST['pfex'])) {$pfcontainershow .= ' pfajaxgridview';}
				}

				$setup22_searchresults_status_sortby = $this->PFSAIssetControl('setup22_searchresults_status_sortby','','0');

				if ($pfcontainerdiv == 'pfsearchresults') {

					$setup22_searchresults_status_ascdesc = 1;
					$setup22_searchresults_status_number = $this->PFSAIssetControl('setup22_searchresults_status_number','','0');
					$setup22_searchresults_status_2col = $this->PFSAIssetControl('setup22_searchresults_status_2col','','0');
					$setup22_searchresults_status_3col = $this->PFSAIssetControl('setup22_searchresults_status_3col','','0');
					$setup22_searchresults_status_4col = $this->PFSAIssetControl('setup22_searchresults_status_4col','','0');
					$setup22_searchresults_status_2colh = $this->PFSAIssetControl('setup22_searchresults_status_2colh','','0');
				}

			/* Grid type for HTML strings */

				if(isset($_POST['grid']) && $_POST['grid']!=''){
					$pfgrid = esc_attr($_POST['grid']);
				}
				

			/* Settings for Retina Feature */
				$general_retinasupport = $this->PFSAIssetControl('general_retinasupport','','0');
				$setupsizelimitconf_general_gridsize1_width = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize1','width',440);
				$setupsizelimitconf_general_gridsize1_height = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize1','height',330);
			

			/* Get if sort/order/number values exist */
				if(isset($_POST['pfg_orderby']) && $_POST['pfg_orderby']!=''){
					$pfg_orderby = $pfg_orderby_orj = $_POST['pfg_orderby'];
				}
					

				if(!empty($pfg_orderby)){
					$pfg_orderby = sanitize_text_field($pfg_orderby);
					$pfg_orderby_original = $pfg_orderby;
					$sortby_function_result = $this->pointfinder_sortby_selector($pfg_orderby);
					
					$pfg_orderby = $sortby_function_result[0];
					$pfg_order = $sortby_function_result[1];
				}elseif (empty($pfg_orderby && $pfaction != 'grid')) {
					$sortby_function_result = $this->pointfinder_sortby_selector($setup22_searchresults_defaultsortbytype);
					
					$pfg_orderby = $sortby_function_result[0];
					$pfg_order = $sortby_function_result[1];

				}elseif (empty($pfg_orderby && $pfaction == 'grid')) {

					$sortby_function_result = $this->pointfinder_sortby_selector($setup22_searchresults_defaultsortbytype);

					if (isset($pfgetdata['orderby'])) {
						if (empty($pfgetdata['orderby'])) {
							if (isset($pfgetdata['pfrandomize']) && $pfgetdata['pfrandomize'] == 'yes') {
								$sortby_function_result = $this->pointfinder_sortby_selector('rand');
							}else{
								$sortby_function_result = $this->pointfinder_sortby_selector($pfgetdata['orderby']);
							}
						}
					}
					
					$pfg_orderby = $sortby_function_result[0];
					$pfg_order = $sortby_function_result[1];
				}
				
				if(isset($_POST['pfg_number'])){
					$pfg_number = absint($_POST['pfg_number']);
				}

				if(isset($_POST['page'])){
					$pfg_paged = absint($_POST['page']);
				}


				if (strpos(esc_url($_SERVER['HTTP_REFERER']),esc_url(home_url("/"))) == 0) {
					$new_strings = '';
					$get_strings = parse_url($_SERVER['HTTP_REFERER'],PHP_URL_QUERY);
					parse_str($get_strings,$new_strings);
					$pfg_orderby_orj = '';
					if(isset($_POST['pfg_orderby']) && $_POST['pfg_orderby']!=''){
						$pfg_orderby_orj = $pfg_orderby_orj = $_POST['pfg_orderby'];
					}

					if (is_array($new_strings)) {
						if (isset($new_strings['grid']) && empty($pfgrid)) {
							$pfgrid = sanitize_text_field( $new_strings['grid'] );
						}

						if (isset($new_strings['pfg_orderby']) && empty($pfg_orderby_orj)) {
							$pfg_orderby = sanitize_text_field( $new_strings['pfg_orderby'] );
							$pfg_orderby_original = $pfg_orderby;

							$sortby_function_result = $this->pointfinder_sortby_selector($pfg_orderby);
					
							$pfg_orderby = $sortby_function_result[0];
							$pfg_order = $sortby_function_result[1];
						}

						if (isset($new_strings['pfg_number']) && empty($pfg_number)) {
							$pfg_number = absint( $new_strings['pfg_number'] );
						}

						if (isset($new_strings['page']) && empty($pfg_paged)) {
							$pfg_paged = absint( $new_strings['page'] );
						}


						if (isset($new_strings['from'])) {
							$pf_from = sanitize_text_field( $new_strings['from'] );
						}
						
					}
				}
				

				if ($pf_from == 'halfmap' || $pf_from == 'topmap') {
					if ($pfaction == 'search') {
						if ($pf_from == 'topmap') {
							$setup22_dlcfcx = $this->PFSAIssetControl('setup22_dlcfc','','3');
						}else{
							$setup22_dlcfcx = $this->PFSAIssetControl('setup42_dlcfcx','','2');
						}
					} else {

						if ($pf_from == 'topmap') {
							$setup22_dlcfcx = $this->PFSAIssetControl('setup22_dlcfc','','3');
						}else{
							$setup22_dlcfcx = $this->PFSAIssetControl('setup22_dlcfcx','','2');
						}
					}
					if (!empty($setup22_dlcfcx)) {
						$setup22_searchresults_defaultlistingtype = $setup22_dlcfcx;
					}
				}

			/* Start: Create arguments for get post */
				$args = array( 'post_type' => $setup3_pointposttype_pt1, 'post_status' => 'publish');

				if ($ohours == 'active') {
					$args['ohourshow'] = 'active';
				}


				if(!empty($pflatp) && !empty($pflngp)){
					$args['geo_query'] = array(
				        'lat_field' => 'webbupointfinder_items_location',
				        'lng_field' => 'webbupointfinder_items_location',
				        'latitude'  => $pflatp,
				        'longitude' => $pflngp,
				        'distance'  => 100000,
				        'units'     => "miles"
	            	);
				}

				/* Main Order Filters */
				$setup31_userpayments_featuredoffer = $this->PFSAIssetControl('setup31_userpayments_featuredoffer','','1');
				$setup22_featrand = $this->PFSAIssetControl('setup22_featrand','','0');
				$setup22_feated = $this->PFSAIssetControl('setup22_feated','','0');

				$featured_process = true;

				if ($setup22_feated == 1 && !empty($pfg_orderby_orj) ) {
					$featured_process = false;
				}

				/* Start: Featured Filter*/
					if ($setup31_userpayments_featuredoffer == 1 && $featured_process == true) {
						if ($setup22_featrand == 1) {
							$args['orderby']['query_featuredor'] = 'rand';
						}else{
							$args['orderby']['query_featuredor'] = 'DESC';
						}
						
						$args['meta_query']['query_featured'] = array(
							'relation' => 'OR',
						 	'query_featurednx' => array(
								'key' => 'webbupointfinder_item_featuredmarker',
								'compare'=>'NOT EXISTS',
								'value'=> 'completely'
							),
							'query_featuredor'=>array(
								'key' => 'webbupointfinder_item_featuredmarker',
								'type' => 'NUMERIC'
							)
						);
					}
				/* End: Featured Filter*/

				/* Start: Order Filters*/
					if(!empty($pfg_orderby)){
						if ($pfg_orderby == 'ID') {$pfg_orderby = 'date';}
						
						if($pfg_orderby == 'date' || $pfg_orderby == 'title'){
							$args['orderby'][$pfg_orderby] = $pfg_order;
						}elseif($pfg_orderby == 'rand'){
							$args['orderby']['rand'] = '';
						}elseif($pfg_orderby == 'mviewed'){
							$args['orderby']['query_key'] = 'DESC';
							$args['meta_query']['query_key'] = array('key' => 'webbupointfinder_page_itemvisitcount','type'=>'NUMERIC');
						}elseif($pfg_orderby == 'mreviewed'){
							$args['orderby']['query_key'] = 'DESC';
							$args['meta_query']['query_key'] = array('key' => 'webbupointfinder_page_itemvisitcount','type'=>'NUMERIC');
						}elseif($pfg_orderby == 'reviewcount'){
							if (class_exists('Pointfinderspecialreview_Public')) {
							 	$args['orderby']['query_review'] = $pfg_order;
								$args['meta_query']['query_review'] = array('key' => 'pfreviewx_totalperitem','type'=>'DECIMAL(2,1)');
							 }else{
							 	$args['orderby']['query_reviewor'] = $pfg_order;
								$args['meta_query']['query_review'] = array(
									'relation' => 'OR',
									'query_reviewnx'=>array(
										'key' => 'webbupointfinder_item_reviewcount',
										'compare'=>'NOT EXISTS',
										'value'=> 'completely'
									),
									'query_reviewor'=>array(
										'key' => 'webbupointfinder_item_reviewcount',
										'type'=>'DECIMAL(2,1)',
									)
								);
							 }
						}elseif($pfg_orderby == 'distance'){
							if (!empty($pflatp) && !empty($pflngp)) {
								$args['orderby']['distance'] = $pfg_order;
							}
						}else{
							$pfg_orderby_exp = explode('|',$pfg_orderby);

							$pfg_order = 'DESC';

		                    if (count($pfg_orderby_exp) == 2) {
		                        $pfg_orderby = $pfg_orderby_exp[0];
		                        $pfg_order = $pfg_orderby_exp[1];
		                    }

							if($this->PFIF_CheckFieldisNumeric_ld($pfg_orderby) == false){
								$args['orderby']['query_key']= $pfg_order;
								$args['meta_query']['query_key'] = array('key' => 'webbupointfinder_item_'.$pfg_orderby,'type'=>'CHAR');
							}else{
								$args['orderby']['query_key']= $pfg_order;
								$args['meta_query']['query_key'] = array('key' => 'webbupointfinder_item_'.$pfg_orderby,'type'=>'NUMERIC');
							}
						}
					}
				/* End: Order Filters*/
			
			
				/* Page number / post per page values */
				if($pfg_number != ''){$args['posts_per_page'] = $pfg_number;}else{$args['posts_per_page'] = $setup22_searchresults_defaultppptype;}
				if($pfg_paged != ''){$args['paged'] = $pfg_paged;}
				
				if(isset($args['meta_query']) == false || isset($args['meta_query']) == NULL){
					$args['meta_query'] = array();
				}	

				if(isset($args['tax_query']) == false || isset($args['tax_query']) == NULL){
					$args['tax_query'] = array();
				}



				if($pfaction == 'search'){
					/*
					* If query is a search result
					*/

						if (!is_array($pfgetdata)) {
							$pfgetdata = json_decode($pfgetdata);

							if (is_array($pfgetdata)) {
								foreach ($pfgetdata as $key => $value) {
									$pfnewgetdata[] = array('name' => $key, 'value'=>$value);
								}
								$pfgetdata = $pfnewgetdata;
							}
						}
					
						if(is_array($pfgetdata)){

							$pfformvars = array();
								
								if (isset($pfgetdata[0]['name'])) {
									
									foreach($pfgetdata as $singledata){

										if(!empty($singledata['value'])){
											
											if(isset($pfformvars[$singledata['name']])){
												$pfformvars[$singledata['name']] = $pfformvars[$singledata['name']]. ',' .$singledata['value'];
											}else{
												$pfformvars[$singledata['name']] = $singledata['value'];
											}
					
										}
									
									}

									$pfsearchvars = $pfformvars;
								}else{
									$pfsearchvars = $pfgetdata;
								}

								/* Added with v1.8.8 */
								/* Define Coordinates */
								if (isset($pfsearchvars['ne'])) {
									if(isset($pfsearchvars['ne'])){$ne = esc_attr($pfsearchvars['ne']);}else{$ne = 360;}
									if(isset($pfsearchvars['ne2'])){$ne2 = esc_attr($pfsearchvars['ne2']);}else{$ne2 = 360;}
									if(isset($pfsearchvars['sw'])){$sw = esc_attr($pfsearchvars['sw']);}else{$sw = -360;}
									if(isset($pfsearchvars['sw2'])){$sw2 = esc_attr($pfsearchvars['sw2']);}else{$sw2 = -360;}
								} else{
									if(isset($_POST['ne']) && $_POST['ne']!=''){$ne = esc_attr($_POST['ne']);}else{$ne = 360;}
									if(isset($_POST['ne2']) && $_POST['ne2']!=''){$ne2 = esc_attr($_POST['ne2']);}else{$ne2 = 360;}
									if(isset($_POST['sw']) && $_POST['sw']!=''){$sw = esc_attr($_POST['sw']);}else{$sw = -360;}
									if(isset($_POST['sw2']) && $_POST['sw2']!=''){$sw2 = esc_attr($_POST['sw2']);}else{$sw2 = -360;}
								}

								if(isset($pfsearchvars['page']) && empty($args['paged'])){
									$args['paged'] = $pfsearchvars['page'];
									$pfg_paged = $pfsearchvars['page'];
								}

								/* Added with v1.8.7 */
								$pf_query_builder = new PointfinderSearchQueryBuilder($args);
								$pf_query_builder->setQueryValues($pfsearchvars,'listdata',array());
								$args = $pf_query_builder->getQuery();	

						}
				}else if( $pfaction == 'grid'){
					/*
					* If query is a Ajax Grid
					*/

					$grid_layout_mode = $pfgetdata['grid_layout_mode'];

						if(is_array($pfgetdata)){
							if ($pfgetdata['related'] == 1) {

								if(!empty($pfgetdata['relatedcpi'])){$args['post__not_in'] = array($pfgetdata['relatedcpi']);}

								$agent_id = redux_post_meta("pointfinderthemefmb_options", $pfgetdata['relatedcpi'], "webbupointfinder_item_agents");

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

							$pfg_authormode = $pfgetdata['authormode'];
							$pfg_agentmode = $pfgetdata['agentmode'];

							if($pfgetdata['posts_in']!=''){
								$args['post__in'] = $this->pfstring2BasicArray($pfgetdata['posts_in']);
							}

							if($pfgetdata['authormode'] != 0){
								if (!empty($pfgetdata['author'])) {
									$args['author'] = $pfgetdata['author'];
								}
							}

							/* Listing type */
								if($pfgetdata['listingtype'] != ''){
									$pfvalue_arr_lt = PFGetArrayValues_ld($pfgetdata['listingtype']);
									$fieldtaxname_lt = 'pointfinderltypes';
									$args['tax_query'][] = array(
										'taxonomy' => $fieldtaxname_lt,
										'field' => 'id',
										'terms' => $pfvalue_arr_lt,
										'operator' => 'IN'
									);
								}

							/* Location type */
								if($setup3_pointposttype_pt5_check == 1){
									if($pfgetdata['locationtype'] != ''){
										$pfvalue_arr_loc = PFGetArrayValues_ld($pfgetdata['locationtype']);
										$fieldtaxname_loc = 'pointfinderlocations';
										$args['tax_query'][] = array(
												'taxonomy' => $fieldtaxname_loc,
												'field' => 'id',
												'terms' => $pfvalue_arr_loc,
												'operator' => 'IN'
										);
									}
								}

							/* Item type */
								if($setup3_pointposttype_pt4_check == 1){
									if($pfgetdata['itemtype'] != ''){
										$pfvalue_arr_it = PFGetArrayValues_ld($pfgetdata['itemtype']);
										$fieldtaxname_it = 'pointfinderitypes';
										$args['tax_query'][] = array(
												'taxonomy' => $fieldtaxname_it,
												'field' => 'id',
												'terms' => $pfvalue_arr_it,
												'operator' => 'IN'
										);
									}
								}

							/* Condition */
								$setup3_pt14_check = $this->PFSAIssetControl('setup3_pt14_check','',0);
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

							/* Features type */
								if($setup3_pointposttype_pt6_check == 1){
									if($pfgetdata['features'] != ''){
										$pfvalue_arr_fe = PFGetArrayValues_ld($pfgetdata['features']);
										$fieldtaxname_fe = 'pointfinderfeatures';
										$args['tax_query'][] = array(
												'taxonomy' => $fieldtaxname_fe,
												'field' => 'id',
												'terms' => $pfvalue_arr_fe,
												'operator' => 'IN'
										);
									}
								}


							$pfitemboxbg = ' style="background-color:'.$pfgetdata['itemboxbg'].';"';
							$pfheaderfilters = ($pfgetdata['filters']=='true') ? '' : 'false' ;

							if($pfgetdata['cols'] != '' && empty($pfgrid)){$pfgrid = 'grid'.$pfgetdata['cols'];}

							
							if($pfg_number != ''){
								$args['posts_per_page'] = $pfg_number;
							}else{
								if($pfgetdata['items'] != ''){
									$args['posts_per_page'] = $pfgetdata['items'];
								}else{
									$args['posts_per_page'] = $setup22_searchresults_defaultppptype;
								}
							}
							
							if($pfg_paged != ''){$args['paged'] = $pfg_paged;}



							/* Show only Featured items filter */
								if($pfgetdata['featureditems'] == 'yes' && $pfgetdata['featureditemshide'] != 'yes'){
									$args['meta_query']['query_featuredor'] = array(
										'key' => 'webbupointfinder_item_featuredmarker',
										'value' => 1,
										'compare' => '=',
										'type' => 'NUMERIC'
									);
								}

							/* Hide Featured items filter */
								if ($pfgetdata['featureditemshide'] == 'yes') {
									$args['meta_query']['query_featuredor'] = array(
										'key' => 'webbupointfinder_item_featuredmarker',
										'value' => 0,
										'compare' => '=',
										'type' => 'NUMERIC'
									);
								}
						}
				}else{
					/*
					* If query is a map search list grid
					*/
					$pfsearchvars = array();
					if(isset($_POST['dtx']) && !empty($_POST['dtx'])){

						$pfgetdatax = $_POST['dtx'];
						$pfgetdatax = $this->PFCleanArrayAttr('PFCleanFilters',$pfgetdatax);

						if (is_array($pfgetdatax)) {
							foreach ($pfgetdatax as $pfgetdatax_key => $pfgetdatax_value) {

								if(isset($pfgetdatax_value['value'])){
									if (!empty($pfgetdatax_value['value'])) {

										if ($pfgetdatax_value['name'] == 'post_tags') {
								
											$args['tag_id'] = $pfgetdatax_value['value'];
											
										}else{
											if(isset($args['tax_query']) == false || isset($args['tax_query']) == NULL){
												$args['tax_query'] = array();
											}

											if(count($args['tax_query']) > 0){
												$args['tax_query'][(count($args['tax_query']) - 1)]=
												array(
														'taxonomy' => $pfgetdatax_value['name'],
														'field' => 'id',
														'terms' => $this->pfstring2BasicArray($pfgetdatax_value['value']),
														'operator' => 'IN'
												);
											}else{
												$args['tax_query']=
												array(
													'relation' => 'AND',
													array(
														'taxonomy' => $pfgetdatax_value['name'],
														'field' => 'id',
														'terms' => $this->pfstring2BasicArray($pfgetdatax_value['value']),
														'operator' => 'IN'
													)
												);
											}
										}

										
									}
								}
							}
						}
					}
				}



				
			
			/* Start: Coordinate Filter */
				if ($sw != -360 && (!empty($sw) && !empty($sw2) && !empty($ne) && !empty($ne2))) {

					$args['pf_sw'] = $sw;
					$args['pf_sw2'] = $sw2;
					$args['pf_ne'] = $ne;
					$args['pf_ne2'] = $ne2;
					
				}
			/* End: Coordinate Filter */


			/* Cleanup query */
			$args = apply_filters( 'pointfinder_cleanup_query_for_grid', $args );


			/* Start: Image Settings and hover elements */
				$setup22_searchresults_animation_image  = $this->PFSAIssetControl('setup22_searchresults_animation_image','','WhiteSquare');
				$setup22_searchresults_hover_image  = $this->PFSAIssetControl('setup22_searchresults_hover_image','','0');
				$setup22_searchresults_hover_video  = $this->PFSAIssetControl('setup22_searchresults_hover_video','','0');
				$setup22_searchresults_hide_address  = $this->PFSAIssetControl('setup22_searchresults_hide_address','','0');
				$st22srloc = $this->PFSAIssetControl('st22srloc','',0);
				$st8_npsys = $this->PFASSIssetControl('st8_npsys','',0);

				$setup16_featureditemribbon_hide = $this->PFSAIssetControl('setup16_featureditemribbon_hide','','1');
				$setup4_membersettings_favorites = $this->PFSAIssetControl('setup4_membersettings_favorites','','1');
				$setup22_searchresults_hide_re = $this->PFREVSIssetControl('setup22_searchresults_hide_re','','1');
				$setup16_reviewstars_nrtext = $this->PFREVSIssetControl('setup16_reviewstars_nrtext','','0');

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
				$stp22_qwlink = $this->PFSAIssetControl('stp22_qwlink','','1');
				$showmapfeature = $this->PFSAIssetControl('setup22_searchresults_showmapfeature','','1');

			
				$pfboptx1 = $this->PFSAIssetControl('setup22_searchresults_hide_excerpt','1','0');
				$pfboptx2 = $this->PFSAIssetControl('setup22_searchresults_hide_excerpt','2','0');
				$pfboptx3 = $this->PFSAIssetControl('setup22_searchresults_hide_excerpt','3','0');
				$pfboptx4 = $this->PFSAIssetControl('setup22_searchresults_hide_excerpt','4','0');
				
				if($pfboptx1 != 1){$pfboptx1_text = 'style="display:none"';}else{$pfboptx1_text = '';}
				if($pfboptx2 != 1){$pfboptx2_text = 'style="display:none"';}else{$pfboptx2_text = '';}
				if($pfboptx3 != 1){$pfboptx3_text = 'style="display:none"';}else{$pfboptx3_text = '';}
				if($pfboptx4 != 1){$pfboptx4_text = 'style="display:none"';}else{$pfboptx4_text = '';}

				/* Grid type for HTML strings */
				
				if(empty($pfgrid)){
					switch($setup22_searchresults_defaultlistingtype){
						case '2':
						case '3':
						case '4':$pfgrid = 'grid'.$setup22_searchresults_defaultlistingtype;break;
						case '1':$pfgrid = 'grid1';break;
					}
				}
				
				switch($pfgrid){
					case 'grid1':$pfgrid_output = 'pf1col';$pfgridcol_output = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';break;
					case 'grid2':$pfgrid_output = 'pf2col';$pfgridcol_output = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';break;
					case 'grid3':$pfgrid_output = 'pf3col';$pfgridcol_output = 'col-lg-4 col-md-6 col-sm-6 col-xs-12';break;
					case 'grid4':$pfgrid_output = 'pf4col';$pfgridcol_output = 'col-lg-3 col-md-4 col-sm-4 col-xs-12';break;
					default:$pfgrid_output = 'pf4col';$pfgridcol_output = 'col-lg-3 col-md-4 col-sm-4 col-xs-12';break;
				}
			
				switch($pfgrid_output){case 'pf1col':$pfboptx_text = $pfboptx1_text;break;case 'pf2col':$pfboptx_text = $pfboptx2_text;break;case 'pf3col':$pfboptx_text = $pfboptx3_text;break;case 'pf4col':$pfboptx_text = $pfboptx4_text;break;}
			/* End: Image Settings and hover elements */

			/* Start: Favorites check */
				if ($user_loggedin_check) {
					$user_favorites_arr = get_user_meta( get_current_user_id(), 'user_favorites', true );

					if (!empty($user_favorites_arr)) {
						$user_favorites_arr = json_decode($user_favorites_arr,true);
					}else{
						$user_favorites_arr = array();
					}

				}
			/* End: Favorites check */


			if ($pf_from == 'halfmap' || $pf_from == 'topmap') {
				$setup22_searchresults_status_catfilters = $this->PFSAIssetControl('setup22_searchresults_status_catfilters','','1');
				if ($setup22_searchresults_status_catfilters == 0) {
					$pfheaderfilters = 'false';
				}
			}
			
			
			$pointfinderlocationsex_vars = get_option('pointfinderlocationsex_vars');
			
			if ($st8_npsys == 1) {
				$listing_pstyle_meta = get_option('pointfinderltypes_style_vars');
			}else{
				$listing_pstyle_meta = '';
			}

			$loop = new WP_Query( $args );

			if ($debug == 1) {
				/*Check Results*/
				//print_r($loop->query).PHP_EOL;
				print_r($args);
				echo $loop->request.PHP_EOL;
				echo $loop->found_posts.PHP_EOL;
			}
			
			if (isset($loop->found_posts)) {
				if ($loop->found_posts == 1) {
					$found_keyword = ' '.esc_html__( "Listing Found", "pointfindercoreelements" );
				}elseif($loop->found_posts > 1){
					$found_keyword = ' '.esc_html__( "Listings Found", "pointfindercoreelements" );
				}
			}
			
			
			/* Start: Grid (HTML) */
				$wpflistdata .= '<div class="pfsearchresults '.$pfcontainershow.' pflistgridview" data-fpt="'.$found_keyword.'" data-foundposts="'.$loop->found_posts.'" data-taxonomyname="'.esc_html__( "Search Results", "pointfindercoreelements" ).'">';

	          	/* Start: Header Area for filters (HTML) */
		            if($pfheaderfilters == '' && $loop->post_count != 0){

		            	$wpflistdata .= '<div class="'.$pfcontainerdiv.'-header pflistcommonview-header clearfix">';

			                if ($pfcontainerdiv == 'pfsearchresults') {
			                	$wpflistdata .= '<div class="pf-container"><div class="pf-row"><div class="col-lg-12">';
			                } 
			                			/*
			                            * Start: Left Filter Area
			                            */
				                            $wpflistdata .= '<div class="'.$pfcontainerdiv.'-filters-left '.$pfcontainerdiv.'-filters searchformcontainer-filters searchformcontainer-filters-left clearfix col-lg-9 col-md-8 col-sm-9 col-xs-12">';

				                            	if ($pf_from == 'halfmap' || $pf_from == 'topmap') {$wpflistdata .= '<input type="hidden" name="pfsearchfilterfrom" value="'.$pf_from.'" class="pfsearch-filter-from"/>';}else{$wpflistdata .= '<input type="hidden" name="pfsearchfilterfrom" value="" class="pfsearch-filter-from"/>';}

					                            /*
					                            * Start: SORT BY Section
					                            */	   
													if($setup22_searchresults_status_sortby == 0){
													   	$wpflistdata .= '<div class="inner-sort-filter">';
														   	$wpflistdata .= '<label for="pfsearch-filter" class="lbl-ui select pfsortby">';

															   	$wpflistdata .= '<select class="pfsearch-filter" name="pfsearch-filter" id="pfsearch-filter">';

																		if($args['orderby'] == 'ID' && $args['orderby'] != 'meta_value_num' && $args['orderby'] != 'meta_value'){
																			$wpflistdata .= '<option value="" selected>'.esc_html__('SORT BY','pointfindercoreelements').'</option>';
																		}else{
																			$wpflistdata .= '<option value="">'.esc_html__('SORT BY','pointfindercoreelements').'</option>';
																		}

																		$pfgform_values3 = array('title','date','rand','nearby','mviewed');
																		$pfgform_values3_texts = array(
																			'title'=>esc_html__('Listing Name','pointfindercoreelements'),
																			'date'=>esc_html__('Release Date','pointfindercoreelements'),
																			'rand'=>esc_html__('Random','pointfindercoreelements'),
																			'nearby'=>esc_html__('Nearby','pointfindercoreelements'),
																			'mviewed'=>esc_html__('Most Viewed','pointfindercoreelements')
																		);

																		$pfgform_values3_options = array(
																			'title'=>array(
																				'title_az'=>esc_html__('From A to Z','pointfindercoreelements'),
																				'title_za'=>esc_html__('From Z to A','pointfindercoreelements')
																			),
																			'date'=>array(
																				'date_az'=>esc_html__('Newest','pointfindercoreelements'),
																				'date_za'=>esc_html__('Oldest','pointfindercoreelements')
																			)
																		);
																		
																		if ($review_system_statuscheck == 1) {
																			array_push($pfgform_values3, 'reviewcount');
																			array_push($pfgform_values3, 'mreviewed');
																			$pfgform_values3_texts['reviewcount'] = esc_html__('Rating Score','pointfindercoreelements');
																			$pfgform_values3_texts['mreviewed'] = esc_html__('Rating Quantity','pointfindercoreelements');

																			$pfgform_values3_options['reviewcount'] = array(
																				'reviewcount_az'=>esc_html__('Highest Rated','pointfindercoreelements'),
																				'reviewcount_za'=>esc_html__('Lowest Rated','pointfindercoreelements')
																			);

																			$pfgform_values3_options['mreviewed'] = array(
																				'mreviewed_az'=>esc_html__('Most Rated','pointfindercoreelements'),
																				'mreviewed_za'=>esc_html__('Less Rated','pointfindercoreelements')
																			);
																		}
																		if (class_exists('Pointfinderspecialreview_Public')) {
																			array_push($pfgform_values3, 'reviewcount');
																			$pfgform_values3_texts['reviewcount'] = esc_html__('Total Score','pointfindercoreelements');
																		}

																		if (empty($pfg_orderby_original)) {
																			$pfg_orderby_selected = $setup22_searchresults_defaultsortbytype;
																		}else{
																			$pfg_orderby_selected = $pfg_orderby_original;
																		}

																		foreach($pfgform_values3 as $pfgform_value3){

																			if (isset($pfgform_values3_options[$pfgform_value3])) {
																				$wpflistdata .= '<optgroup label="'.$pfgform_values3_texts[$pfgform_value3].'">';
																					foreach ($pfgform_values3_options[$pfgform_value3] as $pfgform_values3_single_option_key => $pfgform_values3_single_option_value) {
																						if(strcmp($pfgform_values3_single_option_key, $pfg_orderby_selected) == 0){
																							$wpflistdata .= '<option value="'.$pfgform_values3_single_option_key.'" selected>'.$pfgform_values3_single_option_value.'</option>';
																						}else{
																							$wpflistdata .= '<option value="'.$pfgform_values3_single_option_key.'">'.$pfgform_values3_single_option_value.'</option>';
																						}
																					}
																				$wpflistdata .= '</optgroup>';
																			}else{
																				if(strcmp($pfgform_value3, $pfg_orderby_selected) == 0){
																					$wpflistdata .= '<option value="'.$pfgform_value3.'" selected>'.$pfgform_values3_texts[$pfgform_value3].'</option>';
																				}else{
																					$wpflistdata .= '<option value="'.$pfgform_value3.'">'.$pfgform_values3_texts[$pfgform_value3].'</option>';
																				}
																			}

																		}
																		if(!empty($pfsearchvars)){
																			if(!isset($pfg_orderby)){
																				$wpflistdata .= $this->PFIF_SortFields_sg($pfsearchvars);
																			}else{
																				$wpflistdata .= $this->PFIF_SortFields_sg($pfsearchvars,$pfg_orderby);
																			}
																		}else if(!empty($pfgetdatax)){
																			$pfgetdatax_r = array();
																			if (is_array($pfgetdatax)) {
																				foreach ($pfgetdatax as $pfgetdatax_single) {
																					if (isset($pfgetdatax_single['name'])) {
																						if ($pfgetdatax_single['name'] == 'pointfinderltypes') {
																							$pfgetdatax_r = array('listingtype'=>$pfgetdatax_single['value']);
																						}
																					}
																				}
																			}
																			if(!isset($pfg_orderby)){
																				$wpflistdata .= $this->PFIF_SortFields_sg($pfgetdatax_r);
																			}else{
																				$wpflistdata .= $this->PFIF_SortFields_sg($pfgetdatax_r,$pfg_orderby);
																			}
																		}else{
																			
																			if(!isset($pfg_orderby)){
																				$wpflistdata .= $this->PFIF_SortFields_sg($pfgetdata);
																			}else{
																				$wpflistdata .= $this->PFIF_SortFields_sg($pfgetdata,$pfg_orderby);
																			}
																		}
																		
																$wpflistdata .='</select>';
															$wpflistdata .= '</label>';

															
														$wpflistdata .= '</div>';
													}
												/*
					                            * End: SORT BY Section
					                            */


					                            /*
					                            * Start: Number Section
					                            */
													if($setup22_searchresults_status_number == 0 && $pfg_authormode == 0 && $pfg_agentmode == 0){
														$wpflistdata .= '<div class="inner-sort-filter">';
															$wpflistdata .= '<label for="pfsearch-filter-number" class="lbl-ui select pfnumberby">';
																$wpflistdata .= '<select class="pfsearch-filter-number" name="pfsearch-filter-number" id="pfsearch-filter-number" >';

																	$pfgform_values = $this->PFIFPageNumbers();
																
																	if($args['posts_per_page'] != ''){
																		$pagevalforn = $args['posts_per_page'];
																	}else{
																		$pagevalforn = $setup22_searchresults_defaultppptype;
																	}
																	
																	foreach($pfgform_values as $pfgform_value){
							                                           if(strcmp($pfgform_value,$pagevalforn) == 0){
																	  	   $wpflistdata .= '<option value="'.$pfgform_value.'" selected>'.$pfgform_value.'</option>';
																	   }else{
																		   $wpflistdata .= '<option value="'.$pfgform_value.'">'.$pfgform_value.'</option>';
																	   }
																	}

																$wpflistdata .= '</select>';
															$wpflistdata .= '</label>';
														$wpflistdata .= '</div>';
													}
												/*
					                            * End: Number Section
					                            */
												

												if (!isset($_POST['pfex']) && empty($_POST['pfex'])) {$wpflistdata .= '<li class="pfgridlist6" title="'.esc_html__( "Close this section.", "pointfindercoreelements" ).'"><i class="fas fa-times"></i></li>';}


												$setup3_modulessetup_openinghours = $this->PFSAIssetControl('setup3_modulessetup_openinghours','','0');
												$setup3_modulessetup_openinghours_ex = $this->PFSAIssetControl('setup3_modulessetup_openinghours_ex','','1');
												$setup22_ohours = $this->PFSAIssetControl('setup22_ohours','','1');
												if ($setup3_modulessetup_openinghours == 1 && $setup3_modulessetup_openinghours_ex == 2 && $setup22_ohours == 1) {
													if ($ohours == 'active') {
														$wpflistdata.= '<div class="openedonlybutton pointfinder-border-radius pointfinder-border-color" data-status="active" data-active="'.esc_html__( "Open Now", "pointfindercoreelements" ).'" data-passive="'.esc_html__( "Disable Filter", "pointfindercoreelements" ).'" title="'.esc_html__( "Disable Filter", "pointfindercoreelements" ).'"><i class="fas fa-clock"></i></div>';
													}else{

														$wpflistdata.= '<div class="openedonlybutton pointfinder-border-radius pointfinder-border-color" data-status="passive" data-active="'.esc_html__( "Open Now", "pointfindercoreelements" ).'" data-passive="'.esc_html__( "Disable Filter", "pointfindercoreelements" ).'" title="'.esc_html__( "Open Now", "pointfindercoreelements" ).'"><i class="far fa-clock"></i></div>';
													}
													
												}

											$wpflistdata .= '</div>';
										/*
			                            * End: Left Filter Area
			                            */


			                            /*
			                            * Start: Right Filter Area
			                            */
					                        if($pfg_authormode == 0 && $pfg_agentmode == 0){
						                        $wpflistdata .= '<ul class="'.$pfcontainerdiv.'-filters-right '.$pfcontainerdiv.'-filters searchformcontainer-filters searchformcontainer-filters-right clearfix col-lg-3 col-md-4 col-sm-3 col-xs-12">';
													
													$css_text_pfselectedval = ' pfselectedval';

				                                    if($setup22_searchresults_status_2col == 0){
				                                    	
				                                    	$wpflistdata .= "<li class='pfgridlist2 pfgridlistit";
				                                    	if($pfgrid == "grid2"){
				                                    		$wpflistdata .= $css_text_pfselectedval;
				                                    	}
				                                    	$wpflistdata .= "' data-pf-grid='grid2'><i class='fas fa-th-large'></i></li>";
				                                    }
				                                    if($setup22_searchresults_status_3col == 0 && $pf_from != 'halfmap'){
				                                    	$wpflistdata .= "<li class='pfgridlist3 pfgridlistit";
				                                    	if($pfgrid == "grid3"){
				                                  			$wpflistdata .= $css_text_pfselectedval;
			                                   		 	}
				                                    	$wpflistdata .= "' data-pf-grid='grid3'><i class='fas fa-th'></i></li>";
				                                    }
				                                    if($setup22_searchresults_status_4col == 0 && $pf_from != 'halfmap'){
				                                    	$wpflistdata .= "<li class='pfgridlist4 pfgridlistit";
				                                    	if($pfgrid == "grid4"){
				                                    		$wpflistdata .= $css_text_pfselectedval;
				                                    	}
				                                    	$wpflistdata .= "' data-pf-grid='grid4'><i class='fas fa-grip-vertical'></i><i class='fas fa-grip-vertical'></i></li>";
				                                    }
				                                    if($setup22_searchresults_status_2colh == 0){
				                                    	$wpflistdata .= "<li class='pfgridlist5 pfgridlistit";
				                                    	if($pfgrid == "grid1"){
				                                 		 $wpflistdata .= $css_text_pfselectedval;
				                                    	}
				                                    	$wpflistdata .= "' data-pf-grid='grid1'><i class='fas fa-th-list'></i></li>";
				                                    }
				                                    
													if(empty($pf_from)){
														$wpflistdata .= '<li class="pfgridlist6"><i class="fas fa-times"></i></li>';
													}
				                                
												$wpflistdata .= '</ul>';
											}
										/*
			                            * End: Right Filter Area
			                            */

			                    

			                if ($pfcontainerdiv === 'pfsearchresults') {
			                    $wpflistdata .='</div></div></div>';
			            	}

						$wpflistdata .= '</div>';
		            }
	        	/* End: Header Area for filters (HTML) */


	        	/* Start: Grid List Area - HEAD (HTML) */
	                $wpflistdata .='<div class="'.$pfcontainerdiv.'-content pflistcommonview-content" data-layout-mode="'.$grid_layout_mode.'">';
	                
	                if ($pfcontainerdiv === 'pfsearchresults') {
	                	$wpflistdata.='<div class="pf-container"><div class="pf-row clearfix"><div class="col-lg-12">';
	                }

	            	$wpflistdata .='<ul class="pfitemlists-content-elements '.$pfgrid_output.'" data-layout-mode="'.$grid_layout_mode.'">';
	            /* End: Grid List Area - HEAD (HTML) */



				/* Start: Loop for grid List */
					

					
					if($loop->post_count > 0){
						while ( $loop->have_posts() ) : $loop->the_post();
							$post_id = get_the_id();
							$post_listingtype = get_the_terms($post_id,'pointfinderltypes');
							/* Start: Prepare Item Elements */
								$ItemDetailArr = array();
								
								/* Get Item's WPML ID */
								if ($pflang) {$pfitemid = $this->PFLangCategoryID_ld($post_id,$pflang,$setup3_pointposttype_pt1);}else{$pfitemid = $post_id;}

								/* Start: Setup Featured Image */
									$featured_image_stored = $this->pointfinder_featured_image_getresized_special($pfitemid,$template_directory_uri,$general_crop2,$general_retinasupport,$setupsizelimitconf_general_gridsize1_width,$setupsizelimitconf_general_gridsize1_height);

									$ItemDetailArr['featured_image_org'] = $featured_image_stored['featured_image_org'];
									$ItemDetailArr['featured_image'] = $featured_image_stored['featured_image'];
								/* End: Setup Featured Image */

								/* Start: Setup Details */
									$ItemDetailArr['if_title'] = get_the_title($pfitemid);
									$ItemDetailArr['if_excerpt'] = get_the_excerpt();
									$ItemDetailArr['if_link'] = get_permalink($pfitemid);;
									$ItemDetailArr['if_address'] = esc_html(get_post_meta( $pfitemid, 'webbupointfinder_items_address', true ));
									$ItemDetailArr['featured_video'] =  get_post_meta( $pfitemid, 'webbupointfinder_item_video', true );
									

									$data_values = $pfstviewcor = '';

				                      if (is_archive() || is_category() || is_search() || is_tag() || is_search() || $pf_from == 'halfmap' || $pf_from == 'topmap') {
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
				                      }


									$post_listing_typeval = '';
									$post_listing_typeval = isset($post_listingtype[0]->term_id)?$post_listingtype[0]->term_id:'';

									$output_data = $this->PFIF_DetailText_ld($pfitemid,$setup22_searchresults_hide_lt,$post_listing_typeval,$listing_pstyle_meta,$pf_from);

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
								/* End: Setup Details */

							/* End: Prepare Item Elements */	
							

							/* Start: Item Box */
								$fav_check = 'false';

								$wpflistdata_output .= '<li class="'.$pfgridcol_output.' wpfitemlistdata isotope-item">';
									$wpflistdata_output .= '<div class="pflist-item"'.$pfitemboxbg.$data_values.'>';
										$wpflistdata_output .= '<div class="pflist-item-inner">';
										
											/* Start: Image Container */
												$wpflistdata_output .= '<div class="pflist-imagecontainer pflist-subitem clearfix">';
													$wpflistdata_output .= "<a href='".$ItemDetailArr['if_link']."'".$targetforitem.">";
													if ($general_crop2 == 3) {
														$wpflistdata_output .= "<div class='pfuorgcontainer'><img src='".$ItemDetailArr['featured_image'] ."' alt='' /></div>";
													}else{
														$wpflistdata_output .= "<img src='".$ItemDetailArr['featured_image'] ."' alt='' />";
													}
													
													$wpflistdata_output .= "</a>";
																
													

										            /* Start: Hover mode enabled */
														if($setup22_searchresults_hover_image == 0){
															$wpflistdata_output .= '<div class="pfImageOverlayH hidden-xs"></div>';
															
																if($setup22_searchresults_hover_video == 0 && !empty($ItemDetailArr['featured_video'])){	
																	$wpflistdata_output .= '<div class="pfButtons pfStyleV pfStyleVAni hidden-xs">';
																}else{
																	$wpflistdata_output .= '<div class="pfButtons pfStyleV2 pfStyleVAni hidden-xs">';
																}

																$wpflistdata_output .= '
																<span class="'.$pfbuttonstyletext.' clearfix">
																	<a class="pficon-imageclick" data-pf-link="'.$ItemDetailArr['featured_image_org'].'" data-pf-type="image" style="cursor:pointer">
																		<i class="far fa-image"></i>
																	</a>
																</span>';

																if($setup22_searchresults_hover_video == 0 && !empty($ItemDetailArr['featured_video'])){	
																	$wpflistdata_output .= '
																	<span class="'.$pfbuttonstyletext.'">
																		<a class="pficon-videoclick" data-pf-link="'.$ItemDetailArr['featured_video'].'" data-pf-type="iframe" style="cursor:pointer">
																			<i class="fas fa-film"></i>
																		</a>
																	</span>';
																}
																if (class_exists('Pointfindercustom360')) {
							                                      $special360 = get_post_meta( $pfitemid, 'webbupointfinder_item_360', true );
							                                      if (!empty($special360)) {
							                                         $wpflistdata_output .= '
							                                        <span class="'.$pfbuttonstyletext.'">
							                                          <a class="pficon-videoclick" data-pf-link="'.$special360.'" data-pf-type="iframe" style="cursor:pointer">
							                                            <i class="pf360icon"></i>
							                                          </a>
							                                        </span>';
							                                      }else{
							                                        $wpflistdata_output .= '
							                                        <span class="'.$pfbuttonstyletext.'">
							                                          <a href="'.$ItemDetailArr['if_link'].'"'.$targetforitem.'>
							                                            <i class="fas fa-link"></i>
							                                          </a>
							                                        </span>';
							                                      }
							                                     
							                                    }else{
							                                      $wpflistdata_output .= '
							                                      <span class="'.$pfbuttonstyletext.'">
							                                        <a href="'.$ItemDetailArr['if_link'].'"'.$targetforitem.'>
							                                          <i class="fas fa-link"></i>
							                                        </a>
							                                      </span>';
							                                    }

															$wpflistdata_output .= '</div>';
														}
													/* End: Hover mode enabled */

													/* Start: Featured Item Ribbon */
														if ($setup16_featureditemribbon_hide != 0) {
							                        		$featured_check_x = get_post_meta( $pfitemid, 'webbupointfinder_item_featuredmarker', true );

			                        						if (!empty($featured_check_x)) {
							                        			$wpflistdata_output .= '<div class="pfribbon-wrapper-featured"><div class="pfribbon-featured">'.esc_html__('FEATURED','pointfindercoreelements').'</div></div>';
							                        		}
								                        	
								                        }
								                    /* End: Featured Item Ribbon */

								                    /* Start: Conditions */

								                        if ($setup3_pt14_check == 1) {
						                        			$post_condition = get_the_terms( $post_id, 'pointfinderconditions');

				                        					if (isset($post_condition[0]->term_id)) {
					                        											
						                        				$contidion_colors = $this->pf_get_condition_color($post_condition[0]->term_id);

						                        				$condition_c = (isset($contidion_colors['cl']))? $contidion_colors['cl']:'#494949';
						                        				$condition_b = (isset($contidion_colors['bg']))? $contidion_colors['bg']:'#f7f7f7';

						                        				$wpflistdata_output .= '<div class="pfconditions-tag" style="color:'.$condition_c.';background-color:'.$condition_b.'">';
							                        			$wpflistdata_output .= '<a href="' . esc_url( get_term_link( $post_condition[0]->term_id, 'pointfinderconditions' ) ) . '" style="color:'.$condition_c.';">'.$post_condition[0]->name.'</a>';
							                        			$wpflistdata_output .= '</div>';
							                        			
						                        			}
								                        }
									                /* End: Conditions */




									                /* Start: Price Value Check and Output */
														if (!empty($output_data_priceval) || !empty($output_data_ltypes)) {

															$wpflistdata_output .= '<div class="pflisting-itemband">';
														
																$wpflistdata_output .= '<a href="'.$ItemDetailArr['if_link'].'" "'.$targetforitem.'" class="pflist-pricecontainer">';
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
																
																$wpflistdata_output .= '</a>';
													
															$wpflistdata_output .= '</div>';
														}
													/* End: Price Value Check and Output */

												$wpflistdata_output .='</div>';

											/* End: Image Container */
										
											/* Start: Detail Texts */	
												$title_text = $ItemDetailArr['if_title'];
												$address_text = $ItemDetailArr['if_address'];
												$excerpt_text = $ItemDetailArr['if_excerpt'];
												/* Title and address area */
												$wpflistdata_output .= '
													<div class="pflist-detailcontainer pflist-subitem clearfix">
														<ul class="pflist-itemdetails">
															<li class="pflist-itemtitle pflineclamp-title"><a href="'.$ItemDetailArr['if_link'].'"'.$targetforitem.'>'.$title_text.'</a></li>
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
														';
														
														if ($pfgrid == 'grid1') {
															$field_startdate = get_post_meta($post_id,'webbupointfinder_item_field_startdate',true);
															if (!empty($field_startdate)) {
																$field_enddate = ($post_id != '') ? get_post_meta($post_id,'webbupointfinder_item_field_enddate',true) : '' ;
																$field_starttime = ($post_id != '') ? get_post_meta($post_id,'webbupointfinder_item_field_starttime',true) : '' ;
																$field_endtime = ($post_id != '') ? get_post_meta($post_id,'webbupointfinder_item_field_endtime',true) : '' ;
																$wpflistdata_output .= '<div class="pflistonecol-customfields pfeventinfo">
																<div class="pflistingitem-subelement pf-onlyitem pfeven">
																<span class="pf-ftitle">'.esc_html__("Event Info:","pointfindercoreelements").'</span>
																<span class="pf-ftext">'.date_i18n(get_option('date_format')." ".get_option('time_format'),$field_startdate." ".$field_starttime,true).' '.esc_html__("to","pointfindercoreelements").' '.date_i18n(get_option('date_format')." ".get_option('time_format'),$field_enddate." ".$field_endtime,true).'</span>
																</div></div>';
															}
														}
														
														if (!empty($output_data_content) && $pfgrid == 'grid1') {

															$wpflistdata_output .= '<div class="pflistonecol-customfields">'.$output_data_content.'</div>';
															
														}
														if($pfboptx_text != 'style="display:none"' && $pfgrid == 'grid1'){
														$wpflistdata_output .= '
															<div class="pflist-excerpt pflist-subitem pflineclamp-excerpt" '.$pfboptx_text.'>'.$excerpt_text.'</div>
														';
														}
														$wpflistdata_output .= '
													</div>
												';
													
												if($pfboptx_text != 'style="display:none"' && $pfgrid != 'grid1'){
													$wpflistdata_output .= '<div class="pflist-excerpt pflist-subitem pflineclamp-excerpt" '.$pfboptx_text.'>'.$excerpt_text.'</div>';
												}
												
												if ((!empty($output_data_content) || !empty($output_data_priceval)) && $pfgrid != 'grid1') {
													$wpflistdata_output .= '<div class="pflist-subdetailcontainer pflist-subitem"><div class="pflist-customfields">'.$output_data_content.'</div></div>';
												}
												if ($pfgrid != 'grid1') {
													$field_startdate = get_post_meta($post_id,'webbupointfinder_item_field_startdate',true);
													if (!empty($field_startdate)) {
														$field_enddate = ($post_id != '') ? get_post_meta($post_id,'webbupointfinder_item_field_enddate',true) : '' ;
														$field_starttime = ($post_id != '') ? get_post_meta($post_id,'webbupointfinder_item_field_starttime',true) : '' ;
														$field_endtime = ($post_id != '') ? get_post_meta($post_id,'webbupointfinder_item_field_endtime',true) : '' ;
														$wpflistdata_output .= '<div class="pflist-subdetailcontainer pflist-subitem pfeventinfo"><div class="pflistonecol-customfields">
														<div class="pflistingitem-subelement pf-onlyitem">
														<span class="pf-ftitle">'.esc_html__("Event starting:","pointfindercoreelements").'</span>
														<span class="pf-ftext">'.date_i18n(get_option('date_format')." ".get_option('time_format'),$field_startdate." ".$field_starttime,true).'</span>
														</div><div class="pflistingitem-subelement pf-onlyitem">
														<span class="pf-ftitle">'.esc_html__("Event ending:","pointfindercoreelements").'</span>
														<span class="pf-ftext">'.date_i18n(get_option('date_format')." ".get_option('time_format'),$field_enddate." ".$field_endtime,true).'</span>
														</div></div></div>';
													}
												}

												/* Show on map text for search results and search page */
													$wpflistdata_output .= '<div class="pflist-subdetailcontainer pflist-subitem pfshowmapmain clearfix">';
														if ($st22srloc == 1) {
															$location_val = $this->GetPFTermInfoX( $pfitemid, 'pointfinderlocations','',$pointfinderlocationsex_vars,$pf_from);
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
															if ($showmapfeature == 1 && !empty($pfstviewcor)) {
															$wpflistdata_output .= '<a data-pfitemid="'.$pfitemid.'" class="pfshowmaplink" title="'.esc_html__('Show on Map','pointfindercoreelements').'">
																<i class="fas fa-location-arrow"></i>
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
													
										$wpflistdata_output .= '</div>';
									$wpflistdata_output .= '</div>';
								$wpflistdata_output .= '</li>';

							/* End: Item Box */

						endwhile;
						$wpflistdata .= $wpflistdata_output;
					}
					$wpflistdata .= '</ul>';

					if($loop->found_posts == 0){
						$wpflistdata .= '<div class="golden-forms">';
                      	$wpflistdata .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><div class="pfnotfoundlisting"><i class="fas fa-search-minus"></i></div>';

						if ($pf_from == 'topmap') {
			                $wpflistdata .= '<span>'.esc_html__("SORRY, WE COULDN'T FIND ANY LISTINGS RELATED TO YOUR CRITERIA.",'pointfindercoreelements').'<br/><small>';
			                $wpflistdata .= wp_sprintf(esc_html__( "PLEASE TRY AGAIN WITH SOME DIFFERENT OPTIONS OR %sRESET TO DEFAULT VIEW%s.", "pointfindercoreelements" ),'<a class="pf-resetfilters-button-txt">','</a>').'</small></span></div>';
						}elseif ($pf_from == 'halfmap') {				            
			                $wpflistdata .= '<span>'.esc_html__("SORRY, WE COULDN'T FIND ANY LISTINGS RELATED TO YOUR SEARCH CRITERIA.",'pointfindercoreelements').'<br/><small>';
			                $wpflistdata .= wp_sprintf(esc_html__( "PLEASE TRY AGAIN WITH SOME DIFFERENT KEYWORDS OR %sRESET SEARCH FORM%s.", "pointfindercoreelements" ),'<a class="pf-resetfilters-button-txt">','</a>').'</small></span></div>';
						}else{
			                $wpflistdata .= '<span>'.esc_html__("SORRY, WE COULDN'T FIND ANY LISTINGS RELATED TO YOUR CRITERIAS.",'pointfindercoreelements').'<br/><small>';
			                $wpflistdata .= esc_html__( "PLEASE EDIT THIS ELEMENT AND CHOOSE DIFFERENT FILTERS.", "pointfindercoreelements" ).'</small></span></div>';
						}
						$wpflistdata .= '</div>';
					}
		        /* End: Loop for grid List */


	            /* Start: Paginate */
	            	$wpflistdata .= '<div class="pfajax_paginate" >';
	            	$big = 999999999;
	            	
	            	if (!empty($pfgetdata) && !empty($pfaction) && $pfg_agentmode == 0) {
	            		$pfformvars = array();
	            		if (isset($pfgetdata[0]['name'])) {
							foreach($pfgetdata as $singledata){
								if(!empty($singledata['value'])){
									$pfformvars[$singledata['name']] = $singledata['value'];
								}
							}

							$pfsearchvars = $pfformvars;
							$pfsearchvars['pfg_number'] = $args['posts_per_page'];
							$pfsearchvars['pfg_orderby'] = $pfg_orderby_original;
							$pfsearchvars['grid'] = $pfgrid;
							$pfsearchvars['from'] = $pf_from;
							$pfsearchvars['ohours'] = $ohours;
						}else{
							$pfsearchvars = $pfgetdata;
						}
						

						$pfsearchvars['page'] = $big;

	            		$home_url = add_query_arg($pfsearchvars,home_url("/"));
						$wpflistdata .= paginate_links(array(
							'base' => str_replace( $big, '%#%', esc_url( $home_url ) ),
							'format' => '?page=%#%',
							'current' => max(1, $pfg_paged),
							'total' => $loop->max_num_pages,
							'type' => 'list',
						));
						
	            	}elseif(empty($pfgetdata) && empty($pfaction) && $pfg_agentmode == 0){
	            		if (strpos($_SERVER['HTTP_REFERER'],home_url("/")) == 0) {
	            			$home_url = add_query_arg(array(
	            				'pfg_number' => $args['posts_per_page'],
	            				'pfg_orderby' => $pfg_orderby_original,
	            				'page' => $big,
	            				'grid' => $pfgrid,
	            				'from' => $pf_from,
	            				'ohours' => $ohours
	            			),esc_url(strtok($_SERVER["HTTP_REFERER"],'?')));
	            			$wpflistdata .= paginate_links(array(
								'base' => str_replace( $big, '%#%', esc_url( $home_url ) ),
								'format' => '?page=%#%',
								'current' => max(1, $pfg_paged),
								'total' => $loop->max_num_pages,
								'type' => 'list',
							));
	            		}
						
	            	}elseif (!empty($pfgetdata) && !empty($pfaction) && $pfg_agentmode != 0) {
	            		if (strpos($_SERVER['HTTP_REFERER'],home_url("/")) == 0) {
	            			$home_url = add_query_arg(array(
	            				'pfg_number' => $args['posts_per_page'],
	            				'pfg_orderby' => $pfg_orderby_original,
	            				'page' => $big,
	            				'grid' => $pfgrid,
	            				'ohours' => $ohours
	            			),esc_url(strtok($_SERVER["HTTP_REFERER"],'?')));
	            			$wpflistdata .= paginate_links(array(
								'base' => str_replace( $big, '%#%', esc_url( $home_url ) ),
								'format' => '?page=%#%',
								'current' => max(1, $pfg_paged),
								'total' => $loop->max_num_pages,
								'type' => 'list',
							));
	            		}
	            	}

	            	$wpflistdata .= '</div>';
					
				/* End: Paginate */


				/* Start: Grid List Area - FOOTER (HTML) */
					if ($pfcontainerdiv === 'pfsearchresults') {
						$wpflistdata .= '</div></div></div>';
					}
					$wpflistdata .= '</div></div>';
				/* End: Grid List Area - FOOTER (HTML) */

				wp_reset_postdata();
			
		   echo $wpflistdata;
			
		die();
	}
  
}