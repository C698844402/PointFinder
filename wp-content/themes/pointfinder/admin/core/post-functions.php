<?php

/**********************************************************************************************************************************
*
* Common functions for posts
*
* Author: Webbu
* Please do not modify below functions.
***********************************************************************************************************************************/

if (!function_exists('pointfinderwp_excerpt')) {
    function pointfinderwp_excerpt($length_callback = '', $more_callback = '')
    {
        global $post;

        $output = do_shortcode(get_the_content('' . esc_html__('Read more', 'pointfinder') . ''));
        $output = apply_filters('convert_chars', $output);
        if (strpos($output, '<!--more-->')){$output = apply_filters('the_content_more_link', $output);}
        echo apply_filters('the_content', $output);

    }
}



if (!function_exists('pointfinderwp_excerpt_single')) {
    function pointfinderwp_excerpt_single($length_callback = '', $more_callback = '')
    {
        if (!class_exists('Pointfindercoreelements')) {
            the_content();
        }else{
            do_action('pointfinder_gallery_post_action');
        }
    }
}


if (!class_exists('pf_singlepost_title')) {
    function pf_singlepost_title(){
       echo '<div class="post-mtitle">'.get_the_title().'</div>';
    }
}

if (!class_exists('pf_singlepost_title_list')) {
    function pf_singlepost_title_list(){

        echo '<div class="post-mtitle" ><a href="'.get_the_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></div>';
    }
}

if (!class_exists('pf_singlepost_thumbnail')) {
    function pf_singlepost_thumbnail(){

        if (has_post_format( array('audio','video'))) {return;}

        if ( !has_post_thumbnail()) {return;}

        $post_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );

        if ($post_img == false) {return;}

        if ($post_img[1]>=850) {

            if (function_exists('pointfinder_aq_resize')) {
                $large_image_urlforview = pointfinder_aq_resize($post_img[0],850,267,true);
            }else{
                $large_image_urlforview = $post_img[0];
            }

            if ($large_image_urlforview == false) {$large_image_urlforview = $post_img[0];}

        }else{

            if (function_exists('pointfinder_aq_resize')) {
                $large_image_urlforview = pointfinder_aq_resize($post_img[0],$post_img[1],267,true);
            }else{
                $large_image_urlforview = $post_img[0];
            }

            if ($large_image_urlforview == false) {$large_image_urlforview = $post_img[0];}
        }

        echo '<div class="post-mthumbnail"><div class="inner-postmthumb">';
        echo '<a href="' . esc_url($post_img[0]) . '" class="pf-mfp-image">';
            echo '<img src="'.esc_url($large_image_urlforview).'" class="attachment-full wp-post-image pf-wp-postimg" />';
            echo '<div class="PStyleHe"></div>';
        echo '</a>';
        echo '</div></div>';

    }
}

if (!class_exists('pf_singlepost_thumbnail_list')) {
    function pf_singlepost_thumbnail_list(){
        if ( has_post_thumbnail() && wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ) !== false) {

            $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
            if ($large_image_url[1]>=850) {
                
                if (function_exists('pointfinder_aq_resize')) {
                    $large_image_urlforview = pointfinder_aq_resize($large_image_url[0],850,267,true);
                }else{
                    $large_image_urlforview = $large_image_url[0];
                }

                if ($large_image_urlforview == false) {
                    $large_image_urlforview = $large_image_url[0];
                }
            }else{
                
                if (function_exists('pointfinder_aq_resize')) {
                    $large_image_urlforview = pointfinder_aq_resize($large_image_url[0],$large_image_url[1],267,true);
                }else{
                    $large_image_urlforview = $large_image_url[0];
                }

                if ($large_image_urlforview == false) {
                    $large_image_urlforview = $large_image_url[0];
                }
            }
            echo '<div class="post-mthumbnail"><div class="inner-postmthumb">';
            echo '<a href="' . esc_url(get_the_permalink()) . '">';
                echo '<img src="'.esc_url($large_image_urlforview).'" class="attachment-full wp-post-image pf-wp-postimg"/>';
                echo '<div class="PStyleHe"></div>';
            echo '</a>';
            echo '</div></div>';
        }
    }
}

