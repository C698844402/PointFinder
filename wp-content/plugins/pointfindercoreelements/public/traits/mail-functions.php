<?php 
if (trait_exists('PointFinderMailFunctions')) {
  return;
}

/**
 * Mail Functions
 */
trait PointFinderMailFunctions
{
	public function pointfinder_mail_wp_mail_from_name($name){
		$setup33_emailsettings_fromname = esc_attr($this->PFMSIssetControl('setup33_emailsettings_fromname','',''));
		if($setup33_emailsettings_fromname != ''){
			return $setup33_emailsettings_fromname;
		}else{
			$blog_name = get_option("blogname");
			return $blog_name;
		}
	}


	public function pointfinder_mail_wp_mail_from($email){
		$setup33_emailsettings_fromemail = esc_attr($this->PFMSIssetControl('setup33_emailsettings_fromemail','',''));
		if($setup33_emailsettings_fromemail != ''){
			return $setup33_emailsettings_fromemail;
		}else{
			$admin_email = get_option("admin_email");
			return $admin_email;
		}
	}

	public function pointfinder_mail_content_type( $content_type ) {
		$setup33_emailsettings_mailtype = esc_attr($this->PFMSIssetControl('setup33_emailsettings_mailtype','','1'));
	    if( $setup33_emailsettings_mailtype == 1 ) {
	        return 'text/HTML';
	    } else {
	        return 'text/plain';
	    }
	}

}