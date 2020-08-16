<?php 
$pageid = get_option('page_for_posts');
$_page_sidebar = redux_post_meta("pointfinderthemefmb_options", $pageid, "webbupointfinder_page_sidebar");
	
if ( is_active_sidebar( $_page_sidebar ) ) : ?>
<div class="sidebar-widget">
	<?php dynamic_sidebar($_page_sidebar); ?>
</div>
<?php endif; ?>