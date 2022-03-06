<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchEmployeeDepartmentRequest;
use App\Services\EmployeeDepartmentService;
use Illuminate\Http\JsonResponse;
use Exception;

class EmployeeDepartmentController extends Controller
{
    /** @var EmployeeDepartmentService */
    protected $employeeDepartmentService;

    /**
     * EmployeeDepartmentController constructor
     *
     * @param EmployeeDepartmentService $employeeDepartmentService
     */
    public function __construct(EmployeeDepartmentService $employeeDepartmentService)
    {
        parent::__construct();

        $this->employeeDepartmentService = $employeeDepartmentService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

    /**
     * Retrieves the List of employees
     *
     * @param SearchEmployeeDepartmentRequest $request
     * @return JsonResponse
     */
    public function index(SearchEmployeeDepartmentRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $conditions = [
                'keyword' => $request->getKeyword(),
                'page' => $request->getPage(),
                'limit' => $request->getLimit(),
            ];
            $results = $this->employeeDepartmentService->search($conditions);
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
