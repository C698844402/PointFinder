<?php
/*************************************************************************************************************************
*
* Common functions after v1.9
*
* Author: Webbu
*************************************************************************************************************************/


if (!function_exists('is_elementor_page')) {
	//var_dump(\Elementor\Plugin::$instance->preview->is_preview_mode());
    //var_dump(\Elementor\Plugin::$instance->db->is_built_with_elementor($post_id));
	function is_elementor_page($post_id){

		$result = false;

		if (class_exists('\Elementor\Plugin')) {
            if (\Elementor\Plugin::$instance->db->is_built_with_elementor($post_id)) {
                $result = true;
            }
        }

        return $result;
	}
}

if (!function_exists('pointfinderhex2rgbex')) {
	function pointfinderhex2rgbex($hex, $opacity='1.0')
    {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }


        return array('rgba' => 'rgba('.$r.','.$g.','.$b.','.$opacity.')','rgb'=> 'rgb('.$r.','.$g.','.$b.')');
    }
}

if (!function_exists('PFSAIssetControl')) {
	function PFSAIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){
		global $pointfindertheme_option;
		
		if (empty($pointfindertheme_option)) {
		  $pointfindertheme_option = get_option('pointfindertheme_options');
		}

		if($field2 == ''){
		  if (!isset($pointfindertheme_option[''.$field.''])) {
		    return $default;
		  }
		  if ($pointfindertheme_option[''.$field.''] == "") {
		    return $default;
		  }
		  return $pointfindertheme_option[''.$field.''];
		}else{
		  if (!isset($pointfindertheme_option[''.$field.''][''.$field2.''])) {
		    return $default;
		  }
		  if ($pointfindertheme_option[''.$field.''][''.$field2.''] == "") {
		    return $default;
		  }
		  return $pointfindertheme_option[''.$field.''][''.$field2.''];
		};
	}
}
if (!function_exists('PFASSIssetControl')) {
	function PFASSIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){
		global $pfascontrol_options;

		if (empty($pfascontrol_options)) {
		  $pfascontrol_options = get_option('pfascontrol_options');
		}

		if($field2 == ''){
		  if (!isset($pfascontrol_options[''.$field.''])) {
		    return $default;
		  }
		  if ($pfascontrol_options[''.$field.''] == "") {
		    return $default;
		  }
		  return $pfascontrol_options[''.$field.''];
		}else{
		  if (!isset($pfascontrol_options[''.$field.''][''.$field2.''])) {
		    return $default;
		  }
		  if ($pfascontrol_options[''.$field.''][''.$field2.''] == "") {
		    return $default;
		  }
		  return $pfascontrol_options[''.$field.''][''.$field2.''];
		};
	}
}
if (!function_exists('PFADVIssetControl')) {
	function PFADVIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){
		global $pfadvancedcontrol_options;

		if (empty($pfadvancedcontrol_options)) {
		  $pfadvancedcontrol_options = get_option('pfadvancedcontrol_options');
		}

		if($field2 == ''){
		  if (!isset($pfadvancedcontrol_options[''.$field.''])) {
		    return $default;
		  }
		  if ($pfadvancedcontrol_options[''.$field.''] == "") {
		    return $default;
		  }
		  return $pfadvancedcontrol_options[''.$field.''];
		}else{
		  if (!isset($pfadvancedcontrol_options[''.$field.''][''.$field2.''])) {
		    return $default;
		  }
		  if ($pfadvancedcontrol_options[''.$field.''][''.$field2.''] == "") {
		    return $default;
		  }
		  return $pfadvancedcontrol_options[''.$field.''][''.$field2.''];
		};
	}
}
if (!function_exists('PFSizeSIssetControl')) {
	function PFSizeSIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){

		global $pfsizecontrol_options;

		if (empty($pfsizecontrol_options)) {
		  $pfsizecontrol_options = get_option('pfsizecontrol_options');
		}

		if($field2 == ''){
		  if (!isset($pfsizecontrol_options[''.$field.''])) {
		    return $default;
		  }
		  if ($pfsizecontrol_options[''.$field.''] == "") {
		    return $default;
		  }
		  return $pfsizecontrol_options[''.$field.''];
		}else{
		  if (!isset($pfsizecontrol_options[''.$field.''][''.$field2.''])) {
		    return $default;
		  }
		  if ($pfsizecontrol_options[''.$field.''][''.$field2.''] == "") {
		    return $default;
		  }
		  return $pfsizecontrol_options[''.$field.''][''.$field2.''];
		};
	}
}
if (!function_exists('PFREVSIssetControl')) {
	function PFREVSIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){

		global $pfitemreviewsystem_options;

		if (empty($pfitemreviewsystem_options)) {
		  $pfitemreviewsystem_options = get_option('pfitemreviewsystem_options');
		}

		if($field2 == ''){
		  if (!isset($pfitemreviewsystem_options[''.$field.''])) {
		    return $default;
		  }
		  if ($pfitemreviewsystem_options[''.$field.''] == "") {
		    return $default;
		  }
		  return $pfitemreviewsystem_options[''.$field.''];
		}else{
		  if (!isset($pfitemreviewsystem_options[''.$field.''][''.$field2.''])) {
		    return $default;
		  }
		  if ($pfitemreviewsystem_options[''.$field.''][''.$field2.''] == "") {
		    return $default;
		  }
		  return $pfitemreviewsystem_options[''.$field.''][''.$field2.''];
		};
	}
}
if (!function_exists('PFPGIssetControl')) {
	function PFPGIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){

		global $pfpgcontrol_options;

		if (empty($pfpgcontrol_options)) {
		  $pfpgcontrol_options = get_option('pfpgcontrol_options');
		}

		if($field2 == ''){
		  if (!isset($pfpgcontrol_options[''.$field.''])) {
		    return $default;
		  }
		  if ($pfpgcontrol_options[''.$field.''] == "") {
		    return $default;
		  }
		  return $pfpgcontrol_options[''.$field.''];
		}else{
		  if (!isset($pfpgcontrol_options[''.$field.''][''.$field2.''])) {
		    return $default;
		  }
		  if ($pfpgcontrol_options[''.$field.''][''.$field2.''] == "") {
		    return $default;
		  }
		  return $pfpgcontrol_options[''.$field.''][''.$field2.''];
		};
	}
}
if (!function_exists('PFCFIssetControl')) {
	function PFCFIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){
		global $pfcustomfields_options;

		if (empty($pfcustomfields_options)) {
		  $pfcustomfields_options = get_option('pfcustomfields_options');
		}

		if($field2 == ''){
		  if (!isset($pfcustomfields_options[''.$field.''])) {
		    return $default;
		  }
		  if ($pfcustomfields_options[''.$field.''] == "") {
		    return $default;
		  }
		  return $pfcustomfields_options[''.$field.''];
		}else{
		  if (!isset($pfcustomfields_options[''.$field.''][''.$field2.''])) {
		    return $default;
		  }
		  if ($pfcustomfields_options[''.$field.''][''.$field2.''] == "") {
		    return $default;
		  }
		  return $pfcustomfields_options[''.$field.''][''.$field2.''];
		};
	}
}
if (!function_exists('PFSFIssetControl')) {
	function PFSFIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){
		global $pfsearchfields_options;

		if (empty($pfsearchfields_options)) {
		  $pfsearchfields_options = get_option('pfsearchfields_options');
		}

		if($field2 == ''){
		  if (!isset($pfsearchfields_options[''.$field.''])) {
		    return $default;
		  }
		  if ($pfsearchfields_options[''.$field.''] == "") {
		    return $default;
		  }
		  return $pfsearchfields_options[''.$field.''];
		}else{
		  if (!isset($pfsearchfields_options[''.$field.''][''.$field2.''])) {
		    return $default;
		  }
		  if ($pfsearchfields_options[''.$field.''][''.$field2.''] == "") {
		    return $default;
		  }
		  return $pfsearchfields_options[''.$field.''][''.$field2.''];
		};
	}
}
if (!function_exists('PFMSIssetControl')) {
	function PFMSIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){

		global $pointfindermail_option;

		if (empty($pointfindermail_option)) {
		  $pointfindermail_option = get_option('pointfindermail_options');
		}

		if($field2 == ''){
		  if(isset($pointfindermail_option[''.$field.'']) == false || $pointfindermail_option[''.$field.''] == ""){
		    $output = $default;
		  }else{
		    $output = $pointfindermail_option[''.$field.''];
		  }
		}else{
		  if(isset($pointfindermail_option[''.$field.''][''.$field2.'']) == false || $pointfindermail_option[''.$field.''][''.$field2.''] == ""){
		    $output = $default;
		  }else{
		    $output = $pointfindermail_option[''.$field.''][''.$field2.''];
		  }
		};
		return $output;
	}
}
if (!function_exists('PFPFIssetControl')) {
	function PFPFIssetControl($field, $field2 = '', $default = ''){
		global $pfcustompoints_options;

		if (empty($pfcustompoints_options)) {
		  $pfcustompoints_options = get_option('pfcustompoints_options');
		}

		if($field2 == ''){
		  if (!isset($pfcustompoints_options[''.$field.''])) {
		    return $default;
		  }
		  if ($pfcustompoints_options[''.$field.''] == "") {
		    return $default;
		  }
		  return $pfcustompoints_options[''.$field.''];
		}else{
		  if (!isset($pfcustompoints_options[''.$field.''][''.$field2.''])) {
		    return $default;
		  }
		  if ($pfcustompoints_options[''.$field.''][''.$field2.''] == "") {
		    return $default;
		  }
		  return $pfcustompoints_options[''.$field.''][''.$field2.''];
		};
	}
}


