<?php

namespace App\Http\Controllers\v1\management\configuration\menu;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Configuration\MenuRequest;
use App\Http\Resources\v1\management\configuration\menu\MenuResource;
use App\Models\Configuration\MenuModel;
use App\Services\v1\management\configuration\menu\MenuService;
use App\Services\v1\management\DataViewerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function data(Request $request, DataViewerService $dataViewerService): JsonResponse
    {
        $query = MenuModel::query();

        return $dataViewerService->handle($request, $query, [
            'status' => fn($q, $data) => $q->whereIn('status_id', $data),
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json([
            'response' => MenuModel::query()
                ->find($request->input('id'))
                ->load('parent.parent')
        ]);
    }

    public function store(MenuRequest $request, MenuService $service): JsonResponse
    {
        $item = $service->create($request->toDTO());

        return response()->json([
            'saved' => (bool)$item,
            'item' => new MenuResource($item)
        ]);
    }

    public function update(MenuRequest $request, MenuModel $id, MenuService $service): JsonResponse
    {
        $item = $service->update($id, $request->toDTO());

        return response()->json([
            'saved' => (bool)$item,
            'item' => new MenuResource($item)
        ]);
    }

    public function getParents(): JsonResponse
    {
        $menuTree = MenuModel::query()
            ->with(['children', 'parent'])
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();
        $parents = $this->extractMenusWithChildren($menuTree);
        $parents[] = ['id' => 0, 'name' => 'Sin Padre'];
        $data = collect($parents)->sortBy('id')->values();

        return response()->json(['response' => $data]);
    }

    protected function extractMenusWithChildren($menu): array
    {
        $result = [];

        foreach ($menu as $menuItem) {
            if ($menuItem->children->isNotEmpty()) {
                $name = ($menuItem->parent->name) ?? null ? $menuItem->parent->name . ' - ' . $menuItem->name : $menuItem->name;
                $result[] = [
                    'id' => $menuItem->id,
                    'name' => $name
                ];
                $result = [...$result, ...$this->extractMenusWithChildren($menuItem->children)];
            }
        }
        return $result;
    }
}
