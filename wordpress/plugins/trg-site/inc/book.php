<?php
/**
 * The book page: "Cybersecurity Without the Jargon".
 *
 * Charles wrote a book and wants it downloadable from the site. Everything the
 * page shows — title, subtitle, author, blurb, the "what's inside" list, the
 * cover and the file itself — is held in one option so he can revise it from
 * the admin screen without touching page content or calling me. A second
 * edition is then a file upload, not a job.
 *
 * The download is deliberately ungated: he asked for a plain link. The
 * settings screen carries a note about the email-capture alternative rather
 * than the code carrying a half-built version of it.
 *
 * @package trg-site
 */

defined( 'ABSPATH' ) || exit;

const TRG_BOOK_OPTION = 'trg_book';

/**
 * Saved book settings, merged over the defaults.
 *
 * @return array<string,string>
 */
function trg_book_settings() {
	$saved = get_option( TRG_BOOK_OPTION, array() );
	if ( ! is_array( $saved ) ) {
		$saved = array();
	}

	return wp_parse_args( $saved, array(
		'title'    => 'Cybersecurity Without the Jargon',
		'subtitle' => 'A plain-English guide for business leaders who have to make security decisions without a security background.',
		// As it appears on the book's own title page and on the About page.
		'author'   => 'Dr. Charles Edwards',
		'blurb'    => 'Most cybersecurity writing is aimed at people who already understand it. This one is not. It explains the decisions a business owner actually faces — what to protect first, what the acronyms mean, what a good provider should be doing for you — in language you can act on.',
		'points'   => '',
		'file'     => '',
		'file_id'  => 0,
		'pages'    => '',
		'note'     => 'Free to download. No sign-up, no email address required.',
	) );
}

/**
 * The URL of the book file, or '' when nothing has been uploaded yet.
 *
 * A pasted URL wins over an uploaded attachment, because the file is likely to
 * arrive by FTP: a book PDF is usually larger than the upload limit PHP is
 * configured with on this server, and waiting for that limit to be raised is
 * not a reason for the page to sit empty.
 *
 * @return string
 */
function trg_book_file_url() {
	$book = trg_book_settings();

	if ( '' !== trim( (string) $book['file'] ) ) {
		return trim( (string) $book['file'] );
	}
	if ( ! empty( $book['file_id'] ) ) {
		$url = wp_get_attachment_url( (int) $book['file_id'] );
		return $url ? $url : '';
	}
	return '';
}

/**
 * The size of the book file as a readable string, or '' when it cannot be read.
 *
 * Shown beside the download button. Telling somebody they are about to pull
 * 8 MB down a phone connection is ordinary courtesy, and it is also the only
 * signal on the page that the file behind the button is really there.
 *
 * @return string
 */
function trg_book_file_size() {
	$book = trg_book_settings();

	if ( ! empty( $book['file_id'] ) ) {
		$path = get_attached_file( (int) $book['file_id'] );
		if ( $path && file_exists( $path ) ) {
			return size_format( filesize( $path ), 1 );
		}
	}

	$url = trg_book_file_url();
	if ( '' === $url ) {
		return '';
	}

	// A URL under this site resolves to a path on disk, so the size can be read
	// without a network round trip on every page view.
	$uploads = wp_get_upload_dir();
	if ( 0 === strpos( $url, $uploads['baseurl'] ) ) {
		$path = $uploads['basedir'] . substr( $url, strlen( $uploads['baseurl'] ) );
		if ( file_exists( $path ) ) {
			return size_format( filesize( $path ), 1 );
		}
	}

	return '';
}

/**
 * The book panel.
 *
 * @param array $atts Attributes. Anything passed here overrides the setting.
 * @return string
 */
