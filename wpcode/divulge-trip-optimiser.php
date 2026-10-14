/**
 * Divulge Trip Optimiser
 * Tools → Trip Optimiser
 *  1. Export: downloads all trips (content, itinerary, FAQs, SEO fields, images) as JSON.
 *  2. Auto-fix: splits long paragraphs and keeps one focus keyword per trip (backs up first).
 *  3. Import: applies an optimised JSON file (SEO fields, overview, FAQs, photos).
 *  4. Restore: puts every changed trip back exactly as it was before the first change.
 */

const DIVULGE_TO_BACKUP = '_divulge_trip_backup';
const DIVULGE_TO_MAX_WORDS = 70; // paragraphs longer than this are split (Rank Math flags 120+)

add_action( 'admin_menu', function () {
	add_management_page( 'Trip Optimiser', 'Trip Optimiser', 'manage_options', 'divulge-trip-optimiser', 'divulge_to_page' );
} );

/* ---------- helpers ---------- */

function divulge_to_trips( $statuses = array( 'publish', 'draft', 'pending', 'private', 'future' ) ) {
	return get_posts( array(
		'post_type'      => 'trip',
		'post_status'    => $statuses,
		'posts_per_page' => -1,
		'orderby'        => 'ID',
		'order'          => 'ASC',
	) );
}

function divulge_to_backup( $id ) {
	if ( metadata_exists( 'post', $id, DIVULGE_TO_BACKUP ) ) {
		return; // keep the very first backup
	}
	$post = get_post( $id );
	update_post_meta( $id, DIVULGE_TO_BACKUP, array(
		'time'         => current_time( 'mysql' ),
		'post_content' => $post->post_content,
		'post_excerpt' => $post->post_excerpt,
		'settings'     => get_post_meta( $id, 'wp_travel_engine_setting', true ),
		'gallery'      => get_post_meta( $id, 'wpte_gallery_id', true ),
		'thumbnail'    => get_post_thumbnail_id( $id ),
		'rm_title'     => get_post_meta( $id, 'rank_math_title', true ),
		'rm_desc'      => get_post_meta( $id, 'rank_math_description', true ),
		'rm_keyword'   => get_post_meta( $id, 'rank_math_focus_keyword', true ),
	) );
}

function divulge_to_words( $html ) {
	return str_word_count( wp_strip_all_tags( $html ) );
}

/** Split one paragraph's inner HTML into chunks of about DIVULGE_TO_MAX_WORDS words at sentence ends. */
function divulge_to_split_text( $inner ) {
	if ( divulge_to_words( $inner ) <= DIVULGE_TO_MAX_WORDS ) {
		return array( $inner );
	}
	$sentences = preg_split( '/(?<=[.!?])\s+(?=[A-Z0-9"“‘(])/u', trim( $inner ) );
	$chunks    = array();
	$current   = '';
	foreach ( $sentences as $sentence ) {
		$candidate = $current === '' ? $sentence : $current . ' ' . $sentence;
		$balanced  = substr_count( $current, '<a ' ) === substr_count( $current, '</a>' )
			&& substr_count( $current, '<strong' ) === substr_count( $current, '</strong>' )
			&& substr_count( $current, '<em' ) === substr_count( $current, '</em>' );
		if ( $current !== '' && $balanced && divulge_to_words( $candidate ) > DIVULGE_TO_MAX_WORDS * 0.85 && divulge_to_words( $current ) >= 25 ) {
			$chunks[] = $current;
			$current  = $sentence;
		} else {
			$current = $candidate;
		}
	}
	if ( $current !== '' ) {
		$chunks[] = $current;
	}
	return $chunks;
}

