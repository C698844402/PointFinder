<?php
/**********************************************************************************************************************************
*
* Size Control Settings
*
* Author: Webbu
*
***********************************************************************************************************************************/

if (!class_exists("Redux_Framework_PF_PBcontrol_Config")) {


    class Redux_Framework_PF_PBcontrol_Config{

        public $args = array();
        public $sections = array();
        public $theme;
        public $ReduxFramework;

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


        public function compiler_action($options, $css) {

            $setup18_headerbarsettings_padding = $this->PFSAIssetControl('setup18_headerbarsettings_padding','margin-top',30);
            $setup18_headerbarsettings_padding_number = str_replace('px', '', $setup18_headerbarsettings_padding);

            $setup17_logosettings_sitelogo_height = $this->PFSAIssetControl('setup17_logosettings_sitelogo_height','height',30);
            $setup17_logosettings_sitelogo_height_number = str_replace('px', '', $setup17_logosettings_sitelogo_height);


            $pointfinder_navwrapper_height = ($setup18_headerbarsettings_padding_number*2) + $setup17_logosettings_sitelogo_height_number;

            $setup21_widgetsettings_3_slider_capt = (isset($options['setup21_widgetsettings_3_slider_capt']['color']))?$options['setup21_widgetsettings_3_slider_capt']['color']:'#000000';
            $general_postitembutton_bordercolor = (isset($options['general_postitembutton_bordercolor']['color']))?$options['general_postitembutton_bordercolor']['color']:'#ededed';
            $general_postitembutton_borderr = (isset($options['general_postitembutton_borderr']))?$options['general_postitembutton_borderr']:'0';
            $general_postitembutton_button_mtop = (isset($options['general_postitembutton_button_mtop']))?$options['general_postitembutton_button_mtop']:'26';

            $css .= '.pf-item-slider .pf-item-slider-golink:hover{background-color:'.$setup21_widgetsettings_3_slider_capt.'}';
            $css .= '#pfpostitemlink a {height: auto!important;line-height: 0px!important;margin-top: '.$general_postitembutton_button_mtop.'px!important;border-radius:'.$general_postitembutton_borderr.'px!important}';
          
            $css .= '@media (max-width:1199px){#pfpostitemlink{top:'.(($pointfinder_navwrapper_height - $general_postitembutton_button_mtop)).'px}}';


            $redux = ReduxFrameworkInstances::get_instance($this->args['opt_name']);

            $uploads = wp_upload_dir();
            $upload_dir = trailingslashit($uploads['basedir']);
            $upload_dir = $upload_dir . '/pfstyles';

            if ( ! is_dir( $upload_dir ) ) {
              $redux->filesystem->execute( "mkdir", $upload_dir );
            }

            $filename = trailingslashit($uploads['basedir']) . '/pfstyles/pf-style-pbstyles' . '.css';

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
            *Start : PAGE BUILDER SETTINS
            **/
                $this->sections[] = array(
                    'id' => 'general_pbcustomizer',
                    'title' => esc_html__('Page Builder Styles', 'pointfindercoreelements'),
                    'icon' => 'el-icon-website',
                    'fields' => array()

                );
                /**
                *Page Builder: Info Boxes
                **/
                $this->sections[] = array(
                    'id' => 'setup21_widgetsettings_4',
                    'subsection' => true,
                    'title' => esc_html__('Page Builder: Info Boxes', 'pointfindercoreelements'),
                    'desc'      => sprintf('<p class="description descriptionpf descriptionpfimg">'.esc_html__('%s You can change PF Info Box Typography.', 'pointfindercoreelements').'</p>','<img src="'.PFCOREELEMENTSURLADMIN . 'options/images/image_infobox.png" class="description-img" />'),
                    'fields' => array(
                            array(
                                'id' => 'setup21_iconboxsettings_title_typo',
                                'type' => 'typography',
                                'title' => esc_html__('Info Box Title Area', 'pointfindercoreelements') ,
                                'google' => true,
                                'font-backup' => true,
                                'font-size' => false,
                                'line-height' => false,
                                'text-align' => false,
                                'compiler' => array(
                                    '.pf-iconbox-wrapper .pf-iconbox-title'
                                ) ,
                                'units' => 'px',
                                'color' => false,
                                'default' => array(
                                    'font-weight' => '600',
                                    'font-family' => 'Open Sans',
                                    'google' => true,

                                ) ,
                            ) ,
                            array(
                                'id' => 'setup21_iconboxsettings_typ1_typo',
                                'type' => 'typography',
                                'title' => esc_html__('Info Box Text Area', 'pointfindercoreelements') ,
                                'google' => true,
                                'font-backup' => true,
                                'font-size' => false,
                                'line-height' => false,
                                'text-align' => false,
                                'color' => false,
                                'compiler' => array(
                                    '.pf-iconbox-wrapper .pf-iconbox-text',
                                    '.pf-iconbox-wrapper .pf-iconbox-readmore'
                                ) ,
                                'units' => 'px',
                                'default' => array(
                                    'font-weight' => '400',
                                    'font-family' => 'Open Sans',
                                    'google' => true,
                                ) ,
                            )
                    )
                );



                /**
                *Page Builder: Item Slider
                **/
                $this->sections[] = array(
                    'id' => 'setup21_widgetsettings_3',
                    'subsection' => true,
                    'title' => esc_html__('Page Builder: Item Slider', 'pointfindercoreelements'),
                    'desc' => sprintf('<p class="description descriptionpf descriptionpfimg">'.esc_html__('%s Blue area on the image refers to PF Items Slider.','pointfindercoreelements').'<br/>'.esc_html__('You can change styles of this area by using below options.', 'pointfindercoreelements').'</p>','<img src="'.PFCOREELEMENTSURLADMIN . 'options/images/image_itemslider.png" class="description-img" />'),
                    'fields' => array(
                            array(
                                'id'        => 'setup21_widgetsettings_3_slider_capt',
                                'type'      => 'color_rgba',
                                'title'     => esc_html__('Description Box Background', 'pointfindercoreelements'),
                                'default'   => array('color' => '#000', 'alpha' => '0.8'),
                                'compiler'  => array(
                                    '.pf-item-slider .pf-item-slider-description',
                                    '.pf-item-slider .pf-item-slider-price',
                                    '.pf-item-slider .pf-item-slider-golink'
                                ),
                                'mode'      => 'background',
                                'validate'  => 'colorrgba',
                            ),
                            array(
                                'id' => 'setup21_widgetsettings_3_title_color',
                                'type' => 'link_color',
                                'title' => esc_html__('Title/Type Area Link Color', 'pointfindercoreelements') ,
                                'compiler' => array(
                                    '.pf-item-slider-description .pf-item-slider-title a',
                                    '.pf-item-slider .pflistingitem-subelement.pf-price',
                                    '.pf-item-slider .pf-item-slider-golink a',
                                    '.anemptystylesheet'
                                ) ,
                                'active' => false,
                                'default' => array(
                                    'regular' => '#fff',
                                    'hover' => '#efefef'
                                )
                            ) ,
                            array(
                                'id' => 'setup21_widgetsettings_3_title_typo',
                                'type' => 'typography',
                                'title' => esc_html__('Title Area Typography', 'pointfindercoreelements') ,
                                'google' => true,
                                'font-backup' => true,
                                'compiler' => array(
                                    '.pf-item-slider-description .pf-item-slider-title',
                                    '.pf-item-slider .pflistingitem-subelement.pf-price',
                                    '.pf-item-slider .pf-item-slider-golink'
                                ) ,
                                'units' => 'px',
                                'color' => false,
                                'default' => array(
                                    'font-weight' => '400',
                                    'font-family' => 'Roboto Condensed',
                                    'google' => true,
                                    'font-size' => '25px',
                                    'line-height' => '25px',
                                    'text-align' => 'left'
                                )
                            ) ,
                            array(
                                'id' => 'setup21_widgetsettings_3_address_color',
                                'type' => 'link_color',
                                'title' => esc_html__('Address Area Link Color', 'pointfindercoreelements') ,
                                'compiler' => array(
                                    '.pf-item-slider-description .pf-item-slider-address a'
                                ) ,
                                'active' => false,
                                'default' => array(
                                    'regular' => '#fff',
                                    'hover' => '#efefef'
                                )
                            ) ,
                            array(
                                'id' => 'setup21_widgetsettings_3_address_typo',
                                'type' => 'typography',
                                'title' => esc_html__('Address Typography', 'pointfindercoreelements') ,
                                'google' => true,
                                'font-backup' => true,
                                'compiler' => array(
                                    '.pf-item-slider-description .pf-item-slider-address'
                                ) ,
                                'units' => 'px',
                                'color' => false,
                                'default' => array(
                                    'font-weight' => '400',
                                    'font-family' => 'Open Sans',
                                    'google' => true,
                                    'font-size' => '14px',
                                    'line-height' => '16px',
                                    'text-align' => 'left'
                                )
                            ),
                            array(
                                'id' => 'setup21_widgetsettings_3_typ1_typo',
                                'type' => 'typography',
                                'title' => esc_html__('Excerpt Area Typography', 'pointfindercoreelements') ,
                                'google' => true,
                                'font-backup' => true,
                                'compiler' => array(
                                    '.pf-item-slider-description .pf-item-slider-excerpt'
                                ) ,
                                'units' => 'px',
                                'color' => true,
                                'default' => array(
                                    'font-weight' => '400',
                                    'font-family' => 'Open Sans',
                                    'google' => true,
                                    'font-size' => '12px',
                                    'line-height' => '15px',
                                    'color' => '#fff',
                                    'text-align' => 'left'
                                )
                            )
                    )
                );
            /**
            *End : PAGE BUILDER SETTINS
            **/



            /**
            *Start : POST BUTTON STYLES
            **/

                 $this->sections[] = array(
                    'id' => 'general_postitembutton',
                    'title' => esc_html__('Post Item Button Styles', 'pointfindercoreelements'),
                    'icon' => 'el-icon-plus',
                    'fields' => array(
                        array(
                            'id' => 'general_postitembutton_status',
                            'type' => 'button_set',
                            'title' => esc_html__('Button Status', 'pointfindercoreelements') ,
                            'default' => 1,
                            'options' => array(
                                '1' => esc_html__('Show', 'pointfindercoreelements') ,
                                '0' => esc_html__('Hide', 'pointfindercoreelements')
                            ),
                        ),
                        array(
                            'id' => 'general_postitembutton_linkcolor',
                            'type' => 'link_color',
                            'title' => esc_html__('Text Color', 'pointfindercoreelements') ,
                            'compiler' => array(
                                '.wpf-header #pf-primary-nav .pfnavmenu #pfpostitemlink a',
                                '.anemptystylesheet'
                            ) ,
                            'active' => false,
                            'default' => array(
                                'regular' => '#fff',
                                'hover' => '#efefef'
                            )
                        ) ,

                        array(
                            'id' => 'general_postitembutton_linkcolor_typo',
                            'type' => 'typography',
                            'title' => esc_html__('Text Typography', 'pointfindercoreelements') ,
                            'google' => true,
                            'font-backup' => true,
                            'compiler' => array(
                                '.wpf-header #pf-primary-nav .pfnavmenu #pfpostitemlink a'
                            ) ,
                            'units' => 'px',
                            'color' => false,
                            'line-height' => false,
                            'text-align' => false,
                            'default' => array(
                                'font-weight' => '400',
                                'font-family' => 'Open Sans',
                                'google' => true,
                                'font-size' => '12px'
                            )
                        ),
                        array(
                            'id' => 'general_postitembutton_bgcolor',
                            'type' => 'extension_custom_link_color',
                            'mode' => 'background',
                            'title' => esc_html__('Background Color', 'pointfindercoreelements') ,
                            'compiler' => array(
                                '.wpf-header #pf-primary-nav .pfnavmenu #pfpostitemlink a',
                                '.anemptystylesheet'
                            ) ,
                            'active' => false,
                            'default' => array(
                                'regular' => '#ad2424',
                                'hover' => '#ce2f2f'
                            )
                        ) ,

                        array(
                            'id'       => 'general_postitembutton_border',
                            'type'     => 'border',
                            'title'    => esc_html__( 'Border Option', 'pointfindercoreelements' ),
                            'compiler' => array(
                                '.wpf-header #pf-primary-nav .pfnavmenu #pfpostitemlink a',
                                '.anemptystylesheet'
                            ) ,
                            'all'      => true,
                            'default'  => array(
                                'border-color'  => '#efefef',
                                'border-style'  => 'solid',
                                'border-top'    => '1px',
                                'border-right'  => '1px',
                                'border-bottom' => '1px',
                                'border-left'   => '1px'
                            )
                        ),
                        array(
                            'id'      => 'general_postitembutton_borderr',
                            'type'    => 'spinner',
                            'title'   => esc_html__( 'Border Radius', 'pointfindercoreelements' ),
                            'desc'    => esc_html__( 'px', 'pointfindercoreelements' ),
                            'default' => '50',
                            'min'     => '0',
                            'step'    => '1',
                            'max'     => '100',
                            'compiler' => true
                        ),
                        array(
                            'id' => 'general_postitembutton_iconstatus',
                            'type' => 'button_set',
                            'title' => esc_html__('Icon Status', 'pointfindercoreelements') ,
                            'default' => 0,
                            'options' => array(
                                '1' => esc_html__('Show', 'pointfindercoreelements') ,
                                '0' => esc_html__('Hide', 'pointfindercoreelements')
                            ),
                            'compiler' => true
                        ),
                        array(
                            'id' => 'pnewiconname',
                            'type' => 'text',
                            'title' => esc_html__('Icon Class Name', 'pointfindercoreelements') ,
                            'desc' => esc_html__('Please use Fontawesome 5 Free Icon class name.', 'pointfindercoreelements') ,
                            'default' => 'fas fa-plus',
                            'required' => array('general_postitembutton_iconstatus','=',1)
                        ) ,
                        array(
                            'id' => 'pnewiconsize',
                            'type' => 'typography',
                            'title' => esc_html__('Icon Size', 'pointfindercoreelements') ,
                            'google' => false,
                            'font-backup' => false,
                            'font-family' => false,
                            'font-style' => false,
                            'font-weight' => false,
                            'font-backup' => false,
                            'compiler' => array(
                                '.wpf-header #pf-primary-nav .pfnavmenu #pfpostitemlink a i'
                            ) ,
                            'units' => 'px',
                            'color' => false,
                            'line-height' => false,
                            'text-align' => false,
                            'subsets' => false,
                            'default' => array(
                                'font-size' => '11px'
                            ),
                            'preview' => false,
                            'required' => array('general_postitembutton_iconstatus','=',1)
                        ),
                        array(
                            'id'             => 'general_postitembutton_innerpadding',
                            'type'           => 'spacing',
                            'mode'           => 'padding',
                            'all'            => false,
                            'units'          => array('px'),
                            'compiler' => array(
                                '.wpf-header #pf-primary-nav .pfnavmenu #pfpostitemlink a',
                                '.wpf-header.pfshrink #pf-primary-nav .pfnavmenu #pfpostitemlink a',
                                '.anemptystylesheet'
                            ) ,
                            'units_extended' => 'false',
                            'title'          => esc_html__( 'Inner Padding Option', 'pointfindercoreelements' ),
                            'default'        => array(
                                'padding-top'    => '17px',
                                'padding-right'  => '20px',
                                'padding-bottom' => '17px',
                                'padding-left'   => '20px'
                            )
                        ),
                        array(
                            'id' => 'general_postitembutton_buttontext',
                            'type' => 'text',
                            'title' => esc_html__('Button Text', 'pointfindercoreelements') ,
                            'default' => esc_html__('Post New Point', 'pointfindercoreelements') ,
                        ) ,
                        array(
                            'id'      => 'general_postitembutton_button_mtop',
                            'type'    => 'spinner',
                            'title'   => esc_html__( 'Button Top Margin (px)', 'pointfindercoreelements' ),
                            'default' => '30',
                            'min'     => '0',
                            'step'    => '1',
                            'max'     => '300',
                            'compiler' => true
                        ),

                    )

                );

            /**
            *End : POST BUTTON STYLES
            **/

        }



        public function setArguments() {


            $this->args = array(
                'opt_name'             => 'pfpbcontrol_options',
                'display_name'         => esc_html__('Point Finder Extra Styles','pointfindercoreelements'),
                'menu_type'            => 'submenu',
                'page_parent'          => 'pointfinder_tools',
                'menu_title'           => esc_html__('Extra Styles','pointfindercoreelements'),
                'page_title'           => esc_html__('Extra Styles', 'pointfindercoreelements'),
                'admin_bar'            => false,
                'allow_sub_menu'       => false,
                'admin_bar_priority'   => 50,
                'global_variable'      => '',
                'dev_mode'             => false,
                'update_notice'        => false,
                'menu_icon'            => 'dashicons-twitter',
                'page_slug'            => '_pfpbconf',
                'save_defaults'        => true,
                'default_show'         => false,
                'default_mark'         => '',
                'transient_time'       => 60 * MINUTE_IN_SECONDS,
                'output'               => true,
                'output_tag'           => false,
                'database'             => '',
                'system_info'          => false,
                'domain'               => 'redux-framework',
                'hide_reset'           => true,
                'update_notice'        => false,
                'compiler'             => true,
            );


        }

    }
    global $pointfinderpfpbcontrols;
    $pointfinderpfpbcontrols = new Redux_Framework_PF_PBcontrol_Config();

}
