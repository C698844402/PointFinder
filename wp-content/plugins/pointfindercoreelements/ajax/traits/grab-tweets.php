<?php 

if (!class_exists('PointFinderGrabTweets')) {

	class PointFinderGrabTweets extends Pointfindercoreelements_AJAX
	{
		public function __construct(){}

	    public function pf_ajax_grabtweets(){

			check_ajax_referer( 'pfget_grabtweets', 'security');
			header('Content-Type: application/json; charset=UTF-8;');

			$CONSUMER_KEY = $this->PFASSIssetControl('setuptwitterwidget_conkey','','');
			$CONSUMER_SECRET = $this->PFASSIssetControl('setuptwitterwidget_consecret','','');
			$ACCESS_TOKEN = $this->PFASSIssetControl('setuptwitterwidget_acckey','','');
			$ACCESS_TOKEN_SECRET = $this->PFASSIssetControl('setuptwitterwidget_accsecret','','');

			if(!empty($CONSUMER_KEY) && !empty($CONSUMER_SECRET) && !empty($ACCESS_TOKEN) && !empty($ACCESS_TOKEN_SECRET)){
				require_once PFCOREELEMENTSDIR . 'ajax/traits/codebird.php';
				\Codebird\Codebird::setConsumerKey($CONSUMER_KEY, $CONSUMER_SECRET);
				$cb = \Codebird\Codebird::getInstance();
				$cb->setToken($ACCESS_TOKEN, $ACCESS_TOKEN_SECRET);


				$q = sanitize_text_field($_POST['q']);
				$count = absint($_POST['count']);
				$api = 'statuses_userTimeline';


				$params = array(
					'screen_name' => $q,
					'q' => $q,
					'count' => $count
				);
				$data123 = (array) $cb->$api($params);
				echo json_encode($data123);
			}else{
				echo json_encode(array('httpstatus'=>404));
			}

			die();
		}
	  
	}
}