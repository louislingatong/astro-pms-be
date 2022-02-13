<?php

namespace App\Services;

use App\Exceptions\VesselMachineryNotFoundException;
use App\Http\Resources\VesselMachineryResource;
use App\Models\Interval;
use App\Models\IntervalUnit;
use App\Models\VesselMachinery;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class VesselMachineryService
{
    /** @var VesselMachinery $vesselMachinery */
    protected $vesselMachinery;

    /**
     * VesselMachineryService constructor.
     *
     * @param VesselMachinery $vesselMachinery
     */
    public function __construct(VesselMachinery $vesselMachinery)
    {
        $this->vesselMachinery = $vesselMachinery;
    }

    /**
     * List of vessel machinery by conditions
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

        $query = $this->vesselMachinery;

        if ($conditions['keyword']) {
            $query = $query->search($conditions['keyword']);
        }

        $results = $query->skip($skip)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        $urlParams = ['keyword' => $conditions['keyword'], 'limit' => $limit];

        return paginated($results, VesselMachineryResource::class, $page, $urlParams);
    }

    /**
     * Creates a new vessel machinery in the database
     *
     * @param array $params
     * @return VesselMachinery
     * @throws
     */
    public function create(array $params): VesselMachinery
    {
        DB::beginTransaction();

        try {
            $installedDate = $params['installed_date'];
            $interval = Interval::find($params['interval_id']);
            $params['due_date'] = $this->getDueDate($installedDate, $interval);

            $vessel = $this->vesselMachinery->create($params);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }

        return $vessel;
    }

    /**
     * Updates vessel machinery in the database
     *
     * @param array $params
     * @param VesselMachinery $vesselMachinery
     * @return VesselMachinery
     * @throws
     */
    public function update(array $params, VesselMachinery $vesselMachinery): VesselMachinery
    {
        $installedDate = $params['installed_date'];
        $interval = Interval::find($params['interval_id']);
        $params['due_date'] = $this->getDueDate($installedDate, $interval);

        $vesselMachinery->update($params);
        return $vesselMachinery;
    }

    /**
     * Deletes the vessel machinery in the database
     *
     * @param VesselMachinery $vesselMachinery
     * @return bool
     * @throws
     */
    public function delete(VesselMachinery $vesselMachinery): bool
    {
        if (!($vesselMachinery instanceof VesselMachinery)) {
            throw new VesselMachineryNotFoundException();
        }
        $vesselMachinery->delete();
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
            case config('interval.units.daily'):
                $dueDate->addDay();
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
