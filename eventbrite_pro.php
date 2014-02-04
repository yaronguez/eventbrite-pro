<?php
/**
 * Eventbrite Pro
 *
 * Shortcode to display and cache a list and calendar of your upcoming Eventbrite events
 *
 * @package   Eventbrite_Pro
 * @author    Yaron Guez <yaron@trestian.com>
 * @license   GPL-2.0+
 * @link      http://github.com/yaronguez/eventbrite-pro
 * @copyright 2013 Yaron Guez
 *
 * @wordpress-plugin
 * Plugin Name:       Eventbrite Pro
 * Plugin URI:        http://github.com/yaronguez/eventbrite-pro
 * Description:       Shortcode to displays and cache a list and calendar of your upcoming Eventbrite events
 * Version:           0.1.0
 * Author:            Yaron Guez
 * Author URI:        http://trestian.com
 * Text Domain:       eventbrite_pro
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: yaronguez/eventbrite-pro
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('EVENTBRITE_PRO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-eventbrite_pro.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'Eventbrite_Pro', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Eventbrite_Pro', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Eventbrite_Pro', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-eventbrite_pro-admin.php' );
	add_action( 'plugins_loaded', array( 'Eventbrite_Pro_Admin', 'get_instance' ) );

}