/** Split long paragraphs in an HTML or plain-text field. Returns the new string. */
function divulge_to_split_field( $text ) {
	if ( ! is_string( $text ) || trim( $text ) === '' ) {
		return $text;
	}
	if ( stripos( $text, '<p' ) !== false ) {
		$is_block = strpos( $text, '<!-- wp:paragraph' ) !== false;
		return preg_replace_callback( '#<p(\s[^>]*)?>(.*?)</p>#is', function ( $m ) use ( $is_block ) {
			$attrs  = isset( $m[1] ) ? $m[1] : '';
			$chunks = divulge_to_split_text( $m[2] );
			if ( count( $chunks ) < 2 ) {
				return $m[0];
			}
			$glue = $is_block ? "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p{$attrs}>" : "</p>\n<p{$attrs}>";
			return '<p' . $attrs . '>' . implode( $glue, $chunks ) . '</p>';
		}, $text );
	}
	// Plain text: paragraphs separated by blank lines.
	$paras = preg_split( '/\R\s*\R/', $text );
	$out   = array();
	foreach ( $paras as $p ) {
		$out[] = implode( "\n\n", divulge_to_split_text( trim( $p ) ) );
	}
	return implode( "\n\n", $out );
}

/** Attachment ID for a photo already in this site's Media Library (any size of it), or 0. */
function divulge_to_local_id( $url ) {
	$site = wp_parse_url( home_url(), PHP_URL_HOST );
	$host = wp_parse_url( $url, PHP_URL_HOST );
	if ( ! $host || ! $site || strtolower( preg_replace( '/^www\./', '', $host ) ) !== strtolower( preg_replace( '/^www\./', '', $site ) ) ) {
		return 0;
	}
	$base = preg_replace( '/-\d+x\d+(?=\.[a-z0-9]+$)/i', '', strtok( $url, '?' ) );
	foreach ( array( $url, $base, preg_replace( '/(\.[a-z0-9]+)$/i', '-scaled$1', $base ), preg_replace( '/-scaled(?=\.[a-z0-9]+$)/i', '', $base ) ) as $try ) {
		$id = attachment_url_to_postid( $try );
		if ( $id ) {
			return (int) $id;
		}
	}
	return 0;
}

/** Sideload an image URL into the Media Library once; returns attachment ID or 0. */
function divulge_to_image( $url, $alt, $post_id ) {
	$url = esc_url_raw( $url );
	if ( ! $url ) {
		return 0;
	}
	$existing = get_posts( array(
		'post_type'   => 'attachment',
		'post_status' => 'inherit',
		'meta_key'    => '_divulge_source_url',
		'meta_value'  => $url,
		'fields'      => 'ids',
		'numberposts' => 1,
	) );
	$local = $existing ? 0 : divulge_to_local_id( $url );
	if ( $existing ) {
		$att_id = (int) $existing[0];
	} elseif ( $local ) {
		$att_id = $local; // photo already in the Media Library: reuse it, never copy it
	} else {
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$tmp = download_url( $url, 60 );
		if ( is_wp_error( $tmp ) ) {
			return 0;
		}
		$name = sanitize_title( $alt ? $alt : 'safari-photo' );
		$file = array( 'name' => substr( $name, 0, 80 ) . '.jpg', 'tmp_name' => $tmp );
		$att_id = media_handle_sideload( $file, $post_id, $alt );
		if ( is_wp_error( $att_id ) ) {
			@unlink( $tmp );
			return 0;
		}
		update_post_meta( $att_id, '_divulge_source_url', $url );
	}
	if ( $alt ) {
		update_post_meta( $att_id, '_wp_attachment_image_alt', sanitize_text_field( $alt ) );
	}
	return $att_id;
}

/* ---------- actions ---------- */