if (!function_exists('PFPermalinkCheck')) {
	function PFPermalinkCheck(){
		$current_permalinkst = get_option('permalink_structure');

		if ($current_permalinkst == false || $current_permalinkst == '') {
			/* This using ? default. */
			return '&';
		}else{
			$current_permalinkst_last = substr($current_permalinkst, -1);
			if($current_permalinkst_last == '%'){
				return '/?';
			}elseif($current_permalinkst_last == '/'){
				return '?';
			}
		}
	}
}

if (!function_exists('PFControlEmptyArr')) {
	function PFControlEmptyArr($value){
		if(is_array($value)){
			if(count($value)>0){
				return true;
			}else{return false;}
		}else{return false;}
	}
}
if (!function_exists('pfstring2BasicArray')) {
	function pfstring2BasicArray($string, $kv = ',') {
		$ka = array();
		if($string != ''){
			if(strpos($string, $kv) != false){
				$string_exp = explode($kv,$string);
				foreach($string_exp as $s){
					$ka[]=$s;
				}
			}else{
				return array($string);
			}
		}
		return $ka;
	}
}
/**
*Start: Data Validation for all fields
**/
	if (!function_exists('PFCleanArrayAttr')) {
		function PFCleanArrayAttr($callback, $array) {

			$exclude_list = array('item_desc','item_title','item_mesrev', 'webbupointfinder_item_custombox1','webbupointfinder_item_custombox2','webbupointfinder_item_custombox3','webbupointfinder_item_custombox4','webbupointfinder_item_custombox5','webbupointfinder_item_custombox6');

		    foreach ($array as $key => $value) {
		        if (is_array($array[$key])) {
		        	if (!in_array($key, $exclude_list)) {
		        		$array[$key] = PFCleanArrayAttr($callback, $array[$key]);
		        	}else{
		        		if ($key == 'item_mesrev') {
		        			$array[$key] = wp_kses_post($array[$key]);
		        		}else{
		        			$array[$key] = $array[$key];
		        		}
		            }
		        }else{
		        	if(!in_array($key, $exclude_list)){
		            	$array[$key] = call_user_func($callback, $array[$key]);
		            }else{
		           		if ($key == 'item_mesrev') {
		        			$array[$key] = wp_kses_post($array[$key]);
		        		}else{
		        			$array[$key] = $array[$key];
		        		}
		            }
		        }
		    }
		    return $array;
		}
	}
	if (!function_exists('PFCleanFilters')) {
		function PFCleanFilters($arrayvalue){
			return esc_attr(sanitize_text_field($arrayvalue));
		}
	}
