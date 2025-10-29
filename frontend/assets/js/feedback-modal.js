'use strict';

class FeedbackModal {
    constructor() {
        this.modal = null;
        this.form = null;
        this.submitBtn = null;
        this.successDiv = null;
        this.apiUrl = 'http://94.228.124.202:8080/api/feedback';
        
        this.init();
    }

    init() {
        // Инициализируем GraphModal
        this.modal = new GraphModal({
            isOpen: () => {
                this.onModalOpen();
            },
            isClose: () => {
                this.onModalClose();
            }
        });

        // Находим форму и элементы
        this.form = document.getElementById('feedbackForm');
        this.submitBtn = document.getElementById('submitBtn');
        this.successDiv = document.getElementById('feedbackSuccess');

        if (this.form) {
            this.bindEvents();
        }
    }

    bindEvents() {
        // Обработчик отправки формы
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleSubmit();
        });

        // Обработчики для кнопок "Перезвоните мне" и "Выбрать дату"
        this.bindButtonEvents();
    }

    bindButtonEvents() {
        // Находим все кнопки с атрибутом data-feedback-trigger
        const triggerButtons = document.querySelectorAll('[data-feedback-trigger]');
        triggerButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.openModal();
            });
        });

        // Обрабатываем кнопку "Оставить заявку" с data-graph-path="consult"
        const consultButtons = document.querySelectorAll('[data-graph-path="consult"]');
        consultButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.openModal();
            });
        });

        // Также обрабатываем кнопки по тексту для обратной совместимости
        const allButtons = document.querySelectorAll('a, button');
        allButtons.forEach(button => {
            const text = button.textContent.trim();
            if (text === 'Выбрать дату' || text === 'Перезвоните мне' || text === 'Оставить заявку') {
                // Проверяем, что у кнопки еще нет обработчика
                if (!button.hasAttribute('data-feedback-trigger') && !button.hasAttribute('data-graph-path')) {
                    button.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.openModal();
                    });
                }
            }
        });
    }

    openModal() {
        this.modal.open('feedbackModal');
    }

    closeModal() {
        this.modal.close();
    }

    onModalOpen() {
        // Сброс формы при открытии
        this.resetForm();
        // Фокус на первое поле
        const firstInput = this.form.querySelector('input[type="text"]');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
    }

    onModalClose() {
        // Сброс формы при закрытии
        this.resetForm();
    }

    resetForm() {
        this.form.reset();
        this.hideErrors();
        this.showForm();
        this.setSubmitButtonState(false);
    }

    showForm() {
        this.form.style.display = 'block';
        this.successDiv.style.display = 'none';
    }

    showSuccess() {
        this.form.style.display = 'none';
        this.successDiv.style.display = 'block';
    }

    setSubmitButtonState(loading) {
        const submitText = this.submitBtn.querySelector('.submit-text');
        const submitLoading = this.submitBtn.querySelector('.submit-loading');
        
        if (loading) {
            this.submitBtn.disabled = true;
            submitText.style.display = 'none';
            submitLoading.style.display = 'flex';
        } else {
            this.submitBtn.disabled = false;
            submitText.style.display = 'inline';
            submitLoading.style.display = 'none';
        }
    }

    showError(fieldName, message) {
        const errorElement = document.getElementById(`error${fieldName.charAt(0).toUpperCase() + fieldName.slice(1)}`);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.add('show');
        }
    }

    hideErrors() {
        const errorElements = document.querySelectorAll('.feedback-form__error');
        errorElements.forEach(error => {
            error.textContent = '';
            error.classList.remove('show');
        });
    }

    validateForm() {
        this.hideErrors();
        let isValid = true;

        // Проверка имени
        const name = this.form.name.value.trim();
        if (!name) {
            this.showError('name', 'Поле "Имя" обязательно для заполнения');
            isValid = false;
        }

        // Проверка телефона
        const phone = this.form.phone.value.trim();
        if (!phone) {
            this.showError('phone', 'Поле "Телефон" обязательно для заполнения');
            isValid = false;
        }

        // Проверка email
        const email = this.form.email.value.trim();
        if (!email) {
            this.showError('email', 'Поле "Email" обязательно для заполнения');
            isValid = false;
        } else if (!this.isValidEmail(email)) {
            this.showError('email', 'Некорректный формат email');
            isValid = false;
        }

        // Проверка согласия на обработку данных
        if (!this.form.privacy_agreement.checked) {
            this.showError('privacy', 'Необходимо согласие на обработку персональных данных');
            isValid = false;
        }

        return isValid;
    }

    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    async handleSubmit() {
        if (!this.validateForm()) {
            return;
        }

        this.setSubmitButtonState(true);

        try {
            const formData = new FormData(this.form);
            const data = Object.fromEntries(formData);

            const response = await fetch(this.apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess();
            } else {
                this.handleError(result.message || 'Произошла ошибка при отправке формы');
            }

        } catch (error) {
            console.error('Ошибка отправки формы:', error);
            this.handleError('Произошла ошибка при отправке формы. Проверьте подключение к интернету.');
        } finally {
            this.setSubmitButtonState(false);
        }
    }

    handleError(message) {
        // Показываем общую ошибку
        const submitBtn = this.submitBtn;
        const originalText = submitBtn.querySelector('.submit-text').textContent;
        
        submitBtn.querySelector('.submit-text').textContent = message;
        submitBtn.style.background = '#dc3545';
        
        setTimeout(() => {
            submitBtn.querySelector('.submit-text').textContent = originalText;
            submitBtn.style.background = '';
        }, 3000);
    }
}

// Функция для закрытия модального окна (вызывается из HTML)
function closeFeedbackModal() {
    if (window.feedbackModal) {
        window.feedbackModal.closeModal();
    }
}

// Инициализация при загрузке DOM
document.addEventListener('DOMContentLoaded', () => {
    window.feedbackModal = new FeedbackModal();
});
