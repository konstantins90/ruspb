/**
* Template Name: Logis
* Updated: Jan 29 2024 with Bootstrap v5.3.2
* Template URL: https://bootstrapmade.com/logis-bootstrap-logistics-website-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/
/**
 * Aktualisiert Sticky-Header und Scroll-Top-Button (einmaliger Aufruf).
 * Wird bei Load und nach Turbo-Navigation aufgerufen.
 */
function updateHeaderAndScrollTop() {
  const header = document.querySelector('#header');
  if (header) header.classList.toggle('sticked', window.scrollY > 100);
  const scrollTop = document.querySelector('.scroll-top');
  if (scrollTop) scrollTop.classList.toggle('active', window.scrollY > 100);
}

/**
 * Registriert einmal global den Scroll-Listener und Scroll-Top-Klick.
 * Nach Turbo-Navigation nur noch updateHeaderAndScrollTop() aufrufen.
 */
function initHeaderAndScrollTop() {
  document.addEventListener('scroll', updateHeaderAndScrollTop, { passive: true });
  document.addEventListener('click', (e) => {
    if (e.target.closest('.scroll-top')) {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  });
}

function runOnPageLoad() {
  updateHeaderAndScrollTop();
}

document.addEventListener('DOMContentLoaded', () => {
  "use strict";

  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  initHeaderAndScrollTop();
  runOnPageLoad();

  /**
   * Nach Turbo-Navigation (Link-Klick ohne Vollbild-Reload) Zustand setzen,
   * damit Sticky-Header und Scroll-Top auf der neuen Seite funktionieren.
   */
  document.addEventListener('turbo:load', runOnPageLoad);

  /**
   * Mobile nav toggle
   */
  const mobileNavShow = document.querySelector('.mobile-nav-show');
  const mobileNavHide = document.querySelector('.mobile-nav-hide');

  document.querySelectorAll('.mobile-nav-toggle').forEach(el => {
    el.addEventListener('click', function(event) {
      event.preventDefault();
      mobileNavToogle();
    })
  });

  function mobileNavToogle() {
    document.querySelector('body').classList.toggle('mobile-nav-active');
    mobileNavShow.classList.toggle('d-none');
    mobileNavHide.classList.toggle('d-none');
  }

  /**
   * Hide mobile nav on same-page/hash links
   */
  document.querySelectorAll('#navbar a').forEach(navbarlink => {

    if (!navbarlink.hash) return;

    let section = document.querySelector(navbarlink.hash);
    if (!section) return;

    navbarlink.addEventListener('click', () => {
      if (document.querySelector('.mobile-nav-active')) {
        mobileNavToogle();
      }
    });

  });

  /**
   * Toggle mobile nav dropdowns
   */
  const navDropdowns = document.querySelectorAll('.navbar .dropdown > a');

  navDropdowns.forEach(el => {
    el.addEventListener('click', function(event) {
      if (document.querySelector('.mobile-nav-active')) {
        event.preventDefault();
        this.classList.toggle('active');
        this.nextElementSibling.classList.toggle('dropdown-active');

        let dropDownIndicator = this.querySelector('.dropdown-indicator');
        dropDownIndicator.classList.toggle('bi-chevron-up');
        dropDownIndicator.classList.toggle('bi-chevron-down');
      }
    })
  });
});