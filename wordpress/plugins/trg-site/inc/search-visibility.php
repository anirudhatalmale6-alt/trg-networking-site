<?php
/**
 * Keep WordPress's own by-products out of the search index.
 *
 * A fresh WordPress install invents URLs nobody at TRG asked for: an author
 * archive, a category archive, date archives, and a page for every uploaded
 * file. They carry no content of their own — they list posts, and this site has
 * none — but WordPress still puts them in the sitemap that gets submitted to
 * Google. On a site selling security discipline, "Uncategorized" and
 * "author/trgnetworking" turning up in search results is a poor look, and the
 * empty ones compete with real pages for the crawler's attention.
 *
 * Two things happen here, both reversible by deleting this file:
 *
 *   1. Those views are marked noindex, so anything already crawled drops out.
 *   2. They are removed from wp-sitemap.xml, so nothing points at them.
 *
 * The blog itself is deliberately left alone. If TRG starts publishing articles
 * under Technology Insights, the category and date archives become real pages
 * with real content — at that point remove this file, or narrow it to the
 * author archive only.
 *
 * @package TRG_Site
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the current request is one of WordPress's empty by-product views.
 *
 * Deliberately not is_archive() wholesale: a genuine category archive with
 * posts in it is worth indexing, and this must not quietly hide one.
 *
 * @return bool
 */
function trg_is_thin_archive() {
	if ( is_author() || is_date() || is_attachment() ) {
		return true;
	}

	// Search results and paginated list pages: never worth indexing.
	if ( is_search() || is_paged() ) {
		return true;
	}

	// A category or tag archive is only worth indexing once it has posts of its
	// own. "Uncategorized" with nothing in it is not a page, it is a stub.
	if ( is_category() || is_tag() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term && $term->count < 1 ) {
			return true;
		}
	}

	return false;
}

/**
 * Mark those views noindex.
 *
 * Uses WordPress's own wp_robots filter so it reads as one robots tag rather
 * than a second one printed alongside core's.
 *
 * @param array<string,mixed> $robots Robots directives.
 * @return array<string,mixed>
 */
function trg_robots_noindex_thin( $robots ) {
	if ( trg_is_thin_archive() ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = false;
		unset( $robots['max-image-preview'] );
	}
	return $robots;
}
add_filter( 'wp_robots', 'trg_robots_noindex_thin' );

/**
 * Drop the users sitemap entirely.
 *
 * There is one account and it is an administrator login name. Publishing it
 * hands out half of a credential for free.
 *
 * @param bool   $provider Whether to include the provider.
 * @param string $name     Provider name.
 * @return bool
 */
function trg_sitemap_drop_users( $provider, $name ) {
	if ( 'users' === $name ) {
		return false;
	}
	return $provider;
}
add_filter( 'wp_sitemaps_add_provider', 'trg_sitemap_drop_users', 10, 2 );

/**
 * Drop empty terms from the sitemap.
 *
 * WordPress already excludes terms with no posts — but only once the count is
 * right. Belt and braces, and it keeps this file's behaviour matching the
 * noindex rule above exactly.
 *
 * @param array<string,mixed> $args Term query arguments.
 * @return array<string,mixed>
 */
function trg_sitemap_hide_empty_terms( $args ) {
	$args['hide_empty'] = true;
	return $args;
}
add_filter( 'wp_sitemaps_taxonomies_query_args', 'trg_sitemap_hide_empty_terms' );

/**
 * Send an attachment page to the file it describes.
 *
 * WordPress gives every upload its own URL with the image on it and nothing
 * else. They are thin pages that can outrank the real one for an image search,
 * and there is no reason for a visitor to land on one.
 */
function trg_attachment_to_file() {
	if ( ! is_attachment() ) {
		return;
	}
	$url = wp_get_attachment_url( get_queried_object_id() );
	if ( $url ) {
		wp_safe_redirect( $url, 301 );
		exit;
	}
}
add_action( 'template_redirect', 'trg_attachment_to_file' );
