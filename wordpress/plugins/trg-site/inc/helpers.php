<?php
/**
 * Shared helpers.
 *
 * The plugin can be active while a different theme is: everything here that
 * touches a theme function checks it exists first, so switching themes to
 * debug something never produces a fatal error on the live site.
 *
 * @package TRG_Site
 */

defined( 'ABSPATH' ) || exit;

/**
 * Company detail, falling back to a sensible value if the theme is not active.
 *
 * @param string $key Field name.
 * @return string
 */
function trg_site_company( $key ) {
	if ( function_exists( 'trg_company' ) ) {
		return trg_company( $key );
	}

	$fallback = array(
		'name'  => get_bloginfo( 'name' ),
		'email' => get_option( 'admin_email' ),
	);
	return isset( $fallback[ $key ] ) ? $fallback[ $key ] : '';
}

/**
 * Icon markup, or an empty string when the theme is not providing icons.
 *
 * @param string $name  Lucide icon name.
 * @param int    $size  Pixel size.
 * @param string $class Extra classes.
 * @return string
 */
function trg_site_icon( $name, $size = 16, $class = '' ) {
	return function_exists( 'trg_get_icon' ) ? trg_get_icon( $name, $size, $class ) : '';
}

/**
 * Permalink for a page slug.
 *
 * @param string $slug Page slug.
 * @return string
 */
function trg_site_page_url( $slug ) {
	if ( function_exists( 'trg_page_url' ) ) {
		return trg_page_url( $slug );
	}
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . ltrim( $slug, '/' ) );
}

/**
 * Phone number as a tel: href.
 *
 * @return string
 */
function trg_site_phone_href() {
	return function_exists( 'trg_phone_href' ) ? trg_phone_href() : '';
}

/**
 * Render the children of an enclosing shortcode.
 *
 * Why this is not just do_shortcode(): wpautop runs on the_content BEFORE
 * shortcodes are expanded, and it turns every newline between the child
 * shortcodes into a "<br />". Those survive into the container's markup, and
 * when the container is a CSS grid each one becomes an empty grid item — so
 * four cards in a four-column grid land in columns 2 and 4 with holes beside
 * them. Nothing in an automated check notices: the headings, links, images and
 * page width are all still correct. It is only visible by looking at the page.
 *
 * Card and step bodies are passed through wp_strip_all_tags() anyway, so no
 * intentional line break is lost here.
 *
 * @param string $content Raw inner content.
 * @return string
 */
function trg_shortcode_children( $content ) {
	$out = do_shortcode( (string) $content );
	$out = preg_replace( '#\s*<br\s*/?>\s*#i', '', $out );
	return trim( (string) $out );
}

/**
 * Split a shortcode attribute that holds a list.
 *
 * Written for people, not for code: "Microsoft, Cisco, Dell" and
 * "Microsoft | Cisco | Dell" both work, and stray whitespace or a trailing
 * separator is ignored rather than producing an empty item.
 *
 * @param string $value     Raw attribute value.
 * @param string $separator Separator character.
 * @return array<int,string>
 */
function trg_split_list( $value, $separator = ',' ) {
	if ( ! is_string( $value ) || '' === trim( $value ) ) {
		return array();
	}
	$parts = explode( $separator, $value );
	$parts = array_map( 'trim', $parts );
	return array_values( array_filter( $parts, static function ( $part ) {
		return '' !== $part;
	} ) );
}

/**
 * Render an eyebrow + heading + optional body, the site's standard section
 * opener.
 *
 * @param array $args Keys: eyebrow, title, body, align, pill, light, heading_level.
 * @return string
 */
