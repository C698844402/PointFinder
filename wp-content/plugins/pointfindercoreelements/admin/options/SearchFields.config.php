<?php
/**********************************************************************************************************************************
*
* Search Fields Config
*
* Author: Webbu
*
***********************************************************************************************************************************/

if (class_exists("Redux_Framework_PFS_Fields_Config")) {
    return;
}


class Redux_Framework_PFS_Fields_Config{
    use PointFinderOptionFunctions;
    use PointFinderCustomSearchTrait;
    use PointFinderCommonFunctions;

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

	function compiler_action($options, $css) {
        $redux = ReduxFrameworkInstances::get_instance($this->args['opt_name']);

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


    public function setSections() {
		$setup1_slides = $this->PFSAIssetControl('setup1s_slides','','');
		$pfstart = $this->PFCheckStatusofVar('setup1s_slides');

		if(!$pfstart){

			$this->sections[] = array(
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
			);

		}else{
			$this->sections[] = array(
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
			);



			foreach ($setup1_slides as &$value) {

				$this->sections[] = $this->SDF($value['title'],$value['url'],$value['select']);

			}

		}


    }



    public function setArguments() {


        $this->args = array(
            'opt_name'             => 'pfsearchfields_options',
            'display_name'         => esc_html__('Point Finder Search Fields','pointfindercoreelements'),
            'menu_type'            => 'submenu',
            'page_parent'          => 'pointfinder_tools',
            'menu_title'           => esc_html__('Search Fields Config','pointfindercoreelements'),
            'page_title'           => esc_html__('Point Finder Search Fields', 'pointfindercoreelements'),
            'admin_bar'            => false,
            'allow_sub_menu'       => false,
            'admin_bar_priority'   => 50,
            'global_variable'      => '',
            'dev_mode'             => false,
            'update_notice'        => false,
            'menu_icon'            => 'dashicons-search',
            'page_slug'            => '_pfsifoptions',
            'save_defaults'        => false,
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

new Redux_Framework_PFS_Fields_Config();

