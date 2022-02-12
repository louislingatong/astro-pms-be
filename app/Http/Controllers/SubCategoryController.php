<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubCategoryRequest;
use App\Http\Requests\SearchSubCategoryRequest;
use App\Http\Requests\UpdateSubCategoryRequest;
use App\Http\Resources\SubCategoryResource;
use App\Models\SubCategory;
use App\Services\SubCategoryService;
use Exception;
use Illuminate\Http\JsonResponse;

class SubCategoryController extends Controller
{
    /** @var SubCategoryService */
    protected $subCategoryService;

    /**
     * SubCategoryController constructor
     *
     * @param SubCategoryService $subCategoryService
     */
    public function __construct(SubCategoryService $subCategoryService)
    {
        parent::__construct();

        $this->subCategoryService = $subCategoryService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

    /**
     * Retrieves the List of sub category
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
                'page' => $request->getPage(),
                'limit' => $request->getLimit(),
            ];
            $results = $this->subCategoryService->search($conditions);
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
     * Creates a new sub category. Creator must be authenticated.
     *
     * @param CreateSubCategoryRequest $request
     * @return JsonResponse
     */
    public function create(CreateSubCategoryRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'machinery_id' => $request->getMachineryId(),
                'name' => $request->getName(),
            ];
            $subCategory = $this->subCategoryService->create($formData);
            $this->response['data'] = new SubCategoryResource($subCategory);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Retrieves sub category information
     *
     * @param SubCategory $subCategory
     * @return JsonResponse
     */
    public function read(SubCategory $subCategory): JsonResponse
    {
        try {
            $this->response['data'] = new SubCategoryResource($subCategory);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Updates sub category information
     *
     * @param UpdateSubCategoryRequest $request
     * @param SubCategory $subCategory
     * @return JsonResponse
     */
    public function update(UpdateSubCategoryRequest $request, SubCategory $subCategory): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'machinery_id' => $request->getMachineryId(),
                'name' => $request->getName(),
            ];
            $subCategory = $this->subCategoryService->update($formData, $subCategory);
            $this->response['data'] = new SubCategoryResource($subCategory);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Delete sub category
     *
     * @param SubCategory $subCategory
     * @return JsonResponse
     */
    public function delete(SubCategory $subCategory): JsonResponse
    {
        try {
            $this->response['deleted'] = $this->subCategoryService->delete($subCategory);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }
}
