<?php 

if (trait_exists('PointFinderDashboardFrontendFunctions')) {
	return;
}

/**
 * Dashboard Frontend Page Functions
 */
trait PointFinderDashboardFrontendFunctions
{
    /**
	*Start: Class Functions
	**/
		public function PFGetList($params = array())
		{
		    $defaults = array(
		        'listname' => '',
		        'listtype' => '',
		        'listtitle' => '',
		        'listsubtype' => '',
		        'listdefault' => '',
		        'listmultiple' => 0,
		        'parentonly' => 0
		    );

		    $params = array_merge($defaults, $params);

		    	$output_options = '';
		    	if($params['listmultiple'] == 1){ $multiplevar = ' multiple';$multipletag = '[]';}else{$multiplevar = '';$multipletag = '';};

		    	if ($params['parentonly'] == 1) {
		    		$fieldvalues = get_terms($params['listsubtype'],array('hide_empty'=>false,'parent'=>0));
		    	}else{
		    		$fieldvalues = get_terms($params['listsubtype'],array('hide_empty'=>false));
		    	}

				foreach( $fieldvalues as $parentfieldvalue){
					if($parentfieldvalue->parent == 0){

						$fieldParenttaxSelectedValuex = 0;

						if(is_array($params['listdefault'])){
							if(in_array($parentfieldvalue->term_id, $params['listdefault'])){ $fieldParenttaxSelectedValuex = 1;}
						}else{
							if(strcmp($params['listdefault'],$parentfieldvalue->term_id) == 0){ $fieldParenttaxSelectedValuex = 1;}
						}

						if($fieldParenttaxSelectedValuex == 1){
							$output_options .= '<option class="pointfinder-parent-field" value="'.$parentfieldvalue->term_id.'" selected>'.$parentfieldvalue->name.'</option>';
						}else{
							$output_options .= '<option class="pointfinder-parent-field" value="'.$parentfieldvalue->term_id.'">'.$parentfieldvalue->name.'</option>';
						}

						foreach( $fieldvalues as $fieldvalue){
							if($fieldvalue->parent == $parentfieldvalue->term_id){
								$fieldtaxSelectedValue = 0;

								if($params['listdefault'] != ''){
									if(is_array($params['listdefault'])){
										if(in_array($fieldvalue->term_id, $params['listdefault'])){ $fieldtaxSelectedValue = 1;}
									}else{
										if(strcmp($params['listdefault'],$fieldvalue->term_id) == 0){ $fieldtaxSelectedValue = 1;}
									}
								}

								if($fieldtaxSelectedValue == 1){
									$output_options .= '<option value="'.$fieldvalue->term_id.'" selected>&nbsp;&nbsp;&nbsp;&nbsp;'.$fieldvalue->name.'</option>';
								}else{
									$output_options .= '<option value="'.$fieldvalue->term_id.'">&nbsp;&nbsp;&nbsp;&nbsp;'.$fieldvalue->name.'</option>';
								}
							}
						}
					}
				}



		    	$output = '';
				$output .= '<div class="pf_fr_inner" data-pf-parent="">';


   				if (!empty($params['listtitle'])) {
	   				$output .= '<label for="'.$params['listname'].'" class="lbl-text">'.$params['listtitle'].':</label>';
   				}

   				$as_mobile_dropdowns = $this->PFSAIssetControl('as_mobile_dropdowns','','0');

				if ($as_mobile_dropdowns == 1) {
					$as_mobile_dropdowns_text = 'class="pf-special-selectbox"';
				} else {
					$as_mobile_dropdowns_text = '';
				}

   				$output .= '
                <label class="lbl-ui select">
                <select'.$multiplevar.' name="'.$params['listname'].$multipletag.'" id="'.$params['listname'].'" '.$as_mobile_dropdowns_text.'>';
                $output .= '<option></option>';
                $output .= $output_options.'
                </select>
                </label>';


		   		$output .= '</div>';

            return $output;
		}

		public function PFValidationCheckWrite($field_validation_check,$field_validation_text,$itemid){

			$itemname = (string)trim($itemid);
			$itemname = (strpos($itemname, '[]') == false) ? $itemname : "'".$itemname."'" ;

			if($field_validation_check == 1){
				if($this->VSOMessages != ''){
					$this->VSOMessages .= ','.$itemname.':"'.$field_validation_text.'"';
				}else{
					$this->VSOMessages = $itemname.':"'.$field_validation_text.'"';
				}

				if($this->VSORules != ''){
					$this->VSORules .= ','.$itemname.':"required"';
				}else{
					$this->VSORules = $itemname.':"required"';
				}
			}
		}

		public function PF_UserLimit_Check($action,$post_status){

			switch ($post_status) {
				case 'publish':
						switch ($action) {
							case 'edit':
								$output = ($this->PFSAIssetControl('setup31_userlimits_useredit','','1') == 1) ? 1 : 0 ;
								break;

							case 'delete':
								$output = ($this->PFSAIssetControl('setup31_userlimits_userdelete','','1') == 1) ? 1 : 0 ;
								break;
						}

					break;

				case 'pendingpayment':
						switch ($action) {
							case 'edit':
								$output = ($this->PFSAIssetControl('setup31_userlimits_useredit_pendingpayment','','1') == 1) ? 1 : 0 ;
								break;

							case 'delete':
								$output = ($this->PFSAIssetControl('setup31_userlimits_userdelete_pendingpayment','','1') == 1) ? 1 : 0 ;
								break;
						}

					break;

				case 'rejected':
						switch ($action) {
							case 'edit':
								$output = ($this->PFSAIssetControl('setup31_userlimits_useredit_rejected','','1') == 1) ? 1 : 0 ;
								break;

							case 'delete':
								$output = ($this->PFSAIssetControl('setup31_userlimits_userdelete_rejected','','1') == 1) ? 1 : 0 ;
								break;
						}

					break;

				case 'pendingapproval':
						switch ($action) {
							case 'edit':
								$output = 0 ;
								break;

							case 'delete':
								$output = ($this->PFSAIssetControl('setup31_userlimits_userdelete_pendingapproval','','1') == 1) ? 1 : 0 ;
								break;
						}

					break;

				case 'pfonoff':
						switch ($action) {
							case 'edit':
								$output = ($this->PFSAIssetControl('setup31_userlimits_useredit','','1') == 1) ? 1 : 0 ;
								break;

							case 'delete':
								$output = ($this->PFSAIssetControl('setup31_userlimits_userdelete','','1') == 1) ? 1 : 0 ;
								break;
						}

					break;
			}

			return $output;
		}
    /**
	*End: Class Functions
	**/
}