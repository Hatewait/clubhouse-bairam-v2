<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
  <meta name="format-detection" content="telephone=yes">
  <title>Ответы на вопросы - Захарова Балка</title>

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
    'active' => 'questions'
  ]);
  ?>

  <main class="page-wrapper">
    <div class="container space-md">
      <?php
      require_once __DIR__ . '/includes/breadcrumbs.php';
      render_breadcrumbs([
        ['title' => 'Главная', 'url' => '/index.php'],
        ['title' => 'Ответы на вопросы'],
      ]);
      ?>
    </div>

    <section class="faq space-xxxl">
      <div class="container">
        <h1 class="heading-md space-xl">Ответы на вопросы</h1>
        <div class="accordion" id="accordion-1" data-faq-accordion>
          <div class="accordion__item accordion__item_show">
            <div class="accordion__header subtitle-lg-accent">
              Как добраться на машине?
            </div>
            <div class="accordion__body">
              <div class="accordion__content">
                <p class="text-color-secondary">
                  Проложите маршрут в навигаторе: точка назначения «Захарова Балка, 1, село Даусуз, Зеленчукский район,
                  Карачаево-Черкесская Республика» или воспользуйтесь готовым маршрутом в Яндекс.Картах. Среднее время
                  в пути — от 60 минут, в зависимости от трафика. Возможен объезд по платной трассе.
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
                  В клубном доме «Байрам» вы проведёте несколько дней в собственном ритме. Днём — пешие и конные
                  прогулки по горам, вечером — домашние ужины и душевные посиделки у камина. Формат на 4 дня позволяет
                  никуда не спешить и почувствовать атмосферу дома по‑настоящему
                </p>
              </div>
            </div>
          </div>
          <div class="accordion__item">
            <div class="accordion__header subtitle-lg-accent">
              Есть ли трансфер?
            </div>
            <div class="accordion__body">
              <div class="accordion__content">
                <p class="text-color-secondary">
                  В клубном доме «Байрам» вы проведёте несколько дней в собственном ритме. Днём — пешие и конные
                  прогулки по горам, вечером — домашние ужины и душевные посиделки у камина. Формат на 4 дня позволяет
                  никуда не спешить и почувствовать атмосферу дома по‑настоящему
                </p>
              </div>
            </div>
          </div>
          <div class="accordion__item">
            <div class="accordion__header subtitle-lg-accent">
              Какие программы отдыха доступны?
            </div>
            <div class="accordion__body">
              <div class="accordion__content">
                <p class="text-color-secondary">
                  У нас есть несколько форматов отдыха: аренда клубного дома для самостоятельного отдыха, авторские туры
                  с экскурсиями и активностями, а также различные услуги для комфортного пребывания в горах.
                </p>
              </div>
            </div>
          </div>
          <div class="accordion__item">
            <div class="accordion__header subtitle-lg-accent">
              Что включено в стоимость аренды?
            </div>
            <div class="accordion__body">
              <div class="accordion__content">
                <p class="text-color-secondary">
                  В стоимость аренды включено проживание в клубном доме, использование всей инфраструктуры, парковка,
                  Wi-Fi, отопление и электричество. Дополнительные услуги оплачиваются отдельно.
                </p>
              </div>
            </div>
          </div>
          <div class="accordion__item">
            <div class="accordion__header subtitle-lg-accent">
              Можно ли приехать с детьми?
            </div>
            <div class="accordion__body">
              <div class="accordion__content">
                <p class="text-color-secondary">
                  Да, мы рады семьям с детьми. В доме есть все необходимое для комфортного пребывания с детьми,
                  включая безопасную территорию и возможность организации детских активностей.
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