'use strict'

// Логика мобильного меню
const menuButton = document.querySelector('[data-menu-button]');
const menu = document.querySelector('[data-menu]');
const body = document.body;

// SVG иконки
const hamburgerIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M18 16C18.5522 16.0001 19 16.4478 19 17C19 17.5523 18.5522 18 18 18H6C5.44773 18 5.00002 17.5523 5 17C5 16.4478 5.44772 16 6 16H18ZM18 11C18.5522 11.0001 19 11.4478 19 12C19 12.5523 18.5522 13 18 13H6C5.44773 13 5.00002 12.5523 5 12C5 11.4478 5.44772 11 6 11H18ZM18 6.00005C18.5522 6.00009 19 6.44779 19 7.00005C19 7.55228 18.5522 8 18 8.00005H6C5.44773 8.00005 5.00002 7.55231 5 7.00005C5 6.44776 5.44772 6.00005 6 6.00005H18Z" fill="white"></path>
</svg>`;

const closeIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M16.9492 5.63574C17.3397 5.24522 17.9737 5.24522 18.3643 5.63574C18.7545 6.02615 18.7543 6.6593 18.3643 7.0498L13.4141 11.999L18.3643 16.9492C18.7547 17.3397 18.7548 17.9737 18.3643 18.3643C17.9737 18.7548 17.3397 18.7548 16.9492 18.3643L11.999 13.4141L7.0498 18.3643C6.6593 18.7544 6.02616 18.7545 5.63574 18.3643C5.24522 17.9737 5.24522 17.3397 5.63574 16.9492L10.585 11.999L5.63574 7.0498C5.24545 6.65926 5.24529 6.02619 5.63574 5.63574C6.02619 5.24532 6.65927 5.24546 7.0498 5.63574L11.999 10.585L16.9492 5.63574Z" fill="white"/>
</svg>`;

// Функция для открытия меню
const openMenu = () => {
  menu.classList.add('opened');
  body.classList.add('opened');
  menuButton.innerHTML = closeIcon;
};

// Функция для закрытия меню
const closeMenu = () => {
  menu.classList.remove('opened');
  body.classList.remove('opened');
  menuButton.innerHTML = hamburgerIcon;
  // Сбрасываем состояние подменю
  resetSubmenu();
};

// Функция для переключения состояния меню
const toggleMenu = () => {
  if (menu.classList.contains('opened')) {
    closeMenu();
  } else {
    openMenu();
  }
};

// Функция для сброса состояния подменю
const resetSubmenu = () => {
  const title = menu.querySelector('.side-menu__title');
  const backBtn = menu.querySelector('[data-menu-back]');
  const mobileNav = menu.querySelector('.js-insert-nav');
  
  if (title) title.style.display = 'block';
  if (backBtn) backBtn.style.display = 'none';
  
  // Удаляем созданные ссылки если они есть
  const customLinks = mobileNav ? mobileNav.querySelectorAll('.mobile-submenu-link') : [];
  customLinks.forEach(link => link.remove());
  
  // Показываем все пункты меню
  const navItems = mobileNav ? mobileNav.querySelectorAll('li') : [];
  navItems.forEach(item => {
    item.style.display = 'block';
  });
};

// Функция для показа подменю
const showSubmenu = () => {
  const title = menu.querySelector('.side-menu__title');
  const backBtn = menu.querySelector('[data-menu-back]');
  const mobileNav = menu.querySelector('.js-insert-nav');
  
  if (title) title.style.display = 'none';
  if (backBtn) backBtn.style.display = 'flex';
  
  // Скрываем все основные пункты меню
  const navItems = mobileNav ? mobileNav.querySelectorAll('li') : [];
  navItems.forEach(item => {
    item.style.display = 'none';
  });
  
  // Создаем новые независимые ссылки
  if (mobileNav) {
    const arendaLink = document.createElement('li');
    arendaLink.innerHTML = '<a href="arenda.php" class="color-white link-md-light">Аренда</a>';
    arendaLink.classList.add('mobile-submenu-link', 'space-xxs');
    
    const toursLink = document.createElement('li');
    toursLink.innerHTML = '<a href="avtorskie-tury.php" class="color-white link-md-light">Авторские туры</a>';
    toursLink.classList.add('mobile-submenu-link');
    
    // Добавляем ссылки в мобильное меню
    mobileNav.appendChild(arendaLink);
    mobileNav.appendChild(toursLink);
  }
};

// Обработчик клика по кнопке бургера
if (menuButton) {
  menuButton.addEventListener('click', toggleMenu);
}

// Обработчик клика по кнопке "Назад"
const backBtn = document.querySelector('[data-menu-back]');
if (backBtn) {
  backBtn.addEventListener('click', resetSubmenu);
}

// Обработчик клика по пункту "Программы отдыха" на мобильных устройствах
document.addEventListener('click', (e) => {
  // Проверяем, что это клик по пункту с dropdown на мобильном устройстве
  if (e.target.closest('.dropdown-toggle') && window.innerWidth < 1720) {
    e.preventDefault();
    showSubmenu();
  }
});

// Закрытие меню при клике вне его области
if (menu) {
  menu.addEventListener('click', (e) => {
    if (e.target === menu) {
      closeMenu();
    }
  });
}

// Закрытие меню при нажатии Escape
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && menu.classList.contains('opened')) {
    closeMenu();
  }
});
