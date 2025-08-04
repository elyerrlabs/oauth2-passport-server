<?php
namespace App\Services\Captcha;

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

use App\Services\Captcha\Provider\HCaptcha;
use Illuminate\Support\Facades\App;
use App\Services\Captcha\Provider\CloudflareTurnstile;
use App\Services\Captcha\Interface\CaptchaProviderInterface;

class Captcha
{
    /**
     * Summary of provider
     * @var \App\Services\Captcha\Interface\CaptchaProviderInterface
     */
    protected CaptchaProviderInterface $provider;

    /**
     * Drivers
     * @var array
     */
    protected $drivers = [
        'turnstile' => CloudflareTurnstile::class,
        'hcaptcha' => HCaptcha::class
    ];

    /** 
     * @throws \Exception
     */
    public function __construct()
    {
        $driverKey = config('services.captcha.driver');

        if (!array_key_exists($driverKey, $this->drivers)) {
            throw new \Exception("Unsupported captcha driver: {$driverKey}");
        }

        $driver = $this->drivers[$driverKey];

        $this->provider = app()->make($driver);

    }

    /**
     * Verify captcha
     * @return bool
     */
    public function verify(): bool
    {
        return $this->provider->verify();
    }
}