/**
*End: Data Validation for all fields
**/
if (!function_exists('PFGetArrayValues_ld')) {
	function PFGetArrayValues_ld($pfvalue){
		if(!is_array($pfvalue)){
			$pfvalue_arr = array();
			if(strpos($pfvalue,',')){
				$newpfvalues = explode(',',$pfvalue);
				foreach($newpfvalues as $newpfvalue){
					array_push($pfvalue_arr,$newpfvalue);
				}
			}else{
				array_push($pfvalue_arr,$pfvalue);
			}
			return $pfvalue_arr;
		}else{
			return $pfvalue;
		}
	}
}
if (!function_exists('PF_generate_random_string_ig')) {
	function PF_generate_random_string_ig($name_length = 12) {
		$alpha_numeric = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		return substr(str_shuffle($alpha_numeric), 0, $name_length);
	}
}


if (!function_exists('pointfinder_debug_log_print')) {
	function pointfinder_debug_log_print($val){
		ob_start();
		print_r($val);
		$contentx = ob_get_contents();
		ob_end_clean();
		error_log($contentx);
	}
}

if (!function_exists('pfsocialtoicon')) {
	function pfsocialtoicon($name){
            switch ($name) {
                case 'facebook':
                    return 'fab fa-facebook-f';
                    break;

                case 'pinterest':
                    return 'fab fa-pinterest-p';
                    break;

                case 'twitter':
                    return 'fab fa-twitter';
                    break;

                case 'linkedin':
                    return 'fab fa-linkedin-in';
                    break;

                case 'google-plus':
                    return 'fab fa-google';
                    break;

                case 'dribbble':
                    return 'fab fa-dribbble';
                    break;

                case 'dropbox':
                    return 'fab fa-dropbox';
                    break;

                case 'flickr':
                    return 'fab fa-flickr';
                    break;

                case 'github':
                    return 'fab fa-github';
                    break;

                case 'instagram':
                    return 'fab fa-instagram';
                    break;

                case 'skype':
                    return 'fab fa-skype';
                    break;

                case 'rss':
                    return 'fas fa-rss';
                    break;

                case 'tumblr':
                    return 'fab fa-tumblr';
                    break;

                case 'vk':
                    return 'fab fa-vk';
                    break;

                case 'youtube':
                    return 'fab fa-youtube';
                    break;
            }
    }	
}


/* Static */

if (!function_exists('pointfindercomments')) {
	function pointfindercomments($comment, $args, $depth){
	      $GLOBALS['comment'] = $comment;
	      extract($args, EXTR_SKIP);

	      if ( 'div' == $args['style'] ) {
	        $tag = 'div';
	        $add_below = 'comment';
	      } else {
	        $tag = 'li';
	        $add_below = 'div-comment';
	      }
	    ?>
	      <?php 
	      
	      echo '<'; echo sanitize_text_field($tag);?> 
	      <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	      <?php if ( 'div' != $args['style'] ){ ?>
	      <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	      <?php }; ?>

	      <div class="comment-author-image">
	         <?php if ($args['avatar_size'] != 0){echo get_avatar( $comment,128 );} ?>
	      </div>

	        <div class="comments-detail-container">

	            <div class="comment-author-vcard">
	                <?php printf(esc_html__('%s says:', 'pointfinder'), get_comment_author_link()) ?>
	            </div>

	            <?php if ($comment->comment_approved == '0') { ?>
	              <em class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'pointfinder') ?></em>
	              <br />
	            <?php }; ?>

	          <div class="comment-meta commentmetadata">
	                <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
	            <?php
	              printf( esc_html__('%1$s at %2$s', 'pointfinder'), get_comment_date(),  get_comment_time()) ?></a>
	                    <?php edit_comment_link(esc_html__('Edit', 'pointfinder'),'  ','' );
	            ?>
	          </div>

	            <div class="comment-textarea">
	          <?php comment_text() ?>
	            </div>

	          <div class="reply"> <i class="pfadmicon-glyph-362"></i>
	             <?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	          </div>
	        </div>

	      <?php if ( 'div' != $args['style'] ){ ?>
	      </div>
	      <?php }; ?>
	    <?php 
	}
}

if (!function_exists('pointfinder_navigation_menu')) {
	function pointfinder_navigation_menu($type = ""){
	  $defaults = array(
	      'theme_location'  => 'pointfinder-main-menu',
	      'menu'            => '',
	      'container'       => '',
	      'container_class' => '',
	      'container_id'    => '',
	      'menu_class'      => '',
	      'menu_id'         => '',
	      'echo'            => true,
	      'fallback_cb'     => 'wp_page_menu',
	      'before'          => '',
	      'after'           => '',
	      'link_before'     => '',
	      'link_after'      => '',
	      'items_wrap'      => '%3$s',
	      'depth'           => 0,
	      'walker'          => (class_exists('pointfinder_walker_nav_menu'))? new pointfinder_walker_nav_menu($type): ''
	  );
	  if (has_nav_menu( 'pointfinder-main-menu' )) {
	    wp_nav_menu( $defaults );
	  }
	}
}

if (!function_exists('pointfinder_footer_navigation_menu')) {
	function pointfinder_footer_navigation_menu(){
	  $defaults = array(
	      'theme_location'  => 'pointfinder-footer-menu',
	      'menu'            => '',
	      'container'       => 'div',
	      'container_class' => 'pf-footer-menu',
	      'container_id'    => '',
	      'menu_class'      => '',
	      'menu_id'         => '',
	      'echo'            => true,
	      'fallback_cb'     => 'wp_page_menu',
	      'before'          => '',
	      'after'           => '',
	      'link_before'     => '',
	      'link_after'      => '',
	      'items_wrap'      => '%3$s',
	      'depth'           => 0,
	      'walker'          => ''
	  );
	  if (has_nav_menu( 'pointfinder-footer-menu' )) {
	    wp_nav_menu( $defaults );
	  }
	}
}

