/**
 * Divulge Blog Sync
 * Pulls new Divulge Diaries posts from a GitHub repo and publishes them on divulgeadventures.com.
 *
 * WPCode: Code Snippets > Add Snippet > Add Your Custom Code > PHP Snippet > paste everything below.
 * Insert Method: Auto Insert, Location: Run Everywhere > set to Active > Save.
 *
 * Each post is a JSON file in the repo's /posts folder. The site checks hourly,
 * imports any file it hasn't seen, sets the category, tags, Rank Math SEO fields,
 * featured image and FAQ schema, then publishes (or schedules it if "publish_at" is in the future).
 *
 * Run it on demand (admins only): Tools > Divulge Blog Sync > Sync now, or visit /wp-admin/?dvg_sync=1
 */

// ---- Settings -------------------------------------------------------------
if ( ! defined( 'DVG_GH_OWNER' ) )  define( 'DVG_GH_OWNER', 'divulgeadventures' );
if ( ! defined( 'DVG_GH_REPO' ) )   define( 'DVG_GH_REPO', 'divulge-blog' );
if ( ! defined( 'DVG_GH_BRANCH' ) ) define( 'DVG_GH_BRANCH', 'main' );
if ( ! defined( 'DVG_GH_TOKEN' ) )  define( 'DVG_GH_TOKEN', '' );  // leave empty for a public repo
if ( ! defined( 'DVG_AUTHOR_ID' ) ) define( 'DVG_AUTHOR_ID', 1 );  // WordPress user ID shown as author (Users > hover the name > user_id in the link)
// ---------------------------------------------------------------------------

add_action( 'init', function () {
	if ( ! wp_next_scheduled( 'dvg_blog_sync' ) ) {
		wp_schedule_event( time() + 60, 'hourly', 'dvg_blog_sync' );
	}
} );
add_action( 'dvg_blog_sync', 'dvg_blog_sync_run' );

if ( ! function_exists( 'dvg_gh_get' ) ) {
	function dvg_gh_get( $url, $raw = false ) {
		$headers = array(
			'User-Agent' => 'Divulge-Blog-Sync',
			'Accept'     => $raw ? 'application/vnd.github.raw+json' : 'application/vnd.github+json',
		);
		if ( DVG_GH_TOKEN ) {
			$headers['Authorization'] = 'Bearer ' . DVG_GH_TOKEN;
		}
		$r = wp_remote_get( $url, array( 'headers' => $headers, 'timeout' => 25 ) );
		if ( is_wp_error( $r ) ) {
			return new WP_Error( 'dvg_http', $r->get_error_message() );
		}
		$code = wp_remote_retrieve_response_code( $r );
		if ( 200 !== (int) $code ) {
			return new WP_Error( 'dvg_http', 'GitHub returned HTTP ' . $code . ' for ' . $url );
		}
		return wp_remote_retrieve_body( $r );
	}
}

if ( ! function_exists( 'dvg_find_category' ) ) {
	// Finds a category by name, by its HTML-escaped name (e.g. "&amp;") or by slug.
	function dvg_find_category( $name ) {
		foreach ( array( $name, esc_html( $name ), htmlspecialchars( $name, ENT_QUOTES ) ) as $n ) {
			$t = get_term_by( 'name', $n, 'category' );
			if ( $t ) {
				return (int) $t->term_id;
			}
		}
		$t = get_term_by( 'slug', sanitize_title( $name ), 'category' );
		return $t ? (int) $t->term_id : 0;
	}
}

if ( ! function_exists( 'dvg_save_faq' ) ) {
	function dvg_save_faq( $id, $p ) {
		if ( empty( $p['faq'] ) || ! is_array( $p['faq'] ) ) {
			return;
		}
		$faq = array();
		foreach ( $p['faq'] as $item ) {
			if ( ! empty( $item['q'] ) && ! empty( $item['a'] ) ) {
				$faq[] = array( 'q' => sanitize_text_field( $item['q'] ), 'a' => sanitize_textarea_field( $item['a'] ) );
			}
		}
		if ( $faq ) {
			update_post_meta( $id, '_dvg_faq', $faq );
		}
	}
}

