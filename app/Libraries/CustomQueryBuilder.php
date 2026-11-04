<?php

namespace App\Libraries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CustomQueryBuilder
{
    public function apply(Builder $query, array $data): Builder
    {
        if (!isset($data['f'])) {
            return $query;
        }

        $filterMatch = $data['filter_match'] ?? 'and';

        if ($filterMatch === 'or') {
            $query->where(function ($subQuery) use ($data) {
                foreach ($data['f'] as $index => $field) {
                    if ($index === 0) {
                        $this->applyFilter($subQuery, $field, 'and');
                    } else {
                        $this->applyFilter($subQuery, $field, 'or');
                    }
                }
            });
        } else {
            foreach ($data['f'] as $field) {
                $this->applyFilter($query, $field, 'and');
            }
        }

//        foreach ($data['f'] as $field) {
//            $field['match'] = $field['match'] ?? 'and';
//            $this->applyFilter($query, $field);
//        }
        return $query;
    }

    public function applyFilter(Builder $query, array $filter, string $boolean = 'and'): void
    {
        if (str_contains($filter['column'], '.')) {
            $segments = explode('.', $filter['column']);
            $filter['column'] = array_pop($segments);
            $relation = implode('.', $segments);

            if ($filter['column'] === 'count') {
                $this->callOperatorMethod($query, $filter, $relation, $boolean);
            } else {
                $method = $boolean === 'and' ? 'whereHas' : 'orWhereHas';
                $query->$method($relation, function ($query) use ($filter) {
                    $this->callOperatorMethod($query, $filter, null, 'and');
                });
//                $query->whereHas($relation, function ($query) use ($filter) {
//                    $this->callOperatorMethod($query, $filter);
//                });
            }
        } else {
            $this->callOperatorMethod($query, $filter, null, $boolean);
        }
    }

    public function callOperatorMethod(Builder $query, array $filter, ?string $relation = null, string $boolean = 'and'): void
    {
        $method = Str::camel($filter['operator']);
        if (method_exists($this, $method)) {
            if ($relation) {
                $this->$method($filter, $query, $relation, $boolean);
            } else {
                $this->$method($filter, $query, $boolean);
            }
//            $relation ? $this->$method($filter, $query, $relation) : $this->$method($filter, $query);
        }
    }

    public function equalTo(array $f, Builder $q, string $boolean = 'and'): Builder
    {
        if (in_array($f['column'], $this->getDateColumns())) {
            try {
                return $q->whereDate($f['column'], '=', Carbon::parse($f['query_1'])->format('Y-m-d'), $boolean);
            } catch (\Exception $e) {

            }
        }
        return $q->where($f['column'], '=', $f['query_1'], $boolean);
    }

    public function notEqualTo(array $f, Builder $q, string $boolean = 'and'): Builder
    {
        if (in_array($f['column'], $this->getDateColumns())) {
            try {
                return $q->whereDate($f['column'], '!=', Carbon::parse($f['query_1'])->format('Y-m-d'), $boolean);
            } catch (\Exception $e) {
                // Fall back to regular comparison if date parsing fails
            }
        }
        return $q->where($f['column'], '!=', $f['query_1'], $boolean);
    }

    public function lessThan(array $f, Builder $q, string $boolean = 'and'): Builder
    {
        if (in_array($f['column'], $this->getDateColumns())) {
            try {
                $date = Carbon::parse($f['query_1'])->startOfDay();
                return $q->where($f['column'], '<', $date, $boolean);
            } catch (\Exception $e) {
                // Fall back to regular comparison if date parsing fails
            }
        }

        return $q->where($f['column'], '<', $f['query_1'], $boolean);
    }

    public function greaterThan(array $f, Builder $q, string $boolean = 'and'): Builder
    {
        if (in_array($f['column'], $this->getDateColumns())) {
            try {
                $date = Carbon::parse($f['query_1'])->endOfDay();
                return $q->where($f['column'], '>', $date, $boolean);
            } catch (\Exception $e) {
                // Fall back to regular comparison if date parsing fails
            }
        }
        return $q->where($f['column'], '>', $f['query_1'], $boolean);
    }

    public function between(array $f, Builder $q, string $boolean = 'and'): Builder
    {
        //  Verificando si es una columna tipo fecha
        if (in_array($f['column'], $this->getDateColumns())) {
            try {
                $startDate = Carbon::parse($f['query_1'])->startOfDay();
                $endDate = Carbon::parse($f['query_2'])->endOfDay();

                return $q->whereBetween($f['column'], [$startDate, $endDate], $boolean);
            } catch (\Exception $e) {
                return $q->whereBetween($f['column'], [$f['query_1'], $f['query_2']], $boolean);
            }
        }
        return $q->whereBetween($f['column'], [$f['query_1'], $f['query_2']], $boolean);
    }

