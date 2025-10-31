@push('css')
    <style>
        .universal-gradient {
            background: linear-gradient(135deg, #1f2937 0%, #374151 50%, #4b5563 100%);
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

        .universal-glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .universal-hover {
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .universal-hover:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .social-universal {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
        }

        .social-universal:hover {
            transform: translateY(-2px) scale(1.05);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .text-universal {
            color: #e5e7eb;
        }

        .border-universal {
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .icon-universal {
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .accent-color-1 {
            background: rgba(59, 130, 246, 0.2);
        }

        .accent-color-2 {
            background: rgba(16, 185, 129, 0.2);
        }

        .accent-color-3 {
            background: rgba(245, 158, 11, 0.2);
        }

        .accent-color-4 {
            background: rgba(139, 92, 246, 0.2);
        }

        .accent-color-5 {
            background: rgba(239, 68, 68, 0.2);
        }

        .text-accent-1 {
            color: #60a5fa;
        }

        .text-accent-2 {
            color: #34d399;
        }

        .text-accent-3 {
            color: #fbbf24;
        }

        .text-accent-4 {
            color: #a78bfa;
        }

        .text-accent-5 {
            color: #f87171;
        }
    </style>
@endpush

<footer id="footer" class="universal-gradient text-white pt-16 pb-8 relative overflow-hidden {{ $hidden ?? '' }}">
    <!-- Background Elements Sutiles -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 left-10 w-32 h-32 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-40 h-40 bg-gray-300 rounded-full blur-3xl"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
            <!-- Organization Information -->
            <div class="lg:col-span-5">
                <div class="flex items-center mb-8">
                    @if (config('app.org_name'))
                        <div class="relative group">
                            <div
                                class="w-14 h-14 universal-glass rounded-xl flex items-center justify-center mr-4 universal-hover">
                                <i class="mdi mdi-domain text-white text-xl icon-universal"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-semibold text-white mb-1">
                                {{ config('app.org_name') }}
                            </h3>
                            @if (config('app.name') && config('app.name') != config('app.org_name'))
                                <p class="text-universal text-sm opacity-80">{{ config('app.name') }}</p>
                            @endif
                        </div>
                    @endif
                </div>

                @if (config('app.description'))
                    <p class="text-universal leading-relaxed text-base mb-8 opacity-90">
                        {{ config('app.description') }}
                    </p>
                @endif

                <!-- Mission & Vision Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if (config('app.mission'))
                        <div class="universal-glass rounded-xl p-4 border-universal universal-hover group">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 accent-color-2 rounded-lg flex items-center justify-center mr-3">
                                    <i class="mdi mdi-bullseye-arrow text-accent-2 text-sm"></i>
                                </div>
                                <h4 class="font-medium text-accent-2 text-sm">{{ __('Our Mission') }}</h4>
                            </div>
                            <p
                                class="text-universal text-xs leading-relaxed opacity-90 group-hover:opacity-100 transition-opacity">
                                {{ config('app.mission') }}
                            </p>
                        </div>
                    @endif

                    @if (config('app.vision'))
                        <div class="universal-glass rounded-xl p-4 border-universal universal-hover group">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 accent-color-4 rounded-lg flex items-center justify-center mr-3">
                                    <i class="mdi mdi-eye-outline text-accent-4 text-sm"></i>
                                </div>
                                <h4 class="font-medium text-accent-4 text-sm">{{ __('Our Vision') }}</h4>
                            </div>
                            <p
                                class="text-universal text-xs leading-relaxed opacity-90 group-hover:opacity-100 transition-opacity">
                                {{ config('app.vision') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div class="lg:col-span-3">
                <div class="flex items-center mb-6">
                    <div class="w-6 h-0.5 bg-accent-1 rounded-full mr-3"></div>
                    <h4 class="text-lg font-medium text-white">{{ __('Contact Us') }}</h4>
                </div>

                <div class="space-y-3">
                    @if (config('app.address'))
                        <div
                            class="flex items-start group universal-hover universal-glass rounded-lg p-3 border-universal">
                            <div class="w-8 h-8 accent-color-1 rounded-lg flex items-center justify-center mr-3 mt-0.5">
                                <i class="mdi mdi-map-marker-outline text-accent-1 text-sm"></i>
                            </div>
                            <p
                                class="text-universal text-sm flex-1 opacity-90 group-hover:opacity-100 transition-opacity">
                                {{ config('app.address') }}
                            </p>
                        </div>
                    @endif

                    @if (config('app.phone_dial_code') && config('app.phone_number'))
                        <div
                            class="flex items-center group universal-hover universal-glass rounded-lg p-3 border-universal">
                            <div class="w-8 h-8 accent-color-2 rounded-lg flex items-center justify-center mr-3">
                                <i class="mdi mdi-phone-outline text-accent-2 text-sm"></i>
                            </div>
                            <a href="tel:{{ config('app.phone_dial_code') }}{{ config('app.phone_number') }}"
                                class="text-universal hover:text-white transition-colors text-sm font-normal opacity-90 group-hover:opacity-100 flex-1">
                                {{ config('app.phone_dial_code') }} {{ config('app.phone_number') }}
                            </a>
                        </div>
                    @endif

                    @if (config('app.email'))
                        <div
                            class="flex items-center group universal-hover universal-glass rounded-lg p-3 border-universal">
                            <div class="w-8 h-8 accent-color-3 rounded-lg flex items-center justify-center mr-3">
                                <i class="mdi mdi-email-outline text-accent-3 text-sm"></i>
                            </div>
                            <a href="mailto:{{ config('app.email') }}"
                                class="text-universal hover:text-white transition-colors text-sm font-normal opacity-90 group-hover:opacity-100 flex-1">
                                {{ config('app.email') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Legal & Social Media -->
            <div class="lg:col-span-4">
                <div class="flex items-center mb-6">
                    <div class="w-6 h-0.5 bg-accent-4 rounded-full mr-3"></div>
                    <h4 class="text-lg font-medium text-white">{{ __('Legal & Social') }}</h4>
                </div>

                <!-- Legal Links -->
                <div class="grid grid-cols-1 gap-2 mb-6">
                    <a href="{{ route('admin.policies.terms-and-conditions') }}"
                        class="group universal-hover universal-glass rounded-lg p-3 border-universal flex items-center">
                        <div class="w-8 h-8 accent-color-3 rounded-lg flex items-center justify-center mr-3">
                            <i class="mdi mdi-file-document-outline text-accent-3 text-sm"></i>
                        </div>
                        <span
                            class="text-universal group-hover:text-white transition-colors text-sm font-normal opacity-90 group-hover:opacity-100 flex-1">
                            {{ __('Terms & Conditions') }}
                        </span>
                        <i
                            class="mdi mdi-chevron-right text-universal text-xs group-hover:text-accent-3 group-hover:translate-x-0.5 transition-all duration-300"></i>
                    </a>

                    <a href="{{ route('admin.policies.policies-of-privacy') }}"
                        class="group universal-hover universal-glass rounded-lg p-3 border-universal flex items-center">
                        <div class="w-8 h-8 accent-color-1 rounded-lg flex items-center justify-center mr-3">
                            <i class="mdi mdi-shield-account-outline text-accent-1 text-sm"></i>
                        </div>
                        <span
                            class="text-universal group-hover:text-white transition-colors text-sm font-normal opacity-90 group-hover:opacity-100 flex-1">
                            {{ __('Privacy Policy') }}
                        </span>
                        <i
                            class="mdi mdi-chevron-right text-universal text-xs group-hover:text-accent-1 group-hover:translate-x-0.5 transition-all duration-300"></i>
                    </a>

                    <a href="{{ route('admin.policies.policies-of-cookies') }}"
                        class="group universal-hover universal-glass rounded-lg p-3 border-universal flex items-center">
                        <div class="w-8 h-8 accent-color-2 rounded-lg flex items-center justify-center mr-3">
                            <i class="mdi mdi-cookie-outline text-accent-2 text-sm"></i>
                        </div>
                        <span
                            class="text-universal group-hover:text-white transition-colors text-sm font-normal opacity-90 group-hover:opacity-100 flex-1">
                            {{ __('Cookie Policy') }}
                        </span>
                        <i
                            class="mdi mdi-chevron-right text-universal text-xs group-hover:text-accent-2 group-hover:translate-x-0.5 transition-all duration-300"></i>
                    </a>
                </div>

                <!-- Social Media Links -->
                <div>
                    <h5 class="text-sm font-medium mb-3 text-accent-1 opacity-90 flex items-center">
                        <i class="mdi mdi-share-variant mr-2 text-xs"></i>
                        {{ __('Connect With Us') }}
                    </h5>
                    <div class="grid grid-cols-5 gap-2">
                        @php
                            $socials = [
                                'facebook' => [
                                    'color' => 'accent-color-1',
                                    'icon' => 'mdi mdi-facebook',
                                    'hover' => 'text-accent-1',
                                ],
                                'instagram' => [
                                    'color' => 'accent-color-4',
                                    'icon' => 'mdi mdi-instagram',
                                    'hover' => 'text-accent-4',
                                ],
                                'twitter' => [
                                    'color' => 'accent-color-1',
                                    'icon' => 'mdi mdi-twitter',
                                    'hover' => 'text-accent-1',
                                ],
                                'linkedin' => [
                                    'color' => 'accent-color-1',
                                    'icon' => 'mdi mdi-linkedin',
                                    'hover' => 'text-accent-1',
                                ],
                                'whatsapp' => [
                                    'color' => 'accent-color-2',
                                    'icon' => 'mdi mdi-whatsapp',
                                    'hover' => 'text-accent-2',
                                ],
                                'telegram' => [
                                    'color' => 'accent-color-1',
                                    'icon' => 'mdi mdi-send-variant',
                                    'hover' => 'text-accent-1',
                                ],
                                'tiktok' => [
                                    'color' => 'accent-color-5',
                                    'icon' => 'fa-brands fa-tiktok',
                                    'hover' => 'text-accent-5',
                                ],
                                'youtube' => [
                                    'color' => 'accent-color-5',
                                    'icon' => 'mdi mdi-youtube',
                                    'hover' => 'text-accent-5',
                                ],
                                'pinterest' => [
                                    'color' => 'accent-color-5',
                                    'icon' => 'mdi mdi-pinterest',
                                    'hover' => 'text-accent-5',
                                ],
                            ];
                        @endphp

                        @foreach ($socials as $name => $data)
                            @if (config("app.$name"))
                                <a href="{{ config("app.$name") }}" target="_blank" rel="noopener"
                                    class="w-8 h-8 {{ $data['color'] }} rounded-lg flex items-center justify-center social-universal group"
                                    title="{{ ucfirst($name) }}">
                                    <i
                                        class="{{ $data['icon'] }} text-universal text-xs group-hover:{{ $data['hover'] }} transition-colors"></i>
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
                        <div class="w-8 h-0.5 bg-accent-3 rounded-full mr-3"></div>
                        <h4 class="text-base font-medium text-accent-3">{{ __('Our Values') }}</h4>
                        <div class="w-8 h-0.5 bg-accent-3 rounded-full ml-3"></div>
                    </div>
                    <p
                        class="text-universal text-sm italic leading-relaxed universal-glass rounded-lg p-4 border-universal opacity-90">
                        "{{ config('app.values') }}"
                    </p>
                </div>
            </div>
        @endif

        <!-- Bottom Bar -->
        <div class="border-t border-white/10 pt-6 flex flex-col lg:flex-row justify-between items-center">
            <div class="text-universal text-xs mb-3 lg:mb-0 text-center lg:text-left opacity-80">
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
                        <span class="flex items-center justify-center lg:justify-start text-accent-1">
                            <i class="mdi mdi-card-account-details-outline mr-1 text-xs"></i>
                            {{ config('app.tax_id') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex items-center text-universal text-xs opacity-80">
                <span class="mr-2">{{ __('Powered by') }}</span>
                <div class="universal-glass rounded-full px-3 py-1 border-universal universal-hover">
                    <div class="flex items-center">
                        <i class="mdi mdi-heart text-accent-5 mr-1 text-xs"></i>
                        <a class="font-medium text-white hover:text-accent-5 transition-colors"
                            href="https://t.me/elyerr" target="_blank">
                            @Elyerr
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
