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

use App\Models\Subscription\Scope;


trait Generic
{
    /**
     * Check if it the services is duplicated
     * @param mixed $value
     * @return array
     */
    public function checkServices($value)
    {
        $services = [];

        foreach ($value as $key) {
            $scope = Scope::with(['service'])->find($key);
            array_push($services, $scope->service->slug);
        }

        $count = array_count_values($services);

        $duplicated = array_keys(array_filter($count, function ($amount) {
            return $amount > 1;
        }));

        return $duplicated;
    }
}

