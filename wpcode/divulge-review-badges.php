/**
 * Divulge – review trust bar (TripAdvisor + SafariBookings)
 * 1. Shortcode [divulge_reviews] – place it anywhere with Elementor's Shortcode widget
 *    (homepage below the banner, footer). [divulge_reviews compact="1"] gives a one-line version.
 * 2. Shown automatically at the top of every safari package (trip) page and on the
 *    quote form, group inquiry, contact and about pages.
 * Update the numbers below when your review counts change.
 * WPCode: PHP Snippet, Auto Insert, Run Everywhere.
 */

function divulge_rv_data() {
	return array(
		array( 'TripAdvisor', '4.9', '74', 'https://www.tripadvisor.com/Attraction_Review-g294207-d21179070-Reviews-Divulge_Adventures-Nairobi.html' ),
		array( 'SafariBookings', '5.0', '66', 'https://www.safaribookings.com/reviews/p5368' ),
	);
}

function divulge_rv_bar( $compact = false ) {
	$items = array();
	foreach ( divulge_rv_data() as $r ) {
		$items[] = '<a href="' . esc_url( $r[3] ) . '" target="_blank" rel="noopener" style="color:#043D0E; text-decoration:none; white-space:nowrap;">'
			. '<span style="color:#C97A3D;">&#9733;&#9733;&#9733;&#9733;&#9733;</span> <strong>' . esc_html( $r[1] ) . '/5</strong> on ' . esc_html( $r[0] )
			. ' <span style="color:#555555;">(' . esc_html( $r[2] ) . ' reviews)</span></a>';
	}
	$line = implode( '<span style="color:#C97A3D; margin:0 10px;">&middot;</span>', $items );
	if ( $compact ) {
		return '<p class="divulge-reviews" style="margin:10px 0; font-size:14px; text-align:center; line-height:1.8;">' . $line . '</p>';
	}
	return '<div class="divulge-reviews" style="background-color:#f6efe7; border:1px solid #C97A3D; border-radius:10px; padding:12px 16px; margin:0 0 20px 0; text-align:center; font-size:15px; line-height:1.8;">'
		. '<span style="display:block; font-weight:bold; color:#043D0E; margin-bottom:2px;">Rated excellent by our travellers</span>' . $line . '</div>';
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
	$pages = array( 'safari-proposal-request', 'group-safari-inquiry', 'contact-divulge-adventures', 'about-us' );
	if ( is_singular( 'trip' ) || is_page( $pages ) ) {
		return divulge_rv_bar() . $content;
	}
	return $content;
}, 25 );
