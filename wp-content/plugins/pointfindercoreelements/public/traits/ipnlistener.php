<?php 
if (class_exists('PointFinderIPNListener')) {
	return;
}


class PointFinderIPNListener
{
	use PointFinderOptionFunctions,
	PointFinderCommonFunctions,
	PointFinderWPMLFunctions,
	PointFinderMailSystem,
	PointFinderCUFunctions,
	PointFinderDashFunctions;

    public function __construct(){}
  
    public function pointfinder_ipn_requests_handle(){

    	if (!empty($_GET['testByMollie'])){
			die('OK');
		}

		if (empty($_POST)) {
			return;
		}

		$setup4_membersettings_dashboard = $this->PFSAIssetControl('setup4_membersettings_dashboard','','');
		if (!is_page($setup4_membersettings_dashboard)) {
			return;
		}

		/*ob_start();
		print_r($_POST);
		$contentx = ob_get_contents();
		ob_end_clean();
		error_log($contentx);*/

	

		/*
	    * Start: PayFast Payment Gateway Notification Messages
	    */
	    	if (isset($_POST['m_payment_id'])) {

				$payf_mode = $this->PFPGIssetControl('payf_mode','',0);
				$payf_merid = $this->PFPGIssetControl('payf_merid','','');
				$payf_merkey = $this->PFPGIssetControl('payf_merkey','','');
				$payf_passph = $this->PFPGIssetControl('payf_passph','','');
				$sys_process = true;

				if ($payf_mode == 0) {
					$url = "https://sandbox.payfast.co.za";
				}else{
					$url = "https://www.payfast.co.za";
				}

				$pfData = $_POST;

				foreach( $pfData as $key => $val )
				{
				    $pfData[$key] = stripslashes( $val );
				}


				/* Security Check 1 */
				$pfParamString = '';
				foreach( $pfData as $key => $val )
				{
				    if( $key != 'signature' )
				    {
				        $pfParamString .= $key .'='. urlencode( $val ) .'&';
				    }
				}
				$pfParamString = substr( $pfParamString, 0, -1 );
				$pfTempParamString = $pfParamString;

				if( !empty( $payf_passph ) )
				{
				    $pfTempParamString .= '&passphrase='.urlencode( $payf_passph );
				}
				$signature = md5( $pfTempParamString );

				if($signature!=$pfData['signature'])
				{
				    $sys_process = false;
				}

				/* Security Check 2 */
				$validHosts = array(
				    'www.payfast.co.za',
				    'sandbox.payfast.co.za',
				    'w1w.payfast.co.za',
				    'w2w.payfast.co.za',
				);

				$validIps = array();

				foreach( $validHosts as $pfHostname )
				{
				    $ips = gethostbynamel( $pfHostname );
				    if( $ips !== false )
				    {
				        $validIps = array_merge( $validIps, $ips );
				    }
				}

				$validIps = array_unique( $validIps );

				if( !in_array( $_SERVER['REMOTE_ADDR'], $validIps ) )
				{
				    $sys_process = false;
				}

				if ($sys_process && $pfData['payment_status'] == 'COMPLETE') {

					$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');
					$setup33_emaillimits_listingexpired = PFMSIssetControl('setup33_emaillimits_listingexpired','','1');

					$order_id = esc_attr($pfData['custom_int3']);
					$otype = esc_attr($pfData['custom_str1']);
					$user_id = esc_attr($pfData['custom_int4']);
					$item_post_id = (isset($pfData['custom_int2']))?esc_attr($pfData['custom_int2']):'';

					$this->pointfinder_directpayment_success_process(
						array(
							'paymentsystem' => $setup4_membersettings_paymentsystem,
					        'item_post_id' => $item_post_id,
					        'order_post_id' => $order_id,
					        'otype' => $otype,
					        'user_id' => $user_id,
							'paymentsystem_name' => esc_html__("PayFast","pointfindercoreelements"),
							'checkout_process_name' => 'DoExpressCheckoutPaymentRobo'
						)
					);

					delete_post_meta( sanitize_text_field($order_id), 'pointfinder_order_payf');

				}



	    	}
	    /*
	    * End: PayFast Payment Gateway Notification Messages
	    */


		/*
	    * Start: Paypal Payment Gateway IPN Messages
	    */
		    if (isset($_POST['txn_type'])) {

		        $paypal_host = (PFSAIssetControl('setup20_paypalsettings_paypal_sandbox','','0') == 0)? 'www.paypal.com' : 'www.sandbox.paypal.com';

		        $url = 'https://'.$paypal_host.'/cgi-bin/webscr';

		        $post_data = $_POST;
		        $encoded_data = 'cmd=_notify-validate';
		        foreach ($post_data as $key => $value) {
		            $encoded_data .= "&$key=".urlencode($value);
		        }

		        $response = wp_remote_post( $url.'?'.$encoded_data, array(
		              'method' => 'POST',
		              'sslverify'   => false,
		              'redirection' => 5,
		              'httpversion' => '1.0',
		              'headers' => array('Expect:'),
		              'body' => $encoded_data,
		            )
		        );

		        if (is_wp_error($response)) {
					/*
					$error_string = $response->get_error_message();
					echo $error_string;
					*/
					return false;
			    }

		        $verified = (isset($response['body']))? ($response['body'] == 'VERIFIED')? true : false : false;

		        if ($verified) {

		        	$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

		        	switch ($_POST['txn_type']) {
		        		case 'recurring_payment_profile_cancel':
		        		case 'recurring_payment_failed':
		        		case 'recurring_payment_expired':

		        			/**
		        			*Start : Cancel Recurring Payment Profile
		        			**/
			        			if (isset($_POST['recurring_payment_id'])) {

			        				$setup33_emaillimits_listingexpired = PFMSIssetControl('setup33_emaillimits_listingexpired','','1');
			        				/*Find Item & Order by using profile ID */
				        			global $wpdb;

				        			$order_id = $wpdb->get_var( $wpdb->prepare(
											"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_recurringid' and meta_value = '%s'",
											$_POST['recurring_payment_id']
										));

									$recurring_status = esc_attr(get_post_meta( $order_id, 'pointfinder_order_recurring',true));

									if ($setup4_membersettings_paymentsystem == 2) {

										if (!empty($order_id) && $recurring_status == 1) {

											$user_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_userid', true ));
											$pointfinder_order_expiredate = get_post_meta( $order_id, 'pointfinder_order_expiredate', true );
											$pointfinder_order_packageid = get_post_meta( $order_id, 'pointfinder_order_packageid', true );
											$packageinfo = $this->pointfinder_membership_package_details_get($pointfinder_order_packageid);

											update_post_meta( $order_id, 'pointfinder_order_recurring', 0 );
											update_user_meta( $user_id, 'membership_user_recurring', 0);

											$this->PFCreateProcessRecord(
												array(
											        'user_id' => $user_id,
											        'item_post_id' => $order_id,
													'processname' => esc_html__('Recurring Payment Profile Cancelled','pointfindercoreelements'),
													'membership' => 1
											    )
											);

											if ($setup33_emaillimits_listingexpired == 1) {

												$user_info = get_userdata( $user_id);

											 	$this->pointfinder_mailsystem_mailsender(
													array(
													'toemail' => $user_info->user_email,
											        'predefined' => 'expiredrecpaymentmember',
											        'data' => array(
											        	'packagename' => $packageinfo['webbupointfinder_mp_title'],
											        	'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],
											        	'expiredate' => $this->PFU_DateformatS($pointfinder_order_expiredate),
											        	'orderid' => $order_id
											        	),
													)
												);
											}
										}

									}else{

										if (!empty($order_id) && $recurring_status == 1) {

											$item_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_itemid', true ));
											$pointfinder_order_expiredate = get_post_meta( $order_id, 'pointfinder_order_expiredate', true );

											$post_author = $wpdb->get_var( $wpdb->prepare(
												"SELECT post_author FROM $wpdb->posts WHERE ID = %d",
												$item_id
											));

											update_post_meta( $order_id, 'pointfinder_order_recurring', 0 );
											update_post_meta( $order_id, 'pointfinder_order_frecurring', 0 );

											$this->PFCreateProcessRecord(
												array(
											        'user_id' => $post_author,
											        'item_post_id' => $item_id,
													'processname' => esc_html__('Recurring Payment Profile Cancelled','pointfindercoreelements')
											    )
											);

											if ($setup33_emaillimits_listingexpired == 1) {
												$user_info = get_userdata( $post_author);
											 	$this->pointfinder_mailsystem_mailsender(
													array(
													'toemail' => $user_info->user_email,
											        'predefined' => 'expiredrecpayment',
											        'data' => array('ID' => $item_id, 'expiredate' => $pointfinder_order_expiredate,'orderid' => $order_id),
													)
												);
											}
										}else{
											$order_id = $wpdb->get_var( $wpdb->prepare(
												"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_frecurringid' and meta_value = '%s'",
												$_POST['recurring_payment_id']
											));
											$recurring_status = esc_attr(get_post_meta( $order_id, 'pointfinder_order_frecurring',true));

											if (!empty($order_id) && $recurring_status == 1) {

												$item_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_itemid', true ));
												$pointfinder_order_expiredate = get_post_meta( $order_id, 'pointfinder_order_expiredate', true );

												$post_author = $wpdb->get_var( $wpdb->prepare(
													"SELECT post_author FROM $wpdb->posts WHERE ID = %d",
													$item_id
												));

												update_post_meta( $order_id, 'pointfinder_order_frecurring', 0 );

												$this->PFCreateProcessRecord(
													array(
												        'user_id' => $post_author,
												        'item_post_id' => $item_id,
														'processname' => esc_html__('Recurring Payment Profile Cancelled (For Featured Option)','pointfindercoreelements')
												    )
												);
											}
										}
									}
			        			}
		        			/**
		        			*End : Cancel Recurring Payment Profile
		        			**/
		        			break;


		        		case 'recurring_payment_suspended':
		        		case 'recurring_payment_suspended_due_to_max_failed_payment':

		        			/**
		        			*Start : Suspended Recurring Payment Profile
		        			**/
			        			if (isset($_POST['recurring_payment_id'])) {

			        				/*Find Item & Order by using profile ID */
				        			global $wpdb;

									$order_id = $wpdb->get_var( $wpdb->prepare(
										"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_recurringid' and meta_value = '%s'",
										$_POST['recurring_payment_id']
									));

									$recurring_status = esc_attr(get_post_meta( $order_id, 'pointfinder_order_recurring',true));

									if ($setup4_membersettings_paymentsystem == 2) {
										if (!empty($order_id) && $recurring_status == 1) {

											$user_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_userid', true));
											$pointfinder_order_expiredate = get_post_meta( $order_id, 'pointfinder_order_expiredate', true);

											$pointfinder_order_packageid = get_post_meta( $order_id, 'pointfinder_order_packageid', true );
											$packageinfo = $this->pointfinder_membership_package_details_get($pointfinder_order_packageid);

											update_post_meta( $order_id, 'pointfinder_order_recurring', 0 );
											update_user_meta( $user_id, 'membership_user_recurring', 0);

											$this->PF_Cancel_recurring_payment_member(
											 array(
											        'user_id' => $user_id,
											        'profile_id' => $_POST['recurring_payment_id'],
											        'item_post_id' => $order_id,
											        'order_post_id' => $order_id,
											    )
											 );

											$this->PFCreateProcessRecord(
												array(
											        'user_id' => $user_id,
											        'item_post_id' => $order_id,
													'processname' => esc_html__('Recurring Payment Profile Cancelled by IPN (Failed Payment)','pointfindercoreelements'),
													'membership' => 1
											    )
											);

											$setup33_emaillimits_listingexpired = PFMSIssetControl('setup33_emaillimits_listingexpired','','1');

											if ($setup33_emaillimits_listingexpired == 1) {
												$user_info = get_userdata( $user_id);
											 	$this->pointfinder_mailsystem_mailsender(
													array(
													'toemail' => $user_info->user_email,
											        'predefined' => 'expiredrecpaymentmember',
											        'data' => array(
											        	'packagename' => $packageinfo['webbupointfinder_mp_title'],
											        	'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],
											        	'expiredate' => $this->PFU_DateformatS($pointfinder_order_expiredate),
											        	'orderid' => $order_id
											        	),
													)
												);
											}


										}

									}else{
										if (!empty($order_id) && $recurring_status == 1) {

											$item_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_itemid', true ));
											$pointfinder_order_expiredate = esc_attr(get_post_meta( $order_id, 'pointfinder_order_expiredate', true ));

											$post_author = $wpdb->get_var( $wpdb->prepare(
												"SELECT post_author FROM $wpdb->posts WHERE ID = %d",
												$item_id
											));


											update_post_meta( $order_id, 'pointfinder_order_recurring', 0 );
											update_post_meta( $order_id, 'pointfinder_order_frecurring', 0 );

											$this->PF_Cancel_recurring_payment(
											 array(
											        'user_id' => $post_author,
											        'profile_id' => $_POST['recurring_payment_id'],
											        'item_post_id' => $item_id,
											        'order_post_id' => $order_id,
											    )
											 );

											$this->PFCreateProcessRecord(
												array(
											        'user_id' => $post_author,
											        'item_post_id' => $item_id,
													'processname' => esc_html__('Recurring Payment Profile Cancelled by IPN (Failed Payment)','pointfindercoreelements')
											    )
											);

											$setup33_emaillimits_listingexpired = PFMSIssetControl('setup33_emaillimits_listingexpired','','1');

											if ($setup33_emaillimits_listingexpired == 1) {
												$user_info = get_userdata( $post_author);
											 	$this->pointfinder_mailsystem_mailsender(
													array(
													'toemail' => $user_info->user_email,
											        'predefined' => 'expiredrecpayment',
											        'data' => array('ID' => $item_id, 'expiredate' => $pointfinder_order_expiredate,'orderid' => $order_id),
													)
												);
											}
										}else{
											$order_id = $wpdb->get_var( $wpdb->prepare(
												"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_frecurringid' and meta_value = '%s'",
												$_POST['recurring_payment_id']
											));
											$recurring_status = esc_attr(get_post_meta( $order_id, 'pointfinder_order_frecurring',true));

											if (!empty($order_id) && $recurring_status == 1) {

												$item_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_itemid', true ));
												$pointfinder_order_expiredate = get_post_meta( $order_id, 'pointfinder_order_expiredate', true );

												$post_author = $wpdb->get_var( $wpdb->prepare(
													"SELECT post_author FROM $wpdb->posts WHERE ID = %d",
													$item_id
												));

												update_post_meta( $order_id, 'pointfinder_order_frecurring', 0 );

												$this->PFCreateProcessRecord(
													array(
												        'user_id' => $post_author,
												        'item_post_id' => $item_id,
														'processname' => esc_html__('Recurring Payment Profile Cancelled by IPN (Failed Payment) (For Featured Option)','pointfindercoreelements')
												    )
												);
											}
										}
									}

			        			}
		        			/**
		        			*End : Suspended Recurring Payment Profile
		        			**/
		        			break;


		        		case 'recurring_payment':
		        			/**
		        			*Start : Extend Recurring Payed Item
		        			**/
			        			if ($_POST['payment_status'] == 'Completed') {


				        			/*Find Item & Order by using profile ID */
				        			global $wpdb;

									$order_id = $wpdb->get_var( $wpdb->prepare(
										"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_recurringid' and meta_value = '%s'",
										$_POST['recurring_payment_id']
									));



									if ($setup4_membersettings_paymentsystem == 2) {
										if (!empty($order_id)) {

						        			$old_expire_date = get_post_meta( $order_id, 'pointfinder_order_expiredate', true);
						        			$user_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_userid', true ));

											$membership_user_activeorder = get_user_meta( $user_id, 'membership_user_activeorder', true );
		                  					$expire_date_rec = get_post_meta( $membership_user_activeorder, 'pointfinder_order_expiredate', true );

											$pointfinder_order_packageid = get_post_meta( $order_id, 'pointfinder_order_packageid', true );
											$packageinfo = $this->pointfinder_membership_package_details_get($pointfinder_order_packageid);

											if ($this->pf_membership_expire_check($expire_date_rec) == false) {
												$exp_date = strtotime($this->PFU_DateformatS($old_expire_date)." +".$packageinfo['webbupointfinder_mp_billing_period']." ".$this->pointfinder_billing_timeunit_text_ex($packageinfo['webbupointfinder_mp_billing_time_unit'])."");
												$app_date = strtotime("now");
											} else {
												$exp_date = strtotime("+".$packageinfo['webbupointfinder_mp_billing_period']." ".$this->pointfinder_billing_timeunit_text_ex($packageinfo['webbupointfinder_mp_billing_time_unit'])."");
												$app_date = strtotime("now");
											}


											$app_date = strtotime("now");

											update_post_meta( $order_id, 'pointfinder_order_expiredate', $exp_date);
											update_post_meta( $order_id, 'pointfinder_order_datetime_approval', $app_date);

											$this->PF_CreatePaymentRecord(
												array(
												'user_id'	=>	$user_id,
												'order_post_id'	=>	$order_id,
												'processname'	=>	'RecurringPayment',
												'response' => $post_data,
												'membership' => 1
												)
											);

											$this->PFCreateProcessRecord(
												array(
										        'user_id' => $user_id,
										        'item_post_id' => $order_id,
												'processname' => sprintf(esc_html__('Expire date extended by IPN System: (Order Date: %s / Expire Date: %s)','pointfindercoreelements'),
													$this->PFU_DateformatS($app_date),
													$this->PFU_DateformatS($exp_date)
													),
												'membership' => 1
											    )
											);

											/* Create an invoice for this */
								              $this->PF_CreateInvoice(
								                array(
								                  'user_id' => $user_id,
								                  'item_id' => 0,
								                  'order_id' => $order_id,
								                  'description' => $packageinfo['webbupointfinder_mp_title'],
								                  'processname' => esc_html__('Recurring Payment','pointfindercoreelements'),
								                  'amount' => $packageinfo['packageinfo_priceoutput_text'],
								                  'datetime' => strtotime("now"),
								                  'packageid' => $packageinfo['webbupointfinder_mp_packageid'],
								                  'status' => 'publish'
								                )
								              );
										}
									} else {
										if (!empty($order_id)) {

											$setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
											$setup20_paypalsettings_decimals = $this->PFSAIssetControl('setup20_paypalsettings_decimals','','2');

											$item_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_itemid', true ));
						        			$pointfinder_order_listingtime = esc_attr(get_post_meta( $order_id, 'pointfinder_order_listingtime', true ));
						        			$old_expire_date = get_post_meta( $order_id, 'pointfinder_order_expiredate', true);

						        			$exp_date = date("Y-m-d H:i:s",strtotime($old_expire_date .'+'.$pointfinder_order_listingtime.' day'));
											$app_date = date("Y-m-d H:i:s");

											update_post_meta( $order_id, 'pointfinder_order_expiredate', $exp_date);
											update_post_meta( $order_id, 'pointfinder_order_datetime_approval', $app_date);

											$post_author = $wpdb->get_var( $wpdb->prepare(
												"SELECT post_author FROM $wpdb->posts WHERE ID = %d",
												$item_id
											));


											$this->PF_CreatePaymentRecord(
												array(
												'user_id'	=>	$post_author,
												'item_post_id'	=>	$item_id,
												'order_post_id'	=>	$order_id,
												'processname'	=>	'RecurringPayment',
												'response' => $post_data,
												)
											);

											$this->PFCreateProcessRecord(
												array(
										        'user_id' => $post_author,
										        'item_post_id' => $item_id,
												'processname' => sprintf(esc_html__('Expire date extended by IPN System: (Order Date: %s / Expire Date: %s)','pointfindercoreelements'),
													$app_date,
													$exp_date
													)
											    )
											);

											$order_price_inv = get_post_meta( $order_id, 'pointfinder_order_price', true );
											if (empty($order_price_inv)) {$order_price_inv = 0;}

											$order_pname_inv = get_post_meta( $order_id, 'pointfinder_order_listingpname', true );
											if (empty($order_pname_inv)) {$order_pname_inv = '-';}





											/* Create an invoice for this */
								              $this->PF_CreateInvoice(
								                array(
								                  'user_id' => $post_author,
								                  'item_id' => $item_id,
								                  'order_id' => $order_id,
								                  'description' => $order_pname_inv,
								                  'processname' => esc_html__('Recurring Payment','pointfindercoreelements'),
								                  'amount' => number_format($order_price_inv, $setup20_paypalsettings_decimals, '.', ','),
								                  'datetime' => strtotime("now"),
								                  'packageid' => 0,
								                  'status' => 'publish'
								                )
								              );
										}else{
											$order_id = $wpdb->get_var( $wpdb->prepare(
												"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_frecurringid' and meta_value = '%s'",
												$_POST['recurring_payment_id']
											));

											if (!empty($order_id)) {

												$setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
												$setup20_paypalsettings_decimals = $this->PFSAIssetControl('setup20_paypalsettings_decimals','','2');

												$item_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_itemid', true ));

							        			$stp31_daysfeatured = $this->PFSAIssetControl('stp31_daysfeatured','','3');

							        			$old_expire_date = get_post_meta( $order_id, 'pointfinder_order_expiredate_featured', true);

							        			$exp_date = date("Y-m-d H:i:s",strtotime($old_expire_date .'+'.$stp31_daysfeatured.' day'));
												$app_date = date("Y-m-d H:i:s");

												update_post_meta( $order_id, 'pointfinder_order_expiredate_featured', $exp_date);

												$post_author = $wpdb->get_var( $wpdb->prepare(
													"SELECT post_author FROM $wpdb->posts WHERE ID = %d",
													$item_id
												));


												$this->PF_CreatePaymentRecord(
													array(
													'user_id'	=>	$post_author,
													'item_post_id'	=>	$item_id,
													'order_post_id'	=>	$order_id,
													'processname'	=>	'RecurringPayment',
													'response' => $post_data,
													)
												);

												$this->PFCreateProcessRecord(
													array(
											        'user_id' => $post_author,
											        'item_post_id' => $item_id,
													'processname' => sprintf(esc_html__('Expire date extended by IPN System: (For Featured) (Order Date: %s / Expire Date: %s)','pointfindercoreelements'),
														$app_date,
														$exp_date
														)
												    )
												);


												$order_price_inv = $this->PFSAIssetControl('setup31_userpayments_pricefeatured','',0);

												$order_pname_inv = esc_html__('Recurring Payment for Featured Option','pointfindercoreelements');

												/* Create an invoice for this */
									              $this->PF_CreateInvoice(
									                array(
									                  'user_id' => $post_author,
									                  'item_id' => $item_id,
									                  'order_id' => $order_id,
									                  'description' => $order_pname_inv,
									                  'processname' => esc_html__('Recurring Payment for Featured Option','pointfindercoreelements'),
									                  'amount' => number_format($order_price_inv, $setup20_paypalsettings_decimals, '.', ','),
									                  'datetime' => strtotime("now"),
									                  'packageid' => 0,
									                  'status' => 'publish'
									                )
									              );
											}

										}
									}



								}elseif ($_POST['payment_status'] == 'Pending') {
									/*Find Item & Order by using profile ID */
				        			global $wpdb;

									$order_id = $wpdb->get_var( $wpdb->prepare(
										"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'pointfinder_order_recurringid' and meta_value = '%s'",
										$_POST['recurring_payment_id']
									));

									if (!empty($order_id)) {
										$item_id = esc_attr(get_post_meta( $order_id, 'pointfinder_order_itemid', true ));

										$post_author = $wpdb->get_var( $wpdb->prepare(
											"SELECT post_author FROM $wpdb->posts WHERE ID = %d",
											$item_id
										));

										$this->PF_CreatePaymentRecord(
											array(
											'user_id'	=>	$post_author,
											'item_post_id'	=>	$item_id,
											'order_post_id'	=>	$order_id,
											'processname'	=>	'RecurringPaymentPending',
											'response' => $post_data,
											)
										);

									}
								}
							/**
		        			*End : Extend Recurring Payed Item
		        			**/
		        			break;

		        		case 'web_accept':

		        			/**
		        			*Start : Refund & reversals
		        			**/
			        			if($_POST["payment_status"] == "Refunded" || $_POST["payment_status"] == "Reversed"){
								    if (isset($_POST['custom'])) {
								    	$setup33_emaillimits_listingexpired = PFMSIssetControl('setup33_emaillimits_listingexpired','','1');
				        				/*Find Item & Order by using profile ID */
					        			global $wpdb;

										$order_id = get_user_meta( $_POST['custom'], 'membership_user_package_id', true );

										if ($setup4_membersettings_paymentsystem == 2) {

											$user_id = esc_attr($_POST['custom']);
											$pointfinder_order_expiredate = get_post_meta( $order_id, 'pointfinder_order_expiredate', true );
											$pointfinder_order_packageid = get_post_meta( $order_id, 'pointfinder_order_packageid', true );
											$packageinfo = $this->pointfinder_membership_package_details_get($pointfinder_order_packageid);


											/* This is direct payment */
											$this->PFExpireItemManualMember(
												array(
											        'order_id' => $order_id,
											        'post_author' => $user_id,
													'payment_type' => 'web_accept',
													'payment_err' => $_POST["payment_status"]
											    )
											 );

											 if ($setup33_emaillimits_listingexpired == 1) {
											 	$user_info = get_userdata( $user_id);

											 	$this->pointfinder_mailsystem_mailsender(
													array(
													'toemail' => $user_info->user_email,
											        'predefined' => 'directafterexpiremember',
											        'data' => array(
											        	'orderid' => $order_id,
											        	'packagename' => $packageinfo['webbupointfinder_mp_title'],
											        	'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],
											        	'expiredate' => $this->PFU_DateformatS($pointfinder_order_expiredate)
											        	),
													)
												);
											 }
										} else {
											$post_author = $wpdb->get_var( $wpdb->prepare(
												"SELECT post_author FROM $wpdb->posts WHERE ID = %d",
												$_POST['custom']
											));



											/* This is direct payment */
											$this->PFExpireItemManual(
												array(
											        'order_id' => $order_id,
											        'post_id' => $_POST['custom'],
											        'post_author' => $post_author,
													'payment_type' => 'web_accept',
													'payment_err' => $_POST["payment_status"]
											    )
											 );

											 if ($setup33_emaillimits_listingexpired == 1) {
											 	$user_info = get_userdata( $post_author);
											 	$pointfinder_order_expiredate = get_post_meta( $order_id, 'pointfinder_order_expiredate', true );
											 	$this->pointfinder_mailsystem_mailsender(
													array(
													'toemail' => $user_info->user_email,
											        'predefined' => 'directafterexpire',
											        'data' => array('ID' => $_POST['custom'], 'expiredate' => $pointfinder_order_expiredate,'orderid' => $order_id),
													)
												);
											 }
										}
								    }

								}
							/**
		        			*End : Refund & reversals
		        			**/
		        			break;

		        	}
		        }
		    }
		/*
	    * End: Paypal Payment Gateway IPN Messages
	    */

	    /*
	    * Start: PagSeguro Payment Gateway Notification Messages
	    */
			if (isset($_POST['notificationCode'])) {
				$pags_status = $this->PFPGIssetControl('pags_status','','0');
				if ($pags_status == 1) {

					$pags_notificationCode = (isset($_POST['notificationCode']) && trim($_POST['notificationCode']) !== "" ?trim($_POST['notificationCode']) : null);
				    $pags_type = (isset($_POST['notificationType']) && trim($_POST['notificationType']) !== "" ?trim($_POST['notificationType']) : null);

				    if ($pags_notificationCode && $pags_type) {

				        $notificationType = new PagSeguroNotificationType($pags_type);
				        $pags_strType = $notificationType->getTypeFromValue();

				        $setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

				        switch ($pags_strType) {

				            case 'TRANSACTION':
				                $pags_credentials = PagSeguroConfig::getAccountCredentials();
					        try {
					            $pags_transaction = PagSeguroNotificationService::checkTransaction($pags_credentials, $pags_notificationCode);

					            $pags_status = $pags_transaction->getStatus()->getValue();
					            $pags_reference = $pags_transaction->getreference();


		            			if (isset($pags_reference)) {
					            	$pags_reference_exp = explode("-", $pags_reference);
					            	if(count($pags_reference_exp) == 2){
					            		$order_id = $pags_reference_exp[0];
					            		$otype = $pags_reference_exp[1];
					            	}elseif (count($pags_reference_exp) == 1) {
					            		$order_id = $pags_reference_exp[0];
					            		if ($setup4_membersettings_paymentsystem == 2) {
					            			$otype = 'n';
					            		}else{
					            			$otype = 0;
					            		}
					            	}
					            }

					            if (!empty($order_id)) {
					            	global $wpdb;

					            	switch (intval($pags_status)) {
					            		case 3:

					            			$item_post_id = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s and post_id = %s", 'pointfinder_order_itemid',$order_id));
					            			$user_id = $wpdb->get_var( $wpdb->prepare("SELECT post_author FROM $wpdb->posts WHERE ID = %d", $order_id));

					            			$this->pointfinder_directpayment_success_process(
												array(
													'paymentsystem' => $setup4_membersettings_paymentsystem,
											        'item_post_id' => $item_post_id,
											        'order_post_id' => $order_id,
											        'otype' => $otype,
											        'user_id' => $user_id,
													'paymentsystem_name' => esc_html__("PagSeguro","pointfindercoreelements"),
													'checkout_process_name' => 'DoExpressCheckoutPaymentPags'
												)
											);

					            			break;
					            		case 5:
					            		case 9:
					            			$user_id = $wpdb->get_var( $wpdb->prepare("SELECT post_author FROM $wpdb->posts WHERE ID = %d", $order_id));
					            			$item_post_id = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s and post_id = %s", 'pointfinder_order_itemid',$order_id));
					            			/* In Dispute */
						            		if ($setup4_membersettings_paymentsystem == 2) {
						            			/* Membership */
													$pointfinder_order_expiredate = strtotime("now");
													update_post_meta( $order_id, 'pointfinder_order_expiredate', strtotime("now"));

													$pointfinder_order_packageid = get_post_meta( $order_id, 'pointfinder_order_packageid', true );
													$packageinfo = $this->pointfinder_membership_package_details_get($pointfinder_order_packageid);

													/* This is direct payment */
													$this->PFExpireItemManualMember(
														array(
														    'order_id' => $order_id,
														    'post_author' => $user_id,
															'payment_type' => 'pags',
															'payment_err' => 'In Dispute'
														)
													);
													$setup33_emaillimits_listingexpired = PFMSIssetControl('setup33_emaillimits_listingexpired','','1');
													if ($setup33_emaillimits_listingexpired == 1) {
														$user_info = get_userdata( $user_id);

														$this->pointfinder_mailsystem_mailsender(
															array(
															'toemail' => $user_info->user_email,
														    'predefined' => 'directafterexpiremember',
														    'data' => array(
														    	'orderid' => $order_id,
														    	'packagename' => $packageinfo['webbupointfinder_mp_title'],
														    	'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],
														    	'expiredate' => $this->PFU_DateformatS($pointfinder_order_expiredate)
														    	),
															)
														);
													}

						            		}else{
						            			/* Pay per post do nothing. */
						            			if ($otype != 1) {
							            			wp_update_post(array('ID' => $item_post_id,'post_status' => 'pendingapproval') );
													wp_reset_postdata();
													wp_update_post(array('ID' => $order_id,'post_status' => 'pendingapproval') );
													wp_reset_postdata();

													$this->PFCreateProcessRecord(
														array(
												        'user_id' => $user_id,
												        'item_post_id' => $item_post_id,
														'processname' => esc_html__("PagSeguro: Payment came into dispute.",'pointfindercoreelements')
													    )
													);
												}
						            		}
					            			break;

					            		case 6:
					            		case 8:
					            			$user_id = $wpdb->get_var( $wpdb->prepare("SELECT post_author FROM $wpdb->posts WHERE ID = %d", $order_id));
					            			$item_post_id = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s and post_id = %s", 'pointfinder_order_itemid',$order_id));
					            			/* ChargeBack */
						            		if ($setup4_membersettings_paymentsystem == 2) {
						            			/* Membership */
						            			$pointfinder_order_expiredate = strtotime("now");
												update_post_meta( $order_id, 'pointfinder_order_expiredate', strtotime("now"));

												$pointfinder_order_packageid = get_post_meta( $order_id, 'pointfinder_order_packageid', true );
												$packageinfo = $this->pointfinder_membership_package_details_get($pointfinder_order_packageid);

												/* This is direct payment */
												$this->PFExpireItemManualMember(
													array(
													    'order_id' => $order_id,
													    'post_author' => $user_id,
														'payment_type' => 'pags',
														'payment_err' => 'Chargeback'
													)
												);
												$setup33_emaillimits_listingexpired = PFMSIssetControl('setup33_emaillimits_listingexpired','','1');
												if ($setup33_emaillimits_listingexpired == 1) {
													$user_info = get_userdata( $user_id);

													$this->pointfinder_mailsystem_mailsender(
														array(
														'toemail' => $user_info->user_email,
													    'predefined' => 'directafterexpiremember',
													    'data' => array(
													    	'orderid' => $order_id,
													    	'packagename' => $packageinfo['webbupointfinder_mp_title'],
													    	'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],
													    	'expiredate' => $this->PFU_DateformatS($pointfinder_order_expiredate)
													    	),
														)
													);
												}

						            		}else{
						            			/* Pay per post refund. */
						            			if ($otype != 1) {
							            			wp_update_post(array('ID' => $item_post_id,'post_status' => 'pendingpayment') );
													wp_reset_postdata();
													wp_update_post(array('ID' => $order_id,'post_status' => 'pendingpayment') );
													wp_reset_postdata();

													$this->PFCreateProcessRecord(
														array(
												        'user_id' => $user_id,
												        'item_post_id' => $item_post_id,
														'processname' => esc_html__('PagSeguro: Payment refunded.','pointfindercoreelements')
													    )
													);
												}
						            		}
					            			break;

					            		case 7:
					            			$user_id = $wpdb->get_var( $wpdb->prepare("SELECT post_author FROM $wpdb->posts WHERE ID = %d", $order_id));
					            			$item_post_id = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s and post_id = %s", 'pointfinder_order_itemid',$order_id));
					            			/* Cancel */
											if ($setup4_membersettings_paymentsystem == 2) {
												$this->PFCreateProcessRecord(
								                  array(
								                    'user_id' => $user_id,
								                    'item_post_id' => $order_post_id,
								                    'processname' => esc_html__('PagSeguro: Payment canceled.','pointfindercoreelements'),
								                    'membership' => 1
								                    )
								                );
											}else{
							            		$this->PFCreateProcessRecord(
													array(
											        'user_id' => $user_id,
											        'item_post_id' => $item_post_id,
													'processname' => esc_html__('PagSeguro: Payment canceled.','pointfindercoreelements')
												    )
												);
							            	}
					            			break;

					            		case 4:
					            			$user_id = $wpdb->get_var( $wpdb->prepare("SELECT post_author FROM $wpdb->posts WHERE ID = %d", $order_id));
					            			$item_post_id = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s and post_id = %s", 'pointfinder_order_itemid',$order_id));
					            			/* Available */
											if ($setup4_membersettings_paymentsystem == 2) {
												$this->PFCreateProcessRecord(
								                  array(
								                    'user_id' => $user_id,
								                    'item_post_id' => $order_post_id,
								                    'processname' => esc_html__('PagSeguro: Payment completed and credited to your account.','pointfindercoreelements'),
								                    'membership' => 1
								                    )
								                );
											}else{
							            		$this->PFCreateProcessRecord(
													array(
											        'user_id' => $user_id,
											        'item_post_id' => $item_post_id,
													'processname' => esc_html__('PagSeguro: Payment completed and credited to your account.','pointfindercoreelements')
												    )
												);
						            		}
					            			break;
					            	}

					            }

					        } catch (PagSeguroServiceException $e) {/*$e->getMessage();*/}
				            break;
				        }
				    }
			    }
			}
		/*
	    * End: PagSeguro Payment Gateway Notification Messages
	    */


	    /*
	    * Start: iDeal Payment Gateway Notification Messages
	    */
	    	if (isset($_POST['id'])) {
	    		$ideal_status = $this->PFPGIssetControl('ideal_status','','0');
	    		if ($ideal_status == 1) {

		    		$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

		    		$ideal_id = $this->PFPGIssetControl('ideal_id','','');
		          	$mollie = new Mollie_API_Client;
		          	$mollie->setApiKey($ideal_id);

		          	$payment  = $mollie->payments->get($_POST["id"]);

					$order_id = $payment->metadata->order_id;
					$item_post_id = $payment->metadata->item_post_id;
					$user_id = $payment->metadata->user_id;
					$otype = $payment->metadata->otype;
					$status = $payment->status;

					if (isset($status) && !empty($order_id)) {
						if ($payment->isPaid()){
						    $this->pointfinder_directpayment_success_process(
								array(
									'paymentsystem' => $setup4_membersettings_paymentsystem,
							        'item_post_id' => $item_post_id,
							        'order_post_id' => $order_id,
							        'otype' => $otype,
							        'user_id' => $user_id,
									'paymentsystem_name' => esc_html__("iDeal","pointfindercoreelements"),
									'checkout_process_name' => 'DoExpressCheckoutPaymentiDeal'
								)
							);
						}elseif (!$payment->isOpen() || $status == "cancelled"){
		        			/* Cancel */
							if ($setup4_membersettings_paymentsystem == 2) {
								$this->PFCreateProcessRecord(
				                  array(
				                    'user_id' => $user_id,
				                    'item_post_id' => $order_post_id,
				                    'processname' => esc_html__('iDeal: Payment canceled.','pointfindercoreelements'),
				                    'membership' => 1
				                    )
				                );
							}else{
			            		$this->PFCreateProcessRecord(
									array(
							        'user_id' => $user_id,
							        'item_post_id' => $item_post_id,
									'processname' => esc_html__('iDeal: Payment canceled.','pointfindercoreelements')
								    )
								);
			            	}
						}elseif ($status == "pending") {

							if ($setup4_membersettings_paymentsystem == 2) {
								$this->PFCreateProcessRecord(
				                  array(
				                    'user_id' => $user_id,
				                    'item_post_id' => $order_post_id,
				                    'processname' => esc_html__('iDeal: Payment pending.','pointfindercoreelements'),
				                    'membership' => 1
				                    )
				                );
							}else{
			            		$this->PFCreateProcessRecord(
									array(
							        'user_id' => $user_id,
							        'item_post_id' => $item_post_id,
									'processname' => esc_html__('iDeal: Payment pending.','pointfindercoreelements')
								    )
								);
			            	}
			            }elseif ($status == "refunded" || $status == "charged_back") {

			            	if ($setup4_membersettings_paymentsystem == 2) {
			        			/* Membership */
			        			$pointfinder_order_expiredate = strtotime("now");
								update_post_meta( $order_id, 'pointfinder_order_expiredate', strtotime("now"));

								$pointfinder_order_packageid = get_post_meta( $order_id, 'pointfinder_order_packageid', true );
								$packageinfo = $this->pointfinder_membership_package_details_get($pointfinder_order_packageid);

								/* This is direct payment */
								$this->PFExpireItemManualMember(
									array(
									    'order_id' => $order_id,
									    'post_author' => $user_id,
										'payment_type' => 'pags',
										'payment_err' => $status
									)
								);
								$setup33_emaillimits_listingexpired = PFMSIssetControl('setup33_emaillimits_listingexpired','','1');
								if ($setup33_emaillimits_listingexpired == 1) {
									$user_info = get_userdata( $user_id);

									$this->pointfinder_mailsystem_mailsender(
										array(
										'toemail' => $user_info->user_email,
									    'predefined' => 'directafterexpiremember',
									    'data' => array(
									    	'orderid' => $order_id,
									    	'packagename' => $packageinfo['webbupointfinder_mp_title'],
									    	'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],
									    	'expiredate' => $this->PFU_DateformatS($pointfinder_order_expiredate)
									    	),
										)
									);
								}

								$this->PFCreateProcessRecord(
				                  array(
				                    'user_id' => $user_id,
				                    'item_post_id' => $order_post_id,
				                    'processname' => esc_html__('iDeal: Payment','pointfindercoreelements').$status,
				                    'membership' => 1
				                    )
				                );

			        		}else{
			        			/* Pay per post refund. */
			        			if ($otype != 1) {
			            			wp_update_post(array('ID' => $item_post_id,'post_status' => 'pendingpayment') );
									wp_reset_postdata();
									wp_update_post(array('ID' => $order_id,'post_status' => 'pendingpayment') );
									wp_reset_postdata();

									$this->PFCreateProcessRecord(
										array(
								        'user_id' => $user_id,
								        'item_post_id' => $item_post_id,
										'processname' => esc_html__('iDeal: Payment','pointfindercoreelements').$status
									    )
									);
								}
			        		}

						}
					}
				}
	    	}
	    /*
	    * End: iDeal Payment Gateway Notification Messages
	    */


	    /*
	    * Start: Robokassa Payment Gateway Notification Messages
	    */
	    	if (!empty($_POST['InvId']) && empty($_GET['ro'])) {

	    		$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

				$robo_pass2 = $this->PFPGIssetControl('robo_pass2','','');
				$robo_currency = $this->PFPGIssetControl('robo_currency','','');
				//$order_id = esc_attr($_POST["InvId"]);

				global $wpdb;

				if ($setup4_membersettings_paymentsystem == 2) {
					$InvId = absint($_POST["InvId"]);
					$order_id = absint($_POST["Shp_oid"]);
					$item_post_id = '';
				}else{
					$item_post_id = absint($_POST["Shp_itemnum"]);
					$order_id = $wpdb->get_var( $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %d", 'pointfinder_order_roboitemid',$item_post_id));
				}
				
				if (!empty($order_id)) {
					$out_summ = esc_attr($_POST["OutSum"]);

					$inv_id_random = get_post_meta($order_id, 'pointfinder_order_roborinvid',true);

					$user_id = esc_attr($_POST["Shp_user"]);
					$otype = esc_attr($_POST["Shp_otype"]);
					$robo_crc = isset($_POST["SignatureValue"])?esc_attr($_POST["SignatureValue"]):'';

					$robo_check2 = get_post_meta( sanitize_text_field($order_id), 'pointfinder_order_robo2', true );

					$robo_crc = strtoupper($robo_crc);
					if ($setup4_membersettings_paymentsystem == 2) {
						if ($otype == 'r') {
							$membership_user_package_id = get_user_meta( $user_id, 'membership_user_package_id', true );
						}else{
							$membership_user_package_id = '';
						}
						
						$robo_new_crc = strtoupper(md5("$out_summ:$InvId:$robo_pass2:Shp_itemnum=$membership_user_package_id:Shp_oid=$order_id:Shp_otype=$otype:Shp_user=$user_id"));
					}else{
						$robo_new_crc = strtoupper(md5("$out_summ:$inv_id_random:$robo_pass2:Shp_itemnum=$item_post_id:Shp_otype=$otype:Shp_user=$user_id"));
					}

					if ($robo_new_crc == $robo_crc && !empty($robo_check2)){
						 $this->pointfinder_directpayment_success_process(
							array(
								'paymentsystem' => $setup4_membersettings_paymentsystem,
						        'item_post_id' => $item_post_id,
						        'order_post_id' => $order_id,
						        'otype' => $otype,
						        'user_id' => $user_id,
								'paymentsystem_name' => esc_html__("Robokassa","pointfindercoreelements"),
								'checkout_process_name' => 'DoExpressCheckoutPaymentRobo'
							)
						);

						delete_post_meta( sanitize_text_field($order_id), 'pointfinder_order_robo2');
					}
				}
	    	}
	    /*
	    * End: Robokassa Payment Gateway Notification Messages
	    */




	    /*
	    * Start: 2Checkout Payment Gateway Notification Messages
	    */
	    	if (isset($_POST['message_type'])) {

	    		$message_type = esc_attr( $_POST['message_type'] );

	    		$t2cho_uid = $this->PFPGIssetControl('2cho_key3','','');
	            $t2cho_sw = $this->PFPGIssetControl('2cho_key4','','');

	    		/* Verification */
				$hashOrder = $_POST['sale_id'];
				$hashInvoice = $_POST['invoice_id'];

				$StringToHash = strtoupper(md5($hashOrder . $t2cho_uid . $hashInvoice . $t2cho_sw));

				if ($StringToHash != $_POST['md5_hash']) {
					$result = false;
					error_log('2CO: Hash False');
					return;
				}

				$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

				global $wpdb;

				$order_id = $wpdb->get_var( $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s", 'pointfinder_order_t2co_vendor_order_id',esc_attr($_POST['vendor_order_id'])));

				$item_post_id = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s and post_id = %s", 'pointfinder_order_itemid',$order_id));

				$user_id = $wpdb->get_var( $wpdb->prepare("SELECT post_author FROM $wpdb->posts WHERE ID = %d", $order_id));

				$setup33_emaillimits_listingexpired = PFMSIssetControl('setup33_emaillimits_listingexpired','','1');

	    		switch ($message_type) {
	    			case 'ORDER_CREATED':

							switch ($_POST['fraud_status']) {
					            case 'pass':
					                # Do nothing because order already accepted.
						            $this->PFCreateProcessRecord(
										array(
									        'user_id' => $user_id,
									        'item_post_id' => $order_id,
											'processname' => esc_html__('2CO: Order Created and Fraud Status Passed.','pointfindercoreelements'),
											'membership' => $setup4_membersettings_paymentsystem
									    )
									);

					                break;
					            case 'fail':
					                # Stop the order
					            	$reason = esc_html__('2CO: Item suspended for fraud reason.','pointfindercoreelements');
					            	$this->pointfinder_twoco_item_suspend($order_id, $item_post_id, $user_id, $setup33_emaillimits_listingexpired,$setup4_membersettings_paymentsystem,$reason);

					                break;
					        }

	    				break;

	    			case 'FRAUD_STATUS_CHANGED':
	    					switch ($_POST['fraud_status']) {
					            case 'pass':
					                # Activate back the order.!!!
						            $this->PFCreateProcessRecord(
										array(
									        'user_id' => $user_id,
									        'item_post_id' => $order_id,
											'processname' => esc_html__('2CO: Fraud Status Changed and Fraud Status Passed.','pointfindercoreelements'),
											'membership' => $setup4_membersettings_paymentsystem
									    )
									);

					                break;
					            case 'fail':
					                # Stop the order
					            	$reason = esc_html__('2CO: Item suspended for fraud reason.','pointfindercoreelements');
					            	$this->pointfinder_twoco_item_suspend($order_id, $item_post_id, $user_id, $setup33_emaillimits_listingexpired,$setup4_membersettings_paymentsystem,$reason);

					                break;
					        }
	    				break;

	    			case 'REFUND_ISSUED':
	    					# Stop the order
	    					$reason = esc_html__('2CO: Item suspended for refund reason.','pointfindercoreelements');
			            	$this->pointfinder_twoco_item_suspend($order_id, $item_post_id, $user_id, $setup33_emaillimits_listingexpired,$setup4_membersettings_paymentsystem,$reason);
	    				break;
	    		}
	    	}
	    /*
	    * End: 2Checkout Payment Gateway Notification Messages
	    */

	}

	private function pointfinder_twoco_item_suspend($order_id, $item_post_id, $user_id, $setup33_emaillimits_listingexpired,$setup4_membersettings_paymentsystem,$reason){
		if ($setup4_membersettings_paymentsystem == 2) {
			/* Membership */
			$pointfinder_order_expiredate = strtotime("now");
			update_post_meta( $order_id, 'pointfinder_order_expiredate', strtotime("now"));

			$pointfinder_order_packageid = get_post_meta( $order_id, 'pointfinder_order_packageid', true );
			$packageinfo = $this->pointfinder_membership_package_details_get($pointfinder_order_packageid);

			/* This is direct payment */
			$this->PFExpireItemManualMember(
				array(
				    'order_id' => $order_id,
				    'post_author' => $user_id,
					'payment_type' => '2co',
					'payment_err' => $reason
				)
			);

			if ($setup33_emaillimits_listingexpired == 1) {
				$user_info = get_userdata( $user_id);

				$this->pointfinder_mailsystem_mailsender(
					array(
					'toemail' => $user_info->user_email,
				    'predefined' => 'directafterexpiremember',
				    'data' => array(
				    	'orderid' => $order_id,
				    	'packagename' => $packageinfo['webbupointfinder_mp_title'],
				    	'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],
				    	'expiredate' => $this->PFU_DateformatS($pointfinder_order_expiredate)
				    	),
					)
				);
			}

			$this->PFCreateProcessRecord(
	          array(
	            'user_id' => $user_id,
	            'item_post_id' => $order_post_id,
	            'processname' => $reason,
	            'membership' => 1
	            )
	        );
		}else{
			/* Pay per post refund. */
			$this->PFExpireItemManual(
				array(
			        'order_id' => $order_id,
			        'post_id' => $item_post_id,
			        'post_author' => $user_id,
					'payment_type' => 'web_accept',
					'payment_err' => $reason
			    )
			 );


			if ($setup33_emaillimits_listingexpired == 1) {
			 	$user_info = get_userdata( $user_id);
			 	$pointfinder_order_expiredate = strtotime("now");
				update_post_meta( $order_id, 'pointfinder_order_expiredate', strtotime("now"));

			 	$this->pointfinder_mailsystem_mailsender(
					array(
					'toemail' => $user_info->user_email,
			        'predefined' => 'directafterexpire',
			        'data' => array('ID' => $item_post_id, 'expiredate' => $pointfinder_order_expiredate,'orderid' => $order_id),
					)
				);
			 }
		}
	}
}