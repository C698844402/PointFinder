<?php
/**********************************************************************************************************************************
*
* Size Control Settings
* 
* Author: Webbu
*
***********************************************************************************************************************************/

if (!class_exists("Redux_Framework_PF_sizecontrol_Config")) {
	

    class Redux_Framework_PF_sizecontrol_Config{

        public $args = array();
        public $sections = array();
        public $theme;
        public $ReduxFramework;
        private $newoptions;

        public function __construct() {
            $this->initSettings();
        }

        public function initSettings() {
            $this->setArguments(); 
            $this->setSections();
            if (!isset($this->args['opt_name'])) { return;}
            add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }
		


        private function PFSizeSIssetControlN($field, $field2 = '', $default = '',$icl_exit = 0){

            if($field2 == ''){
              if (!isset($this->newoptions[''.$field.''])) {
                return $default;
              }
              if ($this->newoptions[''.$field.''] == "") {
                return $default;
              }
              return $this->newoptions[''.$field.''];
            }else{
              if (!isset($this->newoptions[''.$field.''][''.$field2.''])) {
                return $default;
              }
              if ($this->newoptions[''.$field.''][''.$field2.''] == "") {
                return $default;
              }
              return $this->newoptions[''.$field.''][''.$field2.''];
            };
        }

        public function compiler_action($options, $css) {

            if (!empty($options)) {
                $this->newoptions = $options;
            }

            $setupsizelimitconf_general_gallerysize2_h = $this->PFSizeSIssetControlN('setupsizelimitconf_general_gallerysize2','height','100');
            $setupsizelimitconf_general_gallerysize2_w = $this->PFSizeSIssetControlN('setupsizelimitconf_general_gallerysize2','width','112');

            $setupsizelimitconf_general_gallerysize1_h = $this->PFSizeSIssetControlN('setupsizelimitconf_general_gallerysize1','height','566');
            $setupsizelimitconf_general_gallerysize1_w = $this->PFSizeSIssetControlN('setupsizelimitconf_general_gallerysize1','width','848');

            $setupsizelimitconf_general_gridsize2_height = $this->PFSizeSIssetControlN('setupsizelimitconf_general_gridsize2','height',416);
            $setupsizelimitconf_general_gridsize3_height = $this->PFSizeSIssetControlN('setupsizelimitconf_general_gridsize3','height',270);
            $setupsizelimitconf_general_gridsize4_width = $this->PFSizeSIssetControlN('setupsizelimitconf_general_gridsize4','width',263);
            $setupsizelimitconf_general_gridsize4_height = $this->PFSizeSIssetControlN('setupsizelimitconf_general_gridsize4','height',197);


            $css .= "#pfitemdetail-slider-sub li img{height:".$setupsizelimitconf_general_gallerysize2_h."px;}";
            $css .= "#pfitemdetail-slider li img{height:auto!important;max-height:".$setupsizelimitconf_general_gallerysize1_h."px;}";
            $css .= "#pfitemdetail-slider li .pfshoworiginalitemphotomain img{height:auto!important;max-height:100%;}";

            $css .= "#pfitemdetail-slider-sub li .pfshoworiginalitemphoto{max-width:".$setupsizelimitconf_general_gallerysize2_w."px!important;width:".$setupsizelimitconf_general_gallerysize2_w."px;height:".$setupsizelimitconf_general_gallerysize2_h."px;text-align:center;}";
            $css .= "#pfitemdetail-slider li .pfshoworiginalitemphotomain{max-width:".$setupsizelimitconf_general_gallerysize1_w."px!important;width:".$setupsizelimitconf_general_gallerysize1_w."px;height:".$setupsizelimitconf_general_gallerysize1_h."px;text-align:center;}";


            $css .= '.pfitemlists-content-elements.pf4col .wpfitemlistdata .pflist-imagecontainer .pfuorgcontainer img{max-height: '.$setupsizelimitconf_general_gridsize4_height.'px!important;width: auto;max-width:100%;}';
            $css .= '.pfitemlists-content-elements.pf3col .wpfitemlistdata .pflist-imagecontainer .pfuorgcontainer img{max-height: '.$setupsizelimitconf_general_gridsize3_height.'px!important;width: auto;max-width:100%;}';
            $css .= '.pfitemlists-content-elements.pf2col .wpfitemlistdata .pflist-imagecontainer .pfuorgcontainer img{max-height: '.$setupsizelimitconf_general_gridsize2_height.'px!important;width: auto;max-width:100%;}';
            $css .= '.pfitemlists-content-elements.pf1col .wpfitemlistdata .pflist-imagecontainer .pfuorgcontainer img{max-height: '.$setupsizelimitconf_general_gridsize4_height.'px!important;width: auto;max-width: '.$setupsizelimitconf_general_gridsize4_width.'px!important;}';
            $css .= '.pfitemlists-content-elements.pf1col .wpfitemlistdata .pflist-imagecontainer .pfuorgcontainer{text-align: center;min-width: '.$setupsizelimitconf_general_gridsize4_width.'px!important;}';



            $redux = ReduxFrameworkInstances::get_instance($this->args['opt_name']);

            $uploads = wp_upload_dir();
            $upload_dir = trailingslashit($uploads['basedir']);
            $upload_dir = $upload_dir . '/pfstyles';

            if ( ! is_dir( $upload_dir ) ) {
              $redux->filesystem->execute( "mkdir", $upload_dir );
            }

            $filename = trailingslashit($uploads['basedir']) . '/pfstyles/pf-style-psizestyles' . '.css';

            $redux->filesystem->execute( 
                'put_contents', 
                $filename, 
                array( 
                    'content' => $css
                ) 
            );
        }

        

        public function setSections() {        
			
            /**
            *Start : Image Sizes 
            **/
                $this->sections[] = array(
                    'id' => 'setupsizelimitconf_general',
                    'title' => esc_html__('Image Size Settings', 'pointfindercoreelements'),
                    'icon' => 'el-icon-resize-full',
                    'fields' => array(
                        array(
                            'id'     => 'setupsizelimitconf_general_gridsize1_help1',
                            'type'   => 'info',
                            'notice' => true,
                            'style'  => 'critical',
                            'title'  => esc_html__( 'IMPORTANT', 'pointfindercoreelements' ),
                            'desc'   => esc_html__( 'Please make sure you are changing correctly. Because these settings will change all your image sizes.', 'pointfindercoreelements' )
                        ),
                        /*Start:(Ajax Grid / Static Grid / Item Carousel)*/
                        array(
                           'id' => 'setupsizelimitconf_general_gridsize1-start',
                           'type' => 'section',
                           'title' => esc_html__('Item Detail Page Gallery Image Sizes', 'pointfindercoreelements'),
                           'subtitle' => esc_html__('This sizes will effect Item Page Image Gallery', 'pointfindercoreelements'),
                           'indent' => true 
                        ),
                            array(
                                'id' => 'general_crop',
                                'type' => 'button_set',
                                'title' => esc_html__('Item Page Gallery Images', 'pointfindercoreelements') ,
                                'options' => array(
                                    '1' => esc_html__('Force Crop', 'pointfindercoreelements') ,
                                    '2' => esc_html__('Use Default', 'pointfindercoreelements'),
                                    '3' => esc_html__("Use Original", 'pointfindercoreelements')
                                ) , 
                                'default' => '1',
                                'desc'           => esc_html__('Please use Force Crop for same sized images. Use Default for leave free size. Use Original for resized and centered images (best for vertical images.)', 'pointfindercoreelements'),
                                'compiler' => true
                            ) ,
                            array(
                                'id'             => 'setupsizelimitconf_general_gallerysize1',
                                'type'           => 'dimensions',
                                'units'          => false,
                                'units_extended' => false,
                                'title'          => esc_html__('Item Page Gallery Photos Min. Size (Width/Height)', 'pointfindercoreelements'),
                                'desc'           => esc_html__('All size units (px)', 'pointfindercoreelements').' (848x566)',
                                'default'        => array(
                                    'width'  => 848,
                                    'height' => 566,
                                ),
                                'compiler' => true
                            ),
                            array(
                                'id'             => 'setupsizelimitconf_general_gallerysize2',
                                'type'           => 'dimensions',
                                'units'          => false,
                                'units_extended' => false,
                                'title'          => esc_html__('Item Page Gallery (THUMB) Photos Min. Size (Width/Height)', 'pointfindercoreelements'),
                                'desc'           => esc_html__('All size units (px)', 'pointfindercoreelements').' (112x100)',
                                'default'        => array(
                                    'width'  => 112,
                                    'height' => 100,
                                ),
                                'compiler' => true
                            ),
                        array(
                           'id' => 'setupsizelimitconf_general_gridsize1-end',
                           'type' => 'section',
                           'indent' => false 
                        ),
                        /*End:(Ajax Grid / Static Grid / Item Carousel)*/   


                        /*Start:(VC_Carousel, VC_Image_Carousel, VC_Client Carousel, VC_Gallery)*/
                        array(
                           'id' => 'setupsizelimitconf_general_gridsize2-start',
                           'type' => 'section',
                           'title' => esc_html__('Grid/Carousel Image Sizes', 'pointfindercoreelements'),
                           'subtitle' => esc_html__('This sizes will effect Visual Composer Post Carousel, PF Image Carousel, PF Client Carousel, PF Grid Images', 'pointfindercoreelements'),
                           'indent' => true 
                        ),
                            array(
                                'id' => 'general_crop2',
                                'type' => 'button_set',
                                'title' => esc_html__('Grid Photos Images', 'pointfindercoreelements') ,
                                'options' => array(
                                    '1' => esc_html__('Force Crop', 'pointfindercoreelements') ,
                                    '2' => esc_html__('Use Default', 'pointfindercoreelements'),
                                    '3' => esc_html__("Use Original", 'pointfindercoreelements')
                                ) , 
                                'default' => '1',
                                'desc'           => esc_html__('Please use Force Crop for same sized images. Use Default for leave free size. Use Original for resized and centered images (best for vertical images.)', 'pointfindercoreelements'),
                                'compiler' => true
                            ) ,
                            array(
                                'id'             => 'setupsizelimitconf_general_gridsize1',
                                'type'           => 'dimensions',
                                'units'          => false,
                                'units_extended' => false,
                                'title'          => esc_html__('Grid Photos Min. Size (Width/Height)', 'pointfindercoreelements'),
                                'desc'           => esc_html__('All size units (px)', 'pointfindercoreelements').' (440x330)',
                                'default'        => array(
                                    'width'  => 440,
                                    'height' => 330,
                                ),
                                'compiler' => true
                            ),
                            array(
                                'id'             => 'setupsizelimitconf_general_gridsize2',
                                'type'           => 'dimensions',
                                'units'          => false,
                                'units_extended' => false,
                                'title'          => esc_html__('2 Cols. Min Size (Width/Height)', 'pointfindercoreelements'),
                                'desc'           => esc_html__('All size units (px)', 'pointfindercoreelements').' (555x416)',
                                'default'        => array(
                                    'width'  => 555,
                                    'height' => 416,
                                ),
                                'compiler' => true
                            ),
                            array(
                                'id'             => 'setupsizelimitconf_general_gridsize3',
                                'type'           => 'dimensions',
                                'units'          => false,
                                'units_extended' => false,
                                'title'          => esc_html__('3 Cols. Min Size (Width/Height)', 'pointfindercoreelements'),
                                'desc'           => esc_html__('All size units (px)', 'pointfindercoreelements').' (360x270)',
                                'default'        => array(
                                    'width'  => 360,
                                    'height' => 270,
                                ),
                                'compiler' => true
                            ),
                            array(
                                'id'             => 'setupsizelimitconf_general_gridsize4',
                                'type'           => 'dimensions',
                                'units'          => false,
                                'units_extended' => false,
                                'title'          => esc_html__('4 Cols. Min Size (Width/Height)', 'pointfindercoreelements'),
                                'desc'           => esc_html__('All size units (px)', 'pointfindercoreelements').' (263x197)',
                                'default'        => array(
                                    'width'  => 263,
                                    'height' => 197,
                                ),
                                'compiler' => true
                            ),

                        array(
                           'id' => 'setupsizelimitconf_general_gridsize2-start',
                           'type' => 'section',
                           'indent' => false 
                        ),
                        /*End:(VC_Carousel, VC_Image_Carousel, VC_Client Carousel, VC_Gallery)*/

                    )
                );
            /**
            *End : Image Sizes
            **/
        }

        

        public function setArguments() {


            $this->args = array(

                'opt_name'             => 'pfsizecontrol_options',
                'display_name'         => esc_html__('Point Finder Size Limits','pointfindercoreelements'),
                'menu_type'            => 'submenu',
                'page_parent'          => 'pointfinder_tools',
                'menu_title'           => esc_html__('Size Limits Config','pointfindercoreelements'),
                'page_title'           => esc_html__('Size Limits Config', 'pointfindercoreelements'),
                'admin_bar'            => false,
                'allow_sub_menu'       => false,
                'admin_bar_priority'   => 50,
                'global_variable'      => '',
                'dev_mode'             => false,
                'update_notice'        => false,
                'menu_icon'            => 'dashicons-twitter',
                'page_slug'            => '_pfsizelimitconf',
                'save_defaults'        => true,
                'default_show'         => false,
                'default_mark'         => '',
                'transient_time'       => 60 * MINUTE_IN_SECONDS,
                'output'               => false,
                'output_tag'           => false,
                'database'             => '',
                'system_info'          => false,
                'domain'               => 'redux-framework',
                'hide_reset'           => true,
                'update_notice'        => false,  
            );


        }

    }

    new Redux_Framework_PF_sizecontrol_Config();
	
}