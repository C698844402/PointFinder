<?php

if (trait_exists('PointFinderListingBackendFilters')) {
  return;
}

/**
 * Listing Filters
 */
trait PointFinderListingBackendFilters
{
  
  /**
  *Start: Invoices Filters
  **/
    public function pf_invoices_item_filter() {
          global $typenow;
          if ($typenow == 'pointfinderinvoices' ) {
              echo '<input type="text" name="invoicenum" value="" placeholder="'.esc_html__('Invoice Number','pointfindercoreelements').'" />';
          }
      }

    public function pf_invoices_item_filter_query($query) {
          global $pagenow;
          global $typenow;
          if ($pagenow=='edit.php' && $typenow == 'pointfinderinvoices' && isset($_GET['invoicenum'])) {

            $inv_prefix = $this->PFASSIssetControl('setup_invoices_prefix','','PFI');
            $invoicenum = str_replace($inv_prefix, "", $_GET['invoicenum']);

              $query->query_vars['p'] = sanitize_text_field($invoicenum );
          }
          return $query;
      }

    public function pointfinder_edit_invoices_columns( $columns ) {
      $columns = array(
              'cb' => '<input type="checkbox" />',
              'invid' => esc_html__( 'ID','pointfindercoreelements' ),
              'itype' => esc_html__( 'Process','pointfindercoreelements' ),
              'ititle' => esc_html__( 'Desc','pointfindercoreelements' ),
              'istatus' => esc_html__( 'Status','pointfindercoreelements' ),
              'userinfo' => esc_html__( 'User','pointfindercoreelements' ),

              'date' => esc_html__( 'Date','pointfindercoreelements' ),
          );
        return $columns;
    }



    public function pointfinder_manage_invoices_columns( $column, $post_id ) {
        global $post;

        $setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');
        $user_login = get_the_author_meta('user_login');
      $user = get_user_by( 'login', $user_login );

        switch( $column ) {

          case 'invid':
            $inv_prefix = $this->PFASSIssetControl('setup_invoices_prefix','','PFI');
            echo '<strong><a href="'.admin_url('post.php?post='.get_the_id().'&action=edit').'">'.$inv_prefix.get_the_id().'</a> - <a href="'.get_permalink().'" target="_blank">'.esc_html__("View","pointfindercoreelements").'</a></strong>';
            break;

            case 'istatus' :

                $value2 = '';

                $value2 = get_post_status( $post_id );
                $value2_output = '';
                if($value2 == 'publish'){
                    $value2_output = '<span style="color:green;font-weight:bold;">'.esc_html__( 'Published', 'pointfindercoreelements' ).'</span>';
                }elseif ($value2 == 'pendingpayment') {
                    $value2_output = '<span style="color:red;font-weight:bold;">'.esc_html__( 'Pending Payment', 'pointfindercoreelements' ).'</span>';
                }
                echo $value2_output;
                break;

            case 'userinfo':
              if (isset($user->ID) && isset($user->nickname)) {
                echo '<a href="'.get_edit_user_link($user->ID).'" target="_blank" title="'.esc_html__('Click for user details','pointfindercoreelements').'">'.$user->ID.' - '.$user->nickname.'</a>';
              }else{
                echo 'User Removed';
              }

                break;

            case 'itype':
              echo '<strong>'.get_post_meta( $post_id,'pointfinder_invoice_invoicetype', true ).'</strong>';
                break;

            case 'ititle':
              echo '<strong>'.get_the_title().'</strong>';
                break;
        }
    }
  /**
  *End: Invoices Filters
  **/


  /**
  *Start: Reviews Item Filter
  **/
      public function pf_reviews_item_filter() {
          global $typenow;
          if ($typenow == 'pointfinderreviews' ) {
              echo '<input type="text" name="itemnumber" value="" placeholder="'.esc_html__('Item Number','pointfindercoreelements').'" />';
          }
      }

