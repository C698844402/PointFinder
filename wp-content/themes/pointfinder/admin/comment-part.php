<?php 
/**********************************************************************************************************************************
*
* Single Blog Page - Comments Content
* 
* Author: Webbu
***********************************************************************************************************************************/

echo '<div class="pfitempagecontainerheader hidden-print" id="comments">';
	if ( comments_open() ){
	   comments_popup_link( esc_html__('No comments yet','pointfinder'), esc_html__('1 comment','pointfinder'), esc_html__('% comments','pointfinder'), 'comments-link', esc_html__('Comments are off for this post','pointfinder'));
	}else{
		esc_html_e('Comments','pointfinder');
	};
echo '</div>';
echo '<div class="pftcmcontainer golden-forms hidden-print">';
	comments_template();
echo '</div>';