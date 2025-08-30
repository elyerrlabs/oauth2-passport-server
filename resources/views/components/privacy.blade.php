<style nonce="{{ $nonce }}">
    .privacy-modal {
        display: none;
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 50;
        align-items: center;
        justify-content: center;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    .privacy-modal.active {
        display: flex;
        animation: fadeIn 0.3s ease-out;
    }

    .privacy-modal__container {
        background-color: white;
        border-radius: 0.75rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        max-width: 32rem;
        width: 90%;
        padding: 1.5rem;
        position: relative;
        max-height: 90vh;
        overflow-y: auto;
    }

    .privacy-modal__title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1a202c;
    }

    .privacy-modal__content {
        margin-bottom: 1.5rem;
    }

    .privacy-modal__text {
        color: #4a5568;
        margin-bottom: 1rem;
        line-height: 1.6;
    }

    .privacy-modal__text:last-of-type {
        margin-bottom: 0;
    }

    .privacy-modal__actions {
        text-align: right;
    }

    .privacy-modal__button {
        background-color: #2563eb;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: background-color 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .privacy-modal__button:hover {
        background-color: #1d4ed8;
    }

    .privacy-modal__button:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @media (max-width: 640px) {
        .privacy-modal__container {
            width: 95%;
            padding: 1rem;
            margin: 1rem;
        }

        .privacy-modal__title {
            font-size: 1.25rem;
        }
    }
</style>

<div id="privacyModal" class="privacy-modal">
    <div class="privacy-modal__container">
        <h2 class="privacy-modal__title">{{ __('Privacy & Cookies') }}</h2>

        <div class="privacy-modal__content">
            <p class="privacy-modal__text">
                {{ __('We take your privacy seriously. This website does not collect personal information from visitors. We only use strictly necessary cookies to ensure the proper functioning of the site, such as keeping your session active or remembering basic display preferences.') }}
            </p>
            <p class="privacy-modal__text">
                {{ __('We do not share, sell, or store personal information. All browsing-related information is kept anonymous and is only used to ensure the website functions correctly.') }}
            </p>
            <p class="privacy-modal__text">
                {{ __('By continuing to browse the site, you agree to the use of these necessary cookies. You can close this window to continue using the site without worrying about personal data collection.') }}
            </p>
        </div>

        <div class="privacy-modal__actions">
            <button id="privacyAccept" class="privacy-modal__button">
                {{ __('Accept') }}
            </button>
        </div>
    </div>
</div>

<script nonce="{{ $nonce }}">
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('privacyModal');
        const btnAccept = document.getElementById('privacyAccept');

        function openModal() {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
            localStorage.setItem('privacyAccepted', 'true');
        }


        if (!localStorage.getItem('privacyAccepted')) {
            setTimeout(openModal, 1000);
        }

        btnAccept.addEventListener('click', closeModal);

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Cerrar con la tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });
    });
</script>
