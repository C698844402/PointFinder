<?php 
if (class_exists('PointFinderReviewFlag')) {
	return;
}


class PointFinderReviewFlag extends Pointfindercoreelements_AJAX
{
    public function __construct(){}

    public function pf_ajax_flagreview(){

		check_ajax_referer( 'pfget_flagreview', 'security');
	  
		header('Content-Type: application/json; charset=UTF-8;');

		$reported_item = '';

		$results = array();

		if(isset($_POST['item']) && $_POST['item']!=''){
			$reported_item = esc_attr($_POST['item']);
		}
		
		$results['item'] = $reported_item;

		if (is_user_logged_in()) {
			$cur_user = get_current_user_id();
			$results['user'] = $cur_user;
			
		}else{
			$results['user'] = 0;
		}


		echo json_encode($results);
	die();
	} 
  
}