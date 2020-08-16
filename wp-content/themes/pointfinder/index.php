<?php 
get_header();

if (is_home() && (get_option('show_on_front') == 'posts' || get_option('show_on_front') == 'page')) {
	if(function_exists('PFGetHeaderBar') && (!is_home() && (get_option('show_on_front') != 'posts' && get_option('show_on_front') != 'posts') )){PFGetHeaderBar();}
	$setup_item_blogcatpage_sidebarpos = PFASSIssetControl('setup_item_blogcatpage_sidebarpos','','3');
	echo '<div class="pf-blogpage-spacing pfb-top"></div>';
	echo '<section role="main">';
		echo '<div class="pf-container">';
			echo '<div class="pf-row">';
			if ($setup_item_blogcatpage_sidebarpos == '2') {
				echo '<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">';

					get_template_part('loop');
					

				echo '</div>';
				echo '<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">';

					get_sidebar('blogpages' ); 
					

				echo '</div>';
			}elseif ($setup_item_blogcatpage_sidebarpos == '1') {
				echo '<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">';

					get_sidebar('blogpages' ); 
					

				echo '</div>';

				echo '<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">';

					get_template_part('loop');
					

				echo '</div>';
			}elseif ($setup_item_blogcatpage_sidebarpos == '3') {
				echo '<div class="col-lg-12">';

					get_template_part('loop');
					

				echo '</div>';
			}
				
			echo '</div>';

			if (!class_exists('Pointfindercoreelements')) {
				echo '<div class="pf-container">';
				echo '<div class="pf-row">';
				echo '<div class="col-lg-12">';
				if ( comments_open() || get_comments_number() ){
					comments_template();
				}
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
		echo '</div>';
	echo '</section>';
	echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';
}elseif(is_front_page()){
	if(function_exists('PFGetHeaderBar')){PFGetHeaderBar();}

	echo '<div class="pf-blogpage-spacing pfb-top"></div>';
	echo '<section role="main" class="blog-full-width">';
		echo '<div class="pf-container">';
			echo '<div class="pf-row">';
				echo '<div class="col-lg-12">';

					get_template_part('loop');
					

				echo '</div>';
				
			echo '</div>';
		echo '</div>';

		if (!class_exists('Pointfindercoreelements')) {
			echo '<div class="pf-container">';
			echo '<div class="pf-row">';
			echo '<div class="col-lg-12">';
			if ( comments_open() || get_comments_number() ){
				comments_template();
			}
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
			
	echo '</section>';
	echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';
}else{

	if(function_exists('PFPageNotFound')){
    	PFPageNotFound();
    } 
}

get_footer();
?>