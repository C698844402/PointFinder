<?php
/**********************************************************************************************************************************
*
* Point Finder Sidebar Generator
* 
* Author: Webbu
*
***********************************************************************************************************************************/

if (!class_exists("Redux_Framework_PF_SBGenerator_Config")) {
	

    class Redux_Framework_PF_SBGenerator_Config{

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
            *Start : SIDEBAR GENERATOR STARTED
            **/
                $this->sections[] = array(
                    'id' => 'setup25_sidebargenerator',
                    'title' => esc_html__('Sidebar Generator', 'pointfindercoreelements'),
                    'icon' => 'el-icon-view-mode',
                    'fields' => array(
                        array(
                            'id'=>'setup25_sidebargenerator_sidebars',
                            'type' => 'extension_sidebar_slides',
                            'title' => esc_html__('Sidebar Name', 'pointfindercoreelements'),
                            'subtitle' => esc_html__('Please add sidebar name per line.', 'pointfindercoreelements'),
                            'add_text' => esc_html__('Add More', 'pointfindercoreelements'),
                            'show_empty' => false
                        )
                    )
                );
            /**
            *End : SIDEBAR GENERATOR STARTED
            **/
			
        }

        

        public function setArguments() {


            $this->args = array(

                'opt_name'             => 'pfsidebargenerator_options',
                'display_name'         => esc_html__('Point Finder Sidebar Generator','pointfindercoreelements'),
                'menu_type'            => 'submenu',
                'page_parent'          => 'pointfinder_tools',
                'menu_title'           => esc_html__('Sidebar Generator','pointfindercoreelements'),
                'page_title'           => esc_html__('Sidebar Generator', 'pointfindercoreelements'),
                'admin_bar'            => false,
                'allow_sub_menu'       => false,
                'admin_bar_priority'   => 50,
                'global_variable'      => '',
                'dev_mode'             => false,
                'update_notice'        => false,
                'menu_icon'            => 'dashicons-analytics',
                'page_slug'            => '_pfsidebaroptions',
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
    global $pointfinder_main_options_sb;
    $pointfinder_main_options_sb = new Redux_Framework_PF_SBGenerator_Config();
	
}