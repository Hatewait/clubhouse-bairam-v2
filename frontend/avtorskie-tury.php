<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
  <meta name="format-detection" content="telephone=yes">
  <title>Клубный дом Байрам</title>

  <link rel="stylesheet" type="text/css" href="assets/css/libs/swiper/swiper-bundle.min.css" />
  <link rel="stylesheet" type="text/css" href="assets/css/libs/graph-modal/graph-modal.min.css" />
  <link rel="stylesheet" type="text/css" href="assets/css/libs/fancybox/fancybox.css" />

  <link rel="stylesheet" href="assets/css/base.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/reveal.css">
</head>
<?php
require_once __DIR__ . '/includes/header.php';
render_header([
  'classes' => ['header_bg', 'space-md'],
  'active' => 'formats',
  'media' => [
    'type' => 'image',
    'src' => 'assets/img/avtorskie/intro.jpg'
  ],
  'content' => [
    'title' => 'Авторские <span> <span class="heading-lg-accent">туры</span> в Байрам</span>',
    'subtitle' => 'Пространство свободы',
    'button' => [
      'text' => 'Оставить заявку',
      'data' => 'data-graph-path="consult"'
    ]
  ]
]);
?>

<body>

  <main class="page-wrapper page-wrapper_mod">
    <div class="container space-md">
      <?php
      require_once __DIR__ . '/includes/breadcrumbs.php';
      render_breadcrumbs([
        ['title' => 'Главная', 'url' => '/index.php'],
        ['title' => 'Авторские туры'],
      ]);
      ?>
    </div>
    <section class="intro">
      <div class="container">
        <p class="intro__text text-center subtitle-xl">
          Наша команда организует уникальные туры под ваш запрос. Продумаем всё до деталей: от безопасного транспорта
          <span class="subtitle-xl-accent color-accent">опытных гидов</span> и до  <span
            class="subtitle-xl-accent color-accent">королевского</span> выездного обеда с <span
            class="subtitle-xl-accent color-accent">потрясающими</span> кавказскими видами
        </p>
      </div>
    </section>

    <section class="feature-icons space-xxxl">
      <div class="container">
        <div class="feature-icons__content">
          <h2 class="heading-md">Авторские программы отдыха</h2>
          <p>Мы создаём уникальные сценарии под ваши интересы: конные походы по закрытым тропам, вечерние ужины с
            артистами, вертолётные прогулки и гастрономические маршруты. Всё продумано до мелочей, чтобы ваш отдых был
            особенным</p>
        </div>

        <div class="feature-icons__items" data-stagger="feature-icons">
          <div class="feature-icons__item">
            <div class="feature-icons__round">
              <img src="/assets/img/avtorskie/feature-icons/1.svg" alt="">
            </div>
            <span>Конные прогулки</span>
          </div>
          <div class="feature-icons__item">
            <div class="feature-icons__round">
              <img src="/assets/img/avtorskie/feature-icons/2.svg" alt="">
            </div>
            <span>Велосипедные прогулки</span>
          </div>
          <div class="feature-icons__item">
            <div class="feature-icons__round">
              <img src="/assets/img/avtorskie/feature-icons/3.svg" alt="">
            </div>
            <span>Автомобильные экскурсии</span>
          </div>
          <div class="feature-icons__item">
            <div class="feature-icons__round">
              <img src="/assets/img/avtorskie/feature-icons/4.svg" alt="">
            </div>
            <span>Пешие походы</span>
          </div>
          <div class="feature-icons__item">
            <div class="feature-icons__round">
              <img src="/assets/img/avtorskie/feature-icons/5.svg" alt="">
            </div>
            <span>Рыбалка</span>
          </div>
          <div class="feature-icons__item">
            <div class="feature-icons__round">
              <img src="/assets/img/avtorskie/feature-icons/6.svg" alt="">
            </div>
            <span>Вертолетные прогулки</span>
          </div>
        </div>
      </div>
    </section>


    <section class="tabs tab" id="tab-1">
      <div class="container">
        <h3 class="heading-md space-xl">Маршруты</h3>


        <div class="tabs__buttons space-xl">
          <button class="btn-reset tabs__button subtitle-md tab-btn tab-btn-active" data-target-id="0">Нижне-Архызское
            городище</button>
          <button class="btn-reset tabs__button subtitle-md tab-btn" data-target-id="1">Кяфарское городище</button>
        </div>

        <div class="tab-content">
          <div class="tab-pane tab-pane-show" data-id="0">
            <div class="tabs__inner">
              <div class="tabs__description">
                <p class="subtitle-xl-accent space-xl">Марухское и Архызское ущелья</p>
                <p class="text-color-secondary text-md-regular space-sm"><span
                    class="text-md-regular text-md-regular-bold text-color-secondary">Продолжительность:</span> 3–4 часа
                </p>
                <p class="text-color-secondary text-md-regular space-sm"><span
                    class="text-md-regular text-md-regular-bold text-color-secondary">Формат:</span> Джип-тур с пешими
                  остановками</p>
                <p class="text-color-secondary text-md-regular"><span
                    class="text-md-regular text-md-regular-bold text-color-secondary">Описание:</span>
                  <br> Путешествие к Кяфарскому городищу — это возможность прикоснуться к древней истории горного Кавказа. По пути вы увидите живописные ущелья и водопады, а также подниметесь к руинам средневекового поселения, расположенного на склонах хребта. С вершины открывается панорама долины реки Кяфар и заснеженных вершин. Во время прогулки предусмотрены короткие остановки для отдыха, фотографий и пикника с блюдами местной кухни.
                </p>
              </div>
              <div class="tabs__pic">
                <img src="/assets/img/avtorskie/map-tour.png" alt="">
              </div>
            </div>
          </div>
          <div class="tab-pane" data-id="1">
            <div class="tabs__inner">
              <div class="tabs__description">
                <p class="subtitle-xl-accent space-xl">Кяфарское городище</p>
                <p class="text-color-secondary text-md-regular space-sm"><span
                    class="text-md-regular text-md-regular-bold text-color-secondary">Продолжительность:</span> 3–4 часа
                </p>
                <p class="text-color-secondary text-md-regular space-sm"><span
                    class="text-md-regular text-md-regular-bold text-color-secondary">Формат:</span> Джип-тур с пешими
                  остановками</p>
                <p class="text-color-secondary text-md-regular"><span
                    class="text-md-regular text-md-regular-bold text-color-secondary">Описание:</span>
                  <br> Маршрут пройдёт по самым живописным горным дорогам Кавказа. Вы подниметесь на обзорные площадки
                  с видами на ущелья и альпийские луга, сделаете остановки для коротких прогулок и фотосессий. В пути —
                  пикник с блюдами кавказской кухни и напитками, чтобы насладиться атмосферой на высоте.
                </p>
              </div>
              <div class="tabs__pic">
                <img src="/assets/img/avtorskie/map-tour.png" alt="">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="intro">
      <div class="container">
        <p class="intro__text text-center subtitle-xl">
          Каждое фото — <span class="subtitle-xl-accent color-accent">готовая история.</span> Впечатления, которые
          останутся с вами навсегда
        </p>
      </div>
    </section>

    <section id="mosaic" class="mosaic space-xxl" aria-label="Фотогалерея">
      <div class="mosaic__grid">
        <div href="#" class="mosaic-card mosaic-card_col-12-sm">
          <img src="/assets/img/avtorskie/mosaic/1.jpg" alt="">
        </div>
        <div class="mosaic-card mosaic-card_col-3-lg mosaic-card_col-6-md mosaic-card_col-12-sm" href="/category">
          <img src="/assets/img/avtorskie/mosaic/2.jpg" alt="">
        </div>

        <div href="#" class="mosaic-card mosaic-card_col-3-lg mosaic-card_col-6-md mosaic-card_col-12-sm">
          <img src="/assets/img/avtorskie/mosaic/3.jpg" alt="">
        </div>

        <div href="#" class="mosaic-card mosaic-card_col-3-lg mosaic-card_col-6-md mosaic-card_col-12-sm">
          <img src="/assets/img/avtorskie/mosaic/4.jpg" alt="">
        </div>

        <div href="#" class="mosaic-card mosaic-card_col-3-lg mosaic-card_col-6-md mosaic-card_col-12-sm">
          <img src="/assets/img/avtorskie/mosaic/5.jpg" alt="">
        </div>

        <div href="#" class="mosaic-card mosaic-card_col-12-sm">
          <img src="/assets/img/avtorskie/mosaic/6.jpg" alt="">
        </div>

        <div href="#" class="mosaic-card mosaic-card_col-12-sm">
          <img src="/assets/img/avtorskie/mosaic/7.jpg" alt="">
        </div>

        <div href="#" class="mosaic-card mosaic-card_col-3-lg mosaic-card_col-6-md mosaic-card_col-12-sm">
          <img src="/assets/img/avtorskie/mosaic/8.jpg" alt="">
        </div>

        <div href="#" class="mosaic-card mosaic-card_col-3-lg mosaic-card_col-6-md mosaic-card_col-12-sm">
          <img src="/assets/img/avtorskie/mosaic/9.jpg" alt="">
        </div>

      </div>

    </section>

    <section class="program space-xxxl">
      <div class="container">
        <div class="program__flex">
          <div class="section-intro">
            <h3 class="heading-md">Программа</h3>
            <p class="text-color-secondary">
              Мы подготовили несколько готовых форматов отдыха — от уютных выходных до масштабных корпоративных выездов.
              Ваша программа может быть такой же или совершенно особенной — мы подберём всё под ваши пожелания,
              атмосферу и количество дней
            </p>
          </div>
          <div class="program__grid">
            <div class="program__grid-item program__grid-item_col-3">
              <div class="program__day">
                <span class="heading-lg-accent color-accent">1</span>
                <span class="heading-sm-accent color-accent">день</span>
              </div>
              <p class="text-md-regular text-color-secondary">Вы приезжаете в клубный дом, отдыхаете после дороги
                и знакомитесь с атмосферой Байрама. Вечером — уютный ужин у камина с домашней кухней и живой музыкой</p>
            </div>
            <div class="program__grid-item program__grid-item_col-3">
              <div class="program__day">
                <span class="heading-lg-accent color-accent">2</span>
                <span class="heading-sm-accent color-accent">день</span>
              </div>
              <p class="text-md-regular text-color-secondary">Утром — катание на лошадях по кавказским тропам
                с проводником. Днём — свободное время для прогулок и отдыха, вечером — расслабление в фурако
                под звёздным небом</p>
            </div>
            <div class="program__grid-item program__grid-item_col-3">
              <div class="program__day">
                <span class="heading-lg-accent color-accent">3</span>
                <span class="heading-sm-accent color-accent">день</span>
              </div>
              <p class="text-md-regular text-color-secondary">Джип-тур по живописным маршрутам Кавказа, пикник
                на природе. Вечером — ужин с дегустацией локальных блюд</p>
            </div>
            <div class="program__grid-item program__grid-item_col-3">
              <div class="program__day">
                <span class="heading-lg-accent color-accent">4</span>
                <span class="heading-sm-accent color-accent">день</span>
              </div>
              <p class="text-md-regular text-color-secondary">Походные маршруты или лёгкие треккинги на выбор,
                мастер-класс по кавказской кухне или художественной фотографии (по запросу)</p>
            </div>
            <div class="program__grid-item program__grid-item_col-6">
              <div class="program__day">
                <span class="heading-lg-accent color-accent">5</span>
                <span class="heading-sm-accent color-accent">день</span>
              </div>
              <p class="text-md-regular text-color-secondary">Днём — свободный формат: бассейн, фурако, прогулки.
                Вечером — гала-ужин с живой музыкой или выступлением артистов, фото — и видеосъёмка на память</p>
            </div>
            <div class="program__grid-item program__grid-item_col-6">
              <div class="program__day">
                <span class="heading-lg-accent color-accent">6</span>
                <span class="heading-sm-accent color-accent">день</span>
              </div>
              <p class="text-md-regular text-color-secondary">Завтракаете в клубном доме, неспешный сбор, прощальная
                фотосессия и дорога домой</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="call-action space-xxxl">
      <div class="container call-action__container">
        <div class="call-action__block">
          <p class="heading-wrap heading-md color-white space-lg">
            Отдых, который
            <span> <span class="heading-md-accent">подойдет</span> именно вам</span>
          </p>
          <p class="color-white space-xl">Оставьте заявку и наши менеджеры смогут подобрать тур, который вам подойдет
          </p>

          <button class="btn-reset button-filled color-white buttons-lg-medium" data-graph-path="consult"
            aria-label="консультация">
            Оставить заявку
          </button>
        </div>
      </div>
    </section>

    <section class="faq space-xxxl">
      <div class="container">
        <h3 class="heading-md space-xl">FAQ</h3>
        <div class="accordion" id="accordion-1" data-faq-accordion>
          <div class="accordion__item accordion__item_show">
            <div class="accordion__header subtitle-lg-accent">
              Можно ли изменить программу под свои пожелания?
            </div>
            <div class="accordion__body">
              <div class="accordion__content">
                <p class="text-color-secondary">
                  Да, мы подстраиваемся под ваши интересы: вы можете выбрать готовый формат или собрать свой.
                </p>
              </div>
            </div>
          </div>
          <div class="accordion__item">
            <div class="accordion__header subtitle-lg-accent">
              Как заказать дополнительные опции?
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
              Можно ли приехать с детьми?
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

  <script type="text/javascript" src="assets/js/swipers.js"></script>
  <script type="text/javascript" src="assets/js/nav.js"></script>
  <script type="text/javascript" src="assets/js/main.js"></script>
  <script type="text/javascript" src="assets/js/tabs.js"></script>


  <script type="text/javascript" src="assets/js/validate-form.js"></script>
  <script type="text/javascript" src="assets/js/reveal-on-scroll.js"></script>
  <script type="text/javascript" src="assets/js/mosaic-reveal.js"></script>
  <script type="text/javascript" src="assets/js/feature-icons-stagger.js"></script>
  <script type="text/javascript" src="assets/js/smooth-scroll.js"></script>
  <script type="text/javascript" src="assets/js/dropdown-menu.js"></script>
  <script type="text/javascript" src="assets/js/hero-reveal.js"></script>
</body>

</html>
