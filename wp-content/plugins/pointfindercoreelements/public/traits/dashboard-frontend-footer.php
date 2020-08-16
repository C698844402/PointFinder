<?php 

if (trait_exists('PointFinderDashboardFrontendFooter')) {
	return;
}

/**
 * Dashboard Frontend Page Header
 */
trait PointFinderDashboardFrontendFooter
{
    public function pointfinder_dashboard_frontend_footer($defaults = array()){


    	$footer_defaults = array(
	        'formaction' => '',
	        'noncefield' => '',
	        'buttontext' => '',
			'buttonid' => '',
			'hide_button' => '',
			'main_package_purchase_permission' => '',
			'main_package_upgrade_permission' => '',
			'free_membership' => '',
			'membership_user_package_id' => '',
			'main_package_renew_permission' => ''
	    );

	    $footer_defaults = array_merge($footer_defaults, $defaults);
	    extract($footer_defaults);

    	/**
		*Start: Page Footer Actions / Divs / Etc...
		**/
			$this->FieldOutput .= '</div>';/*row*/
			$this->FieldOutput .= '</div>';/*form-section*/
			$this->FieldOutput .= '</div>';/*form-enclose*/


			if($this->params['formtype'] != 'myitems' && $this->params['formtype'] != 'favorites' && $this->params['formtype'] != 'reviews'){$xtext = '';}else{$xtext = 'style="background:transparent;background-color:transparent;display:none!important"';}



			$this->FieldOutput .= '
			<div class="pfalign-right" '.$xtext.'>';
			if($this->params['formtype'] != 'errorview' && $this->params['formtype'] != 'banktransfer'){
				if($this->params['formtype'] != 'myitems' && $this->params['formtype'] != 'favorites' && $this->params['formtype'] != 'reviews' && $this->params['formtype'] != 'invoices' && $this->params['dontshowpage'] != 1 && $main_package_expire_problem != true){
		            $this->FieldOutput .='
		                <section '.$xtext.'> ';
		                if($this->params['formtype'] == 'upload'){
			                $setup31_userpayments_recurringoption = $this->PFSAIssetControl('setup31_userpayments_recurringoption','','1');

		                }elseif ($this->params['formtype'] == 'edititem') {

		                	$this->FieldOutput .='
			                   <input type="hidden" name="edit_pid" value="'.$this->params['post_id'].'">';
		                }
		                if ($main_package_purchase_permission == true || $main_package_upgrade_permission == true) {
		                	$this->FieldOutput .='<input type="hidden" name="selectedpackageid" value="">';
		                }elseif ($main_package_renew_permission == true && !empty($membership_user_package_id)) {
		                	if ($free_membership == false) {
		                		$this->FieldOutput .='<input type="hidden" name="selectedpackageid" value="'.$membership_user_package_id.'">';
		                	}else{
		                		$this->FieldOutput .='<input type="hidden" name="selectedpackageid">';
		                	}
		                }
		                if ($main_package_renew_permission == true) {
		                	$this->FieldOutput .='<input type="hidden" name="subaction" value="r">';
		                }elseif ($main_package_purchase_permission == true) {
		                	$this->FieldOutput .='<input type="hidden" name="subaction" value="n">';
		                }elseif ($main_package_upgrade_permission == true) {
		                	$this->FieldOutput .='<input type="hidden" name="subaction" value="u">';
		                }
		                $this->FieldOutput .= '
		                   <input type="hidden" value="'.$formaction.'" name="action" />
		                   <input type="hidden" value="'.$noncefield.'" name="security" />
		                   ';
		                if (!$hide_button) {
		                	$this->FieldOutput .= '
			                   <input type="submit" value="'.$buttontext.'" id="'.$buttonid.'" class="button blue pfmyitempagebuttonsex" data-edit="'.$this->params['post_id'].'"  />
		                   ';
		                }

		                $this->FieldOutput .= '
		                </section>
		            ';
	         	}else{
	       			$this->FieldOutput .='
		                <section  '.$xtext.'>
		                   <input type="hidden" value="'.$formaction.'" name="action" />
		                   <input type="hidden" value="'.$noncefield.'" name="security" />
		                </section>
		            ';
	       		}
	       	}

            $this->FieldOutput.='
            </div>
			';

			$this->FieldOutput .= '</form>';
			$this->FieldOutput .= '</div>';/*golden-forms*/
		/**
		*End: Page Footer Actions / Divs / Etc...
		**/
    }
}