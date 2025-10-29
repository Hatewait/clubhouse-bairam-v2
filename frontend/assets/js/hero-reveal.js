/* Hero reveal animation for image headers */
(function () {
  'use strict';

  // Фича-флаг: быстро выключить эффект при необходимости
  const ENABLE_HERO_REVEAL = true;
  if (!ENABLE_HERO_REVEAL) return;

  // Уважение к системным настройкам
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReduced) return;

  const heroes = Array.from(document.querySelectorAll('.js-hero[data-hero-bg]'));
  if (!heroes.length) return;

  const io = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;

      const hero = entry.target;
      io.unobserve(hero);

      // Прелоад фоновой картинки
      const bg = hero.getAttribute('data-hero-bg');
      if (!bg) {
        hero.classList.add('is-ready');
        staggerItems(hero);
        return;
      }

      const img = new Image();
      img.onload = function () {
        // Ставим фон на header__video-wrap и показываем блок
        const videoWrap = hero.querySelector('.header__video-wrap');
        if (videoWrap) {
          videoWrap.style.backgroundImage = `url("${bg}")`;
        }
        hero.classList.add('is-ready');
        staggerItems(hero);
      };
      img.onerror = function () {
        // Если вдруг не загрузилось — показываем без фона, чтобы не зависать
        hero.classList.add('is-ready');
        staggerItems(hero);
      };
      img.src = bg;
    });
  }, { rootMargin: '0px 0px -10% 0px', threshold: 0.01 });

  heroes.forEach((h) => io.observe(h));

  function staggerItems(hero) {
    const items = Array.from(hero.querySelectorAll('.js-hero-item'));
    // ступенька задержки
    const STEP = 120; // мс
    items.forEach((el, idx) => {
      const delay = idx * STEP;
      // Даём малую задержку, чтобы фон успел «включиться» визуально
      setTimeout(() => el.classList.add('is-in'), 100 + delay);
    });
  }
})();
