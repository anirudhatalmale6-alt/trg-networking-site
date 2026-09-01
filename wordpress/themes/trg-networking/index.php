<?php
/**
 * Fallback template — blog index, archives and search results.
 *
 * The merged site is a brochure site, but WordPress needs this file and the
 * client may add posts later, so it is a real listing rather than a stub.
 *
 * @package TRG_Networking
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<section class="relative overflow-hidden border-b border-line">
	<div class="pointer-events-none absolute inset-0" style="background:linear-gradient(160deg,#FFFFFF 0%,#F5F9FF 45%,#EFF6FF 100%)" aria-hidden="true"></div>
	<div class="shell relative py-14 sm:py-20">
		<div class="max-w-3xl">
			<h1 class="text-[34px] leading-[1.1] sm:text-[46px]">
				<?php
				if ( is_search() ) {
					/* translators: %s: search term. */
					printf( esc_html__( 'Search results for “%s”', 'trg-networking' ), esc_html( get_search_query() ) );
				} elseif ( is_archive() ) {
					the_archive_title();
				} else {
					echo esc_html( get_the_title( (int) get_option( 'page_for_posts' ) ) ?: __( 'Insights', 'trg-networking' ) );
				}
				?>
			</h1>
		</div>
	</div>
</section>

<section class="section bg-white">
	<div class="shell">
		<?php if ( have_posts() ) : ?>
			<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'card-hover group flex flex-col' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="mb-4 block overflow-hidden rounded-lg" tabindex="-1" aria-hidden="true">
								<?php the_post_thumbnail( 'medium_large', array( 'class' => 'aspect-[16/9] w-full object-cover', 'loading' => 'lazy' ) ); ?>
							</a>
						<?php endif; ?>
						<h2 class="text-[18px] group-hover:text-brand-600">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
						<p class="mt-2 flex-1 text-[15px] leading-relaxed text-muted"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26 ) ); ?></p>
						<span class="mt-4 inline-flex items-center gap-1.5 font-heading text-[13.5px] font-bold text-brand-600">
							<?php esc_html_e( 'Read more', 'trg-networking' ); ?>
							<?php trg_icon( 'arrow-right', 14 ); ?>
						</span>
					</article>
					<?php
				endwhile;
				?>
			</div>

			<div class="mt-12">
				<?php
				the_posts_pagination( array(
					'mid_size'  => 2,
					'prev_text' => esc_html__( 'Previous', 'trg-networking' ),
					'next_text' => esc_html__( 'Next', 'trg-networking' ),
				) );
				?>
			</div>
		<?php else : ?>
			<p class="text-[17px] text-muted"><?php esc_html_e( 'Nothing found here yet.', 'trg-networking' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
