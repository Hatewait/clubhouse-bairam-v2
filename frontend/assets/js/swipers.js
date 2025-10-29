'use strict'

// Глобальная функция для инициализации Swiper
window.initSwipers = function() {
  const slider = new Swiper('[data-single-slider]', {
    effect: 'fade',
    fadeEffect: { crossFade: true }, // плавная перекладка прозрачности
    speed: 600,
    grabCursor: true,
    watchSlidesProgress: true,
    preloadImages: false,
    lazy: true,
    keyboard: {enabled: true},
    a11y: {
      enabled: true,
    },
    navigation: {
      nextEl: '[data-single-next]',
      prevEl: '[data-single-prev]',
      lockClass: 'swiper-nav__lock'
    },

    spaceBetween: 0,
    breakpoints: {
      /*480: {spaceBetween: 8},
      768: {spaceBetween: 12}*/
    }
  });

  // Инициализируем превью
  const thumbs = new Swiper('[data-thumbs-slider]', {
    spaceBetween: 12,
    slidesPerView: 3,
    watchSlidesProgress: true,
    watchSlidesVisibility: true,

    a11y: { enabled: true },
    breakpoints: {

    }
  });

// Инициализируем основной
  const main = new Swiper('[data-double-slider]', {
    effect: 'fade',
    fadeEffect: { crossFade: true }, // плавная перекладка прозрачности
    speed: 600,
    spaceBetween: 24,
    loop: true,
    navigation: {
      nextEl: '[data-double-next]',
      prevEl: '[data-double-prev]',
      lockClass: 'swiper-nav__lock'
    },
    thumbs: { swiper: thumbs },
    preloadImages: false,
    lazy: { loadOnTransitionStart: true },
    a11y: { enabled: true }
  });

// Скрыть стрелки, если слайд один
  const navNext = document.querySelector('.swiper-button-next');
  const navPrev = document.querySelector('.swiper-button-prev');
  const toggleNav = () => {
    const isSingle = main.slides.length <= 1;
    [navNext, navPrev].forEach(btn => btn && (btn.style.display = isSingle ? 'none' : ''));
  };
  toggleNav();

  return { main, thumbs, slider };
};

document.addEventListener("DOMContentLoaded", () => {
  window.initSwipers();
})


