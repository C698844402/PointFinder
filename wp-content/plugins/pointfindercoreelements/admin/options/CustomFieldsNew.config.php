<?php
/**********************************************************************************************************************************
*
* Point Finder Custom Fields
* 
* Author: Webbu
*
***********************************************************************************************************************************/

if ( ! class_exists( 'Redux' ) ) {
    return;
}

class Redux_Framework_PF_Fields_Config{
    use PointFinderOptionFunctions;
    use PointFinderCustomFieldsTrait;
    use PointFinderCommonFunctions;

    public function __construct(){
        $this->initSettings();
    }

    public function initSettings(){
        $opt_name = "pfcustomfields_options";

        $args = array(
            'opt_name'             => $opt_name,
            'display_name'         => esc_html__('Point Finder Custom Fields','pointfindercoreelements'),
            'menu_type'            => 'submenu',
            'page_parent'          => 'pointfinder_tools',
            'allow_sub_menu'       => false,
            'menu_title'           => esc_html__('Custom Fields Config','pointfindercoreelements'),
            'page_title'           => esc_html__('Point Finder Custom Fields', 'pointfindercoreelements'),
            'admin_bar'            => false,
            'global_variable'      => '',
            'admin_bar_priority'   => 50,
            'dev_mode'             => false,
            'update_notice'        => false,
            'customizer'           => false,
            'page_priority'        => 290,
            'page_parent'          => 'pointfinder_tools',
            'page_permissions'     => 'manage_options',
            'menu_icon'            => 'dashicons-welcome-widgets-menus',
            'page_slug'            => '_pfcifoptions',
            'save_defaults'        => false,
            'default_show'         => false,
            'default_mark'         => '',
            'show_import_export'   => true,
            'transient_time'       => 60 * MINUTE_IN_SECONDS,
            'output'               => false,
            'output_tag'           => false,
            'database'             => '',
            'use_cdn'              => false,
            'hide_reset'           => true,
            'system_info'          => false,
        );

        Redux::setArgs( $opt_name, $args );

        $setup1_slides = $this->PFSAIssetControl('setup1_slides','','');
        $pfstart = $this->PFCheckStatusofVar('setup1_slides');

        if(!$pfstart){
            Redux::setSection( $opt_name, array(
                'id' => 'setup1',
                'title' => 'Information',
                'icon' => 'el-icon-info-sign',
                'fields' => array (
                    array(
                        'id' => 'setup1_help',
                        'id' => 'notice_critical',
                        'type' => 'info',
                        'notice' => true,
                        'style' => 'critical',
                        'desc' => esc_html__('Please first create fields from <strong>PF Options > System Setup > Custom Detail Fields</strong> then you can see field detail setting on this control panel. If you install theme first time, please check installation steps from help documentations.', 'pointfindercoreelements')
                        ),                    
                    )
                ) 
            );
        }else{
            Redux::setSection( $opt_name, array(
                'id' => 'setup1',
                'title' => 'Custom Fields',
                'icon' => 'el-icon-wrench-alt',
                'fields' => array (
                    array(
                        'id' => 'setup1_help',
                        'id' => 'notice_critical',
                        'type' => 'info',
                        'notice' => true,
                        'style' => 'info',
                        'desc'  => sprintf(esc_html__('Please check help documentation for information about this panel. Section name %s','pointfindercoreelements'),'<strong>'.esc_html__('PF Custom Fields','pointfindercoreelements').'</strong>')
                        ),
                    ) 
                ) 
            );

            foreach ($setup1_slides as $value) {
                
                if($value['select'] != 10 && $value['select'] != 16){
                    Redux::setSection( $opt_name, $this->CDF($value['title'],$value['url'],$value['select']));
                }  
            }
        }
    }
}
new Redux_Framework_PF_Fields_Config();