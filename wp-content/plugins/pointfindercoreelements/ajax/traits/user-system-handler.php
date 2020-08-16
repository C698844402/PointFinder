<?php
if (class_exists('PointFinderUserSystemHandler')) {
  return;
}


class PointFinderUserSystemHandler extends Pointfindercoreelements_AJAX
{
    public function __construct(){}

    public function pf_ajax_usersystemhandler()
    {

        check_ajax_referer( 'pfget_usersystemhandler', 'security' );

        header('Content-Type: application/json; charset=UTF-8;');

        if (isset($_POST['formtype']) && $_POST['formtype']!='') {
            $formtype = esc_attr($_POST['formtype']);
        }

        $lang = '';
        if (isset($_POST['lang']) && $_POST['lang']!='') {
            $lang = sanitize_text_field($_POST['lang']);
        }

        if (class_exists('SitePress')) {
            if (!empty($lang)) {
                do_action('wpml_switch_language', $lang);
            }
        }

        if (isset($_POST['vars']) && $_POST['vars']!='') {
            $vars = array();
            parse_str($_POST['vars'], $vars);

            if (is_array($vars)) {
                $vars = $this->PFCleanArrayAttr('PFCleanFilters', $vars);
            } else {
                $vars = esc_attr($vars);
            }
        }
        

        if (class_exists('Pointfinder_reCaptcha_System') && in_array($formtype, array('register','login','lp'))) {
          
            $g_recaptcha_response = (isset($vars['g-recaptcha-response']))?$vars['g-recaptcha-response']:'';

            $xtype = $formtype;

            if ($formtype == 'register' ||  $formtype == 'lp') {
                $xtype = 'status';
            }
            $this->recaptcha_verify_sysx($g_recaptcha_response, $xtype);
        }

        if (wp_get_referer()) {
            $wpreferurl = esc_url(wp_get_referer());
        } else {
            $wpreferurl = esc_url(home_url("/"));
        }


        switch ($formtype) {
            case 'login':
            if (is_array($vars)) {
                $redirectpage = $this->pointfinder_redirectionselect();

                if (isset($vars['rem'])) {
                    $rememberme = ($vars['rem'] == 'on') ? true : false ;
                } else {
                    $rememberme = false;
                }
                $info = array();

                $info['user_login'] = sanitize_user($vars['username'], true);
                $info['user_password'] = trim(html_entity_decode($vars['password']));
                $info['remember'] = $rememberme;


                $user_signon = wp_signon($info, true);

                if (is_wp_error($user_signon)) {
                    echo json_encode(array( 'login'=>false, 'mes'=>esc_html__('Wrong username or password!', 'pointfindercoreelements')));
                } else {
                    wp_set_auth_cookie($user_signon->ID);
                    echo json_encode(array( 'login'=>true, 'mes'=>esc_html__('Login successful, redirecting...', 'pointfindercoreelements'),'referurl' => $wpreferurl,'redirectpage' => $redirectpage));
                }
                
            }
            break;

          case 'register':
            $regsysx = 0;
            


            $username = $vars['username'];
            $email = sanitize_email($vars['email']);


            $username = sanitize_user($username, $strict = true);

            $user_exist = username_exists($username);
            $user_email_exist = email_exists($email);


            if ($user_exist || $user_email_exist) {
                $message = wp_sprintf(esc_html__("Oops! There appears to be an account already with that name and/or email. %s Please change username and/or email.", "pointfindercoreelements"), '<br/>');
                echo json_encode(array( 'status'=>01, 'mes'=>$message));
                exit;
            } else {
                $redirectpage = $this->pointfinder_redirectionselect();

                if (empty($vars['pass'])) {
                    $password = wp_generate_password(12, false);
                } else {
                    $password = $vars['pass'];
                    $regsysx = 1;
                }

                $user_id = wp_create_user($username, $password, $email);

                wp_update_user(array( 'ID' => $user_id ));

                $user = new WP_User($user_id);
                $user->set_role('subscriber');

                do_action('pointfinder_additional_fields_save', $vars);

                if (!empty($vars['firstname'])) {
                    update_user_meta($user_id, 'first_name', $vars['firstname']);
                }
                if (!empty($vars['lastname'])) {
                    update_user_meta($user_id, 'last_name', $vars['lastname']);
                }
                if (!empty($vars['phone'])) {
                    update_user_meta($user_id, 'user_phone', $vars['phone']);
                }
                if (!empty($vars['mobile'])) {
                    update_user_meta($user_id, 'user_mobile', $vars['mobile']);
                }

                $message_reply = $this->pointfinder_mailsystem_mailsender(
                          array(
                              'toemail' => $email,
                              'predefined' => 'registration',
                              'data' => array('password' => $password,'username'=>$username),
                          )
                      );

                $setup33_emailsettings_mainemail = $this->PFMSIssetControl('setup33_emailsettings_mainemail', '', '1');
                $this->pointfinder_mailsystem_mailsender(
                array(
                  'toemail' => $setup33_emailsettings_mainemail,
                      'predefined' => 'registrationadmin',
                      'data' => array('username'=>$username),
                )
              );

                $auto_ll = $this->PFSAIssetControl('as_autologin', '', '0');

                if ($message_reply) {
                    if ($auto_ll == 1) {
                        if ($regsysx == 1) {
                            $message = esc_html__("Success! You will be auto login in 3sec.", "pointfindercoreelements");
                        } else {
                            $message = esc_html__("Success! Check your email for your password! You will be auto login in 3sec.", "pointfindercoreelements");
                        }


                        $user = get_user_by('id', $user_id);
                        if ($user) {
                            wp_set_current_user($user_id, $user->user_login);
                            wp_set_auth_cookie($user_id);
                            do_action('wp_login', $user->user_login);
                        }
                    } else {
                        if ($regsysx == 1) {
                            $message = esc_html__("Success! Please click here for login.", "pointfindercoreelements");
                        } else {
                            $message = esc_html__("Success! Check your email for your password!", "pointfindercoreelements");
                        }
                    }

                    echo json_encode(array( 'status'=>0, 'mes'=>$message,'auto'=>$auto_ll,'redirectpage' => $redirectpage,'regsysx' => $regsysx));
                } else {
                    $message = esc_html__("User created. Mail Configuration not correct. Please check Mail Config Panel > Email Settings under PF Settings", "pointfindercoreelements");
                    echo json_encode(array( 'status'=>03, 'mes'=>$message,'auto'=>0,'regsysx' => $regsysx));
                }
            }
            
          break;

          case 'lp':

            

            $pflpwd = esc_html__('Unfortunately we can not find this email and/or username in our records.', 'pointfindercoreelements');
            $pflpwd_status = 1;

            
            if (!empty($vars['username'])) {
                if (username_exists($vars['username'])) {
                    $user_login = sanitize_text_field($vars['username']);
                    $pflpwd = $this->pointfinder_retrieve_password($user_login);
                    $pflpwd_status = 0;
                }
            }

            if (!empty($vars['email'])) {
                $user_email = sanitize_email($vars['email']);
                if (email_exists($user_email)) {
                    $user_retemail = get_user_by('email', $user_email);
                    $pflpwd = $this->pointfinder_retrieve_password($user_retemail->data->user_login);
                    $pflpwd_status = 0;
                }
            }
            if ($pflpwd_status == 0) {
                echo json_encode(array( 'status'=>0, 'mes'=>$pflpwd));
            } else {
                echo json_encode(array( 'status'=>1, 'mes'=>$pflpwd));
            };
            
          break;

          case 'reset':
            if (!empty($vars['password']) && !empty($vars['password2'])) {
                $rp_key = $vars['rp_key'];
                $rp_login = $vars['rp_login'];

                $user = check_password_reset_key($rp_key, $rp_login);

                if (! $user || is_wp_error($user)) {
                    $redirect_url = home_url('/');
                    $redirect_url = add_query_arg('active', 'lp', $redirect_url);

                    if ($user && $user->get_error_code() === 'expired_key') {
                        echo json_encode(array( 'reset'=>true, 'mes'=>wp_sprintf(esc_html__('The password reset link you used is not valid anymore. Please %sclick here%s for create new password key.', 'pointfindercoreelements'), '<a class="lplink" href="'.$redirect_url.'">', '</a>')));
                    } else {
                        echo json_encode(array( 'reset'=>true, 'mes'=>wp_sprintf(esc_html__('The password reset link you used is not valid anymore. Please %sclick here%s for create new password key.', 'pointfindercoreelements'), '<a class="lplink" href="'.$redirect_url.'">', '</a>')));
                    }
                    die();
                }

                if (isset($vars['password'])) {
                    if ($vars['password'] != $vars['password2']) {
                        echo json_encode(array( 'reset'=>false, 'mes'=>esc_html__('The two passwords you entered don\'t match', 'pointfindercoreelements')));
                        die();
                    }


                    reset_password($user, $vars['password']);

                    echo json_encode(array( 'reset'=>true, 'mes'=>esc_html__('Password reset successful, now you can login with your new password. Please click here for open login window.', 'pointfindercoreelements')));
                } else {
                    echo json_encode(array( 'reset'=>false, 'mes'=>esc_html__('The password reset link you used is not valid anymore.', 'pointfindercoreelements')));
                }
            } else {
                echo json_encode(array( 'reset'=>false, 'mes'=>esc_html__('Password is empty.', 'pointfindercoreelements')));
            }
          break;


          case 'connectsocial':

            if (is_array($vars)) {
                $redirectpage = (isset($vars['redirectpage']))? $vars['redirectpage']:0;

                $info = array();
                $info['user_login'] = sanitize_user($vars['username'], true);
                $info['user_password'] = trim(html_entity_decode($vars['password']));
                $info['remember'] = false;

                $user_signon = wp_signon($info, true);

                if (!is_wp_error($user_signon)) {
                    $scontenttype = (isset($vars['ctype']))? $vars['ctype']:0;
                    $scontenttext = (isset($vars['ctext']))? json_decode(base64_decode($vars['ctext']), true):array();

                    $redirectpage = $this->pointfinder_redirectionselect();

                    if (!empty($scontenttext) && !empty($scontenttext['username'])) {
                        global $wpdb;

                        $resultid = '';

                        switch ($scontenttype) {
                        case '1':/* Facebook */

                          $resultid = $wpdb->get_var($wpdb->prepare(
                            "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = %s and meta_value = %s",
                            'user_socialloginid',
                            $scontenttext['dbid']
                          ));
                          if (!empty($resultid)) {
                              wp_set_auth_cookie($user_signon->ID);
                              echo json_encode(array( 'login'=>true, 'mes'=>esc_html__('Login successful, this social account already have a connection.', 'pointfindercoreelements'),'referurl' => $wpreferurl,'redirectpage' => $redirectpage));
                              die();
                          } else {
                              $resultid = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key = %s and meta_value = %s", 'user_socialloginidfb', $scontenttext['dbid']));
                          }

                          if (!empty($resultid)) {
                              wp_set_auth_cookie($user_signon->ID);
                              echo json_encode(array( 'login'=>true, 'mes'=>esc_html__('Login successful, this social account already have a connection.(2)', 'pointfindercoreelements'),'referurl' => $wpreferurl,'redirectpage' => $redirectpage));
                              die();
                          }

                          update_user_meta($user_signon->ID, 'user_socialloginidfb', $scontenttext['dbid'], true);
                          $fbid = str_replace('fb', '', $scontenttext['dbid']);
                          
                        break;

                        case '2':/* Twitter */
                          $resultid = $wpdb->get_var($wpdb->prepare(
                            "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = %s and meta_value = %s",
                            'user_socialloginid',
                            $scontenttext['dbid']
                          ));
                          if (!empty($resultid)) {
                              wp_set_auth_cookie($user_signon->ID);
                              echo json_encode(array( 'login'=>true, 'mes'=>esc_html__('Login successful, this social account already have a connection.', 'pointfindercoreelements'),'referurl' => $wpreferurl,'redirectpage' => $redirectpage));
                              die();
                          } else {
                              $resultid = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key = %s and meta_value = %s", 'user_socialloginidtw', $scontenttext['dbid']));
                          }

                          if (!empty($resultid)) {
                              wp_set_auth_cookie($user_signon->ID);
                              echo json_encode(array( 'login'=>true, 'mes'=>esc_html__('Login successful, this social account already have a connection.(2)', 'pointfindercoreelements'),'referurl' => $wpreferurl,'redirectpage' => $redirectpage));
                              die();
                          }

                          update_user_meta($user_signon->ID, 'user_socialloginidtw', $scontenttext['dbid'], true);
                          update_user_meta($user_signon->ID, 'user_twitter', 'http://twitter.com/'.$scontenttext['screen_name']);
                        break;

                        case '3':/* Google */
                          $resultid = $wpdb->get_var($wpdb->prepare(
                            "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = %s and meta_value = %s",
                            'user_socialloginid',
                            $scontenttext['dbid']
                          ));
                          if (!empty($resultid)) {
                              wp_set_auth_cookie($user_signon->ID);
                              echo json_encode(array( 'login'=>true, 'mes'=>esc_html__('Login successful, this social account already have a connection.', 'pointfindercoreelements'),'referurl' => $wpreferurl,'redirectpage' => $redirectpage));
                              die();
                          } else {
                              $resultid = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key = %s and meta_value = %s", 'user_socialloginidgl', $scontenttext['dbid']));
                          }

                          if (!empty($resultid)) {
                              wp_set_auth_cookie($user_signon->ID);
                              echo json_encode(array( 'login'=>true, 'mes'=>esc_html__('Login successful, this social account already have a connection.(2)', 'pointfindercoreelements'),'referurl' => $wpreferurl,'redirectpage' => $redirectpage));
                              die();
                          }

                          update_user_meta($user_signon->ID, 'user_socialloginidgl', $scontenttext['dbid'], true);
                          $glid = str_replace('g', '', $scontenttext['dbid']);
                          update_user_meta($user_signon->ID, 'user_googleplus', 'https://plus.google.com/'.$glid);
                        break;

                        case '4':/* VK */
                          $resultid = $wpdb->get_var($wpdb->prepare(
                            "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = %s and meta_value = %s",
                            'user_socialloginid',
                            $scontenttext['dbid']
                          ));
                          if (!empty($resultid)) {
                              wp_set_auth_cookie($user_signon->ID);
                              echo json_encode(array( 'login'=>true, 'mes'=>esc_html__('Login successful, this social account already have a connection.', 'pointfindercoreelements'),'referurl' => $wpreferurl,'redirectpage' => $redirectpage));
                              die();
                          } else {
                              $resultid = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key = %s and meta_value = %s", 'user_socialloginidvk', $scontenttext['dbid']));
                          }

                          if (!empty($resultid)) {
                              wp_set_auth_cookie($user_signon->ID);
                              echo json_encode(array( 'login'=>true, 'mes'=>esc_html__('Login successful, this social account already have a connection.(2)', 'pointfindercoreelements'),'referurl' => $wpreferurl,'redirectpage' => $redirectpage));
                              die();
                          }

                          update_user_meta($user_signon->ID, 'user_socialloginidvk', $scontenttext['dbid'], true);

                          /*$vkid = str_replace('vk', '', $scontenttext['dbid']);

                          update_user_meta($user_signon->ID, 'user_vk', 'https://vk.com/id'.$vkid);*/
                        break;
                      }
                    }


                    wp_set_auth_cookie($user_signon->ID);
                    echo json_encode(array( 'login'=>true, 'mes'=>esc_html__('Login successful, accounts are connected, redirecting...', 'pointfindercoreelements'),'referurl' => $wpreferurl,'redirectpage' => $redirectpage));
                } else {
                    echo json_encode(array( 'login'=>false, 'mes'=>esc_html__('Wrong username or password!', 'pointfindercoreelements')));
                }
            }


          break;

          case 'createsocial':

            if (is_array($vars)) {
                $redirectpage = (isset($vars['redirectpage']))? $vars['redirectpage']:0;

                $scontenttype = (isset($vars['ctype']))? $vars['ctype']:0;
                $scontenttext = (isset($vars['ctext']))? json_decode(base64_decode($vars['ctext']), true):array();

                $redirectpage = $this->pointfinder_redirectionselect();

                if (!empty($scontenttext) && !empty($scontenttext['username'])) {
                    switch ($scontenttype) {
                  case '1':
                    /* Facebook */
                      $user_exist = username_exists($scontenttext['username']);
                      $user_email_exist = email_exists($scontenttext['email']);

                      if ($user_exist || $user_email_exist) {
                          $message = wp_sprintf(esc_html__("Oops! There appears to be an account already with that name and/or email. %s Please change username and/or email.", "pointfindercoreelements"), '<br/>');
                          echo json_encode(array( 'status'=>01, 'mes'=>$message));
                          exit;
                      } else {
                          $password = wp_generate_password(12, false);
                          $user_id = wp_create_user($scontenttext['username'], $password, $scontenttext['email']);
                          $user = new WP_User($user_id);
                          $fbid = str_replace('fb', '', $scontenttext['dbid']);
                          update_user_meta($user_id, 'user_socialloginid', $scontenttext['dbid'], true);

                          if (isset($scontenttext['name'])) {
                              wp_update_user(array('ID'=>$user_id,'nickname'=>$scontenttext['name']));
                          }

                          if (isset($scontenttext['first_name'])) {
                              update_user_meta($user_id, 'first_name', $scontenttext['first_name']);
                          }

                          if (isset($scontenttext['last_name'])) {
                              update_user_meta($user_id, 'last_name', $scontenttext['last_name']);
                          }

                          $this->pointfinder_mailsystem_mailsender(
                            array(
                              'toemail' => $scontenttext['email'],
                                  'predefined' => 'registration',
                                  'data' => array('password' => $password,'username'=>$scontenttext['username']),
                            )
                        );

                          $message = esc_html__("Success! Check your email for your password! You will be auto login in 3sec.", "pointfindercoreelements");

                          $user = get_user_by('id', $user_id);
                          if ($user) {
                              wp_set_current_user($user_id, $user->user_login);
                              wp_set_auth_cookie($user_id);
                              do_action('wp_login', $user->user_login);
                          }

                          echo json_encode(array('status'=>0, 'mes'=>$message,'auto'=>1,'redirect'=>$redirectpage));
                      }
                    break;

                  case '2':
                    /* Twitter */
                      $domain_name =  preg_replace('/^www\./', '', $_SERVER['SERVER_NAME']);
                      if (!empty($vars['email_n'])) {
                          $email = $vars['email_n'];
                      } else {
                          $email = sanitize_email('twitter_user_'.$scontenttext['email'].'@'.$domain_name);
                      }

                      $user_exist = username_exists($scontenttext['username']);
                      $user_email_exist = email_exists($email);

                      if ($user_exist || $user_email_exist) {
                          $message = wp_sprintf(esc_html__("Oops! There appears to be an account already with that name and/or email. %s Please change username and/or email.", "pointfindercoreelements"), '<br/>');
                          echo json_encode(array( 'status'=>01, 'mes'=>$message));
                          exit;
                      } else {
                          $password = wp_generate_password(12, false);
                          $user_id = wp_create_user($scontenttext['username'], $password, $email);
                          $user = new WP_User($user_id);
                          $ggid = str_replace('g', '', $scontenttext['dbid']);
                          update_user_meta($user_id, 'user_socialloginid', $scontenttext['dbid'], true);
                          update_user_meta($user_id, 'user_twitter', 'http://twitter.com/'.$scontenttext['screen_name']);


                          if (!empty($vars['email_n'])) {
                              $this->pointfinder_mailsystem_mailsender(
                              array(
                                'toemail' => $email,
                                    'predefined' => 'registration',
                                    'data' => array('password' => $password,'username'=>$scontenttext['username']),
                              )
                          );
                          }

                          $message = esc_html__("Success! Check your email for your password! You will be auto login in 3sec.", "pointfindercoreelements");

                          $user = get_user_by('id', $user_id);
                          if ($user) {
                              wp_set_current_user($user_id, $user->user_login);
                              wp_set_auth_cookie($user_id);
                              do_action('wp_login', $user->user_login);
                          }

                          echo json_encode(array('status'=>0, 'mes'=>$message,'auto'=>1,'redirect'=>$redirectpage));
                      }
                    break;

                  case '3':
                    /* Google */
                      $user_exist = username_exists($scontenttext['username']);
                      $user_email_exist = email_exists($scontenttext['email']);

                      if ($user_exist || $user_email_exist) {
                          $message = wp_sprintf(esc_html__("Oops! There appears to be an account already with that name and/or email. %s Please change username and/or email.", "pointfindercoreelements"), '<br/>');
                          echo json_encode(array( 'status'=>01, 'mes'=>$message));
                          exit;
                      } else {
                          $password = wp_generate_password(12, false);
                          $user_id = wp_create_user($scontenttext['username'], $password, $scontenttext['email']);
                          $user = new WP_User($user_id);
                          $ggid = str_replace('g', '', $scontenttext['dbid']);
                          update_user_meta($user_id, 'user_socialloginid', $scontenttext['dbid'], true);
                          update_user_meta($user_id, 'user_googleplus', 'https://plus.google.com/'.$ggid);

                          $this->pointfinder_mailsystem_mailsender(
                            array(
                              'toemail' => $scontenttext['email'],
                                  'predefined' => 'registration',
                                  'data' => array('password' => $password,'username'=>$scontenttext['username']),
                            )
                        );

                          $message = esc_html__("Success! Check your email for your password! You will be auto login in 3sec.", "pointfindercoreelements");

                          $user = get_user_by('id', $user_id);
                          if ($user) {
                              wp_set_current_user($user_id, $user->user_login);
                              wp_set_auth_cookie($user_id);
                              do_action('wp_login', $user->user_login);
                          }

                          echo json_encode(array('status'=>0, 'mes'=>$message,'auto'=>1,'redirect'=>$redirectpage));
                      }
                    break;

                  case '4':
                    /* VK */
                      $domain_name =  preg_replace('/^www\./', '', $_SERVER['SERVER_NAME']);
                      if (!empty($vars['email_n'])) {
                          $email = $vars['email_n'];
                      } else {
                          $email = sanitize_email('vk_user_'.$scontenttext['email'].'@'.$domain_name);
                      }

                      $user_exist = username_exists($scontenttext['username']);
                      $user_email_exist = email_exists($email);

                      if ($user_exist || $user_email_exist) {
                          $message = wp_sprintf(esc_html__("Oops! There appears to be an account already with that name and/or email. %s Please change username and/or email.", "pointfindercoreelements"), '<br/>');
                          echo json_encode(array( 'status'=>01, 'mes'=>$message));
                          exit;
                      } else {
                          $password = wp_generate_password(12, false);
                          $user_id = wp_create_user($scontenttext['username'], $password, $email);
                          $user = new WP_User($user_id);
                          $ggid = str_replace('g', '', $scontenttext['dbid']);
                          update_user_meta($user_id, 'user_socialloginid', $scontenttext['dbid'], true);
                          /*update_user_meta($user_id, 'user_twitter', 'http://twitter.com/'.$scontenttext['screen_name']);*/


                          if (!empty($vars['email_n'])) {
                              $this->pointfinder_mailsystem_mailsender(
                              array(
                                'toemail' => $email,
                                    'predefined' => 'registration',
                                    'data' => array('password' => $password,'username'=>$scontenttext['username']),
                              )
                          );
                          }

                          $message = esc_html__("Success! Check your email for your password! You will be auto login in 3sec.", "pointfindercoreelements");

                          $user = get_user_by('id', $user_id);


                          if (isset($scontenttext['first_name'])) {
                              update_user_meta($user_id, 'first_name', $scontenttext['first_name']);
                          }

                          if (isset($scontenttext['last_name'])) {
                              update_user_meta($user_id, 'last_name', $scontenttext['last_name']);
                          }

                          if ($user) {
                              wp_set_current_user($user_id, $user->user_login);
                              wp_set_auth_cookie($user_id);
                              do_action('wp_login', $user->user_login);
                          }

                          echo json_encode(array('status'=>0, 'mes'=>$message,'auto'=>1,'redirect'=>$redirectpage));
                      }
                    break;
                }
                }
            }

          break;
        }
        die();
    }

