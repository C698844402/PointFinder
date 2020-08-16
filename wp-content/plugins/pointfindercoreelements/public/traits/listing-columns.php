<?php
/**********************************************************************************************************************************
*
* Item Detail Page
*
* Author: Webbu
***********************************************************************************************************************************/

if (trait_exists('PointFinderListingColumns')) {
  return;
}

trait PointFinderListingColumns
{
    use PointFinderListingContents;
    use PointFinderListingBreadcrumbs;
    use PointFinderListingSharebar;
    use PointFinderListingReviewPart;
    use PointFinderListingComments;
    
    public function pf_get_listingmeta_limit($listing_meta, $item_term, $limit_var)
    {
        if (isset($listing_meta[$item_term])) {
            if (isset($listing_meta[$item_term][$limit_var])) {
                if (!empty($listing_meta[$item_term][$limit_var])) {
                    $listing_limit_status = $listing_meta[$item_term][$limit_var];
                } else {
                    $listing_limit_status = 1;
                }
            } else {
                $listing_limit_status = 1;
            }
        } else {
            $listing_limit_status = 1;
        }
        return $listing_limit_status;
    }


    public function PFGetItemPageCol1(){

    global $claim_list_permission;
    global $ohour_list_permission;
    global $wpdb;

    $claim_list_permission = 1;
    $review_list_permission = 1;
    $comment_list_permission = 1;
    $ohour_list_permission = 1;
    $features_list_permission = 1;

    $the_post_id = get_the_id();

    $setup11_reviewsystem_check = $this->PFREVSIssetControl('setup11_reviewsystem_check','','0');

    /*Item Count*/
    $item_old_count = get_post_meta( $the_post_id, 'webbupointfinder_page_itemvisitcount', true );
    
    if (empty($item_old_count)) {
      $item_old_count = 1;
    }
    
    

    $item_term = $this->pf_get_item_term_id($the_post_id);
    global $pointfinderltypes_fevars;
    if (!empty($pointfinderltypes_fevars)) {
      $listing_meta = $pointfinderltypes_fevars;
    }else{
      $listing_meta = get_option('pointfinderltypes_fevars');
    }

    $setup42_itempagedetails_sidebarpos = $this->PFSAIssetControl('setup42_itempagedetails_sidebarpos','','2');
    if ($setup42_itempagedetails_sidebarpos == 3) {
      echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
    }else{
      echo '<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">';
    }

    $setup42_itempagedetails_claim_status = $this->PFSAIssetControl('setup42_itempagedetails_claim_status','','0');
    $verified_badge_text = "";



    $listing_verified = get_post_meta( $the_post_id, 'webbupointfinder_item_verified', true );
    if($setup42_itempagedetails_claim_status == 1 && $listing_verified == 1 ){
      $setup42_itempagedetails_claim_validtext = $this->PFSAIssetControl('setup42_itempagedetails_claim_validtext','','');
      $verified_badge_text = '<span class="pfverified-bagde-text"> <i class="fas fa-check-circle" style="  color: #59C22F;font-size: 18px;"></i> '.$setup42_itempagedetails_claim_validtext.'</span>';

    }


    /*Check Advanced Settings*/
    $advanced_term_status = $this->PFADVIssetControl('setupadvancedconfig_'.$item_term.'_advanced_status','',0);

    /* Check New Advanced Settings */
    $advanced_term_status_new = 0;
    $st8_nasys = $this->PFASSIssetControl('st8_nasys','',0);

    if ( $st8_nasys == 1) {
      $advanced_term_status = 0;

      $listing_config_meta = get_option('pointfinderltypes_aslvars');
      if (isset($listing_config_meta[$item_term])) {
        if (!empty($listing_config_meta[$item_term]['pflt_advanced_status'])) {
          $advanced_term_status_new = 1;
        }
      }
    }


    if ($advanced_term_status == 0 && $advanced_term_status_new == 0) {
       global $pointfindertheme_option;
       $setup42_itempagedetails_configuration = (isset($pointfindertheme_option['setup42_itempagedetails_configuration']))? $pointfindertheme_option['setup42_itempagedetails_configuration'] : array();
        $review_list_permission = $this->PFREVSIssetControl('setup11_reviewsystem_check','','1');
        $claim_list_permission = $this->PFSAIssetControl('setup42_itempagedetails_claim_status','','1');
        $comment_list_permission = $this->PFSAIssetControl('setup3_modulessetup_allow_comments','','1');
        $ohour_list_permission = $this->PFSAIssetControl('setup3_modulessetup_openinghours','','1');
        $features_list_permission = $this->PFSAIssetControl('setup3_pointposttype_pt6_check','','1');
    }else{
      if ($advanced_term_status == 1) {
        global $pfadvancedcontrol_options;
        $setup42_itempagedetails_configuration = (isset($pfadvancedcontrol_options['setupadvancedconfig_'.$item_term.'_configuration']))? $pfadvancedcontrol_options['setupadvancedconfig_'.$item_term.'_configuration'] : array();

        /*Extra Settings*/
        $review_list_permission = $this->PFADVIssetControl('setupadvancedconfig_'.$item_term.'_reviewmodule','','1');
        $claim_list_permission = $this->PFADVIssetControl('setupadvancedconfig_'.$item_term.'_claimsmodule','','1');
        $comment_list_permission = $this->PFADVIssetControl('setupadvancedconfig_'.$item_term.'_commentsmodule','','1');
        $ohour_list_permission = $this->PFADVIssetControl('setupadvancedconfig_'.$item_term.'_ohoursmodule','','1');
        $features_list_permission = $this->PFADVIssetControl('setupadvancedconfig_'.$item_term.'_featuresmodule','','1');
      }

      if($advanced_term_status_new == 1){
        $setup42_itempagedetails_configuration = (isset($listing_config_meta[$item_term]['pflt_configuration']))? $listing_config_meta[$item_term]['pflt_configuration'] : array();
        /*Extra Settings*/
        $review_list_permission = (!empty($listing_config_meta[$item_term]['pflt_reviewmodule']))?1:0;
        $claim_list_permission = (!empty($listing_config_meta[$item_term]['pflt_claimsmodule']))?1:0;
        $comment_list_permission = (!empty($listing_config_meta[$item_term]['pflt_commentsmodule']))?1:0;
        $ohour_list_permission = (!empty($listing_config_meta[$item_term]['pflt_ohoursmodule']))?1:0;
        $features_list_permission = (!empty($listing_config_meta[$item_term]['pflt_featuresmodule']))?1:0;
      }
    }

    


    $setup3_modulessetup_headersection = $this->PFSAIssetControl('setup3_modulessetup_headersection','',1);

    if (!empty($item_term)) {
      if ($advanced_term_status == 1) {
        $setup3_modulessetup_headersection = $this->PFADVIssetControl('setupadvancedconfig_'.$item_term.'_headersection','','2');
      }
      if($advanced_term_status_new == 1){
        $setup3_modulessetup_headersection = (!empty($listing_config_meta[$item_term]['pflt_headersection']))?$listing_config_meta[$item_term]['pflt_headersection']:0;
      }
    }

    $postd_hideshow = $this->PFSAIssetControl('postd_hideshow','',1);
    $viewcount_hideshow = $this->PFSAIssetControl('viewcount_hideshow','',1);

    if ($viewcount_hideshow == 1) {
      $viewcount_text = ' <strong><i class="fas fa-eye"></i> '.$item_old_count.'</strong>';
    }else{
      $viewcount_text = '';
    }

    if ($postd_hideshow == 1) {
      $postd_text = ''.esc_html__('Posted on','pointfindercoreelements').' '.get_the_time(get_option('date_format'));

      if ($viewcount_hideshow == 1) {
        $postd_text .= ' /';
      }
    }else{
      $postd_text = '';
    }

    if ($setup3_modulessetup_headersection == 1 || $setup3_modulessetup_headersection == 2 ) {
      echo '<div class="pf-item-title-bar"><h1 class="pf-item-title-text" itemprop="name">'.get_the_title().'</h1> <span class="pf-item-subtitle"> '.esc_html(get_post_meta( get_the_id(), 'webbupointfinder_items_address', true )).'</span></div><div class="pf-item-extitlebar"><div class="pf-itemdetail-pdate">'.$postd_text.$viewcount_text.' '.$verified_badge_text.'</div></div>';
    }elseif($setup3_modulessetup_headersection == 0){
      echo '<div class="pf-item-title-bar">'.$verified_badge_text.'<div class="pf-itemdetail-pdate">'.$postd_text.$viewcount_text.'</div></div>';
    }


    $i = 1;
    $tabinside = $tabinsidesp = $tabinside_output = $tabinside_first = $taboutside_w1 = $taboutside_w2 = $tabeventdetails = '';

    $contact_check_re = 0; /* Contact status check for recaptcha */
    $tabcontactform = '';

    $sb_contact = $this->PFSAIssetControl('sb_contact','','0');
    $webbupointfinder_item_cstatus = get_post_meta( $the_post_id, 'webbupointfinder_item_cstatus', true);
    foreach ($setup42_itempagedetails_configuration as $key => $value) {
      $valtext = ($i == 2) ? 'checked' : '' ;

      switch ($key) {
        case 'gallery':
          $tabinside = '';
          if ($value['status'] == 1) {
            /**
            *Start: Gallery
            **/

              $general_crop = $this->PFSizeSIssetControl('general_crop','',1);

              $images = rwmb_meta( 'webbupointfinder_item_images', array( 'type'=>'image' ));
             

              $setupsizelimitconf_general_gallerysize1_w = $this->PFSizeSIssetControl('setupsizelimitconf_general_gallerysize1','width',848);
              $setupsizelimitconf_general_gallerysize1_h = $this->PFSizeSIssetControl('setupsizelimitconf_general_gallerysize1','height',566);

              $setupsizelimitconf_general_gallerysize2_w = $this->PFSizeSIssetControl('setupsizelimitconf_general_gallerysize2','width',112);
              $setupsizelimitconf_general_gallerysize2_h = $this->PFSizeSIssetControl('setupsizelimitconf_general_gallerysize2','height',100);

              $featured_image_orj = wp_get_attachment_image_src( get_post_thumbnail_id( $the_post_id ), 'full' );

              $featured_img_type = 'pflandscape';

              switch ($general_crop) {
                case 1:
                  $featured_image = pointfinder_aq_resize($featured_image_orj[0],$setupsizelimitconf_general_gallerysize1_w,$setupsizelimitconf_general_gallerysize1_h,true,true,true);
                  $featured_image_thumb = pointfinder_aq_resize($featured_image_orj[0],$setupsizelimitconf_general_gallerysize2_w,$setupsizelimitconf_general_gallerysize2_h,true,true,true);
                  break;

                case 2:
                  $featured_image = pointfinder_aq_resize($featured_image_orj[0],$setupsizelimitconf_general_gallerysize1_w,$setupsizelimitconf_general_gallerysize1_h,true);
                  $featured_image_thumb = pointfinder_aq_resize($featured_image_orj[0],$setupsizelimitconf_general_gallerysize2_w,$setupsizelimitconf_general_gallerysize2_h,true);
                  break;

                case 3:
                  $featured_image = false;
                  $featured_image_thumb = $featured_image_orj[0];

                  /*Orientation get*/
                  if (isset($featured_image_orj[1]) && isset($featured_image_orj[2])) {
                    if ($featured_image_orj[1] > $featured_image_orj[2]) {
                      $featured_img_type = 'pflandscape';
                    }else{
                      $featured_img_type = 'pfportrait';
                    }
                  }
                  break;
              }

              if ($featured_image == false) {
                $featured_image = pointfinder_aq_resize($featured_image_orj[0],$setupsizelimitconf_general_gallerysize1_w,$setupsizelimitconf_general_gallerysize1_h);
              }else{
                $featured_image = pointfinder_aq_resize($featured_image,$setupsizelimitconf_general_gallerysize1_w,$setupsizelimitconf_general_gallerysize1_h);
              }

              if (isset($featured_image[0])) {
                if (strlen($featured_image[0]) > 10) {
                  $featured_image = $featured_image[0];
                }
              }

              if($featured_image == false){
                                $featured_image = $featured_image_orj[0];
                            }

              if((!empty($images) || !empty($featured_image))){
                $tabinside .= '<div class="ui-tab'.$i.' ">';
                $tabinside .= '<section role="itempagegallery" class="pf-itempage-gallery pf-itempage-elements">';

                  $tabinside .= '<div id="pf-itempage-gallery">';
                  $tabspecial = '';
                  $tabinside .= apply_filters('pointfinder_gallery_before_images',$tabspecial,$the_post_id);
                  $autoplay_status = $this->PFSAIssetControl('setup42_itempagedetails_gallery_autoplay','','0');
                  $autoheight_status = $this->PFSAIssetControl('setup42_itempagedetails_gallery_autoheight','','0');

                  if ($autoplay_status == 1) {
                    $autoplay_status = true;
                  }else{
                    $autoplay_status = false;
                  }

                  if ($autoheight_status == 1) {
                    $autoheight_status = true;
                  }else{
                    $autoheight_status = false;
                  }

                  if ($general_crop == 1) {
                    $autoheight_status = true;
                  }

                  $thumbs_status = ($this->PFSAIssetControl('setup42_itempagedetails_gallery_thumbs','','0') == 1) ? ' pfdispnone' : '' ;

                  $output = $output2 = '';

                  $di_lbox_v = $this->PFSAIssetControl('di_lbox_v','',1);

                  $featured_image_control = $this->PFSAIssetControl('setup42_itempagedetails_featuredimage','','1');

                  if($featured_image_control == 1 && !is_rtl()){
                    $output .= "<li class='item'>";
                    if ($di_lbox_v == 1) {$output .= "<a href='".$featured_image."' class='mfp-image pfimage-linko'>";}

                    if ($general_crop == 3) {
                      $output .= "<div class='pfshoworiginalitemphotomain ".$featured_img_type."'><img data-no-lazy='1' itemprop='image' src='".$featured_image."' alt='' /></div>";
                    }else{
                      $output .= "<img itemprop='image' src='".$featured_image."' alt='' />";
                    }

                    if ($di_lbox_v == 1) {$output .= "</a>";}
                    $output .= "</li>";


                    if ($general_crop == 3) {
                      $output2 .= "<li class='item'><div class='pfshoworiginalitemphoto'><img src='".$featured_image_thumb."' alt='' /></div></li>";
                    }else{
                      $output2 .= "<li class='item'><img src='".$featured_image_thumb."' alt='' /></li>";
                    }
                  }

                  if(!empty($images)){
                    $other_img_type = 'pflandscape';
                    $kl = 0;
                    if (is_rtl()) {
                      foreach ( array_reverse($images) as $image ){

                        switch ($general_crop) {
                          case 1:
                            $image_orj = pointfinder_aq_resize($image['full_url'],$setupsizelimitconf_general_gallerysize1_w,$setupsizelimitconf_general_gallerysize1_h,true,true,true);
                            $image_orj_thumb = pointfinder_aq_resize($image['full_url'],$setupsizelimitconf_general_gallerysize2_w,$setupsizelimitconf_general_gallerysize2_h,true,true,true);
                            break;

                          case 2:
                            $image_orj = pointfinder_aq_resize($image['full_url'],$setupsizelimitconf_general_gallerysize1_w,$setupsizelimitconf_general_gallerysize1_h,true);
                            $image_orj_thumb = pointfinder_aq_resize($image['full_url'],$setupsizelimitconf_general_gallerysize2_w,$setupsizelimitconf_general_gallerysize2_h,true);
                            break;

                          case 3:
                            $image_orj = false;
                            $image_orj_thumb = pointfinder_aq_resize($image['full_url'],$setupsizelimitconf_general_gallerysize2_w,$setupsizelimitconf_general_gallerysize2_h,true);
                            break;
                        }


                        if ($image_orj == false) {
                          $image = array('url'=>$image['full_url'],'full_url'=>$image['full_url'],'width'=>$setupsizelimitconf_general_gallerysize1_w,'height'=>$setupsizelimitconf_general_gallerysize1_h,'alt'=>$image['alt']);
                        }else{
                          $image = array('url'=>$image_orj_thumb,'full_url'=>$image_orj,'width'=>$setupsizelimitconf_general_gallerysize1_w,'height'=>$setupsizelimitconf_general_gallerysize1_h,'alt'=>$image['alt']);
                        }

                        /*Orientation get*/
                        if (isset($image["width"]) && isset($image["height"])) {
                          if ($image["width"] > $image["height"]) {
                            $other_img_type = 'pflandscape';
                          }else{
                            $other_img_type = 'pfportrait';
                          }
                        }


                        if($kl == 0){
                          $firstimage = "<img src='{$image['full_url']}' alt='{$image['alt']}' />";
                        }
                          $output .= "<li class='item'>";
                          if ($di_lbox_v == 1) {$output .= "<a href='{$image['full_url']}' class='mfp-image pfimage-linko'>";}

                          if ($general_crop == 3) {
                          $output .= "<div class='pfshoworiginalitemphotomain ".$other_img_type."'><img data-no-lazy='1' src='{$image['full_url']}' alt='{$image['alt']}' /></div>";
                        }else{
                          $output .= "<img data-no-lazy='1' src='{$image['full_url']}' alt='{$image['alt']}' />";
                        }


                          if ($di_lbox_v == 1) {$output .= "</a>";}
                          $output .= "</li>";

                          if ($general_crop == 3) {
                          $output2 .= "<li class='item'><div class='pfshoworiginalitemphoto'><img src='{$image['url']}' alt='{$image['alt']}' /></div></li>";
                        }else{
                          $output2 .= "<li class='item'><img src='{$image['url']}' alt='{$image['alt']}' /></li>";
                        }

                          $kl++;
                      }
                    }else{
                      foreach ( $images as $image ){

                        switch ($general_crop) {
                          case 1:
                            $image_orj = pointfinder_aq_resize($image['full_url'],$setupsizelimitconf_general_gallerysize1_w,$setupsizelimitconf_general_gallerysize1_h,true,true,true);
                            $image_orj_thumb = pointfinder_aq_resize($image['full_url'],$setupsizelimitconf_general_gallerysize2_w,$setupsizelimitconf_general_gallerysize2_h,true,true,true);
                            break;

                          case 2:
                            $image_orj = pointfinder_aq_resize($image['full_url'],$setupsizelimitconf_general_gallerysize1_w,$setupsizelimitconf_general_gallerysize1_h,true);
                            $image_orj_thumb = pointfinder_aq_resize($image['full_url'],$setupsizelimitconf_general_gallerysize2_w,$setupsizelimitconf_general_gallerysize2_h,true);
                            break;

                          case 3:
                            $image_orj = false;
                            $image_orj_thumb = pointfinder_aq_resize($image['full_url'],$setupsizelimitconf_general_gallerysize2_w,$setupsizelimitconf_general_gallerysize2_h,true);
                            break;
                        }


                        if ($image_orj == false) {
                          $image = array('url'=>$image['full_url'],'full_url'=>$image['full_url'],'width'=>$setupsizelimitconf_general_gallerysize1_w,'height'=>$setupsizelimitconf_general_gallerysize1_h,'alt'=>$image['alt']);
                        }else{
                          $image = array('url'=>$image_orj_thumb,'full_url'=>$image_orj,'width'=>$setupsizelimitconf_general_gallerysize1_w,'height'=>$setupsizelimitconf_general_gallerysize1_h,'alt'=>$image['alt']);
                        }

                        /*Orientation get*/
                        if (isset($image["width"]) && isset($image["height"])) {
                          if ($image["width"] > $image["height"]) {
                            $other_img_type = 'pflandscape';
                          }else{
                            $other_img_type = 'pfportrait';
                          }
                        }

                        if($kl == 0){
                          $firstimage = "<img src='{$image['full_url']}' alt='{$image['alt']}' />";
                        }

                          $output .= "<li class='item'>";
                          if ($di_lbox_v == 1) {$output .= "<a href='{$image['full_url']}' class='mfp-image pfimage-linko'>";}

                          if ($general_crop == 3) {
                          $output .= "<div class='pfshoworiginalitemphotomain ".$other_img_type."'><img data-no-lazy='1' src='{$image['full_url']}' alt='{$image['alt']}' /></div>";
                        }else{
                          $output .= "<img data-no-lazy='1' src='{$image['full_url']}' alt='{$image['alt']}' />";
                        }

                          if ($di_lbox_v == 1) {$output .= "</a>";}
                          $output .= "</li>";

                          if ($general_crop == 3) {
                          $output2 .= "<li class='item'><div class='pfshoworiginalitemphoto'><img src='{$image['url']}' alt='{$image['alt']}' /></div></li>";
                        }else{
                          $output2 .= "<li class='item'><img src='{$image['url']}' alt='{$image['alt']}' /></li>";
                        }


                          $kl++;
                      }
                    }
                  }else{
                    $firstimage = "<img src='".$featured_image."' alt='' />";

                  }

                  if($featured_image_control == 1 && is_rtl()){
                    $output .= "<li class='item'><a href='".$featured_image_orj[0]."' class='mfp-image pfimage-linko'><img itemprop='image' src='".$featured_image."' alt='' /></a></li>";
                    $output2 .= "<li class='item'><img src='".$featured_image_thumb."' alt='' /></li>";

                    if ($general_crop == 3) {
                      $output .= "<li class='item'><a href='".$featured_image_orj[0]."' class='mfp-image pfimage-linko'><div class='pfshoworiginalitemphotomain ".$featured_img_type."'><img data-no-lazy='1' itemprop='image' src='".$featured_image."' alt='' /></div></a></li>";
                      $output2 .= "<li class='item'><div class='pfshoworiginalitemphoto'><img src='".$featured_image_thumb."' alt='' /></div></li>";

                    }else{
                      $output .= "<li class='item'><a href='".$featured_image_orj[0]."' class='mfp-image pfimage-linko'><img itemprop='image' src='".$featured_image."' alt='' /></a></li>";
                      $output2 .= "<li class='item'><img src='".$featured_image_thumb."' alt='' /></li>";

                    }
                  }

                  $tabinside .= '<div class="visible-print">'.$firstimage.'</div>';

                  if(empty($images)){$css_text_slider = " style='margin-bottom:0;'";}else{$css_text_slider = '';};

                  $tabinside .= '<ul id="pfitemdetail-slider" class="owl-carousel hidden-print"'.$css_text_slider.' data-mes1="'.esc_html__("Previous (Left arrow key)", "pointfindercoreelements" ).'" data-mes2="'.esc_html__("Next (Right arrow key)", "pointfindercoreelements" ).'" data-tstyle="'.$this->PFSAIssetControl('setup42_itempagedetails_gallery_effect','','fadeUp').'" data-autoplay="'.$autoplay_status.'" data-autoheight="'.$autoheight_status.'" data-timer="'.$this->PFSAIssetControl('setup42_itempagedetails_gallery_interval','','300').'">';
                    $tabinside .= $output;
                  $tabinside .= '</ul>';
                  if(!empty($images)){
                    $tabinside .= '<ul id="pfitemdetail-slider-sub" class="owl-carousel hidden-print hidden-xs'.$thumbs_status.'">';
                      $tabinside .= $output2;
                    $tabinside .= '</ul>';
                  }
                  $tabinside .= '</div>';
                $tabinside .= '</section>';
                $tabinside .= '</div>';

                if($i > 1){
                  $taboutside_w1 .= '<input class="ui-tab'.$i.'" type="radio" id="tgroup_f_tab'.$i.'" name="tgroup_f" '.$valtext.' />';
                  $taboutside_w2 .= '<label id="pfidp'.$key.'" class="ui-tab'.$i.' hidden-print" for="tgroup_f_tab'.$i.'"><span class="pfitp-title">'.$value['title'].'</span></label>';
                }

              }
            /**
            *End: Gallery
            **/

          }
          break;

        case 'informationbox':

            $tabinside = $tabinsidesp = '';
            /**
            *Start: Information Box
            **/
              if($value['status'] == 1){

                global $pointfindertheme_option;

                $tabinsidesp .= '<div class="ui-tab'.$i.' uix-tabx-desc ">';

                $setup3_modulessetup_openinghours = $this->PFSAIssetControl('setup3_modulessetup_openinghours','','0');
                $setup3_pointposttype_pt6_check = $this->PFSAIssetControl('setup3_pointposttype_pt6_check','','1');

                if ($setup3_modulessetup_openinghours == 0) {
                  $setup42_itempagedetails_config3 = $pointfindertheme_option['setup42_itempagedetails_config4'];
                }elseif ($setup3_modulessetup_openinghours == 1) {
                  $setup42_itempagedetails_config3 = $pointfindertheme_option['setup42_itempagedetails_config3'];
                }
                foreach ($setup42_itempagedetails_config3['enabled'] as $single_arr_val => $val) {

                  switch ($single_arr_val) {

                    /*Details & O. Hours*/
                    case 'details':
                      $tabinsidesp .= $this->pf_itemdetail_halfcol($this->pfitempage_details_block(),$this->pfitempage_ohours_block());
                      break;


                    /*Details*/
                    case 'details1':
                      $tabinsidesp .= $this->pf_itemdetail_fullcol($this->pfitempage_details_block());
                      break;


                    /*Details & Description*/
                    case 'details2':
                      $tabinsidesp .= $this->pf_itemdetail_thirdcol($this->pfitempage_details_block(),$this->pfitempage_description_block());
                      break;


                    /*Details & Description*/
                    case 'details2x':
                      $tabinsidesp .= $this->pf_itemdetail_thirdcolx($this->pfitempage_description_block(),$this->pfitempage_details_block());
                      break;



                    /*Details + Opening Hours & Description*/
                    case 'details4':
                      $tabinsidesp .= $this->pf_itemdetail_forthcol($this->pfitempage_details_block(),$this->pfitempage_ohours_block(),$this->pfitempage_description_block());
                      break;

                    /*Description & Details + Opening Hours*/
                    case 'details4x':
                      $tabinsidesp .= $this->pf_itemdetail_forthcolx($this->pfitempage_description_block(),$this->pfitempage_details_block(),$this->pfitempage_ohours_block());
                      break;




                    /*Description*/
                    case 'description':
                      $tabinsidesp .= $this->pf_itemdetail_fullcol($this->pfitempage_description_block());
                      break;





                    /*Opening Hours*/
                    case 'ohours1':
                      $tabinsidesp .= $this->pf_itemdetail_fullcol($this->pfitempage_ohours_block());
                      break;



                    /*Opening Hours & Description*/
                    case 'ohours3':
                      $tabinsidesp .= $this->pf_itemdetail_thirdcol($this->pfitempage_ohours_block(),$this->pfitempage_description_block());
                      break;




                  }


                }


                $tabinsidesp .= '</div>';

                /* Desc */
                $tabinside .= $tabinsidesp;

              }
            /**
            *End: Information Box
            **/
          break;

        case 'description1':
            $tabinside = '';
            /**
            *Start: Description 1
            **/
              if($value['status'] == 1){

                $tabinside .= '<div class="ui-tab'.$i.' ui-desc-single ">';

                if ($value['fimage'] == 1) {
                  $tabinside .= $this->pf_itemdetail_thirdcols1($this->pfitempage_fimage_block(),$this->pfitempage_description_block1());
                }elseif ($value['fimage'] == 2) {
                  $tabinside .= $this->pf_itemdetail_thirdcolxs1($this->pfitempage_description_block1(),$this->pfitempage_fimage_block());
                }else{
                  $tabinside .= $this->pfitempage_description_block1();
                }

                $tabinside .= '</div>';

                /* Desc */
              }
            /**
            *End: Description  1
            **/
          break;

        case 'description2':
            $tabinside = '';
            /**
            *Start: Description 2
            **/
              if($value['status'] == 1){

                $tabinside .= '<div class="ui-tab'.$i.'">';

                if ($value['fimage'] == 1) {
                  $tabinside .= $this->pf_itemdetail_thirdcols1($this->pfitempage_fimage_block(),$this->pfitempage_description_block2());
                }elseif ($value['fimage'] == 2) {
                  $tabinside .= $this->pf_itemdetail_thirdcolxs1($this->pfitempage_description_block2(),$this->pfitempage_fimage_block());
                }else{
                  $tabinside .= $this->pfitempage_description_block2();
                }

                $tabinside .= '</div>';

                /* Desc */
              }
            /**
            *End: Description  2
            **/
          break;

        case 'location':
          $tabinside = '';
          if ($value['status'] == 1 && $this->pf_get_listingmeta_limit($listing_meta, $item_term, 'pf_address_area') == 1) {

            /**
            *Start: Map
            **/
              $tabinside .= '<div class="ui-tab'.$i.' ">';
                $tabinside .= '<section role="itempagemap" class="pf-itempage-maparea pf-itempage-elements">';
                  $tabinside .= '<div id="pf-itempage-header-map"></div>';
                $tabinside .= '</section>';
              $tabinside .= '</div>';
            /**
            *End: Map
            **/

            if (class_exists('Pointfindercustom360')) {
              $special360 = get_post_meta( $the_post_id, 'webbupointfinder_item_360', true );
                          if (!empty($special360)) {
                $kk = 1 + $i;
                $tabinside .= '<div class="ui-tab'.$kk.'">';
                  $tabinside .= '<section role="itempagemap" class="pf-itempage-maparea pf-itempage-elements">';
                    $tabinside .= '<div id="pf-itempage-360"><iframe width="853" height="480" src="'.$special360.'" frameborder="0" allowfullscreen allow="vr"></iframe></div>';
                  $tabinside .= '</section>';
                $tabinside .= '</div>';

                $taboutside_w1 .= '<input class="ui-tab'.$kk.'" type="radio" id="tgroup_f_tab'.$kk.'" name="tgroup_f" '.$valtext.' />';
                $taboutside_w2 .= '<label id="pfidp360" class="ui-tab'.$kk.' hidden-print" for="tgroup_f_tab'.$kk.'"><span class="pfitp-title">'.esc_html__("360 View","pointfindercoreelements").'</span></label>';
              }
            }
          }
          break;

        case 'streetview':
          $tabinside = '';
          if ($value['status'] == 1 && $this->pf_get_listingmeta_limit($listing_meta, $item_term, 'pf_address_area') == 1) {

            /**
            *Start: Streetview
            **/

              $tabinside .= '<div class="ui-tab'.$i.' ">';
                $tabinside .= '<section role="itempagestmap" class="pf-itempage-stmaparea pf-itempage-elements">';
                  $tabinside .= '<div id="pf-itempage-header-streetview"></div>';
                $tabinside .= '</section>';
              $tabinside .= '</div>';

              if($i > 1){
                $taboutside_w1 .= '<input class="ui-tab'.$i.'" type="radio" id="tgroup_f_tab'.$i.'" name="tgroup_f" '.$valtext.' />';
                $taboutside_w2 .= '<label id="pfidp'.$key.'" class="ui-tab'.$i.' hidden-print" for="tgroup_f_tab'.$i.'"><span class="pfitp-title">'.$value['title'].'</span></label>';
              }

            /**
            *End: Streetview
            **/
          }
          break;

        case 'video':
          $tabinside = '';
          if ($value['status'] == 1) {

            /**
            *Start: Video
            **/

              $video_output = redux_post_meta("pointfinderthemefmb_options", $the_post_id, "webbupointfinder_item_video");

              if(!empty($video_output)){
                $tabinside .= '<div class="ui-tab'.$i.' hidden-print ">';
                  $tabinside .= '<section role="itempagevideo" class="pf-itempage-video pf-itempage-elements">';
                    $tabinside .= '<div id="pf-itempage-video">';
                      $tabinside .= wp_oembed_get($video_output);
                    $tabinside .= '</div>';
                  $tabinside .= '</section>';
                $tabinside .= '</div>';

                if($i > 1){
                  $taboutside_w1 .= '<input class="ui-tab'.$i.'" type="radio" id="tgroup_f_tab'.$i.'" name="tgroup_f" '.$valtext.' />';
                  $taboutside_w2 .= '<label id="pfidp'.$key.'" class="ui-tab'.$i.' hidden-print" for="tgroup_f_tab'.$i.'"><span class="pfitp-title">'.$value['title'].'</span></label>';
                }
              }

            /**
            *End: Video
            **/
          }
          break;

        case 'events':

          if ($value['status'] == 1 ) {

            $field_startdate = ($the_post_id != '') ? get_post_meta($the_post_id,'webbupointfinder_item_field_startdate',true) : '' ;
            $field_enddate = ($the_post_id != '') ? get_post_meta($the_post_id,'webbupointfinder_item_field_enddate',true) : '' ;
            $field_starttime = ($the_post_id != '') ? get_post_meta($the_post_id,'webbupointfinder_item_field_starttime',true) : '' ;
            $field_endtime = ($the_post_id != '') ? get_post_meta($the_post_id,'webbupointfinder_item_field_endtime',true) : '' ;

            $setup4_membersettings_dateformat = $this->PFSAIssetControl('setup4_membersettings_dateformat','','1');

            switch ($setup4_membersettings_dateformat) {
              case '1':$date_field_format2 = 'd/m/Y';break;
              case '2':$date_field_format2 = 'm/d/Y';break;
              case '3':$date_field_format2 = 'Y/m/d';break;
              case '4':$date_field_format2 = 'Y/d/m';break;
              default:$date_field_format2 = 'd/m/Y';break;
            }

            if (!empty($field_startdate)) {
              $field_startdate = date($date_field_format2,$field_startdate);
            }
            if (!empty($field_enddate)) {
              $field_enddate = date($date_field_format2,$field_enddate);
            }

            $eare_times = $this->PFSAIssetControl('eare_times','',1);


            if (!empty($field_startdate) && !empty($field_enddate)) {
              $tabeventdetails = '<div class="pftrwcontainer hidden-print pf-itempagedetail-element pf-itempage-eventinfo pfnewbglppage">
                <div class="pfitempagecontainerheader">'.$value['title'].'</div>
                <div class="pfmaincontactinfo">';
                $tabeventdetails .= '<div class="pf-row clearfix">';

                $tabeventdetails .= '<div class="col-lg-6 pf-event-content-top">';
                  $tabeventdetails .= '<div class="pf-event-content">';
                  $tabeventdetails .= '<div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 pf-event-title">';
                  $tabeventdetails .= esc_html__('Start Date:','pointfindercoreelements');
                  $tabeventdetails .= '</div>';

                  $tabeventdetails .= '<div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 pf-event-text">';
                  $tabeventdetails .= $field_startdate;
                  $tabeventdetails .= '</div>';
                  $tabeventdetails .= '</div>';

                $tabeventdetails .= '</div>';


                $tabeventdetails .= '<div class="col-lg-6 pf-event-content-top">';

                  $tabeventdetails .= '<div class="pf-event-content">';

                  $tabeventdetails .= '<div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 pf-event-title">';
                  $tabeventdetails .= esc_html__('End Date:','pointfindercoreelements');
                  $tabeventdetails .= '</div>';

                  $tabeventdetails .= '<div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 pf-event-text">';
                  $tabeventdetails .= $field_enddate;
                  $tabeventdetails .= '</div>';
                  $tabeventdetails .= '</div>';

                $tabeventdetails .= '</div>';

                if ($eare_times == 1 && (!empty($field_starttime) && !empty($field_endtime))) {
                $tabeventdetails .= '<div class="col-lg-6 pf-event-content-top">';

                  $tabeventdetails .= '<div class="pf-event-content">';

                  $tabeventdetails .= '<div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 pf-event-title">';
                  $tabeventdetails .= esc_html__('Start Time:','pointfindercoreelements');
                  $tabeventdetails .= '</div>';

                  $tabeventdetails .= '<div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 pf-event-text">';
                  $tabeventdetails .= $field_starttime;
                  $tabeventdetails .= '</div>';
                  $tabeventdetails .= '</div>';

                $tabeventdetails .= '</div>';


                $tabeventdetails .= '<div class="col-lg-6 pf-event-content-top">';

                  $tabeventdetails .= '<div class="pf-event-content">';

                  $tabeventdetails .= '<div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 pf-event-title">';
                  $tabeventdetails .= esc_html__('End Time:','pointfindercoreelements');
                  $tabeventdetails .= '</div>';

                  $tabeventdetails .= '<div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 pf-event-text">';
                  $tabeventdetails .= $field_endtime;
                  $tabeventdetails .= '</div>';
                  $tabeventdetails .= '</div>';

                $tabeventdetails .= '</div>';
                }

              $tabeventdetails .= '</div>';


              $tabeventdetails .= '</div>';
              $tabeventdetails .= '</div>';
            }

            $tabinside = '';
          }

          break;

        case 'contact':
          $tabinside = '';
          

          if($value['status'] == 1 && $webbupointfinder_item_cstatus != '0'){
            /**
            *Start: Contact
            **/
              
                /**
                *Start: User Contact
                **/ 
                    $contact_content = $this->contactleftsidebar(
                      array(
                        'formmode' => 'user',
                        'i'=>$i,
                        'the_post_id' => $the_post_id,
                        'the_author_id'=>'',
                        'sb_contact'=>$sb_contact
                      )
                    );
                    
                    if (!empty($contact_content)) {
                      $tabinside .= '<div class="ui-tab'.$i.' hidden-print">';
                      $tabinside .= '<section role="itempagesidebarinfo" class="pf-itempage-sidebarinfo pfpos2 pf-itempage-elements">';
                      $tabinside .= $contact_content;
                      $tabinside .= '</section>';
                      $tabinside .= '</div>';
                    }
                /**
                *End: User Contact
                **/

                  if ($sb_contact == 1) {
                    global $tabcontactform;
                     $tabcontactform = '<div class="pftrwcontainer hidden-print pf-itempagedetail-element pf-itempage-contactinfo pfnewbglppage pfsidebarcontact">';
                  }else{
                     $tabcontactform = '<div class="pftrwcontainer hidden-print pf-itempagedetail-element pf-itempage-contactinfo pfnewbglppage">';
                  }
                 
                   
                  $tabcontactform .= '<div class="pfmaincontactinfo">';
                  $tabcontactform .= $tabinside;
                  $tabcontactform .= '</div>
                  </div>';
                  $tabinside = '';
            /**
            *End: Contact
            **/
          }
          break;

        case 'details':
        case 'ohours':
        case 'features':
          $tabinside = '';
          break;
        case 'customtab1':
        case 'customtab2':
        case 'customtab3':
        case 'customtab4':
        case 'customtab5':
        case 'customtab6':
          $tabinside = '';
          if ($value['status'] == 1) {
            switch ($key) {case 'customtab1':$ctabid = 1;break;case 'customtab2':$ctabid = 2;break;case 'customtab3':$ctabid = 3;break;case 'customtab4':$ctabid = 4;break;case 'customtab5':$ctabid = 5;break;case 'customtab6':$ctabid = 6;break;}
            $customb_content = get_post_meta( $the_post_id, 'webbupointfinder_item_custombox'.$ctabid, true );
            if (!empty($customb_content)) {

              //$customb_content = apply_filters('the_content', $customb_content);

              /**
              *Start: Custom Tab x
              **/
                $tabinside .= '<div class="ui-tab'.$i.' hidden-print ">';
                  $tabinside .= '<section role="itempagecustomtabs" class="pf-itempage-customtabs pf-itempage-elements">';
                    $tabinside .= '<div id="pf-itempage-customtabs'.$i.'">';
                      $tabinside .= do_shortcode($customb_content);
                    $tabinside .= '</div>';
                  $tabinside .= '</section>';
                $tabinside .= '</div>';
              /**
              *End: Custom Tab x
              **/

              if($i > 1){
                $taboutside_w1 .= '<input class="ui-tab'.$i.'" type="radio" id="tgroup_f_tab'.$i.'" name="tgroup_f" '.$valtext.' />';
                $taboutside_w2 .= '<label id="pfidp'.$key.'" class="ui-tab'.$i.' hidden-print" for="tgroup_f_tab'.$i.'"><span class="pfitp-title">'.$value['title'].'</span></label>';
              }
            }else{
              $tabinside = '';
            }
          }
          break;


      }

      if ($key == 'contact' && $value['status'] == 1 && $webbupointfinder_item_cstatus != '0') {
        $contact_check_re = 1;
      }


      $excludeobj_arr = array('twitter','description','video','contact','events','streetview','gallery','customtab1','customtab2','customtab3','customtab4','customtab5','customtab6');
      $itemvalimg = '';

      if($i > 1 && !in_array($key, $excludeobj_arr)){
        if ($value['status'] == 1 && $key != 'location') {
          $taboutside_w1 .= '<input class="ui-tab'.$i.'" type="radio" id="tgroup_f_tab'.$i.'" name="tgroup_f" '.$valtext.' />';
          $taboutside_w2 .= '<label id="pfidp'.$key.'" class="ui-tab'.$i.' hidden-print" for="tgroup_f_tab'.$i.'"><span class="pfitp-title">'.$value['title'].'</span></label>';
        }elseif ($value['status'] == 1 && $key == 'location') {
          if ($this->pf_get_listingmeta_limit($listing_meta, $item_term, 'pf_address_area') == 1) {
            $taboutside_w1 .= '<input class="ui-tab'.$i.'" type="radio" id="tgroup_f_tab'.$i.'" name="tgroup_f" '.$valtext.' />';
            $taboutside_w2 .= '<label id="pfidp'.$key.'" class="ui-tab'.$i.' hidden-print" for="tgroup_f_tab'.$i.'"><span class="pfitp-title">'.$value['title'].'</span></label>';
          }
        }
      }elseif($i > 1 && $key == 'description'){
        if ($value['status'] == 1) {
          $taboutside_w1 .= '<input class="ui-tab'.$i.'" type="radio" id="tgroup_f_tab'.$i.'" name="tgroup_f" '.$valtext.' />';
          $taboutside_w2 .= '<label id="pfidp'.$key.'" class="ui-tab'.$i.' hidden-print" for="tgroup_f_tab'.$i.'"><span class="pfitp-title">'.esc_html__('Information','pointfindercoreelements').'</span></label>';
      } }

      if ($i > 1) {
        if ($value['status'] == 1) {
          $tabinside_output .= $tabinside;
        }
      }else{
        if ($value['status'] == 1) {
          $additional_css_class = '';
          switch ($key) {
            case 'gallery':
              $additional_css_class = ' pffirstgallery';
              break;
            case 'location':
              $additional_css_class = ' pffirstmap';
              break;
            case 'streetview':
              $additional_css_class = ' pffirststreetview';
              break;
            case 'video':
              $additional_css_class = ' pffirstvideo';
              break;
            case 'contact':
              $additional_css_class = ' pffirstcontact';
              break;
          }
          
          $tabinside_first .= '<div class="pf-itempagedetail-element pf-tabfirst pfnewbglppage'.$additional_css_class.'"><div class="pf-itempage-firsttab">'.$tabinside.'</div></div>';
          
        }

      }
      if ($value['status'] == 1 && ($key != 'events' && $key != 'contact' && $key != 'details' && $key != 'ohours' && $key != 'features' && $key != 'twitter' && $key != 'description')) {
        $i++;
      }
      if ($key == 'location' && class_exists('Pointfindercustom360')) {
        $special360 = get_post_meta( $the_post_id, 'webbupointfinder_item_360', true );
        if (!empty($special360)) {
          $i++;
        }
      }

    }

    echo $tabinside_first;

    /**
    *Start: Share bar
    **/
      $this->pointfinder_sharebar_function();
    /**
    *End: Share bar
    **/

    $pflistingpage_content_bf_tabs = '';
    $pflistingpage_content_bf_tabs = apply_filters('pointfinder_listing_detail_before_tabs',$pflistingpage_content_bf_tabs,$the_post_id);

    $pflistingpage_content_af_tabs = '';
    $pflistingpage_content_af_tabs = apply_filters('pointfinder_listing_detail_after_tabs',$pflistingpage_content_af_tabs,$the_post_id);

    echo '<div class="pftabcontainer  pf-itempagedetail-element" data-lid="'.$the_post_id.'">';
      echo $pflistingpage_content_bf_tabs;
      echo '<div class="ui-tabgroup">';
      echo $taboutside_w1;

        echo '<div class="ui-tabs">';
        echo $taboutside_w2;
        echo '</div>';

        echo '<div class="ui-panels pftabsgroup">';
        echo $tabinside_output;
        echo '</div>';


      echo '</div>';
      echo $pflistingpage_content_af_tabs;
    echo '</div>';


    


    /**
    *Start: Features Widget
    **/
      if ($features_list_permission == 1) {

        $cat_extra_opts = get_option('pointfinderltypes_covars');
        $multiple_select = (isset($cat_extra_opts[$item_term]['pf_multipleselect']))?$cat_extra_opts[$item_term]['pf_multipleselect']:2;
        $subcat_select = (isset($cat_extra_opts[$item_term]['pf_subcatselect']))?$cat_extra_opts[$item_term]['pf_subcatselect']:2;
        $cols = 4;
        echo $this->pfitempage_features_block($cols,$subcat_select,$multiple_select);
      }
    /**
    *End: Features Widget
    **/





    /**
    *Start: Files Widget
    **/
      echo $this->pfitempage_files_block();
    /**
    *End: Files Widget
    **/

    

    /**
    *Start: Event Details Widget
    **/
      echo $tabeventdetails;
    /**
    *End: Event Details Widget
    **/



    /**
    *Start: Contact Widget
    **/
      if ($sb_contact != 1 && $webbupointfinder_item_cstatus != '0') {
        echo $tabcontactform;
      }
    /**
    *End: Contact Widget
    **/


    /**
    *Start: Review System
    **/
      if ($review_list_permission == 1 && $setup11_reviewsystem_check == 1) {
        $this->pointfinder_review_part();
      }
    /**
    *End: Review System
    **/



    /**
    *Start: Custom Action
    **/
      do_action( 'pointfinder_custom_itemdetailpage_module');
    /**
    *End: Custom Action
    **/



    /**
    *Start: Comment System
    **/
      if ($comment_list_permission == 1) {
        echo '<div class="pfnewbglppage pfnewcommentsdiv">';
        echo '<div class="pfitempagecontainerheader hidden-print" id="comments">';
          if ( comments_open() ){
             comments_popup_link( esc_html__('No comments yet','pointfindercoreelements'), esc_html__('1 comment','pointfindercoreelements'), esc_html__('% comments','pointfindercoreelements'), 'comments-link', esc_html__('Comments are off for this post','pointfindercoreelements'));
          }else{
            esc_html_e('Comments','pointfindercoreelements');
          };
        echo '</div>';
        echo '<div class="pftcmcontainer golden-forms hidden-print">';
          comments_template();
        echo '</div>';
        echo '</div>';
      }
    /**
    *End: Comment System
    **/

    /**
    *Start: Tags Widget
    **/
      $di_tags_v = $this->PFSAIssetControl('di_tags_v','','1');
      if ($di_tags_v == 1) {
        $this_tags = wp_get_post_tags($the_post_id);

        if (!empty($this_tags)) {
          echo $this->pfitempage_tags_block();
        }
      }
    /**
    *End: Tags Widget
    **/

    echo '</div>';
  }

    public function PFGetItemPageCol2($pointfinder_customsidebar)
    {
        global $tabcontactform;
        $sb_contact = $this->PFSAIssetControl('sb_contact', '', '0');
        echo '<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 hidden-print">';
        echo '<section role="itempagesidebar" class="pf-itempage-sidebar">';
        echo '<div id="pf-itempage-sidebar">';
        echo '<div class="sidebar-widget">';
        if ($sb_contact == 1) {
            echo '<div class="pfitsb-widget">';
            echo $tabcontactform;
            echo '</div>';
        }
        if (!empty($pointfinder_customsidebar)) {
            if (!function_exists('dynamic_sidebar') || !dynamic_sidebar($pointfinder_customsidebar));
        } else {
            if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('pointfinder-itempage-area'));
        }
        echo '</div>';
        echo '</div>';
        echo '</section>';
        echo '</div>';
    }
}
