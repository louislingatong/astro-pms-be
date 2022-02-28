<?php

namespace App\Services;

use App\Http\Resources\VesselOwnerResource;
use App\Models\VesselDepartment;
use App\Models\VesselOwner;

class VesselOwnerService
{
    /** @var VesselOwner $vesselOwner */
    protected $vesselOwner;

    /**
     * VesselOwnerService constructor.
     *
     * @param VesselOwner $vesselOwner
     */
    public function __construct(VesselDepartment $vesselOwner)
    {
        $this->vesselOwner = $vesselOwner;
    }

    /**
     * List of vessel owners by conditions
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

        $query = $this->vesselOwner;

        if ($conditions['keyword']) {
            $query = $query->search($conditions['keyword']);
        }

        $results = $query->skip($skip)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        $urlParams = ['keyword' => $conditions['keyword'], 'limit' => $limit];

        return paginated($results, VesselOwnerResource::class, $page, $urlParams);
    }
}
