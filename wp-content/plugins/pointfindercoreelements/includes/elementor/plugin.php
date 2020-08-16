<?php
namespace PointFinderElementorSYS;

class Plugin {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function widget_scripts() {
		
	}

	public function editor_styles(){
		wp_enqueue_style( 'pointfinder-elementor-editor', PFCOREELEMENTSURL . 'includes/elementor/assets/css/pf-editor.css', array(), '1.0');
	}

	public static function wemx_optget($field = '', $field2 = '', $default = ''){
        global $wemodulexoptions;

        if (empty($wemodulexoptions)) {
          $wemodulexoptions = get_option('wemodulexoptions');
        }

        if($field2 == ''){
          if (!isset($wemodulexoptions[''.$field.''])) {
            return $default;
          }
          if ($wemodulexoptions[''.$field.''] == "") {
            return $default;
          }
          return $wemodulexoptions[''.$field.''];
        }else{
          if (!isset($wemodulexoptions[''.$field.''][''.$field2.''])) {
            return $default;
          }
          if ($wemodulexoptions[''.$field.''][''.$field2.''] == "") {
            return $default;
          }
          return $wemodulexoptions[''.$field.''][''.$field2.''];
        };

    }

    public static function PF_current_language(){
		return apply_filters( 'wpml_current_language', NULL );
	}

    public static function PFSAIssetControl($field, $field2 = '', $default = '',$icl_exit = 0){
      global $pointfindertheme_option;

      if (empty($pointfindertheme_option)) {
        $pointfindertheme_option = get_option('pointfindertheme_options');
      }

      if($field2 == ''){
        if (!isset($pointfindertheme_option[''.$field.''])) {
          return $default;
        }
        if ($pointfindertheme_option[''.$field.''] == "") {
          return $default;
        }
        return $pointfindertheme_option[''.$field.''];
      }else{
        if (!isset($pointfindertheme_option[''.$field.''][''.$field2.''])) {
          return $default;
        }
        if ($pointfindertheme_option[''.$field.''][''.$field2.''] == "") {
          return $default;
        }
        return $pointfindertheme_option[''.$field.''][''.$field2.''];
      };

    }


	private function include_widgets_files() {
		require_once( PFCOREELEMENTSDIR . 'includes/elementor/widgets/directory-map.php' );
		require_once( PFCOREELEMENTSDIR . 'includes/elementor/widgets/contact-map.php' );
		require_once( PFCOREELEMENTSDIR . 'includes/elementor/widgets/contact-form.php' );
		require_once( PFCOREELEMENTSDIR . 'includes/elementor/widgets/text-seperator.php' );
		require_once( PFCOREELEMENTSDIR . 'includes/elementor/widgets/logo-carousel.php' );
		require_once( PFCOREELEMENTSDIR . 'includes/elementor/widgets/testimonials.php' );
		require_once( PFCOREELEMENTSDIR . 'includes/elementor/widgets/agentlist.php' );
	}

	public function register_widgets() {
		$this->include_widgets_files();

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\PointFinder_Directory_Map() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\PointFinder_Contact_Map() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\PointFinder_Contact_Form() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\PointFinder_Text_Separator() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\PointFinder_Logo_Carousel() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\PointFinder_Testimonials() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\PointFinder_AgentList() );

		//\Elementor\Plugin::instance()->widgets_manager->unregister_widget_type( "wp-widget-pf_featured_agents_w" );
		
	}

	


	public function __construct() {

		

		add_action( 'elementor/elements/categories_registered', [ $this, 'add_widget_categories' ],10,1);
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'editor_scripts' ], 999);
		add_action( 'elementor/editor/before_enqueue_styles', [ $this, 'editor_styles' ], 999);

		//add_action( 'elementor/frontend/after_register_styles', [ $this, 'widget_styles' ]);
		//add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ]);
		add_action( 'elementor/preview/enqueue_styles', [ $this, 'preview_styles' ]);
		add_action( 'elementor/preview/enqueue_scripts', [ $this, 'preview_scripts' ]);

		add_action( 'elementor/element/post/document_settings/after_section_end',  [ $this, 'page_settings_controls'],10, 2);
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ], 0);

		add_action( "elementor/widget/pointfinderlogocarousel/skins_init", [ $this, 'widget_scripts' ]);
	}

	public function add_widget_categories($elements_manager) {
		
		$elements_manager->add_category(
			'pointfinder_elements',
			[
				'title' => esc_html__( 'Point Finder Elements', 'pointfindercoreelements' ),
				'icon'  => 'eicon-font',
			],
			1
		);
	}


	public function editor_scripts() {
		wp_enqueue_script(
			'pointfinder-elements-editor',
			PFCOREELEMENTSURL . 'includes/elementor/assets/js/pointfinderelementor_editor.js',
			[
				'elementor-editor', // dependency.
			],
			'1.9.2',
			true // in_footer
		);

		wp_localize_script( 'pointfinder-elements-editor','modulexelmlocalize', array(
			'plselect' => esc_html__( "Please select", "pointfindercoreelements"),
			'nores' => esc_html__( "No results found", "pointfindercoreelements"),
			'searching' => esc_html__( "Searching...", "pointfindercoreelements"),
			'resload' => esc_html__( "The results could not be loaded.", "pointfindercoreelements"),
			'resturl' => get_rest_url(),
		));
	}

	public function preview_styles() {
	   // preview styles
	}

	public function preview_scripts() {
	   // preview styles
	}
	public function page_settings_controls( $element, $args ) {
	 	$element->start_controls_section(
			'mx_page_menu_settings',
			[
				'label' => esc_html__( 'Menu Settings', 'pointfindercoreelements' ),
				'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);
	 		$element->add_control(
				'fullmenu',
				[
					'label' => esc_html__( 'Full Width Menu', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'YES', 'pointfindercoreelements' ),
					'label_off' => esc_html__( 'NO', 'pointfindercoreelements' ),
					'return_value' => 'yes',
					'default' => 'yes'
				]
			);

		$element->end_controls_section();
	}
}

Plugin::instance();