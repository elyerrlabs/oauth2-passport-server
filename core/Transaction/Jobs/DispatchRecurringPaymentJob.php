<?php

namespace Core\Transaction\Jobs;

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

use Core\Transaction\Services\PackageService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DispatchRecurringPaymentJob implements ShouldQueue
{
    use Queueable;

    /**
     * Construct
     *  
     */
    public function __construct()
    {
        $this->onQueue('renewals');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = new PackageService();

        $service->handledDispatchRecurringPayment();
    }
}