    public function notBetween(array $f, Builder $q, string $boolean = 'and'): Builder
    {
        return $q->whereNotBetween($f['column'], [$f['query_1'], $f['query_2']], $boolean);
    }

    public function contains(array $f, Builder $q, string $boolean = 'and'): Builder
    {
        return $q->where($f['column'], 'ILIKE', '%' . $f['query_1'] . '%', $boolean);
    }

    public function startsWith(array $f, Builder $q, string $boolean = 'and'): Builder
    {
        return $q->where($f['column'], 'ILIKE', $f['query_1'] . '%', $boolean);
    }

    public function endsWith(array $f, Builder $q, string $boolean = 'and'): Builder
    {
        return $q->where($f['column'], 'ILIKE', '%' . $f['query_1'], $boolean);
    }

    public function inThePast(array $f, Builder $q, string $boolean = 'and'): Builder
    {
        $end = Carbon::now();
        $begin = match ($f['query_2']) {
            'hours' => Carbon::now()->subHours($f['query_1']),
            'days' => Carbon::now()->subDays($f['query_1'])->startOfDay(),
            'months' => Carbon::now()->subMonths($f['query_1'])->startOfDay(),
            'years' => Carbon::now()->subYears($f['query_1'])->startOfDay(),
            default => Carbon::now()->subDays($f['query_1'])->startOfDay(),
        };

        return $q->whereBetween($f['column'], [$begin, $end], $boolean);
    }

    public function inTheNext(array $f, Builder $q, string $boolean = 'and'): Builder
    {
        $begin = Carbon::now();
        $end = match ($f['query_2']) {
            'hours' => Carbon::now()->addHours($f['query_1']),
            'days' => Carbon::now()->addDays($f['query_1'])->endOfDay(),
            'months' => Carbon::now()->addMonths($f['query_1'])->endOfDay(),
            'years' => Carbon::now()->addYears($f['query_1'])->endOfDay(),
            default => Carbon::now()->addDays($f['query_1'])->endOfDay(),
        };
        return $q->whereBetween($f['column'], [$begin, $end], $boolean);
    }

    public function inThePeriod(array $f, Builder $q, string $boolean = 'and'): Builder
    {
        [$begin, $end] = match ($f['query_1']) { // ✅ Cambiado de query_2 a query_1
            'today' => [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()],
            'yesterday' => [Carbon::now()->subDay()->startOfDay(), Carbon::now()->subDay()->endOfDay()],
            'tomorrow' => [Carbon::now()->addDay()->startOfDay(), Carbon::now()->addDay()->endOfDay()],
            'last_month' => [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()],
            'this_month' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            'next_month' => [Carbon::now()->addMonth()->startOfMonth(), Carbon::now()->addMonth()->endOfMonth()],
            'last_year' => [Carbon::now()->subYear()->startOfYear(), Carbon::now()->subYear()->endOfYear()],
            'this_year' => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
            'next_year' => [Carbon::now()->addYear()->startOfYear(), Carbon::now()->addYear()->endOfYear()],
            default => [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]
        };
        return $q->whereBetween($f['column'], [$begin, $end], $boolean);
    }

    public function equalToCount(array $f, Builder $q, string $relation, string $boolean = 'and'): Builder
    {
        $method = $boolean === 'and' ? 'has' : 'orHas';
        return $q->$method($relation, '=', $f['query_1']);
    }

    public function notEqualToCount(array $f, Builder $q, string $relation, string $boolean = 'and'): Builder
    {
        $method = $boolean === 'and' ? 'has' : 'orHas';
        return $q->$method($relation, '!=', $f['query_1']);
    }

    public function lessThanCount(array $f, Builder $q, string $relation, string $boolean = 'and'): Builder
    {
        $method = $boolean === 'and' ? 'has' : 'orHas';
        return $q->$method($relation, '<', $f['query_1']);
    }

    public function greaterThanCount(array $f, Builder $q, string $relation, string $boolean = 'and'): Builder
    {
        $method = $boolean === 'and' ? 'has' : 'orHas';
        return $q->$method($relation, '>', $f['query_1']);
    }

    private function getDateColumns(): array
    {
        return [
            'closed_at',
            'creation_date',
            'created_at',
            'updated_at',
            'due_date',
            'generation_datetime'
        ];
    }
}
