<?php
/**
 * Компонент героя (видео/изображение + контент)
 * Обрабатывает медиа-контент и текст
 */
if (!isset($media) || $media === null) {
  return;
}

$content = $content ?? [];
$is_image = $media['type'] === 'image';
$is_video = $media['type'] === 'video';
?>

<div class="header__video-wrap">
  <?php if ($is_video): ?>
    <?php
    $video_attrs = '';
    if ($media['autoplay'] ?? false)
      $video_attrs .= ' autoplay=""';
    if ($media['muted'] ?? false)
      $video_attrs .= ' muted=""';
    if ($media['loop'] ?? false)
      $video_attrs .= ' loop=""';
    if ($media['playsinline'] ?? false)
      $video_attrs .= ' playsinline=""';
    if (isset($media['poster']))
      $video_attrs .= ' poster="' . htmlspecialchars($media['poster']) . '"';
    ?>
    <video<?= $video_attrs ?> class="header__video">
      <source src="<?= htmlspecialchars($media['src']) ?>" type="video/mp4">
      </video>
    <?php endif; ?>

    <div class="container header__inner-container header__container">
      <div class="header__info">
        <div class="header__video-wrap-text">

          <?php if (isset($content['title'])): ?>
            <?php
            $title_class = 'heading-wrap heading-wrap_header heading-lg color-white text-center';
            if ($is_image || $is_video) {
              $title_class .= ' js-hero-item';
            }
            ?>
            <h1 class="<?= $title_class ?>">
              <?= $content['title'] ?>
            </h1>
          <?php endif; ?>

          <?php if (isset($content['subtitle'])): ?>
            <?php
            $subtitle_class = 'subtitle-md color-white text-center';
            if ($is_image || $is_video) {
              $subtitle_class .= ' js-hero-item';
            }
            ?>
            <p class="<?= $subtitle_class ?>">
              <?= htmlspecialchars($content['subtitle']) ?>
            </p>
          <?php endif; ?>

        </div>

        <?php if (isset($content['button'])): ?>
          <?php
          $button = $content['button'];
          $button_text = htmlspecialchars($button['text']);
          $button_data = isset($button['data']) ? ' ' . $button['data'] : '';
          $is_not_found_page = $is_not_found_page ?? false;

          $button_class = 'button-filled color-white buttons-lg-medium';
          if ($is_image || $is_video) {
            $button_class .= ' js-hero-item';
          }
          ?>

          <?php if ($is_not_found_page): ?>
            <?php $button_href = $button['href'] ?? '/'; ?>
            <a href="<?= htmlspecialchars($button_href) ?>" class="<?= $button_class ?>" <?= $button_data ?>
              aria-label="консультация">
              <?= $button_text ?>
            </a>
          <?php else: ?>
            <button class="<?= $button_class ?>" <?= $button_data ?> aria-label="консультация">
              <?= $button_text ?>
            </button>
          <?php endif; ?>
        <?php endif; ?>

      </div>
    </div>
</div>