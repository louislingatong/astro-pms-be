<?php

namespace App\Services;

use App\Http\Resources\RankResource;
use App\Models\Rank;

class RankService
{
    /** @var Rank $rank */
    protected $rank;

    /**
     * RankService constructor.
     *
     * @param Rank $rank
     */
    public function __construct(Rank $rank)
    {
        $this->rank = $rank;
    }

    /**
     * List of ranks by conditions
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

        $query = $this->rank;

        if ($conditions['keyword']) {
            $query = $query->search($conditions['keyword']);
        }

        $results = $query->skip($skip)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        $urlParams = ['keyword' => $conditions['keyword'], 'limit' => $limit];

        return paginated($results, RankResource::class, $page, $urlParams);
    }
}
