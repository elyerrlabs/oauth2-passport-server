<?php

namespace App\Http\Response;

final class VerifyEmailResponse extends \Laravel\Fortify\Http\Responses\VerifyEmailResponse implements \Laravel\Fortify\Contracts\VerifyEmailResponse
{
    public function toResponse($request)
    {
        return redirect()->intended(route('user.dashboard') . '?verified=1');
    }
}
