<?php 

/**
 * Common User Functions
 */
trait PointFinderCUFunctions
{

    public function PF_Cancel_recurring_payment($params = array()){
		$defaults = array( 
	        'user_id' => '',
	        'profile_id' => '',
	        'item_post_id' => '',
	        'order_post_id' => '',
	    );

		$params = array_merge($defaults, $params);

		$method = 'ManageRecurringPaymentsProfileStatus';
		
		$paypal_price_unit = $this->PFSAIssetControl('setup20_paypalsettings_paypal_price_unit','','USD');
		$paypal_sandbox = $this->PFSAIssetControl('setup20_paypalsettings_paypal_sandbox','','0');
		$paypal_api_user = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_user','','');
		$paypal_api_pwd = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_pwd','','');
		$paypal_api_signature = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_signature','','2');

		$infos = array();
		$infos['USER'] = $paypal_api_user;
		$infos['PWD'] = $paypal_api_pwd;
		$infos['SIGNATURE'] = $paypal_api_signature;

		if($paypal_sandbox == 1){$sandstatus = true;}else{$sandstatus = false;}
		
		$paypal = new Paypal($infos,$sandstatus);
		$item_arr_rec = array('PROFILEID' => $params['profile_id'],'Action' => 'Cancel','Note'=>'User Cancelled.'); 

		$response_recurring = $paypal -> request($method,$item_arr_rec);
		
		/*Create a payment record for this process */
		$this->PF_CreatePaymentRecord(
			array(
			'user_id'	=>	$params['user_id'],
			'item_post_id'	=>	$params['item_post_id'],
			'order_post_id'	=> $params['order_post_id'],
			'response'	=>	$response_recurring,
			'processname'	=>	'ManageRecurringPaymentsProfileStatus',
			'status'	=>	$response_recurring['ACK']
			)

		);
	}
	/*
	* This function will cancel recurring payment from Paypal and set item status as direct payment.
	*/
	public function PF_Cancel_recurring_payment_member($params = array()){
		$defaults = array( 
	        'user_id' => '',
	        'profile_id' => '',
	        'item_post_id' => '',
	        'order_post_id' => ''
	    );

		$params = array_merge($defaults, $params);

		$method = 'ManageRecurringPaymentsProfileStatus';
		
		$paypal_price_unit = $this->PFSAIssetControl('setup20_paypalsettings_paypal_price_unit','','USD');
		$paypal_sandbox = $this->PFSAIssetControl('setup20_paypalsettings_paypal_sandbox','','0');
		$paypal_api_user = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_user','','');
		$paypal_api_pwd = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_pwd','','');
		$paypal_api_signature = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_signature','','2');

		$infos = array();
		$infos['USER'] = $paypal_api_user;
		$infos['PWD'] = $paypal_api_pwd;
		$infos['SIGNATURE'] = $paypal_api_signature;

		if($paypal_sandbox == 1){$sandstatus = true;}else{$sandstatus = false;}
		
		$paypal = new Paypal($infos,$sandstatus);
		$item_arr_rec = array('PROFILEID' => $params['profile_id'],'Action' => 'Cancel','Note'=>'User Cancelled.'); 

		$response_recurring = $paypal->request($method,$item_arr_rec);
		
		/*Create a payment record for this process */
		$this->PF_CreatePaymentRecord(
			array(
			'user_id'	=>	$params['user_id'],
			'order_post_id'	=> $params['order_post_id'],
			'response'	=>	$response_recurring,
			'processname'	=>	'ManageRecurringPaymentsProfileStatus',
			'status'	=>	$response_recurring['ACK'],
			'membership' => 1
			)

		);
	}

