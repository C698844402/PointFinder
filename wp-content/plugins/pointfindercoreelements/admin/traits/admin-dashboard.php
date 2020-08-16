<?php 

if (trait_exists('PointFinderAdminDashboardWidgets')) {
	return;
}

/**
 * Admin Dashboard Widgets
 */
trait PointFinderAdminDashboardWidgets
{

    public function pointfinder_add_dashboard_widgets() {
    	if (current_user_can('activate_plugins')) {
	    	wp_add_dashboard_widget( 'pfstatusofsystemwidget', esc_html__( 'POINT FINDER SYSTEM STATUS', 'pointfindercoreelements' ), array($this,'pf_status_of_system') );
	    	wp_add_dashboard_widget( 'pfstatusofsystemwidget2', esc_html__( 'POINT FINDER SYSTEM HEALTH', 'pointfindercoreelements' ), array($this,'pf_status_of_system2') );
	    }
	}

	public function pf_status_of_system() {

		global $wpdb;
		$theme = wp_get_theme();

		if (defined("PFCOREPLUGIN_NAME_VERSION")) {
			$pluginversion = 'v'.PFCOREPLUGIN_NAME_VERSION;
		}else{
			$pluginversion = '<small style="font-size:14px">'.esc_html__('Please update core plugin to min. v1.1','pointfindercoreelements').'</small>';
		}

		echo '<div class="pfawidget">';
		echo '<div class="pfawidget-body">';
	 	echo '<div class="pfaflash">'.esc_html__('You are using','pointfindercoreelements').'  <strong>'.esc_html__('Point Finder Theme','pointfindercoreelements').'</div>';

	 	echo '<div class="accordion">';
	 	echo '
				<div class="accordion-body">
					<div class="accordion-mainit">
						<div class="accordion-status-text" style="color: #8EC34B!important;">v'.$theme->version.'</div>
						'.esc_html__('Theme Version','pointfindercoreelements').'
					</div>
					<div class="accordion-mainit">
						<div class="accordion-status-text" style="color: #8EC34B!important;">'.$pluginversion.'</div>
						'.esc_html__('Core Plugin Version','pointfindercoreelements').'
					</div>
				</div>';
		if($this->PFSAIssetControl('setup4_membersettings_loginregister','','1') == 1){

			$setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');
			$pf_published_items = $wpdb->get_var($wpdb->prepare("select count(ID) from $wpdb->posts where post_type='%s' and post_status='%s'",$setup3_pointposttype_pt1,'publish'));

			if($this->PFSAIssetControl('setup4_membersettings_frontend','','1') == 1){

				$pf_pendingapproval_items = $wpdb->get_var($wpdb->prepare("select count(ID) from $wpdb->posts where post_type='%s' and post_status='%s'",$setup3_pointposttype_pt1,'pendingapproval'));
				$pf_pendingpayment_items = $wpdb->get_var($wpdb->prepare("select count(ID) from $wpdb->posts where post_type='%s' and post_status='%s'",$setup3_pointposttype_pt1,'pendingpayment'));

				echo '
				<div class="accordion-header"><h2>'.esc_html__('MAIN SYSTEM STATUS','pointfindercoreelements').'</h2></div>
				<div class="accordion-body">
					<div class="accordion-mainit">
						<div class="accordion-status-text"><a href="'.admin_url("edit.php?post_status=publish&post_type=$setup3_pointposttype_pt1").'">'.$pf_published_items.'</a></div>
						'.esc_html__('Published','pointfindercoreelements').'
					</div>
					<div class="accordion-mainit">
						<div class="accordion-status-text"><a href="'.admin_url("edit.php?post_status=pendingapproval&post_type=$setup3_pointposttype_pt1").'">'.$pf_pendingapproval_items.'</a></div>
						'.esc_html__('Pending Approval','pointfindercoreelements').'
					</div>
					<div class="accordion-mainit">
						<div class="accordion-status-text"><a href="'.admin_url("edit.php?post_status=pendingpayment&post_type=$setup3_pointposttype_pt1").'">'.$pf_pendingpayment_items.'</a></div>
						'.esc_html__('Pending Payment','pointfindercoreelements').'
					</div>
				</div>
				';

			}
		}


		if ($this->PFREVSIssetControl('setup11_reviewsystem_check','','0') == 1) {
			$pf_published_reviews = $wpdb->get_var($wpdb->prepare("select count(ID) from $wpdb->posts where post_type='%s' and post_status='%s'",'pointfinderreviews','publish'));
			$pf_pendingapproval_reviews = $wpdb->get_var($wpdb->prepare("select count(ID) from $wpdb->posts where post_type='%s' and post_status='%s'",'pointfinderreviews','pendingapproval'));
			$pf_pendingpayment_reviews = $wpdb->get_var($wpdb->prepare("select count(ID) from $wpdb->posts where post_type='%s' and post_status='%s'",'pointfinderreviews','pendingpayment'));

			echo '
			<div class="accordion-header">
				<h2>'.esc_html__('REVIEW SYSTEM STATUS','pointfindercoreelements').'</h2>
			</div>
			<div class="accordion-body">
				<div class="accordion-mainit">
					<div class="accordion-status-text">'.$pf_published_reviews.'</div>
					'.esc_html__('Published','pointfindercoreelements').'
				</div>
				<div class="accordion-mainit">
					<div class="accordion-status-text">'.$pf_pendingapproval_reviews.'</div>
					'.esc_html__('Pending Approval','pointfindercoreelements').'
				</div>
				<div class="accordion-mainit">
					<div class="accordion-status-text">'.$pf_pendingpayment_reviews.'</div>
					'.esc_html__('Pending Check','pointfindercoreelements').'
				</div>
			</div>
			';
		}

		echo '</div></div></div>';
	}

