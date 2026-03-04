document.addEventListener('DOMContentLoaded', function () {
  document.addEventListener('click', function (e) {
    var link = e.target.closest('a[href^="#"]');
    if (!link) return;
    var targetId = link.getAttribute('href').slice(1); 
    if (!targetId) return;
    var target = document.getElementById(targetId);
    if (!target) return;
    e.preventDefault(); 
    closeDrawer();
    setTimeout(function () {
      var headerHeight = document.querySelector('.header')
        ? document.querySelector('.header').offsetHeight
        : 0;
      var targetTop =
        target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
      window.scrollTo({ top: targetTop, behavior: 'smooth' });
    }, 10);
  });

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

  var yearEl = document.getElementById('year');
  if (yearEl) yearEl.textContent = new Date().getFullYear();

//  BACK TO TOP 
const backToTop = document.getElementById('backToTop');
const progressFill = document.querySelector('.progress-ring-fill');
const scrollPercent = document.querySelector('.scroll-percent');
const circumference = 160;

if (backToTop) {
  window.addEventListener('scroll', () => {
    const scrollTop = window.scrollY;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const progress = scrollTop / docHeight;
    const offset = circumference - (progress * circumference);

    progressFill.style.strokeDashoffset = offset;
    scrollPercent.textContent = Math.round(progress * 100) + '%';

    if (scrollTop > 400) {
      backToTop.classList.add('visible');
    } else {
      backToTop.classList.remove('visible');
    }
  });
}

});

  // window.addEventListener('load', () => {
  //   setTimeout(() => {
  //     const preloader = document.getElementById('preloader');
  //     preloader.classList.add('hidden');
  //   }, 2000);
  // });