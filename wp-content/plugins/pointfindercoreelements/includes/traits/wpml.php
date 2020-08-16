<?php


if (!trait_exists('PointFinderWPMLFunctions')) {


/**
 * WPML function
 */
trait PointFinderWPMLFunctions
{
    public function PF_current_language(){
		return apply_filters( 'wpml_current_language', NULL );
	}
	
	public function PF_default_language(){
	    return apply_filters( 'wpml_default_language', NULL );
	}

	public function PFLangCategoryID_ld($id,$lang,$post_type_name){
		$translated_id = apply_filters('wpml_object_id',$id,$post_type_name,true,$lang);
		if (!empty($translated_id)) {
			return $translated_id;
		}else{
			return $id;
		}
	}
}
}