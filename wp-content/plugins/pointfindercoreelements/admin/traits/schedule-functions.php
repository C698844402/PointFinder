<?php

if (trait_exists('PointFinderScheduleFunctions')) {
  return;
}

/**
 * PPP packages
 */
trait PointFinderScheduleFunctions
{
    /**
	*Activate all schedule hooks
	**/
	public function pointfinder_activation_twicedaily() {
		wp_schedule_event( strtotime(date('Y-m-d H:s:i',mktime(23,59,59,date('m'),date('d'),date('Y')) )), 'twicedaily', 'pointfinder_schedule_hooks_hourly' );
	}

	public function pointfinder_activation_daily() {
		flush_rewrite_rules();/*added with v1.5.8*/
		wp_schedule_event( strtotime(date('Y-m-d H:s:i',mktime(23,59,59,date('m'),date('d'),date('Y')) )), 'daily', 'pointfinder_schedule_hooks_daily' );
	}


	public function pointfinder_activation_hourly2() {
		wp_schedule_event( strtotime(date('Y-m-d H:s:i',mktime(23,59,59,date('m'),date('d'),date('Y')) )), 'hourly', 'pointfinder_schedule_hooks_hourly2' );
	}

	/**
	*Deactivate all schedule hooks
	**/

	public function pointfinder_deactivation_daily() {
		wp_clear_scheduled_hook( 'pointfinder_schedule_hooks_daily' );
	}

	public function pointfinder_deactivation_hourly() {
		wp_clear_scheduled_hook( 'pointfinder_schedule_hooks_hourly' );
	}


	public function pointfinder_deactivation_hourly2() {
		wp_clear_scheduled_hook( 'pointfinder_schedule_hooks_hourly2' );
	}

	/**
	*Start: Membership System Schedule
	**/
		/**
		*Start: Check Expired Plans & Expire
		**/
			public function pointfinder_check_expires_member() {

				$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

				if ($setup4_membersettings_paymentsystem != 2) {
					return;
				}

				/*
				Direct payment:

					- Change order status to pending payment.
					- Change User status to expired.
					- Send an email to user.
					- Add a process record for this action.
				*/

				$exptime = strtotime("now");


				global $wpdb;
				/*
				print_r(strtotime("now"));
				echo '<br>';
				print_r($this->PFU_DateformatS(strtotime("now"),1));
				echo '<br>';
				*/

				$results = $wpdb->get_results( $wpdb->prepare(
					"SELECT p.ID, p.post_author, pm.meta_value, p.post_date FROM $wpdb->posts as p
					INNER JOIN $wpdb->postmeta as pm
						ON ( p.ID = pm.post_id )
					INNER JOIN $wpdb->postmeta as pm2
						ON ( pm.post_id = pm2.post_id )
					WHERE p.post_type = %s
					and p.post_status = %s
					and pm.meta_key = %s
					and pm.meta_value <= %s
					and pm2.meta_key = %s
					and pm2.meta_value = %d",
					'pointfindermorders',
					'completed',
					'pointfinder_order_expiredate',
					$exptime,
					'pointfinder_order_recurring',
					0
				),'OBJECT_K' );

				$setup33_emaillimits_listingexpired = $this->PFMSIssetControl('setup33_emaillimits_listingexpired','','1');

				if ($this->PFControlEmptyArr($results)) {
					foreach ($results as $result) {

						/* This is direct payment */
						$this->PFExpireItemManualMember(
							array(
							    'order_id' => $result->ID,
							    'post_author' => $result->post_author,
								'payment_type' => 'direct'
							)
						);

						if ($setup33_emaillimits_listingexpired == 1) {
							$packageid = get_post_meta($result->ID,'pointfinder_order_packageid',true );
							$packageinfo = $this->pointfinder_membership_package_details_get($packageid);
							$user_info = get_userdata( $result->post_author);
							if (isset($user_info->user_email)) {
								$this->pointfinder_mailsystem_mailsender(
									array(
									'toemail' => $user_info->user_email,
								    'predefined' => 'directafterexpiremember',
								    'data' => array(
								    	'orderid' => $result->ID,
								    	'expiredate' => $this->PFU_DateformatS($result->meta_value),
								    	'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],
								    	'packagename' => $packageinfo['webbupointfinder_mp_title']
								    	),
									)
								);
							}
							
						}


					}/*Foreach end*/
				}

			}
		/**
		*End: Check Expired Plans & Expire
		**/

