<?php

if (trait_exists('PointFinderOrderMetaboxes')) {
  return;
}

/**
 * Order Post Type Metaboxes
 */
trait PointFinderOrderMetaboxes
{
    public function PFOrderTransArrW($value, $key)
    {
        if (!is_array($value)) {
            echo '<li class="uppcase">'.$key.' : <div class="pforders-orderdetails-lbltext">'.$value.'</div></li>';
        } else {
            array_walk($value, array($this,"PFOrderTransArrW"));
        }
    }

    public function pointfinder_orders_add_meta_box($post_type)
    {
        if ($post_type == 'pointfinderorders') {
            add_meta_box(
                    'pointfinder_orders_info',
                    esc_html__('ORDER INFO', 'pointfindercoreelements'),
                    array($this,'pointfinder_orders_meta_box_orderinfo'),
                    'pointfinderorders',
                    'side',
                    'high'
                );

            add_meta_box(
                    'pointfinder_orders_trans',
                    esc_html__('TRANSACTION HISTORY', 'pointfindercoreelements'),
                    array($this,'pointfinder_orders_meta_box_ordertrans'),
                    'pointfinderorders',
                    'normal',
                    'core'
                );

            add_meta_box(
                    'pointfinder_orders_process',
                    esc_html__('PROCESS HISTORY', 'pointfindercoreelements'),
                    array($this,'pointfinder_orders_meta_box_orderprocess'),
                    'pointfinderorders',
                    'normal',
                    'core'
                );

            add_meta_box(
                    'pointfinder_orders_basicinfo',
                    esc_html__('LISTING INFO', 'pointfindercoreelements'),
                    array($this,'pointfinder_orders_meta_box_order_basicinfo'),
                    'pointfinderorders',
                    'side',
                    'core'
                );
        }
    }

