<?php
/**
 * Outgoing email.
 *
 * PHP on a Windows/IIS host has no local mail server: php.ini ships with
 * SMTP=localhost and smtp_port=25, and nothing is listening there, so every
 * mail() call fails with "Could not instantiate mail function". On this host
 * outbound port 25 is blocked outright as well, so relaying straight to the
 * domain's MX is not an option either. Authenticated submission on 587 is.
 *
 * Rather than hard-code a mailbox password into the site, the credentials live
 * in a settings screen the site owner fills in themselves — and can be
 * overridden by constants in wp-config.php, which is the safer place for a
 * secret because it is never in the database and never in a backup export.
 *
 * @package TRG_Site
 */

defined( 'ABSPATH' ) || exit;

/**
 * Saved mail settings, with constants taking precedence over the database.
 *
 * @return array
 */
function trg_smtp_settings() {
	$saved = wp_parse_args(
		(array) get_option( 'trg_smtp', array() ),
		array(
			'host'       => 'smtp.office365.com',
			'port'       => 587,
			'encryption' => 'tls',
			'username'   => '',
			'password'   => '',
			'from'       => '',
			'from_name'  => '',
			'to'         => '',
		)
	);

	// wp-config.php wins, so a secret can be kept out of the database entirely.
	$constants = array(
		'host'       => 'TRG_SMTP_HOST',
		'port'       => 'TRG_SMTP_PORT',
		'encryption' => 'TRG_SMTP_ENCRYPTION',
		'username'   => 'TRG_SMTP_USER',
		'password'   => 'TRG_SMTP_PASS',
		'from'       => 'TRG_SMTP_FROM',
	);
	foreach ( $constants as $key => $constant ) {
		if ( defined( $constant ) ) {
			$saved[ $key ] = constant( $constant );
		}
	}

	$saved['port'] = (int) $saved['port'];

	return $saved;
}

/**
 * Is there enough here to attempt a send?
 *
 * @return bool
 */
function trg_smtp_configured() {
	$s = trg_smtp_settings();
	return '' !== $s['host'] && '' !== $s['username'] && '' !== $s['password'];
}

/**
 * The address enquiries are delivered to.
 *
 * Deliberately separate from the address printed on the website: the public
 * one is info@, but the person who actually wants the notification may be
 * someone else, and putting their address in the footer would only feed it to
 * scrapers.
 *
 * @return string
 */
function trg_enquiry_recipient() {
	$s = trg_smtp_settings();
	if ( is_email( $s['to'] ) ) {
		return $s['to'];
	}
	$company = trg_site_company( 'email' );
	return is_email( $company ) ? $company : get_option( 'admin_email' );
}

/**
 * Point PHPMailer at the submission server.
 *
 * @param PHPMailer\PHPMailer\PHPMailer $mail Mailer instance.
 */
function trg_smtp_configure( $mail ) {
	if ( ! trg_smtp_configured() ) {
		return;
	}
	$s = trg_smtp_settings();

	$mail->isSMTP();
	$mail->Host        = $s['host'];
	$mail->Port        = $s['port'];
	$mail->SMTPAuth    = true;
	$mail->Username    = $s['username'];
	$mail->Password    = $s['password'];
	$mail->SMTPSecure  = 'none' === $s['encryption'] ? '' : $s['encryption'];
	$mail->SMTPAutoTLS = 'none' !== $s['encryption'];
	$mail->Timeout     = 20;

	// Microsoft 365 rejects a From that is not the authenticated mailbox (or an
	// address it is allowed to send as), so default From to the login itself.
	$from = is_email( $s['from'] ) ? $s['from'] : $s['username'];
	if ( is_email( $from ) ) {
		$mail->setFrom( $from, $s['from_name'] ? $s['from_name'] : get_bloginfo( 'name' ), false );
	}
}
add_action( 'phpmailer_init', 'trg_smtp_configure' );

/**
 * Settings page.
 */
function trg_smtp_menu() {
	add_options_page(
		__( 'TRG Email', 'trg-site' ),
		__( 'TRG Email', 'trg-site' ),
		'manage_options',
		'trg-email',
		'trg_smtp_page'
	);
}
add_action( 'admin_menu', 'trg_smtp_menu' );

/**
 * Save and test handler, then render.
 */
