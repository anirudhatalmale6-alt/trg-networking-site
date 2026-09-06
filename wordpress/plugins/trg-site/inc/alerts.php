<?php
/**
 * Security news and alerts: fetch, hold, approve, publish.
 *
 * The rule this file exists to enforce is that NOTHING reaches the website on
 * its own. Everything fetched lands in a holding list as a pending post and
 * stays there until a person clicks Approve. An MSP that lets a robot put
 * unread security claims on its own front page has a worse problem than an
 * empty news section.
 *
 * Sources were chosen by measuring, not by reputation. CISA's RSS feeds — the
 * obvious first choice — return 403 to any non-browser client, from this
 * server as well as from mine; every `cisa.gov/*.xml` path is behind the same
 * CDN block. CISA's Known Exploited Vulnerabilities catalogue is plain JSON on
 * a different path and answers fine, and it is the better feed anyway: it is
 * the list of things actually being exploited, with the federal patch
 * deadline attached, which is exactly the conversation TRG has with a defence
 * contractor.
 *
 * @package TRG_Site
 */

defined( 'ABSPATH' ) || exit;

const TRG_ALERT_POST_TYPE = 'trg_alert';
const TRG_ALERTS_OPTION   = 'trg_alerts';
const TRG_ALERTS_CRON     = 'trg_alerts_fetch_event';

/**
 * Where the news comes from.
 *
 * `url` is what we ask for; `fallback` is tried only when the first answer is
 * not a 200, so a bad afternoon at one host does not silently empty the queue.
 *
 * @return array
 */
function trg_alert_sources() {
	/*
	 * The NVD window is computed, not written down, and that is a correction
	 * rather than a flourish. Asking for critical CVEs without a date range
	 * returns them in the database's own order, which is oldest first: the
	 * first page came back full of 2002. Sorting afterwards cannot help — the
	 * whole page was old. Two weeks is a comfortable window; the API caps a
	 * single query at 120 days.
	 */
	$nvd_from = gmdate( 'Y-m-d\T00:00:00.000', time() - ( 14 * DAY_IN_SECONDS ) );
	$nvd_to   = gmdate( 'Y-m-d\TH:i:s.000' );

	return array(
		'kev'  => array(
			'label'    => __( 'CISA — actively exploited vulnerabilities', 'trg-site' ),
			'note'     => __( 'The federal Known Exploited Vulnerabilities catalogue. Things being exploited right now, each with the date agencies must patch it by.', 'trg-site' ),
			'url'      => 'https://www.cisa.gov/sites/default/files/feeds/known_exploited_vulnerabilities.json',
			'fallback' => 'https://raw.githubusercontent.com/cisagov/kev-data/develop/known_exploited_vulnerabilities.json',
			'parser'   => 'trg_alert_parse_kev',
			'home'     => 'https://www.cisa.gov/known-exploited-vulnerabilities-catalog',
		),
		'msrc' => array(
			'label'    => __( 'Microsoft security updates', 'trg-site' ),
			'note'     => __( "Microsoft's monthly security release summary. Relevant to every TRG client running Microsoft 365 or Windows.", 'trg-site' ),
			'url'      => 'https://api.msrc.microsoft.com/cvrf/v3.0/updates',
			'fallback' => '',
			'parser'   => 'trg_alert_parse_msrc',
			'home'     => 'https://msrc.microsoft.com/update-guide',
		),
		'sans' => array(
			'label'    => __( 'SANS Internet Storm Center', 'trg-site' ),
			'note'     => __( 'Daily practitioner commentary on what is happening in the wild. Written by analysts, not by a vendor.', 'trg-site' ),
			'url'      => 'https://isc.sans.edu/rssfeed.xml',
			'fallback' => '',
			'parser'   => 'trg_alert_parse_rss',
			'home'     => 'https://isc.sans.edu/',
		),
		'nvd'  => array(
			'label'    => __( 'NVD — critical CVEs', 'trg-site' ),
			'note'     => __( 'Newly published vulnerabilities rated critical, from the US National Vulnerability Database.', 'trg-site' ),
			'url'      => 'https://services.nvd.nist.gov/rest/json/cves/2.0?cvssV3Severity=CRITICAL&noRejected&resultsPerPage=20'
				. '&pubStartDate=' . rawurlencode( $nvd_from ) . '&pubEndDate=' . rawurlencode( $nvd_to ),
			'fallback' => '',
			'parser'   => 'trg_alert_parse_nvd',
			'home'     => 'https://nvd.nist.gov/',
		),
	);
}

/**
 * Saved settings, with the shipped defaults filled in.
 *
 * @return array
 */
function trg_alerts_settings() {
	$saved = get_option( TRG_ALERTS_OPTION, array() );
	if ( ! is_array( $saved ) ) {
		$saved = array();
	}

	$defaults = array(
		// Only the two lowest-noise sources are on out of the box. Turning a
		// source on is one click; wading through four hundred queued items on
		// the first morning is not.
		'sources'   => array( 'kev', 'msrc' ),
		'frequency' => 'daily',
		'per_run'   => 8,
		'show'      => 6,
		'last_run'  => 0,
		'status'    => array(),
	);

	return array_merge( $defaults, $saved );
}

/**
 * The post type. Not public: an alert is a pointer to somebody else's
 * advisory, so it has no page of its own to be indexed instead of theirs.
 */
