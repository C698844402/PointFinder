<?php
get_header();


	if (isset($_GET['action']) && $_GET['action'] == 'pfs') {

		do_action('pointfinder_search_page_hook');

	}else{
		if(function_exists('PFGetDefaultPageHeader')){PFGetDefaultPageHeader();}
		$setup_item_blogspage_sidebarpos = PFASSIssetControl('setup_item_blogspage_sidebarpos','','2');

		echo '<div class="pf-blogpage-spacing pfb-top"></div>';
		echo '<section role="main">';
			echo '<div class="pf-container">';
				echo '<div class="pf-row">';
					if ($setup_item_blogspage_sidebarpos == 3) {
								echo '<div class="col-lg-12">';

							get_template_part('loop');

						echo '</div>';
					}else{

							if($setup_item_blogspage_sidebarpos == 1){
									echo '<div class="col-lg-3 col-md-4">';
											if (is_active_sidebar( 'pointfinder-blogspages-area' )) {

												get_sidebar('blogspages' );
											} else {
												get_sidebar();
											}

									echo '</div>';
							}

							echo '<div class="col-lg-9 col-md-8">';

							get_template_part('loop');

							echo '</div>';
							if($setup_item_blogspage_sidebarpos == 2){
									echo '<div class="col-lg-3 col-md-4">';
											if (is_active_sidebar( 'pointfinder-blogspages-area' )) {
												get_sidebar('blogspages' );
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
