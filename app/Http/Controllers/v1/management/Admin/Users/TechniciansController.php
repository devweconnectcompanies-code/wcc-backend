<?php

namespace App\Http\Controllers\v1\management\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Admin\Users\TechniciansRequest;
use App\Services\v1\management\DataViewerService;
use App\Services\v1\management\admin\users\TechnicianService;
use App\Http\Resources\v1\management\admin\users\TechniciansResource;
use App\Models\Management\TechnicianModel;

class TechniciansController extends Controller
{
    public function data(Request $request, DataViewerService $dataViewer): JsonResponse
    {
        $query = TechnicianModel::query()->with('user:id,name');

        return $dataViewer->handle($request, $query, [
            'status' => fn($query, $data) => $query->whereIn('status_id', $data)
        ]);
    }

    public function store(TechniciansRequest $request, TechnicianService $service): JsonResponse
    {
        $technician = $service->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$technician,
            'technician' => new TechniciansResource($technician->load('user:id,name'))
        ]);
    }

    public function edit(Request $request, TechnicianService $service): JsonResponse
    {
        $technician = $service->read($request->input('id'));

        return response()->json([
            'technician' => new TechniciansResource($technician)
        ]);
    }

    public function update(TechniciansRequest $request, TechnicianModel $id, TechnicianService $service): JsonResponse
    {
        $technician = $service->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$technician,
            'technician' => new TechniciansResource($technician->load('user:id,name'))
        ]);
    }
}
