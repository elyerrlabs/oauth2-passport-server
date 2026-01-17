<?php

namespace Core\User\Http\Controllers\Web;

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


use Inertia\Inertia;
use App\Http\Controllers\WebController;
use Core\User\Repositories\NotificationRepository;

class NotificationController extends WebController
{
    /**
     *
     * @var NotificationRepository
     */
    public $repository;

    /**
     * Construct
     * @param  NotificationRepository $notificationRepository
     */
    public function __construct(NotificationRepository $notificationRepository)
    {
        parent::__construct();
        $this->repository = $notificationRepository;
        $this->middleware('wants.json')->except('listAllNotifications');
    }


    public function listAllNotifications()
    {
        if (request()->wantsJson()) {
            return $this->repository->listAllNotifications();
        }

        return Inertia::render("Web/Notification/Index", [
            'route' => [
                'all' => route('user.notification.index'),
                'unread' => route('user.notification.unread')
            ],
        ]);
    }

    /**
     * List unread notifications
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listUnreadNotifications()
    {
        return $this->repository->listUnreadNotifications();
    }

    /**
     * Show notification details
     * @param string $notification_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(string $notification_id)
    {
        return $this->repository->showNotification($notification_id);
    }

    /**
     * Mark as read notification
     * @param string $notification_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function markAsReadNotification(string $notification_id)
    {
        return $this->repository->markAsReadNotification($notification_id);
    }

    /**
     * Mark as read all notifications
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function markAsReadAllNotifications()
    {
        return $this->repository->markAsReadAllNotifications();
    }

    /**
     * Destroy all notifications
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroyNotifications()
    {
        return $this->repository->destroyNotifications();
    }
}
