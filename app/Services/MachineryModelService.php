<?php

namespace App\Services;

use App\Http\Resources\MachineryModelResource;
use App\Models\MachineryModel;

class MachineryModelService
{
    /** @var MachineryModel $machineryModel */
    protected $machineryModel;

    /**
     * MachineryModelService constructor.
     *
     * @param MachineryModel $machineryModel
     */
    public function __construct(MachineryModel $machineryModel)
    {
        $this->machineryModel = $machineryModel;
    }

    /**
     * List of machinery models by conditions
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

        $query = $this->machineryModel;

        if ($conditions['keyword']) {
            $query = $query->search($conditions['keyword']);
        }

        $results = $query->skip($skip)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        $urlParams = ['keyword' => $conditions['keyword'], 'limit' => $limit];

        return paginated($results, MachineryModelResource::class, $page, $urlParams);
    }
}
