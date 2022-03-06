<?php

namespace App\Services;

use App\Http\Resources\EmployeeDepartmentResource;
use App\Models\EmployeeDepartment;

class EmployeeDepartmentService
{
    /** @var EmployeeDepartment $employeeDepartment */
    protected $employeeDepartment;

    /**
     * EmployeeDepartmentService constructor.
     *
     * @param EmployeeDepartment $employeeDepartment
     */
    public function __construct(EmployeeDepartment $employeeDepartment)
    {
        $this->employeeDepartment = $employeeDepartment;
    }

    /**
     * List of employee departments by conditions
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

        $query = $this->employeeDepartment;

        if ($conditions['keyword']) {
            $query = $query->search($conditions['keyword']);
        }

        $results = $query->skip($skip)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        $urlParams = ['keyword' => $conditions['keyword'], 'limit' => $limit];

        return paginated($results, EmployeeDepartmentResource::class, $page, $urlParams);
    }
}
