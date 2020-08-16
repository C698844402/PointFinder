<?php 
if (!class_exists('PointFinderGeocodingX')) {
	class PointFinderGeocodingX extends Pointfindercoreelements_AJAX
	{
	    public function __construct(){}

	    public function pf_ajax_geocodingx(){
	  	    check_ajax_referer( 'pfget_geocoding', 'security');
			header('Content-Type: application/json;');

			if(isset($_GET['place_id']) && $_GET['place_id']!=''){
				$place_id = sanitize_text_field($_GET['place_id']);
			}

			if(isset($_GET['sessiontoken']) && $_GET['sessiontoken']!=''){
				$sessiontoken = sanitize_text_field($_GET['sessiontoken']);
			}


			$setup5_map_key2 = $this->PFSAIssetControl('setup5_map_key2','','');

			$url = "https://maps.googleapis.com/maps/api/place/details/json";
			$full_url = add_query_arg( array(
			    'sessiontoken' => $sessiontoken,
			    'key' => $setup5_map_key2,
			    'fields' => 'geometry',
			    'place_id' => $place_id
			), $url );
			

			if (empty($full_url)) {
			 	$response_array = esc_html__('Wrong URL', 'pointfindercoreelements');
				echo json_encode($response_array);
				die();
			} 
		
			$curl = curl_init();
			curl_setopt_array($curl, array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_URL => $full_url,
			    CURLOPT_USERAGENT => 'PointFinder cURL Request',
			    CURLOPT_CONNECTTIMEOUT => 10,
			    CURLOPT_TIMEOUT => 10
			));
			$resp = curl_exec($curl);
			curl_close($curl);
			
			echo $resp;
			
			die();
		}
	}
}