function trg_smtp_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$notices = array();
	$s       = trg_smtp_settings();

	if ( isset( $_POST['trg_smtp_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['trg_smtp_nonce'] ) ), 'trg_smtp' ) ) {
		$stored = (array) get_option( 'trg_smtp', array() );

		$new = array(
			'host'       => sanitize_text_field( wp_unslash( $_POST['host'] ?? '' ) ),
			'port'       => (int) ( $_POST['port'] ?? 587 ),
			'encryption' => in_array( ( $_POST['encryption'] ?? '' ), array( 'tls', 'ssl', 'none' ), true ) ? sanitize_key( wp_unslash( $_POST['encryption'] ) ) : 'tls',
			'username'   => sanitize_text_field( wp_unslash( $_POST['username'] ?? '' ) ),
			'from'       => sanitize_email( wp_unslash( $_POST['from'] ?? '' ) ),
			'from_name'  => sanitize_text_field( wp_unslash( $_POST['from_name'] ?? '' ) ),
			'to'         => sanitize_email( wp_unslash( $_POST['to'] ?? '' ) ),
		);

		// An empty password box means "leave it alone", so re-saving the other
		// fields cannot silently wipe a working password.
		$posted_pass       = (string) wp_unslash( $_POST['password'] ?? '' );
		$new['password']   = '' === $posted_pass ? ( $stored['password'] ?? '' ) : $posted_pass;

		update_option( 'trg_smtp', $new );
		$s         = trg_smtp_settings();
		$notices[] = array( 'updated', __( 'Settings saved.', 'trg-site' ) );

		$test_to = sanitize_email( wp_unslash( $_POST['test_to'] ?? '' ) );
		if ( isset( $_POST['send_test'] ) && is_email( $test_to ) ) {
			$error = '';
			$debug = '';
			$catch = function ( $e ) use ( &$error ) {
				$error = $e->get_error_message();
			};
			add_action( 'wp_mail_failed', $catch );
			add_action(
				'phpmailer_init',
				function ( $m ) use ( &$debug ) {
					$m->SMTPDebug   = 2;
					$m->Debugoutput = function ( $str ) use ( &$debug ) {
						$debug .= trim( $str ) . "\n";
					};
				},
				99
			);

			$sent = wp_mail(
				$test_to,
				__( 'TRG website test email', 'trg-site' ),
				__( "This is a test from the TRG Networking website.\n\nIf you can read this, the contact form can reach you.", 'trg-site' )
			);
			remove_action( 'wp_mail_failed', $catch );

			if ( $sent ) {
				/* translators: %s: email address. */
				$notices[] = array( 'updated', sprintf( __( 'Test message accepted for delivery to %s. Check the inbox, and the junk folder.', 'trg-site' ), $test_to ) );
			} else {
				/* translators: %s: error message. */
				$notices[] = array( 'error', sprintf( __( 'Test failed: %s', 'trg-site' ), $error ? $error : __( 'no error reported', 'trg-site' ) ) );
				if ( $debug ) {
					$notices[] = array( 'error', '<pre style="white-space:pre-wrap;margin:0">' . esc_html( $debug ) . '</pre>' );
				}
			}
		}
	}

	$has_pass = '' !== $s['password'];
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'TRG Email', 'trg-site' ); ?></h1>

		<?php foreach ( $notices as $n ) : ?>
			<div class="notice notice-<?php echo 'error' === $n[0] ? 'error' : 'success'; ?>"><p><?php echo wp_kses_post( $n[1] ); ?></p></div>
		<?php endforeach; ?>

		<p style="max-width:46em">
			<?php esc_html_e( 'This server has no mail program of its own, so WordPress cannot send anything until a mailbox is entered here. Enquiries from the contact form are always saved under Enquiries whether email works or not — but nobody is notified until this is filled in.', 'trg-site' ); ?>
		</p>
		<p style="max-width:46em">
			<?php esc_html_e( 'For Microsoft 365: use a mailbox that has SMTP AUTH enabled, and an app password rather than the everyday sign-in password. A dedicated mailbox is better than a person’s own.', 'trg-site' ); ?>
		</p>

		<form method="post">
			<?php wp_nonce_field( 'trg_smtp', 'trg_smtp_nonce' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="trg-host"><?php esc_html_e( 'SMTP host', 'trg-site' ); ?></label></th>
					<td><input name="host" id="trg-host" type="text" class="regular-text" value="<?php echo esc_attr( $s['host'] ); ?>"> <span class="description">smtp.office365.com</span></td>
				</tr>
				<tr>
					<th scope="row"><label for="trg-port"><?php esc_html_e( 'Port', 'trg-site' ); ?></label></th>
					<td><input name="port" id="trg-port" type="number" class="small-text" value="<?php echo esc_attr( (string) $s['port'] ); ?>"> <span class="description">587</span></td>
				</tr>
				<tr>
					<th scope="row"><label for="trg-enc"><?php esc_html_e( 'Encryption', 'trg-site' ); ?></label></th>
					<td>
						<select name="encryption" id="trg-enc">
							<option value="tls" <?php selected( $s['encryption'], 'tls' ); ?>>STARTTLS (587)</option>
							<option value="ssl" <?php selected( $s['encryption'], 'ssl' ); ?>>SSL/TLS (465)</option>
							<option value="none" <?php selected( $s['encryption'], 'none' ); ?>><?php esc_html_e( 'None', 'trg-site' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="trg-user"><?php esc_html_e( 'Username', 'trg-site' ); ?></label></th>
					<td><input name="username" id="trg-user" type="text" class="regular-text" value="<?php echo esc_attr( $s['username'] ); ?>" autocomplete="off"></td>
				</tr>
				<tr>
					<th scope="row"><label for="trg-pass"><?php esc_html_e( 'Password', 'trg-site' ); ?></label></th>
					<td>
						<input name="password" id="trg-pass" type="password" class="regular-text" value="" autocomplete="new-password">
						<p class="description">
							<?php
							echo $has_pass
								? esc_html__( 'A password is saved. Leave this blank to keep it.', 'trg-site' )
								: esc_html__( 'No password saved yet.', 'trg-site' );
							?>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="trg-from"><?php esc_html_e( 'Send as', 'trg-site' ); ?></label></th>
					<td>
						<input name="from" id="trg-from" type="email" class="regular-text" value="<?php echo esc_attr( $s['from'] ); ?>">
						<p class="description"><?php esc_html_e( 'Leave blank to send as the username above. Microsoft 365 will refuse any other address unless the mailbox is allowed to send as it.', 'trg-site' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="trg-from-name"><?php esc_html_e( 'Sender name', 'trg-site' ); ?></label></th>
					<td><input name="from_name" id="trg-from-name" type="text" class="regular-text" value="<?php echo esc_attr( $s['from_name'] ); ?>" placeholder="TRG Networking website"></td>
				</tr>
				<tr>
					<th scope="row"><label for="trg-to"><?php esc_html_e( 'Send enquiries to', 'trg-site' ); ?></label></th>
					<td>
						<input name="to" id="trg-to" type="email" class="regular-text" value="<?php echo esc_attr( $s['to'] ); ?>" placeholder="<?php echo esc_attr( trg_site_company( 'email' ) ); ?>">
						<p class="description"><?php esc_html_e( 'Where contact form enquiries are delivered. This is not shown anywhere on the website. Leave blank to use the address printed in the footer.', 'trg-site' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="trg-test"><?php esc_html_e( 'Send a test to', 'trg-site' ); ?></label></th>
					<td>
						<input name="test_to" id="trg-test" type="email" class="regular-text" value="<?php echo esc_attr( wp_get_current_user()->user_email ); ?>">
						<p class="description"><?php esc_html_e( 'Tick the box below, then save, and a test message goes out immediately.', 'trg-site' ); ?></p>
						<p><label><input type="checkbox" name="send_test" value="1"> <?php esc_html_e( 'Send a test message when I save', 'trg-site' ); ?></label></p>
					</td>
				</tr>
			</table>
			<?php submit_button( __( 'Save settings', 'trg-site' ) ); ?>
		</form>
	</div>
	<?php
}

/**
 * Say so, loudly, while email is off — an enquiry nobody is told about is the
 * failure mode this whole page exists to prevent.
 */
function trg_smtp_notice() {
	if ( trg_smtp_configured() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && 'settings_page_trg-email' === $screen->id ) {
		return;
	}
	printf(
		'<div class="notice notice-warning"><p>%s <a href="%s">%s</a></p></div>',
		esc_html__( 'The website cannot send email yet, so nobody is notified when the contact form is used. Enquiries are still being saved.', 'trg-site' ),
		esc_url( admin_url( 'options-general.php?page=trg-email' ) ),
		esc_html__( 'Set up email', 'trg-site' )
	);
}
add_action( 'admin_notices', 'trg_smtp_notice' );
