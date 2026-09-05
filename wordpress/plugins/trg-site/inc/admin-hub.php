<?php
/**
 * One place in wp-admin for everything the site owner maintains himself.
 *
 * WordPress scatters a site like this across five menus: Pages for the words,
 * three custom lists for the cards, Media for the files, Settings for the
 * pictures and the mailbox. Someone who edits the site once a month should not
 * have to remember that map. Everything he owns is gathered under one
 * "TRG Website" menu, with a plain-English guide as its front page; everything
 * a developer owns stays where a developer expects to find it.
 *
 * @package TRG_Site
 */

defined( 'ABSPATH' ) || exit;

const TRG_HUB_SLUG = 'trg-website';

/**
 * Top-level menu. Registered at priority 9 so it exists before the submenus
 * that other files hang off it.
 */
function trg_hub_menu() {
	add_menu_page(
		__( 'TRG Website', 'trg-site' ),
		__( 'TRG Website', 'trg-site' ),
		'edit_pages',
		TRG_HUB_SLUG,
		'trg_hub_page',
		'dashicons-admin-site-alt3',
		3
	);

	// Rename the auto-created first child so it does not read "TRG Website"
	// twice in a row.
	add_submenu_page(
		TRG_HUB_SLUG,
		__( 'How to update your site', 'trg-site' ),
		__( 'How to update', 'trg-site' ),
		'edit_pages',
		TRG_HUB_SLUG,
		'trg_hub_page'
	);
}
add_action( 'admin_menu', 'trg_hub_menu', 9 );

/**
 * The Pages list, linked from inside the hub rather than duplicated into it.
 * A shortcut, not a second editor — there is exactly one place a page is edited.
 */
function trg_hub_links() {
	global $submenu;

	if ( ! isset( $submenu[ TRG_HUB_SLUG ] ) ) {
		return;
	}

	$submenu[ TRG_HUB_SLUG ][] = array( // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		__( 'Page text', 'trg-site' ),
		'edit_pages',
		'edit.php?post_type=page',
	);
	$submenu[ TRG_HUB_SLUG ][] = array( // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		__( 'Phone, address, social', 'trg-site' ),
		'edit_theme_options',
		'customize.php?autofocus[section]=trg_company',
	);

	// Order them the way the work actually happens — words, then pictures, then
	// the lists, then the settings you touch once a year. Left alone, the order
	// is whatever order the plugin files happened to load in, which puts
	// "Email settings" above "Pictures" for no reason a reader could guess.
	$order = array(
		TRG_HUB_SLUG,
		'edit.php?post_type=page',
		'trg-pictures',
		'edit.php?post_type=trg_service',
		'edit.php?post_type=trg_industry',
		'edit.php?post_type=trg_testimonial',
		'edit.php?post_type=' . TRG_ENQUIRY_POST_TYPE,
		'customize.php?autofocus[section]=trg_company',
		'trg-email',
	);

	usort(
		$submenu[ TRG_HUB_SLUG ], // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		static function ( $a, $b ) use ( $order ) {
			// Anything not on the list keeps to the end rather than jumping to
			// the front, so a menu item added later cannot displace these.
			$ia = array_search( $a[2], $order, true );
			$ib = array_search( $b[2], $order, true );
			$ia = false === $ia ? PHP_INT_MAX : $ia;
			$ib = false === $ib ? PHP_INT_MAX : $ib;
			return $ia <=> $ib;
		}
	);
}
add_action( 'admin_menu', 'trg_hub_links', 100 );

/**
 * Count the enquiries nobody has opened yet, so the hub can say so.
 *
 * @return int
 */
function trg_hub_new_enquiries() {
	if ( ! post_type_exists( TRG_ENQUIRY_POST_TYPE ) ) {
		return 0;
	}
	$counts = wp_count_posts( TRG_ENQUIRY_POST_TYPE );
	return isset( $counts->publish ) ? (int) $counts->publish : 0;
}

/**
 * Render one card on the hub screen.
 *
 * @param string $title Card heading.
 * @param string $body  One or two sentences of plain English.
 * @param string $url   Where the button goes.
 * @param string $cta   Button label.
 * @param string $note  Optional smaller line under the button.
 */
