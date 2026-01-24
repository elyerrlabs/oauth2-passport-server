<?php

namespace App\Http\Response;

final class ResetPasswordViewResponse implements \Laravel\Fortify\Contracts\ResetPasswordViewResponse
{
    public function toResponse($request)
    {
        return response()->view('auth.reset-password', [
            'token' => $request->route('token'),
            'email' => $request->email,
        ]);
    }
}
