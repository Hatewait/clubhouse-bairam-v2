# Система компонентов Header

## Структура файлов

```
includes/header/
├── header.php                    # Основная функция render_header()
├── config/
│   └── menu-config.php          # Конфигурация меню и контактов
├── components/
│   ├── logo.php                 # Логотип
│   ├── navigation.php           # Навигационное меню
│   ├── contacts.php             # Контакты в хедере
│   ├── side-menu.php            # Мобильное меню
│   ├── burger.php               # Кнопка бургера
│   └── hero.php                 # Герой-секция (видео/изображение)
└── example-usage.php            # Примеры использования
```

## Как редактировать

### 1. Изменение пунктов меню

Откройте `config/menu-config.php` и измените массив `menu_items`:

```php
'menu_items' => [
    'formats' => [
        'href' => '#',
        'text' => 'Программы отдыха',  // ← Измените текст
        'dropdown' => true,
        'dropdown_items' => [
            ['href' => 'arenda.php', 'text' => 'Аренда'],
            ['href' => 'avtorskie-tury.php', 'text' => 'Авторские туры']
        ]
    ],
    // Добавьте новый пункт:
    'new-page' => [
        'href' => 'new-page.php',
        'text' => 'Новая страница',
        'dropdown' => false
    ]
]
```

### 2. Изменение контактов

В том же файле измените массив `contacts`:

```php
'contacts' => [
    'phone' => '+7(495) 123-45-67',        // ← Новый телефон
    'phone_href' => 'tel:+74951234567',     // ← Новый href
    'callback_text' => 'Заказать звонок',   // ← Новый текст
    'callback_href' => 'mailto:info@site.ru' // ← Новый email
]
```

### 3. Изменение логотипа

```php
'logo' => [
    'src' => 'assets/img/new-logo.svg',  // ← Новый путь к логотипу
    'alt' => 'Новый логотип'             // ← Новый alt
]
```

### 4. Изменение HTML разметки

Откройте нужный компонент в папке `components/` и отредактируйте HTML:

**Пример изменения навигации** (`components/navigation.php`):

```php
<ul class="header__nav" data-da=".js-insert-nav,1719">
    <?php foreach ($menu_items as $key => $item): ?>
        <!-- Добавьте свои классы или атрибуты -->
        <li class="<?= $has_dropdown ? 'dropdown' : '' ?> custom-class">
            <a href="<?= htmlspecialchars($href) ?>"
               class="<?= $link_class ?> new-class"  <!-- ← Добавьте класс -->
               <?= $data_attrs ?>>
                <?= htmlspecialchars($item['text']) ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
```

## Использование в страницах

### Старый способ (сложный):

```php
// Много сложного кода с echo и условиями
```

### Новый способ (простой):

```php
<?php
require_once 'includes/header/header.php';

// Простой header
render_header([
    'active' => 'about'
]);

// Header с видео
render_header([
    'active' => 'index',
    'media' => [
        'type' => 'video',
        'src' => 'assets/video/intro.mp4',
        'autoplay' => true
    ],
    'content' => [
        'title' => 'Заголовок',
        'subtitle' => 'Подзаголовок'
    ]
]);
?>
```

## Преимущества новой системы

1. **Легкое редактирование** - HTML в отдельных файлах
2. **Простая конфигурация** - все настройки в одном файле
3. **Модульность** - каждый компонент независим
4. **Переиспользование** - компоненты можно использовать отдельно
5. **Читаемость** - минимум PHP кода в HTML
6. **Безопасность** - автоматическое экранирование

## Миграция со старой системы

1. Замените вызовы старой функции на новую
2. Перенесите настройки в `config/menu-config.php`
3. При необходимости отредактируйте компоненты

## Добавление новых компонентов

1. Создайте файл в `components/new-component.php`
2. Добавьте `include` в `header.php`
3. Передайте нужные данные через переменные
