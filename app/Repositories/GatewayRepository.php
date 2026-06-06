<?php

namespace App\Repositories;

use App\Models\Gateway;
use App\Services\ArrayCrypt;
use App\Services\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class GatewayRepository implements RepositoryInterface
{

    public function __construct(public ArrayCrypt $arrayCrypt)
    {
        //
    }

    public function getActiveList(): Collection
    {
        return Gateway::query()->where('is_active',true)->get();
    }

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
        return $gateways->paginate(self::paginatePerPage);
    }

    public function create(array $data): Gateway
    {
        $data['params'] = $this->arrayCrypt->encrypt($data['params'] ?? []);

        $gateway = Gateway::updateOrCreate(['key' => $data['key']], $data);

        $gateway->refresh();
        return $gateway;
    }

    public function update(Gateway|Model $model, array $data): Gateway
    {
        if (isset($data['params'])) {
            $data['params'] = $this->arrayCrypt->encrypt($data['params'] ?? []);
        }

        $model->update($data);
        $model->refresh();
        return $model;
    }

    public function delete(Model $model): void
    {
        $model->delete();
    }

}
