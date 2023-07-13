<?php


if (!function_exists('getFileUploadPath')) {
    function getFileUploadPath($file)
    {
        if (!empty($file)) {
            return config('constant.upload_url').$file;
        }
    }
}
