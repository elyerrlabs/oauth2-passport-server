<style nonce="{{ $nonce }}">
    .privacy-modal {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 50;
    }

    .privacy-modal.active {
        display: block;
    }

    .privacy-modal__container {
        background: white;
        width: 100%;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
        border-top: 1px solid #e5e7eb;
    }

    .dark .privacy-modal__container {
        background: #1f2937;
        border-top-color: #374151;
    }

    .privacy-modal__content {
        max-width: 80rem;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .privacy-modal__text {
        color: #4b5563;
        font-size: 0.875rem;
        line-height: 1.5;
        margin: 0;
        flex: 1;
    }

    .dark .privacy-modal__text {
        color: #9ca3af;
    }

    .privacy-modal__button {
        background: #2563eb;
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
    }

    .privacy-modal__button:hover {
        background: #1d4ed8;
    }

    .privacy-modal__button--cancel {
        background: #6b7280;
    }

    .privacy-modal__button--cancel:hover {
        background: #4b5563;
    }

    .privacy-modal__details {
        margin-top: 0.75rem;
        font-size: 0.75rem;
        color: #6b7280;
    }

    .dark .privacy-modal__details {
        color: #6b7280;
    }

    .privacy-modal__actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    @media (max-width: 640px) {
        .privacy-modal__content {
            flex-direction: column;
            text-align: center;
        }

        .privacy-modal__actions {
            width: 100%;
            flex-direction: column;
        }

        .privacy-modal__button {
            width: 100%;
        }
    }
</style>

<div id="privacyModal" class="privacy-modal">
    <div class="privacy-modal__container">
        <div class="privacy-modal__content">
            <div style="flex: 1;">
                <p class="privacy-modal__text">
                    <strong>{{ __('We use essential cookies for the platform to function properly:') }}</strong>
                </p>
                <p class="privacy-modal__text" style="margin-top: 0.5rem;">
                    • <strong>name_csrf</strong> —
                    {{ __('Protects your session against cross-site request forgery attacks. This cookie is required for all form submissions and API requests.') }}
                </p>
                <p class="privacy-modal__text">
                    • <strong>name_session</strong> —
                    {{ __('Maintains your authenticated session while you navigate the platform. Without it, you would need to log in on every page.') }}
                </p>
                <p class="privacy-modal__text">
                    • <strong>name_server</strong> —
                    {{ __('Essential for API communication between the frontend and Laravel Passport. Enables secure OAuth2 authentication for your own APIs.') }}
                </p>
                <p class="privacy-modal__text" style="margin-top: 0.75rem;">
                    {{ __('No personal data is collected, shared, or sold. These cookies are strictly necessary and cannot be disabled.') }}
                </p>
                <div class="privacy-modal__details">
                    <span>{{ __('By continuing, you accept the use of these essential cookies.') }}</span>
                </div>
            </div>
            <div class="privacy-modal__actions">
                <button id="privacyCancel" class="privacy-modal__button privacy-modal__button--cancel">
                    {{ __('Cancel') }}
                </button>
                <button id="privacyAccept" class="privacy-modal__button">
                    {{ __('Accept') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $nonce }}">
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('privacyModal');
        const acceptBtn = document.getElementById('privacyAccept');
        const cancelBtn = document.getElementById('privacyCancel');

        if (!localStorage.getItem('privacyAccepted')) {
            setTimeout(() => {
                modal.classList.add('active');
            }, 1000);
        }

        acceptBtn.addEventListener('click', function() {
            modal.classList.remove('active');
            localStorage.setItem('privacyAccepted', 'true');
        });

        cancelBtn.addEventListener('click', function() {
            window.location.href = 'https://www.google.com';
        });
    });
</script>