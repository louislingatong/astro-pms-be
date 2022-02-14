<?php

namespace App\Services;

use App\Http\Resources\VesselMachinerySubCategoryWorkResource;
use App\Models\Interval;
use App\Models\VesselMachinery;
use App\Models\Work;
use App\Models\VesselMachinerySubCategory;
use Exception;
use Illuminate\Support\Facades\DB;

class WorkService
{
    /** @var VesselMachinerySubCategory $vesselSubCategory */
    protected $vesselSubCategory;

    /** @var Work $work */
    protected $work;

    /** @var VesselMachineryService $vesselMachineryService */
    protected $vesselMachineryService;

    /**
     * WorkService constructor.
     *
     * @param VesselMachinerySubCategory $vesselSubCategory
     * @param Work $work
     * @param VesselMachineryService $vesselMachineryService
     */
    public function __construct(
        VesselMachinerySubCategory $vesselSubCategory,
        Work $work,
        VesselMachineryService $vesselMachineryService
    )
    {
        $this->vesselSubCategory = $vesselSubCategory;
        $this->work = $work;
        $this->vesselMachineryService = $vesselMachineryService;
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

        $query = $this->vesselSubCategory;

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
            $lastDoneWork = $vesselMachinerySubCategory->works()->first();
            /** @var VesselMachinery $vesselMachinery */
            $vesselMachinery = $vesselMachinerySubCategory->vesselMachinery;
            /** @var Interval $interval */
            $interval = $vesselMachinery->interval;

            $vesselMachinery->machinery()->update([
                'due_date' => $this->vesselMachineryService->getDueDate(
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
