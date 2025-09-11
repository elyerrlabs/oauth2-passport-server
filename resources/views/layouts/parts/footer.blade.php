<footer id="footer" class="bg-[var(--color-bg-secondary)] border-t border-[var(--color-border)] py-12 mt-0">
    <div class="container mx-auto px-4">
        <!-- Main Footer Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
            <!-- Brand & Description -->
            <div class="lg:col-span-1">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-[var(--color-primary)] rounded-lg flex items-center justify-center mr-3">
                        <i class="mdi mdi-security-network text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[var(--color-text-primary)]">{{ __('OAuth2 Passport Server') }}
                    </h3>
                </div>
                <p class="text-[var(--color-text-secondary)] mb-4 text-sm">
                    {{ __('Enterprise-grade authentication system with OAuth2 and OpenID Connect support. Secure, scalable, and modular.') }}
                </p>
                <div class="flex space-x-3">
                    <a href="mailto:yerel9212@yahoo.es"
                        class="w-10 h-10 bg-[var(--color-bg-tertiary)] hover:bg-[var(--color-primary)] text-[var(--color-text-secondary)] hover:text-white rounded-lg flex items-center justify-center transition-all duration-300"
                        title="{{ __('Send email') }}">
                        <i class="mdi mdi-email-outline"></i>
                    </a>
                    <a href="https://github.com/elyerr" target="_blank" rel="noopener noreferrer"
                        class="w-10 h-10 bg-[var(--color-bg-tertiary)] hover:bg-[var(--color-primary)] text-[var(--color-text-secondary)] hover:text-white rounded-lg flex items-center justify-center transition-all duration-300"
                        title="{{ __('GitHub Profile') }}">
                        <i class="mdi mdi-github"></i>
                    </a>
                    <a href="https://gitlab.com/elyerr" target="_blank" rel="noopener noreferrer"
                        class="w-10 h-10 bg-[var(--color-bg-tertiary)] hover:bg-[var(--color-primary)] text-[var(--color-text-secondary)] hover:text-white rounded-lg flex items-center justify-center transition-all duration-300"
                        title="{{ __('GitLab Profile') }}">
                        <i class="mdi mdi-gitlab"></i>
                    </a>
                    <a href="https://t.me/elyerr" target="_blank" rel="noopener noreferrer"
                        class="w-10 h-10 bg-[var(--color-bg-tertiary)] hover:bg-[var(--color-primary)] text-[var(--color-text-secondary)] hover:text-white rounded-lg flex items-center justify-center transition-all duration-300"
                        title="{{ __('Telegram') }}">
                        <i class="mdi mdi-send-circle-outline"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4
                    class="text-lg font-semibold text-[var(--color-text-primary)] mb-4 pb-2 border-b border-[var(--color-border)]">
                    {{ __('Quick Links') }}</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('documentation.index') }}"
                            class="text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] transition-colors flex items-center">
                            <i class="mdi mdi-book-open-page-variant mr-2 text-sm"></i>
                            {{ __('Documentation') }}
                        </a>
                    </li>
                    @if (Route::has('passport.clients.index'))
                        <li>
                            <a href="{{ route('passport.clients.index') }}"
                                class="text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] transition-colors flex items-center">
                                <i class="mdi mdi-shield-key mr-2 text-sm"></i>
                                {{ __('OAuth2 Clients') }}
                            </a>
                        </li>
                    @endif
                    @if (Route::has('passport.personal.tokens.index'))
                        <li>
                            <a href="{{ route('passport.personal.tokens.index') }}"
                                class="text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] transition-colors flex items-center">
                                <i class="mdi mdi-key-chain-variant mr-2 text-sm"></i>
                                {{ __('API Keys') }}
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('user.admin.dashboard') }}"
                            class="text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] transition-colors flex items-center">
                            <i class="mdi mdi-cog-outline mr-2 text-sm"></i>
                            {{ __('Admin Panel') }}
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Resources -->
            <div>
                <h4
                    class="text-lg font-semibold text-[var(--color-text-primary)] mb-4 pb-2 border-b border-[var(--color-border)]">
                    {{ __('Resources') }}</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="https://oauth.net/2/" target="_blank"
                            class="text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] transition-colors flex items-center">
                            <i class="mdi mdi-open-in-new mr-2 text-sm"></i>
                            {{ __('OAuth2 Specification') }}
                        </a>
                    </li>
                    <li>
                        <a href="https://openid.net/connect/" target="_blank"
                            class="text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] transition-colors flex items-center">
                            <i class="mdi mdi-open-in-new mr-2 text-sm"></i>
                            {{ __('OpenID Connect') }}
                        </a>
                    </li>
                    <li>
                        <a href="https://documenter.getpostman.com/view/5625104/2sB2xBDq6o"
                            class="text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] transition-colors flex items-center">
                            <i class="mdi mdi-api mr-2 text-sm"></i>
                            {{ __('API Reference') }}
                        </a>
                    </li>
                    <li>
                        <a href="https://www.youtube.com/@elyerr"
                            class="text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] transition-colors flex items-center">
                            <i class="mdi mdi-school-outline mr-2 text-sm"></i>
                            {{ __('Tutorials') }}
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Repository Stats -->
            <div>
                <h4
                    class="text-lg font-semibold text-[var(--color-text-primary)] mb-4 pb-2 border-b border-[var(--color-border)]">
                    {{ __('Repository') }}
                </h4>
                <div class="mb-4">
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="text-[var(--color-text-secondary)]">{{ __('License') }}</span>
                        <a href="https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/LICENSE.md?ref_type=heads"
                            target="_blank" class="text-[var(--color-success)] hover:underline">
                            {{ __('NON-COMMERCIAL USE LICENSE') }}
                        </a>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-[var(--color-text-secondary)]">{{ __('Status') }}</span>
                        <span class="inline-flex items-center text-[var(--color-success)]">
                            <i class="mdi mdi-check-circle-outline mr-1"></i>
                            {{ __('Active') }}
                        </span>
                    </div>
                </div>
                <div class="mb-4 flex flex-col space-y-2">
                    <a href="https://gitlab.com/elyerr/oauth2-passport-server" target="_blank"
                        class="inline-flex items-center bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white px-4 py-2 rounded-lg transition-colors text-sm">
                        <i class="mdi mdi-star-circle-outline mr-2"></i>
                        {{ __('Star on GitLab') }}
                    </a>

                    <a href="https://github.com/elyerr/oauth2-passport-server" target="_blank"
                        class="inline-flex items-center bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white px-4 py-2 rounded-lg transition-colors text-sm">
                        <i class="mdi mdi-star-circle-outline mr-2"></i>
                        {{ __('Star on GitHub') }}
                    </a>
                </div>

            </div>
        </div>

        <!-- Donations Section -->
        <div class="bg-[var(--color-bg-primary)] border border-[var(--color-border)] rounded-lg p-6 mb-10">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                <div class="mb-4 md:mb-0">
                    <h4 class="text-lg font-semibold text-[var(--color-text-primary)] flex items-center">
                        <i class="mdi mdi-heart-outline text-red-400 mr-2"></i>
                        {{ __('Support this Project') }}
                    </h4>
                    <p class="text-[var(--color-text-secondary)] text-sm mt-1">
                        {{ __('Your donations help maintain and improve this open-source project.') }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <!-- Crypto Wallet -->
                    <button data-action="toggle-wallet"
                        class="flex items-center text-white bg-[var(--color-bg-secondary)] hover:bg-[var(--color-bg-tertiary)] border border-[var(--color-border)] px-4 py-2 rounded-lg transition-colors text-sm">
                        <i class="mdi mdi-wallet-outline mr-2 text-[var(--color-primary)]"></i>
                        {{ __('Crypto Donation') }}
                    </button>

                    <!-- PayPal -->
                    <a href="https://paypal.me/elyerr" target="_blank"
                        class="flex items-center text-white bg-[var(--color-bg-secondary)] hover:bg-[var(--color-bg-tertiary)] border border-[var(--color-border)] px-4 py-2 rounded-lg transition-colors text-sm">
                        <i class="mdi mdi-paypal mr-2 text-blue-400"></i>
                        PayPal
                    </a>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-[var(--color-border)] pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-[var(--color-text-secondary)] text-sm mb-4 md:mb-0">
                &copy; {{ now()->format('Y') }} {{ config('app.org_name') }}. {{ __('All rights reserved.') }}
            </p>
            <div class="flex items-center space-x-6 text-sm">
                <span class="text-[var(--color-text-secondary)] flex items-center">
                    {{ __('Made with') }} <i class="mdi mdi-heart text-red-400 mx-1"></i> {{ __('by Elyerr') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Wallet Modal -->
    <div id="walletModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden">
        <div
            class="bg-[var(--color-bg-primary)] border border-[var(--color-border)] rounded-xl w-full max-w-md mx-4 max-h-[90vh] overflow-hidden flex flex-col">
            <div
                class="flex justify-between items-center p-6 border-b border-[var(--color-border)] bg-[var(--color-bg-secondary)]">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-[var(--color-primary)] rounded-lg flex items-center justify-center mr-3">
                        <i class="mdi mdi-wallet-outline text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-[var(--color-text-primary)]">{{ __('Crypto Donations') }}
                    </h3>
                </div>
                <button data-action="toggle-wallet"
                    class="text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] p-1 rounded-full hover:bg-[var(--color-bg-tertiary)] transition-colors">
                    <i class="mdi mdi-close text-2xl"></i>
                </button>
            </div>

            <div class="overflow-y-auto flex-1 p-6">
                <p class="text-[var(--color-text-secondary)] text-center mb-6">
                    {{ __('Thank you for considering a donation. Your support helps maintain and improve this open-source project.') }}
                </p>

                <!-- TRX Address -->
                <div class="bg-[var(--color-bg-secondary)] p-4 rounded-lg border border-[var(--color-border)] mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-orange-400 rounded-md flex items-center justify-center mr-3">
                                <i class="mdi mdi-currency-usd text-white text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-[var(--color-text-primary)] font-medium">TRX (TRON)</h4>
                                <p class="text-[var(--color-text-secondary)] text-xs">{{ __('Native TRON currency') }}
                                </p>
                            </div>
                        </div>
                        <button data-action="copy-wallet" data-wallet="TU1z3998ku34hf2Cg9B7z6mZcvT5DmaSXj"
                            class="text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] p-2 rounded-md hover:bg-[var(--color-bg-tertiary)] transition-colors"
                            title="{{ __('Copy address') }}">
                            <i class="mdi mdi-content-copy"></i>
                        </button>
                    </div>
                    <div class="bg-[var(--color-bg-primary)] p-3 rounded-md border border-[var(--color-border)]">
                        <p class="text-[var(--color-text-secondary)] text-xs font-mono break-all mb-2">
                            TU1z3998ku34hf2Cg9B7z6mZcvT5DmaSXj
                        </p>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-[var(--color-text-muted)]">Network: TRON (TRC20)</span>
                            <span class="text-[var(--color-success)] flex items-center">
                                <i class="mdi mdi-check-circle-outline mr-1"></i> {{ __('Active') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('walletModal');

            document.addEventListener('click', (e) => {
                const action = e.target.closest('[data-action]')?.dataset.action;

                if (action === 'toggle-wallet') {
                    modal.classList.toggle('hidden');
                    document.body.style.overflow = modal.classList.contains('hidden') ? '' : 'hidden';
                }

                if (action === 'copy-wallet') {
                    const wallet = e.target.closest('[data-action]').dataset.wallet;
                    navigator.clipboard.writeText(wallet).then(() => {
                        showToast('Wallet address copied!');
                    }).catch(() => {
                        showToast('Failed to copy', 'error');
                    });
                }
            });

            function showToast(message, type = 'success') {
                const existing = document.getElementById('copyToast');
                if (existing) existing.remove();

                const toast = document.createElement('div');
                toast.id = 'copyToast';
                toast.className = `fixed bottom-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-y-10 opacity-0 ${
            type === 'success' ? 'bg-[var(--color-success)] text-white' : 'bg-[var(--color-danger)] text-white'
        }`;
                toast.innerHTML = `
            <div class="flex items-center">
                <i class="mdi mdi-${type === 'success' ? 'check-circle' : 'alert-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.classList.remove('translate-y-10', 'opacity-0');
                    toast.classList.add('translate-y-0', 'opacity-100');
                }, 10);

                setTimeout(() => {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-10', 'opacity-0');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }
        });
    </script>
@endpush

@push('css')
    <style nonce="{{ $nonce }}">
        #walletModal {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        #walletModal:not(.hidden) {
            opacity: 1;
            pointer-events: auto;
        }
    </style>
@endpush
