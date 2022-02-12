<?php

namespace App\Services;

use App\Exceptions\SubCategoryNotFoundException;
use App\Http\Resources\SubCategoryResource;
use App\Models\SubCategory;
use Exception;
use Illuminate\Support\Facades\DB;

class SubCategoryService
{
    /** @var SubCategory $subCategory */
    protected $subCategory;

    /**
     * SubCategoryService constructor.
     *
     * @param SubCategory $subCategory
     */
    public function __construct(SubCategory $subCategory)
    {
        $this->subCategory = $subCategory;
    }

    /**
     * List of sub category by conditions
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

        return paginated($results, SubCategoryResource::class, $page, $urlParams);
    }

    /**
     * Creates a new sub category in the database
     *
     * @param array $params
     * @return SubCategory
     * @throws
     */
    public function create(array $params): SubCategory
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
     * Updates sub category in the database
     *
     * @param array $params
     * @param SubCategory $subCategory
     * @return SubCategory
     * @throws
     */
    public function update(array $params, SubCategory $subCategory): SubCategory
    {
        $subCategory->update($params);
        return $subCategory;
    }

    /**
     * Deletes the sub category in the database
     *
     * @param SubCategory $subCategory
     * @return bool
     * @throws
     */
    public function delete(SubCategory $subCategory): bool
    {
        if (!($subCategory instanceof SubCategory)) {
            throw new SubCategoryNotFoundException();
        }
        $subCategory->delete();
        return true;
    }
}
