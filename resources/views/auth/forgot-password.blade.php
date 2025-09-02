@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Recovery my password')])
@endsection

@push('css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .card-shadow {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .floating-label {
            position: relative;
            margin-bottom: 20px;
        }

        .floating-input {
            border: 0;
            padding-left: 40px;
            border-bottom: 2px solid #cbd5e1;
            outline: none;
            transition: all 0.3s;
        }

        .floating-input:focus {
            border-bottom: 2px solid #667eea;
        }

        .floating-label label {
            position: absolute;
            top: 12px;
            left: 40px;
            color: #64748b;
            transition: all 0.3s;
            pointer-events: none;
        }

        .floating-input:focus~label,
        .floating-input:not(:placeholder-shown)~label {
            top: -20px;
            left: 40px;
            font-size: 12px;
            color: #667eea;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 12px;
            color: #94a3b8;
        }

        .illustration {
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .back-button {
            transition: all 0.3s;
        }

        .back-button:hover {
            transform: translateX(-5px);
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-4xl flex flex-col md:flex-row rounded-2xl overflow-hidden card-shadow">
            <!-- Recovery Form -->
            <div class="w-full md:w-1/2 bg-white p-8">
                <!-- Back Button -->
                <a href="{{ route('welcome') }}"
                    class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800 mb-6 back-button">
                    <span class="mdi mdi-arrow-left mr-1"></span> {{ __('Back to Login') }}
                </a>

                <!-- Header -->
                <div class="text-center mb-6">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="mdi mdi-lock-reset text-indigo-600 text-3xl"></span>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ __('Reset Your Password') }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-2 max-w-md mx-auto">
                        {{ __("Forgot your password? No problem. Just enter your email address below and we'll send you a secure link to reset your password.") }}
                    </p>
                </div>

                <!-- Form -->
                <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                    <!-- Email Input -->
                    <div class="floating-label">
                        <span class="mdi mdi-email-outline input-icon"></span>
                        <input type="email" id="email" name="email"
                            class="floating-input w-full px-4 py-3 rounded-lg bg-gray-50" placeholder=" " required>
                        <label for="email">{{ __('Email Address') }}</label>
                        @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="text-red-500 text-xs mt-1 block">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <!-- CAPTCHA Placeholder -->
                    <div class="flex flex-col">
                        <x-captcha />
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full gradient-bg hover:opacity-90 text-white py-3 rounded-xl font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <span class="mdi mdi-email-send-outline mr-2"></span> {{ __('Send Reset Link') }}
                    </button>
                </form>

                <!-- Additional Help -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <div class="flex">
                        <span class="mdi mdi-information-outline text-blue-500 text-xl mr-2"></span>
                        <div>
                            <h3 class="text-sm font-semibold text-blue-800">{{ __("Can't access your email?") }}</h3>
                            <p class="text-xs text-blue-600 mt-1">
                                {{ __("If you're having trouble receiving the reset email, please contact our support team for assistance.") }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Panel -->
            <div class="gradient-bg w-full md:w-1/2 text-white p-8 flex flex-col justify-center">
                <div class="text-center mb-6 illustration">
                    <span class="mdi mdi-shield-account text-6xl opacity-90"></span>
                </div>

                <h2 class="text-2xl font-bold mb-6 text-center">{{ __('Secure Password Recovery') }}</h2>

                <div class="space-y-6">
                    <div class="flex items-start">
                        <span class="mdi mdi-check-circle-outline text-xl mr-3 mt-0.5"></span>
                        <div>
                            <h3 class="font-semibold">{{ __('Encrypted Reset Links') }}</h3>
                            <p class="text-sm mt-1 text-indigo-100">
                                {{ __('Our password reset links are encrypted and time-sensitive for maximum security.') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <span class="mdi mdi-check-circle-outline text-xl mr-3 mt-0.5"></span>
                        <div>
                            <h3 class="font-semibold">{{ __('Instant Delivery') }}</h3>
                            <p class="text-sm mt-1 text-indigo-100">
                                {{ __('Reset instructions are sent immediately to your email inbox.') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <span class="mdi mdi-check-circle-outline text-xl mr-3 mt-0.5"></span>
                        <div>
                            <h3 class="font-semibold">{{ __('One-Click Process') }}</h3>
                            <p class="text-sm mt-1 text-indigo-100">
                                {{ __('Simply click the link in the email to create a new password.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-indigo-400">
                    <div class="text-center">
                        <a href="{{ route('welcome') }}" class="text-sm font-bold text-white hover:underline">
                            {{ config('app.org_name') }}
                        </a>
                    </div>
                    <p class="text-sm text-indigo-100 text-center">
                        <span class="mdi mdi-shield-lock-outline mr-1"></span>
                        {{ __('Your security is our priority. All reset requests are logged and monitored.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            const formElements = document.querySelectorAll('.floating-input, button');
            formElements.forEach((element, index) => {
                element.style.opacity = "0";
                element.style.transform = "translateY(10px)";
                setTimeout(() => {
                    element.style.transition = "opacity 0.5s, transform 0.5s";
                    element.style.opacity = "1";
                    element.style.transform = "translateY(0)";
                }, 100 * index);
            });
        });
    </script>
@endpush
