<?php

namespace App\Http\Response;

final class ConfirmPasswordViewResponse implements \Laravel\Fortify\Contracts\ConfirmPasswordViewResponse
{
    public function toResponse($request)
    {
        return response()->view('auth.confirm-password');
    }
}
