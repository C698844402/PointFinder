<?php 
if (class_exists('PointFinderInfoWindow')) {
	return;
}


class PointFinderInfoWindow extends Pointfindercoreelements_AJAX
{
    public function __construct(){}


    public function pf_ajax_infowindow(){
		check_ajax_referer( 'pfget_infowindow', 'security' );
	    header('Content-Type: text/html; charset=UTF-8;');

	    $output_data = '';

		if(isset($_REQUEST['id']) && !empty($_REQUEST['id'])){
			$id = absint($_REQUEST['id']);

			$itemvars = array();
			
			$itemvars[$id] = $this->PFIF_ItemDetails($id);
			$output_data .= $this->PFIF_OutputData($itemvars[$id],$id);
		}
		
		echo $this->pointfinder_sanitize_output($output_data);
		die();
	}

	

	private function PFIF_ItemDetails($id){
		$ItemDetailArr = array();
		$setup10_infowindow_img_width  = $this->PFSAIssetControl('setup10_infowindow_img_width','','154');
		$setup10_infowindow_img_height  = $this->PFSAIssetControl('setup10_infowindow_img_height','','136');
		$setup10_infowindow_hide_image  = $this->PFSAIssetControl('setup10_infowindow_hide_image','','0');
		$general_retinasupport = $this->PFSAIssetControl('general_retinasupport','','0');
		if($general_retinasupport == 1){$pf_retnumber = 2;}else{$pf_retnumber = 1;}

		$setup10_infowindow_img_width  = $setup10_infowindow_img_width*$pf_retnumber;
		$setup10_infowindow_img_height  = $setup10_infowindow_img_height*$pf_retnumber;

		$itemvars[$id]['featured_image']  = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'full' );
	
		if( $setup10_infowindow_hide_image == 0){
			$ItemDetailArr['featured_image_big'] = $itemvars[$id]['featured_image'][0];

			if($itemvars[$id]['featured_image'][0] != '' && $itemvars[$id]['featured_image'][0] != NULL){
				$ItemDetailArr['featured_image'] = pointfinder_aq_resize($itemvars[$id]['featured_image'][0],$setup10_infowindow_img_width,$setup10_infowindow_img_height,true);}else{$ItemDetailArr['featured_image'] = '';
			}

			if($ItemDetailArr['featured_image'] === false) {
				if($general_retinasupport == 1){
					$ItemDetailArr['featured_image'] = pointfinder_aq_resize($itemvars[$id]['featured_image'][0],$setup10_infowindow_img_width/2,$setup10_infowindow_img_height/2,true);
					if($ItemDetailArr['featured_image'] === false) {
						$ItemDetailArr['featured_image'] = $itemvars[$id]['featured_image'][0];
					}
				}else{
					$ItemDetailArr['featured_image'] = '';
				}

			}

			if (empty($ItemDetailArr['featured_image'])) {
				$ItemDetailArr['featured_image'] = pointfinder_aq_resize($itemvars[$id]['featured_image'][0],$setup10_infowindow_img_width,$setup10_infowindow_img_height,true,true,true);
			}
		}else{
			$ItemDetailArr['featured_image'] = '';
			$ItemDetailArr['featured_image_big'] = '';
		}
		
		$ItemDetailArr['if_title'] = html_entity_decode(get_the_title($id));
		$ItemDetailArr['featured_video'] =  get_post_meta( $id, 'webbupointfinder_item_video', true );
		$ItemDetailArr['if_link'] = get_permalink($id);
		$ItemDetailArr['if_address'] = esc_html(get_post_meta( $id, 'webbupointfinder_items_address', true ));
		
