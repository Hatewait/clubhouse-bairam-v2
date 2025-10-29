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

<body>
  <?php
  require_once __DIR__ . '/includes/header.php';
  render_header([
    'classes' => ['header_bg'],
    'active' => 'index',
    'media' => [
      'type' => 'video',
      'src' => 'assets/video/intro.mp4',
      'poster' => 'assets/img/video-bg.jpg',
      'autoplay' => true,
      'muted' => true,
      'loop' => true,
      'playsinline' => true
    ],
    'content' => [
      'title' => 'Камерный <span> <span class="heading-lg-accent">отдых</span> в горах</span>',
      'subtitle' => 'Природа Кавказа, конные прогулки, уединение и полный комфорт',
      'button' => [
        'text' => 'Оставить заявку',
        'data' => 'data-graph-path="consult"'
      ]
    ]
  ]);
  ?>
  <main>
    <section class="intro">
      <h2 class="visually-hidden">Клубный дом в живописном месте</h2>
      <div class="container">
        <p class="intro__text text-center subtitle-xl">
          Клубный дом в живописном месте, где <span class="subtitle-xl-accent color-accent">всё пространство и
            внимание</span> — только вашей компании. Здесь нет случайных соседей: только вы, ваши близкие и настоящая
          природа
        </p>
      </div>
    </section>

    <section id="tours" class="tours space-xxxl">
      <div class="container">
        <div class="tours__wrap" id="tours-dynamic" data-stagger="tours">
          <!-- Динамическое наполнение бандлами из админки -->
          <div class="loading">Загрузка форматов...</div>
        </div>
      </div>
    </section>
    <section class="feature-icons space-xxxl" data-reveal="fade-up">
      <div class="container">
        <div class="feature-icons__content">
          <h2 class="heading-md">Что мы предлагаем</h2>
          <p>Мы продумали всё, чтобы ваш отдых был комфортным, приватным и по-настоящему особенным. Четыре главные
            причины выбрать Байрам — это атмосфера, сервис, гибкость и впечатления, которые остаются с вами надолго</p>
        </div>

        <div class="feature-icons__items" data-stagger="feature-icons">
          <div class="feature-icons__item">
            <div class="feature-icons__round">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
                <path
                  d="M20.877 14.1316C22.703 12.6228 25.2972 12.6228 27.123 14.1316L38.4883 23.5251C38.8117 23.7924 39 24.1968 39 24.6248V36.8347C38.9998 39.6873 36.7613 41.9998 34 41.9998H29V32.1384C29 29.2855 26.7615 26.9724 24 26.9724C21.2385 26.9724 19 29.2855 19 32.1384V41.9998H14C11.2387 41.9997 9.00019 39.6872 9 36.8347V24.6248C9.00003 24.1968 9.18842 23.7924 9.51172 23.5251L20.877 14.1316ZM20.8037 8.07886C22.6724 6.64066 25.3278 6.64066 27.1963 8.07886L43.4766 20.6091C44.0782 21.0722 44.1754 21.9169 43.6943 22.4958C43.2129 23.0746 42.3353 23.1686 41.7334 22.7058L25.4531 10.1755C24.6038 9.52181 23.3962 9.52181 22.5469 10.1755L6.2666 22.7058C5.66484 23.1686 4.78697 23.0746 4.30566 22.4958C3.82454 21.9169 3.92192 21.0722 4.52344 20.6091L20.8037 8.07886Z"
                  fill="#80011F" />
              </svg>
            </div>
            <span>Весь клубный дом и территория — только для вашей компании</span>
          </div>
          <div class="feature-icons__item">
            <div class="feature-icons__round">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
                <path
                  d="M5.80078 23.3125C10.7056 27.4733 16.8617 29.9544 23.3359 30.3799C29.8256 30.9159 36.3536 29.9657 42.4033 27.6035L43.4893 29.8799C42.6745 30.2072 41.8492 30.5057 41.0176 30.7822L42.9531 38.0986H42.9404C42.9982 38.3143 42.9965 38.542 42.9355 38.7568C42.8746 38.9715 42.7573 39.1665 42.5947 39.3203C42.432 39.4743 42.2297 39.5817 42.0107 39.6318C41.7919 39.6818 41.5636 39.6722 41.3496 39.6045L37.8535 38.4844L35.874 41.5703C35.7609 41.7455 35.6057 41.8902 35.4219 41.9902C35.2381 42.0902 35.0318 42.1422 34.8223 42.1426H34.6338C34.3961 42.1059 34.174 42.0024 33.9941 41.8438C33.8142 41.6849 33.6837 41.4775 33.6191 41.2471L31.3047 32.8145C30.568 32.8821 29.8293 32.9309 29.0889 32.9609L27.8057 41.9316C27.7738 42.1536 27.6818 42.3627 27.54 42.5371C27.3983 42.7115 27.2117 42.8451 27 42.9229C26.7883 43.0006 26.5589 43.0203 26.3369 42.9795C26.1149 42.9386 25.9079 42.8385 25.7383 42.6904L22.9941 40.2637L19.9248 42.292C19.7212 42.4266 19.4819 42.4996 19.2373 42.501C18.9928 42.5023 18.753 42.4321 18.5479 42.2998C18.3428 42.1675 18.1812 41.9783 18.083 41.7559C17.9849 41.5334 17.9541 41.2873 17.9951 41.0479L19.2725 32.3984C18.7469 32.3024 18.2249 32.1918 17.7061 32.0703L14.3115 39.3184C14.2175 39.5214 14.0699 39.6953 13.8848 39.8223C13.6996 39.9492 13.4833 40.0247 13.2588 40.04H13.1719C12.9622 40.0397 12.7552 39.9867 12.5713 39.8867C12.3877 39.7868 12.2322 39.6428 12.1191 39.4678L10.1523 36.3945L6.64355 37.5137C6.40715 37.5908 6.15262 37.5962 5.91309 37.5293C5.67388 37.4624 5.45996 37.3266 5.29883 37.1387C5.13754 36.9505 5.03602 36.7182 5.00781 36.4727C4.97966 36.2272 5.02623 35.9791 5.14062 35.7598L8.53027 28.3643C6.93321 27.4103 5.41582 26.3166 4 25.0889L5.80078 23.3125ZM44 6.77637C42.5909 7.99833 41.081 9.08728 39.4922 10.0381L42.8906 17.542C42.9895 17.7614 43.0217 18.0047 42.9844 18.2422C42.9469 18.4798 42.8413 18.7018 42.6797 18.8809C42.518 19.0599 42.3071 19.1893 42.0732 19.252C41.8396 19.3145 41.5926 19.3082 41.3623 19.2344L37.8535 18.1143L35.8867 21.1885C35.7736 21.3634 35.6181 21.5075 35.4346 21.6074C35.2507 21.7074 35.0436 21.7604 34.834 21.7607H34.7471C34.5226 21.7454 34.3062 21.6699 34.1211 21.543C33.936 21.416 33.7883 21.242 33.6943 21.0391L30.2803 13.7617C29.7625 13.8828 29.2413 13.9912 28.7168 14.0869L29.9854 22.793C30.0263 23.0325 29.9957 23.2794 29.8975 23.502C29.7992 23.7242 29.6376 23.9137 29.4326 24.0459C29.2275 24.1781 28.9877 24.2475 28.7432 24.2461C28.4986 24.2447 28.2593 24.1727 28.0557 24.0381L24.9863 22.0098L22.2422 24.4355C22.0727 24.5835 21.8664 24.6837 21.6445 24.7246C21.4225 24.7655 21.1932 24.7457 20.9814 24.668C20.7696 24.5902 20.5823 24.4567 20.4404 24.2822C20.2988 24.1078 20.2067 23.8986 20.1748 23.6768L18.9082 14.6377C18.1845 14.6089 17.4624 14.5619 16.7422 14.4971L14.3867 23.0176C14.3221 23.2479 14.1916 23.4545 14.0117 23.6133C13.8318 23.7721 13.6099 23.8764 13.3721 23.9131H13.1836C12.9741 23.9127 12.7677 23.8607 12.584 23.7607C12.4002 23.6608 12.245 23.516 12.1318 23.3408L10.1523 20.2549L6.65625 21.3369C6.44508 21.4044 6.21957 21.4155 6.00293 21.3682C5.78633 21.3208 5.58592 21.2167 5.42285 21.0674C5.26007 20.9182 5.1403 20.7288 5.0752 20.5186C5.01008 20.308 5.00215 20.0836 5.05273 19.8691L7.00977 12.4775C6.14707 12.192 5.2918 11.8821 4.44727 11.542L5.53223 9.27832C11.5837 11.6344 18.111 12.5843 24.6006 12.0547C31.0984 11.6588 37.2827 9.17991 42.1992 5L44 6.77637Z"
                  fill="#80011F" />
              </svg>
            </div>
            <span>Форматы отдыха под любое событие и количество дней</span>
          </div>
          <div class="feature-icons__item">
            <div class="feature-icons__round">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
                <path
                  d="M26.21 12.843C26.736 11.7195 28.2631 11.7195 28.7891 12.843L31.6523 19.8811C32.0165 20.7924 32.7045 21.534 33.5742 21.9368L37.1953 23.6438C38.2675 24.1949 38.2676 25.8056 37.1953 26.3567L33.5742 28.0637C32.7145 28.4665 32.0266 29.208 31.6523 30.1194L28.7891 37.1575C28.263 38.2809 26.736 38.2809 26.21 37.1575L23.3467 30.1194C22.9826 29.208 22.2946 28.4665 21.4248 28.0637L17.8037 26.3567C16.7316 25.8056 16.7317 24.195 17.8037 23.6438L21.4248 21.9368C22.2845 21.534 22.9724 20.7923 23.3467 19.8811L26.21 12.843ZM13.9473 25.887C14.1741 25.3981 14.826 25.3981 15.0527 25.887L16.2803 28.9836C16.4409 29.391 16.7342 29.7065 17.1025 29.8899L18.6523 30.6438C19.1152 30.8883 19.1152 31.5917 18.6523 31.8362L17.1025 32.5901C16.7341 32.7735 16.4409 33.0991 16.2803 33.4963L15.0527 36.593C14.826 37.0819 14.174 37.0819 13.9473 36.593L12.7188 33.4963C12.5581 33.0889 12.2649 32.7734 11.8965 32.5901L10.3467 31.8362C9.88374 31.5917 9.88371 30.8883 10.3467 30.6438L11.8965 29.8899C12.2649 29.7065 12.5581 29.3809 12.7188 28.9836L13.9473 25.887ZM16.0703 12.2678C16.2377 11.9111 16.7508 11.9111 16.9287 12.2678L17.8809 14.5129C17.9959 14.8066 18.2258 15.0481 18.5186 15.1741L19.7324 15.719C20.0881 15.8974 20.0881 16.4117 19.7324 16.5901L18.5186 17.136C18.2362 17.2724 18.0063 17.5035 17.8809 17.7971L16.9287 20.053C16.7612 20.4094 16.2483 20.4094 16.0703 20.053L15.1182 17.7971C15.0031 17.5035 14.7733 17.2619 14.4805 17.136L13.2666 16.5901C12.9108 16.4117 12.9108 15.8974 13.2666 15.719L14.4805 15.1741C14.7629 15.0377 14.9926 14.8066 15.1182 14.5129L16.0703 12.2678Z"
                  fill="#80011F" />
              </svg>
            </div>
            <span>Комфорт, кухня, фурако, живые концерты и продуманные детали</span>
          </div>
          <div class="feature-icons__item">
            <div class="feature-icons__round">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
                <path
                  d="M31.4248 23.7783C31.5267 23.7561 31.5489 23.8351 31.5947 23.8975L41.8447 41.6387C42.2583 42.4781 41.8269 42.9964 40.7959 42.998C38.0389 43.004 34.8721 42.7841 32.0811 42.998L31.7842 42.9258L23.3516 27.5781L23.375 27.4131L25.1582 25.6348C26.0566 26.1891 26.7782 27.9245 28.04 27.1279L31.4248 23.7773V23.7783ZM23.5283 30.6045L30.2441 42.9971H5.93164V42.9951C5.1651 42.9949 4.79033 42.2051 5.11816 41.584L11.1719 30.6025C11.2661 30.5791 11.2974 30.6679 11.3564 30.7139C12.373 31.5134 13.3287 32.9613 14.3438 33.7207C15.635 34.6866 16.4123 33.009 17.3047 32.3164C18.2902 32.9525 18.9911 34.7471 20.3594 33.6846C21.3463 32.9191 22.3717 31.3543 23.3438 30.6768C23.4056 30.6337 23.4161 30.5733 23.5283 30.6045ZM17.0342 21.6631C17.5631 21.5561 17.9623 21.671 18.2061 22.0781L22.2646 29.4316L22.2061 29.6377L19.5713 32.2637C18.6331 31.6691 17.9121 29.9029 16.5986 30.8271C16.3386 31.01 15.2422 32.2887 15.0918 32.2637L12.3975 29.5459C12.362 29.4092 12.5297 29.1606 12.5977 29.0283C13.8151 26.6299 15.2598 24.3083 16.5674 21.9648C16.6693 21.7883 16.9394 21.6816 17.0342 21.6621V21.6631ZM24.3115 15.4014C24.5982 14.8545 25.6726 14.8229 25.9902 15.3223L30.2646 22.7246L27.4268 25.5869C26.4649 24.9717 25.7582 23.1929 24.4121 24.1826C23.7 24.7058 22.9757 25.8485 22.2725 26.3359C22.2106 26.379 22.2001 26.4394 22.0879 26.4082L20.1484 22.8242L20.126 22.625L24.3115 15.4014ZM35.4951 6.02148C39.4781 5.65956 42.3643 9.98292 40.3311 13.5078C38.5683 16.5618 34.2788 16.8614 32.0664 14.1152C29.6219 11.0811 31.6377 6.37308 35.4961 6.02148H35.4951Z"
                  fill="#80011F" />
              </svg>
            </div>
            <span>Чистый воздух, горы, конные прогулки и тёплые вечера у камина</span>
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
    <section class="features space-xxxl" data-reveal="fade">
      <div class="container">
        <div class="features__wrap">
          <div class="features__content">
            <div class="features__content-top">
              <p class="heading-md space-md">Почему выбирают нас</p>
              <p class="text-color-secondary space-sm">В клубном доме «Байрам» вы получаете не просто место для отдыха,
                а целый мир, где всё создано для вашей компании. Мы берём на себя организацию досуга и заботимся о
                каждой детали — от удобства размещения до гастрономических впечатлений. Здесь вас ждут природа без
                посторонних, персональный подход и атмосфера, в которую хочется вернуться</p>
              <a href="about.php" class="button-texted buttons-lg-medium">
                Подробнее о компании
                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="18" viewBox="0 0 11 18" fill="none">
                  <path
                    d="M0.293335 0.292725C0.683882 -0.0975224 1.31697 -0.0977072 1.7074 0.292725L10.4144 8.99976L1.7074 17.7068C1.31692 18.0973 0.68387 18.0972 0.293335 17.7068C-0.0971891 17.3163 -0.0971893 16.6832 0.293335 16.2927L7.5863 8.99976L0.293335 1.70679C-0.0971893 1.31626 -0.0971893 0.683249 0.293335 0.292725Z"
                    fill="#363636"></path>
                </svg>
              </a>
            </div>
            <div class="features__grid">
              <div class="features__grid-item">
                <div class="features__group">
                  <span class="heading-lg-accent color-accent">100%</span>
                  <p class="text-color-secondary">Гарантия комфортного отдыха в уединении</p>
                </div>
              </div>
              <div class="features__grid-item">
                <div class="features__group">
                  <span class="heading-lg-accent color-accent">10</span>
                  <p class="text-color-secondary">Более 10 лет принимаем гостей</p>
                </div>
              </div>
              <div class="features__grid-item">
                <div class="features__group">
                  <span class="heading-lg-accent color-accent features__figure">
                    0
                    <span class="heading-sm-accent">(ноль)</span>
                  </span>
                  <p class="text-color-secondary">недовольных клиентов</p>
                </div>
              </div>
              <div class="features__grid-item">
                <div class="features__group">
                  <span class="heading-lg-accent color-accent">5</span>
                  <p class="text-color-secondary">баллов средняя оценка на Яндекс.Картах</p>
                </div>
              </div>
            </div>
          </div>
          <div class="features__pic">
            <div class="features__banner">
              <p class="features__banner-text">
                <span class="heading-lg-accent color-white">>178</span>
                <span class="color-white">довольных <br> гостей</span>
              </p>
            </div>
            <img src="assets/img/features.png" alt="">
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
  <script type="text/javascript" src="assets/js/link-fix.js"></script>
  <script type="text/javascript" src="assets/js/validate-form.js"></script>
  <script type="text/javascript" src="assets/js/reveal-on-scroll.js"></script>
  <script type="text/javascript" src="assets/js/tours-stagger.js"></script>
  <script type="text/javascript" src="assets/js/feature-icons-stagger.js"></script>
  <script type="text/javascript" src="assets/js/smooth-scroll.js"></script>
  <script type="text/javascript" src="assets/js/dropdown-menu.js"></script>
  <script type="text/javascript" src="assets/js/hero-reveal.js"></script>
  <script type="text/javascript" src="assets/js/video-hero-reveal.js"></script>
  <script type="text/javascript" src="assets/js/bundles-loader.js?v=1761428986"></script>
</body>

</html>
