/*
 * Divulge Lead Tracking
 * Records enquiries in Matomo (and the Meta Pixel) so you can see which pages and blog posts bring leads.
 *
 * WPCode: Code Snippets > Add Snippet > Add Your Custom Code > JavaScript Snippet > paste everything below.
 * Insert Method: Auto Insert, Location: Site Wide Footer > set to Active > Save.
 *
 * What it records (Matomo > Behaviour > Events):
 *   Category "Lead"         Action "WhatsApp click" | "Quote form submitted" | "Email click" | "Phone click"
 *                           Name = the page the visitor was on when they enquired
 *   Category "Lead source"  same Actions, Name = the page the visitor first landed on (e.g. a blog post from Google)
 * Post-sharing buttons ("share this post on WhatsApp") are NOT counted as enquiries.
 */
(function () {
  var PHONES = ["254706223888", "254716033761"];   // both Divulge WhatsApp Business numbers
  var lastLead = { action: "", at: 0 };

  function landingPage() {
    try {
      var l = sessionStorage.getItem("dvg_landing");
      if (!l) { l = location.pathname; sessionStorage.setItem("dvg_landing", l); }
      return l;
    } catch (e) { return location.pathname; }
  }
  var landing = landingPage();

  function lead(action, metaEvent) {
    var now = Date.now();
    if (lastLead.action === action && now - lastLead.at < 1500) return; // one click = one lead
    lastLead = { action: action, at: now };
    window._paq = window._paq || [];
    window._paq.push(["trackEvent", "Lead", action, location.pathname]);
    window._paq.push(["trackEvent", "Lead source", action, landing]);
    if (typeof window.fbq === "function") {
      try { window.fbq("track", metaEvent, { content_name: action, content_category: location.pathname }); } catch (e) {}
    }
  }

  function isEnquiryWhatsApp(href) {
    if (!href) return false;
    var h = href.toLowerCase();
    if (h.indexOf("wa.link/") !== -1) return true;                       // your wa.link short link
    var ours = PHONES.some(function (n) { return h.indexOf(n) !== -1; });
    if (h.indexOf("wa.me/") !== -1 && ours) return true;
    if ((h.indexOf("whatsapp.com/send") !== -1 || h.indexOf("whatsapp://send") !== -1) && h.indexOf("phone=") !== -1 && ours) return true;
    return false;                                                         // e.g. share-this-post links have no phone number
  }

  document.addEventListener("click", function (e) {
    var t = e.target;
    if (!t || !t.closest) return;
    var a = t.closest("a[href]");
    if (a) {
      var href = a.getAttribute("href") || "";
      if (isEnquiryWhatsApp(href)) return lead("WhatsApp click", "Contact");
      if (href.indexOf("mailto:") === 0 || href.indexOf("/cdn-cgi/l/email-protection") !== -1) return lead("Email click", "Contact");
      if (href.indexOf("tel:") === 0) return lead("Phone click", "Contact");
    }
    // Floating "Click to Chat" WhatsApp button (built without a normal link)
    if (t.closest(".ht-ctc, .ht_ctc_chat_style, .ht-ctc-chat, [class*='ht_ctc'], [class*='ht-ctc']")) {
      return lead("WhatsApp click", "Contact");
    }
  }, true);

  // Quote form: count it only when the form confirms it was sent
  var formSent = false;
  function formSuccess() {
    if (formSent) return;
    formSent = true;
    lead("Quote form submitted", "Lead");
  }
  document.addEventListener("wpcf7mailsent", formSuccess);                         // Contact Form 7
  if (window.jQuery) {
    window.jQuery(document).on("submit_success", formSuccess);                    // Elementor forms
    window.jQuery(document).on("forminator:form:submit:success", formSuccess);    // Forminator
  }
  // Your proposal form shows "Brief received" after a successful send
  if ("MutationObserver" in window) {
    var pending = false;
    var mo = new MutationObserver(function () {
      if (formSent) { mo.disconnect(); return; }
      if (pending) return;
      pending = true;
      setTimeout(function () {                       // check at most a few times a second
        pending = false;
        if (/brief received/i.test(document.body.innerText)) { formSuccess(); mo.disconnect(); }
      }, 300);
    });
    var start = function () {
      // only on pages that contain the trip-brief form, and only if the thank-you text is not already showing
      if (document.body && /trip brief/i.test(document.body.textContent) && !/brief received/i.test(document.body.innerText)) {
        mo.observe(document.body, { childList: true, subtree: true, characterData: true, attributes: true, attributeFilter: ["style", "class", "hidden"] });
      }
    };
    if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", start); else start();
  }
})();
