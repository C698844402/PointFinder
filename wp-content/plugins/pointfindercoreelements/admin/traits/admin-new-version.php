<?php 


if (trait_exists('PointFinderNewVersionNotice')) {
	return;
}

/**
 * pF New version Notice
 */
trait PointFinderNewVersionNotice
{
    /**
     * summary
     */
    public function pointfinder_new_version_notice() {
		if (current_user_can('activate_plugins')) {

			$pointfinder_new_version_warning = get_user_meta( get_current_user_id(), 'pointfinder_new_version_warningx1', true);
			if (empty($pointfinder_new_version_warning)) {
				$class = 'notice notice-warning is-dismissible';
				$message = '<strong>'.esc_html__( 'IMPORTANT (PointFinder Theme)', 'pointfindercoreelements' ).'</strong>';
				$message .= '<br/>'. esc_html__( 'WordPress 5.0 (a.k.a. the Gutenberg version) is live now. We highly recommend postponing this update in your production sites until we release an official announcement for compatibility.', 'pointfindercoreelements');
				$message .= '<button type="button" class="notice-dismiss">
					<span class="screen-reader-text">'.esc_html__( 'Dismiss this notice.', 'pointfindercoreelements' ).'</span>
				</button>';
				printf( '<div class="%1$s" id="pointfindernndismiss"><p>%2$s</p></div>', esc_attr( $class ), $message);

				$scriptoutput = "
				jQuery(function(){
				jQuery('#pointfindernndismiss button').on('click',function(){
						var ntype = 'install';
						var nstatus = 0;
						jQuery.ajax({
								beforeSend:function(){},
								url: '".admin_url( 'admin-ajax.php' )."',
								type: 'POST',
								dataType: 'json',
								data: {
										action: 'pfget_nagsystem',
										ntype: ntype,
										nstatus: nstatus,
										security: '".wp_create_nonce('pfget_nagsystem')."'
								},
						});
				});

				});
				";
				wp_add_inline_script('pointfinder-customjs',$scriptoutput);
			}

		}
	}
}