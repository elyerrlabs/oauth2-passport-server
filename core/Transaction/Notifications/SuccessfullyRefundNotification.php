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
