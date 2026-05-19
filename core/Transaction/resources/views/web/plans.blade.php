@extends('layouts.pages')

@push('head')
    {!! config('seo.plans') !!}
@endpush

@push('css')
    <style nonce="{{ $nonce }}">
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 40px -12px rgba(0, 0, 0, 0.2);
        }

        .price-option {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .price-option.selected {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border: 1px solid transparent;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            transform: scale(1.02);
        }

        .price-option.selected .price-period,
        .price-option.selected .price-amount,
        .price-option.selected .price-currency {
            color: white !important;
        }

        .price-option.selected .price-icon {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .price-option.selected .price-icon i {
            color: white !important;
        }

        .price-option:not(.selected):hover {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            transform: translateX(4px);
        }

        .dark .price-option:not(.selected):hover {
            background: linear-gradient(135deg, #374151, #1f2937);
        }

        .select-plan-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .pagination .page-item {
            display: inline-block;
        }

        .pagination .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            height: 2.5rem;
            padding: 0 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            background-color: white;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .dark .pagination .page-link {
            background-color: #1f2937;
            color: #d1d5db;
            border-color: #374151;
        }

        .pagination .page-link:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
            transform: translateY(-2px);
        }

        .dark .pagination .page-link:hover {
            background-color: #374151;
            border-color: #4b5563;
        }

        .pagination .active .page-link {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: white;
            border-color: transparent;
        }

        .pagination .disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination .disabled .page-link:hover {
            transform: none;
        }

        .modal-animate {
            animation: modalSlideIn 0.3s ease-out;
        }

        .service-item {
            transition: all 0.2s ease;
        }

        .service-item:hover {
            background-color: rgba(59, 130, 246, 0.05);
            transform: translateX(4px);
        }

        .modal-details-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .modal-details-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .payment-method.selected {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border: 1px solid transparent;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .payment-method.selected .text-gray-900,
        .payment-method.selected .font-semibold,
        .payment-method.selected .text-sm {
            color: white !important;
        }

        .payment-method.selected .text-gray-500,
        .payment-method.selected .text-xs {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .payment-method.selected .bg-blue-100 {
            background-color: rgba(255, 255, 255, 0.2) !important;
        }

        .payment-method.selected i {
            color: white !important;
        }
    </style>
@endpush

@section('header')
    @include('pages.layouts.header')
@endsection

@section('content')
    <div
        class="min-h-screen bg-linear-to-br from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">

        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-linear-to-r from-blue-600 to-indigo-700 text-white">
            <div class="absolute inset-0 opacity-20">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                    <path fill="currentColor" fill-opacity="1"
                        d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                    </path>
                </svg>
            </div>
            <div class="container mx-auto px-4 py-20 relative z-10">
                <div class="text-center fade-in-up">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('Available Plans') }}</h1>
                    <p class="text-lg md:text-xl text-blue-100 max-w-2xl mx-auto">
                        {{ __('Choose the perfect plan for your needs. All plans include premium support and quality guarantee.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Plans Section -->
        <div class="container mx-auto px-4 py-16 -mt-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($data as $index => $plan)
                    <div
                        class="plan-card fade-in-up bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden card-hover flex flex-col">
                        <!-- Header -->
                        <div
                            class="bg-linear-to-r from-gray-800 to-gray-900 dark:from-gray-900 dark:to-gray-950 px-6 py-3 text-white">
                            <h3 class="text-xl font-bold">{{ $plan['name'] }}</h3>

                            <div class="flex flex-wrap gap-2 mt-3">
                                @if ($plan['bonus_enabled'] && $plan['bonus_duration'] > 0)
                                    <span
                                        class="inline-flex items-center gap-1 bg-purple-500/20 text-purple-300 px-2 py-0.5 rounded-lg text-xs">
                                        <i class="mdi mdi-gift text-xs"></i>
                                        {{ $plan['bonus_duration'] }} {{ __('days bonus') }}
                                    </span>
                                @endif

                                @if ($plan['trial_enabled'] && $plan['trial_duration'] > 0)
                                    <span
                                        class="inline-flex items-center gap-1 bg-blue-500/20 text-blue-300 px-2 py-0.5 rounded-lg text-xs">
                                        <i class="mdi mdi-clock-outline text-xs"></i>
                                        {{ $plan['trial_duration'] }} {{ __('days trial') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Prices Section -->
                        <div class="p-6 flex-1">
                            @if (isset($plan['prices']) && count($plan['prices']) > 0)
                                <div class="space-y-3">
                                    <h4
                                        class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                                        {{ __('Select a billing period') }}
                                    </h4>

                                    @foreach ($plan['prices'] as $price)
                                        <button type="button"
                                            class="price-option w-full flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl transition-all duration-200"
                                            data-price-id="{{ $price['id'] }}" data-plan-id="{{ $plan['id'] }}">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="price-icon w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center transition-all">
                                                    <i class="mdi mdi-currency-usd text-blue-600 dark:text-blue-400"></i>
                                                </div>
                                                <div>
                                                    @php
                                                        $period = collect($billing_periods)
                                                            ->where('id', $price['billing_period'])
                                                            ->first();
                                                    @endphp

                                                    <span
                                                        class="price-period text-sm font-semibold text-gray-900 dark:text-white capitalize">
                                                        {{ $period['name'] }}
                                                    </span>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ __('billing period') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <span class="price-amount text-xl font-bold text-gray-900 dark:text-white">
                                                    {{ number_format($price['amount'] / 100, 2) }}
                                                </span>
                                                <span class="price-currency text-sm text-gray-500 dark:text-gray-400 ml-1">
                                                    {{ $price['currency'] }}
                                                </span>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="mdi mdi-tag-outline text-5xl text-gray-400 mb-3 block"></i>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('No prices available') }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Footer with Button -->
                        <div class="p-6 pt-0">
                            <button
                                class="select-plan-btn w-full bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-semibold py-3 px-4 rounded-xl transition-all duration-200"
                                disabled id="plan-detail-{{ $plan->id }}">
                                <i class="mdi mdi-lock text-sm mr-2"></i>
                                {{ __('Select a price first') }}
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-12">
                            <i class="mdi mdi-package-variant-closed text-6xl text-gray-400 mb-4 block"></i>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                {{ __('No plans available') }}
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400">
                                {{ __('Please check back later or contact support.') }}
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if (method_exists($data, 'links'))
                <div class="mt-12">
                    {{ $data->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal - Invoice Style -->
    <div id="modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-all" id="modalOverlay"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div
                class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-full lg:max-w-4xl modal-animate overflow-hidden">

                <!-- Modal Header - Invoice Header -->
                <div class="bg-linear-to-r from-gray-900 to-gray-800 dark:from-gray-950 dark:to-gray-900 px-8 py-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2 text-blue-400 text-sm mb-2">
                                <i class="mdi mdi-receipt text-lg"></i>
                                <span>{{ __('ORDER SUMMARY') }}</span>
                            </div>
                            <h3 class="text-3xl font-bold text-white" id="modalPlanName"></h3>
                            <p class="text-gray-400 text-sm mt-1">{{ __('Review your purchase details below') }}</p>
                        </div>
                        <button type="button" id="closeModalBtn"
                            class="text-gray-400 hover:text-white transition-colors p-1">
                            <i class="mdi mdi-close text-2xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body - Invoice Content -->
                <div class="p-8 max-h-[80vh] overflow-y-auto">
                    <!-- Price Section - Hero Price -->
                    <div class="mb-8 text-center border-b border-gray-200 dark:border-gray-700 pb-6">
                        <span
                            class="text-sm text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Total to
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            pay') }}</span>
                        <div class="flex items-baseline justify-center gap-2 mt-2">
                            <span id="modalPriceCurrency"
                                class="text-2xl font-bold text-gray-600 dark:text-gray-400">USD</span>
                            <span id="modalPriceAmount"
                                class="text-7xl lg:text-8xl font-bold bg-linear-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">0.00</span>
                            <span id="modalPricePeriod" class="text-gray-500 dark:text-gray-400 text-base">/
                                monthly</span>
                        </div>
                    </div>

                    <!-- Plan Description - HTML allowed -->
                    <div class="mb-6 bg-blue-50/50 dark:bg-blue-900/20 rounded-xl p-5 border-l-4 border-blue-500">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="mdi mdi-information-outline text-blue-500 text-lg"></i>
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200">{{ __('Plan Description') }}</h4>
                        </div>
                        <div id="modalDescription"
                            class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed prose prose-sm max-w-none">
                            <!-- HTML description will be inserted here -->
                        </div>
                    </div>

                    <!-- Services Included - Features List -->
                    <div class="mb-6">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="mdi mdi-check-circle text-green-500 text-xl"></i>
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200">{{ __("What's Included") }}</h4>
                            <span class="text-xs text-gray-500 ml-auto">{{ __('All features enabled') }}</span>
                        </div>
                        <div id="modalServicesContainer" class="space-y-3">
                            <!-- Services will be dynamically inserted here -->
                        </div>
                    </div>

                    <!-- Bonus & Trial Row -->
                    <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div id="modalBonusContainer" class="hidden">
                            <div
                                class="bg-linear-to-r from-amber-50 to-orange-50 dark:from-amber-900/10 dark:to-orange-900/10 rounded-xl p-4 border border-amber-200 dark:border-amber-800/30">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                                        <i class="mdi mdi-gift text-amber-600 dark:text-amber-400 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-amber-800 dark:text-amber-300">{{ __('Bonus Included') }}
                                        </p>
                                        <p class="text-sm text-amber-700 dark:text-amber-400">
                                            <span id="modalBonusDays"></span> {{ __('extra days completely free') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="modalTrialContainer" class="hidden">
                            <div
                                class="bg-linear-to-r from-blue-50 to-cyan-50 dark:from-blue-900/10 dark:to-cyan-900/10 rounded-xl p-4 border border-blue-200 dark:border-blue-800/30">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <i class="mdi mdi-clock-outline text-blue-600 dark:text-blue-400 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-blue-800 dark:text-blue-300">{{ __('Trial Period') }}</p>
                                        <p class="text-sm text-blue-700 dark:text-blue-400">
                                            <span id="modalTrialDays"></span> {{ __('days free trial') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Summary - Invoice Table -->
                    <div class="mb-6 bg-gray-100/50 dark:bg-gray-700/30 rounded-xl overflow-hidden">
                        <div class="bg-gray-200/50 dark:bg-gray-700/50 px-5 py-3">
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                <i class="mdi mdi-receipt text-blue-500"></i>
                                {{ __('Invoice Summary') }}
                            </h4>
                        </div>
                        <div class="p-5 space-y-3">
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('Plan name') }}</span>
                                <span id="modalBillingPlan" class="font-semibold text-gray-900 dark:text-white"></span>
                            </div>
                            <div
                                class="flex justify-between items-center py-2 border-t border-gray-200 dark:border-gray-600">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('Billing cycle') }}</span>
                                <span id="modalBillingPeriod"
                                    class="font-semibold text-gray-900 dark:text-white capitalize"></span>
                            </div>
                            <div
                                class="flex justify-between items-center py-2 border-t border-gray-200 dark:border-gray-600">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('Subtotal') }}</span>
                                <span id="modalBillingSubtotal"
                                    class="font-semibold text-gray-900 dark:text-white"></span>
                            </div>
                            <div
                                class="flex justify-between items-center py-2 border-t border-gray-200 dark:border-gray-600">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('Taxes & fees') }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ __('Included') }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-3 mt-2 bg-linear-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg px-3 -mx-1">
                                <span class="font-bold text-gray-900 dark:text-white">{{ __('Total due today') }}</span>
                                <span id="modalBillingTotal"
                                    class="text-xl font-bold text-blue-600 dark:text-blue-400"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods Section -->
                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="mdi mdi-credit-card-multiple text-blue-500 text-xl"></i>
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200">{{ __('Select Payment Method') }}
                            </h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($payment_methods as $method)
                                <button type="button" data-method="{{ $method['key'] }}"
                                    class="payment-method p-4 rounded-2xl bg-gray-200 dark:bg-gray-700 cursor-pointer transition-all">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <i
                                                class="mdi {{ $method['icon'] }} text-blue-600 dark:text-blue-400 text-lg"></i>
                                        </div>
                                        <div>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ __($method['name']) }}
                                            </span>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ __('Secure checkout') }}
                                            </p>
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Secure Notice -->
                    <div class="mt-6 text-center pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-1"><i class="mdi mdi-shield-check text-green-500"></i>
                                {{ __('Secure payment') }}</span>
                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                            <span class="flex items-center gap-1"><i class="mdi mdi-lock"></i>
                                {{ __('SSL encrypted') }}</span>
                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                            <span class="flex items-center gap-1"><i class="mdi mdi-credit-card"></i>
                                {{ __('All major cards') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer - Actions -->
                <div
                    class="bg-gray-100 dark:bg-gray-900/50 px-8 py-4 flex gap-3 justify-end border-t border-gray-200 dark:border-gray-700">
                    <button type="button" id="cancelModalBtn"
                        class="px-5 py-2 cursor-pointer bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all border border-gray-300 dark:border-gray-600">
                        {{ __('Cancel') }}
                    </button>
                    <button type="button" id="confirmBtn" disabled
                        class="px-6 py-2 rounded bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                        <i class="mdi mdi-check-circle mr-1"></i>
                        {{ __('Confirm Order') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include('pages.layouts.footer')
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        const plans = @json($data);
        const billing_periods = @json($billing_periods);
        let plan_selected = '';
        let price_selected = '';
        let method = ''

        // Define button classes for active and disabled states
        const activeButtonClasses = 'bg-blue-600 text-white hover:bg-blue-700 cursor-pointer';
        const disabledButtonClasses = 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed';

        const modal = $('#modal');
        $('.payment-method').on('click', chooseMethod);

        function chooseMethod() {
            method = $(this).data('method');
            $('.payment-method').removeClass('selected');
            $(this).addClass('selected');

            // Enable btn confirm payment
            if (method != '') {
                $("#confirmBtn").removeClass(disabledButtonClasses)
                $("#confirmBtn").addClass(activeButtonClasses)
                $("#confirmBtn").prop('disabled', false)
            }
        }

        // make payment 
        $('#confirmBtn').on('click', function(e) {
            e.preventDefault()
            continuePayment()
        });

        // Close modal on cancel, close button, or overlay click
        $("#cancelModalBtn, #closeModalBtn, #modalOverlay").on('click', function() {
            modal.addClass('hidden');
        });

        document.addEventListener("DOMContentLoaded", function() {
            // Handle price option click
            $('.price-option').on('click', function() {
                // Remove selected class from all options and add to the clicked one
                $('.price-option').removeClass('selected');
                $(this).addClass('selected');

                // Enable the select plan button for the corresponding plan
                SelectPlan($(this).data('planId'), $(this).data('priceId'));

            });
        })

        // Function to find the billing period based on its ID
        function findPeriod() {
            return billing_periods.find(p => p.id === price_selected.billing_period) || {
                name: ''
            };
        }

        /** * Function to handle plan and price selection
         * @param {string} planId - The ID of the selected plan
         * @param {string} priceId - The ID of the selected price
         */
        function SelectPlan(planId, priceId) {
            // Find the selected plan and price from the data
            plan_selected = plans.data.find(p => p.id === planId) || '';
            price_selected = plan_selected.prices.find(pr => pr.id === priceId) || '';

            // Disable all buttons first and reset their text
            $('.select-plan-btn').each(function(btn) {
                $(this).addClass(disabledButtonClasses);
                $(this).removeClass(activeButtonClasses);
                $(this).prop('disabled', true);
                $(this).html('<i class="mdi mdi-lock text-sm mr-2"></i>{{ __('Select a price first') }}');
            });

            // Enable the button and update its text
            const button = $('#plan-detail-' + planId);
            button.prop('disabled', false);
            button.removeClass(disabledButtonClasses);
            button.addClass(activeButtonClasses);
            button.html('<i class="mdi mdi-lock-open text-sm mr-2"></i>{{ __('Proceed to checkout') }}');

            // open modal on button click
            button.off('click').on('click', function() {
                loadModalData();
                modal.removeClass('hidden');
            });
        }

        /**
         * Function to load selected plan and price details into the modal
         */
        function loadModalData() {
            // Populate modal with selected plan and price details
            $('#modalPlanName').text(plan_selected.name);
            $('#modalPriceAmount').text((price_selected.amount / 100).toFixed(2));
            $('#modalPriceCurrency').text(price_selected.currency);

            $('#modalServicesContainer').empty();

            plan_selected.scopes.forEach(scope => {
                $('#modalServicesContainer').append(`
                    <div class="service-item bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 mb-3">
                        <div class="mb-2 pb-2 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="font-bold text-gray-800 dark:text-white">${scope.service.group.name}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">${scope.service.group.description}</p>
                        </div>
                        
                        <div class="flex items-start gap-2 mb-3">
                            <i class="mdi mdi-check-circle text-green-500 mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold text-gray-800 dark:text-white">${scope.service.name}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">${scope.service.description}</p>
                            </div>
                        </div>
                        
                        <div class="ml-6 pl-3 border-l-2 border-blue-300 dark:border-blue-700">
                            <div class="flex items-center gap-2">
                                <i class="mdi mdi-account-tie text-blue-500 text-sm"></i>
                                <span class="font-medium text-gray-700 dark:text-gray-300">${scope.role.name}</span>
                                <span class="text-xs text-gray-400">GSR ID: ${scope.gsr_id}</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">${scope.role.description}</p>
                        </div>
                    </div>
                `);
            });

            const period = findPeriod();
            $('#modalPricePeriod').text('/ ' + period.name);
            $('#modalDescription').html(plan_selected.description || '{{ __('No description available.') }}');
            $('#modalBillingPlan').text(plan_selected.name);
            $('#modalBillingPeriod').text(period.name);
            $('#modalBillingSubtotal').text((price_selected.amount / 100).toFixed(2) + ' ' + price_selected.currency);

            // Bonus & Trial
            if (plan_selected.bonus_enabled && plan_selected.bonus_duration > 0) {
                $('#modalBonusDays').text(plan_selected.bonus_duration);
                $('#modalBonusContainer').removeClass('hidden');
            } else {
                $('#modalBonusContainer').addClass('hidden');
            }
        }

        function getReferralLink() {
            const params = new URLSearchParams(window.location.search);
            return params.get("referral_code");
        }

        async function continuePayment() {
            const btn = $('#confirmBtn')
            btn.prop('disabled', true);
            btn.html(
                `<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                {{ __('Processing...') }}
                `);

            try {
                const res = await $server.post('/system/transaction/subscriptions', {
                    plan_id: plan_selected.id,
                    billing_period: price_selected.billing_period,
                    payment_method: method,
                    referral_code: getReferralLink(),
                });

                if (res.status == 201) {
                    window.location.href = res.data.data.redirect_to;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                $('#confirmBtn').prop('disabled', false);
            }
        }
    </script>
@endpush
