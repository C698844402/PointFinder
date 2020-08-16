<?php

/**
 * Plugin Name:       PointFinder Core Elements
 * Plugin URI:        https://themeforest.net/user/webbu/portfolio
 * Description:       PointFinder theme core elements plugin.
 * Version:           1.1.9
 * Author:            Webbu
 * Author URI:        https://themeforest.net/user/webbu
 * License:           Themeforest Split License
 * License URI:       https://themeforest.net/licenses/terms/regular
 * Text Domain:       pointfindercoreelements
 * Domain Path:       /languages
 */


if ( ! defined( 'WPINC' ) ) {
	die;
}



$theme = wp_get_theme();

if ($theme->get( 'TextDomain' ) != 'pointfinder') {
	add_action( 'admin_notices', 'pointfindertheme_notice');
}

if ($theme->get('Template') == 'pointfinder') {
	remove_action( 'admin_notices', 'pointfindertheme_notice');
}

if (!class_exists("Redux")) {
	add_action( 'admin_notices', 'pointfindertheme_notice2');
}

function pointfindertheme_notice() {
	echo sprintf( '<div class="updated"><p><strong>PointFinder Core Plugin</strong> %s <strong>PointFinder Theme</strong> %s</p></div>', esc_html__( 'requires', 'templatera' ), esc_html__( 'to be installed and activated on your site.', 'templatera' ) );
}

function pointfindertheme_notice2() {
	echo sprintf( '<div class="updated"><p><strong>PointFinder Core Plugin</strong> %s <strong>Redux Framework Plugin</strong> %s</p></div>', esc_html__( 'requires', 'templatera' ), esc_html__( 'to be installed and activated on your site. Please activate Redux Framework plugin or deactivate and activate again to solve this issue.', 'templatera' ) );
}

function pointfindercore_this_plugin_last() {
	$wp_path_to_this_file = preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR."/$2", __FILE__);
	$this_plugin = plugin_basename(trim($wp_path_to_this_file));
	$active_plugins = get_option('active_plugins');
	$this_plugin_key = array_search($this_plugin, $active_plugins);
        array_splice($active_plugins, $this_plugin_key, 1);
        array_push($active_plugins, $this_plugin);
        update_option('active_plugins', $active_plugins);
}
add_action("activated_plugin", "pointfindercore_this_plugin_last");

	
define( 'PFCOREPLUGIN_NAME_VERSION', '1.1.8' );
define( 'PFCOREELEMENTSDIR', plugin_dir_path( __FILE__ ) );
define( 'PFCOREELEMENTSURL', plugin_dir_url( __FILE__ ) );
define( 'PFCOREELEMENTSURLADMIN', plugin_dir_url( __FILE__ ).'admin/' );
define( 'PFCOREELEMENTSURLPUBLIC', plugin_dir_url( __FILE__ ).'public/' );
define( 'PFCOREELEMENTSURLINC', plugin_dir_url( __FILE__ ).'includes/' );
define( 'PFCOREELEMENTSURLEXT', plugin_dir_url( __FILE__ ).'admin/options/extensions/' );

function activate_pointfindercoreelements() {
	require_once PFCOREELEMENTSDIR . 'includes/class-pointfindercoreelements-activator.php';
	Pointfindercoreelements_Activator::activate();
}

function deactivate_pointfindercoreelements() {
	require_once PFCOREELEMENTSDIR . 'includes/class-pointfindercoreelements-deactivator.php';
	Pointfindercoreelements_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pointfindercoreelements' );
register_deactivation_hook( __FILE__, 'deactivate_pointfindercoreelements' );

require PFCOREELEMENTSDIR . 'includes/traits/options.php';
require PFCOREELEMENTSDIR . 'includes/class-pointfindercoreelements.php';
function run_pointfindercoreelements() {

	$plugin = new Pointfindercoreelements();
	$plugin->run();

}
run_pointfindercoreelements();