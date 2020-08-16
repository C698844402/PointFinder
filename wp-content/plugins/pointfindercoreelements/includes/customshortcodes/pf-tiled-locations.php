<?php
/*
*
* Visual Composer PointFinder Static Grid Shortcode
*
*/

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

class PointFinderLocationGListShortcode extends WPBakeryShortCode {
  use PointFinderOptionFunctions,
    PointFinderCommonFunctions,
    PointFinderWPMLFunctions,
    PointFinderCommonVCFunctions;

    function __construct() {
        add_action( 'vc_after_init', array( $this, 'pointfinder_single_pftiled_locations_module_mapping' ) );
        add_shortcode( 'pftiled_locations', array( $this, 'pointfinder_single_pftiled_locations_module_html' ) );
    }

    

    public function pointfinder_single_pftiled_locations_module_mapping() {

      if ( !defined( 'WPB_VC_VERSION' ) ) {
          return;
      }

      $setup3_pointposttype_pt5 = $this->PFSAIssetControl('setup3_pointposttype_pt5','','Locations');
      $setup22_searchresults_background2 = $this->PFSAIssetControl('setup22_searchresults_background2','','#fafafa');
      
      $PFVEX_GetTaxValues3 = $this->PFVEX_GetTaxValues('pointfinderlocations','setup3_pointposttype_pt5','Locations');

      /**
    *Start : PF Tiled Locations ----------------------------------------------------------------------------------------------------
    **/

      $PFVEXFields_tiledlt = array();
      $PFVEXFields_tiledlt['name'] = esc_html__("PF Locations Grid", 'pointfindercoreelements');
      $PFVEXFields_tiledlt['base'] = "pftiled_locations";
      $PFVEXFields_tiledlt['class'] = "pfa-tilesloc";
      $PFVEXFields_tiledlt['controls'] = "full";
      $PFVEXFields_tiledlt['icon'] = "pfaicon-th";
      $PFVEXFields_tiledlt['category'] = "Point Finder";
      $PFVEXFields_tiledlt['description'] = esc_html__("Grid Locations", 'pointfindercoreelements');
      $PFVEXFields_tiledlt['params'] = "";
      $PFVEXFields_tiledlt['params'] = array(
        array(
          "type" => "pfa_select2",
          "heading" => $setup3_pointposttype_pt5,
          "param_name" => "location",
          "value" => $PFVEX_GetTaxValues3,
          "admin_label" => true
        ),

        );



      array_push($PFVEXFields_tiledlt['params'],

        array(
          "type" => "dropdown",
          "heading" => esc_html__("Order by", "pointfindercoreelements"),
          "param_name" => "orderby",
          "value" => array(esc_html__("Title", "pointfindercoreelements")=>'name',esc_html__("ID", "pointfindercoreelements")=>'id'),
          "description" => esc_html__("Please select an order by filter.", "pointfindercoreelements"),
          "edit_field_class" => 'vc_col-sm-6'
        ),
        array(
          "type" => "dropdown",
          "heading" => esc_html__("Order", "pointfindercoreelements"),
          "param_name" => "sortby",
          "value" => array(esc_html__("ASC", "pointfindercoreelements")=>'ASC',esc_html__("DESC", "pointfindercoreelements")=>'DESC'),
          "description" => esc_html__("Please select an order filter.", "pointfindercoreelements"),
          "edit_field_class" => 'vc_col-sm-6 vc_column'
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Columns", "pointfindercoreelements"),
            "param_name" => "cols",
            "description" => esc_html__("Column selection for grid list.", "pointfindercoreelements"),
            "value" => array('4 Columns'=>'grid4','2 Columns'=>'grid2','3 Columns'=>'grid3'),
            "edit_field_class" => 'vc_col-sm-6'
        ),
        array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Location Limit', 'pointfindercoreelements' ),
          'param_name' => 'itemlimit',
          'value' => '20',
          'description' => esc_html__( 'Location limit for select all option.', 'pointfindercoreelements' ),
          "edit_field_class" => 'vc_col-sm-6 vc_column'
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Output Style", "pointfindercoreelements"),
            "param_name" => "style",
            "description" => esc_html__("Masonry or Grid output styles.", "pointfindercoreelements"),
            "value" => array('Grid List'=>'grid','Tiled Masonry List'=>'masonry'),
            "edit_field_class" => 'vc_col-sm-6'
        ),
        array(
            "type" => "colorpicker",
            "heading" => esc_html__("Text Color", 'pointfindercoreelements'),
            "param_name" => "textcolor",
            "value" => $setup22_searchresults_background2,
            "description" => esc_html__("Item box area background color of the grid listing area. Optional", 'pointfindercoreelements'),
            "edit_field_class" => 'vc_col-sm-6 vc_column'
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Animation", "pointfindercoreelements"),
            "param_name" => "effect",
            "description" => esc_html__("Animation for mouse over.", "pointfindercoreelements"),
            "value" => array('Effect 1'=>'1','Effect 2'=>'2','Effect 3'=>'3'),
            "edit_field_class" => 'vc_col-sm-6'
        ),
        array(
              'type' => 'checkbox',
              'heading' => esc_html__( 'Show only parent', 'pointfindercoreelements' ),
              'param_name' => 'showparent',
              'value' => array( esc_html__( 'Yes, please', 'pointfindercoreelements' ) => 'yes' ),
              "edit_field_class" => 'vc_col-sm-6'
        )
      );


