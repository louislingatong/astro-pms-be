<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVesselMachineryRequest;
use App\Http\Requests\SearchVesselMachineryRequest;
use App\Http\Requests\UpdateVesselMachineryRequest;
use App\Http\Resources\VesselMachineryResource;
use App\Models\VesselMachinery;
use App\Services\VesselMachineryService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;

class VesselMachineryController extends Controller
{
    /** @var VesselMachineryService */
    protected $vesselMachineryService;

    /**
     * VesselMachineryController constructor
     *
     * @param VesselMachineryService $vesselMachineryService
     */
    public function __construct(VesselMachineryService $vesselMachineryService)
    {
        parent::__construct();

        $this->vesselMachineryService = $vesselMachineryService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

    /**
     * Retrieves the List of vessel machinery
     *
     * @param SearchVesselMachineryRequest $request
     * @return JsonResponse
     */
    public function index(SearchVesselMachineryRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $conditions = [
                'keyword' => $request->getKeyword(),
                'page' => $request->getPage(),
                'limit' => $request->getLimit(),
            ];
            $results = $this->vesselMachineryService->search($conditions);
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
     * Creates a new vessel machinery. Creator must be authenticated.
     *
     * @param CreateVesselMachineryRequest $request
     * @return JsonResponse
     */
    public function create(CreateVesselMachineryRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'installed_date' => Carbon::create($request->getInstalledDate()),
                'vessel_id' => $request->getVesselId(),
                'machinery_id' => $request->getMachineryId(),
                'incharge_rank_id' => $request->getInchargeRankId(),
                'interval_id' => $request->getIntervalId(),
            ];
            $vesselMachinery = $this->vesselMachineryService->create($formData);
            $this->response['data'] = new VesselMachineryResource($vesselMachinery);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Retrieves vessel machinery information
     *
     * @param VesselMachinery $vesselMachinery
     * @return JsonResponse
     */
    public function read(VesselMachinery $vesselMachinery): JsonResponse
    {
        try {
            $this->response['data'] = new VesselMachineryResource($vesselMachinery);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Updates vessel machinery information
     *
     * @param UpdateVesselMachineryRequest $request
     * @param VesselMachinery $vesselMachinery
     * @return JsonResponse
     */
    public function update(UpdateVesselMachineryRequest $request, VesselMachinery $vesselMachinery): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'installed_date' => Carbon::create($request->getInstalledDate()),
                'vessel_id' => $request->getVesselId(),
                'machinery_id' => $request->getMachineryId(),
                'incharge_rank_id' => $request->getInchargeRankId(),
                'interval_id' => $request->getIntervalId(),
            ];
            $vesselMachinery = $this->vesselMachineryService->update($formData, $vesselMachinery);
            $this->response['data'] = new VesselMachineryResource($vesselMachinery);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Delete vessel machinery
     *
     * @param VesselMachinery $vesselMachinery
     * @return JsonResponse
     */
    public function delete(VesselMachinery $vesselMachinery): JsonResponse
    {
        try {
            $this->response['deleted'] = $this->vesselMachineryService->delete($vesselMachinery);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }
}
