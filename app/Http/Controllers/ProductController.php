<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Filters\V1\ProductFilter;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\V1\ProductCollection;
use App\Http\Resources\V1\ProductResource;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Prepared filters
        $filter = new ProductFilter();
        [$whereFilters, $whereInFilters] = $filter->getCategoryIds(...$filter->transform($request));
        [$sortBy, $sortOrder] = $filter->sortBy($request);

        // Build query
        $query = Product::where($whereFilters);

        if (!empty($whereInFilters)) {
            foreach ($whereInFilters as $whereInFilter) {
                $query->whereIn($whereInFilter[0], $whereInFilter[2]);
            }
        }

        if ($request->query('includeReviews') === 'true') {
            $query->with('reviews');
        }

        $query->orderBy($sortBy, $sortOrder);
        $perPage = $request->query('perPage', 10);
        // response
        return ProductResource::collection($query->paginate($perPage)->appends($request->query()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = new Product($request->except('categoryId'));
        $product->save();

        return ProductResource::make($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return ProductResource::make($product->loadMissing('reviews'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->except('categoryId'));
        return ProductResource::make($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->reviews()->forceDelete();
        $product->forceDelete();

        return response()->noContent();
    }

    /**
     * Display a listing of the soft deleted resource.
     */
    public function indexSoftDeleted()
    {

        return ProductResource::collection(Product::onlyTrashed()->get());
    }

    /**
     * Display the specified soft deleted resource.
     */
    public function showSoftDeleted($product)
    {
        return ProductResource::collection(Product::onlyTrashed()->where('id', $product)->get());
    }

    /**
     * Soft delete the specified resource.
     */
    public function softDelete($product)
    {
        if ($product = Product::where('id', $product)->first()) {
            $product->delete();
        }
        return response()->noContent();
    }

    /**
     * Restore the specified resource.
     */
    public function restore($product)
    {
        $product = Product::onlyTrashed()->where('id', $product)->first();
        if($product){
            $product->restore();
            return ProductResource::make($product);
        }

        return response()->noContent();
    }
}
