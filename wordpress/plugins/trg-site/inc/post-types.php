<?php
/**
 * The three lists that repeat across the site — services, industries and
 * testimonials — as ordinary WordPress post types.
 *
 * Adding a service is then "add a post", with drag-to-reorder and the normal
 * publish/draft controls, instead of editing a list inside a page. The service
 * *pages* are still normal pages; these entries are the cards that link to them.
 *
 * @package TRG_Site
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the post types.
 */
function trg_register_post_types() {
	register_post_type( 'trg_service', array(
		'labels'          => array(
			'name'               => __( 'Services', 'trg-site' ),
			'singular_name'      => __( 'Service', 'trg-site' ),
			'add_new_item'       => __( 'Add service card', 'trg-site' ),
			'edit_item'          => __( 'Edit service card', 'trg-site' ),
			'menu_name'          => __( 'Service cards', 'trg-site' ),
			'not_found'          => __( 'No service cards yet.', 'trg-site' ),
		),
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => true,
		'menu_icon'       => 'dashicons-screenoptions',
		'menu_position'   => 21,
		'supports'        => array( 'title', 'editor', 'page-attributes' ),
		'has_archive'     => false,
		'rewrite'         => false,
		'show_in_rest'    => false,
		'capability_type' => 'page',
	) );

	register_post_type( 'trg_industry', array(
		'labels'          => array(
			'name'          => __( 'Industries', 'trg-site' ),
			'singular_name' => __( 'Industry', 'trg-site' ),
			'add_new_item'  => __( 'Add industry card', 'trg-site' ),
			'edit_item'     => __( 'Edit industry card', 'trg-site' ),
			'menu_name'     => __( 'Industry cards', 'trg-site' ),
		),
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => true,
		'menu_icon'       => 'dashicons-building',
		'menu_position'   => 22,
		'supports'        => array( 'title', 'editor', 'page-attributes' ),
		'has_archive'     => false,
		'rewrite'         => false,
		'show_in_rest'    => false,
		'capability_type' => 'page',
	) );

	register_post_type( 'trg_testimonial', array(
		'labels'          => array(
			'name'          => __( 'Testimonials', 'trg-site' ),
			'singular_name' => __( 'Testimonial', 'trg-site' ),
			'add_new_item'  => __( 'Add testimonial', 'trg-site' ),
			'edit_item'     => __( 'Edit testimonial', 'trg-site' ),
			'menu_name'     => __( 'Testimonials', 'trg-site' ),
		),
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => true,
		'menu_icon'       => 'dashicons-format-quote',
		'menu_position'   => 23,
		'supports'        => array( 'title', 'editor', 'page-attributes' ),
		'has_archive'     => false,
		'rewrite'         => false,
		'show_in_rest'    => false,
		'capability_type' => 'page',
	) );
}
add_action( 'init', 'trg_register_post_types' );

/**
 * Extra fields for each card type.
 *
 * @return array<string,array<string,array{label:string,type:string,help:string}>>
 */
function trg_card_fields() {
	return array(
		'trg_service'     => array(
			'_trg_icon' => array(
				'label' => __( 'Icon', 'trg-site' ),
				'type'  => 'select',
				'help'  => __( 'Shown in the tinted square at the top of the card.', 'trg-site' ),
			),
			'_trg_link' => array(
				'label' => __( 'Links to', 'trg-site' ),
				'type'  => 'page',
				'help'  => __( 'The service page this card opens.', 'trg-site' ),
			),
		),
		'trg_industry'    => array(
			'_trg_tags' => array(
				'label' => __( 'Small print under the title', 'trg-site' ),
				'type'  => 'text',
				'help'  => __( 'For example: CMMC, NIST 800-171, CUI protection', 'trg-site' ),
			),
			'_trg_link' => array(
				'label' => __( 'Links to', 'trg-site' ),
				'type'  => 'page',
				'help'  => __( 'The industry page this row opens.', 'trg-site' ),
			),
		),
		'trg_testimonial' => array(
			'_trg_org' => array(
				'label' => __( 'Company', 'trg-site' ),
				'type'  => 'text',
				'help'  => __( 'Shown under the person’s name. Leave blank if they asked not to be named.', 'trg-site' ),
			),
		),
	);
}

/**
 * Register the meta boxes.
 */