/**
*Start:Page Functions
**/
	if (!function_exists('PFGetHeaderBar')) {
		function PFGetHeaderBar($post_id='', $post_title=''){

		    if($post_id == ''){
		        $post_id = get_the_ID();
		    }

		    if (!function_exists('redux_post_meta')) {
		    	if(function_exists('PFGetDefaultPageHeader')){
					PFGetDefaultPageHeader(array('pagename' => get_the_title()));
					return;
				}
		    	return;
		    }
		    $_page_titlebararea = redux_post_meta("pointfinderthemefmb_options", $post_id, "webbupointfinder_page_titlebararea");

		    if($_page_titlebararea == 1){

		    	$_page_defaultheaderbararea = redux_post_meta("pointfinderthemefmb_options", $post_id, "webbupointfinder_page_defaultheaderbararea");

		    	if ($_page_defaultheaderbararea == 1) {
		    		if(function_exists('PFGetDefaultPageHeader')){
						PFGetDefaultPageHeader(array('pagename' => get_the_title()));
						return;
					}

		    	}

		    	$_page_titlebarareatext = redux_post_meta("pointfinderthemefmb_options", $post_id, "webbupointfinder_page_titlebarareatext");
		    	$_page_titlebarcustomtext_color = redux_post_meta( "pointfinderthemefmb_options", $post_id, "webbupointfinder_page_titlebarcustomtext_color" );
		    	$_page_titlebarcustomtext = redux_post_meta("pointfinderthemefmb_options", $post_id, "webbupointfinder_page_titlebarcustomtext");
		        $_page_titlebarcustomsubtext = redux_post_meta("pointfinderthemefmb_options", $post_id, "webbupointfinder_page_titlebarcustomsubtext");
		        $_page_titlebarcustomheight = redux_post_meta("pointfinderthemefmb_options", $post_id, "webbupointfinder_page_titlebarcustomheight");
		        $_page_titlebarcustombg = redux_post_meta("pointfinderthemefmb_options", $post_id, "webbupointfinder_page_titlebarcustombg");
		        $_page_titlebarcustomtext_bgcolor = redux_post_meta("pointfinderthemefmb_options", $post_id, "webbupointfinder_page_titlebarcustomtext_bgcolor");
		        $_page_titlebarcustomtext_bgcolorop = redux_post_meta("pointfinderthemefmb_options", $post_id, "webbupointfinder_page_titlebarcustomtext_bgcolorop");
		        $setup43_themecustomizer_headerbar_shadowopt = redux_post_meta("pointfinderthemefmb_options", $post_id, "webbupointfinder_page_shadowopt");

		        if (PFControlEmptyArr($_page_titlebarcustombg)) {
		        	$_page_titlebarcustombg_repeat = $_page_titlebarcustombg['background-repeat'];
			        $_page_titlebarcustombg_color = $_page_titlebarcustombg['background-color'];
			        $_page_titlebarcustombg_fixed = $_page_titlebarcustombg['background-attachment'];
			        $_page_titlebarcustombg_image = $_page_titlebarcustombg['background-image'];
		        }else{
		        	$_page_titlebarcustombg_repeat = '';
			        $_page_titlebarcustombg_color = '';
			        $_page_titlebarcustombg_fixed = '';
			        $_page_titlebarcustombg_image = '';
		        }


		        $_page_custom_css = $_text_custom_css = ' style="';

		        if ($_page_titlebarcustomheight != '') {
		            $_page_custom_css .= 'height:'.$_page_titlebarcustomheight.'px;';
		        }

		        if ($_page_titlebarcustombg_image != '') {
		            $_page_custom_css .= 'background-image:url('.$_page_titlebarcustombg_image.');';
		        }
		        if ($_page_titlebarcustombg_repeat != '') {
		            $_page_custom_css .= 'background-repeat: '.$_page_titlebarcustombg_repeat.';';
		        }
		        if ($_page_titlebarcustombg_color != '') {
		            $_page_custom_css .= 'background-color:'.$_page_titlebarcustombg_color.';';
		        }
		        if ($_page_titlebarcustombg_fixed != '') {
		            $_page_custom_css .= 'background-attachment :'.$_page_titlebarcustombg_fixed.';';
		        }
		        if ($_page_titlebarcustomtext_color != '') {
		            $_page_custom_css .= 'color:'.$_page_titlebarcustomtext_color.';';
		            $_text_custom_css .= 'color:'.$_page_titlebarcustomtext_color.';';

		        }

		        if ($_page_titlebarcustomtext_bgcolor != '') {
		        	$color_output = pointfinderhex2rgbex($_page_titlebarcustomtext_bgcolor,$_page_titlebarcustomtext_bgcolorop);
		        	$_text_custom_css .= 'background-color: '.$color_output['rgb'].';background-color: '.$color_output['rgba'].'; ';
		        	$_text_custom_css_main = ' pfwbg';
		    		$_text_custom_css_sub = ' pfwbg';
		        }else{
		        	$_text_custom_css_main = '';
		        	$_text_custom_css_sub = '';
		        }

		        $_page_custom_css .= '';
		        $_text_custom_css .= '"';



		        $pagetitletext = '<div class="main-titlebar-text'.$_text_custom_css_main.'"'.$_text_custom_css.'>';

		        if($_page_titlebarareatext == 1){

		            if ($_page_titlebarcustomtext != '') {
		                $pagetitletext .= $_page_titlebarcustomtext;
		            }else{
		            	$pagetitletext .= get_the_title();
		            }


		            if ($_page_titlebarcustomsubtext != '') {
		                $pagesubtext = '<div class="sub-titlebar-text'.$_text_custom_css_sub.'"'.$_text_custom_css.'>'.$_page_titlebarcustomsubtext.'</div>';
		            }else{
		            	$pagesubtext = '';
		            }
		        }else{
		        	$pagetitletext .= get_the_title();
		        	$pagesubtext = '';
		        }

		        if($post_title != ''){$pagetitletext .= ' / '.$post_title;}
		        $pagetitletext .= '</div>';


	        	echo '
	        	<section'.$_page_custom_css.'" class="pf-page-header">
	        	';
	        	if ($setup43_themecustomizer_headerbar_shadowopt != 0) {
					echo '<div class="pfheaderbarshadow'.$setup43_themecustomizer_headerbar_shadowopt.'"></div>';
				}
	        	echo '
	        		<div class="pf-container">
	        			<div class="pf-row">
	        				<div class="col-lg-12">
	        					<div class="pf-titlebar-texts">'.$pagetitletext.$pagesubtext.'</div>
	        					<div class="pf-breadcrumbs clearfix">'.pf_the_breadcrumb(
	        						array(
								        '_text_custom_css' => $_text_custom_css,
								        '_text_custom_css_main' => $_text_custom_css_main
										)
	        						).'</div>
	        				</div>
	        			</div>
	        		</div>
	        	</section>';

		    }
		}
	}
	if (!function_exists('PFGetDefaultPageHeader')) {
		function PFGetDefaultPageHeader($params = array()){
			global $wp;
			$defaults = array(
		        'author_id' => '',
		        'agent_id' => '',
		        'taxname' => '',
		        'taxnamebr' => '',
		        'taxinfo' => '',
		        'itemname' => '',
		        'itemaddress' => '',
		        'pagename' => ''
		    );

			$params = array_merge($defaults, $params);

			$setup43_themecustomizer_titlebarcustomtext_bgcolor = PFSAIssetControl('setup43_themecustomizer_titlebarcustomtext_bgcolor','','');
			$setup43_themecustomizer_titlebarcustomtext_bgcolorop = PFSAIssetControl('setup43_themecustomizer_titlebarcustomtext_bgcolorop','','');

			$setup43_themecustomizer_headerbar_shadowopt = PFSAIssetControl('setup43_themecustomizer_headerbar_shadowopt','',0);

		 	$_text_custom_css =' style="';

		    if ($setup43_themecustomizer_titlebarcustomtext_bgcolor != '') {
		    	$color_output = pointfinderhex2rgbex($setup43_themecustomizer_titlebarcustomtext_bgcolor,$setup43_themecustomizer_titlebarcustomtext_bgcolorop);
		    	$_text_custom_css .= 'background-color: '.$color_output['rgb'].';background-color: '.$color_output['rgba'].'; ';
		    	$_text_custom_css_main = ' pfwbg';
		    	$_text_custom_css_sub = ' pfwbg';
		    }else{
		    	$_text_custom_css_main = '';
		    	$_text_custom_css_sub = '';
		    }

		    $_text_custom_css .= '"';

		    $titletext = '';
		    if(empty($params['taxname'])){
			    if (is_author()) {
			    	$user = get_user_by('id', $params['author_id']);
			    	$titletext = $user->nickname;
			    }elseif(is_search()){
			    	if (!empty($_GET['s'])) {
			    		$titletext = sprintf(esc_html__( 'Search Results for %s', 'pointfinder' ),$_GET['s']);
			    	}else{
			    		$titletext = esc_html__( 'Search Results', 'pointfinder' );
			    	}

				}elseif(is_category()){
					$categ = get_category_by_path(esc_url(home_url(add_query_arg(array(), $wp->request))),false);

					if (empty($categ)) {
						$categ = esc_sql(get_query_var('cat'));
						$titletext = get_cat_name( $categ );
					}else{
						if (isset($categ)) {
							$titletext = $categ->name;
						}
					}


				}elseif (is_tag()) {
					$titletext = single_tag_title('',false);
				}else{
			    	$titletext = get_the_title();
			    }
			}else{
				$titletext = $params['taxname'];
				$titlesubtext = $params['taxinfo'];
			}

			if (function_exists('is_woocommerce')) {
		    	if (is_woocommerce()) {
		    		ob_start();
		    		woocommerce_page_title();
		    		$titletext = ob_get_contents();
		    		ob_end_clean();
		    	}
		    }

			if(empty($params['itemname'])){

				/* If page is member dashboard. */
			    $setup4_membersettings_dashboard = PFSAIssetControl('setup4_membersettings_dashboard','','');
			    if (get_the_id() == $setup4_membersettings_dashboard) {

			    	if(isset($_GET['ua']) && $_GET['ua']!=''){
						$ua_action = esc_attr($_GET['ua']);
					}else{
						$ua_action = '';
					}

			    	switch ($ua_action) {
						case 'profile':
							$titletext = PFSAIssetControl('setup29_dashboard_contents_profile_page_title','','Profile');
						break;
						case 'favorites':
							$titletext = PFSAIssetControl('setup29_dashboard_contents_favs_page_title','','My Favorites');
						break;
						case 'newitem':
							$titletext = PFSAIssetControl('setup29_dashboard_contents_submit_page_title','','Submit New Item');
						break;
						case 'edititem':
							$titletext = PFSAIssetControl('setup29_dashboard_contents_submit_page_titlee','','Edit Item');
						break;
						case 'reviews':
							$titletext = PFSAIssetControl('setup29_dashboard_contents_rev_page_title','','My Reviews');
						break;
						case 'myitems':
							$titletext = PFSAIssetControl('setup29_dashboard_contents_my_page_title','','My Items');
						break;
						case 'renewplan':
							$titletext = esc_html__("Renew Current Plan","pointfinder" );
						break;
						case 'purchaseplan':
							$titletext = esc_html__("Purchase New Plan","pointfinder");
						break;
						case 'upgradeplan':
							$titletext = esc_html__("Upgrade Plan","pointfinder" );
						break;
						case 'invoices':
							$titletext = PFSAIssetControl('setup29_dashboard_contents_inv_page_title','','My Invoices');
						break;
						default:
							$titletext = esc_html__('Not Found!','pointfinder');
						break;

					}



					$titletext = get_the_title().' / '.$titletext;
			    }

				echo '
				<section class="pf-defaultpage-header">';
					if ($setup43_themecustomizer_headerbar_shadowopt != 0) {
						echo '<div class="pfheaderbarshadow'.$setup43_themecustomizer_headerbar_shadowopt.'"></div>';
					}
				echo '
					<div class="pf-container">
						<div class="pf-row">
							<div class="col-lg-12">';


							echo '
								<div class="pf-titlebar-texts">
								<h1 class="main-titlebar-text'.$_text_custom_css_main.'"'.$_text_custom_css.'>'.$titletext.'</h1>
								';
								if (!empty($titlesubtext)) {
									echo '<div class="sub-titlebar-text'.$_text_custom_css_sub.'"'.$_text_custom_css.'>'.$titlesubtext.'</div>';
								}
								echo '
								</div>
								';

								if(empty($params['taxname'])){
									echo '<div class="pf-breadcrumbs clearfix'.$_text_custom_css_sub.'"'.$_text_custom_css.'>'.pf_the_breadcrumb(array('_text_custom_css' => $_text_custom_css,'_text_custom_css_main' => $_text_custom_css_main)).'</div>';
								}else{
									echo '<div class="pf-breadcrumbs clearfix'.$_text_custom_css_sub.'"'.$_text_custom_css.'>'.pf_the_breadcrumb(array('taxname'=>$params['taxnamebr'],'_text_custom_css' => $_text_custom_css,'_text_custom_css_main' => $_text_custom_css_main)).'</div>';
								}

								echo '
							</div>
						</div>
					</div>
				</section>';
			}else{
				$setup42_itempagedetails_hideaddress = PFSAIssetControl('setup42_itempagedetails_hideaddress','','1');
				echo '
				<section role="itempageheader" class="pf-itempage-header">';
					if ($setup43_themecustomizer_headerbar_shadowopt != 0) {
						echo '<div class="pfheaderbarshadow'.$setup43_themecustomizer_headerbar_shadowopt.'"></div>';
					}
				echo '
					<div class="pf-container">
						<div class="pf-row">
							<div class="col-lg-12">
								<div class="pf-titlebar-texts">
									<div class="main-titlebar-text'.$_text_custom_css_main.'"'.$_text_custom_css.'>'.$params['itemname'].'</div>
									';
									if($setup42_itempagedetails_hideaddress == 1){
									echo '<div class="sub-titlebar-text'.$_text_custom_css_sub.'"'.$_text_custom_css.'>'.$params['itemaddress'].'</div>';
									}
									echo '
								</div>
								<div class="pf-breadcrumbs clearfix hidden-print'.$_text_custom_css_sub.'"'.$_text_custom_css.'>'.pf_the_breadcrumb(array('_text_custom_css' => $_text_custom_css,'_text_custom_css_main' => $_text_custom_css_main)).'</div>
							</div>
						</div>
					</div>
				</section>';
			}
		}
	}
	if (!function_exists('PFGetDefaultCatPageHeader')) {
		function PFGetDefaultCatPageHeader($params = array()){
			global $wp;
			$defaults = array(
		        'taxname' => '',
		        'taxnamebr' => '',
		        'taxinfo' => '',
		        'pf_cat_textcolor' => '',
				'pf_cat_backcolor' => '',
				'pf_cat_bgimg' => '',
				'pf_cat_bgrepeat' => '',
				'pf_cat_bgsize' => '',
				'pf_cat_bgpos' => '',
				'pf_cat_headerheight' => '',
				'pf_cat_bgattachment' => 'scroll'
		    );

			$params = array_merge($defaults, $params);

			$setup43_themecustomizer_headerbar_shadowopt = PFSAIssetControl('setup43_themecustomizer_headerbar_shadowopt','',0);

		 	$_text_custom_css = $_text_custom_css1 = ' style="';

		    if ($params['pf_cat_backcolor'] != '') {
		    	$color_output = pointfinderhex2rgbex($params['pf_cat_backcolor'],'0.7');
		    	$_text_custom_css .= 'background-color: '.$params['pf_cat_backcolor'].'; background-color:'.$color_output['rgba'].'; ';
		    	$_text_custom_css1 .= 'background-color: '.$params['pf_cat_backcolor'].'; background-color:'.$color_output['rgba'].';';
		    	$_text_custom_css_main = ' pfwbg';
		    	$_text_custom_css_sub = ' pfwbg';
		    }else{
		    	$_text_custom_css_main = '';
		    	$_text_custom_css_sub = '';
		    }

		    if (isset($params['pf_cat_bgimg'][0])) {
		    	$bgimage_defined = wp_get_attachment_url($params['pf_cat_bgimg'][0]);

		    	$_text_custom_css .= 'background: url('.$bgimage_defined.');';
		    	$_text_custom_css .= 'background-position: '.$params['pf_cat_bgpos'].';';
		    	$_text_custom_css .= 'background-size: '.$params['pf_cat_bgsize'].';';
		    	$_text_custom_css .= 'background-repeat: '.$params['pf_cat_bgrepeat'].';';
		    	$_text_custom_css .= 'background-attachment: '.$params['pf_cat_bgattachment'].';';
		    	$_text_custom_css .= 'height: '.$params['pf_cat_headerheight'].'px;';
		    	$_text_custom_css .= 'color: '.$params['pf_cat_textcolor'].';';
		    }

		    $_text_custom_css .= '"';
		    $_text_custom_css1 .= '"';


		    $titletext = '';
		    if(empty($params['taxname'])){
			    if(is_category() || is_archive()){
					$categ = get_category_by_path(esc_url(home_url(add_query_arg(array(), $wp->request))),false);

					if (empty($categ)) {
						$categ = esc_sql(get_query_var('cat'));
						$titletext = get_cat_name( $categ );
					}else{
						if (isset($categ)) {
							$titletext = $categ->name;
						}
					}
				}elseif (is_tag()) {
					$titletext = single_tag_title('',false);
				}else{
			    	$titletext = get_the_title();
			    }
			}else{
				$titletext = $params['taxname'];
				$titlesubtext = $params['taxinfo'];
			}



			echo '
			<section class="pf-defaultpage-header"'.$_text_custom_css.'>';
				if ($setup43_themecustomizer_headerbar_shadowopt != 0) {
					echo '<div class="pfheaderbarshadow'.$setup43_themecustomizer_headerbar_shadowopt.'"></div>';
				}
			echo '
				<div class="pf-container" style="height:100%">
					<div class="pf-row" style="height:100%">
						<div class="col-lg-12" style="height:100%">';


						echo '
							<div class="pf-titlebar-texts">
							<h1 class="main-titlebar-text'.$_text_custom_css_main.'"'.$_text_custom_css1.'>'.$titletext.'</h1>
							';
							if (!empty($titlesubtext)) {
								echo '<div class="sub-titlebar-text'.$_text_custom_css_sub.'"'.$_text_custom_css1.'>'.$titlesubtext.'</div>';
							}
							echo '
							</div>
							';

							if(empty($params['taxname'])){
								echo '<div class="pf-breadcrumbs clearfix'.$_text_custom_css_sub.'"'.$_text_custom_css1.'>'.pf_the_breadcrumb(array('_text_custom_css' => $_text_custom_css1,'_text_custom_css_main' => $_text_custom_css_main)).'</div>';
							}else{
								echo '<div class="pf-breadcrumbs clearfix'.$_text_custom_css_sub.'"'.$_text_custom_css1.'>'.pf_the_breadcrumb(array('taxname'=>$params['taxnamebr'],'_text_custom_css' => $_text_custom_css1,'_text_custom_css_main' => $_text_custom_css_main)).'</div>';
							}

							echo '
						</div>
					</div>
				</div>
			</section>';

		}
	}
	if (!function_exists('PFPageNotFound')) {
		function PFPageNotFound(){
		  ?>
			<section role="main">
		        <div class="pf-container">
		            <div class="pf-row">
		                <div class="col-lg-12">

		                    <form method="get" class="form-search" action="<?php echo esc_url(home_url("/")); ?>" data-ajax="false">
		                    <div class="pf-notfound-page animated flipInY">
		                        <h3><?php esc_html_e( 'Sorry!', 'pointfinder' ); ?></h3>
		                        <h4><?php esc_html_e( 'Nothing found...', 'pointfinder' ); ?></h4><br>
		                        <p class="text-lightblue-2"><?php esc_html_e( 'You better try to search', 'pointfinder' ); ?>:</p>
		                        <div class="row">
		                            <div class="pfadmdad input-group col-sm-4 col-sm-offset-4">
		                                <i class="pfadmicon-glyph-386"></i>
		                                <input type="text" name="s" class="form-control" onclick="this.value='';"  onfocus="if(this.value==''){this.value=''};" onblur="if(this.value==''){this.value=''};" value="<?php esc_html_e( 'Search', 'pointfinder' ); ?>">
		                                <span class="input-group-btn">
		                                    <button onc class="btn btn-success" type="submit"><?php esc_html_e( 'Search', 'pointfinder' ); ?></button>
		                                  </span>
		                            </div>
		                        </div><br>
		                        <a class="btn btn-primary btn-sm" href="<?php echo esc_url(home_url("/")); ?>"><i class="pfadmicon-glyph-857"></i><?php esc_html_e( 'Return Home', 'pointfinder' ); ?></a>
		                    </div>
		                    </form>

		                </div>
		            </div>
		        </div>
		    </section>
		  <?php
		}
	}
	if (!function_exists('PFLoginWidget')) {
		function PFLoginWidget(){
		  ?>
			<section role="main">
		        <div class="pf-container">
		            <div class="pf-row">
		                <div class="col-lg-12">

		                    <div class="pf-notlogin-page animated flipInY">
		                        <h3><?php esc_html_e( 'Sorry!', 'pointfinder' ); ?></h3>
		                        <h4><?php esc_html_e( 'You must login to see this page.', 'pointfinder' ); ?></h4><br>
		                    </div>
		                    <script>
					       (function($) {
				  			"use strict";
					       	$(function(){
					       		$.pfOpenLogin('open','login');
					       	})
					       })(jQuery);
					       </script>

		                </div>
		            </div>
		        </div>
		    </section>
		  <?php
		}
	}
