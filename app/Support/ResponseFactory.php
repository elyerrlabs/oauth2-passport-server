<?php

namespace App\Support;

use Inertia\Response;

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
