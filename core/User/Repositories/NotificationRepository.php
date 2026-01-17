<?php

namespace Core\User\Repositories;

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