/**
*End:Page Functions
**/


/**
*Start: Breadcrumbs - Moved removable
**/
	if (!function_exists('pf_the_breadcrumb')) {
		function pf_the_breadcrumb($params = array()) {
			global $wp;
			$defaults = array(
		        'taxname' => '',
		        '_text_custom_css' => '',
		        '_text_custom_css_main' => ''
		    );

			$params = array_merge($defaults, $params);

			$_text_custom_css_main = (!empty($params['_text_custom_css_main']))?$params['_text_custom_css_main']:'';
			$_text_custom_css = (!empty($params['_text_custom_css']))?$params['_text_custom_css']:'';



			$mpost_id = get_the_id();

			$setup3_modulessetup_breadcrumbs = PFSAIssetControl('setup3_modulessetup_breadcrumbs','','1');
			if ($setup3_modulessetup_breadcrumbs == 1) {

				$act_ok = 1;
				if (function_exists('is_bbpress')) {
					if(!is_bbpress()){ $act_ok = 1;}else{$act_ok = 0;}
				}
				if($act_ok == 1){
					$output = '';
			        $output .= '<ul id="pfcrumbs" class="'.trim($_text_custom_css_main).'" '.trim($_text_custom_css).'>';

			        if (!is_home()) {
			                $output .= '<li><a href="';
			                $output .= esc_url(home_url("/"));
			                $output .= '">';
			                $output .= esc_html__('Home','pointfinder');
			                $output .= "</a></li>";
			                if (is_category() || is_single()) {


			                        $post_type = get_post_type();
									$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');

									switch ($post_type) {
										case $setup3_pointposttype_pt1:
											$categories = get_the_terms($mpost_id,'pointfinderltypes');
											$output2 = '';

											if($categories){
												$cat_count = count($categories);
												$i = 1;
												foreach($categories as $category) {
													if (!empty($category->parent)) {

														if ($i == 1) {
															$term_parent_name = get_term_by('id', $category->parent, 'pointfinderltypes','ARRAY_A');
															$get_termname = $term_parent_name['name'].' / '.$category->name;
															$output2 .= '<li>';
															$output2 .= '<a href="'.get_term_link( $category->parent, 'pointfinderltypes' ).'" title="' . esc_attr( sprintf( esc_html__( "View all posts in %s","pointfinder" ), $term_parent_name['name']) ) . '">'.$term_parent_name['name'].'</a>';
															$output2 .= '</li>';
															$i = $i + 1;
														}
													}

													$output2 .= '<li>';
													$output2 .= '<a href="'.get_term_link( $category->term_id,'pointfinderltypes' ).'" title="' . esc_attr( sprintf( esc_html__( "View all posts in %s","pointfinder" ), $category->name ) ) . '">'.$category->name.'</a>';
													$output2 .= '</li>';
												}
											$output .= trim($output2);
											}
											break;

										case 'post':

											$list_cats = get_category_by_path(esc_url(home_url(add_query_arg(array(), $wp->request))),false);
											$ci = 0;
											if (isset($list_cats)) {
												$output .= '<li>'.$list_cats->name.'</li>';
											}

											break;
										default:
											$list_cats = get_the_category();
											$ci = 0;
											foreach ($list_cats as $list_cat) {
												if($ci < 2){
													$output .= '<li>'.$list_cat->name.'</li>';
												}
												$ci++;
											}

									}

			                        if (is_single()) {
			                                $output .= "<li>";
			                                $output .= '<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>';
			                                $output .= '</li>';
			                        }
			                } elseif (is_page()) {

									$parents = get_post_ancestors($mpost_id);
									$parents = array_reverse($parents);
									if (!empty($parents)) {
										foreach ($parents as $key => $value) {
											$output .= '<li>';
			                        		$output .= '<a href="'.get_permalink($value).'" title="'.get_the_title($value).'">'.get_the_title($value).'</a>';
			                        		$output .= '</li>';
										}
									}

			                        $output .= '<li>';
			                        $output .= '<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>';
			                        $output .= '</li>';
			                } elseif (is_tax()) {
			                	$output .= "<li>";
	                            $output .= $params['taxname'];
	                            $output .= '</li>';
			                }elseif (is_tag()) {
			                	$output .= "<li>";
			                	$output .= single_tag_title('',false);
			                	$output .= '</li>';
			                }


			        }elseif (is_day()) {$output .="<li>".esc_html__('Archive for','pointfinder')." "; get_the_time('F jS, Y'); $output .='</li>';
			        }elseif (is_month()) {$output .="<li>".esc_html__('Archive for','pointfinder')." "; get_the_time('F, Y'); $output .='</li>';
			        }elseif (is_year()) {$output .="<li>".esc_html__('Archive for','pointfinder')." "; get_the_time('Y'); $output .='</li>';
			        }elseif (is_author()) {$output .="<li>".esc_html__('Author Archive','pointfinder').""; $output .='</li>';
			        }elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {$output .= "<li>".esc_html__('Blog Archives','pointfinder').""; $output .='</li>';
			        }elseif (is_search()) {$output .="<li>".esc_html__('Search Results','pointfinder').""; $output .='</li>';}
			        $output .= '</ul>';

			        return $output;
			    }
			}
		}
	}
