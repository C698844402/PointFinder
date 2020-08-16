<?php

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if ( ! class_exists( 'ReduxFramework_extension_streetview' ) ) {

        
        class ReduxFramework_extension_streetview {

           
            function __construct( $field = array(), $value = '') {

                $this->field  = $field;
                $this->value  = $value;

                $defaults = array(
                    'heading' => 0,
                    'pitch'   => 0,
                    'zoom' => 0,
                );

                $this->value = wp_parse_args( $this->value, $defaults );

                /*
                    Array ( 
                    [id] => webbupointfinder_item_streetview 
                    [type] => extension_streetview 
                    [class] => 
                    [name] => pointfinderthemefmb_options[webbupointfinder_item_streetview] 
                    [name_suffix] => )
                */
            }


            public function render() {

                $pointfinder_center_lat = PFSAIssetControl('setup42_searchpagemap_lat','','40.71275');
                $pointfinder_center_lng = PFSAIssetControl('setup42_searchpagemap_lng','','-74.00597');
                $pointfinder_google_map_zoom = PFSAIssetControl('setup42_searchpagemap_zoom','','12');

                $pfitemid = get_the_id();
                if (isset($pfitemid)) {
                    $pffmarkerget = esc_attr(get_post_meta( $pfitemid, 'webbupointfinder_items_location', true ));
                    if (!empty($pffmarkerget)) {
                         $pfcoordinates = explode( ',', $pffmarkerget);
                    }
                }

                if (isset($pfcoordinates[0])) {
                    if (empty($pfcoordinates[0])) {
                        $pfcoordinates = '';
                    }
                }
                if (!empty($pfcoordinates)) {
                    if (!is_array($pfcoordinates)) {
                        $pfcoordinates = array($pointfinder_center_lat,$pointfinder_center_lng,$pointfinder_google_map_zoom);
                    }else{
                        if (isset($pfcoordinates[2]) == false) {
                            $pfcoordinates[2] = $pointfinder_google_map_zoom;
                        }
                    }
                }else{
                    $pfcoordinates = array($pointfinder_center_lat,$pointfinder_center_lng,$pointfinder_google_map_zoom);
                }
               
                
                echo '<div id="pfitempagestreetviewMap" style="width:100%; height:400px;" data-pfitemid="' . $this->field['id'] . '" data-pfcoordinateslat="'.$pfcoordinates[0].'" data-pfcoordinateslng="'.$pfcoordinates[1].'" data-pfzoom = "'.$pfcoordinates[2].'"></div>';
                echo '<input id="' . $this->field['id'] . '-heading" name="' . $this->field['name'] . '[heading]' . $this->field['name_suffix'] . '" value="' . $this->value['heading'] . '" type="hidden" />';
                echo '<input id="' . $this->field['id'] . '-pitch" name="' . $this->field['name'] . '[pitch]' . $this->field['name_suffix'] . '" value="' . $this->value['pitch'] . '" type="hidden" />';
                echo '<input id="' . $this->field['id'] . '-zoom" name="' . $this->field['name'] . '[zoom]' . $this->field['name_suffix'] . '" value="' . $this->value['zoom'] . '" type="hidden" />';

            }


            public function enqueue() {
              
            }

        }
    }