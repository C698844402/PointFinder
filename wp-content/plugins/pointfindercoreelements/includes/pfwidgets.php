<?php

/**********************************************************************************************************************************
*
* Custom Widgets for PointFinder
*
* Author: Webbu
*
***********************************************************************************************************************************/

/**
*START: SEARCH ITEMS WIDGET
**/

    class pf_search_items_w extends WP_Widget {
      use PointFinderOptionFunctions,PointFinderCommonFunctions;
      
        function __construct() {
        parent::__construct(
            'pf_search_items_w',
            esc_html__('PointFinder Search', 'pointfindercoreelements'),
            array( 'description' => esc_html__( 'Search PF Items', 'pointfindercoreelements' ),'classname' => 'widget_pfitem_recent_entries' )
        );
        }


        public function widget( $args, $instance ) {
            $title = apply_filters( 'widget_title', $instance['title'] );
            echo $args['before_widget'];
            if ( ! empty( $title ) ){
                echo $args['before_title'] . $title . $args['after_title'];
            }



              /**
              *Start: Search Form
              **/
              ?>
              <form id="pointfinder-search-form-manual" method="get" action="<?php echo esc_url(home_url("/")); ?>" data-ajax="false">
              <div class="pfsearch-content golden-forms">
              <div class="pfsearchformerrors">
                <ul>
                </ul>
                <a class="button pfsearch-err-button"><i class="fas fa-times"></i> <?php echo esc_html__('CLOSE','pointfindercoreelements')?></a>
              </div>
              <?php
                $setup1s_slides = $this->PFSAIssetControl('setup1s_slides','','');

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

                        $PFListSF->GetValue($value['title'],$value['url'],$value['select'],1,$pfformvars);

                    }


                    /*Sense Category*/
                    $current_post_id = get_the_id();

                    if (!empty($current_post_id) && (is_single())) {
                        $current_post_terms = get_the_terms( $current_post_id, 'pointfinderltypes');

                        if (isset($current_post_terms) && $current_post_terms != false) {
                            foreach ($current_post_terms as $key => $value) {
                                $category_selected_auto = $value->term_id;
                            }

                        }
                    }elseif( (is_category() || is_archive() || is_tag() || is_tax())){
                        global $wp_query;

                        if(isset($wp_query->query_vars['taxonomy'])){
                            $taxonomy_name = $wp_query->query_vars['taxonomy'];
                            if ($taxonomy_name == 'pointfinderltypes') {
                                $term_slug = $wp_query->query_vars['term'];
                                $term_name = get_term_by('slug', $term_slug, $taxonomy_name,'ARRAY_A');
                                if (isset($term_name['term_id'])) {
                                    $category_selected_auto = $term_name['term_id'];
                                }
                            }

                        }
                    }



                    /*Get Listing Type Item Slug*/
                    $fltf = $this->pointfinder_find_requestedfields('pointfinderltypes');
                    $features_field = $this->pointfinder_find_requestedfields('pointfinderfeatures');
                    $itemtypes_field = $this->pointfinder_find_requestedfields('pointfinderitypes');
                    $conditions_field = $this->pointfinder_find_requestedfields('pointfinderconditions');

                    $stp_syncs_it = $this->PFSAIssetControl('stp_syncs_it','',1);
                    $stp_syncs_co = $this->PFSAIssetControl('stp_syncs_co','',1);
                    $setup4_sbf_c1 = $this->PFSAIssetControl('setup4_sbf_c1','',1);

                    $second_request_process = false;
                    $second_request_text = "{features:'',itemtypes:'',conditions:''}";
                    $multiple_itemtypes = $multiple_features = $multiple_conditions =  '';

                    if (!empty($features_field) || !empty($itemtypes_field) || !empty($conditions_field)) {
                        $second_request_process = true;
                        $second_request_text = '{';
                        if (!empty($features_field) && $setup4_sbf_c1 == 0) {
                          $second_request_text .= "features:'$features_field'";
                          $multiple_features = $this->PFSFIssetControl('setupsearchfields_'.$features_field.'_multiple','','0');
                        }
                        if (!empty($itemtypes_field) && $stp_syncs_it == 0) {
                          if (!empty($features_field) && $setup4_sbf_c1 == 0) {
                            $second_request_text .= ",";
                          }
                          $second_request_text .= "itemtypes:'$itemtypes_field'";
                          $multiple_itemtypes = $this->PFSFIssetControl('setupsearchfields_'.$itemtypes_field.'_multiple','','0');
                        }
                        if (!empty($conditions_field) && $stp_syncs_co == 0) {
                          if ((!empty($features_field) && $setup4_sbf_c1 == 0) || (!empty($itemtypes_field) && $stp_syncs_it == 0)) {
                            $second_request_text .= ",";
                          }
                          $second_request_text .= "conditions:'$conditions_field'";
                          $multiple_conditions = $this->PFSFIssetControl('setupsearchfields_'.$conditions_field.'_multiple','','0');
                        }

                        if (!empty($multiple_itemtypes)) {
                          if (!empty($second_request_text)) {
                            $second_request_text .= ",";
                          }
                          $second_request_text .= "mit:'1'";
                        }

                        if (!empty($multiple_features)) {
                          if (!empty($second_request_text)) {
                            $second_request_text .= ",";
                          }
                          $second_request_text .= "mfe:'1'";
                        }

                        if (!empty($multiple_conditions)) {
                          if (!empty($second_request_text)) {
                            $second_request_text .= ",";
                          }
                          $second_request_text .= "mco:'1'";
                        }


                        $second_request_text .= '}';
                    }

                    $pfformvars_json = (isset($pfformvars))?json_encode($pfformvars):json_encode(array());
                    $script_output = '';
                    echo $PFListSF->FieldOutput;
                    echo '<div id="pfsearchsubvalues"></div>';
                    echo '<input type="hidden" name="s" value=""/>';
                    echo '<input type="hidden" name="serialized" value="1"/>';
                    echo '<input type="hidden" name="action" value="pfs"/>';
                    echo '<a class="button pfsearch" id="pf-search-button-manual"><i class="pfadmicon-glyph-627"></i> '.esc_html__('SEARCH', 'pointfindercoreelements').'</a>';
                    echo '<a class="button pfsearch" id="pf-reset-button-manual">'.esc_html__('RESET', 'pointfindercoreelements').'</a>';
                    $script_output .= '
                    (function($) {
                        "use strict";
                        $.pffieldsids = '.$second_request_text.'
                        $.pfsliderdefaults = {};$.pfsliderdefaults.fields = Array();

                        $(function(){';
                        $script_output .= $PFListSF->ScriptOutput;
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
                            $ajaxloads = $this->PFSFIssetControl('setupsearchfields_'.$fltf.'_ajaxloads','','0');
                            $as_mobile_dropdowns = $this->PFSAIssetControl('as_mobile_dropdowns','','0');

                            if ($as_mobile_dropdowns == 1) {
                                $script_output .= '
                                $(function(){
                                    $("#'.$fltf.'" ).change(function(e) {
                                      $.PFGetSubItems($("#'.$fltf.'" ).val(),"'.base64_encode($pfformvars_json).'",1,0);
                                      ';
                                      if ($second_request_process) {
                                        $script_output .= '$.PFRenewFeatures($("#'.$fltf.'").val(),"'.$second_request_text.'");';
                                      }
                                      $script_output .= '
                                    });
                                    $(document).one("ready",function(){
                                        if ($("#'.$fltf.'" ).val() !== 0) {
                                           $.PFGetSubItems($("#'.$fltf.'" ).val(),"'.base64_encode($pfformvars_json).'",1,0);
                                           ';
                                           if ($second_request_process) {
                                             $script_output .= '$.PFRenewFeatures($("#'.$fltf.'").val(),"'.$second_request_text.'");';
                                           }
                                           $script_output .= '
                                        }
                                    });
                                    setTimeout(function(){
                                       $(".select2-container" ).attr("title","");
                                       $("#'.$fltf.'" ).attr("title","")

                                    },300);
                                });
                                ';
                            }else{
                                $script_output .= '

                                $("#'.$fltf.'" ).change(function(e) {
                                  $.PFGetSubItems($("#'.$fltf.'" ).val(),"'.base64_encode($pfformvars_json).'",1,0);
                                  ';
                                  if ($second_request_process) {
                                    $script_output .= '$.PFRenewFeatures($("#'.$fltf.'").val(),"'.$second_request_text.'");';
                                  }
                                  $script_output .= '
                                });
                                $(document).one("ready",function(){
                                    if ($("#'.$fltf.'" ).val() !== 0) {
                                       $.PFGetSubItems($("#'.$fltf.'" ).val(),"'.base64_encode($pfformvars_json).'",1,0);
                                       ';
                                       if ($second_request_process) {
                                         $script_output .= '$.PFRenewFeatures($("#'.$fltf.'").val(),"'.$second_request_text.'");';
                                       }
                                       $script_output .= '
                                    }
                                });
                                setTimeout(function(){
                                   $(".select2-container" ).attr("title","");
                                   $("#'.$fltf.'" ).attr("title","")

                                },300);

                                ';
                            }
                        }
                        $script_output .= '
                        });';
                        $script_output .= $PFListSF->ScriptOutputDocReady;
                    }

                    if (!empty($category_selected_auto)) {
                        if($ajaxloads == 1){
                          $script_output .= '
                            $(function(){
                                    if ($("#'.$fltf.'_sel" )) {
                                        $("#'.$fltf.'_sel" ).select2("val","'.$category_selected_auto.'");
                                    }
                                });
                            ';
                        }else{
                          $script_output .= '
                            $(function(){
                                  if ($("#'.$fltf.'" )) {
                                      $("#'.$fltf.'" ).select2("val","'.$category_selected_auto.'");
                                  }
                              });
                          ';
                        }
                    }
                    $script_output .='

                    })(jQuery);';
                    wp_add_inline_script( 'theme-scriptspf', $script_output, 'after' );
                    unset($PFListSF);
              ?>
              </div>
              </form>
              <?php
              /**
              *End: Search Form
              **/
            echo $args['after_widget'];
        }


        public function form( $instance ) {
            $setup3_pointposttype_pt7 = $this->PFSAIssetControl('setup3_pointposttype_pt7','','Listing Types');
            $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
            ?>
            <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:','pointfindercoreelements'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
        <?php
        }

        public function update( $new_instance, $old_instance ) {
            $instance = array();
            $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

            return $instance;
        }
    }

