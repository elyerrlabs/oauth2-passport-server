{{-- resources/views/components/profile-menu.blade.php --}}
@props([
    'user' => null,
    'homeRoute' => '/',
    'showVerifiedBadge' => true,
    'avatarBackground' => 'bg-blue-500 dark:bg-blue-600',
    'menuPosition' => 'right', // right, left
])

@php
    // Valores por defecto
    $user = $user ?? auth()->user();

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
        class="profile-menu-trigger flex items-center p-1.5 md:p-2 bg-white dark:bg-gray-800 cursor-pointer rounded-lg shadow-sm hover:shadow hover:border-gray-300 dark:hover:border-gray-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 group"
        aria-label="{{ __('User menu') }}" aria-expanded="false" aria-haspopup="true">

        {{-- Avatar --}}
        <div class="shrink-0">
            <div
                class="w-7 h-7 md:w-8 md:h-8 rounded-full {{ $avatarBackground }} flex items-center justify-center text-white shadow-sm group-hover:shadow transition-all">
                @if ($user)
                    <span class="text-xs md:text-sm font-medium">{{ $userInitials }}</span>
                @else
                    <i class="mdi mdi-account text-xs md:text-sm"></i>
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
            <i
                class="profile-menu-chevron mdi mdi-chevron-down text-gray-400 text-sm transition-transform duration-200"></i>
        </div>

        <span class="sr-only">{{ __('User menu') }}</span>
    </button>

    {{-- Dropdown Menu --}}
    <div class="profile-menu-dropdown absolute {{ $positionClass }} mt-2 w-64 sm:w-72 bg-white dark:bg-gray-800 rounded-xl shadow-lg ring-1 ring-black/5 dark:ring-white/10 z-50 overflow-hidden hidden"
        role="menu" aria-orientation="vertical">

        @if ($user)
            {{-- Sección de información del usuario autenticado --}}
            <div
                class="p-3 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center space-x-2.5">
                    <div class="shrink-0">
                        <div
                            class="w-10 h-10 rounded-full {{ $avatarBackground }} flex items-center justify-center text-white shadow-sm">
                            <span class="text-sm font-semibold">{{ $userInitials }}</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-gray-900 dark:text-white text-sm truncate">
                            {{ $user->name }} {{ $user->last_name ?? '' }}
                        </div>
                        <div class="text-xs text-gray-600 dark:text-gray-400 truncate">
                            {{ $user->email }}
                        </div>
                        @if ($showVerifiedBadge && auth()->check() && $user->email_verified_at)
                            <div class="flex items-center mt-1 text-[10px] text-blue-600 dark:text-blue-400">
                                <i class="mdi mdi-check-circle text-[10px] mr-0.5"></i>
                                {{ __('Verified') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            {{-- Sección de invitado --}}
            <div
                class="p-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center space-x-2.5">
                    <div class="shrink-0">
                        <div
                            class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-white shadow-sm">
                            <i class="mdi mdi-account-outline text-sm"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 dark:text-white text-sm">
                            {{ __('Welcome!') }}
                        </div>
                        <div class="text-xs text-gray-600 dark:text-gray-400">
                            {{ __('Sign in to your account') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Opciones del menú --}}
        <ul class="py-1 divide-y divide-gray-100 dark:divide-gray-700">
            @if ($user)
                {{-- Mi Cuenta --}}
                <li role="menuitem">
                    <a href="{{ route('user.dashboard') }}"
                        class="profile-menu-item w-full text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 flex items-center transition-colors duration-150 group">
                        <div
                            class="w-7 h-7 rounded-md bg-green-100 dark:bg-green-900/30 flex items-center justify-center mr-2.5 group-hover:scale-105 transition-transform">
                            <i class="mdi mdi-account-cog text-green-600 dark:text-green-400 text-sm"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white text-sm">{{ __('Dashboard') }}</div>
                            <div class="text-[11px] text-gray-500 dark:text-gray-400">{{ __('Manage your profile') }}
                            </div>
                        </div>
                    </a>
                </li>
            @else
                {{-- Iniciar Sesión (Mobile) --}}
                <li class="block lg:hidden" role="menuitem">
                    <a href="{{ route('login') }}" data-login-link
                        class="profile-menu-item w-full text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 flex items-center transition-colors duration-150 group">
                        <div
                            class="w-7 h-7 rounded-md bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-2.5 group-hover:scale-105 transition-transform">
                            <i class="mdi mdi-login text-blue-600 dark:text-blue-400 text-sm"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white text-sm">{{ __('Sign In') }}</div>
                            <div class="text-[11px] text-gray-500 dark:text-gray-400">{{ __('Access your account') }}
                            </div>
                        </div>
                    </a>
                </li>

                {{-- Registrarse (Mobile) --}}
                @if (Route::has('register'))
                    <li class="block lg:hidden" role="menuitem">
                        <a href="{{ route('register') }}" data-register-link
                            class="profile-menu-item w-full text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 flex items-center transition-colors duration-150 group">
                            <div
                                class="w-7 h-7 rounded-md bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mr-2.5 group-hover:scale-105 transition-transform">
                                <i class="mdi mdi-account-plus text-purple-600 dark:text-purple-400 text-sm"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white text-sm">{{ __('Sign Up') }}
                                </div>
                                <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                    {{ __('Create a new account') }}
                                </div>
                            </div>
                        </a>
                    </li>
                @endif
            @endif

            @if (Route::has('transaction.plans.index'))
                <li role="menuitem">
                    <a href="{{ route('transaction.plans.index') }}"
                        class="profile-menu-item w-full text-left px-3 py-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 flex items-center transition-colors duration-150 group">
                        <div
                            class="w-7 h-7 rounded-md bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-2.5 group-hover:scale-105 transition-transform">
                            <i class="mdi mdi-cart-heart text-blue-600 dark:text-blue-400 text-sm"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white text-sm">{{ __('Plans') }}</div>
                            <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                {{ __('Browse available plans') }}
                            </div>
                        </div>
                    </a>
                </li>
            @endif

            {{-- Slot para items adicionales --}}
            @if (isset($additionalItems))
                {{ $additionalItems }}
            @endif

            @if ($user)
                {{-- Separador --}}
                <li class="border-t border-gray-100 dark:border-gray-700 my-1"></li>

                {{-- Cerrar Sesión --}}
                <li role="menuitem">
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-3 py-2 hover:bg-red-50 dark:hover:bg-red-900/10 flex items-center transition-colors duration-150 group">
                            <div
                                class="w-7 h-7 rounded-md bg-red-100 dark:bg-red-900/30 flex items-center justify-center mr-2.5 group-hover:scale-105 transition-transform">
                                <i class="mdi mdi-logout text-red-600 dark:text-red-400 text-sm"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white text-sm">{{ __('Sign Out') }}
                                </div>
                                <div class="text-[11px] text-gray-500 dark:text-gray-400">{{ __('End your session') }}
                                </div>
                            </div>
                        </button>
                    </form>
                </li>
            @endif
        </ul>

        {{-- Footer --}}
        <div class="px-3 py-2 bg-gray-50/80 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-center space-x-1.5 text-[10px] text-gray-500 dark:text-gray-400">
                <i class="mdi mdi-shield-check text-green-500 text-[10px]"></i>
                <span>{{ __('Secure connection') }}</span>
                <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                <i class="mdi mdi-lock text-gray-400 text-[10px]"></i>
                <span>{{ __('Encrypted') }}</span>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener("DOMContentLoaded", function() {

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
                    this.$el.find('[data-login-link], [data-register-link]').on('click.profileMenu',
                        function(e) {
                            e.preventDefault();
                            var url = $(this).attr('href');
                            self.handleAuthRedirect(url);
                        });

                    // Cerrar menú al hacer clic fuera
                    $(document).on('click.profileMenu', function(e) {
                        if (self.isOpen && !self.$el.is(e.target) && !self.$el.has(e.target)
                            .length) {
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
        });
    </script>
@endpush
