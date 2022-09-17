<?php
	/**
	 * Plugin Name:       Fast Testimonial   
	 * Description:       Fast Testimonial is a fantastic WordPress plugin to get testimonial features in your WordPress website.
	 * Version:           1.0.0
	 * Requires at least: 5.2
	 * Requires PHP:      7.2
	 * Author:            reedwanul
	 * Author URI:        https://profiles.wordpress.org/reedwanul/
	 * License:           GPL v2 or later
	 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
	 * Text Domain:       fast-testimonial
	 * Domain Path:       /languages
	*/

    
define('FASTM_PLUGIN_FILE', __FILE__);
define('FASTM_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('FASTM_PLUGIN_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('FASTM_PLUGIN_URL', trailingslashit(plugins_url('/', __FILE__)));
define('FASTM_PLUGIN_VERSION', '1.0.0');


/**----------------------------------------------------------------*/
/* Include all file
/*-----------------------------------------------------------------*/

/**
 *
 */
include_once(dirname( __FILE__ ). '/inc/Fast_Testimonial_Loader.php');

if ( function_exists( 'fast_testimonial_run' ) ) {
    fast_testimonial_run();
}