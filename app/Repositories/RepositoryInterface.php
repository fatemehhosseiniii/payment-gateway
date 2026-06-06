<?php

namespace App\Repositories;

use App\Http\Requests\Api\Panel\GatewayRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{

    const int paginatePerPage = 5;

    public function getList(array $filterData): LengthAwarePaginator;

    public function create(array $data): Model;

    public function update(Model $model, array $data): Model;
}
