<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Panel\GatewayRequest;
use App\Http\Resources\PaginateResource;
use App\Models\Gateway;
use App\Repositories\GatewayRepository;
use App\Services\ArrayCrypt;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    public GatewayRepository $gatewayRepository;

    public function __construct()
    {
        $this->gatewayRepository = new GatewayRepository();
    }

    /**
     * load gateways List with applying filter
     * @param GatewayRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function index(GatewayRequest $request)
    {
        $gateways = $this->gatewayRepository->getList($request->validated());
        return Response::success([
            'gateways' => $gateways->toResourceCollection(),
            'paginate' => new PaginateResource($gateways)
        ]);
    }

    /**
     * Save New Gateway
     * @param GatewayRequest $request
     * @return JsonResponse
     */
    public function store(GatewayRequest $request): JsonResponse
    {
        //save
        $gateway = $this->gatewayRepository->create($request->validated());
        //return Result Message
        return Response::success(['gateway' => $gateway->toResource()]);
    }

    /**
     * Update Gateway data
     * @param GatewayRequest $request
     * @param $gateway
     * @return JsonResponse
     */
    public function update(GatewayRequest $request, Gateway $gateway)
    {
        //save
        $gateway = $this->gatewayRepository->update($gateway, $request->validated());
        //return Result Message
        return Response::success(['gateway' => $gateway->toResource()]);
    }

    /**
     * Remove Gateway (Soft-delete)
     * @param Gateway $gateway
     * @return JsonResponse
     */
    public function destroy(Gateway $gateway)
    {
        //remove
        $this->gatewayRepository->delete($gateway);
        //return Result Message
        return Response::success([]);
    }
}
