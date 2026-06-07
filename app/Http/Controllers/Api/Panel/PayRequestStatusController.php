<?php

namespace App\Http\Controllers\Api\Panel;

use App\Enums\PayRequestStatus;
use App\Http\Controllers\Controller;
use App\Services\Response;
use Illuminate\Http\Request;

class PayRequestStatusController extends Controller
{
    public function __invoke()
    {
        $statuses=collect(PayRequestStatus::cases())->map(fn($status)=>$status->toArray());

        return Response::success(['statuses'=>$statuses]);
    }
}
