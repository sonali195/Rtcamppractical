<?php
/**
 * PHP version 8.0
 *
 * @file
 * Register the uninstall hook
 *
 * @category  Slideshow
 * @package   RtCmpSlideshow
 * @author    Sonali Prajapati <sonaliprajapati2019@gmail.com>
 * @copyright 2019 RtCmp
 * @license   GPL-2.0-or-later <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link      #
 **/
register_uninstall_hook( __FILE__, 'rtcmp_slideshow_uninstall' );

/**
 * Uninstall plugin
 *
 * @return void
 **/
function rtcmp_slideshow_uninstall() {
	// Remove the plugin options from the database
	delete_option( 'rtcmp_slideshow_images' );
	// You can add additional uninstall steps here if needed
}
