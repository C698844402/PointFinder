<?php

/*
*
* Visual Composer PointFinder Static Grid Shortcode
*
*/

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

class PointFinderGridShortcodesAJAX extends WPBakeryShortCode {
    use PointFinderOptionFunctions,
    PointFinderCommonFunctions,
    PointFinderWPMLFunctions,
    PointFinderReviewFunctions,
    PointFinderCommonVCFunctions;

    function __construct() {
        add_action( 'vc_after_init', array( $this, 'pointfinder_single_pf_itemgrid_module_mapping' ) );
        add_action( 'vc_after_init', array( $this, 'pointfinder_single_pf_itemgrid_module_mapping2' ) );
        add_shortcode( 'pf_itemgrid', array( $this, 'pointfinder_single_pf_itemgrid_module_html' ) );
        add_shortcode( 'pf_itemgrid2', array( $this, 'pointfinder_single_pf_itemgrid_module_html' ) );
    }

    

    public function pointfinder_single_pf_itemgrid_module_mapping() {

      if ( !defined( 'WPB_VC_VERSION' ) ) {
          return;
      }

      $setup3_pointposttype_pt7 = $this->PFSAIssetControl('setup3_pointposttype_pt7','','Listing Types');
      $setup3_pointposttype_pt6 = $this->PFSAIssetControl('setup3_pointposttype_pt6','','Features');
      $setup3_pointposttype_pt5 = $this->PFSAIssetControl('setup3_pointposttype_pt5','','Locations');
      $setup3_pointposttype_pt4 = $this->PFSAIssetControl('setup3_pointposttype_pt4','','Item Types');
      $setup3_pt14 = $this->PFSAIssetControl('setup3_pt14','','Conditions');
      $setup3_pt14_check = $this->PFSAIssetControl('setup3_pt14_check','','0');

      //Check taxonomies
      $setup3_pointposttype_pt4_check = $this->PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
      $setup3_pointposttype_pt5_check = $this->PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
      $setup3_pointposttype_pt6_check = $this->PFSAIssetControl('setup3_pointposttype_pt6_check','','1');
      $setup3_pointposttype_pt6_status = $this->PFSAIssetControl('setup3_pointposttype_pt6_status','','1');

      //Default grid settings from admin
      $setup22_searchresults_background = $this->PFSAIssetControl('setup22_searchresults_background','','#ffffff');
      $setup22_searchresults_headerbackground = $this->PFSAIssetControl('setup22_searchresults_headerbackground','','#fafafa');
      $setup22_searchresults_background2 = $this->PFSAIssetControl('setup22_searchresults_background2','','#fafafa');


      $PFVEX_GetTaxValues1 = $this->PFVEX_GetTaxValues('pointfinderltypes','setup3_pointposttype_pt7','Listing Types');
      $PFVEX_GetTaxValues2 = $this->PFVEX_GetTaxValues('pointfinderitypes','setup3_pointposttype_pt4','Item Types');
      $PFVEX_GetTaxValues3 = $this->PFVEX_GetTaxValues('pointfinderlocations','setup3_pointposttype_pt5','Locations');
      $PFVEX_GetTaxValues4 = $this->PFVEX_GetTaxValues('pointfinderfeatures','setup3_pointposttype_pt6','Features');
      $PFVEX_GetTaxValues5 = $this->PFVEX_GetTaxValues('pointfinderconditions','setup3_pt14','Conditions');
        

      /**
      *Start : Item Grid AJAX ----------------------------------------------------------------------------------------------------
      **/
        $PFVEXFields_ItemGrid = array();
        $PFVEXFields_ItemGrid['name'] = esc_html__("PF Listing Grid", 'pointfindercoreelements');
        $PFVEXFields_ItemGrid['base'] = "pf_itemgrid";
        $PFVEXFields_ItemGrid['class'] = "pfa-itemgrid";
        $PFVEXFields_ItemGrid['controls'] = "full";
        $PFVEXFields_ItemGrid['icon'] = "pfaicon-th";
        $PFVEXFields_ItemGrid['deprecated'] = true;
        $PFVEXFields_ItemGrid['description'] = esc_html__("Point Finder item grid with AJAX.", 'pointfindercoreelements');
        $PFVEXFields_ItemGrid['params'] = "";
        $PFVEXFields_ItemGrid['params'] = array(
          array(
            "type" => "pfa_select2",
            "heading" => $setup3_pointposttype_pt7,
            "param_name" => "listingtype",
            "value" => $PFVEX_GetTaxValues1,
            "description"=>esc_html__('Leave empty for select all.','pointfindercoreelements'),
            "admin_label" => true
          ),

          );


        if($setup3_pointposttype_pt4_check == 1){
          array_push($PFVEXFields_ItemGrid['params'],
            array(
              "type" => "pfa_select2",
              "heading" => $setup3_pointposttype_pt4,
              "param_name" => "itemtype",
              "value" => $PFVEX_GetTaxValues2,
              "description"=>esc_html__('Leave empty for select all.','pointfindercoreelements'),
              "admin_label" => true
            )
          );
        }

        if($setup3_pointposttype_pt5_check == 1){
          array_push($PFVEXFields_ItemGrid['params'],
            array(
              "type" => "pfa_select2",
              "heading" => $setup3_pointposttype_pt5,
              "param_name" => "locationtype",
              "value" => $PFVEX_GetTaxValues3,
              "description"=>esc_html__('Leave empty for select all.','pointfindercoreelements'),
              "admin_label" => true
            )
          );
        }

        if($setup3_pointposttype_pt6_check == 1){
          array_push($PFVEXFields_ItemGrid['params'],
            array(
              "type" => "pfa_select2",
              "heading" => $setup3_pointposttype_pt6,
              "param_name" => "features",
              "value" => $PFVEX_GetTaxValues4,
              "description"=>esc_html__('Leave empty for select all.','pointfindercoreelements'),
              "admin_label" => true
            )
          );
        }

        if($setup3_pt14_check == 1){
          array_push($PFVEXFields_ItemGrid['params'],
            array(
              "type" => "pfa_select2",
              "heading" => $setup3_pt14,
              "param_name" => "conditions",
              "value" => $PFVEX_GetTaxValues5,
              "description"=>esc_html__('Leave empty for select all.','pointfindercoreelements'),
              "admin_label" => true
            )
          );
        }

        array_push($PFVEXFields_ItemGrid['params'],
          //If possible add custom fields filter on here.
          array(
            "type" => "dropdown",
            "heading" => esc_html__("Order by", "pointfindercoreelements"),
            "param_name" => "orderby",
            "value" => array(esc_html__('A-Z','pointfindercoreelements')=>'title_az',esc_html__('Z-A','pointfindercoreelements')=>'title_za',esc_html__('Newest','pointfindercoreelements')=>'date_az',esc_html__('Oldest','pointfindercoreelements')=>'date_za',esc_html__('Random', 'pointfindercoreelements')=>'rand',esc_html__('Nearby','pointfindercoreelements')=>'nearby',esc_html__('Most Viewed','pointfindercoreelements')=>'mviewed',esc_html__('Highest Rated','pointfindercoreelements')=>'reviewcount_az',esc_html__('Lowest Rated','pointfindercoreelements')=>'reviewcount_za'),
            "description" => esc_html__("Please select an order by filter.", "pointfindercoreelements"),
            "edit_field_class" => 'vc_col-sm-6'

          ),
          array(
              "type" => "colorpicker",
              "heading" => esc_html__("Item Box Area Background", 'pointfindercoreelements'),
              "param_name" => "itemboxbg",
              "value" => $setup22_searchresults_background2,
              "description" => esc_html__("Item box area background color of the grid listing area. Optional", 'pointfindercoreelements'),
              "edit_field_class" => 'vc_col-sm-6 vc_column'
          ),
          array(
                "type" => "textfield",
                "heading" => esc_html__("Item IDs", "pointfindercoreelements"),
                "param_name" => "posts_in",
                "description" => esc_html__('Fill this field with items ID numbers separated by commas (,), to retrieve only them. Ex: 171,172,173 (Optional) This option will show only selected items.', "pointfindercoreelements")
             ),
          array(
            "type" => "dropdown",
            "heading" => esc_html__("Items Per Page", "pointfindercoreelements"),
            "param_name" => "items",
            "value" => array(4=>4,6=>6,8=>8,12=>12,15=>15,16=>16,18=>18,21=>21,24=>24,25=>25,50=>50,75=>75),
            "description" => esc_html__("How many items would you like to display per page?", "pointfindercoreelements"),
            "edit_field_class" => 'vc_col-sm-6 vc_column'

          ),
          array(
              "type" => "dropdown",
              "heading" => esc_html__("Default Listing Columns", "pointfindercoreelements"),
              "param_name" => "cols",
              "value" => array('4 Columns'=>'4','2 Columns'=>'2','3 Columns'=>'3','1 Column'=>'1'),
              "description" => esc_html__("Please choose default column number for this grid.", "pointfindercoreelements"),
              "edit_field_class" => 'vc_col-sm-6 vc_column'

          ),
          array(
                "type" => "dropdown",
                "heading" => esc_html__("Layout mode", "pointfindercoreelements"),
                "param_name" => "grid_layout_mode",
                "value" => array(esc_html__("Fit rows", "pointfindercoreelements") => "fitRows", esc_html__('Masonry', "pointfindercoreelements") => 'masonry'),
                "description" => esc_html__("Gridview layout template.", "pointfindercoreelements"),
                "edit_field_class" => 'vc_col-sm-6 vc_column'
              ),
          array(
              "type" => "dropdown",
              "heading" => esc_html__("Enable filters on grid header?", "pointfindercoreelements"),
              "param_name" => "filters",
              "value" => array(esc_html__("Yes", "pointfindercoreelements")=>'true',esc_html__("No", "pointfindercoreelements")=>'false'),
              "description" => esc_html__("This function will enable grid filtering (Sortby / Order etc..)", "pointfindercoreelements"),
              "edit_field_class" => 'vc_col-sm-6 vc_column'

          ),
          array(
              "type" => 'checkbox',
              "heading" => esc_html__("Only show featured items", "pointfindercoreelements"),
              "param_name" => "featureditems",
              "description" => esc_html__("Enables featured items and hide another items on query.", "pointfindercoreelements"),
              "value" => array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
              "edit_field_class" => 'vc_col-sm-6 vc_column'
            ),
          
          array(
              "type" => 'checkbox',
              "heading" => esc_html__("HIDE featured items", "pointfindercoreelements"),
              "param_name" => "featureditemshide",
              "description" => esc_html__("Disable featured items and show another items on query. You can not use with Only show featured items", "pointfindercoreelements"),
              "value" => array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
              "edit_field_class" => 'vc_col-sm-6'
            ),
          
          array(
              "type" => "pf_info_line_field",
              "param_name" => "pf_info_field5",
             )
        );
        vc_map($PFVEXFields_ItemGrid);
      /**
      *End : Item Grid AJAX
      **/


    }

