<?php
/**
 * Компонент контактов
 * Простой HTML с данными из конфигурации
 */
$contacts_config = $config['contacts'];
?>

<div class="header__contacts" data-da=".js-insert-contacts,1023">
  <a href="<?= htmlspecialchars($contacts_config['phone_href']) ?>"
    class="text-md text-color-primary-inverse link-decor">
    <?= htmlspecialchars($contacts_config['phone']) ?>
  </a>
  <button class="color-white link-md-medium dashed btn-reset link-decor" data-graph-path="call">
    <?= htmlspecialchars($contacts_config['callback_text']) ?>
  </button>
</div>
