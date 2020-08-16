<?php
/**********************************************************************************************************************************
*
* Item Detail Page - Review Content
*
* Author: Webbu
***********************************************************************************************************************************/

if (trait_exists('PointFinderListingReviewPart')) {
  return;
}

trait PointFinderListingReviewPart
{
    use PointFinderReviewFunctions;

    public function pointfinder_review_part()
    {
        global $wpdb;

        $setup11_reviewsystem_usertype = $this->PFREVSIssetControl('setup11_reviewsystem_usertype', '', '0');

        $review_show = 1;
        $err_mes = '';

        if ($setup11_reviewsystem_usertype == 1) {
            if (is_user_logged_in()) {
                $review_show = 1;
            } else {
                $review_show = 0;
                $err_mes = wp_sprintf(esc_html__('You must be %s logged in %s to post a review.', 'pointfindercoreelements'), '<a class="pf-login-modal">', '</a>');
            }
        } elseif ($setup11_reviewsystem_usertype == 0) {
            $review_show = 1;
        } else {
            $review_show = 0;
        }

        $setup11_reviewsystem_criterias = $this->PFREVSIssetControl('setup11_reviewsystem_criterias', '', '');
        if (!empty($setup11_reviewsystem_criterias)) {
            $review_status = $this->PFControlEmptyArr($setup11_reviewsystem_criterias);
        } else {
            $review_status = '';
        }

        if ($review_status == false || empty($review_status)) {
            $review_show = 0;
            $err_mes = esc_html__('Please setup review criterias before you use review system.', 'pointfindercoreelements');
        }

        $hide_single = $user_review_done = 0;

        $setup11_reviewsystem_singlerev = $this->PFREVSIssetControl('setup11_reviewsystem_singlerev', '', '0');
        if (!is_user_logged_in()) {
            $user_review_done = 0;
        }
        $item_id = get_the_id();
        $criteria_number = $this->pf_number_of_rev_criteria();
        $return_results = $this->pfcalculate_total_review($item_id);

        if ($setup11_reviewsystem_singlerev == 1 && is_user_logged_in()) {
            $cur_user = wp_get_current_user();
            $reviewID = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT key1.post_id FROM $wpdb->postmeta as key1 INNER JOIN $wpdb->postmeta as key2 ON key1.post_id = key2.post_id and key2.meta_value = %s
				where key1.meta_key = %s and key1.meta_value = %d",
                $cur_user->user_email,
                "webbupointfinder_review_itemid",
                $item_id
                ),
            'ARRAY_A'
        );