add_action( 'admin_post_divulge_to_export', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed' );
	}
	check_admin_referer( 'divulge_to_export' );
	$out = array();
	foreach ( divulge_to_trips() as $t ) {
		$s       = get_post_meta( $t->ID, 'wp_travel_engine_setting', true );
		$s       = is_array( $s ) ? $s : array();
		$gallery = array();
		$g       = get_post_meta( $t->ID, 'wpte_gallery_id', true );
		if ( is_array( $g ) ) {
			foreach ( $g as $k => $att ) {
				if ( $k !== 'enable' && is_numeric( $att ) ) {
					$gallery[] = array( 'id' => (int) $att, 'alt' => get_post_meta( (int) $att, '_wp_attachment_image_alt', true ), 'file' => basename( (string) get_attached_file( (int) $att ) ) );
				}
			}
		}
		$thumb = get_post_thumbnail_id( $t->ID );
		$out[] = array(
			'id'               => $t->ID,
			'status'           => $t->post_status,
			'title'            => $t->post_title,
			'slug'             => $t->post_name,
			'url'              => get_permalink( $t->ID ),
			'destinations'     => wp_get_post_terms( $t->ID, 'destination', array( 'fields' => 'names' ) ),
			'post_content'     => $t->post_content,
			'rank_math_title'  => get_post_meta( $t->ID, 'rank_math_title', true ),
			'rank_math_desc'   => get_post_meta( $t->ID, 'rank_math_description', true ),
			'focus_keyword'    => get_post_meta( $t->ID, 'rank_math_focus_keyword', true ),
			'seo_score'        => get_post_meta( $t->ID, 'rank_math_seo_score', true ),
			'price'            => get_post_meta( $t->ID, 'wp_travel_engine_setting_trip_price', true ),
			'duration'         => isset( $s['trip_duration'] ) ? $s['trip_duration'] . ' ' . ( isset( $s['trip_duration_unit'] ) ? $s['trip_duration_unit'] : '' ) : '',
			'overview'         => isset( $s['tab_content']['1_wpeditor'] ) ? $s['tab_content']['1_wpeditor'] : '',
			'highlights'       => isset( $s['trip_highlights'] ) ? $s['trip_highlights'] : array(),
			'itinerary'        => isset( $s['itinerary'] ) ? $s['itinerary'] : array(),
			'cost'             => isset( $s['cost'] ) ? $s['cost'] : array(),
			'faq'              => isset( $s['faq'] ) ? $s['faq'] : array(),
			'featured_image'   => $thumb ? array( 'id' => (int) $thumb, 'alt' => get_post_meta( $thumb, '_wp_attachment_image_alt', true ), 'file' => basename( (string) get_attached_file( $thumb ) ) ) : null,
			'gallery'          => $gallery,
		);
	}
	nocache_headers();
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=divulge-trips-' . gmdate( 'Y-m-d' ) . '.json' );
	echo wp_json_encode( $out, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	exit;
} );

function divulge_to_autofix() {
	$report = array();
	foreach ( divulge_to_trips() as $t ) {
		$id      = $t->ID;
		$changed = array();
		$s       = get_post_meta( $id, 'wp_travel_engine_setting', true );
		$new     = $s;
		if ( is_array( $s ) ) {
			if ( isset( $new['tab_content']['1_wpeditor'] ) ) {
				$new['tab_content']['1_wpeditor'] = divulge_to_split_field( $new['tab_content']['1_wpeditor'] );
			}
			if ( ! empty( $new['itinerary']['itinerary_content'] ) && is_array( $new['itinerary']['itinerary_content'] ) ) {
				foreach ( $new['itinerary']['itinerary_content'] as $k => $v ) {
					$new['itinerary']['itinerary_content'][ $k ] = divulge_to_split_field( $v );
				}
			}
			if ( ! empty( $new['faq']['faq_content'] ) && is_array( $new['faq']['faq_content'] ) ) {
				foreach ( $new['faq']['faq_content'] as $k => $v ) {
					$new['faq']['faq_content'][ $k ] = divulge_to_split_field( $v );
				}
			}
			if ( $new !== $s ) {
				$changed[] = 'long paragraphs split';
			}
		}
		$kw     = (string) get_post_meta( $id, 'rank_math_focus_keyword', true );
		$new_kw = $kw;
		if ( strpos( $kw, ',' ) !== false ) {
			$parts  = array_filter( array_map( 'trim', explode( ',', $kw ) ) );
			$new_kw = (string) reset( $parts );
			$changed[] = 'focus keyword set to "' . $new_kw . '"';
		}
		if ( $changed ) {
			divulge_to_backup( $id );
			if ( $new !== $s ) {
				update_post_meta( $id, 'wp_travel_engine_setting', $new );
			}
			if ( $new_kw !== $kw ) {
				update_post_meta( $id, 'rank_math_focus_keyword', $new_kw );
			}
			$report[] = $t->post_title . ': ' . implode( '; ', $changed );
		}
	}
	return $report;
}

