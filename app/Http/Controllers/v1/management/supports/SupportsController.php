<?php

namespace App\Http\Controllers\v1\management\supports;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Supports\SupportRequest;
use App\Http\Requests\v1\Management\Supports\UpdateSupportRequest;
use App\Http\Resources\v1\management\supports\SupportResource;
use App\Models\Supports\SupportModel;
use App\Services\v1\management\DataViewerService;
use App\Services\v1\management\supports\SupportService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SupportsController extends Controller
{
    public function data(Request $request, DataViewerService $dataViewer): JsonResponse
    {
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
                'survey'
            ]);

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

    public function store(SupportRequest $request, SupportService $service): JsonResponse
    {
        $support = $service->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$support,
            'support' => new SupportResource($support),
        ]);
    }

    public function read(Request $request, SupportService $service): JsonResponse
    {
        return response()->json([
            'support' => new SupportResource($service->read($request->input('id'))),
        ]);
    }

    public function update(SupportRequest $request, SupportModel $id, SupportService $service): JsonResponse
    {
        $support = $service->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$support,
            'support' => new SupportResource($support),
        ]);
    }

    public function print(int $id, SupportService $service): Response
    {
        $support = SupportModel::query()
            ->with([
                'branch.state:id,name',
                'branch.municipality:id,name',
                'branch.district:id,name',
                'client.dui',
                'client.nit',
                'client.passport',
                'client.residence',
                'client.mobile',
                'contract',
                'state',
                'municipality',
                'district',
                'details',
                'type',
            ])
            ->findOrFail($id);
        $pdfBinary = $service->printTicket($support);

        return response($pdfBinary, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="ticket.pdf"');
    }

    public function logs(Request $request, SupportService $service): JsonResponse
    {
        $log = $service->getLogs($request->input('id'));

        return response()->json([
            'logs' => new SupportResource($log),
        ]);
    }
}
