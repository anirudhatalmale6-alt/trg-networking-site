<?php
/**
 * Page template.
 *
 * Layout comes from the blocks and section shortcodes inside the page itself,
 * so there is nothing to configure here. The one thing this template guarantees
 * is that every page has exactly one <h1>: pages built with the TRG hero block
 * already have theirs, and anything else gets a plain hero built from the page
 * title rather than rendering with no heading at all.
 *
 * @package TRG_Networking
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$has_hero = has_shortcode( get_the_content(), 'trg_hero' ) || has_shortcode( get_the_content(), 'trg_home_hero' );

	if ( ! $has_hero ) :
		?>
		<section class="relative overflow-hidden border-b border-line">
			<div class="pointer-events-none absolute inset-0" style="background:linear-gradient(160deg,#FFFFFF 0%,#F5F9FF 45%,#EFF6FF 100%)" aria-hidden="true"></div>
			<div class="shell relative py-14 sm:py-20">
				<div class="max-w-3xl">
					<h1 class="text-[34px] leading-[1.1] sm:text-[46px] lg:text-[52px]"><?php the_title(); ?></h1>
					<?php if ( has_excerpt() ) : ?>
						<p class="mt-5 max-w-2xl text-[18px] leading-relaxed text-muted"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	endif;

	if ( $has_hero ) {
		the_content();
	} else {
		?>
		<section class="section bg-white">
			<div class="shell">
				<div class="trg-prose mx-auto max-w-3xl">
					<?php the_content(); ?>
				</div>
			</div>
		</section>
		<?php
	}

endwhile;

get_footer();
