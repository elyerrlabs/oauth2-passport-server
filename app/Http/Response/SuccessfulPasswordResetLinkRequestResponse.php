<?php

namespace App\Http\Response;
final class SuccessfulPasswordResetLinkRequestResponse extends \Laravel\Fortify\Http\Responses\SuccessfulPasswordResetLinkRequestResponse
{

    public function toResponse($request)
    {
        return redirect()->route('login')->with('status', trans($this->status));
    }
}
