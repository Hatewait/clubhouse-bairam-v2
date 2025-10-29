<?php
/**
 * Пример использования новой системы header
 * Показывает, как легко изменить конфигурацию
 */

// Подключаем новую функцию header
require_once 'includes/header/header.php';

// Пример 1: Простой header без медиа
render_header([
  'active' => 'about',
  'classes' => ['header_bg']
]);

// Пример 2: Header с видео
render_header([
  'active' => 'index',
  'classes' => ['header_bg'],
  'media' => [
    'type' => 'video',
    'src' => 'assets/video/intro.mp4',
    'poster' => 'assets/img/video-bg.jpg',
    'autoplay' => true,
    'muted' => true,
    'loop' => true,
    'playsinline' => true
  ],
  'content' => [
    'title' => 'Добро пожаловать в Захарову Балку',
    'subtitle' => 'Конный отдых в живописном месте',
    'button' => [
      'text' => 'Забронировать',
      'data' => 'data-graph-path="booking"'
    ]
  ]
]);

// Пример 3: Header с изображением
render_header([
  'active' => 'services',
  'classes' => ['header_bg'],
  'media' => [
    'type' => 'image',
    'src' => 'assets/img/about/intro.jpg'
  ],
  'content' => [
    'title' => 'Наши услуги',
    'subtitle' => 'Полный спектр конных услуг'
  ]
]);
?>