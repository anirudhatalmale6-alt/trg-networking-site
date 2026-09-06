<?php
/**
 * One-shot: swap the homepage hero's two cards for the four in the client's
 * mockup, and mark the fourth as the filled one.
 *
 * Targeted string replacement rather than re-running trg_run_setup(), so any
 * other edit the client has made to the homepage survives untouched. Refuses to
 * write unless it finds the exact string it expects.
 *
 * Delete after use.
 */

if ( ! isset( $_GET['t'] ) || 'g7Qm2xR9vLpA4' !== $_GET['t'] ) {
	http_response_code( 403 );
	exit( 'no' );
}

require_once __DIR__ . '/wp-load.php';
header( 'Content-Type: text/plain; charset=utf-8' );

$OLD = 'cards="Responsive support|Real people. Clear ownership.;Security first|Protection built in."';
$NEW = 'cards="Responsive support|Real people. Clear ownership.;24×7 Monitoring|Always-on protection.;'
	. 'CMMC Ready|DoD contractor experts.;Security first|Protection built in." cards_accent="4"';

$front = (int) get_option( 'page_on_front' );
if ( ! $front ) {
	exit( "FAIL: no static front page set\n" );
}

$post = get_post( $front );
echo "front page: #{$front} \"{$post->post_title}\"\n";
echo "content length before: " . strlen( $post->post_content ) . "\n";

if ( false !== strpos( $post->post_content, 'cards_accent' ) ) {
	exit( "ALREADY DONE - cards_accent is already in the content, nothing changed\n" );
}

$count = substr_count( $post->post_content, $OLD );
echo "occurrences of the old cards attribute: {$count}\n";
if ( 1 !== $count ) {
	exit( "FAIL: expected exactly 1, found {$count}. Nothing written.\n" );
}

$updated = str_replace( $OLD, $NEW, $post->post_content );

$res = wp_update_post( array(
	'ID'           => $front,
	'post_content' => $updated,
), true );

if ( is_wp_error( $res ) ) {
	exit( 'FAIL: ' . $res->get_error_message() . "\n" );
}

$after = get_post( $front );
echo "content length after : " . strlen( $after->post_content ) . "\n";
echo "cards_accent present : " . ( false !== strpos( $after->post_content, 'cards_accent="4"' ) ? 'yes' : 'NO' ) . "\n";
echo "all four titles present: ";
foreach ( array( 'Responsive support', '24×7 Monitoring', 'CMMC Ready', 'Security first' ) as $t ) {
	echo ( false !== strpos( $after->post_content, $t ) ? '[ok] ' : "[MISSING {$t}] " );
}
echo "\nOK\n";
