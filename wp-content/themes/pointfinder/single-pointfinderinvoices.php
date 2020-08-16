<?php 
	if ( have_posts() ){
		the_post();

		$post_id = get_the_id();
		$current_user = wp_get_current_user();

		$content = '';

		echo apply_filters( 'pointfinder_invoicepage_filter', $content, $post_id, $current_user );
	}
			
?>