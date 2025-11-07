<?php

namespace Core\Transaction\Repositories;

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

use Core\Transaction\Model\DeliveryAddress;
use Illuminate\Http\Request;
use App\Repositories\Contracts\Contracts;

class DeliveryAddressRepository implements Contracts
{
    /**
     * Model
     * @var 
     */
    private $model;


    public function __construct(DeliveryAddress $deliveryAddress)
    {
        $this->model = $deliveryAddress;
    }


    /**
     * Search resources
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<DeliveryAddress>
     */
    public function search(Request $request)
    {
        $query = $this->model->query();
        $query->where('user_id', auth()->user()->id);

        return $query;
    }

    /**
     * Create new resource
     * @param array $data
     * @return DeliveryAddress|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Search specific resource
     * @param string $id
     * @return void
     */
    public function find(string $id)
    {
        return $this->model->find($id);
    }

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return DeliveryAddress|\Illuminate\Database\Eloquent\Model
     */
    public function update(string $id, array $data)
    {
        return $this->model->updateOrCreate(['id' => $id], $data);
    }

    /**
     * Delete specific resource
     * @param string $id
     * @return bool|null
     */
    public function delete(string $id)
    {
        return $this->model->where('id', $id)->where('user_id', auth()->user()->id)->delete();
    }
}
