<?php
/**
 * Customizer panel for the company details used across the whole site.
 *
 * @package TRG_Networking
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the "TRG company details" section.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function trg_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'trg_company', array(
		'title'       => __( 'TRG company details', 'trg-networking' ),
		'priority'    => 25,
		'description' => __( 'Phone number, address and social profiles. Changing one here changes it in the header, the footer, the contact page and the search-engine markup at the same time.', 'trg-networking' ),
	) );

	$fields = array(
		'name'            => array( __( 'Company name', 'trg-networking' ), 'text' ),
		'legal_name'      => array( __( 'Legal name (footer copyright)', 'trg-networking' ), 'text' ),
		'founded'         => array( __( 'Year founded', 'trg-networking' ), 'text' ),
		'phone'           => array( __( 'Phone number', 'trg-networking' ), 'text' ),
		'email'           => array( __( 'Main email address', 'trg-networking' ), 'email' ),
		'marketing_email' => array( __( 'Marketing email address', 'trg-networking' ), 'email' ),
		'street'          => array( __( 'Street address', 'trg-networking' ), 'text' ),
		'city'            => array( __( 'City', 'trg-networking' ), 'text' ),
		'state'           => array( __( 'State', 'trg-networking' ), 'text' ),
		'state_short'     => array( __( 'State abbreviation', 'trg-networking' ), 'text' ),
		'zip'             => array( __( 'ZIP code', 'trg-networking' ), 'text' ),
		'tagline'         => array( __( 'Top bar tagline', 'trg-networking' ), 'text' ),
		'blurb'           => array( __( 'Footer blurb', 'trg-networking' ), 'textarea' ),
		'linkedin'        => array( __( 'LinkedIn URL', 'trg-networking' ), 'url' ),
		'facebook'        => array( __( 'Facebook URL', 'trg-networking' ), 'url' ),
		'twitter'         => array( __( 'X / Twitter URL', 'trg-networking' ), 'url' ),
		'youtube'         => array( __( 'YouTube URL', 'trg-networking' ), 'url' ),
	);

	$defaults = trg_company_defaults();

	foreach ( $fields as $key => $field ) {
		list( $label, $type ) = $field;

		$sanitize = 'sanitize_text_field';
		if ( 'url' === $type ) {
			$sanitize = 'esc_url_raw';
		} elseif ( 'email' === $type ) {
			$sanitize = 'sanitize_email';
		} elseif ( 'textarea' === $type ) {
			$sanitize = 'sanitize_textarea_field';
		}

		$wp_customize->add_setting( 'trg_' . $key, array(
			'default'           => isset( $defaults[ $key ] ) ? $defaults[ $key ] : '',
			'sanitize_callback' => $sanitize,
			'transport'         => 'refresh',
		) );

		$wp_customize->add_control( 'trg_' . $key, array(
			'label'   => $label,
			'section' => 'trg_company',
			'type'    => 'textarea' === $type ? 'textarea' : ( 'url' === $type ? 'url' : ( 'email' === $type ? 'email' : 'text' ) ),
		) );
	}
	// Note for whoever reads this next: a social field left blank hides that
	// icon entirely rather than rendering a link to "#". The Hostinger build
	// shipped four icons that all pointed at "#", which reads as broken.
}
add_action( 'customize_register', 'trg_customize_register' );
