/**
 * Divulge – booking boxes for older blog posts
 * Older Divulge Diaries posts (written before the blog pipeline) have no booking boxes.
 * This snippet shows the three TEMPLATE.md boxes on any blog post that does not already
 * have them (no "wa.me/254706223888" link in its content):
 *  1. Trip box (green) – after the middle H2, linking the best-matching money page;
 *  2. "Get My Free Safari Quote" (orange) – before the last H2 (usually the FAQ);
 *  3. "Chat on WhatsApp" – at the very end, pre-filled with the post title.
 * Display only: post content in the database is never changed, so a later refresh that
 * adds the boxes for real simply switches this off for that post. Not shown in feeds.
 * WPCode: PHP Snippet, Auto Insert, Run Everywhere.
 */

function divulge_oc_money_page( $text ) {
	$text = strtolower( $text );
	$rules = array(
		array( array( 'team building', 'team-building', 'corporate' ), 'https://divulgeadventures.com/activities/corporate-team-building-events/', 'Plan your team building event', 'Venues, activities and transport arranged for your team by our Nairobi crew.', 'View Team Building Packages' ),
		array( array( 'gorilla', 'volcanoes national park', 'rwanda', 'kigali', 'akagera', 'nyungwe' ), 'https://divulgeadventures.com/destinations/rwanda-gorilla-trek/', 'See our gorilla trekking safaris', 'Permits, lodges and private transfers arranged for you, with expert local guides.', 'View Gorilla Treks' ),
		array( array( 'uganda', 'bwindi', 'kibale', 'murchison', 'queen elizabeth' ), 'https://divulgeadventures.com/destinations/uganda-safari/', 'See our Uganda safari packages', 'Tailor-made itineraries, private vehicles and expert local guides.', 'View Uganda Safaris' ),
		array( array( 'kilimanjaro', 'mount kenya', 'climb', 'hike', 'trek' ), 'https://divulgeadventures.com/mountain-climbing-safari-packages/', 'See our mountain climbing packages', 'Guided climbs with experienced crews, park fees and transfers arranged.', 'View Climbing Packages' ),
		array( array( 'tanzania', 'serengeti', 'ngorongoro', 'tarangire', 'zanzibar', 'manyara', 'arusha', 'ruaha', 'nyerere', 'ndutu' ), 'https://divulgeadventures.com/destinations/tanzania-safari/', 'See our Tanzania safari packages', 'Tailor-made itineraries, private vehicles and expert local guides.', 'View Tanzania Safaris' ),
		array( array( 'botswana', 'okavango', 'chobe', 'moremi', 'kalahari' ), 'https://divulgeadventures.com/destinations/botswana-safari/', 'See our Botswana safari packages', 'Tailor-made itineraries, fly-in camps and expert local guides.', 'View Botswana Safaris' ),
		array( array( 'namibia', 'etosha', 'sossusvlei', 'damaraland', 'skeleton coast' ), 'https://divulgeadventures.com/destinations/namibia-safari/', 'See our Namibia safari packages', 'Tailor-made itineraries, private vehicles and expert local guides.', 'View Namibia Safaris' ),
		array( array( 'birding', 'birds' ), 'https://divulgeadventures.com/activities/birding-safaris/', 'See our birding safaris', 'Tailor-made itineraries with guides who know their birds.', 'View Birding Safaris' ),
		array( array( 'beach', 'diani', 'watamu', 'lamu', 'mombasa' ), 'https://divulgeadventures.com/activities/bush-beach-safaris/', 'See our bush and beach safaris', 'Combine your safari with the Indian Ocean coast, all arranged for you.', 'View Bush & Beach Safaris' ),
	);
	foreach ( $rules as $r ) {
		foreach ( $r[0] as $word ) {
			if ( strpos( $text, $word ) !== false ) {
				return array_slice( $r, 1 );
			}
		}
	}
	return array( 'https://divulgeadventures.com/destinations/kenya-safari/', 'See our Kenya safari packages', 'Tailor-made itineraries, private vehicles and expert local guides.', 'View Kenya Safaris' );
}

function divulge_oc_box( $bg, $border, $title_color, $title, $text, $url, $btn_bg, $btn ) {
	return '<div class="divulge-cta" style="background-color:' . $bg . '; border:2px solid ' . $border . '; border-radius:10px; padding:22px; text-align:center; margin:25px 0;">'
		. '<p style="margin:0 0 14px 0; font-size:17px; font-weight:bold; color:' . $title_color . ';">' . esc_html( $title ) . '</p>'
		. '<p style="margin:0 0 16px 0; font-size:14px; color:#333333; line-height:1.6;">' . esc_html( $text ) . '</p>'
		. '<a href="' . esc_url( $url ) . '" target="_blank" style="display:inline-block; background-color:' . $btn_bg . '; color:#ffffff; padding:12px 28px; border-radius:30px; text-decoration:none; font-weight:bold; font-size:15px;">' . esc_html( $btn ) . '</a>'
		. '</div>';
}

add_filter( 'the_content', function ( $content ) {
	if ( is_admin() || is_feed() || ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	if ( strpos( $content, 'wa.me/254706223888' ) !== false || strpos( $content, 'divulge-cta' ) !== false ) {
		return $content;
	}

	$post  = get_post();
	$title = wp_strip_all_tags( get_the_title( $post ) );
	$team  = ( stripos( $title . ' ' . $post->post_name, 'team building' ) !== false || strpos( $post->post_name, 'team-building' ) !== false );
	$m     = divulge_oc_money_page( $post->post_name . ' ' . $title );

	$trip  = divulge_oc_box( '#eef4ef', '#043D0E', '#043D0E', $m[1], $m[2], $m[0], '#043D0E', $m[3] );
	$quote = $team
		? divulge_oc_box( '#f6efe7', '#C97A3D', '#1b1b1b', 'Let us plan your team day', 'Tell us your date, group size and budget, and our Nairobi team will send you a free tailor-made plan and quote.', 'https://divulgeadventures.com/safari-proposal-request/', '#C97A3D', 'Get My Free Quote' )
		: divulge_oc_box( '#f6efe7', '#C97A3D', '#1b1b1b', 'Let us plan your safari', 'Tell us your dates, budget and dream sightings, and our Nairobi team will send you a free tailor-made itinerary and quote.', 'https://divulgeadventures.com/safari-proposal-request/', '#C97A3D', 'Get My Free Safari Quote' );
	$wa    = divulge_oc_box( '#eef4ef', '#043D0E', '#1b1b1b', $team ? 'Planning a team outing?' : 'Have a question about your safari?', 'Chat with a Divulge Adventures consultant on WhatsApp for honest advice, a quick answer and a quote built around you.', 'https://wa.me/254706223888?text=' . rawurlencode( 'Hi Divulge Adventures, I just read your post on ' . $title ), '#25D366', 'Chat on WhatsApp' );

	// Positions of every H2 opening tag.
	preg_match_all( '/<h2[\s>]/i', $content, $h2, PREG_OFFSET_CAPTURE );
	$pos = wp_list_pluck( $h2[0], 1 );

	if ( count( $pos ) >= 3 ) {
		$last = end( $pos );
		$mid  = $pos[ (int) floor( count( $pos ) / 2 ) ];
		if ( $mid === $last ) {
			$mid = $pos[ count( $pos ) - 2 ];
		}
		// Insert the later one first so earlier offsets stay valid.
		$content = substr_replace( $content, $quote, $last, 0 );
		$content = substr_replace( $content, $trip, $mid, 0 );
	} else {
		$content .= $trip . $quote;
	}

	return $content . $wa;
}, 20 );
