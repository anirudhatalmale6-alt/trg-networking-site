<?php
/**
 * TRG Networking theme bootstrap.
 *
 * @package TRG_Networking
 */

defined( 'ABSPATH' ) || exit;

define( 'TRG_THEME_VERSION', '1.0.0' );

require_once get_template_directory() . '/inc/icons.php';
require_once get_template_directory() . '/inc/nav.php';
require_once get_template_directory() . '/inc/company.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/seo.php';

/**
 * Theme supports and menu locations.
 */
function trg_setup() {
	load_theme_textdomain( 'trg-networking', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'custom-logo', array(
		'height'      => 40,
		'width'       => 109,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets',
	) );

	register_nav_menus( array(
		'primary'           => __( 'Main menu (header)', 'trg-networking' ),
		'footer_services'   => __( 'Footer — Services column', 'trg-networking' ),
		'footer_industries' => __( 'Footer — Industries column', 'trg-networking' ),
		'footer_company'    => __( 'Footer — Company column', 'trg-networking' ),
		'legal'             => __( 'Footer — legal links', 'trg-networking' ),
	) );

	// The compiled stylesheet doubles as the editor stylesheet so a heading
	// looks in the editor the way it looks on the page.
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/trg.css' );

	// Colour palette so the client picks brand colours rather than typing hex.
	add_theme_support( 'editor-color-palette', array(
		array( 'name' => __( 'Brand blue', 'trg-networking' ), 'slug' => 'brand', 'color' => '#2563EB' ),
		array( 'name' => __( 'Brand dark', 'trg-networking' ), 'slug' => 'brand-dark', 'color' => '#1D4ED8' ),
		array( 'name' => __( 'Brand tint', 'trg-networking' ), 'slug' => 'brand-tint', 'color' => '#EFF6FF' ),
		array( 'name' => __( 'Ink', 'trg-networking' ), 'slug' => 'ink', 'color' => '#0F172A' ),
		array( 'name' => __( 'Body text', 'trg-networking' ), 'slug' => 'body', 'color' => '#1E293B' ),
		array( 'name' => __( 'Muted text', 'trg-networking' ), 'slug' => 'muted', 'color' => '#475569' ),
		array( 'name' => __( 'Canvas', 'trg-networking' ), 'slug' => 'canvas', 'color' => '#F8FAFC' ),
		array( 'name' => __( 'White', 'trg-networking' ), 'slug' => 'white', 'color' => '#FFFFFF' ),
	) );
	add_theme_support( 'disable-custom-colors' );
}
add_action( 'after_setup_theme', 'trg_setup' );

/**
 * Content width, used by embeds.
 */
function trg_content_width() {
	$GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'trg_content_width', 0 );

/**
 * Front-end assets.
 *
 * Versioned by file modification time rather than a hand-typed number, so a CSS
 * change is never hidden behind a browser or CDN cache.
 */
function trg_assets() {
	$css = get_template_directory() . '/assets/css/trg.css';
	$js  = get_template_directory() . '/assets/js/site.js';

	wp_enqueue_style(
		'trg-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@300..900&family=Outfit:wght@400..900&family=Plus+Jakarta+Sans:wght@400..800&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'trg-main',
		get_template_directory_uri() . '/assets/css/trg.css',
		array( 'trg-fonts' ),
		file_exists( $css ) ? (string) filemtime( $css ) : TRG_THEME_VERSION
	);

	wp_enqueue_script(
		'trg-site',
		get_template_directory_uri() . '/assets/js/site.js',
		array(),
		file_exists( $js ) ? (string) filemtime( $js ) : TRG_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'trg_assets' );

/**
 * Preconnect to the font host. Without this the browser only discovers
 * fonts.gstatic.com after parsing the stylesheet, which delays first paint.
 */
function trg_resource_hints( $hints, $relation ) {
	if ( 'preconnect' === $relation ) {
		$hints[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous' );
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'trg_resource_hints', 10, 2 );

/**
 * Theme asset URL helper.
 *
 * @param string $path Path below the theme directory.
 * @return string
 */
function trg_asset( $path ) {
	return get_template_directory_uri() . '/' . ltrim( $path, '/' );
}

/**
 * This is a brochure site: comments would only ever collect spam.
 */
function trg_disable_comments() {
	remove_post_type_support( 'post', 'comments' );
	remove_post_type_support( 'page', 'comments' );
}
add_action( 'init', 'trg_disable_comments' );
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );

/**
 * Body classes that let the stylesheet target the front page.
 *
 * @param array $classes Existing classes.
 * @return array
 */
function trg_body_class( $classes ) {
	$classes[] = 'trg';
	if ( is_front_page() ) {
		$classes[] = 'trg-home';
	}
	return $classes;
}
add_filter( 'body_class', 'trg_body_class' );

/*
 * There is deliberately no filter here to strip the <p> WordPress wraps around
 * a shortcode on its own line. Core already does it: shortcode_unautop() runs
 * on the_content at priority 10, straight after wpautop and before shortcodes
 * are expanded at 11. An earlier version of this file added its own version at
 * priority 8 — before wpautop had produced any <p> to strip — so it matched
 * nothing and did nothing. Removed rather than left in as reassuring noise.
 *
 * What core does NOT clean up is the <br /> wpautop inserts between the child
 * shortcodes inside an enclosing one. That is handled where it matters, in
 * trg_shortcode_children() in the plugin.
 */

/**
 * Keep the admin bar off the front end for logged-in editors previewing pages
 * — it shifts the sticky header down by 32px and makes "is the header right?"
 * impossible to answer honestly from a screenshot.
 */
add_filter( 'show_admin_bar', function ( $show ) {
	return is_admin() ? $show : ( current_user_can( 'manage_options' ) ? $show : false );
} );
