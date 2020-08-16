<?php
namespace PointFinderElementorSYS\Widgets;


use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use PointFinderElementorSYS\Helper;
use PointFinderOptionFunctions;
use PointFinderAGNTFunctions;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) exit;

class PointFinder_AgentList extends Widget_Base {

	use PointFinderOptionFunctions;
	use PointFinderAGNTFunctions;

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

    }

	public function show_in_panel() { return true; }

	public function get_keywords() { return [ 'pointfinder', 'agents']; }

	public function get_name() { return 'pointfinderagentlist'; }

	public function get_title() { return esc_html__( 'PF Agent List', 'pointfindercoreelements' ); }

	public function get_icon() { return 'eicon-post-list'; }

	public function get_categories() { return [ 'pointfinder_elements' ]; }


	public function get_script_depends() {
	    return [];
	}

	public function get_style_depends() {
      return [];
    }

	protected function _register_controls() {


		$this->start_controls_section(
			'testimonials_general',
			[
				'label' => esc_html__( 'General', 'pointfindercoreelements' ),
				'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);
			
			
			$this->add_control(
				'pagelimit',
				[
					'label' => esc_html__( 'Agents Per Page', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 50,
					'step' => 1,
					'default' => 6
				]
			);
			$this->add_control(
				'columns',
				[
					'label' => esc_html__( 'Columns', 'pointfindercoreelements' ),
					"description" => esc_html__("Please use 3 columns only on full width page.", "pointfindercoreelements"),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 2,
					'max' => 3,
					'step' => 1,
					'default' => 2
				]
			);
			$this->add_control(
				'orderby',
				[
					'label' => esc_html__( 'Order by', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'title',
					'options' => [
						'date' => esc_html__("Date", "pointfindercoreelements"),
						'ID' => esc_html__("ID", "pointfindercoreelements"),
						'title' => esc_html__("Title", "pointfindercoreelements"),
						'rand' => esc_html__("Random", "pointfindercoreelements"),
						'menu_order' => esc_html__("Menu order", "pointfindercoreelements")
					],
				]
			);

			$this->add_control(
				'order',
				[
					'label' => esc_html__( 'Order', 'pointfindercoreelements' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'ASC',
					'options' => [
						'ASC'  => esc_html__("Ascending", "pointfindercoreelements"),
						'DESC' => esc_html__("Descending", "pointfindercoreelements")
					],
				]
			);
			
		$this->end_controls_section();

	}

	

	protected function render() {

		$settings = $this->get_settings_for_display();

		extract(
			array(
				'pagelimit' => isset($settings['pagelimit'])?$settings['pagelimit']:6,
				'columns' => isset($settings['columns'])?$settings['columns']:2,
				'orderby' => isset($settings['orderby'])?$settings['orderby']:'title',
				'order' => isset($settings['order'])?$settings['order']:'ASC'
			)
		);

		if ( is_front_page() ) {
	        $pfg_paged = (esc_sql(get_query_var('page'))) ? esc_sql(get_query_var('page')) : 1;   
	    } else {
	        $pfg_paged = (esc_sql(get_query_var('paged'))) ? esc_sql(get_query_var('paged')) : 1; 
	    }

		$setup3_pointposttype_pt8 = $this->PFSAIssetControl('setup3_pointposttype_pt8','','agents');

		$args = array(
			'post_type' => $setup3_pointposttype_pt8,
			'posts_per_page' => $pagelimit
		);

		if (!empty($orderby)) {
			$args['orderby'] = $orderby;
		}

		if (!empty($order)) {
			$args['order'] = $order;
		}

		$args['paged'] = $pfg_paged;


		echo '<div class="pf-row pfagentlistrow">'; 

		global $wpdb;

		$loop = new WP_Query( $args );
		$im = 1;

		$setup3_pointposttype_pt3 = $this->PFSAIssetControl('setup3_pointposttype_pt3','','PF Items');
		$setup3_pointposttype_pt2 = $this->PFSAIssetControl('setup3_pointposttype_pt2','','PF Item');
		$setup3_pointposttype_pt1 = $this->PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');

		$setup42_itempagedetails_contact_photo = $this->PFSAIssetControl('setup42_itempagedetails_contact_photo','','1');
		$setup42_itempagedetails_contact_moreitems = $this->PFSAIssetControl('setup42_itempagedetails_contact_moreitems','','1');
		$setup42_itempagedetails_contact_phone = $this->PFSAIssetControl('setup42_itempagedetails_contact_phone','','1');
		$setup42_itempagedetails_contact_mobile = $this->PFSAIssetControl('setup42_itempagedetails_contact_mobile','','1');
		$setup42_itempagedetails_contact_email = $this->PFSAIssetControl('setup42_itempagedetails_contact_email','','1');
		$setup42_itempagedetails_contact_url = $this->PFSAIssetControl('setup42_itempagedetails_contact_url','','1');
		$setup42_itempagedetails_contact_form = $this->PFSAIssetControl('setup42_itempagedetails_contact_form','','1');

		while ( $loop->have_posts() ) : $loop->the_post();

			$author_id = get_the_id();

			$agent_featured_image =  wp_get_attachment_image_src( get_post_thumbnail_id( $author_id ), 'full' );

			if (empty($agent_featured_image)) {
				$user_photo = '<img src="'.PFCOREELEMENTSURLPUBLIC.'images/noimg.png"/>';
			}else{
				$agent_featured_image[0] = pointfinder_aq_resize($agent_featured_image[0],360,203,true,true,true);
				$user_photo = '<img src="'.$agent_featured_image[0].'" alt="" />';
			}
				if ($columns == 2) {
					$columns_output = 'col-lg-6 col-md-6 col-sm-6 col-xs-6';
					$columns_output2 = 'col-lg-4 col-md-4 col-sm-4 col-xs-4';
					$columns_output3 = 'col-lg-8 col-md-8 col-sm-8 col-xs-8';
				}else{
					$columns_output = 'col-lg-4 col-md-4 col-sm-6 col-xs-6';
					$columns_output2 = 'col-lg-4 col-md-4 col-sm-4 col-xs-4';
					$columns_output3 = 'col-lg-8 col-md-8 col-sm-8 col-xs-8';
				}

				$user_description = get_the_title($author_id);
				$user_phone = esc_attr(get_post_meta( $author_id, 'webbupointfinder_agent_tel', true ));
				$user_mobile = esc_attr(get_post_meta( $author_id, 'webbupointfinder_agent_mobile', true ));

				$user_socials = array();
				$user_email = sanitize_email(get_post_meta( $author_id, 'webbupointfinder_agent_email', true ));
				
				if($setup42_itempagedetails_contact_photo == 0){$user_photo = '';}
				$tabinside = '<div class="'.$columns_output.' col-xs-12 agentlistmaincol" >';
					$tabinside .= '<section role="itempagesidebarinfo" class="pf-itempage-sidebarinfo pfpos2 pf-itempage-elements pf-agentlist-pageitem">';
						
						$permalinkauth = get_permalink($author_id);
						
						$tabinside .= '<a href="'.esc_url($permalinkauth).'" class="pf-itempage-sidebarinfo-alist">';
							$tabinside .= '<div class="pfagentphotoname"><span>'.get_the_title().'</span></div>';
							if($setup42_itempagedetails_contact_moreitems == 1){
								$agentitemcount = $this->pointfinder_agentitemcount_calc($author_id, $setup3_pointposttype_pt1,'count');

								$agentitemcount = (isset($agentitemcount['count']))?$agentitemcount['count']:0;

								if ($agentitemcount > 0) {
									if ($agentitemcount > 1) {
										$agentitemcount_keyword = wp_sprintf( esc_html__('%d listings','pointfindercoreelements'),$agentitemcount );
									}elseif ($agentitemcount == 1) {
										$agentitemcount_keyword = esc_html__('1 listing','pointfindercoreelements');
									}
								}else{
									$agentitemcount_keyword = esc_html__('0 listing','pointfindercoreelements');
								}
								$tabinside .= '<div class="pfagentphotodesc"><i class="fas fa-map-marker-alt"></i> '.$agentitemcount_keyword.'</div>';
							}
							$tabinside .= '<div class="pf-itempage-sidebarinfo-photo">'.$user_photo.'</div>';
					$tabinside .= '</a></section>';
				$tabinside .= '</div>';
			echo $tabinside;

		endwhile;
		echo '</div>';
		echo '<div class="pfajax_paginate pf-agentlistpaginate" >';
		$big = 999999999;
		echo paginate_links(array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '?page=%#%',
			'current' => max(1, $pfg_paged),
			'total' => $loop->max_num_pages,
			'type' => 'list',
		));
		echo '</div>';
		
		// Reset Query
		wp_reset_postdata();
	}


}
