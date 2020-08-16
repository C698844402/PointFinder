<?php 
/**********************************************************************************************************************************
*
* Item Detail Page - Sharebar Content
*
* Author: Webbu
***********************************************************************************************************************************/


if (trait_exists('PointFinderListingSharebar')) {
  return;
}

trait PointFinderListingSharebar
{

    public function pointfinder_sharebar_function()
    {
		global $claim_list_permission;

		$setup42_itempagedetails_share_bar = $this->PFSAIssetControl('setup42_itempagedetails_share_bar','','1');
		$setup42_itempagedetails_report_status = $this->PFSAIssetControl('setup42_itempagedetails_report_status','','1');
		$setup42_itempagedetails_claim_status = $this->PFSAIssetControl('setup42_itempagedetails_claim_status','','0');
		$setup4_membersettings_favorites = $this->PFSAIssetControl('setup4_membersettings_favorites','','1');

		$favtitle_text = esc_html__('Add to Favorites','pointfindercoreelements');
		$fav_check = 'false';
		$faviconname = 'far fa-heart';
		$post_id = get_the_id();

		if (is_user_logged_in()) {
			$user_favorites_arr = get_user_meta( get_current_user_id(), 'user_favorites', true );
			if (!empty($user_favorites_arr)) {
				$user_favorites_arr = json_decode($user_favorites_arr,true);
			}else{
				$user_favorites_arr = array();
			}
		}

		if (is_user_logged_in() && count($user_favorites_arr)>0) {
			if (in_array($post_id, $user_favorites_arr)) {
				$fav_check = 'true';
				$faviconname = 'fas fa-heart';
				$favtitle_text = esc_html__('Remove from Favorites','pointfindercoreelements');
			}
		}


		if($setup42_itempagedetails_share_bar == 1){
			$item_title = get_the_title();
			$item_permalink = get_the_permalink();
			$item_thumnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
			$item_thumnail2 = $item_thumnail[0];

			$st10_f_s = $this->PFSAIssetControl('st10_f_s','',1);
			$st10_t_s = $this->PFSAIssetControl('st10_t_s','',1);
			$st10_l_s = $this->PFSAIssetControl('st10_l_s','',1);
			$st10_p_s = $this->PFSAIssetControl('st10_p_s','',1);
			$st10_v_s = $this->PFSAIssetControl('st10_v_s','',1);
			$st10_w_s = $this->PFSAIssetControl('st10_w_s','',1);

			$share_bar_count = 0;
			if ($st10_f_s==1) {$share_bar_count++;}
			if ($st10_t_s==1) {$share_bar_count++;}
			if ($st10_l_s==1) {$share_bar_count++;}
			if ($st10_p_s==1) {$share_bar_count++;}
			if ($st10_v_s==1) {$share_bar_count++;}
			if ($st10_w_s==1) {$share_bar_count++;}

			echo '<div class="pf-itempage-sharebar clearfix hidden-print pf-itempagedetail-element golden-forms">';

		?>
				<ul class="pf-sharebar-icons pfsharebarct<?php echo intval($share_bar_count);?> clearfix">
					<?php if($st10_f_s == 1){?>
					<li><a href="http://www.facebook.com/share.php?u=<?php echo esc_url($item_permalink);?>&title=<?php echo esc_html( $item_title );?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=640,height=480')" class="pfsharebar-fb info-tip2" ><span class="fab fa-facebook-f"></span></a></li>
					<?php }if($st10_t_s == 1){?>
					<li><a href="http://twitter.com/share?text=<?php echo esc_html( $item_title );?>&url=<?php echo esc_url($item_permalink);?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=640,height=480')" class="pfsharebar-twitter info-tip2" ><span class="fab fa-twitter"></span></a></li>
					<?php }if($st10_l_s == 1){?>
					<li><a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url($item_permalink);?>&title=<?php echo esc_html( $item_title );?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=640,height=480')" class="pfsharebar-linkedin info-tip2" ><span class="fab fa-linkedin-in"></span></a></li>
					<?php }if($st10_p_s == 1){?>
					<li><a href="http://pinterest.com/pin/create/bookmarklet/?media=<?php echo esc_url($item_thumnail2);?>&url=<?php echo esc_url($item_permalink);?>&description=<?php echo esc_html( $item_title );?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=640,height=480')" class="pfsharebar-pinterest info-tip2" ><span class="fab fa-pinterest-p"></span></a></li>
					<?php }if($st10_v_s == 1){?>
					<li><a href="http://vk.com/share.php?url=<?php echo esc_url($item_permalink);?>&image=<?php echo esc_url($item_thumnail2);?>&title=<?php echo esc_html( $item_title );?>&description=<?php echo esc_html( $item_title );?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=640,height=480')" class="pfsharebar-vk info-tip2" ><span class="fab fa-vk"></span></a></li>
					<?php }if($st10_w_s == 1){?>
					<li><a href="https://api.whatsapp.com/send?text=<?php echo esc_url($item_permalink);?>" onclick="location.href = 'https://api.whatsapp.com/send?text=<?php echo esc_url($item_permalink);?>'"  class="pfsharebar-whatsapp info-tip2" ><span class="fab fa-whatsapp"></span></a></li>
					<?php }?>
				</ul>
		<?php
			echo '<ul class="pf-sharebar-others">';

			if ($setup4_membersettings_favorites == 1) {
				echo '
				<li>
					<a class="pf-favorites-link" data-pf-num="'.$post_id.'" data-pf-active="'.$fav_check.'" data-pf-item="true" title="'.$favtitle_text.'">
						<i class="'.$faviconname.'"></i>
						<span id="itempage-pffav-text">'.$favtitle_text.'</span>
					</a>
				</li>
				';
			}


			if($setup42_itempagedetails_report_status == 1){
				echo '
				<li>
					<a class="pf-report-link" data-pf-num="'.$post_id.'">
						<i class="fas fa-exclamation-triangle"></i>
						<span>'.esc_html__('Report','pointfindercoreelements').'</span>
					</a>
				</li>
				';
			}

			$listing_verified = get_post_meta( $post_id, 'webbupointfinder_item_verified', true );
			if($setup42_itempagedetails_claim_status == 1 && $claim_list_permission == 1 && $listing_verified != 1){
				echo '
				<li>
					<a id="pfclaimitem" class="pf-claim-link" data-pf-num="'.$post_id.'">
						<i class="fas fa-file-invoice"></i>
						<span>'.esc_html__('Claim','pointfindercoreelements').'</span>
					</a>
				</li>
				';
			}

			echo '
			<li><a onclick="javascript:window.print();"><i class="fas fa-print"></i> '.esc_html__('Print','pointfindercoreelements').'</a></li>
			</ul>';
			echo '</div>';
		}

    }
}