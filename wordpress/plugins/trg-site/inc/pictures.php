<?php
/**
 * Settings → TRG Pictures.
 *
 * Every photograph on the site, numbered, with the place it appears and the
 * shape that suits it. The client asked for exactly this: "we can even set
 * picture to a number and I can upload the pictures here."
 *
 * The numbers are the point. They give the client and me a shared vocabulary —
 * "replace 03" is unambiguous in a chat message in a way that "the one on the
 * home page" is not — and they let the pictures be handed over without anyone
 * having to log in and hunt for the right block.
 *
 * An upload here never overwrites the file that ships with the theme. It is
 * stored in the media library and recorded as an override, so "put it back"
 * is always available and a theme update cannot silently undo a client's photo.
 *
 * @package TRG_Site
 */

defined( 'ABSPATH' ) || exit;

const TRG_PICTURES_OPTION = 'trg_pictures';

/**
 * The picture slots, in the order they appear to a visitor.
 *
 * `key` doubles as the filename of the image that ships with the theme, which
 * is what makes the override lookup in trg_image_url() a single array read.
 *
 * @return array<int,array<string,string>>
 */
function trg_picture_slots() {
	$slots = array(
		array(
			'key'   => 'logo-trg',
			'label' => __( 'Logo — main', 'trg-site' ),
			'where' => __( 'Top left of every page, and the mobile menu.', 'trg-site' ),
			'size'  => __( 'Wide and short, around 600 × 220. PNG or WEBP with a transparent background is best.', 'trg-site' ),
		),
		array(
			'key'   => 'logo-white',
			'label' => __( 'Logo — white version', 'trg-site' ),
			'where' => __( 'Footer, on the dark navy background.', 'trg-site' ),
			'size'  => __( 'Same shape as the main logo, drawn in white. Transparent background.', 'trg-site' ),
		),
		array(
			'key'   => 'hero-team',
			'label' => __( 'Home page — main photo', 'trg-site' ),
			'where' => __( 'The large photo beside "Personalized Technology Support Built Around Your Goals".', 'trg-site' ),
			'size'  => __( 'Landscape, around 1400 × 800. The bottom of this photo is darkened so the caption can sit on top of it, so keep faces out of the very bottom.', 'trg-site' ),
		),
		array(
			'key'   => 'lov-support',
			'label' => __( 'Home page — support photo', 'trg-site' ),
			'where' => __( 'Beside "Multiple eyes on every request. One team accountable."', 'trg-site' ),
			'size'  => __( 'Landscape, around 1200 × 900.', 'trg-site' ),
		),
		array(
			'key'   => 'about-team',
			'label' => __( 'About page — history photo', 'trg-site' ),
			'where' => __( 'Beside "Maryland roots. Nationwide support."', 'trg-site' ),
			'size'  => __( 'Landscape, around 1200 × 900.', 'trg-site' ),
		),
	);

	/*
	 * One slot per page, named after the page, so a photo can be handed over as
	 * "replace 09" rather than "the one on the cybersecurity page". Built from
	 * the same page list the site is built from, so a page added later gets its
	 * slot automatically and this list can never drift out of step with the site.
	 *
	 * A slot with nothing in it is not a hole: that page's hero simply renders
	 * full width, which is the layout the reference build uses.
	 */
	$pages = array(
		'services'   => __( 'Services (the hub page)', 'trg-site' ),
		'industries' => __( 'Industries (the hub page)', 'trg-site' ),
		'why-trg'    => __( 'Why TRG', 'trg-site' ),
		'about'      => __( 'About', 'trg-site' ),
		'resources'  => __( 'Resources', 'trg-site' ),
		'contact'    => __( 'Contact', 'trg-site' ),
	);
	if ( function_exists( 'trg_detail_page_data' ) ) {
		foreach ( trg_detail_page_data() as $slug => $page ) {
			$pages[ $slug ] = $page['title'];
		}
	}

	foreach ( $pages as $slug => $label ) {
		$slots[] = array(
			'key'   => 'pg-' . $slug,
			/* translators: %s: page name. */
			'label' => sprintf( __( '%s — page photo', 'trg-site' ), $label ),
			'where' => __( 'Beside the heading at the top of the page.', 'trg-site' ),
			'size'  => __( 'Landscape, around 1400 × 800. Leave empty and the heading runs full width instead.', 'trg-site' ),
		);
	}

	/*
	 * One slot per person on the About page. Four of the six currently show
	 * initials rather than a face, because the pictures on the old site are not
	 * photographs of those people — see inc/team.php. Uploading a real headshot
	 * here replaces the initials with no other change needed.
	 */
	if ( function_exists( 'trg_team_members' ) ) {
		foreach ( trg_team_members() as $member ) {
			$slots[] = array(
				'key'   => 'team-' . $member['slug'],
				/* translators: %s: person's name. */
				'label' => sprintf( __( '%s — photo', 'trg-site' ), $member['name'] ),
				'where' => __( 'The leadership team on the About page.', 'trg-site' ),
				'size'  => $member['photo']
					? __( 'Square, around 640 × 640, head and shoulders.', 'trg-site' )
					: __( 'Square, around 640 × 640, head and shoulders. Showing initials at the moment — upload a photo and it replaces them.', 'trg-site' ),
			);
		}
	}

	return $slots;
}

