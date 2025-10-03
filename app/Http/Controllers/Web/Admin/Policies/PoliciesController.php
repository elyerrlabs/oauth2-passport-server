<?php

namespace App\Http\Controllers\Web\Admin\Policies;

use App\Http\Controllers\WebController;
use Inertia\Inertia;

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

class PoliciesController extends WebController
{
    public function __construct()
    {
        $this->middleware('auth')->only(['termsAndConditionForm', 'policiesOfPrivacyForm', 'policiesOfCookiesForm']);
        $this->middleware('userCanAny:administrator:settings:full, administrator:settings:view')->only(['termsAndConditionForm', 'policiesOfPrivacyForm', 'policiesOfCookiesForm']);
    }

    public function termsAndConditionForm()
    {
        return view('policies/edit', [
            'title' => 'Terms and Conditions',
            'description' => 'In this section, you can edit the Terms and Conditions for your website. This document outlines the rules, obligations, and responsibilities of your users and your company, ensuring clarity and legal compliance.',
            'content' => config('system.legal.terms_and_condition'),
            'name' => 'system[legal][terms_and_condition]'
        ]);
    }


    public function policiesOfPrivacyForm()
    {
        return view('policies/edit', [
            'title' => 'Privacy Policy',
            'description' => 'In this section, you can edit the Privacy Policy for your website. This policy informs your users about how their personal data is collected, used, stored, and protected, ensuring compliance with privacy regulations and building trust with your users.',
            'content' => config('system.legal.policies_of_privacy'),
            'name' => 'system[legal][policies_of_privacy]'
        ]);
    }


    public function policiesOfCookiesForm()
    {
        return view('policies/edit', [
            'title' => 'Cookies Policy',
            'description' => 'In this section, you can edit the Cookies Policy for your website. This policy informs your users about the cookies used, how they are utilized to maintain session functionality, and assures that no tracking or profiling is performed.',
            'content' => config('system.legal.policies_of_cookies'),
            'name' => 'system[legal][policies_of_cookies]'
        ]);
    }

    public function termsAndCondition()
    {
        return view(
            'policies/detail',
            [
                'title' => __('Terms and Conditions'),
                'content' => config('system.legal.terms_and_condition')
            ]
        );
    }

    public function policiesOfPrivacy()
    {
        return view(
            'policies/detail',
            [
                'title' => __('Privacy policy'),
                'content' => config('system.legal.policies_of_privacy')
            ]
        );
    }

    public function policiesOfCookies()
    {
        return view(
            'policies/detail',
            [
                'title' => __('Cookies Policy'),
                'content' => config('system.legal.policies_of_cookies')
            ]
        );
    }
}