      public function pf_reviews_item_filter_query($query) {
          global $pagenow;
          global $typenow;
          if ($pagenow=='edit.php' && $typenow == 'pointfinderreviews' && isset($_GET['itemnumber'])) {
              $query->query_vars['meta_key'] = 'webbupointfinder_review_itemid';
              $query->query_vars['meta_value'] = $_GET['itemnumber'];
          }
          return $query;
      }

      public function pf_clear_flagged_review(){
        global $post,$post_type,$pagenow;

        if($post_type == 'pointfinderreviews' && $pagenow == 'post.php' && isset($_GET['flag'])){
          if ($_GET['flag'] == 0) {
            update_post_meta($post->ID,'webbupointfinder_review_flag',0);
          }
        }
      }

      public function pointfinder_edit_reviews_columns( $columns ) {

              $columns = array(
                  'cb' => '<input type="checkbox" />',
                  'title' => esc_html__( 'Title','pointfindercoreelements' ),
                  'istatus' => esc_html__( 'Status','pointfindercoreelements' ),
                  'itemname' => esc_html__( 'Item','pointfindercoreelements' ),
                  'stars' => esc_html__( 'Rating','pointfindercoreelements' ),
                  'date' => esc_html__( 'Date','pointfindercoreelements' ),
              );


          return $columns;
      }

      public function pointfinder_reviews_sortable_columns( $columns ) {

          $columns['stars'] = 'stars';

          return $columns;
      }

      public function pointfinder_manage_reviews_columns( $column, $post_id ) {
          global $post;

          switch( $column ) {

              case 'title1' :
            echo '<a href="post.php?post='.$post_id.'&action=edit" style="font-weight:bold">'.get_the_title( $post_id ).'</a>';
                  break;

              case 'istatus' :

                  $value2 = '';

                  $value2 = get_post_status( $post_id );

                  switch ($value2) {
                    case 'publish':
                      $value2_text = '<span style="color:green">'.esc_html__( 'Published', 'pointfindercoreelements' ).'</span>';
                      break;
                    case 'pendingapproval':
                      $value2_text = '<span style="color:orange">'.esc_html__( 'Pending Approval', 'pointfindercoreelements' ).'</span>';
                      break;
                    case 'pendingpayment':
                      $value2_text = '<span style="color:red">'.esc_html__( 'Pending Payment', 'pointfindercoreelements' ).'</span>';
                      break;
                    case 'rejected':
                      $value2_text = '<span style="color:red">'.esc_html__( 'Published', 'pointfindercoreelements' ).'</span>';
                      break;

                    default:
                      $value2_text = '<span style="color:green">'.esc_html__( 'Rejected', 'pointfindercoreelements' ).'</span>';
                      break;
                  }
                  echo $value2_text;
                  break;

              case 'itemname':

                  $item_id = esc_attr(get_post_meta( $post_id, 'webbupointfinder_review_itemid', true ));
                  if(!empty($item_id)){
                      echo '<a href="'.get_permalink($item_id).'" target="_blank">'.get_the_title($item_id).'('.$item_id.')</a>';
                  }
                  break;

              case 'stars':
                  $total = $this->pfcalculate_single_review($post_id);

                  if (empty($total)) {
                      echo '0';
                  }else{
                      echo esc_html($total);
                  }
                  break;

          }
      }
  /**
  *End: Reviews Item Filter
  **/

  /**
  *Start: Order List Filters
  **/
    public function pf_orders_item_filter() {

        // only display these taxonomy filters on desired custom post_type listings
        global $typenow;
        if ($typenow == 'pointfinderorders' ) {
            echo '<input type="text" name="itemnumber" value="" placeholder="'.esc_html__('Item Number','pointfindercoreelements').'" />';
        }
    }

    public function pf_orders_item_filter_query($query) {
          global $pagenow;
          global $typenow;
          if ($pagenow=='edit.php' && $typenow == 'pointfinderorders' && isset($_GET['itemnumber'])) {
              $query->query_vars['meta_key'] = 'pointfinder_order_itemid';
              $query->query_vars['meta_value'] = $_GET['itemnumber'];
          }
          return $query;
      }

