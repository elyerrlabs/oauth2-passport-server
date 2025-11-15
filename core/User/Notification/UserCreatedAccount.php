<?php

namespace Core\User\Notification;

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
 * SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
 */

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserCreatedAccount extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Password 
     * @var string
     */
    public $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($password = null)
    {
        $this->password = $password;
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
        return (new MailMessage)
            ->subject(__("Welcome to Our Platform"))
            ->line(__("We are excited to inform you that your account has been successfully created. Below are your login details:"))
            ->line(__("Password :key", ['key' => $this->password]))
            ->line(__("For your security, we recommend updating your password. You can do this by following these steps:"))
            ->line(__("1. Click the button below to reset your password."))
            ->action(__('Reset Password'), route('forgot-password'))
            ->line(__("2. You will receive an email with a link to update your password."))
            ->line(__("3. Follow the link to set and confirm your new password. Once done, you can log in with your updated credentials."))
            ->line(__("If you have any questions or need assistance, feel free to contact our support team."))
            ->salutation(__('Thank you for choosing us!'));
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
}