    public function pointfinder_single_pf_itemgrid_module_mapping2() {

      if ( !defined( 'WPB_VC_VERSION' ) ) {
          return;
      }

      $setup3_pointposttype_pt7 = $this->PFSAIssetControl('setup3_pointposttype_pt7','','Listing Types');
      $setup3_pointposttype_pt6 = $this->PFSAIssetControl('setup3_pointposttype_pt6','','Features');
      $setup3_pointposttype_pt5 = $this->PFSAIssetControl('setup3_pointposttype_pt5','','Locations');
      $setup3_pointposttype_pt4 = $this->PFSAIssetControl('setup3_pointposttype_pt4','','Item Types');
      $setup3_pt14 = $this->PFSAIssetControl('setup3_pt14','','Conditions');
      $setup3_pt14_check = $this->PFSAIssetControl('setup3_pt14_check','','0');

      //Check taxonomies
      $setup3_pointposttype_pt4_check = $this->PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
      $setup3_pointposttype_pt5_check = $this->PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
      $setup3_pointposttype_pt6_check = $this->PFSAIssetControl('setup3_pointposttype_pt6_check','','1');
      $setup3_pointposttype_pt6_status = $this->PFSAIssetControl('setup3_pointposttype_pt6_status','','1');

      //Default grid settings from admin
      $setup22_searchresults_background = $this->PFSAIssetControl('setup22_searchresults_background','','#ffffff');
      $setup22_searchresults_headerbackground = $this->PFSAIssetControl('setup22_searchresults_headerbackground','','#fafafa');
      $setup22_searchresults_background2 = $this->PFSAIssetControl('setup22_searchresults_background2','','#fafafa');


      $PFVEX_GetTaxValues1 = $this->PFVEX_GetTaxValues('pointfinderltypes','setup3_pointposttype_pt7','Listing Types');
      $PFVEX_GetTaxValues2 = $this->PFVEX_GetTaxValues('pointfinderitypes','setup3_pointposttype_pt4','Item Types');
      $PFVEX_GetTaxValues3 = $this->PFVEX_GetTaxValues('pointfinderlocations','setup3_pointposttype_pt5','Locations');
      $PFVEX_GetTaxValues4 = $this->PFVEX_GetTaxValues('pointfinderfeatures','setup3_pointposttype_pt6','Features');
      $PFVEX_GetTaxValues5 = $this->PFVEX_GetTaxValues('pointfinderconditions','setup3_pt14','Conditions');
        

      /**
      *Start : Item Grid AJAX ----------------------------------------------------------------------------------------------------
      **/
        $PFVEXFields_ItemGrid = array();
        $PFVEXFields_ItemGrid['name'] = esc_html__("PF Listing Grid", 'pointfindercoreelements');
        $PFVEXFields_ItemGrid['base'] = "pf_itemgrid2";
        $PFVEXFields_ItemGrid['class'] = "pfa-itemgrid";
        $PFVEXFields_ItemGrid['controls'] = "full";
        $PFVEXFields_ItemGrid['icon'] = "pfaicon-th";
        $PFVEXFields_ItemGrid['category'] = "Point Finder";
        $PFVEXFields_ItemGrid['description'] = esc_html__("Point Finder item grid with AJAX.", 'pointfindercoreelements');
        $PFVEXFields_ItemGrid['params'] = "";
        $PFVEXFields_ItemGrid['params'] = array(
          array(
            "type" => "pfa_select2",
            "heading" => $setup3_pointposttype_pt7,
            "param_name" => "listingtype",
            "value" => $PFVEX_GetTaxValues1,
            "description"=>esc_html__('Leave empty for select all.','pointfindercoreelements'),
            "admin_label" => true
          ),

          );


        if($setup3_pointposttype_pt4_check == 1){
          array_push($PFVEXFields_ItemGrid['params'],
            array(
              "type" => "pfa_select2",
              "heading" => $setup3_pointposttype_pt4,
              "param_name" => "itemtype",
              "value" => $PFVEX_GetTaxValues2,
              "description"=>esc_html__('Leave empty for select all.','pointfindercoreelements'),
              "admin_label" => true
            )
          );
        }

        if($setup3_pointposttype_pt5_check == 1){
          array_push($PFVEXFields_ItemGrid['params'],
            array(
              "type" => "pfa_select2",
              "heading" => $setup3_pointposttype_pt5,
              "param_name" => "locationtype",
              "value" => $PFVEX_GetTaxValues3,
              "description"=>esc_html__('Leave empty for select all.','pointfindercoreelements'),
              "admin_label" => true
            )
          );
        }

        if($setup3_pointposttype_pt6_check == 1){
          array_push($PFVEXFields_ItemGrid['params'],
            array(
              "type" => "pfa_select2",
              "heading" => $setup3_pointposttype_pt6,
              "param_name" => "features",
              "value" => $PFVEX_GetTaxValues4,
              "description"=>esc_html__('Leave empty for select all.','pointfindercoreelements'),
              "admin_label" => true
            )
          );
        }

        if($setup3_pt14_check == 1){
          array_push($PFVEXFields_ItemGrid['params'],
            array(
              "type" => "pfa_select2",
              "heading" => $setup3_pt14,
              "param_name" => "conditions",
              "value" => $PFVEX_GetTaxValues5,
              "description"=>esc_html__('Leave empty for select all.','pointfindercoreelements'),
              "admin_label" => true
            )
          );
        }

        array_push($PFVEXFields_ItemGrid['params'],
          //If possible add custom fields filter on here.
          array(
            "type" => "dropdown",
            "heading" => esc_html__("Order by", "pointfindercoreelements"),
            "param_name" => "orderby",
            "value" => array(esc_html__('A-Z','pointfindercoreelements')=>'title_az',esc_html__('Z-A','pointfindercoreelements')=>'title_za',esc_html__('Newest','pointfindercoreelements')=>'date_az',esc_html__('Oldest','pointfindercoreelements')=>'date_za',esc_html__('Random', 'pointfindercoreelements')=>'rand',esc_html__('Nearby','pointfindercoreelements')=>'nearby',esc_html__('Most Viewed','pointfindercoreelements')=>'mviewed',esc_html__('Highest Rated','pointfindercoreelements')=>'reviewcount_az',esc_html__('Lowest Rated','pointfindercoreelements')=>'reviewcount_za'),
            "description" => esc_html__("Please select an order by filter.", "pointfindercoreelements"),
            "edit_field_class" => 'vc_col-sm-6'

          ),
          array(
              "type" => "colorpicker",
              "heading" => esc_html__("Item Box Area Background", 'pointfindercoreelements'),
              "param_name" => "itemboxbg",
              "value" => $setup22_searchresults_background2,
              "description" => esc_html__("Item box area background color of the grid listing area. Optional", 'pointfindercoreelements'),
              "edit_field_class" => 'vc_col-sm-6 vc_column'
          ),
          array(
                "type" => "textfield",
                "heading" => esc_html__("Item IDs", "pointfindercoreelements"),
                "param_name" => "posts_in",
                "description" => esc_html__('Fill this field with items ID numbers separated by commas (,), to retrieve only them. Ex: 171,172,173 (Optional) This option will show only selected items.', "pointfindercoreelements")
             ),
          array(
            "type" => "dropdown",
            "heading" => esc_html__("Items Per Page", "pointfindercoreelements"),
            "param_name" => "items",
            "value" => array(4=>4,6=>6,8=>8,12=>12,15=>15,16=>16,18=>18,21=>21,24=>24,25=>25,50=>50,75=>75),
            "description" => esc_html__("How many items would you like to display per page?", "pointfindercoreelements"),
            "edit_field_class" => 'vc_col-sm-6 vc_column'

          ),
          array(
              "type" => "dropdown",
              "heading" => esc_html__("Default Listing Columns", "pointfindercoreelements"),
              "param_name" => "cols",
              "value" => array('4 Columns'=>'4','2 Columns'=>'2','3 Columns'=>'3','1 Column'=>'1'),
              "description" => esc_html__("Please choose default column number for this grid.", "pointfindercoreelements"),
              "edit_field_class" => 'vc_col-sm-6 vc_column'

          ),
          array(
                "type" => "dropdown",
                "heading" => esc_html__("Layout mode", "pointfindercoreelements"),
                "param_name" => "grid_layout_mode",
                "value" => array(esc_html__("Fit rows", "pointfindercoreelements") => "fitRows", esc_html__('Masonry', "pointfindercoreelements") => 'masonry'),
                "description" => esc_html__("Gridview layout template.", "pointfindercoreelements"),
                "edit_field_class" => 'vc_col-sm-6 vc_column'
              ),
          array(
              "type" => "dropdown",
              "heading" => esc_html__("Enable filters on grid header?", "pointfindercoreelements"),
              "param_name" => "filters",
              "value" => array(esc_html__("Yes", "pointfindercoreelements")=>'true',esc_html__("No", "pointfindercoreelements")=>'false'),
              "description" => esc_html__("This function will enable grid filtering (Sortby / Order etc..)", "pointfindercoreelements"),
              "edit_field_class" => 'vc_col-sm-6 vc_column'

          ),
          array(
              "type" => 'checkbox',
              "heading" => esc_html__("Only show featured items", "pointfindercoreelements"),
              "param_name" => "featureditems",
              "description" => esc_html__("Enables featured items and hide another items on query.", "pointfindercoreelements"),
              "value" => array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
              "edit_field_class" => 'vc_col-sm-6 vc_column'
            ),
          
          array(
              "type" => 'checkbox',
              "heading" => esc_html__("HIDE featured items", "pointfindercoreelements"),
              "param_name" => "featureditemshide",
              "description" => esc_html__("Disable featured items and show another items on query. You can not use with Only show featured items", "pointfindercoreelements"),
              "value" => array(esc_html__("Yes, please", "pointfindercoreelements") => 'yes'),
              "edit_field_class" => 'vc_col-sm-6'
            ),
          
          array(
              "type" => "pf_info_line_field",
              "param_name" => "pf_info_field5",
             )
        );
        vc_map($PFVEXFields_ItemGrid);
      /**
      *End : Item Grid AJAX
      **/


    }


