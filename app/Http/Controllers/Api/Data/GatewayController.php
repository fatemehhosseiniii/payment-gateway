<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Controller;
use App\Repositories\GatewayRepository;
use App\Services\Response;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    public function __construct(private readonly GatewayRepository $gatewayRepository)
    {
        //
    }

    public function __invoke()
    {
        //load gateways list for selected by front
        $gateways=$this->gatewayRepository->getActiveList();

        return Response::success(['gateways'=>$gateways->toResourceCollection()->additional(['simple'=>true])]);
    }
}
