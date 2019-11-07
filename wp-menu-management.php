<?php
/*
 * Plugin Name: WP Menu Management
 * Description: Import, Export and Delete your single or all WP menu in a single click.
 * Author: Artoon Solutions
 * Version: 1.0.0
 * Author URI: https://artoonsolutions.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wp-menu-management
 * Domain Path: /languages
 *
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin Folder Path.
if ( ! defined( 'WP_MENU_MNGT_PLUGIN_DIR' ) ) {
	define( 'WP_MENU_MNGT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// Plugin Folder URL.
if ( ! defined( 'WP_MENU_MNGT_PLUGIN_URL' ) ) {
	define( 'WP_MENU_MNGT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Define the class and the function.
require_once WP_MENU_MNGT_PLUGIN_DIR . '/includes/admin/class-admin-menu.php';

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'salcode_add_plugin_page_settings_link');
function salcode_add_plugin_page_settings_link( $links ) {
	$links[] = '<a href="' .
		admin_url( 'themes.php?page=wp-menu-mngt' ) .
		'">' . __('Settings') . '</a>';
	return $links;
}

function wp_menu_mngt_load_plugin_textdomain() {
    load_plugin_textdomain( 'wp-menu-management', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'wp_menu_mngt_load_plugin_textdomain' );