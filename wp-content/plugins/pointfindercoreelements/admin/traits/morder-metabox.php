<?php


if (trait_exists('PointFinderMOrderMetaboxes')) {
  return;
}

/**
 * Membership Order Post Type Metaboxes
 */
trait PointFinderMOrderMetaboxes
{
    public function PFMOrderTransArrW($value, $key)
    {
        if (!is_array($value)) {
            echo '<li class="uppcase">'.$key.' : <div class="pforders-orderdetails-lbltext">'.$value.'</div></li>';
        } else {
            array_walk($value, array($this,"PFMOrderTransArrW"));
        }
    }

    public function pointfinder_activate_morder($post_type){			
			if ($post_type == 'pointfindermorders') {
				if (isset($_GET['oa'])) {
					if ($_GET['oa'] == 1) {
						$order_id = sanitize_text_field($_GET['post']);
						$action = sanitize_text_field($_GET['action']);
						if ($action == 'edit' && !empty($order_id)) {
							global $wpdb;

							$user_id = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key = %s AND meta_value = %d",'membership_user_activeorder_ex',$order_id));
							if (!empty($user_id)) {
								$membership_user_package_id_ex = get_user_meta( $user_id, 'membership_user_package_id_ex', true );
								if (!empty($membership_user_package_id_ex)) {
									$packageinfo = $this->pointfinder_membership_package_details_get($membership_user_package_id_ex);
									$membership_user_subaction_ex = get_user_meta( $user_id, 'membership_user_subaction_ex', true );
									$membership_user_invnum_ex = get_user_meta( $user_id, 'membership_user_invnum_ex', true);

									switch ($membership_user_subaction_ex) {
										case 'r':
											$exp_date = $this->pointfinder_reenable_expired_items(array('user_id'=>$user_id,'packageinfo'=>$packageinfo,'order_id'=>$order_id,'process'=>'r'));
											update_post_meta( $order_id, 'pointfinder_order_expiredate', $exp_date);
											break;

										case 'u':
											$exp_date = $this->pointfinder_reenable_expired_items(array('user_id'=>$user_id,'packageinfo'=>$packageinfo,'order_id'=>$order_id,'process'=>'u'));
											update_post_meta( $order_id, 'pointfinder_order_expiredate', $exp_date);
											update_post_meta( $order_id, 'pointfinder_order_packageid', $membership_user_package_id_ex);

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
						                        update_user_meta( $user_id, 'membership_user_activeorder', $order_id);
						                        update_user_meta( $user_id, 'membership_user_recurring', 0);
						                      /* End: Calculate new limits */

											break;

										case 'n':
											update_post_meta( $order_id, 'pointfinder_order_expiredate', strtotime("+".$packageinfo['webbupointfinder_mp_billing_period']." ".$this->pointfinder_billing_timeunit_text_ex($packageinfo['webbupointfinder_mp_billing_time_unit'])."") );
											/*Create User Limits*/
						                    update_user_meta( $user_id, 'membership_user_package_id', $packageinfo['webbupointfinder_mp_packageid']);
						                    update_user_meta( $user_id, 'membership_user_package', $packageinfo['webbupointfinder_mp_title']);
						                    update_user_meta( $user_id, 'membership_user_item_limit', $packageinfo['webbupointfinder_mp_itemnumber']);
						                    update_user_meta( $user_id, 'membership_user_featureditem_limit', $packageinfo['webbupointfinder_mp_fitemnumber']);
						                    update_user_meta( $user_id, 'membership_user_image_limit', $packageinfo['webbupointfinder_mp_images']);
						                    update_user_meta( $user_id, 'membership_user_trialperiod', 0);
						                    update_user_meta( $user_id, 'membership_user_activeorder', $order_id);
											break;
									}
									
									
									$wpdb->update($wpdb->posts,array('post_status'=>'completed'),array('ID'=>$order_id));
									$wpdb->update($wpdb->posts,array('post_status'=>'publish'),array('ID'=>$membership_user_invnum_ex));

		                    		update_post_meta( $order_id, 'pointfinder_order_bankcheck', 0 );
		                    		update_post_meta( $order_id, 'pointfinder_order_datetime_approval', strtotime("now") );

									delete_user_meta($user_id, 'membership_user_package_id_ex');
					                delete_user_meta($user_id, 'membership_user_activeorder_ex');
					                delete_user_meta($user_id, 'membership_user_subaction_ex');
					                delete_user_meta($user_id, 'membership_user_invnum_ex');

					                $this->PFCreateProcessRecord(
					                  array( 
					                    'user_id' => $user_id,
					                    'item_post_id' => $order_id,
					                    'processname' => esc_html__('Bank Transfer APPROVED & plan ACTIVATED by ADMIN','pointfindert2d'),
					                    'membership' => 1
					                    )
					                );

					                $user_info = get_userdata( $user_id );
									$this->pointfinder_mailsystem_mailsender(
										array(
											'toemail' => $user_info->user_email,
									        'predefined' => 'bankpaymentapprovedmember',
									        'data' => array('ID' => $order_id),
											)
									);
					            }
							}
							
						}
					}
				}
			}
	}

