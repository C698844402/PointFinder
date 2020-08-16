<?php 
if (!trait_exists('PointFinderAGNTFunctions')) {

/**
 * Agent Functions
 */
trait PointFinderAGNTFunctions
{
	public function pointfinder_pfstring2AdvArray($results,$keyname, $kv = ',',$uearr_count) {
		$user_ids = '';
		if (!empty($results) && is_array($results)) {
			$uek = 1;
			foreach ($results as $result) {
				if (isset($result[$keyname])) {
					$user_ids .= $result[$keyname];
					if ($uek != $uearr_count) {$user_ids .= ',';}
				}
			$uek++;
			}
		}
		return $user_ids;
	}

	public function pointfinder_agentitemcount_calc($agent_id, $setup3_pointposttype_pt1,$request_type){

		global $wpdb;


		/* Find; Post ID's which defines as agent */
			$adpi_agentresults = $wpdb->get_results(
			$wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key like %s AND meta_value = %d",'webbupointfinder_item_agents',$agent_id),'ARRAY_A');

			$adpi_agentcount = count($adpi_agentresults);
			if ($adpi_agentcount > 0) {
				$adpi_agentresults = $this->pointfinder_pfstring2AdvArray($adpi_agentresults,'post_id',',',$adpi_agentcount);
			}


		/* Find; User IDS which linked with agent. */
			$adpi_userresults = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta where meta_key like %s and meta_value = %d",'user_agent_link',$agent_id),'ARRAY_A');
			$adpi_usercount = count($adpi_userresults);

			if ($adpi_usercount > 0) {
				$adpi_userresults = $this->pointfinder_pfstring2AdvArray($adpi_userresults,'user_id',',',$adpi_usercount);
				$adpi_userresults = pfstring2BasicArray($adpi_userresults);
				if (is_array($adpi_userresults)) {
					$adpi_userresults = implode( ', ', $adpi_userresults );
				}
			}

		/* Find; posts which belongs to this agent */


			if ($adpi_agentcount > 0) {
				$adpi_totalfromposts = $wpdb->get_results(
					$wpdb->prepare("SELECT $wpdb->posts.ID FROM

						$wpdb->posts

						WHERE $wpdb->posts.post_type = %s AND $wpdb->posts.post_status = %s AND $wpdb->posts.post_author IN ({$adpi_agentresults}) AND $wpdb->posts.ID NOT IN({$adpi_agentresults})

						group by $wpdb->posts.ID",

						$setup3_pointposttype_pt1,
						'publish'
					),'ARRAY_A'
				);
			}else{
				if ($adpi_usercount > 0) {
				$adpi_totalfromposts = $wpdb->get_results(
					$wpdb->prepare("SELECT $wpdb->posts.ID FROM

						$wpdb->posts

						WHERE $wpdb->posts.post_type = %s AND $wpdb->posts.post_status = %s AND $wpdb->posts.post_author IN ({$adpi_userresults})

						group by $wpdb->posts.ID",

						$setup3_pointposttype_pt1,
						'publish'
					),'ARRAY_A'
				);
               }else{
                   $adpi_totalfromposts = array();
               }
			}

			$adpi_totalfrompostscount = count($adpi_totalfromposts);
			$return_ids = '';

			if ($request_type == 'count') {
				$return_array = array(
					'count'=> $adpi_totalfrompostscount + $adpi_agentcount,
					'ids' => $return_ids
				);

			}else{
				if ($adpi_totalfrompostscount > 0) {
					$return_ids = $this->pointfinder_pfstring2AdvArray($adpi_totalfromposts,'ID',',',$adpi_totalfrompostscount);;
				}

				if ($adpi_agentcount > 0) {
					if ($adpi_totalfrompostscount > 0) {
						$return_ids .= ','.$adpi_agentresults;
					}else{
						$return_ids = $adpi_agentresults;
					}
				}

				$return_array = array(
					'count'=> $adpi_totalfrompostscount + $adpi_agentcount,
					'ids' => $return_ids
					);
			}


		return $return_array;
	}
}
}