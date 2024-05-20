<?php
/**
 * PHP version 8.0
 *
 * @file
 * This file is handle all frontend operations like generate shortcode to show slider
 *
 * @category  Slideshow
 * @package   RtCmpSlideshow
 * @author    Sonali Prajapati <sonaliprajapati2019@gmail.com>
 * @copyright 2019 RtCmp
 * @license   GPL-2.0-or-later <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link      #
 **/

/**
 * Enqueue scripts and styles for the admin area.
 *
 * This function is responsible for enqueueing necessary scripts and styles
 * specifically for the WordPress admin area related to the Rtcmp Slideshow plugin.
 *
 * @return void
 **/
function rtcmp_slideshow_frontend_enqueue_scripts() {
	wp_enqueue_style(
		'rtcmp-slideshow-frontend-slick-theme',
		plugin_dir_url( __FILE__ ) . 'assets/css/swiper-bundle.min.css',
	);

	wp_enqueue_style(
		'rtcmp-slideshow-admin-custom',
		plugin_dir_url( __FILE__ ) . 'assets/css/rtcmp-custom-frontend.css',
	);

	wp_enqueue_script(
		'rtcmp-slideshow-frontend-jquery',
		plugin_dir_url( __FILE__ ) . 'assets/js/jquery.min.js',
		array(),
		'',
		true
	);

	wp_enqueue_script(
		'rtcmp-slideshow-frontend-swiper-bundle',
		plugin_dir_url( __FILE__ ) . 'assets/js/swiper-bundle.min.js',
		array( 'rtcmp-slideshow-frontend-jquery' ),
		'1.0',
		true
	);

	wp_enqueue_script(
		'rtcmp-slideshow-frontend-custom',
		plugin_dir_url( __FILE__ ) . 'assets/js/rtcmp-custom-frontend.js',
		array( 'rtcmp-slideshow-frontend-jquery' ),
		'1.0',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'rtcmp_slideshow_frontend_enqueue_scripts' );

/**
 * Enqueue scripts and styles for the admin area.
 *
 * This function is responsible for enqueueing necessary scripts and styles
 * specifically for the WordPress admin area related to the Rtcmp Slideshow plugin.
 *
 * @return void
 **/
function rtcmp_slideshow_shortcode() {
	ob_start();
	$images = get_option( 'rtcmp_slideshow_images', array() );
	if ( ! empty( $images ) ) {
		?>
		<div class="swiper mySwiper">
			<div class="swiper-wrapper">
		<?php foreach ( $images as $image ) : ?>
			<div class="swiper-slide">
				<img 
					src="<?php echo $image['url']; ?>" 
					alt="<?php echo $image['title']; ?>" 
				/>
			</div>
		<?php endforeach; ?>
			</div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
			<div class="swiper-pagination"></div>
			</div>
		<?php
	}
	return ob_get_clean();
}
add_shortcode( 'myslideshow', 'rtcmp_slideshow_shortcode' );
