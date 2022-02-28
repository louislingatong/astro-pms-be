<?php

namespace App\Services;

use App\Http\Resources\MachineryMakerResource;
use App\Models\MachineryMaker;

class MachineryMakerService
{
    /** @var MachineryMaker $machineryMaker */
    protected $machineryMaker;

    /**
     * MachineryMakerService constructor.
     *
     * @param MachineryMaker $machineryMaker
     */
    public function __construct(MachineryMaker $machineryMaker)
    {
        $this->machineryMaker = $machineryMaker;
    }

    /**
     * List of machinery makers by conditions
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

        $query = $this->machineryMaker;

        if ($conditions['keyword']) {
            $query = $query->search($conditions['keyword']);
        }

        $results = $query->skip($skip)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        $urlParams = ['keyword' => $conditions['keyword'], 'limit' => $limit];

        return paginated($results, MachineryMakerResource::class, $page, $urlParams);
    }
}
