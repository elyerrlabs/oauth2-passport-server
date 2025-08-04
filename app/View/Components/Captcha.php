<?php

namespace App\View\Components;

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

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Captcha extends Component
{

    /**
     * Provider
     * @var 
     */
    public $provider;

    /**
     * List of providers available
     * @var 
     */
    public $providers;

    /**
     * Site key 
     * @var 
     */
    public $siteKey;

    /**
     * Driver status 
     * @var 
     */
    public $status;

    /**
     * Loading only links
     */
    public $only_links;

    /**
     * Create a new component instance.
     */
    public function __construct($onlyLinks = false)
    {
        $this->provider = config("services.captcha.driver");
        $this->siteKey = config("services.captcha.providers.$this->provider.sitekey");
        $this->status = config("services.captcha.enabled");
        $this->providers = array_keys(config('services.captcha.providers'));
        $this->only_links = $onlyLinks;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.captcha');
    }
}
