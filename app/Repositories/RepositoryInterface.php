<?php

namespace App\Repositories;

use App\Http\Requests\Api\Panel\GatewayRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{

    const int paginatePerPage = 5;

    public function getList(array $filterData): LengthAwarePaginator;

    public function save(array $data): Model;
}
