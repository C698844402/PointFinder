<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @package	   TGM-Plugin-Activation
 * @subpackage Example
 * @version	   2.6.1
 * @author	   Thomas Griffin <thomas@thomasgriffinmedia.com>
 * @author	   Gary Jones <gamajo@gamajo.com>
 * @copyright  Copyright (c) 2012, Thomas Griffin
 * @license	   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
 */


require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'pointfinderh_register_required_plugins' );
function pointfinderh_register_required_plugins() {

	$plugins = array(
        array(
            'name'      => 'Redux Framework',
            'slug'      => 'redux-framework',
            'required'  => true
        ),
        array(
            'name'      => 'WPBakery Page Builder',
            'slug'      => 'js_composer',
            'required'  => true,
            'version'   => '6.1',
            'source'    => 'https://plugins.webbu.com/pointfinder/js_composer.zip',
        ),
        array(
            'name'      => 'PointFinder Core Elements',
            'slug'      => 'pointfindercoreelements',
            'required'  => true,
            'version'   => '1.1.9',
            'source'    => 'https://plugins.webbu.com/pointfinder/pointfindercoreelements.zip',
        ),
        array(
          'name'        => 'One Click Demo Import',
          'slug'        => 'one-click-demo-import',
          'required'    => false,
        ),
    	array(
            'name'		=> 'Templatera',
            'slug'		=> 'templatera',
            'required'	=> false,
            'version'	=> '2.0.3',
            'source'	=> 'https://plugins.webbu.com/pointfinder/templatera.zip',
        ),

        array(
            'name'        => 'Ultimate Addons for Visual Composer',
            'slug'        => 'Ultimate_VC_Addons',
            'required'    => false,
            'version'     => '3.19.4',
            'source'      => 'https://plugins.webbu.com/pointfinder/Ultimate_VC_Addons.zip',
        ),

        array(
            'name'        => 'Envato Market',
            'slug'        => 'envato-market',
            'required'    => false,
            'version'     => '2.0.3',
            'source'      => 'https://plugins.webbu.com/pointfinder/envato-market.zip',
        ),

        array(
            'name'        => 'Revolution Slider',
            'slug'        => 'revslider',
            'required'    => false,
            'version'     => '6.2.2',
            'source'      => 'https://plugins.webbu.com/pointfinder/revslider.zip',
        ),
        array(
          'name'        => 'WP Mail SMTP by WPForms',
          'slug'        => 'wp-mail-smtp',
          'required'    => false,
        ),

	);



	 $config = array(
        'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug'  => 'themes.php',            // Parent menu slug.
        'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
    );
    tgmpa( $plugins, $config );

}
