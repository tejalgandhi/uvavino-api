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
        $endPoint = config('filesystems.disks.default.endpoint');
        $path = $this->destinationPath($path);
        // Make sure path exists
        Log::info($endPoint);
        Log::info('$path');
        Log::info($path);
//        Log::info(Storage::disk('do')->exists($path));
//         if (! Storage::disk('do')->exists($path)) {
//             Storage::disk('do')->makeDirectory($path);
//         }

        //remove Old Files
        $file_paths = $this->{$field};
        Log::info('$file_paths');
        Log::info($file_paths);
         if(!empty($file_paths)){
             foreach ((array)$file_paths as $file_path) {
                 Log::info('$file_paths');
                 Log::info($file_paths);
                 Log::info('file_p');
                 Log::info($file_path);
                 Log::info('params');
                 Log::info($params);
                 // && Storage::disk('do')->exists($file_path)
                 if (is_array($params) && !in_array($file_path,$params)) {
                         Storage::disk('do')->delete($file_path);
                         Log::info('delete file');
                         Log::info($file_path);
                     }
             }
         }
        // Store file to the storage and save path to database
        $files = [];
        Log::info('$params');
        Log::info($params);
        if(is_array($params)){
            foreach ($params as $param){
                if(!is_string($param)) {
                    $name = time() . rand(1, 100) . '.' . $param->extension();
                    $filePath = Storage::putFileAs($path, $param, $name);
                    $files[] = $filePath;
                }else{
                    Log::info('param');
                    Log::info($param);
                    Log::info($endPoint);
                    $f = str_replace($endPoint,"",$param);
                    $files[] = $param;
                }
            }
        }
        else {
            $name = time().rand(1,100).'.'.$params->extension();
            $filePath = Storage::putFileAs($path, $params,$name);
            $files = $filePath;
        }
        Log::info("files " . json_encode([$files]));
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
