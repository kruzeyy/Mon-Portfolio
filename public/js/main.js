(function () {
  "use strict";

  var header = document.querySelector(".site-header");
  var nav = document.querySelector(".site-nav");
  var toggle = document.querySelector(".nav-toggle");

  function closeNav() {
    if (!nav || !toggle) return;
    nav.classList.remove("is-open");
    toggle.setAttribute("aria-expanded", "false");
    toggle.setAttribute("aria-label", "Ouvrir le menu");
  }

  function toggleNav() {
    if (!nav || !toggle) return;
    var open = nav.classList.toggle("is-open");
    toggle.setAttribute("aria-expanded", open ? "true" : "false");
    toggle.setAttribute(
      "aria-label",
      open ? "Fermer le menu" : "Ouvrir le menu"
    );
  }

  if (toggle && nav) {
    toggle.addEventListener("click", toggleNav);
  }

  function scrollToHash(hash, updateHistory) {
    if (!hash || hash === "#") return;
    var id = hash.replace(/^#/, "");
    var el = document.getElementById(id);
    if (!el) return;

    var headerHeight = header ? header.offsetHeight : 0;
    var top = el.getBoundingClientRect().top + window.scrollY - headerHeight - 8;

    window.scrollTo({
      top: Math.max(0, top),
      behavior: window.matchMedia("(prefers-reduced-motion: reduce)").matches
        ? "auto"
        : "smooth",
    });

    if (updateHistory !== false) {
      history.pushState(null, "", hash);
    }
  }

  document.querySelectorAll("a[data-scroll]").forEach(function (link) {
    link.addEventListener("click", function (e) {
      var href = link.getAttribute("href");
      if (!href || href.charAt(0) !== "#") return;
      e.preventDefault();
      scrollToHash(href);
      closeNav();
    });
  });

  window.addEventListener("hashchange", function () {
    scrollToHash(window.location.hash, false);
  });

  if (window.location.hash) {
    requestAnimationFrame(function () {
      scrollToHash(window.location.hash, false);
    });
  }
})();
