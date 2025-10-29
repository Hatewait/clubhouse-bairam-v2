/* Mosaic stagger animation */
(function () {
  'use strict';

  const section = document.getElementById('mosaic');
  if (!section) return;

  const cards = Array.from(section.querySelectorAll('.mosaic-card'));
  if (!cards.length) return;

  // Проставляем CSS-переменную для ступенчатой задержки
  cards.forEach((el, i) => el.style.setProperty('--reveal-i', i));

  // Если пользователь просит меньше движухи — показываем сразу
  const media = window.matchMedia('(prefers-reduced-motion: reduce)');
  if (media.matches) {
    section.classList.add('is-in');
    return;
  }

  const io = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          section.classList.add('is-in');
          obs.disconnect(); // однократный запуск
        }
      });
    },
    {
      root: null,
      rootMargin: '0px 0px -10% 0px', // чуть раньше, чем полностью в кадре
      threshold: 0.15
    }
  );

  io.observe(section);
})();

