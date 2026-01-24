<?php

namespace App\Http\Response;
use Illuminate\Support\Facades\Log;


final class PasswordConfirmedResponse implements \Laravel\Fortify\Contracts\PasswordConfirmedResponse
{
    public function toResponse($request)
    {
        return redirect()->intended(route('user.dashboard'));
    }
}
