<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVesselRequest;
use App\Http\Requests\SearchVesselRequest;
use App\Http\Requests\UpdateVesselRequest;
use App\Http\Resources\VesselResource;
use App\Models\Vessel;
use App\Services\VesselService;
use Exception;
use Illuminate\Http\JsonResponse;

class VesselController extends Controller
{
    /** @var VesselService */
    protected $vesselService;

    /**
     * VesselController constructor
     *
     * @param VesselService $vesselService
     */
    public function __construct(VesselService $vesselService)
    {
        parent::__construct();

        $this->vesselService = $vesselService;

        // enable api middleware
        $this->middleware(['auth:api']);
    }

    /**
     * Retrieves the List of vessel
     *
     * @param SearchVesselRequest $request
     * @return JsonResponse
     */
    public function index(SearchVesselRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $conditions = [
                'keyword' => $request->getKeyword(),
                'page' => $request->getPage(),
                'limit' => $request->getLimit(),
            ];
            $results = $this->vesselService->search($conditions);
            $this->response = array_merge($results, $this->response);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Creates a new vessel. Creator must be authenticated.
     *
     * @param CreateVesselRequest $request
     * @return JsonResponse
     */
    public function create(CreateVesselRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'owner' => $request->getOwner(),
                'code_name' => $request->getCodeName(),
                'name' => $request->getName(),
                'former_name' => $request->getFormerName(),
                'flag' => $request->getFlag(),
                'call_sign' => $request->getCallSign(),
                'official_no' => $request->getOfficialNumber(),
                'imo_no' => $request->getImoNo(),
                'loa' => $request->getLoa(),
                'lbp' => $request->getLbp(),
                'light_condition' => $request->getLightCondition(),
                'classification' => $request->getClassification(),
                'character' => $request->getCharacter(),
                'descriptive_note' => $request->getDescriptiveNote(),
                'built_year' => $request->getBuiltYear(),
                'build_yard' => $request->getBuiltYard(),
                'tpc' => $request->getTpc(),
                'breadth' => $request->getBreadth(),
                'depth' => $request->getDepth(),
                'summer_draft' => $request->getSummerDraft(),
                'summer_freeboard' => $request->getSummerFreeboard(),
                'summer_deadweight' => $request->getSummerDeadweight(),
                'winter_draft' => $request->getSWinterDraft(),
                'winter_freeboard' => $request->getWinterFreeboard(),
                'winter_deadweight' => $request->getWinterDeadweight(),
                'tropical_draft' => $request->getTropicalDraft(),
                'tropical_freeboard' => $request->getTropicalFreeboard(),
                'tropical_deadweight' => $request->getTropicalDeadweight(),
                'tropical_fw_draft' => $request->getTropicalFwDraft(),
                'tropical_fw_freeboard' => $request->getTropicalFwFreeboard(),
                'tropical_fw_deadweight' => $request->getTropicalFwDeadweight(),
                'fw_draft' => $request->getFwDraft(),
                'fw_freeboard' => $request->getFwFreeboard(),
                'fw_deadweight' => $request->getFwDeadweight(),
                'fw_allowance' => $request->getFwAllowance(),
                'light_shift_drafts_f' => $request->getLightShiftDraftsF(),
                'light_shift_drafts_a' => $request->getLightShiftDraftsA(),
                'heavy_ballast_drafts_f' => $request->getHeavyBallastDraftsF(),
                'heavy_ballast_drafts_a' => $request->getHeavyBallastDraftsA(),
                'normal_ballast_drafts_f' => $request->getNormalBallastDraftsF(),
                'normal_ballast_drafts_a' => $request->getNormalBallastDraftsA(),
                'international_gt' => $request->getInternationalGt(),
                'international_nt' => $request->getInternationalNt(),
                'panama_gt' => $request->getPanamaGt(),
                'panama_nt' => $request->getPanamaNt(),
                'suez_gt' => $request->getSuezGt(),
                'suez_nt' => $request->getSuezNt(),
            ];
            $vessel = $this->vesselService->create($formData);
            $this->response['data'] = new VesselResource($vessel);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Retrieves vessel information
     *
     * @param Vessel $vessel
     * @return JsonResponse
     */
    public function read(Vessel $vessel): JsonResponse
    {
        try {
            $this->response['data'] = new VesselResource($vessel);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Updates vessel information
     *
     * @param UpdateVesselRequest $request
     * @param Vessel $vessel
     * @return JsonResponse
     */
    public function update(UpdateVesselRequest $request, Vessel $vessel): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'owner' => $request->getOwner(),
                'code_name' => $request->getCodeName(),
                'name' => $request->getName(),
                'former_name' => $request->getFormerName(),
                'flag' => $request->getFlag(),
                'call_sign' => $request->getCallSign(),
                'official_no' => $request->getOfficialNumber(),
                'imo_no' => $request->getImoNo(),
                'loa' => $request->getLoa(),
                'lbp' => $request->getLbp(),
                'light_condition' => $request->getLightCondition(),
                'classification' => $request->getClassification(),
                'character' => $request->getCharacter(),
                'descriptive_note' => $request->getDescriptiveNote(),
                'built_year' => $request->getBuiltYear(),
                'build_yard' => $request->getBuiltYard(),
                'tpc' => $request->getTpc(),
                'breadth' => $request->getBreadth(),
                'depth' => $request->getDepth(),
                'summer_draft' => $request->getSummerDraft(),
                'summer_freeboard' => $request->getSummerFreeboard(),
                'summer_deadweight' => $request->getSummerDeadweight(),
                'winter_draft' => $request->getSWinterDraft(),
                'winter_freeboard' => $request->getWinterFreeboard(),
                'winter_deadweight' => $request->getWinterDeadweight(),
                'tropical_draft' => $request->getTropicalDraft(),
                'tropical_freeboard' => $request->getTropicalFreeboard(),
                'tropical_deadweight' => $request->getTropicalDeadweight(),
                'tropical_fw_draft' => $request->getTropicalFwDraft(),
                'tropical_fw_freeboard' => $request->getTropicalFwFreeboard(),
                'tropical_fw_deadweight' => $request->getTropicalFwDeadweight(),
                'fw_draft' => $request->getFwDraft(),
                'fw_freeboard' => $request->getFwFreeboard(),
                'fw_deadweight' => $request->getFwDeadweight(),
                'fw_allowance' => $request->getFwAllowance(),
                'light_shift_drafts_f' => $request->getLightShiftDraftsF(),
                'light_shift_drafts_a' => $request->getLightShiftDraftsA(),
                'heavy_ballast_drafts_f' => $request->getHeavyBallastDraftsF(),
                'heavy_ballast_drafts_a' => $request->getHeavyBallastDraftsA(),
                'normal_ballast_drafts_f' => $request->getNormalBallastDraftsF(),
                'normal_ballast_drafts_a' => $request->getNormalBallastDraftsA(),
                'international_gt' => $request->getInternationalGt(),
                'international_nt' => $request->getInternationalNt(),
                'panama_gt' => $request->getPanamaGt(),
                'panama_nt' => $request->getPanamaNt(),
                'suez_gt' => $request->getSuezGt(),
                'suez_nt' => $request->getSuezNt(),
                
            ];
            $vessel = $this->vesselService->update($formData, $vessel);
            $this->response['data'] = new VesselResource($vessel);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Delete vessel
     *
     * @param Vessel $vessel
     * @return JsonResponse
     */
    public function delete(Vessel $vessel): JsonResponse
    {
        try {
            $this->response['deleted'] = $this->vesselService->delete($vessel);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }
}
