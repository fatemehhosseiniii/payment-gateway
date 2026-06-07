<?php

namespace App\Repositories;

use App\Models\Gateway;
use App\Services\ArrayCrypt;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GatewayRepository
{

    public ArrayCrypt $arrayCrypt;

    public function __construct()
    {
        $this->arrayCrypt = new ArrayCrypt();
    }

    /**
     * Active list for global api
     * @return Collection
     */
    public function getActiveList(): Collection
    {
        return Gateway::query()->where('is_active', true)->get();
    }

    /**
     * Main Gateways list for panel manager
     * @param array $filterData
     * @return LengthAwarePaginator
     */
    public function getList(array $filterData): LengthAwarePaginator
    {
        $gateways = Gateway::query();

        //todo : can move to Gateway model
        //apply filter values
        if (!empty($filterData['title'])) {
            $gateways->whereAny(['title', 'key'], 'like', '%' . $filterData['title'] . '%');
        }
        if (isset($filterData['is_active'])) {
            $gateways->where('is_active', $filterData['is_active']);
        }
        return $gateways->orderByDesc('created_at')->paginate(config('setting.paginate-per-page'));
    }

    /**
     * Find Gateway by custom key
     * @param string $key
     * @param string $value
     * @return Model
     */
    public function find(string $key, string $value): Model
    {
        $gateway = Gateway::where($key, $value)->where('is_active', true)->first();
        if (!$gateway)
            return throw new NotFoundHttpException(__('payment.gateway-not-found', ['gateway' => $value]));

        return $gateway;
    }

    /**
     * Create New gateway and if exists key updated
     * @param array $data
     * @return Gateway
     */
    public function create(array $data): Gateway
    {
        $data['params'] = $this->arrayCrypt->encrypt($data['params'] ?? []);

        $gateway = Gateway::updateOrCreate(['key' => $data['key']], $data);

        $gateway->refresh();
        return $gateway;
    }

    /**
     * Update Gateway Values
     * @param Gateway|Model $model
     * @param array $data
     * @return Gateway
     */
    public function update(Gateway|Model $model, array $data): Gateway
    {
        if (isset($data['params'])) {
            $data['params'] = $this->arrayCrypt->encrypt($data['params'] ?? []);
        }

        $model->update($data);
        $model->refresh();
        return $model;
    }

    /**
     * Remove Gateway selected
     * @param Model $model
     * @return void
     */
    public function delete(Model $model): void
    {
        $model->delete();
    }

}
