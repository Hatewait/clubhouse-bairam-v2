/* Feature icons stagger animation */
(function () {
  'use strict';

  const containers = Array.from(document.querySelectorAll('[data-stagger="feature-icons"]'));
  if (!containers.length) return;

  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
  const activate = (el) => {
    // Проставляем индекс для CSS-задержки только один раз
    const items = Array.from(el.children).filter(ch => ch.classList.contains('feature-icons__item'));
    items.forEach((it, i) => it.style.setProperty('--stagger-i', i));
    el.classList.add('is-in');
  };

  if (reduce.matches) {
    containers.forEach(activate);
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

  containers.forEach((el) => io.observe(el));
})();

