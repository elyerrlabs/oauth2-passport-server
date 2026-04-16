<style nonce="{{ $nonce }}">
    .privacy-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.45), rgba(15, 23, 42, 0.12) 45%, rgba(15, 23, 42, 0));
        z-index: 50;
        align-items: flex-end;
        justify-content: stretch;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    .privacy-modal.active {
        display: flex;
        animation: fadeIn 0.3s ease-out;
    }

    .privacy-modal__container {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 1.25rem 1.25rem 0 0;
        box-shadow: 0 -18px 45px rgba(15, 23, 42, 0.18);
        border-top: 1px solid rgba(148, 163, 184, 0.28);
        width: 100%;
        padding: 1.5rem 2rem;
        position: relative;
        max-height: min(24rem, 82vh);
        overflow-y: auto;
        animation: slideUp 0.35s ease-out;
    }

    .privacy-modal__container::before {
        content: '';
        display: block;
        width: 3.5rem;
        height: 0.3rem;
        border-radius: 9999px;
        background-color: #cbd5e1;
        margin: 0 auto 1.25rem;
    }

    .privacy-modal__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.35rem 0.7rem;
        margin-bottom: 0.85rem;
        border-radius: 9999px;
        background-color: #e2e8f0;
        color: #334155;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .privacy-modal__eyebrow::before {
        content: '';
        width: 0.55rem;
        height: 0.55rem;
        border-radius: 9999px;
        background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
    }

    .privacy-modal__layout {
        max-width: 80rem;
        margin: 0 auto;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1.5rem;
    }

    .privacy-modal__body {
        flex: 1 1 auto;
        min-width: 0;
    }

    .privacy-modal__title {
        font-size: 1.375rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: #0f172a;
        letter-spacing: -0.02em;
    }

    .privacy-modal__content {
        display: grid;
        gap: 0.65rem;
    }

    .privacy-modal__text {
        color: #475569;
        margin: 0;
        line-height: 1.65;
        font-size: 0.98rem;
    }

    .privacy-modal__highlight {
        color: #0f172a;
        font-weight: 600;
    }

    .privacy-modal__actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex: 0 0 auto;
    }

    .privacy-modal__button {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
        color: white;
        padding: 0.9rem 1.4rem;
        border-radius: 0.8rem;
        font-weight: 600;
        transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        border: none;
        cursor: pointer;
        min-width: 10rem;
        box-shadow: 0 14px 28px rgba(30, 58, 138, 0.18);
    }

    .privacy-modal__button:hover {
        transform: translateY(-1px);
        box-shadow: 0 16px 32px rgba(30, 58, 138, 0.24);
    }

    .privacy-modal__button:focus {
        outline: none;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.18);
    }

    .privacy-modal__button-label {
        display: block;
        line-height: 1.1;
    }

    .privacy-modal__button-note {
        display: block;
        margin-top: 0.2rem;
        font-size: 0.75rem;
        font-weight: 500;
        opacity: 0.82;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            transform: translateY(100%);
        }

        to {
            transform: translateY(0);
        }
    }

    @media (max-width: 640px) {
        .privacy-modal__container {
            padding: 1rem 1rem 1.25rem;
            border-radius: 1rem 1rem 0 0;
        }

        .privacy-modal__title {
            font-size: 1.25rem;
        }

        .privacy-modal__layout {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .privacy-modal__actions {
            width: 100%;
        }

        .privacy-modal__button {
            width: 100%;
        }
    }
</style>

<div id="privacyModal" class="privacy-modal">
    <div class="privacy-modal__container">
        <div class="privacy-modal__layout">
            <div class="privacy-modal__body">
                <span class="privacy-modal__eyebrow">{{ __('Required notice') }}</span>
                <h2 class="privacy-modal__title">{{ __('Privacy & Cookies') }}</h2>

                <div class="privacy-modal__content">
                    <p class="privacy-modal__text">
                        {{ __('We take your privacy seriously. This website does not collect personal information from visitors. We only use strictly necessary cookies to ensure the proper functioning of the site, such as keeping your session active or remembering basic display preferences.') }}
                    </p>
                    <p class="privacy-modal__text">
                        <span class="privacy-modal__highlight">{{ __('We do not share, sell, or store personal information.') }}</span>
                        {{ __('All browsing-related information is kept anonymous and is only used to ensure the website functions correctly.') }}
                    </p>
                    <p class="privacy-modal__text">
                        {{ __('To continue using the platform, you must accept this notice and the use of the strictly necessary cookies required for the service to operate correctly.') }}
                    </p>
                </div>
            </div>

            <div class="privacy-modal__actions">
                <button id="privacyAccept" class="privacy-modal__button">
                    <span class="privacy-modal__button-label">{{ __('Accept and continue') }}</span>
                    <span class="privacy-modal__button-note">{{ __('Required to access the platform') }}</span>
                </button>
            </div>
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
    });
</script>
