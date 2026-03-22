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

  /* Header léger au scroll */
  var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  if (header && !reduceMotion) {
    var onHeaderScroll = function () {
      header.classList.toggle("is-scrolled", window.scrollY > 28);
    };
    window.addEventListener("scroll", onHeaderScroll, { passive: true });
    onHeaderScroll();
  }

  /* Apparition au scroll (sections, grilles) */
  var revealEls = document.querySelectorAll(".reveal");
  var revealGroups = document.querySelectorAll(".reveal-group");

  if (reduceMotion) {
    revealEls.forEach(function (el) {
      el.classList.add("is-visible");
    });
    revealGroups.forEach(function (el) {
      el.classList.add("is-in-view");
    });
  } else if ("IntersectionObserver" in window) {
    var revealObserver = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          var t = entry.target;
          if (t.classList.contains("reveal-group")) {
            t.classList.add("is-in-view");
          } else {
            t.classList.add("is-visible");
          }
          revealObserver.unobserve(t);
        });
      },
      { rootMargin: "0px 0px -6% 0px", threshold: 0.06 }
    );

    revealEls.forEach(function (el) {
      revealObserver.observe(el);
    });
    revealGroups.forEach(function (el) {
      revealObserver.observe(el);
    });
  } else {
    revealEls.forEach(function (el) {
      el.classList.add("is-visible");
    });
    revealGroups.forEach(function (el) {
      el.classList.add("is-in-view");
    });
  }
})();
