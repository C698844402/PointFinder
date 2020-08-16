<?php
namespace PointFinderElementorSYS\Widgets;


use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use PointFinderElementorSYS\Helper;


if ( ! defined( 'ABSPATH' ) ) exit;

class PointFinder_Contact_Form extends Widget_Base {



	public function show_in_panel() { return true; }

	public function get_keywords() { return [ 'pointfinder', 'contact form', 'form', 'contact' ]; }

	public function get_name() { return 'pointfindercontactform'; }

	public function get_title() { return esc_html__( 'PF Contact Form', 'pointfindercoreelements' ); }

	public function get_icon() { return 'eicon-form-horizontal'; }

	public function get_categories() { return [ 'pointfinder_elements' ]; }


	public function get_script_depends() {
	    return [];
	}

	protected function _register_controls() {


		$this->start_controls_section(
			'contactform_general',
			[
				'label' => esc_html__( 'General', 'pointfindercoreelements' ),
				'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);

			$this->add_control(
				'contact_subject',
				[
					'label' => esc_html__( 'Subject Field', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'ON', 'pointfindercoreelements' ),
					'label_off' => esc_html__( 'OFF', 'pointfindercoreelements' ),
					'return_value' => 'yes',
					'default' => 'yes',
					'render_type' => 'theme'
				]
			);
			$this->add_control(
				'contact_phone',
				[
					'label' => esc_html__( 'Phone Field', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'ON', 'pointfindercoreelements' ),
					'label_off' => esc_html__( 'OFF', 'pointfindercoreelements' ),
					'return_value' => 'yes',
					'default' => 'yes',
					'render_type' => 'theme'
				]
			);
			$this->add_control(
				'contact_mes',
				[
					'label' => esc_html__( 'Message Field', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'ON', 'pointfindercoreelements' ),
					'label_off' => esc_html__( 'OFF', 'pointfindercoreelements' ),
					'return_value' => 'yes',
					'default' => 'yes',
					'render_type' => 'theme'
				]
			);
			$this->add_control(
				'contact_re',
				[
					'label' => esc_html__( 'reCaptcha Field', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'ON', 'pointfindercoreelements' ),
					'label_off' => esc_html__( 'OFF', 'pointfindercoreelements' ),
					'return_value' => 'yes',
					'default' => 'yes',
					'render_type' => 'theme'
				]
			);
		$this->end_controls_section();

	}

	

	protected function render() {

		$settings = $this->get_settings_for_display();


		extract(array(
			'contact_subject' => isset($settings['contact_subject'])?$settings['contact_subject']:'',
			'contact_phone' => isset($settings['contact_phone'])?$settings['contact_phone']:'',
			'contact_mes' => isset($settings['contact_mes'])?$settings['contact_mes']:'',
			'contact_re' => isset($settings['contact_re'])?$settings['contact_re']:'',
			'form_type' => 1
		));
		


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

		echo $output;
	
	}


}
