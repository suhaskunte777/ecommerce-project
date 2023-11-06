<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;
use App\Models\Category;

class ProductFilter extends ApiFilter
{
    protected $allowedFilters = [
        'brand' => ['eq'],
        'price' => ['eq', 'gt', 'lt', 'gte', 'lte'],
        'ram' => ['eq', 'gt', 'lt', 'gte', 'lte'],
        'availability' => ['eq'],
        'category' => ['eq']
    ];

    protected $columnMap = [
        'category' => 'category_id',
    ];

    protected $allowedSortBy = [
        'price',
        'availability',
        'category',
        'brand',
    ];

    public function getCategoryIds($whereFilters, $whereInFilters) {
        $whereFilters = array_map(function ($filter) {
            if ($filter[0] === "category_id") {
                $category = Category::select('id')->where('name', $filter[2])->first();
                $filter[2] = $category->id;
            }
            return $filter;
        }, $whereFilters);


        $whereInFilters = array_map(function ($filter) {
            if ($filter[0] === "category_id") {
                $category = Category::select('id')->whereIn('name', $filter[2])->get()->toArray();
                $category_ids = array_map(function ($item) {
                    return $item['id'];
                }, $category);
                $filter[2] = $category_ids;
            }
            return $filter;
        }, $whereInFilters);

        return [$whereFilters, $whereInFilters];
    }

}
