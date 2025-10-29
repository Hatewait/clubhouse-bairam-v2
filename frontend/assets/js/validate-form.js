'use strict'

// modals init
const validatorConsult = new window.JustValidate('[data-consult-form]');
const validatorCall = new window.JustValidate('[data-call-form]');

const clearFields = (form) => form.refresh();

const modal = new GraphModal({
  isOpen: (modal) => {},
  isClose: () => {
    const form = modal.modalContainer.querySelector('[data-form-reset]');
    if (form) {
      form.reset();
      clearFields(validatorConsult);
      clearFields(validatorCall)
    }
  }
});

const selector = document.querySelectorAll("input[type='tel']");
const im = new Inputmask("+7 (999)-999-99-99");
selector.forEach(input => im.mask(input))
//im.mask(selector);

// Добавляем отладочный обработчик для кнопки
document.addEventListener('DOMContentLoaded', () => {
  const submitButton = document.querySelector('[data-consult-form] .form__button');
  if (submitButton) {
    submitButton.addEventListener('click', (e) => {
      console.log('Submit button clicked!');
    });
  }
});

validatorConsult
    .addField('[data-input-name]', [
      {
        rule: 'required',
        errorMessage: 'Введите имя!',
      },
      {
        rule: 'minLength',
        value: 3,
        errorMessage: 'Введите минимум 2 буквы',
      },
      {
        rule: 'maxLength',
        value: 15,
      },
    ])
    .addField('[data-input-email]', [
      {
        rule: 'required',
        errorMessage: 'Введите корректный email!'
      },
      {
        rule: 'email',
        errorMessage: 'Введите корректный email!'
      },
    ])
    .addField('[data-input-tel]', [
      {
        rule: 'minLength',
        value: 10,
        errorMessage: 'Введите корректный телефон',
      },
      {
        validator: (value) => {
          const phoneInput = document.querySelector('[data-input-tel]');
          if (!phoneInput || !phoneInput.inputmask) return false;
          const phone = phoneInput.inputmask.unmaskedvalue();
          return Number(phone) && phone.length === 10;
        },
        errorMessage: 'Номер должен содержать 10 цифр',
      }
    ])
    .onFail((fields) => {
      console.log('Form validation failed');
    })
    .onSuccess(async (event) => {
      console.log('Form validation success, submitting to API...')
      
      try {
        const form = event.currentTarget;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);

        const response = await fetch('http://94.228.124.202:8080/api/booking', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
          console.log('Booking submitted successfully');
          // Закрываем текущее модальное окно
          modal.close();
          // Открываем модальное окно успеха
          new GraphModal().open('success-consult');
        } else {
          console.error('Booking submission failed:', result.message);
          alert(result.message || 'Произошла ошибка при отправке формы');
        }

      } catch (error) {
        console.error('Error submitting booking:', error);
        alert('Произошла ошибка при отправке формы. Проверьте подключение к интернету.');
      }
    });

validatorCall
    .addField('[data-input-name]', [
      {
        rule: 'required',
        errorMessage: 'Введите имя!',
      },
      {
        rule: 'minLength',
        value: 3,
        errorMessage: 'Введите минимум 2 буквы',
      },
      {
        rule: 'maxLength',
        value: 15,
      },
    ])
    .addField('[data-input-email]', [
      {
        rule: 'required',
        errorMessage: 'Введите корректный email!'
      },
      {
        rule: 'email',
        errorMessage: 'Введите корректный email!'
      },
    ])
    .onSuccess((event) => {
      //event.currentTarget.submit();
      const currentModal = modal.modal.querySelector('[data-graph-target="call"]');
      modal.modalContainer.classList.remove('graph-modal-open', 'fade', 'animate-open') // закрывает модалку с формой
      new GraphModal().open('success-consult'); // образец вызова модалки по событию
    });
