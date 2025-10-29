<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
  <meta name="format-detection" content="telephone=yes">
  <title></title>

  <link rel="stylesheet" type="text/css" href="/assets/css/libs/swiper/swiper-bundle.min.css" />
  <link rel="stylesheet" type="text/css" href="/assets/css/libs/graph-modal/graph-modal.min.css" />
  <link rel="stylesheet" type="text/css" href="/assets/css/libs/fancybox/fancybox.css" />

  <link rel="stylesheet" href="assets/css/base.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/reveal.css">
</head>

<body>

  <?php
  require_once __DIR__ . '/includes/header.php';
  render_header([
    'classes' => ['header_bg', 'space-md'],
    'active' => 'formats',
    'media' => [
      'type' => 'image',
      'src' => '/assets/img/arenda/intro.jpg'
    ],
    'content' => [
      'title' => 'Аренда <span> <span class="heading-lg-accent">клубного</span> дома</span>',
      'subtitle' => 'Пространство свободы',
      'button' => [
        'text' => 'Оставить заявку',
        'data' => 'data-graph-path="consult"'
      ]
    ]
  ]);
  ?>

  <main class="page-wrapper page-wrapper_mod">
    <div class="container space-md">
      <?php
      require_once __DIR__ . '/includes/breadcrumbs.php';
      render_breadcrumbs([
        ['title' => 'Главная', 'url' => '/index.php'],
        ['title' => 'Аренда клубного дома'],
      ]);
      ?>
    </div>
    <section class="intro">
      <div class="container">
        <p class="intro__text text-center subtitle-xl">
          В вашем распоряжении вся Захарова балка с клубным домом и конюшней «Байрам». Закрытый приватный дом, <span
            class="subtitle-xl-accent color-accent">расположенный вдали от туристических баз</span> с чистейшим воздухом
          и абсолютным комфортом
        </p>
      </div>
    </section>

    <section class="promo space-xxxl">
      <div class="container">
        <div class="promo__wrap space-xxl">
          <div class="promo__text">
            <h2 class="heading-md">Аренда дома</h2>
            <p class="text-lg-regular text-color-secondary">
              Чтобы забронировать Резиденцию, узнать свободные даты и подробную информацию, просто свяжитесь с нашим
              отделом бронирования. Также можно оставить заявку в форме ниже
            </p>
          </div>

          <div class="promo__banner">
            <a href="" class="link-md-light color-white">info@test.ru</a>
            <div class="promo__banner-group">
              <a href="" class="link-md-light color-white">+7 (495) 153-14-98</a>
              <span class="caption-md text-color-secondary-opacity">с 8:00 до 21:00 (ежедневно)</span>
            </div>

          </div>
        </div>

        <div class="promo-table" aria-labelledby="promo-table-title">
          <div class="promo-table__scroll">
            <table class="promo-table__table">
              <thead class="promo-table__head">
                <tr class="promo-table__row promo-table__row--head">
                  <th class="promo-table__cell promo-table__cell--head text-lg-regular">Продолжительность</th>
                  <th class="promo-table__cell promo-table__cell--head text-lg-regular">Цена в низкий сезон, ₽</th>
                  <th class="promo-table__cell promo-table__cell--head text-lg-regular">Цена в высокий сезон, ₽</th>
                </tr>
              </thead>

              <tbody class="promo-table__body">
                <tr class="promo-table__row">
                  <td class="promo-table__cell promo-table__cell--title">3 дня (2 ночи)</td>
                  <td class="promo-table__cell promo-table__cell--price" data-label="Цена в низкий сезон, ₽">2.370.000
                  </td>
                  <td class="promo-table__cell promo-table__cell--price" data-label="Цена в высокий сезон, ₽">34 000 000
                  </td>
                </tr>

                <tr class="promo-table__row">
                  <td class="promo-table__cell promo-table__cell--title">4 дня (3 ночи)</td>
                  <td class="promo-table__cell promo-table__cell--price" data-label="Цена в низкий сезон, ₽">4.100.000
                  </td>
                  <td class="promo-table__cell promo-table__cell--price" data-label="Цена в высокий сезон, ₽">34 000 000
                  </td>
                </tr>

                <tr class="promo-table__row">
                  <td class="promo-table__cell promo-table__cell--title">5 дней (4 ночи)</td>
                  <td class="promo-table__cell promo-table__cell--price" data-label="Цена в низкий сезон, ₽">4.900.000
                  </td>
                  <td class="promo-table__cell promo-table__cell--price" data-label="Цена в высокий сезон, ₽">34 000 000
                  </td>
                </tr>

                <tr class="promo-table__row">
                  <td class="promo-table__cell promo-table__cell--title">6 дней (5 ночей)</td>
                  <td class="promo-table__cell promo-table__cell--price" data-label="Цена в низкий сезон, ₽">5.600.000
                  </td>
                  <td class="promo-table__cell promo-table__cell--price" data-label="Цена в высокий сезон, ₽">34 000 000
                  </td>
                </tr>

                <tr class="promo-table__row">
                  <td class="promo-table__cell promo-table__cell--title">7 дней (6 ночей)</td>
                  <td class="promo-table__cell promo-table__cell--price" data-label="Цена в низкий сезон, ₽">6.500.000
                  </td>
                  <td class="promo-table__cell promo-table__cell--price" data-label="Цена в высокий сезон, ₽">34 000 000
                  </td>
                </tr>

                <tr class="promo-table__row">
                  <td class="promo-table__cell promo-table__cell--title">8 дней (7 ночей)</td>
                  <td class="promo-table__cell promo-table__cell--price" data-label="Цена в низкий сезон, ₽">7.200.000
                  </td>
                  <td class="promo-table__cell promo-table__cell--price" data-label="Цена в высокий сезон, ₽">34 000 000
                  </td>
                </tr>

                <tr class="promo-table__row">
                  <td class="promo-table__cell promo-table__cell--title">9 дней (8 ночей)</td>
                  <td class="promo-table__cell promo-table__cell--price" data-label="Цена в низкий сезон, ₽">8.100.000
                  </td>
                  <td class="promo-table__cell promo-table__cell--price" data-label="Цена в высокий сезон, ₽">34 000 000
                  </td>
                </tr>

                <tr class="promo-table__row">
                  <td class="promo-table__cell promo-table__cell--title">10 дней (9 ночей)</td>
                  <td class="promo-table__cell promo-table__cell--price" data-label="Цена в низкий сезон, ₽">9.100.000
                  </td>
                  <td class="promo-table__cell promo-table__cell--price" data-label="Цена в высокий сезон, ₽">34 000 000
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <section class="services space-xxxl">
      <div class="container">
        <h2 class="heading-md space-xl">Основные услуги</h2>
        <div class="swiper services-slider" data-double-slider>
          <div class="swiper-wrapper">

            <!-- Слайд 1 -->
            <div class="swiper-slide">
              <div class="services-slider__layout">
                <div class="services-slider__image-wrap">
                  <!--<img src="/assets/img/arenda/slide-1.jpg" alt="">-->
                </div>
                <div class="slide-right">
                  <div class="slide-content services-slider__slide-content">
                    <h3 class="subtitle-xl-accent">Домашнее питание</h3>
                    <p class="text-md-regular text-color-secondary">Завтраки, обеды и ужины с блюдами кавказской кухни.
                      Всё готовится из свежих продуктов, подаётся с теплом и вниманием к деталям</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Слайд 2 -->
            <div class="swiper-slide">
              <div class="services-slider__layout">
                <div class="services-slider__image-wrap">
                  <!--<img src="/assets/img/arenda/slide-2.jpg" alt="">-->
                </div>
                <div class="slide-right">
                  <div class="slide-content services-slider__slide-content">
                    <h3 class="subtitle-xl-accent">Прогулки на лошадях</h3>
                    <p class="text-md-regular text-color-secondary">Прогулки по кавказским тропам с опытными инструкторами. Маршруты можно подобрать под любой уровень подготовки.</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Слайд 3 -->
            <div class="swiper-slide">
              <div class="services-slider__layout">
                <div class="services-slider__image-wrap">
                  <!--<img src="/assets/img/arenda/slide-3.jpg" alt="">-->
                </div>
                <div class="slide-right">
                  <div class="slide-content services-slider__slide-content">
                    <h3 class="subtitle-xl-accent">Фурако</h3>
                    <p class="text-md-regular text-color-secondary">Тёплая купель под открытым небом, где особенно приятно отдыхать вечером после активного дня.</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Слайд 4 -->
            <div class="swiper-slide">
              <div class="services-slider__layout">
                <div class="services-slider__image-wrap">
                  <!--<img src="/assets/img/arenda/slide-4.jpg" alt="">-->
                </div>
                <div class="slide-right">
                  <div class="slide-content services-slider__slide-content">
                    <h3 class="subtitle-xl-accent">Трансфер</h3>
                    <p class="text-md-regular text-color-secondary">Мы встречаем вас в аэропорту или на ж/д вокзале и с комфортом доставляем прямо до порога клуба.</p>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <!-- Кнопки -->
          <div class="services-slider__nav swiper-nav">
            <button class="btn-reset swiper-nav__prev round-button" data-double-prev>
              <svg xmlns="http://www.w3.org/2000/svg" width="11" height="18" viewBox="0 0 11 18" fill="none">
                <path
                  d="M8.96021 0.292725C9.35075 -0.0974973 9.98384 -0.0976989 10.3743 0.292725C10.7645 0.683163 10.7644 1.3163 10.3743 1.70679L3.0813 8.99976L10.3743 16.2927C10.7645 16.6832 10.7644 17.3163 10.3743 17.7068C9.9838 18.0973 9.35074 18.0971 8.96021 17.7068L0.253174 8.99976L8.96021 0.292725Z"
                  fill="white" />
              </svg>
            </button>
            <button class="btn-reset swiper-nav__next round-button" data-double-next>
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                <path
                  d="M11.2935 7.29272C11.684 6.90248 12.3171 6.90229 12.7075 7.29272L21.4146 15.9998L12.7075 24.7068C12.317 25.0973 11.684 25.0972 11.2935 24.7068C10.9029 24.3163 10.9029 23.6832 11.2935 23.2927L18.5864 15.9998L11.2935 8.70679C10.9029 8.31626 10.9029 7.68325 11.2935 7.29272Z"
                  fill="white" />
              </svg>
            </button>
          </div>

          <!-- ЕДИНЫЙ превью-слайдер. Визуально расположен «внутри» правой части -->
          <div class="swiper services-thumbs-slider" data-thumbs-slider data-observe-h>
            <div class="swiper-wrapper">
              <div class="swiper-slide">
                <!--<img src="/assets/img/arenda/slide-1.jpg" alt="">-->
              </div>
              <div class="swiper-slide">
                <!--<img src="/assets/img/arenda/slide-2.jpg" alt="">-->
              </div>
              <div class="swiper-slide">
                <!--<img src="/assets/img/arenda/slide-3.jpg" alt="">-->
              </div>
              <div class="swiper-slide">
                <!--<img src="/assets/img/arenda/slide-4.jpg" alt="">-->
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="details space-xxxl">
      <div class="container">
        <div class="details__top space-xl">
          <h3 class="heading-md">Про дом и территорию</h3>
          <p class="text-color-secondary">Чтобы ваш отдых был максимально комфортным, мы подготовили схему планировки
            дома. Вы можете заранее ознакомиться с расположением и видом комнат, гостиной, кухни и зон отдыха, а также
            выбрать, как разместиться вашей компанией</p>
        </div>

        <div class="rooms-plan" aria-label="План помещений">
          <div class="rooms-plan__grid">

            <!-- Гостиная: cols 1–6, rows 1–3 -->
            <article class="rooms-plan__room rooms-plan__room--living">
              <h3 class="rooms-plan__title">Гостиная</h3>
              <button class="rooms-plan__stack" type="button" aria-label="Гостиная">
                <img class="rooms-plan__thumb" src="img/living-1.jpg" alt="">
                <img class="rooms-plan__thumb" src="img/living-2.jpg" alt="">
                <img class="rooms-plan__thumb" src="img/living-3.jpg" alt="">
                <img class="rooms-plan__thumb" src="img/living-4.jpg" alt="">
              </button>
            </article>

            <!-- Кухня: cols 7–9, rows 1–2 -->
            <article class="rooms-plan__room rooms-plan__room--kitchen">
              <h3 class="rooms-plan__title">Кухня</h3>
              <button class="rooms-plan__stack" type="button" aria-label="Кухня">
                <img class="rooms-plan__thumb" src="img/kitchen-1.jpg" alt="">
                <img class="rooms-plan__thumb" src="img/kitchen-2.jpg" alt="">
                <img class="rooms-plan__thumb" src="img/kitchen-3.jpg" alt="">
              </button>
            </article>

            <!-- Спальня (верхняя правая): cols 10–12, rows 1–2 -->
            <article class="rooms-plan__room rooms-plan__room--bed-a">
              <h3 class="rooms-plan__title">Спальня</h3>
              <button class="rooms-plan__stack" type="button" aria-label="Спальня">
                <img class="rooms-plan__thumb" src="img/bed-a-1.jpg" alt="">
                <img class="rooms-plan__thumb" src="img/bed-a-2.jpg" alt="">
                <img class="rooms-plan__thumb" src="img/bed-a-3.jpg" alt="">
              </button>
            </article>

            <!-- Спальня (средняя правая): cols 10–12, rows 3–4 -->
            <article class="rooms-plan__room rooms-plan__room--bed-b">
              <h3 class="rooms-plan__title">Спальня</h3>
              <button class="rooms-plan__stack" type="button" aria-label="Спальня">
                <img class="rooms-plan__thumb" src="img/bed-b-1.jpg" alt="">
                <img class="rooms-plan__thumb" src="img/bed-b-2.jpg" alt="">
                <img class="rooms-plan__thumb" src="img/bed-b-3.jpg" alt="">
              </button>
            </article>

            <!-- Спальня (левый низ): cols 1–6, rows 4–7 -->
            <article class="rooms-plan__room rooms-plan__room--bed-c">
              <h3 class="rooms-plan__title">Спальня</h3>
              <button class="rooms-plan__stack" type="button" aria-label="Спальня">
                <img class="rooms-plan__thumb" src="img/bed-c-1.jpg" alt="">
                <img class="rooms-plan__thumb" src="img/bed-c-2.jpg" alt="">
                <img class="rooms-plan__thumb" src="img/bed-c-3.jpg" alt="">
              </button>
            </article>

            <!-- Санузлы: cols 7–8, rows 4–7 -->
            <article class="rooms-plan__room rooms-plan__room--bath">
              <h3 class="rooms-plan__title">Санузлы</h3>
              <button class="rooms-plan__stack" type="button" aria-label="Санузлы">
                <img class="rooms-plan__thumb" src="img/bath-1.jpg" alt="">
                <img class="rooms-plan__thumb" src="img/bath-2.jpg" alt="">
                <img class="rooms-plan__thumb" src="img/bath-3.jpg" alt="">
              </button>
            </article>

            <!-- Холл: cols 9–12, rows 4–7 -->
            <article class="rooms-plan__room rooms-plan__room--hall">
              <h3 class="rooms-plan__title">Холл</h3>
              <button class="rooms-plan__stack" type="button" aria-label="Холл">
                <img class="rooms-plan__thumb" src="img/hall-1.jpg" alt="">
                <img class="rooms-plan__thumb" src="img/hall-2.jpg" alt="">
                <img class="rooms-plan__thumb" src="img/hall-3.jpg" alt="">
              </button>
            </article>

          </div>
        </div>

      </div>
    </section>

  </main>
  <?php include __DIR__ . '/includes/footer.php'; ?>
  <script type="text/javascript" src="/assets/js/libs/swiper/swiper-bundle.min.js"></script>
  <script type="text/javascript" src="/assets/js/libs/graph-modal/graph-modal.min.js"></script>
  <script type="text/javascript" src="/assets/js/libs/fancybox/fancybox.umd.js"></script>
  <script type="text/javascript" src="assets/js/libs/validate/validate-js.min.js"></script>
  <script type="text/javascript" src="assets/js/libs/inputmask/inputmask.min.js"></script>
  <script type="text/javascript" src="/assets/js/libs/dynamic-adaptive/dynamic-adaptive.min.js"></script>

  <script type="text/javascript" src="/assets/js/swipers.js"></script>
  <script type="text/javascript" src="/assets/js/nav.js"></script>
  <script type="text/javascript" src="/assets/js/main.js"></script>
  <script type="text/javascript" src="/assets/js/arenda.js"></script>

  <script type="text/javascript" src="assets/js/validate-form.js"></script>
  <script type="text/javascript" src="assets/js/reveal-on-scroll.js"></script>
  <script type="text/javascript" src="assets/js/main-services-dynamic.js?v=1761430650"></script>
  <script type="text/javascript" src="assets/js/smooth-scroll.js"></script>
  <script type="text/javascript" src="assets/js/dropdown-menu.js"></script>
  <script type="text/javascript" src="assets/js/hero-reveal.js"></script>
</body>

</html>
