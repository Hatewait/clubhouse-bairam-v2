/* Tours stagger animation */
(function () {
  'use strict';

  const section = document.getElementById('tours');
  if (!section) return;

  const cards = Array.from(section.querySelectorAll('.tours__wrap > .tour'));
  if (!cards.length) return;

  // проставляем индекс для CSS-задержки
  cards.forEach((el, i) => el.style.setProperty('--stagger-i', i));

  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
  if (reduce.matches) {
    section.classList.add('is-in');
    return;
  }

  const io = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          section.classList.add('is-in'); // один раз активируем
          obs.disconnect();
        }
      });
    },
    { root: null, rootMargin: '0px 0px -10% 0px', threshold: 0.15 }
  );

  io.observe(section);
})();

