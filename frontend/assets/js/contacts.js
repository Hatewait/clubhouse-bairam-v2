document.addEventListener('DOMContentLoaded', () => {
  const container = document.querySelector('.contacts[data-dest]');
  if (!container) return;

  const destination = container.dataset.dest; // адрес или "lat,lon"

  const buildYandexRoute = (origin, dest, mode) => {
    const params = new URLSearchParams({
      rtext: `${origin}~${dest}`,
      rtt: mode === 'auto' ? 'auto' : 'mt', // 'mt' — общественный
      lang: 'ru_RU'
    });
    return `https://yandex.ru/maps/?${params.toString()}`;
  };

  const openInNewTab = (url) => window.open(url, '_blank', 'noopener,noreferrer');

  // 1) Любые статические кнопки: data-route + data-origin + data-mode ('auto' | 'mt')
  container.querySelectorAll('[data-route]').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      // если это <a>, чтобы не прыгал по пустому href
      if (btn.tagName === 'A') e.preventDefault();

      const origin = btn.dataset.origin || '';          // точка старта
      const mode   = btn.dataset.mode === 'mt' ? 'mt' : 'auto'; // дефолт — auto
      const url    = buildYandexRoute(origin, destination, mode);
      openInNewTab(url);
    });
  });

  // 2) «От меня»: строим от текущей геолокации (можно и для auto, и для mt)
  container.querySelectorAll('[data-route-me]').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const mode = btn.dataset.mode === 'mt' ? 'mt' : 'auto';

      const openFromEmpty = () => openInNewTab(buildYandexRoute('', destination, mode));
      if (!navigator.geolocation) return openFromEmpty();

      navigator.geolocation.getCurrentPosition(
          ({ coords }) => {
            const origin = `${coords.latitude.toFixed(6)},${coords.longitude.toFixed(6)}`;
            openInNewTab(buildYandexRoute(origin, destination, mode));
          },
          openFromEmpty,
          { enableHighAccuracy: true, timeout: 8000, maximumAge: 60000 }
      );
    });
  });
});
