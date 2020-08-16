<?php


if (!trait_exists('PointFinderCommonFunctions')) {


/**
 * Common Functions
 */
trait PointFinderCommonFunctions
{   

    public function pointfinder_device_check($question = ''){
        $device_check = true;

        if (class_exists('Mobile_Detect')) {
            $detect = new Mobile_Detect;
        }else{
            $detect = false;
        }
    
        switch ($question) {
            case 'isDesktop':
                if ($detect === false) {
                    $device_check = true;
                }else{
                    if (!$detect->isTablet() && !$detect->isMobile()) {
                        $device_check = true;
                    }else{
                        $device_check = false;
                    }
                }
                break;
            case 'isTablet':
                if ($detect === false) {
                    $device_check = true;
                }else{
                    if ($detect->isTablet()) {
                        $device_check = true;
                    }else{
                        $device_check = false;
                    }
                }
                break;
        }

        return $device_check;
    }

    public function contactleftsidebar($params){
        $defaults = array( 
            'formmode' => 'user',
            'i' => 0,
            'the_post_id' => '',
            'the_author_id' => '',
            'sb_contact' => '',
            'locationofplace' => ''
        );
        
        $params = array_merge($defaults, $params);

        extract($params);
        
        global $wpdb;
        $author_id_defined = $output = $ex_text = '';
        $user_socials = array();


        /* Get Admin Options */

            $setup42_itempagedetails_contact_photo = $this->PFSAIssetControl('setup42_itempagedetails_contact_photo','','1');
            $setup42_itempagedetails_contact_moreitems = $this->PFSAIssetControl('setup42_itempagedetails_contact_moreitems','','1');
            $setup42_itempagedetails_contact_phone = $this->PFSAIssetControl('setup42_itempagedetails_contact_phone','','1');
            $setup42_itempagedetails_contact_mobile = $this->PFSAIssetControl('setup42_itempagedetails_contact_mobile','','1');
            $setup42_itempagedetails_contact_email = $this->PFSAIssetControl('setup42_itempagedetails_contact_email','','1');
            $setup42_itempagedetails_contact_url = $this->PFSAIssetControl('setup42_itempagedetails_contact_url','','1');
            $setup42_itempagedetails_contact_form = $this->PFSAIssetControl('setup42_itempagedetails_contact_form','','1');
            $setup42_sociallinks = $this->PFSAIssetControl('setup42_sociallinks','','1');
            $setup42_cname = $this->PFSAIssetControl('setup42_cname','','1');
            if (empty($sb_contact) && $sb_contact != 'not') {
                $sb_contact = $this->PFSAIssetControl('sb_contact','','0');
            }
            $item_agents = '';
            if (!empty($the_post_id)) {
                $item_agents = get_post_meta( $the_post_id, 'webbupointfinder_item_agents', true );
            }
            
            
            $show_usercon = 0;
            $show_agentcon = 0;
            $item_agents_count = (!empty($item_agents))? 1 : 0 ;
            $show_agent_user_con = 0;

            if ($item_agents_count == 0) {
                $show_usercon = 1;
                $show_agentcon = 0;
                $author_id_defined = $the_author_id;
            }
            
            if ($item_agents_count > 0 || $formmode == 'agent' ) {
                $show_usercon = 0;
                $show_agentcon = 1;
                $formmode = 'agent';
                $author_id_defined = empty($item_agents)?$the_author_id:$item_agents;
            }

        if ($i > 1 
            && ($show_agentcon == 1 || $show_usercon == 1) 
            && ($setup42_itempagedetails_contact_phone == 1 
                || $setup42_itempagedetails_contact_mobile == 1 
                || $setup42_itempagedetails_contact_email == 1 
                || $setup42_itempagedetails_contact_moreitems == 1 
                || $setup42_itempagedetails_contact_photo == 1 
                || $setup42_itempagedetails_contact_url == 1 
                || $setup42_sociallinks == 1 
                || $setup42_cname == 1
                )
            )
        {
            /* Get Terms Permalink */
                $terms_conditions_template = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s ",'_wp_page_template','terms-conditions.php'), ARRAY_A);
                $terms_permalink = '#';
                if(count($terms_conditions_template) > 1){
                    foreach ($terms_conditions_template as $terms_conditions_template_single) {
                      $terms_permalink = get_permalink($terms_conditions_template_single['post_id']);
                    }
                }else{
                  if (isset($terms_conditions_template[0]['post_id'])) {
                      $terms_permalink = get_permalink($terms_conditions_template[0]['post_id']);
                  }
                }


            /**
            *Start: Check Connection with an Agent
            **/
            $user_agent_link = '';
            if ($item_agents_count == 0 && $formmode != 'agent' ) {
              $user_login = get_the_author_meta('user_login',$the_author_id);

              $user = get_user_by( 'login', $user_login );
            
              $user_agent_link = get_user_meta( $user->ID, 'user_agent_link', true );

              if(!empty($user_agent_link)){

                $setup3_pointposttype_pt8 = $this->PFSAIssetControl('setup3_pointposttype_pt8','','agents');

                $user_agent_link_correction = $wpdb->get_var( $wpdb->prepare("SELECT post_title FROM $wpdb->posts where post_type = %s and ID = %d",$setup3_pointposttype_pt8,$user_agent_link));
                
                if(!empty($user_agent_link_correction)){
                  $show_usercon = 0;
                  $show_agentcon = 1;
                  $show_agent_user_con = 1;
                  $formmode = 'agent';
                }

              }
             }
            /**
            *End: Check Connection with an Agent
            **/
            
            if ($formmode == 'user' && $show_agentcon == 0) {

                $setup3_pointposttype_pt1 = $this->PFSAIssetControl("setup3_pointposttype_pt1","","pfitemfinder");
                $user_posts = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM $wpdb->posts where post_type = %s and post_author = %d and post_status = %s",
                  $setup3_pointposttype_pt1,
                  $user->ID,
                  "publish"
                  )
                );

                $author_id_defined = $user->ID;
                $user_photo =  wp_get_attachment_image(get_user_meta( $user->ID, 'user_photo', true ),'medium');
                $user_description = get_user_meta( $user->ID, 'description', true );
                $user_phone = get_user_meta( $user->ID, 'user_phone', true );
                $user_mobile = get_user_meta( $user->ID, 'user_mobile', true );
                $user_facebook = get_user_meta( $user->ID, 'user_facebook', true );
                $user_twitter = get_user_meta( $user->ID, 'user_twitter', true );
                $user_linkedin = get_user_meta( $user->ID, 'user_linkedin', true );
                $contact_user_title = $user->nickname;
                $author_permalink_defined = get_author_posts_url($author_id_defined);
                $user_email = sanitize_email($user->user_email);
                $author_web_defined = esc_url($user->user_url);
               
            }

            if (empty($user_photo)) {
              $user_photo = '<img src="'.PFCOREELEMENTSURLPUBLIC.'images/empty_avatar.jpg"/>';
            }

            if ($formmode == 'agent' && $show_agentcon == 1) {

                $author_id_defined = !empty($user_agent_link) ? $user_agent_link : $author_id_defined;
                $item_agents_count = 1;
                
                $user_description = get_the_content($author_id_defined);
                $user_phone = esc_attr(get_post_meta( $author_id_defined, 'webbupointfinder_agent_tel', true ));
                $user_mobile = esc_attr(get_post_meta( $author_id_defined, 'webbupointfinder_agent_mobile', true ));
                $user_web = esc_attr(get_post_meta( $author_id_defined, 'webbupointfinder_agent_web', true ));
                $user_email = sanitize_email(get_post_meta( $author_id_defined, 'webbupointfinder_agent_email', true ));

                $user_facebook = esc_url(get_post_meta( $author_id_defined, 'webbupointfinder_agent_face', true ));
                $user_twitter = esc_url(get_post_meta( $author_id_defined, 'webbupointfinder_agent_twitter', true ));
                $user_linkedin = esc_url(get_post_meta( $author_id_defined, 'webbupointfinder_agent_linkedin', true ));


                $agent_featured_image =  wp_get_attachment_image_src( get_post_thumbnail_id( $author_id_defined ), 'full' );
                if (!empty($agent_featured_image)) {
                    $user_photo = '<img src="'.$agent_featured_image[0].'" width="'.$agent_featured_image[1].'" height="'.$agent_featured_image[2].'" alt="" />';
                }
                $contact_user_title = get_the_title($author_id_defined);

                $author_permalink_defined = get_permalink($author_id_defined);
                $author_web_defined = esc_url($user_web);
            }

            if($setup42_itempagedetails_contact_photo == 0){
              $user_photo = '';
            }

            if(!empty($user_facebook)){
                $user_socials['facebook'] = $user_facebook;
            }
            if(!empty($user_twitter)){
                $user_socials['twitter'] = $user_twitter;
            }
            if(!empty($user_linkedin)){
                $user_socials['linkedin'] = $user_linkedin;
            }

            $css_text = (count($user_socials) < 4)? ' col'.count($user_socials).'pfit':'';
            $user_socials_count = count($user_socials);

            $output .= '<div id="pf-itempage-sidebarinfo side"><div class="pf-row clearfix">';

            if ($sb_contact == 1) {
                $output .= '<div class="col-lg-12 col-md-12 col-sm-6 col-xs-12 pfcols">';
            }else{
                $output .= '<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 pfcols">';
            }
            

            if(!empty($user_photo)){
                $output .= '<div class="pf-itempage-sidebarinfo-photo">';
                $output .= '<a href="'.$author_permalink_defined.'"  class="pfcareauser">'.$user_photo.'</a>';
                $output .= '</div>';
            }else{
                $ex_text = ' pscontactnophoto';
            }

            
            if($user_socials_count > 0 && $setup42_sociallinks == 1){
                $output .= '<div class="pf-itempage-sidebarinfo-social">';
                foreach ($user_socials as $keyy => $valuey) {
                  $output .= '<div class="pf-sociallinks-item '.$keyy.' wpf-transition-all"><a href="'.$valuey.'" target="_blank"><i class="'.$this->pfsocialtoicon($keyy).'"></i></a></div>';
                }
                if($setup42_itempagedetails_contact_moreitems == 1){
                    if (!empty($locationofplace)) {
                        $output .= '<a id="pf-enquiry-trigger-button-author" class="pf-enquiry-form-ex pf-itempage-sidebarinfo-social-all button" data-pf-user="'.$author_id_defined.'">';
                        $output .= '<i class="fas fa-envelope"></i> '.esc_html__("CONTACT US",'pointfindercoreelements');
                        $output .= '</a>';
                    }else{
                        $output .= '<a href="'.$author_permalink_defined.'" class="pf-enquiry-form-ex pf-itempage-sidebarinfo-social-all button" data-pf-user="'.$author_id_defined.'">';
                        $output .= '<i class="fas fa-map-marked-alt"></i> '.esc_html__("All Listings",'pointfindercoreelements');
                        $output .= '</a>';
                    }
                }

                $output .= '</div>';
            }

            
              

              $output .= '<div class="pf-itempage-sidebarinfo-userdetails pfpos2'.$ex_text.'">
              <ul>';
              /*if($setup42_itempagedetails_contact_moreitems == 1 && empty($locationofplace)){
                $output .= '<li class="pf-itempage-sidebarinfo-elurl pf-itempage-sidebarinfo-elitem"><a href="'.$author_permalink_defined.'"><i class="fas fa-map-marked-alt"></i> '.esc_html__("All Listings",'pointfindercoreelements').'</a></li>';
              }*/
              if($setup42_cname == 1 && empty($locationofplace)){
                $output .= '<li class="pf-itempage-sidebarinfo-elurl pf-itempage-sidebarinfo-elitem"><a href="'.$author_permalink_defined.'" target="_blank" rel="nofollow"><i class="fas fa-user"></i> '.$contact_user_title.'</a></li>';
              }
              if(!empty($user_phone ) && $setup42_itempagedetails_contact_phone == 1){
                $output .= '<li class="pf-itempage-sidebarinfo-elurl pf-itempage-sidebarinfo-elitem"><a rel="nofollow" data-mxe="'.substr($user_phone,0,7).'" data-mx="'.substr($user_phone,7,(strlen($user_phone)-7)).'" data-mxt="tel" data-mxi="fas fa-phone-alt"><i class="fas fa-phone-alt"></i> '.substr($user_phone,0,7).'</a> <a class="pfclicktoshowphone button">'.esc_html__("Click to see","pointfindercoreelements").'</a></li>';
              }
              if(!empty($user_mobile) && $setup42_itempagedetails_contact_mobile == 1){
                $output .= '<li class="pf-itempage-sidebarinfo-elurl pf-itempage-sidebarinfo-elitem"><a rel="nofollow" data-mxe="'.substr($user_mobile,0,7).'" data-mx="'.substr($user_mobile,7,(strlen($user_mobile)-7)).'" data-mxt="tel" data-mxi="fas fa-mobile-alt"><i class="fas fa-mobile-alt"></i> '.substr($user_mobile,0,7).'</a> <a class="pfclicktoshowphone button">'.esc_html__("Click to see","pointfindercoreelements").'</a></li>';
              }
              if(!empty($user_email) && $setup42_itempagedetails_contact_email == 1){
                $output .= '<li class="pf-itempage-sidebarinfo-elurl pf-itempage-sidebarinfo-elitem"><a rel="nofollow" data-mxe="'.substr($user_email,0,7).'" data-mx="'.substr($user_email,7,(strlen($user_email)-7)).'" data-mxt="mailto" data-mxi="far fa-envelope"><i class="far fa-envelope"></i> '.substr($user_email,0,7).'</a> <a class="pfclicktoshowphone button">'.esc_html__("Click to see","pointfindercoreelements").'</a></li>';
              }
              if(!empty($author_web_defined) && $setup42_itempagedetails_contact_url == 1){
                $output .= '<li class="pf-itempage-sidebarinfo-elurl pf-itempage-sidebarinfo-elitem"><a href="'.$author_web_defined.'" target="_blank" rel="nofollow"><i class="fas fa-globe"></i> '.$author_web_defined.'</a></li>';
              }


              $output .= '</ul></div>';
              $output .= '</div>';
             
            
            if ($locationofplace == 'agentdetailpage') {
                $author_defined_content = get_the_content();
                if (!empty($author_defined_content)) {
                    $output .= '<div class="pfuserbiography col-lg-8 col-md-6 col-sm-6 col-xs-12 pfcols">'.do_shortcode($author_defined_content).'</div>';
                }
            }

            if ($locationofplace == 'authordetailpage') {
                $author_defined_content = get_the_author_meta('description',$author_id_defined);
                    if (!empty($author_defined_content)) {
                        $output .= '<div class="pfuserbiography col-lg-8 col-md-6 col-sm-6 col-xs-12 pfcols">'.do_shortcode($author_defined_content).'</div>';
                    }
            }

            if ($setup42_itempagedetails_contact_form == 1 && empty($locationofplace)) {

                if ($setup42_itempagedetails_contact_phone == 0 && $setup42_itempagedetails_contact_mobile == 0 && $setup42_itempagedetails_contact_email == 0 && $setup42_itempagedetails_contact_moreitems == 0 && $setup42_itempagedetails_contact_photo == 0 && $setup42_itempagedetails_contact_url == 0 && $setup42_sociallinks == 0 && $setup42_cname == 0) {
                $output .= '<div class="col-xs-12 pfcols">';
                }else{
                if ($sb_contact == 1) {
                    $output .= '<div class="col-lg-12 col-md-12 col-sm-6 col-xs-12 pfcols">';
                }else{
                    $output .= '<div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 pfcols">';
                }
                }
                
                if ($locationofplace == 'agentdetailpage') {
                    $author_defined_content = get_the_content();
                    if (!empty($author_defined_content)) {
                        $output .= '<div class="pfuserbiography">'.$author_defined_content.'</div>';
                    }
                }

                if ($locationofplace == 'authordetailpage') {
                    $author_defined_content = get_the_author_meta('description',$author_id_defined);
                    if (!empty($author_defined_content)) {
                        $output .= '<div class="pfuserbiography">'.$author_defined_content.'</div>';
                    }
                }

                $recaptcha_placeholder = $val1 = $val2 = $val3 = $rowval = '';

                if (class_exists('Pointfinder_reCaptcha_System')) {
                  $reCaptcha = new Pointfinder_reCaptcha_System();
                  $recaptcha_placeholder = $reCaptcha->create_recaptcha("pf-ajax-enquiry-form");
                }

                if (is_user_logged_in()) {
                  $current_user = wp_get_current_user();
                  $user_id = $current_user->ID;
                  $val2 = $current_user->user_email;
                  $rowval = '';
                  $val1 = get_user_meta($user_id, 'first_name', true);
                  $val1 .= ' '.get_user_meta($user_id, 'last_name', true);
                    if (empty($val1) || $val1 == ' ') {
                      $val1 = $current_user->user_login;
                      if (empty($val1) || $val1 == ' ') {
                        $val1 = 'user';
                      }
                    }
                    $val3 = get_user_meta($user_id, 'user_mobile', true);
                    if ($val3 == '') {$val3 = get_user_meta($user_id, 'user_phone', true);}
                    $namefield = '<section><label class="lbl-ui"><input type="text" name="name" class="input" placeholder="'.esc_html__('Name  & Surname','pointfindercoreelements').'" value="'.$val1.'" /></label></section>';
                    $emailfield = '<section><label class="lbl-ui"><input type="email" name="email" class="input" placeholder="'.esc_html__('Email Address','pointfindercoreelements').'" value="'.$val2.'"/></label></section>  ';
                    $phonefield = '<section><label class="lbl-ui"><input type="tel" name="phone" class="input" placeholder="'.esc_html__('Phone Number','pointfindercoreelements').'" value="'.$val3.'"/></label></section>';
                }else{
                      $namefield = '<section><label class="lbl-ui"><input type="text" name="name" class="input" placeholder="'.esc_html__('Name  & Surname','pointfindercoreelements').'"/></label></section>';
                      $emailfield = '<section><label class="lbl-ui"><input type="email" name="email" class="input" placeholder="'.esc_html__('Email Address','pointfindercoreelements').'"/></label></section>  ';
                      $phonefield = '<section><label class="lbl-ui"><input type="tel" name="phone" class="input" placeholder="'.esc_html__('Phone Number','pointfindercoreelements').'"/></label></section>  ';
                }



                $output .= '
                    <div class="golden-forms">
                    <div id="pfmdcontainer-overlaynew" class="pftrwcontainer-overlay"></div>
                    <form id="pf-ajax-enquiry-form">
                    <div class="pfsearchformerrors"><ul></ul><a class="button pfsearch-err-button"><i class="fas fa-times"></i> '.esc_html__('CLOSE','pointfindercoreelements').'</a></div>
                    <div class="row"'.$rowval.'><div class="col6 first">'.$namefield.'</div><div class="col6 last">'.$phonefield.'</div></div>
                    <div class="row"'.$rowval.'><div class="col12">'.$emailfield.'</div></div>
                    <section><label class="lbl-ui"><textarea name="msg" style="max-width:99.99%;" class="textarea no-resize" placeholder="'.esc_html__('Message','pointfindercoreelements').'" ></textarea></label></section>
                    <section>
                    <div style="position:relative;margin: 12px 0;">
                    <span class="goption upt">
                                      <label class="options">
                                          <input type="checkbox" id="pftermsofuser" name="pftermsofuser" value="1">
                                          <span class="checkbox"></span>
                                      </label>
                                      <label for="check1" class="upt1ch1">'.wp_sprintf(esc_html__( 'I have read the %s terms and conditions %s and accept them.', 'pointfindercoreelements' ),'<a href="'.$terms_permalink.'" class="pftermshortc2"><strong>','</strong></a>').'</label>
                                  </span>
                                  </div>
                                  </section>
                    '.$recaptcha_placeholder.'
                    <section><input type="hidden" name="itemid" class="input" value="'.get_the_id().'"/><button id="pf-ajax-enquiry-button" class="button blue">'.esc_html__('Send Message','pointfindercoreelements').'</button></section>
                    </form>
                    </div>
                    ';

                $output .= '</div>';

            }
            $output .= '</div>';
            $output .= '</div>';
        
        }

        return $output;

    }

    public function pointfinder_find_requestedfields($fieldname){

        global $pfsearchfields_options;

        $keyname = array_search($fieldname,$pfsearchfields_options);

        if($keyname != false){
            $keynameexp = explode('_',$keyname);
            if(array_search('posttax', $keynameexp)){
                $keycount = count($keynameexp);

                if ($keycount >= 2) {
                    $keycountx = $keycount - 1;
                    unset($keynameexp[0]);
                    unset($keynameexp[$keycountx]);
                }else{
                    unset($keynameexp[0]);
                }

                if (count($keynameexp) > 1) {
                    $new_keyname_exp = '';
                    $ik = 0;
                    $il = count($keynameexp)-1;

                    foreach ($keynameexp as $kvalue) {
                        if ($ik < $il) {
                            $new_keyname_exp .= $kvalue.'_';
                        }else{
                            $new_keyname_exp .= $kvalue;
                        }
                        $ik = $ik+1;
                    }

                    return $new_keyname_exp;
                }else{
                    foreach ($keynameexp as $keynvalue) {
                        return $keynvalue;
                    }

                }
            }else{
                return '';
            }

        }else{
            return '';
        }
    }

    public function PFControlEmptyArr($value)
    {
        if (is_array($value)) {
            if (count($value)>0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function PFPermalinkCheck()
    {
        $current_permalinkst = get_option('permalink_structure');
        if ($current_permalinkst == false || $current_permalinkst == '') {
            /* This using ? default. */
            return '&';
        } else {
            $current_permalinkst_last = substr($current_permalinkst, -1);
            if ($current_permalinkst_last == '%') {
                return '/?';
            } elseif ($current_permalinkst_last == '/') {
                return '?';
            }
        }
    }

    public function pfstring2BasicArray($string, $kv = ',')
    {
        $ka = array();
        if ($string != '') {
            if (strpos($string, $kv) != false) {
                $string_exp = explode($kv, $string);
                foreach ($string_exp as $s) {
                    $ka[]=$s;
                }
            } else {
                return array($string);
            }
        }
        return $ka;
    }

    public function pfstring2KeyedArray($string, $kv = '=')
    {
        $ka = array();
        if ($string != '') {
            foreach ($string as $s) {
                if ($s) {
                    if ($pos = strpos($s, $kv)) {
                        $ka[trim(substr($s, 0, $pos))] = trim(substr($s, $pos + strlen($kv)));
                    } else {
                        $ka[] = trim($s);
                    }
                }
            }
        }
        return $ka;
    }

    public function PFCheckStatusofVar($varname)
    {
        $setup1_slides = $this->PFSAIssetControl($varname, '', '');
        if (is_array($setup1_slides)) {
            $checkpfarray = count($setup1_slides);
            if ($checkpfarray == 1) {
                foreach ($setup1_slides as $setup1_slide) {
                    if (isset($setup1_slide['title'])) {
                        if ($setup1_slide['title'] != '') {
                            $pfstart = true;
                        } else {
                            $pfstart = false;
                        };
                    } else {
                        $pfstart = false;
                    };
                };
            } elseif ($checkpfarray < 1 || $checkpfarray == null) {
                $pfstart = false;
            } elseif ($checkpfarray > 1) {
                $pfstart = true;
            }
        } else {
            $pfstart = false;
        }
        return $pfstart;
    }

    public function pointfinderhex2rgbex($hex, $opacity='1.0')
    {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }


        return array('rgba' => 'rgba('.$r.','.$g.','.$b.','.$opacity.')','rgb'=> 'rgb('.$r.','.$g.','.$b.')');
    }

    public function pointfinderhex2rgb($hex, $opacity)
    {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }

        if ($opacity !='') {
            return 'rgba('.$r.','.$g.','.$b.','.$opacity.')';
        } else {
            return 'rgb('.$r.','.$g.','.$b.')';
        }
    }

    public function PFCleanArrayAttr($callback, $array)
    {
        $exclude_list = array('item_desc','item_title','item_mesrev', 'webbupointfinder_item_custombox1','webbupointfinder_item_custombox2','webbupointfinder_item_custombox3','webbupointfinder_item_custombox4','webbupointfinder_item_custombox5','webbupointfinder_item_custombox6');

        foreach ($array as $key => $value) {
            if (is_array($array[$key])) {
                if (!in_array($key, $exclude_list)) {
                    $array[$key] = $this->PFCleanArrayAttr($callback, $array[$key]);
                } else {
                    if ($key === 'item_mesrev') { 
                        $array[$key] = wp_kses_post($array[$key]);
                    } else {
                        $array[$key] = $array[$key];
                    }
                }
            } else {
                if (!in_array($key, $exclude_list)) {
                    $array[$key] = call_user_func($callback, $array[$key]);
                } else {
                    if ($key === 'item_mesrev') {
                        $array[$key] = wp_kses_post($array[$key]);
                    } else {
                        $array[$key] = $array[$key];
                    }
                }
            }
        }
        return $array;
    }
    public function PFCleanFilters($arrayvalue)
    {
        return sanitize_text_field($arrayvalue);
    }
    

    public function pointfinder_sanitize_output($buffer)
    {
        $search = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s','/<!--(.|\s)*?-->/');
        $replace = array('>','<','\\1','');
        $buffer = preg_replace($search, $replace, $buffer);
        return $buffer;
    }
    public function pointfinder_redirectionselect()
    {
        $homeurllink = site_url($path = '/');
        $pfmenu_perout = $this->PFPermalinkCheck();

        $redfreg = $this->PFSAIssetControl('redfreg', '', '1');
        $redfregc = $this->PFSAIssetControl('redfregc', '', '');
        $setup4_membersettings_dashboard = $this->PFSAIssetControl('setup4_membersettings_dashboard', '', site_url());
        $setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);


        $redirectpage = $homeurllink;
        switch ($redfreg) {
            case '1':
              $redirectpage = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=profile';
              break;
            case '2':
              $redirectpage = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems';
              break;
            case '3':
              $redirectpage = $homeurllink;
              break;
            case '4':
              $redirectpage = $setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=newitem';
              break;
            case '5':
              if (!empty($redfregc)) {
                  $redirectpage = $redfregc;
              } else {
                  $redirectpage = $homeurllink;
              }
          break;
        }

        return $redirectpage;
    }

    public function pf_redirect($url)
    {
        if (!headers_sent()) {
            header('Location: '.$url);
            exit;
        } else {
            echo '<script type="text/javascript">';
            echo 'window.location.href="'.$url.'";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
            echo '</noscript>';
            exit;
        }
    }

    public function pf_get_condition_color($value){
        $retunarr = array();
        if (!empty($value)) {
            $pointfindercondition_vars = get_option('pointfindercondition_vars');
            if (isset($pointfindercondition_vars[$value]['pf_condition_bg'])) {
                $retunarr['bg'] = $pointfindercondition_vars[$value]['pf_condition_bg'];
            }
            if (isset($pointfindercondition_vars[$value]['pf_condition_text'])) {
                $retunarr['cl'] = $pointfindercondition_vars[$value]['pf_condition_text'];
            }
        }
        return $retunarr;
    }

    public function pointfinder_features_tax_output_check($term_parent,$listting_id,$controlvalue){
        $output_check = '';

        if (!empty($term_parent) && !empty($listting_id)) {
            if (is_array($term_parent)) {
                if (in_array($listting_id, $term_parent)) {$output_check = 'ok';}else{$output_check = 'not';}
            }else{
                if ($listting_id == $term_parent) {$output_check = 'ok';}else{$output_check = 'not';}
            }
        }elseif (empty($term_parent) && empty($listting_id)) {
            $output_check = 'ok';
        }elseif (empty($term_parent) && !empty($listting_id)) {
            if ($controlvalue == 1) {$output_check = 'ok';}else{$output_check = 'not';}
        }elseif (!empty($term_parent) && empty($listting_id)) {
            $output_check = 'not';
        }
        return $output_check;
    }

    public function pft_insert_attachment($file_handler,$setthumb='false') {
        if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');

        $attach_id = media_handle_upload( $file_handler, 0);

        return $attach_id;
    }

    public function pf_get_term_top_most_parent($term_id, $taxonomy){
        $parent  = get_term_by( 'id', $term_id, $taxonomy);
        $k = 0;
        if (!empty($parent)) {
            while ($parent->parent != '0'){
                $term_id = $parent->parent;

                $parent  = get_term_by( 'id', $term_id, $taxonomy);
                $k++;
            }
        }
        if (isset($parent->term_id)) {
            return array('parent'=>$parent->term_id, 'level'=>$k);
        }else{
            return array('parent'=>'', 'level'=>$k);
        }
        
    }

    public function pf_get_term_top_parent($term_id, $taxonomy){
        $parent  = get_term_by( 'id', $term_id, $taxonomy);
        return $parent->parent;
    }

    public function pfsocialtoicon($name){
            switch ($name) {
                case 'facebook':
                    return 'fab fa-facebook-f';
                    break;

                case 'pinterest':
                    return 'fab fa-pinterest-square';
                    break;

                case 'twitter':
                    return 'fab fa-twitter';
                    break;

                case 'linkedin':
                    return 'fab fa-linkedin';
                    break;

                case 'google-plus':
                    return 'fab fa-google';
                    break;

                case 'dribbble':
                    return 'fab fa-dribbble-square';
                    break;

                case 'dropbox':
                    return 'fab fa-dropbox';
                    break;

                case 'flickr':
                    return 'fab fa-flickr';
                    break;

                case 'github':
                    return 'fab fa-github-square';
                    break;

                case 'instagram':
                    return 'fab fa-instagram';
                    break;

                case 'skype':
                    return 'fab fa-skype';
                    break;

                case 'rss':
                    return 'fas fa-rss-square';
                    break;

                case 'tumblr':
                    return 'fab fa-tumblr-square';
                    break;

                case 'vk':
                    return 'fab fa-vk';
                    break;

                case 'youtube':
                    return 'fab fa-youtube';
                    break;
            }
    }

    public function PFIF_DetailText_ld($id,$setup22_searchresults_hide_lt,$post_listing_typeval='',$listing_pstyle_meta=array(),$pf_from=''){

        $pfstart = $this->PFCheckStatusofVar('setup1_slides');
        $output_text = array();
        if($pfstart == true){
            $if_detailtext = '';

            $setup1_slides = $this->PFSAIssetControl('setup1_slides','','');
            if(is_array($setup1_slides)){
                $i=1;$price_field_done = apply_filters( 'pointfinder_price_field_filter', false );
                foreach ($setup1_slides as &$value) {

                    $customfield_infocheck = $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_linfowindow','','0');
                    $available_fields = array(1,2,3,4,5,7,8,14,15);


                    if(in_array($value['select'], $available_fields) && $customfield_infocheck != 0){

                        $PFTMParent = '';
                        $ShowField = true;

                        if(!empty($post_listing_typeval)){
                            $PFTMParent = $this->pf_get_term_top_most_parent($post_listing_typeval,'pointfinderltypes');
                            $PFTMParent = (isset($PFTMParent['parent']))?$PFTMParent['parent']:'';
                        }

                        $ParentItem = $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_parent','','0');

                        if($this->PFControlEmptyArr($ParentItem) && class_exists('SitePress')){
                            $NewParentItemArr = array();
                            foreach ($ParentItem as $ParentItemSingle) {
                                $NewParentItemArr[] = apply_filters('wpml_object_id', $ParentItemSingle, 'pointfinderltypes', TRUE);
                            }
                            $ParentItem = $NewParentItemArr;
                        }


                        /*If it have a parent element*/
                        if($this->PFControlEmptyArr($ParentItem)){

                            if(class_exists('SitePress')) {
                                $PFCLang = $this->PF_current_language();
                                foreach ($ParentItem as $key => $valuex) {
                                    $ParentItem[$key] = apply_filters('wpml_object_id',$valuex,'pointfinderltypes',true,$PFCLang);
                                }
                            }

                            $PFLTCOVars = get_option('pointfinderltypes_covars');

                            if (isset($PFLTCOVars[$PFTMParent]['pf_subcatselect'])) {
                                if ($PFLTCOVars[$PFTMParent]['pf_subcatselect'] == 1) {
                                    $post_listing_typeval = $PFTMParent;
                                }
                            }

                            if(in_array($post_listing_typeval, $ParentItem) ){
                                $ShowField = true;
                            }else{
                                $ShowField = false;
                            }
                        }

                        if ($ShowField) {

                            $PF_CF_Val = new PF_CF_Val($id);
                            $ClassReturnVal = $PF_CF_Val->GetValue($value['url'],$id,$value['select'],$value['title'],1,$price_field_done);

                            if($ClassReturnVal != ''){
                                if(strpos($ClassReturnVal,"pf-price") != false && $price_field_done == false){
                                    $output_text['priceval'] = $ClassReturnVal;
                                    $price_field_done = true;
                                }elseif(strpos($ClassReturnVal,"pf-price") != false && $price_field_done == true){
                                    $ClassReturnVal = str_replace("pf-price","pf-onlyitem",$ClassReturnVal);
                                    $if_detailtext .= $ClassReturnVal;
                                }else{
                                    $if_detailtext .= $ClassReturnVal;
                                }

                            }
                        }

                    }

                }
            }

            $output_text['content'] = $if_detailtext;

            if($setup22_searchresults_hide_lt == '0'){
                $output_text['ltypes']= '<span class="pflistingitem-ltype">'.$this->GetPFTermInfoX($id,'pointfinderltypes','',$listing_pstyle_meta,$pf_from).'</span>';
            }
        }
        unset($PF_CF_Val);

        return $output_text;
    }

    public function PFIF_CheckItemsParent_ld($slug){
        $RelationFieldName = 'setupcustomfields_'.$slug.'_parent';
        $ParentItem = $this->PFCFIssetControl($RelationFieldName,'','');
        if($ParentItem != '' && $ParentItem != '0'){return $ParentItem;}else{return 'none';}
    }
    

    public function PFIF_CheckFormVarsforExist_ld($searchvars,$itemvar = array()){
        if($itemvar != 'none' && count($itemvar)>0){
            foreach($searchvars as $searchvar){
                if(in_array($searchvar,$itemvar)){return true;}
            }
        }
    }

    public function PFIF_SortFields_sg($searchvars,$orderarg_value = ''){
        $pfstart = $this->PFCheckStatusofVar('setup1_slides');
        $if_sorttext = '';

        if($pfstart == true){

            $available_fields = array(1,2,3,4,5,7,8,14,15);
            $setup1_slides = $this->PFSAIssetControl('setup1_slides','','');

            foreach ($setup1_slides as $value) {
                
                $Parentcheckresult = $this->PFIF_CheckItemsParent_ld($value['url']);

                if(is_array($searchvars)){
                    $res = $this->PFIF_CheckFormVarsforExist_ld($searchvars,$Parentcheckresult);
                }else{
                    $res = false;
                }

                $customfield_sortcheck = $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_sortoption','','0');
                $sortnamecheck = $this->PFCFIssetControl('setupcustomfields_'.$value['url'].'_sortname','','');

                if(empty($sortnamecheck)){$sortnamecheck = $value['title'];}

                if($Parentcheckresult == 'none'){

                    if(in_array($value['select'], $available_fields) && $customfield_sortcheck != 0){
                        if ($value['select'] == 4) {
                            $if_sorttext .= '<optgroup label="'.$sortnamecheck.'">';
                            $if_sorttext .= '<option value="'.$value['url'].'|ASC" '.selected( $value['url'].'|ASC', $orderarg_value).'>'.$sortnamecheck.': '.esc_html__('Lowest to Highest','pointfindercoreelements').'</option>';
                            $if_sorttext .= '<option value="'.$value['url'].'|DESC" '.selected( $value['url'].'|DESC', $orderarg_value).'>'.$sortnamecheck.': '.esc_html__('Highest to Lowest','pointfindercoreelements').'</option>';
                            $if_sorttext .= '</optgroup>';
                        } elseif($value['select'] == 15) {
                            $if_sorttext .= '<optgroup label="'.$sortnamecheck.'">';
                            $if_sorttext .= '<option value="'.$value['url'].'|ASC" '.selected( $value['url'].'|ASC', $orderarg_value).'>'.$sortnamecheck.': '.esc_html__('Earlier','pointfindercoreelements').'</option>';
                            $if_sorttext .= '<option value="'.$value['url'].'|DESC" '.selected( $value['url'].'|DESC', $orderarg_value).'>'.$sortnamecheck.': '.esc_html__('Later','pointfindercoreelements').'</option>';
                            $if_sorttext .= '</optgroup>';
                        } else {
                            $if_sorttext .= '<optgroup label="'.$sortnamecheck.'">';
                            $if_sorttext .= '<option value="'.$value['url'].'|ASC" '.selected( $value['url'].'|ASC', $orderarg_value).'>'.$sortnamecheck.': '.esc_html__('From A to Z','pointfindercoreelements').'</option>';
                            $if_sorttext .= '<option value="'.$value['url'].'|DESC" '.selected( $value['url'].'|DESC', $orderarg_value).'>'.$sortnamecheck.': '.esc_html__('From Z to A','pointfindercoreelements').'</option>';
                            $if_sorttext .= '</optgroup>';
                        }
                        
                    }
                }else{
                    if($res == true){
                        if(in_array($value['select'], $available_fields) && $customfield_sortcheck != 0){
                           if ($value['select'] == 4) {
                                $if_sorttext .= '<optgroup label="'.$sortnamecheck.'">';
                                $if_sorttext .= '<option value="'.$value['url'].'|ASC" '.selected( $value['url'].'|ASC', $orderarg_value).'>'.$sortnamecheck.': '.esc_html__('Lowest to Highest','pointfindercoreelements').'</option>';
                                $if_sorttext .= '<option value="'.$value['url'].'|DESC" '.selected( $value['url'].'|DESC', $orderarg_value).'>'.$sortnamecheck.': '.esc_html__('Highest to Lowest','pointfindercoreelements').'</option>';
                                $if_sorttext .= '</optgroup>';
                            } elseif($value['select'] == 15) {
                                $if_sorttext .= '<optgroup label="'.$sortnamecheck.'">';
                                $if_sorttext .= '<option value="'.$value['url'].'|ASC" '.selected( $value['url'].'|ASC', $orderarg_value).'>'.$sortnamecheck.': '.esc_html__('Earlier','pointfindercoreelements').'</option>';
                                $if_sorttext .= '<option value="'.$value['url'].'|DESC" '.selected( $value['url'].'|DESC', $orderarg_value).'>'.$sortnamecheck.': '.esc_html__('Later','pointfindercoreelements').'</option>';
                                $if_sorttext .= '</optgroup>';
                            } else {
                                $if_sorttext .= '<optgroup label="'.$sortnamecheck.'">';
                                $if_sorttext .= '<option value="'.$value['url'].'|ASC" '.selected( $value['url'].'|ASC', $orderarg_value).'>'.$sortnamecheck.': '.esc_html__('From A to Z','pointfindercoreelements').'</option>';
                                $if_sorttext .= '<option value="'.$value['url'].'|DESC" '.selected( $value['url'].'|DESC', $orderarg_value).'>'.$sortnamecheck.': '.esc_html__('From Z to A','pointfindercoreelements').'</option>';
                                $if_sorttext .= '</optgroup>';
                            }
                        }
                    }

                }

            }

        }
        return $if_sorttext;
    }

    public function GetPFTermInfo($id, $taxonomy,$pflang = ''){
        $termnames = '';
        $postterms = get_the_terms( $id, $taxonomy );
        $st22srlinklt = $this->PFSAIssetControl('st22srlinklt','','1');
        $pr_it_v = $this->PFSAIssetControl('pr_it_v','','0');

        if($postterms != false){
           
            $post_termcount = count($postterms);
            $i = 1;

            foreach($postterms as $postterm){
                if (isset($postterm->term_id)) {
                    if(class_exists('SitePress')) {
                        if (!empty($pflang)) {
                            $term_idx = apply_filters('wpml_object_id',$postterm->term_id,$taxonomy,true,$pflang);
                        }else{
                            $term_idx = apply_filters('wpml_object_id',$postterm->term_id,$taxonomy,true,$this->PF_current_language());
                        }
                    } else {
                        $term_idx = $postterm->term_id;
                    }

                    $terminfo = get_term( $term_idx, $taxonomy );

                    if (!empty($terminfo->parent) && $pr_it_v == 1) {

                        $terminfo_parent = get_term( $terminfo->parent, $taxonomy );

                        if (!empty($terminfo_parent->parent)) {
                            $terminfo_parent2 = get_term( $terminfo_parent->parent, $taxonomy );

                            $term_link_parent2 = get_term_link( $terminfo_parent->parent, $taxonomy );
                            if (is_wp_error($term_link_parent2) === true) {$term_link_parent2 = '#';}

                            $term_info_parent2_name = $terminfo_parent2->name;
                            if (is_wp_error($term_info_parent2_name) === true) {$term_info_parent2_name = '';}

                            if ($st22srlinklt == 1) {
                                $termnames .= '<a href="'.$term_link_parent2.'">'.$term_info_parent2_name.'</a>';
                            }else{
                                $termnames .= '<span class="pfdetail-ftext-nolink">'.$term_info_parent2_name.'</span>';
                            }

                            if (($post_termcount > 1 && $i != $post_termcount) || $pr_it_v == 1) {
                                $termnames .= ' / ';
                            }
                        }

                        $term_link_parent = get_term_link( $terminfo->parent, $taxonomy );
                        if (is_wp_error($term_link_parent) === true) {$term_link_parent = '#';}

                        $term_info_parent_name = $terminfo_parent->name;
                        if (is_wp_error($term_info_parent_name) === true) {$term_info_parent_name = '';}

                        if ($st22srlinklt == 1) {
                            $termnames .= '<a href="'.$term_link_parent.'">'.$term_info_parent_name.'</a>';

                        }else{
                            $termnames .= '<span class="pfdetail-ftext-nolink">'.$term_info_parent_name.'</span>';
                        }

                        if (($post_termcount > 1 && $i != $post_termcount) || $pr_it_v == 1) {
                            $termnames .= ' / ';
                        }
                    }

                    $term_link = get_term_link( $term_idx, $taxonomy );
                    if (is_wp_error($term_link) === true) {$term_link = '#';}

                    $term_info_name = $terminfo->name;
                    if (is_wp_error($term_info_name) === true) {$term_info_name = '';}

                    /*if(!empty($termnames)){$termnames .= '';}*/

                    if ($st22srlinklt == 1) {
                        $termnames .= '<a href="'.$term_link.'">'.$term_info_name.'</a>';
                    }else{
                        $termnames .= '<span class="pfdetail-ftext-nolink">'.$term_info_name.'</span>';
                    }

                    if ($post_termcount > 1 && $i != $post_termcount) {
                        $termnames .= ' / ';
                    }

                }
                $i = $i + 1;
            }
        }
        return $termnames;
    }

    public function GetPFTermInfoH($id, $taxonomy,$pflang = '',$type){
        $termnames = '';
        $postterms = wp_get_post_terms( $id, $taxonomy,array('fields' => 'all','orderby'=>'term_order','order'=>'ASC'));

        $st22srlinklt = $this->PFSAIssetControl('st22srlinklt','','1');
        $pr_it_v = $this->PFSAIssetControl('pr_it_v','','0');
        
        if(!is_wp_error( $postterms )){
            $postterms_count = count($postterms);
            $i = 1;
            foreach($postterms as $postterm){
                if (isset($postterm->term_id)) {
                    if(class_exists('SitePress')) {
                        if (!empty($pflang)) {
                            $term_idx = apply_filters('wpml_object_id',$postterm->term_id,$taxonomy,true,$pflang);
                        }else{
                            $term_idx = apply_filters('wpml_object_id',$postterm->term_id,$taxonomy,true,$this->PF_current_language());
                        }

                        $postterm = get_term( $term_idx, $taxonomy );
                    }

                    if (!empty($postterm->parent) && $pr_it_v == 1) {

                        $terminfo_parent = get_term( $postterm->parent, $taxonomy );

                        if (!empty($terminfo_parent->parent)) {
                            $terminfo_parent2 = get_term( $terminfo_parent->parent, $taxonomy );

                            $term_link_parent2 = get_term_link( $terminfo_parent->parent, $taxonomy );
                            if (is_wp_error($term_link_parent2) === true) {$term_link_parent2 = '#';}

                            $term_info_parent2_name = $terminfo_parent2->name;
                            if (is_wp_error($term_info_parent2_name) === true) {$term_info_parent2_name = '';}

                            if ($st22srlinklt == 1) {
                                $termnames .= '<a href="'.$term_link_parent2.'">'.$term_info_parent2_name.'</a>';
                            }else{
                                $termnames .= '<span class="pfdetail-ftext-nolink">'.$term_info_parent2_name.'</span>';
                            }

                            if (($postterms_count > 1 && $i != $postterms_count) || $pr_it_v == 1) {
                                $termnames .= ' / ';
                            }
                        }

                        $term_link_parent = get_term_link( $postterm->parent, $taxonomy );
                        if (is_wp_error($term_link_parent) === true) {$term_link_parent = '#';}

                        $term_info_parent_name = $terminfo_parent->name;
                        if (is_wp_error($term_info_parent_name) === true) {$term_info_parent_name = '';}

                        if ($st22srlinklt == 1) {
                            $termnames .= '<a href="'.$term_link_parent.'">'.$term_info_parent_name.'</a>';

                        }else{
                            $termnames .= '<span class="pfdetail-ftext-nolink">'.$term_info_parent_name.'</span>';
                        }

                        if (($postterms_count > 1 && $i != $postterms_count) || $pr_it_v == 1) {
                            $termnames .= ' / ';
                        }
                    }

                    $term_link = get_term_link( $postterm->term_id, $taxonomy );
                    $term_name = $postterm->name;

                    if (is_wp_error($term_link) === true) {$term_link = '#';}
                    if (is_wp_error($term_name) === true) {$term_name = '';}
                    if ($type == 2) {
                        $termnames .= '<a href="'.$term_link.'">'.$term_name.'</a>';
                    }else{
                        $termnames .= '<a href="'.$term_link.'">'.$term_name.'</a>';
                    }

                    if ($postterms_count > 1 && $i != $postterms_count) {
                        $termnames .= ' / ';
                    }

                    $i++;
                }
            }
        }
        return $termnames;
    }

    public function GetPFTermInfoX($id, $taxonomy,$pflang = '',$listing_pstyle_meta = array(),$pf_from = ''){
        $termnames = '';
        $postterms = get_the_terms( $id, $taxonomy );
        

        if(!is_wp_error($postterms)){
            if($postterms != false){
                for ($i = 0; $i < 1; $i++) {
                     if (isset($postterms[$i]->term_id)) {
                        if(class_exists('SitePress')) {
                            if (!empty($pflang)) {
                                $term_idx = apply_filters('wpml_object_id',$postterms[$i]->term_id,$taxonomy,true,$pflang);
                            }else{
                                $term_idx = apply_filters('wpml_object_id',$postterms[$i]->term_id,$taxonomy,true,$this->PF_current_language());
                            }
                        } else {
                            $term_idx = $postterms[$i]->term_id;
                        }
                      
                        $term_link = get_term_link( $term_idx, $taxonomy );
                        if (is_wp_error($term_link) === true) {$term_link = '#';}

                        $term_info_name = $postterms[$i]->name;
                        $listing_type_point_settings = '';

                        if (is_wp_error($term_info_name) === true) {$term_info_name = '';}

                        $st22srlinklt = $this->PFSAIssetControl('st22srlinklt','','1');
           
                        if (!empty($listing_pstyle_meta)  && $taxonomy == 'pointfinderltypes') {
                            if (isset($listing_pstyle_meta[$term_idx])) {
                                $listing_type_point_settings = $listing_pstyle_meta[$term_idx];
                            }else{
                                if (!empty($postterms[$i]->parent)) {
                                    if (isset($listing_pstyle_meta[$postterms[$i]->parent])) {
                                        $listing_type_point_settings = $listing_pstyle_meta[$postterms[$i]->parent];
                                    }else{
                                        $term_idx_parent = get_term( $postterms[$i]->parent, $taxonomy );
                                        if (isset($listing_pstyle_meta[$term_idx_parent->term_id])) {
                                            $listing_type_point_settings = $listing_pstyle_meta[$term_idx_parent->term_id];
                                        } 
                                    }
                                }
                            }
                        }else{
                            $listing_type_point_settings = '';
                        }

                            
                        $st8_npsys = $this->PFASSIssetControl('st8_npsys','',0);


                        if ($st8_npsys == 1 && $taxonomy == 'pointfinderltypes' && !empty($listing_type_point_settings)) {

                            $icon_type_text = '<span class="pficonltype" style="background-color:'.$listing_type_point_settings['cpoint_bgcolor'].'" >';
                            $default_type_text = '<span class="noiconpffound" style="color:'.$listing_type_point_settings['cpoint_bgcolorinner'].';">'.mb_substr($postterms[$i]->name, 0, 1, "UTF-8").'</span> ';

                            if (isset($listing_type_point_settings['cpoint_type'])) {
                                
                                if ($listing_type_point_settings['cpoint_type'] == "1") {
                                    $icon_bg_image = $listing_type_point_settings['cpoint_bgimage'];
                                    $icon_bg_image = wp_get_attachment_image_src( $icon_bg_image[0], $size = 'thumbnail');

                                    if (isset($icon_bg_image[0])) {
                                        $height_calculated = $icon_bg_image[1];
                                        $width_calculated = $icon_bg_image[2];
                                        
                                        $icon_type_text .= '<span class="imageiconpffound" style="background-color:'.$listing_type_point_settings['cpoint_bgcolor'].';background-image:url('.$icon_bg_image[0].');"></span> ';
                                    } else {
                                         $icon_type_text .= $default_type_text;
                                    }
                                }else if($listing_type_point_settings['cpoint_type'] == "2"){
                                    if (!isset($listing_type_point_settings['cpoint_iconnamefs'])) {
                                        $icon_type_text .= '<i class="'.$listing_type_point_settings['cpoint_iconname'].'" style="color:'.$listing_type_point_settings['cpoint_bgcolorinner'].'"></i> ';
                                    }else{
                                        $icon_type_text .= '<i class="'.$listing_type_point_settings['cpoint_iconnamefs'].'" style="color:'.$listing_type_point_settings['cpoint_bgcolorinner'].'"></i> ';
                                    }

                                }else{
                                    $icon_type_text .= $default_type_text;
                                }
                            }else{
                                $icon_type_text .= $default_type_text;
                            }
                            $icon_type_text .= '</span>';
                            

                            if ($st22srlinklt == 1) {
                                $termnames .= '<a href="'.$term_link.'" class="pflticon" title="'.$term_info_name.'">'.$icon_type_text;
                                $termnames .= '<span class="pflticonname">'.$term_info_name.'</span></a>';
                            }else{
                                $termnames .= '<span class="pflticon" title="'.$term_info_name.'">'.$icon_type_text.'</span>';
                                $termnames .= '<span class="pfdetail-ftext-nolink">'.$term_info_name.'</span>';
                            } 
                        }elseif($st8_npsys != 1 && $taxonomy == 'pointfinderltypes' && empty($listing_type_point_settings)){
                            $icon_type = $this->PFPFIssetControl('pscp_'.$term_idx.'_type','','0');
                            $icon_bg_colorinner = $this->PFPFIssetControl('pscp_'.$term_idx.'_bgcolorinner','','#000000');
                            $icon_bg_color = $this->PFPFIssetControl('pscp_'.$term_idx.'_bgcolor','','#b00000');
                            $icon_type_text = '<span class="pficonltype" style="background-color:'.$icon_bg_color.'">';
                            $default_type_text = '<span class="noiconpffound" style="color:'.$icon_bg_colorinner.';">'.mb_substr($postterms[$i]->name, 0, 1, "UTF-8").'</span> ';

                            if (!empty($icon_type)) {
                                    
                                if ($icon_type == "1") {
                                    $icon_bg_image = $this->PFPFIssetControl('pscp_'.$term_idx.'_bgimage','','');
                                    $icon_bg_image = wp_get_attachment_image_src( $icon_bg_image[0], $size = 'thumbnail');
                                    
                                    if (isset($icon_bg_image[0])) {
                                        $height_calculated = $icon_bg_image[1];
                                        $width_calculated = $icon_bg_image[2];
                                        
                                        $icon_type_text .= '<span class="imageiconpffound" style="background-color:'.$icon_bg_color.';background-image:url('.$icon_bg_image[0].');"></span> ';
                                    } else {
                                         $icon_type_text .= $default_type_text;
                                    }
                                }else if($icon_type == "0"){
                                    $icon_namefs = $this->PFPFIssetControl('pscp_'.$term_idx.'_iconfs','','');
                                    $icon_name = $this->PFPFIssetControl('pscp_'.$term_idx.'_iconname','','');
                                    if (!empty($icon_namefs)) {
                                        $icon_type_text .= '<i class="'.$icon_namefs.'" style="color:'.$icon_bg_colorinner.'"></i> ';
                                    }else{
                                        $icon_type_text .= '<i class="'.$icon_name.'" style="color:'.$icon_bg_colorinner.'"></i> ';
                                    }

                                }else{
                                    $icon_type_text .= $default_type_text;
                                }
                            }else{
                                $icon_type_text .= $default_type_text;
                            }

                            $icon_type_text .= '</span>';
                            

                            if ($st22srlinklt == 1) {
                                $termnames .= '<a href="'.$term_link.'" class="pflticon" title="'.$term_info_name.'">'.$icon_type_text;
                                $termnames .= '<span class="pflticonname">'.$term_info_name.'</span></a>';
                            }else{
                                $termnames .= '<span class="pflticon" title="'.$term_info_name.'">'.$icon_type_text.'</span>';
                                $termnames .= '<span class="pfdetail-ftext-nolink">'.$term_info_name.'</span>';
                            } 
                        }

                        
                        if (!empty($listing_pstyle_meta)  && $taxonomy == 'pointfinderlocations') {

                            $style_text = " style='";
                            if (isset($listing_pstyle_meta[$term_idx]['pf_catbg_of_listing'])) {
                                $style_text .= "background-color:".$listing_pstyle_meta[$term_idx]['pf_catbg_of_listing'].";";
                            }

                            if (isset($listing_pstyle_meta[$term_idx]['pf_cattext_of_listing'])) {
                                $style_text .= "color:".$listing_pstyle_meta[$term_idx]['pf_cattext_of_listing'].";";
                            }

                            $style_text .= "'";

                            $icon_type_text = '<span class="pficonltype pficonloc"'.$style_text.' title="'.$term_info_name.'">';

                            $icon_type_text .= '<i class="fas fa-map-marker-alt"></i> ';


                            if ($st22srlinklt == 1) {
                                $icon_type_text .= '<a href="'.$term_link.'" class="pficonloctxt"></a>';
                            }else{
                                $icon_type_text .= '<span class="pfdetail-ftext-nolink pficonloctxt"></span>';
                            }

                            $icon_type_text .= '</span>';

                            $termnames .= $icon_type_text;
                        }


                    }
                }
            }
        }
        return $termnames;
    }

    public function PFIFPageNumbers(){
        $output = array(4,6,8,10,12,16,20,30,50,75);
        return $output;
    }

    public function PFIF_CheckFieldisNumeric_ld($pfg_orderby){
        $setup1_slides = $this->PFSAIssetControl('setup1_slides','','');
        $text = false;
        foreach ($setup1_slides as &$value) {
            if($value['select'] == 4 && strcmp($value['url'], $pfg_orderby) == 0){
                $text = true;
            }
        }
        return $text;
    }

    public function pfcalculatebounds( $lat, $lng, $distance = 50, $unit = 'mi' ) {
        if( $unit == 'km' ) { $radius = 6371.009; }
        elseif ( $unit == 'mi' ) { $radius = 3958.761; }

        $maxLat = ( float ) $lat + rad2deg( $distance / $radius );
        $minLat = ( float ) $lat - rad2deg( $distance / $radius );
        $maxLng = ( float ) $lng + rad2deg( $distance / $radius) / cos( deg2rad( ( float ) $lat ) );
        $minLng = ( float ) $lng - rad2deg( $distance / $radius) / cos( deg2rad( ( float ) $lat ) );

        $max_min_values = array(
            'max_latitude' => $maxLat,
            'min_latitude' => $minLat,
            'max_longitude' => $maxLng,
            'min_longitude' => $minLng
        );

        return $max_min_values;
    }


    public function pfcalculatedistance($lat1, $lon1, $lat2, $lon2, $unit) {
      if (($lat1 == $lat2) && ($lon1 == $lon2)) {
        return 0;
      }
      else {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
          return ($miles * 1.609344);
        } else if ($unit == "N") {
          return ($miles * 0.8684);
        } else {
          return $miles;
        }
      }
    }

    public function pointfinder_sortby_selector($pfg_orderby){
        $pfg_order = '';
        switch ($pfg_orderby) {
            case 'title_az':
                $pfg_orderby = 'title';
                $pfg_order = 'ASC';
                break;
            
            case 'title_za':
                $pfg_orderby = 'title';
                $pfg_order = 'DESC';
                break;

            case 'date_az':
                $pfg_orderby = 'date';
                $pfg_order = 'DESC';
                break;

            case 'date_za':
                $pfg_orderby = 'date';
                $pfg_order = 'ASC';
                break;

            case 'rand':
                $pfg_orderby = 'rand';
                $pfg_order = '';
                break;

            case 'nearby':
                $pfg_orderby = 'distance';
                $pfg_order = 'ASC';
                break;

            case 'mviewed':
                $pfg_orderby = 'mviewed';
                $pfg_order = 'DESC';
                break;

            case 'reviewcount_az':
                $pfg_orderby = 'reviewcount';
                $pfg_order = 'DESC';
                break;

            case 'reviewcount_za':
                $pfg_orderby = 'reviewcount';
                $pfg_order = 'ASC';
                break;

            case 'mreviewed_az':
                $pfg_orderby = 'mreviewed';
                $pfg_order = 'DESC';
                break;

            case 'mreviewed_za':
                $pfg_orderby = 'mreviewed';
                $pfg_order = 'ASC';
                break;
        }

        return array($pfg_orderby,$pfg_order);
    }

}
}