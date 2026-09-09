<?php
/**
 * The leadership team on the About page, and the screen for editing it.
 *
 * The biographies below are TRG's own, taken word for word from their Hostinger
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
 * Every one of the six has a picture slot in TRG Website -> Pictures, so a real
 * headshot replaces the monogram the moment TRG supplies one. Nothing here has
 * to be edited for that to happen.
 *
 * The words, though, are staff data: a promotion, a new hire, someone leaving.
 * Those change without any developer being involved, so they are editable under
 * TRG Website -> Leadership team. The array below stays as the shipped default,
 * which is what "Put the original text back" restores to.
 *
 * @package TRG_Site
 */

defined( 'ABSPATH' ) || exit;

const TRG_TEAM_OPTION = 'trg_team';

/**
 * The team as it shipped, in the order TRG lists them.
 *
 * 'photo' is true only where we hold a real photograph of that person. It is
 * deliberately not editable: it records what we were given, not a preference.
 *
 * @return array<int,array<string,mixed>>
 */
function trg_team_default_members() {
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
 * The team as it stands now: the shipped list with TRG's own edits applied.
 *
 * Hidden people are still returned. They are filtered out at the point of
 * display, not here, because the picture slots in TRG Pictures are numbered by
 * their position in this list — dropping someone out of it would renumber the
 * slots below them, and those numbers are how photographs get handed over in
 * chat. Taking a person off the About page must not silently change what "27"
 * means.
 *
 * @return array<int,array<string,mixed>>
 */
function trg_team_members() {
	$defaults = trg_team_default_members();
	$shipped  = array();
	foreach ( $defaults as $member ) {
		$shipped[ $member['slug'] ] = $member;
	}

	$saved = get_option( TRG_TEAM_OPTION, null );
	if ( ! is_array( $saved ) || ! $saved ) {
		foreach ( $defaults as $i => $member ) {
			$defaults[ $i ]['hidden'] = false;
		}
		return $defaults;
	}

	$out  = array();
	$seen = array();

	foreach ( $saved as $row ) {
		$slug = isset( $row['slug'] ) ? sanitize_key( $row['slug'] ) : '';
		if ( '' === $slug || isset( $seen[ $slug ] ) ) {
			continue;
		}
		$seen[ $slug ] = true;

		// 'photo' is never read from the saved list. Whether we hold a genuine
		// photograph of someone is a fact about what TRG sent, and no amount of
		// editing their job title changes it.
		$out[] = array(
			'slug'   => $slug,
			'name'   => isset( $row['name'] ) ? (string) $row['name'] : '',
			'title'  => isset( $row['title'] ) ? (string) $row['title'] : '',
			'bio'    => isset( $row['bio'] ) ? (string) $row['bio'] : '',
			'photo'  => ! empty( $shipped[ $slug ]['photo'] ),
			'hidden' => ! empty( $row['hidden'] ),
		);
	}

	// Anyone shipped but absent from the saved list is appended rather than
	// dropped, so a person added to the default list in a later release still
	// appears on a site that has already been edited. Hiding someone writes a
	// row with hidden => true, so this cannot resurrect a person taken down.
	foreach ( $defaults as $member ) {
		if ( ! isset( $seen[ $member['slug'] ] ) ) {
			$member['hidden'] = false;
			$out[]            = $member;
		}
	}

	return $out;
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

	$members = array();
	foreach ( trg_team_members() as $member ) {
		if ( empty( $member['hidden'] ) && '' !== trim( $member['name'] ) ) {
			$members[] = $member;
		}
	}

	ob_start();
	?>
	<section class="section <?php echo 'canvas' === $atts['bg'] ? 'bg-canvas' : 'bg-white'; ?>">
		<div class="shell">
			<?php echo $head; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<div class="<?php echo $head ? 'mt-12' : ''; ?> grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
				<?php foreach ( $members as $member ) : ?>
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
						<h3 class="mt-5 text-[17px]"><?php echo esc_html( $member['name'] ); ?></h3>
						<?php if ( '' !== trim( $member['title'] ) ) : ?>
							<p class="mt-1 font-display text-[14px] font-bold text-brand-600"><?php echo esc_html( $member['title'] ); ?></p>
						<?php endif; ?>
						<?php /* nl2br so a blank line typed in the editor becomes a break on the
						         page. Without it a two-paragraph bio runs together into one
						         block and reads as a fault in the editing screen. */ ?>
						<p class="mt-3 flex-1 text-[15px] leading-relaxed text-muted"><?php echo nl2br( esc_html( $member['bio'] ) ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_team', 'trg_sc_team' );

/**
 * Admin menu entry.
 *
 * edit_pages rather than manage_options: this is page content — names, titles
 * and biographies — so anyone trusted to edit the About page is trusted to edit
 * the people on it.
 */
function trg_team_menu() {
	add_submenu_page(
		TRG_HUB_SLUG,
		__( 'Leadership team', 'trg-site' ),
		__( 'Leadership team', 'trg-site' ),
		'edit_pages',
		'trg-team',
		'trg_team_page'
	);
}
add_action( 'admin_menu', 'trg_team_menu' );

/**
 * A slug that will not collide with one already in use.
 *
 * A person's slug is set once, when they are added, and never regenerated from
 * their name afterwards. It is what ties them to their picture slot and to the
 * headshot already uploaded there, so a correction to a spelling, a married
 * name or a new doctorate must not quietly orphan their photograph.
 *
 * @param string $name  The person's name.
 * @param array  $taken Slugs already in use.
 * @return string
 */
function trg_team_new_slug( $name, $taken ) {
	$base = sanitize_title( $name );
	if ( '' === $base ) {
		$base = 'team-member';
	}
	$slug = $base;
	$n    = 2;
	while ( in_array( $slug, $taken, true ) ) {
		$slug = $base . '-' . $n;
		++$n;
	}
	return $slug;
}

/**
 * Save, add, remove, reset — then render the screen.
 */
function trg_team_page() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		wp_die( esc_html__( 'You do not have permission to edit the leadership team.', 'trg-site' ) );
	}

	$notices = array();
	$shipped = wp_list_pluck( trg_team_default_members(), 'slug' );

	if ( isset( $_POST['trg_team_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['trg_team_nonce'] ) ), 'trg_team' ) ) {

		if ( isset( $_POST['reset'] ) ) {
			delete_option( TRG_TEAM_OPTION );
			$notices[] = array( 'updated', __( 'Put the original text back. Photographs you uploaded are untouched.', 'trg-site' ) );
		} else {
			// Start from the list as it stands, so a row the form did not send
			// (a stale tab, a person added in another window) is not silently
			// deleted by someone else's save.
			$current = array();
			foreach ( trg_team_members() as $member ) {
				$current[ $member['slug'] ] = $member;
			}

			$posted = isset( $_POST['member'] ) && is_array( $_POST['member'] ) ? wp_unslash( $_POST['member'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			$order = array();
			foreach ( $posted as $slug => $row ) {
				$slug = sanitize_key( $slug );
				if ( '' === $slug || ! isset( $current[ $slug ] ) ) {
					continue;
				}
				$current[ $slug ]['name']   = sanitize_text_field( isset( $row['name'] ) ? $row['name'] : '' );
				$current[ $slug ]['title']  = sanitize_text_field( isset( $row['title'] ) ? $row['title'] : '' );
				$current[ $slug ]['bio']    = sanitize_textarea_field( isset( $row['bio'] ) ? $row['bio'] : '' );
				$current[ $slug ]['hidden'] = empty( $row['show'] );
				$order[ $slug ]             = isset( $row['order'] ) ? (float) $row['order'] : 0;
			}

			$remove = isset( $_POST['remove'] ) ? sanitize_key( wp_unslash( $_POST['remove'] ) ) : '';
			if ( $remove && isset( $current[ $remove ] ) && ! in_array( $remove, $shipped, true ) ) {
				unset( $current[ $remove ], $order[ $remove ] );
				$notices[] = array( 'updated', __( 'Removed.', 'trg-site' ) );
			}

			$add_name = isset( $_POST['add_name'] ) ? sanitize_text_field( wp_unslash( $_POST['add_name'] ) ) : '';
			if ( '' !== $add_name ) {
				$slug             = trg_team_new_slug( $add_name, array_keys( $current ) );
				$current[ $slug ] = array(
					'slug'   => $slug,
					'name'   => $add_name,
					'title'  => isset( $_POST['add_title'] ) ? sanitize_text_field( wp_unslash( $_POST['add_title'] ) ) : '',
					'bio'    => '',
					'photo'  => false,
					'hidden' => false,
				);
				// Last, until he reorders. max() of an empty list is a warning,
				// hence the guard.
				$order[ $slug ] = $order ? max( $order ) + 1 : 1;
				$notices[]      = array(
					'updated',
					sprintf(
						/* translators: %s: person's name. */
						__( 'Added %s. They will show initials until you upload a photograph under Pictures.', 'trg-site' ),
						$add_name
					),
				);
			}

			// Sort by the order boxes, keeping anything the form did not carry an
			// order for in its existing position rather than jumping it to the top.
			$i    = 0;
			$sort = array();
			foreach ( $current as $slug => $member ) {
				$sort[] = array(
					'pos'    => isset( $order[ $slug ] ) ? $order[ $slug ] : $i,
					'seq'    => $i,
					'member' => $member,
				);
				++$i;
			}
			usort(
				$sort,
				static function ( $a, $b ) {
					if ( $a['pos'] === $b['pos'] ) {
						return $a['seq'] <=> $b['seq'];
					}
					return $a['pos'] <=> $b['pos'];
				}
			);

			$save = array();
			foreach ( $sort as $entry ) {
				$save[] = array(
					'slug'   => $entry['member']['slug'],
					'name'   => $entry['member']['name'],
					'title'  => $entry['member']['title'],
					'bio'    => $entry['member']['bio'],
					'hidden' => ! empty( $entry['member']['hidden'] ),
				);
			}
			update_option( TRG_TEAM_OPTION, $save );

			if ( ! $notices ) {
				$notices[] = array( 'updated', __( 'Saved. Open the About page to see it.', 'trg-site' ) );
			}
		}
	}

	$members  = trg_team_members();
	$about    = get_page_by_path( 'about' );
	$about_url = $about ? get_permalink( $about ) : home_url( '/about/' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Leadership team', 'trg-site' ); ?></h1>
		<p style="max-width:52em">
			<?php esc_html_e( 'The people shown on the About page. Change a job title, correct a name, rewrite a biography, add someone who has joined, or take someone off the site — all from here. Nothing on this screen can affect the design or any other page.', 'trg-site' ); ?>
		</p>
		<p style="max-width:52em">
			<?php
			printf(
				/* translators: %s: link to the Pictures screen. */
				esc_html__( 'Photographs are not on this screen. They live under %s, one numbered slot per person, and they stay attached to the person even if you change their name here.', 'trg-site' ),
				'<a href="' . esc_url( admin_url( 'admin.php?page=trg-pictures' ) ) . '">' . esc_html__( 'Pictures', 'trg-site' ) . '</a>'
			);
			?>
		</p>

		<?php foreach ( $notices as $notice ) : ?>
			<div class="<?php echo esc_attr( 'error' === $notice[0] ? 'notice notice-error' : 'notice notice-success' ); ?>"><p><?php echo esc_html( $notice[1] ); ?></p></div>
		<?php endforeach; ?>

		<form method="post">
			<?php wp_nonce_field( 'trg_team', 'trg_team_nonce' ); ?>

			<table class="widefat striped" style="max-width:74em;margin-top:1em">
				<thead>
					<tr>
						<th style="width:5em"><?php esc_html_e( 'Order', 'trg-site' ); ?></th>
						<th style="width:22em"><?php esc_html_e( 'Name and job title', 'trg-site' ); ?></th>
						<th><?php esc_html_e( 'Biography', 'trg-site' ); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ( $members as $i => $member ) : ?>
					<?php $is_shipped = in_array( $member['slug'], $shipped, true ); ?>
					<tr>
						<td>
							<input type="number" step="1" min="0"
								name="member[<?php echo esc_attr( $member['slug'] ); ?>][order]"
								value="<?php echo esc_attr( $i + 1 ); ?>"
								style="width:4.5em">
						</td>
						<td>
							<p style="margin:0 0 .5em">
								<input type="text" class="large-text"
									name="member[<?php echo esc_attr( $member['slug'] ); ?>][name]"
									value="<?php echo esc_attr( $member['name'] ); ?>"
									placeholder="<?php esc_attr_e( 'Name', 'trg-site' ); ?>">
							</p>
							<p style="margin:0 0 .6em">
								<input type="text" class="large-text"
									name="member[<?php echo esc_attr( $member['slug'] ); ?>][title]"
									value="<?php echo esc_attr( $member['title'] ); ?>"
									placeholder="<?php esc_attr_e( 'Job title', 'trg-site' ); ?>">
							</p>
							<p style="margin:0">
								<label>
									<input type="checkbox" value="1"
										name="member[<?php echo esc_attr( $member['slug'] ); ?>][show]"
										<?php checked( empty( $member['hidden'] ) ); ?>>
									<?php esc_html_e( 'Show on the About page', 'trg-site' ); ?>
								</label>
							</p>
							<p style="margin:.5em 0 0;color:#646970;font-size:12px">
								<?php
								if ( $member['photo'] || ( function_exists( 'trg_picture_override_url' ) && trg_picture_override_url( 'team-' . $member['slug'] ) ) ) {
									esc_html_e( 'Has a photograph.', 'trg-site' );
								} else {
									esc_html_e( 'Showing initials — no photograph yet.', 'trg-site' );
								}
								?>
							</p>
							<?php if ( ! $is_shipped ) : ?>
								<p style="margin:.6em 0 0">
									<button type="submit" name="remove" value="<?php echo esc_attr( $member['slug'] ); ?>"
										class="button-link" style="color:#b32d2e"
										onclick="return confirm('<?php echo esc_js( __( 'Remove this person from the site?', 'trg-site' ) ); ?>')">
										<?php esc_html_e( 'Remove', 'trg-site' ); ?>
									</button>
								</p>
							<?php endif; ?>
						</td>
						<td>
							<textarea rows="7" class="large-text"
								name="member[<?php echo esc_attr( $member['slug'] ); ?>][bio]"><?php echo esc_textarea( $member['bio'] ); ?></textarea>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<h2 style="margin-top:2em"><?php esc_html_e( 'Add someone', 'trg-site' ); ?></h2>
			<p style="max-width:52em">
				<?php esc_html_e( 'Fill these in and save. The new person appears at the bottom, showing their initials, and a picture slot for them appears under Pictures. You can write their biography and move them up on the next save.', 'trg-site' ); ?>
			</p>
			<p>
				<input type="text" name="add_name" class="regular-text" placeholder="<?php esc_attr_e( 'Name', 'trg-site' ); ?>">
				<input type="text" name="add_title" class="regular-text" placeholder="<?php esc_attr_e( 'Job title', 'trg-site' ); ?>">
			</p>

			<p style="margin-top:1.6em">
				<button type="submit" class="button button-primary button-large"><?php esc_html_e( 'Save', 'trg-site' ); ?></button>
				<a class="button" href="<?php echo esc_url( $about_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'View the About page', 'trg-site' ); ?></a>
			</p>
		</form>

		<h2 style="margin-top:2em"><?php esc_html_e( 'Start again', 'trg-site' ); ?></h2>
		<div style="max-width:52em">
			<p>
				<?php esc_html_e( 'This puts every name, job title and biography back to the words the site was built with. It does not touch any photograph you have uploaded, and anyone you added is removed.', 'trg-site' ); ?>
			</p>
			<form method="post">
				<?php wp_nonce_field( 'trg_team', 'trg_team_nonce' ); ?>
				<button type="submit" name="reset" value="1" class="button"
					onclick="return confirm('<?php echo esc_js( __( 'Put all the original wording back?', 'trg-site' ) ); ?>')">
					<?php esc_html_e( 'Put the original text back', 'trg-site' ); ?>
				</button>
			</form>
		</div>
	</div>
	<?php
}
