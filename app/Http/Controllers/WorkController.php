<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWorkRequest;
use App\Http\Requests\SearchWorkRequest;
use App\Http\Resources\WorkResource;
use App\Models\User;
use App\Services\WorkService;
use Exception;
use Illuminate\Http\JsonResponse;

class WorkController extends Controller
{
    /** @var WorkService */
    protected $workService;

    /**
     * WorkController constructor
     *
     * @param WorkService $workService
     */
    public function __construct(WorkService $workService)
    {
        parent::__construct();

        $this->workService = $workService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

    /**
     * Retrieves the List of vessel machinery with work
     *
     * @param SearchWorkRequest $request
     * @return JsonResponse
     */
    public function index(SearchWorkRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $conditions = [
                'keyword' => $request->getKeyword(),
                'status' => $request->getStatus(),
                'page' => $request->getPage(),
                'limit' => $request->getLimit(),
            ];
            $results = $this->workService->search($conditions);
            $this->response = array_merge($results, $this->response);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Creates a new work of the vessel sub category. Creator must be authenticated.
     *
     * @param CreateWorkRequest $request
     * @return JsonResponse
     */
    public function create(CreateWorkRequest $request): JsonResponse
    {
        $request->validated();

        /** @var User $creator */
        $creator = $request->user();

        try {
            $formData = [
                'vessel_machinery_sub_category_id' => $request->getVesselMachinerySubCategoryId(),
                'last_done' => $request->getLastDone(),
                'instructions' => $request->getInstructions(),
                'remarks' => $request->getRemarks(),
                'creator_id' => $creator->getAttribute('id')
            ];
            $job = $this->workService->create($formData);
            $this->response['data'] = new WorkResource($job);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }
}
