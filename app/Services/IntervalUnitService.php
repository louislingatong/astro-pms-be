<?php

namespace App\Services;

use App\Http\Resources\IntervalUnitResource;
use App\Models\IntervalUnit;

class IntervalUnitService
{
    /** @var IntervalUnit $intervalUnit */
    protected $intervalUnit;

    /**
     * IntervalUnitService constructor.
     *
     * @param IntervalUnit $intervalUnit
     */
    public function __construct(IntervalUnit $intervalUnit)
    {
        $this->intervalUnit = $intervalUnit;
    }

    /**
     * List of interval units by conditions
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

        $query = $this->intervalUnit;

        if ($conditions['keyword']) {
            $query = $query->search($conditions['keyword']);
        }

        $results = $query->skip($skip)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        $urlParams = ['keyword' => $conditions['keyword'], 'limit' => $limit];

        return paginated($results, IntervalUnitResource::class, $page, $urlParams);
    }
}
