<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchMachineryMakerRequest;
use App\Services\MachineryMakerService;
use Exception;
use Illuminate\Http\JsonResponse;

class MachineryMakerController extends Controller
{
    /** @var MachineryMakerService */
    protected $machineryMakerService;

    /**
     * MachineryMakerController constructor
     *
     * @param MachineryMakerService $machineryMakerService
     */
    public function __construct(MachineryMakerService $machineryMakerService)
    {
        parent::__construct();

        $this->machineryMakerService = $machineryMakerService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

    /**
     * Retrieves the List of machinery maker
     *
     * @param SearchMachineryMakerRequest $request
     * @return JsonResponse
     */
    public function index(SearchMachineryMakerRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $conditions = [
                'keyword' => $request->getKeyword(),
                'page' => $request->getPage(),
                'limit' => $request->getLimit(),
            ];
            $results = $this->machineryMakerService->search($conditions);
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
