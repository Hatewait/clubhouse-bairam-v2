'use strict'

// Динамическая загрузка дополнительных услуг на странице services.php
// БЕЗ изменения HTML структуры - только подстановка контента
document.addEventListener('DOMContentLoaded', async () => {
  const servicesGrid = document.querySelector('.services-grid');
  if (!servicesGrid) {
    console.error('Контейнер services-grid не найден');
    return;
  }

  const API_BASE_URL = 'http://94.228.124.202:8080/api/frontend';
  let lastOptionsUpdate = null;

  const fetchAndUpdateOptions = async () => {
    try {
      console.log('Загружаем дополнительные услуги...');
      const res = await fetch(`${API_BASE_URL}/options`, {
        headers: { 'Accept': 'application/json' },
      });
      
      if (!res.ok) {
        throw new Error(`HTTP error! status: ${res.status}`);
      }
      
      const json = await res.json();
      const options = json && json.success ? json.data : [];

      console.log('Получены дополнительные услуги:', options);

      // Находим все карточки услуг (кроме первой статической)
      const serviceCards = servicesGrid.querySelectorAll('.tour.services-grid__item');
      
      if (!options.length) {
        // Если нет услуг из админки, скрываем все карточки кроме первой
        serviceCards.forEach(card => {
          card.style.display = 'none';
        });
        return;
      }

      // Показываем карточки и обновляем контент
      serviceCards.forEach((card, index) => {
        if (index < options.length) {
          // Показываем карточку
          card.style.display = 'block';
          
          // Обновляем контент
          updateServiceCard(card, options[index], index);
        } else {
          // Скрываем лишние карточки
          card.style.display = 'none';
        }
      });

      console.log(`Обновлено ${Math.min(options.length, serviceCards.length)} карточек услуг`);

    } catch (error) {
      console.error('Ошибка загрузки дополнительных услуг:', error);
      // В случае ошибки скрываем все карточки кроме первой
      const serviceCards = servicesGrid.querySelectorAll('.tour.services-grid__item');
      serviceCards.forEach(card => {
        card.style.display = 'none';
      });
    }
  };

  const updateServiceCard = (card, option, index) => {
    // Обновляем фоновое изображение
    const bgElement = card.querySelector('.tour__bg');
    if (bgElement && option.image_url) {
      let imageUrl = option.image_url;
      if (!imageUrl.startsWith('http') && !imageUrl.startsWith('/storage/')) {
        imageUrl = `http://94.228.124.202:8080/storage/${imageUrl}`;
      } else if (imageUrl.startsWith('/storage/')) {
        imageUrl = `http://94.228.124.202:8080${imageUrl}`;
      }
      
      // Добавляем фоновое изображение через style
      bgElement.style.backgroundImage = `url('${imageUrl}')`;
      bgElement.style.backgroundSize = 'cover';
      bgElement.style.backgroundPosition = 'center';
      bgElement.style.backgroundRepeat = 'no-repeat';
    }

    // Обновляем название услуги
    const titleElement = card.querySelector('.subtitle-lg-accent');
    if (titleElement) {
      titleElement.textContent = option.name || 'Дополнительная услуга';
    }

    // Обновляем описание услуги
    const descriptionElement = card.querySelector('.tour__text');
    if (descriptionElement) {
      descriptionElement.textContent = option.description || 'Описание услуги будет добавлено администратором.';
    }
  };

  const checkForUpdates = async () => {
    try {
      const res = await fetch(`${API_BASE_URL}/check-updates`, {
        headers: { 'Accept': 'application/json' },
      });
      
      if (!res.ok) return;
      
      const json = await res.json();
      if (json && json.success && json.data.options_updated_at !== lastOptionsUpdate) {
        console.log('Обнаружены изменения в дополнительных услугах, перезагружаем...');
        lastOptionsUpdate = json.data.options_updated_at;
        await fetchAndUpdateOptions();
      }
    } catch (error) {
      console.warn('Ошибка проверки обновлений дополнительных услуг:', error);
    }
  };

  // Первоначальная загрузка
  await fetchAndUpdateOptions();
  
  // Проверка обновлений каждые 5 секунд
  setInterval(checkForUpdates, 5000);
});

