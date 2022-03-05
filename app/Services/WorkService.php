<?php

namespace App\Services;

use App\Http\Resources\VesselMachinerySubCategoryWorkResource;
use App\Models\Interval;
use App\Models\IntervalUnit;
use App\Models\VesselMachinery;
use App\Models\VesselMachinerySubCategory;
use App\Models\Work;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class WorkService
{
    /** @var VesselMachinerySubCategory $vesselMachinerySubCategory */
    protected $vesselMachinerySubCategory;

    /** @var Work $work */
    protected $work;

    /**
     * WorkService constructor.
     *
     * @param VesselMachinerySubCategory $vesselMachinerySubCategory
     * @param Work $work
     */
    public function __construct(
        VesselMachinerySubCategory $vesselMachinerySubCategory,
        Work $work
    )
    {
        $this->vesselMachinerySubCategory = $vesselMachinerySubCategory;
        $this->work = $work;
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

        $query = $this->vesselMachinerySubCategory->whereHas('vesselMachinery.vessel', function ($q) use ($conditions) {
            $q->where('name', '=', $conditions['vessel']);
        });

        if ($conditions['department']) {
            $query = $query->whereHas('vesselMachinery.machinery.department', function ($q) use ($conditions) {
                $q->where('name', '=', $conditions['department']);
            });
        }

        if ($conditions['machinery']) {
            $query = $query->whereHas('vesselMachinery.machinery', function ($q) use ($conditions) {
                $q->where('name', '=', $conditions['machinery']);
            });
        }

        if ($conditions['status']) {
            $query = $query->searchByStatus($conditions['status']);
        }

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
     * @return Collection
     * @throws
     */
    public function create(array $params): Collection
    {
        DB::beginTransaction();

        $updatedVesselMachinerySubCategory = collect([]);

        try {
            foreach ($params['vessel_machinery_sub_category_Ids'] as $id) {
                /** @var Work $work */
                $work = $this->work->create([
                    'vessel_machinery_sub_category_id' => $id,
                    'last_done' => $params['last_done'],
                    'instructions' => $params['instructions'],
                    'remarks' => $params['remarks'],
                    'creator_id' => $params['creator_id'],
                ]);

                /** @var VesselMachinerySubCategory $vesselMachinerySubCategory */
                $vesselMachinerySubCategory = $work->vesselMachinerySubCategory;
                /** @var Interval $interval */
                $interval = $vesselMachinerySubCategory->interval;

                $vesselMachinerySubCategory->update([
                    'due_date' => $this->getDueDate(
                        $work->getAttribute('last_done'),
                        $interval
                    )
                ]);

                $updatedVesselMachinerySubCategory->push($vesselMachinerySubCategory);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }

        return $updatedVesselMachinerySubCategory;
    }

    /**
     * Get the job due date
     *
     * @param string $date
     * @param Interval $interval
     * @return Carbon
     */
    public function getDueDate(string $date, Interval $interval): Carbon
    {
        $dueDate = Carbon::create($date);

        /** @var IntervalUnit $intervalUnit */
        $intervalUnit = $interval->unit;
        switch ($intervalUnit->getAttribute('name')) {
            case config('interval.units.days'):
                $dueDate->addDays($interval->getAttribute('value'));
                break;
            case config('interval.units.hours'):
                $dueDate->addHours($interval->getAttribute('value'));
                break;
            case config('interval.units.weeks'):
                $dueDate->addWeeks($interval->getAttribute('value'));
                break;
            case config('interval.units.months'):
                $dueDate->addMonths($interval->getAttribute('value'));
                break;
            case config('interval.units.years'):
                $dueDate->addYears($interval->getAttribute('value'));
                break;
        }
        return $dueDate;
    }
}
