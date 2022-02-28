<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchIntervalUnitRequest;
use App\Services\IntervalUnitService;
use Exception;
use Illuminate\Http\JsonResponse;

class IntervalUnitController extends Controller
{
    /** @var IntervalUnitService */
    protected $intervalUnitService;

    /**
     * IntervalUnitController constructor
     *
     * @param IntervalUnitService $intervalUnitService
     */
    public function __construct(IntervalUnitService $intervalUnitService)
    {
        parent::__construct();

        $this->intervalUnitService = $intervalUnitService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

    /**
     * Retrieves the List of machinery model
     *
     * @param SearchIntervalUnitRequest $request
     * @return JsonResponse
     */
    public function index(SearchIntervalUnitRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $conditions = [
                'keyword' => $request->getKeyword(),
                'page' => $request->getPage(),
                'limit' => $request->getLimit(),
            ];
            $results = $this->intervalUnitService->search($conditions);
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
