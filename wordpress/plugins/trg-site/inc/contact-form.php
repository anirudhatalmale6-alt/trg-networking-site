<?php
/**
 * Contact form.
 *
 * The form on the Hostinger build wrote every enquiry to a hidden table and
 * emailed nobody, while telling the visitor "Message Sent". This one does the
 * opposite in both directions:
 *
 *  - It stores the enquiry first, so a mail outage can never lose a lead.
 *  - It only claims success if wp_mail() actually accepted the message. If mail
 *    fails, the visitor is told plainly and given the phone number, rather than
 *    being shown a confirmation that is not true.
 *
 * It is a normal POST to admin-post.php, so it works with JavaScript disabled.
 *
 * @package TRG_Site
 */

defined( 'ABSPATH' ) || exit;

const TRG_ENQUIRY_POST_TYPE = 'trg_enquiry';

/**
 * Store enquiries as a private post type so they are visible in the dashboard
 * and survive a theme change, an email outage or a plugin update.
 */
function trg_register_enquiry_type() {
	register_post_type( TRG_ENQUIRY_POST_TYPE, array(
		'labels'          => array(
			'name'          => __( 'Enquiries', 'trg-site' ),
			'singular_name' => __( 'Enquiry', 'trg-site' ),
			'menu_name'     => __( 'Enquiries', 'trg-site' ),
			'not_found'     => __( 'No enquiries yet.', 'trg-site' ),
		),
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => true,
		'menu_icon'       => 'dashicons-email-alt',
		'menu_position'   => 24,
		'supports'        => array( 'title' ),
		'capability_type' => 'page',
		'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap'    => true,
		'rewrite'         => false,
		'show_in_rest'    => false,
	) );
}
add_action( 'init', 'trg_register_enquiry_type' );

/**
 * The service options offered in the form's dropdown.
 *
 * @return array<string,string>
 */
function trg_service_options() {
	return apply_filters( 'trg_service_options', array(
		'managed-it'          => __( 'Managed IT Services', 'trg-site' ),
		'help-desk'           => __( 'Help Desk & IT Support', 'trg-site' ),
		'cybersecurity'       => __( 'Cybersecurity', 'trg-site' ),
		'microsoft'           => __( 'Microsoft Solutions (Azure / 365)', 'trg-site' ),
		'cmmc'                => __( 'CMMC Readiness', 'trg-site' ),
		'ai'                  => __( 'Secure AI Adoption', 'trg-site' ),
		'business-continuity' => __( 'Backup & Business Continuity', 'trg-site' ),
		'other'               => __( 'Other / General Inquiry', 'trg-site' ),
	) );
}

/**
 * Render the form.
 *
 * @param array $atts Attributes.
 * @return string
 */