function divulge_to_import( $json ) {
	$items = json_decode( $json, true );
	if ( ! is_array( $items ) ) {
		return array( 'The file is not valid JSON.' );
	}
	$report = array();
	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 300 );
	}
	wp_raise_memory_limit( 'image' );
	$start      = isset( $GLOBALS['divulge_to_start'] ) ? $GLOBALS['divulge_to_start'] : microtime( true );
	$time_ok    = function () use ( $start ) {
		return ( microtime( true ) - $start ) < 18; // stay safely under a 30-second server limit
	};
	$photos_left = false;
	foreach ( $items as $item ) {
		$id = isset( $item['id'] ) ? absint( $item['id'] ) : 0;
		if ( ! $id || get_post_type( $id ) !== 'trip' ) {
			$report[] = 'Skipped an item with unknown trip ID.';
			continue;
		}
		divulge_to_backup( $id );
		$done = array();
		$s    = get_post_meta( $id, 'wp_travel_engine_setting', true );
		$s    = is_array( $s ) ? $s : array();

		if ( ! empty( $item['focus_keyword'] ) ) {
			update_post_meta( $id, 'rank_math_focus_keyword', sanitize_text_field( $item['focus_keyword'] ) );
			$done[] = 'keyword';
		}
		if ( ! empty( $item['rank_math_title'] ) ) {
			update_post_meta( $id, 'rank_math_title', sanitize_text_field( $item['rank_math_title'] ) );
			$done[] = 'SEO title';
		}
		if ( ! empty( $item['rank_math_desc'] ) ) {
			update_post_meta( $id, 'rank_math_description', sanitize_text_field( $item['rank_math_desc'] ) );
			$done[] = 'meta description';
		}
		if ( isset( $item['post_content'] ) && $item['post_content'] !== '' ) {
			wp_update_post( array( 'ID' => $id, 'post_content' => wp_kses_post( $item['post_content'] ) ) );
			$done[] = 'intro line';
		}
		// New URL slug: only for trips that are not yet published (never breaks live links).
		if ( ! empty( $item['slug'] ) && get_post_status( $id ) !== 'publish' ) {
			wp_update_post( array( 'ID' => $id, 'post_name' => sanitize_title( $item['slug'] ) ) );
			$done[] = 'URL slug';
		}
		// Always split long paragraphs in this trip's itinerary.
		if ( ! empty( $s['itinerary']['itinerary_content'] ) && is_array( $s['itinerary']['itinerary_content'] ) ) {
			foreach ( $s['itinerary']['itinerary_content'] as $k => $v ) {
				$s['itinerary']['itinerary_content'][ $k ] = divulge_to_split_field( $v );
			}
		}
		if ( ! empty( $item['overview'] ) ) {
			$s['tab_content']['1_wpeditor'] = wp_kses_post( $item['overview'] );
			$done[] = 'overview';
		}
		if ( ! empty( $item['faq'] ) && is_array( $item['faq'] ) ) {
			$titles = array();
			$bodies = array();
			$n      = 1;
			foreach ( $item['faq'] as $qa ) {
				if ( empty( $qa['q'] ) || empty( $qa['a'] ) ) {
					continue;
				}
				$titles[ $n ] = sanitize_text_field( $qa['q'] );
				$bodies[ $n ] = wp_kses_post( $qa['a'] );
				$n++;
			}
			if ( $titles ) {
				$s['faq']['faq_title']   = $titles;
				$s['faq']['faq_content'] = $bodies;
				$done[] = count( $titles ) . ' FAQs';
			}
		}
		if ( ! empty( $item['highlights'] ) && is_array( $item['highlights'] ) ) {
			$s['trip_highlights'] = array();
			foreach ( $item['highlights'] as $h ) {
				$s['trip_highlights'][] = array( 'highlight_text' => sanitize_text_field( $h ) );
			}
			$done[] = 'highlights';
		}
		update_post_meta( $id, 'wp_travel_engine_setting', $s );

		if ( ! empty( $item['featured_image']['url'] ) ) {
			if ( $time_ok() ) {
				$att = divulge_to_image( $item['featured_image']['url'], isset( $item['featured_image']['alt'] ) ? $item['featured_image']['alt'] : '', $id );
				if ( $att ) {
					set_post_thumbnail( $id, $att );
					$done[] = 'featured image';
				} else {
					$done[] = 'featured image FAILED';
				}
			} else {
				$photos_left = true;
			}
		}
		if ( ! empty( $item['gallery'] ) && is_array( $item['gallery'] ) ) {
			$g     = array( 'enable' => '1' );
			$total = 0;
			foreach ( $item['gallery'] as $img ) {
				if ( empty( $img['url'] ) ) {
					continue;
				}
				$total++;
				if ( ! $time_ok() ) {
					$photos_left = true;
					continue;
				}
				$att = divulge_to_image( $img['url'], isset( $img['alt'] ) ? $img['alt'] : '', $id );
				if ( $att ) {
					$g[] = $att;
				}
			}
			if ( count( $g ) > 1 ) {
				update_post_meta( $id, 'wpte_gallery_id', $g );
				$done[] = ( count( $g ) - 1 ) . ' of ' . $total . ' gallery photos';
			}
		}
		if ( isset( $item['status'] ) && $item['status'] === 'publish' && get_post_status( $id ) !== 'publish' ) {
			if ( has_post_thumbnail( $id ) ) {
				wp_update_post( array( 'ID' => $id, 'post_status' => 'publish' ) );
				$done[] = 'PUBLISHED';
			} else {
				$done[] = 'not published (no featured image)';
			}
		}
		$report[] = get_the_title( $id ) . ': ' . ( $done ? implode( ', ', $done ) : 'nothing to change' );
	}
	if ( $photos_left ) {
		$report[] = 'MORE PHOTOS TO ADD: the server time limit was reached. Choose the same file and click Import again to add the remaining photos (finished photos are skipped).';
	}
	$GLOBALS['divulge_to_photos_left'] = $photos_left;
	return $report;
}

