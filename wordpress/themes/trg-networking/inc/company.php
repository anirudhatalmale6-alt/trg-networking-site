<?php
/**
 * Company details — the one place a phone number, address or social profile is
 * stored. Everything on the site (header, footer, contact page, schema markup,
 * the contact form's notification email) reads from here.
 *
 * Values live in the Customizer so the client edits them in
 * Appearance -> Customize -> TRG company details, not in a PHP file.
 *
 * @package TRG_Networking
 */

defined( 'ABSPATH' ) || exit;

/**
 * Defaults, matching the approved build.
 *
 * @return array<string,string>
 */
function trg_company_defaults() {
	return array(
		'name'            => 'TRG Networking',
		'legal_name'      => 'TRG Networking, Inc.',
		'founded'         => '1992',
		'phone'           => '410-363-6980',
		// The address PRINTED on the site. Changed to info@ at the client's
		// request — they do not want marketing@ published. Where enquiry
		// notifications are delivered is a separate setting under
		// Settings → TRG Email, so the two can differ.
		'email'           => 'info@trgnetworking.com',
		'marketing_email' => 'marketing@trgnetworking.com',
		'street'          => '9861 Broken Land Parkway, Suite 100',
		'city'            => 'Columbia',
		'state'           => 'Maryland',
		'state_short'     => 'MD',
		'zip'             => '21046',
		'tagline'         => 'Maryland-based • Supporting businesses nationwide',
		'blurb'           => 'Making technology simpler, safer and more responsive for businesses since 1992.',
		'linkedin'        => 'https://www.linkedin.com/company/trg-networking-inc',
		'facebook'        => 'https://www.facebook.com/TRGNetworkinginc/',
		'twitter'         => 'https://twitter.com/trg_networking',
		'youtube'         => 'https://www.youtube.com/channel/UCzYPG-1UjnPmWU_kYwl4XNg',
	);
}

/**
 * Read one company detail.
 *
 * @param string $key Field name, e.g. 'phone'.
 * @return string
 */
function trg_company( $key ) {
	$defaults = trg_company_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	$value    = get_theme_mod( 'trg_' . $key, $default );

	return is_string( $value ) ? trim( $value ) : $default;
}

/**
 * Phone number as a tel: href. Built from the displayed number so changing the
 * number in the Customizer changes the dial link too — the two cannot drift.
 *
 * @return string
 */
function trg_phone_href() {
	$digits = preg_replace( '/\D+/', '', trg_company( 'phone' ) );
	if ( 10 === strlen( $digits ) ) {
		$digits = '1' . $digits; // US number entered without a country code.
	}
	return 'tel:+' . $digits;
}

/**
 * One-line postal address.
 *
 * @return string
 */
function trg_address_line() {
	return sprintf(
		'%s, %s, %s %s',
		trg_company( 'street' ),
		trg_company( 'city' ),
		trg_company( 'state' ),
		trg_company( 'zip' )
	);
}

/**
 * Social profiles that actually have a URL set.
 *
 * @return array<string,array{label:string,url:string,icon:string}>
 */
function trg_social_profiles() {
	$map = array(
		'linkedin' => array( 'label' => 'LinkedIn',    'icon' => 'linkedin' ),
		'facebook' => array( 'label' => 'Facebook',    'icon' => 'facebook' ),
		'twitter'  => array( 'label' => 'X (Twitter)', 'icon' => 'twitter' ),
		'youtube'  => array( 'label' => 'YouTube',     'icon' => 'youtube' ),
	);

	$out = array();
	foreach ( $map as $key => $meta ) {
		$url = trg_company( $key );
		if ( $url ) {
			$out[ $key ] = $meta + array( 'url' => $url );
		}
	}
	return $out;
}

/**
 * Permalink for a page, looked up by its slug.
 *
 * Used instead of home_url('/contact') so the link keeps working if the client
 * renames a page: WordPress moves the slug and this follows it. Falls back to
 * the literal path when the page does not exist yet, which is what happens
 * between installing the theme and running TRG Setup.
 *
 * @param string $slug Page slug.
 * @return string
 */
function trg_page_url( $slug ) {
	$cache = wp_cache_get( 'trg_page_urls', 'trg' );
	if ( ! is_array( $cache ) ) {
		$cache = array();
	}
	if ( isset( $cache[ $slug ] ) ) {
		return $cache[ $slug ];
	}

	$page = get_page_by_path( $slug );
	$url  = $page ? get_permalink( $page ) : home_url( '/' . ltrim( $slug, '/' ) );

	$cache[ $slug ] = $url;
	wp_cache_set( 'trg_page_urls', $cache, 'trg' );

	return $url;
}
