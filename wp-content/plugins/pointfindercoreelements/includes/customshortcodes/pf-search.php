<?php
/*
*
* Visual Composer PointFinder Static Grid Shortcode
*
*/

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

class PointFinderSearchShortcode extends WPBakeryShortCode {

    use PointFinderCommonFunctions;

    function __construct() {
        add_action( 'vc_after_init', array( $this, 'pointfinder_single_pf_searchw_module_mapping' ) );
        add_shortcode( 'pf_searchw', array( $this, 'pointfinder_single_pf_searchw_module_html' ) );
    }

    

    public function pointfinder_single_pf_searchw_module_mapping() {

      if ( !defined( 'WPB_VC_VERSION' ) ) {
          return;
      }

      /**
    *Start : Search ----------------------------------------------------------------------------------------------------
    **/
      vc_map( array(
        "name" => esc_html__("PF Search", 'pointfindercoreelements'),
        "base" => "pf_searchw",
        "icon" => "pfaicon-chat-empty",
        "category" => esc_html__("Point Finder", "pointfindercoreelements"),
        "description" => esc_html__("Search Widget", 'pointfindercoreelements'),
        "params" => array(
            array(
              "type" => "pf_info_line_vc_field",
              "heading" => esc_html__("Please do not try to use Mini Search with other search elements at the same page.", "pointfindercoreelements"),
              "param_name" => "informationfield",
            ),
            array(
              "type" => "pf_info_line_field",
              "param_name" => "pf_info_field1",
             ),
            array(
              "type" => "dropdown",
              "heading" => esc_html__("Search Field Columns", "pointfindercoreelements"),
              "param_name" => "minisearchc",
              "value" => array('1 Column'=>'1','2 Columns'=>'2','3 Columns'=>'3'),
              "edit_field_class" => 'vc_col-sm-6 vc_column'
            ),
            array(
              "type" => "colorpicker",
              "heading" => esc_html__('Container Background Color', 'pointfindercoreelements'),
              "param_name" => "mini_bg_color",
              "description" => esc_html__("Leave empty for use default color. (Optional)", "pointfindercoreelements"),
              "edit_field_class" => 'vc_col-sm-6 vc_column'
              ),
            array(
              "type" => "colorpicker",
              "heading" => esc_html__('Search Button Background Color', 'pointfindercoreelements'),
              "param_name" => "searchbg",
              "description" => esc_html__("Leave empty for use default color. (Optional)", "pointfindercoreelements"),
              "edit_field_class" => 'vc_col-sm-6 vc_column'
              ),
            array(
              "type" => "colorpicker",
              "heading" => esc_html__('Search Button Text Color', 'pointfindercoreelements'),
              "param_name" => "searchtext",
              "description" => esc_html__("Leave empty for use default color. (Optional)", "pointfindercoreelements"),
              "edit_field_class" => 'vc_col-sm-6 vc_column'
              ),

             array(
              "type" => "pfa_numeric",
              "heading" => esc_html__("Container Padding (Top & Bottom)", "pointfindercoreelements"),
              "param_name" => "mini_padding_tb",
              "description" => esc_html__("Please write a padding value (px) (Optional) (Numeric only)", "pointfindercoreelements"),
              "value" => '0',
              "edit_field_class" => 'vc_col-sm-6 vc_column'
              ),
             array(
              "type" => "pfa_numeric",
              "heading" => esc_html__("Container Padding ( Left & Right )", "pointfindercoreelements"),
              "param_name" => "mini_padding_lr",
              "description" => esc_html__("Please write a padding value (px) (Optional) (Numeric only)", "pointfindercoreelements"),
              "value" => '0',
              "edit_field_class" => 'vc_col-sm-6 vc_column'
              ),
             array(
              "type" => "pfa_numeric",
              "heading" => esc_html__("Container Radius", "pointfindercoreelements"),
              "param_name" => "mini_radius",
              "description" => esc_html__("Please write a radius value (px) (Optional) (Numeric only)", "pointfindercoreelements"),
              "value" => '6',
              "edit_field_class" => 'vc_col-sm-6 vc_column'
              )
          )
        )
      );
    /**
    *End : Search ----------------------------------------------------------------------------------------------------
    **/

    }


