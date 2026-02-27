<?php

namespace Core\Transaction\Services;

use Core\Transaction\Repositories\DeliveryAddressRepository;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;

final class DeliveryAddressService
{

    public function __construct(protected DeliveryAddressRepository $deliveryAddressRepository) {}


    /**
     * Search
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\DeliveryAddress>
     */
    public function search(Request $request)
    {
        $query = $this->deliveryAddressRepository->query();

        $query->when(
            $request->filled('order_type') ? $request->order_type : 'desc',
            function ($query) use ($request) {
                $query->orderBy($request->filled('order_by') ? $request->order_by : 'created_at');
            }
        );

        return $query;
    }


    /**
     * create
     * @param array $data
     * @return \Core\Transaction\Model\DeliveryAddress|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->deliveryAddressRepository->create([
            'full_name' => $data['full_name'],
            'country' => $data['country'],
            'state' => $data['state'],
            'city' => $data['city'],
            'district' => $data['district'],
            'address' => $data['address'],
            'address_line_2' => $data['address_line_2'] ?? null,
            'postal_code' => $data['postal_code'],
            'phone' => $data['phone'],
            'secondary_phone' => $data['secondary_phone'] ?? null,
            'references' => $data['references'] ?? null,
            'user_id' => request()->user()->id,
        ]);
    }

    /**
     * Update
     * @param string $id
     * @param array $data
     * @return \Core\Transaction\Model\DeliveryAddress|\Illuminate\Database\Eloquent\Model
     */
    public function update(string $id, array $data)
    {
        $model = $this->deliveryAddressRepository->find($id);

        throw_if(empty($model), new ReportError(__('Resource can not be found'), 404));

        return $this->deliveryAddressRepository->create([
            'full_name' => $data['full_name'],
            'country' => $data['country'],
            'state' => $data['state'],
            'city' => $data['city'],
            'district' => $data['district'],
            'address' => $data['address'],
            'address_line_2' => $data['address_line_2'] ?? null,
            'postal_code' => $data['postal_code'],
            'phone' => $data['phone'],
            'secondary_phone' => $data['secondary_phone'] ?? null,
            'references' => $data['references'] ?? null,
            'user_id' => request()->user()->id,
        ]);
    }

    /**
     * Delete
     * @param string $id
     * @return object|\Core\Transaction\Model\DeliveryAddress|\Core\Transaction\Repositories\TValue|\stdClass|null
     */
    public function delete(string $id)
    {

        $model = $this->deliveryAddressRepository->find($id);

        throw_if(empty($model), new ReportError(__('Resource can not be found'), 404));

        $model->delete();

        return $model;
    }
}
