<?php

namespace App\Http\Response;

final class TwoFactorLoginResponse implements \Laravel\Fortify\Contracts\TwoFactorLoginResponse
{

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return redirect()->intended(route('user.dashboard'));
    }
}
