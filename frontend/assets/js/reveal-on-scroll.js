/* Reveal on scroll functionality */
(() => {
  'use strict';

  // Проверка поддержки IntersectionObserver
  const supportsIO = 'IntersectionObserver' in window;
  
  // Проверка prefers-reduced-motion
  const prefersReduce = window.matchMedia &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Поиск кандидатов для анимации
  const candidates = Array.from(
    document.querySelectorAll('main > section, [data-reveal]')
  ).filter(el => 
    !el.closest('.swiper, .graph-modal') && 
    !el.hasAttribute('hidden')
  );

  // Автоматическое добавление data-reveal к секциям без атрибута
  for (const el of candidates) {
    if (!el.hasAttribute('data-reveal')) {
      el.setAttribute('data-reveal', 'fade-up');
    }
    
    const variant = el.getAttribute('data-reveal') || 'fade-up';
    const cleanVariant = variant.replace(/[^a-z-]/g, '');
    
    el.classList.add('reveal', `reveal--${cleanVariant}`);
  }

  // Если IntersectionObserver не поддерживается или включен prefers-reduced-motion
  if (!supportsIO || prefersReduce) {
    for (const el of candidates) {
      el.classList.add('reveal--visible');
    }
    return;
  }

  // Создание IntersectionObserver
  const io = new IntersectionObserver((entries) => {
    for (const entry of entries) {
      if (entry.isIntersecting) {
        const el = entry.target;
        const delay = parseInt(el.getAttribute('data-reveal-delay') || '0', 10);
        const once = (el.getAttribute('data-reveal-once') ?? 'true') !== 'false';

        const show = () => el.classList.add('reveal--visible');
        
        if (delay > 0) {
          setTimeout(show, delay);
        } else {
          show();
        }

        if (once) {
          io.unobserve(el);
        }
      }
    }
  }, { 
    root: null, 
    rootMargin: '0px 0px -10% 0px', 
    threshold: 0.15 
  });

  // Наблюдение за элементами
  candidates.forEach(el => io.observe(el));

  // Снятие will-change после окончания анимации
  document.addEventListener('transitionend', (e) => {
    const el = e.target.closest('.reveal--visible');
    if (el) {
      el.style.willChange = 'auto';
    }
  }, { passive: true });

})();

