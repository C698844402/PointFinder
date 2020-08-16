<?php

if (trait_exists('PointFinderPPPPackages')) {
  return;
}

/**
 * PPP packages
 */
trait PointFinderPPPPackages
{
    /**
     * PPP Post Type
     */
    public function pointfinder_ppp_pack_function(){
    	$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');
    	if($setup4_membersettings_paymentsystem != 1){
    		return;
    	}
        register_post_type('pflistingpacks',
            array(
            'labels' => array(
                'name' => esc_html__('Listing Packs','pointfindercoreelements'),
                'singular_name' => esc_html__('Package','pointfindercoreelements'),
                'add_new' => sprintf(esc_html__( 'Add New %s', 'pointfindercoreelements' ),esc_html__('Listing Package','pointfindercoreelements')),
                'add_new_item' => sprintf(esc_html__( 'Add New %s', 'pointfindercoreelements' ),esc_html__('Listing Package','pointfindercoreelements')),
                'edit' => esc_html__('Edit', 'pointfindercoreelements'),
                'edit_item' => sprintf(esc_html__( 'Edit %s', 'pointfindercoreelements' ),esc_html__('Listing Package','pointfindercoreelements')),
                'new_item' => sprintf(esc_html__( 'New %s', 'pointfindercoreelements' ),esc_html__('Listing Package','pointfindercoreelements')),
                'view' => sprintf(esc_html__( 'View %s', 'pointfindercoreelements' ),esc_html__('Listing Package','pointfindercoreelements')),
                'view_item' => sprintf(esc_html__( 'View %s', 'pointfindercoreelements' ),esc_html__('Listing Package','pointfindercoreelements')),
                'search_items' =>  sprintf(esc_html__( 'Search %s', 'pointfindercoreelements' ),esc_html__('Listing Packages','pointfindercoreelements')),
                'not_found' => sprintf(esc_html__( 'No %s found', 'pointfindercoreelements' ),esc_html__('Listing Package','pointfindercoreelements')),
                'not_found_in_trash' => sprintf(esc_html__( 'No %s found in Trash', 'pointfindercoreelements' ),esc_html__('Listing Package','pointfindercoreelements')),
            ),
            'public' => true,
            //'menu_position' => 205,
            'menu_icon' => 'dashicons-admin-generic',
            'hierarchical' => true,
            'show_tagcloud' => false,
            'show_in_nav_menus' => false,
            'has_archive' => true,
            'show_in_menu' => 'pointfinder_tools',
            'supports' => array(
                'title'
            ),
            'can_export' => true,
            'taxonomies' => array()

        ));
    }

    public function pointfinder_listingpacks_manage_columns( $column, $post_id ) {
        global $post;

        switch( $column ) {

            case 'price' :
                echo get_post_meta( $post_id, 'webbupointfinder_lp_price', true );
                break;

            case 'status' :

                $statusofitem = get_post_meta( $post_id, 'webbupointfinder_lp_showhide', true );

                if ($statusofitem == 2) {
                    echo esc_html__('Hidden','pointfindercoreelements' );
                }else{
                    echo esc_html__('Visible','pointfindercoreelements' );
                }
                break;

            case 'cycle':
                $billingp = get_post_meta( $post_id, 'webbupointfinder_lp_billing_period', true );
                $cycleofitem = 'daily';

                if ($billingp == 0) {
                    echo esc_html__('Unlimited','pointfindercoreelements' );
                }else{
                    echo intval($billingp).esc_html__('Day(s)','pointfindercoreelements' );
                    echo '</small>';
                }

                break;

        }
    }

    public function pointfinder_listingpacks_edit_columns( $columns ) {
        $newcolumns = array(
            'cycle' => esc_html__( 'Cycle','pointfindercoreelements'),
            'price' => esc_html__( 'Price','pointfindercoreelements'),
            'status' => esc_html__( 'Status','pointfindercoreelements'),
        );

        $result_array = array_merge($columns, $newcolumns);
        $datefield = $result_array['date'];
        unset($result_array['date']);
        $result_array['date'] = $datefield;
        return $result_array;
    }
}