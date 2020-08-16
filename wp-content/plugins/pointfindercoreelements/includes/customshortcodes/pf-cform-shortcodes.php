<?php

/*
*
* Visual Composer PointFinder Contact Form Shortcode
*
*/

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

class PointFinderContactFrmShortcode extends WPBakeryShortCode {
	use PointFinderOptionFunctions;

    function __construct() {
        add_action( 'vc_after_init', array( $this, 'pointfinder_single_pf_contactform_module_mapping' ) );
        add_shortcode( 'pf_contactform', array( $this, 'pointfinder_single_pf_contactform_module_html' ) );
    }

    

    public function pointfinder_single_pf_contactform_module_mapping() {

		if ( !defined( 'WPB_VC_VERSION' ) ) {
		  return;
		}

		/**
		*Start : Contact Form ----------------------------------------------------------------------------------------------------
		**/
			vc_map( array(
				"name" => esc_html__("PF Contact Form", 'pointfindercoreelements'),
				"base" => "pf_contactform",
				"icon" => "pf_contactform",
				"category" => esc_html__("Point Finder", "pointfindercoreelements"),
				"description" => esc_html__('Contact Form', 'pointfindercoreelements'),
				"params" => array(
					array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("Subject Field", "pointfindercoreelements"),
					  "param_name" => "contact_subject",
					  "description" => esc_html__("Enables subject field.", "pointfindercoreelements"),
					  "value" => array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes')
					),
					array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("Phone Field", "pointfindercoreelements"),
					  "param_name" => "contact_phone",
					  "description" => esc_html__("Enables phone field.", "pointfindercoreelements"),
					  "value" => array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
					),
					array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("Message Field", "pointfindercoreelements"),
					  "param_name" => "contact_mes",
					  "description" => esc_html__("Enables message field.", "pointfindercoreelements"),
					  "value" => array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
					),
					array(
					  "type" => 'checkbox',
					  "heading" => esc_html__("reCaptcha Field", "pointfindercoreelements"),
					  "param_name" => "contact_re",
					  "description" => esc_html__("Enables reCaptcha field.", "pointfindercoreelements"),
					  "value" => array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
					)
				)
			) );
		/**
		*Start : Contact Form ----------------------------------------------------------------------------------------------------
		**/

    }


    public function pointfinder_single_pf_contactform_module_html( $atts ) {

        extract( shortcode_atts( array(
			'contact_subject' => '',
			'contact_phone' => '',
			'contact_mes' => '',
			'contact_re' => '',
			'form_type' => 1
		), $atts ) );


		global $wpdb;
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


		/* Define Fields */
		$name = $email = $subject = $phone = $message = '';

		/* Name Field */
		$name .= "\n\t\t\t\t\t\t\t\t".'<label class="lbl-ui">';
	    $name .= "\n\t\t\t\t\t\t\t\t\t".'<input type="text" name="name" class="input" placeholder="'.esc_html__('Name','pointfindercoreelements').'"/>';
	    $name .= "\n\t\t\t\t\t\t\t\t".'</label>';


	    /* Email Field */
	    $email .= "\n\t\t\t\t\t\t\t\t".'<label class="lbl-ui">';
	    $email .= "\n\t\t\t\t\t\t\t\t\t".'<input type="email" name="email" class="input" placeholder="'.esc_html__('Email','pointfindercoreelements').'"/>';
	    $email .= "\n\t\t\t\t\t\t\t\t".'</label>';


	    /* Subject Field */
	    $subject .= "\n\t\t\t\t\t\t\t\t".'<label class="lbl-ui">';
	    $subject .= "\n\t\t\t\t\t\t\t\t\t".'<input type="text" name="subject" class="input" placeholder="'.esc_html__('Subject','pointfindercoreelements').'"/>';
	    $subject .= "\n\t\t\t\t\t\t\t\t".'</label>';


	    /* Phone Field */
	    $phone .= "\n\t\t\t\t\t\t\t\t".'<label class="lbl-ui">';
	    $phone .= "\n\t\t\t\t\t\t\t\t\t".'<input type="text" name="phone" class="input" placeholder="'.esc_html__('Phone','pointfindercoreelements').'"/>';
	    $phone .= "\n\t\t\t\t\t\t\t\t".'</label>';


	    /* Message Field */
	    $message .= "\n\t\t\t\t\t\t".'<label class="lbl-ui">';
	    $message .= "\n\t\t\t\t\t\t\t".'<textarea name="msg" class="textarea" placeholder="'.esc_html__('Message','pointfindercoreelements').'"></textarea>';
	    $message .= "\n\t\t\t\t\t\t".'</label>  ';


	    $message .= '
	    <div style="position:relative; margin-top:15px">
	    <span class="goption upt">
	        <label class="options">
	            <input type="checkbox" id="pftermsofuser" name="pftermsofuser" value="1">
	            <span class="checkbox"></span>
	        </label>
	        <label for="check1" class="upt1ch1">'.wp_sprintf(esc_html__( 'I have read the %s terms and conditions %s and accept them.', 'pointfindercoreelements' ),'<a href="'.$terms_permalink.'" class="pftermshortc2"><strong>','</strong></a>').'</label>
	    </span>
	    </div>
		';
		$recaptcha_placeholder = '';
		if (class_exists('Pointfinder_reCaptcha_System')) {
	        $reCaptcha = new Pointfinder_reCaptcha_System();
	        $recaptcha_placeholder = $reCaptcha->create_recaptcha("pf-contact-form");
	    }


		$output = "\n\t".'<div class="golden-forms">';
		$output .= '<div id="pfmdcontainer-overlay" class="pftrwcontainer-overlay"></div>';
	    $output .= "\n\t\t\t".'<form id="pf-contact-form">';

	    $output .= '<div class="pfsearchformerrors">';
	    $output .= '<ul></ul>';
	    $output .= '<a class="button pfsearch-err-button"><i class="fas fa-times"></i> '.esc_html__('CLOSE','pointfindercoreelements').'</a>';
	    $output .= '</div>';

	    if($form_type == 1 && $contact_phone == 'yes'){
		    $output .= "\n\t\t\t\t\t".'<section>';
		    $output .= "\n\t\t\t\t\t\t".'<div class="row">';

		    $output .= "\n\t\t\t\t\t\t\t".'<div class="col4 first">';
		 	$output .= $name;
		    $output .= "\n\t\t\t\t\t\t\t".'</div>';

		    $output .= "\n\t\t\t\t\t\t\t".'<div class="col4">';
		 	$output .= $email;
		    $output .= "\n\t\t\t\t\t\t\t".'</div>';

		    $output .= "\n\t\t\t\t\t\t\t".'<div class="col4 last colspacer-two">';
		  	$output .= $phone;
		    $output .= "\n\t\t\t\t\t\t\t".'</div>';

		    $output .= "\n\t\t\t\t\t\t".'</div>';/*end row*/
		    $output .= "\n\t\t\t\t\t".'</section>  ';

		}elseif ($form_type == 2 || ($form_type == 1 && $contact_phone != 'yes')) {

			$output .= "\n\t\t\t\t\t".'<section>';
		    $output .= "\n\t\t\t\t\t\t".'<div class="row">';

		    $output .= "\n\t\t\t\t\t\t\t".'<div class="col6 first">';
		 	$output .= $name;
		    $output .= "\n\t\t\t\t\t\t\t".'</div>';

		    $output .= "\n\t\t\t\t\t\t\t".'<div class="col6 last colspacer-two">';
		  	$output .= $email;
		    $output .= "\n\t\t\t\t\t\t\t".'</div>';

		    $output .= "\n\t\t\t\t\t\t".'</div>';/*end row*/
		    $output .= "\n\t\t\t\t\t".'</section>  ';
		}

		if(($form_type == 1 || $form_type == 2) && $contact_phone != 'yes' && $contact_subject == 'yes'){
		    $output .= "\n\t\t\t\t\t".'<section>  ';
		    $output .= $subject;
		    $output .= "\n\t\t\t\t\t".'</section>  ';
		}elseif ($form_type == 2 && $contact_phone == 'yes' && $contact_subject == 'yes') {
			$output .= "\n\t\t\t\t\t".'<section>';
		    $output .= "\n\t\t\t\t\t\t".'<div class="row">';

		    $output .= "\n\t\t\t\t\t\t\t".'<div class="col6 first">';
		 	$output .= $subject;
		    $output .= "\n\t\t\t\t\t\t\t".'</div>';

		    $output .= "\n\t\t\t\t\t\t\t".'<div class="col6 last colspacer-two">';
		  	$output .= $phone;
		    $output .= "\n\t\t\t\t\t\t\t".'</div>';

		    $output .= "\n\t\t\t\t\t\t".'</div>';/*end row*/
		    $output .= "\n\t\t\t\t\t".'</section>  ';
		}elseif ($form_type == 1 && $contact_subject == 'yes') {
			$output .= "\n\t\t\t\t\t".'<section>';
		 	$output .= $subject;
		 	$output .= "\n\t\t\t\t\t".'</section>  ';

		}elseif(($form_type == 2) && $contact_phone == 'yes' && $contact_subject != 'yes'){
			$output .= "\n\t\t\t\t\t".'<section>  ';
		    $output .= $phone;
		    $output .= "\n\t\t\t\t\t".'</section>  ';
		}

		if($contact_mes == 'yes'){
		    $output .= "\n\t\t\t\t\t".'<section>';
		    $output .= $message;
		    $output .= "\n\t\t\t\t\t".'</section>';
		}

		$output .= $recaptcha_placeholder;


	    $output .= "\n\t\t\t\t\t".'<section> ';
	    $output .= "\n\t\t\t\t\t\t".'<input type="hidden" value="'.$contact_re.'" name="contact_re" />';
	    $output .= "\n\t\t\t\t\t\t".'<input id="pf-contact-form-submit" type="submit" value="'.esc_html__('Send','pointfindercoreelements').'" class="button green" style="width: 120px;" />';
	    $output .= "\n\t\t\t\t\t".'</section>              ';


	    $output .= "\n\t\t\t".'</form>';
	    $output .= "\n\t".'</div>';

		return $output;

    }

}
new PointFinderContactFrmShortcode();

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_pointfinder_single_pf_contactform extends WPBakeryShortCode {
    }
}

?>
