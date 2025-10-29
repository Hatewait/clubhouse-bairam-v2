<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <title>Тест: API интеграция + форма заявки</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body { 
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; 
      margin: 0; 
      padding: 20px; 
      background: #f5f5f5; 
    }
    .container { 
      max-width: 1200px; 
      margin: 0 auto; 
      background: white; 
      border-radius: 12px; 
      padding: 24px; 
      box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
    }
    h1 { 
      margin: 0 0 24px; 
      text-align: center; 
      color: #1976d2; 
      border-bottom: 3px solid #e3f2fd; 
      padding-bottom: 16px; 
    }
    h2 { 
      color: #1976d2; 
      margin: 32px 0 16px; 
      border-bottom: 2px solid #e3f2fd; 
      padding-bottom: 8px; 
    }
    .section { 
      background: #fafafa; 
      border: 1px solid #e0e0e0; 
      border-radius: 8px; 
      padding: 20px; 
      margin: 20px 0; 
    }
    .grid { 
      display: grid; 
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
      gap: 20px; 
      margin: 20px 0; 
    }
    .form-grid { 
      display: grid; 
      grid-template-columns: repeat(2, minmax(260px, 420px)); 
      gap: 20px 28px; 
      align-items: start; 
    }
    label { 
      display: block; 
      font-weight: 600; 
      margin: 10px 0 6px; 
      color: #333; 
    }
    input, select, textarea, button { 
      width: 100%; 
      box-sizing: border-box; 
      padding: 8px 10px; 
      font-size: 14px; 
      border: 1px solid #ddd; 
      border-radius: 4px; 
    }
    input[type="date"]{ padding: 6px 8px; }
    textarea { height: 72px; resize: vertical; }
    fieldset { 
      border: 1px solid #e5e7eb; 
      border-radius: 10px; 
      padding: 12px; 
    }
    legend { 
      padding: 0 6px; 
      color: #111827; 
      font-weight: 600; 
    }
    .muted { color: #6b7280; font-size: 12px; }
    .row { margin-bottom: 12px; }
    .inline { display: inline-flex; gap: 10px; align-items: center; }
    .hint { font-size: 12px; color: #374151; padding-top: 4px; }
    .pill { 
      display:inline-block; 
      background:#e3f2fd; 
      color: #1976d2; 
      padding:4px 8px; 
      border-radius:999px; 
      font-size:12px; 
      font-weight: 600; 
    }
    .btn { 
      display:inline-block; 
      background:#1976d2; 
      color:#fff; 
      border:0; 
      padding:10px 14px; 
      border-radius:10px; 
      cursor:pointer; 
      font-weight: 600; 
    }
    .btn:hover { background: #1565c0; }
    .btn:disabled{ opacity:.6; cursor:not-allowed; }
    .btn-secondary { 
      background: #4caf50; 
      margin-left: 10px; 
    }
    .btn-secondary:hover { background: #45a049; }
    pre { 
      background:#000; 
      color:#fff; 
      padding:12px; 
      white-space:pre-wrap; 
      border-radius:8px; 
      font-size: 12px; 
    }
    .col-span-2 { grid-column: 1 / span 2; }
    
    /* Стили для карточек */
    .bundle-card, .service-card, .option-card {
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 16px;
      margin: 16px 0;
      background: white;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .bundle-card:hover, .service-card:hover, .option-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .bundle-image, .service-image, .option-image {
      width: 100%;
      height: 200px;
      overflow: hidden;
      border-radius: 4px;
      margin-bottom: 12px;
    }
    .bundle-image img, .service-image img, .option-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .no-image {
      width: 100%;
      height: 100%;
      background: #f5f5f5;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #666;
      font-size: 14px;
      border: 2px dashed #ddd;
      border-radius: 4px;
    }
    
    .bundle-image, .service-image, .option-image {
      position: relative;
    }
    
    .bundle-image img, .service-image img, .option-image img {
      transition: opacity 0.3s ease;
    }
    
    .bundle-image img:not([src]), .service-image img:not([src]), .option-image img:not([src]) {
      display: none;
    }
    .bundle-title, .service-title, .option-title {
      margin: 0 0 8px 0;
      color: #333;
      font-size: 18px;
      font-weight: 600;
    }
    .bundle-description, .service-description, .option-description {
      color: #666;
      margin: 0 0 12px 0;
      line-height: 1.4;
      font-size: 14px;
    }
    .bundle-details {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 12px 0;
    }
    .nights {
      background: #e3f2fd;
      color: #1976d2;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 14px;
      font-weight: 600;
    }
    .price, .service-price, .option-price {
      font-weight: bold;
      color: #2e7d32;
      font-size: 18px;
    }
    .bundle-services {
      margin: 12px 0;
    }
    .bundle-services h4 {
      margin: 0 0 8px 0;
      font-size: 14px;
      color: #666;
    }
    .bundle-services ul {
      margin: 0;
      padding-left: 16px;
      font-size: 14px;
      color: #666;
    }
    .select-bundle {
      background: #1976d2;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      width: 100%;
      margin-top: 12px;
    }
    .select-bundle:hover {
      background: #1565c0;
    }
    .option-checkbox {
      display: flex;
      align-items: center;
      margin-top: 12px;
      cursor: pointer;
    }
    .option-checkbox input[type="checkbox"] {
      margin-right: 8px;
      width: auto;
    }
    .loading {
      text-align: center;
      padding: 40px;
      color: #666;
      font-style: italic;
    }
    .error {
      background: #ffebee;
      color: #c62828;
      padding: 16px;
      border-radius: 4px;
      margin: 20px 0;
    }
    .api-info {
      background: #e8f5e8;
      border: 1px solid #4caf50;
      border-radius: 4px;
      padding: 16px;
      margin: 20px 0;
    }
    .api-info h3 {
      margin-top: 0;
      color: #2e7d32;
    }
    .api-endpoint {
      background: #f5f5f5;
      padding: 8px 12px;
      border-radius: 4px;
      font-family: monospace;
      margin: 8px 0;
      border-left: 4px solid #4caf50;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>🚀 Тест: API интеграция + форма заявки</h1>

    <div class="api-info">
      <h3>📡 Доступные API endpoints:</h3>
      <div class="api-endpoint">GET /api/frontend/bundles</div>
      <div class="api-endpoint">GET /api/frontend/services</div>
      <div class="api-endpoint">GET /api/frontend/options</div>
      <p><strong>Особенности:</strong> Кеширование на 5 минут, только активные записи, оптимизированный формат данных</p>
    </div>

    <button class="btn btn-secondary" id="refresh-data">🔄 Обновить данные</button>

    <h2>📦 Форматы отдыха (Пакеты услуг)</h2>
    <div id="bundles-container" class="grid">
      <div class="loading">Загрузка форматов отдыха...</div>
    </div>

    <h2>🛠️ Услуги</h2>
    <div id="services-container" class="grid">
      <div class="loading">Загрузка услуг...</div>
    </div>

    <h2>➕ Дополнительные опции</h2>
    <div id="options-container" class="grid">
      <div class="loading">Загрузка опций...</div>
    </div>

    <h2>📝 Форма заявки</h2>
    <div class="section">
      <form id="intakeForm" class="form-grid" novalidate>
    <div>
      <label>Имя</label>
      <input name="name" placeholder="Имя клиента" />
    </div>

    <div>
      <label>Телефон</label>
      <input name="phone" placeholder="+7 900 000-00-00" />
    </div>

    <div>
      <label>Email</label>
      <input name="email" placeholder="name@example.com" />
    </div>

    <div>
      <label>Формат отдыха (тур)</label>
      <select name="bundle_id" id="bundleSelect">
        <option value="">Загрузка...</option>
      </select>
      <div class="hint">Ночей по формату отдыха: <span id="bundleNights" class="pill">—</span></div>
    </div>

    <div>
      <label>Дата заезда</label>
      <input type="date" name="booking_date" id="dateFrom" />
      <div class="hint">Дата выезда будет рассчитана автоматически.</div>
    </div>

    <div>
      <label>Дата выезда</label>
      <input type="text" id="dateTo" disabled />
      <div class="hint">= Дата заезда + Ночей (из выбранного формата отдыха)</div>
    </div>

    <fieldset class="col-span-2">
      <legend>Дополнительные опции</legend>
      <div id="addonsBox" class="row muted">Загрузка списка…</div>
      <div class="muted">Отображаются только активные опции (включается в админке).</div>
    </fieldset>

    <div class="col-span-2">
      <label>Пожелания клиента (комментарий с сайта)</label>
      <textarea name="client_comment" placeholder="Любые дополнительные пожелания"></textarea>
    </div>

        <div class="col-span-2 inline">
          <button class="btn" type="submit">Отправить заявку</button>
          <span class="muted">На стороне сайта статус оплаты НЕ отправляем — всегда создаём «Новую».</span>
        </div>
      </form>
    </div>

    <h2>📊 Ответ API</h2>
    <pre id="apiOut"></pre>
  </div>

  <script>
    // API класс для работы с данными
    class CatalogAPI {
      constructor(baseUrl = '/api/frontend') {
        this.baseUrl = baseUrl;
        this.cache = new Map();
        this.cacheTimeout = 5 * 60 * 1000; // 5 минут
        this.lastUpdateCheck = null;
        this.updateCheckInterval = 30000; // проверка каждые 30 секунд
        this.startUpdateChecker();
      }

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

      clearCache() {
        this.cache.clear();
      }

      async checkForUpdates() {
        try {
          const response = await fetch(`${this.baseUrl}/check-updates`);
          const result = await response.json();
          
          if (result.success) {
            const updates = result.data;
            const now = new Date().toISOString();
            
            // Проверяем, нужно ли обновить данные
            let needsUpdate = false;
            
            if (!this.lastUpdateCheck) {
              this.lastUpdateCheck = updates;
              return false;
            }
            
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

      startUpdateChecker() {
        setInterval(async () => {
          const needsUpdate = await this.checkForUpdates();
          if (needsUpdate) {
            // Уведомляем о необходимости обновления
            this.onUpdateAvailable?.();
          }
        }, this.updateCheckInterval);
      }

      onUpdateAvailable() {
        // Переопределяется в рендерерах
      }
    }

    // Рендереры для отображения данных
    class BundleRenderer {
      constructor(containerSelector, api) {
        this.container = document.querySelector(containerSelector);
        this.api = api;
      }

      async renderBundles() {
        if (!this.container) {
          console.error('Контейнер для бандлов не найден');
          return;
        }

        try {
          const bundles = await this.api.getBundles();
          this.container.innerHTML = '';

          if (bundles.length === 0) {
            this.container.innerHTML = '<div class="error">Форматы отдыха временно недоступны</div>';
            return;
          }

          bundles.forEach(bundle => {
            const bundleElement = this.createBundleElement(bundle);
            this.container.appendChild(bundleElement);
          });

        } catch (error) {
          console.error('Ошибка рендеринга бандлов:', error);
          this.container.innerHTML = '<div class="error">Ошибка загрузки бандлов</div>';
        }
      }

      createBundleElement(bundle) {
        const div = document.createElement('div');
        div.className = 'bundle-card';
        div.dataset.bundleId = bundle.id;

        const servicesList = bundle.services
          .map(service => `<li>${service.title}</li>`)
          .join('');

        div.innerHTML = `
          <div class="bundle-image">
            ${bundle.image_url ? 
              `<img src="${bundle.image_url}" alt="${bundle.title}" loading="lazy" onload="console.log('Bundle image loaded:', '${bundle.image_url}')" onerror="console.log('Bundle image error:', '${bundle.image_url}'); this.style.display='none'; this.nextElementSibling.style.display='flex';">` : 
              ''
            }
            <div class="no-image" style="${bundle.image_url ? 'display: none;' : ''}">Нет фото</div>
          </div>
          <div class="bundle-content">
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
            <button class="select-bundle" data-bundle-id="${bundle.id}">
              Выбрать пакет
            </button>
          </div>
        `;

        return div;
      }
    }

    class ServiceRenderer {
      constructor(containerSelector, api) {
        this.container = document.querySelector(containerSelector);
        this.api = api;
      }

      async renderServices() {
        if (!this.container) {
          console.error('Контейнер для услуг не найден');
          return;
        }

        try {
          const services = await this.api.getServices();
          this.container.innerHTML = '';

          if (services.length === 0) {
            this.container.innerHTML = '<div class="error">Услуги временно недоступны</div>';
            return;
          }

          services.forEach(service => {
            const serviceElement = this.createServiceElement(service);
            this.container.appendChild(serviceElement);
          });

        } catch (error) {
          console.error('Ошибка рендеринга услуг:', error);
          this.container.innerHTML = '<div class="error">Ошибка загрузки услуг</div>';
        }
      }

      createServiceElement(service) {
        const div = document.createElement('div');
        div.className = 'service-card';
        div.dataset.serviceId = service.id;

        div.innerHTML = `
          <div class="service-image">
            ${service.image_url ? 
              `<img src="${service.image_url}" alt="${service.title}" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">` : 
              ''
            }
            <div class="no-image" style="${service.image_url ? 'display: none;' : ''}">Нет фото</div>
          </div>
          <div class="service-content">
            <h3 class="service-title">${service.title}</h3>
            <p class="service-description">${service.description}</p>
            ${service.price > 0 ? `<div class="service-price">${service.price_formatted}</div>` : ''}
          </div>
        `;

        return div;
      }
    }

    class OptionRenderer {
      constructor(containerSelector, api) {
        this.container = document.querySelector(containerSelector);
        this.api = api;
      }

      async renderOptions() {
        if (!this.container) {
          console.error('Контейнер для опций не найден');
          return;
        }

        try {
          const options = await this.api.getOptions();
          this.container.innerHTML = '';

          if (options.length === 0) {
            this.container.innerHTML = '<div class="error">Дополнительные опции недоступны</div>';
            return;
          }

          options.forEach(option => {
            const optionElement = this.createOptionElement(option);
            this.container.appendChild(optionElement);
          });

        } catch (error) {
          console.error('Ошибка рендеринга опций:', error);
          this.container.innerHTML = '<div class="error">Ошибка загрузки опций</div>';
        }
      }

      createOptionElement(option) {
        const div = document.createElement('div');
        div.className = 'option-card';
        div.dataset.optionId = option.id;

        const modifiers = [];
        if (option.price_per_person) modifiers.push('за человека');
        if (option.price_per_day) modifiers.push('за сутки');

        div.innerHTML = `
          <div class="option-image">
            ${option.image_url ? 
              `<img src="${option.image_url}" alt="${option.name}" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">` : 
              ''
            }
            <div class="no-image" style="${option.image_url ? 'display: none;' : ''}">Нет фото</div>
          </div>
          <div class="option-content">
            <h3 class="option-title">${option.name}</h3>
            <p class="option-description">${option.description}</p>
            <div class="option-price">
              ${option.price_formatted}
              ${modifiers.length > 0 ? `<small>(${modifiers.join(', ')})</small>` : ''}
            </div>
            <label class="option-checkbox">
              <input type="checkbox" value="${option.id}">
              <span>Добавить к заказу</span>
            </label>
          </div>
        `;

        return div;
      }
    }

    // Основная логика
    const out = document.getElementById('apiOut');
    const bundleSelect = document.getElementById('bundleSelect');
    const bundleNightsEl = document.getElementById('bundleNights');
    const dateFromEl = document.getElementById('dateFrom');
    const dateToEl = document.getElementById('dateTo');
    const addonsBox = document.getElementById('addonsBox');

    // Инициализация API и рендереров
    const api = new CatalogAPI();
    const bundleRenderer = new BundleRenderer('#bundles-container', api);
    const serviceRenderer = new ServiceRenderer('#services-container', api);
    const optionRenderer = new OptionRenderer('#options-container', api);

    // Настраиваем автоматическое обновление
    api.onUpdateAvailable = async () => {
      console.log('🔄 Автоматическое обновление данных...');
      await Promise.all([
        bundleRenderer.renderBundles(),
        serviceRenderer.renderServices(),
        optionRenderer.renderOptions()
      ]);
      console.log('✅ Данные обновлены автоматически');
    };

    /** Форматирование даты YYYY-MM-DD в локальном времени */
    const toISO = (d) => {
      const year = d.getFullYear();
      const month = String(d.getMonth() + 1).padStart(2, '0');
      const day = String(d.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    };

    /** Пересчёт даты выезда */
    function recalcDateTo() {
      const nights = Number(bundleSelect.selectedOptions[0]?.dataset.nights || 0);
      const from = dateFromEl.value;
      if (!from || !nights) { dateToEl.value = ''; return; }
      const [year, month, day] = from.split('-').map(Number);
      const d = new Date(year, month - 1, day);
      d.setDate(d.getDate() + nights);
      dateToEl.value = toISO(d);
    }

    /** Загрузка данных для формы */
    async function loadFormData() {
      try {
        // Форматы отдыха для формы
        const resB = await fetch(`${location.origin}/api/bundles-active`);
        const bundles = resB.ok ? await resB.json() : [];
        bundleSelect.innerHTML = '<option value="">— Выберите формат отдыха —</option>';
        for (const b of bundles) {
          const opt = document.createElement('option');
          opt.value = b.id;
          opt.dataset.nights = b.nights;
          opt.textContent = b.name ? `${b.name} — ${b.nights} ноч${b.nights===1?'ь':'и'}` : `Формат отдыха #${b.id} — ${b.nights} ночи`;
          bundleSelect.appendChild(opt);
        }
        bundleSelect.addEventListener('change', () => {
          const n = Number(bundleSelect.selectedOptions[0]?.dataset.nights || 0);
          bundleNightsEl.textContent = n ? n : '—';
          recalcDateTo();
        });

        // Доп. опции для формы
        const resA = await fetch(`${location.origin}/api/addons-active`);
        const addons = resA.ok ? await resA.json() : [];
        if (!addons.length) {
          addonsBox.textContent = 'Активных опций нет.';
        } else {
          addonsBox.innerHTML = '';
          for (const a of addons) {
            const id = `addon_${a.id}`;
            const wrap = document.createElement('div');
            wrap.className = 'row';
            wrap.innerHTML = `
              <label class="inline" for="${id}">
                <input type="checkbox" id="${id}" name="addons[]" value="${a.id}">
                <span>${a.name}${a.price_pretty ? ' — ' + a.price_pretty : ''}</span>
              </label>
            `;
            addonsBox.appendChild(wrap);
          }
        }

        dateFromEl.addEventListener('change', recalcDateTo);
      } catch (e) {
        out.textContent = 'Ошибка инициализации формы: ' + e.message;
      }
    }

    // Обработчики событий
    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('select-bundle')) {
        const bundleId = e.target.dataset.bundleId;
        bundleSelect.value = bundleId;
        bundleSelect.dispatchEvent(new Event('change'));
        // Прокрутка к форме
        document.getElementById('intakeForm').scrollIntoView({ behavior: 'smooth' });
      }
    });

    // Кнопка обновления данных
    document.getElementById('refresh-data').addEventListener('click', function() {
      api.clearCache();
      bundleRenderer.renderBundles();
      serviceRenderer.renderServices();
      optionRenderer.renderOptions();
      loadFormData();
    });

    // Форма заявки
    document.getElementById('intakeForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      out.textContent = 'Отправка…';

      const fd = new FormData(e.currentTarget);
      const raw = Object.fromEntries(fd.entries());
      const addons = fd.getAll('addons[]').map(Number).filter(Boolean);

      const payload = {
        name: (raw.name || '').trim(),
        email: (raw.email || '').trim() || null,
        phone: (raw.phone || '').trim(),
        booking_date: (raw.booking_date || '').trim(),
        bundle_id: raw.bundle_id ? Number(raw.bundle_id) : null,
        addons,
        client_comment: (raw.client_comment || '').trim() || null,
        status: 'new'
      };

      try {
        const res = await fetch(`${location.origin}/api/intake`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload),
        });

        const text = await res.text();
        try {
          const json = JSON.parse(text);
          out.textContent = (res.ok ? 'OK\n' : `Ошибка ${res.status}\n`) + JSON.stringify(json, null, 2);
        } catch {
          out.textContent = (res.ok ? 'OK (non-JSON)\n' : `Ошибка ${res.status}\n`) + text;
        }
      } catch (err) {
        out.textContent = 'Fetch error: ' + err.message;
      }
    });

    // Инициализация при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
      bundleRenderer.renderBundles();
      serviceRenderer.renderServices();
      optionRenderer.renderOptions();
      loadFormData();
    });
  </script>
</body>
</html>