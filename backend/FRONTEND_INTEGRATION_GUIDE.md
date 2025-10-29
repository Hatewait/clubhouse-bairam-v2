# 🚀 Руководство по интеграции фронтенда с API

## 📋 Обзор

Этот документ содержит всю необходимую информацию для фронтендера по интеграции с API системы управления заявками.

## 🔗 API Endpoints

### 1. **Получение данных каталога**

#### Форматы отдыха
```
GET /api/frontend/bundles
```

**Ответ:**
```json
{
  "success": true,
  "count": 3,
  "data": [
    {
      "id": 1,
      "title": "Базовый пакет",
      "description": "Включает проживание и завтрак",
      "nights": 2,
      "price": 5000,
      "price_formatted": "5 000 ₽",
      "image_url": "/storage/bundles/basic.jpg",
      "services": [
        {
          "id": 1,
          "title": "Проживание",
          "description": "Комфортный номер",
          "price": 0,
          "price_formatted": "Включено"
        }
      ]
    }
  ]
}
```

#### Услуги
```
GET /api/frontend/services
```

**Ответ:**
```json
{
  "success": true,
  "count": 5,
  "data": [
    {
      "id": 1,
      "title": "Проживание",
      "description": "Комфортный номер с удобствами",
      "price": 0,
      "price_formatted": "Включено",
      "image_url": "/storage/services/accommodation.jpg"
    }
  ]
}
```

#### Опции
```
GET /api/frontend/options
```

**Ответ:**
```json
{
  "success": true,
  "count": 8,
  "data": [
    {
      "id": 1,
      "name": "Дополнительная кровать",
      "description": "Дополнительная кровать в номере",
      "price": 1000,
      "price_formatted": "1 000 ₽",
      "price_per_person": false,
      "price_per_day": false,
      "image_url": "/storage/options/extra_bed.jpg"
    }
  ]
}
```

### 2. **Проверка обновлений**

```
GET /api/frontend/check-updates
```

**Ответ:**
```json
{
  "success": true,
  "data": {
    "bundles_updated_at": "2024-01-15 10:30:00",
    "services_updated_at": "2024-01-15 10:25:00",
    "options_updated_at": "2024-01-15 10:20:00"
  }
}
```

### 3. **Отправка заявки**

```
POST /api/intake
Content-Type: application/json
```

**Тело запроса:**
```json
{
  "bundle_id": 1,
  "date_from": "2024-02-15",
  "date_to": "2024-02-17",
  "people_count": 2,
  "client_name": "Иван Петров",
  "client_phone": "+7 (999) 123-45-67",
  "client_email": "ivan@example.com",
  "client_wishes": "Хотелось бы номер с видом на море",
  "addons": [1, 3, 5]
}
```

**Ответ:**
```json
{
  "success": true,
  "message": "Заявка успешно создана",
  "application_id": 123
}
```

## 🎨 Структура данных

### Формат отдыха (Bundle)
```typescript
interface Bundle {
  id: number;
  title: string;                    // Название для сайта
  description: string;              // Описание для сайта
  nights: number;                   // Количество ночей
  price: number;                    // Цена в копейках
  price_formatted: string;          // Отформатированная цена
  image_url: string | null;         // URL изображения
  services: Service[];              // Включенные услуги
}
```

### Услуга (Service)
```typescript
interface Service {
  id: number;
  title: string;                    // Название для сайта
  description: string;              // Описание для сайта
  price: number;                    // Цена в копейках
  price_formatted: string;          // Отформатированная цена
  image_url: string | null;         // URL изображения
}
```

### Опция (Option)
```typescript
interface Option {
  id: number;
  name: string;                     // Название
  description: string;              // Описание
  price: number;                    // Цена в копейках
  price_formatted: string;          // Отформатированная цена
  price_per_person: boolean;        // Умножать на количество человек
  price_per_day: boolean;           // Умножать на количество дней
  image_url: string | null;         // URL изображения
}
```

## 🔄 Автоматическое обновление

