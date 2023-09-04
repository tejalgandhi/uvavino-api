<?php

namespace App\Services;



use Illuminate\Support\Arr;

class Attribute {


    public function __construct() {

    }

    public function setAttribute($c){
        if(Arr::get($c,'model')) {
            $query = $c['model']::query()->select($c['list_attrs']);
            return  $query->get();
        }
    }

}
