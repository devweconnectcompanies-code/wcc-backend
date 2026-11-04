<?php

namespace App\Http\Controllers\v1\management\configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Configuration\MenuModel;
use Illuminate\Support\Facades\Auth;

class ConfigurationController extends Controller
{
    public function getMenu(): JsonResponse
    {
        $user = Auth::user();
        $permissions = $user->getAllPermissions()->pluck('menu_id');
        $menu = MenuModel::query()
            ->whereNull('parent_id')
            ->whereIn('id', $permissions)
            ->where('status_id', 1)
            ->orderBy('order')
            ->with(['children' => function ($q) use ($permissions) {
                $q->whereIn('id', $permissions)
                    ->orderBy('order')
                    ->with(['children' => fn($q) => $q->whereIn('id', $permissions)->orderBy('order')]);
            }])
            ->get();

        return response()->json([
            'data' => $menu,
        ]);
    }
}
