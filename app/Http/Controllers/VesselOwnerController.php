<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchVesselOwnerRequest;
use App\Services\VesselOwnerService;
use Exception;
use Illuminate\Http\JsonResponse;

class VesselOwnerController extends Controller
{
    /** @var VesselOwnerService */
    protected $vesselOwnerService;

    /**
     * VesselOwnerController constructor
     *
     * @param VesselOwnerService $vesselOwnerService
     */
    public function __construct(VesselOwnerService $vesselOwnerService)
    {
        parent::__construct();

        $this->vesselOwnerService = $vesselOwnerService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

    /**
     * Retrieves the List of vessel owner
     *
     * @param SearchVesselOwnerRequest $request
     * @return JsonResponse
     */
    public function index(SearchVesselOwnerRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $conditions = [
                'keyword' => $request->getKeyword(),
                'page' => $request->getPage(),
                'limit' => $request->getLimit(),
            ];
            $results = $this->vesselOwnerService->search($conditions);
            $this->response = array_merge($results, $this->response);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

}
