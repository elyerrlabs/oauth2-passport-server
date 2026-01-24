<?php

namespace App\Http\Response;
use Laravel\Fortify\Fortify;


final class PasswordResetResponse extends \Laravel\Fortify\Http\Responses\PasswordResetResponse
{

    public function toResponse($request)
    {
        return redirect(Fortify::redirects('password-reset', config('fortify.views', true) ? route('login') : null))->with('status', trans($this->status));
    }
}
