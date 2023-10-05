<?php
namespace App\Models\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Illuminate\Support\Facades\Log;

trait FileUploadTrait
{
    /**
     * Upload file trait
     *
     * @param string $field
     * @param array $path
     * @return ?string
     */
    public function uploadImages($field,$params,$path)
    {
        // Convert path
        $path = $this->destinationPath($path);
        Log::info($path);
        // Make sure path exists
        // if (! Storage::exists($path)) {
        //     Storage::makeDirectory($path);
        // }

        //remove Old Files
        $file_paths = $this->{$field};
        if(!empty($file_paths)){
            foreach ($file_paths as $file_path) {
                $storagePath = str_replace(env('APP_URL') . '/storage', '', $file_path);
                $checkPath = str_replace(env('APP_URL') . '/storage/', '', $file_path);
                if (!in_array($checkPath,$params) && Storage::exists($storagePath)) {
                        Storage::delete($storagePath);
                    }
            }
        }
        // Store file to the storage and save path to database
        $files = [];
        
        if(is_array($params)){
            Log::info("if");
            foreach ($params as $param){
                if(!is_string($param)) {
                    $name = time() . rand(1, 100) . '.' . $param->extension();
                    // Log::info("bucket" . Storage::disk('do')->getAdapter()->getBucket());
                    Log::info("path" . $path);
                    Log::info("params" . $params);
                    Log::info("name" . $name);
                    $filePath = Storage::putFileAs($path, $param, $name);
                    Log::info("path" . $path);
                    Log::info("params" . $param);
                    Log::info("name" . $name);
                    Log::info("filepath" .  $filePaths  );
                    $files[] = $filePath;
                }else{
                    $files[] = $param;
                }
            }
        }
        else {
            Log::info("else");
            $name = time().rand(1,100).'.'.$params->extension();
            // Log::info("bucket" . Storage::disk('do')->getAdapter()->getBucket());
            Log::info("path" . $path);
            Log::info("params" . $params);
            Log::info("name" . $name);
            $filePath = Storage::putFileAs($path, $params,$name);
            Log::info("path" . $path);
            Log::info("params" . $param);
            Log::info("name" . $name);
            Log::info("filepath" .  $filePaths  );
            $files[] = $filePath;
        }

        $this->{$field} = $files;
        $this->save();
    }
    /**
     * Generate destination folder for saving files
     *
     * @param array $args
     * @return string
     */
    private function destinationPath(array $args): string
    {
        return join('/', [
            ...$args,
        ]);
    }

}
