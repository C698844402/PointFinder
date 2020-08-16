<?php 
if (!class_exists('PointFinderAccSYS')) {
	class PointFinderAccSYS extends Pointfindercoreelements_AJAX
	{
	    public function __construct(){}

	    public function pf_ajax_accountremoval(){
	  
			check_ajax_referer( 'pfget_accountremoval', 'security');
		  
			header('Content-Type: application/json; charset=UTF-8;');

			$fav_item = $fav_active = '';
			$results = array();
			if(isset($_POST['item']) && $_POST['item']!=''){
				$fav_item = esc_attr($_POST['item']);
			}
			if(isset($_POST['active']) && $_POST['active']!=''){
				$fav_active = esc_attr($_POST['active']);
			}

			$results['active'] = $fav_active; // Status of fav link.
			$results['item'] = $fav_item; // Item id number


			if (is_user_logged_in()) {
				
				$current_user = wp_get_current_user();
				$user_email = $current_user->user_email;

				$request_id = wp_create_user_request( $user_email, 'remove_personal_data' );

				if ( is_wp_error( $request_id ) ) {
					wp_send_json_error( $request_id->get_error_message() );
				} elseif ( ! $request_id ) {
					wp_send_json_error( esc_html__( 'Unable to initiate confirmation request. Please contact the administrator.', 'pointfindercoreelements' ) );
				} else {
					$send_request = wp_send_user_request( $request_id );
					if (!is_wp_error( $send_request )) {
						wp_send_json_success( esc_html__( 'Your request received successfully. Please approve the account data removal email to continue the process.', 'pointfindercoreelements' ) );
					}else{
						wp_send_json_error( $send_request->get_error_message() );
					}
					
				}

			}else{
				wp_send_json_error( esc_html__( 'You must be logged in to continue this process.', 'pointfindercoreelements' ) );
			}


		die();
		} 
	  
	}
}