/* ---------- GitHub auto-import ---------- */
// Package files live in github.com/divulgeadventures/divulge-blog/trips/*.json (public repo).
// Every 5 minutes the site imports any new or changed file, a few photos at a time, until complete.

const DIVULGE_TO_GH_API   = 'https://api.github.com/repos/divulgeadventures/divulge-blog/contents/trips?ref=main';
const DIVULGE_TO_GH_STATE = 'divulge_to_gh_state';

add_filter( 'cron_schedules', function ( $s ) {
	$s['divulge_to_5min'] = array( 'interval' => 300, 'display' => 'Every 5 minutes (Trip Optimiser)' );
	return $s;
} );
add_action( 'init', function () {
	if ( ! wp_next_scheduled( 'divulge_to_github_sync' ) ) {
		wp_schedule_event( time() + 60, 'divulge_to_5min', 'divulge_to_github_sync' );
	}
} );
add_action( 'divulge_to_github_sync', 'divulge_to_github_run' );

function divulge_to_gh_get( $url, $raw = false ) {
	$r = wp_remote_get( $url, array(
		'timeout' => 15,
		'headers' => array(
			'User-Agent' => 'Divulge-Trip-Optimiser',
			'Accept'     => $raw ? 'application/vnd.github.raw+json' : 'application/vnd.github+json',
		),
	) );
	if ( is_wp_error( $r ) ) {
		return $r;
	}
	if ( (int) wp_remote_retrieve_response_code( $r ) !== 200 ) {
		return new WP_Error( 'divulge_to_gh', 'GitHub returned HTTP ' . wp_remote_retrieve_response_code( $r ) );
	}
	return wp_remote_retrieve_body( $r );
}

