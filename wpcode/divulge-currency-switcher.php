/**
 * Divulge – currency switcher
 * Adds a currency dropdown (USD, EUR, GBP) to the left of the green top bar,
 * next to the language selector, and converts every USD price on the page.
 * Rates come from a free exchange-rate feed and refresh daily in each visitor's browser;
 * the visitor's choice is remembered. Bookings and quotes are unchanged (USD).
 * WPCode: PHP Snippet, Auto Insert, Run Everywhere.
 */

add_action( 'wp_footer', function () {
	if ( is_admin() ) {
		return;
	}
	?>
	<style>
	.dvg-cur{position:relative; display:inline-flex; align-items:center; margin-left:18px; font-weight:300;}
	.dvg-cur-btn{display:inline-flex; align-items:center; gap:8px; background:none; border:0; color:#ffffff; font-size:14px; font-weight:300; cursor:pointer; padding:4px 0;}
	.dvg-cur-sym{display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:50%; background:#F2B544; color:#043D0E; font-size:12px; font-weight:600;}
	.dvg-cur-list{display:none; position:absolute; top:100%; left:0; z-index:99999; background:#ffffff; border-radius:6px; box-shadow:0 6px 20px rgba(0,0,0,.18); padding:6px 0; margin:6px 0 0; min-width:150px; list-style:none;}
	.dvg-cur.open .dvg-cur-list{display:block;}
	.dvg-cur-list li{padding:8px 16px; color:#1b1b1b; font-size:14px; cursor:pointer; white-space:nowrap;}
	.dvg-cur-list li:hover{background:#f6efe7; color:#043D0E;}
	@media (max-width:1024px){ .dvg-cur{display:none;} }
	</style>
	<script>
	(function () {
		var CUR = {
			USD: { sym: '$',  name: 'US Dollar' },
			EUR: { sym: '€',  name: 'Euro' },
			GBP: { sym: '£',  name: 'British Pound' }
		};
		// Fallback rates (per 1 USD), used only if the live feed is unreachable.
		var RATES = { USD: 1, EUR: 0.92, GBP: 0.78 };
		var KEY = 'dvg_currency', RKEY = 'dvg_rates';
		function store(k, v) { try { if (v === undefined) return localStorage.getItem(k); localStorage.setItem(k, v); } catch (e) { return null; } }

		// USD price patterns: "USD 1,627", "US$1,627", "$1,627".
		var RX = /(USD\s?|US\$\s?|\$\s?)(\d{1,3}(?:,\d{3})+|\d+)(\.\d{1,2})?/g;

		function wrapPrices(root) {
			var w = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
				acceptNode: function (n) {
					var p = n.parentElement;
					if (!p || p.closest('script,style,textarea,input,select,.dvg-price,.dvg-cur')) return NodeFilter.FILTER_REJECT;
					RX.lastIndex = 0;
					return RX.test(n.nodeValue) ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_SKIP;
				}
			});
			var nodes = [];
			while (w.nextNode()) nodes.push(w.currentNode);
			nodes.forEach(function (n) {
				var html = n.nodeValue.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(RX, function (m, cur, num, dec) {
					var usd = parseFloat(num.replace(/,/g, '') + (dec || ''));
					return '<span class="dvg-price" data-usd="' + usd + '">' + m + '</span>';
				});
				var span = document.createElement('span');
				span.innerHTML = html;
				n.parentNode.replaceChild(span, n);
			});
		}

		function apply(code) {
			var c = CUR[code] || CUR.USD, rate = RATES[code] || 1;
			document.querySelectorAll('.dvg-price').forEach(function (el) {
				var v = Math.round(parseFloat(el.getAttribute('data-usd')) * rate);
				el.textContent = (code === 'USD' ? 'USD ' : c.sym) + v.toLocaleString('en-GB');
			});
			var b = document.querySelector('.dvg-cur-btn');
			if (b) b.innerHTML = '<span class="dvg-cur-sym">' + c.sym + '</span>' + c.name + ' <span style="font-size:10px;">&#9662;</span>';
		}

		function buildMenu() {
			if (document.querySelector('.dvg-cur')) return true;
			var host = document.querySelector('.divulge-topbar-lang');
			if (!host) return false;
			var box = document.createElement('div');
			box.className = 'dvg-cur';
			var list = Object.keys(CUR).map(function (k) { return '<li data-c="' + k + '">' + CUR[k].sym + ' &nbsp;' + CUR[k].name + '</li>'; }).join('');
			box.innerHTML = '<button type="button" class="dvg-cur-btn" aria-haspopup="true"></button><ul class="dvg-cur-list">' + list + '</ul>';
			host.appendChild(box);
			box.querySelector('.dvg-cur-btn').addEventListener('click', function (e) { e.stopPropagation(); box.classList.toggle('open'); });
			box.querySelectorAll('li').forEach(function (li) {
				li.addEventListener('click', function () { var c = li.getAttribute('data-c'); store(KEY, c); apply(c); box.classList.remove('open'); });
			});
			document.addEventListener('click', function () { box.classList.remove('open'); });
			return true;
		}

		function loadRates(done) {
			var cached = null;
			try { cached = JSON.parse(store(RKEY) || 'null'); } catch (e) {}
			if (cached && cached.t > Date.now() - 12 * 3600 * 1000) { RATES = cached.r; return done(); }
			fetch('https://open.er-api.com/v6/latest/USD').then(function (r) { return r.json(); }).then(function (j) {
				if (j && j.rates) {
					Object.keys(CUR).forEach(function (k) { if (j.rates[k]) RATES[k] = j.rates[k]; });
					store(RKEY, JSON.stringify({ t: Date.now(), r: RATES }));
				}
				done();
			}).catch(done);
		}

		function run() {
			var tries = 0;
			(function wait() {
				if (!buildMenu() && tries++ < 20) return setTimeout(wait, 150);
				wrapPrices(document.body);
				var code = store(KEY) || 'USD';
				apply(code);
				loadRates(function () { apply(store(KEY) || 'USD'); });
				// Prices loaded later (tabs, filters): convert them too.
				setTimeout(function () { wrapPrices(document.body); apply(store(KEY) || 'USD'); }, 2000);
			})();
		}
		if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run); else run();
	})();
	</script>
	<?php
}, 100 );