    public function pointfinder_morders_add_meta_box($post_type)
    {
        if ($post_type == 'pointfindermorders') {
            add_meta_box(
                    'pointfinder_morders_info',
                    esc_html__('ORDER INFO', 'pointfindercoreelements'),
                    array($this,'pointfinder_morders_meta_box_orderinfo'),
                    'pointfindermorders',
                    'side',
                    'high'
                );

            add_meta_box(
                    'pointfinder_morders_trans',
                    esc_html__('TRANSACTION HISTORY', 'pointfindercoreelements'),
                    array($this,'pointfinder_morders_meta_box_ordertrans'),
                    'pointfindermorders',
                    'normal',
                    'core'
                );

            add_meta_box(
                    'pointfinder_morders_process',
                    esc_html__('PROCESS HISTORY', 'pointfindercoreelements'),
                    array($this,'pointfinder_morders_meta_box_orderprocess'),
                    'pointfindermorders',
                    'normal',
                    'core'
                );

            add_meta_box(
                    'pointfinder_morders_basicinfo',
                    esc_html__('LISTING INFO', 'pointfindercoreelements'),
                    array($this,'pointfinder_morders_meta_box_order_basicinfo'),
                    'pointfindermorders',
                    'side',
                    'core'
                );
        }
    }

    /**
	*Start : Order Info Content
	**/
		public function pointfinder_morders_meta_box_orderinfo( $post ) {

			$prderinfo_itemid = get_post_meta( $post->ID, 'pointfinder_order_packageid', true );
			$prderinfo_user = get_post_meta( $post->ID, 'pointfinder_order_userid', true );

			$current_post_status = get_post_status();

			if($current_post_status == 'completed'){
			    $prderinfo_statusorder = '<span class="pforders-orderdetails-lblcompleted">'.esc_html__('PAYMENT COMPLETED','pointfindercoreelements').'</span>';
			}elseif($current_post_status == 'pendingpayment'){
				$prderinfo_statusorder = '<span class="pforders-orderdetails-lblpending">'.esc_html__('PENDING PAYMENT','pointfindercoreelements').'</span>';
			}elseif($current_post_status == 'pfcancelled'){
				$prderinfo_statusorder = '<span class="pforders-orderdetails-lblcancel">'.esc_html__('CANCELLED','pointfindercoreelements').'</span>';
			}elseif($current_post_status == 'pfsuspended'){
				$prderinfo_statusorder = '<span class="pforders-orderdetails-lblpending">'.esc_html__('SUSPENDED','pointfindercoreelements').'</span>';
			}
			$itemnamex = get_the_title($prderinfo_itemid);

			$itemname = ($itemnamex!= false)? $itemnamex:esc_html__('Item Deleted','pointfindercoreelements');

			echo '<ul class="pforders-orderdetails-ul">';
				echo '<li>';
				esc_html_e( 'ORDER ID : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">'.get_the_title().'</div>';
				echo '</li> ';

				if (!empty($prderinfo_statusorder) && !isset($_GET['oa'])) {
					echo '<li>';
					esc_html_e( 'ORDER STATUS : ', 'pointfindercoreelements' );
					echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_statusorder.'</div>';
					echo '</li> ';
				}
				

				$userdata = get_user_by('id',$prderinfo_user);

				if (!empty($userdata)) {
					echo '<li>';
					esc_html_e( 'USER : ', 'pointfindercoreelements' );
					echo '<div class="pforders-orderdetails-lbltext"><a href="'.get_edit_user_link($prderinfo_user).'" target="_blank" title="'.esc_html__('Click for user details','pointfindercoreelements').'">'.$prderinfo_user.' - '.$userdata->nickname.'</a></div>';
					echo '</li> ';
				}
				

				echo '<li>';
				esc_html_e( 'PLAN : ', 'pointfindercoreelements' );
				if($itemnamex!= false){
					echo '<div class="pforders-orderdetails-lbltext"><a href="'.get_edit_post_link($prderinfo_itemid).'" target="_blank" title="'.esc_html__('Click for open plan','pointfindercoreelements').'">'.$prderinfo_itemid.' - '.$itemname.'</a></div>';
				}else{
					echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_itemid.' - '.$itemname.'</div>';
				}
				echo '</li> ';

			echo '</ul>';
		}
	/**
	*End : Order Info Content
	**/

	/**
	*Start : Listing Info Content
	**/
		public function pointfinder_morders_meta_box_order_basicinfo( $post ) {

			$prderinfo_ordertime = $this->PFU_GetPostOrderDate($post->ID);
			$pointfinder_order_packageid = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_packageid', true ));
			$prderinfo_recurring = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_recurring', true ));
			$prderinfo_order_bankcheck = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_bankcheck', true ));
			$prderinfo_order_appdate = $this->PFU_DateformatS(get_post_meta( $post->ID, 'pointfinder_order_datetime_approval', true ),1);
			$prderinfo_order_expdate = $this->PFU_DateformatS(get_post_meta( $post->ID, 'pointfinder_order_expiredate', true ),1);

			$prderinfo_recurring_text = ($prderinfo_recurring == 1) ? esc_html__('Recurring Payment','pointfindercoreelements') : esc_html__('Direct Payment','pointfindercoreelements') ;

			if($prderinfo_order_bankcheck == 1){
				$prderinfo_recurring_text .= ' - '.esc_html__('Bank Transfer','pointfindercoreelements');
			}

			$setup20_paypalsettings_decimals = $this->PFSAIssetControl('setup20_paypalsettings_decimals','','2');
			
			
			$packageinfo = $this->pointfinder_membership_package_details_get($pointfinder_order_packageid);

			echo '<ul class="pforders-orderdetails-ul">';

				if (class_exists('SitePress')) {
					$lang = sanitize_text_field( $_GET['lang'] );
				} else {
					$lang = '';
				}
				
				if($prderinfo_order_bankcheck == 1){
					echo '<li>';
					echo '<div class="pforders-orderdetails-lbltext"><a href="'.admin_url('post.php?post='.$post->ID.'&action=edit&oa=1&lang='.$lang).'" class="pf-bank-approval">'.esc_html__('Click for Approve Bank Transfer!','pointfindercoreelements' ).'</a></div>';
					echo '</li> ';
				}

				echo '<li>';
				esc_html_e( 'Package : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">'.$packageinfo['webbupointfinder_mp_title'].'</div>';
				echo '</li> ';

				echo '<li>';
				esc_html_e( 'Order Type : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_recurring_text.'</div>';
				echo '</li> ';

				echo '<li>';
				esc_html_e( 'Order Date : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_ordertime.'</div>';
				echo '</li> ';
				

				if ($prderinfo_order_appdate != '') {
					echo '<li>';
					esc_html_e( 'Approval Date : ', 'pointfindercoreelements' );
					echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_order_appdate.'</div>';
					echo '</li> ';
				}

				if ($prderinfo_order_expdate != '') {
					echo '<li>';
					esc_html_e( 'Expire Date : ', 'pointfindercoreelements' );
					echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_order_expdate.'</div>';
					echo '</li> ';
				}

				echo '<li>';
				esc_html_e( 'Order Total : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">'.$packageinfo['packageinfo_priceoutput_text'].'</div>';
				echo '</li> ';

				echo '<li>';
				esc_html_e( 'Billing Period : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">'.$packageinfo['webbupointfinder_mp_billing_period'].' '.$packageinfo['webbupointfinder_mp_billing_time_unit_text'].'</div>';
				echo '</li> ';
				
				if($packageinfo['webbupointfinder_mp_trial'] == 1){
				echo '<li>';
				esc_html_e( 'Trial Billing Period : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">'.$packageinfo['webbupointfinder_mp_trial_period'].' '.$packageinfo['webbupointfinder_mp_billing_time_unit_text'].'</div>';
				echo '</li> ';
				}

				

			echo '</ul>';
		}
	/**
	*End : Listing Info Content
	**/

	/**
	*Start : Order Transaction Content
	**/
		public function pointfinder_morders_meta_box_ordertrans( $post ) {
			global $wpdb;

			$prdertrans_itemid = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_itemid', true ));
			$prderstans_paymentrecs = get_post_meta( $post->ID, 'pointfinder_order_paymentrecs', true );
			
			if($prderstans_paymentrecs != ''){
				

				$transaction_idlist = json_decode($prderstans_paymentrecs,true);

				if ($this->PFControlEmptyArr($transaction_idlist)) {
					echo '<div class="accordion vertical">';
					
					$i = 0;
					$transaction_idlist = array_reverse($transaction_idlist);

					$uncheckarr = array('BankTransferCancel','BankTransfer', 'RecurringPayment','RecurringPaymentPending','ManageRecurringPaymentsProfileStatus','DoExpressCheckoutPaymentStripe','DoExpressCheckoutPaymentPags','DoExpressCheckoutPaymentiDeal','DoExpressCheckoutPaymentRobo','DoExpressCheckoutPaymentPayu','DoExpressCheckoutPaymentIyzico','SetExpressCheckoutStripe');

					foreach ($transaction_idlist as $transaction) {

						echo '<section id="'.$i.'">';
						$token_trans = (isset($transaction['token']))?$transaction['token']:'';
						if(!in_array($transaction['processname'], $uncheckarr)){
							echo '<h2><a href="#'.$i.'">'.esc_html__('Date : ','pointfindercoreelements').''.$transaction['datetime'].' / '.$this->PFProcessNameFilter($transaction['processname']).' ('.$token_trans.')</a></h2>';
						}elseif ($transaction['processname'] == 'DoExpressCheckoutPaymentStripe' || $transaction['processname'] == 'SetExpressCheckoutStripe') {
							echo '<h2><a href="#'.$i.'">'.esc_html__('Date : ','pointfindercoreelements').''.$transaction['datetime'].' / '.$this->PFProcessNameFilter($transaction['processname']).' ('.esc_html__('STRIPE PAYMENT','pointfindercoreelements').')</a></h2>';
						}else{
							echo '<h2><a href="#'.$i.'">'.esc_html__('Date : ','pointfindercoreelements').''.$transaction['datetime'].' / '.$this->PFProcessNameFilter($transaction['processname']).'</a></h2>';
						}
						echo '<p>';
								
								echo '<ul class="pforders-orderdetails-ul">';

								switch ($transaction['processname']) {
									case 'BankTransferCancel':
										echo '<li class="uppcase"><div class="pforders-orderdetails-lbltext">'.esc_html__('Bank transfer cancelled by user.','pointfindercoreelements').'</div></li>';
										break;
									case 'BankTransfer':
										echo '<li class="uppcase"><div class="pforders-orderdetails-lbltext">'.esc_html__('Bank transfer waiting.','pointfindercoreelements').'</div></li>';
										break;
									case 'CancelPayment':
										echo '<li class="uppcase"><div class="pforders-orderdetails-lbltext">'.esc_html__('User cancelled this transaction. There is no extra information.','pointfindercoreelements').'</div></li>';
										break;
									case 'DoExpressCheckoutPayment':
									case 'DoExpressCheckoutPaymentStripe':
									case 'DoExpressCheckoutPaymentPags':
									case 'DoExpressCheckoutPaymentiDeal':
									case 'DoExpressCheckoutPaymentRobo':
									case 'DoExpressCheckoutPaymentIyzico':
									case 'DoExpressCheckoutPaymentPayu':
									case 'CreateRecurringPaymentsProfile':
									case 'ManageRecurringPaymentsProfileStatus':
									case 'GetExpressCheckoutDetails':
									case 'SetExpressCheckout':
									case 'SetExpressCheckoutStripe':
									case 'GetRecurringPaymentsProfileDetails':
									case 'RecurringPayment':
									case 'RecurringPaymentPending':
										array_walk($transaction,array($this,"PFMOrderTransArrW"));
										break;

								}
								
								echo '</ul>';
							
						echo '</p>';
						echo '</section>'; 
						$i++;
					}
					echo '</div>';
				}
			}

		}
	/**
	*End : Order Transaction Content
	**/

	/**
	*Start : Order Process Content
	**/
		public function pointfinder_morders_meta_box_orderprocess( $post ) {
			global $wpdb;

			$prdertrans_itemid = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_itemid', true ));
			$prderstans_processrecs = get_post_meta( $post->ID, 'pointfinder_order_processrecs', true );
			
			if($prderstans_processrecs != ''){
				

				$transaction_idlist = json_decode($prderstans_processrecs,true);

				if ($this->PFControlEmptyArr($transaction_idlist)) {
					echo '<div class="accordion vertical">';
					
					$i = 0;
					$transaction_idlist = array_reverse($transaction_idlist);
					foreach ($transaction_idlist as $transaction) {

						echo '<section id="x'.$i.'">';
						echo '<h2><a href="#k'.$i.'">'.esc_html__('Date : ','pointfindercoreelements').''.$transaction['datetime'].' / '.$transaction['processname'].'</a></h2>';
						echo '</section>'; 
						$i++;
					}
					echo '</div>';
				}
			}
		}
	/**
	*End : Order Process Content
	**/
}
