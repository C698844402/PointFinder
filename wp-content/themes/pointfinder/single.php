<?php
get_header();
	
	if ( have_posts() ){
		the_post();

		$post_type = get_post_type();
		
		$listing_ptype = false; $agent_ptype = false;
		$listing_ptype = apply_filters( 'pointfinder_lpost_type_check', $listing_ptype, $post_type );
		$agent_ptype = apply_filters( 'pointfinder_apost_type_check', $agent_ptype, $post_type );
		
		if ($post_type != 'post' && ($listing_ptype == true || $agent_ptype == true)) {
		
			do_action('pointfinder_single_page_elements');
		
		}elseif ($post_type == 'post') {
			
			if(function_exists('PFGetHeaderBar')){
				PFGetDefaultPageHeader();
			}
	        $setup_item_blogpage_sidebarpos = PFASSIssetControl('setup_item_blogpage_sidebarpos','','2');
	        get_template_part( 'admin/core/post', 'functions' );

			echo '<section role="main">';
		        echo '<div class="pf-blogpage-spacing pfb-top"></div>';
		        echo '<div class="pf-container"><div class="pf-row">';
		        	if ($setup_item_blogpage_sidebarpos == 3) {
		        		echo '<div class="col-lg-12">';

							get_template_part('sloop');

						echo '</div>';
		        	}else{

			            if($setup_item_blogpage_sidebarpos == 1){
			                echo '<div class="col-lg-3 col-md-4">';
			                    if (is_active_sidebar( 'pointfinder-blogpages-area' )) {
			                    	get_sidebar('singleblog' );
			                    } else {
			                    	get_sidebar();
			                    }

			                echo '</div>';
			            }

			            echo '<div class="col-lg-9 col-md-8">';

			            get_template_part('sloop');

			            echo '</div>';
			            if($setup_item_blogpage_sidebarpos == 2){
			                echo '<div class="col-lg-3 col-md-4">';
			                    if (is_active_sidebar( 'pointfinder-blogpages-area' )) {
			                    	get_sidebar('singleblog' );
			                    } else {
			                    	get_sidebar();
			                    }
			                echo '</div>';
			            }

		            }
		        echo '</div></div>';
		        echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';
		    echo '</section>';
				
		}else{

			echo '<div class="pf-blogpage-spacing pfb-top"></div>';
	        echo '<section role="main">';
	            echo '<div class="pf-container">';
	                echo '<div class="pf-row">';
	                    echo '<div class="col-lg-12">';

	                    	echo '<div style="margin:0 0 30px 20px; font-weight:bold;font-size:24px;">'.get_the_title().'</div>';

	                    	if ( has_post_thumbnail() && wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ) !== false) {

						        $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
						        if ($large_image_url[1]>=850) {
						            $large_image_urlforview = pointfinder_aq_resize($large_image_url[0],850,267,true);

						            if ($large_image_urlforview == false) {
						                $large_image_urlforview = $large_image_url[0];
						            }
						        }else{
						            $large_image_urlforview = pointfinder_aq_resize($large_image_url[0],$large_image_url[1],267,true);

						            if ($large_image_urlforview == false) {
						                $large_image_urlforview = $large_image_url[0];
						            }
						        }
						        echo '<div class="post-mthumbnail"><div class="inner-postmthumb">';
						        echo '<a href="' . esc_url($large_image_url[0]) . '" class="pf-mfp-image">';
						            echo '<img src="'.esc_url($large_image_urlforview).'" class="attachment-full wp-post-image pf-wp-postimg" />';
						            echo '<div class="PStyleHe"></div>';
						        echo '</a>';
						        echo '</div></div>';
						    }
	                        the_content();

	                        echo '<div style="margin:0 0 0 20px;">';
	                        the_excerpt();
	                        echo '</div>';

	                    echo '</div>';
	                echo '</div>';
	            echo '</div>';
	        echo '</section>';
	        echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';

		}
	}

get_footer();
?>
