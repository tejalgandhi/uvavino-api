<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class Success extends Base
{
    private $data = [];

    public function __construct($data)
    {
        $message = 'Operation Successfull';
        switch (gettype($data)) {
            case 'string':
                $message = $data;
                $this->data = compact('message');
                break;
            default:
                $this->data = $data;
                break;
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
        return match (true) 
        {
            gettype($this->data) == 'array' => $this->data,
            method_exists($this->data, 'toArray') => $this->data->toArray(),
            default => (array) $this->data
        };
    }
}
