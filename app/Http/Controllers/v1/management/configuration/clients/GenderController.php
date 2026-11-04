<?php

namespace App\Http\Controllers\v1\management\configuration\clients;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Configuration\GenderRequest;
use App\Http\Resources\v1\management\configuration\clients\GenderResource;
use App\Services\v1\management\configuration\clients\GenderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Configuration\Clients\GenderModel;
use App\Services\v1\management\DataViewerService;

class GenderController extends Controller
{
    public function dataViewer(Request $request, DataViewerService $dataViewerService): JsonResponse
    {
        $query = GenderModel::query();

        return $dataViewerService->handle($request, $query, [
            'status' => fn($q, $data) => $query->wherein('status_id', $data),
        ]);
    }

    public function store(GenderRequest $request, GenderService $genderService): JsonResponse
    {
        $gender = $genderService->createGender($request->toDTO());

        return response()->json([
            'saved' => (bool)$gender,
            'gender' => new GenderResource($gender)
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json(['gender' => GenderModel::query()->findOrFail($request->id)]);
    }

    public function update(GenderRequest $request, GenderModel $id, GenderService $genderService): JsonResponse
    {
        $updated = $genderService->updateGender($id, $request->toDTO());
        return response()->json([
            'saved' => (bool)$updated,
            'gender' => new GenderResource($updated)
        ]);
    }
}
