<?php
/**
 * Site header: the utility bar from the Lovable build sitting above the
 * Hostinger build's white sticky bar and blue call-to-action button.
 *
 * @package TRG_Networking
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-[100] focus:left-4 focus:top-4 focus:rounded-lg focus:bg-brand-600 focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white">
	<?php esc_html_e( 'Skip to content', 'trg-networking' ); ?>
</a>

<div class="hidden bg-navy text-white md:block">
	<div class="shell flex h-9 items-center justify-between text-[12.5px]">
		<p class="text-white/70"><?php echo esc_html( trg_company( 'tagline' ) ); ?></p>
		<div class="flex items-center gap-5">
			<a href="<?php echo esc_url( trg_phone_href() ); ?>" class="flex items-center gap-1.5 font-heading font-semibold hover:text-brand-200">
				<?php trg_icon( 'phone', 13 ); ?>
				<?php echo esc_html( trg_company( 'phone' ) ); ?>
			</a>
			<a href="<?php echo esc_url( trg_page_url( 'support-center' ) ); ?>" class="font-heading font-semibold text-white/85 hover:text-brand-200">
				<?php esc_html_e( 'Existing Client Support', 'trg-networking' ); ?>
			</a>
		</div>
	</div>
</div>

<header class="sticky top-0 z-50 border-b border-line bg-white transition-shadow duration-200" data-trg-header>
	<div class="shell flex h-[68px] items-center justify-between gap-4">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex shrink-0 items-center" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: company name. */ __( '%s — home', 'trg-networking' ), trg_company( 'name' ) ) ); ?>">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<img src="<?php echo esc_url( trg_asset( 'assets/img/logo-trg.webp' ) ); ?>"
					alt="<?php echo esc_attr( trg_company( 'name' ) ); ?>"
					width="587" height="216" class="h-9 w-auto sm:h-10">
			<?php endif; ?>
		</a>

		<?php trg_render_main_nav(); ?>

		<div class="flex shrink-0 items-center gap-2">
			<a href="<?php echo esc_url( trg_page_url( 'contact' ) ); ?>" class="btn-primary hidden sm:inline-flex">
				<?php esc_html_e( 'Talk With Our Team', 'trg-networking' ); ?>
			</a>
			<button type="button" class="rounded-lg border border-line p-2 text-ink lg:hidden"
				aria-label="<?php esc_attr_e( 'Open menu', 'trg-networking' ); ?>"
				aria-expanded="false" aria-controls="trg-drawer" data-trg-drawer-open>
				<?php trg_icon( 'menu', 20 ); ?>
			</button>
		</div>
	</div>
</header>

<div id="trg-drawer" class="fixed inset-0 z-[60] hidden lg:hidden" data-trg-drawer>
	<div class="absolute inset-0 bg-ink/45" data-trg-drawer-close aria-hidden="true"></div>
	<div class="absolute right-0 top-0 flex h-full w-[88%] max-w-sm flex-col bg-white shadow-2xl">
		<div class="flex h-[68px] shrink-0 items-center justify-between border-b border-line px-5">
			<img src="<?php echo esc_url( trg_asset( 'assets/img/logo-trg.webp' ) ); ?>"
				alt="<?php echo esc_attr( trg_company( 'name' ) ); ?>" class="h-9 w-auto">
			<button type="button" class="rounded-lg border border-line p-2 text-ink"
				aria-label="<?php esc_attr_e( 'Close menu', 'trg-networking' ); ?>" data-trg-drawer-close>
				<?php trg_icon( 'x', 20 ); ?>
			</button>
		</div>

		<nav class="flex-1 overflow-y-auto px-5 py-5" aria-label="<?php esc_attr_e( 'Mobile', 'trg-networking' ); ?>">
			<?php trg_render_mobile_nav(); ?>

			<div class="mt-6 space-y-3 border-t border-line pt-6">
				<a href="<?php echo esc_url( trg_page_url( 'contact' ) ); ?>" class="btn-primary w-full">
					<?php esc_html_e( 'Talk With Our Team', 'trg-networking' ); ?>
				</a>
				<a href="<?php echo esc_url( trg_phone_href() ); ?>" class="btn-outline w-full">
					<?php trg_icon( 'phone', 15 ); ?> <?php echo esc_html( trg_company( 'phone' ) ); ?>
				</a>
				<a href="<?php echo esc_url( trg_page_url( 'support-center' ) ); ?>" class="block px-1 pt-1 text-center text-sm font-semibold text-muted hover:text-brand-600">
					<?php esc_html_e( 'Existing Client Support', 'trg-networking' ); ?>
				</a>
			</div>
		</nav>
	</div>
</div>

<main id="main-content">
