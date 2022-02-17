<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMachineryRequest;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\SearchMachineryRequest;
use App\Http\Requests\UpdateMachineryRequest;
use App\Http\Resources\MachineryResource;
use App\Imports\MachineryImport;
use App\Models\Machinery;
use App\Services\MachineryService;
use Exception;
use Illuminate\Http\JsonResponse;

class MachineryController extends Controller
{
    /** @var MachineryService */
    protected $machineryService;

    /**
     * MachineryController constructor
     *
     * @param MachineryService $machineryService
     */
    public function __construct(MachineryService $machineryService)
    {
        parent::__construct();

        $this->machineryService = $machineryService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

    /**
     * Retrieves the List of machinery
     *
     * @param SearchMachineryRequest $request
     * @return JsonResponse
     */
    public function index(SearchMachineryRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $conditions = [
                'keyword' => $request->getKeyword(),
                'page' => $request->getPage(),
                'limit' => $request->getLimit(),
            ];
            $results = $this->machineryService->search($conditions);
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
     * Creates a new machinery. Creator must be authenticated.
     *
     * @param CreateMachineryRequest $request
     * @return JsonResponse
     */
    public function create(CreateMachineryRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'vessel_department_id' => $request->getDepartmentId(),
                'code_name' => $request->getCodeName(),
                'name' => $request->getName(),
                'model' => $request->getModel(),
                'maker' => $request->getMaker(),
            ];
            $machinery = $this->machineryService->create($formData);
            $this->response['data'] = new MachineryResource($machinery);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Retrieves machinery information
     *
     * @param Machinery $machinery
     * @return JsonResponse
     */
    public function read(Machinery $machinery): JsonResponse
    {
        try {
            $this->response['data'] = new MachineryResource($machinery);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Updates machinery information
     *
     * @param UpdateMachineryRequest $request
     * @param Machinery $machinery
     * @return JsonResponse
     */
    public function update(UpdateMachineryRequest $request, Machinery $machinery): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'vessel_department_id' => $request->getDepartmentId(),
                'code_name' => $request->getCodeName(),
                'name' => $request->getName(),
                'model' => $request->getModel(),
                'maker' => $request->getMaker(),
            ];
            $machinery = $this->machineryService->update($formData, $machinery);
            $this->response['data'] = new MachineryResource($machinery);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Delete machinery
     *
     * @param Machinery $machinery
     * @return JsonResponse
     */
    public function delete(Machinery $machinery): JsonResponse
    {
        try {
            $this->response['deleted'] = $this->machineryService->delete($machinery);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Import machinery
     *
     * @param ImportRequest $request
     * @return JsonResponse
     * @throws
     */
    public function import(ImportRequest $request): JsonResponse
    {
        $import = new MachineryImport;
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
