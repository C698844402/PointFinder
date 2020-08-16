<?php 
/*
Template Name: Terms & Conditions
*/
echo '<section role="main">';

	echo '<div class="pf-termsconditions-class white-popup-block">'; 
		if (have_posts()){ 
			while (have_posts()) : the_post();
			the_content();
			endwhile;
		};
	echo '</div>';

echo '</section>';
?>