		/**
		*Start: Check expiring packages and send email 1 day before.
		**/
			public function pointfinder_check_expiring_member() {

				$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

				if ($setup4_membersettings_paymentsystem != 2) {
					return;
				}

				/*
				Only for Direct payments. And this schedule will check item 1 day before expire.
				*/
				$setup33_emaillimits_listingautowarning = $this->PFMSIssetControl('setup33_emaillimits_listingautowarning','','1');
				if ($setup33_emaillimits_listingautowarning == 1) {
					$exptime = strtotime(date("Y-m-d H:s:i", strtotime("-1 day")));
					$exptime = strtotime(date('Y-m-d H:s:i',mktime(23,59,59,date('m',$exptime),date('d',$exptime),date('Y',$exptime)) ));

					$exptime2 = strtotime(date("Y-m-d H:s:i", strtotime("+1 day")));
					$exptime2 = strtotime(date('Y-m-d H:s:i',mktime(23,59,59,date('m',$exptime2),date('d',$exptime2),date('Y',$exptime2)) ));

					global $wpdb;
					$results = $wpdb->get_results( $wpdb->prepare(
						"SELECT p.ID, p.post_author, pm.meta_value, p.post_date FROM $wpdb->posts as p
						INNER JOIN $wpdb->postmeta as pm
							ON ( p.ID = pm.post_id )
						INNER JOIN $wpdb->postmeta as pm2
							ON ( pm.post_id = pm2.post_id )
						WHERE p.post_type = %s
						and p.post_status = %s
						and pm.meta_key = %s
						and pm.meta_value >= %s
						and pm.meta_value <= %s
						and pm2.meta_key = %s
						and pm2.meta_value = 0",
						"pointfindermorders",
						"completed",
						"pointfinder_order_expiredate",
						$exptime,
						$exptime2,
						"pointfinder_order_recurring"
						)
					,'OBJECT_K' );

					if ($this->PFControlEmptyArr($results)) {
						foreach ($results as $result) {

							

								$mail_ok = 0;
								$mail_info = get_post_meta( $result->ID, 'pointfinder_order_exemail',true);

								if ( !empty($mail_info) ) {

									$mail_info = json_decode($mail_info,true);
									
									if (is_array($mail_info)) {
										$mail_info_count = count($mail_info);

										if ($mail_info_count > 0) {
											$mail_info_date = (isset($mail_info[($mail_info_count-1)]['date']))? $mail_info[($mail_info_count-1)]['date'] : 0;
										}else{
											$mail_info_date = 0;
										}

										if ($mail_info_date != 0 && strtotime($mail_info_date) < strtotime(date("Y-m-d"))) {
											$mail_ok = 1;
											$mail_info[$mail_info_count] = array('date'=>date("Y-m-d"));
											delete_post_meta($result->ID, 'pointfinder_order_exemail');
											add_post_meta($result->ID, 'pointfinder_order_exemail', json_encode($mail_info) );
										}

									}

								}else{
									$mail_info = array();
									$mail_info[] = array('date'=>date("Y-m-d"));
									add_post_meta($result->ID, 'pointfinder_item_exemail', json_encode($mail_info));
									$mail_ok = 1;
								}

								if ($mail_ok == 1) {
									$packageid = get_post_meta($result->ID,'pointfinder_order_packageid',true );
									$packageinfo = $this->pointfinder_membership_package_details_get($packageid);
									$user_info = get_userdata( $result->post_author);
									if (isset($user_info->user_email)) {
										$this->pointfinder_mailsystem_mailsender(
											array(
											'toemail' => $user_info->user_email,
									        'predefined' => 'directbeforeexpiremember',
									        'data' => array(
									        	'orderid' => $result->ID,
										    	'expiredate' => $this->PFU_DateformatS($result->meta_value),
										    	'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],
										    	'packagename' => $packageinfo['webbupointfinder_mp_title']
									        	),
											)
										);
									}
									
								}


						}/*Foreach end*/
					}
				}

			}
		/**
		*End: Check expiring packages and send email 1 day before.
		**/

