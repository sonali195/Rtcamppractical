<?php
/**
 * Plugin Name
 * php version 8.0
 *
 * @category  Slideshow
 * @package   RtCmpSlideshow
 * @author    Sonali Prajapati <sonaliprajapati2019@gmail.com>
 * @copyright 2019 RtCmp
 * @license   GPL-2.0-or-later <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link      #
 *
 * @wordpress-plugin
 * Plugin Name:       RtCmp Slideshow
 * Plugin URI:        #
 * Description:       A plugin for creating a slideshow from uploaded images.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Sonali Prajapati <sonali@example.com>
 * Author URI:        #
 * Text Domain:       rtcmp-slideshow
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://example.com/my-plugin/
 **/

// Include PHP_CodeSniffer autoload file
require_once __DIR__ . '/vendor/autoload.php';

// Include admin and frontend files
require_once plugin_dir_path( __FILE__ ) . 'admin/rtcmp_slideshow_admin.php';
require_once plugin_dir_path( __FILE__ ) . 'frontend/rtcmp-slideshow-frontend.php';

// Activation and Deactivation hooks
register_activation_hook( __FILE__, 'rtcmp_slideshow_plugin_activate' );
register_deactivation_hook( __FILE__, 'rtcmp_slideshow_plugin_deactivate' );

// Register custom ruleset XML file
add_filter(
	'phpcs_additional_args',
	function ( $args ) {
		$args['standard'] = __DIR__ . '/phpcs.xml.dist';
		return $args;
	}
);

/**
 * Activate the plugin.
 *
 * @return void
 **/
function rtcmp_slideshow_plugin_activate() {
	// Activation code
}

/**
 * Deactivate the plugin.
 *
 * @return void
 **/
function rtcmp_slideshow_plugin_deactivate() {
	// Deactivation code
}
