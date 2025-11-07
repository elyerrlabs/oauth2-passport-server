<header class="bg-indigo-600 text-white shadow-md">
    <div class="container mx-auto flex justify-between items-center py-4 px-2">
        <h1 class="text-2xl font-bold">{{ config('app.name') }}</h1>
        <nav>
            <ul class="flex space-x-6">
                @if (!auth()->check())
                    <li>
                        <a href="{{ route('login') }}"
                            class="hover:underline flex items-center gap-1 transition-colors hover:text-indigo-200">
                            <i class="mdi mdi-login text-2xl"></i> {{ __('Login') }}
                        </a>
                    </li>

                    @if (Route::has('register'))
                        <li>
                            <a href="{{ route('register') }}"
                                class="hover:underline flex items-center gap-1 transition-colors hover:text-indigo-200">
                                <i class="mdi mdi-account-edit-outline text-2xl"></i> {{ __('Register') }}
                            </a>
                        </li>
                    @endif
                    @if (Route::has('transaction.plans.index'))
                        <li>
                            <a href="{{ route('transaction.plans.index') }}"
                                class="hover:underline flex items-center gap-1 transition-colors hover:text-indigo-200">
                                <i class="mdi mdi-cash-check text-2xl"></i> {{ __('Subscriptions') }}
                            </a>
                        </li>
                    @endif
                @endif
                @if (auth()->check() && Route::has('user.dashboard'))
                    <li>
                        <a href="{{ route('user.dashboard') }}"
                            class="hover:underline flex items-center gap-1 transition-colors hover:text-indigo-200">
                            <i class="mdi mdi-home-outline text-2xl"></i> {{ __('Dashboard') }}
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</header>
