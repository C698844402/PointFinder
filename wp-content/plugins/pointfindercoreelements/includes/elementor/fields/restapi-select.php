<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MX_Control_Select2 extends Base_Data_Control {

	const MXSELECT2 = 'mxselect2';

	public function get_type() {
		return self::MXSELECT2;
	}

	protected function get_default_settings() {
		return [
			'options' => [],
			'multiple' => false,
			'select2options' => [],
		];
	}


	public function enqueue() {
		wp_register_script( 'mxselect2-control', PFCOREELEMENTSURL . 'includes/elementor/fields/js/restapi-select.js', [ 'jquery-elementor-select2', 'jquery' ], '1.0.0' );
		wp_enqueue_script( 'mxselect2-control' );
	}


	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field mx-select2">
			<# if ( data.label ) {#>
				<label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper">
				<# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
				<select id="<?php echo $control_uid; ?>" class="elementor-select2" type="mxselect2" {{ multiple }} data-setting="{{ data.name }}" data-selected="{{ data.controlValue }}">
					<# 
					_.each( data.options, function( option_title, option_value ) {
						var value = data.controlValue;
						if ( typeof value == 'string' ) {
							var selected = ( option_value === value ) ? 'selected' : '';
						} else if ( null !== value ) {
							var value = _.values( value );
							var selected = ( -1 !== value.indexOf( option_value ) ) ? 'selected' : '';
						}
						#>
					<option {{ selected }} value="{{ option_value }}">{{{ option_title }}}</option>
					<# } ); #>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}