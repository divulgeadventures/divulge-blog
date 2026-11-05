/**
 * Divulge – review trust bar (TripAdvisor + SafariBookings)
 * 1. Shortcode [divulge_reviews] – place it anywhere with Elementor's Shortcode widget
 *    (homepage below the banner, footer). [divulge_reviews compact="1"] gives a one-line version.
 * 2. Shown automatically at the top of every safari package (trip) page and on the
 *    quote form, group inquiry, contact and about pages.
 * Review numbers update automatically each month from site-data/reviews.json in the blog repo.
 * Optional logo: upload the platform's official badge to Media and paste its URL as the 5th value.
 * WPCode: PHP Snippet, Auto Insert, Run Everywhere.
 */

function divulge_rv_data() {
	// Fallback numbers, used only if the live file below cannot be read.
	$rows = array(
		// Platform, rating, review count, review page URL, logo URL (optional)
		array( 'TripAdvisor', '4.9', '74', 'https://www.tripadvisor.com/Attraction_Review-g294207-d21179070-Reviews-Divulge_Adventures-Nairobi.html', 'https://divulgeadventures.com/wp-content/uploads/2026/11/tripadvisor-logo.png' ),
		array( 'SafariBookings', '5.0', '66', 'https://www.safaribookings.com/reviews/p5368', 'https://divulgeadventures.com/wp-content/uploads/2026/11/sfaribookings-logo.png' ),
		array( 'Google', '', '', 'https://g.page/r/CVKcxEFvUH1zEAI/review', 'https://divulgeadventures.com/wp-content/uploads/2026/11/google-logo.png' ),
	);

	// Live numbers: a monthly task refreshes site-data/reviews.json in the blog repo.
	// Read at most every 12 hours; logos always come from the rows above.
	$live = get_transient( 'divulge_rv_live' );
	if ( false === $live ) {
		$args = array( 'timeout' => 5 );
		if ( defined( 'DVG_GH_TOKEN' ) && DVG_GH_TOKEN ) {
			$args['headers'] = array( 'Authorization' => 'Bearer ' . DVG_GH_TOKEN );
		}
		$res  = wp_remote_get( 'https://raw.githubusercontent.com/divulgeadventures/divulge-blog/main/site-data/reviews.json', $args );
		$json = ( ! is_wp_error( $res ) && 200 === wp_remote_retrieve_response_code( $res ) ) ? json_decode( wp_remote_retrieve_body( $res ), true ) : null;
		$live = ( is_array( $json ) && ! empty( $json['platforms'] ) ) ? $json['platforms'] : array();
		set_transient( 'divulge_rv_live', $live, $live ? 12 * HOUR_IN_SECONDS : HOUR_IN_SECONDS );
	}
	foreach ( $rows as &$row ) {
		foreach ( (array) $live as $p ) {
			if ( isset( $p['name'] ) && $p['name'] === $row[0] && ! empty( $p['rating'] ) ) {
				$row[1] = (string) $p['rating'];
				$row[2] = (string) ( $p['count'] ?? $row[2] );
				$row[3] = ! empty( $p['url'] ) ? $p['url'] : $row[3];
			}
		}
	}
	unset( $row );
	return $rows;
}

function divulge_rv_stars( $rating, $size ) {
	$out = '';
	for ( $i = 1; $i <= 5; $i++ ) {
		$fill = max( 0, min( 1, (float) $rating - ( $i - 1 ) ) ) * 100;
		$out .= '<span style="display:inline-block; font-size:' . $size . 'px; line-height:1; background:linear-gradient(90deg,#F2B544 ' . $fill . '%,rgba(255,255,255,0.3) ' . $fill . '%); -webkit-background-clip:text; background-clip:text; color:transparent;">&#9733;</span>';
	}
	return $out;
}

function divulge_rv_bar( $compact = false ) {
	if ( $compact ) {
		$items = array();
		foreach ( divulge_rv_data() as $r ) {
			if ( $r[1] === '' ) { continue; }
			$items[] = '<a href="' . esc_url( $r[3] ) . '" target="_blank" rel="noopener" style="color:#043D0E; text-decoration:none; white-space:nowrap;">'
				. '<span style="color:#C97A3D;">&#9733;</span> <strong>' . esc_html( $r[1] ) . '/5</strong> on ' . esc_html( $r[0] )
				. ' <span style="color:#555555;">(' . esc_html( $r[2] ) . '+ reviews)</span></a>';
		}
		return '<p class="divulge-reviews" style="margin:10px 0; font-size:14px; text-align:center; line-height:1.8;">'
			. implode( '<span style="color:#C97A3D; margin:0 10px;">&middot;</span>', $items ) . '</p>';
	}

	$cards = '';
	foreach ( divulge_rv_data() as $r ) {
		if ( $r[1] === '' ) { continue; }
		$icon = ! empty( $r[4] )
			? '<img src="' . esc_url( $r[4] ) . '" alt="' . esc_attr( $r[0] ) . '" style="width:44px; height:44px; border-radius:50%; object-fit:contain; background:#ffffff; flex:none;" />'
			: '<span style="display:inline-flex; align-items:center; justify-content:center; width:44px; height:44px; border-radius:50%; background:#ffffff; color:#043D0E; font-weight:bold; font-size:18px; flex:none;">' . esc_html( substr( $r[0], 0, 1 ) ) . '</span>';
		$cards .= '<a href="' . esc_url( $r[3] ) . '" target="_blank" rel="noopener" style="display:flex; align-items:center; gap:12px; color:#ffffff; text-decoration:none; padding:4px 8px;">'
			. $icon
			. '<span style="display:block; text-align:left; line-height:1.3;">'
			. '<span style="display:block; white-space:nowrap;"><strong style="font-size:20px; color:#ffffff;">' . esc_html( $r[1] ) . '/5</strong> ' . divulge_rv_stars( $r[1], 20 ) . '</span>'
			. '<span style="display:block; font-size:14px; font-style:italic; color:#ffffff;">Based on <span style="text-decoration:underline;">' . esc_html( $r[2] ) . '+ reviews</span> on ' . esc_html( $r[0] ) . '</span>'
			. '</span></a>';
	}
	return '<div class="divulge-reviews" style="background-color:#043D0E; border-radius:10px; padding:14px 16px; margin:0 0 20px 0; display:flex; flex-wrap:wrap; justify-content:center; align-items:center; gap:12px 40px;">'
		. $cards . '</div>';
}

add_shortcode( 'divulge_reviews', function ( $atts ) {
	$atts = shortcode_atts( array( 'compact' => '' ), $atts );
	return divulge_rv_bar( ! empty( $atts['compact'] ) );
} );

add_filter( 'the_content', function ( $content ) {
	if ( is_admin() || is_feed() || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	if ( strpos( $content, 'divulge-reviews' ) !== false ) {
		return $content;
	}
	// Show once per page: on trip pages that is under the title (WP Travel Engine runs
	// the content filter again for the Overview, which is skipped).
	static $shown = false;
	$pages = array( 'safari-proposal-request', 'group-safari-inquiry', 'contact-divulge-adventures', 'about-us' );
	if ( ! $shown && ( is_singular( 'trip' ) || is_page( $pages ) ) ) {
		$shown = true;
		return divulge_rv_bar() . $content;
	}
	return $content;
}, 25 );
