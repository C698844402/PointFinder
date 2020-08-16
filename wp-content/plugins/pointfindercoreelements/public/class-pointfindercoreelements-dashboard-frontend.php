<?php 


if (class_exists('PF_Frontend_Fields')) {	
	return;
}

/**
 * Frontend Submission Form Layout
 */
class PF_Frontend_Fields
{
		use PointFinderOptionFunctions,
		PointFinderCommonFunctions,
		PointFinderCUFunctions,
		PointFinderWPMLFunctions,
		PointFinderReviewFunctions;

		public $FieldOutput;
		public $ScriptOutput;
		public $ScriptOutputDocReady;
		public $VSORules;
		public $VSOMessages;
		public $PFHalf = 1;
		private $itemrecurringstatus = 0;

		public function __construct($params = array()){

			global $wpdb;
			global $pointfindertheme_option;

			$defaults = array(
		        'fields' => '',
		        'formtype' => '',
		        'sccval' => '',
				'errorval' => '',
				'post_id' => '',
				'sheader' => '',
				'sheadermes' => '',
				'current_user' => '',
				'dontshowpage' => 0,
				'redirect' => false
		    );

		    $params = array_merge($defaults, $params);

		    $setup4_membersettings_dashboard = $this->PFSAIssetControl('setup4_membersettings_dashboard','','');
			$setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
			$pfmenu_perout = $this->PFPermalinkCheck();

			$lang_custom = '';

			if(class_exists('SitePress')) {
				$lang_custom = $this->PF_current_language();
			}

			/**
			*Start: Page Header Actions / Divs / Etc...
			**/
				$this->FieldOutput = '<div class="golden-forms">';
				if ($params['formtype'] == 'myitems') {
					$this->FieldOutput .= '<form id="pfuaprofileform" enctype="multipart/form-data" name="pfuaprofileform" method="GET" action=""><input type="hidden" value="myitems" name="ua">';
				}elseif ($params['formtype'] == 'mymessages') {
					$this->FieldOutput .= '';
				}else{
					$this->FieldOutput .= '<form id="pfuaprofileform" enctype="multipart/form-data" name="pfuaprofileform" method="POST" action="">';
				}

				$this->FieldOutput .= '<div class="pfsearchformerrors"><ul></ul><a class="button pfsearch-err-button"><i class="fas fa-times"></i> '.esc_html__('CLOSE','pointfindercoreelements').'</a></div>';
				if($params['sccval'] != ''){
					$this->FieldOutput .= '<div class="notification success" id="pfuaprofileform-notify"><div class="row"><p>'.$params['sccval'].'<br>'.$params['sheadermes'].'</p></div></div>';
					$this->ScriptOutput .= '$(function(){$.pfmessagehide();});';
				}
				if($params['errorval'] != ''){
					$this->FieldOutput .= '<div class="notification error" id="pfuaprofileform-notify"><p>'.$params['errorval'].'</p></div>';
					$this->ScriptOutput .= '$(function(){$.pfmessagehide();});';
				}
				$this->FieldOutput .= '<div class="">';
				$this->FieldOutput .= '<div class="">';
				$this->FieldOutput .= '<div class="row">';

			/**
			*End: Page Header Actions / Divs / Etc...
			**/
				$main_submit_permission = true;
				$main_package_purchase_permission = false;
				$main_package_renew_permission = false;
				$main_package_limit_permission = false;
				$main_package_upgrade_permission = false;
				$main_package_expire_problem = false;

				$hide_button = false;

				switch ($params['formtype']) {
					case 'purchaseplan':
					case 'renewplan':
					case 'upgradeplan':
						$formaction = 'pfget_membershipsystem';
						$noncefield = wp_create_nonce($formaction);
						$free_membership = false;
						/**
						*Start: Purchase Plan Content
						**/
							/*If membership activated*/
							$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');
							$user_idx = $params['current_user'];
							$membership_user_package_id = get_user_meta( $user_idx, 'membership_user_package_id', true );
							$membership_user_package = get_user_meta( $user_idx, 'membership_user_package', true );
							$membership_user_item_limit = get_user_meta( $user_idx, 'membership_user_item_limit', true );
							$membership_user_featureditem_limit = get_user_meta( $user_idx, 'membership_user_featureditem_limit', true );
							$membership_user_image_limit = get_user_meta( $user_idx, 'membership_user_image_limit', true );
							$membership_user_trialperiod = get_user_meta( $user_idx, 'membership_user_trialperiod', true );

							$membership_user_activeorder = get_user_meta( $user_idx, 'membership_user_activeorder', true );
							$membership_user_expiredate = get_post_meta( $membership_user_activeorder, 'pointfinder_order_expiredate', true );
							$setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');


							if(empty($membership_user_package_id) && $params['formtype'] == 'purchaseplan'){
								$main_package_purchase_permission = true;
							}
							if ($params['formtype'] == 'renewplan' && !empty($membership_user_package_id)) {
								$main_package_renew_permission = true;
							}elseif ($params['formtype'] == 'renewplan' && empty($membership_user_package_id)){
								$main_package_renew_permission = false;
								$main_package_purchase_permission = true;
								$params['formtype'] = 'purchaseplan';
							}
							if ($params['formtype'] == 'upgradeplan' && !empty($membership_user_package_id)) {
								$main_package_upgrade_permission = true;
							}elseif ($params['formtype'] == 'upgradeplan' && empty($membership_user_package_id)){
								$main_package_upgrade_permission = false;
								$main_package_purchase_permission = true;
								$params['formtype'] = 'purchaseplan';
							}


							/*
							* Start: Order removed expire problem - Membership package
							*/
								if ($main_package_expire_problem) {
									$hide_button = true;
									echo '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="fas fa-info-circle" style="color:black;font-size: 16px;"></i> '.esc_html__("Please contact with your site Admin. Your membership order have problem.","pointfindercoreelements").'</div>';
								}
							/*
							* End: Order removed expire problem - Membership package
							*/


							/*
							* Start: Show Limit Full Message - Membership package
							*/
								if ($main_package_limit_permission) {
									$hide_button = true;
									echo '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="fas fa-info-circle" style="color:black;font-size: 16px;"></i> '.esc_html__("Your membership plan limits reached. Please upgrade your package or contact with your site admin.","pointfindercoreelements").'</div>';
								}
							/*
							* End: Show Limit Full Message - Membership package
							*/


							/*
							* Start: Purchase Membership package
							*/
								if ($main_package_purchase_permission == true || $main_package_upgrade_permission == true || $main_package_renew_permission == true) {

									$p_continue = true;

									switch ($params['formtype']) {
										case 'purchaseplan':
											$buttonid = 'pf-ajax-purchasepack-button';
											$buttontext = esc_html__('Complete Purchase',"pointfindercoreelements"  );
											break;

										case 'renewplan':
											$buttonid = 'pf-ajax-purchasepack-button';
											$buttontext = esc_html__('Renew Plan',"pointfindercoreelements"  );
											break;

										case 'upgradeplan':
											$buttonid = 'pf-ajax-purchasepack-button';
											$buttontext = esc_html__('Upgrade Plan',"pointfindercoreelements"  );
											break;
									}

									if($p_continue){
										/**
										*Purchase Membership Package
										**/
												$is_pack = 0;

												switch ($params['formtype']) {
													case 'purchaseplan':
														$membership_query = new WP_Query(array('post_type' => 'pfmembershippacks','posts_per_page' => -1,'order_by'=>'ID','order'=>'ASC'));
														break;

													case 'renewplan':
														$stp31_userfree = $this->PFSAIssetControl("stp31_userfree","","0");
														if ($stp31_userfree == 0) {
															$membership_query = new WP_Query(array(
															'post_type' => 'pfmembershippacks',
															'posts_per_page' => -1,
															'order_by'=>'ID',
															'order'=>'ASC',
															'p'=>$membership_user_package_id,
															'meta_query' => array(
																'relation' => 'AND',
																array(
																	'key'     => 'webbupointfinder_mp_showhide',
																	'value'   => 1,
																	'compare' => '=',
																	'type' => 'NUMERIC'
																),
																array(
																	'key'     => 'webbupointfinder_mp_price',
																	'value'   => 0,
																	'compare' => '>',
																	'type' => 'NUMERIC'
																),

															),
															));
														}else{
															$membership_query = new WP_Query(array(
															'post_type' => 'pfmembershippacks',
															'posts_per_page' => -1,
															'order_by'=>'ID',
															'order'=>'ASC',
															'p'=>$membership_user_package_id,
															'meta_query' =>
																array(
																	'key'     => 'webbupointfinder_mp_showhide',
																	'value'   => 1,
																	'compare' => '=',
																	'type' => 'NUMERIC'
																)
															));
														}


														break;

													case 'upgradeplan':

														$total_icounts = $this->pointfinder_membership_count_ui($user_idx);

														/*Count User's Items*/
														$user_post_count = 0;
														$user_post_count = $total_icounts['item_count'];

														/*Count User's Featured Items*/
														$users_post_featured = 0;
														$users_post_featured = $total_icounts['fitem_count'];


														if ($user_post_count == 0 && $users_post_featured == 0) {
															$membership_query = new WP_Query(array(
																'post_type' => 'pfmembershippacks',
																'posts_per_page' => -1,
																'order_by'=>'ID',
																'order'=>'ASC',
																'post__not_in' => array($membership_user_package_id),
																'meta_query' => array(

																	'relation' => 'AND',
																	array(
																		'relation' => 'OR',
																		array(
																			'key'     => 'webbupointfinder_mp_itemnumber',
																			'value'   => $user_post_count,
																			'compare' => '>=',
																			'type' => 'NUMERIC'
																		),
																		array(
																			'key'     => 'webbupointfinder_mp_itemnumber',
																			'value'   => 0,
																			'compare' => '<',
																			'type' => 'NUMERIC'
																		)
																	),
																	array(
																		'key'     => 'webbupointfinder_mp_fitemnumber',
																		'value'   => $users_post_featured,
																		'compare' => '>=',
																		'type' => 'NUMERIC'
																	),
																	array(
																		'key'     => 'webbupointfinder_mp_showhide',
																		'value'   => 1,
																		'compare' => '=',
																		'type' => 'NUMERIC'
																	),
																	array(
																		'key'     => 'webbupointfinder_mp_price',
																		'value'   => 0,
																		'compare' => '>',
																		'type' => 'NUMERIC'
																	),

																),
															));
														}else{
															$membership_query = new WP_Query(array(
																'post_type' => 'pfmembershippacks',
																'posts_per_page' => -1,
																'order_by'=>'ID',
																'order'=>'ASC',
																'post__not_in' => array($membership_user_package_id),
																'meta_query' => array(

																	'relation' => 'AND',
																	array(
																		'relation' => 'OR',
																		array(
																			'key'     => 'webbupointfinder_mp_itemnumber',
																			'value'   => $user_post_count,
																			'compare' => '>=',
																			'type' => 'NUMERIC'
																		),
																		array(
																			'key'     => 'webbupointfinder_mp_itemnumber',
																			'value'   => 0,
																			'compare' => '<',
																			'type' => 'NUMERIC'
																		)
																	),
																	array(
																		'key'     => 'webbupointfinder_mp_fitemnumber',
																		'value'   => $users_post_featured,
																		'compare' => '>=',
																		'type' => 'NUMERIC'
																	),
																	array(
																		'key'     => 'webbupointfinder_mp_images',
																		'value'   => $membership_user_image_limit,
																		'compare' => '>=',
																		'type' => 'NUMERIC'
																	),
																	array(
																		'key'     => 'webbupointfinder_mp_showhide',
																		'value'   => 1,
																		'compare' => '=',
																		'type' => 'NUMERIC'
																	),

																),
															));
														}


														break;
												}

												/*print_r($membership_query->request);*/
												$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-title-membershippack"><i class="fas fa-box"></i> '.esc_html__('PLEASE SELECT A PLAN','pointfindercoreelements').'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-membership">';
												if ($params['formtype'] == "renewplan") {
													if (!$membership_query->have_posts()) {
														$this->FieldOutput .= '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="fas fa-info-circle" style="color:black;font-size: 16px;"></i> '.esc_html__("Free plan can't renew. Please try to upgrade.","pointfindercoreelements").'</div>';
														$free_membership = true;
														$this->ScriptOutput = 'window.location = "'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=upgradeplan'.'"';
													}else{
														$this->ScriptOutput = "$.pfmembershipgetp(".$membership_user_package_id.",'".$params['formtype']."');";
														$this->FieldOutput .= '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="fas fa-info-circle" style="color:black;font-size: 16px;"></i> '.esc_html__("You can only select your current plan. If want to change with another plan, please try to upgrade.","pointfindercoreelements").'</div>';
													}
												}
												if ($params['formtype'] == "upgradeplan" && $membership_query->have_posts()) {
													if ($user_post_count == 0 && $users_post_featured == 0) {
														/*$this->FieldOutput .= '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="fas fa-info-circle" style="color:black;font-size: 16px;"></i> '.sprintf(esc_html__("Your current limits require %d item and %d featured item limit. Only below packages available for upgrade. You can remove some items if want to use lower limited packages.","pointfindercoreelements"),$user_post_count,$users_post_featured).'</div>';*/
													}else{
														$this->FieldOutput .= '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="fas fa-info-circle" style="color:black;font-size: 16px;"></i> '.sprintf(esc_html__("Your current limits require %d item and %d featured item and %d image limit. Only below packages available for upgrade. You can remove some items if want to use lower limited packages.","pointfindercoreelements"),$user_post_count,$users_post_featured,$membership_user_image_limit).'</div>';
													}
												}
												if ($params['formtype'] == "upgradeplan" && !$membership_query->have_posts()) {
													$this->FieldOutput .= '<div class="pf-dash-errorview-plan"><i class="fas fa-info-circle" style="color:black;font-size: 16px;"></i> '.sprintf(esc_html__("We can't find an available plan for you. Your current limits require %d item and %d featured item and %d image limit. Please try to remove some items or contact with administrator of your site.","pointfindercoreelements"),$user_post_count,$users_post_featured,$membership_user_image_limit).'</div>';
												}
												if ( $membership_query->have_posts() ) {
												  $this->FieldOutput .= '<ul class="pf-membership-package-list">';

												  while ( $membership_query->have_posts() ) {
												    $membership_query->the_post();

												    $post_id = get_the_id();

												    $packageinfo = $this->pointfinder_membership_package_details_get($post_id);

												    if ($packageinfo['webbupointfinder_mp_showhide'] == 1) {
													    $this->FieldOutput .= '<li>
													    <div class="pf-membership-package-box">
													    	<div class="pf-membership-package-title">' . get_the_title() . '</div>
													    	<div class="pf-membership-package-info">
																<ul>
																	<li><span class="pf-membership-package-info-title">'.esc_html__('Price:','pointfindercoreelements').' </span> '.$packageinfo['packageinfo_priceoutput_text'].'</li>
																	<li><span class="pf-membership-package-info-title">'.esc_html__('Number of listings included in the package:','pointfindercoreelements').' </span> '.$packageinfo['packageinfo_itemnumber_output_text'].'</li>
																	<li><span class="pf-membership-package-info-title">'.esc_html__('Number of featured listings included in the package:','pointfindercoreelements').' </span> '.$packageinfo['webbupointfinder_mp_fitemnumber'].'</li>
																	<li><span class="pf-membership-package-info-title">'.esc_html__('Number of images (per listing) included in the package:','pointfindercoreelements').' </span> '.$packageinfo['webbupointfinder_mp_images'].'</li>
																	<li><span class="pf-membership-package-info-title">'.esc_html__('Listings can be submitted within:','pointfindercoreelements').' </span> '.$packageinfo['webbupointfinder_mp_billing_period'].' '.$packageinfo['webbupointfinder_mp_billing_time_unit_text'].'</li>
																	';
																	if ($packageinfo['webbupointfinder_mp_trial'] == 1 && $packageinfo['packageinfo_priceoutput'] != 0) {
																		$this->FieldOutput .= '<li><span class="pf-membership-package-info-title">'.esc_html__('Trial Period:','pointfindercoreelements').' </span> '.$packageinfo['webbupointfinder_mp_trial_period'].' '.$packageinfo['webbupointfinder_mp_billing_time_unit_text'].' <br/><small>'.esc_html__('Note: Your listing will expire end of trial period.','pointfindercoreelements').'</small></li>';
																	}
																	if (!empty($packageinfo['webbupointfinder_mp_description'])) {
																		$this->FieldOutput .= '<li><span class="pf-membership-package-info-title">'.esc_html__('Description:','pointfindercoreelements').' </span> '.$packageinfo['webbupointfinder_mp_description'].'</li>';
																	}

																	$this->FieldOutput .= '
																</ul>
													    	</div>
													    	<div class="pf-membership-splan-button">
							                                    <a data-id="'.$post_id.'" data-ptype="'.$params['formtype'].'">'.esc_html__('Select','pointfindercoreelements').'</a>
							                                </div>
													    </div>
													    </li>';
													    $is_pack++;
													}
												  }
												  if ($is_pack == 0) {
												  	$this->FieldOutput .= esc_html__("Please set visible one of your plans.",'pointfindercoreelements');
												  }
												  $this->FieldOutput .= '</ul>';
												} else {
													if ($params['formtype'] == 'purchaseplan') {
														$this->FieldOutput .= esc_html__("Please create some membership plans.",'pointfindercoreelements' );
													}

												}

											$this->FieldOutput .= '</section>';
										/**
										*Purchase Membership Package
										**/

										/**
										*PAY Membership Package
										**/
											$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-title-membershippack-payment"><i class="far fa-credit-card"></i> '.esc_html__('PLEASE SELECT PAYMENT TYPE','pointfindercoreelements').'</div>';
											$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-membership-payment">';

													$this->FieldOutput .= '<div class="pfm-payment-plans"><div class="pfm-payment-plans-inner">'.esc_html__('Please select a plan for payment options.','pointfindercoreelements' ).'</div></div>';

											$this->PFValidationCheckWrite(1,esc_html__('Please select a payment type.','pointfindercoreelements' ),'pf_membership_payment_selection');
											$this->PFValidationCheckWrite(1,esc_html__('Please select a plan type','pointfindercoreelements' ),'selectedpackageid');




											$this->FieldOutput .= '</section>';
										/**
										*PAY Membership Package
										**/

										/**
										*Terms and conditions
										**/
											/*$content_spadx = '';
											$this->FieldOutput .= apply_filters( 'pointfinderspadx_newitemonsubpanel',$content_spadx, $author_post_id );*/
											$setup4_mem_terms = $this->PFSAIssetControl('setup4_mem_terms','','1');
											if ($setup4_mem_terms == 1) {

												$this->PFValidationCheckWrite(1,esc_html__('You must accept terms and conditions.','pointfindercoreelements' ),'pftermsofuser');

												
												$terms_conditions_template = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s ",'_wp_page_template','terms-conditions.php'), ARRAY_A);
												$terms_permalink = '#';
												if(count($terms_conditions_template) > 1){
														foreach ($terms_conditions_template as $terms_conditions_template_single) {
															$terms_permalink = get_permalink($terms_conditions_template_single['post_id']);
														}
												}else{
													if (isset($terms_conditions_template[0]['post_id'])) {
															$terms_permalink = get_permalink($terms_conditions_template[0]['post_id']);
													}
												}

												$this->FieldOutput .= '<section style="margin-top: 20px;margin-bottom: 10px;">';
												$this->FieldOutput .= '
												<div style="position:relative;">
													<span class="goption upt">
					                                    <label class="options">
					                                        <input type="checkbox" id="pftermsofuser" name="pftermsofuser" value="1">
					                                        <span class="checkbox"></span>
					                                    </label>
					                                    <label for="check1" class="upt1ch1">'.wp_sprintf(esc_html__( 'I have read the %s terms and conditions %s and accept them.', 'pointfindercoreelements' ),'<a href="'.$terms_permalink.'" class="pftermshortc"><strong>','</strong></a>').'</label>
					                               </span>
					                             </div>
												';

								                $this->FieldOutput .= '</section>';


								                 $this->ScriptOutput .= '
									                $(".pftermshortc").magnificPopup({
														type: "ajax",
														overflowY: "scroll"
													});
												';
								            }
										/**
										*Terms and conditions
										**/


									}
								}elseif (empty($membership_user_package_id) == false && $main_package_purchase_permission == false && $params['formtype'] == 'purchaseplan') {
									$hide_button = true;
									echo '<div class="pf-dash-errorview-plan"><i class="fas fa-exclamation-triangle" style="color:black;font-size: 16px;"></i> '.esc_html__("You can't purchase new plan. Because already have one.","pointfindercoreelements").'</div>';
									$p_continue = false;
								}
							/*
							* End: Purchase - Membership package
							*/



						/**
						*End: Purchase Plan Content
						**/
						break;

					case 'upload':
					case 'edititem':
						/**
						*Start: New Item Page Content
						**/
							
							if($params['formtype'] == 'upload'){
								$formaction = 'pfget_uploaditem';
								$buttonid = 'pf-ajax-uploaditem-button';
								$buttontext = esc_html__( "Submit Listing", "pointfindercoreelements");

							}else{
								$formaction = 'pfget_edititem';
								$buttonid = 'pf-ajax-uploaditem-button';
								$buttontext = $this->PFSAIssetControl('setup29_dashboard_contents_submit_page_titlee','','');

							}

							$noncefield = wp_create_nonce($formaction);


							if ($params['dontshowpage'] != 1) {


							/* Get Admin Settings for Default Fields */
							$setup4_submitpage_titletip = $this->PFSAIssetControl('setup4_submitpage_titletip','','');
							$maplanguage= $this->PFSAIssetControl('setup5_mapsettings_maplanguage','','en');

							/*If membership activated*/
							$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');
							if ($setup4_membersettings_paymentsystem == 2) {
								$user_idx = $params['current_user'];
								$membership_user_package_id = get_user_meta( $user_idx, 'membership_user_package_id', true );

								if (!empty($membership_user_package_id)) {
									$packageinfo = $this->pointfinder_membership_package_details_get($membership_user_package_id);
								}
								$membership_user_package = get_user_meta( $user_idx, 'membership_user_package', true );
								$membership_user_item_limit = get_user_meta( $user_idx, 'membership_user_item_limit', true );
								$membership_user_featureditem_limit = get_user_meta( $user_idx, 'membership_user_featureditem_limit', true );
								$membership_user_image_limit = get_user_meta( $user_idx, 'membership_user_image_limit', true );
								$membership_user_trialperiod = get_user_meta( $user_idx, 'membership_user_trialperiod', true );

								$membership_user_activeorder = get_user_meta( $user_idx, 'membership_user_activeorder', true );
								$membership_user_expiredate = get_post_meta( $membership_user_activeorder, 'pointfinder_order_expiredate', true );
							}

							$current_post_status = get_post_status($params['post_id']);
							if ($params['post_id'] != '') {

								$order_id_current = $this->PFU_GetOrderID($params['post_id'],1);

								$is_this_itemrecurring = get_post_meta( $order_id_current, 'pointfinder_order_recurring', true );
								if ($is_this_itemrecurring == false) {
									$is_this_itemrecurring = get_post_meta( $order_id_current, 'pointfinder_order_frecurring', true );
								}

								if (($current_post_status == 'publish' || $current_post_status == 'pendingapproval') && !empty($is_this_itemrecurring) ) {
									$this->itemrecurringstatus = 1;
								}

								/* Clean sub order values if exist. */
								$change_value_status = get_post_meta( $order_id_current, "pointfinder_sub_order_change", true);

								if ($change_value_status != false) {
									$this->pointfinder_remove_sub_order_metadata($order_id_current);
								}

							}

							/*** DEFAULTS FOR FIRST COLUMN ***/
								$setup3_pointposttype_pt4_check = $this->PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
								$setup4_submitpage_itemtypes_check = $this->PFSAIssetControl('setup4_submitpage_itemtypes_check','','1');
								$setup3_pointposttype_pt5_check = $this->PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
								$setup4_submitpage_locationtypes_check = $this->PFSAIssetControl('setup4_submitpage_locationtypes_check','','1');
								$setup3_pointposttype_pt6_check = $this->PFSAIssetControl('setup3_pointposttype_pt6_check','','1');
								$setup4_submitpage_featurestypes_check = $this->PFSAIssetControl('setup4_submitpage_featurestypes_check','','1');
								$st4_sp_med = $this->PFSAIssetControl('st4_sp_med','','1');
								$setup4_submitpage_locationtypes_validation = $this->PFSAIssetControl('setup4_submitpage_locationtypes_validation','','1');
								$setup4_submitpage_locationtypes_verror = $this->PFSAIssetControl('setup4_submitpage_locationtypes_verror','','Please select a location.');


								$stp4_fupl = $this->PFSAIssetControl("stp4_fupl","","0");

								$setup20_paypalsettings_paypal_price_short = $this->PFSAIssetControl('setup20_paypalsettings_paypal_price_short','','$');
								$setup20_paypalsettings_paypal_price_pref = $this->PFSAIssetControl('setup20_paypalsettings_paypal_price_pref','',1);

								$setup4_coviup = $this->PFSAIssetControl('setup4_coviup','','0');
								$setup4_coviup_req = $this->PFSAIssetControl('setup4_coviup_req','','0');
								$st4_sp_medst = $this->PFSAIssetControl('st4_sp_medst','','0');


							/*** DEFAULTS FOR SECOND COLUMN ***/
								$setup4_submitpage_video = $this->PFSAIssetControl('setup4_submitpage_video','','1');
								$setup4_submitpage_imageupload = $this->PFSAIssetControl('setup4_submitpage_imageupload','','1');
								$setup4_submitpage_imagelimit = $this->PFSAIssetControl('setup4_submitpage_imagelimit','','10');
								$setup4_submitpage_messagetorev = $this->PFSAIssetControl('setup4_submitpage_messagetorev','','1');


								$setup4_submitpage_featuredverror_status = $this->PFSAIssetControl('setup4_submitpage_featuredverror_status','',1);
								$stp4_err_st = $this->PFSAIssetControl("stp4_err_st","","0");


								$setup4_submitpage_conditions_check = $this->PFSAIssetControl('setup4_submitpage_conditions_check','',0);
								$setup3_pt14_check = $this->PFSAIssetControl('setup3_pt14_check','',0);
								$st4_sp_med2 = $this->PFSAIssetControl('st4_sp_med2','',1);

								$package_featuredcheck = '';
								if ($params['post_id'] != '') {
									$package_featuredcheck = get_post_meta( $params['post_id'], 'webbupointfinder_item_featuredmarker', true );
								}

								$default_package = 1;

								if ($params['post_id'] != '' && $setup4_membersettings_paymentsystem == 1) {
									$default_package_meta = get_post_meta( $this->PFU_GetOrderID($params['post_id'],1), 'pointfinder_order_listingpid',true);

									if (!empty($default_package_meta)) {
										if ($default_package_meta == 1 || $default_package_meta == 2) {
											$default_package = 1;
										}else{
											$default_package = $default_package_meta;
										}
									}
								}



							if ($setup4_membersettings_paymentsystem == 2) {
								if(empty($membership_user_package_id)){
									$main_submit_permission = false;
									$main_package_purchase_permission = true;
								}else{

									if (!empty($membership_user_expiredate)) {
										if ($this->pf_membership_expire_check($membership_user_expiredate)) {
											$main_submit_permission = false;
											$main_package_renew_permission = true;
										}else{
											if ($membership_user_item_limit == 0 && $params['formtype'] == 'upload') {
												$main_submit_permission = false;
												$main_package_limit_permission = true;
											}elseif ($membership_user_item_limit == -1 && $params['formtype'] == 'upload') {
												$main_submit_permission = true;
											}

										}
									} else {
										$main_submit_permission = false;
										$main_package_expire_problem = true;
									}


									$setup4_submitpage_imagelimit = $membership_user_image_limit;
								}

							}

							if ($setup4_membersettings_paymentsystem == 2) {

								/*
								* Start: Order removed expire problem - Membership package
								*/
									if ($main_package_expire_problem) {
										$hide_button = true;
										echo '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="fas fa-info-circle" style="color:black;font-size: 16px;"></i> '.esc_html__("Please contact with your site Admin. Your membership order have problem.","pointfindercoreelements").'</div>';
									}
								/*
								* End: Order removed expire problem - Membership package
								*/


								/*
								* Start: Show Limit Full Message - Membership package
								*/
									if ($main_package_limit_permission) {
										$hide_button = true;
										echo '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="fas fa-info-circle" style="color:black;font-size: 16px;"></i> '.esc_html__("Your membership plan limits reached. Please upgrade your package or contact with your site admin.","pointfindercoreelements").'</div>';
									}
								/*
								* End: Show Limit Full Message - Membership package
								*/


								/*
								* Start: Renew Membership package
								*/
									if ($main_package_renew_permission) {
										echo '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="fas fa-info-circle" style="color:black;font-size: 16px;"></i> '.esc_html__("Your membership plan expired. You are redirecting...","pointfindercoreelements").'</div>';
										echo '<script>window.location = "'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=renewplan'.'";</script>';}
								/*
								* End: Renew Membership package
								*/


								/*
								* Start: Upgrade Membership package
								*/
									if ($main_package_upgrade_permission) {
										echo '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="fas fa-info-circle" style="color:black;font-size: 16px;"></i> '.esc_html__("You are redirecting to Upgrade area...","pointfindercoreelements").'</div>';
										echo '<script>window.location = "'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=upgradeplan'.'";</script>';}
								/*
								* End: Upgrade Membership package
								*/

								/*
								* Start: Purchase Membership package
								*/
									if ($main_package_purchase_permission) {
										echo '<div class="pf-dash-errorview-plan pf-dash-infoview-plan"><i class="fas fa-info-circle" style="color:black;font-size: 16px;"></i> '.esc_html__("You should purchase a new membership plan. You are redirecting...","pointfindercoreelements").'</div>';
										echo '<script>window.location = "'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=purchaseplan'.'";</script>';}
								/*
								* End: Purchase Membership package
								*/
							}


							if ($main_submit_permission) {
								/**
								*Start : First Column (Custom Fields)
								**/
										if ($this->itemrecurringstatus == 1) {
											if (class_exists('Pointfinderstripesubscriptions')) {
												$subscription_check = get_post_meta($params['post_id'],'stripsubscriptionid',true);
												$this->FieldOutput .= '<div class="notification warning" style="border:1px solid rgba(255, 206, 94, 0.99)!important" id="pfuaprofileform-notify"><div class="row"><p><i class="fas fa-exclamation-triangle"></i> '.esc_html__("You can not change Listing Type, Featured Option and Listing Plan while this item using recurring payment. Please cancel recurring payment option for change these values.",'pointfindercoreelements').'<br></p></div></div>';
												if (!empty($subscription_check)) {
													$this->FieldOutput .= '<div class="notification error pfsprecurringcancele" style="border:1px solid rgba(255, 131, 94, 0.99)!important; margin-bottom:20px;" id="pfuaprofileform-notify2"><div class="row"><p><i class="fas fa-times-circle"></i> <a href="#" class="pfsprecurringcancel" data-id="'.$params['post_id'].'" data-oid="'.$order_id_current.'" style="color:#900">'.esc_html__("Click here to cancel stripe payment subscription of this listing.",'pointfindercoreelements').'</a><br><small>'.esc_html__("If recurring option canceled, the listing will expire after current period ends.",'pointfindercoreelements').'</small></p></div></div>';
												}
												
											}else{
												$this->FieldOutput .= '<div class="notification warning" style="border:1px solid rgba(255, 206, 94, 0.99)!important" id="pfuaprofileform-notify"><div class="row"><p><i class="fas fa-exclamation-triangle"></i> '.esc_html__("You can not change Listing Type, Featured Option and Listing Plan while this item using recurring payment. Please cancel recurring payment option for change these values.",'pointfindercoreelements').'<br></p></div></div>';
											}
										}

										

										/**
										*Listing Types
										**/
											$setup4_submitpage_listingtypes_title = $this->PFSAIssetControl('setup4_submitpage_listingtypes_title','','Listing Type');
											$setup4_submitpage_sublistingtypes_title = $this->PFSAIssetControl('setup4_submitpage_sublistingtypes_title','','Sub Listing Type');
											$setup4_submitpage_subsublistingtypes_title = $this->PFSAIssetControl('setup4_submitpage_subsublistingtypes_title','','Sub Sub Listing Type');
											$setup4_submitpage_listingtypes_verror = $this->PFSAIssetControl('setup4_submitpage_listingtypes_verror','','Please select a listing type.');
											$stp4_forceu = $this->PFSAIssetControl('stp4_forceu','',0);

											$setup4_ppp_catprice = $this->PFSAIssetControl('setup4_ppp_catprice','','0');

											$itemfieldname = 'pfupload_listingtypes';
											$this_cat_price_output = $status_selector = $status_pc = '';

											$this->PFValidationCheckWrite(1,$setup4_submitpage_listingtypes_verror,$itemfieldname);

											$item_defaultvalue = ($params['post_id'] != '') ? wp_get_post_terms($params['post_id'], 'pointfinderltypes', array("fields" => "ids")) : '' ;
											$item_defaultvalue_output = $sub_level = $sub_sub_level = $item_defaultvalue_output_orj = '';



											/* Get Prices For All Cats & Category options for this listing */

											$cat_extra_opts = get_option('pointfinderltypes_covars');
											$item_level_value = 0;
											
											if (is_array($item_defaultvalue)) {
												if (isset($item_defaultvalue[0])) {
													$item_defaultvalue_output_orj = $item_defaultvalue[0];
													$find_top_parent = $this->pf_get_term_top_most_parent($item_defaultvalue[0],'pointfinderltypes');

													$ci=1;
													foreach ($item_defaultvalue as $value) {
														$sub_level .= $value;
														if ($ci < count($item_defaultvalue)) {
															$sub_level .= ',';
														}
														$ci++;
													}
													switch ($find_top_parent['level']) {
														case '1':
															$sub_level = $item_defaultvalue[0];
															break;

														case '2':
															$sub_sub_level = $item_defaultvalue[0];
															$sub_level = $this->pf_get_term_top_parent($item_defaultvalue[0],'pointfinderltypes');
															break;
													}

													$item_defaultvalue_output = $find_top_parent['parent'];
													$item_level_value = (isset($find_top_parent['level']))?$find_top_parent['level']:0;
													
												}
											}/*else{
												if (isset($item_defaultvalue[0])) {
													$item_defaultvalue_output_orj = $item_defaultvalue[0];
													$find_top_parent = $this->pf_get_term_top_most_parent($item_defaultvalue[0],'pointfinderltypes');

													switch ($find_top_parent['level']) {
														case '1':
															$sub_level = $item_defaultvalue[0];
															break;

														case '2':
															$sub_sub_level = $item_defaultvalue[0];
															$sub_level = $this->pf_get_term_top_parent($item_defaultvalue[0],'pointfinderltypes');
															break;
													}


													$item_defaultvalue_output = $find_top_parent['parent'];
													$item_level_value = (isset($find_top_parent['level']))?$find_top_parent['level']:0;
												}
											}*/

											

											$this->FieldOutput .= '<div class="pfsubmit-title">'.$setup4_submitpage_listingtypes_title.'</div>';
											$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-listingtype pferrorcontainer">';
											$this->FieldOutput .= '<section class="pfsubmit-inner-sub" style="margin-left: -10px!important;">';

												$listingtype_values = get_terms('pointfinderltypes',array('hide_empty'=>false,'parent'=> 0));

												$this->FieldOutput .= '<input type="hidden" name="pfupload_listingtypes" id="pfupload_listingtypes" value="'.$item_defaultvalue_output.'"/>';
												$this->FieldOutput .= '<input type="hidden" name="pfupload_listingpid" id="pfupload_listingpid" value="'.$params['post_id'].'"/>';
												$this->FieldOutput .= '<input type="hidden" name="pfupload_type" id="pfupload_type" value="'.$setup4_membersettings_paymentsystem .'"/>';

												if ($params['formtype'] == 'edititem' && $current_post_status != 'pendingpayment' && $setup4_ppp_catprice == 1 && $setup4_membersettings_paymentsystem == 1) {
													$control_cat_price = (isset($cat_extra_opts[$item_defaultvalue_output]['pf_categoryprice']))?$cat_extra_opts[$item_defaultvalue_output]['pf_categoryprice']:0;
													if ($control_cat_price != 0) {
														$status_selector = ' disabled="disabled"';
														$status_pc = 1;
													}
												}

												if ($this->itemrecurringstatus == 1) {
													$status_selector = ' disabled="disabled"';
												}

												if ($params['post_id'] != '') {
													$this->FieldOutput .= '<input type="hidden" name="pfupload_o" id="pfupload_o" value="'.$this->PFU_GetOrderID($params['post_id'],1).'"/>';
												}

												if ($current_post_status != 'pendingpayment' && $params['formtype'] == 'edititem') {
													$this->FieldOutput .= '<input type="hidden" name="pfupload_c" id="pfupload_c" value="'.$status_pc.'"/>';
													$this->FieldOutput .= '<input type="hidden" name="pfupload_f" id="pfupload_f" value="'.$package_featuredcheck.'"/>';
													$this->FieldOutput .= '<input type="hidden" name="pfupload_p" id="pfupload_p" value="'.$default_package.'"/>';
												}else{
													$this->FieldOutput .= '<input type="hidden" name="pfupload_c" id="pfupload_c" />';
													$this->FieldOutput .= '<input type="hidden" name="pfupload_f" id="pfupload_f" />';
													$this->FieldOutput .= '<input type="hidden" name="pfupload_p" id="pfupload_p" />';
													if ($params['formtype'] == 'edititem') {
														$this->ScriptOutput .= "$(function(){
														$.pf_get_priceoutput();
													});";
													}

												}
												if ($params['formtype'] == 'edititem' && $current_post_status != 'pendingpayment'){
													$this->FieldOutput .= '<input type="hidden" name="pfupload_px" id="pfupload_px" value="1"/>';
												}



												$this->FieldOutput .= '<div class="pflistingtype-selector-main-top clearfix">';

												$subcatsarray = "var pfsubcatselect = [";
												$multiplesarray = "var pfmultipleselect = [";

												$ltimgpath = '';$ltstatusch = true;
												foreach ($listingtype_values as $listingtype_value) {
													$ltimgpath = apply_filters('pointfinder_ltimagepath',$ltimgpath,$listingtype_value->term_id);
													/* Multiple select & Subcat Select */
													$multiple_select = (isset($cat_extra_opts[$listingtype_value->term_id]['pf_multipleselect']))?$cat_extra_opts[$listingtype_value->term_id]['pf_multipleselect']:2;
													$subcat_select = (isset($cat_extra_opts[$listingtype_value->term_id]['pf_subcatselect']))?$cat_extra_opts[$listingtype_value->term_id]['pf_subcatselect']:2;

													if ($multiple_select == 1) {$multiplesarray .= $listingtype_value->term_id.',';}
													if ($subcat_select == 1) {$subcatsarray .= $listingtype_value->term_id.',';}

													if ($setup4_ppp_catprice == 1 && $setup4_membersettings_paymentsystem == 1) {
														$this_cat_price = (isset($cat_extra_opts[$listingtype_value->term_id]['pf_categoryprice']))?$cat_extra_opts[$listingtype_value->term_id]['pf_categoryprice']:0;
														if ($this_cat_price == 0) {
															$this_cat_price_output = '';
														}else{
															if ($setup20_paypalsettings_paypal_price_pref == 1) {
																$this_cat_price_output = ' <span style="font-weight:600;" title="'.esc_html__("This category price is ",'pointfindercoreelements' ).'('.$setup20_paypalsettings_paypal_price_short.$this_cat_price.')'.'">('.$setup20_paypalsettings_paypal_price_short.$this_cat_price.')</span>';
															}else{
																$this_cat_price_output = ' <span style="font-weight:600;" title="'.esc_html__("This category price is ",'pointfindercoreelements' ).'('.$this_cat_price.$setup20_paypalsettings_paypal_price_short.')'.'">('.$this_cat_price.$setup20_paypalsettings_paypal_price_short.')</span>';
															}
														}
													}


													$ltstatusch = apply_filters('pointfinder_ltstatusch',$ltstatusch,$listingtype_value->term_id);

													$this->FieldOutput .= '<div class="pflistingtype-selector-main">';
													if ($ltstatusch) {
														$this->FieldOutput .= '<input type="radio" name="radio" id="pfltypeselector'.$listingtype_value->term_id.'" data-img="'.$ltimgpath.'" class="pflistingtypeselector"'.$status_selector.' value="'.$listingtype_value->term_id.'" '.checked( $item_defaultvalue_output, $listingtype_value->term_id, 0 ).'/>';
													
														$this->FieldOutput .= '<label for="pfltypeselector'.$listingtype_value->term_id.'" style="font-weight:600;">'.$listingtype_value->name.$this_cat_price_output.'</label>';
													}
													$this->FieldOutput .= '</div>';

												}

												$this->FieldOutput .= '</div>';
												$subcatsarray .= "];";
												$multiplesarray .= "];";

												$this->ScriptOutput .= $subcatsarray.$multiplesarray;

											$this->FieldOutput .= '<div style="margin-left:10px" class="pf-sub-listingtypes-container"></div>';


											$this->FieldOutput .= '</section>';
											$this->FieldOutput .= '</section>';

											/* Start: Function for sub listing types */
												$this->ScriptOutput .= "
													$.pf_get_sublistingtypes = function(itemid,defaultv){

														if ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) != -1) {
															var multiple_ex = 1;
														}else{
															var multiple_ex = 0;
														}
														$.ajax({
													    	beforeSend:function(){
													    		$('.pfsubmit-inner-listingtype').pfLoadingOverlay({action:'show',message: '".esc_html__("Loading fields...",'pointfindercoreelements')."'});
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

															if ($stp4_forceu == 1) {
																$this->ScriptOutput .= "$('#pfupload_sublistingtypes').rules('add',{required: true,messages:{required:'".$setup4_submitpage_listingtypes_verror."'}});";
															}

															if (class_exists('Pointfinderstripesubscriptions') && $this->itemrecurringstatus == 1) {
																$this->ScriptOutput .= "$('#pfupload_sublistingtypes').prop('disabled', true);";
															}

															$this->ScriptOutput .= "

																if ($.pf_tablet_check()) {
																	$('#pfupload_sublistingtypes').select2({
																		placeholder: '".esc_html__("Please select",'pointfindercoreelements')."',
																		formatNoMatches:'".esc_html__("No match found",'pointfindercoreelements')."',
																		allowClear: true,
																		minimumResultsForSearch: 10
																	});
																}

																if ($.pf_tablet_check() == false) {
																	$('.pf-special-selectbox').each(function(index, el) {
																		var pfplc = $(this).data('pf-plc');
																		if(typeof pfplc == 'undefined'){pfplc = theme_scriptspf.pfselectboxtex;}
																		if((
																			!$('option:selected',this).attr('value') ||
																			$('option:selected',this).attr('value') == '' 
																			|| typeof $('option:selected',this).attr('value') == 'undefined' 
																			|| $('option:selected',this).attr('value') == null
																			) 
																			&& (!$(this).attr('multiple') || typeof $(this).attr('multiple') == 'undefined' || $(this).attr('multiple') == false || $(this).attr('multiple') == 'false')
																		){
																			
																			$(this).children('option:first').replaceWith(\"<option value='' selected='selected'>\"+pfplc+\"</option>\");
																		}else{
																			$(this).children('option:first').replaceWith('<option value=\"\">'+pfplc+'</option>');
																		}
																	});
																};";

																if (empty($sub_sub_level)) {
																$this->ScriptOutput .= " if ($('#pfupload_sublistingtypes').val() != 0 && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																	$.pf_get_subsublistingtypes($('#pfupload_sublistingtypes').val(),'');
																}";
																}


																$this->ScriptOutput .= "
																$('#pfupload_sublistingtypes').change(function(){
																	if($(this).val() != 0 && $(this).val() != null){
																		if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																			$('#pfupload_listingtypes').val($(this).val()).trigger('change');
																		}else{
																			$('#pfupload_listingtypes').val($(this).val());
																		}
																		if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																			$.pf_get_subsublistingtypes($(this).val(),'');
																		}
																	}else{
																		if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																			$('#pfupload_listingtypes').val($('input.pflistingtypeselector:checked').val());
																		}else{
																			$('#pfupload_listingtypes').val($('input.pflistingtypeselector:checked').val()).trigger('change');
																		}

																	}
																	$('.pfsubsublistingtypes').remove();

																});
																if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																	$('body').on('select2-removed', '#pfupload_sublistingtypes', function(e) {
																		$('#pfupload_listingtypes').val($('input.pflistingtypeselector:checked').val()).trigger('change');
																	});
																}
															}

														}).complete(function(obj,obj2){
															if (obj.responseText != '') {

																if (defaultv != '') {
																	if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																		";
																		if ($item_level_value == 2 && $params['post_id'] != '') {
																			$this->ScriptOutput .= "$('#pfupload_listingtypes').val(defaultv);
																			";
																		}else{
																			$this->ScriptOutput .= "$('#pfupload_listingtypes').val(defaultv).trigger('change');";
																		}
																		$this->ScriptOutput .= "
																	}else{
																		$('#pfupload_listingtypes').val(defaultv);
																	}
																}else{

																	if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																		";
																		if ($item_level_value == 1 && $params['post_id'] != '') {
																			$this->ScriptOutput .= "$('#pfupload_listingtypes').val(itemid).trigger('change');";
																		}elseif (empty($params['post_id'])) {
																			$this->ScriptOutput .= "$('#pfupload_listingtypes').val(itemid);";
																		}elseif (!empty($params['post_id']) && $item_level_value == 2) {
																			$this->ScriptOutput .= "$('#pfupload_listingtypes').val(itemid);
																			";
																		}
																		$this->ScriptOutput .= "

																	}else{
																		$('#pfupload_listingtypes').val(itemid);
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
																$('.pfsubmit-inner-listingtype').pfLoadingOverlay({action:'hide'});
															},1000);

								
														});
													}
												";
											/* End: Function for sub listing types */

											/* Start: Function for sub sub listing types */
												$this->ScriptOutput .= "
													$.pf_get_subsublistingtypes = function(itemid,defaultv){
														$.ajax({
													    	beforeSend:function(){
													    		$('.pfsubmit-inner-listingtype').pfLoadingOverlay({action:'show',message: '".esc_html__("Loading fields ...",'pointfindercoreelements')."'});
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

															if ($stp4_forceu == 1) {
																$this->ScriptOutput .= "$('#pfupload_subsublistingtypes').rules('add',{required: true,messages:{required:'".$setup4_submitpage_listingtypes_verror."'}});";
															}

															if (class_exists('Pointfinderstripesubscriptions') && $this->itemrecurringstatus == 1) {
																$this->ScriptOutput .= "$('#pfupload_subsublistingtypes').prop('disabled', true);";
															}
															
															$this->ScriptOutput .= "
															if ($.pf_tablet_check()) {
																$('#pfupload_subsublistingtypes').select2({
																	placeholder: '".esc_html__("Please select",'pointfindercoreelements')."',
																	formatNoMatches:'".esc_html__("No match found",'pointfindercoreelements')."',
																	allowClear: true,
																	minimumResultsForSearch: 10
																});
															}

															if ($.pf_tablet_check() == false) {
																	$('.pf-special-selectbox').each(function(index, el) {
																		

																		var pfplc = $(this).data('pf-plc');
																		if(typeof pfplc == 'undefined'){pfplc = '".esc_html__("Please select",'pointfindercoreelements')."';}
																		if((
																			!$('option:selected',this).attr('value') ||
																			$('option:selected',this).attr('value') == '' 
																			|| typeof $('option:selected',this).attr('value') == 'undefined' 
																			|| $('option:selected',this).attr('value') == null
																			) 
																			&& (!$(this).attr('multiple') || typeof $(this).attr('multiple') == 'undefined' || $(this).attr('multiple') == false || $(this).attr('multiple') == 'false')
																		){
																			
																			$(this).children('option:first').replaceWith(\"<option value='' selected='selected'>\"+pfplc+\"</option>\");
																		}else{
																			$(this).children('option:first').replaceWith('<option value=\"\">'+pfplc+'</option>');
																		}
																	});
																};


																$('#pfupload_subsublistingtypes').change(function(){
																	if($('#pfupload_subsublistingtypes').val() != 0){

																		if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																			$('#pfupload_listingtypes').val($(this).val()).trigger('change');
																		}else{
																			$('#pfupload_listingtypes').val($(this).val());
																		}

																	}else{

																		if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																			$('#pfupload_listingtypes').val($('#pfupload_sublistingtypes').val()).trigger('change');
																		}else{
																			$('#pfupload_listingtypes').val($('#pfupload_sublistingtypes').val());
																		}
																	}
																});
															}

														}).complete(function(obj,obj2){
															if (obj.responseText != '') {

																if (defaultv != '') {

																	if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																		$('#pfupload_listingtypes').val(defaultv).trigger('change');
																	}else{
																		$('#pfupload_listingtypes').val(defaultv);
																	}
																}else{

																	if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
																		";
																		if ($item_level_value == 2 && $params['post_id'] != '') {
																			$this->ScriptOutput .= "$('#pfupload_listingtypes').val(itemid).trigger('change');";
																		}elseif (empty($params['post_id'])) {
																			$this->ScriptOutput .= "$('#pfupload_listingtypes').val(itemid);";
																		}
																		$this->ScriptOutput .= "
																	}else{
																		$('#pfupload_listingtypes').val(itemid);
																	}
																}
															}
															setTimeout(function(){
																$('.pfsubmit-inner-listingtype').pfLoadingOverlay({action:'hide'});
															},1000);
														});
													}
												";
											/* End: Function for sub sub listing types */


											/* Start: Create Limit Array */

												$this->ScriptOutput .= "var pflimitarray = [";
												$pflimittext = '';
												/*Get Limits for Areas*/
												if ($st4_sp_med == 1) {
													$pflimittext .= "'pf_address_area'";
												}

												/*Get Limits for Areas*/
												if ($setup3_pointposttype_pt5_check == 1 && $setup4_submitpage_locationtypes_check == 1) {
													if (!empty($pflimittext)) {$pflimittext .= ",";}
													$pflimittext .= "'pf_location_area'";
												}


												/*Get Limits for Image Area*/
												if($setup4_submitpage_imageupload == 1){
													if (!empty($pflimittext)) {$pflimittext .= ",";}
													$pflimittext .= "'pf_image_area'";
												}

												/*Get Limits for Header Image Area*/
												if($setup4_coviup == 1){
													if (!empty($pflimittext)) {$pflimittext .= ",";}
													$pflimittext .= "'pf_header_area'";
												}

												/*Get Limits for File Area*/
												if($stp4_fupl == 1){
													if (!empty($pflimittext)) {$pflimittext .= ",";}
													$pflimittext .= "'pf_file_area'";
												}

												$this->ScriptOutput .= $pflimittext;
												$this->ScriptOutput .= "];";
											/* End: Create Limit Array */


											/* Start: Check Limits */
												$this->ScriptOutput .= "
												$.pf_get_checklimits = function(itemid,limitvalue){
													$.ajax({
														url: theme_scriptspf.ajaxurl,
														type: 'POST',
														dataType: 'json',
														data: {
															action: 'pfget_listingtypelimits',
															id: itemid,
															limit: limitvalue,
															lang: '".$lang_custom."',
															security: '".wp_create_nonce('pfget_listingtypelimits')."'
														},
													}).success(function(obj) {";

														/* Address Area Check */
														if ($st4_sp_med == 1) {
															$this->ScriptOutput .= "
															if (obj.pf_address_area == 2) {
																$('.pfsubmit-inner-sub-address').hide();";
																if ($st4_sp_med2 == 1) {
																	$this->ScriptOutput .= "
																	$('#pfupload_address').rules('remove');
																	$('#pfupload_lng_coordinate').rules('remove');
																	$('#pfupload_lat_coordinate').rules('remove');
																	";
																}
																$this->ScriptOutput .= "
															}else{
																$('.pfsubmit-inner-sub-address').show();";
																if ($st4_sp_med2 == 1) {
																	$this->ScriptOutput .= "
																	$('#pfupload_address').rules('add',{required: true,messages:{required:\"".esc_html__("Please enter an address",'pointfindercoreelements')."\"}});
																	$('#pfupload_lng_coordinate').rules('add',{required: true,messages:{required:\"".esc_html__('Please select a marker location or type lat/lng.', 'pointfindercoreelements')."\"}});
																	$('#pfupload_lat_coordinate').rules('add',{required: true,messages:{required:\"".esc_html__('Please select a marker location or type lat/lng.', 'pointfindercoreelements')."\"}});
																	";
																}
																$this->ScriptOutput .= "
																$.pf_submit_page_map();
															}
															";
														}

														/* Location Check */
														if ($setup3_pointposttype_pt5_check == 1 && $setup4_submitpage_locationtypes_check == 1) {
															$this->ScriptOutput .= "
															if (obj.pf_location_area == 2) {
																$('.pfsubmit-inner-sub-location').hide();
															";
															if ($setup4_submitpage_locationtypes_validation == 1) {
																$this->ScriptOutput .= "$('#pfupload_locations').rules('remove');";
															}
															$this->ScriptOutput .= "
															}else{
																$('.pfsubmit-inner-sub-location').show();
															";
															if ($setup4_submitpage_locationtypes_validation == 1) {
																$this->ScriptOutput .= "$('#pfupload_locations').rules('add',{required: true,messages:{required:\"".$setup4_submitpage_locationtypes_verror."\"}});";
															}
															$this->ScriptOutput .= "
															}";
														}


														/* Image Area Check */
														if ($setup4_submitpage_imageupload == 1) {
															$this->ScriptOutput .= "
															if (obj.pf_image_area == 2) {
																$('.pfsubmit-inner-sub-image').hide();
															";
															$itemfieldname = 'pfuploadimagesrc' ;
															if ($params['formtype'] != 'edititem' && $setup4_submitpage_featuredverror_status == 1) {
																$this->ScriptOutput .= "$('#".$itemfieldname."').rules('remove');";
															}
															$this->ScriptOutput .= "
															}else{
																$('.pfsubmit-inner-sub-image').show();
															";
															if ($params['formtype'] != 'edititem' && $setup4_submitpage_featuredverror_status == 1) {
																$this->ScriptOutput .= "$('#".$itemfieldname."').rules('add',{required: true,messages:{required:\"".esc_html("Please upload minimum one image.","pointfindercoreelements")."\"}});";
															}
															$this->ScriptOutput .= "
															}";
														}


														/* Header Image Area Check */
														if ($setup4_coviup == 1) {
															$this->ScriptOutput .= "
															if (obj.pf_header_area == 2) {
																$('.pfsubmit-inner-sub-cimage').hide();
															";
															$itemfieldname = 'pfuploadcovimagesrc' ;
															if ($params['formtype'] != 'edititem' && $setup4_coviup_req == 1) {
																$this->ScriptOutput .= "$('#".$itemfieldname."').rules('remove');";
															}
															$this->ScriptOutput .= "
															}else{
																$('.pfsubmit-inner-sub-cimage').show();
															";
															if ($params['formtype'] != 'edititem' && $setup4_coviup_req == 1) {
																$this->ScriptOutput .= "$('#".$itemfieldname."').rules('add',{required: true,messages:{required:\"".esc_html__('Please upload cover image.','pointfindercoreelements')."\"}});";
															}
															$this->ScriptOutput .= "
															}";
														}

														/* File Area Check */
														if ($stp4_fupl == 1) {
															$this->ScriptOutput .= "
															if (obj.pf_file_area == 2) {
																$('.pfsubmit-inner-sub-file').hide();
															";
															$itemfieldname = 'pfuploadfilesrc' ;
															if ($params['formtype'] != 'edititem' && $stp4_err_st == 1) {
																$this->ScriptOutput .= "$('#".$itemfieldname."').rules('remove');";
															}
															$this->ScriptOutput .= "
															}else{
																$('.pfsubmit-inner-sub-file').show();
															";
															if ($params['formtype'] != 'edititem' && $stp4_err_st == 1) {
																$this->ScriptOutput .= "$('#".$itemfieldname."').rules('add',{required: true,messages:{required:\"".esc_html__('Please upload an attachment.', 'pointfindercoreelements')."\"}});";
															}
															$this->ScriptOutput .= "
															}";
														}

													$this->ScriptOutput .= "
													}).complete(function(){
														";

														$this->ScriptOutput .= "
													});
												};";
											/* End: Check Limits */




											/* Start: Page Loading functions */
												$this->ScriptOutput .= "$(function(){";

													/* Edit Functions */
													if ($params['post_id'] != '') {

														$this->ScriptOutput .= "
														$.pf_get_checklimits('".$item_defaultvalue_output."',pflimitarray);

														$.pf_get_sublistingtypes($('#pfupload_listingtypes').val(),'".$sub_level."');


														if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) != -1)) {

															$.pf_get_modules_now(".$item_defaultvalue_output.");

														}

														if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) != -1)) {

															$.pf_get_modules_now(".$item_defaultvalue_output.");

														}else{
															";
															if ($item_level_value == 0 && $params['post_id'] != '') {
																$this->ScriptOutput .= "$.pf_get_modules_now($('#pfupload_listingtypes').val());";
															}elseif(empty($params['post_id'])){
																$this->ScriptOutput .= "$.pf_get_modules_now($('#pfupload_listingtypes').val());";
															}
															$this->ScriptOutput .= "
														}

														";
														if (empty($sub_sub_level) && !empty($sub_level)) {
															$this->ScriptOutput .= "$('#pfupload_listingtypes').val('".$sub_level."');";
														}
													}
												$this->ScriptOutput .= "});";
											/* End: Page Loading functions */


											/* Start: Listing Type Change Functions */
												$this->ScriptOutput .= "
												$('#pfupload_listingtypes').change(function(){

													$.pf_get_modules_now($(this).val(),'pointfinderfeatures');

												});

												$('.pflistingtypeselector').change(function(){

													$('.pf-sub-listingtypes-container').html('');

													$('#pfupload_listingtypes').val($(this).val()).trigger('change');

													$.pf_get_sublistingtypes($(this).val(),'');

													$.pf_get_checklimits($(this).val(),pflimitarray);

													$.pf_get_priceoutput();
												});";
												if ($st4_sp_med == 1) {
													$this->ScriptOutput .= "$.pf_submit_page_map();";
												}
											/* End: Listing Type Change Functions */

										/**
										*Listing Types
										**/


										/**
										* Title & Description Area
										**/
											$this->FieldOutput .= '<div class="pf-excludecategory-container">';

											$this->FieldOutput .= '<div class="pfsubmit-title">'.esc_html__("INFORMATION",'pointfindercoreelements').'</div>';
											$this->FieldOutput .= '<section class="pfsubmit-inner">';

												/**
												*Title
												**/
													$item_title = ($params['post_id'] != '') ? get_the_title($params['post_id']) : '' ;
													$this->FieldOutput .= '
													<section class="pfsubmit-inner-sub">
								                        <label for="item_title" class="lbl-text">'.esc_html__('Title','pointfindercoreelements').':</label>
								                        <label class="lbl-ui">
								                        	<input type="text" name="item_title" id="item_title" class="input" value="'.$item_title.'"/>';
													if ($setup4_submitpage_titletip!='') {
														$this->FieldOutput .= '<b class="tooltip left-bottom"><em>'.$setup4_submitpage_titletip.'</em></b>';
													}
								                    $this->FieldOutput .= '</label>
								                   </section>
													';
													$this->PFValidationCheckWrite(1,esc_html__('Please type a title.', 'pointfindercoreelements'),'item_title');
												/**
												*Title
												**/


												/**
												*Desc
												**/

													$setup4_sbp_dh = $this->PFSAIssetControl('setup4_sbp_dh','','1');
													if ($setup4_sbp_dh == 1) {
														$setup4_submitpage_descriptionvcheck = $this->PFSAIssetControl('setup4_submitpage_descriptionvcheck','','0');
														$item_desc = ($params['post_id'] != '') ? get_post_field('post_content',$params['post_id']) : '' ;

														$this->FieldOutput .= '
														<section class="pfsubmit-inner-sub">
									                        <label for="item_desc" class="lbl-text">'.esc_html__('Description','pointfindercoreelements').':</label>
									                        <label class="lbl-ui">';

									                        $this->FieldOutput .= do_action( 'pf_desc_editor_hook',$item_desc);
									                        $this->FieldOutput .= '<textarea id="item_desc" name="item_desc" class="textarea mini">'.$item_desc.'</textarea>';

									                    $this->FieldOutput .= '</label></section>';
														$this->PFValidationCheckWrite($setup4_submitpage_descriptionvcheck,esc_html__('Please write a description', 'pointfindercoreelements'),'item_desc');
													}
												/**
												*Desc
												**/

											$this->FieldOutput .= '</section>';
										/**
										* Title & Description Area
										**/


										/**
										*Item Types
										**/
											if($setup3_pointposttype_pt4_check == 1 && $setup4_submitpage_itemtypes_check == 1){
												$setup4_submitpage_itemtypes_title = $this->PFSAIssetControl('setup4_submitpage_itemtypes_title','','Item Type');

												$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-sub-itype">'.$setup4_submitpage_itemtypes_title.'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-sub-itype"></section>';
											}
										/**
										*Item Types
										**/



										/**
										*Conditions
										**/
											if($setup3_pt14_check == 1 && $setup4_submitpage_conditions_check == 1){

												$setup4_submitpage_conditions_title = $this->PFSAIssetControl('setup4_submitpage_conditions_title','','Conditions');

												$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-sub-conditions">'.$setup4_submitpage_conditions_title.'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-sub-conditions"></section>';
											}
										/**
										*Conditions
										**/




										/**
										*Start : Event Details
										**/
											$this->FieldOutput .= '<div class="eventdetails-output-container"></div>';
										/**
										*End : Event Details
										**/




										/**
										*Start : Custom Fields
										**/
											$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-customfields-title">'.esc_html__('ADDITIONAL INFO','pointfindercoreelements').'</div>';
											$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-customfields"></section>';
										/**
										*End : Custom Fields
										**/



										/**
										*Features
										**/
											if($setup3_pointposttype_pt6_check == 1 && $setup4_submitpage_featurestypes_check == 1){
												$setup4_submitpage_featurestypes_title = $this->PFSAIssetControl('setup4_submitpage_featurestypes_title','','Features');

												$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-features-title">'.$setup4_submitpage_featurestypes_title.'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-features"></section>';
											}
										/**
										*Features
										**/


										/**
										*Custom Tabs
										**/
											$this->FieldOutput .= '<div class="customtab-output-container"></div>';

										/**
										*Custom Tabs
										**/


										/**
										*Post Tags
										**/
											$stp4_psttags = $this->PFSAIssetControl('stp4_psttags','','1');
											if ($stp4_psttags == 1) {
												$this->FieldOutput .= '<div class="pfsubmit-title">'.esc_html__('Tags','pointfindercoreelements').'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner">';
												$this->FieldOutput .= '
												<section class="pfsubmit-inner-sub">

							                        <label class="lbl-ui">
							                        	<input type="text" name="posttags" id="posttags" class="input" placeholder="'.esc_html__('Please add post tags with comma like: keyword,keyword2,keyword3','pointfindercoreelements').'" value=""/>
													</label>

							                    ';

							                    $post_tags = wp_get_post_tags( $params['post_id']);
												if (isset($post_tags) && $params['formtype'] == 'edititem') {
													$this->FieldOutput .= '<div class="pf-posttag-container">';
							                    	foreach ($post_tags as $value) {
							                    		$this->FieldOutput .= '<div class="pf-item-posttag">'.$value->name.'';
							                    		$this->FieldOutput .= '<a data-pid="'.$value->term_taxonomy_id.'" data-pid2="'.$params['post_id'].'"  id="pf-delete-tag-'.$value->term_taxonomy_id.'" title="'.esc_html__('Delete','pointfindercoreelements').'"><i class="far fa-times-circle"></i></a></div>';
							                    	}
							                    	$this->FieldOutput .= '</div>';
												}
												$this->FieldOutput .= '</section></section>';
											}
										/**
										*Post Tags
										**/

										if ($setup4_submitpage_video == 1) {
											$taxonomies = array(
								                'pointfinderltypes'
								            );

								            $args = array(
								                'orderby'           => 'name',
								                'order'             => 'ASC',
								                'hide_empty'        => false,
								                'parent'            => 0,
								            );
											$pf_get_term_details = get_terms($taxonomies,$args);
										}

										/**
										*Opening Hours
										**/
											$this->FieldOutput .= '<div class="openinghourstab-output-container"></div>';

											$setup3_modulessetup_openinghours = $this->PFSAIssetControl('setup3_modulessetup_openinghours','','0');
											$setup3_modulessetup_openinghours_ex = $this->PFSAIssetControl('setup3_modulessetup_openinghours_ex','','1');
										/**
										*Opening Hours
										**/



										/**
										*Featured Video
										**/
											$this->FieldOutput .= '<div class="pfvideotab-output-container"></div>';
										/**
										*Featured Video
										**/



										/**
										*Locations
										**/
											if($setup3_pointposttype_pt5_check == 1 && $setup4_submitpage_locationtypes_check == 1){

													$stp4_loc_new = $this->PFSAIssetControl('stp4_loc_new','','0');
													$setup4_submitpage_locationtypes_title = $this->PFSAIssetControl('setup4_submitpage_locationtypes_title','','Location');

													$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-sub-location">'.$setup4_submitpage_locationtypes_title.'</div>';
													$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-sub-location pfsubmit-location-errc">';

													if ($stp4_loc_new == 1) {

														$stp4_sublotyp_title = $this->PFSAIssetControl('stp4_sublotyp_title','',esc_html__('Sub Location', 'pointfindercoreelements'));
														$stp4_subsublotyp_title = $this->PFSAIssetControl('stp4_subsublotyp_title','',esc_html__('Sub Sub Location', 'pointfindercoreelements'));

														$itemfieldname = 'pfupload_locations' ;

														$this->PFValidationCheckWrite($setup4_submitpage_locationtypes_validation,$setup4_submitpage_locationtypes_verror,$itemfieldname);


														$item_defaultvalue = ($params['post_id'] != '') ? wp_get_post_terms($params['post_id'], 'pointfinderlocations', array("fields" => "ids")) : '' ;
														$item_defaultvalue_output = $sub_level = $sub_sub_level = $item_defaultvalue_output_orj = '';



														if (isset($item_defaultvalue[0])) {
															$item_defaultvalue_output_orj = $item_defaultvalue[0];
															$find_top_parent = $this->pf_get_term_top_most_parent($item_defaultvalue[0],'pointfinderlocations');

															switch ($find_top_parent['level']) {
																case '1':
																	$sub_level = $item_defaultvalue[0];
																	break;

																case '2':
																	$sub_sub_level = $item_defaultvalue[0];
																	$sub_level = $this->pf_get_term_top_parent($item_defaultvalue[0],'pointfinderlocations');
																	break;
															}


															$item_defaultvalue_output = $find_top_parent['parent'];
														}

														$this->FieldOutput .= '<input type="hidden" name="pfupload_locations" id="pfupload_locations" value="'.$item_defaultvalue_output.'"/>';

														$this->FieldOutput .= '<section class="pfsubmit-inner-sub pfsubmit-inner-sub-locloader">';
														$fields_output_arr = array(
															'listname' => 'pflocationselector',
													        'listtype' => 'locations',
													        'listtitle' => $setup4_submitpage_locationtypes_title,
													        'listsubtype' => 'pointfinderlocations',
													        'listdefault' => $item_defaultvalue_output,
													        'listmultiple' => 0,
													        'parentonly' => 1
														);
														$this->FieldOutput .= $this->PFGetList($fields_output_arr);
														$this->FieldOutput .= '<div class="pf-sub-locations-container"></div>';

														/* Custom location */
														$stp4_loc_add = $this->PFSAIssetControl('stp4_loc_add','','0');
														if ($stp4_loc_add == 1) {

															$this->FieldOutput .= '<section class="pfsubmit-inner-sub-customcity">';
															$this->FieldOutput .= ' <label for="item_title" class="lbl-text">'.esc_html__('Custom City','pointfindercoreelements').': '.esc_html__('(Optional)','pointfindercoreelements').'</label>';
																$this->FieldOutput .= '
									                            <label for="file" class="lbl-ui" >
									                            <input class="input" name="customlocation" placeholder="'.esc_html__("If you couldn't find your city. Please type custom city here.",'pointfindercoreelements').'" value="">
									                            </label>
																';
															$this->FieldOutput .= '</section>';
														}


														$this->FieldOutput .= '</section>';
														$this->ScriptOutput .= '
														if ($.pf_tablet_check()) {
														$("#pflocationselector").select2({
															placeholder: "'.esc_html__("Please select","pointfindercoreelements").'",
															formatNoMatches:"'.esc_html__("Nothing found.","pointfindercoreelements").'",
															allowClear: true,
															minimumResultsForSearch: 10
														});
														}
														';

														$this->ScriptOutput .= "
															/* Start: Function for sub location types */
																$.pf_get_sublocations = function(itemid,defaultv){
																	$.ajax({
																    	beforeSend:function(){
																    		$('.pfsubmit-inner-sub-locloader').pfLoadingOverlay({action:'show',message: '".esc_html__('Loading locations...','pointfindercoreelements')."'});
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
																					$('#pfupload_locations').val($(this).val()).trigger('change');
																					$.pf_get_subsublocations($(this).val(),'');
																					$('.pfsubmit-inner-sub-customcity').show();
																				}else{
																					$('#pfupload_locations').val(itemid);
																					$('.pfsubmit-inner-sub-customcity').hide();
																				}
																				$('.pfsubsublocations').remove();
																			});
																		}
																	}).complete(function(obj,obj2){
																		if (obj.responseText != '') {
																			if (defaultv != '') {
																				$('#pfupload_locations').val(defaultv).trigger('change');
																				//$.pf_get_subsublocations($('#pfupload_sublocations').val(),'');
																				$('.pfsubmit-inner-sub-customcity').show();
																			}else{
																				$('#pfupload_locations').val(itemid).trigger('change');
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
																			$('.pfsubmit-inner-sub-locloader').pfLoadingOverlay({action:'hide'});
																		},1000);


																	});
																}


																$.pf_get_subsublocations = function(itemid,defaultv){
																	$.ajax({
																    	beforeSend:function(){
																    		$('.pfsubmit-inner-sub-locloader').pfLoadingOverlay({action:'show',message: '".esc_html__('Loading locations...','pointfindercoreelements')."'});
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
																				placeholder: '".esc_html__('Please select','pointfindercoreelements')."',
																				formatNoMatches:'".esc_html__('No match found','pointfindercoreelements')."',
																				allowClear: true,
																				minimumResultsForSearch: 10
																			});
																		}



																			$('#pfupload_subsublocations').change(function(){
																				if($(this).val() != 0){
																					$('#pfupload_locations').val($(this).val()).trigger('change');
																				}else{
																					$('#pfupload_locations').val($('#pfupload_sublocations').val())
																				}
																			});

																	}).complete(function(obj,obj2){
																		if (obj.responseText != '') {
																			if (defaultv != '') {
																				$('#pfupload_locations').val(defaultv).trigger('change');
																			}else{
																				$('#pfupload_locations').val(itemid).trigger('change');
																			}
																		}
																		setTimeout(function(){
																			$('.pfsubmit-inner-sub-locloader').pfLoadingOverlay({action:'hide'});
																		},1000);
																	});
																}

															/* End: Function for sub location types */
															";


														$this->ScriptOutput .= "$(function(){";

															if ($params['post_id'] != '') {
																$this->ScriptOutput .= "$.pf_get_sublocations($('#pfupload_locations').val(),'".$sub_level."');";
																if (empty($sub_sub_level) && !empty($sub_level)) {
																	$this->ScriptOutput .= "$('#pfupload_locations').val('".$sub_level."');";
																}
															}

														$this->ScriptOutput .= "});";
														$stp4_loc_level = $this->PFSAIssetControl('stp4_loc_level','',3);
														$this->ScriptOutput .= "
														$('#pflocationselector').change(function(){
															$('.pf-sub-locations-container').html('');
															$('#pfupload_locations').val($(this).val()).trigger('change');
														";
														if ($stp4_loc_level == 2) {
															$this->ScriptOutput .= "
																if($(this).val() != 0 && $(this).val() != null){
																	$('.pfsubmit-inner-sub-customcity').show();
																}else{
																	$('.pfsubmit-inner-sub-customcity').hide();
																}
															";
														}
														$this->ScriptOutput .= "
															$.pf_get_sublocations($(this).val(),'');
														});
														";

													}else{

														$setup4_submitpage_locationtypes_multiple = $this->PFSAIssetControl('setup4_submitpage_locationtypes_multiple','','0');

														$itemfieldname = ($setup4_submitpage_locationtypes_multiple == 1) ? 'pfupload_locations[]' : 'pfupload_locations' ;

														$this->PFValidationCheckWrite($setup4_submitpage_locationtypes_validation,$setup4_submitpage_locationtypes_verror,$itemfieldname);

														$item_defaultvalue = ($params['post_id'] != '') ? wp_get_post_terms($params['post_id'], 'pointfinderlocations', array("fields" => "ids")) : '' ;

														$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
														$fields_output_arr = array(
															'listname' => 'pfupload_locations',
													        'listtype' => 'locations',
													        'listtitle' => $setup4_submitpage_locationtypes_title,
													        'listsubtype' => 'pointfinderlocations',
													        'listdefault' => $item_defaultvalue,
													        'listmultiple' => $setup4_submitpage_locationtypes_multiple
														);
														$this->FieldOutput .= $this->PFGetList($fields_output_arr);
														$this->FieldOutput .= '</section>';
														$this->ScriptOutput .= '
														if ($.pf_tablet_check()) {
														$("#pfupload_locations").select2({
															placeholder: "'.esc_html__("Please select","pointfindercoreelements").'",
															formatNoMatches:"'.esc_html__("Nothing found.","pointfindercoreelements").'",
															allowClear: true,
															minimumResultsForSearch: 10
														});
														}
														';

													}

													$this->FieldOutput .= '</section>';
											}
										/**
										*Locations
										**/


										/**
										*Map  & Locations
										**/

											if($st4_sp_med == 1){
												$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-sub-address">'.esc_html__('ADDRESS','pointfindercoreelements').'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-sub-address pfsubmit-address-errc">';


												$setup4_submitpage_maparea_title = $this->PFSAIssetControl('setup4_submitpage_maparea_title','','');
												$setup4_submitpage_maparea_tooltip = $this->PFSAIssetControl('setup4_submitpage_maparea_tooltip','','');


												$this->PFValidationCheckWrite($st4_sp_med2,esc_html__('Please select a marker location or type lat/lng.', 'pointfindercoreelements'),'pfupload_lat');
												$this->PFValidationCheckWrite($st4_sp_med2,esc_html__('Please select a marker location or type lat/lng.', 'pointfindercoreelements'),'pfupload_lng');
												$this->PFValidationCheckWrite($st4_sp_med2,esc_html__('Please enter an address','pointfindercoreelements'),'pfupload_address');


												$setup5_mapsettings_zoom = $this->PFSAIssetControl('setup42_searchpagemap_zoom','','6');
												$setup5_mapsettings_type = 'ROADMAP';
												$setup42_searchpagemap_lat = $this->PFSAIssetControl('setup42_searchpagemap_lat','','');
												$setup42_searchpagemap_lng = $this->PFSAIssetControl('setup42_searchpagemap_lng','','');

												$setup42_searchpagemap_lat_text = $setup42_searchpagemap_lng_text = '';

												if($params['post_id'] != ''){
													$coordinates = get_post_meta( $params['post_id'], 'webbupointfinder_items_location', true );

													if(isset($coordinates)){
														$coordinates = explode(',', $coordinates);

														if (isset($coordinates[1])) {
															$setup42_searchpagemap_lat = $setup42_searchpagemap_lat_text = $coordinates[0];
															$setup42_searchpagemap_lng = $setup42_searchpagemap_lng_text = $coordinates[1];
														}else{
															$setup42_searchpagemap_lat = $setup42_searchpagemap_lat_text = '';
															$setup42_searchpagemap_lng = $setup42_searchpagemap_lng_text = '';
														}

													}
												}

												$description = ($setup4_submitpage_maparea_tooltip!='') ? ' <a href="javascript:;" class="info-tip" aria-describedby="helptooltip">?<span role="tooltip">'.$setup4_submitpage_maparea_tooltip.'</span></a>' : '' ;

												$pfupload_address = ($params['post_id'] != '') ? esc_html(get_post_meta($params['post_id'], 'webbupointfinder_items_address', true)) : '' ;


												$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
													$this->FieldOutput .= '<div class="typeahead__container we-change-addr-input-upl" data-text1="'.wp_sprintf( esc_html__( "No results found for %s", "pointfindercoreelements" ), "<b>{{query}}</b>" ).'"><div class="typeahead__field"><span class="typeahead__query"><label for="pfupload_address" class="lbl-text">'.$setup4_submitpage_maparea_title.':'.$description.'</label>';
													$this->FieldOutput .= '<label class="lbl-ui pflabelfixsearch search">';
													$this->FieldOutput .= '<input id="pfupload_address" value="'.$pfupload_address.'" name="pfupload_address" class="controls input" type="text" placeholder="'.esc_html__('Please type an address...','pointfindercoreelements').'">';
													$this->FieldOutput .= '<a class="button" id="pf_search_geolocateme" data-istatus="false" title="'.esc_html__('Locate me!','pointfindercoreelements').'">
													<img src="'.PFCOREELEMENTSURL .'images/geoicon.svg" width="16px" height="16px" class="pf-search-locatemebut injectable" alt="'.esc_html__('Locate me!','pointfindercoreelements').'">
													<div class="pf-search-locatemebutloading"></div>
													</a>';
													$this->FieldOutput .= '</label></span></div></div>';


													$stp5_mapty = $this->PFSAIssetControl('stp5_mapty','',1);
											    	$wemap_here_appid = $wemap_here_appcode = $we_special_key = '';
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
													$wemap_geoctype = $this->PFSAIssetControl('wemap_geoctype','','');
													$this->FieldOutput .= '<div id="pfupload_map" style="width: 100%;height: 300px;border:0" 
										    		data-lat="'.$setup42_searchpagemap_lat.'" 
										    		data-lng="'.$setup42_searchpagemap_lng.'" 
										    		data-zoom="'.$setup5_mapsettings_zoom.'" 
										    		data-zoommx="'.$setup5_mapsettings_zoom.'" 
										    		data-mtype="'.$stp5_mapty.'" 
										    		data-key="'.$we_special_key.'" 
										    		data-hereappid="'.$wemap_here_appid.'" 
													data-hereappcode="'.$wemap_here_appcode.'" 
													data-geoctype="'.$wemap_geoctype.'" 
													data-pf-istatus="false"></div>';


												$this->FieldOutput .= '</section>';


												$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';

													$this->FieldOutput .= '<div class="row">';


													$this->FieldOutput .= '<div class="col6 first"><div id="pfupload_lat">';
														 $this->FieldOutput .= '<label for="pfupload_lat" class="lbl-text">'.esc_html__('Lat Coordinate','pointfindercoreelements').':</label>
						                                <label class="lbl-ui">
						                                	<input type="text" name="pfupload_lat" id="pfupload_lat_coordinate" class="input" value="'.$setup42_searchpagemap_lat_text.'" />
						                                </label>';
													$this->FieldOutput .= '</div></div>';



													$this->FieldOutput .= '<div class="col6 last colspacer-two"><div id="pfupload_lng">';
														$this->FieldOutput .= '<label for="pfupload_lng" class="lbl-text">'.esc_html__('Lng Coordinate','pointfindercoreelements').':</label>
						                                <label class="lbl-ui">
						                                	<input type="text" name="pfupload_lng" id="pfupload_lng_coordinate" class="input" value="'.$setup42_searchpagemap_lng_text.'"/>
						                                </label>';
													$this->FieldOutput .= '</div></div>';


													$this->FieldOutput .= '<div>';/*row*/
												$this->FieldOutput .= '</section>';

												if($st4_sp_medst == 1){
													/* Streetview status check */
										
													$pointfinder_center_lat = $this->PFSAIssetControl('setup42_searchpagemap_lat','','33.87212589943945');
									                $pointfinder_center_lng = $this->PFSAIssetControl('setup42_searchpagemap_lng','','-118.19297790527344');
									                $setup42_searchpagemap_zoom = $this->PFSAIssetControl('setup42_searchpagemap_zoom','','1');

									                $pfitemid = $params['post_id'];
									                if (isset($pfitemid)) {
									                    $pffmarkerget = esc_attr(get_post_meta( $pfitemid, 'webbupointfinder_items_location', true ));
									                    if (!empty($pffmarkerget)) {
									                         $pfcoordinates = explode( ',', $pffmarkerget);
									                    }
									                }

									                if (isset($pfcoordinates[0])) {
									                    if (empty($pfcoordinates[0])) {
									                        $pfcoordinates = '';
									                    }
									                }
									                if (!empty($pfcoordinates)) {
									                    if (!is_array($pfcoordinates)) {
									                        $pfcoordinates = array($pointfinder_center_lat,$pointfinder_center_lng,$setup42_searchpagemap_zoom);
									                    }else{
									                        if (isset($pfcoordinates[2]) == false) {
									                            $pfcoordinates[2] = $setup42_searchpagemap_zoom;
									                        }
									                    }
									                }else{
									                    $pfcoordinates = array($pointfinder_center_lat,$pointfinder_center_lng,$setup42_searchpagemap_zoom);
									                }
									                if (isset($pfitemid)) {
									                	$pfstviewcget = get_post_meta( $pfitemid, 'webbupointfinder_item_streetview',true);
									                	$stview_heading = (isset($pfstviewcget['heading']))?$pfstviewcget['heading']:0;
									                	$stview_pitch = (isset($pfstviewcget['pitch']))?$pfstviewcget['pitch']:0;
									                	$stview_zoom =(isset($pfstviewcget['zoom']))?$pfstviewcget['zoom']:0;
									                }else{
									                	$stview_heading = $stview_pitch = $stview_zoom = 0;
									                }


													$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
													$this->FieldOutput .= '<label for="pfitempagestreetviewMap" class="lbl-text">'.esc_html__('STREET VIEW:','pointfindercoreelements' ).'</label>';
														$this->FieldOutput .= '<div id="pfitempagestreetviewMap" data-pfitemid="' . $pfitemid . '" data-pfcoordinateslat="'.$pfcoordinates[0].'" data-pfcoordinateslng="'.$pfcoordinates[1].'" data-pfzoom = "'.$pfcoordinates[2].'"></div>';
										                $this->FieldOutput .= '<input id="webbupointfinder_item_streetview-heading" name="webbupointfinder_item_streetview[heading]" value="'.$stview_heading.'" type="hidden" />';
										                $this->FieldOutput .= '<input id="webbupointfinder_item_streetview-pitch" name="webbupointfinder_item_streetview[pitch]" value="'.$stview_pitch.'" type="hidden" />';
										                $this->FieldOutput .= '<input id="webbupointfinder_item_streetview-zoom" name="webbupointfinder_item_streetview[zoom]" value="'.$stview_zoom.'" type="hidden" />';
													$this->FieldOutput .= '</section>';
												}

											$this->FieldOutput .= '</section>';

											}
										/**
										*Map & Locations
										**/

										$content_special_360 = '';		
										$this->FieldOutput .= apply_filters( 'pointfinder_custom_form_elements', $content_special_360, $params['post_id'],$params['formtype'] );

										/**
										*Cover Image Upload
										**/
											$setup4_coviup = $this->PFSAIssetControl('setup4_coviup','','0');
											if ($setup4_coviup == 1) {
												$setup4_submitpage_status_old = $this->PFSAIssetControl('setup4_submitpage_status_old','','0');
												$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-sub-cimage">'.esc_html__('HEADER IMAGE UPLOAD','pointfindercoreelements' ).'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pfitemcoverimgcontainer pferrorcontainer pfsubmit-inner-sub-cimage">';

												/**
												*Old Image Upload - if this is an ie9 or 8
												**/
													
													

													$this->FieldOutput .= '<div class="pfuploadedcoverimages"></div>';


													$imagesvalue = '';

													$images_newlimit = 1;

													if ($params['formtype'] == 'edititem') {
														$images_of_thispost = get_post_meta($params['post_id'],'webbupointfinder_item_headerimage');
														if (isset($images_of_thispost[0])) {
															if (!empty($images_of_thispost[0])) {
																$images_newlimit = 0;
															}
														}
													}

													$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
													$setup4_submitpage_imagesizelimit = $this->PFSAIssetControl('setup4_submitpage_imagesizelimit','','2');
													$nonceimgup = wp_create_nonce('pfget_imageupload');
													$this->FieldOutput .= '<label for="file" class="lbl-text"></label><small style="margin-bottom:4px;display:block;">'.esc_html__('Recommended size width: 2000px and height: 485 px. (Better for large screens.)','pointfindercoreelements').'</small>';
													$this->FieldOutput .= '<div class="pfuploadcoverimg-container"><a id="pfuploadcoverimg_remove" style="font-size: 12px;line-height: 14px;"><i class="far fa-times-circle" style="font-size: 14px;"></i> '.esc_html__('Remove Uploaded Header Image','pointfindercoreelements').'</a></div>';
													$this->FieldOutput .= '<div class="pfuploadcoverimgupl-container"
							                            data-imagesnewlimit="'.$images_newlimit.'" 
							                            data-nonceimgup="'.$nonceimgup.'" 
							                            data-formtype="'.$params['formtype'].'" 
							                            data-mes1="'.esc_html__('Uploading image: ','pointfindercoreelements').'" 
							                            data-mes2="'.esc_html__('Removing image...','pointfindercoreelements').'" 
							                            data-imagesizelimit="'.$setup4_submitpage_imagesizelimit.'" 
							                            data-editid="'.$params['post_id'].'"
							                            >';
													$this->FieldOutput .= '
						                            <label for="file" class="lbl-ui file-input">
							                            <div id="pfcoverimageuploadcontainer">
													        <a id="pfcoverimageuploadfilepicker" href="javascript:;"><i class="fas fa-file-import"></i> '.esc_html__('Choose Header Image','pointfindercoreelements').'</a>
													    </div>
						                            </label>
						                            </div>
													';

													$this->FieldOutput .= '</section>';

													$this->FieldOutput .= '<input type="hidden" name="pfuploadcovimagesrc" id="pfuploadcovimagesrc" value="'.$imagesvalue.'">';

													if($setup4_coviup_req == 1 && $params['formtype'] != 'edititem'){

														if($this->VSOMessages != ''){
															$this->VSOMessages .= ',pfuploadcovimagesrc:"'.esc_html__('Please upload cover image.','pointfindercoreelements').'"';
														}else{
															$this->VSOMessages = 'pfuploadcovimagesrc:"'.esc_html__('Please upload cover image.','pointfindercoreelements').'"';
														}

														if($this->VSORules != ''){
															$this->VSORules .= ',pfuploadcovimagesrc:"required"';
														}else{
															$this->VSORules = 'pfuploadcovimagesrc:"required"';
														}
													}

												/**
												*Old Image Upload
												**/
												$this->FieldOutput .= '</section>';
											}

										/**
										*Cover Image Upload
										**/


										/**
										*Image Upload
										**/
											if ($setup4_submitpage_imageupload == 1) {
												$setup4_submitpage_status_old = $this->PFSAIssetControl('setup4_submitpage_status_old','','0');
												$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-sub-image">'.esc_html__('IMAGE UPLOAD','pointfindercoreelements' ).'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pfitemimgcontainer pferrorcontainer pfsubmit-inner-sub-image">';



												if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9') !== false || $setup4_submitpage_status_old == 1) {
													/**
													*Old Image Upload - if this is an ie9 or 8
													**/
														
														$this->FieldOutput .= '<div class="pfuploadedimages"></div>';
														$pfimageuploadimit = $setup4_submitpage_imagelimit + 1;
														$imagesvalue = '';
														if ($params['formtype'] != 'edititem') {
															$images_count = 0;
															$images_newlimit = $pfimageuploadimit;
														}else{

															$images_of_thispost = get_post_meta($params['post_id'],'webbupointfinder_item_images');
															$featuredimagenum = get_post_thumbnail_id($params['post_id']);

															$images_count = count($images_of_thispost) + 1;
															$images_newlimit = $pfimageuploadimit - $images_count;
														}
														$setup4_submitpage_imagesizelimit = $this->PFSAIssetControl('setup4_submitpage_imagesizelimit','','2');
														$nonceimgup = wp_create_nonce('pfget_imageupload');

														$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';


														$this->FieldOutput .= '<label for="file" class="lbl-text">'.esc_html__('UPLOAD NEW IMAGES','pointfindercoreelements').': ('.esc_html__('MAX','pointfindercoreelements').': '.$pfimageuploadimit.'/<span class="pfmaxtext">'.$images_newlimit.'</span>) '.sprintf(esc_html__('(Allowed: JPG,GIF,PNG / Max. Image Size: %d MB)','pointfindercoreelements'),$setup4_submitpage_imagesizelimit).':</label><small style="margin-bottom:4px;display:block;">'.esc_html__('First image will be main image.','pointfindercoreelements').'</small>';
														$this->FieldOutput .= '<div class="pfuploadfeaturedimg-container"><a id="pfuploadfeaturedimg_remove" style="font-size: 12px;line-height: 14px;"><i class="far fa-times-circle" style="font-size: 14px;"></i> '.esc_html__('Remove Uploaded Images','pointfindercoreelements').'</a></div>';
														$this->FieldOutput .= '<div class="pfuploadfeaturedimgupl-container" 
														data-imagelimit="'.$images_newlimit.'" 
														data-pfimageuploadimit="'.$pfimageuploadimit.'" 
														data-formtype="'.$params['formtype'].'" 
														data-imagesizelimit="'.$setup4_submitpage_imagesizelimit.'" 
														data-nonceimgup="'.$nonceimgup.'" 
														data-mes1="'.esc_html__('Uploading file: ','pointfindercoreelements').'" 
														data-mes2="'.esc_html__('Removing file(s)...','pointfindercoreelements').'" 
														data-editid="'.$params['post_id'].'"
														>';
														$this->FieldOutput .= '
							                            <label for="file" class="lbl-ui file-input">
								                            <div id="pffeaturedimageuploadcontainer">
														        <a id="pffeaturedimageuploadfilepicker" href="javascript:;"><i class="fas fa-file-import"></i> '.esc_html__('Choose Images','pointfindercoreelements').'</a>
														    </div>
							                            </label>
							                            </div>
														';

														$this->FieldOutput .= '</section>';

														$this->FieldOutput .= '<input type="hidden" name="pfuploadimagesrc" id="pfuploadimagesrc" value="'.$imagesvalue.'">';

														if($setup4_submitpage_featuredverror_status == 1 && $params['formtype'] != 'edititem'){

															if($this->VSOMessages != ''){
																$this->VSOMessages .= ',pfuploadimagesrc:"'.esc_html("Please upload minimum one image.","pointfindercoreelements").'"';
															}else{
																$this->VSOMessages = 'pfuploadimagesrc:"'.esc_html("Please upload minimum one image.","pointfindercoreelements").'"';
															}

															if($this->VSORules != ''){
																$this->VSORules .= ',pfuploadimagesrc:"required"';
															}else{
																$this->VSORules = 'pfuploadimagesrc:"required"';
															}
														}

														
													/**
													*Old Image Upload
													**/

												}elseif ($setup4_submitpage_status_old == 0) {

													/**
													*Dropzone Upload
													**/
														$setup42_itempagedetails_configuration = (isset($pointfindertheme_option['setup42_itempagedetails_configuration']))? $pointfindertheme_option['setup42_itempagedetails_configuration'] : array();
														$images_count = 0;
														if($setup4_submitpage_imageupload == 1){

															$images_of_thispost = get_post_meta($params['post_id'],'webbupointfinder_item_images');
															if ($images_of_thispost != false) {
																$images_count = count($images_of_thispost) + 1;
															}

															$this->FieldOutput .= '<div class="pfuploadedimages"></div>';

															/* Validation for upload */
															if ($params['formtype'] != 'edititem' && $setup4_submitpage_featuredverror_status == 1) {
															if($this->VSOMessages != ''){
																$this->VSOMessages .= ',pfuploadimagesrc:"'.esc_html("Please upload minimum one image.","pointfindercoreelements").'"';
															}else{
																$this->VSOMessages = 'pfuploadimagesrc:"'.esc_html("Please upload minimum one image.","pointfindercoreelements").'"';
															}

															if($this->VSORules != ''){
																$this->VSORules .= ',pfuploadimagesrc:"required"';
															}else{
																$this->VSORules = 'pfuploadimagesrc:"required"';
															}
															}
															if ($params['formtype'] != 'edititem') {
																$upload_limited = $setup4_submitpage_imagelimit;
															}else{
																$upload_limited = $setup4_submitpage_imagelimit - $images_count;
															}
															$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';


															$setup4_submitpage_imagesizelimit = $this->PFSAIssetControl('setup4_submitpage_imagesizelimit','','2');/*Image size limit*/

															$this->FieldOutput .= '<div id="pfdropzoneupload" class="dropzone"></div>';
															if ($params['formtype'] != 'edititem') {
															$this->FieldOutput .= '<input type="hidden" class="pfuploadimagesrc" name="pfuploadimagesrc" id="pfuploadimagesrc">';
															}
															$this->FieldOutput .= '
															<script type="text/javascript">
															(function($) {
															"use strict";
																$(function(){
																	';
																	if(!empty($params['post_id'])){
																	$this->FieldOutput .= '$.pfitemdetail_listimages('.$params['post_id'].');';
																	}
																	$this->FieldOutput .= '

																	$.drzoneuploadlimit = '.$upload_limited.';
																	var myDropzone = new Dropzone("div#pfdropzoneupload", {
																		url: theme_scriptspf.ajaxurl,
																		params: {
																	      action: "pfget_imageupload",
																	      security: "'.wp_create_nonce('pfget_imageupload').'",
																	      ';
																	      if ($params['formtype'] == 'edititem') {
																	      	$this->FieldOutput .= ' id:'.$params['post_id'];
																	      }
																		$this->FieldOutput .= '
																	    },
																		autoProcessQueue: true,
																		acceptedFiles:"image/*",
																		maxFilesize: '.$setup4_submitpage_imagesizelimit.',
																		maxFiles: '.$upload_limited.',
																		parallelUploads:1,
																		uploadMultiple: false,
																		';
																	      if ($params['formtype'] != 'edititem') {
																	      	$this->FieldOutput .= 'addRemoveLinks:true,';
																	      }
																		$this->FieldOutput .= '
																		dictDefaultMessage: "'.esc_html__( 'Drop files here to upload!','pointfindercoreelements').'<br/>'.esc_html__( 'You can add up to','pointfindercoreelements').' <div class=\'pfuploaddrzonenum\'>{0}</div> '.esc_html__( 'image(s)','pointfindercoreelements').' '.sprintf(esc_html__('(Max. File Size: %dMB per image)','pointfindercoreelements'),$setup4_submitpage_imagesizelimit).' ".format($.drzoneuploadlimit),
																		dictFallbackMessage: "'.esc_html__( 'Your browser does not support drag and drop file upload', 'pointfindercoreelements' ).'",
																		dictInvalidFileType: "'.esc_html__( 'Unsupported file type', 'pointfindercoreelements' ).'",
																		dictFileTooBig: "'.sprintf(esc_html__( 'File size is too big. (Max file size: %dmb)', 'pointfindercoreelements' ),$setup4_submitpage_imagesizelimit).'",
																		dictCancelUpload: "",
																		dictRemoveFile: "'.esc_html__( 'Remove', 'pointfindercoreelements' ).'",
																		dictMaxFilesExceeded: "'.esc_html__( 'Max file exceeded', 'pointfindercoreelements' ).'",
																		clickable: "#pf-ajax-fileuploadformopen"
																	});

																	Dropzone.autoDiscover = false;

																	var uploadeditems = new Array();

																	myDropzone.on("success", function(file,responseText) {
																		var obj = [];
																		$.each(responseText, function(index, element) {
																			obj[index] = element;
																		});
																		';

																	    if ($params['formtype'] != 'edititem') {
																		    $this->FieldOutput .= '

																			if (obj.process == "up" && obj.id.length != 0) {
																				file._removeLink.id = obj.id;
																				uploadeditems.push(obj.id);
																				$("#pfuploadimagesrc").val(uploadeditems);
																			}
																			';
																		}else{
																			$this->FieldOutput .= '
																				$(".pfuploaddrzonenum").text($.drzoneuploadlimit -1);
																				$.drzoneuploadlimit = $.drzoneuploadlimit -1
																		    	$.pfitemdetail_listimages('.$params['post_id'].');
																		    	myDropzone.options.maxFiles = $.drzoneuploadlimit;
																	      	';
																		}

																	$this->FieldOutput .= '

																	});

																	myDropzone.on("totaluploadprogress",function(uploadProgress,totalBytes,totalBytesSent){

																		if (uploadProgress > 0 ) {
																			$("#pf-ajax-uploaditem-button").val("'.esc_html__( 'Please Wait for Image Upload...', 'pointfindercoreelements' ).'");
																			$("#pf-ajax-uploaditem-button").attr("disabled", true);
																		}
																		if(totalBytes == 0) {
																			$("#pf-ajax-uploaditem-button").attr("disabled", false);
																			$("#pf-ajax-uploaditem-button").val("'.PFSAIssetControl('setup29_dashboard_contents_submit_page_menuname','','').'");
																		}
																	});
																	';
																	if ($params['formtype'] != 'edititem') {
																		$this->FieldOutput .= '
																			myDropzone.on("removedfile", function(file) {
																			    if (file.upload.progress != 0) {
																					if(file._removeLink.id.length != 0){
																						var removeditem = file._removeLink.id;
																						removeditem.replace(\'"\', "");
																						$.ajax({
																						    type: "POST",
																						    dataType: "json",
																						    url: theme_scriptspf.ajaxurl,
																						    data: {
																						        action: "pfget_imageupload",
																				      			security: "'.wp_create_nonce('pfget_imageupload').'",
																				      			iid:removeditem
																						    }
																						});
																						for(var i = uploadeditems.length; i--;) {
																					          if(uploadeditems[i] == removeditem) {
																					              uploadeditems.splice(i, 1);
																					          }
																					      }

																						$("#pfuploadimagesrc").val(uploadeditems);

																						$("#pf-ajax-uploaditem-button").attr("disabled", false);
																						$("#pf-ajax-uploaditem-button").val("'.PFSAIssetControl('setup29_dashboard_contents_submit_page_menuname','','').'");
																					}
																			    }
																			});


																			myDropzone.on("queuecomplete",function(file){
																				$("#pf-ajax-uploaditem-button").attr("disabled", false);
																				$("#pf-ajax-uploaditem-button").val("'.PFSAIssetControl('setup29_dashboard_contents_submit_page_menuname','','').'");
																			});
																		';
																	}else{
																		$this->FieldOutput .= '
																			myDropzone.on("queuecomplete",function(file){
																				myDropzone.removeAllFiles();
																			});

																			myDropzone.on("queuecomplete",function(file){
																				$("#pf-ajax-uploaditem-button").attr("disabled", false);
																				$("#pf-ajax-uploaditem-button").val("'.PFSAIssetControl('setup29_dashboard_contents_submit_page_titlee','','').'");
																			});
																		';
																	}
																$this->FieldOutput .= '
																});

															})(jQuery);
															</script>

															<a id="pf-ajax-fileuploadformopen" class="button pfmyitempagebuttonsex" style="width:100%"><i class="fas fa-file-import"></i> '.esc_html__( 'Click to select photos', 'pointfindercoreelements' ).'</a>
															';
															$this->FieldOutput .= '</section>';
														}
													/**
													*Dropzone Upload
													**/
												}
												$this->FieldOutput .= '</section>';
											}
										/**
										*Image Upload
										**/



										/**
										*File Upload
										**/


											if ($stp4_fupl == 1) {
												$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-sub-file">'.esc_html__('ATTACHMENT UPLOAD','pointfindercoreelements' ).'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner pfitemfilecontainer pferrorcontainer pfsubmit-inner-sub-file">';


												$stp4_Filelimit = $this->PFSAIssetControl("stp4_Filelimit","","10");
												$stp4_Filesizelimit = $this->PFSAIssetControl("stp4_Filesizelimit","","2");

												
												

												$this->FieldOutput .= '<div class="pfuploadedfiles"></div>';

												

												$pffileuploadlimit = $stp4_Filelimit;
												$imagesvalue = '';
												if ($params['formtype'] != 'edititem') {
													$files_count = 0;
													$files_newlimit = $pffileuploadlimit;
												}else{

													$images_of_thispost = get_post_meta($params['post_id'],'webbupointfinder_item_files');

													$files_count = count($images_of_thispost);
													$files_newlimit = $pffileuploadlimit - $files_count;
												}
												$nonceimgup = wp_create_nonce('pfget_fileupload');

												$stp4_allowed = $this->PFSAIssetControl("stp4_allowed","",'jpg,jpeg,gif,png,pdf,rtf,csv,zip, x-zip, x-zip-compressed,rar,doc,docx,docm,dotx,dotm,docb,xls,xlt,xlm,xlsx,xlsm,xltx,xltm,ppt,pot,pps,pptx,pptm');

												$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';


												$this->FieldOutput .= '<label for="file" class="lbl-text">'.esc_html__('UPLOAD NEW ATTACHMENT','pointfindercoreelements').': ('.esc_html__('MAX','pointfindercoreelements').': '.$pffileuploadlimit.'/<span class="pfmaxtext2">'.$files_newlimit.'</span>) '.sprintf(esc_html__('(Allowed: Documents / Max. File Size: %d MB)','pointfindercoreelements'),$stp4_Filesizelimit).':</label>';
												$this->FieldOutput .= '<div class="pfuploadfeaturedfile-container" ><a id="pfuploadfeaturedfile_remove" style="font-size: 12px;line-height: 14px;"><i class="far fa-times-circle" style="font-size: 14px;"></i> '.esc_html__('Remove Uploaded Files','pointfindercoreelements').'</a></div>';
												$this->FieldOutput .= '<div class="pfuploadfeaturedfileupl-container" 
												data-filesizelimit="'.$stp4_Filesizelimit.'" 
												data-filesnewlimit="'.$files_newlimit.'" 
												data-formtype="'.$params['formtype'].'" 
												data-nonceimgup="'.$nonceimgup.'" 
												data-pffileuploadlimit="'.$pffileuploadlimit.'" 
												data-allowed="'.$stp4_allowed.'" 
												data-mes1="'.esc_html__('Uploading file: ','pointfindercoreelements').'" 
												data-mes2="'.esc_html__('Removing file(s)...','pointfindercoreelements').'" 
												data-editid="'.$params['post_id'].'"
												>';
												$this->FieldOutput .= '
					                            <label for="file" class="lbl-ui file-input">
						                            <div id="pffeaturedfileuploadcontainer">
												        <a id="pffeaturedfileuploadfilepicker" href="javascript:;"><i class="fas fa-file-import"></i> '.esc_html__('Choose Files','pointfindercoreelements').'</a>
												    </div>
					                            </label>
					                            </div>
												';

												$this->FieldOutput .= '</section>';

												$this->FieldOutput .= '<input type="hidden" name="pfuploadfilesrc" id="pfuploadfilesrc" value="'.$imagesvalue.'">';

												if($stp4_err_st == 1 && $params['formtype'] != 'edititem'){

													if($this->VSOMessages != ''){
														$this->VSOMessages .= ',pfuploadfilesrc:"'.esc_html__('Please upload an attachment.', 'pointfindercoreelements').'"';
													}else{
														$this->VSOMessages = 'pfuploadfilesrc:"'.esc_html__('Please upload an attachment.', 'pointfindercoreelements').'"';
													}

													if($this->VSORules != ''){
														$this->VSORules .= ',pfuploadfilesrc:"required"';
													}else{
														$this->VSORules = 'pfuploadfilesrc:"required"';
													}
												}

												$this->FieldOutput .= '</section>';

											}
										/**
										*File Upload
										**/





										/**
										*Message to Reviewer
										**/
											if($setup4_submitpage_messagetorev == 1){

												$this->FieldOutput .= '<div class="pfsubmit-title">'.esc_html__('Message to Reviewer','pointfindercoreelements').'</div>';
												$this->FieldOutput .= '<section class="pfsubmit-inner">';
												$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';
												$this->FieldOutput .= '
							                        <label class="lbl-ui">
							                        	<textarea id="item_mesrev" name="item_mesrev" class="textarea mini"></textarea>';
												$this->FieldOutput .= '<b class="tooltip left-bottom"><em>'.esc_html__('OPTIONAL:','pointfindercoreelements').esc_html__('You can send a message to reviewer.','pointfindercoreelements').'</em></b>';

							                    $this->FieldOutput .= '</label>';
							                    $this->FieldOutput .= '</section>';
							                  	$this->FieldOutput .= '</section>';

											}
										/**
										*Message to Reviewer
										**/

										/**
										*Featured Item
										**/
											$featured_permission = true;

											if ($setup4_membersettings_paymentsystem == 2) {
												if ($params['formtype'] == 'edititem') {
													if ($packageinfo['webbupointfinder_mp_fitemnumber'] <= 0) {
														$featured_permission = false;
													}elseif ($membership_user_featureditem_limit <= 0 && $package_featuredcheck != 1) {
														$featured_permission = false;
													}
												}else{
													if ($membership_user_featureditem_limit <= 0) {
														$featured_permission = false;
													}
												}
											}else{
												if ($params['formtype'] == 'edititem') {
													$featured_permission = true;
												}
												if (PFSAIssetControl('setup31_userpayments_featuredoffer','','1') != 1) {
													$featured_permission = false;
												}
											}

											if (class_exists('Pointfinder_Hide_Plans')) {
												$featured_permission = false;
											}

											if (class_exists('Pointfinderspecialreview')) {
												$featured_permission = false;
											}

											if ($featured_permission) {
												if ($setup4_membersettings_paymentsystem != 2) {

													$setup31_userpayments_pricefeatured = $this->PFSAIssetControl('setup31_userpayments_pricefeatured','','5');
													$stp31_daysfeatured = $this->PFSAIssetControl('stp31_daysfeatured','','3');

													if ($stp31_daysfeatured > 1) {
														$featured_day_word = esc_html__(' days','pointfindercoreelements');
													}else{
														$featured_day_word = esc_html__(' day','pointfindercoreelements');
													}

													$featured_price_output = '';
													if ($package_featuredcheck != 1) {
														if ($setup31_userpayments_pricefeatured == 0) {
															$featured_price_output = '<span class="pfitem-featuredprice" title="'.sprintf(esc_html__('For %d %s','pointfindercoreelements' ),$stp31_daysfeatured,$featured_day_word).'">'.$stp31_daysfeatured.$featured_day_word.'</span>';
														}else{

															$setup31_userpayments_pricefeatured_rf = $this->pointfinder_reformat_pricevalue_for_frontend($setup31_userpayments_pricefeatured);

															$featured_price_output = ' <span class="pfitem-featuredprice" title="'.sprintf(esc_html__('Price is %s for %d %s','pointfindercoreelements' ),$setup31_userpayments_pricefeatured,$stp31_daysfeatured,$featured_day_word).'">'.$setup31_userpayments_pricefeatured_rf.' / '.$stp31_daysfeatured.$featured_day_word.'</span>';
														}
													}

													$this->FieldOutput .= '<div class="pfsubmit-title">'.PFSAIssetControl('setup31_userpayments_titlefeatured','','Featured Item').$featured_price_output.'</div>';
													$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-nopadding">';


								                    if($package_featuredcheck == 1 && $current_post_status != 'pendingpayment'){
														$pointfinder_order_expiredate_featured = esc_attr(get_post_meta( $this->PFU_GetOrderID($params['post_id'],1), 'pointfinder_order_expiredate_featured', true ));
														$featured_listing_expiry = $this->PFU_Dateformat($pointfinder_order_expiredate_featured);
														$status_featured_it_text = sprintf(esc_html__('This item is featured until %s','pointfindercoreelements'),'<b>'.$featured_listing_expiry.'</b>');
								                    }else{
								                    	$status_featured_it_text = $this->PFSAIssetControl('setup31_userpayments_textfeatured','','');
								                    }

													$this->FieldOutput .= '
							                            <div class="gspace pfupload-featured-item-box" style="border:0;padding: 12px;">
							                            	<p>
															';
																$pp_status_checked = $pp_status_checked2 = '';

																if ($this->itemrecurringstatus == 1) {
																	$pp_status_checked2 = ' disabled="disabled"';
																}


																if($package_featuredcheck == 1 && $current_post_status != 'pendingpayment'){
																	$this->FieldOutput .='<input type="hidden" name="featureditembox" id="featureditembox">';
																}else{

																	if ($current_post_status == 'pendingpayment') {
																		if ($package_featuredcheck == 1) {
																			$pp_status_checked = ' checked="checked"';
																		}
																	}


																	$this->FieldOutput .='
																	<label class="toggle-switch blue">
																	<input type="checkbox" name="featureditembox" id="featureditembox"'.$pp_status_checked.$pp_status_checked2.'>
																	<label for="featureditembox" data-on="'.esc_html__('YES','pointfindercoreelements').'" data-off="'.esc_html__('NO','pointfindercoreelements').'"></label>
																	</label>';

																}
																$this->FieldOutput .= $status_featured_it_text;
															  $this->FieldOutput .= '
															</p>
							                            </div>';

								                    $this->FieldOutput .= '</section>';
												}else{
													$pf_member_checked_t = '';

													$pf_member_checked = get_post_meta( $params['post_id'], 'webbupointfinder_item_featuredmarker', true );

													if (!empty($pf_member_checked)) {
														$pf_member_checked_t = ' checked';
													}
													$setup31_userpayments_pricefeatured = $this->PFSAIssetControl('setup31_userpayments_pricefeatured','','');

													$this->FieldOutput .= '<div class="pfsubmit-title">'.PFSAIssetControl('setup31_userpayments_titlefeatured','','Featured Item').'</div>';
													$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-nopadding">';


													$this->FieldOutput .= '
							                            <div class="gspace pfupload-featured-item-box" style="border:0;padding: 12px;">
							                            	<p>
															<label class="toggle-switch blue">
																<input type="checkbox" name="featureditembox" id="featureditembox"'.$pf_member_checked_t.'>
																<label for="featureditembox" data-on="'.esc_html__('YES','pointfindercoreelements').'" data-off="'.esc_html__('NO','pointfindercoreelements').'"></label>
															</label>
															 <span>
															   '.PFSAIssetControl('setup31_userpayments_textfeatured','','').'
															  </span>
															</p>


							                            </div>';
							                        $this->FieldOutput .= '</section>';
												}
						                    }
										/**
										*Featured Item
										**/


										/**
										*Select package
										**/
											if (class_exists('Pointfinder_Hide_Plans')) {
												$this->FieldOutput .= '<div class="pflistingtype-selector-main-top pf-select-package clearfix" class="display:none!important"></div>';
											}else{
												if ($setup4_membersettings_paymentsystem == 1) {

													$stp31_up2_pn = $this->PFSAIssetControl('stp31_up2_pn','','Basic Package');
													$setup31_userpayments_priceperitem = $this->PFSAIssetControl('setup31_userpayments_priceperitem','','10');
													$setup31_userpayments_timeperitem = $this->PFSAIssetControl('setup31_userpayments_timeperitem','','10');

													$this->FieldOutput .= '<div class="pfsubmit-title">'.esc_html__('Listing Packages','pointfindercoreelements').'</div>';
													$this->FieldOutput .= '<section class="pfsubmit-inner pf-select-package">';
													$this->FieldOutput .= '<section class="pfsubmit-inner-sub pf-select-package" style="margin-left: -7px;">';

														$this->FieldOutput .= '<div class="pflistingtype-selector-main-top clearfix">';
														/* Add first package - Price/Time/Name */
														$ppp_packages = array();
														$ppp_packages[] = array('id'=>1,'price'=>$setup31_userpayments_priceperitem,'time'=>$setup31_userpayments_timeperitem,'title'=>$stp31_up2_pn);


														$listing_query = new WP_Query(array('post_type' => 'pflistingpacks','posts_per_page' => -1,'order_by'=>'ID','order'=>'ASC'));
														$this_pack_price = $this_pack_info = '';

														$founded_listingpacks = 0;
														$founded_listingpacks = $listing_query->found_posts;

														if ($founded_listingpacks > 0) {
															if ( $listing_query->have_posts() ) {
																$this->FieldOutput .= '<ul>';
																while ( $listing_query->have_posts() ) {
																	$listing_query->the_post();
																	$lp_post_id = get_the_id();

																	$lp_price = get_post_meta( $lp_post_id, 'webbupointfinder_lp_price', true );
																	if (empty($lp_price)) {
																		$lp_price = 0;
																	}

																	$lp_time = get_post_meta( $lp_post_id, 'webbupointfinder_lp_billing_period', true );
																	if (empty($lp_time)) {
																		$lp_time = 0;
																	}

																	$lp_show = get_post_meta( $lp_post_id, 'webbupointfinder_lp_showhide', true );

																	if ($lp_show == 1) {
																		array_push($ppp_packages, array('id'=>$lp_post_id, 'price'=>$lp_price, 'time'=>$lp_time, 'title'=>get_the_title($lp_post_id)));
																	}
																}
																$this->FieldOutput .= '</ul>';
																wp_reset_postdata();
															}
														}

														if ($this->itemrecurringstatus == 1) {
															$status_checked_pack = ' disabled="disabled"';
														}else{
															$status_checked_pack = '';
														}

														$stp31_userfree = $this->PFSAIssetControl("stp31_userfree","","0");

														$status_package_selection = true;



														if ($ppp_packages > 0) {

															foreach ($ppp_packages as $ppp_package) {

																if ($ppp_package['price'] == 0) {
																	$this_pack_price = esc_html__('Free','pointfindercoreelements');
																}else{

																	$this_pack_price = $this->pointfinder_reformat_pricevalue_for_frontend($ppp_package['price']);

																	$this_pack_price = ' <span style="font-weight:600;" title="'.esc_html__('This package price is ','pointfindercoreelements' ).$this_pack_price.'">'.$this_pack_price.'</span>';
																}

																if ($current_post_status == 'publish') {
																	if ($default_package == $ppp_package['id']) {
																		$status_package_selection = true;
																	}else{
																		if ($ppp_package['price'] == 0 && $params['formtype'] == 'edititem' && $stp31_userfree == 0) {
																			$status_package_selection = false;
																		}else{
																			$status_package_selection = true;
																		}
																	}

																}elseif ($current_post_status == 'pendingpayment') {
																	if ($params['formtype'] == 'edititem') {
																		$pointfinder_order_expiredate = esc_attr(get_post_meta( $this->PFU_GetOrderID($params['post_id'],1), 'pointfinder_order_expiredate', true ));
																	}else{
																		$pointfinder_order_expiredate = false;
																	}


																	if ($ppp_package['price'] == 0 && $pointfinder_order_expiredate != false && $params['formtype'] == 'edititem' && $stp31_userfree == 0) {
																		$status_package_selection = false;
																	}else{
																		$status_package_selection = true;
																	}
																}

																if ($ppp_package['price'] == 0 && $default_package != 1) {
																	$status_package_selection = false;
																}
																
																if ($status_package_selection) {
																	$this->FieldOutput .= '<div class="pfpack-selector-main">';
																	$this->FieldOutput .= '<input type="radio" name="pfpackselector" id="pfpackselector'.$ppp_package['id'].'" class="pfpackselector" value="'.$ppp_package['id'].'"'.$status_checked_pack.' '.checked( $default_package, $ppp_package['id'],0).'/>';
																	$this->FieldOutput .= '<label for="pfpackselector'.$ppp_package['id'].'" style="font-weight:600;">
																	<span class="packselector-title">'.$ppp_package['title'].'</span>
																	<span class="packselector-info">'.sprintf(esc_html__("For %s day(s)",'pointfindercoreelements' ),$ppp_package['time']).'</span>
																	<span class="packselector-price">'.$this_pack_price.'</span>
																	</label>';
																	$this->FieldOutput .= '</div>';
																}





															}
														}


														$this->FieldOutput .= '</div>';

								                    $this->FieldOutput .= '</section>';
								                  	$this->FieldOutput .= '</section>';
								                  	$this->PFValidationCheckWrite(1,esc_html__('Please select a package.','pointfindercoreelements' ),'pfpackselector');
								                }
								            }
										/**
										*Select package
										**/


										/**
										*Total Cost
										**/
											if (class_exists('Pointfinder_Hide_Plans')) {
												$this->FieldOutput .= '<div class="pfsubmit-inner-totalcost-output" class="display:none!important"></div>';
											}else{
												if ($setup4_membersettings_paymentsystem == 1) {

													$this->FieldOutput .= '<div class="pfsubmit-title pfsubmit-inner-payment">'.esc_html__('Payment','pointfindercoreelements').'</div>';
													$this->FieldOutput .= '<section class="pfsubmit-inner pfsubmit-inner-payment">';
													$this->FieldOutput .= '<section class="pfsubmit-inner-sub">';

														$this->FieldOutput .= '<div class="pfsubmit-inner-totalcost-output"></div>';

								                    $this->FieldOutput .= '</section>';
								                  	$this->FieldOutput .= '</section>';
								                  	$this->PFValidationCheckWrite(1,esc_html__('Please select a payment type.','pointfindercoreelements' ),'pf_lpacks_payment_selection');
								                }
								            }
										/**
										*Total Cost
										**/


										/**
										*Terms and conditions
										**/
											$setup4_ppp_terms = $this->PFSAIssetControl('setup4_ppp_terms','','1');
											if ($setup4_ppp_terms == 1) {
												if($this->VSOMessages != ''){
													$this->VSOMessages .= ',pftermsofuser:"'.esc_html__( 'You must accept terms and conditions.', 'pointfindercoreelements' ).'"';
												}else{
													$this->VSOMessages = 'pftermsofuser:"'.esc_html__( 'You must accept terms and conditions.', 'pointfindercoreelements' ).'"';
												}

												if($this->VSORules != ''){
													$this->VSORules .= ',pftermsofuser:"required"';
												}else{
													$this->VSORules = 'pftermsofuser:"required"';
												}

												
												$terms_conditions_template = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s ",'_wp_page_template','terms-conditions.php'), ARRAY_A);
												$terms_permalink = '#';
												if(count($terms_conditions_template) > 1){
														foreach ($terms_conditions_template as $terms_conditions_template_single) {
															$terms_permalink = get_permalink($terms_conditions_template_single['post_id']);
														}
												}else{
													if (isset($terms_conditions_template[0]['post_id'])) {
															$terms_permalink = get_permalink($terms_conditions_template[0]['post_id']);
													}
												}


												if ($params['formtype'] == 'edititem') {
													$checktext1 = ' checked=""';
												}else{$checktext1 = '';}
												$pfmenu_perout = $this->PFPermalinkCheck();
												$this->FieldOutput .= '<section>';
												$this->FieldOutput .= '
													<span class="goption upt">
					                                    <label class="options">
					                                        <input type="checkbox" id="pftermsofuser" name="pftermsofuser" value="1"'.$checktext1.'>
					                                        <span class="checkbox"></span>
					                                    </label>
					                                    <label for="check1" class="upt1ch1">'.sprintf(esc_html__( 'I have read the %s terms and conditions %s and accept them.', 'pointfindercoreelements' ),'<a href="'.$terms_permalink.'" class="pftermshortc"><strong>','</strong></a>').'</label>
					                               </span>
												';

								                $this->FieldOutput .= '</section>';

								                $this->ScriptOutput .= '
								                $(".pftermshortc").magnificPopup({
													type: "ajax",
													overflowY: "scroll"
												});
												';
								            }
										/**
										*Terms and conditions
										**/

									$this->FieldOutput .= '</div>';



								/**
								*End : First Column (Map area, Image upload etc..)
								**/
							}


						/**
						*End: New Item Page Content
						**/
						}
						break;

					case 'profile':
						/**
						*Start: Profile Page Content
						**/
								$noncefield = wp_create_nonce('pfget_updateuserprofile');
								$formaction = 'pfget_updateuserprofile';
								$buttonid = 'pf-ajax-profileupdate-button';
								$buttontext = esc_html__('UPDATE INFO','pointfindercoreelements');
								$current_user = get_user_by( 'id', $params['current_user'] );
								$user_id = $current_user->ID;
								$usermetaarr = get_user_meta($user_id);
								$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

								$stp_prf_vat = $this->PFSAIssetControl('stp_prf_vat','','1');
								$stp_prf_country = $this->PFSAIssetControl('stp_prf_country','','1');
								$stp_prf_address = $this->PFSAIssetControl('stp_prf_address','','1');
								$stp_prf_city = $this->PFSAIssetControl('stp_prf_city','','1');
								$usnfield = $this->PFSAIssetControl('usnfield','','1');

								if(!isset($usermetaarr['first_name'])){$usermetaarr['first_name'][0] = '';}
								if(!isset($usermetaarr['last_name'])){$usermetaarr['last_name'][0] = '';}
								if(!isset($usermetaarr['user_phone'])){$usermetaarr['user_phone'][0] = '';}
								if(!isset($usermetaarr['user_mobile'])){$usermetaarr['user_mobile'][0] = '';}
								if(!isset($usermetaarr['description'])){$usermetaarr['description'][0] = '';}
								if(!isset($usermetaarr['nickname'])){$usermetaarr['nickname'][0] = '';}
								if(!isset($usermetaarr['user_twitter'])){$usermetaarr['user_twitter'][0] = '';}
								if(!isset($usermetaarr['user_facebook'])){$usermetaarr['user_facebook'][0] = '';}
								if(!isset($usermetaarr['user_googleplus'])){$usermetaarr['user_googleplus'][0] = '';}
								if(!isset($usermetaarr['user_linkedin'])){$usermetaarr['user_linkedin'][0] = '';}
								if(!isset($usermetaarr['user_vatnumber'])){$usermetaarr['user_vatnumber'][0] = '';}
								if(!isset($usermetaarr['user_country'])){$usermetaarr['user_country'][0] = '';}
								if(!isset($usermetaarr['user_address'])){$usermetaarr['user_address'][0] = '';}
								if(!isset($usermetaarr['user_city'])){$usermetaarr['user_city'][0] = '';}

								if(!isset($usermetaarr['user_photo'])){
									$usermetaarr['user_photo'][0] = '<img src= "'.PFCOREELEMENTSURL.'images/noimg.png">';
								}else{
									if($usermetaarr['user_photo'][0]!= ''){
										$usermetaarr['user_photo'][0] = wp_get_attachment_image( $usermetaarr['user_photo'][0] );
									}else{
										$usermetaarr['user_photo'][0] = '<img src= "'.PFCOREELEMENTSURL.'images/noimg.png" width:"50" height="50">';
									}
								}

								$this->ScriptOutput = "
									$.pfAjaxUserSystemVars4 = {};
									$.pfAjaxUserSystemVars4.email_err = '".esc_html__('Please write an email','pointfindercoreelements')."';
									$.pfAjaxUserSystemVars4.email_err2 = '".esc_html__('Your email address must be in the format of name@domain.com','pointfindercoreelements')."';
									$.pfAjaxUserSystemVars4.nickname_err = '".esc_html__('Please write nickname','pointfindercoreelements')."';
									$.pfAjaxUserSystemVars4.nickname_err2 = '".esc_html__('Please enter at least 3 characters for nickname.','pointfindercoreelements')."';
									$.pfAjaxUserSystemVars4.passwd_err = '".esc_html__('Enter at least 7 characters','pointfindercoreelements')."';
									$.pfAjaxUserSystemVars4.passwd_err2 = '".esc_html__('Enter the same password as above','pointfindercoreelements')."';
								";

								$this->FieldOutput .= '
		                           <div class="col6 first">';

															 if ($usnfield == 1) {$usntext = '';}else{$usntext = 'disabled';}
															 $this->FieldOutput .='
		                           	   <section>
		                                    <label for="username" class="lbl-text"><strong>'.esc_html__('User Name','pointfindercoreelements').'</strong>:</label>
		                                    <label class="lbl-ui">
		                                    	<input type="text" name="username" class="input" value="'.$current_user->user_login.'" '.$usntext.' />
		                                    	<input type="hidden" name="username_old" class="input" value="'.$current_user->user_login.'" />
		                                    </label>
		                               </section>';

																	 $this->FieldOutput .= '
		                               <section>
		                                    <label for="email" class="lbl-text"><strong>'.esc_html__('Email Address','pointfindercoreelements').'(*)</strong>:</label>
		                                    <label class="lbl-ui">
		                                    	<input  type="email" name="email" class="input" value="'.$current_user->user_email.'" />
		                                    </label>
		                                </section>
		                               <section>
		                                    <label for="nickname" class="lbl-text"><strong>'.esc_html__('Nickname (Display Name)','pointfindercoreelements').'(*)</strong>:</label>
		                                    <label class="lbl-ui">
		                                    	<input  type="text" name="nickname" class="input" value="'.$usermetaarr['nickname'][0].'" />
		                                    </label>
		                                </section>
		                               <section>
		                                    <label for="descr" class="lbl-text">'.esc_html__('Biographical Info','pointfindercoreelements').':</label>
		                                    <label class="lbl-ui">
		                                    	<textarea name="descr" class="textarea mini no-resize">'.$usermetaarr['description'][0].'</textarea>
		                                    </label>
		                               </section>
		                               <section>
		                                    <label for="userphoto" class="lbl-text">'.esc_html__('User Photo (Recommend:200px W/H)','pointfindercoreelements').' (.jpg, .png, .gif):</label>
		                                    <div class="col-lg-3">
		                                    <div class="pfuserphoto-container">
		                               		'.$usermetaarr['user_photo'][0].'
		                               		</div>
		                               		</div>
		                                    <div class="col-lg-9">
		                                    <label for="userphoto" class="lbl-ui file-input">
		                                    <input type="file" name="userphoto" />
		                                    <div class="clearfix" style="margin-bottom:10px"></div>
		                                    <span class="goption">
				                                <label class="options">
				                                    <input type="checkbox" name="deletephoto" value="1">
				                                    <span class="checkbox"></span>
				                                </label>
				                                <label for="check1">'.esc_html__('Remove Photo','pointfindercoreelements').'</label>
				                           </span>
		                                    </div>
		                                    </label>
		                                    <div class="clearfix"></div>
		                               </section>
		                               <section>
		                                    <label for="password" class="lbl-text">'.esc_html__('New Password','pointfindercoreelements').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="password" name="password" id="password" class="input" autocomplete="new-password" />
		                                    </label>
		                               </section>
		                               <section>
		                                    <label for="password2" class="lbl-text">'.esc_html__('Repeat New Password','pointfindercoreelements').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="password" name="password2" class="input" autocomplete="new-password" />
		                                    </label>
		                               </section>
		                               <section><small><strong>
		                               		'. esc_html__('Hint:','pointfindercoreelements').'</strong> '. esc_html__('The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ & ).','pointfindercoreelements').'</small>
		                               </section>
		                               ';
		                                if (!empty($stp_prf_address)) {
			                                $this->FieldOutput .= '
			                                 <section>
			                                    <label for="address" class="lbl-text">'.esc_html__('Address','pointfindercoreelements').':</label>
			                                    <label class="lbl-ui">
			                                    	<textarea name="address" class="textarea mini no-resize">'.$usermetaarr['user_address'][0].'</textarea>
			                                    </label>
			                               </section>
			                                ';
		                            	}
		                               $content_profilef = '';
									   $this->FieldOutput .= apply_filters('pointfinder_additional_fields_profilef',$content_profilef,$usermetaarr);
		                               $this->FieldOutput .= '

		                           </div>


		                           <div class="col6 last">
		                           		<section>
		                                    <label for="firstname" class="lbl-text">'.esc_html__('First name','pointfindercoreelements').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="text" name="firstname" class="input" value="'.$usermetaarr['first_name'][0].'" />
		                                    </label>
		                                </section>
		                           		<section>
		                                    <label for="lastname" class="lbl-text">'.esc_html__('Last Name','pointfindercoreelements').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="text" name="lastname" class="input" value="'.$usermetaarr['last_name'][0].'" />
		                                    </label>
		                                </section>
		                           		<section>
		                                    <label for="webaddr" class="lbl-text">'.esc_html__('Website','pointfindercoreelements').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="text" name="webaddr" class="input" value="'.$current_user->user_url.'" />
		                                    </label>
		                                </section>
		                                <section>
		                                    <label for="phone" class="lbl-text">'.esc_html__('Telephone','pointfindercoreelements').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="tel" name="phone" class="input" placeholder="" value="'.$usermetaarr['user_phone'][0].'" />
		                                    </label>
		                                </section>
		                                <section>
		                                    <label for="mobile" class="lbl-text">'.esc_html__('Mobile','pointfindercoreelements').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="tel" name="mobile" class="input" placeholder="" value="'.$usermetaarr['user_mobile'][0].'"/>
		                                    </label>
		                                </section>
		                                <section>
		                                    <label for="twitter" class="lbl-text">'.esc_html__('Twitter','pointfindercoreelements').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="text" name="twitter" class="input" value="'.$usermetaarr['user_twitter'][0].'"/>
		                                    </label>
		                                </section>
		                                <section>
		                                    <label for="facebook" class="lbl-text">'.esc_html__('Facebook','pointfindercoreelements').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="text" name="facebook" class="input" value="'.$usermetaarr['user_facebook'][0].'" />
		                                    </label>
		                                </section>
		                                <section>
		                                    <label for="linkedin" class="lbl-text">'.esc_html__('LinkedIn','pointfindercoreelements').':</label>
		                                    <label class="lbl-ui">
		                                    	<input type="text" name="linkedin" class="input" value="'.$usermetaarr['user_linkedin'][0].'"/>
		                                    </label>
		                                </section>
		                                ';
		                                if (!empty($stp_prf_vat)) {
			                                $this->FieldOutput .= '
			                                 <section>
			                                    <label for="vatnumber" class="lbl-text">'.esc_html__('VAT Number','pointfindercoreelements').':</label>
			                                    <label class="lbl-ui">
			                                    	<input type="text" name="vatnumber" class="input" value="'.$usermetaarr['user_vatnumber'][0].'"/>
			                                    </label>
			                                </section>
			                                ';
		                            	}

		                            	if (!empty($stp_prf_country)) {
			                                $this->FieldOutput .= '
			                                <section>
			                                    <label for="country" class="lbl-text">'.esc_html__('Country','pointfindercoreelements').':</label>
			                                    <label class="lbl-ui">
			                                    	<input type="text" name="country" class="input" value="'.$usermetaarr['user_country'][0].'"/>
			                                    </label>
			                                </section>
			                                ';
		                            	}

		                            	if (!empty($stp_prf_city)) {
			                                $this->FieldOutput .= '
			                                <section>
			                                    <label for="city" class="lbl-text">'.esc_html__('City','pointfindercoreelements').':</label>
			                                    <label class="lbl-ui">
			                                    	<input type="text" name="city" class="input" value="'.$usermetaarr['user_city'][0].'"/>
			                                    </label>
			                                </section>
			                                ';
		                            	}
		                                $this->FieldOutput .= '

		                           </div>

		                            <div class="row"><div class="col12"><div class="pfalign-right">
		                            <section>
								    <label for="city" class="lbl-text" style="color:#c70101">'.esc_html__('Account removal request','pointfindercoreelements').'</label>
									<label class="lbl-ui"><small>
									'.esc_html__("Deleting your account is permanent.
