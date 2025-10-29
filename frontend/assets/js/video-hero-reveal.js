/* Video hero reveal animation for video headers */
(function () {
  'use strict';

  // Фича-флаг: быстро выключить эффект при необходимости
  const ENABLE_VIDEO_HERO_REVEAL = true;
  if (!ENABLE_VIDEO_HERO_REVEAL) return;

  // Уважение к системным настройкам
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReduced) return;

  const videoHeroes = Array.from(document.querySelectorAll('.js-video-hero'));
  if (!videoHeroes.length) return;

  const io = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;

      const hero = entry.target;
      io.unobserve(hero);

      // Для видео-хедеров сразу показываем контент с анимацией
      hero.classList.add('is-ready');
      staggerItems(hero);
    });
  }, { rootMargin: '0px 0px -10% 0px', threshold: 0.01 });

  videoHeroes.forEach((h) => io.observe(h));

  function staggerItems(hero) {
    const items = Array.from(hero.querySelectorAll('.js-hero-item'));
    // ступенька задержки - увеличиваем с 120ms до 200ms
    const STEP = 200; // мс
    items.forEach((el, idx) => {
      const delay = idx * STEP;
      // Даём малую задержку для плавного появления
      setTimeout(() => el.classList.add('is-in'), 100 + delay);
    });
  }
})();
