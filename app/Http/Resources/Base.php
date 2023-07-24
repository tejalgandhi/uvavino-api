<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Base extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    //@codeCoverageIgnoreStart

    public function toArray($request)
    {
        return parent::toArray($request);
    }
    //@codeCoverageIgnoreEnd
    /**
     * Transform the resource into an array.
     *
     * @param  int $status
     * @return \Illuminate\Http\JsonResponse.
     */

    public function json($status = 200) {
        // $res = match(gettype($data)) {
        //     "string", "integer", "double" => ["message" => $data],
        //     default => $data
        // };
        return response()->json($this->toArray(request()), $status);
    }
}
