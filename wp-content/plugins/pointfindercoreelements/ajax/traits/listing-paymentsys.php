<?php 
if (class_exists('PointFinderListingPaymentSYS')) {
	return;
}


class PointFinderListingPaymentSYS extends Pointfindercoreelements_AJAX
{
    public function __construct(){}

    public function pf_ajax_listingpaymentsystem(){
  
	  check_ajax_referer( 'pfget_lprice', 'security');
	  
	  header('Content-Type: application/json; charset=UTF-8;');

	  $c = $p = $f = $o = $px = $output = $output_top = $output_main = $total_pr_output = $total_pr = $scriptoutput = '';
	  $hide_somepayments = 0;

	  /* Listing Type */
	  if(isset($_POST['c']) && $_POST['c']!=''){
	    $c = esc_attr($_POST['c']);
	  }


	  /* Selected Package */
	  if(isset($_POST['p']) && $_POST['p']!=''){
	    $p = esc_attr($_POST['p']);
	  }

	  /*Featured Status*/
	  if(isset($_POST['f']) && $_POST['f']!=''){
	    $f = esc_attr($_POST['f']);
	  }

	  /*Order ID*/
	  if(isset($_POST['o']) && $_POST['o']!=''){
	    $o = esc_attr($_POST['o']);
	  }

	  /*Process*/
	  if(isset($_POST['px']) && $_POST['px']!=''){
	    $px = esc_attr($_POST['px']);
	    /*1-> Edit */
	  }


	  /* Start: Defaults from Options Panel */
	    $bank_status = $this->PFSAIssetControl('setup20_paypalsettings_bankdeposit_status','','0');
	    $paypal_status = $this->PFSAIssetControl('setup20_paypalsettings_paypal_status','','0');
	    $stripe_status = $this->PFSAIssetControl('setup20_stripesettings_status','','0');
	    
	    $pags_status = $this->PFPGIssetControl('pags_status','','0');
	    $payu_status = $this->PFPGIssetControl('payu_status','','0');
	    $ideal_status = $this->PFPGIssetControl('ideal_status','','0');
	    $robo_status = $this->PFPGIssetControl('robo_status','','0');
	    $iyzico_status = $this->PFPGIssetControl('iyzico_status','','0');
	    $twocho_status = $this->PFPGIssetControl('2cho_status','','0');
	    $payf_status = $this->PFPGIssetControl('payf_status','','0');
	   

	    $current_user = wp_get_current_user();
	    $user_id = $current_user->ID;
	  /* End: Defaults from Options Panel */

	     if (class_exists('Pointfinderstripesubscriptions')) {
	        $subscription_check = get_post_meta($p,'webbupointfinder_lp_stripe_subscription',true);
	      }

	    $results = $this->pointfinder_calculate_listingtypeprice($c,$f,$p);

	    $total_pr = $results['total_pr'];
	    $cat_price = $results['cat_price'];
	    $pack_price = $results['pack_price'];
	    $featured_price = $results['featured_price'];
	    $total_pr_output_vat = $results['total_pr_output_vat'];
	    $total_pr_output_bfvat = $results['total_pr_output_bfvat'];
	    $total_pr_output = $results['total_pr_output'];
	    $featured_pr_output = $results['featured_pr_output'];
	    $pack_pr_output = $results['pack_pr_output'];
	    $cat_pr_output = $results['cat_pr_output'];
	    $pack_title = $results['pack_title'];

	    if ($f == 1 && $this->pointfinder_get_package_price_ppp($p) == 0) {
	      $hide_somepayments = 1;
	    }

	    
	    if (!empty($o)) {
	      $bank_current = get_post_meta( $o, 'pointfinder_order_bankcheck', true);
	    } else {
	      $bank_current = false;
	    }
	    

	    /* Price info show */
	    if ($total_pr != 0) {
	      $output_top .= '<div class="pf-membership-price-header">'.esc_html__("Summary",'pointfindercoreelements').'</div>';
	      $output_top .= '
	      <div class="pf-membership-package-box">
	        <div class="pf-lppack-package-info">
	        <ul>';
	          if ($cat_price != 0) {
	            $output_top .= '<li><span class="pf-lppack-package-info-title">'.esc_html__("Listing Type :",'pointfindercoreelements').' </span> <span class="pf-lppack-package-info-price">'.$cat_pr_output.'</span></li>';
	          }
	          if ($pack_price != 0) {
	            $output_top .= '<li><span class="pf-lppack-package-info-title">'.sprintf(__("Package (%s) :",'pointfindercoreelements'),'<small>'.$pack_title.'</small>').' </span> <span class="pf-lppack-package-info-price">'.$pack_pr_output.'</span></li>';
	          }
	          if ($featured_price != 0) {
	            $output_top .= '<li><span class="pf-lppack-package-info-title">'.esc_html__("Featured Item :",'pointfindercoreelements').' </span> <span class="pf-lppack-package-info-price">'.$featured_pr_output.'</span></li>';
	          }

	          $setup4_pricevat = $this->PFSAIssetControl('setup4_pricevat','','0');
	          if ($setup4_pricevat == 1) {
	            $setup4_pv_pr = $this->PFSAIssetControl('setup4_pv_pr','','0');
	            $output_top .= '<li class="pf-lppack-package-info-title pftotal-before-vat"><span class="pf-lppack-package-info-title">'.esc_html__("Sub Total Before VAT :",'pointfindercoreelements').' </span> <span class="pf-lppack-package-info-price">'.$total_pr_output_bfvat.'</span></li>';
	            $output_top .= '<li class="pf-lppack-package-info-title"><span class="pf-lppack-package-info-title">'.sprintf(esc_html__("VAT (%s) :",'pointfindercoreelements'),$setup4_pv_pr.'%').' </span> <span class="pf-lppack-package-info-price">'.$total_pr_output_vat.'</span></li>';
	          }
	          

	          $output_top .= '<li class="pf-total-pricelp"><span class="pf-lppack-package-info-title">'.esc_html__("Total :",'pointfindercoreelements').' </span> <span class="pf-lppack-package-info-price">'.$total_pr_output.'</span></li>';

	      $output_top .= ' 
	        </ul>
	        </div>
	      </div>';
	    }
	   

	    /*Payment Options*/
	    if ($total_pr != 0) {
	      $output_main .= '<div class="pf-membership-price-header">'.esc_html__("Payment Options",'pointfindercoreelements').'</div>';
	      
	      if ($bank_status == 1 && $px != 1) {
	       
	        if ($bank_current != false && !empty($bank_current)) {
	          $output .= '
	          <div class="pf-lpacks-upload-option">
	            <input name="pf_lpacks_payment_selection" type="radio" id="pfm-payment-bank" value="bank" disabled="disabled">
	            <label for="pfm-payment-bank">'.esc_html__("Bank Transfer",'pointfindercoreelements').' <font style="font-weight:normal;"> '.esc_html__('(Disabled - Please complete or cancel existing transfer.)','pointfindercoreelements').'</font></label>
	            <div class="pfm-active">
	            <p>'.__("Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won't be approved until the funds have cleared in our account.",'pointfindercoreelements').'</p>
	            </div>
	          </div>';
	        } else {
	          $output .= '
	          <div class="pf-lpacks-upload-option">
	            <input name="pf_lpacks_payment_selection" type="radio" id="pfm-payment-bank" value="bank">
	            <label for="pfm-payment-bank">'.esc_html__("Bank Transfer",'pointfindercoreelements').'</label>
	            <div class="pfm-active">
	            <p>'.__("Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won't be approved until the funds have cleared in our account.",'pointfindercoreelements').'</p>
	            </div>
	          </div>';
	        }
	      }

	      if ($paypal_status == 1) {  
	        $output .= '
	        <div class="pf-lpacks-upload-option">
	          <input name="pf_lpacks_payment_selection" type="radio" id="pfm-payment-paypal" value="paypal">
	          <label for="pfm-payment-paypal">'.esc_html__('Paypal','pointfindercoreelements').'</label>
	          <div class="pfm-active">
	          <p>'.__("Pay via PayPal; you can pay with your credit card if you don't have a PayPal account.",'pointfindercoreelements').'</p>
	          </div>
	        </div>';
	        $setup31_userpayments_recurringoption = $this->PFSAIssetControl('setup31_userpayments_recurringoption','','1');
	        if($setup31_userpayments_recurringoption == 1 && $px != 1 && $hide_somepayments == 0){
	          $output .= '
	          <div class="pf-lpacks-upload-option">
	            <input name="pf_lpacks_payment_selection" type="radio" id="pfm-payment-paypal2" value="paypal2">
	            <label for="pfm-payment-paypal2">'.esc_html__('Paypal Recurring Payment','pointfindercoreelements').'</label>
	            <div class="pfm-active">
	            <p>'.__("Pay via PayPal Recurring Payment; you can create automated payments for this order.",'pointfindercoreelements').'</p>
	            </div>
	          </div>';
	        }
	      }

	      if ($stripe_status == 1) {
	        if (class_exists('Pointfinderstripesubscriptions')) {
	          $output .= '
	          <div class="pf-lpacks-upload-option">
	            <input name="pf_lpacks_payment_selection" type="radio" id="pfm-payment-stripe" value="stripe">';

	            if ($subscription_check) {
	              $output .= '<label for="pfm-payment-stripe">'.esc_html__('Credit Card (Stripe Recurring Payment)','pointfindercoreelements').'</label>';
	            } else {
	              $output .= '<label for="pfm-payment-stripe">'.esc_html__('Credit Card (Stripe)','pointfindercoreelements').'</label>';
	            }
	            

	            $output .= '<div class="pfm-active">
	            <p>'.__("Pay via Credit Card; you can pay with your credit card. (This service is using Stripe Payment Gateway)",'pointfindercoreelements').'</p>
	            </div>
	          </div>';
	        } else {
	          $output .= '
	          <div class="pf-lpacks-upload-option">
	            <input name="pf_lpacks_payment_selection" type="radio" id="pfm-payment-stripe" value="stripe">
	            <label for="pfm-payment-stripe">'.esc_html__('Credit Card (Stripe)','pointfindercoreelements').'</label>
	            <div class="pfm-active">
	            <p>'.__("Pay via Credit Card; you can pay with your credit card. (This service is using Stripe Payment Gateway)",'pointfindercoreelements').'</p>
	            </div>
	          </div>';
	        }
	      }

	      if ($pags_status == 1) {
	        $output .= '
	        <div class="pf-lpacks-upload-option">
	          <input name="pf_lpacks_payment_selection" type="radio" id="pfm-payment-pags" value="pags">
	          <label for="pfm-payment-pags">'.esc_html__('PagSeguro Payment System','pointfindercoreelements').'</label>
	          <div class="pfm-active">
	          <p>'.__("Pay via PagSeguro; you can pay with your PagSeguro account.",'pointfindercoreelements').'</p>
	          </div>
	        </div>';
	      }

	      if ($payu_status == 1) {
	        $output .= '
	        <div class="pf-lpacks-upload-option">
	          <input name="pf_lpacks_payment_selection" type="radio" id="pfm-payment-payu" value="payu">
	          <label for="pfm-payment-payu">'.esc_html__('PayU Money Payment System','pointfindercoreelements').'</label>
	          <div class="pfm-active">
	          <p>'.__("Pay via Payu Money; you can pay with your Payu Money account.",'pointfindercoreelements').'</p>
	          </div>
	        </div>';
	      }
	      
	      if ($ideal_status == 1) {
	        require_once PFCOREELEMENTSDIR . 'includes/Mollie/API/Autoloader.php';
	        $ideal_id = $this->PFPGIssetControl('ideal_id','','');
	        $mollie = new Mollie_API_Client;
	        $mollie->setApiKey($ideal_id);
	              
	        $output .= '
	        <div class="pf-lpacks-upload-option">
	          <input name="pf_lpacks_payment_selection" type="radio" id="pfm-payment-ideal" value="ideal">
	          <label for="pfm-payment-ideal">'.esc_html__('iDeal Payment System','pointfindercoreelements').'</label>
	          <div class="pfm-active">
	          <p>'.__("Pay via iDeal; you can pay with your iDeal account.",'pointfindercoreelements').'</p>
	          ';
	          $issuers = $mollie->issuers->all();
	          $output .= esc_html__("Select your bank:","pointfindercoreelements");
	          $output .= '<select name="issuer" style="margin-top:5px;margin-left: 5px;">';

	          foreach ($issuers as $issuer)
	          {
	            if ($issuer->method == Mollie_API_Object_Method::IDEAL)
	            {
	              $output .= '<option value=' . htmlspecialchars($issuer->id) . '>' . htmlspecialchars($issuer->name) . '</option>';
	            }
	          }

	          $output .= '<option value="">or select later</option>';
	          $output .= '</select>';
	          $output .= '
	          </div>
	        </div>';
	      }

	      if ($robo_status == 1) {
	        $output .= '
	        <div class="pf-lpacks-upload-option">
	          <input name="pf_lpacks_payment_selection" type="radio" id="pfm-payment-robo" value="robo">
	          <label for="pfm-payment-robo">'.esc_html__('Robokassa Payment System','pointfindercoreelements').'</label>
	          <div class="pfm-active">
	          <p>'.esc_html__("Pay via Robokassa; you can pay with your Robokassa account.",'pointfindercoreelements').'</p>
	          </div>
	        </div>';
	      }


	      if ($twocho_status == 1) {
	        $output .= '
	        <div class="pf-lpacks-upload-option">
	          <input name="pf_lpacks_payment_selection" type="radio" id="pfm-payment-t2co" value="t2co">
	          <label for="pfm-payment-t2co">'.esc_html__('2Checkout Payment System','pointfindercoreelements').'</label>
	          <div class="pfm-active">
	          <p>'.esc_html__("Pay via 2Checkout; you can pay with your 2Checkout account.",'pointfindercoreelements').'</p>
	          </div>
	        </div>';
	      }

	      if ($payf_status == 1) {
	        $output .= '
	        <div class="pf-lpacks-upload-option">
	          <input name="pf_lpacks_payment_selection" type="radio" id="pfm-payment-payf" value="payf">
	          <label for="pfm-payment-payf">'.esc_html__('PayFast Payment System','pointfindercoreelements').'</label>
	          <div class="pfm-active">
	          <p>'.esc_html__("Pay via PayFast; you can pay with your PayFast account.",'pointfindercoreelements').'</p>
	          </div>
	        </div>';
	      }

	      if ($iyzico_status == 1) {
	          $output .= '
	          <div class="pf-lpacks-upload-option">
	            <input name="pf_lpacks_payment_selection" type="radio" id="pfm-payment-iyzico" value="iyzico">
	            <label for="pfm-payment-iyzico">'.esc_html__('Iyzico Payment System','pointfindercoreelements').'</label>
	            <div class="pfm-active">
	              <p>'.esc_html__("Pay via iyzico; you can pay with your iyzico account.",'pointfindercoreelements').'</p>
	              ';
	              $usermetaarr = get_user_meta($user_id);

	              $output .= '<div class="iyzico-fields golden-forms">';
	            
	              if(empty($usermetaarr['first_name'][0])){
	                $output .= '<section>
	                  <label for="pfusr_firstname" class="lbl-text">'.esc_html__('First Name','pointfindercoreelements').'<span style="color:red!important">*</span></label>
	                  <label class="lbl-ui">
	                    <input type="text" name="pfusr_firstname" id="pfusr_firstname" class="input">
	                  </label>                            
	                </section>';
	              }
	              if(empty($usermetaarr['last_name'][0])){
	                $output .= '<section>
	                  <label for="pfusr_lastname" class="lbl-text">'.esc_html__('Last Name','pointfindercoreelements').'<span style="color:red!important">*</span></label>
	                  <label class="lbl-ui">
	                    <input type="text" name="pfusr_lastname" id="pfusr_lastname" class="input">
	                  </label>                            
	                </section>';
	              }
	              if(empty($usermetaarr['user_mobile'][0])){
	                $output .= '<section>
	                  <label for="pfusr_mobile" class="lbl-text">'.esc_html__('GSM Number','pointfindercoreelements').'</label>
	                  <label class="lbl-ui">
	                    <input type="text" name="pfusr_mobile" id="pfusr_mobile" class="input">
	                  </label>                            
	                </section>';
	              }
	              if(empty($usermetaarr['user_vatnumber'][0])){
	                $output .= '<section>
	                  <label for="pfusr_vatnumber" class="lbl-text">'.esc_html__('VAT Number','pointfindercoreelements').'<span style="color:red!important">*</span></label>
	                  <label class="lbl-ui">
	                    <input type="text" name="pfusr_vatnumber" id="pfusr_vatnumber" class="input">
	                  </label>                            
	                </section>';
	              }
	              if(empty($usermetaarr['user_country'][0])){
	                $output .= '<section>
	                  <label for="pfusr_country" class="lbl-text">'.esc_html__('Country','pointfindercoreelements').'<span style="color:red!important">*</span></label>
	                  <label class="lbl-ui">
	                    <input type="text" name="pfusr_country" id="pfusr_country" class="input">
	                  </label>                            
	                </section>';
	              }
	              if(empty($usermetaarr['user_city'][0])){
	                $output .= '<section>
	                  <label for="pfusr_city" class="lbl-text">'.esc_html__('City','pointfindercoreelements').'<span style="color:red!important">*</span></label>
	                  <label class="lbl-ui">
	                    <input type="text" name="pfusr_city" id="pfusr_city" class="input">
	                  </label>                            
	                </section>';
	              }
	              
	              if(empty($usermetaarr['user_address'][0])){
	                $output .= '<section>
	                  <label for="pfusr_address" class="lbl-text">'.esc_html__('Address','pointfindercoreelements').'<span style="color:red!important">*</span></label>
	                  <label class="lbl-ui">
	                    <input type="text" name="pfusr_address" id="pfusr_address" class="input">
	                  </label>                            
	                </section>';
	              }

	              if (
	                empty($usermetaarr['user_address'][0]) 
	                || empty($usermetaarr['user_city'][0])
	                || empty($usermetaarr['user_country'][0])
	                || empty($usermetaarr['user_vatnumber'][0])
	                || empty($usermetaarr['user_mobile'][0])
	                || empty($usermetaarr['first_name'][0])
	                || empty($usermetaarr['last_name'][0])
	                ) {
	                $output .= '<small>'.esc_html__('Please fill above informations before use iyzico payment system.','pointfindercoreelements').'</small>';
	              }
	              

	              $output .= '</div>';
	            $output .= '</div>';

	          $output .= '</div>';
	      }


	      if (empty($output)) {
	        $output = '<div class="pf-lpacks-upload-option">'.esc_html__('Please enable a payment system by using Options Panel','pointfindercoreelements').'</div>';
	      }else{
	        if ($iyzico_status == 1) {
	          $output .= '
	          <script>
	          (function($) {
	          "use strict";
	            $(function(){
	              var lpacks_radio = $(".pf-lpacks-upload-option input[type=\'radio\']");
	              lpacks_radio.on("change", function () {
	                lpacks_radio.parents().removeClass("active");
	                $(this).parent().addClass("active");
	                if ($(this).val() == "iyzico") {
	                ';
	                   if(empty($usermetaarr['first_name'][0])){
	                   $output .= '$("#pfusr_firstname").rules( "add", {
	                    required: true,
	                    messages: {
	                      required: "'.esc_html__('Please add your Name (Iyzico requirement)','pointfindercoreelements').'"
	                    }
	                    });';
	                   }
	                   if(empty($usermetaarr['last_name'][0])){
	                   $output .= '$("#pfusr_lastname").rules( "add", {required: true,messages: {required: "'.esc_html__('Please add your Last Name (Iyzico requirement)','pointfindercoreelements').'"}});';
	                   }
	                   if(empty($usermetaarr['user_vatnumber'][0])){
	                   $output .= '$("#pfusr_vatnumber").rules( "add", {required: true,messages: {required: "'.esc_html__('Please add your VAT Number (Iyzico requirement)','pointfindercoreelements').'"}});';
	                   }
	                   if(empty($usermetaarr['user_country'][0])){
	                   $output .= '$("#pfusr_country").rules( "add", {required: true,messages: {required: "'.esc_html__('Please add your Country (Iyzico requirement)','pointfindercoreelements').'"}});';
	                   }
	                   if(empty($usermetaarr['user_city'][0])){
	                   $output .= '$("#pfusr_city").rules( "add", {required: true,messages: {required: "'.esc_html__('Please add your City (Iyzico requirement)','pointfindercoreelements').'"}});';
	                   }
	                   if(empty($usermetaarr['user_address'][0])){
	                   $output .= '$("#pfusr_address").rules( "add", {required: true,messages: {required: "'.esc_html__('Please add your Address (Iyzico requirement)','pointfindercoreelements').'"}});';
	                   }
	                $output .= '
	                }else{
	                 
	                  ';
	                   if(empty($usermetaarr['first_name'][0])){
	                   $output .= '$("#pfusr_firstname").rules( "remove" );';
	                   }
	                   if(empty($usermetaarr['last_name'][0])){
	                   $output .= '$("#pfusr_lastname").rules( "remove" );';
	                   }
	                   if(empty($usermetaarr['user_vatnumber'][0])){
	                   $output .= '$("#pfusr_vatnumber").rules( "remove" );';
	                   }
	                   if(empty($usermetaarr['user_country'][0])){
	                   $output .= '$("#pfusr_country").rules( "remove" );';
	                   }
	                   if(empty($usermetaarr['user_city'][0])){
	                   $output .= '$("#pfusr_city").rules( "remove" );';
	                   }
	                   if(empty($usermetaarr['user_address'][0])){
	                   $output .= '$("#pfusr_address").rules( "remove" );';
	                   }
	                $output .= '

	                }
	              });

	              
	            });
	          })(jQuery);
	          </script>
	          ';
	        } 
	      }

	    }else{
	      $output .= '<input name="pf_lpacks_payment_selection" type="hidden" id="pfm-payment-free" value="free">';
	    }

	  echo json_encode(array('html'=>trim($output_top.$output_main.$output),'totalpr'=>$total_pr));

	die();
	}
  
}