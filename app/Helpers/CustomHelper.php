<?php


use Illuminate\Support\Facades\Config;

if (!function_exists('getBottleSize')) {
    function getBottleSize($key,$field)
    {
        return Config::get('bottle_size.'.$key.'.'.$field);
    }
}

