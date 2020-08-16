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

class Redux_Framework_PFS_Fields_Config{
    use PointFinderOptionFunctions;
    use PointFinderCustomSearchTrait;
    use PointFinderCommonFunctions;

    public $opt_name;

    public function __construct(){
        $this->opt_name = "pfsearchfields_options";
        $this->initSettings();
        add_filter('redux/options/'.$this->opt_name.'/compiler', array( $this, 'compiler_action' ), 10, 2);
    }

    public function initSettings(){
        $args = array(
            'opt_name'             => $this->opt_name,
            'display_name'         => esc_html__('Point Finder Search Fields','pointfindercoreelements'),
            'menu_type'            => 'submenu',
            'page_parent'          => 'pointfinder_tools',
            'allow_sub_menu'       => false,
            'menu_title'           => esc_html__('Search Fields Config','pointfindercoreelements'),
            'page_title'           => esc_html__('Point Finder Search Fields', 'pointfindercoreelements'),
            'admin_bar'            => false,
            'global_variable'      => '',
            'admin_bar_priority'   => 50,
            'dev_mode'             => false,
            'update_notice'        => false,
            'customizer'           => false,
            'page_priority'        => 300,
            'page_parent'          => 'pointfinder_tools',
            'page_permissions'     => 'manage_options',
            'menu_icon'            => 'dashicons-search',
            'page_slug'            => '_pfsifoptions',
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

        Redux::setArgs( $this->opt_name, $args );

        $setup1s_slides = $this->PFSAIssetControl('setup1s_slides','','');
        $pfstart = $this->PFCheckStatusofVar('setup1s_slides');

        if(!$pfstart){
            Redux::setSection( $this->opt_name, array(
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
                        'desc' => esc_html__('Please first create search fields from <strong>PF Options > System Setup > Search Fields</strong> then you can see field detail setting on this control panel. If you install theme first time, please check installation steps from help documentations.', 'pointfindercoreelements')
                        ),

                    )
                )
            );
        }else{
            Redux::setSection( $this->opt_name, array(
                'id' => 'setup1',
                'title' => 'Search Fields',
                'icon' => 'el-icon-search-alt',
                'fields' => array (
                    array(
                        'id' => 'setup1_help',
                        'id' => 'notice_critical',
                        'type' => 'info',
                        'notice' => true,
                        'style' => 'info',
                        'desc'  => sprintf(esc_html__('Please check help documentation for information about this panel. Section name %s','pointfindercoreelements'),'<strong>'.esc_html__('PF Search Fields','pointfindercoreelements').'</strong>')
                        ),

                    )
                ) 
            );

            foreach ($setup1s_slides as $value) {
                
                if($value['select'] != 10 && $value['select'] != 16){
                    Redux::setSection( $this->opt_name, $this->SDF($value['title'],$value['url'],$value['select']));
                }  
            }
        }
    }


    public function compiler_action($options, $css) {
        $redux = ReduxFrameworkInstances::get_instance($this->opt_name);

        $uploads = wp_upload_dir();
        $upload_dir = trailingslashit($uploads['basedir']);
        $upload_dir = $upload_dir . '/pfstyles';

        if ( ! is_dir( $upload_dir ) ) {
          $redux->filesystem->execute( "mkdir", $upload_dir );
        }

        $filename = trailingslashit($uploads['basedir']) . '/pfstyles/pf-style-search' . '.css';

        $redux->filesystem->execute( 
            'put_contents', 
            $filename, 
            array( 
                'content' => $css
            ) 
        );
    }
}
new Redux_Framework_PFS_Fields_Config();