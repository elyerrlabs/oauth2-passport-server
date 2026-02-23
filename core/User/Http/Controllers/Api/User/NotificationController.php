<?php

namespace Core\User\Http\Controllers\Api\User;

use App\Http\Controllers\ApiController;
use Core\User\Services\NotificationService;
use Core\User\Transformer\User\NotificationTransformer;

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

final class NotificationController extends ApiController
{

    /**
     * Construct
     * @param NotificationService $notificationService
     */
    public function __construct(protected NotificationService $notificationService)
    {
        parent::__construct();
    }

    /**
     * List notification
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listAllNotifications()
    {
        $data = $this->notificationService->listAllNotifications();

        return $this->showAllByBuilder($data, NotificationTransformer::class);
    }

    /**
     * List unread notifications
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listUnreadNotifications()
    {
        $data =  $this->notificationService->listUnreadNotifications();

        return $this->showAllByBuilder($data, NotificationTransformer::class);
    }

    /**
     * Show notification details
     * @param string $notification_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(string $notification_id)
    {
        $notification = $this->notificationService->showNotification($notification_id);

        return $this->showOne($notification, NotificationTransformer::class);
    }

    /**
     * Mark as read notification
     * @param string $notification_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function markAsReadNotification(string $notification_id)
    {
        $this->notificationService->markAsReadNotification($notification_id);

        return $this->message(__('Notification mark as read'), 200);
    }

    /**
     * Mark as read all notifications
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function markAsReadAllNotifications()
    {
        $this->notificationService->markAsReadAllNotifications();

        return $this->message(__('All Notification mark as read'), 200);
    }

    /**
     * Destroy all notifications
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroyNotifications()
    {
        $this->notificationService->destroyNotifications();

        return $this->message(__('All notification has been removed'), 200);
    }
}
