<?php
/**********************************************************************************************************************************
*
* Point Finder Twitter Widget
*
* Author: Webbu
*
***********************************************************************************************************************************/

if (!class_exists("Redux_Framework_PF_TWGenerator_Config")) {


    class Redux_Framework_PF_TWGenerator_Config{

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
            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }
        

        public function setSections() {

            /**
            *Start : Twitter Widget
            **/
                $this->sections[] = array(
                    'id' => 'setuptwitter_widget',
                    'title' => esc_html__('Twitter Widget', 'pointfindercoreelements'),
                    'icon' => 'el-icon-twitter',
                    'fields' => array(
                        array(
                            'id' => 'setuptwitterwidget_general_help',
                            'type' => 'info',
                            'notice' => true,
                            'style' => 'info',
                            'desc' => esc_html__('Please check help docs for setup below settings.', 'pointfindercoreelements')
                        ) ,

                        array(
                            'id' => 'setuptwitterwidget_conkey',
                            'type' => 'text',
                            'title' => esc_html__('Consumer Key', 'pointfindercoreelements') ,
                        ) ,
                        array(
                            'id' => 'setuptwitterwidget_consecret',
                            'type' => 'text',
                            'title' => esc_html__('Consumer Secret', 'pointfindercoreelements') ,
                        ),

                        array(
                            'id' => 'setuptwitterwidget_acckey',
                            'type' => 'text',
                            'title' => esc_html__('Access Token Key', 'pointfindercoreelements') ,
                        ) ,
                        array(
                            'id' => 'setuptwitterwidget_accsecret',
                            'type' => 'text',
                            'title' => esc_html__('Access Token Secret', 'pointfindercoreelements') ,
                        ),



                    )
                );
            /**
            *End : Twitter Widget
            **/

        }



        public function setArguments() {


            $this->args = array(

                'opt_name'             => 'pftwitterwidget_options',
                'display_name'         => esc_html__('Point Finder Twitter Widget','pointfindercoreelements'),
                'menu_type'            => 'submenu',
                'page_parent'          => 'pointfinder_tools',
                'menu_title'           => esc_html__('Twitter Widget Config','pointfindercoreelements'),
                'page_title'           => esc_html__('Twitter Widget Config', 'pointfindercoreelements'),
                'admin_bar'            => false,
                'allow_sub_menu'       => false,
                'admin_bar_priority'   => 50,
                'global_variable'      => '',
                'dev_mode'             => false,
                'update_notice'        => false,
                'menu_icon'            => 'dashicons-twitter',
                'page_slug'            => '_pftwitteroptions',
                'save_defaults'        => false,
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
    global $pftwgeneratorconfig;
    $pftwgeneratorconfig = new Redux_Framework_PF_TWGenerator_Config();

}
