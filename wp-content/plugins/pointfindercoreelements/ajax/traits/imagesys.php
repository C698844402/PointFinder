<?php 
if (class_exists('PointFinderImageSYS')) {
	return;
}


class PointFinderImageSYS extends Pointfindercoreelements_AJAX
{
    public function __construct(){}

    public function pf_ajax_imagesystem(){

		check_ajax_referer( 'pfget_imagesystem', 'security');

		header('Content-Type: text/html; charset=UTF-8;');

		$iid = '';
		$output = '';

		if(isset($_POST['iid']) && $_POST['iid']!=''){
			$iid = sanitize_text_field($_POST['iid']);
		}

		if(isset($_POST['id']) && $_POST['id']!=''){
			$id = sanitize_text_field($_POST['id']);
		}

		if(isset($_POST['process']) && $_POST['process']!=''){
			$process = sanitize_text_field($_POST['process']);
		}

		$oldup = $olduptext = '';
		if(isset($_POST['oldup']) && $_POST['oldup']!=''){
			$oldup = sanitize_text_field($_POST['oldup']);
		}

		if ($oldup == 1) {
			$olduptext = '-old';
		}


		/*Image Remove Process*/
		if (!empty($iid) && !empty($id) && $process == 'd') {
			/*Check this image if this user uploaded*/
			$content_post = get_post($iid);
			$post_author = $content_post->post_author;

			if (get_current_user_id() == $post_author) {
				wp_delete_attachment( $iid, true );
				delete_post_meta( $id, 'webbupointfinder_item_images', $iid );
			}

		};

		/*Cover Image Remove Process*/
		if (!empty($iid) && !empty($id) && $process == 'd2') {
			/*Check this image if this user uploaded*/
			$content_post = get_post($iid);
			$post_author = $content_post->post_author;



			if (get_current_user_id() == $post_author) {
				wp_delete_attachment( $iid, true );
				delete_post_meta( $id, 'webbupointfinder_item_headerimage');
			}

		};

		/*Image Change Process*/
		if (!empty($iid) && !empty($id) && $process == 'c') {
			/*Check this image if this user uploaded*/
			$content_post = get_post($iid);
			$post_author = $content_post->post_author;

			if (get_current_user_id() == $post_author) {
				$imageID_of_featured = get_post_thumbnail_id($id);
				add_post_meta($id, 'webbupointfinder_item_images', $imageID_of_featured);
				delete_post_meta( $id, 'webbupointfinder_item_images', $iid );
				set_post_thumbnail( $id, $iid );
			}
		}


		/*Image List Process*/
		if (!empty($id) && $process == 'l') {
			$content_post = get_post($id);
			$post_author = $content_post->post_author;

			if (get_current_user_id() == $post_author) {

				/*Create HTML*/
				if ($id != '') {
					$images_of_thispost = get_post_meta($id,'webbupointfinder_item_images');
					/*Featured Image*/
					$imageID_of_featured = get_post_thumbnail_id($id);

	        foreach ($images_of_thispost as $images_of_thispostkey => $images_of_thispostvalue) {
	          if (empty($images_of_thispostvalue)) {
	            unset($images_of_thispost[$images_of_thispostkey]);
	          }
	        }
					if (!empty($images_of_thispost) || !empty($imageID_of_featured)) {
	          if (empty($images_of_thispost) && !empty($imageID_of_featured)) {
	            $images_count = 1;
	          }elseif (!empty($images_of_thispost) && !empty($imageID_of_featured)) {
	            if (count($images_of_thispost) == 1) {
	              if (isset($images_of_thispost[0])) {
	      					if (!empty($images_of_thispost[0])) {
	                  $images_count = count($images_of_thispost) + 1;
	                }else{
	                  $images_count = 1;
	                }
	              }else{
	                $images_count = 1;
	              }
	            }else{
	              $images_count = count($images_of_thispost) + 1;
	            }

	          }else{
	            $images_count = 0;
	          }

				$output_images = '';


				/*Start:First export featured*/
				$image_src = wp_get_attachment_image_src( $imageID_of_featured, 'thumbnail' );
	          if ($image_src != false) {
	  					$output_images .= '<li>';
	  					$output_images .= '<div class="pf-itemimage-container">';
	  					$output_images .= '<img src="'.pointfinder_aq_resize($image_src[0],90,90,true,true,true).'">';
	  					$output_images .= '<div class="pf-itemimage-delete"><a class="pf-delete-standartimg'.$olduptext.'" data-pfimgno="'.$imageID_of_featured.'" data-pfpid="'.$id.'" data-pffeatured="yes">'.esc_html__('Remove', 'pointfindercoreelements').'</a></div><div class="pfitemedit-featured"><div>'.esc_html__('Cover Photo', 'pointfindercoreelements').'</div></div>';
	  					$output_images .= '</div>';
	  					$output_images .= '</li>';
	          }

				/*End:First export featured*/

	          if (!empty($images_of_thispost)) {
	    					foreach ($images_of_thispost as $image_number) {
	    						$image_src = wp_get_attachment_image_src( $image_number, 'thumbnail' );
	    						$output_images .= '<li>';
	    						$output_images .= '<div class="pf-itemimage-container">';
	    						$output_images .= '<img src="'.pointfinder_aq_resize($image_src[0],90,90,true,true,true).'">';
	    						$output_images .= '<div class="pf-itemimage-delete"><a class="pf-delete-standartimg'.$olduptext.'" data-pfimgno="'.$image_number.'" data-pfpid="'.$id.'" data-pffeatured="no">'.esc_html__( 'Remove', 'pointfindercoreelements' ).'</a></div><div class="pfitemedit-featured"><a class="pf-change-standartimg'.$olduptext.'" data-pfimgno="'.$image_number.'" data-pfpid="'.$id.'" title="'.esc_html__('You can change your cover photo by clicking here', 'pointfindercoreelements').'">'.esc_html__('Set as Cover', 'pointfindercoreelements').'</a></div>';
	    						$output_images .= '</div>';
	    						$output_images .= '</li>';
	    					}
	          }
						$output .= '<section class="pfuploadform-mainsec">';
							$output .= '<label for="file" class="lbl-text">'.esc_html__('UPLOADED IMAGES','pointfindercoreelements').':</label>';
							$output .= '<ul class="pfimages-ul">'.$output_images.'</ul>';
						$output .= '</section>';

						echo $output;
					}
				}
			}
		}

		/*Image List Process*/
		if (!empty($id) && $process == 'l2') {
			$content_post = get_post($id);
			$post_author = $content_post->post_author;
			$output_images = '';
			if (get_current_user_id() == $post_author) {

				/*Create HTML*/
				if ($id != '') {
					$images_of_thispost = get_post_meta($id,'webbupointfinder_item_headerimage');

					if (isset($images_of_thispost[0])) {
						if (!empty($images_of_thispost[0])) {
							foreach ($images_of_thispost as $image_number) {

								$output_images .= '<li>';
								$output_images .= '<div class="pf-itemimage-container">';
								$output_images .= '<img src="'.pointfinder_aq_resize($image_number['url'],90,90,true,true,true).'">';
								$output_images .= '<div class="pf-itemimage-delete"><a class="pf-delete-coverimg'.$olduptext.'" data-pfimgno="'.$image_number['id'].'" data-pfpid="'.$id.'" data-pffeatured="no">'.esc_html__( 'Remove', 'pointfindercoreelements' ).'</a></div>';
								$output_images .= '</div>';
								$output_images .= '</li>';
							}
							$output .= '<section class="pfuploadform-mainsec">';
								$output .= '<label for="file" class="lbl-text"></label>';
								$output .= '<ul class="pfimages-ul">'.$output_images.'</ul>';
							$output .= '</section>';

							echo $output;
						}
					}
				}
			}
		}

		die();
	} 
  
}