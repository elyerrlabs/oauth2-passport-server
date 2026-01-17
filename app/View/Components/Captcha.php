<?php

namespace App\View\Components;

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
