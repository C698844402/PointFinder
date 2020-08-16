<?php 
if (!trait_exists('PointFinderAuthorPGFunctions')) {

	trait PointFinderAuthorPGFunctions
	{
		public function PFGetAuthorPageCol1($author_id){
			$setup42_itempagedetails_sidebarpos_auth = $this->PFSAIssetControl('setup42_itempagedetails_sidebarpos_auth','','2');
			if ($setup42_itempagedetails_sidebarpos_auth == 3) {
				echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="pf-itemdetail-inner pfauthordetail">';
			}else{
				echo '<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12"><div class="pf-itemdetail-inner pfauthordetail">';
			}
		   
			global $wpdb;

			$user = get_user_by('id', $author_id);
			echo '<div class="pftrwcontainer hidden-print pf-itempagedetail-element pf-itempage-contactinfo pfnewbglppage">';
			echo '<section role="itempagesidebarinfo" class="pf-itempage-sidebarinfo pfpos2 pf-itempage-elements">';
			echo $this->contactleftsidebar(array('formmode' => 'user','i'=>2,'the_post_id' => '','the_author_id' => $author_id,'sb_contact'=> 'not','locationofplace'=>'authordetailpage'));
			echo '</section>';
			echo '</div>';

			$setup42_authorpagedetails_background2 = $this->PFSAIssetControl('setup22_searchresults_background2','','#f7f7f7');
			$setup42_authorpagedetails_grid_layout_mode = $this->PFSAIssetControl('setup22_searchresults_grid_layout_mode','','1');
			$setup42_authorpagedetails_defaultppptype = $this->PFSAIssetControl('setup22_searchresults_defaultppptype','','10');

			$user_posts = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM $wpdb->posts where post_type = %s and post_author = %d and post_status = 'publish'",$this->post_type_name,$author_id) );

			/*$user_post_posts = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM $wpdb->posts where post_type = %s and post_author = %d and post_status = 'publish'",'post',$author_id) );*/

			$setup42_authorpagedetails_grid_layout_mode = ($setup42_authorpagedetails_grid_layout_mode == 1) ? 'fitRows' : 'masonry' ;

			if(intval($user_posts) > 0){
				echo '<div class="pf-itemdetail-inner pfauthoritems"></div>';
				echo '<div id="pfauthor-items">';
				echo do_shortcode('[pf_itemgrid orderby="title" sortby="ASC" items="'.$setup42_authorpagedetails_defaultppptype.'" cols="3" grid_layout_mode="'.$setup42_authorpagedetails_grid_layout_mode.'" filters="true" itemboxbg="'.$setup42_authorpagedetails_background2.'" authormode="1" author="'.$author_id.'"]' );
				echo '</div>';
			}

			echo '</div>';
			echo '</div>';
		}

		public function PFGetAuthorPageCol2(){
			echo '<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 hidden-print">';
			echo '<section role="itempagesidebar" class="pf-itempage-sidebar">';
				echo '<div id="pf-itempage-sidebar">';
					echo '<div class="sidebar-widget">';
						if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('pointfinder-authorpage-area'))
					echo '</div>';
				echo '</div>';
			echo '</section>';
			echo '</div>';
		}
	}
}