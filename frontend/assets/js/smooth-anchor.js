/* Нативный плавный скролл к якорным ссылкам */
(function () {
  'use strict';

  // Проверяем поддержку браузером
  if (!document.querySelector || !window.requestAnimationFrame) return;

  // Функция плавного скролла
  function smoothScrollTo(target, duration = 800) {
    const targetElement = document.querySelector(target);
    if (!targetElement) return;

    const targetPosition = targetElement.offsetTop;
    const startPosition = window.pageYOffset;
    const distance = targetPosition - startPosition;
    let startTime = null;

    // Учитываем высоту хедера для корректного позиционирования
    const headerHeight = document.querySelector('.header')?.offsetHeight || 0;
    const finalPosition = targetPosition - headerHeight - 20; // 20px отступ

    function animation(currentTime) {
      if (startTime === null) startTime = currentTime;
      const timeElapsed = currentTime - startTime;
      const run = easeInOutQuad(timeElapsed, startPosition, finalPosition - startPosition, duration);
      window.scrollTo(0, run);
      if (timeElapsed < duration) requestAnimationFrame(animation);
    }

    // Функция easing для плавности
    function easeInOutQuad(t, b, c, d) {
      t /= d / 2;
      if (t < 1) return c / 2 * t * t + b;
      t--;
      return -c / 2 * (t * (t - 2) - 1) + b;
    }

    requestAnimationFrame(animation);
  }

  // Обработчик кликов по якорным ссылкам
  function handleAnchorClick(e) {
    const link = e.target.closest('a[href^="#"]');
    if (!link) return;

    const href = link.getAttribute('href');
    if (href === '#') return; // Игнорируем пустые якоря

    const target = href.substring(1); // Убираем #
    const targetElement = document.getElementById(target);
    
    if (targetElement) {
      e.preventDefault();
      smoothScrollTo('#' + target);
    }
  }

  // Добавляем обработчик событий
  document.addEventListener('click', handleAnchorClick);

  // Обработка якорных ссылок при загрузке страницы
  function handlePageLoad() {
    const hash = window.location.hash;
    if (hash) {
      // Небольшая задержка для полной загрузки страницы
      setTimeout(() => {
        smoothScrollTo(hash);
      }, 100);
    }
  }

  // Запускаем после загрузки DOM
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', handlePageLoad);
  } else {
    handlePageLoad();
  }

})();

