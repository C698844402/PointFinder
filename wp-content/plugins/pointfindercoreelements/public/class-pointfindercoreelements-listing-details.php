<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themeforest.net/user/webbu
 * @since      1.0.0
 *
 * @package    Pointfindercoreelements
 * @subpackage Pointfindercoreelements/listingdetails
 */

class Pointfindercoreelements_ListingDetails
{
    use PointFinderOptionFunctions,
    PointFinderCommonFunctions,
    PointFinderWPMLFunctions,
    PointFinderListingColumns,
    PointFinderPageNotFound;
    

    private $post_type_name;
    private $plugin_name;
    private $version;

    public $post_id;
    public $post_type;
    public $item_terms;
    public $item_parent_term;
    public $item_locations;
    public $thumbnail_id;
    public $listing_config_meta;

    public function __construct($plugin_name, $version, $post_type_name)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->post_type_name = $post_type_name;
    }

    public function pointfinder_listing_post_type_filter($status, $post_type){
        if ($post_type == $this->post_type_name) {
            $status = true;
        }else{
            $status = false;
        }

        return $status;
    }

    public function pf_get_item_term_id($the_post_id){
        $item_term = '';
        if (!empty($the_post_id)) {
            $item_term_get = (!empty($the_post_id)) ? wp_get_post_terms($the_post_id, 'pointfinderltypes', array("fields" => "ids")) : '' ;

            if (count($item_term_get) > 1) {
                if (isset($item_term_get[0])) {
                    $find_top_parent = $this->pf_get_term_top_most_parent($item_term_get[0],'pointfinderltypes');
                    $item_term = $find_top_parent['parent'];
                }
            }else{
                if (isset($item_term_get[0])) {
                    $find_top_parent = $this->pf_get_term_top_most_parent($item_term_get[0],'pointfinderltypes');
                    $item_term = $find_top_parent['parent'];
                }
            }
        }
        return $item_term;
    }

    public function enqueue_scripts()
    {
        global $post_type;

        if (is_single() && $post_type == $this->post_type_name) {
            $setup42_itempagedetails_configuration = $this->PFSAIssetControl("setup42_itempagedetails_configuration", "", "");
            $street_view_height = (isset($setup42_itempagedetails_configuration['streetview']['mheight']))?$setup42_itempagedetails_configuration['streetview']['mheight']:390;
            $location_view_height = (isset($setup42_itempagedetails_configuration['location']['mheight']))?$setup42_itempagedetails_configuration['location']['mheight']:390;
            $streetview_status = (isset($setup42_itempagedetails_configuration['streetview']['status']))?$setup42_itempagedetails_configuration['streetview']['status']:0;

            $pfid = get_the_id();
            

            $pfstviewcor = esc_attr(get_post_meta($pfid, 'webbupointfinder_items_location', true));

            if (empty($pfstviewcor) || $pfstviewcor == ",") {
                $pfstviewcor = '0,0';
            }

            $pfstview = get_post_meta($pfid, 'webbupointfinder_item_streetview', true);

            if (empty($pfstview)) {
                $pfstview = array('heading'=>'0','pitch'=>0,'zoom'=>0);
            }

            $pfstview = $this->PFCleanArrayAttr('PFCleanFilters', $pfstview);
            $wemap_geoctype = $this->PFSAIssetControl('wemap_geoctype','','');

            wp_enqueue_script( 'theme-leafletjs' );
            wp_enqueue_style( 'theme-leafletcss');

            $we_special_key = $wemap_here_appid = $wemap_here_appcode = '';
            $stp5_mapty = $this->PFSAIssetControl('stp5_mapty', '', 1);

            switch ($stp5_mapty) {
                case 3:
                    $we_special_key = $this->PFSAIssetControl('stp5_mapboxpt','','');
                    break;

                case 5:
                    $wemap_here_appid = $this->PFSAIssetControl('wemap_here_appid','','');
                    $wemap_here_appcode = $this->PFSAIssetControl('wemap_here_appcode','','');
                    break;

                case 6:
                    $we_special_key = $this->PFSAIssetControl('wemap_bingmap_api_key','','');
                    break;
            }


            if ($stp5_mapty == 1 || ($wemap_geoctype == 'google' || !empty($pfstview)) ) {
                $maplanguage = $this->PFSAIssetControl('setup5_mapsettings_maplanguage','','en');
                $we_special_key_google = $this->PFSAIssetControl('setup5_map_key','','');
                wp_enqueue_script('theme-google-api', "https://maps.googleapis.com/maps/api/js?key=$we_special_key_google&libraries=places&language='.$maplanguage");
            }

            if ($stp5_mapty == 4) {
                $we_special_key_yandex = $this->PFSAIssetControl('wemap_yandexmap_api_key','','');
                $wemap_langy = $this->PFSAIssetControl('wemap_langy','','');;
                wp_enqueue_script('theme-yandex-map', "https://api-maps.yandex.ru/2.1/?lang=".$wemap_langy."&apikey=".$we_special_key_yandex);
            }

            $setup5_mapsettings_zoom = $this->PFSAIssetControl('setup42_searchpagemap_zoom','','12');

            wp_register_script($this->plugin_name.'singlethememap', PFCOREELEMENTSURLPUBLIC . 'js/pointfindercoreelements-single-thememap.js', array( 'jquery' ), $this->version, true);
            wp_enqueue_script($this->plugin_name.'singlethememap');
            wp_localize_script($this->plugin_name.'singlethememap', 'pfthemesm', array(
                'stp5_mapty' => $stp5_mapty,
                'key' => $we_special_key,
                'wemap_here_appid' => $wemap_here_appid,
                'wemap_here_appcode' => $wemap_here_appcode,
                'location_view_height' => $location_view_height,
                'pfstviewcor' => $pfstviewcor,
                'pfid' => $pfid,
                'zoom' => $setup5_mapsettings_zoom,
                'lang' => $this->PF_current_language(),
                'pfstview_zoom' => $pfstview['zoom'],
                'pfstview_heading' => $pfstview['heading'],
                'pfstview_pitch' => $pfstview['pitch'],
                'street_view_height' => $street_view_height,
                'streetview_status' => $streetview_status
            ));

            wp_register_script($this->plugin_name.'singlescripts', PFCOREELEMENTSURLPUBLIC . 'js/pointfindercoreelements-single-public.js', array( 'jquery' ), $this->version, true);
            wp_enqueue_script($this->plugin_name.'singlescripts');
        }
    }


    private function pf_get_term_top_most_parent_special($parent, $taxonomy)
    {
        if (!empty($parent)) {
            $k = 1;
            $found_parent  = get_term_by('id', $parent, $taxonomy);

            if ($found_parent->parent != 0) {
                $k = 2;
                $found_parent = $this->pf_get_term_top_most_parent_special($found_parent->parent, $taxonomy);
            }
            if ($k == 2) {
                return array('parent'=>$found_parent['parent'], 'level'=>$k);
            } else {
                return array('parent'=>$found_parent->term_id, 'level'=>$k);
            }
        }

        return '';
        
    }

    private function pf_get_item_term_id_special()
    {
        foreach ($this->item_terms as $item_term) {
            if ($item_term->parent == 0) {
                $item_term_id = $item_term->term_id;
                return $item_term_id;
                break;
            }else{
                $parent_termik = $this->pf_get_term_top_most_parent_special($item_term->parent, 'pointfinderltypes');
                return (isset($parent_termik['parent']))?$parent_termik['parent']:'';
            }
        }
    }

    public function pointfinder_single_listing_output($content)
    {
        if (!is_single()) {
            return;
        }

        $post_type = get_post_type();
        $this->post_type = $post_type;

        if ($post_type != $this->post_type_name) {
            return;
        }


        $post_id = get_the_id();
        $this->post_id = $post_id;
        $this->item_terms = get_the_terms($post_id, 'pointfinderltypes');

        $post_status = get_post_status();

        if ($post_status != 'publish') {
            return $this->PFPageNotFound();
        }


        $item_old_count = get_post_meta( $post_id, 'webbupointfinder_page_itemvisitcount', true );
        
        if (empty($item_old_count)) {
          $item_old_count = 1;
        }

        echo '<div itemscope itemtype="https://schema.org/Product">';
        $item_term = $this->pf_get_item_term_id_special();


        $setup4_membersettings_dashboard = $this->PFSAIssetControl('setup4_membersettings_dashboard', '', '');
        $setup3_modulessetup_headersection = $this->PFSAIssetControl('setup3_modulessetup_headersection', '', 1);
        $viewcount_hideshow = $this->PFSAIssetControl('viewcount_hideshow', '', 1);

        $st8_nasys = $this->PFASSIssetControl('st8_nasys', '', 0);

        if ($st8_nasys == 1) {
            $this->listing_config_meta = get_option('pointfinderltypes_aslvars');
        }

        if (!empty($item_term)) {
            if ($st8_nasys == 1) {
                
                if (isset($this->listing_config_meta[$item_term])) {
                    if (!empty($this->listing_config_meta[$item_term]['pflt_advanced_status'])) {
                        $setup3_modulessetup_headersection = isset($this->listing_config_meta[$item_term]['pflt_headersection'])?$this->listing_config_meta[$item_term]['pflt_headersection']:'';
                    }
                }
            } else {
                $advanced_status = $this->PFADVIssetControl('setupadvancedconfig_'.$item_term.'_advanced_status', '', '0');
                if ($advanced_status == 1) {
                    $setup3_modulessetup_headersection = $this->PFADVIssetControl('setupadvancedconfig_'.$item_term.'_headersection', '', '2');
                }
            }
        }

        $setup3_modulessetup_breadcrumbs = $this->PFSAIssetControl('setup3_modulessetup_breadcrumbs', '', '1');

        if ($setup3_modulessetup_headersection == 1) {
            echo '<section role="itempagemapheader" class="pf-itempage-mapheader">';
            echo '<div class="pfheaderbarshadow2"></div>';
            echo '<div id="item-map-page"></div>';
            echo '</section>';
            if ($setup3_modulessetup_breadcrumbs == 1) {
                echo '<div class="pf-fullwidth pf-itempage-br-xm"><div class="pf-container"><div class="pf-row"><div class="col-lg-12">';
                echo '<div class="pf-breadcrumbs pf-breadcrumbs-special">'.$this->pf_the_breadcrumb().'</div></div></div></div></div>';
            }
        } elseif ($setup3_modulessetup_headersection == 0) {
            if (function_exists('PFGetDefaultPageHeader')) {
                PFGetDefaultPageHeader(array('itemname' => get_the_title(), 'itemaddress' => get_post_meta($post_id, 'webbupointfinder_items_address', true)));
            }
        } elseif ($setup3_modulessetup_headersection == 3) {
            $header_image = get_post_meta($post_id, 'webbupointfinder_item_headerimage', true);

            $header_image_url = $header_image_width = $header_image_height = $postd_text = '';

            if (!empty($header_image['url'])) {
                $header_image_url = $header_image['url'];
                $header_image_width = $header_image['width'];
                $header_image_height = $header_image['height'];
            } else {
                $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');
                $header_image_url = $featured_image[0];
                $header_image_width = $featured_image[1];
                $header_image_height = $featured_image[2];
            }

            $postd_hideshow = $this->PFSAIssetControl('postd_hideshow', '', 1);
            if ($postd_hideshow == 1) {
                $postd_text = ''.esc_html__('Posted on', 'pointfindercoreelements').' '.get_the_time(get_option('date_format'));
                if ($viewcount_hideshow == 1) {
                    $postd_text .= ' /';
                }
            }

            $verified_badge_text = "";

            $setup42_itempagedetails_claim_status = $this->PFSAIssetControl('setup42_itempagedetails_claim_status', '', '0');
            $verified_badge_text = "";

            $listing_verified = get_post_meta($post_id, 'webbupointfinder_item_verified', true);
            if ($setup42_itempagedetails_claim_status == 1 && $listing_verified == 1) {
                $setup42_itempagedetails_claim_validtext = $this->PFSAIssetControl('setup42_itempagedetails_claim_validtext', '', '');
                $verified_badge_text = '<span class="pfverified-bagde-text"> <i class="fas fa-check-circle" style="  color: #59C22F;font-size: 18px;"></i> '.$setup42_itempagedetails_claim_validtext.'</span>';
            }

            if ($viewcount_hideshow == 1) {
                $view_text_output = '<i class="fas fa-eye"></i> '.$item_old_count;
            } else {
                $view_text_output = '';
            }

            echo '<section role="itempageimageheader" class="pf-itempage-imageheader pf-itempage-imageheaderheight" style="background-image: url('.$header_image_url.');">';

            echo '<div class="pf-container clearfix"><div class="pf-row"><div class="col-lg-12">';
            echo '<div class="pf-image-headercapts">';
            echo '
						 <div class="pf-item-title-barimg">
							 <div class="pointfinder-ex-special-fix">
							 <h1 class="pf-item-title-textimg" itemprop="name">'.get_the_title().'</h1>
							 <span class="pf-item-subtitleimg"> '.esc_html(get_post_meta($post_id, 'webbupointfinder_items_address', true)).'</span>
							 </div>';
            do_action('pointfinder_ex_user_special_badge');
            echo '
						 </div>
						 <div class="pf-item-extitlebarimg">
							 <div class="pf-itemdetail-pdateimg">'.$postd_text.' '.$view_text_output.' '.$verified_badge_text.'</div>
						 </div>';
            echo '</div>';
            echo '</div></div></div>';

            echo '<div class="pfheaderbarshadow2 pf-itempage-imageheaderheight"></div>';
            echo '<div class="pfitempageimageheadsh pf-itempage-imageheaderheight"></div>';
            echo '<div id="item-image-page"></div>';


            echo '</section>';
            if ($setup3_modulessetup_breadcrumbs == 1) {
                echo '<div class="pf-fullwidth pf-itempage-br-xm"><div class="pf-container"><div class="pf-row"><div class="col-lg-12">';
                echo '<div class="pf-breadcrumbs pf-breadcrumbs-special">'.$this->pf_the_breadcrumb().'</div></div></div></div></div>';
            }
        } else {
            if ($setup3_modulessetup_breadcrumbs == 1) {
                echo '<div class="pf-fullwidth pf-itempage-br-xm pf-itempage-br-xm-nh"><div class="pf-container"><div class="pf-row"><div class="col-lg-12">';
                echo '<div class="pf-breadcrumbs pf-breadcrumbs-special">'.$this->pf_the_breadcrumb().'</div></div></div></div></div>';
            }
        }

        $setup42_itempagedetails_sidebarpos = $this->PFSAIssetControl('setup42_itempagedetails_sidebarpos', '', '2');

        if (!empty($item_term)) {
            if ($st8_nasys == 1) {
                $pointfinder_customsidebar = '';
                if (isset($this->listing_config_meta[$item_term])) {
                    if (!empty($this->listing_config_meta[$item_term]['pflt_advanced_status'])) {
                        $pointfinder_customsidebar = (isset($this->listing_config_meta[$item_term]['pflt_sidebar']))?$this->listing_config_meta[$item_term]['pflt_sidebar']:'';
                    }
                }
            } else {
                $pointfinder_customsidebar = $this->PFADVIssetControl('setupadvancedconfig_'.$item_term.'_sidebar', '', '');
            }
        } else {
            $pointfinder_customsidebar = '';
        }


        echo '<section role="main" class="pf-itempage-maindiv">';
        echo '<div class="pf-container clearfix">';
        echo '<div class="pf-row clearfix">';
        if ($setup42_itempagedetails_sidebarpos == 2) {
            $this->PFGetItemPageCol1();
            $this->PFGetItemPageCol2($pointfinder_customsidebar);
        } elseif ($setup42_itempagedetails_sidebarpos == 1) {
            $this->PFGetItemPageCol2($pointfinder_customsidebar);
            $this->PFGetItemPageCol1();
        } else {
            $this->PFGetItemPageCol1();
        }
        echo '</div>';
        echo '<div class="pf-row clearfix">';

        $re_li_1 = $this->PFSAIssetControl('re_li_1', '', '1');
        if ($re_li_1 == 1) {
            $re_li_3 = $this->PFSAIssetControl('re_li_3', '', '1');

            $r_post_terms = $r_post_locs = '';
            $r_post_count = 0;

            /*Listing Type Filter*/
            foreach ($this->item_terms as $current_post_term) {
                $r_post_terms = $current_post_term->term_id;
                $r_post_count = $r_post_count + 1;
            }

            /*Location Type Filter*/
            $setup3_pointposttype_pt5_check = $this->PFSAIssetControl('setup3_pointposttype_pt5_check', '', '1');
            if ($setup3_pointposttype_pt5_check == 1 && $re_li_3 == 2) {
                $this->item_locations = get_the_terms($post_id, 'pointfinderlocations');
                if (isset($this->item_locations) && $this->item_locations != false) {
                    foreach ($this->item_locations as $current_post_term_location) {
                        $r_post_locs .= $current_post_term_location->term_id.',';
                    }
                }
            }

            $re_li_2 = $this->PFSAIssetControl('re_li_2', '', '1');


            /*Defaults*/
            $setup22_searchresults_defaultppptype = $this->PFSAIssetControl('setup22_searchresults_defaultppptype', '', '10');
            $setup22_searchresults_defaultsortbytype = $this->PFSAIssetControl('setup22_searchresults_defaultsortbytype', '', 'ID');
            $setup22_searchresults_defaultsorttype = $this->PFSAIssetControl('setup22_searchresults_defaultsorttype', '', 'ASC');
            $setup22_searchresults_background2 = $this->PFSAIssetControl('setup22_searchresults_background2', '', '#f7f7f7');
            $re_li_5 = $this->PFSAIssetControl('re_li_5', '', 20);

            if ($re_li_2 == 1) {
                $relatex_listing_text = '[pf_pfitemcarousel listingtype="'.$r_post_terms.'" itemtype="" locationtype="'.$r_post_locs.'" itemlimit="'.$re_li_5.'" features="" orderby="'.$setup22_searchresults_defaultsortbytype.'" sortby="'.$setup22_searchresults_defaultsorttype.'" cols="4" itemboxbg="'.$setup22_searchresults_background2.'" related="1"]';
            } else {
                $relatex_listing_text = '[pf_itemgrid listingtype="'.$r_post_terms.'" itemtype="" locationtype="'.$r_post_locs.'" features="" orderby="'.$setup22_searchresults_defaultsortbytype.'" sortby="'.$setup22_searchresults_defaultsorttype.'" items="4" cols="4" filters="false" itemboxbg="'.$setup22_searchresults_background2.'" related="1" relatedcpi="'.$post_id.'"]';
            }
            $relatex_listing_text_output = do_shortcode($relatex_listing_text);

            if ($r_post_count > 0 && !empty($relatex_listing_text_output)) {
                echo '
							<div class="col-lg-12">
								<div class="pftrwcontainer hidden-print pf-itempagedetail-element pitempagedetail-relatedlistings">
									<div class="pfitempagecontainerheader">'.esc_html__('Related Listings', 'pointfindercoreelements').'</div>
									<div class="pfmaincontactinfo">
										<section role="itempagedetails" class="pf-itempage-features-block pf-itempage-elements">
											<div class="pf-itempage-features">'.
                                            $relatex_listing_text_output
                                            .'</div>
										</section>
									</div>
								</div>
							</div>
							';
            }
        }

        echo '</div>';
        echo '</div>';
        echo '</section>';
        echo '</div>';
    }
}
