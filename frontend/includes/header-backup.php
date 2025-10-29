<?php
/**
 * Функция для отображения хедера
 * 
 * @param array $opts Параметры хедера
 *   [
 *     'classes' => ['header_bg', 'space-md'], // дополнительные классы для <header>
 *     'active' => 'about', // активный пункт меню (formats|services|about|contacts)
 *     'isNotFoundPage' => false, // true для страницы 404 (кнопка будет ссылкой)
 *     'media' => [ // медиа-контент (null если не нужен)
 *       'type' => 'video', // 'video' или 'image'
 *       'src' => 'assets/video/intro.mp4',
 *       'poster' => 'assets/img/video-bg.jpg', // только для видео
 *       'autoplay' => true,
 *       'muted' => true,
 *       'loop' => true,
 *       'playsinline' => true
 *     ],
 *     'content' => [ // контент внутри медиа-блока
 *       'title' => 'Заголовок',
 *       'subtitle' => 'Подзаголовок',
 *       'button' => [ // кнопка (опционально)
 *         'text' => 'Оставить заявку',
 *         'href' => 'catalog_old.html', // только для isNotFoundPage=true
 *         'data' => 'data-graph-path="consult"'
 *       ]
 *     ]
 *   ]
 */
function render_header(array $opts): void
{
  // Базовые классы
  $headerClasses = ['header'];
  if (isset($opts['classes']) && is_array($opts['classes'])) {
    $headerClasses = array_merge($headerClasses, $opts['classes']);
  }

  // Проверяем, нужна ли анимация для изображений или видео
  $isImage = isset($opts['media']['type']) && $opts['media']['type'] === 'image';
  $isVideo = isset($opts['media']['type']) && $opts['media']['type'] === 'video';
  if ($isImage) {
    $headerClasses[] = 'js-hero';
  }
  if ($isVideo) {
    $headerClasses[] = 'js-video-hero';
  }

  $headerClassString = htmlspecialchars(implode(' ', $headerClasses), ENT_QUOTES, 'UTF-8');

  // Определяем wrapper классы
  $wrapperClasses = ['header__wrapper'];
  if (!isset($opts['media']) || $opts['media'] === null) {
    $wrapperClasses[] = 'header__wrapper_inner';
  }
  $wrapperClassString = htmlspecialchars(implode(' ', $wrapperClasses), ENT_QUOTES, 'UTF-8');

  // Активный пункт меню
  $active = isset($opts['active']) ? $opts['active'] : '';

  // Меню
  $menuItems = [
    'formats' => ['href' => '#', 'text' => 'Программы отдыха', 'dropdown' => true],
    'services' => ['href' => 'services.php', 'text' => 'Услуги'],
    'about' => ['href' => 'about.php', 'text' => 'О нас'],
    'contacts' => ['href' => 'contacts.php', 'text' => 'Контакты']
  ];

  // Логотип
  $logoHref = ($active === 'index') ? '/' : 'index.php';

  // Подготовка атрибутов для изображений
  $bgSrc = $isImage ? ($opts['media']['src'] ?? '') : '';
  $heroAttrs = '';
  if ($isImage && $bgSrc) {
    $heroAttrs = ' data-hero-bg="' . htmlspecialchars($bgSrc, ENT_QUOTES, 'UTF-8') . '"';
  }

  echo '<header class="' . $headerClassString . '"' . $heroAttrs . '>' . "\n";
  echo '  <div class="' . $wrapperClassString . '" data-header="">' . "\n";
  echo '    <div class="container header__container">' . "\n";
  echo '      <a href="' . htmlspecialchars($logoHref, ENT_QUOTES, 'UTF-8') . '" class="header__logo-wrap">' . "\n";
  echo '        <img src="assets/img/logo-old.svg" alt="logo">' . "\n";
  echo '      </a>' . "\n";
  echo '' . "\n";
  echo '      <ul class="header__nav" data-da=".js-insert-nav,1719">' . "\n";

  foreach ($menuItems as $key => $item) {
    $isActive = ($key === $active);
    $text = htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8');
    $hasDropdown = isset($item['dropdown']) && $item['dropdown'];

    // Для активного пункта используем #, для остальных - реальные ссылки
    if ($isActive) {
      $href = '#';
    } else {
      $href = htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8');
    }

    $dataAttr = ($key === 'about' && $active !== 'about') ? ' data-scrolling-to="true"' : '';
    $dataAttr .= ($key === 'contacts' && $active !== 'contacts') ? ' data-scrolling-to="true"' : '';

    // Добавляем класс active для активного пункта меню
    $linkClass = 'color-white link-md-light link-decor';
    if ($isActive) {
      $linkClass .= ' active';
    }

    // Добавляем класс для выпадающего меню
    if ($hasDropdown) {
      $linkClass .= ' dropdown-toggle';
      $dataAttr .= ' data-dropdown="formats-dropdown"';
    }

    echo '        <li class="' . ($hasDropdown ? 'dropdown' : '') . '">' . "\n";
    echo '          <a href="' . $href . '" class="' . $linkClass . '"' . $dataAttr . '>' . $text . '</a>' . "\n";

    // Добавляем выпадающее меню для пункта "Программы отдыха"
    if ($hasDropdown && $key === 'formats') {
      echo '          <ul class="dropdown-menu" id="formats-dropdown">' . "\n";
      echo '            <li><a href="arenda.php" class="link-md-light">Аренда</a></li>' . "\n";
      echo '            <li><a href="avtorskie-tury.php" class="link-md-light">Авторские туры</a></li>' . "\n";
      echo '          </ul>' . "\n";
    }

    echo '        </li>' . "\n";
  }

  echo '      </ul>' . "\n";
  echo '' . "\n";
  echo '      <div class="header__contacts" data-da=".js-insert-contacts,1023">' . "\n";
  echo '        <a href="tel:+74951531498" class="text-md text-color-primary-inverse link-decor">+7(495) 153-14-98</a>' . "\n";
  echo '        <button class="color-white link-md-medium dashed btn-reset" data-graph-path="call">Перезвоните мне</button>' . "\n";
  echo '      </div>' . "\n";
  echo '' . "\n";
  echo '      <div class="side-menu" data-menu>' . "\n";
  echo '        <div class="side-menu__top space-xl">' . "\n";
  echo '        </div>' . "\n";
  echo '        <div class="space-xxl js-insert-nav"></div>' . "\n";
  echo '        <div class="side-menu__group">' . "\n";
  echo '          <div class="js-insert-contacts"></div>' . "\n";
  echo '        </div>' . "\n";
  echo '      </div>' . "\n";
  echo '    </div>' . "\n";
  echo '    <button class="btn-reset header__burger" data-menu-button="">' . "\n";
  echo '      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">' . "\n";
  echo '        <path' . "\n";
  echo '          d="M18 16C18.5522 16.0001 19 16.4478 19 17C19 17.5523 18.5522 18 18 18H6C5.44773 18 5.00002 17.5523 5 17C5 16.4478 5.44772 16 6 16H18ZM18 11C18.5522 11.0001 19 11.4478 19 12C19 12.5523 18.5522 13 18 13H6C5.44773 13 5.00002 12.5523 5 12C5 11.4478 5.44772 11 6 11H18ZM18 6.00005C18.5522 6.00009 19 6.44779 19 7.00005C19 7.55228 18.5522 8 18 8.00005H6C5.44773 8.00005 5.00002 7.55231 5 7.00005C5 6.44776 5.44772 6.00005 6 6.00005H18Z"' . "\n";
  echo '          fill="white" />' . "\n";
  echo '      </svg>' . "\n";
  echo '    </button>' . "\n";
  echo '  </div>' . "\n";

  // Медиа-контент
  if (isset($opts['media']) && $opts['media'] !== null) {
    $media = $opts['media'];
    $content = isset($opts['content']) ? $opts['content'] : [];

    echo '  <div class="header__video-wrap">' . "\n";

    if ($media['type'] === 'video') {
      $videoAttrs = '';
      if (isset($media['autoplay']) && $media['autoplay'])
        $videoAttrs .= ' autoplay=""';
      if (isset($media['muted']) && $media['muted'])
        $videoAttrs .= ' muted=""';
      if (isset($media['loop']) && $media['loop'])
        $videoAttrs .= ' loop=""';
      if (isset($media['playsinline']) && $media['playsinline'])
        $videoAttrs .= ' playsinline=""';
      if (isset($media['poster']))
        $videoAttrs .= ' poster="' . htmlspecialchars($media['poster'], ENT_QUOTES, 'UTF-8') . '"';

      echo '    <video' . $videoAttrs . ' class="header__video">' . "\n";
      echo '      <source src="' . htmlspecialchars($media['src'], ENT_QUOTES, 'UTF-8') . '" type="video/mp4">' . "\n";
      echo '    </video>' . "\n";
    } else {
      // Для изображений не выводим img, фон будет установлен через JS
      // echo '    <img src="' . htmlspecialchars($media['src'], ENT_QUOTES, 'UTF-8') . '" alt="" class="header__bg">' . "\n";
    }

    echo '' . "\n";
    echo '    <div class="container header__inner-container header__container">' . "\n";
    echo '      <div class="header__info">' . "\n";
    echo '        <div class="header__video-wrap-text">' . "\n";

    if (isset($content['title'])) {
      $titleClass = 'heading-wrap heading-wrap_header heading-lg color-white text-center';
      if ($isImage || $isVideo) {
        $titleClass .= ' js-hero-item';
      }
      echo '          <h1 class="' . $titleClass . '">' . "\n";
      echo '            ' . $content['title'] . "\n";
      echo '          </h1>' . "\n";
    }

    if (isset($content['subtitle'])) {
      $subtitleClass = 'subtitle-md color-white text-center';
      if ($isImage || $isVideo) {
        $subtitleClass .= ' js-hero-item';
      }
      echo '          <p class="' . $subtitleClass . '">' . htmlspecialchars($content['subtitle'], ENT_QUOTES, 'UTF-8') . '</p>' . "\n";
    }

    echo '        </div>' . "\n";

    if (isset($content['button'])) {
      $button = $content['button'];
      $buttonText = htmlspecialchars($button['text'], ENT_QUOTES, 'UTF-8');
      $buttonData = isset($button['data']) ? ' ' . $button['data'] : '';
      $isNotFoundPage = isset($opts['isNotFoundPage']) && $opts['isNotFoundPage'] === true;

      $buttonClass = 'button-filled color-white buttons-lg-medium';
      if ($isImage || $isVideo) {
        $buttonClass .= ' js-hero-item';
      }

      if ($isNotFoundPage) {
        // Для страницы 404 - ссылка на главную
        $buttonHref = isset($button['href']) ? htmlspecialchars($button['href'], ENT_QUOTES, 'UTF-8') : '/';
        echo '        <a href="' . $buttonHref . '" class="' . $buttonClass . '"' . $buttonData . ' aria-label="консультация">' . "\n";
        echo '          ' . $buttonText . "\n";
        echo '        </a>' . "\n";
      } else {
        // Для всех остальных страниц - кнопка с data-graph-path
        echo '        <button class="' . $buttonClass . '"' . $buttonData . ' aria-label="консультация">' . "\n";
        echo '          ' . $buttonText . "\n";
        echo '        </button>' . "\n";
      }
    }

    echo '      </div>' . "\n";
    echo '    </div>' . "\n";
    echo '' . "\n";
    echo '  </div>' . "\n";
  }

  echo '</header>' . "\n";
}
