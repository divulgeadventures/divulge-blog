/**
 * Divulge – plain country tags for Pinterest
 * Pinterest boards read /tag/kenya/feed/, /tag/tanzania/feed/ etc. Posts tagged only
 * "Kenya safari" never reach the Kenya board. This snippet:
 *  1. adds the plain country tag (Kenya, Tanzania, Uganda, Rwanda, Namibia, Botswana)
 *     to every blog post that is about that country, whenever a post is saved or published;
 *  2. once, fixes all existing posts (runs the first time an admin page loads).
 * Only adds tags – never removes any.
 */

function divulge_ct_countries() {
	return array(
		'Kenya'    => array( 'masai mara', 'maasai mara', 'amboseli', 'samburu', 'tsavo', 'lake nakuru', 'naivasha', 'ol pejeta', 'laikipia', 'diani', 'nairobi', 'watamu', 'lamu', 'mount kenya', 'aberdare' ),
		'Tanzania' => array( 'serengeti', 'ngorongoro', 'tarangire', 'zanzibar', 'lake manyara', 'arusha', 'ruaha', 'selous', 'nyerere', 'ndutu' ),
		'Uganda'   => array( 'bwindi', 'kibale', 'murchison', 'queen elizabeth national park', 'lake mburo', 'semliki' ),
		'Rwanda'   => array( 'volcanoes national park', 'akagera', 'nyungwe', 'kigali', 'lake kivu' ),
		'Namibia'  => array( 'etosha', 'sossusvlei', 'damaraland', 'skeleton coast', 'swakopmund', 'fish river canyon' ),
		'Botswana' => array( 'okavango', 'chobe', 'moremi', 'kalahari', 'makgadikgadi', 'khwai', 'savuti' ),
	);
}

function divulge_ct_tag_post( $post_id ) {
	if ( get_post_type( $post_id ) !== 'post' ) {
		return 0;
	}
	$tags  = wp_get_post_tags( $post_id, array( 'fields' => 'names' ) );
	$lower = array_map( 'strtolower', $tags );
	$named = strtolower( get_the_title( $post_id ) . ' ' . get_post_meta( $post_id, 'rank_math_focus_keyword', true ) . ' ' . implode( ' ', $tags ) );
	$main  = strtolower( get_the_title( $post_id ) . ' ' . get_post_meta( $post_id, 'rank_math_focus_keyword', true ) );
	$add   = array();
	foreach ( divulge_ct_countries() as $country => $places ) {
		$c = strtolower( $country );
		if ( in_array( $c, $lower, true ) ) {
			continue;
		}
		$hit = preg_match( '/\b' . $c . '\b/', $named );
		foreach ( $places as $place ) {
			if ( ! $hit && preg_match( '/\b' . preg_quote( $place, '/' ) . '\b/', $main ) ) {
				$hit = 1;
			}
		}
		if ( $hit ) {
			$add[] = $country;
		}
	}
	if ( $add ) {
		wp_set_post_tags( $post_id, $add, true ); // append, keep existing tags
	}
	return count( $add );
}

// New and updated posts (runs after the post and its tags are saved).
add_action( 'wp_after_insert_post', function ( $post_id, $post ) {
	if ( wp_is_post_revision( $post_id ) || $post->post_type !== 'post' ) {
		return;
	}
	divulge_ct_tag_post( $post_id );
}, 20, 2 );

// One-time fix for all existing posts.
add_action( 'admin_init', function () {
	if ( get_option( 'divulge_ct_backfill_done' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$ids   = get_posts( array( 'post_type' => 'post', 'post_status' => array( 'publish', 'future', 'draft' ), 'numberposts' => -1, 'fields' => 'ids' ) );
	$fixed = 0;
	foreach ( $ids as $id ) {
		$fixed += divulge_ct_tag_post( $id ) ? 1 : 0;
	}
	update_option( 'divulge_ct_backfill_done', current_time( 'mysql' ) . ' – ' . $fixed . ' posts tagged', false );
	set_transient( 'divulge_ct_notice', $fixed, 120 );
} );

add_action( 'admin_notices', function () {
	$n = get_transient( 'divulge_ct_notice' );
	if ( $n === false ) {
		return;
	}
	delete_transient( 'divulge_ct_notice' );
	echo '<div class="notice notice-success"><p>Country tags added to ' . (int) $n . ' blog posts, so they now reach the Pinterest country boards.</p></div>';
} );
