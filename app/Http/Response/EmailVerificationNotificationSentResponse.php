<?php

namespace App\Http\Response;

final class EmailVerificationNotificationSentResponse extends \Laravel\Fortify\Http\Responses\EmailVerificationNotificationSentResponse
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return back()->with('status', __('A new verification link has been sent to your email address.'));
    }
}