	/**
	*End: Membership System Schedule
	**/


	/**
	*Start: Pay per post Schedule
	**/

		/**
		*Start: Check Pending Orders and Delete if they reached waiting limit.
		**/
			public function pointfinder_clean_pending_orders() {

				$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

				if ($setup4_membersettings_paymentsystem != 1) {
					return;
				}

				/*
				Clean orders if waited longer then 10 days & if status is pending payment.
				This function will remove the old pendingpayment records from system.
				*/

				$setup31_userpayments_pendinglimit = $this->PFSAIssetControl('setup31_userpayments_pendinglimit','','10');

				if ($setup31_userpayments_pendinglimit > 0) {
					$exptime = date("Y-m-d",strtotime("-".$setup31_userpayments_pendinglimit." days"));
					global $wpdb;
					$results = $wpdb->get_results( $wpdb->prepare(
						"SELECT p.ID, p.post_author, pm.meta_value, p.post_date
						FROM $wpdb->posts as p
						INNER JOIN $wpdb->postmeta as pm
						ON ( p.ID = pm.post_id )
						WHERE p.post_type = %s and p.post_status = %s and p.post_parent = %d  and p.post_date <= %s and pm.meta_key = %s",
						'pointfinderorders',
						'pendingpayment',
						0,
						$exptime,
						'pointfinder_order_itemid'
					),'OBJECT_K' );


					if ($this->PFControlEmptyArr($results)) {
						foreach ($results as $result) {

							$removal_process = false;

							/* Check if item have expire date */
							$exp_date_item = get_post_meta($result->ID, 'pointfinder_order_expiredate',true);

							if ($exp_date_item != false) {
								if(strtotime($exp_date_item) < strtotime($exptime)){
									$removal_process = true;
								}
							}else{
								$removal_process = true;
							}

							if($removal_process == true){
								/*Delete Images First*/
								$delete_item_images = get_post_meta($result->meta_value, 'webbupointfinder_item_images');
								if(!empty($delete_item_images)){
									foreach ($delete_item_images as $item_image) {
										wp_delete_attachment(esc_attr($item_image),true);
									}
								}
								wp_delete_attachment(get_post_thumbnail_id( $result->meta_value ),true);

								/*Delete Post*/
								$delete_item_images = get_post_meta($result->meta_value, 'webbupointfinder_item_images');
								if (!empty($delete_item_images)) {
									foreach ($delete_item_images as $item_image) {
										wp_delete_attachment(esc_attr($item_image),true);
									}
								}
								wp_delete_attachment(get_post_thumbnail_id( $result->meta_value ),true);
								wp_delete_post($result->meta_value);

								$wpdb->update($wpdb->posts,array('post_status'=>'pfcancelled'),array('ID'=>$result->ID));
								/* - Creating record for process system. */
								$this->PFCreateProcessRecord(
									array(
								        'user_id' => $result->post_author,
								        'item_post_id' => $result->meta_value,
										'processname' => sprintf(esc_html__('Item deleted by Auto System: (Pendingpayment Waiting Time Reached/ Order : Cancelled.). (Order Date: %s)','pointfindercoreelements'),$result->post_date)
								    )
								);
							}
						}
					}

				}

			}
		/**
		*End: Check Pending Orders and Delete if they reached waiting limit.
		**/



		/**
		*Start: Check Expired Items & Expire
		**/

