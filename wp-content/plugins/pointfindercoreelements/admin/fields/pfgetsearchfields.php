<?php
/**********************************************************************************************************************************
*
* Custom Search Fields Retrieve Value Class
* This class prepared for help to create auto config file.
* Author: Webbu
*
***********************************************************************************************************************************/
if ( ! class_exists( 'PF_SF_Val' ) ){
	class PF_SF_Val{

		use PointFinderOptionFunctions;
		use PointFinderCommonFunctions;
		use PointFinderWPMLFunctions;
		
		public $FieldOutput;
		public $PFHalf = 1;
		public $ScriptOutput;
		public $ScriptOutputDocReady;
		public $VSORules;
		public $VSOMessages;
		public $ListingTypeField;
		public $LocationField;


		public function __construct(){}
		
		function PriceFieldCheck($slug){
			if($this->PFCFIssetControl('setupcustomfields_'.$slug.'_currency_check','','0') == 1){
				return array(
					'CFPrefix' => $this->PFCFIssetControl('setupcustomfields_'.$slug.'_currency_prefix','',''),
					'CFSuffix' => $this->PFCFIssetControl('setupcustomfields_'.$slug.'_currency_suffix','',''),
					'CFDecima' => $this->PFCFIssetControl('setupcustomfields_'.$slug.'_currency_decima','','0'),
					'CFDecimp' => $this->PFCFIssetControl('setupcustomfields_'.$slug.'_currency_decimp','','.'),
					'CFDecimt' => $this->PFCFIssetControl('setupcustomfields_'.$slug.'_currency_decimt','',',')
				);
			}else{return 'none';	}
		}
		
		function SizeFieldCheck($slug){
			if($this->PFCFIssetControl('setupcustomfields_'.$slug.'_size_check','','0') == 1){

				$CFDecimp = $this->PFCFIssetControl('setupcustomfields_'.$slug.'_size_decimp','','.');
				if ($CFDecimp == '.') {
					$CFDecimt = ',';
				}else{
					$CFDecimt = '.';
				}

				return array(
					'CFPrefix' => $this->PFCFIssetControl('setupcustomfields_'.$slug.'_size_prefix','',''),
					'CFSuffix' => $this->PFCFIssetControl('setupcustomfields_'.$slug.'_size_suffix','',''),
					'CFDecima' => $this->PFCFIssetControl('setupcustomfields_'.$slug.'_size_decima','','0'),
					'CFDecimp' => $CFDecimp,
					'CFDecimt' => $CFDecimt
				);
			}else{return 'none';	}
		}
		
		function CheckItemsParent($slug){
			$RelationFieldName = 'setupcustomfields_'.$slug.'_parent';

			$ParentItem = $this->PFCFIssetControl($RelationFieldName,'','');
			
	
			if(!empty($ParentItem)){
				
				if(class_exists('SitePress')) {
					if (is_array($ParentItem)) {
						foreach ($ParentItem as $key => $value) {
							$ParentItem[$key] = apply_filters('wpml_object_id',$value,'pointfinderltypes',true,$this->PF_current_language());
						}
					}else{
						$ParentItem = apply_filters('wpml_object_id',$ParentItem,'pointfinderltypes',true,$this->PF_current_language());
					}
					return $ParentItem;
					
				} else {
					return $ParentItem;
				}
			}else{
				return 'none';
			}
		}

		function GetMiniSearch($minisearchc=1){
			switch ($minisearchc) {
				case '1':
					return '<div class="col-lg-9 col-md-9 col-sm-6 colhorsearch">';
					break;
				
				case '2':
					return '<div class="col-lg-4 col-md-4 col-sm-6 colhorsearch">';
					break;

				case '3':
					return '<div class="col-lg-3 col-md-3 col-sm-6 colhorsearch">';
					break;

				default:
					return '<div class="col-lg-9 col-md-9 col-sm-6 colhorsearch">';
					break;
			}
		}

		function ShowOnlyWidgetCheck($widget,$slug,$minisearch){
			$showonlywidget_check = 'show';
			
			$showonlywidget = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_showonlywidget','','0');
			$minisearchadm = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_minisearch','','0');
			$minisearchso = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_minisearchso','','0');
			
			if ($showonlywidget == 0 && $widget == 0) {
				$showonlywidget_check = 'show';
			}elseif ($showonlywidget == 1 && $widget == 0) {
				$showonlywidget_check = 'hide';
			}else{
				$showonlywidget_check = 'show';
			}

			if ($minisearch == 1 && $minisearchadm == 0) {
				$showonlywidget_check = 'hide';
			}elseif ($minisearch == 0 && $minisearchso == 1) {
				$showonlywidget_check = 'hide';
			}

			return $showonlywidget_check;
		}
		
		
		function GetValue($title,$slug,$ftype,$widget=0,$pfgetdata=array(),$hormode=0,$minisearch=0,$minisearchc=1){
			global $pfsearchfields_options;
			
			$showonlywidget_check = $this->ShowOnlyWidgetCheck($widget,$slug,$minisearch);

			$lang_custom = '';

			if(class_exists('SitePress')) {
				$lang_custom = $this->PF_current_language();
			}

			if ($hormode == 1) {
				$device_check = $this->pointfinder_device_check('isDesktop');								
			}

			switch($ftype){
				case '1':
				/* Select Box */
					if ($showonlywidget_check == 'show') {
						$target = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_rvalues_target_target','','');

						$parentshowonly = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_parentso','','0');
						$ajaxloads = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_ajaxloads','','0');


						$itemparent = $this->CheckItemsParent($target);
						/*Check element: is it a taxonomy?*/
						$rvalues_check = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_rvalues_check','','0');

						if ($rvalues_check == 0) {
							$fieldtaxname = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_posttax','','');
							$fieldtaxSelected = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_posttax_selected','','');
						}

						if($itemparent == 'none'){
							$validation_check = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_validation_required','','0');
							$multiple = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_multiple','','0');

							if ($multiple == 1 && ($widget == 1 || $minisearch == 1)) {
								$taxmultipletype = '[]';
							}else{
								$taxmultipletype = '';
							}

							if($validation_check == 1){
								$validation_message = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_message','','');
								if($this->VSOMessages != ''){
									$this->VSOMessages .= ',"'.$slug.$taxmultipletype.'":"'.$validation_message.'"';
								}else{
									$this->VSOMessages = '"'.$slug.$taxmultipletype.'":"'.$validation_message.'"';
								}
								
								if($this->VSORules != ''){
									$this->VSORules .= ',"'.$slug.$taxmultipletype.'":"required"';
								}else{
									$this->VSORules = '"'.$slug.$taxmultipletype.'":"required"';
								}
							}

							
							
							$select2_style = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_select2','','0');
							if($select2_style == 0){
								$select2sh = ', minimumResultsForSearch: -1';
							}else{ $select2sh = '';}
							
							$placeholder = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_placeholder','','');
							if($placeholder == ''){ $placeholder = esc_html__('Please select','pointfindercoreelements');};
							$nomatch = (isset($pfsearchfields_options['setupsearchfields_'.$slug.'_nomatch']))?$pfsearchfields_options['setupsearchfields_'.$slug.'_nomatch']:'';
							if($nomatch == ''){ $nomatch = '';};
							
							$column_type = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_column','','0');
							
							if($multiple == 1){ $multiplevar = 'multiple';}else{$multiplevar = '';};
							
							
							
							
							if($column_type == 1){
								if ($this->PFHalf % 2 == 0) {
									$this->FieldOutput .= '<div class="col6 last"><div id="'.$slug.'_main">';
								}else{
									if ($hormode == 1 && $widget == 0) {
										if ($device_check) {
											$this->FieldOutput .= '<div class="col-lg-3 col-md-4 col-sm-4 colhorsearch">';
										}else{
											$this->FieldOutput .= '<div class="col-lg-12 colhorsearch">';
										}
										
									}
									if ($hormode == 1 && $widget == 1 && $minisearch == 1) {
										$this->FieldOutput .= $this->GetMiniSearch($minisearchc);
									}
									$this->FieldOutput .= '<div class="row"><div class="col6 first"><div id="'.$slug.'_main">';
								}
								$this->PFHalf++;
							}else{
								if ($hormode == 1 && $widget == 0) {
									if ($device_check) {
										$this->FieldOutput .= '<div class="col-lg-3 col-md-4 col-sm-4 colhorsearch">';
									}else{
										$this->FieldOutput .= '<div class="col-lg-12 colhorsearch">';
									}
								}
								if ($hormode == 1 && $widget == 1 && $minisearch == 1) {
									$this->FieldOutput .= $this->GetMiniSearch($minisearchc);
								}
								$this->FieldOutput .= '<div id="'.$slug.'_main">';
							};


							/*/Begin to create Select Box*/
							if ($ajaxloads == 1 && $minisearch != 1 && $rvalues_check == 0 && ($fieldtaxname == 'pointfinderltypes' || $fieldtaxname == 'pointfinderlocations')) {
								if ($fieldtaxname == 'pointfinderltypes') {
									$this->ListingTypeField = $slug;
								}else{
									$this->LocationField = $slug;
								}
								$this->ScriptOutput .= '$("#'.$slug.'_sel").select2({dropdownCssClass:"pfselect2drop",containerCssClass:"pfselect2container",placeholder: "'.esc_js($placeholder).'", formatNoMatches:"'.esc_js($nomatch).'",allowClear: true'.$select2sh.'});$("#pf-resetfilters-button").on("click", function(event) {$("#'.$slug.'_sel").select2("val","");});';
							}else{
								$this->ScriptOutput .= '$("#'.$slug.'").select2({dropdownCssClass:"pfselect2drop",containerCssClass:"pfselect2container",placeholder: "'.esc_js($placeholder).'", formatNoMatches:"'.esc_js($nomatch).'",allowClear: true'.$select2sh.'});$("#pf-resetfilters-button").on("click", function(event) {$("#'.$slug.'").select2("val","");});';
							}
							

							$as_mobile_dropdowns = $this->PFSAIssetControl('as_mobile_dropdowns','','0');
							if ($as_mobile_dropdowns == 1) {
								if ($ajaxloads == 1 && $minisearch != 1 && $rvalues_check == 0 && ($fieldtaxname == 'pointfinderltypes' || $fieldtaxname == 'pointfinderlocations')) {
									$this->ScriptOutput .= 'if(!$.pf_tablet_check()){$("#'.$slug.'_sel").select2("destroy");}';
								}else{
									$this->ScriptOutput .= 'if(!$.pf_tablet_check()){$("#'.$slug.'").select2("destroy");}';
								}
							}

							$fieldtext = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_fieldtext','','');
							$this->FieldOutput .= '<div class="pftitlefield">'.$fieldtext.'</div>';
							if ($ajaxloads == 1 && $minisearch != 1 && $rvalues_check == 0 && ($fieldtaxname == 'pointfinderltypes' || $fieldtaxname == 'pointfinderlocations')) {
								$this->FieldOutput .= '<label for="'.$slug.'_sel" class="lbl-ui select">';
							}else{
								$this->FieldOutput .= '<label for="'.$slug.'" class="lbl-ui select">';
							}



							if ($as_mobile_dropdowns == 1) {
								$as_mobile_dropdowns_text = 'class="pf-special-selectbox" data-pf-plc="'.$placeholder.'" data-pf-stt="false"';
							} else {
								$as_mobile_dropdowns_text = '';
							}

							

							if ($ajaxloads == 1 && $minisearch != 1 && $rvalues_check == 0 && ($fieldtaxname == 'pointfinderltypes' || $fieldtaxname == 'pointfinderlocations')) {

								$item_defaultvalue_output = $sub_level = $sub_sub_level = $item_defaultvalue_output_orj = '';

								if ($fieldtaxname == 'pointfinderltypes') {
									if (!empty($this->ListingTypeField)) {
										if (!empty($pfgetdata[$this->ListingTypeField])) {
											$fieldtaxSelected = $pfgetdata[$this->ListingTypeField];
										}
									}
								}else{
									if (!empty($this->LocationField)) {
										if (!empty($pfgetdata[$this->LocationField])) {
											$fieldtaxSelected = $pfgetdata[$this->LocationField];
										}
									}
								}
								
								if (!empty($fieldtaxSelected)) {
									if (is_array($fieldtaxSelected) && count($fieldtaxSelected) > 1) {
										if (isset($fieldtaxSelected)) {
											$item_defaultvalue_output_orj = $fieldtaxSelected;
											$find_top_parent = $this->pf_get_term_top_most_parent($fieldtaxSelected,$fieldtaxname);

											$ci = 1;
											foreach ($fieldtaxSelected as $value) {
												$sub_level .= $value;
												if ($ci < count($fieldtaxSelected)) {
													$sub_level .= ',';
												}
												$ci++;
											}
											$item_defaultvalue_output = $find_top_parent['parent'];
										}
									}else{
										if (isset($fieldtaxSelected)) {
											$item_defaultvalue_output_orj = $fieldtaxSelected;
											$find_top_parent = $this->pf_get_term_top_most_parent($fieldtaxSelected,$fieldtaxname);

											switch ($find_top_parent['level']) {
												case '1':
													$sub_level = $fieldtaxSelected;
													break;
												
												case '2':
													$sub_sub_level = $fieldtaxSelected;
													$sub_level = $this->pf_get_term_top_parent($fieldtaxSelected,$fieldtaxname);
													break;
											}
											

											$item_defaultvalue_output = $find_top_parent['parent'];
										}
									}
								}

								$this->FieldOutput .= '<input type="hidden" name="'.$slug.'" id="'.$slug.'" value="'.$fieldtaxSelected.'"/>';
								if ($fieldtaxname == 'pointfinderltypes') {
									$cat_extra_opts = get_option('pointfinderltypes_covars');
								
									$subcatsarray = "var pfsubcatselect = [";
									$multiplesarray = "var pfmultipleselect = [";
								}
								$this->FieldOutput .= '<select '.$multiplevar.' id="'.$slug.'_sel" name="'.$slug.'_sel" '.$as_mobile_dropdowns_text.'>';
								
							
							}else{
								$this->FieldOutput .= '<select '.$multiplevar.' id="'.$slug.'" name="'.$slug.$taxmultipletype.'" '.$as_mobile_dropdowns_text.'>';
							}
							
							

							if($rvalues_check == 0){
							/* If this is a taxonomy */
								$fieldtaxname = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_posttax','','');
								$fieldtaxmove = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_posttax_move','','0');

								$mylat = $this->PFSAIssetControl('setup42_searchpagemap_lat','','');
								$mylng = $this->PFSAIssetControl('setup42_searchpagemap_lng','','');
								
								if($fieldtaxmove == 1 && $multiple != 1 && $widget == 0 && $ajaxloads != 1){ 
									/* If this is location and select move enabled. */
									/* GET - points per location */
									if ($this->LocationField == $slug) {
										$this->ScriptOutput .= '$( "#'.$slug.'" ).change(function(){
										
											if($( "#'.$slug.'" ).val() != ""){
													
												$.ajax({
													type: "POST",
													dataType: "JSON",
													url: theme_scriptspf.ajaxurl,
													data: { 
														"action": "pfget_taxpoint",
														"id": $( "#'.$slug.'" ).val(),
														"security": theme_scriptspf.pfget_taxpoint,
														"cl":theme_scriptspf.pfcurlang
													},
													success:function(data){
														if(data.lat != 0){
															if(typeof $.pointfinderdirectorymap != "undefined"){
																$.pointfinderdirectorymap.setView([data.lat,data.lng],$("#pfdirectorymap").data("zoom"));
															}else if(typeof $.pointfindernewmapsys != "undefined"){
																$.pointfindernewmapsys.setView([data.lat,data.lng],$("#wpf-map").data("zoom"));
															}
														}
													}
												});
												
											}else{
												if(typeof $.pointfinderdirectorymap != "undefined"){
													if($.pointfinderdirectorymap._lastCenter.lat != $("#pfdirectorymap").data("lat") && ((Math.round(parseFloat($.pointfinderdirectorymap._lastCenter.lat)) - Math.round(parseFloat($("#pfdirectorymap").data("lat")))) > 1 || (Math.round(parseFloat($.pointfinderdirectorymap._lastCenter.lat)) - Math.round(parseFloat($("#pfdirectorymap").data("lat")))) < -1)){
														$.pointfinderdirectorymap.setView([$("#pfdirectorymap").data("lat"),$("#pfdirectorymap").data("lng")],$("#pfdirectorymap").data("zoom"));
														$.pointfinderdirectorymap.fitBounds($.pointfindermarkers.getBounds(),{padding: [100,100]});
													}
												}

												if(typeof $.pointfindernewmapsys != "undefined"){
													if($.pointfindernewmapsys._lastCenter.lat != $("#wpf-map").data("lat") && ((Math.round(parseFloat($.pointfindernewmapsys._lastCenter.lat)) - Math.round(parseFloat($("#wpf-map").data("lat")))) > 1 || (Math.round(parseFloat($.pointfindernewmapsys._lastCenter.lat)) - Math.round(parseFloat($("#wpf-map").data("lat")))) < -1)){console.log("here2");
														$.pointfindernewmapsys.setView([$("#wpf-map").data("lat"),$("#wpf-map").data("lng")],$("#wpf-map").data("zoom"));
														$.pointfindernewmapsys.fitBounds($.pointfindermarkers.getBounds(),{padding: [100,100]});
													}
												}
											}
											
											});
										';
									}
								}
								$process = 'ok';
								if($fieldtaxname != ''){

									$setup4_sbf_c1 = $this->PFSAIssetControl('setup4_sbf_c1','','1');
									$stp_syncs_it = $this->PFSAIssetControl('stp_syncs_it','',1);
									$stp_syncs_co = $this->PFSAIssetControl('stp_syncs_co','',1);
									
									if ($fieldtaxname == 'pointfinderfeatures') {
										if ($setup4_sbf_c1 == 0) {
											$process = 'not';
										}
									}

									if ($fieldtaxname == 'pointfinderitypes') {
										if ($stp_syncs_it == 0) {
											$process = 'not';
											if ($ajaxloads == 1 && $minisearch != 1) {
												$this->ScriptOutput .= '$(function(){$( "#'.$slug.'_main" ).hide();});';
											}
										}
									}

									if ($fieldtaxname == 'pointfinderconditions') {
										if ($stp_syncs_co == 0) {
											$process = 'not';
											if ($ajaxloads == 1 && $minisearch != 1) {
												$this->ScriptOutput .= '$(function(){$( "#'.$slug.'_main" ).hide();});';
											}
										}
									}
									
									if ($ajaxloads == 1 && $minisearch != 1) {$parentshowonly = 1;}
									
									/*Select tax auto on cat page*/

									if (isset($pfgetdata['pointfinderltypes'])) {
																		
										if ($widget == 1 && !empty($pfgetdata['pointfinderltypes'])) {
											
											global $wp_query;
											if(isset($wp_query->query_vars['taxonomy'])){
												$taxonomy_name = $wp_query->query_vars['taxonomy'];
												
												if ($taxonomy_name == 'pointfinderltypes') {
													
													$term_slug = $wp_query->query_vars['term'];
									
													$term_name = get_term_by('slug', $term_slug, $taxonomy_name,'ARRAY_A');
													
													$fieldtaxSelected = $term_name['term_id'];

												}
											}
										}
									}

									/*Select tax auto on search page*/
									$fltf = $this->pointfinder_find_requestedfields('pointfinderltypes');
									if ($fltf != 'none') {
										if (isset($pfgetdata[$fltf])) {
																			
											if (!empty($pfgetdata[$fltf])) {
												
												$fieldtaxSelected = $pfgetdata[$fltf];
											}
										}
									}
										
									if ($process == 'ok') {
										$fieldvalues = get_terms($fieldtaxname,array('hide_empty'=>false)); 
										
										$this->FieldOutput .= '	<option></option>';

										if ($multiple == 1 ){
											$this->FieldOutput .= '<optgroup disabled hidden></optgroup>';
										}

										foreach( $fieldvalues as $parentfieldvalue){
											if($parentfieldvalue->parent == 0){

													if ($fieldtaxname == 'pointfinderltypes') {
														/* Multiple select & Subcat Select */
														$multiple_select = (isset($cat_extra_opts[$parentfieldvalue->term_id]['pf_multipleselect']))?$cat_extra_opts[$parentfieldvalue->term_id]['pf_multipleselect']:2;
														$subcat_select = (isset($cat_extra_opts[$parentfieldvalue->term_id]['pf_subcatselect']))?$cat_extra_opts[$parentfieldvalue->term_id]['pf_subcatselect']:2;

														if ($multiple_select == 1) {$multiplesarray .= $parentfieldvalue->term_id.',';}
														if ($subcat_select == 1) {$subcatsarray .= $parentfieldvalue->term_id.',';}
													}
												
													$mypf_text = '';
													
													if (empty($parentshowonly)) {
														$tax_class_text = 'class="pfoptheader"';
													}else{$tax_class_text = '';}

													if($fieldtaxSelected != ''){
														if (!empty($item_defaultvalue_output)) {
															if(strcmp($item_defaultvalue_output,$parentfieldvalue->term_id) == 0){$mypf_text = " selected";}
														}else{
															if(strcmp($fieldtaxSelected,$parentfieldvalue->term_id) == 0){$mypf_text = " selected";}
														}
														
													}

													if (isset($pfgetdata[$slug]) && ($widget == 1 || $minisearch == 1)) {
														if (is_array($pfgetdata[$slug])) {
															if (in_array($parentfieldvalue->term_id, $pfgetdata[$slug])) {
																$mypf_text = " selected";
															}
														}else{
															if ($parentfieldvalue->term_id == $pfgetdata[$slug]) {
																$mypf_text = " selected";
															}
														}
													}

													$this->FieldOutput .= '<option value="'.$parentfieldvalue->term_id.'"'.$mypf_text.' '.$tax_class_text.'>'.$parentfieldvalue->name.'</option>';
													
													if (empty($parentshowonly)) {
														foreach( $fieldvalues as $fieldvalue){
															if($fieldvalue->parent == $parentfieldvalue->term_id){

																if($fieldtaxSelected != ''){

																	if(strcmp($fieldtaxSelected,$fieldvalue->term_id) == 0){ $fieldtaxSelectedValue = 1;}else{ $fieldtaxSelectedValue = 0;}
																}else{
																	$fieldtaxSelectedValue = 0;
																}

																
																$tax_normal_output = '<option value="'.$fieldvalue->term_id.'">&nbsp;&nbsp;&nbsp;&nbsp;'.$fieldvalue->name.'</option>';
																$tax_selected_output = '<option value="'.$fieldvalue->term_id.'" selected>&nbsp;&nbsp;&nbsp;&nbsp;'.$fieldvalue->name.'</option>';

																if($fieldtaxSelectedValue == 1 && $widget == 0 && !isset($pfgetdata[$slug])){
																	$this->FieldOutput .= $tax_normal_output;
																}else{
																	if (array_key_exists($slug,$pfgetdata)) {
																		if (isset($pfgetdata[$slug])) {
																			if (is_array($pfgetdata[$slug])) {
																				if (in_array($fieldvalue->term_id, $pfgetdata[$slug])) {
																					$tax_normal_output = $tax_selected_output;
																				}
																			}else{
																				if ($fieldvalue->term_id == $pfgetdata[$slug]) {
																					$tax_normal_output = $tax_selected_output;
																				}else{
																					if ($fieldtaxSelectedValue == 1) {
																						$tax_normal_output = $tax_selected_output;
																					}
																				}
																			}
																			
																		}
																		
																	}
																	$this->FieldOutput .= $tax_normal_output;
																}



																$has_this_term_children = get_term_children( $fieldvalue->term_id, $fieldtaxname );

																if (count($has_this_term_children) > 0) {

																	foreach( $fieldvalues as $fieldvalues2){

																		if($fieldvalues2->parent == $fieldvalue->term_id){
																			if($fieldtaxSelected != ''){
																				if(strcmp($fieldtaxSelected,$fieldvalues2->term_id) == 0){ $fieldtaxSelectedValues2 = 1;}else{ $fieldtaxSelectedValues2 = 0;}
																			}else{
																				$fieldtaxSelectedValues2 = 0;
																			}

																			$subtax_normal_output = '<option value="'.$fieldvalues2->term_id.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$fieldvalues2->name.'</option>';
																			$subtax_selected_output = '<option value="'.$fieldvalues2->term_id.'" selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$fieldvalues2->name.'</option>';

																			if($fieldtaxSelectedValues2 == 1 && $widget == 0 && !isset($pfgetdata[$slug])){
																				$this->FieldOutput .= $subtax_normal_output;
																			}else{
																				if (array_key_exists($slug,$pfgetdata)) {
																					if (isset($pfgetdata[$slug])) {

																						if (is_array($pfgetdata[$slug])) {
																							if (in_array($fieldvalue2->term_id, $pfgetdata[$slug])) {
																								$subtax_normal_output = $subtax_selected_output;
																							}
																						}else{
																							if ($fieldvalues2->term_id == $pfgetdata[$slug]) {
																								$subtax_normal_output = $subtax_selected_output;
																							}else{
																								if ($fieldtaxSelectedValue == 1) {
																									$subtax_normal_output = $subtax_selected_output;
																								}
																							}
																						}
																					}
																				}
																				$this->FieldOutput .= $subtax_normal_output;
																			}
																		}
																	}
																}



															}
														}
													}
													
											}
										}

									}else{
										$this->FieldOutput .= '	<option></option>';
									}
									


								}
							}elseif($rvalues_check == 1){
							/* If not a taxonomy */

								$rvalues = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_rvalues','','');

								if(count($rvalues) > 0){$fieldvalues = $rvalues;}else{$fieldvalues = '';}/* Get element's custom values.*/

								if(count($fieldvalues) > 0){
									
									$this->FieldOutput .= '	<option></option>';

									$ikk = 0;
									foreach ($fieldvalues as $s) { 

										if (class_exists('SitePress')) {
											//$s = icl_t('admin_texts_pfsearchfields_options', '[pfsearchfields_options][setupsearchfields_'.$slug.'_rvalues]'.$ikk, $s);
											$s = apply_filters( 'wpml_translate_single_string', $s, 'admin_texts_pfsearchfields_options', '[pfsearchfields_options][setupsearchfields_'.$slug.'_rvalues]'.$ikk );

										}

										if ($pos = strpos($s, '=')) { 

											$tax_normal_output = '<option value="'.trim(substr($s, 0, $pos)).'">'.trim(substr($s, $pos + strlen('='))).'</option>';
											$tax_selected_output = '<option value="'.trim(substr($s, 0, $pos)).'" selected>'.trim(substr($s, $pos + strlen('='))).'</option>';

											if($widget == 1){

												if (array_key_exists($slug,$pfgetdata)) {
													if (isset($pfgetdata[$slug])) {
														if (is_array($pfgetdata[$slug])) {
															if (in_array(trim(substr($s, 0, $pos)), $pfgetdata[$slug])) {
																$tax_normal_output = $tax_selected_output;
															}
														}else{
															if (trim(substr($s, 0, $pos)) == $pfgetdata[$slug]) {
																$tax_normal_output = $tax_selected_output;
															}
														}
													}
												}
											}
											$this->FieldOutput .= $tax_normal_output;

										}
										$ikk++;
									}
								}
							}

							$this->FieldOutput .= '</select>';
							$this->FieldOutput .= '</label>';

							if ($ajaxloads == 1 && $minisearch != 1 && $rvalues_check == 0 && ($fieldtaxname == 'pointfinderltypes' || $fieldtaxname == 'pointfinderlocations')) {
								
								if ($fieldtaxname == 'pointfinderlocations') {
									$this->FieldOutput .= '<div class="pf-sub-locations-container"></div>';
									$stp4_sublotyp_title = $this->PFSAIssetControl('stp4_sublotyp_title','',esc_html__('Sub Location', 'pointfindercoreelements'));
									$stp4_subsublotyp_title = $this->PFSAIssetControl('stp4_subsublotyp_title','',esc_html__('Sub Sub Location', 'pointfindercoreelements'));

									$this->ScriptOutput .= "
									/* Start: Function for sub location types */
										$.pf_get_sublocations = function(itemid,defaultv){
											$.ajax({
										    	beforeSend:function(){
										    		$('.pfsearch-content').pfLoadingOverlay({action:'show',opacity:0.5});
										    	},
												url: theme_scriptspf.ajaxurl,
												type: 'POST',
												dataType: 'html',
												data: {
													action: 'pfget_listingtype',
													id: itemid,
													default: defaultv,
													sname: 'pfupload_sublocations',
													stext: '".$stp4_sublotyp_title."',
													stype: 'locations',
													stax: 'pointfinderlocations',
													lang: '".$lang_custom."',
													security: '".wp_create_nonce('pfget_listingtype')."'
												},
											}).success(function(obj) {
												$('.pf-sub-locations-container').append('<div class=\'pfsublocations\'>'+obj+'</div>');
												if (obj != '') {
													if ($.pf_tablet_check()) {
														$('#pfupload_sublocations').select2({
															dropdownCssClass:'pfselect2drop',containerCssClass:'pfselect2container',
															placeholder: '".esc_html__('Please select','pointfindercoreelements')."', 
															formatNoMatches:'".esc_html__('No match found','pointfindercoreelements')."',
															allowClear: true, 
															minimumResultsForSearch: 10
														});
													}";

													if (empty($sub_sub_level)) {
													$this->ScriptOutput .= "
														$.pf_get_subsublocations($('#pfupload_sublocations').val(),'');
													";
													}

													
													$this->ScriptOutput .= "
													$('#pfupload_sublocations').change(function(){
														if($(this).val() != 0 && $(this).val() != null){
															$('#".$slug."').val($(this).val()).trigger('change');
															$.pf_get_subsublocations($(this).val(),'');
														}else{
															$('#".$slug."').val($('#".$slug."_sel').val());
														}
														$('.pfsubsublocations').remove();
													});
												}
											}).complete(function(obj,obj2){
												if (obj.responseText != '') {
													if (defaultv != '') {
														$('#".$slug."').val(defaultv).trigger('change');
													}else{
														$('#".$slug."').val(itemid).trigger('change');
													}";
																
													if (!empty($sub_sub_level)) {
														$this->ScriptOutput .= "
														if (".$sub_level." == $('#pfupload_sublocations').val()) {
															$.pf_get_subsublocations('".$sub_level."','".$sub_sub_level."');
														}
														";
													}
													$this->ScriptOutput .= "
												}
												setTimeout(function(){
													$('.pfsearch-content').pfLoadingOverlay({action:'hide'});
												},1000);
												
												
											});
										}


										$.pf_get_subsublocations = function(itemid,defaultv){
											$.ajax({
										    	beforeSend:function(){
										    		$('.pfsearch-content').pfLoadingOverlay({action:'show',opacity:0.5});
										    	},
												url: theme_scriptspf.ajaxurl,
												type: 'POST',
												dataType: 'html',
												data: {
													action: 'pfget_listingtype',
													id: itemid,
													default: defaultv,
													sname: 'pfupload_subsublocations',
													stext: '".$stp4_subsublotyp_title."',
													stype: 'locations',
													stax: 'pointfinderlocations',
													lang: '".$lang_custom."',
													security: '".wp_create_nonce('pfget_listingtype')."'
												},
											}).success(function(obj) {
												$('.pf-sub-locations-container').append('<div class=\'pfsubsublocations\'>'+obj+'</div>');
												if ($.pf_tablet_check()) {
													$('#pfupload_subsublocations').select2({
														dropdownCssClass:'pfselect2drop',containerCssClass:'pfselect2container',
														placeholder: '".esc_html__('Please select','pointfindercoreelements')."', 
														formatNoMatches:'".esc_html__('No match found','pointfindercoreelements')."',
														allowClear: true, 
														minimumResultsForSearch: 10
													});
												}
													


													$('#pfupload_subsublocations').change(function(){
														if($(this).val() != 0){
															$('#".$slug."').val($(this).val()).trigger('change');
														}else{
															$('#".$slug."').val($('#pfupload_sublocations').val())
														}
													});

											}).complete(function(obj,obj2){
												if (obj.responseText != '') {
													if (defaultv != '') {
														$('#".$slug."').val(defaultv).trigger('change');
													}else{
														$('#".$slug."').val(itemid).trigger('change');
													}
												}
												setTimeout(function(){
													$('.pfsearch-content').pfLoadingOverlay({action:'hide'});
												},1000);
											});
										}

									/* End: Function for sub location types */
									";
									$this->ScriptOutput .= "
									$('#".$slug."_sel').change(function(){
										$('.pf-sub-locations-container').html('');
										$('#".$slug."').val($(this).val()).trigger('change');
										$.pf_get_sublocations($(this).val(),'');
									});
									";
								}

								if ($fieldtaxname == 'pointfinderltypes') {
									$subcatsarray .= "];";
									$multiplesarray .= "];";

									$this->ScriptOutput .= $subcatsarray . $multiplesarray;

									$this->FieldOutput .= '<div class="pf-sub-listingtypes-container"></div>';

									$setup4_submitpage_listingtypes_title = $this->PFSAIssetControl('setup4_submitpage_listingtypes_title','','Listing Type');
									$setup4_submitpage_sublistingtypes_title = $this->PFSAIssetControl('setup4_submitpage_sublistingtypes_title','','Sub Listing Type');
									$setup4_submitpage_subsublistingtypes_title = $this->PFSAIssetControl('setup4_submitpage_subsublistingtypes_title','','Sub Sub Listing Type');
									$setup4_submitpage_listingtypes_verror = $this->PFSAIssetControl('setup4_submitpage_listingtypes_verror','','Please select a listing type.');
									$stp4_forceu_cs = $this->PFSAIssetControl('stp4_forceu_cs','',0);
								

									$this->ScriptOutput .= "
										$.pf_get_sublistingtypes = function(itemid,defaultv){
											if (jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfmultipleselect) != -1) {
												var multiple_ex = 1;
											}else{
												var multiple_ex = 0;
											}
											$.ajax({
										    	beforeSend:function(){
										    		$('.pfsearch-content').pfLoadingOverlay({action:'show',opacity:0.5});
										    	},
												url: theme_scriptspf.ajaxurl,
												type: 'POST',
												dataType: 'html',
												data: {
													action: 'pfget_listingtype',
													id: itemid,
													default: defaultv,
													sname: 'pfupload_sublistingtypes',
													stext: '".$setup4_submitpage_sublistingtypes_title."',
													stype: 'listingtypes',
													stax: 'pointfinderltypes',
													lang: '".$lang_custom."',
													multiple: multiple_ex,
													security: '".wp_create_nonce('pfget_listingtype')."'
												},
											}).success(function(obj) {
												
												$('.pf-sub-listingtypes-container').append('<div class=\'pfsublistingtypes\'>'+obj+'</div>');

												if (obj != '') {
													";
													
													if ($stp4_forceu_cs == 1) {
														$this->ScriptOutput .= "$('#pfupload_sublistingtypes').rules('add',{
															required: true,
															messages:{required:'".$setup4_submitpage_listingtypes_verror."'
														}});";
													}
													
													$this->ScriptOutput .= "

													if ($.pf_tablet_check()) {
														$('#pfupload_sublistingtypes').select2({
															dropdownCssClass:'pfselect2drop',containerCssClass:'pfselect2container',
															placeholder: '".esc_html__("Please select",'pointfindercoreelements')."', 
															formatNoMatches:'".esc_html__("No match found",'pointfindercoreelements')."',
															allowClear: true, 
															minimumResultsForSearch: 10
														});
													}
													$.pf_data_pfpcl_apply();
													";

													if (empty($sub_sub_level)) {
													$this->ScriptOutput .= " if ($('#pfupload_sublistingtypes').val() != 0 && (jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfmultipleselect) == -1)) {
														$.pf_get_subsublistingtypes($('#pfupload_sublistingtypes').val(),'');
													}";
													}

													
													$this->ScriptOutput .= "
													$('#pfupload_sublistingtypes').change(function(){
														if($(this).val() != 0 && $(this).val() != null){
															if ((jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfsubcatselect) == -1) && (jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfmultipleselect) == -1)) {
																$('#".$slug."').val($(this).val()).trigger('change');
															}else{
																$('#".$slug."').val($(this).val());
															}
															if ((jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfmultipleselect) == -1)) {
																$.pf_get_subsublistingtypes($(this).val(),'');
															}
														}else{
															if ((jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfsubcatselect) == -1) && (jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfmultipleselect) == -1)) {
																$('#".$slug."').val($('#".$slug."_sel').val());
															}else{
																$('#".$slug."').val($('#".$slug."_sel').val()).trigger('change');
															}
															
														}
														$('.pfsubsublistingtypes').remove();
													});
												}

											}).complete(function(obj,obj2){
												if (obj.responseText != '') {

													if (defaultv != '') {
														if ((jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfsubcatselect) == -1) && (jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfmultipleselect) == -1)) {
															$('#".$slug."').val(defaultv).trigger('change');
														}else{
															$('#".$slug."').val(defaultv);
														}
													}else{
														
														if ((jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfsubcatselect) == -1) && (jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfmultipleselect) == -1)) {
															$('#".$slug."').val(itemid).trigger('change');
														}else{
															$('#".$slug."').val(itemid);
														}
													}
													";
													
													if (!empty($sub_sub_level)) {
														$this->ScriptOutput .= "
														if (".$sub_level." == $('#pfupload_sublistingtypes').val()) {
															$.pf_get_subsublistingtypes('".$sub_level."','".$sub_sub_level."');
														}
														";
													}

												$this->ScriptOutput .= "
												}
												setTimeout(function(){
													$('.pfsearch-content').pfLoadingOverlay({action:'hide'});
												},1000);
											});
										}

										$.pf_get_subsublistingtypes = function(itemid,defaultv){
											$.ajax({
										    	beforeSend:function(){
										    		$('.pfsearch-content').pfLoadingOverlay({action:'show',opacity:0.5});
										    	},
												url: theme_scriptspf.ajaxurl,
												type: 'POST',
												dataType: 'html',
												data: {
													action: 'pfget_listingtype',
													id: itemid,
													default: defaultv,
													sname: 'pfupload_subsublistingtypes',
													stext: '".$setup4_submitpage_subsublistingtypes_title."',
													stype: 'listingtypes',
													stax: 'pointfinderltypes',
													lang: '".$lang_custom."',
													security: '".wp_create_nonce('pfget_listingtype')."'
												},
											}).success(function(obj) {
												$('.pf-sub-listingtypes-container').append('<div class=\'pfsubsublistingtypes\'>'+obj+'</div>');
												if (obj != '') {
													";

													if ($stp4_forceu_cs == 1) {
														$this->ScriptOutput .= "$('#pfupload_subsublistingtypes').rules('add',{required: true,messages:{required:'".$setup4_submitpage_listingtypes_verror."'}});";
													}
													$this->ScriptOutput .= "
													if ($.pf_tablet_check()) {
														$('#pfupload_subsublistingtypes').select2({
															dropdownCssClass:'pfselect2drop',containerCssClass:'pfselect2container',
															placeholder: '".esc_html__('Please select','pointfindercoreelements')."', 
															formatNoMatches:'".esc_html__('No match found','pointfindercoreelements')."',
															allowClear: true, 
															minimumResultsForSearch: 10
														});
													}
													
													$.pf_data_pfpcl_apply();


													$('#pfupload_subsublistingtypes').change(function(){
														if($('#pfupload_subsublistingtypes').val() != 0){
															
															if ((jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfsubcatselect) == -1) && (jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfmultipleselect) == -1)) {
																$('#".$slug."').val($(this).val()).trigger('change');
															}else{
																$('#".$slug."').val($(this).val());
															}

														}else{
															
															if ((jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfsubcatselect) == -1) && (jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfmultipleselect) == -1)) {
																$('#".$slug."').val($('#pfupload_sublistingtypes').val()).trigger('change');
															}else{
																$('#".$slug."').val($('#pfupload_sublistingtypes').val());
															}
														}
													});
												}

											}).complete(function(obj,obj2){
												if (obj.responseText != '') {

													if (defaultv != '') {
														
														if ((jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfsubcatselect) == -1) && (jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfmultipleselect) == -1)) {
															$('#".$slug."').val(defaultv).trigger('change');
														}else{
															$('#".$slug."').val(defaultv);
														}
													}else{
														
														if ((jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfsubcatselect) == -1) && (jQuery.inArray(parseInt($('#".$slug."_sel').val()),pfmultipleselect) == -1)) {
															$('#".$slug."').val(itemid).trigger('change');
														}else{
															$('#".$slug."').val(itemid);
														}
													}
												}
												setTimeout(function(){
													$('.pfsearch-content').pfLoadingOverlay({action:'hide'});
												},1000);
											});
										}


										$('#".$slug."_sel').change(function(){

											$('.pf-sub-listingtypes-container').html('');

											$('#".$slug."').val($(this).val()).trigger('change');

											$.pf_get_sublistingtypes($(this).val(),'');

										});
									";

									$this->ScriptOutput .= "$(function(){
									$.pf_get_sublistingtypes($('#".$slug."_sel').val(),'".$sub_level."');
									";
									if (empty($sub_sub_level) && !empty($sub_level)) {
										$this->ScriptOutput .= "$('#".$slug."').val('".$sub_level."');";
									}
									$this->ScriptOutput .= "});";
								}
							}

							
							
							if($column_type == 1){
								if ($this->PFHalf % 2 == 0) {
									$this->FieldOutput .= '</div></div>';
								}else{
									if (($hormode == 1 && $widget == 0) || ($hormode == 1 && $widget == 1 && $minisearch == 1)) {
										$this->FieldOutput .= '</div>';
									}
									$this->FieldOutput .= '</div></div></div>';
								}
							}else{
								if (($hormode == 1 && $widget == 0) || ($hormode == 1 && $widget == 1 && $minisearch == 1)) {
									$this->FieldOutput .= '</div>';
								}
								$this->FieldOutput .= '</div>';
							};

							
						}/*Parent Check*/
					}
					break;
				
				case '2':
				/* Slider Field */
					if ($showonlywidget_check == 'show') {
						
						$target = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_target','','');

						$itemparent = $this->CheckItemsParent($target);
						
						if($itemparent == 'none'){								
							
							$fieldtext = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_fieldtext','','');

							//Check price item
							$itempriceval = $this->PriceFieldCheck($target);
							
							
							//Check size item
							$itemsizeval = $this->SizeFieldCheck($target);
								
							// Get slider type.
							$slidertype = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_type','','');
							if($slidertype == 'range'){ $slidertype = 'true';}


							//Min value, max value, steps, color
							$fmin = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_min','','0');
							$fmax = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_max','','1000000');
							$fsteps = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_steps','','1');
							$fcolor = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_colorslider','','#3D637C');
							$fcolor2 = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_colorslider2','','#444444');
							$svalue = '';
							
							$slidertypetext = $valuestext = '';
							if (array_key_exists($slug,$pfgetdata)) {
								if($slidertype == 'true'){ 
									$valuestext = 'values:'.'['.$pfgetdata[$slug].'],'; 
									$slidertypetext = 'range: '.$slidertype.',';
								}
								if($slidertype == 'min'){ 
									$valuestext = 'value:'.$pfgetdata[$slug].',';
									$slidertypetext = 'range: \''.$slidertype.'\',';
								}
								if($slidertype == 'max'){ 
									$valuestext = 'value:'.$pfgetdata[$slug].',';
									$slidertypetext = 'range: \''.$slidertype.'\',';
								}
							}else{
								if($slidertype == 'true'){ 
									$valuestext = 'values:'.'['.$fmin.','.$fmax.'],'; 
									$slidertypetext = 'range: '.$slidertype.',';
								}
								if($slidertype == 'min'){ 
									$valuestext = 'value:'.$fmin.',';
									$slidertypetext = 'range: \''.$slidertype.'\',';
								}
								if($slidertype == 'max'){ 
									$valuestext = 'value:'.$fmax.',';
									$slidertypetext = 'range: \''.$slidertype.'\',';
								}
							}
							
							if($itempriceval != 'none'){
								$suffixtext = '+"'.$itempriceval['CFSuffix'].'"';
								$suffixtext2 = '+" - "';
								$prefixtext = '"'.$itempriceval['CFPrefix'].'"+';
								$prefixtext2 = '+"'.$itempriceval['CFPrefix'].'"+';
								$prefixtext3 = $itempriceval['CFPrefix'];
							}elseif($itemsizeval != 'none'){
								$suffixtext = '+"'.$itemsizeval['CFSuffix'].'"';
								$suffixtext2 = '+" - "';
								$prefixtext = '"'.$itemsizeval['CFPrefix'].'"+';
								$prefixtext2 = '+"'.$itemsizeval['CFPrefix'].'"+';
								$prefixtext3 = $itemsizeval['CFPrefix'];
							}else{
								$suffixtext = '';
								$suffixtext2 = '" - "';
								$prefixtext = '';
								$prefixtext2 = '';
								$prefixtext3 = '';
							}
							
							//Create script for this slider.
							$slideroptions = '{'.$slidertypetext.''.$valuestext.'min: '.esc_js($fmin).',max: '.esc_js($fmax).',step: '.esc_js($fsteps).',slide: function(event, ui) {';
										
							$slideroptions .= '$("#'.$slug.'-view").';
							if($slidertype == 'true'){
								if($itempriceval != 'none'){
									$slideroptions .='val('.$prefixtext.' number_format(ui.values[0], '.$itempriceval['CFDecima'].', "'.$itempriceval['CFDecimp'].'", "'.$itempriceval['CFDecimt'].'") + " - '.$prefixtext3.'" + number_format(ui.values[1], '.$itempriceval['CFDecima'].', "'.$itempriceval['CFDecimp'].'", "'.$itempriceval['CFDecimt'].'") '.$suffixtext.');';
									
									
								}elseif($itemsizeval != 'none'){
									$slideroptions .='val('.$prefixtext.' number_format(ui.values[0], '.$itemsizeval['CFDecima'].', "'.$itemsizeval['CFDecimp'].'", "'.$itemsizeval['CFDecimt'].'") + " - '.$prefixtext3.'" + number_format(ui.values[1], '.$itemsizeval['CFDecima'].', "'.$itemsizeval['CFDecimp'].'", "'.$itemsizeval['CFDecimt'].'")  '.$suffixtext.');';
									
								}else{
									$slideroptions  .='val(ui.values[0] + " - " + ui.values[1]);';
									
								}
							}else{
								if($itempriceval != 'none'){
									$slideroptions .='val('.$prefixtext.' number_format(ui.value, "'.$itempriceval['CFDecima'].'", "'.$itempriceval['CFDecimp'].'", "'.$itempriceval['CFDecimt'].'") '.$suffixtext.');';
									
								}elseif($itemsizeval != 'none'){
									//$slideroptions  .='val('.$prefixtext.' ui.value '.$suffixtext.');';
									$slideroptions .='val('.$prefixtext.' number_format(ui.value, "'.$itemsizeval['CFDecima'].'", "'.$itemsizeval['CFDecimp'].'", "'.$itemsizeval['CFDecimt'].'") '.$suffixtext.');';
									
								}else{
									$slideroptions .='val(ui.value);';
									
								}
							}
							
							$slideroptions .= '$("#'.$slug.'-view2").';
							if($slidertype == 'true'){
								$slideroptions .='val(ui.values[0]+","+ui.values[1]);';
							}else{
								$slideroptions .='val(ui.value);';
							}
							
							$slideroptions .='}}';
							
							$this->ScriptOutput .= '$( "#'.$slug.'" ).slider('.$slideroptions.');';


							$this->ScriptOutput .= '
							$("#pf-resetfilters-button").on("click", function(event) {
								$("#'.$slug.'-view2").val("");
								$( "'.$slug.'" ).slider( "destroy" );
								$( "#'.$slug.'" ).slider('.$slideroptions.');
							});
							';
							
							$this->ScriptOutput .='$( "#'.$slug.'" ).addClass("ui-slider-'.$slug.'");';
							
							if($slidertype == 'true'){
								if($itempriceval != 'none'){
									$this->ScriptOutput .='$("#'.$slug.'-view").val('.$prefixtext.' number_format($("#'.$slug.'").slider("values",0), '.$itempriceval['CFDecima'].', "'.$itempriceval['CFDecimp'].'", "'.$itempriceval['CFDecimt'].'") '.$suffixtext2.''.$prefixtext2.'number_format($("#'.$slug.'").slider("values",1), '.$itempriceval['CFDecima'].', "'.$itempriceval['CFDecimp'].'", "'.$itempriceval['CFDecimt'].'") '.$suffixtext.');';
								}elseif($itemsizeval != 'none'){
									$this->ScriptOutput .='$("#'.$slug.'-view").val('.$prefixtext.' number_format($("#'.$slug.'").slider("values", 0), '.$itemsizeval['CFDecima'].', "'.$itemsizeval['CFDecimp'].'", "'.$itemsizeval['CFDecimt'].'")  '.$suffixtext2.''.$prefixtext2.' number_format($("#'.$slug.'").slider("values", 1), '.$itemsizeval['CFDecima'].', "'.$itemsizeval['CFDecimp'].'", "'.$itemsizeval['CFDecimt'].'") '.$suffixtext.');';
								}else{
									$this->ScriptOutput .='$("#'.$slug.'-view").val($("#'.$slug.'").slider("values", 0) + " - " + $("#'.$slug.'").slider("values", 1));';
								}
							}else{
								if($itempriceval != 'none'){
									$this->ScriptOutput .='$("#'.$slug.'-view").val( '.$prefixtext.' number_format($("#'.$slug.'").slider("value"), '.$itempriceval['CFDecima'].', "'.$itempriceval['CFDecimp'].'", "'.$itempriceval['CFDecimt'].'") '.$suffixtext.');';
								}elseif($itemsizeval != 'none'){
									$this->ScriptOutput .='$("#'.$slug.'-view").val( '.$prefixtext.' number_format($("#'.$slug.'").slider("value"), '.$itemsizeval['CFDecima'].', "'.$itemsizeval['CFDecimp'].'", "'.$itemsizeval['CFDecimt'].'") '.$suffixtext.');';
								}else{
									$this->ScriptOutput .='$("#'.$slug.'-view").val( $("#'.$slug.'").slider("value"));';
								}
							}
							
							
							$this->ScriptOutputDocReady .= '$(document).one("ready",function(){$.pfsliderdefaults.fields["'.$slug.'_main"] = $("#'.$slug.'-view").val()});';
							
							$column_type = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_column','','0');						
							
							

							if($column_type == 1){
								if ($this->PFHalf % 2 == 0) {
									$this->FieldOutput .= '<div class="col6 last">';
								}else{
									if ($hormode == 1 && $widget == 0) {
										if ($device_check) {
											$this->FieldOutput .= '<div class="col-lg-3 col-md-4 col-sm-4 colhorsearch">';
										}else{
											$this->FieldOutput .= '<div class="col-lg-12 colhorsearch">';
										}
									}
									if ($hormode == 1 && $widget == 1 && $minisearch == 1) {
										$this->FieldOutput .= $this->GetMiniSearch($minisearchc);
									}
									$this->FieldOutput .= '<div class="row"><div class="col6 first">';
								}
								$this->PFHalf++;
							}else{
								if ($hormode == 1 && $widget == 0) {
									if ($device_check) {
										$this->FieldOutput .= '<div class="col-lg-3 col-md-4 col-sm-4 colhorsearch">';
									}else{
										$this->FieldOutput .= '<div class="col-lg-12 colhorsearch">';
									}
								}
								if ($hormode == 1 && $widget == 1 && $minisearch == 1) {
									$this->FieldOutput .= $this->GetMiniSearch($minisearchc);
								}
							};
							
								//Slider size calculate
								if(strlen($fmax) <=3){
									$slidersize = ((strlen($fmax)*8))+4;
								}else{
									if($suffixtext != ''){
										$slidersize = ((strlen($fmax)*8)*2)+70;
									}else{
										$slidersize = ((strlen($fmax)*8)*2)+50;
									}
								}
								//Output for this field
								$this->FieldOutput .= ' <div id="'.$slug.'_main"><label for="'.$slug.'-view" class="pfrangelabel">'.$fieldtext.'</label><input type="text" id="'.$slug.'-view" class="slider-input" style="width:'.$slidersize.'px" disabled>';
								$this->FieldOutput .= '<input name="'.$slug.'" id="'.$slug.'-view2" type="hidden" class="pfignorevalidation" value="">';
								$this->FieldOutput .= ' <div class="slider-wrapper"><div id="'.$slug.'"></div>  </div></div>';
								
							if($column_type == 1){
								if ($this->PFHalf % 2 == 0) {
									$this->FieldOutput .= '</div>';
								}else{
									if (($hormode == 1 && $widget == 0) || ($hormode == 1 && $widget == 1 && $minisearch == 1)) {
										$this->FieldOutput .= '</div>';
									}
									$this->FieldOutput .= '</div></div>';
								}
							}else{
								if (($hormode == 1 && $widget == 0) || ($hormode == 1 && $widget == 1 && $minisearch == 1)) {
									$this->FieldOutput .= '</div>';
								}
							};

							
							if (array_key_exists($slug,$pfgetdata)) {
								$this->ScriptOutput .= '$( "#'.$slug.'-view2" ).val("'.$pfgetdata[$slug].'");';
							}
						}
					}
					break;
				
				case '4':
				/* Text Field */
					if ($showonlywidget_check == 'show') {
						
						$target = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_target_target','','');

						$itemparent = $this->CheckItemsParent($target);
						
						if($itemparent == 'none'){

							$validation_check = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_validation_required','','0');
							$field_autocmplete = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_autocmplete','','1');

							if($validation_check == 1){
								$validation_message = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_message','','');
								
								if($this->VSOMessages != ''){
									$this->VSOMessages .= ','.$slug.':"'.$validation_message.'"';
								}else{
									$this->VSOMessages = $slug.':"'.$validation_message.'"';
								}
								
								if($this->VSORules != ''){
									$this->VSORules .= ','.$slug.':"required"';
								}else{
									$this->VSORules = $slug.':"required"';
								}
							}
							
							$fieldtext = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_fieldtext','','');
							$placeholder = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_placeholder','','');
							$column_type = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_column','','0');

							$geolocfield = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_geolocfield','','0');
							$geolocfield = ($geolocfield == 1)? 'Mile':'Km';
							$geolocfield2 = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_geolocfield2','','100');
							
							

							if($column_type == 1 && $target != 'google'){
								if ($this->PFHalf % 2 == 0) {
									$this->FieldOutput .= '<div class="col6 last">';
								}else{
									if ($hormode == 1 && $widget == 0 && $target != 'google') {
										if ($device_check) {
											$this->FieldOutput .= '<div class="col-lg-3 col-md-4 col-sm-4 colhorsearch">';
										}else{
											$this->FieldOutput .= '<div class="col-lg-12 colhorsearch">';
									}
									}
									if ($hormode == 1 && $widget == 1 && $minisearch == 1) {
										$this->FieldOutput .= $this->GetMiniSearch($minisearchc);
									}
									$this->FieldOutput .= '<div class="row"><div class="col6 first">';
								}
								$this->PFHalf++;
							}else{
								if ($hormode == 1 && $widget == 0 && $target != 'google') {
									if ($device_check) {
										$this->FieldOutput .= '<div class="col-lg-3 col-md-4 col-sm-4 colhorsearch">';
									}else{
										$this->FieldOutput .= '<div class="col-lg-12 colhorsearch">';
									}
								}
								if ($hormode == 1 && $widget == 1 && $minisearch == 1) {
									$this->FieldOutput .= $this->GetMiniSearch($minisearchc);
								}
							};

							if (array_key_exists($slug,$pfgetdata)) {
								$valtext = ' value = "'.$pfgetdata[$slug].'" ';;
							}else{
								$valtext = '';
							}

							
							if ($target == 'google') {
								if ($widget == 0) {
									
									if ($hormode == 1) {
										if ($device_check) {
											$this->FieldOutput .= '<div class="col-lg-3 col-md-4 col-sm-4 colhorsearch">';
										}else{
											$this->FieldOutput .= '<div class="col-lg-12 colhorsearch">';
										}
									}
									$this->FieldOutput .= '
									<div id="'.$slug.'_main" class="pfmapgoogleaddon">
										<label for="'.$slug.'" class="pftitlefield">'.$fieldtext.'</label>
										
											<div class="typeahead__container we-change-addr-input"><div class="typeahead__field"><span class="typeahead__query"><label class="pflabelfixsearch lbl-ui search"><input autocomplete="off" type="search" name="'.$slug.'" id="'.$slug.'" class="input" placeholder="'.$placeholder.'"'.$valtext.' /></label> </span></div></div>
	
											<input type="hidden" name="pointfinder_google_search_coord" id="pointfinder_google_search_coord" class="input" value="" />
											<input type="hidden" name="pointfinder_google_search_coord_unit" id="pointfinder_google_search_coord_unit" class="input" value="'.$geolocfield.'" />
											<a class="button" id="pf_search_geolocateme" title="'.esc_html__('Locate me!','pointfindercoreelements').'"><img src="'.PFCOREELEMENTSURLPUBLIC.'images/geoicon.svg" width="16px" height="16px" class="pf-search-locatemebut injectable" alt="'.esc_html__('Locate me!','pointfindercoreelements').'"><div class="pf-search-locatemebutloading"></div></a>
											<a class="button" id="pf_search_geodistance" title="'.esc_html__('Distance','pointfindercoreelements').'"><i class="pfadmicon-glyph-72"></i></a>
										
									';
									
									$this->FieldOutput .= '
										<div id="pointfinder_radius_search_mainerror"><div class="pfradius-triangle-up"></div>'.esc_html__('Please click to geolocate button to change this value.','pointfindercoreelements').'</div>
										<div id="pointfinder_radius_search_main">
										<div class="pfradius-triangle-up"></div>
											<label for="pointfinder_radius_search-view" class="pfrangelabel">'.esc_html__('Distance','pointfindercoreelements').' ('.$geolocfield.') :</label>
											<input type="text" id="pointfinder_radius_search-view" class="slider-input" disabled="" style="width: 44%;">
											<input name="pointfinder_radius_search" id="pointfinder_radius_search-view2" type="hidden" class="pfignorevalidation"> 
											<div class="slider-wrapper">
												<div id="pointfinder_radius_search" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all ui-slider-pointfinder_radius_search">
													<div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-min"></div>
													<span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"></span>
												</div>  
											</div>
										</div> 

									</div>                        
									';
									
									if ($hormode == 1) {
										$this->FieldOutput .= '</div>';
									}
									$wemap_geoctype = $this->PFSAIssetControl('wemap_geoctype','','');
									$this->ScriptOutput .= "
									$('#pf_search_geolocateme').on('click',function(e){
										e.stopPropagation();
										if ($('#pf_search_geodistance i').hasClass('pfadmicon-glyph-96')) {
											$('#pf_search_geodistance').trigger('click');
										}
										$('#pfsearch-draggable .typeahead__container.we-change-addr-input .typeahead__field input').css('padding-right','48px');
										$('.pf-search-locatemebut').hide('fast');
										$('.pf-search-locatemebutloading').show('fast');
										$.pfgeolocation_findme('".$slug."','".$wemap_geoctype."');
									});
									
									";
									
									$this->ScriptOutput .= '
									if($.pf_tablet2_check() && theme_scriptspf.ttstatus == 1){
							  			$("#'.$slug.'_main a").tooltip(
							  				{
											  tooltipClass: "wpfquick-tooltip",
											  position: { my: "center+50 bottom", at: "center top+8", },
											  show: {
												duration: "fast"
											  },
											  hide: {
											  	effect: "hide"
											  }
											}
							  			);
							  		}
									$.typeahead({
									    input: "#'.$slug.'",
									    minLength: 3,
									    accent: true,
									    dynamic:true,
									    compression: false,
									    cache: false,
										ttl: 86400000,
										hint: false,
										loadingAnimation: true,
										cancelButton: true,
										debug: true,
										searchOnFocus: false,
										delay: 1000,
										group: false,
										filter: false,
										maxItem: 10,
										maxItemPerGroup: 10,
										emptyTemplate: "'.wp_sprintf( esc_html__( "No results found for %s", "pointfindercoreelements" ), "<b>{{query}}</b>" ).'",
										template: "{{address}}",
										templateValue: "{{address}}",
										selector: {
									        cancelButton: "typeahead__cancel-button2"
									    },
									    source: {
									        "found": {
									          ajax: {
									          	type: "GET",
									              url: theme_scriptspf.ajaxurl,
									              dataType: "json",
									              path: "data.found",
									              data: {
									              	action: "pfget_geocoding",
									              	security: theme_scriptspf.pfget_geocoding,
									              	q: "{{query}}",
									              	option: "geocode",
									              	ctype: "'.$wemap_geoctype.'"
									              }
									          }
									        }
									    },
									    callback: {
									    	onLayoutBuiltAfter:function(){
												$(".pfminigoogleaddon").find(".typeahead__list").css("width",$("#'.$slug.'").outerWidth());
												$(".pfminigoogleaddon").find(".typeahead__result").css("width",$("#'.$slug.'").outerWidth());
												$("#pfsearch-draggable .we-change-addr-input ul.typeahead__list").css("min-width",$("#pfsearch-draggable .we-change-addr-input .typeahead__field").outerWidth());
									    	},
									    	onClickBefore: function(){
												$("#pfsearch-draggable .typeahead__container.we-change-addr-input .typeahead__field input").css("padding-right","66px");
								    		},
											onClickAfter: function(node, a, item, event){
												event.preventDefault();
												
												$("#'.$slug.'").val(item.address);

												if("'.$wemap_geoctype.'" == "google"){
													
													var sessiontoken = item.lng;
													var place_id = item.lat
													$.ajax({
												    	url: theme_scriptspf.ajaxurl,
												    	type: "GET",
												    	dataType: "JSON",
												    	data: {action: "pfget_geocodingx",security: theme_scriptspf.pfget_geocoding,sessiontoken:sessiontoken,place_id:place_id},
												    })
												    .done(function(data) {
												    	$("#pointfinder_google_search_coord").val(data.result.geometry.location.lat+","+data.result.geometry.location.lng);
												    });
													

												}else{
													$("#pointfinder_google_search_coord").val(item.lat+","+item.lng);
												}
												
												$(".typeahead__cancel-button2").css("visibility","visible");
											},
											onCancel: function(node,event){
												$(".typeahead__cancel-button2").css("visibility","hidden");
												$("#pointfinder_google_search_coord").val("");
								        	}
									    }
									});';
									
									$pointfinder_radius_search_val = $this->PFSAIssetControl('setup7_geolocation_distance','','10');

									if (isset($_GET['pointfinder_radius_search'])) {
										if (!empty(absint($_GET['pointfinder_radius_search']))) {
											$pointfinder_radius_search_val = absint($_GET['pointfinder_radius_search']);
										}
									}
									

									$this->ScriptOutput .= '
										$( "#pointfinder_radius_search" ).slider({
											range: "min",value:'.$pointfinder_radius_search_val.',min: 0,max: '.$geolocfield2.',step: 1,
											slide: function(event, ui) {
												$("#pointfinder_radius_search-view").val(ui.value);
												$("#pointfinder_radius_search-view2").val(ui.value);
											}
										});

										$("#pointfinder_radius_search-view").val( $("#pointfinder_radius_search").slider("value"));

														
										$(document).one("ready",function(){
											$("#pointfinder_radius_search-view2").val('.$pointfinder_radius_search_val.');
										});
									';

									$this->ScriptOutput .= "
									$('#pointfinder_radius_search').slider({
									    stop: function(event, ui) {
											var coord_value = $('#pointfinder_google_search_coord').val();
											if(coord_value != 'undefined'){
												var coord_value1 = coord_value.split(',');
												$.pointfindersetboundsex(parseFloat(coord_value1[0]),parseFloat(coord_value1[1]));	
											}
									    }
									});
									";
									
								}else{


									$nefv = $ne2fv = $swfv = $sw2fv = $pointfinder_google_search_coord1 = '';
									$wemap_geoctype = $this->PFSAIssetControl('wemap_geoctype','','google');
									if (isset($_GET['pointfinder_google_search_coord'])) {$pointfinder_google_search_coord1 = $_GET['pointfinder_google_search_coord'];}
									
									if ($minisearch == 1) {
										$statustextform2 = 'class="pfminigoogleaddon"';
									}else{$statustextform2 = 'class="pfwidgetgoogleaddon"';}


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

									wp_enqueue_script( 'theme-leafletjs' );
									wp_enqueue_style( 'theme-leafletcss');
									$this->FieldOutput .= '
									<div id="pfwidgetmap" 
									data-mode="topmap" 
								    data-lat="0" 
						    		data-lng="0" 
						    		data-zoom="12" 
						    		data-zoomm="12" 
						    		data-zoommx="12" 
						    		data-mtype="'.$stp5_mapty.'" 
						    		data-key="'.$we_special_key.'" 
						    		data-hereappid="'.$wemap_here_appid.'" 
									data-hereappcode="'.$wemap_here_appcode.'" 
									data-gldistance="'.$setup7_geolocation_distance.'" 
									data-gldistanceunit="'.$setup7_geolocation_distance_unit.'" 
									data-gldistancepopup="'.$setup7_geolocation_hideinfo.'" 
									data-found=""  
									data-cluster="'.$setup6_clustersettings_status.'" 
									data-clusterrad="'.$stp6_crad.'" 
									style="display:none;"></div>
									<div id="'.$slug.'_main" '.$statustextform2.'>
										<label for="'.$slug.'" class="pftitlefield">'.$fieldtext.'</label>
										
										<div class="pflabelfixsearchmain">
											<div class="typeahead__container we-change-addr-input"><div class="typeahead__field"><span class="typeahead__query"><label class="pflabelfixsearch lbl-ui search"><input autocomplete="off" type="search" name="'.$slug.'" id="'.$slug.'" class="input" placeholder="'.$placeholder.'"'.$valtext.' /></label> </span></div></div>
											<input type="hidden" name="pointfinder_google_search_coord" id="pointfinder_google_search_coord" class="input" value="'.$pointfinder_google_search_coord1.'" />
											<input type="hidden" name="pointfinder_google_search_coord_unit" id="pointfinder_google_search_coord_unit" class="input" value="'.$geolocfield.'" />
											<a class="button" id="pf_search_geolocateme" title="'.esc_html__('Locate me!','pointfindercoreelements').'"><img src="'.PFCOREELEMENTSURLPUBLIC.'images/geoicon.svg" width="16px" height="16px" class="pf-search-locatemebut injectable" alt="'.esc_html__('Locate me!','pointfindercoreelements').'"><div class="pf-search-locatemebutloading"></div></a>
											<a class="button" id="pf_search_geodistance" title="'.esc_html__('Distance','pointfindercoreelements').'"><i class="pfadmicon-glyph-72"></i></a>
										</div>
									';

									
									if (isset($_GET['ne'])) {$nefv = floatval($_GET['ne']);}
									if (isset($_GET['ne2'])) {$ne2fv = floatval($_GET['ne2']);}
									if (isset($_GET['sw'])) {$swfv = floatval($_GET['sw']);}
									if (isset($_GET['sw2'])) {$sw2fv = floatval($_GET['sw2']);}
									if (isset($_GET['pointfinder_radius_search'])) {$pointfinder_radius_search_val = $_GET['pointfinder_radius_search'];}
									
									if (empty($pointfinder_radius_search_val)) {
									    $pointfinder_radius_search_val = $this->PFSAIssetControl('setup7_geolocation_distance','','10');
									    if (isset($_GET['pointfinder_radius_search'])) {
											if (!empty(absint($_GET['pointfinder_radius_search']))) {
												$pointfinder_radius_search_val = absint($_GET['pointfinder_radius_search']);
											}
										}
									}
									if ($minisearch == 1) {
										$statustextform = ' style="display:none;"';
									}else{$statustextform = '';}

									$this->FieldOutput .= '
										<div id="pointfinder_radius_search_mainerror"><div class="pfradius-triangle-up"></div>'.esc_html__('Please click to geolocate button to change this value.','pointfindercoreelements').'</div>
										<div id="pointfinder_radius_search_main"'.$statustextform.'>
										<div class="pfradius-triangle-up"></div>
											<label for="pointfinder_radius_search-view" class="pfrangelabel">'.esc_html__('Distance','pointfindercoreelements').' ('.$geolocfield.') :</label>
											<input type="text" id="pointfinder_radius_search-view" class="slider-input" disabled="" style="width: 44%;">
											<input name="pointfinder_radius_search" id="pointfinder_radius_search-view2" type="hidden" class="pfignorevalidation"> 
											<div class="slider-wrapper">
												<div id="pointfinder_radius_search" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all ui-slider-pointfinder_radius_search">
													<div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-min"></div>
													<span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"></span>
												</div>  
											</div>
											<input type="hidden" name="ne" id="pfw-ne" class="input" value="'.$nefv.'" />
											<input type="hidden" name="ne2" id="pfw-ne2" class="input" value="'.$ne2fv.'" />
											<input type="hidden" name="sw" id="pfw-sw" class="input" value="'.$swfv.'" />
											<input type="hidden" name="sw2" id="pfw-sw2" class="input" value="'.$sw2fv.'" />
										</div> 

									</div>                        
									';
									
									$this->ScriptOutput .= "

									$(function(){
										";
										if (!empty($pointfinder_radius_search_val)) {
											$this->ScriptOutput .= "											
											$( '#pointfinder_radius_search' ).slider( 'option', 'value', ".$pointfinder_radius_search_val." );
											$( '#pointfinder_radius_search-view' ).val( ".$pointfinder_radius_search_val." );
											";

										}
										$this->ScriptOutput .= "
										
									});
						
									$('#pf_search_geolocateme').on('click',function(e){
										e.stopPropagation();
										if ($('#pf_search_geodistance i').hasClass('pfadmicon-glyph-96')) {
											$('#pf_search_geodistance').trigger('click');
										}
										$('.pfminigoogleaddon .typeahead__container.we-change-addr-input .typeahead__field input').css('padding-right','48px');
										$('.pf-search-locatemebut').hide('fast');
										$('.pf-search-locatemebutloading').show('fast');
										$.pfgeolocation_findme('".$slug."','".$wemap_geoctype."');
									});
									";

									
									$this->ScriptOutput .= '$.typeahead({
									    input: "#'.$slug.'",
									    minLength: 3,
									    accent: true,
									    dynamic:true,
									    compression: false,
									    cache: false,
										ttl: 86400000,
										hint: false,
										loadingAnimation: true,
										cancelButton: true,
										debug: true,
										searchOnFocus: false,
										delay: 1000,
										group: false,
										filter: false,
										maxItem: 10,
										maxItemPerGroup: 10,
										emptyTemplate: "'.wp_sprintf( esc_html__( "No results found for %s", "pointfindercoreelements" ), "<b>{{query}}</b>" ).'",
										template: "{{address}}",
										templateValue: "{{address}}",
										selector: {
									        cancelButton: "typeahead__cancel-button2"
									    },
									    source: {
									        "found": {
									          ajax: {
									          	type: "GET",
									              url: theme_scriptspf.ajaxurl,
									              dataType: "json",
									              path: "data.found",
									              data: {
									              	action: "pfget_geocoding",
									              	security: theme_scriptspf.pfget_geocoding,
									              	q: "{{query}}",
									              	option: "geocode",
									              	ctype: "'.$wemap_geoctype.'"
									              }
									          }
									        }
									    },
									    callback: {
									    	onLayoutBuiltAfter:function(){
												$(".pfminigoogleaddon").find(".typeahead__list").css("width",$("#'.$slug.'").outerWidth());
												$(".pfminigoogleaddon").find(".typeahead__result").css("width",$("#'.$slug.'").outerWidth());
												$("#pfsearch-draggable .we-change-addr-input ul.typeahead__list").css("min-width",$("#pfsearch-draggable .we-change-addr-input .typeahead__field").outerWidth());
									    	},
									    	onClickBefore: function(){
									    		
												$(".pfminigoogleaddon .typeahead__container.we-change-addr-input .typeahead__field input").css("padding-right","66px");
								    		},
											onClickAfter: function(node, a, item, event){
												event.preventDefault();
												
												$("#'.$slug.'").val(item.address);
												
												if("'.$wemap_geoctype.'" == "google"){
													
													var sessiontoken = item.lng;
													var place_id = item.lat
													$.ajax({
												    	url: theme_scriptspf.ajaxurl,
												    	type: "GET",
												    	dataType: "JSON",
												    	data: {action: "pfget_geocodingx",security: theme_scriptspf.pfget_geocoding,sessiontoken:sessiontoken,place_id:place_id},
												    })
												    .done(function(data) {
												    	$("#pointfinder_google_search_coord").val(data.result.geometry.location.lat+","+data.result.geometry.location.lng);
												    	$.pointfindersetbounds(data.result.geometry.location.lat,data.result.geometry.location.lng);
												    });
													

												}else{
													$("#pointfinder_google_search_coord").val(item.lat+","+item.lng);
													$.pointfindersetbounds(item.lat,item.lng);
												}
												
												$(".typeahead__cancel-button2").css("visibility","visible");
											},
											onCancel: function(node,event){
												$(".typeahead__cancel-button2").css("visibility","hidden");
												$("#pointfinder_google_search_coord").val("");
								        	}
									    }
									});';
									$this->ScriptOutput .= "
									$('#pointfinder_radius_search').slider({
									    stop: function(event, ui) {
											var coord_value = $('#pointfinder_google_search_coord').val();
											if(coord_value != 'undefined'){
												var coord_value1 = coord_value.split(',');
												$.pointfindersetbounds(parseFloat(coord_value1[0]),parseFloat(coord_value1[1]));
											}
									    }
									});
									";

									$this->ScriptOutput .= '
										$( "#pointfinder_radius_search" ).slider({
											range: "min",value:'.$pointfinder_radius_search_val.',min: 0,max: '.$geolocfield2.',step: 1,
											slide: function(event, ui) {
												$("#pointfinder_radius_search-view").val(ui.value);
												$("#pointfinder_radius_search-view2").val(ui.value);
											}
										});

										$("#pointfinder_radius_search-view").val( $("#pointfinder_radius_search").slider("value"));

														
										$(document).one("ready",function(){
											$("#pointfinder_radius_search-view2").val('.$pointfinder_radius_search_val.');
										});
									';

								}

							}elseif ($target == 'title' || $target == 'address') {
								
								$this->FieldOutput .= '
								<div id="'.$slug.'_main" class="ui-widget">
								<label for="'.$slug.'" class="pftitlefield">'.$fieldtext.'</label>
								<label class="lbl-ui pflabelfixsearch pflabelfixsearch'.$slug.'">
									<input type="text" name="'.$slug.'" id="'.$slug.'" class="input" placeholder="'.$placeholder.'"'.$valtext.' />
								</label>    
								</div>                        
								';
								
								if($field_autocmplete == 1){
									$this->ScriptOutput .= '
									$( "#'.$slug.'" ).on("keydown",function(){


									$( "#'.$slug.'" ).autocomplete({
									  position: { my : "right top", at: "right bottom" },
									  appendTo: "body",
								      source: function( request, response ) {
								        $.ajax({
								          url: theme_scriptspf.ajaxurl,
								          dataType: "jsonp",
								          data: {
								          	action: "pfget_autocomplete",
								            q: request.term,
								            security: theme_scriptspf.pfget_autocomplete,
								            lang: "'.$lang_custom.'",
								            ftype: "'.$target.'"
								          },
								          success: function( data ) {
								            response( data );
								          }
								        });
								      },
								      minLength: 3,
								      select: function( event, ui ) {
								        $("#'.$slug.'").val(ui.item);
								      },
								      open: function() {
										console.log($("body").find("#'.$slug.'").outerWidth());
								        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
								        $( ".ui-autocomplete" ).css("width",$("body").find("#'.$slug.'").outerWidth());
								      },
								      close: function() {
								        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
								      }
								    });

									});
									';
								}


							}elseif ($target == 'description' || $target == 'title_description') {

								$this->FieldOutput .= '
								<div id="'.$slug.'_main" class="ui-widget">
								<label for="'.$slug.'" class="pftitlefield">'.$fieldtext.'</label>
								<label class="lbl-ui pflabelfixsearch pflabelfixsearch'.$slug.'">
									<input type="text" name="'.$slug.'" id="'.$slug.'" class="input" placeholder="'.$placeholder.'"'.$valtext.' />
								</label>    
								</div>                        
								';

								if($field_autocmplete == 1){
									$this->ScriptOutput .= '
									$( "#'.$slug.'" ).on("keydown",function(){


									$( "#'.$slug.'" ).autocomplete({
										position: { my : "right top", at: "right bottom" },
									  appendTo: "body",
								      source: function( request, response ) {
								        $.ajax({
								          url: theme_scriptspf.ajaxurl,
								          dataType: "jsonp",
								          data: {
								          	action: "pfget_autocomplete",
								            q: request.term,
								            security: theme_scriptspf.pfget_autocomplete,
								            lang: "'.$lang_custom.'",
								            ftype: "'.$target.'"
								          },
								          success: function( data ) {
								            response( data );
								          }
								        });
								      },
								      minLength: 3,
								      select: function( event, ui ) {
								        $("#'.$slug.'").val(ui.item);
								      },
								      open: function() {
								        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
								        $( ".ui-autocomplete" ).css("width",$("body").find("#'.$slug.'").outerWidth());
								      },
								      close: function() {
								        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
								      }
								    });

									});
									';
								}

							}elseif ($target == 'search_all') {

								$searchall_click = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_searchall_click','','0');
								$setup3_pointposttype_pt7 = $this->PFSAIssetControl('setup3_pointposttype_pt7','','Listing Types');
								$setup3_pointposttype_pt4 = $this->PFSAIssetControl('setup3_pointposttype_pt4','','Item Types');
								$setup3_pointposttype_pt5 = $this->PFSAIssetControl('setup3_pointposttype_pt5','','Locations');
								$setup3_pointposttype_pt6 = $this->PFSAIssetControl('setup3_pointposttype_pt6','','Features');
								$setup3_pointposttype_pt3 = $this->PFSAIssetControl('setup3_pointposttype_pt3','','PF Items');

								if (!empty($searchall_click)) {
									$minLenght_sa = 0;
									$searchOnFocus = 'true';
									$maxItemPerGroup = 15;
								}else{
									$minLenght_sa = 1;
									$searchOnFocus = 'false';
									$maxItemPerGroup = 5;
								}

								$this->FieldOutput .= '
								<div id="'.$slug.'_main" class="ui-widget">
								<label for="'.$slug.'" class="pftitlefield">'.$fieldtext.'</label>
								<label class="lbl-ui pflabelfixsearch pflabelfixsearch'.$slug.'">
									<div class="typeahead__container"><div class="typeahead__field"><span class="typeahead__query">
									<input type="search" name="'.$slug.'" id="'.$slug.'" class="input" placeholder="'.$placeholder.'"'.$valtext.' autocomplete="off" value="" />
									<input type="hidden" name="'.$slug.'_sel" id="'.$slug.'_sel" value=""/>
									<input type="hidden" name="'.$slug.'_val" id="'.$slug.'_val" value=""/>
									 </span></div></div>
								</label>    
								</div>                        
								';

								if($field_autocmplete == 1){
									$this->ScriptOutput .= '
									if(typeof $("#'.$slug.'").val() != "undefined"){
										$.typeahead({
										    input: "#'.$slug.'",
										    minLength: '.$minLenght_sa.',
										    accent: true,
										    compression: false,
										    cache: false,
											ttl: 86400000,
											hint: false,
											loadingAnimation: true,
											cancelButton: true,
											debug: true,
											searchOnFocus: '.$searchOnFocus.',
											delay:1000,
											group: false,
											filter: false,
											maxItem: 15,
											maxItemPerGroup: '.$maxItemPerGroup.',
											emptyTemplate: \''.esc_html__( "No result for", "pointfindercoreelements" ).' "{{query}}"\',
										    source: {
										        "listings": {
										        	display: "name",
										        	dynamic: true,
										        	template: function (query,item) {
										        		var str = item.group;
														var group_replace = str.replace("listings", "'.$setup3_pointposttype_pt3.'");
												        var output_style = item.name +"<small>"+group_replace+"</small>";
												        return output_style;
												    },
										            ajax: {
										            	type: "POST",
										                url: "'.PFCOREELEMENTSURLINC.'pfajaxhandler.php'.'",
										                dataType: "json",
										                path: "data.listings",
										                data: {
										                	action: "pfget_autocomplete_sa",
										                	security: theme_scriptspf.pfget_autocomplete,
										                	q: "{{query}}",
										                	lang: "'.$lang_custom.'",
										                	fslug: "'.$slug.'"
										                }
										            }
										        },
										        "pointfinderltypes": {
										        	display: "name",
										        	dynamic: false,
										        	template: function (query,item) {
										        		var str = item.group;
														var group_replace = str.replace("pointfinderltypes", "'.$setup3_pointposttype_pt7.'");
												        var output_style = item.name +"<small>"+group_replace+"</small>";
												        return output_style;
												    },
										            ajax: {
										            	type: "POST",
										                url: "'.PFCOREELEMENTSURLINC.'pfajaxhandler.php'.'",
										                dataType: "json",
										                path: "data.pointfinderltypes",
										                data: {
										                	action: "pfget_autocomplete_sa",
										                	security: theme_scriptspf.pfget_autocomplete,
										                	q: "{{query}}",
										                	lang: "'.$lang_custom.'",
										                	fslug: "'.$slug.'"
										                }
										            }
										        },
										        "pointfinderitypes": {
										        	display: "name",
										        	dynamic: false,
										        	template: function (query,item) {
										        		var str = item.group;
														var group_replace = str.replace("pointfinderitypes", "'.$setup3_pointposttype_pt4.'");
												        var output_style = item.name +"<small>"+group_replace+"</small>";
												        return output_style;
												    },
										            ajax: {
										            	type: "POST",
										                url: "'.PFCOREELEMENTSURLINC.'pfajaxhandler.php'.'",
										                dataType: "json",
										                path: "data.pointfinderitypes",
										                data: {
										                	action: "pfget_autocomplete_sa",
										                	security: theme_scriptspf.pfget_autocomplete,
										                	q: "{{query}}",
										                	lang: "'.$lang_custom.'",
										                	fslug: "'.$slug.'"
										                }
										            }
										        },
										        "pointfinderlocations": {
										        	display: "name",
										        	dynamic: false,
										        	template: function (query,item) {
										        		var str = item.group;
														var group_replace = str.replace("pointfinderlocations", "'.$setup3_pointposttype_pt5.'");
												        var output_style = item.name +"<small>"+group_replace+"</small>";
												        return output_style;
												    },
										            ajax: {
										            	type: "POST",
										                url: "'.PFCOREELEMENTSURLINC.'pfajaxhandler.php'.'",
										                dataType: "json",
										                path: "data.pointfinderlocations",
										                data: {
										                	action: "pfget_autocomplete_sa",
										                	security: theme_scriptspf.pfget_autocomplete,
										                	q: "{{query}}",
										                	lang: "'.$lang_custom.'",
										                	fslug: "'.$slug.'"
										                }
										            }
										        },
										        "pointfinderfeatures": {
										        	display: "name",
										        	dynamic: false,
										        	template: function (query,item) {
										        		var str = item.group;
														var group_replace = str.replace("pointfinderfeatures", "'.$setup3_pointposttype_pt6.'");
												        var output_style = item.name +"<small>"+group_replace+"</small>";
												        return output_style;
												    },
										            ajax: {
										            	type: "POST",
										                url: "'.PFCOREELEMENTSURLINC.'pfajaxhandler.php'.'",
										                dataType: "json",
										                path: "data.pointfinderfeatures",
										                data: {
										                	action: "pfget_autocomplete_sa",
										                	security: theme_scriptspf.pfget_autocomplete,
										                	q: "{{query}}",
										                	lang: "'.$lang_custom.'",
										                	fslug: "'.$slug.'"
										                }
										            }
										        },
										        "post_tags": {
										        	display: "name",
										        	dynamic: false,
										        	template: function (query,item) {
										        		var str = item.group;
														var group_replace = str.replace("post_tags", "'.esc_html__( 'Tags', 'pointfindercoreelements').'");
												        var output_style = item.name +"<small>"+group_replace+"</small>";
												        return output_style;
												    },
										            ajax: {
										            	type: "POST",
										                url: "'.PFCOREELEMENTSURLINC.'pfajaxhandler.php'.'",
										                dataType: "json",
										                path: "data.post_tags",
										                data: {
										                	action: "pfget_autocomplete_sa",
										                	security: theme_scriptspf.pfget_autocomplete,
										                	q: "{{query}}",
										                	lang: "'.$lang_custom.'",
										                	fslug: "'.$slug.'"
										                }
										            }
										        }
										    },
										    callback: {
										        onClickBefore: function (node, a, item, event) {
										        	$("#'.$slug.'_sel").val(item.group);
										        	$("#'.$slug.'_val").val(item.id);
										        	$(".typeahead__cancel-button").css("visibility","visible");
										        },
										        onCancel: function(node,event){
										        	$(".typeahead__cancel-button").css("visibility","hidden");
										        	return false;
										        }
										    }
										});
									}
				
									';
								}

							} else {
								
								$this->FieldOutput .= '
								<div id="'.$slug.'_main">
								<label for="'.$slug.'" class="pftitlefield">'.$fieldtext.'</label>
								<label class="lbl-ui pflabelfixsearch pflabelfixsearch'.$slug.'">
									<input type="text" name="'.$slug.'" id="'.$slug.'" class="input" placeholder="'.$placeholder.'"'.$valtext.' />
								</label>    
								</div>                        
								';

								if($field_autocmplete == 1){
									$this->ScriptOutput .= '
									$( "#'.$slug.'" ).on("keydown",function(){

									$( "#'.$slug.'" ).autocomplete({
										position: { my : "right top", at: "right bottom" },
									  appendTo: "body",
								      source: function( request, response ) {
								        $.ajax({
								          url: theme_scriptspf.ajaxurl,
								          dataType: "jsonp",
								          data: {
								          	action: "pfget_autocomplete",
								            q: request.term,
								            security: theme_scriptspf.pfget_autocomplete,
								            lang: "'.$lang_custom.'",
								            ftype: "'.$target.'"
								          },
								          success: function( data ) {
								            response( data );
								          }
								        });
								      },
								      minLength: 3,
								      select: function( event, ui ) {
								        $("#'.$slug.'").val(ui.item);
								      },
								      open: function() {
								        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
								        $( ".ui-autocomplete" ).css("width",$("body").find("#'.$slug.'").outerWidth());
								      },
								      close: function() {
								        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
								      }
								    });

									});
									';
								}
								
							}
							
							
							if($column_type == 1 && $target != 'google'){
								if ($this->PFHalf % 2 == 0) {
									$this->FieldOutput .= '</div>';
								}else{
									if (($hormode == 1 && $widget == 0 && $target != 'google') || ($hormode == 1 && $widget == 1 && $minisearch == 1)) {
										$this->FieldOutput .= '</div>';
									}
									$this->FieldOutput .= '</div></div>';
								}
							}else{
								if (($hormode == 1 && $widget == 0 && $target != 'google') || ($hormode == 1 && $widget == 1 && $minisearch == 1)) {
									$this->FieldOutput .= '</div>';
								}
							};
							
							
						}
					}
					break;

				case '5':
				/* Date Field */
					if ($showonlywidget_check == 'show') {
						wp_enqueue_script('jquery-ui-core');
						wp_enqueue_script('jquery-ui-datepicker');
						

						$column_type = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_column','','0');
						$target = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_target','','');
						if (empty($target)) {
							$target = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_target_target','','');
						}

						$itemparent = $this->CheckItemsParent($target);
						if($itemparent == 'none'){

							$validation_check = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_validation_required','','0');
							$field_autocmplete = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_autocmplete','','1');

							if($validation_check == 1){
								$validation_message = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_message','','');
								
								if($this->VSOMessages != ''){
									$this->VSOMessages .= ','.$slug.':"'.$validation_message.'"';
								}else{
									$this->VSOMessages = $slug.':"'.$validation_message.'"';
								}
								
								if($this->VSORules != ''){
									$this->VSORules .= ','.$slug.':"required"';
								}else{
									$this->VSORules = $slug.':"required"';
								}
							}
							
							$fieldtext = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_fieldtext','','');
							$placeholder = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_placeholder','','');
							$column_type = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_column','','0');
							
							if($column_type == 1){
								if ($this->PFHalf % 2 == 0) {
									$this->FieldOutput .= '<div class="col6 last">';
								}else{
									if ($hormode == 1 && $widget == 0 && $target != 'google') {
										if ($device_check) {
											$this->FieldOutput .= '<div class="col-lg-3 col-md-4 col-sm-4 colhorsearch">';
										}else{
											$this->FieldOutput .= '<div class="col-lg-12 colhorsearch">';
										}
									}
									if ($hormode == 1 && $widget == 1 && $minisearch == 1) {
										$this->FieldOutput .= $this->GetMiniSearch($minisearchc);
									}
									$this->FieldOutput .= '<div class="row"><div class="col6 first">';
								}
								$this->PFHalf++;
							}else{
								if ($hormode == 1 && $widget == 0) {
									if ($device_check) {
										$this->FieldOutput .= '<div class="col-lg-3 col-md-4 col-sm-4 colhorsearch">';
									}else{
										$this->FieldOutput .= '<div class="col-lg-12 colhorsearch">';
									}
								}
								if ($hormode == 1 && $widget == 1 && $minisearch == 1) {
									$this->FieldOutput .= $this->GetMiniSearch($minisearchc);
								}
							};
							

							if (array_key_exists($slug,$pfgetdata)) {
								$valtext = ' value = "'.$pfgetdata[$slug].'" ';;
							}else{
								$valtext = '';
							}

							
								
							$this->FieldOutput .= '
							<div id="'.$slug.'_main">
							<label for="'.$slug.'" class="pftitlefield">'.$fieldtext.'</label>
							<label class="lbl-ui pflabelfixsearch pflabelfixsearch'.$slug.'">
								<input type="text" name="'.$slug.'" id="'.$slug.'" class="input" placeholder="'.$placeholder.'"'.$valtext.' />
							</label>    
							</div>                        
							';

							$setup4_membersettings_dateformat = $this->PFSAIssetControl('setup4_membersettings_dateformat','','1');
							$setup3_modulessetup_openinghours_ex2 = $this->PFSAIssetControl('setup3_modulessetup_openinghours_ex2','','1');
							$yearselection = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_yearselection','','0');
							$date_field_rtl = (!is_rtl())? 'false':'true';
							$date_field_ys = (empty($yearselection))?'false':'true';

							switch ($setup4_membersettings_dateformat) {
								case '1':$date_field_format = 'dd/mm/yy';break;
								case '2':$date_field_format = 'mm/dd/yy';break;
								case '3':$date_field_format = 'yy/mm/dd';break;
								case '4':$date_field_format = 'yy/dd/mm';break;
								default:$date_field_format = 'dd/mm/yy';break;
							}

							$yearrange1 = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_yearrange1','','2000');
							$yearrange2 = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_yearrange2','',date("Y"));

							if (!empty($yearrange1) && !empty($yearrange2)) {
								$yearrangesetting = 'yearRange:"'.$yearrange1.':'.$yearrange2.'",';
							}elseif (!empty($yearrange1) && empty($yearrange2)) {
								$yearrangesetting = 'yearRange:"'.$yearrange1.':'.date("Y").'",';
							}else{
								$yearrangesetting = '';
							}

							$this->ScriptOutput .= "
								$(function(){
									$( '#".$slug."' ).datepicker({
								      changeMonth: $date_field_ys,
								      changeYear: $date_field_ys,
								      isRTL: $date_field_rtl,
								      dateFormat: '$date_field_format',
								      firstDay: $setup3_modulessetup_openinghours_ex2,/* 0 Sunday 1 monday*/
								      $yearrangesetting
								      prevText: '',
								      nextText: '',
								      beforeShow: function(input, inst) {
									       $('#ui-datepicker-div').addClass('pointfinder-map-datepicker');
									   }
								    });
								});
				            ";

							if($column_type == 1){
								if ($this->PFHalf % 2 == 0) {
									$this->FieldOutput .= '</div>';
								}else{
									if (($hormode == 1 && $widget == 0 && $target != 'google') || ($hormode == 1 && $widget == 1 && $minisearch == 1)) {
										$this->FieldOutput .= '</div>';
									}
									$this->FieldOutput .= '</div></div>';
								}
							}else{
								if (($hormode == 1 && $widget == 0) || ($hormode == 1 && $widget == 1 && $minisearch == 1)) {
									$this->FieldOutput .= '</div>';
								}
							};
							
							
						}
					}
					break;

				case '6':
				/* check Box */
					if ($showonlywidget_check == 'show') {
						$target = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_target','','');
						if (empty($target)) {
							$target = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_target_target','','');
						}
						
						$itemparent = $this->CheckItemsParent($target);

						if($itemparent == 'none'){
							$validation_check = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_validation_required','','0');
							if($validation_check == 1){
								$validation_message = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_message','','');
								if($this->VSOMessages != ''){
									$this->VSOMessages .= ','.$slug.':"'.$validation_message.'"';
								}else{
									$this->VSOMessages = $slug.':"'.$validation_message.'"';
								}
								
								if($this->VSORules != ''){
									$this->VSORules .= ','.$slug.':"required"';
								}else{
									$this->VSORules = $slug.':"required"';
								}
							}
							
							if ($hormode == 1 && $widget == 0) {
								if ($device_check) {
									$this->FieldOutput .= '<div class="col-lg-3 col-md-4 col-sm-4 colhorsearch">';
								}else{
									$this->FieldOutput .= '<div class="col-lg-12 colhorsearch">';
								}
							}
							
							$this->FieldOutput .= '<div id="'.$slug.'_main">';
							
							$fieldtext = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_fieldtext','','');
							$this->FieldOutput .= '<div class="pftitlefield">'.$fieldtext.'</div>';
							//$this->FieldOutput .= '<label for="'.$slug.'" class="lbl-ui checkbox">';
							$this->FieldOutput .= '<div class="option-group">';

							$rvalues = $this->PFSFIssetControl('setupsearchfields_'.$slug.'_rvalues','','');

							if(count($rvalues) > 0){$fieldvalues = $rvalues;}else{$fieldvalues = '';}

							if(count($fieldvalues) > 0){
								
								$ikk = 0;
								$widget_checkbox = '';
								if ($widget != 0) {
									$widget_checkbox = '[]';
								}

								foreach ($fieldvalues as $s) { 

									if (class_exists('SitePress')) {
										//$s = icl_t('admin_texts_pfsearchfields_options', '[pfsearchfields_options][setupsearchfields_'.$slug.'_rvalues]'.$ikk, $s);
										$s = apply_filters( 'wpml_translate_single_string', $s, 'admin_texts_pfsearchfields_options', '[pfsearchfields_options][setupsearchfields_'.$slug.'_rvalues]'.$ikk );
									}

									if ($pos = strpos($s, '=')) { 

										$this->FieldOutput .= '<span class="goption">';
		   								$this->FieldOutput .= '<label class="options">';


										$checkbox_output = '<input type="checkbox" name="'.$slug.$widget_checkbox.'" value="'.trim(substr($s, 0, $pos)).'" /><span class="checkbox"></span></label><label for="'.$slug.'">'.trim(substr($s, $pos + strlen('='))).'</label>';

										if (array_key_exists($slug,$pfgetdata)) {
											if (isset($pfgetdata[$slug])) {
												if (is_array($pfgetdata[$slug])) {
													if (in_array(trim(substr($s, 0, $pos)), $pfgetdata[$slug])) {
														$checkbox_output = '<input type="checkbox" name="'.$slug.$widget_checkbox.'" value="'.trim(substr($s, 0, $pos)).'" checked /><span class="checkbox"></span></label><label for="'.$slug.'">'.trim(substr($s, $pos + strlen('='))).'</label>';
													}
												}else{
													if (trim(substr($s, 0, $pos)) == $pfgetdata[$slug]) {
														$checkbox_output = '<input type="checkbox" name="'.$slug.$widget_checkbox.'" value="'.trim(substr($s, 0, $pos)).'" checked /><span class="checkbox"></span></label><label for="'.$slug.'">'.trim(substr($s, $pos + strlen('='))).'</label>';
													}
												}
											}
										}

										$this->FieldOutput .= $checkbox_output;

										
									}
									$this->FieldOutput .= '</span>';
									$ikk++;
								}
							}

							$this->FieldOutput .= '</div>';
							
							
							if (($hormode == 1 && $widget == 0)) {
								$this->FieldOutput .= '</div>';
							}
							$this->FieldOutput .= '</div>';
							

							
						}/*Parent Check*/
					}
					break;
			}
					
					
		}

				
	}
}
?>