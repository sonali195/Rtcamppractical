<?php
/**
 * PHP version 8.0
 *
 * @file
 * This file is responsible for admin operations like add/remove image, drag and drop to change order etc..
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
function rtcmp_slideshow_admin_enqueue_scripts() {
	wp_enqueue_style(
		'rtcmp-slideshow-admin-jqueryui',
		plugin_dir_url( __FILE__ ) . 'assets/css/jquery-ui.min.css',
	);
	wp_enqueue_style(
		'rtcmp-slideshow-admin-custom',
		plugin_dir_url( __FILE__ ) . 'assets/css/rtcmp_custom_admin.css',
	);

	wp_enqueue_media();
	wp_enqueue_script(
		'rtcmp-slideshow-admin-jquery',
		plugin_dir_url( __FILE__ ) . 'assets/js/jquery.min.js',
		array(),
		'',
		true
	);
	wp_enqueue_script(
		'rtcmp-slideshow-admin-jqueryui',
		plugin_dir_url( __FILE__ ) . 'assets/js/jquery-ui.min.js',
		array( 'rtcmp-slideshow-admin-jquery' ),
		'1.0',
		true
	);
	wp_enqueue_script(
		'rtcmp-slideshow-admin',
		plugin_dir_url( __FILE__ ) . 'assets/js/rtcmp_slideshow_admin.js',
		array(
			'rtcmp-slideshow-admin-jquery',
			'jquery-ui-sortable',
		),
		'1.1',
		true
	);

	wp_enqueue_style( 'wp-jquery-ui-core' );
	wp_enqueue_style( 'wp-jquery-ui-theme' );
	wp_enqueue_script( 'jquery-ui-sortable' );
}
add_action( 'admin_enqueue_scripts', 'rtcmp_slideshow_admin_enqueue_scripts' );

/**
 * Add menu page for the plugin in the admin menu.
 *
 * This function adds a menu page for the Rtcmp Slideshow plugin
 * to the WordPress admin menu.
 *
 * @return void
 **/
function rtcmp_slideshow_admin_menu() {
	add_options_page(
		'Rtcmp Slideshow Settings',
		'Rtcmp Slideshow',
		'manage_options',
		'rtcmp-slideshow-settings',
		'rtcmp_slideshow_settings_page'
	);
}
add_action( 'admin_menu', 'rtcmp_slideshow_admin_menu' );

/**
 * Create settings page for the plugin.
 *
 * This function is responsible for creating a settings page
 * for the Rtcmp Slideshow plugin in the WordPress admin area.
 *
 * @return void
 **/
