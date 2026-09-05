<?php
/**
 * The leadership team on the About page.
 *
 * The biographies here are TRG's own, taken word for word from their Hostinger
 * site. The photographs are a different matter: of the six that site carries,
 * two are genuine photographs of the person named, two are stock-library
 * pictures of models who are not the employee, and two are screenshots of an
 * app avatar (88x87 pixels, one still wearing its status badge).
 *
 * So only the two real ones ship. The other four render as an initials
 * monogram, which is a deliberate design, reads as intentional, and is honest.
 * Publishing a stock model's face under a real person's name and credentials is
 * the same category of problem as the invented testimonials already removed
 * from this site, and worse, because it attaches a stranger to a named
 * individual's professional reputation.
 *
 * Every one of the six has a picture slot in Settings -> TRG Pictures, so a real
 * headshot replaces the monogram the moment TRG supplies one. Nothing here has
 * to be edited for that to happen.
 *
 * @package TRG_Site
 */

defined( 'ABSPATH' ) || exit;

/**
 * The team, in the order TRG lists them.
 *
 * 'photo' is true only where we hold a real photograph of that person.
 *
 * @return array<int,array<string,mixed>>
 */
function trg_team_members() {
	return array(
		array(
			'slug'  => 'madhuri-edwards',
			'name'  => 'Dr. Madhuri Edwards',
			'title' => 'President & CEO',
			'photo' => true,
			'bio'   => 'Dr. Madhuri Edwards provides executive leadership and strategic direction for TRG Networking, bringing decades of experience in technology, audit, governance, risk management, and organizational leadership. A former federal Internal Audit Executive and Risk Management Director with the National Weather Service, she brings a disciplined approach to operational excellence, cybersecurity, compliance, and enterprise risk. At TRG, she leads the company\'s strategic vision and commitment to delivering secure, reliable, and business-focused technology solutions.',
		),
		array(
			'slug'  => 'charles-edwards',
			'name'  => 'Dr. Charles Edwards',
			'title' => 'COO & Chief Technology Officer',
			'photo' => true,
			'bio'   => 'Dr. Charles Edwards leads TRG Networking\'s technology strategy, cybersecurity initiatives, and technical operations. With extensive experience spanning managed IT, cybersecurity, cloud technologies, systems engineering, compliance, and enterprise architecture, he helps translate complex technology challenges into practical business solutions. He also brings decades of higher-education experience teaching cybersecurity, cyber forensics, programming, networking, and operating systems. At TRG, Dr. Edwards provides executive and technical leadership across cybersecurity, Microsoft cloud, CMMC and compliance, infrastructure modernization, and emerging technologies.',
		),
		array(
			'slug'  => 'kandra-clifton',
			'name'  => 'Kandra Clifton',
			'title' => 'Vice President, Operations',
			'photo' => false,
			'bio'   => 'Kandra Clifton oversees TRG Networking\'s day-to-day business operations and has been an integral part of the company since 1997. With nearly three decades of organizational knowledge and client experience, she provides continuity across TRG\'s operational and administrative functions. Her responsibilities include contract administration and management, client invoicing, operational coordination, and supporting the successful delivery of customer engagements. Kandra works closely with TRG\'s executive, technical, and project teams to ensure that contracts, client requirements, billing, and business operations remain organized and responsive.',
		),
		array(
			'slug'  => 'marybeth-frank',
			'name'  => 'MaryBeth Frank',
			'title' => 'Procurement Specialist & Manager, Vendor Administration',
			'photo' => false,
			'bio'   => 'MaryBeth Frank manages TRG Networking\'s procurement and vendor administration activities. She coordinates technology purchasing, vendor relationships, product sourcing, licensing, order management, and procurement support for client projects and ongoing IT operations. Working closely with TRG\'s engineering, operations, and project teams, MaryBeth helps ensure that the hardware, software, licensing, and technology resources required by clients are sourced efficiently and administered effectively.',
		),
		array(
			'slug'  => 'frank-guntia',
			'name'  => 'Frank Guntia',
			'title' => 'Senior Engineer & Project Manager',
			'photo' => false,
			'bio'   => 'Frank Guntia serves as one of TRG Networking\'s senior technical leaders, combining advanced engineering expertise with hands-on project management. He plays a central role in designing, troubleshooting, and implementing complex client technology environments and is a key technical resource behind many of TRG\'s solutions. From infrastructure modernization and cloud initiatives to networking, security, migrations, and complex technical projects, Frank helps turn solution designs into reliable production environments while coordinating projects from planning through successful implementation.',
		),
		array(
			'slug'  => 'derek-aquino',
			'name'  => 'Derek Aquino',
			'title' => 'Network Engineer',
			'photo' => false,
			'bio'   => 'Derek Aquino supports the design, implementation, administration, and troubleshooting of TRG Networking\'s client technology environments. Working alongside TRG\'s senior engineering and project teams, he supports network infrastructure, Microsoft technologies, cloud environments, system deployments, security initiatives, and ongoing client operations. His hands-on technical role helps ensure that client systems remain reliable, secure, connected, and responsive to changing business requirements.',
		),
	);
}