function trg_hub_card( $title, $body, $url, $cta, $note = '' ) {
	?>
	<div style="background:#fff;border:1px solid #dcdcde;border-radius:6px;padding:1.2em 1.4em;display:flex;flex-direction:column">
		<h2 style="margin:0 0 .4em;font-size:15px"><?php echo esc_html( $title ); ?></h2>
		<p style="margin:0 0 1.1em;color:#50575e;flex:1"><?php echo esc_html( $body ); ?></p>
		<p style="margin:0">
			<a class="button button-primary" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $cta ); ?></a>
		</p>
		<?php if ( $note ) : ?>
			<p style="margin:.7em 0 0;color:#646970;font-size:12px"><?php echo esc_html( $note ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * The hub screen: what you can change yourself, and where each thing lives.
 */
function trg_hub_page() {
	$pictures = count( trg_picture_slots() );
	$new      = trg_hub_new_enquiries();
	$services = wp_count_posts( 'trg_service' );
	$services = isset( $services->publish ) ? (int) $services->publish : 0;
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'TRG Website', 'trg-site' ); ?></h1>
		<p style="max-width:52em;font-size:14px">
			<?php esc_html_e( 'Everything on this page you can change yourself, without asking anybody. Nothing here can break the site: the words, the pictures and the lists are separate from the design, so editing them is safe.', 'trg-site' ); ?>
		</p>

		<?php /* No "email is not set up" notice here: trg_smtp_notice() already
		         prints one on every admin screen but its own, and two identical
		         warnings stacked on one page read as two separate faults. */ ?>

		<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(20em,1fr));gap:1em;max-width:70em;margin-top:1.5em">
			<?php
			trg_hub_card(
				__( 'Pictures', 'trg-site' ),
				__( 'Every picture on the site, numbered. Upload a replacement and it changes everywhere that picture is used. The original is never overwritten — you can always put it back.', 'trg-site' ),
				admin_url( 'admin.php?page=trg-pictures' ),
				__( 'Change a picture', 'trg-site' ),
				sprintf(
					/* translators: %d: number of picture slots. */
					__( '%d numbered slots.', 'trg-site' ),
					$pictures
				)
			);

			trg_hub_card(
				__( 'The words on a page', 'trg-site' ),
				__( 'Open any page and edit its text the way you would edit a document. Save, then click "View Page" to see it live.', 'trg-site' ),
				admin_url( 'edit.php?post_type=page' ),
				__( 'Edit a page', 'trg-site' ),
				__( 'Leave anything inside square brackets alone — those build the coloured bands.', 'trg-site' )
			);

			trg_hub_card(
				__( 'Services', 'trg-site' ),
				__( 'The list of services shown on the home page and on Services. Editing a title or description here changes it in both places at once.', 'trg-site' ),
				admin_url( 'edit.php?post_type=trg_service' ),
				__( 'Edit services', 'trg-site' ),
				sprintf(
					/* translators: %d: number of published services. */
					__( '%d services listed.', 'trg-site' ),
					$services
				)
			);

			trg_hub_card(
				__( 'Industries', 'trg-site' ),
				__( 'The industries band. Same idea as services — one list, shown in several places.', 'trg-site' ),
				admin_url( 'edit.php?post_type=trg_industry' ),
				__( 'Edit industries', 'trg-site' )
			);

			trg_hub_card(
				__( 'Enquiries', 'trg-site' ),
				__( 'Every message sent through the contact form is stored here, with the name, email, phone and what they asked about. Nothing is ever lost, even when email is down.', 'trg-site' ),
				admin_url( 'edit.php?post_type=' . TRG_ENQUIRY_POST_TYPE ),
				__( 'Read enquiries', 'trg-site' ),
				sprintf(
					/* translators: %d: number of stored enquiries. */
					_n( '%d message stored.', '%d messages stored.', $new, 'trg-site' ),
					$new
				)
			);

			trg_hub_card(
				__( 'Phone, address, email, social links', 'trg-site' ),
				__( 'These are held in one place and printed everywhere they appear — the header, the footer, the buttons and the contact page. Change the phone number once and it changes on every page.', 'trg-site' ),
				admin_url( 'customize.php?autofocus[section]=trg_company' ),
				__( 'Change contact details', 'trg-site' )
			);

			trg_hub_card(
				__( 'Testimonials', 'trg-site' ),
				__( 'Client quotes. They currently show without names, because the names have not been cleared for publication yet.', 'trg-site' ),
				admin_url( 'edit.php?post_type=trg_testimonial' ),
				__( 'Edit testimonials', 'trg-site' )
			);

			trg_hub_card(
				__( 'Email settings', 'trg-site' ),
				__( 'Where enquiry notifications are sent, and the mailbox the site sends them through.', 'trg-site' ),
				admin_url( 'admin.php?page=trg-email' ),
				__( 'Open email settings', 'trg-site' )
			);
			?>
		</div>

		<h2 style="margin-top:2em"><?php esc_html_e( 'Things worth knowing', 'trg-site' ); ?></h2>
		<div style="max-width:52em">
			<p>
				<strong><?php esc_html_e( 'You cannot break it by editing text.', 'trg-site' ); ?></strong>
				<?php esc_html_e( 'Every page keeps its own history. If an edit goes wrong, open the page, look under "Revisions", and put the previous version back.', 'trg-site' ); ?>
			</p>
			<p>
				<strong><?php esc_html_e( 'Square brackets are the layout.', 'trg-site' ); ?></strong>
				<?php esc_html_e( 'Text like [trg_hero title="..."] draws a coloured band. Change the words between the quote marks freely; do not delete the brackets themselves.', 'trg-site' ); ?>
			</p>
			<p>
				<strong><?php esc_html_e( 'Pictures are replaced, never overwritten.', 'trg-site' ); ?></strong>
				<?php esc_html_e( 'Uploading a new picture keeps the old one. "Put the original back" undoes it in one click.', 'trg-site' ); ?>
			</p>
			<p>
				<strong><?php esc_html_e( 'Ask before changing anything under Appearance, Plugins or Settings.', 'trg-site' ); ?></strong>
				<?php esc_html_e( 'Those control how the site is built rather than what it says. Everything on this screen is safe; those are not.', 'trg-site' ); ?>
			</p>
		</div>
	</div>
	<?php
}
