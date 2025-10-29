/* Выпадающее меню в хедере */
(function () {
  'use strict';

  // Инициализация после загрузки DOM
  function initDropdownMenu() {
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    if (!dropdownToggles.length) return;

    // Обработчик клика по кнопке выпадающего меню
    function handleDropdownClick(e) {
      // На мобильных устройствах (< 1720px) не обрабатываем dropdown
      if (window.innerWidth < 1720) {
        return;
      }
      
      e.preventDefault();
      e.stopPropagation();
      
      const toggle = e.currentTarget;
      const dropdown = toggle.closest('.dropdown');
      const isOpen = dropdown.classList.contains('open');
      
      // Закрываем все открытые выпадающие меню
      closeAllDropdowns();
      
      // Открываем текущее меню, если оно было закрыто
      if (!isOpen) {
        dropdown.classList.add('open');
        
        // Добавляем обработчик клика вне меню для закрытия
        setTimeout(() => {
          document.addEventListener('click', handleOutsideClick);
        }, 0);
      }
    }

    // Обработчик клика вне выпадающего меню
    function handleOutsideClick(e) {
      const dropdown = document.querySelector('.dropdown.open');
      if (dropdown && !dropdown.contains(e.target)) {
        closeAllDropdowns();
        document.removeEventListener('click', handleOutsideClick);
      }
    }

    // Функция закрытия всех выпадающих меню
    function closeAllDropdowns() {
      const openDropdowns = document.querySelectorAll('.dropdown.open');
      openDropdowns.forEach(dropdown => {
        dropdown.classList.remove('open');
      });
      document.removeEventListener('click', handleOutsideClick);
    }

    // Добавляем обработчики событий
    dropdownToggles.forEach(toggle => {
      toggle.addEventListener('click', handleDropdownClick);
    });

    // Закрытие меню при нажатии Escape
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeAllDropdowns();
      }
    });

    // Закрытие меню при изменении размера окна (для мобильных)
    window.addEventListener('resize', function() {
      closeAllDropdowns();
    });
  }

  // Запускаем инициализацию
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDropdownMenu);
  } else {
    initDropdownMenu();
  }

})();