function divulge_to_github_run() {
	if ( get_transient( 'divulge_to_gh_lock' ) ) {
		return array( 'Another import is already running. Try again in a minute.' );
	}
	set_transient( 'divulge_to_gh_lock', 1, 120 );
	$GLOBALS['divulge_to_start'] = microtime( true );
	$log   = array();
	$state = get_option( DIVULGE_TO_GH_STATE, array() );

	$list = divulge_to_gh_get( DIVULGE_TO_GH_API );
	if ( is_wp_error( $list ) ) {
		delete_transient( 'divulge_to_gh_lock' );
		$state['_last'] = array( 'time' => current_time( 'mysql' ), 'msg' => $list->get_error_message() );
		update_option( DIVULGE_TO_GH_STATE, $state, false );
		return array( 'Could not read GitHub: ' . $list->get_error_message() );
	}
	$files = json_decode( $list, true );
	$files = is_array( $files ) ? $files : array();
	usort( $files, function ( $a, $b ) {
		return strcmp( $a['name'], $b['name'] );
	} );

	foreach ( $files as $f ) {
		if ( empty( $f['name'] ) || substr( $f['name'], -5 ) !== '.json' ) {
			continue;
		}
		$name = $f['name'];
		if ( isset( $state[ $name ] ) && $state[ $name ]['sha'] === $f['sha'] && $state[ $name ]['status'] === 'done' ) {
			continue;
		}
		if ( microtime( true ) - $GLOBALS['divulge_to_start'] > 14 ) {
			$log[] = 'Time limit reached, the rest continues in the next run.';
			break;
		}
		$body = divulge_to_gh_get( $f['download_url'], true );
		if ( is_wp_error( $body ) ) {
			$log[] = $name . ': ' . $body->get_error_message();
			continue;
		}
		$GLOBALS['divulge_to_photos_left'] = false;
		$report = divulge_to_import( $body );
		$status = $GLOBALS['divulge_to_photos_left'] ? 'partial' : 'done';
		$state[ $name ] = array( 'sha' => $f['sha'], 'status' => $status, 'time' => current_time( 'mysql' ), 'report' => implode( ' | ', $report ) );
		$log[] = $name . ' (' . $status . '): ' . implode( ' | ', $report );
		update_option( DIVULGE_TO_GH_STATE, $state, false );
		if ( $status === 'partial' ) {
			break; // finish this file's photos in the next run
		}
	}
	$state['_last'] = array( 'time' => current_time( 'mysql' ), 'msg' => $log ? 'Worked on: ' . count( $log ) . ' item(s)' : 'Nothing new on GitHub' );
	update_option( DIVULGE_TO_GH_STATE, $state, false );
	delete_transient( 'divulge_to_gh_lock' );
	return $log ? $log : array( 'Nothing new on GitHub. All package files are already imported.' );
}

function divulge_to_restore() {
	$report = array();
	foreach ( divulge_to_trips() as $t ) {
		$b = get_post_meta( $t->ID, DIVULGE_TO_BACKUP, true );
		if ( ! is_array( $b ) ) {
			continue;
		}
		wp_update_post( array( 'ID' => $t->ID, 'post_content' => $b['post_content'], 'post_excerpt' => $b['post_excerpt'] ) );
		update_post_meta( $t->ID, 'wp_travel_engine_setting', $b['settings'] );
		update_post_meta( $t->ID, 'wpte_gallery_id', $b['gallery'] );
		if ( $b['thumbnail'] ) {
			set_post_thumbnail( $t->ID, $b['thumbnail'] );
		} else {
			delete_post_thumbnail( $t->ID );
		}
		update_post_meta( $t->ID, 'rank_math_title', $b['rm_title'] );
		update_post_meta( $t->ID, 'rank_math_description', $b['rm_desc'] );
		update_post_meta( $t->ID, 'rank_math_focus_keyword', $b['rm_keyword'] );
		delete_post_meta( $t->ID, DIVULGE_TO_BACKUP );
		$report[] = $t->post_title . ': restored to ' . $b['time'];
	}
	return $report ? $report : array( 'No backups found, nothing to restore.' );
}