/**
*END: SEARCH ITEMS WIDGET
**/





/**
*START: RECENT ITEMS WIDGET
**/

    class pf_recent_items_w extends WP_Widget {
      use PointFinderOptionFunctions,PointFinderCommonFunctions;
        function __construct() {
        parent::__construct(
            'pf_recent_items_w',
            esc_html__('PointFinder Recent Items', 'pointfindercoreelements'),
            array( 'description' => esc_html__( 'Recent posts', 'pointfindercoreelements' ),'classname' => 'widget_pfitem_recent_entries')
        );
        }


        public function widget( $args, $instance ) {
            $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
            echo $args['before_widget'];
            if ( ! empty( $title ) ){
                echo $args['before_title'] . $title . $args['after_title'];
            }


            if ( !$number = (int) $instance['number'] ){
                $number = 10;
            }else if ( $number < 1 ){
                $number = 1;
            }else if ( $number > 15 ){
                $number = 15;
            }
            $ltype = 0;
            $laddress = 1;
            $limage = 1;

            $sense = empty($instance['sense']) ? 0 : $instance['sense'];
            $rnd_feature = empty($instance['rnd']) ? 0 : $instance['rnd'];

            $setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');

            $args2 = array(
                'showposts' => $number,
                'nopaging' => 0,
                'post_status' => 'publish',
                'ignore_sticky_posts' => true,
                'post_type' => array($setup3_pointposttype_pt1),
                'orderby'=>'date',
                'order'=>'DESC'
            );


            if ($rnd_feature != 0) {
                $args2['orderby']='rand';
            }


            /*Sense Category*/
            if ((is_single() || is_category() || is_archive()) && $sense == 1) {
                $current_post_id = get_the_id();

                if (!empty($current_post_id)) {
                    $current_post_terms = get_the_terms( $current_post_id, 'pointfinderltypes');
                    if (isset($current_post_terms) && $current_post_terms != false) {
                        foreach ($current_post_terms as $key => $value) {
                            if ($value->parent == 0) {
                                $args2['tax_query']=
                                    array(
                                        'relation' => 'AND',
                                        array(
                                            'taxonomy' => 'pointfinderltypes',
                                            'field' => 'id',
                                            'terms' => $value->term_id,
                                            'operator' => 'IN'
                                        )
                                    );
                            }else{
                                $args2['tax_query']=
                                    array(
                                        'relation' => 'AND',
                                        array(
                                            'taxonomy' => 'pointfinderltypes',
                                            'field' => 'id',
                                            'terms' => $value->parent,
                                            'operator' => 'IN'
                                        )
                                    );
                            }
                        }

                    }
                }
            }


            $r = new WP_Query($args2);
            echo '<div class="widget_pfitem_recent_entries">';
            if ($r->have_posts()) {
            echo '<ul class="pf-widget-itemlist">';
                while ($r->have_posts()) : $r->the_post();
                    echo '<li class="clearfix">';
                        $mytitle = get_the_title();
                        $myid = get_the_ID();
                        echo '<a href="'.get_the_permalink().'" title="';
                                esc_attr($mytitle ? $mytitle : $myid);
                                echo '">';
                        if($limage == 1){

                            if ( has_post_thumbnail()) {

                                $general_retinasupport = $this->PFSAIssetControl('general_retinasupport','','0');

                                $attachment_img_pf = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()),'thumbnail');
                                if($general_retinasupport != 1){
                                    $attachment_img_pf_url = pointfinder_aq_resize($attachment_img_pf[0],70,70,true);
                                }else{
                                    $attachment_img_pf_url = pointfinder_aq_resize($attachment_img_pf[0],140,140,true);
                                }

                                if ($attachment_img_pf_url == false) {
                                    $attachment_img_pf_url = $attachment_img_pf[0];
                                }
                                echo '<img src="'.$attachment_img_pf_url.'" alt="">';

                            }
                            }

                            echo '<div class="pf-recent-items-title pflineclamp-title">';

                                if ( $mytitle ){
                                    echo $mytitle;
                                    
                                }else{
                                    echo $myid;
                                };

                            echo '</div>';
                            if($laddress == 1){
                                echo '<div class="pf-recent-items-address pflineclamp-address">';
                                $mypostmeta = esc_html(get_post_meta( get_the_ID(), 'webbupointfinder_items_address', true ));
                                echo $mypostmeta;
                                
                                echo '</div>';
                            }
                            if($ltype == 1){
                                echo '<div class="pf-recent-items-terms">';
                                echo $this->GetPFTermInfo(get_the_ID(),'pointfinderltypes');
                                echo '</div>';
                                echo '<div class="pf-recent-items-terms">';
                                echo $this->GetPFTermInfo(get_the_ID(),'pointfinderitypes');
                                echo '</div>';
                            }
                         echo '</a>';
                    echo '</li>';

                endwhile;
            echo '</ul>';
            echo '</div>';

            wp_reset_postdata();

            }
            echo $args['after_widget'];
        }


        public function form( $instance ) {
            $setup3_pointposttype_pt7 = $this->PFSAIssetControl('setup3_pointposttype_pt7','','Listing Types');
            $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
            if ( !isset($instance['number']) || !$number = (int) $instance['number'] ){$number = 5;}
            if ( isset($instance['sense']) && $instance['sense'] == 1 ){$sense_checked = " checked = 'checked'";}else{$sense_checked ='';}
            if ( isset($instance['rnd']) && $instance['rnd'] == 1 ){$rnd_checked = " checked = 'checked'";}else{$rnd_checked ='';}
            ?>
            <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:','pointfindercoreelements'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

            <p>
                <label for="<?php echo $this->get_field_id('number'); ?>"><?php esc_html_e('Number of posts to show:','pointfindercoreelements'); ?></label>
                <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('sense'); ?>"><?php esc_html_e('Filter Category:','pointfindercoreelements'); ?></label>
                <input id="<?php echo $this->get_field_id('sense'); ?>" name="<?php echo $this->get_field_name('sense'); ?>" type="checkbox" value="1"<?php echo $sense_checked;?> /><br/>
                <small><?php echo esc_html__('If this enabled, this widget will show only page category items.','pointfindercoreelements');?></small>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('rnd'); ?>"><?php esc_html_e('Random Posts:','pointfindercoreelements'); ?></label>
                <input id="<?php echo $this->get_field_id('rnd'); ?>" name="<?php echo $this->get_field_name('rnd'); ?>" type="checkbox" value="1"<?php echo $rnd_checked;?> /><br/>
                <small><?php echo esc_html__('If this enabled, this widget will show random items.','pointfindercoreelements');?></small>
            </p>

        <?php
        }

        public function update( $new_instance, $old_instance ) {
            $instance = $old_instance;

            $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['number'] = (int) $new_instance['number'];
            $instance['sense'] = isset($new_instance['sense'])? $new_instance['sense']:0;
            $instance['rnd'] = isset($new_instance['rnd'])? $new_instance['rnd']:0;

            return $instance;
        }
    }

