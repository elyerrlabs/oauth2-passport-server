<?php

namespace App\Http\Controllers\Web\Admin\Policies;

use App\Http\Controllers\WebController;
use Inertia\Inertia;

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
