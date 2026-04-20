{{-- resources/views/components/profile-menu.blade.php --}}
@props([
    'user' => null,
    'loginRoute' => null,
    'logoutRoute' => null,
    'registerRoute' => null,
    'dashboardRoute' => null,
    'homeRoute' => '/',
    'allowRegister' => true,
    'showVerifiedBadge' => true,
    'avatarBackground' => 'bg-blue-500 dark:bg-blue-600',
    'menuPosition' => 'right', // right, left
])

@php
    // Valores por defecto
    $user = $user ?? auth()->user();
    $loginRoute = $loginRoute ?? route('login');
    $logoutRoute = $logoutRoute ?? route('logout');
    $registerRoute = $registerRoute ?? route('register');
    $dashboardRoute = $dashboardRoute ?? route('user.dashboard');

    // Iniciales del usuario
    $userInitials = '?';
    if ($user) {
        $names = array_filter([$user->name ?? '', $user->last_name ?? '']);
        $userInitials = implode('', array_map(fn($n) => strtoupper(substr($n, 0, 1)), $names));
        if (empty($userInitials)) {
            $userInitials = '?';
        }
    }

    // Clases de posición del menú
    $positionClass = $menuPosition === 'left' ? 'left-0' : 'right-0';
@endphp

<div class="profile-menu relative" data-profile-menu>
    {{-- Botón del menú --}}
    <button type="button"
        class="profile-menu-trigger flex items-center p-1 md:p-2 bg-white dark:bg-gray-800 cursor-pointer rounded-xl shadow-sm hover:shadow hover:border-gray-300 dark:hover:border-gray-600 transition-shadow duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 group"
        aria-label="{{ __('User menu') }}" aria-expanded="false" aria-haspopup="true">

        {{-- Avatar --}}
        <div class="shrink-0">
            <div
                class="p-2 rounded-full {{ $avatarBackground }} flex items-center justify-center text-white shadow-sm group-hover:shadow transition-shadow">
                @if ($user)
                    <span class="text-sm font-medium">{{ $userInitials }}</span>
                @else
                    <i class="mdi mdi-account text-sm"></i>
                @endif
            </div>
        </div>

        {{-- Información del usuario (Desktop) --}}
        <div class="hidden lg:block lg:mx-2 text-left">
            <div class="text-sm font-medium text-gray-700 dark:text-gray-300 leading-tight">
                {{ $user ? $user->name : __('Guest') }}
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400 leading-tight">
                {{ $user ? $user->email : __('Sign in') }}
            </div>
        </div>

        {{-- Icono Chevron --}}
        <div class="hidden lg:block shrink-0">
            <i class="profile-menu-chevron mdi mdi-chevron-down text-gray-400 transition-transform duration-200"></i>
        </div>

        <span class="sr-only">{{ __('User menu') }}</span>
    </button>

    {{-- Dropdown Menu --}}
    <div class="profile-menu-dropdown absolute {{ $positionClass }} mt-2 w-72 sm:w-80 bg-white dark:bg-gray-800 rounded-xl shadow-xl ring-1 ring-black/10 dark:ring-white/10 z-50 overflow-hidden hidden"
        role="menu" aria-orientation="vertical">

        @if ($user)
            {{-- Sección de información del usuario autenticado --}}
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="shrink-0">
                        <div
                            class="w-12 h-12 rounded-full {{ $avatarBackground }} flex items-center justify-center text-white shadow">
                            <span class="text-lg font-semibold">{{ $userInitials }}</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-gray-900 dark:text-white truncate">
                            {{ $user->name }} {{ $user->last_name ?? '' }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 truncate">
                            {{ $user->email }}
                        </div>
                        @if ($showVerifiedBadge && $user->email_verified_at)
                            <div class="flex items-center mt-1 text-xs text-blue-600 dark:text-blue-400">
                                <i class="mdi mdi-check-circle mr-1"></i>
                                {{ __('Verified account') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            {{-- Sección de invitado --}}
            <div class="p-4 bg-gray-50 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="shrink-0">
                        <div
                            class="w-12 h-12 rounded-full bg-gray-400 dark:bg-gray-600 flex items-center justify-center text-white shadow">
                            <i class="mdi mdi-account-outline text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 dark:text-white">
                            {{ __('Welcome!') }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Sign in to your account') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Opciones del menú --}}
        <ul class="divide-y divide-gray-100 dark:divide-gray-700">
            {{-- Inicio --}}
            <li role="menuitem">
                <a href="{{ $homeRoute }}"
                    class="profile-menu-item w-full text-left px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center transition-colors duration-150 group">
                    <div
                        class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                        <i class="mdi mdi-home text-blue-600 dark:text-blue-400 text-lg"></i>
                    </div>
                    <div>
                        <div class="font-medium text-gray-900 dark:text-white">{{ __('Home') }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Back to homepage') }}</div>
                    </div>
                </a>
            </li>

            @if ($user)
                {{-- Mi Cuenta --}}
                <li role="menuitem">
                    <a href="{{ $dashboardRoute }}"
                        class="profile-menu-item w-full text-left px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center transition-colors duration-150 group">
                        <div
                            class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center mr-3">
                            <i class="mdi mdi-account-cog text-green-600 dark:text-green-400 text-lg"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">{{ __('My Account') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Manage your profile') }}</div>
                        </div>
                    </a>
                </li>
            @else
                {{-- Iniciar Sesión (Mobile) --}}
                <li class="block lg:hidden" role="menuitem">
                    <a href="{{ $loginRoute }}" data-login-link
                        class="profile-menu-item w-full text-left px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center transition-colors duration-150 group">
                        <div
                            class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                            <i class="mdi mdi-login text-blue-600 dark:text-blue-400 text-lg"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">{{ __('Sign In') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Access your account') }}</div>
                        </div>
                    </a>
                </li>

                {{-- Registrarse (Mobile) --}}
                @if ($allowRegister)
                    <li class="block lg:hidden" role="menuitem">
                        <a href="{{ $registerRoute }}" data-register-link
                            class="profile-menu-item w-full text-left px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center transition-colors duration-150 group">
                            <div
                                class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mr-3">
                                <i class="mdi mdi-account-plus text-purple-600 dark:text-purple-400 text-lg"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ __('Sign Up') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Create a new account') }}
                                </div>
                            </div>
                        </a>
                    </li>
                @endif
            @endif

            {{-- Slot para items adicionales --}}
            @if (isset($additionalItems))
                {{ $additionalItems }}
            @endif

            @if ($user)
                {{-- Cerrar Sesión --}}
                <li role="menuitem">
                    <form method="POST" action="{{ $logoutRoute }}" class="logout-form">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-3 hover:bg-red-50 dark:hover:bg-red-900/10 flex items-center transition-colors duration-150 group">
                            <div
                                class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center mr-3">
                                <i class="mdi mdi-logout text-red-600 dark:text-red-400 text-lg"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ __('Sign Out') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('End your session') }}
                                </div>
                            </div>
                        </button>
                    </form>
                </li>
            @endif
        </ul>

        {{-- Footer --}}
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700">
            <div class="text-xs text-gray-500 dark:text-gray-400 text-center">
                {{ __('Secure connection') }}
                <i class="mdi mdi-shield-check text-green-500 ml-1"></i>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script nonce="{{ $nonce }}">
        (function($) {
            'use strict';

            // Componente Profile Menu
            function ProfileMenu(element) {
                this.$el = $(element);
                this.$trigger = this.$el.find('.profile-menu-trigger');
                this.$dropdown = this.$el.find('.profile-menu-dropdown');
                this.$chevron = this.$el.find('.profile-menu-chevron');
                this.isOpen = false;

                this.init();
            }

            ProfileMenu.prototype.init = function() {
                var self = this;

                // Toggle menú al hacer clic en el trigger
                this.$trigger.on('click.profileMenu', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    self.toggle();
                });

                // Manejar enlaces de login/register con redirect
                this.$el.find('[data-login-link], [data-register-link]').on('click.profileMenu', function(e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    self.handleAuthRedirect(url);
                });

                // Cerrar menú al hacer clic fuera
                $(document).on('click.profileMenu', function(e) {
                    if (self.isOpen && !self.$el.is(e.target) && !self.$el.has(e.target).length) {
                        self.close();
                    }
                });

                // Cerrar menú con tecla Escape
                $(document).on('keydown.profileMenu', function(e) {
                    if (e.key === 'Escape' && self.isOpen) {
                        self.close();
                        self.$trigger.focus();
                    }
                });

                // Navegación con teclado dentro del menú
                this.$dropdown.on('keydown.profileMenu', function(e) {
                    if (e.key === 'Escape') {
                        self.close();
                        self.$trigger.focus();
                    }
                });

                // Cerrar menú al hacer clic en un item
                this.$el.find('.profile-menu-item').on('click.profileMenu', function() {
                    self.close();
                });
            };

            ProfileMenu.prototype.toggle = function() {
                this.isOpen ? this.close() : this.open();
            };

            ProfileMenu.prototype.open = function() {
                this.$dropdown.removeClass('hidden').addClass('block');
                this.$chevron.removeClass('mdi-chevron-down').addClass('mdi-chevron-up');
                this.$trigger.attr('aria-expanded', 'true');
                this.isOpen = true;

                // Enfocar primer elemento del menú
                var $firstItem = this.$dropdown.find('.profile-menu-item').first();
                if ($firstItem.length) {
                    setTimeout(function() {
                        $firstItem.focus();
                    }, 50);
                }
            };

            ProfileMenu.prototype.close = function() {
                this.$dropdown.removeClass('block').addClass('hidden');
                this.$chevron.removeClass('mdi-chevron-up').addClass('mdi-chevron-down');
                this.$trigger.attr('aria-expanded', 'false');
                this.isOpen = false;
            };

            ProfileMenu.prototype.handleAuthRedirect = function(url) {
                var currentUrl = window.location.href;
                var path = url.startsWith('/') ? url : new URL(url, window.location.origin).pathname;

                if (path.startsWith('/auth/login') || path.startsWith('/auth/register')) {
                    var redirectUrl = new URL(url, window.location.origin);
                    redirectUrl.searchParams.set('redirect_to', currentUrl);
                    window.location.href = redirectUrl.toString();
                } else {
                    window.location.href = url;
                }
            };

            ProfileMenu.prototype.destroy = function() {
                this.$trigger.off('.profileMenu');
                this.$el.find('[data-login-link], [data-register-link]').off('.profileMenu');
                this.$el.find('.profile-menu-item').off('.profileMenu');
                this.$dropdown.off('.profileMenu');
                $(document).off('.profileMenu');
            };

            // Plugin jQuery
            $.fn.profileMenu = function() {
                return this.each(function() {
                    var $this = $(this);
                    var instance = $this.data('profileMenu');

                    if (!instance) {
                        instance = new ProfileMenu(this);
                        $this.data('profileMenu', instance);
                    }
                });
            };

            // Inicializar todos los menús de perfil
            $(document).ready(function() {
                $('[data-profile-menu]').profileMenu();
            });

            // Exponer para uso global
            window.ProfileMenu = ProfileMenu;

        })(jQuery);
    </script>
@endpush
