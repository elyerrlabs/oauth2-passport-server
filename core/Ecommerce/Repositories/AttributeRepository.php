<?php

namespace Core\Ecommerce\Repositories;

use App\Models\Common\Attribute;

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

class AttributeRepository
{

    private $model;

    public function __construct(Attribute $attribute)
    {
        $this->model = $attribute;
    }

    /**
     * List the attributes
     * @return \Illuminate\Database\Eloquent\Collection 
     */
    public function listAttributes()
    {
        $attributes = $this->model->query()
            ->whereHas('products')
            ->get();

        $grouped = $attributes->groupBy(function ($item) {
            return $item->name . '|' . ($item->multiple ? '1' : '0');
        })->map(function ($items, $key) {
            $first = $items->first();

            return [
                "name" => ucfirst($first->name),
                "slug" => $first->slug,
                "type" => $first->type,
                "widget" => $first->widget,
                "multiple" => $first->multiple,
                "unit_id" => $first->unit_id,
                "values" => $items->pluck('value')->unique()->values()->all()
            ];
        })->values();

        return $grouped;
    }

}

