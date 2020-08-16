<?php 
if (trait_exists('PointFinderDashFunctions')) {
  return;
}

/**
 * Dashboard Functions
 */
trait PointFinderDashFunctions
{
	
	public function pointfinder_directpayment_success_process($params = array()){
		global $wpdb;
		$defaults = array( 
	        'paymentsystem' => 1,
	        'item_post_id' => '',
	        'order_post_id' => '',
	        'otype' => '',
	        'user_id' => '',
			'paymentsystem_name' => '',
			'checkout_process_name' => ''
	    );
		$params = array_merge($defaults, $params);
		$setup4_membersettings_paymentsystem = $params['paymentsystem'];
		$order_id = $params['order_post_id'];
		$item_post_id = $params['item_post_id'];
		$otype = $params['otype'];
		$user_id = $params['user_id'];
		$paymentsystem_name = $params['paymentsystem_name'];

		delete_post_meta($order_id, 'pointfinder_order_txnid');

		if ($setup4_membersettings_paymentsystem == 2) {
			/* Start: Membership */
				$membership_user_package_id = get_user_meta( $user_id, 'membership_user_package_id_ex', true );
				$sub_action = get_user_meta( $user_id, 'membership_user_subaction_ex', true );

				$packageinfo = $this->pointfinder_membership_package_details_get($membership_user_package_id);
				$order_post_id = $order_id;

				if (empty($sub_action)) {
					$sub_action = $otype;
				}

				$this->PF_CreatePaymentRecord(
	                array(
	                'user_id' =>  $user_id,
	                'item_post_id'  =>  $membership_user_package_id,
	                'order_post_id' => $order_post_id,
	                'processname' =>  $params['checkout_process_name'],
	                'token'	=>	'Checkout Process Completed ('.$order_id.'-'.$item_post_id.') '.$paymentsystem_name,
	                'status'  =>  'Success',
	                'membership' => 1
	                )
	          	);

				delete_user_meta($user_id, 'membership_user_package_id_ex');
	            delete_user_meta($user_id, 'membership_user_activeorder_ex');
	            delete_user_meta($user_id, 'membership_user_subaction_ex');

	            if ($sub_action == 'r') {
	                $exp_date = $this->pointfinder_reenable_expired_items(array('user_id'=>$user_id,'packageinfo'=>$packageinfo,'order_id'=>$order_post_id,'process' => 'r'));
	                $app_date = strtotime("now");
	                update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
	               
	                /* - Creating record for process system. */
	                $this->PFCreateProcessRecord(
	                  array( 
	                    'user_id' => $user_id,
	                    'item_post_id' => $order_post_id,
	                    'processname' => $paymentsystem_name.esc_html__(': Package Renew Process Completed','pointfindercoreelements'),
	                    'membership' => 1
	                    )
	                );

	                /* Create an invoice for this */
	                $this->PF_CreateInvoice(
	                  array( 
	                    'user_id' => $user_id,
	                    'item_id' => 0,
	                    'order_id' => $order_post_id,
	                    'description' => $packageinfo['webbupointfinder_mp_title'].'-'.esc_html__('Renew','pointfindercoreelements'),
	                    'processname' => $paymentsystem_name.esc_html__(' Payment','pointfindercoreelements'),
	                    'amount' => $packageinfo['packageinfo_priceoutput_text'],
	                    'datetime' => strtotime("now"),
	                    'packageid' => $packageinfo['webbupointfinder_mp_packageid'],
	                    'status' => 'publish'
	                  )
	                );
	            }elseif ($sub_action == 'u') {
	                $exp_date = $this->pointfinder_reenable_expired_items(array('user_id'=>$user_id,'packageinfo'=>$packageinfo,'order_id'=>$order_post_id,'process' => 'u'));
	                update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
	                update_post_meta( $order_post_id, 'pointfinder_order_packageid', $membership_user_package_id);

	                /* Start: Calculate item/featured item count and remove from new package. */
	                  $total_icounts = $this->pointfinder_membership_count_ui($user_id);

	                  /*Count User's Items*/
	                  $user_post_count = 0;
	                  $user_post_count = $total_icounts['item_count'];

	                  /*Count User's Featured Items*/
	                  $users_post_featured = 0;
	                  $users_post_featured = $total_icounts['fitem_count'];

	                  if ($packageinfo['webbupointfinder_mp_itemnumber'] != -1) {
	                    $new_item_limit = $packageinfo['webbupointfinder_mp_itemnumber'] - $user_post_count;
	                  }else{
	                    $new_item_limit = $packageinfo['webbupointfinder_mp_itemnumber'];
	                  }
	                  
	                  $new_fitem_limit = $packageinfo['webbupointfinder_mp_fitemnumber'] - $users_post_featured;


	                  /*Create User Limits*/
	                  update_user_meta( $user_id, 'membership_user_package_id', $packageinfo['webbupointfinder_mp_packageid']);
	                  update_user_meta( $user_id, 'membership_user_package', $packageinfo['webbupointfinder_mp_title']);
	                  update_user_meta( $user_id, 'membership_user_item_limit', $new_item_limit);
	                  update_user_meta( $user_id, 'membership_user_featureditem_limit', $new_fitem_limit);
	                  update_user_meta( $user_id, 'membership_user_image_limit', $packageinfo['webbupointfinder_mp_images']);
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
	                    'description' => $packageinfo['webbupointfinder_mp_title'].'-'.esc_html__('Upgrade','pointfindercoreelements'),
	                    'processname' => sprintf(esc_html__('%s Payment','pointfindercoreelements'),$paymentsystem_name),
	                    'amount' => $packageinfo['packageinfo_priceoutput_text'],
	                    'datetime' => strtotime("now"),
	                    'packageid' => $packageinfo['webbupointfinder_mp_packageid'],
	                    'status' => 'publish'
	                  )
	                );

	                /* - Creating record for process system. */
	                $this->PFCreateProcessRecord(
	                  array( 
	                    'user_id' => $user_id,
	                    'item_post_id' => $order_post_id,
	                    'processname' => sprintf(esc_html__('%s Package Upgrade Process Completed','pointfindercoreelements'),$paymentsystem_name),
	                    'membership' => 1
	                    )
	                );
	            }else{
	                update_post_meta( $order_post_id, 'pointfinder_order_expiredate', strtotime("+".$packageinfo['webbupointfinder_mp_billing_period']." ".$this->pointfinder_billing_timeunit_text_ex($packageinfo['webbupointfinder_mp_billing_time_unit'])."") );
	                /* - Creating record for process system. */
	                $this->PFCreateProcessRecord(
	                  array( 
	                    'user_id' => $user_id,
	                    'item_post_id' => $order_post_id,
	                    'processname' => sprintf(esc_html__("%s Package Purchase Process Completed",'pointfindercoreelements'),$paymentsystem_name),
	                    'membership' => 1
	                    )
	                );

	                /*Create User Limits*/
	                update_user_meta( $user_id, 'membership_user_package_id', $packageinfo['webbupointfinder_mp_packageid']);
	                update_user_meta( $user_id, 'membership_user_package', $packageinfo['webbupointfinder_mp_title']);
	                update_user_meta( $user_id, 'membership_user_item_limit', $packageinfo['webbupointfinder_mp_itemnumber']);
	                update_user_meta( $user_id, 'membership_user_featureditem_limit', $packageinfo['webbupointfinder_mp_fitemnumber']);
	                update_user_meta( $user_id, 'membership_user_image_limit', $packageinfo['webbupointfinder_mp_images']);
	                update_user_meta( $user_id, 'membership_user_trialperiod', 0);
	                update_user_meta( $user_id, 'membership_user_activeorder', $order_post_id);
	                update_user_meta( $user_id, 'membership_user_recurring', 0);

	                /* Create an invoice for this */
	                $this->PF_CreateInvoice(
	                  array( 
	                    'user_id' => $user_id,
	                    'item_id' => 0,
	                    'order_id' => $order_post_id,
	                    'description' => $packageinfo['webbupointfinder_mp_title'],
	                    'processname' => sprintf(esc_html__('%s Payment','pointfindercoreelements'),$paymentsystem_name),
	                    'amount' => $packageinfo['packageinfo_priceoutput_text'],
	                    'datetime' => strtotime("now"),
	                    'packageid' => $packageinfo['webbupointfinder_mp_packageid'],
	                    'status' => 'publish'
	                  )
	                );
	            }
				

