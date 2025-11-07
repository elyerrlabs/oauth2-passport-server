<?php

namespace Core\User\Repositories;

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
 
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Core\User\Transformer\User\NotificationTransformer;


class NotificationRepository
{
    use JsonResponser;

    /**
     * List all notifications
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listAllNotifications()
    {
        $data = request()->user()->readNotifications()
            ->latest()
            ->take(150)
            ->get();

        return $this->showAll($data, NotificationTransformer::class);
    }

    /**
     * List un read notifications
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listUnreadNotifications()
    {
        $data = request()->user()->unreadNotifications()
            ->latest()
            ->take(150)
            ->get();

        return $this->showAll($data, NotificationTransformer::class);
    }

    /**
     * Show notification details
     * @param string $notification_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function showNotification(string $notification_id)
    {
        $notification = request()->user()->notifications()->where('id', $notification_id)->first();
        $this->markAsReadNotification($notification_id);
        return $this->showOne($notification, NotificationTransformer::class);
    }

    /**
     * Mark as read notification
     * @param string $notification_id
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function markAsReadNotification(string $notification_id)
    {
        $notification = request()->user()->notifications()->where('id', $notification_id)->first();

        if (empty($notification)) {
            throw new ReportError(__('Notification cannot be found'), 404);
        }

        $notification->markAsRead();

        return $this->message(__('Notification marked as read successfully'), 201);
    }

    /**
     * Mark all notifications as read
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function markAsReadAllNotifications()
    {
        request()->user()->unreadNotifications->markAsRead();
        return $this->message(__('Notifications marked as read successfully'), 201);
    }

    /**
     * Destroy all notification
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroyNotifications()
    {
        request()->user()->notifications()->delete();

        return $this->message(__('Notification deleted successfully'));
    }
}
