'use strict';

(() => {
  const ROOT_SEL   = '[data-select]';
  const INPUT_SEL  = '[data-input]';
  const OPTION_SEL = '.option';

  const updateFilledState = (root) => {
    const input = root.querySelector(INPUT_SEL);
    const hasValue = !!(input && input.value.trim());
    root.classList.toggle('is-filled', hasValue);
  };

  const open  = (root) => {
    // Закрываем другие
    document.querySelectorAll(`${ROOT_SEL}.opened`).forEach(dd => {
      if (dd !== root) dd.classList.remove('opened');
    });
    root.classList.add('opened');
  };
  const close = (root) => root.classList.remove('opened');
  const toggle = (root) => root.classList.contains('opened') ? close(root) : open(root);

  const onRootClick = (e) => {
    const root = e.currentTarget;
    e.stopPropagation();

    // Клик по опции обрабатывается отдельным слушателем
    if (e.target.closest(OPTION_SEL)) return;
    // Клик внутри меню (скроллбар и т.п.) не должен закрывать/открывать
    if (e.target.closest('[data-menu]')) return;

    toggle(root);
  };

  const onOptionClick = (e) => {
    e.stopPropagation();
    const opt  = e.currentTarget;
    const root = opt.closest(ROOT_SEL);
    const input = root.querySelector(INPUT_SEL);

    input.value = opt.textContent.trim();
    updateFilledState(root);
    close(root);
  };

  const init = (root) => {
    const input = root.querySelector(INPUT_SEL);

    // Делегирование не обязательно — повесим на каждую опцию внутри конкретного дропа
    root.querySelectorAll(OPTION_SEL).forEach(opt => {
      opt.addEventListener('click', onOptionClick);
    });

    root.addEventListener('click', onRootClick);

    // Инициализируем состояние (если значение уже было проставлено)
    updateFilledState(root);

    // Поддержка reset формы
    const form = root.closest('form');
    if (form) {
      form.addEventListener('reset', () => {
        setTimeout(() => {   // даём форме очиститься
          if (input) input.value = '';
          close(root);
          updateFilledState(root);
        }, 0);
      });
    }

    // Если значение меняют программно
    if (input) input.addEventListener('input', () => updateFilledState(root));
  };

  // Клик вне любого селекта — закрыть открытые
  document.addEventListener('click', (e) => {
    document.querySelectorAll(`${ROOT_SEL}.opened`).forEach(root => {
      if (!root.contains(e.target)) close(root);
    });
  });

  // Инициализация всех селектов на странице
  document.querySelectorAll(ROOT_SEL).forEach(init);
})();
