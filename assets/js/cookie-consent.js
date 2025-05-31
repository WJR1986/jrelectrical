// cookie-consent.js

const Consent = {
  get: () => JSON.parse(localStorage.getItem("cookieConsent") || "{}"),
  set: (prefs) => localStorage.setItem("cookieConsent", JSON.stringify(prefs)),
};

function loadAnalytics() {
  (function (i, s, o, g, r, a, m) {
    i["GoogleAnalyticsObject"] = r;
    (i[r] =
      i[r] ||
      function () {
        (i[r].q = i[r].q || []).push(arguments);
      }),
      (i[r].l = 1 * new Date());
    a = s.createElement(o);
    m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m);
  })(
    window,
    document,
    "script",
    "https://www.google-analytics.com/analytics.js",
    "ga"
  );

  ga("create", "UA-XXXXXXX-X", "auto");
  ga("send", "pageview");
}

function hideBanner() {
  document.getElementById("cookie-banner")?.classList.add("d-none");
}

function showCookieBanner() {
  document.getElementById("cookie-banner")?.classList.remove("d-none");
}

window.addEventListener("load", () => {
  const prefs = Consent.get();
  if (!prefs.decision) {
    showCookieBanner();
  } else if (prefs.analytics) {
    loadAnalytics();
  }
});

document.getElementById("accept-all")?.addEventListener("click", () => {
  Consent.set({ decision: true, analytics: true });
  loadAnalytics();
  hideBanner();
});

document.getElementById("reject-all")?.addEventListener("click", () => {
  Consent.set({ decision: true, analytics: false });
  hideBanner();
});

document.getElementById("manage")?.addEventListener("click", () => {
  alert(
    "You can change your preferences at any time via our Cookie Policy page."
  );
});

document.getElementById("save-preferences")?.addEventListener("click", () => {
  const analytics = document.getElementById("toggle-analytics")?.checked;
  Consent.set({ decision: true, analytics });
  if (analytics) loadAnalytics();
  else location.reload();
  alert("Your preferences have been saved.");
});
