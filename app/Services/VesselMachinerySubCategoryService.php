<?php

namespace App\Services;

use App\Exceptions\VesselMachinerySubCategoryNotFoundException;
use App\Http\Resources\VesselMachinerySubCategoryResource;
use App\Models\SubCategoryDescription;
use App\Models\VesselMachinerySubCategory;
use Exception;
use Illuminate\Support\Facades\DB;

class VesselMachinerySubCategoryService
{
    /** @var VesselMachinerySubCategory $vesselMachinerySubCategory */
    protected $vesselMachinerySubCategory;

    /** @var SubCategoryDescription $description */
    protected $description;

    /**
     * VesselMachinerySubCategoryService constructor.
     *
     * @param VesselMachinerySubCategory $vesselMachinerySubCategory
     * @param SubCategoryDescription $description
     */
    public function __construct(
        VesselMachinerySubCategory $vesselMachinerySubCategory,
        SubCategoryDescription $description
    )
    {
        $this->vesselMachinerySubCategory = $vesselMachinerySubCategory;
        $this->description = $description;
    }

    /**
     * List of vessel machinery sub category by conditions
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

        $query = $this->vesselMachinerySubCategory;

        if ($conditions['keyword']) {
            $query = $query->search($conditions['keyword']);
        }

        $results = $query->skip($skip)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        $urlParams = ['keyword' => $conditions['keyword'], 'limit' => $limit];

        return paginated($results, VesselMachinerySubCategoryResource::class, $page, $urlParams);
    }

    /**
     * Creates a new vessel machinery sub category in the database
     *
     * @param array $params
     * @return VesselMachinerySubCategory
     * @throws
     */
    public function create(array $params): VesselMachinerySubCategory
    {
        DB::beginTransaction();

        try {
            if ($params['description']) {
                $this->description = $this->description->firstOrCreate(['name' => $params['description']]);
            }

            if ($this->description->getAttribute('id')) {
                $params['sub_category_description_id'] = $this->description->getAttribute('id');
            }

            /** @var VesselMachinerySubCategory $vesselSubCategory */
            $vesselSubCategory = $this->vesselMachinerySubCategory->create($params);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }

        return $vesselSubCategory;
    }

    /**
     * Updates vessel machinery sub category in the database
     *
     * @param array $params
     * @param VesselMachinerySubCategory $vesselMachinerySubCategory
     * @return VesselMachinerySubCategory
     * @throws
     */
    public function update(array $params, VesselMachinerySubCategory $vesselMachinerySubCategory): VesselMachinerySubCategory
    {
        DB::beginTransaction();

        try {
            if ($params['description']) {
                $this->description = $this->description->firstOrCreate(['name' => $params['description']]);
            }

            if ($this->description->getAttribute('id')) {
                $params['sub_category_description_id'] = $this->description->getAttribute('id');
            }

            $vesselMachinerySubCategory->update($params);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }
        return $vesselMachinerySubCategory;
    }

    /**
     * Deletes the vessel machinery sub category in the database
     *
     * @param VesselMachinerySubCategory $vesselMachinerySubCategory
     * @return bool
     * @throws
     */
    public function delete(VesselMachinerySubCategory $vesselMachinerySubCategory): bool
    {
        if (!($vesselMachinerySubCategory instanceof VesselMachinerySubCategory)) {
            throw new VesselMachinerySubCategoryNotFoundException();
        }
        $vesselMachinerySubCategory->delete();
        return true;
    }
}