if ( ! function_exists( 'dvg_set_featured' ) ) {
	// Sets a featured image from a URL: reuses a Media Library item if the URL is already there, otherwise downloads it.
	function dvg_set_featured( $post_id, $url, $alt, $slug ) {
		$existing = attachment_url_to_postid( $url );
		if ( $existing ) {
			set_post_thumbnail( $post_id, $existing );
			return true;
		}
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$tmp = download_url( esc_url_raw( $url ), 30 );
		if ( is_wp_error( $tmp ) ) {
			return $tmp;
		}
		$exts = array( 'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif' );
		$mime = wp_get_image_mime( $tmp );
		if ( ! isset( $exts[ $mime ] ) ) {
			@unlink( $tmp );
			return new WP_Error( 'dvg_img', 'not a supported image type' );
		}
		$img_id = media_handle_sideload( array( 'name' => $slug . '.' . $exts[ $mime ], 'tmp_name' => $tmp ), $post_id, $alt );
		if ( is_wp_error( $img_id ) ) {
			@unlink( $tmp );
			return $img_id;
		}
		set_post_thumbnail( $post_id, $img_id );
		update_post_meta( $img_id, '_wp_attachment_image_alt', $alt );
		return true;
	}
}

if ( ! function_exists( 'dvg_save_seo' ) ) {
	function dvg_save_seo( $id, $p ) {
		foreach ( array( 'focus_keyword' => 'rank_math_focus_keyword', 'meta_title' => 'rank_math_title', 'meta_description' => 'rank_math_description' ) as $k => $meta ) {
			if ( ! empty( $p[ $k ] ) ) {
				update_post_meta( $id, $meta, sanitize_text_field( $p[ $k ] ) );
			}
		}
	}
}

if ( ! function_exists( 'dvg_blog_sync_run' ) ) {
	function dvg_blog_sync_run() {
		if ( get_transient( 'dvg_sync_lock' ) ) {
			return array( 'Another sync is already running.' );
		}
		set_transient( 'dvg_sync_lock', 1, 10 * MINUTE_IN_SECONDS );

		$log  = array();
		$done = get_option( 'dvg_synced_files', array() );
		if ( ! is_array( $done ) ) {
			$done = array();
		}

		$list_url = sprintf(
			'https://api.github.com/repos/%s/%s/contents/posts?ref=%s',
			rawurlencode( DVG_GH_OWNER ), rawurlencode( DVG_GH_REPO ), rawurlencode( DVG_GH_BRANCH )
		);
		$list = dvg_gh_get( $list_url );
		if ( is_wp_error( $list ) ) {
			$log[] = $list->get_error_message();
			dvg_sync_finish( $log, $done );
			return $log;
		}
		$files = json_decode( $list, true );
		if ( ! is_array( $files ) ) {
			$files = array();
		}
		usort( $files, function ( $a, $b ) {
			return strcmp( $a['name'] ?? '', $b['name'] ?? '' );
		} );

		foreach ( $files as $f ) {
			$name = $f['name'] ?? '';
			if ( '.json' !== substr( $name, -5 ) || in_array( $name, $done, true ) ) {
				continue;
			}

			$raw = dvg_gh_get( $f['url'], true );
			if ( is_wp_error( $raw ) ) {
				$log[] = $name . ': ' . $raw->get_error_message();
				continue; // retry next hour
			}
			$p = json_decode( $raw, true );
			if ( empty( $p['title'] ) || empty( $p['content'] ) ) {
				$log[]  = $name . ': skipped (missing title or content).';
				$done[] = $name;
				continue;
			}

			// REFRESH MODE: rewrite an existing post in place (same URL and date).
			if ( ! empty( $p['update_slug'] ) ) {
				$log[]  = $name . ': ' . dvg_refresh_post( $p );
				$done[] = $name;
				continue;
			}

			$slug = sanitize_title( ! empty( $p['slug'] ) ? $p['slug'] : $p['title'] );
			if ( get_page_by_path( $slug, OBJECT, array( 'post', 'page' ) ) ) {
				$log[]  = $name . ': skipped (a post or page with slug "' . $slug . '" already exists).';
				$done[] = $name;
				continue;
			}

			$cat_ids = array();
			if ( ! empty( $p['category'] ) ) {
				$cid = dvg_find_category( $p['category'] );
				if ( $cid ) {
					$cat_ids[] = $cid;
				} else {
					$log[] = $name . ': category "' . $p['category'] . '" not found, post filed as Uncategorized.';
				}
			}

			$status = ( isset( $p['status'] ) && 'draft' === $p['status'] ) ? 'draft' : 'publish';
			$args   = array(
				'post_type'     => 'post',
				'post_title'    => wp_strip_all_tags( $p['title'] ),
				'post_name'     => $slug,
				'post_content'  => wp_kses_post( $p['content'] ),
				'post_excerpt'  => sanitize_text_field( $p['excerpt'] ?? '' ),
				'post_status'   => $status,
				'post_author'   => (int) DVG_AUTHOR_ID,
				'post_category' => $cat_ids,
				'tags_input'    => array_map( 'sanitize_text_field', (array) ( $p['tags'] ?? array() ) ),
			);

			// Schedule for later if publish_at is in the future.
			if ( 'publish' === $status && ! empty( $p['publish_at'] ) ) {
				$ts = strtotime( $p['publish_at'] );
				if ( $ts && $ts > time() + 60 ) {
					$args['post_status']   = 'future';
					$args['post_date']     = wp_date( 'Y-m-d H:i:s', $ts );
					$args['post_date_gmt'] = gmdate( 'Y-m-d H:i:s', $ts );
				}
			}

			$id = wp_insert_post( $args, true );
			if ( is_wp_error( $id ) ) {
				$log[] = $name . ': ' . $id->get_error_message();
				continue;
			}

			dvg_save_seo( $id, $p );
			dvg_save_faq( $id, $p );
			update_post_meta( $id, '_dvg_source_file', $name );

			$note = '';
			if ( ! empty( $p['featured_image'] ) ) {
				$alt = sanitize_text_field( $p['featured_image_alt'] ?? $p['title'] );
				$img = dvg_set_featured( $id, $p['featured_image'], $alt, $slug );
				if ( is_wp_error( $img ) ) {
					$note = ', featured image failed (' . $img->get_error_message() . ')';
				}
			}

			$done[] = $name;
			$log[]  = $name . ': imported as post #' . $id . ' (' . get_post_status( $id ) . ')' . $note . '.';
		}

		if ( empty( $log ) ) {
			$log[] = 'Nothing new.';
		}
		dvg_sync_finish( $log, $done );
		return $log;
	}
}

