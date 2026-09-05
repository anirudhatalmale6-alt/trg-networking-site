<?php
/**
 * Site footer.
 *
 * @package TRG_Networking
 */

defined( 'ABSPATH' ) || exit;
?>
</main>

<footer class="bg-navy pb-8 pt-16 text-white">
	<div class="shell">
		<div class="grid gap-10 lg:grid-cols-[1.6fr_1fr_1fr_1fr_1.3fr]">
			<div>
				<?php
				/*
				 * A white knock-out of the real wordmark. The image the source
				 * build used here, logo-footer.webp, was not a logo at all — it
				 * was a watermarked stock photo, so the footer showed a photo of
				 * strangers where the company logo belongs. It is not shipped.
				 */
				?>
				<img src="<?php echo esc_url( trg_asset( 'assets/img/logo-white.webp' ) ); ?>"
					alt="<?php echo esc_attr( trg_company( 'name' ) ); ?>"
					width="507" height="174" loading="lazy" class="mb-5 h-11 w-auto">

				<p class="max-w-xs text-[14px] leading-relaxed text-white/65">
					<?php echo esc_html( trg_company( 'blurb' ) ); ?>
				</p>

				<?php $socials = trg_social_profiles(); ?>
				<?php if ( $socials ) : ?>
					<ul class="mt-5 flex gap-2">
						<?php foreach ( $socials as $social ) : ?>
							<li>
								<a href="<?php echo esc_url( $social['url'] ); ?>" target="_blank" rel="noopener noreferrer"
									aria-label="<?php echo esc_attr( sprintf( /* translators: 1: company name, 2: network name. */ __( '%1$s on %2$s', 'trg-networking' ), trg_company( 'name' ), $social['label'] ) ); ?>"
									class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-white/15 bg-white/5 text-white/80 transition-colors hover:border-brand-400 hover:bg-brand-600 hover:text-white">
									<?php trg_icon( $social['icon'], 17 ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<?php
			trg_render_footer_column( 'footer_services', __( 'Services', 'trg-networking' ) );
			trg_render_footer_column( 'footer_industries', __( 'Industries', 'trg-networking' ) );
			trg_render_footer_column( 'footer_company', __( 'Company', 'trg-networking' ) );
			?>

			<div>
				<h2 class="mb-4 font-heading text-[12px] font-bold uppercase tracking-[0.14em] text-white">
					<?php esc_html_e( 'Contact', 'trg-networking' ); ?>
				</h2>
				<ul class="space-y-3 text-[14px] text-white/65">
					<li>
						<a href="<?php echo esc_url( trg_phone_href() ); ?>" class="flex items-start gap-2.5 hover:text-white">
							<?php trg_icon( 'phone', 15, 'mt-0.5 shrink-0 text-brand-400' ); ?>
							<?php echo esc_html( trg_company( 'phone' ) ); ?>
						</a>
					</li>
					<li>
						<?php
						/*
						 * break-words, not break-all: break-all splits the address
						 * mid-word ("marketing@trgnetworking.c / om"), which is how
						 * the longer marketing@ address rendered in the footer.
						 * min-w-0 lets the flex child shrink so it wraps at all.
						 */
						?>
						<a href="mailto:<?php echo esc_attr( trg_company( 'email' ) ); ?>" class="flex items-start gap-2.5 hover:text-white">
							<?php trg_icon( 'mail', 15, 'mt-0.5 shrink-0 text-brand-400' ); ?>
							<span class="min-w-0 break-words"><?php echo esc_html( trg_company( 'email' ) ); ?></span>
						</a>
					</li>
					<li class="flex items-start gap-2.5">
						<?php trg_icon( 'map-pin', 15, 'mt-0.5 shrink-0 text-brand-400' ); ?>
						<span><?php echo esc_html( trg_address_line() ); ?></span>
					</li>
				</ul>
				<a href="<?php echo esc_url( trg_page_url( 'support-center' ) ); ?>" class="btn-ghost-l mt-5 w-full">
					<?php esc_html_e( 'Existing client support', 'trg-networking' ); ?>
				</a>
			</div>
		</div>

		<div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-6 sm:flex-row">
			<p class="text-[13px] text-white/50">
				<?php
				printf(
					/* translators: 1: current year, 2: legal company name. */
					esc_html__( '© %1$s %2$s All rights reserved. · Women / Minority Owned', 'trg-networking' ),
					esc_html( gmdate( 'Y' ) ),
					esc_html( trg_company( 'legal_name' ) )
				);
				?>
			</p>
			<?php $legal = trg_menu_tree( 'legal' ); ?>
			<?php if ( $legal ) : ?>
				<ul class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-[13px] text-white/50">
					<?php foreach ( $legal as $item ) : ?>
						<li><a href="<?php echo esc_url( $item['url'] ); ?>" class="hover:text-white"><?php echo esc_html( $item['title'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
