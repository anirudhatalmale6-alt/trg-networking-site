<?php
/**
 * One-shot: put the client's industry photos into the media library and point
 * the matching TRG Pictures slots at them.
 *
 * Goes through the media library rather than dropping files in the theme, so
 * the pictures appear in TRG Pictures like any he uploads himself and he can
 * swap or reset them without me.
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

echo "gd:       " . ( extension_loaded( 'gd' ) ? 'on' : 'OFF' ) . "\n";
echo "fileinfo: " . ( extension_loaded( 'fileinfo' ) ? 'on' : 'OFF' ) . "\n";
echo "upload_max_filesize: " . ini_get( 'upload_max_filesize' ) . "\n\n";

$src_dir = __DIR__ . '/trg-industry-src';
$files   = array(
	'pg-professional-services' => array( 'pg-professional-services.jpg', 'An advisor working at a desk with cloud and network graphics' ),
	'pg-government-contractors' => array( 'pg-government-contractors.jpg', 'A team meeting around a conference table with laptops' ),
	'pg-construction'          => array( 'pg-construction.jpg', 'Construction site workers reviewing plans on a laptop' ),
);

$map = (array) get_option( TRG_PICTURES_OPTION, array() );

foreach ( $files as $slot => $spec ) {
	list( $name, $alt ) = $spec;
	$path = $src_dir . '/' . $name;

	if ( ! file_exists( $path ) ) {
		echo "{$slot}: SOURCE MISSING ({$path})\n";
		continue;
	}

	// Copy into a temp name: media_handle_sideload MOVES the file it is given.
	$tmp = wp_tempnam( $name );
	copy( $path, $tmp );

	$id = media_handle_sideload(
		array( 'name' => $name, 'tmp_name' => $tmp ),
		0,
		$alt
	);

	if ( is_wp_error( $id ) ) {
		@unlink( $tmp );
		echo "{$slot}: FAILED - " . $id->get_error_message() . "\n";
		continue;
	}

	update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	$map[ $slot ] = (int) $id;

	$meta = wp_get_attachment_metadata( $id );
	echo "{$slot}: attachment #{$id}  "
		. ( isset( $meta['width'] ) ? $meta['width'] . 'x' . $meta['height'] : 'no metadata' )
		. "  " . wp_get_attachment_image_url( $id, 'full' ) . "\n";
}

update_option( TRG_PICTURES_OPTION, $map );

echo "\nslots now pointing somewhere:\n";
foreach ( trg_picture_overrides() as $k => $v ) {
	echo "  {$k} -> #{$v} " . ( trg_picture_override_url( $k ) ?: 'URL RESOLVES TO NOTHING' ) . "\n";
}
echo "\nOK\n";
