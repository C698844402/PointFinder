<?php
/*
*
* Visual Composer PointFinder Contact Map Shortcode
*
*/

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

class PointFinderContactMapShortcode extends WPBakeryShortCode {
	use PointFinderOptionFunctions;

    function __construct() {
        add_action( 'vc_after_init', array( $this, 'pointfinder_single_pf_contact_map_module_mapping' ) );
        add_shortcode( 'pf_contact_map', array( $this, 'pointfinder_single_pf_contact_map_module_html' ) );
    }

    

    public function pointfinder_single_pf_contact_map_module_mapping() {

		if ( !defined( 'WPB_VC_VERSION' ) ) {
		  return;
		}

		/**
		*Start : Contact Map ----------------------------------------------------------------------------------------------------
		**/
			vc_map( array(
			"name" => esc_html__("PF Contact Map", 'pointfindercoreelements'),
			"base" => "pf_contact_map",
			"icon" => "pf_contact_map",
			"category" => esc_html__("Point Finder", "pointfindercoreelements"),
			"description" => esc_html__('Contact Map', 'pointfindercoreelements'),
			"params" => array(

					array(
						"type" => "textfield",
						"heading" => esc_html__("Map Center Latitude", "pointfindercoreelements"),
						"param_name" => "setup5_mapsettings_lat",
						"description" => sprintf(esc_html__('This coordinate for auto center on that point. %s Please click here for finding your coordinates %s', 'pointfindercoreelements'),'<a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">','</a>'),
					  	"edit_field_class" => 'vc_col-sm-6 vc_column-with-padding'
					  ),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Map Center Longitude", "pointfindercoreelements"),
						"param_name" => "setup5_mapsettings_lng",
						"description" => sprintf(esc_html__('This coordinate for auto center on that point. %s Please click here for finding your coordinates %s', 'pointfindercoreelements'),'<a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">','</a>'),
						"edit_field_class" => 'vc_col-sm-6 vc_column'
					  ),
					array(
						  "type" => "pf_info_line_field",
						  "param_name" => "pf_info_field122",
					  ),
					array(
						  "type" => "pf_custom_pointsx",
						  "param_name" => "pfcustompoint",
					  ),
					array(
						  "type" => "pf_info_line_field",
						  "param_name" => "pf_info_field130",
					  ),
					array(
						"type" => "colorpicker",
						"heading" => esc_html__('Pointer Color', 'pointfindercoreelements'),
						"param_name" => "colorp",
					  ),
					array(
						  "type" => "pf_info_line_field",
						  "param_name" => "pf_info_field37",
					  ),
					array(
						"type" => "pfa_numeric",
						"heading" => esc_html__("Map Height", "pointfindercoreelements"),
						"param_name" => "setup5_mapsettings_height",
						"value"	=> '350',
						"edit_field_class" => 'vc_col-sm-4 vc_column',
						"description"	=> esc_html__("Min. 350px", "pointfindercoreelements"),
					  ),
					array(
						"type" => "pfa_numeric",
						"heading" => esc_html__("Zoom", "pointfindercoreelements"),
						"param_name" => "setup5_mapsettings_zoom",
						"value"	=> '12',
						"edit_field_class" => 'vc_col-sm-4 vc_column'
					  )
				)
			) );
		/**
		*End : Contact Map ----------------------------------------------------------------------------------------------------
		**/

    }


    public function pointfinder_single_pf_contact_map_module_html( $atts ) {

    	$we_special_key = $wemap_here_appid = $wemap_here_appcode = '';

		extract( shortcode_atts( array(
			'setup5_mapsettings_height' => 350,
			'setup5_mapsettings_lat' => '37.77493',
			'setup5_mapsettings_lng' => '-122.41942',
			'setup5_mapsettings_zoom' => 12,
			'contact_title' => '',
			'contact_desc' => '',
			'pfcustompoint' => '',
			'colorp' => '',
		), $atts ) );

	  	

	  	parse_str(html_entity_decode($pfcustompoint),$pfcustompoints);

	  	$wpf_rndnum = rand(10,1000);

	    

	  	$data = '[';

	    for ($i=0; $i < intval($pfcustompoints['rownum']); $i++) {
	 		$data .= '{"lat": '.$pfcustompoints['cmap_lat'][$i].',"lng": '.$pfcustompoints['cmap_lng'][$i].',"data":{"title":"'.trim(urlencode($pfcustompoints['cmap_title'][$i])).'","desc":"'.trim(urlencode($pfcustompoints['cmap_desc'][$i])).'"},"options":{"content":"pfmdefault"}}';
		
		 	if (intval($pfcustompoints['rownum']) > 1 && intval($pfcustompoints['rownum']) > $i && intval($pfcustompoints['rownum']) != ($i + 1)) {$data .= ',';}

	 	}

	 	$data .= ']';
	 	
	 	

	 	$stp5_mapty = $this->PFSAIssetControl('stp5_mapty','',1);

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
				break;

			case 6:
				$we_special_key = $this->PFSAIssetControl('wemap_bingmap_api_key','','');
				break;

			case 4:
				$we_special_key = $this->PFSAIssetControl('wemap_yandexmap_api_key','','');
				break;
		}


		ob_start();
	  	?>

	   
    	<div id="pointfindercontactmap" class="wpf-map" style="height:<?php echo $setup5_mapsettings_height;?>px" 
    		data-mapid="<?php echo $wpf_rndnum;?>" 
    		data-lat="<?php echo $setup5_mapsettings_lat;?>" 
    		data-lng="<?php echo $setup5_mapsettings_lng;?>" 
    		data-zoom="<?php echo $setup5_mapsettings_zoom; ?>" 
    		data-zoommx="18" 
    		data-mtype="<?php echo $stp5_mapty;?>" 
    		data-key="<?php echo $we_special_key;?>" 
    		data-hereappid="<?php echo $wemap_here_appid;?>" 
			data-hereappcode="<?php echo $wemap_here_appcode;?>" 
			data-points='<?php echo $data;?>' 
			data-iconbg="<?php echo $colorp;?>"
    		></div>
	  
		    
			
		<?php
		$output_value = ob_get_contents();
		ob_end_clean();
		return $output_value;

    }

}
new PointFinderContactMapShortcode();

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_pointfinder_single_pf_contact_map extends WPBakeryShortCode {
    }
}