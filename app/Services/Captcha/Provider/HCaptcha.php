<?php
namespace App\Services\Captcha\Provider;

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

use Illuminate\Support\Facades\Http;
use App\Services\Captcha\Interface\CaptchaProviderInterface;


class HCaptcha implements CaptchaProviderInterface
{

    /**
     * Verify captcha
     * @return bool
     */
    public function verify()
    {
        $api = config('services.captcha.providers.hcaptcha.api');
        $secret = config('services.captcha.providers.hcaptcha.secret');

        $token = request()->input('h-captcha-response');

        $response = Http::asForm()->post($api, [
            'secret' => $secret,
            'response' => $token,
            'remoteip' => request()->ip(),
        ]);

        $body = $response->json();

        return $body['success'];
    }
}
