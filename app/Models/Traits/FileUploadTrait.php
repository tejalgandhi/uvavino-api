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
            foreach ($params as $param){
                if(!is_string($param)) {
                    $name = time() . rand(1, 100) . '.' . $param->extension();
                    $filePath = Storage::putFileAs($path, $param, $name);
                    $files[] = $filePath;
                }else{
                    $files[] = $param;
                }
            }
        }
        else {
            $name = time().rand(1,100).'.'.$params->extension();
            $filePath = Storage::putFileAs($path, $params,$name);
            $files[] = $filePath;
        }
        Log::info("files " . json_encode([$files]));
        $this->{$field} = $files;
        Log::info("after files");
        $this->save();
        Log::info("saved");
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
