<?php
/**
 * Plugin Name:       TRG Site
 * Plugin URI:        https://www.trgnetworking.com/
 * Description:       The section blocks, contact form, editable service/industry/testimonial lists and old-URL redirects for the TRG Networking site. Keep this active — the theme's pages are built from the shortcodes it registers.
 * Version:           1.3.0
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Author:            Anirudha Talmale
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       trg-site
 *
 * Deliberately a plugin rather than theme code: the redirect map for the sixty
 * URLs on the old site, and the contact form's submissions, must survive a
 * theme change. Anything that would be lost with the theme does not belong in
 * the theme.
 *
 * @package TRG_Site
 */

defined( 'ABSPATH' ) || exit;

define( 'TRG_SITE_VERSION', '1.3.0' );
define( 'TRG_SITE_FILE', __FILE__ );
define( 'TRG_SITE_DIR', plugin_dir_path( __FILE__ ) );
define( 'TRG_SITE_URL', plugin_dir_url( __FILE__ ) );

require_once TRG_SITE_DIR . 'inc/helpers.php';
require_once TRG_SITE_DIR . 'inc/post-types.php';
require_once TRG_SITE_DIR . 'inc/shortcodes.php';
require_once TRG_SITE_DIR . 'inc/shortcodes-contact.php';
require_once TRG_SITE_DIR . 'inc/smtp.php';
require_once TRG_SITE_DIR . 'inc/pictures.php';
require_once TRG_SITE_DIR . 'inc/contact-form.php';
require_once TRG_SITE_DIR . 'inc/redirects.php';
require_once TRG_SITE_DIR . 'inc/setup.php';

/**
 * Flush rewrite rules once on activation so the custom post types resolve.
 */
function trg_site_activate() {
	trg_register_post_types();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'trg_site_activate' );

/**
 * And once on deactivation, so nothing is left pointing at post types that
 * no longer exist.
 */
function trg_site_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'trg_site_deactivate' );
