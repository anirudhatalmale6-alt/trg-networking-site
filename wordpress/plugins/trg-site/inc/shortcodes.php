<?php
/**
 * Section shortcodes.
 *
 * Every visual band on the site is one of these. They are attribute-driven on
 * purpose: the client edits the words in the page editor, in plain text, and
 * cannot accidentally break a layout by editing markup.
 *
 * Where a section is just prose — a heading and a paragraph — it is NOT a
 * shortcode. Those are ordinary WordPress blocks so they can be edited
 * visually. Shortcodes are only used where the layout is doing something a
 * core block cannot.
 *
 * @package TRG_Site
 */

defined( 'ABSPATH' ) || exit;

/**
 * Resolve an image reference to a URL.
 *
 * Accepts a media library ID, a full URL, or the filename of one of the images
 * that ships with the theme.
 *
 * @param string $value Image reference.
 * @return string
 */
function trg_image_url( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	if ( ctype_digit( $value ) ) {
		$url = wp_get_attachment_image_url( (int) $value, 'full' );
		return $url ? $url : '';
	}
	if ( preg_match( '#^https?://#i', $value ) || 0 === strpos( $value, '/' ) ) {
		return $value;
	}
	// A picture replaced under Settings → TRG Pictures wins over the file that
	// ships with the theme, so every place that references "hero-team" picks up
	// the client's own photograph without any of them being edited.
	if ( function_exists( 'trg_picture_override_url' ) ) {
		$override = trg_picture_override_url( preg_replace( '/\.(webp|jpe?g|png|avif|gif|svg)$/i', '', $value ) );
		if ( $override ) {
			return $override;
		}
	}
	if ( ! preg_match( '/\.(webp|jpe?g|png|avif|gif|svg)$/i', $value ) ) {
		$value .= '.webp';
	}
	return get_template_directory_uri() . '/assets/img/' . ltrim( $value, '/' );
}

/**
 * Resolve a link attribute: a page slug, a full URL, or empty.
 *
 * @param string $value Link reference.
 * @return string
 */
function trg_link_url( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	// "phone" and "email" resolve to the live values from the Customizer, so a
	// button in page content can never disagree with the header and footer.
	if ( 'phone' === $value ) {
		return trg_site_phone_href();
	}
	if ( 'email' === $value ) {
		return 'mailto:' . trg_site_company( 'email' );
	}
	// Delimiter is "~" rather than "#", because "#" is one of the prefixes this
	// pattern has to match and would otherwise end the pattern early.
	if ( preg_match( '~^(https?:|tel:|mailto:|#)~i', $value ) ) {
		return $value;
	}
	// A path with a query string or fragment, e.g. /contact?type=assessment.
	if ( 0 === strpos( $value, '/' ) && preg_match( '/[?#]/', $value ) ) {
		return home_url( $value );
	}
	return trg_site_page_url( ltrim( $value, '/' ) );
}

/**
 * A pair of call-to-action buttons, used by both heroes.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function trg_hero_buttons( $atts ) {
	$out = '';

	if ( $atts['button_text'] ) {
		$out .= sprintf(
			'<a href="%s" class="btn-primary">%s%s</a>',
			esc_url( trg_link_url( $atts['button_link'] ) ),
			esc_html( $atts['button_text'] ),
			trg_site_icon( 'arrow-right', 16 )
		);
	}

	if ( $atts['button2_text'] ) {
		// button2_style="text" renders the secondary action as a bare link
		// rather than an outlined button, which is what test2 does beside the
		// hero's primary call to action.
		$out .= sprintf(
			'<a href="%s" class="%s">%s</a>',
			esc_url( trg_link_url( $atts['button2_link'] ) ),
			'text' === ( $atts['button2_style'] ?? '' ) ? 'btn-text' : 'btn-outline',
			esc_html( $atts['button2_text'] )
		);
	}

	// call_button="1" renders the number from the Customizer, so the button can
	// never disagree with the header and footer. test2 shows the bare number as
	// a text link beside the primary action rather than an outlined "Call ..."
	// button, and this is the pattern on every one of its inner pages.
	if ( ! empty( $atts['call_button'] ) ) {
		$out .= sprintf(
			'<a href="%s" class="btn-text">%s</a>',
			esc_url( trg_site_phone_href() ),
			esc_html( trg_site_company( 'phone' ) )
		);
	}

	return $out ? '<div class="mt-9 flex flex-col gap-4 sm:flex-row sm:items-center">' . $out . '</div>' : '';
}

/* -------------------------------------------------------------------------
 * Heroes
 * ---------------------------------------------------------------------- */

/**
 * Homepage hero.
 *
 * @param array $atts Attributes.
 * @return string
 */
