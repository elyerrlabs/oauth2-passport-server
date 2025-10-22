@push('css')
    <style>
        .gradient-bg {
            background: linear-gradient(135deg,
                    #5f1596 0%, #9c20bf 50%, #2b0c80 100%);
            background-size: 200% 200%;
            animation: gentleGradient 20s ease infinite;
        }

        @keyframes gentleGradient {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .subtle-hover {
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .subtle-hover:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .social-subtle {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
        }

        .social-subtle:hover {
            transform: translateY(-2px) scale(1.05);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .text-subtle {
            color: #e2e8f0;
        }

        .border-subtle {
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .icon-glow {
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }
    </style>
@endpush

<footer id="footer" class="gradient-bg text-white pt-16 pb-8 relative overflow-hidden {{ $hidden ?? '' }}">
    <!-- Background Elements Sutiles -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 left-10 w-32 h-32 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-40 h-40 bg-purple-200 rounded-full blur-3xl"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
            <!-- Organization Information -->
            <div class="lg:col-span-5">
                <div class="flex items-center mb-8">
                    @if (config('app.org_name'))
                        <div class="relative group">
                            <div
                                class="w-14 h-14 glass-effect rounded-xl flex items-center justify-center mr-4 subtle-hover">
                                <i class="mdi mdi-domain text-white text-xl icon-glow"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-semibold text-white mb-1">
                                {{ config('app.org_name') }}
                            </h3>
                            @if (config('app.name') && config('app.name') != config('app.org_name'))
                                <p class="text-blue-100 text-sm opacity-80">{{ config('app.name') }}</p>
                            @endif
                        </div>
                    @endif
                </div>

                @if (config('app.description'))
                    <p class="text-subtle leading-relaxed text-base mb-8 opacity-90">
                        {{ config('app.description') }}
                    </p>
                @endif

                <!-- Mission & Vision Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if (config('app.mission'))
                        <div class="glass-effect rounded-xl p-4 border-subtle subtle-hover group">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center mr-3">
                                    <i class="mdi mdi-bullseye-arrow text-emerald-300 text-sm"></i>
                                </div>
                                <h4 class="font-medium text-emerald-200 text-sm">{{ __('Our Mission') }}</h4>
                            </div>
                            <p
                                class="text-subtle text-xs leading-relaxed opacity-90 group-hover:opacity-100 transition-opacity">
                                {{ config('app.mission') }}
                            </p>
                        </div>
                    @endif

                    @if (config('app.vision'))
                        <div class="glass-effect rounded-xl p-4 border-subtle subtle-hover group">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center mr-3">
                                    <i class="mdi mdi-eye-outline text-purple-300 text-sm"></i>
                                </div>
                                <h4 class="font-medium text-purple-200 text-sm">{{ __('Our Vision') }}</h4>
                            </div>
                            <p
                                class="text-subtle text-xs leading-relaxed opacity-90 group-hover:opacity-100 transition-opacity">
                                {{ config('app.vision') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div class="lg:col-span-3">
                <div class="flex items-center mb-6">
                    <div class="w-6 h-0.5 bg-cyan-400 rounded-full mr-3"></div>
                    <h4 class="text-lg font-medium text-white">{{ __('Contact Us') }}</h4>
                </div>

                <div class="space-y-3">
                    @if (config('app.address'))
                        <div class="flex items-start group subtle-hover glass-effect rounded-lg p-3 border-subtle">
                            <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center mr-3 mt-0.5">
                                <i class="mdi mdi-map-marker-outline text-blue-300 text-sm"></i>
                            </div>
                            <p class="text-subtle text-sm flex-1 opacity-90 group-hover:opacity-100 transition-opacity">
                                {{ config('app.address') }}
                            </p>
                        </div>
                    @endif

                    @if (config('app.phone_dial_code') && config('app.phone_number'))
                        <div class="flex items-center group subtle-hover glass-effect rounded-lg p-3 border-subtle">
                            <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center mr-3">
                                <i class="mdi mdi-phone-outline text-green-300 text-sm"></i>
                            </div>
                            <a href="tel:{{ config('app.phone_dial_code') }}{{ config('app.phone_number') }}"
                                class="text-subtle hover:text-white transition-colors text-sm font-normal opacity-90 group-hover:opacity-100 flex-1">
                                {{ config('app.phone_dial_code') }} {{ config('app.phone_number') }}
                            </a>
                        </div>
                    @endif

                    @if (config('app.email'))
                        <div class="flex items-center group subtle-hover glass-effect rounded-lg p-3 border-subtle">
                            <div class="w-8 h-8 bg-orange-500/20 rounded-lg flex items-center justify-center mr-3">
                                <i class="mdi mdi-email-outline text-orange-300 text-sm"></i>
                            </div>
                            <a href="mailto:{{ config('app.email') }}"
                                class="text-subtle hover:text-white transition-colors text-sm font-normal opacity-90 group-hover:opacity-100 flex-1">
                                {{ config('app.email') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Legal & Social Media -->
            <div class="lg:col-span-4">
                <div class="flex items-center mb-6">
                    <div class="w-6 h-0.5 bg-pink-400 rounded-full mr-3"></div>
                    <h4 class="text-lg font-medium text-white">{{ __('Legal & Social') }}</h4>
                </div>

                <!-- Legal Links -->
                <div class="grid grid-cols-1 gap-2 mb-6">
                    <a href="{{ route('admin.policies.terms-and-conditions') }}"
                        class="group subtle-hover glass-effect rounded-lg p-3 border-subtle flex items-center">
                        <div class="w-8 h-8 bg-amber-500/20 rounded-lg flex items-center justify-center mr-3">
                            <i class="mdi mdi-file-document-outline text-amber-300 text-sm"></i>
                        </div>
                        <span
                            class="text-subtle group-hover:text-white transition-colors text-sm font-normal opacity-90 group-hover:opacity-100 flex-1">
                            {{ __('Terms & Conditions') }}
                        </span>
                        <i
                            class="mdi mdi-chevron-right text-subtle text-xs group-hover:text-amber-300 group-hover:translate-x-0.5 transition-all duration-300"></i>
                    </a>

                    <a href="{{ route('admin.policies.policies-of-privacy') }}"
                        class="group subtle-hover glass-effect rounded-lg p-3 border-subtle flex items-center">
                        <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center mr-3">
                            <i class="mdi mdi-shield-account-outline text-blue-300 text-sm"></i>
                        </div>
                        <span
                            class="text-subtle group-hover:text-white transition-colors text-sm font-normal opacity-90 group-hover:opacity-100 flex-1">
                            {{ __('Privacy Policy') }}
                        </span>
                        <i
                            class="mdi mdi-chevron-right text-subtle text-xs group-hover:text-blue-300 group-hover:translate-x-0.5 transition-all duration-300"></i>
                    </a>

                    <a href="{{ route('admin.policies.policies-of-cookies') }}"
                        class="group subtle-hover glass-effect rounded-lg p-3 border-subtle flex items-center">
                        <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center mr-3">
                            <i class="mdi mdi-cookie-outline text-emerald-300 text-sm"></i>
                        </div>
                        <span
                            class="text-subtle group-hover:text-white transition-colors text-sm font-normal opacity-90 group-hover:opacity-100 flex-1">
                            {{ __('Cookie Policy') }}
                        </span>
                        <i
                            class="mdi mdi-chevron-right text-subtle text-xs group-hover:text-emerald-300 group-hover:translate-x-0.5 transition-all duration-300"></i>
                    </a>
                </div>

                <!-- Social Media Links -->
                <div>
                    <h5 class="text-sm font-medium mb-3 text-cyan-200 opacity-90 flex items-center">
                        <i class="mdi mdi-share-variant mr-2 text-xs"></i>
                        {{ __('Connect With Us') }}
                    </h5>
                    <div class="grid grid-cols-5 gap-2">
                        @php
                            $socials = [
                                'facebook' => [
                                    'color' => 'bg-blue-500/20',
                                    'icon' => 'mdi mdi-facebook',
                                    'hover' => 'text-blue-300',
                                ],
                                'instagram' => [
                                    'color' => 'bg-purple-500/20',
                                    'icon' => 'mdi mdi-instagram',
                                    'hover' => 'text-purple-300',
                                ],
                                'twitter' => [
                                    'color' => 'bg-sky-500/20',
                                    'icon' => 'mdi mdi-twitter',
                                    'hover' => 'text-sky-300',
                                ],
                                'linkedin' => [
                                    'color' => 'bg-blue-600/20',
                                    'icon' => 'mdi mdi-linkedin',
                                    'hover' => 'text-blue-400',
                                ],
                                'whatsapp' => [
                                    'color' => 'bg-green-500/20',
                                    'icon' => 'mdi mdi-whatsapp',
                                    'hover' => 'text-green-300',
                                ],
                                'telegram' => [
                                    'color' => 'bg-blue-400/20',
                                    'icon' => 'mdi mdi-send-variant',
                                    'hover' => 'text-blue-300',
                                ],
                                'tiktok' => [
                                    'color' => 'bg-gray-700/20',
                                    'icon' => 'fa-brands fa-tiktok',
                                    'hover' => 'text-gray-300',
                                ],
                                'youtube' => [
                                    'color' => 'bg-red-500/20',
                                    'icon' => 'mdi mdi-youtube',
                                    'hover' => 'text-red-300',
                                ],
                                'pinterest' => [
                                    'color' => 'bg-red-400/20',
                                    'icon' => 'mdi mdi-pinterest',
                                    'hover' => 'text-red-300',
                                ],
                            ];
                        @endphp

                        @foreach ($socials as $name => $data)
                            @if (config("app.$name"))
                                <a href="{{ config("app.$name") }}" target="_blank" rel="noopener"
                                    class="w-8 h-8 {{ $data['color'] }} rounded-lg flex items-center justify-center social-subtle group"
                                    title="{{ ucfirst($name) }}">
                                    <i
                                        class="  {{ $data['icon'] }} text-subtle text-xs group-hover:{{ $data['hover'] }} transition-colors"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        @if (config('app.values'))
            <div class="border-t border-white/10 pt-8 mb-8">
                <div class="text-center max-w-3xl mx-auto">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-8 h-0.5 bg-amber-400 rounded-full mr-3"></div>
                        <h4 class="text-base font-medium text-amber-200">{{ __('Our Values') }}</h4>
                        <div class="w-8 h-0.5 bg-amber-400 rounded-full ml-3"></div>
                    </div>
                    <p
                        class="text-subtle text-sm italic leading-relaxed glass-effect rounded-lg p-4 border-subtle opacity-90">
                        "{{ config('app.values') }}"
                    </p>
                </div>
            </div>
        @endif

        <!-- Bottom Bar -->
        <div class="border-t border-white/10 pt-6 flex flex-col lg:flex-row justify-between items-center">
            <div class="text-subtle text-xs mb-3 lg:mb-0 text-center lg:text-left opacity-80">
                <div class="flex flex-col lg:flex-row lg:items-center lg:space-x-4 space-y-1 lg:space-y-0">
                    <span>
                        &copy; {{ date('Y') }}
                        <span class="font-medium text-white">
                            {{ config('app.org_name', config('app.name', __('Our Company'))) }}
                        </span>
                        . {{ __('All rights reserved.') }}
                    </span>

                    @if (config('app.tax_id'))
                        <span class="hidden lg:inline text-white/60">•</span>
                        <span class="flex items-center justify-center lg:justify-start text-blue-200">
                            <i class="mdi mdi-card-account-details-outline mr-1 text-xs"></i>
                            {{ config('app.tax_id') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex items-center text-subtle text-xs opacity-80">
                <span class="mr-2">{{ __('Powered by') }}</span>
                <div class="glass-effect rounded-full px-3 py-1 border-subtle subtle-hover">
                    <div class="flex items-center">
                        <i class="mdi mdi-heart text-rose-300 mr-1 text-xs"></i>
                        <a class="font-medium text-white" href="https://t.me/elyerr">
                            @Elyerr
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
