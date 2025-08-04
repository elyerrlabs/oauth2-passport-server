<?php
namespace App\Transformers\Subscription;

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

use App\Models\Subscription\Scope;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class ScopeTransformer extends TransformerAbstract
{
    use Asset;

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Scope $data)
    {
        return [
            'id' => $data->id,
            'public' => $data->public ? true : false,
            'active' => $data->active ? true : false,
            'api_key' => $data->api_key ? true : false,
            'gsr_id' => $data->getGsrId(),
            'service' => [
                'id' => $data->service->id,
                'name' => $data->service->name,
                'slug' => $data->service->slug,
                'description' => $data->service->description,
                'system' => $data->service->system ? true : false,
                'group' => [
                    'id' => $data->service->group->id,
                    'name' => $data->service->group->name,
                    'description' => $data->service->group->description,
                ]
            ],
            'role' => [
                'id' => $data->role->id,
                'name' => $data->role->name,
                'slug' => $data->role->slug,
                'description' => $data->role->description
            ],
            'created' => $this->format_date($data->created_at),
            'updated' => $this->format_date($data->updated_at),
            'links' => [
                'index' => route('admin.scopes.index'),
            ]
        ];
    }

    /**
     * Return the original keys
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'name' => "name",
            'slug' => "slug",
            'description' => "service",
            'system' => "system",
            'group_id' => "group_id",
            'created' => "created_at",
            'updated' => "updated_at",
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
