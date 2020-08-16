<?php

/*
*
* Visual Composer PointFinder Static Grid Shortcode
*
*/

if ( ! defined( 'ABSPATH' ) ) {
  die( '-1' );
}

class PointFinderAgentListShortcode extends WPBakeryShortCode {
	use PointFinderOptionFunctions;
	use PointFinderAGNTFunctions;

    function __construct() {
        add_action( 'vc_after_init', array( $this, 'pointfinder_single_pf_agentlist_module_mapping' ) );
        add_shortcode( 'pf_agentlist', array( $this, 'pointfinder_single_pf_agentlist_module_html' ) );
    }

    

    public function pointfinder_single_pf_agentlist_module_mapping() {

      if ( !defined( 'WPB_VC_VERSION' ) ) {
          return;
      }
      $setup3_pointposttype_pt6_status = $this->PFSAIssetControl('setup3_pointposttype_pt6_status','','1');

      /**
		*Start : Agent List ----------------------------------------------------------------------------------------------------
		**/
			if ($setup3_pointposttype_pt6_status == 1) {
				vc_map( array(
				"name" => esc_html__("PF Agent List", 'pointfindercoreelements'),
				"base" => "pf_agentlist",
				"icon" => "pf_agentlist",
				"category" => esc_html__("Point Finder", "pointfindercoreelements"),
				"description" => esc_html__('List of Agents', 'pointfindercoreelements'),
				"params" => array(
						array(
							"type" => "dropdown",
							"heading" => esc_html__("Items Per Page", "pointfindercoreelements"),
							"param_name" => "pagelimit",
							"value" => array(2=>2,4=>4,6=>6,8=>8,10=>10,12=>12,14=>14,16=>16,18=>18,20=>20,22=>22,24=>24,26=>26,28=>28,30=>30),
							"description" => esc_html__("How many agents would you like to display per page?", "pointfindercoreelements"),
						),
						array(
							"type" => "dropdown",
							"heading" => esc_html__("Columns", "pointfindercoreelements"),
							"param_name" => "columns",
							"value" => array(2=>2,3=>3),
							"description" => esc_html__("Please use 3 columns only on full width page.", "pointfindercoreelements"),
						),
						array(
							"type" => "dropdown",
							"heading" => esc_html__("Order by", "pointfindercoreelements"),
							"param_name" => "orderby",
							"value" => array(esc_html__("Title", "pointfindercoreelements")=>'title',esc_html__("Date", "pointfindercoreelements")=>'date', esc_html__("Menu Order", "pointfindercoreelements")=>'menu_order', esc_html__("Random", "pointfindercoreelements")=>'rand', esc_html__("ID", "pointfindercoreelements")=>'ID'),
							"description" => esc_html__("Please select an order by filter.", "pointfindercoreelements"),
							"edit_field_class" => 'vc_col-sm-6'

						),
						array(
							"type" => "dropdown",
							"heading" => esc_html__("Order", "pointfindercoreelements"),
							"param_name" => "order",
							"value" => array(esc_html__("ASC", "pointfindercoreelements")=>'ASC',esc_html__("DESC", "pointfindercoreelements")=>'DESC'),
							"description" => esc_html__("Please select an order filter.", "pointfindercoreelements"),
							"edit_field_class" => 'vc_col-sm-6 vc_column'

						),
					)
				) );
			}
		/**
		*End : Agent List ----------------------------------------------------------------------------------------------------
		**/

    }


    public function pointfinder_single_pf_agentlist_module_html( $atts ) {

      extract( shortcode_atts( array(
   		'pagelimit' => 10,
   		'columns' => 2,
   		'order' => 'ASC',
   		'orderby' => 'title'
   	), $atts ) );
  
	$output = '';

	ob_start();

		if ( is_front_page() ) {
	        $pfg_paged = (esc_sql(get_query_var('page'))) ? esc_sql(get_query_var('page')) : 1;   
	    } else {
	        $pfg_paged = (esc_sql(get_query_var('paged'))) ? esc_sql(get_query_var('paged')) : 1; 
	    }

		$setup3_pointposttype_pt8 = PFSAIssetControl('setup3_pointposttype_pt8','','agents');

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

		$setup3_pointposttype_pt3 = PFSAIssetControl('setup3_pointposttype_pt3','','PF Items');
		$setup3_pointposttype_pt2 = PFSAIssetControl('setup3_pointposttype_pt2','','PF Item');
		$setup3_pointposttype_pt1 = PFSAIssetControl('setup3_pointposttype_pt1','','pfitemfinder');

		$setup42_itempagedetails_contact_photo = PFSAIssetControl('setup42_itempagedetails_contact_photo','','1');
		$setup42_itempagedetails_contact_moreitems = PFSAIssetControl('setup42_itempagedetails_contact_moreitems','','1');
		$setup42_itempagedetails_contact_phone = PFSAIssetControl('setup42_itempagedetails_contact_phone','','1');
		$setup42_itempagedetails_contact_mobile = PFSAIssetControl('setup42_itempagedetails_contact_mobile','','1');
		$setup42_itempagedetails_contact_email = PFSAIssetControl('setup42_itempagedetails_contact_email','','1');
		$setup42_itempagedetails_contact_url = PFSAIssetControl('setup42_itempagedetails_contact_url','','1');
		$setup42_itempagedetails_contact_form = PFSAIssetControl('setup42_itempagedetails_contact_form','','1');

		while ( $loop->have_posts() ) : $loop->the_post();

			$author_id = get_the_id();

			$agent_featured_image =  wp_get_attachment_image_src( get_post_thumbnail_id( $author_id ), 'full' );

			if (empty($agent_featured_image)) {
				$user_photo = '<img src="'.get_template_directory_uri().'/images/placeholder/placeholder-16-9.jpg"/>';
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

	$output = ob_get_contents();

	ob_end_clean();

	return $output;

    }

}
new PointFinderAgentListShortcode();

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_pointfinder_single_pf_agentlist extends WPBakeryShortCode {
    }
}