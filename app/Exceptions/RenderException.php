<?php

namespace App\Exceptions;

use App\Exceptions\OAuthAuthenticationException;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class RenderException
{
    /**
     * Render
     * @throws ReportError
     */
    public static function render(Throwable $throwable, Request $request)
    {
        if ($throwable instanceof ValidationException) {

            return static::validationErrors($request, $throwable);
        }

        if ($throwable instanceof NotFoundHttpException && request()->acceptsHtml()) {

            if (request()->path() === '/') {
                return redirect()->route('login');
            }

            return redirect('/');
        }

        if ($throwable instanceof ModelNotFoundException) {
            throw new ReportError(__("Model not be found"), 404);
        }

        if ($throwable instanceof TokenMismatchException) {
            throw new ReportError(__($throwable->getMessage()), 419);
        }

        if ($throwable instanceof BadRequestHttpException) {
            throw new ReportError(__($throwable->getMessage()), 400);
        }

        if ($throwable instanceof AuthorizationException) {
            throw new ReportError(__("Don't have the access rights"), 403);
        }

        if ($throwable instanceof OAuthAuthenticationException) {

            return redirectToHome();
        }

        if (method_exists($throwable, 'render') && $response = $throwable->render($request)) {
            return Router::toResponse($request, $response);
        }

        if ($throwable instanceof Responsable) {
            return $throwable->toResponse($request);
        }

        if ($throwable instanceof AccessDeniedHttpException) {
            throw new ReportError(__("You haven't logged in yet. Please log in to unlock all features."), 401);
        }
    }

    /**
     * Validation errors
     * @param Request $request
     * @param Throwable $e
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    private static function validationErrors(Request $request, Throwable $e)
    {
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

        // Para peticiones JSON/API
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $formatted
            ], 422);
        }

        // Para Blade/Inertia - aplanar los errores
        $flattened = [];
        foreach ($formatted as $field => $messages) {
            if (is_array($messages)) {
                // Si es un array anidado
                if (isset($messages[0]) && is_array($messages[0])) {
                    foreach ($messages as $index => $nestedMessages) {
                        foreach ($nestedMessages as $nestedField => $nestedMessage) {
                            $key = $field . '.' . $index . '.' . $nestedField;
                            $flattened[$key] = is_array($nestedMessage) ? $nestedMessage[0] : $nestedMessage;
                        }
                    }
                } else {
                    // Array simple de strings
                    $flattened[$field] = $messages[0] ?? '';
                }
            } else {
                $flattened[$field] = $messages;
            }
        }

        return redirect()->back()
            ->withInput($request->input())
            ->withErrors($flattened);
    }
}
