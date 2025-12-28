<header class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-50">
    <nav class="container mx-auto px-4 lg:px-0 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <i class="fas fa-passport text-purple-600 text-xl"></i>
                <div>
                    <h1 class="text-sm md:text-lg font-bold text-gray-900 dark:text-white">
                        {{ __('OAuth2 Passport Server') }}
                    </h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ __('by Elyerr') }}
                    </p>
                </div>
            </div>

            {{-- Desktop menu --}}
            <div class="hidden md:flex space-x-8 items-center">
                <a href="#features"
                    class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium">
                    {{ __('Features') }}
                </a>
                <a href="#elymod"
                    class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium">
                    {{ __('Elymod') }}
                </a>
                <a href="#runtime"
                    class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium">
                    {{ __('Laravel Runtime') }}
                </a>
                <a href="#links"
                    class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium">
                    {{ __('Links') }}
                </a>

                @guest
                    <a href="{{ route('login') }}"
                        class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium">
                        {{ __('Login') }}
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium">
                            {{ __('Register') }}
                        </a>
                    @endif

                    @if (Route::has('transaction.plans.index'))
                        <a href="{{ route('transaction.plans.index') }}"
                            class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium">
                            {{ __('Subscriptions') }}
                        </a>
                    @endif
                @endguest

                @auth
                    @if (Route::has('user.dashboard'))
                        <a href="{{ route('user.dashboard') }}"
                            class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium">
                            {{ __('Dashboard') }}
                        </a>
                    @endif
                @endauth

                <x-theme />
            </div>

            {{-- Mobile button --}}
            <button id="mobile-menu-button"
                class="md:hidden text-gray-800 dark:text-gray-300 focus:outline-none transition-colors"
                aria-label="{{ __('Toggle menu') }}" aria-expanded="false">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div id="mobile-menu"
            class="hidden md:hidden mt-4 w-full rounded-lg bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col divide-y divide-gray-200 dark:divide-gray-700">
                <a href="#features"
                    class="block text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium px-4 py-3">
                    {{ __('Features') }}
                </a>
                <a href="#elymod"
                    class="block text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium px-4 py-3">
                    {{ __('Elymod') }}
                </a>
                <a href="#runtime"
                    class="block text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium px-4 py-3">
                    {{ __('Laravel Runtime') }}
                </a>
                <a href="#links"
                    class="block text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium px-4 py-3">
                    {{ __('Links') }}
                </a>

                @guest
                    <a href="{{ route('login') }}"
                        class="block text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium px-4 py-3">
                        {{ __('Login') }}
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="block text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium px-4 py-3">
                            {{ __('Register') }}
                        </a>
                    @endif

                    @if (Route::has('transaction.plans.index'))
                        <a href="{{ route('transaction.plans.index') }}"
                            class="block text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium px-4 py-3">
                            {{ __('Subscriptions') }}
                        </a>
                    @endif
                @endguest

                @auth
                    @if (Route::has('user.dashboard'))
                        <a href="{{ route('user.dashboard') }}"
                            class="block text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 font-medium px-4 py-3">
                            {{ __('Dashboard') }}
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>
</header>

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener("DOMContentLoaded", function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            if (!mobileMenuButton || !mobileMenu) {
                console.error('{{ __('Menu elements not found') }}');
                return;
            }

            // Mobile menu toggle
            mobileMenuButton.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !isExpanded);

                // Toggle icon
                const icon = this.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-bars');
                    icon.classList.toggle('fa-times');
                }

                // Toggle menu visibility
                mobileMenu.classList.toggle('hidden');
            });

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        // Close mobile menu
                        mobileMenu.classList.add('hidden');
                        mobileMenuButton.setAttribute('aria-expanded', 'false');

                        // Reset icon
                        const icon = mobileMenuButton.querySelector('i');
                        if (icon) {
                            icon.classList.add('fa-bars');
                            icon.classList.remove('fa-times');
                        }

                        // Smooth scroll
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                    mobileMenuButton.setAttribute('aria-expanded', 'false');

                    const icon = mobileMenuButton.querySelector('i');
                    if (icon) {
                        icon.classList.add('fa-bars');
                        icon.classList.remove('fa-times');
                    }
                }
            });
        });
    </script>
@endpush
