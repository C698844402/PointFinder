<?php


if (trait_exists('PointFinderListingMetabox')) {
  return;
}


/**
 * Listing Post type Metaboxes
 */
trait PointFinderListingMetabox
{
    /**
    *Start: Change order record to new user if exist
    **/
	    public function pointfinder_correctowneroforder($post_ID, $post_after, $post_before)
	    {
	        if ($post_after->post_author != $post_before->post_author) {
	            global $wpdb;
	            $order_post_id = $wpdb->get_var($wpdb->prepare(
	                    "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %d",
	                    'pointfinder_order_itemid',
	                    $post_ID
	                ));

	            $post_images = get_post_meta($post_ID, 'webbupointfinder_item_images', false);


	            if (is_array($post_images)) {
			    	foreach ($post_images as $post_image) {
			    		$results = $wpdb->update($wpdb->posts,array('post_author'=>$post_after->post_author),array('ID'=>$post_image));
			    	}
			    }

				$post_header_img = get_post_meta( $post_ID, 'webbupointfinder_item_headerimage', false );

			    if (is_array($post_header_img)) {
			    	foreach ($post_header_img as $header_img) {
			    		if (isset($header_img['id'])) {
			    			$results = $wpdb->update($wpdb->posts,array('post_author'=>$post_after->post_author),array('ID'=>$header_img['id']));
			    		}
			    		
			    	}
			    }

				$post_files = get_post_meta( $post_ID, 'webbupointfinder_item_files', false );
				
			    if (is_array($post_files)) {
			    	foreach ($post_files as $post_file) {
			    		$results = $wpdb->update($wpdb->posts,array('post_author'=>$post_after->post_author),array('ID'=>$post_file));
			    	}
			    }

	            $post_thumb = get_post_thumbnail_id($post_ID);
	            $results = $wpdb->update($wpdb->posts, array('post_author'=>$post_after->post_author), array('ID'=>$post_thumb));

	            if (!empty($order_post_id)) {
		            $order_post_owner = $wpdb->get_var($wpdb->prepare(
	                    "SELECT post_author FROM $wpdb->posts WHERE ID = %d",
	                    $order_post_id
	                ));

	                if ($order_post_owner != $post_after->post_author) {
	                    $results = $wpdb->update($wpdb->posts, array('post_author'=>$post_after->post_author), array('ID'=>$order_post_id));
	                    update_post_meta($order_post_id, 'pointfinder_order_userid', $post_after->post_author);

	                    /* - Creating record for process system. */
	                    $this->PFCreateProcessRecord(
		                    array(
		                        'user_id' => $post_after->post_author,
		                        'item_post_id' => $post_ID,
		                        'processname' => esc_html__('Item post author changed by ADMIN', 'pointfindercoreelements')
		                    )
		                );
	                }

	                if (class_exists('Pointfinderstripesubscriptions')) {
						//Claim Form
						$user_info = get_userdata( $post_after->post_author );

						$message_reply = $this->pointfinder_mailsystem_mailsender(
			                array(
			                  'toemail' => $user_info->user_email,
			                  'predefined' => 'authorchangemail',
			                  'data' => array(
			                    'user'=> $post_after->post_author,
			                    'item'=> $post_ID,
			                    ),
			                )
			              );
					}
	            }else{

	            	//Claim Form
					$user_info = get_userdata( $post_after->post_author );

					$message_reply = $this->pointfinder_mailsystem_mailsender(
		                array(
		                  'toemail' => $user_info->user_email,
		                  'predefined' => 'authorchangemail',
		                  'data' => array(
		                    'user'=> $post_after->post_author,
		                    'item'=> $post_ID,
		                    ),
		                )
		              );
	            }
	        }
	    }
    /**
    *End: Change order record to new user if exist
    **/

    /**
	*Start: Change, Status change selection
	**/
		public function pointfinder_add_altered_submit_box($post_type, $post = '') {

			if ($post_type == $this->post_type_name) {

				if (!user_can($post->post_author,'activate_plugins')) {

					$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

					remove_meta_box( 'submitdiv', $this->post_type_name, 'side' );

					if ($setup4_membersettings_paymentsystem == 2) {
						add_meta_box(
							'pointfinder_orders_status',
							esc_html__( 'User Plan Status', 'pointfindercoreelements' ),
							array($this,'pointfinder_morders_meta_box_orderstatus'),
							$this->post_type_name,
							'side',
							'high'
						);
					} else {
						add_meta_box(
							'pointfinder_orders_status',
							esc_html__( 'Order Status', 'pointfindercoreelements' ),
							array($this,'pointfinder_orders_meta_box_orderstatus'),
							$this->post_type_name,
							'side',
							'high'
						);
					}

					add_meta_box(
						'submitdiv',
						esc_html__( 'Status Actions','pointfindercoreelements'),
						array($this,'PF_Modified_post_submit_meta_box'),
						$this->post_type_name,
						'side',
						'high'
					);
				}
			}
			
		}

	/**
	*End: Change, Status change selection
	**/

