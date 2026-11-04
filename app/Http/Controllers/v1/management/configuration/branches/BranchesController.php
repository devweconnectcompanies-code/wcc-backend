<?php

namespace App\Http\Controllers\v1\management\configuration\branches;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\management\configuration\branch\BranchResource;
use App\Services\v1\management\configuration\branches\BranchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\v1\Management\Configuration\BranchesRequest;
use App\Models\Configuration\BranchModel;

class BranchesController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        $query = BranchModel::query()->with(['state', 'municipality', 'district', 'country']);

        if ($request->has('e')) {
            foreach ($request->e as $filter) {
                if (!isset($filter['column'], $filter['data'])) continue;
                $data = json_decode($filter['data'], true);
                if (!is_array($data)) continue;
                match ($filter['column']) {
                    'status' => $query->whereIn('status_id', $data),
                    default => null,
                };
            }
        }
        $query = $query->orderByDesc('status_id')->advancedFilter();

        return response()->json(['collection' => $query]);
    }

    public function store(BranchesRequest $request, BranchService $branchService): JsonResponse
    {
        $branch = $branchService->createBranch($request->toDTO());

        return response()->json([
            'saved' => (bool)$branch,
            'branch' => new BranchResource($branch),
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json(['branch' => BranchModel::query()->findOrFail($request->id)]);
    }

    public function update(BranchesRequest $request, BranchModel $id, BranchService $branchService): JsonResponse
    {
        $branch = $branchService->updateBranch($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$branch,
            'branch' => new BranchResource($branch),
        ]);
    }
}