    public function pointfinder_single_pf_itemgrid_module_html( $atts ) {
        extract( shortcode_atts( array(
          'listingtype' => '',
          'itemtype' => '',
          'conditions' => '',
          'locationtype' => '',
          'posts_in' => '',
          'sortby' => 'ASC',
          'orderby' => 'title',
          'items' => 8,
          'cols' => 4,
          'features'=>array(),
          'filters' => 'true',
          'itemboxbg' => '',
          'grid_layout_mode' => 'fitRows',
          'featureditems'=>'',
          'featureditemshide'=>'',
          'authormode'=>0,
          'agentmode'=>0,
          'author'=>'',
          'related'=> 0,
          'relatedcpi'=> 0,
          'pfrandomize' => '',
          'tag' => ''
          ), $atts ) );

          if (empty($itemboxbg)) {
            $itemboxbg = $this->PFSAIssetControl('setup22_searchresults_background2','','');
          }

          $setup3_pointposttype_pt4_check = $this->PFSAIssetControl('setup3_pointposttype_pt4_check','','1');
          $setup3_pointposttype_pt5_check = $this->PFSAIssetControl('setup3_pointposttype_pt5_check','','1');
          $setup3_pointposttype_pt6_check = $this->PFSAIssetControl('setup3_pointposttype_pt6_check','','1');

          $gridrandno_orj = PF_generate_random_string_ig();
          $gridrandno = 'pf_'.$gridrandno_orj;

          $listingtype_x = $this->PFEX_extract_type_ig($listingtype);
          $itemtype_x = ($setup3_pointposttype_pt4_check == 1) ? $this->PFEX_extract_type_ig($itemtype) : '' ;
          $conditions_x = ($setup3_pointposttype_pt4_check == 1) ? $this->PFEX_extract_type_ig($conditions) : '' ;
          $locationtype_x = ($setup3_pointposttype_pt5_check == 1) ? $this->PFEX_extract_type_ig($locationtype) : '' ;
          $features_x = ($setup3_pointposttype_pt6_check == 1) ? $this->PFEX_extract_type_ig($features) : '' ;
          $pfnonce = wp_create_nonce('pfget_listitems');


          $wpflistdata = "<div class='pflistgridview".$gridrandno_orj."-container pflistgridviewgr-container pflistgridajaxview clearfix' 
          data-gridorj='".$gridrandno_orj."' 
          data-grid='".$gridrandno."' 
          data-sortby='".$sortby."' 
          data-orderby='".$orderby."' 
          data-items='".$items."' 
          data-cols='".$cols."' 
          data-posts_in='".$posts_in."' 
          data-filters='".$filters."' 
          data-itemboxbg='".$itemboxbg."' 
          data-grid_layout_mode='".$grid_layout_mode."' 
          data-listingtype_x='".$listingtype_x."' 
          data-itemtype_x='".$itemtype_x."' 
          data-conditions_x='".$conditions_x."' 
          data-locationtype_x='".$locationtype_x."' 
          data-features_x='".$features_x."' 
          data-featureditems='".$featureditems."' 
          data-featureditemshide='".$featureditemshide."' 
          data-authormode='".$authormode."' 
          data-agentmode='".$agentmode."' 
          data-author='".$author."' 
          data-related='".$related."' 
          data-relatedcpi='".$relatedcpi."' 
          data-tag='".$tag."' 
          data-pfrandomize='".$pfrandomize."' 
          data-nonce='".wp_create_nonce('pfget_listitems')."' 
          data-isrtl='".is_rtl()."' 
          data-page=''
          ></div>";

  return $wpflistdata;
}

}
new PointFinderGridShortcodesAJAX();

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_pointfinder_single_pf_itemgrid extends WPBakeryShortCode {
    }
    class WPBakeryShortCode_pointfinder_single_pf_itemgrid2 extends WPBakeryShortCode {
    }
}