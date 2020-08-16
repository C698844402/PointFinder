<?php 
/**********************************************************************************************************************************
*
* Item Detail Page - Comments Content
* 
* Author: Webbu
***********************************************************************************************************************************/

if (trait_exists('PointFinderListingComments')) {
  return;
}

trait PointFinderListingComments
{
    function pointfinder_listing_comments(){
    	$setup3_modulessetup_allow_comments = $this->PFSAIssetControl('setup3_modulessetup_allow_comments','','0');
		if($setup3_modulessetup_allow_comments == 1){
			
			echo '<div class="pftcmcontainer golden-forms hidden-print pf-itempagedetail-element">';
			echo '<div class="pfitempagecontainerheader" id="comments">';
				if ( comments_open() ){
				   comments_popup_link( esc_html__('No comments yet','pointfindercoreelements'), esc_html__('1 comment','pointfindercoreelements'), esc_html__('% comments','pointfindercoreelements'), 'comments-link', esc_html__('Comments are off for this post','pointfindercoreelements'));
				}else{
					esc_html_e('Comments','pointfindercoreelements');
				};
			echo '</div>';
				comments_template();
			echo '</div>';
		}
    }
}