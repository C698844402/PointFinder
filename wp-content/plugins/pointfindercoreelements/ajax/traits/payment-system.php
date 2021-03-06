<?php 
if (class_exists('PointFinderPaymentSystem')) {
  return;
}


class PointFinderPaymentSystem extends Pointfindercoreelements_AJAX
{
    public function __construct(){}

    public function pf_ajax_paymentsystem(){
  
      check_ajax_referer( 'pfget_paymentsystem', 'security');
      
    	header('Content-Type: application/json; charset=UTF-8;');

      if(isset($_POST['formtype']) && $_POST['formtype']!=''){$formtype = esc_attr($_POST['formtype']);}
      if(isset($_POST['itemid']) && $_POST['itemid']!=''){$item_post_id = esc_attr($_POST['itemid']);}else{$item_post_id = '';}
      if(isset($_POST['otype']) && $_POST['otype']!=''){$otype = esc_attr($_POST['otype']);}else{$otype = 0;}

      $icon_processout = 62;
      $msg_output = $pfreturn_url = $overlar_class = $output_html = '';
      $current_user = wp_get_current_user();
      $user_id = isset($current_user->ID)?$current_user->ID:0;
      $user_email = isset($current_user->user_email)?$current_user->user_email:'';

      if($user_id != 0){
        if ($item_post_id != '') {

          $setup4_membersettings_dashboard = $this->PFSAIssetControl('setup4_membersettings_dashboard','',site_url());
          $setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
          $pfmenu_perout = $this->PFPermalinkCheck();
          $setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');


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
              $result_id = $wpdb->get_var( $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s", 'pointfinder_order_itemid',$item_post_id));

              /* Check is this a change */
              $pointfinder_sub_order_change = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_change', true ));

            	switch($formtype){

                /*Paypal Request*/
            		case 'paypalrequest':

                  $setup20_paypalsettings_decimals = $this->PFSAIssetControl('setup20_paypalsettings_decimals','','2');
                  $pointfinder_order_pricesign = esc_attr(get_post_meta( $result_id, 'pointfinder_order_pricesign', true ));

                  if ($pointfinder_sub_order_change == 1 && $otype == 1) {
                    $pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_listingtime', true ));
                    $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_price', true ));
                    $pointfinder_order_listingtime = ($pointfinder_order_listingtime == '') ? 0 : $pointfinder_order_listingtime ;
                    $pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpid', true)); 
                    $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpname', true)); 

                    $total_package_price =  number_format($pointfinder_order_price, $setup20_paypalsettings_decimals, '.', ',');

                    $paymentName = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindercoreelements'));

                    $billing_description = $pointfinder_order_recurring = $total_package_price_recurring = $featured_package_price = $featuredrecurring  = $billing_description_featured = '';


                    $response = $this->pointfinder_paypal_request(
                      array(
                        'returnurl' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_rec',
                        'cancelurl' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_cancel',
                        'total_package_price' => $total_package_price,
                        'total_package_price_recurring' => $total_package_price_recurring,
                        'featured_package_price' => $featured_package_price,
                        'payment_custom_field' => $item_post_id,
                        'recurring' => $pointfinder_order_recurring,
                        'billing_description' => $billing_description,
                        'paymentName' => $paymentName,
                        'apipackage_name' => $pointfinder_order_listingpname,
                        'featuredrecurring' => $featuredrecurring,
                        'featured_billing_description' => $billing_description_featured,
                        'payment_custom_field1' => $otype,
                        'payment_custom_field3' => $paymentName.' '.$pointfinder_order_listingpname
                      )
                    );


                  }else{
                    
                    $pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_order_listingtime', true ));
                    $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
                    $pointfinder_order_recurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_recurring', true ));
                    $pointfinder_order_listingtime = ($pointfinder_order_listingtime == '') ? 0 : $pointfinder_order_listingtime ;
                    $pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true)); 
                    $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpname', true)); 

                    $total_package_price =  number_format($pointfinder_order_price, $setup20_paypalsettings_decimals, '.', ',');

                    $paymentName = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindercoreelements'));

                    $billing_description = $total_package_price_recurring = $featured_package_price = $featuredrecurring  = $billing_description_featured = '';

                    if ($pointfinder_order_recurring == 1) {

                      /* Added with v1.6.4 */
                      $pointfinder_order_featured = esc_attr(get_post_meta($result_id, 'pointfinder_order_featured', true)); 
                      if ($pointfinder_order_featured == 1) {
                        $setup31_userpayments_pricefeatured = $this->PFSAIssetControl('setup31_userpayments_pricefeatured','','5');
                        $stp31_daysfeatured = $this->PFSAIssetControl('stp31_daysfeatured','','3');

                        $total_package_price_recurring = $pointfinder_order_price -  $setup31_userpayments_pricefeatured;

                        $total_package_price_recurring = number_format($total_package_price_recurring, $setup20_paypalsettings_decimals, '.', ',');
                        $setup31_userpayments_pricefeatured = number_format($setup31_userpayments_pricefeatured, $setup20_paypalsettings_decimals, '.', ',');

                        $billing_description_featured = sprintf(
                        esc_html__('%s / %s / Recurring: %s%s per %s days / For: (%s)','pointfindercoreelements'),
                        $paymentName,
                        esc_html__('Featured Point','pointfindercoreelements'),
                        $setup31_userpayments_pricefeatured,
                        $pointfinder_order_pricesign,
                        $stp31_daysfeatured,
                        $item_post_id
                        );

                        $featuredrecurring = 1;
                        $featured_package_price = $setup31_userpayments_pricefeatured;
                      }else{
                        $total_package_price_recurring = $total_package_price;
                        $featuredrecurring = $billing_description_featured = $featured_package_price = '';
                      }

                      $billing_description = sprintf(
                        esc_html__('%s / %s / Recurring: %s%s per %s days / For: (%s)','pointfindercoreelements'),
                        $paymentName,
                        $pointfinder_order_listingpname,
                        $total_package_price_recurring,
                        $pointfinder_order_pricesign,
                        $pointfinder_order_listingtime,
                        $item_post_id
                        );
                    }


                    $response = $this->pointfinder_paypal_request(
                      array(
                        'returnurl' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_rec',
                        'cancelurl' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_cancel',
                        'total_package_price' => $total_package_price,
                        'total_package_price_recurring' => $total_package_price_recurring,
                        'featured_package_price' => $featured_package_price,
                        'payment_custom_field' => $item_post_id,
                        'recurring' => $pointfinder_order_recurring,
                        'billing_description' => $billing_description,
                        'paymentName' => $paymentName,
                        'apipackage_name' => $pointfinder_order_listingpname,
                        'featuredrecurring' => $featuredrecurring,
                        'featured_billing_description' => $billing_description_featured
                      )
                    );
                  }
                  
                  if(!$response){ 
                    $msg_output .= esc_html__( 'Error: No Response', 'pointfindercoreelements' ).'<br>';
                    $icon_processout = 485;
                    /*$errorval .= $paypal->getErrors();*/
                  }
                 
                  if(is_array($response) && ($response['ACK'] == 'Success')) { 
                    $token = $response['TOKEN']; 
                    
                    if ($pointfinder_sub_order_change == 1) {
                      update_post_meta($result_id, 'pointfinder_sub_order_token', $token ); 
                    }else{
                      update_post_meta($result_id, 'pointfinder_order_token', $token ); 
                    }

                    update_user_meta( $user_id, 'paymentsugoogle', $item_post_id );
                    
                    /*Create a payment record for this process */
                    $this->PF_CreatePaymentRecord(
                        array(
                        'user_id' =>  $user_id,
                        'item_post_id'  =>  $item_post_id,
                        'order_post_id' =>  $result_id,
                        'response'  =>  $response,
                        'token' =>  $response['TOKEN'],
                        'processname' =>  'SetExpressCheckout',
                        'status'  =>  $response['ACK'],
                        )
                      );
                  
                    $paypal_sandbox = $this->PFSAIssetControl('setup20_paypalsettings_paypal_sandbox','','0');
                    if($paypal_sandbox == 0){
                      $pfreturn_url = 'https://www.paypal.com/webscr?cmd=_express-checkout&token=' . urlencode($token).'';
                    }else{
                      $pfreturn_url = 'https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=' . urlencode($token).'';
                    }
                    
                    $msg_output .= esc_html__('Payment process started. Please wait redirection.(Sub order)','pointfindercoreelements');

                  }else{
                    /*Create a payment record for this process */
               
                    $this->PF_CreatePaymentRecord(
                        array(
                        'user_id' =>  $user_id,
                        'item_post_id'  =>  $item_post_id,
                        'order_post_id' =>  $result_id,
                        'response'  =>  $response,
                        'token' =>  '',
                        'processname' =>  'SetExpressCheckout',
                        'status'  =>  $response['ACK'],
                        )
                      );

                    $msg_output .= esc_html__( 'Error: Not Success', 'pointfindercoreelements' ).'<br>';
                    if (isset($response['L_SHORTMESSAGE0'])) {
                     $msg_output .= '<small>'.$response['L_SHORTMESSAGE0'].'</small><br/>';
                    }
                    if (isset($response['L_LONGMESSAGE0'])) {
                     $msg_output .= '<small>'.$response['L_LONGMESSAGE0'].'</small><br/>';
                    }
                    $icon_processout = 485;
                    
                  }
                break;

                /*Stripe Request*/
                case 'creditcardstripe':

                    $setup20_stripesettings_decimals = $this->PFSAIssetControl('setup20_stripesettings_decimals','','2');
                    $setup20_stripesettings_publishkey = $this->PFSAIssetControl('setup20_stripesettings_publishkey','','');
                    $setup20_stripesettings_currency = $this->PFSAIssetControl('setup20_stripesettings_currency','','USD');
                    $setup20_stripesettings_sitename = $this->PFSAIssetControl('setup20_stripesettings_sitename','','');
                    
                    if ($pointfinder_sub_order_change == 1 && $otype == 1) {
                       $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_price', true ));
                       $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpname', true)); 
                    }else{
                       $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
                       $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpname', true)); 
                    }
                   
                    if ($setup20_stripesettings_decimals == 0) {
                      $total_package_price =  $pointfinder_order_price;
                    }else{
                      $total_package_price =  $pointfinder_order_price.'00';
                    }
                break;

                /*Stripe Response*/
                case 'stripepayment':
                  
                  require_once( PFCOREELEMENTSDIR.'includes/stripe/init.php');

                  $setup20_stripesettings_decimals = $this->PFSAIssetControl('setup20_stripesettings_decimals','','2');
                  $setup20_stripesettings_secretkey = $this->PFSAIssetControl('setup20_stripesettings_secretkey','','');
                  $setup20_stripesettings_publishkey = $this->PFSAIssetControl('setup20_stripesettings_publishkey','','');
                  $setup20_stripesettings_currency = $this->PFSAIssetControl('setup20_stripesettings_currency','','USD');

                  if ($pointfinder_sub_order_change == 1 && $otype == 1) {
                    $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_price', true ));
                    $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpname', true));
                    $pointfinder_order_listingpname .= esc_html__('(Plan/Featured/Category Change)','pointfindercoreelements'); 
                  }else{
                    $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
                    $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpname', true)); 
                  }

                  if ($setup20_stripesettings_decimals == 0) {
                    $total_package_price =  $pointfinder_order_price;
                    $total_package_price_ex =  $pointfinder_order_price;
                  }else{
                    $total_package_price =  $pointfinder_order_price.'00';
                    $total_package_price_ex =  $pointfinder_order_price.'.00';
                  }
                  
                  $stripe = array("secret_key" => $setup20_stripesettings_secretkey,"publishable_key" => $setup20_stripesettings_publishkey);

                  \Stripe\Stripe::setApiKey($stripe['secret_key']);
                  

                  $token  = $_POST['token'];
                  $token = $this->PFCleanArrayAttr('PFCleanFilters',$token);
             
                  $charge = '';

                  if ($total_package_price != 0) {
                    try {

                      $process_status = '';
                      if (class_exists('Pointfinderstripesubscriptions')) {
                        $recurring_mail = false;
                        $pointfinder_order_listingpid = get_post_meta($result_id, 'pointfinder_order_listingpid', true);
                        $subscription_check = get_post_meta($pointfinder_order_listingpid,'webbupointfinder_lp_stripe_subscription',true);

                        if (!empty($subscription_check) && $otype == 0) {
                            
                            $customer = \Stripe\Customer::create(array(
                              'source' => $token['id'],
                              'description' => 'WordPress UserID: '.$user_id,
                              'email' => $user_email
                            ));

                            $stripe_customer_id = $customer->id;

                            $subscription = \Stripe\Subscription::create(array(
                              "customer" => $stripe_customer_id,
                              "items" => [["plan" => "".$subscription_check."",],]
                            ));
                            $process_status = $subscription->status;
                        }elseif (!empty($subscription_check) && $otype == 1) {
                            
                            $customer = \Stripe\Customer::create(array(
                              'source' => $token['id'],
                              'description' => 'WordPress UserID: '.$user_id,
                              'email' => $user_email
                            ));

                            $stripe_customer_id = $customer->id;

                            $subscription = \Stripe\Subscription::create(array(
                              "customer" => $stripe_customer_id,
                              "items" => [["plan" => "".$subscription_check."",],]
                            ));
                            $process_status = $subscription->status;
                        }else{
                            $charge = \Stripe\Charge::create(array(
                              'amount'   => $total_package_price,
                              'currency' => ''.$setup20_stripesettings_currency.'',
                              'source'  => (isset($token['id']))?$token['id']:'',
                              'description' => "Charge for ".$pointfinder_order_listingpname.'(ItemID: '.$item_post_id.' / UserID: '.$user_id.')',
                            ));
                            $process_status = $charge->status;
                        }
                      }else{
                        $charge = \Stripe\Charge::create(array(
                          'amount'   => $total_package_price,
                          'currency' => ''.$setup20_stripesettings_currency.'',
                          'source'  => (isset($token['id']))?$token['id']:'',
                          'description' => "Charge for ".$pointfinder_order_listingpname.'(ItemID: '.$item_post_id.' / UserID: '.$user_id.')',
                        ));

                        $process_status = $charge->status;
                      }
                     
                      if ( $process_status == 'succeeded' || $process_status == 'active') {
                        
                        if ($process_status == 'active') {
                          update_post_meta($result_id,'pointfinder_order_recurring',true);
                          update_post_meta($item_post_id,'stripsubscriptionid',$subscription->id);
                          $recurring_mail = true;
                          \Stripe\Subscription::update(
                            $subscription->id,
                            [
                              'metadata' => ['order_id' => "$result_id",'post_id' => "$item_post_id"],
                            ]
                          );
                         
                        }

                        $this->pointfinder_order_fallback_operations($result_id,$pointfinder_order_price);
                        
                        $this->PF_CreatePaymentRecord(
                          array(
                          'user_id' =>  $user_id,
                          'item_post_id'  =>  $item_post_id,
                          'order_post_id' => $result_id,
                          'processname' =>  'DoExpressCheckoutPaymentStripe',
                          'status'  =>  $process_status
                          )
                        );

                        $this->PF_CreateInvoice(
                          array( 
                            'user_id' => $user_id,
                            'item_id' => $item_post_id,
                            'order_id' => $result_id,
                            'description' => $pointfinder_order_listingpname,
                            'processname' => esc_html__('Credit Card Payment','pointfindercoreelements'),
                            'amount' => $total_package_price_ex,
                            'datetime' => strtotime("now"),
                            'packageid' => 0,
                            'status' => 'publish'
                          )
                        );

                        if ($pointfinder_sub_order_change == 1 && $otype == 1) {
                          
                          $pointfinder_sub_order_changedvals = get_post_meta( $result_id, 'pointfinder_sub_order_changedvals', true );
                                          
                          $this->pointfinder_additional_orders(
                            array(
                              'changedvals' => $pointfinder_sub_order_changedvals,
                              'order_id' => $result_id,
                              'post_id' => $item_post_id
                            )
                          );

                        }else{
                          $setup31_userlimits_userpublish = $this->PFSAIssetControl('setup31_userlimits_userpublish','','0');
                          $publishstatus = ($setup31_userlimits_userpublish == 1) ? 'publish' : 'pendingapproval' ;

                          wp_update_post(array('ID' => $item_post_id,'post_status' => $publishstatus) );
                          wp_update_post(array('ID' => $result_id,'post_status' => 'completed') );

                          $admin_email = get_option( 'admin_email' );
                          $setup33_emailsettings_mainemail = PFMSIssetControl('setup33_emailsettings_mainemail','',$admin_email);
                          $mail_item_title = get_the_title($item_post_id);
                          
                          if (class_exists('Pointfinderstripesubscriptions')) {
                            if ($recurring_mail) {
                              $pointfinder_order_listingtime = esc_attr(get_post_meta( $result_id, 'pointfinder_order_listingtime', true ));
                              $this->pointfinder_mailsystem_mailsender(
                                array(
                                  'toemail' => $user_info->user_email,
                                      'predefined' => 'recprofilecreated',
                                      'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price_ex,'packagename' => $pointfinder_order_listingpname,'nextpayment' => date("Y-m-d", strtotime("+".$pointfinder_order_listingtime." days")),'profileid' => $subscription->id),
                                  )
                                );

                              $this->pointfinder_mailsystem_mailsender(
                                array(
                                  'toemail' => $setup33_emailsettings_mainemail,
                                      'predefined' => 'recurringprofilecreated',
                                      'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price_ex,'packagename' => $pointfinder_order_listingpname,'nextpayment' => date("Y-m-d", strtotime("+".$pointfinder_order_listingtime." days")),'profileid' => $subscription->id),
                                  )
                                );
                            }else{
                              $this->pointfinder_mailsystem_mailsender(
                                array(
                                  'toemail' => $user_email,
                                      'predefined' => 'paymentcompleted',
                                      'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price_ex,'packagename' => $pointfinder_order_listingpname),
                                  )
                                );

                              $this->pointfinder_mailsystem_mailsender(
                                array(
                                  'toemail' => $setup33_emailsettings_mainemail,
                                      'predefined' => 'newpaymentreceived',
                                      'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price_ex,'packagename' => $pointfinder_order_listingpname),
                                  )
                                );
                            }
                          }else{
                            $this->pointfinder_mailsystem_mailsender(
                              array(
                                'toemail' => $user_email,
                                    'predefined' => 'paymentcompleted',
                                    'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price_ex,'packagename' => $pointfinder_order_listingpname),
                                )
                              );

                            $this->pointfinder_mailsystem_mailsender(
                              array(
                                'toemail' => $setup33_emailsettings_mainemail,
                                    'predefined' => 'newpaymentreceived',
                                    'data' => array('ID' => $item_post_id,'title'=>$mail_item_title,'paymenttotal' => $total_package_price_ex,'packagename' => $pointfinder_order_listingpname),
                                )
                              );
                          }
                        }

                        $msg_output .= esc_html__('Payment is successful.','pointfindercoreelements');
                      }
                    } catch(\Stripe\Error\Card $e) {
                      if(isset($e)){
                        $error_mes = json_decode($e->httpBody,true);
                        $icon_processout = 485;
                        $msg_output = (isset($error_mes['error']['message']))? $error_mes['error']['message']:'';
                        if (empty($msg_output)) {
                          $msg_output .= esc_html__('Payment not completed.','pointfindercoreelements');
                        }
                      }
                    }
                  }else{
                    $msg_output .= esc_html__('Price can not be 0!). Payment process is stopped.','pointfindercoreelements');
                    $icon_processout = 485;
                  }
                   
                  if ($icon_processout != 485) {
                    $overlar_class = ' pfoverlayapprove';
                  }else{
                    $overlar_class = '';
                  }
                break;

                /*Pagseguro*/
                case 'pags':

                  require_once PFCOREELEMENTSDIR. 'includes/PagSeguroLibrary/PagSeguroLibrary.php';
               
                  $pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true)); 
                  $paymentName = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindercoreelements'));

                  if ($pointfinder_sub_order_change == 1 && $otype == 1) {
                    $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_price', true ));
                    $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpname', true));
                  }else{
                    $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
                    $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpname', true)); 
                  }

                  $stp31_up2_pn = $this->PFSAIssetControl('stp31_up2_pn','',esc_html__('Basic Listing Payment','pointfindercoreelements'));
                  $inv_desc_get = ($pointfinder_order_listingpid != 1)?get_the_title($pointfinder_order_listingpid):$stp31_up2_pn;

                  $paymentRequest = new PagSeguroPaymentRequest();
                  $paymentRequest->setCurrency("BRL");
                  $paymentRequest->setReference($result_id.'-'.$otype); 
                  $paymentRequest->addItem($item_post_id, $paymentName.' '.$inv_desc_get , 1, $pointfinder_order_price);
                  $paymentRequest->addParameter('notificationURL', $setup4_membersettings_dashboard_link);

                  try {

                      $credentials = PagSeguroConfig::getAccountCredentials();
                      $url = $paymentRequest->register($credentials);

                      
                      $this->PF_CreatePaymentRecord(
                        array(
                        'user_id' =>  $user_id,
                        'item_post_id'  =>  $item_post_id,
                        'order_post_id' =>  $result_id,
                        'token' =>  $result_id.'-'.$item_post_id.'- PagSeguro',
                        'processname' =>  'SetExpressCheckout',
                        'status'  =>  'success',
                        )
                      );

                      $msg_output .= esc_html__('Payment process started. Please wait redirection.','pointfindercoreelements');
                      $pfreturn_url = $url;

                  } catch (PagSeguroServiceException $e) {

                      
                      $this->PF_CreatePaymentRecord(
                        array(
                        'user_id' =>  $user_id,
                        'item_post_id'  =>  $item_post_id,
                        'order_post_id' =>  $result_id,
                        'token' =>  $result_id.'-'.$item_post_id.'- PagSeguro',
                        'processname' =>  'SetExpressCheckout',
                        'status'  =>  $e->getMessage(),
                        )
                      );

                      $msg_output .= esc_html__( 'Error: Not Success', 'pointfindercoreelements' ).'<br>';
                      $msg_output .= '<small>'.$e->getMessage().'</small><br/>';
                      $icon_processout = 485;
                      $pfreturn_url = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems';
                  }
                break;

                /*Payu Money*/
                case 'payu':

                  $payu_key = $this->PFPGIssetControl('payu_key','','');
                  $payu_salt = $this->PFPGIssetControl('payu_salt','','');

                  if (!empty($payu_key) && !empty($payu_salt)) {

                    if ($pointfinder_sub_order_change == 1 && $otype == 1) {
                      $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_price', true ));
                      $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpname', true));
                    }else{
                      $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
                      $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpname', true)); 
                    }

                    $paymentName = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindercoreelements'));

                    $payu_mode = $this->PFPGIssetControl('payu_mode','',0);
                    if (empty($payu_mode)) {
                      $PAYU_BASE_URL = "https://test.payu.in";
                    }else{
                      $PAYU_BASE_URL = "https://secure.payu.in";
                    }

                    $payu_provider = $this->PFPGIssetControl('payu_provider','',1);
                    if (empty($payu_provider)) {
                      $service_provider = "";
                    }else{
                      $service_provider = "payu_paisa";
                    }
                    
                    /* Generate a transaction ID */
                    $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);

                    update_post_meta($result_id, 'pointfinder_order_txnid', $txnid );

                    /*First name */
                    $firstname = $current_user->user_firstname;
                    if (empty($firstname)) {
                      $firstname = $current_user->user_login;
                    }

                    /*Email*/
                    if (empty($user_email)) {
                      $domain_name = $_SERVER['SERVER_NAME'];
                      $user_email = $current_user->user_login.'@'.$domain_name;
                    }

                    /*Phone*/
                    $user_phone = get_user_meta( $user_id, 'user_phone', true );
                    if(isset($_POST['user_phone']) && $_POST['user_phone']!=''){
                      $user_phone = esc_attr($_POST['user_phone']);
                    }
                    
                    if (empty($user_phone)) {
                      
                        $this->PF_CreatePaymentRecord(
                          array(
                          'user_id' =>  $user_id,
                          'item_post_id'  =>  $item_post_id,
                          'order_post_id' =>  $result_id,
                          'token' =>  $result_id.'-'.$item_post_id.'- PAYUMONEY',
                          'processname' =>  'SetExpressCheckout',
                          'status'  =>  'Failure: Phone '.$user_phone,
                          )
                        );

                        $msg_output .= esc_html__( 'Error: Not Success (Phone)', 'pointfindercoreelements' ).'<br>';
                        $msg_output .= '<small>'.esc_html__( 'Please update your phone from profile page.', 'pointfindercoreelements' ).'</small><br/>';
                        $icon_processout = 485;
                        $pfreturn_url = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems';

                        break;
                    }

                    $productinfo = str_replace(":", "-", $paymentName).' '.$result_id;


                    $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

                    $createOrder = array();

                    $createOrder['key'] = $payu_key;
                    $createOrder['txnid'] = $txnid;
                    $createOrder['amount'] = $pointfinder_order_price;
                    $createOrder['firstname'] = $firstname;
                    $createOrder['email'] = $user_email;
                    $createOrder['phone'] = $user_phone;
                    $createOrder['productinfo'] = $productinfo;
                    $createOrder['surl'] = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&payu=s';
                    $createOrder['furl'] = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&payu=f';
                    $createOrder['service_provider'] = $service_provider;
                    $createOrder['udf1'] = $result_id;
                    $createOrder['udf2'] = $otype;
                    $createOrder['udf3'] = $item_post_id;


                    $hashVarsSeq = explode('|', $hashSequence);
                    $hash_string = '';

                    foreach($hashVarsSeq as $hash_var) {
                        $hash_string .= isset($createOrder[$hash_var]) ? $createOrder[$hash_var] : '';
                        $hash_string .= '|';
                    }

                    $hash_string .= $payu_salt;
                    $hash = strtolower(hash('sha512', $hash_string));
                    
                    $pfreturn_url = $PAYU_BASE_URL . '/_payment';

                    
                    $this->PF_CreatePaymentRecord(
                      array(
                      'user_id' =>  $user_id,
                      'item_post_id'  =>  $item_post_id,
                      'order_post_id' =>  $result_id,
                      'token' =>  $result_id.'-'.$item_post_id.'- PAYUMONEY',
                      'processname' =>  'SetExpressCheckout',
                      'status'  =>  'success',
                      )
                    );
      
                    $msg_output .= esc_html__('Payment process started. Please wait redirection.','pointfindercoreelements');
                  }else{
                    $msg_output .= esc_html__("PAYU: Key or salt empty.",'pointfindercoreelements');
                    $icon_processout = 485;
                  }
                break;


