<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'image' => $this->images,
            'available' => $this->availability ? "yes" : "no",
            'description' => $this->description,
            'rating' => $this->whenLoaded('reviews', fn() => $this->reviews->avg('rating')),
            'category' => new CategoryResource($this->category),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
        ];
    }
}


