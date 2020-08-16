<?php 
if (!class_exists('PointFinderPoiData')) {
	class PointFinderPoiData extends Pointfindercoreelements_AJAX
	{
	    public function __construct(){}

	    public function pf_ajax_markers(){
			check_ajax_referer( 'pfget_markers', 'security' );
			header('Content-type: application/json');		
				
				$debug = 0;

				if(isset($_POST['cl']) && $_POST['cl']!=''){
					$pflang = esc_attr($_POST['cl']);
				}else{
					$pflang = '';
				}

				/* WPML Fix */
				if(class_exists('SitePress')) {
					if (!empty($pflang)) {
						do_action( 'wpml_switch_language', $pflang );
					}
				}


				

				/* Get admin values */
				$setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
				
				/*Get point limits*/
				if(isset($_POST['spl']) && $_POST['spl']!=''){
					$setup8_pointsettings_limit = $_POST['spl'];
					$setup8_pointsettings_orderby = $_POST['splob'];
					$setup8_pointsettings_order = $_POST['splo'];
				}else{
					$setup8_pointsettings_limit = -1;
				}
				
				/*Search form check*/
				if(isset($_POST['act']) && $_POST['act']!=''){
					$pfaction = esc_attr($_POST['act']);
				}else{
					$pfaction = '';
				}
				

				$pfgetdata['manual_args'] = (!empty($manualargs))? maybe_unserialize(base64_decode($manualargs)): '';
				
				$args = array( 'post_type' => $setup3_pointposttype_pt1, 'posts_per_page' => $setup8_pointsettings_limit, 'post_status' => 'publish');
				
				if(isset($args['meta_query']) == false || isset($args['meta_query']) == NULL){
					$args['meta_query'] = array();
				}

				if(isset($args['tax_query']) == false || isset($args['tax_query']) == NULL){
					$args['tax_query'] = array();
				}
				
				if($setup8_pointsettings_limit > 0){
					
					if($setup8_pointsettings_orderby != ''){$args['orderby']=$setup8_pointsettings_orderby;};
					if($setup8_pointsettings_order != ''){$args['order']=$setup8_pointsettings_order;};
					
				}
				
							
				if($pfaction == 'search'){
					if(isset($_POST['dt']) && $_POST['dt']!=''){
						$pfgetdata = $_POST['dt'];
						if (!is_array($pfgetdata)) {
							$pfgetdata = maybe_unserialize(base64_decode($pfgetdata,true));
							if (is_array($pfgetdata)) {
								foreach ($pfgetdata as $key => $value) {
									$pfnewgetdata[] = array('name' => $key, 'value'=>$value);
								}
								$pfgetdata = $pfnewgetdata;
							}
						}
					}else{
						$pfgetdata = '';
					}
					
						if(is_array($pfgetdata)){
							
							$pfformvars = array();
							
								foreach($pfgetdata as $singledata){
									
									/*Get Values & clean*/
									if (is_array($singledata['value'])) {
										$pfformvars[esc_attr($singledata['name'])] = $singledata['value'];
									}else{
										if(esc_attr($singledata['value']) != ''){
											
											if(isset($pfformvars[esc_attr($singledata['name'])])){
												$pfformvars[esc_attr($singledata['name'])] = $pfformvars[esc_attr($singledata['name'])]. ',' .$singledata['value'];
											}else{
												$pfformvars[esc_attr($singledata['name'])] = $singledata['value'];
											}
										}
									}
								
								}

								$pfgetdata = $this->PFCleanArrayAttr('PFCleanFilters',$pfgetdata);

								$pf_query_builder = new PointfinderSearchQueryBuilder($args);
								$pf_query_builder->setQueryValues($pfformvars,'poidata',array());
								$args = $pf_query_builder->getQuery();	
							
						}
				}else{
					if(isset($_POST['singlepoint']) && !empty($_POST['singlepoint'])){
						$pfitem_singlepoint = esc_attr($_POST['singlepoint']);
						$args['p'] = $pfitem_singlepoint;
						$args['suppress_filters'] = true;
					}
					
				}


				if(isset($_POST['dtx']) && $_POST['dtx']!='' && isset($_POST['dt']) == false ){

					$pfgetdatax = $_POST['dtx'];
					$pfgetdatax = $this->PFCleanArrayAttr('PFCleanFilters',$pfgetdatax);


					if (is_array($pfgetdatax)) {
						foreach ($pfgetdatax as $key => $value) {

							if(isset($value['value'])){
								if (!empty($value['value'])) {
									$args['tax_query'][]=array(
											'taxonomy' => $value['name'],
											'field' => 'id',
											'terms' => pfstring2BasicArray($value['value']),
											'operator' => 'IN'
									);
								}
							}
						}
					}

				}

				/* Check paged for archive and category */
				if(isset($_POST['ppp']) && $_POST['ppp']!=''){
					$ppp = esc_attr($_POST['ppp']);
					$paged = intval(esc_attr($_POST['paged']));
					$order = sanitize_text_field($_POST['order']);
					$orderby = sanitize_text_field($_POST['orderby']);
					$pfrandomize = (isset($_POST['pfrandomize'])) ? sanitize_text_field($_POST['pfrandomize']):'';
					if ($ppp != -1) {
						$args['posts_per_page'] = $ppp;
						$args['paged'] = $paged;
						$args['orderby'] = $orderby;
						$args['order'] = $order;
						if($orderby == 'date' || $orderby == 'title'){
							
							$args['orderby'] = array('meta_value_num' => 'DESC' , $orderby => $order);
							$args['meta_key'] = 'webbupointfinder_item_featuredmarker';

							if ($pfrandomize == 'yes') {
								if(isset($args['orderby'][$orderby])){unset($args['orderby'][$orderby]);}
								$args['orderby']['rand']='';
							}

						}else{
							
							$args['meta_key']='webbupointfinder_item_'.$orderby;
							
							if($this->PFIF_CheckFieldisNumeric_ld($orderby) == false){
								$args['orderby']= array('meta_value' => $order);
							}else{
								$args['orderby']= array('meta_value_num' => $order);
							}
							
						}
					}

				}

				/* Check if lat,lng empty */
				if(isset($_POST['ne']) && $_POST['ne']!=''){
					$ne = esc_attr($_POST['ne']);
				}else{
					$ne = "";
				}
				
				if(isset($_POST['ne2']) && $_POST['ne2']!=''){
					$ne2 = esc_attr($_POST['ne2']);
				}else{
					$ne2 = "";
				}
				
				if(isset($_POST['sw']) && $_POST['sw']!=''){
					$sw = esc_attr($_POST['sw']);
				}else{
					$sw = "";
				}
				
				if(isset($_POST['sw2']) && $_POST['sw2']!=''){
					$sw2 = esc_attr($_POST['sw2']);
				}else{
					$sw2 = "";
				}

				$args['meta_query'][] = array(
					'key' => 'webbupointfinder_items_location',
					'compare' => 'EXISTS'
					
				);

				$args['pf_sw'] = $sw;
				$args['pf_sw2'] = $sw2;
				$args['pf_ne'] = $ne;
				$args['pf_ne2'] = $ne2;


				/* Cleanup query */
				if (isset($args['meta_query'])) {
					if (empty($args['meta_query'])) {
						unset($args['meta_query']);
					}
				}
				if (isset($args['tax_query'])) {
					if (empty($args['tax_query'])) {
						unset($args['tax_query']);
					}
				}

				if (isset($pfgetdata['manual_args']['meta_query'])) {
					if (empty($pfgetdata['manual_args']['meta_query'])) {
						unset($pfgetdata['manual_args']['meta_query']);
					}
				}
				if (isset($pfgetdata['manual_args']['tax_query'])) {
					if (empty($pfgetdata['manual_args']['tax_query'])) {
						unset($pfgetdata['manual_args']['tax_query']);
					}
				}

				
				$st8_npsys = $this->PFASSIssetControl('st8_npsys','',0);
				$output = array();
			
				
				$loop = new WP_Query( $args );
				
					/*
						Check Results	*/
						if ($debug == 1) {
							print_r($loop->query).PHP_EOL;
							echo $loop->request.PHP_EOL;
							echo $loop->found_posts.PHP_EOL;
						}
							
						
					if ($ppp != -1) {
						$output['found_posts'] = $ppp;
					}else{
						$output['found_posts'] = $loop->found_posts;
					}
					if($loop->post_count > 0){
				
						while ( $loop->have_posts() ) : $loop->the_post();
						$post_id = get_the_id();
						$item_coordinates = get_post_meta( $post_id, 'webbupointfinder_items_location', true);
						
						$coordinates = explode( ',', $item_coordinates );
						if (!empty($coordinates[0]) && !empty($coordinates[1])) {
							if (is_numeric($coordinates[0]) && is_numeric($coordinates[1])) {
									

									$pfitemicon = $this->pf_get_markerimage($post_id,1,1,$st8_npsys);

									$pf_cat_idld = $this->PFLangCategoryID_ld($post_id,$pflang,$setup3_pointposttype_pt1);
										
									$output['data'][$post_id] = array(
										'latLng' => array(
											$coordinates[0],
											$coordinates[1]
										)
									);

									$output['data'][$post_id]['id'] = (!empty($pflang))?$pf_cat_idld:$post_id;
									$output['data'][$post_id]['title'] = get_the_title();
									if ($this->PFControlEmptyArr($pfitemicon)) {
										$output['data'][$post_id]['icon'] = ($pfitemicon['is_cat'] == 1)?$pfitemicon['cat']:$pfitemicon['content'];
										$output['data'][$post_id]['icontype'] = ($pfitemicon['is_cat'] == 1)?1:2;
									}else{
										$output['data'][$post_id]['icon'] = '';
									}
							}
						}	
						endwhile;
					
					}
					if ($ppp == -1) {
						$output['found_posts'] = (isset($output['data']))?count($output['data']):0;
					}
					
				echo json_encode($output,JSON_PRETTY_PRINT);
				
			die();
		}

		

		private function pf_get_markerimage($postid,$st8_npsys){
							
			$pfitemicon = array();

			/* Check if item have a custom icon */

			$webbupointfinder_item_point_type = esc_attr(get_post_meta( $postid, "webbupointfinder_item_point_type", true ));
			$webbupointfinder_item_point_typenew = (empty($webbupointfinder_item_point_type))? 3:$webbupointfinder_item_point_type;


			switch ($webbupointfinder_item_point_typenew) {
				case 1:
					//$pf_custom_point_images = redux_post_meta("pointfinderthemefmb_options", $postid, "webbupointfinder_item_custom_marker");
					//$pf_custom_point_images = get_post_meta( $post_id,"webbupointfinder_item_custom_marker", true );
					
					/** 
					*Start: Custom icon check result = Image Icon
					**/
						$pfitemicon['is_image'] = 1;
						$pfitemicon['is_cat'] = 0;

						global $wpdb;
						$pf_custom_point_images = $wpdb->get_var($wpdb->prepare("select meta_value from $wpdb->postmeta where post_id=%d and meta_key='%s'",$postid,'webbupointfinder_item_custom_marker'));
						$pf_custom_point_images = unserialize($pf_custom_point_images);

						$pf_custom_point_image_height = (!empty($pf_custom_point_images['height']))? $pf_custom_point_images['height'] : 0;
						$pf_custom_point_image_width = (!empty($pf_custom_point_images['width']))? $pf_custom_point_images['width'] : 0;


						$width_calculated = $pf_custom_point_image_width;
						$height_calculated = $pf_custom_point_image_height;

						$pfitemicon['content']= '<div class=\'pf-map-pin-x\' style=\'background-image:url('.$pf_custom_point_images['url'].'); background-size:'.$width_calculated.'px '.$height_calculated.'px; width:'.$width_calculated.'px; height:'.$height_calculated.'px;opacity:1;\'></div>';
					/** 
					*End: Custom icon check result = Image Icon
					**/
				break;

			case 2:

				/** 
				*Start: Custom icon check result = Css Icon
				**/
					$cssmarker_icontype = esc_attr(get_post_meta( $postid, 'webbupointfinder_item_cssmarker_icontype', true ));
					$cssmarker_icontype = (empty($cssmarker_icontype)) ? 1 : $cssmarker_icontype ;
					$cssmarker_iconsize = esc_attr(get_post_meta( $postid, 'webbupointfinder_item_cssmarker_iconsize', true ));
					$cssmarker_iconsize = (empty($cssmarker_iconsize)) ? 'middle' : $cssmarker_iconsize ;
					$cssmarker_iconname = esc_attr(get_post_meta( $postid, 'webbupointfinder_item_cssmarker_iconname', true ));

					$cssmarker_bgcolor = esc_attr(get_post_meta( $postid, 'webbupointfinder_item_cssmarker_bgcolor', true ));
					$cssmarker_bgcolor = (empty($cssmarker_bgcolor)) ? '#b00000' : $cssmarker_bgcolor ;
					$cssmarker_bgcolorinner = esc_attr(get_post_meta( $postid, 'webbupointfinder_item_cssmarker_bgcolorinner', true ));
					$cssmarker_bgcolorinner = (empty($cssmarker_bgcolorinner)) ? '#ffffff' : $cssmarker_bgcolorinner ;
					$cssmarker_iconcolor = esc_attr(get_post_meta( $postid, 'webbupointfinder_item_cssmarker_iconcolor', true ));
					$cssmarker_iconcolor = (empty($cssmarker_iconcolor)) ? '#b00000' : $cssmarker_iconcolor ;
					
					$arrow_text = ($cssmarker_icontype == 2)? '<div class=\'pf-pinarrow\' style=\'border-color: '.$cssmarker_bgcolor.' transparent transparent transparent;\'></div>': '';

					$pfitemicon['is_image'] = 1;
					$pfitemicon['is_cat'] = 0;

					$pfitemicon['content'] = '';
					
					
					$pfitemicon['content'] .= '<div ';
					$pfitemicon['content'] .= 'class=\'pfcatdefault-mapicon pf-map-pin-'.$cssmarker_icontype.' pf-map-pin-'.$cssmarker_icontype.'-'.$cssmarker_iconsize.' pfcustom-mapicon-'.$postid.'\'';
					$pfitemicon['content'] .= ' style=\'background-color:'.$cssmarker_bgcolor.';opacity:1;\' >';
					$pfitemicon['content'] .= '<i class=\''.$cssmarker_iconname.'\' style=\'color:'.$cssmarker_iconcolor.'\' ></i></div>'.$arrow_text;
					$pfitemicon['content'] .= '<style>.pfcustom-mapicon-'.$postid.':after{background-color:'.$cssmarker_bgcolorinner.'!important}</style>';

				/** 
				*End: Custom icon check result = Css Icon
				**/	
				break;

			default:
				/** 
				*Start: Check category icon 
				**/
					$pfitemicon['is_image'] = 0;
					$pfitemicon['is_cat'] = 1;

					$pf_item_terms = get_the_terms( $postid, 'pointfinderltypes');
					
					/* If marker term is available and array not empty */
					if(count($pf_item_terms) > 0){

						if ( $pf_item_terms && ! is_wp_error( $pf_item_terms ) ) {
							
							if($st8_npsys == 1){
								foreach ( $pf_item_terms as $pf_item_term ) {
									$pf_item_term_id = $pf_item_term->term_id;
								}
							}else{
								foreach ( $pf_item_terms as $pf_item_term ) {
								
									if ($pf_item_term->parent != 0) {
										$pf_item_term_subcheck = $this->pf_term_sub_check_ex($pf_item_term->parent);
										if ($pf_item_term_subcheck) {
											$pf_item_term_id = $pf_item_term->term_id;
										}else{
											$pf_item_term_id = $this->pf_term_sub_check($pf_item_term->term_id);
										}
										if (!empty($pf_item_term_id)) {
											break;
										}
									}else{
										$pf_item_term_id = $pf_item_term->term_id;
									}
									
									
								}
							}
							

						} 

						if(class_exists('SitePress')) { /* If wpml enabled */
							$pf_item_term_id = apply_filters('wpml_object_id',$pf_item_term_id,'pointfinderltypes',true,$this->PF_default_language());
						}

						if (!empty($pf_item_term_id)) {
							$pfitemicon['cat'] = 'pfcat'.$pf_item_term_id;
						}else{
							$pfitemicon['cat'] = 'pfcatdefault';
						}
					
						
					}
					
				/** 
				*End: Check category icon 
				**/
				break;
			}
			
			return $pfitemicon;
		}

		private function pf_term_sub_check($myval){
			$term_sub_check = get_term_by( 'term_id', $myval, 'pointfinderltypes');
		
			if ($term_sub_check != false) {

				if ($term_sub_check->parent == 0) {
					$output = $myval;
				}else{
					$output = $this->pf_term_sub_check($term_sub_check->parent);
				}
			}else{
				$output = $myval;
			}

			return $output;
		}

		private function pf_term_sub_check_ex($myval){
			$term_sub_check = get_term_by( 'term_id', $myval, 'pointfinderltypes');
		
			if ($term_sub_check != false) {

				if ($term_sub_check->parent == 0) {
					return true;
				}else{
					return false;
				}
			}
		}

		
	  
	}
}