function trg_sc_contact_form( $atts ) {
	$atts = shortcode_atts( array(
		'title'  => __( 'Send us a message', 'trg-site' ),
		'intro'  => __( 'We usually reply the same business day.', 'trg-site' ),
		'button' => __( 'Send message', 'trg-site' ),
	), $atts, 'trg_contact_form' );

	$status  = isset( $_GET['trg_sent'] ) ? sanitize_key( wp_unslash( $_GET['trg_sent'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification -- read-only display flag.
	$old     = get_transient( trg_form_state_key() );
	$old     = is_array( $old ) ? $old : array();
	$options = trg_service_options();

	$preselect = isset( $_GET['type'] ) && 'assessment' === sanitize_key( wp_unslash( $_GET['type'] ) ) ? 'managed-it' : ''; // phpcs:ignore WordPress.Security.NonceVerification

	$value = static function ( $key ) use ( $old ) {
		return isset( $old[ $key ] ) ? $old[ $key ] : '';
	};

	ob_start();
	?>
	<div class="card" id="contact-form">
		<?php if ( 'ok' === $status ) : ?>
			<div class="rounded-xl border border-brand-200 bg-brand-50 p-6">
				<div class="flex items-start gap-3">
					<span class="mt-0.5 text-brand-600" aria-hidden="true"><?php echo trg_site_icon( 'circle-check', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<div>
						<h2 class="text-[19px]"><?php esc_html_e( 'Thank you — your message is on its way.', 'trg-site' ); ?></h2>
						<p class="mt-2 text-[15px] leading-relaxed text-muted">
							<?php
							printf(
								/* translators: %s: phone number. */
								esc_html__( 'A member of the team will get back to you. If it is urgent, call %s and speak to someone now.', 'trg-site' ),
								esc_html( trg_site_company( 'phone' ) )
							);
							?>
						</p>
					</div>
				</div>
			</div>
			<?php return ob_get_clean(); ?>
		<?php endif; ?>

		<h2 class="text-[21px]"><?php echo esc_html( $atts['title'] ); ?></h2>
		<p class="mt-2 text-[15px] text-muted"><?php echo esc_html( $atts['intro'] ); ?></p>

		<?php if ( 'failed' === $status ) : ?>
			<?php // Never a false confirmation: if the mail did not leave, say so and give a route that works. ?>
			<div class="mt-5 rounded-xl border border-amber-300 bg-amber-50 p-4 text-[14.5px] leading-relaxed text-amber-900" role="alert">
				<strong><?php esc_html_e( 'We could not send that message.', 'trg-site' ); ?></strong><br>
				<?php
				printf(
					/* translators: 1: email address, 2: phone number. */
					esc_html__( 'Your details are safe and nothing was lost, but the email did not leave our server. Please email %1$s or call %2$s and we will pick it up straight away.', 'trg-site' ),
					esc_html( trg_site_company( 'email' ) ),
					esc_html( trg_site_company( 'phone' ) )
				);
				?>
			</div>
		<?php elseif ( 'invalid' === $status ) : ?>
			<div class="mt-5 rounded-xl border border-amber-300 bg-amber-50 p-4 text-[14.5px] leading-relaxed text-amber-900" role="alert">
				<?php esc_html_e( 'Please check the highlighted fields — we need a name, a valid email address and a message.', 'trg-site' ); ?>
			</div>
		<?php elseif ( 'throttled' === $status ) : ?>
			<div class="mt-5 rounded-xl border border-amber-300 bg-amber-50 p-4 text-[14.5px] leading-relaxed text-amber-900" role="alert">
				<?php
				printf(
					/* translators: %s: phone number. */
					esc_html__( 'That is a few messages in a short time. Please wait a few minutes and try again, or call %s.', 'trg-site' ),
					esc_html( trg_site_company( 'phone' ) )
				);
				?>
			</div>
		<?php endif; ?>

		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="mt-6 space-y-4">
			<input type="hidden" name="action" value="trg_contact">
			<input type="hidden" name="trg_return" value="<?php echo esc_url( get_permalink() ); ?>">
			<?php wp_nonce_field( 'trg_contact', 'trg_contact_nonce' ); ?>

			<?php // Honeypot. Hidden from people, irresistible to bots that fill every field. ?>
			<div class="hidden" aria-hidden="true">
				<label for="trg-website"><?php esc_html_e( 'Website', 'trg-site' ); ?></label>
				<input type="text" id="trg-website" name="trg_website" tabindex="-1" autocomplete="off" value="">
			</div>

			<div class="grid gap-4 sm:grid-cols-2">
				<div>
					<label for="trg-name" class="mb-1.5 block font-heading text-[13.5px] font-bold text-ink">
						<?php esc_html_e( 'Your name', 'trg-site' ); ?> <span class="text-brand-600" aria-hidden="true">*</span>
					</label>
					<input type="text" id="trg-name" name="trg_name" required autocomplete="name"
						value="<?php echo esc_attr( $value( 'name' ) ); ?>"
						class="w-full rounded-lg border border-line bg-white px-3.5 py-2.5 text-[15px] text-body placeholder:text-soft focus:border-brand-600">
				</div>
				<div>
					<label for="trg-company" class="mb-1.5 block font-heading text-[13.5px] font-bold text-ink"><?php esc_html_e( 'Company', 'trg-site' ); ?></label>
					<input type="text" id="trg-company" name="trg_company" autocomplete="organization"
						value="<?php echo esc_attr( $value( 'company' ) ); ?>"
						class="w-full rounded-lg border border-line bg-white px-3.5 py-2.5 text-[15px] text-body placeholder:text-soft focus:border-brand-600">
				</div>
				<div>
					<label for="trg-email" class="mb-1.5 block font-heading text-[13.5px] font-bold text-ink">
						<?php esc_html_e( 'Email', 'trg-site' ); ?> <span class="text-brand-600" aria-hidden="true">*</span>
					</label>
					<input type="email" id="trg-email" name="trg_email" required autocomplete="email"
						value="<?php echo esc_attr( $value( 'email' ) ); ?>"
						class="w-full rounded-lg border border-line bg-white px-3.5 py-2.5 text-[15px] text-body placeholder:text-soft focus:border-brand-600">
				</div>
				<div>
					<label for="trg-phone" class="mb-1.5 block font-heading text-[13.5px] font-bold text-ink"><?php esc_html_e( 'Phone', 'trg-site' ); ?></label>
					<input type="tel" id="trg-phone" name="trg_phone" autocomplete="tel"
						value="<?php echo esc_attr( $value( 'phone' ) ); ?>"
						class="w-full rounded-lg border border-line bg-white px-3.5 py-2.5 text-[15px] text-body placeholder:text-soft focus:border-brand-600">
				</div>
			</div>

			<div>
				<label for="trg-service" class="mb-1.5 block font-heading text-[13.5px] font-bold text-ink"><?php esc_html_e( 'What can we help with?', 'trg-site' ); ?></label>
				<select id="trg-service" name="trg_service"
					class="w-full rounded-lg border border-line bg-white px-3.5 py-2.5 text-[15px] text-body focus:border-brand-600">
					<?php foreach ( $options as $key => $label ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $value( 'service' ) ? $value( 'service' ) : $preselect, $key ); ?>>
							<?php echo esc_html( $label ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<div>
				<label for="trg-message" class="mb-1.5 block font-heading text-[13.5px] font-bold text-ink">
					<?php esc_html_e( 'Message', 'trg-site' ); ?> <span class="text-brand-600" aria-hidden="true">*</span>
				</label>
				<textarea id="trg-message" name="trg_message" rows="5" required
					placeholder="<?php esc_attr_e( 'Tell us what is working, what is frustrating your team, and what you want technology to do better.', 'trg-site' ); ?>"
					class="w-full rounded-lg border border-line bg-white px-3.5 py-2.5 text-[15px] text-body placeholder:text-soft focus:border-brand-600"><?php echo esc_textarea( $value( 'message' ) ); ?></textarea>
			</div>

			<button type="submit" class="btn-primary w-full">
				<?php echo esc_html( $atts['button'] ); ?>
				<?php echo trg_site_icon( 'send', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>

			<p class="text-[13px] leading-relaxed text-soft">
				<?php
				printf(
					/* translators: %s: phone number. */
					esc_html__( 'We use your details only to reply to this enquiry. Prefer to talk? Call %s.', 'trg-site' ),
					esc_html( trg_site_company( 'phone' ) )
				);
				?>
			</p>
		</form>
	</div>
	<?php
	delete_transient( trg_form_state_key() );
	return ob_get_clean();
}
add_shortcode( 'trg_contact_form', 'trg_sc_contact_form' );

/**
 * A per-visitor key for remembering what they typed across the redirect.
 *
 * Keyed on IP and user agent rather than a cookie so it works for a visitor who
 * has not accepted cookies, and expires on its own.
 *
 * @return string
 */
function trg_form_state_key() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$ua = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';
	return 'trg_form_' . md5( $ip . '|' . $ua );
}

/**
 * The visitor's IP, used for rate limiting only.
 *
 * @return string
 */
function trg_client_ip() {
	return isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '0.0.0.0';
}

/**
 * Handle the submission.
 */
function trg_handle_contact() {
	$return = isset( $_POST['trg_return'] ) ? esc_url_raw( wp_unslash( $_POST['trg_return'] ) ) : home_url( '/contact' );
	// Only ever redirect back to this site, whatever the form was told to post.
	if ( 0 !== strpos( $return, home_url() ) ) {
		$return = home_url( '/contact' );
	}

	$finish = static function ( $status ) use ( $return ) {
		wp_safe_redirect( add_query_arg( 'trg_sent', $status, $return ) . '#contact-form' );
		exit;
	};

	if ( ! isset( $_POST['trg_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['trg_contact_nonce'] ) ), 'trg_contact' ) ) {
		$finish( 'invalid' );
	}

	// A filled honeypot is a bot. Report success so it stops retrying, and file
	// nothing — the one place where a cheerful message is the right answer.
	if ( ! empty( $_POST['trg_website'] ) ) {
		$finish( 'ok' );
	}

	$fields = array(
		'name'    => isset( $_POST['trg_name'] ) ? sanitize_text_field( wp_unslash( $_POST['trg_name'] ) ) : '',
		'company' => isset( $_POST['trg_company'] ) ? sanitize_text_field( wp_unslash( $_POST['trg_company'] ) ) : '',
		'email'   => isset( $_POST['trg_email'] ) ? sanitize_email( wp_unslash( $_POST['trg_email'] ) ) : '',
		'phone'   => isset( $_POST['trg_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['trg_phone'] ) ) : '',
		'service' => isset( $_POST['trg_service'] ) ? sanitize_key( wp_unslash( $_POST['trg_service'] ) ) : '',
		'message' => isset( $_POST['trg_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['trg_message'] ) ) : '',
	);

	// Keep what they typed so a rejected submission does not throw it away.
	set_transient( trg_form_state_key(), $fields, 15 * MINUTE_IN_SECONDS );

	if ( ! $fields['name'] || ! is_email( $fields['email'] ) || strlen( $fields['message'] ) < 5 ) {
		$finish( 'invalid' );
	}

	// Rate limit: five submissions per IP per ten minutes.
	$bucket = 'trg_rate_' . md5( trg_client_ip() );
	$count  = (int) get_transient( $bucket );
	if ( $count >= 5 ) {
		$finish( 'throttled' );
	}
	set_transient( $bucket, $count + 1, 10 * MINUTE_IN_SECONDS );

	$options = trg_service_options();
	$service = isset( $options[ $fields['service'] ] ) ? $options[ $fields['service'] ] : __( 'Not specified', 'trg-site' );

	// Store first. A mail failure must never lose the lead.
	$enquiry_id = wp_insert_post( array(
		'post_type'    => TRG_ENQUIRY_POST_TYPE,
		'post_status'  => 'publish',
		'post_title'   => sprintf(
			/* translators: 1: sender name, 2: company or service. */
			__( '%1$s — %2$s', 'trg-site' ),
			$fields['name'],
			$fields['company'] ? $fields['company'] : $service
		),
		'post_content' => $fields['message'],
		'meta_input'   => array(
			'_trg_email'   => $fields['email'],
			'_trg_phone'   => $fields['phone'],
			'_trg_company' => $fields['company'],
			'_trg_service' => $service,
			'_trg_ip'      => trg_client_ip(),
		),
	), true );

	$to      = trg_site_company( 'email' );
	$to      = is_email( $to ) ? $to : get_option( 'admin_email' );
	$subject = sprintf(
		/* translators: 1: service area, 2: sender name. */
		__( 'Website enquiry: %1$s — %2$s', 'trg-site' ),
		$service,
		$fields['name']
	);

	$lines = array(
		__( 'New enquiry from the website.', 'trg-site' ),
		'',
		sprintf( '%s: %s', __( 'Name', 'trg-site' ), $fields['name'] ),
		sprintf( '%s: %s', __( 'Company', 'trg-site' ), $fields['company'] ? $fields['company'] : '—' ),
		sprintf( '%s: %s', __( 'Email', 'trg-site' ), $fields['email'] ),
		sprintf( '%s: %s', __( 'Phone', 'trg-site' ), $fields['phone'] ? $fields['phone'] : '—' ),
		sprintf( '%s: %s', __( 'Interested in', 'trg-site' ), $service ),
		'',
		__( 'Message:', 'trg-site' ),
		$fields['message'],
		'',
		'---',
		sprintf( '%s: %s', __( 'Sent from', 'trg-site' ), $return ),
	);

	// From: an address at this site's own domain, so SPF and DMARC pass.
	// Reply-To: the enquirer, so hitting reply reaches the right person.
	$domain  = wp_parse_url( home_url(), PHP_URL_HOST );
	$domain  = preg_replace( '/^www\./', '', (string) $domain );
	$headers = array(
		'From: ' . trg_site_company( 'name' ) . ' website <website@' . $domain . '>',
		'Reply-To: ' . $fields['name'] . ' <' . $fields['email'] . '>',
		'Content-Type: text/plain; charset=UTF-8',
	);

	$sent = wp_mail( $to, $subject, implode( "\n", $lines ), $headers );

	if ( ! is_wp_error( $enquiry_id ) && $enquiry_id ) {
		update_post_meta( $enquiry_id, '_trg_mailed', $sent ? '1' : '0' );
	}

	if ( ! $sent ) {
		$finish( 'failed' );
	}

	delete_transient( trg_form_state_key() );
	$finish( 'ok' );
}
add_action( 'admin_post_nopriv_trg_contact', 'trg_handle_contact' );
add_action( 'admin_post_trg_contact', 'trg_handle_contact' );

/**
 * Show the useful fields in the Enquiries list rather than making someone open
 * every row to find a phone number.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function trg_enquiry_columns( $columns ) {
	return array(
		'cb'           => isset( $columns['cb'] ) ? $columns['cb'] : '',
		'title'        => __( 'From', 'trg-site' ),
		'trg_email'    => __( 'Email', 'trg-site' ),
		'trg_phone'    => __( 'Phone', 'trg-site' ),
		'trg_service'  => __( 'Interested in', 'trg-site' ),
		'trg_mailed'   => __( 'Emailed', 'trg-site' ),
		'date'         => __( 'Received', 'trg-site' ),
	);
}
add_filter( 'manage_' . TRG_ENQUIRY_POST_TYPE . '_posts_columns', 'trg_enquiry_columns' );

/**
 * Fill those columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function trg_enquiry_column( $column, $post_id ) {
	switch ( $column ) {
		case 'trg_email':
			$email = get_post_meta( $post_id, '_trg_email', true );
			if ( $email ) {
				printf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( $email ) );
			}
			break;
		case 'trg_phone':
			echo esc_html( get_post_meta( $post_id, '_trg_phone', true ) );
			break;
		case 'trg_service':
			echo esc_html( get_post_meta( $post_id, '_trg_service', true ) );
			break;
		case 'trg_mailed':
			$mailed = get_post_meta( $post_id, '_trg_mailed', true );
			echo '1' === $mailed
				? esc_html__( 'Yes', 'trg-site' )
				: '<strong style="color:#b32d2e">' . esc_html__( 'NO — not delivered', 'trg-site' ) . '</strong>';
			break;
	}
}
add_action( 'manage_' . TRG_ENQUIRY_POST_TYPE . '_posts_custom_column', 'trg_enquiry_column', 10, 2 );

/**
 * Show the full enquiry when one is opened.
 */
function trg_enquiry_meta_box() {
	add_meta_box(
		'trg_enquiry_details',
		__( 'Enquiry details', 'trg-site' ),
		static function ( $post ) {
			$rows = array(
				__( 'Email', 'trg-site' )         => get_post_meta( $post->ID, '_trg_email', true ),
				__( 'Phone', 'trg-site' )         => get_post_meta( $post->ID, '_trg_phone', true ),
				__( 'Company', 'trg-site' )       => get_post_meta( $post->ID, '_trg_company', true ),
				__( 'Interested in', 'trg-site' ) => get_post_meta( $post->ID, '_trg_service', true ),
				__( 'IP address', 'trg-site' )    => get_post_meta( $post->ID, '_trg_ip', true ),
				__( 'Emailed out', 'trg-site' )   => '1' === get_post_meta( $post->ID, '_trg_mailed', true )
					? __( 'Yes', 'trg-site' )
					: __( 'No — the notification email did not send', 'trg-site' ),
			);
			echo '<table class="widefat striped"><tbody>';
			foreach ( $rows as $label => $value ) {
				printf( '<tr><th style="width:160px">%s</th><td>%s</td></tr>', esc_html( $label ), esc_html( $value ? $value : '—' ) );
			}
			echo '</tbody></table>';
			echo '<p><strong>' . esc_html__( 'Message', 'trg-site' ) . '</strong></p>';
			echo '<p style="white-space:pre-wrap">' . esc_html( $post->post_content ) . '</p>';
		},
		TRG_ENQUIRY_POST_TYPE,
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'trg_enquiry_meta_box' );
