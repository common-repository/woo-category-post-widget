<?php
/*
Plugin Name: Woocommerce Category Post Widget
Plugin URI: http://wordpress.org/plugins/woo-category-post-widget
Description: Display post type and his category base post in your widget
Version: 1.0.2
Author: Nayan Virani
Author URI: https://profiles.wordpress.org/nayanvirani/
License: GPLv2 or later
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

define('WOOCPW_URL',plugin_dir_url(plugin_basename(__FILE__)));
define('WOOCPW_DIR', plugin_dir_path(__FILE__));
define('WOOCPW_CLASSES_DIR', plugin_dir_path(__FILE__).'/classes/');
define('WOOCPW_ASSETS_URL', plugin_dir_url(plugin_basename(__FILE__)).'/assets/');
define('WOOCPW_NAME','woocpw');



/**
 * The code that runs during plugin activation.
 */
function activate_woocpw(){
	require_once WOOCPW_CLASSES_DIR . 'class-woocpw-activator.php';
	WOOCPW_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_woocpw' );

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_woocpw(){
	require_once WOOCPW_CLASSES_DIR . 'class-woocpw-deactivator.php';
	WOOCPW_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_woocpw' );


/**
 * The core plugin class that is used to define 
 * admin-specific hooks, and public-specific site hooks.
 */
require_once WOOCPW_CLASSES_DIR . 'class-woocpw.php';

function run_woocpw() {
	
	$plugin = new woocpw();
	$plugin->run();

}
run_woocpw();
