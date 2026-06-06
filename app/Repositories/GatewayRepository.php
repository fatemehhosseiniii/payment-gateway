<?php

namespace App\Repositories;

use App\Models\Gateway;
use App\Services\ArrayCrypt;
use App\Services\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class GatewayRepository implements RepositoryInterface
{
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

    public function save(array $data): Gateway
    {
        $arrayCrypt = new ArrayCrypt($data['params'] ?? []);
        $data['params'] = $arrayCrypt->encrypt();

        return Gateway::updateOrCreate(['key' => $data['key']], $data);

    }

    public function delete(Model $model): void
    {
        $model->delete();
    }

}