    public function pointfinder_edit_orders_columns( $columns ) {

            $columns = array(
                'cb' => '<input type="checkbox" />',
                'title' => esc_html__( 'Title','pointfindercoreelements' ),
                'istatus' => esc_html__( 'Status','pointfindercoreelements' ),
                'itemname' => esc_html__( 'Item','pointfindercoreelements' ),
                'price' => esc_html__( 'Total','pointfindercoreelements' ),
                'itime' => esc_html__( 'Time','pointfindercoreelements' ),
                'itype' => esc_html__( 'Type','pointfindercoreelements' ),
                'date' => esc_html__( 'Create Date','pointfindercoreelements' ),
                'idate' => esc_html__( 'Expire Date','pointfindercoreelements' ),
            );


        return $columns;
    }


    public function pointfinder_manage_orders_columns( $column, $post_id ) {
        global $post;

        switch( $column ) {


            case 'istatus' :

                $value2 = '';

                $value2 = get_post_status( $post_id );

                if($value2 == 'publish'){
                    $value2 = '<span style="color:green">'.esc_html__( 'Published', 'pointfindercoreelements' ).'</span>';
                }elseif($value2 == 'pendingapproval'){
                    $value2 = '<span style="color:red">'.esc_html__( 'Pending Approval', 'pointfindercoreelements' ).'</span>';
                }elseif ($value2 == 'pendingpayment') {
                    $value2 = '<span style="color:red">'.esc_html__( 'Pending Payment', 'pointfindercoreelements' ).'</span>';
                }elseif ($value2 == 'pfsuspended') {
                    $value2 = '<span style="color:red">'.esc_html__( 'Suspended', 'pointfindercoreelements' ).'</span>';
                }elseif ($value2 == 'completed') {
                    $value2 = '<span style="color:green">'.esc_html__( 'Completed', 'pointfindercoreelements' ).'</span>';
                }elseif ($value2 == 'pfcancelled') {
                    $value2 = '<span style="color:red">'.esc_html__( 'Cancelled', 'pointfindercoreelements' ).'</span>';
                }
                echo $value2;
                break;

            case 'itemname':

                $prderinfo_itemid = esc_attr(get_post_meta( $post_id , 'pointfinder_order_itemid', true ));

                if(!empty($prderinfo_itemid)){
                    //echo '<a href="'.get_permalink($item_id).'" target="_blank">'.get_the_title($item_id).'('.$item_id.')</a>';
                    echo '<a href="'.get_edit_post_link($prderinfo_itemid).'" target="_blank"><strong>'.get_the_title($prderinfo_itemid).'('.$prderinfo_itemid.')</strong></a>';
                }
                break;

            case 'price':
                echo esc_attr(get_post_meta( $post_id, 'pointfinder_order_price', true ));
                echo esc_attr(get_post_meta( $post_id, 'pointfinder_order_pricesign', true ));
                break;


            case 'itime':
                echo esc_attr(get_post_meta( $post_id, 'pointfinder_order_listingtime', true )).' '.esc_html__('Days','pointfindercoreelements');
                break;


            case 'itype':
              $nameofb = esc_attr(get_post_meta( $post_id, 'pointfinder_order_listingpname', true ));

                if(esc_attr(get_post_meta( $post_id, 'pointfinder_order_recurring', true )) == 1){
                  echo esc_html__('Recurring','pointfindercoreelements').' : '.$nameofb;
                }else{
                  echo esc_html__('Direct','pointfindercoreelements').' : '.$nameofb;
                }
                break;


            case 'idate':
                echo esc_attr(get_post_meta( $post_id, 'pointfinder_order_expiredate', true ));
                break;

        }
    }
  /**
  *End: Order List Filters
  **/

