<?php
namespace App\Services\Captcha;

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
