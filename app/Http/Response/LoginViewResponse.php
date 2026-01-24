<?php

namespace App\Http\Response;


final class LoginViewResponse implements \Laravel\Fortify\Contracts\LoginViewResponse
{
    public function toResponse($request)
    {
        // If the request has a redirect_to parameter, store it in the session
        if (!empty($request->input('redirect_to'))) {
            session()->put('redirect_to', $request->input('redirect_to'));
        }

        return auth()->check() ? redirect()->route('user.dashboard') : view('auth.login');
    }
}