if ( ! function_exists( 'dvg_refresh_post' ) ) {
	// Rewrites an existing published post. Keeps its URL (slug), date and author.
	// WordPress saves the previous version as a revision (Posts > Edit > Revisions).
	function dvg_refresh_post( $p ) {
		$slug = sanitize_title( $p['update_slug'] );
		$post = get_page_by_path( $slug, OBJECT, 'post' );
		if ( ! $post ) {
			return 'refresh skipped (no post with slug "' . $slug . '").';
		}
		$id   = $post->ID;
		$args = array(
			'ID'           => $id,
			'post_title'   => wp_strip_all_tags( $p['title'] ),
			'post_content' => wp_kses_post( $p['content'] ),
		);
		if ( isset( $p['excerpt'] ) ) {
			$args['post_excerpt'] = sanitize_text_field( $p['excerpt'] );
		}
		if ( ! empty( $p['category'] ) ) {
			$cid = dvg_find_category( $p['category'] );
			if ( $cid ) {
				$args['post_category'] = array( $cid );
			}
		}
		// If the old post was built with Elementor, back up its layout and switch to the new content.
		if ( 'builder' === get_post_meta( $id, '_elementor_edit_mode', true ) ) {
			update_post_meta( $id, '_dvg_elementor_backup', get_post_meta( $id, '_elementor_data', true ) );
			delete_post_meta( $id, '_elementor_edit_mode' );
		}
		$r = wp_update_post( $args, true );
		if ( is_wp_error( $r ) ) {
			return 'refresh failed: ' . $r->get_error_message();
		}
		if ( ! empty( $p['tags'] ) ) {
			wp_set_post_tags( $id, array_map( 'sanitize_text_field', (array) $p['tags'] ), false );
		}
		dvg_save_seo( $id, $p );
		dvg_save_faq( $id, $p );
		update_post_meta( $id, '_dvg_refreshed', current_time( 'mysql' ) );
		$note = '';
		if ( ! empty( $p['featured_image'] ) ) {
			$alt = sanitize_text_field( $p['featured_image_alt'] ?? $p['title'] );
			$img = dvg_set_featured( $id, $p['featured_image'], $alt, $slug );
			if ( is_wp_error( $img ) ) {
				$note = ' (featured image failed: ' . $img->get_error_message() . ')';
			}
		}
		return 'refreshed post #' . $id . ' (' . $slug . ')' . $note . '.';
	}
}

