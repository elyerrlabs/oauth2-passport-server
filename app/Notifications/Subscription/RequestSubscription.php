<?php

namespace App\Notifications\Subscription;

/**
 * OAuth2 Passport Server — a centralized, modular authorization server
 * implementing OAuth 2.0 and OpenID Connect specifications.
 *
 * Copyright (c) 2026 Elvis Yerel Roman Concha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * Author: Elvis Yerel Roman Concha
 * Contact: yerel9212@yahoo.es
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
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
        app()->setLocale($notifiable->lang);

        return (new MailMessage)
            ->subject(__('Your Subscription Request is Being Processed'))
            ->greeting(__('Dear User,'))
            ->line(__('We have received your subscription request and it is currently being processed.'))
            ->line(__("Transaction Code  :code", ['code' => $this->code]))
            ->line(__('You will receive a confirmation email once the process is completed.'))
            ->line(__('Thank you for your interest and for choosing our services.'))
            ->action(__('Go to dashboard'), $this->url)
            ->salutation(__('Best regards, The Team'));
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
            'message' => __("Your subscription request with transaction code :code is being processed. You will be notified once it is confirmed.", ['code' => $this->code]),
            'transaction_code' => $this->code,
            'url' => $this->url,
        ];
    }
}