	public function pf_status_of_system2() {

		echo '<div class="pfawidget">';
		echo '<div class="pfawidget-body">';

	 	echo '<div class="accordion">';

		$ssl_text = $api_text = $api_text2 = $dash_text = $miv_text = $met_text = $ml_text = $pms_text = $umfs_text = $curl_text = $php_text = $mfu_text = $mit_text = '';

		$miv_css = $met_css = $api_css = $api_css2 = $ssl_css = $dash_css = $ml_css = $pms_css = $umfs_css = $curl_css = $php_css = $mfu_css = $mit_css = ' pf-st-ok';

		$ssl_check = (is_ssl())? '<span class="dashicons dashicons-yes"></span>':'<span class="dashicons dashicons-no-alt"></span>';
		if (!is_ssl()) {
			$ssl_text = '<br/><small>'.wp_sprintf(esc_html__('You are not using ssl and you may have problems on google map. Please read %sthis article%s.','pointfindercoreelements'),'<a href="https://support.wethemes.com/forums/topic/no-https-then-say-goodbye-to-geolocation-in-chrome-50/" target="blank">','</a>').'</small>';
			$ssl_css = '';
		}


		$setup4_membersettings_dashboard = $this->PFSAIssetControl('setup4_membersettings_dashboard','','');
		$dash_check = (!empty($setup4_membersettings_dashboard))? '<span class="dashicons dashicons-yes"></span>':'<span class="dashicons dashicons-no-alt"></span>';
		if (empty($setup4_membersettings_dashboard)) {
			$dash_text = '<br/><small>'.wp_sprintf(esc_html__('Your dashboard page not configured and you may have problems on you site. Please read %sthis article%s.','pointfindercoreelements'),'<a href="https://pointfinderdocs.wethemes.com/knowledgebase/page-not-found-while-submitting-new-item/" target="blank">','</a>').'</small>';
			$dash_css = '';
		}

		echo '
		<div class="accordion-header">
			<h2>'.esc_html__('SYSTEM HEALTH CHECK','pointfindercoreelements').'</h2>
		</div>
		<div class="accordion-body">
			<div class="accordion-mainit">
				<div class="accordion-status-text'.$ssl_css.'">'.$ssl_check.'</div>
				'.esc_html__('SSL Check','pointfindercoreelements').$ssl_text.'
			</div>
			<div class="accordion-mainit">
				<div class="accordion-status-text'.$dash_css.'">'.$dash_check.'</div>
				'.esc_html__('Dashboard Page Check','pointfindercoreelements').$dash_text.'
			</div>
		</div>
		';


		$miv_check = ini_get('max_input_vars');

		if ($miv_check <= 10000) {
			$miv_text = '<br/><small>'.wp_sprintf(esc_html__('You have to increase this value to 10000 otherwise you may have problems while saving admin options. Please read %sthis article%s.','pointfindercoreelements'),'<a href="https://pointfinderdocs.wethemes.com/knowledgebase/what-is-this-message-there-was-a-problem-with-your-action-please/" target="blank">','</a>').'</small>';
			$miv_css = '';
		}

		$ml_check = ini_get('memory_limit');
		if (in_array($ml_check, array('32M','64M','128M','256M'))) {
			$ml_text = '<br/><small>'.wp_sprintf(esc_html__('You have to increase this value otherwise you may have problems. Please read %sthis article%s.','pointfindercoreelements'),'<a href="https://pointfinderdocs.wethemes.com/knowledgebase/increasing-the-wordpress-memory-limit/" target="blank">','</a>').'</small>';
			$ml_css = '';
		}

		$met_check = ini_get('max_execution_time');
		if (($met_check < 600) || $met_check == 0 ) {
			$met_text = '<br/><small>'.esc_html__('You have to increase this value otherwise you may have problems. Recommended value: 400 or more','pointfindercoreelements').'</small>';
			$met_css = '';
		}

		$pms_check = ini_get('post_max_size');
		if (in_array($pms_check, array('2M','4M','8M','16M'))) {
			$pms_text = '<br/><small>'.wp_sprintf(esc_html__('You have to increase this value otherwise you may have problems. Please read %sthis article%s.','pointfindercoreelements'),'<a href="https://pointfinderdocs.wethemes.com/knowledgebase/requirements/" target="blank">','</a>').'</small>';
			$pms_css = '';
		}

		$umfs_check = ini_get('post_max_size');
		if (in_array($umfs_check, array('2M','4M','8M','16M'))) {
			$umfs_text = '<br/><small>'.wp_sprintf(esc_html__('You have to increase this value otherwise you may have problems. Please read %sthis article%s.','pointfindercoreelements'),'<a href="https://pointfinderdocs.wethemes.com/knowledgebase/requirements/" target="blank">','</a>').'</small>';
			$umfs_css = '';
		}

		$php_version_num = (function_exists('phpversion'))?phpversion():'';
		$curl_version_num = (function_exists('curl_version'))?curl_version():'';
		$curl_version_num = (isset($curl_version_num['version']))?$curl_version_num['version']:'<span class="dashicons dashicons-no-alt"></span>';

		$mfu_check = ini_get('max_file_uploads');
		$mit_check = ini_get('max_input_time');

		if(version_compare($curl_version_num, "7.34.0", "<=")){
			$curl_text = '<br/><small>'.wp_sprintf(esc_html__('You have to use v7.34.0 with TLS 1.2 for Paypal Payments otherwise you may have problems. Please read %sthis article%s.','pointfindercoreelements'),'<a href="https://support.wethemes.com/forums/topic/paypal-tls-v1-2-upgrade/" target="blank">','</a>').'</small>';
			$curl_css = '';
		}

		if(version_compare($php_version_num, "5.6.0", "<=")){
			$php_text = '<br/><small>'.esc_html__('You have to use php v5.6.x otherwise you may have problems.','pointfindercoreelements').'</small>';
			$php_css = '';
		}


		if ($mfu_check < 20) {
			$mfu_text = '<br/><small>'.esc_html__('You have to increase this value otherwise you may have problems. Recommended value: 20 or more','pointfindercoreelements').'</small>';
			$mfu_css = '';
		}

		if ($mit_check < 20) {
			$mit_text = '<br/><small>'.esc_html__('You have to increase this value otherwise you may have problems. Recommended value: 20 or more','pointfindercoreelements').'</small>';
			$mit_css = '';
		}

		echo '
		<div class="accordion-header">
			<h2>'.esc_html__('PHP VARIABLES CHECK','pointfindercoreelements').'</h2>
		</div>
		<div class="accordion-body">
			<div class="accordion-mainit">
				<div class="accordion-status-text'.$miv_css.'">'.$miv_check.'</div>
				'.esc_html__('max_input_vars','pointfindercoreelements').$miv_text.'
			</div>
			<div class="accordion-mainit">
				<div class="accordion-status-text'.$ml_css.'">'.$ml_check.'</div>
				'.esc_html__('memory_limit','pointfindercoreelements').$ml_text.'
			</div>
			<div class="accordion-mainit">
				<div class="accordion-status-text'.$met_css.'">'.$met_check.'</div>
				'.esc_html__('max_execution_time','pointfindercoreelements').$met_text.'
			</div>
			<div class="accordion-mainit">
				<div class="accordion-status-text'.$pms_css.'">'.$pms_check.'</div>
				'.esc_html__('post_max_size','pointfindercoreelements').$pms_text.'
			</div>
			<div class="accordion-mainit">
				<div class="accordion-status-text'.$umfs_css.'">'.$umfs_check.'</div>
				'.esc_html__('upload_max_filesize','pointfindercoreelements').$umfs_text.'
			</div>

			<div class="accordion-mainit">
				<div class="accordion-status-text'.$mfu_css.'">'.$mfu_check.'</div>
				'.esc_html__('max_file_uploads','pointfindercoreelements').$mfu_text.'
			</div>

			<div class="accordion-mainit">
				<div class="accordion-status-text'.$mit_css.'">'.$mit_check.'</div>
				'.esc_html__('max_input_time','pointfindercoreelements').$mit_text.'
			</div>

			<div class="accordion-mainit">
				<div class="accordion-status-text'.$curl_css.'">'.$curl_version_num.'</div>
				'.esc_html__('cURL Version Check','pointfindercoreelements').$curl_text.'
			</div>

			<div class="accordion-mainit">
				<div class="accordion-status-text'.$php_css.'">'.$php_version_num.'</div>
				'.esc_html__('Php Version Check','pointfindercoreelements').$php_text.'
			</div>
		</div>
		';

		echo '</div></div></div>';
	}
}