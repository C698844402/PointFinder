<?php 
get_header();
	

	if(isset($_GET['author_name'])){
		$current_author = get_user_by('login',$author_name);
	}else{
		$current_author = get_userdata(intval($author));
	}

	$content = '';

	if (class_exists('Pointfindercoreelements')) {
		echo apply_filters( 'pointfinder_authorpage_filter', $content, $current_author );
	}else{
		if(!empty($current_author)){

			
            if(function_exists('PFGetDefaultPageHeader')){
				PFGetDefaultPageHeader(array('author_id'=>$current_author->ID));
			}
	       
			$setup42_itempagedetails_sidebarpos_auth = PFSAIssetControl('setup42_itempagedetails_sidebarpos_auth','','3');
			echo '<section role="main">';
				echo '<div class="pf-container clearfix">';
				echo '<div class="pf-row clearfix">';
					echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="pf-itemdetail-inner pfauthordetail">';
					echo '<div class="pf-itemdetail-inner pfauthorposts">'.esc_html__("Author's Posts:","pointfinder").'</div>';
					get_template_part('loop');
					echo '</div>';
	    		echo '</div>';
	        	echo '</div>';
	        echo '</section>';
			                
		}else{

			PFPageNotFound(); 

		}
	}


get_footer();