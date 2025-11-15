<header class="bg-blue-600 dark:bg-gray-800 text-white shadow-md">
    <div class="container mx-auto flex justify-between items-center py-4 px-4">
        <h1 class="text-2xl font-bold">{{ config('app.name') }}</h1>

        <nav class="flex items-center">
            <ul class="flex items-center space-x-6">
                @if (!auth()->check())
                    <li class="hidden lg:block">
                        <a href="{{ route('login') }}"
                            class="hover:underline flex items-center gap-1 transition-colors hover:text-indigo-200">
                            <i class="mdi mdi-login text-2xl"></i> {{ __('Login') }}
                        </a>
                    </li>

                    @if (Route::has('register'))
                        <li class="hidden lg:block">
                            <a href="{{ route('register') }}"
                                class="hover:underline flex items-center gap-1 transition-colors hover:text-indigo-200">
                                <i class="mdi mdi-account-edit-outline text-2xl"></i> {{ __('Register') }}
                            </a>
                        </li>
                    @endif
                    @if (Route::has('transaction.plans.index'))
                        <li class="hidden lg:block">
                            <a href="{{ route('transaction.plans.index') }}"
                                class="hover:underline flex items-center gap-1 transition-colors hover:text-indigo-200">
                                <i class="mdi mdi-cash-check text-2xl"></i> {{ __('Subscriptions') }}
                            </a>
                        </li>
                    @endif
                @endif

                <!-- Tema siempre visible -->
                <li class="flex items-center">
                    <x-theme />
                </li>

                @if (auth()->check() && Route::has('user.dashboard'))
                    <li class="hidden lg:block">
                        <a href="{{ route('user.dashboard') }}"
                            class="hover:underline flex items-center gap-1 transition-colors hover:text-indigo-200">
                            <i class="mdi mdi-home-outline text-2xl"></i> {{ __('Dashboard') }}
                        </a>
                    </li>
                @endif

                <!-- Mobile menu -->
                <li class="relative block lg:hidden">
                    <button id="mobileMenuButton"
                        class="p-2 rounded-md hover:bg-blue-500 dark:hover:bg-gray-700 transition-colors">
                        <i class="mdi mdi-menu text-2xl"></i>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="mobileDropdown"
                        class="absolute right-0 top-full mt-2 w-48 bg-blue-700 dark:bg-gray-700 rounded-md shadow-lg py-2 hidden z-50">
                        @if (!auth()->check())
                            <a href="{{ route('login') }}"
                                class="flex items-center gap-2 px-4 py-2 hover:bg-blue-600 dark:hover:bg-gray-600 transition-colors">
                                <i class="mdi mdi-login text-xl"></i>
                                <span>{{ __('Login') }}</span>
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="flex items-center gap-2 px-4 py-2 hover:bg-blue-600 dark:hover:bg-gray-600 transition-colors">
                                    <i class="mdi mdi-account-edit-outline text-xl"></i>
                                    <span>{{ __('Register') }}</span>
                                </a>
                            @endif
                            @if (Route::has('transaction.plans.index'))
                                <a href="{{ route('transaction.plans.index') }}"
                                    class="flex items-center gap-2 px-4 py-2 hover:bg-blue-600 dark:hover:bg-gray-600 transition-colors">
                                    <i class="mdi mdi-cash-check text-xl"></i>
                                    <span>{{ __('Subscriptions') }}</span>
                                </a>
                            @endif
                        @endif

                        @if (auth()->check() && Route::has('user.dashboard'))
                            <a href="{{ route('user.dashboard') }}"
                                class="flex items-center gap-2 px-4 py-2 hover:bg-blue-600 dark:hover:bg-gray-600 transition-colors">
                                <i class="mdi mdi-home-outline text-xl"></i>
                                <span>{{ __('Dashboard') }}</span>
                            </a>
                        @endif
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</header>

<script nonce="{{ $nonce }}">
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileDropdown = document.getElementById('mobileDropdown');

        if (mobileMenuButton && mobileDropdown) {
            mobileMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                mobileDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!mobileDropdown.contains(e.target) && !mobileMenuButton.contains(e.target)) {
                    mobileDropdown.classList.add('hidden');
                }
            });

            mobileDropdown.addEventListener('click', function(e) {
                if (e.target.tagName === 'A') {
                    mobileDropdown.classList.add('hidden');
                }
            });
        }
    });
</script>
