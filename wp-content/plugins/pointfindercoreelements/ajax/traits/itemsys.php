<?php 
if (class_exists('PointFinderItemSYS')) {
  return;
}


class PointFinderItemSYS extends Pointfindercoreelements_AJAX
{
    use PointFinderCUFunctions;

    public function __construct(){}

    public function pf_ajax_itemsystem(){
      check_ajax_referer( 'pfget_itemsystem', 'security');
    	header('Content-Type: application/json; charset=UTF-8;');

    	/* Get form variables */
      if(isset($_POST['formtype']) && $_POST['formtype']!=''){
        $formtype = $processname = esc_attr($_POST['formtype']);
      }

      /* Get data*/
      $vars = array();
      if(isset($_POST['dt']) && $_POST['dt']!=''){
        if ($formtype == 'delete') {
          $pid = sanitize_text_field($_POST['dt']);
        }else{
          $vars = array();
          parse_str($_POST['dt'], $vars);

          if (is_array($vars)) {
              $vars = $this->PFCleanArrayAttr('PFCleanFilters',$vars);
          } else {
              $vars = esc_attr($vars);
          }
        }
        
      }

      /* WPML Fix */
      $lang_c = '';
      if(isset($_POST['lang']) && $_POST['lang']!=''){
        $lang_c = sanitize_text_field($_POST['lang']);
      }
      if(class_exists('SitePress')) {
        if (!empty($lang_c)) {
          do_action( 'wpml_switch_language', $lang_c );
        }
      }


      $current_user = wp_get_current_user();
      $user_id = $current_user->ID;

      $returnval = $errorval = $pfreturn_url = $msg_output = $overlay_add = $sccval = '';
      $icon_processout = 62;
    	
      $setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');

      if ($formtype == 'delete') {
        /**
        *Start: Delete Item for PPP/Membership
        **/
          if($user_id != 0){

            $delete_postid = (is_numeric($pid))? $pid:'';

            if ($delete_postid != '') {
              $old_status_featured = false;
              $setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');
          
              if ($setup4_membersettings_paymentsystem == 2) {

                /*Check if item user s item*/
                global $wpdb;

                $result = $wpdb->get_results( $wpdb->prepare( 
                  "SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s", 
                  $delete_postid,
                  $user_id,
                  $setup3_pointposttype_pt1
                ) );
                
                if (is_array($result) && count($result)>0) {  
                  if ($result[0]->ID == $delete_postid) {
                    $delete_item_images = get_post_meta($delete_postid, 'webbupointfinder_item_images');
                    if (!empty($delete_item_images)) {
                      foreach ($delete_item_images as $item_image) {
                        wp_delete_attachment(esc_attr($item_image),true);
                      }
                    }
                    wp_delete_attachment(get_post_thumbnail_id( $delete_postid ),true);
                    $old_status_featured = get_post_meta( $delete_postid, 'webbupointfinder_item_featuredmarker', true );
                    wp_delete_post($delete_postid);


                    $membership_user_activeorder = get_user_meta( $user_id, 'membership_user_activeorder', true );
                    /* - Creating record for process system. */
                    $this->PFCreateProcessRecord(
                      array( 
                        'user_id' => $user_id,
                        'item_post_id' => $membership_user_activeorder,
                        'processname' => esc_html__('Item deleted by USER.','pointfindercoreelements'),
                        'membership' => 1
                        )
                    );

                    /* - Create a record for payment system. */
                  
                    $sccval .= esc_html__('Item successfully deleted. Refreshing...','pointfindercoreelements');
                  }

                }else{
                  $icon_processout = 485;
                  $errorval .= esc_html__('Wrong item ID or already deleted. Item can not delete.','pointfindercoreelements');
                }

                /*Membership limits for item /featured limit*/
                
                $membership_user_item_limit = get_user_meta( $user_id, 'membership_user_item_limit', true );
                $membership_user_featureditem_limit = get_user_meta( $user_id, 'membership_user_featureditem_limit', true );
                
                $membership_user_package_id = get_user_meta( $user_id, 'membership_user_package_id', true );
                $packageinfox = $this->pointfinder_membership_package_details_get($membership_user_package_id);

                if ($membership_user_item_limit == -1) {
                  /* Do nothing... */
                }else{

                  if ($membership_user_item_limit >= 0) {
                    $membership_user_item_limit = $membership_user_item_limit + 1;
                    if ($membership_user_item_limit <= $packageinfox['webbupointfinder_mp_itemnumber']) {
                      update_user_meta( $user_id, 'membership_user_item_limit', $membership_user_item_limit);
                    }
                  }
                }


                if($old_status_featured != false && $old_status_featured != 0){

                  $membership_user_featureditem_limit = $membership_user_featureditem_limit + 1;
                  if ($membership_user_featureditem_limit <= $packageinfox['webbupointfinder_mp_fitemnumber']) {
                    update_user_meta( $user_id, 'membership_user_featureditem_limit', $membership_user_featureditem_limit);
                  } 
                }
                  
                
              } else {
                /*Check if item user s item*/
                global $wpdb;

                $result = $wpdb->get_results( $wpdb->prepare( 
                  "SELECT ID, post_author FROM $wpdb->posts WHERE ID = %s and post_author = %s and post_type = %s", 
                  $delete_postid,
                  $user_id,
                  $setup3_pointposttype_pt1
                ) );


                $result_id = $wpdb->get_var( $wpdb->prepare(
                  "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %s", 
                  'pointfinder_order_itemid',
                  $delete_postid
                ) );

                $pointfinder_order_recurring = esc_attr(get_post_meta( $result_id, 'pointfinder_order_recurring', true ));

                if($pointfinder_order_recurring == 1){
                  do_action('pointfinder_recurring_itemremove_actions',array('user_id' => $user_id, 'post_id' => $delete_postid, 'order_id' => $result_id));
                  $pointfinder_order_recurringid = esc_attr(get_post_meta( $result_id, 'pointfinder_order_recurringid', true ));
                  $this->PF_Cancel_recurring_payment(
                   array( 
                          'user_id' => $user_id,
                          'profile_id' => $pointfinder_order_recurringid,
                          'item_post_id' => $delete_postid,
                          'order_post_id' => $result_id,
                      )
                   );
                }

                
                if (is_array($result) && count($result)>0) {  
                  if ($result[0]->ID == $delete_postid) {
                    $delete_item_images = get_post_meta($delete_postid, 'webbupointfinder_item_images');
                    if (!empty($delete_item_images)) {
                      foreach ($delete_item_images as $item_image) {
                        wp_delete_attachment(esc_attr($item_image),true);
                      }
                    }
                    wp_delete_attachment(get_post_thumbnail_id( $delete_postid ),true);
                    wp_delete_post($delete_postid);

                    /* - Creating record for process system. */
                    $this->PFCreateProcessRecord(
                      array( 
                        'user_id' => $user_id,
                        'item_post_id' => $delete_postid,
                        'processname' => esc_html__('Item deleted by USER.','pointfindercoreelements')
                        )
                    );

                    /* - Create a record for payment system. */
                  
                    $sccval .= esc_html__('Item successfully deleted. Refreshing...','pointfindercoreelements');
                  }

                }else{
                  $icon_processout = 485;
                  $errorval .= esc_html__('Wrong item ID (Not your item!). Item can not delete.','pointfindercoreelements');
                }
              }
          
            }else{
              $icon_processout = 485;
              $errorval .= esc_html__('Wrong item ID.','pointfindercoreelements');
            }
          }else{
            $icon_processout = 485;
            $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindercoreelements');
          }

            if (!empty($sccval)) {
              $msg_output .= $sccval;
              $overlay_add = ' pfoverlayapprove';
            }elseif (!empty($errorval)) {
              $msg_output .= $errorval;
            }
        /**
        *End: Delete Item for PPP/Membership
        **/
      } else {
        /**
        *Start: New/Edit Item Form Request
        **/ 

          if(isset($_POST) && $_POST!='' && count($_POST)>0){
              if($user_id != 0){
                if($vars['action'] == 'pfget_edititem'){
                  
                  
                  if (isset($vars['edit_pid']) && !empty($vars['edit_pid'])) {
                    $edit_postid = $vars['edit_pid'];
                    global $wpdb;

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
                        $returnval = $this->PFU_AddorUpdateRecord(
                          array(
                            'post_id' => $edit_postid,
                                'order_post_id' => $this->PFU_GetOrderID($edit_postid,1),
                                'order_title' => $this->PFU_GetOrderID($edit_postid,0),
                            'vars' => $vars,
                            'user_id' => $user_id
                          )
                        );
                      }else{
                        $icon_processout = 485;
                        $errorval .= esc_html__('This is not your item.','pointfindercoreelements');
                      }
                    }else{
                      $icon_processout = 485;
                      $errorval .= esc_html__('Wrong Item ID','pointfindercoreelements');
                    }
                  }else{
                    $icon_processout = 485;
                    $errorval .= esc_html__('There is no item ID to edit.','pointfindercoreelements');
                  }
                }elseif ($vars['action'] == 'pfget_uploaditem') {           
                  $returnval = $this->PFU_AddorUpdateRecord(
                    array(
                      'post_id' => '',
                          'order_post_id' => '',
                          'order_title' => '',
                      'vars' => $vars,
                      'user_id' => $user_id
                    )
                  );   
                }
              }else{
                  $icon_processout = 485;
                  $errorval .= esc_html__('Please login again to upload/edit item (Invalid UserID).','pointfindercoreelements');
              }   
          }