### JavaScript класс для работы с API

```javascript
class CatalogAPI {
  constructor(baseUrl = '/api/frontend') {
    this.baseUrl = baseUrl;
    this.cache = new Map();
    this.cacheTimeout = 5 * 60 * 1000; // 5 минут
    this.lastUpdateCheck = null;
    this.updateCheckInterval = 30000; // проверка каждые 30 секунд
    this.autoUpdateEnabled = true;
    this.startUpdateChecker();
  }

  // Получение бандлов
  async getBundles(forceRefresh = false) {
    const cacheKey = 'bundles';
    
    if (!forceRefresh && this.cache.has(cacheKey)) {
      const cached = this.cache.get(cacheKey);
      if (Date.now() - cached.timestamp < this.cacheTimeout) {
        return cached.data;
      }
    }

    try {
      const response = await fetch(`${this.baseUrl}/bundles`);
      const result = await response.json();
      
      if (result.success) {
        this.cache.set(cacheKey, {
          data: result.data,
          timestamp: Date.now()
        });
        return result.data;
      } else {
        throw new Error('Ошибка загрузки бандлов');
      }
    } catch (error) {
      console.error('Ошибка API:', error);
      return [];
    }
  }

  // Получение услуг
  async getServices(forceRefresh = false) {
    const cacheKey = 'services';
    
    if (!forceRefresh && this.cache.has(cacheKey)) {
      const cached = this.cache.get(cacheKey);
      if (Date.now() - cached.timestamp < this.cacheTimeout) {
        return cached.data;
      }
    }

    try {
      const response = await fetch(`${this.baseUrl}/services`);
      const result = await response.json();
      
      if (result.success) {
        this.cache.set(cacheKey, {
          data: result.data,
          timestamp: Date.now()
        });
        return result.data;
      } else {
        throw new Error('Ошибка загрузки услуг');
      }
    } catch (error) {
      console.error('Ошибка API:', error);
      return [];
    }
  }

  // Получение опций
  async getOptions(forceRefresh = false) {
    const cacheKey = 'options';
    
    if (!forceRefresh && this.cache.has(cacheKey)) {
      const cached = this.cache.get(cacheKey);
      if (Date.now() - cached.timestamp < this.cacheTimeout) {
        return cached.data;
      }
    }

    try {
      const response = await fetch(`${this.baseUrl}/options`);
      const result = await response.json();
      
      if (result.success) {
        this.cache.set(cacheKey, {
          data: result.data,
          timestamp: Date.now()
        });
        return result.data;
      } else {
        throw new Error('Ошибка загрузки опций');
      }
    } catch (error) {
      console.error('Ошибка API:', error);
      return [];
    }
  }

  // Проверка обновлений
  async checkForUpdates() {
    if (!this.autoUpdateEnabled) return false;
    
    try {
      const response = await fetch(`${this.baseUrl}/check-updates`);
      const result = await response.json();
      
      if (result.success) {
        const updates = result.data;
        
        if (!this.lastUpdateCheck) {
          this.lastUpdateCheck = updates;
          return false;
        }
        
        let needsUpdate = false;
        
        if (updates.bundles_updated_at !== this.lastUpdateCheck.bundles_updated_at) {
          console.log('🔄 Обнаружены обновления бандлов');
          this.cache.delete('bundles');
          needsUpdate = true;
        }
        
        if (updates.services_updated_at !== this.lastUpdateCheck.services_updated_at) {
          console.log('🔄 Обнаружены обновления услуг');
          this.cache.delete('services');
          needsUpdate = true;
        }
        
        if (updates.options_updated_at !== this.lastUpdateCheck.options_updated_at) {
          console.log('🔄 Обнаружены обновления опций');
          this.cache.delete('options');
          needsUpdate = true;
        }
        
        this.lastUpdateCheck = updates;
        return needsUpdate;
      }
    } catch (error) {
      console.error('Ошибка проверки обновлений:', error);
    }
    return false;
  }

  // Запуск проверки обновлений
  startUpdateChecker() {
    setInterval(async () => {
      const needsUpdate = await this.checkForUpdates();
      if (needsUpdate) {
        this.onUpdateAvailable?.();
      }
    }, this.updateCheckInterval);
  }

  // Очистка кеша
  clearCache() {
    this.cache.clear();
  }

  // Переключение автообновления
  toggleAutoUpdate() {
    this.autoUpdateEnabled = !this.autoUpdateEnabled;
    return this.autoUpdateEnabled;
  }
}
```