function trg_card_meta_boxes() {
	foreach ( trg_card_fields() as $post_type => $fields ) {
		add_meta_box(
			'trg_card_meta',
			__( 'Card details', 'trg-site' ),
			'trg_card_meta_render',
			$post_type,
			'side',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'trg_card_meta_boxes' );

/**
 * Render the meta box.
 *
 * @param WP_Post $post Current post.
 */
function trg_card_meta_render( $post ) {
	$all = trg_card_fields();
	if ( ! isset( $all[ $post->post_type ] ) ) {
		return;
	}

	wp_nonce_field( 'trg_card_meta_save', 'trg_card_meta_nonce' );

	foreach ( $all[ $post->post_type ] as $key => $field ) {
		$value = get_post_meta( $post->ID, $key, true );
		$id    = esc_attr( $key );

		echo '<p><label for="' . $id . '"><strong>' . esc_html( $field['label'] ) . '</strong></label><br>';

		if ( 'select' === $field['type'] ) {
			echo '<select id="' . $id . '" name="' . $id . '" class="widefat">';
			foreach ( trg_icon_choices() as $icon => $label ) {
				printf(
					'<option value="%s" %s>%s</option>',
					esc_attr( $icon ),
					selected( $value, $icon, false ),
					esc_html( $label )
				);
			}
			echo '</select>';
		} elseif ( 'page' === $field['type'] ) {
			wp_dropdown_pages( array(
				'name'              => $id,
				'id'                => $id,
				'selected'          => (int) $value,
				'show_option_none'  => __( '— no link —', 'trg-site' ),
				'option_none_value' => '0',
				'class'             => 'widefat',
			) );
		} else {
			echo '<input type="text" id="' . $id . '" name="' . $id . '" class="widefat" value="' . esc_attr( $value ) . '">';
		}

		echo '<span class="description">' . esc_html( $field['help'] ) . '</span></p>';
	}

	echo '<p class="description">' . esc_html__( 'Use the Order field under Page Attributes to change where this card appears.', 'trg-site' ) . '</p>';
}

/**
 * Save the meta box.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function trg_card_meta_save( $post_id, $post ) {
	$all = trg_card_fields();
	if ( ! isset( $all[ $post->post_type ] ) ) {
		return;
	}
	if ( ! isset( $_POST['trg_card_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['trg_card_meta_nonce'] ) ), 'trg_card_meta_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( $all[ $post->post_type ] as $key => $field ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}
		$raw = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );

		if ( 'page' === $field['type'] ) {
			$raw = (string) absint( $raw );
		} elseif ( 'select' === $field['type'] && ! array_key_exists( $raw, trg_icon_choices() ) ) {
			$raw = 'check';
		}

		if ( '' === $raw || '0' === $raw ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $raw );
		}
	}
}
add_action( 'save_post', 'trg_card_meta_save', 10, 2 );

/**
 * Fetch published cards of one type, in their admin order.
 *
 * @param string $post_type Post type name.
 * @param int    $limit     Maximum number of cards.
 * @param int    $exclude   Post ID to leave out.
 * @return WP_Post[]
 */
function trg_get_cards( $post_type, $limit = -1, $exclude = 0 ) {
	return get_posts( array(
		'post_type'        => $post_type,
		'post_status'      => 'publish',
		'numberposts'      => $limit,
		'orderby'          => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
		'exclude'          => $exclude ? array( (int) $exclude ) : array(),
		'suppress_filters' => false,
	) );
}

/**
 * The URL a card links to.
 *
 * Returns an empty string when no page is chosen, and callers render a plain
 * card rather than a link — a card that looks clickable and goes nowhere reads
 * as a broken feature.
 *
 * @param WP_Post $card Card post.
 * @return string
 */
function trg_card_link( $card ) {
	$page_id = (int) get_post_meta( $card->ID, '_trg_link', true );
	if ( ! $page_id ) {
		return '';
	}
	$url = get_permalink( $page_id );
	return $url ? $url : '';
}

/**
 * A card's body text, with block markup and shortcodes stripped.
 *
 * @param WP_Post $card Card post.
 * @return string
 */
function trg_card_body( $card ) {
	$content = strip_shortcodes( $card->post_content );
	$content = excerpt_remove_blocks( $content );
	return trim( wp_strip_all_tags( $content ) );
}
