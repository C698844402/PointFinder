<?php
if (class_exists('PointFinderUserSystem')) {
    return;
}


class PointFinderUserSystem extends Pointfindercoreelements_AJAX
{
    public function __construct(){}


    public function pf_ajax_usersystem(){

      check_ajax_referer( 'pfget_usersystem', 'security');
      header('Content-Type: text/html; charset=UTF-8;');
      $pid = '';
      if(isset($_POST['pid']) && $_POST['pid']!=''){
        $pid = intval($_POST['pid']);
      }
      if(isset($_POST['formtype']) && $_POST['formtype']!=''){
        $formtype = esc_attr($_POST['formtype']);
      }
      if(isset($_POST['redirectpage']) && $_POST['redirectpage']!=''){
        $redirectpage = esc_attr($_POST['redirectpage']);
      }else{$redirectpage = 0;};

      $lang = '';
      if(isset($_POST['lang']) && $_POST['lang']!=''){
        $lang = sanitize_text_field($_POST['lang']);
      }

      if(class_exists('SitePress')) {
        if (!empty($lang)) {
          do_action( 'wpml_switch_language', $lang );
        }
      }

      

      $recaptcha_placeholder = '';
      $setup4_membersettings_dashboard = $this->PFSAIssetControl('setup4_membersettings_dashboard','','');
      $setup4_membersettings_dashboard_link = get_permalink($setup4_membersettings_dashboard);
      $pfmenu_perout = $this->PFPermalinkCheck();


        switch($formtype){
        /**
        *Login
        **/
            case 'login':
            $setup4_membersettings_dashboard_link = esc_url(home_url("/"));
            $pfmenu_perout = $this->PFPermalinkCheck();

            if (class_exists('Pointfinder_reCaptcha_System')) {
                $reCaptcha = new Pointfinder_reCaptcha_System();
                $recaptcha_placeholder = $reCaptcha->create_recaptcha("pf-ajax-login-form");
            }

            $facebook_login_check = $this->PFASSIssetControl('setup4_membersettings_facebooklogin','','0');
            $twitter_login_check = $this->PFASSIssetControl('setup4_membersettings_twitterlogin','','0');
            $google_login_check = $this->PFASSIssetControl('setup4_membersettings_googlelogin','','0');
            $vk_login_check = $this->PFASSIssetControl('setup4_membersettings_vklogin','','0');

            if($twitter_login_check == 1){
              $twitter_login_text = '<div class="social-btns full"><a id="pf-ajax-logintwitter" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'uaf=twlogin" class="tws" title="'.esc_html__('LOGIN WITH TWITTER','pointfindercoreelements').'"><i class="fab fa-twitter"></i></a></div>';
            }else{$twitter_login_text = '';}

            if($facebook_login_check == 1){
              $facebook_login_text = '<div class="social-btns full"><a id="pf-ajax-loginfacebook" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'uaf=fblogin" class="fbs" title="'.esc_html__('LOGIN WITH FACEBOOK','pointfindercoreelements').'"><i class="fab fa-facebook-f"></i></a></div>';
            }else{$facebook_login_text = '';}

            if($google_login_check == 1){
              $google_login_text = '<div class="social-btns full"><a id="pf-ajax-logingoogle" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'uaf=gologin" class="gbs" title="'.esc_html__('LOGIN WITH GOOGLE','pointfindercoreelements').'"><i class="pfloginglogo"></i></a></div>';
            }else{$google_login_text = '';}

            if($vk_login_check == 1){
              $vk_login_text = '<div class="social-btns full"><a id="pf-ajax-loginvk" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'uaf=vklogin" class="vk" title="'.esc_html__('LOGIN WITH VK','pointfindercoreelements').'"><i class="fab fa-vk"></i></a></div>';
            }else{$vk_login_text = '';}


            ?>
            <script type='text/javascript'>
                (function($) {
                    "use strict";
                    $.pfAjaxUserSystemVars = {};
                    $.pfAjaxUserSystemVars.username_err = '<?php echo esc_html__('Please write username ','pointfindercoreelements');?>';
                    $.pfAjaxUserSystemVars.username_err2 = '<?php echo esc_html__('Please enter at least 3 charactersfor Username.','pointfindercoreelements');?>';
                    $.pfAjaxUserSystemVars.password_err = '<?php echo esc_html__('Please write password ','pointfindercoreelements');?>';
                })(jQuery);
            </script>
            <div class="golden-forms wrapper mini">
                <div id="pflgcontainer-overlay" class="pftrwcontainer-overlay"></div>
                <form id="pf-ajax-login-form">
                    <div class="pfmodalclose"><i class="fas fa-times-circle"></i>
                    </div>
                    <div class="pfsearchformerrors">
                        <ul></ul>
                        <a class="button pfsearch-err-button"><i class="fas fa-times"></i> 
                            <?php echo esc_html__( 'CLOSE', 'pointfindercoreelements');?>
                        </a>
                    </div>
                    <div class="form-title">
                        <h2><?php echo esc_html__('Account Login','pointfindercoreelements');?></h2>
                    </div>
                    <div class="form-enclose">
                        <div class="form-section">
                            <section>
                                <label class="cxb">
                                    <?php echo esc_html__( 'Not a member yet?', 'pointfindercoreelements');?> <strong><a id="pf-register-trigger-button-inner" class="glink ext"><?php echo esc_html__('Register Now','pointfindercoreelements');?></a></strong>
                                    <?php echo esc_html__( '- Its  Free', 'pointfindercoreelements');?>
                                </label>
                                <div class="tagline"><span><?php echo esc_html__('OR','pointfindercoreelements');?></span>
                                </div>
                            </section>
                            <?php if( !(empty($facebook_login_text) && empty($twitter_login_text) && empty($google_login_text) && empty($vk_login_text)) ){?>
                            <section>
                                <div class="pointfinder-login-scbuttons"><span class="pflgtext"><?php echo esc_html__('LOGIN WITH','pointfindercoreelements');?></span><span class="pflgbuttons"><?php echo $facebook_login_text;echo $twitter_login_text;echo $google_login_text;echo $vk_login_text;?></span>
                                </div>
                                <div class="tagline"></div>

                            </section>
                            <?php }?>
                            <section>
                                <label for="usernames" class="lbl-text">
                                    <?php echo esc_html__( 'Username:', 'pointfindercoreelements');?>
                                </label>
                                <label class="lbl-ui append-icon">
                                    <input type="text" name="username" class="input" placeholder="<?php echo esc_html__('Enter Username','pointfindercoreelements');?>" autofocus /><span><i class="fas fa-user"></i></span>
                                </label>
                            </section>
                            <section>
                                <label for="pass" class="lbl-text">
                                    <?php echo esc_html__( 'Password:', 'pointfindercoreelements');?>
                                </label>
                                <label class="lbl-ui append-icon">
                                    <input type="password" name="password" class="input" placeholder="<?php echo esc_html__('Enter Password','pointfindercoreelements');?>" /><span><i class="fas fa-lock"></i></span>
                                </label>
                            </section>
                            <?php echo $recaptcha_placeholder;?>
                            <section><span class="gtoggle"><label class="toggle-switch blue"><input type="checkbox" name="rem" id="toggle1_rememberme" /><label for="toggle1_rememberme" data-on="<?php echo esc_html__('YES','pointfindercoreelements');?>" data-off="<?php echo esc_html__('NO','pointfindercoreelements');?>"></label></label><label for="toggle1"><?php echo esc_html__('Remember me','pointfindercoreelements');?> <strong><a id="pf-lp-trigger-button-inner" class="glink ext"><?php echo esc_html__('Forgot Password?','pointfindercoreelements');?></a></strong></label></span>
                            </section>
                        </div>
                    </div>
                    <div class="form-buttons">
                        <section>
                            <input type="hidden" name="redirectpage" value="<?php echo $redirectpage;?>" />
                            <button id="pf-ajax-login-button" class="button blue">
                                <?php echo esc_html__( 'Login Now', 'pointfindercoreelements');?>
                            </button>
                        </section>
                    </div>
                </form>
            </div>
        <?php
                break;
        /**
        *Register
        **/
          case 'register':
            if (class_exists('Pointfinder_reCaptcha_System')) {
                $reCaptcha = new Pointfinder_reCaptcha_System();
                $recaptcha_placeholder = $reCaptcha->create_recaptcha("pf-ajax-register-form");
            }

            $passffreg = $this->PFSAIssetControl('passffreg','','0');
            $tandcfreg = $this->PFSAIssetControl('tandcfreg','','0');
            $mobileffreg = $this->PFSAIssetControl('mobileffreg','','0');
            $mobileffreg_req = $this->PFSAIssetControl('mobileffreg_req','','0');
            $phoneffreg = $this->PFSAIssetControl('phoneffreg','','0');
            $phoneffreg_req = $this->PFSAIssetControl('phoneffreg_req','','0');

            $lnffreg = $this->PFSAIssetControl('lnffreg','','0');
            $lnffreg_req = $this->PFSAIssetControl('lnffreg_req','','0');
            $fnffreg = $this->PFSAIssetControl('fnffreg','','0');
            $fnffreg_req = $this->PFSAIssetControl('fnffreg_req','','0');


            $facebook_login_check = $this->PFASSIssetControl('setup4_membersettings_facebooklogin','','0');
            $twitter_login_check = $this->PFASSIssetControl('setup4_membersettings_twitterlogin','','0');
            $google_login_check = $this->PFASSIssetControl('setup4_membersettings_googlelogin','','0');
            $vk_login_check = $this->PFASSIssetControl('setup4_membersettings_vklogin','','0');

            if($twitter_login_check == 1){
              $twitter_login_text = '<div class="social-btns full"><a id="pf-ajax-logintwitter" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'uaf=twlogin" class="tws" title="'.esc_html__('LOGIN WITH TWITTER','pointfindercoreelements').'"><i class="fab fa-twitter"></i></a></div>';
            }else{$twitter_login_text = '';}

            if($facebook_login_check == 1){
              $facebook_login_text = '<div class="social-btns full"><a id="pf-ajax-loginfacebook" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'uaf=fblogin" class="fbs" title="'.esc_html__('LOGIN WITH FACEBOOK','pointfindercoreelements').'"><i class="fab fa-facebook-f"></i></a></div>';
            }else{$facebook_login_text = '';}

            if($google_login_check == 1){
              $google_login_text = '<div class="social-btns full"><a id="pf-ajax-logingoogle" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'uaf=gologin" class="gbs" title="'.esc_html__('LOGIN WITH GOOGLE','pointfindercoreelements').'"><i class="pfloginglogo"></i></a></div>';
            }else{$google_login_text = '';}

            if($vk_login_check == 1){
              $vk_login_text = '<div class="social-btns full"><a id="pf-ajax-loginvk" href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'uaf=vklogin" class="vk" title="'.esc_html__('LOGIN WITH VK','pointfindercoreelements').'"><i class="fab fa-vk"></i></a></div>';
            }else{$vk_login_text = '';}

            echo '<script type="text/javascript">';
                echo "(function($) {";
                    echo '"use strict";';
                    echo "$.pfAjaxUserSystemVars2 = {};";
                    echo "$.pfAjaxUserSystemVars2.username_err = '".esc_html__('Please write username ','pointfindercoreelements')."';";
                    echo "$.pfAjaxUserSystemVars2.username_err2 = '".esc_html__('Please enter at least 3 charactersfor Username.','pointfindercoreelements')."';";
                    echo "$.pfAjaxUserSystemVars2.email_err = '".esc_html__('Please write an email ','pointfindercoreelements')."';";
                    echo "$.pfAjaxUserSystemVars2.email_err2 = '".esc_html__('Your email address must be in the format of name@ domain.com ','pointfindercoreelements')."';";

                    if ($passffreg == '1') {
                        echo "$.pfAjaxUserSystemVars2.pass_err2 = '".esc_html__('Please enter password','pointfindercoreelements')."';";
                    }
                    if($fnffreg_req == '1'){
                        echo "$.pfAjaxUserSystemVars2.fn_err2 = '".esc_html__('Please enter first name.','pointfindercoreelements')."';";
                        echo "$.pfAjaxUserSystemVars2.fn_req = true;";
                    }
                    if($lnffreg_req == '1'){
                        echo "$.pfAjaxUserSystemVars2.ln_err2 = '".esc_html__('Please enter last name.','pointfindercoreelements')."';";
                        echo "$.pfAjaxUserSystemVars2.ln_req = true;";
                    }
                    if($phoneffreg_req == '1'){
                        echo "$.pfAjaxUserSystemVars2.phn_err2 = '".esc_html__('Please enter phone.','pointfindercoreelements')."';";
                        echo "$.pfAjaxUserSystemVars2.phn_req = true;";
                    }
                    if($mobileffreg_req == '1'){
                        echo "$.pfAjaxUserSystemVars2.mbl_err2 = '".esc_html__('Please enter mobile.','pointfindercoreelements')."';";
                        echo "$.pfAjaxUserSystemVars2.mbl_req = true;";
                    }
                    if($tandcfreg == '1'){
                        echo "$.pfAjaxUserSystemVars2.tnc_err2 = '".esc_html__('Please check terms and conditions checkbox.','pointfindercoreelements')."';";
                    }
                    do_action( 'pointfinder_additional_fields_js');
                echo "})(jQuery);";
            echo '</script>';
            ?>
            <div class="golden-forms wrapper mini">
                <div id="pflgcontainer-overlay" class="pftrwcontainer-overlay"></div>
                <form id="pf-ajax-register-form">
                    <div class="pfmodalclose"><i class="fas fa-times-circle"></i>
                    </div>
                    <div class="pfsearchformerrors">
                        <ul></ul>
                        <a class="button pfsearch-err-button"><i class="fas fa-times"></i> 
                            <?php echo esc_html__( 'CLOSE', 'pointfindercoreelements');?>
                        </a>
                    </div>
                    <div class="form-title">
                        <h2><?php echo esc_html__('Register an Account','pointfindercoreelements');?></h2>
                    </div>
                    <div class="form-enclose">
                        <div class="form-section">
                            <section>
                                <label class="cxb">
                                    <?php echo esc_html__( 'Already have an account?', 'pointfindercoreelements');?> <strong><a id="pf-login-trigger-button-inner" class="glink ext"><?php echo esc_html__('Login now','pointfindercoreelements');?></a></strong>
                                </label>
                                <div class="tagline"><span><?php echo esc_html__('OR','pointfindercoreelements');?></span>
                                </div>
                            </section>
                            <?php if( !(empty($facebook_login_text) && empty($twitter_login_text) && empty($google_login_text) && empty($vk_login_text)) ){?>
                            <section>
                                <div class="pointfinder-login-scbuttons"><span class="pflgtext"><?php echo esc_html__('REGISTER WITH','pointfindercoreelements');?></span><span class="pflgbuttons"><?php echo $facebook_login_text;echo $twitter_login_text;echo $google_login_text;echo $vk_login_text;?></span>
                                </div>
                                <div class="tagline"></div>

                            </section>
                            <?php }?>
                            <section>
                                <label for="usernames" class="lbl-text">
                                    <?php echo esc_html__( 'Username:', 'pointfindercoreelements');?>
                                </label>
                                <label class="lbl-ui append-icon">
                                    <input type="text" name="username" autocomplete="new-username" class="input" placeholder="<?php echo esc_html__('Enter Username','pointfindercoreelements');?>" autofocus /><span><i class="fas fa-user"></i></span>
                                </label>
                            </section>

                            <?php if($passffreg == '1'){ ?>
                            <section>
                                <label for="pass" class="lbl-text">
                                    <?php echo esc_html__( 'Password:', 'pointfindercoreelements');?>
                                </label>
                                <label class="lbl-ui append-icon">
                                    <input type="password" name="pass" autocomplete="new-password" class="input" placeholder="<?php echo esc_html__('Enter Password','pointfindercoreelements');?>" /><span><i class="fas fa-lock"></i></span>
                                </label>
                            </section>
                            <?php } ?>
                            <?php do_action( 'pointfinder_additional_fields_html');?>
                            <?php if($fnffreg == '1'){ ?>
                            <section>
                                <label for="firstname" class="lbl-text">
                                    <?php echo esc_html__( 'First Name:', 'pointfindercoreelements');?>
                                </label>
                                <label class="lbl-ui append-icon">
                                    <input type="text" name="firstname" class="input" placeholder="<?php echo esc_html__('Enter First Name','pointfindercoreelements');?>" /><span><i class="fas fa-id-card"></i></span>
                                </label>
                            </section>
                            <?php } ?>

                            <?php if($lnffreg == '1'){ ?>
                            <section>
                                <label for="lastname" class="lbl-text">
                                    <?php echo esc_html__( 'Last Name:', 'pointfindercoreelements');?>
                                </label>
                                <label class="lbl-ui append-icon">
                                    <input type="text" name="lastname" class="input" placeholder="<?php echo esc_html__('Enter Last Name','pointfindercoreelements');?>" /><span><i class="fas fa-id-card"></i></span>
                                </label>
                            </section>
                            <?php } ?>


                            <section>
                                <label for="pass" class="lbl-text">
                                    <?php echo esc_html__( 'Email:', 'pointfindercoreelements');?>
                                </label>
                                <label class="lbl-ui append-icon">
                                    <input type="text" name="email" class="input" placeholder="<?php echo esc_html__('Enter Email Address','pointfindercoreelements');?>" /><span><i class="fas fa-envelope"></i></span>
                                </label>
                            </section>

                            <?php if($phoneffreg == '1'){ ?>
                            <section>
                                <label for="phone" class="lbl-text">
                                    <?php echo esc_html__( 'Phone:', 'pointfindercoreelements');?>
                                </label>
                                <label class="lbl-ui append-icon">
                                    <input type="text" name="phone" class="input" placeholder="<?php echo esc_html__('Enter Phone Number','pointfindercoreelements');?>" /><span><i class="fas fa-phone-alt"></i></span>
                                </label>
                            </section>
                            <?php } ?>

                            <?php if($mobileffreg == '1'){ ?>
                            <section>
                                <label for="mobile" class="lbl-text">
                                    <?php echo esc_html__( 'Mobile:', 'pointfindercoreelements');?>
                                </label>
                                <label class="lbl-ui append-icon">
                                    <input type="text" name="mobile" class="input" placeholder="<?php echo esc_html__('Enter Mobile Number','pointfindercoreelements');?>" /><span><i class="fas fa-mobile-alt"></i></span>
                                </label>
                            </section>
                            <?php } ?>

                            <?php if($tandcfreg == '1'){
                                /**
                                *Terms and conditions
                                **/
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

                                /**
                                *Terms and conditions
                                **/
                            }?>
                            <?php echo $recaptcha_placeholder;?>
                        </div>
                    </div>
                    <div class="form-buttons">
                        <section>
                            <button class="button blue" id="pf-ajax-register-button">
                                <?php echo esc_html__( 'Register Now', 'pointfindercoreelements');?>
                            </button>
                        </section>
                    </div>
                </form>
            </div>
        <?php
            break;
        /**
        *Lost Password System
        **/
          case 'lp':
            if (class_exists('Pointfinder_reCaptcha_System')) {
                $reCaptcha = new Pointfinder_reCaptcha_System();
                $recaptcha_placeholder = $reCaptcha->create_recaptcha("pf-ajax-lp-form");
            }
            ?>
            <script type='text/javascript'>
                (function($) {
                    "use strict";
                    $.pfAjaxUserSystemVars3 = {};
                    $.pfAjaxUserSystemVars3.username_err = '<?php echo esc_html__('Username or Email must be filled.','pointfindercoreelements');?>';
                    $.pfAjaxUserSystemVars3.username_err2 = '<?php echo esc_html__('Please enter at least 3 charactersfor Username.','pointfindercoreelements');?>';
                    $.pfAjaxUserSystemVars3.email_err2 = '<?php echo esc_html__('Your email address must be in the format of name@ domain.com ','pointfindercoreelements');?>';
                })(jQuery);
            </script>
            <div class="golden-forms wrapper mini">
                <div id="pflgcontainer-overlay" class="pftrwcontainer-overlay"></div>
                <div class="pfmodalclose"><i class="fas fa-times-circle"></i>
                </div>
                <form id="pf-ajax-lp-form">
                    <div class="pfsearchformerrors">
                        <ul></ul>
                        <a class="button pfsearch-err-button"><i class="fas fa-times"></i> 
                            <?php echo esc_html__( 'CLOSE', 'pointfindercoreelements');?>
                        </a>
                    </div>
                    <div class="form-title">
                        <h2><?php echo esc_html__('Forgot Password','pointfindercoreelements');?></h2>
                    </div>
                    <div class="form-enclose">
                        <div class="form-section">
                            <section>
                                <label class="lbl-text"><strong><?php echo esc_html__('Please Enter;','pointfindercoreelements');?></strong>
                                </label>
                            </section>
                            <section>
                                <label for="usernames" class="lbl-text">
                                    <?php echo esc_html__( 'Username:', 'pointfindercoreelements');?>
                                </label>
                                <label class="lbl-ui append-icon">
                                    <input type="text" name="username" class="input" placeholder="<?php echo esc_html__('Enter Username','pointfindercoreelements');?>" autofocus /><span><i class="fas fa-user"></i></span>
                                </label>
                            </section>
                            <section>
                                <div class="tagline"><span><?php echo esc_html__('OR','pointfindercoreelements');?></span>
                                </div>
                            </section>
                            <section>
                                <label for="pass" class="lbl-text">
                                    <?php echo esc_html__( 'Email:', 'pointfindercoreelements');?>
                                </label>
                                <label class="lbl-ui append-icon">
                                    <input type="text" name="email" class="input" placeholder="<?php echo esc_html__('Enter Email Address','pointfindercoreelements');?>" /><span><i class="fas fa-envelope"></i></span>
                                </label>
                            </section>
                            <?php echo $recaptcha_placeholder;?>
                        </div>
                    </div>
                    <div class="form-buttons">
                        <section>
                            <button class="button blue" id="pf-ajax-lp-button">
                                <?php echo esc_html__( 'Send Password', 'pointfindercoreelements');?>
                            </button>
                        </section>
                    </div>
                </form>
            </div>
          <?php
            break;

          case 'lpex':
          case 'lpin':
          $redirect_url = home_url( '/' );
          $redirect_url = add_query_arg( 'active', 'lp', $redirect_url );
            ?>
            <div class="golden-forms wrapper mini">
                <div id="pflgcontainer-overlay" class="pftrwcontainer-overlay"></div>
                <div class="pfmodalclose"><i class="fas fa-times-circle"></i>
                </div>
                <form id="pf-ajax-lp-form">
                    <div class="form-title">
                        <h2><?php echo esc_html__('Error','pointfindercoreelements');?></h2>
                    </div>
                    <div class="form-enclose">
                        <div class="form-section">
                            <section>
                                <label class="lbl-text">
                                    <strong><?php echo sprintf(esc_html__('The password reset link you used is not valid anymore. Please %sclick here%s for create new password key.','pointfindercoreelements'),'<a class="lplink" href="'.$redirect_url.'">','</a>');?></strong>
                                </label>
                            </section>
                        </div>
                    </div>
                    <div class="form-buttons" style="height:55px;">
                        <section style="height:55px;">

                        </section>
                    </div>
                </form>
            </div>
          <?php
            break;
            case 'confirmaction':
                if(isset($_POST['scontenttext']) && $_POST['scontenttext']!=''){
                  $scontenttext = $_POST['scontenttext'];
                }else{
                  $scontenttext = array('request_id' => '','confirm_key' => '');
                }
                
                $request_id = (int) $scontenttext['request_id'];
                $key        = sanitize_text_field( wp_unslash( $scontenttext['confirm_key'] ) );
                $result     = wp_validate_user_request_key( $request_id, $key );
            ?>
            <div class="golden-forms wrapper mini">
                <div id="pflgcontainer-overlay" class="pftrwcontainer-overlay"></div>
                <div class="pfmodalclose"><i class="fas fa-times-circle"></i>
                </div>
                <form id="pf-ajax-confirmaction-form">
                    <div class="form-title">
                        <?php if(!is_wp_error( $result )){ 
                            $request = wp_get_user_request_data( $request_id );
                         
                            if (  in_array( $request->status, array( 'request-pending', 'request-failed' ), true ) ) {
                                update_post_meta( $request_id, '_wp_user_request_confirmed_timestamp', time() );
                                wp_update_post(
                                    array(
                                        'ID'          => $request_id,
                                        'post_status' => 'request-confirmed',
                                    )
                                );

                                $setup33_emailsettings_mainemail = $this->PFMSIssetControl('setup33_emailsettings_mainemail', '', '1');
                                    $this->pointfinder_mailsystem_mailsender(
                                    array(
                                      'toemail' => $setup33_emailsettings_mainemail,
                                          'predefined' => 'personeldataeraserequestadmin',
                                          'data' => '',
                                    )
                                  );
                            }
                        ?>
                        <h2><?php echo esc_html__('Action has been confirmed.','pointfindercoreelements');?></h2>
                        <?php }else{ ?>
                        <h2><?php echo esc_html__('Error','pointfindercoreelements');?></h2>
                        <?php } ?> 
                    </div>
                    <div class="form-enclose">
                        <div class="form-section">
                            <section>
                                <label class="lbl-text">
                                    <?php if(!is_wp_error( $result )){ ?>
                                    <strong><?php echo esc_html__('Thanks for confirming your erasure request. The site administrator has been notified. You will receive an email confirmation when they erase your data.','pointfindercoreelements');?></strong>
                                <?php }else{ ?>
                                <strong><?php echo $result->get_error_message();?></strong>
                                <?php
                                }
                                ?>
                                </label>
                            </section>
                        </div>
                    </div>
                    <div class="form-buttons" style="height:55px;">
                        <section style="height:55px;">

                        </section>
                    </div>
                </form>
            </div>
          <?php
            break;
          case 'lpr':
          ?>
            <script type='text/javascript'>
                (function($) {
                    "use strict";
                    $.pfAjaxUserSystemVars = {};
                    $.pfAjaxUserSystemVars.password_err = '<?php echo esc_html__('Please write password ','pointfindercoreelements');?>';
                    $.pfAjaxUserSystemVars.password_err2 = '<?php echo esc_html__('Please enter at least 3 charactersfor Password.','pointfindercoreelements');?>';
                    $.pfAjaxUserSystemVars.password2_err = '<?php echo esc_html__('Please write repeat password ','pointfindercoreelements');?>';
                    $.pfAjaxUserSystemVars.password2_err2 = '<?php echo esc_html__('Please enter at least 3 characters for Password.','pointfindercoreelements');?>';
                    $.pfAjaxUserSystemVars.password2_err3 = '<?php echo esc_html__('The two passwords you entered don\'t match.','pointfindercoreelements');?>';
                })(jQuery);
            </script>
          <div class="golden-forms wrapper mini">
              <div id="pflgcontainer-overlay" class="pftrwcontainer-overlay"></div>
              <div class="pfmodalclose"><i class="fas fa-times-circle"></i>
              </div>
              <form id="pf-ajax-lpr-form">
                  <div class="pfsearchformerrors">
                      <ul></ul>
                      <a class="button pfsearch-err-button"><i class="fas fa-times"></i> 
                          <?php echo esc_html__( 'CLOSE', 'pointfindercoreelements');?>
                      </a>
                  </div>
                  <div class="form-title">
                      <h2><?php echo esc_html__('Reset Password','pointfindercoreelements');?></h2>
                  </div>
                  <div class="form-enclose">
                      <div class="form-section">
                          <section>
                              <label class="lbl-text"><strong><?php echo esc_html__('Please Enter;','pointfindercoreelements');?></strong>
                              </label>
                          </section>
                          <section>
                              <label for="password" class="lbl-text">
                                  <?php echo esc_html__( 'New Password:', 'pointfindercoreelements');?>
                              </label>
                              <label class="lbl-ui append-icon">
                                    <input type="password" name="password" id="password" class="input" placeholder="<?php echo esc_html__('Enter Password','pointfindercoreelements');?>" /><span><i class="fas fa-lock"></i></span>
                                </label>
                          </section>
                          <section>
                              <label for="password2" class="lbl-text">
                                  <?php echo esc_html__( 'Repeat New Password:', 'pointfindercoreelements');?>
                              </label>
                              <label class="lbl-ui append-icon">
                                    <input type="password" name="password2" id="password2" class="input" placeholder="<?php echo esc_html__('Repeat Password','pointfindercoreelements');?>" /><span><i class="fas fa-lock"></i></span>
                                </label>
                          </section>
                      </div>
                  </div>
                  <div class="form-buttons">
                      <section>
                          <div class="pf-additional-detailsforlpr"></div>
                          <button class="button blue" id="pf-ajax-lpr-button">
                              <?php echo esc_html__( 'Reset Password', 'pointfindercoreelements');?>
                          </button>
                      </section>
                  </div>
              </form>
          </div>
          <?php
            break;

        /**
        *Scontent
        **/
          case 'scontent':
            if(isset($_POST['scontenttype']) && $_POST['scontenttype']!=''){
              $scontenttype = esc_attr($_POST['scontenttype']);
            }else{
              $scontenttype = '';
            }
            if(isset($_POST['scontenttext']) && $_POST['scontenttext']!=''){
              $scontenttext = esc_attr($_POST['scontenttext']);
            }else{
              $scontenttext = '';
            }
            ?>
            <script type='text/javascript'>(function($) {
              "use strict";
              $.pfAjaxUserSystemVars = {};
              $.pfAjaxUserSystemVars.username_err = '<?php echo esc_html__('Please write username','pointfindercoreelements');?>';
              $.pfAjaxUserSystemVars.username_err2 = '<?php echo esc_html__('Please enter at least 3 characters for Username.','pointfindercoreelements');?>';
              $.pfAjaxUserSystemVars.password_err = '<?php echo esc_html__('Please write password','pointfindercoreelements');?>';
              })(jQuery);
            </script>

            <div class="golden-forms wrapper mini">
              <div id="pflgcontainer-overlay" class="pftrwcontainer-overlay"></div>
              <form id="pf-ajax-login-form">
                <div class="pfmodalclose">
                  <i class="fas fa-times-circle"></i>
                </div>

                <div class="pfsearchformerrors">
                  <ul></ul>
                  <a class="button pfsearch-err-button"><i class="fas fa-times"></i> <?php echo esc_html__('CLOSE','pointfindercoreelements');?></a>
                </div>

                <div class="form-title">
                  <h2><?php echo esc_html__('Social Account Settings','pointfindercoreelements');?></h2>
                </div>

                <div class="form-enclose">
                  <div class="form-section">

                    <section style="text-align: center;">
                      <?php if ($scontenttype == 2 || $scontenttype == 4) {?>
                        <section>
                          <label for="email_n" class="lbl-text"><?php echo esc_html__('Email:','pointfindercoreelements');?></label>
                          <label class="lbl-ui append-icon">
                            <input type="text" name="email_n" class="input" placeholder="<?php echo esc_html__('Enter Email','pointfindercoreelements');?>" autofocus />
                            <span>
                              <i class="fas fa-user"></i>
                            </span>
                          </label>
                          <small><?php echo esc_html__('Please enter your email address to complete your registration.','pointfindercoreelements');?></small>
                        </section>
                      <?php }?>
                      <button id="pfsocialnewaccountbutton" class="button blue"><?php echo esc_html__('CREATE as NEW ACCOUNT','pointfindercoreelements');?></button>

                      <div class="tagline">
                        <span><?php echo esc_html__('OR','pointfindercoreelements');?></span>
                      </div>
                    </section>

                    <section>
                      <div class="pointfinder-login-scbuttons">
                        <?php echo esc_html__('CONNECT WITH EXISTING ACCOUNT','pointfindercoreelements');?>
                      </div>
                    </section>

                    <section>
                      <label for="usernames" class="lbl-text"><?php echo esc_html__('Username:','pointfindercoreelements');?></label>
                      <label class="lbl-ui append-icon">
                        <input type="text" name="username" class="input" placeholder="<?php echo esc_html__('Enter Username','pointfindercoreelements');?>" autofocus />
                        <span>
                          <i class="fas fa-user"></i>
                        </span>
                      </label>
                    </section>

                    <section>
                      <label for="pass" class="lbl-text"><?php echo esc_html__('Password:','pointfindercoreelements');?></label>
                      <label class="lbl-ui append-icon">
                        <input type="password" name="password" class="input" placeholder="<?php echo esc_html__('Enter Password','pointfindercoreelements');?>" />
                        <span><i class="fas fa-lock"></i></span>
                      </label>
                    </section>

                  </div>
                </div>

                <div class="form-buttons">

                  <section>
                    <input type="hidden" name="redirectpage" value="<?php echo $redirectpage;?>"/>
                    <input type="hidden" name="ctype" value="<?php echo $scontenttype;?>"/>
                    <input type="hidden" name="ctext" value="<?php echo $scontenttext;?>"/>
                    <button id="pfsocialconnectbutton" class="button blue"><?php echo esc_html__('CONNECT NOW','pointfindercoreelements');?></button>
                  </section>

                </div>

              </form>

            </div>
        <?php
            break;
        /**
        *Error Window
        **/
          case 'error':
            if(isset($_POST['errortype']) && $_POST['errortype']!=''){
                $errortype = esc_attr($_POST['errortype']);
            }
            if (empty($errortype)) {
                $errortype = 0;
            }

            if ($errortype == 1) {
                $pfkeyarray = array(
                  0 => esc_html__('Information','pointfindercoreelements'),
                  1 => esc_html__('Details;','pointfindercoreelements'),
                  2 => esc_html__('Close','pointfindercoreelements'),
                );
            }elseif($errortype == 0){
                $pfkeyarray = array(
                  0 => esc_html__('Error','pointfindercoreelements'),
                  1 => esc_html__('Error Details;','pointfindercoreelements'),
                  2 => esc_html__('Close','pointfindercoreelements'),
                );
            }

            ?>
            <div class="golden-forms wrapper mini">
              <form id="pf-ajax-cl-form">
                  <div class="form-title">
                      <h2><?php echo $pfkeyarray[0];?></h2>
                  </div>
                  <div class="form-enclose">
                      <div class="form-section">
                          <section>
                              <label class="lbl-text"><strong><?php echo $pfkeyarray[1];?></strong>
                              </label>
                              <p id="pf-ajax-cl-details"></p>
                          </section>
                      </div>
                  </div>
                  <div class="form-buttons">
                      <section>
                          <button class="button blue" id="pf-ajax-cl-button">
                              <?php echo $pfkeyarray[2];?>
                          </button>
                      </section>
                  </div>
              </form>
          </div>
          <?php
            break;

        /*Terms */
        case 'terms':
        ?>
        <div class="golden-forms wrapper" style="margin-left:0!important; margin-right:0!important;max-width:650px!important;">
              <form id="pf-ajax-cl-form">
                  <div class="form-enclose">
                      <div>
                          <section>
                              <div class="pf-termsconditions-class">
                                  <?php
                                    $content_post = get_post($pid);
                                    $content = $content_post->post_content;
                                    $content = apply_filters('the_content', $content);
                                    echo $content;
                                    ?>
                              </div>
                          </section>
                      </div>
                  </div>
                  <div class="form-buttons">
                      <section style="text-align: center;">
                          <button class="button blue" style="text-align:center;margin:0 auto;" id="pf-ajax-terms-button">
                              <?php echo esc_html__('Return Back','pointfindercoreelements');?>
                          </button>
                      </section>
                  </div>
              </form>
          </div>
        <?php
        break;
        }
    die();
    }
}
