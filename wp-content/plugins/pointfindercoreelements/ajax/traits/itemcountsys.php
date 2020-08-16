<?php 
if (!class_exists('PointFinderItemCountSYS')) {

	class PointFinderItemCountSYS extends Pointfindercoreelements_AJAX
	{

		public function __construct(){}

	    public function pf_ajax_itemcount(){

			check_ajax_referer( 'pfget_itemcount', 'security');
		  
			header('Content-Type: application/json; charset=UTF-8;');

			$listing = '';

			if(isset($_POST['l']) && $_POST['l']!=''){
				$listing = esc_attr($_POST['l']);
			}

			if (!empty($listing)) {

				$item_old_count = get_post_meta( $listing, 'webbupointfinder_page_itemvisitcount', true );
	    
			    if (empty($item_old_count)) {
			      $item_old_count = 1;
			    }

				$item_new_count = absint( $item_old_count ) + 1;
	    		update_post_meta( $listing, 'webbupointfinder_page_itemvisitcount', $item_new_count);
				echo json_encode('success');
				die();
			}else{
				echo json_encode('failed');
				die();
			}
			echo json_encode('failed');
			die();
		} 
	  
	}
}