<?php

namespace App\Http\Response;

final class RegisterViewResponse implements \Laravel\Fortify\Contracts\RegisterViewResponse
{
    public function toResponse($request)
    {
        // If the request has a redirect_to parameter, store it in the session
        if (!empty($request->input('redirect_to'))) {
            session()->put('redirect_to', $request->input('redirect_to'));
        }

        if (request()->user()) {
            return redirect()->route('users.dashboard');
        }
        return view('auth.register');
    }
}
