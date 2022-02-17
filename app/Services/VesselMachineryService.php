<?php

namespace App\Services;

use App\Exceptions\VesselMachineryNotFoundException;
use App\Http\Resources\VesselMachineryResource;
use App\Models\VesselMachinery;
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
}