	/*
	* This function is manually expire the order of membership package.
	*/
	public function PFExpireItemManualMember($params){
		/*
		* Expire Order Record.	
		*/
		$defaults = array( 
	        'order_id' => '',
	        'post_author' => '',
			'payment_type' => 'direct',
			'payment_err' => ''
	    );

	    $params = array_merge($defaults, $params);

	    global $wpdb;

	    switch ($params['payment_type']) {
	    	case 'direct':
	    		$expire_message_var = esc_html__('Schedule System','pointfindercoreelements');
	    		break;

	    	case 'web_accept':
	    		$expire_message_var = sprintf(esc_html__('IPN System (%s)','pointfindercoreelements'),$params['payment_err']);
	    		break;

	    	case 'pags':
	    		$expire_message_var = sprintf(esc_html__('PagSeguro: IPN System (%s)','pointfindercoreelements'),$params['payment_err']);
	    		break;
	    		
	    	
	    	default:
	    		$expire_message_var = esc_html__('IPN System','pointfindercoreelements');
	    		break;
	    }

		$expire_message = sprintf(esc_html__('Plan & Order Status changed to Pending Payment by %s : (Item Expired)','pointfindercoreelements'), $expire_message_var);
		
		
		$wpdb->update($wpdb->posts,array('post_status'=>'pendingpayment'),array('ID'=>$params['order_id']));

		/*
		* Start : Find this user's all items and record before expire
		*/
			$setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
			$user_posts = $wpdb->get_results($wpdb->prepare(
				"SELECT ID, post_status FROM $wpdb->posts WHERE post_author = %d and post_status IN('publish','rejected','pendingapproval','pendingpayment','completed','pfcancelled','pfsuspended') and post_type = %s",
				$params['post_author'],
				$setup3_pointposttype_pt1
			),'ARRAY_A');

			$old_history = get_user_meta($params['post_author'],'membership_user_history',true );
			
			if ($old_history == false) {
				$json_array = '';
			} else {
				$json_array = json_decode($old_history,true);
				$user_posts = array_merge($user_posts,$json_array);
			}
			
	    	$json_array = json_encode($user_posts);

			update_user_meta( $params['post_author'], 'membership_user_history', $json_array);
		/*
		* End : Find this user's all items and record before expire
		*/

		/*
		* Start : Expire user's posts
		*/
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE $wpdb->posts SET post_status = 'pendingpayment' WHERE post_author = %d and post_status IN('publish','rejected','pendingapproval','pendingpayment','completed','pfcancelled','pfsuspended') and post_type = %s",
					$params['post_author'],
					$setup3_pointposttype_pt1
				)
			);
		/*
		* End : Expire user's posts
		*/


		$this->PFCreateProcessRecord(
			array( 
		        'user_id' => $params['post_author'],
		        'item_post_id' => $params['order_id'],
				'processname' => $expire_message,
				'membership' => 1
		    )
		);
	}

	public function PFCreateProcessRecord($params = array()){
		$defaults = array( 
	        'user_id' => '',
	        'item_post_id' => '',
			'processname' => '',
			'datetime' => date("Y-m-d H:i:s"),
			'membership' => 0
	    );

		$params = array_merge($defaults, $params);
		if ($params['membership'] == 0) {
			$order_post_id = $this->PFU_GetOrderID($params['item_post_id'],1);
		} else {
			$order_post_id = $params['item_post_id'];
		}
		
		$json_array = get_post_meta($order_post_id, 'pointfinder_order_processrecs',true);	

	    if (!empty($json_array)) { 
	    	$json_array = json_decode($json_array,true);
	    	$json_count = count($json_array);
	    
	    	$json_array[$json_count] = $params;
	    	$json_array = json_encode($json_array);
			update_post_meta($order_post_id, 'pointfinder_order_processrecs', $json_array);	
		}else{
			$json_array = array(array());
			$json_array[0] = $params;
	    	$json_array = json_encode($json_array);
			add_post_meta ($order_post_id, 'pointfinder_order_processrecs', $json_array);
		};   
	}

	public function PF_CreatePaymentRecord($params = array()){

		$defaults = array( 
	        'user_id' => '',
	        'item_post_id' => '',
	        'order_post_id' => '',
	        'orderdetails_post_id' => '',
	        'response' => array(),
			'token' => '',
			'payerid' => '',
			'processname' => '',
			'status' => '',
			'datetime' => date("Y-m-d H:i:s"),
			'membership' => 0
	    );
		if(isset($params['response'])){
	    	if (is_numeric($params['response'])) {
				if(count($params['response'])>0){
			  		$response = $params['response'];
				}else{
					$response = '';
				}
			}else{
				$response = $params['response'];
			}
		}else{
			$response = '';
		}

		$params = array_merge($defaults, $params);


		global $wpdb;

		if(empty($params['order_post_id'])){
		    $order_post_id = $wpdb->get_var( $wpdb->prepare( 
				"SELECT post_id FROM $wpdb->postmeta WHERE meta_value = %s and meta_key = %s", 
				$params['token'],
				'pointfinder_order_token'
			) );
		    $params['order_post_id'] = $order_post_id;
		}

		$json_array = get_post_meta( $params['order_post_id'], 'pointfinder_json_array', true);
	    
	    if (!empty($json_array)) { 
	    		    	
	    	if(!empty($json_array)){
	    		$json_array = json_decode($json_array,true);
	    	}else{
	    		$json_array = array();
	    	}

			switch ($params['processname']) {
				case 'BankTransferCancel':

					$json_array[] = array(
						'processname' => $params['processname'],
						'datetime'	=> $params['datetime']
						);

					break;
				case 'BankTransfer':

					$json_array[] = array(
						'processname' => $params['processname'],
						'datetime'	=> $params['datetime']
						);

					break;
				case 'SetExpressCheckout':
					
					$json_array[] = array(
						'processname' => $params['processname'],
						'datetime'	=> $params['datetime'],
						'token'	=> $params['token'],
						'status'	=> $params['status']
						);

					break;

				case 'SetExpressCheckoutStripe':
					
					$json_array[] = array(
						'processname' => $params['processname'],
						'datetime'	=> $params['datetime'],
						'status'	=> $params['status']
						);

					break;

				case 'GetExpressCheckoutDetails':
					
					$output_array = array();
					$output_array['processname'] = $params['processname'];
					$output_array['datetime'] = $params['datetime'];
					$output_array['token'] = $params['token'];
					$output_array['status'] = $params['status'];
					
					if(count($response) > 0){
							
							$output_array_response = array(
								'EMAIL'	=>	$response['EMAIL'],	
								'PAYERID'	=>	$response['PAYERID'],
								'PAYERSTATUS'	=>	$response['PAYERSTATUS'],	
								'CHECKOUTSTATUS'  =>	$response['CHECKOUTSTATUS'],	
								'FIRSTNAME'	=>	$response['FIRSTNAME'],	
								'LASTNAME'	=>	$response['LASTNAME'],
								'COUNTRYCODE'	=>	$response['COUNTRYCODE'],
								'SHIPTONAME'	=>	$response['SHIPTONAME'],
								'SHIPTOSTREET'	=>	$response['SHIPTOSTREET'],
								'SHIPTOCITY'	=>	$response['SHIPTOCITY'],
								'SHIPTOSTATE'	=>	$response['SHIPTOSTATE'],
								'SHIPTOZIP'	=>	$response['SHIPTOZIP'],
								'SHIPTOCOUNTRYNAME'	=>	$response['SHIPTOCOUNTRYNAME'],
								'ADDRESSSTATUS'	=>	$response['ADDRESSSTATUS'],
								'CURRENCYCODE'	=>	$response['CURRENCYCODE'],
								'PackagePrice'	=>	$response['PAYMENTREQUEST_0_AMT']
							);

							if (isset($response['L_PAYMENTREQUEST_0_NAME0']) && isset($response['L_PAYMENTREQUEST_0_DESC0'])) {
								$output_array_response['PackageName'] = $response['L_PAYMENTREQUEST_0_NAME0'].'/'.$response['L_PAYMENTREQUEST_0_DESC0'];
							}

						$json_array[] = array_merge($output_array,$output_array_response);
					}else{
						$json_array[] = $output_array;
					}

					break;
				
				case 'CreateRecurringPaymentsProfile':


					$output_array = array();
					$output_array['processname'] = $params['processname'];
					$output_array['datetime'] = $params['datetime'];
					$output_array['token'] = $params['token'];
					$output_array['status'] = $params['status'];

					if(count($response) > 0){

						if(isset($response['PROFILEID'])){$output_array['PROFILEID'] = $response['PROFILEID'];}
						if(isset($response['PROFILESTATUS'])){$output_array['PROFILESTATUS'] = $response['PROFILESTATUS'];}
						if(isset($response['TIMESTAMP'])){$output_array['TIMESTAMP'] = $response['TIMESTAMP'];}

					}

					$json_array[] = $output_array;

					break;
				case 'ManageRecurringPaymentsProfileStatus':
					$output_array = array();
					$output_array['processname'] = $params['processname'];
					$output_array['datetime'] = $params['datetime'];
					$output_array['status'] = $params['status'];

					if(count($response) > 0){

						if(isset($response['PROFILEID'])){$output_array['PROFILEID'] = $response['PROFILEID'];}
						if(isset($response['TIMESTAMP'])){$output_array['TIMESTAMP'] = $response['TIMESTAMP'];}

					}

					$json_array[] = $output_array;

					break;
				case 'DoExpressCheckoutPayment':

					$output_array = array();
					$output_array['processname'] = $params['processname'];
					$output_array['datetime'] = $params['datetime'];
					$output_array['token'] = $params['token'];
					$output_array['status'] = $params['status'];

					if(count($response) > 0){
						

						if(isset($response['PAYMENTINFO_0_TRANSACTIONID'])){$output_array['TRANSACTIONID'] = $response['PAYMENTINFO_0_TRANSACTIONID'];}
						if(isset($response['PAYMENTINFO_0_TRANSACTIONTYPE'])){$output_array['TRANSACTIONTYPE'] = $response['PAYMENTINFO_0_TRANSACTIONTYPE'];}
						if(isset($response['PAYMENTINFO_0_ORDERTIME'])){$output_array['TIMESTAMP'] = $response['PAYMENTINFO_0_ORDERTIME'];}
						if(isset($response['PAYMENTINFO_0_PAYMENTSTATUS'])){$output_array['PAYMENTSTATUS'] = $response['PAYMENTINFO_0_PAYMENTSTATUS'];}
						if(isset($response['L_SHORTMESSAGE0'])){$output_array['SHORTMESSAGE'] = $response['L_SHORTMESSAGE0'];}
						if(isset($response['L_LONGMESSAGE0'])){$output_array['LONGMESSAGE'] = $response['L_LONGMESSAGE0'];}
						if(isset($response['L_ERRORCODE0'])){$output_array['ERRORCODE'] = $response['L_ERRORCODE0'];}

						
					}

					$json_array[] = $output_array;

					break;

				case 'DoExpressCheckoutPaymentStripe':

					$output_array = array();
					$output_array['processname'] = $params['processname'];
					$output_array['datetime'] = $params['datetime'];
					$output_array['status'] = $params['status'];

					$json_array[] = $output_array;

					break;

				case 'DoExpressCheckoutPaymentPags':
				case 'DoExpressCheckoutPaymentIyzico':
				case 'DoExpressCheckoutPaymentPayu':
				case 'DoExpressCheckoutPaymentiDeal':
				case 'DoExpressCheckoutPaymentRobo':
				
					$output_array = array();
					$output_array['processname'] = $params['processname'];
					$output_array['datetime'] = $params['datetime'];
					$output_array['status'] = $params['status'];
					$output_array['token'] = $params['token'];

					$json_array[] = $output_array;

					break;

				case 'CancelPayment':
					
					$output_array = array();
					$output_array['processname'] = $params['processname'];
					$output_array['datetime'] = $params['datetime'];
					$output_array['token'] = $params['token'];
					
					$wpdb->UPDATE($wpdb->posts,array('post_status' => 'pfcancelled'),array('ID' => $params['order_post_id']));

					$json_array[] = $output_array;

					break;

				case 'GetRecurringPaymentsProfileDetails':

					$output_array = array();
					$output_array['processname'] = $params['processname'];
					$output_array['datetime'] = $params['datetime'];
					$output_array['token'] = $params['token'];
					$output_array['status'] = $params['status'];

					if(count($response) > 0){
						

						if(isset($response['STATUS'])){$output_array['STATUS'] = $response['STATUS'];}
						if(isset($response['NEXTBILLINGDATE'])){$output_array['NEXTBILLINGDATE'] = $response['NEXTBILLINGDATE'];}
						if(isset($response['NUMCYCLESCOMPLETED'])){$output_array['NUMCYCLESCOMPLETED'] = $response['NUMCYCLESCOMPLETED'];}
						if(isset($response['LASTPAYMENTDATE'])){$output_array['LASTPAYMENTDATE'] = $response['LASTPAYMENTDATE'];}
						if(isset($response['LASTPAYMENTAMT'])){$output_array['LASTPAYMENTAMT'] = $response['LASTPAYMENTAMT'];}
						if(isset($response['DESC'])){$output_array['DESC'] = $response['DESC'];}
						if(isset($response['PROFILEID'])){$output_array['PROFILEID'] = $response['PROFILEID'];}

						
					}

					$json_array[] = $output_array;

					break;

				case 'RecurringPayment':

					$output_array = array();
					$output_array['processname'] = $params['processname'];
					$output_array['datetime'] = $params['datetime'];


					if(count($response) > 0){
						
						$output_array['response'] = $params['response'];

					}
					$json_array[] = $output_array;
					break;
			}


			$json_array = json_encode($json_array);
			update_post_meta($params['order_post_id'], 'pointfinder_order_paymentrecs', $json_array);	

		}else{

			$json_array = array(array());
			$json_array[0] = array(
				'processname' => $params['processname'],
				'datetime'	=> $params['datetime'],
				'token'	=> $params['token'],
				'status'	=> $params['status']
				);
	    	$json_array = json_encode($json_array);
			add_post_meta ($params['order_post_id'], 'pointfinder_order_paymentrecs', $json_array);

		};   
	}

	public function PF_CreateInvoice($params = array()){

		$defaults = array( 
	        'user_id' => '',
	        'item_id' => 0,
	        'order_id' => '',
	        'description' => '',// Basic Package
	        'processname' => '', // Recurring or Direct or Bank etc..,
	        'amount' => 0,// 20$
			'datetime' => strtotime("now"),
			'packageid' => 0,
			'status' => 'publish'// pendingpayment
	    );

	    /*
			Pay Per Post:
				1-) Order Title (ID)
				2-) Item ID (Title)
				3-) Process Type (Recurring or Direct or Bank etc..)
				4-) User ID (We will get username etc..)
				5-) Date
				6-) Amount
				7-) Status
					a-) Pending for Bank Payment. If completed change to completed.
					b-) Completed for other payment systems.

			Membership System
				1-) Order Title (ID)
				2-) Package Name (packageid)
				3-) Process Type (Recurring or Direct or Bank etc..)
				4-) User ID (We will get username etc..)
				5-) Date
				6-) Amount
				7-) Status
					a-) Pending for Bank Payment. If completed change to completed.
					b-) Completed for other payment systems.

	    */
		
		$params = array_merge($defaults, $params);

		$arg_invoice = array(
		  'post_type'    => 'pointfinderinvoices',
		  'post_title'	=> $params['description'],
		  'post_status'   => $params['status'],
		  'post_author'   => $params['user_id'],
		);

		$invoice_post_id = wp_insert_post($arg_invoice);

		/*Invoice Meta*/
		update_post_meta($invoice_post_id, 'pointfinder_invoice_date', $params['datetime']);
		update_post_meta($invoice_post_id, 'pointfinder_invoice_orderid', $params['order_id']);
		update_post_meta($invoice_post_id, 'pointfinder_invoice_amount', $params['amount']);
		update_post_meta($invoice_post_id, 'pointfinder_invoice_invoicetype', $params['processname']);

		if (!empty($params['item_id'])) {
			update_post_meta($invoice_post_id, 'pointfinder_invoice_itemid', $params['item_id']);
		}
		if (!empty($params['packageid'])) {
			update_post_meta($invoice_post_id, 'pointfinder_invoice_packageid', $params['packageid']);
		}

		return $invoice_post_id;
	}

	public function PFU_GetOrderID($value,$type = 0) {
		global $wpdb;
		
		$meta_key = 'pointfinder_order_itemid';

		$result = $wpdb->get_var( $wpdb->prepare( 
			"
				SELECT post_id
				FROM $wpdb->postmeta 
				WHERE meta_key = %s and meta_value = %d
			", 
			$meta_key,
			$value
		) );

		if($type == 0){
			return get_the_title($result);
		}else{
			return $result;
		}
	}

	public function pointfinder_order_fallback_operations($order_post_id,$pointfinder_order_price){
		$pointfinder_order_fremoveback = get_post_meta( $order_post_id, "pointfinder_order_fremoveback", true );
		if (!empty($pointfinder_order_fremoveback)) {
			update_post_meta($order_post_id, 'pointfinder_order_price', 0);
			delete_post_meta($order_post_id, "pointfinder_order_fremoveback");
		}

		$setup31_userpayments_featuredoffer = $this->PFSAIssetControl('setup31_userpayments_featuredoffer','',0);
		if ($setup31_userpayments_featuredoffer == 1) {
			$pointfinder_order_fremoveback2 = get_post_meta( $order_post_id, "pointfinder_order_fremoveback2", true );
			if (!empty($pointfinder_order_fremoveback2)) {
			  $setup31_userpayments_pricefeatured = $this->PFSAIssetControl('setup31_userpayments_pricefeatured','','5');
			  $pointfinder_order_price_output = $pointfinder_order_price - $setup31_userpayments_pricefeatured;
			  update_post_meta($order_post_id, 'pointfinder_order_price', $pointfinder_order_price_output);
			  delete_post_meta($order_post_id, "pointfinder_order_fremoveback2");
			}
		}
	}

	public function pointfinder_get_package_price_ppp($packid){
		if ($packid == 1 || $packid == 2) {
		$pack_price = $this->PFSAIssetControl('setup31_userpayments_priceperitem','','0');
		}else{
		$pack_price = get_post_meta( $packid, 'webbupointfinder_lp_price', true );
		}

		if (!empty($pack_price)) {
			return $pack_price;
		}else{ 
			return false;
		};
	}

	public function pointfinder_additional_orders($params=array()){

		$defaults = array( 
		'changedvals' => array(),
		'order_id' => '',
		'post_id' => ''
		);

		$params = array_merge($defaults, $params);
		$current_category = '';

		if (is_array($params['changedvals'])) {
			foreach ($params['changedvals'] as $key => $value) {
				switch ($key) {
					case 'featured':
						if ($value == 1) {
							update_post_meta($params['post_id'], 'webbupointfinder_item_featuredmarker', 1);
							add_post_meta($params['order_id'], 'pointfinder_order_featured', 1);
							$stp31_daysfeatured = $this->PFSAIssetControl('stp31_daysfeatured','','3');
							$exp_date_featured = date("Y-m-d H:i:s", strtotime("+".$stp31_daysfeatured." days"));
							update_post_meta( $params['order_id'], 'pointfinder_order_expiredate_featured', $exp_date_featured);
							update_post_meta( $params['order_id'], 'pointfinder_order_frecurring', 0);
						}
						break;
					
					case 'category':
						if ($value == 1) {

							/* Re Create order price */
							$pointfinder_order_listingpid = get_post_meta($params['order_id'], 'pointfinder_sub_order_listingpid', true);

							$pointfinder_sub_order_terms = get_post_meta( $params['order_id'], "pointfinder_sub_order_terms", true );
							$pointfinder_sub_order_termsmc = get_post_meta( $params['order_id'], "pointfinder_sub_order_termsmc", true );
							$pointfinder_sub_order_termsms = get_post_meta( $params['order_id'], "pointfinder_sub_order_termsms", true );


							$pack_results = $this->pointfinder_calculate_listingtypeprice($pointfinder_sub_order_termsms,0,$pointfinder_order_listingpid);
						    
						    update_post_meta($params['order_id'], 'pointfinder_order_price', $pack_results['total_pr']);

							wp_set_post_terms( $params['post_id'], $pointfinder_sub_order_terms, 'pointfinderltypes');

							$pointfinder_order_category_price = get_post_meta($params['order_id'], 'pointfinder_sub_order_category_price', true);

							update_post_meta($params['order_id'], 'pointfinder_order_category_price', $pointfinder_order_category_price);
						}
						break;

					case 'plan':
						if ($value == 1) {
							/* Re Create order price */
							$pointfinder_order_listingpid = get_post_meta($params['order_id'], 'pointfinder_sub_order_listingpid', true);
							
							$pointfinder_sub_order_terms = get_post_meta( $params['order_id'], "pointfinder_sub_order_terms", true );
							$pointfinder_sub_order_termsmc = get_post_meta( $params['order_id'], "pointfinder_sub_order_termsmc", true );
							$pointfinder_sub_order_termsms = get_post_meta( $params['order_id'], "pointfinder_sub_order_termsms", true );
							if (empty($pointfinder_sub_order_termsms)) {
								$item_defaultvalue = wp_get_post_terms($params['post_id'], 'pointfinderltypes', array("fields" => "ids"));
								if (isset($item_defaultvalue[0])) {
									$current_category = $this->pf_get_term_top_most_parent($item_defaultvalue[0],'pointfinderltypes');
									$pointfinder_sub_order_termsms = $current_category['parent'];
								}
							}

							$pack_results = $this->pointfinder_calculate_listingtypeprice($pointfinder_sub_order_termsms,0,$pointfinder_order_listingpid);
						    update_post_meta($params['order_id'], 'pointfinder_order_price', $pack_results['total_pr']);
							

							$pointfinder_order_detailedprice = get_post_meta($params['order_id'], 'pointfinder_sub_order_detailedprice', true);
							$pointfinder_order_listingtime = get_post_meta($params['order_id'], 'pointfinder_sub_order_listingtime', true);
							$pointfinder_order_listingpname = get_post_meta($params['order_id'], 'pointfinder_sub_order_listingpname', true);	
							

							update_post_meta($params['order_id'], 'pointfinder_order_detailedprice', $pointfinder_order_detailedprice);
							update_post_meta($params['order_id'], 'pointfinder_order_listingtime', $pointfinder_order_listingtime);
							update_post_meta($params['order_id'], 'pointfinder_order_listingpname', $pointfinder_order_listingpname);	
							update_post_meta($params['order_id'], 'pointfinder_order_listingpid', $pointfinder_order_listingpid);
							update_post_meta($params['order_id'], 'pointfinder_order_bankcheck', '0');

							$exp_date = date("Y-m-d H:i:s", strtotime("+".$pointfinder_order_listingtime." days"));

							$old_expire_date = get_post_meta( $params['order_id'], 'pointfinder_order_expiredate', true);

		        			$exp_date = date("Y-m-d H:i:s",strtotime($old_expire_date .'+'.$pointfinder_order_listingtime.' day'));
							$app_date = date("Y-m-d H:i:s");
							if (class_exists('Pointfinderspecialreview')) {
								$pointfinderspecialreview_option = intval(get_option('pfsrextend'));
								if ($pointfinderspecialreview_option == 1) {
									$exp_date = date("Y-m-d H:i:s", strtotime("+".$pointfinder_order_listingtime." days"));
									$app_date = date("Y-m-d H:i:s");
								}
							}
							update_post_meta( $params['order_id'], 'pointfinder_order_expiredate', $exp_date);
							update_post_meta( $params['order_id'], 'pointfinder_order_datetime_approval', $app_date);

						}

						break;
				}
			}

			/* Remove Meta Data */
			$this->pointfinder_remove_sub_order_metadata($params['order_id']);
		}
	}

	public function pointfinder_remove_sub_order_metadata($order_id_current){
		delete_post_meta($order_id_current, 'pointfinder_sub_order_change');
		delete_post_meta($order_id_current, 'pointfinder_sub_order_changedvals');
		delete_post_meta($order_id_current, 'pointfinder_sub_order_price');
		delete_post_meta($order_id_current, 'pointfinder_sub_order_detailedprice');
		delete_post_meta($order_id_current, 'pointfinder_sub_order_listingtime');
		delete_post_meta($order_id_current, 'pointfinder_sub_order_listingpname');	
		delete_post_meta($order_id_current, 'pointfinder_sub_order_listingpid');
		delete_post_meta($order_id_current, 'pointfinder_sub_order_category_price');
		delete_post_meta($order_id_current, 'pointfinder_sub_order_featured');
		delete_post_meta($order_id_current, 'pointfinder_sub_order_token');
		delete_post_meta($order_id_current, 'pointfinder_sub_order_terms');
		delete_post_meta($order_id_current, 'pointfinder_sub_order_termsmc');
		delete_post_meta($order_id_current, 'pointfinder_sub_order_termsms');
	}

	public function pointfinder_calculate_listingtypeprice($c,$f,$p){

	  $setup4_ppp_catprice = $this->PFSAIssetControl('setup4_ppp_catprice','','0');
	  $price_short = $this->PFSAIssetControl('setup20_paypalsettings_paypal_price_short','','$');
	  $price_pref = $this->PFSAIssetControl('setup20_paypalsettings_paypal_price_pref','',1);
	  $stp31_up2_pn = $this->PFSAIssetControl('stp31_up2_pn','','');

	  $setup20_decimals_new = $this->PFSAIssetControl('setup20_decimals_new','',2);
	  $setup20_decimalpoint = $this->PFSAIssetControl('setup20_paypalsettings_decimalpoint','','.');
	  $setup20_thousands = $this->PFSAIssetControl('setup20_paypalsettings_thousands','',',');

	  $cat_price = $pack_title = $pack_price = $featured_price = '';
	  $cat_price = $pack_price = $featured_price = 0;
	  /* Get Category Price */
	  if (!empty($c)) {
	    if ($setup4_ppp_catprice == 1) {
	      $cat_extra_opts = get_option('pointfinderltypes_covars');
	      if (!empty($cat_extra_opts)) {
	      	$cat_price = (isset($cat_extra_opts[$c]['pf_categoryprice']))?$cat_extra_opts[$c]['pf_categoryprice']:0;
	      }
	    }
	  }

	  /* Get Pack Price */
	  if ($p == 1) {
	    $pack_price = $this->PFSAIssetControl('setup31_userpayments_priceperitem','','0');
	    $pack_title = $stp31_up2_pn;
	  }else{
	    $pack_price = get_post_meta( $p, 'webbupointfinder_lp_price', true );
	    $pack_title = get_the_title($p);
	  }

	  /* Get Featured Price */
	  if ($f == 1 && $this->PFSAIssetControl('setup31_userpayments_featuredoffer','','1') == 1) {
	    $featured_price = $this->PFSAIssetControl('setup31_userpayments_pricefeatured','',0);
	  }else{
	    $featured_price = 0;
	  }

	  $total_pr_output_vat = $total_pr_output_bfvat = '';
	  
	  /* Total Price Output Value*/
	  $total_pr = $cat_price + $pack_price + $featured_price;

		$setup4_pricevat = $this->PFSAIssetControl('setup4_pricevat','','0');
		if ($setup4_pricevat == 1) {
			$setup4_pv_pr = $this->PFSAIssetControl('setup4_pv_pr','','0');

			$setup4_pv_pr_float = '0.'.$setup4_pv_pr;
			$setup4_pv_pr_full = 1.00 + (float)$setup4_pv_pr_float;
			$total_pr_output_vat = round((($total_pr/$setup4_pv_pr_full)*$setup4_pv_pr)/100,$setup20_decimals_new);

			$total_pr_output_bfvat = round(($total_pr - $total_pr_output_vat),$setup20_decimals_new);

			$total_pr_output_bfvat = number_format($total_pr_output_bfvat,$setup20_decimals_new,$setup20_decimalpoint,$setup20_thousands);
			$total_pr_output_vat = number_format($total_pr_output_vat,$setup20_decimals_new,$setup20_decimalpoint,$setup20_thousands);

		}


		$total_pr_output = number_format($total_pr,$setup20_decimals_new,$setup20_decimalpoint,$setup20_thousands);

	  
	  	$featured_pr_output = number_format($featured_price,$setup20_decimals_new,$setup20_decimalpoint,$setup20_thousands);
	    $pack_pr_output = number_format($pack_price,$setup20_decimals_new,$setup20_decimalpoint,$setup20_thousands);
	    $cat_pr_output = number_format($cat_price,$setup20_decimals_new,$setup20_decimalpoint,$setup20_thousands);


	  if ($price_pref == 1) {
	    $total_pr_output = $price_short.$total_pr_output;
	    if ($setup4_pricevat == 1) {
	    	$total_pr_output_vat = '~ '.$price_short.$total_pr_output_vat;
	    	$total_pr_output_bfvat = '~ '.$price_short.$total_pr_output_bfvat;
	    }

	    $featured_pr_output = $price_short.$featured_pr_output;
	    $pack_pr_output = $price_short.$pack_pr_output;
	    $cat_pr_output = $price_short.$cat_pr_output;
	  }else{
	    $total_pr_output = $total_pr_output.$price_short;
	    if ($setup4_pricevat == 1) {
	    	$total_pr_output_vat = '~ '.$total_pr_output_vat.$price_short;
	    	$total_pr_output_bfvat = '~ '.$total_pr_output_bfvat.$price_short;
	    }
	    $featured_pr_output = $featured_pr_output.$price_short;
	    $pack_pr_output = $pack_pr_output.$price_short;
	    $cat_pr_output = $cat_pr_output.$price_short;
	  }

	  return array(
	    'total_pr'=>$total_pr,
	    'total_pr_output'=>$total_pr_output,
	    'featured_pr_output'=>$featured_pr_output,
	    'pack_pr_output'=>$pack_pr_output,
	    'cat_pr_output'=>$cat_pr_output,
	    'cat_price'=>$cat_price,
	    'pack_price'=>$pack_price,
	    'featured_price'=>$featured_price,
	    'pack_title'=>$pack_title,
	    'total_pr_output_vat'=>$total_pr_output_vat,
	    'total_pr_output_bfvat'=>$total_pr_output_bfvat
	    );
	}

	public function pointfinder_check_priceformatted($value){
		if (strpos($value, '.') == false && strpos($value, ',') == false) {
			return false;
		}else{
			return true;
		}
	}

	public function pointfinder_reformat_pricevalue_for_frontend($value){
		if (empty($value)) {return $value;}

		$setup20_decimals_new = $this->PFSAIssetControl('setup20_decimals_new','',2);
		$setup20_decimalpoint = $this->PFSAIssetControl('setup20_paypalsettings_decimalpoint','','.');
		$setup20_thousands = $this->PFSAIssetControl('setup20_paypalsettings_thousands','',',');
		$price_short = $this->PFSAIssetControl('setup20_paypalsettings_paypal_price_short','','$');
	    $price_pref = $this->PFSAIssetControl('setup20_paypalsettings_paypal_price_pref','',1);

	    if ($this->pointfinder_check_priceformatted($value) === false) {
	    	if (is_numeric($value)) {
	    		$value_formatted = number_format(intval($value),$setup20_decimals_new,$setup20_decimalpoint,$setup20_thousands);
	    	}else{
	    		$value_formatted = $value;
	    	}
	    	
	    }else{
	    	$value_formatted = $value;
	    }

	    if (strpos($value, $price_short) === false) {
	    	if ($price_pref == 1) {

				return $price_short.$value_formatted;

			}else{

				return $value_formatted.$price_short;

			}
	    }else{
	    	return $value_formatted;
	    }
	}

	public function pointfinder_membership_package_details_get($post_id){
										    	
		$packageinfo = array();
		$packageinfo['webbupointfinder_mp_packageid'] = $post_id;
		$packageinfo['webbupointfinder_mp_title'] = get_the_title($post_id);

	    $webbupointfinder_mp_showhide = get_post_meta($post_id, 'webbupointfinder_mp_showhide', true );
	    if ($webbupointfinder_mp_showhide == false) {$packageinfo['webbupointfinder_mp_showhide'] = 2;}else{$packageinfo['webbupointfinder_mp_showhide'] = $webbupointfinder_mp_showhide;}

	    $webbupointfinder_mp_billing_time_unit = get_post_meta($post_id, 'webbupointfinder_mp_billing_time_unit', true );
	    $packageinfo['webbupointfinder_mp_billing_time_unit_text'] = $this->pointfinder_billing_timeunit_text($webbupointfinder_mp_billing_time_unit);
	    if($webbupointfinder_mp_billing_time_unit == false){$packageinfo['webbupointfinder_mp_billing_time_unit'] = 'daily';}else{$packageinfo['webbupointfinder_mp_billing_time_unit'] = $webbupointfinder_mp_billing_time_unit;}

	    $webbupointfinder_mp_billing_period = get_post_meta($post_id, 'webbupointfinder_mp_billing_period', true );
	    if ($webbupointfinder_mp_billing_period == false) {$packageinfo['webbupointfinder_mp_billing_period'] = 1;}else{$packageinfo['webbupointfinder_mp_billing_period'] = $webbupointfinder_mp_billing_period;}

	    $webbupointfinder_mp_trial = get_post_meta($post_id, 'webbupointfinder_mp_trial', true );
	    if ($webbupointfinder_mp_trial == false) {$packageinfo['webbupointfinder_mp_trial'] = 0;}else{$packageinfo['webbupointfinder_mp_trial'] = $webbupointfinder_mp_trial;}

	    $webbupointfinder_mp_trial_period = get_post_meta($post_id, 'webbupointfinder_mp_trial_period', true );
	    if ($webbupointfinder_mp_trial_period == false) {$packageinfo['webbupointfinder_mp_trial_period'] = 1;}else{$packageinfo['webbupointfinder_mp_trial_period'] = $webbupointfinder_mp_trial_period;}

	    $webbupointfinder_mp_itemnumber = get_post_meta($post_id, 'webbupointfinder_mp_itemnumber', true );
	    if ($webbupointfinder_mp_itemnumber == false) {$packageinfo['webbupointfinder_mp_itemnumber'] = -1;}else{$packageinfo['webbupointfinder_mp_itemnumber'] = $webbupointfinder_mp_itemnumber;}

	    $webbupointfinder_mp_fitemnumber = get_post_meta($post_id, 'webbupointfinder_mp_fitemnumber', true );
	    if ($webbupointfinder_mp_fitemnumber == false) {$packageinfo['webbupointfinder_mp_fitemnumber'] = 0;}else{$packageinfo['webbupointfinder_mp_fitemnumber'] = $webbupointfinder_mp_fitemnumber;}

	    $webbupointfinder_mp_images = get_post_meta($post_id, 'webbupointfinder_mp_images', true );
	    if ($webbupointfinder_mp_images == false) {$packageinfo['webbupointfinder_mp_images'] = 10;}else{$packageinfo['webbupointfinder_mp_images'] = $webbupointfinder_mp_images;}

	    $webbupointfinder_mp_price = get_post_meta($post_id, 'webbupointfinder_mp_price', true );
	    if (empty($webbupointfinder_mp_price)) {$packageinfo['webbupointfinder_mp_price'] = 0;}else{$packageinfo['webbupointfinder_mp_price'] = $webbupointfinder_mp_price;}

	    $webbupointfinder_mp_description = get_post_meta($post_id, 'webbupointfinder_mp_description', true );
		if (empty($webbupointfinder_mp_description)) {$packageinfo['webbupointfinder_mp_description'] = '';}else{$packageinfo['webbupointfinder_mp_description'] = $webbupointfinder_mp_description;}

		$setup20_paypalsettings_paypal_price_pref = $this->PFSAIssetControl('setup20_paypalsettings_paypal_price_pref','',1);
		$setup20_paypalsettings_paypal_price_short = $this->PFSAIssetControl('setup20_paypalsettings_paypal_price_short','','$');

		$packageinfo['packageinfo_priceoutput'] = $packageinfo['webbupointfinder_mp_price'];
		if ($packageinfo['webbupointfinder_mp_price'] != 0) {

			$setup20_decimals_new = $this->PFSAIssetControl('setup20_decimals_new','',2);
			$setup20_decimalpoint = $this->PFSAIssetControl('setup20_paypalsettings_decimalpoint','','.');
			$setup20_thousands = $this->PFSAIssetControl('setup20_paypalsettings_thousands','',',');

			$setup4_pricevat = $this->PFSAIssetControl('setup4_pricevat','','0');
			if ($setup4_pricevat == 1) {
				$setup4_pv_pr = $this->PFSAIssetControl('setup4_pv_pr','','0');

				$setup4_pv_pr_float = '0.'.$setup4_pv_pr;
				$setup4_pv_pr_full = 1.00 + (float)$setup4_pv_pr_float;
				$packageinfo['packageinfo_priceoutput_vat'] = round((($packageinfo['webbupointfinder_mp_price']/$setup4_pv_pr_full)*$setup4_pv_pr)/100,$setup20_decimals_new);
				$packageinfo['packageinfo_priceoutput_bfvat'] = round(($packageinfo['webbupointfinder_mp_price'] - $packageinfo['packageinfo_priceoutput_vat']),$setup20_decimals_new);
				$packageinfo['packageinfo_priceoutput_bfvat'] = $this->pointfinder_reformat_pricevalue_for_frontend($packageinfo['packageinfo_priceoutput_bfvat']);
				$packageinfo['packageinfo_priceoutput_vat'] = $this->pointfinder_reformat_pricevalue_for_frontend($packageinfo['packageinfo_priceoutput_vat']);
			}

			/*If package not free*/
			$packageinfo['packageinfo_priceoutput'] = number_format($packageinfo['packageinfo_priceoutput'], $setup20_decimals_new, $setup20_decimalpoint, $setup20_thousands);
			
			if ($setup20_paypalsettings_paypal_price_pref != 1) {
				$packageinfo['packageinfo_priceoutput_text'] = $packageinfo['packageinfo_priceoutput'].$setup20_paypalsettings_paypal_price_short;
			}else{
				$packageinfo['packageinfo_priceoutput_text'] = $setup20_paypalsettings_paypal_price_short.$packageinfo['packageinfo_priceoutput'];
			}

		}else{
			$packageinfo['packageinfo_priceoutput'] = 0;
			$packageinfo['packageinfo_priceoutput_text'] = esc_html__('Free','pointfindercoreelements');
		}

		/*Check unlimited item*/
		if ($packageinfo['webbupointfinder_mp_itemnumber'] == -1) {
			$packageinfo['packageinfo_itemnumber_output_text'] = esc_html__('Unlimited','pointfindercoreelements');
		}else{
			$packageinfo['packageinfo_itemnumber_output_text'] = $packageinfo['webbupointfinder_mp_itemnumber'];
		}

	    return $packageinfo;
	}

	public function pointfinder_membership_create_order($params = array()){

		$defaults = array(
			'order_payment' => 0,
			'user_id' => '',
			'packageinfo' => array(),
			'recurring' => 0,
			'autoexpire_create' => 0,
			'token' => '',
			'bankcheck' => 0,
			'trial' => 0
		);

		$params = array_merge($defaults, $params);
		$packageinfo = $params['packageinfo'];

		/** Orders: Post Info **/

		
		srand($this->pfmake_seed());


		$setup31_userpayments_orderprefix = $this->PFSAIssetControl('setup31_userpayments_orderprefix','','PF');
		
		$order_post_title = $setup31_userpayments_orderprefix.rand();

		if ($params['order_payment'] == 0) {
			$order_post_status = 'pendingpayment';
		}else{
			$order_post_status = 'completed';
		}

		$arg_order = array(
		  'post_type'    => 'pointfindermorders',
		  'post_title'	=> $order_post_title,
		  'post_status'   => $order_post_status,
		  'post_author'   => $params['user_id'],
		);

		$order_post_id = wp_insert_post($arg_order);


		/*Order Meta*/
		add_post_meta($order_post_id, 'pointfinder_order_packageid', $packageinfo['webbupointfinder_mp_packageid'], true );	
		add_post_meta($order_post_id, 'pointfinder_order_userid', $params['user_id'], true );	
		add_post_meta($order_post_id, 'pointfinder_order_recurring', $params['recurring'], true );	
		add_post_meta($order_post_id, 'pointfinder_order_token', $params['token'], true );
		add_post_meta($order_post_id, 'pointfinder_order_bankcheck', '0');

		/* Start: Add expire date if this item is ready to publish (free listing) */
		if($params['autoexpire_create'] == 1){
			
			if ($params['trial'] == 1) {
				update_post_meta($order_post_id, 'pointfinder_order_trialcheck', '1');	
				$exp_date = strtotime("+".$packageinfo['webbupointfinder_mp_trial_period']." ".$this->pointfinder_billing_timeunit_text_ex($packageinfo['webbupointfinder_mp_billing_time_unit'])."");
			}else{
				$exp_date = strtotime("+".$packageinfo['webbupointfinder_mp_billing_period']." ".$this->pointfinder_billing_timeunit_text_ex($packageinfo['webbupointfinder_mp_billing_time_unit'])."");
			}
			$app_date = strtotime("now");

			update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
			update_post_meta( $order_post_id, 'pointfinder_order_datetime_approval', $app_date);
			update_post_meta( $order_post_id, 'pointfinder_order_bankcheck', $params['bankcheck']);	

			/* - Creating record for process system. */
			$this->PFCreateProcessRecord(
				array( 
			        'user_id' => $params['user_id'],
			        'item_post_id' => $order_post_id,
					'processname' => esc_html__('Order status changed to Publish by Autosystem (Free/Trial Package)','pointfindercoreelements'),
					'membership' => 1
			    )
			);
		}
		/* End: Add expire date if this item is ready to publish (free listing) */



		/* - Creating record for process system. */
		$this->PFCreateProcessRecord(
			array( 
		        'user_id' => $params['user_id'],
		        'item_post_id' => $order_post_id,
				'processname' => esc_html__('A new membership package ordered by USER.','pointfindercoreelements'),
				'membership' => 1
		    )
		);	
			
		return $order_post_id;
	}

	public function pfmake_seed(){
		list($usec, $sec) = explode(' ', microtime());
		return (float) $sec + ((float) $usec * 10000000);
	}

	public function pointfinder_membership_count_ui($user_idx){
		
		$setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
		global $wpdb;
		
		/*Count User's Items*/
		$user_post_count = 0;
		$user_post_count = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT count(*) FROM $wpdb->posts where post_author = %d and post_type = %s and post_status IN('publish','rejected','pendingapproval','pendingpayment','completed','pfcancelled','pfsuspended')",
				$user_idx,
				$setup3_pointposttype_pt1
			)
		);

		/*Count User's Featured Items*/
		$users_post_featured = 0;
		$users_post_featured = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT count(*) FROM $wpdb->posts db1 INNER JOIN $wpdb->postmeta db2 ON db2.post_id = db1.ID where db1.post_author = %d and db1.post_type = %s and db1.post_status IN('publish','rejected','pendingapproval','pendingpayment','completed','pfcancelled','pfsuspended') and db2.meta_key = %s and db2.meta_value = %d",
				$user_idx,
				$setup3_pointposttype_pt1,
				'webbupointfinder_item_featuredmarker',
				1
			)
		);

		return array('item_count'=> $user_post_count, 'fitem_count'=>$users_post_featured);
	}

	public function pointfinder_billing_timeunit_text_ex($webbupointfinder_mp_billing_time_unit){
		switch ($webbupointfinder_mp_billing_time_unit) {
	    	case 'yearly':
	    		$webbupointfinder_mp_billing_time_unit_text = 'year';
	    		break;

	    	case 'monthly':
	    		$webbupointfinder_mp_billing_time_unit_text = 'month';
	    		break;
	    	
	    	default:
	    		$webbupointfinder_mp_billing_time_unit_text = 'days';
	    		break;
	    }
	    return $webbupointfinder_mp_billing_time_unit_text;
	}
	/*
	* pointfinder_reenable_expired_items()
	* This function put original post status after upgrade and renew
	*/
	public function pointfinder_reenable_expired_items($params = array()){
      $defaults = array( 
        'user_id' => '',
        'packageinfo' => '',
        'order_id' => '',
		'process' => 'u'
      );

      $params = array_merge($defaults, $params);

      $packageinfo = $params['packageinfo'];

      $membership_user_expiredate = get_post_meta( $params['order_id'], 'pointfinder_order_expiredate', true );

      if ($this->pf_membership_expire_check($membership_user_expiredate) == false) {
        $membership_user_expiredate_out = date("d-m-Y H:i:s",$membership_user_expiredate);
        if($params['process'] == 'r'){
        	$exp_date = strtotime($membership_user_expiredate_out . " +".$packageinfo['webbupointfinder_mp_billing_period']." ".$this->pointfinder_billing_timeunit_text_ex($packageinfo['webbupointfinder_mp_billing_time_unit'])."");
      	}else{
      		$exp_date = strtotime("+".$packageinfo['webbupointfinder_mp_billing_period']." ".$this->pointfinder_billing_timeunit_text_ex($packageinfo['webbupointfinder_mp_billing_time_unit'])."");
      	}
      }else{
        $exp_date = strtotime("+".$packageinfo['webbupointfinder_mp_billing_period']." ".$this->pointfinder_billing_timeunit_text_ex($packageinfo['webbupointfinder_mp_billing_time_unit'])."");                                                             
        /*Re enable items if user have expired items.*/
        $expired_items = get_user_meta( $params['user_id'], 'membership_user_history', true );
        
        if (!empty($expired_items)) {
          $expired_items_arr = json_decode($expired_items,true);
          if (is_array($expired_items_arr) && count($expired_items_arr) > 0) {
          	/* - Creating record for process system. */
            $this->PFCreateProcessRecord(
              array( 
                'user_id' => $params['user_id'],
                'item_post_id' => $params['order_id'],
                'processname' => esc_html__('Expired Items; Change post status process. (Put back to old status)','pointfindercoreelements'),
                'membership' => 1
                )
            );
            global $wpdb;
            foreach ($expired_items_arr as $exvalue) {
              $wpdb->update($wpdb->posts,array('post_status'=>$exvalue['post_status']),array('ID'=>$exvalue['ID']));
            }
            update_user_meta( $params['user_id'], 'membership_user_historybackup', $expired_items );
            delete_user_meta( $params['user_id'], 'membership_user_history');
          }
          
        }
      }

      return $exp_date;
    }
    /*
	* pf_membership_expire_check()
	* This function will check for expire date.
	*/
    public function pf_membership_expire_check($expire_date){
		if ( strtotime("now") >= $expire_date) {return true;/*Expired*/}else{return false;/*Not Expired*/}
	}

	public function pointfinder_billing_timeunit_text($webbupointfinder_mp_billing_time_unit){
		switch ($webbupointfinder_mp_billing_time_unit) {
	    	case 'yearly':
	    		$webbupointfinder_mp_billing_time_unit_text = esc_html__('Year(s)','pointfindercoreelements');
	    		break;

	    	case 'monthly':
	    		$webbupointfinder_mp_billing_time_unit_text = esc_html__('Month(s)','pointfindercoreelements');
	    		break;
	    	
	    	default:
	    		$webbupointfinder_mp_billing_time_unit_text = esc_html__('Day(s)','pointfindercoreelements');
	    		break;
	    }
	    return $webbupointfinder_mp_billing_time_unit_text;
	}

	public function PFU_DateformatS($value,$showtime = 0){
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
		$newdate = date($datetype,$value);
		return $newdate;*/

		if (empty($value)) {
			return esc_html__( "-", "pointfindercoreelements" );
		}
		if ($showtime != 1) {
			return date_i18n( get_option( 'date_format' ), $value );
		}else{
			return date_i18n( get_option('date_format') . ' ' . get_option('time_format'), $value);
		}
		
	}

	public function PFExpireItemManual($params){

		$defaults = array( 
	        'order_id' => '',
	        'post_id' => '',
	        'post_author' => '',
			'payment_type' => 'direct',
			'payment_err' => ''
	    );

	    $params = array_merge($defaults, $params);

	    switch ($params['payment_type']) {
	    	case 'direct':
	    		$expire_message_var = esc_html__('Schedule System','pointfindercoreelements');
	    		break;

	    	case 'web_accept':
	    		$expire_message_var = sprintf(esc_html__('IPN System (%s)','pointfindercoreelements'),$params['payment_err']);
	    		break;
	    	
	    	default:
	    		$expire_message_var = esc_html__('IPN System','pointfindercoreelements');
	    		break;
	    }

		$expire_message = sprintf(esc_html__('Item & Order Status changed to Pending Payment by %s : (Item Expired)','pointfindercoreelements'), $expire_message_var);
		
		global $wpdb;
		$wpdb->update($wpdb->posts,array('post_status'=>'pendingpayment'),array('ID'=>$params['post_id']));
		$wpdb->update($wpdb->posts,array('post_status'=>'pendingpayment'),array('ID'=>$params['order_id']));
		
		$this->PFCreateProcessRecord(
			array( 
		        'user_id' => $params['post_author'],
		        'item_post_id' => $params['post_id'],
				'processname' => $expire_message
		    )
		);
	}
}