<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Eventbrite_Pro
 * @author    Yaron Guez <yaron@trestian.com>
 * @license   GPL-2.0+
 * @link      http://github.com/yaronguez/eventbrite-pro
 * @copyright 2013 Yaron Guez
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option('eventbrite_pro_options');
delete_transient('eventbrite_events');
