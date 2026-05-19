@extends('layouts.pages')

@push('head')
    @include('layouts.parts.title', ['title' => __('Two-Factor Authentication')])
    <style nonce="{{ $nonce }}">
        .two-factor-card {
            animation: fadeInUp 0.5s ease-out;
        }

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

        .btn-submit {
            position: relative;
            overflow: hidden;
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.7);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }

        .shake {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .code-container {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-bottom: 16px;
        }

        .code-digit {
            width: 55px;
            height: 65px;
            text-align: center;
            font-size: 28px;
            font-weight: 600;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: white;
            transition: all 0.2s ease;
            -moz-appearance: textfield;
        }

        .code-digit:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            transform: translateY(-2px);
        }

        .code-digit.filled {
            border-color: #10b981;
            background-color: #f0fdf4;
        }

        .dark .code-digit {
            background: #1f2937;
            border-color: #4b5563;
            color: white;
        }

        .dark .code-digit.filled {
            border-color: #10b981;
            background-color: rgba(16, 185, 129, 0.1);
        }

        .code-digit.error {
            border-color: #ef4444;
            animation: shake 0.5s ease-in-out;
        }

        .timer-container {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 20px;
            text-align: center;
            border-left: 4px solid #f59e0b;
        }

        .dark .timer-container {
            background: linear-gradient(135deg, #451a03 0%, #78350f 100%);
            border-left-color: #f59e0b;
        }

        .timer-text {
            font-size: 14px;
            font-weight: 500;
            color: #92400e;
        }

        .dark .timer-text {
            color: #fde68a;
        }

        .timer-countdown {
            font-size: 18px;
            font-weight: bold;
            font-family: monospace;
            color: #b45309;
        }

        .dark .timer-countdown {
            color: #fbbf24;
        }

        .session-warning {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
            padding: 10px;
            border-radius: 8px;
            margin-top: 15px;
            font-size: 12px;
            color: #991b1b;
            display: none;
        }

        .dark .session-warning {
            background: #7f1d1d;
            color: #fecaca;
        }

        @media (max-width: 500px) {
            .code-digit {
                width: 45px;
                height: 55px;
                font-size: 24px;
            }

            .code-container {
                gap: 8px;
            }
        }
    </style>
@endpush

@section('content')
    <div
        class="min-h-screen bg-linear-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center p-4 transition-colors duration-300">
        <div class="w-full max-w-md two-factor-card">

            <div class="flex justify-center mb-6">
                <div
                    class="w-20 h-20 bg-linear-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-full flex items-center justify-center pulse">
                    <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11c0 3-3 3-3 3m6-3c0 3-3 3-3 3m0-10a4 4 0 00-4 4v3h8V8a4 4 0 00-4-4z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden transition-colors duration-300">

                <div
                    class="bg-linear-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 px-8 py-6 border-b dark:border-gray-700">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white text-center">
                        {{ __('Two-Factor Challenge') }}
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 text-center mt-1">
                        {{ __('Confirm access to your account') }}
                    </p>
                </div>

                <div class="px-8 py-6">

                    {{-- Timer de sesión --}}
                    <div class="timer-container" id="timerContainer">
                        <div class="timer-text">
                            <i class="mdi mdi-clock-outline"></i>
                            {{ __('Session expires in:') }}
                        </div>
                        <div class="timer-countdown" id="countdown">
                            05:00
                        </div>
                    </div>

                    {{-- Advertencia --}}
                    <div id="sessionWarning" class="session-warning">
                        <i class="mdi mdi-alert-circle"></i>
                        {{ __('Your session will expire in 30 seconds. Please complete the verification.') }}
                    </div>

                    @if ($errors->any())
                        <div id="errorAlert"
                            class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-sm text-red-700 dark:text-red-400">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $errors->first() }}
                            </div>
                        </div>
                    @endif

                    <div
                        class="bg-linear-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-100 dark:border-blue-800 rounded-xl p-4 mb-6 text-sm text-blue-800 dark:text-blue-300">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>{{ __('Enter the authentication code from your authenticator app or use a recovery code.') }}</span>
                        </div>
                    </div>

                    <form id="twoFactorForm" method="POST" action="{{ route('two-factor.login.store') }}">
                        @csrf

                        <div id="totp-section">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 text-center">
                                {{ __('Authentication Code') }}
                            </label>

                            <div class="code-container" id="codeContainer">
                                <input type="text" maxlength="1" class="code-digit" id="digit1" autofocus>
                                <input type="text" maxlength="1" class="code-digit" id="digit2">
                                <input type="text" maxlength="1" class="code-digit" id="digit3">
                                <input type="text" maxlength="1" class="code-digit" id="digit4">
                                <input type="text" maxlength="1" class="code-digit" id="digit5">
                                <input type="text" maxlength="1" class="code-digit" id="digit6">
                            </div>

                            <input type="hidden" name="code" id="hiddenCode">

                            <p class="text-xs text-gray-500 dark:text-gray-400 text-center mt-2">
                                {{ __('Enter the 6-digit code from Google Authenticator, Authy or similar app') }}
                            </p>
                        </div>

                        <div id="recovery-section" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Recovery Code') }}
                            </label>
                            <input type="text" name="recovery_code" id="recoveryInput"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                placeholder="xxxx-xxxx-xxxx" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ __('Enter one of your recovery codes') }}
                            </p>
                        </div>

                        <x-captcha />

                        <button type="submit" id="submitBtn"
                            class="btn-submit w-full mt-6 bg-linear-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                            <span class="relative z-10">{{ __('Confirm') }}</span>
                        </button>
                    </form>

                    <div class="text-center mt-6">
                        <button type="button" id="toggleRecoveryButton"
                            class="text-sm text-blue-600 dark:text-blue-400 hover:underline transition-all">
                            <span id="toggleText">{{ __('Use a recovery code') }}</span>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="text-center mt-4" id="logoutForm">
                        @csrf
                        <button type="button" id="logoutButton"
                            class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                            {{ __('← Log out') }}
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script nonce="{{ $nonce }}">
        $(document).ready(function() {
            var $digits = $('.code-digit');
            var $hiddenCode = $('#hiddenCode');
            var timeoutId;
            var warningTimeoutId;
            var countdownInterval;
            var totalSeconds = 300; // 5 minutos = 300 segundos

            // Función para cerrar sesión
            function logoutSession() {
                $('#logoutForm').submit();
            }

            // Función para actualizar el contador
            function updateCountdown() {
                var minutes = Math.floor(totalSeconds / 60);
                var seconds = totalSeconds % 60;

                var timeString = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
                $('#countdown').text(timeString);

                // Mostrar advertencia cuando faltan 30 segundos
                if (totalSeconds === 30) {
                    $('#sessionWarning').fadeIn();
                }

                if (totalSeconds <= 0) {
                    // Detener el contador
                    clearInterval(countdownInterval);
                    clearTimeout(timeoutId);
                    clearTimeout(warningTimeoutId);

                    // Cerrar sesión
                    logoutSession();
                }

                totalSeconds--;
            }

            // Función para resetear el timer (cuando hay actividad del usuario)
            function resetTimer() {
                // Restablecer los segundos a 300
                totalSeconds = 300;

                // Ocultar advertencia
                $('#sessionWarning').fadeOut();

                // Actualizar display
                updateCountdown();

                // Reiniciar intervalos
                clearInterval(countdownInterval);
                clearTimeout(timeoutId);
                clearTimeout(warningTimeoutId);

                // Iniciar nuevos timers
                countdownInterval = setInterval(function() {
                    updateCountdown();
                }, 1000);

                // Timer para cerrar sesión después de 5 minutos
                timeoutId = setTimeout(function() {
                    logoutSession();
                }, 300000);

                // Timer para mostrar advertencia a los 4:30
                warningTimeoutId = setTimeout(function() {
                    if (totalSeconds > 30) {
                        totalSeconds = 30;
                        updateCountdown();
                    }
                }, 270000);
            }

            // Eventos que reinician el timer (actividad del usuario)
            function bindActivityEvents() {
                var events = ['keypress', 'keydown', 'click', 'input', 'paste'];
                events.forEach(function(event) {
                    $(document).on(event, function() {
                        resetTimer();
                    });
                });
            }

            function updateHiddenCode() {
                var code = '';
                $digits.each(function() {
                    code += $(this).val() || '';
                });
                $hiddenCode.val(code);
            }

            function clearErrors() {
                $digits.removeClass('error');
            }

            function focusNext(currentIndex) {
                if (currentIndex < 5) {
                    $digits.eq(currentIndex + 1).focus();
                }
            }

            function focusPrev(currentIndex) {
                if (currentIndex > 0) {
                    $digits.eq(currentIndex - 1).focus();
                }
            }

            // Iniciar timers
            resetTimer();
            bindActivityEvents();

            $digits.on('input', function(e) {
                var $this = $(this);
                var index = $digits.index($this);
                var value = $this.val();

                clearErrors();

                if (value && !/^\d+$/.test(value)) {
                    $this.val('');
                    return;
                }

                if (value) {
                    $this.addClass('filled');
                    focusNext(index);
                } else {
                    $this.removeClass('filled');
                }

                updateHiddenCode();
            });

            $digits.on('keydown', function(e) {
                var $this = $(this);
                var index = $digits.index($this);

                if (e.key === 'Backspace') {
                    e.preventDefault();

                    if ($this.val() === '') {
                        if (index > 0) {
                            $digits.eq(index - 1).val('').removeClass('filled').focus();
                            updateHiddenCode();
                        }
                    } else {
                        $this.val('').removeClass('filled');
                        updateHiddenCode();
                    }
                } else if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    focusPrev(index);
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    focusNext(index);
                }
            });

            $digits.on('paste', function(e) {
                e.preventDefault();
                var paste = (e.originalEvent || e).clipboardData.getData('text');
                var numbers = paste.replace(/\D/g, '').slice(0, 6);

                if (numbers) {
                    for (var i = 0; i < numbers.length; i++) {
                        $digits.eq(i).val(numbers[i]).addClass('filled');
                    }
                    updateHiddenCode();

                    if (numbers.length === 6) {
                        $digits.eq(5).focus();
                    } else {
                        $digits.eq(numbers.length).focus();
                    }
                }
            });

            $('.btn-submit').on('click', function(e) {
                var $this = $(this);
                var ripple = $('<span class="ripple"></span>');
                var rect = $this[0].getBoundingClientRect();
                var size = Math.max(rect.width, rect.height);

                ripple.css({
                    width: size,
                    height: size,
                    left: e.clientX - rect.left - size / 2,
                    top: e.clientY - rect.top - size / 2
                });

                $this.append(ripple);

                setTimeout(function() {
                    ripple.remove();
                }, 600);
            });

            $('#toggleRecoveryButton').on('click', function() {
                var $this = $(this);
                var $totp = $('#totp-section');
                var $recovery = $('#recovery-section');
                var $recoveryInput = $('#recoveryInput');
                var $toggleText = $('#toggleText');

                if ($totp.is(':visible')) {
                    $totp.fadeOut(200, function() {
                        $recovery.fadeIn(200);
                        $recoveryInput.focus();
                    });
                    $toggleText.text('{{ __('Use authentication code') }}');
                } else {
                    $recovery.fadeOut(200, function() {
                        $totp.fadeIn(200);
                        $digits.eq(0).focus();
                    });
                    $toggleText.text('{{ __('Use a recovery code') }}');
                }
            });

            @if ($errors->any())
                $('.two-factor-card').addClass('shake');
                $digits.addClass('error');
                setTimeout(function() {
                    $('.two-factor-card').removeClass('shake');
                    setTimeout(function() {
                        $digits.removeClass('error');
                    }, 500);
                }, 500);
            @endif

            $('#twoFactorForm').on('submit', function() {
                var $btn = $('#submitBtn');

                if ($btn.data('submitting')) {
                    return false;
                }

                // Detener timers antes de enviar
                clearInterval(countdownInterval);
                clearTimeout(timeoutId);
                clearTimeout(warningTimeoutId);

                $btn.data('submitting', true);
                $btn.find('span').html(
                    '<svg class="inline animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>{{ __('Processing...') }}'
                );
                $btn.prop('disabled', true);

                return true;
            });

            // Botón manual de logout
            $('#logoutButton').on('click', function() {
                clearInterval(countdownInterval);
                clearTimeout(timeoutId);
                clearTimeout(warningTimeoutId);
                $('#logoutForm').submit();
            });
        });
    </script>
@endsection
