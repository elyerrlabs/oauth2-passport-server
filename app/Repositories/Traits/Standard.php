<?php

namespace App\Repositories\Traits;

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
 */

trait Standard
{
    /**
     * Add standard to save notification in database
     * @param mixed $title
     * @param mixed $description
     * @param mixed $url
     * @return array{created_at: string, description: array|string|null, link: mixed, title: array|string|null}
     */
    public function notificationDatabase($title, $description, $url = null)
    {
        return [
            "title" => $title,
            "message" => $description,
            "link" => $url,
        ];
    }
}