    /**
	*Start : Order Info Content
	**/
		public function pointfinder_orders_meta_box_orderinfo( $post ) {

			$prderinfo_itemid = get_post_meta( $post->ID, 'pointfinder_order_itemid', true );
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

				if (!empty($prderinfo_statusorder)) {
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
				esc_html_e( 'ITEM : ', 'pointfindercoreelements' );
				if($itemnamex!= false){
					echo '<div class="pforders-orderdetails-lbltext"><a href="'.get_edit_post_link($prderinfo_itemid).'" target="_blank" title="'.esc_html__('Click for open item','pointfindercoreelements').'">'.$prderinfo_itemid.' - '.$itemname.'</a></div>';
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
		public function pointfinder_orders_meta_box_order_basicinfo( $post ) {

			$prderinfo_ordertime = $this->PFU_GetPostOrderDate($post->ID);
			$prderinfo_recurring = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_recurring', true ));
			$prderinfo_order_total = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_price', true ));
			$prderinfo_order_totalsign = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_pricesign', true ));
			$prderinfo_order_time = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_listingtime', true ));
			$prderinfo_order_pname = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_listingpname', true ));
			$prderinfo_order_bankcheck = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_bankcheck', true ));
			$prderinfo_order_appdate = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_datetime_approval', true ));
			$prderinfo_order_expdate = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_expiredate', true ));
			$prderinfo_order_expdate_featured = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_expiredate_featured', true ));

			$prderinfo_recurring_text = ($prderinfo_recurring == 1) ? esc_html__('Recurring Payment','pointfindercoreelements') : esc_html__('Direct Payment','pointfindercoreelements') ;

			if($prderinfo_order_bankcheck == 1){$prderinfo_recurring_text .= ' - '.esc_html__('Bank Transfer','pointfindercoreelements');}

			$setup20_paypalsettings_decimals = PFSAIssetControl('setup20_paypalsettings_decimals','','2');
			

			echo '<ul class="pforders-orderdetails-ul">';


				echo '<li>';
				esc_html_e( 'Order Package : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_order_pname.'</div>';
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

				if ($prderinfo_order_expdate_featured != '') {
					echo '<li>';
					esc_html_e( 'Expire Date (Featured) : ', 'pointfindercoreelements' );
					echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_order_expdate_featured.'</div>';
					echo '</li> ';
				}

				echo '<li>';
				esc_html_e( 'Order Total : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">'.number_format($prderinfo_order_total, $setup20_paypalsettings_decimals, '.', ',').$prderinfo_order_totalsign.'</div>';
				echo '</li> ';

				echo '<li>';
				esc_html_e( 'Listing Period : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_order_time.esc_html__(' days','pointfindercoreelements').'</div>';
				echo '</li> ';
				

			echo '</ul>';
		}
	/**
	*End : Listing Info Content
	**/


	/**
	*Start : Order Transaction Content
	**/
		public function pointfinder_orders_meta_box_ordertrans( $post ) {
			global $wpdb;

			$prdertrans_itemid = esc_attr(get_post_meta( $post->ID, 'pointfinder_order_itemid', true ));
			$prderstans_paymentrecs = get_post_meta( $post->ID, 'pointfinder_order_paymentrecs', true );
	
			if($prderstans_paymentrecs != ''){
				

				$transaction_idlist = json_decode($prderstans_paymentrecs,true);

				if ($this->PFControlEmptyArr($transaction_idlist)) {
					echo '<div class="accordion vertical">';
					
					$i = 0;
					$transaction_idlist = array_reverse($transaction_idlist);

					$uncheckarr = array('BankTransferCancel','BankTransfer', 'RecurringPayment','RecurringPaymentPending','ManageRecurringPaymentsProfileStatus','DoExpressCheckoutPaymentStripe');


					foreach ($transaction_idlist as $transaction) {

						echo '<section id="'.$i.'">';

						if(!in_array($transaction['processname'], $uncheckarr)){
							echo '<h2><a href="#'.$i.'">'.esc_html__('Date : ','pointfindercoreelements').''.$transaction['datetime'].' / '.$this->PFProcessNameFilter($transaction['processname']).' ('.$transaction['token'].')</a></h2>';
						}elseif ($transaction['processname'] == 'DoExpressCheckoutPaymentStripe') {
							echo '<h2><a href="#'.$i.'">'.esc_html__('Date : ','pointfindercoreelements').''.$transaction['datetime'].' / '.$this->PFProcessNameFilter($transaction['processname']).' ('.esc_html__('STRIPE PAYMENT','pointfindercoreelements').')</a></h2>';
						}elseif ($transaction['processname'] == 'DoExpressCheckoutPaymentPags') {
							echo '<h2><a href="#'.$i.'">'.esc_html__('Date : ','pointfindercoreelements').''.$transaction['datetime'].' / '.$this->PFProcessNameFilter($transaction['processname']).' ('.esc_html__('PAGSEGURO PAYMENT','pointfindercoreelements').')</a></h2>';
						}elseif ($transaction['processname'] == 'DoExpressCheckoutPaymentPayu') {
							echo '<h2><a href="#'.$i.'">'.esc_html__('Date : ','pointfindercoreelements').''.$transaction['datetime'].' / '.$this->PFProcessNameFilter($transaction['processname']).' ('.esc_html__('PAYUMONEY PAYMENT','pointfindercoreelements').')</a></h2>';
						}elseif ($transaction['processname'] == 'DoExpressCheckoutPaymentIyzico') {
							echo '<h2><a href="#'.$i.'">'.esc_html__('Date : ','pointfindercoreelements').''.$transaction['datetime'].' / '.$this->PFProcessNameFilter($transaction['processname']).' ('.esc_html__('Iyzico PAYMENT','pointfindercoreelements').')</a></h2>';
						}elseif ($transaction['processname'] == 'DoExpressCheckoutPaymentiDeal') {
							echo '<h2><a href="#'.$i.'">'.esc_html__('Date : ','pointfindercoreelements').''.$transaction['datetime'].' / '.$this->PFProcessNameFilter($transaction['processname']).' ('.esc_html__('iDeal PAYMENT','pointfindercoreelements').')</a></h2>';
						}elseif ($transaction['processname'] == 'DoExpressCheckoutPaymentRobo') {
							echo '<h2><a href="#'.$i.'">'.esc_html__('Date : ','pointfindercoreelements').''.$transaction['datetime'].' / '.$this->PFProcessNameFilter($transaction['processname']).' ('.esc_html__('Robokassa PAYMENT','pointfindercoreelements').')</a></h2>';
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
									case 'CreateRecurringPaymentsProfile':
									case 'ManageRecurringPaymentsProfileStatus':
									case 'GetExpressCheckoutDetails':
									case 'SetExpressCheckout':
									case 'SetExpressCheckoutStripe':
									case 'GetRecurringPaymentsProfileDetails':
									case 'RecurringPayment':
									case 'RecurringPaymentPending':

										array_walk($transaction,array($this,"PFOrderTransArrW"));
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
		public function pointfinder_orders_meta_box_orderprocess( $post ) {
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
