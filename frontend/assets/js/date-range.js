'use strict';

(() => {
  if (typeof flatpickr === 'undefined' || typeof window.rangePlugin === 'undefined') return;

  const fromEl = document.getElementById('date-from');
  const toEl   = document.getElementById('date-to');
  if (!fromEl || !toEl) return;


  // --- ограничения диапазона (статично) ---
  const MIN_NIGHTS = 2;
  const MAX_NIGHTS = 5;
  const MS_DAY = 86400000;
  const diffDaysUTC = (a, b) =>
      Math.round(
          (Date.UTC(b.getFullYear(), b.getMonth(), b.getDate()) -
              Date.UTC(a.getFullYear(), a.getMonth(), a.getDate())) / MS_DAY
      );
  const addDays = (d, days) => new Date(d.getFullYear(), d.getMonth(), d.getDate() + days);

  // формат d.m.Y
  const fmt = (d) => `${String(d.getDate()).padStart(2,'0')}.${String(d.getMonth()+1).padStart(2,'0')}.${d.getFullYear()}`;

  // локаль
  const ru = window.flatpickr?.l10ns?.ru;

  const fp = flatpickr(fromEl, {
    // ВАЖНО: режим диапазона включён
    mode: 'range',
    dateFormat: 'd.m.Y',
    rangeSeparator: ' — ',
    allowInput: false,
    clickOpens: true,
    disableMobile: true,
    locale: ru ?? undefined,
    minDate: 'today',

    // шапка
    monthSelectorType: 'static',
    prevArrow:
        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">' +
        '  <path d="M8.76758 3.43475C9.08 3.12233 9.58602 3.12233 9.89844 3.43475C10.2108 3.74718 10.2108 4.25321 9.89844 4.56561L6.46387 8.00018L9.89844 11.4348C10.2108 11.7472 10.2108 12.2532 9.89844 12.5656C9.58602 12.8779 9.07995 12.8779 8.76758 12.5656L4.76758 8.56561L4.20117 8.00018L4.76758 7.43475L8.76758 3.43475Z" fill="#363636"/>' +
        '</svg>',
    nextArrow:
        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">' +
        '  <path d="M5.43457 3.43478C5.74699 3.1224 6.25302 3.12238 6.56543 3.43478L10.5654 7.43478L11.1318 8.00021L10.5654 8.56564L6.56543 12.5656C6.25309 12.878 5.74701 12.8778 5.43457 12.5656C5.12215 12.2532 5.12215 11.7472 5.43457 11.4348L8.86914 8.00021L5.43457 4.56564C5.12215 4.25322 5.12215 3.7472 5.43457 3.43478Z" fill="#363636"/>' +
        '</svg>',
    showMonths: 1,

    // второй инпут ведёт плагин
    plugins: [ new window.rangePlugin({ input: '#date-to' }) ],

    // выходные
    onDayCreate: (_sel, _str, _inst, dayElem) => {
      const d = dayElem.dateObj; if (!d) return;
      const wd = d.getDay();
      if (wd === 0 || wd === 6) dayElem.classList.add('is-weekend');
    },

    // показываем плейсхолдеры/лейблы
    onOpen: () => {
      const target = (document.activeElement === toEl) ? toEl : fromEl;
      setFocus(target, true);
    },
    onClose: () => {
      setFocus(fromEl, false);
      setFocus(toEl, false);
    },

    // 1) Жёстко соблюдаем 2–5 ночей,
    // 2) всегда пишем в инпуты "только заезд" и "только выезд"
    onChange: function(selectedDates) {
      if (selectedDates.length === 2) {
        let [start, end] = selectedDates;
        // flatpickr гарантирует порядок, но на всякий проверим
        if (end < start) [start, end] = [end, start];

        let nights = diffDaysUTC(start, end);

        if (nights < MIN_NIGHTS) {
          end = addDays(start, MIN_NIGHTS);
          this.setDate([start, end], true);
        } else if (nights > MAX_NIGHTS) {
          end = addDays(start, MAX_NIGHTS);
          this.setDate([start, end], true);
        }
      }

      // нормализация значений инпутов
      const [s, e] = this.selectedDates;
      fromEl.value = s ? fmt(s) : '';
      toEl.value   = e ? fmt(e) : '';
      setFilled(fromEl);
      setFilled(toEl);
    },

    // на готовности привести инпуты к норме (чтобы в первом не было "склейки")
    onValueUpdate: function() {
      const [s, e] = this.selectedDates;
      fromEl.value = s ? fmt(s) : '';
      toEl.value   = e ? fmt(e) : '';
      setFilled(fromEl);
      setFilled(toEl);
    },

    onReady: function () {
      const [s, e] = this.selectedDates;
      fromEl.value = s ? fmt(s) : '';
      toEl.value   = e ? fmt(e) : '';
      setFilled(fromEl);
      setFilled(toEl);
    }
  });

  // плавающий лейбл и запрет ручного ввода
  fromEl.addEventListener('focus', () => setFocus(fromEl, true));
  fromEl.addEventListener('blur',  () => setFocus(fromEl, false));
  toEl.addEventListener('focus',   () => setFocus(toEl, true));
  toEl.addEventListener('blur',    () => setFocus(toEl, false));

  const preventTyping = (e) => e.preventDefault();
  fromEl.addEventListener('keydown', preventTyping);
  toEl.addEventListener('keydown',   preventTyping);
})();
