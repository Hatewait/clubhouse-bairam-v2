/* Services stagger animation */
(function () {
  'use strict';

  const container = document.querySelector('[data-stagger="services"]');
  if (!container) return;

  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');

  const activate = (root) => {
    const items = Array.from(root.children)
      .filter((el) => el.classList.contains('services-grid__item'));
    items.forEach((el, i) => el.style.setProperty('--stagger-i', i));
    root.classList.add('is-in');
  };

  if (reduce.matches) {
    activate(container);
    return;
  }

  const io = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          activate(entry.target);
          io.unobserve(entry.target);
        }
      });
    },
    { root: null, rootMargin: '0px 0px -10% 0px', threshold: 0.15 }
  );

  io.observe(container);
})();