When you delete your account, you won\'t be able to retrieve the content or information you've uploaded. Your Listings and all of your contents will also be deleted. We will proceed your request after an email approval.Please click request button to continue","pointfindercoreelements").'</small><div class="accountremovebuttonres"></div><a href="#" class="button blue accountremovebutton" data-nonce="'.wp_create_nonce( 'pfget_accountremoval' ).'" data-remes="'.esc_html__("Are you sure want to remove your account? (This action cannot rollback.)","pointfindercoreelements").'"><i class="fas fa-times-circle"></i> '.esc_html__("REQUEST ACCOUNT DATA REMOVAL","pointfindercoreelements").'</a></label>
									</section>
									</div></div></div>
					            ';

					            if ($setup4_membersettings_paymentsystem == 2) {
									/*Get user meta*/
									$membership_user_activeorder = get_user_meta( $user_id, 'membership_user_activeorder', true );
									$membership_user_recurring = get_user_meta( $user_id, 'membership_user_recurring', true );
									$recurring_status = esc_attr(get_post_meta( $membership_user_activeorder, 'pointfinder_order_recurring',true));
									if($recurring_status == 1 && $membership_user_recurring == 1){
										$this->FieldOutput .= '
											<div class="row"><div class="col12">
											<hr/>
											<div class="col8 first">
											<section>
			                                    <label for="recurring" class="lbl-text" style="margin-top:12px"><strong>'.esc_html__('Recurring Profile','pointfindercoreelements').'</strong>:</label>
			                                    <label class="lbl-ui">
											<p>'.esc_html__("You are using Paypal Recurring Payments. If want to upgrade your membership plan please cancel this option. Be careful this action can not roll back.",'pointfindercoreelements').'</p></label></section></div>
											<div class="col4 last"><section style="text-align:right;margin-top: 35px;">
			                                    	<a class="pf-dash-cancelrecurring" title="'.esc_html__('This option for cancel recurring payment profile.','pointfindercoreelements').'">'.esc_html__('Cancel Recurring Profile','pointfindercoreelements').'</a></section></div>

			                            	</div></div>';
			                        }
								}
				        /**
						*End: Profile Page Content
						**/
						break;

					case 'myitems':

						/**
						*Start: My Items Page Content
						**/
							$formaction = 'pf_refineitemlist';
							$noncefield = wp_create_nonce($formaction);
							$buttonid = 'pf-ajax-itemrefine-button';
							$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

							if ($params['redirect']) {
								echo '<script>window.location = "'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems'.'";</script>';
							}

							/**
							*Start: Content Area
							**/
								$setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
								$setup3_pointposttype_pt7 = $this->PFSAIssetControl('setup3_pointposttype_pt7','','Listing Types');

								/*User Limits*/
								$setup31_userlimits_useredit = $this->PFSAIssetControl('setup31_userlimits_useredit','','1');
								$setup31_userlimits_userdelete = $this->PFSAIssetControl('setup31_userlimits_userdelete','','1');
								$setup31_userlimits_useredit_pending = $this->PFSAIssetControl('setup31_userlimits_useredit_pending','','1');
								$setup31_userlimits_userdelete_pending = $this->PFSAIssetControl('setup31_userlimits_userdelete_pending','','1');

								$setup4_membersettings_loginregister = $this->PFSAIssetControl('setup4_membersettings_loginregister','','1');
								$setup11_reviewsystem_check = PFREVSIssetControl('setup11_reviewsystem_check','','0');
								$setup31_userpayments_featuredoffer = $this->PFSAIssetControl('setup31_userpayments_featuredoffer','','1');



								$this->FieldOutput .= '<div class="pfmu-itemlisting-container pfmu-itemlisting-container-new">';
									if ($params['fields']!= '') {
										$fieldvars = $params['fields'];
									}else{
										$fieldvars = '';
									}

									$selected_lfs = $selected_lfl = $selected_lfo2 = $selected_lfo = '';

									if ($this->PFControlEmptyArr($fieldvars)) {

			                            if(isset($fieldvars['listing-filter-status'])){
			                           		if ($fieldvars['listing-filter-status'] != '') {
			                           			$selected_lfs = $fieldvars['listing-filter-status'];
			                           		}
			                            }

				                        if(isset($fieldvars['listing-filter-ltype'])){
				                       		if ($fieldvars['listing-filter-ltype'] != '') {
				                       			$selected_lfl = $fieldvars['listing-filter-ltype'];
				                       		}
				                        }

			                            if(isset($fieldvars['listing-filter-orderby'])){
			                           		if ($fieldvars['listing-filter-orderby'] != '') {
			                           			$selected_lfo = $fieldvars['listing-filter-orderby'];
			                           		}
			                            }

			                            if(isset($fieldvars['listing-filter-order'])){
			                           		if ($fieldvars['listing-filter-order'] != '') {
			                           			$selected_lfo2 = $fieldvars['listing-filter-order'];
			                           		}
			                            }

									}

									$current_user = wp_get_current_user();
									$user_id = $current_user->ID;

									$paged = ( esc_sql(get_query_var('paged')) ) ? esc_sql(get_query_var('paged')) : '';
									if (empty($paged)) {
										$paged = ( esc_sql(get_query_var('page')) ) ? esc_sql(get_query_var('page')) : 1;
									}

									$output_args = array(
											'post_type'	=> $setup3_pointposttype_pt1,
											'author' => $user_id,
											'posts_per_page' => 10,
											'paged' => $paged,
											'order'	=> 'DESC',
											'orderby' => 'ID'
										);

									if($selected_lfs != ''){$output_args['post_status'] = $selected_lfs;}
									if($selected_lfo != ''){$output_args['orderby'] = $selected_lfo;}
									if($selected_lfo2 != ''){$output_args['order'] = $selected_lfo2;}
									if($selected_lfl != ''){
										$output_args['tax_query']=
											array(
												'relation' => 'AND',
												array(
													'taxonomy' => 'pointfinderltypes',
													'field' => 'id',
													'terms' => $selected_lfl,
													'operator' => 'IN'
												)
											);
									}



									if($params['post_id'] != ''){
										$output_args['p'] = $params['post_id'];
									}

									$output_loop = new WP_Query( $output_args );

									/**
									*Header for search
									**/

										if($params['sheader'] != 'hide'){

											$this->FieldOutput .= '<section><div class="row"><div class="col1-5 first">';

												$this->FieldOutput .= '<label for="listing-filter-status" class="lbl-ui select">
					                              <select id="listing-filter-status" name="listing-filter-status">';

					                                $this->FieldOutput .= '<option value="">'.esc_html__('Status','pointfindercoreelements').'</option>';
					                                $this->FieldOutput  .= ($selected_lfs == 'publish') ? '<option value="publish" selected>'.esc_html__('Published','pointfindercoreelements').'</option>' : '<option value="publish">'.esc_html__('Published','pointfindercoreelements').'</option>';
					                                $this->FieldOutput  .= ($selected_lfs == 'pendingapproval') ? '<option value="pendingapproval" selected>'.esc_html__('Pending Approval','pointfindercoreelements').'</option>' : '<option value="pendingapproval">'.esc_html__('Pending Approval','pointfindercoreelements').'</option>';
					                                $this->FieldOutput  .= ($selected_lfs == 'pendingpayment') ? '<option value="pendingpayment" selected>'.esc_html__('Pending Payment','pointfindercoreelements').'</option>' : '<option value="pendingpayment">'.esc_html__('Pending Payment','pointfindercoreelements').'</option>';
					                                $this->FieldOutput  .= ($selected_lfs == 'rejected') ? '<option value="rejected" selected>'.esc_html__('Rejected','pointfindercoreelements').'</option>' : '<option value="rejected">'.esc_html__('Rejected','pointfindercoreelements').'</option>';

					                              $this->FieldOutput .= '
					                              </select>
					                            </label>';
					                        $this->FieldOutput .= '</div>';

					                        $this->FieldOutput .= '<div class="col1-5 first">';
					                        	$fieldvalues = get_terms('pointfinderltypes',array('hide_empty'=>false));

												$this->FieldOutput .= '<label for="listing-filter-ltype" class="lbl-ui select">
					                              <select id="listing-filter-ltype" name="listing-filter-ltype">
					                                <option value="">'.$setup3_pointposttype_pt7.'</option>
					                                ';

													foreach( $fieldvalues as $fieldvalue){
														if ($fieldvalue->parent == 0) {

															$this->FieldOutput  .= ($selected_lfl == $fieldvalue->term_id) ? '<option value="'.$fieldvalue->term_id.'" selected>'.$fieldvalue->name.'</option>' : '<option value="'.$fieldvalue->term_id.'">'.$fieldvalue->name.'</option>';

															foreach ($fieldvalues as $subfieldvalue) {
																if ($subfieldvalue->parent == $fieldvalue->term_id) {
																	$this->FieldOutput  .= ($selected_lfl == $subfieldvalue->term_id) ? '<option value="'.$subfieldvalue->term_id.'" selected>- '.$subfieldvalue->name.'</option>' : '<option value="'.$subfieldvalue->term_id.'">- '.$subfieldvalue->name.'</option>';

																	foreach ($fieldvalues as $subsubfieldvalue) {
																		if ($subsubfieldvalue->parent == $subfieldvalue->term_id) {
																			$this->FieldOutput  .= ($selected_lfl == $subsubfieldvalue->term_id) ? '<option value="'.$subsubfieldvalue->term_id.'" selected>-- '.$subsubfieldvalue->name.'</option>' : '<option value="'.$subsubfieldvalue->term_id.'">-- '.$subsubfieldvalue->name.'</option>';
																		}
																	}
																}
															}
														}

													}

					                                $this->FieldOutput .= '
					                              </select>
					                            </label>';
					                        $this->FieldOutput .= '</div>';


					                        $this->FieldOutput .= '<div class="col1-5">';
												$this->FieldOutput .= '<label for="listing-filter-orderby" class="lbl-ui select">
					                              <select id="listing-filter-orderby" name="listing-filter-orderby">';

					                                $reviewspadxspc_output = '';
					                                $reviewspadxspc_output .= '<option value="">'.esc_html__('Order By','pointfindercoreelements').'</option>';
					                                $reviewspadxspc_output  .= ($selected_lfo == 'title') ? '<option value="title" selected>'.esc_html__('Title','pointfindercoreelements').'</option>' : '<option value="title">'.esc_html__('Title','pointfindercoreelements').'</option>';
					                                $reviewspadxspc_output  .= ($selected_lfo == 'date') ? '<option value="date" selected>'.esc_html__('Date','pointfindercoreelements').'</option>' : '<option value="date">'.esc_html__('Date','pointfindercoreelements').'</option>';
					                                $reviewspadxspc_output  .= ($selected_lfo == 'ID') ? '<option value="ID" selected>'.esc_html__('ID','pointfindercoreelements').'</option>' : '<option value="ID">'.esc_html__('ID','pointfindercoreelements').'</option>';
					                                $reviewspadxspc_output .= apply_filters( 'pointfinderspadx_neworder_val', $selected_lfo );
					                                $this->FieldOutput .= $reviewspadxspc_output;


					                              $this->FieldOutput .= '
					                              </select>
					                            </label>';
					                        $this->FieldOutput .= '</div>';

					                        $this->FieldOutput .= '<div class="col1-5">';
												$this->FieldOutput .= '<label for="listing-filter-order" class="lbl-ui select">
					                              <select id="listing-filter-order" name="listing-filter-order">';

					                                $this->FieldOutput .= '<option value="">'.esc_html__('Order','pointfindercoreelements').'</option>';
					                                $this->FieldOutput  .= ($selected_lfo2 == 'ASC') ? '<option value="ASC" selected>'.esc_html__('ASC','pointfindercoreelements').'</option>' : '<option value="ASC">'.esc_html__('ASC','pointfindercoreelements').'</option>';
					                                $this->FieldOutput  .= ($selected_lfo2 == 'DESC') ? '<option value="DESC" selected>'.esc_html__('DESC','pointfindercoreelements').'</option>' : '<option value="DESC">'.esc_html__('DESC','pointfindercoreelements').'</option>';

					                              $this->FieldOutput .= '
					                              </select>
					                            </label>';
					                        $this->FieldOutput .= '</div>';



					                        $this->FieldOutput .= '<div class="col1-5 last">';
												$this->FieldOutput .= '<button type="submit" value="" id="'.$buttonid.'" class="button blue pfmyitempagebuttons" title="'.esc_html__('SEARCH','pointfindercoreelements').'"  ><i class="fas fa-search"></i></button>';
												$this->FieldOutput .= '<a class="button pfmyitempagebuttons" style="margin-left:4px;" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems" title="'.esc_html__('RESET','pointfindercoreelements').'"><i class="fas fa-search-minus"></i></a>';
												$this->FieldOutput .= '<a class="button pfmyitempagebuttons" style="margin-left:4px;" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=newitem" title="'.esc_html__('ADD NEW','pointfindercoreelements').'"><i class="fas fa-plus"></i></a>';
											$this->FieldOutput .= '</div></div></section>';
										}


									if ( $output_loop->have_posts() ) {
										/**
										*Start: Column Headers
										**/
										$setup3_pointposttype_pt7s = $this->PFSAIssetControl('setup3_pointposttype_pt7s','','Listing Type');
										$this->FieldOutput .= '<section>';

										$this->FieldOutput .= '<div class="pfhtitle pf-row clearfix">';
											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfmu-itemlisting-htitlenc col-lg-1 col-md-1 col-sm-1 col-xs-3">';

											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-5 col-md-4 col-sm-4 col-xs-9">';
											$this->FieldOutput .= esc_html__('Information','pointfindercoreelements');
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-2 col-md-2 col-sm-2 hidden-xs">';
											$this->FieldOutput .= $setup3_pointposttype_pt7s;
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-2 col-md-2 col-sm-2 hidden-xs">';
											$this->FieldOutput .= esc_html__('Posted on','pointfindercoreelements');
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle col-lg-2 col-md-3 col-sm-3 hidden-xs">';
											$this->FieldOutput .= '</div>';
										/**
										*End: Column Headers
										**/
										$this->FieldOutput .= '</div>';

										while ( $output_loop->have_posts() ) {
											$output_loop->the_post();

											$author_post_id = get_the_ID();



													/*Post Meta Info*/
													
													if ($setup4_membersettings_paymentsystem == 2) {
														$current_user = wp_get_current_user();
														$user_id = $current_user->ID;
														$result_id = get_user_meta( $user_id, 'membership_user_activeorder', true );
													}else{
														$result_id = $wpdb->get_var( $wpdb->prepare(
															"
																SELECT post_id
																FROM $wpdb->postmeta
																WHERE meta_key = %s and meta_value = %s
															",
															'pointfinder_order_itemid',
															$author_post_id
														) );
													}

													if ($setup4_membersettings_paymentsystem == 2) {
														$pointfinder_order_datetime = $this->PFU_GetPostOrderDate($author_post_id);
													} else {
														$pointfinder_order_datetime = $this->PFU_GetPostOrderDate($result_id);
													}


													$pointfinder_order_datetime = $this->PFU_Dateformat($pointfinder_order_datetime);

													$pointfinder_order_datetime_approval = esc_attr(get_post_meta( $result_id, 'pointfinder_order_datetime_approval', true ));
													$pointfinder_order_pricesign = esc_attr(get_post_meta( $result_id, 'pointfinder_order_pricesign', true ));
													$pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_order_listingtime', true ));
													$pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
													$pointfinder_order_recurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_recurring', true ));
													$pointfinder_order_frecurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_frecurring', true ));
													$pointfinder_order_expiredate = esc_attr(get_post_meta( $result_id, 'pointfinder_order_expiredate', true ));
													$pointfinder_order_bankcheck = esc_attr(get_post_meta( $result_id, 'pointfinder_order_bankcheck', true ));

													$featured_enabled = esc_attr(get_post_meta( $author_post_id, 'webbupointfinder_item_featuredmarker', true ));

													$pointfinder_order_listingtime = ($pointfinder_order_listingtime == '') ? 0 : $pointfinder_order_listingtime ;


													if($pointfinder_order_expiredate != ''){
														$item_listing_expiry = $this->PFU_Dateformat($pointfinder_order_expiredate);
													}else{
														$item_listing_expiry = '';
													}

													$item_recurring_text = ($pointfinder_order_recurring == 1)? '('.esc_html__('Recurring','pointfindercoreelements').')' : '';


													$status_of_post = get_post_status($author_post_id);

													$status_of_order = get_post_status($result_id);



													switch ($status_of_post) {
														case 'pendingpayment':
															if ($status_of_order == 'pfsuspended') {
																$status_text = sprintf(esc_html__('Suspended (Required Paypal Activation)','pointfindercoreelements'));
																$status_payment = 1;
																$status_icon = 'far fa-clock';
																$status_lbl = 'lblpending';
															}else{
																if ($setup4_membersettings_paymentsystem == 2) {
																	$status_text = esc_html__('Suspended','pointfindercoreelements');
																} else {

																	$pf_price_output = $this->pointfinder_reformat_pricevalue_for_frontend($pointfinder_order_price);

																	if ($pointfinder_order_price == 0) {
																		$status_text = sprintf(esc_html__('Pending Payment %s Please edit this item and change plan.','pointfindercoreelements'),'<br/>');
																	}else{
																		$status_text = sprintf(esc_html__('Pending Payment (%s)','pointfindercoreelements'),$pf_price_output);
																	}
																}
																$status_payment = 0;
																$status_icon = 'far fa-clock';
																$status_lbl = 'lblpending';
															}

															break;

														case 'rejected':
															$status_text = esc_html__('Rejected','pointfindercoreelements');
															$status_payment = 1;
															$status_icon = 'far fa-clock';
															$status_lbl = 'lblcancel';
															break;

														case 'pendingapproval':
															$status_text = esc_html__('Pending Approval','pointfindercoreelements');
															$status_payment = 1;
															$status_icon = 'far fa-clock';
															$status_lbl = 'lblpending';
															break;

														case 'publish':
															if ($setup4_membersettings_paymentsystem == 2) {
																$status_text = esc_html__('Active','pointfindercoreelements');
															} else {
																$status_text = sprintf(esc_html__('Active until: %s','pointfindercoreelements'),$item_listing_expiry);
															}
															$status_payment = 1;
															$status_icon = 'far fa-clock';
															$status_lbl = 'lblcompleted';
															break;

														case 'pfonoff':
															/*$status_text = esc_html__('Deactivated by user','pointfindercoreelements');
															$status_lbl = 'lblpending';
															$status_icon = 'far fa-clock';
															$status_payment = 1;*/
															if ($setup4_membersettings_paymentsystem == 2) {
																$status_text = esc_html__('Active','pointfindercoreelements');
															} else {
																$status_text = sprintf(esc_html__('Active until: %s','pointfindercoreelements'),$item_listing_expiry);
															}
															$status_payment = 1;
															$status_icon = 'far fa-clock';
															$status_lbl = 'lblcompleted';
															break;
													}


													/*
														Reviews Store in $review_output:
													*/
														$setup11_reviewsystem_check = PFREVSIssetControl('setup11_reviewsystem_check','','0');
														if ($setup11_reviewsystem_check == 1) {
															global $pfitemreviewsystem_options;
															$setup11_reviewsystem_criterias = $pfitemreviewsystem_options['setup11_reviewsystem_criterias'];
															$review_status = $this->PFControlEmptyArr($setup11_reviewsystem_criterias);

															if($review_status != false){
																$review_output = '';
																$setup11_reviewsystem_singlerev = PFREVSIssetControl('setup11_reviewsystem_singlerev','','0');
																$criteria_number = $this->pf_number_of_rev_criteria();
																$return_results = $this->pfcalculate_total_review($author_post_id);
																if ($return_results['totalresult'] > 0) {

																	$review_output .= '<span class="pfiteminfolist-infotext pfreviews" title="'.esc_html__('Reviews','pointfindercoreelements').'"><i class="fas fa-star-half-alt"></i>';
																		$review_output .=  $return_results['totalresult'].' (<a title="'.esc_html__('Review Total','pointfindercoreelements').'" style="cursor:pointer">'.$this->pfcalculate_total_rusers($author_post_id).'</a>)';
																	$review_output .= '</span>';
																}else{

																	$review_output .= '<span class="pfiteminfolist-infotext pfreviews" title="'.esc_html__('Reviews','pointfindercoreelements').'"><i class="fas fa-star-half-alt"></i>';
																		$review_output .=  '0 (<a title="'.esc_html__('Review Total','pointfindercoreelements').'" style="cursor:pointer">0</a>)';
																	$review_output .= '</span>';
																}
															}
														}else{
															$review_output = '';
														}

													/*
														Favorites Store in $fav_output:
													*/
														$setup4_membersettings_favorites = $this->PFSAIssetControl('setup4_membersettings_favorites','','1');
														if($setup4_membersettings_favorites == 1){
															$fav_number = esc_attr(get_post_meta( $author_post_id, 'webbupointfinder_items_favorites', true ));
															$fav_number = ($fav_number == false) ? '0' : $fav_number ;
															$fav_output = '';
															if ($fav_number > 0) {
																$fav_output .= '<span class="pfiteminfolist-title pfstatus-title pfreviews" title="'.esc_html__('Favorites','pointfindercoreelements').'"><i class="far fa-heart"></i> </span>';
																$fav_output .= '<span class="pfiteminfolist-infotext pfreviews">';
																	$fav_output .=  $fav_number;
																$fav_output .= '</span>';
															}else{
																$fav_output .= '<span class="pfiteminfolist-title pfstatus-title pfreviews" title="'.esc_html__('Favorites','pointfindercoreelements').'"><i class="far fa-heart"></i></span>';
																$fav_output .= '<span class="pfiteminfolist-infotext pfreviews">0</span>';
															}
														}else{
															$fav_output = '';
														}

													/*
														View Count for item.
													*/
														$viewcount_hideshow_f = $this->PFSAIssetControl('viewcount_hideshow_f','',1);

														if($viewcount_hideshow_f == 1){
															$view_count_num = esc_attr(get_post_meta($author_post_id,"webbupointfinder_page_itemvisitcount",true));
															if (!empty($view_count_num)) {
																$view_outputx = $view_count_num;
															}else{
																$view_outputx = 0;
															}
															$view_output = '<span class="pfiteminfolist-title pfstatus-title pfreviews" title="'.esc_html__('Views','pointfindercoreelements').'"><i class="far fa-eye"></i></span>';
															$view_output .= '<span class="pfiteminfolist-infotext pfreviews">'.$view_outputx.'</span>';
														}

													$setup4_membersettings_loginregister = $this->PFSAIssetControl('setup4_membersettings_loginregister','','1');


												$this->FieldOutput .= '<div class="pfmu-itemlisting-inner pfmu-itemlisting-inner'.$author_post_id.' pf-row clearfix pfmylistingpage">';
														if ($status_of_post == 'pfonoff') {
															$addthistextstyle = ' style="display:block"';
														}else{$addthistextstyle = '';}
														$this->FieldOutput .= '<div class="pfmu-itemlisting-inner-overlay pfmu-itemlisting-inner-overlay'.$author_post_id.'"'.$addthistextstyle.'></div>';
														if (get_post_status($author_post_id) == 'publish') {
															$permalink_item = get_permalink($author_post_id);
														}else{
															$permalink_item = '#';
														}

														/*Item Photo Area*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-photo col-lg-1 col-md-1 col-sm-1 col-xs-3">';
															if ( has_post_thumbnail()) {
															   $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(),'full');
															   $this->FieldOutput .= '<a href="' . $large_image_url[0] . '" title="' . the_title_attribute('echo=0') . '" class="pf-mfp-image">';
															   $this->FieldOutput .= '<img src="'.pointfinder_aq_resize($large_image_url[0],60,60,true).'" alt="" />';
															   $this->FieldOutput .= '</a>';
															}else{
															   $this->FieldOutput .= '<a href="#">';
															   $this->FieldOutput .= '<img src="'.PFCOREELEMENTSURL.'images/noimg.png'.'" alt="" />';
															   $this->FieldOutput .= '</a>';
															}
														$this->FieldOutput .= '</div>';



														/* Item Title */
														$this->FieldOutput .= '<div class="col-lg-5 col-md-4 col-sm-4 col-xs-9 pfmu-itemlisting-title-wd">';
														$this->FieldOutput .= '<div class="pfmu-itemlisting-title">';
														$this->FieldOutput .= '<a href="'.$permalink_item.'">'.get_the_title().'</a>';
														$this->FieldOutput .= '</div>';


														/*Status*/
														if (!class_exists('Pointfinder_Hide_Plans')) {
															$this->FieldOutput .= '<div class="pfmu-itemlisting-info pfmu-itemlisting-info-'.$author_post_id.' pffirst" data-deactivatedt="'.esc_html__('Deactivated by user','pointfindercoreelements').'">';
																$this->FieldOutput .= '<ul class="pfiteminfolist">';



																	/** Basic & Featured Listing Setting **/
																	$this->FieldOutput .= '<li>';
																	/*$this->FieldOutput .= '<span class="pfiteminfolist-title pfstatus-title">'.esc_html__('Listing Status','pointfindercoreelements').' '.$item_recurring_text.'  : </span>';*/


																	if($status_payment == 1 && $status_of_post == 'pendingapproval'){
																		$this->FieldOutput .= '<span class="pfiteminfolist-infotext '.$status_lbl.'"><i class="'.$status_icon.'" title="'.esc_html__('This item is waiting for approval. Please be patient while this process goes on.','pointfindercoreelements').'"></i></a>';
																	}else{
																		if (empty($item_listing_expiry) && $status_of_post == 'publish') {
																			$this->FieldOutput .= '<span class="pfiteminfolist-infotext '.$status_lbl.'">';
																		}else{
																			$this->FieldOutput .= '<span class="pfiteminfolist-infotext '.$status_lbl.'"><i class="'.$status_icon.'"></i>';
																		}
																	}
																	if (empty($item_listing_expiry) && $status_of_post == 'publish') {
																		$this->FieldOutput .= '</span>';
																	}else{
																		$this->FieldOutput .= ' '.$status_text.'</span>';
																	}

																	$this->FieldOutput .= '</li>';

																	/** Basic & Featured Listing Setting **/





																$this->FieldOutput .= '</ul>';
															$this->FieldOutput .= '</div>';
														}
														$this->FieldOutput .= '</div>';



														/*Type of item*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast col-lg-2 col-md-2 col-sm-2 hidden-xs">';
															$this->FieldOutput .= '<ul class="pfiteminfolist" style="padding-left:10px">';
																$this->FieldOutput .= '<li><strong>'.get_the_term_list( $author_post_id, 'pointfinderltypes', '<ul class="pointfinderpflistterms"><li>', ',</li><li>', '</li></ul>' ).'</strong></li>';


															$this->FieldOutput .= '</ul>';
														$this->FieldOutput .= '</div>';

														/*Date Creation*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast col-lg-2 col-md-2 col-sm-2 hidden-xs">';
															$this->FieldOutput .= '<ul class="pfiteminfolist" >';
																$this->FieldOutput .= '<li>'.$pointfinder_order_datetime.'</li>';
															$this->FieldOutput .= '</ul>';
														$this->FieldOutput .= '</div>';






														/*Item Footer*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-footer col-lg-2 col-md-3 col-sm-3 col-xs-12">';
													    $this->FieldOutput .= '<ul class="pfmu-userbuttonlist">';

													    if ($this->PF_UserLimit_Check('delete',$status_of_post) == 1 || $status_of_post == 'pfonoff') {
															$this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-delete-item-button wpf-transition-all pf-itemdelete-link" data-pid="'.$author_post_id.'" id="pf-delete-item-'.$author_post_id.'" title="'.esc_html__('Delete','pointfindercoreelements').'"><i class="fas fa-trash-alt"></i></a></li>';
														}

														if($status_of_post == 'publish' || $status_of_post == 'pfonoff'){
															$this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-view-item-button wpf-transition-all" href="'.$permalink_item.'" title="'.esc_html__('View','pointfindercoreelements').'"><i class="far fa-eye"></i></a></li>';
														}

														if (($this->PF_UserLimit_Check('edit',$status_of_post) == 1 && $status_of_order != 'pfsuspended') || ($this->PF_UserLimit_Check('edit',$status_of_post) == 1 && $status_of_post == 'pfonoff')) {

															$show_edit_button = 1;

															if (($setup4_membersettings_paymentsystem == 2 && $status_of_post == 'pendingpayment') || ($setup4_membersettings_paymentsystem == 2 && $status_of_post == 'pfonoff')) {
																$show_edit_button = 0;
															}
															if ($show_edit_button == 1) {
																$this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-edit-item-button wpf-transition-all" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=edititem&i='.$author_post_id.'" title="'.esc_html__('Edit','pointfindercoreelements').'"><i class="far fa-edit"></i></a></li>';
															}

														}


														$this->FieldOutput .= '</ul>';



														$this->FieldOutput .= '</div>';



													$this->FieldOutput .= '</div>';
													$this->FieldOutput .= '<div class="pf-listing-item-inner-addinfo">
													<ul>';
														/** Reviews: show **/
														$content_spadx='';
														$this->FieldOutput .= apply_filters( 'pointfinderspadx_newitemonsubpanel',$content_spadx, $author_post_id );
														$setup4_membersettings_favorites = $this->PFSAIssetControl('setup4_membersettings_favorites','','1');
														if($setup4_membersettings_favorites == 1 && !empty($review_output)){
															$this->FieldOutput .= '<li>';
															$this->FieldOutput .= $review_output;
															$this->FieldOutput .= '</li>';
														}

														/** Favorites: show **/
														$setup11_reviewsystem_check = PFREVSIssetControl('setup11_reviewsystem_check','','0');
														if ($setup11_reviewsystem_check == 1 && !empty($fav_output)) {
															$this->FieldOutput .= '<li>';
															$this->FieldOutput .= $fav_output;
															$this->FieldOutput .= '</li>';
														}

														/** View: show **/
														if ($viewcount_hideshow_f == 1) {
															$this->FieldOutput .= '<li>';
															$this->FieldOutput .= $view_output;
															$this->FieldOutput .= '</li>';
														}


														if ($featured_enabled == 1) {
															$pf_featured_exptime = get_post_meta( $result_id, 'pointfinder_order_expiredate_featured', true );
															if ($pf_featured_exptime != false) {
																$pf_featured_exptime = sprintf(esc_html__('Featured until %s','pointfindercoreelements'),$this->PFU_Dateformat($pf_featured_exptime));
															}else{
																$pf_featured_exptime = esc_html__('Featured','pointfindercoreelements');
															}
															/** Featured: show **/
															$this->FieldOutput .= '<li>';
															$this->FieldOutput .= '<span class="pfiteminfolist-title pfstatus-title pffeaturedbuttondash" title="'.$pf_featured_exptime.'"><i class="fab fa-adversal"></i></span>';
															$this->FieldOutput .= '</li>';
														}

														$is_listing_recurring = get_post_meta($result_id, 'pointfinder_order_recurring', true );
														if ($is_listing_recurring == false) {
															$is_listing_recurring = get_post_meta($result_id, 'pointfinder_order_frecurring', true );
														}
														if ($is_listing_recurring != false) {
															/** Recurring: show **/
															$this->FieldOutput .= '<li>';
															$this->FieldOutput .= '<span class="pfiteminfolist-title pfstatus-title pfrecurringbuttonactive" title="'.esc_html__('Recurring Payment','pointfindercoreelements').'"><i class="fas fa-sync"></i></span>';
															$this->FieldOutput .= '</li>';
														}

														/** on/off: show **/
														$old_post_status = get_post_status($author_post_id);
														if ($old_post_status != 'pfonoff') {
															$onoff_text = 'pfstatusbuttonactive';
															$onoff_word = esc_html__("Your listing is active","pointfindercoreelements" );
														}else{
															$onoff_text = 'pfstatusbuttondeactive';
															$onoff_word = esc_html__("Your listing is deactive","pointfindercoreelements" );
														}
														if (!in_array($status_of_post, array('pendingapproval','pendingpayment','rejected'))) {
															$this->FieldOutput .= '<li>';

															$this->FieldOutput .= '<span data-pfid="'.$author_post_id.'" class="pfiteminfolist-title pfstatus-title '.$onoff_text.' pfstatusbuttonaction" title="'.$onoff_word.'" data-pf-deactive="'.esc_html__("Your listing is deactive","pointfindercoreelements" ).'" data-pf-active="'.esc_html__("Your listing is active","pointfindercoreelements" ).'"><i class="fas fa-power-off"></i></span>';
															$this->FieldOutput .= '</li>';
														}

													$this->FieldOutput .= '
													</ul>
													</div>';

													$this->FieldOutput .='<div class="pf-listing-item-inner-addpinfo clearfix">';

													if ($setup4_membersettings_paymentsystem != 2) {
														$stp31_userfree = $this->PFSAIssetControl("stp31_userfree","","0");


														$pointfinder_order_listingpid = get_post_meta($result_id, "pointfinder_order_listingpid",true);
														$package_price_check = $this->pointfinder_get_package_price_ppp($pointfinder_order_listingpid);

														$ip_process = true;

														if (empty($package_price_check) && !empty($pointfinder_order_expiredate) && $status_of_post == 'pendingpayment' && $stp31_userfree == 0) {
															$ip_process = false;
														}


														if ($ip_process) {
															if ($status_payment == 0 && $pointfinder_order_price != 0) {

												            	$this->FieldOutput .= '<div class="pfmu-payment-area golden-forms pf-row clearfix">';

												            	if($pointfinder_order_bankcheck == 0){
													            		$this->FieldOutput .= '<div class="col-md-9 col-sm-6 col-xs-12">';
														            		$this->FieldOutput .= '<div class="pfcanceltext">';
														            		$this->FieldOutput .= '<div class="notification info" id="pfuaprofileform-notify"><div class="row"><p>'.esc_html__('Payment not completed. Please choose a payment method to complete the payment. ','pointfindercoreelements');
														            			
														            			if($pointfinder_order_recurring == 1){
														            				$this->FieldOutput .= esc_html__('Recurring payments, do not support BANK TRANSFER & CREDIT CARD PAYMENTS.','pointfindercoreelements');
														            			}

														            		$this->FieldOutput .='</p></div></div>';
															            	$this->FieldOutput .= '</div>';
														            	$this->FieldOutput .= '</div>';

														            	$this->FieldOutput .= '<div class="col-md-2 col-sm-6 col-xs-12">';
														            		$this->FieldOutput .= '<div class="pfmylistingspmselect">';
																                $this->FieldOutput .= '<label class="lbl-ui select">';

																		        	$this->FieldOutput .= '<select name="paymenttype">';
																		        		if ($this->PFSAIssetControl('setup20_paypalsettings_paypal_status','','1') == 1) {
																		        			if ($pointfinder_order_recurring == 1 || $pointfinder_order_frecurring == 1) {
						        																$this->FieldOutput .= '<option value="paypal">'.esc_html__('PAYPAL REC.','pointfindercoreelements').'</option>';
						        															}else{
						        																$this->FieldOutput .= '<option value="paypal">'.esc_html__('PAYPAL','pointfindercoreelements').'</option>';
						        															}

																			       		}

																			       		if (($pointfinder_order_recurring != 1 && $pointfinder_order_frecurring != 1) && PFSAIssetControl('setup20_stripesettings_status','','0') == 1) {
																			       			$this->FieldOutput .= '<option value="creditcard">'.esc_html__('CREDIT CARD','pointfindercoreelements').'</option>';
																			       		}

																			       		if (($pointfinder_order_recurring != 1 && $pointfinder_order_frecurring != 1) && PFPGIssetControl('pags_status','',0) == 1) {
																			       			$this->FieldOutput .= '<option value="pags">'.esc_html__('PAGSEGURO','pointfindercoreelements').'</option>';
																			       		}

																			       		if (($pointfinder_order_recurring != 1 && $pointfinder_order_frecurring != 1) && PFPGIssetControl('payu_status','',0) == 1) {
																			       			$this->FieldOutput .= '<option value="payu">'.esc_html__('PAYUMONEY','pointfindercoreelements').'</option>';
																			       		}

																			       		if (($pointfinder_order_recurring != 1 && $pointfinder_order_frecurring != 1) && PFPGIssetControl('ideal_status','',0) == 1) {
																			       			$this->FieldOutput .= '<option value="ideal">'.esc_html__('iDeal','pointfindercoreelements').'</option>';
																			       		}

																			       		if (($pointfinder_order_recurring != 1 && $pointfinder_order_frecurring != 1) && PFPGIssetControl('robo_status','',0) == 1) {
																			       			$this->FieldOutput .= '<option value="robo">'.esc_html__('Robokassa','pointfindercoreelements').'</option>';
																			       		}

																			       		if (($pointfinder_order_recurring != 1 && $pointfinder_order_frecurring != 1) && PFPGIssetControl('2cho_status','',0) == 1) {
																			       			$this->FieldOutput .= '<option value="t2co">'.esc_html__('2CHECKOUT','pointfindercoreelements').'</option>';
																			       		}

																			       		if (($pointfinder_order_recurring != 1 && $pointfinder_order_frecurring != 1) && PFPGIssetControl('payf_status','',0) == 1) {
																			       			$this->FieldOutput .= '<option value="payf">'.esc_html__('PAYFAST','pointfindercoreelements').'</option>';
																			       		}

																			       		if (($pointfinder_order_recurring != 1 && $pointfinder_order_frecurring != 1) && PFPGIssetControl('iyzico_status','',0) == 1) {
																			       			$this->FieldOutput .= '<option value="iyzico">'.esc_html__('Iyzico','pointfindercoreelements').'</option>';
																			       		}

																			       		if(($pointfinder_order_recurring != 1 && $pointfinder_order_frecurring != 1) && PFSAIssetControl('setup20_paypalsettings_bankdeposit_status','',0) == 1){
																			       			$this->FieldOutput .= '<option value="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_pay2&i='.$author_post_id.'">'.esc_html__('BANK TRANS.','pointfindercoreelements').'</option>';
																			       		}

																			        $this->FieldOutput .= '</select>';

																		        $this->FieldOutput .= '</label>';
																	        $this->FieldOutput .= '</div>';
																	    $this->FieldOutput .= '</div>';

																	    $this->FieldOutput .= '<div class="col-md-1 col-sm-6 col-xs-12">';
																	        $this->FieldOutput .= '<div class="pfmylistingspmbutton">';
														            			$this->FieldOutput .= '<a class="button buttonpaymentb pfbuttonpaymentb" data-pfitemnum="'.$author_post_id.'" title="'.esc_html__('Click for Payment','pointfindercoreelements').'">'.esc_html__('PAY','pointfindercoreelements').'</a>';
														            		$this->FieldOutput .= '</div>';
														            	$this->FieldOutput .= '</div>';
													            }else{
													            	$this->FieldOutput .= '<div class="col-md-9 col-sm-8 col-xs-12">';
													            		$this->FieldOutput .= '<div class="pfcanceltext">';
													            		$this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify"><div class="row"><p>'.esc_html__('Waiting Bank Transfer, but you can cancel this transfer and make payment with another payment method.','pointfindercoreelements').'</p></div></div>';
													            	$this->FieldOutput .= '</div>';
													            	$this->FieldOutput .= '</div>';

													            	$this->FieldOutput .= '<div class="col-md-3 col-sm-4 col-xs-12">';
													            		$this->FieldOutput .= '<a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_pay2c&i='.$author_post_id.'" class="pfcancelbtransferbutton button"><i class="fas fa-times-circle"></i> '.esc_html__('CANCEL TRANSFER','pointfindercoreelements').'</a>';
													            	$this->FieldOutput .= '</div>';
													            }

													            $this->FieldOutput .= '</div>';

												        	}elseif ($status_payment == 0 && $pointfinder_order_price == 0) {// && $stp31_userfree == 1
												        		/*If user is free user then extend it free.*/

												            	$this->FieldOutput .= '<div class="col-md-10 col-sm-9 col-xs-12">';
												            		$this->FieldOutput .= '<div class="pfcanceltext">';
												            		$this->FieldOutput .= '<div class="notification error" id="pfuaprofileform-notify"><div class="row"><p>'.esc_html__('The listing has expired. Please click to "RENEW" button to extend the listing expire date.','pointfindercoreelements').'</p></div></div>';
												            	$this->FieldOutput .= '</div>';
												            	$this->FieldOutput .= '</div>';

												            	$this->FieldOutput .= '<div class="col-md-2 col-sm-3 col-xs-12">';
												            		$this->FieldOutput .= '<a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_extend&i='.$author_post_id.'" class="button buttonrenewpf" title="'.esc_html__('Click for renew (Extend)','pointfindercoreelements').'"><i class="fas fa-sync"></i> '.esc_html__('RENEW','pointfindercoreelements').'</a>';
												            	$this->FieldOutput .= '</div>';
												        	}
														}else{
															$this->FieldOutput .= '<div class="col-lg-12">';
											            		$this->FieldOutput .= '<div class="pfcanceltext">';
											            		$this->FieldOutput .= '<div class="notification error" id="pfuaprofileform-notify"><div class="row"><p>'.esc_html__('The listing has expired. Please edit the listing and order a plan to extend the expiry time.','pointfindercoreelements');

											            		$this->FieldOutput .='</p></div></div>';
												            	$this->FieldOutput .= '</div>';
											            	$this->FieldOutput .= '</div>';
														}


											        }
												    $this->FieldOutput .= '</div>';

										}

										$this->FieldOutput .= '</section>';
									}else{
										$this->FieldOutput .= '<section>';
										$this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p><i class="fas fa-info-circle"></i> ';
										if ($this->PFControlEmptyArr($fieldvars)) {
											$this->FieldOutput .= '<strong>'.wp_sprintf(esc_html__("We couldn't find a listing record.",'pointfindercoreelements').'</strong><br>'.esc_html__('Please refine your search criteria and try to check again. Or you can press %s button to see all listings.','pointfindercoreelements'),'<i class="fas fa-search-minus"></i>').'</p></div>';
										}else{
											$this->FieldOutput .= '<strong>'.esc_html__("We couldn't find a listing record.",'pointfindercoreelements').'</strong><br>'.esc_html__('If you saw this error first time please upload a new listing to see on this page.','pointfindercoreelements').'</p></div>';
										}
										$this->FieldOutput .= '</section>';
									}
									$this->FieldOutput .= '<div class="pfstatic_paginate" >';
									$big = 999999999;
									$this->FieldOutput .= paginate_links(array(
										'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
										'format' => '?paged=%#%',
										'current' => max(1, $paged),
										'total' => $output_loop->max_num_pages,
										'type' => 'list',
									));
									$this->FieldOutput .= '</div>';
									wp_reset_postdata();

								$this->FieldOutput .= '</div>';

							/**
							*End: Content Area
							**/
						/**
						*End: My Items Page Content
						**/
						break;

					case 'errorview':
						break;

					case 'banktransfer':
						/**
						*Start: Bank Transfer Page Content
						**/
							$this->FieldOutput .= '<div class="pf-banktransfer-window">';

								$this->FieldOutput .= '<span class="pf-orderid-text">';
								$this->FieldOutput .= esc_html__('Your Order ID:','pointfindercoreelements').' '.$params['post_id'];
								$this->FieldOutput .= '</span>';

								$this->FieldOutput .= '<span class="pf-order-text">';
								
								$setup20_bankdepositsettings_text = ($pointfindertheme_option['setup20_bankdepositsettings_text'])? wp_kses_post($pointfindertheme_option['setup20_bankdepositsettings_text']):'';
								$this->FieldOutput .= $setup20_bankdepositsettings_text;
								$this->FieldOutput .= '</span>';

							$this->FieldOutput .= '</div>';

						/**
						*End: Bank Transfer Page Content
						**/
						break;

					case 'favorites':
						$formaction = 'pf_refinefavlist';
						$noncefield = wp_create_nonce($formaction);
						$buttonid = 'pf-ajax-itemrefine-button';

						/**
						*Start: Favorites Page Content
						**/

							$user_favorites_arr = get_user_meta( $params['current_user'], 'user_favorites', true );

							if (!empty($user_favorites_arr)) {
								$user_favorites_arr = json_decode($user_favorites_arr,true);
							}else{
								$user_favorites_arr = array();
							}


							$output_arr = '';
							$countarr = count($user_favorites_arr);

							if($countarr>0){

								$this->FieldOutput .= '<div class="pfmu-itemlisting-container">';

									if ($params['fields']!= '') {
										$fieldvars = $params['fields'];
									}else{
										$fieldvars = '';
									}

									$selected_lfs = $selected_lfl = $selected_lfo2 = $selected_lfo = '';

									$paged = ( esc_sql(get_query_var('paged')) ) ? esc_sql(get_query_var('paged')) : '';
									if (empty($paged)) {
										$paged = ( esc_sql(get_query_var('page')) ) ? esc_sql(get_query_var('page')) : 1;
									}

									$setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
									$setup3_pointposttype_pt7 = $this->PFSAIssetControl('setup3_pointposttype_pt7','','Listing Types');

									if ($this->PFControlEmptyArr($fieldvars)) {

				                        if(isset($fieldvars['listing-filter-ltype'])){
				                       		if ($fieldvars['listing-filter-ltype'] != '') {
				                       			$selected_lfl = $fieldvars['listing-filter-ltype'];
				                       		}
				                        }

			                            if(isset($fieldvars['listing-filter-orderby'])){
			                           		if ($fieldvars['listing-filter-orderby'] != '') {
			                           			$selected_lfo = $fieldvars['listing-filter-orderby'];
			                           		}
			                            }

			                            if(isset($fieldvars['listing-filter-order'])){
			                           		if ($fieldvars['listing-filter-order'] != '') {
			                           			$selected_lfo2 = $fieldvars['listing-filter-order'];
			                           		}
			                            }

									}

									$user_id = $params['current_user'];


									$output_args = array(
											'post_type'	=> $setup3_pointposttype_pt1,
											'posts_per_page' => 10,
											'paged' => $paged,
											'order'	=> 'ASC',
											'orderby' => 'Title',
											'post__in' => $user_favorites_arr
									);

									if($selected_lfs != ''){$output_args['post_status'] = $selected_lfs;}
									if($selected_lfo != ''){$output_args['orderby'] = $selected_lfo;}
									if($selected_lfo2 != ''){$output_args['order'] = $selected_lfo2;}
									if($selected_lfl != ''){
										$output_args['tax_query']=
											array(
												'relation' => 'AND',
												array(
													'taxonomy' => 'pointfinderltypes',
													'field' => 'id',
													'terms' => $selected_lfl,
													'operator' => 'IN'
												)
											);
									}



									if($params['post_id'] != ''){
										$output_args['p'] = $params['post_id'];
									}

									$output_loop = new WP_Query( $output_args );

									/**
									*START: Header for search
									**/

										if($params['sheader'] != 'hide'){

											$this->FieldOutput .= '<section><div class="row">';


					                        $this->FieldOutput .= '<div class="col3 first">';
												$this->FieldOutput .= '<label for="listing-filter-ltype" class="lbl-ui select">
					                              <select id="listing-filter-ltype" name="listing-filter-ltype">
					                                <option value="">'.$setup3_pointposttype_pt7.'</option>
					                                ';

					                                $fieldvalues = get_terms('pointfinderltypes',array('hide_empty'=>false));
													foreach( $fieldvalues as $fieldvalue){
														if ($fieldvalue->parent == 0) {

															$this->FieldOutput  .= ($selected_lfl == $fieldvalue->term_id) ? '<option value="'.$fieldvalue->term_id.'" selected>'.$fieldvalue->name.'</option>' : '<option value="'.$fieldvalue->term_id.'">'.$fieldvalue->name.'</option>';

															foreach ($fieldvalues as $subfieldvalue) {
																if ($subfieldvalue->parent == $fieldvalue->term_id) {
																	$this->FieldOutput  .= ($selected_lfl == $subfieldvalue->term_id) ? '<option value="'.$subfieldvalue->term_id.'" selected>- '.$subfieldvalue->name.'</option>' : '<option value="'.$subfieldvalue->term_id.'">- '.$subfieldvalue->name.'</option>';

																	foreach ($fieldvalues as $subsubfieldvalue) {
																		if ($subsubfieldvalue->parent == $subfieldvalue->term_id) {
																			$this->FieldOutput  .= ($selected_lfl == $subsubfieldvalue->term_id) ? '<option value="'.$subsubfieldvalue->term_id.'" selected>-- '.$subsubfieldvalue->name.'</option>' : '<option value="'.$subsubfieldvalue->term_id.'">-- '.$subsubfieldvalue->name.'</option>';
																		}
																	}
																}
															}
														}

													}

					                                $this->FieldOutput .= '
					                              </select>
					                            </label>';
					                        $this->FieldOutput .= '</div>';


					                        $this->FieldOutput .= '<div class="col3">';
												$this->FieldOutput .= '<label for="listing-filter-orderby" class="lbl-ui select">
					                              <select id="listing-filter-orderby" name="listing-filter-orderby">';

					                                $this->FieldOutput .= '<option value="">'.esc_html__('Order By','pointfindercoreelements').'</option>';
					                                $this->FieldOutput  .= ($selected_lfo == 'title') ? '<option value="title" selected>'.esc_html__('Title','pointfindercoreelements').'</option>' : '<option value="title">'.esc_html__('Title','pointfindercoreelements').'</option>';
					                                $this->FieldOutput  .= ($selected_lfo == 'date') ? '<option value="date" selected>'.esc_html__('Date','pointfindercoreelements').'</option>' : '<option value="date">'.esc_html__('Date','pointfindercoreelements').'</option>';
					                                $this->FieldOutput  .= ($selected_lfo == 'ID') ? '<option value="ID" selected>'.esc_html__('ID','pointfindercoreelements').'</option>' : '<option value="ID">'.esc_html__('ID','pointfindercoreelements').'</option>';


					                              $this->FieldOutput .= '
					                              </select>
					                            </label>';
					                        $this->FieldOutput .= '</div>';

					                        $this->FieldOutput .= '<div class="col3">';
												$this->FieldOutput .= '<label for="listing-filter-order" class="lbl-ui select">
					                              <select id="listing-filter-order" name="listing-filter-order">';

					                                $this->FieldOutput .= '<option value="">'.esc_html__('Order','pointfindercoreelements').'</option>';
					                                $this->FieldOutput  .= ($selected_lfo2 == 'ASC') ? '<option value="ASC" selected>'.esc_html__('ASC','pointfindercoreelements').'</option>' : '<option value="ASC">'.esc_html__('ASC','pointfindercoreelements').'</option>';
					                                $this->FieldOutput  .= ($selected_lfo2 == 'DESC') ? '<option value="DESC" selected>'.esc_html__('DESC','pointfindercoreelements').'</option>' : '<option value="DESC">'.esc_html__('DESC','pointfindercoreelements').'</option>';

					                              $this->FieldOutput .= '
					                              </select>
					                            </label>';
					                        $this->FieldOutput .= '</div>';



					                        $this->FieldOutput .= '<div class="col3 last">';
												$this->FieldOutput .= '<button type="submit" value="" id="'.$buttonid.'" class="button blue pfmyitempagebuttons" title="'.esc_html__('SEARCH','pointfindercoreelements').'" ><i class="fas fa-search"></i></button>';
												$this->FieldOutput .= '<a class="button pfmyitempagebuttons" style="margin-left:4px;" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=favorites" title="'.esc_html__('RESET','pointfindercoreelements').'"><i class="fas fa-search-minus"></i></a>';
											$this->FieldOutput .= '</div></div></section>';
										}

									/**
									*END: Header for search
									**/

									if ( $output_loop->have_posts() ) {

										$this->FieldOutput .= '<section>';
										$this->FieldOutput .= '<div class="pfhtitle pf-row clearfix">';

										$setup3_pointposttype_pt4 = $this->PFSAIssetControl('setup3_pointposttype_pt4s','','Item Type');
										$setup3_pointposttype_pt4_check = $this->PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
										$setup3_pointposttype_pt5 = $this->PFSAIssetControl('setup3_pointposttype_pt5s','','Location');
										$setup3_pointposttype_pt5_check = $this->PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
										$setup3_pointposttype_pt7s = $this->PFSAIssetControl('setup3_pointposttype_pt7s','','Listing Type');
										/**
										*Start: Column Headers
										**/
											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfmu-itemlisting-htitlenc col-lg-1 col-md-1 col-sm-1 hidden-xs">';
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-4 col-md-4 col-sm-4 col-xs-5">';
											$this->FieldOutput .= esc_html__('Title','pointfindercoreelements');
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-3 col-md-3 col-sm-3 col-xs-4">';
											$this->FieldOutput .= esc_html__('Type','pointfindercoreelements');
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-2 col-md-2 col-sm-2 hidden-xs">';

												if($setup3_pointposttype_pt5_check == 1){
													$this->FieldOutput .= $setup3_pointposttype_pt5;
												}else{
													if($setup3_pointposttype_pt4_check == 1){
														$this->FieldOutput .= $setup3_pointposttype_pt4;
													}
												}

											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfmu-itemlisting-htitlenc col-lg-2 col-md-2 col-sm-2 col-xs-3">';
											$this->FieldOutput .= '</div>';
										/**
										*End: Column Headers
										**/

										$this->FieldOutput .= '</div>';

										$setup22_searchresults_hide_lt  = $this->PFSAIssetControl('setup22_searchresults_hide_lt','','0');

										while ( $output_loop->have_posts() ) {
											$output_loop->the_post();

											$author_post_id = get_the_ID();

												$this->FieldOutput .= '<div class="pfmu-itemlisting-inner pf-row clearfix pffavoritespage">';

														$permalink_item = get_permalink($author_post_id);


														/*Item Photo Area*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-photo col-lg-1 col-md-1 col-sm-1 hidden-xs">';
															if ( has_post_thumbnail()) {
															   $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(),'full');
															   $this->FieldOutput .= '<a href="' . $large_image_url[0] . '" title="' . the_title_attribute('echo=0') . '" class="pf-mfp-image">';
															   $this->FieldOutput .= '<img src="'.pointfinder_aq_resize($large_image_url[0],60,60,true).'" alt="" />';
															   $this->FieldOutput .= '</a>';
															}else{
															   $this->FieldOutput .= '<a href="#" style="border:1px solid #efefef">';
															   $this->FieldOutput .= '<img src="'.PFCOREELEMENTSURL.'images/noimg.png'.'" alt="" />';
															   $this->FieldOutput .= '</a>';
															}
														$this->FieldOutput .= '</div>';

														$title_of_item = get_the_title();

														/* Item Title */
														$this->FieldOutput .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-5 pfmu-itemlisting-title-wd">';
														$this->FieldOutput .= '<div class="pfmu-itemlisting-title">';
														$this->FieldOutput .= '<a href="'.$permalink_item.'" class="hidden-xs">'.mb_strimwidth($title_of_item, 0, 38, "...").'</a>';
														$this->FieldOutput .= '<a href="'.$permalink_item.'" class="visible-xs">'.mb_strimwidth($title_of_item, 0, 14, "...").'</a>';
														$this->FieldOutput .= '</div>';


														/*Other Infos*/
														$output_data = $this->PFIF_DetailText_ld($author_post_id,$setup22_searchresults_hide_lt);
														$rl_pfind = '/pflistingitem-subelement pf-price/';
														$rl_pfind2 = '/pflistingitem-subelement pf-onlyitem/';
					                                    $rl_preplace = 'pf-fav-listing-price';
					                                    $rl_preplace2 = 'pf-fav-listing-item';
					                                    $mcontent = preg_replace( $rl_pfind, $rl_preplace, $output_data);
					                                    $mcontent = preg_replace( $rl_pfind2, $rl_preplace2, $mcontent );

					                                    if (isset($mcontent['content'])) {
					                                    	$this->FieldOutput .= '<div class="pfmu-itemlisting-info pffirst">';
						                                    $this->FieldOutput .= $mcontent['content'];
															$this->FieldOutput .= '</div>';
					                                    }

					                                    if (isset($mcontent['priceval'])) {
					                                    	$this->FieldOutput .= '<div class="pfmu-itemlisting-info pffirst">';
						                                    $this->FieldOutput .= $mcontent['priceval'];
															$this->FieldOutput .= '</div>';
					                                    }

					                                    $this->FieldOutput .= '</div>';




														/*Type of item*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast col-lg-3 col-md-3 col-sm-3 col-xs-4">';
															$this->FieldOutput .= '<ul class="pfiteminfolist" style="padding-left:10px">';
																$this->FieldOutput .= '<li class="hidden-xs">'.$this->GetPFTermName($author_post_id, 'pointfinderltypes').'</li>';
																	$this->FieldOutput .= '<li class="visible-xs">'.mb_strimwidth($this->GetPFTermName($author_post_id, 'pointfinderltypes'), 0, 8, "...").'</li>';
															$this->FieldOutput .= '</ul>';
														$this->FieldOutput .= '</div>';

														/*Location*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast col-lg-2 col-md-2 col-sm-2 hidden-xs">';
															$this->FieldOutput .= '<ul class="pfiteminfolist" style="padding-left:10px">';
																if($setup3_pointposttype_pt5_check == 1){
																	$this->FieldOutput .= '<li class="hidden-xs">'.$this->GetPFTermName($author_post_id, 'pointfinderlocations').'</li>';
																}else{
																	if($setup3_pointposttype_pt4_check == 1){
																		$this->FieldOutput .= '<li class="hidden-xs">'.$this->GetPFTermName($author_post_id, 'pointfinderitypes').'</li>';
																	}
																}
																if($setup3_pointposttype_pt5_check == 1){
																	$this->FieldOutput .= '<li class="visible-xs">'.mb_strimwidth($this->GetPFTermName($author_post_id, 'pointfinderlocations'), 0, 8, "...").'</li>';
																}else{
																	if($setup3_pointposttype_pt4_check == 1){
																		$this->FieldOutput .= '<li class="visible-xs">'.mb_strimwidth($this->GetPFTermName($author_post_id, 'pointfinderitypes'), 0, 8, "...").'</li>';
																	}
																}
															$this->FieldOutput .= '</ul>';
														$this->FieldOutput .= '</div>';






														/*Item Footer*/


														$fav_check = 'true';
														$favtitle_text = esc_html__('Remove from Favorites','pointfindercoreelements');



														$this->FieldOutput .= '<div class="pfmu-itemlisting-footer col-lg-2 col-md-2 col-sm-2 col-xs-3">';
													    $this->FieldOutput .= '<ul class="pfmu-userbuttonlist">';
															$this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-delete-item-button wpf-transition-all pf-favorites-link" data-pf-num="'.$author_post_id.'" data-pf-active="'.$fav_check.'" data-pf-item="false" title="'.$favtitle_text.'"><i class="fas fa-trash-alt"></i></a></li>';
															$this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-view-item-button wpf-transition-all" href="'.$permalink_item.'" title="'.esc_html__('View','pointfindercoreelements').'"><i class="far fa-eye"></i></a></li>';
														$this->FieldOutput .= '</ul>';

														$this->FieldOutput .= '</div>';


													$this->FieldOutput .= '</div>';


										}

										$this->FieldOutput .= '</section>';
									}else{
										$this->FieldOutput .= '<section>';
										$this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p><i class="fas fa-info-circle"></i> ';
										if ($this->PFControlEmptyArr($fieldvars)) {
											$this->FieldOutput .= '<strong>'.wp_sprintf(esc_html__("We couldn't find a favorite record.",'pointfindercoreelements').'</strong><br>'.esc_html__('Please refine your search criteria and try to check again. Or you can press %s button to see all.','pointfindercoreelements'),'<i class="fas fa-search-minus"></i>').'</p></div>';
										}else{
											$this->FieldOutput .= '<strong>'.esc_html__("We couldn't find a favorite record.",'pointfindercoreelements').'</strong></p></div>';
										}
										$this->FieldOutput .= '</section>';
									}
									$this->FieldOutput .= '<div class="pfstatic_paginate" >';
									$big = 999999999;
									$this->FieldOutput .= paginate_links(array(
										'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
										'format' => '?paged=%#%',
										'current' => max(1, $paged),
										'total' => $output_loop->max_num_pages,
										'type' => 'list',
									));
									$this->FieldOutput .= '</div>';


								$this->FieldOutput .= '</div>';
							}else{
								$this->FieldOutput .= '<section>';
								$this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p><i class="fas fa-info-circle"></i> '.esc_html__("We couldn't find a favorite record.",'pointfindercoreelements').'</p></div>';
								$this->FieldOutput .= '</section>';
							}
						/**
						*End: Favorites Page Content
						**/
						break;

					case 'reviews':
						$formaction = 'pf_refinerevlist';
						$noncefield = wp_create_nonce($formaction);
						$buttonid = 'pf-ajax-revrefine-button';

						/**
						*Start: Reviews Page Content
						**/
							/*Post Meta Info*/
							
							$results = $wpdb->get_results( $wpdb->prepare(
								"
									SELECT ID
									FROM $wpdb->posts
									WHERE post_type = '%s' and post_author = %d
								",
								'pointfinderreviews',
								$params['current_user']
							),'ARRAY_A' );

							function pf_arraya_2_array($aval = array()){
								$aval_output = array();
								foreach ($aval as $aval_single) {

									$aval_output[] = (isset($aval_single['ID']))? $aval_single['ID'] : '';
								}
								return $aval_output;
							}
							$results = pf_arraya_2_array($results);

							$output_arr = '';
							$countarr = count($results);


							if($countarr>0){

								$this->FieldOutput .= '<div class="pfmu-itemlisting-container">';

									$paged = ( esc_sql(get_query_var('paged')) ) ? esc_sql(get_query_var('paged')) : '';
									if (empty($paged)) {
										$paged = ( esc_sql(get_query_var('page')) ) ? esc_sql(get_query_var('page')) : 1;
									}


									$user_id = $params['current_user'];


									$output_args = array(
											'post_type'	=> 'pointfinderreviews',
											'posts_per_page' => 10,
											'paged' => $paged,
											'order'	=> 'DESC',
											'orderby' => 'Date',
											'post__in' => $results
									);


									$output_loop = new WP_Query( $output_args );
									/*
									print_r($output_loop->query).PHP_EOL;
									echo $output_loop->request.PHP_EOL;
									*/


									if ( $output_loop->have_posts() ) {

										$this->FieldOutput .= '<section>';
										$this->FieldOutput .= '<div class="pfhtitle pf-row clearfix pflistingreviews">';

										/**
										*Start: Column Headers
										**/

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-6 col-md-6 col-sm-6 col-xs-7">';
											$this->FieldOutput .= esc_html__('Title','pointfindercoreelements');
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-2 col-md-2 col-sm-2 col-xs-3">';
											$this->FieldOutput .= esc_html__('Review','pointfindercoreelements');
											$this->FieldOutput .= '</div>';


											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-3 col-md-3 col-sm-3 hidden-xs">';
											$this->FieldOutput .= esc_html__('Date','pointfindercoreelements');
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfmu-itemlisting-htitlenc col-lg-1 col-md-1 col-sm-1 col-xs-2">';
											$this->FieldOutput .= '</div>';
										/**
										*End: Column Headers
										**/

										$this->FieldOutput .= '</div>';

										while ( $output_loop->have_posts() ) {
											$output_loop->the_post();

											$author_post_id = get_the_ID();
											$item_post_id = esc_attr(get_post_meta( $author_post_id, 'webbupointfinder_review_itemid', true ));

												$this->FieldOutput .= '<div class="pfmu-itemlisting-inner pf-row clearfix">';


														/* Item Title */
														$this->FieldOutput .= '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-7 pfmu-itemlisting-title-wd">';
															$this->FieldOutput .= '<div class="pfmu-itemlisting-title pf-review-list" style="padding-left:10px">';
																$this->FieldOutput .= '<a href="'.get_permalink($item_post_id).'" class="hidden-xs">'.mb_strimwidth(get_the_title($item_post_id), 0, 58, "...").'</a>';
																$this->FieldOutput .= '<a href="'.get_permalink($item_post_id).'" class="visible-xs">'.mb_strimwidth(get_the_title($item_post_id), 0, 28, "...").'</a>';
															$this->FieldOutput .= '</div>';
					                                    $this->FieldOutput .= '</div>';


					                                    /* Review Title */
														$this->FieldOutput .= '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 pfmu-itemlisting-title-wd">';
															$this->FieldOutput .= '<div class="pfmu-itemlisting-title pf-review-list pfreviewpointmain">';


																		$review_output = '';
																		$return_results = $this->pfcalculate_single_review($author_post_id);

																		if (!empty($return_results)) {
																			$review_output .= '<span class="pfiteminfolist-infotext pfreviews pfreviewpoint">';
																				$review_output .=  $return_results;
																			$review_output .= '</span>';
																		}else{
																			$review_output .= ''.esc_html__('Reviews','pointfindercoreelements').' : ';
																			$review_output .= '<span class="pfiteminfolist-infotext pfreviews" style="padding-left:10px">';
																				$review_output .=  '0 (<a title="'.esc_html__('Review Total','pointfindercoreelements').'" style="cursor:pointer">0</a>)';
																			$review_output .= '</span>';
																		}

																$this->FieldOutput .= $review_output;

															$this->FieldOutput .= '</div>';
					                                    $this->FieldOutput .= '</div>';



														/*Type of item*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast col-lg-3 col-md-3 col-sm-3 hidden-xs">';
															$this->FieldOutput .= '<ul class="pfiteminfolist" style="padding-left:10px">';
																$this->FieldOutput .= '<li>'.date_i18n(get_option('date_format') . ' ' . get_option('time_format'),get_post_time()).'</li>';
															$this->FieldOutput .= '</ul>';
														$this->FieldOutput .= '</div>';


														/*Item Footer*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-footer col-lg-1 col-md-1 col-sm-1 col-xs-2">';
													    $this->FieldOutput .= '<ul class="pfmu-userbuttonlist pfreviewbuttonlist">';
															$this->FieldOutput .= '<li class="pfmu-userbuttonlist-item"><a class="button pf-view-item-button wpf-transition-all" href="'.get_permalink($item_post_id).'" title="'.esc_html__('View','pointfindercoreelements').'"><i class="far fa-eye"></i></a></li>';
														$this->FieldOutput .= '</ul>';

														$this->FieldOutput .= '</div>';


													$this->FieldOutput .= '</div>';


										}

										$this->FieldOutput .= '</section>';
									}else{
										$this->FieldOutput .= '<section>';
										$this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p><i class="fas fa-info-circle"></i> '.esc_html__("We couldn't find a review record.",'pointfindercoreelements').'</p></div>';

										$this->FieldOutput .= '</section>';
									}
									$this->FieldOutput .= '<div class="pfstatic_paginate" >';
									$big = 999999999;
									$this->FieldOutput .= paginate_links(array(
										'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
										'format' => '?paged=%#%',
										'current' => max(1, $paged),
										'total' => $output_loop->max_num_pages,
										'type' => 'list',
									));
									$this->FieldOutput .= '</div>';
									wp_reset_postdata();

								$this->FieldOutput .= '</div>';
							}else{
								$this->FieldOutput .= '<section>';
								$this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p><i class="fas fa-info-circle"></i> '.esc_html__("We couldn't find a review record.",'pointfindercoreelements').'</p></div>';
								$this->FieldOutput .= '</section>';
							}
						/**
						*End: Reviews Page Content
						**/
						break;

					case 'invoices':
						$formaction = 'pf_refineinvlist';
						$noncefield = wp_create_nonce($formaction);
						$buttonid = 'pf-ajax-invrefine-button';

						/**
						*Start: Invoices Page Content
						**/
							/*Post Meta Info*/
							
							$results = $wpdb->get_results( $wpdb->prepare(
								"
									SELECT ID
									FROM $wpdb->posts
									WHERE post_type = '%s' and post_author = %d
								",
								'pointfinderinvoices',
								$params['current_user']
							),'ARRAY_A' );

							function pf_arraya_2_array($aval = array()){
								$aval_output = array();
								foreach ($aval as $aval_single) {

									$aval_output[] = (isset($aval_single['ID']))? $aval_single['ID'] : '';
								}
								return $aval_output;
							}
							$results = pf_arraya_2_array($results);

							$output_arr = '';
							$countarr = count($results);


							if($countarr>0){

								$this->FieldOutput .= '<div class="pfmu-itemlisting-container pfinvoices">';

									$paged = ( esc_sql(get_query_var('paged')) ) ? esc_sql(get_query_var('paged')) : '';
									if (empty($paged)) {
										$paged = ( esc_sql(get_query_var('page')) ) ? esc_sql(get_query_var('page')) : 1;
									}


									$user_id = $params['current_user'];


									$output_args = array(
											'post_type'	=> 'pointfinderinvoices',
											'posts_per_page' => 10,
											'paged' => $paged,
											'order'	=> 'DESC',
											'orderby' => 'Date',
											'post__in' => $results
									);


									$output_loop = new WP_Query( $output_args );
									/*
									print_r($output_loop->query).PHP_EOL;
									echo $output_loop->request.PHP_EOL;
									*/


									if ( $output_loop->have_posts() ) {

										$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');
										

										$this->FieldOutput .= '<section>';
										$this->FieldOutput .= '<div class="pfinvoices pfhtitle pf-row clearfix hidden-xs">';

										/**
										*Start: Column Headers
										**/
											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-sm-2">';
											$this->FieldOutput .= esc_html__('Print/ID','pointfindercoreelements');
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-4 col-md-3 col-sm-3">';
											$this->FieldOutput .= esc_html__('Desc','pointfindercoreelements');
											$this->FieldOutput .= '</div>';


											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-2 col-md-2 col-sm-2">';
											$this->FieldOutput .= esc_html__('Status','pointfindercoreelements');
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-1 col-md-1 col-sm-1">';
											$this->FieldOutput .= esc_html__('Type','pointfindercoreelements');
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-lg-2 col-md-3 col-sm-2">';
											$this->FieldOutput .= esc_html__('Date','pointfindercoreelements');
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle col-lg-1 col-md-1 col-sm-2">';
											$this->FieldOutput .= '<i class="fas fa-wallet"></i>';
											$this->FieldOutput .= '</div>';
										/**
										*End: Column Headers
										**/

										$this->FieldOutput .= '</div>';


										$this->FieldOutput .= '<div class="pfinvoices pfhtitle pf-row clearfix hidden-lg hidden-md hidden-sm">';

										/**
										*Start: Column Headers
										**/
											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-xs-7">';
											$this->FieldOutput .= esc_html__('ID','pointfindercoreelements').'/'.esc_html__('Status','pointfindercoreelements').'/'.esc_html__('Amount','pointfindercoreelements').'/'.esc_html__('Type','pointfindercoreelements');;
											$this->FieldOutput .= '</div>';


											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle hidden-xs">';
											$this->FieldOutput .= esc_html__('Desc','pointfindercoreelements');
											$this->FieldOutput .= '</div>';

											$this->FieldOutput .= '<div class="pfmu-itemlisting-htitle pfexhtitle col-xs-5">';
											$this->FieldOutput .= esc_html__('Date','pointfindercoreelements');
											$this->FieldOutput .= '</div>';
										/**
										*End: Column Headers
										**/

										$this->FieldOutput .= '</div>';


										$inv_prefix = PFASSIssetControl('setup_invoices_prefix','','PFI');
										$setup20_paypalsettings_paypal_price_short = $this->PFSAIssetControl('setup20_paypalsettings_paypal_price_short','','$');

										while ( $output_loop->have_posts() ) {
											$output_loop->the_post();

											$author_post_id = get_the_ID();
											$pf_inv_type = get_post_meta( $author_post_id,'pointfinder_invoice_invoicetype', true );
											$price_val_inv = get_post_meta( $author_post_id, 'pointfinder_invoice_amount', true );
											$pf_inv_type_mobile = '';
											if ($pf_inv_type == 'Bank Transfer') {
												$pf_inv_type_mobile = '<i class="fas fa-university" title="'.esc_html__('Bank Transfer','pointfindercoreelements').'"></i>';
											}elseif($pf_inv_type == 'Credit Card Payment'){
												$pf_inv_type_mobile = '<i class="far fa-credit-card" title="'.esc_html__('Credit Card Payment','pointfindercoreelements').'"></i>';
											}elseif($pf_inv_type == 'Paypal Payment'){
												$pf_inv_type_mobile = '<i class="fab fa-cc-paypal" title="'.esc_html__('Paypal Payment','pointfindercoreelements').'"></i>';
											}elseif($pf_inv_type == 'Stripe Payment'){
												$pf_inv_type_mobile = '<i class="fab fa-cc-stripe" title="'.esc_html__('Stripe Payment','pointfindercoreelements').'"></i>';
											}else{
												$pf_inv_type_mobile = '<i class="fab fa-cc-visa" title="'.$pf_inv_type.'"></i>';
											}


											if (strpos($price_val_inv, $setup20_paypalsettings_paypal_price_short) === false) {
												$price_val_inv =  ($price_val_inv != 0)?$this->pointfinder_reformat_pricevalue_for_frontend((int)$price_val_inv):0;
											}
											$item_post_status_out_mobile = $item_post_status_out = '';
												switch (get_post_status()) {
													case 'publish':
														$item_post_status_out = esc_html__('Completed','pointfindercoreelements');
														$item_post_status_out_mobile = '<i class="fas fa-check-circle" style="color:#8DB600"></i>';
														break;
													case 'pendingpayment':
														$item_post_status_out = esc_html__('Pending Payment','pointfindercoreelements');
														$item_post_status_out_mobile = '<i class="fas fa-exclamation-circle" style="color:#FFE135"></i>';
														break;
													case 'pendingapproval':
														$item_post_status_out = esc_html__('Pending Approval','pointfindercoreelements');
														$item_post_status_out_mobile = '<i class="fas fa-circle" style="color:#FFE135"></i>';
														break;
													case 'rejected':
														$item_post_status_out = esc_html__('Rejected','pointfindercoreelements');
														$item_post_status_out_mobile = '<i class="fas fa-times-circle" style="color:#E32636"></i>';
														break;
												}


												$this->FieldOutput .= '<div class="pfmu-itemlisting-inner hidden-xs clearfix">';
													$this->FieldOutput .= '<div class="pf-row">';
														/* Item ID */
														$this->FieldOutput .= '<div class="col-sm-2 col-xs-4 pfmu-itemlisting-title-wd">';
															$this->FieldOutput .= '<div class="pfmu-itemlisting-title pf-review-list">';
																$this->FieldOutput .= '<a href="'.get_permalink().'" style="font-weight:bold" title="'.esc_html__('View/Print','pointfindercoreelements').'" target="_blank" ><i class="fas fa-print"></i> '.$inv_prefix.$author_post_id.'</a>';
															$this->FieldOutput .= '</div>';
					                                    $this->FieldOutput .= '</div>';


														/* Item Title */
														$this->FieldOutput .= '<div class="col-lg-4 col-md-3 col-sm-3 col-xs-4 pfmu-itemlisting-title-wd">';
															$this->FieldOutput .= '<div class="pfmu-itemlisting-title pf-review-list" style="padding-left: 20px!important;">';
																$this->FieldOutput .= mb_strimwidth(get_the_title(), 0, 28, "...");
															$this->FieldOutput .= '</div>';
					                                    $this->FieldOutput .= '</div>';

					                                    /* Status */
														$this->FieldOutput .= '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 pfmu-itemlisting-title-wd">';
															$this->FieldOutput .= '<div class="pfmu-itemlisting-title pf-review-list">';
																		$this->FieldOutput .= $item_post_status_out;
															$this->FieldOutput .= '</div>';
					                                    $this->FieldOutput .= '</div>';

					                            
					                                    /* Type */
														$this->FieldOutput .= '<div class="col-lg-1 col-md-1 col-sm-1 col-xs-4 pfmu-itemlisting-title-wd">';
															$this->FieldOutput .= '<div class="pfmu-itemlisting-title pf-review-list">';
																		$this->FieldOutput .= $pf_inv_type_mobile;
															$this->FieldOutput .= '</div>';
					                                    $this->FieldOutput .= '</div>';


														/*Date*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast col-lg-2 col-md-3 col-sm-2 col-xs-4">';
															$this->FieldOutput .= '<div class="pfmu-itemlisting-title pf-review-list pflistdate">';
																$this->FieldOutput .= '<span>'.sprintf( esc_html__('%1$s', 'pointfindercoreelements'), get_the_date()).'</span>';
															$this->FieldOutput .= '</div>';
														$this->FieldOutput .= '</div>';


														/*Item Footer*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-footer col-lg-1 col-md-1 col-sm-2 col-xs-4">';
														    $this->FieldOutput .= '<div class="pfmu-itemlisting-title pf-review-list" style="text-align:right">';

														    	$this->FieldOutput .= $price_val_inv;

															$this->FieldOutput .= '</div>';
														$this->FieldOutput .= '</div>';

													$this->FieldOutput .= '</div>';

												$this->FieldOutput .= '</div>';


												$this->FieldOutput .= '<div class="pfmu-itemlisting-inner pfinvoicemobile hidden-sm hidden-lg hidden-md clearfix">';
													$this->FieldOutput .= '<div class="pf-row">';
														/* Item ID */
														$this->FieldOutput .= '<div class="pfmu-itemlisting-title-wd pfinvoicemobileinner col-xs-7">';
															$this->FieldOutput .= '<div class="pfmu-itemlisting-title pf-review-list" style="padding-left:10px!important">';
																$this->FieldOutput .= '<a href="'.get_permalink().'" style="font-weight:bold" title="'.esc_html__('View/Print','pointfindercoreelements').'" target="_blank" ><i class="fas fa-print"></i> '.$inv_prefix.$author_post_id.' </a>- '.$item_post_status_out_mobile.' - '.$price_val_inv.' - '.$pf_inv_type_mobile;
															$this->FieldOutput .= '</div>';
					                                    $this->FieldOutput .= '</div>';

					                                    $this->FieldOutput .= '<div class="hidden-xs" style="text-align: left;">';
														/* Item Title */
														$this->FieldOutput .= '<div class="pfmu-itemlisting-title-wd pfinvoicemobileinner">';
															$this->FieldOutput .= '<div class="pfmu-itemlisting-title pf-review-list">';
																$this->FieldOutput .= mb_strimwidth(get_the_title(), 0, 15, "...");
															$this->FieldOutput .= '</div>';
					                                    $this->FieldOutput .= '</div>';



					                                    $this->FieldOutput .= '</div>';


														/*Date*/
														$this->FieldOutput .= '<div class="pfmu-itemlisting-info pfflast pfinvoicemobileinner col-xs-5">';
															$this->FieldOutput .= '<div class="pfmu-itemlisting-title pf-review-list">';
																$this->FieldOutput .= '<span>'.sprintf( esc_html__('%1$s', 'pointfindercoreelements'), get_the_date()).'</span>';
															$this->FieldOutput .= '</div>';
														$this->FieldOutput .= '</div>';


														

													$this->FieldOutput .= '</div>';

												$this->FieldOutput .= '</div>';

										}

										$this->FieldOutput .= '</section>';
									}else{
										$this->FieldOutput .= '<section>';
										$this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p><i class="fas fa-info-circle"></i> '.esc_html__("We couldn't find an invoice record.",'pointfindercoreelements').'</p></div>';

										$this->FieldOutput .= '</section>';
									}
									$this->FieldOutput .= '<div class="pfstatic_paginate" >';
									$big = 999999999;
									$this->FieldOutput .= paginate_links(array(
										'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
										'format' => '?paged=%#%',
										'current' => max(1, $paged),
										'total' => $output_loop->max_num_pages,
										'type' => 'list',
									));
									$this->FieldOutput .= '</div>';
									wp_reset_postdata();

								$this->FieldOutput .= '</div>';
							}else{
								$this->FieldOutput .= '<section>';
								$this->FieldOutput .= '<div class="notification warning" id="pfuaprofileform-notify-warning"><p><i class="fas fa-info-circle"></i> '.esc_html__("We couldn't find an invoice record.",'pointfindercoreelements').'</p></div>';
								$this->FieldOutput .= '</section>';
							}
						/**
						*End: Invoices Page Content
						**/
						break;


					case 'mymessages':
						/**
						*Start: Bank Transfer Page Content
						**/
							$this->FieldOutput .= '<div class="pf-banktransfer-window">';

								$this->FieldOutput .= do_shortcode('[front-end-pm]');


							$this->FieldOutput .= '</div>';

						/**
						*End: Bank Transfer Page Content
						**/
						break;

				}

			/**
			*Start: Page Footer Actions / Divs / Etc...
			**/
				$this->FieldOutput .= '</div>';/*row*/
				$this->FieldOutput .= '</div>';/*form-section*/
				$this->FieldOutput .= '</div>';/*form-enclose*/


				if($params['formtype'] != 'myitems' && $params['formtype'] != 'favorites' && $params['formtype'] != 'reviews'){$xtext = '';}else{$xtext = 'style="background:transparent;background-color:transparent;display:none!important"';}


				if ($params['formtype'] != 'mymessages') {
					$this->FieldOutput .= '<div class="pfalign-right" '.$xtext.'>';
				}
				
				if($params['formtype'] != 'errorview' && $params['formtype'] != 'banktransfer' && $params['formtype'] != 'mymessages'){
					if($params['formtype'] != 'myitems' && $params['formtype'] != 'favorites' && $params['formtype'] != 'reviews' && $params['formtype'] != 'invoices' && $params['dontshowpage'] != 1 && $main_package_expire_problem != true){
			            $this->FieldOutput .='
			                <section '.$xtext.'> ';
			                if($params['formtype'] == 'upload'){
				                $setup31_userpayments_recurringoption = $this->PFSAIssetControl('setup31_userpayments_recurringoption','','1');

			                }elseif ($params['formtype'] == 'edititem') {

			                	$this->FieldOutput .='
				                   <input type="hidden" name="edit_pid" value="'.$params['post_id'].'">';
			                }
			                if ($main_package_purchase_permission == true || $main_package_upgrade_permission == true) {
			                	$this->FieldOutput .='<input type="hidden" name="selectedpackageid" value="">';
			                }elseif ($main_package_renew_permission == true && !empty($membership_user_package_id)) {
			                	if ($free_membership == false) {
			                		$this->FieldOutput .='<input type="hidden" name="selectedpackageid" value="'.$membership_user_package_id.'">';
			                	}else{
			                		$this->FieldOutput .='<input type="hidden" name="selectedpackageid">';
			                	}
			                }
			                if ($main_package_renew_permission == true) {
			                	$this->FieldOutput .='<input type="hidden" name="subaction" value="r">';
			                }elseif ($main_package_purchase_permission == true) {
			                	$this->FieldOutput .='<input type="hidden" name="subaction" value="n">';
			                }elseif ($main_package_upgrade_permission == true) {
			                	$this->FieldOutput .='<input type="hidden" name="subaction" value="u">';
			                }
			                $this->FieldOutput .= '
			                   <input type="hidden" value="'.$formaction.'" name="action" />
			                   <input type="hidden" value="'.$noncefield.'" name="security" />
			                   ';
			                if (!$hide_button) {
			                	$this->FieldOutput .= '
				                   <input type="submit" value="'.$buttontext.'" id="'.$buttonid.'" class="button blue pfmyitempagebuttonsex" data-edit="'.$params['post_id'].'"  />
			                   ';
			                }

			                $this->FieldOutput .= '
			                </section>
			            ';
		         	}else{
		       			$this->FieldOutput .='
			                <section  '.$xtext.'>
			                   <input type="hidden" value="'.$formaction.'" name="action" />
			                   <input type="hidden" value="'.$noncefield.'" name="security" />
			                </section>
			            ';
		       		}
		       	}

	            $this->FieldOutput.='
	            </div>
				';

				if ($params['formtype'] != 'mymessages') {
					$this->FieldOutput .= '</form>';
				}
				$this->FieldOutput .= '</div>';/*golden-forms*/
			/**
			*End: Page Footer Actions / Divs / Etc...
			**/


		}

		/**
		*Start: Class Functions
		**/
			public function PFGetList($params = array())
			{
			    $defaults = array(
			        'listname' => '',
			        'listtype' => '',
			        'listtitle' => '',
			        'listsubtype' => '',
			        'listdefault' => '',
			        'listmultiple' => 0,
			        'parentonly' => 0
			    );

			    $params = array_merge($defaults, $params);

			    	$output_options = '';
			    	if($params['listmultiple'] == 1){ $multiplevar = ' multiple';$multipletag = '[]';}else{$multiplevar = '';$multipletag = '';};

			    	if ($params['parentonly'] == 1) {
			    		$fieldvalues = get_terms($params['listsubtype'],array('hide_empty'=>false,'parent'=>0));
			    	}else{
			    		$fieldvalues = get_terms($params['listsubtype'],array('hide_empty'=>false));
			    	}

					foreach( $fieldvalues as $parentfieldvalue){
						if($parentfieldvalue->parent == 0){

							$fieldParenttaxSelectedValuex = 0;

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



			    	$output = '';
					$output .= '<div class="pf_fr_inner" data-pf-parent="">';


	   				if (!empty($params['listtitle'])) {
		   				$output .= '<label for="'.$params['listname'].'" class="lbl-text">'.$params['listtitle'].':</label>';
	   				}

	   				$as_mobile_dropdowns = $this->PFSAIssetControl('as_mobile_dropdowns','','0');

					if ($as_mobile_dropdowns == 1) {
						$as_mobile_dropdowns_text = 'class="pf-special-selectbox"';
					} else {
						$as_mobile_dropdowns_text = '';
					}

	   				$output .= '
	                <label class="lbl-ui select">
	                <select'.$multiplevar.' name="'.$params['listname'].$multipletag.'" id="'.$params['listname'].'" '.$as_mobile_dropdowns_text.'>';
	                $output .= '<option></option>';
	                $output .= $output_options.'
	                </select>
	                </label>';


			   		$output .= '</div>';

	            return $output;
			}

			private function GetPFTermName($id,$taxname){
				$post_type_name = wp_get_post_terms($id, $taxname, array("fields" => "names"));

				if($this->PFControlEmptyArr($post_type_name)){
					return $post_type_name[0];
				}
			}
	

			private function PFValidationCheckWrite($field_validation_check,$field_validation_text,$itemid){

				$itemname = (string)trim($itemid);
				$itemname = (strpos($itemname, '[]') == false) ? $itemname : "'".$itemname."'" ;

				if($field_validation_check == 1){
					if($this->VSOMessages != ''){
						$this->VSOMessages .= ','.$itemname.':"'.$field_validation_text.'"';
					}else{
						$this->VSOMessages = $itemname.':"'.$field_validation_text.'"';
					}

					if($this->VSORules != ''){
						$this->VSORules .= ','.$itemname.':"required"';
					}else{
						$this->VSORules = $itemname.':"required"';
					}
				}
			}

			private function PF_UserLimit_Check($action,$post_status){

				switch ($post_status) {
					case 'publish':
							switch ($action) {
								case 'edit':
									$output = ($this->PFSAIssetControl('setup31_userlimits_useredit','','1') == 1) ? 1 : 0 ;
									break;

								case 'delete':
									$output = ($this->PFSAIssetControl('setup31_userlimits_userdelete','','1') == 1) ? 1 : 0 ;
									break;
							}

						break;

					case 'pendingpayment':
							switch ($action) {
								case 'edit':
									$output = ($this->PFSAIssetControl('setup31_userlimits_useredit_pendingpayment','','1') == 1) ? 1 : 0 ;
									break;

								case 'delete':
									$output = ($this->PFSAIssetControl('setup31_userlimits_userdelete_pendingpayment','','1') == 1) ? 1 : 0 ;
									break;
							}

						break;

					case 'rejected':
							switch ($action) {
								case 'edit':
									$output = ($this->PFSAIssetControl('setup31_userlimits_useredit_rejected','','1') == 1) ? 1 : 0 ;
									break;

								case 'delete':
									$output = ($this->PFSAIssetControl('setup31_userlimits_userdelete_rejected','','1') == 1) ? 1 : 0 ;
									break;
							}

						break;

					case 'pendingapproval':
							switch ($action) {
								case 'edit':
									$output = 0 ;
									break;

								case 'delete':
									$output = ($this->PFSAIssetControl('setup31_userlimits_userdelete_pendingapproval','','1') == 1) ? 1 : 0 ;
									break;
							}

						break;

					case 'pfonoff':
							switch ($action) {
								case 'edit':
									$output = ($this->PFSAIssetControl('setup31_userlimits_useredit','','1') == 1) ? 1 : 0 ;
									break;

								case 'delete':
									$output = ($this->PFSAIssetControl('setup31_userlimits_userdelete','','1') == 1) ? 1 : 0 ;
									break;
							}

						break;
				}

				return $output;
			}

			private function PFU_GetPostOrderDate($value) {
				global $wpdb;
				$result = $wpdb->get_var( $wpdb->prepare( 
					"SELECT post_date FROM $wpdb->posts WHERE ID = %d", 
					$value
				) );
				return $result;
			}

			private function PFU_Dateformat($value,$showtime = 0){
				/*$setup4_membersettings_dateformat = $this->PFSAIssetControl('setup4_membersettings_dateformat','','1');
		
				'1' => 'dd/mm/yyyy', 
			    '2' => 'mm/dd/yyyy', 
			    '3' => 'yyyy/mm/dd',
			    '4' => 'yyyy/dd/mm'
				
				switch ($setup4_membersettings_dateformat) {
					case '1':
						$datetype = ($showtime != 1)? "d-m-Y" : "d-m-Y H:i:s";
						break;
					
					case '2':
						$datetype = ($showtime != 1)? "m-d-Y" : "m-d-Y H:i:s";
						break;

					case '3':
						$datetype = ($showtime != 1)? "Y-m-d" : "Y-m-d H:i:s";
						break;

					case '4':
						$datetype = ($showtime != 1)? "Y-d-m" : "Y-d-m H:i:s";
						break;
				}
				$newdate = date($datetype,strtotime($value));
				*/

				if (empty($value)) {
					return '<span class="pfdatenotfound" title="'.esc_html__( "This listing uploaded from backend.", "pointfindercoreelements" ).'"><strong>'.esc_html__( "?", "pointfindercoreelements" ).'</strong></span>';
				}
				if ($showtime != 1) {
					return date_i18n( get_option( 'date_format' ), strtotime($value) );
				}else{
					return date_i18n( get_option('date_format') . ' ' . get_option('time_format'), strtotime($value) );
				}
			}
	    /**
		*End: Class Functions
		**/


	   function __destruct() {
		  $this->FieldOutput = '';
		  $this->ScriptOutput = '';
	    }
	}