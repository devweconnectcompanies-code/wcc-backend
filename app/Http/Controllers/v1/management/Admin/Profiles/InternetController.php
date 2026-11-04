<?php

namespace App\Http\Controllers\v1\management\Admin\Profiles;

use App\Http\Controllers\Controller;
use App\Models\Management\Profiles\InternetModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Admin\Profiles\InternetRequest;
use App\Services\v1\management\admin\profiles\InternetService;
use App\Http\Resources\v1\management\admin\profiles\InternetResource;
use App\Services\v1\management\DataViewerService;

class InternetController extends Controller
{
    public function data(Request $request, DataViewerService $service): JsonResponse
    {
        $query = InternetModel::query();
        return $service->handle($request, $query, [
            'iptv' => fn($q, $data) => $query->whereIn('iptv', $data),
            'ftth' => fn($q, $data) => $query->whereIn('ftth', $data),
            'status' => fn($q, $data) => $query->whereIn('status_id', $data),
        ]);
    }

    public function store(InternetRequest $request, InternetService $service): JsonResponse
    {
        $profile = $service->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$profile,
            'profile' => new InternetResource($profile)
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json([
            'profile' => InternetModel::query()->find($request->input('id'))
        ]);
    }

    public function update(InternetRequest $request, InternetModel $id, InternetService $service): JsonResponse
    {
        $profile = $service->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$profile,
            'profile' => new InternetResource($profile)
        ]);
    }
}
