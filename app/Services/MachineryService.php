<?php

namespace App\Services;

use App\Exceptions\MachineryNotFoundException;
use App\Http\Resources\MachineryResource;
use App\Models\Machinery;
use App\Models\MachineryMaker;
use App\Models\MachineryModel;
use Exception;
use Illuminate\Support\Facades\DB;

class MachineryService
{
    /** @var Machinery $machinery */
    protected $machinery;

    /** @var MachineryModel $model */
    protected $model;

    /** @var MachineryMaker $maker */
    protected $maker;

    /**
     * MachineryService constructor.
     *
     * @param Machinery $machinery
     * @param MachineryModel $model
     * @param MachineryMaker $maker
     */
    public function __construct(
        Machinery $machinery,
        MachineryModel $model,
        MachineryMaker $maker
    )
    {
        $this->machinery = $machinery;
        $this->model = $model;
        $this->maker = $maker;
    }

    /**
     * List of machinery by conditions
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

        $query = $this->machinery;

        if ($conditions['keyword']) {
            $query = $query->search($conditions['keyword']);
        }

        $results = $query->skip($skip)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        $urlParams = ['keyword' => $conditions['keyword'], 'limit' => $limit];

        return paginated($results, MachineryResource::class, $page, $urlParams);
    }

    /**
     * Creates a new machinery in the database
     *
     * @param array $params
     * @param mixed $model
     * @param mixed $maker
     * @return Machinery
     * @throws
     */
    public function create(array $params, $model, $maker): Machinery
    {
        DB::beginTransaction();

        try {
            if ($model) {
                $machineryModel = $this->findOrCreateModelByName($model);
                $params['machinery_model_id'] = $machineryModel->getAttribute('id');
            }
            if ($maker) {
                $machineryMaker = $this->findOrCreateMakerByName($maker);
                $params['machinery_maker_id'] = $machineryMaker->getAttribute('id');
            }
            $machinery = $this->machinery->create($params);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }

        return $machinery;
    }

    /**
     * Updates machinery in the database
     *
     * @param array $params
     * @param Machinery $machinery
     * @param mixed $model
     * @param mixed $maker
     * @return Machinery
     * @throws
     */
    public function update(array $params, Machinery $machinery, $model, $maker): Machinery
    {
        DB::beginTransaction();

        try {
            if ($model) {
                $machineryModel = $this->findOrCreateModelByName($model);
                $params['machinery_model_id'] = $machineryModel->getAttribute('id');
            }
            if ($maker) {
                $machineryMaker = $this->findOrCreateMakerByName($maker);
                $params['machinery_maker_id'] = $machineryMaker->getAttribute('id');
            }
            $machinery->update($params);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }

        return $machinery;
    }

    /**
     * Deletes the machinery in the database
     *
     * @param Machinery $machinery
     * @return bool
     * @throws
     */
    public function delete(Machinery $machinery): bool
    {
        if (!($machinery instanceof Machinery)) {
            throw new MachineryNotFoundException();
        }
        $machinery->delete();
        return true;
    }

    /**
     * Retrieve/Create the machinery model
     *
     * @param string $name
     * @return MachineryModel
     * @throws
     */
    public function findOrCreateModelByName(string $name): MachineryModel
    {
        return $this->model->firstOrCreate(['name' => $name]);
    }

    /**
     * Retrieve/Create the machinery maker
     *
     * @param string $name
     * @return MachineryMaker
     * @throws
     */
    public function findOrCreateMakerByName(string $name): MachineryMaker
    {
        return $this->maker->firstOrCreate(['name' => $name]);
    }
}
