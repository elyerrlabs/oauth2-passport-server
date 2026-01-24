<?php

namespace App\Http\Response;

final class TwoFactorChallengeViewResponse implements \Laravel\Fortify\Contracts\TwoFactorChallengeViewResponse
{

    public function toResponse($request)
    {
        return response()->view('auth.two-factor-challenge');
    }
}
