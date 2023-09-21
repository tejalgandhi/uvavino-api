<?php


use Illuminate\Support\Facades\Config;

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
