/**
 * Divulge – top bar reviews
 * Green top bar: hides the social icons, phone and email, and shows the review
 * cards (same style as the trip pages) next to the menu.
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
		$cards .= '<a href="' . esc_url( $r[3] ) . '" target="_blank" rel="noopener" style="display:flex; align-items:center; gap:7px; color:#ffffff; text-decoration:none; white-space:nowrap;">'
			. ( $r[4] ? '<img src="' . esc_url( $r[4] ) . '" alt="' . esc_attr( $r[0] ) . '" style="width:26px; height:26px; border-radius:50%; background:#ffffff; object-fit:contain; flex:none; margin:0;" />' : '' )
			. '<span style="display:block; text-align:left; line-height:1.15;">'
			. '<span style="display:block;"><span style="font-size:15px; font-weight:300; color:#ffffff;">' . esc_html( $r[1] ) . '/5</span> ' . divulge_rv_stars( $r[1], '15px' ) . '</span>'
			. '<span class="divulge-tb-based" style="display:block; font-size:12px; font-weight:300; font-style:italic; color:#ffffff;">Based on <span style="text-decoration:underline;">' . esc_html( $r[2] ) . '+ reviews</span></span>'
			. '</span></a>';
	}
	$strip = '<div class="divulge-topbar-reviews" style="flex:1 1 auto; display:flex; justify-content:center; align-items:center; gap:24px; padding:4px 12px; min-width:0;">' . $cards . '</div>';
	?>
	<style>
	@media (max-width:1300px){ .divulge-topbar-reviews{gap:14px !important;} }
	@media (max-width:1024px){ .divulge-topbar-reviews{display:none !important;} }
	</style>
	<script>
	(function () {
		var strip = <?php echo wp_json_encode( $strip ); ?>;
		function run() {
			var head = document.querySelector('header') || document.body;
			if (head.querySelector('.divulge-topbar-reviews')) return;

			// 1. Phone number -> WhatsApp chat with a ready message.
			// Top bar row = smallest block holding both the email link and the B2B menu link.
			var b2b = head.querySelector('a[href*="b2b-agent-rates"]');
			if (!b2b) return;
			var row = b2b.parentElement;
			while (row && row !== head && !row.querySelector('a[href^="mailto:"], a[href^="tel:"]')) row = row.parentElement;
			if (!row || row === head) return;
			var menu = b2b;
			while (menu.parentElement && menu.parentElement !== row) menu = menu.parentElement;

			// Hide the left side (social icons, phone, email) so only reviews and menu remain.
			Array.prototype.forEach.call(row.children, function (c) {
				if (c !== menu && !c.contains(menu)) c.style.display = 'none';
			});

			// Reviews go before the menu, centred in the free space.
			menu.insertAdjacentHTML('beforebegin', strip);
		}
		if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run); else run();
	})();
	</script>
	<?php
}, 99 );
