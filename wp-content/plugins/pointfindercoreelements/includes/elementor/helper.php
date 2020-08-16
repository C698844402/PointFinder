<?php
namespace PointFinderElementorSYS;

class Helper {

	public function __construct(){

	}

	public static function get_cpt_posts($post_type) {
	    
	    if (empty($post_type)) {return;}

	    global $wpdb;

	    $results = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", $post_type ), ARRAY_A );

	    if ( ! $results ){return;}

	    $output = array();
	    foreach( $results as $index => $post ) {
	        $output[$post['ID']] = $post['post_title'];
	    }

	    return $output;
	}

}