/**
*END: RECENT ITEMS WIDGET
**/





/**
*START: FEATURED ITEMS WIDGET
**/
    class pf_featured_items_w extends WP_Widget {
      use PointFinderOptionFunctions,PointFinderCommonFunctions;
        function __construct() {
          parent::__construct(
              'pf_featured_items_w',
              esc_html__('PointFinder Featured Items', 'pointfindercoreelements'),
              array( 'description' => esc_html__( 'Featured posts', 'pointfindercoreelements' ),'classname' => 'widget_pfitem_recent_entries' )
          );
        }


        public function widget( $args, $instance ) {

            $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

            echo $args['before_widget'];
            if ( ! empty( $title ) ){
                echo $args['before_title'] . $title . $args['after_title'];
            }

            if ( !$number = (int) $instance['number'] ){
                $number = 10;
            }else if ( $number < 1 ){
                $number = 1;
            }else if ( $number > 15 ){
                $number = 15;
            }
            $ltype = 0;
            $laddress = 1;
            $limage = 1;

            $sense = empty($instance['sense']) ? 0 : $instance['sense'];
            $rnd_feature = empty($instance['rnd']) ? 0 : $instance['rnd'];

            $setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');


            $args2 = array(
                'showposts' => $number,
                'nopaging' => 0,
                'post_status' => 'publish',
                'ignore_sticky_posts' => true,
                'post_type' => array($setup3_pointposttype_pt1),
                'orderby'=>'date',
                'order'=>'DESC',
                'meta_query' => array(array('key' => 'webbupointfinder_item_featuredmarker','value' => '1','compare' => '='))
            );

            if ($rnd_feature != 0) {
                $args2['orderby']='rand';
            }


            /*Sense Category*/
            if ((is_single() || is_category() || is_archive()) && $sense == 1) {
                $current_post_id = get_the_id();

                if (!empty($current_post_id)) {
                    $current_post_terms = get_the_terms( $current_post_id, 'pointfinderltypes');
                    if (isset($current_post_terms) && $current_post_terms != false) {
                        foreach ($current_post_terms as $key => $value) {
                            if ($value->parent == 0) {
                                $args2['tax_query']=
                                    array(
                                        'relation' => 'AND',
                                        array(
                                            'taxonomy' => 'pointfinderltypes',
                                            'field' => 'id',
                                            'terms' => $value->term_id,
                                            'operator' => 'IN'
                                        )
                                    );
                            }else{
                                $args2['tax_query']=
                                    array(
                                        'relation' => 'AND',
                                        array(
                                            'taxonomy' => 'pointfinderltypes',
                                            'field' => 'id',
                                            'terms' => $value->parent,
                                            'operator' => 'IN'
                                        )
                                    );
                            }
                        }

                    }
                }
            }


            $r = new WP_Query($args2);
            echo '<div class="widget_pfitem_recent_entries">';
            if ($r->have_posts()) {
            echo '<ul class="pf-widget-itemlist">';
                while ($r->have_posts()) : $r->the_post();
                    echo '<li class="clearfix">';
                        $mytitle = get_the_title();
                        $myid = get_the_ID();
                        echo '<a href="'.get_the_permalink().'" title="';
                                esc_attr($mytitle ? $mytitle : $myid);
                                echo '">';
                        if($limage == 1){

                            if ( has_post_thumbnail()) {

                                    $general_retinasupport = $this->PFSAIssetControl('general_retinasupport','','0');

                                    $attachment_img_pf = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()),'thumbnail');
                                    if($general_retinasupport != 1){
                                        $attachment_img_pf_url = pointfinder_aq_resize($attachment_img_pf[0],70,70,true);
                                    }else{
                                        $attachment_img_pf_url = pointfinder_aq_resize($attachment_img_pf[0],140,140,true);
                                    }
                                    if ($attachment_img_pf_url == false) {
                                        $attachment_img_pf_url = $attachment_img_pf[0];
                                    }
                                    echo '<img src="'.$attachment_img_pf_url.'" alt="">';

                            }
                        }


                        echo '<div class="pf-recent-items-title pflineclamp-title">';
                            if ( $mytitle ){
                                echo $mytitle;
                            }else{
                                echo $myid;
                            };

                        echo '</div>';
                        if($laddress == 1){
                        echo '<div class="pf-recent-items-address pflineclamp-address">';
                        $mypostmeta = esc_html(get_post_meta( get_the_ID(), 'webbupointfinder_items_address', true ));
                        echo $mypostmeta;
                        echo '</div>';
                        }
                        if($ltype == 1){
                        echo '<div class="pf-recent-items-terms">';
                        echo $this->GetPFTermInfo(get_the_ID(),'pointfinderltypes');
                        echo '</div>';
                        echo '<div class="pf-recent-items-terms">';
                        echo $this->GetPFTermInfo(get_the_ID(),'pointfinderitypes');
                        echo '</div>';
                        }
                    echo '</a>';
                    echo '</li>';

                endwhile;
            echo '</ul>';
            echo '</div>';

            wp_reset_postdata();

            }
            echo $args['after_widget'];
        }


        public function form( $instance ) {
            $setup3_pointposttype_pt7 = $this->PFSAIssetControl('setup3_pointposttype_pt7','','Listing Types');
            $title = isset($instance['title']) ? esc_attr($instance['title']) : '';

            if ( !isset($instance['number']) || !$number = (int) $instance['number'] ){$number = 5;}
            if ( isset($instance['sense']) && $instance['sense'] == 1 ){$sense_checked = " checked = 'checked'";}else{$sense_checked ='';}
            if ( isset($instance['rnd']) && $instance['rnd'] == 1 ){$rnd_checked = " checked = 'checked'";}else{$rnd_checked ='';}
            ?>
            <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:','pointfindercoreelements'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

            <p>
                <label for="<?php echo $this->get_field_id('number'); ?>"><?php esc_html_e('Number of posts to show:','pointfindercoreelements'); ?></label>
                <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('sense'); ?>"><?php esc_html_e('Filter Category:','pointfindercoreelements'); ?></label>
                <input id="<?php echo $this->get_field_id('sense'); ?>" name="<?php echo $this->get_field_name('sense'); ?>" type="checkbox" value="1"<?php echo $sense_checked;?> /><br/>
                <small><?php echo esc_html__('If this enabled, this widget will show only page category items.','pointfindercoreelements');?></small>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('rnd'); ?>"><?php esc_html_e('Random Posts:','pointfindercoreelements'); ?></label>
                <input id="<?php echo $this->get_field_id('rnd'); ?>" name="<?php echo $this->get_field_name('rnd'); ?>" type="checkbox" value="1"<?php echo $rnd_checked;?> /><br/>
                <small><?php echo esc_html__('If this enabled, this widget will show random items.','pointfindercoreelements');?></small>
            </p>
        <?php
        }


        public function update( $new_instance, $old_instance ) {
            $instance = $old_instance;

            $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['number'] = (int) $new_instance['number'];
            $instance['sense'] = isset($new_instance['sense'])? $new_instance['sense']:0;
            $instance['rnd'] = isset($new_instance['rnd'])? $new_instance['rnd']:0;

            return $instance;
        }
    }

