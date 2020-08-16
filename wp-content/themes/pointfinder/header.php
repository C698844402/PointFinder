<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		
		<?php wp_head();?>
	</head>
	<body <?php body_class(); ?> >
		
		<?php if (!is_page_template('pf-empty-page.php' )  && !is_page_template('terms-conditions.php' )) {?>
		
			<div id="pf-loading-dialog" class="pftsrwcontainer-overlay"></div>
        	<?php

			/* Start: Transparent Header Addon */
			global $post;
			$transparent_header_text = $style_text = $logocsstext = "";
			if (isset($post->ID) && !is_search()) {
				$transparent_header = get_post_meta( $post->ID, 'webbupointfinder_page_transparent', true );
				if (!empty($transparent_header)) {
					$transparent_header_text = " pftransparenthead";
				}
			}
			/* End: Transparent Header Addon */

			?>
	        <header class="wpf-header wpf-transition-all hidden-print<?php echo esc_attr($transparent_header_text);?>" id="pfheadernav">
        	<?php
        	$general_toplinedstatus = PFSAIssetControl('general_toplinedstatus','','1');
        	$post_item_icon_status = PFASSIssetControl('general_postitembutton_iconstatus','','1');
        	$post_item_button_status = PFASSIssetControl('general_postitembutton_status','','0');
        	$setup4_membersettings_dashboard = absint(PFSAIssetControl('setup4_membersettings_dashboard','',''));
        	$setup4_membersettings_dashboard_link = esc_url(get_permalink($setup4_membersettings_dashboard));
        	$pfmenu_perout = PFPermalinkCheck();
        	$setup4_membersettings_frontend = PFSAIssetControl('setup4_membersettings_frontend','','0');
			$setup4_membersettings_loginregister = PFSAIssetControl('setup4_membersettings_loginregister','','0');	

        	if ($general_toplinedstatus == '1') {
        		$output = '';
        		$setup19_socialiconsbarsettings_main = PFSAIssetControl('setup19_socialiconsbarsettings_main','','0');
									
				$setup19_socialiconsbarsettings_envelope = PFSAIssetControl('setup19_socialiconsbarsettings_envelope','','');
				$setup19_socialiconsbarsettings_envelope_link = PFSAIssetControl('setup19_socialiconsbarsettings_envelope_link','','');
				
				$setup19_socialiconsbarsettings_phone = PFSAIssetControl('setup19_socialiconsbarsettings_phone','','');
				$setup19_socialiconsbarsettings_phone_link = PFSAIssetControl('setup19_socialiconsbarsettings_phone_link','','');
        	?>
	        	<div class="pftopline wpf-transition-all">
	        		<div class="pf-container">
						<div class="pf-row">
							<div class="col-lg-12 col-md-12">
								<div class="wpf-toplinewrapper">
										<?php 
										if($setup19_socialiconsbarsettings_main == 1){
										?>
										<div class="pf-toplinks-left clearfix">
											<ul class="pf-sociallinks">
												<?php
												$pf_socialname_arr = array("facebook","twitter","linkedin","pinterest","dribbble","dropbox","flickr","github","instagram","rss","skype","tumblr","vk","youtube");
												
												$output_num = 1;
												foreach ($pf_socialname_arr as $socialname) {
													if($socialname != ''){
														$social_admin_var = PFSAIssetControl('setup19_socialiconsbarsettings_'.$socialname,'','');
														if($social_admin_var != '' && $output_num < 8){
															echo '<li class="pf-sociallinks-item '.esc_attr($socialname).'  wpf-transition-all"><a href="'.esc_url($social_admin_var).'" target="_blank"><i class="'.pfsocialtoicon($socialname).'"></i></a></li>';
															$output_num++;
														}
													}
												}
																
												
												if (!empty($setup19_socialiconsbarsettings_phone)) {?>
													<li class="pf-sociallinks-item pf-infolinks-item envelope  wpf-transition-all">
														<a href="<?php echo esc_url($setup19_socialiconsbarsettings_phone_link);?>"><i class="fas fa-phone-square-alt"></i> <span class="pf-infolink-item-text"><?php echo esc_attr( $setup19_socialiconsbarsettings_phone );?></span></a>
													</li>
												<?php 
												} 
												if (!empty($setup19_socialiconsbarsettings_envelope)) {?>
													<li class="pf-sociallinks-item pf-infolinks-item pflast envelope  wpf-transition-all">
														<a href="<?php echo esc_url( $setup19_socialiconsbarsettings_envelope_link );?>"><i class="fas fa-envelope"></i><span class="pf-infolink-item-text" style="margin-left: 3px;"><?php echo esc_html($setup19_socialiconsbarsettings_envelope);?></span></a>
													</li>
												<?php } ?>
											</ul>
										</div>
										<?php 
										}
										?>
										<?php
										if($setup4_membersettings_loginregister == 1){
											if(is_user_logged_in()){$pflogintext = " pfloggedin";}else{$pflogintext = "";}
										?>
										<div class="pf-toplinks-right clearfix">
										<?php
											$st9_currency_status = PFASSIssetControl('st9_currency_status','',0);

											if (!empty($st9_currency_status)) {
												$currency_arr = array();
												$st9_currency_from = PFASSIssetControl('st9_currency_from','','');

												if (!empty($st9_currency_from)) {

													$st9_currency_to = PFASSIssetControl('st9_currency_to','','');

													if (!empty($st9_currency_to)) {
														$currency_arr = pfstring2BasicArray($st9_currency_to);
													}

													if (!empty($currency_arr)) {
														array_push($currency_arr, $st9_currency_from);
													}else{
														$currency_arr = array($st9_currency_from);
													}

													if (count($currency_arr)>0) {
														$currency_fix_text = '';
														if(class_exists('SitePress')) {$currency_fix_text = ' pfwpmlenabled';}
													}
													
													$selected_currency = (isset($_COOKIE['pointfinder_c_code']))? $_COOKIE['pointfinder_c_code']: $st9_currency_from;
													if (extension_loaded('intl') && function_exists('pointfinder_getCurrencySymbol')) {
													?>
													<div class="pointfinder-currency-changer<?php echo esc_attr( $currency_fix_text );?>">
														<select name="pfccs_changer" id="pfccs_changer">
															<?php 
															foreach ($currency_arr as $currency_arr_single) {
																echo '<option value="'.$currency_arr_single.'" '.selected( $selected_currency, $currency_arr_single, false ).'>'.$currency_arr_single.'('.pointfinder_getCurrencySymbol($currency_arr_single).')</option>';
															}
															?>
														</select>
													</div>
												<?php 
												   }
												}
											} 
										?>

										<nav id="pf-topprimary-nav" class="pf-topprimary-nav pf-nav-dropdown clearfix hidden-sm hidden-xs">
											<ul class="pf-nav-dropdown pfnavmenu pf-topnavmenu ">
												<li class="pf-my-account pfloggedin">
													<?php 
													if(class_exists('SitePress')) {
														$pf_languages = icl_get_languages('skip_missing=1&orderby=KEY&order=DIR'); 
													
														foreach ($pf_languages as $pf_languagex) {
															if (PF_current_language() == $pf_languagex['language_code']) {
																echo '<a href="#" class="pf_language_selects"><img src="'.$pf_languagex['country_flag_url'].'"/>'.$pf_languagex['translated_name'].'</a>';
															}
														}
														echo '<ul class="pfnavsub-menu sub-menu menu-odd  menu-depth-1 hidden-xs hidden-sm">';
														
														
														foreach ($pf_languages as $pf_language) {
															echo '<li>';
																echo '<a href="'.esc_url($pf_language['url']).'" class="pf_language_selects"><img src="'.esc_url($pf_language['country_flag_url']).'"/>'.esc_html($pf_language['translated_name']).'</a>';
															echo '</li>';
														}

														echo '</ul>';
													}
													?>	
												</li>

												<?php 
												if ( !is_user_logged_in() ){
													?>
													<li class="pf-login-register" id="pf-login-trigger-button"><a href="#"><i class="fas fa-sign-in-alt"></i> <?php  echo esc_html__('Login','pointfinder')?></a></li>
													<li class="pf-login-register" id="pf-register-trigger-button"><a href="#"><i class="fas fa-user-plus"></i> <?php  echo esc_html__('Register','pointfinder')?></a></li>
													<?php 
														$st9_currency_status = PFASSIssetControl('st9_currency_status','',0);
														if (empty($st9_currency_status)) {
															?>
															<li class="pf-login-register" id="pf-lp-trigger-button"><a href="#"><i class="far fa-question-circle"></i> <?php  echo esc_html__('Forgot Password','pointfinder')?></a></li>
															<?php 
														}
												}else {
													global $current_user;
													?>
													<li class="pf-my-account pfloggedin">
														<a href="#">
														<i class="fas fa-user-circle"></i> 
														<?php  echo esc_html($current_user->nickname)?>
														</a>
														<ul class="pfnavsub-menu sub-menu menu-odd  menu-depth-1 hidden-xs hidden-sm">
															<?php 													
															
															
															$setup4_membersettings_favorites = absint(PFSAIssetControl('setup4_membersettings_favorites','','1'));
															$setup11_reviewsystem_check = absint(PFREVSIssetControl('setup11_reviewsystem_check','','0'));
															


															$setup29_dashboard_contents_my_page_menuname = sanitize_text_field(PFSAIssetControl('setup29_dashboard_contents_my_page_menuname','',''));
															$setup29_dashboard_contents_inv_page_menuname = sanitize_text_field(PFSAIssetControl('setup29_dashboard_contents_inv_page_menuname','',''));
															$setup29_dashboard_contents_favs_page_menuname = sanitize_text_field(PFSAIssetControl('setup29_dashboard_contents_favs_page_menuname','',''));
															$setup29_dashboard_contents_profile_page_menuname = sanitize_text_field(PFSAIssetControl('setup29_dashboard_contents_profile_page_menuname','',''));
															$setup29_dashboard_contents_submit_page_menuname = sanitize_text_field(PFSAIssetControl('setup29_dashboard_contents_submit_page_menuname','',''));
															$setup29_dashboard_contents_rev_page_menuname = sanitize_text_field(PFSAIssetControl('setup29_dashboard_contents_rev_page_menuname','',''));
															$setup4_membersettings_frontend = absint(PFSAIssetControl('setup4_membersettings_frontend','','0'));
															$setup_invoices_sh = absint(PFASSIssetControl('setup_invoices_sh','','1'));
															
															

															echo '<li ><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=profile"><i class="fas fa-user-cog"></i> '. $setup29_dashboard_contents_profile_page_menuname.'</a></li>';
															if($setup4_membersettings_frontend == 1){
																echo '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=newitem"><i class="fas fa-plus-square"></i> '. $setup29_dashboard_contents_submit_page_menuname.'</a></li>';
																echo '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems"><i class="far fa-list-alt"></i> '. $setup29_dashboard_contents_my_page_menuname.'</a></li>';
															}

															if (class_exists('Front_End_Pm')) {
																echo '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=mymessages"><i class="fas fa-envelope"></i> '.esc_html__( "Messages", "pointfinder" ).'</a></li>';
															}
															


															if($setup4_membersettings_frontend == 1 && $setup_invoices_sh == 1){
																echo '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=invoices"><i class="fas fa-file-invoice-dollar"></i> '. $setup29_dashboard_contents_inv_page_menuname.'</a></li>';
															}
															if($setup4_membersettings_favorites == 1){
																echo '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=favorites"><i class="fas fa-heart"></i> '. $setup29_dashboard_contents_favs_page_menuname.'</a></li>';
															}
															if($setup11_reviewsystem_check == 1){
																echo '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=reviews"><i class="fas fa-star"></i> '. $setup29_dashboard_contents_rev_page_menuname.'</a></li>';
															}
															echo '<li><a href="'.wp_logout_url( esc_url(home_url("/")) ).'"><i class="fas fa-sign-out-alt"></i> '. esc_html__('Logout','pointfinder').'</a></li>';
															?>
														</ul>
														
													</li>
												<?php } ?>
											</ul>
										</nav>
									</div>
									<?php }else{
										if(class_exists('SitePress')) {
										?>
											<div class="pf-toplinks-right clearfix">
												<nav id="pf-topprimary-nav" class="pf-topprimary-nav pf-nav-dropdown clearfix hidden-sm hidden-xs">
													<ul class="pf-nav-dropdown pfnavmenu pf-topnavmenu ">
														<li class="pf-my-account pfloggedin">
															<?php 
														
															$pf_languages = icl_get_languages('skip_missing=1&orderby=KEY&order=DIR'); 
															
																foreach ($pf_languages as $pf_languagex) {
																	if (PF_current_language() == $pf_languagex['language_code']) {
																		echo '<a href="#" class="pf_language_selects"><img src="'.$pf_languagex['country_flag_url'].'"/>'.$pf_languagex['translated_name'].'</a>';
																	}
																}
																echo '<ul class="pfnavsub-menu sub-menu menu-odd  menu-depth-1 hidden-xs hidden-sm">';
																
																
																foreach ($pf_languages as $pf_language) {
																	echo '<li>';
																		echo '<a href="'.esc_url($pf_language['url']).'" class="pf_language_selects"><img src="'.esc_url($pf_language['country_flag_url']).'"/>'.esc_html($pf_language['translated_name']).'</a>';
																	echo '</li>';
																}

																echo '</ul>';
															
															?>
															
														</li>
													</ul>
													</nav>
											</div>
										<?php 
										} 
									}?>
								</div>
							</div>
						</div>
					</div>
	        	</div>
	            <?php 
	            }
	            ?>
	            <div class="wpf-navwrapper">
	            	<div class="pfmenucontaineroverflow"></div>
		            <?php 
					$pf_navmenu = wp_nav_menu(array(
					        'echo' => FALSE,
					        'theme_location'  => 'pointfinder-main-menu',
					        'fallback_cb' => '__return_false'
					    	)
					);

					if ( ! empty ( $pf_navmenu ) ){
					?>
						<a id="pf-primary-nav-button" class="mobilenavbutton" data-menu="pf-primary-navmobile" title="<?php echo esc_html__('Menu','pointfinder');?>"><i class="fas fa-bars"></i><i class="fas fa-times"></i></a>
					<?php 
					}?>
				
					<?php 
					$setup4_membersettings_loginregister = esc_attr(PFSAIssetControl('setup4_membersettings_loginregister','','0'));
					if ($setup4_membersettings_loginregister == 1) {
					?>
						<a id="pf-topprimary-nav-button" class="mobilenavbutton" data-menu="pf-topprimary-navmobi" title="<?php echo esc_html__('User Menu','pointfinder');?>"><i class="fas fa-user"></i><i class="fas fa-times"></i></a>
					<?php 
					}
					?>
					<a id="pf-primary-search-button" class="mobilenavbutton" data-menu="pfsearch-draggable" title="<?php echo esc_html__('Search','pointfinder');?>"><i class="fas fa-search"></i><i class="fas fa-times"></i></a>
				
					<div class="pf-container pf-megamenu-container">

						<div class="pf-row">
							<?php
							if (is_rtl()) {
								pointfinder_menucolumn_get();
								pointfinder_logocolumn_get();
							}else{
								pointfinder_logocolumn_get();
								pointfinder_menucolumn_get();
							}
							?>
						</div>
					</div>
					<?php
						$stp28_mmenu_menulocation = esc_attr(PFSAIssetControl('stp28_mmenu_menulocation','','left'));
					?>
					<div class="pf-container pfmobilemenucontainer pf-megamenu-container" data-direction="<?php echo sanitize_text_field( $stp28_mmenu_menulocation );?>">
						<div class="pf-row">

							<div class="pf-menu-container">

								<a class="pf-logo-container pfmobilemenulogo clearfix" href="<?php echo esc_url(home_url("/"));?>"></a>
								<div class="pf-sidebar-divider"></div>
								
								

								
								<nav id="pf-primary-navmobile" class="pf-primary-navclass pf-nav-dropdown clearfix" data-direction="<?php echo sanitize_text_field( $stp28_mmenu_menulocation );?>">
									<ul class="pf-nav-dropdown pfnavmenu pf-topnavmenu clearfix">
										<?php pointfinder_navigation_menu("mobile");?>
									</ul>

									
									<?php 

										if(class_exists('SitePress')) {
											$pf_languages = icl_get_languages('skip_missing=1&orderby=KEY&order=DIR');
											if (count($pf_languages) > 0) {
												echo '<div class="pf-sidebar-divider"></div>';
												echo '<div class="pfnewlanguageselection">';
												echo '<span class="langbarpf">';
												echo '<i class="fas fa-globe"></i> ';esc_html_e("Languages:","pointfinder");
												echo '</span>';
												foreach ($pf_languages as $pf_language) {
														echo '<span>';
															echo '<a href="'.esc_url($pf_language['url']).'" class="pf_language_selects"><img src="'.esc_url($pf_language['country_flag_url']).'" alt="'.esc_html($pf_language['translated_name']).'"/></a>';
														echo '</span>';
												}
												echo '</div>';
											}
										}
										
									?>

									<div class="pf-sidebar-divider"></div>
									<?php if (PFASSIssetControl('general_postitembutton_mstatus','',1) == 1){ ?>
										<a id="pfpostitemlinkmobile" class="menu-link main-menu-link" href="<?php echo esc_url($setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=newitem');?>">
										<?php if($post_item_icon_status == 1){?>
										<i class="<?php echo esc_attr(PFASSIssetControl('pnewiconname','','fas fa-plus'));?>"></i><?php }?>
										<?php echo esc_attr(PFASSIssetControl('general_postitembutton_buttontext','','Post New Listing'));?>
										</a>
										<div class="pf-sidebar-divider"></div>
									<?php } ?>
								</nav>	
								
								<nav id="pf-topprimary-navmobi" class="pf-topprimary-nav pf-nav-dropdown clearfix" data-direction="<?php echo sanitize_text_field( $stp28_mmenu_menulocation );?>">
									<ul class="pf-nav-dropdown  pfnavmenu pf-topnavmenu pf-nav-dropdownmobi">
										<?php 
										if ( !is_user_logged_in() ){
											?>
											<li class="pf-login-register" id="pf-login-trigger-button-mobi"><a href="#"><i class="fas fa-sign-in-alt"></i> <?php  echo esc_html__('Login','pointfinder')?></a></li>
											<li class="pf-login-register" id="pf-register-trigger-button-mobi"><a href="#"><i class="fas fa-user-plus"></i> <?php  echo esc_html__('Register','pointfinder')?></a></li>
											<li class="pf-login-register" id="pf-lp-trigger-button-mobi"><a href="#"><i class="far fa-question-circle"></i> <?php  echo esc_html__('Forgot Password','pointfinder')?></a></li>
											<?php 
										}else {
											$setup29_dashboard_contents_my_page_menuname = sanitize_text_field(PFSAIssetControl('setup29_dashboard_contents_my_page_menuname','',''));
											$setup29_dashboard_contents_inv_page_menuname = sanitize_text_field(PFSAIssetControl('setup29_dashboard_contents_inv_page_menuname','',''));
											$setup29_dashboard_contents_favs_page_menuname = sanitize_text_field(PFSAIssetControl('setup29_dashboard_contents_favs_page_menuname','',''));
											$setup29_dashboard_contents_profile_page_menuname = sanitize_text_field(PFSAIssetControl('setup29_dashboard_contents_profile_page_menuname','',''));
											$setup29_dashboard_contents_submit_page_menuname = sanitize_text_field(PFSAIssetControl('setup29_dashboard_contents_submit_page_menuname','',''));
											$setup29_dashboard_contents_rev_page_menuname = sanitize_text_field(PFSAIssetControl('setup29_dashboard_contents_rev_page_menuname','',''));
											$setup4_membersettings_frontend = absint(PFSAIssetControl('setup4_membersettings_frontend','','0'));
											$setup4_membersettings_favorites = absint(PFSAIssetControl('setup4_membersettings_favorites','','1'));
											$setup11_reviewsystem_check = absint(PFREVSIssetControl('setup11_reviewsystem_check','','0'));
											$setup_invoices_sh = absint(PFASSIssetControl('setup_invoices_sh','','1'));
											echo '<li ><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=profile"><i class="fas fa-user-cog"></i> '. $setup29_dashboard_contents_profile_page_menuname.'</a></li>';
											if($setup4_membersettings_frontend == 1){
												echo '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=newitem"><i class="fas fa-plus-square"></i> '. $setup29_dashboard_contents_submit_page_menuname.'</a></li>';
												echo '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=myitems"><i class="far fa-list-alt"></i> '. $setup29_dashboard_contents_my_page_menuname.'</a></li>';
											}
											if($setup4_membersettings_frontend == 1 && $setup_invoices_sh == 1){
												echo '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=invoices"><i class="fas fa-file-invoice-dollar"></i> '. $setup29_dashboard_contents_inv_page_menuname.'</a></li>';
											}
											if($setup4_membersettings_favorites == 1){
												echo '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=favorites"><i class="fas fa-heart"></i> '. $setup29_dashboard_contents_favs_page_menuname.'</a></li>';
											}
											if($setup11_reviewsystem_check == 1){
												echo '<li><a href="'.$setup4_membersettings_dashboard_link.$pfmenu_perout.'ua=reviews"><i class="fas fa-star"></i> '. $setup29_dashboard_contents_rev_page_menuname.'</a></li>';
											}
											echo '<li><a href="'.wp_logout_url( esc_url(home_url("/")) ).'"><i class="fas fa-sign-out-alt"></i> '. esc_html__('Logout','pointfinder').'</a></li>';
										} 
										?>
									</ul>
								</nav>
								
							</div>
							
						</div>
					</div>
				</div>

	        </header>
		
       		<div class="wpf-container<?php echo esc_attr($transparent_header_text);?>">
        	<div id="pfmaincontent" class="wpf-container-inner">
        <?php }?>