/**
 * Initials for the monogram. "Dr." is a title, not a name, so it is skipped —
 * otherwise every doctor on the team would be a "D".
 *
 * @param string $name Full name.
 * @return string
 */
function trg_team_initials( $name ) {
	$parts    = preg_split( '/\s+/', trim( $name ) );
	$initials = '';
	foreach ( $parts as $part ) {
		$part = rtrim( $part, '.' );
		if ( '' === $part || in_array( strtolower( $part ), array( 'dr', 'mr', 'mrs', 'ms' ), true ) ) {
			continue;
		}
		$initials .= strtoupper( substr( $part, 0, 1 ) );
	}
	return substr( $initials, 0, 2 );
}

/**
 * The leadership grid.
 *
 * @param array $atts Attributes.
 * @return string
 */
function trg_sc_team( $atts ) {
	$atts = shortcode_atts( array(
		'eyebrow' => '',
		'title'   => '',
		'body'    => '',
		'bg'      => 'canvas',
	), $atts, 'trg_team' );

	$head = $atts['title'] ? trg_section_head( array(
		'eyebrow' => $atts['eyebrow'],
		'title'   => $atts['title'],
		'body'    => $atts['body'],
	) ) : '';

	ob_start();
	?>
	<section class="section <?php echo 'canvas' === $atts['bg'] ? 'bg-canvas' : 'bg-white'; ?>">
		<div class="shell">
			<?php echo $head; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div class="<?php echo $head ? 'mt-12' : ''; ?> grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
				<?php foreach ( trg_team_members() as $member ) : ?>
					<?php
					// A client-uploaded picture always wins; then the shipped
					// photograph, if we hold a genuine one; then the monogram.
					$src = '';
					if ( function_exists( 'trg_picture_override_url' ) ) {
						$src = trg_picture_override_url( 'team-' . $member['slug'] );
					}
					if ( ! $src && $member['photo'] ) {
						$src = trg_image_url( 'team-' . $member['slug'] . '.webp' );
					}
					?>
					<article class="card-hover flex flex-col">
						<?php if ( $src ) : ?>
							<img src="<?php echo esc_url( $src ); ?>"
								alt="<?php echo esc_attr( $member['name'] ); ?>"
								width="640" height="640" loading="lazy" decoding="async"
								class="h-24 w-24 rounded-full object-cover">
						<?php else : ?>
							<span aria-hidden="true"
								class="flex h-24 w-24 items-center justify-center rounded-full bg-brand-50 font-display text-2xl font-extrabold tracking-tight text-brand-600">
								<?php echo esc_html( trg_team_initials( $member['name'] ) ); ?>
							</span>
						<?php endif; ?>
						<h3 class="mt-5 text-[18px]"><?php echo esc_html( $member['name'] ); ?></h3>
						<p class="mt-1 font-display text-[14px] font-bold text-brand-600"><?php echo esc_html( $member['title'] ); ?></p>
						<p class="mt-3 flex-1 text-[15px] leading-relaxed text-muted"><?php echo esc_html( $member['bio'] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_team', 'trg_sc_team' );
