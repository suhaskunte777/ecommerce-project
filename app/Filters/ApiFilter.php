<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter implements ApiFilterInterface
{
    protected $allowedFilters = [];
    protected $columnMap = [];
    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];
    protected $allowedSortBy = [];
    protected $allowedSortByOrders = ['ASC', 'DESC'];

    public function transform(Request $request)
    {
        $whereItems = [];
        $whereInItems = [];

        foreach ($this->allowedFilters as $filter => $operators) {
            $query = $request->query($filter);

            if(!isset($query)) {
                continue;
            }

            $column = $this->columnMap[$filter] ?? $filter;

            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $value = $query[$operator];
                    $operatorMap = $this->operatorMap[$operator];

                    if (strpos($query[$operator], ',') === false) {
                        $whereItems[] = [$column, $operatorMap, $value];
                    } else {
                        $whereInItems[] = [$column, $operatorMap, explode(',', $value)];
                    }
                }
            }
        }

        return [$whereItems, $whereInItems];
    }

    public function sortBy(Request $request)
    {
        $sortingQuery = $request->query('sortBy');

        if (is_null($sortingQuery)) {
            return ['id', 'ASC'];
        }

        $indexedArray = [...array_keys($sortingQuery), ...array_values($sortingQuery)];

        $sortOrder = $indexedArray[0] ?? 'ASC';
        $sortBy = $indexedArray[1] ?? 'id';

        if (!in_array($sortOrder, $this->allowedSortByOrders)) {
            $sortOrder = 'ASC';
        }

        if (!in_array($sortBy, $this->allowedSortBy)) {
            $sortBy = 'id';
        }

        return [$sortBy, $sortOrder];
    }
}
