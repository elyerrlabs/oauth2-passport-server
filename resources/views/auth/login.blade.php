@extends('layouts.pages')

@section('title')
    @include('layouts.parts.title', ['title' => __('Login')])
@endsection

@push('css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .card-shadow {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        }

        .info-card {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }

        .floating-label {
            position: relative;
            margin-bottom: 20px;
        }

        .floating-input {
            border: 0;
            border-bottom: 2px solid #cbd5e1;
            outline: none;
            transition: all 0.3s;
        }

        .floating-input:focus {
            border-bottom: 2px solid #4f46e5;
        }

        .floating-label label {
            position: absolute;
            top: 12px;
            left: 12px;
            color: #64748b;
            transition: all 0.3s;
            pointer-events: none;
        }

        .floating-input:focus~label,
        .floating-input:not(:placeholder-shown)~label {
            top: -20px;
            left: 0;
            font-size: 12px;
            color: #4f46e5;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 12px;
            cursor: pointer;
            color: #64748b;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.2);
        }
    </style>
@endpush

@section('content')
    <div class="gradient-bg min-h-screen flex items-center justify-center p-4">

        <div class="w-full max-w-4xl bg-white rounded-2xl overflow-hidden card-shadow flex flex-col md:flex-row">
            <!-- Panel de Login -->
            <div class="w-full md:w-1/2 p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="mdi mdi-lock text-indigo-600 mr-2"></i>
                        {{ __(config('app.name', 'Oauth2 Server')) }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-2">{{ __('Sign in to your account') }}</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    <div class="floating-label">
                        <input type="email" id="email" name="email"
                            class="floating-input w-full px-4 py-3 rounded-lg bg-gray-50" placeholder=" " required>
                        <label for="email">{{ __('Email') }}</label>
                        @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="text-red-500 text-xs mt-1 block">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="floating-label relative">
                        <input type="password" id="password" name="password"
                            class="floating-input w-full px-4 py-3 rounded-lg bg-gray-50" placeholder=" " required>
                        <label for="password">{{ __('Password') }}</label>
                        <span class="toggle-password" onclick="togglePassword()">
                            <i class="mdi mdi-eye"></i>
                        </span>
                        @if ($errors->has('password'))
                            @foreach ($errors->get('password') as $item)
                                <span class="text-red-500 text-xs mt-1 block">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="flex flex-col items-start justify-between">
                        <x-captcha />
                        <div class="flex items-end">
                            <a href="{{ route('forgot-password') }}" class="text-sm text-indigo-600 hover:underline">
                                {{ __('Forgot your password') }}
                            </a>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-lg transition duration-200 font-semibold shadow-sm flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        {{ __('Sign In') }}
                    </button>

                    @if (Route::has('register'))
                        <div class="text-center text-sm text-gray-600">
                            {{ __("Don't have an account?") }}
                            <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">
                                {{ __('Sign up') }}
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <div class="info-card w-full md:w-1/2 text-white p-8 flex flex-col justify-center">
                <h2 class="text-2xl font-bold mb-6">{{ __('Authorization Server') }}</h2>

                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="feature-icon mr-4">
                            <i class="mdi mdi-shield"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">{{ __('Secure Authentication') }}</h3>
                            <p class="text-sm mt-1 text-indigo-100">
                                {{ __('Protect your applications with our advanced authentication system.') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="feature-icon mr-4">
                            <i class="mdi mdi-connection"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">{{ __('Universal Connectivity') }}</h3>
                            <p class="text-sm mt-1 text-indigo-100">
                                {{ __('Supports secure connectivity with any external application.') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="feature-icon mr-4">
                            <i class="mdi mdi-code-braces"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">{{ __('OpenID Connect & OAuth2') }}</h3>
                            <p class="text-sm mt-1 text-indigo-100">
                                {{ __('Implement the most widely used authorization protocols in the industry.') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="feature-icon mr-4">
                            <i class="mdi mdi-server"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">{{ __('Service-based Control') }}</h3>
                            <p class="text-sm mt-1 text-indigo-100">
                                {{ __('Advanced service-driven system for maximum scalability.') }}
                            </p>
                        </div>
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
                        {{ __('Need help?') }} <a href="#"
                        class="font-semibold hover:underline">{{ __('Contact our team') }}</a>
                        --}}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script nonce="{{ $nonce }}">
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.querySelector('.toggle-password i');

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
