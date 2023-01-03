<?php

namespace App\Http\Resources;

class AuthToken extends Success
{
    private $token = '';

    public function __construct($token)
    {
        if(gettype($token) == 'string') {
            $this->token = $token;
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
        return ['token' => $this->token];
    }
}