  /**
  *Start: Order Membership List Filters
  **/
    public function pf_morders_item_filter() {

          // only display these taxonomy filters on desired custom post_type listings
          global $typenow;
          if ($typenow == 'pointfindermorders' ) {
              echo '<input type="text" name="usernumber" value="" placeholder="'.esc_html__('User ID','pointfindercoreelements').'" />';
              echo '<input type="text" name="itemtitle" value="" placeholder="'.esc_html__('Order ID (Title)','pointfindercoreelements').'" />';
          }
      }

    public function pf_morders_item_filter_query($query) {
          global $pagenow;
          global $typenow;
          if ($pagenow=='edit.php' && $typenow == 'pointfindermorders' && isset($_GET['usernumber'])) {
              $query->query_vars['meta_key'] = 'pointfinder_order_userid';
              $query->query_vars['meta_value'] = $_GET['usernumber'];
          }
          if ($pagenow=='edit.php' && $typenow == 'pointfindermorders' && isset($_GET['itemtitle'])) {
              $query->query_vars['search_prod_titlex'] = $_GET['itemtitle'];
              if (!function_exists('pointfinder_orders_titlexe_filter')) {
                function pointfinder_orders_titlexe_filter( $where, &$wp_query )
          {
            global $wpdb;
            if ( $search_term = $wp_query->get( 'search_prod_titlex' ) ) {
              if($search_term != ''){
                $search_term = $wpdb->esc_like( $search_term );
                $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql(  $search_term ) . '%\' and post_type = "pointfindermorders"';
              }
            }
            return $where;
          }
              }

          add_filter( 'posts_where', 'pointfinder_orders_titlexe_filter', 10, 2 );
          wp_reset_postdata();
          }
          return $query;
      }

    public function pointfinder_edit_morders_columns( $columns ) {
        $columns = array(
              'cb' => '<input type="checkbox" />',
              'title' => esc_html__( 'Title','pointfindercoreelements' ),
              'istatus' => esc_html__( 'Status','pointfindercoreelements' ),
              'packageinfo' => esc_html__( 'Package','pointfindercoreelements' ),
              'userinfo' => esc_html__( 'User','pointfindercoreelements' ),
              'itype' => esc_html__( 'Type','pointfindercoreelements' ),
              'date' => esc_html__( 'Create Date','pointfindercoreelements' ),
              'idate' => esc_html__( 'Expire Date','pointfindercoreelements' ),
          );
        return $columns;
    }


    public function pointfinder_manage_morders_columns( $column, $post_id ) {
        global $post;

        switch( $column ) {

            case 'istatus' :

                $value2 = '';

                $value2 = get_post_status( $post_id );

                if($value2 == 'publish'){
                    $value2 = '<span style="color:green">'.esc_html__( 'Published', 'pointfindercoreelements' ).'</span>';
                }elseif($value2 == 'pendingapproval'){
                    $value2 = '<span style="color:red">'.esc_html__( 'Pending Approval', 'pointfindercoreelements' ).'</span>';
                }elseif ($value2 == 'pendingpayment') {
                    $value2 = '<span style="color:red">'.esc_html__( 'Pending Payment', 'pointfindercoreelements' ).'</span>';
                }elseif ($value2 == 'pfsuspended') {
                    $value2 = '<span style="color:red">'.esc_html__( 'Suspended', 'pointfindercoreelements' ).'</span>';
                }elseif ($value2 == 'completed') {
                    $value2 = '<span style="color:green">'.esc_html__( 'Completed', 'pointfindercoreelements' ).'</span>';
                }elseif ($value2 == 'pfcancelled') {
                    $value2 = '<span style="color:red">'.esc_html__( 'Cancelled', 'pointfindercoreelements' ).'</span>';
                }
                echo $value2;
                break;

            case 'packageinfo':

              $prderinfo_itemid = esc_attr(get_post_meta( $post_id , 'pointfinder_order_packageid', true ));

                if(!empty($prderinfo_itemid)){
                    echo '<a href="'.get_edit_post_link($prderinfo_itemid).'" target="_blank"><strong>'.get_the_title($prderinfo_itemid).'</strong></a>';
                }
                break;

            case 'userinfo':

              $user_id = get_post_meta( $post_id, 'pointfinder_order_userid', true );
                $userdata = get_user_by('id',$user_id);
                echo '<a href="'.get_edit_user_link($user_id).'" target="_blank" title="'.esc_html__('Click for user details','pointfindercoreelements').'">'.$user_id.' - '.$userdata->nickname.'</a>';
                break;



            case 'itype':
              $nameofb = esc_attr(get_post_meta( $post_id, 'pointfinder_order_listingpname', true ));

                if(esc_attr(get_post_meta( $post_id, 'pointfinder_order_recurring', true )) == 1){
                  echo esc_html__('Recurring','pointfindercoreelements');
                }else{
                  echo esc_html__('Direct','pointfindercoreelements');
                }
                break;


            case 'idate':
                echo $this->PFU_DateformatS(get_post_meta( $post_id, 'pointfinder_order_expiredate', true ),1);
                break;

        }
    }
  /**
  *End: Order Membership List Filters
  **/