/* ---------- structured data on trip pages (TouristTrip) ---------- */
// Google and AI answer engines read this JSON-LD. Built only from what is saved on the trip
// (itinerary day titles, SEO description, featured image), so it always matches the page.

add_action( 'wp_head', function () {
	if ( ! is_singular( 'trip' ) ) {
		return;
	}
	$id = get_queried_object_id();
	$s  = get_post_meta( $id, 'wp_travel_engine_setting', true );
	$s  = is_array( $s ) ? $s : array();
	$url    = get_permalink( $id );
	$graph  = array();
	$clean  = function ( $text ) {
		return trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $text ) ) );
	};

	// FAQPage is not added here: WP Travel Engine already outputs it from the FAQ tab.

	// TouristTrip: name, description, photo, provider and the day-by-day titles.
	$desc = get_post_meta( $id, 'rank_math_description', true );
	if ( ! $desc ) {
		$desc = wp_trim_words( $clean( isset( $s['tab_content']['1_wpeditor'] ) ? $s['tab_content']['1_wpeditor'] : get_post_field( 'post_content', $id ) ), 40, '' );
	}
	$trip = array(
		'@type'       => 'TouristTrip',
		'@id'         => $url . '#trip',
		'name'        => get_the_title( $id ),
		'url'         => $url,
		'description' => $clean( $desc ),
		'provider'    => array(
			'@type' => 'TravelAgency',
			'name'  => 'Divulge Adventures',
			'url'   => home_url( '/' ),
			'telephone' => '+254706223888',
			'email' => 'bookings@divulgeadventures.com',
			'address' => array( '@type' => 'PostalAddress', 'addressLocality' => 'Nairobi', 'addressCountry' => 'KE' ),
		),
	);
	$img = get_the_post_thumbnail_url( $id, 'full' );
	if ( $img ) {
		$trip['image'] = $img;
	}
	if ( ! empty( $s['itinerary']['itinerary_title'] ) && is_array( $s['itinerary']['itinerary_title'] ) ) {
		$days = array();
		$pos  = 1;
		foreach ( $s['itinerary']['itinerary_title'] as $title ) {
			$title = $clean( $title );
			if ( $title !== '' ) {
				$days[] = array( '@type' => 'ListItem', 'position' => $pos++, 'item' => array( '@type' => 'TouristAttraction', 'name' => $title ) );
			}
		}
		if ( $days ) {
			$trip['itinerary'] = array( '@type' => 'ItemList', 'numberOfItems' => count( $days ), 'itemListElement' => $days );
		}
	}
	$graph[] = $trip;

	echo "\n<script type=\"application/ld+json\">" . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
}, 20 );

/* ---------- page ---------- */