      vc_map($PFVEXFields_tiledlt);

    /**
    *End : PF Tiled Locations ----------------------------------------------------------------------------------------------------
    **/

    }


    public function pointfinder_single_pftiled_locations_module_html( $atts ) {
      extract( shortcode_atts( array(
        'location' => '',
        'cols' => 'grid4',
        'textcolor' => '#fff',
        'itemlimit' => '20',
        'orderby' => 'name',
        'sortby' => 'ASC',
        'style' => 'grid',
        'effect' => 1,
        'showparent' => ''
      ), $atts ) );


          $output = $image = $iconimage = $term_icon_area = $textcolor_style = $background_effect = '';

          if (!empty($effect)) {
            switch ($effect) {case 1:$background_effect = 'BackgroundRR';case 2:$background_effect = 'BackgroundRS';break;case 3:$background_effect = 'BackgroundS';break;}
          }else{
            $background_effect = 'BackgroundRR';
          }

          if (!empty($textcolor)) {
              $textcolor_style = ' style="color:'.$textcolor.'"';
          }

          $listing_meta = get_option('pointfinderlocationsex2_vars');

          if (!is_array($location) && !empty($location)) {
              $locations = pfstring2BasicArray($location,',');
          }elseif(empty($location)){
              $locations = get_terms(array(
                  'taxonomy' => 'pointfinderlocations',
                  'orderby' => $orderby,
                  'order' => $sortby,
                  'hide_empty' => false,
                  'number' => $itemlimit,
                  'fields' => 'ids',
                  'parent' => (!empty($showparent))? 0 : ''
              ));
          }


        $template_directory_uri = get_template_directory_uri();
        $general_crop2 = $this->PFSizeSIssetControl('general_crop2','',1);
        $general_retinasupport = $this->PFSAIssetControl('general_retinasupport','','0');
        if($general_retinasupport == 1){$pf_retnumber = 2;}else{$pf_retnumber = 1;}
        $gridrandno_orj = PF_generate_random_string_ig();

        if($style == 'grid'){
            $setupsizelimitconf_general_gridsize2_height = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize2','height',416);
            $setupsizelimitconf_general_gridsize2_width = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize2','width',555);
            $setupsizelimitconf_general_gridsize3_height = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize3','height',270);
            $setupsizelimitconf_general_gridsize3_width = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize3','width',360);
            $setupsizelimitconf_general_gridsize4_width = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize4','width',263);
            $setupsizelimitconf_general_gridsize4_height = $this->PFSizeSIssetControl('setupsizelimitconf_general_gridsize4','height',197);

            switch($cols){
                case 'grid2':
                    $pfgrid_output = 'pf2col';
                    $pfgridcol_output = 'col-lg-6 col-md-6 col-sm-6';
                    $featured_image_width = $setupsizelimitconf_general_gridsize2_width*$pf_retnumber;
                    $featured_image_height = $setupsizelimitconf_general_gridsize2_height*$pf_retnumber;
                    break;
                case 'grid3':
                    $pfgrid_output = 'pf3col';
                    $pfgridcol_output = 'col-lg-4 col-md-6 col-sm-6';
                    $featured_image_width = $setupsizelimitconf_general_gridsize3_width*$pf_retnumber;
                    $featured_image_height = $setupsizelimitconf_general_gridsize3_height*$pf_retnumber;
                    break;
                case 'grid4':
                    $pfgrid_output = 'pf4col';
                    $pfgridcol_output = 'col-lg-3 col-md-4 col-sm-6';
                    $featured_image_width = $setupsizelimitconf_general_gridsize4_width*$pf_retnumber;
                    $featured_image_height = $setupsizelimitconf_general_gridsize4_height*$pf_retnumber;
                    break;
                default:
                    $pfgrid_output = 'pf4col';
                    $pfgridcol_output = 'col-lg-3 col-md-4 col-sm-6';
                    $featured_image_width = $setupsizelimitconf_general_gridsize4_width*$pf_retnumber;
                    $featured_image_height = $setupsizelimitconf_general_gridsize4_height*$pf_retnumber;
                break;
            }


            $output .= '<ul class="pf-tiled-term-gallery '.$gridrandno_orj.' pfitemlists-content-elements '.$pfgrid_output.'">';
                foreach ($locations as $location_single) {
                    $term_info = get_term_by( 'id', $location_single, 'pointfinderlocations');
              $iconimage = $term_icon_area = '';
                    $term_count = '';

                    if ($term_info != false) {
                      if ($term_info->count > 1) {
                          $term_count = $term_info->count .' '. esc_html__( 'Listings', 'pointfindercoreelements' );
                      }elseif($term_info->count == 1){
                          $term_count = $term_info->count .' '. esc_html__( 'Listing', 'pointfindercoreelements' );
                      }
                      $this_term_icon = (isset($listing_meta[$location_single]['pf_icon_of_listing']))? $listing_meta[$location_single]['pf_icon_of_listing']:'';
                      $this_term_iconfont = (isset($listing_meta[$location_single]['pf_icon_of_listingfs']))? $listing_meta[$location_single]['pf_icon_of_listingfs']:'';
                      $img_arr = $this->pointfinder_featured_image_getresized_ex($listing_meta,$location_single,$template_directory_uri,$general_crop2,$general_retinasupport,$featured_image_width,$featured_image_height);

                      if (!empty($img_arr['featured_image'])) {

                          $term_link = get_term_link($term_info->slug,'pointfinderlocations');
                          if (isset($this_term_icon[0])) {
                              $iconimage = wp_get_attachment_image_src($this_term_icon[0], 'full');
                          }

                          if (!empty($this_term_iconfont)) {
                            $term_icon_area = '<div class="pf-main-term-icon-ct"><i class="'.$this_term_iconfont.'"></i></div> ';
                          }else{
                            if (isset($iconimage[0])) {
                                $term_icon_area = '<div class="pf-main-term-icon-ct"><img src="'.$iconimage[0].'"></div>';
                            }
                          }

                          $output .= '<li class="pf-tiled-gallery-image pfgallery-item animated fadeIn '.$pfgridcol_output.'">';
                          $output .= '<div class="termgutter-sizer"><a href="'.$term_link.'">';
                              $output .= ''.$term_icon_area.'<div class="PXXImageWrapper '.$background_effect.'"><img src="'.$img_arr['featured_image'].'" alt=""/><div class="ImageOverlayTi"></div><div class="ImageOverlayTix"></div><div class="pf-tiled-gallery-ctname"'.$textcolor_style.'>'.$term_info->name.'</div><div class="pf-tiled-gallery-ctcount"'.$textcolor_style.'>'.$term_count.'</div></div>';
                          $output .= '</a></div>';
                          $output .= '</li>';
                      }
                    }
                }
            $output .= '</ul>';

            $output .= '<script>
            (function($) {
            "use strict";
                $(function() {
                    $(".'.$gridrandno_orj.'").isotope({
                      layoutMode: "fitRows",
                      fitRows: {
                         gutter: 0
                      },
                      itemSelector: ".'.$gridrandno_orj.' .pfgallery-item"
                    });
                });
            })(jQuery);
            </script>
            ';


        }else{


          $output .= '<ul class="pf-tiled-term-gallery pfmasonry '.$gridrandno_orj.'">';
            foreach ($locations as $location_single) {
              $term_info = get_term_by( 'id', $location_single, 'pointfinderlocations');
              $iconimage = $term_icon_area = '';
              if ($term_info->count > 1) {
                $term_count = $term_info->count .' '. esc_html__( 'Listings', 'pointfindercoreelements' );
              }elseif($term_info->count = 1){
                $term_count = $term_info->count .' '. esc_html__( 'Listing', 'pointfindercoreelements' );
              }else{
                $term_count = '';
              }
              $this_term_icon = (isset($listing_meta[$location_single]['pf_icon_of_listing']))? $listing_meta[$location_single]['pf_icon_of_listing']:'';

              $this_masonry_size = (isset($listing_meta[$location_single]['pf_masonry_size']))? $listing_meta[$location_single]['pf_masonry_size']:'';

              if (isset($this_masonry_size[0])) {
                $this_masonry_size = $this_masonry_size[0];
              }

              switch ($this_masonry_size) {
                case 'l':
                  $class_of_this = 'pfmsgrid-item--large';
                  $img_arr = $this->pointfinder_featured_image_getresized_ex($listing_meta,$location_single,$template_directory_uri,1,0,564,564);
                  break;

                case 'w':
                  $class_of_this = 'pfmsgrid-item--medium';
                  $img_arr = $this->pointfinder_featured_image_getresized_ex($listing_meta,$location_single,$template_directory_uri,1,0,564,277);
                  break;

                default:
                  $class_of_this = 'pfmsgrid-item--box';
                  $img_arr = $this->pointfinder_featured_image_getresized_ex($listing_meta,$location_single,$template_directory_uri,1,0,277,277);
                  break;
              }

              $this_term_iconfont = (isset($listing_meta[$location_single]['pf_icon_of_listingfs']))? $listing_meta[$location_single]['pf_icon_of_listingfs']:'';
             
              if (!empty($img_arr['featured_image'])) {

                $term_link = get_term_link($term_info->slug,'pointfinderlocations');
                if (isset($this_term_icon[0])) {
                  $iconimage = wp_get_attachment_image_src($this_term_icon[0], 'full');
                }

                if (!empty($this_term_iconfont)) {
                  $term_icon_area = '<div class="pf-main-term-icon-ct"><i class="'.$this_term_iconfont.'"></i></div> ';
                }else{
                  if (isset($iconimage[0])) {
                    $term_icon_area = '<div class="pf-main-term-icon-ct"><img src="'.$iconimage[0].'"></div>';
                 }
                
                }
                

                $output .= '<li class="pf-tiled-gallery-image pfgallery-item animated fadeIn pfmsgrid-item '.$class_of_this.'">';
                $output .= '<div class="termgutter-sizer"><a href="'.$term_link.'">';
                  $output .= ''.$term_icon_area.'<div class="PXXImageWrapper '.$background_effect.'"><img src="'.$img_arr['featured_image'].'" alt=""/><div class="ImageOverlayTi"></div><div class="ImageOverlayTix"></div><div class="pf-tiled-gallery-ctname"'.$textcolor_style.'>'.$term_info->name.'</div><div class="pf-tiled-gallery-ctcount"'.$textcolor_style.'>'.$term_count.'</div></div>';
                $output .= '</a></div>';
                $output .= '</li>';
              }
            }
          $output .= '</ul>';

          $output .= '<script>
          (function($) {
            "use strict";
              $(function() {
              var $grid = $(".'.$gridrandno_orj.'").masonry({
                itemSelector: ".'.$gridrandno_orj.' .pfgallery-item",
                layoutMode: "masonry",
                columnWidth: ".'.$gridrandno_orj.' .pfmsgrid-item--box",
                gutter: 10,
              });

              $grid.imagesLoaded().progress( function() {
                $grid.masonry("layout");
              });
            });
          })(jQuery);
          </script>
          ';
        }

        return $output;

    }

}
new PointFinderLocationGListShortcode();

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_pointfinder_single_pftiled_locations extends WPBakeryShortCode {
    }
}