/**
 * Saved overrides: slot key => attachment ID.
 *
 * @return array<string,int>
 */
function trg_picture_overrides() {
	return array_map( 'intval', (array) get_option( TRG_PICTURES_OPTION, array() ) );
}

/**
 * The attachment ID sitting in a slot, or 0 when the slot has never been filled.
 *
 * Separate from trg_picture_override_url() because a credit line has to be read
 * off the attachment itself. Tying it to the picture rather than to the page
 * means that when the client swaps the photograph for one of his own, the credit
 * for the previous one disappears with it — nobody has to remember to delete it.
 *
 * @param string $key Slot key.
 * @return int
 */
function trg_picture_override_id( $key ) {
	$map = trg_picture_overrides();
	return empty( $map[ $key ] ) ? 0 : (int) $map[ $key ];
}

/**
 * The URL to use for a slot, or '' when the theme's own file should be used.
 *
 * @param string $key Slot key.
 * @return string
 */
function trg_picture_override_url( $key ) {
	$map = trg_picture_overrides();
	if ( empty( $map[ $key ] ) ) {
		return '';
	}
	$url = wp_get_attachment_image_url( $map[ $key ], 'full' );
	return $url ? $url : '';
}

/**
 * Admin menu entry.
 */
function trg_pictures_menu() {
	add_submenu_page(
		TRG_HUB_SLUG,
		__( 'TRG Pictures', 'trg-site' ),
		__( 'Pictures', 'trg-site' ),
		'manage_options',
		'trg-pictures',
		'trg_pictures_page'
	);
}
add_action( 'admin_menu', 'trg_pictures_menu' );

/**
 * Handle an upload or a reset, then render the screen.
 */
