<?php

namespace App\Http\Response;

final class VerifyEmailViewResponse implements \Laravel\Fortify\Contracts\VerifyEmailViewResponse
{
    public function toResponse($request)
    {
        return response()->view('auth.verify-email');
    }
}
