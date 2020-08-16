<?php 

if (trait_exists('PointFinderDashboardFrontendHeader')) {
	return;
}

/**
 * Dashboard Frontend Page Header
 */
trait PointFinderDashboardFrontendHeader
{
    public function pointfinder_dashboard_frontend_header(){
    	/**
		*Start: Page Header Actions / Divs / Etc...
		**/
			$this->FieldOutput = '<div class="golden-forms">';
			if ($this->params['formtype'] == 'myitems') {
				$this->FieldOutput .= '<form id="pfuaprofileform" enctype="multipart/form-data" name="pfuaprofileform" method="GET" action=""><input type="hidden" value="myitems" name="ua">';
			}else{
				$this->FieldOutput .= '<form id="pfuaprofileform" enctype="multipart/form-data" name="pfuaprofileform" method="POST" action="">';
			}

			$this->FieldOutput .= '<div class="pfsearchformerrors"><ul></ul><a class="button pfsearch-err-button"><i class="fas fa-times"></i> '.esc_html__('CLOSE','pointfindercoreelements').'</a></div>';
			if($this->params['sccval'] != ''){
				$this->FieldOutput .= '<div class="notification success" id="pfuaprofileform-notify"><div class="row"><p>'.$this->params['sccval'].'<br>'.$this->params['sheadermes'].'</p></div></div>';
				$this->ScriptOutput .= '$(function(){$.pfmessagehide();});';
			}
			if($this->params['errorval'] != ''){
				$this->FieldOutput .= '<div class="notification error" id="pfuaprofileform-notify"><p>'.$this->params['errorval'].'</p></div>';
				$this->ScriptOutput .= '$(function(){$.pfmessagehide();});';
			}
			$this->FieldOutput .= '<div class="">';
			$this->FieldOutput .= '<div class="">';
			$this->FieldOutput .= '<div class="row">';

		/**
		*End: Page Header Actions / Divs / Etc...
		**/
    }
}