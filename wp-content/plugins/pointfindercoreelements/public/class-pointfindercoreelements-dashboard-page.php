<?php

/**********************************************************************************************************************************
*
* User Dashboard Actions
*
* Author: Webbu
*
***********************************************************************************************************************************/
if (class_exists('PointfinderDashboardPageClass')) {	
	return;
}
class PointfinderDashboardPageClass
{
	use PointFinderOptionFunctions;
	use PointFinderCommonFunctions;
	use PointFinderCUFunctions;
	use PointFinderWPMLFunctions;
	use PointFinderMailSystem;
	use PointFinderDashFunctions;

	

 	public function __construct(){}

 	private function pointfinder_post_exists( $id ) {return is_string( get_post_status( $id ) );}
 	private function pfcalculatefavs($user_id){

		$user_favorites_arr = get_user_meta( $user_id, 'user_favorites', true );

		$latest_fav_count = $new_favorite_count = $favorite_count = 0;

		if (!empty($user_favorites_arr)) {
			$user_favorites_arr = json_decode($user_favorites_arr,true);
			$favorite_count = count($user_favorites_arr);

			if (!empty($user_favorites_arr)) {
				foreach ($user_favorites_arr as $user_favorites_arr_single) {
					if($this->pointfinder_post_exists($user_favorites_arr_single)){
						$new_user_fav_arr[] = $user_favorites_arr_single;
					}
				}
			}else{
				$new_user_fav_arr = array();
			}
			$new_favorite_count = (!empty($new_user_fav_arr))? count($new_user_fav_arr):0;

			if ($favorite_count !== $new_favorite_count) {
				if (isset($new_user_fav_arr)) {
					update_user_meta($user_id,'user_favorites',json_encode($new_user_fav_arr));
					$latest_fav_count = $new_favorite_count;
				}

			}else{
				$latest_fav_count = $favorite_count;
			}
		}

		return $latest_fav_count;
	}

