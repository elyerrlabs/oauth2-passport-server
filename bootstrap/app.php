<?php

use App\Exceptions\RenderException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        //channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {


        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \App\Http\Middleware\RequirePassword::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'client' => \App\Http\Middleware\CheckClientCredentials::class,
            'scopes' => \App\Http\Middleware\CheckScopes::class,
            'scope' => \App\Http\Middleware\CheckForAnyScope::class,
            'authorize' => \App\Http\Middleware\DenyGrantType::class,
            'verify.credentials' => \App\Http\Middleware\verifyCredentials::class,
            'reactive.account' => \App\Http\Middleware\ReactiveAccount::class,
            'userCanAny' => \App\Http\Middleware\UserCanAny::class,
            'captcha' => \App\Http\Middleware\VerifyCaptcha::class,
            'demo' => \App\Http\Middleware\VerifyDemoUser::class,
            'track.ref' => \App\Http\Middleware\TrackReferralCode::class,
        ]);

        $middleware->web(
            remove: [
                \Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class,
                \Illuminate\Cookie\Middleware\EncryptCookies::class,
            ],
            prepend: [
                \App\Http\Middleware\SecureHeaders::class,
                \App\Http\Middleware\EncryptCookies::class,
                \App\Http\Middleware\VerifyCsrfToken::class,
            ],
            append: [
                \Laravel\Passport\Http\Middleware\CreateFreshApiToken::class,
                \App\Http\Middleware\EnsureEmailIsVerified::class,
                \App\Http\Middleware\Lang::class,
                \App\Http\Middleware\VerifyDemoUser::class,
                \App\Http\Middleware\HandleInertiaRequests::class,
            ]
        );

        $middleware->api(
            prepend: [
                \App\Http\Middleware\Lang::class,
            ],
            append: [
                \App\Http\Middleware\EnsureEmailIsVerified::class,
                \App\Http\Middleware\VerifyDemoUser::class,
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (Throwable $e, Request $request) {
            return  RenderException::render($e, $request);
        });
    })->create();
