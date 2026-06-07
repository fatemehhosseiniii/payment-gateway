<?php

namespace App\Repositories;

use App\Models\Gateway;
use App\Models\Payment\PayRequest;
use App\Services\ArrayCrypt;
use App\Services\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class PayRequestRepository
{

    public function create(array $data): PayRequest
    {
        $payRequest = PayRequest::create($data);
        $payRequest->refresh();
        return $payRequest;
    }

}