    public function pointfinder_dashpage_maindash(){
    	if(isset($_GET['ua'])){ $ua_action = esc_attr($_GET['ua']);}


			$setup4_membersettings_dashboard = $this->PFSAIssetControl('setup4_membersettings_dashboard','','');


			if(isset($ua_action) && is_page($setup4_membersettings_dashboard)){
				$setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
				$pfmenu_perout = $this->PFPermalinkCheck();

				if(is_user_logged_in()){

					if($setup4_membersettings_dashboard != 0){

							if ($ua_action == 'profile') {
								$this->profile_form_content();
							}
							$setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
							$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');
							$setup4_submitpage_status_old = $this->PFSAIssetControl('setup4_submitpage_status_old','','0');

							$current_user = wp_get_current_user();
							$user_id = $current_user->ID;
							/**
							*Start: Member Page Actions
							**/
							if (is_page($setup4_membersettings_dashboard)) {


								/**
								*Start: Menu
								**/
									$sidebar_output = '';
									$item_count = $favorite_count = $review_count = 0;

									global $wpdb;

									//$item_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM $wpdb->posts where post_author = %d and post_type = %s and post_status IN (%s,%s,%s)",$user_id,$setup3_pointposttype_pt1,"publish","pendingpayment","pendingapproval")  );

									$item_count_query = new WP_Query( array('author'=>$user_id, 'post_status'=> array("publish","pendingpayment","pendingapproval"),'post_type'=>$setup3_pointposttype_pt1) );
									$item_count = (!isset($item_count_query->found_posts)) ? 0 : $item_count_query->found_posts;
									wp_reset_postdata();
									$favorite_count = $this->pfcalculatefavs($user_id);

									/** Prepare Menu Output **/
									$setup4_membersettings_favorites = $this->PFSAIssetControl('setup4_membersettings_favorites','','1');
									$setup11_reviewsystem_check = $this->PFREVSIssetControl('setup11_reviewsystem_check','','0');
									$setup4_membersettings_frontend = $this->PFSAIssetControl('setup4_membersettings_frontend','','0');
									$setup4_membersettings_loginregister = $this->PFSAIssetControl('setup4_membersettings_loginregister','','1');


									$setup29_dashboard_contents_my_page_menuname = $this->PFSAIssetControl('setup29_dashboard_contents_my_page_menuname','','');
									$setup29_dashboard_contents_inv_page_menuname = $this->PFSAIssetControl('setup29_dashboard_contents_inv_page_menuname','','');
									$setup29_dashboard_contents_favs_page_menuname = $this->PFSAIssetControl('setup29_dashboard_contents_favs_page_menuname','','');
									$setup29_dashboard_contents_profile_page_menuname = $this->PFSAIssetControl('setup29_dashboard_contents_profile_page_menuname','','');
									$setup29_dashboard_contents_submit_page_menuname = $this->PFSAIssetControl('setup29_dashboard_contents_submit_page_menuname','','');
									$setup29_dashboard_contents_rev_page_menuname = $this->PFSAIssetControl('setup29_dashboard_contents_rev_page_menuname','','');

									$setup_invoices_sh = PFASSIssetControl('setup_invoices_sh','','1');

									$pfmenu_output = '';

									$user_name_field = get_user_meta( $user_id, 'first_name', true ).' '.get_user_meta( $user_id, 'last_name', true );
									if ($user_name_field == ' ') {$user_name_field = $current_user->user_login;}

									$user_photo_field = get_user_meta( $user_id, 'user_photo', true );
									$user_photo_field_output = ''.PFCOREELEMENTSURLPUBLIC.'images/empty_avatar.jpg';
									if(!empty($user_photo_field)){
										$user_photo_field = wp_get_attachment_image_src($user_photo_field);
										if (isset($user_photo_field[0])) {
											$user_photo_field_output = $user_photo_field[0];
										}
									}

									if ($setup4_membersettings_paymentsystem == 2) {
										/*Get user meta*/
										$membership_user_package_id = get_user_meta( $user_id, 'membership_user_package_id', true );
										$packageinfo = $this->pointfinder_membership_package_details_get($membership_user_package_id);

										$membership_user_package = get_user_meta( $user_id, 'membership_user_package', true );
										$membership_user_item_limit = get_user_meta( $user_id, 'membership_user_item_limit', true );
										$membership_user_featureditem_limit = get_user_meta( $user_id, 'membership_user_featureditem_limit', true );
										$membership_user_image_limit = get_user_meta( $user_id, 'membership_user_image_limit', true );
										$membership_user_trialperiod = get_user_meta( $user_id, 'membership_user_trialperiod', true );
										$membership_user_recurring = get_user_meta( $user_id, 'membership_user_recurring', true );

										$membership_user_activeorder = get_user_meta( $user_id, 'membership_user_activeorder', true );
			              				$membership_user_expiredate = get_post_meta( $membership_user_activeorder, 'pointfinder_order_expiredate', true );

			              				/*Bank Transfer vars*/
			              				$membership_user_activeorder_ex = get_user_meta( $user_id, 'membership_user_activeorder_ex', true );
			              				$membership_user_package_id_ex = get_user_meta( $user_id, 'membership_user_package_id_ex', true );
			              				if (!empty($membership_user_activeorder_ex)) {
			              					$pointfinder_order_bankcheck = get_post_meta( $membership_user_activeorder_ex, 'pointfinder_order_bankcheck', true );
			              				}else{
			              					$pointfinder_order_bankcheck = '';
			              				}


										$package_itemlimit = $package_fitemlimit = 0;
										if (!empty($membership_user_package_id)) {
											/*Get package info*/
											$package_itemlimit = $packageinfo['packageinfo_itemnumber_output_text'];
											$package_itemlimit_num = $packageinfo['webbupointfinder_mp_itemnumber'];
											$package_fitemlimit = $packageinfo['webbupointfinder_mp_fitemnumber'];
										}

										$pfmenu_output .= '<li class="pf-dash-userprof"><img src="'.$user_photo_field_output.'" class="pf-dash-userphoto"/><span class="pf-dash-usernamef">'.$user_name_field.'</span></li>';

										if (empty($membership_user_package_id)) {
											$pfmenu_output .= '<li class="pf-dash-userprof2">';

										}else{
											$pfmenu_output .= '<li class="pf-dash-userprof2 pf-dash-notempty">';

										}

										
										if (empty($membership_user_package_id)) {

											$pfmenu_output .= '<div class="pf-dash-packageinfo pf-dash-newpackage">
											<button class="pf-dash-purchaselink" title="'.esc_html__('Click here for purchase new membership package.','pointfindercoreelements').'">'.esc_html__('Purchase Membership Package','pointfindercoreelements').'</button>';
											$pfmenu_output .= "
												<script>
													jQuery('.pf-dash-purchaselink').on('click',function() {
														window.location = '".$setup4_membersettings_dashboard_link.$pfmenu_perout."ua=purchaseplan';
													});
												</script>
											";

										}else{

											$pfmenu_output .= '<div class="pf-dash-packageinfo"><span class="pf-dash-packageinfo-title">'.$membership_user_package.'</span><br/><small>'.esc_html__('Package','pointfindercoreelements').'</small><br/>';

											if ($membership_user_recurring == false || $membership_user_recurring == 0) {
												$pfmenu_output .= '<button class="pf-dash-renewlink" title="'.esc_html__('This option for extend expire date of this package.','pointfindercoreelements').'">'.esc_html__('Renew','pointfindercoreelements').'</button>
												<button class="pf-dash-changelink" title="'.esc_html__('This option for upgrade this package.','pointfindercoreelements').'">'.esc_html__('Upgrade','pointfindercoreelements').'</button>';

												$pfmenu_output .= "
													<script>
														jQuery('.pf-dash-renewlink').on('click',function() {
															window.location = '".$setup4_membersettings_dashboard_link.$pfmenu_perout."ua=renewplan';
														});
														jQuery('.pf-dash-changelink').on('click',function() {
															window.location = '".$setup4_membersettings_dashboard_link.$pfmenu_perout."ua=upgradeplan';
														});
													</script>
												";
											}

										}
										$pfmenu_output .= '
										</div>
										</li>';

										if (!empty($pointfinder_order_bankcheck)) {
											$pfmenu_output .= '<li class="pf-dash-userprof2 pf-dash-bank-info">';

													$pfmenu_output .= '<div class="pf-dash-packageinfo">
													<strong>'.esc_html__('Bank Transfer : ','pointfindercoreelements').'</strong>'. get_the_title($membership_user_package_id_ex).'<br/>
													<strong>'.esc_html__('Status : ','pointfindercoreelements').'</strong>'. esc_html__('Pending Bank Payment','pointfindercoreelements').'
													<button class="pf-dash-cancelbanklink" title="'.esc_html__('Click here for cancel transfer.','pointfindercoreelements').'">'.esc_html__('Cancel Transfer','pointfindercoreelements').'</button>';
													$pfmenu_output .= "
														<script>
															jQuery('.pf-dash-cancelbanklink').on('click',function() {
																window.location = '".$setup4_membersettings_dashboard_link.$pfmenu_perout."ua=myitems&action=cancelbankm';
															});
														</script>
													";

											$pfmenu_output .= '
											</div>
											</li>';
										}



										if (!empty($membership_user_package_id)) {
											if ($membership_user_item_limit < 0) {
												$package_itemlimit_text = esc_html__('Unlimited','pointfindercoreelements');
											} else {
												$package_itemlimit_text = $package_itemlimit.'/'.$membership_user_item_limit;
											}
											if (!empty($membership_user_expiredate)) {
												if ($this->pf_membership_expire_check($membership_user_expiredate) == false) {
													$expire_date_text = $this->PFU_DateformatS($membership_user_expiredate);
												}else{
													$expire_date_text = '<span style="color:red;">'.__("EXPIRED","pointfindercoreelements").'</span>';
												}
											}else{
												$expire_date_text = '<span style="color:red;">'.__("ERROR!","pointfindercoreelements").'</span>';
											}

											$pfmenu_output .= '<li class="pf-dash-userprof2 pf-dash-userlimits">
											<div class="pf-dash-packageinfo pf-dash-package-infoex">
												<div class="pf-dash-pinfo-col"><span class="pf-dash-packageinfo-tableex" title="'.esc_html__('Included/Remaining','pointfindercoreelements').'">'.$package_itemlimit_text.'</span><span class="pf-dash-packageinfo-table">'.esc_html__('Listings','pointfindercoreelements').'</span></div>
												<div class="pf-dash-pinfo-col"><span class="pf-dash-packageinfo-tableex" title="'.esc_html__('Included/Remaining','pointfindercoreelements').'">'.$package_fitemlimit.'/'.$membership_user_featureditem_limit.'</span><span class="pf-dash-packageinfo-table">'.esc_html__('Featured','pointfindercoreelements').'</span></div>
												<div class="pf-dash-pinfo-col"><span class="pf-dash-packageinfo-tableex" title="'.esc_html__('You can renew your package before this date.','pointfindercoreelements').'">'.$expire_date_text.'</span><span class="pf-dash-packageinfo-table">'.esc_html__('Expire Date','pointfindercoreelements').'</span></div>
											</div>
											</li>';
										}
									}else{
										$pfmenu_output .= '<li class="pf-dash-userprof"><img src="'.$user_photo_field_output.'" class="pf-dash-userphoto"/><span class="pf-dash-usernamef">'.$user_name_field.'</span></li>';
									}

									if (function_exists('fep_get_user_message_count')) {
										$message_count = fep_get_user_message_count( 'unread' );
									}else{
										$message_count = 0;
									}

									$pfmenu_output .= '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=profile"><i class="fas fa-user-cog"></i> '. $setup29_dashboard_contents_profile_page_menuname.'</a></li>';
									$pfmenu_output .= ($setup4_membersettings_frontend == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=newitem"><i class="fas fa-plus-square"></i> '. $setup29_dashboard_contents_submit_page_menuname.'</a></li>' : '' ;
									$pfmenu_output .= ($setup4_membersettings_frontend == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems"><i class="far fa-list-alt"></i> '. $setup29_dashboard_contents_my_page_menuname.'<span class="pfbadge">'.$item_count.'</span></a></li>' : '' ;
									$pfmenu_output .= (class_exists('Front_End_Pm')) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mymessages"><i class="fas fa-envelope"></i> '.esc_html__( "Messages", "pointfindercoreelements" ).' <span class="pfbadge">'.$message_count.'</span></a></li>' : '' ;
									$pfmenu_output .= ($setup4_membersettings_frontend == 1 && $setup_invoices_sh == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=invoices"><i class="fas fa-file-invoice-dollar"></i> '. $setup29_dashboard_contents_inv_page_menuname.'</a></li>' : '' ;
									$pfmenu_output .= ($setup4_membersettings_favorites == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=favorites"><i class="fas fa-heart"></i> '. $setup29_dashboard_contents_favs_page_menuname.'<span class="pfbadge">'.$favorite_count.'</span></a></li>' : '';
									$pfmenu_output .= ($setup11_reviewsystem_check == 1) ? '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=reviews"><i class="fas fa-star"></i> '. $setup29_dashboard_contents_rev_page_menuname.'</a></li>' : '';
									$pfmenu_output .= '<li><a href="'.esc_url(wp_logout_url( esc_url(home_url("/")) )).'"><i class="fas fa-sign-out-alt"></i> '. esc_html__('Logout','pointfindercoreelements').'</a></li>';


									$sidebar_output .= '
										<div class="pfuaformsidebar ">
										<ul class="pf-sidebar-menu">
											'.$pfmenu_output.'
										</ul>
										</div>

										<div class="sidebar-widget"></div>
									';
								/**
								*End: Menu
								**/




								/**
								*Start: Page Start Actions / Divs etc...
								**/
									switch ($ua_action) {
										case 'purchaseplan':
											$case_text = 'purchaseplan';
										break;
										case 'renewplan':
											$case_text = 'renewplan';
										break;
										case 'upgradeplan':
											$case_text = 'upgradeplan';
										break;
										case 'profile':
											$case_text = 'profile';
										break;
										case 'favorites':
											$case_text = 'favs';
										break;
										case 'newitem':
										case 'edititem':
											$case_text = 'submit';
										break;
										case 'reviews':
											$case_text = 'rev';
										break;
										case 'myitems':
											$case_text = 'my';
										break;
										case 'invoices':
											$case_text = 'inv';
										break;
										case 'mymessages':
											$case_text = 'mm';
										break;
										default:
											$case_text = 'my';
										break;

									}

									if (!in_array($case_text, array('purchaseplan','renewplan','upgradeplan'))) {

										$setup29_dashboard_contents_my_page = $this->PFSAIssetControl('setup29_dashboard_contents_'.$case_text.'_page','','');
										$setup29_dashboard_contents_my_page_pos = $this->PFSAIssetControl('setup29_dashboard_contents_'.$case_text.'_page_pos','','1');
										$setup29_dashboard_contents_my_page_layout = $this->PFSAIssetControl('setup29_dashboard_contents_profile_page_layout','','3');
										if ($ua_action == 'edititem') {
											$setup29_dashboard_contents_my_page_title = $this->PFSAIssetControl('setup29_dashboard_contents_'.$case_text.'_page_titlee','','');
										}else{
											$setup29_dashboard_contents_my_page_title = $this->PFSAIssetControl('setup29_dashboard_contents_'.$case_text.'_page_menuname','','');
										}
									}else{
										$setup29_dashboard_contents_my_page = $this->PFSAIssetControl('setup29_dashboard_contents_submit_page','','');
										$setup29_dashboard_contents_my_page_layout = $this->PFSAIssetControl('setup29_dashboard_contents_profile_page_layout','','3');
										$setup29_dashboard_contents_my_page_pos = $this->PFSAIssetControl('setup29_dashboard_contents_submit_page_pos','','1');
										$membership_user_package_id = get_user_meta( $user_id, 'membership_user_package_id', true );

										switch ($case_text) {
											case 'purchaseplan':
												$setup29_dashboard_contents_my_page_title = esc_html__("Purchase New Plan","pointfindercoreelements" );
												break;

											case 'renewplan':
												if (!empty($membership_user_package_id)) {
													$setup29_dashboard_contents_my_page_title = esc_html__("Renew Current Plan","pointfindercoreelements" );
												}else{
													$setup29_dashboard_contents_my_page_title = esc_html__("Purchase New Plan","pointfindercoreelements" );
												}

												break;

											case 'upgradeplan':
												if (!empty($membership_user_package_id)) {
													$setup29_dashboard_contents_my_page_title = esc_html__("Upgrade Plan","pointfindercoreelements" );
												}else{
													$setup29_dashboard_contents_my_page_title = esc_html__("Purchase New Plan","pointfindercoreelements" );
												}

												break;
										}
									}

									$pf_ua_col_codes = '<div class="col-lg-9 col-md-12 col-xs-12">';
									$pf_ua_col_close = '</div>';
									$pf_ua_prefix_codes = '<section role="main"><div class="pf-container clearfix"><div class="pf-row clearfix"><div class="pf-uadashboard-container clearfix">';
									$pf_ua_suffix_codes = '</div></div></div></section>';
									$pf_ua_sidebar_codes = '<div class="col-lg-3 hidden-md hidden-xs hidden-sm">';
									$pf_ua_sidebar_close = '</div>';


									PFGetHeaderBar('',$setup29_dashboard_contents_my_page_title);

									$content_of_section = '';
									if ($setup29_dashboard_contents_my_page != '') {
										$content_of_section = do_shortcode(get_post_field( 'post_content', $setup29_dashboard_contents_my_page, 'raw' ));
									}
									if ($setup29_dashboard_contents_my_page_pos == 1 && $setup29_dashboard_contents_my_page != '') {
										echo $content_of_section;
									}


									switch($setup29_dashboard_contents_my_page_layout) {
										case '3':
										echo $pf_ua_prefix_codes.$pf_ua_col_codes;
										break;
										case '2':
										echo $pf_ua_prefix_codes.$pf_ua_sidebar_codes.$sidebar_output;
										echo $pf_ua_sidebar_close.$pf_ua_col_codes;
										break;
									}
								/**
								*End: Page Start Actions / Divs etc...
								**/

								$errorval = '';
								$sccval = '';





								switch ($ua_action) {

									case 'purchaseplan':
									case 'renewplan':
									case 'upgradeplan':
										/**
										*Start: My Items Page Content
										**/
				              				$membership_user_activeorder_ex = get_user_meta( $user_id, 'membership_user_activeorder_ex', true );
				              				if (!empty($membership_user_activeorder_ex)) {
				              					$pointfinder_order_pagscheck = get_post_meta( $membership_user_activeorder_ex, 'pointfinder_order_pagscheck', true );
				              				}else{
				              					$pointfinder_order_pagscheck = '';
				              				}
				              				if (!empty($pointfinder_order_pagscheck)) {
				              					if (!empty($sccval)) {
				              						$sccval .= '</br>';
				              					}
				              					switch ($ua_action) {
				              						case 'renewplan':
				              							$sccval .= esc_html__('Your previous order is waiting for approval. Please wait until we receive notification from PagSeguro. If you renew this plan, this may create duplicate payment. ','pointfindercoreelements');
				              							break;

				              						case 'upgradeplan':
				              							$sccval .= esc_html__('Your previous order is waiting for approval. Please wait until we receive notification from PagSeguro. If you upgrade to new plan, this may create duplicate payment.','pointfindercoreelements');
				              							break;

				              						case 'purchaseplan':
				              							$sccval .= esc_html__('Your previous order is waiting for approval. Please wait until we receive notification from PagSeguro. If you purchase new plan, this may create duplicate payment.','pointfindercoreelements');
				              							break;
				              					}

											}
										/**
										*End: My Items Form Request
										**/


										/**
										*Start: Purchase/Renew/Upgrade Plan Page Content
										**/
											$output = new PF_Frontend_Fields(
													array(
														'formtype' => $ua_action,
														'current_user' => $user_id,
														'sccval' => $sccval
													)
												);
											echo $output->FieldOutput;

											$script_output = '
											(function($) {
												"use strict";
												$(function(){
												'.$output->ScriptOutput;
												$script_output .= '
												var pfsearchformerrors = $(".pfsearchformerrors");
													$("#pfuaprofileform").validate({
														  debug:false,
														  onfocus: false,
														  onfocusout: false,
														  onkeyup: false,
														  rules:{'.$output->VSORules.'},messages:{'.$output->VSOMessages.'},
														  ignore: ".select2-input, .select2-focusser, .pfignorevalidation",
														  validClass: "pfvalid",
														  errorClass: "pfnotvalid pfaddnotvalidicon",
														  errorElement: "li",
														  errorContainer: pfsearchformerrors,
														  errorLabelContainer: $("ul", pfsearchformerrors),
														  invalidHandler: function(event, validator) {
															var errors = validator.numberOfInvalids();
															if (errors) {
																pfsearchformerrors.show("slide",{direction : "up"},100)
																$(".pfsearch-err-button").on("click",function(){
																	pfsearchformerrors.hide("slide",{direction : "up"},100)
																	return false;
																});
															}else{
																pfsearchformerrors.hide("fade",300)
															}
														  }
													});
												});'.$output->ScriptOutputDocReady;

											$script_output .= '
											})(jQuery);
											';

											wp_add_inline_script( 'theme-scriptspfm', $script_output, 'after' );

											unset($output);
										/**
										*End: Purchase/Renew/Upgrade Plan Page Content
										**/
										break;

									case 'newitem':
									case 'edititem':

										/**
										*Start: New/Edit Item Page Content
										**/
											$confirmed_postid = '';
											$formtype = 'upload';
											$dontshowpage = 0;
											if ($ua_action == 'edititem') {
												if (!empty($_GET['i'])) {
													$edit_postid = (is_numeric($_GET['i']))? esc_attr($_GET['i']):'';
													if(!empty($edit_postid)){
														$result = $wpdb->get_results( $wpdb->prepare(
															"
																SELECT ID, post_author
																FROM $wpdb->posts
																WHERE ID = %s and post_author = %s and post_type = %s
															",
															$edit_postid,
															$user_id,
															$setup3_pointposttype_pt1
														) );


														if (is_array($result) && count($result)>0) {

															if ($result[0]->ID == $edit_postid) {
																$confirmed_postid = $edit_postid;
																$formtype = 'edititem';
															}else{
																$dontshowpage = 1;
																$errorval .= esc_html__('This is not your item.','pointfindercoreelements');
															}
														}else{
															$dontshowpage = 1;
															$errorval .= esc_html__('This is not your item.','pointfindercoreelements');
														}
													}else{
														$dontshowpage = 1;
														$errorval .= esc_html__('Please select an item for edit.','pointfindercoreelements');
													}
												} else{
													$dontshowpage = 1;
													$errorval .= esc_html__('Please select an item for edit.','pointfindercoreelements');
												}


											}

											/**
											*Start : Item Image & Featured Image Delete (OLD Image Upload)
											**/ 
												$script_output = '';
												if($formtype == 'edititem'){

													if(isset($_GET) && isset($_GET['action'])){
														if (esc_attr($_GET['action']) == 'delfimg' && $setup4_submitpage_status_old == 1) {
															wp_delete_attachment(get_post_thumbnail_id( $confirmed_postid ),true);
															delete_post_thumbnail( $confirmed_postid );
															$sccval .= esc_html__('Featured image removed. Redirecting to item details...','pointfindercoreelements');

													  		$output = new PF_Frontend_Fields(
																array(
																	'formtype' => 'errorview',
																	'sccval' => $sccval
																	)
																);

															echo $output->FieldOutput;

															echo '<script type="text/javascript">
																<!--
																window.location = "'.$setup4_membersettings_dashboard_link.'/?ua=edititem&i='.$confirmed_postid.'"
																//-->
																</script>';
															break;
														}elseif (esc_attr($_GET['action']) == 'delimg' && $setup4_submitpage_status_old == 1) {
															$delimg_id = '';
															$delimg_id = esc_attr($_GET['ii']);

															if($delimg_id != ''){
																delete_post_meta( $confirmed_postid, 'webbupointfinder_item_images', $delimg_id );
																if(isset($confirmed_postid)){
																	wp_delete_attachment( $delimg_id, true );
																}

																$sccval .= esc_html__('Image removed. Redirecting item details...','pointfindercoreelements');

														  		$output = new PF_Frontend_Fields(
																	array(
																		'formtype' => 'errorview',
																		'sccval' => $sccval
																		)
																	);

																echo $output->FieldOutput;

																echo '<script type="text/javascript">
																	<!--
																	window.location = "'.$setup4_membersettings_dashboard_link.'/?ua=edititem&i='.$confirmed_postid.'"
																	//-->
																	</script>';
																break;
															}
														}
													}
												}
											/**
											*End : Item Image & Featured Image Delete (OLD Image Upload)
											**/

											$output = new PF_Frontend_Fields(
												array(
													'fields'=>'',
													'formtype' => $formtype,
													'sccval' => $sccval,
													'post_id' => $confirmed_postid,
													'errorval' => $errorval,
													'current_user' => $user_id,
													'dontshowpage' => $dontshowpage
													)
												);

											echo $output->FieldOutput;
											$script_output .= '
											(function($) {
												"use strict";
												$(function(){
												'.$output->ScriptOutput;
												$script_output .= '

												var pfsearchformerrors = $(".pfsearchformerrors");
													$("#pfuaprofileform").validate({
														  debug:false,
														  onfocus: false,
														  onfocusout: false,
														  onkeyup: false,
														  rules:{'.$output->VSORules.'},messages:{'.$output->VSOMessages.'},
														  ignore: ".select2-input, .select2-focusser, .pfignorevalidation",
														  validClass: "pfvalid",
														  errorClass: "pfnotvalid pfaddnotvalidicon",
														  errorElement: "li",
														  errorContainer: pfsearchformerrors,
														  errorLabelContainer: $("ul", pfsearchformerrors),
														  invalidHandler: function(event, validator) {
															var errors = validator.numberOfInvalids();
															if (errors) {
																pfsearchformerrors.show("slide",{direction : "up"},100)
																$(".pfsearch-err-button").on("click",function(){
																	pfsearchformerrors.hide("slide",{direction : "up"},100)
																	return false;
																});
															}else{
																pfsearchformerrors.hide("fade",300)
															}
														  }
													});
												});'.$output->ScriptOutputDocReady;

											$script_output .= '
											})(jQuery);
										';

											wp_add_inline_script( 'theme-scriptspfm', $script_output, 'after' );
											unset($output);
										/**
										*End: New/Edit Item Page Content
										**/
										break;

									case 'myitems':
										$script_output = '';
										do_action('pointfinder_myitems_page_before_action',$user_id);

										/**
										*Start: PayFast Process
										**/
											if (isset($_GET['payf'])) {

												if (esc_attr( $_GET['payf'] ) == 'success') {
													$sccval .= esc_html__('Thanks for your payment. PayFast payment process completed. ','pointfindercoreelements');

													if ($setup4_membersettings_paymentsystem == 2) {
														$sccval .= esc_html__('Thanks for your payment. PayFast payment process completed. Please wait for auto page refresh.','pointfindercoreelements');
														$this->pf_redirect($setup4_membersettings_dashboard_link.'/?ua=myitems');
													}

												}else{
													$errorval .= esc_html__('Unfortunately PayFast payment process not completed.','pointfindercoreelements');
												}



											}
										/**
										*End: PayFast Process
										**/


										/**
										*Start: 2Checkout Process
										**/
											if (isset($_GET['credit_card_processed'])) {

												$order_id = esc_attr($_GET['custom_orderpid']);

												$robo_check2 = get_post_meta( sanitize_text_field($order_id), 'pointfinder_order_t2co', true );

												if (!empty($robo_check2)) {

													$t2cho_mode = PFPGIssetControl('2cho_mode','',0);
										            $t2cho_uid = PFPGIssetControl('2cho_key3','','');
										            $t2cho_sw = PFPGIssetControl('2cho_key4','','');
										            $t2cho_ordpre = PFPGIssetControl('2cho_ordpre','','PINTFNDR');
										    		/* Verification */
													$hashTotal = $_GET['total'];
													$hashOrder = $_GET['order_number'];
													if ($t2cho_mode == 0) {
														//$hashOrder = 1;
													}
													$StringToHash = strtoupper(md5($t2cho_sw . $t2cho_uid . $hashOrder . $hashTotal));

													if ($StringToHash != $_GET['key']) {
														$result = false;
														} else {
														$result = true;
													}

													if ($result) {


														if (esc_attr( $_GET['credit_card_processed'] ) == 'Y') {
															$sccval .= esc_html__('Thanks for your payment. 2Checkout payment process completed. ','pointfindercoreelements');
														}else{
															$errorval .= esc_html__('Unfortunately 2Checkout payment process not completed.','pointfindercoreelements');
														}

														if (!empty($order_id)) {
															$order_id = esc_attr($_GET['custom_orderpid']);
															$otype = esc_attr($_GET['custom_otype']);
															$user_id = esc_attr($_GET['custom_uid']);
															$item_post_id = (isset($_GET['custom_itempid']))?esc_attr($_GET['custom_itempid']):'';

															$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

															$this->pointfinder_directpayment_success_process(
																array(
																	'paymentsystem' => $setup4_membersettings_paymentsystem,
															        'item_post_id' => $item_post_id,
															        'order_post_id' => $order_id,
															        'otype' => $otype,
															        'user_id' => $user_id,
																	'paymentsystem_name' => esc_html__("2Checkout","pointfindercoreelements"),
																	'checkout_process_name' => 'DoExpressCheckoutPaymentRobo'
																)
															);

															delete_post_meta( sanitize_text_field($order_id), 'pointfinder_order_t2co');

															if ($setup4_membersettings_paymentsystem == 2) {
																$sccval .= esc_html__('Thanks for your payment. 2Checkout payment process completed. Please wait for auto page refresh.','pointfindercoreelements');

																$this->pf_redirect($setup4_membersettings_dashboard_link.'/?ua=myitems');
															}
														}
													}
												}
											}
										/**
										*End: 2Checkout Process
										**/



										/**
										*Start: Pagseguro Check Item
										**/
											if (isset($_GET['transaction_id'])) {
										    	$pags_transaction_id = $_GET['transaction_id'];
										        try {

										            $pags_credentials = PagSeguroConfig::getAccountCredentials();
										            $pags_transaction = PagSeguroTransactionSearchService::searchByCode($pags_credentials, $pags_transaction_id);

										            $pags_status = $pags_transaction->getStatus()->getValue();
										            $pags_reference = $pags_transaction->getreference();

										            if (isset($pags_reference)) {
										            	$pags_reference_exp = explode("-", $pags_reference);
										            	if(count($pags_reference_exp) == 2){
										            		$order_id = $pags_reference_exp[0];
										            		$otype = $pags_reference_exp[1];
										            	}elseif (count($pags_reference_exp) == 1) {
										            		$order_id = $pags_reference_exp[0];
										            		$otype = 0;
										            	}
										            }

										            if (!empty($order_id)) {
										            	if (in_array($pags_status, array(1,2))) {
										            		/* Cancel */
										            		if ($setup4_membersettings_paymentsystem == 2) {
										            			/* Membership */
										            			update_post_meta( $order_id, 'pointfinder_order_pagscheck', 1);
										            		}else{
										            			/* Pay per post*/
										            			if ($otype != 1) {
										            				global $wpdb;
											            			$item_post_id = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s and post_id = %s", 'pointfinder_order_itemid',$order_id));
																	$status_of_post = get_post_status($order_id);

																	if($status_of_post == 'pendingpayment'){
																		$wpdb->UPDATE($wpdb->posts,array('post_status' => 'pendingapproval'),array('ID' => $item_post_id));
																	}
																	switch (intval($pags_status)) {
																		case 1:
																				$this->PFCreateProcessRecord(
																					array(
																			        'user_id' => $user_id,
																			        'item_post_id' => $item_post_id,
																					'processname' => esc_html__('PagSeguro: The buyer initiated the transaction, but so far the PagSeguro not received any payment information.','pointfindercoreelements')
																				    )
																				);
																				$sccval .= esc_html__('PagSeguro: Your payment under review. Please wait until approval.','pointfindercoreelements');
																			break;

																		case 2:
																				$this->PFCreateProcessRecord(
																					array(
																			        'user_id' => $user_id,
																			        'item_post_id' => $item_post_id,
																					'processname' => esc_html__('PagSeguro: Payment under review.','pointfindercoreelements')
																				    )
																				);
																				$sccval .= esc_html__('PagSeguro: Your payment under review. Please wait until approval.','pointfindercoreelements');
																			break;
																	}

										            			}
										            		}
										            	}
										            }

										        } catch (PagSeguroServiceException $e) {/*$e->getMessage();*/}
										    }
										/**
										*End: Pagseguro Check Item
										**/


										/**
										*Start: Payu Process
										**/
											if (isset($_GET['payu'])) {

												$payu_mihd = (isset($_POST["mihpayid"]))?$_POST["mihpayid"]:'';
												$payu_status = (isset($_POST["status"]))?$_POST["status"]:'';
												$payu_firstname = (isset($_POST["firstname"]))?$_POST["firstname"]:'';
												$payu_amount = (isset($_POST["amount"]))?$_POST['amount']:'';
												$payu_txnid = (isset($_POST["txnid"]))?$_POST['txnid']:'';
												$payu_posted_hash = (isset($_POST["hash"]))?$_POST['hash']:'';
												$payu_key = (isset($_POST["key"]))?$_POST['key']:'';
												$payu_productinfo = (isset($_POST["productinfo"]))?$_POST['productinfo']:'';
												$payu_email = (isset($_POST["email"]))?$_POST['email']:'';
												$order_id = (isset($_POST["udf1"]))?$_POST['udf1']:'';
												$otype = (isset($_POST["udf2"]))?$_POST['udf2']:'';
												$item_post_id = (isset($_POST["udf3"]))?$_POST['udf3']:'';
												$payu_salt = PFPGIssetControl('payu_salt','','');

												if (!empty($payu_mihd) && !empty($payu_status)) {
													if (!empty($payu_posted_hash)) {
														if (isset($_POST["additionalCharges"])) {
															$additionalCharges=$_POST["additionalCharges"];
															$retHashSeq = $additionalCharges.'|'.$payu_salt.'|'.$payu_status.'||||||||'.$item_post_id.'|'.$otype.'|'.$order_id.'|'.$payu_email.'|'.$payu_firstname.'|'.$payu_productinfo.'|'.$payu_amount.'|'.$payu_txnid.'|'.$payu_key;
														}else{
															$retHashSeq = $payu_salt.'|'.$payu_status.'||||||||'.$item_post_id.'|'.$otype.'|'.$order_id.'|'.$payu_email.'|'.$payu_firstname.'|'.$payu_productinfo.'|'.$payu_amount.'|'.$payu_txnid.'|'.$payu_key;
														}
														$retHashSeq2 = hash("sha512", $retHashSeq);

														if ($payu_posted_hash == $retHashSeq2) {

															switch ($payu_status) {
																case 'success':
																		$check_process = get_post_meta( $order_id, 'pointfinder_order_txnid', true );
																		if (!empty($check_process)) {
																			$this->pointfinder_directpayment_success_process(
																				array(
																					'paymentsystem' => $setup4_membersettings_paymentsystem,
																			        'item_post_id' => $item_post_id,
																			        'order_post_id' => $order_id,
																			        'otype' => $otype,
																			        'user_id' => $user_id,
																					'paymentsystem_name' => esc_html__("PayU Money","pointfindercoreelements"),
																					'checkout_process_name' => 'DoExpressCheckoutPaymentPayu'
																				)
																			);
																			$sccval .= esc_html__('Thanks for your payment. PayU Money payment process completed. Please wait for auto page refresh.','pointfindercoreelements');

																			$this->pf_redirect($setup4_membersettings_dashboard_link.'/?ua=myitems');
																		}

																	break;

																case 'pending':
																		if ($setup4_membersettings_paymentsystem == 2) {
																			$this->PFCreateProcessRecord(
															                  array(
															                    'user_id' => $user_id,
															                    'item_post_id' => $order_post_id,
															                    'processname' => esc_html__('Payu Money: Payment pending.','pointfindercoreelements').'ID:'.$_REQUEST['mihpayid'],
															                    'membership' => 1
															                    )
															                );
																		}else{
														            		$this->PFCreateProcessRecord(
																				array(
																		        'user_id' => $user_id,
																		        'item_post_id' => $item_post_id,
																				'processname' => esc_html__('Payu Money: Payment pending.','pointfindercoreelements').'ID:'.$_REQUEST['mihpayid']
																			    )
																			);
														            	}
														            	$sccval .= esc_html__('Thank you for shopping with us. Right now your payment status is pending.','pointfindercoreelements');
																	break;

																case 'failure':
																		$payu_error = (isset($_POST["error"]))?$_POST['error']:'';
																		$payu_error_Message = (isset($_POST["error_Message"]))?$_POST['error_Message']:'';
																		if ($setup4_membersettings_paymentsystem == 2) {
																			$this->PFCreateProcessRecord(
															                  array(
															                    'user_id' => $user_id,
															                    'item_post_id' => $order_post_id,
															                    'processname' => esc_html__('Payu Money: Payment canceled.','pointfindercoreelements').' - '.$payu_error.' - '.$payu_error_Message.' - ID:'.$_REQUEST['mihpayid'],
															                    'membership' => 1
															                    )
															                );
																		}else{
														            		$this->PFCreateProcessRecord(
																				array(
																		        'user_id' => $user_id,
																		        'item_post_id' => $item_post_id,
																				'processname' => esc_html__('Payu Money: Payment canceled.','pointfindercoreelements').' - '.$payu_error.' - '.$payu_error_Message.' - ID:'.$_REQUEST['mihpayid']
																			    )
																			);
														            	}
														            	$errorval .= esc_html__('Thank you for shopping with us. However, the transaction has been declined.','pointfindercoreelements');
																	break;
															}
														}else{
															if (!empty($errorval)) {
																$errorval .= "<br>";
															}
															$errorval .= esc_html__("Invalid Transaction. Please try again","pointfindercoreelements");
														}
													}
												}
											}
										/**
										*End: Payu Process
										**/


										/**
										*Start: iDeal Process
										**/
											if (isset($_GET['il'])) {
												$ideal_check = get_post_meta( sanitize_text_field($_GET['il']), 'pointfinder_order_ideal', true );

												if (!empty($ideal_check)) {
													$ideal_id = PFPGIssetControl('ideal_id','','');
										          	$mollie = new Mollie_API_Client;
										          	$mollie->setApiKey($ideal_id);

										          	$payment  = $mollie->payments->get($ideal_check);
										          	$status = $payment->status;

										          	if (isset($status)) {
										          		if ($payment->isPaid()){
														    $sccval .= esc_html__('Thanks for your payment. iDeal payment process completed.','pointfindercoreelements');
														}elseif (! $payment->isOpen()){
														    $errorval .= esc_html__('Unfortunately iDeal payment process not completed.','pointfindercoreelements');
														}

														delete_post_meta( sanitize_text_field($_GET['il']), 'pointfinder_order_ideal');
										          	}
												}
											}
										/**
										*End: iDeal Process
										**/


										/**
										*Start: Robokassa Process
										**/
											if (isset($_GET['ro'])) {

												$robo_pass1 = PFPGIssetControl('robo_pass1','','');

												global $wpdb;

												
												if ($setup4_membersettings_paymentsystem == 2) {
													$InvId = absint($_POST["InvId"]);
													$order_id = absint($_POST["Shp_oid"]);
													$item_post_id = '';
												}else{
													$item_post_id = absint($_POST["Shp_itemnum"]);
													$order_id = $wpdb->get_var( $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s", 'pointfinder_order_roboitemid',$item_post_id));
												}
												if (!empty($order_id)) {
													$out_summ = esc_attr($_POST["OutSum"]);
													$otype = esc_attr($_POST["Shp_otype"]);
													$robo_crc = isset($_POST["SignatureValue"])?esc_attr($_POST["SignatureValue"]):'';

													$inv_id_random = get_post_meta($order_id, 'pointfinder_order_roborinvid',true);

													$robo_check = get_post_meta( $order_id, 'pointfinder_order_robo', true );

													if (esc_attr($_GET['ro']) == 's' && !empty($robo_check)) {

														$robo_crc = strtoupper($robo_crc);

														if ($setup4_membersettings_paymentsystem == 2) {
															if ($otype == 'r') {
																$membership_user_package_id = get_user_meta( $user_id, 'membership_user_package_id', true );
															}else{
																$membership_user_package_id = '';
															}
		
															$robo_new_crc = strtoupper(md5("$out_summ:$InvId:$robo_pass1:Shp_itemnum=$membership_user_package_id:Shp_oid=$order_id:Shp_otype=$otype:Shp_user=$user_id"));
														}else{
															$robo_new_crc = strtoupper(md5("$out_summ:$inv_id_random:$robo_pass1:Shp_itemnum=$item_post_id:Shp_otype=$otype:Shp_user=$user_id"));
														}

														if ($robo_new_crc == $robo_crc){
															$sccval .= esc_html__('Thanks for your payment. Robokassa payment process completed. Please wait for auto page refresh...','pointfindercoreelements');
															$this->pf_redirect($setup4_membersettings_dashboard_link.'/?ua=myitems');
														}else{
															$errorval .= esc_html__('Unfortunately Robokassa payment process not completed. 2','pointfindercoreelements');
														}


													}elseif (esc_attr($_GET['ro']) == 'f' && !empty($robo_check)){
														/* Cancel */
														if ($setup4_membersettings_paymentsystem == 2) {
															$this->PFCreateProcessRecord(
											                  array(
											                    'user_id' => $user_id,
											                    'item_post_id' => $order_id,
											                    'processname' => esc_html__('Robokassa: Payment canceled.','pointfindercoreelements'),
											                    'membership' => 1
											                    )
											                );
														}else{
										            		$this->PFCreateProcessRecord(
																array(
														        'user_id' => $user_id,
														        'item_post_id' => $item_post_id,
																'processname' => esc_html__('Robokassa: Payment canceled.','pointfindercoreelements')
															    )
															);
										            	}
										            	$errorval .= esc_html__('Unfortunately Robokassa payment process not completed.','pointfindercoreelements');
													}

													delete_post_meta( $order_id, 'pointfinder_order_robo');
												}
											}
										/**
										*End: Robokassa Process
										**/



										/**
										*Start: Iyzico Process
										**/
											if (isset($_POST['token'])) {

												$iyzico_key1 = PFPGIssetControl('iyzico_key1','','');
												$iyzico_key2 = PFPGIssetControl('iyzico_key2','','');
												$iyzico_mode = PFPGIssetControl('iyzico_mode','','0');

												if ($iyzico_mode == 1) {
												$api_url = 'https://api.iyzipay.com/';
												}else{
												$api_url = 'https://sandbox-api.iyzipay.com/';
												}

												IyzipayBootstrap::init();

												$options = new \Iyzipay\Options();
												$options->setApiKey($iyzico_key1);
												$options->setSecretKey($iyzico_key2);
												$options->setBaseUrl($api_url);

												$request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
												$request->setLocale(\Iyzipay\Model\Locale::TR);
												$request->setToken($_POST['token']);

												$checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($request, $options);
												$iyzico_status = $checkoutForm->getpaymentStatus();
												$iyzico_status = (!empty($iyzico_status))?$iyzico_status:'FAILURE';

												global $wpdb;

						            			if ($setup4_membersettings_paymentsystem == 2) {
						            				$iyzico_order_id = get_user_meta( $user_id, 'membership_user_activeorder_ex',true);
						            			} else {
						            				$iyzico_order_id = $wpdb->get_var( $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s", 'pointfinder_order_iyzicotoken',$_POST['token']));
						            			}

						            			if (!empty($iyzico_order_id)) {

							            			if ($setup4_membersettings_paymentsystem == 2) {
							            				$iyzico_item_post_id = get_user_meta( $user_id, 'membership_user_package_id_ex',true);
			              								$iyzico_otype = get_user_meta( $user_id, 'membership_user_subaction_ex',true);
							            			} else {
							            				$iyzico_item_post_id = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s and post_id = %s", 'pointfinder_order_itemid',$iyzico_order_id));
							            			}


													if ($iyzico_status == 'SUCCESS') {

														$iyzico_otype = get_post_meta( $iyzico_order_id, 'pointfinder_order_iyzicootype', true );


														if (empty($iyzico_otype)) {
															$iyzico_otype = 0;
														}

														$this->pointfinder_directpayment_success_process(
															array(
																'paymentsystem' => $setup4_membersettings_paymentsystem,
														        'item_post_id' => $iyzico_item_post_id,
														        'order_post_id' => $iyzico_order_id,
														        'otype' => $iyzico_otype,
														        'user_id' => $user_id,
																'paymentsystem_name' => esc_html__("Iyzico","pointfindercoreelements"),
																'checkout_process_name' => 'DoExpressCheckoutPaymentIyzico'
															)
														);

														if ($setup4_membersettings_paymentsystem == 2) {
															delete_user_meta($user_id, 'membership_user_package_id_ex');
												            delete_user_meta($user_id, 'membership_user_activeorder_ex');
												            delete_user_meta($user_id, 'membership_user_subaction_ex');

												            $sccval .= esc_html__('Thanks for your payment. Payment process completed. Please wait for auto page refresh...','pointfindercoreelements');

																		$this->pf_redirect($setup4_membersettings_dashboard_link.'/?ua=myitems');
												        }else{
												        	$sccval .= esc_html__('Thanks for your payment. Payment process completed.','pointfindercoreelements');
												        }


														delete_post_meta($iyzico_order_id, 'pointfinder_order_iyzicotoken' );


													}else{


														if ($setup4_membersettings_paymentsystem == 2) {
															$this->PFCreateProcessRecord(
											                  array(
											                    'user_id' => $user_id,
											                    'item_post_id' => $iyzico_item_post_id,
											                    'processname' => esc_html__('Iyzico: Payment canceled.','pointfindercoreelements').' - '.$checkoutForm->geterrorCode().' - '.$checkoutForm->geterrorMessage().' - Error Group:'.$checkoutForm->geterrorGroup(),
											                    'membership' => 1
											                    )
											                );
														}else{
										            		$this->PFCreateProcessRecord(
																array(
														        'user_id' => $user_id,
														        'item_post_id' => $iyzico_item_post_id,
																'processname' => esc_html__('Iyzico: Payment canceled.','pointfindercoreelements').' - '.$checkoutForm->geterrorCode().' - '.$checkoutForm->geterrorMessage().' - Error Group:'.$checkoutForm->geterrorGroup()
															    )
															);
										            	}
										            	$errorval .= esc_html__('Thank you for shopping with us. However, the transaction has been declined.','pointfindercoreelements');

													}
												}
											}
										/**
										*End: Iyzico Process
										**/

										/**
										*Start: My Items Form Request
										**/
											$redirectval = false;
											if(isset($_GET)){
												if (isset($_GET['action'])) {
													$action_ofpage = esc_attr($_GET['action']);

													/**
													* Process for Membership System
													**/

														/**
														*Start:Response Membership Package
														**/

															if ($action_ofpage == 'pf_recm') {


																if($user_id != 0){

																	if (isset($_GET['token'])) {
																		global $wpdb;

																		/*Check token*/
																		$order_post_id = $wpdb->get_var( $wpdb->prepare(
																			"SELECT post_id FROM $wpdb->postmeta WHERE meta_value = %s and meta_key = %s",
																			esc_attr($_GET['token']),
																			'pointfinder_order_token'
																		) );


																		$package_post_id = $item_post_id = $wpdb->get_var( $wpdb->prepare(
																			"SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s and post_id = %s",
																			'pointfinder_order_packageid',
																			$order_post_id
																		) );

																		$result = $wpdb->get_results( $wpdb->prepare(
																			"SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s",
																			$package_post_id,
																			$user_id,
																			$setup3_pointposttype_pt1
																		) );



																		if (!empty($package_post_id) && !empty($order_post_id)) {

																				$paypal_price_unit = $this->PFSAIssetControl('setup20_paypalsettings_paypal_price_unit','','USD');
																				$paypal_sandbox = $this->PFSAIssetControl('setup20_paypalsettings_paypal_sandbox','','0');
																				$paypal_api_user = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_user','','');
																				$paypal_api_pwd = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_pwd','','');
																				$paypal_api_signature = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_signature','','2');

																				$setup20_paypalsettings_decimals = $this->PFSAIssetControl('setup20_paypalsettings_decimals','','2');


																				$packageinfo = $this->pointfinder_membership_package_details_get($package_post_id);
																				$apipackage_name = $packageinfo['webbupointfinder_mp_title'];
																				$infos = array();
																				$infos['USER'] = $paypal_api_user;
																				$infos['PWD'] = $paypal_api_pwd;
																				$infos['SIGNATURE'] = $paypal_api_signature;

																				if($paypal_sandbox == 1){$sandstatus = true;}else{$sandstatus = false;}

																				$paypal = new Paypal($infos,$sandstatus);

																				$tokenparams = array(
																				   'TOKEN' => esc_attr($_GET['token']),
																				);

																				$response = $paypal -> request('GetExpressCheckoutDetails',$tokenparams);



																				if (is_array($response)) {

																					if(isset($response['CHECKOUTSTATUS'])){

																						if($response['CHECKOUTSTATUS'] != 'PaymentActionCompleted'){

																							/*Create a payment record for this process */
																							$this->PF_CreatePaymentRecord(
																								array(
																									'user_id'	=>	$user_id,
																									'order_post_id'	=> $order_post_id,
																									'response'	=>	$response,
																									'token'	=>	$response['TOKEN'],
																									'payerid'	=>	$response['PAYERID'],
																									'processname'	=>	'GetExpressCheckoutDetails',
																									'status'	=>	$response['ACK'],
																									'membership' => 1
																									)
																							);

																							/*Check Payer id check for hack*/
																							if($response['ACK'] == 'Success' &&  esc_attr($_GET['PayerID'] == $response['PAYERID'])){

																								$setup20_paypalsettings_paypal_verified = $this->PFSAIssetControl('setup20_paypalsettings_paypal_verified','','0');

																								if ($setup20_paypalsettings_paypal_verified == 1) {
																									if($response['PAYERSTATUS'] == 'verified'){
																										$work_status = 'accepted';
																									}else{
																										$work_status = 'declined';
																									}
																								}else{
																									$work_status = 'accepted';
																								}

																								if ($work_status == 'accepted') {

																									if(isset($response['CUSTOM'])){
																										$custom_val_ex = explode(',', $response['CUSTOM']);
																										$process_type = $custom_val_ex[1];
																									}else{
																										$process_type = 'n';
																									}

																									$newpackage_id = (isset($response['PAYMENTREQUEST_0_NOTETEXT']))?$response['PAYMENTREQUEST_0_NOTETEXT']:0;
																									if (!empty($newpackage_id)) {
																										$packageinfo_n = $this->pointfinder_membership_package_details_get($newpackage_id);
																									}else{$packageinfo_n = $packageinfo;}
																									$pointfinder_order_pricesign = esc_attr(get_post_meta( $order_post_id, 'pointfinder_order_pricesign', true ));
																									$pointfinder_order_listingtime = esc_attr(get_post_meta( $order_post_id, 'pointfinder_order_listingtime', true ));
																									$pointfinder_order_price = esc_attr(get_post_meta( $order_post_id, 'pointfinder_order_price', true ));
																									$pointfinder_order_recurring = esc_attr(get_post_meta( $order_post_id, 'pointfinder_order_recurring', true ));
																									$pointfinder_order_listingtime = ($pointfinder_order_listingtime == '') ? 0 : $pointfinder_order_listingtime ;
																									$pointfinder_order_listingpid = esc_attr(get_post_meta($order_post_id, 'pointfinder_order_listingpid', true ));

																									if ($process_type == 'u') {
																										$total_package_price =  number_format($packageinfo_n['webbupointfinder_mp_price'], $setup20_paypalsettings_decimals, '.', ',');
																									} else {
																										$total_package_price =  number_format($packageinfo['webbupointfinder_mp_price'], $setup20_paypalsettings_decimals, '.', ',');
																									}

																									$user_info = get_userdata( $user_id );


																									$admin_email = get_option( 'admin_email' );
													 												$setup33_emailsettings_mainemail = PFMSIssetControl('setup33_emailsettings_mainemail','',$admin_email);



																									if ($pointfinder_order_recurring == 1) {
																										/**
																										*Start : Recurring Payment Process
																										**/
																											/** Express Checkout **/
																											$expresspay_paramsr = array(
																												'TOKEN' => $response['TOKEN'],
																												'PAYERID' => $response['PAYERID'],
																												'PAYMENTREQUEST_0_AMT' => $total_package_price,
																												'PAYMENTREQUEST_0_CURRENCYCODE' => $paypal_price_unit,
																												'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
																											);

																											$response_expressr = $paypal -> request('DoExpressCheckoutPayment',$expresspay_paramsr);

																											if (isset($response_expressr['TOKEN'])) {
																												$tokenr = $response_expressr['TOKEN'];
																											}else{
																												$tokenr = '';
																											}
																											/*Create a payment record for this process */
																											$this->PF_CreatePaymentRecord(
																													array(
																													'user_id'	=>	$user_id,
																													'order_post_id'	=> $order_post_id,
																													'response'	=>	$response_expressr,
																													'token'	=>	$tokenr,
																													'processname'	=>	'DoExpressCheckoutPayment',
																													'status'	=>	$response_expressr['ACK'],
																													'membership' => 1
																													)
																												);

																											if($response_expressr['ACK'] == 'Success'){

																												if(isset($response_expressr['PAYMENTINFO_0_PAYMENTSTATUS'])){
																													if ($response_expressr['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed' || $response_expressr['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed_Funds_Held') {

																														switch ($process_type) {
																															case 'n':
																																$exp_date = strtotime("+".$packageinfo['webbupointfinder_mp_billing_period']." ".$this->pointfinder_billing_timeunit_text_ex($packageinfo['webbupointfinder_mp_billing_time_unit'])."");
																																$app_date = strtotime("now");

																																update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
																																update_post_meta( $order_post_id, 'pointfinder_order_datetime_approval', $app_date);
																																update_post_meta( $order_post_id, 'pointfinder_order_bankcheck', 0);


																												                $wpdb->update($wpdb->posts,array('post_status'=>'completed'),array('ID'=>$order_post_id));

																												                /*Create User Limits*/
																												                update_user_meta( $user_id, 'membership_user_package_id', $packageinfo['webbupointfinder_mp_packageid']);
																												                update_user_meta( $user_id, 'membership_user_package', $packageinfo['webbupointfinder_mp_title']);
																												                update_user_meta( $user_id, 'membership_user_item_limit', $packageinfo['webbupointfinder_mp_itemnumber']);
																												                update_user_meta( $user_id, 'membership_user_featureditem_limit', $packageinfo['webbupointfinder_mp_fitemnumber']);
																												                update_user_meta( $user_id, 'membership_user_image_limit', $packageinfo['webbupointfinder_mp_images']);
																												                update_user_meta( $user_id, 'membership_user_trialperiod', 0);
																												                update_user_meta( $user_id, 'membership_user_recurring', 0);
																												                update_user_meta( $user_id, 'membership_user_activeorder', $order_post_id);

																												                /* Create an invoice for this */
																													              $this->PF_CreateInvoice(
																													                array(
																													                  'user_id' => $user_id,
																													                  'item_id' => 0,
																													                  'order_id' => $order_post_id,
																													                  'description' => $packageinfo['webbupointfinder_mp_title'],
																													                  'processname' => esc_html__('Paypal Payment','pointfindercoreelements'),
																													                  'amount' => $packageinfo['packageinfo_priceoutput_text'],
																													                  'datetime' => strtotime("now"),
																													                  'packageid' => $packageinfo['webbupointfinder_mp_packageid'],
																													                  'status' => 'publish'
																													                )
																													              );
																																break;

																															case 'u':
																																if (!empty($newpackage_id)) {

																																	$exp_date = $this->pointfinder_reenable_expired_items(array('user_id'=>$user_id,'packageinfo'=>$packageinfo_n,'order_id'=>$order_post_id,'process'=>'u'));
																																	$app_date = strtotime("now");

																																	update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
																																	update_post_meta( $order_post_id, 'pointfinder_order_datetime_approval', $app_date);
																																	update_post_meta( $order_post_id, 'pointfinder_order_bankcheck', 0);
																																	update_post_meta( $order_post_id, 'pointfinder_order_packageid', $newpackage_id);

																													                $wpdb->update($wpdb->posts,array('post_status'=>'completed'),array('ID'=>$order_post_id));

																													                /* Start: Calculate item/featured item count and remove from new package. */
																												                        $total_icounts = $this->pointfinder_membership_count_ui($user_id);

																												                        /*Count User's Items*/
																												                        $user_post_count = 0;
																												                        $user_post_count = $total_icounts['item_count'];

																												                        /*Count User's Featured Items*/
																												                        $users_post_featured = 0;
																												                        $users_post_featured = $total_icounts['fitem_count'];

																												                        if ($packageinfo_n['webbupointfinder_mp_itemnumber'] != -1) {
																												                          $new_item_limit = $packageinfo_n['webbupointfinder_mp_itemnumber'] - $user_post_count;
																												                        }else{
																												                          $new_item_limit = $packageinfo_n['webbupointfinder_mp_itemnumber'];
																												                        }

																												                        $new_fitem_limit = $packageinfo_n['webbupointfinder_mp_fitemnumber'] - $users_post_featured;


																												                        /*Create User Limits*/
																												                        update_user_meta( $user_id, 'membership_user_package_id', $packageinfo_n['webbupointfinder_mp_packageid']);
																												                        update_user_meta( $user_id, 'membership_user_package', $packageinfo_n['webbupointfinder_mp_title']);
																												                        update_user_meta( $user_id, 'membership_user_item_limit', $new_item_limit);
																												                        update_user_meta( $user_id, 'membership_user_featureditem_limit', $new_fitem_limit);
																												                        update_user_meta( $user_id, 'membership_user_image_limit', $packageinfo_n['webbupointfinder_mp_images']);
																												                        update_user_meta( $user_id, 'membership_user_trialperiod', 0);
																												                        update_user_meta( $user_id, 'membership_user_activeorder', $order_post_id);
																												                        update_user_meta( $user_id, 'membership_user_recurring', 0);
																												                     /* End: Calculate new limits */

																												                     /* Create an invoice for this */
																														              $this->PF_CreateInvoice(
																														                array(
																														                  'user_id' => $user_id,
																														                  'item_id' => 0,
																														                  'order_id' => $order_post_id,
																														                  'description' => $packageinfo_n['webbupointfinder_mp_title'].'-'.esc_html__('Upgrade','pointfindercoreelements'),
																														                  'processname' => esc_html__('Paypal Payment','pointfindercoreelements'),
																														                  'amount' => $packageinfo_n['packageinfo_priceoutput_text'],
																														                  'datetime' => strtotime("now"),
																														                  'packageid' => $packageinfo_n['webbupointfinder_mp_packageid'],
																														                  'status' => 'publish'
																														                )
																														              );

																																}

																																break;

																															case 'r':
																																$exp_date = $this->pointfinder_reenable_expired_items(array('user_id'=>$user_id,'packageinfo'=>$packageinfo,'order_id'=>$order_post_id,'process'=>'r'));
																																$app_date = strtotime("now");

																																update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
																																update_post_meta( $order_post_id, 'pointfinder_order_datetime_approval', $app_date);

																												                $wpdb->update($wpdb->posts,array('post_status'=>'completed'),array('ID'=>$order_post_id));

																												                /* Create an invoice for this */
																													              $this->PF_CreateInvoice(
																													                array(
																													                  'user_id' => $user_id,
																													                  'item_id' => 0,
																													                  'order_id' => $order_post_id,
																													                  'description' => $packageinfo['webbupointfinder_mp_title'].'-'.esc_html__('Renew','pointfindercoreelements'),
																													                  'processname' => esc_html__('Paypal Payment','pointfindercoreelements'),
																													                  'amount' => $packageinfo['packageinfo_priceoutput_text'],
																													                  'datetime' => strtotime("now"),
																													                  'packageid' => $packageinfo['webbupointfinder_mp_packageid'],
																													                  'status' => 'publish'
																													                )
																													              );

																																break;
																														}
																													}
																												}

																												if ($process_type == 'u') {
																													$this->pointfinder_mailsystem_mailsender(
																														array(
																															'toemail' => $user_info->user_email,
																													        'predefined' => 'paymentcompletedmember',
																													        'data' => array('paymenttotal' => $packageinfo_n['packageinfo_priceoutput_text'],'packagename' => $packageinfo_n['webbupointfinder_mp_title']),
																															)
																														);

																													$this->pointfinder_mailsystem_mailsender(
																														array(
																															'toemail' => $setup33_emailsettings_mainemail,
																													        'predefined' => 'newpaymentreceivedmember',
																													        'data' => array('ID' => $order_post_id,'paymenttotal' => $packageinfo_n['packageinfo_priceoutput_text'],'packagename' => $packageinfo_n['webbupointfinder_mp_title']),
																															)
																														);
																												}else{
																													$this->pointfinder_mailsystem_mailsender(
																														array(
																															'toemail' => $user_info->user_email,
																													        'predefined' => 'paymentcompletedmember',
																													        'data' => array('paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],'packagename' => $apipackage_name),
																															)
																														);

																													$this->pointfinder_mailsystem_mailsender(
																														array(
																															'toemail' => $setup33_emailsettings_mainemail,
																													        'predefined' => 'newpaymentreceivedmember',
																													        'data' => array('ID' => $order_post_id,'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],'packagename' => $apipackage_name),
																															)
																														);
																												}


																												$sccval .= esc_html__('Thanks for your payment. Please wait while redirecting...','pointfindercoreelements');
																												$redirectval = true;
																												/*Start : Creating Recurring Payment*/
																												$timestamp_forprofile = strtotime("+".$packageinfo['webbupointfinder_mp_billing_period']." ".$this->pointfinder_billing_timeunit_text_ex($packageinfo['webbupointfinder_mp_billing_time_unit'])."");

																												$billing_description = sprintf(
																								                  esc_html__('%s / %s / Recurring: %s per %s','pointfindercoreelements'),
																								                  $packageinfo['webbupointfinder_mp_title'],
																								                  $packageinfo['packageinfo_itemnumber_output_text'].' '.esc_html__('Item','pointfindercoreelements'),
																								                  $packageinfo['packageinfo_priceoutput_text'],
																								                  $packageinfo['webbupointfinder_mp_billing_period'].' '.$packageinfo['webbupointfinder_mp_billing_time_unit_text']
																								                 );

																												$recurringpay_params = array(
																													'TOKEN' => $response_expressr['TOKEN'],
																													'PAYERID' => $response['PAYERID'],
																													'PROFILESTARTDATE' => date("Y-m-d\TH:i:s\Z",$timestamp_forprofile),
																													'DESC' => $billing_description,
																													'BILLINGPERIOD' => $this->pointfinder_billing_timeunit_text_paypal($packageinfo['webbupointfinder_mp_billing_time_unit']),
																													'BILLINGFREQUENCY' => $packageinfo['webbupointfinder_mp_billing_period'],
																													'AMT' => $total_package_price,
																													'CURRENCYCODE' => $paypal_price_unit,
																													'MAXFAILEDPAYMENTS' => 1
																												);

																												$item_arr_rec = array(
																												   'L_PAYMENTREQUEST_0_NAME0' => $packageinfo['webbupointfinder_mp_title'],
																												   'L_PAYMENTREQUEST_0_AMT0' => $total_package_price,
																												   'L_PAYMENTREQUEST_0_QTY0' => '1',
																												   //'L_PAYMENTREQUEST_0_ITEMCATEGORY0'	=> 'Digital',
																												);

																												$response_recurring = $paypal -> request('CreateRecurringPaymentsProfile',$recurringpay_params,$item_arr_rec);
																												unset($paypal);
																												/*Create a payment record for this process */
																												$this->PF_CreatePaymentRecord(
																														array(
																														'user_id'	=>	$user_id,
																														'order_post_id'	=> $order_post_id,
																														'response'	=>	$response_recurring,
																														'token'	=>	$response_expressr['TOKEN'],
																														'processname'	=>	'CreateRecurringPaymentsProfile',
																														'status'	=>	$response_recurring['ACK'],
																														'membership' => 1
																														)

																													);


																													if($response_recurring['ACK'] == 'Success'){

																														update_post_meta($order_post_id, 'pointfinder_order_recurringid', $response_recurring['PROFILEID'] );
																														update_post_meta($order_post_id, 'pointfinder_order_recurring', 1 );
																														update_user_meta($user_id, 'membership_user_recurring', 1);


																														$this->pointfinder_mailsystem_mailsender(
																															array(
																																'toemail' => $user_info->user_email,
																														        'predefined' => 'recprofilecreatedmember',
																														        'data' => array('title'=>get_the_title($order_post_id),'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],'packagename' => $apipackage_name,'nextpayment' => date("Y-m-d", strtotime("+".$pointfinder_order_listingtime." days")),'profileid' => $response_recurring['PROFILEID']),
																																)
																															);

																														$this->pointfinder_mailsystem_mailsender(
																															array(
																																'toemail' => $setup33_emailsettings_mainemail,
																														        'predefined' => 'recurringprofilecreatedmember',
																														        'data' => array(
																														        	'ID' => $user_id,
																														        	'title'=>get_the_title($order_post_id),
																														        	'orderid'=>$order_post_id,
																														        	'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],
																														        	'packagename' => $apipackage_name,
																														        	'nextpayment' => date("Y-m-d", strtotime("+".$pointfinder_order_listingtime." days")),
																														        	'profileid' => $response_recurring['PROFILEID']),
																																)
																															);

																														$sccval .= esc_html__('Recurring payment profile created.','pointfindercoreelements');
																													}else{

																														update_post_meta($order_post_id, 'pointfinder_order_recurring', 0 );
																														$errorval .= esc_html__('Error: Recurring profile creation is failed. Recurring payment option cancelled.','pointfindercoreelements');
																													}

																													/*End : Creating Recurring Payment*/

																											}else{

																												$errorval .= esc_html__('Sorry: The operation could not be completed. Recurring profile creation is failed and payment process could not completed.','pointfindercoreelements').'<br>';
																												if (isset($response_expressr['L_SHORTMESSAGE0'])) {
																													$errorval .= '<br>'.esc_html__('Paypal Message:','pointfindercoreelements').' '.$response_expressr['L_SHORTMESSAGE0'];
																												}
																												if (isset($response_expressr['L_LONGMESSAGE0'])) {
																													$errorval .= '<br>'.esc_html__('Paypal Message Details:','pointfindercoreelements').' '.$response_expressr['L_LONGMESSAGE0'];
																												}
																											}

																											/** Express Checkout **/

																										/**
																										*End : Recurring Payment Process
																										**/

																									}else{
																										/**
																										*Start : Express Payment Process
																										**/

																											$expresspay_params = array(
																												'TOKEN' => $response['TOKEN'],
																												'PAYERID' => $response['PAYERID'],
																												'PAYMENTREQUEST_0_AMT' => $total_package_price,
																												'PAYMENTREQUEST_0_CURRENCYCODE' => $paypal_price_unit,
																												'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
																											);

																											$response_express = $paypal -> request('DoExpressCheckoutPayment',$expresspay_params);

																											unset($paypal);




																											/*Create a payment record for this process */
																											if (isset($response_express['TOKEN'])) {
																												$token = $response_express['TOKEN'];
																											}else{
																												$token = '';
																											}
																											$this->PF_CreatePaymentRecord(
																													array(
																													'user_id'	=>	$user_id,
																													'order_post_id'	=> $order_post_id,
																													'response'	=>	$response_express,
																													'token'	=>	$token,
																													'processname'	=>	'DoExpressCheckoutPayment',
																													'status'	=>	$response_express['ACK']
																													)
																												);


																											if($response_express['ACK'] == 'Success'){

																												if(isset($response_express['PAYMENTINFO_0_PAYMENTSTATUS'])){
																													if ($response_express['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed' || $response_expressr['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed_Funds_Held') {
																														switch ($process_type) {
																															case 'n':
																																$exp_date = strtotime("+".$packageinfo['webbupointfinder_mp_billing_period']." ".$this->pointfinder_billing_timeunit_text_ex($packageinfo['webbupointfinder_mp_billing_time_unit'])."");
																																$app_date = strtotime("now");

																																update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
																																update_post_meta( $order_post_id, 'pointfinder_order_datetime_approval', $app_date);
																																update_post_meta( $order_post_id, 'pointfinder_order_bankcheck', 0);


																												                $wpdb->update($wpdb->posts,array('post_status'=>'completed'),array('ID'=>$order_post_id));

																												                /*Create User Limits*/
																												                update_user_meta( $user_id, 'membership_user_package_id', $packageinfo['webbupointfinder_mp_packageid']);
																												                update_user_meta( $user_id, 'membership_user_package', $packageinfo['webbupointfinder_mp_title']);
																												                update_user_meta( $user_id, 'membership_user_item_limit', $packageinfo['webbupointfinder_mp_itemnumber']);
																												                update_user_meta( $user_id, 'membership_user_featureditem_limit', $packageinfo['webbupointfinder_mp_fitemnumber']);
																												                update_user_meta( $user_id, 'membership_user_image_limit', $packageinfo['webbupointfinder_mp_images']);
																												                update_user_meta( $user_id, 'membership_user_trialperiod', 0);
																												                update_user_meta( $user_id, 'membership_user_recurring', 0);
																												                update_user_meta( $user_id, 'membership_user_activeorder', $order_post_id);

																												                /* Create an invoice for this */
																													              $this->PF_CreateInvoice(
																													                array(
																													                  'user_id' => $user_id,
																													                  'item_id' => 0,
																													                  'order_id' => $order_post_id,
																													                  'description' => $packageinfo['webbupointfinder_mp_title'],
																													                  'processname' => esc_html__('Paypal Payment','pointfindercoreelements'),
																													                  'amount' => $packageinfo['packageinfo_priceoutput_text'],
																													                  'datetime' => strtotime("now"),
																													                  'packageid' => $packageinfo['webbupointfinder_mp_packageid'],
																													                  'status' => 'publish'
																													                )
																													              );
																																break;

																															case 'u':

																																if (!empty($newpackage_id)) {

																																	$exp_date = $this->pointfinder_reenable_expired_items(array('user_id'=>$user_id,'packageinfo'=>$packageinfo_n,'order_id'=>$order_post_id,'process'=>'u'));

																																	$app_date = strtotime("now");

																																	update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
																																	update_post_meta( $order_post_id, 'pointfinder_order_datetime_approval', $app_date);
																																	update_post_meta( $order_post_id, 'pointfinder_order_bankcheck', 0);
																																	update_post_meta( $order_post_id, 'pointfinder_order_packageid', $newpackage_id);

																													                $wpdb->update($wpdb->posts,array('post_status'=>'completed'),array('ID'=>$order_post_id));

																													                /* Start: Calculate item/featured item count and remove from new package. */
																												                        $total_icounts = $this->pointfinder_membership_count_ui($user_id);

																												                        /*Count User's Items*/
																												                        $user_post_count = 0;
																												                        $user_post_count = $total_icounts['item_count'];

																												                        /*Count User's Featured Items*/
																												                        $users_post_featured = 0;
																												                        $users_post_featured = $total_icounts['fitem_count'];

																												                        if ($packageinfo_n['webbupointfinder_mp_itemnumber'] != -1) {
																												                          $new_item_limit = $packageinfo_n['webbupointfinder_mp_itemnumber'] - $user_post_count;
																												                        }else{
																												                          $new_item_limit = $packageinfo_n['webbupointfinder_mp_itemnumber'];
																												                        }

																												                        $new_fitem_limit = $packageinfo_n['webbupointfinder_mp_fitemnumber'] - $users_post_featured;


																												                        /*Create User Limits*/
																												                        update_user_meta( $user_id, 'membership_user_package_id', $packageinfo_n['webbupointfinder_mp_packageid']);
																												                        update_user_meta( $user_id, 'membership_user_package', $packageinfo_n['webbupointfinder_mp_title']);
																												                        update_user_meta( $user_id, 'membership_user_item_limit', $new_item_limit);
																												                        update_user_meta( $user_id, 'membership_user_featureditem_limit', $new_fitem_limit);
																												                        update_user_meta( $user_id, 'membership_user_image_limit', $packageinfo_n['webbupointfinder_mp_images']);
																												                        update_user_meta( $user_id, 'membership_user_trialperiod', 0);
																												                        update_user_meta( $user_id, 'membership_user_activeorder', $order_post_id);
																												                        update_user_meta( $user_id, 'membership_user_recurring', 0);
																												                    /* End: Calculate new limits */

																												                    /* Create an invoice for this */
																														              $this->PF_CreateInvoice(
																														                array(
																														                  'user_id' => $user_id,
																														                  'item_id' => 0,
																														                  'order_id' => $order_post_id,
																														                  'description' => $packageinfo_n['webbupointfinder_mp_title'].'-'.esc_html__('Upgrade','pointfindercoreelements'),
																														                  'processname' => esc_html__('Paypal Payment','pointfindercoreelements'),
																														                  'amount' => $packageinfo_n['packageinfo_priceoutput_text'],
																														                  'datetime' => strtotime("now"),
																														                  'packageid' => $packageinfo_n['webbupointfinder_mp_packageid'],
																														                  'status' => 'publish'
																														                )
																														              );

																																}
																																break;

																															case 'r':
																																$exp_date = $this->pointfinder_reenable_expired_items(array('user_id'=>$user_id,'packageinfo'=>$packageinfo,'order_id'=>$order_post_id,'process'=>'r'));
																																$app_date = strtotime("now");

																																update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
																																update_post_meta( $order_post_id, 'pointfinder_order_datetime_approval', $app_date);

																												                $wpdb->update($wpdb->posts,array('post_status'=>'completed'),array('ID'=>$order_post_id));
																												                /* Create an invoice for this */
																													              $this->PF_CreateInvoice(
																													                array(
																													                  'user_id' => $user_id,
																													                  'item_id' => 0,
																													                  'order_id' => $order_post_id,
																													                  'description' => $packageinfo['webbupointfinder_mp_title'].'-'.esc_html__('Renew','pointfindercoreelements'),
																													                  'processname' => esc_html__('Paypal Payment','pointfindercoreelements'),
																													                  'amount' => $packageinfo['packageinfo_priceoutput_text'],
																													                  'datetime' => strtotime("now"),
																													                  'packageid' => $packageinfo['webbupointfinder_mp_packageid'],
																													                  'status' => 'publish'
																													                )
																													              );
																																break;
																														}
																													}
																												}


																												if ($process_type == 'u') {
																													$this->pointfinder_mailsystem_mailsender(
																														array(
																															'toemail' => $user_info->user_email,
																													        'predefined' => 'paymentcompletedmember',
																													        'data' => array('paymenttotal' => $packageinfo_n['packageinfo_priceoutput_text'],'packagename' => $packageinfo_n['webbupointfinder_mp_title']),
																															)
																														);

																													$this->pointfinder_mailsystem_mailsender(
																														array(
																															'toemail' => $setup33_emailsettings_mainemail,
																													        'predefined' => 'newpaymentreceivedmember',
																													        'data' => array('ID' => $order_post_id,'paymenttotal' => $packageinfo_n['packageinfo_priceoutput_text'],'packagename' => $packageinfo_n['webbupointfinder_mp_title']),
																															)
																														);
																												}else{
																													$this->pointfinder_mailsystem_mailsender(
																														array(
																															'toemail' => $user_info->user_email,
																													        'predefined' => 'paymentcompletedmember',
																													        'data' => array('paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],'packagename' => $apipackage_name),
																															)
																														);

																													$this->pointfinder_mailsystem_mailsender(
																														array(
																															'toemail' => $setup33_emailsettings_mainemail,
																													        'predefined' => 'newpaymentreceivedmember',
																													        'data' => array('ID' => $order_post_id,'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],'packagename' => $apipackage_name),
																															)
																														);
																												}


																												$sccval .= esc_html__('Thanks for your payment. Please wait while redirecting...','pointfindercoreelements');
																												$redirectval = true;
																											}else{
																												$errorval .= esc_html__('Sorry: The operation could not be completed. Payment is failed.','pointfindercoreelements').'<br>';
																												if (isset($response_express['L_SHORTMESSAGE0'])) {
																													$errorval .= '<br>'.esc_html__('Paypal Message:','pointfindercoreelements').' '.$response_express['L_SHORTMESSAGE0'];
																												}
																												if (isset($response_express['L_LONGMESSAGE0'])) {
																													$errorval .= '<br>'.esc_html__('Paypal Message Details:','pointfindercoreelements').' '.$response_express['L_LONGMESSAGE0'];
																												}
																											}

																										/**
																										*End : Express Payment Process
																										**/
																									}




																								}else{
																									$errorval .= esc_html__('Sorry: Our payment system only accepts verified Paypal Users. Payment is failed.','pointfindercoreelements');
																								}

																							}else{
																								$errorval .= esc_html__('Can not get express checkout informations. Payment is failed.','pointfindercoreelements');
																							}
																						}elseif($response['CHECKOUTSTATUS'] == 'PaymentActionCompleted'){
																							$sccval .= esc_html__('Payment Completed.','pointfindercoreelements').'';
																						}else{
																							$errorval .= esc_html__('Response could not be received. Payment is failed.','pointfindercoreelements').'(1)';
																						}
																					}else{
																						$errorval .= esc_html__('Response could not be received. Payment is failed.','pointfindercoreelements').'(2)';
																					}

																				}else{
																					$errorval .= esc_html__('Response could not be received. Payment is failed.','pointfindercoreelements');
																				}

																		}

																	}else{
																		$errorval .= esc_html__('Need token value.','pointfindercoreelements');
																	}

																}else{
																    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindercoreelements');
															  	}
															}

														/**
														*End:Response Membership Package
														**/

														/**
														*Start:Bank Transfer Membership
														**/

															if ($action_ofpage == 'pf_pay2m') {

																if($user_id != 0){
															        $order_post_id = get_user_meta( $user_id, 'membership_user_activeorder_ex', true );
																	$sccval .= esc_html__('Bank Transfer Process; Waiting payment...','pointfindercoreelements');
																}else{
																    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindercoreelements');
															  	}

															  	/**
																*Start: Bank Transfer Page Content
																**/
																	$output = new PF_Frontend_Fields(
																			array(
																				'formtype' => 'banktransfer',
																				'sccval' => $sccval,
																				'errorval' => $errorval,
																				'post_id' => get_the_title($order_post_id),

																			)
																		);
																	echo $output->FieldOutput;
																	break;
																/**
																*End: Bank Transfer Page Content
																**/
															}
														/**
														*End:Bank Transfer Membership
														**/

														/**
														*Start:Cancel Bank Transfer Membership
														**/

															if ($action_ofpage == 'cancelbankm') {

																if($user_id != 0){
															        $order_post_id = get_user_meta( $user_id, 'membership_user_activeorder_ex', true );

															        update_post_meta( $order_post_id, 'pointfinder_order_bankcheck', 0);

															        delete_user_meta($user_id, 'membership_user_package_id_ex');
													                delete_user_meta($user_id, 'membership_user_activeorder_ex');
													                delete_user_meta($user_id, 'membership_user_subaction_ex');
													                delete_user_meta($user_id, 'membership_user_invnum_ex');

													                $this->PFCreateProcessRecord(
													                  array(
													                    'user_id' => $user_id,
													                    'item_post_id' => $order_post_id,
													                    'processname' => esc_html__('Bank Transfer Cancelled by User','pointfindercoreelements'),
													                    'membership' => 1
													                    )
													                );

													                /*Create email record for this*/
																	$user_info = get_userdata( $user_id );
																	$this->pointfinder_mailsystem_mailsender(
																		array(
																			'toemail' => $user_info->user_email,
																	        'predefined' => 'bankpaymentcancelmember',
																	        'data' => array('ID' => $order_post_id),
																			)
																		);

																	$sccval .= esc_html__('Bank Transfer Cancelled. Redirecting...','pointfindercoreelements');
																	$redirectval = true;
																}else{
																    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindercoreelements');
															  	}

															}
														/**
														*End:Cancel Bank Transfer Membership
														**/

													/**
													* Process for Membership System
													**/






													/**
													* Process for Basic Listing
													**/

														/**
														*Start:Extend free listing
														**/
															if ($action_ofpage == 'pf_extend') {
																$stp31_userfree = $this->PFSAIssetControl("stp31_userfree","","0");

																if ($stp31_userfree == 1) {
																	if($user_id != 0){

																		$item_post_id = (is_numeric($_GET['i']))? esc_attr($_GET['i']):'';

																		if ($item_post_id != '') {

																			/*Check if item user s item*/
																			global $wpdb;

																			$result = $wpdb->get_results( $wpdb->prepare(
																				"SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s",
																				$item_post_id,
																				$user_id,
																				$setup3_pointposttype_pt1
																			) );



																			if (is_array($result) && count($result)>0) {

																				if ($result[0]->ID == $item_post_id) {

																					/*Meta for order*/
																					global $wpdb;
																					$result_id = $wpdb->get_var( $wpdb->prepare(
																						"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s",
																						'pointfinder_order_itemid',
																						$item_post_id
																					) );

																					$status_of_post = get_post_status($item_post_id);

																					$pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
																					if ($status_of_post == 'pendingpayment' && $pointfinder_order_price == 0) {
																						/*Extend listing*/
																						$pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_order_listingtime', true ));


																	        			//$old_expire_date = get_post_meta( $result_id, 'pointfinder_order_expiredate', true);
																								$today = date("Y-m-d H:i:s");

																	        			$exp_date = date("Y-m-d H:i:s",strtotime($today .'+'.$pointfinder_order_listingtime.' day'));
																						$app_date = date("Y-m-d H:i:s");

																						update_post_meta( $result_id, 'pointfinder_order_expiredate', $exp_date);
																						update_post_meta( $result_id, 'pointfinder_order_datetime_approval', $app_date);

																						$wpdb->update($wpdb->posts,array('post_status'=>'publish'),array('ID'=>$item_post_id));
																						$wpdb->update($wpdb->posts,array('post_status'=>'completed'),array('ID'=>$result_id));

																						$this->PFCreateProcessRecord(
																							array(
																					        'user_id' => $user_id,
																					        'item_post_id' => $item_post_id,
																							'processname' => sprintf(esc_html__('Expire date extended by User (Free Listing): (Order Date: %s / Expire Date: %s)','pointfindercoreelements'),
																								$app_date,
																								$exp_date
																								)
																						    )
																						);
																						$sccval .= esc_html__('Item expire date extended.','pointfindercoreelements');
																						$this->pf_redirect($setup4_membersettings_dashboard_link.$pfmenu_perout."ua=myitems");
																					}else{
																						$errorval .= esc_html__('Item could not extend.','pointfindercoreelements');
																					}


																				}else{
																					$errorval .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindercoreelements');
																				}
																			}
																		}else{
																			$errorval .= esc_html__('Wrong item ID.','pointfindercoreelements');
																		}
																	}else{
																	    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindercoreelements');
																  	}
																}
															}
														/**
														*End:Extend Free Listing
														**/


														/**
														*Start:Bank Transfer
														**/

															if ($action_ofpage == 'pf_pay2') {

																if($user_id != 0){

																	$item_post_id = (is_numeric($_GET['i']))? esc_attr($_GET['i']):'';

																	if ($item_post_id != '') {

																		/*Check if item user s item*/
																		global $wpdb;

																		$result = $wpdb->get_results( $wpdb->prepare(
																			"SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s",
																			$item_post_id,
																			$user_id,
																			$setup3_pointposttype_pt1
																		) );



																		if (is_array($result) && count($result)>0) {

																			if ($result[0]->ID == $item_post_id) {

																				/*Meta for order*/
																				global $wpdb;
																				$result_id = $wpdb->get_var( $wpdb->prepare(
																					"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s",
																					'pointfinder_order_itemid',
																					$item_post_id
																				) );

																				$pointfinder_order_recurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_recurring', true ));

																				$pointfinder_order_frecurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_frecurring', true ));

																				if($pointfinder_order_recurring != 1 && $pointfinder_order_frecurring != 1){

																					update_post_meta($result_id, 'pointfinder_order_bankcheck', '1');



																					/*Create a payment record for this process */
																					$this->PF_CreatePaymentRecord(
																						array(
																						'user_id'	=>	$user_id,
																						'item_post_id'	=>	$item_post_id,
																						'order_post_id'	=>	$result_id,
																						'processname'	=>	'BankTransfer',
																						)
																					);

																					/*Create email record for this*/
																					$user_info = get_userdata( $user_id );
																					$mail_item_title = get_the_title($item_post_id);

																					$setup20_paypalsettings_decimals = $this->PFSAIssetControl('setup20_paypalsettings_decimals','','2');
																					$pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));

																					$total_package_price =  number_format($pointfinder_order_price, $setup20_paypalsettings_decimals, '.', ',');

																					$pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true ));
																					$pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpname', true ));

																					$paymentName = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindercoreelements'));

																					$apipackage_name = $pointfinder_order_listingpname;


																					/* Create an invoice for this */
																					$invoice_id = $this->PF_CreateInvoice(
																						array(
																						  'user_id' => $user_id,
																						  'item_id' => $item_post_id,
																						  'order_id' => $result_id,
																						  'description' => $apipackage_name,
																						  'processname' => esc_html__('Bank Payment','pointfindercoreelements'),
																						  'amount' => $pointfinder_order_price,
																						  'datetime' => strtotime("now"),
																						  'packageid' => 0,
																						  'status' => 'pendingpayment'
																						)
																					);
																					update_post_meta($result_id, 'pointfinder_order_invoice', $invoice_id);

																					$this->pointfinder_mailsystem_mailsender(
																						array(
																						'toemail' => $user_info->user_email,
																				        'predefined' => 'bankpaymentwaiting',
																				        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name),
																						)
																					);

																					$admin_email = get_option( 'admin_email' );
														 							$setup33_emailsettings_mainemail = PFMSIssetControl('setup33_emailsettings_mainemail','',$admin_email);
																					$this->pointfinder_mailsystem_mailsender(
																						array(
																							'toemail' => $setup33_emailsettings_mainemail,
																					        'predefined' => 'newbankpreceived',
																					        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name),
																							)
																						);

																					$sccval .= esc_html__('Bank Transfer Process; Completed','pointfindercoreelements');
																				}else{
																					$errorval .= esc_html__('Recurring Payment Orders not accepted for bank transfer.','pointfindercoreelements');
																				}
																			}else{
																				$errorval .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindercoreelements');
																			}
																		}
																	}else{
																		$errorval .= esc_html__('Wrong item ID.','pointfindercoreelements');
																	}
																}else{
																    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindercoreelements');
															  	}

															  	/**
																*Start: Bank Transfer Page Content
																**/

																	$output = new PF_Frontend_Fields(
																			array(
																				'formtype' => 'banktransfer',
																				'sccval' => $sccval,
																				'errorval' => $errorval,
																				'post_id' => $item_post_id
																			)
																		);
																	echo $output->FieldOutput;
																	break;
																/**
																*End: Bank Transfer Page Content
																**/
															}
														/**
														*End:Bank Transfer
														**/


														/**
														*Start:Cancel Bank Transfer
														**/

															if ($action_ofpage == 'pf_pay2c') {

																if($user_id != 0){

																	$item_post_id = (is_numeric($_GET['i']))? esc_attr($_GET['i']):'';

																	if ($item_post_id != '') {

																		/*Check if item user s item*/
																		global $wpdb;

																		$result = $wpdb->get_results( $wpdb->prepare(
																			"SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s",
																			$item_post_id,
																			$user_id,
																			$setup3_pointposttype_pt1
																		) );


																		if (is_array($result) && count($result)>0) {

																			if ($result[0]->ID == $item_post_id) {

																				/*Meta for order*/
																				global $wpdb;
																				$result_id = $wpdb->get_var( $wpdb->prepare(
																					"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s",
																					'pointfinder_order_itemid',
																					$item_post_id
																				) );

																				update_post_meta($result_id, 'pointfinder_order_bankcheck', '0');
																				delete_post_meta( $result_id, 'pointfinder_order_invoice');

																				/*Create a payment record for this process */
																				$this->PF_CreatePaymentRecord(
																						array(
																						'user_id'	=>	$user_id,
																						'item_post_id'	=>	$item_post_id,
																						'order_post_id'	=>	$result_id,
																						'processname'	=>	'BankTransferCancel',
																						)
																					);

																				/*Create email record for this*/
																				$user_info = get_userdata( $user_id );
																				$mail_item_title = get_the_title($item_post_id);
																				$this->pointfinder_mailsystem_mailsender(
																					array(
																						'toemail' => $user_info->user_email,
																				        'predefined' => 'bankpaymentcancel',
																				        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title),
																						)
																					);


																				$sccval .= esc_html__('Bank Transfer Process; Cancelled','pointfindercoreelements');

																			}else{
																				$errorval .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindercoreelements');
																			}
																		}
																	}else{
																		$errorval .= esc_html__('Wrong item ID.','pointfindercoreelements');
																	}
																}else{
																    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindercoreelements');
															  	}


															}

														/**
														*End:Cancel Bank Transfer
														**/


														/**
														*Start:Response Basic Listing
														**/

															if ($action_ofpage == 'pf_rec') {


																if($user_id != 0){

																	if (isset($_GET['token'])) {
																		global $wpdb;
																		$otype = 0;

																		/*Check token*/
																		$order_post_id = $wpdb->get_var( $wpdb->prepare(
																			"SELECT post_id FROM $wpdb->postmeta WHERE meta_value = %s and meta_key = %s",
																			esc_attr($_GET['token']),
																			'pointfinder_order_token'
																		) );
																			/* Check if sub order */
																			if (empty($order_post_id)) {
																				$order_post_id = $wpdb->get_var( $wpdb->prepare(
																					"SELECT post_id FROM $wpdb->postmeta WHERE meta_value = %s and meta_key = %s",
																					esc_attr($_GET['token']),
																					'pointfinder_sub_order_token'
																				) );
																				if (!empty($order_post_id)) {
																					$otype = 1;
																				}
																			}


																		$item_post_id = $wpdb->get_var( $wpdb->prepare(
																			"SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s and post_id = %s",
																			'pointfinder_order_itemid',
																			$order_post_id
																		) );

																		$result = $wpdb->get_results( $wpdb->prepare(
																			"SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s",
																			$item_post_id,
																			$user_id,
																			$setup3_pointposttype_pt1
																		) );



																		if (is_array($result) && count($result)>0) {

																			if ($result[0]->ID == $item_post_id) {


																				$paypal_price_unit = $this->PFSAIssetControl('setup20_paypalsettings_paypal_price_unit','','USD');
																				$paypal_sandbox = $this->PFSAIssetControl('setup20_paypalsettings_paypal_sandbox','','0');
																				$paypal_api_user = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_user','','');
																				$paypal_api_pwd = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_pwd','','');
																				$paypal_api_signature = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_signature','','2');

																				$setup20_paypalsettings_decimals = $this->PFSAIssetControl('setup20_paypalsettings_decimals','','2');

																				$infos = array();
																				$infos['USER'] = $paypal_api_user;
																				$infos['PWD'] = $paypal_api_pwd;
																				$infos['SIGNATURE'] = $paypal_api_signature;

																				if($paypal_sandbox == 1){$sandstatus = true;}else{$sandstatus = false;}

																				$paypal = new Paypal($infos,$sandstatus);

																				$tokenparams = array(
																				   'TOKEN' => esc_attr($_GET['token']),
																				);

																				$response = $paypal -> request('GetExpressCheckoutDetails',$tokenparams);


																				if (is_array($response)) {

																						if(isset($response['CHECKOUTSTATUS'])){

																							if($response['CHECKOUTSTATUS'] != 'PaymentActionCompleted'){
																								
																								/*Create a payment record for this process */
																								$this->PF_CreatePaymentRecord(
																									array(
																										'user_id'	=>	$user_id,
																										'item_post_id'	=>	$item_post_id,
																										'order_post_id'	=> $order_post_id,
																										'response'	=>	$response,
																										'token'	=>	$response['TOKEN'],
																										'payerid'	=>	$response['PAYERID'],
																										'processname'	=>	'GetExpressCheckoutDetails',
																										'status'	=>	$response['ACK']
																										)
																								);


																								/*Check Payer id*/
																								if($response['ACK'] == 'Success' &&  esc_attr($_GET['PayerID'] == $response['PAYERID'])){

																									$setup20_paypalsettings_paypal_verified = $this->PFSAIssetControl('setup20_paypalsettings_paypal_verified','','0');

																									if ($setup20_paypalsettings_paypal_verified == 1) {
																										if($response['PAYERSTATUS'] == 'verified'){
																											$work_status = 'accepted';
																										}else{
																											$work_status = 'declined';
																										}
																									}else{
																										$work_status = 'accepted';
																									}

																									if ($work_status == 'accepted') {

																										$result_id = $order_post_id;

																										$pointfinder_sub_order_change = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_change', true ));

			              																				if ($pointfinder_sub_order_change == 1 && $otype == 1 ) {

																											$pointfinder_order_pricesign = esc_attr(get_post_meta( $result_id, 'pointfinder_order_pricesign', true ));
																											$pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_listingtime', true ));
																											$pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_price', true ));
																											$pointfinder_order_listingtime = ($pointfinder_order_listingtime == '') ? 0 : $pointfinder_order_listingtime ;

																											$pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpid', true ));
																											$pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpname', true ));


																											$total_package_price = number_format($pointfinder_order_price, $setup20_paypalsettings_decimals, '.', ',');

																											$paymentName = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindercoreelements'));

																											$apipackage_name = $pointfinder_order_listingpname. esc_html__('(Plan/Featured/Category Change)','pointfindercoreelements');

															 												/* Create an invoice for this */
																											$this->PF_CreateInvoice(
																												array(
																												  'user_id' => $user_id,
																												  'item_id' => $item_post_id,
																												  'order_id' => $result_id,
																												  'description' => $apipackage_name,
																												  'processname' => esc_html__('Paypal Payment','pointfindercoreelements'),
																												  'amount' => $total_package_price,
																												  'datetime' => strtotime("now"),
																												  'packageid' => 0,
																												  'status' => 'publish'
																												)
																											);

																											/**
																											*Start : Express Payment Process
																											**/

																												$expresspay_params = array(
																													'TOKEN' => $response['TOKEN'],
																													'PAYERID' => $response['PAYERID'],
																													'PAYMENTREQUEST_0_AMT' => $total_package_price,
																													'PAYMENTREQUEST_0_CURRENCYCODE' => $paypal_price_unit,
																													'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
																												);

																												$response_express = $paypal -> request('DoExpressCheckoutPayment',$expresspay_params);
																												/*print_r($response_express);*/
																												unset($paypal);


																													/*Create a payment record for this process */
																													if (isset($response_express['TOKEN'])) {
																														$token = $response_express['TOKEN'];
																													}else{
																														$token = '';
																													}


																													$this->PF_CreatePaymentRecord(
																															array(
																															'user_id'	=>	$user_id,
																															'item_post_id'	=>	$item_post_id,
																															'order_post_id'	=> $order_post_id,
																															'response'	=>	$response_express,
																															'token'	=>	$token,
																															'processname'	=>	'DoExpressCheckoutPayment',
																															'status'	=>	$response_express['ACK']
																															)
																														);


																													if($response_express['ACK'] == 'Success'){

																														if(isset($response_express['PAYMENTINFO_0_PAYMENTSTATUS'])){
																															if ($response_express['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed'  || $response_expressr['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed_Funds_Held') {
																																$pointfinder_sub_order_changedvals = get_post_meta( $order_post_id, 'pointfinder_sub_order_changedvals', true );

																																$this->pointfinder_additional_orders(
																																	array(
																																		'changedvals' => $pointfinder_sub_order_changedvals,
																																		'order_id' => $order_post_id,
																																		'post_id' => $item_post_id
																																	)
																																);
																															}
																														}
																														$sccval .= esc_html__('Thanks for your payment. All changes completed.','pointfindercoreelements');

																													}else{
																														$errorval .= esc_html__('Sorry: The operation could not be completed. Payment is failed.','pointfindercoreelements').'<br>';
																														if (isset($response_express['L_SHORTMESSAGE0'])) {
																															$errorval .= '<br>'.esc_html__('Paypal Message:','pointfindercoreelements').' '.$response_express['L_SHORTMESSAGE0'];
																														}
																														if (isset($response_express['L_LONGMESSAGE0'])) {
																															$errorval .= '<br>'.esc_html__('Paypal Message Details:','pointfindercoreelements').' '.$response_express['L_LONGMESSAGE0'];
																														}
																													}

																											/**
																											*End : Express Payment Process
																											**/

																										}else{
																											$pointfinder_order_pricesign = esc_attr(get_post_meta( $result_id, 'pointfinder_order_pricesign', true ));
																											$pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_order_listingtime', true ));
																											$pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
																											$pointfinder_order_recurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_recurring', true ));
																											$pointfinder_order_listingtime = ($pointfinder_order_listingtime == '') ? 0 : $pointfinder_order_listingtime ;

																											$pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true ));
																											$pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpname', true ));


																											$total_package_price = number_format($pointfinder_order_price, $setup20_paypalsettings_decimals, '.', ',');

																											$paymentName = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindercoreelements'));

																											$apipackage_name = $pointfinder_order_listingpname;

																											$setup31_userlimits_userpublish = $this->PFSAIssetControl('setup31_userlimits_userpublish','','0');
																											$publishstatus = ($setup31_userlimits_userpublish == 1) ? 'publish' : 'pendingapproval' ;

																											$user_info = get_userdata( $user_id );
																											$mail_item_title = get_the_title($item_post_id);

																											$admin_email = get_option( 'admin_email' );
															 												$setup33_emailsettings_mainemail = PFMSIssetControl('setup33_emailsettings_mainemail','',$admin_email);

															 												/* Create an invoice for this */
																											$this->PF_CreateInvoice(
																												array(
																												  'user_id' => $user_id,
																												  'item_id' => $item_post_id,
																												  'order_id' => $result_id,
																												  'description' => $apipackage_name,
																												  'processname' => esc_html__('Paypal Payment','pointfindercoreelements'),
																												  'amount' => $total_package_price,
																												  'datetime' => strtotime("now"),
																												  'packageid' => 0,
																												  'status' => 'publish'
																												)
																											);

																											if ($pointfinder_order_recurring == 1) {
																												/**
																												*Start : Recurring Payment Process
																												**/



																													/** Express Checkout **/
																													$expresspay_paramsr = array(
																														'TOKEN' => $response['TOKEN'],
																														'PAYERID' => $response['PAYERID'],
																														'PAYMENTREQUEST_0_AMT' => $total_package_price,
																														'PAYMENTREQUEST_0_CURRENCYCODE' => $paypal_price_unit,
																														'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
																													);

																													$response_expressr = $paypal -> request('DoExpressCheckoutPayment',$expresspay_paramsr);

																													if (isset($response_expressr['TOKEN'])) {
																														$tokenr = $response_expressr['TOKEN'];
																													}else{
																														$tokenr = '';
																													}
																													/*Create a payment record for this process */
																													$this->PF_CreatePaymentRecord(
																															array(
																															'user_id'	=>	$user_id,
																															'item_post_id'	=>	$item_post_id,
																															'order_post_id'	=> $order_post_id,
																															'response'	=>	$response_expressr,
																															'token'	=>	$tokenr,
																															'processname'	=>	'DoExpressCheckoutPayment',
																															'status'	=>	$response_expressr['ACK']
																															)
																														);


																													if($response_expressr['ACK'] == 'Success'){

																														if(isset($response_expressr['PAYMENTINFO_0_PAYMENTSTATUS'])){
																															if ($response_expressr['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed' || $response_expressr['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed_Funds_Held') {
																																	wp_update_post(array('ID' => $item_post_id,'post_status' => $publishstatus) );
																																	wp_reset_postdata();
																																	wp_update_post(array('ID' => $order_post_id,'post_status' => 'completed') );
																																	wp_reset_postdata();

																																	$this->pointfinder_order_fallback_operations($order_post_id,$pointfinder_order_price);
																															}
																														}

																														$this->pointfinder_mailsystem_mailsender(
																															array(
																																'toemail' => $user_info->user_email,
																														        'predefined' => 'paymentcompleted',
																														        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name),
																																)
																															);

																														$this->pointfinder_mailsystem_mailsender(
																															array(
																																'toemail' => $setup33_emailsettings_mainemail,
																														        'predefined' => 'newpaymentreceived',
																														        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price,'packagename' => $apipackage_name),
																																)
																															);

																														$sccval .= sprintf(esc_html__('Thanks for your payment. %s Now please wait until our admin approve your payment and activate your item.','pointfindercoreelements'),'<br>');

																															/*Start : Creating Recurring Payment*/

																															/* Added with v1.6.4 */
																											                $pointfinder_order_featured = esc_attr(get_post_meta($result_id, 'pointfinder_order_featured', true));
																											                if ($pointfinder_order_featured == 1) {
																											                  $setup31_userpayments_pricefeatured = $this->PFSAIssetControl('setup31_userpayments_pricefeatured','','5');
																											                  $total_package_price_recurring = $total_package_price -  $setup31_userpayments_pricefeatured;
																											                }else{
																											                  $total_package_price_recurring = $total_package_price;
																											                }

																											                $total_package_price_recurring = number_format($total_package_price_recurring, $setup20_paypalsettings_decimals, '.', ',');


																															$timestamp_forprofile = strtotime('+ '.$pointfinder_order_listingtime.' days');

																															$recurringpay_params = array(
																																'TOKEN' => $response_expressr['TOKEN'],
																																'PAYERID' => $response['PAYERID'],
																																'PROFILESTARTDATE' => date("Y-m-d\TH:i:s\Z",$timestamp_forprofile),
																																'DESC' => sprintf(
																																	esc_html__('%s / %s / Recurring: %s%s per %s days / For: (%s)','pointfindercoreelements'),
																																	$paymentName,
																																	$apipackage_name,
																																	$total_package_price_recurring,
																																	$pointfinder_order_pricesign,
																																	$pointfinder_order_listingtime,
																																	$item_post_id
																																),
																																'BILLINGPERIOD' => 'Day',
																																'BILLINGFREQUENCY' => $pointfinder_order_listingtime,
																																'AMT' => $total_package_price_recurring,
																																'CURRENCYCODE' => $paypal_price_unit,
																																'MAXFAILEDPAYMENTS' => 1
																															);

																															$item_arr_rec = array(
																															   'L_PAYMENTREQUEST_0_NAME0' => $paymentName.' : '.$apipackage_name,
																															   'L_PAYMENTREQUEST_0_AMT0' => $total_package_price_recurring,
																															   'L_PAYMENTREQUEST_0_QTY0' => '1',
																															);


																															/*If featured package enabled create a profile for this package*/
																															if ($pointfinder_order_featured == 1) {

																																	$stp31_daysfeatured = $this->PFSAIssetControl('stp31_daysfeatured','','3');
																																	$timestamp_forprofile_featured = strtotime('+ '.$stp31_daysfeatured.' days');

																																	$setup31_userpayments_pricefeatured = number_format($setup31_userpayments_pricefeatured, $setup20_paypalsettings_decimals, '.', ',');

																																	$recurringpay_params_featured = array(
																																		'TOKEN' => $response_expressr['TOKEN'],
																																		'PAYERID' => $response['PAYERID'],
																																		'PROFILESTARTDATE' => date("Y-m-d\TH:i:s\Z",$timestamp_forprofile_featured),
																																		'DESC' => sprintf(
																																			esc_html__('%s / %s / Recurring: %s%s per %s days / For: (%s)','pointfindercoreelements'),
																																			$paymentName,
																																			esc_html__('Featured Point','pointfindercoreelements'),
																																			$setup31_userpayments_pricefeatured,
																																			$pointfinder_order_pricesign,
																																			$stp31_daysfeatured,
																																			$item_post_id
																																		),
																																		'BILLINGPERIOD' => 'Day',
																																		'BILLINGFREQUENCY' => $stp31_daysfeatured,
																																		'AMT' => $setup31_userpayments_pricefeatured,
																																		'CURRENCYCODE' => $paypal_price_unit,
																																		'MAXFAILEDPAYMENTS' => 1
																																	);
																																	if ($total_package_price_recurring > 0) {
																																		$item_arr_rec_featured = array(
																																		   'L_PAYMENTREQUEST_0_NAME1' => $paymentName.' : '.$apipackage_name,
																																		   'L_PAYMENTREQUEST_0_AMT1' => $setup31_userpayments_pricefeatured,
																																		   'L_PAYMENTREQUEST_0_QTY1' => '1',
																																		);
																																	}else{
																																		$item_arr_rec_featured = array(
																																		   'L_PAYMENTREQUEST_0_NAME0' => $paymentName.' : '.$apipackage_name,
																																		   'L_PAYMENTREQUEST_0_AMT0' => $setup31_userpayments_pricefeatured,
																																		   'L_PAYMENTREQUEST_0_QTY0' => '1',
																																		);
																																	}


																																	$response_recurring_featured = $paypal -> request('CreateRecurringPaymentsProfile',$recurringpay_params_featured,$item_arr_rec_featured);
																																	

																																	/*Create a payment record for this process */
																																	$this->PF_CreatePaymentRecord(
																																			array(
																																			'user_id'	=>	$user_id,
																																			'item_post_id'	=>	$item_post_id,
																																			'order_post_id'	=> $order_post_id,
																																			'response'	=>	$response_recurring_featured,
																																			'token'	=>	$response_expressr['TOKEN'],
																																			'processname'	=>	'CreateRecurringPaymentsProfile',
																																			'status'	=>	$response_recurring_featured['ACK']
																																			)

																																		);

																																	if($response_recurring_featured['ACK'] == 'Success'){
																																		update_post_meta($order_post_id, 'pointfinder_order_frecurringid', $response_recurring_featured['PROFILEID'] );

																																		$this->pointfinder_mailsystem_mailsender(
																																			array(
																																				'toemail' => $user_info->user_email,
																																		        'predefined' => 'recprofilecreated',
																																		        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $this->pointfinder_reformat_pricevalue_for_frontend($setup31_userpayments_pricefeatured),'packagename' => esc_html__('Featured Point','pointfindercoreelements'),'nextpayment' => date("Y-m-d", strtotime("+".$stp31_daysfeatured." days")),'profileid' => $response_recurring_featured['PROFILEID']),
																																				)
																																			);

																																		$this->pointfinder_mailsystem_mailsender(
																																			array(
																																				'toemail' => $setup33_emailsettings_mainemail,
																																		        'predefined' => 'recurringprofilecreated',
																																		        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $this->pointfinder_reformat_pricevalue_for_frontend($setup31_userpayments_pricefeatured),'packagename' => esc_html__('Featured Point','pointfindercoreelements'),'nextpayment' => date("Y-m-d", strtotime("+".$stp31_daysfeatured." days")),'profileid' => $response_recurring_featured['PROFILEID']),
																																				)
																																			);
																																		$sccval .= '<br>'.esc_html__('Recurring payment profile created for Featured Point.','pointfindercoreelements');
																																	}else{
																																		update_post_meta($order_post_id, 'pointfinder_order_frecurring', 0 );
																																		$errorval .= '<br>'.esc_html__('Error: Recurring profile creation is failed for Featured Point. Recurring payment option cancelled for featured point.','pointfindercoreelements');
																																	}
																															}

																															if ($total_package_price_recurring > 0) {
																																$response_recurring = $paypal -> request('CreateRecurringPaymentsProfile',$recurringpay_params,$item_arr_rec);
																																
																																/*Create a payment record for this process */
																																$this->PF_CreatePaymentRecord(
																																		array(
																																		'user_id'	=>	$user_id,
																																		'item_post_id'	=>	$item_post_id,
																																		'order_post_id'	=> $order_post_id,
																																		'response'	=>	$response_recurring,
																																		'token'	=>	$response_expressr['TOKEN'],
																																		'processname'	=>	'CreateRecurringPaymentsProfile',
																																		'status'	=>	$response_recurring['ACK']
																																		)

																																	);


																																if($response_recurring['ACK'] == 'Success'){

																																	update_post_meta($order_post_id, 'pointfinder_order_recurringid', $response_recurring['PROFILEID'] );

																																	$this->pointfinder_mailsystem_mailsender(
																																		array(
																																			'toemail' => $user_info->user_email,
																																	        'predefined' => 'recprofilecreated',
																																	        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $this->pointfinder_reformat_pricevalue_for_frontend($total_package_price),'packagename' => $apipackage_name,'nextpayment' => date("Y-m-d", strtotime("+".$pointfinder_order_listingtime." days")),'profileid' => $response_recurring['PROFILEID']),
																																			)
																																		);

																																	$this->pointfinder_mailsystem_mailsender(
																																		array(
																																			'toemail' => $setup33_emailsettings_mainemail,
																																	        'predefined' => 'recurringprofilecreated',
																																	        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $this->pointfinder_reformat_pricevalue_for_frontend($total_package_price),'packagename' => $apipackage_name,'nextpayment' => date("Y-m-d", strtotime("+".$pointfinder_order_listingtime." days")),'profileid' => $response_recurring['PROFILEID']),
																																			)
																																		);
																																	do_action('pointfinder_paypal_payment_success',$user_id);
																																	$sccval .= '<br>'.esc_html__('Recurring payment profile created for Listing.','pointfindercoreelements');
																																}else{

																																	update_post_meta($order_post_id, 'pointfinder_order_recurring', 0 );
																																	$errorval .= '<br>'.esc_html__('Error: Recurring profile creation is failed. Recurring payment option cancelled.','pointfindercoreelements');
																																}
																															}else{
																																update_post_meta($order_post_id, 'pointfinder_order_recurring', 0 );
																															}
																															unset($paypal);

																															/*End : Creating Recurring Payment*/

																													}else{

																														$errorval .= '<br>'.esc_html__('Sorry: The operation could not be completed. Recurring profile creation is failed and payment process could not completed.','pointfindercoreelements').'<br>';
																														if (isset($response_expressr['L_SHORTMESSAGE0'])) {
																															$errorval .= '<br>'.esc_html__('Paypal Message:','pointfindercoreelements').' '.$response_expressr['L_SHORTMESSAGE0'];
																														}
																														if (isset($response_expressr['L_LONGMESSAGE0'])) {
																															$errorval .= '<br>'.esc_html__('Paypal Message Details:','pointfindercoreelements').' '.$response_expressr['L_LONGMESSAGE0'];
																														}
																													}

																													/** Express Checkout **/

																												/**
																												*End : Recurring Payment Process
																												**/

																											}else{
																												/**
																												*Start : Express Payment Process
																												**/

																													$expresspay_params = array(
																														'TOKEN' => $response['TOKEN'],
																														'PAYERID' => $response['PAYERID'],
																														'PAYMENTREQUEST_0_AMT' => $total_package_price,
																														'PAYMENTREQUEST_0_CURRENCYCODE' => $paypal_price_unit,
																														'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
																													);

																													$response_express = $paypal -> request('DoExpressCheckoutPayment',$expresspay_params);
																													/*print_r($response_express);*/
																													unset($paypal);


																														/*Create a payment record for this process */
																														if (isset($response_express['TOKEN'])) {
																															$token = $response_express['TOKEN'];
																														}else{
																															$token = '';
																														}

																														$response_ack = isset($response_express['ACK'])? $response_express['ACK']:'';

																														$this->PF_CreatePaymentRecord(
																																array(
																																'user_id'	=>	$user_id,
																																'item_post_id'	=>	$item_post_id,
																																'order_post_id'	=> $order_post_id,
																																'response'	=>	$response_express,
																																'token'	=>	$token,
																																'processname'	=>	'DoExpressCheckoutPayment',
																																'status'	=>	$response_ack
																																)
																															);


																														if($response_ack == 'Success'){

																															if(isset($response_express['PAYMENTINFO_0_PAYMENTSTATUS'])){
																																if ($response_express['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed' || $response_expressr['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed_Funds_Held') {
																																	wp_update_post(array('ID' => $item_post_id,'post_status' => $publishstatus) );
																																	wp_reset_postdata();
																																	wp_update_post(array('ID' => $order_post_id,'post_status' => 'completed') );
																																	wp_reset_postdata();

																																	$this->pointfinder_order_fallback_operations($order_post_id,$pointfinder_order_price);

																																}
																															}

																															$this->pointfinder_mailsystem_mailsender(
																																array(
																																	'toemail' => $user_info->user_email,
																															        'predefined' => 'paymentcompleted',
																															        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $this->pointfinder_reformat_pricevalue_for_frontend($total_package_price),'packagename' => $apipackage_name),
																																	)
																																);

																															$this->pointfinder_mailsystem_mailsender(
																																array(
																																	'toemail' => $setup33_emailsettings_mainemail,
																															        'predefined' => 'newpaymentreceived',
																															        'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $this->pointfinder_reformat_pricevalue_for_frontend($total_package_price),'packagename' => $apipackage_name),
																																	)
																																);
																															do_action('pointfinder_paypal_payment_success',$user_id);
																															$sccval .= esc_html__('Thanks for your payment. Now please wait until our system approve your payment and activate your item listing.','pointfindercoreelements');
																														}else{
																															$errorval .= esc_html__('Sorry: The operation could not be completed. Payment is failed.','pointfindercoreelements').'<br>';
																															if (isset($response_express['L_SHORTMESSAGE0'])) {
																																$errorval .= '<br>'.esc_html__('Paypal Message:','pointfindercoreelements').' '.$response_express['L_SHORTMESSAGE0'];
																															}
																															if (isset($response_express['L_LONGMESSAGE0'])) {
																																$errorval .= '<br>'.esc_html__('Paypal Message Details:','pointfindercoreelements').' '.$response_express['L_LONGMESSAGE0'];
																															}
																														}

																												/**
																												*End : Express Payment Process
																												**/
																											}

																										}

																									}else{
																										$errorval .= esc_html__('Sorry: Our payment system only accepts verified Paypal Users. Payment is failed.','pointfindercoreelements');
																									}

																								}else{
																									$errorval .= esc_html__('Can not get express checkout informations. Payment is failed.','pointfindercoreelements');
																								}
																							}elseif($response['CHECKOUTSTATUS'] == 'PaymentActionCompleted'){
																								$sccval .= esc_html__('Payment Completed.','pointfindercoreelements').'';
																							}else{
																								$errorval .= esc_html__('Response could not be received. Payment is failed.','pointfindercoreelements').'(1)';
																							}
																						}else{
																							$errorval .= esc_html__('Response could not be received. Payment is failed.','pointfindercoreelements').'(2)';
																						}

																				}else{
																					$errorval .= esc_html__('Response could not be received. Payment is failed.','pointfindercoreelements');
																				}


																			}else{
																				$errorval .= esc_html__('Wrong item ID (It is not your item!). Payment process is stopped.','pointfindercoreelements');
																			}
																		}

																	}else{
																		$errorval .= esc_html__('Need token value.','pointfindercoreelements');
																	}



																}else{
																    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindercoreelements');
															  	}
															}

														/**
														*End:Response Basic Listing
														**/


														/**
														*Start:Cancel Basic Listing
														**/

															if ($action_ofpage == 'pf_cancel') {
																$returned_token = esc_attr($_GET['token']);
																if(!empty($returned_token)){
																	/*Create a payment record for this process */
																	$this->PF_CreatePaymentRecord(
																			array(
																			'user_id'	=>	$user_id,
																			'token'	=>	$returned_token,
																			'processname'	=>	'CancelPayment'
																			)
																		);
																}

																$errorval .= esc_html__('Sale process cancelled.','pointfindercoreelements');
															}

														/**
														*End:Cancel Basic Listing
														**/

													/**
													* Process Basic Listing
													**/

												}
											}


											/**
											*Start: Refine Listing
											**/
												if(isset($_GET['action'])){

													if (esc_attr($_GET['action']) == 'pf_refineitemlist') {
														/*
														$nonce = esc_attr($_POST['security']);
														if ( ! wp_verify_nonce( $nonce, 'pf_refineitemlist' ) ) {
															die( 'Security check' );
														}*/

														$vars = $_GET;

														$vars = $this->PFCleanArrayAttr('PFCleanFilters',$vars);

														if($user_id != 0){

															$output = new PF_Frontend_Fields(
																	array(
																		'formtype' => 'myitems',
																		'fields' => $vars,
																	)
																);
															echo $output->FieldOutput;
															$script_output .= '
															(function($) {
																"use strict";
																'.$output->ScriptOutput.'
															})(jQuery);';
															unset($output);
															break;

														}else{
														    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindercoreelements');
													  	}
													}
												}
											/**
											*End: Refine Listing
											**/
										/**
										*End: My Items Form Request
										**/


										/**
										*Start: My Items Page Content
										**/
				              				$membership_user_activeorder_ex = get_user_meta( $user_id, 'membership_user_activeorder_ex', true );
				              				if (!empty($membership_user_activeorder_ex)) {
				              					$pointfinder_order_pagscheck = get_post_meta( $membership_user_activeorder_ex, 'pointfinder_order_pagscheck', true );
				              				}else{
				              					$pointfinder_order_pagscheck = '';
				              				}
				              				if (!empty($pointfinder_order_pagscheck)) {
				              					if (!empty($sccval)) {
				              						$sccval .= '</br>';
				              					}
												$sccval .= esc_html__('Your order is waiting for approval. Please wait until we receive notification from PagSeguro.','pointfindercoreelements');
											}
										/**
										*End: My Items Form Request
										**/

										/**
										*Start: My Items Page Content
										**/

											$output = new PF_Frontend_Fields(
													array(
														'formtype' => 'myitems',
														'sccval' => $sccval,
														'errorval' => $errorval,
														'redirect' => $redirectval
													)
												);
											echo $output->FieldOutput;
											$script_output .= '
											(function($) {
												"use strict";
												'.$output->ScriptOutput.'
											})(jQuery);';
											wp_add_inline_script( 'theme-scriptspfm', $script_output, 'after' );
											unset($output);

										/**
										*End: My Items Page Content
										**/
										delete_user_meta( $user_id, 'paymentsugoogle' );
										break;

									case 'reviews':
										/**
										*Review Page Content
										**/
											$output = new PF_Frontend_Fields(
													array(
														'formtype' => 'reviews',
														'current_user' => $user_id
													)
												);
											echo $output->FieldOutput;
										/**
										*Review Page Content
										**/
										break;

									case 'profile':
										/**
										*Start: Profile Page Content
										**/
											$output = new PF_Frontend_Fields(
												array(
													'formtype' => 'profile',
													'current_user' => $user_id,
													'sccval' => $sccval,
													'errorval' => $errorval
												)
												);
											echo $output->FieldOutput;
											$script_output = '
											(function($) {
												"use strict";
												'.$output->ScriptOutput.'
											})(jQuery);';
											wp_add_inline_script( 'theme-scriptspfm', $script_output, 'after' );
											unset($output);
										/**
										*End: Profile Page Content
										**/
										break;

									case 'favorites':

										/**
										*Favs Page Content
										**/
											if(isset($_POST) && $_POST!='' && count($_POST)>0){

												if (esc_attr($_POST['action']) == 'pf_refinefavlist') {

													$nonce = esc_attr($_POST['security']);
													if ( ! wp_verify_nonce( $nonce, 'pf_refinefavlist' ) ) {
														die( 'Security check' );
													}

													$vars = $_POST;

													$vars = $this->PFCleanArrayAttr('PFCleanFilters',$vars);

													if($user_id != 0){

														$output = new PF_Frontend_Fields(
																array(
																	'formtype' => 'favorites',
																	'fields' => $vars,
																	'current_user' => $user_id
																)
															);
														echo $output->FieldOutput;
														$script_output = '
														(function($) {
															"use strict";
															'.$output->ScriptOutput.'
														})(jQuery);';
														wp_add_inline_script( 'theme-scriptspfm', $script_output, 'after' );
														unset($output);
														break;

													}else{
													    $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindercoreelements');
												  	}
												}
											}
										/**
										*Favs Page Content
										**/

										$output = new PF_Frontend_Fields(
													array(
														'formtype' => 'favorites',
														'current_user' => $user_id
													)
												);
											echo $output->FieldOutput;

										break;

									case 'invoices':
										/**
										*Invoices Page Content
										**/
											$output = new PF_Frontend_Fields(
													array(
														'formtype' => 'invoices',
														'current_user' => $user_id
													)
												);
											echo $output->FieldOutput;
										/**
										*Invoices Page Content
										**/
										break;
									case 'mymessages':
										/**
										*Messages Page Content
										**/
											$output = new PF_Frontend_Fields(
													array(
														'formtype' => 'mymessages',
														'current_user' => $user_id
													)
												);
											echo $output->FieldOutput;
										/**
										*Messages Page Content
										**/
										break;

								}

								/**
								*Start: Page End Actions / Divs etc...
								**/
									switch($setup29_dashboard_contents_my_page_layout) {
										case '3':
										echo $pf_ua_col_close.$pf_ua_sidebar_codes.$sidebar_output;
										echo $pf_ua_sidebar_close.$pf_ua_suffix_codes;
										break;
										case '2':
										echo $pf_ua_col_close.$pf_ua_suffix_codes;
										break;
									}


									if ($setup29_dashboard_contents_my_page_pos == 0 && $setup29_dashboard_contents_my_page != '') {
										echo $content_of_section;
									}
								/**
								*End: Page End Actions / Divs etc...
								**/

							}
							/**
							*End: Member Page Actions
							**/
					}


				}else{

				   PFLoginWidget();
				}
			}else{

				$content = get_the_content();
				if (!empty($setup4_membersettings_dashboard)) {
					if (is_page($setup4_membersettings_dashboard)) {
						$setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
						$pfmenu_perout = $this->PFPermalinkCheck();
						$this->pf_redirect(''.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=profile');
					}else{
						if(function_exists('PFGetHeaderBar') && !is_front_page()){
						  PFGetHeaderBar();
						}

						if (!has_shortcode( $content , 'vc_row' )) {
							echo '<div class="pf-blogpage-spacing pfb-top"></div>';
				            echo '<section role="main">';
				                echo '<div class="pf-container">';
				                    echo '<div class="pf-row">';
				                        echo '<div class="col-lg-12">';
				                            the_content();
				                        echo '</div>';
				                    echo '</div>';
				                echo '</div>';
				            echo '</section>';
				            echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';
						}else{
							the_content();
						}

					}
				}else{
					if(function_exists('PFGetHeaderBar') && !is_front_page()){
					  PFGetHeaderBar();
					}
					if (!has_shortcode( $content , 'vc_row' )) {
						echo '<div class="pf-blogpage-spacing pfb-top"></div>';
				        echo '<section role="main">';
				            echo '<div class="pf-container">';
				                echo '<div class="pf-row">';
				                    echo '<div class="col-lg-12">';
				                        the_content();
				                    echo '</div>';
				                echo '</div>';
				            echo '</div>';
				        echo '</section>';
				        echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';
					}else{
						the_content();
					}
				}
			}
    }

    public function profile_form_content(){
    	if(isset($_GET['ua']) && $_GET['ua']!=''){
			$ua_action = esc_attr($_GET['ua']);
		}
		if(isset($ua_action)){
			$current_user = wp_get_current_user();
			$user_id = $current_user->ID;

			$errorval = '';
			$sccval = '';
			/**
			*Start: Profile Form Request
			**/
				if(isset($_POST) && $_POST!='' && count($_POST)>0){

					if (esc_attr($_POST['action']) == 'pfget_updateuserprofile') {

						$nonce = esc_attr($_POST['security']);
						if ( ! wp_verify_nonce( $nonce, 'pfget_updateuserprofile' ) ) {
							die( 'Security check' );
						}


							$vars = $_POST;

						    $vars = $this->PFCleanArrayAttr('PFCleanFilters',$vars);

							$newupload = '';
							if($user_id != 0){

								global $wpdb;

								$usnfield = $this->PFSAIssetControl('usnfield','','1');

								// Sanitize the new username
				                $pf_username       = (isset($_POST['username']))?sanitize_user( $_POST['username'] ):'';
				                $pf_username       = esc_sql( $pf_username );
				                $pf_username_old   = esc_sql( $_POST['username_old'] );

			                    $current_user = wp_get_current_user();

			                    if ($usnfield == 1) {
			                    	if( username_exists( $pf_username ) && $current_user->user_login != $pf_username ) {
				                        /*Username already exist. */
				                    	$errorval .= esc_html__( 'Username already exist. Not changed.', 'pointfindercoreelements' );
				                    } else{
				                    	if($current_user->user_login == $pf_username_old){
					                    	if( $pf_username != $pf_username_old ) {
						                        /* Update username*/
						                        $result_username = $wpdb->query($wpdb->prepare( "UPDATE $wpdb->users SET user_login = %s WHERE user_login = %s", $pf_username, $pf_username_old ));

						                        if( $result_username === false ) {
						                            $errorval .= sprintf( esc_html__( 'A database error occurred : %s', 'pointfindercoreelements' ), $wpdb->last_error );
						                        }else{
						                        	$sccval .= sprintf( esc_html__( 'New Username : %s', 'pointfindercoreelements' ), $pf_username ).'<br/><strong>'.esc_html__('You must be login again. Now redirecting to Home Page in 3 seconds.','pointfindercoreelements').'</strong><br/> ';
						                        	$sccval .= "
													   <script type='text/javascript'>
												      (function($) {
												      'use strict';
													      $(function(){
															setTimeout(function() {
																window.location = '".esc_url(home_url("/"))."';
															}, 3000);
													      });
												      })(jQuery);
												      </script>
						                        	";
						                        }
						                    }
						                }
				                    }
			                    }


								$arg = array('ID' => $user_id);

								$arg['user_url'] = esc_url($vars['webaddr']);


								if(isset($vars['email'])){
								$arg['user_email'] = $vars['email'];
								}

								if(isset($vars['nickname'])){
								$arg['nickname'] = $vars['nickname'];
								}

								if(isset($vars['password']) && isset($vars['password2']) && $vars['password'] != '' && $vars['password2'] != ''){
									wp_set_password( $vars['password'], $user_id );
								}

								wp_update_user($arg);

								update_user_meta($user_id, 'first_name', $vars['firstname']);
								update_user_meta($user_id, 'last_name', $vars['lastname']);
								update_user_meta($user_id, 'description', $vars['descr']);
								update_user_meta($user_id, 'user_facebook', $vars['facebook']);
								update_user_meta($user_id, 'user_googleplus', $vars['googleplus']);
								update_user_meta($user_id, 'user_linkedin', $vars['linkedin']);
								update_user_meta($user_id, 'user_twitter', $vars['twitter']);
								update_user_meta($user_id, 'user_phone', $vars['phone']);
								update_user_meta($user_id, 'user_mobile', $vars['mobile']);

								if(isset($vars['vatnumber'])){update_user_meta($user_id, 'user_vatnumber', $vars['vatnumber']);}
								if(isset($vars['country'])){update_user_meta($user_id, 'user_country', $vars['country']);}
								if(isset($vars['address'])){update_user_meta($user_id, 'user_address', $vars['address']);}
								if(isset($vars['city'])){update_user_meta($user_id, 'user_city', $vars['city']);}


								do_action( 'pointfinder_additional_profile_fields_save', $user_id,$vars);

								if ( isset($_FILES['userphoto'])) {
									if ( $_FILES['userphoto']['size'] >0) {
									    $file = array(
									      'name'     => $_FILES['userphoto']['name'],
									      'type'     => $_FILES['userphoto']['type'],
									      'tmp_name' => $_FILES['userphoto']['tmp_name'],
									      'error'    => $_FILES['userphoto']['error'],
									      'size'     => $_FILES['userphoto']['size']
									    );
									    $allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');

									    if(!in_array($_FILES['userphoto']['type'], $allowed_file_types)) { // wrong file type
									      $errorval .= esc_html__("Please upload a JPG, GIF, or PNG file.<br>",'pointfindercoreelements');
									    }else{

										    $_FILES = array("userphoto" => $file);
										    foreach ($_FILES as $file => $array) {
										      $newupload = $this->pft_insert_attachment($file);
										      update_user_meta($user_id, 'user_photo', $newupload);
										    }
										}
									}
								}

								if(isset($vars['deletephoto'])){
								if($vars['deletephoto'] == 1){

								  if(wp_delete_attachment(get_user_meta( $user_id, 'user_photo',true ),true)){
								     update_user_meta($user_id, 'user_photo', '');
								     $newuploadphoto = PFCOREELEMENTSURLPUBLIC.'images/noimg.png';
								  }

								}
								}

								if($newupload != '' && !isset($newuploadphoto)){
								$newuploadphoto = wp_get_attachment_image_src( $newupload );
								$newuploadphoto = $newuploadphoto[0];
								}else{
								if(!isset($newuploadphoto)){
								  $newuploadphoto = '';
								}

								}

								$sccval .= '<strong>'.esc_html__('Your update was successful.','pointfindercoreelements').'</strong>';

							}else{
							    $errorval .= esc_html__('Please login again to update profile (Invalid UserID).','pointfindercoreelements');
						  	}


					}
				}
			/**
			*End: Profile Form Request
			**/
		}
    }
}