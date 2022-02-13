<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVesselMachinerySubCategoryRequest;
use App\Http\Requests\SearchSubCategoryRequest;
use App\Http\Requests\UpdateVesselMachinerySubCategoryRequest;
use App\Http\Resources\VesselMachinerySubCategoryResource;
use App\Models\VesselMachinerySubCategory;
use App\Services\VesselMachinerySubCategoryService;
use Exception;
use Illuminate\Http\JsonResponse;

class VesselMachinerySubCategoryController extends Controller
{
    /** @var VesselMachinerySubCategoryService */
    protected $vesselMachinerySubCategoryService;

    /**
     * VesselMachinerySubCategoryController constructor
     *
     * @param VesselMachinerySubCategoryService $vesselMachinerySubCategoryService
     */
    public function __construct(VesselMachinerySubCategoryService $vesselMachinerySubCategoryService)
    {
        parent::__construct();

        $this->vesselMachinerySubCategoryService = $vesselMachinerySubCategoryService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

    /**
     * Retrieves the List of vessel machinery sub category
     *
     * @param SearchSubCategoryRequest $request
     * @return JsonResponse
     */
    public function index(SearchSubCategoryRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $conditions = [
                'keyword' => $request->getKeyword(),
                'status' => $request->getStatus(),
                'page' => $request->getPage(),
                'limit' => $request->getLimit(),
            ];
            $results = $this->vesselMachinerySubCategoryService->search($conditions);
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
     * Creates a new vessel machinery sub category. Creator must be authenticated.
     *
     * @param CreateVesselMachinerySubCategoryRequest $request
     * @return JsonResponse
     */
    public function create(CreateVesselMachinerySubCategoryRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'vessel_machinery_id' => $request->getVesselMachineryId(),
                'code' => $request->getCode(),
                'sub_category_id' => $request->getSubCategoryId(),
                'description' => $request->getDescription(),
            ];
            $subCategory = $this->vesselMachinerySubCategoryService->create($formData);
            $this->response['data'] = new VesselMachinerySubCategoryResource($subCategory);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Retrieves vessel machinery sub category information
     *
     * @param VesselMachinerySubCategory $vesselMachinerySubCategory
     * @return JsonResponse
     */
    public function read(VesselMachinerySubCategory $vesselMachinerySubCategory): JsonResponse
    {
        try {
            $this->response['data'] = new VesselMachinerySubCategoryResource($vesselMachinerySubCategory);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Updates vessel machinery sub category information
     *
     * @param UpdateVesselMachinerySubCategoryRequest $request
     * @param VesselMachinerySubCategory $vesselMachinerySubCategory
     * @return JsonResponse
     */
    public function update(
        UpdateVesselMachinerySubCategoryRequest $request,
        VesselMachinerySubCategory $vesselMachinerySubCategory
    ): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'vessel_machinery_id' => $request->getVesselMachineryId(),
                'code' => $request->getCode(),
                'sub_category_id' => $request->getSubCategoryId(),
                'description' => $request->getDescription(),
            ];
            $subCategory = $this->vesselMachinerySubCategoryService->update($formData, $vesselMachinerySubCategory);
            $this->response['data'] = new VesselMachinerySubCategoryResource($subCategory);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Delete vessel machinery sub category
     *
     * @param VesselMachinerySubCategory $vesselMachinerySubCategory
     * @return JsonResponse
     */
    public function delete(VesselMachinerySubCategory $vesselMachinerySubCategory): JsonResponse
    {
        try {
            $this->response['deleted'] = $this->vesselMachinerySubCategoryService->delete($vesselMachinerySubCategory);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }
}
