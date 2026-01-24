<?php

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

return [

    //redirect after login
    "redirect_to" => "/account",
 
    // Default redirect after login or main page
    "home_page" => "/",

    // Custom cookie name for the session (null = default)
    "cookie_name" => null,

    // Token services for Laravel Passport (null = disabled)
    "passport_token_services" => null,

    // Time in minutes to allow account verification
    "verify_account_time" => 5,

    // Prevent creating users via Artisan commands
    "disable_create_user_by_command" => false,

    // Automatically delete users after X days (e.g., inactive or not verified)
    "destroy_user_after" => 30,

    // Expiration time (in minutes) for the 2FA code sent via email
    "code_2fa_email_expires" => 5,

    // Allow or block public registration
    "enable_register_member" => true,

    // Enable Content Security Policy (CSP) headers
    "csp_enabled" => false,

    //Policies
    "terms_url" => null,
    "privacy_url" => null,
    "policy_cookies" => null,

    "birthday" => [
        "active" => false, // Activate birthday
        "limit" => 18, // Limit of year to allow register users
    ],

    "demo" => [
        'enabled' => false,
        'email' => null,
        'password' => null
    ],


    "legal" => [
        // Terms and Conditions
        'terms_and_condition' => <<<EOT
<h2>Terms and Conditions</h2>
<p>Welcome to our website. By accessing or using this website, you agree to comply with and be bound by the following terms and conditions. These terms govern your use of the website and all services provided.</p>

<h3>Use of Website</h3>
<p>You may use the website for lawful purposes only. You must not engage in any activity that may damage, disable, or interfere with the proper functioning of the website.</p>

<h3>User Obligations</h3>
<p>All users are expected to provide accurate information, maintain the confidentiality of their account, and avoid prohibited activities such as spamming, hacking, or distributing malware.</p>

<h3>Intellectual Property</h3>
<p>All content, trademarks, and logos on this website are the property of the company. Unauthorized use or reproduction is strictly prohibited.</p>

<h3>Limitation of Liability</h3>
<p>The company is not liable for any direct, indirect, or consequential damages arising from the use of this website. Users access and use the site at their own risk.</p>

<h3>Changes to Terms</h3>
<p>We reserve the right to modify these Terms and Conditions at any time. Changes will be posted on this page, and continued use constitutes acceptance of the new terms.</p>
EOT,

        // Privacy Policy
        'policies_of_privacy' => <<<EOT
<h2>Privacy Policy</h2>
<p>We value your privacy and are committed to protecting your personal information. This Privacy Policy explains how we collect, use, and safeguard your data when you visit our website.</p>

<h3>Information Collection</h3>
<p>We may collect information such as your name, email address, and usage data when you interact with our website. This data is used solely for providing and improving our services.</p>

<h3>Use of Cookies</h3>
<p>Our website uses essential cookies to maintain active sessions and enable basic functionalities. We do not use cookies to track your behavior or for advertising purposes.</p>

<h3>Data Protection</h3>
<p>We implement appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction.</p>

<h3>Third-Party Sharing</h3>
<p>We do not sell, trade, or otherwise transfer your personal data to outside parties. Any third-party services integrated with our website comply with their own privacy policies.</p>

<h3>Your Rights</h3>
<p>You have the right to access, correct, or request deletion of your personal data. To exercise these rights, please contact our support team.</p>

<h3>Policy Updates</h3>
<p>We may update this Privacy Policy periodically. The latest version will always be available on our website, and continued use indicates acceptance of any changes.</p>
EOT,

        'policies_of_cookies' => <<<EOT
        <h2 class="text-2xl font-semibold mb-4">Cookies Policy</h2>
    <p class="mb-3">Our website uses cookies to ensure the proper functioning of the site and to maintain your session while you navigate through different pages. These cookies are essential for providing a seamless and secure user experience.</p>
    <p class="mb-3">We want to emphasize that our website does <strong>not</strong> use any cookies to track your browsing behavior, monitor your activity, or collect personal data for marketing purposes. All cookies implemented are strictly technical and functional in nature.</p>
    <p class="mb-3">Specifically, the cookies we use are designed to:</p>
    <ul class="list-disc list-inside mb-3">
        <li>Keep you logged in during your session.</li>
        <li>Remember your selections and preferences while navigating the site.</li>
        <li>Enable basic functionalities such as form submissions and page navigation without errors.</li>
    </ul>
    <p class="mb-3">No third-party tracking or advertising cookies are deployed by our system. Your interactions with our website remain private, and no external entity receives data about your behavior on our platform.</p>
    <p class="mb-3">By using our website, you can rest assured that your privacy is fully respected and that any cookies stored in your browser are strictly necessary for the website's functionality. If you choose to clear cookies from your browser, you may need to log in again or lose temporary preferences.</p>
    <p class="mb-3">We are committed to transparency and maintaining a safe and secure environment for all users. Should you have any questions regarding our use of cookies or website functionality, please do not hesitate to contact our support team.</p>
    <p class="text-sm text-gray-500 mt-4">Last updated: September 30, 2025.</p>
    EOT,
    ]
];
