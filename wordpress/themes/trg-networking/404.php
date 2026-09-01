<?php
/**
 * 404.
 *
 * This template sends a real 404 status — WordPress does that for us — and
 * offers the routes people actually wanted, because the site it replaces had
 * sixty indexed URLs and someone will always arrive on an old one.
 *
 * @package TRG_Networking
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<section class="relative overflow-hidden border-b border-line">
	<div class="pointer-events-none absolute inset-0" style="background:linear-gradient(160deg,#FFFFFF 0%,#F5F9FF 45%,#EFF6FF 100%)" aria-hidden="true"></div>
	<div class="shell relative py-16 sm:py-24">
		<div class="max-w-3xl">
			<span class="eyebrow-pill"><?php esc_html_e( 'Page not found', 'trg-networking' ); ?></span>
			<h1 class="mt-5 text-[34px] leading-[1.1] sm:text-[46px]"><?php esc_html_e( 'That page has moved or no longer exists.', 'trg-networking' ); ?></h1>
			<p class="mt-5 max-w-2xl text-[18px] leading-relaxed text-muted">
				<?php esc_html_e( 'Use the links below, or call us and a person will point you the right way.', 'trg-networking' ); ?>
			</p>
			<div class="mt-8 flex flex-col gap-3 sm:flex-row">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary"><?php esc_html_e( 'Back to the homepage', 'trg-networking' ); ?></a>
				<a href="<?php echo esc_url( trg_phone_href() ); ?>" class="btn-outline">
					<?php
					/* translators: %s: phone number. */
					printf( esc_html__( 'Call %s', 'trg-networking' ), esc_html( trg_company( 'phone' ) ) );
					?>
				</a>
			</div>
		</div>
	</div>
</section>

<section class="section bg-white">
	<div class="shell">
		<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
			<?php
			$suggestions = array(
				'services'    => __( 'All services', 'trg-networking' ),
				'industries'  => __( 'Industries we serve', 'trg-networking' ),
				'resources'   => __( 'Resources', 'trg-networking' ),
				'contact'     => __( 'Contact us', 'trg-networking' ),
			);
			foreach ( $suggestions as $slug => $label ) :
				?>
				<a href="<?php echo esc_url( trg_page_url( $slug ) ); ?>" class="card-hover group">
					<h2 class="text-[17px] group-hover:text-brand-600"><?php echo esc_html( $label ); ?></h2>
					<span class="mt-3 inline-flex items-center gap-1.5 font-heading text-[13px] font-bold text-brand-600">
						<?php esc_html_e( 'Go', 'trg-networking' ); ?>
						<?php trg_icon( 'arrow-right', 13 ); ?>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
get_footer();
