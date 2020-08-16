<?php 
get_header();
	

	if (class_exists('Pointfindercoreelements')) {
		do_action('pointfinder_category_page_hook');
	}else{
		$setup_item_blogcatpage_sidebarpos = PFASSIssetControl('setup_item_blogcatpage_sidebarpos','','3');
			if(function_exists('PFGetDefaultPageHeader')){PFGetDefaultPageHeader();}
			echo '<div class="pf-blogpage-spacing pfb-top"></div>';
			echo '<section role="main">';
				echo '<div class="pf-container">';
					echo '<div class="pf-row">';
						if ($setup_item_blogcatpage_sidebarpos == 3) {
			        		echo '<div class="col-lg-12">';

								get_template_part('loop');

							echo '</div>';
			        	}else{
			        	
				            if($setup_item_blogcatpage_sidebarpos == 1){
				                echo '<div class="col-lg-3 col-md-4">';
				                    if (is_active_sidebar( 'pointfinder-blogcatpages-area' )) {

				                    	get_sidebar('catblog' );
				                    } else {
				                    	get_sidebar();
				                    }
				                    
				                echo '</div>';
				            }
				              
				            echo '<div class="col-lg-9 col-md-8">'; 
				            
				            get_template_part('loop');

				            echo '</div>';
				            if($setup_item_blogcatpage_sidebarpos == 2){
				                echo '<div class="col-lg-3 col-md-4">';
				                    if (is_active_sidebar( 'pointfinder-blogcatpages-area' )) {
				                    	get_sidebar('catblog' );
				                    } else {
				                    	get_sidebar();
				                    }
				                echo '</div>';
				            }

			            }
					echo '</div>';
				echo '</div>';
			echo '</section>';
			echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';
	}
	

get_footer();
?>