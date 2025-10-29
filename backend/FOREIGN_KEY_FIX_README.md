# Исправление ограничений внешних ключей

## Проблема

При попытке удаления записи из таблицы `services` возникала ошибка:
```
SQLSTATE[23000]: Integrity constraint violation: 19 FOREIGN KEY constraint failed
```

## Причина

Внешние ключи в связанных таблицах были настроены на `RESTRICT`, что запрещало удаление записей, на которые ссылаются другие таблицы.

## Таблицы с проблемными ограничениями

1. **bundle_service** - ссылается на `services.id` через `service_id`
2. **applications** - ссылается на `services.id` через `service_id` 
3. **orders** - ссылается на `services.id` через `service_id`

## Решение

Созданы две миграции для исправления ограничений:

### 1. Миграция `2025_09_08_105558_update_bundle_service_foreign_key_constraints.php`

Изменяет ограничения в таблице `bundle_service`:
- `bundle_id`: `RESTRICT` → `CASCADE`
- `service_id`: `RESTRICT` → `CASCADE`

**Логика:** При удалении бандла или услуги, связанные записи в `bundle_service` также удаляются.

### 2. Миграция `2025_09_08_105630_update_applications_orders_foreign_key_constraints.php`

Изменяет ограничения в таблицах `applications` и `orders`:
- `service_id`: `RESTRICT` → `SET NULL`

**Логика:** При удалении услуги, поле `service_id` в заявках и заказах устанавливается в `NULL` (так как поле nullable).

## Результат

После применения миграций:

- ✅ Удаление услуг работает корректно
- ✅ Связанные записи в `bundle_service` удаляются автоматически (CASCADE)
- ✅ Поля `service_id` в `applications` и `orders` устанавливаются в `NULL` (SET NULL)
- ✅ Нет нарушения целостности данных

## Тестирование

```php
// Создание тестовой услуги
$service = Service::create([
    'name' => 'Тестовая услуга',
    'site_description' => 'Описание',
    'is_active' => true
]);

// Удаление работает без ошибок
$service->delete();
```

## Совместимость

- ✅ Обратная совместимость сохранена
- ✅ Миграции можно откатить
- ✅ Данные не теряются
- ✅ Целостность данных поддерживается