function trg_alerts_register() {
	register_post_type( TRG_ALERT_POST_TYPE, array(
		'labels'          => array(
			'name'          => __( 'Security alerts', 'trg-site' ),
			'singular_name' => __( 'Security alert', 'trg-site' ),
		),
		'public'          => false,
		'show_ui'         => false,
		'has_archive'     => false,
		'rewrite'         => false,
		'supports'        => array( 'title', 'editor' ),
		'capability_type' => 'post',
		'map_meta_cap'    => true,
	) );
}
add_action( 'init', 'trg_alerts_register' );

/* -------------------------------------------------------------------------
 * Parsers. Each takes the raw body and returns a flat list of items:
 *   uid  — stable identity, used to know whether we have seen this before
 *   title, url, date (Y-m-d), summary, tag
 * ---------------------------------------------------------------------- */

/**
 * CISA Known Exploited Vulnerabilities.
 *
 * @param string $body Raw JSON.
 * @return array|WP_Error
 */
function trg_alert_parse_kev( $body ) {
	$data = json_decode( $body, true );
	if ( ! is_array( $data ) || empty( $data['vulnerabilities'] ) ) {
		return new WP_Error( 'trg_alert_parse', __( 'The catalogue came back in a shape I did not recognise.', 'trg-site' ) );
	}

	$rows = $data['vulnerabilities'];

	// The catalogue ships oldest-first and holds well over a thousand entries.
	// Sorting before slicing is the difference between "this week's" and "2021's".
	usort(
		$rows,
		static function ( $a, $b ) {
			return strcmp( isset( $b['dateAdded'] ) ? $b['dateAdded'] : '', isset( $a['dateAdded'] ) ? $a['dateAdded'] : '' );
		}
	);

	$out = array();
	foreach ( $rows as $row ) {
		if ( empty( $row['cveID'] ) ) {
			continue;
		}
		$vendor  = isset( $row['vendorProject'] ) ? $row['vendorProject'] : '';
		$product = isset( $row['product'] ) ? $row['product'] : '';
		$name    = isset( $row['vulnerabilityName'] ) ? $row['vulnerabilityName'] : $row['cveID'];

		$summary = isset( $row['shortDescription'] ) ? $row['shortDescription'] : '';
		if ( ! empty( $row['dueDate'] ) ) {
			$summary .= ' ' . sprintf(
				/* translators: %s: date federal agencies must remediate by. */
				__( 'Federal agencies must remediate this by %s.', 'trg-site' ),
				$row['dueDate']
			);
		}
		if ( ! empty( $row['knownRansomwareCampaignUse'] ) && 'Known' === $row['knownRansomwareCampaignUse'] ) {
			$summary .= ' ' . __( 'Known to be used in ransomware campaigns.', 'trg-site' );
		}

		/*
		 * `vulnerabilityName` already opens with the vendor and the product —
		 * "SonicWall SMA1000 Appliances OS Command Injection Vulnerability" —
		 * so prefixing it produced "SonicWall SMA1000 Appliances — SonicWall
		 * SMA1000 Appliances OS Command Injection Vulnerability" on the card.
		 * The vendor and product are kept only for the rare row that has no
		 * name of its own.
		 */
		$title = '' !== trim( $name ) ? $name : trim( $vendor . ' ' . $product );

		$out[] = array(
			'uid'     => 'kev:' . $row['cveID'],
			'title'   => $title,
			'url'     => 'https://nvd.nist.gov/vuln/detail/' . rawurlencode( $row['cveID'] ),
			'date'    => isset( $row['dateAdded'] ) ? $row['dateAdded'] : '',
			'summary' => $summary,
			'tag'     => $row['cveID'],
		);
	}

	return $out;
}

/**
 * Microsoft's monthly security release summaries.
 *
 * @param string $body Raw JSON.
 * @return array|WP_Error
 */
function trg_alert_parse_msrc( $body ) {
	$data = json_decode( $body, true );
	if ( ! is_array( $data ) || empty( $data['value'] ) ) {
		return new WP_Error( 'trg_alert_parse', __( 'Microsoft answered in a shape I did not recognise.', 'trg-site' ) );
	}

	$rows = $data['value'];
	usort(
		$rows,
		static function ( $a, $b ) {
			return strcmp( isset( $b['InitialReleaseDate'] ) ? $b['InitialReleaseDate'] : '', isset( $a['InitialReleaseDate'] ) ? $a['InitialReleaseDate'] : '' );
		}
	);

	$out = array();
	foreach ( $rows as $row ) {
		if ( empty( $row['ID'] ) ) {
			continue;
		}
		$out[] = array(
			'uid'     => 'msrc:' . $row['ID'],
			'title'   => isset( $row['DocumentTitle'] ) ? $row['DocumentTitle'] : $row['ID'],
			'url'     => 'https://msrc.microsoft.com/update-guide/releaseNote/' . rawurlencode( $row['ID'] ),
			'date'    => isset( $row['InitialReleaseDate'] ) ? substr( $row['InitialReleaseDate'], 0, 10 ) : '',
			'summary' => __( "Microsoft's security update summary for this month, listing every product affected and the patches that address it.", 'trg-site' ),
			'tag'     => __( 'Microsoft', 'trg-site' ),
		);
	}

	return $out;
}