		return $ItemDetailArr;
	}
	

	private function PFIF_OutputData($itemvars,$id){
		$output_data = '';
		$st22srlinknw = $this->PFSAIssetControl('st22srlinknw','','0');
		$targetforitem = '';
		if ($st22srlinknw == 1) {
			$targetforitem = ' target="_blank"';
		}
		$setup10_infowindow_animation_image  = $this->PFSAIssetControl('setup10_infowindow_animation_image','','WhiteSquare');
		$setup10_infowindow_hover_image  = $this->PFSAIssetControl('setup10_infowindow_hover_image','','0');
		$setup10_infowindow_hover_video  = $this->PFSAIssetControl('setup10_infowindow_hover_video','','0');
		$setup10_infowindow_hide_address  = $this->PFSAIssetControl('setup10_infowindow_hide_address','','0');

		$setup16_featureditemribbon_hide = $this->PFSAIssetControl('setup16_featureditemribbon_hide','','1');
	

		$pfbuttonstyletext = 'pfHoverButtonStyle ';

		switch($setup10_infowindow_animation_image){
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

		$single_point = 0;

		if(isset($_POST['single']) && !empty($_POST['single'])){
			$single_point = esc_attr($_POST['single']);
		}
		$disable_itempr = (!empty($_POST['disable']))?esc_attr($_POST['disable']):0;

		$featured_status_ribbon = get_post_meta( $id, 'webbupointfinder_item_featuredmarker', true );

		if($itemvars['featured_image'] != ''){
			$output_data .= "<div class='wpfimage clearfix'><div class='wpfimage-wrapper clearfix'>";
				$setup10_infowindow_hide_ratings = $this->PFSAIssetControl('setup10_infowindow_hide_ratings','','1');
				if($setup10_infowindow_hover_image == 1 && $single_point == 0){
					$output_data .= "<a href='".$itemvars['if_link']."'".$targetforitem."><img src='".$itemvars['featured_image'] ."' alt='' /></a>";

					

                    $setup3_pt14_check = $this->PFSAIssetControl('setup3_pt14_check','',0);
                    if ($setup3_pt14_check == 1) {

                    	$item_defaultvalue = wp_get_post_terms($id, 'pointfinderconditions', array("fields" => "all"));
						if (isset($item_defaultvalue[0]->term_id)) {
            				$contidion_colors = $this->pf_get_condition_color($item_defaultvalue[0]->term_id);

            				$condition_c = (isset($contidion_colors['cl']))? $contidion_colors['cl']:'#494949';
            				$condition_b = (isset($contidion_colors['bg']))? $contidion_colors['bg']:'#f7f7f7';

                			$output_data .= '
                			<div class="pfribbon-wrapper-featured3" style="color:'.$condition_c.';background-color:'.$condition_b.'">
                			<div class="pfribbon-featured3">'.$item_defaultvalue[0]->name.'</div>
                			</div>';
            			}


                    }


                    if ($this->PFREVSIssetControl('setup11_reviewsystem_check','','0') == 1 && $setup10_infowindow_hide_ratings == 0) {
                    	$setup22_searchresults_hide_re = $this->PFREVSIssetControl('setup22_searchresults_hide_re','','1');
                    	$setup16_reviewstars_nrtext = $this->PFREVSIssetControl('setup16_reviewstars_nrtext','','0');
                    	if ($setup22_searchresults_hide_re == 0) {
                    		$reviews = $this->pfcalculate_total_review($id);

                    		if (!empty($reviews['totalresult'])) {
                    			$rev_total_res = round($reviews['totalresult']);

                    			$output_data .= '<div class="pfrevstars-wrapper-review pf-infowindow-review">';
                    			$output_data .= ' <div class="pfrevstars-review">';
                    				for ($ri=0; $ri < $rev_total_res; $ri++) {
                    					$output_data .= '<i class="pfadmicon-glyph-377"></i>';
                    				}
                    				for ($ki=0; $ki < (5-$rev_total_res); $ki++) {
                    					$output_data .= '<i class="pfadmicon-glyph-378"></i>';
                    				}

                    			$output_data .= '</div></div>';
                    		}else{
                    			if($setup16_reviewstars_nrtext == 0){
                        			$output_data .= '<div class="pfrevstars-wrapper-review pf-infowindow-review">';
                        			$output_data .= ' <div class="pfrevstars-review">'.esc_html__('Not rated.','pointfindercoreelements').'';
                        			$output_data .= '</div></div>';
                    			}
                    		}
                    	}

                    }

				}elseif($setup10_infowindow_hover_image == 0 && $single_point == 0){
					$output_data .= "<img src='".$itemvars['featured_image'] ."' alt='' />";


                    if($disable_itempr != 1){
					$buton_q_text = ($setup10_infowindow_hover_video != 1 && !empty($itemvars['featured_video']))? 'pfStyleV':'pfStyleV2';
					$output_data .= '<div class="pfImageOverlayH"></div><div class="pfButtons '.$buton_q_text.' pfStyleVAni"><span class="'.$pfbuttonstyletext.' clearfix"><a class="pficon-imageclick" data-pf-link="'.$itemvars['featured_image_big'].'" data-pf-type="image" style="cursor:pointer"><i class="far fa-image"></i></a></span>';

					if($setup10_infowindow_hover_video != 1 && !empty($itemvars['featured_video'])){
					$output_data .= '<span class="'.$pfbuttonstyletext.'"><a class="pficon-videoclick" data-pf-link="'.$itemvars['featured_video'] .'" data-pf-type="iframe" style="cursor:pointer"><i class="fas fa-film"></i></a></span>';
					}

					$output_data .='<span class="'.$pfbuttonstyletext.'"><a href="'.$itemvars['if_link'].'"'.$targetforitem.'><i class="fas fa-link"></i></a></span></div>';
					}

					$setup3_pt14_check = $this->PFSAIssetControl('setup3_pt14_check','',0);
                    if ($setup3_pt14_check == 1) {

                    	$item_defaultvalue = wp_get_post_terms($id, 'pointfinderconditions', array("fields" => "all"));
						if (isset($item_defaultvalue[0]->term_id)) {
            				$contidion_colors = $this->pf_get_condition_color($item_defaultvalue[0]->term_id);

            				$condition_c = (isset($contidion_colors['cl']))? $contidion_colors['cl']:'#494949';
            				$condition_b = (isset($contidion_colors['bg']))? $contidion_colors['bg']:'#f7f7f7';

                			$output_data .= '
                			<div class="pfribbon-wrapper-featured3" style="color:'.$condition_c.';background-color:'.$condition_b.'">
                			<div class="pfribbon-featured3">'.$item_defaultvalue[0]->name.'</div>
                			</div>';
            			}


                    }



					


                    if ($this->PFREVSIssetControl('setup11_reviewsystem_check','','0') == 1 && $setup10_infowindow_hide_ratings == 0) {
                    	$setup22_searchresults_hide_re = $this->PFREVSIssetControl('setup22_searchresults_hide_re','','1');
                    	$setup16_reviewstars_nrtext = $this->PFREVSIssetControl('setup16_reviewstars_nrtext','','0');

                    	if ($setup22_searchresults_hide_re == 0) {

                    		$reviews = $this->pfcalculate_total_review($id);
                    		if (!empty($reviews['totalresult'])) {
                    			$rev_total_res = round($reviews['totalresult']);
                    			$output_data .= '<div class="pfrevstars-wrapper-review pf-infowindow-review">';
                    			$output_data .= ' <div class="pfrevstars-review">';
                    				for ($ri=0; $ri < $rev_total_res; $ri++) {
                    					$output_data .= '<i class="pfadmicon-glyph-377"></i>';
                    				}
                    				for ($ki=0; $ki < (5-$rev_total_res); $ki++) {
                    					$output_data .= '<i class="pfadmicon-glyph-378"></i>';
                    				}

                    			$output_data .= '</div></div>';
                    		}else{
                    			if($setup16_reviewstars_nrtext == 0){
                        			$output_data .= '<div class="pfrevstars-wrapper-review pf-infowindow-review">';
                        			$output_data .= ' <div class="pfrevstars-review">  '.esc_html__('Not rated yet.','pointfindercoreelements').'';
                        			$output_data .= '</div></div>';
                    			}
                    		}
                    	}

                    }

				}elseif($single_point == 1){
					$output_data .= "<img src='".$itemvars['featured_image'] ."'>";
				}

			$output_data .= "</div></div>";
		}

		
		$output_data .= "<div class='wpftext'>";
		$output_data .= "<span class='wpftitle pflineclamp-title-iw'><a href='".$itemvars['if_link']."'".$targetforitem.">".$itemvars['if_title']."</a></span>";

		$address_text = $itemvars['if_address'];
		if($setup10_infowindow_hide_address == 0){
			$output_data .= "<span class='wpfaddress pflineclamp-address-iw'>".$address_text."</span>";
		}

		$output_data .= "<span class='wpfdetail'>".$this->PFIF_DetailText($id)."</span>";
		$output_data .= "</div>";
		return $output_data;
	}
	

	private function PFIF_DetailText($id){
		if(isset($_POST['cl']) && $_POST['cl']!=''){
			$pflang = esc_attr($_POST['cl']);

			if(class_exists('SitePress')) {
				if (!empty($pflang)) {
					do_action( 'wpml_switch_language', $pflang );
				}
			}
		}else{
			$pflang = '';
		}


		$setup10_infowindow_animation_image  = $this->PFSAIssetControl('setup10_infowindow_animation_image','','WhiteSquare');
		$setup10_infowindow_hover_image  = $this->PFSAIssetControl('setup10_infowindow_hover_image','','0');
		$setup10_infowindow_hover_video  = $this->PFSAIssetControl('setup10_infowindow_hover_video','','0');
		$setup10_infowindow_hide_address  = $this->PFSAIssetControl('setup10_infowindow_hide_address','','0');
		$setup10_infowindow_hide_lt  = $this->PFSAIssetControl('setup10_infowindow_hide_lt','','0');
		$setup10_infowindow_hide_it  = $this->PFSAIssetControl('setup10_infowindow_hide_it','','0');


		$pfstart = $this->PFCheckStatusofVar('setup1_slides');

		if($pfstart == true){

			$if_detailtext = '<ul class="pfinfowindowdlist">';

				$post_listing_typeval = wp_get_post_terms( $id, 'pointfinderltypes', array('fields'=>'ids') );
				if (isset($post_listing_typeval[0])) {
					$post_listing_typeval = $post_listing_typeval[0];
				}else{
					$post_listing_typeval = '';
				}

				$setup1_slides = $this->PFSAIssetControl('setup1_slides','','');
				if(is_array($setup1_slides)){
					$price_field_done = apply_filters( 'pointfinder_price_field_filter', false );
					foreach ($setup1_slides as &$value) {

						$customfield_infocheck = $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_sinfowindow','','0');
						$available_fields = array(1,2,3,4,5,7,8,14);

						if(in_array($value['select'], $available_fields) && $customfield_infocheck != 0){


							$PFTMParent = '';
							$ShowField = true;

							if(!empty($post_listing_typeval)){
								$PFTMParent = $this->pf_get_term_top_most_parent($post_listing_typeval,'pointfinderltypes');
								$PFTMParent = (isset($PFTMParent['parent']))?$PFTMParent['parent']:'';
							}

							$ParentItem = $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_parent','','0');

							if($this->PFControlEmptyArr($ParentItem) && class_exists('SitePress')){
								$NewParentItemArr = array();
								foreach ($ParentItem as $ParentItemSingle) {
									$NewParentItemArr[] = apply_filters('wpml_object_id', $ParentItemSingle, 'pointfinderltypes', TRUE);
								}
								$ParentItem = $NewParentItemArr;
							}


							/*If it have a parent element*/
							if($this->PFControlEmptyArr($ParentItem)){

								if(class_exists('SitePress')) {
									$PFCLang = PF_current_language();
									foreach ($ParentItem as $key => $valuex) {
										$ParentItem[$key] = apply_filters('wpml_object_id',$valuex,'pointfinderltypes',true,$PFCLang);
									}
								}

								$PFLTCOVars = get_option('pointfinderltypes_covars');

								if (isset($PFLTCOVars[$PFTMParent]['pf_subcatselect'])) {
									if ($PFLTCOVars[$PFTMParent]['pf_subcatselect'] == 1) {
										$post_listing_typeval = $PFTMParent;
									}
								}

								if(in_array($post_listing_typeval, $ParentItem) ){
									$ShowField = true;
								}else{
									$ShowField = false;
								}
							}

							if ($ShowField) {


								$PF_CF_Val = new PF_CF_Val($id);
								$ClassReturnVal = $PF_CF_Val->GetValue($value['url'],$id,$value['select'],$value['title']);
								if($ClassReturnVal != ''){
									
									if ($price_field_done) {
										$ClassReturnVal = str_replace("pf-price","",$ClassReturnVal);
									}
									if (strpos($ClassReturnVal, 'pf-price') != false) {
										$price_field_done = true;
									}
									$if_detailtext .= $ClassReturnVal;
								}
							}
						}

					}
				}
				unset($PF_CF_Val);


			if($setup10_infowindow_hide_lt == 0){

				$setup10_infowindow_hide_lt_text = $this->PFSAIssetControl('setup10_infowindow_hide_lt_text','','');
				if($setup10_infowindow_hide_lt_text != ''){ $pfitemtext = $setup10_infowindow_hide_lt_text;}else{$pfitemtext = '';}
				$if_detailtext .= '<li class="pfiflitype pfliittype"><span class="wpfdetailtitle">'.$pfitemtext.'</span>';
				if($pfitemtext != ''){
					$if_detailtext .= ' '.$this->GetPFTermInfoWindow($id,'pointfinderltypes',$pflang).'<span class="pf-fieldspace"></span></li>';
				}else{
					$if_detailtext .= ' <span class="wpfdetailtitle">'.$this->GetPFTermInfoWindow($id,'pointfinderltypes',$pflang).'</span><span class="pf-fieldspace"></span></li>';
				}
			}



			if($setup10_infowindow_hide_it == 0){
				$setup10_infowindow_hide_it_text = $this->PFSAIssetControl('setup10_infowindow_hide_it_text','','');
				if($setup10_infowindow_hide_it_text != ''){ $pfitemtext = $setup10_infowindow_hide_it_text;}else{$pfitemtext = '';}
				$if_detailtext .= '<li class="pfifittype pfliittype"><span class="wpfdetailtitle">'.$pfitemtext.'</span>';
				if($pfitemtext != ''){
					$if_detailtext .= ' '.$this->GetPFTermInfoWindow($id,'pointfinderitypes',$pflang).'<span class="pf-fieldspace"></span></li>';
				}else{
					$if_detailtext .= ' <span class="wpfdetailtitle">'.$this->GetPFTermInfoWindow($id,'pointfinderitypes',$pflang).'</span><span class="pf-fieldspace"></span></li>';
				}
			}

			$if_detailtext .= '</ul>';

		}
		unset($PF_CF_Val);
		return $if_detailtext;
	}

	private function GetPFTermInfoWindow($id, $taxonomy,$pflang = ''){
		$termnames = '';
		$postterms = get_the_terms( $id, $taxonomy );
		$st22srlinklt = PFSAIssetControl('st22srlinklt','','1');

		if($postterms){
			foreach($postterms as $postterm){
				if (isset($postterm->term_id)) {
					if(class_exists('SitePress')) {
						if (!empty($pflang)) {
							$term_idx = apply_filters('wpml_object_id',$postterm->term_id,$taxonomy,true,$pflang);
						}else{
							$term_idx = apply_filters('wpml_object_id',$postterm->term_id,$taxonomy,true,PF_current_language());
						}
					} else {
						$term_idx = $postterm->term_id;
					}

					$terminfo = get_term( $term_idx, $taxonomy );

					$term_link = get_term_link( $term_idx, $taxonomy );
					if (is_wp_error($term_link) === true) {$term_link = '#';}


					$term_info_name = $terminfo->name;
					if (is_wp_error($term_info_name) === true) {$term_info_name = '';}

					if(!empty($termnames)){$termnames .= ', ';}


					if ($st22srlinklt == 1) {
						$termnames .= '<a href="'.$term_link.'">'.$term_info_name.'</a>';
					}else{
						$termnames .= '<span class="pfdetail-ftext-nolink">'.$term_info_name.'</span>';
					}
				}
			}
		}
		return $termnames;
	}

  
}