if (!class_exists('pf_singlepost_thumbnail_list_small')) {
    function pf_singlepost_thumbnail_list_small(){
        if ( has_post_thumbnail() && wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ) !== false) {

            $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
            
            if (function_exists('pointfinder_aq_resize')) {
                $large_image_url_out = pointfinder_aq_resize($large_image_url[0],680,270,true);
            }else{
                $large_image_url_out = $large_image_url[0];
            }
            if ($large_image_url_out == false) {
                $large_image_url_out = $large_image_url[0];
            }


            echo '<div class="post-mthumbnail"><div class="pflist-imagecontainer">';
                echo '<img src="'.esc_url($large_image_url_out).'" class="attachment-full wp-post-image pf-wp-smallthumbpost"/>';
                echo '
                    <div class="pfImageOverlayH hidden-xs"></div>
                        <div class="pfButtons pfStyleV2 pfStyleVAni hidden-xs">
                            <span class="pfHoverButtonStyle pfHoverButtonWhite pfHoverButtonSquare clearfix">
                                <a class="pficon-imageclick pf-mfp-image" href="'.esc_url($large_image_url[0]).'">
                                    <i class="pfadmicon-glyph-684"></i>
                                </a>
                            </span>
                            <span class="pfHoverButtonStyle pfHoverButtonWhite pfHoverButtonSquare">
                                <a href="' . get_the_permalink() . '">
                                    <i class="pfadmicon-glyph-794"></i>
                                </a>
                            </span>
                        </div>
                ';
            echo '</div></div>';
        }
    }
}

if (!class_exists('pf_singlepost_info')) {
    function pf_singlepost_info(){
        $cats_arr = get_the_category();
        echo '<div class="post-minfo"><i class="pfadmicon-glyph-28"></i>';
        echo get_the_time(get_option('date_format')).' / ';
        echo comments_popup_link( '', '<i class="pfadmicon-glyph-382"></i> '.esc_html__( '1 Comment / ', 'pointfinder' ), '<i class="pfadmicon-glyph-382"></i> '.esc_html__( '% Comments / ', 'pointfinder' ),'', '');
        echo esc_html__( 'by ', 'pointfinder' );
        echo the_author_posts_link();
        echo get_the_tags('/ <i class="pfadmicon-glyph-22"></i>',', ','');
        echo (PFControlEmptyArr($cats_arr))? ' / '.esc_html__( 'in ', 'pointfinder' )  : '';
        echo the_category(', ');
        echo '</div>';
    }
}

if (!class_exists('pf_singlepost_info_list')) {
    function pf_singlepost_info_list(){
        $cats_arr = get_the_category();
        echo '<div class="post-minfo"><i class="pfadmicon-glyph-28"></i>';
        echo get_the_time(get_option('date_format')).' / ';
        echo comments_popup_link( '', '<i class="pfadmicon-glyph-382"></i> '.esc_html__( '1 Comment / ', 'pointfinder' ), '<i class="pfadmicon-glyph-382"></i> '.esc_html__( '% Comments / ', 'pointfinder' ),'', '');
        echo esc_html__( 'by ', 'pointfinder' );
        echo the_author_posts_link();
        echo get_the_tags('/ <i class="pfadmicon-glyph-22"></i>',', ','');
        echo (PFControlEmptyArr($cats_arr))? ' / '.esc_html__( 'in ', 'pointfinder' )  : '';
        echo the_category(', ');

        echo '<div class="meta-comment-link pull-right">';
        echo '<a class="pull-right post-link" href="'.get_the_permalink().'" title="'.get_the_title().'">'.esc_html__('Read more','pointfinder').'&nbsp;<i class="fas fa-angle-right"></i></a>';
        echo '</div>';

        echo '</div>';
    }
}