/**
 * NVD critical CVEs.
 *
 * @param string $body Raw JSON.
 * @return array|WP_Error
 */
function trg_alert_parse_nvd( $body ) {
	$data = json_decode( $body, true );
	if ( ! is_array( $data ) || ! isset( $data['vulnerabilities'] ) ) {
		return new WP_Error( 'trg_alert_parse', __( 'The vulnerability database answered in a shape I did not recognise.', 'trg-site' ) );
	}

	$rows = $data['vulnerabilities'];
	usort(
		$rows,
		static function ( $a, $b ) {
			$da = isset( $a['cve']['published'] ) ? $a['cve']['published'] : '';
			$db = isset( $b['cve']['published'] ) ? $b['cve']['published'] : '';
			return strcmp( $db, $da );
		}
	);

	$out = array();
	foreach ( $rows as $row ) {
		if ( empty( $row['cve']['id'] ) ) {
			continue;
		}
		$cve  = $row['cve'];
		$desc = '';
		if ( ! empty( $cve['descriptions'] ) ) {
			foreach ( $cve['descriptions'] as $d ) {
				if ( isset( $d['lang'] ) && 'en' === $d['lang'] ) {
					$desc = $d['value'];
					break;
				}
			}
		}

		$score = '';
		if ( ! empty( $cve['metrics']['cvssMetricV31'][0]['cvssData']['baseScore'] ) ) {
			$score = (string) $cve['metrics']['cvssMetricV31'][0]['cvssData']['baseScore'];
		}

		$out[] = array(
			'uid'     => 'nvd:' . $cve['id'],
			'title'   => $cve['id'] . ( '' !== $score ? ' — CVSS ' . $score : '' ),
			'url'     => 'https://nvd.nist.gov/vuln/detail/' . rawurlencode( $cve['id'] ),
			'date'    => isset( $cve['published'] ) ? substr( $cve['published'], 0, 10 ) : '',
			'summary' => $desc,
			'tag'     => __( 'Critical', 'trg-site' ),
		);
	}

	return $out;
}

/**
 * Plain RSS 2.0.
 *
 * @param string $body Raw XML.
 * @return array|WP_Error
 */
