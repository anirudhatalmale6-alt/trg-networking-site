<?php
/**
 * The remaining page sections: contact details, the support centre cards, the
 * split "heading + tick list" band and the plain note panel.
 *
 * @package TRG_Site
 */

defined( 'ABSPATH' ) || exit;

/**
 * Contact details column: call, email, visit, existing clients.
 *
 * @param array $atts Attributes.
 * @return string
 */
function trg_sc_contact_details( $atts ) {
	$atts = shortcode_atts( array(
		'title'   => '',
		'body'    => '',
		'support' => '1',
	), $atts, 'trg_contact_details' );

	$rows = array(
		array(
			'icon'  => 'phone',
			'label' => __( 'Call', 'trg-site' ),
			'value' => trg_site_company( 'phone' ),
			'href'  => trg_site_phone_href(),
		),
		array(
			'icon'  => 'mail',
			'label' => __( 'Email', 'trg-site' ),
			'value' => trg_site_company( 'email' ),
			'href'  => 'mailto:' . trg_site_company( 'email' ),
			'break' => true,
		),
		array(
			'icon'  => 'map-pin',
			'label' => __( 'Visit', 'trg-site' ),
			'value' => function_exists( 'trg_address_line' ) ? trg_address_line() : '',
			'href'  => '',
		),
	);

	ob_start();
	?>
	<div>
		<?php if ( $atts['title'] ) : ?>
			<?php echo trg_section_head( array( 'title' => $atts['title'], 'body' => $atts['body'], 'align' => 'left' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<?php endif; ?>

		<ul class="<?php echo $atts['title'] ? 'mt-9' : ''; ?> space-y-4">
			<?php foreach ( $rows as $row ) : ?>
				<?php if ( ! $row['value'] ) { continue; } ?>
				<li class="flex items-start gap-4 rounded-xl border border-line bg-canvas p-5">
					<span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600" aria-hidden="true">
						<?php echo trg_site_icon( $row['icon'], 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</span>
					<span class="min-w-0">
						<span class="block font-heading text-[15px] font-bold text-ink"><?php echo esc_html( $row['label'] ); ?></span>
						<?php if ( $row['href'] ) : ?>
							<a href="<?php echo esc_url( $row['href'] ); ?>" class="<?php echo empty( $row['break'] ) ? '' : 'break-all '; ?>text-[15px] text-brand-600 hover:underline"><?php echo esc_html( $row['value'] ); ?></a>
						<?php else : ?>
							<span class="text-[15px] text-muted"><?php echo esc_html( $row['value'] ); ?></span>
						<?php endif; ?>
					</span>
				</li>
			<?php endforeach; ?>

			<?php if ( $atts['support'] ) : ?>
				<li class="flex items-start gap-4 rounded-xl border border-brand-200 bg-brand-50 p-5">
					<span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white text-brand-600" aria-hidden="true">
						<?php echo trg_site_icon( 'external-link', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</span>
					<span class="min-w-0">
						<span class="block font-heading text-[15px] font-bold text-ink"><?php esc_html_e( 'Existing clients', 'trg-site' ); ?></span>
						<span class="text-[14.5px] text-muted">
							<?php
							printf(
								/* translators: %s: link to the support centre page. */
								esc_html__( 'Please use the %s for active technical requests.', 'trg-site' ),
								'<a href="' . esc_url( trg_site_page_url( 'support-center' ) ) . '" class="font-semibold text-brand-600 hover:underline">' . esc_html__( 'Support Center', 'trg-site' ) . '</a>'
							);
							?>
						</span>
					</span>
				</li>
			<?php endif; ?>
		</ul>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_contact_details', 'trg_sc_contact_details' );

/**
 * The contact page band: details on the left, form on the right.
 *
 * @param array $atts Attributes.
 * @return string
 */
function trg_sc_contact_section( $atts ) {
	$atts = shortcode_atts( array(
		'title' => '',
		'body'  => '',
	), $atts, 'trg_contact_section' );

	return '<section class="section bg-white"><div class="shell grid gap-10 lg:grid-cols-[1fr_1.1fr] lg:gap-14">'
		. do_shortcode( sprintf( '[trg_contact_details title="%s" body="%s"]', esc_attr( $atts['title'] ), esc_attr( $atts['body'] ) ) )
		. '<div>' . do_shortcode( '[trg_contact_form]' ) . '</div>'
		. '</div></section>';
}
add_shortcode( 'trg_contact_section', 'trg_sc_contact_section' );

/**
 * Support centre: two contact cards, a panel for non-clients and the hours note.
 *
 * @param array $atts Attributes.
 * @return string
 */
function trg_sc_support_cards( $atts ) {
	$atts = shortcode_atts( array(
		'panel_title'  => __( 'Not an existing client?', 'trg-site' ),
		'panel_body'   => __( 'If you are enquiring about services rather than raising a technical issue, the contact page will get you to the right person faster.', 'trg-site' ),
		'panel_button' => __( 'Go to the contact page', 'trg-site' ),
		'note'         => __( 'Support is available 24×7. For urgent issues affecting your business operations, please call rather than email — it reaches the team fastest.', 'trg-site' ),
	), $atts, 'trg_support_cards' );

	ob_start();
	?>
	<section class="section bg-white">
		<div class="shell max-w-3xl">
			<div class="grid gap-5 sm:grid-cols-2">
				<a href="<?php echo esc_url( trg_site_phone_href() ); ?>" class="card-hover group flex items-start gap-4">
					<span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600" aria-hidden="true">
						<?php echo trg_site_icon( 'phone', 19 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</span>
					<span class="min-w-0">
						<span class="block font-heading text-[12px] font-bold uppercase tracking-[0.12em] text-soft"><?php esc_html_e( 'Phone support', 'trg-site' ); ?></span>
						<span class="mt-1 block font-heading text-[19px] font-extrabold text-ink group-hover:text-brand-600"><?php echo esc_html( trg_site_company( 'phone' ) ); ?></span>
					</span>
				</a>

				<a href="mailto:<?php echo esc_attr( trg_site_company( 'email' ) ); ?>" class="card-hover group flex items-start gap-4">
					<span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600" aria-hidden="true">
						<?php echo trg_site_icon( 'mail', 19 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</span>
					<span class="min-w-0">
						<span class="block font-heading text-[12px] font-bold uppercase tracking-[0.12em] text-soft"><?php esc_html_e( 'Email support', 'trg-site' ); ?></span>
						<span class="mt-1 block break-all font-heading text-[15px] font-extrabold text-ink group-hover:text-brand-600"><?php echo esc_html( trg_site_company( 'email' ) ); ?></span>
					</span>
				</a>
			</div>

			<div class="mt-8 rounded-xl border border-brand-200 bg-brand-50 p-6">
				<h2 class="text-[18px]"><?php echo esc_html( $atts['panel_title'] ); ?></h2>
				<p class="mt-2 text-[15px] leading-relaxed text-muted"><?php echo esc_html( $atts['panel_body'] ); ?></p>
				<a href="<?php echo esc_url( trg_site_page_url( 'contact' ) ); ?>" class="btn-primary mt-5">
					<?php echo esc_html( $atts['panel_button'] ); ?>
					<?php echo trg_site_icon( 'arrow-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</a>
			</div>

			<?php if ( $atts['note'] ) : ?>
				<p class="mt-8 text-[14px] leading-relaxed text-soft"><?php echo esc_html( $atts['note'] ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_support_cards', 'trg_sc_support_cards' );

/**
 * Heading on one side, a grid of ticked points on the other.
 *
 * @param array $atts Attributes.
 * @return string
 */
function trg_sc_split_points( $atts ) {
	$atts = shortcode_atts( array(
		'id'      => '',
		'eyebrow' => '',
		'title'   => '',
		'body'    => '',
		'points'  => '',
		'bg'      => 'canvas',
	), $atts, 'trg_split_points' );

	$points = trg_split_list( $atts['points'], '|' );

	ob_start();
	?>
	<section <?php echo $atts['id'] ? 'id="' . esc_attr( $atts['id'] ) . '"' : ''; ?> class="section <?php echo 'white' === $atts['bg'] ? 'bg-white' : 'bg-canvas'; ?>">
		<div class="shell grid gap-10 lg:grid-cols-[1fr_1.2fr] lg:gap-14">
			<?php
			echo trg_section_head( array( // phpcs:ignore WordPress.Security.EscapeOutput
				'eyebrow' => $atts['eyebrow'],
				'title'   => $atts['title'],
				'body'    => $atts['body'],
				'align'   => 'left',
			) );
			?>
			<?php if ( $points ) : ?>
				<ul class="grid gap-3 sm:grid-cols-2">
					<?php foreach ( $points as $point ) : ?>
						<li class="flex items-start gap-3 rounded-xl border border-line bg-white p-4">
							<?php echo trg_site_icon( 'check', 16, 'mt-0.5 shrink-0 text-brand-600' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span class="text-[14.5px] leading-relaxed text-body"><?php echo esc_html( $point ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_split_points', 'trg_sc_split_points' );

/**
 * A plain bordered note with an optional button. Used where something is
 * honestly not ready yet.
 *
 * @param array  $atts    Attributes.
 * @param string $content Body text.
 * @return string
 */
function trg_sc_note( $atts, $content = '' ) {
	$atts = shortcode_atts( array(
		'title'       => '',
		'button_text' => '',
		'button_link' => 'contact',
		'shell'       => '1',
	), $atts, 'trg_note' );

	$body = trim( wp_strip_all_tags( $content ) );

	$out  = '<div class="rounded-xl border border-line bg-canvas p-6">';
	$out .= '<h2 class="text-[17px]">' . esc_html( $atts['title'] ) . '</h2>';
	$out .= '<p class="mt-2 max-w-2xl text-[15px] leading-relaxed text-muted">' . esc_html( $body ) . '</p>';

	if ( $atts['button_text'] ) {
		$out .= '<a href="' . esc_url( trg_link_url( $atts['button_link'] ) ) . '" class="btn-primary mt-5">'
			. esc_html( $atts['button_text'] ) . trg_site_icon( 'arrow-right', 16 ) . '</a>';
	}

	$out .= '</div>';

	return $atts['shell']
		? '<section class="pb-16 sm:pb-20"><div class="shell">' . $out . '</div></section>'
		: $out;
}
add_shortcode( 'trg_note', 'trg_sc_note' );

/*
 * There is deliberately no "prose wrapper" shortcode here. Long-form pages —
 * privacy, terms, accessibility — simply carry no hero shortcode, and page.php
 * then builds the hero from the page title and excerpt and wraps the content in
 * the readable column itself. An enclosing shortcode that has to span several
 * blocks to wrap them works, but only until someone drags a block outside it.
 */
