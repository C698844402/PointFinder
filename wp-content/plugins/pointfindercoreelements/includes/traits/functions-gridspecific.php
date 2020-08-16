<?php

if (!trait_exists('PointFinderGridSpecificFunctions')) {
    trait PointFinderGridSpecificFunctions
    {   

        public function pf_get_markerimage($postid,$st8_npsys){
                
            $pfitemicon = array();

            /* Check if item have a custom icon */

            $webbupointfinder_item_point_type = esc_attr(get_post_meta( $postid, "webbupointfinder_item_point_type", true ));
            $webbupointfinder_item_point_typenew = (empty($webbupointfinder_item_point_type))? 3:$webbupointfinder_item_point_type;


            switch ($webbupointfinder_item_point_typenew) {
              case 1:
                //$pf_custom_point_images = redux_post_meta("pointfinderthemefmb_options", $postid, "webbupointfinder_item_custom_marker");
                //$pf_custom_point_images = get_post_meta( $post_id,"webbupointfinder_item_custom_marker", true );
                
                /** 
                *Start: Custom icon check result = Image Icon
                **/
                  $pfitemicon['is_image'] = 1;
                  $pfitemicon['is_cat'] = 0;

                  global $wpdb;
                  $pf_custom_point_images = $wpdb->get_var($wpdb->prepare("select meta_value from $wpdb->postmeta where post_id=%d and meta_key='%s'",$postid,'webbupointfinder_item_custom_marker'));
                  $pf_custom_point_images = unserialize($pf_custom_point_images);

                  $pf_custom_point_image_height = (!empty($pf_custom_point_images['height']))? $pf_custom_point_images['height'] : 0;
                  $pf_custom_point_image_width = (!empty($pf_custom_point_images['width']))? $pf_custom_point_images['width'] : 0;


                  $width_calculated = $pf_custom_point_image_width;
                  $height_calculated = $pf_custom_point_image_height;

                  $pfitemicon['content']= '<div class=\'pf-map-pin-x\' style=\'background-image:url('.$pf_custom_point_images['url'].'); background-size:'.$width_calculated.'px '.$height_calculated.'px; width:'.$width_calculated.'px; height:'.$height_calculated.'px;opacity:1;\'></div>';
                /** 
                *End: Custom icon check result = Image Icon
                **/
              break;

            case 2:

              /** 
              *Start: Custom icon check result = Css Icon
              **/
                $cssmarker_icontype = esc_attr(get_post_meta( $postid, 'webbupointfinder_item_cssmarker_icontype', true ));
                $cssmarker_icontype = (empty($cssmarker_icontype)) ? 1 : $cssmarker_icontype ;
                $cssmarker_iconsize = esc_attr(get_post_meta( $postid, 'webbupointfinder_item_cssmarker_iconsize', true ));
                $cssmarker_iconsize = (empty($cssmarker_iconsize)) ? 'middle' : $cssmarker_iconsize ;
                $cssmarker_iconname = esc_attr(get_post_meta( $postid, 'webbupointfinder_item_cssmarker_iconname', true ));

                $cssmarker_bgcolor = esc_attr(get_post_meta( $postid, 'webbupointfinder_item_cssmarker_bgcolor', true ));
                $cssmarker_bgcolor = (empty($cssmarker_bgcolor)) ? '#b00000' : $cssmarker_bgcolor ;
                $cssmarker_bgcolorinner = esc_attr(get_post_meta( $postid, 'webbupointfinder_item_cssmarker_bgcolorinner', true ));
                $cssmarker_bgcolorinner = (empty($cssmarker_bgcolorinner)) ? '#ffffff' : $cssmarker_bgcolorinner ;
                $cssmarker_iconcolor = esc_attr(get_post_meta( $postid, 'webbupointfinder_item_cssmarker_iconcolor', true ));
                $cssmarker_iconcolor = (empty($cssmarker_iconcolor)) ? '#b00000' : $cssmarker_iconcolor ;
                
                $arrow_text = ($cssmarker_icontype == 2)? '<div class=\'pf-pinarrow\' style=\'border-color: '.$cssmarker_bgcolor.' transparent transparent transparent;\'></div>': '';

                $pfitemicon['is_image'] = 1;
                $pfitemicon['is_cat'] = 0;

                $pfitemicon['content'] = '';
                
                
                $pfitemicon['content'] .= '<div ';
                $pfitemicon['content'] .= 'class=\'pfcatdefault-mapicon pf-map-pin-'.$cssmarker_icontype.' pf-map-pin-'.$cssmarker_icontype.'-'.$cssmarker_iconsize.' pfcustom-mapicon-'.$postid.'\'';
                $pfitemicon['content'] .= ' style=\'background-color:'.$cssmarker_bgcolor.';opacity:1;\' >';
                $pfitemicon['content'] .= '<i class=\''.$cssmarker_iconname.'\' style=\'color:'.$cssmarker_iconcolor.'\' ></i></div>'.$arrow_text;
                $pfitemicon['content'] .= '<style>.pfcustom-mapicon-'.$postid.':after{background-color:'.$cssmarker_bgcolorinner.'!important}</style>';

              /** 
              *End: Custom icon check result = Css Icon
              **/ 
              break;

            default:
              /** 
              *Start: Check category icon 
              **/
                $pfitemicon['is_image'] = 0;
                $pfitemicon['is_cat'] = 1;

                $pf_item_terms = get_the_terms( $postid, 'pointfinderltypes');
                
                /* If marker term is available and array not empty */
                if(count($pf_item_terms) > 0){

                  if ( $pf_item_terms && ! is_wp_error( $pf_item_terms ) ) {
                    
                    if($st8_npsys == 1){
                      foreach ( $pf_item_terms as $pf_item_term ) {
                        $pf_item_term_id = $pf_item_term->term_id;
                      }
                    }else{
                      foreach ( $pf_item_terms as $pf_item_term ) {
                      
                        if ($pf_item_term->parent != 0) {
                          $pf_item_term_subcheck = $this->pf_term_sub_check_ex($pf_item_term->parent);
                          if ($pf_item_term_subcheck) {
                            $pf_item_term_id = $pf_item_term->term_id;
                          }else{
                            $pf_item_term_id = $this->pf_term_sub_check($pf_item_term->term_id);
                          }
                          if (!empty($pf_item_term_id)) {
                            break;
                          }
                        }else{
                          $pf_item_term_id = $pf_item_term->term_id;
                        }
                        
                        
                      }
                    }
                    

                  } 

                  if(class_exists('SitePress')) { /* If wpml enabled */
                    $pf_item_term_id = apply_filters('wpml_object_id',$pf_item_term_id,'pointfinderltypes',true,$this->PF_default_language());
                  }

                  if (!empty($pf_item_term_id)) {
                    $pfitemicon['cat'] = 'pfcat'.$pf_item_term_id;
                  }else{
                    $pfitemicon['cat'] = 'pfcatdefault';
                  }
                
                  
                }
                
              /** 
              *End: Check category icon 
              **/
              break;
            }
            
            return $pfitemicon;
        }

        public function pf_get_default_cat_images($pflang = '',$catid = '',$st8_npsys = ''){
                
              $wpflistdata = '';

              /**
              *Start: Default Point Variables
              **/
              if (empty($catid)) {
                if ($st8_npsys != 1) {
                  $icon_layout_type = $this->PFPFIssetControl('pscp_pfdefaultcat_icontype','','1');
                  $icon_name = $this->PFPFIssetControl('pscp_pfdefaultcat_iconname','','');
                  $icon_namefs = $this->PFPFIssetControl('pscp_pfdefaultcat_iconfs','','');
                  $icon_size = $this->PFPFIssetControl('pscp_pfdefaultcat_iconsize','','middle');
                  $icon_bg_color = $this->PFPFIssetControl('pscp_pfdefaultcat_bgcolor','','#b00000');

                  $arrow_text = ($icon_layout_type == 2)? '<div class=\'pf-pinarrow\' style=\'border-color: '.$icon_bg_color.' transparent transparent transparent;\'></div>': '';

                  $wpflistdata .= 'var pfcatdefault =';
                  $wpflistdata .= ' "<div ';
                  $wpflistdata .= 'class=\'pfcatdefault-mapicon pf-map-pin-'.$icon_layout_type.' pf-map-pin-'.$icon_layout_type.'-'.$icon_size.'\'';
                  $wpflistdata .= ' >';
                  if (!empty($icon_namefs)) {
                    $wpflistdata .= '<i class=\''.$icon_namefs.'\' ></i></div>'.$arrow_text.'";';
                  }else{
                    $wpflistdata .= '<i class=\''.$icon_name.'\' ></i></div>'.$arrow_text.'";';
                  }
                  
                }else{
                  $icon_layout_type = $this->PFASSIssetControl('cpoint_icontype','',1);
                  $icon_name = $this->PFASSIssetControl('cpoint_iconname','','');
                  $icon_namefs = $this->PFASSIssetControl('cpoint_iconnamefs','','');
                  $icon_size = $this->PFASSIssetControl('cpoint_iconsize','','middle');
                  $icon_bg_color = $this->PFASSIssetControl('cpoint_bgcolor','','#b00000');

                  $arrow_text = ($icon_layout_type == 2)? '<div class=\'pf-pinarrow\' style=\'border-color: '.$icon_bg_color.' transparent transparent transparent;\'></div>': '';

                  $wpflistdata .= '"<div ';
                  $wpflistdata .= 'class=\'pfcatdefault-mapicon pf-map-pin-'.$icon_layout_type.' pf-map-pin-'.$icon_layout_type.'-'.$icon_size.'\'';
                  $wpflistdata .= ' >';
                  if (!empty($icon_namefs)) {
                    $wpflistdata .= '<i class=\''.$icon_namefs.'\' ></i></div>'.$arrow_text.'";';
                  }else{
                    $wpflistdata .= '<i class=\''.$icon_name.'\' ></i></div>'.$arrow_text.'";';
                  }
                  
                }
              }
              /**
              *End: Default Point Variables
              **/



              /**
              *Start: Cat Point Variables
              **/
                
                $pf_get_term_details = get_terms('pointfinderltypes',array('hide_empty'=>false)); 

                if (!empty($pflang) && class_exists('SitePress')) {
                  global $sitepress;
                  do_action( 'wpml_switch_language', $pflang );
                }

                if(!empty($catid)){
                  $catid = absint(str_replace("pfcat", "", $catid));
                  $pf_get_term_detail = get_term_by( 'id', $catid, 'pointfinderltypes');
                  
                  $default_language = $current_language = $listing_meta = $cpoint_type = $cpoint_icontype = $cpoint_iconsize = $cpoint_iconname = $cpoint_bgcolor = '';
                  
                  if (class_exists('SitePress')) {
                    $default_language = $this->PF_default_language();
                    $current_language = $this->PF_current_language();
                  }

                  if ($st8_npsys == 1) {
                    $listing_meta = get_option('pointfinderltypes_style_vars');
                    $cpoint_type = $this->PFASSIssetControl('cpoint_type','',0);
                    $cpoint_icontype = $this->PFASSIssetControl('cpoint_icontype','',1);
                    $cpoint_iconsize = $this->PFASSIssetControl('cpoint_iconsize','','middle');
                    $cpoint_iconname = $this->PFASSIssetControl('cpoint_iconname','','');
                    $cpoint_iconnamefs = $this->PFASSIssetControl('cpoint_iconnamefs','','');
                    $cpoint_bgcolor = $this->PFASSIssetControl('cpoint_bgcolor','','#b00000');
                  }

                    if ($st8_npsys == 1) {
                      $wpflistdata .= $this->pointfinder_get_category_points(
                        array(
                          'pf_get_term_detail_idm' => $pf_get_term_detail->term_id,
                          'pf_get_term_detail_idm_parent' => $pf_get_term_detail->parent,
                          'listing_meta' => $listing_meta,
                          'cpoint_type' => $cpoint_type,
                          'cpoint_icontype' => $cpoint_icontype,
                          'cpoint_iconsize' => $cpoint_iconsize,
                          'cpoint_iconname' => $cpoint_iconname,
                          'cpoint_iconnamefs' => $cpoint_iconnamefs,
                          'cpoint_bgcolor' => $cpoint_bgcolor,
                          'dlang' => $default_language,
                          'clang' => $current_language,
                          'st8_npsys' => $st8_npsys
                        )
                      );
                    }else{
                        $wpflistdata .= $this->pointfinder_get_category_points(
                          array(
                            'pf_get_term_detail_idm' => $pf_get_term_detail->term_id,
                            'listing_meta' => $listing_meta,
                            'cpoint_type' => $cpoint_type,
                            'cpoint_icontype' => $cpoint_icontype,
                            'cpoint_iconsize' => $cpoint_iconsize,
                            'cpoint_iconname' => $cpoint_iconname,
                            'cpoint_bgcolor' => $cpoint_bgcolor,
                            'dlang' => $default_language,
                            'clang' => $current_language,
                            'st8_npsys' => $st8_npsys
                          )
                        );
                    }
                  
                  /*
                    Loop End from PF Custom Points
                  */

            
                }

              /**
              *End: Cat Point Variables
              **/

              return $wpflistdata;
        }

        public function pointfinder_get_category_points($params = array()){

          $defaults = array( 
            'pf_get_term_detail_idm' => '',
            'pf_get_term_detail_idm_parent' => '',
            'listing_meta' => '',
            'cpoint_type' => 0,
            'cpoint_icontype' => 1,
            'cpoint_iconsize' => 'middle',
            'cpoint_iconname' => '',
            'cpoint_iconnamefs' => '',
            'cpoint_bgcolor' => '#b00000',
            'dlang' => '',
            'clang' => '',
            'st8_npsys' => 0
            );

          $params = array_merge($defaults, $params);

          $listing_meta = $params['listing_meta'];
           
          $pf_get_term_detail_id = $pf_get_term_detail_idxx = $params['pf_get_term_detail_idm'];
          $pf_get_term_detail_idm_parent = $params['pf_get_term_detail_idm_parent'];
          
          $output_data = $pf_get_term_detail_id_output = '';

          if(class_exists('SitePress')) {
            $pf_get_term_detail_id = apply_filters( 'wpml_object_id',$params['pf_get_term_detail_idm'],'pointfinderltypes',true,$params['dlang']);
            $pf_get_term_detail_idm_parent = apply_filters( 'wpml_object_id',$params['pf_get_term_detail_idm_parent'],'pointfinderltypes',true,$params['dlang']);
            $pf_get_term_detail_idxx = apply_filters( 'wpml_object_id',$params['pf_get_term_detail_idm'],'pointfinderltypes',true,$params['clang']);
          }

          if ($params['st8_npsys'] == 1) {
            $run_parent_check = false;

            if(isset($listing_meta[$pf_get_term_detail_id])){
              $slisting_meta = $listing_meta[$pf_get_term_detail_id];
              $icon_type = (isset($slisting_meta['cpoint_type']))?$slisting_meta['cpoint_type']:0;
              if (empty($icon_type)) {
                $run_parent_check = true;
              }else{
                $run_parent_check = false;
                $pf_get_term_detail_id_output = $pf_get_term_detail_id;
              }
            }else{
              $slisting_meta = '';
              $run_parent_check = true;
            }

            /* If 2nd level */
            if ($run_parent_check && !empty($pf_get_term_detail_idm_parent)) {
              if(isset($listing_meta[$pf_get_term_detail_idm_parent])){
                $slisting_meta = $listing_meta[$pf_get_term_detail_idm_parent];
                $icon_type = (isset($slisting_meta['cpoint_type']))?$slisting_meta['cpoint_type']:0;
                if (empty($icon_type)) {
                  $run_parent_check = true;
                }else{
                  $run_parent_check = false;
                  $pf_get_term_detail_id_output = $pf_get_term_detail_idm_parent;
                }

              }else{
                $slisting_meta = '';
                $run_parent_check = true;
              }
            }

          
            /* If 3rd level */
            if ($run_parent_check && !empty($pf_get_term_detail_idm_parent)) {
              $top_most_parent = $this->pf_get_term_top_most_parent($pf_get_term_detail_id,"pointfinderltypes");
              $top_most_parent = (isset($top_most_parent['parent']))?$top_most_parent['parent']:'';
              
              if(isset($listing_meta[$top_most_parent])){
                $slisting_meta = $listing_meta[$top_most_parent];
                $pf_get_term_detail_id_output = $top_most_parent;
              }else{
                $slisting_meta = '';
              }
              $run_parent_check = false;
            }

            

            if (!empty($slisting_meta)) {

              $icon_type = (isset($slisting_meta['cpoint_type']))?$slisting_meta['cpoint_type']:0;
              $icon_layout_type = (isset($slisting_meta['cpoint_icontype']))?$slisting_meta['cpoint_icontype']:1;
              $icon_size = (isset($slisting_meta['cpoint_iconsize']))?$slisting_meta['cpoint_iconsize']:'middle';
              $icon_bg_color = (isset($slisting_meta['cpoint_bgcolor']))?$slisting_meta['cpoint_bgcolor']:'#b00000';
              $icon_name = (isset($slisting_meta['cpoint_iconname']))?$slisting_meta['cpoint_iconname']:'';
              $icon_namefs = (isset($slisting_meta['cpoint_iconnamefs']))?$slisting_meta['cpoint_iconnamefs']:'';
             
              if ($icon_type == 2) {
                $arrow_text = ($icon_layout_type == 2)? '<div class=\'pf-pinarrow\' style=\'border-color: '.$icon_bg_color.' transparent transparent transparent;\'></div>': '';

                $output_data .= '<div ';
                $output_data .= 'class=\'pfcat'.$pf_get_term_detail_id_output.'-mapicon pf-map-pin-'.$icon_layout_type.' pf-map-pin-'.$icon_layout_type.'-'.$icon_size.'\'';
                $output_data .= '>';
                if (!empty($icon_namefs)) {
                  $output_data .= '<i class=\''.$icon_namefs.'\'></i></div>'.$arrow_text.'';
                }else{
                  $output_data .= '<i class=\''.$icon_name.'\'></i></div>'.$arrow_text.'';
                }
              }elseif($icon_type == 0){
                  $icon_layout_type = $this->PFASSIssetControl('cpoint_icontype','',1);
                  $icon_name = $this->PFASSIssetControl('cpoint_iconname','','');
                  $icon_namefs = $this->PFASSIssetControl('cpoint_iconnamefs','','');
                  $icon_size = $this->PFASSIssetControl('cpoint_iconsize','','middle');
                  $icon_bg_color = $this->PFASSIssetControl('cpoint_bgcolor','','#b00000');

                  $arrow_text = ($icon_layout_type == 2)? '<div class=\'pf-pinarrow\' style=\'border-color: '.$icon_bg_color.' transparent transparent transparent;\'></div>': '';

                  $output_data .= '<div ';
                  $output_data .= 'class=\'pfcatdefault-mapicon pf-map-pin-'.$icon_layout_type.' pf-map-pin-'.$icon_layout_type.'-'.$icon_size.'\'';
                  $output_data .= ' >';
                  if (!empty($icon_namefs)) {
                    $output_data .= '<i class=\''.$icon_namefs.'\' ></i></div>'.$arrow_text.';';
                  }else{
                    $output_data .= '<i class=\''.$icon_name.'\' ></i></div>'.$arrow_text.';';
                  }

              }else{

                $output_data .= '<div ';
                $output_data .= 'class=\'pfcat'.$pf_get_term_detail_id_output.'-mapicon\'';
                $output_data .= '>';
                $output_data .= '</div>';
              }
            }else{

              /* Check parent term has settings */
              if ($params['cpoint_type'] == 0) {
                $arrow_text = ($params['cpoint_icontype'] == 2)? '<div class=\'pf-pinarrow\' style=\'border-color: '.$params['cpoint_bgcolor'].' transparent transparent transparent;\'></div>': '';

                $output_data .= '<div ';
                $output_data .= 'class=\'pfcatdefault-mapicon pf-map-pin-'.$params['cpoint_icontype'].' pf-map-pin-'.$params['cpoint_icontype'].'-'.$params['cpoint_iconsize'].'\'';
                $output_data .= ' >';
                $output_data .= '<i class=\''.$params['cpoint_iconname'].'\' ></i></div>'.$arrow_text.'';
              }else{
                $output_data .= '<div ';
                $output_data .= 'class=\'pfcatdefault-mapicon\'';
                $output_data .= '>';
                $output_data .= '</div>';
              }
            }
              
          }else{
            
            $icon_type = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_type','','0');

            $icon_bg_image = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_bgimage','','0');

            $icon_layout_type = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_icontype','','1');
            $icon_name = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_iconname','','');
            $icon_namefs = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_iconfs','','');
            $icon_size = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_iconsize','','middle');
            $icon_bg_color = $this->PFPFIssetControl('pscp_'.$pf_get_term_detail_id.'_bgcolor','','#b00000');
            
            $arrow_text = ($icon_layout_type == 2)? '<div class=\'pf-pinarrow\' style=\'border-color: '.$icon_bg_color.' transparent transparent transparent;\'></div>': '';

            if ($icon_type == 0 && empty($icon_bg_image)) {

              $output_data .= '<div ';
              $output_data .= 'class=\'pfcat'.$pf_get_term_detail_id.'-mapicon pf-map-pin-'.$icon_layout_type.' pf-map-pin-'.$icon_layout_type.'-'.$icon_size.'\'';
              $output_data .= ' >';
              if (!empty($icon_namefs)) {
                $output_data .= '<i class=\''.$icon_namefs.'\' ></i></div>';
              }else{
                $output_data .= '<i class=\''.$icon_name.'\' ></i></div>';
              }
              
            
            }elseif ($icon_type != 0 && !empty($icon_bg_image)){

              $height_calculated = $icon_bg_image['height'];
              $width_calculated = $icon_bg_image['width'];

              $output_data .= '<div ';
              $output_data .= 'class=\'pf-map-pin-x\' ';
              $output_data .= 'style=\'background-image:url('.$icon_bg_image['url'].');opacity:1; background-size:'.$width_calculated.'px '.$height_calculated.'px; width:'.$width_calculated.'px; height:'.$height_calculated.'px;\'';
              $output_data .= ' >';
              $output_data .= '</div>';
            
            }else{

              $output_data .= '<div ';
              $output_data .= 'class=\'pfcat'.$pf_get_term_detail_id.'-mapicon pf-map-pin-'.$icon_layout_type.' pf-map-pin-'.$icon_layout_type.'-'.$icon_size.'\'';
              $output_data .= ' >';
              if (!empty($icon_namefs)) {
               $output_data .= '<i class=\''.$icon_namefs.'\' ></i></div>'.$arrow_text.'';
              }else{
                $output_data .= '<i class=\''.$icon_name.'\' ></i></div>'.$arrow_text.'';
              }

            }
          }
          
          return $output_data;
        }
    }
}
