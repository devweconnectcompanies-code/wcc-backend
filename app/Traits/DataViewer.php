<?php

namespace App\Traits;

use App\Libraries\CustomQueryBuilder;

trait DataViewer
{
    public function scopeAdvancedFilter($query)
    {
        $request = request();
        $processedQuery = $this->process($query, $request->all());

        return $request->boolean('no_limit')
            ? $processedQuery->get()
            : $processedQuery->paginate($request->input('limit', 10));
    }

    public function process($query, $data)
    {
        validator($data, [
            'order_column' => 'sometimes|required|in:' . $this->columnList($this->orderable),
            'order_direction' => 'sometimes|required|in:asc,desc',
            'limit' => 'sometimes|required|integer|min:1',
            'filter_match' => 'sometimes|required|in:and,or',
            'f' => 'sometimes|required|array',
            'f.*.column' => 'required|in:' . $this->columnList($this->allowedFilters),
            'f.*.operator' => 'required_with:f.*.column|in:' . $this->allowedOperators(),
            'f.*.query_1' => 'required',
            'f.*.query_2' => 'required_if:f.*.operator,between,not_between'
        ])->validate();
        return (new CustomQueryBuilder)->apply($query, $data);
    }

    protected function columnList(array $columns): string
    {
        return implode(',', $columns);
    }

    protected function allowedOperators(): string
    {
        return implode(',', [
            'equal_to',
            'not_equal_to',
            'less_than',
            'greater_than',
            'between',
            'not_between',
            'contains',
            'starts_with',
            'ends_with',
            'in_the_past',
            'in_the_next',
            'in_the_period',
            'less_than_count',
            'greater_than_count',
            'equal_to_count',
            'not_equal_to_count',
            //  Operadores de fecha
            'date_equals',
            'date_between',
            'date_greater_than',
            'date_less_than',
            'date_greater_than_or_equal',
            'date_less_than_or_equal',
        ]);
    }
}