### Рендереры для отображения данных

```javascript
class BundleRenderer {
  constructor(containerSelector, api) {
    this.container = document.querySelector(containerSelector);
    this.api = api;
  }

  async renderBundles() {
    if (!this.container) return;

    try {
      const bundles = await this.api.getBundles();
      this.container.innerHTML = '';

      if (bundles.length === 0) {
        this.container.innerHTML = '<div>Форматы отдыха не найдены</div>';
        return;
      }

      bundles.forEach(bundle => {
        const bundleElement = this.createBundleElement(bundle);
        this.container.appendChild(bundleElement);
      });

    } catch (error) {
      console.error('Ошибка рендеринга бандлов:', error);
      this.container.innerHTML = '<div>Ошибка загрузки бандлов</div>';
    }
  }

  createBundleElement(bundle) {
    const div = document.createElement('div');
    div.className = 'bundle-card';

    const servicesList = bundle.services
      .map(service => `<li>${service.title}</li>`)
      .join('');

    div.innerHTML = `
      <h3 class="bundle-title">${bundle.title}</h3>
      <p class="bundle-description">${bundle.description}</p>
      <div class="bundle-details">
        <span class="nights">${bundle.nights} ночей</span>
        <span class="price">${bundle.price_formatted}</span>
      </div>
      <div class="bundle-services">
        <h4>Включено в пакет:</h4>
        <ul>${servicesList}</ul>
      </div>
    `;

    return div;
  }
}
```

## 📱 Пример использования

### HTML структура
```html
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Наши предложения</h1>
        
        <div class="update-notification" id="updateNotification">
            🔄 Обнаружены изменения! Данные обновляются автоматически...
        </div>

        <h2>📦 Форматы отдыха <span class="count" id="bundlesCount">(0)</span></h2>
        <div id="bundles-container">
            <div>Загрузка...</div>
        </div>

        <h2>🛠️ Услуги <span class="count" id="servicesCount">(0)</span></h2>
        <div id="services-container">
            <div>Загрузка...</div>
        </div>

        <h2>➕ Опции <span class="count" id="optionsCount">(0)</span></h2>
        <div id="options-container">
            <div>Загрузка...</div>
        </div>
    </div>

    <script src="catalog-api.js"></script>
    <script src="app.js"></script>
</body>
</html>
```

### JavaScript инициализация
```javascript
// Инициализация
const api = new CatalogAPI();
const bundleRenderer = new BundleRenderer('#bundles-container', api);
const serviceRenderer = new ServiceRenderer('#services-container', api);
const optionRenderer = new OptionRenderer('#options-container', api);

// Настраиваем автоматическое обновление
api.onUpdateAvailable = async () => {
    console.log('🔄 Автоматическое обновление данных...');
    
    // Показываем уведомление
    const notification = document.getElementById('updateNotification');
    notification.style.display = 'block';
    
    await Promise.all([
        bundleRenderer.renderBundles(),
        serviceRenderer.renderServices(),
        optionRenderer.renderOptions()
    ]);
    
    // Скрываем уведомление через 3 секунды
    setTimeout(() => {
        notification.style.display = 'none';
    }, 3000);
    
    console.log('✅ Данные обновлены автоматически');
};

// Загрузка при старте
document.addEventListener('DOMContentLoaded', function() {
    Promise.all([
        bundleRenderer.renderBundles(),
        serviceRenderer.renderServices(),
        optionRenderer.renderOptions()
    ]);
});
```