  /**
  *Start: PF Items Item Filter
  **/
    function pointfinder_items_item_filter() {
        global $typenow;
        if ($typenow == $this->post_type_name ) {
            echo '<input type="text" name="itemnumber" value="" placeholder="'.esc_html__('Item Number','pointfindercoreelements').'" />';
        }
    }

    function pointfinder_items_item_filter_query($query) {
        global $pagenow;
        global $typenow;
        if ($pagenow=='edit.php' && $typenow == $this->post_type_name && isset($_GET['itemnumber'])) {
            $query->query_vars['p'] = $_GET['itemnumber'];
        }
        return $query;
    }

    function pointfinder_items_edit_columns( $columns ) {
      $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => esc_html__( 'Title','pointfindercoreelements'),
        'istatus' => esc_html__( 'Status','pointfindercoreelements'),
        'ltype' => esc_html__( 'List Type','pointfindercoreelements'),
        'author' => esc_html__( 'Author','pointfindercoreelements'),
        'date' => esc_html__( 'Date','pointfindercoreelements'),
        'estatephoto' => esc_html__( 'Photo','pointfindercoreelements'),
      );
      return $columns;
    }

    function pointfinder_items_sortable_columns( $columns ) {
      $columns['author'] = 'author';
      return $columns;
    }

    function pointfinder_items_manage_columns( $column, $post_id ) {

      global $post;
      $noimg_url = get_template_directory_uri().'/images/noimg.png';

      switch( $column ) {
        case 'estatephoto' :
          $post_featured_image = get_the_post_thumbnail( $post_id, 'thumbnail', array( 'class' => 'pointfinderlistthumbimg' ));
          if ($post_featured_image) {
            echo $post_featured_image;
          } else {
            echo '<img src="' . $noimg_url.'" width="101" height="67" alt="-" />';
          }
          break;

        case 'istatus' :
          switch ($post->post_status) {
            case 'publish':
              echo '<span style="color:green">'.esc_html__( 'Published', 'pointfindercoreelements' ).'</span>';
              break;
            case 'pendingapproval':
              echo '<span style="color:red">'.esc_html__( 'Pending Approval', 'pointfindercoreelements' ).'</span>';
              break;
            case 'pendingpayment':
              echo '<span style="color:red">'.esc_html__( 'Pending Payment', 'pointfindercoreelements' ).'</span>';
              break;
            case 'rejected':
              echo '<span style="color:red">'.esc_html__( 'Rejected', 'pointfindercoreelements' ).'</span>';
              break;
            default:
              echo '';
              break;
          }
          break;

        case 'ltype':
          echo get_the_term_list( $post_id, 'pointfinderltypes', '<ul class="pointfinderpflistterms"><li>', ',</li><li>', '</li></ul>' );
          break;

      }
    }
  /**
  *End: PF Items Item Filter
  **/
}
 ?>