if (!class_exists('pf_singlepost_content')) {
    function pf_singlepost_content(){


    	if ( has_shortcode( get_the_content(), 'gallery' ) ) {

    		$gallery = get_post_gallery(get_the_id(),false);
    		if (isset($gallery['ids'])) {
    			$gallery_ids = explode(',', $gallery['ids']);

    			if (is_array($gallery_ids)) {

    			$gridrandno_orj = PF_generate_random_string_ig();
    			echo '<div class="vc-image-carousel">';
    			echo '<ul id="'.$gridrandno_orj.'" class="pf-gallery-slider">';

    				foreach ($gallery_ids as $gallery_id) {

    					$large_image_url = wp_get_attachment_image_src( $gallery_id, 'full' );
                        
                        if (function_exists('pointfinder_aq_resize')) {
                            $large_image_url_out = pointfinder_aq_resize($large_image_url[0],680,270,true);
                        }else{
                            $large_image_url_out = $large_image_url[0];
                        }
    			        echo '<li>
                        <div class="pflist-imagecontainer">
                        <img src="'.$large_image_url_out.'" alt="-"/>
                        <div class="pfImageOverlayH hidden-xs"></div>
                        <div class="pfButtons pfStyleV2 pfStyleVAni hidden-xs">
                            <span class="pfHoverButtonStyle pfHoverButtonWhite pfHoverButtonSquare clearfix">
                                <a class="pficon-imageclick pf-mfp-image" href="'.$large_image_url[0].'" >
                                    <i class="pfadmicon-glyph-684"></i>
                                </a>
                            </span>
                            <span class="pfHoverButtonStyle pfHoverButtonWhite pfHoverButtonSquare">
                                <a href="' . get_the_permalink() . '">
                                    <i class="pfadmicon-glyph-794"></i>
                                </a>
                            </span>
                        </div>
                        </div>
                        </li>';

    				}

    			echo '</ul>';
    			echo '</div>';
    			

    			$script_output = '(function($) {
    "use strict";$(function() {

    					$("#'.esc_attr($gridrandno_orj).'").owlCarousel({
    							items : 1,
    							navigation : true,
    							paginationNumbers : false,
    							pagination : false,
    							autoPlay : false,
    							slideSpeed:7000,
    							mouseDrag:true,
    							touchDrag:true,
    							itemSpaceWidth: 10,
    							autoHeight : false,
    							responsive:true,
    							transitionStyle: "fade",
    							itemsScaleUp : false,
    							navigationText:false,
    							theme:"owl-theme",
    							singleItem : true,
    							itemsCustom : true,
    							itemsDesktop : [1199,1],
    							itemsDesktopSmall : [980,1],
    							itemsTablet: [768,1],
    							itemsTabletSmall: false,
    							itemsMobile : [479,1],

    						});
    				});})(jQuery);
                ';
                wp_add_inline_script( 'pftheme-customjs', $script_output );

    	        }
    		}

    	}


        echo '<div class="post-content">';
        echo pointfinderwp_excerpt_single();
        echo '</div>';
        
    }
}

if (!class_exists('pf_singlepost_content_list')) {
    function pf_singlepost_content_list(){
        echo '<div class="post-content clearfix">';
        echo pointfinderwp_excerpt();
        echo '</div>';
    }
}

if (!class_exists('pf_singlepost_comments')) {
    function pf_singlepost_comments(){
        echo '<div class="pfsinglecommentheader" id="comments">';
            if ( comments_open() ){
               ob_start();
               comments_popup_link( esc_html__('No comments yet','pointfinder'), esc_html__('1 comment','pointfinder'), esc_html__('% comments','pointfinder'), 'comments-link', esc_html__('Comments are off for this post','pointfinder'));
               $clink = ob_get_contents();
               ob_end_clean();
            }else{
               $clink = esc_html__('Comments','pointfinder');
            };
            echo '
            <div class="pf-singlepost-clink">'.$clink.'</div>';

        echo '</div>';
        echo '<div class="pftcmcontainer singleblogcomments">';
            comments_template();
        echo '</div>';
    }
}