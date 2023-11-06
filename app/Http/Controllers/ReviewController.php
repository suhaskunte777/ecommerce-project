<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\V1\ReviewResource;
use App\Models\Product;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        return ReviewResource::collection($product->reviews);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Product $product, StoreReviewRequest $request)
    {
        $review = new Review();
        $review->user_id = $request->user_id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;

        $product->reviews()->save($review);

        return ReviewResource::make($review);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product, Review $review)
    {
        return new ReviewResource($review->loadMissing(['product', 'user']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Product $product, Review $review)
    {
        $review->update($request->except(['userId', 'productId']));
        return ReviewResource::make($review);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, Review $review)
    {
        $review->forceDelete();
        return response()->noContent();
    }

    /**
     * Display a listing of the soft deleted resource.
     */
    public function indexSoftDeleted(Product $product)
    {
        return ReviewResource::collection($product->reviews()->onlyTrashed()->get());
    }

    /**
     * Display the specified soft deleted resource.
     */
    public function showSoftDeleted($product, $review)
    {
        return ReviewResource::collection(Review::onlyTrashed()->where('product_id', $product)->where('id', $review)->get());
    }

    /**
     * Soft delete the specified resource.
     */
    public function softDelete($product, $review)
    {
        if ($review = Review::where('product_id', $product)->where('id', $review)->first()) {
            $review->delete();
        }
        return response()->noContent();
    }

    /**
     * Restore the specified resource.
     */
    public function restore($product, $review)
    {
        $review = Review::onlyTrashed()->where('product_id', $product)->where('id', $review)->first();

        if ($review) {
            $review->restore();
            return ReviewResource::make($review);
        }

        return response()->noContent();
    }
}
