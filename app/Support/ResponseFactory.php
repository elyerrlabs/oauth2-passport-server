<?php

namespace App\Support;

use Inertia\Response;

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

final class ResponseFactory extends \Inertia\ResponseFactory
{
    /**
     * Re-write Inertia render to detect modules
     *
     * @param  array<array-key, mixed>|\Illuminate\Contracts\Support\Arrayable<array-key, mixed>|ProvidesInertiaProperties  $props
     */
    public function render(string $component, $props = []): Response
    {
        $currentRoute = request()->route();
        // Verify the module
        if (isset($currentRoute->action['module'])) {
            //Module view creation
            $ModuleView = $currentRoute->action['module'] . '::' . $this->rootView;
             
            // Check view exist
            if (view()->exists($ModuleView)) {
                $this->setRootView($ModuleView);
            }
        }

        if (config('inertia.ensure_pages_exist', false)) {
            $this->findComponentOrFail($component);
        }

        if ($props instanceof Arrayable) {
            $props = $props->toArray();
        } elseif ($props instanceof ProvidesInertiaProperties) {
            // Will be resolved in Response::resolveResponsableProperties()
            $props = [$props];
        }

        return new Response(
            $component,
            array_merge($this->sharedProps, $props),
            $this->rootView,
            $this->getVersion(),
            $this->encryptHistory ?? config('inertia.history.encrypt', false),
            $this->urlResolver,
        );
    }

}