function rtcmp_slideshow_settings_page() {
	?>
	<div class="wrap">
		<h2>Rtcmp Slideshow Settings</h2>
		
		<!-- Form to upload and manage images -->
		<form method="post" action="options.php">
			<?php settings_fields( 'rtcmp_slideshow_settings_group' ); ?>
			<?php do_settings_sections( 'rtcmp-slideshow-settings' ); ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Save images uploaded via the plugin.
 *
 * This function handles the saving of images uploaded
 * via the Rtcmp Slideshow plugin.
 *
 * @return void
 **/
function rtcmp_slideshow_save_images() {
	// Save images logic here
}

/**
 * Register and initialize settings
 *
 * @return void
 **/
function rtcmp_slideshow_admin_init() {
	register_setting(
		'rtcmp_slideshow_settings_group',
		'rtcmp_slideshow_images',
		'rtcmp_slideshow_sanitize_images'
	);

	add_settings_section(
		'rtcmp_slideshow_images_section',
		'Slider Images',
		'rtcmp_slideshow_images_section_callback',
		'rtcmp-slideshow-settings'
	);
	add_settings_field(
		'rtcmp_slideshow_images_field',
		'Upload Images',
		'rtcmp_slideshow_images_field_callback',
		'rtcmp-slideshow-settings',
		'rtcmp_slideshow_images_section'
	);
}
add_action( 'admin_init', 'rtcmp_slideshow_admin_init' );

/**
 * Sanitize images
 *
 * @param array $images An array of image paths.
 *
 * @return void
 **/
function rtcmp_slideshow_sanitize_images( $images ) {
	return is_array( $images ) ? $images : array();
}

/**
 * Images section callback
 *
 * @return void
 **/
function rtcmp_slideshow_images_section_callback() {
	echo '<p>Upload images for the slideshow and change their order by 
    dragging them.</p>';
}

/**
 * Images field callback
 *
 * @return void
 **/
function rtcmp_slideshow_images_field_callback() {
	$images = get_option( 'rtcmp_slideshow_images', array() );
	?>
	<ul id="rtcmp-slideshow-images-list" class="sortable-list">
		<?php foreach ( $images as $index => $image ) : ?>
			<li class="sortable-item" data-index="<?php echo $index; ?>">
				<div class="img_wrapper">
					<img 
						src="<?php echo $image['url']; ?>" 
						alt="<?php echo $image['title']; ?>" 
					/>
				
					<input 
						type="hidden" 
						name="rtcmp_slideshow_images[<?php echo $index; ?>][url]" 
						value="<?php echo $image['url']; ?>" 
					/>
					<input 
						type="hidden"
						name="rtcmp_slideshow_images[<?php echo $index; ?>][title]" 
						value="<?php echo $image['title']; ?>" 
					/>
					<input 
						type="button" 
						class="button remove-image-button"
						value="Remove"
					/>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
	<input
		type="button"
		class="button button-primary add-image-button"
		value="Add Image"
	/>
	<?php
}

/**
 * AJAX handler for saving image order
 *
 * @return void
 **/
function rtcmp_slideshow_save_image_order() {
	check_ajax_referer( 'rtcmp_slideshow_nonce', 'nonce' );

	if ( isset( $_POST['order'] ) && is_array( $_POST['order'] ) ) {
		$images = get_option( 'rtcmp_slideshow_images', array() );

		foreach ( $_POST['order'] as $index => $image_index ) {
			if ( isset( $images[ $image_index ] ) ) {
				$images[ $image_index ]['order'] = $index;
			}
		}

		uasort(
			$images,
			function ( $a, $b ) {
				return $a['order'] - $b['order'];
			}
		);

		update_option( 'rtcmp_slideshow_images', $images );
	}

	wp_send_json_success();
}
add_action( 'wp_ajax_rtcmp_slideshow_save_image_order_action', 'rtcmp_slideshow_save_image_order' );

/**
 * Include JavaScript for image sorting
 *
 * @return void
 **/
function rtcmp_slideshow_admin_js() {
	?>
	<script>
		jQuery(document).ready(function($) {
			$('#rtcmp-slideshow-images-list').sortable({
				update: function(event, ui) {
					var order = $(this).sortable(
						'toArray', { attribute: 'data-index' }
					);

					$.post(ajaxurl, {
						action: 'rtcmp_slideshow_save_image_order_action',
						nonce: '<?php echo wp_create_nonce( 'rtcmp_slideshow_nonce' ); ?>',
						order: order
					});
				}
			});

			$('.remove-image-button').click(function() {
				$(this).parent().parent().remove();
				var order = $('#rtcmp-slideshow-images-list').sortable(
					'toArray', { attribute: 'data-index' }
				);
				$.post(ajaxurl, {
					action: 'rtcmp_slideshow_save_image_order_action',
					nonce: '<?php echo wp_create_nonce( 'rtcmp_slideshow_nonce' ); ?>',
					order: order
				});
			});

			$('.add-image-button').click(function() {
				var frame = wp.media({
					title: 'Select Image',
					multiple: false,
					library: { type: 'image' },
					button: { text: 'Select' }
				});

				frame.on('select', function() {
					var attachment = frame.state().get('selection').first().toJSON();
					var index = $('#rtcmp-slideshow-images-list li').length;
					var html = '<li class="sortable-item" data-index="' + index + '">' +
									'<div class="img_wrapper">' +
									'<img src="' + attachment.url + '" alt="' + attachment.title + '" />' +
									'<input type="hidden" name="rtcmp_slideshow_images[' + index + '][url]" value="' + attachment.url + '" />' +
									'<input type="hidden" name="rtcmp_slideshow_images[' + index + '][title]" value="' + attachment.title + '" />' +
									'<input type="button" class="button remove-image-button" value="Remove" />' +
									'</div>' +
								'</li>';
					$('#rtcmp-slideshow-images-list').append(html);
					var order = $('#rtcmp-slideshow-images-list').sortable('toArray', { attribute: 'data-index' });
					$.post(ajaxurl, {
						action: 'rtcmp_slideshow_save_image_order_action',
						nonce: '<?php echo wp_create_nonce( 'rtcmp_slideshow_nonce' ); ?>',
						order: order
					});
				});

				frame.open();
			});
		});
	</script>
	<?php
}
add_action( 'admin_footer', 'rtcmp_slideshow_admin_js' );