	/**
	*Start : Plan Info Content (For membership)
	**/
		public function pointfinder_morders_meta_box_orderstatus( $post ) {
			$user_id = $post->post_author;
			$userdata = get_user_by('id',$user_id);
			$membership_user_package_id = get_user_meta( $user_id, 'membership_user_package_id', true );
			$membership_user_package = get_user_meta( $user_id, 'membership_user_package', true );

			$membership_user_activeorder = get_user_meta( $user_id, 'membership_user_activeorder', true );
			$expire_date = get_post_meta( $membership_user_activeorder, 'pointfinder_order_expiredate', true );
			$ex_text = '';
			if(!empty($expire_date)){
				if($this->pf_membership_expire_check($expire_date) == false){
				    $prderinfo_statusorder = '<span class="pforders-orderdetails-lblcompleted">'.esc_html__('ACTIVE UNTIL: ','pointfindercoreelements').$this->PFU_DateformatS($expire_date).'</span>';
				}else{
					$prderinfo_statusorder = '<span class="pforders-orderdetails-lblcancel">'.esc_html__('EXPIRED','pointfindercoreelements').'</span>';
				}
			}else{
				$ex_text = '<br/>'.__("Probably user's order removed by admin. You should rollback this action or create new membership plan for this user.","pointfindercoreelements").
				'<br/><br/>'.esc_html__("You can create new plan by using user's profile page.",'pointfindercoreelements').''.
				'<br/><br/><a href="'.get_edit_user_link($user_id).'" class="button button-primary button-normal">'.esc_html__("CREATE NEW PLAN",'pointfindercoreelements').'</a>';
			}

			echo '<ul class="pforders-orderdetails-ul">';
			if (empty($prderinfo_statusorder)) {
				echo '<li>';
				esc_html_e( 'PLAN STATUS : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">'.esc_html__('This user has no plan.','pointfindercoreelements').'<br/>'.$ex_text.'</div>';
				echo '</li> ';
			}else{

				echo '<li>';
				esc_html_e( 'PLAN INFO : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">'.$membership_user_package.'</div>';
				echo '</li> ';


				echo '<li>';
				esc_html_e( 'PLAN STATUS : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_statusorder.'</div>';
				echo '</li> ';


				echo '<li>';
				esc_html_e( 'USER : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext"><a href="'.get_edit_user_link($user_id).'" target="_blank" title="'.esc_html__('Click for user details','pointfindercoreelements').'">'.$user_id.' - '.$userdata->nickname.'</a></div>';
				echo '</li> ';
			}
			echo '</ul>';
		}
		
	/**
	*End : Plan Info Content
	**/

	/**
	*Start : Order Info Content
	**/
		public function pointfinder_orders_meta_box_orderstatus( $post ) {

			global $wpdb;
			$order_post_id = $wpdb->get_var( $wpdb->prepare(
				"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s and meta_value = %d",
				'pointfinder_order_itemid',
				$post->ID
			) );
			$prderinfo_itemid = esc_attr(get_post_meta( $order_post_id , 'pointfinder_order_itemid', true ));
			$prderinfo_user = esc_attr(get_post_meta( $order_post_id , 'pointfinder_order_userid', true ));
			$order_post_status = get_post_status($order_post_id);

			if($order_post_status == 'completed'){
			    $prderinfo_statusorder = '<span class="pforders-orderdetails-lblcompleted">'.esc_html__('PAYMENT COMPLETED','pointfindercoreelements').'</span>';
			}elseif($order_post_status == 'pendingpayment'){
				$prderinfo_statusorder = '<span class="pforders-orderdetails-lblpending">'.esc_html__('PENDING PAYMENT','pointfindercoreelements').'</span>';
			}elseif($order_post_status == 'pfcancelled'){
				$prderinfo_statusorder = '<span class="pforders-orderdetails-lblcancel">'.esc_html__('CANCELLED','pointfindercoreelements').'</span>';
			}elseif($order_post_status == 'pfsuspended'){
				$prderinfo_statusorder = '<span class="pforders-orderdetails-lblpending">'.esc_html__('SUSPENDED','pointfindercoreelements').'</span>';
			}

			echo '<ul class="pforders-orderdetails-ul">';
			if (empty($prderinfo_statusorder)) {
				echo '<li>';
				esc_html_e( 'STATUS : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">
				'.esc_html__('This item has no order info. If you claimed this item to another user please click to create order button for create a new order for this user.','pointfindercoreelements').'<br/>
				<a class="button button-primary button-large" id="createorder">'.esc_html__('CREATE ORDER','pointfindercoreelements').'</a>
				</div>';
				echo '</li> ';
			}else{
				echo '<li>';
				esc_html_e( 'ORDER ID : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext"><a href="'.get_edit_post_link($order_post_id).'" target="_blank" title="'.esc_html__('Click for order details','pointfindercoreelements').'"><strong>'.get_the_title($order_post_id).'</strong></a></div>';
				echo '</li> ';

				echo '<li>';
				esc_html_e( 'ORDER STATUS : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext">'.$prderinfo_statusorder.'</div>';
				echo '</li> ';

				$userdata = get_user_by('id',$prderinfo_user);
				echo '<li>';
				esc_html_e( 'USER : ', 'pointfindercoreelements' );
				echo '<div class="pforders-orderdetails-lbltext"><a href="'.get_edit_user_link($prderinfo_user).'" target="_blank" title="'.esc_html__('Click for user details','pointfindercoreelements').'">'.$prderinfo_user.' - '.$userdata->nickname.'</a></div>';
				echo '</li> ';
			}
			echo '</ul>';

			if (empty($prderinfo_statusorder)) {
				echo '
					<script>
					(function($) {
	 				 "use strict";
	 				 $("#createorder").on("click",function(){
						$("#createorder").text("'.esc_html__('Please wait...','pointfindercoreelements').'");
						$("#createorder").attr("disabled", true);';
				echo "
						$.ajax({
				            type: 'POST',
				            dataType: 'json',
				            url: '".PFCOREELEMENTSURLINC.'pfajaxhandler.php'."',
				            data: {
				                'action': 'pfget_createorder',
				                'newauthor': ".$post->post_author.",
				                'itemid': ".$post->ID.",
				                'security': '".wp_create_nonce('pfget_createorder')."'
				            },
				            success:function(data){

				            	var obj = [];
								$.each(data, function(index, element) {
									obj[index] = element;
								});

								if(obj.process == true){
									window.location.reload();
								}

				            },
				            error: function (request, status, error) {},
				            complete: function(){
									$('#createorder').text('".esc_html__('Refreshing...','pointfindercoreelements')."');
				            },
				        });

						return false;
	 				 });


					})(jQuery);
					</script>
				";
			}
		}
	/**
	*End : Order Info Content
	**/


	/**
	*Start : Custom Publish Box
	**/
		public function PF_Modified_post_submit_meta_box($post, $args = array() ) {
			global $action;

			$post_type = $post->post_type;
			$post_type_object = get_post_type_object($post_type);

			$can_publish = current_user_can($post_type_object->cap->publish_posts);
			?>
			<div class="submitbox pointfinder" id="submitpost">
				<div id="minor-publishing">


					<div style="display:none;">
					<?php submit_button( esc_html__( 'Save' ,'pointfindercoreelements'), 'button', 'save' ); ?>
					</div>


					<div class="clear"></div>
				</div><!-- #minor-publishing-actions -->

				<div id="misc-publishing-actions">

					<div class="misc-pub-section misc-pub-post-status"><label for="post_status"><?php esc_html_e('Status:','pointfindercoreelements') ?></label>
						<span id="post-status-display">
						<?php
						switch ( $post->post_status ) {
							case 'publish':
								esc_html_e('Published','pointfindercoreelements');
								break;
							case 'pendingpayment':
								esc_html_e('Pending Payment','pointfindercoreelements');
								break;
							case 'pendingapproval':
								esc_html_e('Pending Approval','pointfindercoreelements');
								break;
							case 'rejected':
								esc_html_e('Rejected','pointfindercoreelements');
								break;
							case 'pfonoff':
								esc_html_e('Deactived by User','pointfindercoreelements');
								break;
						}
						?>
						</span>
						<?php if ( ('publish' == $post->post_status || 'pendingpayment' == $post->post_status || 'pendingapproval' == $post->post_status) && $can_publish ) { ?>
						<a href="#post_status" <?php if ( 'private' == $post->post_status ) { ?>style="display:none;" <?php } ?>class="edit-post-status hide-if-no-js"><span aria-hidden="true"><?php esc_html_e( 'Edit','pointfindercoreelements' ); ?></span> <span class="screen-reader-text"><?php esc_html_e( 'Edit status' ,'pointfindercoreelements'); ?></span></a>

						<div id="post-status-select" class="hide-if-js">
							<input type="hidden" name="hidden_post_status" id="hidden_post_status" value="<?php echo esc_attr( ('pendingapproval' == $post->post_status ) ? 'pendingapproval' : $post->post_status); ?>" />
							<select name='post_status' id='post_status'>

							<option<?php selected( $post->post_status, 'publish' ); ?> value='publish'><?php esc_html_e('Published','pointfindercoreelements') ?></option>
							<option<?php selected( $post->post_status, 'pendingpayment' ); ?> value='pendingpayment'><?php esc_html_e('Pending Payment','pointfindercoreelements') ?></option>
							<option<?php selected( $post->post_status, 'pendingapproval' ); ?> value='pendingapproval'><?php esc_html_e('Pending Approval','pointfindercoreelements') ?></option>
							<option<?php selected( $post->post_status, 'rejected' ); ?> value='rejected'><?php esc_html_e('Rejected','pointfindercoreelements') ?></option>

							</select>
							 <a href="#post_status" class="save-post-status hide-if-no-js button"><?php esc_html_e('OK','pointfindercoreelements'); ?></a>
							 <a href="#post_status" class="cancel-post-status hide-if-no-js button-cancel"><?php esc_html_e('Cancel','pointfindercoreelements'); ?></a>
						</div>

						<?php } ?>
					</div><!-- .misc-pub-section -->

					<div class="misc-pub-section misc-pub-visibility" id="visibility">
						<?php esc_html_e('Visibility:','pointfindercoreelements'); ?> <span id="post-visibility-display"><?php

						if ( 'private' == $post->post_status ) {
							$post->post_password = '';
							$visibility = 'private';
							$visibility_trans = esc_html__('Private','pointfindercoreelements');
						} elseif ( !empty( $post->post_password ) ) {
							$visibility = 'password';
							$visibility_trans = esc_html__('Password protected','pointfindercoreelements');
						} elseif ( $post_type == 'post' && is_sticky( $post->ID ) ) {
							$visibility = 'public';
							$visibility_trans = esc_html__('Public, Sticky','pointfindercoreelements');
						} else {
							$visibility = 'public';
							$visibility_trans = esc_html__('Public','pointfindercoreelements');
						}

						echo esc_html( $visibility_trans ); ?></span>
						<?php if ( $can_publish ) { ?>
						<a href="#visibility" class="edit-visibility hide-if-no-js"><span aria-hidden="true"><?php esc_html_e( 'Edit' ,'pointfindercoreelements'); ?></span> <span class="screen-reader-text"><?php esc_html_e( 'Edit visibility' ,'pointfindercoreelements'); ?></span></a>

						<div id="post-visibility-select" class="hide-if-js">
							<input type="hidden" name="hidden_post_password" id="hidden-post-password" value="<?php echo esc_attr($post->post_password); ?>" />
							<input type="hidden" name="hidden_post_visibility" id="hidden-post-visibility" value="<?php echo esc_attr( $visibility ); ?>" />
							<input type="radio" name="visibility" id="visibility-radio-public" value="public" <?php checked( $visibility, 'public' ); ?> /> <label for="visibility-radio-public" class="selectit"><?php esc_html_e('Public','pointfindercoreelements'); ?></label><br />
							<input type="radio" name="visibility" id="visibility-radio-password" value="password" <?php checked( $visibility, 'password' ); ?> /> <label for="visibility-radio-password" class="selectit"><?php esc_html_e('Password protected','pointfindercoreelements'); ?></label><br />
							<span id="password-span"><label for="post_password"><?php esc_html_e('Password:','pointfindercoreelements'); ?></label> <input type="text" name="post_password" id="post_password" value="<?php echo esc_attr($post->post_password); ?>"  maxlength="20" /><br /></span>
							<input type="radio" name="visibility" id="visibility-radio-private" value="private" <?php checked( $visibility, 'private' ); ?> /> <label for="visibility-radio-private" class="selectit"><?php esc_html_e('Private','pointfindercoreelements'); ?></label><br />

							<p>
							 <a href="#visibility" class="save-post-visibility hide-if-no-js button"><?php esc_html_e('OK','pointfindercoreelements'); ?></a>
							 <a href="#visibility" class="cancel-post-visibility hide-if-no-js button-cancel"><?php esc_html_e('Cancel','pointfindercoreelements'); ?></a>
							</p>
						</div>
						<?php } ?>

					</div><!-- .misc-pub-section -->

					<?php
					/* translators: Publish box date format, see http://php.net/date */
					$datef = 'M j, Y @ G:i';
					if ( 0 != $post->ID ) {
						if ( 'future' == $post->post_status ) { // scheduled for publishing at a future date
							$stamp = esc_attr__('Scheduled for: <b>%1$s</b>','pointfindercoreelements');
						} else if ( 'publish' == $post->post_status || 'private' == $post->post_status ) { // already published
							$stamp = esc_attr__('Published on: <b>%1$s</b>','pointfindercoreelements');
						} else if ( '0000-00-00 00:00:00' == $post->post_date_gmt ) { // draft, 1 or more saves, no date specified
							$stamp = esc_attr__('Publish <b>immediately</b>','pointfindercoreelements');
						} else if ( time() < strtotime( $post->post_date_gmt . ' +0000' ) ) { // draft, 1 or more saves, future date specified
							$stamp = esc_attr__('Schedule for: <b>%1$s</b>','pointfindercoreelements');
						} else { // draft, 1 or more saves, date specified
							$stamp = esc_attr__('Publish on: <b>%1$s</b>','pointfindercoreelements');
						}
						$date = date_i18n( $datef, strtotime( $post->post_date ) );
					} else { // draft (no saves, and thus no date specified)
						$stamp = esc_attr__('Publish <b>immediately</b>','pointfindercoreelements');
						$date = date_i18n( $datef, strtotime( current_time('mysql') ) );
					}

					if ( ! empty( $args['args']['revisions_count'] ) ){
						$revisions_to_keep = wp_revisions_to_keep( $post );
					?>


					<div class="misc-pub-section misc-pub-revisions">
						<?php
							if ( $revisions_to_keep > 0 && $revisions_to_keep <= $args['args']['revisions_count'] ) {
								echo '<span title="' . esc_attr( sprintf( esc_html__( 'Your site is configured to keep only the last %s revisions.','pointfindercoreelements'),
									number_format_i18n( $revisions_to_keep ) ) ) . '">';
								printf( esc_html__( 'Revisions: %s','pointfindercoreelements' ), '<b>' . number_format_i18n( $args['args']['revisions_count'] ) . '+</b>' ,'pointfindercoreelements');
								echo '</span>';
							} else {
								printf( esc_html__( 'Revisions: %s','pointfindercoreelements' ), '<b>' . number_format_i18n( $args['args']['revisions_count'] ) . '</b>' ,'pointfindercoreelements');
							}
						?>
						<a class="hide-if-no-js" href="<?php echo esc_url( get_edit_post_link( $args['args']['revision_id'] ) ); ?>"><span aria-hidden="true"><?php esc_html__( 'Browse', 'pointfindercoreelements' ); ?></span> <span class="screen-reader-text"><?php esc_html_e( 'Browse revisions' ,'pointfindercoreelements'); ?></span></a>
					</div>
					<?php };

					if ( $can_publish){ // Contributors don't get to choose the date of publish ?>
					<div class="misc-pub-section curtime misc-pub-curtime" style="display:none;">
						<span id="timestamp">
						<?php printf($stamp, $date); ?></span>
						<a href="#edit_timestamp" class="edit-timestamp hide-if-no-js"><span aria-hidden="true"><?php esc_html_e( 'Edit' ,'pointfindercoreelements'); ?></span> <span class="screen-reader-text"><?php esc_html_e( 'Edit date and time' ,'pointfindercoreelements'); ?></span></a>
						<div id="timestampdiv" class="hide-if-js"><?php touch_time(($action == 'edit'), 1); ?></div>
					</div><?php // /misc-pub-section ?>
					<?php }; ?>

					<?php
					/**
					 * Fires after the post time/date setting in the Publish meta box.
					 *
					 * @since 2.9.0
					 */
					do_action( 'post_submitbox_misc_actions' );
					?>
				</div>
			</div>
			<div class="clear"></div>


			<div id="major-publishing-actions">
				<?php
				/**
				 * Fires at the beginning of the publishing actions section of the Publish meta box.
				 *
				 * @since 2.7.0
				 */
				do_action( 'post_submitbox_start' );
				?>
				<div id="delete-action">
				<?php
				if ( current_user_can( "delete_post", $post->ID ) ) {
					if ( !EMPTY_TRASH_DAYS )
						$delete_text = esc_html__('Delete Permanently','pointfindercoreelements');
					else
						$delete_text = esc_html__('Move to Trash','pointfindercoreelements');
					?>
				<a class="submitdelete deletion" href="<?php echo get_delete_post_link($post->ID); ?>"><?php echo $delete_text; ?></a><?php
				} ?>
			</div>

			<div id="publishing-action">
				<span class="spinner"></span>

				<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_html_e('Update','pointfindercoreelements') ?>" />
				<input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="<?php esc_html_e('Update','pointfindercoreelements') ?>" />

			</div>
			<div class="clear"></div>

			</div>

			<?php
		}
		
	/**
	*End : Custom Publish Box
	**/


    /**
    *Start: Change Author Box
    **/
    	public function pointfinderex_author_metabox_remove(){
    		global $post_type;
    		if ($post_type == $this->post_type_name) {
    			remove_meta_box( 'authordiv', 'post', 'normal' );
    		}
		}

		public function pointfinderex_author_metabox_move(){
			
			global $post_type;

			if ($post_type == $this->post_type_name) {
				global $post;

				echo '<div class="pointfinder-current-auth">'.esc_html__("Current Author:","pointfindercoreelements").' '.get_the_author_meta('login',$post->post_author).'</div>';
				echo '<div id="author" class="misc-pub-section" style="border-top-style:solid; border-top-width:1px; border-top-color:#EEEEEE; border-bottom-width:0px;">'.esc_html__('Change Author: ','pointfindercoreelements').' ';
				echo '<input type="hidden" name="post_author_override" id="post_author_override" />';
				echo '<br/><small>'.esc_html__('Important: Author change will be effect order records. Please be carefull.','pointfindercoreelements').'</small>';
				echo '</div>';
			}
		}
    /**
    *End: Change Author Box
    **/

    /**
	*Start : Item Reviewer Messages
	**/
		public function pf_reviewer_message_metabox($post_type) {
			
			if ($post_type == $this->post_type_name) {
				if ($this->PFSAIssetControl('setup4_submitpage_messagetorev','','1') == 1) {
					add_meta_box(
						'pf_reviewer_message_metabox_id',
						esc_html__( 'Reviewer Message', 'pointfindercoreelements' ),
						array($this,'pf_reviewer_message_metabox_cb'),
						$this->post_type_name
					);
				}
			}
		}

		public function pf_reviewer_message_metabox_cb( $post ) {
			$old_mesrev = get_post_meta($post->ID, 'webbupointfinder_items_mesrev', true);
			$old_mesrev = json_decode($old_mesrev,true);
			if (!empty($old_mesrev)) {
				$old_mesrev = array_reverse($old_mesrev);
				foreach ($old_mesrev as $old_mesrev_single) {

					if (!empty($old_mesrev_single['message'])) {
						echo '<div class="pfdateshow">';
						echo esc_attr($old_mesrev_single['date']);
						echo '</div><div class="pfmsgshow">';
						echo $this->pointfinder_Utf8_ansi($old_mesrev_single['message']);
						echo '</div>';
					}

				}
				echo '<small>'.esc_html__("These messages has been sent by This Item Owner","pointfindercoreelements").'</small>';

			}else{
				echo '<small>'.esc_html__("There is no message yet.","pointfindercoreelements").'</small>';
			}
		}

		private function pointfinder_Utf8_ansi($valor='') {

		    $utf8_ansi2 = array(
		    "u00c0" =>"À",
		    "u00c1" =>"Á",
		    "u00c2" =>"Â",
		    "u00c3" =>"Ã",
		    "u00c4" =>"Ä",
		    "u00c5" =>"Å",
		    "u00c6" =>"Æ",
		    "u00c7" =>"Ç",
		    "u00c8" =>"È",
		    "u00c9" =>"É",
		    "u00ca" =>"Ê",
		    "u00cb" =>"Ë",
		    "u00cc" =>"Ì",
		    "u00cd" =>"Í",
		    "u00ce" =>"Î",
		    "u00cf" =>"Ï",
		    "u00d1" =>"Ñ",
		    "u00d2" =>"Ò",
		    "u00d3" =>"Ó",
		    "u00d4" =>"Ô",
		    "u00d5" =>"Õ",
		    "u00d6" =>"Ö",
		    "u00d8" =>"Ø",
		    "u00d9" =>"Ù",
		    "u00da" =>"Ú",
		    "u00db" =>"Û",
		    "u00dc" =>"Ü",
		    "u00dd" =>"Ý",
		    "u00df" =>"ß",
		    "u00e0" =>"à",
		    "u00e1" =>"á",
		    "u00e2" =>"â",
		    "u00e3" =>"ã",
		    "u00e4" =>"ä",
		    "u00e5" =>"å",
		    "u00e6" =>"æ",
		    "u00e7" =>"ç",
		    "u00e8" =>"è",
		    "u00e9" =>"é",
		    "u00ea" =>"ê",
		    "u00eb" =>"ë",
		    "u00ec" =>"ì",
		    "u00ed" =>"í",
		    "u00ee" =>"î",
		    "u00ef" =>"ï",
		    "u00f0" =>"ð",
		    "u00f1" =>"ñ",
		    "u00f2" =>"ò",
		    "u00f3" =>"ó",
		    "u00f4" =>"ô",
		    "u00f5" =>"õ",
		    "u00f6" =>"ö",
		    "u00f8" =>"ø",
		    "u00f9" =>"ù",
		    "u00fa" =>"ú",
		    "u00fb" =>"û",
		    "u00fc" =>"ü",
		    "u00fd" =>"ý",
		    "u00ff" =>"ÿ");

		    return strtr($valor, $utf8_ansi2);
		}
	/**
	*End : Item Reviewer Messages
	**/


	/** 
	* listing metaboxes
	**/
	public function pointfinder_orders_add_meta_box_ex($post_type) {

		if ($post_type == $this->post_type_name) {
			$setup3_pointposttype_pt7s = $this->PFSAIssetControl('setup3_pointposttype_pt7s','','Listing Type');
			$setup3_pointposttype_pt6 = $this->PFSAIssetControl('setup3_pointposttype_pt6','','Features');
			$setup3_pointposttype_pt4_check = $this->PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
			$setup3_pointposttype_pt4 = $this->PFSAIssetControl('setup3_pointposttype_pt4','','Item Types');
			$setup3_pointposttype_pt6_check = $this->PFSAIssetControl('setup3_pointposttype_pt6_check','','1');
			$setup3_modulessetup_openinghours = $this->PFSAIssetControl('setup3_modulessetup_openinghours','','0');
			$setup3_pt14_check = $this->PFSAIssetControl('setup3_pt14_check','',0);
			$setup3_pt14s = $this->PFSAIssetControl('setup3_pt14s','','Condition');
			$eare_status = $this->PFSAIssetControl('eare_status','',1);


			remove_meta_box( 'pointfinderltypesdiv', $this->post_type_name, 'side' );
			remove_meta_box( 'pointfinderconditionsdiv', $this->post_type_name, 'side' );
			remove_meta_box( 'pointfinderfeaturesdiv', $this->post_type_name, 'side' );
			remove_meta_box( 'pointfinderitypesdiv', $this->post_type_name, 'side' );

			add_meta_box(
				'pointfinder_itemdetailcf_process_lt',
				$setup3_pointposttype_pt7s,
				array($this,'pointfinder_itemdetailcf_process_lt_function'),
				$this->post_type_name,
				'normal',
				'high'
			);

			add_meta_box(
				'pointfinder_itemdetailcf_process',
				esc_html__( 'Additional Details', 'pointfindercoreelements' ),
				array($this,'pointfinder_itemdetailcf_process_function'),
				$this->post_type_name,
				'normal',
				'high'
			);


			if ($setup3_pointposttype_pt6_check ) {
				add_meta_box(
					'pointfinder_itemdetailcf_process_fe',
					$setup3_pointposttype_pt6,
					array($this,'pointfinder_itemdetailcf_process_fe_function'),
					$this->post_type_name,
					'normal',
					'core'
				);
			}


			if ($setup3_modulessetup_openinghours == 1) {
				add_meta_box(
					'pointfinder_itemdetailoh_process_fe',
					esc_html__( 'Opening Hours', 'pointfindercoreelements' ).' <small>('.esc_html__('Leave blank to show closed','pointfindercoreelements' ).')</small>',
					array($this,'pointfinder_itemdetailoh_process_fe_function'),
					$this->post_type_name,
					'normal',
					'high'
				);
			}



			if ($setup3_pt14_check == 1) {
				add_meta_box(
					'pointfinder_itemdetailcf_process_co',
					$setup3_pt14s,
					array($this,'pointfinder_itemdetailcf_process_co_function'),
					$this->post_type_name,
					'side',
					'core'
				);
			}



			if ($setup3_pointposttype_pt4_check == 1) {
				add_meta_box(
					'pointfinder_itemdetailcf_process_it',
					$setup3_pointposttype_pt4,
					array($this,'pointfinder_itemdetailcf_process_it_function'),
					$this->post_type_name,
					'side',
					'core'
				);
			}

			if ($eare_status == 1) {
				add_meta_box(
					'pointfinder_eventdetail_process',
					esc_html__("Events","pointfindercoreelements"),
					array($this,'pointfinder_eventdetail_process_fe_function'),
					$this->post_type_name,
					'normal',
					'core'
				);
			}
		}
	}

	/**
	*Start : Listing Type
	**/
	public function pointfinder_itemdetailcf_process_lt_function( $post ) {

		/* Get admin panel defaults */
		$setup4_submitpage_listingtypes_title = $this->PFSAIssetControl('setup4_submitpage_listingtypes_title','','Listing Type');
		$setup4_submitpage_sublistingtypes_title = $this->PFSAIssetControl('setup4_submitpage_sublistingtypes_title','','Sub Listing Type');
		$setup4_submitpage_subsublistingtypes_title = $this->PFSAIssetControl('setup4_submitpage_subsublistingtypes_title','','Sub Sub Listing Type');

	    $st4_sp_med = $this->PFSAIssetControl('st4_sp_med','','1');
		$setup3_pointposttype_pt5_check = $this->PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
		$setup4_submitpage_locationtypes_check = $this->PFSAIssetControl('setup4_submitpage_locationtypes_check','','1');
		$setup3_pointposttype_pt4_check = $this->PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
		$setup4_submitpage_itemtypes_check = $this->PFSAIssetControl('setup4_submitpage_itemtypes_check','','1');
		$setup4_submitpage_imageupload = $this->PFSAIssetControl('setup4_submitpage_imageupload','','1');
		$stp4_fupl = $this->PFSAIssetControl("stp4_fupl","","0");


		/* WPML Check */
		if(class_exists('SitePress')) {$lang_custom = PF_current_language();}else{$lang_custom = '';}

		/* Get Limits */
		$cat_extra_opts = get_option('pointfinderltypes_covars');

		/* Get selected listing types */
		$item_level_value = 0;
	    $item_defaultvalue = ($post->ID != '') ? wp_get_post_terms($post->ID, 'pointfinderltypes', array("fields" => "ids")) : '' ;
		$item_defaultvalue_output = $sub_level = $sub_sub_level = $item_defaultvalue_output_orj = '';

		if (count($item_defaultvalue) > 1) {
			if (isset($item_defaultvalue[0])) {
				$item_defaultvalue_output_orj = $item_defaultvalue[0];
				$find_top_parent = $this->pf_get_term_top_most_parent($item_defaultvalue[0],'pointfinderltypes');

				$ci=1;
				foreach ($item_defaultvalue as $value) {
					$sub_level .= $value;
					if ($ci < count($item_defaultvalue)) {
						$sub_level .= ',';
					}
					$ci++;
				}
				$item_defaultvalue_output = $find_top_parent['parent'];
				$item_level_value = (isset($find_top_parent['level']))?$find_top_parent['level']:0;
			}
		}else{
			if (isset($item_defaultvalue[0])) {
				$item_defaultvalue_output_orj = $item_defaultvalue[0];
				$find_top_parent = $this->pf_get_term_top_most_parent($item_defaultvalue[0],'pointfinderltypes');

				switch ($find_top_parent['level']) {
					case '1':
						$sub_level = $item_defaultvalue[0];
						break;

					case '2':
						$sub_sub_level = $item_defaultvalue[0];
						$sub_level = $this->pf_get_term_top_parent($item_defaultvalue[0],'pointfinderltypes');
						break;
				}


				$item_defaultvalue_output = $find_top_parent['parent'];
				$item_level_value = (isset($find_top_parent['level']))?$find_top_parent['level']:0;
			}
		}

	    echo '<div class="form-field">';
	    echo '<section>';

	    $listingtype_values = get_terms('pointfinderltypes',array('hide_empty'=>false,'parent'=> 0));

		echo '<input type="hidden" name="pfupload_listingtypes" id="pfupload_listingtypes" value="'.$item_defaultvalue_output.'"/>';

		echo '<div class="pflistingtype-selector-main-top clearfix" data-pfajaxurl="'.PFCOREELEMENTSURLINC.'pfajaxhandler.php'.'" data-pflang="'.$lang_custom.'" data-pfnonce="'.wp_create_nonce('pfget_listingtypelimits').'" data-pfnoncef="'.wp_create_nonce('pfget_featuresystem').'" data-pfid="'.$post->ID.'" data-pfplaceh="'.esc_html__("Search for a user","pointfindercoreelements").'">';

		$subcatsarray = "var pfsubcatselect = [";
		$multiplesarray = "var pfmultipleselect = [";
			foreach ($listingtype_values as $listingtype_value) {

				/* Multiple select & Subcat Select */
				$multiple_select = (isset($cat_extra_opts[$listingtype_value->term_id]['pf_multipleselect']))?$cat_extra_opts[$listingtype_value->term_id]['pf_multipleselect']:2;
				$subcat_select = (isset($cat_extra_opts[$listingtype_value->term_id]['pf_subcatselect']))?$cat_extra_opts[$listingtype_value->term_id]['pf_subcatselect']:2;

				if ($multiple_select == 1) {$multiplesarray .= $listingtype_value->term_id.',';}
				if ($subcat_select == 1) {$subcatsarray .= $listingtype_value->term_id.',';}


				echo '<div class="pflistingtype-selector-main">';
				echo '<input type="radio" name="radio" id="pfltypeselector'.$listingtype_value->term_id.'" class="pflistingtypeselector" value="'.$listingtype_value->term_id.'" '.checked( $item_defaultvalue_output, $listingtype_value->term_id,0).'/>';
				echo '<label for="pfltypeselector'.$listingtype_value->term_id.'">'.$listingtype_value->name.'</label>';
				echo '</div>';

			}
		echo '</div>';

		$subcatsarray .= "];";
		$multiplesarray .= "];";

		echo '<div style="margin-left:10px" class="pf-sub-listingtypes-container"></div>';

	    echo '</section>';

	    $script_output = '
	    (function($) {
	  	"use strict";';
	  	$script_output .= $subcatsarray.$multiplesarray;

	  	/* Start: Function for sub listing types */
	  	$script_output .= "

			$.pf_get_sublistingtypes = function(itemid,defaultv){
				if ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) != -1) {
					var multiple_ex = 1;
				}else{
					var multiple_ex = 0;
				}
				$.ajax({
			    	beforeSend:function(){
			    		$('#pointfinder_itemdetailcf_process_lt .inside').pfLoadingOverlay({action:'show',message: '".esc_html__('Loading fields...','pointfindercoreelements')."'});
			    	},
					url: '".PFCOREELEMENTSURLINC.'pfajaxhandler.php'."',
					type: 'POST',
					dataType: 'html',
					data: {
						action: 'pfget_listingtype',
						id: itemid,
						default: defaultv,
						sname: 'pfupload_sublistingtypes',
						stext: '".$setup4_submitpage_sublistingtypes_title."',
						stype: 'listingtypes',
						stax: 'pointfinderltypes',
						lang: '".$lang_custom."',
						multiple: multiple_ex,
						security: '".wp_create_nonce('pfget_listingtype')."'
					},
				}).success(function(obj) {
					$('.pf-sub-listingtypes-container').append('<div class=\'pfsublistingtypes\'>'+obj+'</div>');

						if (obj != '') {
						$('#pfupload_sublistingtypes').select2({
							placeholder: '".esc_html__('Please select','pointfindercoreelements')."',
							formatNoMatches:'".esc_html__('No match found','pointfindercoreelements')."',
							allowClear: true,
							minimumResultsForSearch: 10
						});";

						if (empty($sub_sub_level)) {
						$script_output .= " if ($('#pfupload_sublistingtypes').val() != 0 && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
							$.pf_get_subsublistingtypes($('#pfupload_sublistingtypes').val(),'');
						}";
						}
						$script_output .= "

						$('#pfupload_sublistingtypes').change(function(){
							if($('#pfupload_sublistingtypes').val() != 0){
								if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
									$('#pfupload_listingtypes').val($(this).val()).trigger('change');
								}else{
									$('#pfupload_listingtypes').val($(this).val());
								}
								if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
									$.pf_get_subsublistingtypes($(this).val(),'');
								}
							}else{
								if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
									$('#pfupload_listingtypes').val($('input.pflistingtypeselector:checked').val());
								}else{
									$('#pfupload_listingtypes').val($('input.pflistingtypeselector:checked').val()).trigger('change');
								}
							}
							$('.pfsubsublistingtypes').remove();
						});

						if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
							$('#pfupload_sublistingtypes').on('select2-removed', function(e) {
								$('#pfupload_listingtypes').val($('input.pflistingtypeselector:checked').val()).trigger('change');
							});
						}
					}

				}).complete(function(obj,obj2){

					if (obj.responseText != '') {
					if (defaultv != '') {
						if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
							";
							if ($item_level_value == 2 && $post->ID != '') {
								$script_output .= "$('#pfupload_listingtypes').val(defaultv);
								";
							}else{
								$script_output .= "$('#pfupload_listingtypes').val(defaultv).trigger('change');";
							}
							$script_output .= "
						}else{
							$('#pfupload_listingtypes').val(defaultv);
						}
					}else{

						if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
							";
							if ($item_level_value == 1 && $post->ID != '') {
								$script_output .= "$('#pfupload_listingtypes').val(itemid).trigger('change');";
							}elseif (empty($post->ID)) {
								$script_output .= "$('#pfupload_listingtypes').val(itemid);";
							}elseif (!empty($post->ID) && $item_level_value == 2) {
								$script_output .= "$('#pfupload_listingtypes').val(itemid);
								";
							}
							$script_output .= "
						}else{
							$('#pfupload_listingtypes').val(itemid);
						}
					}
					}
					setTimeout(function(){
						$('#pointfinder_itemdetailcf_process_lt .inside').pfLoadingOverlay({action:'hide'});
					},1000);
					";

					if (!empty($sub_sub_level)) {
						$script_output .= "
						if (".$sub_level." == $('#pfupload_sublistingtypes').val()) {
							$.pf_get_subsublistingtypes('".$sub_level."','".$sub_sub_level."');
						}
						";
					}
					$script_output .= "
				});
			}

			$.pf_get_subsublistingtypes = function(itemid,defaultv){
				$.ajax({
			    	beforeSend:function(){
			    		$('#pointfinder_itemdetailcf_process_lt .inside').pfLoadingOverlay({action:'show',message: '".esc_html__('Loading fields ...','pointfindercoreelements')."'});
			    	},
					url: '".PFCOREELEMENTSURLINC.'pfajaxhandler.php'."',
					type: 'POST',
					dataType: 'html',
					data: {
						action: 'pfget_listingtype',
						id: itemid,
						default: defaultv,
						sname: 'pfupload_subsublistingtypes',
						stext: '".$setup4_submitpage_subsublistingtypes_title."',
						stype: 'listingtypes',
						stax: 'pointfinderltypes',
						lang: '".$lang_custom."',
						security: '".wp_create_nonce('pfget_listingtype')."'
					},
				}).success(function(obj) {
					$('.pf-sub-listingtypes-container').append('<div class=\'pfsubsublistingtypes\'>'+obj+'</div>');
						if (obj != '') {
						$('#pfupload_subsublistingtypes').select2({
							placeholder: '".esc_html__('Please select','pointfindercoreelements')."',
							formatNoMatches:'".esc_html__('No match found','pointfindercoreelements')."',
							allowClear: true,
							minimumResultsForSearch: 10
						});

						$('#pfupload_subsublistingtypes').change(function(){
							if($('#pfupload_subsublistingtypes').val() != 0){

								if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
									$('#pfupload_listingtypes').val($(this).val()).trigger('change');
								}else{
									$('#pfupload_listingtypes').val($(this).val());
								}

							}else{

								if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
									$('#pfupload_listingtypes').val($('#pfupload_sublistingtypes').val()).trigger('change');
								}else{
									$('#pfupload_listingtypes').val($('#pfupload_sublistingtypes').val());
								}
							}
						});
						}
				}).complete(function(obj,obj2){
					if (obj.responseText != '') {
					if (defaultv != '') {
						if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
							$('#pfupload_listingtypes').val(defaultv).trigger('change');
						}else{
							$('#pfupload_listingtypes').val(defaultv);
						}
					}else{

						if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) == -1) && ($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) == -1)) {
							";
							if ($item_level_value == 2 && $post->ID != '') {
								$script_output .= "$('#pfupload_listingtypes').val(itemid).trigger('change');";
							}elseif (empty($post->ID)) {
								$script_output .= "$('#pfupload_listingtypes').val(itemid);";
							}
							$script_output .= "
						}else{
							$('#pfupload_listingtypes').val(itemid);
						}
					}
					}
					setTimeout(function(){
						$('#pointfinder_itemdetailcf_process_lt .inside').pfLoadingOverlay({action:'hide'});
					},1000);
				});
			}

		";
		/* End: Function for sub listing types */

		$script_output .= "$.pflimitarray = [";
			$pflimittext = '';
			/*Get Limits for Areas*/
			if ($st4_sp_med == 1) {
				$pflimittext .= "'pf_address_area'";
			}

			/*Get Limits for Image Area*/
			if($setup4_submitpage_imageupload == 1){
				if (!empty($pflimittext)) {$pflimittext .= ",";}
				$pflimittext .= "'pf_image_area'";
			}

			/*Get Limits for File Area*/
			if($stp4_fupl == 1){
				if (!empty($pflimittext)) {$pflimittext .= ",";}
				$pflimittext .= "'pf_file_area'";
			}

			$script_output .= $pflimittext;
			$script_output .= "];";


		$script_output .= "$(function(){";
			if ($post->ID != '') {
				$script_output .= "$.pf_get_checklimits('".$item_defaultvalue_output."',$.pflimitarray);";
				$script_output .= "$.pf_get_sublistingtypes($('#pfupload_listingtypes').val(),'".$sub_level."');";
				$script_output .= "
				if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfsubcatselect) != -1)) {

					$.pf_getmodules_now(".$item_defaultvalue_output.");

				}
				if (($.inArray(parseInt($('input.pflistingtypeselector:checked').val()),pfmultipleselect) != -1)) {
					$.pf_getmodules_now(".$item_defaultvalue_output.");
				}else{";
				if ($item_level_value == 0 && $post->ID != '') {
					$script_output .= "$.pf_getmodules_now($('#pfupload_listingtypes').val());";
				}elseif(empty($post->ID)){
					$script_output .= "$.pf_getmodules_now($('#pfupload_listingtypes').val());";
				}
				$script_output .= "
				}";

				if (empty($sub_sub_level) && !empty($sub_level)) {
					$script_output .= "$('#pfupload_listingtypes').val('".$sub_level."');";
				}
			}
		$script_output .= "});";


		$script_output .= "})(jQuery);";
		echo '</div>';
		wp_add_inline_script( 'pointfinder-customjs', $script_output);

	}
	/**
	*End : Listing Type
	**/



	/**
	*Start : Custom Fields Content
	**/
	public function pointfinder_itemdetailcf_process_function( $post ) {
		echo "<div class='golden-forms'>";
		echo "<section class='pfsubmit-inner pfsubmit-inner-customfields'></section>";
		echo "</div>";
	}
	/**
	*End : Custom Fields Content
	**/


	/**
	*Start : Features
	**/
	public function pointfinder_itemdetailcf_process_fe_function( $post ) {
		echo "<a class='pfitemdetailcheckall'>";
		echo esc_html__('Check All','pointfindercoreelements');
		echo "</a>";
		echo " / ";
		echo "<a class='pfitemdetailuncheckall'>";
		echo esc_html__('Uncheck All','pointfindercoreelements');
		echo "</a>";
		echo "<section class='pfsubmit-inner pfsubmit-inner-features'></section>";
	}
	/**
	*End : Features
	**/


	/**
	*Start : Event Details
	**/
	public function pointfinder_eventdetail_process_fe_function( $post ) {
		echo '<div class="eventdetails-output-container golden-forms"></div>';
	}
	/**
	*End : Event Details
	**/



	/**
	*Start : Conditions
	**/
	public function pointfinder_itemdetailcf_process_co_function( $post ) {
		echo '<section class="pfsubmit-inner pfsubmit-inner-sub-conditions"></section>';
	}
	/**
	*End : Conditions
	**/



	/**
	*Start : Item Types
	**/
	public function pointfinder_itemdetailcf_process_it_function( $post ) {
		echo '<section class="pfsubmit-inner pfsubmit-inner-sub-itype"></section>';
	}
	/**
	*End : Item Types
	**/



	/**
	*Start : Opening Hours
	**/
	public function pointfinder_itemdetailoh_process_fe_function( $post ) {
		echo '<section class="pfsubmit-inner pf-openinghours-div golden-forms openinghourstab-output-container"></section>';
	}
	/**
	*End : Opening Hours
	**/

	public function pointfinder_metaboxio_metaboxes( $meta_boxes ) {
		$pointfinder_center_lat = $this->PFSAIssetControl('setup42_searchpagemap_lat','','40.71275');
		$pointfinder_center_lng = $this->PFSAIssetControl('setup42_searchpagemap_lng','','-74.00597');
		$pointfinder_google_map_zoom = $this->PFSAIssetControl('setup42_searchpagemap_zoom','','6');
		$st4_sp_med = $this->PFSAIssetControl('st4_sp_med','','1');
		$stp4_fupl = $this->PFSAIssetControl('stp4_fupl','','0');
		$maplanguage = $this->PFSAIssetControl('setup5_mapsettings_maplanguage','','en');
		$setup5_map_key = $this->PFSAIssetControl('setup5_map_key','','');
		$prefix = 'webbupointfinder';
	    if ($st4_sp_med == 1) {
			$meta_boxes[] = array(
				'id' => 'pointfinder_map',
				'title' => esc_html__('Please Select Location','pointfindercoreelements'),
				'pages' => array( $this->post_type_name ),
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array(
					array(
						'id'            => "{$prefix}_items_address",
						'name'          => esc_html__('Address','pointfindercoreelements'),
						'type'          => 'text',
						'std'           => '',
						'placeholder'	=> esc_html__('Address','pointfindercoreelements'),
						),
					
					array(
						'id'            => "{$prefix}_items_location",
						'name'          => esc_html__('Location','pointfindercoreelements'),
						'type'          => 'map',
						'api_key'		=> ''.$setup5_map_key.'',
						'setup5_map_key'=> ''.$maplanguage.'',
						'std'           => ''.$pointfinder_center_lat.','.$pointfinder_center_lng.'',     
						'style'         => 'width: 100%; height: 400px',
						'address_field' => "{$prefix}_items_address",
						'placeholder'	=> esc_html__('Location','pointfindercoreelements'),                     
					),
				),
				
			);
		}

		if ($stp4_fupl == 1) {
			$meta_boxes[] = array(
				'title' => esc_html__('Attachment Upload','pointfindercoreelements'),
				'pages' => array( $this->post_type_name ),
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array(
						array(
							'name'             => __( 'Attachments', 'pointfindercoreelements' ),
							'id'               => "{$prefix}_item_files",
							'type'             => 'file_advanced',
							'max_file_uploads' => 100,
							'mime_type'        => '',
						),
				)
			);
		}

			
		$meta_boxes[] = array(
			'title' => esc_html__('Gallery','pointfindercoreelements'),
			'pages' => array( $this->post_type_name ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
					array(
						'name'             => esc_html__('Images','pointfindercoreelements'),
						'id'               => "{$prefix}_item_images",
						'max_file_uploads' => 50,
						'type'             => 'image_advanced',
					),
			)
		);
	    return $meta_boxes;
	}

	/**
	*Start : Save Metadata and other inputs
	**/
	public function pointfinder_item_save_meta_box_data( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! isset( $_POST['pfupload_listingtypes'] ) ) {
			return;
		}

		$pfupload_listingtypes = sanitize_text_field($_POST['pfupload_listingtypes']);

		if (!empty($pfupload_listingtypes)) {

				
			/*Listing Type*/
				if(isset($pfupload_listingtypes)){
					if($this->PFControlEmptyArr($pfupload_listingtypes)){
						$pftax_terms = $pfupload_listingtypes;
					}else if(!$this->PFControlEmptyArr($pfupload_listingtypes) && isset($pfupload_listingtypes)){
						$pftax_terms = $pfupload_listingtypes;
						if (strpos($pftax_terms, ",") != false) {
							$pftax_terms = pfstring2BasicArray($pftax_terms);
						}else{
							$pftax_terms = array($pfupload_listingtypes);
						}
					}
					wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderltypes');
				}

				
			
			/*Item Types*/
			if (isset($_POST['pfupload_itemtypes'])) {
				$pfupload_itemtypes = $_POST['pfupload_itemtypes'];
				
				if (is_array($pfupload_itemtypes)) {
					$pfupload_itemtypes = $this->PFCleanArrayAttr('PFCleanFilters',$pfupload_itemtypes);
				}else{
					$pfupload_itemtypes = sanitize_text_field($pfupload_itemtypes );
				}

				if($this->PFControlEmptyArr($pfupload_itemtypes)){
					$pftax_terms = $pfupload_itemtypes;
				}else{
					$pftax_terms = $pfupload_itemtypes;
					if (strpos($pftax_terms, ",") != false) {
						$pftax_terms = pfstring2BasicArray($pftax_terms);
					}else{
						$pftax_terms = array($pfupload_itemtypes);
					}
				}
				wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderitypes');
				
			}


			/*Conditions*/
			if (isset($_POST['pfupload_conditions'])) {
				$pfupload_conditions = sanitize_text_field($_POST['pfupload_conditions']);
				if (!empty($pfupload_conditions)) {
					wp_set_post_terms( $post_id, array($pfupload_conditions), 'pointfinderconditions');
				}else{
					wp_set_post_terms( $post_id, "", 'pointfinderconditions');
				}
			}
				

			/*Custom fields loop*/
				$pfstart = $this->PFCheckStatusofVar('setup1_slides');
				$setup1_slides = $this->PFSAIssetControl('setup1_slides','','');

				if($pfstart){

					foreach ($setup1_slides as &$value) {

			          $available_fields = array(1,2,3,4,5,7,8,9,14,15);
			          
			          if(in_array($value['select'], $available_fields)){

			           	if (isset($_POST[''.$value['url'].''])) {
				           	
				           	if (is_array($_POST[''.$value['url'].''])) {
				           		$post_value_url = $this->PFCleanArrayAttr('PFCleanFilters',$_POST[''.$value['url'].'']);
				           	}else{
				           		$post_value_url = sanitize_text_field($_POST[''.$value['url'].'']);
				           	}

							if(isset($post_value_url)){
								
								if ($value['select'] == 15) {
									if (!empty($post_value_url)) {
										$setup4_membersettings_dateformat = $this->PFSAIssetControl('setup4_membersettings_dateformat','','1');
										switch ($setup4_membersettings_dateformat) {
											case '1':$datetype = "d/m/Y";break;
											case '2':$datetype = "m/d/Y";break;
											case '3':$datetype = "Y/m/d";break;
											case '4':$datetype = "Y/d/m";break;
										}

										$pfvalue = date_parse_from_format($datetype, $post_value_url);
										$post_value_url = strtotime(date("Y-m-d", mktime(0, 0, 0, $pfvalue['month'], $pfvalue['day'], $pfvalue['year'])));
									}
								}

								if(!is_array($post_value_url)){ 
									update_post_meta($post_id, 'webbupointfinder_item_'.$value['url'], $post_value_url);	
								}else{
									$check_if_exists = get_post_meta( $post_id, 'webbupointfinder_item_'.$value['url'], true );
									if($check_if_exists != false){
										delete_post_meta($post_id, 'webbupointfinder_item_'.$value['url']);
									};
									
									foreach ($post_value_url as $val) {
										add_post_meta($post_id, 'webbupointfinder_item_'.$value['url'], $val);
									};

								};
							}else{
								delete_post_meta($post_id, 'webbupointfinder_item_'.$value['url']);
							};
						}else{
							delete_post_meta($post_id, 'webbupointfinder_item_'.$value['url']);
						};

			          };
			          
			        };
				};


			/*Features*/
				if (!empty($_POST['pffeature'])) {
					$feature_values = $this->PFCleanArrayAttr('PFCleanFilters',$_POST['pffeature']);
				
					if(isset($feature_values)){				
						if($this->PFControlEmptyArr($feature_values)){
							$pftax_terms = $feature_values;
						}else if(!$this->PFControlEmptyArr($feature_values) && isset($feature_values)){
							$pftax_terms = array($feature_values);
						}
						wp_set_post_terms( $post_id, $pftax_terms, 'pointfinderfeatures');
					}else{
						wp_set_post_terms( $post_id, '', 'pointfinderfeatures');
					}
				}else{
					wp_set_post_terms( $post_id, '', 'pointfinderfeatures');
				}
				

			/*Opening Hours*/
				$setup3_modulessetup_openinghours = $this->PFSAIssetControl('setup3_modulessetup_openinghours','','0');
				$setup3_modulessetup_openinghours_ex = $this->PFSAIssetControl('setup3_modulessetup_openinghours_ex','','1');
				if ($setup3_modulessetup_openinghours == 1 &&  $setup3_modulessetup_openinghours_ex == 2) {
					$i = 1;
					while ( $i <= 7) {
						if(isset($_POST['o'.$i.'_1']) && isset($_POST['o'.$i.'_2'])){
							update_post_meta($post_id, 'webbupointfinder_items_o_o'.$i, sanitize_text_field($_POST['o'.$i.'_1']).'-'.sanitize_text_field($_POST['o'.$i.'_2']));	
						}
						$i++;
					}
				}elseif ($setup3_modulessetup_openinghours == 1 &&  $setup3_modulessetup_openinghours_ex == 0) {
					$i = 1;
					while ( $i <= 7) {
						if(isset($_POST['o'.$i])){
							update_post_meta($post_id, 'webbupointfinder_items_o_o'.$i, sanitize_text_field($_POST['o'.$i]));	 
						}
						$i++;
					}
				}elseif ($setup3_modulessetup_openinghours == 1 &&  $setup3_modulessetup_openinghours_ex == 1) {
					$i = 1;
					while ( $i <= 1) {
						if(isset($_POST['o'.$i])){
							update_post_meta($post_id, 'webbupointfinder_items_o_o'.$i, sanitize_text_field($_POST['o'.$i]));	 
						}
						$i++;
					}
				}

			/** Start: Events **/

				$setup4_membersettings_dateformat = $this->PFSAIssetControl('setup4_membersettings_dateformat','','1');
				switch ($setup4_membersettings_dateformat) {
					case '1':$datetype = "d/m/Y";break;
					case '2':$datetype = "m/d/Y";break;
					case '3':$datetype = "Y/m/d";break;
					case '4':$datetype = "Y/d/m";break;
				}
			
				if (isset($_POST['field_startdate'])) {
					if (!empty($_POST['field_startdate'])) {

						$start_time_hour = 0;
						$start_time_min = 0;

						if (isset($_POST['field_starttime'])) {
							if (!empty($_POST['field_starttime'])) {
								$start_time = explode(':', $_POST['field_starttime']);
								if (isset($start_time[0])) {
									$start_time_hour = $start_time[0];
								}
								if (isset($start_time[1])) {
									$start_time_min = $start_time[1];
								}
							}
						}

						$field_startdate = date_parse_from_format($datetype, $_POST['field_startdate']);
						$_POST['field_startdate'] = strtotime(date("Y-m-d", mktime($start_time_hour, $start_time_min, 0, $field_startdate['month'], $field_startdate['day'], $field_startdate['year'])));

						update_post_meta($post_id, 'webbupointfinder_item_field_startdate', $_POST['field_startdate']);
					}else{
						update_post_meta($post_id, 'webbupointfinder_item_field_startdate', '');
					}
				}

				if (isset($_POST['field_enddate'])) {
					if (!empty($_POST['field_enddate'])) {

						$end_time_hour = 0;
						$end_time_min = 0;
						
						if (isset($_POST['field_endtime'])) {
							if (!empty($_POST['field_endtime'])) {
								$end_time = explode(':', $_POST['field_endtime']);
								if (isset($end_time[0])) {
									$end_time_hour = $end_time[0];
								}
								if (isset($end_time[1])) {
									$end_time_min = $end_time[1];
								}
							}
						}

						$field_enddate = date_parse_from_format($datetype, $_POST['field_enddate']);
						$_POST['field_enddate'] = strtotime(date("Y-m-d", mktime($end_time_hour, $end_time_min, 0, $field_enddate['month'], $field_enddate['day'], $field_enddate['year'])));

						update_post_meta($post_id, 'webbupointfinder_item_field_enddate', $_POST['field_enddate']);
					}else{
						update_post_meta($post_id, 'webbupointfinder_item_field_enddate', '');
					}
				}

				if (isset($_POST['field_starttime'])) {
					if (!empty($_POST['field_starttime'])) {
						update_post_meta($post_id, 'webbupointfinder_item_field_starttime', $_POST['field_starttime']);
					}else{
						update_post_meta($post_id, 'webbupointfinder_item_field_starttime', '');
					}
				}

				if (isset($_POST['field_endtime'])) {
					if (!empty($_POST['field_endtime'])) {
						update_post_meta($post_id, 'webbupointfinder_item_field_endtime', $_POST['field_endtime']);
					}else{
						update_post_meta($post_id, 'webbupointfinder_item_field_endtime', '');
					}
				}

			/** End: Events **/


		}
		
	}

	/**
	*End : Save Metadata and other inputs
**/
}