function trg_section_head( $args ) {
	$args = wp_parse_args( $args, array(
		'eyebrow'       => '',
		'title'         => '',
		'body'          => '',
		// test2 left-aligns every section header on the site; there is no
		// text-center anywhere in its markup. align="center" is still honoured
		// so the closing call-to-action band can centre itself.
		'align'         => 'left',
		'pill'          => false,
		'light'         => false,
		'heading_level' => 'h2',
	) );

	$centred = 'center' === $args['align'];
	$level   = in_array( $args['heading_level'], array( 'h1', 'h2', 'h3' ), true ) ? $args['heading_level'] : 'h2';

	$out = '<div class="' . ( $centred ? 'mx-auto max-w-2xl text-center' : 'max-w-3xl' ) . '">';

	if ( $args['eyebrow'] ) {
		if ( $args['pill'] ) {
			$out .= '<span class="eyebrow-pill">' . esc_html( $args['eyebrow'] ) . '</span>';
		} else {
			// Plain text, no leading rule: on test2 only the page hero carries
			// the short dash, every section eyebrow below it is bare.
			$tint = $args['light'] ? 'text-white/70' : '';
			$out .= '<span class="eyebrow ' . esc_attr( $tint ) . '">' . esc_html( $args['eyebrow'] ) . '</span>';
		}
	}

	$out .= sprintf(
		'<%1$s class="mt-4 text-[30px] leading-[1.15] sm:text-[38px] %2$s">%3$s</%1$s>',
		$level,
		$args['light'] ? '!text-white' : '',
		wp_kses_post( $args['title'] )
	);

	if ( $args['body'] ) {
		$out .= '<p class="mt-4 text-[17px] leading-relaxed ' . ( $args['light'] ? 'text-white/75' : 'text-muted' ) . '">'
			. wp_kses_post( $args['body'] ) . '</p>';
	}

	return $out . '</div>';
}

/**
 * The tinted square that holds a card icon.
 *
 * @param string $icon Lucide icon name.
 * @param string $tone brand|ink|white.
 * @return string
 */
function trg_icon_tile( $icon, $tone = 'brand' ) {
	$tones = array(
		'brand' => 'bg-brand-50 text-brand-600',
		'ink'   => 'bg-ink/5 text-ink',
		'white' => 'bg-white/10 text-white',
	);
	$class = isset( $tones[ $tone ] ) ? $tones[ $tone ] : $tones['brand'];

	/*
	 * A value that is not one of the shipped icon names is drawn as a short
	 * text token instead — "IT", "365", "C3" — which is what test2 puts in this
	 * tile. Falling through to the icon renderer would print an empty box, so
	 * the check is on what actually came back, not on a list of allowed names:
	 * a card the client edits later can say anything.
	 */
	$svg = trg_site_icon( $icon, 20 );
	if ( '' === trim( (string) $svg ) ) {
		return '<div class="grid h-12 w-12 place-items-center rounded-xl ' . esc_attr( $class ) . ' font-display text-[13px] font-extrabold" aria-hidden="true">'
			. esc_html( $icon ) . '</div>';
	}

	return '<div class="flex h-12 w-12 items-center justify-center rounded-xl ' . esc_attr( $class ) . '" aria-hidden="true">'
		. $svg . '</div>';
}

/**
 * The list of Lucide icons offered in the admin dropdowns.
 *
 * @return array<string,string>
 */
function trg_icon_choices() {
	return array(
		'server'      => __( 'Server', 'trg-site' ),
		'shield'      => __( 'Shield', 'trg-site' ),
		'cloud'       => __( 'Cloud', 'trg-site' ),
		'cloud-cog'   => __( 'Cloud with cog', 'trg-site' ),
		'sparkles'    => __( 'Sparkles (AI)', 'trg-site' ),
		'database'    => __( 'Database', 'trg-site' ),
		'badge-check' => __( 'Badge with check', 'trg-site' ),
		'headset'     => __( 'Headset', 'trg-site' ),
		'activity'    => __( 'Activity', 'trg-site' ),
		'users'       => __( 'People', 'trg-site' ),
		'map'         => __( 'Map', 'trg-site' ),
		'check'       => __( 'Check', 'trg-site' ),
		'mail'        => __( 'Envelope', 'trg-site' ),
		'phone'       => __( 'Phone', 'trg-site' ),
	);
}
