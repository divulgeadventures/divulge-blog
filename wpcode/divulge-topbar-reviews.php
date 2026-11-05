/**
 * Divulge – top bar reviews
 * Replaces the social media icons in the green top bar with a mini review strip
 * (TripAdvisor, SafariBookings, Google logos + rating) and turns the phone number
 * into a click-to-chat WhatsApp link. Needs the "Divulge – Review trust bar" snippet
 * (for the logos and live numbers). No theme settings to change.
 * WPCode: PHP Snippet, Auto Insert, Run Everywhere.
 */

add_action( 'wp_footer', function () {
	if ( is_admin() || ! function_exists( 'divulge_rv_data' ) ) {
		return;
	}
	$items = array();
	foreach ( divulge_rv_data() as $r ) {
		if ( $r[1] === '' ) {
			continue;
		}
		$items[] = '<a href="' . esc_url( $r[3] ) . '" target="_blank" rel="noopener" title="' . esc_attr( $r[0] . ': ' . $r[1] . '/5 from ' . $r[2] . '+ reviews' ) . '" style="display:inline-flex; align-items:center; gap:6px; color:#ffffff; text-decoration:none; white-space:nowrap;">'
			. ( $r[4] ? '<img src="' . esc_url( $r[4] ) . '" alt="' . esc_attr( $r[0] ) . '" style="width:20px; height:20px; border-radius:50%; background:#ffffff; object-fit:contain; margin:0;" />' : '' )
			. '<strong style="font-size:14px; color:#ffffff;">' . esc_html( $r[1] ) . '</strong><span style="color:#F2B544; font-size:14px;">&#9733;</span></a>';
	}
	if ( ! $items ) {
		return;
	}
	$strip = '<span class="divulge-topbar-reviews" style="display:inline-flex; align-items:center; gap:14px;">' . implode( '', $items ) . '</span>';
	?>
	<script>
	(function () {
		var strip = <?php echo wp_json_encode( $strip ); ?>;
		function run() {
			var head = document.querySelector('header') || document.body;
			if (head.querySelector('.divulge-topbar-reviews')) return;
			// Social icons: the smallest block holding 2+ social links.
			var first = head.querySelector('a[href*="facebook.com"], a[href*="instagram.com"]');
			if (first) {
				var box = first.parentElement;
				while (box && box !== head && box.querySelectorAll('a[href*="facebook.com"], a[href*="instagram.com"], a[href*="tripadvisor"], a[href*="wa.me"], a[href*="whatsapp"]').length < 2) {
					box = box.parentElement;
				}
				if (box && box !== head) box.innerHTML = strip;
			}
			// Phone number opens WhatsApp chat.
			head.querySelectorAll('a[href^="tel:"]').forEach(function (a) {
				a.href = 'https://wa.me/254706223888?text=' + encodeURIComponent('Hi Divulge Adventures, I would like to plan a safari.');
				a.target = '_blank';
				a.rel = 'noopener';
			});
		}
		if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run); else run();
	})();
	</script>
	<?php
}, 99 );
