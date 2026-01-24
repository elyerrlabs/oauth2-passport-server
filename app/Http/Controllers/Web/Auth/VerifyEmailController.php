<?php

namespace App\Http\Controllers\Web\Auth;
use Laravel\Fortify\Http\Requests\VerifyEmailRequest;
use App\Http\Response\VerifyEmailResponse;
use Illuminate\Auth\Events\Verified;

final class VerifyEmailController extends \Laravel\Fortify\Http\Controllers\VerifyEmailController
{

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Laravel\Fortify\Http\Requests\VerifyEmailRequest  $request
     * @return \Laravel\Fortify\Contracts\VerifyEmailResponse
     */
    public function __invoke(VerifyEmailRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return app(VerifyEmailResponse::class);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return app(VerifyEmailResponse::class);
    }
}
