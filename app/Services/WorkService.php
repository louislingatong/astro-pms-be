<?php

namespace App\Services;

use App\Http\Resources\VesselMachinerySubCategoryWorkResource;
use App\Models\Interval;
use App\Models\VesselMachinerySubCategory;
use App\Models\Work;
use Exception;
use Illuminate\Support\Facades\DB;

class WorkService
{
    /** @var VesselMachinerySubCategory $vesselMachinerySubCategory */
    protected $vesselMachinerySubCategory;

    /** @var Work $work */
    protected $work;

    /** @var VesselMachinerySubCategoryService $vesselMachinerySubCategoryService */
    protected $vesselMachinerySubCategoryService;

    /**
     * WorkService constructor.
     *
     * @param VesselMachinerySubCategory $vesselMachinerySubCategory
     * @param Work $work
     * @param VesselMachinerySubCategoryService $vesselMachinerySubCategoryService
     */
    public function __construct(
        VesselMachinerySubCategory $vesselMachinerySubCategory,
        Work $work,
        VesselMachinerySubCategoryService $vesselMachinerySubCategoryService
    )
    {
        $this->vesselMachinerySubCategory = $vesselMachinerySubCategory;
        $this->work = $work;
        $this->vesselMachinerySubCategoryService = $vesselMachinerySubCategoryService;
    }

    /**
     * List of vessel sub category with job by conditions
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

        $query = $this->vesselMachinerySubCategory->whereHas('vesselMachinery', function ($q) use ($conditions) {
            $q->where('vessel_id', '=', $conditions['vessel_id']);
        });

        if ($conditions['keyword']) {
            $query = $query->search($conditions['keyword']);
        }

        $results = $query->skip($skip)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        $urlParams = ['keyword' => $conditions['keyword'], 'limit' => $limit];

        return paginated($results, VesselMachinerySubCategoryWorkResource::class, $page, $urlParams);
    }

    /**
     * Creates a new job of vessel sub category in the database
     *
     * @param array $params
     * @return Work
     * @throws
     */
    public function create(array $params): Work
    {
        DB::beginTransaction();

        try {
            /** @var Work $work */
            $work = $this->work->create($params);
            /** @var VesselMachinerySubCategory $vesselMachinerySubCategory */
            $vesselMachinerySubCategory = $work->vesselMachinerySubCategory;
            /** @var Work $lastDoneWork */
            $lastDoneWork = $vesselMachinerySubCategory->currentWork;
            /** @var Interval $interval */
            $interval = $vesselMachinerySubCategory->interval;

            $vesselMachinerySubCategory->update([
                'due_date' => $this->vesselMachinerySubCategoryService->getDueDate(
                    $lastDoneWork->getAttribute('last_done'),
                    $interval
                )
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }

        return $work;
    }
}
