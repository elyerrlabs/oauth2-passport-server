@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Register')])
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

        .checkbox-container {
            position: relative;
            padding-left: 30px;
            cursor: pointer;
            font-size: 14px;
            color: #64748b;
        }

        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .checkmark {
            position: absolute;
            top: 2px;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: #f8fafc;
            border: 2px solid #cbd5e1;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .checkbox-container input:checked~.checkmark {
            background-color: #667eea;
            border-color: #667eea;
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        .checkbox-container input:checked~.checkmark:after {
            display: block;
        }

        .checkbox-container .checkmark:after {
            left: 6px;
            top: 2px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.15);
            margin-bottom: 15px;
        }

        .feature-item {
            transition: all 0.3s;
        }

        .feature-item:hover {
            transform: translateY(-5px);
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen flex items-center justify-center p-4 py-8 overflow-y-auto">

        <div class="w-full max-w-6xl flex flex-col md:flex-row rounded-2xl overflow-hidden card-shadow">
            <!-- Registration Form -->
            <div class="w-full md:w-2/3 bg-white p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">
                        <span class="mdi mdi-account-plus text-indigo-600 mr-2"></span>
                        {{ __('Create a New Account') }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-2">
                        {{ __('Join our secure authorization server and enjoy enhanced privacy and security.') }}
                    </p>
                </div>

                <!-- Form -->
                <form class="space-y-4" action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div class="floating-label">
                            <span class="mdi mdi-account input-icon"></span>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="floating-input w-full px-4 py-3 rounded-lg bg-gray-50" placeholder=" " required>
                            <label for="name">{{ __('First Name') }}</label>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="floating-label">
                            <span class="mdi mdi-account-outline input-icon"></span>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                                class="floating-input w-full px-4 py-3 rounded-lg bg-gray-50" placeholder=" " required>
                            <label for="last_name">{{ __('Last Name') }}</label>
                            @error('last_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="floating-label">
                        <span class="mdi mdi-email input-icon"></span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="floating-input w-full px-4 py-3 rounded-lg bg-gray-50" placeholder=" " required>
                        <label for="email">{{ __('Email Address') }}</label>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Birthday -->
                    @if (config('system.birthday.active'))
                        <div class="floating-label">
                            <span class="mdi mdi-cake input-icon"></span>
                            <input type="text" id="birthday" name="birthday" value="{{ old('birthday') }}"
                                class="floating-input date w-full px-4 py-3 rounded-lg bg-gray-50">

                            @error('birthday')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div class="floating-label relative">
                            <span class="mdi mdi-lock input-icon"></span>
                            <input type="password" id="password" name="password"
                                class="floating-input w-full px-4 py-3 rounded-lg bg-gray-50" placeholder=" " required>
                            <label for="password">{{ __('Password') }}</label>
                            <span class="toggle-password" onclick="togglePassword('password')">
                                <span class="mdi mdi-eye"></span>
                            </span>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="floating-label relative">
                            <span class="mdi mdi-lock-check input-icon"></span>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="floating-input w-full px-4 py-3 rounded-lg bg-gray-50" placeholder=" " required>
                            <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                            <span class="toggle-password" onclick="togglePassword('password_confirmation')">
                                <span class="mdi mdi-eye"></span>
                            </span>
                            @error('password_confirmation')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="flex items-start gap-3 text-sm text-gray-600 mt-6">
                        <label class="checkbox-container">
                            <input type="checkbox" name="accept_terms" id="accept_terms" value="1">
                            <span class="checkmark"></span>
                        </label>
                        <label for="accept_terms" class="mt-0.5">
                            {{ __('By choosing this option, you accept the') }}
                            <a href="{{ config('system.terms_url') }}" target="_blank"
                                class="text-indigo-600 hover:underline">{{ __('Terms and Conditions') }}</a>
                            {{ __('and') }} <a href="{{ config('system.privacy_url') }}" target="_blank"
                                class="text-indigo-600 hover:underline">
                                {{ __('Privacy Policy') }}
                            </a>.
                        </label>
                    </div>
                    @error('accept_terms')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Cookies -->
                    <div class="flex items-start gap-3 text-sm text-gray-600">
                        <label class="checkbox-container">
                            <input type="checkbox" name="accept_cookies" id="accept_cookies" value="1">
                            <span class="checkmark"></span>
                        </label>
                        <label for="accept_cookies" class="mt-0.5">
                            {{ __('By choosing this option, you accept the') }}
                            <a href="{{ config('system.policy_cookies') }}" target="_blank"
                                class="text-indigo-600 hover:underline">{{ __('Cookies Policy') }}</a>.
                        </label>
                    </div>
                    @error('accept_cookies')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <x-captcha />

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full gradient-bg hover:opacity-90 text-white py-3 rounded-xl font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition mt-6">
                        <span class="mdi mdi-account-plus mr-2"></span> {{ __('Create Account') }}
                    </button>

                    <!-- Login Link -->
                    <div class="text-center text-sm text-gray-600 mt-4">
                        {{ __('Already have an account?') }}
                        <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">{{ __('Sign In') }}</a>
                    </div>
                </form>
            </div>

            <!-- Information Panel -->
            <div class="gradient-bg w-full md:w-1/3 text-white p-8 flex flex-col justify-center">
                <h2 class="text-2xl font-bold mb-6">{{ __('Why Join Our Platform?') }}</h2>

                <div class="space-y-8">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <span class="mdi mdi-shield-account text-2xl"></span>
                        </div>
                        <h3 class="font-semibold text-lg">{{ __('Secure Authorization') }}</h3>
                        <p class="text-sm mt-2 text-indigo-100">
                            {{ __("Our advanced authorization server ensures your data remains
                                                                                                                                                                                                                                                                                        protected with industry-leading security protocols.") }}
                        </p>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <span class="mdi mdi-connection text-2xl"></span>
                        </div>
                        <h3 class="font-semibold text-lg">{{ __('Universal Connectivity') }}</h3>
                        <p class="text-sm mt-2 text-indigo-100">
                            {{ __("Seamlessly connect with any external application through
                                                                                                                                                                                                                                                            our OAuth2 and OpenID Connect implementation.") }}
                        </p>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <span class="mdi mdi-server-security text-2xl"></span>
                        </div>
                        <h3 class="font-semibold text-lg">{{ __('Service-Controlled System') }}</h3>
                        <p class="text-sm mt-2 text-indigo-100">
                            {{ __("Our service-based architecture ensures maximum scalability,
                                                                                                                                                                                                    reliability, and performance for all your authorization needs.") }}
                        </p>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-indigo-400">

                    <p class="text-sm text-indigo-100">
                    <div class="text-center">
                        <a href="{{ route('welcome') }}" class="text-sm font-bold text-white hover:underline">
                            {{ config('app.org_name') }}
                        </a>
                    </div>
                    {{-- 
                        <span class="mdi mdi-information-outline mr-1"></span>
                        Need assistance? <a href="" class="font-semibold hover:underline">Contact our
                            support
                            team</a>
                            --}}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script nonce="{{ $nonce }}">
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
    </script>
@endpush
