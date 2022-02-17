<?php

namespace App\Services;

use App\Exceptions\SubCategoryNotFoundException;
use App\Http\Resources\MachinerySubCategoryResource;
use App\Models\MachinerySubCategory;
use Exception;
use Illuminate\Support\Facades\DB;

class MachinerySubCategoryService
{
    /** @var MachinerySubCategory $subCategory */
    protected $subCategory;

    /**
     * MachinerySubCategoryService constructor.
     *
     * @param MachinerySubCategory $subCategory
     */
    public function __construct(MachinerySubCategory $subCategory)
    {
        $this->subCategory = $subCategory;
    }

    /**
     * List of machinery sub category by conditions
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

        $query = $this->subCategory;

        if ($conditions['keyword']) {
            $query = $query->search($conditions['keyword']);
        }

        $results = $query->skip($skip)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        $urlParams = ['keyword' => $conditions['keyword'], 'limit' => $limit];

        return paginated($results, MachinerySubCategoryResource::class, $page, $urlParams);
    }

    /**
     * Creates a new machinery sub category in the database
     *
     * @param array $params
     * @return MachinerySubCategory
     * @throws
     */
    public function create(array $params): MachinerySubCategory
    {
        DB::beginTransaction();

        try {
            $subCategory = $this->subCategory->create($params);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }

        return $subCategory;
    }

    /**
     * Updates machinery sub category in the database
     *
     * @param array $params
     * @param MachinerySubCategory $subCategory
     * @return MachinerySubCategory
     * @throws
     */
    public function update(array $params, MachinerySubCategory $subCategory): MachinerySubCategory
    {
        $subCategory->update($params);
        return $subCategory;
    }

    /**
     * Deletes the machinery sub category in the database
     *
     * @param MachinerySubCategory $subCategory
     * @return bool
     * @throws
     */
    public function delete(MachinerySubCategory $subCategory): bool
    {
        if (!($subCategory instanceof MachinerySubCategory)) {
            throw new SubCategoryNotFoundException();
        }
        $subCategory->delete();
        return true;
    }
}
