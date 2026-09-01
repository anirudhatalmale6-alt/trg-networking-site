<?php
/**
 * Navigation rendering.
 *
 * The header markup has enough structure (a dropdown panel with its own "All
 * Services" link and a divider) that extending Walker_Nav_Menu would be more
 * indirection than it is worth. These functions read the assigned menu, build a
 * two-level tree and print it, so what you see here is what ships.
 *
 * @package TRG_Networking
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fetch a menu location as a two-level tree.
 *
 * @param string $location Registered menu location.
 * @return array<int,array{title:string,url:string,current:bool,children:array}>
 */
function trg_menu_tree( $location ) {
	$locations = get_nav_menu_locations();
	if ( empty( $locations[ $location ] ) ) {
		return array();
	}

	$items = wp_get_nav_menu_items( $locations[ $location ] );
	if ( ! $items ) {
		return array();
	}

	$by_id = array();
	foreach ( $items as $item ) {
		$by_id[ $item->ID ] = array(
			'id'       => $item->ID,
			'parent'   => (int) $item->menu_item_parent,
			'title'    => $item->title,
			'url'      => $item->url,
			'current'  => in_array( 'current-menu-item', (array) $item->classes, true )
				|| in_array( 'current-menu-ancestor', (array) $item->classes, true )
				|| in_array( 'current_page_item', (array) $item->classes, true )
				|| in_array( 'current_page_ancestor', (array) $item->classes, true ),
			'children' => array(),
		);
	}

	// Two passes and no references. The reference-into-the-array trick for
	// building trees is a known PHP footgun, and the menu is only ever two
	// levels deep, so it buys nothing here.
	$children = array();
	$tree     = array();
	foreach ( $by_id as $item ) {
		if ( $item['parent'] && isset( $by_id[ $item['parent'] ] ) ) {
			$children[ $item['parent'] ][] = $item;
		} else {
			$tree[] = $item;
		}
	}

	foreach ( $tree as $index => $top ) {
		$tree[ $index ]['children'] = isset( $children[ $top['id'] ] ) ? $children[ $top['id'] ] : array();
		// A parent is "current" when one of its children is.
		foreach ( $tree[ $index ]['children'] as $child ) {
			if ( $child['current'] ) {
				$tree[ $index ]['current'] = true;
			}
		}
	}

	return $tree;
}

/**
 * Desktop main navigation.
 */
function trg_render_main_nav() {
	$tree = trg_menu_tree( 'primary' );
	if ( ! $tree ) {
		return;
	}
	?>
	<nav class="hidden min-w-0 flex-1 items-center justify-center lg:flex" aria-label="<?php esc_attr_e( 'Main', 'trg-networking' ); ?>" data-trg-nav>
		<ul class="flex items-center gap-0.5">
			<?php foreach ( $tree as $i => $item ) : ?>
				<li class="relative">
					<?php if ( $item['children'] ) : ?>
						<button
							type="button"
							class="flex items-center gap-1 rounded-md px-3 py-2 font-heading text-[14.5px] font-semibold transition-colors <?php echo $item['current'] ? 'text-brand-600' : 'text-body hover:text-brand-600'; ?>"
							aria-expanded="false"
							aria-controls="trg-menu-<?php echo (int) $i; ?>"
							data-trg-dropdown-toggle
						>
							<?php echo esc_html( $item['title'] ); ?>
							<?php trg_icon( 'chevron-down', 14, 'transition-transform' ); ?>
						</button>
						<div
							id="trg-menu-<?php echo (int) $i; ?>"
							class="absolute left-1/2 top-full z-50 mt-1 hidden w-[290px] -translate-x-1/2 rounded-xl border border-line bg-white p-2 shadow-[0_18px_44px_-16px_rgba(15,23,42,0.3)]"
							data-trg-dropdown-panel
						>
							<a href="<?php echo esc_url( $item['url'] ); ?>" class="block rounded-lg px-3 py-2 font-heading text-[13px] font-bold uppercase tracking-wider text-brand-600 hover:bg-brand-50">
								<?php
								/* translators: %s: menu section name, e.g. "Services". */
								printf( esc_html__( 'All %s', 'trg-networking' ), esc_html( $item['title'] ) );
								?>
							</a>
							<div class="my-1 h-px bg-line"></div>
							<?php foreach ( $item['children'] as $child ) : ?>
								<a href="<?php echo esc_url( $child['url'] ); ?>" class="block rounded-lg px-3 py-2 text-[14px] text-body hover:bg-brand-50 hover:text-brand-600">
									<?php echo esc_html( $child['title'] ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					<?php else : ?>
						<a
							href="<?php echo esc_url( $item['url'] ); ?>"
							class="block rounded-md px-3 py-2 font-heading text-[14.5px] font-semibold transition-colors <?php echo $item['current'] ? 'text-brand-600' : 'text-body hover:text-brand-600'; ?>"
							<?php echo $item['current'] ? 'aria-current="page"' : ''; ?>
						>
							<?php echo esc_html( $item['title'] ); ?>
						</a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php
}

/**
 * Mobile drawer navigation.
 */
function trg_render_mobile_nav() {
	$tree = trg_menu_tree( 'primary' );
	if ( ! $tree ) {
		return;
	}
	?>
	<ul class="space-y-1">
		<?php foreach ( $tree as $item ) : ?>
			<li>
				<a href="<?php echo esc_url( $item['url'] ); ?>" class="block rounded-lg px-3 py-2.5 font-heading text-[15px] font-bold text-ink hover:bg-brand-50">
					<?php echo esc_html( $item['title'] ); ?>
				</a>
				<?php if ( $item['children'] ) : ?>
					<ul class="mb-2 ml-3 border-l border-line pl-3">
						<?php foreach ( $item['children'] as $child ) : ?>
							<li>
								<a href="<?php echo esc_url( $child['url'] ); ?>" class="block rounded-lg px-3 py-2 text-[14px] text-muted hover:bg-brand-50 hover:text-brand-600">
									<?php echo esc_html( $child['title'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
}

/**
 * A footer link column.
 *
 * @param string $location Menu location.
 * @param string $title    Column heading.
 */
function trg_render_footer_column( $location, $title ) {
	$tree = trg_menu_tree( $location );
	if ( ! $tree ) {
		return;
	}
	?>
	<div>
		<h2 class="mb-4 font-heading text-[12px] font-bold uppercase tracking-[0.14em] text-white"><?php echo esc_html( $title ); ?></h2>
		<ul class="space-y-2.5">
			<?php foreach ( $tree as $item ) : ?>
				<li>
					<a href="<?php echo esc_url( $item['url'] ); ?>" class="text-[14px] text-white/65 transition-colors hover:text-white">
						<?php echo esc_html( $item['title'] ); ?>
					</a>
				</li>
				<?php foreach ( $item['children'] as $child ) : ?>
					<li>
						<a href="<?php echo esc_url( $child['url'] ); ?>" class="text-[14px] text-white/65 transition-colors hover:text-white">
							<?php echo esc_html( $child['title'] ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
}