			public function pointfinder_check_expires() {

				$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

				if ($setup4_membersettings_paymentsystem != 1) {
					return;
				}

				/*
				Direct payment:

					- Change order status to pending payment.
					- Change Item status to pending payment.
					- Send an email to user.
					- Add a process record for this action.
				*/

				$exptime = date("Y-m-d H:s:i");

				global $wpdb;
				$results = $wpdb->get_results( $wpdb->prepare(
					"SELECT p.ID, p.post_author, pm.meta_value, p.post_date FROM $wpdb->posts as p
					INNER JOIN $wpdb->postmeta as pm
						ON ( p.ID = pm.post_id )
					INNER JOIN $wpdb->postmeta as pm2
						ON ( pm.post_id = pm2.post_id )
					WHERE p.post_type = %s
					and p.post_status = %s
					and p.post_parent = %d
					and pm.meta_key = %s
					and pm.meta_value <= %s
					and pm2.meta_key = %s
					and pm2.meta_value = %d",
					'pointfinderorders',
					'completed',
					0,
					'pointfinder_order_expiredate',
					$exptime,
					'pointfinder_order_recurring',
					0
				),'OBJECT_K' );


				$setup33_emaillimits_listingexpired = $this->PFMSIssetControl('setup33_emaillimits_listingexpired','','1');

				if ($this->PFControlEmptyArr($results)) {
					foreach ($results as $result) {

						$item_id = esc_attr(get_post_meta( $result->ID, 'pointfinder_order_itemid', true ));

						if (  false != get_post_status( $item_id ) ) {
							/* This is direct payment */
							$this->PFExpireItemManual(
							array(
							    'order_id' => $result->ID,
							    'post_id' => $item_id,
							    'post_author' => $result->post_author,
								'payment_type' => 'direct'
							)
							);

							if ($setup33_emaillimits_listingexpired == 1) {
								$user_info = get_userdata( $result->post_author);
								$this->pointfinder_mailsystem_mailsender(
								array(
								'toemail' => $user_info->user_email,
							    'predefined' => 'directafterexpire',
							    'data' => array('ID' => $item_id, 'expiredate' => $result->meta_value,'orderid' => $result->ID),
								)
								);
							}
						}

					}/*Foreach end*/
				}

			}
		/**
		*End: Check Expired Items & Expire
		**/


		/**
		*Start: Check Expired Featured Items & Expire Featured option - v1.6.4
		**/

			public function pointfinder_check_expires_featured() {

				$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

				if ($setup4_membersettings_paymentsystem != 1) {
					return;
				}

				/*
				Direct payment:

					- Change order status to pending payment.
					- Change Item status to pending payment.
					- Send an email to user.
					- Add a process record for this action.
				*/

				$exptime = date("Y-m-d H:s:i");

				global $wpdb;
				$results = $wpdb->get_results( $wpdb->prepare(
					"SELECT p.ID, p.post_author, pm.meta_value, p.post_date FROM $wpdb->posts as p
					INNER JOIN $wpdb->postmeta as pm
						ON ( p.ID = pm.post_id )
					INNER JOIN $wpdb->postmeta as pm2
						ON ( pm.post_id = pm2.post_id )
					WHERE p.post_type = %s
					and p.post_status = %s
					and p.post_parent = %d
					and pm.meta_key = %s
					and pm.meta_value <= %s
					and pm2.meta_key = %s
					and pm2.meta_value = %d",
					'pointfinderorders',
					'completed',
					0,
					'pointfinder_order_expiredate_featured',
					$exptime,
					'pointfinder_order_frecurring',
					0
				),'OBJECT_K' );



				if ($this->PFControlEmptyArr($results)) {
					foreach ($results as $result) {

						$item_id = esc_attr(get_post_meta( $result->ID, 'pointfinder_order_itemid', true ));

						update_post_meta($result->ID, 'pointfinder_order_featured', 0);
						delete_post_meta($result->ID, 'pointfinder_order_expiredate_featured');
						update_post_meta($item_id, 'webbupointfinder_item_featuredmarker', 0);


						$this->PFCreateProcessRecord(
							array(
						        'user_id' => $result->post_author,
						        'item_post_id' => $item_id,
								'processname' => esc_html__('Featured Item option expired and disabled by Auto System','pointfindercoreelements')
						    )
						);

					}/*Foreach end*/
				}

			}
		/**
		*End: Check Expired Featured Items & Expire Featured option
		**/