## 🎨 CSS стили

### Базовые стили для карточек
```css
.bundle-card, .service-card, .option-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 16px;
    margin: 16px 0;
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}

.bundle-card:hover, .service-card:hover, .option-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.bundle-title, .service-title, .option-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 8px 0;
    color: #333;
}

.bundle-description, .service-description, .option-description {
    color: #666;
    margin: 0 0 12px 0;
    line-height: 1.4;
}

.price {
    font-weight: bold;
    color: #2e7d32;
    font-size: 18px;
}

.nights {
    background: #e3f2fd;
    color: #1976d2;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 600;
}

.update-notification {
    background: #d1ecf1;
    border: 1px solid #bee5eb;
    color: #0c5460;
    padding: 10px;
    border-radius: 4px;
    margin: 10px 0;
    display: none;
}

.count {
    font-weight: bold;
    color: #1976d2;
}
```

## 🔧 Настройка

### 1. **Базовый URL API**
```javascript
const api = new CatalogAPI('/api/frontend');
```

### 2. **Интервал проверки обновлений**
```javascript
api.updateCheckInterval = 30000; // 30 секунд
```

### 3. **Время жизни кеша**
```javascript
api.cacheTimeout = 5 * 60 * 1000; // 5 минут
```

### 4. **Включение/отключение автообновления**
```javascript
api.toggleAutoUpdate(); // Переключить
api.autoUpdateEnabled = false; // Отключить
```

## 🚨 Обработка ошибок

### Обработка ошибок API
```javascript
try {
    const bundles = await api.getBundles();
    // Обработка данных
} catch (error) {
    console.error('Ошибка загрузки данных:', error);
    // Показать сообщение пользователю
    showErrorMessage('Не удалось загрузить данные. Попробуйте позже.');
}
```

### Fallback для изображений
```html
<img src="/storage/bundles/image.jpg" 
     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
<div class="no-image" style="display: none;">
    <span>Нет фото</span>
</div>
```

## 📊 Мониторинг

### Логирование
```javascript
// Включить подробное логирование
api.onUpdateAvailable = async () => {
    console.log('🔄 Обновление данных...');
    console.time('update-time');
    
    await Promise.all([
        bundleRenderer.renderBundles(),
        serviceRenderer.renderServices(),
        optionRenderer.renderOptions()
    ]);
    
    console.timeEnd('update-time');
    console.log('✅ Обновление завершено');
};
```

### Метрики производительности
```javascript
// Отслеживание времени загрузки
const startTime = performance.now();
const bundles = await api.getBundles();
const loadTime = performance.now() - startTime;
console.log(`Загрузка бандлов заняла: ${loadTime.toFixed(2)}ms`);
```

## 🔐 Безопасность

### Валидация данных
```javascript
function validateBundle(bundle) {
    if (!bundle.id || !bundle.title || !bundle.price) {
        throw new Error('Некорректные данные бандла');
    }
    return true;
}
```

### Санитизация HTML
```javascript
function sanitizeHtml(html) {
    const div = document.createElement('div');
    div.textContent = html;
    return div.innerHTML;
}
```

## 📱 Адаптивность

### Responsive дизайн
```css
@media (max-width: 768px) {
    .bundle-card, .service-card, .option-card {
        margin: 8px 0;
        padding: 12px;
    }
    
    .bundle-title, .service-title, .option-title {
        font-size: 16px;
    }
}
```

## 🎯 Лучшие практики

1. **Всегда проверяйте успешность ответа API**
2. **Используйте кеширование для улучшения производительности**
3. **Обрабатывайте ошибки сети и API**
4. **Показывайте индикаторы загрузки**
5. **Используйте fallback для изображений**
6. **Логируйте важные события**
7. **Тестируйте на разных устройствах**

## 📞 Поддержка

При возникновении вопросов или проблем обращайтесь к разработчику бэкенда.

---

**Версия документа:** 1.0  
**Дата обновления:** 2024-01-15  
**Автор:** Backend Developer



