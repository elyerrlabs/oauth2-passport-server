<?php

namespace Core\Transaction\Notifications;

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

class SuccessfullyRefundNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Transaction data
     * @var 
     */
    private $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->onQueue('payments');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        app()->setLocale($notifiable->lang);

        $data = $this->data;

        return (new MailMessage)
            ->subject(__('Your refund has been processed successfully'))
            ->greeting(__('Hello!'))
            ->line(__('We’re writing to let you know that your refund has been successfully processed.'))
            ->line(__('Here are the details of your transaction:'))
            ->line(__('Transaction Code: :code', ['code' => $data['code']]))
            ->line(__('Type: :type', ['type' => ucfirst($data['type'])]))
            ->line(__('Status: :status', ['status' => ucfirst($data['status'])]))
            ->line(__('Total Refunded: :currency :total', [
                'currency' => $data['currency'],
                'total' => $data['total']
            ]))
            ->action(__('View Transaction'), $data['payment_url'])
            ->line(__('If you have any questions regarding this refund, please don’t hesitate to contact our support team.'))
            ->line(__('Thank you for trusting us!'));
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
