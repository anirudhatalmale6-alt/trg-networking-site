<?php
/**
 * Old-URL redirects.
 *
 * The site this replaces has sixty indexed URLs. Without these, every one of
 * them becomes a 404 on launch day and the rankings behind them are thrown
 * away.
 *
 * Done in PHP rather than in .htaccess on purpose: the target host has changed
 * twice during this project, and PHP redirects work identically on Apache,
 * IIS/Azure App Service, nginx and LiteSpeed. There is a matching .htaccess in
 * the handoff for anyone who would rather the web server did it.
 *
 * They only run on a request WordPress could not otherwise answer, so a rule
 * here can never shadow a real page. Renaming a page in the dashboard does not
 * break them either: targets are resolved by slug at request time.
 *
 * @package TRG_Site
 */

defined( 'ABSPATH' ) || exit;

/**
 * Exact-path redirects: old path (no leading or trailing slash) => new target.
 *
 * A target starting with "/" is used as-is; anything else is treated as a page
 * slug and resolved to that page's permalink.
 *
 * @return array<string,string>
 */
function trg_redirect_map() {
	return apply_filters( 'trg_redirect_map', array(

		// Slugs from this project's own earlier revision. test2 renamed the
		// Azure page and dropped two others, so anything already linking to the
		// old addresses lands on the page that replaced them rather than a 404.
		'azure'                             => 'azure-cloud-hosting',
		'network-infrastructure'            => 'managed-it-services',
		'strategic-it-vcio'                 => 'why-trg',

		// Interim slugs from the Hostinger staging build.
		'managed-it'                        => 'managed-it-services',
		'help-desk'                         => 'help-desk-it-support',
		'microsoft-cloud'                   => 'microsoft-365-cloud',
		'ai-services'                       => 'secure-ai-adoption',
		'cmmc'                              => 'cmmc-readiness',
		'business-continuity'               => 'backup-business-continuity',
		'industries/construction'           => 'construction',
		'industries/manufacturing'          => 'manufacturing',
		'industries/government-contractors' => 'government-contractors',
		'industries/professional-services'  => 'professional-services',

		// The live WordPress site at www.trgnetworking.com.
		// /managed-it-services/ needs no rule: it is the same path on both
		// sites, so its ranking carries straight over.
		'network-security'                  => 'cybersecurity',
		'cloud-computing'                   => 'microsoft-365-cloud',
		'data-backup-and-recovery'          => 'backup-business-continuity',
		'about-us/contact-us'               => 'contact',
		'about-us/referral-program'         => 'about',
		'about-us'                          => 'about',
		'why-choose-us'                     => 'why-trg',
		'our-clients'                       => 'resources/case-studies',
		'initial-consultation'              => 'contact',
		'discoverycall'                     => 'contact',
		'cyber-security-tip-of-the-week'    => 'resources',
		'itbuyersguide'                     => 'resources/guides',
		'new-cybersecurity-crisis'          => 'cybersecurity',
		'3problems'                         => 'resources',
		'aspirin'                           => 'resources',
		'thank-you-aspirin'                 => 'resources',
		'closerlook'                        => 'resources',
		'is-this-you'                       => 'resources',

		// WordPress generates its sitemap at /wp-sitemap.xml. The old site was
		// submitted to Search Console under the other two names, and an SEO
		// plugin installed later would take these paths over itself — until
		// then, point them at the one that exists rather than returning 404.
		'sitemap.xml'                       => '/wp-sitemap.xml',
		'sitemap_index.xml'                 => '/wp-sitemap.xml',
	) );
}

/**
 * Pattern redirects, for the old URL shapes that cannot be listed one by one.
 *
 * @return array<string,string>
 */
function trg_redirect_patterns() {
	return apply_filters( 'trg_redirect_patterns', array(
		// Every WordPress testimonial post.
		'#^testimonial/[^/]+$#'                  => 'resources/case-studies',
		// Every dated blog post, e.g. /2024/03/11/some-title/.
		'#^[0-9]{4}/[0-9]{2}/[0-9]{2}/.+$#'      => 'resources',
		// Category and tag archives from the old theme.
		'#^(category|tag)/[^/]+$#'               => 'resources',
	) );
}

/**
 * Resolve a redirect target to a URL.
 *
 * @param string $target Page slug or absolute path.
 * @return string
 */
function trg_redirect_target( $target ) {
	if ( 0 === strpos( $target, '/' ) || preg_match( '#^https?://#i', $target ) ) {
		return home_url( $target );
	}
	return trg_site_page_url( $target );
}

/**
 * Send the redirect.
 */
function trg_do_redirects() {
	// Only ever act on a request WordPress could not answer. A rule here can
	// then never shadow a page that exists.
	if ( ! is_404() ) {
		return;
	}

	$path = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
	$path = trim( (string) $path, '/' );

	// A subdirectory install adds its folder to every path; strip it so the map
	// stays written in site-relative terms.
	$home_path = trim( (string) wp_parse_url( home_url(), PHP_URL_PATH ), '/' );
	if ( $home_path && 0 === strpos( $path, $home_path . '/' ) ) {
		$path = substr( $path, strlen( $home_path ) + 1 );
	} elseif ( $home_path && $path === $home_path ) {
		$path = '';
	}

	if ( '' === $path ) {
		return;
	}

	$path = strtolower( $path );
	$map  = trg_redirect_map();

	if ( isset( $map[ $path ] ) ) {
		wp_safe_redirect( trg_redirect_target( $map[ $path ] ), 301 );
		exit;
	}

	// The free assessment landing page pre-selects the assessment on the form.
	if ( 'free-network-assessment' === $path ) {
		wp_safe_redirect( add_query_arg( 'type', 'it-assessment', trg_site_page_url( 'contact' ) ), 301 );
		exit;
	}

	foreach ( trg_redirect_patterns() as $pattern => $target ) {
		if ( preg_match( $pattern, $path ) ) {
			wp_safe_redirect( trg_redirect_target( $target ), 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'trg_do_redirects', 1 );
