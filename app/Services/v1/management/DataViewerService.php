<?php

namespace App\Services\v1\management;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class DataViewerService
{
    /***
     * @param Request $request
     * @param Builder $query
     * @param array $filterColumns
     * @param string|null $defaultOrderColumn
     * @param string|null $defaultOrderDirection
     * @return JsonResponse
     */
    public function handle(
        Request $request,
        Builder $query,
        array   $filterColumns = [],
        ?string $defaultOrderColumn = 'status_id',
        ?string $defaultOrderDirection = 'desc'
    ): JsonResponse
    {
        if ($request->has('e')) {
            foreach ($request->e as $filter) {
                if (!isset($filter['column'], $filter['data'])) continue;

                $data = json_decode($filter['data'], true);

                if (!is_array($data) || empty($data)) continue;

                if (isset($filterColumns[$filter['column']])) {
                    $filterColumns[$filter['column']] ($query, $data);
                }
            }
        }

        $orderColumn = $request->input('order_column', $defaultOrderColumn);
        $orderDirection = $request->input('order_direction', $defaultOrderDirection);
        $orderDirection = in_array(strtolower($orderDirection), ['asc', 'desc']) ? strtolower($orderDirection) : 'desc';
        $query->orderBy($orderColumn, $orderDirection);
        $result = $query->advancedFilter();

        return response()->json(['collection' => $result]);
    }

}
