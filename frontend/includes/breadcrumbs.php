<?php
/**
 * Функция для отображения хлебных крошек
 * 
 * @param array $items Массив элементов крошек
 *   [
 *     ['title' => 'Главная', 'url' => '/index.php'],
 *     ['title' => 'О нас'] // последний элемент без ссылки — активная страница
 *   ]
 */
function render_breadcrumbs(array $items): void
{
  if (empty($items)) {
    return;
  }

  echo '<ul class="breadcrumbs">' . "\n";

  foreach ($items as $index => $item) {
    $title = htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8');
    $isLast = ($index === count($items) - 1);

    if ($isLast) {
      // Последний элемент (активная страница)
      echo '  <li class="breadcrumbs__item breadcrumbs__item_active">' . "\n";
      echo '    <a class="breadcrumbs__link caption-md">' . $title . '</a>' . "\n";
      echo '  </li>' . "\n";
    } else {
      // Обычные элементы с ссылками
      $url = isset($item['url']) ? htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') : '#';
      echo '  <li class="breadcrumbs__item">' . "\n";
      echo '    <a href="' . $url . '" class="breadcrumbs__link caption-md">' . $title . '</a>' . "\n";
      echo '  </li>' . "\n";
    }
  }

  echo '</ul>' . "\n";
}
