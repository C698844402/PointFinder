<?php 

if (trait_exists('PointFinderMegaMenu')) {
  return;
}

/**
 * Megamenu Functions
 */
trait PointFinderMegaMenu
{
    public function pointfinder_custom_nav_edit_walker($walker,$menu_id) {
	    return 'Pointfinder_Walker_Nav_Menu_Edit_Custom';
	}

	public function pointfinder_custom_nav_item($menu_item) {
	    $menu_item->columnvalue = get_post_meta( $menu_item->ID, '_menu_item_columnvalue', true );
	    $menu_item->megamenu = get_post_meta( $menu_item->ID, '_menu_item_megamenu', true );
	    $menu_item->megamenu_hide_menu = get_post_meta( $menu_item->ID, '_menu_item_megamenu_hide', true );
	    $menu_item->icon = get_post_meta( $menu_item->ID, '_menu_item_icon', true );
	    $menu_item->iconm = get_post_meta( $menu_item->ID, '_menu_item_iconm', true );
	    return $menu_item;
	}

	public function pointfinder_custom_nav_update($menu_id, $menu_item_db_id, $args ) {
	    if(empty($_REQUEST['menu-item-columnvalue'])){
	        update_post_meta( $menu_item_db_id, '_menu_item_columnvalue', '0' );
	    }else{
	        if ( is_array($_REQUEST['menu-item-columnvalue']) ) {
	            $custom_value = $_REQUEST['menu-item-columnvalue'][$menu_item_db_id];
	            update_post_meta( $menu_item_db_id, '_menu_item_columnvalue', $custom_value );
	        }
	    }

	    if(empty($_REQUEST['menu-item-megamenu'])){
	        update_post_meta( $menu_item_db_id, '_menu_item_megamenu', '0' );
	    }else{
	        if ( is_array($_REQUEST['menu-item-megamenu']) ) {
	            
	            if (isset($_REQUEST['menu-item-megamenu'][$menu_item_db_id])) {
	                $custom_value2 = $_REQUEST['menu-item-megamenu'][$menu_item_db_id];
	                update_post_meta( $menu_item_db_id, '_menu_item_megamenu', $custom_value2 );
	            }
	        }
	    }

	    if(empty($_REQUEST['menu-item-megamenu-hide'])){
	        update_post_meta( $menu_item_db_id, '_menu_item_megamenu_hide', '0' );
	    }else{
	        if ( is_array($_REQUEST['menu-item-megamenu-hide']) ) {
	            
	            if (isset($_REQUEST['menu-item-megamenu-hide'][$menu_item_db_id])) {
	                $custom_value2 = $_REQUEST['menu-item-megamenu-hide'][$menu_item_db_id];
	                update_post_meta( $menu_item_db_id, '_menu_item_megamenu_hide', $custom_value2 );
	            }
	        }
	    }


	    if(empty($_REQUEST['menu-item-icon'])){
	        update_post_meta( $menu_item_db_id, '_menu_item_icon', '' );
	    }else{
	        if ( is_array($_REQUEST['menu-item-icon']) ) {
	            
	            if (isset($_REQUEST['menu-item-icon'][$menu_item_db_id])) {
	                $custom_value2 = $_REQUEST['menu-item-icon'][$menu_item_db_id];
	                update_post_meta( $menu_item_db_id, '_menu_item_icon', $custom_value2 );
	            }
	        }
	    }


	    if(empty($_REQUEST['menu-item-iconm'])){
	        update_post_meta( $menu_item_db_id, '_menu_item_iconm', '' );
	    }else{
	        if ( is_array($_REQUEST['menu-item-iconm']) ) {
	            
	            if (isset($_REQUEST['menu-item-iconm'][$menu_item_db_id])) {
	                $custom_value2 = $_REQUEST['menu-item-iconm'][$menu_item_db_id];
	                update_post_meta( $menu_item_db_id, '_menu_item_iconm', $custom_value2 );
	            }
	        }
	    }
	}
}