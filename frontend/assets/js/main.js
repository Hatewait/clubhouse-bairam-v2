'use strict'

const header = document.querySelector('[data-header]');

const addFixedHeaderClass = () => {
  if (window.scrollY > 1) {
    header.classList.add('fixed');
  } else {
    header.classList.remove('fixed');
  }
}

window.addEventListener("scroll", addFixedHeaderClass);

/* initialising accordions */
const accordionFaq = document.querySelector('#accordion-1');

if (accordionFaq) {
  new ItcAccordion('#accordion-1');
}

Fancybox.bind('[data-fancybox]', {
  Thumbs: false
});

// плавающий лейбл
const setFocus = (el, state) => {
  const field = el.closest('.date-field');
  if (field) field.classList.toggle('is-focused', !!state);
};
const setFilled = (el) => {
  const field = el.closest('.date-field');
  if (field) field.classList.toggle('is-filled', !!el.value.trim());
};

