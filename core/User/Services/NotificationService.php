<?php

namespace Core\User\Services;

use Elyerr\ApiResponse\Exceptions\ReportError;

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

final class NotificationService
{

    /**
     * Retrieve the notifications
     */
    public function listAllNotifications()
    {
        return request()->user()->readNotifications();
    }

    /**
     * List unread notifications
     */
    public function listUnreadNotifications()
    {
        return request()->user()->unreadNotifications();
    }

    /**
     * Show notification
     * @param string $notification_id
     */
    public function showNotification(string $notification_id)
    {
        return  request()->user()->notifications()->where('id', $notification_id)->first();
    }

    /**
     * Mark as read the notification
     * @param string $notification_id
     * @throws ReportError
     */
    public function markAsReadNotification(string $notification_id)
    {
        $notification = request()->user()->notifications()->where('id', $notification_id)->first();

        if (empty($notification)) {
            throw new ReportError(__('Notification cannot be found'), 404);
        }

        return $notification->markAsRead();
    }

    /**
     * Mark as read all notifications
     */
    public function markAsReadAllNotifications()
    {
        return   request()->user()->unreadNotifications->markAsRead();
    }

    /**
     * Destroy the notification
     */
    public function destroyNotifications()
    {
        return   request()->user()->notifications()->delete();
    }
}