/**
*END: FEATURED ITEMS WIDGET
**/




/**
*START: TWITTER WIDGET
**/
    class pf_twitter_w extends WP_Widget {
      use PointFinderOptionFunctions,PointFinderCommonFunctions;
        function __construct() {
        parent::__construct(
            'pf_twitter_w',
            esc_html__('PointFinder Twitter Widget', 'pointfindercoreelements'),
            array( 'description' => esc_html__( 'Twitter feeds', 'pointfindercoreelements' ),'classname' => 'widget_pfitem_recent_entries' )
        );
        }


        public function widget( $args, $instance ) {
            $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
            echo $args['before_widget'];
            if ( ! empty( $title ) ){
                echo $args['before_title'] . $title . $args['after_title'];
            }


            if ( !$number = (int) $instance['number'] ){
                $number = 10;
            }else if ( $number < 1 ){
                $number = 1;
            }else if ( $number > 15 ){
                $number = 15;
            }

            $scname = empty($instance['scname']) ? 0 : $instance['scname'];

            $twitterpage= '
            <!-- Twitter page begin -->

                <script type="text/javascript">
                    jQuery(document).ready(function(){
                        jQuery(function () {
                            jQuery.JQTWEET.loadTweets("'.$scname.'","'.$number.'");
                        });
                    });
                </script>

                <div id="jstwitter"></div>

            <!-- Twitter End -->';
            echo '<div class="widget_pfitem_recent_entries">';
            echo $twitterpage;
            echo '</div>';

            echo $args['after_widget'];
        }


        public function form( $instance ) {

            $title = isset($instance['title']) ? esc_attr($instance['title']) : '';

            if ( !isset($instance['number']) || !$number = (int) $instance['number'] ){$number = 5;}
            if ( !isset($instance['scname'])){$scname = '';}else{$scname = $instance['scname'];}
            ?>
            <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:','pointfindercoreelements'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

            <p>
                <label for="<?php echo $this->get_field_id('scname'); ?>"><?php esc_html_e('Screen Name:','pointfindercoreelements'); ?></label>
                <input id="<?php echo $this->get_field_id('scname'); ?>" name="<?php echo $this->get_field_name('scname'); ?>" type="text" value="<?php echo $scname; ?>" size="15" />
            </p>


            <p>
                <label for="<?php echo $this->get_field_id('number'); ?>"><?php esc_html_e('Number of tweets:','pointfindercoreelements'); ?></label>
                <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
            </p>

        <?php
        }

        public function update( $new_instance, $old_instance ) {
            $instance = array();
            $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

            $instance['number'] = (int) $new_instance['number'];
            $instance['scname'] = $new_instance['scname'];

            return $instance;
        }
    }