/**
*End: Breadcrumbs
**/



/**
*Start: WPML
**/
	if (!function_exists('PF_current_language')) {
		function PF_current_language(){
			return apply_filters( 'wpml_current_language', NULL );
		}
	}

	if (!function_exists('PF_default_language')) {
		function PF_default_language(){
		    return apply_filters( 'wpml_default_language', NULL );
		}
	}

	if (!function_exists('PFLangCategoryID_ld')) {
		function PFLangCategoryID_ld($id,$lang,$post_type_name){
			$translated_id = apply_filters('wpml_object_id',$id,$post_type_name,true,$lang);
			if (!empty($translated_id)) {
				return $translated_id;
			}else{
				return $id;
			}
		}
	}
/**
*End: WPML
**/

if (!function_exists('pointfinder_getCurrencySymbol')) {
	function pointfinder_getCurrencySymbol($currency){
		$locale = "";
		$http_accept_language = apply_filters( 'pointfinder_accept_language_filter', '');
		if (isset($http_accept_language)) {
			$locale = Locale::acceptFromHttp(sanitize_text_field($http_accept_language));
		}

		if (empty($locale)) {$locale = 'en_US';}

	    $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

	    $withCurrency = $formatter->formatCurrency(0, $currency);
	    $formatter->setPattern(str_replace('¤', '', $formatter->getPattern()));
	    $withoutCurrency = $formatter->formatCurrency(0, $currency);

	    return str_replace($withoutCurrency, '', $withCurrency);
	}
}