function trg_sc_book( $atts ) {
	$book = trg_book_settings();
	$atts = shortcode_atts( array(
		'eyebrow' => 'New book',
		'title'   => $book['title'],
		'lede'    => $book['subtitle'],
		'author'  => $book['author'],
		'body'    => $book['blurb'],
		'points'  => $book['points'],
		'cover'   => 'book-cover',
		'note'    => $book['note'],
		'bg'      => 'white',
	), $atts, 'trg_book' );

	$cover  = trg_image_url( $atts['cover'] );
	$file   = trg_book_file_url();
	$size   = trg_book_file_size();
	$points = trg_split_list( $atts['points'], '|' );

	ob_start();
	?>
	<section class="section <?php echo 'white' === $atts['bg'] ? 'bg-white' : 'bg-canvas'; ?>">
		<?php // min-w-0 on both columns — see the note in trg_sc_media_split(). ?>
		<?php
		/*
		 * items-start, not items-center. With a full chapter list beside it the
		 * text column is several times the height of the cover, and centring
		 * parked the cover in the middle of the row with a screen of dead space
		 * above it. Top-aligned and sticky, it stays with the reader instead.
		 */
		?>
		<div class="shell grid items-start gap-10 lg:grid-cols-[minmax(0,340px)_minmax(0,1fr)] lg:gap-16">

			<div class="min-w-0 lg:sticky lg:top-28">
				<?php if ( $cover ) : ?>
					<?php
					/*
					 * No fixed aspect ratio and no object-cover: a book cover is
					 * artwork, and cropping it to a layout's preferred shape cuts
					 * the title off. The shadow sits under the image rather than
					 * on a wrapper so it follows the cover's own proportions.
					 */
					?>
					<img src="<?php echo esc_url( $cover ); ?>"
						alt="<?php echo esc_attr( sprintf( '%s — book cover', $atts['title'] ) ); ?>"
						loading="lazy"
						class="mx-auto w-full max-w-[300px] rounded-lg shadow-[0_30px_60px_-25px_rgba(15,23,42,0.55)] lg:mx-0">
				<?php else : ?>
					<?php
					/*
					 * A stand-in that reads as a book rather than a broken image,
					 * so the page can go live before the cover artwork exists.
					 * It disappears the moment a cover is uploaded.
					 */
					?>
					<div class="mx-auto flex aspect-[2/3] w-full max-w-[300px] flex-col justify-between rounded-lg bg-gradient-to-br from-brand-700 to-navy p-7 text-white shadow-[0_30px_60px_-25px_rgba(15,23,42,0.55)] lg:mx-0">
						<span class="font-heading text-[11px] font-bold uppercase tracking-[0.18em] text-white/60"><?php esc_html_e( 'TRG Networking', 'trg-site' ); ?></span>
						<span class="font-display text-[26px] font-bold leading-[1.15]"><?php echo esc_html( $atts['title'] ); ?></span>
						<span class="font-heading text-[13px] text-white/70"><?php echo esc_html( $atts['author'] ); ?></span>
					</div>
				<?php endif; ?>
			</div>

			<div class="min-w-0">
				<?php
				echo trg_section_head( array( // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside.
					'eyebrow' => $atts['eyebrow'],
					'title'   => $atts['title'],
					'body'    => $atts['lede'],
					'align'   => 'left',
				) );
				?>

				<?php if ( $atts['author'] ) : ?>
					<p class="mt-5 font-heading text-[15px] font-semibold text-ink">
						<?php
						printf(
							/* translators: %s: author name. */
							esc_html__( 'By %s', 'trg-site' ),
							esc_html( $atts['author'] )
						);
						?>
					</p>
				<?php endif; ?>

				<?php if ( $atts['body'] ) : ?>
					<p class="mt-5 text-[16px] leading-relaxed text-body"><?php echo wp_kses_post( $atts['body'] ); ?></p>
				<?php endif; ?>

				<?php if ( $points ) : ?>
					<ul class="mt-7 space-y-3">
						<?php foreach ( $points as $point ) : ?>
							<li class="flex items-start gap-3 text-[15.5px] text-body">
								<?php echo trg_site_icon( 'check', 17, 'mt-0.5 shrink-0 text-brand-600' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<?php echo esc_html( $point ); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<div class="mt-9">
					<?php if ( $file ) : ?>
						<?php
						/*
						 * `download` asks the browser to save rather than open the
						 * PDF in a tab. Same-origin only, which is why the file
						 * belongs in uploads and not on someone else's host.
						 */
						?>
						<a href="<?php echo esc_url( $file ); ?>" class="btn-primary" download>
							<?php esc_html_e( 'Download the book', 'trg-site' ); ?>
							<?php echo trg_site_icon( 'arrow-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</a>
						<p class="mt-3 text-[13.5px] text-muted">
							<?php
							$meta = array_filter( array(
								strtoupper( pathinfo( wp_parse_url( $file, PHP_URL_PATH ), PATHINFO_EXTENSION ) ),
								$size,
								$atts['note'],
							) );
							echo esc_html( implode( ' · ', $meta ) );
							?>
						</p>
					<?php else : ?>
						<?php
						/*
						 * No dead button. A download link that 404s costs more trust
						 * than an honest "not yet" — and this page goes live before
						 * the file does.
						 */
						?>
						<span class="inline-flex items-center gap-2 rounded-full border border-line bg-canvas px-4 py-2 font-heading text-[14px] font-semibold text-muted">
							<span class="h-2 w-2 shrink-0 rounded-full bg-brand-400" aria-hidden="true"></span>
							<?php esc_html_e( 'Available to download shortly', 'trg-site' ); ?>
						</span>
						<p class="mt-3 text-[13.5px] text-muted">
							<?php esc_html_e( 'The book is being finalised. Call us and we will send you a copy the day it is ready.', 'trg-site' ); ?>
						</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_book', 'trg_sc_book' );

/**
 * Schema.org Book data, so a search engine can see this as a published work by
 * a named author rather than as another page of marketing text.
 *
 * Printed only on the page that actually carries the shortcode — describing a
 * book in the head of a page that does not show one is a false claim, and the
 * kind search engines penalise.
 */
function trg_book_schema() {
	if ( ! is_page() ) {
		return;
	}

	$post = get_post();
	if ( ! $post || ! has_shortcode( (string) $post->post_content, 'trg_book' ) ) {
		return;
	}

	$book = trg_book_settings();
	$data = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Book',
		'name'     => $book['title'],
		'author'   => array(
			'@type' => 'Person',
			'name'  => $book['author'],
		),
		'publisher' => array(
			'@type' => 'Organization',
			'name'  => trg_site_company( 'name' ),
		),
		'inLanguage' => 'en',
		'url'        => get_permalink( $post ),
	);

	$description = trim( (string) $book['subtitle'] );
	if ( '' !== $description ) {
		$data['description'] = $description;
	}

	$cover = trg_image_url( 'book-cover' );
	if ( $cover ) {
		$data['image'] = $cover;
	}

	$file = trg_book_file_url();
	if ( '' !== $file ) {
		$data['workExample'] = array(
			'@type'          => 'Book',
			'bookFormat'     => 'https://schema.org/EBook',
			'encodingFormat' => 'application/pdf',
			'url'            => $file,
			'isAccessibleForFree' => true,
		);
	}

	echo "\n" . '<script type="application/ld+json">'
		. wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
		. '</script>' . "\n";
}
add_action( 'wp_head', 'trg_book_schema', 20 );

/**
 * Admin menu entry.
 */
function trg_book_menu() {
	add_submenu_page(
		TRG_HUB_SLUG,
		__( 'The book', 'trg-site' ),
		__( 'The book', 'trg-site' ),
		'manage_options',
		'trg-book',
		'trg_book_page'
	);
}
add_action( 'admin_menu', 'trg_book_menu' );

/**
 * Save, then render the settings screen.
 */
function trg_book_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to change the book.', 'trg-site' ) );
	}

	$notices = array();
	$book    = trg_book_settings();

	if ( isset( $_POST['trg_book_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['trg_book_nonce'] ) ), 'trg_book' ) ) {

		if ( isset( $_POST['remove_file'] ) ) {
			$book['file']    = '';
			$book['file_id'] = 0;
			update_option( TRG_BOOK_OPTION, $book );
			$notices[] = array( 'updated', __( 'Download removed. The page now says the book is coming shortly.', 'trg-site' ) );
		} else {
			/*
			 * array_key_exists, not isset/!empty: a field the client has cleared
			 * on purpose has to be saveable as empty. Guarding on !empty() means
			 * a blurb can be changed but never deleted.
			 */
			foreach ( array( 'title', 'subtitle', 'author', 'blurb', 'points', 'note', 'file' ) as $field ) {
				if ( array_key_exists( $field, $_POST ) ) {
					$value = wp_unslash( $_POST[ $field ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitised on the next line.
					$book[ $field ] = 'file' === $field
						? esc_url_raw( trim( (string) $value ) )
						: sanitize_textarea_field( (string) $value );
				}
			}

			if ( ! empty( $_FILES['book_file']['name'] ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/media.php';
				require_once ABSPATH . 'wp-admin/includes/image.php';

				// media_handle_upload() checks the real MIME type, so a renamed
				// script cannot arrive through this form.
				$id = media_handle_upload( 'book_file', 0 );
				if ( is_wp_error( $id ) ) {
					$notices[] = array( 'error', $id->get_error_message() );
				} else {
					$book['file_id'] = (int) $id;
					$book['file']    = '';
					$notices[] = array( 'updated', __( 'Book file uploaded.', 'trg-site' ) );
				}
			}

			update_option( TRG_BOOK_OPTION, $book );
			$notices[] = array( 'updated', __( 'Saved.', 'trg-site' ) );
		}

		$book = trg_book_settings();
	}

	$file = trg_book_file_url();
	$size = trg_book_file_size();
	$page = trg_site_page_url( 'book' );
	$max  = wp_max_upload_size();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'The book', 'trg-site' ); ?></h1>
		<p style="max-width:46em">
			<?php esc_html_e( 'Everything shown on the book page. Change anything here and the page updates — the wording, the file people download, and the note under the button.', 'trg-site' ); ?>
			<?php if ( $page ) : ?>
				<a href="<?php echo esc_url( $page ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Open the page', 'trg-site' ); ?></a>
			<?php endif; ?>
		</p>

		<?php foreach ( $notices as $notice ) : ?>
			<div class="<?php echo esc_attr( 'error' === $notice[0] ? 'notice notice-error' : 'notice notice-success' ); ?>"><p><?php echo esc_html( $notice[1] ); ?></p></div>
		<?php endforeach; ?>

		<form method="post" enctype="multipart/form-data" style="max-width:52em">
			<?php wp_nonce_field( 'trg_book', 'trg_book_nonce' ); ?>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="trg-book-title"><?php esc_html_e( 'Title', 'trg-site' ); ?></label></th>
					<td><input name="title" id="trg-book-title" type="text" class="large-text" value="<?php echo esc_attr( $book['title'] ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="trg-book-subtitle"><?php esc_html_e( 'One line under the title', 'trg-site' ); ?></label></th>
					<td>
						<textarea name="subtitle" id="trg-book-subtitle" rows="2" class="large-text"><?php echo esc_textarea( $book['subtitle'] ); ?></textarea>
						<p class="description"><?php esc_html_e( 'Also used as the description a search engine reads.', 'trg-site' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="trg-book-author"><?php esc_html_e( 'Author', 'trg-site' ); ?></label></th>
					<td><input name="author" id="trg-book-author" type="text" class="large-text" value="<?php echo esc_attr( $book['author'] ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="trg-book-blurb"><?php esc_html_e( 'About the book', 'trg-site' ); ?></label></th>
					<td><textarea name="blurb" id="trg-book-blurb" rows="5" class="large-text"><?php echo esc_textarea( $book['blurb'] ); ?></textarea></td>
				</tr>
				<tr>
					<th scope="row"><label for="trg-book-points"><?php esc_html_e( 'What is inside', 'trg-site' ); ?></label></th>
					<td>
						<textarea name="points" id="trg-book-points" rows="4" class="large-text" placeholder="<?php echo esc_attr__( 'One per line', 'trg-site' ); ?>"><?php echo esc_textarea( str_replace( '|', "\n", $book['points'] ) ); ?></textarea>
						<p class="description"><?php esc_html_e( 'One point per line — chapter headings work well. Each appears with a tick beside it. Leave empty and the list is left out.', 'trg-site' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'The file', 'trg-site' ); ?></th>
					<td>
						<?php if ( $file ) : ?>
							<p>
								<strong><?php esc_html_e( 'Now:', 'trg-site' ); ?></strong>
								<a href="<?php echo esc_url( $file ); ?>" target="_blank" rel="noopener"><?php echo esc_html( basename( (string) wp_parse_url( $file, PHP_URL_PATH ) ) ); ?></a>
								<?php if ( $size ) : ?><span class="description"><?php echo esc_html( $size ); ?></span><?php endif; ?>
							</p>
						<?php else : ?>
							<p class="description"><?php esc_html_e( 'Nothing uploaded yet — the page says the book is coming shortly instead of showing a button.', 'trg-site' ); ?></p>
						<?php endif; ?>

						<p style="margin-top:1em">
							<input type="file" name="book_file" accept=".pdf,.epub">
						</p>
						<p class="description">
							<?php
							printf(
								/* translators: %s: formatted file size, e.g. "2 MB". */
								esc_html__( 'The largest file this server currently accepts through the browser is %s. If the book is bigger than that, put it on the server by FTP and paste its address in the box below instead — that route has no size limit.', 'trg-site' ),
								esc_html( size_format( $max ) )
							);
							?>
						</p>

						<p style="margin-top:1em">
							<label for="trg-book-file"><?php esc_html_e( 'Or the address of a file already on the server', 'trg-site' ); ?></label><br>
							<input name="file" id="trg-book-file" type="url" class="large-text code" value="<?php echo esc_attr( $book['file'] ); ?>" placeholder="https://www.trgnetworking.com/wp-content/uploads/book/...">
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="trg-book-note"><?php esc_html_e( 'Note under the button', 'trg-site' ); ?></label></th>
					<td>
						<input name="note" id="trg-book-note" type="text" class="large-text" value="<?php echo esc_attr( $book['note'] ); ?>">
						<p class="description"><?php esc_html_e( 'Shown after the file type and size, e.g. “PDF · 6 MB · Free to download.”', 'trg-site' ); ?></p>
					</td>
				</tr>
			</table>

			<p class="submit">
				<?php submit_button( __( 'Save', 'trg-site' ), 'primary', 'submit', false ); ?>
				<?php if ( $file ) : ?>
					<button type="submit" name="remove_file" value="1" class="button" style="margin-left:.5em">
						<?php esc_html_e( 'Remove the download', 'trg-site' ); ?>
					</button>
				<?php endif; ?>
			</p>
		</form>

		<h2 style="margin-top:2em"><?php esc_html_e( 'The cover picture', 'trg-site' ); ?></h2>
		<p style="max-width:46em">
			<?php esc_html_e( 'The cover is uploaded under Pictures, with everything else — look for “Book — cover” at the bottom of that list. Until one is uploaded the page draws a plain navy cover with the title on it, so nothing looks broken.', 'trg-site' ); ?>
		</p>

		<h2 style="margin-top:2em"><?php esc_html_e( 'Asking for an email address first', 'trg-site' ); ?></h2>
		<p style="max-width:46em">
			<?php esc_html_e( 'At the moment anyone can download the book with one click, which is what gets it read. The alternative is to ask for a name and email address first, so every download lands in Enquiries as a lead. That is a small change and can be switched on later — it is not built now because a free download was what you asked for.', 'trg-site' ); ?>
		</p>
	</div>
	<?php
}

/**
 * Store the "what is inside" list the way the shortcode reads it.
 *
 * The textarea is one point per line because that is how a person writes a
 * list; the shortcode splits on "|" because that is the separator every other
 * list attribute on this site uses. Converting on save keeps both true.
 *
 * @param mixed $value New option value.
 * @return mixed
 */
function trg_book_normalise_points( $value ) {
	if ( is_array( $value ) && isset( $value['points'] ) ) {
		$lines = preg_split( '/[\r\n]+/', (string) $value['points'] );
		$lines = array_filter( array_map( 'trim', (array) $lines ), 'strlen' );
		$value['points'] = implode( '|', $lines );
	}
	return $value;
}
add_filter( 'pre_update_option_' . TRG_BOOK_OPTION, 'trg_book_normalise_points' );
