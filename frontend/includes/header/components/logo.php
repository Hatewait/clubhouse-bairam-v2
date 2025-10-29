<?php
/**
 * Компонент логотипа
 * Простой HTML с минимальным PHP
 */
$logo_href = ($active === 'index') ? '/' : 'index.php';
$logo_config = $config['logo'];
?>

<a href="<?= htmlspecialchars($logo_href) ?>" class="header__logo-wrap">
  <img src="<?= htmlspecialchars($logo_config['src']) ?>" alt="<?= htmlspecialchars($logo_config['alt']) ?>">
</a>