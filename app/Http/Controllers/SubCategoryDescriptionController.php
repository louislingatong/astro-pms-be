<?php

namespace App\Http\Controllers;

use App\Services\IntervalService;

class SubCategoryDescriptionController extends Controller
{
    /** @var IntervalService */
    protected $intervalService;

    /**
     * IntervalController constructor
     *
     * @param IntervalService $intervalService
     */
    public function __construct(IntervalService $intervalService)
    {
        parent::__construct();

        $this->intervalService = $intervalService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

//    /**
//     * Retrieves the List of intervals
//     *
//     * @param SearchIntervalRequest $request
//     * @return JsonResponse
//     */
//    public function index(SearchIntervalRequest $request): JsonResponse
//    {
//        $request->validated();
//
//        try {
//            $conditions = [
//                'keyword' => $request->getKeyword(),
//                'page' => $request->getPage(),
//                'limit' => $request->getLimit(),
//            ];
//            $results = $this->intervalService->search($conditions);
//            $this->response = array_merge($results, $this->response);
//        } catch (Exception $e) {
//            $this->response = [
//                'error' => $e->getMessage(),
//                'code' => 500,
//            ];
//        }
//
//        return response()->json($this->response, $this->response['code']);
//    }
//
//    /**
//     * Creates a new interval. Creator must be authenticated.
//     *
//     * @param CreateIntervalRequest $request
//     * @return JsonResponse
//     */
//    public function create(CreateIntervalRequest $request): JsonResponse
//    {
//        $request->validated();
//
//        try {
//            $formData = [
//                'interval_unit_id' => $request->getUnitId(),
//                'value' => $request->getValue(),
//            ];
//            $vessel = $this->intervalService->create($formData);
//            $this->response['data'] = new IntervalResource($vessel);
//        } catch (Exception $e) {
//            $this->response = [
//                'error' => $e->getMessage(),
//                'code' => 500,
//            ];
//        }
//
//        return response()->json($this->response, $this->response['code']);
//    }
//
//    /**
//     * Retrieves interval information
//     *
//     * @param Interval $interval
//     * @return JsonResponse
//     */
//    public function read(Interval $interval): JsonResponse
//    {
//        try {
//            $this->response['data'] = new IntervalResource($interval);
//        } catch (Exception $e) {
//            $this->response = [
//                'error' => $e->getMessage(),
//                'code' => 500,
//            ];
//        }
//
//        return response()->json($this->response, $this->response['code']);
//    }
//
//    /**
//     * Updates interval information
//     *
//     * @param UpdateIntervalRequest $request
//     * @param Interval $interval
//     * @return JsonResponse
//     */
//    public function update(UpdateIntervalRequest $request, Interval $interval): JsonResponse
//    {
//        $request->validated();
//
//        try {
//            $formData = [
//                'interval_unit_id' => $request->getUnitId(),
//                'value' => $request->getValue(),
//            ];
//
//            $user = $this->intervalService->update($formData, $interval);
//            $this->response['data'] = new IntervalResource($user);
//        } catch (Exception $e) {
//            $this->response = [
//                'error' => $e->getMessage(),
//                'code' => 500,
//            ];
//        }
//
//        return response()->json($this->response, $this->response['code']);
//    }
//
//    /**
//     * Delete interval
//     *
//     * @param Interval $interval
//     * @return JsonResponse
//     */
//    public function delete(Interval $interval): JsonResponse
//    {
//        try {
//            $this->response['deleted'] = $this->intervalService->delete($interval);
//        } catch (Exception $e) {
//            $this->response = [
//                'error' => $e->getMessage(),
//                'code' => 500,
//            ];
//        }
//
//        return response()->json($this->response, $this->response['code']);
//    }
}
