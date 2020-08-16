<?php 
if (class_exists('PointFinderFeatureSYS')) {
	return;
}


class PointFinderFeatureSYS extends Pointfindercoreelements_AJAX
{
    public function __construct(){}

    public function pf_ajax_featuresystem(){

		check_ajax_referer( 'pfget_featuresystem', 'security');

		header('Content-Type: application/json; charset=UTF-8;');

		$id = $postid = $place = $lang_c = $output_features = $output_itemtypes = $output_video = $output_conditions = $output_customtab = $output_customfields = $post_terms_itemtype = $post_terms = $post_terms_comditions = $output_ohours = $scriptoutput = '';

		if(isset($_POST['id']) && $_POST['id']!=''){
			$id = sanitize_text_field($_POST['id']);
		}

		if(isset($_POST['postid']) && $_POST['postid']!=''){
			$postid = sanitize_text_field($_POST['postid']);
		}

		if(isset($_POST['place']) && $_POST['place']!=''){
			$place = sanitize_text_field($_POST['place']);
		}

		if(isset($_POST['lang']) && $_POST['lang']!=''){
			$lang_c = sanitize_text_field($_POST['lang']);
		}

		if(class_exists('SitePress')) {
			if (!empty($lang_c)) {
				do_action( 'wpml_switch_language', $lang_c );
			}
			//$pointfindertheme_option = get_option('pointfindertheme_options');
			//$pfadvancedcontrol_options = get_option('pfadvancedcontrol_options');
			global $pointfindertheme_option;
			global $pfadvancedcontrol_options;
		}else{
			global $pointfindertheme_option;
			global $pfadvancedcontrol_options;
		}

		/*
		* START : Features
		*/
			$args = array(
			    'orderby'           => 'name',
			    'order'             => 'ASC',
			    'hide_empty'        => false,
			    'exclude'           => array(),
			    'exclude_tree'      => array(),
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
			    'pad_counts'        => false,
			    'offset'            => '',
			    'search'            => '',
			    'cache_domain'      => 'core'
			);

			$terms = get_terms('pointfinderfeatures', $args);


			$output_features .= '<section class="pfsubmit-inner-sub"><div class="option-group">';
			if ($place != 'backend') {
				$output_features .= "<a class='pfitemdetailcheckall' >".esc_html__('Check All','pointfindercoreelements')."</a> / <a class='pfitemdetailuncheckall'>".esc_html__('Uncheck All','pointfindercoreelements')."</a><br/><br>";
			}

			$i = 0;

			if (isset($terms)) {
				if (is_array($terms)) {

					if (count($terms) > 0) {


						if (!empty($postid)) {
							$post_terms = wp_get_post_terms($postid, 'pointfinderfeatures', array("fields" => "ids"));
						}

						$controlvalue = $this->PFSAIssetControl('setup4_sbf_c1','',1);

						foreach ($terms as $term) {

							if ($term->parent == 0) {

								$term_parent = get_option( 'pointfinder_features_customlisttype_' . $term->term_id );

								if ($controlvalue != 1) {
									$output_check = $this->pointfinder_features_tax_output_check($term_parent,$id,$controlvalue);
								}else{
									$output_check = "ok";
								}



								if ($output_check == 'ok') {

									$checked_text = '';

									$term_childs = get_term_children( $term->term_id, 'pointfinderfeatures' );
						        	$term_child_count = 0;

						        	if (!is_wp_error( $term_childs )) {
						        		$term_child_count = count($term_childs);
						        	}


									if (!empty($post_terms)) {
										if (in_array($term->term_id, $post_terms)) {
											$checked_text = ' checked=""';
										}
									}

									if ($term_child_count > 0) {
										$output_features .= '<span class="goption goparentoption">';
					                    	$output_features .= '<label class="options">';
					                            $output_features .= '<input type="checkbox" id="pffeature'.$term->term_id.'" name="pffeature[]" data-pid="'.$term->term_id.'" data-parent="yes" data-child="no" data-childof="0" value="'.$term->term_id.'"'.$checked_text.'>';
					                            $output_features .= '<span class="checkbox"></span>';
					                        $output_features .= '</label>';
					                    	$output_features .= '<label for="pffeature'.$term->term_id.'">'.$term->name.'</label>';
					                    $output_features .= '</span>';

					              foreach ($term_childs as $featured_terms_single_sub) {
				        		      $term_chl = get_term_by( 'id', $featured_terms_single_sub, 'pointfinderfeatures' );
	                        $checked_text_child = '';
	                         if (is_array($post_terms)) {
	                        if (in_array($term_chl->term_id, $post_terms)) {
	      										$checked_text_child = ' checked=""';
	      									}
	      								}

				        		      $output_features .= '<span class="goption">';
			                    	$output_features .= '<label class="options">';
			                          $output_features .= '<input class="pffeature'.$term->term_id.'" type="checkbox" id="pffeature'.$term_chl->term_id.'" name="pffeature[]" data-pid="'.$term->term_id.'" data-parent="no" data-child="yes" data-childof="'.$term->term_id.'" value="'.$term_chl->term_id.'"'.$checked_text_child.' disabled>';
			                          $output_features .= '<span class="checkbox"></span>';
			                        $output_features .= '</label>';
			                    	$output_features .= '<label for="pffeature'.$term_chl->term_id.'">'.$term_chl->name.'</label>';
			                    $output_features .= '</span>';
								        }

								        $output_features .= '<span class="goparentfooteroption">';
								        $output_features .= '</span>';
									}else{
										$output_features .= '<span class="goption">';
					                    	$output_features .= '<label class="options">';
					                            $output_features .= '<input type="checkbox" id="pffeature'.$term->term_id.'" name="pffeature[]" data-pid="'.$term->term_id.'" data-parent="no" data-child="no" data-childof="0" value="'.$term->term_id.'"'.$checked_text.'>';
					                            $output_features .= '<span class="checkbox"></span>';
					                        $output_features .= '</label>';
					                    	$output_features .= '<label for="pffeature'.$term->term_id.'">'.$term->name.'</label>';
					                    $output_features .= '</span>';
					                }
				                    $i++;
								}
							}

						}
						$output_features .= '<input name="pointfinderfeaturecount" type="hidden" value="'.$i.'"/>';
					}

				}
			}
			$output_features .= '</div></section>';

			if ($place == 'backend' && $i == 0) {
				$output_features = esc_html__("Features not defined.","pointfindercoreelements");
	   		}
		/*
		* END : Features
		*/


		/*
		* START : Item Types
		*/
			$setup3_pointposttype_pt4_check = $this->PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
			$setup4_submitpage_itemtypes_check = $this->PFSAIssetControl('setup4_submitpage_itemtypes_check','','1');
			if ($place == 'backend') {
				$setup4_submitpage_itemtypes_check = 1;
			}
			if ($setup3_pointposttype_pt4_check == 1 && $setup4_submitpage_itemtypes_check == 1) {

				$stp_syncs_it = $this->PFSAIssetControl('stp_syncs_it','',1);
				$setup4_submitpage_itemtypes_multiple = $this->PFSAIssetControl('setup4_submitpage_itemtypes_multiple','','0');
				$setup4_submitpage_itemtypes_validation = $this->PFSAIssetControl('setup4_submitpage_itemtypes_validation','','1');
				$setup4_submitpage_itemtypes_verror = $this->PFSAIssetControl('setup4_submitpage_itemtypes_verror','','Please select an item type.');

				if ($setup4_submitpage_itemtypes_multiple == 1) {
					$setup4_submitpage_itemtypes_multiple_output = 'true';
				}else{
					$setup4_submitpage_itemtypes_multiple_output = 'false';
				}
				$itemfieldname = ($setup4_submitpage_itemtypes_multiple == 1) ? 'pfupload_itemtypes[]' : 'pfupload_itemtypes' ;

				$item_defaultvalue = (!empty($postid)) ? wp_get_post_terms($postid, 'pointfinderitypes', array("fields" => "ids")) : '' ;

				$output_itemtypes .= '<section class="pfsubmit-inner-sub">';
				$fields_output_arr = array(
					'listname' => 'pfupload_itemtypes',
			        'listtype' => 'itemtypes',
			        'listtitle' => '',
			        'listsubtype' => 'pointfinderitypes',
			        'listdefault' => $item_defaultvalue,
			        'listmultiple' => $setup4_submitpage_itemtypes_multiple,
			        'connectionkey' => 'pointfinder_itemtype_clt',
			        'connectionvalue' => $id,
			        'connectionstatus' => $stp_syncs_it,
			        'place' => $place
				);

				$output_function_it = $this->PFGetListFA($fields_output_arr);

				$output_itemtypes .= $output_function_it;
				$output_itemtypes .= '</section>';

				if ($place == 'backend') {
					$output_itemtypes .= '
				    <script>
				    jQuery(function(){
				        jQuery("#pfupload_itemtypes").multiselect({
				            buttonWidth: "100%",
				            disableIfEmpty: true,
				            nonSelectedText: "'.esc_html__("Please select","pointfindercoreelements").'",
				            nSelectedText: "'.esc_html__("selected","pointfindercoreelements").'",
				            allSelectedText: "'.esc_html__("All selected","pointfindercoreelements").'",
				            selectAllText: "'.esc_html__("Select all","pointfindercoreelements").'",
				            includeSelectAllOption: true,
				            enableFiltering: true,
				            filterPlaceholder: "'.esc_html__("Search","pointfindercoreelements").'",
				            enableFullValueFiltering: true,
				            enableCaseInsensitiveFiltering: true,
				            maxHeight: 300,
				            multiple:'.$setup4_submitpage_itemtypes_multiple_output.'
				        });
				    });
				    </script>
				    </td>
				    </tr>
				    ';
				}else{

					$output_itemtypes .= '
						<script type="text/javascript">
						(function($) {
						"use strict";';

					if ($place == 'backend') {
						$output_itemtypes .= '
							$("#pfupload_itemtypes").select2({
							placeholder: "'.esc_html__("Please select","pointfindercoreelements").'",
							formatNoMatches:"'.esc_html__("Nothing found.","pointfindercoreelements").'",
							allowClear: true,
							minimumResultsForSearch: 10
							});
						';
					}else{
						$output_itemtypes .= '
							if ($.pf_tablet_check()) {
								$("#pfupload_itemtypes").select2({
								placeholder: "'.esc_html__("Please select","pointfindercoreelements").'",
								formatNoMatches:"'.esc_html__("Nothing found.","pointfindercoreelements").'",
								allowClear: true,
								minimumResultsForSearch: 10
								});
							}
						';
					}

					if ($setup4_submitpage_itemtypes_validation == 1 && $place != 'backend') {
						$output_itemtypes .= '$("#pfupload_itemtypes").rules("add",{required: true,messages:{required:"'.$setup4_submitpage_itemtypes_verror.'"}});';
					}
					$output_itemtypes .= '
					})(jQuery);
					</script>
					';
				}

				if (empty($output_function_it)) {
					$output_itemtypes = '';
				}

				if(empty($output_itemtypes) && $place == 'backend'){
					$output_itemtypes = esc_html__("Item Types not defined.","pointfindercoreelements");
				}

			}

		/*
		* END : Item Types
		*/


		/*
		* START : Conditions
		*/
			$setup4_submitpage_conditions_check = $this->PFSAIssetControl('setup4_submitpage_conditions_check','',0);
			$setup3_pt14_check = $this->PFSAIssetControl('setup3_pt14_check','',0);

			if ($place == 'backend') {
				$setup4_submitpage_conditions_check = 1;
			}

			if ($setup3_pt14_check == 1 && $setup4_submitpage_conditions_check == 1) {

				$stp_syncs_co = $this->PFSAIssetControl('stp_syncs_co','',1);

				$setup4_submitpage_conditions_validation = $this->PFSAIssetControl('setup4_submitpage_conditions_validation','',0);
				$setup4_submitpage_conditions_verror = $this->PFSAIssetControl('setup4_submitpage_conditions_verror','','');
				$setup4_submitpage_conditions_check = $this->PFSAIssetControl('setup4_submitpage_conditions_check','',0);

				$item_defaultvalue = ($postid != '') ? wp_get_post_terms($postid, 'pointfinderconditions', array("fields" => "ids")) : '' ;

				$output_conditions .= '<section class="pfsubmit-inner-sub">';
				$fields_output_arr = array(
					'listname' => 'pfupload_conditions',
			        'listtype' => 'conditions',
			        'listtitle' => '',
			        'listsubtype' => 'pointfinderconditions',
			        'listdefault' => $item_defaultvalue,
			        'connectionkey' => 'pointfinder_condition_clt',
			        'connectionvalue' => $id,
			        'connectionstatus' => $stp_syncs_co,
			        'place' => $place
				);

				$output_function_co = $this->PFGetListFA($fields_output_arr);
				$output_conditions .= $output_function_co;
				$output_conditions .= '</section>';

				if ($place == 'backend') {
					$output_conditions .= '
				    <script>
				    jQuery(function(){
				        jQuery("#pfupload_conditions").multiselect({
				            buttonWidth: "100%",
				            disableIfEmpty: true,
				            nonSelectedText: "'.esc_html__("Please select","pointfindercoreelements").'",
				            nSelectedText: "'.esc_html__("selected","pointfindercoreelements").'",
				            allSelectedText: "'.esc_html__("All selected","pointfindercoreelements").'",
				            selectAllText: "'.esc_html__("Select all","pointfindercoreelements").'",
				            includeSelectAllOption: true,
				            enableFiltering: true,
				            filterPlaceholder: "'.esc_html__("Search","pointfindercoreelements").'",
				            enableFullValueFiltering: true,
				            enableCaseInsensitiveFiltering: true,
				            maxHeight: 300,
				            multiple:false
				        });
				    });
				    </script>
				    </td>
				    </tr>
				    ';
				}else{
					$output_conditions .= '
					<script type="text/javascript">
					(function($) {
					"use strict";';

					if ($place == 'backend') {
						$output_conditions .= '
							$("#pfupload_conditions").select2({
							placeholder: "'.esc_html__("Please select","pointfindercoreelements").'",
							formatNoMatches:"'.esc_html__("Nothing found.","pointfindercoreelements").'",
							allowClear: true,
							minimumResultsForSearch: 10
							});
						';
					}else{
						$output_conditions .= '
							if ($.pf_tablet_check()) {
								$("#pfupload_conditions").select2({
								placeholder: "'.esc_html__("Please select","pointfindercoreelements").'",
								formatNoMatches:"'.esc_html__("Nothing found.","pointfindercoreelements").'",
								allowClear: true,
								minimumResultsForSearch: 10
								});
							}
						';
					}
					if ($setup4_submitpage_conditions_validation == 1) {
						$output_conditions .= '$("#pfupload_conditions").rules("add",{required: true,messages:{required:"'.$setup4_submitpage_conditions_verror.'"}});';
					}
					$output_conditions .= '
					})(jQuery);
					</script>';
				}




				if (empty($output_function_co)) {
					$output_conditions = '';
				}

				if(empty($output_conditions) && $place == 'backend'){
					$output_conditions = esc_html__("Conditions not defined.","pointfindercoreelements");
				}

			}
		/*
		* END : Conditions
		*/


		/*
		* START : Custom Fields
		*/

			/**
			*Start : Field foreach
			**/
				$setup1_slides = $this->PFSAIssetControl('setup1_slides','','');
				$fields_output_arr = array();
				$pfstart = $this->PFCheckStatusofVar('setup1_slides');

				if(is_array($setup1_slides) && $pfstart){

					if (class_exists('SitePress')) {
						$pfcustomfields_options = get_option('pfcustomfields_options');
					}else{
						global $pfcustomfields_options;
					}

					foreach ($setup1_slides as &$value) {

			          $customfield_statuscheck = $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_frontupload','','0');
			          if ($place == 'backend') {
			          	$customfield_statuscheck = 1;
			          }
			          $available_fields = array(1,2,3,4,5,7,8,9,14,15);

			          if(in_array($value['select'], $available_fields) && $customfield_statuscheck != 0){


						$fieldtitle = (empty($pfcustomfields_options['setupcustomfields_'.$value['url'].'_frontendname'])) ? $value['title'] : $pfcustomfields_options['setupcustomfields_'.$value['url'].'_frontendname'];


						$fieldarr = array(
							'fieldname' => $value['url'],
							'fieldtitle' => $fieldtitle
						);

						/***
						Check parent item
						***/
						$ParentItem = $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_parent','','');
						if($this->PFControlEmptyArr($ParentItem)){
							if (class_exists('SitePress')) {
								$NewParentItemArr = array();
								foreach ($ParentItem as $ParentItemSingle) {
									$NewParentItemArr[] = apply_filters('wpml_object_id', $ParentItemSingle, 'pointfinderltypes', TRUE);
								}
								$fieldarr['fieldparent'] = $NewParentItemArr;
							}else{
								$fieldarr['fieldparent'] = $ParentItem;
							}

						}
						/***
						End :
						***/

						$status = 'not';

						if (!empty($id)) {
							if (isset($fieldarr['fieldparent'])) {
							if (is_array($fieldarr['fieldparent'])) {
								if (in_array($id, $fieldarr['fieldparent'])) {
									$status = 'ok';
								}
							}
							}
						}else{
							if (empty($fieldarr['fieldparent'])) {
								$status = 'ok';
							}
						}

						if ($status == 'ok' || empty($fieldarr['fieldparent'])) {

							switch ($value['select']) {
								case '1':/*Text*/
								case '2':/*URL*/
								case '3':/*Email*/
								case '4':/*Number*/
									$fieldarr['fieldtype'] = 'text';
									$fieldarr['fieldsubtype'] = $value['select'];
									break;
								case '5':
									$fieldarr['fieldtype'] = 'textarea';
									break;
								case '7':
									$fieldarr['fieldtype'] = 'radio';
									$fieldarr['fieldoptions'] = $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_rvalues','','');
									break;
								case '9':
									$fieldarr['fieldtype'] = 'checkbox';
									$fieldarr['fieldoptions'] = $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_rvalues','','');
									break;
								case '8':
									$fieldarr['fieldtype'] = 'select';
									$fieldarr['fieldoptions'] = $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_rvalues','','');
									break;
								case '14':
									$fieldarr['fieldtype'] = 'selectmulti';
									$fieldarr['fieldoptions'] = $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_rvalues','','');
									break;
								case '15':
									$fieldarr['fieldtype'] = 'date';
							}


							/***
							Field Options from Admin Panel
							***/
							$field_description = $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_descriptionfront','','');
							$field_validation_check = $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_validation_required','','0');
							$fieldarr['fieldtooltip'] = $field_description;
							/***
							End :
							***/



							/***
							*Validation check and add rules.
							***/

							if($place != 'backend'){
								if($fieldarr['fieldtype'] == 'selectmulti' || $fieldarr['fieldtype'] == 'checkbox'){
									$field_validation_text = $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_message','','');
									$scriptoutput .= $this->PFValidationCheckWriteEx($field_validation_check,$field_validation_text,$value['url'].'[]');
								}else{
									$field_validation_text = $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_message','','');
									$scriptoutput .= $this->PFValidationCheckWriteEx($field_validation_check,$field_validation_text,$value['url']);
								}
							}
							/***
							*End :
							***/

							$fullwidth = $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_fwr','','0');
							if ($fullwidth == 1) {
								$fwtext = ' pf-cf-inner-fullwidth';
							}elseif ($fullwidth == 2) {
								$fwtext = ' pf-cf-inner-halfwidth';
							}
							else{$fwtext = '';}
							$output_customfields .= '<section class="pf-cf-inner-elements'.$fwtext.'">';

							/** Default Value **/
							$fieldarr['fielddefault'] = ($postid != '') ? get_post_meta($postid,'webbupointfinder_item_'.$value['url'],false) : $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_defaultvalue','','') ;

							$fieldarr['place'] = $place;
							$output_customfields .= $this->PFgetfield($fieldarr);

							$output_customfields .= '</section>';
						}

			          }

			        }

				}
			/**
			*End : Field foreach
			**/

			if($place != 'backend' && !empty($output_customfields)){
				$output_customfields .= "<script>";
				$output_customfields .= "(function($) {'use strict'; $(function(){";
				$output_customfields .= $scriptoutput;
				$output_customfields .= "});})(jQuery);";
				$output_customfields .= "</script>";
			}

			if (empty($output_customfields) && $place == 'backend') {
				$output_customfields = esc_html__("Custom fields not defined.","pointfindercoreelements");

			}

		/*
		* END : Custom Fields
		*/




		/* Check permission first */



		$st8_nasys = PFASSIssetControl('st8_nasys','',0);

		$advanced_term_status_new = 0;

		$top_term_id = $this->pf_get_term_top_most_parent($id,'pointfinderltypes');
		if (isset($top_term_id['parent'])) {
			if (!empty($top_term_id['parent'])) {
				$id = $top_term_id['parent'];
			}
		}

		if ($st8_nasys != 1){
			$advanced_term_status = $this->PFADVIssetControl('setupadvancedconfig_'.$id.'_advanced_status','','0');
		}

		if ($st8_nasys == 1 && !empty($id)) {
			$advanced_term_status = 0;
			$listing_config_meta = get_option('pointfinderltypes_aslvars');
			if (isset($listing_config_meta[$id])) {
				if (!empty($listing_config_meta[$id]['pflt_advanced_status'])) {
					$advanced_term_status_new = 1;
				}
			}
		}


		if (empty($id)) {

			$setup42_itempagedetails_configuration = (isset($pointfindertheme_option['setup42_itempagedetails_configuration']))? $pointfindertheme_option['setup42_itempagedetails_configuration'] : array();
			$setup4_submitpage_video = $this->PFSAIssetControl('setup4_submitpage_video','',0);
			$setup3_modulessetup_openinghours = $this->PFSAIssetControl('setup3_modulessetup_openinghours','',0);

		}elseif ( $advanced_term_status == 1 && !empty($id)) {

			$setup42_itempagedetails_configuration = (isset($pfadvancedcontrol_options['setupadvancedconfig_'.$id.'_configuration']))? $pfadvancedcontrol_options['setupadvancedconfig_'.$id.'_configuration'] : array();
			$setup4_submitpage_video = $this->PFADVIssetControl('setupadvancedconfig_'.$id.'_videomodule','',0);
			$setup3_modulessetup_openinghours = $this->PFADVIssetControl('setupadvancedconfig_'.$id.'_ohoursmodule','',0);

		}elseif ($st8_nasys == 1 && $advanced_term_status_new == 1 && !empty($id)) {

			$setup42_itempagedetails_configuration = (isset($listing_config_meta[$id]['pflt_configuration']))? $listing_config_meta[$id]['pflt_configuration'] : array();
			$setup4_submitpage_video = (isset($listing_config_meta[$id]['pflt_videomodule']))? $listing_config_meta[$id]['pflt_videomodule'] : array();
			$setup3_modulessetup_openinghours = (isset($listing_config_meta[$id]['pflt_ohoursmodule']))? $listing_config_meta[$id]['pflt_ohoursmodule'] : array();

		}else{

			$setup42_itempagedetails_configuration = (isset($pointfindertheme_option['setup42_itempagedetails_configuration']))? $pointfindertheme_option['setup42_itempagedetails_configuration'] : array();
			$setup4_submitpage_video = $this->PFSAIssetControl('setup4_submitpage_video','',0);
			$setup3_modulessetup_openinghours = $this->PFSAIssetControl('setup3_modulessetup_openinghours','',0);

		}



		/*
		* START : Event Details
		*/
			$output_eventdetails = '';
			$eare_status = $this->PFSAIssetControl('eare_status','',1);

			if(array_key_exists('events', $setup42_itempagedetails_configuration) && $eare_status == 1){
				if ($setup42_itempagedetails_configuration['events']['status'] == 1) {

					$events_title = apply_filters( 'wpml_translate_single_string', $setup42_itempagedetails_configuration['events']['title'], 'admin_texts_pointfindertheme_options', '[pointfindertheme_options][setup42_itempagedetails_configuration][events]title' );

					$setup4_membersettings_dateformat = $this->PFSAIssetControl('setup4_membersettings_dateformat','','1');
					$setup3_modulessetup_openinghours_ex2 = $this->PFSAIssetControl('setup3_modulessetup_openinghours_ex2','','1');
					$eare_times = $this->PFSAIssetControl('eare_times','',1);
					$yearrange1 = $this->PFSAIssetControl('earea_y1','',date("Y"));
					$yearrange2 = $this->PFSAIssetControl('earea_y2','',(date("Y")+10));

					switch ($setup4_membersettings_dateformat) {
						case '1':$date_field_format = 'dd/mm/yy';break;
						case '2':$date_field_format = 'mm/dd/yy';break;
						case '3':$date_field_format = 'yy/mm/dd';break;
						case '4':$date_field_format = 'yy/dd/mm';break;
						default:$date_field_format = 'dd/mm/yy';break;
					}

					switch ($setup4_membersettings_dateformat) {
						case '1':$date_field_format2 = 'd/m/Y';break;
						case '2':$date_field_format2 = 'm/d/Y';break;
						case '3':$date_field_format2 = 'Y/m/d';break;
						case '4':$date_field_format2 = 'Y/d/m';break;
						default:$date_field_format2 = 'd/m/Y';break;
					}


					$output_eventdetails .= '<div class="pfsubmit-title">'.$events_title.'</div>';

					$output_eventdetails .= '<section class="pfsubmit-inner">';

						$field_startdate = ($postid != '') ? get_post_meta($postid,'webbupointfinder_item_field_startdate',true) : '' ;
						$field_enddate = ($postid != '') ? get_post_meta($postid,'webbupointfinder_item_field_enddate',true) : '' ;
						$field_starttime = ($postid != '') ? get_post_meta($postid,'webbupointfinder_item_field_starttime',true) : '' ;
						$field_endtime = ($postid != '') ? get_post_meta($postid,'webbupointfinder_item_field_endtime',true) : '' ;

						if (!empty($field_startdate)) {
							$field_startdate = date($date_field_format2,$field_startdate);
						}
						if (!empty($field_enddate)) {
							$field_enddate = date($date_field_format2,$field_enddate);
						}

						$output_eventdetails .= '<section class="pfsubmit-inner-sub" style="margin-bottom: 0!important;">';


		                    $output_eventdetails .= '<section class="pf-cf-inner-elements pf-cf-inner-halfwidth">';
			                    $output_eventdetails .= '<div class="pf_fr_inner" data-pf-parent="">';
				   					$output_eventdetails .= '<label for="field_startdate" class="lbl-text">'.esc_html__("Start Date","pointfindercoreelements").'</label>';
				               		$output_eventdetails .= '<label class="lbl-ui"><input type="text" name="field_startdate" class="input" value="'.$field_startdate.'"></label>';
				                $output_eventdetails .= '</div>';
			                $output_eventdetails .= '</section>';


			                $output_eventdetails .= '<section class="pf-cf-inner-elements pf-cf-inner-halfwidth">';
			                    $output_eventdetails .= '<div class="pf_fr_inner" data-pf-parent="">';
				   					$output_eventdetails .= '<label for="field_enddate" class="lbl-text">'.esc_html__("End Date","pointfindercoreelements").'</label>';
				               		$output_eventdetails .= '<label class="lbl-ui"><input type="text" name="field_enddate" class="input" value="'.$field_enddate.'"></label>';
				                $output_eventdetails .= '</div>';
			                $output_eventdetails .= '</section>';

			                if ($eare_times == 1) {
			                $output_eventdetails .= '<section class="pf-cf-inner-elements pf-cf-inner-halfwidth">';
			                    $output_eventdetails .= '<div class="pf_fr_inner" data-pf-parent="">';
				   					$output_eventdetails .= '<label for="field_starttime" class="lbl-text">'.esc_html__("Start Time","pointfindercoreelements").'</label>';
				               		$output_eventdetails .= '<label class="lbl-ui"><input type="text" name="field_starttime" class="input" value="'.$field_starttime.'"></label>';
				                $output_eventdetails .= '</div>';
			                $output_eventdetails .= '</section>';


			                $output_eventdetails .= '<section class="pf-cf-inner-elements pf-cf-inner-halfwidth">';
			                    $output_eventdetails .= '<div class="pf_fr_inner" data-pf-parent="">';
				   					$output_eventdetails .= '<label for="field_endtime" class="lbl-text">'.esc_html__("End Time","pointfindercoreelements").'</label>';
				               		$output_eventdetails .= '<label class="lbl-ui"><input type="text" name="field_endtime" class="input" value="'.$field_endtime.'"></label>';
				                $output_eventdetails .= '</div>';
			                $output_eventdetails .= '</section>';
			            	}

							if (is_rtl()) {
								$rtltext_oh = 'true';
							}else{
								$rtltext_oh = 'false';
							}




							if (!empty($yearrange1) && !empty($yearrange2)) {
								$yearrangesetting = 'yearRange:"'.$yearrange1.':'.$yearrange2.'",';
							}elseif (!empty($yearrange1) && empty($yearrange2)) {
								$yearrangesetting = 'yearRange:"'.$yearrange1.':'.date("Y").'",';
							}else{
								$yearrangesetting = '';
							}


			                $output_eventdetails .= "<script>
							(function($) {
							'use strict';";
								if ($eare_times == 1) {
								$output_eventdetails .= "
								$.timepicker.timeRange(
									$('input[name=\"field_starttime\"]'),
									$('input[name=\"field_endtime\"]'),
									{
										minInterval: (1000*60*60),
										timeFormat: 'HH:mm',
										start: {},
										end: {},
										timeOnlyTitle: '".esc_html__('Choose Time','pointfindercoreelements')."',
										timeText: '".esc_html__('Time','pointfindercoreelements')."',
										hourText: '".esc_html__('Hour','pointfindercoreelements')."',
										currentText: '".esc_html__('Now','pointfindercoreelements')."',
										minuteText: '".esc_html__('Minute','pointfindercoreelements')."',
										secondText: '".esc_html__('Second','pointfindercoreelements')."',
										millisecText: '".esc_html__('Millisecond','pointfindercoreelements')."',
										microsecText: '".esc_html__('Microsecond','pointfindercoreelements')."',
										timezoneText: '".esc_html__('Time Zone','pointfindercoreelements')."',
										currentText: '".esc_html__('Now','pointfindercoreelements')."',
										closeText: '".esc_html__('Done','pointfindercoreelements')."',
										isRTL: ".$rtltext_oh."
									}
								);
								";
								}
								$output_eventdetails .= "
								$('input[name=\"field_startdate\"]').datepicker({
							      changeMonth: true,
							      changeYear: true,
							      isRTL: $rtltext_oh,
							      dateFormat: '$date_field_format',
							      firstDay: $setup3_modulessetup_openinghours_ex2,
							      $yearrangesetting
							      prevText: '',
							      nextText: '',
							      onClose: function( selectedDate ) {
							        $('input[name=\"field_enddate\"]').datepicker( 'option', 'minDate', selectedDate );
							      }
							    });

							    $('input[name=\"field_enddate\"]').datepicker({
							      changeMonth: true,
							      changeYear: true,
							      isRTL: $rtltext_oh,
							      dateFormat: '$date_field_format',
							      firstDay: $setup3_modulessetup_openinghours_ex2,
							      $yearrangesetting
							      prevText: '',
							      nextText: '',
							      onClose: function( selectedDate ) {
							        $('input[name=\"field_startdate\"]').datepicker( 'option', 'maxDate', selectedDate );
							      }
							    });
							    ";
							    $earea_vcheck = $this->PFSAIssetControl('earea_vcheck','',0);
							    $earea_verror = $this->PFSAIssetControl('earea_verror','',esc_html__('Please choose event date and time.', 'pointfindercoreelements'));
							    if ($place != 'backend') {
							    	$output_eventdetails .= $this->PFValidationCheckWriteEx($earea_vcheck,$earea_verror,'field_starttime');
								    $output_eventdetails .= $this->PFValidationCheckWriteEx($earea_vcheck,$earea_verror,'field_endtime');
								    $output_eventdetails .= $this->PFValidationCheckWriteEx($earea_vcheck,$earea_verror,'field_startdate');
								    $output_eventdetails .= $this->PFValidationCheckWriteEx($earea_vcheck,$earea_verror,'field_enddate');
							    }
							    


							    $output_eventdetails .= "
							})(jQuery);</script>
							";


	                    $output_eventdetails .= '</section>';
					$output_eventdetails .= '</section>';
				}
			}

		/*
		* END : Event Details
		*/



		/*
		* START : Video
		*/
			if($setup4_submitpage_video == 1 && $place != 'backend'){

				$pfuploadfeaturedvideo = ($postid != '') ? get_post_meta($postid, 'webbupointfinder_item_video', true) : '' ;

				$output_video .= '<div class="pfsubmit-title pf-videomodule-div">'.esc_html__('VIDEO','pointfindercoreelements').'</div>';
				$output_video .= '<section class="pfsubmit-inner pf-videomodule-div">';
				$output_video .= '<section class="pfsubmit-inner-sub">';
				$output_video .= '<small style="margin-bottom:4px">'.esc_html__('Please copy & paste video sharing url. ','pointfindercoreelements').'</small>';
					$output_video .= '
	                <label for="file" class="lbl-ui" >
	                <input class="input" name="pfuploadfeaturedvideo" value="'.$pfuploadfeaturedvideo.'">
	                </label>
					';
				$output_video .= '</section>';
				$output_video .= '</section>';

			}
		/*
		* END : Video
		*/


		/*
		* START : Opening Hours
		*/
			if($setup3_modulessetup_openinghours == 1 ){

				$setup3_modulessetup_openinghours_ex = $this->PFSAIssetControl('setup3_modulessetup_openinghours_ex','','1');
				$setup3_modulessetup_openinghours_ex2 = $this->PFSAIssetControl('setup3_modulessetup_openinghours_ex2','','1');

				if($setup3_modulessetup_openinghours_ex == 1){

					$output_ohours .= '<div class="pfsubmit-title pf-openinghours-div">'.esc_html__('Opening Hours','pointfindercoreelements').' <small>('.esc_html__('Leave blank to show closed','pointfindercoreelements' ).')</small></div>';
					$output_ohours .= '<section class="pfsubmit-inner pf-openinghours-div">';

					$output_ohours .= '<section class="pfsubmit-inner-sub">';
					$output_ohours .= '
	                <label class="lbl-ui">
	                <input type="text" name="o1" class="input" placeholder="'.esc_html__('Monday-Friday: 09:00 - 22:00','pointfindercoreelements').'" value="'.esc_attr(get_post_meta($postid,'webbupointfinder_items_o_o1',true)).'" />
		            </label>
		            </section>
		            ';
		            $output_ohours .= '</section>';

				}elseif( $setup3_modulessetup_openinghours_ex == 0){


					if ($setup3_modulessetup_openinghours_ex2 == 1) {
						$text_ohours1 = esc_html__('Monday','pointfindercoreelements');
						$text_ohours2 = esc_html__('Sunday','pointfindercoreelements');
					}else{
						$text_ohours1 = esc_html__('Sunday','pointfindercoreelements');
						$text_ohours2 = esc_html__('Monday','pointfindercoreelements');
					}

					$ohours_first = '<section>
						<label for="o1" class="lbl-text">'.esc_html__('Monday','pointfindercoreelements').':</label>
			            <label class="lbl-ui">
			            <input type="text" name="o1" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($postid,'webbupointfinder_items_o_o1',true)).'" />
			            </label>
			            </section>';

			        $ohours_last = '<section>
			            <label for="o7" class="lbl-text">'.esc_html__('Sunday','pointfindercoreelements').':</label>
			            <label class="lbl-ui">
			            <input type="text" name="o7" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($postid,'webbupointfinder_items_o_o7',true)).'"/>
			            </label>
			            </section>';

					if ($setup3_modulessetup_openinghours_ex2 != 1) {
						$ohours_first = $ohours_last . $ohours_first;
						$ohours_last = '';
					}

					$output_ohours .= '<div class="pfsubmit-title pf-openinghours-div">'.esc_html__('Opening Hours','pointfindercoreelements').' <small>('.esc_html__('Leave blank to show closed','pointfindercoreelements' ).')</small></div>';
					$output_ohours .= '<section class="pfsubmit-inner pf-openinghours-div">';
					$output_ohours .= '<section class="pfsubmit-inner-sub">';

					$output_ohours .= $ohours_first;
					$output_ohours .= '
			            <section>
			            <label for="o2" class="lbl-text">'.esc_html__('Tuesday','pointfindercoreelements').':</label>
		                <label class="lbl-ui">
		                <input type="text" name="o2" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($postid,'webbupointfinder_items_o_o2',true)).'"/>
			            </label>
			            </section>
			            <section>
			            <label for="o3" class="lbl-text">'.esc_html__('Wednesday','pointfindercoreelements').':</label>
		                <label class="lbl-ui">
		                <input type="text" name="o3" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($postid,'webbupointfinder_items_o_o3',true)).'"/>
			            </label>
			            </section>
			            <section>
			            <label for="o4" class="lbl-text">'.esc_html__('Thursday','pointfindercoreelements').':</label>
		                <label class="lbl-ui">
		                <input type="text" name="o4" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($postid,'webbupointfinder_items_o_o4',true)).'"/>
			            </label>
			            </section>
			            <section>
			            <label for="o5" class="lbl-text">'.esc_html__('Friday','pointfindercoreelements').':</label>
		                <label class="lbl-ui">
		                <input type="text" name="o5" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($postid,'webbupointfinder_items_o_o5',true)).'"/>
			            </label>
			            </section>
			            <section>
			            <label for="o6" class="lbl-text">'.esc_html__('Saturday','pointfindercoreelements').':</label>
		                <label class="lbl-ui">
		                <input type="text" name="o6" class="input" placeholder="09:00 - 22:00" value="'.esc_attr(get_post_meta($postid,'webbupointfinder_items_o_o6',true)).'"/>
			            </label>
			            </section>
		            ';
		            $output_ohours .= $ohours_last;
		            $output_ohours .= '</section>';
		            $output_ohours .= '</section>';

				}elseif($setup3_modulessetup_openinghours_ex == 2){

					$output_ohours .= '<div class="pfsubmit-title pf-openinghours-div">'.esc_html__('Opening Hours','pointfindercoreelements').' <small>('.esc_html__('Leave blank to show closed','pointfindercoreelements' ).')</small></div>';

					$output_ohours .= '<section class="pfsubmit-inner pf-openinghours-div">';
					$output_ohours .= '<section class="pfsubmit-inner-sub">';

					if (is_rtl()) {
						$rtltext_oh = 'true';
					}else{
						$rtltext_oh = 'false';
					}

					for ($i=0; $i < 7; $i++) {
						$o_value[$i] = get_post_meta($postid,'webbupointfinder_items_o_o'.($i+1),true);
						if (!empty($o_value[$i])) {
							$o_value[$i] = explode("-", $o_value[$i]);
							if (count($o_value[$i]) < 1) {
								$o_value[$i] = array("","");
							}elseif (count($o_value[$i]) < 2) {
								$o_value[$i][1] = "";
							}
						}else{
							$o_value[$i] = array("","");
						}


						$output_ohours .= "<script>
						(function($) {
						'use strict';
						$.timepicker.timeRange(
							$('input[name=\"o".($i+1)."_1\"]'),
							$('input[name=\"o".($i+1)."_2\"]'),
							{
								minInterval: (1000*60*60),
								timeFormat: 'HH:mm',
								start: {},
								end: {},
								timeOnlyTitle: '".esc_html__('Choose Time','pointfindercoreelements')."',
								timeText: '".esc_html__('Time','pointfindercoreelements')."',
								hourText: '".esc_html__('Hour','pointfindercoreelements')."',
								currentText: '".esc_html__('Now','pointfindercoreelements')."',
								minuteText: '".esc_html__('Minute','pointfindercoreelements')."',
								secondText: '".esc_html__('Second','pointfindercoreelements')."',
								millisecText: '".esc_html__('Millisecond','pointfindercoreelements')."',
								microsecText: '".esc_html__('Microsecond','pointfindercoreelements')."',
								timezoneText: '".esc_html__('Time Zone','pointfindercoreelements')."',
								currentText: '".esc_html__('Now','pointfindercoreelements')."',
								closeText: '".esc_html__('Done','pointfindercoreelements')."',
								isRTL: ".$rtltext_oh."
							}
						);
					})(jQuery);</script>
						";
					}


					$ohours_first = '
						<section>
						<label for="o1" class="lbl-text">'.esc_html__('Monday','pointfindercoreelements').':</label>
		   				<div class="row">
		   					<div class="col6 first">
				                <label class="lbl-ui">
				                <input type="text" name="o1_1" class="input" placeholder="'.__('Start','pointfindercoreelements').'" value="'.$o_value[0][0].'" />
					            </label>
		   					</div>
		   					<div class="col6 last">
				                <label class="lbl-ui">
				                <input type="text" name="o1_2" class="input" placeholder="'.__('End','pointfindercoreelements').'" value="'.$o_value[0][1].'" />
					            </label>
		   					</div>
		   				</div>
			            </section>
					';
					$ohours_last = '
						<section>
			            <label for="o7" class="lbl-text">'.esc_html__('Sunday','pointfindercoreelements').':</label>
			            <div class="row">
		                	<div class="col6 first">
				                <label class="lbl-ui">
				                <input type="text" name="o7_1" class="input" placeholder="'.__('Start','pointfindercoreelements').'" value="'.$o_value[6][0].'" />
					            </label>
		   					</div>
		   					<div class="col6 last">
				                <label class="lbl-ui">
				                <input type="text" name="o7_2" class="input" placeholder="'.__('End','pointfindercoreelements').'" value="'.$o_value[6][1].'" />
					            </label>
		   					</div>
		   				</div>
			            </section>

					';

					if ($setup3_modulessetup_openinghours_ex2 != 1) {
						$ohours_first = $ohours_last . $ohours_first;
						$ohours_last = '';
					}


					$output_ohours .= $ohours_first;
					$output_ohours .= '
			            <section>
			            <label for="o2" class="lbl-text">'.esc_html__('Tuesday','pointfindercoreelements').':</label>
			            <div class="row">
		                	<div class="col6 first">
				                <label class="lbl-ui">
				                <input type="text" name="o2_1" class="input" placeholder="'.__('Start','pointfindercoreelements').'" value="'.$o_value[1][0].'" />
					            </label>
		   					</div>
		   					<div class="col6 last">
				                <label class="lbl-ui">
				                <input type="text" name="o2_2" class="input" placeholder="'.__('End','pointfindercoreelements').'" value="'.$o_value[1][1].'" />
					            </label>
		   					</div>
		   				</div>
			            </section>
			            <section>
			            <label for="o3" class="lbl-text">'.esc_html__('Wednesday','pointfindercoreelements').':</label>
			            <div class="row">
		                	<div class="col6 first">
				                <label class="lbl-ui">
				                <input type="text" name="o3_1" class="input" placeholder="'.__('Start','pointfindercoreelements').'" value="'.$o_value[2][0].'" />
					            </label>
		   					</div>
		   					<div class="col6 last">
				                <label class="lbl-ui">
				                <input type="text" name="o3_2" class="input" placeholder="'.__('End','pointfindercoreelements').'" value="'.$o_value[2][1].'" />
					            </label>
		   					</div>
		   				</div>
			            </section>
			            <section>
			            <label for="o4" class="lbl-text">'.esc_html__('Thursday','pointfindercoreelements').':</label>
			            <div class="row">
		                	<div class="col6 first">
				                <label class="lbl-ui">
				                <input type="text" name="o4_1" class="input" placeholder="'.__('Start','pointfindercoreelements').'" value="'.$o_value[3][0].'" />
					            </label>
		   					</div>
		   					<div class="col6 last">
				                <label class="lbl-ui">
				                <input type="text" name="o4_2" class="input" placeholder="'.__('End','pointfindercoreelements').'" value="'.$o_value[3][1].'" />
					            </label>
		   					</div>
		   				</div>
			            </section>
			            <section>
			            <label for="o5" class="lbl-text">'.esc_html__('Friday','pointfindercoreelements').':</label>
			            <div class="row">
		                	<div class="col6 first">
				                <label class="lbl-ui">
				                <input type="text" name="o5_1" class="input" placeholder="'.__('Start','pointfindercoreelements').'" value="'.$o_value[4][0].'" />
					            </label>
		   					</div>
		   					<div class="col6 last">
				                <label class="lbl-ui">
				                <input type="text" name="o5_2" class="input" placeholder="'.__('End','pointfindercoreelements').'" value="'.$o_value[4][1].'" />
					            </label>
		   					</div>
		   				</div>
			            </section>
			            <section>
			            <label for="o6" class="lbl-text">'.esc_html__('Saturday','pointfindercoreelements').':</label>
			            <div class="row">
		                	<div class="col6 first">
				                <label class="lbl-ui">
				                <input type="text" name="o6_1" class="input" placeholder="'.__('Start','pointfindercoreelements').'" value="'.$o_value[5][0].'" />
					            </label>
		   					</div>
		   					<div class="col6 last">
				                <label class="lbl-ui">
				                <input type="text" name="o6_2" class="input" placeholder="'.__('End','pointfindercoreelements').'" value="'.$o_value[5][1].'" />
					            </label>
		   					</div>
		   				</div>
			            </section>
		            ';
		            $output_ohours .= $ohours_last;
		            $output_ohours .= '</section>';
		            $output_ohours .= '</section>';

				}
			}else{
				if ($place == 'backend' ) {
					$output_ohours = esc_html__("Opening Hours not enabled for this Listing Type.","pointfindercoreelements");
		   		}
			}
		/*
		* END : Opening Hours
		*/


		/*
		* START : Custom Tabs
		*/
			$stp4_ctt1 = $this->PFSAIssetControl('stp4_ctt1','',0);
			$stp4_ctt2 = $this->PFSAIssetControl('stp4_ctt2','',0);
			$stp4_ctt3 = $this->PFSAIssetControl('stp4_ctt3','',0);
			$stp4_ctt4 = $this->PFSAIssetControl('stp4_ctt4','',0);
			$stp4_ctt5 = $this->PFSAIssetControl('stp4_ctt5','',0);
			$stp4_ctt6 = $this->PFSAIssetControl('stp4_ctt6','',0);

			if (($stp4_ctt1 == 1 || $stp4_ctt2 == 1 || $stp4_ctt3 == 1 || $stp4_ctt4 == 1 || $stp4_ctt5 == 1 || $stp4_ctt6 == 1) && $place != 'backend') {


				$ctt1tx = $ctt2tx = $ctt3tx = $ctt4tx = $ctt5tx = $ctt6tx ='';

				/* Start: Custom Tab 1 */
					if(array_key_exists('customtab1', $setup42_itempagedetails_configuration) && $stp4_ctt1 == 1){

						if ($setup42_itempagedetails_configuration['customtab1']['status'] == 1) {
							$stp4_ctt1_t = $this->PFSAIssetControl('stp4_ctt1_t','',0);
							$customtab1_title = apply_filters( 'wpml_translate_single_string', $setup42_itempagedetails_configuration['customtab1']['title'], 'admin_texts_pointfindertheme_options', '[pointfindertheme_options][setup42_itempagedetails_configuration][customtab1]title' );

							$output_customtab .= '<div class="pfsubmit-title">'.$customtab1_title.'</div>';

							$output_customtab .= '<section class="pfsubmit-inner">';
								$ct1_content = ($postid != '') ? get_post_meta($postid,'webbupointfinder_item_custombox1',true) : '' ;

								$output_customtab .= '
								<section class="pfsubmit-inner-sub">
			                        <label class="lbl-ui">';
			                        if ($stp4_ctt1_t == 1) {
			                        	$ed_settings = array(
											'media_buttons' => false,
											'teeny' => true,
											'editor_class' => 'textarea mini',
											'textarea_name' => 'webbupointfinder_item_custombox1',
											'drag_drop_upload' => false,
											'dfw' => false,
											'tinymce' => true,
											'quicktags' => false
										);
										ob_start();
											wp_editor( $ct1_content, 'webbupointfinder_item_custombox1', $ed_settings );
											$content = ob_get_contents();
										ob_end_clean();
										$ctt1tx = ' textareaadv';
										$output_customtab .= $content;
			                        }
			                        $output_customtab .= '<textarea id="webbupointfinder_item_custombox1" name="webbupointfinder_item_custombox1" class="textarea mini'.$ctt1tx.'">'.$ct1_content.'</textarea>';
			                    $output_customtab .= '</label></section>';
							$output_customtab .= '</section>';
						}
					}
				/* End: Custom Tab 1 */

				/* Start: Custom Tab 2 */
					if(array_key_exists('customtab2', $setup42_itempagedetails_configuration) && $stp4_ctt2 == 1){

						if ($setup42_itempagedetails_configuration['customtab2']['status'] == 1) {
							$stp4_ctt2_t = $this->PFSAIssetControl('stp4_ctt2_t','',0);
							$customtab2_title = apply_filters( 'wpml_translate_single_string', $setup42_itempagedetails_configuration['customtab2']['title'], 'admin_texts_pointfindertheme_options', '[pointfindertheme_options][setup42_itempagedetails_configuration][customtab2]title' );

							$output_customtab .= '<div class="pfsubmit-title">'.$customtab2_title.'</div>';


							$output_customtab .= '<section class="pfsubmit-inner">';
								$ct2_content = ($postid != '') ? get_post_meta($postid,'webbupointfinder_item_custombox2',true) : '' ;

								$output_customtab .= '
								<section class="pfsubmit-inner-sub">
			                        <label class="lbl-ui">';

			                        if ($stp4_ctt2_t == 1) {
			                        	$ed_settings = array(
											'media_buttons' => false,
											'teeny' => true,
											'editor_class' => 'textarea mini',
											'textarea_name' => 'webbupointfinder_item_custombox2',
											'drag_drop_upload' => false,
											'dfw' => false,
											'tinymce' => true,
											'quicktags' => false
										);
										ob_start();
											wp_editor( $ct2_content, 'webbupointfinder_item_custombox2', $ed_settings );
											$content = ob_get_contents();
										ob_end_clean();
										$ctt2tx = ' textareaadv';
										$output_customtab .= $content;
			                        }

			                        $output_customtab .= '<textarea id="webbupointfinder_item_custombox2" name="webbupointfinder_item_custombox2" class="textarea mini'.$ctt2tx.'">'.$ct2_content.'</textarea>';
			                    $output_customtab .= '</label></section>';
							$output_customtab .= '</section>';
						}
					}
				/* End: Custom Tab 2 */

				/* Start: Custom Tab 3 */
					if(array_key_exists('customtab3', $setup42_itempagedetails_configuration) && $stp4_ctt3 == 1){
						if ($setup42_itempagedetails_configuration['customtab3']['status'] == 1) {
							$stp4_ctt3_t = $this->PFSAIssetControl('stp4_ctt3_t','',0);

							$customtab3_title = apply_filters( 'wpml_translate_single_string', $setup42_itempagedetails_configuration['customtab3']['title'], 'admin_texts_pointfindertheme_options', '[pointfindertheme_options][setup42_itempagedetails_configuration][customtab3]title' );

							$output_customtab .= '<div class="pfsubmit-title">'.$customtab3_title.'</div>';


							$output_customtab .= '<section class="pfsubmit-inner">';
								$ct3_content = ($postid != '') ? get_post_meta($postid,'webbupointfinder_item_custombox3',true) : '' ;

								$output_customtab .= '
								<section class="pfsubmit-inner-sub">
			                        <label class="lbl-ui">';

			                        if ($stp4_ctt3_t == 1) {
			                        	$ed_settings = array(
											'media_buttons' => false,
											'teeny' => true,
											'editor_class' => 'textarea mini',
											'textarea_name' => 'webbupointfinder_item_custombox3',
											'drag_drop_upload' => false,
											'dfw' => false,
											'tinymce' => true,
											'quicktags' => false
										);
										ob_start();
											wp_editor( $ct3_content, 'webbupointfinder_item_custombox3', $ed_settings );
											$content = ob_get_contents();
										ob_end_clean();
										$ctt3tx = ' textareaadv';
										$output_customtab .= $content;
			                        }

			                        $output_customtab .= '<textarea id="webbupointfinder_item_custombox3" name="webbupointfinder_item_custombox3" class="textarea mini'.$ctt3tx.'">'.$ct3_content.'</textarea>';
			                    $output_customtab .= '</label></section>';
							$output_customtab .= '</section>';
						}
					}
				/* End: Custom Tab 3 */

				/* Start: Custom Tab 4 */
					if(array_key_exists('customtab4', $setup42_itempagedetails_configuration) && $stp4_ctt4 == 1){
						if ($setup42_itempagedetails_configuration['customtab4']['status'] == 1) {
							$stp4_ctt4_t = $this->PFSAIssetControl('stp4_ctt4_t','',0);

							$customtab4_title = apply_filters( 'wpml_translate_single_string', $setup42_itempagedetails_configuration['customtab4']['title'], 'admin_texts_pointfindertheme_options', '[pointfindertheme_options][setup42_itempagedetails_configuration][customtab4]title' );

							$output_customtab .= '<div class="pfsubmit-title">'.$customtab4_title.'</div>';


							$output_customtab .= '<section class="pfsubmit-inner">';
								$ct4_content = ($postid != '') ? get_post_meta($postid,'webbupointfinder_item_custombox4',true) : '' ;

								$output_customtab .= '
								<section class="pfsubmit-inner-sub">
			                        <label class="lbl-ui">';

			                        if ($stp4_ctt4_t == 1) {
			                        	$ed_settings = array(
											'media_buttons' => false,
											'teeny' => true,
											'editor_class' => 'textarea mini',
											'textarea_name' => 'webbupointfinder_item_custombox4',
											'drag_drop_upload' => false,
											'dfw' => false,
											'tinymce' => true,
											'quicktags' => false
										);
										ob_start();
											wp_editor( $ct4_content, 'webbupointfinder_item_custombox4', $ed_settings );
											$content = ob_get_contents();
										ob_end_clean();
										$ctt4tx = ' textareaadv';
										$output_customtab .= $content;
			                        }

			                        $output_customtab .= '<textarea id="webbupointfinder_item_custombox4" name="webbupointfinder_item_custombox4" class="textarea mini'.$ctt4tx.'">'.$ct4_content.'</textarea>';
			                    $output_customtab .= '</label></section>';
							$output_customtab .= '</section>';
						}
					}
				/* End: Custom Tab 4 */

				/* Start: Custom Tab 5 */
					if(array_key_exists('customtab5', $setup42_itempagedetails_configuration) && $stp4_ctt5 == 1){
						if ($setup42_itempagedetails_configuration['customtab5']['status'] == 1) {
							$stp4_ctt5_t = $this->PFSAIssetControl('stp4_ctt5_t','',0);
							$customtab5_title = apply_filters( 'wpml_translate_single_string', $setup42_itempagedetails_configuration['customtab5']['title'], 'admin_texts_pointfindertheme_options', '[pointfindertheme_options][setup42_itempagedetails_configuration][customtab5]title' );
							

							$output_customtab .= '<div class="pfsubmit-title">'.$customtab5_title.'</div>';


							$output_customtab .= '<section class="pfsubmit-inner">';
								$ct5_content = ($postid != '') ? get_post_meta($postid,'webbupointfinder_item_custombox5',true) : '' ;

								$output_customtab .= '
								<section class="pfsubmit-inner-sub">
			                        <label class="lbl-ui">';

			                        if ($stp4_ctt5_t == 1) {
			                        	$ed_settings = array(
											'media_buttons' => false,
											'teeny' => true,
											'editor_class' => 'textarea mini',
											'textarea_name' => 'webbupointfinder_item_custombox5',
											'drag_drop_upload' => false,
											'dfw' => false,
											'tinymce' => true,
											'quicktags' => false
										);
										ob_start();
											wp_editor( $ct5_content, 'webbupointfinder_item_custombox5', $ed_settings );
											$content = ob_get_contents();
										ob_end_clean();
										$ctt5tx = ' textareaadv';
										$output_customtab .= $content;
			                        }

			                        $output_customtab .= '<textarea id="webbupointfinder_item_custombox5" name="webbupointfinder_item_custombox5" class="textarea mini'.$ctt5tx.'">'.$ct5_content.'</textarea>';
			                    $output_customtab .= '</label></section>';
							$output_customtab .= '</section>';
						}
					}
				/* End: Custom Tab 5 */

				/* Start: Custom Tab 6 */
					if(array_key_exists('customtab6', $setup42_itempagedetails_configuration) && $stp4_ctt6 == 1){
						if ($setup42_itempagedetails_configuration['customtab6']['status'] == 1) {
							$stp4_ctt6_t = $this->PFSAIssetControl('stp4_ctt6_t','',0);
							$customtab6_title = apply_filters( 'wpml_translate_single_string', $setup42_itempagedetails_configuration['customtab6']['title'], 'admin_texts_pointfindertheme_options', '[pointfindertheme_options][setup42_itempagedetails_configuration][customtab6]title' );


							$output_customtab .= '<div class="pfsubmit-title">'.$customtab6_title.'</div>';


							$output_customtab .= '<section class="pfsubmit-inner">';
								$ct6_content = ($postid != '') ? get_post_meta($postid,'webbupointfinder_item_custombox6',true) : '' ;

								$output_customtab .= '
								<section class="pfsubmit-inner-sub">
			                        <label class="lbl-ui">';

			                        if ($stp4_ctt6_t == 1) {
			                        	$ed_settings = array(
											'media_buttons' => false,
											'teeny' => true,
											'editor_class' => 'textarea mini',
											'textarea_name' => 'webbupointfinder_item_custombox6',
											'drag_drop_upload' => false,
											'dfw' => false,
											'tinymce' => true,
											'quicktags' => false
										);
										ob_start();
											wp_editor( $ct6_content, 'webbupointfinder_item_custombox6', $ed_settings );
											$content = ob_get_contents();
										ob_end_clean();
										$ctt6tx = ' textareaadv';
										$output_customtab .= $content;
			                        }

			                        $output_customtab .= '<textarea id="webbupointfinder_item_custombox6" name="webbupointfinder_item_custombox6" class="textarea mini'.$ctt6tx.'">'.$ct6_content.'</textarea>';
			                    $output_customtab .= '</label></section>';
							$output_customtab .= '</section>';
						}
					}
				/* End: Custom Tab 6 */
			}
		/*
		* END : Custom Tabs
		*/

		echo json_encode(array('features' => $output_features, 'itemtypes' => $output_itemtypes, 'conditions' => $output_conditions, 'customfields' => $output_customfields, 'customtabs' => $output_customtab, 'video'=>$output_video, 'ohours'=> $output_ohours,'eventdetails' => $output_eventdetails));

		die();
	} 

	private function PFValidationCheckWriteEx($field_validation_check,$field_validation_text,$itemid){
		
		$itemname = (string)trim($itemid);
		$itemname = (strpos($itemname, '[]') == false) ? $itemname : "'".$itemname."'" ;

		if($field_validation_check == 1){
			return '$("[name=\''.$itemname.'\']").rules( "add", {
			  required: true,
			  messages: {
			    required: "'.$field_validation_text.'",
			  }
			});';
		}
	}

	private function PFgetfield($params = array()){
	    $defaults = array(
	        'fieldname' => '',
	        'fieldtype' => '',
	        'fieldtitle' => '',
	        'fieldsubtype' => '',
	        'fieldparent' => '',
	        'fieldtooltip' => '',
	        'fieldoptions' => '',
	        'fielddefault' => '',
	        'place' => ''
	    );

	    $params = array_merge($defaults, $params);
	    	$output = '';

	    	if($this->PFControlEmptyArr($params['fieldparent'])){
   				$output .= '<div class="pf_fr_inner" data-pf-parent="'.implode(',', $params['fieldparent']).'">';
   			}else{
   				$output .= '<div class="pf_fr_inner" data-pf-parent="">';
   			}

	   		switch ($params['fieldtype']) {
	   			/**
	   			*Text
	   			**/
		   			case 'text':
		   				$description = ($params['fieldtooltip']!='') ? ' <a href="javascript:;" class="info-tip" aria-describedby="helptooltip"> ? <span role="tooltip">'.$params['fieldtooltip'].'</span></a>' : '' ;
			   			$output .= '
			   				<label for="'.$params['fieldname'].'" class="lbl-text">'.$params['fieldtitle'].' '.$description.'</label>
			                <label class="lbl-ui">';
			            if (is_array($params['fielddefault'])) {
			            	if(isset($params['fielddefault'][0])){
			            		$checkvalue = ($params['fielddefault'][0]!='') ? ' value="'.$params['fielddefault'][0].'"' : '' ;
			            	}else{
			            		$checkvalue = '';
			            	}
			            }else{
			            	$checkvalue = ($params['fielddefault']!='') ? ' value="'.$params['fielddefault'].'"' : '' ;
			            }
			            if($params['fieldsubtype'] == 4){
			            	$output .= '<input type="text" name="'.$params['fieldname'].'"  class="input"'.$checkvalue.'/>';
			            	
			            	/*onKeyPress="return pointfinder_numbersonly(this, event)"*/
			            	if ($params['place'] != 'backend') {
			            		
			            	
			            	$output .= '<script>
										(function($) {
										"use strict";

										$("[name=\''.$params['fieldname'].'\']").rules( "add", {
										  number: true,
										  messages: {
										    number: "'.esc_html__( "Please add numeric value and only dot (.) allowed for decimals.", "pointfindercoreelements" ).'"
										  }
										});})(jQuery);</script>';
							}

			            	$p_control = $this->PFCFIssetControl('setupcustomfields_'.$params['fieldname'].'_currency_check','',0);

							if($p_control == 1){
				            	$CFPrefix = $this->PFCFIssetControl('setupcustomfields_'.$params['fieldname'].'_currency_prefix','','');
								$CFSuffix = $this->PFCFIssetControl('setupcustomfields_'.$params['fieldname'].'_currency_suffix','','');
				            }
				        }else{
				        	$output .= '<input type="text" name="'.$params['fieldname'].'" class="input"'.$checkvalue.' />';
				        }
				        /*if ($params['fieldtooltip']!='') {
				        	$output .= '<b class="tooltip left-bottom"><em>'.$params['fieldtooltip'].'</em></b>';
				        }*/
			            $output .= '</label>';
		   			break;
	   			/**
	   			*TextArea
	   			**/
		   			case 'textarea':
		   				$description = ($params['fieldtooltip']!='') ? ' <a href="javascript:;" class="info-tip" aria-describedby="helptooltip"> ? <span role="tooltip">'.$params['fieldtooltip'].'</span></a>' : '' ;
			   			$output .= '
			   				<label for="'.$params['fieldname'].'" class="lbl-text">'.$params['fieldtitle'].' '.$description.'</label>
			                <label class="lbl-ui">';
			            if (is_array($params['fielddefault'])) {
			            	if(isset($params['fielddefault'][0])){
			            		$checkvalue = ($params['fielddefault'][0]!='') ? $params['fielddefault'][0] : '' ;
			            	}else{
			            		$checkvalue = '';
			            	}
			            }else{
			            	$checkvalue = ($params['fielddefault']!='') ? $params['fielddefault'] : '' ;
			            }
				        $output .= '<textarea id="desc" name="'.$params['fieldname'].'" class="textarea mini" >'.$checkvalue.'</textarea>';

				        /*if ($params['fieldtooltip']!='') {
				        	$output .= '<b class="tooltip left-bottom"><em>'.$params['fieldtooltip'].'</em></b>';
				        }*/
			            $output .= '</label>';
		   			break;
	   			/**
	   			*Select
	   			**/
		   			case 'select':
		   			$description = ($params['fieldtooltip']!='') ? ' <a href="javascript:;" class="info-tip" aria-describedby="helptooltip">?<span role="tooltip">'.$params['fieldtooltip'].'</span></a>' : '' ;
			   			$output .= '
			   				<label for="'.$params['fieldname'].'" class="lbl-text">'.$params['fieldtitle'].' '.$description.'</label>
			                <label class="lbl-ui select">';

				        $output .= '<select name="'.$params['fieldname'].'">';

				        $output .= '<option value="">'.esc_html__('Please select','pointfindercoreelements').'</option>';

				        $ikk = 0;

				        foreach ($this->pfstring2KeyedArray($params['fieldoptions']) as $key => $value) {


				        	if (is_array($params['fielddefault'])) {
				            	$checkvalue = (in_array($key,$params['fielddefault'])) ? ' selected' : '' ;
				            }else{
				            	$checkvalue = ($params['fielddefault']!='' && strcmp($params['fielddefault'], $key) == 0) ? ' selected' : '' ;
				            }

				        	if (class_exists('SitePress')) {
				            	 //$exvalue = explode('=', icl_t('admin_texts_pfcustomfields_options', '[pfcustomfields_options][setupcustomfields_'.$params['fieldname'].'_rvalues]'.$ikk, $key.'='.$value));
				            	 $exvalue = apply_filters( 'wpml_translate_single_string', $key.'='.$value, 'admin_texts_pfcustomfields_options', '[pfcustomfields_options][setupcustomfields_'.$params['fieldname'].'_rvalues]'.$ikk);
				            	 $exvalue = explode('=', $exvalue);

				            	 if (isset($exvalue[1])) {
				            	 	$output .= '<option value="'.$key.'"'.$checkvalue.'>'.$exvalue[1].'</option>';
				            	 }else{
				            	 	$output .= '<option value="'.$key.'"'.$checkvalue.'>'.$value.'</option>';
				            	 }
				            }else{
				            	 $output .= '<option value="'.$key.'"'.$checkvalue.'>'.$value.'</option>';
				            }

				            $ikk++;
				        }
			            $output .= '</select>';
			            $output .= '</label>';
		   			break;
	   			/**
	   			*Select Multiple
	   			**/
		   			case 'selectmulti':

		   			$description = ($params['fieldtooltip']!='') ? ' <a href="javascript:;" class="info-tip" aria-describedby="helptooltip"> ? <span role="tooltip">'.$params['fieldtooltip'].'</span></a>' : '' ;
			   			$output .= '
			   				<label for="'.$params['fieldname'].'" class="lbl-text">'.$params['fieldtitle'].' '.$description.'</label>
			                <label class="lbl-ui select-multiple">';

				        $output .= '<select name="'.$params['fieldname'].'[]" multiple="multiple" size="6">';

				        $ikk = 0;

				        foreach ($this->pfstring2KeyedArray($params['fieldoptions']) as $key => $value) {

				        	if (is_array($params['fielddefault'])) {
				            	$checkvalue = (in_array($key,$params['fielddefault'])) ? ' selected' : '' ;
				            }else{
				            	$checkvalue = ($params['fielddefault']!='' && strcmp($params['fielddefault'], $key) == 0) ? ' selected' : '' ;
				            }

				        	/*$output .= '<option value="'.$key.'"'.$checkvalue.'>'.$value.'</option>';*/

				        	if (class_exists('SitePress')) {
				            	 //$exvalue = explode('=', icl_t('admin_texts_pfcustomfields_options', '[pfcustomfields_options][setupcustomfields_'.$params['fieldname'].'_rvalues]'.$ikk, $key.'='.$value));
				            	 $exvalue = apply_filters( 'wpml_translate_single_string', $key.'='.$value, 'admin_texts_pfcustomfields_options', '[pfcustomfields_options][setupcustomfields_'.$params['fieldname'].'_rvalues]'.$ikk);
				            	 $exvalue = explode('=', $exvalue);
				            	 if (isset($exvalue[1])) {
				            	 	$output .= '<option value="'.$key.'"'.$checkvalue.'>'.$exvalue[1].'</option>';
				            	 }else{
				            	 	$output .= '<option value="'.$key.'"'.$checkvalue.'>'.$value.'</option>';
				            	 }
				            }else{
				            	 $output .= '<option value="'.$key.'"'.$checkvalue.'>'.$value.'</option>';
				            }

				            $ikk++;
				        }
			            $output .= '</select>';
			            $output .= '</label>';
		   			break;
	   			/**
	   			*Radio
	   			**/
		   			case 'radio':
		   			$description = ($params['fieldtooltip']!='') ? ' <a href="javascript:;" class="info-tip" aria-describedby="helptooltip">?<span role="tooltip">'.$params['fieldtooltip'].'</span></a>' : '' ;
		   				$output .= '<label class="lbl-text ext">'.$params['fieldtitle'].' '.$description.'</label>';
		   				$output .= '<div class="option-group">';

		   				$ikk = 0;

		   				foreach ($this->pfstring2KeyedArray($params['fieldoptions']) as $key => $value) {
		   					$output .= '<span class="goption">';
				   			$output .= '<label class="options">';
				   			if (is_array($params['fielddefault'])) {
				            	$checkvalue = (in_array($key,$params['fielddefault'])) ? ' checked' : '' ;
				            }else{
				            	$checkvalue = ($params['fielddefault']!='' && strcmp($params['fielddefault'], $key) == 0) ? ' checked' : '' ;
				            }
					        $output .= '<input type="radio" name="'.$params['fieldname'].'" value="'.$key.'"'.$checkvalue.' />';
					        $output .= '<span class="radio"></span>';
				            $output .= '</label>';
				            if (class_exists('SitePress')) {
				            	 //$exvalue = explode('=', icl_t('admin_texts_pfcustomfields_options', '[pfcustomfields_options][setupcustomfields_'.$params['fieldname'].'_rvalues]'.$ikk, $value));
				            	 $exvalue = apply_filters( 'wpml_translate_single_string', $value, 'admin_texts_pfcustomfields_options', '[pfcustomfields_options][setupcustomfields_'.$params['fieldname'].'_rvalues]'.$ikk);
				            	 $exvalue = explode('=', $exvalue);
				            	 if (isset($exvalue[1])) {
				            	 	$output .= '<label for="'.$params['fieldname'].'">'.$exvalue[1].'</label>';
				            	 }else{
				            	 	$output .= '<label for="'.$params['fieldname'].'">'.$value.'</label>';
				            	 }
				            }else{
				            	 $output .= '<label for="'.$params['fieldname'].'">'.$value.'</label>';
				            }
				            $output .= '</span>';

				            $ikk++;
						}

			            $output .= '</div>';
		   			break;
	   			/**
	   			*Checkbox
	   			**/
		   			case 'checkbox':
		   				$description = ($params['fieldtooltip']!='') ? ' <a href="javascript:;" class="info-tip" aria-describedby="helptooltip">?<span role="tooltip">'.$params['fieldtooltip'].'</span></a>' : '' ;
		   				$output .= '<label class="lbl-text ext">'.$params['fieldtitle'].' '.$description.'</label>';
		   				$output .= '<div class="option-group">';

		   				$ikk = 0;

		   				foreach ($this->pfstring2KeyedArray($params['fieldoptions']) as $key => $value) {
		   					$output .= '<span class="goption">';
				   			$output .= '<label class="options">';
				   			if (is_array($params['fielddefault'])) {
				            	$checkvalue = (in_array($key,$params['fielddefault'])) ? ' checked' : '' ;
				            }else{
				            	$checkvalue = ($params['fielddefault']!='' && strcmp($params['fielddefault'], $key) == 0) ? ' checked' : '' ;
				            }
					        $output .= '<input type="checkbox" name="'.$params['fieldname'].'[]" value="'.$key.'"'.$checkvalue.' />';
					        $output .= '<span class="checkbox"></span>';
				            $output .= '</label>';
				            if (class_exists('SitePress')) {
				            	 //$exvalue = explode('=', icl_t('admin_texts_pfcustomfields_options', '[pfcustomfields_options][setupcustomfields_'.$params['fieldname'].'_rvalues]'.$ikk, $value));
				            	 $exvalue = apply_filters( 'wpml_translate_single_string', $value, 'admin_texts_pfcustomfields_options', '[pfcustomfields_options][setupcustomfields_'.$params['fieldname'].'_rvalues]'.$ikk);
				            	 $exvalue = explode('=', $exvalue);
				            	 if (isset($exvalue[1])) {
				            	 	$output .= '<label for="'.$params['fieldname'].'">'.$exvalue[1].'</label>';
				            	 }else{
				            	 	$output .= '<label for="'.$params['fieldname'].'">'.$value.'</label>';
				            	 }
				            }else{
				            	 $output .= '<label for="'.$params['fieldname'].'">'.$value.'</label>';
				            }
				            $output .= '</span>';
				            $ikk++;
						}

			            $output .= '</div>';
		   			break;
		   		/**
	   			*Date
	   			**/
		   			case 'date':


			            $setup4_membersettings_dateformat = $this->PFSAIssetControl('setup4_membersettings_dateformat','','1');
						$setup3_modulessetup_openinghours_ex2 = $this->PFSAIssetControl('setup3_modulessetup_openinghours_ex2','','1');

						$date_field_rtl = (!is_rtl())? 'false':'true';
						$date_field_ys = 'true';

						switch ($setup4_membersettings_dateformat) {
							case '1':$date_field_format = 'dd/mm/yy';$date_field_format0 = 'd/m/Y';break;
							case '2':$date_field_format = 'mm/dd/yy';$date_field_format0 = 'm/d/Y';break;
							case '3':$date_field_format = 'yy/mm/dd';$date_field_format0 = 'Y/m/d';break;
							case '4':$date_field_format = 'yy/dd/mm';$date_field_format0 = 'Y/d/m';break;
							default:$date_field_format = 'dd/mm/yy';$date_field_format0 = 'd/m/Y';break;
						}

			   			$output .= '
			   				<label for="'.$params['fieldname'].'" class="lbl-text">'.$params['fieldtitle'].'</label>
			                <label class="lbl-ui">';

			            if (is_array($params['fielddefault'])) {
			            	if(isset($params['fielddefault'][0])){
			            		$checkvalue = ($params['fielddefault'][0]!='') ? ' value="'.date($date_field_format0,$params['fielddefault'][0]).'"' : '' ;
			            	}else{
			            		$checkvalue = '';
			            	}
			            }else{
			            	$checkvalue = ($params['fielddefault']!='') ? ' value="'.date($date_field_format0,$params['fielddefault']).'"' : '' ;
			            }

			            if($params['fieldsubtype'] == 4){
			            	$output .= '<input type="text" id="'.$params['fieldname'].'" name="'.$params['fieldname'].'"  class="input"'.$checkvalue.' />';
				        }else{
				        	$output .= '<input type="text" id="'.$params['fieldname'].'" name="'.$params['fieldname'].'" class="input"'.$checkvalue.' />';
				        }
				        if ($params['fieldtooltip']!='') {
				        	$output .= '<b class="tooltip left-bottom"><em>'.$params['fieldtooltip'].'</em></b>';
				        }
			            $output .= '</label>';

			            $yearrange1 = $this->PFCFIssetControl('setupcustomfields_'.$params['fieldname'].'_yearrange1','','2000');
						$yearrange2 = $this->PFCFIssetControl('setupcustomfields_'.$params['fieldname'].'_yearrange2','',date("Y"));

						if (!empty($yearrange1) && !empty($yearrange2)) {
							$yearrangesetting = 'yearRange:"'.$yearrange1.':'.$yearrange2.'",';
						}elseif (!empty($yearrange1) && empty($yearrange2)) {
							$yearrangesetting = 'yearRange:"'.$yearrange1.':'.date("Y").'",';
						}else{
							$yearrangesetting = '';
						}


			            $output .= "
						<script>
						(function($) {
							'use strict';
							$(function(){
								$( '#".$params['fieldname']."' ).datepicker({
							      changeMonth: $date_field_ys,
							      changeYear: $date_field_ys,
							      isRTL: $date_field_rtl,
							      dateFormat: '$date_field_format',
							      $yearrangesetting
							      firstDay: $setup3_modulessetup_openinghours_ex2,/* 0 Sunday 1 monday*/

							    });
							});
						})(jQuery);
						</script>
			            ";
		   			break;

	   		}

	   		$output .= '</div>';



        return $output;
	}


	private function PFGetListFA($params = array()){
	    $defaults = array(
	        'listname' => '',
	        'listtype' => '',
	        'listtitle' => '',
	        'listsubtype' => '',
	        'listdefault' => '',
	        'listmultiple' => 0,
	        'connectionkey' => '',
	        'connectionvalue' => '',
	        'connectionstatus' => 1,
	        'place' => ''
	    );

	    $params = array_merge($defaults, $params);

	    $i = 0;
	    	$output_options = '';
	    	if($params['listmultiple'] == 1){ $multiplevar = ' multiple';$multipletag = '[]';}else{$multiplevar = '';$multipletag = '';};

	    	$fieldvalues = get_terms($params['listsubtype'],array('hide_empty'=>false));


	    	if (count($fieldvalues) > 0) {
				foreach( $fieldvalues as $parentfieldvalue){
					if($parentfieldvalue->parent == 0){
						/* If connection enabled */
						if ($params['connectionstatus'] == 0) {
							$process = false;

							$term_meta_check = get_term_meta($parentfieldvalue->term_id,$params['connectionkey'],true);
							if (is_array($term_meta_check)) {
								if (in_array($params['connectionvalue'], $term_meta_check)) {
									$process = true;
								}
							}
						}else{
							$process = true;
						}



						if ($process) {
							$fieldParenttaxSelectedValuex = 0;
							$i++;
							if(is_array($params['listdefault'])){
								if(in_array($parentfieldvalue->term_id, $params['listdefault'])){ $fieldParenttaxSelectedValuex = 1;}
							}else{
								if(strcmp($params['listdefault'],$parentfieldvalue->term_id) == 0){ $fieldParenttaxSelectedValuex = 1;}
							}

							if($fieldParenttaxSelectedValuex == 1){
								$output_options .= '<option class="pointfinder-parent-field" value="'.$parentfieldvalue->term_id.'" selected>'.$parentfieldvalue->name.'</option>';
							}else{
								$output_options .= '<option class="pointfinder-parent-field" value="'.$parentfieldvalue->term_id.'">'.$parentfieldvalue->name.'</option>';
							}



							foreach( $fieldvalues as $fieldvalue){

								if($fieldvalue->parent == $parentfieldvalue->term_id){

									/* If connection enabled */
									if ($params['connectionstatus'] == 0) {
										$process_child = false;

										$term_meta_check = get_term_meta($fieldvalue->term_id,$params['connectionkey'],true);
										if (is_array($term_meta_check)) {
											if (in_array($params['connectionvalue'], $term_meta_check)) {
												$process_child = true;
											}
										}
									}else{
										$process_child = true;
									}


									if ($process_child) {
										$fieldtaxSelectedValue = 0;

										if($params['listdefault'] != ''){
											if(is_array($params['listdefault'])){
												if(in_array($fieldvalue->term_id, $params['listdefault'])){ $fieldtaxSelectedValue = 1;}
											}else{
												if(strcmp($params['listdefault'],$fieldvalue->term_id) == 0){ $fieldtaxSelectedValue = 1;}
											}
										}

										if($fieldtaxSelectedValue == 1){
											$output_options .= '<option value="'.$fieldvalue->term_id.'" selected>&nbsp;&nbsp;&nbsp;&nbsp;'.$fieldvalue->name.'</option>';
										}else{
											$output_options .= '<option value="'.$fieldvalue->term_id.'">&nbsp;&nbsp;&nbsp;&nbsp;'.$fieldvalue->name.'</option>';
										}
									}
								}
							}


						}

					}
				}
			}

	    	$output = '';
			$output .= '<div class="pf_fr_inner" data-pf-parent="">';


				if (!empty($params['listtitle'])) {
					$output .= '<label for="'.$params['listname'].'" class="lbl-text">'.$params['listtitle'].':</label>';
				}

				$as_mobile_dropdowns = PFSAIssetControl('as_mobile_dropdowns','','0');

			if ($as_mobile_dropdowns == 1) {
				$as_mobile_dropdowns_text = 'class="pf-special-selectbox"';
			} else {
				$as_mobile_dropdowns_text = '';
			}

				$output .= '
	        <label class="lbl-ui select">
	        <select'.$multiplevar.' name="'.$params['listname'].$multipletag.'" id="'.$params['listname'].'" '.$as_mobile_dropdowns_text.'>';

	        if ($params['place'] != 'backend') {
	        	$output .= '<option></option>';
	        }elseif ($params['listtype'] == 'conditions' && $params['place'] == 'backend') {


	        	$ctext_forcond = "";
				if (empty($params['listdefault'])) {
					$ctext_forcond = ' checked="checked"';
				}


	        	$output .= '<option value="" '.$ctext_forcond.' >'.esc_html__("Empty Condition","pointfindercoreelements").'</option>';
	        }

	        $output .= $output_options.'
	        </select>
	        </label>';


	   		$output .= '</div>';

	   	if ($i > 0 ) {
	   		return $output;
	   	}

	}
  
}