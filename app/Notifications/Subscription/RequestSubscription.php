<?php

namespace App\Notifications\Subscription;

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
 */

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestSubscription extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Code
     * @var 
     */
    public string $code;

    /**
     * URl
     * @var 
     */
    public string $url;

    /**
     * Create a new notification instance.
     * @param string $url
     * @param string $code
     */
    public function __construct(string $url, string $code)
    {
        $this->url = $url;
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Subscription Request is Being Processed')
            ->greeting('Dear User,')
            ->line('We have received your subscription request and it is currently being processed.')
            ->line("Transaction Code: {$this->code}")
            ->line('You will receive a confirmation email once the process is completed.')
            ->line('Thank you for your interest and for choosing our services.')
            ->action('Go to dashboard', $this->url)
            ->salutation('Best regards, The Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Subscription Request Received',
            'message' => "Your subscription request with transaction code {$this->code} is being processed. You will be notified once it is confirmed.",
            'transaction_code' => $this->code,
            'url' => $this->url,
        ];
    }
}
