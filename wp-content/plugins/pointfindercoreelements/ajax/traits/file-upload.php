<?php 
if (class_exists('PointFinderAJAXFileUpload')) {
	return;
}


class PointFinderAJAXFileUpload extends Pointfindercoreelements_AJAX
{
    public function __construct(){}


    public function pf_ajax_fileupload(){
  
		check_ajax_referer( 'pfget_fileupload', 'security');
	  	
	  	header('Content-Type: text/html;charset=UTF-8;');
		
		
		$iid = $newupload = $id = '';
		$output = array();

		if(isset($_POST['iid']) && $_POST['iid']!=''){
			$iid = esc_attr($_POST['iid']);
		}

		if(isset($_POST['id']) && $_POST['id']!=''){
			$id = esc_attr($_POST['id']);
		}

		if(isset($_POST['exid']) && $_POST['exid']!=''){
			$exid = esc_attr($_POST['exid']);
		}

		if (!empty($exid)) {
			if (strpos($exid, ",")) {
				$exarray = $this->pfstring2BasicArray($exid);
				if (is_array($exarray)) {
					foreach ($exarray as $exarrayval) {
						$result = delete_post_meta( $exarrayval, 'pointfinder_delete_unused', '1', true);
						if ($result) {
							wp_delete_attachment( $exarrayval, true );
							$output['process'] = 'del';
							$output['id'] = $exarrayval;
						}
					}
				}
			}else{
				$result = delete_post_meta( $exid, 'pointfinder_delete_unused', '1', true);
				if ($result) {
					wp_delete_attachment( $exid, true );
					$output['process'] = 'del';
					$output['id'] = $exid;
				}
			}
			echo json_encode($output);
			die();
		}


		/*Image Remove Process*/
		if (!empty($iid)) {
			/*Check this image if this user uploaded*/
			$content_post = get_post($iid);
			$post_author = $content_post->post_author;
			
			if (get_current_user_id() == $post_author) {
				if (!empty($id)) {
					delete_post_meta( $id, 'webbupointfinder_item_files', $iid );
				}
				wp_delete_attachment( $iid, true );
				
				$output['process'] = 'del';
				$output['id'] = $iid;
				echo json_encode($output);
				
			}
			die();
		};


		/* Upload Images */	
		$stp4_allowed = $this->PFSAIssetControl("stp4_allowed","",'jpg,jpeg,gif,png,pdf,rtf,csv,zip, x-zip, x-zip-compressed,rar,doc,docx,docm,dotx,dotm,docb,xls,xlt,xlm,xlsx,xlsm,xltx,xltm,ppt,pot,pps,pptx,pptm');
		$allowed_file_types = $this->pfstring2BasicArray($stp4_allowed);
		$stp4_Filesizelimit = $this->PFSAIssetControl("stp4_Filesizelimit","","2");
		foreach ($_FILES as $key => $array) {
			
			if ( isset($_FILES[$key])) { 

				$path_infos = pathinfo($_FILES[$key]['name']); 

				if ( $_FILES[$key]['error'] <= 0) {      
				    if(in_array($path_infos['extension'], $allowed_file_types)) {

				    	if ($_FILES[$key]['size']  <= (1000000*$stp4_Filesizelimit)) {
					    	if (!empty($id)) {
					    		$newupload = $this->pft_insert_attachment($key);
					      		add_post_meta($id, 'webbupointfinder_item_files', $newupload);
					    	}else{
					    		$newupload = $this->pft_insert_attachment($key);
					      		add_post_meta( $newupload, 'pointfinder_delete_unused', '1', true);
					    	}

				      
							$output['process'] = 'up';
							$output['id'] = $newupload;
							echo json_encode($output);
							die();
					    }else{
					    	$output['process'] = 'down';
							echo json_encode($output);
							die();
					    }
					}
				}
			}
			
		}

	} 
  
}