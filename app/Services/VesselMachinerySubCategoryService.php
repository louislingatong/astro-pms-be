<?php

namespace App\Services;

use App\Exceptions\VesselMachinerySubCategoryNotFoundException;
use App\Http\Resources\VesselMachinerySubCategoryResource;
use App\Models\Interval;
use App\Models\IntervalUnit;
use App\Models\MachinerySubCategory;
use App\Models\VesselMachinery;
use App\Models\VesselMachinerySubCategory;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class VesselMachinerySubCategoryService
{
    /** @var VesselMachinerySubCategory $vesselMachinerySubCategory */
    protected $vesselMachinerySubCategory;

    /**
     * VesselMachinerySubCategoryService constructor.
     *
     * @param VesselMachinerySubCategory $vesselMachinerySubCategory
     */
    public function __construct(
        VesselMachinerySubCategory $vesselMachinerySubCategory,
    )
    {
        $this->vesselMachinerySubCategory = $vesselMachinerySubCategory;
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
            /** @var VesselMachinery $vesselMachinery */
            $vesselMachinery = VesselMachinery::find($params['vessel_machinery_id']);

            /** @var Interval $interval */
            $interval = Interval::whereName($params['interval'])->first();

            $params['due_date'] = $this->getDueDate($vesselMachinery->getAttribute('installed_date'), $interval);

            /** @var MachinerySubCategory $machinerySubCategory */
            $machinerySubCategory = MachinerySubCategory::find($params['machinery_sub_category_id']);
            if ($params['description']) {
                $description = $machinerySubCategory
                    ->descriptions()
                    ->firstOrCreate([
                        'name' => $params['description'],
                    ]);
            }

            if (isset($description) && $description->getAttribute('id')) {
                $params['machinery_sub_category_description_id'] = $description->getAttribute('id');
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
            /** @var VesselMachinery $vesselMachinery */
            $vesselMachinery = VesselMachinery::find($params['vessel_machinery_id']);

            /** @var Interval $interval */
            $interval = Interval::whereName($params['interval'])->first();

            $params['due_date'] = $this->getDueDate($vesselMachinery->getAttribute('installed_date'), $interval);

            /** @var MachinerySubCategory $machinerySubCategory */
            $machinerySubCategory = MachinerySubCategory::find($params['machinery_sub_category_id']);
            if ($params['description']) {
                $description = $machinerySubCategory
                    ->descriptions()
                    ->firstOrCreate([
                        'name' => $params['description'],
                    ]);
            }

            if (isset($description) && $description->getAttribute('id')) {
                $params['machinery_sub_category_description_id'] = $description->getAttribute('id');
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
