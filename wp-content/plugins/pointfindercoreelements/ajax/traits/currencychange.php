<?php 
if (class_exists('PointFinderCurrencySYSHandler')) {
	return;
}


class PointFinderCurrencySYSHandler extends Pointfindercoreelements_AJAX
{
    public function __construct(){}

    public function pf_ajax_currencychange(){

		check_ajax_referer( 'pfget_modalsystemhandler', 'security' );

		header('Content-Type: application/json; charset=UTF-8;');

		$value = '';
		if(isset($_POST['value']) && $_POST['value']!=''){
			$value = esc_attr($_POST['value']);
		}

		/* Check if currency changed */
			$st9_currency_from = $this->PFASSIssetControl('st9_currency_from','','');
			$st9_currency_to = $this->PFASSIssetControl('st9_currency_to','','');

			if (!empty($st9_currency_from)) {

				$old_currencyfield = get_option('pointfinder_currency_fields');
				$new_currencyfield = $st9_currency_from.$st9_currency_to;

				if ($old_currencyfield != $new_currencyfield) {
					update_option( 'pointfinder_currency_fields', $new_currencyfield );
					$this->pointfinder_currency_schedule();
				}
			}


		/* Check if currency selected */
			if (!empty($value)) {
				$selected_currency = sanitize_text_field($value);
				setcookie('pointfinder_c_code', $selected_currency, time() + 86400, "/");
			}

			echo json_encode('done');
		die();
	}
}