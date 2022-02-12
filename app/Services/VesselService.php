<?php

namespace App\Services;

use App\Exceptions\VesselNotFoundException;
use App\Http\Resources\VesselResource;
use App\Models\Vessel;
use Exception;
use Illuminate\Support\Facades\DB;

class VesselService
{
    /** @var Vessel $vessel */
    protected $vessel;

    /**
     * VesselService constructor.
     *
     * @param Vessel $vessel
     */
    public function __construct(Vessel $vessel)
    {
        $this->vessel = $vessel;
    }

    /**
     * List of vessel by conditions
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

        $query = $this->vessel;

        if ($conditions['keyword']) {
            $query = $query->search($conditions['keyword']);
        }

        $results = $query->skip($skip)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        $urlParams = ['keyword' => $conditions['keyword'], 'limit' => $limit];

        return paginated($results, VesselResource::class, $page, $urlParams);
    }

    /**
     * Creates a new vessel in the database
     *
     * @param array $params
     * @return Vessel
     * @throws
     */
    public function create(array $params): Vessel
    {
        DB::beginTransaction();

        try {
            $vessel = $this->vessel->create($params);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }

        return $vessel;
    }

    /**
     * Updates vessel in the database
     *
     * @param array $params
     * @param Vessel $vessel
     * @return Vessel
     * @throws
     */
    public function update(array $params, Vessel $vessel): Vessel
    {
        $vessel->update($params);
        return $vessel;
    }

    /**
     * Deletes the vessel in the database
     *
     * @param Vessel $vessel
     * @return bool
     * @throws
     */
    public function delete(Vessel $vessel): bool
    {
        if (!($vessel instanceof Vessel)) {
            throw new VesselNotFoundException();
        }
        $vessel->delete();
        return true;
    }
}
