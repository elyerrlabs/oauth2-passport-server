<?php
namespace App\Notifications\Member;

/**
 * Copyright (c) 2025 Elvis Yerel Roman Concha
 *
 * This file is part of an open source project licensed under the
 * "NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).
 *
 * You may use, study, modify, and redistribute this file for personal,
 * educational, or non-commercial research purposes only.
 *
 * Commercial use is strictly prohibited without prior written consent
 * from the author.
 *
 * Combining this software with any project licensed for commercial use
 * (such as AGPL) is not permitted without explicit authorization.
 *
 * This software supports OAuth 2.0 and OpenID Connect.
 *
 * Author Contact: yerel9212@yahoo.es
 * 
 * SPDX-License-Identifier: LicenseRef-NC-Open-Source-Projects
 */

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MemberCreatedAccount extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $link = $this->generateLink($notifiable);

        return (new MailMessage)
            ->subject(__('Welcome to Our Platform'))
            ->greeting(__('Hello :name :last_name,', [
                'name' => $notifiable->name,
                'last_name' => $notifiable->last_name
            ]))
            ->line(__('We’re excited to have you with us! To complete your registration and verify your identity, please follow the instructions provided.'))
            ->line(__('You have a maximum of :time minutes to verify your account. If the verification is not completed within this time, your information will be permanently deleted, and you’ll need to register again.', [
                'time' => config('system.verify_account_time', 5)
            ]))
            ->action(__('Verify Your Account'), url($link))
            ->line(__('Thank you for choosing us. We’re here to support you every step of the way.'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * Generate a new url to verify account
     * @param mixed $client
     * @return string
     */
    public function generateLink($user)
    {
        $token = Str::random(40);
        $email = $user->email;

        $query = http_build_query([
            'email' => $email,
            'token' => $token,
        ]);

        DB::transaction(function () use ($token, $email) {
            DB::table('password_resets')->updateOrInsert(
                [
                    'email' => $email,
                ],
                [
                    'email' => $email,
                    'token' => $token,
                    'created_at' => now(),
                ]
            );
        });

        return route('user.verify.account') . "?$query";
    }
}
