'use strict'

console.log('Main Services Dynamic v3 - Swiper integration loaded');

document.addEventListener('DOMContentLoaded', async () => {
  console.log('main-services-dynamic.js загружен');

  const servicesSlider = document.querySelector('.services-slider .swiper-wrapper');
  const thumbsSlider = document.querySelector('.services-thumbs-slider .swiper-wrapper');

  console.log('servicesSlider:', servicesSlider);
  console.log('thumbsSlider:', thumbsSlider);

  if (!servicesSlider || !thumbsSlider) {
    console.error('Контейнеры слайдеров не найдены');
    return;
  }

  const API_BASE_URL = 'http://94.228.124.202:8080/api/frontend';
  let lastServicesUpdate = null;
  let mainSwiper = null;
  let thumbsSwiper = null;

  // Функция для поиска и уничтожения существующих Swiper экземпляров
  const destroyExistingSwipers = () => {
    console.log('Уничтожаем существующие Swiper экземпляры');

    // Ищем существующие экземпляры Swiper
    const mainSliderEl = document.querySelector('[data-double-slider]');
    const thumbsSliderEl = document.querySelector('[data-thumbs-slider]');

    if (mainSliderEl && mainSliderEl.swiper) {
      console.log('Уничтожаем основной слайдер');
      mainSliderEl.swiper.destroy(true, true);
      mainSwiper = null;
    }

    if (thumbsSliderEl && thumbsSliderEl.swiper) {
      console.log('Уничтожаем превью слайдер');
      thumbsSliderEl.swiper.destroy(true, true);
      thumbsSwiper = null;
    }
  };

  // Функция для инициализации Swiper с новыми слайдами
  const initializeSwipers = () => {
    console.log('Инициализируем новые Swiper экземпляры');

    // Ждем немного для завершения создания DOM элементов
    setTimeout(() => {
      try {
        // Инициализируем превью слайдер
        thumbsSwiper = new Swiper('[data-thumbs-slider]', {
          spaceBetween: 12,
          slidesPerView: 3,
          watchSlidesProgress: true,
          watchSlidesVisibility: true,
          a11y: { enabled: true },
        });
        console.log('Превью слайдер инициализирован');

        // Инициализируем основной слайдер
        mainSwiper = new Swiper('[data-double-slider]', {
          effect: 'fade',
          fadeEffect: { crossFade: true },
          speed: 600,
          spaceBetween: 24,
          autoHeight: true,
          loop: true,
          navigation: {
            nextEl: '[data-double-next]',
            prevEl: '[data-double-prev]',
            lockClass: 'swiper-nav__lock'
          },
          thumbs: { swiper: thumbsSwiper },
          preloadImages: false,
          lazy: { loadOnTransitionStart: true },
          a11y: { enabled: true }
        });
        console.log('Основной слайдер инициализирован');

        // Скрыть стрелки, если слайд один
        const navNext = document.querySelector('[data-double-next]');
        const navPrev = document.querySelector('[data-double-prev]');
        const toggleNav = () => {
          const isSingle = mainSwiper.slides.length <= 1;
          [navNext, navPrev].forEach(btn => btn && (btn.style.display = isSingle ? 'none' : ''));
        };
        toggleNav();
        console.log('Навигация настроена');

      } catch (error) {
        console.error('Ошибка инициализации Swiper:', error);
      }
    }, 100);
  };

  const fetchAndUpdateServices = async () => {
    try {
      console.log('Загружаем основные услуги...');
      const res = await fetch(`${API_BASE_URL}/services`, {
        headers: { 'Accept': 'application/json' },
      });

      console.log('Response status:', res.status);

      if (!res.ok) {
        throw new Error(`HTTP error! status: ${res.status}`);
      }

      const json = await res.json();
      console.log('API Response:', json);

      const services = json && json.success ? json.data : [];
      console.log('Parsed services:', services);

      // Уничтожаем существующие Swiper экземпляры
      destroyExistingSwipers();

      if (!services.length) {
        console.log('Нет услуг, очищаем слайды и скрываем слайдер');
        servicesSlider.innerHTML = '';
        thumbsSlider.innerHTML = '';

        // Скрываем весь слайдер
        const sliderContainer = document.querySelector('.services-slider');
        if (sliderContainer) {
          sliderContainer.style.display = 'none';
        }
        return;
      }

      console.log(`Создаем ${services.length} новых слайдов`);

      // Очищаем существующие слайды
      servicesSlider.innerHTML = '';
      thumbsSlider.innerHTML = '';

      // Создаем новые слайды на основе данных из админки
      services.forEach((service, index) => {
        console.log(`Создаем слайд ${index} для услуги:`, service);

        // Создаем основной слайд
        const slide = createServiceSlide(service);
        servicesSlider.appendChild(slide);

        // Создаем превью слайд
        const thumbSlide = createThumbSlide(service);
        thumbsSlider.appendChild(thumbSlide);
      });

      console.log(`Создано ${services.length} слайдов основных услуг`);

      // Показываем слайдер
      const sliderContainer = document.querySelector('.services-slider');
      if (sliderContainer) {
        sliderContainer.style.display = 'block';
      }

      // Инициализируем Swiper с новыми слайдами
      console.log('Инициализируем Swiper с новыми слайдами...');
      initializeSwipers();

    } catch (error) {
      console.error('Ошибка загрузки основных услуг:', error);
      // В случае ошибки очищаем слайды и скрываем слайдер
      servicesSlider.innerHTML = '';
      thumbsSlider.innerHTML = '';

      const sliderContainer = document.querySelector('.services-slider');
      if (sliderContainer) {
        sliderContainer.style.display = 'none';
      }

      destroyExistingSwipers();
    }
  };

  // Создание нового основного слайда
  const createServiceSlide = (service) => {
    console.log('createServiceSlide вызвана для:', service);

    let imageUrl = '';
    if (service.image_url) {
      if (!service.image_url.startsWith('http')) {
        imageUrl = 'http://94.228.124.202:8080' + service.image_url;
      } else {
        imageUrl = service.image_url;
      }
    }

    console.log('Создаем слайд с изображением:', imageUrl);

    const slide = document.createElement('div');
    slide.className = 'swiper-slide';
    slide.innerHTML = `
      <div class="services-slider__layout">
        <div class="services-slider__image-wrap">
          <img src="${imageUrl}" alt="${service.name || 'Основная услуга'}">
        </div>
        <div class="slide-right">
          <div class="slide-content services-slider__slide-content">
            <h3 class="subtitle-xl-accent">${service.name || 'Основная услуга'}</h3>
            <p class="text-md-regular text-color-secondary">${service.description || service.site_description || 'Описание услуги будет добавлено администратором.'}</p>
          </div>
        </div>
      </div>
    `;

    return slide;
  };

  // Создание нового превью слайда
  const createThumbSlide = (service) => {
    console.log('createThumbSlide вызвана для:', service);

    let imageUrl = '';
    if (service.image_url) {
      if (!service.image_url.startsWith('http')) {
        imageUrl = 'http://94.228.124.202:8080' + service.image_url;
      } else {
        imageUrl = service.image_url;
      }
    }

    console.log('Создаем превью слайд с изображением:', imageUrl);

    const thumbSlide = document.createElement('div');
    thumbSlide.className = 'swiper-slide';
    thumbSlide.innerHTML = `<img src="${imageUrl}" alt="${service.name || 'Основная услуга'}">`;

    return thumbSlide;
  };

  const checkForUpdates = async () => {
    try {
      const res = await fetch(`${API_BASE_URL}/check-updates`, {
        headers: { 'Accept': 'application/json' },
      });

      if (!res.ok) return;

      const json = await res.json();
      if (json && json.success && json.data.services_updated_at !== lastServicesUpdate) {
        console.log('Обнаружены изменения в основных услугах, перезагружаем...');
        lastServicesUpdate = json.data.services_updated_at;
        await fetchAndUpdateServices();
      }
    } catch (e) {
      console.warn('main-services-dynamic: update check failed', e);
    }
  };

  await fetchAndUpdateServices(); // Initial load
  setInterval(checkForUpdates, 5000); // Poll every 5 seconds
});