    public function pointfinder_retrieve_password($user_login)
    {
        global $wpdb, $current_site;

        if (empty($user_login)) {
            return false;
        } elseif (strpos($user_login, '@')) {
            $user_data = get_user_by('email', trim($user_login));
            if (empty($user_data)) {
                return false;
            }
        } else {
            $login = trim($user_login);
            $user_data = get_user_by('login', $login);
        }

        do_action('lostpassword_post');


        if (!$user_data) {
            return false;
        }

        // redefining user_login ensures we return the right case in the email
        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;

        do_action('retrieve_password', $user_login);

        $allow = apply_filters('allow_password_reset', true, $user_data->ID);

        if (! $allow) {
            return false;
        } elseif (is_wp_error($allow)) {
            return false;
        }

        $key = '';//$wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
        if (empty($key)) {
            // Generate something random for a key...
            $key = wp_generate_password(20, false);
            do_action('retrieve_password_key', $user_login, $key);


            if (empty($wp_hasher)) {
                require_once ABSPATH . 'wp-includes/class-phpass.php';
                $wp_hasher = new PasswordHash(8, true);
            }
            /*
            Change this
            $hashed = $wp_hasher->HashPassword( $key );
            */
            $hashed = time() . ':' . $wp_hasher->HashPassword($key);
            // Now insert the new md5 key into the db
            $wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user_login));
        }

        $message_reply = $this->pointfinder_mailsystem_mailsender(
                 array(
                    'toemail' => $user_email,
                    'predefined' => 'lostpassword',
                    'data' => array('keylink' => network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login'),'username'=>$user_login),
                 )
              );


        if (!$message_reply) {
            return  esc_html__('The e-mail could not be sent.', 'pointfindercoreelements') . "<br />\n" . esc_html__('Possible reason: Your host may have disabled the mail() function...', 'pointfindercoreelements');
        }

        return esc_html__('Password reset link has been sent to your email.', 'pointfindercoreelements');
    }
}
