<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVesselRequest;
use App\Http\Requests\SearchVesselRequest;
use App\Http\Requests\UpdateVesselRequest;
use App\Http\Resources\VesselResource;
use App\Models\Vessel;
use App\Services\VesselService;
use Exception;
use Illuminate\Http\JsonResponse;

class VesselController extends Controller
{
    /** @var VesselService */
    protected $vesselService;

    /**
     * VesselController constructor
     *
     * @param VesselService $vesselService
     */
    public function __construct(VesselService $vesselService)
    {
        parent::__construct();

        $this->vesselService = $vesselService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

    /**
     * Retrieves the List of vessel
     *
     * @param SearchVesselRequest $request
     * @return JsonResponse
     */
    public function index(SearchVesselRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $conditions = [
                'keyword' => $request->getKeyword(),
                'page' => $request->getPage(),
                'limit' => $request->getLimit(),
            ];
            $results = $this->vesselService->search($conditions);
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
     * Creates a new vessel. Creator must be authenticated.
     *
     * @param CreateVesselRequest $request
     * @return JsonResponse
     */
    public function create(CreateVesselRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'vessel_owner_id' => $request->getOwnerId(),
                'code_name' => $request->getCodeName(),
                'name' => $request->getName(),
            ];
            $vessel = $this->vesselService->create($formData);
            $this->response['data'] = new VesselResource($vessel);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Retrieves vessel information
     *
     * @param Vessel $vessel
     * @return JsonResponse
     */
    public function read(Vessel $vessel): JsonResponse
    {
        try {
            $this->response['data'] = new VesselResource($vessel);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Updates vessel information
     *
     * @param UpdateVesselRequest $request
     * @param Vessel $vessel
     * @return JsonResponse
     */
    public function update(UpdateVesselRequest $request, Vessel $vessel): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'vessel_owner_id' => $request->getOwnerId(),
                'code_name' => $request->getCodeName(),
                'name' => $request->getName(),
            ];
            $vessel = $this->vesselService->update($formData, $vessel);
            $this->response['data'] = new VesselResource($vessel);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Delete vessel
     *
     * @param Vessel $vessel
     * @return JsonResponse
     */
    public function delete(Vessel $vessel): JsonResponse
    {
        try {
            $this->response['deleted'] = $this->vesselService->delete($vessel);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }
}
