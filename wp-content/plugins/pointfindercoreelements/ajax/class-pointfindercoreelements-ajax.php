<?php 

if (class_exists('Pointfindercoreelements_AJAX')) {
	return;
}

/**
 * Ajax Functions
 */
class Pointfindercoreelements_AJAX
{
	use PointFinderOptionFunctions;
	use PointFinderCommonFunctions;
	use PointFinderCUFunctions;
	use PointFinderWPMLFunctions;
	use PointFinderMailSystem;
	use PointFinderReviewFunctions;
	

    public function __construct(){}

	public function recaptcha_verify_sys($g_recaptcha_response){
	 if (isset($g_recaptcha_response)) {
	     $reCaptcha = new Pointfinder_reCaptcha_System();
	     $recaptcha_result = $reCaptcha->check_recaptcha($g_recaptcha_response);
	     $reCaptcha = null;

	     if (!$recaptcha_result) {
	         echo json_encode(array( 'process' => false, 'mes' => esc_html__('Wrong reCaptcha. Please verify first.', 'pointfindercoreelements')));
	         die();
	     }
	 } else {
	     echo json_encode(array( 'process' => false, 'mes' => esc_html__('Please verify reCaptcha!', 'pointfindercoreelements')));
	     die();
	 }
	}

	public function recaptcha_verify_sysx($g_recaptcha_response, $formtype){
	 if (isset($g_recaptcha_response)) {
	     $reCaptcha = new Pointfinder_reCaptcha_System();
	     $recaptcha_result = $reCaptcha->check_recaptcha($g_recaptcha_response);
	     $reCaptcha = null;
	     $reply = false;
	     if ($formtype != 'login') {
	     	$reply = 1;
	     }
	    
	     if (!$recaptcha_result) {
	         echo json_encode(array( $formtype => $reply, 'mes' => esc_html__('Wrong reCaptcha. Please verify first.', 'pointfindercoreelements')));
	         die();
	     }
	 } else {
	     echo json_encode(array( $formtype => $reply, 'mes' => esc_html__('Please verify reCaptcha!', 'pointfindercoreelements')));
	     die();
	 }
	}

	public function pointfinder_paypal_request($params = array()){	

		$defaults = array( 
		'returnurl' => '',
		'cancelurl' => '',
		'total_package_price' => '',
		'payment_custom_field' => '',//item_post_id
		'payment_custom_field1' => 'n',//description,
		'payment_custom_field2' => '',//upgrade package id
		'payment_custom_field3' => '',//description
		'recurring' => 0,
		'billing_description' => '',
		'paymentName' => '',
		'apipackage_name' => '',
		'featuredrecurring' => 0,
		'featured_billing_description' => '',
		'featured_package_price' => '',
		'total_package_price_recurring' => 0
		);

		$params = array_merge($defaults, $params);

		$paypal_price_unit = $this->PFSAIssetControl('setup20_paypalsettings_paypal_price_unit','','USD');
		$paypal_sandbox = $this->PFSAIssetControl('setup20_paypalsettings_paypal_sandbox','','0');
		$paypal_api_user = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_user','','');
		$paypal_api_pwd = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_pwd','','');
		$paypal_api_signature = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_signature','','2');

		$requestParams = array(
		 'RETURNURL' => $params['returnurl'], 
		 'CANCELURL' => $params['cancelurl']
		);


		$orderParams = array(
		   'PAYMENTREQUEST_0_AMT' => $params['total_package_price'],
		   'PAYMENTREQUEST_0_CURRENCYCODE' => $paypal_price_unit,
		   'PAYMENTREQUEST_0_ITEMAMT' => $params['total_package_price'],
		   'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
		   'PAYMENTREQUEST_0_CUSTOM' => $params['payment_custom_field'].','.$params['payment_custom_field1'],
		   'PAYMENTREQUEST_0_DESC' => $params['payment_custom_field3'],
		   'PAYMENTREQUEST_0_NOTETEXT' => $params['payment_custom_field2'],
		   /*"SOLUTIONTYPE" => 'Sole',
	        "LANDINGPAGE" => 'Billing'*/
		);

		if ($params['recurring'] == 1) {
			$orderParams['L_BILLINGTYPE0'] = 'RecurringPayments';
			$orderParams['L_BILLINGAGREEMENTDESCRIPTION0'] = $params['billing_description'];
		}

		if ($params['recurring'] == 1 && $params['featuredrecurring'] == 1) {
			if ($params['total_package_price_recurring'] == 0 ) {
				$item_arr = array(
				 'L_PAYMENTREQUEST_0_NAME0' => $params['paymentName'],
				 'L_PAYMENTREQUEST_0_DESC0' => esc_html__('Featured Point','pointfindercoreelements'),
				 'L_PAYMENTREQUEST_0_AMT0' => $params['featured_package_price'],
				 'L_PAYMENTREQUEST_0_QTY0' => '1',
				);
				$orderParams['L_BILLINGTYPE0'] = 'RecurringPayments';
				$orderParams['L_BILLINGAGREEMENTDESCRIPTION0'] = $params['featured_billing_description'];
			}else{
				$item_arr = array(
				 'L_PAYMENTREQUEST_0_NAME0' => $params['paymentName'],
				 'L_PAYMENTREQUEST_0_DESC0' => $params['apipackage_name'],
				 'L_PAYMENTREQUEST_0_AMT0' => $params['total_package_price_recurring'],
				 'L_PAYMENTREQUEST_0_QTY0' => '1',
				 'L_PAYMENTREQUEST_0_NAME1' => $params['paymentName'],
				 'L_PAYMENTREQUEST_0_DESC1' => esc_html__('Featured Point','pointfindercoreelements'),
				 'L_PAYMENTREQUEST_0_AMT1' => $params['featured_package_price'],
				 'L_PAYMENTREQUEST_0_QTY1' => '1'
				);
				$orderParams['L_BILLINGTYPE0'] = 'RecurringPayments';
				$orderParams['L_BILLINGAGREEMENTDESCRIPTION0'] = $params['billing_description'];
				$orderParams['L_BILLINGTYPE1'] = 'RecurringPayments';
				$orderParams['L_BILLINGAGREEMENTDESCRIPTION1'] = $params['featured_billing_description'];
			}
			
		}else{
			$item_arr = array(
			 'L_PAYMENTREQUEST_0_NAME0' => $params['paymentName'],
			 'L_PAYMENTREQUEST_0_DESC0' => $params['apipackage_name'],
			 'L_PAYMENTREQUEST_0_AMT0' => $params['total_package_price'],
			 'L_PAYMENTREQUEST_0_QTY0' => '1',
			 //'L_PAYMENTREQUEST_0_ITEMCATEGORY0' => 'Digital',
			);
		}

		


		$infos = array();
		$infos['USER'] = $paypal_api_user;
		$infos['PWD'] = $paypal_api_pwd;
		$infos['SIGNATURE'] = $paypal_api_signature;
		if($paypal_sandbox == 1){$sandstatus = true;}else{$sandstatus = false;}
		
		$paypal = new Paypal($infos,$sandstatus);
		$response = $paypal -> request('SetExpressCheckout',$requestParams + $orderParams + $item_arr);

		unset($paypal);
		
		return $response;
	}
}