            if (!empty($reviewID)) {
                $user_review_done = 1;
            }
        } elseif ($setup11_reviewsystem_singlerev == 1 && isset($commenter['comment_author_email'])) {
            $reviewID = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT key1.post_id FROM $wpdb->postmeta as key1 INNER JOIN $wpdb->postmeta as key2 ON key1.post_id = key2.post_id and key2.meta_value = %s
				where key1.meta_key = %s and key1.meta_value = %d",
                esc_attr($commenter['comment_author_email']),
                "webbupointfinder_review_itemid",
                $item_id
                ),
            'ARRAY_A'
        );

            if (!empty($reviewID)) {
                $user_review_done = 1;
            }
        }

        echo '<div class="pftrwcontainer hidden-print pf-itempagedetail-element pfnewbglppage" id="pfxreviews">';
        echo '<div class="pfitempagecontainerheader">'.esc_html__('Reviews', 'pointfindercoreelements').'</div>';

        $fixtext = '';
        if (isset($return_results['totalresult'])) {
            if ($return_results['totalresult'] == 0) {
                $fixtext = ' style="border-bottom:0;margin-bottom:0;"';
            }
        }

        echo '<div class="pfmainreviewinfo"'.$fixtext.' itemscope="" itemtype="https://schema.org/Review">';

      
        if (isset($return_results['totalresult']) && $return_results['totalresult'] > 0) {
            $total_resultx = $this->pfcalculate_total_rusers($item_id);
            $specialngcolor = '';
            if ($return_results['totalresult'] > 2 && $return_results['totalresult'] <= 3) {
                $specialngcolor = '#F6BB42';
            }elseif ($return_results['totalresult'] > 3 && $return_results['totalresult'] < 4 ) {
                $specialngcolor = '#8CC152';
            }elseif($return_results['totalresult'] >= 4){
                $specialngcolor = '#2ECC71';
            }
            $review_word = ($total_resultx > 1)?esc_html__('REVIEWS', 'pointfindercoreelements'):esc_html__('REVIEW', 'pointfindercoreelements');
            echo '<div class="pf-row clearfix">';
            echo '<div class="col-lg-2 col-md-3 col-sm-4">';
            if ($setup11_reviewsystem_singlerev == 1 && $user_review_done == 1) {
                echo '<span class="pf-rev-userdone">'.esc_html__('Already reviewed.', 'pointfindercoreelements').'</span>';
            }
            echo '<div class="pfreviewscore" style="background-color:'.$specialngcolor.'">
				<div itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating"><span itemprop="ratingValue">'.$return_results['totalresult'].'</span></div>
				<span class="pfreviewscoretext">'.esc_html__('Total Score', 'pointfindercoreelements').'</span>
				<span class="pfreviewusers">'.$total_resultx.' '.$review_word.'</span>
				<meta itemprop="itemReviewed" content="'.get_the_title().'"/>
				<meta itemprop="author" content="'.get_the_author_meta('nickname').'"/>
				';

            echo '</div></div>';
            echo '<div class="col-lg-10 col-md-9 col-sm-8"><div class="pfreviewcriterias">';
            $i = 0;
            $reviewcriterias2 = '';
            foreach ($setup11_reviewsystem_criterias as $rev_criteria) {
                $reviewcriterias2 .= '
								<span class="pf-rating-block clearfix">
						       		<span class="pf-rev-cr-text">'.$rev_criteria.':</span>
						            <span class="pf-rev-stars">';

                for ($m=0; $m < $return_results['peritemresult'][$i]; $m++) {
                    $reviewcriterias2 .= '<i class="fas fa-star"></i>';
                }
                for ($s=0; $s < (5-$return_results['peritemresult'][$i]); $s++) {
                    $reviewcriterias2 .= '<i class="far fa-star nostarp"></i>';
                }
                $reviewcriterias2 .= '</span>
						 		</span>
							';
                $i++;
            }
            echo ''.$reviewcriterias2;
            echo '</div></div>';
            echo '</div>';
        } else {
            echo esc_html__("There are no reviews yet, why not be the first?", 'pointfindercoreelements');
        }

        echo '</div>';

        if (isset($return_results['totalresult']) && $return_results['totalresult'] > 0) {
            $pfg_paged = (esc_sql(get_query_var('page'))) ? esc_sql(get_query_var('page')) : '';

            if (empty($pfg_paged)) {
                $pfg_paged = (esc_sql(get_query_var('paged'))) ? esc_sql(get_query_var('paged')) : 1;
            }

            $setup11_reviewsystem_revperpage = $this->PFREVSIssetControl('setup11_reviewsystem_revperpage', '', '3');
            $setup11_reviewsystem_flagfeature = $this->PFREVSIssetControl('setup11_reviewsystem_flagfeature', '', '1');
            $args = array(
                'post_type' => 'pointfinderreviews',
                'posts_per_page' => $setup11_reviewsystem_revperpage,
                'paged' => $pfg_paged,
                'post_status' => 'publish',
                'meta_key' => 'webbupointfinder_review_itemid',
                'meta_value' => $item_id,
                'orderby' => 'Date',/*Date, Title*/
                'order' => 'DESC'
            );
            $the_query = new WP_Query($args);

            /*
            *	Check Results
            *		print_r($the_query->query).PHP_EOL;
            *		print_r($the_query->request).PHP_EOL;
            *		print_r($the_query->found_posts).PHP_EOL;
            */

            global $wpdb;
            if ($the_query->have_posts()) {
                echo '<div class="pfreviews golden-forms">';
                echo '<ul>';


                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    $post_id_rev = get_the_id();
                    $author_pf_rev = get_the_title();
                    $revmetax = esc_attr(get_post_meta($post_id_rev, 'webbupointfinder_review_flag', true));

                    $flagstatus = ($revmetax != false) ? $revmetax : '';

                    $user_photo = get_the_author_meta('user_photo');
                    if (!empty($user_photo)) {
                        $user_photo = wp_get_attachment_image($user_photo);
                    }


                    $content_of_rev = ($flagstatus != 1)? get_the_content() : esc_html__('This review flagged.', 'pointfindercoreelements');
                    $user_photo_area = (!empty($user_photo))? $user_photo:get_avatar($post_id_rev, 128);


                    echo '<li class="pf-row clearfix">';

                    echo '
							<div class="pfreview-body clearfix">
								<div class="col-lg-1 col-md-3 col-sm-4">
							   		<div class="review-author-image">
									  '.$user_photo_area.'
									</div>
							   	</div>

					    		<div class="col-lg-11 col-md-9 col-sm-8">
							    	<div class="review-details-container">

								        <div class="review-author-vcard">'.$author_pf_rev .' '.esc_html__('says', 'pointfindercoreelements').' :  ';
                    if ($setup11_reviewsystem_flagfeature == 1 && ($flagstatus != 1 && $flagstatus == '')) {
                        echo '<a class="review-flag-link" data-pf-revid="'.$post_id_rev.'"><i class="fas fa-flag"></i> '.esc_html__('Flag this review', 'pointfindercoreelements').'</a>';
                    }
                    echo '<a class="pf-show-review-details"><i class="fas fa-sliders-h"></i> '.esc_html__('Details', 'pointfindercoreelements').'<div class="pf-itemrevtextdetails"><div class="pf-arrow-up"></div> '.$this->pfget_reviews_peritem($post_id_rev).'</div></a>

								        </div>
								    	<div class="pfreview-meta">'.wp_sprintf(esc_html__('%1$s at %2$s', 'pointfindercoreelements'), get_the_date(), get_the_time()).'</div>

								        <div class="pfreview-textarea"><p>'.$content_of_rev.'</p></div>


									</div>
								</div>
							</div>
							';


                    echo '</li>';
                }


                echo '</ul>';
                echo '</div>';
            }

            echo '<div class="pfstatic_paginate" >';
            $big = 999999999;


            echo paginate_links(array(
                    'base' => @add_query_arg('page','%#%'),//str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                    'format' => '?page=%#%',
                    'current' => max(1, $pfg_paged),
                    'total' => $the_query->max_num_pages,
                    'type' => 'list',
                    'add_fragment' => '#pfxreviews'
                ));
            echo '</div>';



            wp_reset_postdata();
        }
        echo '</div>';




        /**
        *Start: Review Show
        **/

        if ($review_show == 1) {
            $setup11_reviewsystem_singlerev = $this->PFREVSIssetControl('setup11_reviewsystem_singlerev', '', '0');

            $recaptcha_placeholder = '';

            if (class_exists('Pointfinder_reCaptcha_System')) {
                $reCaptcha = new Pointfinder_reCaptcha_System();
                $recaptcha_placeholder = $reCaptcha->create_recaptcha("pf-review-form");
            }

            $rev_rules = $rev_rules2 = '';

            $i = 0;
            $reviewcriterias = '';
            foreach ($setup11_reviewsystem_criterias as $rev_criteriakey=>$rev_criteriavalue) {
                $reviewcriterias .= '
				<span class="rating block">
		       		<span class="lbl-text">'.$rev_criteriavalue.':</span>
		            <input type="radio" class="rating-input" id="review-criteria-'.$i.'-5" name="rating'.$i.'" value="5">
		            <label for="review-criteria-'.$i.'-5" class="rating-star"><i class="fas fa-star"></i></label>
		            <input type="radio" class="rating-input" id="review-criteria-'.$i.'-4" name="rating'.$i.'" value="4">
		            <label for="review-criteria-'.$i.'-4" class="rating-star"><i class="fas fa-star"></i></label>
		            <input type="radio" class="rating-input" id="review-criteria-'.$i.'-3" name="rating'.$i.'" value="3">
		            <label for="review-criteria-'.$i.'-3" class="rating-star"><i class="fas fa-star"></i></label>
		            <input type="radio" class="rating-input" id="review-criteria-'.$i.'-2" name="rating'.$i.'" value="2">
		            <label for="review-criteria-'.$i.'-2" class="rating-star"><i class="fas fa-star"></i></label>
		            <input type="radio" class="rating-input" id="review-criteria-'.$i.'-1" name="rating'.$i.'" value="1">
		            <label for="review-criteria-'.$i.'-1" class="rating-star"><i class="fas fa-star"></i></label>
		 		</span>
			';

                $rev_rules .= 'rating'.$i.':"required",';
                $rev_rules2 .= 'rating'.$i.':"'.wp_sprintf(esc_html__('%s review required', 'pointfindercoreelements'), $rev_criteriavalue).'",';

                $i++;
            }

            /* Review Admin Panel Vars */
            $setup11_reviewsystem_emailarea = $this->PFREVSIssetControl('setup11_reviewsystem_emailarea', '', '1');
            $setup11_reviewsystem_emailarea_req = $this->PFREVSIssetControl('setup11_reviewsystem_emailarea_req', '', '0');
            $setup11_reviewsystem_mesarea = $this->PFREVSIssetControl('setup11_reviewsystem_mesarea', '', '1');
            $setup11_reviewsystem_mesarea_req = $this->PFREVSIssetControl('setup11_reviewsystem_mesarea_req', '', '0');


            /* Review JS Error Messages */

            $rev_rules .= 'pftermsofuserreview:"required",';
            $rev_rules2 .= 'pftermsofuserreview:"'.esc_html__('You must accept terms and conditions.', 'pointfindercoreelements').'",';

            $rev_rules .= 'name:"required",';
            $rev_rules2 .= 'name:"'.esc_html__('Please write your name', 'pointfindercoreelements').'",';


            if ($setup11_reviewsystem_emailarea == 1 && $setup11_reviewsystem_emailarea_req == 1) {
                $rev_rules .= 'email:{required:true,email:true},';
                $rev_rules2 .= 'email: {required: "'.esc_html__('Please write email', 'pointfindercoreelements').'",email: "'.esc_html__('Please write correct email', 'pointfindercoreelements').'"},';
            }
            if ($setup11_reviewsystem_mesarea == 1 && $setup11_reviewsystem_mesarea_req == 1) {
                $rev_rules .= 'msg:"required",';
                $rev_rules2 .= 'msg:"'.esc_html__('Please write message.', 'pointfindercoreelements').'",';
            }


            $nameandemailarea = '';

            if ($setup11_reviewsystem_emailarea == 1) {
                if (is_user_logged_in()) {
                    $user = get_user_by('id', get_current_user_id());

                    if (!empty($user->nickname)) {
                        $nameandemailarea .= '
						<input type="hidden" name="name" value="' . $user->nickname . '" />
						<input type="hidden" name="userid" value="' . $user->ID . '" />
					';
                    } else {
                        $nameandemailarea .= '
						<div class="col6 first">
							<section>
								<label class="lbl-ui">
									<input type="text" name="name" class="input" placeholder="'.esc_html__('Name  & Surname', 'pointfindercoreelements').'" value="' . $user->nickname . '" />
								</label>
							</section>
						</div>
					';
                    }

                    if (!empty($user->user_email)) {
                        $nameandemailarea .= '
						<input type="hidden" name="email" value="' . $user->user_email . '" />
					';
                    } else {
                        $nameandemailarea .= '
						<div class="col6 last">
							<section>
								<label class="lbl-ui">
									<input type="email" name="email" class="input" placeholder="'.esc_html__('Email Address', 'pointfindercoreelements').'" value="' . $user->user_email . '" />
								</label>
							</section>
						</div>
					';
                    }
                } else {
                    $nameandemailarea .= '
					<div class="col6 first">
						<section>
							<label class="lbl-ui">
								<input type="text" name="name" class="input" placeholder="'.esc_html__('Name  & Surname', 'pointfindercoreelements').'" />
							</label>
						</section>
					</div>
					<div class="col6 last">
						<section>
							<label class="lbl-ui">
								<input type="email" name="email" class="input" placeholder="'.esc_html__('Email Address', 'pointfindercoreelements').'" />
							</label>
						</section>
					</div>
				';
                }
            } else {
                $nameandemailarea .= '
				<div class="col12">
					<section>
						<label class="lbl-ui">
							<input type="text" name="name" class="input" placeholder="'.esc_html__('Name  & Surname', 'pointfindercoreelements').'" />
						</label>
					</section>
				</div>
			';
            }



            $messagearea = '';

            if ($setup11_reviewsystem_mesarea == 1) {
                $messagearea .= '
				<section>
					<label class="lbl-ui">
						<textarea name="msg" class="textarea" placeholder="'.esc_html__('Review', 'pointfindercoreelements').'"></textarea>
					</label>
				</section>
			';
            }


            $hide_single = $user_review_done = 0;
            $mypost_id = get_the_id();
            if ($setup11_reviewsystem_singlerev == 1 && is_user_logged_in()) {
                $cur_user = wp_get_current_user();
                $reviewID = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT key1.post_id FROM $wpdb->postmeta as key1 INNER JOIN $wpdb->postmeta as key2 ON key1.post_id = key2.post_id and key2.meta_value = %s
					where key1.meta_key = %s and key1.meta_value = %d",
                    $cur_user->user_email,
                    "webbupointfinder_review_itemid",
                    $mypost_id
                    ),
                'ARRAY_A'
            );

                if (!empty($reviewID)) {
                    $hide_single = 1;
                    $user_review_done = 1;
                }
            } elseif ($setup11_reviewsystem_singlerev == 1 && isset($commenter['comment_author_email'])) {
                $reviewID = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT key1.post_id FROM $wpdb->postmeta as key1 INNER JOIN $wpdb->postmeta as key2 ON key1.post_id = key2.post_id and key2.meta_value = %s
					where key1.meta_key = %s and key1.meta_value = %d",
                    esc_attr($commenter['comment_author_email']),
                    "webbupointfinder_review_itemid",
                    $mypost_id
                    ),
                'ARRAY_A'
            );

                if (!empty($reviewID)) {
                    $hide_single = 1;
                    $user_review_done = 1;
                }
            }

            if ($hide_single == 0) {
                $lm = $i-1;

                echo '<div id="pftrwcontainer" class="pftrwcontainer pfrevformex golden-forms hidden-print pf-itempagedetail-element pfnewbglppage">';
                echo '<div id="pftrwcontainer-overlay" class="pftrwcontainer-overlay"></div>';
                echo '<div class="pfitempagecontainerheader">'.esc_html__('Leave a review', 'pointfindercoreelements').'</div>';
                ob_start(); ?>
			      (function($) {
			      "use strict";



					$('body').on('click','#pf-review-submit-button',function(){

						var form = $('#pf-review-form');
						var pfsearchformerrors = form.find(".pfsearchformerrors");

						form.validate({
							  debug:false,
							  onfocus: false,
							  onfocusout: false,
							  onkeyup: false,
							  rules:{
							  	<?php echo $rev_rules; ?>
							  },
							  messages:{
								<?php echo $rev_rules2; ?>
							  },
							  validClass: "pfvalid",
							  errorClass: "pfnotvalid pfaddnotvalidicon",
							  errorElement: "li",
							  errorContainer: pfsearchformerrors,
							  errorLabelContainer: $("ul", pfsearchformerrors),
							  invalidHandler: function(event, validator) {
								var errors = validator.numberOfInvalids();
								if (errors) {
									pfsearchformerrors.show("slide",{direction : "up"},100);
									form.find(".pfsearch-err-button").on("click",function(){
										pfsearchformerrors.hide("slide",{direction : "up"},100);
										return false;
									});
								}else{
									pfsearchformerrors.hide("fade",300);
								}
							  }
						});


						if(form.valid()){
							$.pfReviewwithAjax(form.serialize());
						}
						return false;
					});

			      })(jQuery);

			    <?php
                $script_output = ob_get_contents();
                ob_end_clean();
                wp_add_inline_script($this->plugin_name.'singlethememap', $script_output, 'after');
				
                global $wpdb;
                $terms_conditions_template = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s ",'_wp_page_template','terms-conditions.php'), ARRAY_A);
                $terms_permalink = '#';
                if(count($terms_conditions_template) > 1){
                        foreach ($terms_conditions_template as $terms_conditions_template_single) {
                            $terms_permalink = get_permalink($terms_conditions_template_single['post_id']);
                        }
                }else{
                    if (isset($terms_conditions_template[0]['post_id'])) {
                            $terms_permalink = get_permalink($terms_conditions_template[0]['post_id']);
                    }
                }

				echo '
				<form id="pf-review-form">
					<div class="pfsearchformerrors">
						<ul>
						</ul>
					<a class="button pfsearch-err-button"><i class="fas fa-times"></i> '.esc_html__('CLOSE', 'pointfindercoreelements').'</a>
					</div>
					<div class="pf-row clearfix">
                        <div class="col-lg-5">';
                        echo '<section>';

                        echo $reviewcriterias;

                        echo '</section>';
                        echo '</div>
						<div class="col-lg-7">
							<div class="row">
							'.$nameandemailarea.'
							</div>
							'.$messagearea.'
                            <section>
                                <div style="position:relative;">
                                <span class="goption upt">
                                    <label class="options">
                                        <input type="checkbox" id="pftermsofuserreview" name="pftermsofuserreview" value="1">
                                        <span class="checkbox"></span>
                                    </label>
                                    <label for="check1" class="upt1ch1">'.wp_sprintf(esc_html__( 'I have read the %s terms and conditions %s and accept them.', 'pointfindercoreelements' ),'<a href="'.$terms_permalink.'" class="pftermshortc3"><strong>','</strong></a>').'</label>
                                </span>
                                </div>
                            </section>
                            '.$recaptcha_placeholder.'
							<section>
						  	   <input type="hidden" name="itemid" value="'.intval($mypost_id).'" />
						  	   <input type="hidden" name="revcrno" value="'.intval($lm).'" />
						       <button id="pf-review-submit-button" class="button green">'.esc_html__('Submit Review', 'pointfindercoreelements').'</button>
						    </section>
						</div>
			   			';


                

                echo '
			  	 </div>
			    </form>
				';

                echo '</div>';
            }
        } else {
            echo '<div class="pftrwcontainer pfrevformex golden-forms hidden-print pf-itempagedetail-element">';
            echo '<div class="pfitempagecontainerheader">'.esc_html__('Leave a Review', 'pointfindercoreelements').'</div>';
            if ($err_mes != '') {
                echo ''.$err_mes;
            }
            echo '</div>';
        }

        /**
        *End: Review Show
        **/
    }
}
