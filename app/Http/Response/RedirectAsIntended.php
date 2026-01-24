<?php

namespace App\Http\Response;

final class RedirectAsIntended extends \Laravel\Fortify\Http\Responses\RedirectAsIntended
{

    public function toResponse($request)
    {
        return redirect()->intended(route('user.dashboard'));
    }
}
