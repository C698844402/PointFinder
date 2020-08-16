<?php 
if (!trait_exists('PointFinderCommonELFunctions')) {

/**
 * Common functions for VC
 */
trait PointFinderCommonELFunctions
{
 
    public function PFVEX_GetTaxValues($taxname,$optionvar,$defaultname){
      $listingtype_terms = array();
      $terms = get_terms($taxname, array('hide_empty'=>false,'orderby'=>'title'));
        $listingtype_terms[esc_html__("All : ", 'pointfindercoreelements').$this->PFSAIssetControl($optionvar,'',$defaultname)] = '';
        if ( !empty( $terms ) && !is_wp_error( $terms ) ){
        foreach ( $terms as $term ) {
          $listingtype_terms[$term->term_id] =  $term->name;
        }
      }
      return $listingtype_terms;
    }

    public function PFVEX_GetTaxValues2($taxname,$optionvar,$defaultname){
		$listingtype_terms = array();
		$terms = get_terms($taxname, array('hide_empty'=>false,'orderby'=>'title','parent'=>0));
			$listingtype_terms[esc_html__("All : ", 'pointfindercoreelements').PFSAIssetControl($optionvar,'',$defaultname)] = '';
			if ( !empty( $terms ) && !is_wp_error( $terms ) ){
			foreach ( $terms as $term ) {
				$listingtype_terms[$term->term_id] =  $term->name;
			}
		}
		return $listingtype_terms;
	}

	public function pf_vc_add_css_animation(){
		return array(
			"type" => "dropdown",
			"heading" => esc_html__("CSS Animation", "pointfindercoreelements"),
			"param_name" => "css_animation",
			"admin_label" => true,
			"value" => array(esc_html__("No", "pointfindercoreelements") => '', esc_html__("Top to bottom", "pointfindercoreelements") => "top-to-bottom", esc_html__("Bottom to top", "pointfindercoreelements") => "bottom-to-top", esc_html__("Left to right", "pointfindercoreelements") => "left-to-right", esc_html__("Right to left", "pointfindercoreelements") => "right-to-left", esc_html__("Appear from center", "pointfindercoreelements") => "appear"),
			"description" => esc_html__("Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.", "pointfindercoreelements")
		  );
	}

	public function PFEX_extract_type_ig($pfarray){
		$output = '';
		if(is_array($pfarray)){
			foreach ($pfarray as $value) {
				if ($output != '') {
					$output .= ',';
				}
				$output .= $value;
			}
			return $output;
		}else{return $pfarray;}
	}

	public function pointfinder_featured_image_getresized_ex($listing_meta,$listingtype_single,$template_directory_uri,$general_crop2,$general_retinasupport,$setupsizelimitconf_general_gridsize1_width,$setupsizelimitconf_general_gridsize1_height){
		$noimg_url = PFCOREELEMENTSURLPUBLIC.'images/noimg.png';
		$featured_image = '';

		if (isset($listing_meta[$listingtype_single]['pf_timage_of_listing'])) {
			$featured_image = $listing_meta[$listingtype_single]['pf_timage_of_listing'];
			if (isset($featured_image[0])) {

				$featured_image = wp_get_attachment_image_src( $featured_image[0], 'full');
			}
		}

		$featured_image_original = isset($featured_image[0])?$featured_image[0]:'';

		if($general_retinasupport == 1){$pf_retnumber = 2;}else{$pf_retnumber = 1;}
		$featured_image_width = $setupsizelimitconf_general_gridsize1_width*$pf_retnumber;
		$featured_image_height = $setupsizelimitconf_general_gridsize1_height*$pf_retnumber;

		if(!empty($featured_image[0])){

			switch ($general_crop2) {
				case 1:
					$featured_image_output = pointfinder_aq_resize($featured_image[0],$featured_image_width,$featured_image_height,true,true,true);
					break;
				case 2:

					$featured_image_output = pointfinder_aq_resize($featured_image[0],$featured_image_width,$featured_image_height,true);

					if($featured_image_output === false) {
						if($general_retinasupport == 1){
							$featured_image_output = pointfinder_aq_resize($featured_image[0],$featured_image_width/2,$featured_image_height/2,true);
							if($featured_image_output === false) {
								$featured_image_output = $featured_image_original;
								if($featured_image_output == '') {
									$featured_image_output = $noimg_url;
								}
							}
						}else{
							$featured_image_output = pointfinder_aq_resize($featured_image[0],$featured_image_width/2,$featured_image_height/2,true);
							if ($featured_image_output === false) {
								$featured_image_output = pointfinder_aq_resize($featured_image[0],$featured_image_width/4,$featured_image_height/4,true);
								if ($featured_image_output === false) {
									$featured_image_output = $featured_image_original;
									if($featured_image_output == '') {
										$featured_image_output = $noimg_url;
									}
								}
							}

							$featured_image_output = $featured_image_original;
							if($featured_image_output == '') {
								$featured_image_output = $noimg_url;
							}
						}

					}
					break;

				case 3:
					$featured_image_output = $featured_image_original;
					break;
			}

		}else{
			$featured_image_output = $noimg_url;
		}

		return array('featured_image' => $featured_image_output,'featured_image_org' => $featured_image_original);

	}

	public function pointfinder_featured_image_getresized($pfitemid,$template_directory_uri,$general_crop2,$general_retinasupport,$setupsizelimitconf_general_gridsize1_width,$setupsizelimitconf_general_gridsize1_height){
		$noimg_url = PFCOREELEMENTSURLPUBLIC.'images/noimg.png';
		$featured_image = '';
		$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $pfitemid ), 'full' );

		$featured_image_original = $featured_image[0];

		if($general_retinasupport == 1){$pf_retnumber = 2;}else{$pf_retnumber = 1;}
		$featured_image_width = $setupsizelimitconf_general_gridsize1_width*$pf_retnumber;
		$featured_image_height = $setupsizelimitconf_general_gridsize1_height*$pf_retnumber;

		if(!empty($featured_image[0])){

			switch ($general_crop2) {
				case 1:
					$featured_image_output = pointfinder_aq_resize($featured_image[0],$featured_image_width,$featured_image_height,true,true,true);
					break;
				case 2:

					$featured_image_output = pointfinder_aq_resize($featured_image[0],$featured_image_width,$featured_image_height,true);

					if($featured_image_output === false) {
						if($general_retinasupport == 1){
							$featured_image_output = pointfinder_aq_resize($featured_image[0],$featured_image_width/2,$featured_image_height/2,true);
							if($featured_image_output === false) {
								$featured_image_output = $featured_image_original;
								if($featured_image_output == '') {
									$featured_image_output = $noimg_url;
								}
							}
						}else{
							$featured_image_output = pointfinder_aq_resize($featured_image[0],$featured_image_width/2,$featured_image_height/2,true);
							if ($featured_image_output === false) {
								$featured_image_output = pointfinder_aq_resize($featured_image[0],$featured_image_width/4,$featured_image_height/4,true);
								if ($featured_image_output === false) {
									$featured_image_output = $featured_image_original;
									if($featured_image_output == '') {
										$featured_image_output = $noimg_url;
									}
								}
							}

							$featured_image_output = $featured_image_original;
							if($featured_image_output == '') {
								$featured_image_output = $noimg_url;
							}
						}

					}
					break;

				case 3:
					$featured_image_output = $featured_image_original;
					break;
			}

		}else{
			$featured_image_output = $noimg_url;
		}

		return array('featured_image' => $featured_image_output,'featured_image_org' => $featured_image_original);

	}
}
	
}