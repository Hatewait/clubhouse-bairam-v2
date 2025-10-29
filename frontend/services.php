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
    'active' => 'services'
  ]);
  ?>
  <main class="page-wrapper">
    <div class="container space-md">
      <?php
      require_once __DIR__ . '/includes/breadcrumbs.php';
      render_breadcrumbs([
        ['title' => 'Главная', 'url' => '/index.php'],
        ['title' => 'Дополнительные услуги'],
      ]);
      ?>
    </div>
    <section class="services space-xxxl">
      <div class="container">
        <h1 class="heading-md space-xl">Услуги</h1>
        <div class="services-grid" data-stagger="services">
          <div class="services-grid__item">
            <h2 class="subtitle-xl space-sm">Дополнить впечатления</h2>
            <p class="text-lg-regular space-lg">Добавьте в свой отдых особенные моменты: прогулки, мастер-классы,
              гастро-вечера или полёт над горами. Вы можете выбрать готовое впечатление или предложить своё — мы всё
              организуем</p>

            <button class="btn-reset button-filled color-white buttons-lg-medium" data-graph-path="consult"
              aria-label="консультация">
              Оставить заявку
            </button>
          </div>
          <div class="tour services-grid__item">
            <div class="tour__bg tour__bg_1"></div>

            <div class="tour__bottom">
              <p class="subtitle-lg-accent color-white">Вертолётные прогулки</p>
              <p class="tour__text text-sm-regular color-white">Полёт над горами и ущельями Кавказа с панорамными
                видами, которые невозможно увидеть с земли.</p>

            </div>
          </div>
          <div class="tour services-grid__item">
            <div class="tour__bg tour__bg_2"></div>


            <div class="tour__bottom">
              <p class="subtitle-lg-accent color-white">Маршруты на внедорожниках</p>
              <p class="tour__text text-sm-regular color-white">Идеально для любителей драйва и приключений —
                индивидуальные маршруты по труднодоступным местам.</p>

            </div>
          </div>
          <div class="tour services-grid__item">
            <div class="tour__bg tour__bg_3"></div>


            <div class="tour__bottom">
              <p class="subtitle-lg-accent color-white">Гала-ужин с артистами</p>
              <p class="tour__text text-sm-regular color-white">Камерный концерт, живая музыка или целое шоу — вечер,
                который станет финальной точкой вашего отдыха.</p>

            </div>
          </div>
          <div class="tour services-grid__item">
            <div class="tour__bg tour__bg_4"></div>


            <div class="tour__bottom">
              <p class="subtitle-lg-accent color-white">Профессиональный фотограф</p>
              <p class="tour__text text-sm-regular color-white">Личная фотосессия или фотоотчёт вашего события, чтобы
                сохранить воспоминания не только в памяти.</p>

            </div>
          </div>
          <div class="tour services-grid__item">
            <div class="tour__bg tour__bg_5"></div>


            <div class="tour__bottom">
              <p class="subtitle-lg-accent color-white">Организация под любое событие</p>
              <p class="tour__text text-sm-regular color-white">Свадьбы, корпоративы, дни рождения или уикенды с
                друзьями — мы создаём сценарии под любой формат и количество дней.</p>

            </div>
          </div>
          <div class="tour services-grid__item">
            <div class="tour__bg tour__bg_6"></div>


            <div class="tour__bottom">
              <p class="subtitle-lg-accent color-white">Массаж</p>
              <p class="tour__text text-sm-regular color-white">Тёплое масло, приглушённый свет, запах кедра — массаж в
                «Байраме» возвращает телу лёгкость, а душе спокойствие.</p>

            </div>
          </div>
          <div class="tour services-grid__item">
            <div class="tour__bg tour__bg_7"></div>


            <div class="tour__bottom">
              <p class="subtitle-lg-accent color-white">Мастер-классы</p>
              <p class="tour__text text-sm-regular color-white">Вы можете выбрать готовую тему или предложить свою — мы
                создадим все условия, чтобы провести занятие</p>

            </div>
          </div>
          <div class="tour services-grid__item">
            <div class="tour__bg tour__bg_8"></div>


            <div class="tour__bottom">
              <p class="subtitle-lg-accent color-white">Охота</p>
              <p class="tour__text text-sm-regular color-white">Рассвет в горах, свежий воздух, шаги по влажной траве и
                опытный егерь рядом. Настоящая охота в духе старых традиций Кавказа</p>

            </div>
          </div>
        </div>
      </div>
    </section>


  </main>
  <?php include __DIR__ . '/includes/footer.php'; ?>
  <script type="text/javascript" src="assets/js/libs/swiper/swiper-bundle.min.js"></script>
  <script type="text/javascript" src="assets/js/libs/graph-modal/graph-modal.min.js"></script>
  <script type="text/javascript" src="assets/js/libs/accordion/accordion.js"></script>
  <script type="text/javascript" src="assets/js/libs/fancybox/fancybox.umd.js"></script>
  <script type="text/javascript" src="assets/js/libs/validate/validate-js.min.js"></script>
  <script type="text/javascript" src="assets/js/libs/inputmask/inputmask.min.js"></script>
  <script type="text/javascript" src="assets/js/libs/dynamic-adaptive/dynamic-adaptive.min.js"></script>

  <script type="text/javascript" src="assets/js/nav.js"></script>
  <script type="text/javascript" src="assets/js/main.js"></script>

  <script type="text/javascript" src="assets/js/validate-form.js"></script>
  <script type="text/javascript" src="assets/js/reveal-on-scroll.js"></script>
  <script type="text/javascript" src="assets/js/services-stagger.js"></script>
  <script type="text/javascript" src="assets/js/services-dynamic.js"></script>
  <script type="text/javascript" src="assets/js/smooth-scroll.js"></script>
  <script type="text/javascript" src="assets/js/dropdown-menu.js"></script>
  <script type="text/javascript" src="assets/js/hero-reveal.js"></script>
</body>

</html>