          if (is_array($returnval) && !empty($returnval)) {
            if (isset($returnval['sccval'])) {
              $msg_output .= $returnval['sccval'];
              $overlay_add = ' pfoverlayapprove';
            }elseif (isset($returnval['errorval'])) {
              $msg_output .= $returnval['errorval'];
            }
          }else{
            $msg_output .= $errorval;
          }
        /**
        *End: New/Edit Item Form Request
        **/
      }
      
      

      
      $setup4_membersettings_dashboard = $this->PFSAIssetControl('setup4_membersettings_dashboard','','');
      $setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
      $pfmenu_perout = $this->PFPermalinkCheck();

      $pfreturn_url = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems';

      $output_html = '';
      $output_html .= '<div class="golden-forms wrapper mini" style="height:200px">';
      $output_html .= '<div id="pfmdcontainer-overlay" class="pftrwcontainer-overlay">';
      $output_html .= "<div class='pf-overlay-close'><i class='pfadmicon-glyph-707'></i></div>";
      $output_html .= "<div class='pfrevoverlaytext".$overlay_add."'><i class='pfadmicon-glyph-".$icon_processout."'></i><span>".$msg_output."</span></div>";
      $output_html .= '</div>';
      $output_html .= '</div>';    

      if (!empty($errorval)) {  
        echo json_encode( 
          array( 
            'process'=>false, 
            'processname'=>$processname, 
            'mes'=>$output_html, 
            'returnurl' => $pfreturn_url
            )
          );
      }else{
          echo json_encode( 
            array( 
              'process'=>true, 
              'processname'=>$processname, 
              'returnval'=>$returnval, 
              'mes'=>$output_html, 
              'returnurl' => $pfreturn_url
              )
            );
      }


      die();
    }

    /**
    *Start: Update & Add function for new item
    **/
      
    private function PFU_AddorUpdateRecord($params = array()){

      $defaults = array(
        'post_id' => '',
        'order_post_id' => '',
        'order_title' => '',
        'vars' => array(),
        'user_id' => ''
      );

      $params = array_merge($defaults, $params);


      $setup4_membersettings_dateformat = $this->PFSAIssetControl('setup4_membersettings_dateformat','','1');
      switch ($setup4_membersettings_dateformat) {
        case '1':$datetype = "d/m/Y";break;
        case '2':$datetype = "m/d/Y";break;
        case '3':$datetype = "Y/m/d";break;
        case '4':$datetype = "Y/d/m";break;
      }



      $vars = $params['vars'];


      $user_id = $params['user_id'];
      $returnval = array();
      $returnval['sccval'] = $returnval['errorval'] = $returnval['post_id'] = $returnval['ppps'] = $selectedpayment = $returnval['pppso'] ='';

      $setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
      $setup31_userlimits_userpublish = $this->PFSAIssetControl('setup31_userlimits_userpublish','','0');
      $setup31_userpayments_priceperitem = $this->PFSAIssetControl('setup31_userpayments_priceperitem','','0');
      $setup31_userlimits_userpublishonedit = $this->PFSAIssetControl('setup31_userlimits_userpublishonedit','','0');
      $setup31_userpayments_pricefeatured = $this->PFSAIssetControl('setup31_userpayments_pricefeatured','','0');
      $setup31_userpayments_featuredoffer = $this->PFSAIssetControl('setup31_userpayments_featuredoffer','','0');
      $setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');
      $setup4_membersettings_dashboard = $this->PFSAIssetControl('setup4_membersettings_dashboard','','');
      $setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
      $pfmenu_perout = $this->PFPermalinkCheck();
      $setup4_ppp_catprice = $this->PFSAIssetControl('setup4_ppp_catprice','','0');


      $autoexpire_create = $is_item_recurring = 0;

      /* Selected Payment Method for PPP */
      if (isset($vars['pf_lpacks_payment_selection']) && $setup4_membersettings_paymentsystem == 1) {
        if ($vars['pf_lpacks_payment_selection'] == 'paypal' || $vars['pf_lpacks_payment_selection'] == 'paypal2') {
          $selectedpayment = 'paypal';
        }else{
          $selectedpayment = $vars['pf_lpacks_payment_selection'];
        }
        if ($vars['pf_lpacks_payment_selection'] == 'paypal2') {
          $is_item_recurring = 1;
        }
        if ($vars['pf_lpacks_payment_selection'] == 'iyzico') {
          if(isset($vars['pfusr_firstname'])){update_user_meta($user_id, 'first_name', $vars['pfusr_firstname']);}
          if(isset($vars['pfusr_lastname'])){update_user_meta($user_id, 'last_name', $vars['pfusr_lastname']);}
          if(isset($vars['pfusr_mobile'])){update_user_meta($user_id, 'user_mobile', $vars['pfusr_mobile']);}
          if(isset($vars['pfusr_vatnumber'])){update_user_meta($user_id, 'user_vatnumber', $vars['pfusr_vatnumber']);}
          if(isset($vars['pfusr_country'])){update_user_meta($user_id, 'user_country', $vars['pfusr_country']);}
          if(isset($vars['pfusr_address'])){update_user_meta($user_id, 'user_address', $vars['pfusr_address']);}
          if(isset($vars['pfusr_city'])){update_user_meta($user_id, 'user_city', $vars['pfusr_city']);}
        }
        $returnval['ppps'] = $selectedpayment;
      }


      if($params['post_id'] == ''){
        $userpublish = ($setup31_userlimits_userpublish == 0) ? 'pendingapproval' : 'publish' ;

        if ($setup4_membersettings_paymentsystem == 2) {
          $membership_user_activeorder = get_user_meta( $params['user_id'], 'membership_user_activeorder', true );
          $post_status = $userpublish;
          $checkemail_poststatus = $post_status;
        }else{
          if ($vars['pf_lpacks_payment_selection'] == 'free') {
            $pricestatus = 'publish';
            $autoexpire_create = 1;
          }else{
            $pricestatus = 'pendingpayment';
          }

          if($userpublish == 'publish' && $pricestatus == 'publish'){
            $post_status = 'publish';
          }elseif($userpublish == 'publish' && $pricestatus == 'pendingpayment'){
            $post_status = 'pendingpayment';
          }elseif($userpublish == 'pendingapproval' && $pricestatus == 'publish'){
            $post_status = 'pendingapproval';
          }elseif($userpublish == 'pendingapproval' && $pricestatus == 'pendingpayment'){
            $post_status = 'pendingpayment';
          }

        }

      }else{
        if ($setup4_membersettings_paymentsystem == 2) {
          $membership_user_activeorder = get_user_meta( $params['user_id'], 'membership_user_activeorder', true );
          $post_status = ($setup31_userlimits_userpublishonedit == 0) ? 'pendingapproval' : 'publish' ;
          $checkemail_poststatus = get_post_status( $params['post_id']);
          if($post_status == 'publish'){
            $this->PFCreateProcessRecord(
              array(
                    'user_id' => $user_id,
                    'item_post_id' => $membership_user_activeorder,
                'processname' => esc_html__('Published post edited by USER.','pointfindercoreelements'),
                'membership' => 1
                )
            );
          }else{
            $this->PFCreateProcessRecord(
              array(
                    'user_id' => $user_id,
                    'item_post_id' => $membership_user_activeorder,
                'processname' => esc_html__('Pending Approval post edited by USER.','pointfindercoreelements'),
                'membership' => 1
                )
            );
          }
        }else{
          /**
          *Rules;
          * - If post editing
          * - If post status not pending payment create a post meta item edited.
          *   - If post status pending approval and not approved before. don't create edit record for order meta.
          * - If post status pending payment don't change status and not create record for edit.
          **/
          $checkemail_poststatus = get_post_status( $params['post_id']);
          if($checkemail_poststatus != 'pendingpayment'){
            if($checkemail_poststatus != 'pendingapproval'){
              $post_status = ($setup31_userlimits_userpublishonedit == 0) ? 'pendingapproval' : 'publish' ;
            }else{
              $post_status = 'pendingapproval';
              $this->PFCreateProcessRecord(
                array(
                      'user_id' => $user_id,
                      'item_post_id' => $params['post_id'],
                  'processname' => esc_html__('Pending Approval post edited by USER.','pointfindercoreelements')
                  )
              );
            }

            update_post_meta($params['order_post_id'], 'pointfinder_order_itemedit', 1 );

          }else{
            $post_status = 'pendingpayment';

            /* - Creating record for process system. */
            $this->PFCreateProcessRecord(
              array(
                    'user_id' => $user_id,
                    'item_post_id' => $params['post_id'],
                'processname' => esc_html__('Pending Payment post edited by USER.','pointfindercoreelements')
                )
            );
          }

          if($checkemail_poststatus == 'publish'){
            /* - Creating record for process system. */
            $this->PFCreateProcessRecord(
              array(
                    'user_id' => $user_id,
                    'item_post_id' => $params['post_id'],
                'processname' => esc_html__('Published post edited by USER.','pointfindercoreelements')
                )
            );
          }


          /* New Payment system  with v1.6.4 */
          if ($checkemail_poststatus == 'publish') {

            $pf_changed_value = array();
            $current_category_change = $pf_plan_changed_val = '';
            $pf_category_change = $pf_featured_change = $pf_plan_change = 0;

            /* Detect Featured Change */
            if (!class_exists('Pointfinderspecialreview')) {
              $pf_changed_featured = get_post_meta( $params['post_id'], "webbupointfinder_item_featuredmarker", true );
              if (empty($pf_changed_featured) && !empty($vars['featureditembox'])) {
                $pf_featured_change = 1;
                $pf_changed_value['featured'] = 1;
              }else{
                $pf_featured_change = 0;
                $pf_changed_value['featured'] = 0;
              }
            }else{
              $pf_featured_change = 0;
            }


            /* Detect Category Change if paid category selected */
            if (isset($vars['radio'])) {
              $item_defaultvalue = wp_get_post_terms($params['post_id'], 'pointfinderltypes', array("fields" => "ids"));
              if (isset($item_defaultvalue[0])) {
                $current_category = $this->pf_get_term_top_most_parent($item_defaultvalue[0],'pointfinderltypes');
                $current_category = $current_category['parent'];
              }
              if ($vars['radio'] == $current_category) {
                $pf_category_change = 0;
                $pf_changed_value['category'] = 0;
              }else{
                $pf_category_change = 1;
                $pf_changed_value['category'] = 1;
                $current_category_change = $vars['radio'];
              }
            }else{
              $pf_changed_value['category'] = 0;
              $pf_category_change = 0;
            }



            if (isset($vars['radio'])) {
              $current_category = $vars['radio'];
            }else{
              $item_defaultvalue = wp_get_post_terms($params['post_id'], 'pointfinderltypes', array("fields" => "ids"));
              if (isset($item_defaultvalue[0])) {
                $current_category = $this->pf_get_term_top_most_parent($item_defaultvalue[0],'pointfinderltypes');
                $current_category = $current_category['parent'];
              }
            }

            /* Detect Package Change */
            if (isset($vars['pfpackselector'])) {
              $current_selected_plan = get_post_meta( $params['order_post_id'], 'pointfinder_order_listingpid', true );

              if ($current_selected_plan == $vars['pfpackselector']) {
                $pf_plan_change = 0;
                $pf_changed_value['plan'] = 0;
              }else{
                $pf_plan_change = 1;
                $pf_changed_value['plan'] = 1;
                $pf_plan_changed_val = $vars['pfpackselector'];
              }
            }

            $pack_results = $this->pointfinder_calculate_listingtypeprice($current_category_change,$pf_featured_change,$pf_plan_changed_val);

              $total_pr = $pack_results['total_pr'];
              $cat_price = $pack_results['cat_price'];
              $pack_price = $pack_results['pack_price'];
              $featured_price = $pack_results['featured_price'];
              $total_pr_output = $pack_results['total_pr_output'];
              $featured_pr_output = $pack_results['featured_pr_output'];
              $pack_pr_output = $pack_results['pack_pr_output'];
              $cat_pr_output = $pack_results['cat_pr_output'];
              $pack_title = $pack_results['pack_title'];


              if ($vars['pfpackselector'] == 1) {
                $duration_package = $this->PFSAIssetControl('setup31_userpayments_timeperitem','','');
              }else{
                $duration_package =  get_post_meta( $vars['pfpackselector'], 'webbupointfinder_lp_billing_period', true );
              if (empty($duration_package)) {
                $duration_package = 0;
              }

              if (class_exists('Pointfinderspecialreview')) {
                $webbupointfinder_fe_showhide =  get_post_meta( $vars['pfpackselector'], 'webbupointfinder_fe_showhide', true );
                
                if (empty($webbupointfinder_fe_showhide)) {
                  $webbupointfinder_fe_showhide = 2;
                }

                if ($webbupointfinder_fe_showhide == 1) {
                  update_post_meta( $params['post_id'], "webbupointfinder_item_featuredmarker", 1 );
                }
              }
              };

            /* Create Order Sub Fields */
            update_post_meta($params['order_post_id'], 'pointfinder_sub_order_change', 1);
            update_post_meta($params['order_post_id'], 'pointfinder_sub_order_changedvals', $pf_changed_value);

            update_post_meta($params['order_post_id'], 'pointfinder_sub_order_price', $total_pr);
            update_post_meta($params['order_post_id'], 'pointfinder_sub_order_detailedprice', json_encode(array($pack_title => $total_pr)));
            update_post_meta($params['order_post_id'], 'pointfinder_sub_order_listingtime', $duration_package);
            update_post_meta($params['order_post_id'], 'pointfinder_sub_order_listingpname', $pack_title);
            update_post_meta($params['order_post_id'], 'pointfinder_sub_order_listingpid', $vars['pfpackselector']);
            update_post_meta($params['order_post_id'], 'pointfinder_sub_order_category_price', $cat_price);


            if ($pf_featured_change == 1) {
              update_post_meta($params['order_post_id'], 'pointfinder_sub_order_featured', 1);
            }

            $returnval['pppso'] = 1;

          }elseif ($checkemail_poststatus == 'pendingpayment') {

            if ($vars['pf_lpacks_payment_selection'] == 'free') {
              $pricestatus = 'publish';
              $autoexpire_create = 1;
            }else{
              $pricestatus = 'pendingpayment';
            }

            if ($setup4_ppp_catprice == 1) {
              if (isset($vars['radio'])) {
                $current_category = $vars['radio'];
              }else{
                $item_defaultvalue = wp_get_post_terms($params['post_id'], 'pointfinderltypes', array("fields" => "ids"));
                if (isset($item_defaultvalue[0])) {
                  $current_category = $this->pf_get_term_top_most_parent($item_defaultvalue[0],'pointfinderltypes');
                  $current_category = $current_category['parent'];
                }
              }
            }else{
              $current_category = '';
            }

            if (!class_exists('Pointfinderspecialreview')) {
              if(empty($vars['featureditembox'])){
                $featured_item_box = 0;
                update_post_meta($params['order_post_id'], 'pointfinder_order_featured', 0);
                delete_post_meta($params['order_post_id'], 'pointfinder_order_expiredate_featured');
                update_post_meta($params['post_id'], 'webbupointfinder_item_featuredmarker', 0);
              }else{
                $featured_item_box = 1;
              }
            }else{
              $featured_item_box = 0;
            }

            if (isset($vars['pfpackselector']) && isset($vars['radio'])) {
              if ($featured_item_box == 1 && ($this->pointfinder_get_package_price_ppp($vars['pfpackselector']) != 0 || $this->pointfinder_get_category_price_ppp($vars['radio']) != 0)) {
                update_post_meta($params['order_post_id'], 'pointfinder_order_fremoveback2', 1);
              }
            }


            $pack_results = $this->pointfinder_calculate_listingtypeprice($current_category,$featured_item_box,$vars['pfpackselector']);

              $total_pr = $pack_results['total_pr'];
              $cat_price = $pack_results['cat_price'];
              $pack_price = $pack_results['pack_price'];
              $featured_price = $pack_results['featured_price'];
              $total_pr_output = $pack_results['total_pr_output'];
              $featured_pr_output = $pack_results['featured_pr_output'];
              $pack_pr_output = $pack_results['pack_pr_output'];
              $cat_pr_output = $pack_results['cat_pr_output'];
              $pack_title = $pack_results['pack_title'];

              if ($vars['pfpackselector'] == 1) {
                $duration_package = $this->PFSAIssetControl('setup31_userpayments_timeperitem','','');
              }else{
                $duration_package =  get_post_meta( $vars['pfpackselector'], 'webbupointfinder_lp_billing_period', true );
              if (empty($duration_package)) {
                $duration_package = 0;
              }
              };

            $setup31_userpayments_orderprefix = $this->PFSAIssetControl('setup31_userpayments_orderprefix','','PF');

            $order_post_status = ($total_pr == 0)? 'completed' : 'pendingpayment';

            $arg_order = array(
              'ID' => $params['order_post_id'],
              'post_type'    => 'pointfinderorders',
              'post_status'   => $order_post_status
            );

            $order_post_id = wp_update_post($arg_order);

            $order_recurring = ($is_item_recurring == 1 && $total_pr != 0 ) ? '1' : '0';

            $setup20_paypalsettings_paypal_price_short = $this->PFSAIssetControl('setup20_paypalsettings_paypal_price_short','','');
            $stp31_daysfeatured = $this->PFSAIssetControl('stp31_daysfeatured','','3');

            /* Order Meta */
            update_post_meta($params['order_post_id'], 'pointfinder_order_itemid', $params['post_id']);
            update_post_meta($params['order_post_id'], 'pointfinder_order_userid', $user_id);
            update_post_meta($params['order_post_id'], 'pointfinder_order_recurring', $order_recurring);
            update_post_meta($params['order_post_id'], 'pointfinder_order_price', $total_pr);
            update_post_meta($params['order_post_id'], 'pointfinder_order_detailedprice', json_encode(array($pack_title => $total_pr)));
            update_post_meta($params['order_post_id'], 'pointfinder_order_listingtime', $duration_package);
            update_post_meta($params['order_post_id'], 'pointfinder_order_listingpname', $pack_title);
            update_post_meta($params['order_post_id'], 'pointfinder_order_listingpid', $vars['pfpackselector']);
            update_post_meta($params['order_post_id'], 'pointfinder_order_pricesign', $setup20_paypalsettings_paypal_price_short);
            update_post_meta($params['order_post_id'], 'pointfinder_order_category_price', $cat_price);

            if ($featured_item_box == 1) {
              update_post_meta($params['order_post_id'], 'pointfinder_order_featured', 1);
              update_post_meta($params['order_post_id'], 'pointfinder_order_frecurring', $order_recurring);
            }

            if ($selectedpayment == 'bank') {
              $returnval['pppsru'] = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_pay2&i='.$params['post_id'];
              update_post_meta($params['order_post_id'], 'pointfinder_order_bankcheck', '1');
            }else{
              update_post_meta($params['order_post_id'], 'pointfinder_order_bankcheck', '0');
            }

            /* Start: Add expire date if this item is ready to publish (free listing) */
            if($autoexpire_create == 1){

              $userpublish = ($setup31_userlimits_userpublish == 0) ? 'pendingapproval' : 'publish' ;

              if($userpublish == 'publish' && $pricestatus == 'publish'){
                $post_status = 'publish';
              }elseif($userpublish == 'publish' && $pricestatus == 'pendingpayment'){
                $post_status = 'pendingpayment';
              }elseif($userpublish == 'pendingapproval' && $pricestatus == 'publish'){
                $post_status = 'pendingapproval';
              }elseif($userpublish == 'pendingapproval' && $pricestatus == 'pendingpayment'){
                $post_status = 'pendingpayment';
              }

              wp_update_post(array('ID' => $params['post_id'],'post_status' => $post_status) );

              $exp_date = date("Y-m-d H:i:s", strtotime("+".$duration_package." days"));
              $app_date = date("Y-m-d H:i:s");

              if ($featured_item_box == 1) {
                $exp_date_featured = date("Y-m-d H:i:s", strtotime("+".$stp31_daysfeatured." days"));
                update_post_meta( $params['order_post_id'], 'pointfinder_order_expiredate_featured', $exp_date_featured);
              }

              update_post_meta( $params['order_post_id'], 'pointfinder_order_expiredate', $exp_date);
              update_post_meta( $params['order_post_id'], 'pointfinder_order_datetime_approval', $app_date);
              update_post_meta( $params['order_post_id'], 'pointfinder_order_bankcheck', '0');

              global $wpdb;
              $wpdb->UPDATE($wpdb->posts,array('post_status' => 'completed'),array('ID' => $params['order_post_id']));

              /* - Creating record for process system. */
              $this->PFCreateProcessRecord(
                array(
                      'user_id' => $user_id,
                      'item_post_id' => $params['post_id'],
                  'processname' => esc_html__('Item status changed to Publish by Autosystem (Free Plan)','pointfindercoreelements')
                  )
              );
            }
            /* End: Add expire date if this item is ready to publish (free listing) */

            /* - Creating record for process system. */
            $this->PFCreateProcessRecord(array( 'user_id' => $user_id,'item_post_id' => $params['post_id'],'processname' => esc_html__('An item edited by USER.','pointfindercoreelements')));
          }
        }

      }

      $arg = array(
        'ID'=> $params['post_id'],
        'post_type'    => $setup3_pointposttype_pt1,
        'post_title'    => sanitize_text_field($vars['item_title']),
        'post_content'  => (!empty($vars['item_desc']))?wp_kses_post($vars['item_desc']):'',
        'post_status'   => $post_status,
        'post_author'   => $user_id,
      );

      if ($params['post_id']!='') {
        $update_work = "ok";
        wp_update_post($arg);
        $post_id = $params['post_id'];
        $old_status_featured = get_post_meta( $post_id, 'webbupointfinder_item_featuredmarker', true );
      }else{
        $update_work = "not";
        $post_id = wp_insert_post($arg);
        $old_status_featured = false;
        update_post_meta( $post_id, "webbupointfinder_item_reviewcount", 0);
      }



      if ($setup4_membersettings_paymentsystem == 2) {
        $this->PFCreateProcessRecord(
          array(
                'user_id' => $user_id,
                'item_post_id' => $membership_user_activeorder,
            'processname' => esc_html__('New item uploaded by USER.','pointfindercoreelements'),
            'membership' => 1
            )
        );
      }

      /**
      *Send email to the user;
      * - Check $post_id for edit
      * - Don't send email if direct publish enabled on edit.
      * - Don't send email if edited post status pendingpayment & pendingapproval
      **/
        if ($params['post_id'] != '') {

          if($checkemail_poststatus != 'pendingpayment' && $checkemail_poststatus != 'pendingapproval'){
            if ($setup31_userlimits_userpublishonedit == 0) {
              $user_email_action = 'send';
            }else{
              $user_email_action = 'cancel';
            }
          }else{
            $user_email_action = 'cancel';
          }

        }elseif ($params['post_id'] == '') {
          $user_email_action = 'send';
        }

        if($user_email_action == 'send'){

          if ($post_status == 'publish') {
            $email_subject = 'itemapproved';
          }elseif ($post_status == 'pendingpayment') {
            $email_subject = 'waitingpayment';
          }elseif ($post_status == 'pendingapproval') {
            $email_subject = 'waitingapproval';
          }
          $user_info = get_userdata( $user_id );

          $this->pointfinder_mailsystem_mailsender(
            array(
              'toemail' => $user_info->user_email,
                  'predefined' => $email_subject,
                  'data' => array('ID' => $post_id,'title'=>esc_html($vars['item_title'])),
              )
            );
        }


      /**
      *Send email to the admin;
      * - System will not send email if disabled by PF Mail System
      * - Don't send email if edited post status pendingpayment & pendingapproval
      **/

         $admin_email = get_option( 'admin_email' );
         $setup33_emailsettings_mainemail = $this->PFMSIssetControl('setup33_emailsettings_mainemail','',$admin_email);


         if ($setup33_emailsettings_mainemail != '') {

          if ($params['post_id']!='') {
            $adminemail_subject = 'updateditemsubmission';
            $setup33_emaillimits_adminemailsafteredit = $this->PFMSIssetControl('setup33_emaillimits_adminemailsafteredit','','1');
            if($checkemail_poststatus != 'pendingpayment' && $checkemail_poststatus != 'pendingapproval'){
              if ($setup33_emaillimits_adminemailsafteredit == 1) {
                $admin_email_action = 'send';
              }else{
                $admin_email_action = 'cancel';
              }
            }else{
              $admin_email_action = 'cancel';
            }
          }else{
            $adminemail_subject = 'newitemsubmission';
            $setup33_emaillimits_adminemailsafterupload = $this->PFMSIssetControl('setup33_emaillimits_adminemailsafterupload','','1');
            if ($setup33_emaillimits_adminemailsafterupload == 1) {
              $admin_email_action = 'send';
            }else{
              $admin_email_action = 'cancel';
            }
          }

          if ($admin_email_action == 'send') {

            $this->pointfinder_mailsystem_mailsender(
            array(
              'toemail' => $setup33_emailsettings_mainemail,
                  'predefined' => $adminemail_subject,
                  'data' => array('ID' => $post_id,'title'=>esc_html($vars['item_title'])),
              )
            );
          }
         }

      $returnval['post_id'] = $post_id;

      if (isset($vars['issuer'])) {
        $returnval['issuer'] = $vars['issuer'];
      }


      /** Start: Taxonomies **/

        /*Listing Types*/

          $pftax_terms = '';

          if(isset($vars['pfupload_listingtypes'])){
            if($this->PFControlEmptyArr($vars['pfupload_listingtypes'])){
              $pftax_terms = $vars['pfupload_listingtypes'];
            }else if(!$this->PFControlEmptyArr($vars['pfupload_listingtypes']) && isset($vars['pfupload_listingtypes'])){
              $pftax_terms = $vars['pfupload_listingtypes'];
              if (strpos($pftax_terms, ",") != false) {
                $pftax_terms = $this->pfstring2BasicArray($pftax_terms);
              }else{
                $pftax_terms = array($vars['pfupload_listingtypes']);
              }
            }
          }

          if(!empty($pftax_terms)){
            if ($setup4_membersettings_paymentsystem == 2) {

              wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderltypes');

            }else{

              if ($setup4_ppp_catprice == 1) {

                if ($update_work == "not") {

                  wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderltypes');

                }else{

                  $item_defaultvalue = wp_get_post_terms($post_id, 'pointfinderltypes', array("fields" => "ids"));

                  if (isset($item_defaultvalue[0])) {
                    $current_category = $this->pf_get_term_top_most_parent($item_defaultvalue[0],'pointfinderltypes');
                    $current_category = $current_category['parent'];
                  }

                  if (isset($vars['radio'])) {

                    if ($post_status != "pendingpayment") {
                      $category_price_status = $this->pointfinder_get_category_price_ppp($vars['radio']);

                      if (empty($category_price_status)) {
                        wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderltypes');
                      }else{
                        update_post_meta($params['order_post_id'], 'pointfinder_sub_order_termsmc', $current_category);
                        update_post_meta($params['order_post_id'], 'pointfinder_sub_order_termsms', $vars['radio']);
                        update_post_meta($params['order_post_id'], 'pointfinder_sub_order_terms', $pftax_terms);
                      }

                    }else{

                      wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderltypes');

                    }

                  }else{
                    wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderltypes');
                  }
                }

              }else{
                wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderltypes');
              }

            }
          }


        /*Item Types*/
        if(isset($vars['pfupload_itemtypes'])){
          if($this->PFControlEmptyArr($vars['pfupload_itemtypes'])){
            $pftax_terms = $vars['pfupload_itemtypes'];
          }else if(!$this->PFControlEmptyArr($vars['pfupload_itemtypes']) && isset($vars['pfupload_itemtypes'])){
            $pftax_terms = array($vars['pfupload_itemtypes']);
          }
          wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderitypes');
        }

        /*Conditions*/
        if(isset($vars['pfupload_conditions'])){
          if($this->PFControlEmptyArr($vars['pfupload_conditions'])){
            $pftax_terms = $vars['pfupload_conditions'];
          }else if(!$this->PFControlEmptyArr($vars['pfupload_conditions']) && isset($vars['pfupload_conditions'])){
            $pftax_terms = array($vars['pfupload_conditions']);
          }
          wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderconditions');
        }


        /*Locations Types*/
        if(isset($vars['pfupload_locations'])){

          $stp4_loc_new = $this->PFSAIssetControl('stp4_loc_new','','0');
          $stp4_loc_add = $this->PFSAIssetControl('stp4_loc_add','','0');
          $pftax_terms = '';
          if ($stp4_loc_new == 1 && $stp4_loc_add == 1 && !empty($vars['customlocation'])) {
            $stp4_loc_level = $this->PFSAIssetControl('stp4_loc_level','',3);
            if ($stp4_loc_level == 2) {
              $retunlocation = wp_insert_term( $vars['customlocation'], 'pointfinderlocations', array('parent'=>$vars['pfupload_locations']) );
            }else{
              $retunlocation = wp_insert_term( $vars['customlocation'], 'pointfinderlocations', array('parent'=>$vars['pfupload_sublocations']) );
            }
            if (!is_wp_error( $retunlocation )) {
              $pftax_terms = $retunlocation['term_id'];
            }
            
          }else{
            if($this->PFControlEmptyArr($vars['pfupload_locations'])){
              $pftax_terms = $vars['pfupload_locations'];
            }else if(!$this->PFControlEmptyArr($vars['pfupload_locations']) && isset($vars['pfupload_locations'])){
              $pftax_terms = array($vars['pfupload_locations']);
            }
          }

          if (!empty($pftax_terms)) {
            wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderlocations');
          }
        }


        /*Features Types*/
        if(isset($vars['pffeature'])){
          if($this->PFControlEmptyArr($vars['pffeature'])){
            $pftax_terms = $vars['pffeature'];
          }else if(!$this->PFControlEmptyArr($vars['pffeature']) && isset($vars['pffeature'])){
            $pftax_terms = array($vars['pffeature']);
          }
          
          wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderfeatures');
        }else{
          wp_set_post_terms( $post_id, '', 'pointfinderfeatures');
        }


        /* Post Tags */
        if (isset($vars['posttags'])) {wp_set_post_tags( $post_id, $vars['posttags'], true );}

      /** End: Taxonomies **/



      /** Start: Events **/

        if (isset($vars['field_startdate'])) {
          if (!empty($vars['field_startdate'])) {

            $start_time_hour = 0;
            $start_time_min = 0;

            if (isset($vars['field_starttime'])) {
              if (!empty($vars['field_starttime'])) {
                $start_time = explode(':', $vars['field_starttime']);
                if (isset($start_time[0])) {
                  $start_time_hour = $start_time[0];
                }
                if (isset($start_time[1])) {
                  $start_time_min = $start_time[1];
                }
              }
            }

            $field_startdate = date_parse_from_format($datetype, $vars['field_startdate']);
            $vars['field_startdate'] = strtotime(date("Y-m-d", mktime($start_time_hour, $start_time_min, 0, $field_startdate['month'], $field_startdate['day'], $field_startdate['year'])));

            update_post_meta($post_id, 'webbupointfinder_item_field_startdate', $vars['field_startdate']);
          }else{
            update_post_meta($post_id, 'webbupointfinder_item_field_startdate', '');
          }
        }

        if (isset($vars['field_enddate'])) {
          if (!empty($vars['field_enddate'])) {

            $end_time_hour = 0;
            $end_time_min = 0;

            if (isset($vars['field_endtime'])) {
              if (!empty($vars['field_endtime'])) {
                $end_time = explode(':', $vars['field_endtime']);
                if (isset($end_time[0])) {
                  $end_time_hour = $end_time[0];
                }
                if (isset($end_time[1])) {
                  $end_time_min = $end_time[1];
                }
              }
            }

            $field_enddate = date_parse_from_format($datetype, $vars['field_enddate']);
            $vars['field_enddate'] = strtotime(date("Y-m-d", mktime($end_time_hour, $end_time_min, 0, $field_enddate['month'], $field_enddate['day'], $field_enddate['year'])));

            update_post_meta($post_id, 'webbupointfinder_item_field_enddate', $vars['field_enddate']);
          }else{
            update_post_meta($post_id, 'webbupointfinder_item_field_enddate', '');
          }
        }

        if (isset($vars['field_starttime'])) {
          if (!empty($vars['field_starttime'])) {
            update_post_meta($post_id, 'webbupointfinder_item_field_starttime', $vars['field_starttime']);
          }else{
            update_post_meta($post_id, 'webbupointfinder_item_field_starttime', '');
          }
        }

        if (isset($vars['field_endtime'])) {
          if (!empty($vars['field_endtime'])) {
            update_post_meta($post_id, 'webbupointfinder_item_field_endtime', $vars['field_endtime']);
          }else{
            update_post_meta($post_id, 'webbupointfinder_item_field_endtime', '');
          }
        }

      /** End: Events **/




      /** Start: Opening Hours **/
        $setup3_modulessetup_openinghours = $this->PFSAIssetControl('setup3_modulessetup_openinghours','','0');
        $setup3_modulessetup_openinghours_ex = $this->PFSAIssetControl('setup3_modulessetup_openinghours_ex','','1');

        if($setup3_modulessetup_openinghours == 1 && $setup3_modulessetup_openinghours_ex == 0){

          $i = 1;
          while ( $i <= 7) {
            if(isset($vars['o'.$i])){
              update_post_meta($post_id, 'webbupointfinder_items_o_o'.$i, $vars['o'.$i]);
            }
            $i++;
          }

        }elseif($setup3_modulessetup_openinghours == 1 && $setup3_modulessetup_openinghours_ex == 1){

          $i = 1;
          while ( $i <= 1) {
            if(isset($vars['o'.$i])){
              update_post_meta($post_id, 'webbupointfinder_items_o_o'.$i, $vars['o'.$i]);
            }
            $i++;
          }

        }elseif($setup3_modulessetup_openinghours == 1 && $setup3_modulessetup_openinghours_ex == 2){

          $i = 1;
          while ( $i <= 7) {
            if(isset($vars['o'.$i.'_1']) && isset($vars['o'.$i.'_2'])){
              update_post_meta($post_id, 'webbupointfinder_items_o_o'.$i, $vars['o'.$i.'_1'].'-'.$vars['o'.$i.'_2']);
            }
            $i++;
          }

        }
      /** End: Opening Hours **/


      /** Start: Post Meta **/

        /*Featured*/

          if(!empty($vars['featureditembox']) && $params['post_id'] == ''){
            if($vars['featureditembox'] == 'on'){
              update_post_meta($post_id, 'webbupointfinder_item_featuredmarker', 1);
            }else{
              update_post_meta($post_id, 'webbupointfinder_item_featuredmarker', 0);
            }
          }elseif(empty($vars['featureditembox']) && $params['post_id'] == ''){
            update_post_meta($post_id, 'webbupointfinder_item_featuredmarker', 0);
          }


        /*Location*/
          if(isset($vars['pfupload_lat']) && isset($vars['pfupload_lng'])){
            $check_latlng = true;

            if ($check_latlng) {
              update_post_meta($post_id, 'webbupointfinder_items_location', ''.$vars['pfupload_lat'].','.$vars['pfupload_lng'].'');
            }else{
              update_post_meta($post_id, 'webbupointfinder_items_location', '');
            }
          }

        /*Addrress*/
          if(isset($vars['pfupload_address'])){
            update_post_meta($post_id, 'webbupointfinder_items_address', $vars['pfupload_address']);
          }

        /*Message to Reviewer*/
          if (isset($vars['item_mesrev'])) {
            if ($this->PFcheck_postmeta_exist('webbupointfinder_items_mesrev',$post_id)) {
              $old_mesrev = get_post_meta($post_id, 'webbupointfinder_items_mesrev', true);
              $old_mesrev = json_decode($old_mesrev,true);

              if (is_array($old_mesrev)) {
                $old_mesrev = $this->PFCleanArrayAttr('PFCleanFilters',$old_mesrev);
              }

              $old_mesrev[] = array('message' => $vars['item_mesrev'], 'date' => date("Y-m-d H:i:s"));
              $old_mesrev = json_encode($old_mesrev);

              update_post_meta($post_id, 'webbupointfinder_items_mesrev', $old_mesrev);
            }else{

              $old_mesrev = array();
              $old_mesrev[] = array('message' => $vars['item_mesrev'], 'date' => date("Y-m-d H:i:s"));
              $old_mesrev = json_encode($old_mesrev);

              add_post_meta ($post_id, 'webbupointfinder_items_mesrev', $old_mesrev);
            };
          }

        /** Start: Featured Video **/
          if(isset($vars['pfuploadfeaturedvideo'])){
            update_post_meta($post_id, 'webbupointfinder_item_video', esc_url($vars['pfuploadfeaturedvideo']));
          }
        /** End: Featured Video **/

        /*Custom fields loop*/
          $pfstart = $this->PFCheckStatusofVar('setup1_slides');
          $setup1_slides = $this->PFSAIssetControl('setup1_slides','','');

          if($pfstart == true){

            foreach ($setup1_slides as &$value) {

                  $customfield_statuscheck = $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_frontupload','','0');
                  $available_fields = array(1,2,3,4,5,7,8,9,14,15);

                  if(in_array($value['select'], $available_fields) && $customfield_statuscheck != 0){

              if(isset($vars[''.$value['url'].''])){

                if ($value['select'] == 15 && !empty($vars[''.$value['url'].''])) {
                  $pfvalue = date_parse_from_format($datetype, $vars[''.$value['url'].'']);
                  $vars[''.$value['url'].''] = strtotime(date("Y-m-d", mktime(0, 0, 0, $pfvalue['month'], $pfvalue['day'], $pfvalue['year'])));
                }

                if(!is_array($vars[''.$value['url'].''])){
                  update_post_meta($post_id, 'webbupointfinder_item_'.$value['url'], $vars[''.$value['url'].'']);
                }else{
                  if($this->PFcheck_postmeta_exist('webbupointfinder_item_'.$value['url'],$post_id)){
                    delete_post_meta($post_id, 'webbupointfinder_item_'.$value['url']);
                  };

                  foreach ($vars[''.$value['url'].''] as $val) {
                    add_post_meta ($post_id, 'webbupointfinder_item_'.$value['url'], $val);
                  };

                };
              }else{
                if ($this->PFcheck_postmeta_exist('webbupointfinder_item_'.$value['url'],$post_id)) {
                  delete_post_meta($post_id, 'webbupointfinder_item_'.$value['url']);
                };
              };

                  };

                };
          };

      /** End: Post Meta **/


      /** Streetview Save **/
        if (!empty($vars['webbupointfinder_item_streetview'])) {
          update_post_meta($post_id, 'webbupointfinder_item_streetview', $vars['webbupointfinder_item_streetview']);
        }


      /* Cover Image Upload */
        if (!empty($vars['pfuploadcovimagesrc'])) {

          $uploadimage = $vars['pfuploadcovimagesrc'];
          $upload_img_cid = $uploadimage;
          delete_post_meta( $uploadimage, 'pointfinder_delete_unused');
          $uploadimage = wp_get_attachment_image_src($uploadimage,'full');

          $uploadimage['id'] = $upload_img_cid;

          if (isset($uploadimage[0])) {
            $uploadimage['url'] = $uploadimage[0];
            unset($uploadimage[0]);
          }
          if (isset($uploadimage[1])) {
            $uploadimage['width'] = $uploadimage[1];
            unset($uploadimage[1]);
          }
          if (isset($uploadimage[2])) {
            $uploadimage['height'] = $uploadimage[2];
            unset($uploadimage[2]);
          }

          if ($update_work == "ok") {
            update_post_meta($post_id, 'webbupointfinder_item_headerimage', $uploadimage);
          }else{
            update_post_meta($post_id, 'webbupointfinder_item_headerimage', $uploadimage);
          }


        }

      /*Old Image upload for Backup*/
        $setup4_submitpage_status_old = $this->PFSAIssetControl('setup4_submitpage_status_old','','0');

        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9') !== false || $setup4_submitpage_status_old == 1) {

          if (!empty($vars['pfuploadimagesrc'])) {

            $uploadimages = $this->pfstring2BasicArray($vars['pfuploadimagesrc']);
            $i = 0;
            foreach ($uploadimages as $uploadimage) {
              delete_post_meta( $uploadimage, 'pointfinder_delete_unused');
              $postthumbid = get_post_thumbnail_id($post_id);
              if ($update_work == "ok" && $postthumbid != false) {
                add_post_meta($post_id, 'webbupointfinder_item_images', $uploadimage);
              }else{
                if($i != 0){
                   add_post_meta($post_id, 'webbupointfinder_item_images', $uploadimage);
                }else{
                   set_post_thumbnail( $post_id, $uploadimage );
                }
              }
              $i++;
            }

          }
        }elseif ($setup4_submitpage_status_old == 0){
          if (!empty($vars['pfuploadimagesrc'])) {
            if ($params['order_post_id'] == '') {
              $uploadimages = $this->pfstring2BasicArray($vars['pfuploadimagesrc']);
              $i = 0;
              foreach ($uploadimages as $uploadimage) {
                delete_post_meta( $uploadimage, 'pointfinder_delete_unused');
                if($i != 0){
                   add_post_meta($post_id, 'webbupointfinder_item_images', $uploadimage);
                }else{
                   set_post_thumbnail( $post_id, $uploadimage );
                }
                $i++;
              }
            }
          }
        }

      /*File Upload System*/
        $stp4_fupl = $this->PFSAIssetControl('stp4_fupl','','0');

        if($stp4_fupl == 1) {

          if (!empty($vars['pfuploadfilesrc'])) {

            $uploadfiles = $this->pfstring2BasicArray($vars['pfuploadfilesrc']);
            $i = 0;
            foreach ($uploadfiles as $uploadfile) {
              delete_post_meta( $uploadfile, 'pointfinder_delete_unused');
              add_post_meta($post_id, 'webbupointfinder_item_files', $uploadfile);
              $i++;
            }

          }
        }

      /*Custom Tabs System*/

        if (!empty($vars['webbupointfinder_item_custombox1'])) {
          $vars['webbupointfinder_item_custombox1'] = (!empty($vars['webbupointfinder_item_custombox1']))?wp_kses_post($vars['webbupointfinder_item_custombox1']):'';
          update_post_meta($post_id, 'webbupointfinder_item_custombox1', $vars['webbupointfinder_item_custombox1']);
        }else{
          delete_post_meta( $post_id, 'webbupointfinder_item_custombox1' );
        }


        if (!empty($vars['webbupointfinder_item_custombox2'])) {
          $vars['webbupointfinder_item_custombox2'] = (!empty($vars['webbupointfinder_item_custombox2']))?wp_kses_post($vars['webbupointfinder_item_custombox2']):'';
          update_post_meta($post_id, 'webbupointfinder_item_custombox2', $vars['webbupointfinder_item_custombox2']);
        }else{
          delete_post_meta( $post_id, 'webbupointfinder_item_custombox2' );
        }


        if (!empty($vars['webbupointfinder_item_custombox3'])) {
          $vars['webbupointfinder_item_custombox3'] = (!empty($vars['webbupointfinder_item_custombox3']))?wp_kses_post($vars['webbupointfinder_item_custombox3']):'';
          update_post_meta($post_id, 'webbupointfinder_item_custombox3', $vars['webbupointfinder_item_custombox3']);
        }else{
          delete_post_meta( $post_id, 'webbupointfinder_item_custombox3' );
        }

        if (!empty($vars['webbupointfinder_item_custombox4'])) {
          $vars['webbupointfinder_item_custombox4'] = (!empty($vars['webbupointfinder_item_custombox4']))?wp_kses_post($vars['webbupointfinder_item_custombox4']):'';
          update_post_meta($post_id, 'webbupointfinder_item_custombox4', $vars['webbupointfinder_item_custombox4']);
        }else{
          delete_post_meta( $post_id, 'webbupointfinder_item_custombox4' );
        }

        if (!empty($vars['webbupointfinder_item_custombox5'])) {
          $vars['webbupointfinder_item_custombox5'] = (!empty($vars['webbupointfinder_item_custombox5']))?wp_kses_post($vars['webbupointfinder_item_custombox5']):'';
          update_post_meta($post_id, 'webbupointfinder_item_custombox5', $vars['webbupointfinder_item_custombox5']);
        }else{
          delete_post_meta( $post_id, 'webbupointfinder_item_custombox5' );
        }

        if (!empty($vars['webbupointfinder_item_custombox6'])) {
          $vars['webbupointfinder_item_custombox6'] = (!empty($vars['webbupointfinder_item_custombox6']))?wp_kses_post($vars['webbupointfinder_item_custombox6']):'';
          update_post_meta($post_id, 'webbupointfinder_item_custombox6', $vars['webbupointfinder_item_custombox6']);
        }else{
          delete_post_meta( $post_id, 'webbupointfinder_item_custombox6' );
        }



      if ($setup4_membersettings_paymentsystem == 2) {
        /* - Creating record for process system. */
        $this->PFCreateProcessRecord(array( 'user_id' => $user_id,'item_post_id' => $membership_user_activeorder,'processname' => esc_html__('A new item uploaded by USER.','pointfindercoreelements'),'membership' => 1));
      }else{
        /** Orders: Post Info **/
        if ($params['order_post_id'] == '' && $params['post_id'] == '') {

          /* New order system
          $vars['pfpackselector'];//2461 paket
          $vars['featureditembox'];//on
          $vars['radio'];// pf listing type
          $vars['pf_lpacks_payment_selection'];//payment selector
          */
          if (!class_exists('Pointfinderspecialreview')) {
            if(empty($vars['featureditembox'])){
              $featured_item_box = 0;
            }else{
              $featured_item_box = 1;
            }
          }else{
            $featured_item_box = 0;
          }


          $pack_results = $this->pointfinder_calculate_listingtypeprice($vars['radio'],$featured_item_box,$vars['pfpackselector']);

            $total_pr = $pack_results['total_pr'];
            $cat_price = $pack_results['cat_price'];
            $pack_price = $pack_results['pack_price'];
            $featured_price = $pack_results['featured_price'];
            $total_pr_output = $pack_results['total_pr_output'];
            $featured_pr_output = $pack_results['featured_pr_output'];
            $pack_pr_output = $pack_results['pack_pr_output'];
            $cat_pr_output = $pack_results['cat_pr_output'];
            $pack_title = $pack_results['pack_title'];

            if ($vars['pfpackselector'] == 1) {
              $duration_package = $this->PFSAIssetControl('setup31_userpayments_timeperitem','','');
            }else{
              $duration_package =  get_post_meta( $vars['pfpackselector'], 'webbupointfinder_lp_billing_period', true );
            if (empty($duration_package)) {
              $duration_package = 0;
            }

            if (class_exists('Pointfinderspecialreview')) {
              $webbupointfinder_fe_showhide =  get_post_meta( $vars['pfpackselector'], 'webbupointfinder_fe_showhide', true );
              
              if (empty($webbupointfinder_fe_showhide)) {
                $webbupointfinder_fe_showhide = 2;
              }

              if ($webbupointfinder_fe_showhide == 1) {
                update_post_meta( $post_id, "webbupointfinder_item_featuredmarker", 1 );
              }
            }
            };

          srand($this->pfmake_seed());

          $setup31_userpayments_orderprefix = $this->PFSAIssetControl('setup31_userpayments_orderprefix','','PF');

          $order_post_title = ($params['order_title'] != '') ? $params['order_title'] : $setup31_userpayments_orderprefix.rand();
          $order_post_status = ($total_pr == 0)? 'completed' : 'pendingpayment';

          $arg_order = array(
            'post_type'    => 'pointfinderorders',
            'post_title'  => $order_post_title,
            'post_status'   => $order_post_status,
            'post_author'   => $user_id,
          );

          $order_post_id = wp_insert_post($arg_order);

          $order_recurring = ($is_item_recurring == 1 && $total_pr != 0 ) ? '1' : '0';

          $setup20_paypalsettings_paypal_price_short = $this->PFSAIssetControl('setup20_paypalsettings_paypal_price_short','','');
          $stp31_daysfeatured = $this->PFSAIssetControl('stp31_daysfeatured','','3');

          /* Order Meta */
          add_post_meta($order_post_id, 'pointfinder_order_itemid', $post_id, true );
          add_post_meta($order_post_id, 'pointfinder_order_userid', $user_id, true );
          add_post_meta($order_post_id, 'pointfinder_order_recurring', $order_recurring, true );
          add_post_meta($order_post_id, 'pointfinder_order_price', $total_pr, true );
          add_post_meta($order_post_id, 'pointfinder_order_detailedprice', json_encode(array($pack_title => $total_pr)), true );
          add_post_meta($order_post_id, 'pointfinder_order_listingtime', $duration_package, true );
          add_post_meta($order_post_id, 'pointfinder_order_listingpname', $pack_title, true );
          add_post_meta($order_post_id, 'pointfinder_order_listingpid', $vars['pfpackselector'], true );
          add_post_meta($order_post_id, 'pointfinder_order_pricesign', $setup20_paypalsettings_paypal_price_short, true );
          add_post_meta($order_post_id, 'pointfinder_order_category_price', $cat_price);

          if ($featured_item_box == 1) {
            add_post_meta($order_post_id, 'pointfinder_order_featured', 1);
            add_post_meta($order_post_id, 'pointfinder_order_frecurring', $order_recurring, true );
          }

          if ($selectedpayment == 'bank') {
            $returnval['pppsru'] = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems&action=pf_pay2&i='.$post_id;
            add_post_meta($order_post_id, 'pointfinder_order_bankcheck', '1');
          }else{
            add_post_meta($order_post_id, 'pointfinder_order_bankcheck', '0');
          }

          if (isset($vars['pfpackselector'])) {
            if ($featured_item_box == 1 && $this->pointfinder_get_package_price_ppp($vars['pfpackselector']) == 0) {
              update_post_meta($order_post_id, 'pointfinder_order_fremoveback', 1);
            }
          }

          if (isset($vars['pfpackselector']) && isset($vars['radio'])) {
            if ($featured_item_box == 1 && ($this->pointfinder_get_package_price_ppp($vars['pfpackselector']) != 0 || $this->pointfinder_get_category_price_ppp($vars['radio']) != 0)) {
              update_post_meta($order_post_id, 'pointfinder_order_fremoveback2', 1);
            }
          }



          /* Start: Add expire date if this item is ready to publish (free listing) */
          if($autoexpire_create == 1){

            $exp_date = date("Y-m-d H:i:s", strtotime("+".$duration_package." days"));
            $app_date = date("Y-m-d H:i:s");

            if ($featured_item_box == 1) {
              $exp_date_featured = date("Y-m-d H:i:s", strtotime("+".$stp31_daysfeatured." days"));
              update_post_meta( $order_post_id, 'pointfinder_order_expiredate_featured', $exp_date_featured);
            }

            update_post_meta( $order_post_id, 'pointfinder_order_expiredate', $exp_date);
            update_post_meta( $order_post_id, 'pointfinder_order_datetime_approval', $app_date);

            if ($this->PFcheck_postmeta_exist('pointfinder_order_bankcheck',$order_post_id)) {
              update_post_meta($order_post_id, 'pointfinder_order_bankcheck', '0');
            };

            global $wpdb;
            $wpdb->UPDATE($wpdb->posts,array('post_status' => 'completed'),array('ID' => $order_post_id));

            /* - Creating record for process system. */
            $this->PFCreateProcessRecord(
              array(
                    'user_id' => $user_id,
                    'item_post_id' => $post_id,
                'processname' => esc_html__('Item status changed to Publish by Autosystem','pointfindercoreelements')
                )
            );
          }
          /* End: Add expire date if this item is ready to publish (free listing) */

          /* - Creating record for process system. */
          $this->PFCreateProcessRecord(array( 'user_id' => $user_id,'item_post_id' => $post_id,'processname' => esc_html__('A new item uploaded by USER.','pointfindercoreelements')));
        }
        /** Orders: Post Info **/
      }


      if ($params['post_id'] == '') {
        $returnval['sccval'] = sprintf(esc_html__('New item successfully added. %s You are redirecting to my items page...','pointfindercoreelements'),'<br/>');
      }else{
        $returnval['sccval'] = sprintf(esc_html__('Your item successfully updated. %s You are redirecting to my items page...','pointfindercoreelements'),'<br/>');
      }

      /*Membership limits for item /featured limit*/
      if ($setup4_membersettings_paymentsystem == 2) {

          $membership_user_item_limit = get_user_meta( $user_id, 'membership_user_item_limit', true );
          $membership_user_featureditem_limit = get_user_meta( $user_id, 'membership_user_featureditem_limit', true );

          if (!empty($membership_user_item_limit)){
            if ($update_work == "not") {
              $membership_user_item_limit = $membership_user_item_limit - 1;
              update_user_meta( $user_id, 'membership_user_item_limit', $membership_user_item_limit);
            }
          }


          if(!empty($vars['featureditembox'])){

            if($vars['featureditembox'] == 'on' && $update_work == "not"){

              $membership_user_featureditem_limit = $membership_user_featureditem_limit - 1;
              update_user_meta( $user_id, 'membership_user_featureditem_limit', $membership_user_featureditem_limit);

            }elseif ($vars['featureditembox'] == 'on' && $update_work == "ok") {

              if (empty($old_status_featured) && $membership_user_featureditem_limit > 0) {
                $membership_user_featureditem_limit = $membership_user_featureditem_limit - 1;
                update_post_meta( $post_id, 'webbupointfinder_item_featuredmarker', 1);
                update_user_meta( $user_id, 'membership_user_featureditem_limit', $membership_user_featureditem_limit);
              }elseif (empty($old_status_featured) && $membership_user_featureditem_limit <= 0) {
                update_post_meta( $post_id, 'webbupointfinder_item_featuredmarker', 0);
              }

            }
          }else{
            if ($old_status_featured != false && $old_status_featured != 0) {
              update_post_meta($post_id, 'webbupointfinder_item_featuredmarker', 0);
              $membership_user_featureditem_limit = $membership_user_featureditem_limit + 1;
              update_user_meta( $user_id, 'membership_user_featureditem_limit', $membership_user_featureditem_limit);
            }
          }
      }

      return $returnval;
    }
  /**
  *End: Update & Add function for new item
  **/
  private function PFcheck_postmeta_exist( $meta_key, $post_id) {
    global $wpdb;

    $meta_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM $wpdb->postmeta where meta_key like %s and post_id = %d",$meta_key,$post_id) );

    if ($meta_count > 0){
      return true;
    }else{
      return false;
    }
  }

  private function pointfinder_get_category_price_ppp($category){
    
    $cat_price = 0;

    if (!empty($category)) {
      $cat_extra_opts = get_option('pointfinderltypes_covars');
      if (!empty($cat_extra_opts)) {
        $cat_price = (isset($cat_extra_opts[$category]['pf_categoryprice']))?$cat_extra_opts[$category]['pf_categoryprice']:0;
      }
    }

    return $cat_price;
  }
  
}