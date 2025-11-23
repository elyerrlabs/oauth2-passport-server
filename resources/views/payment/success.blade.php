@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Payment Successful')])
@endsection

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <!-- Success Confetti Animation -->
        <div class="confetti-container"></div>

        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <!-- Success Header -->
            <div
                class="bg-gradient-to-r from-green-600 to-emerald-700 dark:from-green-700 dark:to-emerald-800 py-10 px-6 text-center">
                <div class="flex justify-center mb-5">
                    <div class="bg-white/20 p-4 rounded-full shadow-lg animate-pulse">
                        <i class="mdi mdi-check-circle-outline text-6xl text-white"></i>
                    </div>
                </div>
                <h1 class="text-4xl font-bold text-white mb-3">{{ __('Payment Successful!') }}</h1>
                <p class="text-green-100 dark:text-green-200 text-xl font-medium max-w-2xl mx-auto">
                    {{ __('Thank you for your purchase. Your transaction has been completed successfully.') }}
                </p>
            </div>

            <!-- Transaction Info -->
            <div class="p-8 bg-gray-50 dark:bg-gray-900">
                <h2
                    class="text-2xl font-bold text-gray-900 dark:text-white mb-7 pb-3 border-b border-gray-300 dark:border-gray-600 flex items-center">
                    <i class="mdi mdi-receipt text-indigo-600 dark:text-indigo-400 mr-3 text-2xl"></i>
                    {{ __('Transaction Details') }}
                </h2>

                <div
                    class="space-y-5 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-600">
                        <span class="font-semibold text-gray-800 dark:text-gray-200 text-lg flex items-center">
                            <i class="mdi mdi-barcode mr-2 text-indigo-500 dark:text-indigo-400"></i>
                            {{ __('Transaction Code') }}
                        </span>
                        <span
                            class="font-mono text-indigo-700 dark:text-indigo-300 font-bold text-lg">{{ $transaction->code }}</span>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-600">
                        <span class="font-semibold text-gray-800 dark:text-gray-200 text-lg flex items-center">
                            <i class="mdi mdi-status-up mr-2 text-indigo-500 dark:text-indigo-400"></i>
                            {{ __('Status') }}
                        </span>
                        <span
                            class="px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 font-semibold rounded-full capitalize text-md">
                            {{ $transaction->status }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-600">
                        <span class="font-semibold text-gray-800 dark:text-gray-200 text-lg flex items-center">
                            <i class="mdi mdi-currency-sign mr-2 text-indigo-500 dark:text-indigo-400"></i>
                            {{ __('Currency') }}
                        </span>
                        <span
                            class="font-medium text-gray-900 dark:text-white text-lg">{{ strtoupper($transaction->currency) }}</span>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-600">
                        <span class="font-semibold text-gray-800 dark:text-gray-200 text-lg flex items-center">
                            <i class="mdi mdi-credit-card mr-2 text-indigo-500 dark:text-indigo-400"></i>
                            {{ __('Payment Method') }}
                        </span>
                        <span
                            class="font-medium text-gray-900 dark:text-white text-lg">{{ ucfirst($transaction->payment_method) }}</span>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-600">
                        <span class="font-semibold text-gray-800 dark:text-gray-200 text-lg flex items-center">
                            <i class="mdi mdi-calendar-clock mr-2 text-indigo-500 dark:text-indigo-400"></i>
                            {{ __('Billing Period') }}
                        </span>
                        <span
                            class="font-medium text-gray-900 dark:text-white text-lg">{{ billing_get_period($transaction->billing_period)['name'] }}</span>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-600">
                        <span class="font-semibold text-gray-800 dark:text-gray-200 text-lg flex items-center">
                            <i class="mdi mdi-autorenew mr-2 text-indigo-500 dark:text-indigo-400"></i>
                            {{ __('Auto Renewal') }}
                        </span>
                        <span
                            class="font-medium text-gray-900 dark:text-white text-lg">{{ $transaction->renew ? __('Yes') : __('No') }}</span>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-600">
                        <span class="font-semibold text-gray-800 dark:text-gray-200 text-lg flex items-center">
                            <i class="mdi mdi-clock-outline mr-2 text-indigo-500 dark:text-indigo-400"></i>
                            {{ __('Date & Time') }}
                        </span>
                        <span class="js-local-datetime text-gray-900 dark:text-white"
                            data-datetime="{{ $transaction->created_at->toIso8601String() }}">
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-3">
                        <span class="font-semibold text-gray-800 dark:text-gray-200 text-lg flex items-center">
                            <i class="mdi mdi-cash mr-2 text-indigo-500 dark:text-indigo-400"></i>
                            {{ __('Total Amount') }}
                        </span>
                        <span class="text-3xl font-bold text-indigo-700 dark:text-indigo-300">
                            {{ number_format($transaction->total / 100, 2) }} {{ strtoupper($transaction->currency) }}
                        </span>
                    </div>
                </div>

                <!-- Receipt -->
                @if ($transaction->payment_url)
                    <div
                        class="mt-8 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-300 dark:border-blue-700 rounded-xl p-7 shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center justify-between">
                            <div class="flex items-center mb-4 md:mb-0">
                                <i class="mdi mdi-receipt text-blue-600 dark:text-blue-400 text-5xl mr-5"></i>
                                <div>
                                    <p class="font-bold text-blue-900 dark:text-blue-100 text-xl">
                                        {{ __('Payment Receipt Available') }}</p>
                                    <p class="text-blue-700 dark:text-blue-300 font-medium">
                                        {{ __('You can view and download your receipt.') }}</p>
                                </div>
                            </div>
                            <a href="{{ $transaction->payment_url }}" target="_blank"
                                class="inline-flex items-center bg-blue-700 hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="mdi mdi-open-in-new mr-2 text-lg"></i>
                                {{ __('View Receipt') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div
                class="px-8 py-8 flex flex-col sm:flex-row gap-5 justify-center border-t border-gray-300 dark:border-gray-600">
                <a href="{{ route('user.dashboard') }}"
                    class="inline-flex items-center justify-center text-white bg-indigo-700 hover:bg-indigo-800 dark:bg-indigo-600 dark:hover:bg-indigo-700 font-semibold px-8 py-4 rounded-lg transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="mdi mdi-home-outline mr-3 text-xl"></i>
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style nonce="{{ $nonce }}">
        .confetti-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1000;
            overflow: hidden;
        }

        .confetti {
            position: absolute;
            width: 12px;
            height: 12px;
            opacity: 0;
            animation: confetti-fall 5s linear forwards;
        }

        @keyframes confetti-fall {
            0% {
                opacity: 1;
                transform: translateY(-100px) rotate(0deg);
            }

            100% {
                opacity: 0;
                transform: translateY(100vh) rotate(720deg);
            }
        }

        /* Improved readability */
        body {
            color: #1f2937;
            /* gray-800 */
            background-color: #f9fafb;
            /* gray-50 */
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        @media (prefers-color-scheme: dark) {
            body {
                color: #f9fafb;
                /* gray-50 */
                background-color: #111827;
                /* gray-900 */
            }
        }

        a:focus,
        button:focus {
            outline: 2px solid #4f46e5;
            /* indigo-600 */
            outline-offset: 2px;
        }
    </style>
@endpush

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            // Create confetti animation
            const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#f9c74f', '#ffafcc', '#83e377'];
            const container = document.querySelector('.confetti-container');

            for (let i = 0; i < 80; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDelay = Math.random() * 3 + 's';
                confetti.style.width = Math.random() * 12 + 8 + 'px';
                confetti.style.height = Math.random() * 12 + 8 + 'px';
                confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                container.appendChild(confetti);
            }

            document.querySelectorAll(".js-local-datetime").forEach(el => {
                const iso = el.dataset.datetime;
                const date = new Date(iso);
                const lang = navigator.language || 'en-US';

                el.textContent = date.toLocaleString(lang, {
                    weekday: "short",
                    year: "numeric",
                    month: "short",
                    day: "numeric",
                    hour: "2-digit",
                    minute: "2-digit",
                    second: "2-digit"
                });
            });
        });
    </script>
@endpush
