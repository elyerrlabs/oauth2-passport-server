<?php

namespace Core\User\Http\Controllers\Web;

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
