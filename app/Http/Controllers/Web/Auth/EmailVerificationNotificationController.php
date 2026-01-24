<?php

namespace App\Http\Controllers\Web\Auth;
use Illuminate\Http\Request;
use App\Http\Response\EmailVerificationNotificationSentResponse;
use App\Http\Response\RedirectAsIntended;

final class EmailVerificationNotificationController extends \Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController
{
    /**
     * Send verification email
     * @param Request $request
     * @return EmailVerificationNotificationSentResponse|RedirectAsIntended
     */
    public function store(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return app(RedirectAsIntended::class, ['name' => 'email-verification']);
        }

        $request->user()->sendEmailVerificationNotification();

        return app(EmailVerificationNotificationSentResponse::class);
    }
}
