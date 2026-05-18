document.addEventListener('DOMContentLoaded', () => {
    window.setTimeout(() => {
        document.querySelectorAll('.alert').forEach((alertElement) => {
            if (alertElement.classList.contains('show')) {
                const closeButton = alertElement.querySelector('.btn-close');
                if (closeButton) {
                    closeButton.click();
                }
            }
        });
    }, 5000);

    document.querySelectorAll('[data-confirm]').forEach((element) => {
        element.addEventListener('click', (event) => {
            const message = element.getAttribute('data-confirm') || '¿Seguro que quieres continuar?';
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('form').forEach((form) => {
        form.addEventListener('submit', () => {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton && !submitButton.disabled) {
                submitButton.disabled = true;
                submitButton.dataset.originalText = submitButton.innerHTML;
                submitButton.innerHTML = 'Procesando...';
                window.setTimeout(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = submitButton.dataset.originalText || 'Enviar';
                }, 2500);
            }
        });
    });
});
