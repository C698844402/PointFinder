<?php 
if (!class_exists('PointFinderModalSYS')) {



class PointFinderModalSYS extends Pointfindercoreelements_AJAX
{
    public function __construct(){}

    public function pf_ajax_modalsystem(){
      check_ajax_referer( 'pfget_modalsystem', 'security'); 
    	header('Content-Type: text/html; charset=UTF-8;');
      if(isset($_POST['formtype']) && $_POST['formtype']!=''){$formtype = esc_attr($_POST['formtype']);}
      
      $lang_c = '';
      if(isset($_POST['lang']) && $_POST['lang']!=''){
        $lang_c = sanitize_text_field($_POST['lang']);
      }

      if(class_exists('SitePress')) {
        if (!empty($lang_c)) {
          do_action( 'wpml_switch_language', $lang_c );
        }
      }

      if(isset($_POST['itemid']) && $_POST['itemid']!=''){
        $itemid = esc_attr($_POST['itemid']);
        $itemname = ($itemid != '') ? get_the_title($itemid) : '' ;
      }else{
        $itemid = $itemname = '';
      }

      $recaptcha_placeholder = '';

      if(isset($_POST['userid']) && $_POST['userid']!=''){$userid = esc_attr($_POST['userid']);}else{$userid = '';}

      global $wpdb;

      $terms_conditions_template = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s ",'_wp_page_template','terms-conditions.php'), ARRAY_A);
      $terms_permalink = '';
      if(count($terms_conditions_template) > 1){
          foreach ($terms_conditions_template as $terms_conditions_template_single) {
            $terms_permalink = $terms_conditions_template_single['post_id'];
          }
      }else{
        if (isset($terms_conditions_template[0]['post_id'])) {
            $terms_permalink = $terms_conditions_template[0]['post_id'];
        }
      }
      

    	switch($formtype){

        case 'quickpreview':

         $pfstviewcor = get_post_meta($itemid, 'webbupointfinder_items_location', true);
         $data_values = "";
         if (!empty($pfstviewcor)) {
           $pfstviewcor = explode(',', $pfstviewcor);

           if (count($pfstviewcor) >= 2) {
            if (!empty($pfstviewcor[0]) && !empty($pfstviewcor[1])) {
              $data_values .= ' data-welat="'.$pfstviewcor[0].'"';
              $data_values .= ' data-welng="'.$pfstviewcor[1].'"';
            }
           }
         }
          $stp5_mapty = $this->PFSAIssetControl('stp5_mapty','',1);
          $setup42_searchpagemap_zoom = $this->PFSAIssetControl('setup42_searchpagemap_zoom','','20');
          $data_values .= ' data-mtype="'.$stp5_mapty.'"';
          $data_values .= ' data-zoom="'.$setup42_searchpagemap_zoom.'"';
          $we_special_key = '';
          switch ($stp5_mapty) {
            case 1:
              $we_special_key = $this->PFSAIssetControl('setup5_map_key','','');
              break;

            case 3:
              $we_special_key = $this->PFSAIssetControl('stp5_mapboxpt','','');
              break;

            case 5:
              $wemap_here_appid = $this->PFSAIssetControl('wemap_here_appid','','');
              $wemap_here_appcode = $this->PFSAIssetControl('wemap_here_appcode','','');
              $data_values .= ' data-hereappid="'.$wemap_here_appid.'"';
              $data_values .= ' data-hereappcode="'.$wemap_here_appcode.'"';
              break;

            case 6:
              $we_special_key = $this->PFSAIssetControl('wemap_bingmap_api_key','','');
              break;

            case 4:
              $we_special_key = $this->PFSAIssetControl('wemap_yandexmap_api_key','','');
              break;
          }
          $data_values .= ' data-key="'.$we_special_key.'"';

          $post_object = get_post( $itemid );
          ?>
            <div class="pfquickpreviewmain">
              <div class="pf-row">
                <div class="col-lg-6">
                  <div class="pfquickclose"><i class="far fa-times-circle"></i></div>
                  <div class="pfquickviewhead">
                    <div class="pfoverlay"></div>
                    <?php if (has_post_thumbnail( $itemid ) ): ?>
                    <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $itemid ), 'single-post-thumbnail' ); ?>
                    <div id="custom-bg" style="background-image: url('<?php echo $image[0]; ?>')">

                    </div>
                  <?php endif; ?>
                  </div>
                  <div class="pfquickviewtitle"><?php echo $this->get_the_title_pf($post_object);?></div>
                  <div class="pfquickviewcontent">
                    <?php echo $this->get_post_content_pf($post_object);?>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div id="pfquickviewmap" class="pfquickviewmap" <?php echo $data_values;?>></div>
                </div>
              </div>
            </div>
          <?php
        break;
      /**
      *Enquiry Form
      **/
      	case 'enquiryform':
        if (class_exists('Pointfinder_reCaptcha_System')) {
            $reCaptcha = new Pointfinder_reCaptcha_System();
            $recaptcha_placeholder = $reCaptcha->create_recaptcha("pf-ajax-enquiry-form");
        }

        $val1 = $val2 = $val3 = '';

        if (is_user_logged_in()) {
          $current_user = wp_get_current_user();
          $user_id = $current_user->ID;
          $val2 = $current_user->user_email;

          $val1 = get_user_meta($user_id, 'first_name', true);
          $val1 .= ' '.get_user_meta($user_id, 'last_name', true);
          
          $val3 = get_user_meta($user_id, 'user_mobile', true);
          if ($val3 == '') {
            $val3 = get_user_meta($user_id, 'user_phone', true);
          }
          $namefield = '<section><label class="lbl-ui"><input type="hidden" name="name" class="input" placeholder="" value="'.$val1.'" /></label></section>';
          $emailfield = '<section><label class="lbl-ui"><input type="hidden" name="email" class="input" placeholder="" value="'.$val2.'"/></label></section>';
          $phonefield = '<section><label class="lbl-ui"><input type="hidden" name="phone" class="input" placeholder="" value="'.$val3.'"/></label></section>';
        }else{
          $namefield = '<section><label for="names" class="lbl-text">'.esc_html__('Name  & Surname','pointfindercoreelements').':</label><label class="lbl-ui"><input type="text" name="name" class="input" placeholder=""/></label></section>';
          $emailfield = '<section><label for="email" class="lbl-text">'.esc_html__('Email Address','pointfindercoreelements').':</label><label class="lbl-ui"><input type="email" name="email" class="input" placeholder=""/></label></section>';
          $phonefield = '';
        }
        ?><div class="golden-forms wrapper mini">
              <div id="pfmdcontainer-overlay" class="pftrwcontainer-overlay"></div>
              <form id="pf-ajax-enquiry-form">
                  <div class="pfmodalclose"><i class="fas fa-times-circle"></i></div>
                  <div class="pfsearchformerrors">
                      <ul></ul>
                      <a class="button pfsearch-err-button"><i class="fas fa-times"></i> 
                          <?php echo esc_html__('CLOSE','pointfindercoreelements');?>
                      </a>
                  </div>
                  <div class="form-title">
                      <h2><?php echo esc_html__('Contact Form','pointfindercoreelements');?></h2></div>
                  <div class="form-enclose">
                      <div class="form-section">
                          <section>
                              <label for="name" class="lbl-text">
                                  <?php echo esc_html__('Form Info','pointfindercoreelements');?>:</label>
                              <label class="lbl-ui">
                                  <?php echo $itemname;?>
                              </label>
                          </section>
                          <?php echo $namefield;echo $emailfield;echo $phonefield;?>
                              <section>
                                  <label for="phone" class="lbl-text">
                                      <?php echo esc_html__('Phone (Optional)','pointfindercoreelements');?>:</label>
                                  <label class="lbl-ui">
                                      <input type="tel" name="phone" class="input" placeholder="" value="<?php echo $val3;?>" />
                                  </label>
                              </section>
                              <section>
                                  <label for="msg" class="lbl-text">
                                      <?php echo esc_html__('Message','pointfindercoreelements');?>:</label>
                                  <label class="lbl-ui">
                                      <textarea name="msg" class="textarea no-resize"></textarea>
                                  </label>
                              </section>
                              <?php
                                echo '<section style="margin-top: 20px;margin-bottom: 10px;">';
                                echo '
                                    <span class="goption upt2">
                                        <label class="options">
                                            <input type="checkbox" id="pftermsofuser" name="pftermsofuser" value="1">
                                            <span class="checkbox"></span>
                                        </label>
                                        <label for="check1" class="upt2ch1">'.wp_sprintf(esc_html__( 'I have read the %s terms and conditions %s and accept them.', 'pointfindercoreelements' ),'<a href="'.$terms_permalink.'" class="pftermshortc" data-pid="'.$terms_permalink.'"><strong>','</strong></a>').'</label>
                                   </span>
                                ';
                                echo '</section>';
                              ?>
                              <?php echo $recaptcha_placeholder;?>
                      </div>
                  </div>
                  <div class="form-buttons">
                      <section>
                          <input type="hidden" name="itemid" class="input" value="<?php echo $itemid;?>" />
                          <button id="pf-ajax-enquiry-button" class="button blue">
                              <?php echo esc_html__('Send Message','pointfindercoreelements');?>
                          </button>
                      </section>
                  </div>
              </form>
          </div>		
          <?php
      		break;

      /**
      *Enquiry Form Author
      **/
        case 'enquiryformauthor':
        if (class_exists('Pointfinder_reCaptcha_System')) {
            $reCaptcha = new Pointfinder_reCaptcha_System();
            $recaptcha_placeholder = $reCaptcha->create_recaptcha("pf-ajax-enquiry-form-author");
        }

        $val1 = $val2 = $val3 = '';

        if (is_user_logged_in()) {
          $current_user = wp_get_current_user();
          $user_id = $current_user->ID;
          $val2 = $current_user->user_email;

          $val1 = get_user_meta($user_id, 'first_name', true);
          $val1 .= ' '.get_user_meta($user_id, 'last_name', true);

          if (empty($val1)) {
            $val1 = $current_user->user_login;
          }
          
          $val3 = get_user_meta($user_id, 'user_mobile', true);
          if ($val3 == '') {
            $val3 = get_user_meta($user_id, 'user_phone', true);
          }  
          $namefield = '<section><label class="lbl-ui"><input type="hidden" name="name" class="input" placeholder="" value="'.$val1.'" /></label></section>';
          $emailfield = '<section><label class="lbl-ui"><input type="hidden" name="email" class="input" placeholder="" value="'.$val2.'"/></label></section>';
        }else{
          $namefield = '<section><label for="names" class="lbl-text">'.esc_html__('Name  & Surname','pointfindercoreelements').':</label><label class="lbl-ui"><input type="text" name="name" class="input" placeholder=""/></label></section>';
          $emailfield = '<section><label for="email" class="lbl-text">'.esc_html__('Email Address','pointfindercoreelements').':</label><label class="lbl-ui"><input type="email" name="email" class="input" placeholder=""/></label></section>';
        }
        ?><div class="golden-forms wrapper mini">
              <div id="pfmdcontainer-overlay" class="pftrwcontainer-overlay"></div>
              <form id="pf-ajax-enquiry-form-author">
                  <div class="pfmodalclose"><i class="fas fa-times-circle"></i></div>
                  <div class="pfsearchformerrors">
                      <ul></ul>
                      <a class="button pfsearch-err-button"><i class="fas fa-times"></i> 
                          <?php echo esc_html__('CLOSE','pointfindercoreelements');?>
                      </a>
                  </div>
                  <div class="form-title">
                      <h2><?php echo esc_html__('Contact Form','pointfindercoreelements');?></h2></div>
                  <div class="form-enclose">
                      <div class="form-section">
                          <?php echo $namefield; echo $emailfield;?>
                              <section>
                                  <label for="phone" class="lbl-text">
                                      <?php echo esc_html__('Phone (Optional)','pointfindercoreelements');?>:</label>
                                  <label class="lbl-ui">
                                      <input type="tel" name="phone" class="input" placeholder="" value="<?php echo $val3;?>" />
                                  </label>
                              </section>
                              <section>
                                  <label for="msg" class="lbl-text">
                                      <?php echo esc_html__('Message','pointfindercoreelements');?>:</label>
                                  <label class="lbl-ui">
                                      <textarea name="msg" class="textarea no-resize"></textarea>
                                  </label>
                              </section>
                              <?php
                                echo '<section style="margin-top: 20px;margin-bottom: 10px;">';
                                echo '
                                    <span class="goption upt2">
                                        <label class="options">
                                            <input type="checkbox" id="pftermsofuser" name="pftermsofuser" value="1">
                                            <span class="checkbox"></span>
                                        </label>
                                        <label for="check1" class="upt2ch1">'.wp_sprintf(esc_html__( 'I have read the %s terms and conditions %s and accept them.', 'pointfindercoreelements' ),'<a href="'.$terms_permalink.'" class="pftermshortc" data-pid="'.$terms_permalink.'"><strong>','</strong></a>').'</label>
                                   </span>
                                ';
                                echo '</section>';
                              ?>
                              <?php echo $recaptcha_placeholder;?>
                      </div>
                  </div>
                  <div class="form-buttons">
                      <section>
                          <input type="hidden" name="userid" class="input" value="<?php echo $userid;?>" />
                          <button id="pf-ajax-enquiry-button-author" class="button blue">
                              <?php echo esc_html__('Send Message','pointfindercoreelements');?>
                          </button>
                      </section>
                  </div>
              </form>
          </div>   
          <?php
          break;

      /**
      *Report Form
      **/
        case 'reportform':
        if (class_exists('Pointfinder_reCaptcha_System')) {
            $reCaptcha = new Pointfinder_reCaptcha_System();
            $recaptcha_placeholder = $reCaptcha->create_recaptcha("pf-ajax-report-form");
        }

        $val1 = $val2 = $val3 = $user_id = '';

        if (is_user_logged_in()) {
          $current_user = wp_get_current_user();
          $user_id = $current_user->ID;
          $val2 = $current_user->user_email;

          $val1 = get_user_meta($user_id, 'first_name', true);
          $val1 .= ' '.get_user_meta($user_id, 'last_name', true);
          
          if (empty($val1) || $val1 == ' ') {
            $val1 = $user_id;
          }
          $namefield = '<section><label class="lbl-ui"><input type="hidden" name="name" class="input" placeholder="" value="'.$val1.'" /></label></section>';
          $emailfield = '<section><label class="lbl-ui"><input type="hidden" name="email" class="input" placeholder="" value="'.$val2.'"/></label></section>';
        }else{
          $namefield = '<section><label for="names" class="lbl-text">'.esc_html__('Name  & Surname','pointfindercoreelements').':</label><label class="lbl-ui"><input type="text" name="name" class="input" placeholder=""/></label></section>';
          $emailfield = '<section><label for="email" class="lbl-text">'.esc_html__('Email Address','pointfindercoreelements').':</label><label class="lbl-ui"><input type="email" name="email" class="input" placeholder=""/></label></section>';
        } 
        ?>
        
          <div class="golden-forms wrapper mini">
              <div id="pfmdcontainer-overlay" class="pftrwcontainer-overlay"></div>
              <form id="pf-ajax-report-form">
                  <div class="pfmodalclose"><i class="fas fa-times-circle"></i></div>
                  <div class="pfsearchformerrors">
                      <ul></ul>
                      <a class="button pfsearch-err-button"><i class="fas fa-times"></i> 
                          <?php echo esc_html__('CLOSE','pointfindercoreelements');?>
                      </a>
                  </div>
                  <div class="form-title">
                      <h2><?php echo esc_html__('Report Form','pointfindercoreelements');?></h2></div>
                  <div class="form-enclose">
                      <div class="form-section">
                          <section>
                              <label for="name" class="lbl-text">
                                  <?php echo esc_html__('Reported Listing','pointfindercoreelements');?>:</label>
                              <label class="lbl-ui">
                                  <?php echo $itemname;?>
                              </label>
                          </section>
                          <?php echo $namefield;echo $emailfield;?>
                              <section>
                                  <label for="msg" class="lbl-text">
                                      <?php echo esc_html__('Message','pointfindercoreelements');?>:</label>
                                  <label class="lbl-ui">
                                      <textarea name="msg" class="textarea no-resize"></textarea>
                                  </label>
                              </section>
                              <?php
                                echo '<section style="margin-top: 20px;margin-bottom: 10px;">';
                                echo '
                                    <span class="goption upt2">
                                        <label class="options">
                                            <input type="checkbox" id="pftermsofuser" name="pftermsofuser" value="1">
                                            <span class="checkbox"></span>
                                        </label>
                                        <label for="check1" class="upt2ch1">'.wp_sprintf(esc_html__( 'I have read the %s terms and conditions %s and accept them.', 'pointfindercoreelements' ),'<a href="'.$terms_permalink.'" class="pftermshortc" data-pid="'.$terms_permalink.'"><strong>','</strong></a>').'</label>
                                   </span>
                                ';
                                echo '</section>';
                              ?>
                              <?php echo $recaptcha_placeholder;?>
                      </div>
                  </div>
                  <div class="form-buttons">
                      <section>
                          <input type="hidden" name="itemid" class="input" value="<?php echo $itemid;?>" />
                          <input type="hidden" name="userid" class="input" value="<?php echo $user_id;?>" />
                          <button id="pf-ajax-report-button" class="button blue">
                              <?php echo esc_html__('Send','pointfindercoreelements');?>
                          </button>
                      </section>
                  </div>
              </form>
          </div>      
          <?php
          break;
      /**
      *Claim Form
      **/
        case 'claimform':
        if (class_exists('Pointfinder_reCaptcha_System')) {
            $reCaptcha = new Pointfinder_reCaptcha_System();
            $recaptcha_placeholder = $reCaptcha->create_recaptcha("pf-ajax-claim-form");
        }

        $val1 = $val2 = $val3 = $user_id = '';

        if (is_user_logged_in()) {
          $current_user = wp_get_current_user();
          $user_id = $current_user->ID;
          $val2 = $current_user->user_email;

          $val1 = get_user_meta($user_id, 'first_name', true);
          $val1 .= ' '.get_user_meta($user_id, 'last_name', true);
          
          if (empty($val1) || $val1 == ' ') {
            $val1 = $user_id;
          }
          $namefield = '<section><label class="lbl-ui"><input type="hidden" name="name" class="input" placeholder="" value="'.$val1.'" /></label></section>';
          $emailfield = '<section><label class="lbl-ui"><input type="hidden" name="email" class="input" placeholder="" value="'.$val2.'"/></label></section>';
        }else{
          $namefield = '<section><label for="name" class="lbl-text">'.esc_html__('Name  & Surname','pointfindercoreelements').':</label><label class="lbl-ui"><input type="text" name="name" class="input" placeholder=""/></label></section>';
          $emailfield = '<section><label for="email" class="lbl-text">'.esc_html__('Email Address','pointfindercoreelements').':</label><label class="lbl-ui"><input type="email" name="email" class="input" placeholder=""/></label></section>';
        }
        ?>
        <div class="golden-forms wrapper mini">
            <div id="pfmdcontainer-overlay" class="pftrwcontainer-overlay"></div>
            <form id="pf-ajax-claim-form">
                <div class="pfmodalclose"><i class="fas fa-times-circle"></i></div>
                <div class="pfsearchformerrors">
                    <ul></ul>
                    <a class="button pfsearch-err-button"><i class="fas fa-times"></i> 
                        <?php echo esc_html__('CLOSE','pointfindercoreelements');?>
                    </a>
                </div>
                <div class="form-title">
                    <h2><?php echo esc_html__('Claim Form','pointfindercoreelements');?></h2></div>
                <div class="form-enclose">
                    <div class="form-section">
                        <section>
                            <label for="name" class="lbl-text">
                                <?php echo esc_html__('Claim Item','pointfindercoreelements');?>:</label>
                            <label class="lbl-ui">
                                <?php echo $itemname;?>
                            </label>
                        </section>
                        <?php echo $namefield;echo $emailfield;?>
                            <section>
                                <label for="phonenum" class="lbl-text">
                                    <?php echo esc_html__('Phone Number','pointfindercoreelements');?>:</label>
                                <label class="lbl-ui">
                                    <input type="phonenum" name="phonenum" class="input" placeholder="" />
                                </label>
                            </section>
                            <section>
                                <label for="msg" class="lbl-text">
                                    <?php echo esc_html__('Message','pointfindercoreelements');?>:</label>
                                <label class="lbl-ui">
                                    <textarea name="msg" class="textarea no-resize"></textarea>
                                </label>
                            </section>
                            <?php
                                echo '<section style="margin-top: 20px;margin-bottom: 10px;">';
                                echo '
                                    <span class="goption upt2">
                                        <label class="options">
                                            <input type="checkbox" id="pftermsofuser" name="pftermsofuser" value="1">
                                            <span class="checkbox"></span>
                                        </label>
                                        <label for="check1" class="upt2ch1">'.wp_sprintf(esc_html__( 'I have read the %s terms and conditions %s and accept them.', 'pointfindercoreelements' ),'<a href="'.$terms_permalink.'" class="pftermshortc" data-pid="'.$terms_permalink.'"><strong>','</strong></a>').'</label>
                                   </span>
                                ';
                                echo '</section>';
                              ?>
                            <?php echo $recaptcha_placeholder;?>
                    </div>
                </div>
                <div class="form-buttons">
                    <section>
                        <input type="hidden" name="itemid" class="input" value="<?php echo $itemid;?>" />
                        <input type="hidden" name="userid" class="input" value="<?php echo $user_id;?>" />
                        <button id="pf-ajax-claim-button" class="button blue">
                            <?php echo esc_html__('Claim Now!','pointfindercoreelements');?>
                        </button>
                    </section>
                </div>
            </form>
        </div>
          <?php
          break;
      /**
      *Flag Review Form
      **/
        case 'flagreview':
        if (class_exists('Pointfinder_reCaptcha_System')) {
            $reCaptcha = new Pointfinder_reCaptcha_System();
            $recaptcha_placeholder = $reCaptcha->create_recaptcha("pf-ajax-flag-form");
        }

        $val1 = $val2 = $val3 = $user_id = '';

        if (is_user_logged_in()) {
          $current_user = wp_get_current_user();
          $user_id = $current_user->ID;
          $val2 = $current_user->user_email;

          $val1 = get_user_meta($user_id, 'first_name', true);
          $val1 .= ' '.get_user_meta($user_id, 'last_name', true);

          $namefield = '<section><label class="lbl-ui"><input type="hidden" name="name" class="input" placeholder="" value="'.$val1.'" /></label></section>';
          $emailfield = '<section><label class="lbl-ui"><input type="hidden" name="email" class="input" placeholder="" value="'.$val2.'"/></label></section>';
        }
          
        ?>

        <div class="golden-forms wrapper mini">
            <div id="pfmdcontainer-overlay" class="pftrwcontainer-overlay"></div>
            <form id="pf-ajax-flag-form">
                <div class="pfmodalclose"><i class="fas fa-times-circle"></i></div>
                <div class="pfsearchformerrors">
                    <ul></ul>
                    <a class="button pfsearch-err-button"><i class="fas fa-times"></i> 
                        <?php echo esc_html__('CLOSE','pointfindercoreelements');?>
                    </a>
                </div>
                <div class="form-title">
                    <h2><?php echo esc_html__('Flag Review Form','pointfindercoreelements');?></h2></div>
                <div class="form-enclose">
                    <div class="form-section">
                        <?php echo $namefield;?>
                            <?php echo $emailfield;?>
                                <section>
                                    <label for="msg" class="lbl-text">
                                        <?php echo esc_html__('Reason','pointfindercoreelements');?>:</label>
                                    <label class="lbl-ui">
                                        <textarea name="msg" class="textarea no-resize"></textarea>
                                    </label>
                                </section>
                                <?php
                                  echo '<section style="margin-top: 20px;margin-bottom: 10px;">';
                                  echo '
                                      <span class="goption upt2">
                                          <label class="options">
                                              <input type="checkbox" id="pftermsofuser" name="pftermsofuser" value="1">
                                              <span class="checkbox"></span>
                                          </label>
                                          <label for="check1" class="upt2ch1">'.wp_sprintf(esc_html__( 'I have read the %s terms and conditions %s and accept them.', 'pointfindercoreelements' ),'<a href="'.$terms_permalink.'" class="pftermshortc" data-pid="'.$terms_permalink.'"><strong>','</strong></a>').'</label>
                                     </span>
                                  ';
                                  echo '</section>';
                                ?>
                                <?php echo $recaptcha_placeholder;?>
                    </div>
                </div>
                <div class="form-buttons">
                    <section>
                        <input type="hidden" name="reviewid" class="input" value="<?php echo $itemid;?>" />
                        <input type="hidden" name="userid" class="input" value="<?php echo $user_id;?>" />
                        <button id="pf-ajax-flag-button" class="button blue">
                            <?php echo esc_html__('Flag This Review!','pointfindercoreelements');?>
                        </button>
                    </section>
                </div>
            </form>
        </div>
          <?php
          break;
    	}

      $content_modal = '';
      $content_modal = apply_filters('pointfinder_modal_content_filter',$content_modal,$formtype,$itemname,$itemid);
      
    die();
  }

  private function get_post_content_pf( $post) {
      
      if ( ! $post ) { return ''; }
      //else

      return apply_filters('the_content', $post->post_content);
  }

  private function get_the_title_pf( $post ) {
      
   
      $title = isset( $post->post_title ) ? $post->post_title : '';
      $id    = isset( $post->ID ) ? $post->ID : 0;
   
      if ( ! is_admin() ) {
          if ( ! empty( $post->post_password ) ) {
   
              /* translators: %s: Protected post title. */
              $prepend = __( 'Protected: %s' );
   
              /**
               * Filters the text prepended to the post title for protected posts.
               *
               * The filter is only applied on the front end.
               *
               * @since 2.8.0
               *
               * @param string  $prepend Text displayed before the post title.
               *                         Default 'Protected: %s'.
               * @param WP_Post $post    Current post object.
               */
              $protected_title_format = apply_filters( 'protected_title_format', $prepend, $post );
              $title                  = sprintf( $protected_title_format, $title );
          } elseif ( isset( $post->post_status ) && 'private' == $post->post_status ) {
   
              /* translators: %s: Private post title. */
              $prepend = __( 'Private: %s' );
   
              /**
               * Filters the text prepended to the post title of private posts.
               *
               * The filter is only applied on the front end.
               *
               * @since 2.8.0
               *
               * @param string  $prepend Text displayed before the post title.
               *                         Default 'Private: %s'.
               * @param WP_Post $post    Current post object.
               */
              $private_title_format = apply_filters( 'private_title_format', $prepend, $post );
              $title                = sprintf( $private_title_format, $title );
          }
      }
   
      /**
       * Filters the post title.
       *
       * @since 0.71
       *
       * @param string $title The post title.
       * @param int    $id    The post ID.
       */
      return apply_filters( 'the_title', $title, $id );
  }
  
}
}