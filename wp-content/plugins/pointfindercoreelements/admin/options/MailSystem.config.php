<?php
/**********************************************************************************************************************************
*
* Main Admin Options Config File
*
* Author: Webbu
*
***********************************************************************************************************************************/

if (!class_exists("Redux_Framework_PF_Mail_Config")) {
	class Redux_Framework_PF_Mail_Config

	{
		public $args = array();
		public $sections = array();
		public $theme;
		public $ReduxFramework;
		
		public function __construct(){
			$this->initSettings();
		}


		public function initSettings(){

			$this->setArguments();
			$this->setSections();
			if (!isset($this->args['opt_name'])) { return;}
			 
			$this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
		}

		


		public function setSections(){
			

			/**
			*EMAIL SETTINS 
			**/
				/**
				*Start: Email Limits
				**/
					$this->sections[] = array(
						'id' => 'setup33_emaillimits',
						'icon' => 'el-icon-unlock-alt',
						'title' => esc_html__('Email Permissions', 'pointfindercoreelements'),
						'fields' => array(
								array(
									'id' => 'setup33_emaillimits_listingautowarning',
									'type' => 'button_set',
									'title' => esc_html__('Item Expire Date Warning', 'pointfindercoreelements') ,
									'desc'		=> esc_html__('If this option is enabled, the owner of item will receive an email before item expires.','pointfindercoreelements').'<br>'.esc_html__('(Sending time: 24 hours before expire time.)', 'pointfindercoreelements'),
									'options' => array(
										'1' => esc_html__('Enable', 'pointfindercoreelements') ,
										'0' => esc_html__('Disable', 'pointfindercoreelements')
									) ,
									'default' => '1'
									
								) ,
								array(
									'id' => 'setup33_emaillimits_listingexpired',
									'type' => 'button_set',
									'title' => esc_html__('Item Expiration', 'pointfindercoreelements') ,
									'desc'		=> esc_html__('If this option is enabled, the owner of item will receive an email after item expires.', 'pointfindercoreelements'),
									'options' => array(
										'1' => esc_html__('Enable', 'pointfindercoreelements') ,
										'0' => esc_html__('Disable', 'pointfindercoreelements')
									) ,
									'default' => '1'
									
								) ,
								array(
									'id' => 'setup33_emaillimits_adminemailsafterupload',
									'type' => 'button_set',
									'title' => esc_html__('New Upload: Admin Notification', 'pointfindercoreelements') ,
									'desc'		=> esc_html__('Do you want to receive an email after new item is uploaded?', 'pointfindercoreelements'),
									'options' => array(
										'1' => esc_html__('Yes', 'pointfindercoreelements') ,
										'0' => esc_html__('No', 'pointfindercoreelements')
									) ,
									'default' => '1'
									
								) ,
								array(
									'id' => 'setup33_emaillimits_adminemailsafteredit',
									'type' => 'button_set',
									'title' => esc_html__('Item Edit: Admin Notification', 'pointfindercoreelements') ,
									'desc'		=> esc_html__('Do you want to receive an email after item is edited?', 'pointfindercoreelements'),
									'options' => array(
										'1' => esc_html__('Yes', 'pointfindercoreelements') ,
										'0' => esc_html__('No', 'pointfindercoreelements')
									) ,
									'default' => '1'
									
								) ,

								array(
									'id' => 'setup33_emaillimits_useremailsaftertrash',
									'type' => 'button_set',
									'title' => esc_html__('Item Delete: User Notification', 'pointfindercoreelements') ,
									'desc'		=> esc_html__('Do you want to send an email after item is deleted by admin?', 'pointfindercoreelements'),
									'options' => array(
										'1' => esc_html__('Yes', 'pointfindercoreelements') ,
										'0' => esc_html__('No', 'pointfindercoreelements')
									) ,
									'default' => '1'
									
								) ,

								array(
									'id' => 'setup33_emaillimits_copyofcontactform',
									'type' => 'button_set',
									'title' => esc_html__('Item Contact Form: Send a copy to Admin', 'pointfindercoreelements') ,
									'desc'		=> esc_html__('Do you want to send a copy of every single item contact form to yourself?', 'pointfindercoreelements'),
									'options' => array(
										'1' => esc_html__('Yes', 'pointfindercoreelements') ,
										'0' => esc_html__('No', 'pointfindercoreelements')
									) ,
									'default' => '1'
									
								) ,

								array(
									'id' => 'setup33_emaillimits_copyofreviewform',
									'type' => 'button_set',
									'title' => esc_html__('Item Review Form: Send a copy to Admin', 'pointfindercoreelements') ,
									'desc'		=> esc_html__('Do you want to send a copy of every single item review form to yourself?', 'pointfindercoreelements'),
									'options' => array(
										'1' => esc_html__('Yes', 'pointfindercoreelements') ,
										'0' => esc_html__('No', 'pointfindercoreelements')
									) ,
									'default' => '1'
									
								) ,
							)
					);
				
				/**
				*End: Email Limits
				**/






				/**
				*Start: Email Settings
				**/
					$this->sections[] = array(
						'id' => 'setup33_emailsettings',
						'icon' => ' el-icon-wrench-alt',
						'title' => esc_html__('Email Settings', 'pointfindercoreelements'),
						'fields' => array(
								
								array(
			                        'id'        => 'setup33_emailsettings_sitename',
			                        'type'      => 'text',
			                        'title'     => esc_html__('Site Name', 'pointfindercoreelements'),
			                        'default'   => '',
									'hint' => array(
										'content'   => esc_html__('Please write site name for email header.','pointfindercoreelements')
									),
			                    ),
								array(
			                        'id'        => 'setup33_emailsettings_fromname',
			                        'type'      => 'text',
			                        'title'     => esc_html__('From Name', 'pointfindercoreelements'),
			                        'default'   => '',
									'hint' => array(
										'content'   => esc_html__('Email from this name.','pointfindercoreelements')
									),
			                    ),
								array(
			                        'id'        => 'setup33_emailsettings_fromemail',
			                        'type'      => 'text',
			                        'title'     => esc_html__('From Email', 'pointfindercoreelements'),
			                        'validate'  => 'email',
			                        'msg'       => esc_html__('Please write a correct email.','pointfindercoreelements'),
			                        'default'   => '',
									'text_hint' => array(
										'title'     => esc_html__('Valid Email Required!','pointfindercoreelements'),
										'content'   => esc_html__('This field required a valid email address.','pointfindercoreelements')
									),
			                    ),
			                    array(
			                        'id'        => 'setup33_emailsettings_mainemail',
			                        'type'      => 'text',
			                        'title'     => esc_html__('Receive Email', 'pointfindercoreelements'),
			                        'validate'  => 'email',
			                        'msg'       => esc_html__('Please write a correct email.','pointfindercoreelements'),
			                        'desc'       => esc_html__('This email address will receive all system emails such as payment, item submission, etc.','pointfindercoreelements'),
			                        'default'   => '',
									'text_hint' => array(
										'title'     => esc_html__('Valid Email Required!','pointfindercoreelements'),
										'content'   => esc_html__('This field required a valid email address.','pointfindercoreelements')
									),
			                    ),
			                    array(
									'id' => 'setup33_emailsettings_mailtype',
									'type' => 'button_set',
									'title' => esc_html__('Content Type', 'pointfindercoreelements') ,
									'options' => array(
										'1' => esc_html__('HTML', 'pointfindercoreelements') ,
										'0' => esc_html__('Plain Text', 'pointfindercoreelements')
									) ,
									'hint' => array(
										'content'   => esc_html__('Do you want to send emails Plain Text or HTML format? Recommended: HTML','pointfindercoreelements')
									),
									'default' => '1',									
								) 
							)
					);

					$this->sections[] = array(
						'id' => 'setup34_emailcontents',
						'icon' => 'el-icon-pencil-alt',
						'title' => esc_html__('Email Contents', 'pointfindercoreelements'),
						'fields' => array(
								
							)
					);
				/**
				*End: Email Settings
				**/

			
			
				/**
				*Start: Email Contents
				**/
					/**
					*Start: User System Email Contents
					**/
						$this->sections[] = array(
							'id' => 'setup35_loginemails',
							'subsection' => true,
							'title' => esc_html__('User Registration', 'pointfindercoreelements'),
							'desc'	=> esc_html__('You can change email contents by using below options.', 'pointfindercoreelements'),
							'fields' => array(
									/**
									*Registration Email
									**/
										array(
					                        'id'        => 'setup35_loginemails_register-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Registration Email', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after user registration.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
											array(
						                        'id'        => 'setup35_loginemails_register_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Registration Completed','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_loginemails_register_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('New User Registration','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_loginemails_register_contents',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true,
						                        	'wpautop' => true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display username', 'pointfindercoreelements'),'%%username%%').'<br>'.sprintf(esc_html__('%s : Display password', 'pointfindercoreelements'),'%%password%%'),
						                        'validate'  => 'html',
						                    ),
										array(
					                        'id'        => 'setup35_loginemails_register-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
					                    array(
					                        'id'    => 'opt-divide',
					                        'type'  => 'divide'
					                    ),


					                /**
									*Registration Email to Admin
									**/
										array(
					                        'id'        => 'setup35_loginemails_registeradm-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Registration Email to Admin', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after user registration.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
											array(
						                        'id'        => 'setup35_loginemails_registeradm_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Registration Completed','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_loginemails_registeradm_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('New User Registration','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_loginemails_registeradm_contents',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true,
						                        	'wpautop' => true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display username', 'pointfindercoreelements'),'%%username%%'),
						                        'validate'  => 'html',
						                    ),
										array(
					                        'id'        => 'setup35_loginemails_registeradm-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
					                    array(
					                        'id'    => 'opt-divide',
					                        'type'  => 'divide'
					                    ),


				                    /**
									*Forgot Password Email
									**/
						                array(
					                        'id'        => 'setup35_loginemails_forgot-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Forgot Password Email', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent when forgotten password requests.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
						                    array(
						                        'id'        => 'setup35_loginemails_forgot_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Lost Password Reset','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_loginemails_forgot_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Lost Password Reset','pointfindercoreelements'),
						                    ),
						                    array(
						                        'id'        => 'setup35_loginemails_forgot_contents',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display username', 'pointfindercoreelements'),'%%username%%').'<br>'.sprintf(esc_html__('%s : Display reset password link', 'pointfindercoreelements'),'%%keylink%%'),
						                        'validate'  => 'html',
						                    ),
					                    array(
					                        'id'        => 'setup35_loginemails_forgot-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
								)
						);
					/**
					*End: User System Email Contents
					**/









					/**
					*Start: CONTACT Contents
					**/
						$this->sections[] = array(
							'id' => 'setup35_contactformemails',
							'subsection' => true,
							'title' => esc_html__('Contact Form', 'pointfindercoreelements'),
							'desc'	=> esc_html__('You can change email contents by using below options.', 'pointfindercoreelements'),
							'fields' => array(
								
								array(
			                        'id'        => 'setup35_contactform_subject',
			                        'type'      => 'text',
			                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
			                        'default'	=> esc_html__('Contact Form','pointfindercoreelements'),
			                    ),
								array(
			                        'id'        => 'setup35_contactform_title',
			                        'type'      => 'text',
			                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
			                        'default'	=> esc_html__('New Contact Form','pointfindercoreelements'),
			                    ),
								array(
			                        'id'        => 'setup35_contactform_contents',
			                        'type'      => 'editor',
			                        'args'	=> array(
			                        	'media_buttons'	=> false,
			                        	'teeny'	=> true,
			                        	'wpautop' => true
			                        	),
			                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
			                        'subtitle'	=> sprintf(esc_html__('%s : Display name', 'pointfindercoreelements'),'%%name%%').
			                        '<br>'.sprintf(esc_html__('%s : Display email', 'pointfindercoreelements'),'%%email%%').
			                        '<br>'.sprintf(esc_html__('%s : Display subject', 'pointfindercoreelements'),'%%subject%%').
			                        '<br>'.sprintf(esc_html__('%s : Display phone', 'pointfindercoreelements'),'%%phone%%').
			                        '<br>'.sprintf(esc_html__('%s : Display message', 'pointfindercoreelements'),'%%msg%%').
			                        '<br>'.sprintf(esc_html__('%s : Display date time', 'pointfindercoreelements'),'%%datetime%%'),
			                        'validate'  => 'html',
			                    ),
								

								)
						);
					/**
					*End: CONTACT Email Contents
					**/








					/**
					*Start: Submission Email Contents to USER
					**/
						$this->sections[] = array(
							'id' => 'setup35_submissionemails',
							'subsection' => true,
							'title' => sprintf(esc_html__('Item Submission (%s)', 'pointfindercoreelements'),esc_html__('User','pointfindercoreelements')),
							'heading' => esc_html__('New Uploaded Item User Notification Emails', 'pointfindercoreelements'),
							'desc'	=> sprintf(esc_html__('You can change email contents for %s notification by using below options.', 'pointfindercoreelements'),esc_html__('user','pointfindercoreelements')),
							'fields' => array(

									 /**
									*Waiting for PAYMENT email
									**/

					                    array(
					                        'id'        => 'setup35_submissionemails_waitingpayment-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('New Item; Waiting for PAYMENT', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent when an item is uploaded and waiting for payment process', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_submissionemails_waitingpayment_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Your item is waiting for payment','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_submissionemails_waitingpayment_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Your item is waiting for payment','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_submissionemails_waitingpayment',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item ID', 'pointfindercoreelements'),'%%itemid%%').'<br>'.sprintf(esc_html__('%s : Display item title', 'pointfindercoreelements'),'%%itemname%%'),
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_submissionemails_waitingpayment-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),



									/**
									*Waiting for APPROVAL Email
									**/

									 	array(
					                        'id'        => 'setup35_submissionemails_waitingapproval-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('New Item; Waiting for APPROVAL', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after an item is uploaded and payment process is completed.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_submissionemails_waitingapproval_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Your item is waiting for approval','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_submissionemails_waitingapproval_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Your item is waiting for approval','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_submissionemails_waitingapproval',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item ID', 'pointfindercoreelements'),'%%itemid%%').'<br>'.sprintf(esc_html__('%s : Display item title', 'pointfindercoreelements'),'%%itemname%%'),
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_submissionemails_waitingapproval-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),


						           
					                /**
									*Item APPROVED email
									**/

										array(
					                        'id'        => 'setup35_submissionemails_approveditem-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Item; APPROVED', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after an item is approved by admin.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_submissionemails_approveditem_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Your item has been approved for listing','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_submissionemails_approveditem_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Item Approved','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_submissionemails_approveditem',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item ID', 'pointfindercoreelements'),'%%itemid%%').'<br>'.sprintf(esc_html__('%s : Display item title', 'pointfindercoreelements'),'%%itemname%%').'<br>'.sprintf(esc_html__('%s : Display item link', 'pointfindercoreelements'),'%%itemlink%%'),
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_submissionemails_approveditem-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),


					                /**
									*Item REJECTED email
									**/

					                    array(
					                        'id'        => 'setup35_submissionemails_rejected-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Item; REJECTED', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent when an item is rejected by admin.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_submissionemails_rejected_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Your item has been rejected for listing','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_submissionemails_rejected_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Item Rejected','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_submissionemails_rejected',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item ID', 'pointfindercoreelements'),'%%itemid%%').'<br>'.sprintf(esc_html__('%s : Display item title', 'pointfindercoreelements'),'%%itemname%%'),
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_submissionemails_rejected-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),

					                /**
									*Item DELETED email
									**/

					                    array(
					                        'id'        => 'setup35_submissionemails_deleted-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Item; DELETED', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent when an item is sent to trash (removed) by admin.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_submissionemails_deleted_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Your item has been deleted','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_submissionemails_deleted_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Item Deleted','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_submissionemails_deleted',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item ID', 'pointfindercoreelements'),'%%itemid%%').'<br>'.sprintf(esc_html__('%s : Display item title', 'pointfindercoreelements'),'%%itemname%%'),
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_submissionemails_deleted-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
								)
						);
					/**
					*End: Submission Email Contents to USER
					**/






					/**
					*Start: Submission Email Contents to ADMIN
					**/
						$this->sections[] = array(
							'id' => 'setup35_submissionemailsadmin',
							'subsection' => true,
							'title' => sprintf(esc_html__('Item Submission (%s)', 'pointfindercoreelements'),esc_html__('Admin','pointfindercoreelements')),
							'heading' => esc_html__('Item Upload Emails for the Admin', 'pointfindercoreelements'),
							'desc'	=> sprintf(esc_html__('You can change email contents for %s notification by using below options.', 'pointfindercoreelements'),esc_html__('admin','pointfindercoreelements')),
							'fields' => array(

									/**
									*New item submitted
									**/

					                    array(
					                        'id'        => 'setup35_submissionemails_newitem-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('New Item; Uploaded', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent when new item is uploaded and waiting for approval process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_submissionemails_newitem_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('New item has been uploaded','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_submissionemails_newitem_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('New item has been uploaded','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_submissionemails_newitem',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item ID', 'pointfindercoreelements'),'%%itemid%%').'<br>'.sprintf(esc_html__('%s : Display item title', 'pointfindercoreelements'),'%%itemname%%').'<br>'.sprintf(esc_html__('%s : Display item link (For admin)', 'pointfindercoreelements'),'%%itemlinkadmin%%'),
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_submissionemails_newitem-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),


									/**
									*Item Updated email
									**/

					                    array(
					                        'id'        => 'setup35_submissionemails_updateditem-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Item; Edited', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent when existing item is updated and waiting for approval process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_submissionemails_updateditem_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Item edited','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_submissionemails_updateditem_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Item edited','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_submissionemails_updateditem',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item ID', 'pointfindercoreelements'),'%%itemid%%').'<br>'.sprintf(esc_html__('%s : Display item title', 'pointfindercoreelements'),'%%itemname%%').'<br>'.sprintf(esc_html__('%s : Display item link (For admin)', 'pointfindercoreelements'),'%%itemlinkadmin%%'),
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_submissionemails_updateditem-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
								)
						);
					/**
					*End: Submission Email Contents to ADMIN
					**/





					/**
					*Start: Payment Email Contents (USER)
					**/
						$this->sections[] = array(
							'id' => 'setup35_paymentemails',
							'subsection' => true,
							'title' => sprintf(esc_html__('Payments (PPP) (%s)', 'pointfindercoreelements'),esc_html__('User','pointfindercoreelements')),
							'heading' => sprintf(esc_html__('Payment System (PAY PER POST): %s Notifications', 'pointfindercoreelements'),esc_html__('User','pointfindercoreelements')),
							'fields' => array(
									/**
									*Direct Payment completed email to USER
									**/
					                    array(
					                        'id'        => 'setup35_paymentemails_paymentcompleted-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Direct Payment Completed', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after a succeeded direct payment process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentemails_paymentcompleted_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Payment completed','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentemails_paymentcompleted_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Payment completed','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentemails_paymentcompleted',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item ID', 'pointfindercoreelements'),'%%itemid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display item title', 'pointfindercoreelements'),'%%itemname%%').'<br>'
						                        .sprintf(esc_html__('%s : Display payment total', 'pointfindercoreelements'),'%%paymenttotal%%').'<br>'
						                        .sprintf(esc_html__('%s : Display packagename', 'pointfindercoreelements'),'%%packagename%%').'<br>'
						                        ,
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_paymentemails_paymentcompleted-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),

									/**
									*Recurring Payment completed email to USER
									**/

					                    array(
					                        'id'        => 'setup35_paymentemails_paymentcompletedrec-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Recurring Payment Completed', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after a succeeded recurring payment process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentemails_paymentcompletedrec_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Recurring profile has been created','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentemails_paymentcompletedrec_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Recurring profile has been created','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentemails_paymentcompletedrec',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item ID', 'pointfindercoreelements'),'%%itemid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display item title', 'pointfindercoreelements'),'%%itemname%%').'<br>'
						                        .sprintf(esc_html__('%s : Display payment total', 'pointfindercoreelements'),'%%paymenttotal%%').'<br>'
						                        .sprintf(esc_html__('%s : Display packagename', 'pointfindercoreelements'),'%%packagename%%').'<br>'
						                        .sprintf(esc_html__('%s : Display next payment date', 'pointfindercoreelements'),'%%nextpayment%%').'<br>'
						                        .sprintf(esc_html__('%s : Display recurring profile ID', 'pointfindercoreelements'),'%%profileid%%').'<br>'
						                        ,
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_paymentemails_paymentcompletedrec-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
					                
					                /**
									*Bank Payment waiting email to USER
									**/
					                    array(
					                        'id'        => 'setup35_paymentemails_bankpaymentwaiting-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Bank Payment Request Completed', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after a succeeded bank payment request process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentemails_bankpaymentwaiting_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Bank transfer waiting','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentemails_bankpaymentwaiting_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Bank transfer waiting','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentemails_bankpaymentwaiting',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item ID', 'pointfindercoreelements'),'%%itemid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display item title', 'pointfindercoreelements'),'%%itemname%%').'<br>'
						                        .sprintf(esc_html__('%s : Display payment total', 'pointfindercoreelements'),'%%paymenttotal%%').'<br>'
						                        .sprintf(esc_html__('%s : Display packagename', 'pointfindercoreelements'),'%%packagename%%').'<br>'
						                        ,
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_paymentemails_bankpaymentwaiting-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),

					                /**
									*Bank Payment cancelled email to USER
									**/
					                    array(
					                        'id'        => 'setup35_paymentemails_bankpaymentcancel-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Bank Payment Request Cancelled', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after a cancelled bank payment request process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentemails_bankpaymentcancel_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Bank transfer request cancelled','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentemails_bankpaymentcancel_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Bank transfer request cancelled','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentemails_bankpaymentcancel',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item ID', 'pointfindercoreelements'),'%%itemid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display item title', 'pointfindercoreelements'),'%%itemname%%').'<br>'
						                        ,
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_paymentemails_bankpaymentcancel-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),

								)
						);
					/**
					*End: Payment Email Contents (USER)
					**/




					/**
					*Start: Payment Email Contents (ADMIN)
					**/
						$this->sections[] = array(
							'id' => 'setup35_paymentemailsadmin',
							'subsection' => true,
							'title' => sprintf(esc_html__('Payments (PPP) (%s)', 'pointfindercoreelements'),esc_html__('Admin','pointfindercoreelements')),
							'heading' => sprintf(esc_html__('Payment System (PAY PER POST): %s Notifications', 'pointfindercoreelements'),esc_html__('Admin','pointfindercoreelements')),
							'fields' => array(
					                /**
									*Direct Payment completed email to ADMIN
									**/
				                    	
					                    array(
					                        'id'        => 'setup35_paymentemails_newdirectpayment-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Direct Payment Received Email Content', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after a succeeded direct payment process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentemails_newdirectpayment_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('New payment has been received','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentemails_newdirectpayment_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('New payment has been received','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentemails_newdirectpayment',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item ID', 'pointfindercoreelements'),'%%itemid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display item title', 'pointfindercoreelements'),'%%itemname%%').'<br>'
						                        .sprintf(esc_html__('%s : Display edit link', 'pointfindercoreelements'),'%%itemadminlink%%').'<br>'
						                        .sprintf(esc_html__('%s : Display payment total', 'pointfindercoreelements'),'%%paymenttotal%%').'<br>'
						                        .sprintf(esc_html__('%s : Display packagename', 'pointfindercoreelements'),'%%packagename%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_paymentemails_newdirectpayment-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
				                   	/**
									*Recurring Payment completed email to ADMIN
									**/
					                    
					                    array(
					                        'id'        => 'setup35_paymentemails_newrecpayment-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Recurring Payment Received Email Content', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after a succeeded recurring payment process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentemails_newrecpayment_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Recurring Profile has been created','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentemails_newrecpayment_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Recurring Profile has been created','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentemails_newrecpayment',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item ID', 'pointfindercoreelements'),'%%itemid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display item title', 'pointfindercoreelements'),'%%itemname%%').'<br>'
						                        .sprintf(esc_html__('%s : Display edit link', 'pointfindercoreelements'),'%%itemadminlink%%').'<br>'
						                        .sprintf(esc_html__('%s : Display payment total', 'pointfindercoreelements'),'%%paymenttotal%%').'<br>'
						                        .sprintf(esc_html__('%s : Display packagename', 'pointfindercoreelements'),'%%packagename%%').'<br>'
						                        .sprintf(esc_html__('%s : Display next payment date', 'pointfindercoreelements'),'%%nextpayment%%').'<br>'
						                        .sprintf(esc_html__('%s : Display recurring profile ID', 'pointfindercoreelements'),'%%profileid%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_paymentemails_newrecpayment-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),

					                /**
									*Bank Payment received email to ADMIN
									**/
				                    	
					                    array(
					                        'id'        => 'setup35_paymentemails_newbankpayment-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Bank Payment Request Received Email Content', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after a succeeded bank payment request process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentemails_newbankpayment_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('New bank payment transfer request received','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentemails_newbankpayment_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('New bank payment transfer request received','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentemails_newbankpayment',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item ID', 'pointfindercoreelements'),'%%itemid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display item title', 'pointfindercoreelements'),'%%itemname%%').'<br>'
						                        .sprintf(esc_html__('%s : Display edit link', 'pointfindercoreelements'),'%%itemadminlink%%').'<br>'
						                        .sprintf(esc_html__('%s : Display payment total', 'pointfindercoreelements'),'%%paymenttotal%%').'<br>'
						                        .sprintf(esc_html__('%s : Display packagename', 'pointfindercoreelements'),'%%packagename%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_paymentemails_newbankpayment-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
								)
						);
					/**
					*End: Payment Email Contents (ADMIN)
					**/



					/**
					*Start: Payment Membership Email Contents (USER)
					**/
						$this->sections[] = array(
							'id' => 'setup35_paymentmemberemails',
							'subsection' => true,
							'title' => sprintf(esc_html__('Payments (Member) (%s)', 'pointfindercoreelements'),esc_html__('User','pointfindercoreelements')),
							'heading' => sprintf(esc_html__('Payment System (Membership System): %s Notifications', 'pointfindercoreelements'),esc_html__('User','pointfindercoreelements')),
							'fields' => array(

									/**
									*Free Payment completed email to USER - done
									**/
					                    array(
					                        'id'        => 'setup35_paymentmemberemails_freecompleted-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Free Plan Completed', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after a succeeded free plan process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentmemberemails_freecompleted_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Package Activated','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentmemberemails_freecompleted_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Package Activated','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentmemberemails_freecompleted',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display package title', 'pointfindercoreelements'),'%%packagename%%'),
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_paymentmemberemails_freecompleted-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),

									/**
									*Direct Payment completed email to USER - done
									**/
					                    array(
					                        'id'        => 'setup35_paymentmemberemails_paymentcompleted-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Direct Payment Completed', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after a succeeded direct payment process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentmemberemails_paymentcompleted_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Payment completed','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentmemberemails_paymentcompleted_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Payment completed','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentmemberemails_paymentcompleted',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display package title', 'pointfindercoreelements'),'%%packagename%%').'<br>'
						                        .sprintf(esc_html__('%s : Display payment total', 'pointfindercoreelements'),'%%paymenttotal%%').'<br>'
						                        ,
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_paymentmemberemails_paymentcompleted-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),

									/**
									*Recurring Payment completed email to USER - done
									**/

					                    array(
					                        'id'        => 'setup35_paymentmemberemails_paymentcompletedrec-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Recurring Payment Completed', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after a succeeded recurring payment process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentmemberemails_paymentcompletedrec_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Recurring profile has been created','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentmemberemails_paymentcompletedrec_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Recurring profile has been created','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentmemberemails_paymentcompletedrec',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display order number', 'pointfindercoreelements'),'%%ordernumber%%').'<br>'
						                        .sprintf(esc_html__('%s : Display payment total', 'pointfindercoreelements'),'%%paymenttotal%%').'<br>'
						                        .sprintf(esc_html__('%s : Display packagename', 'pointfindercoreelements'),'%%packagename%%').'<br>'
						                        .sprintf(esc_html__('%s : Display next payment date', 'pointfindercoreelements'),'%%nextpayment%%').'<br>'
						                        .sprintf(esc_html__('%s : Display recurring profile ID', 'pointfindercoreelements'),'%%profileid%%').'<br>'
						                        ,
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_paymentmemberemails_paymentcompletedrec-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
					                
					                /**
									*Bank Payment waiting email to USER
									**/
					                    array(
					                        'id'        => 'setup35_paymentmemberemails_bankpaymentwaiting-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Bank Payment Request Completed', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after a succeeded bank payment request process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentmemberemails_bankpaymentwaiting_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Bank transfer waiting','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentmemberemails_bankpaymentwaiting_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Bank transfer waiting','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentmemberemails_bankpaymentwaiting',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display order ID', 'pointfindercoreelements'),'%%orderid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display payment total', 'pointfindercoreelements'),'%%paymenttotal%%').'<br>'
						                        .sprintf(esc_html__('%s : Display packagename', 'pointfindercoreelements'),'%%packagename%%').'<br>'
						                        ,
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_paymentmemberemails_bankpaymentwaiting-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),

					                /**
									*Bank Payment cancelled email to USER
									**/
					                    array(
					                        'id'        => 'setup35_paymentmemberemails_bankpaymentcancel-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Bank Payment Request Cancelled', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after a cancelled bank payment request process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentmemberemails_bankpaymentcancel_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Bank transfer request cancelled','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentmemberemails_bankpaymentcancel_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Bank transfer request cancelled','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentmemberemails_bankpaymentcancel',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display order ID', 'pointfindercoreelements'),'%%orderid%%'),
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_paymentmemberemails_bankpaymentcancel-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),

					                /**
									*Bank Payment approved email to USER
									**/
					                    array(
					                        'id'        => 'setup35_paymentmemberemails_bankpaymentapp-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Bank Payment Request Approved', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after a approved bank payment request process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentmemberemails_bankpaymentapp_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Bank transfer request approved','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentmemberemails_bankpaymentapp_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Bank transfer request approved','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentmemberemails_bankpaymentapp',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display order ID', 'pointfindercoreelements'),'%%orderid%%'),
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_paymentmemberemails_bankpaymentapp-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),

								)
						);
					/**
					*End: Payment Membership Email Contents (USER)
					**/


					/**
					*Start: Payment Membership Email Contents (ADMIN)
					**/
						$this->sections[] = array(
							'id' => 'setup35_paymentmemberemailsadmin',
							'subsection' => true,
							'title' => sprintf(esc_html__('Payments (Member) (%s)', 'pointfindercoreelements'),esc_html__('Admin','pointfindercoreelements')),
							'heading' => sprintf(esc_html__('Payment System (Membership System): %s Notifications', 'pointfindercoreelements'),esc_html__('Admin','pointfindercoreelements')),
							'fields' => array(
									/**
									*Free Payment completed email to ADMIN - done
									**/
				                    	
					                    array(
					                        'id'        => 'setup35_paymentmemberemails_newfreepayment-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Direct Payment Received Email Content', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after a succeeded direct payment process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentmemberemails_newfreepayment_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('New free plan ordered','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentmemberemails_newfreepayment_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('New free plan ordered','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentmemberemails_newfreepayment',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display order ID', 'pointfindercoreelements'),'%%orderid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display edit link', 'pointfindercoreelements'),'%%ordereditlink%%').'<br>'
						                        .sprintf(esc_html__('%s : Display packagename', 'pointfindercoreelements'),'%%packagename%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_paymentmemberemails_newfreepayment-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),

					                /**
									*Direct Payment completed email to ADMIN - done
									**/
				                    	
					                    array(
					                        'id'        => 'setup35_paymentmemberemails_newdirectpayment-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Direct Payment Received Email Content', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after a succeeded direct payment process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentmemberemails_newdirectpayment_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('New payment has been received','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentmemberemails_newdirectpayment_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('New payment has been received','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentmemberemails_newdirectpayment',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display order ID', 'pointfindercoreelements'),'%%orderid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display edit link', 'pointfindercoreelements'),'%%ordereditlink%%').'<br>'
						                        .sprintf(esc_html__('%s : Display payment total', 'pointfindercoreelements'),'%%paymenttotal%%').'<br>'
						                        .sprintf(esc_html__('%s : Display packagename', 'pointfindercoreelements'),'%%packagename%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_paymentmemberemails_newdirectpayment-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
				                   	/**
									*Recurring Payment completed email to ADMIN - done
									**/
					                    
					                    array(
					                        'id'        => 'setup35_paymentmemberemails_newrecpayment-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Recurring Payment Received Email Content', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after a succeeded recurring payment process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentmemberemails_newrecpayment_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Recurring Profile has been created','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentmemberemails_newrecpayment_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Recurring Profile has been created','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentmemberemails_newrecpayment',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display User ID', 'pointfindercoreelements'),'%%userid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display order number', 'pointfindercoreelements'),'%%ordernumber%%').'<br>'
						                        .sprintf(esc_html__('%s : Display order edit link', 'pointfindercoreelements'),'%%ordereditadminlink%%').'<br>'
						                        .sprintf(esc_html__('%s : Display payment total', 'pointfindercoreelements'),'%%paymenttotal%%').'<br>'
						                        .sprintf(esc_html__('%s : Display packagename', 'pointfindercoreelements'),'%%packagename%%').'<br>'
						                        .sprintf(esc_html__('%s : Display next payment date', 'pointfindercoreelements'),'%%nextpayment%%').'<br>'
						                        .sprintf(esc_html__('%s : Display recurring profile ID', 'pointfindercoreelements'),'%%profileid%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_paymentmemberemails_newrecpayment-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),

					                /**
									*Bank Payment received email to ADMIN
									**/
				                    	
					                    array(
					                        'id'        => 'setup35_paymentmemberemails_newbankpayment-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Bank Payment Request Received Email Content', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent after a succeeded bank payment request process.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentmemberemails_newbankpayment_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('New bank payment transfer request received','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentmemberemails_newbankpayment_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('New bank payment transfer request received','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentmemberemails_newbankpayment',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display order ID', 'pointfindercoreelements'),'%%orderid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display edit link', 'pointfindercoreelements'),'%%orderadminlink%%').'<br>'
						                        .sprintf(esc_html__('%s : Display payment total', 'pointfindercoreelements'),'%%paymenttotal%%').'<br>'
						                        .sprintf(esc_html__('%s : Display packagename', 'pointfindercoreelements'),'%%packagename%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_paymentmemberemails_newbankpayment-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
								)
						);
					/**
					*End: Payment Membership Email Contents (ADMIN)
					**/




					/**
					*Start: Expiry/Expired Email Contents
					**/
						$this->sections[] = array(
							'id' => 'setup35_autoemailsadmin',
							'subsection' => true,
							'title' => esc_html__('Auto System (PPP/Expiry)', 'pointfindercoreelements'),
							'fields' => array(
					                /**
									*Direct Payment before expire email content
									**/
				                    	
					                    array(
					                        'id'        => 'setup35_autoemailsadmin_directbeforeexpire-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Direct Payment: Item Expiring Notification ', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent before item expires.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentemails_directbeforeexpire_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Expiration date of your item','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentemails_directbeforeexpire_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Expiration date of your item','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentemails_directbeforeexpire',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item ID', 'pointfindercoreelements'),'%%itemid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display item title', 'pointfindercoreelements'),'%%itemname%%').'<br>'
						                        .sprintf(esc_html__('%s : Display expire date', 'pointfindercoreelements'),'%%expiredate%%').'<br>'
						                        .sprintf(esc_html__('%s : Display payment total', 'pointfindercoreelements'),'%%paymenttotal%%').'<br>'
						                        .sprintf(esc_html__('%s : Display packagename', 'pointfindercoreelements'),'%%packagename%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_autoemailsadmin_directbeforeexpire-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
					                /**
									*Direct Payment after expire email content
									**/
				                    	
					                    array(
					                        'id'        => 'setup35_autoemailsadmin_directafterexpire-start',
					                        'type'      => 'section',
					                        'title'     => sprintf(esc_html__('%s: Item Expired Notification', 'pointfindercoreelements'),esc_html__('Direct Payment','pointfindercoreelements')),
					                        'subtitle'  => esc_html__('This email will be sent after item is expired.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_autoemailsadmin_directafterexpire_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Your item has been expired','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_autoemailsadmin_directafterexpire_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Your item has been expired','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_autoemailsadmin_directafterexpire',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item ID', 'pointfindercoreelements'),'%%itemid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display item title', 'pointfindercoreelements'),'%%itemname%%').'<br>'
						                        .sprintf(esc_html__('%s : Display expire date', 'pointfindercoreelements'),'%%expiredate%%').'<br>'
						                        .sprintf(esc_html__('%s : Display payment total', 'pointfindercoreelements'),'%%paymenttotal%%').'<br>'
						                        .sprintf(esc_html__('%s : Display packagename', 'pointfindercoreelements'),'%%packagename%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_autoemailsadmin_directafterexpire-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
				                   	/**
									*Recurring Payment expired email content
									**/
					                    
					                    array(
					                        'id'        => 'setup35_autoemailsadmin_expiredrecpayment-start',
					                        'type'      => 'section',
					                        'title'     => sprintf(esc_html__('%s: Item Expired Notification', 'pointfindercoreelements'),esc_html__('Recurring Payment','pointfindercoreelements')),
					                        'subtitle'  => esc_html__('This email will be sent after item is expired.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentemails_expiredrecpayment_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Your item has been expired','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentemails_expiredrecpayment_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Your item has been expired','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentemails_expiredrecpayment',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item ID', 'pointfindercoreelements'),'%%itemid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display item title', 'pointfindercoreelements'),'%%itemname%%').'<br>'
						                        .sprintf(esc_html__('%s : Display payment total', 'pointfindercoreelements'),'%%paymenttotal%%').'<br>'
						                        .sprintf(esc_html__('%s : Display packagename', 'pointfindercoreelements'),'%%packagename%%').'<br>'
						                        .sprintf(esc_html__('%s : Display expire date', 'pointfindercoreelements'),'%%expiredate%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_autoemailsadmin_expiredrecpayment-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),

					                    

								)
						);
					/**
					*End: Expiry/Expired Email Contents
					**/


					/**
					*Start: Membership Expiry/Expired Email Contents
					**/
						$this->sections[] = array(
							'id' => 'setup35_autoemailsmemberadmin',
							'subsection' => true,
							'title' => esc_html__('Auto System (Member/Expiry)', 'pointfindercoreelements'),
							'fields' => array(
					                /**
									*Direct Payment before expire email content - done
									**/
				                    	
					                    array(
					                        'id'        => 'setup35_autoemailsmemberadmin_directbeforeexpire-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Direct Payment: Plan Expiring Notification ', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent before plan expires.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentmemberemails_directbeforeexpire_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Expiration date of your item','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentmemberemails_directbeforeexpire_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Expiration date of your item','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentmemberemails_directbeforeexpire',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display order ID', 'pointfindercoreelements'),'%%orderid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display expire date', 'pointfindercoreelements'),'%%expiredate%%').'<br>'
						                        .sprintf(esc_html__('%s : Display payment total', 'pointfindercoreelements'),'%%paymenttotal%%').'<br>'
						                        .sprintf(esc_html__('%s : Display packagename', 'pointfindercoreelements'),'%%packagename%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_autoemailsmemberadmin_directbeforeexpire-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
					                /**
									*Direct Payment after expire email content - done
									**/
				                    	
					                    array(
					                        'id'        => 'setup35_autoemailsmemberadmin_directafterexpire-start',
					                        'type'      => 'section',
					                        'title'     => sprintf(esc_html__('%s: Plan Expired Notification', 'pointfindercoreelements'),esc_html__('Direct Payment','pointfindercoreelements')),
					                        'subtitle'  => esc_html__('This email will be sent after plan is expired.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_autoemailsmemberadmin_directafterexpire_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Your item has been expired','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_autoemailsmemberadmin_directafterexpire_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Your item has been expired','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_autoemailsmemberadmin_directafterexpire',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display order ID', 'pointfindercoreelements'),'%%orderid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display expire date', 'pointfindercoreelements'),'%%expiredate%%').'<br>'
						                        .sprintf(esc_html__('%s : Display payment total', 'pointfindercoreelements'),'%%paymenttotal%%').'<br>'
						                        .sprintf(esc_html__('%s : Display packagename', 'pointfindercoreelements'),'%%packagename%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_autoemailsmemberadmin_directafterexpire-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
				                   	/**
									*Recurring Payment expired email content - done
									**/
					                    
					                    array(
					                        'id'        => 'setup35_autoemailsmemberadmin_expiredrecpayment-start',
					                        'type'      => 'section',
					                        'title'     => sprintf(esc_html__('%s: Plan Expired Notification', 'pointfindercoreelements'),esc_html__('Recurring Payment','pointfindercoreelements')),
					                        'subtitle'  => esc_html__('This email will be sent after plan is expired.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_paymentmemberemails_expiredrecpayment_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Recurring Profile Cancelled','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_paymentmemberemails_expiredrecpayment_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Recurring Profile Cancelled','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_paymentmemberemails_expiredrecpayment',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display order ID', 'pointfindercoreelements'),'%%orderid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display payment total', 'pointfindercoreelements'),'%%paymenttotal%%').'<br>'
						                        .sprintf(esc_html__('%s : Display packagename', 'pointfindercoreelements'),'%%packagename%%').'<br>'
						                        .sprintf(esc_html__('%s : Display expire date', 'pointfindercoreelements'),'%%expiredate%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_autoemailsmemberadmin_expiredrecpayment-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
								)
						);
					/**
					*End: Membership Expiry/Expired Email Contents
					**/




					/**
					*Start: Item Contact Form Email Contents
					**/
						$this->sections[] = array(
							'id' => 'setup35_itemcontact',
							'subsection' => true,
							'title' => esc_html__('Item Contact Form', 'pointfindercoreelements'),
							'fields' => array(
					                /**
									*Item Contact Form to User email content
									**/
				                    	
					                    array(
					                        'id'        => 'setup35_itemcontact_enquiryformuser-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Item Contact Form: To User', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent when a user item contact form submitted.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_itemcontact_enquiryformuser_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Contact Form Received','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_itemcontact_enquiryformuser_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Contact Form Received','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_itemcontact_enquiryformuser',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item info', 'pointfindercoreelements'),'%%iteminfo%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender name', 'pointfindercoreelements'),'%%name%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender email', 'pointfindercoreelements'),'%%email%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender phone', 'pointfindercoreelements'),'%%phone%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender message', 'pointfindercoreelements'),'%%message%%').'<br>'
						                        .sprintf(esc_html__('%s : Display date time', 'pointfindercoreelements'),'%%date%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_itemcontact_enquiryformuser-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
					                /**
									*Item Contact Form to Admin email content
									**/
				                    	
					                    array(
					                        'id'        => 'setup35_itemcontact_enquiryformadmin-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Item Contact Form: To Admin', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent when a user item contact form submitted.(A copy to Admin)', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_itemcontact_enquiryformadmin_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('(User) Contact Form Received','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_itemcontact_enquiryformadmin_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('(User) Contact Form Received','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_itemcontact_enquiryformadmin',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item info', 'pointfindercoreelements'),'%%iteminfo%%').'<br>'
						                        .sprintf(esc_html__('%s : Display user info', 'pointfindercoreelements'),'%%userinfo%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender name', 'pointfindercoreelements'),'%%name%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender email', 'pointfindercoreelements'),'%%email%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender phone', 'pointfindercoreelements'),'%%phone%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender message', 'pointfindercoreelements'),'%%message%%').'<br>'
						                        .sprintf(esc_html__('%s : Display date time', 'pointfindercoreelements'),'%%date%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_itemcontact_enquiryformadmin-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),

								)
						);
					/**
					*End: Item Contact Form Email Contents
					**/





					/**
					*Start: Item Review Form Email Contents
					**/
						$this->sections[] = array(
							'id' => 'setup35_itemreview',
							'subsection' => true,
							'title' => esc_html__('Item Review Form', 'pointfindercoreelements'),
							'fields' => array(
					                /**
									*Item Review Form to User email content
									**/
				                    	
					                    array(
					                        'id'        => 'setup35_itemreview_reviewformuser-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Item Review Form: To User', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent when a user item review form submitted.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_itemreview_reviewformuser_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Review Form Received','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_itemreview_reviewformuser_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Review Form Received','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_itemreview_reviewformuser',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item info', 'pointfindercoreelements'),'%%iteminfo%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender name', 'pointfindercoreelements'),'%%name%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender email', 'pointfindercoreelements'),'%%email%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender message', 'pointfindercoreelements'),'%%message%%').'<br>'
						                        .sprintf(esc_html__('%s : Display date time', 'pointfindercoreelements'),'%%date%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_itemreview_reviewformuser-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
					                /**
									*Item Review Form to Admin email content
									**/
				                    	
					                    array(
					                        'id'        => 'setup35_itemreview_reviewformadmin-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Item Review Form: To Admin', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent when a user item review form submitted.(A copy to Admin)', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_itemreview_reviewformadmin_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('(User) Review Form Received','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_itemreview_reviewformadmin_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('(User) Review Form Received','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_itemreview_reviewformadmin',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item info', 'pointfindercoreelements'),'%%iteminfo%%').'<br>'
						                        .sprintf(esc_html__('%s : Display review edit link', 'pointfindercoreelements'),'%%reveditlink%%').'<br>'
						                        .sprintf(esc_html__('%s : Display user info', 'pointfindercoreelements'),'%%userinfo%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender name', 'pointfindercoreelements'),'%%name%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender email', 'pointfindercoreelements'),'%%email%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender message', 'pointfindercoreelements'),'%%message%%').'<br>'
						                        .sprintf(esc_html__('%s : Display date time', 'pointfindercoreelements'),'%%date%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_itemreview_reviewformadmin-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),

					                /**
									*Item Review Flag Form to Admin email content
									**/
				                    	
					                    array(
					                        'id'        => 'setup35_itemreview_reviewflagformadmin-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Item Review Flag Form: To Admin', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent when a review comment has been flagged.(to Admin)', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_itemreview_reviewflagformadmin_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('A review flagged for re-check','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_itemreview_reviewflagformadmin_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('A review flagged for re-check','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_itemreview_reviewflagformadmin',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display review info', 'pointfindercoreelements'),'%%reviewinfo%%').'<br>'
						                        .sprintf(esc_html__('%s : Display user info', 'pointfindercoreelements'),'%%userinfo%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender name', 'pointfindercoreelements'),'%%name%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender email', 'pointfindercoreelements'),'%%email%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender reason', 'pointfindercoreelements'),'%%message%%').'<br>'
						                        .sprintf(esc_html__('%s : Display date time', 'pointfindercoreelements'),'%%date%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_itemreview_reviewflagformadmin-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),

								)
						);
					/**
					*End: Item Review Form Email Contents
					**/



					/**
					*Start: Item Report Form Email Contents
					**/
						$this->sections[] = array(
							'id' => 'setup35_itemreport',
							'subsection' => true,
							'title' => esc_html__('Item Report Form', 'pointfindercoreelements'),
							'fields' => array(
					                /**
									*Item Report Form to User email content
									**/
				                    	
					                    array(
					                        'id'        => 'setup35_itemcontact_report-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Item Report Form: To User', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent when a user item report form, submitted.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_itemcontact_report_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Item Report Form Received','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_itemcontact_report_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Item Report Form Received','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_itemcontact_report',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item info', 'pointfindercoreelements'),'%%iteminfo%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender name', 'pointfindercoreelements'),'%%name%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender email', 'pointfindercoreelements'),'%%email%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender message', 'pointfindercoreelements'),'%%message%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender UserID', 'pointfindercoreelements'),'%%userid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display date time', 'pointfindercoreelements'),'%%date%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_itemcontact_report-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
					               

								)
						);
					/**
					*End: Item Report Form Email Contents
					**/


					/**
					*Start: Item Claim Form Email Contents
					**/
						$this->sections[] = array(
							'id' => 'setup35_itemclaim',
							'subsection' => true,
							'title' => esc_html__('Item Claim Form', 'pointfindercoreelements'),
							'fields' => array(
					                /**
									*Item Claim Form to Admin email content
									**/
				                    	
					                    array(
					                        'id'        => 'setup35_itemcontact_claim-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Item Claim Form: To Admin', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent when a user item claim form, submitted.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_itemcontact_claim_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Item Claim Form Received','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_itemcontact_claim_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Item Claim Form Received','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_itemcontact_claim',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item info', 'pointfindercoreelements'),'%%iteminfo%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender name', 'pointfindercoreelements'),'%%name%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender email', 'pointfindercoreelements'),'%%email%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender phone', 'pointfindercoreelements'),'%%phone%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender message', 'pointfindercoreelements'),'%%message%%').'<br>'
						                        .sprintf(esc_html__('%s : Display sender UserID', 'pointfindercoreelements'),'%%userid%%').'<br>'
						                        .sprintf(esc_html__('%s : Display date time', 'pointfindercoreelements'),'%%date%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_itemcontact_claim-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),
					                /**
									*Item Claim Form to User email content
									**/
				                    	
					                    array(
					                        'id'        => 'setup35_itemcontact_claimu-start',
					                        'type'      => 'section',
					                        'title'     => esc_html__('Item Claim Approved: To User', 'pointfindercoreelements'),
					                        'subtitle'  => esc_html__('This email will be sent when a user accepted and author changed.', 'pointfindercoreelements'),
					                        'indent'    => true, 
					                        
					                    ),
					                    	array(
						                        'id'        => 'setup35_itemcontact_claimu_subject',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Subject', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Your claim request approved','pointfindercoreelements'),
						                    ),
											array(
						                        'id'        => 'setup35_itemcontact_claimu_title',
						                        'type'      => 'text',
						                        'title'     => esc_html__('Email Title', 'pointfindercoreelements'),
						                        'default'	=> esc_html__('Claim request approved','pointfindercoreelements'),
						                    ),
										 	array(
						                        'id'        => 'setup35_itemcontact_claimu',
						                        'type'      => 'editor',
						                        'args'	=> array(
						                        	'media_buttons'	=> false,
						                        	'teeny'	=> true
						                        	),
						                        'title'     => esc_html__('Email Content', 'pointfindercoreelements'),
						                        'subtitle'	=> sprintf(esc_html__('%s : Display item info', 'pointfindercoreelements'),'%%iteminfo%%').'<br>'
						                        .sprintf(esc_html__('%s : Display date time', 'pointfindercoreelements'),'%%date%%').'<br>',
						                        'validate'  => 'html',
						                    ),
						                array(
					                        'id'        => 'setup35_itemcontact_claimu-end',
					                        'type'      => 'section',
					                        'indent'    => false, 
					                    ),

								)
						);
					/**
					*End: Item Claim Form Email Contents
					**/



				/**
				*End: Email Contents
				**/


				/**
				*Start: Email Template Settings
				**/
					$this->sections[] = array(
						'id' => 'setup35_template',
						'icon' => 'el-icon-website-alt',
						'title' => esc_html__('Email Template', 'pointfindercoreelements'),
						'fields' => array(
								array(
									'id' => 'setup35_template_rtl',
									'type' => 'button_set',
									'title' => esc_html__('Text Direction', 'pointfindercoreelements') ,
									'options' => array(
										'1' => esc_html__('Show Right to Left', 'pointfindercoreelements') ,
										'0' => esc_html__('Show Left to Right', 'pointfindercoreelements')
									) ,
									'default' => '0'
									
								) ,

								array(
									'id' => 'setup35_template_logo',
									'type' => 'button_set',
									'title' => esc_html__('Template Logo', 'pointfindercoreelements') ,
									'options' => array(
										'1' => esc_html__('Show Logo', 'pointfindercoreelements') ,
										'0' => esc_html__('Show Text', 'pointfindercoreelements')
									) ,
									'default' => '1'
									
								) ,

								array(
			                        'id'        => 'setup35_template_logotext',
			                        'type'      => 'text',
			                        'title'     => esc_html__('Logo Text', 'pointfindercoreelements'),
			                        'required'   => array('setup35_template_logo','=','0'),
			                        'text_hint' => array(
			                            'title'     => '',
			                            'content'   => esc_html__('Please type your logo text. Ex: Pointfinder','pointfindercoreelements')
			                        )
			                    ),

								array(
			                        'id'        => 'setup35_template_mainbgcolor',
			                        'type'      => 'color',
			                        'title'     => esc_html__('Main Background Color', 'pointfindercoreelements'),
			                        'default'   => '#F0F1F3',
			                        'validate'  => 'color',
			                        'transparent'	=> false
			                    ),

								array(
			                        'id'        => 'setup35_template_headerfooter',
			                        'type'      => 'color',
			                        'title'     => esc_html__('Header / Footer: Background Color', 'pointfindercoreelements'),
			                        'default'   => '#f7f7f7',
			                        'validate'  => 'color',
			                         'transparent'	=> false
			                    ),

			                    array(
			                        'id'        => 'setup35_template_headerfooter_line',
			                        'type'      => 'color',
			                        'title'     => esc_html__('Header / Footer: Line Color', 'pointfindercoreelements'),
			                        'default'   => '#F25555',
			                        'validate'  => 'color',
			                         'transparent'	=> false
			                    ),

			                    
			                    array(
			                        'id'        => 'setup35_template_headerfooter_text',
			                        'type'      => 'link_color',
			                        'title'     => esc_html__('Header / Footer: Text/Link Color', 'pointfindercoreelements'),
			                        //'regular'   => false, 
			                        //'hover'     => false,
			                        'active'    => false,
			                        'visited'   => false,
			                        'default'   => array(
			                            'regular'   => '#494949',
			                            'hover'     => '#F25555',
			                        )
			                    ),

			                    array(
			                        'id'        => 'setup35_template_contentbg',
			                        'type'      => 'color',
			                        'title'     => esc_html__('Content: Background Color', 'pointfindercoreelements'),
			                        'default'   => '#ffffff',
			                        'validate'  => 'color',
			                         'transparent'	=> false
			                    ),

			                    array(
			                        'id'        => 'setup35_template_contenttext',
			                        'type'      => 'link_color',
			                        'title'     => esc_html__('Content: Text/Link Color', 'pointfindercoreelements'),
			                        //'regular'   => false, 
			                        //'hover'     => false,
			                        'active'    => false,
			                        'visited'   => false,
			                        'default'   => array(
			                            'regular'   => '#494949',
			                            'hover'     => '#F25555',
			                        )
			                    ),

								array(
			                        'id'        => 'setup35_template_footertext',
			                        'type'      => 'textarea',
			                        'title'     => esc_html__('Footer Text', 'pointfindercoreelements'),
			                        'desc'		=> esc_html__('%%siteurl%% : Site URL', 'pointfindercoreelements').'<br>'.esc_html__('%%sitename%% : Site Name', 'pointfindercoreelements'),
			                        'default'	=> 'This is an automated email from <a href="%%siteurl%%">%%sitename%%</a>'
			                    ),
							)
					);
				/**
				*End: Email Template Settings
				**/
			/**
			*EMAIL SETTINS
			**/
			
		}

		public function setArguments(){
			$this->args = array(
				'opt_name'             => 'pointfindermail_options',
				"global_variable" 	   => "pointfindermail_option",
                'display_name'         => esc_html__('Point Finder Mail System Config','pointfindercoreelements'),
                'menu_type'            => 'submenu',
                'page_parent'          => 'pointfinder_tools',
                'menu_title'           => esc_html__('Mail System Config','pointfindercoreelements'),
                'page_title'           => esc_html__('Point Finder Mail System Config', 'pointfindercoreelements'),
                'admin_bar'            => false,
                'allow_sub_menu'       => false,
                'admin_bar_priority'   => 50,
                'global_variable'      => '',
                'dev_mode'             => false,
                'update_notice'        => false,
                'menu_icon'            => 'dashicons-email',
                'page_slug'            => '_pfmailoptions',
                'save_defaults'        => true,
                'default_show'         => false,
                'default_mark'         => '',
                'transient_time'       => 60 * MINUTE_IN_SECONDS,
                'output'               => false,
                'output_tag'           => false,
                'database'             => '',
                'system_info'          => false,
                'hide_reset'           => true,
                'update_notice'        => false,

			);
			$this->args['global_variable'] = 'pointfindermail_option';

		}
	}
	new Redux_Framework_PF_Mail_Config();
}