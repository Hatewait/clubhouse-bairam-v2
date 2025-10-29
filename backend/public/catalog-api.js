/**
 * 🚀 CatalogAPI - JavaScript библиотека для работы с API каталога
 * 
 * Автоматически загружает данные бандлов, услуг и опций с кешированием
 * и автоматическим обновлением при изменениях в админке.
 * 
 * @version 1.0
 * @author Backend Developer
 */

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

    /**
     * Получение бандлов
     * @param {boolean} forceRefresh - принудительное обновление
     * @returns {Promise<Array>} массив бандлов
     */
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

    /**
     * Получение услуг
     * @param {boolean} forceRefresh - принудительное обновление
     * @returns {Promise<Array>} массив услуг
     */
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

    /**
     * Получение опций
     * @param {boolean} forceRefresh - принудительное обновление
     * @returns {Promise<Array>} массив опций
     */
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

    /**
     * Проверка обновлений
     * @returns {Promise<boolean>} true если есть обновления
     */
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

    /**
     * Запуск проверки обновлений
     */
    startUpdateChecker() {
        setInterval(async () => {
            const needsUpdate = await this.checkForUpdates();
            if (needsUpdate) {
                this.onUpdateAvailable?.();
            }
        }, this.updateCheckInterval);
    }

    /**
     * Очистка кеша
     */
    clearCache() {
        this.cache.clear();
    }

    /**
     * Переключение автообновления
     * @returns {boolean} текущее состояние
     */
    toggleAutoUpdate() {
        this.autoUpdateEnabled = !this.autoUpdateEnabled;
        return this.autoUpdateEnabled;
    }

    /**
     * Отправка заявки
     * @param {Object} data - данные заявки
     * @returns {Promise<Object>} результат отправки
     */
    async submitApplication(data) {
        try {
            const response = await fetch('/api/intake', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            return result;
        } catch (error) {
            console.error('Ошибка отправки заявки:', error);
            return { success: false, message: 'Ошибка отправки заявки' };
        }
    }
}

/**
 * Рендерер для бандлов
 */
class BundleRenderer {
    constructor(containerSelector, api) {
        this.container = document.querySelector(containerSelector);
        this.api = api;
    }

    /**
     * Рендеринг бандлов
     */
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

    /**
     * Создание элемента бандла
     * @param {Object} bundle - данные бандла
     * @returns {HTMLElement} элемент бандла
     */
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

/**
 * Рендерер для услуг
 */
class ServiceRenderer {
    constructor(containerSelector, api) {
        this.container = document.querySelector(containerSelector);
        this.api = api;
    }

    /**
     * Рендеринг услуг
     */
    async renderServices() {
        if (!this.container) return;

        try {
            const services = await this.api.getServices();
            this.container.innerHTML = '';

            if (services.length === 0) {
                this.container.innerHTML = '<div>Услуги не найдены</div>';
                return;
            }

            services.forEach(service => {
                const serviceElement = this.createServiceElement(service);
                this.container.appendChild(serviceElement);
            });

        } catch (error) {
            console.error('Ошибка рендеринга услуг:', error);
            this.container.innerHTML = '<div>Ошибка загрузки услуг</div>';
        }
    }

    /**
     * Создание элемента услуги
     * @param {Object} service - данные услуги
     * @returns {HTMLElement} элемент услуги
     */
    createServiceElement(service) {
        const div = document.createElement('div');
        div.className = 'service-card';

        div.innerHTML = `
            <h3 class="service-title">${service.title}</h3>
            <p class="service-description">${service.description}</p>
            ${service.price > 0 ? `<div class="price">${service.price_formatted}</div>` : ''}
        `;

        return div;
    }
}

/**
 * Рендерер для опций
 */
class OptionRenderer {
    constructor(containerSelector, api) {
        this.container = document.querySelector(containerSelector);
        this.api = api;
    }

    /**
     * Рендеринг опций
     */
    async renderOptions() {
        if (!this.container) return;

        try {
            const options = await this.api.getOptions();
            this.container.innerHTML = '';

            if (options.length === 0) {
                this.container.innerHTML = '<div>Опции не найдены</div>';
                return;
            }

            options.forEach(option => {
                const optionElement = this.createOptionElement(option);
                this.container.appendChild(optionElement);
            });

        } catch (error) {
            console.error('Ошибка рендеринга опций:', error);
            this.container.innerHTML = '<div>Ошибка загрузки опций</div>';
        }
    }

    /**
     * Создание элемента опции
     * @param {Object} option - данные опции
     * @returns {HTMLElement} элемент опции
     */
    createOptionElement(option) {
        const div = document.createElement('div');
        div.className = 'option-card';

        const modifiers = [];
        if (option.price_per_person) modifiers.push('за человека');
        if (option.price_per_day) modifiers.push('за сутки');

        div.innerHTML = `
            <h3 class="option-title">${option.name}</h3>
            <p class="option-description">${option.description}</p>
            <div class="price">
                ${option.price_formatted}
                ${modifiers.length > 0 ? `<small>(${modifiers.join(', ')})</small>` : ''}
            </div>
        `;

        return div;
    }
}

// Экспорт для использования в других модулях
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { CatalogAPI, BundleRenderer, ServiceRenderer, OptionRenderer };
}