/**
*END: TWITTER WIDGET
**/




/**
*START: FEATURED AGENTS WIDGET
**/
    class pf_featured_agents_w extends WP_Widget {
      use PointFinderOptionFunctions,PointFinderCommonFunctions;
        function __construct() {
        parent::__construct(
            'pf_featured_agents_w',
            esc_html__('PointFinder List Agents', 'pointfindercoreelements'),
            array( 'description' => esc_html__( 'List agents', 'pointfindercoreelements' ),'classname' => 'widget_pfitem_recent_entries' )
        );
        }


        public function widget( $args, $instance ) {
            $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
            echo $args['before_widget'];
            if ( ! empty( $title ) ){
                echo $args['before_title'] . $title . $args['after_title'];
            }


            if ( !$number = (int) $instance['number'] ){
                $number = 10;
            }else if ( $number < 1 ){
                $number = 1;
            }else if ( $number > 15 ){
                $number = 15;
            }

            if ( !empty($instance['number2']) ){
                $post_numbers = pfstring2BasicArray($instance['number2']);
            }else{
                $post_numbers = array();
            }


            $limage = 1;

            $setup3_pointposttype_pt8 = $this->PFSAIssetControl('setup3_pointposttype_pt8','','agents');
            $r = new WP_Query(array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => true, 'post_type' => array($setup3_pointposttype_pt8),'orderby'=>'title','order'=>'ASC','post__in'=> $post_numbers));



            if ($r->have_posts()) {
            echo '<div class="widget_pfitem_recent_entries">';
            echo '<ul class="pf-widget-itemlist">';
                while ($r->have_posts()) : $r->the_post();
                    echo '<li class="clearfix">';
                        $mytitle = get_the_title();
                        $myid = get_the_ID();
                        $mycontent = get_the_content();
                        echo '<a href="'.get_the_permalink().'" title="';
                                esc_attr($mytitle ? $mytitle : $myid);
                                echo '">';
                        if($limage == 1){

                            if ( has_post_thumbnail()) {

                                    $general_retinasupport = $this->PFSAIssetControl('general_retinasupport','','0');

                                    $attachment_img_pf = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()),'thumbnail');
                                    if($general_retinasupport != 1){
                                        $attachment_img_pf_url = pointfinder_aq_resize($attachment_img_pf[0],70,70,true);
                                    }else{
                                        $attachment_img_pf_url = pointfinder_aq_resize($attachment_img_pf[0],140,140,true);
                                    }
                                    echo '<img src="'.$attachment_img_pf_url.'" alt="">';

                            }
                        }


                        echo '<div class="pf-recent-items-title pflineclamp-title">';
                            if ( $mytitle ){
                                echo $mytitle;
                                
                            }else{
                                echo $myid;
                            };

                        echo '</div>';

                        echo '<div class="pf-recent-items-address pflineclamp-address">';
                        echo $mycontent; 
                        echo '</div>';

                    echo '</a>';
                    echo '</li>';

                endwhile;
            echo '</ul>';
            echo '</div>';


            wp_reset_postdata();

            }
            echo $args['after_widget'];
        }


        public function form( $instance ) {
            $setup3_pointposttype_pt7 = $this->PFSAIssetControl('setup3_pointposttype_pt7','','Listing Types');
            $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
            if ( !isset($instance['number']) || !$number = (int) $instance['number'] ){$number = 5;}
            if ( !isset($instance['number2']) || !$number2= $instance['number2'] ){$number2 = '';}
            ?>
            <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:','pointfindercoreelements'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

            <p>
                <label for="<?php echo $this->get_field_id('number'); ?>"><?php esc_html_e('Number of posts to show:','pointfindercoreelements'); ?></label>
                <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('number2'); ?>"><?php esc_html_e('Agent ID Numbers:','pointfindercoreelements'); ?></label><br/>
                <input id="<?php echo $this->get_field_id('number2'); ?>" name="<?php echo $this->get_field_name('number2'); ?>" type="text" value="<?php echo $number2; ?>" style="width:100%" /><br/>
                <small><?php esc_html_e('Please write like ex: 12,13,14','pointfindercoreelements'); ?></small>
            </p>
        <?php
        }

        public function update( $new_instance, $old_instance ) {
            $instance = array();
            $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['number'] = (int) $new_instance['number'];
            $instance['number2'] = $new_instance['number2'];
            return $instance;
        }
    }

/**
*END: FEATURED AGENTS WIDGET
**/
