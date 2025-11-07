<?php

namespace Core\Transaction\Notifications;

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
