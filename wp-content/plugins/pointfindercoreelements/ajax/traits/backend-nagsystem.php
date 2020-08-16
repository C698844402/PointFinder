<?php 
if (class_exists('PointFinderNagSystem')) {
	return;
}
/**
 * Disable Admin notice
 */
class PointFinderNagSystem extends Pointfindercoreelements_AJAX
{
    public function __construct(){}

    public function pf_ajax_nagsystem(){
		check_ajax_referer( 'pfget_nagsystem', 'security');
		header('Content-Type: application/json; charset=UTF-8;');

		$ntype = $nstatus = $result = '';

		if(isset($_POST['ntype']) && $_POST['ntype']!=''){
			$ntype = esc_attr($_POST['ntype']);
		}

		if(isset($_POST['nstatus']) && $_POST['nstatus']!=''){
			$nstatus = esc_attr($_POST['nstatus']);
		}


		global $current_user;
	    $user_id = $current_user->ID;

	    if (!empty($user_id)) {

		    switch ($ntype) {
		    	case 'install':
		    		if ($nstatus == 0) {
		    			update_user_meta($user_id, 'pointfinder_new_version_warningx1', true);
		    			$result = 1;
		    		}else{
		    			delete_user_meta($user_id, 'pointfinder_new_version_warningx1');
		    			$result = 1;
		    		}
		    }

	    }

		echo json_encode($result);
		die();
	}
  
}