if ( ! function_exists( 'dvg_sync_finish' ) ) {
	function dvg_sync_finish( $log, $done ) {
		update_option( 'dvg_synced_files', array_values( array_unique( $done ) ), false );
		update_option( 'dvg_sync_last_log', array( 'time' => current_time( 'mysql' ), 'log' => $log ), false );
		delete_transient( 'dvg_sync_lock' );
	}
}

// FAQPage schema for imported posts (read by Google and AI answer engines).
add_action( 'wp_head', function () {
	if ( ! is_singular( 'post' ) ) {
		return;
	}
	$faq = get_post_meta( get_the_ID(), '_dvg_faq', true );
	if ( empty( $faq ) || ! is_array( $faq ) ) {
		return;
	}
	$items = array();
	foreach ( $faq as $f ) {
		$items[] = array(
			'@type'          => 'Question',
			'name'           => $f['q'],
			'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f['a'] ),
		);
	}
	$schema = array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $items );
	echo "\n" . '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}, 30 );

// Tools > Divulge Blog Sync page: "Sync now" button and the last sync result.
add_action( 'admin_menu', function () {
	add_management_page( 'Divulge Blog Sync', 'Divulge Blog Sync', 'manage_options', 'dvg-blog-sync', function () {
		$ran = null;
		if ( isset( $_POST['dvg_sync_now'] ) && check_admin_referer( 'dvg_sync_now' ) ) {
			$ran = dvg_blog_sync_run();
		}
		$last = get_option( 'dvg_sync_last_log' );
		$next = wp_next_scheduled( 'dvg_blog_sync' );
		echo '<div class="wrap"><h1>Divulge Blog Sync</h1>';
		echo '<p>Imports new posts from GitHub (' . esc_html( DVG_GH_OWNER . '/' . DVG_GH_REPO ) . '). It also runs automatically every hour.</p>';
		if ( $ran ) {
			echo '<div class="notice notice-success"><p><strong>Sync result:</strong><br>' . implode( '<br>', array_map( 'esc_html', (array) $ran ) ) . '</p></div>';
		}
		echo '<form method="post">';
		wp_nonce_field( 'dvg_sync_now' );
		echo '<p><button type="submit" name="dvg_sync_now" value="1" class="button button-primary">Sync now</button></p></form>';
		if ( $last && ! $ran ) {
			echo '<h2>Last sync</h2><p>' . esc_html( $last['time'] ) . '<br>' . implode( '<br>', array_map( 'esc_html', (array) $last['log'] ) ) . '</p>';
		}
		echo '<p>Next automatic check: ' . ( $next ? esc_html( wp_date( 'j M Y, H:i', $next ) ) : 'not scheduled yet' ) . '</p>';
		echo '<p>Posts imported so far: ' . count( (array) get_option( 'dvg_synced_files', array() ) ) . '</p></div>';
	} );
} );

// Manual run for admins: /wp-admin/?dvg_sync=1
add_action( 'admin_init', function () {
	if ( isset( $_GET['dvg_sync'] ) && current_user_can( 'manage_options' ) ) {
		$log = dvg_blog_sync_run();
		set_transient( 'dvg_sync_notice', $log, 60 );
		wp_safe_redirect( admin_url( 'edit.php' ) );
		exit;
	}
} );
add_action( 'admin_notices', function () {
	$log = get_transient( 'dvg_sync_notice' );
	if ( ! $log ) {
		return;
	}
	delete_transient( 'dvg_sync_notice' );
	echo '<div class="notice notice-info is-dismissible"><p><strong>Divulge Blog Sync:</strong><br>' .
		implode( '<br>', array_map( 'esc_html', (array) $log ) ) . '</p></div>';
} );
