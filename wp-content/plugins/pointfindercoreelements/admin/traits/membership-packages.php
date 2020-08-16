<?php

if (trait_exists('PointFinderMembershipPackages')) {
  return;
}

/**
 * Membership packages
 */
trait PointFinderMembershipPackages
{
    /**
     * Membership Post Type
     */
    public function pointfinder_membership_pack_function(){
  		$setup4_membersettings_paymentsystem = $this->PFSAIssetControl('setup4_membersettings_paymentsystem','','1');

  		if($setup4_membersettings_paymentsystem != 2){
  			return;
  		}

        register_post_type('pfmembershippacks',
            array(
            'labels' => array(
                'name' => esc_html__('Membership Packs','pointfindercoreelements'),
                'singular_name' => esc_html__('Package','pointfindercoreelements'),
                'add_new' => sprintf(esc_html__( 'Add New %s', 'pointfindercoreelements' ),esc_html__('Membership Package','pointfindercoreelements')),
                'add_new_item' => sprintf(esc_html__( 'Add New %s', 'pointfindercoreelements' ),esc_html__('Membership Package','pointfindercoreelements')),
                'edit' => esc_html__('Edit', 'pointfindercoreelements'),
                'edit_item' => sprintf(esc_html__( 'Edit %s', 'pointfindercoreelements' ),esc_html__('Membership Package','pointfindercoreelements')),
                'new_item' => sprintf(esc_html__( 'New %s', 'pointfindercoreelements' ),esc_html__('Membership Package','pointfindercoreelements')),
                'view' => sprintf(esc_html__( 'View %s', 'pointfindercoreelements' ),esc_html__('Membership Package','pointfindercoreelements')),
                'view_item' => sprintf(esc_html__( 'View %s', 'pointfindercoreelements' ),esc_html__('Membership Package','pointfindercoreelements')),
                'search_items' =>  sprintf(esc_html__( 'Search %s', 'pointfindercoreelements' ),esc_html__('Membership Packages','pointfindercoreelements')),
                'not_found' => sprintf(esc_html__( 'No %s found', 'pointfindercoreelements' ),esc_html__('Membership Package','pointfindercoreelements')),
                'not_found_in_trash' => sprintf(esc_html__( 'No %s found in Trash', 'pointfindercoreelements' ),esc_html__('Membership Package','pointfindercoreelements')),
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

   	public function pointfinder_membershippacks_manage_columns( $column, $post_id ) {
        global $post;

        switch( $column ) {

            case 'price' :
                echo get_post_meta( $post_id, 'webbupointfinder_mp_price', true );
                break;

            case 'status' :

                $statusofitem = get_post_meta( $post_id, 'webbupointfinder_mp_showhide', true );

                if ($statusofitem == 2) {
                    echo esc_html__('Hidden','pointfindercoreelements' );
                }else{
                    echo esc_html__('Visible','pointfindercoreelements' );
                }
                break;

            case 'cycle':
                $billingp = get_post_meta( $post_id, 'webbupointfinder_mp_billing_period', true );
                $cycleofitem = get_post_meta( $post_id, 'webbupointfinder_mp_billing_time_unit', true );

                if ($billingp == 0) {
                    echo esc_html__('Unlimited','pointfindercoreelements' );
                }else{
                    echo esc_attr( $billingp );
                    echo ' <small>';
                     switch ($cycleofitem) {
                        case 'yearly':
                            echo esc_html__('Year(s)','pointfindercoreelements' );
                            break;

                        case 'monthly':
                            echo esc_html__('Month(s)','pointfindercoreelements' );
                            break;

                        case 'daily':
                            echo esc_html__('Day(s)','pointfindercoreelements' );
                            break;
                        default:
                            echo esc_html__('Day(s)','pointfindercoreelements' );
                            break;
                    }
                    echo '</small>';
                }

                break;
            case 'itemlimit' :
                $itemlimit = get_post_meta( $post_id, 'webbupointfinder_mp_itemnumber', true );
                if ($itemlimit == -1) {
                    echo esc_html__('Unlimited','pointfindercoreelements');
                }else{
                    echo esc_attr( $itemlimit );
                }
                break;
        }
    }

    public function pointfinder_membershippacks_edit_columns( $columns ) {
            $newcolumns = array(
                'cycle' => esc_html__( 'Cycle','pointfindercoreelements'),
                'itemlimit' => esc_html__( 'Items','pointfindercoreelements'),
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