<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BasketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $order_by =$request->order_by;
        $products =  $this->resource->products();
        $products = match($order_by){
        'ascending' => $products->orderBy('wine_name', 'asc'),
            'descending' => $products->orderBy('wine_name', 'desc'),
            'recent' => $products->orderBy('created_at', 'desc'),
            'older' => $products->orderBy('created_at', 'asc'),
//            'expensive' => $products->orderBy('price', 'desc'),
//            'cheapest' => $products->orderBy('price', 'asc'),
            default => $products
        };
        $products = $products->get();
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'description' => $this->resource->description,
            'is_wishlist'=> $this->wishlist_count,
            'stock' => $this->resource->stock,
            'price' => $this->resource->price,
            'weight' => $this->resource->weight,
            'dimensions' => $this->resource->dimensions,
            'products' => ProductResource::collection($products),
        ];
    }

    public static function selectLst() {
        return [
            'baskets.id',
            'baskets.title',
            'baskets.slug',
            'baskets.description',
            'baskets.stock',
            'baskets.price',
            'baskets.weight',
            'baskets.dimensions',
        ];
    }
}
