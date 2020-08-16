<?php
if (!class_exists('pointfinder_walker_nav_menu')) {
	class pointfinder_walker_nav_menu extends Walker_Nav_Menu {

	  	private $megamenu_status = "";
	  	private $megamenu_column = "";
	  	private $megamenu_hide_menu = "";
	  	private $megamenu_icon = "";
	  	private $typeofprocess = "";

	  	public function __construct($type = "")
	    {
	        $this->typeofprocess = $type;
	    }

		function start_lvl( &$output, $depth = 0, $args = array() ) {

			if ($this->megamenu_status == 1) {
				$megamenu_css_text = ' pfnav-megasubmenu pfnav-megasubmenu-col'.$this->megamenu_column;
			}else{
				$megamenu_css_text = '';
			}

		    $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' );
		    $display_depth = ( $depth + 1);
		    $classes = array(
		        'sub-menu'.$megamenu_css_text,
		        ( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
		        ( $display_depth ==1 ? 'pfnavsub-menu' : '' ),
		        ( $display_depth >=2 ? 'pfnavsub-menu' : '' ),
		        ( $display_depth >=2 && $this->megamenu_hide_menu == 1 ? 'pf-megamenu-unhide' : '' ),
		        'menu-depth-' . $display_depth
		        );
		    $class_names = implode( ' ', $classes );

		    $output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
		}


		function start_el(  &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

			$this->megamenu_status = $item->megamenu;
			$this->megamenu_hide_menu = $item->megamenu_hide_menu;
			$this->megamenu_column = $item->columnvalue;
			$this->megamenu_icon = $item->icon;

		    $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' );

		    $depth_classes = array(
		        ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
		        ( $depth >=2 ? 'sub-sub-menu-item' : '' ),
		        ( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
		        'menu-item-depth-' . $depth
		    );

		    $depth_class_names = esc_attr( implode( ' ', $depth_classes ) );

		    $classes = empty( $item->classes ) ? array() : (array) $item->classes;

		   	if (in_array('menu-item-has-children', $classes)) {
		   		if($this->megamenu_status == 1){$classes[] = 'pf-megamenu-main';}
		   	}

		    $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );


		    if (empty($this->typeofprocess)) {
		    	$output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';
		    }else{
		    	$output .= $indent . '<li class="' . $depth_class_names . ' ' . $class_names . '">';
		    }
		    

		    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		   	if ($this->megamenu_hide_menu != 1) {
		    	$attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';
			}else{
				$attributes .= ' class="menu-link pf-megamenu-hidedesktop ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';
			}

		  	$args_before = (isset($args->before))? $args->before: '';
		  	$args_link_before = (isset($args->link_before))? $args->link_before: '';
		  	$args_link_after = (isset($args->link_after))? $args->link_after: '';
		  	$args_after = (isset($args->after))? $args->after: '';


		  	if (!empty($this->typeofprocess)) {
	  		 	$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
			        $args_link_before,
			        $attributes,
			        $args_link_before,
			        (!empty($this->megamenu_iconm))?'<i class="'.$this->megamenu_iconm.'"></i> '.apply_filters( 'the_title', $item->title, $item->ID ):apply_filters( 'the_title', $item->title, $item->ID ),
			        $args_link_after,
			        $args_after
			    );
	  		}else{
	  			$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
			        $args_link_before,
			        $attributes,
			        $args_link_before,
			        (!empty($this->megamenu_icon))?'<i class="'.$this->megamenu_icon.'"></i> '.apply_filters( 'the_title', $item->title, $item->ID ):apply_filters( 'the_title', $item->title, $item->ID ),
			        $args_link_after,
			        $args_after
			    );
	  		}



		    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}
}
