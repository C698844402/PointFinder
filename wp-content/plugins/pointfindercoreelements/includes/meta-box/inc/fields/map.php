<?php

class RWMB_Map_Field extends RWMB_Field {

	public static function admin_enqueue_scripts() {
		wp_enqueue_style( 'rwmb-map', RWMB_CSS_URL . 'map.css', array(), RWMB_VER );

		$args            = func_get_args();
		$field           = $args[0];
	}

	public static function html( $meta, $field ) {
		$address = is_array( $field['address_field'] ) ? implode( ',', $field['address_field'] ) : $field['address_field'];
		$html    = sprintf(
			'<div class="rwmb-map-field" data-address-field="%s">',
			esc_attr( $address )
		);


		$stp5_mapty = PFSAIssetControl('stp5_mapty','',1);
    	$wemap_here_appid = $wemap_here_appcode = $we_special_key = '';
		switch ($stp5_mapty) {
			case 1:
				$we_special_key = PFSAIssetControl('setup5_map_key','','');
				break;

			case 3:
				$we_special_key = PFSAIssetControl('stp5_mapboxpt','','');
				break;

			case 5:
				$wemap_here_appid = PFSAIssetControl('wemap_here_appid','','');
				$wemap_here_appcode = PFSAIssetControl('wemap_here_appcode','','');
				break;

			case 6:
				$we_special_key = PFSAIssetControl('wemap_bingmap_api_key','','');
				break;

			case 4:
				$we_special_key = PFSAIssetControl('wemap_yandexmap_api_key','','');
				break;
		}
		$wemap_geoctype = PFSAIssetControl('wemap_geoctype','','');


		if (!empty($meta)) {
			$defloca = explode(",",esc_attr($meta));
		}else{
			$defloca = explode(",",esc_attr($field['std']));
		}

		
		$html .= '<a class="button" id="pf_search_geolocateme" data-istatus="false" title="'.esc_html__('Locate me!','pointfindercoreelements').'">
		<img src="'.PFCOREELEMENTSURL .'images/geoicon.svg" width="16px" height="16px" class="pf-search-locatemebut injectable" alt="'.esc_html__('Locate me!','pointfindercoreelements').'">
		<div class="pf-search-locatemebutloading"></div>
		</a>';
		$html .= 
			'<div id="pfupload_map" style="width: 100%;height: 300px;border:0" 
    		data-lat="'.$defloca[0].'" 
    		data-lng="'.$defloca[1].'" 
    		data-zoom="14" 
    		data-zoommx="14" 
    		data-mtype="'.$stp5_mapty.'" 
    		data-key="'.$we_special_key.'" 
    		data-hereappid="'.$wemap_here_appid.'" 
			data-hereappcode="'.$wemap_here_appcode.'" 
			data-geoctype="'.$wemap_geoctype.'" 
			data-pf-istatus="false" class="rwmb-map-canvas" data-default-loc="'.esc_attr( $field['std'] ).'" data-region="'.esc_attr( $field['region'] ).'" data-text1="'.esc_html__( "No results found for {{query}}", "pointfindercoreelements" ).'"></div>';
		

		
		
		$html .= sprintf(
			"<input type='text' name='%s' class='rwmb-map-coordinate' value='%s' style='%s'>",
			esc_attr( $field['field_name'] ),
			esc_attr( $meta ),
			'width:50%'
		);
		$html .= '';
		$html .= '<input name="findaddress" type="button" class="button button-primary button-large rwmb-map-coordinate-find" id="rwmb-map-coordinate-find" value="'.esc_html__( "Find Coordinate", "pointfindercoreelements" ).'"></div>';

		return $html;
	}

	public static function normalize( $field ) {
		$field = parent::normalize( $field );
		$field = wp_parse_args(
			$field,
			array(
				'std'           => '',
				'address_field' => '',
				'language'      => '',
				'region'        => '',

			)
		);

		return $field;
	}

	public static function get_value( $field, $args = array(), $post_id = null ) {
		$value                               = parent::get_value( $field, $args, $post_id );
		list( $latitude, $longitude, $zoom ) = explode( ',', $value . ',,' );
		return compact( 'latitude', 'longitude', 'zoom' );
	}

	public static function the_value( $field, $args = array(), $post_id = null ) {
		$value = parent::get_value( $field, $args, $post_id );
		$args  = wp_parse_args(
			$args,
			array(
				'api_key' => isset( $field['api_key'] ) ? $field['api_key'] : '',
			)
		);
		return self::render_map( $value, $args );
	}

	
	public static function render_map( $location, $args = array() ) {
		list( $latitude, $longitude, $zoom ) = explode( ',', $location . ',,' );
		if ( ! $latitude || ! $longitude ) {
			return '';
		}

		$args = wp_parse_args(
			$args,
			array(
				'latitude'     => $latitude,
				'longitude'    => $longitude,
				'width'        => '100%',
				'height'       => '480px',
				'marker'       => true,
				'marker_title' => '',
				'info_window'  => '',
				'js_options'   => array(),
				'zoom'         => $zoom,

			)
		);

		
		$args['js_options'] = wp_parse_args(
			$args['js_options'],
			array(
				'zoom'      => $args['zoom'],
				'mapTypeId' => 'ROADMAP',
			)
		);

		$output = sprintf(
			'<div class="rwmb-map-canvas" data-map_options="%s" style="width:%s;height:%s"></div>',
			esc_attr( wp_json_encode( $args ) ),
			esc_attr( $args['width'] ),
			esc_attr( $args['height'] )
		);
		return $output;
	}
}
