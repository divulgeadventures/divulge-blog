/**
 * Divulge – top bar reviews
 * Green top bar: removes the social icons, shows the review cards (same style as the
 * trip pages) in the centre, makes the phone number a WhatsApp chat link with a
 * ready message, and shows the email as a short "Email" link.
 * Needs the "Divulge – Review trust bar" snippet to be active (logos + live numbers).
 * WPCode: PHP Snippet, Auto Insert, Run Everywhere.
 */

// Travellers' Choice badge image (Media Library URL). Leave empty to hide it.
if ( ! defined( 'DIVULGE_TC_BADGE' ) ) {
	define( 'DIVULGE_TC_BADGE', '' );
}

add_action( 'wp_footer', function () {
	if ( is_admin() || ! function_exists( 'divulge_rv_data' ) ) {
		return;
	}
	$cards = '';
	if ( DIVULGE_TC_BADGE ) {
		$cards .= '<a href="https://www.tripadvisor.com/Attraction_Review-g294207-d21179070-Reviews-Divulge_Adventures-Nairobi.html" target="_blank" rel="noopener" style="display:flex; align-items:center;"><img src="' . esc_url( DIVULGE_TC_BADGE ) . '" alt="Tripadvisor Travellers\' Choice" style="height:40px; width:auto; margin:0;" /></a>';
	}
	foreach ( divulge_rv_data() as $r ) {
		if ( $r[1] === '' ) {
			continue;
		}
		$cards .= '<a href="' . esc_url( $r[3] ) . '" target="_blank" rel="noopener" style="display:flex; align-items:center; gap:8px; color:#ffffff; text-decoration:none; white-space:nowrap;">'
			. ( $r[4] ? '<img src="' . esc_url( $r[4] ) . '" alt="' . esc_attr( $r[0] ) . '" style="width:32px; height:32px; border-radius:50%; background:#ffffff; object-fit:contain; flex:none; margin:0;" />' : '' )
			. '<span style="display:block; text-align:left; line-height:1.2;">'
			. '<span style="display:block;"><strong style="font-size:15px; color:#ffffff;">' . esc_html( $r[1] ) . '/5</strong> ' . divulge_rv_stars( $r[1], '14px' ) . '</span>'
			. '<span class="divulge-tb-based" style="display:block; font-size:12px; font-style:italic; color:#ffffff;">Based on <span style="text-decoration:underline;">' . esc_html( $r[2] ) . '+ reviews</span></span>'
			. '</span></a>';
	}
	$strip = '<div class="divulge-topbar-reviews" style="flex:1 1 auto; display:flex; justify-content:center; align-items:center; gap:24px; padding:4px 12px; min-width:0;">' . $cards . '</div>';
	$wa    = 'https://wa.me/254706223888?text=' . rawurlencode( 'Hi Divulge Adventures, I would like to plan a safari.' );
	?>
	<style>
	@media (max-width:1300px){ .divulge-tb-based{display:none !important;} .divulge-topbar-reviews{gap:14px !important;} }
	@media (max-width:1024px){ .divulge-topbar-reviews{display:none !important;} }
	</style>
	<script>
	(function () {
		var strip = <?php echo wp_json_encode( $strip ); ?>;
		var wa = <?php echo wp_json_encode( $wa ); ?>;
		var SOCIAL = 'a[href*="facebook.com"], a[href*="instagram.com"], a[href*="tripadvisor"], a[href*="wa.me"], a[href*="whatsapp"]';
		function run() {
			var head = document.querySelector('header') || document.body;
			if (head.querySelector('.divulge-topbar-reviews')) return;

			// 1. Phone number -> WhatsApp chat with a ready message.
			head.querySelectorAll('a[href^="tel:"]').forEach(function (a) {
				a.href = wa; a.target = '_blank'; a.rel = 'noopener';
			});

			// 2. Email address -> short "Email" link.
			head.querySelectorAll('a[href^="mailto:"]').forEach(function (a) {
				var w = document.createTreeWalker(a, NodeFilter.SHOW_TEXT);
				while (w.nextNode()) {
					if (w.currentNode.nodeValue.indexOf('@') > -1) w.currentNode.nodeValue = 'Email';
				}
			});

			// 3. Find the social icons block and the top bar row around it.
			var first = head.querySelector('a[href*="facebook.com"], a[href*="instagram.com"]');
			if (!first) return;
			var box = first.parentElement;
			while (box && box !== head && box.querySelectorAll(SOCIAL).length < 2) box = box.parentElement;
			if (!box || box === head) return;
			var row = box.parentElement;
			while (row && row !== head && !row.querySelector('a[href*="b2b-agent-rates"], nav, .menu')) row = row.parentElement;
			if (!row || row === head) return;

			// The row's direct child that holds the menu (right side).
			var menu = row.querySelector('a[href*="b2b-agent-rates"], nav, .menu');
			while (menu.parentElement && menu.parentElement !== row) menu = menu.parentElement;

			// 4. Remove the social icons, put the reviews between contacts and menu.
			box.style.display = 'none';
			menu.insertAdjacentHTML('beforebegin', strip);
		}
		if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run); else run();
	})();
	</script>
	<?php
}, 99 );
