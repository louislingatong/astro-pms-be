<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchVesselDepartmentRequest;
use App\Services\VesselDepartmentService;
use Exception;
use Illuminate\Http\JsonResponse;

class VesselDepartmentController extends Controller
{
    /** @var VesselDepartmentService */
    protected $vesselDepartmentService;

    /**
     * VesselDepartmentController constructor
     *
     * @param VesselDepartmentService $vesselDepartmentService
     */
    public function __construct(VesselDepartmentService $vesselDepartmentService)
    {
        parent::__construct();

        $this->vesselDepartmentService = $vesselDepartmentService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

    /**
     * Retrieves the List of vessel department
     *
     * @param SearchVesselDepartmentRequest $request
     * @return JsonResponse
     */
    public function index(SearchVesselDepartmentRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $conditions = [
                'keyword' => $request->getKeyword(),
                'page' => $request->getPage(),
                'limit' => $request->getLimit(),
            ];
            $results = $this->vesselDepartmentService->search($conditions);
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
