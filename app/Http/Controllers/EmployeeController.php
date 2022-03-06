<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\SearchEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Exception;

class EmployeeController extends Controller
{
    /** @var EmployeeService */
    protected $employeeService;

    /**
     * EmployeeController constructor
     *
     * @param EmployeeService $employeeService
     */
    public function __construct(EmployeeService $employeeService)
    {
        parent::__construct();

        $this->employeeService = $employeeService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

    /**
     * Retrieves the List of employees
     *
     * @param SearchEmployeeRequest $request
     * @return JsonResponse
     */
    public function index(SearchEmployeeRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $conditions = [
                'keyword' => $request->getKeyword(),
                'page' => $request->getPage(),
                'limit' => $request->getLimit(),
            ];
            $results = $this->employeeService->search($conditions);
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
     * Creates a new employee. Creator must be authenticated.
     *
     * @param CreateEmployeeRequest $request
     * @return JsonResponse
     */
    public function create(CreateEmployeeRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'first_name' => $request->getFirstName(),
                'middle_name' => $request->getMiddleName(),
                'last_name' => $request->getLastName(),
                'email' => $request->getEmail(),
                'password' => $request->getPassword(),
                'department' => $request->getDepartment(),
                'id_number' => $request->getIdNumber(),
                'position' => $request->getPosition(),
            ];
            $employee = $this->employeeService->create($formData);
            $this->response['data'] = new EmployeeResource($employee);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Retrieves employee information
     *
     * @param Employee $employee
     * @return JsonResponse
     */
    public function read(Employee $employee): JsonResponse
    {
        try {
            $this->response['data'] = new EmployeeResource($employee);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Updates employee information
     *
     * @param UpdateEmployeeRequest $request
     * @param Employee $employee
     * @return JsonResponse
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'first_name' => $request->getFirstName(),
                'middle_name' => $request->getMiddleName(),
                'last_name' => $request->getLastName(),
                'email' => $request->getEmail(),
                'password' => $request->getPassword(),
                'department' => $request->getDepartment(),
                'id_number' => $request->getIdNumber(),
                'position' => $request->getPosition(),
            ];
            $employee = $this->employeeService->update($formData, $employee);
            $this->response['data'] = new EmployeeResource($employee);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Delete employee
     *
     * @param Employee $interval
     * @return JsonResponse
     */
    public function delete(Employee $interval): JsonResponse
    {
        try {
            $this->response['deleted'] = $this->employeeService->delete($interval);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }
}