function trg_alert_parse_rss( $body ) {
	if ( ! function_exists( 'simplexml_load_string' ) ) {
		return new WP_Error( 'trg_alert_parse', __( 'This server has no XML reader, so RSS sources cannot be read. The JSON sources still work.', 'trg-site' ) );
	}

	// LIBXML_NONET is not decoration. This is third-party XML, and an external
	// entity is precisely how a feed turns into a request made by your server
	// on someone else's behalf. PHP 8 no longer loads them by default; saying
	// it here as well costs nothing and survives a future default changing back.
	$xml = simplexml_load_string( $body, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NONET );

	if ( false === $xml || ! isset( $xml->channel->item ) ) {
		return new WP_Error( 'trg_alert_parse', __( 'That feed did not come back as readable RSS.', 'trg-site' ) );
	}

	/*
	 * Decoded until it stops changing, up to three passes. Feeds routinely
	 * encode an entity and then escape the ampersand of that entity, so a
	 * bracket arrives as `&amp;#x5b;` and a single pass leaves the literal
	 * text "&#x5b;" sitting on the page. The bound matters as much as the
	 * loop — "keep going until it is clean" on attacker-supplied text is how
	 * you decode your way back into markup. Three is past anything real, and
	 * the result is escaped again on output regardless.
	 *
	 * Whitespace is collapsed afterwards because the last thing to fall out
	 * is usually a stray carriage return.
	 */
	$decode = static function ( $s ) {
		for ( $i = 0; $i < 3; $i++ ) {
			$next = html_entity_decode( $s, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
			if ( $next === $s ) {
				break;
			}
			$s = $next;
		}

		/*
		 * html_entity_decode deliberately will not decode an entity that names
		 * a control character, so `&#xd;` — a carriage return, and something
		 * SANS emits in almost every summary — survives every pass above and
		 * would print as literal text. Turn the three that occur in practice
		 * into the space they were standing in for.
		 */
		$s = preg_replace( '/&#(?:x0*(?:9|a|d)|0*(?:9|10|13));/i', ' ', $s );

		return trim( preg_replace( '/\s+/u', ' ', $s ) );
	};

	$out = array();
	foreach ( $xml->channel->item as $item ) {
		$link  = trim( (string) $item->link );
		$title = $decode( wp_strip_all_tags( (string) $item->title ) );
		if ( '' === $title ) {
			continue;
		}
		$date = trim( (string) $item->pubDate );
		$ts   = $date ? strtotime( $date ) : 0;

		$out[] = array(
			'uid'     => 'rss:' . md5( $link ? $link : $title ),
			'title'   => $title,
			'url'     => $link,
			'date'    => $ts ? gmdate( 'Y-m-d', $ts ) : '',
			'summary' => wp_trim_words( $decode( wp_strip_all_tags( (string) $item->description ) ), 45 ),
			'tag'     => '',
		);
	}

	return $out;
}

/* -------------------------------------------------------------------------
 * Fetching
 * ---------------------------------------------------------------------- */

/**
 * Ask one source for its items.
 *
 * @param array $source One entry from trg_alert_sources().
 * @return array|WP_Error
 */
function trg_alert_fetch_source( $source ) {
	$urls = array_filter( array( $source['url'], $source['fallback'] ) );
	$last = new WP_Error( 'trg_alert_http', __( 'No address to try.', 'trg-site' ) );

	foreach ( $urls as $url ) {
		$res = wp_remote_get( $url, array(
			'timeout'    => 25,
			'user-agent' => 'TRGNetworking-site/1.0 (+' . home_url( '/' ) . ')',
			'headers'    => array( 'Accept' => 'application/json, application/xml, */*' ),
		) );

		if ( is_wp_error( $res ) ) {
			$last = $res;
			continue;
		}

		$code = (int) wp_remote_retrieve_response_code( $res );
		if ( 200 !== $code ) {
			$last = new WP_Error(
				'trg_alert_http',
				sprintf(
					/* translators: %d: HTTP status code. */
					__( 'The source answered %d rather than OK.', 'trg-site' ),
					$code
				)
			);
			continue;
		}

		$body = wp_remote_retrieve_body( $res );
		if ( '' === $body ) {
			$last = new WP_Error( 'trg_alert_http', __( 'The source answered with nothing in it.', 'trg-site' ) );
			continue;
		}

		return call_user_func( $source['parser'], $body );
	}

	return $last;
}

/**
 * Have we already got this one?
 *
 * Checked against every status including trash, so an item the client has
 * already rejected does not come back next morning wearing a hat.
 *
 * @param string $uid Stable identifier.
 * @return bool
 */
function trg_alert_seen( $uid ) {
	$found = get_posts( array(
		'post_type'        => TRG_ALERT_POST_TYPE,
		'post_status'      => 'any',
		'posts_per_page'   => 1,
		'fields'           => 'ids',
		'no_found_rows'    => true,
		'suppress_filters' => false,
		'meta_key'         => '_trg_alert_uid', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'meta_value'       => $uid,             // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
	) );

	return ! empty( $found );
}

/**
 * Fetch every enabled source and file anything new into the holding list.
 *
 * @return array Per-source outcome, for the screen to report.
 */
function trg_alerts_fetch() {
	$settings = trg_alerts_settings();
	$sources  = trg_alert_sources();
	$per_run  = max( 1, min( 25, (int) $settings['per_run'] ) );
	$status   = array();

	foreach ( $sources as $key => $source ) {
		if ( ! in_array( $key, (array) $settings['sources'], true ) ) {
			continue;
		}

		$items = trg_alert_fetch_source( $source );

		if ( is_wp_error( $items ) ) {
			$status[ $key ] = array(
				'ok'    => false,
				'added' => 0,
				'msg'   => $items->get_error_message(),
				'when'  => time(),
			);
			continue;
		}

		$added = 0;
		foreach ( array_slice( $items, 0, $per_run ) as $item ) {
			if ( '' === $item['uid'] || trg_alert_seen( $item['uid'] ) ) {
				continue;
			}

			$stamp = $item['date'] ? $item['date'] . ' 09:00:00' : current_time( 'mysql' );

			/*
			 * post_date_gmt is set explicitly, and it is not optional. A
			 * pending post normally carries a zeroed GMT date, and WordPress
			 * treats that as "this was never really dated" — so the moment
			 * somebody presses Publish it stamps the post with today. Three
			 * advisories from three different weeks all came out reading
			 * "6 Sep 2026", which on an MSP's own website is worse than having
			 * no dates at all. Setting the GMT date up front is what makes the
			 * advisory's own date survive approval.
			 */
			$id = wp_insert_post( array(
				'post_type'     => TRG_ALERT_POST_TYPE,
				'post_status'   => 'pending',
				'post_title'    => wp_strip_all_tags( $item['title'] ),
				'post_content'  => wp_kses_post( $item['summary'] ),
				'post_date'     => $stamp,
				'post_date_gmt' => get_gmt_from_date( $stamp ),
			), true );

			if ( is_wp_error( $id ) ) {
				continue;
			}

			update_post_meta( $id, '_trg_alert_uid', $item['uid'] );
			update_post_meta( $id, '_trg_alert_source', $key );
			update_post_meta( $id, '_trg_alert_url', esc_url_raw( $item['url'] ) );
			update_post_meta( $id, '_trg_alert_tag', sanitize_text_field( $item['tag'] ) );
			++$added;
		}

		$status[ $key ] = array(
			'ok'    => true,
			'added' => $added,
			'msg'   => sprintf(
				/* translators: 1: number of new items, 2: number of items the source offered. */
				__( '%1$d new, out of %2$d offered.', 'trg-site' ),
				$added,
				count( $items )
			),
			'when'  => time(),
		);
	}

	$settings['last_run'] = time();
	$settings['status']   = $status;
	update_option( TRG_ALERTS_OPTION, $settings );

	return $status;
}

/**
 * Keep the scheduled check in step with the chosen frequency.
 */
function trg_alerts_schedule() {
	$settings = trg_alerts_settings();
	$wanted   = in_array( $settings['frequency'], array( 'twicedaily', 'daily', 'weekly' ), true ) ? $settings['frequency'] : 'daily';
	$current  = wp_get_schedule( TRG_ALERTS_CRON );

	if ( $current === $wanted ) {
		return;
	}
	if ( $current ) {
		wp_clear_scheduled_hook( TRG_ALERTS_CRON );
	}
	wp_schedule_event( time() + HOUR_IN_SECONDS, $wanted, TRG_ALERTS_CRON );
}
add_action( 'init', 'trg_alerts_schedule', 20 );
add_action( TRG_ALERTS_CRON, 'trg_alerts_fetch' );

/**
 * A weekly interval, which WordPress only added in 6.4.
 *
 * @param array $schedules Existing schedules.
 * @return array
 */
function trg_alerts_intervals( $schedules ) {
	if ( ! isset( $schedules['weekly'] ) ) {
		$schedules['weekly'] = array(
			'interval' => WEEK_IN_SECONDS,
			'display'  => __( 'Once weekly', 'trg-site' ),
		);
	}
	return $schedules;
}
add_filter( 'cron_schedules', 'trg_alerts_intervals' ); // phpcs:ignore WordPress.WP.CronInterval.ChangeDetected

/* -------------------------------------------------------------------------
 * Reading them back out
 * ---------------------------------------------------------------------- */

/**
 * The approved alerts, newest first.
 *
 * @param int $limit How many.
 * @return WP_Post[]
 */
function trg_alerts_published( $limit = 6 ) {
	return get_posts( array(
		'post_type'      => TRG_ALERT_POST_TYPE,
		'post_status'    => 'publish',
		'posts_per_page' => (int) $limit,
		'orderby'        => 'date',
		'order'          => 'DESC',
	) );
}

/**
 * One alert's source label, for display.
 *
 * @param int $id Post ID.
 * @return string
 */
function trg_alert_source_label( $id ) {
	$key     = get_post_meta( $id, '_trg_alert_source', true );
	$sources = trg_alert_sources();
	return isset( $sources[ $key ]['label'] ) ? $sources[ $key ]['label'] : '';
}

/* -------------------------------------------------------------------------
 * The review screen
 * ---------------------------------------------------------------------- */

/**
 * Admin menu entry.
 *
 * `edit_pages`, not `manage_options`. Deciding whether a security advisory is
 * worth showing clients is editorial judgement, not server administration, and
 * the person with that judgement is not necessarily the person with the keys.
 */
function trg_alerts_menu() {
	$waiting = trg_alerts_count( 'pending' );
	$label   = __( 'News & alerts', 'trg-site' );
	if ( $waiting ) {
		$label .= ' <span class="awaiting-mod"><span class="pending-count">' . (int) $waiting . '</span></span>';
	}

	add_submenu_page(
		TRG_HUB_SLUG,
		__( 'Security news & alerts', 'trg-site' ),
		$label,
		'edit_pages',
		'trg-alerts',
		'trg_alerts_page'
	);
}
add_action( 'admin_menu', 'trg_alerts_menu' );

/**
 * How many alerts are in a given state.
 *
 * @param string $status pending, publish or trash.
 * @return int
 */
function trg_alerts_count( $status ) {
	$counts = wp_count_posts( TRG_ALERT_POST_TYPE );
	return isset( $counts->$status ) ? (int) $counts->$status : 0;
}

/**
 * Handle the buttons, then draw the screen.
 */
function trg_alerts_page() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		wp_die( esc_html__( 'You do not have permission to manage the news section.', 'trg-site' ) );
	}

	$notices  = array();
	$settings = trg_alerts_settings();

	if ( isset( $_POST['trg_alerts_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['trg_alerts_nonce'] ) ), 'trg_alerts' ) ) {

		// Approve / reject / take back down. One item, one button.
		foreach ( array( 'approve' => 'publish', 'reject' => 'trash', 'unpublish' => 'pending' ) as $action => $new_status ) {
			if ( empty( $_POST[ $action ] ) ) {
				continue;
			}
			$id = (int) $_POST[ $action ];
			if ( $id && get_post_type( $id ) === TRG_ALERT_POST_TYPE ) {
				wp_update_post( array( 'ID' => $id, 'post_status' => $new_status ) );
				$notices[] = array(
					'updated',
					'publish' === $new_status ? __( 'Published. It is on the site now.', 'trg-site' )
						: ( 'trash' === $new_status ? __( 'Rejected. It will not come back.', 'trg-site' )
						: __( 'Taken off the site and put back in the waiting list.', 'trg-site' ) ),
				);
			}
		}

		if ( ! empty( $_POST['approve_all'] ) && ! empty( $_POST['ids'] ) && is_array( $_POST['ids'] ) ) {
			$n = 0;
			foreach ( wp_unslash( $_POST['ids'] ) as $id ) {
				$id = (int) $id;
				if ( $id && get_post_type( $id ) === TRG_ALERT_POST_TYPE ) {
					wp_update_post( array( 'ID' => $id, 'post_status' => 'publish' ) );
					++$n;
				}
			}
			/* translators: %d: number of items published. */
			$notices[] = array( 'updated', sprintf( _n( '%d item published.', '%d items published.', $n, 'trg-site' ), $n ) );
		}

		if ( ! empty( $_POST['fetch'] ) ) {
			$status = trg_alerts_fetch();
			$new    = array_sum( wp_list_pluck( $status, 'added' ) );
			$notices[] = array(
				'updated',
				$new
					/* translators: %d: number of new items found. */
					? sprintf( _n( 'Checked now. %d new item is waiting for you.', 'Checked now. %d new items are waiting for you.', $new, 'trg-site' ), $new )
					: __( 'Checked now. Nothing new since last time.', 'trg-site' ),
			);
			$settings = trg_alerts_settings();
		}

		if ( ! empty( $_POST['save_settings'] ) ) {
			$chosen = isset( $_POST['sources'] ) ? array_map( 'sanitize_key', (array) wp_unslash( $_POST['sources'] ) ) : array();
			$settings['sources']   = array_values( array_intersect( $chosen, array_keys( trg_alert_sources() ) ) );
			$freq                  = isset( $_POST['frequency'] ) ? sanitize_key( wp_unslash( $_POST['frequency'] ) ) : 'daily';
			$settings['frequency'] = in_array( $freq, array( 'twicedaily', 'daily', 'weekly' ), true ) ? $freq : 'daily';
			$settings['per_run']   = max( 1, min( 25, isset( $_POST['per_run'] ) ? (int) $_POST['per_run'] : 8 ) );
			$settings['show']      = max( 1, min( 24, isset( $_POST['show'] ) ? (int) $_POST['show'] : 6 ) );
			update_option( TRG_ALERTS_OPTION, $settings );

			// The schedule is derived from the setting, so re-derive it now
			// rather than waiting for the next page load to notice.
			wp_clear_scheduled_hook( TRG_ALERTS_CRON );
			trg_alerts_schedule();

			$notices[] = array( 'updated', __( 'Settings saved.', 'trg-site' ) );
		}
	}

	$view  = isset( $_GET['view'] ) ? sanitize_key( wp_unslash( $_GET['view'] ) ) : 'pending';
	$view  = in_array( $view, array( 'pending', 'publish', 'trash' ), true ) ? $view : 'pending';
	$items = get_posts( array(
		'post_type'      => TRG_ALERT_POST_TYPE,
		'post_status'    => $view,
		'posts_per_page' => 60,
		'orderby'        => 'date',
		'order'          => 'DESC',
	) );

	$sources = trg_alert_sources();
	$tabs    = array(
		'pending' => __( 'Waiting for you', 'trg-site' ),
		'publish' => __( 'On the site', 'trg-site' ),
		'trash'   => __( 'Rejected', 'trg-site' ),
	);
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Security news & alerts', 'trg-site' ); ?></h1>
		<p style="max-width:52em">
			<?php esc_html_e( 'The site checks a few security sources on a schedule and puts what it finds in the list below. Nothing appears on the website until you press Publish on it — no exceptions, and there is no setting that changes that.', 'trg-site' ); ?>
		</p>

		<?php foreach ( $notices as $notice ) : ?>
			<div class="<?php echo esc_attr( 'error' === $notice[0] ? 'notice notice-error' : 'notice notice-success' ); ?>"><p><?php echo esc_html( $notice[1] ); ?></p></div>
		<?php endforeach; ?>

		<form method="post" style="margin:1.4em 0">
			<?php wp_nonce_field( 'trg_alerts', 'trg_alerts_nonce' ); ?>
			<button type="submit" name="fetch" value="1" class="button button-secondary"><?php esc_html_e( 'Check for new items now', 'trg-site' ); ?></button>
			<span style="margin-left:1em;color:#646970">
				<?php
				if ( $settings['last_run'] ) {
					printf(
						/* translators: %s: human-readable time difference, e.g. "3 hours". */
						esc_html__( 'Last checked %s ago.', 'trg-site' ),
						esc_html( human_time_diff( (int) $settings['last_run'] ) )
					);
				} else {
					esc_html_e( 'Never checked yet.', 'trg-site' );
				}
				$next = wp_next_scheduled( TRG_ALERTS_CRON );
				if ( $next ) {
					echo ' ';
					printf(
						/* translators: %s: human-readable time difference. */
						esc_html__( 'Next automatic check in %s.', 'trg-site' ),
						esc_html( human_time_diff( $next ) )
					);
				}
				?>
			</span>
		</form>

		<?php if ( ! empty( $settings['status'] ) ) : ?>
			<table class="widefat striped" style="max-width:64em;margin-bottom:1.6em">
				<thead><tr>
					<th><?php esc_html_e( 'Source', 'trg-site' ); ?></th>
					<th style="width:8em"><?php esc_html_e( 'Last check', 'trg-site' ); ?></th>
					<th><?php esc_html_e( 'Result', 'trg-site' ); ?></th>
				</tr></thead>
				<tbody>
				<?php foreach ( $settings['status'] as $key => $s ) : ?>
					<tr>
						<td><?php echo esc_html( isset( $sources[ $key ]['label'] ) ? $sources[ $key ]['label'] : $key ); ?></td>
						<td><?php echo esc_html( $s['when'] ? human_time_diff( (int) $s['when'] ) . __( ' ago', 'trg-site' ) : '—' ); ?></td>
						<td style="color:<?php echo $s['ok'] ? '#1d6b2f' : '#b32d2e'; ?>">
							<?php echo esc_html( ( $s['ok'] ? '' : __( 'Problem: ', 'trg-site' ) ) . $s['msg'] ); ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<h2 class="nav-tab-wrapper" style="margin-bottom:1em">
			<?php foreach ( $tabs as $slug => $label ) : ?>
				<a class="nav-tab <?php echo $view === $slug ? 'nav-tab-active' : ''; ?>"
					href="<?php echo esc_url( admin_url( 'admin.php?page=trg-alerts&view=' . $slug ) ); ?>">
					<?php echo esc_html( $label ); ?> (<?php echo (int) trg_alerts_count( $slug ); ?>)
				</a>
			<?php endforeach; ?>
		</h2>

		<?php if ( ! $items ) : ?>
			<p style="max-width:46em;color:#646970">
				<?php
				if ( 'pending' === $view ) {
					esc_html_e( 'Nothing waiting. Press “Check for new items now” above, or wait for the next scheduled check.', 'trg-site' );
				} elseif ( 'publish' === $view ) {
					esc_html_e( 'Nothing published yet, so the news section does not appear on the site at all. It will show up the moment you publish your first item.', 'trg-site' );
				} else {
					esc_html_e( 'Nothing rejected.', 'trg-site' );
				}
				?>
			</p>
		<?php else : ?>
			<form method="post">
				<?php wp_nonce_field( 'trg_alerts', 'trg_alerts_nonce' ); ?>
				<table class="widefat striped" style="max-width:76em">
					<thead><tr>
						<?php if ( 'pending' === $view ) : ?><th style="width:2em"></th><?php endif; ?>
						<th><?php esc_html_e( 'Item', 'trg-site' ); ?></th>
						<th style="width:15em"><?php esc_html_e( 'Source', 'trg-site' ); ?></th>
						<th style="width:7em"><?php esc_html_e( 'Dated', 'trg-site' ); ?></th>
						<th style="width:15em"></th>
					</tr></thead>
					<tbody>
					<?php foreach ( $items as $item ) : ?>
						<?php
						$url = get_post_meta( $item->ID, '_trg_alert_url', true );
						$tag = get_post_meta( $item->ID, '_trg_alert_tag', true );
						?>
						<tr>
							<?php if ( 'pending' === $view ) : ?>
								<td><input type="checkbox" name="ids[]" value="<?php echo (int) $item->ID; ?>"></td>
							<?php endif; ?>
							<td>
								<strong>
									<?php if ( $url ) : ?>
										<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $item->post_title ); ?></a>
									<?php else : ?>
										<?php echo esc_html( $item->post_title ); ?>
									<?php endif; ?>
								</strong>
								<?php if ( $tag ) : ?><span style="margin-left:.5em;color:#646970;font-size:12px"><?php echo esc_html( $tag ); ?></span><?php endif; ?>
								<div style="color:#50575e;margin-top:.25em"><?php echo esc_html( wp_trim_words( $item->post_content, 40 ) ); ?></div>
							</td>
							<td><?php echo esc_html( trg_alert_source_label( $item->ID ) ); ?></td>
							<td><?php echo esc_html( get_the_date( 'j M Y', $item ) ); ?></td>
							<td>
								<?php if ( 'publish' === $view ) : ?>
									<button class="button" name="unpublish" value="<?php echo (int) $item->ID; ?>"><?php esc_html_e( 'Take it down', 'trg-site' ); ?></button>
								<?php else : ?>
									<button class="button button-primary" name="approve" value="<?php echo (int) $item->ID; ?>"><?php esc_html_e( 'Publish', 'trg-site' ); ?></button>
									<?php if ( 'pending' === $view ) : ?>
										<button class="button" name="reject" value="<?php echo (int) $item->ID; ?>"><?php esc_html_e( 'Not this one', 'trg-site' ); ?></button>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				<?php if ( 'pending' === $view ) : ?>
					<p style="margin-top:1em">
						<button class="button" name="approve_all" value="1"><?php esc_html_e( 'Publish the ticked ones', 'trg-site' ); ?></button>
					</p>
				<?php endif; ?>
			</form>
		<?php endif; ?>

		<h2 style="margin-top:2.4em"><?php esc_html_e( 'Settings', 'trg-site' ); ?></h2>
		<form method="post" style="max-width:56em">
			<?php wp_nonce_field( 'trg_alerts', 'trg_alerts_nonce' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'Where to look', 'trg-site' ); ?></th>
					<td>
						<?php foreach ( $sources as $key => $source ) : ?>
							<p style="margin:0 0 .8em">
								<label>
									<input type="checkbox" name="sources[]" value="<?php echo esc_attr( $key ); ?>"
										<?php checked( in_array( $key, (array) $settings['sources'], true ) ); ?>>
									<strong><?php echo esc_html( $source['label'] ); ?></strong>
								</label>
								<span style="display:block;margin-left:1.8em;color:#646970"><?php echo esc_html( $source['note'] ); ?></span>
							</p>
						<?php endforeach; ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="trg-freq"><?php esc_html_e( 'How often to check', 'trg-site' ); ?></label></th>
					<td>
						<select name="frequency" id="trg-freq">
							<option value="twicedaily" <?php selected( $settings['frequency'], 'twicedaily' ); ?>><?php esc_html_e( 'Twice a day', 'trg-site' ); ?></option>
							<option value="daily" <?php selected( $settings['frequency'], 'daily' ); ?>><?php esc_html_e( 'Once a day', 'trg-site' ); ?></option>
							<option value="weekly" <?php selected( $settings['frequency'], 'weekly' ); ?>><?php esc_html_e( 'Once a week', 'trg-site' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="trg-perrun"><?php esc_html_e( 'Most to collect each time', 'trg-site' ); ?></label></th>
					<td>
						<input type="number" min="1" max="25" name="per_run" id="trg-perrun" value="<?php echo (int) $settings['per_run']; ?>" class="small-text">
						<span style="color:#646970"><?php esc_html_e( 'Per source. Keeps the waiting list to a length somebody will actually read.', 'trg-site' ); ?></span>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="trg-show"><?php esc_html_e( 'How many to show on the site', 'trg-site' ); ?></label></th>
					<td>
						<input type="number" min="1" max="24" name="show" id="trg-show" value="<?php echo (int) $settings['show']; ?>" class="small-text">
						<span style="color:#646970"><?php esc_html_e( 'The newest published items appear; older ones drop off the page but stay here.', 'trg-site' ); ?></span>
					</td>
				</tr>
			</table>
			<p><button class="button button-primary" name="save_settings" value="1"><?php esc_html_e( 'Save settings', 'trg-site' ); ?></button></p>
		</form>

		<h2 style="margin-top:2.4em"><?php esc_html_e( 'Where it shows on the site', 'trg-site' ); ?></h2>
		<div style="max-width:52em">
			<p><?php esc_html_e( 'Published items appear on the Resources page. To put the list on another page as well, add this to the page text:', 'trg-site' ); ?></p>
			<p><code>[trg_alerts]</code></p>
			<p style="color:#646970">
				<?php esc_html_e( 'Each item shows its headline, a short summary and a link to the original advisory. It deliberately does not reproduce the whole article — the point is to send a client to the authoritative source, not to compete with it.', 'trg-site' ); ?>
			</p>
		</div>
	</div>
	<?php
}

/* -------------------------------------------------------------------------
 * The front of the site
 * ---------------------------------------------------------------------- */

/**
 * [trg_alerts] — the approved items.
 *
 * Renders nothing at all when nothing has been approved. An empty "Latest
 * security alerts" heading with no alerts under it looks like a broken site,
 * and on an MSP's own website it looks like an unwatched one.
 *
 * @param array $atts Attributes.
 * @return string
 */
function trg_sc_alerts( $atts ) {
	$atts = shortcode_atts( array(
		'eyebrow' => __( 'Security watch', 'trg-site' ),
		'title'   => __( 'Current advisories worth your attention', 'trg-site' ),
		'body'    => __( 'Chosen by TRG from the federal and vendor advisories we monitor for clients. Each links to the original notice.', 'trg-site' ),
		'limit'   => 0,
		'bg'      => 'white',
	), $atts, 'trg_alerts' );

	$settings = trg_alerts_settings();
	$limit    = (int) $atts['limit'] ? (int) $atts['limit'] : (int) $settings['show'];
	$items    = trg_alerts_published( $limit );

	if ( ! $items ) {
		return '';
	}

	$bg = in_array( $atts['bg'], array( 'white', 'canvas' ), true ) ? $atts['bg'] : 'white';

	ob_start();
	?>
	<section class="bg-<?php echo esc_attr( $bg ); ?> py-16 sm:py-20">
		<div class="shell">
			<?php
			echo trg_section_head( array( // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside.
				'eyebrow' => $atts['eyebrow'],
				'title'   => $atts['title'],
				'body'    => $atts['body'],
			) );
			?>
			<ul class="mt-10 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
				<?php foreach ( $items as $item ) : ?>
					<?php
					$url    = get_post_meta( $item->ID, '_trg_alert_url', true );
					$tag    = get_post_meta( $item->ID, '_trg_alert_tag', true );
					$source = trg_alert_source_label( $item->ID );
					?>
					<li class="flex flex-col rounded-2xl border border-line bg-white p-5 shadow-sm">
						<p class="font-heading text-[11px] font-bold uppercase tracking-[0.14em] text-brand-600">
							<?php echo esc_html( get_the_date( 'j M Y', $item ) ); ?>
							<?php if ( $tag ) : ?><span class="text-soft"> · <?php echo esc_html( $tag ); ?></span><?php endif; ?>
						</p>
						<h3 class="mt-2 text-[17px] leading-snug"><?php echo esc_html( $item->post_title ); ?></h3>
						<p class="mt-2 flex-1 text-[14px] leading-relaxed text-muted"><?php echo esc_html( wp_trim_words( $item->post_content, 32 ) ); ?></p>
						<?php if ( $url ) : ?>
							<?php // rel="noopener" is not optional on target=_blank: without it the opened page can reach back through window.opener. ?>
							<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"
								class="mt-4 inline-flex items-center gap-1.5 font-heading text-[14px] font-semibold text-brand-600 hover:text-brand-800">
								<?php esc_html_e( 'Read the advisory', 'trg-site' ); ?>
								<span aria-hidden="true">→</span>
								<span class="sr-only"><?php esc_html_e( '(opens in a new tab)', 'trg-site' ); ?></span>
							</a>
						<?php endif; ?>
						<?php if ( $source ) : ?>
							<p class="mt-2 text-[12px] text-soft"><?php echo esc_html( $source ); ?></p>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'trg_alerts', 'trg_sc_alerts' );
