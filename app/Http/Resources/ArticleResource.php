<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'category' => !empty($this->resource->category)?$this->resource->category->name:'',
            'content' => $this->resource->content,
            'image' => $this->resource->image ?? '',
            'images' => $this->resource->images ?? '' ,
            'videos' => $this->resource->videos ?? '',
            'status' => $this->resource->status,
            'date' => $this->resource->date,
            'documents' => $this->resource->documents,
            'featured' => $this->resource->featured,
            'link' => $this->resource->link,
            'extras' => $this->resource->extras??'',
            'extras_translatable' => $this->resource->extras_translatable??'',
        ];
    }
}
