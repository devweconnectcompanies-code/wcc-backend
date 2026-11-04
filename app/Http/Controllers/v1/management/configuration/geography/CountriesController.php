<?php

namespace App\Http\Controllers\v1\management\configuration\geography;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Management\Configuration\CountriesRequest;
use App\Models\Configuration\Geography\CountryModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        $query = CountryModel::query();
        $statusFilter = collect($request->e ?? [])->firstWhere('column', 'status');
        if ($statusFilter) {
            $query->whereIn('status_id', json_decode($statusFilter['data']));
        }
        $query = $query->orderBy('status_id', 'desc')->advancedFilter();

        return response()->json(['collection' => $query]);
    }

    public function create(CountriesRequest $request): JsonResponse
    {
        $country = CountryModel::create([
            'es_name' => $request->esName,
            'en_name' => $request->enName,
            'iso_2' => $request->iso2,
            'iso_3' => $request->iso3,
            'phone_prefix' => $request->prefix,
            'status_id' => $request->status
        ]);

        return response()->json(['saved' => (bool)$country, 'country' => $country]);
    }

    public function edit(Request $request): JsonResponse
    {
        return response()->json(['country' => CountryModel::query()->find($request->id ?? 0)]);
    }

    public function update(CountriesRequest $request, $id): JsonResponse
    {
        $country = CountryModel::query()->findOrFail($id);
        $country->update([
            'es_name' => $request->esName,
            'en_name' => $request->enName,
            'iso_2' => $request->iso2,
            'iso_3' => $request->iso3,
            'phone_prefix' => $request->prefix,
            'status_id' => $request->status
        ]);

        return response()->json(['saved' => (bool)$country, 'country' => $country]);
    }

}
