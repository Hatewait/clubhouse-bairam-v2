<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
  <meta name="format-detection" content="telephone=yes">
  <title></title>

  <link rel="stylesheet" type="text/css" href="assets/css/libs/swiper/swiper-bundle.min.css" />
  <link rel="stylesheet" type="text/css" href="assets/css/libs/graph-modal/graph-modal.min.css" />
  <link rel="stylesheet" type="text/css" href="assets/css/libs/fancybox/fancybox.css" />

  <link rel="stylesheet" href="assets/css/base.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/reveal.css">
</head>

<body>

  <?php
  require_once __DIR__ . '/includes/header.php';
  render_header([
    'classes' => ['space-xxl'],
    'active' => 'contacts'
  ]);
  ?>
  <main class="page-wrapper">
    <div class="container space-md">
      <?php
      require_once __DIR__ . '/includes/breadcrumbs.php';
      render_breadcrumbs([
        ['title' => 'Главная', 'url' => '/index.php'],
        ['title' => 'Контакты'],
      ]);
      ?>
    </div>

    <section class="contacts space-xxxl"
      data-dest="Захарова Балка, 1, село Даусуз, Зеленчукский район, Карачаево-Черкесская Республика">
      <div class="container">
        <h1 class="heading-md space-xl">Контакты</h1>

        <div class="contacts__flex space-xxl">
          <div class="contacts__map">
            <script type="text/javascript" charset="utf-8" async
              src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A25085185ab3ad6d1e90d888b6ffc819190ef7b650e3d4d2844bbb56388c5b146&amp;width=100%25&amp;height=720&amp;lang=ru_RU&amp;scroll=true"></script>
          </div>

          <div class="contacts__detail">
            <div class="contacts__group">
              <p class="subtitle-lg-accent color-accent">Адрес:</p>
              <p class="text-sm-regular text-color-secondary">Захарова Балка, 1, село Даусуз, Зеленчукский район,
                Карачаево-Черкесская Республика</p>
            </div>

            <div class="contacts__group">
              <p class="subtitle-lg-accent color-accent">Телефон:</p>
              <a href="tel:" class="text-sm-regular text-color-secondary">+7 (900) 000-00-00</a>
              <div class="contacts__buttons">
                <a href="" class="button-bordered button-bordered_xs buttons-sm-medium">Telegram</a>
                <a href="" class="button-bordered button-bordered_xs buttons-sm-medium">Whatsapp</a>
              </div>
            </div>

            <div class="contacts__group">
              <p class="subtitle-lg-accent color-accent">E-mail:</p>
              <p class="text-sm-regular text-color-secondary">zaharova_balka@2025.gmail.com</p>
            </div>

            <div class="contacts__group">
              <p class="subtitle-lg-accent color-accent">Часы работы:</p>
              <p class="text-sm-regular text-color-secondary">пн-вс с 10:00 - 21:00</p>
            </div>
          </div>
        </div>
        <div class="contacts__bottom-row">
          <div class="contacts__group">
            <p class="subtitle-lg-accent color-accent">Маршрут на машине:</p>
            <div class="contacts__buttons">
              <a href="" class="button-bordered button-bordered_xs buttons-sm-medium" data-route data-mode="auto"
                data-origin="Аэропорт Минеральные Воды (MRV)">
                От аэропорта
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                  <path
                    d="M11.467 0.533325V6.66711C11.4668 7.10874 11.1079 7.46692 10.6662 7.46692C10.2248 7.46669 9.86668 7.10859 9.86644 6.66711V3.26477L1.89867 11.2325C1.58633 11.5445 1.08014 11.5445 0.767807 11.2325C0.455542 10.9201 0.455439 10.4131 0.767807 10.1007L8.7346 2.13391H5.33324C4.89171 2.13386 4.53385 1.77555 4.53343 1.33411C4.53343 0.892313 4.89146 0.533381 5.33324 0.533325H11.467Z"
                    fill="#363636" />
                </svg>
              </a>
              <a href="" class="button-bordered button-bordered_xs buttons-sm-medium" data-route data-mode="auto"
                data-origin="Железнодорожный вокзал Минеральные Воды">
                От ЖД
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                  <path
                    d="M11.467 0.533325V6.66711C11.4668 7.10874 11.1079 7.46692 10.6662 7.46692C10.2248 7.46669 9.86668 7.10859 9.86644 6.66711V3.26477L1.89867 11.2325C1.58633 11.5445 1.08014 11.5445 0.767807 11.2325C0.455542 10.9201 0.455439 10.4131 0.767807 10.1007L8.7346 2.13391H5.33324C4.89171 2.13386 4.53385 1.77555 4.53343 1.33411C4.53343 0.892313 4.89146 0.533381 5.33324 0.533325H11.467Z"
                    fill="#363636" />
                </svg>
              </a>
              <a href="#" class="button-bordered button-bordered_xs buttons-sm-medium" data-route-me data-mode="auto">
                От меня
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                  <path
                    d="M11.467 0.533325V6.66711C11.4668 7.10874 11.1079 7.46692 10.6662 7.46692C10.2248 7.46669 9.86668 7.10859 9.86644 6.66711V3.26477L1.89867 11.2325C1.58633 11.5445 1.08014 11.5445 0.767807 11.2325C0.455542 10.9201 0.455439 10.4131 0.767807 10.1007L8.7346 2.13391H5.33324C4.89171 2.13386 4.53385 1.77555 4.53343 1.33411C4.53343 0.892313 4.89146 0.533381 5.33324 0.533325H11.467Z"
                    fill="#363636" />
                </svg>
              </a>
            </div>
          </div>
          <div class="contacts__group">
            <p class="subtitle-lg-accent color-accent">Маршрут на общественном транспорте:</p>
            <div class="contacts__buttons">
              <a href="" class="button-bordered button-bordered_xs buttons-sm-medium" target="_blank" rel="noopener"
                data-route data-mode="mt" data-origin="Аэропорт Минеральные Воды (MRV)">
                От аэропорта
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                  <path
                    d="M11.467 0.533325V6.66711C11.4668 7.10874 11.1079 7.46692 10.6662 7.46692C10.2248 7.46669 9.86668 7.10859 9.86644 6.66711V3.26477L1.89867 11.2325C1.58633 11.5445 1.08014 11.5445 0.767807 11.2325C0.455542 10.9201 0.455439 10.4131 0.767807 10.1007L8.7346 2.13391H5.33324C4.89171 2.13386 4.53385 1.77555 4.53343 1.33411C4.53343 0.892313 4.89146 0.533381 5.33324 0.533325H11.467Z"
                    fill="#363636" />
                </svg>
              </a>
              <a href="" class="button-bordered button-bordered_xs buttons-sm-medium" target="_blank" rel="noopener"
                data-route data-mode="mt" data-origin="Железнодорожный вокзал Минеральные Воды">
                От ЖД
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                  <path
                    d="M11.467 0.533325V6.66711C11.4668 7.10874 11.1079 7.46692 10.6662 7.46692C10.2248 7.46669 9.86668 7.10859 9.86644 6.66711V3.26477L1.89867 11.2325C1.58633 11.5445 1.08014 11.5445 0.767807 11.2325C0.455542 10.9201 0.455439 10.4131 0.767807 10.1007L8.7346 2.13391H5.33324C4.89171 2.13386 4.53385 1.77555 4.53343 1.33411C4.53343 0.892313 4.89146 0.533381 5.33324 0.533325H11.467Z"
                    fill="#363636" />
                </svg>
              </a>
              <a href="#" class="button-bordered button-bordered_xs buttons-sm-medium" data-route-me data-mode="mt">
                От меня
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                  <path
                    d="M11.467 0.533325V6.66711C11.4668 7.10874 11.1079 7.46692 10.6662 7.46692C10.2248 7.46669 9.86668 7.10859 9.86644 6.66711V3.26477L1.89867 11.2325C1.58633 11.5445 1.08014 11.5445 0.767807 11.2325C0.455542 10.9201 0.455439 10.4131 0.767807 10.1007L8.7346 2.13391H5.33324C4.89171 2.13386 4.53385 1.77555 4.53343 1.33411C4.53343 0.892313 4.89146 0.533381 5.33324 0.533325H11.467Z"
                    fill="#363636" />
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="faq space-xxxl">
      <div class="container">
        <h3 class="heading-md space-xl">Частые вопросы</h3>
        <div class="accordion" id="accordion-1" data-faq-accordion>
          <div class="accordion__item accordion__item_show">
            <div class="accordion__header subtitle-lg-accent">
              Как добраться на машине?
            </div>
            <div class="accordion__body">
              <div class="accordion__content">
                <p class="text-color-secondary">
                  Проложите маршрут в навигаторе: точка назначения «Захарова Балка, 1, село Даусуз, Зеленчукский район,
                  Карачаево-Черкесская Республика» или воспользуйтесь готовым маршрутом в Яндекс.Картах. Среднее время
                  в пути — от 60 минут, в зависимости от трафика. Возможен объезд по платной трассе.
                </p>
              </div>
            </div>
          </div>
          <div class="accordion__item">
            <div class="accordion__header subtitle-lg-accent">
              Как добраться на общественном транспорте?
            </div>
            <div class="accordion__body">
              <div class="accordion__content">
                <p class="text-color-secondary">
                  В клубном доме «Байрам» вы проведёте несколько дней в собственном ритме. Днём — пешие и конные
                  прогулки по горам, вечером — домашние ужины и душевные посиделки у камина. Формат на 4 дня позволяет
                  никуда не спешить и почувствовать атмосферу дома по‑настоящему
                </p>
              </div>
            </div>
          </div>
          <div class="accordion__item ">
            <div class="accordion__header subtitle-lg-accent">
              Есть ли трансфер?
            </div>
            <div class="accordion__body">
              <div class="accordion__content">
                <p class="text-color-secondary">
                  В клубном доме «Байрам» вы проведёте несколько дней в собственном ритме. Днём — пешие и конные
                  прогулки по горам, вечером — домашние ужины и душевные посиделки у камина. Формат на 4 дня позволяет
                  никуда не спешить и почувствовать атмосферу дома по‑настоящему
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <?php include __DIR__ . '/includes/footer.php'; ?>
  <script type="text/javascript" src="assets/js/libs/swiper/swiper-bundle.min.js"></script>
  <script type="text/javascript" src="assets/js/libs/accordion/accordion.js"></script>
  <script type="text/javascript" src="assets/js/libs/graph-modal/graph-modal.min.js"></script>
  <script type="text/javascript" src="assets/js/libs/fancybox/fancybox.umd.js"></script>
  <script type="text/javascript" src="assets/js/libs/validate/validate-js.min.js"></script>
  <script type="text/javascript" src="assets/js/libs/inputmask/inputmask.min.js"></script>
  <script type="text/javascript" src="assets/js/libs/dynamic-adaptive/dynamic-adaptive.min.js"></script>
  <script type="text/javascript" src="assets/js/nav.js"></script>
  <script type="text/javascript" src="assets/js/main.js"></script>
  <script type="text/javascript" src="assets/js/tabs.js"></script>
  <script type="text/javascript" src="assets/js/contacts.js"></script>

  <script type="text/javascript" src="assets/js/validate-form.js"></script>
  <script type="text/javascript" src="assets/js/reveal-on-scroll.js"></script>
  <script type="text/javascript" src="assets/js/smooth-scroll.js"></script>
  <script type="text/javascript" src="assets/js/dropdown-menu.js"></script>
  <script type="text/javascript" src="assets/js/hero-reveal.js"></script>
</body>

</html>