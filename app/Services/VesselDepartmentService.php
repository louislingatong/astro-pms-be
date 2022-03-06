<?php

namespace App\Services;

use App\Http\Resources\VesselDepartmentResource;
use App\Models\VesselDepartment;

class VesselDepartmentService
{
    /** @var VesselDepartment $vesselDepartment */
    protected $vesselDepartment;

    /**
     * VesselDepartmentService constructor.
     *
     * @param VesselDepartment $vesselDepartment
     */
    public function __construct(VesselDepartment $vesselDepartment)
    {
        $this->vesselDepartment = $vesselDepartment;
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

        $query = $this->vesselDepartment;

        if ($conditions['keyword']) {
            $query = $query->search($conditions['keyword']);
        }

        $results = $query->skip($skip)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        $urlParams = ['keyword' => $conditions['keyword'], 'limit' => $limit];

        return paginated($results, VesselDepartmentResource::class, $page, $urlParams);
    }
}
