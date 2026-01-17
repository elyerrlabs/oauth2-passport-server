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

use Core\Transaction\Repositories\TransactionRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProcessRefundNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Transaction data
     * @var 
     */
    private $transaction_code;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $transaction_code)
    {
        $this->transaction_code = $transaction_code;
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

        $repository = app(TransactionRepository::class);
        $transaction = $repository->findByCode($this->transaction_code)->toArray();

        return (new MailMessage)
            ->subject(__('Your refund request has been initiated'))
            ->greeting(__('Hello!'))
            ->line(__('We have received your refund request for the following transaction:'))
            ->line(__('Transaction Code: :code', ['code' => $transaction['code']]))
            ->line(__('Type: :type', ['type' => ucfirst($transaction['type'])]))
            ->line(__('Status: :status', ['status' => ucfirst($transaction['status'])]))
            ->line(__('Total Amount: :currency :total', [
                'currency' => $transaction['currency'],
                'total' => $transaction['total']
            ]))
            //  ->action(__('View Transaction'), $transaction['payment_url'])
            ->line(__('Your refund is currently being processed. Once it is completed, we will notify you by email.'))
            ->line(__('Thank you for your patience!'));
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
