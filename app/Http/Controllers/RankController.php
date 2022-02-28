<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRankRequest;
use App\Services\RankService;
use Exception;
use Illuminate\Http\JsonResponse;

class RankController extends Controller
{
    /** @var RankService */
    protected $rankService;

    /**
     * RankController constructor
     *
     * @param RankService $rankService
     */
    public function __construct(RankService $rankService)
    {
        parent::__construct();

        $this->rankService = $rankService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

    /**
     * Retrieves the List of rank
     *
     * @param SearchRankRequest $request
     * @return JsonResponse
     */
    public function index(SearchRankRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $conditions = [
                'keyword' => $request->getKeyword(),
                'page' => $request->getPage(),
                'limit' => $request->getLimit(),
            ];
            $results = $this->rankService->search($conditions);
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
