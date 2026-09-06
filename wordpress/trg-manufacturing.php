<?php
/**
 * One-shot: put the free-to-use photograph into the Manufacturing slot, with the
 * licence credit stored on the attachment itself.
 *
 * The credit goes in the Caption field, which is what trg_image_credit() reads.
 * Store it there and not in the page, so that when TRG replaces this picture
 * with one of their own the credit line disappears with it.
 *
 * Delete after use.
 */

if ( ! isset( $_GET['t'] ) || 'g7Qm2xR9vLpA4' !== $_GET['t'] ) {
	http_response_code( 403 );
	exit( 'no' );
}

require_once __DIR__ . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
header( 'Content-Type: text/plain; charset=utf-8' );

$name   = 'pg-manufacturing.jpg';
$path   = __DIR__ . '/trg-industry-src/' . $name;
$alt    = 'A worker operating a machine on a factory floor';
$credit = 'Photograph: Shixart1985, via Wikimedia Commons, CC BY 2.0';

if ( ! file_exists( $path ) ) {
	exit( "SOURCE MISSING: {$path}\n" );
}

$tmp = wp_tempnam( $name );
copy( $path, $tmp );

$id = media_handle_sideload( array( 'name' => $name, 'tmp_name' => $tmp ), 0, $alt );
if ( is_wp_error( $id ) ) {
	@unlink( $tmp );
	exit( 'FAILED: ' . $id->get_error_message() . "\n" );
}

update_post_meta( $id, '_wp_attachment_image_alt', $alt );

// post_excerpt IS the Caption field in the media library.
wp_update_post( array( 'ID' => $id, 'post_excerpt' => $credit ) );

$map                     = (array) get_option( TRG_PICTURES_OPTION, array() );
$map['pg-manufacturing'] = (int) $id;
update_option( TRG_PICTURES_OPTION, $map );

$meta = wp_get_attachment_metadata( $id );
echo "attachment  : #{$id}\n";
echo "dimensions  : " . ( isset( $meta['width'] ) ? $meta['width'] . 'x' . $meta['height'] : 'no metadata' ) . "\n";
echo "url         : " . wp_get_attachment_image_url( $id, 'full' ) . "\n";
echo "slot url    : " . trg_picture_override_url( 'pg-manufacturing' ) . "\n";
echo "slot id     : " . trg_picture_override_id( 'pg-manufacturing' ) . "\n";
echo "credit read : " . trg_image_credit( 'pg-manufacturing' ) . "\n";
echo "\ncredit on a slot that should have none:\n";
foreach ( array( 'pg-construction', 'pg-government-contractors', 'hero-team' ) as $k ) {
	echo "  {$k}: '" . trg_image_credit( $k ) . "'\n";
}
echo "\nOK\n";
