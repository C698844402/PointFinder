<?php
/**********************************************************************************************************************************
*
* Custom Detail Fields Config
* 
* Author: Webbu
*
***********************************************************************************************************************************/

if (class_exists("Redux_Framework_PF_Fields_Config")) {
	return;
}

class Redux_Framework_PF_Fields_Config{
    use PointFinderOptionFunctions;
    use PointFinderCustomFieldsTrait;
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
        if (!isset($this->args['opt_name'])) {return;}
        $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
    }
	

 

    public function setSections() {
		
		$setup1_slides = $this->PFSAIssetControl('setup1_slides','','');
		
		$pfstart = $this->PFCheckStatusofVar('setup1_slides');
		
		if(!$pfstart){
			// ACTUAL DECLARATION OF SECTIONs
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
					'desc' => esc_html__('Please first create fields from <strong>PF Options > System Setup > Custom Detail Fields</strong> then you can see field detail setting on this control panel. If you install theme first time, please check installation steps from help documentations.', 'pointfindercoreelements')
					),
				
				)
			);
			
		}else{
			$this->sections[] = array(
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
			);
			
			
			foreach ($setup1_slides as &$value) {
				
				if($value['select'] != 10 && $value['select'] != 16){
					$this->sections[] = $this->CDF($value['title'],$value['url'],$value['select']);
				}
				
			}
			
		}
		
    }

    

    public function setArguments() {

        $this->args = array(

            'opt_name'             => 'pfcustomfields_options',
            'display_name'         => esc_html__('Point Finder Custom Fields','pointfindercoreelements'),
            'menu_type'            => 'submenu',
            'page_parent'          => 'pointfinder_tools',
            'menu_title'           => esc_html__('Custom Fields Config','pointfindercoreelements'),
            'page_title'           => esc_html__('Point Finder Custom Fields', 'pointfindercoreelements'),
            'admin_bar'            => false,
            'allow_sub_menu'       => false,
            'admin_bar_priority'   => 50,
            'global_variable'      => '',
            'dev_mode'             => false,
            'update_notice'        => false,
            'menu_icon'            => 'dashicons-welcome-widgets-menus',
            'page_slug'            => '_pfcifoptions',
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


        if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
            if (!empty($this->args['global_variable'])) {
                $v = $this->args['global_variable'];
            } else {
                $v = str_replace("-", "_", $this->args['opt_name']);
            }
        } 

    }

}
new Redux_Framework_PF_Fields_Config();