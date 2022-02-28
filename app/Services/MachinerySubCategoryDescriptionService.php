<?php

namespace App\Services;

use App\Http\Resources\MachinerySubCategoryDescriptionResource;
use App\Models\MachinerySubCategoryDescription;

class MachinerySubCategoryDescriptionService
{
    /** @var MachinerySubCategoryDescription $machinerySubCategoryDescription */
    protected $machinerySubCategoryDescription;

    /**
     * MachinerySubCategoryDescriptionService constructor.
     *
     * @param MachinerySubCategoryDescription $machinerySubCategoryDescription
     */
    public function __construct(MachinerySubCategoryDescription $machinerySubCategoryDescription)
    {
        $this->machinerySubCategoryDescription = $machinerySubCategoryDescription;
    }

    /**
     * List of machinery sub category descriptions by conditions
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

        $query = $this->machinerySubCategoryDescription;

        if ($conditions['keyword']) {
            $query = $query->search($conditions['keyword']);
        }

        $results = $query->skip($skip)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        $urlParams = ['keyword' => $conditions['keyword'], 'limit' => $limit];

        return paginated($results, MachinerySubCategoryDescriptionResource::class, $page, $urlParams);
    }
}
