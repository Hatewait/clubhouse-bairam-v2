<?php
/**
 * Компонент навигации
 * Чистый HTML с простой логикой
 */
?>

<ul class="header__nav" data-da=".js-insert-nav,1719">
  <?php foreach ($menu_items as $key => $item): ?>
    <?php
    $is_active = ($key === $active);
    $has_dropdown = $item['dropdown'] ?? false;

    // Определяем ссылку
    $href = $is_active ? '#' : $item['href'];

    // Определяем классы
    $link_class = 'color-white link-md-light link-decor';
    if ($is_active) {
      $link_class .= ' active';
    }
    if ($has_dropdown) {
      $link_class .= ' dropdown-toggle';
    }

    // Определяем data-атрибуты
    $data_attrs = '';
    if ($key === 'about' && $active !== 'about') {
      $data_attrs .= ' data-scrolling-to="true"';
    }
    if ($key === 'contacts' && $active !== 'contacts') {
      $data_attrs .= ' data-scrolling-to="true"';
    }
    if ($has_dropdown) {
      $data_attrs .= ' data-dropdown="formats-dropdown"';
    }
    ?>

    <li class="<?= $has_dropdown ? 'dropdown' : '' ?>">
      <a href="<?= htmlspecialchars($href) ?>" class="<?= $link_class ?>" <?= $data_attrs ?>>
        <?= htmlspecialchars($item['text']) ?>
        <?php if ($has_dropdown): ?>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"
            class="dropdown-arrow">
            <path
              d="M5.43457 3.43481C5.74699 3.1224 6.25301 3.1224 6.56543 3.43481L10.5654 7.43481L11.1318 8.00024L10.5654 8.56567L6.56543 12.5657C6.25305 12.878 5.74699 12.8779 5.43457 12.5657C5.12217 12.2533 5.1222 11.7472 5.43457 11.4348L8.86914 8.00024L5.43457 4.56567C5.12217 4.25327 5.1222 3.74724 5.43457 3.43481Z"
              fill="white" />
          </svg>
        <?php endif; ?>
      </a>

      <?php if ($has_dropdown && isset($item['dropdown_items'])): ?>
        <ul class="dropdown-menu" id="formats-dropdown">
          <?php foreach ($item['dropdown_items'] as $dropdown_item): ?>
            <li>
              <a href="<?= htmlspecialchars($dropdown_item['href']) ?>" class="link-md-light">
                <?= htmlspecialchars($dropdown_item['text']) ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </li>
  <?php endforeach; ?>
</ul>