<?php

if (trait_exists('PointFinderListingBackendReviewMetabox')) {
  return;
}

/**
 * Reviews metabox for listing edit
 */
trait PointFinderListingBackendReviewMetabox
{
    function pf_add_additional_review_metabox($post_type, $post = '')
    {
        if (!empty($post)) {
            if ($post_type == $this->post_type_name) {
                add_meta_box(
                        'pointfinder_reviews_status',
                        esc_html__('Reviews', 'pointfindercoreelements'),
                        array($this,'pointfinder_item_meta_box_reviews'),
                        $this->post_type_name,
                        'side',
                        'high'
                    );
            }
        }
    }

    function pointfinder_item_meta_box_reviews($post)
    {
        $reviews = $this->pfcalculate_total_review_ot($post->ID);

        $reviewtext = (is_array($reviews)) ? $reviews['totalresult'] : 0 ;
        echo esc_html__('Total Rating', 'pointfindercoreelements').' : '.$reviewtext.' / '.esc_html__('Total Reviews', 'pointfindercoreelements').' : '.$this->pfcalculate_total_rusers($post->ID);
        echo '<br/>';
        echo '    <a href="'.admin_url('edit.php?post_type=pointfinderreviews&itemnumber='.$post->ID).'" class="pf-seeallrevs">'.esc_html__("See all reviews", "pointfindercoreelements").'</a>';
    }

    function pointfinder_reviews_add_meta_box($post_type, $post = '') {
        if ($post_type == 'pointfinderreviews') {
            add_meta_box(
                'submitdiv',
                esc_html__( 'Status Actions','pointfindercoreelements'),
                array($this,'PF_Modified_review_submit_meta_box'),
                'pointfinderreviews',
                'side',
                'high'
            );
            add_meta_box(
                'pointfinder_reviews_info',
                esc_html__( 'REVIEW INFO', 'pointfindercoreelements' ),
                array($this,'pointfinder_reviews_meta_box_revinfo'),
                'pointfinderreviews',
                'side',
                'high'
            );

            add_meta_box(
                'pointfinder_reviews_details',
                esc_html__( 'REVIEW DETAILS', 'pointfindercoreelements' ),
                array($this,'pointfinder_reviews_meta_box_details'),
                'pointfinderreviews',
                'side',
                'core'
            );

        }
    }

    /**
    *Start : Review Info Content
    **/
        function pointfinder_reviews_meta_box_revinfo( $post ) {
            $itemid = esc_attr(get_post_meta( $post->ID, 'webbupointfinder_review_itemid', true ));
            $email = esc_attr(get_post_meta( $post->ID, 'webbupointfinder_review_email', true ));


            $output = '<ul>';
                $output .= '<li><span class="rev-cr-title">'.esc_html__('Item','pointfindercoreelements').' : </span><a href="'.get_edit_post_link($itemid).'" target="_blank">'.get_the_title($itemid).'('.$itemid.')</a></li>';
                $pf_review_user_id = get_post_meta($post->ID, 'webbupointfinder_review_userid',true);

                if (!empty($pf_review_user_id)) {
                    $userid = esc_attr(get_post_meta( $post->ID, 'webbupointfinder_review_userid', true ));
                    $output .= '<li><span class="rev-cr-title">'.esc_html__('Reviewer','pointfindercoreelements').' : </span><a href="'.get_edit_user_link($userid).'" target="_blank">'.get_the_title($post->ID).'</a></li>';
                }else{
                    $output .= '<li><span class="rev-cr-title">'.esc_html__('Reviewer','pointfindercoreelements').' : </span>'.get_the_title($post->ID).'</li>';
                }

                $output .= '<li><span class="rev-cr-title">'.esc_html__('Email','pointfindercoreelements').' : </span>'.$email.'</li>';

                $pf_review_flag = get_post_meta($post->ID, 'webbupointfinder_review_flag',true);

                $flagstatus = (!empty($pf_review_flag))? $pf_review_flag : '' ;
                $output .=  ($flagstatus == 1)? '<li style="background:red;padding:8px 10px;"><a href="'.admin_url('post.php?post='.$post->ID.'&action=edit&flag=0').'" style="color:white; font-weight:bold;">'.esc_html__('This review flagged for check. Click here for remove flag.','pointfindercoreelements').'</a></li>' : '';

            $output .= '</ul>';
            echo $output;

        }
    /**
    *End : Review Info Content
    **/

    /**
    *Start : Review Details
    **/
        function pointfinder_reviews_meta_box_details( $post ) {
            echo '<div class="pf-single-rev">'.esc_html__('Review Total','pointfindercoreelements').' : '.$this->pfcalculate_single_review($post->ID).'</div>';
            echo $this->pfget_reviews_peritem($post->ID);

        }
    /**
    *End : Review Details
    **/

    /**
    *Start : Custom Publish Box
    **/
        function PF_Modified_review_submit_meta_box($post, $args = array() ) {
            global $action;

            $post_type = $post->post_type;
            $post_type_object = get_post_type_object($post_type);
            $can_publish = current_user_can($post_type_object->cap->publish_posts);
        ?>
        <div class="submitbox" id="submitpost">

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
                        case 'pendingapproval':
                            esc_html_e('Pending Approval','pointfindercoreelements');
                            break;
                    }
                    ?>
                    </span>
                    <?php if ( 'publish' == $post->post_status || 'pendingapproval' == $post->post_status || $can_publish ) { ?>
                    <a href="#post_status" <?php if ( 'private' == $post->post_status ) { ?>style="display:none;" <?php } ?>class="edit-post-status hide-if-no-js"><span aria-hidden="true"><?php esc_html_e( 'Edit','pointfindercoreelements' ); ?></span> <span class="screen-reader-text"><?php esc_html_e( 'Edit status' ,'pointfindercoreelements'); ?></span></a>

                    <div id="post-status-select" class="hide-if-js">
                        <input type="hidden" name="hidden_post_status" id="hidden_post_status" value="<?php echo esc_attr( ('pendingapproval' == $post->post_status ) ? 'pendingapproval' : $post->post_status); ?>" />
                        <select name='post_status' id='post_status'>

                        <option<?php selected( $post->post_status, 'publish' ); ?> value='publish'><?php esc_html_e('Published','pointfindercoreelements') ?></option>
                        <option<?php selected( $post->post_status, 'pendingapproval' ); ?> value='pendingapproval'><?php esc_html_e('Pending Approval','pointfindercoreelements') ?></option>

                        </select>
                         <a href="#post_status" class="save-post-status hide-if-no-js button"><?php esc_html_e('OK','pointfindercoreelements'); ?></a>
                         <a href="#post_status" class="cancel-post-status hide-if-no-js button-cancel"><?php esc_html_e('Cancel','pointfindercoreelements'); ?></a>
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
                    <a class="hide-if-no-js" href="<?php echo esc_url( get_edit_post_link( $args['args']['revision_id'] ) ); ?>"><span aria-hidden="true"><?php esc_html_e( 'Browse', 'pointfindercoreelements' ); ?></span> <span class="screen-reader-text"><?php esc_html_e( 'Browse revisions' ,'pointfindercoreelements'); ?></span></a>
                </div>
                <?php };

                if ( $can_publish ){ // Contributors don't get to choose the date of publish ?>
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

}
