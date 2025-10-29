// глушим переход по ссылке tour
// Отменяем переход по ссылке .tour, если кликнули по кнопке внутри .tour__bottom
document.addEventListener('DOMContentLoaded', () => {
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.tour__bottom button');
    if (!btn) return; // не кнопка — выходим

    const link = btn.closest('a.tour');
    if (link) {
      e.preventDefault(); // отменяем переход по ссылке
      // всплытие оставляем, чтобы сработал код открытия модалки
    }
  }, true); // используем capture, чтобы сработать раньше дефолта ссылки
});

