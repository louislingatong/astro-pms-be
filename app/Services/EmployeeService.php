<?php

namespace App\Services;

use App\Exceptions\IntervalNotFoundException;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\EmployeeDepartment;
use App\Models\Interval;
use Illuminate\Support\Facades\DB;
use Exception;

class EmployeeService
{
    /** @var Employee $employee */
    protected $employee;

    /** @var UserService $userService */
    protected $userService;

    /**
     * EmployeeService constructor.
     *
     * @param Employee $employee
     * @param UserService $userService
     */
    public function __construct(Employee $employee, UserService $userService)
    {
        $this->employee = $employee;
        $this->userService = $userService;
    }

    /**
     * List of vessel departments by conditions
     *
     * @param array $conditions
     * @return array
     * @throws
     */
    public function search(array $conditions): array
    {
        $page = 1;
        $limit = config('search.results_per_page');

        if ($conditions['page']) {
            $page = $conditions['page'];
        }

        if ($conditions['limit']) {
            $limit = $conditions['limit'];
        }

        $skip = ($page > 1) ? ($page * $limit - $limit) : 0;

        $query = $this->employee;

        if ($conditions['keyword']) {
            $query = $query->search($conditions['keyword']);
        }

        $results = $query->skip($skip)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        $urlParams = ['keyword' => $conditions['keyword'], 'limit' => $limit];

        return paginated($results, EmployeeResource::class, $page, $urlParams);
    }

    /**
     * Creates a new employee in the database
     *
     * @param array $params
     * @return Employee
     * @throws
     */
    public function create(array $params): Employee
    {
        DB::beginTransaction();

        try {
            $user = $this->userService->create([
                'first_name' => $params['first_name'],
                'middle_name' => $params['middle_name'],
                'last_name' => $params['last_name'],
                'email' => $params['email'],
                'password' => $params['password'],
            ]);

            /** @var EmployeeDepartment $employeeDepartment */
            $employeeDepartment = EmployeeDepartment::whereName($params['department'])->first();

            $employee = $this->employee->create([
                'user_id' => $user->getAttribute('id'),
                'employee_department_id' => $employeeDepartment->getAttribute('id'),
                'id_number' => $params['id_number'],
                'position' => $params['position'],
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }

        return $employee;
    }

    /**
     * Updates employee in the database
     *
     * @param array $params
     * @param Employee $employee
     * @return Employee
     * @throws
     */
    public function update(array $params, Employee $employee): Employee
    {
        DB::beginTransaction();

        try {
            $this->userService->update([
                'first_name' => $params['first_name'],
                'middle_name' => $params['middle_name'],
                'last_name' => $params['last_name'],
                'email' => $params['email'],
                'password' => $params['password'],
            ], $employee->user);

            /** @var EmployeeDepartment $employeeDepartment */
            $employeeDepartment = EmployeeDepartment::whereName($params['department'])->first();

            $employee->update([
                'employee_department_id' => $employeeDepartment->getAttribute('id'),
                'id_number' => $params['id_number'],
                'position' => $params['position'],
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }
        return $employee;
    }

    /**
     * Deletes the employee in the database
     *
     * @param Employee $employee
     * @return bool
     * @throws
     */
    public function delete(Employee $employee): bool
    {
        if (!($employee instanceof Employee)) {
            throw new IntervalNotFoundException();
        }
        $employee->delete();
        $this->userService->delete($employee->user);
        return true;
    }
}
