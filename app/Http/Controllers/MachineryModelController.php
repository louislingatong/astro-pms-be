<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchMachineryModelRequest;
use App\Services\MachineryModelService;
use Exception;
use Illuminate\Http\JsonResponse;

class MachineryModelController extends Controller
{
    /** @var MachineryModelService */
    protected $machineryModelService;

    /**
     * MachineryModelController constructor
     *
     * @param MachineryModelService $machineryModelService
     */
    public function __construct(MachineryModelService $machineryModelService)
    {
        parent::__construct();

        $this->machineryModelService = $machineryModelService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

    /**
     * Retrieves the List of machinery model
     *
     * @param SearchMachineryModelRequest $request
     * @return JsonResponse
     */
    public function index(SearchMachineryModelRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $conditions = [
                'keyword' => $request->getKeyword(),
                'page' => $request->getPage(),
                'limit' => $request->getLimit(),
            ];
            $results = $this->machineryModelService->search($conditions);
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
