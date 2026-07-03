<?php

use App\Exceptions\OAuthAuthenticationException;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        //channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->replace(
            Illuminate\Http\Middleware\TrustProxies::class,
            \App\Http\Middleware\TrustProxies::class
        );

        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \App\Http\Middleware\RequirePassword::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'client' => \App\Http\Middleware\CheckClientCredentials::class,
            'scopes' => \App\Http\Middleware\CheckScopes::class,
            'scope' => \App\Http\Middleware\CheckForAnyScope::class,
            'userCanAny' => \App\Http\Middleware\UserCanAny::class,
            'captcha' => \App\Http\Middleware\VerifyCaptcha::class,
            'demo' => \App\Http\Middleware\VerifyDemoUser::class,
            'track.ref' => \App\Http\Middleware\TrackReferralCode::class,
        ]);

        $middleware->web(
            replace: [
                \Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class =>
                    \App\Http\Middleware\VerifyCsrfToken::class,
            ],
            prepend: [
                \App\Http\Middleware\SecureHeaders::class,
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

            if ($e instanceof ValidationException) {

                $errors = $e->errors();

                $formatted = [];

                foreach ($errors as $field => $messages) {
                    $cleanedMessages = array_map(function ($message) {
                        $message = preg_replace('/\.\d+\./', ' ', $message);
                        $message = preg_replace('/\.\d+/', ' ', $message);
                        $message = str_replace('_', ' ', $message);
                        $message = preg_replace('/\s+/', ' ', $message);

                        return trim($message);
                    }, $messages);

                    data_set($formatted, $field, $cleanedMessages);
                }

                // Reponse JSON/API
                if ($request->wantsJson() || $request->is('api/*')) {
                    return response()->json([
                        'message' => $e->getMessage(),
                        'errors' => $formatted
                    ], 422);
                }
            }

            if ($e instanceof NotFoundHttpException && request()->acceptsHtml()) {

                if (request()->path() === '/') {
                    return redirect()->route('login');
                }

                return redirect('/');
            }

            if ($e instanceof OAuthAuthenticationException) {

                return redirectToHome();
            }

            if ($e instanceof ModelNotFoundException) {
                throw new ReportError(__("Model not be found"), 404);
            }

            if ($e instanceof TokenMismatchException) {
                throw new ReportError(__($e->getMessage()), 419);
            }

            if ($e instanceof BadRequestHttpException) {
                throw new ReportError(__($e->getMessage()), 400);
            }

            if ($e instanceof AuthorizationException) {
                throw new ReportError(__("Don't have the access rights"), 403);
            }

            if ($e instanceof AccessDeniedHttpException) {
                throw new ReportError(__("You haven't logged in yet. Please log in to unlock all features."), 401);
            }
        });
    })->create();
