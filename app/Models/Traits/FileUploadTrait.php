<?php
namespace App\Models\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

trait FileUploadTrait
{
    /**
     * Upload file trait
     *
     * @param string $field
     * @param array $path
     * @return ?string
     */
    public function uploadProductImages($field,$params,$path)
    {
        // Convert path
        $path = $this->destinationPath($path);

        // Make sure path exists
        if (! Storage::exists($path)) {
            Storage::makeDirectory($path);
        }
        // Store file to the storage and save path to database
        $files = [];
        if(is_array($params)){
            foreach ($params as $param){
                $name = time().rand(1,100).'.'.$param->extension();
                $filePath = Storage::putFileAs($path, $param,$name);
                $files[] = $filePath;
            }
        }else {
            $name = time().rand(1,100).'.'.$params->extension();
            $filePath = Storage::putFileAs($path, $params,$name);
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