                /*iDeal*/
                case 'ideal':
                  require_once PFCOREELEMENTSDIR . 'includes/Mollie/API/Autoloader.php';
            
                  $pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true)); 

                  $ideal_id = $this->PFPGIssetControl('ideal_id','','');
                  $mollie = new Mollie_API_Client;
                  $mollie->setApiKey($ideal_id);


                  if(isset($_POST['token']) && $_POST['token']!=''){
                    $ideal_issuer = esc_attr($_POST['token']);
                  }else{
                    $ideal_issuer = '';
                  }

                  if ($pointfinder_sub_order_change == 1 && $otype == 1) {
                    $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_price', true ));
                    $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpname', true));
                  }else{
                    $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
                    $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpname', true)); 
                  }

                  $paymentName = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindercoreelements'));

                  $stp31_up2_pn = $this->PFSAIssetControl('stp31_up2_pn','',esc_html__('Basic Listing Payment','pointfindercoreelements'));
                  $inv_desc_get = ($pointfinder_order_listingpid != 1)?get_the_title($pointfinder_order_listingpid):$stp31_up2_pn;
                  
                  try{
                    $payment = $mollie->payments->create(array(
                      "amount"       => $pointfinder_order_price,
                      "method"       => Mollie_API_Object_Method::IDEAL,
                      "description"  => $paymentName." ".$inv_desc_get,
                      "redirectUrl"  => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&il='.$result_id,
                      "webhookUrl"   => $setup4_membersettings_dashboard_link,
                      "metadata"     => array(
                        "order_id" => $result_id,
                        "item_post_id" => $item_post_id,
                        "user_id" => $user_id,
                        "otype" => $otype
                      ),
                      "issuer"       => !empty($ideal_issuer) ? $ideal_issuer : NULL
                    ));

                    update_post_meta($result_id, 'pointfinder_order_ideal', $payment->id );

                    $this->PF_CreatePaymentRecord(
                      array(
                      'user_id' =>  $user_id,
                      'item_post_id'  =>  $item_post_id,
                      'order_post_id' =>  $result_id,
                      'token' =>  $result_id.'-'.$item_post_id.'- iDeal',
                      'processname' =>  'SetExpressCheckout',
                      'status'  =>  'success',
                      )
                    );

                    $msg_output .= esc_html__('Payment process started. Please wait redirection.','pointfindercoreelements');
                    $pfreturn_url = $payment->getPaymentUrl();
                  }catch (Mollie_API_Exception $e){
                    $this->PF_CreatePaymentRecord(
                      array(
                      'user_id' =>  $user_id,
                      'item_post_id'  =>  $item_post_id,
                      'order_post_id' =>  $result_id,
                      'token' =>  $result_id.'-'.$item_post_id.'- iDeal',
                      'processname' =>  'SetExpressCheckout',
                      'status'  =>  $e->getMessage(),
                      )
                    );

                    $msg_output .= esc_html__( 'Error: Not Success', 'pointfindercoreelements' ).'<br>';
                    $msg_output .= '<small>'.htmlspecialchars($e->getMessage()).'</small><br/>';
                    $icon_processout = 485;
                    $pfreturn_url = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&il='.$result_id;
                  }    
                break;


                /*Robokassa*/
                case 'robo':
                  $pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true)); 

                  $robo_mode = $this->PFPGIssetControl('robo_mode','',0);
                  $robo_login = $this->PFPGIssetControl('robo_login','','');
                  $robo_pass1 = $this->PFPGIssetControl('robo_pass1','','');
                  $robo_currency = $this->PFPGIssetControl('robo_currency','','');
                  $robo_lang = $this->PFPGIssetControl('robo_lang','','ru');
                  
                  if ($pointfinder_sub_order_change == 1 && $otype == 1) {
                    $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_price', true ));
                    $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpname', true));
                  }else{
                    $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
                    $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpname', true)); 
                  }

                  $paymentName = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindercoreelements'));

                  $stp31_up2_pn = $this->PFSAIssetControl('stp31_up2_pn','',esc_html__('Basic Listing Payment','pointfindercoreelements'));
                  $inv_desc_get = ($pointfinder_order_listingpid != 1)?get_the_title($pointfinder_order_listingpid):$stp31_up2_pn;
                  $inv_desc = $paymentName." ".$inv_desc_get;

                  $inv_id_random = rand(1000,2147483647);

                  if (!empty($robo_currency)) {
                    $crc  = md5("$robo_login:$pointfinder_order_price:$inv_id_random:$robo_currency:$robo_pass1:Shp_itemnum=$item_post_id:Shp_otype=$otype:Shp_user=$user_id");
                  }else{
                    $crc  = md5("$robo_login:$pointfinder_order_price:$inv_id_random:$robo_pass1:Shp_itemnum=$item_post_id:Shp_otype=$otype:Shp_user=$user_id");
                  }
                  
                  $robo_html = "<form action='https://auth.robokassa.ru/Merchant/Index.aspx' method='POST' name='roboForm'>".
                        "<input type=hidden name='MrchLogin' value='$robo_login'>".
                        "<input type=hidden name='OutSum' value='$pointfinder_order_price'>".
                        "<input type=hidden name='InvId' value='$inv_id_random'>".
                        "<input type=hidden name='Desc' value='$inv_desc'>".
                        "<input type=hidden name='SignatureValue' value='$crc'>".
                        "<input type=hidden name='Shp_itemnum' value='$item_post_id'>".
                        "<input type=hidden name='Shp_user' value='$user_id'>".
                        "<input type=hidden name='Shp_otype' value='$otype'>".
                        "<input type=hidden name='Culture' value='$robo_lang'>";
                        
                        if (!empty($robo_currency)) {
                          $robo_html .= "<input type=hidden name='OutSumCurrency' value='$robo_currency'>";
                        }
                        if ($robo_mode == 0) {
                          $robo_html .= "<input type=hidden name='IsTest' value='1'>";
                        }
                        $robo_html .= "</form>"; 

                  update_post_meta($result_id, 'pointfinder_order_roborinvid', $inv_id_random );
                  update_post_meta($result_id, 'pointfinder_order_roboitemid', $item_post_id );
                  update_post_meta($result_id, 'pointfinder_order_robo', $result_id );
                  update_post_meta($result_id, 'pointfinder_order_robo2', $result_id );

                  $this->PF_CreatePaymentRecord(
                    array(
                    'user_id' =>  $user_id,
                    'item_post_id'  =>  $item_post_id,
                    'order_post_id' =>  $result_id,
                    'token' =>  $result_id.'-'.$item_post_id.' - '.$inv_id_random.' - Robokassa',
                    'processname' =>  'SetExpressCheckout',
                    'status'  =>  'success',
                    )
                  );

                  $msg_output .= esc_html__('Payment process started. Please wait redirection.','pointfindercoreelements');
                  $pfreturn_url = '';
                  $icon_processout = 62;
                break;


                /*Iyzico*/
                case 'iyzico':
                  if ($pointfinder_sub_order_change == 1 && $otype == 1) {
                     $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_price', true ));
                     $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpname', true)); 
                  }else{
                     $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
                     $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpname', true)); 
                  }

                  update_post_meta($result_id,'pointfinder_order_iyzicootype',$otype);

                  $iyzico_installment = $this->PFPGIssetControl('iyzico_installment','','1, 2, 3, 6, 9');
                  $iyzico_installment = (!empty($iyzico_installment))?explode(",", $iyzico_installment):1;
                  $iyzico_key1 = $this->PFPGIssetControl('iyzico_key1','','');
                  $iyzico_key2 = $this->PFPGIssetControl('iyzico_key2','','');
                  $iyzico_mode = $this->PFPGIssetControl('iyzico_mode','','0');
                  $pfreturn_url = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems';

                  $pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true)); 
                  $stp31_up2_pn = $this->PFSAIssetControl('stp31_up2_pn','',esc_html__('Basic Listing Payment','pointfindercoreelements'));
                  $inv_desc_get = ($pointfinder_order_listingpid != 1)?get_the_title($pointfinder_order_listingpid):$stp31_up2_pn;
                  $package_id = !empty($result_id)?$result_id:0;


                  if ($iyzico_mode == 1) {
                    $api_url = 'https://api.iyzipay.com/';
                  }else{
                    $api_url = 'https://sandbox-api.iyzipay.com/';
                  }
                  $usermetaarr = get_user_meta($user_id);
                  $user_address = (isset($usermetaarr['user_address'][0]))?$usermetaarr['user_address'][0]:'';
                  $user_country = (isset($usermetaarr['user_country'][0]))?$usermetaarr['user_country'][0]:'';
                  $user_name = (isset($usermetaarr['first_name'][0]))?$usermetaarr['first_name'][0]:'';
                  $user_surname = (isset($usermetaarr['last_name'][0]))?$usermetaarr['last_name'][0]:'';
                  $user_email = $current_user->user_email;
                  $user_tck = (isset($usermetaarr['user_vatnumber'][0]))?$usermetaarr['user_vatnumber'][0]:'';
                  $user_city = (isset($usermetaarr['user_city'][0]))?$usermetaarr['user_city'][0]:'';
                  $user_phone = (isset($usermetaarr['user_mobile'][0]))?$usermetaarr['user_mobile'][0]:'';

                  $ConversationId = PF_generate_random_string_ig();


                  require_once PFCOREELEMENTSDIR .'includes/IyzipayBootstrap.php'; 

                  IyzipayBootstrap::init();

                  $options = new \Iyzipay\Options();
                  $options->setApiKey($iyzico_key1);
                  $options->setSecretKey($iyzico_key2);
                  $options->setBaseUrl($api_url);

                  $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
                  $request->setLocale(\Iyzipay\Model\Locale::TR);
                  $request->setPrice($pointfinder_order_price);
                  $request->setPaidPrice($pointfinder_order_price);
                  $request->setCurrency(\Iyzipay\Model\Currency::TL);
                  $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::LISTING);
                  $request->setCallbackUrl($pfreturn_url);
                  $request->setEnabledInstallments($iyzico_installment);
                  $request->setConversationId($result_id);

                  $buyer = new \Iyzipay\Model\Buyer();
                  $buyer->setId('PF'.$user_id);
                  $buyer->setName($user_name);
                  $buyer->setSurname($user_surname);
                  $buyer->setEmail($user_email);
                  $buyer->setIdentityNumber($user_tck);
                  $buyer->setGsmNumber($user_phone);
                  $buyer->setRegistrationAddress($user_address);
                  $buyer->setIp($this->pointfinder_getUserIP());
                  $buyer->setCity($user_city);
                  $buyer->setCountry($user_country);
                  $request->setBuyer($buyer);

                  $billingAddress = new \Iyzipay\Model\Address();
                  $billingAddress->setContactName($user_name.' '.$user_surname);
                  $billingAddress->setCity($user_city);
                  $billingAddress->setCountry($user_country);
                  $billingAddress->setAddress($user_address);
                  $request->setBillingAddress($billingAddress);

                  $BasketItem = new \Iyzipay\Model\BasketItem();
                  $BasketItem->setId($package_id);
                  $BasketItem->setName($inv_desc_get.'-'.$item_post_id);
                  $BasketItem->setCategory1("Listing");
                  $BasketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
                  $BasketItem->setPrice($pointfinder_order_price);
                  $basketItems[0] = $BasketItem;
                  $request->setBasketItems($basketItems);

                  $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);
                  
                  update_post_meta($result_id,'pointfinder_order_iyzicotoken',$checkoutFormInitialize->getToken());


                  $iyzico_content = $checkoutFormInitialize->getCheckoutFormContent();
                  $iyzico_status = $checkoutFormInitialize->getStatus();
                  $iyzico_errorMessage = $checkoutFormInitialize->geterrorMessage();

                  if($iyzico_status == 'success'){
                      
                      $this->PF_CreatePaymentRecord(
                        array(
                        'user_id' =>  $user_id,
                        'item_post_id'  =>  $item_post_id,
                        'order_post_id' =>  $result_id,
                        'token' =>  $result_id.'-'.$item_post_id.'- Iyzico',
                        'processname' =>  'SetExpressCheckout',
                        'status'  =>  'success',
                        )
                      );

                      $msg_output .= esc_html__('Payment process started. Please wait...','pointfindercoreelements');
              
                  }else{
                      $this->PF_CreatePaymentRecord(
                        array(
                        'user_id' =>  $user_id,
                        'item_post_id'  =>  $item_post_id,
                        'order_post_id' =>  $result_id,
                        'token' =>  $result_id.'-'.$item_post_id.'- Iyzico',
                        'processname' =>  'SetExpressCheckout',
                        'status'  =>  'fail',
                        )
                      );

                      $msg_output .= sprintf(esc_html__( 'Error: %s', 'pointfindercoreelements' ),$iyzico_errorMessage).'<br>';
                      $msg_output .= '<small>'.$iyzico_errorMessage.'</small><br/>';
                      $icon_processout = 485;
                      $pfreturn_url = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems';
                  }
                break;


                /*2Checkout*/
                case 't2co':
                  $pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true)); 

                  $t2cho_mode = $this->PFPGIssetControl('2cho_mode','',0);
                  $t2cho_currency = $this->PFPGIssetControl('2cho_ccode','','');
                  $t2cho_lang = $this->PFPGIssetControl('2cho_lang','','');
                  $t2cho_uid = $this->PFPGIssetControl('2cho_key3','','');
                  $t2cho_ordpre = $this->PFPGIssetControl('2cho_ordpre','','PINTFNDR');
                  
                  if ($pointfinder_sub_order_change == 1 && $otype == 1) {
                    $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_price', true ));
                    $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpname', true));
                  }else{
                    $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
                    $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpname', true)); 
                  }

                  $paymentName = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindercoreelements'));

                  $stp31_up2_pn = $this->PFSAIssetControl('stp31_up2_pn','',esc_html__('Basic Listing Payment','pointfindercoreelements'));
                  $inv_desc_get = ($pointfinder_order_listingpid != 1)?get_the_title($pointfinder_order_listingpid):$stp31_up2_pn;
                  $inv_desc = $paymentName." ".$inv_desc_get;

                  $inv_id_random = rand(1000,2147483647);

                  if ($t2cho_mode == 0) {
                    $url = "https://sandbox.2checkout.com/checkout/purchase";
                  }else{
                    $url = "https://www.2checkout.com/checkout/purchase";
                  }

                  $usermetaarr = get_user_meta($user_id);
                  if(!isset($usermetaarr['first_name'])){$usermetaarr['first_name'][0] = '';}
                  if(!isset($usermetaarr['last_name'])){$usermetaarr['last_name'][0] = '';}
                  if(!isset($usermetaarr['user_phone'])){$usermetaarr['user_phone'][0] = '';}
                  if(!isset($usermetaarr['user_mobile'])){$usermetaarr['user_mobile'][0] = '';}
                  if(!isset($usermetaarr['user_country'])){$usermetaarr['user_country'][0] = '';}
                  if(!isset($usermetaarr['user_address'])){$usermetaarr['user_address'][0] = '';}
                  if(!isset($usermetaarr['user_city'])){$usermetaarr['user_city'][0] = '';}

                  if (!empty($usermetaarr['user_phone'][0])) {
                    $user_phone = $usermetaarr['user_phone'][0];
                  }else{
                    $user_phone = $usermetaarr['user_mobile'][0];
                  }

                  $robo_html = "<form action='$url' method='POST' name='roboForm'>".
                        "<input type='hidden' name='sid' value='$t2cho_uid'>".
                        "<input type='hidden' name='merchant_order_id' value='".$t2cho_ordpre."".$result_id."'>".
                        "<input type='hidden' name='mode' value='2CO'>".
                        "<input type='hidden' name='li_0_type' value='product'>".
                        "<input type='hidden' name='li_0_name' value='$pointfinder_order_listingpname'>".
                        "<input type='hidden' name='li_0_product_id' value='$pointfinder_order_listingpid'>".
                        "<input type='hidden' name='li_0__description' value='$inv_desc'>".
                        "<input type='hidden' name='li_0_price' value='$pointfinder_order_price'>".
                        "<input type='hidden' name='li_0_quantity' value='1'>".
                        "<input type='hidden' name='li_0_tangible' value='N'>".
                        "<input type='hidden' name='purchase_step' value='billing-information' >".
                        "<input type='hidden' name='custom_invid' value='$inv_id_random' >".
                        "<input type='hidden' name='custom_itempid' value='$item_post_id' >".
                        "<input type='hidden' name='custom_orderpid' value='$result_id' >".
                        "<input type='hidden' name='custom_otype' value='$otype' >".
                        "<input type='hidden' name='custom_sochange' value='$pointfinder_sub_order_change' >".
                        "<input type='hidden' name='custom_uid' value='$user_id' >".
                        "<input type='hidden' name='x_receipt_link_url' value='".$setup4_membersettings_dashboard_link.$pfmenu_perout."ua=myitems' >".
                        "<input type='hidden' name='card_holder_name' value='".$usermetaarr['first_name'][0]." ".$usermetaarr['last_name'][0]."' >".
                        "<input type='hidden' name='email' value='$user_email' >".
                        "<input type='hidden' name='city' value='".$usermetaarr['user_city'][0]."' >".
                        "<input type='hidden' name='state' value='' >".
                        "<input type='hidden' name='country' value='".$usermetaarr['user_country'][0]."' >".
                        "<input type='hidden' name='phone' value='$user_phone' >".
                        "<input type='hidden' name='phone_extension' value='' >".
                        "<input type='hidden' name='street_address' value='".$usermetaarr['user_address'][0]."' >".
                        "<input type='hidden' name='zip' value='' >";

                        if (!empty($t2cho_currency)) {
                          $robo_html .= "<input type='hidden' name='currency_code' value='$t2cho_currency'>";
                        }
                        if (!empty($t2cho_lang)) {
                          $robo_html .= "<input type='hidden' name='lang' value='$t2cho_lang'>";
                        }
                        
                        $robo_html .= "</form>"; 

                  update_post_meta($result_id, 'pointfinder_order_t2co_vendor_order_id', $t2cho_ordpre.$result_id );
                  update_post_meta($result_id, 'pointfinder_order_t2co', $result_id );

                  $this->PF_CreatePaymentRecord(
                    array(
                    'user_id' =>  $user_id,
                    'item_post_id'  =>  $item_post_id,
                    'order_post_id' =>  $result_id,
                    'token' =>  $result_id.'-'.$item_post_id.' - '.$inv_id_random.' - 2Checkout',
                    'processname' =>  'SetExpressCheckout',
                    'status'  =>  'success',
                    )
                  );

                  $msg_output .= esc_html__('Payment process started. Please wait redirection.','pointfindercoreelements');
                  $pfreturn_url = '';
                  $icon_processout = 62;
                break;

                /*PayFast*/
                case 'payf':
                  $pointfinder_order_listingpid = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpid', true)); 

                  $payf_mode = $this->PFPGIssetControl('payf_mode','',0);
                  $payf_merid = $this->PFPGIssetControl('payf_merid','','');
                  $payf_merkey = $this->PFPGIssetControl('payf_merkey','','');
                  $payf_passph = $this->PFPGIssetControl('payf_passph','','');

                  if ($pointfinder_sub_order_change == 1 && $otype == 1) {
                    $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_sub_order_price', true ));
                    $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_sub_order_listingpname', true));
                  }else{
                    $pointfinder_order_price = esc_attr(get_post_meta( $result_id, 'pointfinder_order_price', true ));
                    $pointfinder_order_listingpname = esc_attr(get_post_meta($result_id, 'pointfinder_order_listingpname', true)); 
                  }

                  $paymentName = $this->PFSAIssetControl('setup20_paypalsettings_paypal_api_packagename','',esc_html__('PointFinder Payment:','pointfindercoreelements'));

                  $stp31_up2_pn = $this->PFSAIssetControl('stp31_up2_pn','',esc_html__('Basic Listing Payment','pointfindercoreelements'));
                  $inv_desc_get = ($pointfinder_order_listingpid != 1)?get_the_title($pointfinder_order_listingpid):$stp31_up2_pn;
                  $inv_desc = $paymentName." ".$inv_desc_get;

                  $inv_id_random = rand(1000,2147483647);

                  if ($payf_mode == 0) {
                    $url = "https://sandbox.payfast.co.za";
                  }else{
                    $url = "https://www.payfast.co.za";
                  }

                  $usermetaarr = get_user_meta($user_id);
                  if(!isset($usermetaarr['first_name'])){$usermetaarr['first_name'][0] = '';}
                  if(!isset($usermetaarr['last_name'])){$usermetaarr['last_name'][0] = '';}
                  if(!isset($usermetaarr['user_phone'])){$usermetaarr['user_phone'][0] = '';}
                  if(!isset($usermetaarr['user_mobile'])){$usermetaarr['user_mobile'][0] = '';}
                  if(!isset($usermetaarr['user_country'])){$usermetaarr['user_country'][0] = '';}
                  if(!isset($usermetaarr['user_address'])){$usermetaarr['user_address'][0] = '';}
                  if(!isset($usermetaarr['user_city'])){$usermetaarr['user_city'][0] = '';}

                  if (!empty($usermetaarr['user_phone'][0])) {
                    $user_phone = $usermetaarr['user_phone'][0];
                  }else{
                    $user_phone = $usermetaarr['user_mobile'][0];
                  }

                  if (empty($pointfinder_order_listingpname)) {
                    $pointfinder_order_listingpname = esc_html__('Listing Addon Payment','pointfindercoreelements');
                  }
                  $data = array(
                      'merchant_id' => $payf_merid,
                      'merchant_key' => $payf_merkey,
                      'return_url' => "".$setup4_membersettings_dashboard_link.$pfmenu_perout."ua=myitems&payf=success",
                      'cancel_url' => "".$setup4_membersettings_dashboard_link.$pfmenu_perout."ua=myitems&payf=fail",
                      'notify_url' => "".$setup4_membersettings_dashboard_link."",
                      'name_first' => "".$usermetaarr['first_name'][0]."",
                      'name_last'  => "".$usermetaarr['last_name'][0]."",
                      'email_address'=> "".$user_email."",
                      'm_payment_id' => "PFPAY".$result_id."",
                      'amount' => $pointfinder_order_price,
                      'item_name' => "".$pointfinder_order_listingpname."",
                      'item_description' => "".$inv_desc."",
                      'custom_int1' => "".$inv_id_random."",
                      'custom_int2' => "".$item_post_id."",
                      'custom_int3' => "".$result_id."",
                      'custom_int4' => "".$user_id."",
                      'custom_str1' => "".$otype."",
                      'custom_str2' => "".$pointfinder_sub_order_change.""
                  );        

                  $pfOutput ='';

                  // Create GET string
                  foreach( $data as $key => $val )
                  {
                      if(!empty($val))
                       {
                          $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
                       }
                  }

                  // Remove last ampersand
                  $getString = substr( $pfOutput, 0, -1 );
                  if( isset( $payf_passph ) )
                  {
                      $getString .= '&passphrase='. urlencode( trim( $payf_passph ) );
                  }   
                  $data['signature'] = md5( $getString );

                  

                  $robo_html = "<form action='".$url."/eng/process' method='POST' name='roboForm'>";
                  foreach($data as $name=> $value){ 
                      $robo_html .= '<input name="'.$name.'" type="hidden" value="'.$value.'" />'; 
                  } 
                  $robo_html .= "</form>"; 

                  update_post_meta($result_id, 'pointfinder_order_payf_vendor_order_id', 'PFPAY'.$result_id );
                  update_post_meta($result_id, 'pointfinder_order_payf', $result_id );

                  $this->PF_CreatePaymentRecord(
                    array(
                    'user_id' =>  $user_id,
                    'item_post_id'  =>  $item_post_id,
                    'order_post_id' =>  $result_id,
                    'token' =>  $result_id.'-'.$item_post_id.' - '.$inv_id_random.' - PayFast',
                    'processname' =>  'SetExpressCheckout',
                    'status'  =>  'success',
                    )
                  );

                  $msg_output .= esc_html__('Payment process started. Please wait redirection.','pointfindercoreelements');
                  $pfreturn_url = '';
                  $icon_processout = 62;
                break;
            	}


          }else{
              $msg_output .= esc_html__('Wrong item ID (This is not your item!). Payment process is stopped.','pointfindercoreelements');
              $icon_processout = 485;
          }
          }
        }else{
          $msg_output .= esc_html__('Wrong item ID.','pointfindercoreelements');
          $icon_processout = 485;
        }
      }else{
        $msg_output .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindercoreelements');
        $icon_processout = 485;
      }

      if ($icon_processout == 62) {
        $overlar_class = ' pfoverlayapprove';
      }

      $output_html = '';
      $output_html .= '<div class="golden-forms wrapper mini" style="height:200px">';
      $output_html .= '<div id="pfmdcontainer-overlay" class="pftrwcontainer-overlay">';
      
      $output_html .= "<div class='pf-overlay-close'><i class='fas fa-times-circle'></i></div>";
      $output_html .= "<div class='pfrevoverlaytext".$overlar_class."'><i class='pfadmicon-glyph-".$icon_processout."'></i><span>".$msg_output."</span></div>";
      
      $output_html .= '</div>';
      $output_html .= '</div>';

      if ($icon_processout == 62 && $formtype == 'stripepayment') {
        $output_html_special = '';
        $output_html_special .= apply_filters( 'pointfinderajaxpopupfilterforstripe', $output_html_special, $user_id, $item_post_id );
        $output_html .= $output_html_special;
      }

      if ($icon_processout == 485) {  
        echo json_encode( array( 'process'=>false, 'mes'=>$output_html, 'returnurl' => $pfreturn_url));
      }else{

        if ($formtype != 'payu' && $formtype != 'creditcardstripe' && $formtype != 'stripepayment' && $formtype != 'robo' && $formtype != 't2co' && $formtype != 'iyzico' && $formtype != 'payf') {

          echo json_encode( array( 'process'=>true, 'mes'=>$output_html, 'returnurl' => $pfreturn_url));

        }elseif ($formtype == 'payu'){

          $payumail = '';
          $payumail .= '<form action="'.$pfreturn_url.'" method="post" name="payuForm">
          <input type="hidden" name="hash" value="'.$hash.'"/>
          <input type="hidden" name="key" value="'.$payu_key.'" />
          <input type="hidden" name="txnid" value="'.$txnid.'" />
          <input type="hidden" name="amount" value="'.$pointfinder_order_price.'" />
          <input type="hidden" name="firstname" value="'.$firstname.'" />
          <input type="hidden" name="email" value="'.$user_email.'" />
          <input type="hidden" name="phone" value="'.$user_phone.'" />
          <input type="hidden" name="productinfo" value="'.$productinfo.'" />
          <input type="hidden" name="surl" value="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&payu=s'.'" />
          <input type="hidden" name="furl" value="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&payu=f'.'" />
          <input type="hidden" name="service_provider" value="'.$service_provider.'" size="64" />
          <input type="hidden" name="udf1" value="'.$result_id.'" />
          <input type="hidden" name="udf2" value="'.$otype.'" />
          <input type="hidden" name="udf3" value="'.$item_post_id.'" />
          </form>';

          echo json_encode( array( 'process'=>true, 'mes'=>$output_html, 'returnurl' => $pfreturn_url,'payumail' => $payumail));

        }elseif ($formtype == 'robo' || $formtype == 't2co' || $formtype == 'payf'){

          $output_html .= $robo_html;

          echo json_encode( array( 'process'=>true, 'mes'=>$output_html, 'returnurl' => $pfreturn_url));

        }elseif ($formtype == 'iyzico'){

          echo json_encode( array( 'process'=>true, 'mes'=>$output_html, 'returnurl' => $pfreturn_url,'iyzico_content' => $iyzico_content,'iyzico_status' => $iyzico_status));

        }elseif ($formtype == 'creditcardstripe') {
         
          echo json_encode( array( 'process'=>true, 'otype'=>$otype, 'name'=>$setup20_stripesettings_sitename, 'description'=>$pointfinder_order_listingpname, 'amount' => $total_package_price,'key'=>$setup20_stripesettings_publishkey,'email'=>$user_email,'currency'=>$setup20_stripesettings_currency));

        }elseif ($formtype == 'stripepayment') {
          echo json_encode( array( 'process'=>true, 'mes'=>$output_html, 'returnurl' => $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems'));
        }
        
      }

    die();


  }

  private function pointfinder_getUserIP(){
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP)){
            $ip = $client;
        }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
            $ip = $forward;
        }else{
            $ip = $remote;
        }
        return $ip;
    }
  
}