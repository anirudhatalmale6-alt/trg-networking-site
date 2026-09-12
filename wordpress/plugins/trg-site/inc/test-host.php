<?php
/**
 * Keep the test address usable after the site's own address becomes the live
 * domain.
 *
 * WordPress builds every link from one stored address and redirects anything
 * arriving on a different hostname to it. That is the right behaviour and it
 * is why the site pointed everyone at the test address before the switch — but
 * run in reverse it would make test.trgnetworking.com bounce to the live
 * domain, and during the changeover that test address is the only way TRG,
 * Madhuri and Kandy can actually look at the site.
 *
 * So: a request that arrives on the test hostname is answered as the test
 * site, with its own links; everything else uses the live address. Nothing is
 * duplicated and nothing is stored differently — this only changes what the
 * two options read as, for the duration of one request.
 *
 * The test host is also told not to be indexed, so the same pages cannot
 * compete with the live domain in search results while both answer.
 *
 * Delete this file once the changeover is finished and the test address is no
 * longer wanted.
 *
 * @package TRG_Site
 */

defined( 'ABSPATH' ) || exit;

/**
 * The hostname this request arrived on, lower-cased and without any port.
 *
 * @return string
 */
function trg_request_host() {
	$host = isset( $_SERVER['HTTP_HOST'] ) ? wp_unslash( $_SERVER['HTTP_HOST'] ) : '';
	$host = strtolower( trim( (string) $host ) );
	// Strip a port, and anything that is not a plausible hostname character.
	$host = preg_replace( '/:\d+$/', '', $host );
	return preg_replace( '/[^a-z0-9.\-]/', '', $host );
}

/**
 * Serve the test hostname as itself.
 */
function trg_test_host_urls() {
	if ( 'test.trgnetworking.com' !== trg_request_host() ) {
		return;
	}

	$url = 'http://test.trgnetworking.com';
	add_filter( 'option_siteurl', static function () use ( $url ) {
		return $url;
	} );
	add_filter( 'option_home', static function () use ( $url ) {
		return $url;
	} );

	// Two copies of the same pages are answering at once during the changeover.
	// Only the live domain should be in search results.
	add_filter( 'pre_option_blog_public', '__return_zero' );
}
add_action( 'plugins_loaded', 'trg_test_host_urls', 1 );