function trg_pictures_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to change the pictures.', 'trg-site' ) );
	}

	$notices = array();
	$map     = trg_picture_overrides();
	$slots   = wp_list_pluck( trg_picture_slots(), 'key' );

	if ( isset( $_POST['trg_pictures_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['trg_pictures_nonce'] ) ), 'trg_pictures' ) ) {

		$reset = isset( $_POST['reset'] ) ? sanitize_key( wp_unslash( $_POST['reset'] ) ) : '';
		if ( $reset && in_array( $reset, $slots, true ) ) {
			unset( $map[ $reset ] );
			update_option( TRG_PICTURES_OPTION, $map );
			$notices[] = array( 'updated', __( 'Put the original picture back.', 'trg-site' ) );
		}

		$slot = isset( $_POST['slot'] ) ? sanitize_key( wp_unslash( $_POST['slot'] ) ) : '';
		if ( $slot && in_array( $slot, $slots, true ) && ! empty( $_FILES['picture']['name'] ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';

			// media_handle_upload() checks the MIME type itself and refuses
			// anything that is not really an image, so a renamed .php cannot
			// arrive through this form.
			$id = media_handle_upload( 'picture', 0 );
			if ( is_wp_error( $id ) ) {
				$notices[] = array( 'error', $id->get_error_message() );
			} else {
				$map[ $slot ] = (int) $id;
				update_option( TRG_PICTURES_OPTION, $map );
				$notices[] = array( 'updated', __( 'Picture updated. Open the page to see it.', 'trg-site' ) );
			}
		}
	}

	$max = wp_max_upload_size();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'TRG Pictures', 'trg-site' ); ?></h1>
		<p style="max-width:46em">
			<?php esc_html_e( 'Every picture on the site, numbered. Upload a replacement and it appears everywhere that picture is used. Nothing is overwritten — “Put the original back” is always available.', 'trg-site' ); ?>
		</p>
		<p style="max-width:46em">
			<?php
			printf(
				/* translators: %s: formatted file size, e.g. "2 MB". */
				esc_html__( 'The largest file this server currently accepts is %s. If your photo is bigger than that the upload will fail, and your host has to raise the limit.', 'trg-site' ),
				esc_html( size_format( $max ) )
			);
			?>
		</p>

		<?php foreach ( $notices as $notice ) : ?>
			<div class="<?php echo esc_attr( 'error' === $notice[0] ? 'notice notice-error' : 'notice notice-success' ); ?>"><p><?php echo esc_html( $notice[1] ); ?></p></div>
		<?php endforeach; ?>

		<table class="widefat striped" style="max-width:70em;margin-top:1em">
			<thead>
				<tr>
					<th style="width:3em"><?php esc_html_e( 'No.', 'trg-site' ); ?></th>
					<th style="width:12em"><?php esc_html_e( 'Now', 'trg-site' ); ?></th>
					<th><?php esc_html_e( 'Where it appears', 'trg-site' ); ?></th>
					<th style="width:22em"><?php esc_html_e( 'Replace it', 'trg-site' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( trg_picture_slots() as $i => $slot ) : ?>
				<?php
				$number   = sprintf( '%02d', $i + 1 );
				$override = trg_picture_override_url( $slot['key'] );
				// Not every slot ships with a picture. Printing a URL for a file
				// that is not there would render a broken-image icon and read as
				// a fault; an empty slot is a normal, valid state.
				$shipped  = get_template_directory() . '/assets/img/' . $slot['key'] . '.webp';
				$current  = $override ? $override : ( file_exists( $shipped ) ? get_template_directory_uri() . '/assets/img/' . $slot['key'] . '.webp' : '' );
				?>
				<tr>
					<td><strong style="font-size:15px"><?php echo esc_html( $number ); ?></strong></td>
					<td>
						<?php if ( $current ) : ?>
							<img src="<?php echo esc_url( $current ); ?>" alt=""
								style="max-width:11em;height:auto;background:#f0f0f1;padding:4px;border:1px solid #dcdcde">
						<?php else : ?>
							<p style="margin:0;padding:1.6em .6em;text-align:center;color:#646970;background:#f0f0f1;border:1px dashed #c3c4c7">
								<?php esc_html_e( 'No picture yet', 'trg-site' ); ?>
							</p>
						<?php endif; ?>
						<?php if ( $override ) : ?>
							<p style="margin:.4em 0 0;color:#2271b1"><?php esc_html_e( 'Your picture', 'trg-site' ); ?></p>
						<?php endif; ?>
					</td>
					<td>
						<strong><?php echo esc_html( $slot['label'] ); ?></strong><br>
						<?php echo esc_html( $slot['where'] ); ?><br>
						<span style="color:#646970"><?php echo esc_html( $slot['size'] ); ?></span>
					</td>
					<td>
						<form method="post" enctype="multipart/form-data" style="margin:0 0 .6em">
							<?php wp_nonce_field( 'trg_pictures', 'trg_pictures_nonce' ); ?>
							<input type="hidden" name="slot" value="<?php echo esc_attr( $slot['key'] ); ?>">
							<input type="file" name="picture" accept="image/*" required>
							<p style="margin:.5em 0 0"><button type="submit" class="button button-primary"><?php esc_html_e( 'Upload', 'trg-site' ); ?></button></p>
						</form>
						<?php if ( $override ) : ?>
							<form method="post" style="margin:0">
								<?php wp_nonce_field( 'trg_pictures', 'trg_pictures_nonce' ); ?>
								<input type="hidden" name="reset" value="<?php echo esc_attr( $slot['key'] ); ?>">
								<button type="submit" class="button-link" style="color:#b32d2e"><?php esc_html_e( 'Put the original back', 'trg-site' ); ?></button>
							</form>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}
