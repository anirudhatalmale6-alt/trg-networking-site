<?php
/**
 * Search-engine and social-preview markup.
 *
 * The site this replaces has sixty indexed URLs, so the new one has to arrive
 * with canonical tags, descriptions, Open Graph and organisation markup on day
 * one rather than as a later job.
 *
 * If Yoast or Rank Math is active, this file steps aside completely: two
 * plugins writing the same tags produce duplicate canonicals, which is worse
 * than having none.
 *
 * @package TRG_Networking
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether a dedicated SEO plugin is handling the head already.
 *
 * @return bool
 */
function trg_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

/**
 * Description for the current view: the page excerpt when one is set, the
 * site tagline otherwise.
 *
 * @return string
 */
function trg_meta_description() {
	if ( is_singular() ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			$custom = get_post_meta( $post->ID, '_trg_meta_description', true );
			if ( $custom ) {
				return $custom;
			}
			if ( $post->post_excerpt ) {
				return $post->post_excerpt;
			}
		}
	}
	return get_bloginfo( 'description' );
}

/**
 * Print the head tags.
 */
function trg_head_tags() {
	if ( trg_seo_plugin_active() ) {
		return;
	}

	$description = trg_meta_description();
	$canonical   = is_singular() ? get_permalink() : home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );
	$title       = wp_get_document_title();

	$image = '';
	if ( is_singular() && has_post_thumbnail() ) {
		$image = get_the_post_thumbnail_url( null, 'full' );
	}
	if ( ! $image ) {
		$image = trg_asset( 'assets/img/hero-team.webp' );
	}

	echo "\n";
	if ( $description ) {
		printf( "<meta name=\"description\" content=\"%s\">\n", esc_attr( wp_strip_all_tags( $description ) ) );
	}
	printf( "<link rel=\"canonical\" href=\"%s\">\n", esc_url( $canonical ) );

	printf( "<meta property=\"og:type\" content=\"%s\">\n", is_singular( 'post' ) ? 'article' : 'website' );
	printf( "<meta property=\"og:title\" content=\"%s\">\n", esc_attr( $title ) );
	printf( "<meta property=\"og:description\" content=\"%s\">\n", esc_attr( wp_strip_all_tags( $description ) ) );
	printf( "<meta property=\"og:url\" content=\"%s\">\n", esc_url( $canonical ) );
	printf( "<meta property=\"og:site_name\" content=\"%s\">\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( "<meta property=\"og:image\" content=\"%s\">\n", esc_url( $image ) );
	printf( "<meta name=\"twitter:card\" content=\"summary_large_image\">\n" );
	printf( "<meta name=\"twitter:title\" content=\"%s\">\n", esc_attr( $title ) );
	printf( "<meta name=\"twitter:description\" content=\"%s\">\n", esc_attr( wp_strip_all_tags( $description ) ) );
	printf( "<meta name=\"twitter:image\" content=\"%s\">\n", esc_url( $image ) );
}
add_action( 'wp_head', 'trg_head_tags', 2 );

/**
 * Organisation markup, printed once on the homepage.
 *
 * Uses ProfessionalService rather than LocalBusiness: TRG serves clients
 * nationwide and the address is an office, not a storefront people visit.
 */
function trg_schema() {
	if ( ! is_front_page() ) {
		return;
	}

	$socials = array_values( wp_list_pluck( trg_social_profiles(), 'url' ) );

	$schema = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'ProfessionalService',
		'name'          => trg_company( 'legal_name' ),
		'alternateName' => trg_company( 'name' ),
		'url'           => home_url( '/' ),
		'logo'          => trg_asset( 'assets/img/logo-trg.webp' ),
		'image'         => trg_asset( 'assets/img/hero-team.webp' ),
		'telephone'     => trg_company( 'phone' ),
		'email'         => trg_company( 'email' ),
		'foundingDate'  => trg_company( 'founded' ),
		'description'   => trg_company( 'blurb' ),
		'address'       => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => trg_company( 'street' ),
			'addressLocality' => trg_company( 'city' ),
			'addressRegion'   => trg_company( 'state_short' ),
			'postalCode'      => trg_company( 'zip' ),
			'addressCountry'  => 'US',
		),
		'areaServed'    => array( '@type' => 'Country', 'name' => 'United States' ),
	);

	if ( $socials ) {
		$schema['sameAs'] = $socials;
	}

	printf(
		"<script type=\"application/ld+json\">%s</script>\n",
		wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
	);
}
add_action( 'wp_head', 'trg_schema', 3 );

/**
 * A one-field meta box for the search-result description, so the client can
 * write the snippet Google shows without installing an SEO plugin.
 */
function trg_meta_box() {
	if ( trg_seo_plugin_active() ) {
		return;
	}
	add_meta_box(
		'trg_seo',
		__( 'Search engine description', 'trg-networking' ),
		'trg_meta_box_render',
		array( 'page', 'post' ),
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'trg_meta_box' );

/**
 * Render the meta box.
 *
 * @param WP_Post $post Post being edited.
 */
function trg_meta_box_render( $post ) {
	wp_nonce_field( 'trg_seo_save', 'trg_seo_nonce' );
	$value = get_post_meta( $post->ID, '_trg_meta_description', true );
	?>
	<p>
		<label for="trg_meta_description">
			<?php esc_html_e( 'The sentence Google shows under the page title. Aim for 140–160 characters. Leave blank to use the page excerpt.', 'trg-networking' ); ?>
		</label>
	</p>
	<textarea id="trg_meta_description" name="trg_meta_description" rows="3" class="large-text"><?php echo esc_textarea( $value ); ?></textarea>
	<?php
}

/**
 * Save the meta box.
 *
 * @param int $post_id Post ID.
 */
function trg_meta_box_save( $post_id ) {
	if ( ! isset( $_POST['trg_seo_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['trg_seo_nonce'] ) ), 'trg_seo_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$value = isset( $_POST['trg_meta_description'] )
		? sanitize_textarea_field( wp_unslash( $_POST['trg_meta_description'] ) )
		: '';

	if ( $value ) {
		update_post_meta( $post_id, '_trg_meta_description', $value );
	} else {
		delete_post_meta( $post_id, '_trg_meta_description' );
	}
}
add_action( 'save_post', 'trg_meta_box_save' );

/**
 * Let a page carry its own <title>.
 *
 * WordPress builds "Page name – Site name". test2 writes a distinct, keyword-led
 * title for each page — "Managed IT Services in Maryland | TRG Networking" — so
 * a page that has one stored wins, and everything else keeps WordPress's.
 *
 * @param string $title Assembled document title.
 * @return string
 */
function trg_document_title( $title ) {
	if ( ! is_singular() ) {
		return $title;
	}
	$post = get_queried_object();
	if ( ! $post instanceof WP_Post ) {
		return $title;
	}
	$custom = get_post_meta( $post->ID, '_trg_meta_title', true );
	return $custom ? $custom : $title;
}
add_filter( 'pre_get_document_title', 'trg_document_title', 20 );