				update_post_meta( $order_id, 'pointfinder_order_pagscheck', 0);

				global $wpdb;
				$wpdb->update($wpdb->posts,array('post_status'=>'completed'),array('ID'=>$order_post_id));

				$admin_email = get_option( 'admin_email' );
				$setup33_emailsettings_mainemail = PFMSIssetControl('setup33_emailsettings_mainemail','',$admin_email);
				$user_info = get_userdata( $user_id);

				$this->pointfinder_mailsystem_mailsender(
					array(
					'toemail' => $user_info->user_email,
					  'predefined' => 'paymentcompletedmember',
					  'data' => array(
					    'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],
					    'packagename' => $packageinfo['webbupointfinder_mp_title']),
					)
				);

				$this->pointfinder_mailsystem_mailsender(
					array(
					'toemail' => $setup33_emailsettings_mainemail,
					  'predefined' => 'newpaymentreceivedmember',
					  'data' => array(
					    'ID'=> $order_post_id,
					    'paymenttotal' => $packageinfo['packageinfo_priceoutput_text'],
					    'packagename' => $packageinfo['webbupointfinder_mp_title']),
					)
				);
			/* End: Membership */
		}else{
			/* Start: Pay per post */
				$setup20_paypalsettings_decimals = PFSAIssetControl('setup20_paypalsettings_decimals','','2');

				$pointfinder_sub_order_change = esc_attr(get_post_meta( $order_id, 'pointfinder_sub_order_change', true ));
				$setup31_userlimits_userpublish = PFSAIssetControl('setup31_userlimits_userpublish','','0');
				$publishstatus = ($setup31_userlimits_userpublish == 1) ? 'publish' : 'pendingapproval' ;

				if ($pointfinder_sub_order_change == 1 && $otype == 1) {
					/* Upgrade process */
					$pointfinder_order_price = esc_attr(get_post_meta( $order_id, 'pointfinder_sub_order_price', true ));
					$pointfinder_order_listingpname = esc_attr(get_post_meta($order_id, 'pointfinder_sub_order_listingpname', true));
					$pointfinder_order_listingpid = esc_attr(get_post_meta($order_id, 'pointfinder_sub_order_listingpid', true ));
					$apipackage_name = $pointfinder_order_listingpname. esc_html__('(Plan/Featured/Category Change)','pointfindercoreelements');
				}else{
					/* Normal process */
	                $pointfinder_order_price = esc_attr(get_post_meta( $order_id, 'pointfinder_order_price', true ));
	                $pointfinder_order_listingpname = esc_attr(get_post_meta($order_id, 'pointfinder_order_listingpname', true)); 
	                $pointfinder_order_listingpid = esc_attr(get_post_meta($order_id, 'pointfinder_order_listingpid', true ));	
	                $apipackage_name = $pointfinder_order_listingpname;
				}

				$total_package_price = number_format($pointfinder_order_price, $setup20_paypalsettings_decimals, '.', ',');
				$item_post_id = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s and post_id = %s", 'pointfinder_order_itemid',$order_id));

				if ($pointfinder_sub_order_change == 1 && $otype == 1) {
					$pointfinder_sub_order_changedvals = get_post_meta( $order_id, 'pointfinder_sub_order_changedvals', true );
																									
					$this->pointfinder_additional_orders(
						array(
							'changedvals' => $pointfinder_sub_order_changedvals,
							'order_id' => $order_id,
							'post_id' => $item_post_id
						)
					);
				}else{
	    			wp_update_post(array('ID' => $item_post_id,'post_status' => $publishstatus) );
					wp_reset_postdata();
					wp_update_post(array('ID' => $order_id,'post_status' => 'completed') );
					wp_reset_postdata();
				
					$this->pointfinder_order_fallback_operations($order_id,$pointfinder_order_price);
				}

				$this->PFCreateProcessRecord(
					array( 
			        'user_id' => $user_id,
			        'item_post_id' => $item_post_id,
					'processname' => sprintf(esc_html__('%s Payment approved.','pointfindercoreelements'),$paymentsystem_name)
				    )
				);

				/* Start: Create an invoice for this */
					$this->PF_CreateInvoice(
						array( 
						  'user_id' => $user_id,
						  'item_id' => $item_post_id,
						  'order_id' => $order_id,
						  'description' => $apipackage_name,
						  'processname' => sprintf(esc_html__('%s Payment','pointfindercoreelements'),$paymentsystem_name),
						  'amount' => $total_package_price,
						  'datetime' => strtotime("now"),
						  'packageid' => 0,
						  'status' => 'publish'
						)
					);
				/* End: Create an invoice for this */


				/* Start: Create payment record for this */
					$this->PF_CreatePaymentRecord(
						array(
						'user_id'	=>	$user_id,
						'item_post_id'	=>	$item_post_id,
						'order_post_id'	=> $order_id,
						'token'	=>	'Checkout Process Completed ('.$order_id.'-'.$item_post_id.') '.$paymentsystem_name,
						'processname'	=>	$params['checkout_process_name'],
						'status'	=>	'Success'
						)
					);
				/* End: Create payment record for this */


				/* Start: Sending Email */
					$user_info = get_userdata( $user_id );
					$mail_item_title = get_the_title($item_post_id);

					$admin_email = get_option( 'admin_email' );
					$setup33_emailsettings_mainemail = PFMSIssetControl('setup33_emailsettings_mainemail','',$admin_email);

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
				/* End: Sending Email */
			/* End: Pay Per Post */
		}
	}

	public function pointfinder_billing_timeunit_text_paypal($webbupointfinder_mp_billing_time_unit){
		switch ($webbupointfinder_mp_billing_time_unit) {
	    	case 'yearly':
	    		$webbupointfinder_mp_billing_time_unit_text = 'Year';
	    		break;

	    	case 'monthly':
	    		$webbupointfinder_mp_billing_time_unit_text = 'Month';
	    		break;
	    	
	    	default:
	    		$webbupointfinder_mp_billing_time_unit_text = 'Day';
	    		break;
	    }
	    return $webbupointfinder_mp_billing_time_unit_text;
	}

	public function PFU_Dateformat($value,$showtime = 0){
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
			return esc_html__( "-", "pointfindercoreelements" );
		}
		if ($showtime != 1) {
			return date_i18n( get_option( 'date_format' ), strtotime($value) );
		}else{
			return date_i18n( get_option('date_format') . ' ' . get_option('time_format'), strtotime($value) );
		}
		
	}

}