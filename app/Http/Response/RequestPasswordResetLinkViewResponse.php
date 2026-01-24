<?php

namespace App\Http\Response;

final class RequestPasswordResetLinkViewResponse implements \Laravel\Fortify\Contracts\RequestPasswordResetLinkViewResponse
{
    public function toResponse($request)
    {
        return view('auth.forgot-password');
    }
}
