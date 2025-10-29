<?php
/**
 * Конфигурация меню
 * Здесь можно легко изменить пункты меню, ссылки, классы
 */
return [
  'menu_items' => [
    'formats' => [
      'href' => '#',
      'text' => 'Программы отдыха',
      'dropdown' => true,
      'dropdown_items' => [
        ['href' => 'arenda.php', 'text' => 'Аренда'],
        ['href' => 'avtorskie-tury.php', 'text' => 'Авторские туры']
      ]
    ],
    'services' => [
      'href' => 'services.php',
      'text' => 'Услуги',
      'dropdown' => false
    ],
    'about' => [
      'href' => 'about.php',
      'text' => 'О нас',
      'dropdown' => false
    ],
    'contacts' => [
      'href' => 'contacts.php',
      'text' => 'Контакты',
      'dropdown' => false
    ]
  ],

  'contacts' => [
    'phone' => '+7(495) 153-14-98',
    'phone_href' => 'tel:+74951531498',
    'callback_text' => 'Перезвоните мне',
    'callback_href' => 'mailto:info@t-d.ru'
  ],

  'logo' => [
    'src' => 'assets/img/logo-old.svg',
    'alt' => 'logo'
  ]
];