		/**
		*Start: Check expiring items and send email 1 day before.
		**/

			public function pointfinder_check_expiring() {

				$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

				if ($setup4_membersettings_paymentsystem != 1) {
					return;
				}

				/*
				Only for Direct payments. And this schedule will check item 1 day before expire.
				*/
				$setup33_emaillimits_listingautowarning = $this->PFMSIssetControl('setup33_emaillimits_listingautowarning','','1');
				if ($setup33_emaillimits_listingautowarning == 1) {
					$exptime = strtotime(date("Y-m-d H:s:i", strtotime("-1 day")));
					$exptime = date('Y-m-d H:s:i',mktime(23,59,59,date('m',$exptime),date('d',$exptime),date('Y',$exptime)) );

					$exptime2 = strtotime(date("Y-m-d H:s:i", strtotime("+1 day")));
					$exptime2 = date('Y-m-d H:s:i',mktime(23,59,59,date('m',$exptime2),date('d',$exptime2),date('Y',$exptime2)) );

					global $wpdb;
					$results = $wpdb->get_results( $wpdb->prepare(
						"SELECT p.ID, p.post_author, pm.meta_value, p.post_date FROM $wpdb->posts as p
						INNER JOIN $wpdb->postmeta as pm
							ON ( p.ID = pm.post_id )
						INNER JOIN $wpdb->postmeta as pm2
							ON ( pm.post_id = pm2.post_id )
						WHERE p.post_type = %s
						and p.post_status = %s
						and p.post_parent = %d
						and pm.meta_key = %s
						and pm.meta_value >= %s
						and pm.meta_value <= %s
						and pm2.meta_key = %s
						and pm2.meta_value = 0",
						"pointfinderorders",
						"completed",
						0,
						"pointfinder_order_expiredate",
						$exptime,
						$exptime2,
						"pointfinder_order_recurring"
						)
					,'OBJECT_K' );

					if ($this->PFControlEmptyArr($results)) {
						foreach ($results as $result) {

							$user_info = get_userdata( $result->post_author);
							$item_id = esc_attr(get_post_meta( $result->ID, 'pointfinder_order_itemid', true ));

							if (  false != get_post_status( $item_id ) ) {

								$mail_ok = 0;
								$mail_info = get_post_meta( $item_id, 'webbupointfinder_item_exemail',true);
								if ( !empty($mail_info) ) {

									$mail_info = json_decode($mail_info,true);

									if (is_array($mail_info)) {
										$mail_info_count = count($mail_info);

										if ($mail_info_count > 0) {
											$mail_info_date = (isset($mail_info[($mail_info_count-1)]['date']))? $mail_info[($mail_info_count-1)]['date'] : 0;
										}else{
											$mail_info_date = 0;
										}

										if ($mail_info_date != 0 && strtotime($mail_info_date) < strtotime(date("Y-m-d"))) {
											$mail_ok = 1;
											$mail_info[$mail_info_count] = array('date'=>date("Y-m-d"));
											delete_post_meta($item_id, 'webbupointfinder_item_exemail');
											add_post_meta($item_id, 'webbupointfinder_item_exemail', json_encode($mail_info) );
										}

									}

								}else{
									$mail_info = array();
									$mail_info[] = array('date'=>date("Y-m-d"));
									add_post_meta($item_id, 'webbupointfinder_item_exemail', json_encode($mail_info));
									$mail_ok = 1;
								}

								if ($mail_ok == 1) {
									$this->pointfinder_mailsystem_mailsender(
										array(
										'toemail' => $user_info->user_email,
								        'predefined' => 'directbeforeexpire',
								        'data' => array('ID' => $item_id, 'expiredate' => $result->meta_value,'orderid' => $result->ID),
										)
									);
								}
							}

						}/*Foreach end*/
					}
				}

			}
		/**
		*End: Check expiring items and send email 1 day before.
		**/
	/**
	*End: Pay per post Schedule
	**/