if (!function_exists('pointfinder_menucolumn_get')) {
	function pointfinder_menucolumn_get(){
	$post_item_icon_status = PFASSIssetControl('general_postitembutton_iconstatus','','1');
	$post_item_button_status = PFASSIssetControl('general_postitembutton_status','','0');
	$setup4_membersettings_dashboard = absint(PFSAIssetControl('setup4_membersettings_dashboard','',''));
	$setup4_membersettings_dashboard_link = esc_url(get_permalink($setup4_membersettings_dashboard));
	$pfmenu_perout = PFPermalinkCheck();
?>
	<div class="col-lg-9 col-md-9" id="pfmenucol1">
		<div class="pf-menu-container">
		
			<nav id="pf-primary-nav" class="pf-primary-navclass pf-nav-dropdown clearfix">
				<ul class="pf-nav-dropdown pfnavmenu pf-topnavmenu">
					<?php pointfinder_navigation_menu();?>
					<?php if ($post_item_button_status == 1):
						$post_new_item_text_status = PFASSIssetControl('general_postitembutton_htext','','0');
						$post_new_item_text = PFASSIssetControl('general_postitembutton_buttontext','','Post New Listing');
						if ($post_new_item_text_status == '1') {
							$class_for_pnibutton = 'notext ';
						}else{
							$class_for_pnibutton = '';
						}
						?>
						<li id="pfpostitemlink" class="<?php echo esc_attr($class_for_pnibutton);?>main-menu-item menu-item-even menu-item-depth-0 menu-item menu-item-type-post_type menu-item-object-page current-menu-ancestor current-menu-parent current_page_parent current_page_ancestor menu-item-has-children">
						<?php if (is_user_logged_in()): ?>
							<a class="menu-link main-menu-link" href="<?php echo esc_url($setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=newitem');?>">
						<?php else: ?>
							<a class="menu-link main-menu-link" href="#">
						<?php endif ?>
						<?php if ($post_item_icon_status == '1'): ?>
							<i class="<?php echo PFASSIssetControl('pnewiconname','','fas fa-plus');?>"></i>
							<?php if ($post_new_item_text_status == '0'): ?>
								<?php echo sanitize_text_field( $post_new_item_text );?>
							<?php endif ?>
						<?php else: ?>
							<?php if ($post_new_item_text_status == '0'): ?>
								<?php echo sanitize_text_field( $post_new_item_text );?>
							<?php endif ?>
						<?php endif ?>
					</a>
					</li>
					<?php endif ?>
				</ul>
			</nav>	

		</div>
	</div>
<?php	
}
}
if (!function_exists('pointfinder_logocolumn_get')) {
	function pointfinder_logocolumn_get(){
?>
	<div class="col-lg-3 col-md-3">
		<a class="pf-logo-container" href="<?php echo esc_url(home_url("/"));?>"></a>
	</div>
<?php	
	}
}
?>