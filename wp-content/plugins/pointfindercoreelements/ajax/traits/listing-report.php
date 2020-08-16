<?php 
if (class_exists('PointFinderListingReport')) {
	return;
}


class PointFinderListingReport extends Pointfindercoreelements_AJAX
{
    public function __construct(){}

    public function pf_ajax_reportitem(){
	  	check_ajax_referer( 'pfget_reportitem', 'security');
		header('Content-Type: application/json; charset=UTF-8;');
		$reported_item = '';
		$results = array();
		if(isset($_POST['item']) && $_POST['item']!=''){$reported_item = esc_attr($_POST['item']);}
		$results['item'] = $reported_item;
		$setup42_itempagedetails_report_regstatus = $this->PFSAIssetControl('setup42_itempagedetails_report_regstatus','','1');
		$results['rs'] = $setup42_itempagedetails_report_regstatus;
		if (is_user_logged_in()) {$cur_user = get_current_user_id();$results['user'] = $cur_user;}else{$results['user'] = 0;}
		echo json_encode($results);
		die();
	}
  
}