function trg_sc_home_hero( $atts ) {
	$atts = shortcode_atts( array(
		'eyebrow'        => '',
		'line1'          => '',
		'line2'          => '',
		'accent'         => '',
		'lede'           => '',
		'button_text'    => __( 'Talk With Our Team', 'trg-site' ),
		'button_link'    => 'contact',
		'button2_text'   => __( 'Free IT assessment', 'trg-site' ),
		'button2_link'   => 'contact',
		'button2_style'  => '',
		'badges'         => '',
		'jump_text'      => '',
		'jump_link'      => '#services',
		'image'          => 'hero-team',
		'image_alt'      => '',
		'caption_eyebrow' => '',
		'caption'        => '',
		'cards'          => '',
		'strip'          => '',
	), $atts, 'trg_home_hero' );

	$badges = trg_split_list( $atts['badges'], '|' );
	$strip  = trg_split_list( $atts['strip'], '|' );
	$cards  = trg_split_list( $atts['cards'], ';' );
	$image  = trg_image_url( $atts['image'] );

	ob_start();
	?>
	<section class="relative overflow-hidden bg-brand-50">
		<div class="dotted-field pointer-events-none absolute inset-0 opacity-60" aria-hidden="true"></div>

		<div class="shell relative grid items-center gap-12 py-14 sm:py-16 lg:grid-cols-2 lg:gap-14 lg:py-20">
			<div class="animate-fadeUp">
				<?php if ( $atts['eyebrow'] ) : ?>
					<span class="flex items-center gap-3">
						<span class="eyebrow-rule" aria-hidden="true"></span>
						<span class="eyebrow"><?php echo esc_html( $atts['eyebrow'] ); ?></span>
					</span>
				<?php endif; ?>

				<?php
				// Built from whichever lines are actually filled in. Emitting a
				// <br> per *slot* rather than per *line* would leave a blank line
				// in the middle of the headline whenever line2 is empty.
				$lines = array();
				if ( $atts['line1'] ) {
					$lines[] = esc_html( $atts['line1'] );
				}
				if ( $atts['line2'] ) {
					$lines[] = esc_html( $atts['line2'] );
				}
				if ( $atts['accent'] ) {
					$lines[] = '<span class="text-brand-600">' . esc_html( $atts['accent'] ) . '</span>';
				}
				?>
				<h1 class="mt-6 text-[36px] leading-[1.06] sm:text-[52px] lg:text-[58px]">
					<?php echo implode( '<br>', $lines ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above. ?>
				</h1>

				<?php if ( $atts['lede'] ) : ?>
					<p class="mt-6 max-w-xl text-[18px] leading-relaxed text-muted"><?php echo esc_html( $atts['lede'] ); ?></p>
				<?php endif; ?>

				<?php echo trg_hero_buttons( $atts ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside. ?>

				<?php if ( $badges ) : ?>
					<ul class="mt-8 flex flex-wrap gap-2">
						<?php foreach ( $badges as $badge ) : ?>
							<li class="inline-flex items-center gap-1.5 rounded-full border border-line bg-white px-3 py-1.5 text-[13px] font-medium text-muted shadow-sm">
								<?php echo trg_site_icon( 'check', 13, 'text-brand-600' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<?php echo esc_html( $badge ); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( $atts['jump_text'] ) : ?>
					<a href="<?php echo esc_url( $atts['jump_link'] ); ?>" class="mt-8 inline-flex items-center gap-2 font-heading text-[14px] font-semibold text-brand-600 hover:text-brand-700">
						<?php echo esc_html( $atts['jump_text'] ); ?>
						<?php echo trg_site_icon( 'arrow-down', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				<?php endif; ?>
			</div>

			<div class="relative">
				<?php if ( $image ) : ?>
					<div class="relative overflow-hidden rounded-2xl shadow-[0_30px_70px_-30px_rgba(15,23,42,0.45)]">
						<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $atts['image_alt'] ); ?>"
							width="1376" height="768" fetchpriority="high" class="aspect-[16/10] w-full object-cover">
						<div class="pointer-events-none absolute inset-x-0 bottom-0 h-2/5" style="background:linear-gradient(to top,rgba(15,23,42,0.85),transparent)" aria-hidden="true"></div>
						<?php if ( $atts['caption'] || $atts['caption_eyebrow'] ) : ?>
							<div class="absolute inset-x-0 bottom-0 p-5 sm:p-6">
								<p class="font-heading text-[10.5px] font-bold uppercase tracking-[0.16em] text-brand-200"><?php echo esc_html( $atts['caption_eyebrow'] ); ?></p>
								<p class="mt-1.5 font-heading text-[19px] font-extrabold leading-tight text-white sm:text-[22px]"><?php echo esc_html( $atts['caption'] ); ?></p>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( $cards ) : ?>
					<?php
					/*
					 * Two positions, and only two: card 1 floats over the top-left
					 * of the image, card 2 over the bottom-right, which is the
					 * arrangement test2 uses.
					 *
					 * The floating is switched on at `lg:` ONLY. Below that the
					 * cards fall back to a normal stack underneath the image.
					 * The Lovable build positioned these against the viewport at
					 * every width, so on a phone the right-hand card hung off the
					 * edge of the screen and pushed the page sideways. Anything
					 * beyond the first two cards stays in the stack at all widths
					 * rather than being dropped silently.
					 */
					$float_class = array(
						0 => 'lg:absolute lg:left-[-2.5rem] lg:top-16 lg:z-10 lg:w-64',
						1 => 'lg:absolute lg:bottom-24 lg:right-[-2.5rem] lg:z-10 lg:w-64',
					);
					?>
					<ul class="mt-4 grid gap-3 sm:grid-cols-2 lg:mt-0 lg:block">
						<?php foreach ( $cards as $i => $card ) : ?>
							<?php $parts = array_pad( trg_split_list( $card, '|' ), 2, '' ); ?>
							<li class="flex items-start gap-3 rounded-xl border border-line bg-white p-3.5 shadow-sm <?php echo esc_attr( $float_class[ $i ] ?? 'lg:mt-3' ); ?>">
								<span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-md bg-brand-50 text-brand-600" aria-hidden="true">
									<?php echo trg_site_icon( 'check', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								</span>
								<span class="min-w-0">
									<span class="block font-heading text-[10.5px] font-bold uppercase tracking-[0.14em] text-brand-600"><?php echo esc_html( $parts[0] ); ?></span>
									<span class="block text-[14px] font-semibold text-ink"><?php echo esc_html( $parts[1] ); ?></span>
								</span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( $strip ) : ?>
			<div class="relative border-t border-brand-100 bg-white/60">
				<div class="shell flex flex-wrap items-center justify-center gap-x-10 gap-y-2.5 py-5">
					<?php foreach ( $strip as $item ) : ?>
						<span class="font-heading text-[11px] font-bold uppercase tracking-[0.18em] text-brand-600"><?php echo esc_html( $item ); ?></span>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_home_hero', 'trg_sc_home_hero' );

/**
 * Inner-page hero.
 *
 * @param array $atts Attributes.
 * @return string
 */
function trg_sc_hero( $atts ) {
	$atts = shortcode_atts( array(
		'eyebrow'      => '',
		'title'        => '',
		'lede'         => '',
		'button_text'  => '',
		'button_link'  => 'contact',
		'button2_text' => '',
		'button2_link' => 'contact',
		'button2_style' => '',
		'call_button'  => '',
	), $atts, 'trg_hero' );

	// Falling back to the page title means a hero can never render headless,
	// which would leave the page with no <h1> at all.
	$title = $atts['title'] ? $atts['title'] : get_the_title();

	ob_start();
	?>
	<?php // Same tinted dot field as the homepage hero, which is what test2 uses on every inner page. ?>
	<section class="relative overflow-hidden bg-brand-50">
		<div class="dotted-field pointer-events-none absolute inset-0 opacity-60" aria-hidden="true"></div>
		<div class="shell relative py-16 sm:py-24">
			<div class="max-w-4xl">
				<?php if ( $atts['eyebrow'] ) : ?>
					<span class="eyebrow"><?php echo esc_html( $atts['eyebrow'] ); ?></span>
				<?php endif; ?>
				<h1 class="mt-5 text-[34px] leading-[1.1] sm:text-[46px] lg:text-[52px]"><?php echo esc_html( $title ); ?></h1>
				<?php if ( $atts['lede'] ) : ?>
					<p class="mt-5 max-w-2xl text-[18px] leading-relaxed text-muted"><?php echo esc_html( $atts['lede'] ); ?></p>
				<?php endif; ?>
				<?php echo trg_hero_buttons( $atts ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_hero', 'trg_sc_hero' );

/* -------------------------------------------------------------------------
 * Bands
 * ---------------------------------------------------------------------- */

/**
 * Dark statistics band.
 *
 * @param array $atts Attributes.
 * @return string
 */
function trg_sc_stats( $atts ) {
	$atts  = shortcode_atts( array( 'items' => '' ), $atts, 'trg_stats' );
	$items = trg_split_list( $atts['items'], ';' );
	if ( ! $items ) {
		return '';
	}

	ob_start();
	?>
	<section class="bg-ink py-12">
		<div class="shell grid grid-cols-2 gap-8 lg:grid-cols-4">
			<?php foreach ( $items as $item ) : ?>
				<?php $parts = array_pad( trg_split_list( $item, '|' ), 2, '' ); ?>
				<div class="text-center">
					<div class="font-display text-[34px] font-extrabold leading-none text-white sm:text-[40px]"><?php echo esc_html( $parts[0] ); ?></div>
					<div class="mt-2 text-[13.5px] text-white/60"><?php echo esc_html( $parts[1] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_stats', 'trg_sc_stats' );

/**
 * Scrolling partner strip.
 *
 * @param array $atts Attributes.
 * @return string
 */
function trg_sc_partners( $atts ) {
	$atts  = shortcode_atts( array(
		'title' => __( 'Technology partners & alliances', 'trg-site' ),
		'items' => '',
	), $atts, 'trg_partners' );
	$items = trg_split_list( $atts['items'], ',' );
	if ( ! $items ) {
		return '';
	}

	ob_start();
	?>
	<section class="border-b border-line bg-white py-12">
		<div class="shell">
			<p class="text-center font-heading text-[11px] font-bold uppercase tracking-[0.16em] text-soft"><?php echo esc_html( $atts['title'] ); ?></p>
			<?php // Overflow is clipped by the parent so the marquee can never make the page scroll sideways. ?>
			<div class="relative mt-7 overflow-hidden">
				<div class="flex w-max animate-marquee gap-3">
					<?php foreach ( array_merge( $items, $items ) as $i => $item ) : ?>
						<span class="flex h-11 shrink-0 items-center rounded-lg border border-line bg-canvas px-6 font-heading text-[14px] font-bold text-soft"
							<?php echo $i >= count( $items ) ? 'aria-hidden="true"' : ''; ?>><?php echo esc_html( $item ); ?></span>
					<?php endforeach; ?>
				</div>
				<div class="pointer-events-none absolute inset-y-0 left-0 w-16 bg-gradient-to-r from-white to-transparent" aria-hidden="true"></div>
				<div class="pointer-events-none absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-white to-transparent" aria-hidden="true"></div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_partners', 'trg_sc_partners' );

/**
 * Card grid. Wraps [trg_card] children.
 *
 * @param array  $atts    Attributes.
 * @param string $content Inner shortcodes.
 * @return string
 */
function trg_sc_cards( $atts, $content = '' ) {
	$atts = shortcode_atts( array(
		'eyebrow' => '',
		'title'   => '',
		'body'    => '',
		'pill'    => '',
		'columns' => '3',
		'bg'      => 'canvas',
		'id'      => '',
	), $atts, 'trg_cards' );

	$cols = array(
		'2' => 'sm:grid-cols-2',
		'3' => 'sm:grid-cols-2 lg:grid-cols-3',
		'4' => 'sm:grid-cols-2 lg:grid-cols-4',
	);
	$grid = isset( $cols[ $atts['columns'] ] ) ? $cols[ $atts['columns'] ] : $cols['3'];
	$bg   = 'white' === $atts['bg'] ? 'bg-white' : 'bg-canvas';

	$head = '';
	if ( $atts['title'] ) {
		$head = trg_section_head( array(
			'eyebrow' => $atts['eyebrow'],
			'title'   => $atts['title'],
			'body'    => $atts['body'],
			'pill'    => (bool) $atts['pill'],
		) );
	}

	return sprintf(
		'<section %s class="section %s"><div class="shell">%s<div class="%s grid gap-5 %s">%s</div></div></section>',
		$atts['id'] ? 'id="' . esc_attr( $atts['id'] ) . '"' : '',
		esc_attr( $bg ),
		$head,
		$head ? 'mt-12' : '',
		esc_attr( $grid ),
		trg_shortcode_children( $content )
	);
}
add_shortcode( 'trg_cards', 'trg_sc_cards' );

/**
 * One card inside [trg_cards].
 *
 * @param array  $atts    Attributes.
 * @param string $content Card body text.
 * @return string
 */
function trg_sc_card( $atts, $content = '' ) {
	$atts = shortcode_atts( array(
		'icon'  => 'check',
		'title' => '',
		'link'  => '',
		'cta'   => '',
		'badge' => '',
		'num'   => '',
	), $atts, 'trg_card' );

	$body = wp_kses_post( trim( wp_strip_all_tags( $content ) ) );
	$url  = trg_link_url( $atts['link'] );
	$tag  = $url ? 'a' : 'div';

	// num="01" replaces the icon tile with a numeral, which is how test2 marks
	// the capability grids. A number and an icon would compete, so num wins.
	if ( '' !== $atts['num'] ) {
		$inner = '<span class="font-heading text-[13px] font-bold tracking-wider text-brand-600">' . esc_html( $atts['num'] ) . '</span>';
		$lead  = 'mt-3 ';
	} else {
		$inner = 'none' === $atts['icon'] ? '' : trg_icon_tile( $atts['icon'] );
		$lead  = 'none' === $atts['icon'] ? '' : 'mt-4 ';
	}
	$inner .= '<h3 class="' . $lead . 'text-[17px]' . ( $url ? ' group-hover:text-brand-600' : '' ) . '">' . esc_html( $atts['title'] ) . '</h3>';
	$inner .= '<p class="mt-2 flex-1 text-[15px] leading-relaxed text-muted">' . $body . '</p>';

	if ( $url && $atts['cta'] ) {
		$inner .= '<span class="mt-4 inline-flex items-center gap-1.5 font-heading text-[13.5px] font-bold text-brand-600">'
			. esc_html( $atts['cta'] )
			. trg_site_icon( 'arrow-right', 14, 'transition-transform group-hover:translate-x-1' )
			. '</span>';
	}

	// A badge instead of a link: used where a download does not exist yet. A
	// button that goes nowhere reads as a broken feature, so we say "Coming
	// soon" rather than shipping a dead link.
	if ( $atts['badge'] ) {
		$inner .= '<p class="mt-4 inline-flex w-fit rounded-full bg-canvas px-3 py-1 font-heading text-[12px] font-bold uppercase tracking-wider text-soft">'
			. esc_html( $atts['badge'] ) . '</p>';
	}

	return sprintf(
		'<%1$s %2$s class="card-hover %3$sflex flex-col">%4$s</%1$s>',
		$tag,
		$url ? 'href="' . esc_url( $url ) . '"' : '',
		$url ? 'group ' : '',
		$inner
	);
}
add_shortcode( 'trg_card', 'trg_sc_card' );

/**
 * Service cards, read from the Service cards admin menu.
 *
 * @param array $atts Attributes.
 * @return string
 */
function trg_sc_services( $atts ) {
	$atts = shortcode_atts( array(
		'eyebrow' => '',
		'title'   => '',
		'body'    => '',
		'pill'    => '',
		'bg'      => 'white',
		'id'      => 'services',
		'limit'   => '-1',
		'exclude' => '',
	), $atts, 'trg_services' );

	$cards = trg_get_cards( 'trg_service', (int) $atts['limit'], (int) $atts['exclude'] );
	if ( ! $cards ) {
		return '';
	}

	$head = $atts['title'] ? trg_section_head( array(
		'eyebrow' => $atts['eyebrow'],
		'title'   => $atts['title'],
		'body'    => $atts['body'],
		'pill'    => (bool) $atts['pill'],
	) ) : '';

	ob_start();
	?>
	<section <?php echo $atts['id'] ? 'id="' . esc_attr( $atts['id'] ) . '"' : ''; ?> class="section <?php echo 'canvas' === $atts['bg'] ? 'bg-canvas' : 'bg-white'; ?>">
		<div class="shell">
			<?php echo $head; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in trg_section_head. ?>
			<div class="<?php echo $head ? 'mt-12' : ''; ?> grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
				<?php foreach ( $cards as $card ) : ?>
					<?php
					$url  = trg_card_link( $card );
					$icon = get_post_meta( $card->ID, '_trg_icon', true );
					$tag  = $url ? 'a' : 'div';
					?>
					<<?php echo esc_html( $tag ); ?> <?php echo $url ? 'href="' . esc_url( $url ) . '"' : ''; ?> class="card-hover <?php echo $url ? 'group ' : ''; ?>flex flex-col">
						<?php echo trg_icon_tile( $icon ? $icon : 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<h3 class="mt-4 text-[18px]<?php echo $url ? ' group-hover:text-brand-600' : ''; ?>"><?php echo esc_html( get_the_title( $card ) ); ?></h3>
						<p class="mt-2 flex-1 text-[15px] leading-relaxed text-muted"><?php echo esc_html( trg_card_body( $card ) ); ?></p>
						<?php if ( $url ) : ?>
							<span class="mt-4 inline-flex items-center gap-1.5 font-heading text-[13.5px] font-bold text-brand-600">
								<?php esc_html_e( 'Learn more', 'trg-site' ); ?>
								<?php echo trg_site_icon( 'arrow-right', 14, 'transition-transform group-hover:translate-x-1' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</span>
						<?php endif; ?>
					</<?php echo esc_html( $tag ); ?>>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_services', 'trg_sc_services' );

/**
 * Numbered industry rows.
 *
 * @param array $atts Attributes.
 * @return string
 */
function trg_sc_industries( $atts ) {
	$atts = shortcode_atts( array(
		'eyebrow' => '',
		'title'   => '',
		'body'    => '',
		'pill'    => '',
		'bg'      => 'white',
		'limit'   => '-1',
	), $atts, 'trg_industries' );

	$cards = trg_get_cards( 'trg_industry', (int) $atts['limit'] );
	if ( ! $cards ) {
		return '';
	}

	$head = $atts['title'] ? trg_section_head( array(
		'eyebrow' => $atts['eyebrow'],
		'title'   => $atts['title'],
		'body'    => $atts['body'],
		'pill'    => (bool) $atts['pill'],
	) ) : '';

	ob_start();
	?>
	<section class="section <?php echo 'canvas' === $atts['bg'] ? 'bg-canvas' : 'bg-white'; ?>">
		<div class="shell">
			<?php echo $head; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php // test2 lays the industries out as four cards, not as rows. ?>
			<div class="<?php echo $head ? 'mt-12' : ''; ?> grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
				<?php foreach ( $cards as $i => $card ) : ?>
					<?php
					$url  = trg_card_link( $card );
					$tags = get_post_meta( $card->ID, '_trg_tags', true );
					$tag  = $url ? 'a' : 'div';
					?>
					<<?php echo esc_html( $tag ); ?> <?php echo $url ? 'href="' . esc_url( $url ) . '"' : ''; ?>
						class="card-hover <?php echo $url ? 'group ' : ''; ?>flex flex-col">
						<span class="font-display text-[13px] font-bold tracking-wider text-brand-400"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<h3 class="mt-3 text-[17px]<?php echo $url ? ' group-hover:text-brand-600' : ''; ?>"><?php echo esc_html( get_the_title( $card ) ); ?></h3>
						<p class="mt-2 flex-1 text-[15px] leading-relaxed text-muted"><?php echo esc_html( trg_card_body( $card ) ); ?></p>
						<?php if ( $tags ) : ?>
							<p class="mt-3 text-[13px] text-soft"><?php echo esc_html( $tags ); ?></p>
						<?php endif; ?>
						<?php if ( $url ) : ?>
							<?php echo trg_site_icon( 'arrow-up-right', 19, 'mt-5 text-soft transition-all group-hover:translate-x-0.5 group-hover:-translate-y-0.5 group-hover:text-brand-600' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<?php endif; ?>
					</<?php echo esc_html( $tag ); ?>>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_industries', 'trg_sc_industries' );

/**
 * Testimonial cards.
 *
 * @param array $atts Attributes.
 * @return string
 */
function trg_sc_testimonials( $atts ) {
	$atts = shortcode_atts( array(
		'eyebrow'   => '',
		'title'     => '',
		'body'      => '',
		'pill'      => '',
		'bg'        => 'white',
		'cta_text'  => '',
		'cta_link'  => 'why-trg',
		// attribution="0" prints the quote on its own. test2 shows both
		// testimonials unattributed, and until each client has agreed in
		// writing to be named on the new site that is the correct default for
		// the homepage. The names stay in the Testimonials admin screen, so
		// turning attribution back on is a one-word edit, not a re-entry job.
		'attribution' => '1',
	), $atts, 'trg_testimonials' );

	$cards = trg_get_cards( 'trg_testimonial' );
	if ( ! $cards ) {
		return '';
	}

	$head = $atts['title'] ? trg_section_head( array(
		'eyebrow' => $atts['eyebrow'],
		'title'   => $atts['title'],
		'body'    => $atts['body'],
		'pill'    => (bool) $atts['pill'],
	) ) : '';

	ob_start();
	?>
	<section class="section <?php echo 'canvas' === $atts['bg'] ? 'bg-canvas' : 'bg-white'; ?>">
		<div class="shell">
			<?php echo $head; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div class="<?php echo $head ? 'mt-12' : ''; ?> grid gap-5 md:grid-cols-2">
				<?php foreach ( $cards as $card ) : ?>
					<figure class="card flex flex-col bg-canvas">
						<?php echo trg_site_icon( 'quote', 26, 'text-brand-200' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<blockquote class="mt-4 flex-1 text-[17px] leading-relaxed text-body">&ldquo;<?php echo esc_html( trg_card_body( $card ) ); ?>&rdquo;</blockquote>
						<?php if ( '0' !== (string) $atts['attribution'] ) : ?>
							<figcaption class="mt-6 border-t border-line pt-4">
								<span class="block font-heading text-[15px] font-bold text-ink"><?php echo esc_html( get_the_title( $card ) ); ?></span>
								<?php $org = get_post_meta( $card->ID, '_trg_org', true ); ?>
								<?php if ( $org ) : ?>
									<span class="block text-[13.5px] text-soft"><?php echo esc_html( $org ); ?></span>
								<?php endif; ?>
							</figcaption>
						<?php endif; ?>
					</figure>
				<?php endforeach; ?>
			</div>
			<?php if ( $atts['cta_text'] ) : ?>
				<div class="mt-10 text-center">
					<a href="<?php echo esc_url( trg_link_url( $atts['cta_link'] ) ); ?>" class="btn-outline">
						<?php echo esc_html( $atts['cta_text'] ); ?>
						<?php echo trg_site_icon( 'arrow-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_testimonials', 'trg_sc_testimonials' );

/**
 * Image on one side, heading + bullets + button on the other.
 *
 * @param array $atts Attributes.
 * @return string
 */
function trg_sc_media_split( $atts ) {
	$atts = shortcode_atts( array(
		'image'       => '',
		'image_alt'   => '',
		'eyebrow'     => '',
		'title'       => '',
		'body'        => '',
		'bullets'     => '',
		'pills'       => '',
		'button_text' => '',
		'button_link' => 'contact',
		'note_title'  => '',
		'note_body'   => '',
		'note_icon'   => 'users',
		'reverse'     => '',
		'bg'          => 'canvas',
	), $atts, 'trg_media_split' );

	$image   = trg_image_url( $atts['image'] );
	$bullets = trg_split_list( $atts['bullets'], '|' );
	$pills   = trg_split_list( $atts['pills'], ',' );
	$reverse = (bool) $atts['reverse'];

	ob_start();
	?>
	<section class="section <?php echo 'white' === $atts['bg'] ? 'bg-white' : 'bg-canvas'; ?>">
		<?php // min-w-0 on both columns: without it a grid item's min-width:auto lets one wide child stretch the whole page. ?>
		<div class="shell grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
			<div class="relative min-w-0 <?php echo $reverse ? 'order-2 lg:order-1' : 'order-2'; ?>">
				<?php if ( $image ) : ?>
					<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $atts['image_alt'] ); ?>"
						width="1400" height="1050" loading="lazy"
						class="aspect-[4/3] w-full rounded-2xl object-cover shadow-[0_24px_60px_-28px_rgba(15,23,42,0.45)]">
				<?php endif; ?>

				<?php if ( $atts['note_title'] ) : ?>
					<div class="mt-4 flex items-start gap-3 rounded-xl border border-line bg-white p-4 shadow-sm sm:absolute sm:-bottom-6 sm:left-6 sm:mt-0 sm:max-w-[280px]">
						<span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600" aria-hidden="true">
							<?php echo trg_site_icon( $atts['note_icon'], 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</span>
						<span class="min-w-0">
							<span class="block font-heading text-[14px] font-bold text-ink"><?php echo esc_html( $atts['note_title'] ); ?></span>
							<span class="block text-[13px] leading-snug text-muted"><?php echo esc_html( $atts['note_body'] ); ?></span>
						</span>
					</div>
				<?php endif; ?>
			</div>

			<div class="min-w-0 <?php echo $reverse ? 'order-1 lg:order-2' : 'order-1'; ?>">
				<?php
				echo trg_section_head( array( // phpcs:ignore WordPress.Security.EscapeOutput
					'eyebrow' => $atts['eyebrow'],
					'title'   => $atts['title'],
					'body'    => $atts['body'],
					'align'   => 'left',
				) );
				?>

				<?php if ( $pills ) : ?>
					<?php
					// Calling the pills renderer directly rather than round-tripping
					// through do_shortcode(): esc_attr() would turn "POA&M" into
					// "POA&amp;M", which the shortcode parser stores literally and
					// the renderer then escapes again, printing "&amp;" on screen.
					?>
					<div class="mt-7"><?php echo trg_sc_pills( array( 'items' => $atts['pills'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside. ?></div>
				<?php endif; ?>

				<?php if ( $bullets ) : ?>
					<ul class="mt-7 space-y-3">
						<?php foreach ( $bullets as $bullet ) : ?>
							<li class="flex items-start gap-3 text-[15.5px] text-body">
								<?php echo trg_site_icon( 'check', 17, 'mt-0.5 shrink-0 text-brand-600' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<?php echo esc_html( $bullet ); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( $atts['button_text'] ) : ?>
					<a href="<?php echo esc_url( trg_link_url( $atts['button_link'] ) ); ?>" class="btn-primary mt-8">
						<?php echo esc_html( $atts['button_text'] ); ?>
						<?php echo trg_site_icon( 'arrow-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_media_split', 'trg_sc_media_split' );

/**
 * Rounded capability labels.
 *
 * @param array $atts Attributes.
 * @return string
 */
function trg_sc_pills( $atts ) {
	$atts  = shortcode_atts( array( 'items' => '' ), $atts, 'trg_pills' );
	$items = trg_split_list( $atts['items'], ',' );
	if ( ! $items ) {
		return '';
	}

	$out = '<ul class="flex flex-wrap gap-2">';
	foreach ( $items as $item ) {
		$out .= '<li class="rounded-full border border-brand-200 bg-brand-50 px-3.5 py-1.5 font-heading text-[13px] font-semibold text-brand-600">'
			. esc_html( $item ) . '</li>';
	}
	return $out . '</ul>';
}
add_shortcode( 'trg_pills', 'trg_sc_pills' );

/**
 * The "TRG perspective" band.
 *
 * A light, centred panel with a text link out to the contact page — test2's
 * shape. It used to be a dark rounded card; the dark treatment now belongs to
 * the Azure and Secure AI panels and to the closing call to action.
 *
 * @param array  $atts    Attributes.
 * @param string $content Body text.
 * @return string
 */
function trg_sc_perspective( $atts, $content = '' ) {
	$atts = shortcode_atts( array(
		'eyebrow'   => __( 'TRG perspective', 'trg-site' ),
		'title'     => '',
		'body'      => '',
		'link_text' => __( 'Start a conversation', 'trg-site' ),
		'link'      => 'contact',
	), $atts, 'trg_perspective' );

	$body = $atts['body'] ? $atts['body'] : trim( wp_strip_all_tags( $content ) );

	ob_start();
	?>
	<section class="section bg-canvas">
		<div class="shell mx-auto max-w-3xl text-center">
			<span class="eyebrow"><?php echo esc_html( $atts['eyebrow'] ); ?></span>
			<h2 class="mt-4 text-[26px] leading-[1.2] sm:text-[32px]"><?php echo esc_html( $atts['title'] ); ?></h2>
			<p class="mt-5 text-[17px] leading-relaxed text-muted"><?php echo esc_html( $body ); ?></p>
			<?php if ( $atts['link_text'] ) : ?>
				<a href="<?php echo esc_url( trg_link_url( $atts['link'] ) ); ?>"
					class="mt-8 inline-flex items-center gap-1.5 font-heading text-sm font-bold text-brand-600 hover:underline">
					<?php echo esc_html( $atts['link_text'] ); ?>
					<?php echo trg_site_icon( 'arrow-right', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</a>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_perspective', 'trg_sc_perspective' );

/**
 * Closing call-to-action band.
 *
 * @param array  $atts    Attributes.
 * @param string $content Body text.
 * @return string
 */
function trg_sc_cta_band( $atts, $content = '' ) {
	$atts = shortcode_atts( array(
		'eyebrow'      => __( 'Let’s talk', 'trg-site' ),
		'title'        => __( 'Ready for technology that feels easier?', 'trg-site' ),
		'body'         => __( 'Start with a straightforward conversation about your business, your concerns and what better IT support could look like.', 'trg-site' ),
		'button_text'  => __( 'Talk With Our Team', 'trg-site' ),
		'button_link'  => 'contact',
		'button2_text' => '',
		'button2_link' => 'contact',
		'button2_style' => '',
	), $atts, 'trg_cta_band' );

	$body = trim( wp_strip_all_tags( $content ) );
	if ( $body ) {
		$atts['body'] = $body;
	}

	ob_start();
	?>
	<?php // Solid navy and centred, which is how test2 closes every page. ?>
	<section class="section relative overflow-hidden bg-navy text-white">
		<div class="dotted-field pointer-events-none absolute inset-0 opacity-[0.12]" aria-hidden="true"></div>
		<div class="shell relative mx-auto max-w-3xl text-center">
			<?php
			echo trg_section_head( array( // phpcs:ignore WordPress.Security.EscapeOutput
				'eyebrow' => $atts['eyebrow'],
				'title'   => $atts['title'],
				'body'    => $atts['body'],
				'align'   => 'center',
				'light'   => true,
			) );
			?>
			<div class="mt-9 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
				<a href="<?php echo esc_url( trg_link_url( $atts['button_link'] ) ); ?>" class="btn-white">
					<?php echo esc_html( $atts['button_text'] ); ?>
					<?php echo trg_site_icon( 'arrow-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</a>
				<?php if ( $atts['button2_text'] ) : ?>
					<a href="<?php echo esc_url( trg_link_url( $atts['button2_link'] ) ); ?>" class="btn-ghost-l">
						<?php echo esc_html( $atts['button2_text'] ); ?>
					</a>
				<?php endif; ?>
				<a href="<?php echo esc_url( trg_site_phone_href() ); ?>" class="btn-ghost-l">
					<?php
					/* translators: %s: phone number. */
					printf( esc_html__( 'Or call %s', 'trg-site' ), esc_html( trg_site_company( 'phone' ) ) );
					?>
				</a>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_cta_band', 'trg_sc_cta_band' );

/**
 * FAQ accordion.
 *
 * Built on <details>/<summary>: it opens with the keyboard alone, works with
 * JavaScript disabled, and is exposed to assistive technology without any ARIA
 * bookkeeping of our own.
 *
 * @param array  $atts    Attributes.
 * @param string $content [trg_faq_item] children.
 * @return string
 */
function trg_sc_faq( $atts, $content = '' ) {
	$atts = shortcode_atts( array(
		'eyebrow' => __( 'Common questions', 'trg-site' ),
		'title'   => __( 'What business leaders ask us.', 'trg-site' ),
		'bg'      => 'canvas',
	), $atts, 'trg_faq' );

	$items = trg_shortcode_children( $content );
	if ( ! trim( $items ) ) {
		return '';
	}

	return sprintf(
		'<section class="section %s"><div class="shell max-w-3xl">%s<div class="mt-10 space-y-3" data-trg-faq>%s</div></div></section>',
		'white' === $atts['bg'] ? 'bg-white' : 'bg-canvas',
		trg_section_head( array( 'eyebrow' => $atts['eyebrow'], 'title' => $atts['title'] ) ),
		$items
	);
}
add_shortcode( 'trg_faq', 'trg_sc_faq' );

/**
 * One question and answer.
 *
 * @param array  $atts    Attributes.
 * @param string $content Answer.
 * @return string
 */
function trg_sc_faq_item( $atts, $content = '' ) {
	$atts = shortcode_atts( array( 'q' => '' ), $atts, 'trg_faq_item' );
	$answer = trim( wp_strip_all_tags( $content ) );
	if ( ! $atts['q'] || ! $answer ) {
		return '';
	}

	return '<details class="group overflow-hidden rounded-xl border border-line bg-white transition-colors open:border-brand-200">'
		. '<summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-5 py-4 font-heading text-[15.5px] font-bold text-ink marker:hidden hover:text-brand-600 [&::-webkit-details-marker]:hidden">'
		. esc_html( $atts['q'] )
		. '<span class="shrink-0 text-brand-600" aria-hidden="true">'
		. '<span class="group-open:hidden">' . trg_site_icon( 'plus', 17 ) . '</span>'
		. '<span class="hidden group-open:inline">' . trg_site_icon( 'minus', 17 ) . '</span>'
		. '</span></summary>'
		. '<div class="px-5 pb-5 text-[15px] leading-relaxed text-muted">' . esc_html( $answer ) . '</div>'
		. '</details>';
}
add_shortcode( 'trg_faq_item', 'trg_sc_faq_item' );

/**
 * Numbered "what happens next" steps.
 *
 * @param array  $atts    Attributes.
 * @param string $content [trg_step] children.
 * @return string
 */
function trg_sc_process( $atts, $content = '' ) {
	$atts = shortcode_atts( array(
		'eyebrow' => '',
		'title'   => '',
		'body'    => '',
		'bg'      => 'canvas',
		'columns' => '3',
	), $atts, 'trg_process' );

	$steps = trg_shortcode_children( $content );
	if ( ! trim( $steps ) ) {
		return '';
	}

	$head = $atts['title'] ? trg_section_head( array(
		'eyebrow' => $atts['eyebrow'],
		'title'   => $atts['title'],
		'body'    => $atts['body'],
	) ) : '';

	// Both widths are in the Tailwind safelist: these classes only ever appear
	// in page content stored in the database, which the CSS build cannot scan.
	$grid = '4' === (string) $atts['columns'] ? 'sm:grid-cols-2 md:grid-cols-4' : 'md:grid-cols-3';

	return sprintf(
		'<section class="section %s"><div class="shell">%s<ol class="%s grid gap-6 %s">%s</ol></div></section>',
		'white' === $atts['bg'] ? 'bg-white' : 'bg-canvas',
		$head,
		$head ? 'mt-12' : '',
		esc_attr( $grid ),
		$steps
	);
}
add_shortcode( 'trg_process', 'trg_sc_process' );

/**
 * One numbered step.
 *
 * @param array  $atts    Attributes.
 * @param string $content Step body.
 * @return string
 */
function trg_sc_step( $atts, $content = '' ) {
	$atts = shortcode_atts( array( 'n' => '', 'title' => '' ), $atts, 'trg_step' );

	return '<li class="text-center">'
		. '<span class="mx-auto flex h-11 w-11 items-center justify-center rounded-full border-2 border-brand-200 bg-white font-display text-[16px] font-extrabold text-brand-600">'
		. esc_html( $atts['n'] ) . '</span>'
		. '<h3 class="mt-4 text-[18px]">' . esc_html( $atts['title'] ) . '</h3>'
		. '<p class="mx-auto mt-2 max-w-xs text-[15px] leading-relaxed text-muted">' . esc_html( trim( wp_strip_all_tags( $content ) ) ) . '</p>'
		. '</li>';
}
add_shortcode( 'trg_step', 'trg_sc_step' );

/**
 * The dark AI panel from the homepage.
 *
 * @param array $atts Attributes.
 * @return string
 */
function trg_sc_ai_panel( $atts ) {
	$atts = shortcode_atts( array(
		'eyebrow'     => '',
		'title'       => '',
		'accent'      => '',
		'body'        => '',
		'pills'       => '',
		'button_text' => '',
		'button_link' => '',
		'panel_label' => 'TRG / AI enablement',
		'steps'       => '',
		'bg'          => 'canvas',
	), $atts, 'trg_ai_panel' );

	$steps = trg_split_list( $atts['steps'], ';' );

	ob_start();
	?>
	<?php
	// bg="navy" turns the whole band dark, which is how test2 renders its Azure
	// section. Same component, because the two bands are the same shape.
	$dark = 'navy' === $atts['bg'];
	?>
	<section class="section <?php echo $dark ? 'relative overflow-hidden bg-navy text-white' : ( 'white' === $atts['bg'] ? 'bg-white' : 'bg-canvas' ); ?>">
		<?php if ( $dark ) : ?>
			<div class="dotted-field pointer-events-none absolute inset-0 opacity-[0.12]" aria-hidden="true"></div>
		<?php endif; ?>
		<div class="shell relative grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
			<div>
				<div class="max-w-2xl">
					<?php if ( $atts['eyebrow'] ) : ?>
						<span class="eyebrow <?php echo $dark ? 'text-white/70' : ''; ?>"><?php echo esc_html( $atts['eyebrow'] ); ?></span>
					<?php endif; ?>
					<h2 class="mt-4 text-[30px] leading-[1.15] sm:text-[38px] <?php echo $dark ? 'text-white' : ''; ?>">
						<?php echo esc_html( $atts['title'] ); ?>
						<?php if ( $atts['accent'] ) : ?>
							<br><span class="<?php echo $dark ? 'text-brand-300' : 'text-brand-600'; ?>"><?php echo esc_html( $atts['accent'] ); ?></span>
						<?php endif; ?>
					</h2>
					<?php if ( $atts['body'] ) : ?>
						<p class="mt-4 text-[17px] leading-relaxed <?php echo $dark ? 'text-white/75' : 'text-muted'; ?>"><?php echo esc_html( $atts['body'] ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( $atts['pills'] ) : ?>
					<?php
					// Calling the pills renderer directly rather than round-tripping
					// through do_shortcode(): esc_attr() would turn "POA&M" into
					// "POA&amp;M", which the shortcode parser stores literally and
					// the renderer then escapes again, printing "&amp;" on screen.
					?>
					<div class="mt-7"><?php echo trg_sc_pills( array( 'items' => $atts['pills'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside. ?></div>
				<?php endif; ?>

				<?php if ( $atts['button_text'] ) : ?>
					<a href="<?php echo esc_url( trg_link_url( $atts['button_link'] ) ); ?>" class="<?php echo $dark ? 'btn-white' : 'btn-primary'; ?> mt-8">
						<?php echo esc_html( $atts['button_text'] ); ?>
						<?php echo trg_site_icon( 'arrow-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				<?php endif; ?>
			</div>

			<div class="relative overflow-hidden rounded-2xl <?php echo $dark ? 'border border-white/15 bg-white/[0.06]' : 'bg-ink'; ?> p-6 sm:p-7">
				<?php if ( ! $dark ) : ?>
					<div class="pointer-events-none absolute inset-0" style="background:radial-gradient(ellipse 70% 60% at 80% 10%,rgba(14,92,175,0.40) 0%,transparent 70%)" aria-hidden="true"></div>
				<?php endif; ?>
				<div class="relative">
					<div class="flex items-center justify-between border-b border-white/10 pb-4">
						<span class="font-heading text-[10.5px] font-bold uppercase tracking-[0.16em] text-white/55"><?php echo esc_html( $atts['panel_label'] ); ?></span>
						<span class="h-2 w-2 rounded-full bg-emerald-400" aria-hidden="true"></span>
					</div>
					<ul class="divide-y divide-white/10">
						<?php foreach ( $steps as $i => $step ) : ?>
							<?php $parts = array_pad( trg_split_list( $step, '|' ), 2, '' ); ?>
							<li class="flex items-center gap-4 py-4">
								<span class="font-display text-[13px] font-bold text-white/40"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
								<span class="min-w-0 flex-1">
									<span class="block font-heading text-[15px] font-bold text-white"><?php echo esc_html( $parts[0] ); ?></span>
									<span class="block text-[13px] text-white/55"><?php echo esc_html( $parts[1] ); ?></span>
								</span>
								<span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-white/10 text-white/70" aria-hidden="true">
									<?php echo trg_site_icon( 'check', 13 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								</span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_ai_panel', 'trg_sc_ai_panel' );

/**
 * "Keep exploring" cards — sibling service or industry pages.
 *
 * @param array $atts Attributes.
 * @return string
 */
function trg_sc_related( $atts ) {
	$atts = shortcode_atts( array(
		'type'    => 'service',
		'eyebrow' => __( 'Keep exploring', 'trg-site' ),
		'title'   => '',
		'limit'   => '3',
	), $atts, 'trg_related' );

	$post_type = 'industry' === $atts['type'] ? 'trg_industry' : 'trg_service';
	$title     = $atts['title'] ? $atts['title'] : ( 'industry' === $atts['type']
		? __( 'Other industries we serve', 'trg-site' )
		: __( 'Other ways TRG helps', 'trg-site' ) );

	// Leave out the card that points at the page we are already on, so the
	// section never offers a link back to itself.
	$current  = get_the_ID();
	$cards    = trg_get_cards( $post_type );
	$filtered = array();
	foreach ( $cards as $card ) {
		if ( (int) get_post_meta( $card->ID, '_trg_link', true ) === (int) $current ) {
			continue;
		}
		$filtered[] = $card;
		if ( count( $filtered ) >= (int) $atts['limit'] ) {
			break;
		}
	}
	if ( ! $filtered ) {
		return '';
	}

	ob_start();
	?>
	<section class="section bg-white">
		<div class="shell">
			<?php echo trg_section_head( array( 'eyebrow' => $atts['eyebrow'], 'title' => $title ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div class="mt-10 grid gap-5 sm:grid-cols-3">
				<?php foreach ( $filtered as $card ) : ?>
					<?php $url = trg_card_link( $card ); ?>
					<<?php echo $url ? 'a href="' . esc_url( $url ) . '"' : 'div'; ?> class="card-hover group">
						<h3 class="text-[17px] group-hover:text-brand-600"><?php echo esc_html( get_the_title( $card ) ); ?></h3>
						<p class="mt-2 text-[14.5px] leading-relaxed text-muted"><?php echo esc_html( trg_card_body( $card ) ); ?></p>
						<?php if ( $url ) : ?>
							<span class="mt-4 inline-flex items-center gap-1.5 font-heading text-[13px] font-bold text-brand-600">
								<?php esc_html_e( 'Learn more', 'trg-site' ); ?>
								<?php echo trg_site_icon( 'arrow-right', 13, 'transition-transform group-hover:translate-x-1' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</span>
						<?php endif; ?>
					</<?php echo $url ? 'a' : 'div'; ?>>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_related', 'trg_sc_related' );
