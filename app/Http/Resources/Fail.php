<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Fail extends Base
{
    private $message = 'Operation Successfull';

    public function __construct($data)
    {
        if(gettype($data) == 'string') {
            $this->message = $data;
        }
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return ["message" => $this->message];
    }

    public function json($status = 400) {
        return parent::json($status);
    } 
}
