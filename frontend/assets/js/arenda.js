// наблюдаем высоту превью и записываем в var
/*
(() => {
  const thumbs = document.querySelector('[data-observe-h]');
  if (!thumbs) return;

  const host = thumbs.closest('.services-slider') || document.documentElement;

  const set = (h) => host.style.setProperty('--thumbs-h', `${Math.round(h)}px`);

  set(thumbs.getBoundingClientRect().height);

  const ro = new ResizeObserver((entries) => {
    for (const e of entries) {
      const h = e.borderBoxSize?.[0]?.blockSize ?? e.contentRect.height;
      set(h);
    }
  });
  ro.observe(thumbs);
})();
*/

/*
(() => {
  const thumbsSlider = document.querySelector('.services-thumbs-slider');
  if (!thumbsSlider) {
    console.log('❌ thumbsSlider не найден');
    return;
  }

  const mainSlider = document.querySelector('.services-slider');
  if (!mainSlider) {
    console.log('❌ mainSlider не найден');
    return;
  }

  // Ждем инициализации Swiper
  const checkSwiper = setInterval(() => {
    if (mainSlider.swiper) {
      clearInterval(checkSwiper);
      init();
    }
  }, 100);

  setTimeout(() => clearInterval(checkSwiper), 5000);

  function init() {
    console.log('✅ Swiper инициализирован');

    const insertThumbsToSlide = (slide) => {
      const placeholder = slide.querySelector('.js-insert-thumbs-slider');
      console.log('🔍 placeholder:', placeholder);
      if (placeholder) {
        console.log('📝 Вставляю в placeholder');
        placeholder.appendChild(thumbsSlider);
        console.log('✅ Вставлено');
      } else {
        console.log('❌ placeholder не найден');
      }
    };

    const insertThumbsToOriginal = () => {
      if (!mainSlider.contains(thumbsSlider)) {
        mainSlider.appendChild(thumbsSlider);
        console.log('↩️ Возвращено в оригинальное место');
      }
    };

    const updateThumbsPosition = () => {
      if (window.innerWidth <= 1024) {
        console.log('📱 Мобильная версия');
        const activeSlide = document.querySelector('.services-slider .swiper-slide-active');
        console.log('📄 activeSlide:', activeSlide);
        if (activeSlide) {
          insertThumbsToSlide(activeSlide);
        }
      } else {
        console.log('🖥️ Десктоп версия');
        insertThumbsToOriginal();
      }
    };

    mainSlider.swiper.on('slideChange', updateThumbsPosition);

    let resizeTimer;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(updateThumbsPosition, 150);
    });

    updateThumbsPosition();
  }
})();
*/


