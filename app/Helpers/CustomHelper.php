<?php


if (!function_exists('getFileUploadPath')) {
    function getFileUploadPath($files = [])
    {
        $images = [];
        if (!empty($files)) {
            $f = json_decode($files);
            foreach ($f as $file){
                $images[] =  storage_path('app/'.$file);
            }
        }
        return $images;
    }
}

