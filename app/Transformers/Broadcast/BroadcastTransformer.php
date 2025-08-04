<?php

namespace App\Transformers\Broadcast;

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

use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class BroadcastTransformer extends TransformerAbstract
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
    public function transform($data)
    {
        return [
            'id' => $data->id,
            'name' => $data->name,
            'slug' => $data->slug,
            'description' => $data->description,
            'system' => $data->system,
            'created' => $this->format_date($data->created_at),
            'updated' => $this->format_date($data->updated_at),
            'links' => [
                'parent' => route('admin.broadcasts.index'),
                'store' => route('admin.broadcasts.store'),
                'destroy' => route('admin.broadcasts.destroy', ['broadcast' => $data->id]),
            ],
        ];
    }


    /**
     * Retrieve the original attributes
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'name' => 'channel',
            'description' => 'description',
            'system' => 'system',
            'created' => 'created_at',
            'updated' => 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
