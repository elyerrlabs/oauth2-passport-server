@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Reset my password')])
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

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 12px;
            cursor: pointer;
            color: #64748b;
        }

        .password-strength {
            height: 4px;
            margin-top: 8px;
            border-radius: 2px;
            transition: all 0.3s;
        }

        .password-rules {
            font-size: 12px;
            color: #64748b;
            margin-top: 5px;
        }

        .language-selector {
            position: absolute;
            top: 20px;
            right: 20px;
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
    </style>
@endpush

@section('content')
    <div class="min-h-screen flex items-center justify-center p-4">

        <div class="w-full max-w-4xl flex flex-col md:flex-row rounded-2xl overflow-hidden card-shadow">
            <!-- Reset Form -->
            <div class="w-full md:w-1/2 bg-white p-8">
                <!-- Header -->
                <div class="text-center mb-6">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="mdi mdi-key-change text-indigo-600 text-3xl"></span>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ __('Update Your Password') }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-2 max-w-md mx-auto">
                        {{ __('Dear user, in this section you can set a new password for your account. Please enter your new                                                                                                                                                                                                                                                                                                                                                                                password below.') }}
                    </p>
                </div>

                <!-- Form -->
                <form class="space-y-6" method="post" action="{{ route('password.store') }}">
                    @csrf
                    <input type="hidden" name="token" id="token" value="{{ $token }}">

                    <!-- Email (hidden but present) -->
                    <div class="floating-label">
                        <span class="mdi mdi-email-outline input-icon"></span>
                        <input type="email" id="email" name="email" value="{{ $email }}"
                            class="floating-input w-full px-4 py-3 rounded-lg bg-gray-50" placeholder=" "
                            value="user@example.com" required readonly>
                        <label for="email">{{ __('Email Address') }}</label>
                        @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="text-red-500 text-sm mt-1 block">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <!-- New Password -->
                    <div class="floating-label relative">
                        <span class="mdi mdi-lock-plus input-icon"></span>
                        <input type="password" id="password" name="password"
                            class="floating-input w-full px-4 py-3 rounded-lg bg-gray-50" placeholder=" " required>
                        <label for="password">{{ __('New Password') }}</label>
                        <span class="toggle-password" onclick="togglePassword('password')">
                            <span class="mdi mdi-eye"></span>
                        </span>
                        @if ($errors->has('password'))
                            @foreach ($errors->get('password') as $item)
                                <span class="text-red-500 text-sm mt-1 block">{{ $item }}</span>
                            @endforeach
                        @endif
                        <!-- Password Strength Meter -->
                        <div class="password-strength bg-gray-200" id="password-strength"></div>
                        <div class="password-rules">
                            <span class="mdi mdi-information-outline text-xs mr-1"></span>
                            {{ __('Use at least 8 characters with a mix of letters, numbers & symbols') }}
                        </div>

                    </div>

                    <!-- Confirm Password -->
                    <div class="floating-label relative">
                        <span class="mdi mdi-lock-check input-icon"></span>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="floating-input w-full px-4 py-3 rounded-lg bg-gray-50" placeholder=" " required>
                        <label for="password_confirmation">{{ __('Confirm New Password') }}</label>
                        <span class="toggle-password" onclick="togglePassword('password_confirmation')">
                            <span class="mdi mdi-eye"></span>
                        </span>

                        <!-- Password Match Indicator -->
                        <div id="password-match" class="password-rules hidden">
                            <span class="mdi mdi-check-circle-outline text-green-500 text-xs mr-1"></span>
                            <span class="text-green-500">{{ __('Passwords match') }}</span>
                        </div>
                        <div id="password-mismatch" class="password-rules hidden">
                            <span class="mdi mdi-alert-circle-outline text-red-500 text-xs mr-1"></span>
                            <span class="text-red-500">{{ __('Passwords do not match') }}</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full gradient-bg hover:opacity-90 text-white py-3 rounded-xl font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <span class="mdi mdi-key-change mr-2"></span> {{ __('Change Password') }}
                    </button>
                </form>
            </div>

            <!-- Information Panel -->
            <div class="gradient-bg w-full md:w-1/2 text-white p-8 flex flex-col justify-center">
                <div class="text-center mb-6 illustration">
                    <span class="mdi mdi-security text-6xl opacity-90"></span>
                </div>

                <h2 class="text-2xl font-bold mb-6 text-center">{{ __('Secure Password Requirements') }}</h2>

                <div class="space-y-6">
                    <div class="flex items-start">
                        <span class="mdi mdi-key-variant text-xl mr-3 mt-0.5"></span>
                        <div>
                            <h3 class="font-semibold">{{ __('Strong Password Policy') }}</h3>
                            <p class="text-sm mt-1 text-indigo-100">
                                {{ __('Your new password must include uppercase, lowercase, numbers, and special characters.') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <span class="mdi mdi-shield-check-outline text-xl mr-3 mt-0.5"></span>
                        <div>
                            <h3 class="font-semibold">{{ __('Immediate Activation') }}</h3>
                            <p class="text-sm mt-1 text-indigo-100">
                                {{ __('Your password will be updated immediately after confirmation.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-indigo-400">
                    @if (Route::has('welcome'))
                        <div class="text-center">
                            <a href="{{ route('welcome') }}" class="text-sm font-bold text-white hover:underline">
                                {{ config('app.org_name') }}
                            </a>
                        </div>
                    @endif
                    <p class="text-sm text-indigo-100 text-center">
                        <span class="mdi mdi-clock-fast mr-1"></span>
                        {{ __('Password reset links expire after :minute minutes for security reasons.', ['minute' => config('auth.passwords.users.expire')]) }}
                    </p>
                </div>
            </div>
        </div>


    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        // Toggle password visibility
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.querySelector(`#${inputId} + .toggle-password .mdi`);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('mdi-eye');
                eyeIcon.classList.add('mdi-eye-off');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('mdi-eye-off');
                eyeIcon.classList.add('mdi-eye');
            }
        }

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('password-strength');
            let strength = 0;

            if (password.length > 5) strength += 20;
            if (password.length > 7) strength += 20;
            if (/[A-Z]/.test(password)) strength += 20;
            if (/[0-9]/.test(password)) strength += 20;
            if (/[^A-Za-z0-9]/.test(password)) strength += 20;

            // Update strength bar color
            if (strength < 40) {
                strengthBar.style.backgroundColor = '#ef4444'; // red
            } else if (strength < 80) {
                strengthBar.style.backgroundColor = '#f59e0b'; // amber
            } else {
                strengthBar.style.backgroundColor = '#10b981'; // green
            }

            strengthBar.style.width = strength + '%';
        });

        // Password confirmation check
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const matchIndicator = document.getElementById('password-match');
            const mismatchIndicator = document.getElementById('password-mismatch');

            if (confirmPassword.length === 0) {
                matchIndicator.classList.add('hidden');
                mismatchIndicator.classList.add('hidden');
            } else if (password === confirmPassword) {
                matchIndicator.classList.remove('hidden');
                mismatchIndicator.classList.add('hidden');
            } else {
                matchIndicator.classList.add('hidden');
                mismatchIndicator.classList.remove('hidden');
            }
        });

        // Simple animation for the form elements
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
