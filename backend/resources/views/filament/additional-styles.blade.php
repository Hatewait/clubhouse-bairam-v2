<style>
/* Кастомизация бокового меню Filament для адаптивной ширины */

/* Основной контейнер бокового меню */
.fi-sidebar {
    width: auto !important;
    min-width: fit-content !important;
    max-width: none !important;
}

/* Контейнер навигации */
.fi-sidebar-nav {
    width: auto !important;
    min-width: fit-content !important;
}

/* Группы навигации */
.fi-sidebar-nav-group {
    width: auto !important;
    min-width: fit-content !important;
}

/* Элементы навигации */
.fi-sidebar-nav-item {
    width: auto !important;
    min-width: fit-content !important;
}

/* Ссылки навигации */
.fi-sidebar-nav-item-link {
    width: auto !important;
    min-width: fit-content !important;
    padding-right: 1.5rem !important;
}

/* Текст навигации */
.fi-sidebar-nav-item-label {
    white-space: nowrap !important;
    overflow: visible !important;
    text-overflow: unset !important;
}

/* Иконки навигации */
.fi-sidebar-nav-item-icon {
    flex-shrink: 0 !important;
}

/* Основной контент */
.fi-main {
    flex: 1 !important;
    width: 100% !important;
    min-width: 0 !important;
}

/* Контейнер страницы */
.fi-main-content {
    width: 100% !important;
    max-width: none !important;
}

/* Адаптивность для мобильных устройств */
@media (max-width: 768px) {
    .fi-sidebar {
        width: 16rem !important;
        min-width: 16rem !important;
    }
    
    .fi-main {
        margin-left: 0 !important;
    }
}

/* Дополнительные стили для лучшего отображения */
.fi-sidebar-header {
    width: auto !important;
    min-width: fit-content !important;
}

.fi-sidebar-footer {
    width: auto !important;
    min-width: fit-content !important;
}

/* Убираем фиксированную ширину у всех элементов сайдбара */
.fi-sidebar * {
    max-width: none !important;
}

/* Обеспечиваем правильное отображение длинных названий */
.fi-sidebar-nav-item-link {
    display: flex !important;
    align-items: center !important;
    gap: 0.75rem !important;
}

/* Дополнительные стили для календаря */
.booking-calendar-container {
    max-width: none !important;
    width: 100% !important;
}

.year-calendar-wrapper {
    width: 100% !important;
}

/* Карточки месяцев - фиксированная минимальная ширина */
.month-calendar {
    min-width: 280px !important;
    width: auto !important;
    flex-shrink: 0 !important;
}

/* Контейнер календаря - горизонтальный скролл при необходимости */
.year-calendar-wrapper {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 2rem !important;
    overflow-x: auto !important;
    padding-bottom: 1rem !important;
}

/* Сетка календаря внутри месяца */
.calendar-grid {
    min-width: 280px !important;
    width: 280px !important;
}

/* Дни календаря */
.calendar-day {
    min-width: 40px !important;
    width: 40px !important;
    height: 40px !important;
}

/* Стили для таблиц и форм */
.fi-table {
    width: 100% !important;
    max-width: none !important;
}

.fi-form {
    width: 100% !important;
    max-width: none !important;
}

/* Контейнеры таблиц */
.fi-ta-table-container {
    width: 100% !important;
    max-width: none !important;
}

/* Сами таблицы */
.fi-ta-table {
    width: 100% !important;
    table-layout: auto !important;
}

/* Контейнеры страниц */
.fi-page {
    width: 100% !important;
    max-width: none !important;
}

/* Контейнеры ресурсов */
.fi-resource {
    width: 100% !important;
    max-width: none !important;
}

/* Улучшаем отображение длинных названий в таблицах */
.fi-ta-col-header {
    white-space: nowrap !important;
}

/* Стили для модальных окон */
.fi-modal {
    max-width: 90vw !important;
}

/* Стили для карточек */
.fi-section {
    width: 100% !important;
}
</style>