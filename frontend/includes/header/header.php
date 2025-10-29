<?php
/**
 * Упрощенная функция для отображения хедера
 * Использует систему компонентов для легкого редактирования
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
  // Загружаем конфигурацию
  $config = include __DIR__ . '/config/menu-config.php';

  // Подготавливаем данные для компонентов
  $active = $opts['active'] ?? '';
  $menu_items = $config['menu_items'];

  // Определяем классы для header
  $header_classes = ['header'];
  if (isset($opts['classes']) && is_array($opts['classes'])) {
    $header_classes = array_merge($header_classes, $opts['classes']);
  }

  // Проверяем тип медиа для добавления соответствующих классов
  $is_image = isset($opts['media']['type']) && $opts['media']['type'] === 'image';
  $is_video = isset($opts['media']['type']) && $opts['media']['type'] === 'video';

  if ($is_image) {
    $header_classes[] = 'js-hero';
  }
  if ($is_video) {
    $header_classes[] = 'js-video-hero';
  }

  // Определяем wrapper классы
  $wrapper_classes = ['header__wrapper'];
  if (!isset($opts['media']) || $opts['media'] === null) {
    $wrapper_classes[] = 'header__wrapper_inner';
  }

  // Подготавливаем атрибуты для изображений
  $hero_attrs = '';
  if ($is_image && isset($opts['media']['src'])) {
    $hero_attrs = ' data-hero-bg="' . htmlspecialchars($opts['media']['src'], ENT_QUOTES, 'UTF-8') . '"';
  }

  // Передаем данные в компоненты
  $media = $opts['media'] ?? null;
  $content = $opts['content'] ?? [];
  $is_not_found_page = $opts['isNotFoundPage'] ?? false;
  ?>

  <header class="<?= implode(' ', $header_classes) ?>" <?= $hero_attrs ?>>
    <div class="<?= implode(' ', $wrapper_classes) ?>" data-header>
      <div class="container header__container">

        <?php include __DIR__ . '/components/logo.php'; ?>

        <?php include __DIR__ . '/components/navigation.php'; ?>

        <?php include __DIR__ . '/components/contacts.php'; ?>

        <?php include __DIR__ . '/components/side-menu.php'; ?>

      </div>

      <?php include __DIR__ . '/components/burger.php'; ?>
    </div>

    <?php if ($media): ?>
      <?php include __DIR__ . '/components/hero.php'; ?>
    <?php endif; ?>

  </header>

  <?php
}