function divulge_to_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$report = array();
	if ( isset( $_POST['divulge_to_action'] ) && check_admin_referer( 'divulge_to_run' ) ) {
		$action = sanitize_key( $_POST['divulge_to_action'] );
		if ( $action === 'autofix' ) {
			$report = divulge_to_autofix();
			if ( ! $report ) {
				$report = array( 'Nothing needed fixing.' );
			}
		} elseif ( $action === 'import' ) {
			if ( ! empty( $_FILES['divulge_to_file']['tmp_name'] ) ) {
				$report = divulge_to_import( file_get_contents( $_FILES['divulge_to_file']['tmp_name'] ) );
			} else {
				$report = array( 'Please choose a JSON file to import.' );
			}
		} elseif ( $action === 'restore' ) {
			$report = divulge_to_restore();
		} elseif ( $action === 'github' ) {
			$report = divulge_to_github_run();
		}
	}
	$backups = 0;
	foreach ( divulge_to_trips() as $t ) {
		if ( metadata_exists( 'post', $t->ID, DIVULGE_TO_BACKUP ) ) {
			$backups++;
		}
	}
	?>
	<div class="wrap">
		<h1>Trip Optimiser</h1>
		<?php if ( $report ) : ?>
			<div class="notice notice-success"><p><strong>Done:</strong></p><ul style="list-style:disc;margin-left:20px">
				<?php foreach ( $report as $line ) : ?><li><?php echo esc_html( $line ); ?></li><?php endforeach; ?>
			</ul></div>
		<?php endif; ?>

		<h2>1. Export trips</h2>
		<p>Downloads every trip (overview, itinerary, FAQs, SEO fields and photo details) as a JSON file. It changes nothing.</p>
		<p><a class="button button-primary" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=divulge_to_export' ), 'divulge_to_export' ) ); ?>">Download trips JSON</a></p>

		<h2>2. Quick auto-fix</h2>
		<p>Splits long paragraphs (over <?php echo (int) DIVULGE_TO_MAX_WORDS; ?> words) in every trip's overview, itinerary and FAQs, and keeps only the first focus keyword where several are listed. Each trip is backed up before its first change.</p>
		<form method="post"><?php wp_nonce_field( 'divulge_to_run' ); ?><input type="hidden" name="divulge_to_action" value="autofix">
			<p><button class="button button-primary" onclick="return confirm('Run the auto-fix on all trips? Each trip is backed up first.');">Run auto-fix</button></p>
		</form>

		<h2>3. Import optimised trips</h2>
		<p>Upload the optimised JSON file you received. It updates SEO title, description, focus keyword, overview, highlights, FAQs, featured image and gallery for the trips in the file.</p>
		<form method="post" enctype="multipart/form-data"><?php wp_nonce_field( 'divulge_to_run' ); ?><input type="hidden" name="divulge_to_action" value="import">
			<p><input type="file" name="divulge_to_file" accept=".json,application/json"> <button class="button button-primary" onclick="return confirm('Import this file? Each trip is backed up first.');">Import</button></p>
		</form>

		<h2>GitHub auto-import (automatic)</h2>
		<?php $gh = get_option( DIVULGE_TO_GH_STATE, array() ); ?>
		<p>Every 5 minutes the site checks <code>github.com/divulgeadventures/divulge-blog/trips</code> and imports new or changed package files, a few photos at a time, until each is complete. It only ever changes trip packages, never blog posts.</p>
		<p><strong>Last check:</strong> <?php echo isset( $gh['_last'] ) ? esc_html( $gh['_last']['time'] . ' (' . $gh['_last']['msg'] . ')' ) : 'not yet'; ?></p>
		<?php
		$rows = array_filter( $gh, function ( $k ) {
			return $k !== '_last';
		}, ARRAY_FILTER_USE_KEY );
		if ( $rows ) :
			?>
			<table class="widefat striped" style="max-width:1000px"><thead><tr><th>File</th><th>Status</th><th>Last run</th><th>Result</th></tr></thead><tbody>
			<?php foreach ( $rows as $file => $r ) : ?>
				<tr><td><?php echo esc_html( $file ); ?></td><td><?php echo esc_html( $r['status'] === 'done' ? 'Complete' : 'In progress (photos)' ); ?></td><td><?php echo esc_html( $r['time'] ); ?></td><td><?php echo esc_html( $r['report'] ); ?></td></tr>
			<?php endforeach; ?>
			</tbody></table>
		<?php endif; ?>
		<form method="post"><?php wp_nonce_field( 'divulge_to_run' ); ?><input type="hidden" name="divulge_to_action" value="github">
			<p><button class="button">Check GitHub now</button></p>
		</form>

		<h2>4. Restore</h2>
		<p><?php echo (int) $backups; ?> trip(s) have a backup. Restore puts them back exactly as they were before the first change.</p>
		<form method="post"><?php wp_nonce_field( 'divulge_to_run' ); ?><input type="hidden" name="divulge_to_action" value="restore">
			<p><button class="button" onclick="return confirm('Restore all backed-up trips to their original state?');">Restore all</button></p>
		</form>
		<p><em>After any change, open a trip in the editor and click Save to see its updated Rank Math score.</em></p>
	</div>
	<?php
}