    public function pointfinder_single_pf_searchw_module_html( $atts ) {
      $output = $title = $number = $el_class = $mini_style = '';
      extract( shortcode_atts( array(
        'minisearchc' => 1,
        'searchbg' => '',
        'searchtext' => '',
        'mini_padding_tb' => 0,
        'mini_padding_lr' => 0,
        'mini_bg_color' => '',
        'mini_radius' => 0
        ), $atts ) );
      
      $coln = '<div class="col-lg-3 col-md-3 col-sm-6 colhorsearch">';

      switch ($minisearchc) {
          case '1':
            $coln = '<div class="col-lg-3 col-md-3 col-sm-6 colhorsearch">';
            break;
          
          case '2':
            $coln = '<div class="col-lg-4 col-md-4 col-sm-6 colhorsearch">';
            break;

          case '3':
            $coln = '<div class="col-lg-3 col-md-3 col-sm-6 colhorsearch">';
            break;

          default:
            $coln = '<div class="col-lg-3 col-md-3 col-sm-6 colhorsearch">';
            break;
      }

      /**
      *START: SEARCH ITEMS WIDGET
      **/  
            $mini_style = " style='";
            if (!empty($mini_bg_color)) {
              $mini_style .= "background-color:".$mini_bg_color.';';
            }
            $mini_style .= "padding: ".$mini_padding_tb."px ".$mini_padding_lr."px;";
            if (!empty($mini_radius)) {
              $mini_style .= "border-radius:".$mini_radius.'px;';
            }
            $mini_style .= "'";
            if ($searchbg != '' && $searchtext != '') {
              $searchb_style = " style='color:".$searchtext."!important;background-color:".$searchbg."!important'";
            } else {
              $searchb_style = "";
            }
            
            ob_start();

              /**
              *Start: Search Form
              **/
              ?>
              <div class="pointfinder-mini-search<?php echo ' pfministyle'.$minisearchc;?>"<?php echo $mini_style;?>>
              <form id="pointfinder-search-form-manual" class="pfminisearch" method="get" action="<?php echo esc_url(home_url("/")); ?>" data-ajax="false">
              <div class="pfsearch-content golden-forms">
              <div class="pf-row">
              <?php 
                $setup1s_slides = PFSAIssetControl('setup1s_slides','','');
                
                if(is_array($setup1s_slides)){
                    
                    /**
                    *Start: Get search data & apply to query arguments.
                    **/

                        $pfgetdata = $_GET;
                        
                        if(is_array($pfgetdata)){
                            
                            $pfformvars = array();
                            
                            foreach ($pfgetdata as $key => $value) {
                                if (!empty($value) && $value != 'pfs') {
                                    $pfformvars[$key] = $value;
                                }
                            }
                            
                            $pfformvars = $this->PFCleanArrayAttr('PFCleanFilters',$pfformvars);

                        }       
                    /**
                    *End: Get search data & apply to query arguments.
                    **/
                    $PFListSF = new PF_SF_Val();
                  
                    foreach ($setup1s_slides as &$value) {

                      $PFListSF->GetValue($value['title'],$value['url'],$value['select'],1,$pfformvars,1,1,$minisearchc);
                        
                    }


                    /*Get Listing Type Item Slug*/
                    $fltf = $this->pointfinder_find_requestedfields('pointfinderltypes');

                    $pfformvars_json = (isset($pfformvars))?json_encode($pfformvars):json_encode(array());
                
                    if (!is_rtl()) {
                      echo $PFListSF->FieldOutput;
                    }
                    echo $coln;
                    echo '<div id="pfsearchsubvalues"></div>';
                    echo '<input type="hidden" name="s" value=""/>';
                    echo '<input type="hidden" name="serialized" value="1"/>';
                    echo '<input type="hidden" name="action" value="pfs"/>';
                    echo '<a class="button pfsearch" id="pf-search-button-manual"'.$searchb_style.'><i class="pfadmicon-glyph-627"></i> '.esc_html__('SEARCH', 'pointfindercoreelements').'</a>';
                    $script_output = '
                    (function($) {
                        "use strict";
                        $.pfsliderdefaults = {};$.pfsliderdefaults.fields = Array();

                        $(function(){
                        '.$PFListSF->ScriptOutput;
                        $script_output .= 'var pfsearchformerrors = $(".pfsearchformerrors");
                        
                            $("#pointfinder-search-form-manual").validate({
                                  debug:false,
                                  onfocus: false,
                                  onfocusout: false,
                                  onkeyup: false,
                                  rules:{'.$PFListSF->VSORules.'},messages:{'.$PFListSF->VSOMessages.'},
                                  ignore: ".select2-input, .select2-focusser, .pfignorevalidation",
                                  validClass: "pfvalid",
                                  errorClass: "pfnotvalid pfaddnotvalidicon",
                                  errorElement: "li",
                                  errorContainer: pfsearchformerrors,
                                  errorLabelContainer: $("ul", pfsearchformerrors),
                                  invalidHandler: function(event, validator) {
                                    var errors = validator.numberOfInvalids();
                                    if (errors) {
                                        pfsearchformerrors.show("slide",{direction : "up"},100)
                                        $(".pfsearch-err-button").on("click",function(){
                                            pfsearchformerrors.hide("slide",{direction : "up"},100)
                                            return false;
                                        });
                                    }else{
                                        pfsearchformerrors.hide("fade",300)
                                    }
                                  }
                            });
                        ';

                        if ($fltf != 'none') {
                            $script_output .= '
                            setTimeout(function(){
                               $(".select2-container" ).attr("title","");
                               $("#'.$fltf.'" ).attr("title","")
                                
                            },300);
                            ';
                        }
                        $script_output .= '
                        });'.$PFListSF->ScriptOutputDocReady;
                    }

                    if (!empty($category_selected_auto)) {
                        $script_output .= '
                            $(function(){
                                if ($("#'.$fltf.'" )) {
                                    $("#'.$fltf.'" ).select2("val","'.$category_selected_auto.'");
                                }
                            });
                        ';
                    }
                    $script_output .='})(jQuery);';

                    wp_add_inline_script('theme-scriptspf',$script_output,'after');

                    echo '</div>';
                    if (is_rtl()) {
                      echo $PFListSF->FieldOutput;
                    }
                    unset($PFListSF);
              ?>
              </div>
              </div>
              </form>
              </div>
              <?php
              /**
              *End: Search Form
              **/   


      /**
      *END: SEARCH ITEMS WIDGET
      **/

  
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}

}
new PointFinderSearchShortcode();

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_pointfinder_single_pf_searchw extends WPBakeryShortCode {
    }
}