	/**
	*Start: Clear unused images from user upload
	**/
	public function pointfinder_clear_unusedimages() {
		global $wpdb;
		$results = $wpdb->get_results( $wpdb->prepare(
			"SELECT post_id FROM $wpdb->postmeta where meta_key = %s and meta_value = %d",
			"pointfinder_delete_unused",
			1
		),'OBJECT_K' );
		if ($this->PFControlEmptyArr($results)) {
			foreach ($results as $result) {
				delete_post_meta( $result->post_id, 'pointfinder_delete_unused');
				wp_delete_attachment( $result->post_id);
			}
		}
	}
	/**
	*End: Clear unused images from user upload
	**/


	/**
	*Start: Currency System
	**/

			public function pointfinder_currency_schedule(){

				$st9_currency_from = $this->PFASSIssetControl('st9_currency_from','','');

				$currency_output_arr = array();

				if (!empty($st9_currency_from)) {
					$st9_currency_to = $this->PFASSIssetControl('st9_currency_to','','');

					if (!empty($st9_currency_to)) {
						$currency_arr = $this->pfstring2BasicArray($st9_currency_to);
						$currency_output_arr = $this->pointfinder_custom_currencyConverter_clayer($st9_currency_from,$currency_arr);
					}

					if (!empty($currency_output_arr)) {
						update_option( 'pointfinder_currency_rates', $currency_output_arr);
					}
				}
			}

			public function pointfinder_custom_currencyConverter_clayer($currency_from,$currency_to){

				$st9_currency_key = $this->PFASSIssetControl('st9_currency_key','','');
				
				$currencies_arr = '';
				$currency_to_count = count($currency_to);
				$i = 0;

				foreach ($currency_to as $value) {
					$currencies_arr .= $value;
					if ($i < $currency_to_count) {
						$currencies_arr .= ',';
					}
					$i++;
				}

			    $base_url = "https://apilayer.net/api/live";

			    $query_url = $base_url . "?access_key=".$st9_currency_key;
			    $query_url .= "&currencies=".$currencies_arr."&source=".$currency_from."&format=1";

			    $query_session = curl_init($query_url);
			    curl_setopt($query_session, CURLOPT_RETURNTRANSFER,true);
			    $curlexec = curl_exec($query_session);

			    $curl_json_output =  json_decode($curlexec,true);

			    $status_of_job = (isset($curl_json_output['success']))?$curl_json_output['success']:false;
			    $currency_output = (isset($curl_json_output['quotes']))?$curl_json_output['quotes']:'';


			    $currency_output_arr = array();
			    if ($status_of_job == true) {
				   	if (is_array($currency_output)) {
				   		foreach ($currency_output as $currency_output_single => $currency_output_single_value) {
					    	if (isset($currency_output_single)) {
					    		$currency_output_arr[$currency_output_single] = (isset($currency_output_single_value))?$currency_output_single_value:'';
					    	}
					    }
				   	}
			   	}

			    return $currency_output_arr;
			}

			public function pointfinder_currency_system_process(){
				/* Check if currency changed */
					$st9_currency_from = $this->PFASSIssetControl('st9_currency_from','','');
					$st9_currency_to = $this->PFASSIssetControl('st9_currency_to','','');

					if (!empty($st9_currency_from)) {

						$old_currencyfield = get_option('pointfinder_currency_fields');
						$new_currencyfield = $st9_currency_from.$st9_currency_to;

						if ($old_currencyfield != $new_currencyfield) {
							update_option( 'pointfinder_currency_fields', $new_currencyfield );
							$this->pointfinder_currency_schedule();
						}
					}


				/* Check if currency selected */
					if (isset($_GET['c_code'])) {
						$selected_currency = sanitize_text_field($_GET['c_code']);
						setcookie('pointfinder_c_code', $selected_currency, time() + 86400, "/");
					}
			}

		

	/**
	*End: Currency System
	**/
}