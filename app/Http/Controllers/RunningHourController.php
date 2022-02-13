<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRunningHourRequest;
use App\Http\Requests\SearchRunningHourRequest;
use App\Http\Resources\RunningHourResource;
use App\Models\User;
use App\Services\RunningHourService;
use Exception;
use Illuminate\Http\JsonResponse;

class RunningHourController extends Controller
{
    /** @var RunningHourService */
    protected $runningHourService;

    /**
     * RunningHourController constructor
     *
     * @param RunningHourService $runningHourService
     */
    public function __construct(RunningHourService $runningHourService)
    {
        parent::__construct();

        $this->runningHourService = $runningHourService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

    /**
     * Retrieves the List of vessel machinery with running hours
     *
     * @param SearchRunningHourRequest $request
     * @return JsonResponse
     */
    public function index(SearchRunningHourRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $conditions = [
                'keyword' => $request->getKeyword(),
                'page' => $request->getPage(),
                'limit' => $request->getLimit(),
            ];
            $results = $this->runningHourService->search($conditions);
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
     * Creates a new running hour of the vessel machinery. Creator must be authenticated.
     *
     * @param CreateRunningHourRequest $request
     * @return JsonResponse
     */
    public function create(CreateRunningHourRequest $request): JsonResponse
    {
        $request->validated();

        /** @var User $creator */
        $creator = $request->user();

        try {
            $formData = [
                'vessel_machinery_id' => $request->getVesselMachineryId(),
                'running_hours' => $request->getRunningHours(),
                'creator_id' => $creator->getAttribute('id')
            ];
            $runningHour = $this->runningHourService->create($formData);
            $this->response['data'] = new RunningHourResource($runningHour);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }
}
