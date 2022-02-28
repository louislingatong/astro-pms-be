<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchMachinerySubCategoryDescriptionRequest;
use App\Services\MachinerySubCategoryDescriptionService;
use Exception;
use Illuminate\Http\JsonResponse;

class MachinerySubCategoryDescriptionController extends Controller
{
    /** @var MachinerySubCategoryDescriptionService */
    protected $machinerySubCategoryDescriptionService;

    /**
     * MachinerySubCategoryDescriptionController constructor
     *
     * @param MachinerySubCategoryDescriptionService $machinerySubCategoryDescriptionService
     */
    public function __construct(MachinerySubCategoryDescriptionService $machinerySubCategoryDescriptionService)
    {
        parent::__construct();

        $this->machinerySubCategoryDescriptionService = $machinerySubCategoryDescriptionService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

    /**
     * Retrieves the List of machinery sub category description
     *
     * @param SearchMachinerySubCategoryDescriptionRequest $request
     * @return JsonResponse
     */
    public function index(SearchMachinerySubCategoryDescriptionRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $conditions = [
                'keyword' => $request->getKeyword(),
                'page' => $request->getPage(),
                'limit' => $request->getLimit(),
            ];
            $results = $this->machinerySubCategoryDescriptionService->search($conditions);
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
