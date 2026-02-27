document.addEventListener('DOMContentLoaded', function () {

  // =====================================================
  // SMOOTH SCROLL — no #hash in the URL
  // Handles both header menu links AND drawer links
  // =====================================================
  document.addEventListener('click', function (e) {
    var link = e.target.closest('a[href^="#"]');
    if (!link) return;

    var targetId = link.getAttribute('href').slice(1); // strip the #
    if (!targetId) return;

    var target = document.getElementById(targetId);
    if (!target) return;

    e.preventDefault(); // ← this stops the browser adding #id to the URL

    // Close drawer first (safe to call even when drawer is closed)
    closeDrawer();

    // Wait a tick so body overflow:hidden is removed before we scroll
    setTimeout(function () {
      var headerHeight = document.querySelector('.header')
        ? document.querySelector('.header').offsetHeight
        : 0;
      var targetTop =
        target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
      window.scrollTo({ top: targetTop, behavior: 'smooth' });
    }, 10);
  });

  // =====================================================
  // DRAWER LOGIC
  // =====================================================
  function openDrawer() {
    document.getElementById('drawer').classList.add('open');
    document.getElementById('drawerOverlay').classList.add('open');
    document.getElementById('hamburgerBtn').classList.add('open');
    document.getElementById('hamburgerBtn').setAttribute('aria-expanded', 'true');
    document.body.classList.add('drawer-open');
  }

  function closeDrawer() {
    document.getElementById('drawer').classList.remove('open');
    document.getElementById('drawerOverlay').classList.remove('open');
    document.getElementById('hamburgerBtn').classList.remove('open');
    document.getElementById('hamburgerBtn').setAttribute('aria-expanded', 'false');
    document.body.classList.remove('drawer-open');
  }

  function toggleDrawer() {
    document.getElementById('drawer').classList.contains('open')
      ? closeDrawer()
      : openDrawer();
  }

  document.getElementById('hamburgerBtn').addEventListener('click', toggleDrawer);
  document.getElementById('drawerClose').addEventListener('click', closeDrawer);
  document.getElementById('drawerOverlay').addEventListener('click', closeDrawer);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeDrawer();
  });

  // Year in footer
  var yearEl = document.getElementById('year');
  if (yearEl) yearEl.textContent = new Date().getFullYear();

});