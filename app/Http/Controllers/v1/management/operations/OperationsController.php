<?php

namespace App\Http\Controllers\v1\management\operations;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Operations\OperationRequest;
use App\Http\Resources\v1\management\supports\SupportResource;
use App\Models\Management\TechnicianModel;
use App\Models\Supports\SupportModel;
use App\Services\v1\management\operations\OperationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\v1\management\DataViewerService;
use Illuminate\Support\Facades\Auth;

class OperationsController extends Controller
{
    /***
     * @param Request $request
     * @param DataViewerService $dataViewer
     * @return JsonResponse
     */
    public function data(Request $request, DataViewerService $dataViewer): JsonResponse
    {
        $user = Auth::user();
        $roleID = $user->roles->pluck('id')->first();
        $technician = TechnicianModel::query()
            ->where('user_id', $user->id)
            ->first();

        $query = SupportModel::query()
            ->with([
                'type:id,name,badge_color',
                'client:id,name,surname',
                'client.client_type:id,name',
                'branch:id,name',
                'technician.user:id,name',
                'state:id,name',
                'municipality:id,name',
                'district:id,name',
                'user:id,name',
                'status:id,name,badge_color',
                'details',
            ]);

        if ($roleID === 4) {
            $query->where('technician_id', $technician->id);
        }

        return $dataViewer->handle($request, $query, [
            'status' => fn($q, $data) => $q->whereIn('status_id', $data),
            'branch' => fn($q, $data) => $q->whereIn('branch_id', $data),
            'type' => fn($q, $data) => $q->whereIn('type_id', $data),
            'state' => fn($q, $data) => $q->whereIn('state_id', $data),
            'municipality' => fn($q, $data) => $q->whereIn('municipality_id', $data),
            'district' => fn($q, $data) => $q->whereIn('district_id', $data),
            'user' => fn($q, $data) => $q->whereIn('user_id', $data),
            'technician' => fn($q, $data) => $q->whereIn('technician_id', $data),
        ], 'id', 'desc');
    }

    /***
     * @param Request $request
     * @param OperationService $service
     * @return JsonResponse
     */
    public function edit(Request $request, OperationService $service): JsonResponse
    {
        $support = $service->getSupportData($request->input('id'));

        return response()->json([
            'support' => new SupportResource($support),
        ]);
    }

    /***
     * @param OperationRequest $request
     * @param SupportModel $id
     * @param OperationService $service
     * @return JsonResponse
     */
    public function processSupport(OperationRequest $request, SupportModel $id, OperationService $service): JsonResponse
    {
        $transaction = $service->process($id, $request->toArray());

        return response()->json([
            'saved' => (bool)$transaction,
            'support' => new SupportResource($transaction),
        ]);
    }
}
