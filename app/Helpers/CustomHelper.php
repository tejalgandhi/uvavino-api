<?php


use Illuminate\Support\Facades\Config;

if (!function_exists('getBottleSize')) {
    function getBottleSize($key,$field)
    {
        return Config::get('bottle_size.'.$key.'.'.$field);
    }
}
/*
 * This function is used for the backend crud that store the images in the backend
 * and get the images from that folder
 * */
if (!function_exists('getFileUploadPath')) {
    function getFileUploadPath($file)
    {
        if (!empty($file)) {
            return config('constant.upload_url').$file;
        }
    }
}
