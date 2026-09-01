<?php
/**
 * Single post.
 *
 * @package TRG_Networking
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class(); ?>>
		<section class="relative overflow-hidden border-b border-line">
			<div class="pointer-events-none absolute inset-0" style="background:linear-gradient(160deg,#FFFFFF 0%,#F5F9FF 45%,#EFF6FF 100%)" aria-hidden="true"></div>
			<div class="shell relative py-14 sm:py-20">
				<div class="max-w-3xl">
					<span class="eyebrow-pill"><?php echo esc_html( get_the_date() ); ?></span>
					<h1 class="mt-5 text-[34px] leading-[1.1] sm:text-[46px]"><?php the_title(); ?></h1>
				</div>
			</div>
		</section>

		<section class="section bg-white">
			<div class="shell">
				<div class="trg-prose mx-auto max-w-3xl">
					<?php the_content(); ?>
				</div>
			</div>
		</section>
	</article>
	<?php
endwhile;

echo do_shortcode( '[trg_cta_band]' );

get_footer();
