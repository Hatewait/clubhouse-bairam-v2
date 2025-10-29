# 🚀 Быстрый старт для фронтендера

## 📁 Файлы для интеграции

### 1. **JavaScript библиотека**
```html
<script src="/catalog-api.js"></script>
```

### 2. **HTML структура**
```html
<div id="bundles-container"></div>
<div id="services-container"></div>
<div id="options-container"></div>
```

### 3. **CSS стили**
```css
.bundle-card, .service-card, .option-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 16px;
    margin: 16px 0;
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
```

## 🔧 Минимальный код

```javascript
// Инициализация
const api = new CatalogAPI();
const bundleRenderer = new BundleRenderer('#bundles-container', api);
const serviceRenderer = new ServiceRenderer('#services-container', api);
const optionRenderer = new OptionRenderer('#options-container', api);

// Автоматическое обновление
api.onUpdateAvailable = async () => {
    await Promise.all([
        bundleRenderer.renderBundles(),
        serviceRenderer.renderServices(),
        optionRenderer.renderOptions()
    ]);
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

## 🌐 API Endpoints

- `GET /api/frontend/bundles` - получение бандлов
- `GET /api/frontend/services` - получение услуг  
- `GET /api/frontend/options` - получение опций
- `GET /api/frontend/check-updates` - проверка обновлений
- `POST /api/intake` - отправка заявки

## 📱 Пример страницы

Откройте `http://127.0.0.1:8000/frontend-example.html` для демонстрации.

## 🎯 Ключевые особенности

- ✅ **Автоматическое обновление** каждые 30 секунд
- ✅ **Кеширование** на 5 минут
- ✅ **Обработка ошибок** и fallback
- ✅ **Responsive дизайн**
- ✅ **TypeScript интерфейсы** в документации

## 📞 Поддержка

Полная документация: `FRONTEND_INTEGRATION_GUIDE.md`



