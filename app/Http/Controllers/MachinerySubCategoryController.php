<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMachinerySubCategoryRequest;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\SearchMachinerySubCategoryRequest;
use App\Http\Requests\UpdateMachinerySubCategoryRequest;
use App\Http\Resources\MachinerySubCategoryResource;
use App\Imports\MachinerySubCategoryImport;
use App\Models\MachinerySubCategory;
use App\Services\MachinerySubCategoryService;
use Exception;
use Illuminate\Http\JsonResponse;

class MachinerySubCategoryController extends Controller
{
    /** @var MachinerySubCategoryService */
    protected $subCategoryService;

    /**
     * MachinerySubCategoryController constructor
     *
     * @param MachinerySubCategoryService $subCategoryService
     */
    public function __construct(MachinerySubCategoryService $subCategoryService)
    {
        parent::__construct();

        $this->subCategoryService = $subCategoryService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

    /**
     * Retrieves the List of machinery sub category
     *
     * @param SearchMachinerySubCategoryRequest $request
     * @return JsonResponse
     */
    public function index(SearchMachinerySubCategoryRequest $request): JsonResponse
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
     * Creates a new machinery sub category. Creator must be authenticated.
     *
     * @param CreateMachinerySubCategoryRequest $request
     * @return JsonResponse
     */
    public function create(CreateMachinerySubCategoryRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'machinery_id' => $request->getMachineryId(),
                'name' => $request->getName(),
            ];
            $subCategory = $this->subCategoryService->create($formData);
            $this->response['data'] = new MachinerySubCategoryResource($subCategory);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Retrieves machinery sub category information
     *
     * @param MachinerySubCategory $subCategory
     * @return JsonResponse
     */
    public function read(MachinerySubCategory $subCategory): JsonResponse
    {
        try {
            $this->response['data'] = new MachinerySubCategoryResource($subCategory);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Updates machinery sub category information
     *
     * @param UpdateMachinerySubCategoryRequest $request
     * @param MachinerySubCategory $subCategory
     * @return JsonResponse
     */
    public function update(
        UpdateMachinerySubCategoryRequest $request,
        MachinerySubCategory $subCategory
    ): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'machinery_id' => $request->getMachineryId(),
                'name' => $request->getName(),
            ];
            $subCategory = $this->subCategoryService->update($formData, $subCategory);
            $this->response['data'] = new MachinerySubCategoryResource($subCategory);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Delete machinery sub category
     *
     * @param MachinerySubCategory $subCategory
     * @return JsonResponse
     */
    public function delete(MachinerySubCategory $subCategory): JsonResponse
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

    /**
     * Import machinery sub category
     *
     * @param ImportRequest $request
     * @return JsonResponse
     * @throws
     */
    public function import(ImportRequest $request): JsonResponse
    {
        $import = new MachinerySubCategoryImport();
        $import->import($request->getFile());

        if ($import->failures()->isNotEmpty()) {
            $this->response = [
                'error' => $import->failures(),
                'code' => 422,
            ];
        }

        if ($import->errors()->isNotEmpty()) {
            $this->response = [
                'error' => $import->errors(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }
}
