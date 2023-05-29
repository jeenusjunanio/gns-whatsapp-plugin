<?php
/*
Plugin Name: GNS WA Floating Icon
Plugin URI: https://jeenus.in/gns-whatsapp-plugin
Description: A simple WhatsApp floating icon plugin for WordPress.
Version: 1.0.0
Author: Jeenus A
Author URI: https://jeenus.in
Text Domain: gns-whatsapp-plugin
*/

// Add the WhatsApp icon to the frontend
add_action( 'wp_footer', 'gns_wa_icon' );

function gns_wa_icon() {
	// Get the mobile number from the admin
	$mobile_number = get_option( 'gns_wa_mobile_number' );

	// Get the button position from the admin
	$button_position = get_option( 'gns_wa_button_position' );

	// Get the button size from the admin
	$button_size = get_option( 'gns_wa_button_size' );

	if ($mobile_number) {
		// Define the icon URL and size based on the button size
		switch ( $button_size ) {
			case 'sm':
				$icon_size = 'fa-lg';
				break;
			case 'md':
				$icon_size = 'fa-2x';
				break;
			case 'lg':
				$icon_size = 'fa-3x';
				break;
			default:
				$icon_size = 'fa-4x';
				break;
		}

		// Check if the user is accessing the website from a mobile device
		function isMobileDevice() {
			$mobileDevices = array("Android", "iPhone", "iPad", "iPod", "BlackBerry", "Windows Phone");
			$userAgent = $_SERVER['HTTP_USER_AGENT'];
			foreach ($mobileDevices as $device) {
				if (stristr($userAgent, $device)) {
					return true;
				}
			}
			return false;
		}

		// Output the WhatsApp icon HTML with the mobile number and button position
		// echo '<a href="https://web.whatsapp.com/send?phone=' . $mobile_number . '" target="_blank" class="gns-wa-icon gns-wa-'.$button_position.'"><i class="fab fa-whatsapp ' . $icon_size . '"></i></a>';

		if (isMobileDevice()) {
			// Mobile device: Open in WhatsApp app
			echo '<a href="whatsapp://send?phone=' . $mobile_number . '" class="gns-wa-icon gns-wa-'.$button_position.'"><i class="fab fa-whatsapp ' . $icon_size . '"></i></a>';
		} else {
			// Desktop device: Open in WhatsApp Web
			echo '<a href="https://web.whatsapp.com/send?phone=' . $mobile_number . '" target="_blank" class="gns-wa-icon gns-wa-'.$button_position.'"><i class="fab fa-whatsapp ' . $icon_size . '"></i></a>';
		}
	}
	
}

// Add the admin options page
add_action( 'admin_menu', 'gns_wa_icon_options_page' );

function gns_wa_icon_options_page() {
	add_options_page( 'GNS WA Icon Options', 'GNS WA Icon', 'manage_options', 'gns-wa-icon', 'gns_wa_icon_options_page_html' );
}

// Register the mobile number, button position, and button size options
add_action( 'admin_init', 'gns_wa_icon_options' );

function gns_wa_icon_options() {
	register_setting( 'gns_wa_icon_options', 'gns_wa_mobile_number' );
	register_setting( 'gns_wa_icon_options', 'gns_wa_button_position' );
	register_setting( 'gns_wa_icon_options', 'gns_wa_button_size' );
}

// Output the admin options page HTML
function gns_wa_icon_options_page_html() {
	?>
	<div class="wrap">
		<h1>GNS WA Icon Options</h1>
				<form method="post" action="options.php">
			<?php settings_fields( 'gns_wa_icon_options' ); ?>
			<?php do_settings_sections( 'gns_wa_icon_options' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Mobile Number:</th>
					<td><input type="text" name="gns_wa_mobile_number" value="<?php echo esc_attr( get_option( 'gns_wa_mobile_number' ) ); ?>"></td>
				</tr>
				<tr valign="top">
					<th scope="row">Button Position:</th>
					<td>
						<select name="gns_wa_button_position">
							<option value="top-left" <?php selected( get_option( 'gns_wa_button_position' ), 'top-left' ); ?>>Top Left</option>
							<option value="top-right" <?php selected( get_option( 'gns_wa_button_position' ), 'top-right' ); ?>>Top Right</option>
							<option value="bottom-left" <?php selected( get_option( 'gns_wa_button_position' ), 'bottom-left' ); ?>>Bottom Left</option>
							<option value="bottom-right" <?php selected( get_option( 'gns_wa_button_position' ), 'bottom-right' ); ?>>Bottom Right</option>
							<option value="middle-left" <?php selected( get_option( 'gns_wa_button_position' ), 'middle-left' ); ?>>Middle Left</option>
							<option value="middle-right" <?php selected( get_option( 'gns_wa_button_position' ), 'middle-right' ); ?>>Middle Right</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Button Size:</th>
					<td>
						<select name="gns_wa_button_size">
							<option value="sm" <?php selected( get_option( 'gns_wa_button_size' ), 'sm' ); ?>>Small</option>
							<option value="md" <?php selected( get_option( 'gns_wa_button_size' ), 'md' ); ?>>Medium</option>
							<option value="lg" <?php selected( get_option( 'gns_wa_button_size' ), 'lg' ); ?>>Large</option>
						</select>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

// uninstall hook
function gns_wa_icon_uninstall() {
	// Delete options from the database
	delete_option( 'gns_wa_mobile_number' );
	delete_option( 'gns_wa_button_position' );
	delete_option( 'gns_wa_button_size' );
}
register_uninstall_hook( __FILE__, 'gns_wa_icon_uninstall' );

// Include css and js from fontawesome
function my_gns_whatsapp_plugin_scripts() {
    wp_enqueue_style( 'gns-whatsapp-custom-style', plugin_dir_url( __FILE__ ) . 'assets/fontawesome/css/all.min.css' );
    wp_enqueue_style( 'gns-wa-custom-style', plugin_dir_url( __FILE__ ) . 'assets/css/gns-custom.css' );
    wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'assets/fontawesome/css/all.min.css' );
    wp_enqueue_script( 'font-awesome', plugin_dir_url( __FILE__ ) . 'assets/fontawesome/js/all.min.js', array(), '5.15.3', true );
}
add_action( 'wp_enqueue_scripts', 'my_gns_whatsapp_plugin_scripts' );

?>